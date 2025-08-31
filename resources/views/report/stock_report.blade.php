@extends('layouts.app')
@section('title', __('report.stock_report'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ __('report.stock_report') }}</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <form method="GET" action="{{ action('ReportController@getStockReport') }}" id="stock_report_filter_form">
                        @csrf
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="location_id">@lang('purchase.business_location'):</label>
                                <select name="location_id" id="location_id" class="form-control select2" style="width:100%;">
                                    @foreach ($business_locations as $key => $location)
                                        <option value="{{ $key }}">{{ $location }}</option>
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
                                <label for="category_id">@lang('category.category'):</label>
                                <select name="category_id" id="category_id" class="form-control select2" style="width:100%;">
                                    <option value="">@lang('messages.all')</option>
                                    @foreach ($categories as $key => $category)
                                        <option value="{{ $key }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sub_category_id">@lang('product.sub_category'):</label>
                                <select name="sub_category_id" id="sub_category_id" class="form-control select2"
                                    style="width:100%;">
                                    <option value="">@lang('messages.all')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="brand">@lang('product.brand'):</label>
                                <select name="brand" id="brand" class="form-control select2" style="width:100%;">
                                    <option value="">@lang('messages.all')</option>
                                    @foreach ($brands as $key => $brand)
                                        <option value="{{ $key }}">{{ $brand }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="unit">@lang('product.unit'):</label>
                                <select name="unit" id="unit" class="form-control select2" style="width:100%;">
                                    <option value="">@lang('messages.all')</option>
                                    @foreach ($units as $key => $unit)
                                        <option value="{{ $key }}">{{ $unit }}</option>
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
                                                value="1">
                                            @lang('manufacturing::lang.only_mfg_products')
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
                    <table class="table no-border">
                        <tr>
                            <td>@lang('report.closing_stock') (@lang('lang_v1.by_purchase_price'))</td>
                            <td>@lang('report.closing_stock') (@lang('lang_v1.by_sale_price'))</td>
                            <td>@lang('lang_v1.potential_profit')</td>
                            <td>@lang('lang_v1.profit_margin')</td>
                        </tr>
                        <tr>
                            <td>
                                <h3 id="closing_stock_by_pp" class="mb-0 mt-0"></h3>
                            </td>
                            <td>
                                <h3 id="closing_stock_by_sp" class="mb-0 mt-0"></h3>
                            </td>
                            <td>
                                <h3 id="potential_profit" class="mb-0 mt-0"></h3>
                            </td>
                            <td>
                                <h3 id="profit_margin" class="mb-0 mt-0"></h3>
                            </td>
                        </tr>
                    </table>
                @endcomponent
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('report.partials.stock_report_table')
            </div>
        </div>
    </section>

    <!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>

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
