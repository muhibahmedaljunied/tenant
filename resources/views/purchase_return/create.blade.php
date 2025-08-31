@extends('layouts.app')
@section('title', __('lang_v1.add_purchase_return'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
<br>
    <h1>@lang('lang_v1.add_purchase_return')</h1>
</section>

<!-- Main content -->
<section class="content no-print">
    <form action="{{ action('CombinedPurchaseReturnController@save') }}" method="POST" id="purchase_return_form" enctype="multipart/form-data">
        @csrf

        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="supplier_id">@lang('purchase.supplier'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <select name="contact_id" id="supplier_id" class="form-control" required>
                                    <option value="" disabled selected>@lang('messages.please_select')</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="location_id">@lang('purchase.business_location'):</label>
                            <select name="location_id" id="location_id" class="form-control select2" required>
                                <option value="" disabled selected>@lang('messages.please_select')</option>
                                @foreach($business_locations as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="ref_no">@lang('purchase.ref_no'):</label>
                            <input type="text" name="ref_no" id="ref_no" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="transaction_date">@lang('messages.date'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" name="transaction_date" id="transaction_date" class="form-control"
                                    value="{{ now()->format('Y-m-d H:i:s') }}" readonly required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="submit" id="submit_purchase_return_form" class="btn btn-primary pull-right btn-flat">@lang('messages.submit')</button>
            </div>
        </div>
    </form>
</section>

@stop
@section('javascript')
	<script src="{{ asset('js/purchase_return.js?v=' . $asset_v) }}"></script>
	<script type="text/javascript">
		__page_leave_confirmation('#purchase_return_form');
	</script>
@endsection
