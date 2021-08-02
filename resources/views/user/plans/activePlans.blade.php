@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row -->
    <div class="row">	
        <div class="col-xl-12">
            <div class="hk-row">
				<div class="col-lg-12 col-sm-6">
					@forelse($currentPlan as $key=>$crtplan)
					<div class="card d-inline-block w-sm-320p">
						
                        <div class="card-body">
                        	
							<div class="d-flex flex-wrap">
								{{-- <img class="d-86 rounded mb-15 mr-15" src="dist/img/img-thumb1.jpg" alt="thumb"> --}}
								<div class="w-120">
									<h6 class="mb-5">Plan name : {{ $crtplan->plan_name }}</h6>
									<p> plan Validity : <h6> @if($crtplan->current_validity) {{ $crtplan->current_validity }} @else {{ $crtplan->plan_validity }} Days @endif</h6></p>
									<hr>
									<p> daily count : <h6>@if($crtplan->daily_count) {{ $crtplan->daily_count }} @else {{ $crtplan->daily_count }} Days @endif</h6></p>
								</div>
							</div>
							
						</div>

                        <div class="card-footer text-muted justify-content-between">
                            <div><span class="text-dark"></span></div>
                            <?php $today_date = date('Y-m-d'); 
	                            if($crtplan->current_validity ==NULL){
	                            	$current_validity = $today_date; 
	                            }else{
	                            	$current_validity = $crtplan->current_validity;
	                            }
                            ?>
                             @if($current_validity >= $today_date )
                             	@if($crtplan->is_status ==1)
                             		<button class="btn btn-xs btn-success ml-15 w-sm-100p">Active</button>
                             	@elseif($crtplan->is_status ==2)
                            	<button class="btn btn-xs btn-info ml-15 w-sm-100p">Start</button>
                            	@elseif($crtplan->is_status ==0)
                            	<button class="btn btn-xs btn-danger ml-15 w-sm-100p">Activate</button>
                            	@endif
                            @else
                            <button class="btn btn-xs btn-danger ml-15 w-sm-100p">Expired</button>
                            @endif
							
						</div>
						
                    </div>
                    @empty
                                 No Plan logs in the list
                            @endforelse
				</div>
			</div>		
		</div>
    </div>
    <!-- /Row -->
</div>
@endsection