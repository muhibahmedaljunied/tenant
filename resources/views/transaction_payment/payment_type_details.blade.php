<div class="payment_details_div @if( $payment_line->method !== 'card' ) {{ 'hide' }} @endif" data-type="card" >
	<div class="col-md-4">
		<div class="form-group">
			<label for="card_number">{{ __('lang_v1.card_no') }}</label>
			<input type="text" name="card_number" id="card_number" class="form-control" placeholder="{{ __('lang_v1.card_no') }}" value="{{ $payment_line->card_number }}">
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label for="card_holder_name">{{ __('lang_v1.card_holder_name') }}</label>
			<input type="text" name="card_holder_name" id="card_holder_name" class="form-control" placeholder="{{ __('lang_v1.card_holder_name') }}" value="{{ $payment_line->card_holder_name }}">
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label for="card_transaction_number">{{ __('lang_v1.card_transaction_no') }}</label>
			<input type="text" name="card_transaction_number" id="card_transaction_number" class="form-control" placeholder="{{ __('lang_v1.card_transaction_no') }}" value="{{ $payment_line->card_transaction_number }}">
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="col-md-3">
		<div class="form-group">
			<label for="card_type">{{ __('lang_v1.card_type') }}</label>
			<select name="card_type" id="card_type" class="form-control select2">
				<option value="credit" {{ $payment_line->card_type == 'credit' ? 'selected' : '' }}>Credit Card</option>
				<option value="debit" {{ $payment_line->card_type == 'debit' ? 'selected' : '' }}>Debit Card</option>
				<option value="visa" {{ $payment_line->card_type == 'visa' ? 'selected' : '' }}>Visa</option>
				<option value="master" {{ $payment_line->card_type == 'master' ? 'selected' : '' }}>MasterCard</option>
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label for="card_month">{{ __('lang_v1.month') }}</label>
			<input type="text" name="card_month" id="card_month" class="form-control" placeholder="{{ __('lang_v1.month') }}" value="{{ $payment_line->card_month }}">
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label for="card_year">{{ __('lang_v1.year') }}</label>
			<input type="text" name="card_year" id="card_year" class="form-control" placeholder="{{ __('lang_v1.year') }}" value="{{ $payment_line->card_year }}">
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label for="card_security">{{ __('lang_v1.security_code') }}</label>
			<input type="text" name="card_security" id="card_security" class="form-control" placeholder="{{ __('lang_v1.security_code') }}" value="{{ $payment_line->card_security }}">
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<div class="payment_details_div @if( $payment_line->method !== 'cheque' ) {{ 'hide' }} @endif" data-type="cheque" >
	<div class="col-md-12">
		<div class="form-group">
			<label for="cheque_number">{{ __('lang_v1.cheque_no') }}</label>
			<input type="text" name="cheque_number" id="cheque_number" class="form-control" placeholder="{{ __('lang_v1.cheque_no') }}" value="{{ $payment_line->cheque_number }}">
		</div>
	</div>
</div>
<div class="payment_details_div @if( $payment_line->method !== 'bank_transfer' ) {{ 'hide' }} @endif" data-type="bank_transfer" >
	<div class="col-md-12">
		<div class="form-group">
			<label for="bank_account_number">{{ __('lang_v1.bank_account_number') }}</label>
			<input type="text" name="bank_account_number" id="bank_account_number" class="form-control" placeholder="{{ __('lang_v1.bank_account_number') }}" value="{{ $payment_line->bank_account_number }}">
		</div>
	</div>
</div>
<div class="payment_details_div @if( $payment_line->method !== 'custom_pay_1' ) {{ 'hide' }} @endif" data-type="custom_pay_1" >
	<div class="col-md-12">
		<div class="form-group">
			<label for="transaction_no_1">{{ __('lang_v1.transaction_no') }}</label>
			<input type="text" name="transaction_no_1" id="transaction_no_1" class="form-control" placeholder="{{ __('lang_v1.transaction_no') }}" value="{{ $payment_line->transaction_no }}">
		</div>
	</div>
</div>
<div class="payment_details_div @if( $payment_line->method !== 'custom_pay_2' ) {{ 'hide' }} @endif" data-type="custom_pay_2" >
	<div class="col-md-12">
		<div class="form-group">
			<label for="transaction_no_2">{{ __('lang_v1.transaction_no') }}</label>
			<input type="text" name="transaction_no_2" id="transaction_no_2" class="form-control" placeholder="{{ __('lang_v1.transaction_no') }}" value="{{ $payment_line->transaction_no }}">
		</div>
	</div>
</div>
<div class="payment_details_div @if( $payment_line->method !== 'custom_pay_3' ) {{ 'hide' }} @endif" data-type="custom_pay_3" >
	<div class="col-md-12">
		<div class="form-group">
			<label for="transaction_no_3">{{ __('lang_v1.transaction_no') }}</label>
			<input type="text" name="transaction_no_3" id="transaction_no_3" class="form-control" placeholder="{{ __('lang_v1.transaction_no') }}" value="{{ $payment_line->transaction_no }}">
		</div>
	</div>
</div>