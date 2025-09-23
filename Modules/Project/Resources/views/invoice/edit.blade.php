@extends('layouts.app')
@section('title', __('project::lang.invoices'))

@section('content')
<section class="content">
	<h1>
		<i class="fa fa-file"></i>
    	@lang('project::lang.invoice')
    	<small>@lang('messages.edit')</small>
    </h1>
    <!-- form open -->
    <form action="{{ action('\\Modules\\Project\\Http\\Controllers\\InvoiceController@update', $transaction->id) }}" id="invoice_form" method="POST">
    @csrf
    @method('PUT')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="pjt_title">{{ __('project::lang.title') }}:*</label>
                        <input type="text" name="pjt_title" id="pjt_title" class="form-control" required value="{{ $transaction->pjt_title }}">
                    </div>
                </div>
                <!-- project_id -->
                <input type="hidden" name="pjt_project_id" value="{{ $transaction->pjt_project_id }}" class="form-control">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="contact_id">{{ __('role.customer') }}:*</label>
                        <select name="contact_id" id="contact_id" class="form-control select2" required style="width: 100%;">
                            <option value="">{{ __('messages.please_select') }}</option>
                            @foreach($customers as $id => $name)
                                <option value="{{ $id }}" {{ $transaction->contact_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location_id">{{ __('business.business_location') }}:*</label>
                        <select name="location_id" id="location_id" class="form-control" required style="width: 100%;">
                            <option value="">{{ __('messages.please_select') }}</option>
                            @foreach($business_locations as $id => $name)
                                <option value="{{ $id }}" {{ $transaction->location_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="transaction_date">{{ __('project::lang.invoice_date') }}:*</label>
                        <input type="text" name="transaction_date" id="transaction_date" class="form-control date-picker" required readonly value="{{ @format_date($transaction->transaction_date) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="multi-input">
                            <label for="pay_term_number">{{ __('contact.pay_term') }}:</label> @show_tooltip(__('tooltip.pay_term'))
                            <br/>
                            <input type="number" name="pay_term_number" id="pay_term_number" class="form-control width-40 pull-left" placeholder="{{ __('contact.pay_term') }}" value="{{ $transaction->pay_term_number }}">
                            <select name="pay_term_type" id="pay_term_type" class="form-control width-60 pull-left">
                                <option value="">{{ __('messages.please_select') }}</option>
                                <option value="months" {{ $transaction->pay_term_type == 'months' ? 'selected' : '' }}>{{ __('lang_v1.months') }}</option>
                                <option value="days" {{ $transaction->pay_term_type == 'days' ? 'selected' : '' }}>{{ __('lang_v1.days') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">{{ __('sale.status') }}:*</label>
                        <select name="status" id="status" class="form-control" required style="width: 100%;">
                            <option value="">{{ __('messages.please_select') }}</option>
                            @foreach($statuses as $id => $name)
                                <option value="{{ $id }}" {{ $transaction->status == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /box -->
    <div class="box box-primary">
        <div class="box-body">
            <div class="col-md-12">
                <div class="col-md-3">
                    <label>@lang('project::lang.task'):*</label>
                </div>
                <div class="col-md-2">
                    <label>@lang('project::lang.rate'):*</label>
                </div>
                <div class="col-md-2">
                    <label>@lang('project::lang.qty'):*</label>
                </div>
                <div class="col-md-2">
                    <label>@lang('business.tax')(%)</label>
                </div>
                <div class="col-md-2">
                    <label>@lang('receipt.total'):*</label>
                </div>
                <div class="col-md-1">
                </div>
            </div>
            <div class="invoice_lines">
                @foreach($transaction->invoiceLines as $key => $invoiceLine)
                    <div class="col-md-12 il-bg invoice_line">
                        <div class="mt-10">
                        <!-- invoice line id -->
                        <input type="hidden" name="invoice_line_id[]" value="{{ $invoiceLine->id }}" class="form-control">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="existing_task[]" class="form-control" required value="{{ $invoiceLine->task }}">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default toggle_description" type="button">
                                            <i class="fa fa-info-circle text-info" data-toggle="tooltip" title="@lang('project::lang.toggle_invoice_task_description')"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" name="existing_rate[]" class="form-control rate input_number" required value="{{ @num_format($invoiceLine->rate) }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" name="existing_quantity[]" class="form-control quantity input_number" required value="{{ @format_quantity($invoiceLine->quantity) }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="existing_tax_rate_id[]" class="form-control tax">
                                    <option value="">--</option>
                                    @foreach($taxes as $id => $tax)
                                        <option value="{{ $id }}"
                                            @if(isset($tax_attributes[$id]))
                                                @foreach($tax_attributes[$id] as $attr => $val)
                                                    {{ $attr }}="{{ $val }}"
                                                @endforeach
                                            @endif
                                            {{ $invoiceLine->tax_rate_id == $id ? 'selected' : '' }}
                                        >{{ $tax }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" name="existing_total[]" class="form-control total input_number" required readonly value="{{ @num_format($invoiceLine->total) }}">
                            </div>
                        </div>
                        @if($key != 0)
                            <div class="col-md-1">
                                <i class="fa fa-trash text-danger cursor-pointer remove_this_row"></i>
                            </div>
                        @endif
                        <div class="col-md-11">
                            <div class="form-group description" style="@if(!isset($invoiceLine->description))display: none;@endif">
                                <textarea name="existing_description[]" class="form-control" placeholder="{{ __('lang_v1.description') }}" rows="3">{{ $invoiceLine->description }}</textarea>
                            </div>
                        </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-4 col-md-offset-4">
                <br>
                <button type="button" class="btn btn-block btn-primary btn-sm add_invoice_line">
                    @lang('project::lang.add_a_row')
                    <i class="fa fa-plus-circle"></i>
                </button>
            </div>
        </div>
        <!-- including invoice line row -->
        @includeIf('project::invoice.partials.invoice_line_row')
    </div>  <!-- /box -->
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-md-6 col-md-offset-10">
                    <b>@lang('sale.subtotal'):</b>
                    <span class="subtotal display_currency" data-currency_symbol="true" >0.00</span>
                    <input type="hidden" name="total_before_tax" id="subtotal" value="0.00">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="discount_type">{{ __('sale.discount_type') }}:</label>
                    <select name="discount_type" id="discount_type" class="form-control select2" style="width: 100%;">
                        <option value="">{{ __('messages.please_select') }}</option>
                        @foreach($discount_types as $id => $name)
                            <option value="{{ $id }}" {{ $transaction->discount_type == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="discount_amount">{{ __('sale.discount_amount') }}:</label>
                    <input type="text" name="discount_amount" id="discount_amount" class="form-control input_number" value="{{ @num_format($transaction->discount_amount) }}">
                </div>
            </div> <br>
            <div class="row">
                <div class="col-md-6 col-md-offset-6">
                    <b>@lang('project::lang.invoice_total'):</b>
                    <span class="invoice_total display_currency" data-currency_symbol="true" >0.00</span>
                    <input type="hidden" name="final_total" id="invoice_total" value="0.00">
                </div>
            </div> <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="staff_note">{{ __('project::lang.terms') }}:</label>
                        <textarea name="staff_note" id="staff_note" class="form-control" rows="3">{{ $transaction->staff_note }}</textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="additional_notes">{{ __('project::lang.notes') }}:</label>
                        <textarea name="additional_notes" id="additional_notes" class="form-control" rows="3">{{ $transaction->additional_notes }}</textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary pull-right">
                @lang('messages.update')
            </button>
        </div>
    </div> <!-- /box -->
</form> <!-- /form close -->
</section>
<link rel="stylesheet" href="{{ url('modules/project/sass/project.css?v=' . $asset_v) }}">
@endsection
@section('javascript')
<script src="{{ url('modules/project/js/project.js?v=' . $asset_v) }}"></script>
<!-- call a function to calculate subtotal once edit page is loaded -->
<script type="text/javascript">
	$(document).ready(function() {
		calculateSubtotal();
	});
</script>
@endsection