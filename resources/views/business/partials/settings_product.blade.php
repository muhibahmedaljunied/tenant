<div class="pos-tab-content">
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label for="sku_prefix">@lang('business.sku_prefix'):</label>
            <input type="text" name="sku_prefix" id="sku_prefix" class="form-control text-uppercase"
                value="{{ $business->sku_prefix }}">
        </div>
    </div>

    <div class="col-sm-4">
        <label for="enable_product_expiry">@lang('product.enable_product_expiry'):</label>
        @show_tooltip(__('lang_v1.tooltip_enable_expiry'))
        <div class="input-group">
            <span class="input-group-addon">
                <input type="checkbox" name="enable_product_expiry" id="enable_product_expiry"
                    value="1" {{ $business->enable_product_expiry ? 'checked' : '' }}>
            </span>
            <select class="form-control" id="expiry_type" name="expiry_type"
                {{ !$business->enable_product_expiry ? 'disabled' : '' }}>
                <option value="add_expiry" {{ $business->expiry_type == 'add_expiry' ? 'selected' : '' }}>
                    @lang('lang_v1.add_expiry')
                </option>
                <option value="add_manufacturing" {{ $business->expiry_type == 'add_manufacturing' ? 'selected' : '' }}>
                    @lang('lang_v1.add_manufacturing_auto_expiry')
                </option>
            </select>
        </div>
    </div>

    <div class="col-sm-4 {{ !$business->enable_product_expiry ? 'hide' : '' }}" id="on_expiry_div">
        <div class="form-group">
            <label for="on_product_expiry">@lang('lang_v1.on_product_expiry'):</label>
            @show_tooltip(__('lang_v1.tooltip_on_product_expiry'))
            <select name="on_product_expiry" id="on_product_expiry" class="form-control pull-left"
                style="width:60%;">
                <option value="keep_selling" {{ $business->on_product_expiry == 'keep_selling' ? 'selected' : '' }}>
                    @lang('lang_v1.keep_selling')
                </option>
                <option value="stop_selling" {{ $business->on_product_expiry == 'stop_selling' ? 'selected' : '' }}>
                    @lang('lang_v1.stop_selling')
                </option>
            </select>

            @php
                $disabled = $business->on_product_expiry == 'keep_selling' ? 'disabled' : '';
            @endphp
            <input type="number" name="stop_selling_before" id="stop_selling_before"
                class="form-control pull-left" placeholder="stop n days before" style="width:40%;"
                value="{{ $business->stop_selling_before }}" {{ $disabled }} required>
        </div>
    </div>
</div>

</div>