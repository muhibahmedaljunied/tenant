<!--Purchase related settings -->
<div class="pos-tab-content">
    <div class="row">
      
        <div class="col-sm-4">
    <div class="form-group">
        <label for="default_credit_limit">@lang('lang_v1.default_credit_limit'):</label>
        <input type="text" name="common_settings[default_credit_limit]" id="default_credit_limit"
            class="form-control input_number"
            placeholder="@lang('lang_v1.default_credit_limit')"
            value="{{ $common_settings['default_credit_limit'] ?? '' }}">
    </div>
</div>

    </div>
</div>