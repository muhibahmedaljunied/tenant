<table class='table' id='accounts_table'>
    <thead>
    <tr>
        <th>اسم العميل</th>
        <th>الرصيد الافتتاحي</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <select name="accounts[0][account]" id="select_location_id" class="form-control select2" required autofocus>
                <option value="">{{ __('lang_v1.select_location') }}</option>
                @foreach($customers as $key => $value)
                    <option value="{{ $key }}" {{ old('accounts.0.account', array_key_first($customers)) == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </td>
        <td style='width:50%'>
            <input type="number" name="accounts[0][amount]" class="form-control" required step="any" value="{{ old('accounts.0.amount') }}">
        </td>
    </tr>
    </tbody>
</table>