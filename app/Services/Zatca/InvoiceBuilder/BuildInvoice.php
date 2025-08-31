<?php

namespace App\Services\Zatca\InvoiceBuilder;

use Carbon\Carbon;
use App\Models\Zatca;
use App\Models\ZatcaInvoice;
use App\Services\Zatca\Utils\QRCode;
use App\Services\Zatca\Cert\Cert509XParser;
use App\Services\Zatca\Utils\GenerateInvoiceHash;
use App\Services\Zatca\InvoiceBuilder\BuildInvoiceLines;

class BuildInvoice
{
    public $invoice;
    public $company_setting;
    public $lines;
    public $certificate;
    public $digital_signature;
    public $signing_time;
    public function __construct($invoice_obj, $setting_obj)
    {
        $this->invoice = $invoice_obj;
        $this->company_setting = $setting_obj;
        $this->signing_time = now();
        $this->lines = new BuildInvoiceLines($this->invoice);
        $this->certificate = new Cert509XParser($this->company_setting);
    }
    /**
     * 
     *  Build Billing Reference Xml Start .
     *  Used For Credit or Debit notes
     * 
     */
    public function GetBillingReference()
    {
        $xml_billing_reference = file_get_contents(public_path('zatca/xml/xml_billing_reference.xml'));
        $xml_billing_reference = str_replace("SET_INVOICE_NUMBER", $this->invoice->parentInvoice ?? '', $xml_billing_reference);
        return ($this->invoice->parentInvoice) ? $xml_billing_reference : '';
    }
    /**
     * 
     *  Build Billing Reference Xml End .
     * 
     */

    /**
     * 
     *  Get Xml Invoice QrCode Value Start .
     * 
     */

    public function GetQrCodeFromXml(ZatcaInvoice $invoice)
    {
        $xml_string = base64_decode($invoice->xml);
        $element = simplexml_load_string($xml_string);
        $element->registerXPathNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $element->registerXPathNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $result = $element->xpath('//cac:AdditionalDocumentReference[3]//cac:Attachment//cbc:EmbeddedDocumentBinaryObject')[0];
        return $result;
    }
    /**
     * 
     *  Get Xml Invoice QrCode Value End .
     * 
     */

    /**
     * 
     *  Generate Invoice Digital Signature Start .
     * 
     */
    public function GetDigitalSignature()
    {
        $setting = Zatca::find($this->company_setting->id);
        $default_xml_hash = hash('sha256', $this->GenerateFullXml('hash'), true);
        $priv_key = base64_decode($setting->private_key);
        openssl_sign($default_xml_hash, $signature, $priv_key, "sha256");
        return base64_encode($signature);
    }

    public function GetUBLExtensions()
    {
        $this->digital_signature = $this->GetDigitalSignature();
        $xml_ubl_extensions = file_get_contents(public_path('zatca/xml/xml_ubl_extensions.xml'));
        $ubl_xml = str_replace("SET_INVOICE_HASH", $this->GenerateInvoiceHash(), $xml_ubl_extensions);
        $ubl_xml = str_replace("SET_SIGNED_PROPERTIES_HASH", $this->GenerateSignedPropertiesHashEncoded(), $ubl_xml);
        $ubl_xml = str_replace("SET_DIGITAL_SIGNATURE", $this->digital_signature, $ubl_xml);
        $ubl_xml = str_replace("SET_CERTIFICATE_VALUE", $this->certificate->certificate, $ubl_xml);
        $ubl_xml = str_replace("SET_CERTIFICATE_SIGNED_PROPERTIES", $this->GenerateSignedProperties(), $ubl_xml);
        return $ubl_xml;
    }

    public function GetPIH()
    {
        $previous_invoice = ZatcaInvoice::where('zatca_id', $this->company_setting->id)->where('invoiceID', '<', $this->invoice->invoice_counter)->latest('id')->first();

        $previous_hash = file_get_contents(public_path('zatca/xml/previous_hash.xml'));
        $hash = $previous_invoice ? $previous_invoice->hash : base64_encode(hash('sha256', '0', true));

        $previous_hash = str_replace("SET_PREVIOUS_INVOICE_HASH", $hash, $previous_hash);
        return $previous_hash;
    }

