@extends('layouts.app')
@section('title', __('report.stock_expiry_report'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ __('report.stock_expiry_report') }}</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <form id="stock_report_filter_form" method="get"
                        action="{{ action('ReportController@getStockExpiryReport') }}">
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
                                    <option value="">{{ __('messages.all') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="category_id">{{ __('product.category') . ':' }}</label>
                                <select name="category" id="category_id" class="form-control select2" style="width:100%">
                                    <option value="">{{ __('lang_v1.all') }}</option>
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
                                    <option value="">{{ __('lang_v1.all') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="brand">{{ __('product.brand') . ':' }}</label>
                                <select name="brand" id="brand" class="form-control select2" style="width:100%">
                                    <option value="">{{ __('lang_v1.all') }}</option>
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
                                    <option value="">{{ __('lang_v1.all') }}</option>
                                    @foreach ($units as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="view_stock_filter">{{ __('report.view_stocks') . ':' }}</label>
                                <select name="view_stock_filter" id="view_stock_filter" class="form-control select2"
                                    style="width:100%">
                                    <option value="">{{ __('messages.all') }}</option>
                                    @foreach ($view_stock_filter as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if (Module::has('Manufacturing'))
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
                @component('components.widget', ['class' => 'box-primary'])
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="stock_expiry_report_table">
                            <thead>
                                <tr>
                                    <th>@lang('business.product')</th>
                                    <th>SKU</th>
                                    <!-- <th>@lang('purchase.ref_no')</th> -->
                                    <th>@lang('business.location')</th>
                                    <th>@lang('store.name')</th>
                                    <th>@lang('report.stock_left')</th>
                                    {{-- <th>@lang('lang_v1.lot_number')</th> --}}
                                    <th>@lang('product.exp_date')</th>
                                    <th>@lang('product.mfg_date')</th>
                                    <!--  <th>@lang('messages.edit')</th> -->
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="bg-gray font-17 text-center footer-total">
                                    <td colspan="3"><strong>@lang('sale.total'):</strong></td>
                                    <td id="footer_total_stock_left"></td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endcomponent
            </div>
        </div>
    </section>
    <!-- /.content -->

    <div class="modal fade exp_update_modal" tabindex="-1" role="dialog">
    </div>
@endsection

@section('javascript')
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
                        // console.log(key);
                        console.log(value['name']);
                        options += '<option value="' + value['id'] + '">' +
                            value['name'] + '</option>';
                    });
                    $('#store_id').html(options).trigger('change');
                }
            });
        });
    </script>
@endsection
