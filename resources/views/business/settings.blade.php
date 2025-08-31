@extends('layouts.app')
@section('title', __('business.business_settings'))

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('business.business_settings')</h1>
        <br>
        @include('layouts.partials.search_settings')
    </section>

    <!-- Main content -->
   <section class="content">
    <form action="{{ action('BusinessController@postBusinessSettings') }}" method="POST" id="bussiness_edit_form" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-12 pos-tab-container">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pos-tab-menu">
                        <div class="list-group">
                            <a href="#" class="list-group-item text-center active">@lang('business.business')</a>
                            <a href="#" class="list-group-item text-center">@lang('zatca.full_integrate')</a>
                            <a href="#" class="list-group-item text-center">@lang('business.tax') @show_tooltip(__('tooltip.business_tax'))</a>
                            <a href="#" class="list-group-item text-center">@lang('business.product')</a>
                            <a href="#" class="list-group-item text-center">@lang('contact.contact')</a>
                            <a href="#" class="list-group-item text-center">@lang('business.sale')</a>
                            <a href="#" class="list-group-item text-center">@lang('sale.pos_sale')</a>
                            <a href="#" class="list-group-item text-center">@lang('purchase.purchases')</a>
                            <a href="#" class="list-group-item text-center">@lang('business.dashboard')</a>
                            <a href="#" class="list-group-item text-center">@lang('business.system')</a>
                            <a href="#" class="list-group-item text-center">@lang('lang_v1.prefixes')</a>
                            <a href="#" class="list-group-item text-center">@lang('lang_v1.email_settings')</a>
                            <a href="#" class="list-group-item text-center">@lang('lang_v1.sms_settings')</a>
                            <a href="#" class="list-group-item text-center">@lang('lang_v1.reward_point_settings')</a>
                            <a href="#" class="list-group-item text-center">@lang('lang_v1.modules')</a>
                            <a href="#" class="list-group-item text-center">@lang('lang_v1.custom_labels')</a>
                        </div>
                    </div>

                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                        @include('business.partials.settings_business')
                        @include('business.partials.settings_zatca')
                        @include('business.partials.settings_tax')
                        @include('business.partials.settings_product')
                        @include('business.partials.settings_contact')
                        @include('business.partials.settings_sales')
                        @include('business.partials.settings_pos')
                        @include('business.partials.settings_purchase')
                        @include('business.partials.settings_dashboard')
                        @include('business.partials.settings_system')
                        @include('business.partials.settings_prefixes')
                        @include('business.partials.settings_email')
                        @include('business.partials.settings_sms')
                        @include('business.partials.settings_reward_point')
                        @include('business.partials.settings_modules')
                        @include('business.partials.settings_custom_labels')
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-danger pull-right" type="submit">@lang('business.update_settings')</button>
            </div>
        </div>
    </form>
</section>

<div class="modal fade view_modal zatca_settings_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('zatca.settings.title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('business.update_zatca_setting') }}" method="POST" id="zatca_settings_form">
                    @method('PUT')
                    @csrf
                    @include('zatca_setting.partials.form')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.close')</button>
                <button type="button" class="btn btn-primary save_zatca_settings">@lang('messages.save')</button>
            </div>
        </div>
    </div>
</div>

    <!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        __page_leave_confirmation('#bussiness_edit_form');
        $(document).on('ifToggled', '#use_superadmin_settings', function() {
            if ($('#use_superadmin_settings').is(':checked')) {
                $('#toggle_visibility').addClass('hide');
                $('.test_email_btn').addClass('hide');
            } else {
                $('#toggle_visibility').removeClass('hide');
                $('.test_email_btn').removeClass('hide');
            }
        });

        $("#zatca_phase").change(function() {
            var value = $(this).val();
            if (value == 'phase_2') {
                return $('.zatca_inputs').show();
            }
            return $(".zatca_inputs").hide();
        })

        $('#test_email_btn').click(function() {
            var data = {
                mail_driver: $('#mail_driver').val(),
                mail_host: $('#mail_host').val(),
                mail_port: $('#mail_port').val(),
                mail_username: $('#mail_username').val(),
                mail_password: $('#mail_password').val(),
                mail_encryption: $('#mail_encryption').val(),
                mail_from_address: $('#mail_from_address').val(),
                mail_from_name: $('#mail_from_name').val(),
            };
            $.ajax({
                method: 'post',
                data: data,
                url: "{{ action('BusinessController@testEmailConfiguration') }}",
                dataType: 'json',
                success: function(result) {
                    if (result.success == true) {
                        swal({
                            text: result.msg,
                            icon: 'success'
                        });
                    } else {
                        swal({
                            text: result.msg,
                            icon: 'error'
                        });
                    }
                },
            });
        });

        $('#test_sms_btn').click(function() {
            var test_number = $('#test_number').val();
            if (test_number.trim() == '') {
                toastr.error('{{ __('lang_v1.test_number_is_required') }}');
                $('#test_number').focus();

                return false;
            }

            var data = {
                url: $('#sms_settings_url').val(),
                send_to_param_name: $('#send_to_param_name').val(),
                msg_param_name: $('#msg_param_name').val(),
                request_method: $('#request_method').val(),
                param_1: $('#sms_settings_param_key1').val(),
                param_2: $('#sms_settings_param_key2').val(),
                param_3: $('#sms_settings_param_key3').val(),
                param_4: $('#sms_settings_param_key4').val(),
                param_5: $('#sms_settings_param_key5').val(),
                param_6: $('#sms_settings_param_key6').val(),
                param_7: $('#sms_settings_param_key7').val(),
                param_8: $('#sms_settings_param_key8').val(),
                param_9: $('#sms_settings_param_key9').val(),
                param_10: $('#sms_settings_param_key10').val(),

                param_val_1: $('#sms_settings_param_val1').val(),
                param_val_2: $('#sms_settings_param_val2').val(),
                param_val_3: $('#sms_settings_param_val3').val(),
                param_val_4: $('#sms_settings_param_val4').val(),
                param_val_5: $('#sms_settings_param_val5').val(),
                param_val_6: $('#sms_settings_param_val6').val(),
                param_val_7: $('#sms_settings_param_val7').val(),
                param_val_8: $('#sms_settings_param_val8').val(),
                param_val_9: $('#sms_settings_param_val9').val(),
                param_val_10: $('#sms_settings_param_val10').val(),
                test_number: test_number
            };

            $.ajax({
                method: 'post',
                data: data,
                url: "{{ action('BusinessController@testSmsConfiguration') }}",
                dataType: 'json',
                success: function(result) {
                    if (result.success == true) {
                        swal({
                            text: result.msg,
                            icon: 'success'
                        });
                    } else {
                        swal({
                            text: result.msg,
                            icon: 'error'
                        });
                    }
                },
            });
        });
        // $('.edit_zatca').click(function() {
        //     $('.zatca_settings_modal').modal('show');
        // });
        $('.zatca_settings_modal .save_zatca_settings').click(function() {
            $('.zatca_settings_modal').modal('hide');
            if(!confirm('Are you sure?')) return false;
            $('#zatca_settings_form').submit();

        });
        $(document).ready(function() {
            $("#zatca_phase").trigger('change');
        })
    </script>
@endsection
