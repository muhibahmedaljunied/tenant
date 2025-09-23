@extends('layouts.app')
@section('title', 'خطوط السير')

@section('content')
    <style>
        .table-striped th {
            background-color: #626161;
            color: #ffffff;
        }
    </style>

    <section class="content-header">
        <h1>خطوط السير</h1>
    </section>

    <section class="content">
        @component('components.filters', ['title' => __('report.filters')])
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="بحث...." id="query">
                </div>
            </div>
            <div class="col-md-4">
                <label for="distribution_area_id">منطقة التوزيع:</label>
                <select name="distribution_area_id" id="distribution_area_id" class="select2 form-control" data-placeholder=""
                    data-allow-clear="true">
                    <option value=""></option>
                    @foreach ($distributionAreas as $da)
                        <option value="{{ $da->id }}">{{ $da->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="contact_id"> العميل:</label>
                <select name="contact_id" id="contact_id" class="select2 form-control" data-placeholder=""
                    data-allow-clear="true" multiple>
                    @foreach ($contacts as $contact)
                        <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="user_id"> المسئول:</label>
                <select name="user_id" id="user_id" class="select2 form-control" data-placeholder="" data-allow-clear="true">
                    <option value=""></option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->getUserFullNameAttribute() }}</option>
                    @endforeach
                </select>
            </div>
        @endcomponent
        @component('components.widget', ['class' => 'box-primary', 'title' => ''])
            @can('tracker.create_tracks')
                @slot('tool')
                    <div class="box-tools">
                        <button type="button" class="btn btn-block btn-primary " onclick="addTrack()">
                            <i class="fa fa-plus"></i> @lang('messages.add')
                        </button>
                    </div>
                @endslot
            @endcan
            @can('tracker.view_tracks')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped " id="assete_table">
                        <thead>
                            <tr>
                                <th>الإسم</th>
                                <th>منطقة توزيع</th>
                                <th>قطاع</th>
                                <th>إقليم</th>
                                <th>الوصف</th>
                                <th>المسئول</th>
                                @if (auth()->user()->can('tracker.edit_tracks') ||
                                        auth()->user()->can('tracker.delete_tracks'))
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
    <div class="modal fade datamodal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modeldialog">

    </div>


    {{-- Create Model --}}
@endsection


