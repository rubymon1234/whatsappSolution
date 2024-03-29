@extends('layouts.master')
@section('title', 'Credit Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: List Requests</h6>
        </div>
    </div>
    <style type="text/css">
        .form-control-sm, .custom-select-sm {
            height: calc(1.8125rem + 11px);
        }
        label{
            margin-bottom: 0px ! important;
        }
    </style>
    <div class="row">
        <div class="col-xl-12">
            <div class="hk-row">
                <div class="table-responsive" style="padding: 1%;">
                    <table class="table table-hover table-bordered mb-0">
                        <thead>
                            <tr>
                                <th >#</th>
                                <th >Username</th>
                                <th >Reseller Name</th>
                                <th>Plan Name</th>
                                <th>Count</th>
                                <th>Plan Subscription</th>
                                <th>Scrub Count</th>
                                <th>Instance Count</th>
                                <th>Plan Validity(days)</th>
                                <th>Request Time</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plans as $key=>$plan)
                                <tr>
                                    <td class="serial">{{ $key + $plans->firstItem()}} </td>
                                    <td> <span class="name">{{ $plan->user_name }}</span> </td>
                                    @php
                                    $resellerDetail = \App\Helpers\Helper::getUserDetail(Crypt::encrypt($plan->reseller_id));
                                    @endphp
                                    <td> <span style="font-weight: bold;">{{ $resellerDetail->name }}
                                    </span> </td>
                                    <td> <span class="name">{{ $plan->plan_name }}</span> </td>
                                    
                                     <td> <span class="product">{{ $plan->daily_count }}</span> </td>
                                     <td> @if($plan->plan_subscription===0)
                                        <span class="badge badge-info">daily</span>
                                    @elseif($plan->plan_subscription===1)
                                       <span class="badge badge-success">monthly</span>
                                    @else
                                       <span class="badge badge-info">daily</span>
                                    @endif </td>
                                     <td> <span class="product">{{ $plan->scrub_count }}</span> </td>
                                     <td> <span class="product">{{ $plan->bot_instance_count }}</span> </td>
                                    <td >{{$plan->plan_validity }}</td>
                                    <td>
                                        {{ $plan->created_at}}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary " data-toggle="modal" data-target="#exampleModalPopovers_{{ $plan->id}}">
                                           view
                                        </button>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"> No Request in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($plans->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$plans->firstItem()}} to {{$plans->lastItem()}} of {{$plans->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $plans->appends(Request::get('qa'))->links() }}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
    @forelse($plans as $key=>$plan)
    <div class="modal fade" id="exampleModalPopovers_{{ $plan->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        @php
        $planDetail = \App\Helpers\Helper::getPlanDetail(Crypt::encrypt($plan->plan_id));
        @endphp
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Plan Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Plan name </h6>
                    <p class="mt-10 mb-20"> {{ $planDetail->plan_name }}</p>
                    <h6>Count</h6>
                    <p class="mt-10"> {{ $planDetail->daily_count }} </p>
                    <h6>Plan Subscription</h6>
                    <p class="mt-10">  @if($planDetail->plan_subscription===0)
                                        <span class="badge badge-info">daily</span>
                                    @elseif($planDetail->plan_subscription===1)
                                       <span class="badge badge-success">monthly</span>
                                    @else
                                       <span class="badge badge-info">daily</span>
                                    @endif </p>
                    <h6>Scrub Count</h6>
                    <p class="mt-10"> {{ $planDetail->scrub_count }} </p>
                    <h6>Instance Count</h6>
                    <p class="mt-10"> {{ $planDetail->bot_instance_count }} </p>
                    <h6>Plan Validity</h6>
                    <p class="mt-10"> {{ $planDetail->plan_validity }} </p>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="__appActions('{{ Crypt::encryptString($plan->id) }}',1)">Approve</button>
                    <button type="submit" class="btn btn-danger" onclick="__appActions('{{ Crypt::encryptString($plan->id) }}',3)">Reject</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @empty
    @endforelse
</div>
<script type="text/javascript">
    function __appActions(plan_req_id,status){
        if(status ==1){
            res = 'Approve';
        }else{
            res = 'Reject';
        }
        if(typeof  plan_req_id !=='undefined' && plan_req_id !=''){

            $.ajax(
                {
                    url: '{{ route('ajax.request.approve') }}',
                    dataType: 'json', // what to expect back from the PHP script
                    cache: false,
                    data: { _token: "{{ csrf_token() }}", plan_req_id : plan_req_id , status : status ,res : res } ,
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
