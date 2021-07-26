@extends('layouts.master')
@section('title', 'User Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: List Users</h6>
        </div>
    </div>
    <div class="hk-pg-header mb-0">
    <div class="d-flex">
            @permission('admin.user.create')
                <a class="btn btn-outline-info" href="{{ route('admin.user.create') }}"> Add New User </a>
            @endpermission
            &nbsp;&nbsp;
            @permission('admin.user.reseller.create')
                <a class="btn btn-outline-info" href="{{ route('admin.user.reseller.create') }}"> Add New Reseller </a>
            @endpermission
            &nbsp;&nbsp;&nbsp;&nbsp;
            <form name="searchForm" action="" method="get">
                <label>
                    <input type="text" class="form-control form-control-sm new" placeholder="name or email search" aria-controls="datable_1" name="qa" value="{{ $key }}">
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th style="text-align: left;">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $key=>$user)
                                <tr>
                                    <td class="serial">{{ $key + $users->firstItem()}} </td>
                                    <td> <span class="name">{{ $user->name }}</span> </td>
                                    <td> <span class="product">{{ $user->email }}</span> </td>
                                    @php
                                    $roleUser = \App\Helpers\Helper::getUserRole(Crypt::encrypt($user->id));
                                    @endphp
                                    <td style="font-weight: bold;">{{$roleUser->display_name }}</td>
                                    <td> 
                                        @if($user->is_status==0)
                                            <span class="badge badge-danger">Not Active</span>
                                        @elseif($user->is_status==1)
                                           <span class="badge badge-success">Active</span>
                                        @elseif($user->is_status==2)
                                            <span class="badge badge-warning"> Pending</span>
                                        @endif
                                    </td>
                                    <td style="text-align: left;"> 
                                        <a href="">
                                            <i class="fa fa-ban" data-toggle="tooltip" data-original-title="Block User" onclick="doubleConfirm({{ $user->id }})"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"> No Users in the list... for now! </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($users->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$users->firstItem()}} to {{$users->lastItem()}} of {{$users->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $users->appends(Request::get('qa'))->links() }}
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
        if (confirm('Are you sure you want to permanently delete this permission ?')){
           
        }
    }
</script>
@endsection