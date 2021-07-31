@extends('layouts.master')
@section('title', 'Role Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <h6 class="hk-sec-title">@yield('title') :: List Role
        @permission('acl.role.create')
            <a class="btn btn-outline-info pull-right" href="{{ route('acl.role.manage') }}"> Add Role </a>
        @endpermission
    </h6>
    <p class="mb-20"></p>
    <div class="row">
        <div class="col-xl-12">
            <div class="hk-row">
                <div class="table-responsive" style="padding: 1%;">
                    <table class="table table-hover table-bordered mb-0">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Role Name</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th >Manage</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $key=>$role)
                                <tr>
                                    <td class="serial">{{ $key + $roles->firstItem()}}</td>
                                    <td>  <span class="name">{{ $role->name }}</span> </td>
                                    <td> <span class="product">{{ $role->display_name }}</span> </td>
                                    <td> {{ $role->description }} </td>
                                    <td>
                                        <span>
                                            <a  class="btn btn-outline-primary" href="{{ route('acl.assign.role.permission', Crypt::encrypt($role->id)) }}">Permissions</a></span>
                                    </td>
                                    <td>
                                        {{-- Edit --}}

                                    <a href="{{ route('acl.role.action', Crypt::encrypt($role->id)) }}"><i class="fa fa-edit" data-toggle="tooltip" data-original-title="Edit Permission" ></i></a>&nbsp;&nbsp;
                                    {{-- Delete --}}
                                    <a href="javascript:void(0)" {{-- id="dou_conf_{{ $perm->id }}" --}} >
                                    <i class="fa fa-trash" data-toggle="tooltip" data-original-title="Delete Permission" onclick="">
                                    </i></a>
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"> No Role in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
</div>
@endsection
