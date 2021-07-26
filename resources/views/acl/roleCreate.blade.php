@extends('layouts.master')
@section('title', 'Role Management')
@section('content')
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row -->
   <h5 class="hk-sec-title">@yield('title') :: @php 
if($id){ echo 'Update'; }else { echo'Add'; } @endphp Role</h5>
   <p class="mb-20"></p>
    <div class="row">   
        <div class="col-xl-12">
            <div class="hk-row">
                <div class="col-lg-12 col-sm-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="col-sm">
                                <form role="form" action="@if($id) {{ route('acl.role.action',Crypt::encrypt($id)) }} @endif" method="post" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Role name</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name" class="form-control" placeholder="Enter Role Name." value="<?php if(isset($roleDetail->name)){ echo $roleDetail->name; }else{ echo old('name'); } ?>">
                                            
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Display Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="display_name" class="form-control" placeholder="Enter display name." value="<?php if(isset($roleDetail->display_name)){ echo $roleDetail->display_name; }else{ echo old('display_name'); } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Default Permission </label>
                                        <div class="col-sm-10">
                                            <select class="form-control select2" name="slug">
                                                <option>Select Permission </option>
                                                <optgroup label="Permission List">
                                                    @foreach($permissions as $permission)
                                                    <option value="{{ $permission->name }}" <?php if(isset($roleDetail->slug)){ if($roleDetail->slug === $permission->name) {echo "selected";} } ?>>{{ $permission->display_name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Description</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="description" class="form-control" placeholder="Enter Description." value="<?php if(isset($roleDetail->description)){ echo $roleDetail->description; }else{ echo old('description'); } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-10">
                                            @if ($errors->any())
                                                <label class="control-label" for="inputError" style="color: #dd4b39"><i class="fa fa-times-circle-o" ></i> {{ implode(' | ', $errors->all(':message')) }} .</label>
                                                <br>
                                            @endif
                                            @if(!$id)
                                                <button type="submit" class="btn btn-info" style="margin-right: 10px;" name="Update" value="Save">Create</button>
                                            @endif
                                            @if($id)
                                                <button type="submit" class="btn btn-info" style="margin-right: 10px;" name="Update" value="update">Update</button>
                                            @endif
                                            <button type="submit" class="btn btn-danger" name="Cancel" value="cancel">Cancel</button>&nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
@endsection
