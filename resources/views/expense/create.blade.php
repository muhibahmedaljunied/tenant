@extends('layouts.app')
@section('title', __('expense.add_expense'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('expense.add_expense')</h1>
</section>

<!-- Main content -->

<section class="content">
    <form action="{{ action('ExpenseController@store') }}" method="POST" id="add_expense_form" enctype="multipart/form-data">
        @csrf
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    @php
                        $default_location = count($business_locations) == 1 ? current(array_keys($business_locations->toArray())) : null;
                    @endphp

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="location_id">@lang('purchase.business_location'):</label>
                            <select name="location_id" id="location_id" class="form-control select2" required>
                                <option value="" disabled selected>@lang('messages.please_select')</option>
                                @foreach($business_locations as $key => $value)
                                    <option value="{{ $key }}" {{ $default_location == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="expense_category_id">@lang('expense.expense_category'):</label>
                            <select name="expense_category_id" id="expense_category_id" class="form-control select2">
                                <option value="" disabled selected>@lang('messages.please_select')</option>
                                @foreach($expense_categories as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
    </form>
</section>

<script type="text/javascript">
	$(document).ready( function(){
		$('.paid_on').datetimepicker({
            format: moment_date_format + ' ' + moment_time_format,
            ignoreReadonly: true,
        });
	});
	
	__page_leave_confirmation('#add_expense_form');
	$(document).on('change', 'input#final_total, input.payment-amount', function() {
		calculateExpensePaymentDue();
	});

	function calculateExpensePaymentDue() {
		var final_total = __read_number($('input#final_total'));
		var payment_amount = __read_number($('input.payment-amount'));
		var payment_due = final_total - payment_amount;
		$('#payment_due').text(__currency_trans_from_en(payment_due, true, false));
	}

	$(document).on('change', '#recur_interval_type', function() {
	    if ($(this).val() == 'months') {
	        $('.recur_repeat_on_div').removeClass('hide');
	    } else {
	        $('.recur_repeat_on_div').addClass('hide');
	    }
	});

	$('#is_refund').on('ifChecked', function(event){
		$('#recur_expense_div').addClass('hide');
	});
	$('#is_refund').on('ifUnchecked', function(event){
		$('#recur_expense_div').removeClass('hide');
	});

	$(document).on('change', '.payment_types_dropdown, #location_id', function(e) {
	    var default_accounts = $('select#location_id').length ? 
	                $('select#location_id')
	                .find(':selected')
	                .data('default_payment_accounts') : [];
	    var payment_types_dropdown = $('.payment_types_dropdown');
	    var payment_type = payment_types_dropdown.val();
	    if (payment_type) {
	        var default_account = default_accounts && default_accounts[payment_type]['account'] ? 
	            default_accounts[payment_type]['account'] : '';
	        var payment_row = payment_types_dropdown.closest('.payment_row');
	        var row_index = payment_row.find('.payment_row_index').val();

	        var account_dropdown = payment_row.find('select#account_' + row_index);
	        if (account_dropdown.length && default_accounts) {
	            account_dropdown.val(default_account);
	            account_dropdown.change();
	        }
	    }
	});
</script>
@endsection