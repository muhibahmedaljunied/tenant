<div class="pos-tab-content">
    @can('accounts_routing.update')
  <form action="{{ action('AcRoutingAccountsController@update', ['type' => 'payment_methods']) }}" method="POST" class="ac_routing">
    @csrf



    @endcan
    <div class="row">
   <div class="col-sm-4">
    <div class="form-group">
        <label for="payment_method_cash">{{ __('chart_of_accounts.cash') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="payment_method_cash" name="payment_method_cash" class="form-control select2" style="width: 100%;" required {{ !$can_update ? 'disabled' : '' }}>
                <option value="">{{ __('Select Account') }}</option>
                @foreach($sale_accounts as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->payment_method_cash == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="payment_method_card">{{ __('chart_of_accounts.card') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="payment_method_card" name="payment_method_card" class="form-control select2" style="width: 100%;" required {{ !$can_update ? 'disabled' : '' }}>
                <option value="">{{ __('Select Account') }}</option>
                @foreach($sale_accounts as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->payment_method_card == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="payment_method_cheque">{{ __('chart_of_accounts.cheque') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="payment_method_cheque" name="payment_method_cheque" class="form-control select2" style="width: 100%;" required {{ !$can_update ? 'disabled' : '' }}>
                <option value="">{{ __('Select Account') }}</option>
                @foreach($sale_accounts as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->payment_method_cheque == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="payment_method_bank_transfer">{{ __('chart_of_accounts.bank_transfer') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="payment_method_bank_transfer" name="payment_method_bank_transfer" class="form-control select2" style="width: 100%;" required {{ !$can_update ? 'disabled' : '' }}>
                <option value="">{{ __('Select Account') }}</option>
                @foreach($sale_accounts as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->payment_method_bank_transfer == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="payment_method_other">{{ __('chart_of_accounts.payment_method_other') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="payment_method_other" name="payment_method_other" class="form-control select2" style="width: 100%;" required {{ !$can_update ? 'disabled' : '' }}>
                <option value="">{{ __('Select Account') }}</option>
                @foreach($sale_accounts as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->payment_method_other == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="payment_method_custom_pay_1">{{ __('chart_of_accounts.on_delivery') }}:</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="payment_method_custom_pay_1" name="payment_method_custom_pay_1" class="form-control select2" style="width: 100%;" required {{ !$can_update ? 'disabled' : '' }}>
                <option value="">{{ __('Select Account') }}</option>
                @foreach($sale_accounts as $key => $value)
                    <option value="{{ $key }}" {{ $ac_setting->payment_method_custom_pay_1 == $key ? 'selected' : '' }}>
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
