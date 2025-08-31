<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="tax_label_1">@lang('business.tax_1_name'):</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="tax_label_1" id="tax_label_1" class="form-control"
                        placeholder="@lang('business.tax_1_placeholder')" value="{{ $business->tax_label_1 }}">
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="tax_number_1">@lang('business.tax_1_no'):</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="tax_number_1" id="tax_number_1" class="form-control"
                        value="{{ $business->tax_number_1 }}">
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="tax_label_2">@lang('business.tax_2_name'):</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="tax_label_2" id="tax_label_2" class="form-control"
                        placeholder="@lang('business.tax_1_placeholder')" value="{{ $business->tax_label_2 }}">
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="tax_number_2">@lang('business.tax_2_no'):</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="tax_number_2" id="tax_number_2" class="form-control"
                        value="{{ $business->tax_number_2 }}">
                </div>
            </div>
        </div>

        <div class="col-sm-8">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_inline_tax" id="enable_inline_tax" class="input-icheck"
                            value="1" {{ $business->enable_inline_tax ? 'checked' : '' }}>
                        @lang('lang_v1.enable_inline_tax')
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-8">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_invoic_tax" id="enable_invoic_tax" class="input-icheck"
                            value="1" {{ $business->enable_invoic_tax ? 'checked' : '' }}>
                        @lang('lang_v1.enable_invoic_tax')
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

