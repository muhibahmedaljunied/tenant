<div class="pos-tab-content">
    @can('accounts_routing.update')
  <form action="{{ action('AcRoutingAccountsController@update', ['type' => 'inventory_sec']) }}" method="POST" class="ac_routing">
    @csrf



    @endcan
    <div class="row">
   <div class="col-sm-6">
    <div class="form-group">
        <label for="inventory_transactions">{{ __('chart_of_accounts.inventory') }}:</label>
        <small class="text-danger">(حسابات مدينة)</small>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="inventory_transactions" name="inventory_transactions" class="form-control select2" style="width: 100%;" {{ !$can_update ? 'disabled' : '' }}>
                <option value="">{{ __('business.currency') }}</option>
                @foreach($masterByType['debtor']['empty_parent'] as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->inventory == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
        <label for="stock_opening_account">{{ __('chart_of_accounts.stock_opening_account') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="stock_opening_account" name="stock_opening_account" class="form-control select2" style="width: 100%;" {{ !$can_update ? 'disabled' : '' }} data-placeholder="{{ __('chart_of_accounts.stock_opening_account') }}">
                <option value="">{{ __('chart_of_accounts.stock_opening_account') }}</option>
                @foreach($masterByType['both']['empty_parent'] as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->stock_opening_account == $key ? 'selected' : '' }}>
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
        <div class="clearfix"></div>
    </div>
</div>
