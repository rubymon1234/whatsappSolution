@extends('layouts.master')
@section('title', 'Scrub Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: Scrubing List</h6>
        </div>
        @permission('user.compose.scrub.create')
            <a class="btn btn-outline-info pull-right" href="{{ route('user.compose.scrub.create') }}"> Create scrub campaign </a>
        @endpermission
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
                                <th>Name</th>
                                <th>Plan name</th>
                                <th>Instance name</th>
                                <th>Count</th>
                                <th>Registered count</th>
                                <th>Non-registered count</th>
                                <th>Status</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($scrubDetail as $key=>$scrub)
                                <tr>
                                    <td class="serial">{{ $key + $scrubDetail->firstItem()}} </td>
                                    <td> <span class="name">{{ $scrub->scrub_name }}</span> </td>
                                    @php
                                    $planDetail = \App\Helpers\Helper::getPlanDetail(Crypt::encrypt($scrub->current_plan_id));
                                    @endphp
                                    <td> <span class="product">{{ $planDetail->plan_name }}</span> </td>
                                    <td >{{$scrub->instance_name }}</td>
                                    <td ><b style="font-weight: bold;"></b>{{ $scrub->count }} </td>
                                    
                                    <td>{{ $scrub->registered_count }}</td>
                                    <td>{{ $scrub->not_registered_count }}</td>
                                    <td>
                                        @if($scrub->is_status==1)
                                            <span class="badge badge-success">completed</span>
                                        @elseif($scrub->is_status==2)
                                        <span class="badge badge-warning">cancel </span>
                                        @elseif($scrub->is_status==0)
                                            <span class="badge badge-danger">In-progress</span>
                                        @endif
                                    </td>
                                    <td>
                                    @if($scrub->is_status==1)
                                    <a href="{{ asset('uploads/scrubCsv/') }}/{{ $scrub->registered_file }}" class="badge badge-success" download>wh_registered</a>&nbsp;&nbsp;&nbsp;
                                    <a href="{{ asset('uploads/scrubCsv/') }}/{{ $scrub->not_registered_file }}" class="badge badge-success" download>wh_non_registered</a>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9"> No scrub logs in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($scrubDetail->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$scrubDetail->firstItem()}} to {{$scrubDetail->lastItem()}} of {{$scrubDetail->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $scrubDetail->appends(Request::get('qa'))->links() }}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
