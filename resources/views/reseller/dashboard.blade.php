@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row -->
    <div class="row">	
        <div class="col-xl-12">
            <div class="hk-row">
				<div class="col-lg-3 col-sm-6">
					<div class="card card-sm">
						<div class="card-body">
							<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">Total Users </span>
							<div class="d-flex align-items-center justify-content-between position-relative">
								<div>
									<span class="d-block display-5 font-weight-400 text-dark">{{ $dashboardCount['total_users'] }}</span>
								</div>
								<div class="position-absolute r-0">
									<span id="pie_chart_1" class="d-flex easy-pie-chart" data-percent="{{ $dashboardCount['total_users'] }}">
										<span class="percent head-font">{{ $dashboardCount['total_users'] }}</span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="card card-sm">
						<div class="card-body">
							<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">Total Reseller </span>
							<div class="d-flex align-items-center justify-content-between position-relative">
								<div>
									<span class="d-block display-5 font-weight-400 text-dark">{{ $dashboardCount['total_reseller'] }}</span>
								</div>
								<div class="position-absolute r-0">
									<span id="pie_chart_2" class="d-flex easy-pie-chart" data-percent="{{ $dashboardCount['total_reseller'] }}">
										<span class="percent head-font">{{ $dashboardCount['total_reseller'] }}</span>
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