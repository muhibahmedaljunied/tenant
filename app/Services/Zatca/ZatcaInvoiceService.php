<?php

namespace App\Services\Zatca;

use App\User;
use App\Contact;
use App\TaxRate;
use App\Transaction;
use App\Models\Zatca;
use Illuminate\Support\Str;
use App\Models\ZatcaInvoice;
use App\Traits\ZatcaResponse;
use Illuminate\Support\Carbon;
use App\Services\Zatca\ZatcaService;
use App\Notifications\ZatcaNotification;
use App\Services\Zatca\ReportInvoiceToZatca;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Exceptions\HttpResponseException;

class ZatcaInvoiceService
{
    use ZatcaResponse;
    const SELL_INVOICE_STATUS = 388;
    const SELLRETURN_INVOICE_STATUS = 381;
    public function reportSellInvoice(?Transaction $transaction)
    {
        $zatca = Zatca::latest('id')->first();
        if (! $zatca || ! optional($zatca)->isZatcaConfigured()) {
            return false;
        }
        if (! $transaction instanceof Transaction) {
            $transaction = Transaction::whereId($transaction)->firstOrFail();
        }
        $document_type = 'simplified';
        $client = null;
        $contact = Contact::whereId($transaction->contact_id)->first();
        if (! $contact->is_default) {
            $client = $contact;
            if (! empty($client->tax_number)) {
                $document_type = 'standard';
            }
        }

        $this->checkInvoiceNotExists($transaction, self::SELL_INVOICE_STATUS, $document_type);

        $invoice = $this->createSellInvoice($transaction, $zatca->id, $document_type);

        $sell_line_relations = ['modifiers', 'sub_unit', 'related_variations', 'related_variations.unit'];

        $lines = $transaction->sell_lines()->whereNull('parent_sell_line_id')->with($sell_line_relations)->get();
        $invoiceContent = $this->invoiceValues($invoice, $transaction);

        $invoiceContent['items'] = $this->items($lines);

        if ($client) {
            $invoiceContent['client'] = $this->client($client);
        }
        $invoiceContent = json_decode(json_encode($invoiceContent));

        $setting_obj = (new ZatcaService)->settings($zatca);
        $zatcaReport = (new ReportInvoiceToZatca($invoiceContent, $setting_obj))->ReportInvoice();
        $response = $invoice->responses()->latest('id')->value('response');
        $response = json_decode($response);
        if (! $invoice->sent_to_zatca) {
            // $this->sendNotification($invoice);
        }

        return $this->handle_zatca_response($response, $invoice);
    }
    protected function sendNotification($invoice)
    {
        if (auth()->check()) {
            return Notification::notify(auth()->user(), new ZatcaNotification([
                'body' => trans('zatca.failed_send', [
                    'invoice' => $invoice->id
                ])
            ]));
        }
        $businessAdminUsers = User::where('business_id', $invoice->business_id)->whereHas('roles', function ($q) use ($invoice) {
            $q->where('name', "Admin#{$invoice->business_id}");
        })->get();
        if ($businessAdminUsers->count()) {
            return Notification::notify($businessAdminUsers, new ZatcaNotification([
                'body' => trans('zatca.failed_send', [
                    'number' => $invoice->id
                ])
            ]));
        }
        return false;
    }
    public function reportSellReturnInvoice($transaction)
    {
        $zatca = Zatca::latest('id')->first();
        if (! $zatca || ! $transaction->return_parent_id || ! optional($zatca)->isZatcaConfigured()) {
            return false;
        }
        $document_type = 'simplified';
        $client = null;
        $contact = Contact::whereId($transaction->contact_id)->first();

        if (! $contact->is_default) {
            $client = $contact;
            if (! empty($client->tax_number)) {
                $document_type = 'standard';
            }
        }
        $checkInvoice = $this->checkInvoiceNotExists($transaction, self::SELL_INVOICE_STATUS, $document_type);
        $parentTransaction = Transaction::find($transaction->return_parent_id);
        $parentInvoiceId = optional(ZatcaInvoice::where('transaction_id', $parentTransaction->id)->first())->id;
        $invoice = $this->createSellReturnInvoice($transaction, $zatca->id, $document_type, $parentInvoiceId);
        $sell_line_relations = ['sub_unit', 'related_variations', 'related_variations.unit'];

        $lines = $parentTransaction->sell_lines()->with($sell_line_relations)->get()->where('returned_main_qty', '>', 0);

        $invoice_obj = $this->invoiceValues($invoice, $transaction);
        $invoice_obj['items'] = $this->items($lines);
        if ($client) {
            $invoice_obj['client'] = $this->client($client);
        }
        $invoice_obj = json_decode(json_encode($invoice_obj));
        $settings = (new ZatcaService())->settings($zatca);

        $zatcaReport = (new ReportInvoiceToZatca($invoice_obj, $settings))->ReportInvoice();
        // $this->sendNotification();

        $response = $invoice->responses()->orderby('id', 'desc')->first()->response;
        $response = json_decode($response);
        return $this->handle_zatca_response($response, $invoice);
    }
    protected function createSellInvoice($transaction, $zatcaId, $document_type): ZatcaInvoice
    {
        return ZatcaInvoice::firstOrCreate([
            'transaction_id' => $transaction->id,
            'invoice_type' => self::SELL_INVOICE_STATUS
        ], [
            'invoiceID' => $transaction->id,
            'transaction_id' => $transaction->id,
            'invoiceNumber' => $transaction->invoice_no,
            'invoiceUUid' => Str::uuid(),
            'issue_at' => $transaction->created_at,
            'total' => $transaction->total_before_tax,
            'tax_total' => $transaction->tax_amount,
            'total_discount' => $transaction->discount_amount,
            'total_net' => $transaction->final_total,
            'zatca_id' => $zatcaId,
            'invoice_type' => self::SELL_INVOICE_STATUS,
            'document_type' => $document_type,
        ]);
    }

