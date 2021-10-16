@extends('layouts.master')
@section('title', 'Credit Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: List Transactions</h6>
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
                                <th>Plan Name</th>
                                <th>Plan Count</th>
                                <th>Scrub Count</th>
                                <th>Bot Instance Count</th>
                                <th>Plan Validity(days)</th>
                                <th>Credit</th>
                                <th>Request Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseHistory as $key=>$transaction)
                                <tr>
                                    <td class="serial">{{ $key + $purchaseHistory->firstItem()}} </td>
                                    <td> <span class="name">{{ $transaction->name }}</span> </td>
                                    </span></td>
                                    <td> <span class="name">{{ $transaction->plan_name }}</span> </td>
                                     <td> <span class="product">{{ $transaction->daily_count }}</span> </td>
                                     <td> {{ $transaction->scrub_count }}</td>
                                     <td> {{ $transaction->bot_instance_count }}</td>
                                    <td >{{$transaction->plan_validity }}</td>
                                    <td ><b style="font-weight: bold;">Rs: </b>{{ $transaction->credit }} </td>
                                    <td>
                                        {{ $transaction->created_at}}
                                    </td>
                                    <td>
                                        @if($transaction->is_status==1)
                                            <span class="badge badge-success">approved</span>
                                        @elseif($transaction->is_status==2)
                                        <span class="badge badge-warning">waiting for approval</span>
                                        @elseif($transaction->is_status==3)
                                            <span class="badge badge-danger">reject</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"> No transaction logs in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($purchaseHistory->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$purchaseHistory->firstItem()}} to {{$purchaseHistory->lastItem()}} of {{$purchaseHistory->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $purchaseHistory->appends(Request::get('qa'))->links() }}
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
