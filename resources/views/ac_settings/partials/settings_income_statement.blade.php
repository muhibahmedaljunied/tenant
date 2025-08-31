<div class="pos-tab-content active">
    @can('accounts_routing.update')
        <form action="{{ action('AcRoutingAccountsController@update', ['type' => 'income_statement']) }}" method="POST"
            class="ac_routing">
            @csrf
        @endcan
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="operating_income">{{ __('chart_of_accounts.operating_income') }}:</label>
                    <small class="text-danger">(حسابات دائنة)</small>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fas fa-money-bill-alt"></i>
                        </span>
                        <select id="operating_income" name="operating_income" class="form-control select2" required
                            {{ !$can_update ? 'disabled' : '' }}>
                            @foreach ($masterByType['creditor']['all'] as $key => $value)
                                <option value="{{ $key }}"
                                    {{ $ac_setting->operating_income == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="direct_expenses">{{ __('chart_of_accounts.direct_expenses') }}:</label>
                    <small class="text-danger">(حسابات دائنة)</small>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fas fa-money-bill-alt"></i>
                        </span>
                        <select id="direct_expenses" name="direct_expenses" class="form-control select2" required
                            {{ !$can_update ? 'disabled' : '' }}>
                            @foreach ($masterByType['creditor']['all'] as $key => $value)
                                <option value="{{ $key }}"
                                    {{ $ac_setting->direct_expenses == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="non_operating_income">{{ __('chart_of_accounts.non_operating_income') }}:</label>
                    <small class="text-danger">(حسابات دائنة)</small>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fas fa-money-bill-alt"></i>
                        </span>
                        <select id="non_operating_income" name="non_operating_income" class="form-control select2"
                            required {{ !$can_update ? 'disabled' : '' }}>
                            @foreach ($masterByType['creditor']['all'] as $key => $value)
                                <option value="{{ $key }}"
                                    {{ $ac_setting->non_operating_income == $key ? 'selected' : '' }}>
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
                    <label for="operating_expenses">{{ __('chart_of_accounts.operating_expenses') }}:</label>
                    <small class="text-danger">(حسابات دائنة)</small>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fas fa-money-bill-alt"></i>
                        </span>
                        <select id="operating_expenses" name="operating_expenses" class="form-control select2" required
                            {{ !$can_update ? 'disabled' : '' }}>
                            @foreach ($masterByType['creditor']['all'] as $key => $value)
                                <option value="{{ $key }}"
                                    {{ $ac_setting->operating_expenses == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="non_operating_expenses">{{ __('chart_of_accounts.non_operating_expenses') }}:</label>
                    <small class="text-danger">(حسابات دائنة)</small>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fas fa-money-bill-alt"></i>
                        </span>
                        <select id="non_operating_expenses" name="non_operating_expenses" class="form-control select2"
                            required {{ !$can_update ? 'disabled' : '' }}>
                            @foreach ($masterByType['creditor']['all'] as $key => $value)
                                <option value="{{ $key }}"
                                    {{ $ac_setting->non_operating_expenses == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="sales_return">{{ __('chart_of_accounts.sales_return') }}:</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fas fa-money-bill-alt"></i>
                        </span>
                        <select id="sales_return" name="sales_return" class="form-control select2" required
                            {{ !$can_update ? 'disabled' : '' }}>
                            @foreach ($masterByType['both']['all'] as $key => $value)
                                <option value="{{ $key }}"
                                    {{ $ac_setting->sales_return == $key ? 'selected' : '' }}>
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
