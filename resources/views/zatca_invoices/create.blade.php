@extends('layouts.app')
@section('title', __('zatca.invoices.title'))

@section('content')
    <section class="content-header">
        <h1>@lang('zatca.invoices.title')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('zatca.settings.title')])
            <div>
                <form action="{{ action('Zatca\\ZatcaSettingsController@store') }}" method="POST" id="zatca_setting_create">
                    @csrf
                    <div>
                        <div class="form-group">
                            <label for="name">{{ __('zatca.settings.name') }}:*</label>
                            <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __('zatca.settings.name') }}" value="{{ old('name') }}">
                        </div>
                        <div class="form-group">
                            <label for="mobile">{{ __('zatca.settings.mobile') }}:</label>
                            <input type="text" name="mobile" id="mobile" class="form-control input_number" placeholder="{{ __('zatca.settings.mobile') }}" value="{{ old('mobile') }}">
                        </div>
                        <div class="form-group">
                            <label for="trn">{{ __('zatca.settings.trn') }}:</label>
                            <input type="text" name="trn" id="trn" class="form-control" placeholder="{{ __('zatca.settings.trn') }}" value="{{ old('trn') }}">
                            <span style="color: #808080a1;">Example : 300000000000003</span>
                        </div>
                        <div class="form-group">
                            <label for="crn">{{ __('zatca.settings.crn') }}:</label>
                            <input type="text" name="crn" id="crn" class="form-control" placeholder="{{ __('zatca.settings.crn') }}" value="{{ old('crn') }}">
                        </div>
                        <div class="form-group">
                            <label for="street_name">{{ __('zatca.settings.street_name') }}:</label>
                            <input type="text" name="street_name" id="street_name" class="form-control" placeholder="{{ __('zatca.settings.street_name') }}" value="{{ old('street_name') }}">
                        </div>
                        <div class="form-group">
                            <label for="building_number">{{ __('zatca.settings.building_number') }}:</label>
                            <input type="text" name="building_number" id="building_number" class="form-control input_number" placeholder="{{ __('zatca.settings.building_number') }}" value="{{ old('building_number') }}">
                            <span style="color: #808080a1;">Example : 1234</span>
                        </div>
                        <div class="form-group">
                            <label for="plot_identification">{{ __('zatca.settings.plot_identification') }}:</label>
                            <input type="text" name="plot_identification" id="plot_identification" class="form-control" placeholder="{{ __('zatca.settings.plot_identification') }}" value="{{ old('plot_identification') }}">
                            <span style="color: #808080a1;">Example : 1234</span>
                        </div>
                        <div class="form-group">
                            <label for="region">{{ __('zatca.settings.region') }}:</label>
                            <input type="text" name="region" id="region" class="form-control" placeholder="{{ __('zatca.settings.region') }}" value="{{ old('region') }}">
                        </div>
                        <div class="form-group">
                            <label for="city">{{ __('zatca.settings.city') }}:</label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="{{ __('zatca.settings.city') }}" value="{{ old('city') }}">
                        </div>
                        <div class="form-group">
                            <label for="postal_number">{{ __('zatca.settings.postal_number') }}:</label>
                            <input type="text" name="postal_number" id="postal_number" class="form-control input_number" placeholder="{{ __('zatca.settings.postal_number') }}" value="{{ old('postal_number') }}">
                            <span style="color: #808080a1;">Example : 12345</span>
                        </div>
                        <div class="form-group">
                            <label for="egs_serial_number">{{ __('zatca.settings.egs_serial_number') }}:</label>
                            <input type="text" name="egs_serial_number" id="egs_serial_number" class="form-control" placeholder="{{ __('zatca.settings.egs_serial_number') }}" value="{{ old('egs_serial_number') }}">
                            <span style="color: #808080a1;">Example : 1-ABC|2-ABC|3-ABC</span>
                        </div>
                        <div class="form-group">
                            <label for="business_category">{{ __('zatca.settings.business_category') }}:</label>
                            <input type="text" name="business_category" id="business_category" class="form-control" placeholder="{{ __('zatca.settings.business_category') }}" value="{{ old('business_category') }}">
                        </div>
                        <div class="form-group">
                            <label for="common_name">{{ __('zatca.settings.common_name') }}:</label>
                            <input type="text" name="common_name" id="common_name" class="form-control" placeholder="{{ __('zatca.settings.common_name') }}" value="{{ old('common_name') }}">
                        </div>
                        <div class="form-group">
                            <label for="organization_unit_name">{{ __('zatca.settings.organization_unit_name') }}:</label>
                            <input type="text" name="organization_unit_name" id="organization_unit_name" class="form-control" placeholder="{{ __('zatca.settings.organization_unit_name') }}" value="{{ old('organization_unit_name') }}">
                        </div>
                        <div class="form-group">
                            <label for="organization_name">{{ __('zatca.settings.organization_name') }}:</label>
                            <input type="text" name="organization_name" id="organization_name" class="form-control" placeholder="{{ __('zatca.settings.organization_name') }}" value="{{ old('organization_name') }}">
                        </div>
                        <div class="form-group">
                            <label for="country_name">{{ __('zatca.settings.country_name') }}:</label>
                            <select name="country_name" id="country_name" class="form-control">
                                <option value="">{{ __('zatca.settings.country_name') }}</option>
                                @foreach($countries as $key => $value)
                                    <option value="{{ $key }}" {{ old('country_name') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="registered_address">{{ __('zatca.settings.registered_address') }}:</label>
                            <input type="text" name="registered_address" id="registered_address" class="form-control" placeholder="{{ __('zatca.settings.registered_address') }}" value="{{ old('registered_address') }}">
                        </div>
                        <div class="form-group">
                            <label for="otp">{{ __('zatca.settings.otp') }}:</label>
                            <input type="text" name="otp" id="otp" class="form-control input_number" placeholder="{{ __('zatca.settings.otp') }}" value="{{ old('otp') }}">
                        </div>
                        <div class="form-group">
                            <label for="email_address">{{ __('zatca.settings.email_address') }}:</label>
                            <input type="text" name="email_address" id="email_address" class="form-control" placeholder="{{ __('zatca.settings.email_address') }}" value="{{ old('email_address') }}">
                        </div>
                        <div class="form-group">
                            <label for="invoice_type">{{ __('zatca.settings.invoice_type') }}:</label>
                            <select name="invoice_type" id="invoice_type" class="form-control">
                                <option value="">{{ __('zatca.settings.invoice_type') }}</option>
                                @foreach($issuingTypes as $key => $value)
                                    <option value="{{ $key }}" {{ old('invoice_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_production" id="is_production" class="form-check icheck" value="1" {{ old('is_production') ? 'checked' : '' }}>
                                <label for="is_production">{{ __('zatca.settings.is_production') }}:</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                </form>
            </div>
        @endcomponent
    </section>
    <!-- /.content -->
@endsection
