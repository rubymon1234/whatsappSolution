@extends('layouts.master')
@section('title', 'Message')
@section('content')
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: List</h6>
        </div>
    </div>
    <div class="">
    {{-- <div class="d-flex"> --}}
        <form name="searchForm" action="" method="get">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="firstName"> </label>
                    <select class="form-control select2" name="combination">
                        @foreach($combinationList as $key => $combination)
                            <option <?php echo Request::get('combination') == $key ? 'selected' : '' ?> value="{{ $key }}">{{ $combination }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="lastName">  </label>
                    <input type="text" class="form-control form-control-sm new" placeholder="name" aria-controls="datable_1" name="name" value="{{ Request::get('name') }}">
                </div>
                <div class="col-md-3 form-group">
                <button class="btn btn-tool btn-danger" name="search" value="search" style="margin-top: 19px;">Search </button>
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
                                <th>Name</th>
                                <th>App Name</th>
                                <th>Message</th>
                                <th>Combination Type</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messageList as $key=>$data)
                                <tr>
                                    <td class="serial">{{ $key + $messageList->firstItem()}} </td>
                                    <td> <span class="name">{{ $data->name }}</span> </td>
                                    <td><span style="font-weight: bold;">{{ $data->app_name }}</span></td>
                                    <td >{{ $data->message }}</td>
                                    <td >{{ $data->type }}</td>
                                    <td >{{ $data->created_at }}</td>
                                    <td ><a href="#" class="btn btn-info">Edit</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"> No Data in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($messageList->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$messageList->firstItem()}} to {{$messageList->lastItem()}} of {{$messageList->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $messageList->appends(Request::get('name'))->links() }}
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
