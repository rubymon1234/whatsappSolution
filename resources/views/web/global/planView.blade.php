@extends('layouts.master')
@section('title', 'Plan Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: List Plans</h6>

        </div>
    </div>
    <div class="hk-pg-header mb-0">
    <div class="d-flex">
            @permission('global.plan.create')
                <a class="btn btn-outline-info" href="{{ route('global.plan.create') }}"> Add Plan </a>
            @endpermission
            &nbsp;&nbsp;
            <form name="searchForm" action="" method="get">
                <label>
                    <input type="text" class="form-control form-control-sm new" placeholder="plan name search" aria-controls="datable_1" name="qa" value="{{ $key }}">
                </label>
                <button class="btn btn-tool btn-danger" name="search" value="search">Search </button>
            </form>
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
                                <th>Plan name</th>
                                <th>Daily count</th>
                                <th>Plan Subscription</th>
                                <th>Scrub count</th>
                                <th>Instance count</th>
                                <th>Plan validity</th>
                                <th>Status</th>
                                @permission('global.plan.update')
                                <th style="text-align: left;">Manage</th>
                                @endpermission
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plans as $key=>$plan)
                                <tr>
                                    <td class="serial">{{ $key + $plans->firstItem()}} </td>
                                    <td> <span class="name">{{ $plan->plan_name }}</span> </td>
                                    <td> <span class="product">{{ $plan->daily_count }}</span> </td>
                                    <td> 
                                    @if($plan->plan_subscription===0)
                                        <span class="badge badge-info">daily</span>
                                    @elseif($plan->plan_subscription===1)
                                       <span class="badge badge-success">monthly</span>
                                    @else
                                       <span class="badge badge-info">daily</span>
                                    @endif </td>
                                    <td> <span class="product">{{ $plan->scrub_count }}</span> </td>
                                    <td> <span class="product">{{ $plan->bot_instance_count }}</span> </td>
                                    <td> <span class="product">{{ $plan->plan_validity }}</span> </td>
                                    <td>
                                        @if($plan->is_status==0)
                                            <span class="badge badge-danger">Not Active</span>
                                        @elseif($plan->is_status==1)
                                           <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    @permission('global.plan.update')
                                    <td>
                                        <a href="{{ route('global.plan.update' ,Crypt::encrypt($plan->id)) }}"><i class="fa fa-edit" data-toggle="tooltip" data-original-title="Edit Plan" ></i></a>&nbsp;&nbsp;
                                    </td>
                                    @endpermission
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"> No Plan in the list</td>
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
</div>
<script type="text/javascript">
    function doubleConfirm(permission_id,url){
        if (confirm('Are you sure you want to block the user ?')){

        }
    }
</script>
@endsection
