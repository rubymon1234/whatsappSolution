@extends('layouts.master')
@section('title', 'Interactive Bot')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <h6 class="hk-sec-title">@yield('title') :: List Instance
        @permission('user.chat.bot.instance.create')
        <a class="btn btn-outline-info pull-right" href="{{ route('user.chat.bot.instance.create') }}"> Assign Instance </a>
        @endpermission
    </h6>
    <p class="mb-20"></p>
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
                                <th>name</th>
                                <th>Instance token</th>
                                <th>Plan name</th>
                                <th>App name</th>
                                <th>App value</th>
                                <th>Status</th>
                                <th>created at</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($chatInstanceList as $key=>$instance)
                                <tr>
                                    <td class="serial">{{ $key + $chatInstanceList->firstItem()}} </td>
                                    <td> <span class="name">{{ $instance->name }}</span> </td>
                                    <td> <span class="product">{{ $instance->instance_token }}</span> </td>
                                    @php
                                    $planDetail = \App\Helpers\Helper::getPlanDetailView(Crypt::encrypt($instance->current_plan_id));
                                    @endphp
                                    <td> <span class="name">{{ $planDetail->plan_name }}</span> </td>
                                     <td> {{ $instance->app_name }}</td>
                                    @php
                                    $appValue = \App\Helpers\Helper::getNextAppNameView(strtolower($instance->app_name),Crypt::encryptString($instance->app_value));
                                    @endphp
                                    <td >{{ $appValue }}</td>
                                    <td>
                                        @if($instance->is_status==1)
                                            <span class="badge badge-success">Active</span>
                                        @elseif($instance->is_status==0)
                                        <span class="badge badge-warning">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $instance->created_at }}
                                    </td>
                                    <td><a href="{{ route('user.chat.bot.instance.update' ,Crypt::encrypt($instance->id)) }}"><i class="fa fa-edit" data-toggle="tooltip" data-original-title="Edit Plan" ></i></a>&nbsp;&nbsp; </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"> No instance logs in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($chatInstanceList->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$chatInstanceList->firstItem()}} to {{$chatInstanceList->lastItem()}} of {{$chatInstanceList->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $chatInstanceList->appends(Request::get('qa'))->links() }}
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
