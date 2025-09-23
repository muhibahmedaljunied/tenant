<?php

namespace App\Utils;

use App\Contact;
use App\Models\AcJournalEntryDetail;
use App\Transaction;
use App\Utils\TransactionUtil;
use Illuminate\Support\Facades\DB;

class ContactUtil extends Util
{
    private static $contactTransactions = null;

    /**
     * Returns Walk In Customer for a Business
     *
     * @param int $business_id
     *
     * @return array|null
     */
    public function getWalkInCustomer($business_id, $array = true)
    {
        $contact = Contact::whereIn('type', ['customer', 'both'])
            ->where('contacts.business_id', $business_id)
            ->where('contacts.is_default', 1)
            ->leftjoin('customer_groups as cg', 'cg.id', '=', 'contacts.customer_group_id')
            ->select(
                'contacts.*',
                'cg.amount as discount_percent',
                'cg.price_calculation_type',
                'cg.selling_price_group_id'
            )
            ->first();

        if (! empty($contact)) {
            // $contact->contact_address = $contact->contact_address;
            $output = $array ? $contact->toArray() : $contact;
            return $output;
        } else {
            return null;
        }
    }

    /**
     * Returns the customer group
     *
     * @param int $business_id
     * @param int $customer_id
     *
     * @return array|Contact
     */
    public function getCustomerGroup($business_id, $customer_id)
    {
        if (empty($customer_id)) {
            return [];
        }

        return Contact::leftjoin('customer_groups as CG', 'contacts.customer_group_id', 'CG.id')
            ->where('contacts.id', $customer_id)
            ->where('contacts.business_id', $business_id)
            ->select('CG.*')
            ->first();
    }

    /**
     * Returns the contact info
     *
     * @param int $business_id
     * @param int $contact_id
     *
     * @return Contact
     */
    public function getContactInfo($business_id, $contact_id)
    {

        // -------------------muhib update this query for sqlserver--------------------------------- 

        return  Contact::where('contacts.id', $contact_id)
            ->where('contacts.business_id', $business_id)
            ->join('transactions AS t', 'contacts.id', '=', 't.contact_id')
            ->leftJoin('transaction_payments AS tp', 'tp.transaction_id', '=', 't.id')
            ->with(['business'])
            ->select(
                DB::raw("SUM(CASE WHEN t.type = 'purchase' THEN t.final_total ELSE 0 END) AS total_purchase"),
                DB::raw("SUM(CASE WHEN t.type = 'sell' AND t.status = 'final' THEN t.final_total ELSE 0 END) AS total_invoice"),
                DB::raw("SUM(CASE WHEN t.type = 'purchase' THEN tp.amount ELSE 0 END) AS purchase_paid"),
                DB::raw("SUM(CASE WHEN t.type = 'sell' AND t.status = 'final' THEN 
            CASE WHEN tp.is_return = 1 THEN -1 * tp.amount ELSE tp.amount END 
        ELSE 0 END) AS invoice_received"),
                DB::raw("SUM(CASE WHEN t.type = 'opening_balance' THEN t.final_total ELSE 0 END) AS opening_balance"),
                DB::raw("SUM(CASE WHEN t.type = 'opening_balance' THEN tp.amount ELSE 0 END) AS opening_balance_paid"),
                'contacts.id',
                'contacts.business_id',
                'contacts.type',
                'contacts.name',
                'contacts.prefix',
                'contacts.first_name',
                'contacts.last_name',
                'contacts.contact_id',
                'contacts.email',
                'contacts.middle_name',
                'contacts.created_by',
                'contacts.supplier_business_name',
                'contacts.contact_status',
                'contacts.tax_number',
                'contacts.city',
                'contacts.state',
                'contacts.country',
                'contacts.address_line_1',
                'contacts.address_line_2',
                'contacts.zip_code',
                'contacts.dob',
                'contacts.mobile',
                'contacts.landline',
                'contacts.alternate_number',
                'contacts.pay_term_number',
                'contacts.pay_term_type',
                'contacts.credit_limit',
                'contacts.converted_by',
                'contacts.converted_on',
                'contacts.balance',
                'contacts.total_rp',
                'contacts.total_rp_used',
                'contacts.total_rp_expired',
                'contacts.is_default',
                'contacts.shipping_address',
                'contacts.position',
                'contacts.customer_group_id',
                'contacts.crm_source',
                'contacts.crm_life_stage',
                'contacts.custom_field1',
                'contacts.custom_field2',
                'contacts.custom_field3',
                'contacts.custom_field4',
                'contacts.custom_field5',
                'contacts.custom_field6',
                'contacts.custom_field7',
                'contacts.custom_field8',
                'contacts.custom_field9',
                'contacts.custom_field10',
                'contacts.deleted_at',
                'contacts.created_at',
                'contacts.updated_at',
                'contacts.price_group_id',
                'contacts.account_number_customer',
                'contacts.account_number_supplier',
                'contacts.province_id',
                'contacts.sector_id',
                'contacts.distribution_area_id',
                'contacts.track_id',
                'contacts.location',
                'contacts.commercial_registration_number',
            )->groupBy(

                'contacts.id',
                'contacts.business_id',
                'contacts.type',
                'contacts.name',
                'contacts.prefix',
                'contacts.first_name',
                'contacts.last_name',
                'contacts.contact_id',
                'contacts.email',
                'contacts.middle_name',
                'contacts.created_by',
                'contacts.supplier_business_name',
                'contacts.contact_status',
                'contacts.tax_number',
                'contacts.city',
                'contacts.state',
                'contacts.country',
                'contacts.address_line_1',
                'contacts.address_line_2',
                'contacts.zip_code',
                'contacts.dob',
                'contacts.mobile',
                'contacts.landline',
                'contacts.alternate_number',
                'contacts.pay_term_number',
                'contacts.pay_term_type',
                'contacts.credit_limit',
                'contacts.converted_by',
                'contacts.converted_on',
                'contacts.balance',
                'contacts.total_rp',
                'contacts.total_rp_used',
                'contacts.total_rp_expired',
                'contacts.is_default',
                'contacts.shipping_address',
                'contacts.position',
                'contacts.customer_group_id',
                'contacts.crm_source',
                'contacts.crm_life_stage',
                'contacts.custom_field1',
                'contacts.custom_field2',
                'contacts.custom_field3',
                'contacts.custom_field4',
                'contacts.custom_field5',
                'contacts.custom_field6',
                'contacts.custom_field7',
                'contacts.custom_field8',
                'contacts.custom_field9',
                'contacts.custom_field10',
                'contacts.deleted_at',
                'contacts.created_at',
                'contacts.updated_at',
                'contacts.price_group_id',
                'contacts.account_number_customer',
                'contacts.account_number_supplier',
                'contacts.province_id',
                'contacts.sector_id',
                'contacts.distribution_area_id',
                'contacts.track_id',
                'contacts.location',
                'contacts.commercial_registration_number',

            )
            ->first();
    }

