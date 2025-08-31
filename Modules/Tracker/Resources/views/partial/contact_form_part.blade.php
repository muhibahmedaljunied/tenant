<div class="form-group customer_fields">
   <label for="location">@lang('contact.contact_location'):</label>

    <input
            id="pac-input"
            class="controls"
            type="text"
            placeholder="Search Box"
    />
    <input id="contact_location" name="location" type="hidden">
    <div id="contactLocationMap"></div>
</div>
<script>
    (() => {
        let marker;
        let map;

        function addMarker(event) {
            if (marker) {
                marker.setMap(null);
                marker = null;
            }
            let pos = event;
            if (event.latLng) {
                pos = event.latLng;
            }
            document.querySelector('#contact_location').value = JSON.stringify(pos);
            marker = new google.maps.Marker({
                position: pos,
                map,
                title: "Selected Position"
            })
        }

        function createMap(center) {
            map = new google.maps.Map(document.getElementById('contactLocationMap'), {
                mapTypeControl: false,
                streetViewControl: false,
                center: center,
                zoom: 18
            })

            addMarker(center);
            map.addListener('click', addMarker)
            initAutocomplete();
        }

        function initAutocomplete() {
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });

            let markers = [];

            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();

                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }

                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }

        function initMap() {
            if ('geolocation' in navigator) {
                navigator.geolocation.getCurrentPosition((loc) => {
                    createMap({
                        lat: loc.coords.latitude,
                        lng: loc.coords.longitude,
                    })
                }, () => {
                    createMap({
                        lat: 26.820553,
                        lng: 30.802498
                    })
                })
            }
        }

        initMap();
    })()

</script>
<style>
    #contactLocationMap {
        width: 100%;
        height: 300px;
    }


    .controls {
        margin-top: 16px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
    }

    #pac-input:focus {
        border-color: #4d90fe;
    }

    .pac-container {
        font-family: Roboto;
    }

    #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
    }

    #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }
</style>

