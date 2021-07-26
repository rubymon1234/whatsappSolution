@extends('layouts.master')
@section('title', 'Role Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <h6 class="hk-sec-title">@yield('title') :: List Permission
        @permission('acl.perms.create')
        <a class="btn btn-outline-info pull-right" href="{{ route('acl.perms.manage') }}"> Add New Permission </a>
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
                                <th >#</th>
                                <th >Permission Name</th>
                                <th>Display Name</th>
                                <th>Description</th>
                                <th style="text-align: left;">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perms as $key=>$perm)
                                <tr>
                                    <td class="serial">{{ $key + $perms->firstItem()}}</td>
                                    <td> <span class="name">{{ $perm->name }}</span> </td>
                                    <td> <span class="product">{{ $perm->display_name }}</span> </td>
                                    <td> {{ $perm->description }} </td>
                                    <td style="text-align: left;"> 
                                        <a href="{{ route('acl.perms.edit' ,Crypt::encrypt($perm->id)) }}"><i class="fa fa-edit" data-toggle="tooltip" data-original-title="Edit Permission" ></i></a>&nbsp;&nbsp;
                                        <a href="" id="dou_conf_{{ $perm->id }}">
                                        <i class="fa fa-trash" data-toggle="tooltip" data-original-title="Delete Permission" onclick="doubleConfirm({{ $perm->id }},'{{ route('acl.perms.delete',$perm->id) }}')">
                                         </i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"> No Permission in the list... for now! </td>
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
<script type="text/javascript">
    function doubleConfirm(permission_id,url){
        if (confirm('Are you sure you want to permanently delete this permission ?')){
           jQuery('#dou_conf_'+permission_id).attr('href',url);
        }
    }
</script>
@endsection