@extends('product_gallery.layouts.app')
@section('title')

    <style>
        .mheader {
            width: 100%;
            /* height: 300px;*/
            background-image: url("/uploads/business_header/slider_1.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
    </style>

@section('content')
    <!-- Main content -->
    <div class="mheader">

    </div>
    <section class="content">
        <form action="{{ action('\Modules\Manufacturing\Http\Controllers\ProductionController@store') }}" method="POST"
            id="production_form" enctype="multipart/form-data">
            @csrf

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        @component('components.filters', ['title' => __('report.filters')])
                            <div class="col-md-3" id="location_filter">
                                <div class="form-group">
                                    <label for="location_id">@lang('purchase.business_location'):</label>
                                    <select name="location_id" id="location_id" class="form-control select2"
                                        style="width:100%;">
                                        <option value="">@lang('messages.please_select')</option>
                                        @foreach ($business_locations as $key => $location)
                                            <option value="{{ $key }}">{{ $location }}</option>
                                        @endforeach
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
                                        <option value="">@lang('messages.please_select')</option>
                                        @foreach ($categories as $key => $category)
                                            <option value="{{ $key }}">{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="brand_id">@lang('product.brand'):</label>
                                    <select name="brand_id" id="product_list_filter_brand_id" class="form-control select2"
                                        style="width:100%;" placeholder="@lang('lang_v1.all')">
                                        <option value="">@lang('messages.please_select')</option>
                                        @foreach ($brands as $key => $brand)
                                            <option value="{{ $key }}">{{ $brand }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="productname">@lang('lang_v1.search')</label>
                                    <div style="height: 37px">
                                        <input type="text" name="productname" id="productname" class="form-control"
                                            style="width: 80%; float: left; height: 100%;">
                                        <button type="button" class="btn-search" style="height:100%"><i
                                                class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endcomponent
                    </div>
                </div>
            </div>

            <input type="hidden" id="rem" name="rem" value="true">
            <input type="hidden" id="offset" name="offset" value="0">

            <div class="main-product container" id="products"></div>

            <div class="loader hidden" id="loader"></div>

            <div style="justify-content: center; display: flex;">
                <button type="submit" class="btn btn-success" style="background-color:#26252b;"
                    id="morebtn">المزيد</button>
            </div>
        </form>
    </section>

    <!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>

    {{--  <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            getproducts();

            $(document).on('change',
                '#product_list_filter_image,#product_list_filter_current_stock,#product_list_filter_current_stock,#product_list_filter_type, #product_list_filter_category_id, #product_list_filter_brand_id, #product_list_filter_unit_id, #product_list_filter_tax_id, #location_id, #active_state, #repair_model_id',
                function() {
                    $('#offset').val(0);
                    $('#morebtn').html('المزيد');
                    var products = document.getElementById("products");
                    products.innerHTML = '';
                    getproducts();

                });


        });

        $(document).on('keyup', '#productname', function(e) {
            if (e.keyCode == 32)
                return;
            $('#offset').val(0);
            $('#rem').val('true')
            $('#morebtn').html('المزيد');
            var products = document.getElementById("products");
            products.innerHTML = '';
            getproducts();

        });

        function getproducts() {
            var offset = $('#offset').val() * 1;
            var rem = $('#rem').val();
            if (rem === 'false') {
                $('#morebtn').html('finshed');
                return;
            }

            $('#loader').removeClass('hidden');
            $('#offset').val(offset + 12);
            $.ajax({
                url: "/product/slug",
                type: 'GET',
                data: {
                    type: $('#product_list_filter_type').val(),
                    category_id: $('#product_list_filter_category_id').val(),
                    brand_id: $('#product_list_filter_brand_id').val(),
                    unit_id: $('#product_list_filter_unit_id').val(),
                    location_id: $('#location_id').val(),
                    offset: offset,
                    productname: $('#productname').val()
                },
                success: function(data) {
                    var products = document.getElementById("products");
                    products.innerHTML += data['product'];
                    if (data['count'] < 12) {
                        $('#morebtn').html('finshed');
                        $('#rem').val('false');
                    }


                }

            });
            $('#loader').addClass('hidden');

        }
    </script>

@endsection
