@extends('layouts.app')
@section('title', __('lang_v1.edit_purchase_return'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
<br>
    <h1>@lang('lang_v1.edit_purchase_return')</h1>
</section>

<!-- Main content -->
<section class="content no-print">
    <form action="{{ action('CombinedPurchaseReturnController@update') }}" method="post" id="purchase_return_form" enctype="multipart/form-data">
        @csrf
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input type="hidden" name="purchase_return_id" value="{{$purchase_return->id}}">
                            <input type="hidden" id="location_id" value="{{$purchase_return->location_id}}">
                            <label for="supplier_id">{{ __('purchase.supplier') . ':*' }}</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <select name="contact_id" class="form-control" id="supplier_id" required>
                                    <option value="{{ $purchase_return->contact_id }}">{{ $purchase_return->contact->name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="ref_no">{{ __('purchase.ref_no').':' }}</label>
                            <input type="text" name="ref_no" id="ref_no" value="{{ $purchase_return->ref_no }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="transaction_date">{{ __('messages.date') . ':*' }}</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" name="transaction_date" id="transaction_date" value="{{ @format_datetime($purchase_return->transaction_date) }}" class="form-control" readonly required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="document">{{ __('purchase.attach_document') . ':' }}</label>
                            <input type="file" name="document" id="upload_document" accept="{{ implode(',', array_keys(config('constants.document_upload_mimes_types'))) }}">
                            <p class="help-block">@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                            @includeIf('components.document_help_text')</p>
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
                                <input type="text" name="search_product" class="form-control" id="search_product_for_purchase_return" placeholder="{{ __('stock_adjustment.search_products') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" id="total_amount" name="final_total" value="{{$purchase_return->final_total}}">
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed" 
                        id="purchase_return_product_table">
                            <thead>
                                <tr>
                                    <th class="text-center">	
                                        @lang('sale.product')
                                    </th>
                                    @if(session('business.enable_lot_number'))
                                        <th>
                                            @lang('lang_v1.lot_number')
                                        </th>
                                    @endif
                                    @if(session('business.enable_product_expiry'))
                                        <th>
                                            @lang('product.exp_date')
                                        </th>
                                    @endif
                                    <th class="text-center">
                                        @lang('sale.qty')
                                    </th>
                                    <th class="text-center">
                                        @lang('sale.unit_price')
                                    </th>
                                    <th class="text-center">
                                        @lang('sale.subtotal')
                                    </th>
                                    <th class="text-center"><i class="fa fa-trash" aria-hidden="true"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase_lines as $purchase_line)
                                    @include('purchase_return.partials.product_table_row', ['product' => $purchase_line, 'row_index' => $loop->index, 'edit' => true])

                                    @php
                                        $row_index = $loop->iteration;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4 hide">
                        <input type="hidden" id="product_row_index" value="{{$row_index}}">
                        <div class="form-group">
                            <label for="tax_id">{{ __('purchase.purchase_tax') . ':' }}</label>
                            <select name="tax_id" id="tax_id" class="form-control select2" placeholder="'Please Select'">
                                <option value="" data-tax_amount="0" data-tax_type="fixed" selected>@lang('lang_v1.none')</option>
                                @foreach($taxes as $tax)
                                    <option value="{{ $tax->id }}" data-tax_amount="{{ $tax->amount }}" data-tax_type="{{ $tax->calculation_type }}" @if($purchase_return->tax_id == $tax->id) selected @endif>{{ $tax->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="tax_amount" id="tax_amount" value="{{ $purchase_return->tax_amount }}">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="pull-right"><b>@lang('stock_adjustment.total_amount'):</b> <span id="total_return" class="display_currency">{{$purchase_return->final_total}}</span></div>
                    </div>
                </div>
            </div>
        </div> <!--box end-->
        <div class="row">
            <div class="col-md-12">
                <button type="button" id="submit_purchase_return_form" class="btn btn-primary pull-right btn-flat">@lang('messages.update')</button>
            </div>
        </div>
    </form>
</section>
@stop
@section('javascript')
    <script src="{{ url('js/purchase_return.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">