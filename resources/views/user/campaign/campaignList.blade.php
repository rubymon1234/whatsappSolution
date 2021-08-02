@extends('layouts.master')
@section('title', 'Messages')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: Sent</h6>
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
                                <th>Campaign name</th>
                                <th>Current plan </th>
                                <th>Instance name</th>
                                <th>Type</th>
                                <th>number count</th>
                                <th>Message</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campaignList as $key=>$campaign)
                                <tr>
                                    <td class="serial">{{ $key + $campaignList->firstItem()}} </td>
                                    <td> <span class="name">{{ $campaign->campaign_name }}</span> </td>
                                    @php
                                    $planDetail = \App\Helpers\Helper::getPlanDetail(Crypt::encrypt($campaign->current_plan_id));
                                    @endphp
                                     <td> <span class="product">{{ $planDetail->plan_name }}</span> </td>
                                    <td >{{$campaign->instance_name }}</td>
                                    <td >{{ $campaign->type }} </td>
                                    <td>
                                        {{ $campaign->count }}
                                    </td>
                                    <td>
                                        {{ rawurlencode($campaign->message) }}
                                    </td>
                                    <td>
                                        @if($campaign->is_status==0)
                                            <span class="badge badge-warning">Queued</span>
                                        @elseif($campaign->is_status==2)
                                        <span class="badge badge-info">Sending</span>
                                        @elseif($campaign->is_status==1)
                                            <span class="badge badge-success">Sent </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"> No campaign logs in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($campaignList->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$campaignList->firstItem()}} to {{$campaignList->lastItem()}} of {{$campaignList->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $campaignList->appends(Request::get('qa'))->links() }}
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
