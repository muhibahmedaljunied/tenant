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
      
            </div>

            

        </form>


    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
