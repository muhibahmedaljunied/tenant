@foreach($variations as $variation)
<tr>
    <td><span class="sr_number"></span></td>
    <td>
        {{ $product->name }} @if(!$product->is_asset)
        ({{$variation->sub_sku}})
        @endif
        @if( $product->type == 'variable' )
        <br />
        (<b>{{ $variation->product_variation->name }}</b> : {{ $variation->name }})
        @endif
        @if($product->enable_stock == 1 && !$product->is_asset)
        <br>
        <small class="text-muted" style="white-space: nowrap;">@lang('report.current_stock')
            : @if(!empty($variation->variation_location_details->first()))
            {{@num_format($variation->variation_location_details->first()->qty_available)}}
            @elseif($variation->parentVariation)
            @if(!empty($variation->parentVariation->variation_location_details->first()))
            {{@num_format($variation->parentVariation->variation_location_details->first()->qty_available / $variation->equal_qty)}}
            @endif
            @else
            0
            @endif {{ $variation->unit->short_name ?? $product->unit->short_name }}</small>
        @endif
        @if($variation->parentVariation)
        <br>
        <small class="text-muted">
            المخزون لنسبة الوحدة الاصلي :
            @if(!empty($variation->parentVariation->variation_location_details->first()))
            {{@num_format($variation->parentVariation->variation_location_details->first()->qty_available)}}
            @endif
            <br>
            نسبة الوحدة : {{ @num_format($variation->equal_qty) }}
        </small>
        @endif
    </td>
    <!-- ---- -->
    <td>
        <input type="hidden" name="purchases[{{ $row_count }}][product_id]" value="{{ $product->id }}">
        <input type="hidden" name="purchases[{{ $row_count }}][variation_id]" value="{{ $variation->id }}" class="hidden_variation_id">

        @php
        $check_decimal = $product->unit->allow_decimal == 0 ? 'true' : 'false';
        $currency_precision = config('constants.currency_precision', 2);
        $quantity_precision = config('constants.quantity_precision', 2);
        @endphp

        <input type="text"
            name="purchases[{{ $row_count }}][quantity]"
            value="{{ number_format(1, $quantity_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
            class="form-control input-sm purchase_quantity input_number mousetrap"
            required
            data-rule-abs_digit="{{ $check_decimal }}"
            data-msg-abs_digit="{{ __('lang_v1.decimal_value_not_allowed') }}">

        @if(!$product->is_asset)
        <input type="hidden" class="base_unit_cost" value="{{ $variation->default_purchase_price }}">
        <input type="hidden" class="base_unit_selling_price" value="{{ $variation->sell_price_inc_tax }}">
        <input type="hidden" name="purchases[{{ $row_count }}][product_unit_id]" value="{{ $product->unit->id }}">

        @if(!empty($sub_units) && empty($variation['unit_id']))
        <br>
        <select name="purchases[{{ $row_count }}][sub_unit_id]" class="form-control input-sm sub_unit">
            @foreach($sub_units as $key => $value)
            <option value="{{ $key }}" data-multiplier="{{ $value['multiplier'] }}">{{ $value['name'] }}</option>
            @endforeach
        </select>
        @else
        {{ $variation->unit->short_name ?? $product->unit->short_name }}
        @endif
        @endif
    </td>

    <td>
        <input type="text"
            name="purchases[{{ $row_count }}][pp_without_discount]"
            value="{{ number_format($variation->default_purchase_price, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
            class="form-control input-sm purchase_unit_cost_without_discount input_number"
            required>
    </td>

    <td>
        <input type="text"
            name="purchases[{{ $row_count }}][discount_percent]"
            value="0"
            class="form-control input-sm inline_discounts input_number"
            required>
    </td>

    <td>
        <input type="text"
            name="purchases[{{ $row_count }}][purchase_price]"
            value="{{ number_format($variation->default_purchase_price, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
            class="form-control input-sm purchase_unit_cost input_number"
            required>
    </td>

    <!-- -- -->
    <td class="{{$hide_tax}}">
        <span class="row_subtotal_before_tax display_currency">0</span>
        <input type="hidden" class="row_subtotal_before_tax_hidden" value=0>
    </td>
    <td class="{{$hide_tax}}">
        <div class="input-group">
            <select name="purchases[{{ $row_count }}][purchase_line_tax_id]"
                class="form-control select2 input-sm purchase_line_tax_id" placeholder="'Please Select'">
                <option value="" data-tax_amount="0" @if( $hide_tax=='hide' )
                    selected @endif>@lang('lang_v1.none')</option>
                @foreach($taxes as $tax)
                <option value="{{ $tax->id }}" data-tax_amount="{{ $tax->amount }}"
                    @if( $product->tax == $tax->id && $hide_tax != 'hide') selected @endif >{{ $tax->name }}</option>
                @endforeach
            </select>
