<div class="payment_details_div @if($payment_line['method'] !== 'card') hide @endif" data-type="card">
    <div class="col-md-4">
        <div class="form-group">
            <label for="card_number_{{ $row_index }}">@lang('lang_v1.card_no')</label>
            <input type="text" name="payment[{{ $row_index }}][card_number]" id="card_number_{{ $row_index }}"
                class="form-control" placeholder="@lang('lang_v1.card_no')" value="{{ $payment_line['card_number'] }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="card_holder_name_{{ $row_index }}">@lang('lang_v1.card_holder_name')</label>
            <input type="text" name="payment[{{ $row_index }}][card_holder_name]" id="card_holder_name_{{ $row_index }}"
                class="form-control" placeholder="@lang('lang_v1.card_holder_name')" value="{{ $payment_line['card_holder_name'] }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="card_transaction_number_{{ $row_index }}">@lang('lang_v1.card_transaction_no')</label>
            <input type="text" name="payment[{{ $row_index }}][card_transaction_number]" id="card_transaction_number_{{ $row_index }}"
                class="form-control" placeholder="@lang('lang_v1.card_transaction_no')" value="{{ $payment_line['card_transaction_number'] }}">
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<div class="payment_details_div @if($payment_line['method'] !== 'cheque') hide @endif" data-type="cheque">
    <div class="col-md-12">
        <div class="form-group">
            <label for="cheque_number_{{ $row_index }}">@lang('lang_v1.cheque_no')</label>
            <input type="text" name="payment[{{ $row_index }}][cheque_number]" id="cheque_number_{{ $row_index }}"
                class="form-control" placeholder="@lang('lang_v1.cheque_no')" value="{{ $payment_line['cheque_number'] }}">
        </div>
    </div>
</div>

<div class="payment_details_div @if($payment_line['method'] !== 'bank_transfer') hide @endif" data-type="bank_transfer">
    <div class="col-md-12">
        <div class="form-group">
            <label for="bank_account_number_{{ $row_index }}">@lang('lang_v1.bank_account_number')</label>
            <input type="text" name="payment[{{ $row_index }}][bank_account_number]" id="bank_account_number_{{ $row_index }}"
                class="form-control" placeholder="@lang('lang_v1.bank_account_number')" value="{{ $payment_line['bank_account_number'] }}">
        </div>
    </div>
</div>

@for ($i = 1; $i < 8; $i++)
    <div class="payment_details_div @if($payment_line['method'] !== 'custom_pay_' . $i) hide @endif" data-type="custom_pay_{{ $i }}">
        <div class="col-md-12">
            <div class="form-group">
                <label for="transaction_no_{{ $i }}_{{ $row_index }}">@lang('lang_v1.transaction_no')</label>
                <input type="text" name="payment[{{ $row_index }}][transaction_no_{{ $i }}]" id="transaction_no_{{ $i }}_{{ $row_index }}"
                    class="form-control" placeholder="@lang('lang_v1.transaction_no')" value="{{ $payment_line['transaction_no'] }}">
            </div>
        </div>
    </div>
@endfor
