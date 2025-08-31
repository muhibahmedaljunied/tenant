<div class="pos-tab-content">
    <div class="row well">
    <div class="col-sm-4">
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="enable_rp" id="enable_rp" class="input-icheck"
                        value="1" {{ $business->enable_rp ? 'checked' : '' }}>
                    @lang('lang_v1.enable_rp')
                </label>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label for="rp_name">@lang('lang_v1.rp_name'):</label>
            <input type="text" name="rp_name" id="rp_name" class="form-control"
                placeholder="@lang('lang_v1.rp_name')" value="{{ $business->rp_name }}">
        </div>
    </div>
</div>

<div class="row well">
    <div class="col-sm-12">
        <h4>@lang('lang_v1.earning_points_setting'):</h4>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label for="amount_for_unit_rp">@lang('lang_v1.amount_for_unit_rp'):</label>
            @show_tooltip(__('lang_v1.amount_for_unit_rp_tooltip'))
            <input type="text" name="amount_for_unit_rp" id="amount_for_unit_rp" class="form-control input_number"
                placeholder="@lang('lang_v1.amount_for_unit_rp')" value="{{ @num_format($business->amount_for_unit_rp) }}">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label for="min_order_total_for_rp">@lang('lang_v1.min_order_total_for_rp'):</label>
            @show_tooltip(__('lang_v1.min_order_total_for_rp_tooltip'))
            <input type="text" name="min_order_total_for_rp" id="min_order_total_for_rp" class="form-control input_number"
                placeholder="@lang('lang_v1.min_order_total_for_rp')" value="{{ @num_format($business->min_order_total_for_rp) }}">
        </div>
    </div>
</div>

</div>
