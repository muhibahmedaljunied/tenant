@extends('layouts.app')
@section('title', __('printer.add_printer'))

@section('content')
    <style type="text/css">



    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('printer.add_printer')</h1>
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
     
      <form action="{{ action('PrinterController@store') }}" method="post" id="add_printer_form">



        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">@lang('printer.name'):</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                placeholder="@lang('lang_v1.printer_name_help')">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="connection_type">@lang('printer.connection_type'):</label>
                            <select name="connection_type" id="connection_type" class="form-control select2">
                                @foreach ($connection_types as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="capability_profile">@lang('printer.capability_profile'):</label>
                            @show_tooltip(__('tooltip.capability_profile'))
                            <select name="capability_profile" id="capability_profile" class="form-control select2">
                                @foreach ($capability_profiles as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="char_per_line">@lang('printer.character_per_line'):</label>
                            <input type="number" name="char_per_line" id="char_per_line" class="form-control" required
                                placeholder="@lang('lang_v1.char_per_line_help')" value="42">
                        </div>
                    </div>

                    <div class="col-sm-12" id="ip_address_div">
                        <div class="form-group">
                            <label for="ip_address">@lang('printer.ip_address'):</label>
                            <input type="text" name="ip_address" id="ip_address" class="form-control" required
                                placeholder="@lang('lang_v1.ip_address_help')">
                        </div>
                    </div>

                    <div class="col-sm-12" id="port_div">
                        <div class="form-group">
                            <label for="port">@lang('printer.port'):</label>
                            <input type="text" name="port" id="port" class="form-control" required
                                value="9100">
                            <span class="help-block">@lang('lang_v1.port_help')</span>
                        </div>
                    </div>

                    <div class="col-sm-12 hide" id="path_div">
                        <div class="form-group">
                            <label for="path">@lang('printer.path'):</label>
                            <input type="text" name="path" id="path" class="form-control" required>
                            <span class="help-block">
                                <b>@lang('lang_v1.connection_type_windows'):</b> @lang('lang_v1.windows_type_help') <code>LPT1</code> (parallel) /
                                <code>COM1</code> (serial). <br />
                                <b>@lang('lang_v1.connection_type_linux'):</b> @lang('lang_v1.linux_type_help') <code>/dev/lp0</code> (parallel),
                                <code>/dev/usb/lp1</code> (USB), <code>/dev/ttyUSB0</code> (USB-Serial),
                                <code>/dev/ttyS0</code> (serial). <br />
                            </span>
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
