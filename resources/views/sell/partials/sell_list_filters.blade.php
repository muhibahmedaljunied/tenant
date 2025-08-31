@if(empty($only) || in_array('sell_list_filter_location_id', $only))
<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_location_id">@lang('purchase.business_location'):</label>
        <select name="sell_list_filter_location_id" id="sell_list_filter_location_id" class="form-control select2" style="width:100%;">
            <option value="">@lang('lang_v1.all')</option>
            @foreach ($business_locations as $key => $location)
                <option value="{{ $key }}">{{ $location }}</option>
            @endforeach
        </select>
    </div>
</div>
@endif

@if(empty($only) || in_array('sell_list_filter_store_id', $only))
<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_store_id">@lang('store.store'):</label>
        <select name="sell_list_filter_store_id" id="sell_list_filter_store_id" class="form-control select2" style="width:100%;">
      
        </select>
    </div>
</div>
@endif

@if(empty($only) || in_array('sell_list_filter_customer_id', $only))
<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_customer_id">@lang('contact.customer'):</label>
        <select name="sell_list_filter_customer_id" id="sell_list_filter_customer_id" class="form-control select2" style="width:100%;">
            <option value="">@lang('lang_v1.all')</option>
            @foreach ($customers as $key => $customer)
                <option value="{{ $key }}">{{ $customer }}</option>
            @endforeach
        </select>
    </div>
</div>
@endif

@if(empty($only) || in_array('sell_list_filter_payment_status', $only))
<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_payment_status">@lang('purchase.payment_status'):</label>
        <select name="sell_list_filter_payment_status" id="sell_list_filter_payment_status" class="form-control select2" style="width:100%;">
            <option value="">@lang('lang_v1.all')</option>
            <option value="paid">@lang('lang_v1.paid')</option>
            <option value="due">@lang('lang_v1.due')</option>
            <option value="partial">@lang('lang_v1.partial')</option>
            <option value="overdue">@lang('lang_v1.overdue')</option>
            <option value="installmented">@lang('lang_v1.installmented')</option>
        </select>
    </div>
</div>
@endif

@if(empty($only) || in_array('sell_list_filter_date_range', $only))
<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_date_range">@lang('report.date_range'):</label>
        <input type="text" name="sell_list_filter_date_range" id="sell_list_filter_date_range" class="form-control" placeholder="@lang('lang_v1.select_a_date_range')" readonly>
    </div>
</div>
@endif

@if((empty($only) || in_array('created_by', $only)) && !empty($sales_representative))
<div class="col-md-3">
    <div class="form-group">
        <label for="created_by">@lang('report.user'):</label>
        <select name="created_by" id="created_by" class="form-control select2" style="width:100%;">
            @foreach ($sales_representative as $key => $user)
                <option value="{{ $key }}">{{ $user }}</option>
            @endforeach
        </select>
    </div>
</div>
@endif

@if(empty($only) || in_array('sales_cmsn_agnt', $only))
@if(!empty($is_cmsn_agent_enabled))
<div class="col-md-3">
    <div class="form-group">
        <label for="sales_cmsn_agnt">@lang('lang_v1.sales_commission_agent'):</label>
        <select name="sales_cmsn_agnt" id="sales_cmsn_agnt" class="form-control select2" style="width:100%;">
            @foreach ($commission_agents as $key => $agent)
                <option value="{{ $key }}">{{ $agent }}</option>
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
        <label for="service_staffs">@lang('restaurant.service_staff'):</label>
        <select name="service_staffs" id="service_staffs" class="form-control select2" style="width:100%;">
            <option value="">@lang('lang_v1.all')</option>
            @foreach ($service_staffs as $key => $staff)
                <option value="{{ $key }}">{{ $staff }}</option>
            @endforeach
        </select>
    </div>
</div>
@endif
@endif

@if(!empty($shipping_statuses))
<div class="col-md-3">
    <div class="form-group">
        <label for="shipping_status">@lang('lang_v1.shipping_status'):</label>
        <select name="shipping_status" id="shipping_status" class="form-control select2" style="width:100%;">
            <option value="">@lang('lang_v1.all')</option>
            @foreach ($shipping_statuses as $key => $status)
                <option value="{{ $key }}">{{ $status }}</option>
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
                <input type="checkbox" name="only_subscriptions" id="only_subscriptions" class="input-icheck" value="1">
                @lang('lang_v1.subscriptions')
            </label>
        </div>
    </div>
</div>
@endif