    protected function createSellReturnInvoice($transaction, $zatcaId, $document_type, $parentInvoiceId = null): ZatcaInvoice
    {
        // Credit Is Return Sell
        // Debit Sell
        return ZatcaInvoice::firstOrCreate([
            'transaction_id' => $transaction->id,
            'invoice_type' => self::SELLRETURN_INVOICE_STATUS,
        ], [
            'invoiceID' => $transaction->id,
            'transaction_id' => $transaction->id,
            'invoiceNumber' => $transaction->invoice_no,
            'invoiceUUid' => Str::uuid(),
            'issue_at' => $transaction->created_at,
            'parent_id' => $parentInvoiceId,
            'total' => $transaction->total_before_tax,
            'tax_total' => $transaction->tax_amount,
            'total_discount' => $transaction->discount_amount,
            'total_net' => $transaction->final_total,
            'zatca_id' => $zatcaId,
            'invoice_type' => self::SELLRETURN_INVOICE_STATUS,
            'document_type' => $document_type,
        ]);
    }
    protected function client($contact): array
    {
        if (! empty($contact->tax_number)) {
            return [
                'name' => $contact->name,
                'trn' => $contact->tax_number,
                'street_name' => $contact->street_name,
                'building_number' => $contact->building_number ?? '12345',
                'plot_identification' => $contact->plot_identification ?? '12345',
                'region' => $contact->region,
                'city' => $contact->city,
                'postal_number' => $contact->postal_number,

                // 'name'                      => $contact->name,
                // 'trn'                       => $contact->tax_number,
                // 'street_name'               => 'Qassim',
                // 'building_number'           => '1234',
                // 'plot_identification'       => '1234',
                // 'region'                    =>'Gaddah',
                // 'city'                      => 'Gaddah',
                // 'postal_number'             => '02020',
            ];
        }
        return [
            'name' => $contact->name,
            'trn' => $contact->tax_number,
            'street_name' => $contact->street_name,
            'building_number' => $contact->building_number,
            'plot_identification' => $contact->plot_identification,
            'region' => $contact->region,
            'city' => $contact->city,
            'postal_number' => $contact->postal_number,
        ];
    }


    protected function items($invoiceItems): array
    {
        $items = [];
        foreach ($invoiceItems as $line) {
            $item = [
                'id' => $line->product->id,
                'name' => $line->product->name,
                'qty' => (int) $line->main_qty,
                'sell_price' => floatval($line->unit_price),
            ];
            $item['taxes'] = $this->productTaxes($line);
            $item['discounts'] = $this->productDiscounts($line);
            $items[] = $item;
        }

        return $items;
    }
    protected function checkInvoiceNotExists($transaction, $invoiceType, $document_type)
    {
        $invoice = ZatcaInvoice::where('invoice_type', $invoiceType)->where('transaction_id', $transaction->id)->first();
        if ($invoice && $invoice->document_type != $document_type) {
            throw new HttpResponseException($this->response(__('zatca.respones.not_allowed'), 405, null));
        }
        if ($invoice && $invoice->sent_to_zatca && $invoice->sent_to_zatca_status == 'PASS') {
            $response = $invoice->responses()->latest('id')->value('response');
            $response = json_decode($response);
            throw new HttpResponseException($response, $invoice, 'Sent before');
        }
        return true;
    }
    protected function productTaxes($item): array
    {
        $taxes = [];
        $tax_details = TaxRate::find($item->tax_id);
        if (! empty($tax_details)) {
            $taxes[] = [
                'percentage' => floatval($tax_details->amount),
                'category' => 'S',
                'type' => '',
                'reason' => '',
            ];
        } elseif (empty($item->tax_id)) {
            $taxes[] = [
                'percentage' => 0,
                'category' => 'Z',
                'type' => 'VATEX-SA-32',
                'reason' => 'Zero rated goods',
            ];
        }
        return $taxes;
    }
    protected function productDiscounts($item): array
    {
        $discounts = [];
        $discounts[] = [
            'amount' => method_exists($item, 'get_discount_amount') ? floatval($item->get_discount_amount()) : 0,
            'reason' => '',
        ];
        return $discounts;
    }
    protected function invoiceValues($invoice, $transaction): array
    {
        return [
            'invoice_counter' => $invoice->invoiceID,
            'invoice_number' => $invoice->invoiceNumber,
            'issue_date' => Carbon::parse($transaction->transaction_date)->format('Y-m-d'),
            'transaction_created_at' => Carbon::parse($transaction->transaction_date)->toIso8601ZuluString(),
            'issue_time' => Carbon::parse($transaction->transaction_date)->format('H:i:s'),
            'currency' => 'SAR',
            'document_type' => $invoice->document_type, // simplified or standard
            'invoice_type' => $invoice->invoice_type, //  "388" NORMAL INVOICE , "383"  DEBIT_NOTE , "381" CREDIT_NOTE
            'uuid' => $invoice->invoiceUUid,
            'parentInvoice' => $invoice->parent_id ?? null,
            'transaction_id' => $transaction->id
        ];
    }
}
