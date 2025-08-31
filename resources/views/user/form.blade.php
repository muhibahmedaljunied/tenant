@php
  $custom_labels = json_decode(session('business.custom_labels'), true);
  $user_custom_field1 = !empty($custom_labels['user']['custom_field_1']) ? $custom_labels['user']['custom_field_1'] : __('lang_v1.user_custom_field1');
  $user_custom_field2 = !empty($custom_labels['user']['custom_field_2']) ? $custom_labels['user']['custom_field_2'] : __('lang_v1.user_custom_field2');
  $user_custom_field3 = !empty($custom_labels['user']['custom_field_3']) ? $custom_labels['user']['custom_field_3'] : __('lang_v1.user_custom_field3');
  $user_custom_field4 = !empty($custom_labels['user']['custom_field_4']) ? $custom_labels['user']['custom_field_4'] : __('lang_v1.user_custom_field4');
@endphp
<div class="form-group col-md-3">
    <label for="user_dob">@lang('lang_v1.dob'):</label>
    <input type="text" name="dob" id="user_dob" class="form-control" readonly placeholder="@lang('lang_v1.dob')" value="{{ !empty($user->dob) ? format_date($user->dob) : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="gender">@lang('lang_v1.gender'):</label>
    <select name="gender" id="gender" class="form-control" placeholder="@lang('messages.please_select')">
        <option value="male" {{ !empty($user->gender) && $user->gender == 'male' ? 'selected' : '' }}>@lang('lang_v1.male')</option>
        <option value="female" {{ !empty($user->gender) && $user->gender == 'female' ? 'selected' : '' }}>@lang('lang_v1.female')</option>
        <option value="others" {{ !empty($user->gender) && $user->gender == 'others' ? 'selected' : '' }}>@lang('lang_v1.others')</option>
    </select>
</div>

<div class="form-group col-md-3">
    <label for="marital_status">@lang('lang_v1.marital_status'):</label>
    <select name="marital_status" id="marital_status" class="form-control" placeholder="@lang('lang_v1.marital_status')">
        <option value="married" {{ !empty($user->marital_status) && $user->marital_status == 'married' ? 'selected' : '' }}>@lang('lang_v1.married')</option>
        <option value="unmarried" {{ !empty($user->marital_status) && $user->marital_status == 'unmarried' ? 'selected' : '' }}>@lang('lang_v1.unmarried')</option>
        <option value="divorced" {{ !empty($user->marital_status) && $user->marital_status == 'divorced' ? 'selected' : '' }}>@lang('lang_v1.divorced')</option>
    </select>
</div>

<div class="form-group col-md-3">
    <label for="blood_group">@lang('lang_v1.blood_group'):</label>
    <input type="text" name="blood_group" id="blood_group" class="form-control" placeholder="@lang('lang_v1.blood_group')" value="{{ !empty($user->blood_group) ? $user->blood_group : '' }}">
</div>

<div class="clearfix"></div>

<div class="form-group col-md-3">
    <label for="contact_number">@lang('lang_v1.mobile_number'):</label>
    <input type="text" name="contact_number" id="contact_number" class="form-control" placeholder="@lang('lang_v1.mobile_number')" value="{{ !empty($user->contact_number) ? $user->contact_number : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="alt_number">@lang('business.alternate_number'):</label>
    <input type="text" name="alt_number" id="alt_number" class="form-control" placeholder="@lang('business.alternate_number')" value="{{ !empty($user->alt_number) ? $user->alt_number : '' }}">
</div>

{{-- ------------ --}}
<div class="form-group col-md-3">
    <label for="user_dob">@lang('lang_v1.dob'):</label>
    <input type="text" name="dob" id="user_dob" class="form-control" readonly placeholder="@lang('lang_v1.dob')" value="{{ !empty($user->dob) ? format_date($user->dob) : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="gender">@lang('lang_v1.gender'):</label>
    <select name="gender" id="gender" class="form-control" placeholder="@lang('messages.please_select')">
        <option value="male" {{ !empty($user->gender) && $user->gender == 'male' ? 'selected' : '' }}>@lang('lang_v1.male')</option>
        <option value="female" {{ !empty($user->gender) && $user->gender == 'female' ? 'selected' : '' }}>@lang('lang_v1.female')</option>
        <option value="others" {{ !empty($user->gender) && $user->gender == 'others' ? 'selected' : '' }}>@lang('lang_v1.others')</option>
    </select>
</div>

<div class="form-group col-md-3">
    <label for="marital_status">@lang('lang_v1.marital_status'):</label>
    <select name="marital_status" id="marital_status" class="form-control" placeholder="@lang('lang_v1.marital_status')">
        <option value="married" {{ !empty($user->marital_status) && $user->marital_status == 'married' ? 'selected' : '' }}>@lang('lang_v1.married')</option>
        <option value="unmarried" {{ !empty($user->marital_status) && $user->marital_status == 'unmarried' ? 'selected' : '' }}>@lang('lang_v1.unmarried')</option>
        <option value="divorced" {{ !empty($user->marital_status) && $user->marital_status == 'divorced' ? 'selected' : '' }}>@lang('lang_v1.divorced')</option>
    </select>
</div>

<div class="form-group col-md-3">
    <label for="blood_group">@lang('lang_v1.blood_group'):</label>
    <input type="text" name="blood_group" id="blood_group" class="form-control" placeholder="@lang('lang_v1.blood_group')" value="{{ !empty($user->blood_group) ? $user->blood_group : '' }}">
