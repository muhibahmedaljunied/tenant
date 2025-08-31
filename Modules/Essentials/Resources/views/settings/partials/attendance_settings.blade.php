<div class="pos-tab-content">
    <div class="row">
        <div class="col-xs-4">
            <div class="checkbox">
                <label>
                    <br/>
                    <input type="checkbox" name="allow_users_for_attendance" value="1" class="input-icheck" {{ !empty($settings['allow_users_for_attendance']) ? 'checked' : '' }}> @lang('essentials::lang.allow_users_for_attendance')
                </label>
            </div>
        </div>
        <div class="col-xs-12">
            <strong>@lang('essentials::lang.grace_time'):</strong>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label for="grace_before_checkin">{{ __('essentials::lang.grace_before_checkin') . ':' }}</label>
                <input type="number" name="grace_before_checkin" id="grace_before_checkin" class="form-control" placeholder="{{ __('essentials::lang.grace_before_checkin') }}" step="1" value="{{ !empty($settings['grace_before_checkin']) ? $settings['grace_before_checkin'] : '' }}">
                <p class="help-block">@lang('essentials::lang.grace_before_checkin_help')</p>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label for="grace_after_checkin">{{ __('essentials::lang.grace_after_checkin') . ':' }}</label>
                <input type="number" name="grace_after_checkin" id="grace_after_checkin" class="form-control" placeholder="{{ __('essentials::lang.grace_after_checkin') }}" step="1" value="{{ !empty($settings['grace_after_checkin']) ? $settings['grace_after_checkin'] : '' }}">
                <p class="help-block">@lang('essentials::lang.grace_after_checkin_help')</p>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label for="grace_before_checkout">{{ __('essentials::lang.grace_before_checkout') . ':' }}</label>
                <input type="number" name="grace_before_checkout" id="grace_before_checkout" class="form-control" placeholder="{{ __('essentials::lang.grace_before_checkout') }}" step="1" value="{{ !empty($settings['grace_before_checkout']) ? $settings['grace_before_checkout'] : '' }}">
                <p class="help-block">@lang('essentials::lang.grace_before_checkout_help')</p>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label for="grace_after_checkout">{{ __('essentials::lang.grace_after_checkout') . ':' }}</label>
                <input type="number" name="grace_after_checkout" id="grace_after_checkout" class="form-control" placeholder="{{ __('essentials::lang.grace_after_checkout') }}" step="1" value="{{ !empty($settings['grace_after_checkout']) ? $settings['grace_after_checkout'] : '' }}">
                <p class="help-block">@lang('essentials::lang.grace_before_checkin_help')</p>
            </div>
        </div>
    </div>
</div>