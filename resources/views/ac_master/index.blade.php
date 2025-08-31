@extends('layouts.app')
@section('title', __('chart_of_accounts.master_tree'))

@yield('css')
<style type="text/css">
    body {
        /* direction: rtl; */
        /* background-color: #3482AB; */
        /* background: linear-gradient(to right,#3482AB, #266c91); */
        /* color: #9A1551; */
        /* font-family: 'Noto Kufi Arabic', sans-serif; */
    }

    .tree_icon.active::after {
        content: "⊖";
    }

    .tree_icon::after {
        content: "⊕";
    }

    a {
        text-decoration: none;
        color: #850062;

        &:hover {
            color: #9A1551;
        }
    }

    .treeview {
        * {
            margin: .1em;
            padding: .1em;
        }

        li {
            margin-right: 2em;
            list-style: none;
            border-right: 1px dotted #cbbcc7;
            padding: 2px;

            ul {
                display: none;
            }
        }
    }

    .folder {
        font-size: 1.7vw;
        color: #850062;
        width: 15px;
        /* display: inline-block; */
        text-align: center;
        cursor: pointer;
    }

    .last_branch {
        font-size: 1.5vw;
        color: #130331;
    }

    .actions_sections {
        display: flex;
        justify-content: space-between;
    }
</style>
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="actions_sections">
            <div>
                <h4>@lang('lang_v1.chart_of_accounts')
                    <small>@lang('chart_of_accounts.master_tree')</small>
                </h4>
            </div>
            <div>
                <a href="{{ route('ac.master.export') }}" class="btn btn-primary btn-md">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    {{ trans('lang_v1.export') }}
                </a>
                <a href="{{ action('AcMaster\ImportAcMasterController@index') }}" class="btn btn-success     btn-md">
                    <i class="fa fa-upload"></i>
                    {{ trans('chart_of_accounts.import_master') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        {{-- @can('account.access') --}}
        {{-- <div class="row"> --}}
        {{-- <div class="col-sm-12"> --}}
        @component('components.widget')
            {{-- <div class="parent-container"> --}}
            {{-- <div class="child-container"> --}}
            <div class="treeview">
                <ul>
                    @include('ac_master.partials.tree')
                </ul>
            </div>
            {{-- </div> --}}
            {{-- </div> --}}
        @endcomponent
        {{-- </div> --}}
        {{-- </div> --}}
        {{-- @endcan --}}
        @can('chartaccounts.edit')
            <div class="modal fade master_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
            </div>
        @endcan

        @can('chartofaccounts.create')
            <div class="modal fade add_masterModal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">


               <form action="{{ action('AcMasterController@store') }}" method="POST" id="master_add_form">



                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">@lang('chart_of_accounts.add_mastert')</h4>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" id="account_level" name="account_level">
                            <input type="hidden" id="account_number" name="account_number">
                            <input type="hidden" id="parent_acct_no" name="parent_acct_no">
                            <input type="hidden" id="account_type" name="account_type">

                   <div class="form-group">
    <label for="add-account_name_ar">@lang('chart_of_accounts.account_name_ar'):</label>
    <input type="text" name="account_name_ar" id="add-account_name_ar" class="form-control" required placeholder="@lang('chart_of_accounts.account_name_ar')">
</div>

<div class="form-group">
    <label for="add-account_name_en">@lang('chart_of_accounts.account_name_en'):</label>
    <input type="text" name="account_name_en" id="add-account_name_en" class="form-control" placeholder="@lang('chart_of_accounts.account_name_en')">
</div>

<div class="form-group">
    <label for="add-account_number">@lang('chart_of_accounts.account_number'):</label>
    <input type="text" name="account_number" id="add-account_number" class="form-control" required readonly>
</div>

<div class="form-group">
    <label for="add-account_level">@lang('chart_of_accounts.account_level'):</label>
    <input type="text" name="account_level" id="add-account_level" class="form-control" required readonly>
</div>

<div class="form-group">
    <label for="add-parent_acct_name">@lang('chart_of_accounts.parent_acct_no'):</label>
    <input type="text" name="add-parent_acct_name" id="add-parent_acct_name" class="form-control" required readonly>
</div>

<div class="form-group">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="pay_collect" value="1" id="pay_collect">
            @lang('chart_of_accounts.pay_collect')
        </label>
    </div>
</div>



                        </div><!-- /.modal-body -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">@lang('messages.add')</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
                        </div>

       </form>


                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->

            </div>
        @endcan
    </section>
    <!-- /.content -->

@endsection

@section('javascript')

    <script>
        $(document).ready(function() {
            $('.folder').click(function() {
                $(this).parent().find('ul:first').slideToggle();
                $(this).closest('li').find('.tree_icon').first().toggleClass('active')
            });
        });

        // Just for centering elements:

        // Centring function:
        jQuery.fn.center = function() {
            this.css('position', 'absolute');
            this.css('top', Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                $(window).scrollTop()) + 'px');
            this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                $(window).scrollLeft()) + 'px');
            return this;
        };
        @can('chartofaccounts.edit')
            $('.master_modal').on('shown.bs.modal', function(e) {


                // $(".contact_modal").find('.select2').each(function() {
                //     $(this).select2();
                // });

                // $('form#contact_add_form, form#contact_edit_form')
                $('form#master_edit_form')
                    .submit(function(e) {
                        e.preventDefault();
                    })
                    .validate({
                        rules: {
                            // contact_id: {
                            //     remote: {
                            //         url: '/contacts/check-contact-id',
                            //         type: 'post',
                            //         data: {
                            //             contact_id: function() {
                            //                 return $('#contact_id').val();
                            //             },
                            //             hidden_id: function() {
                            //                 if ($('#hidden_id').length) {
                            //                     return $('#hidden_id').val();
                            //                 } else {
                            //                     return '';
                            //                 }
                            //             },
                            //         },
                            //     },
                            // },
                        },
                        messages: {
                            // contact_id: {
                            //     remote: LANG.contact_id_already_exists,
                            // },
                        },
                        submitHandler: function(form) {
                            e.preventDefault();
                            var data = $(form).serialize();
                            $.ajax({
                                method: 'POST',
                                url: $(form).attr('action'),
                                dataType: 'json',
                                data: data,
                                beforeSend: function(xhr) {
                                    __disable_submit_button($(form).find(
                                        'button[type="submit"]'));
                                },
                                success: function(result) {
                                    if (result.success == true) {
                                        $('div.master_modal').modal('hide');
                                        toastr.success(result.msg);

                                        window.location = $(form).attr('href_redirect');

                                    } else {
                                        toastr.error(result.msg);
                                    }
                                },
                            });
                        },
                    });

                // $('#contact_add_form').trigger('contactFormvalidationAdded');
            });
            $(document).on('click', '.edit_master_button', function(e) {
                e.preventDefault();
                $('div.master_modal').load($(this).attr('href'), function() {
                    $(this).modal('show');
                });
            });
        @endcan
        @can('chartofaccounts.create')
            $('.add_masterModal').on('shown.bs.modal', function(e) {
                $('form#master_add_form')
                    .submit(function(e) {
                        e.preventDefault();
                    })
                    .validate({
                        rules: {
                            // contact_id: {
                            //     remote: {
                            //         url: '/contacts/check-contact-id',
                            //         type: 'post',
                            //         data: {
                            //             contact_id: function() {
                            //                 return $('#contact_id').val();
                            //             },
                            //             hidden_id: function() {
                            //                 if ($('#hidden_id').length) {
                            //                     return $('#hidden_id').val();
                            //                 } else {
                            //                     return '';
                            //                 }
                            //             },
                            //         },
                            //     },
                            // },
                        },
                        messages: {
                            // contact_id: {
                            //     remote: LANG.contact_id_already_exists,
                            // },
                        },
                        submitHandler: function(form) {
                            e.preventDefault();
                            var data = $(form).serialize();
                            $.ajax({
                                method: 'POST',
                                url: $(form).attr('action'),
                                dataType: 'json',
                                data: data,
                                beforeSend: function(xhr) {
                                    __disable_submit_button($(form).find(
                                        'button[type="submit"]'));
                                },
                                success: function(result) {
                                    if (result.success == true) {
                                        $('div.master_modal').modal('hide');
                                        toastr.success(result.msg);

                                        window.location = $(form).attr('href_redirect');

                                    } else {
                                        toastr.error(result.msg);
                                    }
                                },
                            });

                        },
                    });
            });

            function addTree(p1, p2, p3, p4, p5) {
                $('#add-parent_acct_no').val(p1);
                $('#add-parent_acct_name').val(p4);
                $('#parent_acct_no').val(p1);
                $('#add-account_level').val(p2);
                $('#account_level').val(p2);
                $('#add-account_number').val(p3);
                $('#account_number').val(p3);
                $('#account_type').val(p5);
                $('#add-account_name_ar').val('');
                $('#add-account_name_en').val('');
                $('.add_masterModal').modal('show');
            }
        @endcan
        @can('chartofaccounts.delete')
            $(document).on('click', 'button.delete_master_button', function() {

                var href_redirect = $(this).data('href_redirect');
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_master,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then(willDelete => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        var data = $(this).serialize();

                        $.ajax({
                            method: 'DELETE',
                            url: href,
                            dataType: 'json',
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    window.location = href_redirect;
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    }
                });
            });
        @endcan


        // Centring element:
        // $('.parent-container').center();
    </script>
@endsection
