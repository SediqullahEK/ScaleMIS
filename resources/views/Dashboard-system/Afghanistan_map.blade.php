<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet_fullscreen/dist/leaflet.fullscreen.css') }}">


    <style>
        div#map {
            height: 98.32vh !important;
            width: 100% !important;
        }

        div#afg {
            position: absolute;
            bottom: 0px;
            right: 0px;
            width: 1000px;
            height: 20px;
            background-color: #fff;
            z-index: 10000000000000;
        }

        div#coordinate_of_each_point {
            position: absolute;
            bottom: 5px;
            left: 0px;
            width: 230px;
            height: 20px;
            background-color: #fff;
            z-index: 10000000000000;
        }

        .leaflet-container {
            background: transparent !important
        }

        .customPopup .leaflet-popup-content-wrapper,
        .customPopup .leaflet-popup-tip {
            background: #000;
            color: #fff;
        }

        img.leaflet-tile,
        img.leaflet-marker-icon,
        img.leaflet-marker-shadow {
            /* work-around from here: https://github.com/Leaflet/Leaflet/issues/161 */
            outline: 1px solid transparent !important;
            /* work-around from here: https://bugs.chromium.org/p/chromium/issues/detail?id=600120 */
            mix-blend-mode: plus-lighter !important;
        }


        /* div.auto-search-wrapper{
            position: absolute;
            top: 5px;
            right: 100px;
            width: 230px;
            height: 20px;
            background-color: #fff;
            z-index: 10000000000000;
        } */
    </style>
</head>

