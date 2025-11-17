<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | POSPAY</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/dashboard-assets/images/favicon.ico') }}">
    <!-- remix icon font css  -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/remixicon.css') }}">
    <!-- BootStrap css -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/bootstrap.min.css') }}">
    <!-- Apex Chart css -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/apexcharts.css') }}">
    <!-- Data Table css -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/dataTables.min.css') }}">
    <!-- Text Editor css -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/editor-katex.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/editor.atom-one-dark.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/editor.quill.snow.css') }}">
    <!-- Date picker css -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/flatpickr.min.css') }}">
    <!-- Calendar css -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/full-calendar.css') }}">
    <!-- Vector Map css -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/jquery-jvectormap-2.0.5.css') }}">
    <!-- Popup css -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/magnific-popup.css') }}">
    <!-- Slick Slider css -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/slick.css') }}">
    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/prism.css') }}">
    <!-- file upload css -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/file-upload.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/lib/audioplayer.css') }}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/js/lib/file-uploader/css/jquery.dm-uploader.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/js/lib/file-uploader/css/styles.css') }}"/>

    <link href="{{ asset('assets/dashboard-assets/js/lib/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Toast message -->
    <link href="{{ asset('assets/dashboard-assets/js/lib/toast/toastr.css') }}" rel="stylesheet" type="text/css" />
    <!-- Toast message -->
    
    <link rel="stylesheet" href="{{ asset('assets/dashboard-assets/vendors/sweetalert/sweetalert.css') }}" />

    <style>
        .select2 .span, .selection{
            width: 100% !important;
            height: 2.75rem !important;
        }
        .selection .select2-selection--single{
            height: 2.75rem !important;
            padding-top: 7px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px !important;
        }
    </style>

    <style>
        .main-menu .nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            color: #333;
            padding: 8px 14px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .main-menu .nav-link:hover {
            background-color: #f1f5ff;
            color: #007bff;
        }

        .main-menu .nav-link.active {
            background-color: #007bff;
            color: #fff;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }

        .main-menu .menu-icon {
            font-size: 20px;
            line-height: 1;
        }

        .dropdown-menu .dropdown-item {
            border-radius: 6px;
            padding: 8px 14px;
            transition: background-color 0.2s;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #f0f8ff;
        }

        @media (max-width: 991px) {
            .main-menu {
                text-align: center;
            }

            .main-menu .nav-link {
                justify-content: center;
            }
        }

        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            display: none;
            position: absolute;
            top: 0;
            left: 100%;
        }


    </style>



    @yield('css')
</head>