    public function createNewContact($input)
    {
        //Check Contact id
        $count = 0;
        if (! empty($input['contact_id'])) {
            $count = Contact::where('business_id', $input['business_id'])
                ->where('contact_id', $input['contact_id'])
                ->count();
        }

        if ($count == 0) {

            //Update reference count
            $ref_count = $this->setAndGetReferenceCount('contacts', $input['business_id']);

            if (empty($input['contact_id'])) {
                //Generate reference number
                $input['contact_id'] = $this->generateReferenceNumber('contacts', $ref_count, $input['business_id']);
            }

            $opening_balance = isset($input['opening_balance']) ? $input['opening_balance'] : 0;
            if (isset($input['opening_balance'])) {
                unset($input['opening_balance']);
            }

            $contact = Contact::create($input);

            //Add opening balance
            if (! empty($opening_balance)) {
                $transactionUtil = new TransactionUtil();
                $transactionUtil->createOpeningBalanceTransaction($contact->business_id, $contact->id, $opening_balance, $contact->created_by, false);
            }

            $output = [
                'success' => true,
                'data' => $contact,
                'msg' => __("contact.added_success")
            ];
            return $output;
        } else {
            throw new \Exception("Error Processing Request", 1);
        }
    }
    public function getContactAccountantDueDetails($accountNumber): array
    {
        $account = AcJournalEntryDetail::where('account_number', $accountNumber)->select('amount', 'account_number')->with('account_type:account_number,account_type', 'ac_journal_entry')->get();
        $positive = $account->where('amount', '>', 0)->sum(function ($entry) {
            return $entry->account_type->account_type == 'creditor' && $entry['amount'] > 0  ? $entry['amount'] * -1 : $entry['amount'];
        });
        $negative = $account->where('amount', '<', 0)->sum(function ($entry) {
            return $entry->account_type->account_type == 'debtor' &&  $entry['amount'] > 0 ? $entry['amount'] * -1 : $entry['amount'];
        });

        return [
            'all' => [
                'total_invoice' => $positive,
                'total_paid' => -$negative,
            ],
            'excluded' => [
                'total_invoice' => $positive,
                'total_paid' => -$negative,
            ],
            'due' => 0
        ];
    }
    public function updateContact($input, $id, $business_id)
    {
        $count = 0;
        //Check Contact id
        if (! empty($input['contact_id'])) {
            $count = Contact::where('business_id', $business_id)
                ->where('contact_id', $input['contact_id'])
                ->where('id', '!=', $id)
                ->count();
        }

        if ($count == 0) {
            //Get opening balance if exists
            $ob_transaction = Transaction::where('contact_id', $id)
                ->where('type', 'opening_balance')
                ->first();
            $opening_balance = isset($input['opening_balance']) ? $input['opening_balance'] : 0;

            if (isset($input['opening_balance'])) {
                unset($input['opening_balance']);
            }

            $contact = Contact::where('business_id', $business_id)->findOrFail($id);
            foreach ($input as $key => $value) {
                $contact->$key = $value;
            }
            $contact->save();

            $transactionUtil = new TransactionUtil();
            if (! empty($ob_transaction)) {
                $opening_balance_paid = $transactionUtil->getTotalAmountPaid($ob_transaction->id);
                if (! empty($opening_balance_paid)) {
                    $opening_balance += $opening_balance_paid;
                }

                $ob_transaction->final_total = $opening_balance;
                $ob_transaction->save();
                //Update opening balance payment status
                $transactionUtil->updatePaymentStatus($ob_transaction->id, $ob_transaction->final_total);
            } else {
                //Add opening balance
                if (! empty($opening_balance)) {
                    $transactionUtil->createOpeningBalanceTransaction($business_id, $contact->id, $opening_balance, $contact->created_by, false);
                }
            }

            $output = [
                'success' => true,
                'msg' => __("contact.updated_success"),
                'data' => $contact
            ];
        } else {
            throw new \Exception("Error Processing Request", 1);
        }

        return $output;
    }
    /**
     * Retrieves sum of due amount of a contact
     * @param int $contact_id
     *
     * @return mixed
     */
    protected function getContactTransactionsDetails($contact_id, $types = ['sell', 'opening_balance', 'purchase'])
    {
        if (is_null(self::$contactTransactions)) {
            self::$contactTransactions = Transaction::where('contact_id', $contact_id)
                ->whereIn('transactions.type', $types)
                ->select(
                    'transactions.status',
                    'transactions.id',
                    'transactions.type',
                    'transactions.status',
                    'transactions.final_total',
                )->with('payment_lines:id,transaction_id,amount,is_return')->get();
        }
        return self::$contactTransactions;
    }

