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
							<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">projects</span>
							<div class="d-flex align-items-center justify-content-between position-relative">
								<div>
									<span class="d-block display-5 font-weight-400 text-dark">12+</span>
								</div>
								<div class="position-absolute r-0">
									<span id="pie_chart_1" class="d-flex easy-pie-chart" data-percent="86">
										<span class="percent head-font">86</span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="card card-sm">
						<div class="card-body">
							<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">Budget</span>
							<div class="d-flex align-items-center justify-content-between position-relative">
								<div>
									<span class="d-block">
										<span class="display-5 font-weight-400 text-dark">$<span class="counter-anim">740,260</span></span>
									</span>
								</div>
								<div class="position-absolute r-0">
									<span id="pie_chart_2" class="d-flex easy-pie-chart" data-percent="75">
										<span class="percent head-font">75</span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="card card-sm">
						<div class="card-body">
							<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">Revenue</span>
							<div class="d-flex align-items-end justify-content-between">
								<div>
									<span class="d-block">
										<span class="display-5 font-weight-400 text-dark">$28,725</span>
										<small>excl tax</small>
									</span>
								</div>
								<div>
									<span class="text-success font-12 font-weight-600">+5%</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="card card-sm">
						<div class="card-body">
							<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">Genrated Invoices</span>
							<div class="d-flex align-items-end justify-content-between">
								<div>
									<span class="d-block">
										<span class="display-5 font-weight-400 text-dark">187</span>
									</span>
								</div>
								<div>
									<span class="text-danger font-12 font-weight-600">-12%</span>
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