@extends('layouts.app')
@section('title','القطاعات')

@section('content')
    <style>
        .table-striped th {
            background-color: #626161;
            color: #ffffff;
        }
    </style>

    <section class="content-header">
        <h1>قطاعات</h1>
    </section>

    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' =>''])
            @can('tracker.create_sectors')
                @slot('tool')
                    <div class="box-tools">
                        <button type="button" class="btn btn-block btn-primary " onclick="addSector()">
                            <i class="fa fa-plus"></i> @lang( 'messages.add' )
                        </button>
                    </div>
                @endslot
            @endcan
            @can('tracker.view_sectors')

                <div class="table-responsive">
                    <table class="table table-bordered table-striped " id="assete_table">
                        <thead>
                        <tr>
                            <th>الإسم</th>
                            <th>إقليم</th>
                            <th>الوصف</th>
                            <th>المسئول</th>
                            @if(auth()->user()->canAny(['tracker.edit_sectors', 'tracker.delete_sectors']))
                                <th>خيار</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody id="datatable"></tbody>
                    </table>
                </div>
            @endcan
        @endcomponent
    </section>
    <div class="modal fade datamodal" tabindex="-1" role="dialog"
         aria-labelledby="gridSystemModalLabel" id="modeldialog">

    </div>


    {{--Create Model --}}
@endsection


@section('javascript')
    <script>
        function deleteSector(id) {
            swal({
                title: LANG.sure,
                text: 'هل تريد حذف الاقليم',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then(willDelete => {
                if (willDelete) {
                    var href = '/tracker/sectors/' + id;
                    var data = id;
                    $.ajax({
                        method: 'DELETE',
                        url: href,
                        dataType: 'json',
                        data: {
                            data: data
                        },
                        success: function (result) {
                            if (result.success) {
                                toastr.success(result.msg);
                                var drow = document.getElementById(id);
                                drow.parentNode.removeChild(drow);
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                    });
                }
            });
        }

        $(document).ready(function () {
            getdata();
        });

        function getdata() {

            $.ajax({
                url: "{{route('sector-index')}}",
                method: 'GET',
                success: function (data) {
                    document.getElementById("datatable").innerHTML = data;

                },
                error: function (data) {
                    // Something went wrong
                    // HERE you can handle asynchronously the response

                    // Log in the console
                    var errors = data.responseJSON;
                    console.log(errors);

                    // or, what you are trying to achieve
                    // render the response via js, pushing the error in your
                    // blade page
                    errorsHtml = '<div class="alert alert-danger"><ul>';

                    $.each(errors.error, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></div>';

                    $('#form-errors').html(errorsHtml); //appending to a <div id="form-errors"></div> inside form
                }
            });
        }

        function addSector() {
            $.ajax({
                url: '/tracker/sectors/create',
                dataType: 'html',
                success: function (result) {
                    $('#modeldialog')
                        .html(result)
                        .modal('show');
                },
            });
        }

        $(document).on('submit', 'form#addsector', function (e) {
            e.preventDefault();
            $(this)
                .find('button[type="submit"]')
                .attr('disabled', true);
            var data = $(this).serialize();

            $.ajax({
                method: 'POST',
                url: $(this).attr('action'), // action('BrandController@store'
                dataType: 'json',
                data: data,
                success: function (result) {
                    if (result.success) {
                        $('#modeldialog').modal('hide');
                        toastr.success(result.msg);
                        getdata();

                    } else {
                        toastr.error(result.msg);
                    }
                },
                error: function (data) {
                    // Something went wrong
                    // HERE you can handle asynchronously the response

                    // Log in the console
                    var errors = data.responseJSON;
                    console.log(errors);

                    // or, what you are trying to achieve
                    // render the response via js, pushing the error in your
                    // blade page
                    errorsHtml = '<div class="alert alert-danger"><ul>';

                    $.each(errors.error, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></div>';

                    $('#form-errors').html(errorsHtml); //appending to a <div id="form-errors"></div> inside form
                }

            });
        });

        $(document).on('submit', 'form#editsector', function (e) {
            e.preventDefault();
            $(this)
                .find('button[type="submit"]')
                .attr('disabled', true);
            var data = $(this).serialize();

            $.ajax({
                method: 'PUT',
                url: $(this).attr('action'), // action('BrandController@store'
                dataType: 'json',
                data: data,
                success: function (result) {
                    if (result.success) {
                        $('#modeldialog').modal('hide');
                        toastr.success(result.msg);
                        getdata();

                    } else {
                        toastr.error(result.msg);
                    }
                },
                error: function (data) {
                    // Something went wrong
                    // HERE you can handle asynchronously the response

                    // Log in the console
                    var errors = data.responseJSON;
                    console.log(errors);

                    // or, what you are trying to achieve
                    // render the response via js, pushing the error in your
                    // blade page
                    errorsHtml = '<div class="alert alert-danger"><ul>';

                    $.each(errors.error, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></div>';

                    $('#form-errors').html(errorsHtml); //appending to a <div id="form-errors"></div> inside form
                    $(this)
                        .find('button[type="submit"]')
                        .removeAttr('disabled');
                }

            });
        });

        function editSector(id) {
            $.ajax({
                url: '/tracker/sectors/' + id + '/edit',
                dataType: 'html',
                success: function (result) {
                    $("#modeldialog").html(result)
                        .modal('show');
                },
            });
        }


    </script>

@endsection

