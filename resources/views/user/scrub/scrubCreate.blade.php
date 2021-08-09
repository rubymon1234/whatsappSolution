@extends('layouts.master')
@section('title', 'Scrub Management')
@section('content')
 <!-- select2 CSS -->
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
    @include('errors.status')
   <h6 class="hk-pg-title">@yield('title') :: Scrubing Create </h6>
   <p class="mb-20"></p>
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <div class="row">
                    <div class="col-sm">
                        <form id="scrubForm" method="POST" action="" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="firstName">Scrub name </label>
                                    <input class="form-control" id="scrub_name" name="scrub_name" placeholder="Enter scrub name" value="" type="text" value="{{ old('scrub_name') }}">
                                    <div class="invalid-feedback">
                                        Please provide a valid scrub name.
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="lastName">Instance </label>
                                   <select class="form-control custom-select select2" id="instance" name="instance">
                                        <option value="">Select Instance</option>
                                            @foreach($instanceDetail as $instance)
                                                <option value="{{$instance->id}}">{{$instance->instance_name }}</option>
                                            @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Please provide a valid instance.
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-6 form-group">
                                    <label for="">Number</label>
                                     <textarea class="form-control mt-15" name="mobile" id="mobile" rows="3" placeholder="Enter Mobile Numbers With Country Code"  rows="5" cols="14" style="margin-top: 15px; margin-bottom: 5px; height: 154px;" onkeypress="return isNumberKey(event);" wrap="HARD"></textarea>
                                     <span class="btn btn-success btn-xs" id="removeDups">Remove Duplicate</span>
                                     <span class="btn btn-danger btn-xs" id="clearNums">Clear Numbers</span>
                                     <span class="btn btn-info btn-xs pull-right btn-rounded" id="num-counter">0</span>
                                 </div>
                            </div>
                            @if ($errors->any())
                                <label class="control-label" for="inputError" style="color: #dd4b39"><i class="fa fa-times-circle-o" ></i> {{ implode(' | ', $errors->all(':message')) }} .</label>
                                <br>
                            @endif
                            <button class="btn btn-primary pull-center" id="sendBtn" style="margin-left: 45%;" type="submit">Create</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<style type="text/css">
    .select2-container .select2-selection--single {
        height: 40px ! important;
    }
    .toggle-slide {
        margin-top: 10px;
    }
</style>
<script src="{{ asset('dist/js/jquery.min.js') }}"></script>
<script src="{{ asset('dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('dist/js/select2-data.js') }}"></script>
<script src="{{ asset('dist/js/custom-script.js') }}"></script>
<script type="text/javascript">

    $('#scrubForm').submit(function(e){
        e.preventDefault();
        valid = validationError();
        if(valid===true){
            $("#scrubForm").submit();
        }
    });
    function validationError(){

        i = 0;
        scrub_name        = $("#scrub_name").val();
        instance        = $("#instance").val();
        mobile          = $("#mobile").val();

        if(scrub_name =='' || scrub_name =='undefined'){
            $("#scrub_name").addClass('is-invalid');
            i++;
        }else{
            $("#scrub_name").removeClass("is-invalid");
        }
        if(instance =='' || instance =='undefined'){
            $("#instance").addClass('is-invalid');
            i++;
        }else{
            $("#instance").removeClass("is-invalid");
        }
        if(mobile =='' || mobile =='undefined'){
            $("#mobile").addClass('is-invalid');
            i++;
        }else{
            $("#mobile").removeClass("is-invalid");
        }
        if(i ==0){
            return true;
        }else{
            return false;
        }
    }
</script>
@endsection
