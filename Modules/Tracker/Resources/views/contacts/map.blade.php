@extends('layouts.app')
@section('title','خريطة العملاء')

@section('content')

    <style>
        #map {
            width: 100%;
            height: 500px;
        }
        .select2-selection__clear{
            line-height: 22px;
            font-size: 30px;
        }
    </style>
    <section class="content-header">
        <h1>خريطة العملاء</h1>
    </section>

    <section class="content">
        @component('components.widget', ['class' => 'box-solid'])
            <div class="form-group">
                <label for="contacts">@lang('lang_v1.select_contacts')</label>
                <div style="display: flex; gap: 12px;">
                    <select id="contacts" class="form-control" name="contact_id"></select>
                </div>
            </div>
        @endcomponent
        @component('components.widget', ['class' => 'box-primary', 'title' =>''])
            @can('tracker.access_map')
                <div id="map"></div>
            @endcan
        @endcomponent
    </section>


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

        var contacts = @json($contacts);
        $(document).ready(function () {
            var data = $.map(contacts, function (obj) {
                obj.text = obj.name;
                obj.id = obj.id;
                obj.shipping_address = obj.shipping_address || "";
                obj.contact_id = obj.contact_id || "";
                return obj;
            });
            data.unshift({
                text: "",
                id: ""
            })
            $('#contacts').select2({
                data: data,
                placeholder: "",
                allowClear: true,
                templateResult: function (data) {
                    var template = data.name + " (" + data.contact_id + ")" + '<br><small>' + data.shipping_address + '</small>';

                    return template;
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
            });

        });
        let map;

        let polygon;

        async function initMap() {
            const {Map} = await google.maps.importLibrary("maps");
            if ('geolocation' in navigator) {
                navigator.geolocation.getCurrentPosition((loc) => {
                    const location = new google.maps.LatLng(loc.coords.latitude, loc.coords.longitude);

                })
            } else {

            }
            map = new Map(document.getElementById("map"), {
                center: {lat: 26.807523, lng: 25.5864918},
                zoom: 5,
            });

            function contactContent(contact) {
                return '<div id="content">' +
                    '<div id="siteNotice">' +
                    "</div>" +
                    '<h1 id="firstHeading" class="firstHeading" style="margin-right: 20px;">' + contact.name + '</h1>' +
                    '<div id="bodyContent">' +
                    '<p>' + contact.address_line_1 + '</p>' +
                    "</div>" +
                    "</div>"
            }

            $('#contacts').on('select2:select', function (e) {
                console.log(e);
                const contact = contacts.find((con) => con.id == e.target.value);
                if (contact) {
                    map.moveCamera({
                        zoom: 16,
                        center: JSON.parse(contact.location),
                    });
                }
            })
            $('#contacts').on('select2:unselect', function () {
                console.log('Cleared');
                map.moveCamera({
                    center: {lat: 26.807523, lng: 25.5864918},
                    zoom: 5,
                })
            })

            contacts.forEach((contact) => {
                const infowindow = new google.maps.InfoWindow({
                    content: contactContent(contact),
                    ariaLabel: "Uluru",
                });
                const marker = new google.maps.Marker({
                    position: JSON.parse(contact.location),
                    map: map
                })
                marker.addListener("click", () => {
                    infowindow.open({
                        anchor: marker,
                        map,
                    });
                });
            })
        }

        initMap();
    </script>
@endsection
