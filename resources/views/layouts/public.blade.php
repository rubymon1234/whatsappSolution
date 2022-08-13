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
{{-- <li class="nav-item">
                    
                    <a id="settings_toggle_btn" class="nav-link nav-link" href="javascript:void(0);"><i class="zmdi zmdi-balance-wallet"> &nbsp;: @php 
                    if($accounts){echo $accounts->credits;}else{ echo 0;}
                    @endphp</i> </a>
                </li> &nbsp; | 
                <li class="nav-item dropdown dropdown-notifications">
                    <?php
                    $accounts = \App\Helpers\Helper::getCredits(Crypt::encrypt(Auth::user()->id));
                    ?>
                    <a class="nav-link dropdown-toggle no-caret" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="zmdi zmdi-balance-wallet"></span><span class="badge-wrap"><span class="badge badge-success badge-indicator badge-indicator-sm badge-pill pulse"></span></span></a>
                    <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" style="height: 400%;">
                        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                            <div class="notifications-nicescroll-bar" style="overflow: hidden; width: auto;  outline: currentcolor none medium;" tabindex="-50">
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <div class="media">
                                        <div class="media-img-wrap col-md-6">
                                            CHAT BOT
                                        </div>
                                        <div class="media-body col-md-6">
                                            <div>
                                                @php 
                                                    if($accounts){echo $accounts->credits;}else{ echo 0;}
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <div class="media">
                                        <div class="media-img-wrap col-md-6">
                                            API
                                        </div>
                                        <div class="media-body col-md-6">
                                            <div>
                                                @php 
                                                    if($accounts){echo $accounts->api_credits;}else{ echo 0;}
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <div class="media">
                                        <div class="media-img-wrap col-md-6">
                                            RANDOM
                                        </div>
                                        <div class="media-body col-md-6">
                                            <div>
                                                @php 
                                                    if($accounts){echo $accounts->random_credits;}else{ echo 0;}
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                            </div>
                        </div>
                    </div>
                </li> &nbsp; |--}}
