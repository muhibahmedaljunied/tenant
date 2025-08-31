@extends('layouts.app')
@section('title', "مقارنة المخازن")

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>مقارنة الفروع</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
              <form id="stock_report_filter_form" method="get" action="{{ action('ReportController@compare_locations') }}">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="location_id">{{ __('purchase.business_location') . ':' }}</label>
                        <select name="location_id" id="location_id" class="form-control select2" style="width:100%">
                            @foreach($business_locations as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="location_id2">{{ __('purchase.business_location') . '2:' }}</label>
                        <select name="location_id2" id="location_id2" class="form-control select2" style="width:100%">
                            @foreach($business_locations as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                 <div class="col-md-3">
            <div class="form-group">
                <label for="category_id">{{ __('product.category') . ':' }}</label>
                <select name="category_id" id="category_id" class="form-control select2" style="width:100%">
                    <option value="">{{ __('lang_v1.all') }}</option>
                    @foreach($categories as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
             </div>
            <div class="col-md-3">
            <div class="form-group">
                <label for="brand_id">{{ __('product.brand') . ':' }}</label>
                <select name="brand_id" id="brand_id" class="form-control select2" style="width:100%">
                    <option value="">{{ __('lang_v1.all') }}</option>
                    @foreach($brands as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
              </form>
            @endcomponent
        </div>
    </div>
  
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
    <table class="table table-bordered table-striped" id="stock_report_table_compare">
        <thead>
            <tr>
                <th>action</th>
                <th>SKU</th>
                <th>@lang('business.product')</th>
              
                <th>@lang('sale.unit_price')</th>
                <th>@lang('purchase.business_location')</th>
                <th>@lang('purchase.business_location')2</th>
            </tr>
        </thead>
        <tfoot>
            <!--
            <tr class="bg-gray font-17 text-center footer-total">
                <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                <td id="footer_total_stock"></td>
                @can('view_product_stock_value')
                <td><span id="footer_total_stock_price" class="display_currency" data-currency_symbol="true"></span></td>
                <td><span id="footer_stock_value_by_sale_price" class="display_currency" data-currency_symbol="true"></span></td>
                <td><span id="footer_potential_profit" class="display_currency" data-currency_symbol="true"></span></td>
                @endcan
                <td id="footer_total_sold"></td>
                <td id="footer_total_transfered"></td>
                <td id="footer_total_adjusted"></td>
                @if($show_manufacturing_data)
                    <td id="footer_total_mfg_stock"></td>
                @endif
            </tr>
            -->
        </tfoot>
    </table>
</div>
        </div>
    </div>
</section>
<!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection