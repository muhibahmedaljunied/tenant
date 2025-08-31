@if (count($multi_units) > 0)
    <br>
    <select name="products[{{ $row_count }}][another_variation_id]" class="form-control input-sm another_variation_id">
        @foreach ($multi_units as $key => $value)
            <option value="{{ $key }}" data-unit_name="{{ $value['name'] }}"
                {{ $value['is_available'] ?: 'disabled' }}
                {{ !empty($product->variation_id) && $product->variation_id == $key ? 'selected' : '' }}>
                {{ $value['name'] }} @unless ($value['is_available'])
                    - @lang('report.stock_left') {{ $value['qty'] }}
                @endunless
            </option>
        @endforeach
    </select>
@else
    {{ $product->v_unit ?? $product->unit }}
@endif
