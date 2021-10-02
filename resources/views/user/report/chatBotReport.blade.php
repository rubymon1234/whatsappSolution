@extends('layouts.master')
@section('title', 'Reports Management')
@section('content')
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: Interactive Bot</h6>
        </div>
    </div>
    <div class="">
    {{-- <div class="d-flex"> --}}
        <form name="searchForm" action="" method="get">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="firstName"> </label>
                    <select class="form-control select2" name="instance_id" >
                        <option value="">ALL</option>
                        @foreach($chatInstanceList as $instance)
                            <option value="{{ $instance->token }}" @if($instance_id ==$instance->token) {{ 'selected' }} @endif >{{ $instance->instance_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="lastName">  </label>
                    <select class="form-control custom-select select2 select2" id="combination" name="combination">
                        <option value="">ALL</option>
                        @foreach($combinationList as $key => $comb)
                            <option value="{{ $key }}" @if($combination ==$key) {{ 'selected' }} @endif>{{ $comb }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="lastName">  </label>
                   <input class="form-control" type="text" name="daterange" value="<?php if($daterange){ echo $daterange; } ?>" />
                </div>
                <div class="col-md-3 form-group">
                <button class="btn btn-tool btn-danger" name="search" value="search" style="margin-top: 19px;">Search </button>
                <button class="btn btn-tool btn-info" name="download" value="download" style="margin-top: 19px;">Download </button>
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
                                <th>Instance name</th>
                                <th>Number</th>
                                <th>User Input</th>
                                <th>App name</th>
                                <th>App value</th>
                                <th>Sent time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logSessionList as $key=>$log)
                                <tr>
                                    <td class="serial">{{ $key + $logSessionList->firstItem()}} </td>
                                    <td> <span class="name">{{ $log->instance_name }}</span> </td>
                                    <td><span style="font-weight: bold;">{{ explode("@",$log->number)[0] }}</span></td>
                                    <td >{{ rawurldecode($log->user_input) }}</td>
                                    <td >{{ $log->app_name }}</td>
                                    @php
                                    $appValue = \App\Helpers\Helper::getNextAppNameView(strtolower($log->app_name),Crypt::encryptString($log->app_value));
                                    @endphp
                                    <td >{{ $appValue }}</td>
                                    <td >{{ $log->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"> No Data in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($logSessionList->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$logSessionList->firstItem()}} to {{$logSessionList->lastItem()}} of {{$logSessionList->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $logSessionList->appends(Request::get('number'))->links() }}
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
