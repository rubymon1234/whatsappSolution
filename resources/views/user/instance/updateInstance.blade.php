@extends('layouts.master')
@section('title', 'Role Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row -->
   <h6 class="hk-sec-title">@yield('title') :: Update Instance</h6>
   <p class="mb-20"></p>
    <div class="row">
        <div class="col-xl-12">
            <div class="hk-row">
                <div class="col-lg-12 col-sm-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="col-sm">
                                <form role="form" action="{{ route('user.instance.update' ,$id) }}" method="post" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Instance name</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="instance_name" class="form-control" placeholder="Enter instance name." value="{{ $instanceDetail->instance_name }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Web Hook url</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" name="web_hook_url" class="form-control" placeholder="Enter web hook url." value="">{{ $instanceDetail->web_hook_url }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-10">
                                            @if ($errors->any())
                                                <label class="control-label" for="inputError" style="color: #dd4b39"><i class="fa fa-times-circle-o" ></i> {{ implode(' | ', $errors->all(':message')) }} .</label>
                                                <br>
                                            @endif

                                            <button type="submit" class="btn btn-info " style="margin-right: 10px;" name="save" value="update">Update</button>&nbsp;
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
