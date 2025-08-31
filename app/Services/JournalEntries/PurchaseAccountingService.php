<?php

namespace App\Services\JournalEntries;

use App\Contact;
use App\Product;
use App\TaxRate;
use App\Models\AcSetting;
use App\Models\AcJournalEntry;
use Illuminate\Support\Carbon;
use App\Models\AcJournalEntryDetail;

class PurchaseAccountingService
{
    use RecordEntriesTransaction;
    public function storeCreatePurchase($transaction, $products)
    {

        $ac_setting = AcSetting::first();
   
        $contact_details = Contact::whereId($transaction->contact_id)->first();
        $total_entry_amount = 0;
        $totalProductsTaxes = 0;
        // ----------------------------------------- \\
        $accounts = isset(
            $ac_setting,
            $contact_details['account_number_supplier'],
            $ac_setting['vat_due'],
            $ac_setting['inventory']
        );
        // dd($ac_setting,$contact_details['account_number_supplier'],$ac_setting['vat_due'],$ac_setting['inventory']);
        // ----------------------------------------- \\
        if ($accounts) {
            $entryFormatedDate = $transaction->transaction_date;
            $entryFormatedDate = ($entryFormatedDate instanceof Carbon) ? $entryFormatedDate->toDateString() : Carbon::parse($entryFormatedDate)->toDateString();
            // ----------------------------------------- \\
            $description = "قيد مخزون لفاتورة مشتريات #{$transaction->invoice_no}" . $entryFormatedDate;
            // ----------------------------------------- \\
            $record = $this->storeEntry(
                $transaction->transaction_date,
                $description,
                $transaction->branch_cost_center_id,
                $transaction
            );
            // ----------------------------------------- \\
            foreach ($products as $product) {
                // ----------------------------------------- \\
                $newInvoicePricesAfterDiscount = $this->storeProductsEntryDetails(
                    $product,
                    $record->id,
                    $ac_setting->inventory,
                    $transaction
                );
                // ----------------------------------------- \\
                //for suppliers
                if (is_string($product['purchase_price_inc_tax'])) {
                    $product['purchase_price_inc_tax'] = (float) str_replace(',', '', $product['purchase_price_inc_tax']);
                }
                if (is_string($product['purchase_price'])) {
                    $product['purchase_price'] = (float) str_replace(',', '', $product['purchase_price']);
                }
                // ----------------------------------------- \\
                $total_entry_amount += array_sum($newInvoicePricesAfterDiscount);
                $totalProductsTaxes += abs($newInvoicePricesAfterDiscount['product_tax']);
                // ----------------------------------------- \\
            }
            if ($contact_details->account_number_supplier && $ac_setting->vat_due) {
                // ----------------------------------------- \\
                $totalTaxes = $totalProductsTaxes + $transaction->tax;
                //add journal_entry_details hire for Taxes (ProductsTaxes + TransactionTax) 
                // ----------------------------------------- \\
                if ($totalTaxes > 0) {
                    AcJournalEntryDetail::create([
                        'account_number' => $ac_setting->vat_due,
                        'amount' => abs($totalTaxes) * -1,
                        'ac_journal_entries_id' => $record->id,
                        'cost_cen_field_id' => $transaction->extra_cost_center_id,
                    ]);
                }
                // ----------------------------------------- \\
                //add journal_entry_details hire for suppliers
                if ($contact_details->account_number_supplier) {
                    AcJournalEntryDetail::create([
                        'account_number' => $contact_details->account_number_supplier,
                        'amount' => $total_entry_amount,
                        'ac_journal_entries_id' => $record->id,
                        'cost_cen_field_id' => $transaction->extra_cost_center_id,
                    ]);
                }

                // dd(AcJournalEntryDetail::all());
            }

        }
    }
    private function newProductPriceAfterDiscount($transaction, $purchaseLine): array
    {

        $tax = TaxRate::whereId($purchaseLine['purchase_line_tax_id'])->firstOrNew([
            'amount' => 0
        ]);

        if (! is_null($transaction['discount_type']) && $transaction['discount_amount'] > 0) {
            $discountAmount = $transaction->discount_percentage_per_product;

            $totalWithoutTax = $purchaseLine['purchase_price'] * $purchaseLine['quantity'];
            $discountAmount = ($totalWithoutTax * $discountAmount) / 100;

            $newTotalWithDiscount = ($totalWithoutTax - $discountAmount);
            $taxAmount = ($newTotalWithDiscount * $tax->amount) / 100;

            return [
                'total_before_tax' => round($newTotalWithDiscount, 2),
                'product_tax' => round($taxAmount, 2),
            ];
        }
        return [
            'total_before_tax' => round($purchaseLine['purchase_price'] * $purchaseLine['quantity'], 2),
            'product_tax' => round($purchaseLine['item_tax'] * $purchaseLine['quantity'], 2),
        ];
    }
    private function storeProductsEntryDetails($product, $entryRecord, $inventoryAccountNumber, $transaction)
    {
        $product_details = Product::find($product['product_id']);

        // quantity
        $newInvoicePricesAfterDiscount = $this->newProductPriceAfterDiscount($transaction, $product);

        AcJournalEntryDetail::create([
            'account_number' => $inventoryAccountNumber,
            'cost_cen_field_id' => $transaction->extra_cost_center_id,
            'amount' => $newInvoicePricesAfterDiscount['total_before_tax'],
            'entry_desc' => "قيد مخزون لمنتج  {$product_details->name} بتاريخ " . $transaction->transaction_date,
            'ac_journal_entries_id' => $entryRecord,
        ]);

        return $newInvoicePricesAfterDiscount;
    }
    // ----------------------------------------- \\
    public function storeReturnPurchase($purchase, $transaction, $purchasPrices, $taxesAmount)
    {
        $contact = Contact::find($purchase->contact_id);

        $ac_setting = AcSetting::first();
        if ($ac_setting) {
            $entry = AcJournalEntry::create([
                'entry_no' => 1,
                'entry_date' => now(),
                'entry_desc' => "مرتجع مشتريات",
                'cost_cen_branche_id' => $purchase->branch_cost_center_id,
                'entry_type' => "daily",
                "transaction_id" => $transaction->id,
            ]);

            AcJournalEntryDetail::create([
                'account_number' => $ac_setting->inventory,
                'amount' => -array_sum($purchasPrices),
                'cost_cen_field_id' => $transaction->extra_cost_center_id,
                'ac_journal_entries_id' => $entry->id,
            ]);

            $totalTaxes = array_sum($taxesAmount) + $transaction->tax;
            if ($totalTaxes > 0) {
                AcJournalEntryDetail::create([
                    'account_number' => $ac_setting->vat_due,
                    'amount' => $totalTaxes,
                    'cost_cen_field_id' => $transaction->extra_cost_center_id,
                    'ac_journal_entries_id' => $entry->id,
                ]);
            }
            AcJournalEntryDetail::create([
                'account_number' => $contact->account_number_supplier,
                'amount' => -$transaction->final_total,
                'cost_cen_field_id' => $transaction->extra_cost_center_id,
                'ac_journal_entries_id' => $entry->id,
            ]);
        }

    }
}