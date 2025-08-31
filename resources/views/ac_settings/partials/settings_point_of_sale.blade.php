<div class="pos-tab-content">
    @can('accounts_routing.update')
<form action="{{ action('AcRoutingAccountsController@update', ['type' => 'point_of_sale']) }}" method="POST" class="ac_routing">
    @csrf



    @endcan
    <div class="row">
     <div class="col-sm-6">
    <div class="form-group">
        <label for="branch_cost_center_id">{{ __('chart_of_accounts.branch_cost_center') }}:</label>
        <select id="branch_cost_center_id" name="branch_cost_center_id" class="form-control select2" style="width: 100%;" required {{ !$can_update ? 'disabled' : '' }}>
            @foreach($branch_cost_centers as $key => $value)
                <option value="{{ $key }}" {{ $ac_setting->branch_cost_center_id == $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
        <label for="extra_cost_center_id">{{ __('chart_of_accounts.extra__cost_center') }}:</label>
        <select id="extra_cost_center_id" name="extra_cost_center_id" class="form-control select2" style="width: 100%;" required {{ !$can_update ? 'disabled' : '' }}>
            @foreach($extra_cost_centers as $key => $value)
                <option value="{{ $key }}" {{ $ac_setting->extra_cost_center_id == $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
</div>

        @can('accounts_routing.update')
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-danger pull-right" type="submit">@lang('business.update_settings')</button>
                </div>
            </div>
    </form>
        @endcan
    </div>
    <div class="clearfix"></div>
</div>
