<!--
=========================================================
* Volt Pro - Premium Bootstrap 5 Dashboard
=========================================================

* Product Page: https://themesberg.com/product/admin-dashboard/volt-bootstrap-5-dashboard
* Copyright 2021 Themesberg (https://www.themesberg.com)
* License (https://themes.getbootstrap.com/licenses/)

* Designed and coded by https://themesberg.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. Please contact us to request a removal.
-->
<!DOCTYPE html>
<html lang="en">

<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="Admin Dashboard">
    <meta name="author" content="Your Company">
    <meta name="description" content="Admin Dashboard System">
    <meta name="keywords" content="bootstrap 5, admin dashboard, dashboard" />
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets-admin/img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets-admin/img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets-admin/img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets-admin/img/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('assets-admin/img/favicon/safari-pinned-tab.svg') }}" color="#ffffff">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Sweet Alert -->
    <link type="text/css" href="{{ asset('assets-admin/vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">

    <!-- Notyf -->
    <link type="text/css" href="{{ asset('assets-admin/vendor/notyf/notyf.min.css') }}" rel="stylesheet">

    <!-- Volt CSS -->
    <link type="text/css" href="{{ asset('assets-admin/css/volt.css') }}" rel="stylesheet">

    <!-- NOTICE: You can use the _analytics.html partial to include production code specific code & trackers -->

</head>

<body>

    <!-- NOTICE: You can use the _analytics.html partial to include production code specific code & trackers -->
    
    @yield('content')

    <!-- Core -->
    <script src="{{ asset('assets-admin/vendor/@popperjs/core/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Vendor JS -->
    <script src="{{ asset('assets-admin/vendor/onscreen/dist/on-screen.umd.min.js') }}"></script>

    <!-- Slider -->
    <script src="{{ asset('assets-admin/vendor/nouislider/distribute/nouislider.min.js') }}"></script>

    <!-- Smooth scroll -->
    <script src="{{ asset('assets-admin/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js') }}"></script>

    <!-- Charts -->
    <script src="{{ asset('assets-admin/vendor/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>

    <!-- Datepicker -->
    <script src="{{ asset('assets-admin/vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>

    <!-- Sweet Alerts 2 -->
    <script src="{{ asset('assets-admin/vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    <!-- Moment JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>

    <!-- Vanilla JS Datepicker -->
    <script src="{{ asset('assets-admin/vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>

    <!-- Notyf -->
    <script src="{{ asset('assets-admin/vendor/notyf/notyf.min.js') }}"></script>

    <!-- Simplebar -->
    <script src="{{ asset('assets-admin/vendor/simplebar/dist/simplebar.min.js') }}"></script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Volt JS -->
    <script src="{{ asset('assets-admin/assets/js/volt.js') }}"></script>

</body>

</html>