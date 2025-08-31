<div class="pos-tab-content">
    @can('accounts_routing.update')
        <form action="{{ action('AcRoutingAccountsController@update', ['type' => 'vat_due']) }}" method="POST" class="ac_routing">
            @csrf
    @endcan
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="vat_due">{{ __('chart_of_accounts.vat_due') . ':' }}</label>
                <small class="text-danger">(حسابات دائنة)</small>

                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-money-bill-alt"></i>
                    </span>
                    <select name="vat_due" id="vat_due" class="form-control select2" placeholder="{{ __('business.currency') }}" required @if(!$can_update) disabled @endif>
                        <option value="">{{ __('business.currency') }}</option>
                        @foreach($masterByType['debtor'] as $key => $value)
                            <option value="{{ $key }}" {{ $ac_setting->vat_due == $key ? 'selected' : '' }}>{{ $value }}</option>
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
