@extends('layouts.master')
@section('title', 'Profile Management')
@section('content')

<div class="profile-cover-wrap bg-indigo-light-2">
    {{-- <div class="profile-cover-img" style="background-image:url('{{ asset('dist/img/trans-bg.jpg') }}')"></div> --}}
	<div class="bg-overlay bg-trans-dark-60"></div>
	<div class="container-fluid profile-cover-content py-50">
		<div class="hk-row"> 
			<div class="col-lg-6">
				<div class="media align-items-center">
					<div class="media-img-wrap  d-flex">
						<div class="avatar">
							<img src="{{ asset('dist/img/profile_demo.png') }}" alt="user" class="avatar-img rounded-circle">
						</div>
					</div>
					<div class="media-body">
						<div class="text-white text-capitalize display-6 mb-5 font-weight-400">{{ Auth::user()->name }}</div>
						<div class="font-14 text-white"><span class="mr-5"><span class="font-weight-500 pr-5">User</span><span class="mr-5"></span></span><span><span class="font-weight-500 pr-5"></span><span></span></span></div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="button-list">
					<a href="javascript:void()" class="btn btn-light btn-wth-icon icon-wthot-bg btn-rounded"><span class="btn-text" style="text-transform: lowercase !important;">{{Auth::user()->email }}</span><span class="icon-label"><i class="icon ion-md-mail"></i> </span></a>


					<a href="javascript:void()" class="btn btn-blue btn-wth-icon icon-wthot-bg btn-rounded"><span class="btn-text">{{Auth::user()->mobile }}</span><span class="icon-label"><i class="icon ion-md-call"></i> </span></a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="profile-tab bg-white shadow-bottom" >
	<div class="container-fluid">
		<ul class="nav nav-light nav-tabs" role="tablist">
			<li class="nav-item">
				<a href="#tabProfile" data-toggle="tab" class="d-flex h-60p align-items-center nav-link @if(old('tab')==1) {{'active'}} @endif @if(old('tab')==null) {{'active'}} @endif">Profile</a>
			</li>
			<li class="nav-item">
				<a href="#tabProfileUpdate" data-toggle="tab" class="d-flex h-60p align-items-center nav-link @if(old('tab')==2) {{'active'}} @endif">Edit</a>
			</li>
			<li class="nav-item">
				<a href="#tabPasswordUpdate" data-toggle="tab" class="d-flex h-60p align-items-center nav-link @if(old('tab')==3) {{'active'}} @endif">Password </a>
			</li>
		</ul>
	</div>
</div>

<div class="tab-content mt-sm-30 mt-30">
	<div class="tab-content mt-sm-30 mt-30" style="margin-right: 12px;
    margin-left: 14px;">
	@include('errors.status')
	</div>
		<div class="tab-pane fade show @if(old('tab')==1) {{'active'}} @endif @if(old('tab')==null) {{'active'}} @endif" id="tabProfile">
			<div class="container-fluid">
				<div class="hk-row">
					<div class="col-lg-12">
						<div class="card card-profile-feed">
							@php
				            $planDetail = \App\Helpers\Helper::getUserActivePlans(Crypt::encrypt(Auth::user()->id));
				            @endphp
				            @if(isset($planDetail['plan_name']))
							<ul class="list-group list-group-flush">
                                <li class="list-group-item"><span><i class="ion ion-md-calendar font-18 text-light-20 mr-10"></i><span>Active Plan : </span></span><span class="ml-5 text-dark"><b style="font-weight: bold;">{{ $planDetail['plan_name']}} </b> | <b style="font-weight: bold;"><span class="badge badge-info">{{ $planDetail['status']}} </span></b></span></li>
                                <li class="list-group-item"><span><i class="ion ion-md-briefcase font-18 text-light-20 mr-10"></i><span>Active Services:</span></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="badge badge-warning"> whatsapp</span></li>
                                <li class="list-group-item"><span><i class="ion ion-ios-person font-18 text-light-20 mr-10"></i>
                                    <span>User status :</span>
                                    @if(Auth::user()->is_status ===1)
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="badge badge-success">Active</span>
                                    @endif
                                    @if(Auth::user()->is_status ===0)
                                        <span class="badge badge-danger">In Active</span>
                                    @endif
                                </span></li>
                            </ul>
                            @else
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><span><i class="ion ion-ios-person font-18 text-light-20 mr-10"></i>
                                    @if(Auth::user()->is_status ===1)
                                        <span>Status :</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="badge badge-success">Active</span>
                                    @endif
                                    @if(Auth::user()->is_status ===0)
                                        <span>Status :</span><span class="badge badge-danger">In Active</span>
                                    @endif
                                </span></li>
                                <li class="list-group-item"><span><i class="ion ion-md-briefcase font-18 text-light-20 mr-10"></i><span>Active Services:</span></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="badge badge-danger">In Active  </span></li>
                            </ul>
                            @endif
						 </div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade show @if(old('tab')==2) {{'active'}} @endif" id="tabProfileUpdate">
			<div class="container-fluid">
				<div class="hk-row">
					<div class="col-lg-12 col-sm-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="col-sm">
                            	 
                            	 <h6 class="hk-sec-title"> Update Profile</h6><hr><br>
                                <form role="form" action="" method="post" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="tab" value="2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">user name</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="name" class="form-control" placeholder="Enter user name." value="{{ Auth::user()->name }}">
                                        </div>
                                    </div><div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">mobile</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number." value="{{ Auth::user()->mobile }}">
                                        </div>
                                    </div><hr>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-10" >
                                            @if ($errors->any())
                                                <label class="control-label" for="inputError" style="color: #dd4b39"><i class="fa fa-times-circle-o" ></i> {{ implode(' | ', $errors->all(':message')) }} .</label>
                                                <br>
                                            @endif
                                        	<button type="submit" class="btn btn-success" name="update" value="update">Update</button>&nbsp;
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
		<div class="tab-pane fade show @if(old('tab')==3) {{'active'}} @endif" id="tabPasswordUpdate">
			<div class="container-fluid">
				<div class="hk-row">
					<div class="col-lg-12 col-sm-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="col-sm">
                            	 <h6 class="hk-sec-title"> Password Update</h6><hr><br>
                                <form role="form" action="" method="post" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="tab" value="3">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="current_password" class="form-control" placeholder="Enter password." value="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">New password</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="new_password" class="form-control" placeholder="Enter new password." value="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Confirm password</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="confirm_password" class="form-control" placeholder="Enter confirm password." value="">
                                        </div>
                                    </div><hr>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-10" >
                                            @if ($errors->any())
                                                <label class="control-label" for="inputError" style="color: #dd4b39"><i class="fa fa-times-circle-o" ></i> {{ implode(' | ', $errors->all(':message')) }} .</label>
                                                <br>
                                            @endif
                                        	<button type="submit" class="btn btn-success" name="update" value="change">Change</button>&nbsp;
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
	</div>
@endsection