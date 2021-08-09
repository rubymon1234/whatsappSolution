@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row -->
    <div class="row">	
        <div class="col-xl-12">
            <div class="hk-row">
            	<div class="col-lg-12 col-sm-12">
            	<div class="card card-profile-feed">
					<ul class="list-group list-group-flush">
                        <li class="list-group-item" style="line-height: 28px;">
                        	<span>
                        		Hello <b style="font-weight: bold;">{{ Auth::user()->name }}
                        		</b>, welcome back!<br>
You have logged in from <b style="font-weight: bold;">{{ $_SERVER['REMOTE_ADDR'] }}</b> @ {{ date('l jS \of F Y h:i:s A') }}
                        	</li>
                        	@php
				            $activePlan = \App\Helpers\Helper::getUserActivePlans(Crypt::encrypt(Auth::user()->id));
				            @endphp
				        @if(isset($activePlan['plan_name']))
                        
                        <li class="list-group-item"><span><i class="ion ion-md-home font-18 text-light-20 mr-10"></i><span>Plan name:</span></span><span class="ml-5 text-dark">{{ $activePlan['plan_name'] }}</span></li>
                        <li class="list-group-item"><span><i class="ion ion-md-pin font-18 text-light-20 mr-10"></i><span>Status:</span></span><span class="ml-5 text-dark"><?php echo $activePlan['status'] ?></span></li>
                        <li class="list-group-item"><span><i class="ion ion-md-pin font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark">Messages/Day {{ $activePlan['daily']}}</span></li>
                        @else
                        <li class="list-group-item">
                        	<span>
                        		<i class="ion ion-md-briefcase font-18 text-light-20 mr-10"></i>
                        		<span>WhatsApp Service:</span>
                        	</span>
                        	<span class="ml-5 text-dark">InActive</span>
                        </li>
                        @endif
                    </ul>
				 </div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="card card-sm">
						<div class="card-body">
							<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">Today</span>
							<div class="d-flex align-items-center justify-content-between position-relative">
								<div>
									<span class="d-block display-5 font-weight-400 text-dark">{{ $dashboardCount['today_count'] }}</span>
								</div>
								<div class="position-absolute r-0">
									<span id="pie_chart_1" class="d-flex easy-pie-chart" data-percent="{{ $dashboardCount['today_count'] }}">
										<span class="percent head-font">{{ $dashboardCount['today_count'] }}</span>
									</span>
								</div>
							</div>

						</div>

					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="card card-sm">
						<div class="card-body">
							<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">Yesterday</span>
							<div class="d-flex align-items-center justify-content-between position-relative">
								<div>
									<span class="d-block display-5 font-weight-400 text-dark">{{ $dashboardCount['yesterday_count'] }}</span>
								</div>
								<div class="position-absolute r-0">
									<span id="pie_chart_2" class="d-flex easy-pie-chart" data-percent="{{ $dashboardCount['yesterday_count'] }}">
										<span class="percent head-font">{{ $dashboardCount['yesterday_count'] }}</span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="card card-sm">
						<div class="card-body">
							<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">This Week</span>
							<div class="d-flex align-items-center justify-content-between position-relative">
								<div>
									<span class="d-block display-5 font-weight-400 text-dark">{{ $dashboardCount['this_week_count'] }}</span>
								</div>
								<div class="position-absolute r-0">
									<span id="pie_chart_3" class="d-flex easy-pie-chart" data-percent="{{ $dashboardCount['this_week_count'] }}">
										<span class="percent head-font">{{ $dashboardCount['this_week_count'] }}</span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="card card-sm">
						<div class="card-body">
							<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">Last Week</span>
							<div class="d-flex align-items-center justify-content-between position-relative">
								<div>
									<span class="d-block display-5 font-weight-400 text-dark">{{ $dashboardCount['last_week_count'] }}</span>
								</div>
								<div class="position-absolute r-0">
									<span id="pie_chart_4" class="d-flex easy-pie-chart" data-percent="{{ $dashboardCount['last_week_count'] }}">
										<span class="percent head-font">{{ $dashboardCount['last_week_count'] }}</span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="card card-sm">
						<div class="card-body">
							<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">This Month</span>
							<div class="d-flex align-items-center justify-content-between position-relative">
								<div>
									<span class="d-block display-5 font-weight-400 text-dark">{{ $dashboardCount['this_month'] }}</span>
								</div>
								<div class="position-absolute r-0">
									<span id="pie_chart_5" class="d-flex easy-pie-chart" data-percent="{{ $dashboardCount['this_month'] }}">
										<span class="percent head-font">{{ $dashboardCount['this_month'] }}</span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="card card-sm">
						<div class="card-body">
							<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">Last Month</span>
							<div class="d-flex align-items-center justify-content-between position-relative">
								<div>
									<span class="d-block display-5 font-weight-400 text-dark">{{ $dashboardCount['last_month'] }}</span>
								</div>
								<div class="position-absolute r-0">
									<span id="pie_chart_6" class="d-flex easy-pie-chart" data-percent="{{ $dashboardCount['last_month'] }}">
										<span class="percent head-font">{{ $dashboardCount['last_month'] }}</span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>		
		</div>
    </div>
    <!-- /Row -->
</div>
@endsection