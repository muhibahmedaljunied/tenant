@extends('layouts.app')

@section('title', __('ايصال ضمان'))

@section('content')
@include('repair::layouts.nav')
<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>
    	ايصال ضمان
        <small>@lang('repair::lang.create')</small>
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
    <form action="{{ action('\\Modules\\Repair\\Http\\Controllers\\GuaranteeController@store') }}" id="job_sheet_form" method="POST" enctype="multipart/form-data">
        @csrf
        @includeIf('repair::job_sheet.partials.scurity_modal')
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    @if(count($business_locations) == 1)
                        @php 
                            $default_location = current(array_keys($business_locations->toArray()));
                        @endphp
                    @else
                        @php $default_location = null;
                        @endphp
                    @endif
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="location_id">{{ __('business.business_location') . ':*' }}</label>
                            <select name="location_id" id="location_id" class="form-control" required style="width: 100%;">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($business_locations as $id => $location)
                                    <option value="{{ $id }}" @if($default_location == $id) selected @endif>{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="customer_id">{{ __('role.customer') . ':*' }}</label>
                            <div class="input-group">
                                <input type="hidden" id="default_customer_id" value="{{ $walk_in_customer['id'] ?? ''}}" >
                                <input type="hidden" id="default_customer_name" value="{{ $walk_in_customer['name'] ?? ''}}" >
                                <input type="hidden" id="default_customer_balance" value="{{ $walk_in_customer['balance'] ?? ''}}" >
                                <select name="contact_id" class="form-control mousetrap" id="customer_id" required style="width: 100%;" placeholder="Enter Customer name / phone">
                                    <option value="">Enter Customer name / phone</option>
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat add_new_customer" data-name=""  @if(!auth()->user()->can('customer.create')) disabled @endif><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
           <script>
    
                   </script>
                    <div class="col-md-5">
                        <label style="margin-left:20px;">{{ __('repair::lang.service_type') . ':*' }}</label>
                        <br>
                        <label class="radio-inline">
                            <input type="radio" name="service_type" value="carry_in" class="input-icheck" required checked>
                            @lang('repair::lang.carry_in')
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="service_type" value="pick_up" class="input-icheck">
                            @lang('repair::lang.pick_up')
                        </label>
                        <label class="radio-inline radio_btns">
                            <input type="radio" name="service_type" value="on_site" class="input-icheck">
                            @lang('repair::lang.on_site')
                        </label>
                    </div>
                </div>
                <div class="row pick_up_onsite_addr" style="display: none;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pick_up_on_site_addr">{{ __('repair::lang.pick_up_on_site_addr') . ':' }}</label>
                            <textarea name="pick_up_on_site_addr" class="form-control" id="pick_up_on_site_addr" placeholder="{{ __('repair::lang.pick_up_on_site_addr') }}" rows="3"></textarea>
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
                            <label for="variation_id">{{ __('المنتج') . ':' }}</label>
                            <select name="variation_id" id="variation_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="invoice_id">{{ __('الفاتورة ') . ':' }}</label>
                            <select name="transaction_id" id="invoice_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                         <a class="btn btn-sm btn-primary pull-right" data-href="{{action('\\Modules\\Repair\\Http\\Controllers\\DeviceModelController@create')}}" id="add_device_model">
                        	<i class="fa fa-plus"></i>
                        	@lang('messages.add')
                        </a>
                        <div class="form-group">
                            <label for="supplier_id">{{ __('المورد') . ':' }}</label>
                            <select name="supplier_id" id="supplier_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
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
                            <input type="text" name="serial_no" class="form-control" placeholder="{{ __('repair::lang.serial_no') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                           <label for="security_pwd">{{ __('repair::lang.repair_passcode') . ':' }}</label>
                            <div class="input-group">
                                <input type="text" name="security_pwd" class="form-control" placeholder="{{ __('lang_v1.password') }}">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#security_pattern">
                                        <i class="fas fa-lock"></i> @lang('repair::lang.pattern_lock')
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
                            <textarea name="product_configuration" id="product_configuration" class="tags-look" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="defects">{{ __('repair::lang.problem_reported_by_customer') . ':' }}</label> <br>
                            <textarea name="defects" id="defects" class="tags-look" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="product_condition">{{ __('repair::lang.condition_of_product') . ':' }}</label> <br>
                            <textarea name="product_condition" id="product_condition" class="tags-look" rows="3"></textarea>
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
                                <label for="service_staff">{{ __('repair::lang.assign_service_staff') . ':' }}</label>
                                <select name="service_staff" id="service_staff" class="form-control select2">
                                    <option value="">{{ __('restaurant.select_service_staff') }}</option>
                                    @foreach($technecians as $id => $tech)
                                        <option value="{{ $id }}">{{ $tech }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="comment_by_ss">{{ __('repair::lang.comment_by_ss') . ':' }}</label>
                            <textarea name="comment_by_ss" id="comment_by_ss" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="estimated_cost">{{ __('repair::lang.estimated_cost') . ':' }}</label>
                            <input type="text" name="estimated_cost" id="estimated_cost" class="form-control input_number" placeholder="{{ __('repair::lang.estimated_cost') }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="status_id">{{__('sale.status') . ':*'}}</label>
                            <select name="status_id" class="form-control status" id="status_id" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="delivery_date">{{ __('repair::lang.expected_delivery_date') . ':' }}</label>
                            @show_tooltip(__('repair::lang.delivery_date_tooltip'))
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" name="delivery_date" id="delivery_date" class="form-control" readonly>
                                <span class="input-group-addon">
                                    <i class="fas fa-times-circle cursor-pointer clear_delivery_date"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="images">{{ __('lang_v1.image') . ':' }}</label>
                            <input type="file" name="images[]" id="upload_job_sheet_image" accept="image/*" multiple>
                            <small>
                                <p class="help-block">
                                    @lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                                </p>
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>@lang('repair::lang.send_notification')</label><br>
                            <div class="checkbox-inline">
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="send_notification[]" value="sms">
                                    @lang('repair::lang.sms')
                                </label>
                            </div>
                            <div class="checkbox-inline">
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="send_notification[]" value="email">
                                    @lang('business.email')
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary pull-right">
                    @lang('messages.save')
                </button>
            </div>
        </div>
    </form> <!-- /form close -->
    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        @include('contact.create', ['quick_add' => true])
    </div>
    <div class="modal fade" id="device_model_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<script>
    $(document).on('click', '#add_device_model', function () {
            var url = $(this).data('href');
            $.ajax({
                method: 'GET',
                url: url,
                dataType: 'html',
                success: function(result) {
                    $('#device_model_modal').html(result).modal('show');
                }
            });
        });
</script>
</section>
@stop
@section('css')
    @include('repair::job_sheet.tagify_css')
@stop
@section('javascript')
    <script src="{{ url('js/repair_module.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready( function() {

            $('form#job_sheet_form').validate({
                errorPlacement: function(error, element) {
                    if (element.parent('.iradio_square-blue').length) {
                        error.insertAfter($(".radio_btns"));
                    } else if (element.hasClass('status')) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

            var data = [{
              id: "",
              text: '@lang("messages.please_select")',
              html: '@lang("messages.please_select")',
            }, 
            @foreach($repair_statuses as $repair_status)
                {
                id: {{$repair_status->id}},
                @if(!empty($repair_status->color))
                    text: '<i class="fa fa-circle" aria-hidden="true" style="color: {{$repair_status->color}};"></i> {{$repair_status->name}}',
                    title: '{{$repair_status->name}}'
                @else
                    text: "{{$repair_status->name}}"
                @endif
                },
            @endforeach
            ];

            $("select#status_id").select2({
              data: data,
              escapeMarkup: function(markup) {
                return markup;
              }
            });

            @if(!empty($default_status))
                $("select#status_id").val({{$default_status}}).change();
            @endif

            $('#delivery_date').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                ignoreReadonly: true,
            });

            $(document).on('click', '.clear_delivery_date', function() {
                $('#delivery_date').data("DateTimePicker").clear();
            });

            var lock = new PatternLock("#pattern_container", {
                onDraw:function(pattern){
                    $('input#security_pattern').val(pattern);
                },
                enableSetPattern: true
            });

            //filter device model id based on brand & device
            $(document).on('change', '#brand_id', function() {
                getModelForDevice();
                getModelRepairChecklists();
            });

            // get models for particular device
            $(document).on('change', '#device_id', function() {
                getModelForDevice();
            });
            
            $(document).on('change', '#device_model_id', function() {
                getModelRepairChecklists();
            });
            
            function getModelForDevice() {
                var data = {
                    device_id : $("#device_id").val(),
                    brand_id: $("#brand_id").val()
                };

                $.ajax({
                    method: 'GET',
                    url: '/repair/get-device-models',
                    dataType: 'html',
                    data: data,
                    success: function(result) {
                        $('select#device_model_id').html(result);
                    }
                });
            }

            function getModelRepairChecklists() {
                console.log('here');
                var data = {
                        model_id : $("#device_model_id").val(),
                    };
                $.ajax({
                    method: 'GET',
                    url: '/repair/models-repair-checklist',
                    dataType: 'html',
                    data: data,
                    success: function(result) {
                        $(".append_checklists").html(result);
                    }
                });
            }

            $('input[type=radio][name=service_type]').on('ifChecked', function(){
              if ($(this).val() == 'pick_up' || $(this).val() == 'on_site') {
                $("div.pick_up_onsite_addr").show();
              } else {
                $("div.pick_up_onsite_addr").hide();
              }
            });

            //initialize file input
            $('#upload_job_sheet_image').fileinput({
                showUpload: false,
                showPreview: false,
                browseLabel: LANG.file_browse_label,
                removeLabel: LANG.remove,
                maxFileCount: 2
            });

            //initialize tags input (tagify)
            var product_configuration = document.querySelector('textarea#product_configuration');
            tagify_pc = new Tagify(product_configuration, {
              whitelist: {!!json_encode($product_conf)!!},
              maxTags: 100,
              dropdown: {
                maxItems: 100,           // <- mixumum allowed rendered suggestions
                classname: "tags-look", // <- custom classname for this dropdown, so it could be targeted
                enabled: 0,             // <- show suggestions on focus
                closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
              }
            });

            var product_defects = document.querySelector('textarea#defects');
            tagify_pd = new Tagify(product_defects, {
              whitelist: {!!json_encode($defects)!!},
              maxTags: 100,
              dropdown: {
                maxItems: 100,           // <- mixumum allowed rendered suggestions
                classname: "tags-look", // <- custom classname for this dropdown, so it could be targeted
                enabled: 0,             // <- show suggestions on focus
                closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
              }
            });

            var product_condition = document.querySelector('textarea#product_condition');
            tagify_p_condition = new Tagify(product_condition, {
              whitelist: {!!json_encode($product_cond)!!},
              maxTags: 100,
              dropdown: {
                maxItems: 100,           // <- mixumum allowed rendered suggestions
                classname: "tags-look", // <- custom classname for this dropdown, so it could be targeted
                enabled: 0,             // <- show suggestions on focus
                closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
              }
            });
        });
         $('#customer_id').on('select2:select', function(e) {
        var data = e.params.data;
        if (data.pay_term_number) {
            $('input#pay_term_number').val(data.pay_term_number);
        } else {
            $('input#pay_term_number').val('');
        }

        if (data.pay_term_type) {
            $('#pay_term_type').val(data.pay_term_type);
        } else {
            $('#pay_term_type').val('');
        }

        $('#advance_balance_text').text(__currency_trans_from_en(data.balance), true);
        $('#advance_balance').val(data.balance);
       

         $("#customer_balance_due").text(data.balance_due);
         $('#price_group').removeAttr('disabled');
        $("#price_group").val(data.price_group_id);
        if(data.price_group_id>=1){
            $('#price_group option[value='+data.price_group_id+']').attr('selected','selected');
            $('#price_group').attr('disabled','disabled');
        }
        $.ajax({
            method: 'POST',
            url: '/repair/guarantee/get_products_sold',
            data:{
                'customer_id':$("#customer_id").val()
                
            },
        
            success: function(result) {
                console.log(result);
                $('#variation_id').html(result);
                //enable_pos_form_actions();
              //  if (result.success == 1) {
                //    reset_pos_form();
                 //   toastr.success(result.msg);
              //  } else {
                  //  toastr.error(result.msg);
               // }
            },
        });

    });
    $("#variation_id").change(function(){
        $.ajax({
            method: 'POST',
            url: '/repair/guarantee/get_invoice_sold',
            data:{
                'customer_id':$("#customer_id").val(),
                'variation_id':$("#variation_id").val()
            },
        
            success: function(result) {
                console.log(result);
                $('#invoice_id').html(result);
                //enable_pos_form_actions();
              //  if (result.success == 1) {
                //    reset_pos_form();
                 //   toastr.success(result.msg);
              //  } else {
                  //  toastr.error(result.msg);
               // }
            },
        });
    });
    $("#invoice_id").change(function(){
        $.ajax({
            method: 'POST',
            url: '/repair/guarantee/get_supplier',
            data:{
                'transaction_id':$("#invoice_id").val(),
                'variation_id':$("#variation_id").val()
            },
        
            success: function(result) {
                console.log(result);
                $('#supplier_id').html(result);
                //enable_pos_form_actions();
              //  if (result.success == 1) {
                //    reset_pos_form();
                 //   toastr.success(result.msg);
              //  } else {
                  //  toastr.error(result.msg);
               // }
            },
        });
    });
    
    </script>
@endsection