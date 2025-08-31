@extends('layouts.app')
@section('title', __('assets.add_asset'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('assets.add_asset')</h1>
        <!-- <ol class="breadcrumb">
                                                                                    <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                                                                    <li class="active">Here</li>
                                                                                </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @php
            $form_class = empty($duplicate_product) ? 'create' : '';
        @endphp
<form action="{{ action('AcAssetController@store') }}" method="post" id="asset_class_add_form" class="asset_class_form{{ $form_class }}" enctype="multipart/form-data">
    @csrf
    <!-- Your form fields go here -->


        @component('components.widget', ['class' => 'box-primary'])
            <div class="row">
                <div class="clearfix"></div>
 <div class="col-md-6">
    <div class="form-group">
        <label for="asset_name_ar">{{ __('assets.asset_name_ar') }}:*</label>
        <input type="text" id="asset_name_ar" name="asset_name_ar" class="form-control" required>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="asset_name_en">{{ __('assets.asset_name_en') }}</label>
        <input type="text" id="asset_name_en" name="asset_name_en" class="form-control">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="asset_description">{{ __('assets.asset_description') }}</label>
        <textarea id="asset_description" name="asset_description" class="form-control" rows="3"></textarea>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="barcode">{{ __('lang_v1.barcode') }}</label>
        <div class="input-group" style="display: flex">
            <input type="text" id="barcode" name="barcode" class="form-control">
            <button id="barcode_btn" class="btn" type="button">
                <i class="fas fa-money-bill"></i>
            </button>
        </div>
        <svg id="barcode"></svg>
    </div>
</div>

<div class="clearfix"></div>

<div class="col-md-6">
    <div class="form-group">
        <label for="asset_classes_id">{{ __('assets.asset_classes') }}</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select id="asset_classes_id" name="asset_classes_id" class="form-control select2" required>
                <option value="">{{ __('Select Acc') }}</option>
                @foreach($asset_classes as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>



                <div class="clearfix"></div>

                <div id="asset_classes_div">


                </div>


            </div>
        @endcomponent

        <div class="row">
            <div class="col-sm-12">
                <input type="hidden" name="submit_type" id="submit_type">
                <div class="text-center">
                    <div class="btn-group">
                        <button type="submit" value="submit"
                                class="btn btn-primary submit_product_form">@lang('messages.save')</button>
                    </div>

                </div>
            </div>
        </div>


       </form>

    </section>
    <!-- /.content -->

@endsection

@section('javascript')

    <script src='https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/barcodes/JsBarcode.code128.min.js'></script>
    <script type="text/javascript">

        $(document).ready(function() {
            $('#barcode_btn').on('click', function() {
                JsBarcode('#barcode', '12345', {
                    format: 'CODE128',
                    width: 4,
                    height: 156
                });
            });

            $('#depreciable_div').hide();
            $('#percent_lable_div').hide();
            $(document).on('change', '#is_depreciable', function() {
                // var is_depreciable = $(this).val();
                if (this.checked) {
                    $('#depreciable_div').show();
                } else {
                    $('#depreciable_div').hide();
                }
            });
            $(document).on('change', '#useful_life_type', function() {
                var useful_life_type = $(this).val();
                if (useful_life_type == 'years') {
                    $('#years_lable_div').show();
                    $('#percent_lable_div').hide();
                } else {
                    $('#percent_lable_div').show();
                    $('#years_lable_div').hide();
                }
            });

            $(document).on('change', '#asset_classes_id', function() {
                var asset_classes_id = $(this).val();
                if (asset_classes_id != 'NULL') {
                    $('#asset_classes_div').html('');
                    $.ajax({
                        method: 'POST',
                        url: '/ac/get_asset_classes_details',
                        dataType: 'json',
                        data: {
                            asset_classes_id: asset_classes_id,
                        },
                        success: function(result) {
                            // console.log(result.msg);
                            // $("#cost_cen_list").html(result.html_content);
                            if (result.success) {
                                $('#asset_classes_div').html(result.html_content);
                                $('#asset_classes_div').show();
                                // toastr.success('Done');
                            }
                        },
                    });

                } else {
                    // $('#cost_cen_list_div').hide();
                    // toastr.success('null');
                }
            });


        });
    </script>
@endsection
