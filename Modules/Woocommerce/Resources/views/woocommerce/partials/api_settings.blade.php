<div class="pos-tab-content">
    <div class="row">
        <div class="col-xs-4">
            <div class="form-group">
                <label for="woocommerce_app_url">@lang('woocommerce::lang.woocommerce_app_url'):</label>
                <input type="text" name="woocommerce_app_url" id="woocommerce_app_url" class="form-control"
                    placeholder="@lang('woocommerce::lang.woocommerce_app_url')" value="{{ $default_settings['woocommerce_app_url'] }}">
            </div>
        </div>

        <div class="col-xs-4">
            <div class="form-group">
                <label for="woocommerce_consumer_key">@lang('woocommerce::lang.woocommerce_consumer_key'):</label>
                <input type="text" name="woocommerce_consumer_key" id="woocommerce_consumer_key" class="form-control"
                    placeholder="@lang('woocommerce::lang.woocommerce_consumer_key')" value="{{ $default_settings['woocommerce_consumer_key'] }}">
            </div>
        </div>

        <div class="col-xs-4">
            <div class="form-group">
                <label for="woocommerce_consumer_secret">@lang('woocommerce::lang.woocommerce_consumer_secret'):</label>
                <input type="password" name="woocommerce_consumer_secret" id="woocommerce_consumer_secret"
                    class="form-control" value="{{ $default_settings['woocommerce_consumer_secret'] }}">
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-xs-4">
            <div class="form-group">
                <label for="location_id">@lang('business.business_locations'):</label>
                @show_tooltip(__('woocommerce::lang.location_dropdown_help'))
                <select name="location_id" id="location_id" class="form-control">
                    @foreach ($locations as $key => $value)
                        <option value="{{ $key }}"
                            {{ $default_settings['location_id'] == $key ? 'selected' : '' }}>{{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="checkbox">
                <label>
                    <br />
                    <input type="checkbox" name="enable_auto_sync" class="input-icheck" value="1"
                        {{ !empty($default_settings['enable_auto_sync']) ? 'checked' : '' }}> @lang('woocommerce::lang.enable_auto_sync')
                </label>
                @show_tooltip(__('woocommerce::lang.auto_sync_tooltip'))
            </div>
        </div>

    </div>
</div>
