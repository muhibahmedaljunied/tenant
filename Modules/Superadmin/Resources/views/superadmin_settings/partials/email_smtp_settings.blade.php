<div class="pos-tab-content">
    <div class="row">
    	<div class="col-xs-4">
            <div class="form-group">
            	<label for="MAIL_DRIVER">{{ __('superadmin::lang.mail_driver') }}:</label>
            	<select name="MAIL_DRIVER" id="MAIL_DRIVER" class="form-control">
            		@foreach($mail_drivers as $key => $value)
            			<option value="{{ $key }}" {{ old('MAIL_DRIVER', $default_values['MAIL_DRIVER']) == $key ? 'selected' : '' }}>{{ $value }}</option>
            		@endforeach
            	</select>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
            	<label for="MAIL_HOST">{{ __('superadmin::lang.mail_host') }}:</label>
            	<input type="text" name="MAIL_HOST" id="MAIL_HOST" class="form-control" placeholder="{{ __('superadmin::lang.mail_host') }}" value="{{ old('MAIL_HOST', $default_values['MAIL_HOST']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
            	<label for="MAIL_PORT">{{ __('superadmin::lang.mail_port') }}:</label>
            	<input type="text" name="MAIL_PORT" id="MAIL_PORT" class="form-control" placeholder="{{ __('superadmin::lang.mail_port') }}" value="{{ old('MAIL_PORT', $default_values['MAIL_PORT']) }}">
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="MAIL_USERNAME">{{ __('superadmin::lang.mail_username') }}:</label>
                <input type="text" name="MAIL_USERNAME" id="MAIL_USERNAME" class="form-control" placeholder="{{ __('superadmin::lang.mail_username') }}" value="{{ old('MAIL_USERNAME', $default_values['MAIL_USERNAME']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="MAIL_PASSWORD">{{ __('superadmin::lang.mail_password') }}:</label>
                <input type="text" name="MAIL_PASSWORD" id="MAIL_PASSWORD" class="form-control" placeholder="{{ __('superadmin::lang.mail_password') }}" value="{{ old('MAIL_PASSWORD', $default_values['MAIL_PASSWORD']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="MAIL_ENCRYPTION">{{ __('superadmin::lang.mail_encryption') }}:</label>
                <input type="text" name="MAIL_ENCRYPTION" id="MAIL_ENCRYPTION" class="form-control" placeholder="{{ __('superadmin::lang.mail_encryption_place') }}" value="{{ old('MAIL_ENCRYPTION', $default_values['MAIL_ENCRYPTION']) }}">
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="MAIL_FROM_ADDRESS">{{ __('superadmin::lang.mail_from_address') }}:</label>
                <input type="email" name="MAIL_FROM_ADDRESS" id="MAIL_FROM_ADDRESS" class="form-control" placeholder="{{ __('superadmin::lang.mail_from_address') }}" value="{{ old('MAIL_FROM_ADDRESS', $default_values['MAIL_FROM_ADDRESS']) }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="MAIL_FROM_NAME">{{ __('superadmin::lang.mail_from_name') }}:</label>
                <input type="text" name="MAIL_FROM_NAME" id="MAIL_FROM_NAME" class="form-control" placeholder="{{ __('superadmin::lang.mail_from_name') }}" value="{{ old('MAIL_FROM_NAME', $default_values['MAIL_FROM_NAME']) }}">
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-4">
            <div class="form-group">
                <label>
                <input type="checkbox" name="allow_email_settings_to_businesses" value="1" class="input-icheck" {{ old('allow_email_settings_to_businesses', !empty($settings['allow_email_settings_to_businesses'])) ? 'checked' : '' }}>
                @lang('superadmin::lang.allow_email_settings_to_businesses') 
                </label>
                @show_tooltip(__('superadmin::lang.allow_email_settings_tooltip'))
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>
                <input type="checkbox" name="enable_new_business_registration_notification" value="1" class="input-icheck" {{ old('enable_new_business_registration_notification', !empty($settings['enable_new_business_registration_notification'])) ? 'checked' : '' }}>
                @lang('superadmin::lang.enable_new_business_registration_notification') 
                </label> @show_tooltip(__('superadmin::lang.new_business_notification_tooltip'))
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>
                <input type="checkbox" name="enable_new_subscription_notification" value="1" class="input-icheck" {{ old('enable_new_subscription_notification', !empty($settings['enable_new_subscription_notification'])) ? 'checked' : '' }}>
                @lang('superadmin::lang.enable_new_subscription_notification') 
                </label> @show_tooltip(__('superadmin::lang.new_subscription_tooltip'))
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12">
            <hr>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="enable_welcome_email" value="1" class="input-icheck" {{ old('enable_welcome_email', isset($settings['enable_welcome_email']) ? (int)$settings['enable_welcome_email'] : false) ? 'checked' : '' }}> {{ __( 'superadmin::lang.enable_welcome_email' ) }}
                </label> @show_tooltip(__('superadmin::lang.new_business_welcome_notification_tooltip'))
            </div>
        </div>
        <div class="col-xs-12">
            <h4>@lang('superadmin::lang.welcome_email_template'):</h4>
            <strong>@lang('lang_v1.available_tags'):</strong> {business_name}, {owner_name} <br><br>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <label for="welcome_email_subject">{{ __('superadmin::lang.welcome_email_subject') }}:</label>
                <input type="text" name="welcome_email_subject" id="welcome_email_subject" class="form-control" placeholder="{{ __('superadmin::lang.welcome_email_subject') }}" value="{{ old('welcome_email_subject', isset($settings['welcome_email_subject']) ? $settings['welcome_email_subject'] : '') }}">
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <label for="welcome_email_body">{{ __('superadmin::lang.welcome_email_body') }}:</label>
                <textarea name="welcome_email_body" id="welcome_email_body" class="form-control" placeholder="{{ __('superadmin::lang.welcome_email_body') }}">{{ old('welcome_email_body', isset($settings['welcome_email_body']) ? $settings['welcome_email_body'] : '') }}</textarea>
            </div>
        </div>
    </div>
</div>