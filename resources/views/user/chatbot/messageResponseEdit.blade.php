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
                        <form id="scrubForm" method="POST" action="{{ route('user.chat.bot.message.add') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $id }}">
                            <input type="hidden" name="combination" value="{{ Request::get("combination") }}">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="firstName"> Name </label>
                                    <input class="form-control" id="scrub_name" name="scrub_name" placeholder="Enter name" type="text" value="{{ $name }}">
                                    <div class="invalid-feedback">
                                        Please provide a valid name.
                                    </div>
                                </div>
                                 <div class="col-md-6 form-group">
                                    <label for="combination"> Select Response </label>
                                     <select class="form-control custom-select select2" id="combination" onchange="selectedMessage(this.value)" readonly disabled>
                                        <option value="">Select Reeponse</option>
                                        @foreach($combinationList as $key => $combination)
                                            <option <?php echo Request::get("combination") == $key ? 'selected' : '' ?> value="{{ $key }}">{{ $combination }}</option>
                                        @endforeach
                                    </select>
                                 </div>
                            </div>
                            @if (Request::get("combination") === 'text')
                                <div class="row sel_text" class="" style="display: none;">
                                    <div class="col-md-6 form-group">
                                        <label for="lastName">Message</label>
                                         <textarea class="form-control mt-15 sel_msg" rows="3" placeholder="Enter Message"  rows="5" cols="14" style="margin-top: 15px; margin-bottom: 5px; height: 154px;"  maxlength="1000" id="messageText" name="messageText">{{ rawurldecode($message) }}</textarea>
                                     </div>
                                </div>
                                <div class="row sel_text" style="display: none;">
                                    <div class="col-sm-6 form-group">
                                        <label for="text_app_name" class="col-form-label" >Next App Name</label>
                                        <select class="form-control custom-select select2" id="text_app_name" name="text_app_name" onchange="__getAppName(this.value)">
                                            <option value="null">NULL</option>
                                                <option value="text">TEXT</option>
                                                <option value="image">IMAGE</option>
                                                <option value="video">VIDEO</option>
                                                <option value="capture">CAPTURE</option>
                                                <option value="api">API</option>
                                                <option value="timeCondition">TIME CONDITION</option>
                                                <option value="menu">MENU</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="text_app_name1" class="col-form-label">Next App Value </label>
                                        <select class="form-control custom-select select2" id="text_app_name1" name="text_app_name1" onchange="__checkAppValueCondition(this.value, 'text_app_name')">
                                            <option value="null">null</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if (Request::get("combination") === 'image')
                                <div id="sel_image" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="lastName">Message</label>
                                             <textarea class="form-control mt-15 sel_msg" rows="3" placeholder="Enter Message"  rows="5" cols="14" style="margin-top: 15px; margin-bottom: 5px; height: 154px;"  maxlength="1000" id="messageImage" name="messageImage">{{ rawurldecode($message) }}</textarea>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="image_photo" class="col-form-label m_sel_image">Select Image:</label>
                                            <input type="file" class="form-control m_sel_image" id="image_photo" name="image_photo" accept=".png, .jpg, .jpeg"><span class="m_sel_image text-sm" style="display: none;font-size:  9px;"><b>* Please upload - jpeg, jpg or PNG images with less than 4 MB size.</b></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="image_app_name" class="col-form-label" >Next App Name</label>
                                        <select class="form-control custom-select select2" id="image_app_name" name="image_app_name" onchange="__getAppName(this.value)">
                                            <option value="null">NULL</option>
                                                <option value="text">TEXT</option>
                                                <option value="image">IMAGE</option>
                                                <option value="video">VIDEO</option>
                                                <option value="capture">CAPTURE</option>
                                                <option value="api">API</option>
                                                <option value="timeCondition">TIME CONDITION</option>
                                                <option value="menu">MENU</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="image_app_name1" class="col-form-label">Next App Value </label>
                                        <select class="form-control custom-select select2" id="image_app_name1" name="image_app_name1" onchange="__checkAppValueCondition(this.value, 'image_app_name')">
                                            <option value="null">null</option>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                            @endif
                            @if (Request::get("combination") === 'video')
                                <div id="sel_video" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="lastName">Message</label>
                                             <textarea class="form-control mt-15 sel_msg" rows="3" placeholder="Enter Message"  rows="5" cols="14" style="margin-top: 15px; margin-bottom: 5px; height: 154px;"  maxlength="1000" id="messageVideo" name="messageVideo">{{ rawurldecode($message) }}</textarea>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="video" class="col-form-label m_sel_image">Select Image:</label>
                                            <input type="file" class="form-control m_sel_image" id="video" name="video" accept=".png, .jpg, .jpeg"><span class="m_sel_image text-sm" style="display: none;font-size:  9px;"><b>* Please upload - jpeg, jpg or PNG images with less than 4 MB size.</b></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="video_app_name" class="col-form-label" >Next App Name</label>
                                        <select class="form-control custom-select select2" id="video_app_name" name="video_app_name" onchange="__getAppName(this.value)">
                                            <option value="null">NULL</option>
                                                <option value="text">TEXT</option>
                                                <option value="image">IMAGE</option>
                                                <option value="video">VIDEO</option>
                                                <option value="capture">CAPTURE</option>
                                                <option value="api">API</option>
                                                <option value="timeCondition">TIME CONDITION</option>
                                                <option value="menu">MENU</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="video_app_name1" class="col-form-label">Next App Value </label>
                                        <select class="form-control custom-select select2" id="video_app_name1" name="video_app_name1" onchange="__checkAppValueCondition(this.value, 'video_app_name')">
                                            <option value="null"></option>
                                        </select>
                                    </div>
                                    </div>

                                </div>
                            @endif
                            @if (Request::get("combination") === 'capture')
                                <div id="capture" style="display: none;">
                                    <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="capture_app_name" class="col-form-label" >Next App Name</label>
                                        <select class="form-control custom-select select2" id="capture_app_name" name="capture_app_name" onchange="__getAppName(this.value)">   
                                            <option value="null">NULL</option>
                                                <option value="text">TEXT</option>
                                                <option value="image">IMAGE</option>
                                                <option value="video">Video</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="capture_app_name1" class="col-form-label">Next App Value </label>
                                        <select class="form-control custom-select select2" id="capture_app_name1" name="capture_app_name1" onchange="__checkAppValueCondition(this.value, 'capture_app_name')">
                                            <option value="null">null</option>
                                        </select>
                                    </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="" class="col-form-label m_sel_image">Success Application </label>
                                            <select class="form-control custom-select select2" id="capture_success_app_name" name="capture_success_app_name" onchange="__getSuccessFailureName(this.value, true)">   
                                                <option value="null">NULL</option>
                                                <option value="text">TEXT</option>
                                                <option value="image">IMAGE</option>
                                                <option value="video">VIDEO</option>
                                                <option value="capture">CAPTURE</option>
                                                <option value="api">API</option>
                                                <option value="timeCondition">TIME CONDITION</option>
                                                <option value="menu">MENU</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="" class="col-form-label m_sel_image">Success Application value</label>
                                            <select class="form-control custom-select select2" id="capture_success_app_value" name="capture_success_app_value" onchange="__checkAppValueCondition(this.value, 'capture_success_app_name')">
                                                <option value="null">null</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="capture_failure_app_name" class="col-form-label m_sel_image">failed Application name</label>
                                            <select class="form-control custom-select select2" id="capture_failure_app_name" name="capture_failure_app_name" onchange="__getSuccessFailureName(this.value, false)">    
                                                <option value="null">NULL</option>
                                                <option value="text">TEXT</option>
                                                <option value="image">IMAGE</option>
                                                <option value="video">VIDEO</option>
                                                <option value="capture">CAPTURE</option>
                                                <option value="api">API</option>
                                                <option value="timeCondition">TIME CONDITION</option>
                                                <option value="menu">MENU</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="capture_failure_app_value" class="col-form-label m_sel_image">failed Application value</label>
                                            <select class="form-control custom-select select2" id="capture_failure_app_value" name="capture_failure_app_value" onchange="__checkAppValueCondition(this.value, 'capture_failure_app_name')">   
                                                <option value="null">null</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="validator" class="col-form-label m_sel_image">Input validator</label>
                                            <select class="form-control custom-select select2" id="validator" name="validator"> 
                                                <option <?php echo $validator == 'Alpha-numeric' ? "selected" : "" ?> value="Alpha-numeric">Alpha-numeric</option>
                                                <option <?php echo $validator == 'Numeric' ? "selected" : "" ?> value="Numeric">Numeric</option>
                                                <option <?php echo $validator == 'Email' ? "selected" : "" ?> value="Email">Email</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (Request::get("combination") === 'api')
                                <div id="api" style="display: none;">
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="api_app_name" class="col-form-label" >Next App Name</label>
                                            <select class="form-control custom-select select2" id="api_app_name" name="api_app_name" onchange="__getAppName(this.value)">   
                                                <option value="null">NULL</option>
                                                    <option value="text">TEXT</option>
                                                    <option value="image">IMAGE</option>
                                                    <option value="video">VIDEO</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="api_app_name1" class="col-form-label">Next App Value </label>
                                            <select class="form-control custom-select select2" id="api_app_name1" name="api_app_name1" onchange="__checkAppValueCondition(this.value, 'api_app_name')">
                                                <option value="null">null</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="api_success_app_name" class="col-form-label m_sel_image">Success Application </label>
                                            <select class="form-control custom-select select2" id="api_success_app_name" name="api_success_app_name" onchange="__getSuccessFailureName(this.value, true)">   
                                                <option value="null">NULL</option>
                                                    <option value="text">TEXT</option>
                                                    <option value="image">IMAGE</option>
                                                    <option value="video">VIDEO</option>
                                                    <option value="capture">CAPTURE</option>
                                                    <option value="api">API</option>
                                                    <option value="timeCondition">TIME CONDITION</option>
                                                    <option value="menu">MENU</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="api_success_app_value" class="col-form-label m_sel_image">Success Application value</label>
                                            <select class="form-control custom-select select2" id="api_success_app_value" name="api_success_app_value" onchange="__checkAppValueCondition(this.value, 'api_success_app_name')">   
                                                <option value="null">null</option>
                                            </select>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="api_failure_app_name" class="col-form-label m_sel_image">failed Application name</label>
                                            <select class="form-control custom-select select2" id="api_failure_app_name" name="api_failure_app_name" onchange="__getSuccessFailureName(this.value, false)">   
                                                <option value="null">NULL</option>
                                                <option value="text">TEXT</option>
                                                <option value="image">IMAGE</option>
                                                <option value="video">VIDEO</option>
                                                <option value="capture">CAPTURE</option>
                                                <option value="api">API</option>
                                                <option value="timeCondition">TIME CONDITION</option>
                                                <option value="menu">MENU</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="api_failure_app_value" class="col-form-label m_sel_image" >failed Application value</label>
                                            <select class="form-control custom-select select2" id="api_failure_app_value" name="api_failure_app_value" onchange="__checkAppValueCondition(this.value, 'api_failure_app_name')">   
                                                <option value="null">null</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="parameter_input" class="col-form-label m_sel_image">Parameter Input</label>
                                            <input class="form-control" id="parameter_input" name="parameter_input" placeholder="Enter input" value="{{ $parameter_input }}" type="text">
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="parameter_mobile" class="col-form-label m_sel_image">Parameter Mobile</label>
                                            <input class="form-control" id="parameter_mobile" name="parameter_mobile" placeholder="Enter Mobile" value="{{ $parameter_mobile }}" type="text">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="" class="col-form-label m_sel_image">Url</label>
                                            <input class="form-control" id="url" name="url" placeholder="Enter url" value="{{ $url }}" type="text">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (Request::get("combination") === 'location')
                                <div id="location" style="display: none;">
                                    <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="location_app_name" class="col-form-label" >Next App Name</label>
                                        <select class="form-control custom-select select2" id="location_app_name" name="location_app_name" onchange="__getAppName(this.value)">   
                                            <option value="null">NULL</option>
                                                <option value="text">TEXT</option>
                                                <option value="image">IMAGE</option>
                                                <option value="video">VIDEO</option>
                                                <option value="capture">CAPTURE</option>
                                                <option value="api">API</option>
                                                <option value="timeCondition">TIME CONDITION</option>
                                                <option value="menu">MENU</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="location_app_name1" class="col-form-label">Next App Value </label>
                                        <select class="form-control custom-select select2" id="location_app_name1" name="location_app_name1" onchange="__checkAppValueCondition(this.value, 'location_app_name')">
                                            <option value="null">null</option>
                                            
                                        </select>
                                    </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="latitude" class="col-form-label m_sel_image">lat</label>
                                            <input class="form-control" id="latitude" name="latitude" placeholder="Enter lat" value="{{ $lattitude }}" type="text">
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="longitude" class="col-form-label m_sel_image">long</label>
                                            <input class="form-control" id="longitude" name="longitude" placeholder="Enter Long" value="{{ $longitude }}" type="text">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="lastName">Location</label>
                                        <textarea class="form-control mt-15 sel_msg" rows="3" placeholder="Enter location"  rows="5" cols="14" style="margin-top: 15px; margin-bottom: 5px; height: 154px;"  id="location" name="location">{{ rawurldecode($message) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (Request::get("combination") === 'timeCondition')
                                <div id="timeCondition" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 form-group schduleRow">
                                            <label for="startTime">Start Time</label>
                                            <input type="text" class="form-control input-timepicker" id="startTime" name="startTime" value="{{ $start_time }}">
                                        </div>
                                        <div class="col-md-6 form-group schduleRow">
                                            <label for="endTime">End Time</label>
                                            <input type="text" class="form-control input-timepicker" id="endTime" name="endTime" value="{{ $end_time }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="timeCondition_success_app_name" class="col-form-label m_sel_image">Success Application </label>
                                            <select class="form-control custom-select select2" id="timeCondition_success_app_name" name="timeCondition_success_app_name" onchange="__getSuccessFailureName(this.value, true)">   
                                                <option value="null">NULL</option>
                                                    <option value="text">TEXT</option>
                                                    <option value="image">IMAGE</option>
                                                    <option value="video">VIDEO</option>
                                                    <option value="capture">CAPTURE</option>
                                                    <option value="api">API</option>
                                                    <option value="timeCondition">TIME CONDITION</option>
                                                    <option value="menu">MENU</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="timeCondition_success_app_value" class="col-form-label m_sel_image">Success Application value</label>
                                            <select class="form-control custom-select select2" id="timeCondition_success_app_value" name="timeCondition_success_app_value" onchange="__checkAppValueCondition(this.value, 'timeCondition_success_app_name')">   
                                                <option value="null">null</option>
                                            </select>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label for="timeCondition_failure_app_name" class="col-form-label m_sel_image">failed Application name</label>
                                            <select class="form-control custom-select select2" id="timeCondition_failure_app_name" name="timeCondition_failure_app_name" onchange="__getSuccessFailureName(this.value, false)">   
                                                <option value="null">NULL</option>
                                                <option value="text">TEXT</option>
                                                <option value="image">IMAGE</option>
                                                <option value="video">VIDEO</option>
                                                <option value="capture">CAPTURE</option>
                                                <option value="api">API</option>
                                                <option value="timeCondition">TIME CONDITION</option>
                                                <option value="menu">MENU</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="timeCondition_failure_app_value" class="col-form-label m_sel_image" >failed Application value</label>
                                            <select class="form-control custom-select select2" id="timeCondition_failure_app_value" name="timeCondition_failure_app_value" onchange="__checkAppValueCondition(this.value, 'timeCondition_failure_app_name')">   
                                                <option value="null">null</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-1 form-group">
                                            <label for="sun" class="col-form-label m_sel_image">Sun</label>
                                            <input type="checkbox" id="sun" name="sun" value="1" <?php echo $sun == 1 ? "checked" : "" ?>>
                                        </div>
                                        <div class="col-sm-1 form-group">
                                            <label for="mon" class="col-form-label m_sel_image">Mon</label>
                                            <input type="checkbox" id="mon" name="mon" value="1" <?php echo $mon == 1 ? "checked" : "" ?>>
                                        </div>
                                        <div class="col-sm-1 form-group">
                                            <label for="tue" class="col-form-label m_sel_image">Tue</label>
                                            <input type="checkbox"  id="tue" name="tue" value="1" <?php echo $tue == 1 ? "checked" : "" ?>>
                                        </div>
                                        <div class="col-sm-1 form-group">
                                            <label for="wed" class="col-form-label m_sel_image">Wed</label>
                                            <input type="checkbox" id="wed" name="wed" value="1" <?php echo $wed == 1 ? "checked" : "" ?>>
                                        </div>
                                        <div class="col-sm-1 form-group">
                                            <label for="thu" class="col-form-label m_sel_image">Thu</label>
                                            <input type="checkbox" id="thu" name="thu" value="1" <?php echo $thu == 1 ? "checked" : "" ?>>
                                        </div>
                                        <div class="col-sm-1 form-group">
                                            <label for="fri" class="col-form-label m_sel_image">Fri</label>
                                            <input type="checkbox" id="fri" name="fri" value="1" <?php echo $fri == 1 ? "checked" : "" ?>>
                                        </div>
                                        <div class="col-sm-1 form-group">
                                            <label for="sat" class="col-form-label m_sel_image">Sat</label>
                                            <input type="checkbox" id="sat" name="sat" value="1" <?php echo $sat == 1 ? "checked" : "" ?>>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- <div class="row">
                            
                             </div> --}}
                            
                            
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
<script src="{{ asset('dist/js/moment.min.js') }}"></script>
<script src="{{ asset('dist/js/daterangepicker.js') }}"></script>
<script type="text/javascript">
    function selectedMessage(slug){
        $("select").removeClass("errorClass");
        $(".sel_text").hide();
        $("#sel_image").hide();
        $("#sel_video").hide();
        $("#capture").hide();
        $("#api").hide();
        $("#location").hide();
        $("#message").show();
        $("#timeCondition").hide();

        if(slug=='text'){
            $(".sel_text").show();
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

    function __getAppName(combination, isDefault=false){
        let appValue = "{{ (empty($app_value) ? 'null' : $app_value) }}".toLowerCase();
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
                    if(isDefault) {
                        $('#'+ $("#combination").val() +'_app_name1').val(appValue);
                    }
                },
                error: function (response) {
                    $('.preloader-it').hide();
                    $('#'+ $("#combination").val() +'_app_name1').html(result.response);
                    if(isDefault) {
                        $('#'+ $("#combination").val() +'_app_name1').val(appValue);
                    }
                }
            }
        );
    }

    function __getSuccessFailureName(combination, isForSuccess = true, isDefault=false){
    let successAppValue = "{{ (empty($success_app_value) ? 'null' : $success_app_value) }}".toLowerCase();
    let failureAppValue = "{{ (empty($failed_app_value) ? 'null' : $failed_app_value) }}".toLowerCase();
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
                    if(isDefault) {
                        $('#'+ $("#combination").val() + (isForSuccess ? '_success_' : '_failure_') +'app_value').val(isForSuccess ? successAppValue : failureAppValue);
                    }
                },
                error: function (response) {
                    $('.preloader-it').hide();
                    $('#'+ $("#combination").val() + (isForSuccess ? '_success_' : '_failure_') +'app_value').html(result.response);
                    if(isDefault) {
                        $('#'+ $("#combination").val() + (isForSuccess ? '_success_' : '_failure_') +'app_value').val(isForSuccess ? successAppValue : failureAppValue);
                    }
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
        if($(value).is(":visible") && $(value).val() == ""  && $(value).prop("tagName") != "SPAN") {
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
function setEditValues() {
    let successAppName = "{{ (empty($success_app_name) ? 'null' : $success_app_name) }}".toLowerCase();
    let failureAppName = "{{ (empty($failed_app_name) ? 'null' : $failed_app_name) }}".toLowerCase();
    let appName = "{{ (empty($app_name) ? 'null' : $app_name) }}".toLowerCase();
    $("[id*='success_app_name']").each((e, value) => {
        $(value).val(successAppName);
    });
    $("[id*='failure_app_name']").each((e, value) => {
        $(value).val(failureAppName);
    });
    selectedMessage('{{ Request::get("combination") }}');
    $('#'+ '{{ Request::get("combination") }}' +'_app_name').val(appName);
    __getAppName(appName, true);
    __getSuccessFailureName(successAppName, true, true);
    __getSuccessFailureName(failureAppName, false, true);
}

setEditValues();
</script>
<style type="text/css">
    .select2-container .select2-selection--single {
        height: 40px ! important;
    }


  .select2-container--default .select2-selection--single .select2-selection__rendered, .select2-results__option {
    text-transform: uppercase;
  }
</style>
<script src="{{ asset('dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('dist/js/select2-data.js') }}"></script>
@endsection
