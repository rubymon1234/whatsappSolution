@extends('layouts.master')
@section('title', 'User Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row -->
   @include('errors.status')
   <h5 class="hk-sec-title">@yield('title') :: Add User</h5>
   <p class="mb-20"></p>
    <div class="row">   
        <div class="col-xl-12">
            <div class="hk-row">
                <div class="col-lg-12 col-sm-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="col-sm">
                                <form role="form" action="" method="post" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">name</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name" class="form-control" placeholder="Enter User Name." value="{{ old('name') }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="input" class="col-sm-2 col-form-label">username(email)</label>
                                        <div class="col-sm-10">
                                            <input type="email" name="email" class="form-control" placeholder="Enter email." value="{{ old('email') }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">password</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="password" class="form-control" placeholder="Enter password." value="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">mobile</label>
                                        <div class="col-sm-10">
                                            <input type="mobile" name="mobile" class="form-control" placeholder="Enter mobile number." value="{{ old('mobile') }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-10">
                                            @if ($errors->any())
                                                <label class="control-label" for="inputError" style="color: #dd4b39"><i class="fa fa-times-circle-o" ></i> {{ implode(' | ', $errors->all(':message')) }} .</label>
                                                <br>
                                            @endif
                                            
                                            <button type="submit" class="btn btn-info " style="margin-right: 10px;" name="Update" value="Save">Create</button>&nbsp;
                                            <button type="submit" class="btn btn-danger " name="Cancel" value="cancel">Cancel</button>
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
@endsection
