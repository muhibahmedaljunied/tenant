@extends('layouts.app')
@section('title', __('payment.payments'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang( 'depreciation.depreciations' )
            <small>@lang( 'depreciation.manage_depreciations' )</small>
        </h1>
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __( 'depreciation.all_depreciations' )])
            @slot('tool')
                <div class="box-tools">
                    <a class="btn btn-block btn-primary"
                       href="{{ action('DepreciationController@create') }}">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</a>
                </div>
            @endslot
            <table class="table table-bordered table-striped" id="depreciations-table">
                <thead>
                <tr>
                    <th>@lang( 'messages.reference_number' )</th>
                    <th>@lang( 'messages.depreciation_start_date' )</th>
                    <th>@lang('messages.depreciation_end_date')</th>
                    <th>@lang('messages.action')</th>
                </tr>
                </thead>
            </table>
        @endcomponent

    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            var roles_table = $('#depreciations-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/depreciations',
                columnDefs: [{
                    'targets': 1,
                    'orderable': false,
                    'searchable': false,
                }],
            });
            $(document).on('click', 'button.delete_role_button', function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_role,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
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
                                    roles_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    }
                });
            });
        });
    </script>
@endsection
