@extends('layouts.app')
@section('title', __('lang_v1.edit_stock_transfer'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('lang_v1.edit_stock_transfer')</h1>
</section>

<!-- Main content -->
<section class="content no-print">
    <form action="{{ action('StockTransferController@update', [$sell_transfer->id]) }}" method="post" id="stock_transfer_form">
        @csrf
        @method('PUT')
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="transaction_date">{{ __('messages.date') }}:*</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" name="transaction_date" id="transaction_date" class="form-control" value="{{ @format_datetime($sell_transfer->transaction_date) }}" readonly required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="ref_no">{{ __('purchase.ref_no') }}:</label>
                            <input type="text" name="ref_no" id="ref_no" class="form-control" value="{{ $sell_transfer->ref_no }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="status">{{ __('sale.status') }}:*</label> @show_tooltip(__('lang_v1.completed_status_help'))
                            <select name="status" id="status" class="form-control select2" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($statuses as $key => $value)
                                    <option value="{{ $key }}" {{ $sell_transfer->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="location_id">{{ __('lang_v1.location_from') }}:*</label>
                            <select name="location_id" id="location_id" class="form-control select2" disabled>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($business_locations as $key => $value)
                                    <option value="{{ $key }}" {{ $sell_transfer->location_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="transfer_location_id">{{ __('lang_v1.location_to') }}:*</label>
                            <select name="transfer_location_id" id="transfer_location_id" class="form-control select2" disabled>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($business_locations as $key => $value)
                                    <option value="{{ $key }}" {{ $purchase_transfer->location_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--box end-->
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">{{ __('stock_adjustment.search_products') }}</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>
                                <input type="text" name="search_product" id="search_product_for_srock_adjustment" class="form-control" placeholder="{{ __('stock_adjustment.search_product') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed" id="stock_adjustment_product_table">
                                <thead>
                                    <tr>
                                        <th class="col-sm-4 text-center">@lang('sale.product')</th>
                                        <th class="col-sm-3 text-center">@lang('sale.qty')</th>
                                        <th class="col-sm-3 text-center">@lang('sale.subtotal')</th>
                                        <th class="col-sm-2 text-center"><i class="fa fa-trash" aria-hidden="true"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $product_row_index = 0;
                                        $subtotal = 0;
                                    @endphp
                                    @foreach($products as $product)
                                        @include('stock_transfer.partials.product_table_row', ['product' => $product, 'row_index' => $loop->index])
                                        @php
                                            $product_row_index = $loop->index + 1;
                                            $subtotal += ($product->quantity_ordered*$product->last_purchased_price);
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="text-center"><td colspan="2"></td><td><div class="pull-right"><b>@lang('stock_adjustment.total_amount'):</b> <span id="total_adjustment">{{@num_format($subtotal)}}</span></div></td></tr>
                                </tfoot>
                            </table>
                            <input type="hidden" id="product_row_index" value="{{$product_row_index}}">
                            <input type="hidden" id="total_amount" name="final_total" value="{{$subtotal}}">
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--box end-->
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="shipping_charges">{{ __('lang_v1.shipping_charges') }}:</label>
                            <input type="text" name="shipping_charges" id="shipping_charges" class="form-control input_number" value="{{ @num_format($sell_transfer->shipping_charges) }}" placeholder="{{ __('lang_v1.shipping_charges') }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="additional_notes">{{ __('purchase.additional_notes') }}</label>
                            <textarea name="additional_notes" id="additional_notes" class="form-control" rows="3">{{ $sell_transfer->additional_notes }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="submit" id="save_stock_transfer" class="btn btn-primary pull-right">@lang('messages.save')</button>
                    </div>
                </div>
            </div>
        </div> <!--box end-->
    </form>
</section>
@stop
@section('javascript')
    <script src="{{ asset('js/stock_transfer.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        __page_leave_confirmation('#stock_transfer_form');
    </script>
@endsection