
<!DOCTYPE html>
<html lang="en" class="h-100">


<!-- Mirrored from techzaa.in/larkon/admin/auth-signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 25 Feb 2025 12:42:45 GMT -->
<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>@yield('title')</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="A fully responsive premium admin dashboard template" />
     <meta name="author" content="Techzaa" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />

     <!-- App favicon -->
     <link rel="shortcut icon" href="{{ asset('admin-assets/images/favicon.ico') }}">

     <!--=============== Bootstrap ===============-->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />

     <!-- Vendor css (Require in all Page) -->
     <link href="{{ asset('admin-assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

     <!-- Icons css (Require in all Page) -->
     <link href="{{ asset('admin-assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

     <!-- App css (Require in all Page) -->
     <link href="{{ asset('admin-assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

     <!-- Theme Config js (Require in all Page) -->
     <script src="{{ asset('admin-assets/js/config.js') }}"></script>
     @stack('styles')
</head>

<body class="h-100">

    @yield('content')

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
    @stack('scripts')
</body>

</html>