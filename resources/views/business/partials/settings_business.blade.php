<div class="pos-tab-content active">
    <div class="row">
        <!-- Business Name -->
        <div class="col-sm-4">
            <div class="form-group">
                <label for="name">{{ __('business.business_name') }}:*</label>
                <input type="text" name="name" id="name" value="{{ old('name', $business->name) }}" class="form-control" required placeholder="{{ __('business.business_name') }}">
            </div>
        </div>

        <!-- Start Date -->
        <div class="col-sm-4">
            <div class="form-group">
                <label for="start_date">{{ __('business.start_date') }}:</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="start_date" id="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($business->start_date)->format('Y-m-d')) }}" class="form-control start-date-picker" placeholder="{{ __('business.start_date') }}" readonly>
                </div>
            </div>
        </div>

        <!-- Default Profit Percent -->
        <div class="col-sm-4">
            <div class="form-group">
                <label for="default_profit_percent">{{ __('business.default_profit_percent') }}:*</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-plus-circle"></i></span>
                    <input type="text" name="default_profit_percent" id="default_profit_percent" value="{{ old('default_profit_percent', number_format($business->default_profit_percent, 2)) }}" class="form-control input_number">
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- Currency -->
        <div class="col-sm-4">
            <div class="form-group">
                <label for="currency_id">{{ __('business.currency') }}:</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fas fa-money-bill-alt"></i></span>
                    <select name="currency_id" id="currency_id" class="form-control select2" required>
                        <option value="">{{ __('business.currency') }}</option>
                        @foreach($currencies as $key => $value)
                            <option value="{{ $key }}" {{ $business->currency_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Currency Symbol Placement -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="currency_symbol_placement">{{ __('lang_v1.currency_symbol_placement') }}:</label>
                <select name="currency_symbol_placement" id="currency_symbol_placement" class="form-control select2" required>
                    <option value="before" {{ $business->currency_symbol_placement == 'before' ? 'selected' : '' }}>{{ __('lang_v1.before_amount') }}</option>
                    <option value="after" {{ $business->currency_symbol_placement == 'after' ? 'selected' : '' }}>{{ __('lang_v1.after_amount') }}</option>
                </select>
            </div>
        </div>

        <!-- Time Zone -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="time_zone">{{ __('business.time_zone') }}:</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fas fa-clock"></i></span>
                    <select name="time_zone" id="time_zone" class="form-control select2" required>
                        @foreach($timezone_list as $key => $label)
                            <option value="{{ $key }}" {{ $business->time_zone == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- Business Logo -->
        <div class="col-sm-4">
            <div class="form-group">
                <label for="business_logo">{{ __('business.upload_logo') }}:</label>
                <input type="file" name="business_logo" id="business_logo" accept="image/*">
                <p class="help-block"><i>{{ __('business.logo_help') }}</i></p>
            </div>
        </div>

        <!-- FY Start Month -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="fy_start_month">{{ __('business.fy_start_month') }}:</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <select name="fy_start_month" id="fy_start_month" class="form-control select2" required>
                        @foreach($months as $key => $month)
                            <option value="{{ $key }}" {{ $business->fy_start_month == $key ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Accounting Method -->
        <div class="col-sm-4">
            <div class="form-group">
                <label for="accounting_method">{{ __('business.accounting_method') }}:*</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calculator"></i></span>
                    <select name="accounting_method" id="accounting_method" class="form-control select2" required>
                        @foreach($accounting_methods as $key => $method)
                            <option value="{{ $key }}" {{ $business->accounting_method == $key ? 'selected' : '' }}>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- Transaction Edit Days -->
        <div class="col-sm-4">
            <div class="form-group">
                <label for="transaction_edit_days">{{ __('business.transaction_edit_days') }}:*</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                    <input type="number" name="transaction_edit_days" id="transaction_edit_days" value="{{ old('transaction_edit_days', $business->transaction_edit_days) }}" class="form-control" placeholder="{{ __('business.transaction_edit_days') }}" required>
                </div>
            </div>
        </div>

        <!-- Date Format -->
        <div class="col-sm-4">
            <div class="form-group">
                <label for="date_format">{{ __('lang_v1.date_format') }}:*</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <select name="date_format" id="date_format" class="form-control select2" required>
                        @foreach($date_formats as $format => $label)
                            <option value="{{ $format }}" {{ $business->date_format == $format ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Time Format -->
        <div class="col-sm-4">
            <div class="form-group">
                <label for="time_format">{{ __('lang_v1.time_format') }}:*</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fas fa-clock"></i></span>
                    <select name="time_format" id="time_format" class="form-control select2" required>
                        <option value="12" {{ $business->time_format == 12 ? 'selected' : '' }}>{{ __('lang_v1.12_hour') }}</option>
                        <option value="24" {{ $business->time_format == 24 ? 'selected' : '' }}>{{ __('lang_v1.24_hour') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
