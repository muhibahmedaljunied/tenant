<div class="pos-tab-content">
     <div class="row">
    <div class="col-sm-4">
    <div class="form-group">
        <label for="custom_payment_1">@lang('lang_v1.custom_payment_1'):</label>
        <input type="text" name="custom_labels[payments][custom_pay_1]" id="custom_payment_1" class="form-control"
            value="{{ $custom_labels['payments']['custom_pay_1'] ?? '' }}">
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="custom_payment_2">@lang('lang_v1.custom_payment_2'):</label>
        <input type="text" name="custom_labels[payments][custom_pay_2]" id="custom_payment_2" class="form-control"
            value="{{ $custom_labels['payments']['custom_pay_2'] ?? '' }}">
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="custom_payment_3">@lang('lang_v1.custom_payment_3'):</label>
        <input type="text" name="custom_labels[payments][custom_pay_3]" id="custom_payment_3" class="form-control"
            value="{{ $custom_labels['payments']['custom_pay_3'] ?? '' }}">
    </div>
</div>

<div class="col-sm-3">
    <div class="form-group">
        <label for="contact_custom_field_1_label">@lang('lang_v1.contact_custom_field1'):</label>
        <input type="text" name="custom_labels[contact][custom_field_1]" id="contact_custom_field_1_label"
            class="form-control"
            value="{{ $custom_labels['contact']['custom_field_1'] ?? '' }}">
    </div>
</div>

<div class="col-sm-3">
    <div class="form-group">
        <label for="contact_custom_field_2_label">@lang('lang_v1.contact_custom_field2'):</label>
        <input type="text" name="custom_labels[contact][custom_field_2]" id="contact_custom_field_2_label"
            class="form-control"
            value="{{ $custom_labels['contact']['custom_field_2'] ?? '' }}">
    </div>
</div>

   {{-- --------------------------------- --}}
<div class="col-sm-3">
    <div class="form-group">
        <label for="contact_custom_field_3_label">@lang('lang_v1.contact_custom_field3'):</label>
        <input type="text" name="custom_labels[contact][custom_field_3]" id="contact_custom_field_3_label"
            class="form-control"
            value="{{ $custom_labels['contact']['custom_field_3'] ?? '' }}">
    </div>
</div>

<div class="col-sm-3">
    <div class="form-group">
        <label for="contact_custom_field_4_label">@lang('lang_v1.contact_custom_field4'):</label>
        <input type="text" name="custom_labels[contact][custom_field_4]" id="contact_custom_field_4_label"
            class="form-control"
            value="{{ $custom_labels['contact']['custom_field_4'] ?? '' }}">
    </div>
</div>

<div class="col-sm-3">
    <div class="form-group">
        <label for="contact_custom_field_5_label">@lang('lang_v1.custom_field', ['number' => 5]):</label>
        <input type="text" name="custom_labels[contact][custom_field_5]" id="contact_custom_field_5_label"
            class="form-control"
            value="{{ $custom_labels['contact']['custom_field_5'] ?? '' }}">
    </div>
</div>

<div class="col-sm-3">
    <div class="form-group">
        <label for="contact_custom_field_6_label">@lang('lang_v1.custom_field', ['number' => 6]):</label>
        <input type="text" name="custom_labels[contact][custom_field_6]" id="contact_custom_field_6_label"
            class="form-control"
            value="{{ $custom_labels['contact']['custom_field_6'] ?? '' }}">
    </div>
</div>

        {{-- --------------------------------- --}}
 <div class="col-sm-3">
    <div class="form-group">
        <label for="location_custom_field_1_label">@lang('lang_v1.location_custom_field1'):</label>
        <input type="text" name="custom_labels[location][custom_field_1]" id="location_custom_field_1_label"
            class="form-control"
            value="{{ $custom_labels['location']['custom_field_1'] ?? '' }}">
    </div>
</div>

<div class="col-sm-3">
    <div class="form-group">
        <label for="location_custom_field_2_label">@lang('lang_v1.location_custom_field2'):</label>
        <input type="text" name="custom_labels[location][custom_field_2]" id="location_custom_field_2_label"
            class="form-control"
            value="{{ $custom_labels['location']['custom_field_2'] ?? '' }}">
    </div>
</div>

<div class="col-sm-3">
    <div class="form-group">
        <label for="location_custom_field_3_label">@lang('lang_v1.location_custom_field3'):</label>
        <input type="text" name="custom_labels[location][custom_field_3]" id="location_custom_field_3_label"
            class="form-control"
            value="{{ $custom_labels['location']['custom_field_3'] ?? '' }}">
    </div>
</div>

<div class="col-sm-3">
    <div class="form-group">
        <label for="location_custom_field_4_label">@lang('lang_v1.location_custom_field4'):</label>
        <input type="text" name="custom_labels[location][custom_field_4]" id="location_custom_field_4_label"
            class="form-control"
            value="{{ $custom_labels['location']['custom_field_4'] ?? '' }}">
    </div>
</div>

                {{-- --------------------------------- --}}
<div class="col-sm-3">
    <div class="form-group">
        <label for="sell_custom_field_4_label">@lang('lang_v1.product_custom_field4'):</label>
        <input type="text" name="custom_labels[sell][custom_field_4]" id="sell_custom_field_4_label"
            class="form-control"
            value="{{ !empty($custom_labels['sell']['custom_field_4']) ? $custom_labels['sell']['custom_field_4'] : '' }}">
        <div class="input-group-addon">
            <label>
                <input type="checkbox" name="custom_labels[sell][is_custom_field_4_required]" value="1"
                    {{ !empty($custom_labels['sell']['is_custom_field_4_required']) && $custom_labels['sell']['is_custom_field_4_required'] == 1 ? 'checked' : '' }}>
                @lang('lang_v1.is_required')
            </label>
        </div>
    </div>
</div>

 
    </div>
</div>