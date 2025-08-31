<tbody class="product_rows" id="product_{{$product->id}}">
    <tr class="bg-green">
        <td>{{$product->name}} ({{$product->sku}})</td>
        <td>
            <select name="products[{{ $product->id }}][category_id]" class="form-control select2 input-sm category_id" style="width: 100%;">
                <option value="">{{ __('messages.please_select') }}</option>
                @foreach($categories as $key => $value)
                    <option value="{{ $key }}" @if($product->category_id == $key) selected @endif>{{ $value }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select name="products[{{ $product->id }}][sub_category_id]" class="form-control select2 input-sm sub_category_id" style="width: 100%;">
                <option value="">{{ __('messages.please_select') }}</option>
                @if(!empty($sub_categories[$product->category_id]))
                    @foreach($sub_categories[$product->category_id] as $key => $value)
                        <option value="{{ $key }}" @if($product->sub_category_id == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td>
            <select name="products[{{ $product->id }}][brand_id]" class="form-control select2 input-sm" style="width: 100%;">
                <option value="">{{ __('messages.please_select') }}</option>
                @foreach($brands as $key => $value)
                    <option value="{{ $key }}" @if($product->brand_id == $key) selected @endif>{{ $value }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select name="products[{{ $product->id }}][tax]" class="form-control select2 input-sm row_tax" style="width: 100%;"
                @foreach($tax_attributes ?? [] as $attr => $val) {{ $attr }}="{{ $val }}" @endforeach
            >
                <option value="">{{ __('messages.please_select') }}</option>
                @foreach($taxes as $key => $value)
                    <option value="{{ $key }}" @if($product->tax == $key) selected @endif>{{ $value }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select name="products[{{ $product->id }}][product_locations][]" class="form-control select2" multiple>
                @foreach($business_locations as $key => $value)
                    <option value="{{ $key }}" @if(collect($product->product_locations->pluck('id'))->contains($key)) selected @endif>{{ $value }}</option>
                @endforeach
            </select>
        </td>
    <tr>
    <tr>
        <td colspan="6">
            <table class="table">
                <thead>
                    <tr>
                        <th>@lang('lang_v1.variation')</th>
                        <th>@lang('product.default_purchase_price')</th>
                        <th>@lang('product.profit_percent') @show_tooltip(__('tooltip.profit_percent'))</th>
                        <th>@lang('product.default_selling_price')</th>
                        <th>@lang('lang_v1.group_price')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($product->variations as $variation)
                    <tr class="variation_row">
                        @include('product.partials.bulk_edit_variation_row')
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>
</tbody>