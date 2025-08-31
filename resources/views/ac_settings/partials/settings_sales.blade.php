<div class="pos-tab-content">
    @can('accounts_routing.update')
 <form action="{{ action('AcRoutingAccountsController@update', ['type' => 'sales']) }}" method="POST" class="ac_routing">
    @csrf



    @endcan
    <div class="row">
     <div class="col-sm-6">
    <div class="form-group">
        <label for="customers">{{ __('chart_of_accounts.customers') }}:</label>
        <small class="text-danger">(حسابات مدينة)</small>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="customers" name="customers" class="form-control select2" style="width: 100%;" required {{ !$can_update ? 'disabled' : '' }}>
                @foreach($masterByType['debtor']['all'] as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->customers == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
        <label for="sold_goods_cost">{{ __('chart_of_accounts.sold_goods_cost') }}:</label>
        <small class="text-danger">(حسابات مدينة)</small>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="sold_goods_cost" name="sold_goods_cost" class="form-control select2" style="width: 100%;" required {{ !$can_update ? 'disabled' : '' }}>
                <option value="">{{ __('business.currency') }}</option>
                @foreach($masterByType['debtor']['empty_parent'] as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->sold_goods_cost == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
        <label for="sales_service_revenue">{{ __('chart_of_accounts.sales_service_revenue') }}:</label>
        <small class="text-danger">(حسابات دائنة)</small>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="sales_service_revenue" name="sales_service_revenue" class="form-control select2" style="width: 100%;" required {{ !$can_update ? 'disabled' : '' }}>
                <option value="">{{ __('business.currency') }}</option>
                @foreach($masterByType['creditor']['empty_parent'] as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->sales_service_revenue == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
        <label for="sales_services_return">{{ __('chart_of_accounts.sales_services_return') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="sales_services_return" name="sales_services_return" class="form-control select2" style="width: 100%;" required {{ !$can_update ? 'disabled' : '' }}>
                <option value="">{{ __('business.currency') }}</option>
                @foreach($masterByType['both']['empty_parent'] as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->sales_services_return == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
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
</div>
