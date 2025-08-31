<?php

namespace App\Services\Zatca\Utils;

use App\Models\Zatca;

class ZatcaRequest
{
    private static $settings;
    private function getSettings()
    {
        if (is_null(self::$settings)) {
            self::$settings = Zatca::first();
        }
        return self::$settings;
    }
    public function setSettings($settings)
    {
        self::$settings = $settings;
    }
    public function storeInvoiceRequest($invoiceDocumentType, $data)
    {
        $url = '';
        if ($this->getSettings()->is_production) {
            if ($invoiceDocumentType == 'simplified') {
                $url = '/invoices/reporting/single';
            } else {
                $url = '/invoices/clearance/single';
            }
        } else {
            $url = '/compliance/invoices';
        }
        return $this->baseRequest('POST', $url, $data);
    }
    public function baseRequest($method, $url, $data)
    {
        $client = new \GuzzleHttp\Client();
        $setting = $this->getSettings();
        $request = $client->request($method, ZatcaUrl::baseUrl() . $url, [
            'json' => $data,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept-Language' => 'en',
                'otp' => $setting->otp,
                'Accept-Version' => 'V2',
                'Accept' => 'application/json'
            ],
            'auth' => [
                $setting->certificate, // username
                $setting->secret // password
            ]
        ]);
        // $response = $request->getBody()->getContents();

        return $request;
    }
}