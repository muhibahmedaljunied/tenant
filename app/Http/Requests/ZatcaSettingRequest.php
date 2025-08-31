<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ZatcaSettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            "name" => "required|string|min:3",
            "mobile" => "required|string|min:3",
            "trn" => "required|numeric|digits:15|regex:/(3)[0-9]{14}/",
            "crn" => "required|min:3",
            "street_name" => "required|string|min:3",
            "building_number" => "required|numeric|digits:4",
            "plot_identification" => "required|numeric|digits:4",
            "region" => "required|string|min:3",
            "city" => "required|string|min:3",
            "postal_number" => "required|numeric|digits:5",
            "egs_serial_number" => ["required", "string", "min:3", "regex:/(1-[A-Z]{3})([|]2-[A-Z]{3})([|]3-[A-Z]{3})/"],
            "business_category" => "required|string",
            "common_name" => "required|string|min:3",
            "organization_unit_name" => "required|string|min:3",
            "organization_name" => "required|string|min:3",
            "country_name" => "required|string|min:2",
            "registered_address" => "required|string|min:3",
            "otp" => "required|string|digits:6",
            "email_address" => "required|email",
            "invoice_type" => ["required", Rule::in(config("zatca.invoices_issuing_types"))],
            "is_production" => "nullable|in:on",
        ];
    }
}
