<?php

namespace App\Services\Zatca;

use Exception;
use App\Models\Zatca;
use App\Models\ZatcaInvoice;
use App\Services\Zatca\InvoiceBuilder\BuildInvoice;
use App\Services\Zatca\Utils\ZatcaRequest;

class ReportInvoiceToZatca
{
    private $company_setting;
    private $invoice;
    private $invoice_builder;
    private $certificate;
    private $secret;
    private $csid;
    public function __construct($invoice_obj, $setting_obj)
    {
        $this->company_setting = $setting_obj;
        $this->invoice = $invoice_obj;
        $this->invoice_builder = new BuildInvoice($invoice_obj, $setting_obj);
        $setting = Zatca::find($this->company_setting->id);
        if ($this->company_setting->is_production) {
            $this->certificate = $setting->production_certificate;
            $this->secret = $setting->production_secret;
            $this->csid = $setting->production_csid;
        } else {
            $this->certificate = $setting->certificate;
            $this->secret = $setting->secret;
            $this->csid = $setting->csid;
        }

    }

    /**
     * 
     *  Report Invoice Start .
     * 
     */
    public function ReportInvoice()
    {
        $invoice = ZatcaInvoice::where('zatca_id', $this->company_setting->id)->where('invoiceID', $this->invoice->invoice_counter)->first();
        // set post fields
        $post = [
            'invoiceHash' => $this->invoice_builder->GenerateInvoiceHash(),
            'uuid' => $this->invoice->uuid,
            'invoice' => $this->invoice_builder->GenerateInvoiceXmlEncoded(),
        ];


        try {
            $requestBuilder = new ZatcaRequest();
            $requestBuilder->setSettings($this->company_setting);
            $request = $requestBuilder->storeInvoiceRequest($this->invoice->document_type, $post);

            $response = $request->getBody()->getContents();
            $response_encode = json_decode($response);
            $invoice->responses()->create(['response' => $response, 'status' => $response_encode->validationResults->status]);
            if ($this->invoice->document_type == 'standard') {
                $xml = (isset($response_encode->clearedInvoice)) ? $response_encode->clearedInvoice : $this->invoice_builder->GenerateInvoiceXmlEncoded();
            } else {
                $xml = $this->invoice_builder->GenerateInvoiceXmlEncoded();
            }

            $invoice->update([
                'hash' => $this->invoice_builder->GenerateInvoiceHash(),
                'sent_to_zatca' => true,
                'sent_to_zatca_status' => $this->handle_zatca_response($response_encode, $this->invoice)['status'],
                'signing_time' => $this->invoice_builder->signing_time,
                'xml' => $xml
            ]);
            return $this->handle_zatca_response($response_encode);
        } catch (Exception $e) {
            // $xml = $this->invoice_builder->GenerateFullXml('sign');

            $this->responseHandler($invoice, $e);
        }
    }
    private function responseHandler($invoice, $e)
    {
        if (method_exists($e, 'getResponse')) {
            $response = $e->getResponse();
            $response_encode = null;
            if ($response) {
                $response_encode = json_decode($response->getBody()->getContents());

                $invoice->responses()->create([
                    'response' => json_encode(json_decode($response->getBody()->getContents())),
                    'status' => $this->handle_zatca_response($response_encode)['status']
                ]);
                $invoice->update([
                    'sent_to_zatca_status' => $this->handle_zatca_response($response_encode)['status']
                ]);
                return $this->handle_zatca_response($response_encode);
            }

        }
    }
    
    /**
     * 
     * Report Invoice End .
     * 
     */
    public function handle_zatca_response($response)
    {
        $response = optional($response);
        $invoice = ZatcaInvoice::where('zatca_id', $this->company_setting->id)->where('invoiceID', $this->invoice->invoice_counter)->first();
        $custom_response = [
            'status' => $response->validationResults->status ?? '',
            'zatca_response' => null
        ];
        if ($response->validationResults && count($response->validationResults->errorMessages) > 0) {
            $custom_response['errorMessages'] = $response->validationResults->errorMessages;
        }
        if ($invoice->document_type == 'simplified') {
            $custom_response['reportingStatus'] = $response->reportingStatus;
        } else {
            $custom_response['clearanceStatus'] = $response->clearanceStatus;
        }
        $custom_response['QrCode'] = (string) $invoice->zatca_qrcode ?? '';
        $custom_response['hash'] = (string) $invoice->hash ?? '';
        return $custom_response;
    }
}
