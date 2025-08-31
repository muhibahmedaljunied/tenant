<div class="form-group">
    <label for="name">@lang('zatca.settings.name'):</label>
    <input type="text" name="zatca_settings[name]" id="name" class="form-control"
        placeholder="@lang('zatca.settings.name')" value="{{ old('zatca_settings.name', $zatcaSetting->name) }}">
    @error('zatca_settings.name')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="mobile">@lang('zatca.settings.mobile'):</label>
    <input type="text" name="zatca_settings[mobile]" id="mobile" class="form-control input_number"
        placeholder="@lang('zatca.settings.mobile')" value="{{ old('zatca_settings.mobile', $zatcaSetting->mobile) }}">
    @error('zatca_settings.mobile')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="trn">@lang('zatca.settings.trn'):</label>
    <input type="text" name="zatca_settings[trn]" id="trn" class="form-control"
        placeholder="@lang('zatca.settings.trn')" value="{{ old('zatca_settings.trn', $zatcaSetting->trn) }}">
    <span class="text-info">Example: 300000000000003</span>
    @error('zatca_settings.trn')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="crn">@lang('zatca.settings.crn'):</label>
    <input type="text" name="zatca_settings[crn]" id="crn" class="form-control"
        placeholder="@lang('zatca.settings.crn')" value="{{ old('zatca_settings.crn', $zatcaSetting->crn) }}">
    @error('zatca_settings.crn')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="street_name">@lang('zatca.settings.street_name'):</label>
    <input type="text" name="zatca_settings[street_name]" id="street_name" class="form-control"
        placeholder="@lang('zatca.settings.street_name')" value="{{ old('zatca_settings.street_name', $zatcaSetting->street_name) }}">
    @error('zatca_settings.street_name')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="building_number">@lang('zatca.settings.building_number'):</label>
    <input type="text" name="zatca_settings[building_number]" id="building_number" class="form-control input_number"
        placeholder="@lang('zatca.settings.building_number')" value="{{ old('zatca_settings.building_number', $zatcaSetting->building_number) }}">
    <span class="text-info">Example: 1234</span>
    @error('zatca_settings.building_number')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="plot_identification">@lang('zatca.settings.plot_identification'):</label>
    <input type="text" name="zatca_settings[plot_identification]" id="plot_identification" class="form-control"
        placeholder="@lang('zatca.settings.plot_identification')" value="{{ old('zatca_settings.plot_identification', $zatcaSetting->plot_identification) }}">
    <span class="text-info">Example: 1234</span>
    @error('zatca_settings.plot_identification')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="region">@lang('zatca.settings.region'):</label>
    <input type="text" name="zatca_settings[region]" id="region" class="form-control"
        placeholder="@lang('zatca.settings.region')" value="{{ old('zatca_settings.region', $zatcaSetting->region) }}">
    @error('zatca_settings.region')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="city">@lang('zatca.settings.city'):</label>
    <input type="text" name="zatca_settings[city]" id="city" class="form-control"
        placeholder="@lang('zatca.settings.city')" value="{{ old('zatca_settings.city', $zatcaSetting->city) }}">
    @error('zatca_settings.city')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="postal_number">@lang('zatca.settings.postal_number'):</label>
    <input type="text" name="zatca_settings[postal_number]" id="postal_number" class="form-control input_number"
        placeholder="@lang('zatca.settings.postal_number')" value="{{ old('zatca_settings.postal_number', $zatcaSetting->postal_number) }}">
    <span class="text-info">Example: 12345</span>
    @error('zatca_settings.postal_number')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

{{-- ------------------------ --}}
<div class="form-group">
    <label for="egs_serial_number">@lang('zatca.settings.egs_serial_number'):</label>
    <input type="text" name="zatca_settings[egs_serial_number]" id="egs_serial_number" class="form-control"
        placeholder="@lang('zatca.settings.egs_serial_number')" value="{{ old('zatca_settings.egs_serial_number', $zatcaSetting->egs_serial_number) }}">
    <span class="text-info">Example: 1-ABC|2-ABC|3-ABC</span>
    @error('zatca_settings.egs_serial_number')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="business_category">@lang('zatca.settings.business_category'):</label>
    <input type="text" name="zatca_settings[business_category]" id="business_category" class="form-control"
        placeholder="@lang('zatca.settings.business_category')" value="{{ old('zatca_settings.business_category', $zatcaSetting->business_category) }}">
    @error('zatca_settings.business_category')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="common_name">@lang('zatca.settings.common_name'):</label>
    <input type="text" name="zatca_settings[common_name]" id="common_name" class="form-control"
        placeholder="@lang('zatca.settings.common_name')" value="{{ old('zatca_settings.common_name', $zatcaSetting->common_name) }}">
    @error('zatca_settings.common_name')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="organization_name">@lang('zatca.settings.organization_name'):</label>
    <input type="text" name="zatca_settings[organization_name]" id="organization_name" class="form-control"
        placeholder="@lang('zatca.settings.organization_name')" value="{{ old('zatca_settings.organization_name', $zatcaSetting->organization_name) }}">
    @error('zatca_settings.organization_name')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="country_name">@lang('zatca.settings.country_name'):</label>
    <select name="zatca_settings[country_name]" id="country_name" class="form-control">
        <option value="" disabled selected>@lang('zatca.settings.country_name')</option>
        @foreach($countries as $key => $value)
            <option value="{{ $key }}" {{ old('zatca_settings.country_name', $zatcaSetting->country_name) == $key ? 'selected' : '' }}>
                {{ $value }}
            </option>
        @endforeach
    </select>
    @error('zatca_settings.country_name')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="email_address">@lang('zatca.settings.email_address'):</label>
    <input type="email" name="zatca_settings[email_address]" id="email_address" class="form-control"
        placeholder="@lang('zatca.settings.email_address')" value="{{ old('zatca_settings.email_address', $zatcaSetting->email_address) }}">
    @error('zatca_settings.email_address')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="invoice_type">@lang('zatca.settings.invoice_type'):</label>
    <select name="zatca_settings[invoice_type]" id="invoice_type" class="form-control">
        <option value="" disabled selected>@lang('zatca.settings.invoice_type')</option>
        @foreach($issuingTypes as $key => $value)
            <option value="{{ $key }}" {{ old('zatca_settings.invoice_type', $zatcaSetting->invoice_type) == $key ? 'selected' : '' }}>
                {{ $value }}
            </option>
        @endforeach
    </select>
    @error('zatca_settings.invoice_type')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="is_production">@lang('zatca.settings.is_production'):</label>
    <input type="checkbox" name="zatca_settings[is_production]" id="is_production" class="form-check icheck"
        value="1" {{ old('zatca_settings.is_production', $zatcaSetting->is_production) ? 'checked' : '' }}>
    @error('zatca_settings.is_production')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

