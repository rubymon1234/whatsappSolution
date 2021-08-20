@extends('layouts.master')
@section('title', 'Message Response')
@section('content')
 <!-- select2 CSS -->
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('dist/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
    @include('errors.status')
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
                                    <input class="form-control" id="scrub_name" name="scrub_name" placeholder="Enter campaign name" value="" type="text" value="{{ old('scrub_name') }}">
                                    <div class="invalid-feedback">
                                        Please provide a valid name.
                                    </div>
                                </div>
                                 <div class="col-md-6 form-group">
                                    <label for="combination"> Select combination </label>
                                     <select class="form-control custom-select select2" id="combination" name="combination" onchange="selectedMessage(this.value)" >
                                        {{-- <?php echo old('combination') == "text" ? 'selected' : '' ?> --}}
                                            <option value="">Select</option>
                                            <option value="text">Text Only</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="location">Location</option>
                                            <option value="timeCondition">Time Condition</option>
                                    </select>
                                 </div>
                            </div>
                            <div class="row" id="sel_text" style="display: none;">
                                <div class="col-sm-6 form-group">
                                     <label for="text_app_name" class="col-form-label" >Next App Name</label>
                                    <select class="form-control custom-select" id="text_app_name" name="text_app_name" onchange="__getAppName(this.value)">
                                        <option value="null">null</option>
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
                                        <option value="null">null</option>
                                    </select>
                                </div>
                            </div>
                            <div id="sel_image" style="display: none;">
                                <div class="row">
                                <div class="col-sm-6 form-group">
                                     <label for="image_app_name" class="col-form-label" >Next App Name</label>
                                    <select class="form-control custom-select" id="image_app_name" name="image_app_name" onchange="__getAppName(this.value)">
                                        <option value="null">null</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                     <label for="image_app_name1" class="col-form-label">Next App Value </label>
                                    <select class="form-control custom-select" id="image_app_name1" name="image_app_name1" onchange="__checkAppValueCondition(this.value, 'image_app_name')">
                                        <option value="null">null</option>
                                           
                                    </select>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="image_photo" class="col-form-label m_sel_image">Select Image:</label>
                                        <input type="file" class="form-control m_sel_image" id="image_photo" name="image_photo" accept=".png, .jpg, .jpeg"><span class="m_sel_image text-sm" style="display: none;font-size:  9px;"><b>* Please upload - jpeg, jpg or PNG images with less than 4 MB size.</b></span>
                                    </div>
                                </div>
                            </div>
                            <div id="sel_video" style="display: none;">
                                <div class="row">
                                <div class="col-sm-6 form-group">
                                     <label for="video_app_name" class="col-form-label" >Next App Name</label>
                                    <select class="form-control custom-select" id="video_app_name" name="video_app_name" onchange="__getAppName(this.value)">
                                        <option value="null">null</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                     <label for="video_app_name1" class="col-form-label">Next App Value </label>
                                    <select class="form-control custom-select" id="video_app_name1" name="video_app_name1" onchange="__checkAppValueCondition(this.value, 'video_app_name')">
                                        <option value="null">null</option>
                                           
                                    </select>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="video" class="col-form-label m_sel_image">Select Image:</label>
                                        <input type="file" class="form-control m_sel_image" id="video" name="video" accept=".png, .jpg, .jpeg"><span class="m_sel_image text-sm" style="display: none;font-size:  9px;"><b>* Please upload - jpeg, jpg or PNG images with less than 4 MB size.</b></span>
                                    </div>
                                </div>
                            </div>
                            <div id="capture" style="display: none;">
                                <div class="row">
                                <div class="col-sm-6 form-group">
                                     <label for="capture_app_name" class="col-form-label" >Next App Name</label>
                                    <select class="form-control custom-select" id="capture_app_name" name="capture_app_name" onchange="__getAppName(this.value)">   
                                        <option value="null">null</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                     <label for="capture_app_name1" class="col-form-label">Next App Value </label>
                                    <select class="form-control custom-select" id="capture_app_name1" name="capture_app_name1" onchange="__checkAppValueCondition(this.value, 'capture_app_name')">
                                        <option value="null">null</option>
                                           
                                    </select>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Success Application </label>
                                        <select class="form-control custom-select" id="capture_success_app_name" name="capture_success_app_name" onchange="__getSuccessFailureName(this.value, true)">   
                                            <option value="null">null</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Success Application value</label>
                                        <select class="form-control custom-select" id="capture_success_app_value" name="capture_success_app_value">   
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="capture_failure_app_name" class="col-form-label m_sel_image">failed Application name</label>
                                        <select class="form-control custom-select" id="capture_failure_app_name" name="capture_failure_app_name" onchange="__getSuccessFailureName(this.value, false)">    
                                            <option value="null">null</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="capture_failure_app_value" class="col-form-label m_sel_image">failed Application value</label>
                                        <select class="form-control custom-select" id="capture_failure_app_value" name="capture_failure_app_value">   
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="validator" class="col-form-label m_sel_image">Input validator</label>
                                        <select class="form-control custom-select" id="validator" name="validator">   
                                        <option value="">Input Validator</option>
                                            <option value="text">Alpha-numeric</option>
                                            <option value="image">Numeric</option>
                                            <option value="video">Email</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="api" style="display: none;">
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="api_app_name" class="col-form-label" >Next App Name</label>
                                        <select class="form-control custom-select" id="api_app_name" name="api_app_name" onchange="__getAppName(this.value)">   
                                            <option value="null">null</option>
                                                <option value="text">Text</option>
                                                <option value="image">Image</option>
                                                <option value="video">Video</option>
                                                <option value="capture">Capture</option>
                                                <option value="api">Api</option>
                                                <option value="menu">Menu</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="api_app_name1" class="col-form-label">Next App Value </label>
                                        <select class="form-control custom-select" id="api_app_name1" name="api_app_name1" onchange="__checkAppValueCondition(this.value, 'api_app_name')">
                                            <option value="null">null</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="api_success_app_name" class="col-form-label m_sel_image">Success Application </label>
                                        <select class="form-control custom-select" id="api_success_app_name" name="api_success_app_name" onchange="__getSuccessFailureName(this.value, true)">   
                                            <option value="null">null</option>
                                                <option value="text">Text</option>
                                                <option value="image">Image</option>
                                                <option value="video">Video</option>
                                                <option value="capture">Capture</option>
                                                <option value="api">Api</option>
                                                <option value="menu">Menu</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="api_success_app_value" class="col-form-label m_sel_image">Success Application value</label>
                                        <select class="form-control custom-select" id="api_success_app_value" name="api_success_app_value">   
                                        
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="api_failure_app_name" class="col-form-label m_sel_image">failed Application name</label>
                                        <select class="form-control custom-select" id="api_failure_app_name" name="api_failure_app_name" onchange="__getSuccessFailureName(this.value, false)">   
                                            <option value="null">null</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="api_failure_app_value" class="col-form-label m_sel_image" >failed Application value</label>
                                        <select class="form-control custom-select" id="api_failure_app_value" name="api_failure_app_value">   
                                        
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="parameter_input" class="col-form-label m_sel_image">Parameter Input</label>
                                        <input class="form-control" id="parameter_input" name="parameter_input" placeholder="Enter input" value="" type="text">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="parameter_mobile" class="col-form-label m_sel_image">Parameter Mobile</label>
                                        <input class="form-control" id="parameter_mobile" name="parameter_mobile" placeholder="Enter Mobile" value="" type="text">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Url</label>
                                        <input class="form-control" id="url" name="url" placeholder="Enter url" value="" type="text">
                                    </div>
                                </div>
                            </div>
                            <div id="location" style="display: none;">
                                <div class="row">
                                <div class="col-sm-6 form-group">
                                     <label for="location_app_name" class="col-form-label" >Next App Name</label>
                                    <select class="form-control custom-select" id="location_app_name" name="location_app_name" onchange="__getAppName(this.value)">   
                                        <option value="null">null</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                     <label for="location_app_name1" class="col-form-label">Next App Value </label>
                                    <select class="form-control custom-select" id="location_app_name1" name="location_app_name1" onchange="__checkAppValueCondition(this.value, 'location_app_name')">
                                        <option value="null">null</option>
                                           
                                    </select>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="latitude" class="col-form-label m_sel_image">lat</label>
                                        <input class="form-control" id="latitude" name="latitude" placeholder="Enter lat" value="" type="text">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="longitude" class="col-form-label m_sel_image">long</label>
                                        <input class="form-control" id="longitude" name="longitude" placeholder="Enter Long" value="" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="lastName">Location</label>
                                     <textarea class="form-control mt-15 sel_msg" rows="3" placeholder="Enter location"  rows="5" cols="14" style="margin-top: 15px; margin-bottom: 5px; height: 154px;"  id="location" name="location"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div id="timeCondition" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6 form-group schduleRow">
                                        <label for="startTime">Start Time</label>
                                        <input type="text" class="form-control input-timepicker" id="startTime" name="startTime">
                                    </div>
                                    <div class="col-md-6 form-group schduleRow">
                                        <label for="endTime">End Time</label>
                                        <input type="text" class="form-control input-timepicker" id="endTime" name="endTime">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="timeCondition_success_app_name" class="col-form-label m_sel_image">Success Application </label>
                                        <select class="form-control custom-select" id="timeCondition_success_app_name" name="timeCondition_success_app_name" onchange="__getSuccessFailureName(this.value, true)">   
                                            <option value="null">null</option>
                                                <option value="text">Text</option>
                                                <option value="image">Image</option>
                                                <option value="video">Video</option>
                                                <option value="capture">Capture</option>
                                                <option value="api">Api</option>
                                                <option value="menu">Menu</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="timeCondition_success_app_value" class="col-form-label m_sel_image">Success Application value</label>
                                        <select class="form-control custom-select" id="timeCondition_success_app_value" name="timeCondition_success_app_value">   
                                        
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="timeCondition_failure_app_name" class="col-form-label m_sel_image">failed Application name</label>
                                        <select class="form-control custom-select" id="timeCondition_failure_app_name" name="timeCondition_failure_app_name" onchange="__getSuccessFailureName(this.value, false)">   
                                            <option value="null">null</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="timeCondition_failure_app_value" class="col-form-label m_sel_image" >failed Application value</label>
                                        <select class="form-control custom-select" id="timeCondition_failure_app_value" name="timeCondition_failure_app_value">   
                                        
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-md-6 form-group" id="message">
                                    <label for="lastName">Message</label>
                                     <textarea class="form-control mt-15 sel_msg" rows="3" placeholder="Enter Message"  rows="5" cols="14" style="margin-top: 15px; margin-bottom: 5px; height: 154px;"  maxlength="1000" id="message" name="message"></textarea>
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
<script src="{{ asset('dist/js/jquery.min.js') }}"></script>
<script src="{{ asset('dist/js/moment.min.js') }}"></script>
<script src="{{ asset('dist/js/daterangepicker.js') }}"></script>
<script type="text/javascript">
    function selectedMessage(slug){
        $("#sel_text").hide();
        $("#sel_image").hide();
        $("#sel_video").hide();
        $("#capture").hide();
        $("#api").hide();
        $("#location").hide();
        $("#message").show();

        if(slug=='text'){
            $("#sel_text").show();
        }else if(slug=='image'){
            $("#sel_image").show();
        }else if(slug =='video'){
            $("#sel_video").show();
        }else if(slug =='capture'){
            $("#capture").show();
            $("#message").hide();
        }else if (slug =='api') {
            $("#api").show();
            $("#message").hide();
        }else if(slug =='location'){
            $("#location").show();
            $("#message").hide();
        }else if(slug =='timeCondition'){
            $("#timeCondition").show();
            $("#message").hide();
        }
    }
    // let oldCobminationValue = "{{ old('combination') }}";
    // if(oldCobminationValue != "") {
    //     selectedMessage(oldCobminationValue);
    // }
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
                    $('#'+ $("#combination").val() +'_app_name1').html(result.response);
                },
                error: function (response) {
                    $('.preloader-it').hide();
                    $('#'+ $("#combination").val() +'_app_name1').html(result.response);
                }
            }
        );
    }

    function __getSuccessFailureName(combination, isForSuccess = true){
        console.log('#'+ $("#combination").val() + (isForSuccess ? '_success_' : '_failure_') +'_app_value');
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
                    $('#'+ $("#combination").val() + (isForSuccess ? '_success_' : '_failure_') +'app_value').html(result.response);
                },
                error: function (response) {
                    $('.preloader-it').hide();
                    $('#'+ $("#combination").val() + (isForSuccess ? '_success_' : '_failure_') +'app_value').html(result.response);
                }
            }
        );
    }

    function __checkAppValueCondition(value, targetId) {
        let appNameElementVal = $("#" + targetId).val();
        if((value == 'null' || value == null) && (appNameElementVal != 'null' && appNameElementVal != null)) {
            alert("Next App name should be null if Next App value is null");
            $("#" + targetId).val("null");
        }
    }

    $('.input-timepicker').daterangepicker({
        timePicker: true,
        datePicker: false,
        timePicker24Hour: true,
        timePickerIncrement: 1,
        timePickerSeconds: true,
        singleDatePicker: true,
        locale: {
            format: 'HH:mm:ss'
        }
    }).on('show.daterangepicker', function (ev, picker) {
            picker.container.find(".calendar-table").hide();
        })

</script>
@endsection
