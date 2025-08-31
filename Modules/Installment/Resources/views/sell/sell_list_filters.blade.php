@if(empty($only) || in_array('sell_list_filter_location_id', $only))
<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_location_id">{{ __('purchase.business_location') . ':' }}</label>
        <select name="sell_list_filter_location_id" id="sell_list_filter_location_id" class="form-control select2" style="width:100%">
            <option value="">{{ __('lang_v1.all') }}</option>
            @foreach($business_locations as $key => $value)
                <option value="{{ $key }}" {{ old('sell_list_filter_location_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>
@endif
@if(empty($only) || in_array('sell_list_filter_customer_id', $only))
<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_customer_id">{{ __('contact.customer') . ':' }}</label>
        <select name="sell_list_filter_customer_id" id="sell_list_filter_customer_id" class="form-control select2" style="width:100%">
            <option value="">{{ __('lang_v1.all') }}</option>
            @foreach($customers as $key => $value)
                <option value="{{ $key }}" {{ old('sell_list_filter_customer_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>
@endif
@if(empty($only) || in_array('sell_list_filter_payment_status', $only))
<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_payment_status">{{ __('purchase.payment_status') . ':' }}</label>
        <select name="sell_list_filter_payment_status" id="sell_list_filter_payment_status" class="form-control select2" style="width:100%">
            <option value="">{{ __('lang_v1.all') }}</option>
            <option value="paid" {{ old('sell_list_filter_payment_status') == 'paid' ? 'selected' : '' }}>{{ __('lang_v1.paid') }}</option>
            <option value="due" {{ old('sell_list_filter_payment_status') == 'due' ? 'selected' : '' }}>{{ __('lang_v1.due') }}</option>
            <option value="partial" {{ old('sell_list_filter_payment_status') == 'partial' ? 'selected' : '' }}>{{ __('lang_v1.partial') }}</option>
            <option value="overdue" {{ old('sell_list_filter_payment_status') == 'overdue' ? 'selected' : '' }}>{{ __('lang_v1.overdue') }}</option>
        </select>
    </div>
</div>
@endif
@if(empty($only) || in_array('sell_list_filter_date_range', $only))
<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_date_range">{{ __('report.date_range') . ':' }}</label>
        <input type="text" name="sell_list_filter_date_range" id="sell_list_filter_date_range" placeholder="{{ __('lang_v1.select_a_date_range') }}" class="form-control" readonly>
    </div>
</div>
@endif
@if((empty($only) || in_array('created_by', $only)) && !empty($sales_representative))
<div class="col-md-3">
    <div class="form-group">
        <label for="created_by">{{ __('report.user') . ':' }}</label>
        <select name="created_by" id="created_by" class="form-control select2" style="width:100%">
            @foreach($sales_representative as $key => $value)
                <option value="{{ $key }}" {{ old('created_by') == $key ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>
@endif
@if(empty($only) || in_array('sales_cmsn_agnt', $only))
@if(!empty($is_cmsn_agent_enabled))
    <div class="col-md-3">
        <div class="form-group">
            <label for="sales_cmsn_agnt">{{ __('lang_v1.sales_commission_agent') . ':' }}</label>
            <select name="sales_cmsn_agnt" id="sales_cmsn_agnt" class="form-control select2" style="width:100%">
                @foreach($commission_agents as $key => $value)
                    <option value="{{ $key }}" {{ old('sales_cmsn_agnt') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif
@endif
@if(empty($only) || in_array('service_staffs', $only))
@if(!empty($service_staffs))
    <div class="col-md-3">
        <div class="form-group">
            <label for="service_staffs">{{ __('restaurant.service_staff') . ':' }}</label>
            <select name="service_staffs" id="service_staffs" class="form-control select2" style="width:100%">
                <option value="">{{ __('lang_v1.all') }}</option>
                @foreach($service_staffs as $key => $value)
                    <option value="{{ $key }}" {{ old('service_staffs') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif
@endif
@if(!empty($shipping_statuses))
    <div class="col-md-3">
        <div class="form-group">
            <label for="shipping_status">{{ __('lang_v1.shipping_status') . ':' }}</label>
            <select name="shipping_status" id="shipping_status" class="form-control select2" style="width:100%">
                <option value="">{{ __('lang_v1.all') }}</option>
                @foreach($shipping_statuses as $key => $value)
                    <option value="{{ $key }}" {{ old('shipping_status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif
@if(empty($only) || in_array('only_subscriptions', $only))
<div class="col-md-3">
    <div class="form-group">
        <div class="checkbox">
            <label>
                <br>
                <input type="checkbox" name="only_subscriptions" id="only_subscriptions" value="1" class="input-icheck"> {{ __('lang_v1.subscriptions') }}
            </label>
        </div>
    </div>
</div>
@endif