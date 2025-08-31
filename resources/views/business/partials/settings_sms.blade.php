@php
    $sms_service = isset($sms_settings['sms_service']) ? $sms_settings['sms_service'] : 'other';
@endphp
<div class="pos-tab-content">
    <div class="row">
        <div class="col-xs-3">
            <div class="form-group">
                <label for="sms_service">@lang('lang_v1.sms_service'):</label>
                <select name="sms_settings[sms_service]" id="sms_service" class="form-control">
                    <option value="nexmo" {{ $sms_service == 'nexmo' ? 'selected' : '' }}>Nexmo</option>
                    <option value="twilio" {{ $sms_service == 'twilio' ? 'selected' : '' }}>Twilio</option>
                    <option value="other" {{ $sms_service == 'other' ? 'selected' : '' }}>@lang('lang_v1.other')</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row sms_service_settings {{ $sms_service != 'nexmo' ? 'hide' : '' }}" data-service="nexmo">
        <div class="col-xs-3">
            <div class="form-group">
                <label for="nexmo_key">@lang('lang_v1.nexmo_key'):</label>
                <input type="text" name="sms_settings[nexmo_key]" id="nexmo_key" class="form-control"
                    placeholder="@lang('lang_v1.nexmo_key')" value="{{ $sms_settings['nexmo_key'] ?? '' }}">
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="nexmo_secret">@lang('lang_v1.nexmo_secret'):</label>
                <input type="text" name="sms_settings[nexmo_secret]" id="nexmo_secret" class="form-control"
                    placeholder="@lang('lang_v1.nexmo_secret')" value="{{ $sms_settings['nexmo_secret'] ?? '' }}">
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="nexmo_from">@lang('account.from'):</label>
                <input type="text" name="sms_settings[nexmo_from]" id="nexmo_from" class="form-control"
                    placeholder="@lang('account.from')" value="{{ $sms_settings['nexmo_from'] ?? '' }}">
            </div>
        </div>
    </div>

    <div class="row sms_service_settings {{ $sms_service != 'twilio' ? 'hide' : '' }}" data-service="twilio">
        <div class="col-xs-3">
            <div class="form-group">
                <label for="twilio_sid">@lang('lang_v1.twilio_sid'):</label>
                <input type="text" name="sms_settings[twilio_sid]" id="twilio_sid" class="form-control"
                    placeholder="@lang('lang_v1.twilio_sid')" value="{{ $sms_settings['twilio_sid'] ?? '' }}">
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="twilio_token">@lang('lang_v1.twilio_token'):</label>
                <input type="text" name="sms_settings[twilio_token]" id="twilio_token" class="form-control"
                    placeholder="@lang('lang_v1.twilio_token')" value="{{ $sms_settings['twilio_token'] ?? '' }}">
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="twilio_from">@lang('account.from'):</label>
                <input type="text" name="sms_settings[twilio_from]" id="twilio_from" class="form-control"
                    placeholder="@lang('account.from')" value="{{ $sms_settings['twilio_from'] ?? '' }}">
            </div>
        </div>
    </div>

    <div class="row sms_service_settings @if ($sms_service != 'other') hide @endif" data-service="other">

        <div class="col-xs-3">
            <div class="form-group">
                <label for="sms_settings_url">URL:</label>
                <input type="text" name="sms_settings[url]" id="sms_settings_url" class="form-control"
                    placeholder="URL" value="{{ $sms_settings['url'] ?? '' }}">
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="send_to_param_name">@lang('lang_v1.send_to_param_name'):</label>
                <input type="text" name="sms_settings[send_to_param_name]" id="send_to_param_name"
                    class="form-control" placeholder="@lang('lang_v1.send_to_param_name')"
                    value="{{ $sms_settings['send_to_param_name'] ?? '' }}">
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="msg_param_name">@lang('lang_v1.msg_param_name'):</label>
                <input type="text" name="sms_settings[msg_param_name]" id="msg_param_name" class="form-control"
                    placeholder="@lang('lang_v1.msg_param_name')" value="{{ $sms_settings['msg_param_name'] ?? '' }}">
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="request_method">@lang('lang_v1.request_method'):</label>
                <select name="sms_settings[request_method]" id="request_method" class="form-control">
                    <option value="get" {{ $sms_settings['request_method'] == 'get' ? 'selected' : '' }}>GET
                    </option>
                    <option value="post" {{ $sms_settings['request_method'] == 'post' ? 'selected' : '' }}>POST
                    </option>
                </select>
            </div>
        </div>

        {{-- --------------------------- --}}
        <div class="col-xs-4">
            <div class="form-group">
                <label for="sms_settings_param_key3">@lang('lang_v1.sms_settings_param_key', ['number' => 3]):</label>
                <input type="text" name="sms_settings[param_3]" id="sms_settings_param_key3" class="form-control"
                    placeholder="@lang('lang_v1.sms_settings_param_val', ['number' => 3])" value="{{ $sms_settings['param_3'] ?? '' }}">
            </div>
        </div>

        <div class="col-xs-4">
            <div class="form-group">
                <label for="sms_settings_param_val3">@lang('lang_v1.sms_settings_param_val', ['number' => 3]):</label>
                <input type="text" name="sms_settings[param_val_3]" id="sms_settings_param_val3"
                    class="form-control" placeholder="@lang('lang_v1.sms_settings_param_val', ['number' => 3])"
                    value="{{ $sms_settings['param_val_3'] ?? '' }}">
            </div>
        </div>

        <div class="col-md-8 col-xs-12">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="test_number" id="test_number" class="form-control"
                        placeholder="@lang('lang_v1.test_number')">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-success pull-right"
                            id="test_sms_btn">@lang('lang_v1.test_sms_configuration')</button>
                    </span>
                </div>
            </div>
        </div>


    </div>
</div>
