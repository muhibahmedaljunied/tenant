<div class="row">
    <div class="col-sm-12">
        <h4>@lang('lang_v1.weighing_scale_setting'):</h4>
        <p>@lang('lang_v1.weighing_scale_setting_help')</p>
        <br/>
    </div>

    <!-- 1st part: Prefix (here any prefix can be entered), user can leave it blank also if prefix not supported by scale.
	2nd part: Dropdown list from 1 to 9 for Barcode 0
	3rd part: Dropdown list from 1 to 5 for Quantity 
	4th part: Dropdown list from 1 to 4 for Quantity decimals. -->

<div class="col-sm-3">
    <div class="form-group">
        <label for="label_prefix">@lang('lang_v1.weighing_barcode_prefix'):</label>
        <input type="text" name="weighing_scale_setting[label_prefix]" id="label_prefix" class="form-control"
            value="{{ isset($weighing_scale_setting['label_prefix']) ? $weighing_scale_setting['label_prefix'] : '' }}">
    </div>
</div>

<div class="col-sm-3">
    <div class="form-group">
        <label for="product_sku_length">@lang('lang_v1.weighing_product_sku_length'):</label>
        <select name="weighing_scale_setting[product_sku_length]" id="product_sku_length" class="form-control select2" style="width: 100%;">
            @foreach([1,2,3,4,5,6,7,8,9] as $length)
                <option value="{{ $length }}" {{ isset($weighing_scale_setting['product_sku_length']) && $weighing_scale_setting['product_sku_length'] == $length ? 'selected' : '' }}>
                    {{ $length }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-sm-3">
    <div class="form-group">
        <label for="qty_length">@lang('lang_v1.weighing_qty_integer_part_length'):</label>
        <select name="weighing_scale_setting[qty_length]" id="qty_length" class="form-control select2" style="width: 100%;">
            @foreach([1,2,3,4,5] as $length)
                <option value="{{ $length }}" {{ isset($weighing_scale_setting['qty_length']) && $weighing_scale_setting['qty_length'] == $length ? 'selected' : '' }}>
                    {{ $length }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-sm-3">
    <div class="form-group">
        <label for="qty_length_decimal">@lang('lang_v1.weighing_qty_fractional_part_length'):</label>
        <select name="weighing_scale_setting[qty_length_decimal]" id="qty_length_decimal" class="form-control select2" style="width: 100%;">
            @foreach([1,2,3,4] as $length)
                <option value="{{ $length }}" {{ isset($weighing_scale_setting['qty_length_decimal']) && $weighing_scale_setting['qty_length_decimal'] == $length ? 'selected' : '' }}>
                    {{ $length }}
                </option>
            @endforeach
        </select>
    </div>
</div>


</div>