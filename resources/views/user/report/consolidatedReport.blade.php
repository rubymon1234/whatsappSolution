@extends('layouts.master')
@section('title', 'User Management')
@section('content')
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: List Users</h6>
        </div>
    </div>
    <div class="">
    {{-- <div class="d-flex"> --}}
        <form name="searchForm" action="" method="get">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="firstName">Campaign </label>
                    <select class="form-control select2" name="campaign_id">
                        <option value="">Select Campaign</option>
                        @foreach($campaignList as $campaign)
                            <option value="{{ $campaign->id }}" <?php if($campaign->id ==$campaign_id){ echo 'selected'; } ?>>{{ $campaign->campaign_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="lastName"> number </label>
                    <input type="text" class="form-control form-control-sm new" placeholder="number" aria-controls="datable_1" name="number" value="{{ $number }}">
                </div>
                <div class="col-md-3 form-group">
                <button class="btn btn-tool btn-danger" name="search" value="search" style="margin-top: 19px;">Search </button>
                <button class="btn btn-tool btn-info" name="search" value="search" style="margin-top: 19px;">Download </button>
                </div>
            </div>
        </form>
        {{-- </div> --}}
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
                                <th>#</th>
                                <th>campaign name</th>
                                <th>Number</th>
                                <th>Instance_token</th>
                                <th>Type</th>
                                <th>message</th>
                                <th>Sent time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sentList as $key=>$sent)
                                <tr>
                                    <td class="serial">{{ $key + $sentList->firstItem()}} </td>
                                    <td> <span class="name">{{ $sent->campaign_name }}</span> </td>
                                    <td><span style="font-weight: bold;">{{ $sent->number }}</span></td>
                                    <td >{{ $sent->instance_token }}</td>
                                    <td >{{ $sent->type }}</td>
                                    <td ><?php
                                        $string = strip_tags(rawurldecode($sent->message));
                                        if (strlen($string) > 10) {

                                            // truncate string
                                            $stringCut = substr($string, 0, 10);
                                            $endPoint = strrpos($stringCut, ' ');
                                            //if the string doesn't contain any space then it will cut without word basis.
                                            $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                            $string .= '... <i class="fa fa-eye" data-toggle="tooltip" data-original-title="'.rawurldecode($sent->message).'" ></i>';
                                        }
                                        ?>
                                        <?php  echo $string ?></td>
                                    <td >{{ strtotime($sent->sent_time) }}</td>
                                    <td>
                                        @if($sent->is_status==0)
                                            <span class="badge badge-danger">Not sent</span>
                                        @elseif($sent->is_status==1)
                                           <span class="badge badge-success">sent</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"> No Data in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($sentList->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$sentList->firstItem()}} to {{$sentList->lastItem()}} of {{$sentList->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $sentList->appends(Request::get('number'))->links() }}
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
<style type="text/css">
    .select2-container .select2-selection--single {
        height: 40px ! important;
    }
</style>
<script src="{{ asset('dist/js/jquery.min.js') }}"></script>
<script src="{{ asset('dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('dist/js/select2-data.js') }}"></script>
<script src="{{ asset('dist/js/custom-script.js') }}"></script>
@endsection
