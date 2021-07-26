@extends('layouts.master')
@section('title', 'Role Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row -->
   <h5 class="hk-sec-title">@yield('title') :: List Role</h5>
    <p class="mb-20"></p>
    <?php $assignRole  = array(); ?>
        @foreach($role_permissions as $assignrole)
            <?php  $assignRole[] = $assignrole->id; ?> 
        @endforeach
    <div class="row">
        <div class="col-xl-12">
            <div class="hk-row">
                <div class="table-responsive" style="padding: 1%;">
                    <form role="form" method="post" action="">
                    {{ csrf_field() }}
                        <table class="table table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th >Check</th>
                                    <th >Name</th>
                                    <th>Display Name</th>
                                    <th style="text-align: left;">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @forelse($permissions as $key=>$role_perms)
                                    <tr>
                                        <td class="serial"><input type="checkbox" name="permissn[]" class="" id="check1" value="{{ $role_perms->id }}" <?php 
                                    if (in_array($role_perms->id, $assignRole)) { ?>
                                        checked=checked
                                    <?php } ?>> </td>
                                        <td>  <span class="name">{{ $role_perms->name }}</span> </td>
                                        <td> <span class="product">{{ $role_perms->display_name }}</span> </td>
                                        <td style="text-align: left;"> {{ $role_perms->description }} </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"> No Permission in the list... for now! </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="card-body1 mb-20" style="margin-top: 10px;">
                            <button type="submit" class="btn btn-danger" name="Cancel" value="cancel">Cancel</button>&nbsp;
                            <button type="submit" class="btn btn-info pull-left" style="margin-right: 10px;" name="Update" value="Save">Update</button>
                        </div>
                    </form>
                </div>
            </div>      
        </div>
    </div>
    <!-- /Row -->
</div>
@endsection