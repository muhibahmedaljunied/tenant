<div class="pos-tab-content">
    <div class="row">

        @if (!empty($allow_superadmin_email_settings))
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="email_settings[use_superadmin_settings]"
                                id="use_superadmin_settings" class="input-icheck" value="1"
                                {{ !empty($email_settings['use_superadmin_settings']) ? 'checked' : '' }}>
                            @lang('lang_v1.use_superadmin_email_settings')
                        </label>
                    </div>
                </div>
            </div>
        @endif

        <div id="toggle_visibility" class="{{ !empty($email_settings['use_superadmin_settings']) ? 'hide' : '' }}">
            <div class="col-xs-4">
                <div class="form-group">
                    <label for="mail_driver">@lang('lang_v1.mail_driver'):</label>
                    <select name="email_settings[mail_driver]" id="mail_driver" class="form-control">
                        @foreach ($mail_drivers as $key => $value)
                            <option value="{{ $key }}"
                                {{ !empty($email_settings['mail_driver']) && $email_settings['mail_driver'] == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-4">
                <div class="form-group">
                    <label for="mail_host">@lang('lang_v1.mail_host'):</label>
                    <input type="text" name="email_settings[mail_host]" id="mail_host" class="form-control"
                        placeholder="@lang('lang_v1.mail_host')" value="{{ $email_settings['mail_host'] }}">
                </div>
            </div>

            <div class="col-xs-4">
                <div class="form-group">
                    <label for="mail_port">@lang('lang_v1.mail_port'):</label>
                    <input type="text" name="email_settings[mail_port]" id="mail_port" class="form-control"
                        placeholder="@lang('lang_v1.mail_port')" value="{{ $email_settings['mail_port'] }}">
                </div>
            </div>

            <div class="col-xs-4">
                <div class="form-group">
                    <label for="mail_username">@lang('lang_v1.mail_username'):</label>
                    <input type="text" name="email_settings[mail_username]" id="mail_username" class="form-control"
                        placeholder="@lang('lang_v1.mail_username')" value="{{ $email_settings['mail_username'] }}">
                </div>
            </div>

            <div class="col-xs-4">
                <div class="form-group">
                    <label for="mail_password">@lang('lang_v1.mail_password'):</label>
                    <input type="password" name="email_settings[mail_password]" id="mail_password" class="form-control"
                        placeholder="@lang('lang_v1.mail_password')" value="{{ $email_settings['mail_password'] }}">
                </div>
            </div>

            <div class="col-xs-4">
                <div class="form-group">
                    <label for="mail_encryption">@lang('lang_v1.mail_encryption'):</label>
                    <input type="text" name="email_settings[mail_encryption]" id="mail_encryption"
                        class="form-control" placeholder="@lang('lang_v1.mail_encryption_place')"
                        value="{{ $email_settings['mail_encryption'] }}">
                </div>
            </div>

            <div class="col-xs-4">
                <div class="form-group">
                    <label for="mail_from_address">@lang('lang_v1.mail_from_address'):</label>
                    <input type="email" name="email_settings[mail_from_address]" id="mail_from_address"
                        class="form-control" placeholder="@lang('lang_v1.mail_from_address')"
                        value="{{ $email_settings['mail_from_address'] }}">
                </div>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="form-group">
                <label for="mail_from_name">@lang('lang_v1.mail_from_name'):</label>
                <input type="text" name="email_settings[mail_from_name]" id="mail_from_name" class="form-control"
                    placeholder="@lang('lang_v1.mail_from_name')" value="{{ $email_settings['mail_from_name'] }}">
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-xs-12 test_email_btn {{ !empty($email_settings['use_superadmin_settings']) ? 'hide' : '' }}">
            <button type="button" class="btn btn-success pull-right" id="test_email_btn">@lang('lang_v1.test_email_configuration')</button>
        </div>

    </div>
</div>
