@extends('layouts.app2')
@section('title', __('tenant.add_tenant'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('tenant.add_tenant')</h1>
    </section>

    <!-- Main content -->

    <section class="content">
        <form action="{{ action('ItGuy\TenantController@store') }}" method="POST" id="add_expense_form"
            enctype="multipart/form-data">
            @csrf
            <div class="box box-solid">
                <div class="box-body">

                    <div class="row">


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">@lang('tenant.name'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-id-badge"></i>
                                    </span>
                                    <input type="text" name="name" id="tenant_id" class="form-control"
                                        placeholder="@lang('tenant.name')">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                       

                            <div class="form-group">
                                <label for="account_status">@lang('tenant.account_status'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <select name="account_status" id="account_status" class="form-control" required>

                                        <option value="" disabled selected>@lang('messages.please_select')</option>
                                        @foreach($account_status as $key => $value)
                                            <option value="{{ $value }}" >{{ $value }}</option>
                                        @endforeach

    
                                    </select>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="domain">@lang('tenant.domain'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-briefcase"></i>
                                    </span>
                                    <input type="text" name="domain" id="domain"
                                        class="form-control" placeholder="@lang('tenant.domain')">
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-4">
                       

                            <div class="form-group">
                                <label for="payment_status">@lang('tenant.payment_status'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <select name="payment_status" id="payment_status" class="form-control" required>
    
                                        <option value="" disabled selected>@lang('messages.please_select')</option>
                                        @foreach($payment_status as $key => $value)
                                            <option value="{{ $value }}" >{{ $value }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>


                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="remarks">{{ __('tenant.description') }}:*</label>
                                <input type="text" name="remarks" id="description" class="form-control" required
                                    placeholder="{{ __('tenant.description') }}">
                            </div>
                        </div>

                  





                    </div>

                </div>
            </div>

            <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
        </form>
    </section>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.paid_on').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                ignoreReadonly: true,
            });
        });

      
        $(document).on('change', '#recur_interval_type', function() {
            if ($(this).val() == 'months') {
                $('.recur_repeat_on_div').removeClass('hide');
            } else {
                $('.recur_repeat_on_div').addClass('hide');
            }
        });

   
    </script>
@endsection
