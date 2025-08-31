<?php

namespace App\Services\Zatca;

use Exception;
use App\Models\Zatca;
use App\Services\Zatca\Cert\OnBoarding;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ZatcaSettingRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ZatcaService
{
    private function requestDto($request)
    {
        $request = optional($request);

        return [
            "name" => $request['name'],
            "mobile" => $request['mobile'],
            "trn" => $request['trn'],
            "crn" => $request['crn'],
            "street_name" => $request['street_name'],
            "building_number" => $request['building_number'],
            "plot_identification" => $request['plot_identification'],
            "region" => $request['region'],
            "city" => $request['city'],
            "postal_number" => $request['postal_number'],
            "egs_serial_number" => $request['egs_serial_number'],
            "business_category" => $request['business_category'],
            "common_name" => $request['common_name'],
            "organization_unit_name" => $request['organization_unit_name'],
            "organization_name" => $request['organization_name'],
            "country_name" => $request['country_name'],
            "registered_address" => $request['registered_address'],
            "otp" => $request['otp'],
            "email_address" => $request['email_address'],
            "invoice_type" => $request['invoice_type'],
            "created_by" => auth()->user()->id,
            "is_production" => ($request['is_production'] == 'on'),
        ];
    }
    public function validateRequest($requestData): mixed
    {
        $rules = (new ZatcaSettingRequest())->rules();
        $validation = Validator::make($requestData->toArray(), $rules);

        if ($validation->fails()) {
            $formattedErrors = collect($validation->errors())->mapWithKeys(function ($message, $key) {
                return ["zatca_settings.$key" => $message];
            });
            throw new HttpResponseException(back()->withErrors($formattedErrors->toArray())->withInput()); // Pass errors and old input back to the view
        }
        return $this;
    }

    public function create($request): Zatca
    {
        return Zatca::create($this->requestDto($request->toArray()));
    }

    public function updateBusinessSettings($request)
    {
        Zatca::updateOrCreate(
            [],
            $this->requestDto($request->collect('zatca_settings')->toArray()),
        );
        return $this;
    }

    public function update($request, $zatca): bool
    {
        return $zatca->update($this->requestDto($request->toArray()));
    }

    public function settings($zatca): object
    {
        return (object) [
            'id' => $zatca->id,
            'name' => $zatca->name,
            'mobile' => $zatca->mobile,
            'trn' => $zatca->trn,
            'crn' => $zatca->crn,
            'street_name' => $zatca->street_name,
            'building_number' => $zatca->building_number,
            'plot_identification' => $zatca->plot_identification,
            'region' => $zatca->region,
            'city' => $zatca->city,
            'postal_number' => $zatca->postal_number,
            'egs_serial_number' => $zatca->egs_serial_number,
            'business_category' => $zatca->business_category,
            'common_name' => $zatca->common_name,
            'organization_unit_name' => $zatca->organization_unit_name,
            'organization_name' => $zatca->organization_name,
            'country_name' => $zatca->country_name,
            'registered_address' => $zatca->registered_address,
            'otp' => $zatca->otp,
            'email_address' => $zatca->email_address,
            'invoice_type' => $zatca->invoice_type,
            'is_production' => $zatca->is_production,
            'cnf' => 'cnf',
            'private_key' => 'private_key',
            'public_key' => 'public_key',
            'csr_request' => 'csr_request',
            'certificate' => 'certificate',
            'secret' => 'secret',
            'csid' => 'csid',
            'production_certificate' => 'production_certificate',
            'production_secret' => 'production_secret',
            'production_csid' => 'production_csid'
        ];
    }
    public function updateOnBoardingGateway()
    {
        $zatca = Zatca::first();
        if (! $zatca) {
            return false;
        }
        $settings = $this->settings($zatca);
        $zatcaOnBoarding = new OnBoarding($settings);
        $gatewayResponse = $zatcaOnBoarding->generatePemsKeys()->IssueCert509();
        return $gatewayResponse;
    }
}