@section('javascript')
    <script>
        var sectors = @json($sectors);
        var provinces = @json($provinces);
        var distributionAreas = @json($distributionAreas);
        $('#modeldialog').on('show.bs.modal', function() {
            const distributionArea = document.querySelector('#distribution_area_id');
            if (!distributionArea.options.length) {
                distributionAreas.forEach((area) => {
                    const option = document.createElement('option');
                    option.setAttribute('value', area.id)
                    option.textContent = area.name;
                    distributionArea.append(option);
                });
            }
            distributionArea.addEventListener('input', function(event) {
                const sectorId = document.querySelector('#sector_id');
                const provinceId = document.querySelector('#province_id');
                if (!event.target.value) {
                    sectorId.value = '';
                    provinceId.value = '';
                    return;
                }
                const area = distributionAreas.find(disArea => disArea.id === parseInt(event.target.value));
                const sector = sectors.find((sector) => sector.id === area.sector_id);
                const province = provinces.find((province) => province.id === sector.province_id);

                sectorId.value = sector.name;
                provinceId.value = province.name;
            })

            $('#contacts').select2({
                multiple: true,
                width: '100%',
                placeholder: 'Select Contact'
            });
        })

        function deleteTrack(id) {
            function onTrackDeleted(result) {
                if (result.success) {
                    toastr.success(result.msg);
                    var drow = document.getElementById(id);
                    drow.parentNode.removeChild(drow);
                } else {
                    toastr.error(result.msg);
                }
            }

            swal({
                title: LANG.sure,
                text: 'هل تريد حذف خط السير',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then(willDelete => {
                if (willDelete) {
                    var href = '/tracker/tracks/' + id;
                    var data = id;
                    $.ajax({
                        method: 'DELETE',
                        url: href,
                        dataType: 'json',
                        data: {
                            data: data
                        },
                        success: onTrackDeleted,
                        error: function(result) {
                            const res = result.responseJSON;
                            if (result.status === 422) {
                                swal({
                                    title: LANG.sure,
                                    text: res.msg,
                                    icon: 'warning',
                                    buttons: true,
                                    dangerMode: true
                                }).then((confirm) => {
                                    data.force = true;
                                    console.log(data);
                                    if (confirm) {
                                        $.ajax({
                                            method: 'DELETE',
                                            url: href,
                                            dataType: 'json',
                                            data: {
                                                force: true,
                                            },
                                            success: onTrackDeleted
                                        })
                                    }
                                })
                            }
                            console.log(result);
                        }
                    });
                }
            });
        }

        $(document).ready(function() {
            getdata();
        });

        function getdata() {
            let data = {};
            if ($('#distribution_area_id').val()) {
                data.distribution_area_id = $('#distribution_area_id').val();
            }
            if ($('#contact_id').val()) {
                data.contact_id = $('#contact_id').val();
            }

            if ($('#user_id').val()) {
                data.user_id = $('#user_id').val();
            }

            if($('#query').val()){
                data.query = $('#query').val();
            }

            $.ajax({
                url: "{{ route('track-index') }}",
                method: 'GET',
                data,
                success: function(data) {
                    document.getElementById("datatable").innerHTML = data;

                },
                error: function(data) {
                    // Something went wrong
                    // HERE you can handle asynchronously the response

                    // Log in the console
                    var errors = data.responseJSON;
                    console.log(errors);

                    // or, what you are trying to achieve
                    // render the response via js, pushing the error in your
                    // blade page
                    errorsHtml = '<div class="alert alert-danger"><ul>';

                    $.each(errors.error, function(key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></div>';

                    $('#form-errors').html(
                    errorsHtml); //appending to a <div id="form-errors"></div> inside form
                }
            });
        }

        function addTrack() {
            $.ajax({
                url: '/tracker/tracks/create',
                dataType: 'html',
                success: function(result) {
                    $('#modeldialog')
                        .html(result)
                        .modal('show');
                },
            });
        }

        $(document).on('submit', 'form#addtrack', function(e) {
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
                success: function(result) {
                    if (result.success) {
                        $('#modeldialog').modal('hide');
                        toastr.success(result.msg);
                        getdata();

                    } else {
                        toastr.error(result.msg);
                    }
                },
                error: function(data) {
                    // Something went wrong
                    // HERE you can handle asynchronously the response
                    $(this)
                        .find('button[type="submit"]').removeAttr('disabled')
                    // Log in the console
                    var errors = data.responseJSON;
                    console.log(errors);

                    // or, what you are trying to achieve
                    // render the response via js, pushing the error in your
                    // blade page
                    errorsHtml = '<div class="alert alert-danger"><ul>';

                    $.each(errors.error, function(key, value) {
                        errorsHtml += '<li>' + value[0] +
                        '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></div>';

                    $('#form-errors').html(
                    errorsHtml); //appending to a <div id="form-errors"></div> inside form
                }

            });
        });

        $(document).on('submit', 'form#editsector', function(e) {
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
                success: function(result) {
                    if (result.success) {
                        $('#modeldialog').modal('hide');
                        toastr.success(result.msg);
                        getdata();

                    } else {
                        toastr.error(result.msg);
                    }
                },
                error: function(data) {
                    // Something went wrong
                    // HERE you can handle asynchronously the response

                    // Log in the console
                    var errors = data.responseJSON;
                    console.log(errors);

                    // or, what you are trying to achieve
                    // render the response via js, pushing the error in your
                    // blade page
                    errorsHtml = '<div class="alert alert-danger"><ul>';

                    $.each(errors.error, function(key, value) {
                        errorsHtml += '<li>' + value[0] +
                        '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></div>';

                    $('#form-errors').html(
                    errorsHtml); //appending to a <div id="form-errors"></div> inside form
                    $(this)
                        .find('button[type="submit"]')
                        .removeAttr('disabled');
                }

            });
        });

        function editTrack(id) {
            $.ajax({
                url: '/tracker/tracks/' + id + '/edit',
                dataType: 'html',
                success: function(result) {
                    $("#modeldialog").html(result)
                        .modal('show');
                },
            });
        }
        $(document).on('change', '#distribution_area_id, #contact_id, #user_id', function() {
            getdata();
        })
        $('#query').on('input', function() {
                getdata();
        })
    </script>

@endsection