    public function GetBuyer()
    {
        $xml_client = file_get_contents(public_path('zatca/xml/xml_client.xml'));
        $client = $this->invoice->client ?? null;

        if (! $client) {
            return '';
        }
        $xml_client = str_replace("SET_CLIENT_VAT_NUMBER", $client->trn, $xml_client);
        $xml_client = str_replace("SET_CLIENT_STREET_NAME", $client->street_name, $xml_client);
        $xml_client = str_replace("SET_CLIENT_BUILDING_NUMBER", $client->building_number, $xml_client);
        $xml_client = str_replace("SET_CLIENT_PLOT_IDENTIFICATION", $client->plot_identification, $xml_client);
        $xml_client = str_replace("SET_CLIENT_SUB_DIVISION_NAME", $client->city, $xml_client);
        $xml_client = str_replace("SET_CLIENT_CITY_NAME", $client->city, $xml_client);
        $xml_client = str_replace("SET_CLIENT_POSTAL_ZONE", $client->postal_number, $xml_client);
        $xml_client = str_replace("SET_CLIENT_REGISTRATION_NAME", $client->name, $xml_client);
        return $xml_client;
    }

    public function GenerateFullXml($type = 'hash')
    {
        $xml = file_get_contents(public_path('zatca/xml/xml_to_hash.xml'));
        if ($type == 'sign') {
            $xml = file_get_contents(public_path('zatca/xml/xml_to_sign.xml'));
            $xml = str_replace("SET_UBL_Extensions", $this->GetUBLExtensions(), $xml);
        }

        $xml = str_replace("SET_INVOICE_SERIAL_NUMBER", $this->invoice->invoice_number, $xml);
        $xml = str_replace("SET_TERMINAL_UUID", $this->invoice->uuid, $xml);
        $xml = str_replace("SET_ISSUE_DATE", Carbon::parse($this->invoice->issue_date)->format('Y-m-d'), $xml);
        $xml = str_replace("SET_ISSUE_TIME", Carbon::parse($this->invoice->issue_time)->format('H:i:s'), $xml);
        $xml = str_replace("SET_INVOICE_TYPE", $this->invoice->invoice_type, $xml);
        $xml = str_replace("SET_PREVIOUS_INVOICE_HASH", $this->GetPIH(), $xml);
        $xml = str_replace("SET_DOCUMENT", ($this->invoice->document_type == 'simplified') ? '0200000' : '0100000', $xml);
        $xml = str_replace("SET_BILLING_REFERENCE", $this->GetBillingReference(), $xml);
        $xml = str_replace("SET_INVOICE_COUNTER_NUMBER", $this->invoice->invoice_counter, $xml);
        $xml = str_replace("SET_COMMERCIAL_REGISTRATION_NUMBER", $this->company_setting->crn, $xml);
        $xml = str_replace("SET_STREET_NAME", $this->company_setting->street_name, $xml);
        $xml = str_replace("SET_BUILDING_NUMBER", $this->company_setting->building_number, $xml);
        $xml = str_replace("SET_PLOT_IDENTIFICATION", $this->company_setting->plot_identification, $xml);
        $xml = str_replace("SET_CITY_SUBDIVISION", $this->company_setting->city, $xml);
        $xml = str_replace("SET_CITY", $this->company_setting->city, $xml);
        $xml = str_replace("SET_POSTAL_NUMBER", $this->company_setting->postal_number, $xml);
        $xml = str_replace("SET_VAT_NUMBER", $this->company_setting->trn, $xml);
        $xml = str_replace("SET_VAT_NAME", $this->company_setting->name, $xml);
        $xml = str_replace("SET_CLIENT", $this->GetBuyer(), $xml);
        if ($this->invoice->invoice_type == 383 || $this->invoice->invoice_type == 381) {
            $xml_return_reason = file_get_contents(public_path('zatca/xml/xml_return_reason.xml'));
        } else {
            $xml_return_reason = '';
        }
        $xml = str_replace("SET_RETURN_REASON", $xml_return_reason, $xml);
        $xml = str_replace("SET_TAX_TOTALS", $this->lines->GenerateTaxTotalsXml(), $xml);
        $xml = str_replace("SET_LINE_EXTENSION_AMOUNT", number_format($this->lines->items_total, 2, '.', ''), $xml);
        $xml = str_replace("SET_EXCLUSIVE_AMOUNT", number_format($this->lines->lines_sub_total, 2, '.', ''), $xml);
        $xml = str_replace("SET_ALLOWANCE_AMOUNT", number_format($this->lines->lines_discount_total, 2, '.', ''), $xml);
        $xml = str_replace("SET_NET_TOTAL", number_format($this->lines->lines_net_total, 2, '.', ''), $xml);
        $xml = str_replace("SET_LINE_ITEMS", $this->lines->generated_lines_xml, $xml);
        $xml = str_replace("SET_INVOICE_ALLOWANCES", $this->lines->generated_invoice_allowance_charge, $xml);
        $xml = str_replace("CURRENCY_NAME", $this->invoice->currency, $xml);

        if ($type == 'sign') {
            $xml = str_replace("SET_QR_CODE_DATA", $this->GenerateQrCode(), $xml);
        }
        return $xml;
    }

