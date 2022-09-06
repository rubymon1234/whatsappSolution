@extends('layouts.master')
@section('title', 'Plan Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row -->
   <h6 class="hk-sec-title">@yield('title') :: Create Plan </h6>
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
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Plan name</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="plan_name" class="form-control" placeholder="Enter plan name." value="{{ old('plan_name') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">count</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="daily_count" class="form-control" placeholder="Enter daily count." value="{{ old('daily_count') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Plan Subscription</label>
                                        <div class="col-sm-10">
                                            <select class="form-control custom-select select2" id="plan_subscription" name="plan_subscription">
                                                <option value="0">Daily</option>
                                                <option value="1">Monthly</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Scrub count</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="scrub_count" class="form-control" placeholder="Enter scrub count." value="{{ old('scrub_count') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Instance count(N)</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="bot_instance_count" class="form-control" placeholder="Enter Instance count." value="{{ old('bot_instance_count') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Validity (Days)</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="plan_validity" class="form-control" placeholder="Enter plan validity." value="{{ old('plan_validity') }}">
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
