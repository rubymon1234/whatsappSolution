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
            $domainDetail = \App\Helpers\Helper::getDomainDetail($_SERVER['SERVER_NAME']);
            @endphp
            <a class="navbar-brand" href="dashboard1.html">
                {{-- <img class="brand-img d-inline-block" src="{{ asset('dist/img/logo-light.png') }}" alt="brand" /> --}}
                <h3 style="color: #273b86; font-weight: bold;">{{ $domainDetail->company_name }}</h3>
            </a>
            <ul class="navbar-nav hk-navbar-content">

                <li class="nav-item dropdown dropdown-notifications">
                    <a class="nav-link dropdown-toggle no-caret" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="feather-icon"><i data-feather="bell"></i></span><span class="badge-wrap"><span class="badge badge-primary badge-indicator badge-indicator-sm badge-pill pulse"></span></span></a>
                    <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                        <h6 class="dropdown-header">Notifications <a href="javascript:void(0);" class="">View all</a></h6>
                        <div class="notifications-nicescroll-bar">
                            <a href="javascript:void(0);" class="dropdown-item">
                                <div class="media">
                                    <div class="media-img-wrap">
                                        <div class="avatar avatar-sm">
                                            <img src="{{ asset('dist/img/avatar1.jpg') }}" alt="user" class="avatar-img rounded-circle">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div>
                                            <div class="notifications-text"><span class="text-dark text-capitalize">Evie Ono</span> accepted your invitation to join the team</div>
                                            <div class="notifications-time">12m</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0);" class="dropdown-item">
                                <div class="media">
                                    <div class="media-img-wrap">
                                        <div class="avatar avatar-sm">
                                            <img src="{{ asset('dist/img/avatar2.jpg') }}" alt="user" class="avatar-img rounded-circle">
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div>
                                            <div class="notifications-text">New message received from <span class="text-dark text-capitalize">Misuko Heid</span></div>
                                            <div class="notifications-time">1h</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0);" class="dropdown-item">
                                <div class="media">
                                    <div class="media-img-wrap">
                                        <div class="avatar avatar-sm">
                                            <span class="avatar-text avatar-text-primary rounded-circle">
													<span class="initial-wrap"><span><i class="zmdi zmdi-account font-18"></i></span></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div>
                                            <div class="notifications-text">You have a follow up with<span class="text-dark text-capitalize"> Mintos head</span> on <span class="text-dark text-capitalize">friday, dec 19</span> at <span class="text-dark">10.00 am</span></div>
                                            <div class="notifications-time">2d</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0);" class="dropdown-item">
                                <div class="media">
                                    <div class="media-img-wrap">
                                        <div class="avatar avatar-sm">
                                            <span class="avatar-text avatar-text-success rounded-circle">
													<span class="initial-wrap"><span>A</span></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div>
                                            <div class="notifications-text">Application of <span class="text-dark text-capitalize">Sarah Williams</span> is waiting for your approval</div>
                                            <div class="notifications-time">1w</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0);" class="dropdown-item">
                                <div class="media">
                                    <div class="media-img-wrap">
                                        <div class="avatar avatar-sm">
                                            <span class="avatar-text avatar-text-warning rounded-circle">
													<span class="initial-wrap"><span><i class="zmdi zmdi-notifications font-18"></i></span></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div>
                                            <div class="notifications-text">Last 2 days left for the project</div>
                                            <div class="notifications-time">15d</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>
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
                        <a class="dropdown-item" href="profile.html"><i class="dropdown-icon zmdi zmdi-account"></i><span>Profile</span></a>
                        <a class="dropdown-item" href="#"><i class="dropdown-icon zmdi zmdi-card"></i><span>My balance</span></a>

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
    <script src="{{ asset('dist/js/excanvas.min.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.crosshair.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.flot.tooltip.min.js') }}"></script>

	<!-- EChartJS JavaScript -->
    <script src="{{ asset('dist/js/echarts-en.min.js') }}"></script>

    <!-- Init JavaScript -->
    <script src="{{ asset('dist/js/init.js') }}"></script>
	<script src="{{ asset('dist/js/dashboard2-data.js') }}"></script>

</body>
</html>