<input type="hidden"
       name="purchases[{{ $row_count }}][item_tax]"
       value="0"
       class="purchase_product_unit_tax">
            <span class="input-group-addon purchase_product_unit_tax_text">
                0.00</span>
        </div>
    </td>
    <td class="{{$hide_tax}}">
        @php
        $dpp_inc_tax = number_format($variation->dpp_inc_tax, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator);
        if($hide_tax == 'hide'){
        $dpp_inc_tax = number_format($variation->default_purchase_price, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator);
        }

        @endphp
        <input type="text"
            name="purchases[{{ $row_count }}][purchase_price_inc_tax]"
            value="{{ $dpp_inc_tax }}"
            class="form-control input-sm purchase_unit_cost_after_tax input_number"
            required>

    </td>
    <td>
        <span class="row_subtotal_after_tax display_currency">0</span>
        <input type="hidden" class="row_subtotal_after_tax_hidden" value=0>
    </td>
    <td class="@if(!session('business.enable_editing_product_from_purchase')) hide @endif">
        @if(!$product->is_asset)

        <input type="text"
            name="purchases[{{ $row_count }}][profit_percent]"
            value="{{ number_format($variation->profit_percent, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
            class="form-control input-sm input_number profit_percent"
            required>
        @endif
    </td>
    <td>
        @if(!$product->is_asset)
        @if(session('business.enable_editing_product_from_purchase'))

        <input type="text"
            name="purchases[{{ $row_count }}][default_sell_price]"
            value="{{ number_format($variation->sell_price_inc_tax, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
            class="form-control input-sm input_number default_sell_price"
            required>
        @else
        {{ number_format($variation->sell_price_inc_tax, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator)}}
        @endif
        @endif
    </td>
    @if(session('business.enable_lot_number'))
    <td>
<input type="text"
       name="purchases[{{ $row_count }}][lot_number]"
       class="form-control input-sm">
    </td>
    @endif
    @if(session('business.enable_patch_number'))
    <td>
<input type="text"
       name="purchases[{{ $row_count }}][patch_number]"
       class="form-control input-sm">
    </td>
    @endif
    @if(session('business.enable_product_expiry'))
    <td style="text-align: left;">

        {{-- Maybe this condition for checkin expiry date need to be removed --}}
        @php
        $expiry_period_type = !empty($product->expiry_period_type) ? $product->expiry_period_type : 'month';
        @endphp
        @if(!empty($expiry_period_type))
        <input type="hidden" class="row_product_expiry" value="{{ $product->expiry_period }}">
        <input type="hidden" class="row_product_expiry_type" value="{{ $expiry_period_type }}">

        @if(session('business.expiry_type') == 'add_manufacturing')
        @php
        $hide_mfg = false;
        @endphp
        @else
        @php
        $hide_mfg = true;
        @endphp
        @endif

        <b class="@if($hide_mfg) hide @endif"><small>@lang('product.mfg_date'):</small></b>
        <div class="input-group @if($hide_mfg) hide @endif">
            <span class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </span>
            <input type="text"
       name="purchases[{{ $row_count }}][mfg_date]"
       class="form-control input-sm expiry_datepicker mfg_date"
       readonly>

        </div>
        <b><small>@lang('product.exp_date'):</small></b>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </span>
<input type="text"
       name="purchases[{{ $row_count }}][exp_date]"
       class="form-control input-sm expiry_datepicker exp_date"
       readonly>
        </div>
        @else
        <div class="text-center">
            @lang('product.not_applicable')
        </div>
        @endif
    </td>
    @endif
    <?php $row_count++; ?>

    <td><i class="fa fa-times remove_purchase_entry_row text-danger" title="Remove" style="cursor:pointer;"></i>
    </td>
</tr>
@endforeach

<input type="hidden" id="row_count" value="{{ $row_count }}">