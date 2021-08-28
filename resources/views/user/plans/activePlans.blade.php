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
								{{-- <div class="w-120">
									<h6 class="mb-5">Plan name : {{ $crtplan->plan_name }}</h6>
									<p> plan Validity : <h6> @if($crtplan->current_validity) {{ $crtplan->current_validity }} @else {{ $crtplan->plan_validity }} Days @endif</h6></p>
									<hr>
									<p> daily count : <h6>@if($crtplan->daily_count) {{ $crtplan->daily_count }} @else {{ $crtplan->daily_count }} Days @endif</h6></p>
								</div> --}}
                                <table class="table table-hover table-bordered mb-0">
                                    <thead>
                            <tr>
                                <th colspan="2" style="text-align: center;">{{ $crtplan->plan_name }}</th>
                            </tr>
                        </thead>
                         <tr>
                            <td>plan validity </td>
                            <td>@if($crtplan->current_validity) {{ $crtplan->current_validity }} @else {{ $crtplan->plan_validity }} Days @endif</td>
                            </tr>
                            <tr>
                            <td>daily count</td>
                            <td>@if($crtplan->daily_count) {{ $crtplan->daily_count }} @else {{ $crtplan->daily_count }} Days @endif</td>
                        </tr>
                        <tr>
                            <td>scrub count</td>
                            <td>@if($crtplan->scrub_count) {{ $crtplan->scrub_count }} @else {{ $crtplan->scrub_count }} @endif</td>
                        </tr>
                        <tr>
                            <td>Bot Instance count</td>
                            <td>@if($crtplan->bot_instance_count) {{ $crtplan->bot_instance_count }} @else {{ $crtplan->bot_instance_count }} @endif</td>
                        </tr>
                         </tr>
                                </table>
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
                             		<button class="btn btn-xs btn-success ml-15 w-sm-90p">Active</button>
                             	@elseif($crtplan->is_status ==2)
                            	<button class="btn btn-xs btn-info ml-15 w-sm-90p" onclick="__planChange({{ $crtplan->id }},1)">Start</button>
                            	@elseif($crtplan->is_status ==0)
                            	<button class="btn btn-xs btn-danger ml-15 w-sm-90p" onclick="__planChange({{ $crtplan->id }},1)">Activate </button>
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
<style type="text/css">
    .card-body {
        padding: 0.0rem ! important;
    }
</style>
<script type="text/javascript">
    function __planChange(curr_plan_id,status){
        if(typeof  curr_plan_id !=='undefined' && curr_plan_id !=''){

            $.ajax(
                {
                    url: '{{ route('ajax.current.status.change') }}',
                    dataType: 'json', //what to expect back from the PHP script
                    cache: false,
                    data: { _token: "{{ csrf_token() }}", curr_plan_id : curr_plan_id , status : status } ,
                    type: 'POST' ,
                    beforeSend: function () {
                        $('.preloader-it').show();
                    },
                    complete: function () {
                        $('.preloader-it').hide();
                    },
                    success: function (result) {
                        $('#loading').hide();
                        if(result.success){
                            $('.preloader-it').hide();
                            //alert(result.response);
                            location.reload();
                        }
                    },
                    error: function (response) {
                        console.log('Server error');
                    }
                }
            );
        }
    }
</script>
@endsection