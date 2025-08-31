<div class="pos-tab-content">
    @can('accounts_routing.update')
        <form action="{{ action('AcRoutingAccountsController@update', ['type' => 'stocktaking']) }}" method="POST" class="ac_routing">
            @csrf
    @endcan
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="stock_taking_inc">{{ __('chart_of_accounts.increase_inventory') . ':' }}</label>
                <small class="text-danger">(حسابات دائنة)</small>

                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-money-bill-alt"></i>
                    </span>
                    <select name="increase_inventory_id" id="stock_taking_inc" class="form-control select2" style="width:100%;" placeholder="{{ __('business.currency') }}" required @if(!$can_update) disabled @endif>
                        <option value="">{{ __('business.currency') }}</option>
                        @foreach($masterByType['debtor'] as $key => $value)
                            <option value="{{ $key }}" {{ $ac_setting->increase_inventory_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="disability_inventory_id">{{ __('chart_of_accounts.decrease_inventory') . ':' }}</label>
                <small class="text-danger">(حسابات دائنة)</small>

                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-money-bill-alt"></i>
                    </span>
                    <select name="disability_inventory_id" id="disability_inventory_id" class="form-control select2" style="width:100%;" placeholder="{{ __('business.currency') }}" required @if(!$can_update) disabled @endif>
                        <option value="">{{ __('business.currency') }}</option>
                        @foreach($masterByType['debtor'] as $key => $value)
                            <option value="{{ $key }}" {{ $ac_setting->disability_inventory_id == $key ? 'selected' : '' }}>{{ $value }}</option>
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