<body>

    <div id="afg">

    </div>

    <div id="coordinate_of_each_point">

    </div>


    <div id="map">

    </div>



    {{--
    <div class="auto-search-wrapper">
        <input type="text" id="local" autocomplete="off" placeholder="Enter letter" />
    </div> --}}






    <script src="{{ asset('assets/jquery/dist/jquery.min.js') }}"></script>


    <script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('assets/leaflet/leaflet_fullscreen/dist/Leaflet.fullscreen.min.js') }}"></script>




    <script>
        // Creating map options
        var mapOptions = {

            center: [33.94, 67.71],

            zoom: 7,

            minZoom: 6,
            fullscreenControl: true,


        }


        // Creating a map object
        var map = new L.map('map', mapOptions);



        // Types of Maps and add it to layer start it here

        var OpenStreetMap_Mapnik = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });

        var OpenTopoMap = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            maxZoom: 17,
            attribution: 'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
        });

        var Esri_WorldTopoMap = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ, TomTom, Intermap, iPC, USGS, FAO, NPS, NRCAN, GeoBase, Kadaster NL, Ordnance Survey, Esri Japan, METI, Esri China (Hong Kong), and the GIS User Community'
            });

        var Esri_WorldStreetMap = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012'
            });

        var Esri_WorldImagery = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {

                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'


            });



        var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });


        var googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });

        var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });

        var googleTerrain = L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });



        map.addLayer(googleStreets);


        var baseLayers = {

            OpenStreetMap_Mapnik: OpenStreetMap_Mapnik,
            OpenTopoMap: OpenTopoMap,
            Esri_WorldTopoMap: Esri_WorldTopoMap,
            Esri_WorldStreetMap: Esri_WorldStreetMap,
            Esri_WorldImagery: Esri_WorldImagery,
            googleStreets: googleStreets,
            googleHybrid: googleHybrid,
            googleSat: googleSat,
            googleTerrain: googleTerrain,



        };

        L.control.layers(baseLayers).addTo(map);

        // Types of Maps and add it to layer End it here






        // obtaining coordinates after clicking on the map
        map.on("click", function(e) {
            const markerPlace = document.querySelector("#coordinate_of_each_point");
            markerPlace.textContent = e.latlng;
        });


        // image
        const imageUrl =
            "https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/Krakow_Center_-_basic_map.svg/1440px-Krakow_Center_-_basic_map.svg.png";


        // custom popup image + text
        const customPopup =
            '<div class="customPopup"><figure><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/be/A-10_Sukiennice_w_Krakowie_Krak%C3%B3w%2C_Rynek_G%C5%82%C3%B3wny_MM.jpg/1920px-A-10_Sukiennice_w_Krakowie_Krak%C3%B3w%2C_Rynek_G%C5%82%C3%B3wny_MM.jpg"><figcaption>Source: wikipedia.org</figcaption></figure><div>Kraków,[a] also written in English as Krakow and traditionally known as Cracow, is the second-largest and one of the oldest cities in Poland. Situated on the Vistula River in Lesser Poland Voivodeship... <a href="https://en.wikipedia.org/wiki/Krak%C3%B3w" target="_blank">→ show more</a></div></div>';

        // specify popup options
        const customOptions = {
            minWidth: "220", // set max-width
            keepInView: false, // Set it to true if you want to prevent users from panning the popup off of the screen while it is open.
        };






        // Define the base URLs for your images
        const scaleImageBaseUrl = "{{ asset('storage/') }}";
        const defaultImage = "{{ asset('storage/scale_images/scale.png') }}"; // Use a placeholder if needed

        $.getJSON('Afg_Map_Data', function(data) {
            console.log(data);
            data.data.forEach(function(record) {
                // let iconUrl = record.status 
                //     ? "{{ asset('assets/leaflet/images/marker-icon-green.png') }}" // Green icon for active
                //     : "{{ asset('assets/leaflet/images/marker-icon-gray.png') }}"; // Gray icon for inactive
                //     // Create marker with dynamic icon
                // L.marker([location.latitude, location.longitude], {
                //     icon: L.icon({
                //         iconUrl: iconUrl,
                //         iconSize: [50, 58],
                //         iconAnchor: [20, 58],
                //         popupAnchor: [0, -60]
                //     })
                // }).addTo(map)
                // .bindPopup(location.popup_content);

                let marker = L.marker([record.latitude, record.longitude], {
                        icon: L.icon({
                            iconUrl: record.status == 1 ?
                                "{{ asset('assets/leaflet/images/scale.gif') }}" :
                                "{{ asset('assets/leaflet/images/Scale-deactive.png') }}",
                            iconSize: record.status == 1 ? [50, 58] : [80, 90],
                            iconAnchor: [20, 58],
                            popupAnchor: [0, -60]
                        })
                    }).addTo(map)
                    .bindPopup(`
                <div class="customPopup">
                    <img src="${record.scale_image ? scaleImageBaseUrl + '/' + record.scale_image : defaultImage}"
                alt="" class="rounded-t-md" width="100%">

                <div class="p-6 text-right">
                    <h2 class="font-bold mb-2 text-2xl text-purple-800 ">
                        <span>${record.scale_name}</span>
                        <span>${record.location}</span>
                    </h2>
                    <table class="min-w-full border text-center text-sm font-light dark:border-neutral-500" dir="rtl" style="float: right">
                        <thead>
                            <tr class="border-b font-medium dark:border-neutral-500">
                                <th class="border-r py-2 dark:border-neutral-500 w-2/5">مادل :</th>
                                <th class="border-r py-2 dark:border-neutral-500">${record.scale_model ? record.scale_model: 'ندارد'}</th>
                            </tr>
                            <tr class="border-b font-medium dark:border-neutral-500">
                                <th class="border-r py-2 dark:border-neutral-500">کمپنی :</th>
                                <th class="border-r py-2 dark:border-neutral-500">${record.scale_company ? record.scale_company : 'ندارد' }</th>
                            </tr>
                           <tr class="border-b font-medium dark:border-neutral-500">
                                <th class="border-r py-2 dark:border-neutral-500">حالت :</th>
                               <th class="border-r py-2 dark:border-neutral-500">
                                    ${record.status == 1 ? 'فعال' : 'غیر فعال'}
                                </th>
                            </tr>
                            <tr class="border-b font-medium dark:border-neutral-500">
                                <th class="border-r py-2 dark:border-neutral-500">کارمند ترازو :</th>
                                <th class="border-r py-2 dark:border-neutral-500">${record.scale_employee ? record.scale_employee : 'ندارد' }</th>
                            </tr>
                            <tr class="border-b font-medium dark:border-neutral-500">
                                <th class="border-r py-2 dark:border-neutral-500">شماره تماس :</th>
                                <th class="border-r py-2 dark:border-neutral-500">${record.employee_phone ? record.employee_phone : 'ندارد' }</th>
                            </tr>
                            <tr class="border-b font-medium dark:border-neutral-500">
                                <th class="border-r py-2 dark:border-neutral-500">مقدار منرال منتقل شده :</th>
                                <th class="border-r py-2 dark:border-neutral-500">${record.transferred_mineral ? record.transferred_mineral : 0 }</th>
                            </tr>
                            <tr class="border-b font-medium dark:border-neutral-500">
                                <th class="border-r py-2 dark:border-neutral-500">جزییات :</th>
                                <th class="border-r py-2 dark:border-neutral-500">${record.description ? record.description : 'ندارد' }</th>
                            </tr>
                        </thead>
                    </table>
                    <a class="text-purple-600 hover:text-purple-500 underline text-sm">_</a>
                </div>
            </div>
        `, customOptions)
                    .on("dblclick", function(e) {
                        map.flyTo(e.target.getLatLng(), 17, {
                            duration: 5.5,
                            easeLinearity: 3.25,
                        });
                    });
            });
        });



        // Create a home button element
        var resetZoomButton = L.Control.extend({
            options: {
                position: 'topleft'
            },

            onAdd: function(map) {
                var container = L.DomUtil.create('div', 'home-button');
                container.innerHTML =
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">' +
                    '<path d="M0 0h24v24H0z" fill="none"/>' +
                    '<path d="M17.65 6.35A7.95 7.95 0 0 0 12 4a8 8 0 1 0 8 8h-2a6 6 0 1 1-6-6c1.31 0 2.55.42 3.55 1.14L13 11h7V4l-2.35 2.35z"/>' +
                    '</svg>';


                // Add a click event listener to the home button
                container.addEventListener('click', function() {
                    // Set the map view to the default zoom level
                    map.flyTo(map.options.center, map.options.zoom, {
                        duration: 1, // Transition duration in seconds
                    });
                });

                return container;
            }
        });

        // Add the home button control to the map
        map.addControl(new resetZoomButton());

        // Create a home button element
        var homeButton = L.Control.extend({
            options: {
                position: 'topleft' // Position of the button on the map (can be 'topright', 'bottomleft', 'bottomright')
            },

            onAdd: function(map) {
                // Create a container for the button
                var container = L.DomUtil.create('div', 'home-button leaflet-bar');
                container.innerHTML =
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">' +
                    '<path d="M0 0h24v24H0z" fill="none"/>' +
                    '<path d="M9 20v-6h6v6h5v-8h3L12 3 1 12h3v8z"/>' +
                    '</svg>';

                // Add a click event listener to the home button
                container.addEventListener('click', function() {
                    // Navigate to the desired route
                    window.location.href = '{{ route('high_scale_dashboard') }}';
                });

                return container;
            }
        });

        // Add the home button control to the map
        map.addControl(new homeButton());


        // CSS styling for the home button
        var homeButtonStyle = document.createElement('style');
        homeButtonStyle.innerHTML = '.home-button { \
                                                                                          background-color: #fff; \
                                                                                          color: #000; \
                                                                                          border: 1px solid #000; \
                                                                                          padding: 2px 4px; \
                                                                                          cursor: pointer; \
                                                                                        }';

        // Append the CSS styling to the document head
        document.head.appendChild(homeButtonStyle);
    </script>




</body>

</html>
