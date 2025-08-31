<div class="pos-tab-content">
    @can('accounts_routing.update')
<form action="{{ action('AcRoutingAccountsController@update', ['type' => 'purchases']) }}" method="POST" class="ac_routing">
    @csrf



    @endcan
    <div class="row">
      <div class="col-sm-6">
    <div class="form-group">
        <label for="suppliers">{{ __('chart_of_accounts.suppliers') }}:</label>
        <small class="text-danger">(حسابات دائنة)</small>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="suppliers" name="suppliers" class="form-control select2" required {{ !$can_update ? 'disabled' : '' }}>
                <option value="">{{ __('business.currency') }}</option>
                @foreach($masterByType['creditor']['all'] as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->suppliers == $key ? 'selected' : '' }}>
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