    public function getContactDueDetails($contact_id)
    {
        $contact_payments = $this->getContactTransactionsDetails($contact_id);
        $result = $this->collectContactBalanceDetails($contact_payments);
        return $result;
    }

    private function collectContactBalanceDetails($listTranscations)
    {
        $result = [
            'opening_balance' => $listTranscations->where('type', 'opening_balance')->sum('final_total'),
            'opening_balance_paid' => $listTranscations->where('type', 'opening_balance')->sum(function ($item) {
                return $item->payment_lines->sum(function ($paymentLine) {
                    return $paymentLine->amount;
                });
            }),
            'total_invoice' => $listTranscations->where('status', 'final')->where('type', 'sell')->sum('final_total'),
            'total_paid' => $listTranscations->where('status', 'final')->where('type', 'sell')->sum(function ($item) {
                return $item->payment_lines->sum(function ($paymentLine) {
                    return $paymentLine->is_return ? $paymentLine->amount * -1 : $paymentLine->amount;
                });
            }),
            'total_purchase' => $listTranscations->where('status', 'final')->where('type', 'purchase')->sum('final_total'),
            'purchase_paid' => $listTranscations->where('type', 'purchase')->sum(function ($item) {
                return $item->payment_lines->sum(function ($paymentLine) {
                    return $paymentLine->amount;
                });
            }),
        ];
        return $result;
    }

    public function getContactDue($contact_id)
    {
        $contact_payments = $this->getContactDueDetails($contact_id);
        return $contact_payments['total_invoice'] + $contact_payments['total_purchase'] - $contact_payments['total_paid'] - $contact_payments['purchase_paid'] + $contact_payments['opening_balance'] - $contact_payments['opening_balance_paid'];
    }

    public function getContactSellDueDetails($contact_id, $excludeTransactionIds = []): array
    {
        $excludeTransactionIds = (array) $excludeTransactionIds;
        $all = $this->getContactDueDetails($contact_id);
        $listTranscations = $this->getContactTransactionsDetails($contact_id)->whereNotIn('id', $excludeTransactionIds);
        $result = $this->collectContactBalanceDetails($listTranscations);

        return [
            'excluded' => $result,
            'all' => $all,
            'due' => $this->getContactDue($contact_id)
        ];
    }
    public function getContactQuery($business_id, $type, $contact_ids = [])
    {


        $query = Contact::leftJoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
            ->leftJoin('customer_groups AS cg', 'contacts.customer_group_id', '=', 'cg.id')
            ->leftJoin(DB::raw("
        (SELECT transaction_id, 
                SUM(amount) AS total_paid,
                SUM(CASE WHEN is_return = 1 THEN -1 * amount ELSE amount END) AS net_paid
         FROM transaction_payments
         GROUP BY transaction_id
        ) AS tp
    "), 't.id', '=', 'tp.transaction_id')
            ->where('contacts.business_id', $business_id);

        if ($type == 'supplier') {
            $query->onlySuppliers();
        } elseif ($type == 'customer') {
            $query->onlyCustomers();
        }

        if (! empty($contact_ids)) {
            $query->whereIn('contacts.id', $contact_ids);
        }

        $query->select([
            'contacts.*',
            'cg.name as customer_group',
            DB::raw("SUM(CASE WHEN t.type = 'opening_balance' THEN ISNULL(tp.net_paid, 0) ELSE 0 END) AS opening_balance_paid"),
            DB::raw("SUM(CASE WHEN t.type = 'opening_balance' THEN final_total ELSE 0 END) AS opening_balance"),
        ]);

        if (in_array($type, ['supplier', 'both'])) {
            $query->addSelect([
                DB::raw("SUM(CASE WHEN t.type = 'purchase' THEN final_total ELSE 0 END) AS total_purchase"),
                DB::raw("SUM(CASE WHEN t.type = 'purchase_return' THEN final_total ELSE 0 END) AS total_purchase_return"),
                DB::raw("SUM(CASE WHEN t.type = 'purchase' THEN ISNULL(tp.total_paid, 0) ELSE 0 END) AS purchase_paid"),
                DB::raw("SUM(CASE WHEN t.type = 'purchase_return' THEN ISNULL(tp.total_paid, 0) ELSE 0 END) AS purchase_return_paid"),
            ]);
        }

        if (in_array($type, ['customer', 'both'])) {
            $query->addSelect([
                DB::raw("SUM(CASE WHEN t.type = 'sell' AND t.status = 'final' THEN final_total ELSE 0 END) AS total_invoice"),
                DB::raw("SUM(CASE WHEN t.type = 'sell_return' THEN final_total ELSE 0 END) AS total_sell_return"),
                DB::raw("SUM(CASE WHEN t.type = 'sell' AND t.status = 'final' THEN ISNULL(tp.net_paid, 0) ELSE 0 END) AS invoice_received"),
                DB::raw("SUM(CASE WHEN t.type = 'sell_return' THEN ISNULL(tp.total_paid, 0) ELSE 0 END) AS sell_return_paid"),
            ]);
        }


        $query->groupBy(
            'contacts.id',
            'contacts.business_id',
            'contacts.type',
            'contacts.name',
            'contacts.prefix',
            'contacts.first_name',
            'contacts.last_name',
            'contacts.contact_id',
            'contacts.email',
            'contacts.middle_name',
            'contacts.created_by',
            'contacts.supplier_business_name',
            'contacts.contact_status',
            'contacts.tax_number',
            'contacts.city',
            'contacts.state',
            'contacts.country',
            'contacts.address_line_1',
            'contacts.address_line_2',
            'contacts.zip_code',
            'contacts.dob',
            'contacts.mobile',
            'contacts.landline',
            'contacts.alternate_number',
            'contacts.pay_term_number',
            'contacts.pay_term_type',
            'contacts.credit_limit',
            'contacts.converted_by',
            'contacts.converted_on',
            'contacts.balance',
            'contacts.total_rp',
            'contacts.total_rp_used',
            'contacts.total_rp_expired',
            'contacts.is_default',
            'contacts.shipping_address',
            'contacts.position',
            'contacts.customer_group_id',
            'contacts.crm_source',
            'contacts.crm_life_stage',
            'contacts.custom_field1',
            'contacts.custom_field2',
            'contacts.custom_field3',
            'contacts.custom_field4',
            'contacts.custom_field5',
            'contacts.custom_field6',
            'contacts.custom_field7',
            'contacts.custom_field8',
            'contacts.custom_field9',
            'contacts.custom_field10',
            'contacts.deleted_at',
            'contacts.created_at',
            'contacts.updated_at',
            'contacts.price_group_id',
            'contacts.account_number_customer',
            'contacts.account_number_supplier',
            'contacts.province_id',
            'contacts.sector_id',
            'contacts.distribution_area_id',
            'contacts.track_id',
            'contacts.location',
            'contacts.commercial_registration_number',
            'cg.name'


        );

        return $query;
    }
}