    public function GenerateInvoiceHash()
    {
        $new_obj = new GenerateInvoiceHash($this->GenerateFullXml('hash'));
        return $new_obj->GenerateBinaryHashEncoded();
    }

    public function GenerateInvoiceXmlEncoded()
    {
        return base64_encode($this->GenerateFullXml('sign'));
    }

    public function GenerateBinaryHash()
    {
        $new_obj = new GenerateInvoiceHash($this->GenerateFullXml('hash'));
        return $new_obj->GenerateBinaryHash();
    }

    public function GenerateQrCode()
    {

        $tax_total = $this->lines->lines_tax_total;
        if ($this->invoice->currency == 'USD') {
            $tax_total = $tax_total * 3.75;
        }

        $data = [
            $this->company_setting->name,
            $this->company_setting->trn,
            Carbon::parse($this->invoice->transaction_created_at)->toIso8601ZuluString(),
            number_format($this->lines->lines_net_total, 2, '.', ''),
            number_format($tax_total, 2, '.', ''),
        ];
        $data[] = $this->GenerateInvoiceHash();
        $data[] = $this->digital_signature;
        $data[] = $this->certificate->GetCertificateECDSA();
        $data[] = $this->certificate->GetCertificateSignature();

        return (new QRCode($data))->toBase64();
    }

    public function GenerateSignedProperties()
    {
        $xml_ubl_signed_properties = file_get_contents(public_path('zatca/xml/xml_ubl_signed_properties.xml'));
        $xml_ubl_certificate_signed_properties = str_replace("SET_SIGN_TIMESTAMP", Carbon::parse($this->signing_time)->toIso8601ZuluString(), $xml_ubl_signed_properties);
        $xml_ubl_certificate_signed_properties = str_replace("SET_CERTIFICATE_HASH", $this->certificate->GetCertificateHashEncoded(), $xml_ubl_certificate_signed_properties);
        $xml_ubl_certificate_signed_properties = str_replace("SET_CERTIFICATE_ISSUER", $this->certificate->GetIssuerName(), $xml_ubl_certificate_signed_properties);
        $xml_ubl_certificate_signed_properties = str_replace("SET_CERTIFICATE_SERIAL_NUMBER", $this->certificate->GetIssuerSerialNumber(), $xml_ubl_certificate_signed_properties);
        return $xml_ubl_certificate_signed_properties;
    }

    public function GenerateSignedPropertiesHashEncoded()
    {
        $signed_properties = $this->GenerateSignedProperties();
        $signed_properties = hash('sha256', $signed_properties, false);
        return base64_encode($signed_properties);
    }
}
