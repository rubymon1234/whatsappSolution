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
   <h6 class="hk-pg-title">@yield('title') :: Create Messages</h6>
   <p class="mb-20"></p>
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <div class="row">
                    <div class="col-sm">
                        <form id="scrubForm" method="POST" action="{{ route('user.chat.bot.message.add') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="firstName"> Name </label>
                                    <input class="form-control" id="scrub_name" name="scrub_name" placeholder="Enter name" value="" type="text" value="{{ old('scrub_name') }}">
                                    <div class="invalid-feedback">
                                        Please provide a valid name.
                                    </div>
                                </div>
                                 <div class="col-md-6 form-group">
                                    <label for="combination"> Select Instance </label>
                                     <select class="form-control custom-select select2" id="instance" name="instance">
                                        <option value="">Select Instance</option>
                                            @foreach($instanceDetail as $instance)
                                                <option value="{{$instance->id}}">{{$instance->instance_name }}</option>
                                            @endforeach
                                    </select>
                                 </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="combination"> Select combination </label>
                                     <select class="form-control custom-select select2" id="combination" name="combination" onchange="selectedMessage(this.value)" >
                                        <option value="">Select combinationList</option>
                                        @foreach($combinationList as $key => $combination)
                                            <option value="{{ $key }}">{{ $combination }}</option>
                                        @endforeach
                                    </select>
                                 </div>
                                <div class="col-sm-6 form-group">
                                     <label for="text_app_name" class="col-form-label" >Next App Name</label>
                                    <select class="form-control custom-select" id="text_app_name" name="text_app_name" onchange="__getAppName(this.value)">
                                        <option value="null"></option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                     <label for="text_app_name1" class="col-form-label">Next App Value </label>
                                    <select class="form-control custom-select" id="text_app_name1" name="text_app_name1" onchange="__checkAppValueCondition(this.value, 'text_app_name')">
                                        <option value="null"></option>
                                    </select>
                                </div>
                            </div>
                            @if ($errors->any())
                                <label class="control-label" for="inputError" style="color: #dd4b39"><i class="fa fa-times-circle-o" ></i> {{ implode(' | ', $errors->all(':message')) }} .</label>
                                <br>
                            @endif
                            <button class="btn btn-primary pull-center" id="sendBtn" style="margin-left: 45%;" type="button">Create</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<script src="{{ asset('dist/js/jquery.min.js') }}"></script>
<script type="text/javascript">
    function __appQRScan(instance_id){
        if(typeof  instance_id !=='undefined' && instance_id !=''){
            $.ajax(
                {
                    url: '{{ route('user.instance.postqrscan') }}',
                    dataType: 'json', // what to expect back from the PHP script
                    cache: false,
                    data: { _token: "{{ csrf_token() }}", instance_id : instance_id } ,
                    type: 'POST' ,
                    beforeSend: function () {
                        $('.preloader-it').show();
                    },
                    complete: function () {
                        $('.preloader-it').hide();
                    },
                    success: function (result) {
                        $('.preloader-it').hide();
                        if(result.success){
                            $('#scanQRCodeModel').modal('show');
                            $('.qrCode').attr('src', result.scan_url);
                        }else{
                            console.log("Something went wrong in server!!");
                        }
                    },
                    error: function (response) {
                        console.log('Server error');
                    }
                }
            );
        }
    }

    function __getAppName(combination){
        $.ajax(
            {
                url: '{{ route('ajax.message.request.appname') }}',
                dataType: 'json', // what to expect back from the PHP script
                cache: false,
                data: { _token: "{{ csrf_token() }}", combination : combination } ,
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
    $("[id*='failure_app_value'], [id*='app_name1'], [id*='success_app_value']").each((e, value) => {
        if($(value).is(":visible") && $(value).val() == "") {
            $(value).addClass("errorClass");
            hasFormError = true;
        }
    });
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
