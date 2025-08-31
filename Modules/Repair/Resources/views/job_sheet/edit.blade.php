@extends('layouts.app')

@section('title', __('repair::lang.edit_job_sheet'))

@section('content')
@include('repair::layouts.nav')
<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>
    	@lang('repair::lang.job_sheet')
        (<code>{{$job_sheet->job_sheet_no}}</code>)
    </h1>
</section>
<section class="content">
    @if(!empty($repair_settings))
        @php
            $product_conf = isset($repair_settings['product_configuration']) ? explode(',', $repair_settings['product_configuration']) : [];
            $defects = isset($repair_settings['problem_reported_by_customer']) ? explode(',', $repair_settings['problem_reported_by_customer']) : [];
            $product_cond = isset($repair_settings['product_condition']) ? explode(',', $repair_settings['product_condition']) : [];
        @endphp
    @else
        @php
            $product_conf = [];
            $defects = [];
            $product_cond = [];
        @endphp
    @endif
    <form action="{{ action('\\Modules\\Repair\\Http\\Controllers\\JobSheetController@update', [$job_sheet->id]) }}" method="POST" id="edit_job_sheet_form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @includeIf('repair::job_sheet.partials.scurity_modal')
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" id="job_sheet_id" value="{{$job_sheet->id}}">
                        <div class="form-group">
                            <label for="customer_id">{{ __('role.customer') . ':*' }}</label>
                            <div class="input-group">
                                <input type="hidden" id="default_customer_id" value="{{ $job_sheet->customer->id }}" >
                                <input type="hidden" id="default_customer_name" value="{{ $job_sheet->customer->name }}" >
                                <input type="hidden" id="default_customer_balance" value="{{$job_sheet->customer->balance}}" >
                                <select name="contact_id" class="form-control mousetrap" id="customer_id" required style="width: 100%;" placeholder="Enter Customer name / phone">
                                    <option value="">Enter Customer name / phone</option>
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat add_new_customer" data-name=""  @if(!auth()->user()->can('customer.create')) disabled @endif><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label style="margin-left:20px;">{{ __('repair::lang.service_type') . ':*' }}</label>
                        <br>
                        <label class="radio-inline">
                            <input type="radio" name="service_type" value="carry_in" class="input-icheck" required @if($job_sheet->service_type == 'carry_in') checked @endif>
                            @lang('repair::lang.carry_in')
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="service_type" value="pick_up" class="input-icheck" @if($job_sheet->service_type == 'pick_up') checked @endif>
                            @lang('repair::lang.pick_up')
                        </label>
                        <label class="radio-inline radio_btns">
                            <input type="radio" name="service_type" value="on_site" class="input-icheck" @if($job_sheet->service_type == 'on_site') checked @endif>
                            @lang('repair::lang.on_site')
                        </label>
                    </div>
                </div>
                @if($job_sheet->service_type == 'pick_up' || $job_sheet->service_type == 'on_site')
                    @php $avail_addr = true; @endphp
                @else
                    @php $avail_addr = false; @endphp
                @endif
                <div class="row pick_up_onsite_addr" @if(!$avail_addr) style="display: none;" @endif>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pick_up_on_site_addr">{{ __('repair::lang.pick_up_on_site_addr') . ':' }}</label>
                            <textarea name="pick_up_on_site_addr" class="form-control" id="pick_up_on_site_addr" placeholder="{{ __('repair::lang.pick_up_on_site_addr') }}" rows="3">{{ $job_sheet->pick_up_on_site_addr }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="brand_id">{{ __('product.brand') . ':' }}</label>
                            <select name="brand_id" id="brand_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($brands as $id => $brand)
                                    <option value="{{ $id }}" @if($job_sheet->brand_id == $id) selected @endif>{{ $brand }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="device_id">{{ __('repair::lang.device') . ':' }}</label>
                            <select name="device_id" id="device_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($devices as $id => $device)
                                    <option value="{{ $id }}" @if($job_sheet->device_id == $id) selected @endif>{{ $device }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="device_model_id">{{ __('repair::lang.device_model') . ':' }}</label>
                            <select name="device_model_id" id="device_model_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($device_models as $id => $model)
                                    <option value="{{ $id }}" @if($job_sheet->device_model_id == $id) selected @endif>{{ $model }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h5 class="box-title">
                                    @lang('repair::lang.pre_repair_checklist'):
                                    @show_tooltip(__('repair::lang.prechecklist_help_text'))
                                    <small>
                                        @lang('repair::lang.not_applicable_key') = @lang('repair::lang.not_applicable')
                                    </small>
                                </h5>
                            </div>
                            <div class="box-body">
                                <div class="append_checklists"></div>  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="serial_no">{{ __('repair::lang.serial_no') . ':*' }}</label>
                            <input type="text" name="serial_no" class="form-control" placeholder="{{ __('repair::lang.serial_no') }}" value="{{ $job_sheet->serial_no }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                           <label for="security_pwd">{{ __('repair::lang.repair_passcode') . ':' }}</label>
                            <div class="input-group">
                                <input type="text" name="security_pwd" class="form-control" placeholder="{{ __('lang_v1.password') }}" value="{{ $job_sheet->security_pwd }}">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#security_pattern">
                                        <i class="fas fa-lock"></i>
                                        @lang('repair::lang.pattern_lock')
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="product_configuration">{{ __('repair::lang.product_configuration') . ':' }}</label> <br>
                            <textarea name="product_configuration" id="product_configuration" class="tags-look" rows="4">{{ $job_sheet->product_configuration }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="defects">{{ __('repair::lang.problem_reported_by_customer') . ':' }}</label> <br>
                            <textarea name="defects" id="defects" class="tags-look" rows="4">{{ $job_sheet->defects }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="product_condition">{{ __('repair::lang.condition_of_product') . ':' }}</label> <br>
                            <textarea name="product_condition" id="product_condition" class="tags-look" rows="4">{{ $job_sheet->product_condition }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    @if(in_array('service_staff' ,$enabled_modules))
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="service_staff">{{ __('repair::lang.assign_service_staff'