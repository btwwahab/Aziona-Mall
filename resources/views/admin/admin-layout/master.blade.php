<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | Larkon - Responsive Admin Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully responsive premium admin dashboard template" />
    <meta name="author" content="Techzaa" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin-assets/images/favicon.ico') }}">
    <!-- Vendor css (Require in all Page) -->
    <link href="{{ asset('admin-assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Icons css (Require in all Page) -->
    <link href="{{ asset('admin-assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App css (Require in all Page) -->
    <link href="{{ asset('admin-assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
    <!-- Theme Config js (Require in all Page) -->
    <script src="{{ asset('admin-assets/js/config.js') }}"></script>

    @stack('styles')
</head>

<body>


    <!-- START Wrapper -->
    <div class="wrapper">

        {{-- Header --}}
        @include('admin.admin-partials.admin-header')

        {{-- Right Sidebar --}}
        @include('admin.admin-partials.right-sidebar')

        {{-- Sidebar Here --}}
        @include('admin.admin-partials.admin-sidebar')

        {{-- Main Content --}}
        @yield('content')

        {{-- Footer --}}
        @include('admin.admin-partials.admin-footer')

    </div>
    <!-- END Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('admin-assets/js/jquery.min.js') }}"></script>
    <!-- Validate JS -->
    <script src="{{ asset('admin-assets/js/jquery-validate.js') }}"></script>
    <!-- Form Validator JS -->
    <script src="{{ asset('admin-assets/js/form-validator.min.js') }}"></script>
    <!-- Vendor Javascript (Require in all Page) -->
    <script src="{{ asset('admin-assets/js/vendor.js') }}"></script>
    <!-- App Javascript (Require in all Page) -->
    <script src="{{ asset('admin-assets/js/app.js') }}"></script>
    <!-- Vector Map Js -->
    <script src="{{ asset('admin-assets/vendor/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/jsvectormap/maps/world.js') }}"></script>
    <!-- Dashboard Js -->
    <script src="{{ asset('admin-assets/js/pages/dashboard.js') }}"></script>
    <!-- Bootstrap Tags Input JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    @stack('scripts')
</body>

</html>
