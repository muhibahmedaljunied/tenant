@extends('layouts.app2')
@section('title', __('tenant.tenants'))

@section('content')

    <!-- Content Header (Page header) -->


    <section class="content-header">
        <h1>@lang( 'user.users' )
            <small>@lang( 'user.manage_users' )</small>
        </h1>
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol> -->
    </section>


    <!-- Main content -->
    <section class="content">


     
        
                @component('components.widget', ['class' => 'box-primary', 'title' => __('user.all_users')])
                    {{-- @slot('tool')
                        <div class="box-tools">
                            <a class="btn btn-block btn-primary" href="{{ action('ItGuy\UserController@create') }}">
                                <i class="fa fa-plus"></i> @lang('messages.add')
                            </a>
                        </div>
                    @endslot --}}

                    
                    @slot('tool')
                        <div class="box-tools">
                            <button type="button" class="btn btn-block btn-primary btn-modal"
                                data-href="{{ action('ItGuy\UserController@create') }}" data-container=".user_modal">
                                <i class="fa fa-plus"></i> @lang('messages.add')</button>
                        </div>
                    @endslot



                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="user_table">
                            <thead>
                                <tr>
                                    <th>@lang('business.username')</th>
                                    <th>@lang('user.name')</th>
                                    <th>@lang('user.role')</th>
                                    <th>@lang('business.email')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                @endcomponent
         
    </section>

    <!-- /.content -->
    <!-- /.content -->

    
    <div class="modal fade user_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>


@stop
@section('javascript')

    <script type="text/javascript">
        //Roles table
        $(document).ready(function() {
            var user_table = $('#user_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/users/index',
                columnDefs: [{
                    "targets": [4],
                    "orderable": false,
                    "searchable": false
                }],
                "columns": [{
                        "data": "username"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "role"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "action"
                    }
                ]
            });
            // $(document).on('click', 'button.delete_user_button', function(){
            //     swal({
            //       title: LANG.sure,
            //       text: LANG.confirm_delete_user,
            //       icon: "warning",
            //       buttons: true,
            //       dangerMode: true,
            //     }).then((willDelete) => {
            //         if (willDelete) {
            //             var href = $(this).data('href');
            //             var data = $(this).serialize();
            //             $.ajax({
            //                 method: "DELETE",
            //                 url: href,
            //                 dataType: "json",
            //                 data: data,
            //                 success: function(result){
            //                     if(result.success == true){
            //                         toastr.success(result.msg);
            //                         user_table.ajax.reload();
            //                     } else {
            //                         toastr.error(result.msg);
            //                     }
            //                 }
            //             });
            //         }
            //      });
            // });

        });
    </script>


@endsection
