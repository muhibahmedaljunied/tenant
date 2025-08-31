@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@section('title',  __('payment.add_payment'))

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('payment.add_payment')</h1>
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol> -->
    </section>

    <!-- Main content -->
<section class="content">
    <form action="{{ action('PaymentController@store', ['type' => $type]) }}" method="POST" id="add_barcode_settings_form">
        @csrf
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">@lang('payment.reference_number'):</label>
                            <input type="text" name="name" id="name" class="form-control"
                                required placeholder="@lang('barcode.setting_name')" value="{{ $ref_num }}">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="contact">@lang('payment.contact'):</label>
                            <select name="contact" id="contact" class="form-control select2" required>
                                <option value="" disabled selected>@lang('messages.please_select')</option>
                                @foreach($contacts as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="account">@lang('payment.account'):</label>
                            <select name="account" id="account" class="form-control select2" required>
                                <option value="" disabled selected>@lang('messages.please_select')</option>
                                @foreach($accounts as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="description">@lang('payment.description'):</label>
                            <input type="text" name="description" id="description" class="form-control"
                                required placeholder="@lang('payment.description')">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="type">@lang('payment.type'):</label>
                            <select name="type" id="type" class="form-control select2" required>
                                <option value="" disabled selected>@lang('messages.please_select')</option>
                                @foreach($types as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="branch_cost_center_id">@lang('payment.branch_cost_centers'):</label>
                            <select name="branch_cost_center_id" id="branch_cost_center_id" class="form-control select2">
                                <option value="" disabled selected>@lang('messages.please_select')</option>
                                @foreach($branch_cost_centers as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="extra_cost_center_id">@lang('payment.extra_cost_center'):</label>
                            <select name="extra_cost_center_id" id="extra_cost_center_id" class="form-control select2">
                                <option value="" disabled selected>@lang('messages.please_select')</option>
                                @foreach($extra_cost_centers as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="checkbox" name="debtAges" id="debtAges" class="checkbox-inline" checked>
                            <label for="debtAges">@lang('debtages.input_check')</label>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="date">@lang('payment.date'):</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" name="date" id="date" class="form-control"
                                    required readonly value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="amount">@lang('payment.amount'):</label>
                            <input type="number" name="amount" id="amount" class="form-control"
                                required step="any" min="0">
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
@section('javascript')
    <script>
        $(document).ready(function() {
            $('#date').datepicker({
                dateFormat: "yy-mm-dd"
            });
        })
    </script>
@endsection
