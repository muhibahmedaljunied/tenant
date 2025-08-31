<?php

namespace App\Services\JournalEntries;

use App\Models\AcSetting;

class SaleEntriesPosService
{
    use RecordEntriesTransaction;
    public function storeAllEntryDetails($transaction)
    {
        $entrySaleInvoice = $this->storeEntry(
            $transaction->transaction_date,
            sprintf('فاتورة مبيعات #%s', $transaction->invoice_no),
            $transaction->branch_cost_center_id,
            $transaction
        );

        $entryInventory = $this->storeEntry(
            $transaction->transaction_date,
            "قيد مخزون او تكلفة البضاعة المباعة",
            $transaction->branch_cost_center_id,
            $transaction
        );

        return [
            'sales' => $entrySaleInvoice,
            'inventory' => $entryInventory,
        ];
    }
    public function storeReturnTransactionsEntries($transaction, $sell, $invoice_total, $returnInvoicePrices, $contact)
    {
        $ac_setting = AcSetting::first();
        if ($ac_setting) {
            $description = sprintf("فاتورة %s", "مرتجع مبيعات #{$transaction->invoice_no}");
            $ac_journal_entry_record_sale = $this->storeEntry(
                now(),
                $description,
                $sell->branch_cost_center_id,
                $transaction
            );
            $this->storeEntryDetails(
                $ac_journal_entry_record_sale['id'],
                $ac_setting->sales_services_return,
                $returnInvoicePrices['total_before_tax'] * -1,
                $sell->extra_cost_center_id
            );
            $this->storeEntryDetails(
                $ac_journal_entry_record_sale['id'],
                $ac_setting->vat_due,
                abs($returnInvoicePrices['products_tax']) * -1,
                $sell->extra_cost_center_id
            );

            $this->storeEntryDetails(
                $ac_journal_entry_record_sale['id'],
                $contact->account_number_customer,
                round(abs($invoice_total['final_total']), 2) * -1,
                $sell->extra_cost_center_id
            );
        }
    }
    // ----------------------------------------- \\
    public function returnedSellProducts($transaction, $sumPricesPurchases, $sell)
    {
        $ac_setting = AcSetting::first();
        if ($ac_setting) {
            $entry = $this->storeEntry(
                $transaction->transaction_date,
                "قيد مخزون او تكلفة البضاعة المرتجعة",
                $transaction->branch_cost_center_id,
                $transaction
            );
            // inventory

            $this->storeEntryDetails(
                $entry->id,
                $ac_setting->inventory,
                $sumPricesPurchases,
                $sell->extra_cost_center_id
            );
            $this->storeEntryDetails(
                $entry->id,
                $ac_setting->sold_goods_cost,
                -$sumPricesPurchases,
                $sell->extra_cost_center_id
            );
        }
        // ----------------------------------------- \\
    }

}