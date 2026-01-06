<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DokdesKu - Admin Panel')</title>
    
    <!-- Primary Meta Tags -->
    <meta name="description" content="Sistem Manajemen Dokumen Hukum">
    
    <!-- Volt CSS -->
    @include('layouts.admin.css')
    
    <!-- Custom CSS untuk memperbaiki layout -->
    <style>
        /* Fix untuk sidebar dan content yang bertumpuk */
        .main-content {
            padding-left: 240px; /* Sesuaikan dengan lebar sidebar */
            transition: all 0.3s;
        }
        
        @media (max-width: 991.98px) {
            .main-content {
                padding-left: 0;
            }
        }
        
        /* Pastikan container dashboard tidak melebar */
        .container-fluid {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
            width: 100%;
        }
        
        /* Fix untuk card yang keluar dari container */
        .row {
            margin-right: -15px;
            margin-left: -15px;
        }
        
        .col-1, .col-2, .col-3, .col-4, .col-5, .col-6,
        .col-7, .col-8, .col-9, .col-10, .col-11, .col-12,
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,
        .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12,
        .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6,
        .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12,
        .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6,
        .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12,
        .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6,
        .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12 {
            padding-right: 15px;
            padding-left: 15px;
        }
        
        /* Pastikan sidebar tidak menimpa content */
        .sidebar {
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s;
        }
        
        /* Tambahkan margin top untuk konten */
        .mt-4 {
            margin-top: 1.5rem !important;
        }
        
        /* Styling untuk dashboard */
        .stat-card {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        /* Fix untuk navbar dan sidebar */
        .navbar-vertical.navbar-expand-lg.fixed-left ~ .main-content {
            margin-left: 240px;
        }
        
        /* Responsive fixes */
        @media (max-width: 1199.98px) {
            .main-content {
                padding-left: 0;
            }
            
            .navbar-vertical.navbar-expand-lg.fixed-left ~ .main-content {
                margin-left: 0;
            }
        }
        
        /* Pastikan tidak ada overflow horizontal */
        body {
            overflow-x: hidden;
        }
    </style>
    
    @stack('styles')
</head>

<body>

    
    <!-- MAIN CONTENT -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- TOP NAVBAR -->
        @include('layouts.admin.navbar')
        
        <!-- PAGE CONTENT -->
        <div class="container-fluid py-4">
            @yield('content')
        </div>
    </main>

    @include('layouts.admin.whatsapp')

    <!-- Volt JS -->
    @include('layouts.admin.js')
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('scripts')
</body>

</html>