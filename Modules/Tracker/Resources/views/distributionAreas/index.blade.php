@extends('layouts.app')
@section('title','مناطق التوزيع')

@section('content')
    <style>
        .table-striped th {
            background-color: #626161;
            color: #ffffff;
        }
    </style>

    <section class="content-header">
        <h1>مناطق التوزيع</h1>
    </section>

    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' =>''])
            @can('tracker.create_distribution_areas')
                @slot('tool')
                    <div class="box-tools">
                        <button type="button" class="btn btn-block btn-primary " onclick="addDistributionArea()">
                            <i class="fa fa-plus"></i> @lang( 'messages.add' )
                        </button>
                    </div>
                @endslot
            @endcan
            @can('tracker.view_distribution_areas')

                <div class="table-responsive">
                    <table class="table table-bordered table-striped " id="assete_table">
                        <thead>
                        <tr>
                            <th>الإسم</th>
                            <th>إقليم</th>
                            <th>قطاع</th>
                            <th>الوصف</th>
                            <th>المسئول</th>
                            @if(auth()->user()->can('tracker.edit_distribution_areas') || auth()->user()->can('tracker.delete_distribution_areas'))
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
    <script>(g => {
            var h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__",
                m = document, b = window;
            b = b[c] || (b[c] = {});
            var d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams,
                u = () => h || (h = new Promise(async (f, n) => {
                    await (a = m.createElement("script"));
                    e.set("libraries", [...r] + "");
                    for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                    e.set("callback", c + ".maps." + q);
                    a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                    d[q] = f;
                    a.onerror = () => h = n(Error(p + " could not load."));
                    a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                    m.head.append(a)
                }));
            d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n))
        })
        ({key: "{{ env('GOOGLE_MAP_API_KEY') }}", v: "weekly"});</script>
    <script>
        let map;
        let poly;

        let polygon;


        function createPolygon(paths) {
            polygon = new google.maps.Polygon({
                paths: paths,
                strokeColor: "#FF0000",
                strokeOpacity: 0.7,
                strokeWeight: 3,
                editable: true,
                draggable: true,
            });

            function updatePointsInput(paths) {
                console.log(paths);
                document.querySelector('#points').value = JSON.stringify(paths.map((path) => {
                    return {lat: path.lat(), lng: path.lng()}
                }));
            }

            polygon.setMap(map);
            console.log()

            polygon.addListener('insert_at', function () {
                console.log('New Points');
            })
            polygon.addListener('bounds_changed', function () {
                console.log('Poly change')
            })
            polygon.addListener('set_at', function () {
                console.log('Set At')
            })
            google.maps.event.addListener(polygon.getPath(), 'insert_at', function () {
                updatePointsInput(polygon.getPath().g);
            })
            google.maps.event.addListener(polygon.getPath(), 'set_at', function () {
                updatePointsInput(polygon.getPath().g);
            })
        }

        async function initMap() {
            const {Map, Polyline} = await google.maps.importLibrary("maps");
            map = new Map(document.getElementById("map"), {
                center: {lat: -34.397, lng: 150.644},
                zoom: 8,
            });
            if (document.querySelector('#points').value) {
                createPolygon(JSON.parse(document.querySelector('#points').value))
            }

            map.addListener('click', addLatLng);
        }

        function addLatLng(event) {
            let paths = [];
            if (polygon) {
                paths = polygon.getPath().g;
                polygon.setMap(null);
                polygon = null;
            }
            paths.push(event.latLng);


            document.querySelector('#points').value = JSON.stringify(paths.map((path) => {
                return {lat: path.lat(), lng: path.lng()}
            }));
            createPolygon(paths);
        }

    </script>
    <script>
        const sectors = @json($sectors);

        function deleteArea(id) {
            swal({
                title: LANG.sure,
                text: 'هل تريد حذف منطقة التوزيع',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then(willDelete => {
                if (willDelete) {
                    var href = '/tracker/distributionAreas/' + id;
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
                url: "{{route('distributionArea-index')}}",
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

        function addDistributionArea() {
            $.ajax({
                url: '/tracker/distributionAreas/create',
                dataType: 'html',
                success: function (result) {
                    $('#modeldialog')
                        .html(result)
                        .modal('show');
                },
            });
        }

        $(document).on('submit', 'form#addarea', function (e) {
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

        $(document).on('submit', 'form#editarea', function (e) {
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

        function editArea(id) {
            $.ajax({
                url: '/tracker/distributionAreas/' + id + '/edit',
                dataType: 'html',
                success: function (result) {
                    $("#modeldialog").html(result)
                        .modal('show');
                },
            });
        }


    </script>

@endsection

