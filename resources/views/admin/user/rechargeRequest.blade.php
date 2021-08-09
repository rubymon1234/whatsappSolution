@extends('layouts.master')
@section('title', 'User Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row -->
   <h6 class="hk-sec-title">@yield('title') :: Recharge Request</h6>
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
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name" class="form-control" placeholder="Enter User name." value="{{ $user->name }}" readonly="readonly" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">User plan </label>
                                        <div class="col-sm-10">
                                            <select class="form-control select2" name="plan_id" onchange="openSelectedPlan(this.value)">
                                                <option value="">Select plan </option>
                                                <optgroup label="Plan List">
                                                    @foreach($plans as $plan)
                                                    <option value="{{ $plan->id }}">{{ $plan->plan_name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Credit</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="credit" class="form-control" placeholder="Enter credit." value="{{ old('credit') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-10">
                                            @if ($errors->any())
                                                <label class="control-label" for="inputError" style="color: #dd4b39"><i class="fa fa-times-circle-o" ></i> {{ implode(' | ', $errors->all(':message')) }} .</label>
                                                <br>
                                            @endif

                                            <button type="submit" class="btn btn-info " style="margin-right: 10px;" name="Update" value="Save">Request</button>&nbsp;
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
    <!-- Button trigger modal -->
</div>
@foreach($plans as $plan)
{{-- Model --}}
<div class="modal fade" id="planModalPopovers_{{ $plan->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Plan Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>Plan name </h6>
                <p class="mt-10 mb-20"> {{ $plan->plan_name }}</p>
                <h6>Daily Count</h6>
                <p class="mt-10"> {{ $plan->daily_count }} </p>
                <h6>Scrub Count</h6>
                <p class="mt-10"> {{ $plan->scrub_count }} </p>
                <h6>Plan Validity</h6>
                <p class="mt-10"> {{ $plan->plan_validity }} </p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
<script type="text/javascript">
    function openSelectedPlan(id){
        $("#planModalPopovers_"+id).modal('show');;
    }
</script>
@endsection
