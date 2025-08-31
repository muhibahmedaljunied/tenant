<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        @php
            if (isset($update_action)) {
                $url = $update_action;
                $customer_groups = [];
                $opening_balance = 0;
                $lead_users = $contact->leadUsers->pluck('id');
            } else {
                $url = action('ContactController@update', [$contact->id]);
                $sources = [];
                $life_stages = [];
                $users = [];
                $lead_users = [];
            }
        @endphp

        <form action="{{ $url }}" method="POST" id="contact_edit_form">
            @csrf
            @method('PUT')

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('contact.edit_contact')</h4>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="contact_type">{{ __('contact.contact_type') }}:*</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <select name="type" id="contact_type" class="form-control" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($types as $key => $value)
                                    <option value="{{ $key }}" {{ old('type', $contact->type) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="contact_id">{{ __('lang_v1.contact_id') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-id-badge"></i>
                            </span>
                            <input type="hidden" id="hidden_id" value="{{ $contact->id }}">
                            <input type="text" name="contact_id" id="contact_id" class="form-control" placeholder="{{ __('lang_v1.contact_id') }}" value="{{ old('contact_id', $contact->contact_id) }}">
                        </div>
                        <p class="help-block">
                            @lang('lang_v1.leave_empty_to_autogenerate')
                        </p>
                    </div>
                </div>
                <div class="col-md-4 customer_fields">
                    <div class="form-group">
                        <label for="customer_group_id">{{ __('lang_v1.customer_group') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-users"></i>
                            </span>
                            <select name="customer_group_id" id="customer_group_id" class="form-control">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($customer_groups as $key => $value)
                                    <option value="{{ $key }}" {{ old('customer_group_id', $contact->customer_group_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="clearfix customer_fields"></div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="supplier_business_name">{{ __('business.business_name') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-briefcase"></i>
                            </span>
                            <input type="text" name="supplier_business_name" id="supplier_business_name" class="form-control" placeholder="{{ __('business.business_name') }}" value="{{ old('supplier_business_name', $contact->supplier_business_name) }}">
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="prefix">{{ __('business.prefix') }}:</label>
                        <input type="text" name="prefix" id="prefix" class="form-control" placeholder="{{ __('business.prefix_placeholder') }}" value="{{ old('prefix', $contact->prefix) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="first_name">{{ __('business.first_name') }}:*</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required placeholder="{{ __('business.first_name') }}" value="{{ old('first_name', $contact->first_name) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="middle_name">{{ __('lang_v1.middle_name') }}:</label>
                        <input type="text" name="middle_name" id="middle_name" class="form-control" placeholder="{{ __('lang_v1.middle_name') }}" value="{{ old('middle_name', $contact->middle_name) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="last_name">{{ __('business.last_name') }}:</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="{{ __('business.last_name') }}" value="{{ old('last_name', $contact->last_name) }}">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="mobile">{{ __('contact.mobile') }}:*</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-mobile"></i>
                            </span>
                            <input type="text" name="mobile" id="mobile" class="form-control" required placeholder="{{ __('contact.mobile') }}" value="{{ old('mobile', $contact->mobile) }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="alternate_number">{{ __('contact.alternate_contact_number') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-phone"></i>
                            </span>
                            <input type="text" name="alternate_number" id="alternate_number" class="form-control" placeholder="{{ __('contact.alternate_contact_number') }}" value="{{ old('alternate_number', $contact->alternate_number) }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="landline">{{ __('contact.landline') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-phone"></i>
                            </span>
                            <input type="text" name="landline" id="landline" class="form-control" placeholder="{{ __('contact.landline') }}" value="{{ old('landline', $contact->landline) }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="email">{{ __('business.email') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('business.email') }}" value="{{ old('email', $contact->email) }}">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="dob">{{ __('lang_v1.dob') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input type="text" name="dob" id="dob" class="form-control dob-date-picker" placeholder="{{ __('lang_v1.dob') }}" value="{{ old('dob', !empty($contact->dob) ? @format_date($contact->dob) : null) }}" readonly>
                        </div>
                    </div>
                </div>
                <!-- lead additional field -->
                <div class="col-md-4 lead_additional_div">
                    <div class="form-group">
                        <label for="crm_source">{{ __('lang_v1.source') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa fa-search"></i>
                            </span>
                            <select name="crm_source" id="crm_source" class="form-control">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($sources as $key => $value)
                                    <option value="{{ $key }}" {{ old('crm_source', $contact->crm_source) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 lead_additional_div">
                    <div class="form-group">
                        <label for="crm_life_stage">{{ __('lang_v1.life_stage') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa fa-life-ring"></i>
                            </span>
                            <select name="crm_life_stage" id="crm_life_stage" class="form-control">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($life_stages as $key => $value)
                                    <option value="{{ $key }}" {{ old('crm_life_stage', $contact->crm_life_stage) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 lead_additional_div">
                    <div class="form-group">
                        <label for="user_id">{{ __('lang_v1.assigned_to') }}:*</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <select name="user_id[]" id="user_id" class="form-control select2" multiple required style="width: 100%;">
                                @foreach($users as $key => $value)
                                    <option value="{{ $key }}" {{ (collect(old('user_id', $lead_users))->contains($key)) ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary center-block more_btn" data-target="#more_div">@lang('lang_v1.more_info') <i class="fa fa-chevron-down"></i></button>
                </div>
                <div id="more_div" class="hide">
                    <div class="col-md-12">
                        <hr />
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tax_number">{{ __('contact.tax_no') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                <input type="text" name="tax_number" id="tax_number" class="form-control" placeholder="{{ __('contact.tax_no') }}" value="{{ old('tax_number', $contact->tax_number) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="commercial_registration_number">{{ __('contact.commercial_registration_number') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                <input type="text" name="commercial_registration_number" id="commercial_registration_number" class="form-control" placeholder="{{ __('contact.commercial_registration_number') }}" value="{{ old('commercial_registration_number', $contact->commercial_registration_number) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 opening_balance">
                        <div class="form-group">
                            <label for="opening_balance">{{ __('lang_v1.opening_balance') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-money-bill-alt"></i>
                                </span>
                                <input type="text" name="opening_balance" id="opening_balance" class="form-control input_number" value="{{ old('opening_balance', $opening_balance) }}">
                            </div>
                        </div>
                    </div>
                    @if ($isEnabledModuleTracker)
                        <div class="col-md-4 track_id">
                            <div class="form-group">
                                <label for="track_id">{{ __('tracker::lang.user_track') }}:</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fas fa-map"></i>
                                    </span>
                                    <select name="track_id" id="track_id" class="form-control">
                                        <option value="">{{ __('messages.please_select') }}</option>
                                        @foreach($trackers as $key => $value)
                                            <option value="{{ $key }}" {{ old('track_id', $contact->track_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-4 pay_term">
                        <div class="form-group">
                            <div class="multi-input">
                                <label for="pay_term_number">{{ __('contact.pay_term') }}:</label> @show_tooltip(__('tooltip.pay_term'))
                                <br />
                                <input type="number" name="pay_term_number" id="pay_term_number" class="form-control width-40 pull-left" placeholder="{{ __('contact.pay_term') }}" value="{{ old('pay_term_number', $contact->pay_term_number) }}">
                                <select name="pay_term_type" id="pay_term_type" class="form-control width-60 pull-left">
                                    <option value="">{{ __('messages.please_select') }}</option>
                                    <option value="months" {{ old('pay_term_type', $contact->pay_term_type) == 'months' ? 'selected' : '' }}>{{ __('lang_v1.months') }}</option>
                                    <option value="days" {{ old('pay_term_type', $contact->pay_term_type) == 'days' ? 'selected' : '' }}>{{ __('lang_v1.days') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4 customer_fields">
                        <div class="form-group">
                            <label for="credit_limit">{{ __('lang_v1.credit_limit') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-money-bill-alt"></i>
                                </span>
                                <input type="text" name="credit_limit" id="credit_limit" class="form-control input_number" value="{{ old('credit_limit', $contact->credit_limit != null ? @num_format($contact->credit_limit) : null) }}">
                            </div>
                            <p class="help-block">@lang('lang_v1.credit_limit_help')</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr />
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address_line_1">{{ __('lang_v1.address_line_1') }}:</label>
                            <input type="text" name="address_line_1" id="address_line_1" class="form-control" placeholder="{{ __('lang_v1.address_line_1') }}" value="{{ old('address_line_1', $contact->address_line_1) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address_line_2">{{ __('lang_v1.address_line_2') }}:</label>
                            <input type="text" name="address_line_2" id="address_line_2" class="form-control" placeholder="{{ __('lang_v1.address_line_2') }}" value="{{ old('address_line_2', $contact->address_line_2) }}">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="city">{{ __('business.city') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                <input type="text" name="city" id="city" class="form-control" placeholder="{{ __('business.city') }}" value="{{ old('city', $contact->city) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="state">{{ __('business.state') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                <input type="text" name="state" id="state" class="form-control" placeholder="{{ __('business.state') }}" value="{{ old('state', $contact->state) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="country">{{ __('business.country') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-globe"></i>
                                </span>
                                <input type="text" name="country" id="country" class="form-control" placeholder="{{ __('business.country') }}" value="{{ old('country', $contact->country) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="zip_code">{{ __('business.zip_code') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                <input type="text" name="zip_code" id="zip_code" class="form-control" placeholder="{{ __('business.zip_code_placeholder') }}" value="{{ old('zip_code', $contact->zip_code) }}">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <hr />
                    </div>
                    @php
                        $custom_labels = json_decode(session('business.custom_labels'), true);
                        $contact_custom_field1 = !empty($custom_labels['contact']['custom_field_1']) ? $custom_labels['contact']['custom_field_1'] : __('lang_v1.contact_custom_field1');
                        $contact_custom_field2 = !empty($custom_labels['contact']['custom_field_2']) ? $custom_labels['contact']['custom_field_2'] : __('lang_v1.contact_custom_field2');
                        $contact_custom_field3 = !empty($custom_labels['contact']['custom_field_3']) ? $custom_labels['contact']['custom_field_3'] : __('lang_v1.contact_custom_field3');
                        $contact_custom_field4 = !empty($custom_labels['contact']['custom_field_4']) ? $custom_labels['contact']['custom_field_4'] : __('lang_v1.contact_custom_field4');
                        $contact_custom_field5 = !empty($custom_labels['contact']['custom_field_5']) ? $custom_labels['contact']['custom_field_5'] : __('lang_v1.custom_field', ['number' => 5]);
                        $contact_custom_field6 = !empty($custom_labels['contact']['custom_field_6']) ? $custom_labels['contact']['custom_field_6'] : __('lang_v1.custom_field', ['number' => 6]);
                        $contact_custom_field7 = !empty($custom_labels['contact']['custom_field_7']) ? $custom_labels['contact']['custom_field_7'] : __('lang_v1.custom_field', ['number' => 7]);
                        $contact_custom_field8 = !empty($custom_labels['contact']['custom_field_8']) ? $custom_labels['contact']['custom_field_8'] : __('lang_v1.custom_field', ['number' => 8]);
                        $contact_custom_field9 = !empty($custom_labels['contact']['custom_field_9']) ? $custom_labels['contact']['custom_field_9'] : __('lang_v1.custom_field', ['number' => 9]);
                        $contact_custom_field10 = !empty($custom_labels['contact']['custom_field_10']) ? $custom_labels['contact']['custom_field_10'] : __('lang_v1.custom_field', ['number' => 10]);
                    @endphp
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="custom_field1">{{ $contact_custom_field1 }}:</label>
                            <input type="text" name="custom_field1" id="custom_field1" class="form-control" placeholder="{{ $contact_custom_field1 }}" value="{{ old('custom_field1', $contact->custom_field1) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="custom_field2">{{ $contact_custom_field2 }}:</label>
                            <input type="text" name="custom_field2" id="custom_field2" class="form-control" placeholder="{{ $contact_custom_field2 }}" value="{{ old('custom_field2', $contact->custom_field2) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="custom_field3">{{ $contact_custom_field3 }}:</label>
                            <input type="text" name="custom_field3" id="custom_field3" class="form-control" placeholder="{{ $contact_custom_field3 }}" value="{{ old('custom_field3', $contact->custom_field3) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="custom_field4">{{ $contact_custom_field4 }}:</label>
                            <input type="text" name="custom_field4" id="custom_field4" class="form-control" placeholder="{{ $contact_custom_field4 }}" value="{{ old('custom_field4', $contact->custom_field4) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="custom_field5">{{ $contact_custom_field5 }}:</label>
                            <input type="text" name="custom_field5" id="custom_field5" class="form-control" placeholder="{{ $contact_custom_field5 }}" value="{{ old('custom_field5', $contact->custom_field5) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="custom_field6">{{ $contact_custom_field6 }}:</label>
                            <input type="text" name="custom_field6" id="custom_field6" class="form-control" placeholder="{{ $contact_custom_field6 }}" value="{{ old('custom_field6', $contact->custom_field6) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="custom_field7">{{ $contact_custom_field7 }}:</label>
                            <input type="text" name="custom_field7" id="custom_field7" class="form-control" placeholder="{{ $contact_custom_field7 }}" value="{{ old('custom_field7', $contact->custom_field7) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="custom_field8">{{ $contact_custom_field8 }}:</label>
                            <input type="text" name="custom_field8" id="custom_field8" class="form-control" placeholder="{{ $contact_custom_field8 }}" value="{{ old('custom_field8', $contact->custom_field8) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="custom_field9">{{ $contact_custom_field9 }}:</label>
                            <input type="text" name="custom_field9" id="custom_field9" class="form-control" placeholder="{{ $contact_custom_field9 }}" value="{{ old('custom_field9', $contact->custom_field9) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="custom_field10">{{ $contact_custom_field10 }}:</label>
                            <input type="text" name="custom_field10" id="custom_field10" class="form-control" placeholder="{{ $contact_custom_field10 }}" value="{{ old('custom_field10', $contact->custom_field10) }}">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 shipping_addr_div">
                        <hr>
                    </div>
                    <div class="col-md-8 col-md-offset-2 shipping_addr_div">
                        <strong>{{ __('lang_v1.shipping_address') }}</strong><br>
                        <input type="text" name="shipping_address" id="shipping_address" class="form-control" placeholder="{{ __('lang_v1.search_address') }}" value="{{ old('shipping_address', $contact->shipping_address) }}">
                        <div id="map"></div>
                    </div>
                    <input type="hidden" name="position" id="position" value="{{ old('position', $contact->position) }}">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
