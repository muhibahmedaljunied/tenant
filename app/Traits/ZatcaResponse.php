<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\ZatcaInvoice;

trait ZatcaResponse
{
    public function response($response, $code, $result = null)
    {
        $flag = ($code == 200) ? 0 : 1;
        return response()->json([
            'error_flag' => $flag,
            'message' => $response,
            'result' => $result,
        ], $code);
    }
    public function handle_zatca_response($response, $invoice, $is_sent_before = '')
    {
        $invoice = ZatcaInvoice::find($invoice->id);
        if (!empty($response)) {

            $custom_response = ['status' => $response->validationResults->status, 'zatca_response' => null];
            if (count($response->validationResults->errorMessages)) {
                $custom_response['errorMessages'] = $response->validationResults->errorMessages;
            }
            if (count($response->validationResults->warningMessages)) {
                $custom_response['warningMessages'] = $response->validationResults->warningMessages;
            }
            if ($invoice->document_type == 'simplified') {
                $custom_response['reportingStatus'] = $response->reportingStatus;
            } else {
                $custom_response['clearanceStatus'] = $response->clearanceStatus;
            }
            $custom_response['QrCode'] = (string) $invoice->zatca_qrcode ?? '';
            $custom_response['hash'] = (string) $invoice->hash ?? '';
            $custom_response['zatca_gate_status'] = $is_sent_before;
            return $custom_response;
        }

    }
}
