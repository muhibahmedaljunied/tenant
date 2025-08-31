@php
	$repair = [];
	if (!empty($view_data['job_sheet'])) {
		$repair['repair_job_sheet_id'] = $view_data['job_sheet']['id'];
		$repair['repair_due_date'] = $view_data['job_sheet']['delivery_date'];
		$repair['repair_completed_on'] = null;
		$repair['repair_warranty_id'] = null;
		$repair['repair_status_id'] = $view_data['job_sheet']['status_id'];
		$repair['repair_brand_id'] = $view_data['job_sheet']['brand_id'];
		$repair['repair_device_id'] = $view_data['job_sheet']['device_id'];
		$repair['repair_model_id'] = $view_data['job_sheet']['device_model_id'];
		$repair['repair_serial_no'] = $view_data['job_sheet']['serial_no'];
		$repair['repair_defects'] = $view_data['job_sheet']['defects'];
		$repair['res_waiter_id'] = $view_data['job_sheet']['service_staff'];
	} elseif (!empty($transaction)) {
		$repair['repair_due_date'] = $transaction['repair_due_date'];
		$repair['repair_completed_on'] = $transaction['repair_completed_on'];
		$repair['repair_warranty_id'] = $transaction['repair_warranty_id'];
		$repair['repair_status_id'] = $transaction['repair_status_id'];
		$repair['repair_brand_id'] = $transaction['repair_brand_id'];
		$repair['repair_device_id'] = $transaction['repair_device_id'];
		$repair['repair_model_id'] = $transaction['repair_model_id'];
		$repair['repair_serial_no'] = $transaction['repair_serial_no'];
		$repair['repair_defects'] = $transaction['repair_defects'];
	}
@endphp
<input type="hidden" name="has_module_data" value="1">
<input type="hidden" id="repair_transaction_id" value="@if(!empty($transaction->id)){{$transaction->id}}@endif">
<input type="hidden" id="repair_job_sheet_id" name="repair_job_sheet_id" value="@if(!empty($repair['repair_job_sheet_id'])){{$repair['repair_job_sheet_id']}}@endif">
{{-- override serive staff --}}
@if(!empty($repair['res_waiter_id']))
	<input type="hidden" id="repair_technician" value="{{$repair['res_waiter_id']}}">
@endif
<div class="row">
	<div class="col-sm-4">
		<div class="form-group">
			<label for="repair_due_date">{{ __('repair::lang.delivery_date') . ':' }}</label>
			@show_tooltip(__('repair::lang.repair_due_date_tooltip'))
			<div class="input-group">
				<span class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</span>
				<input type="text" name="repair_due_date" id="repair_due_date" class="form-control" value="{{ !empty($repair['repair_due_date']) ? @format_datetime($repair['repair_due_date']) : '' }}" readonly>
				<span class="input-group-addon">
					<i class="fas fa-times-circle cursor-pointer clear_repair_due_date"></i>
				</span>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label for="repair_completed_on">{{ __('repair::lang.repair_completed_on') . ':' }}</label>
			<div class="input-group">
				<span class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</span>
				<input type="text" name="repair_completed_on" id="repair_completed_on" class="form-control" value="{{ !empty($repair['repair_completed_on']) ? @format_datetime($repair['repair_completed_on']) : '' }}" readonly>
				<span class="input-group-addon">
					<i class="fas fa-times-circle cursor-pointer clear_repair_completed_on"></i>
				</span>
			</div>
		</div>
	</div>
	@if(!empty($view_data['warranties']))
		<div class="col-sm-4">
			<div class="form-group">
				<label for="repair_warranty_id">{{ __('lang_v1.warranty') . ':' }}</label>
				<select name="repair_warranty_id" id="repair_warranty_id" class="form-control select2">
					<option value="">{{ __('messages.please_select') }}</option>
					@foreach($view_data['warranties'] as $key => $value)
						<option value="{{ $key }}" {{ (!empty($repair['repair_warranty_id']) && $repair['repair_warranty_id'] == $key) ? 'selected' : '' }}>{{ $value }}</option>
					@endforeach
				</select>
			</div>
		</div>
	@endif
	<div class="col-sm-4">
		<div class="form-group">
			<label for="repair_status_id">{{__('sale.status') . ':'}}</label>
			<select name="repair_status_id" class="form-control" id="repair_status_id" required>
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
		<div class="form-group">
			<label for="repair_brand_id">{{ __('product.brand') . ':' }}</label>
			<select name="repair_brand_id" id="repair_brand_id" class="form-control select2">
				<option value="">{{ __('messages.please_select') }}</option>
				@foreach($view_data['brands'] as $key => $value)
					<option value="{{ $key }}" {{ (!empty($repair['repair_brand_id']) && $repair['repair_brand_id'] == $key) ? 'selected' : '' }}>{{ $value }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label for="repair_device_id">{{ __('repair::lang.device') . ':' }}</label>
			<select name="repair_device_id" id="repair_device_id" class="form-control select2">
				<option value="">{{ __('messages.please_select') }}</option>
				@foreach($view_data['devices'] as $key => $value)
					<option value="{{ $key }}" {{ (!empty($repair['repair_device_id']) && $repair['repair_device_id'] == $key) ? 'selected' : '' }}>{{ $value }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label for="repair_model_id">{{ __('repair::lang.device_model') . ':' }}</label>
			<select name="repair_model_id" id="repair_model_id" class="form-control select2">
				<option value="">{{ __('messages.please_select') }}</option>
				@foreach($view_data['device_models'] as $key => $value)
					<option value="{{ $key }}" {{ (!empty($repair['repair_model_id']) && $repair['repair_model_id'] == $key) ? 'selected' : '' }}>{{ $value }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
		<div class="form-group">
			<label for="repair_serial_no">{{ __('repair::lang.serial_no') . ':' }}</label>
			<input type="text" name="repair_serial_no" id="repair_serial_no" class="form-control" placeholder="{{ __('repair::lang.serial_no') }}" value="{{ !empty($repair['repair_serial_no']) ? $repair['repair_serial_no'] : '' }}">
		</div>
	</div>
	<div class="col-sm-6 mt-15">
		<div class="btn-group mt-5" role="group">
			<button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#checklist_modal"><i class="fa fa-plus"></i> @lang('repair::lang.pre_repair_checklist')</button>
			<button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#security_modal"><i class="fa fa-lock"></i> @lang('repair::lang.security')</button>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label for="repair_defects">{{__('repair::lang.problem_reported_by_customer') . ':'}}</label> <br>
			<textarea name="repair_defects" id="repair_defects" class="tags-look" rows="3">{{ !empty($repair['repair_defects']) ? $repair['repair_defects'] : '' }}</textarea>
		</div>
	</div>
</div>
@include('repair::repair.partials.security_modal')
@include('repair::repair.partials.checklist_modal')

<style type="text/css">
	#product_category_div, #feature_product_div, #product_brand_div{
		display: none !important;
	}
</style>