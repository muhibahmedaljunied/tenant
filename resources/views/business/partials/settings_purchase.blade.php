<!--Purchase related settings -->
<div class="pos-tab-content">
    <div class="row">
        @if(!config('constants.disable_purchase_in_other_currency', true))
            <div class="col-sm-4">
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="purchase_in_diff_currency" id="purchase_in_diff_currency" class="input-icheck"
                                value="1" {{ $business->purchase_in_diff_currency ? 'checked' : '' }}>
                            @lang('purchase.allow_purchase_different_currency')
                        </label>
                        @show_tooltip(__('tooltip.purchase_different_currency'))
                    </div>
                </div>
            </div>

            <div class="col-sm-4 {{ $business->purchase_in_diff_currency != 1 ? 'hide' : '' }}" id="settings_purchase_currency_div">
                <div class="form-group">
                    <label for="purchase_currency_id">@lang('purchase.purchase_currency'):</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fas fa-money-bill-alt"></i>
                        </span>
                        <select name="purchase_currency_id" id="purchase_currency_id" class="form-control select2" style="width: 100% !important;" required>
                            @foreach($currencies as $key => $value)
                                <option value="{{ $key }}" {{ $business->purchase_currency_id == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 {{ $business->purchase_in_diff_currency != 1 ? 'hide' : '' }}" id="settings_currency_exchange_div">
                <div class="form-group">
                    <label for="p_exchange_rate">@lang('purchase.p_exchange_rate'):</label>
                    @show_tooltip(__('tooltip.currency_exchange_factor'))
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-info"></i>
                        </span>
                        <input type="number" name="p_exchange_rate" id="p_exchange_rate" class="form-control"
                            placeholder="@lang('business.p_exchange_rate')" required step="0.001"
                            value="{{ $business->p_exchange_rate }}">
                    </div>
                </div>
            </div>
        @endif

        <div class="clearfix"></div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_editing_product_from_purchase" class="input-icheck"
                            value="1" {{ $business->enable_editing_product_from_purchase ? 'checked' : '' }}>
                        @lang('lang_v1.enable_editing_product_from_purchase')
                    </label>
                    @show_tooltip(__('lang_v1.enable_updating_product_price_tooltip'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_purchase_status" id="enable_purchase_status" class="input-icheck"
                            value="1" {{ $business->enable_purchase_status ? 'checked' : '' }}>
                        @lang('lang_v1.enable_purchase_status')
                    </label>
                    @show_tooltip(__('lang_v1.tooltip_enable_purchase_status'))
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_lot_number" id="enable_lot_number" class="input-icheck"
                            value="1" {{ $business->enable_lot_number ? 'checked' : '' }}>
                        @lang('lang_v1.enable_lot_number')
                    </label>
                    @show_tooltip(__('lang_v1.tooltip_enable_lot_number'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_patch_number" id="enable_patch_number" class="input-icheck"
                            value="1" {{ $business->enable_patch_number ? 'checked' : '' }}>
                        @lang('lang_v1.enable_patch_number')
                    </label>
                    @show_tooltip(__('lang_v1.tooltip_enable_patch_number'))
                </div>
            </div>
        </div>
    </div>
</div>
