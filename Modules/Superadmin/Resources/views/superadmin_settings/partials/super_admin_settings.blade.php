<div class="pos-tab-content active">
    <div class="row">
    	<div class="col-xs-4">
            <div class="form-group">
                <label for="invoice_business_name">{{ __('business.business_name') }}:</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-suitcase"></i>
                    </span>
                    <input type="text" name="invoice_business_name" id="invoice_business_name" class="form-control" placeholder="{{ __('business.business_name') }}" value="{{ old('invoice_business_name', $settings['invoice_business_name']) }}" required>
                </div>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="form-group">
                <label for="email">{{ __('business.email') }}:</label>
                <div class="input-group">
                    <span class="input-group-addon">
                    <i class="fa fa-envelope"></i>
                    </span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('business.email') }}" value="{{ old('email', $settings['email']) }}">
                </div>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="form-group">
                 <label for="app_currency_id">{{ __('business.currency') }}:</label>
                <div class="input-group">
                <span class="input-group-addon">
                    <i class="fas fa-money-bill-alt"></i>
                </span>
                <select name="app_currency_id" id="app_currency_id" class="form-control select2" required>
                    <option value="">{{ __('business.currency_placeholder') }}</option>
                    @foreach($currencies as $key => $value)
                        <option value="{{ $key }}" {{ old('app_currency_id', $settings['app_currency_id']) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-xs-4">
            <div class="form-group">
                 <label for="invoice_business_landmark">{{ __('business.landmark') }}:</label>
                <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-map-marker"></i>
                </span>
                <input type="text" name="invoice_business_landmark" id="invoice_business_landmark" class="form-control" placeholder="{{ __('business.landmark') }}" value="{{ old('invoice_business_landmark', $settings['invoice_business_landmark']) }}" required>
            </div>
            </div>
        </div> 
        
        <div class="col-xs-4">
            <div class="form-group">
                 <label for="invoice_business_zip">{{ __('business.zip_code') }}:</label>
                <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-map-marker"></i>
                </span>
                <input type="text" name="invoice_business_zip" id="invoice_business_zip" class="form-control" placeholder="{{ __('business.zip_code') }}" value="{{ old('invoice_business_zip', $settings['invoice_business_zip']) }}" required>
            </div>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="form-group">
                 <label for="invoice_business_state">{{ __('business.state') }}:</label>
                <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-map-marker"></i>
                </span>
                <input type="text" name="invoice_business_state" id="invoice_business_state" class="form-control" placeholder="{{ __('business.state') }}" value="{{ old('invoice_business_state', $settings['invoice_business_state']) }}" required>
            </div>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="form-group">
                 <label for="invoice_business_city">{{ __('business.city') }}:</label>
                <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-map-marker"></i>
                </span>
                <input type="text" name="invoice_business_city" id="invoice_business_city" class="form-control" placeholder="{{ __('business.city') }}" value="{{ old('invoice_business_city', $settings['invoice_business_city']) }}" required>
            </div>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                 <label for="invoice_business_country">{{ __('business.country') }}:</label>
                <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-globe"></i>
                </span>
                <input type="text" name="invoice_business_country" id="invoice_business_country" class="form-control" placeholder="{{ __('business.country') }}" value="{{ old('invoice_business_country', $settings['invoice_business_country']) }}" required>
            </div>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                 <label for="package_expiry_alert_days">{{ __('superadmin::lang.package_expiry_alert_days') }}:</label>
                <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-exclamation-triangle"></i>
                </span>
                <input type="number" name="package_expiry_alert_days" id="package_expiry_alert_days" class="form-control" placeholder="{{ __('superadmin::lang.package_expiry_alert_days') }}" value="{{ old('package_expiry_alert_days', $settings['package_expiry_alert_days']) }}" required>
            </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-xs-4">
            <div class="form-group">
                <label>
                    <input type="checkbox" name="enable_business_based_username" value="1" class="input-icheck" {{ old('enable_business_based_username', (int)$settings['enable_business_based_username']) ? 'checked' : '' }}> {{ __( 'superadmin::lang.enable_business_based_username' ) }}
                </label>
                <p class="help-block">@lang('superadmin::lang.business_based_username_help')</p>
            </div>
        </div>

        <div class="col-xs-12">
            <p class="help-block"><i>{!! __('superadmin::lang.version_info', ['version' => $superadmin_version]) !!}</i></p>
        </div>
    </div>
</div>