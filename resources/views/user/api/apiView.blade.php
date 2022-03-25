@extends('layouts.master')
@section('title', 'Api Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: List Api Key's</h6>
        </div>
         @permission('api.key.create')
        <a class="btn btn-outline-info pull-right" href="{{ route('api.key.create') }}"> Create Api Key </a>
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
                                <th>apiKey</th>
                                <th>Instance</th>
                                <th>Status </th>
                                <th>created</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($apiList as $key=> $campaign)
                                <tr>
                                    <td class="serial">{{ $key + $apiList->firstItem()}} </td>
                                    <td> <span class="name"></span> </td>
                                    <td> <span class="name"></span> </td>
                                    <td> <span class="name"></span> </td>
                                    <td> <span class="name"></span> </td>
                                    <td> <span class="name"></span> </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"> No api key in the list...</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($apiList->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$apiList->firstItem()}} to {{$apiList->lastItem()}} of {{$apiList->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $apiList->appends(Request::get('qa'))->links() }}
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
