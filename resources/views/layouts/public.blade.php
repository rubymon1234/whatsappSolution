<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title> @yield('title') </title>
    <meta name="description" content="Whatsapp Messaging System" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.gif') }}">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Custom CSS -->
    <link href="{{ asset('dist/css/style.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
    <!-- Preloader -->
    <div class="preloader-it">
        <div class="loader-pendulums"></div>
    </div>
    <!-- /Preloader -->

	<!-- HK Wrapper -->
	<div class="hk-wrapper">
        <!-- Main Content -->
            @yield('content')
        <!-- /Main Content -->
    </div>
	<!-- /HK Wrapper -->
    <!-- jQuery -->
    <script src="{{ asset('dist/js/jquery.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('dist/js/popper.min.js') }}"></script>
    <script src="{{ asset('dist/js/bootstrap.min.js') }}"></script>

    <!-- Slimscroll JavaScript -->
    <script src="{{ asset('dist/js/jquery.slimscroll.js') }}"></script>

    <!-- Fancy Dropdown JS -->
    <script src="{{ asset('dist/js/dropdown-bootstrap-extended.js') }}"></script>

    <!-- Owl JavaScript -->
    <script src="{{ asset('dist/js/owl.carousel.min.js') }}"></script>

    <!-- FeatherIcons JavaScript -->
    <script src="{{ asset('dist/js/feather.min.js') }}"></script>

    <!-- Init JavaScript -->
    <script src="{{ asset('dist/js/init.js') }}"></script>
    <script src="{{ asset('dist/js/login-data.js') }}"></script>
</body>
</html>
