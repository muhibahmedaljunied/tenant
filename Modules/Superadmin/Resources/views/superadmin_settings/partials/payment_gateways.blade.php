<div class="pos-tab-content">
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <label>
                <input type="checkbox" name="enable_offline_payment" value="1" class="input-icheck" {{ old('enable_offline_payment', !empty($settings['enable_offline_payment'])) ? 'checked' : '' }}>
                @lang('superadmin::lang.enable_offline_payment')
                </label>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label for="offline_payment_details">{{ __('superadmin::lang.offline_payment_details') }}:</label>
                @show_tooltip(__('superadmin::lang.offline_payment_details_tooltip'))
                <textarea name="offline_payment_details" id="offline_payment_details" class="form-control" placeholder="{{ __('superadmin::lang.offline_payment_details') }}" rows="3">{{ old('offline_payment_details', !empty($settings['offline_payment_details']) ? $settings['offline_payment_details'] : null) }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
    	<h4>Stripe:</h4>
    	<div class="col-xs-4">
            <div class="form-group">
            	<label for="STRIPE_PUB_KEY">{{ __('superadmin::lang.stripe_pub_key') }}:</label>
            	<input type="text" name="STRIPE_PUB_KEY" id="STRIPE_PUB_KEY" class="form-control" placeholder="{{ __('superadmin::lang.stripe_pub_key') }}" value="{{ old('STRIPE_PUB_KEY', $default_values['STRIPE_PUB_KEY']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
            	<label for="STRIPE_SECRET_KEY">{{ __('superadmin::lang.stripe_secret_key') }}:</label>
            	<input type="text" name="STRIPE_SECRET_KEY" id="STRIPE_SECRET_KEY" class="form-control" placeholder="{{ __('superadmin::lang.stripe_secret_key') }}" value="{{ old('STRIPE_SECRET_KEY', $default_values['STRIPE_SECRET_KEY']) }}">
            </div>
        </div>
        <div class="clearfix"></div>
        <h4>Paypal:</h4>
        <div class="col-xs-6">
            <div class="form-group">
            	<label for="PAYPAL_MODE">{{ __('superadmin::lang.paypal_mode') }}:</label>
            	<select name="PAYPAL_MODE" id="PAYPAL_MODE" class="form-control">
            		<option value="">{{ __('messages.please_select') }}</option>
            		<option value="live" {{ old('PAYPAL_MODE', $default_values['PAYPAL_MODE']) == 'live' ? 'selected' : '' }}>Live</option>
            		<option value="sandbox" {{ old('PAYPAL_MODE', $default_values['PAYPAL_MODE']) == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
            	</select>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-4">
            <div class="form-group">
            	<label for="PAYPAL_SANDBOX_API_USERNAME">{{ __('superadmin::lang.paypal_sandbox_api_username') }}:</label>
            	<input type="text" name="PAYPAL_SANDBOX_API_USERNAME" id="PAYPAL_SANDBOX_API_USERNAME" class="form-control" placeholder="{{ __('superadmin::lang.paypal_sandbox_api_username') }}" value="{{ old('PAYPAL_SANDBOX_API_USERNAME', $default_values['PAYPAL_SANDBOX_API_USERNAME']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
            	<label for="PAYPAL_SANDBOX_API_PASSWORD">{{ __('superadmin::lang.paypal_sandbox_api_password') }}:</label>
            	<input type="text" name="PAYPAL_SANDBOX_API_PASSWORD" id="PAYPAL_SANDBOX_API_PASSWORD" class="form-control" placeholder="{{ __('superadmin::lang.paypal_sandbox_api_password') }}" value="{{ old('PAYPAL_SANDBOX_API_PASSWORD', $default_values['PAYPAL_SANDBOX_API_PASSWORD']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
            	<label for="PAYPAL_SANDBOX_API_SECRET">{{ __('superadmin::lang.paypal_sandbox_api_secret') }}:</label>
            	<input type="text" name="PAYPAL_SANDBOX_API_SECRET" id="PAYPAL_SANDBOX_API_SECRET" class="form-control" placeholder="{{ __('superadmin::lang.paypal_sandbox_api_secret') }}" value="{{ old('PAYPAL_SANDBOX_API_SECRET', $default_values['PAYPAL_SANDBOX_API_SECRET']) }}">
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-4">
            <div class="form-group">
            	<label for="PAYPAL_LIVE_API_USERNAME">{{ __('superadmin::lang.paypal_live_api_username') }}:</label>
            	<input type="text" name="PAYPAL_LIVE_API_USERNAME" id="PAYPAL_LIVE_API_USERNAME" class="form-control" placeholder="{{ __('superadmin::lang.paypal_live_api_username') }}" value="{{ old('PAYPAL_LIVE_API_USERNAME', $default_values['PAYPAL_LIVE_API_USERNAME']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
            	<label for="PAYPAL_LIVE_API_PASSWORD">{{ __('superadmin::lang.paypal_live_api_password') }}:</label>
            	<input type="text" name="PAYPAL_LIVE_API_PASSWORD" id="PAYPAL_LIVE_API_PASSWORD" class="form-control" placeholder="{{ __('superadmin::lang.paypal_live_api_password') }}" value="{{ old('PAYPAL_LIVE_API_PASSWORD', $default_values['PAYPAL_LIVE_API_PASSWORD']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
            	<label for="PAYPAL_LIVE_API_SECRET">{{ __('superadmin::lang.paypal_live_api_secret') }}:</label>
            	<input type="text" name="PAYPAL_LIVE_API_SECRET" id="PAYPAL_LIVE_API_SECRET" class="form-control" placeholder="{{ __('superadmin::lang.paypal_live_api_secret') }}" value="{{ old('PAYPAL_LIVE_API_SECRET', $default_values['PAYPAL_LIVE_API_SECRET']) }}">
            </div>
        </div>
        <div class="clearfix"></div>
        <h4>Razorpay: <small>(For INR India)</small></h4>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="RAZORPAY_KEY_ID">Key ID:</label>
                <input type="text" name="RAZORPAY_KEY_ID" id="RAZORPAY_KEY_ID" class="form-control" value="{{ old('RAZORPAY_KEY_ID', $default_values['RAZORPAY_KEY_ID']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="RAZORPAY_KEY_SECRET">Key Secret:</label>
                <input type="text" name="RAZORPAY_KEY_SECRET" id="RAZORPAY_KEY_SECRET" class="form-control" value="{{ old('RAZORPAY_KEY_SECRET', $default_values['RAZORPAY_KEY_SECRET']) }}">
            </div>
        </div>
        <div class="clearfix"></div>
        <h4>Pesapal: <small>(For KES currency)</small></h4>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="PESAPAL_CONSUMER_KEY">Consumer Key:</label>
                <input type="text" name="PESAPAL_CONSUMER_KEY" id="PESAPAL_CONSUMER_KEY" class="form-control" value="{{ old('PESAPAL_CONSUMER_KEY', $default_values['PESAPAL_CONSUMER_KEY']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="PESAPAL_CONSUMER_SECRET">Consumer Secret:</label>
                <input type="text" name="PESAPAL_CONSUMER_SECRET" id="PESAPAL_CONSUMER_SECRET" class="form-control" value="{{ old('PESAPAL_CONSUMER_SECRET', $default_values['PESAPAL_CONSUMER_SECRET']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="PESAPAL_LIVE">Is Live?</label>
                <select name="PESAPAL_LIVE" id="PESAPAL_LIVE" class="form-control">
                    <option value="false" {{ old('PESAPAL_LIVE', $default_values['PESAPAL_LIVE']) == 'false' ? 'selected' : '' }}>False</option>
                    <option value="true" {{ old('PESAPAL_LIVE', $default_values['PESAPAL_LIVE']) == 'true' ? 'selected' : '' }}>True</option>
                </select>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12">
            <br/>
            <p class="help-block"><i>@lang('superadmin::lang.payment_gateway_help')</i></p>
        </div>
    </div>
</div>