</div>

<div class="clearfix"></div>

<div class="form-group col-md-3">
    <label for="contact_number">@lang('lang_v1.mobile_number'):</label>
    <input type="text" name="contact_number" id="contact_number" class="form-control" placeholder="@lang('lang_v1.mobile_number')" value="{{ !empty($user->contact_number) ? $user->contact_number : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="alt_number">@lang('business.alternate_number'):</label>
    <input type="text" name="alt_number" id="alt_number" class="form-control" placeholder="@lang('business.alternate_number')" value="{{ !empty($user->alt_number) ? $user->alt_number : '' }}">
</div>

{{-- -------------------------- --}}
<div class="form-group col-md-3">
    <label for="custom_field_2">{{ $user_custom_field2 }}:</label>
    <input type="text" name="custom_field_2" id="custom_field_2" class="form-control" placeholder="{{ $user_custom_field2 }}" value="{{ !empty($user->custom_field_2) ? $user->custom_field_2 : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="custom_field_3">{{ $user_custom_field3 }}:</label>
    <input type="text" name="custom_field_3" id="custom_field_3" class="form-control" placeholder="{{ $user_custom_field3 }}" value="{{ !empty($user->custom_field_3) ? $user->custom_field_3 : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="custom_field_4">{{ $user_custom_field4 }}:</label>
    <input type="text" name="custom_field_4" id="custom_field_4" class="form-control" placeholder="{{ $user_custom_field4 }}" value="{{ !empty($user->custom_field_4) ? $user->custom_field_4 : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="guardian_name">@lang('lang_v1.guardian_name'):</label>
    <input type="text" name="guardian_name" id="guardian_name" class="form-control" placeholder="@lang('lang_v1.guardian_name')" value="{{ !empty($user->guardian_name) ? $user->guardian_name : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="id_proof_name">@lang('lang_v1.id_proof_name'):</label>
    <input type="text" name="id_proof_name" id="id_proof_name" class="form-control" placeholder="@lang('lang_v1.id_proof_name')" value="{{ !empty($user->id_proof_name) ? $user->id_proof_name : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="id_proof_number">@lang('lang_v1.id_proof_number'):</label>
    <input type="text" name="id_proof_number" id="id_proof_number" class="form-control" placeholder="@lang('lang_v1.id_proof_number')" value="{{ !empty($user->id_proof_number) ? $user->id_proof_number : '' }}">
</div>

<div class="clearfix"></div>

{{-- -------------------------- --}}
<div class="form-group col-md-6">
    <label for="permanent_address">@lang('lang_v1.permanent_address'):</label>
    <textarea name="permanent_address" id="permanent_address" class="form-control" rows="3" placeholder="@lang('lang_v1.permanent_address')">{{ !empty($user->permanent_address) ? $user->permanent_address : '' }}</textarea>
</div>

<div class="form-group col-md-6">
    <label for="current_address">@lang('lang_v1.current_address'):</label>
    <textarea name="current_address" id="current_address" class="form-control" rows="3" placeholder="@lang('lang_v1.current_address')">{{ !empty($user->current_address) ? $user->current_address : '' }}</textarea>
</div>

<div class="col-md-12">
    <hr>
    <h4>@lang('lang_v1.bank_details'):</h4>
</div>

<div class="form-group col-md-3">
    <label for="account_holder_name">@lang('lang_v1.account_holder_name'):</label>
    <input type="text" name="bank_details[account_holder_name]" id="account_holder_name" class="form-control" placeholder="@lang('lang_v1.account_holder_name')" value="{{ !empty($bank_details['account_holder_name']) ? $bank_details['account_holder_name'] : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="account_number">@lang('lang_v1.account_number'):</label>
    <input type="text" name="bank_details[account_number]" id="account_number" class="form-control" placeholder="@lang('lang_v1.account_number')" value="{{ !empty($bank_details['account_number']) ? $bank_details['account_number'] : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="bank_name">@lang('lang_v1.bank_name'):</label>
    <input type="text" name="bank_details[bank_name]" id="bank_name" class="form-control" placeholder="@lang('lang_v1.bank_name')" value="{{ !empty($bank_details['bank_name']) ? $bank_details['bank_name'] : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="bank_code">@lang('lang_v1.bank_code'):</label>
    @show_tooltip(__('lang_v1.bank_code_help'))
    <input type="text" name="bank_details[bank_code]" id="bank_code" class="form-control" placeholder="@lang('lang_v1.bank_code')" value="{{ !empty($bank_details['bank_code']) ? $bank_details['bank_code'] : '' }}">
</div>

{{-- -------------------------- --}}

<div class="form-group col-md-3">
    <label for="branch">@lang('lang_v1.branch'):</label>
    <input type="text" name="bank_details[branch]" id="branch" class="form-control" placeholder="@lang('lang_v1.branch')" value="{{ !empty($bank_details['branch']) ? $bank_details['branch'] : '' }}">
</div>

<div class="form-group col-md-3">
    <label for="tax_payer_id">@lang('lang_v1.tax_payer_id'):</label>
    @show_tooltip(__('lang_v1.tax_payer_id_help'))
    <input type="text" name="bank_details[tax_payer_id]" id="tax_payer_id" class="form-control" placeholder="@lang('lang_v1.tax_payer_id')" value="{{ !empty($bank_details['tax_payer_id']) ? $bank_details['tax_payer_id'] : '' }}">
</div>
