<?php

namespace App\Listeners;

use App\AccountTransaction;
use App\Contact;

use App\Events\TransactionPaymentAdded;
use App\Models\{
    AcJournalEntry,
    AcSetting,
    AcMaster,
    AcJournalEntryDetail
};

use App\Utils\{
    ModuleUtil,
    TransactionUtil
};
use App\Services\JournalEntries\RecordEntriesTransaction;

class AddAccountTransaction
{
    use RecordEntriesTransaction;
    protected $transactionUtil;
    protected $formInputs;
    protected $moduleUtil;

    /**
     * Constructor
     *
     * @param TransactionUtil $transactionUtil
     * @return void
     */
    public function __construct(TransactionUtil $transactionUtil, ModuleUtil $moduleUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
    }

    private function transactionDescription($transaction, $type): string
    {
        $transaction = optional($transaction);
        switch ($type) {
            case 'purchase':
                return "مشتريات #{$transaction->ref_no}";
            case 'purchase_return':
                return "مرتجع مشتريات #{$transaction->ref_no}";
            case 'sell':
                return "مبيعات #{$transaction->invoice_no}";
            case 'sell_return':
                return "مرتجع مبيعات #{$transaction->ref_no}";
        }
        return '';
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(TransactionPaymentAdded $event)
    {
        $this->formInputs = $event->formInput;
        $isPosSale = ! in_array(
            $event->formInput['transaction_type'],
            ['purchase', 'purchase_return']
        ) !== '' && ! ($event->transactionPayment->transaction !== null ? $event->transactionPayment->transaction->is_direct_sale : false);
        if ($event->transactionPayment->method == 'advance') {
            $this->transactionUtil->updateContactBalance(
                $event->transactionPayment->payment_for,
                $event->transactionPayment->amount,
                'deduct'
            );
        }

        if (! $this->moduleUtil->isModuleEnabled('account')) {
            return;
        }


        // //Create new account transaction
        if (! empty($event->formInput['account_id']) && $event->transactionPayment->method != 'advance') {

            $type = ! empty($event->transactionPayment->payment_type) ? $event->transactionPayment->payment_type : AccountTransaction::getAccountTransactionType($event->formInput['transaction_type']);
            $account_transaction_data = [
                'amount' => $event->formInput['amount'],
                'account_id' => $event->formInput['account_id'],
                'type' => $type,
                'operation_date' => $event->transactionPayment->paid_on,
                'created_by' => $event->transactionPayment->created_by,
                'transaction_id' => $event->transactionPayment->transaction_id,
                'transaction_payment_id' => $event->transactionPayment->id
            ];

            //If change return then set type as debit
            if ($event->formInput['transaction_type'] == 'sell' && isset($event->formInput['is_return']) && $event->formInput['is_return'] == 1) {
                $account_transaction_data['type'] = 'debit';
            }
            $account = AcMaster::find($event->transactionPayment->account_id);
            $contact = Contact::find($event->transactionPayment->payment_for);

            $transaction = $event->transactionPayment->transaction;
            $event->transactionPayment->load('child_payments');

            $is_purchase = ($event->formInput['transaction_type'] == "purchase" || $event->formInput['transaction_type'] == "purchase_return");
            $is_return = ($event->formInput['transaction_type'] == "sell_return" || $event->formInput['transaction_type'] == "purchase_return");

            $ExtraBranchId = $transaction->extra_cost_center_id ?? $event->formInput['extra_branch_id'] ?? null;
            $invoiceDescriptionTitle = $this->transactionDescription($transaction, $event->formInput['transaction_type']);
            $branchCostCenterId = $transaction->branch_cost_center_id ?? $event->formInput['branch_id'] ?? null;
            $journalDescription = sprintf(
                "مدفوعات فاتورة %s",
                $invoiceDescriptionTitle
            );

            $journalEntry = $this->storeEntry(
                $event->transactionPayment->paid_on,
                $journalDescription,
                $branchCostCenterId
            );

            $amount = $event->transactionPayment->amount;

            if ($event->formInput['transaction_type'] == "purchase" && optional($transaction)->payment_status == "due") {
                $amount = $amount * -1;
            }
            $amount = ($is_return && $is_purchase) ? ($amount) : $amount * -1;
            //add journal_entry hire inv
            if ($account) {
                $this->createFirstEntry($account, $journalEntry, $is_purchase, $amount, $is_return, $ExtraBranchId);

                $this->createSecondEntry($account, $contact, $journalEntry, $is_purchase, $amount, $is_return, $ExtraBranchId);
            }

            AccountTransaction::createAccountTransaction($account_transaction_data);
        } else {
            if ($event->transactionPayment->amount > 0) {
                switch ($event->formInput['transaction_type']) {
                    case 'purchase':
                        return $this->handlePurchasePayment($event);
                    case 'purchase_return':
                        return $this->handlePurchaseReturnPayment($event);
                }

                if ($isPosSale) {
                    return $this->handlePosSale($event);
                }
            }
        }
    }

    private function handlePosSale(TransactionPaymentAdded $event)
    {
        $settings = AcSetting::where('business_id', $event->transactionPayment->business_id)->first()->toArray();

        $method = $settings["payment_method_{$event->transactionPayment->method}"];
        $account = AcMaster::where('account_number', $method)->first();
        $contact = Contact::find($event->transactionPayment->payment_for);
        $amount = $event->transactionPayment->amount;
        if ($account) {
            $ac_journal_entry_record_sale = $this->storeEntry(
                $event->transactionPayment->paid_on,
                "مدفوعات نقاط بيع",
                $settings['branch_cost_center_id'],
                $event->transactionPayment->transaction
            );

            $this->storeEntryDetails(
                $ac_journal_entry_record_sale->id,
                $account->account_number,
                $amount,
                $settings['extra_cost_center_id']
            );
            $this->storeEntryDetails(
                $ac_journal_entry_record_sale->id,
                $contact->account_number_customer,
                -$amount,
                $settings['extra_cost_center_id']
            );
        }
    }
    private function handlePurchasePayment(TransactionPaymentAdded $event): void
    {
        $settings = AcSetting::first()->toArray();

        $method = $settings["payment_method_{$event->transactionPayment->method}"];
        $account = AcMaster::where('account_number', $method)->first();
        $contact = Contact::find($event->transactionPayment->payment_for);
        $amount = $event->transactionPayment->amount;
        if ($account) {

            $ac_journal_entry_record_sale = $this->storeEntry(
                $event->transactionPayment->paid_on,
                "مدفوعات فاتورة المشتريات",
                $settings['branch_cost_center_id']
            );
            $this->storeEntryDetails(
                $ac_journal_entry_record_sale->id,
                $account->account_number,
                -$amount,
                $settings['extra_cost_center_id']
            );
            $this->storeEntryDetails(
                $ac_journal_entry_record_sale->id,
                $contact->account_number_supplier,
                -$amount,
                $settings['extra_cost_center_id']
            );
        }
    }
    private function handlePurchaseReturnPayment(TransactionPaymentAdded $event): void
    {
        $settings = AcSetting::first()->toArray();

        $method = $settings["payment_method_{$event->transactionPayment->method}"];
        $account = AcMaster::where('account_number', $method)->first();
        $contact = Contact::find($event->transactionPayment->payment_for);
        $amount = $event->transactionPayment->amount;
        if ($account) {
            $ac_journal_entry_record_sale = $this->storeEntry(
                $event->transactionPayment->paid_on,
                "مدفوعات فاتورة مرتجع المشتريات",
                $settings['branch_cost_center_id'],
            );
            $this->storeEntryDetails(
                $ac_journal_entry_record_sale['id'],
                $account->account_number,
                $amount,
                $settings['extra_cost_center_id']
            );
            $this->storeEntryDetails(
                $ac_journal_entry_record_sale['id'],
                $contact->account_number_supplier,
                $amount,
                $settings['extra_cost_center_id']
            );
        }
    }


    private function createFirstEntry($account, $journalEntry, $is_purchase, $amount, $is_return, $extra_cost_center_id)
    {
        $entryAmount = $amount * ($is_return ? 1 : -1);
        if ($is_purchase) {
            $entryAmount = $amount * ($is_return ? -1 : 1);
        }

        return $this->storeEntryDetails(
            $journalEntry->id,
            $account->account_number,
            $entryAmount,
            $extra_cost_center_id
        );
    }

    private function createSecondEntry($account, $contact, $journalEntry, $is_purchase, $amount, $is_return, $extra_cost_center_id)
    {
        $data = [
            'account_number' => $is_purchase ? $contact->account_number_supplier : $contact->account_number_customer,
            'ac_journal_entries_id' => $journalEntry->id,
            'cost_cen_field_id' => $extra_cost_center_id
        ];

        if ($is_purchase) {
            $data['amount'] = $amount * ($is_return ? 1 : -1);
        } else {
            $data['amount'] = $amount * ($is_return ? -1 : 1);
        }

        return $this->storeEntryDetails(
            $journalEntry->id,
            $data['account_number'],
            $data['amount'],
            $extra_cost_center_id
        );
    }
}
