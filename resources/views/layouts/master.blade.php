<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>@yield('title')</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.gif') }}">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

	<!-- Morris Charts CSS -->
    <link href="{{ asset('dist/css/morris.css') }}" rel="stylesheet" type="text/css" />

    <!-- Toggles CSS -->
    <link href="{{ asset('dist/css/toggles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dist/css/toggles-light.css') }}" rel="stylesheet" type="text/css">

	<!-- Toastr CSS -->
    {{-- <link href="{{ asset('dist/css/jquery.toast.min.css') }}" rel="stylesheet" type="text/css"> --}}
    <!-- Daterangepicker CSS -->
    <link href="{{ asset('dist/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
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
	<div class="hk-wrapper hk-vertical-nav">

        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-xl navbar-light fixed-top hk-navbar">
            <a id="navbar_toggle_btn" class="navbar-toggle-btn nav-link-hover" href="javascript:void(0);"><span class="feather-icon"><i data-feather="menu"></i></span></a>
            @php
            $domain = \App\Helpers\Helper::getFqdn($_SERVER['SERVER_NAME']);
            $domainDetail = \App\Helpers\Helper::getDomainDetail($domain);
            @endphp
            <a class="navbar-brand" href="#">
                {{-- <img class="brand-img d-inline-block" src="{{ asset('dist/img/logo-light.png') }}" alt="brand" /> --}}
                <h3 style="color: #273b86; font-weight: bold;">{{ $domainDetail->company_name }}</h3>
            </a>
            <ul class="navbar-nav hk-navbar-content">
                <li class="nav-item">
                    <?php
                    $Hour = date('H');
                    $Minutes = date('i');
                    $Seconds = date('s');
                    $result = strtotime($Hour.':'.$Minutes.':'.$Seconds) - strtotime('today');
                    ?>
                    <input type="hidden" value="{{ $result }}" id="hr_mn_se_seconnds" />
                    <span style="font-size: 15px;">{{date('d-m-Y')}} <span id="hr_mn_se_timer">{{ date('H:i:s') }}</span></span>
                </li> &nbsp;&nbsp; |
                <li class="nav-item">
                    <?php
                    $accounts = \App\Helpers\Helper::getCredits(Crypt::encrypt(Auth::user()->id));
                    ?>

                    <a id="settings_toggle_btn" class="nav-link nav-link" href="javascript:void(0);"><i class="zmdi zmdi-balance-wallet"> &nbsp;: @php 
                    if($accounts){echo $accounts->credits;}else{ echo 0;}
                    @endphp</i> </a>
                </li> &nbsp; |
                <li class="nav-item dropdown dropdown-authentication">
                    <a class="nav-link dropdown-toggle no-caret" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @php $user = Auth::user() @endphp
                        <div class="media">
                            <div class="media-img-wrap">
                                <div class="avatar">
                                    <span class="avatar-text avatar-text-inv-primary rounded-circle"><span class="initial-wrap" style="margin-top: -31% ! important;">
                                        <span>
                                            {{ substr($user->name, 0, 2) }}
                                        </span>
                                    </span>
                                </div>
                                <span class="badge badge-success badge-indicator"></span>
                            </div>
                            <div class="media-body">
                                <span>{{ Auth::user()->name }} | {{ $user->roles->first()->display_name }}<i class="zmdi zmdi-chevron-down"></i></span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
                        @permission('global.my.profile')
                        <a class="dropdown-item" href="{{ route('global.my.profile')}}"><i class="dropdown-icon zmdi zmdi-account"></i><span>Profile</span></a>
                        @endpermission
                        @permission(('user.plan.my.plans'))
                        <a class="dropdown-item" href="{{ route('user.plan.my.plans') }}"><i class="dropdown-icon zmdi zmdi-card"></i><span>My Plans</span></a>
                        @endpermission
                        <div class="dropdown-divider"></div>
                        <div class="sub-dropdown-menu show-on-hover">
                            <a href="#" class="dropdown-toggle dropdown-item no-caret"><i class="zmdi zmdi-check text-success"></i>Online</a>
                            <div class="dropdown-menu open-left-side">
                                <a class="dropdown-item" href="#"><i class="dropdown-icon zmdi zmdi-check text-success"></i><span>Online</span></a>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="dropdown-icon zmdi zmdi-power"></i><span>{{ __('Logout') }}</span></a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                    </div>
                </li>
            </ul>
        </nav>
        <form role="search" class="navbar-search">
            <div class="position-relative">
                <a href="javascript:void(0);" class="navbar-search-icon"><span class="feather-icon"><i data-feather="search"></i></span></a>
                <input type="text" name="example-input1-group2" class="form-control" placeholder="Type here to Search">
                <a id="navbar_search_close" class="navbar-search-close" href="#"><span class="feather-icon"><i data-feather="x"></i></span></a>
            </div>
        </form>
        <!-- Top Navbar -->

        <!-- Vertical Nav -->
            @include('layouts.sidemenu.navmenu')
        <!-- Vertical Nav -->

        <!-- Main Content -->
        <div class="hk-pg-wrapper">
			<!-- Container -->
                @yield('content')
            <!-- Container -->

            <!-- Footer -->
            <div class="hk-footer-wrap container-fluid">
                <footer class="footer">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <p> Powered by <a href="http://{{ $_SERVER['SERVER_NAME'] }}" class="text-dark" target="_blank">{{ $domainDetail->company_name }}</a> © {{ date('Y')}}</p>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- Footer -->
        </div>
        <!-- Main Content -->

    </div>
    <!-- HK Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('dist/js/jquery.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('dist/js/popper.min.js') }}"></script>
    <script src="{{ asset('dist/js/bootstrap.min.js') }}"></script>

    <!-- Slimscroll JavaScript -->
    <script src="{{ asset('dist/js/jquery.slimscroll.js') }}"></script>

    <!-- Fancy Dropdown JS -->
    <script src="{{ asset('dist/js/dropdown-bootstrap-extended.js') }}"></script>

    <!-- FeatherIcons JavaScript -->
    <script src="{{ asset('dist/js/feather.min.js') }}"></script>

    <!-- Toggles JavaScript -->
    <script src="{{ asset('dist/js/toggles.min.js') }}"></script>
    <script src="{{ asset('dist/js/toggle-data.js') }}"></script>

	<!-- Toastr JS -->
    {{-- <script src="{{ asset('dist/js/jquery.toast.min.js') }}"></script> --}}

	<!-- Counter Animation JavaScript -->
	<script src="{{ asset('dist/js/jquery.waypoints.min.js') }}"></script>
	<script src="{{ asset('dist/js/jquery.counterup.min.js') }}"></script>

	<!-- Morris Charts JavaScript -->
    <script src="{{ asset('dist/js/raphael.min.js') }}"></script>
    <script src="{{ asset('dist/js/morris.min.js') }}"></script>

	<!-- Easy pie chart JS -->
    <script src="{{ asset('dist/js/jquery.easypiechart.min.js') }}"></script>

	<!-- Flot Charts JavaScript -->
    {{-- <script src="{{ asset('dist/js/excanvas.min.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.crosshair.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.tooltip.min.js') }}"></script> --}}

	<!-- EChartJS JavaScript -->
    <script src="{{ asset('dist/js/echarts-en.min.js') }}"></script>

    <!-- Init JavaScript -->
    <script src="{{ asset('dist/js/init.js') }}"></script>
	<script src="{{ asset('dist/js/dashboard2-data.js') }}"></script>

    {{-- DatePicker --}}
    <script src="{{ asset('dist/js/moment.min.js') }}"></script>
    <script src="{{ asset('dist/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('dist/js/daterangepicker-data.js') }}"></script>
    <script type="text/javascript">
        var timerVar = setInterval(countTimer, 1000);
        var totalSeconds = document.getElementById('hr_mn_se_seconnds').value;

    function countTimer() {
        ++totalSeconds;
        var hour = Math.floor(totalSeconds /3600);
        var minute = Math.floor((totalSeconds - hour*3600)/60);
        var seconds = totalSeconds - (hour*3600 + minute*60);
        if(hour.toString().length == 1)
        {
            var hour='0' + hour;
        }
        if(minute.toString().length == 1)
        {
            var minute='0' + minute;
        }
        if(seconds.toString().length == 1)
        {
            var seconds='0' + seconds;
        }
        document.getElementById("hr_mn_se_timer").innerHTML = hour + ":" + minute + ":" + seconds;
    }
    </script>
</body>
</html>
