@extends('layouts.app')
@section('title', __('barcode.add_barcode_setting'))

@section('content')
    <style type="text/css">



    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('barcode.add_barcode_setting')</h1>
        <!-- <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        <form action="{{ action('BarcodeController@store') }}" method="post" id="add_barcode_settings_form">


            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">@lang('barcode.setting_name'):</label>
                                <input type="text" name="name" id="name" class="form-control" required
                                    placeholder="@lang('barcode.setting_name')">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="description">@lang('barcode.setting_description'):</label>
                                <textarea name="description" id="description" class="form-control" placeholder="@lang('barcode.setting_description')" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_continuous" id="is_continuous" value="1">
                                        @lang('barcode.is_continuous')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="top_margin">@lang('barcode.top_margin') (@lang('barcode.in_in')):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
                                    </span>
                                    <input type="number" name="top_margin" id="top_margin" class="form-control"
                                        placeholder="@lang('barcode.top_margin')" min="0" step="0.00001" required
                                        value="0">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="left_margin">@lang('barcode.left_margin') (@lang('barcode.in_in')):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                                    </span>
                                    <input type="number" name="left_margin" id="left_margin" class="form-control"
                                        placeholder="@lang('barcode.left_margin')" min="0" step="0.00001" required
                                        value="0">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="width">@lang('barcode.width') (@lang('barcode.in_in')):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-text-width" aria-hidden="true"></i>
                                    </span>
                                    <input type="number" name="width" id="width" class="form-control"
                                        placeholder="@lang('barcode.width')" min="0.1" step="0.00001" required>
                                </div>
                            </div>
                        </div>





                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="height">@lang('barcode.height') (@lang('barcode.in_in')):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-text-height" aria-hidden="true"></i>
                                    </span>
                                    <input type="number" name="height" id="height" class="form-control"
                                        placeholder="@lang('barcode.height')" min="0.1" step="0.00001" required>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="paper_width">@lang('barcode.paper_width') (@lang('barcode.in_in')):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-text-width" aria-hidden="true"></i>
                                    </span>
                                    <input type="number" name="paper_width" id="paper_width" class="form-control"
                                        placeholder="@lang('barcode.paper_width')" min="0.1" step="0.00001" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 paper_height_div">
                            <div class="form-group">
                                <label for="paper_height">@lang('barcode.paper_height') (@lang('barcode.in_in')):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-text-height" aria-hidden="true"></i>
                                    </span>
                                    <input type="number" name="paper_height" id="paper_height" class="form-control"
                                        placeholder="@lang('barcode.paper_height')" min="0.1" step="0.00001" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="stickers_in_one_row">@lang('barcode.stickers_in_one_row'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </span>
                                    <input type="number" name="stickers_in_one_row" id="stickers_in_one_row"
                                        class="form-control" placeholder="@lang('barcode.stickers_in_one_row')" min="1" required>
                                </div>
                            </div>
                        </div>




                        <div class="clearfix"></div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="row_distance">@lang('barcode.row_distance') (@lang('barcode.in_in')):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
                                    </span>
                                    <input type="number" name="row_distance" id="row_distance" class="form-control"
                                        placeholder="@lang('barcode.row_distance')" min="0" step="0.00001" required
                                        value="0">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="col_distance">@lang('barcode.col_distance') (@lang('barcode.in_in')):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true"></span>
                                    </span>
                                    <input type="number" name="col_distance" id="col_distance" class="form-control"
                                        placeholder="@lang('barcode.col_distance')" min="0" step="0.00001" required
                                        value="0">
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-sm-6 stickers_per_sheet_div">
                            <div class="form-group">
                                <label for="stickers_in_one_sheet">@lang('barcode.stickers_in_one_sheet'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-th" aria-hidden="true"></i>
                                    </span>
                                    <input type="number" name="stickers_in_one_sheet" id="stickers_in_one_sheet"
                                        class="form-control" placeholder="@lang('barcode.stickers_in_one_sheet')" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_default" value="1"> @lang('barcode.set_as_default')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
@endsection
