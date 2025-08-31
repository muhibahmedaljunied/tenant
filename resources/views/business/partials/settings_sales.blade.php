<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="default_sales_discount">@lang('business.default_sales_discount'):</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                    <input type="text" name="default_sales_discount" id="default_sales_discount"
                        class="form-control input_number"
                        value="{{ @num_format($business->default_sales_discount) }}">
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="default_sales_tax">@lang('business.default_sales_tax'):</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <select name="default_sales_tax" id="default_sales_tax" class="form-control select2" style="width:100%;">
                        <option value="" disabled selected>@lang('business.default_sales_tax')</option>
                        @foreach($tax_rates as $key => $value)
                            <option value="{{ $key }}" {{ $business->default_sales_tax == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="sales_cmsn_agnt">@lang('lang_v1.sales_commission_agent'):</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <select name="sales_cmsn_agnt" id="sales_cmsn_agnt" class="form-control select2" style="width:100%;">
                        @foreach($commission_agent_dropdown as $key => $value)
                            <option value="{{ $key }}" {{ $business->sales_cmsn_agnt == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="item_addition_method">@lang('lang_v1.sales_item_addition_method'):</label>
                <select name="item_addition_method" id="item_addition_method" class="form-control select2" style="width:100%;">
                    <option value="0" {{ $business->item_addition_method == 0 ? 'selected' : '' }}>@lang('lang_v1.add_item_in_new_row')</option>
                    <option value="1" {{ $business->item_addition_method == 1 ? 'selected' : '' }}>@lang('lang_v1.increase_item_qty')</option>
                </select>
            </div>
        </div>
    </div>
</div>
