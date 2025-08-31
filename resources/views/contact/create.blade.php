<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        @php
        $form_id = 'contact_add_form';
        if (isset($quick_add)) {
        $form_id = 'quick_add_contact';
        }

        if (isset($store_action)) {
        $url = $store_action;
        $type = 'lead';
        $customer_groups = [];
        } else {
        $url = action('ContactController@store');
        $type = isset($selected_type) ? $selected_type : '';
        $sources = [];
        $life_stages = [];
        $users = [];
        }
        @endphp
        <form action="{{ $url }}" method="POST" id="{{ $form_id }}">
            @csrf


            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('contact.add_contact')</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 contact_type_div">
                        <div class="form-group">
                            <label for="contact_type">@lang('contact.contact_type'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <select name="type" id="contact_type" class="form-control" required>
                                    <option value="" disabled selected>@lang('messages.please_select')</option>
                                    @foreach ($types as $key => $value)
                                    <option value="{{ $key }}" {{ $type == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contact_id">@lang('lang_v1.contact_id'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-id-badge"></i>
                                </span>
                                <input type="text" name="contact_id" id="contact_id" class="form-control"
                                    placeholder="@lang('lang_v1.contact_id')">
                            </div>
                            <p class="help-block">@lang('lang_v1.leave_empty_to_autogenerate')</p>
                        </div>
                    </div>

                    <div class="col-md-4 customer_fields">
                        <div class="form-group">
                            <label>المجموعة التسعيرية</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-users"></i>
                                </span>
                                <?php
                                $business_id = request()->session()->get('user.business_id');
                                $price_groups = \App\SellingPriceGroup::where('business_id', $business_id)->active()->get();
                                ?>
                                <select id="price_groups" class="form-control" name="price_group_id">
                                    <option value="">افتراضي</option>
                                    @foreach ($price_groups as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="supplier_business_name">@lang('business.business_name'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </span>
                                <input type="text" name="supplier_business_name" id="supplier_business_name"
                                    class="form-control" placeholder="@lang('business.business_name')">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="prefix">{{ __('business.prefix') }}:</label>
                            <input type="text" name="prefix" id="prefix" class="form-control" placeholder="{{ __('business.prefix_placeholder') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="first_name">{{ __('business.first_name') }}:*</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required placeholder="{{ __('business.first_name') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="middle_name">{{ __('lang_v1.middle_name') }}:</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-control" placeholder="{{ __('lang_v1.middle_name') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="last_name">{{ __('business.last_name') }}:</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="{{ __('business.last_name') }}">
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="mobile">@lang('contact.mobile'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-mobile"></i>
                                </span>
                                <input type="text" name="mobile" id="mobile" class="form-control" required
                                    placeholder="@lang('contact.mobile')">
                            </div>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="alternate_number">@lang('contact.alternate_contact_number'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-phone"></i>
                                </span>
                                <input type="text" name="alternate_number" id="alternate_number" class="form-control"
                                    placeholder="@lang('contact.alternate_contact_number')">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="landline">@lang('contact.landline'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-phone"></i>
                                </span>
                                <input type="text" name="landline" id="landline" class="form-control"
                                    placeholder="@lang('contact.landline')">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email">@lang('business.email'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="@lang('business.email')">
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="dob">@lang('lang_v1.dob'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" name="dob" id="dob"
                                    class="form-control dob-date-picker" placeholder="@lang('lang_v1.dob')" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- lead additional field -->
                    <div class="col-md-4 lead_additional_div">
                        <div class="form-group">
                            <label for="crm_source">@lang('lang_v1.source'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-search"></i>
                                </span>
                                <select name="crm_source" id="crm_source" class="form-control">
                                    <option value="" disabled selected>@lang('messages.please_select')</option>
                                    @foreach ($sources as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 lead_additional_div">
                        <div class="form-group">
                            <label for="crm_life_stage">@lang('lang_v1.life_stage'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-life-ring"></i>
                                </span>
                                <select name="crm_life_stage" id="crm_life_stage" class="form-control">
                                    <option value="" disabled selected>@lang('messages.please_select')</option>
                                    @foreach ($life_stages as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 lead_additional_div">
                        <div class="form-group">
                            <label for="user_id">@lang('lang_v1.assigned_to'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <select name="user_id[]" id="user_id" class="form-control select2" multiple
                                    required style="width: 100%;">
                                    @foreach ($users as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary center-block more_btn"
                            data-target="#more_div">@lang('lang_v1.more_info') <i class="fa fa-chevron-down"></i></button>
                    </div>

                    <div id="more_div" class="hide">

                        <input type="hidden" name="position" id="position" value="">

                        <div class="col-md-12">
                            <hr />
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tax_number">@lang('contact.tax_no'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <input type="text" name="tax_number" id="tax_number" class="form-control"
                                        placeholder="@lang('contact.tax_no')">
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('trn', __('contact.trn') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                {!! Form::text('trn', null, ['class' => 'form-control', 'placeholder' => __('contact.trn')]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('plot_identification', __('contact.plot_identification') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                {!! Form::text('plot_identification', null, ['class' => 'form-control', 'placeholder' => __('contact.plot_identification')]) !!}
                            </div>
                        </div>
                    </div> --}}


                        <div class="col-md-4 opening_balance">
                            <div class="form-group">
                                <label for="opening_balance">@lang('lang_v1.opening_balance'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </span>
                                    <input type="text" name="opening_balance" id="opening_balance"
                                        class="form-control input_number" value="0">
                                </div>
                            </div>
                        </div>

                        @unless (empty($isEnabledModuleTracker))
                        <div class="col-md-4 track_id">
                            <div class="form-group">
                                <label for="track_id">@lang('tracker::lang.user_track'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fas fa-map"></i>
                                    </span>
                                    <select name="track_id" id="track_id" class="form-control">
                                        <option value="" disabled selected>@lang('messages.please_select')</option>
                                        @foreach ($trackers as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endunless

                        <div class="col-md-4 pay_term">
                            <div class="form-group">
                                <div class="multi-input">
                                    <label for="pay_term_number">@lang('contact.pay_term'):</label>
                                    @show_tooltip(__('tooltip.pay_term'))
                                    <br />
                                    <input type="number" name="pay_term_number" id="pay_term_number"
                                        class="form-control width-40 pull-left" placeholder="@lang('contact.pay_term')">

                                    <select name="pay_term_type" id="pay_term_type"
                                        class="form-control width-60 pull-left">
                                        <option value="" disabled selected>@lang('messages.please_select')</option>
                                        <option value="months">@lang('lang_v1.months')</option>
                                        <option value="days">@lang('lang_v1.days')</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        @php
                        $common_settings = session()->get('business.common_settings');
                        $default_credit_limit = !empty($common_settings['default_credit_limit'])
                        ? $common_settings['default_credit_limit']
                        : null;
                        @endphp

                        <div class="col-md-4 customer_fields">
                            <div class="form-group">
                                <label for="credit_limit">@lang('lang_v1.credit_limit'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </span>
                                    <input type="text" name="credit_limit" id="credit_limit"
                                        class="form-control input_number" value="{{ $default_credit_limit ?? '' }}">
                                </div>
                                <p class="help-block">@lang('lang_v1.credit_limit_help')</p>
                            </div>
                        </div>

                        {{-- -------------------- --}}
                        <div class="col-md-12">
                            <hr />
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_line_1">@lang('lang_v1.address_line_1'):</label>
                                <input type="text" name="address_line_1" id="address_line_1" class="form-control"
                                    placeholder="@lang('lang_v1.address_line_1')">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_line_2">@lang('lang_v1.address_line_2'):</label>
                                <input type="text" name="address_line_2" id="address_line_2" class="form-control"
                                    placeholder="@lang('lang_v1.address_line_2')">
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="city">@lang('business.city'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </span>
                                    <input type="text" name="city" id="city" class="form-control"
                                        placeholder="@lang('business.city')">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="state">@lang('business.state'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </span>
                                    <input type="text" name="state" id="state" class="form-control"
                                        placeholder="@lang('business.state')">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="country">@lang('business.country'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-globe"></i>
                                    </span>
                                    <input type="text" name="country" id="country" class="form-control"
                                        placeholder="@lang('business.country')">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="zip_code">@lang('business.zip_code'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </span>
                                    <input type="text" name="zip_code" id="zip_code" class="form-control"
                                        placeholder="@lang('business.zip_code_placeholder')">
                                </div>
                            </div>
                        </div>

                        {{-- --------------- --}}
                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <hr />
                        </div>

                        @php
                        $custom_labels = json_decode(session('business.custom_labels'), true);
                        @endphp

                        @for ($i = 1; $i <= 10; $i++)
                            @php
                            $field_label=$custom_labels['contact']["custom_field_$i"] ??
                            __('lang_v1.custom_field', ['number'=> $i]);
                            @endphp
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="custom_field{{ $i }}">{{ $field_label }}:</label>
                                    <input type="text" name="custom_field{{ $i }}"
                                        id="custom_field{{ $i }}" class="form-control"
                                        placeholder="{{ $field_label }}">
                                </div>
                            </div>
                            @endfor

                            <div class="col-md-12 shipping_addr_div">
                                <hr>
                            </div>

                            <div class="col-md-8 col-md-offset-2 shipping_addr_div">
                                <strong>@lang('lang_v1.shipping_address')</strong><br>
                                <input type="text" name="shipping_address" id="shipping_address" class="form-control"
                                    placeholder="@lang('lang_v1.search_address')">
                                <div id="map"></div>
                            </div>

                    </div>
                </div>
                @include('layouts.partials.module_form_part')
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>

        </form>


    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $(document).on('change', '#tax_number', function(e) {
        // e.preventDefault();
        if ($(this).val().length > 0) {
            $('#address_line_1,#city,#state,#country,#zip_code').attr('required', '');
        } else {
            $('#address_line_1,#city,#state,#country,#zip_code').removeAttr('required');
        }
    })
</script>