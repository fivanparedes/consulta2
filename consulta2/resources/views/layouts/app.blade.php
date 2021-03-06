<!-- 
=========================================================
 Light Bootstrap Dashboard - v2.0.1
=========================================================

 Product Page: https://www.creative-tim.com/product/light-bootstrap-dashboard
 Copyright 2019 Creative Tim (https://www.creative-tim.com) & Updivision (https://www.updivision.com)
 Licensed under MIT (https://github.com/creativetimofficial/light-bootstrap-dashboard/blob/master/LICENSE)

 Coded by Creative Tim & Updivision

=========================================================

 The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.  -->
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('light-bootstrap/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('light-bootstrap/img/favicon.ico') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>{{ $title }}</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href="{{ asset('light-bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('light-bootstrap/css/light-bootstrap-dashboard.css?v=2.0.0') }} " rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/locale/es.js'></script>
    @if ($activePage == 'role_permissions')
        <link href="{{ asset(mix('laratrust.css', 'vendor/laratrust')) }}" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    @endif
    @yield('head')
</head>

<body>
    @php
        /** Esto soluciona un bug del (template?) en donde no se ve ni el sidebar ni el nav autenticado */
        $wtfpages = [
            'medical_histories',
            'statistics',
            'patient_events',
            'professional_show',
            'institutions'
    ];
     $companyLogo =  DB::table('settings')->where('name', 'company-logo')->first(['value']);
     $companyLogo = $companyLogo->value;
     $companyName = DB::table('settings')->where('name', 'company-name')->first(['value']);
     $companyName = $companyName->value;
    @endphp
    <div
        class="wrapper @if ((!auth()->check() ||
    request()->route()->getName() == '') && !in_array($activePage, $wtfpages))
            wrapper-full-page  
    @endif">

        @if ((auth()->check() &&
    request()->route()->getName() != '') || in_array($activePage, $wtfpages))
            @include('layouts.navbars.sidebar')
            @include('pages/sidebarstyle')
        @endif

        <div class="@if ((auth()->check() &&
    request()->route()->getName() != '') || in_array($activePage, $wtfpages)) main-panel 
    @endif">
            @include('layouts.navbars.navbar')
            @yield('content')
            @include('layouts.footer.nav')
        </div>

    </div>



</body>
<!--   Core JS Files   -->
{{-- <script src="{{ asset('light-bootstrap/js/core/jquery.3.2.1.min.js') }}" type="text/javascript"></script> --}}
<script src="{{ asset('light-bootstrap/js/core/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('light-bootstrap/js/core/bootstrap.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('light-bootstrap/js/plugins/jquery.sharrre.js') }}"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="{{ asset('light-bootstrap/js/plugins/bootstrap-switch.js') }}"></script>
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key="></script>
<!--  Chartist Plugin  -->
<script src="{{ asset('light-bootstrap/js/plugins/chartist.min.js') }}"></script>
<!--  Notifications Plugin    -->
<script src="{{ asset('light-bootstrap/js/plugins/bootstrap-notify.js') }}"></script>
<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="{{ asset('light-bootstrap/js/light-bootstrap-dashboard.js?v=2.0.0') }}" type="text/javascript"></script>
<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
<script src="{{ asset('light-bootstrap/js/demo.js') }}"></script>
@stack('js')

</html>
