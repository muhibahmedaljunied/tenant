

<input type="hidden" name="language" value="{{ request()->lang }}">

{{--تفاصيل الشركة --}}


<div class="div-content">
    <div style="text-align: center;
                color: #FFF;
                background-color: #31313C;
                margin: -11px -15px 0px -15px;
                border-radius: 10px 10px 0px 0px;
                padding-top: 1px;
                padding-bottom: 15px;">
        <h3 style="color: #FFFFFF">{{ env('APP_TITLE', 'AZHA-ERP') }}</h3>
    </div>

    <div class="div-content-titel">
        <p>@lang('business.business_details')</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">@lang('business.business_name'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-suitcase"></i>
                    </span>
                    <input type="text" name="name" id="name" class="form-control" placeholder="@lang('business.business_name')" required>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="currency_id">@lang('business.currency'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-money-bill-alt"></i>
                    </span>
                    <select name="currency_id" id="currency_id" class="form-control select2" style="max-width:200px" required>
                        <option value="">@lang('business.currency_placeholder')</option>
                        @foreach($currencies as $key => $currency)
                            <option value="{{ $key }}">{{ $currency }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6" style="display: none">
            <div class="form-group">
                <label for="start_date">@lang('business.start_date'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="text" name="start_date" id="start_date" class="form-control start-date-picker" placeholder="@lang('business.start_date')" readonly>
                </div>
            </div>
        </div>

        <div class="col-md-6" style="display: none">
            <div class="form-group">
                <label for="business_logo">@lang('business.upload_logo'):</label>
                <input type="file" name="business_logo" id="business_logo" accept="image/*">
            </div>
        </div>

        <div class="col-md-6" style="display: none">
            <div class="form-group">
                <label for="website">@lang('lang_v1.website'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-globe"></i>
                    </span>
                    <input type="text" name="website" id="website" class="form-control" value="http://azhasoft.com" placeholder="@lang('lang_v1.website')">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="mobile">@lang('lang_v1.business_telephone'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-phone"></i>
                    </span>
                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="@lang('lang_v1.business_telephone')">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="alternate_number">@lang('business.alternate_number'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-phone"></i>
                    </span>
                    <input type="text" name="alternate_number" id="alternate_number" class="form-control" placeholder="@lang('business.alternate_number')">
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Owner Information -->





<div class="div-content">
    <div class="div-content-titel">
        <p>@lang('business.owner_info')</p>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="surname">@lang('business.prefix'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    <input type="text" name="surname" id="surname" class="form-control" placeholder="@lang('business.prefix_placeholder')">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="first_name">@lang('business.first_name'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="@lang('business.first_name')" required>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="last_name">@lang('business.last_name'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="@lang('business.last_name')">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="username">@lang('business.username'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-user"></i>
                    </span>
                    <input type="text" name="username" id="username" class="form-control" placeholder="@lang('business.username')" required>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="email">@lang('business.email'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-envelope"></i>
                    </span>
                    <input type="text" name="email" id="email" class="form-control" placeholder="@lang('business.email')">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="password">@lang('business.password'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="@lang('business.password')" required>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="confirm_password">@lang('business.confirm_password'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="@lang('business.confirm_password')" required>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            @if(!empty($system_settings['superadmin_enable_register_tc']))
            <div class="form-group">
                <label>
                    <input type="checkbox" name="accept_tc" id="accept_tc" required class="input-icheck">
                    <u><a class="terms_condition cursor-pointer" data-toggle="modal" data-target="#tc_modal">
                        @lang('lang_v1.accept_terms_and_conditions')
                    </a></u>
                </label>
            </div>
            @include('business.partials.terms_conditions')
            @endif
        </div>
    </div>
</div>







<div class="col-md-6" style="display: none">
    <div class="form-group">
        <label for="country">@lang('business.country'):</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-globe"></i>
            </span>
            <input type="text" name="country" id="country" class="form-control" placeholder="@lang('business.country')" value="Egypt" required>
        </div>
    </div>
</div>

<div class="col-md-6" style="display: none">
    <div class="form-group">
        <label for="state">@lang('business.state'):</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
            </span>
            <input type="text" name="state" id="state" class="form-control" placeholder="@lang('business.state')" value="Cairo" required>
        </div>
    </div>
</div>

<div class="col-md-6" style="display: none">
    <div class="form-group">
        <label for="city">@lang('business.city'):</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
            </span>
            <input type="text" name="city" id="city" class="form-control" placeholder="@lang('business.city')" value="Cairo" required>
        </div>
    </div>
</div>

<div class="col-md-6" style="display: none">
    <div class="form-group">
        <label for="zip_code">@lang('business.zip_code'):</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
            </span>
            <input type="text" name="zip_code" id="zip_code" class="form-control" placeholder="@lang('business.zip_code_placeholder')" value="00426" required>
        </div>
    </div>
</div>

<div class="col-md-6" style="display: none">
    <div class="form-group">
        <label for="landmark">@lang('business.landmark'):</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
            </span>
            <input type="text" name="landmark" id="landmark" class="form-control" placeholder="@lang('business.landmark')" value="33002" required>
        </div>
    </div>
</div>

<div class="col-md-6" style="display: none">
    <div class="form-group">
        <label for="time_zone">@lang('business.time_zone'):</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-clock"></i>
            </span>
            <select name="time_zone" id="time_zone" class="form-control select2_register" required>
                <option value="">@lang('business.time_zone')</option>
                @foreach($timezone_list as $key => $timezone)
                    <option value="{{ $key }}" {{ $key == config('app.timezone') ? 'selected' : '' }}>{{ $timezone }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>



<!-- tax details -->

<div style="display: none">
    @lang('business.business_settings')

    <div class="col-md-6">
        <div class="form-group">
            <label for="tax_label_1">@lang('business.tax_1_name'):</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-info"></i>
                </span>
                <input type="text" name="tax_label_1" id="tax_label_1" class="form-control" placeholder="@lang('business.tax_1_placeholder')">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="tax_number_1">@lang('business.tax_1_no'):</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-info"></i>
                </span>
                <input type="text" name="tax_number_1" id="tax_number_1" class="form-control">
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="tax_label_2">@lang('business.tax_2_name'):</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-info"></i>
                </span>
                <input type="text" name="tax_label_2" id="tax_label_2" class="form-control" placeholder="@lang('business.tax_1_placeholder')">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="tax_number_2">@lang('business.tax_2_no'):</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-info"></i>
                </span>
                <input type="text" name="tax_number_2" id="tax_number_2" class="form-control">
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="fy_start_month">@lang('business.fy_start_month'):</label>
            @show_tooltip(__('tooltip.fy_start_month'))
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </span>
                <select name="fy_start_month" id="fy_start_month" class="form-control select2_register" required style="width:100%;">
                    @foreach($months as $key => $month)
                        <option value="{{ $key }}" {{ $key == 1 ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            <label for="accounting_method">@lang('business.accounting_method'):</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calculator"></i>
                </span>
                <select name="accounting_method" id="accounting_method" class="form-control select2_register" required style="width:100%;">
                    @foreach($accounting_methods as $key => $method)
                        <option value="{{ $key }}">{{ $method }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
