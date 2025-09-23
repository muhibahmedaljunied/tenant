@extends('layouts.app')
@section('title', __('sale.products'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('sale.products')
            <small>@lang('lang_v1.manage_products')</small>
        </h1>
        <!-- <ol class="breadcrumb">
                                    <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                    <li class="active">Here</li>
                                </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <div class="col-md-3" id="location_filter">
                        <div class="form-group">
                            <label for="location_id">@lang('purchase.business_location'):</label>
                            <select name="location_id" id="location_id" class="form-control select2" style="width:100%;"
                                placeholder="@lang('lang_v1.all')">
                                @foreach ($business_locations as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" id="store_filter">
                        <div class="form-group">
                            <label for="store_id">@lang('store.store'):</label>

                            <select name="store_id" id="store_id" class="form-control select2" style="width:100%;"
                                placeholder="@lang('lang_v1.all')">
                                <option value="">@lang('lang_v1.all')</option>

                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">@lang('product.product_type'):</label>
                            <select name="type" id="product_list_filter_type" class="form-control select2"
                                style="width:100%;" placeholder="@lang('lang_v1.all')">
                                <option value="single">@lang('lang_v1.single')</option>
                                <option value="variable">@lang('lang_v1.variable')</option>
                                <option value="combo">@lang('lang_v1.combo')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category_id">@lang('product.category'):</label>
                            <select name="category_id" id="product_list_filter_category_id" class="form-control select2"
                                style="width:100%;" placeholder="@lang('lang_v1.all')">
                                <option value="">@lang('lang_v1.all')</option>
                                @foreach ($categories as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" style="display: none;">
                        <div class="form-group">
                            <label for="unit_id">@lang('product.unit'):</label>
                            <select name="unit_id" id="product_list_filter_unit_id" class="form-control select2"
                                style="width:100%;" placeholder="@lang('lang_v1.all')">
                                <option value="">@lang('lang_v1.all')</option>
                                @foreach ($units as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" style="display: none;">
                        <div class="form-group">
                            <label for="tax_id">@lang('product.tax'):</label>
                            <select name="tax_id" id="product_list_filter_tax_id" class="form-control select2"
                                style="width:100%;" placeholder="@lang('lang_v1.all')">
                                <option value="">@lang('lang_v1.all')</option>
                                @foreach ($taxes as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="brand_id">@lang('product.brand'):</label>
                            <select name="brand_id" id="product_list_filter_brand_id" class="form-control select2"
                                style="width:100%;" placeholder="@lang('lang_v1.all')">
                                <option value="">@lang('lang_v1.all')</option>
                                @foreach ($brands as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{--  --}}
                    <div class="col-md-3" style="display: none;">
                        <br>
                        <div class="form-group">
                            <select name="active_state" id="active_state" class="form-control select2" style="width:100%;"
                                placeholder="@lang('lang_v1.all')">
                                <option value="active">@lang('business.is_active')</option>
                                <option value="inactive">@lang('lang_v1.inactive')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" style="display: none;">
                        <div class="form-group">
                            <label for="product_list_filter_image">@lang('lang_v1.image'):</label>
                            <select name="type" id="product_list_filter_image" class="form-control select2"
                                style="width:100%;" placeholder="@lang('lang_v1.all')">
                                <option value="default">بدون صورة</option>
                                <option value="image">@lang('lang_v1.image')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" style="display: none;">
                        <div class="form-group">
                            <label for="product_list_filter_current_stock">@lang('report.current_stock'):</label>
                            <select name="current_stock" id="product_list_filter_current_stock" class="form-control select2"
                                style="width:100%;" placeholder="@lang('lang_v1.all')">
                                <option value="">@lang('lang_v1.all')</option>
                                <option value="zero">Zero</option>
                                <option value="gtzero">اكبر من الصفر</option>
                                <option value="lszero">اقل من الصفر</option>
                            </select>
                        </div>
                    </div>

                    {{-- Include module filter --}}
                    {{-- @if (!empty($pos_module_data))
                        @foreach ($pos_module_data as $key => $value)
                            @if (!empty($value['view_path']))
                                @includeIf($value['view_path'], ['view_data' => $value['view_data']])
                            @endif
                        @endforeach
                    @endif --}}

                    <div class="col-md-3" style="display: none;">
                        <div class="form-group">
                            <br>
                            <label>
                                <input type="checkbox" name="not_for_selling" value="1" class="input-icheck"
                                    id="not_for_selling">
                                <strong>@lang('lang_v1.not_for_selling')</strong>
                            </label>
                        </div>
                    </div>

                    @if ($is_woocommerce)
                        <div class="col-md-3" style="display: none;">
                            <div class="form-group">
                                <br>
                                <label>
                                    <input type="checkbox" name="woocommerce_enabled" value="1" class="input-icheck"
                                        id="woocommerce_enabled">
                                    @lang('lang_v1.woocommerce_enabled')
                                </label>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>@lang('lang_v1.search')</label>
                            <div>
                                <input type="text" name="productname" id="productname" class="form-control"
                                    style="width: 80%;float: left; ">
                                <button type="button" class="btn-search"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>




                    <div class="col-md-12">
                        <div class="mt-15">
                            @can('product.create')
                                <a class="btn btn-primary  " href="{{ action('ProductController@create') }}">
                                    <i class="fa fa-plus"></i> @lang('product.add_new_product')</a>
                            @endcan
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>


        <input type="hidden" id="rem" name="rem" value="true">
        <input type="hidden" id="offset" name="offset" value="0">
        <div class="main-prduct container" id="products">


        </div>
        <div class="loader hidden" id="loader"></div>
        <div style="justify-content: center;display: flex;">
            <button class="btn btn-success" style="background-color:#26252b;" onclick="getproducts()"
                id="morebtn">المزيد</button>
        </div>

        <input type="hidden" id="is_rack_enabled" value="{{ $rack_enabled }}">

        <div class="modal fade product_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

        <div class="modal fade" id="view_product_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel">
        </div>

        <div class="modal fade" id="opening_stock_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel">
        </div>

        @include('product.partials.edit_product_location_modal')

    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ url('js/product.js?v=' . $asset_v) }}"></script>
    <script src="{{ url('js/opening_stock.js?v=' . $asset_v) }}"></script>
    <script src="{{ url('js/gallery.js?v=' . $asset_v) }}"></script>

    <script class="content">
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
                url: '{{ route('getStoresByLocations') }}',
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


    {{--  <script src="{{ url('js/report.js?v=' . $asset_v) }}"></script> --}}


@endsection
