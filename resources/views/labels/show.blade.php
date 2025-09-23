@extends('layouts.app')
@section('title', __('barcode.print_labels'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
<br>
    <h1>@lang('barcode.print_labels') @show_tooltip(__('tooltip.print_label'))</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content no-print">
	<form action="#" method="post" id="preview_setting_form" onsubmit="return false">
	@csrf
	@component('components.widget', ['class' => 'box-primary', 'title' => __('product.add_product_for_labels')])
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-search"></i>
						</span>
						<input type="text" name="search_product" class="form-control" id="search_product_for_label" placeholder="{{ __('lang_v1.enter_product_name_to_print_labels') }}" autofocus>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<table class="table table-bordered table-striped table-condensed" id="product_table">
					<thead>
						<tr>
							<th>@lang( 'barcode.products' )</th>
							<th>@lang( 'barcode.no_of_labels' )</th>
							@if(request()->session()->get('business.enable_lot_number') == 1)
								<th>@lang( 'lang_v1.lot_number' )</th>
							@endif
							@if(request()->session()->get('business.enable_product_expiry') == 1)
								<th>@lang( 'product.exp_date' )</th>
							@endif
							<th>@lang('lang_v1.packing_date')</th>
							<th>الباركود</th>
						</tr>
					</thead>
					<tbody>
						@include('labels.partials.show_table_rows', ['index' => 0])
					</tbody>
				</table>
			</div>
		</div>
	@endcomponent

	@component('components.widget', ['class' => 'box-primary', 'title' => __( 'barcode.info_in_labels' )])
		<div class="row">
			<div class="col-sm-3">
				<div class="checkbox">
				    <label>
				    	<input type="checkbox" checked name="print[name]" value="1"> <b>@lang( 'barcode.print_name' )</b>
				    </label>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="checkbox">
				    <label>
				    	<input type="checkbox" checked name="print[variations]" value="1"> <b>@lang( 'barcode.print_variations' )</b>
				    </label>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="checkbox">
				    <label>
				    	<input type="checkbox" checked name="print[price]" value="1" id="is_show_price"> <b>@lang( 'barcode.print_price' )</b>
				    </label>
				</div>
			</div>

			<div class="col-sm-3" id="price_type_div">
				<div class="form-group">
					<label for="print[price_type]">@trans( 'barcode.show_price' ) :</label>
					<div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-info"></i>
						</span>
						<select name="print[price_type]" id="print[price_type]" class="form-control">
							<option value="inclusive" @if(old('print.price_type', 'inclusive') == 'inclusive') selected @endif>{{ __('product.inc_of_tax') }}</option>
							<option value="exclusive" @if(old('print.price_type') == 'exclusive') selected @endif>{{ __('product.exc_of_tax') }}</option>
						</select>
					</div>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="checkbox">
				    <label>
				    	<input type="checkbox" checked name="print[business_name]" value="1"> <b>@lang( 'barcode.print_business_name' )</b>
				    </label>
				</div>
			</div>
			@if(request()->session()->get('business.enable_lot_number') == 1)
			<div class="col-sm-3">
				<div class="checkbox">
				    <label>
				    	<input type="checkbox" checked name="print[lot_number]" value="1"> <b>@lang( 'lang_v1.print_lot_number' )</b>
				    </label>
				</div>
			</div>
			@endif
			@if(request()->session()->get('business.enable_product_expiry') == 1)
			<div class="col-sm-3">
				<div class="checkbox">
				    <label>
				    	<input type="checkbox" checked name="print[exp_date]" value="1"> <b>@lang( 'lang_v1.print_exp_date' )</b>
				    </label>
				</div>
			</div>
			@endif

			<div class="col-sm-3">
				<div class="checkbox">
				    <label>
				    	<input type="checkbox" checked name="print[packing_date]" value="1"> <b>@lang( 'lang_v1.print_packing_date' )</b>
				    </label>
				</div>
			</div>

			<div class="col-sm-12">
				<hr/>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label for="price_type">@trans( 'barcode.barcode_setting' ) :</label>
					<div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-cog"></i>
						</span>
						<select name="barcode_setting" id="barcode_setting" class="form-control">
							@foreach($barcode_settings as $key => $value)
								<option value="{{ $key }}" @if(old('barcode_setting') == $key) selected @endif>{{ $value }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="code_type">@trans( 'نوع الكود' ) :</label>
					<div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-cog"></i>
						</span>
						<select name="code_type" id="code_type" class="form-control">
							<option value="parcode" @if(old('code_type') == 'parcode') selected @endif>Parcode</option>
							<option value="qrcode" @if(old('code_type') == 'qrcode') selected @endif>QrCode</option>
						</select>
					</div>
				</div>
			</div>

			<div class="clearfix"></div>
			
			<div class="col-sm-4 col-sm-offset-8">
				<button type="button" id="labels_preview" class="btn btn-primary pull-right btn-flat btn-block">@lang( 'barcode.preview' )</button>
			</div>
		</div>
	@endcomponent
	</form>

	<div class="col-sm-8 hide display_label_div">
		<h3 class="box-title">@lang( 'barcode.preview' )</h3>
		<button type="button" class="col-sm-offset-2 btn btn-success btn-block" id="print_label">Print</button>
	</div>
	<div class="clearfix"></div>
</section>

<!-- Preview section-->
<div id="preview_box">
</div>

@stop
@section('javascript')
	<script src="{{ url('js/labels.js?v=' . $asset_v) }}"></script>
@endsection
