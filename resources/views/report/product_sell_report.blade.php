@extends('layouts.app')
@section('title', __('lang_v1.product_sell_report'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1>{{ __('lang_v1.product_sell_report') }}</h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <form id="product_sell_report_form" method="get" action="{{ action('ReportController@getStockReport') }}">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search_product">{{ __('lang_v1.search_product') . ':' }}</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <input type="hidden" value="" id="variation_id">
                                    <input type="text" name="search_product" class="form-control" id="search_product"
                                        placeholder="{{ __('lang_v1.search_product_placeholder') }}" autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="customer_id">{{ __('contact.customer') . ':' }}</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <select name="customer_id" id="customer_id" class="form-control select2" required>
                                        <option value="">{{ __('messages.please_select') }}</option>
                                        @foreach ($customers as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="location_id">{{ __('purchase.business_location') . ':' }}</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </span>
                                    <select name="location_id" id="location_id" class="form-control select2" required>
                                        <option value="">{{ __('messages.please_select') }}</option>
                                        @foreach ($business_locations as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="store_id">@lang('store.store'):</label>
                                <select name="store_id" id="store_id" class="form-control select2" style="width:100%;">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="product_sr_date_filter">{{ __('report.date_range') . ':' }}</label>
                                <input type="text" name="date_range" class="form-control" id="product_sr_date_filter"
                                    placeholder="{{ __('lang_v1.select_a_date_range') }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="product_sr_start_time">{{ __('lang_v1.time_range') . ':' }}</label>
                            @php
                                $startDay = Carbon::now()->startOfDay();
                                $endDay = $startDay->copy()->endOfDay();
                            @endphp
                            <div class="form-group">
                                <input type="text" name="start_time" class="form-control width-50 f-left"
                                    id="product_sr_start_time" value="{{ @format_time($startDay) }}"
                                    style="{{ __('lang_v1.select_a_date_range') }}">
                                <input type="text" name="end_time" class="form-control width-50 f-left"
                                    id="product_sr_end_time" value="{{ @format_time($endDay) }}">
                            </div>
                        </div>
                    </form>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#psr_detailed_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"
                                    aria-hidden="true"></i> @lang('lang_v1.detailed')</a>
                        </li>
                        <li>
                            <a href="#psr_detailed_with_purchase_tab" data-toggle="tab" aria-expanded="true"><i
                                    class="fa fa-list" aria-hidden="true"></i> @lang('lang_v1.detailed_with_purchase')</a>
                        </li>
                        <li>
                            <a href="#psr_grouped_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-bars"
                                    aria-hidden="true"></i> @lang('lang_v1.grouped')</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="psr_detailed_tab">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="product_sell_report_table">
                                    <thead>
                                        <tr>
                                            <th>@lang('lang_v1.returned')</th>
                                            <th>@lang('sale.product')</th>
                                            <th>@lang('product.sku')</th>
                                            <th>@lang('sale.customer_name')</th>
                                            <th>@lang('lang_v1.contact_id')</th>
                                            <th>@lang('sale.invoice_no')</th>
                                            <th>@lang('messages.date')</th>
                                            <th>@lang('sale.qty')</th>
                                            <th>@lang('sale.unit_price')</th>
                                            <th>@lang('sale.discount')</th>
                                            <th>@lang('sale.tax')</th>
                                            <th>@lang('sale.price_inc_tax')</th>
                                            <th>@lang('sale.total')</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr class="bg-gray font-17 footer-total text-center">
                                            <td colspan="6"><strong>@lang('sale.total'):</strong></td>
                                            <td id="footer_total_sold"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="footer_tax"></td>
                                            <td></td>
                                            <td><span class="display_currency" id="footer_subtotal"
                                                    data-currency_symbol ="true"></span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="psr_detailed_with_purchase_tab">
                            <div class="table-responsive">
                                @if (session('business.enable_lot_number'))
                                    <input type="hidden" id="lot_enabled">
                                @endif
                                <table class="table table-bordered table-striped"
                                    id="product_sell_report_with_purchase_table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>@lang('sale.product')</th>
                                            <th>@lang('product.sku')</th>
                                            <th>@lang('sale.customer_name')</th>
                                            <th>@lang('sale.invoice_no')</th>
                                            <th>@lang('messages.date')</th>
                                            <th>@lang('lang_v1.purchase_ref_no')</th>
                                            <th>@lang('lang_v1.lot_number')</th>
                                            <th>@lang('lang_v1.supplier_name')</th>
                                            <th>@lang('sale.qty')</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane" id="psr_grouped_tab">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="product_sell_grouped_report_table"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>@lang('sale.product')</th>
                                            <th>@lang('product.sku')</th>
                                            <th>@lang('messages.date')</th>
                                            <th>@lang('report.current_stock')</th>
                                            <th>@lang('report.total_unit_sold')</th>
                                            <th>@lang('sale.total')</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr class="bg-gray font-17 footer-total text-center">
                                            <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                                            <td id="footer_total_grouped_sold"></td>
                                            <td><span class="display_currency" id="footer_grouped_subtotal"
                                                    data-currency_symbol ="true"></span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    <div class="modal fade view_register" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>

@endsection
