@extends('layouts.master')
@section('title', 'API Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row -->
   <h6 class="hk-sec-title">@yield('title') :: Create Api Key</h6>
   <p class="mb-20"></p>
    <div class="row">
        <div class="col-xl-12">
            <div class="hk-row">
                <div class="col-lg-12 col-sm-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="col-sm">
                                <form role="form" action="{{ route('api.key.create') }}" method="post" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Api name</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="api_name" class="form-control" placeholder="Enter Api name." value="{{ old('api_name') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Api key</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="api_key" class="form-control" placeholder="" value="{{ $api_key }}" disabled="disabled" style="color: red;">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Instance</label>
                                        <div class="col-sm-10">
                                           <select class="form-control custom-select select2" id="instance" name="instance">
                                                <option value="">Select Instance</option>
                                                @foreach($instanceDetail as $instance)
                                                    <option value="{{$instance->id}}">{{$instance->instance_name }}</option>
                                                @endforeach
                                            </select>
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
