<div class="row">
<input type="hidden" class="payment_row_index" value="{{ $row_index }}">

@php
    $col_class = !empty($accounts) && !isset($pos) ? 'col-md-4' : 'col-md-6';
    $readonly = $payment_line['method'] == 'advance' ? true : false;
@endphp

<div class="{{ $col_class }}">
    <div class="form-group">
        <label for="amount_{{ $row_index }}">@lang('sale.amount'):</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <input type="text" name="payment[{{ $row_index }}][amount]" id="amount_{{ $row_index }}"
                class="form-control payment-amount input_number" required placeholder="@lang('sale.amount')"
                value="{{ @num_format($payment_line['amount']) }}" {{ $readonly ? 'readonly' : '' }}>
        </div>
    </div>
</div>

@if(!empty($show_date))
    <div class="{{ $col_class }}">
        <div class="form-group">
            <label for="paid_on_{{ $row_index }}">@lang('lang_v1.paid_on'):</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </span>
                <input type="text" name="payment[{{ $row_index }}][paid_on]" id="paid_on_{{ $row_index }}"
                    class="form-control paid_on" required readonly
                    value="{{ isset($payment_line['paid_on']) ? @format_datetime($payment_line['paid_on']) : @format_datetime('now') }}">
            </div>
        </div>
    </div>
@endif

<div class="{{ $col_class }}">
    <div class="form-group">
        <label for="method_{{ $row_index }}">@lang('lang_v1.payment_method'):</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            @php
                $_payment_method = empty($payment_line['method']) && array_key_exists('cash', $payment_types) ? 'cash' : $payment_line['method'];
            @endphp
            <select name="payment[{{ $row_index }}][method]" id="{{ !$readonly ? "method_$row_index" : "method_advance_$row_index" }}"
                class="form-control col-md-12 payment_types_dropdown" required style="width:100%;" {{ $readonly ? 'disabled' : '' }}>
                @foreach($payment_types as $key => $value)
                    <option value="{{ $key }}" {{ $_payment_method == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>

            @if($readonly)
                <input type="hidden" name="payment[{{ $row_index }}][method]" id="method_{{ $row_index }}"
                    class="payment_types_dropdown" required value="{{ $payment_line['method'] }}">
            @endif
        </div>
    </div>
</div>

@if(!empty($accounts) && !isset($pos))
    <div class="{{ $col_class }}">
        <div class="form-group {{ $readonly ? 'hide' : '' }}">
            <label for="account_{{ $row_index }}">@lang('lang_v1.payment_account'):</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fas fa-money-bill-alt"></i>
                </span>
                <select name="payment[{{ $row_index }}][account_id]" id="{{ !$readonly ? "account_$row_index" : "account_advance_$row_index" }}"
                    class="form-control select2 account-dropdown" style="width:100%;" {{ $readonly ? 'disabled' : '' }}>
                    @foreach($accounts as $key => $value)
                        <option value="{{ $key }}" {{ !empty($payment_line['account_id']) && $payment_line['account_id'] == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@endif

<div class="clearfix"></div>

@include('sale_pos.partials.payment_type_details')

<div class="col-md-12">
    <div class="form-group">
        <label for="note_{{ $row_index }}">@lang('sale.payment_note'):</label>
        <textarea name="payment[{{ $row_index }}][note]" id="note_{{ $row_index }}" class="form-control" rows="3">{{ $payment_line['note'] }}</textarea>
    </div>
</div>

</div>