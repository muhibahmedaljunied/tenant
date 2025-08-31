<div class="pos-tab-content">
    @can('accounts_routing.update')
 <form action="{{ action('AcRoutingAccountsController@update', ['type' => 'balance_sheet']) }}" method="POST" class="ac_routing">
    @csrf



    @endcan
    <div class="row">
    <div class="col-sm-6">
    <div class="form-group">
        <label for="current_period_profit_loss">{{ __('chart_of_accounts.current_period_profit_loss') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="current_period_profit_loss" name="current_period_profit_loss" class="form-control select2" required {{ !$can_update ? 'disabled' : '' }} style="width: 100%;">
                <option value="">{{ __('business.currency') }}</option>
                @foreach($masterByType['both']['empty_parent'] as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->current_period_profit_loss == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-sm-6">
    <div class="form-group">
        <label for="stage_period_profit_loss">{{ __('chart_of_accounts.stage_period_profit_loss') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="stage_period_profit_loss" name="stage_period_profit_loss" class="form-control select2" required {{ !$can_update ? 'disabled' : '' }} style="width: 100%;">
                <option value="">{{ __('business.currency') }}</option>
                @foreach($masterByType['both']['empty_parent'] as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->stage_period_profit_loss == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="assets">{{ __('chart_of_accounts.assets') }}:</label>
        <small class="text-danger">(حسابات مدينة)</small>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="assets" name="assets" class="form-control select2" required {{ !$can_update ? 'disabled' : '' }} style="width: 100%;">
                <option value="">{{ __('business.currency') }}</option>
                @foreach($masterByType['debtor']['all'] as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->assets == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="liabilities">{{ __('chart_of_accounts.liabilities') }}:</label>
        <small class="text-danger">(حسابات دائنة)</small>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="liabilities" name="liabilities" class="form-control select2" required {{ !$can_update ? 'disabled' : '' }} style="width: 100%;">
                <option value="">{{ __('business.currency') }}</option>
                @foreach($masterByType['creditor']['all'] as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->liabilities == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="equity">{{ __('chart_of_accounts.equity') }}:</label>
        <small class="text-danger">(حسابات دائنة)</small>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="equity" name="equity" class="form-control select2" required {{ !$can_update ? 'disabled' : '' }} style="width: 100%;">
                <option value="">{{ __('business.currency') }}</option>
                @foreach($masterByType['creditor']['all'] as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->equity == $key ? 'selected' : '' }}>
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
