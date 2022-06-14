@extends('layouts.app', ['activePage' => 'maps', 'title' => $companyName.' | Dónde estamos', 'navName' => 'Mapa', 'activeButton' => 'laravel'])

@section('content')
    <div class="map-container">
        <div id="map"></div>
    </div>
    <script>
        $(document).ready(function() {
            function initMap() {
            // TODO: PARAMETRIZE CONSULTORY LOCATION
            const consultory = { lat: -25.344, lng: 131.036 };
            const consParams = { zoom: 4, center: consultory };
            // The map
            const map = new google.maps.Map($('#map'), consParams);
            // The marker
            const markerParams = {
                position: consultory,
                map: map
            };
            const marker = new google.maps.Marker(markerParams);
            }
        }
    </script>
@endsection


    
