@extends('layouts.app')

@section('title', __('repair::lang.add_repair'))

@section('content')
<style type="text/css">
	.krajee-default.file-preview-frame .kv-file-content {
		height: 65px;
	}
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('repair::lang.add_repair')</h1>
</section>
<!-- Main content -->
<section class="content no-print">
@if(is_null($default_location))
<div class="row">
	<div class="col-sm-3">
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon">
					<i class="fa fa-map-marker"></i>
				</span>
				<select name="select_location_id" id="select_location_id" class="form-control input-sm" required autofocus>
					<option value="">{{ __('lang_v1.select_location') }}</option>
					@foreach($business_locations as $key => $value)
						<option value="{{ $key }}" @if(old('select_location_id') == $key) selected @endif>{{ $value }}</option>
					@endforeach
				</select>
				<span class="input-group-addon">
					@show_tooltip(__('tooltip.sale_location'))
				</span> 
			</div>
		</div>
	</div>
</div>
@endif
<input type="hidden" id="item_addition_method" value="{{$business_details->item_addition_method}}">

@if(!empty($repair_settings['default_product']))
  	<input type="hidden" id="default_product" value="{{$repair_settings['default_product']}}">
@endif
@if(session('business.enable_rp') == 1)
    <input type="hidden" id="reward_point_enabled">
@endif

<form action="{{ action('SellPosController@store') }}" method="post" id="add_sell_form" enctype="multipart/form-data">
@csrf
<input type="hidden" name="status" value="final">
<input type="hidden" name="sub_type" value="repair">
<input type="hidden" name="has_module_data" value="1">
<div class="row">
	<div class="col-md-12 col-sm-12">
		@component('components.widget')
			<input type="hidden" name="location_id" id="location_id" value="{{ $default_location }}" data-receipt_printer_type="{{ isset($bl_attributes[$default_location]['data-receipt_printer_type']) ? $bl_attributes[$default_location]['data-receipt_printer_type'] : 'browser' }}">
			<div class="clearfix"></div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="contact_id">{{ __('contact.customer') . ':*' }}</label>
					<div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-user"></i>
						</span>
						<input type="hidden" id="default_customer_id" value="{{ $walk_in_customer['id']}}" >
						<input type="hidden" id="default_customer_name" value="{{ $walk_in_customer['name']}}" >
						<select name="contact_id" id="customer_id" class="form-control mousetrap" required>
							<option value="">Enter Customer name / phone</option>
						</select>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default bg-white btn-flat add_new_customer" data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
						</span>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="transaction_date">{{ __('repair::lang.repair_added_on') . ':*' }}</label>
					<div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</span>
						<input type="text" name="transaction_date" id="transaction_date" class="form-control" value="{{ $default_datetime }}" readonly required>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="repair_completed_on">{{ __('repair::lang.repair_completed_on') . ':*' }}</label>
					<div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</span>
						<input type="text" name="repair_completed_on" id="repair_completed_on" class="form-control" value="{{ $default_datetime }}" readonly required>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="repair_status_id">{{__('repair::lang.repair_status') . ':*'}}</label>
					<select name="repair_status_id" class="form-control" id="repair_status_id" required></select>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="repair_brand_id">{{ __('repair::lang.manufacturer') . ':' }}</label>
					<select name="repair_brand_id" id="repair_brand_id" class="form-control select2">
						<option value="">{{ __('messages.please_select') }}</option>
						@foreach($brands as $key => $value)
							<option value="{{ $key }}">{{ $value }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="repair_model">{{ __('repair::lang.model') . ':' }}</label>
					<input type="text" name="repair_model" id="repair_model" class="form-control" placeholder="{{ __('repair::lang.model') }}">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="repair_serial_no">{{ __('repair::lang.serial_no') . ':' }}</label>
					<input type="text" name="repair_serial_no" id="repair_serial_no" class="form-control" placeholder="{{ __('repair::lang.serial_no') }}">
				</div>
			</div>
			@if(in_array('service_staff' ,$enabled_modules))
				<div class="col-sm-4">
					<div class="form-group">
						<label for="res_waiter_id">{{ __('repair::lang.assign_repair_to'
