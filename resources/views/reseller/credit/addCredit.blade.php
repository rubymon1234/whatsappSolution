@extends('layouts.master')
@section('title', 'Credit Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row -->
   @include('errors.status')
   <h6 class="hk-sec-title">@yield('title') :: Add / Deduct Credit </h6>
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
                                        <label for="input" class="col-sm-2 col-form-label">User</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="email" class="form-control" placeholder="Username" value="{{ $user->name }}" autocomplete="off" readonly="readonly" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label"> Account </label>
                                        <div class="col-sm-10">
                                            <select class="form-control custom-select select2" id="account" name="account" value="{{ old('payMethod')}}">
                                            <option value=""></option>
                                            <option value="api_credits">Api</option>
                                            <option value="credits">Chat Bot</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Type</label>
                                        <div class="col-sm-10">
                                            <select class="form-control custom-select select2" id="payMethod" name="payMethod" value="{{ old('payMethod')}}">
                                            <option value=""></option>
                                            <option value="credit">Add</option>
                                            <option value="debit">Debit</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="input" class="col-sm-2 col-form-label">Amount</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="amount" class="form-control" placeholder="Amount" value="{{ old('amount')}}" autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-10">
                                            @if ($errors->any())
                                                <label class="control-label" for="inputError" style="color: #dd4b39"><i class="fa fa-times-circle-o" ></i> {{ implode(' | ', $errors->all(':message')) }} .</label>
                                                <br>
                                            @endif

                                            <button type="submit" class="btn btn-info " style="margin-right: 10px;" name="Update" value="Save">Credit/Debit</button>&nbsp;
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
