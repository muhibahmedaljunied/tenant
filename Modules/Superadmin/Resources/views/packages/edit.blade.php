@extends('layouts.app')
@section('title', __('superadmin::lang.superadmin') . ' | ' . __('superadmin::lang.packages'))

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('superadmin::lang.packages') <small>@lang('superadmin::lang.edit_package')</small></h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>


<!-- Main content -->
<section class="content">
	<form action="{{ route('packages.update', $packages->id) }}" method="POST" id="edit_package_form">
		@csrf
		@method('PUT')
	<div class="box box-solid">
		<div class="box-body">

	    	<div class="row">
				<!-- Name -->
				<div class="col-sm-6">
					<div class="form-group">
						<label for="name">{{ __('messages.name') }}:</label>
						<input type="text" name="name" id="name" class="form-control" value="{{ old('name', $packages->name) }}" required>
					</div>
				</div>
				<!-- Description -->
				<div class="col-sm-6">
					<div class="form-group">
						<label for="description">{{ __('superadmin::lang.description') }}:</label>
						<input type="text" name="description" id="description" class="form-control" value="{{ old('description', $packages->description) }}" required>
					</div>
				</div>
				<!-- Location Count -->
				<div class="col-sm-6">
					<div class="form-group">
						<label for="location_count">{{ __('superadmin::lang.location_count') }}:</label>
						<input type="number" name="location_count" id="location_count" class="form-control" value="{{ old('location_count', $packages->location_count) }}" required min="0">
						<span class="help-block">
							@lang('superadmin::lang.infinite_help')
						</span>
					</div>
				</div>
				<!-- User Count -->
				<div class="col-sm-6">
					<div class="form-group">
						<label for="user_count">{{ __('superadmin::lang.user_count') }}:</label>
						<input type="number" name="user_count" id="user_count" class="form-control" value="{{ old('user_count', $packages->user_count) }}" required min="0">
						<span class="help-block">
							@lang('superadmin::lang.infinite_help')
						</span>
					</div>
				</div>
				<!-- Product Count -->
				<div class="col-sm-6">
					<div class="form-group">
						<label for="product_count">{{ __('superadmin::lang.product_count') }}:</label>
						<input type="number" name="product_count" id="product_count" class="form-control" value="{{ old('product_count', $packages->product_count) }}" required min="0">
						<span class="help-block">
							@lang('superadmin::lang.infinite_help')
						</span>
					</div>
				</div>
				<!-- Invoice Count -->
				<div class="col-sm-6">
					<div class="form-group">
						<label for="invoice_count">{{ __('superadmin::lang.invoice_count') }}:</label>
						<input type="number" name="invoice_count" id="invoice_count" class="form-control" value="{{ old('invoice_count', $packages->invoice_count) }}" required min="0">
						<span class="help-block">
							@lang('superadmin::lang.infinite_help')
						</span>
					</div>
				</div>
				<!-- Interval -->
				<div class="col-sm-6">
					<div class="form-group">
						<label for="interval">{{ __('superadmin::lang.interval') }}:</label>
						<select name="interval" id="interval" class="form-control select2" required>
							<option value="">{{ __('messages.please_select') }}</option>
							@foreach($intervals as $key => $value)
								<option value="{{ $key }}" {{ old('interval', $packages->interval) == $key ? 'selected' : '' }}>{{ $value }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<!-- Interval Count -->
				<div class="col-sm-6">
					<div class="form-group">
						<label for="interval_count">{{ __('superadmin::lang.interval_count') }}:</label>
						<input type="number" name="interval_count" id="interval_count" class="form-control" value="{{ old('interval_count', $packages->interval_count) }}" required>
					</div>
				</div>
				<!-- Trial Days -->
				<div class="col-sm-6">
					<div class="form-group">
						<label for="trial_days">{{ __('superadmin::lang.trial_days') }}:</label>
						<input type="number" name="trial_days" id="trial_days" class="form-control" value="{{ old('trial_days', $packages->trial_days) }}" required min="0">
					</div>
				</div>
				<!-- Price -->
				<div class="col-sm-6">
					<div class="form-group">
						<label for="price">{{ __('superadmin::lang.price') }}:</label>
						<input type="text" name="price" id="price" class="form-control input_number" value="{{ old('price', $packages->price) }}" required>
					</div>
				</div>
				<!-- Sort Order -->
				<div class="col-sm-6">
					<div class="form-group">
						<label for="sort_order">{{ __('superadmin::lang.sort_order') }}:</label>
						<input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $packages->sort_order) }}" required>
					</div>
				</div>
				<div class="clearfix"></div>
				<!-- Checkboxes -->
				<div class="col-sm-6">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="is_private" value="1" class="input-icheck" {{ old('is_private', $packages->is_private) ? 'checked' : '' }}>
                        {{__('superadmin::lang.private_superadmin_only')}}
						</label>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="is_one_time" value="1" class="input-icheck" {{ old('is_one_time', $packages->is_one_time) ? 'checked' : '' }}>
                        {{__('superadmin::lang.one_time_only_subscription')}}
						</label>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-sm-4">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="enable_custom_link" value="1" class="input-icheck" id="enable_custom_link" {{ old('enable_custom_link', $packages->enable_custom_link) ? 'checked' : '' }}>
                        {{__('superadmin::lang.enable_custom_subscription_link')}}
						</label>
					</div>
				</div>
				<div id="custom_link_div" @if(empty($packages->enable_custom_link)) class="hide" @endif>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="custom_link">{{ __('superadmin::lang.custom_link') }}:</label>
							<input type="text" name="custom_link" id="custom_link" class="form-control" value="{{ old('custom_link', $packages->custom_link) }}">
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="custom_link_text">{{ __('superadmin::lang.custom_link_text') }}:</label>
							<input type="text" name="custom_link_text" id="custom_link_text" class="form-control" value="{{ old('custom_link_text', $packages->custom_link_text) }}">
						</div>
					</div>
				</div>
				<div class="clearfix"></div>

				@foreach($permissions as $module => $module_permissions)
					@foreach($module_permissions as $permission)
					@php
						$value = old('custom_permissions.' . $permission['name'], isset($packages->custom_permissions[$permission['name']]) ? $packages->custom_permissions[$permission['name']] : false);
					@endphp
					<div class="col-sm-3">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="custom_permissions[{{ $permission['name'] }}]" value="1" class="input-icheck" {{ $value ? 'checked' : '' }}>
	                        {{$permission['label']}}
							</label>
						</div>
					</div>
					@endforeach
				@endforeach
				<div class="col-sm-3 ">
					<div class="checkbox">
						<label>
                        <input type="checkbox" name="is_active" value="1" class="input-icheck" {{ old('is_active', $packages->is_active) ? 'checked' : '' }}>
                        {{__('superadmin::lang.is_active')}}
						</label>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-sm-4">
					<div class="checkbox">
						<label>
                        <input type="checkbox" name="update_subscriptions" value="1" class="input-icheck">
                        {{__('superadmin::lang.update_existing_subscriptions')}}
						</label>
						@show_tooltip(__('superadmin::lang.update_existing_subscriptions_tooltip'))
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<button type="submit" class="btn btn-primary pull-right btn-flat">@lang('messages.save')</button>
				</div>
			</div>

		</div>
	</div>

	</form>
</section>

@endsection

@section('javascript')
	<script type="text/javascript">
		$(document).ready(function(){
			$('form#edit_package_form').validate();
		});
		$('#enable_custom_link').on('ifChecked', function(event){
		   $("div#custom_link_div").removeClass('hide');
		});
		$('#enable_custom_link').on('ifUnchecked', function(event){
		   $("div#custom_link_div").addClass('hide');
		});
	</script>
@endsection