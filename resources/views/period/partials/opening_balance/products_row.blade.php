<table class='table' id='accounts_table'>
    <thead>
    <tr>
        <th>اسم الموقع</th>
        <th>المنتج</th>
        <th>القيمة المتوسطة</th>
        <th>الكمية</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style='width: 25%;'>
            <select name="accounts[0][location]" id="select_location" class="form-control select2" required autofocus>
                <option value="">{{ __('lang_v1.select_location') }}</option>
                @foreach($locations as $key => $value)
                    <option value="{{ $key }}" {{ old('accounts.0.location', $locations->first()) == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </td>
        <td style='width: 25%;'>
            <select name="accounts[0][product]" id="select_product_id" class="form-control select2" required autofocus>
                <option value="">{{ __('lang_v1.select_product') }}</option>
                @foreach($products as $key => $value)
                    <option value="{{ $key }}" {{ old('accounts.0.product', $products->first()) == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </td>
        <td style='width: 25%;'>
            <input type="number" name="accounts[0][amount]" class="form-control" step="any" required autofocus value="{{ old('accounts.0.amount') }}">
        </td>
        <td style='width: 25%;'>
            <input type="number" name="accounts[0][qty]" class="form-control" required step="any" value="{{ old('accounts.0.qty') }}">
        </td>
    </tr>
    </tbody>
</table>
