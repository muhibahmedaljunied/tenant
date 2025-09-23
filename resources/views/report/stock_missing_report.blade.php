@extends('layouts.app')
@section('title', __('.تقرير النواقص'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ __('تقرير النواقص') }}</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <form id="stock_report_filter_form" method="get" action="{{ action('ReportController@getStockReport') }}">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="location_id">{{ __('purchase.business_location') . ':' }}</label>
                                <select name="location_id" id="location_id" class="form-control select2" style="width:100%">
                                    @foreach ($business_locations as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
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
                                <label for="category_id">{{ __('category.category') . ':' }}</label>
                                <select name="category" id="category_id" class="form-control select2" style="width:100%">
                                    <option value="">{{ __('messages.all') }}</option>
                                    @foreach ($categories as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sub_category_id">{{ __('product.sub_category') . ':' }}</label>
                                <select name="sub_category" id="sub_category_id" class="form-control select2"
                                    style="width:100%">
                                    <option value="">{{ __('messages.all') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="brand">{{ __('product.brand') . ':' }}</label>
                                <select name="brand" id="brand" class="form-control select2" style="width:100%">
                                    <option value="">{{ __('messages.all') }}</option>
                                    @foreach ($brands as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="unit">{{ __('product.unit') . ':' }}</label>
                                <select name="unit" id="unit" class="form-control select2" style="width:100%">
                                    <option value="">{{ __('messages.all') }}</option>
                                    @foreach ($units as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if ($show_manufacturing_data)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <br>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="only_mfg" id="only_mfg_products" class="input-icheck"
                                                value="1"> {{ __('manufacturing::lang.only_mfg_products') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                @endcomponent
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-solid'])
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="stock_report_table_missing">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>@lang('business.product')</th>
                                    <th>@lang('sale.location')</th>
                                    <th>سعر التكلفة</th>
                                    <th>@lang('report.current_stock')</th>
                                    <th>تنبيه الكمية</th>
                                    <th>الكمية الناقصة</th>

                                </tr>
                            </thead>

                        </table>
                    </div>
                @endcomponent
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection
@section('javascript')
    <script>
        $('#stock_report_filter_form #location_id,#stock_report_filter_form ,#location_id2 ,#brand_id ,#category_id ,#product_list_filter_brand_id,#stock_report_filter_form #category_id, #stock_report_filter_form #sub_category_id, #stock_report_filter_form #brand, #stock_report_filter_form #unit,#stock_report_filter_form #view_stock_filter')
            .change(function() {
                stock_missing_report_table.ajax.reload();

            });
        $(document).ready(function() {
            //Stock report table
            var stock_report_cols = [{
                    data: 'sku',
                    name: 'variations.sub_sku'
                },
                {
                    data: 'product',
                    name: 'p.name'
                },
                {
                    data: 'location_name',
                    name: 'l.name'
                },
                {
                    data: 'default_purchase_price',
                    name: 'variations.default_purchase_price'
                },
                {
                    data: 'stock',
                    name: 'stock',
                    searchable: false
                },
                {
                    data: 'alert_qty',
                    name: 'alert_qty',
                    searchable: true
                },
                {
                    data: 'missing_qty',
                    name: 'missing_qty',
                    searchable: true
                },

            ];



            if ($('th.current_stock_mfg').length) {
                stock_report_cols.push({
                    data: 'total_mfg_stock',
                    name: 'total_mfg_stock',
                    searchable: false
                });
            }
            stock_missing_report_table = $('#stock_report_table_missing').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/reports/stock-missing-report',
                    data: function(d) {
                        d.location_id = $('#location_id').val();
                        d.category_id = $('#category_id').val();
                        d.sub_category_id = $('#sub_category_id').val();
                        d.brand_id = $('#brand').val();
                        d.unit_id = $('#unit').val();

                        d.only_mfg_products = $('#only_mfg_products').length && $('#only_mfg_products')
                            .is(':checked') ? 1 : 0;
                    },
                },
                columns: stock_report_cols,
                fnDrawCallback: function(oSettings) {
                    $('#footer_total_stock').html(__sum_stock($('#stock_report_table'),
                        'current_stock'));
                    $('#footer_total_sold').html(__sum_stock($('#stock_report_table'), 'total_sold'));
                    $('#footer_total_transfered').html(
                        __sum_stock($('#stock_report_table'), 'total_transfered')
                    );
                    $('#footer_total_adjusted').html(
                        __sum_stock($('#stock_report_table'), 'total_adjusted')
                    );
                    var total_stock_price = sum_table_col($('#stock_report_table'),
                    'total_stock_price');
                    $('#footer_total_stock_price').text(total_stock_price);

                    var total_stock_value_by_sale_price = sum_table_col($('#stock_report_table'),
                        'stock_value_by_sale_price');
                    $('#footer_stock_value_by_sale_price').text(total_stock_value_by_sale_price);

                    var total_potential_profit = sum_table_col($('#stock_report_table'),
                        'potential_profit');
                    $('#footer_potential_profit').text(total_potential_profit);

                    __currency_convert_recursively($('#stock_report_table'));
                    if ($('th.current_stock_mfg').length) {
                        $('#footer_total_mfg_stock').html(
                            __sum_stock($('#stock_report_table'), 'total_mfg_stock')
                        );
                    }
                },
            });
        });
    </script>
    <script src="{{ url('js/report.js?v=' . $asset_v) }}"></script>
    
    <script>
        $('#location_id').on('change', function() {

            console.log('hebo_change');
            var locationIds = $(this).val();
            if (!locationIds) {
                $('#store_id').html(
                    '<option value="">{{ __('messages.please_select') }}</option>');
                $('#store_id').trigger('change');
                return;
            }
            $.ajax({
                url: '{{ route('getStoresByLocationsStockReport') }}',
                type: 'POST',
                data: {
                    location_ids: locationIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    var options =
                        '<option value="">{{ __('messages.please_select') }}</option>';
                    $.each(data, function(key, value) {
                        options += '<option value="' + key + '">' +
                            value + '</option>';
                    });
                    $('#store_id').html(options).trigger('change');
                }
            });
        });
    </script>
@endsection
