@extends('layouts.master')
@section('title', 'Message Response')
@section('content')
 <!-- select2 CSS -->
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('dist/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
    @include('errors.status')
    <style>
        .errorClass {
            border-color: #dd4b39;
        }
    </style>
   <h6 class="hk-pg-title">@yield('title') :: Edit Messages</h6>
   <p class="mb-20"></p>
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <div class="row">
                    <div class="col-sm">
                        <form id="scrubForm" method="POST" action="{{ route('user.chat.bot.instance.update',Crypt::encrypt($botInstanceDetail->id)) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="firstName"> Name </label>
                                    <input class="form-control" id="bot_instance_name" name="bot_instance_name" placeholder="Enter bot instance name"  type="text" value="{{ $botInstanceDetail->name }}">
                                    
                                    <div class="invalid-feedback">
                                        Please provide a valid bot instance name.
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="combination"> Select Instance </label>
                                     <select class="form-control custom-select select2" id="instance" name="instance">
                                        <option value="">Select Instance</option>
                                            @foreach($instanceDetail as $instance)
                                                <option value="{{$instance->id}}" {{ ((string)$instance->instance_name ===(string)$botInstanceDetail->instance_token) ? 'selected' : '' }}>{{$instance->instance_name }}</option>
                                            @endforeach
                                    </select>
                                 </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="combination"> Select Plans </label>
                                     <select class="form-control custom-select select2" id="plan_id" name="plan_id">
                                        <option value="">Select Plan</option>
                                            @foreach($planDetail as $plan)
                                                <option value="{{$plan->id}}" {{ ((int)$plan->id ===(int)$botInstanceDetail->plan_id) ? 'selected' : '' }}>{{$plan->plan_name }} </option>
                                            @endforeach
                                    </select>
                                 </div>
                                
                                <div class="col-sm-6 form-group">
                                     <label for="text_app_name" class="col-form-label" >App Name</label>
                                    <select class="form-control custom-select" id="text_app_name" name="text_app_name" onchange="__getAppName(this.value)">
                                        <option value=""></option>
                                            <option value="text" {{ ('text' ===(string)strtolower($botInstanceDetail->app_name)) ? 'selected' : '' }}>Text</option>
                                            <option value="image" {{ ('image' ===(string)strtolower($botInstanceDetail->app_name)) ? 'selected' : '' }}>Image</option>
                                            <option value="video" {{ ('video' ===(string)strtolower($botInstanceDetail->app_name)) ? 'selected' : '' }}>Video</option>
                                            <option value="capture" {{ ('capture' ===(string)strtolower($botInstanceDetail->app_name)) ? 'selected' : '' }}>Capture</option>
                                            <option value="api" {{ ('api' ===(string)strtolower($botInstanceDetail->app_name)) ? 'selected' : '' }}>Api</option>
                                            <option value="menu" {{ ('menu' ===(string)strtolower($botInstanceDetail->app_name)) ? 'selected' : '' }}>Menu</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                     <label for="text_app_name1" class="col-form-label"> App Value </label>
                                    <select class="form-control custom-select" id="text_app_name1" name="text_app_name1" onchange="__checkAppValueCondition(this.value, 'text_app_name')">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            @if ($errors->any())
                                <label class="control-label" for="inputError" style="color: #dd4b39"><i class="fa fa-times-circle-o" ></i> {{ implode(' | ', $errors->all(':message')) }} .</label>
                                <br>
                            @endif
                            <button class="btn btn-primary pull-center" id="sendBtn" style="margin-left: 45%;" type="button">Update</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<script src="{{ asset('dist/js/jquery.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        __getAppName($('#text_app_name').val(),{{ $botInstanceDetail->app_value }});
    });
    function __getAppName(combination,app_value = null){
        $.ajax(
            {
                url: '{{ route('ajax.message.request.appname') }}',
                dataType: 'json', // what to expect back from the PHP script
                cache: false,
                data: { _token: "{{ csrf_token() }}", combination : combination ,app_value: app_value } ,
                type: 'POST' ,
                beforeSend: function () {
                    $('.preloader-it').show();
                },
                complete: function () {
                    $('.preloader-it').hide();
                },
                success: function (result) {
                    $('.preloader-it').hide();
                    $('#text_app_name1').html(result.response);
                },
                error: function (response) {
                    $('.preloader-it').hide();
                    $('#text_app_name1').html(result.response);
                }
            }
        );
    }

$("#sendBtn").click(function(event) {
    event.preventDefault();
    if(!__appValueValidationCheck()) {
        $("form").submit();
    }
});

function __appValueValidationCheck() {
    let hasFormError = false;
    $("select").removeClass("errorClass");
    hasFormError = __defaultValidation();
    /*$("[id*='failure_app_value'], [id*='app_name1'], [id*='success_app_value']").each((e, value) => {
        if($(value).is(":visible") && $(value).val() == "") {
            $(value).addClass("errorClass");
            hasFormError = true;
        }
    });*/
    hasFormError = false;
    return hasFormError;
}

function __defaultValidation() {
    let hasError = false;
    let fieldName = ["scrub_name", "messageText", "latitude", "longitude", "parameter_input", "parameter_mobile"];
    fieldName.forEach((value, index) => {
        $("#" + value).removeClass("errorClass");
        if($("#" + value).is(":visible") && $("#" + value).val() == "") {
            $("#" + value).addClass("errorClass");
            hasError = true;
        }
    })
    return hasError;
}
</script>
@endsection
