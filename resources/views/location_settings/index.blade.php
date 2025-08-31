@extends('layouts.app')
@section('title', __('messages.business_location_settings'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'messages.business_location_settings' ) - {{$location->name}}</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">@lang('receipt.receipt_settings')</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>@lang( 'receipt.receipt_settings')
                                    <small>@lang( 'receipt.receipt_settings_mgs')</small>
                                </h4>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ route('location.settings_update', [$location->id]) }}" method="POST" id="bl_receipt_setting_form">
                                    @csrf



                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="print_receipt_on_invoice">{{ __('receipt.print_receipt_on_invoice') }}:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-file-alt"></i>
                                                </span>
                                                <select name="print_receipt_on_invoice" class="form-control select2" required>
                                                    @foreach($printReceiptOnInvoice as $key => $label)
                                                    <option value="{{ $key }}" {{ $location->print_receipt_on_invoice == $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="receipt_printer_type">{{ __('receipt.receipt_printer_type') }}:*</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-print"></i>
                                                </span>
                                                <select name="receipt_printer_type" class="form-control select2" required>
                                                    @foreach($receiptPrinterType as $key => $label)
                                                    <option value="{{ $key }}" {{ $location->receipt_printer_type == $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if(config('app.env') == 'demo')
                                            <span class="help-block">Only Browser based option is enabled in demo.</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4" id="location_printer_div">
                                        <div class="form-group">
                                            <label for="printer_id">{{ __('printer.receipt_printers') }}:*</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-share-alt"></i>
                                                </span>
                                                <select name="printer_id" class="form-control select2" required>
                                                    @foreach($printers as $key => $name)
                                                    <option value="{{ $key }}" {{ $location->printer_id == $key ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <br />

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="invoice_layout_id">{{ __('invoice.invoice_layout') }}:*</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-info"></i>
                                                </span>
                                                <select name="invoice_layout_id" class="form-control select2" required>
                                                    @foreach($invoice_layouts as $key => $name)
                                                    <option value="{{ $key }}" {{ $location->invoice_layout_id == $key ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="invoice_scheme_id">{{ __('invoice.invoice_scheme') }}:*</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-info"></i>
                                                </span>
                                                <select name="invoice_scheme_id" class="form-control select2" required>
                                                    @foreach($invoice_schemes as $key => $name)
                                                    <option value="{{ $key }}" {{ $location->invoice_scheme_id == $key ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary pull-right" type="submit">@lang('messages.update')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
        </div>
    </div>

    <div class="modal fade invoice_modal" tabindex="-1" role="dialog"
        aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade invoice_edit_modal" tabindex="-1" role="dialog"
        aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection