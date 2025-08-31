<!--instalment start--->
<div class="payment_details_div @if ($payment_line['method'] !== 'installment') {{ 'hide' }} @endif" data-type="installment">
    <div class="col-md-4">
        <div class="form-group">
            <label for="prepaid_{{ $row_index }}">{{ __('.مبلغ القسط') }}</label>
            <input type="text" name="payment[{{ $row_index }}][prepaid]" id="remaining_due_for_ins" class="form-control" placeholder="{{ __('مبلغ القسط') }}" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="percent_rate_tax_{{ $row_index }}">{{ __('.فايدة ') }}</label>
            @php
                $business_id = request()
                    ->session()
                    ->get('user.business_id');
                $taxes_rate = \DB::table('tax_rates')
                    ->where('business_id', $business_id)
                    ->where('count_months', '>=', 1)
                    ->orWhere('is_composite', 1)
                    ->get();
            @endphp
            <select class ='form-control' name="order_tax_modal" id ="order_tax_modall">
                <option value="" selected> لا احد</option>
                @foreach ($taxes_rate as $row)
                    <option value="{{ $row->id }}" data-rate="{{ $row->amount }}"
                        data-months="{{ $row->count_months }}" data-composite="{{ $row->is_composite }}">
                        {{ $row->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="remain_for_installment_{{ $row_index }}">{{ __('  عدد الاشهر   ') }}</label>
            <input type="number" name="count_months" id="count_months" class="form-control" placeholder="{{ __('  عدد الاشهر ') }}" readonly>
        </div>
    </div>
    <div class="col-md-4" id="count_year_section" style="display:none">
        <div class="form-group">
            <label for="remain_for_installment_{{ $row_index }}">{{ __('  عددالسنين    ') }}</label>
            <input type="number" name="count_years" id="count_years" class="form-control" placeholder="{{ __('  عدد السنين ') }}">
        </div>
    </div>
    <div class="clearfix"></div>
</div>



<!--/instalment end--->
<div class="payment_details_div @if ($payment_line['method'] !== 'card') {{ 'hide' }} @endif" data-type="card">
    <div class="col-md-4">
        <div class="form-group">
            <label for="card_number_{{ $row_index }}">{{ __('lang_v1.card_no') }}</label>
            <input type="text" name="payment[{{ $row_index }}][card_number]" id="card_number_{{ $row_index }}" class="form-control" placeholder="{{ __('lang_v1.card_no') }}" value="{{ $payment_line['card_number'] }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="card_holder_name_{{ $row_index }}">{{ __('lang_v1.card_holder_name') }}</label>
            <input type="text" name="payment[{{ $row_index }}][card_holder_name]" id="card_holder_name_{{ $row_index }}" class="form-control" placeholder="{{ __('lang_v1.card_holder_name') }}" value="{{ $payment_line['card_holder_name'] }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="card_transaction_number_{{ $row_index }}">{{ __('lang_v1.card_transaction_no') }}</label>
            <input type="text" name="payment[{{ $row_index }}][card_transaction_number]" id="card_transaction_number_{{ $row_index }}" class="form-control" placeholder="{{ __('lang_v1.card_transaction_no') }}" value="{{ $payment_line['card_transaction_number'] }}">
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="card_type_{{ $row_index }}">{{ __('lang_v1.card_type') }}</label>
            <select name="payment[{{ $row_index }}][card_type]" id="card_type_{{ $row_index }}" class="form-control">
                <option value="credit" @if($payment_line['card_type'] == 'credit') selected @endif>Credit Card</option>
                <option value="debit" @if($payment_line['card_type'] == 'debit') selected @endif>Debit Card</option>
                <option value="visa" @if($payment_line['card_type'] == 'visa') selected @endif>Visa</option>
                <option value="master" @if($payment_line['card_type'] == 'master') selected @endif>MasterCard</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="card_month_{{ $row_index }}">{{ __('lang_v1.month') }}</label>
            <input type="text" name="payment[{{ $row_index }}][card_month]" id="card_month_{{ $row_index }}" class="form-control" placeholder="{{ __('lang_v1.month') }}" value="{{ $payment_line['card_month'] }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="card_year_{{ $row_index }}">{{ __('lang_v1.year') }}</label>
            <input type="text" name="payment[{{ $row_index }}][card_year]" id="card_year_{{ $row_index }}" class="form-control" placeholder="{{ __('lang_v1.year') }}" value="{{ $payment_line['card_year'] }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="card_security_{{ $row_index }}">{{ __('lang_v1.security_code') }}</label>
            <input type="text" name="payment[{{ $row_index }}][card_security]" id="card_security_{{ $row_index }}" class="form-control" placeholder="{{ __('lang_v1.security_code') }}" value="{{ $payment_line['card_security'] }}">
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="payment_details_div @if ($payment_line['method'] !== 'cheque') {{ 'hide' }} @endif" data-type="cheque">
    <div class="col-md-12">
        <div class="form-group">
            <label for="cheque_number_{{ $row_index }}">{{ __('lang_v1.cheque_no') }}</label>
            <input type="text" name="payment[{{ $row_index }}][cheque_number]" id="cheque_number_{{ $row_index }}" class="form-control" placeholder="{{ __('lang_v1.cheque_no') }}" value="{{ $payment_line['cheque_number'] }}">
        </div>
    </div>
</div>
<div class="payment_details_div @if ($payment_line['method'] !== 'bank_transfer') {{ 'hide' }} @endif" data-type="bank_transfer">
    <div class="col-md-12">
        <div class="form-group">
            <label for="bank_account_number_{{ $row_index }}">{{ __('lang_v1.bank_account_number') }}</label>
            <input type="text" name="payment[{{ $row_index }}][bank_account_number]" id="bank_account_number_{{ $row_index }}" class="form-control" placeholder="{{ __('lang_v1.bank_account_number') }}" value="{{ $payment_line['bank_account_number'] }}">
        </div>
    </div>
</div>
<div class="payment_details_div @if ($payment_line['method'] !== 'custom_pay_1') {{ 'hide' }} @endif" data-type="custom_pay_1">
    <div class="col-md-12">
        <div class="form-group">
            <label for="transaction_no_1_{{ $row_index }}">{{ __('lang_v1.transaction_no') }}</label>
            <input type="text" name="payment[{{ $row_index }}][transaction_no_1]" id="transaction_no_1_{{ $row_index }}" class="form-control" placeholder="{{ __('lang_v1.transaction_no') }}" value="{{ $payment_line['transaction_no'] }}">
        </div>
    </div>
</div>
<div class="payment_details_div @if ($payment_line['method'] !== 'custom_pay_2') {{ 'hide' }} @endif" data-type="custom_pay_2">
    <div class="col-md-12">
        <div class="form-group">
            <label for="transaction_no_2_{{ $row_index }}">{{ __('lang_v1.transaction_no') }}</label>
            <input type="text" name="payment[{{ $row_index }}][transaction_no_2]" id="transaction_no_2_{{ $row_index }}" class="form-control" placeholder="{{ __('lang_v1.transaction_no') }}" value="{{ $payment_line['transaction_no'] }}">
        </div>
    </div>
</div>
<div class="payment_details_div @if ($payment_line['method'] !== 'custom_pay_3') {{ 'hide' }} @endif" data-type="custom_pay_3">
    <div class="col-md-12">
        <div class="form-group">
            <label for="transaction_no_3_{{ $row_index }}">{{ __('lang_v1.transaction_no') }}</label>
            <input type="text" name="payment[{{ $row_index }}][transaction_no_3]" id="transaction_no_3_{{ $row_index }}" class="form-control" placeholder="{{ __('lang_v1.transaction_no') }}" value="{{ $payment_line['transaction_no'] }}">
        </div>
    </div>
</div>