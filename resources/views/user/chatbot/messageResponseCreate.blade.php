@extends('layouts.master')
@section('title', 'Message Response')
@section('content')
 <!-- select2 CSS -->
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
    @include('errors.status')
   <h6 class="hk-pg-title">@yield('title') :: Create Messages</h6>
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
                                    <label for="firstName"> Name </label>
                                    <input class="form-control" id="scrub_name" name="scrub_name" placeholder="Enter campaign name" value="" type="text" value="{{ old('scrub_name') }}">
                                    <div class="invalid-feedback">
                                        Please provide a valid name.
                                    </div>
                                </div>
                                 <div class="col-md-6 form-group">
                                    <label for="lastName"> Select combination </label>
                                     <select class="form-control custom-select select2" id="bot_application" name="bot_application" onchange="selectedMessage(this.value)">
                                            <option value="">Select</option>
                                            <option value="text">Text Only</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="location">Location</option>
                                    </select>
                                 </div>
                            </div>
                            <div class="row" id="sel_text" style="display: none;">
                                <div class="col-sm-6 form-group">
                                     <label for="bot_application" class="col-form-label" > Next App </label>
                                    <select class="form-control custom-select" id="nxt_app_name" name="nxt_app_name">
                                        <option value="">Select Next App</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                     <label for="bot_application" class="col-form-label">Next App Name </label>
                                    <select class="form-control custom-select" id="nxt_app_name1" name="nxt_app_name1">
                                        <option value="">Select Next App</option>
                                            <option value="text">Text</option>
                                    </select>
                                </div>
                            </div>
                            <div id="sel_image" style="display: none;">
                                <div class="row">
                                <div class="col-sm-6 form-group">
                                     <label for="bot_application" class="col-form-label" > Next App </label>
                                    <select class="form-control custom-select" id="nxt_app_name" name="nxt_app_name">
                                        <option value="">Select Next App</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                     <label for="bot_application" class="col-form-label">Next App Name </label>
                                    <select class="form-control custom-select" id="nxt_app_name1" name="nxt_app_name1">
                                        <option value="">Select Next App</option>
                                            <option value="text">Text</option>
                                           
                                    </select>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Select Image:</label>
                                        <input type="file" class="form-control m_sel_image" id="photo" name="photo" accept=".png, .jpg, .jpeg"><span class="m_sel_image text-sm" style="display: none;font-size:  9px;"><b>* Please upload - jpeg, jpg or PNG images with less than 4 MB size.</b></span>
                                    </div>
                                </div>
                            </div>
                            <div id="sel_video" style="display: none;">
                                <div class="row">
                                <div class="col-sm-6 form-group">
                                     <label for="bot_application" class="col-form-label" > Next App </label>
                                    <select class="form-control custom-select" id="nxt_app_name" name="nxt_app_name">
                                        <option value="">Select Next App</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                     <label for="bot_application" class="col-form-label">Next App Name </label>
                                    <select class="form-control custom-select" id="nxt_app_name1" name="nxt_app_name1">
                                        <option value="">Select Next App</option>
                                            <option value="text">Text</option>
                                           
                                    </select>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Select Image:</label>
                                        <input type="file" class="form-control m_sel_image" id="photo" name="photo" accept=".png, .jpg, .jpeg"><span class="m_sel_image text-sm" style="display: none;font-size:  9px;"><b>* Please upload - jpeg, jpg or PNG images with less than 4 MB size.</b></span>
                                    </div>
                                </div>
                            </div>
                            <div id="capture" style="display: none;">
                                <div class="row">
                                <div class="col-sm-6 form-group">
                                     <label for="bot_application" class="col-form-label" > Next App </label>
                                    <select class="form-control custom-select" id="nxt_app_name" name="nxt_app_name">   
                                        <option value="">Select Next App</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                     <label for="bot_application" class="col-form-label">Next App Name </label>
                                    <select class="form-control custom-select" id="nxt_app_name1" name="nxt_app_name1">
                                        <option value="">Select Next App</option>
                                            <option value="text">Text</option>
                                           
                                    </select>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Input validator</label>
                                        <select class="form-control custom-select" id="success_app_name" name="success_app_name">   
                                        <option value="">Input Validator</option>
                                            <option value="text">Alpha-numeric</option>
                                            <option value="image">Numeric</option>
                                            <option value="video">Email</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Success Application </label>
                                        <select class="form-control custom-select" id="success_app_name" name="success_app_name">   
                                            <option value="">sdsr</option>
                                            <option value="">sdsr</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Success Application value</label>
                                        <select class="form-control custom-select" id="success_app_value" name="success_app_value">   
                                        <option value="">Input Validator</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">failed Application name</label>
                                        <select class="form-control custom-select" id="success_app_value" name="success_app_value">   
                                        <option value="">Input Validator</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">failed Application value</label>
                                        <select class="form-control custom-select" id="success_app_value" name="success_app_value">   
                                        <option value="">Input Validator</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="api" style="display: none;">
                                <div class="row">
                                <div class="col-sm-6 form-group">
                                     <label for="bot_application" class="col-form-label" > Next App </label>
                                    <select class="form-control custom-select" id="nxt_app_name" name="nxt_app_name">   
                                        <option value="">Select Next App</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                     <label for="bot_application" class="col-form-label">Next App Name </label>
                                    <select class="form-control custom-select" id="nxt_app_name1" name="nxt_app_name1">
                                        <option value="">Select Next App</option>
                                            <option value="text">Text</option>
                                           
                                    </select>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Url</label>
                                        <input class="form-control" id="url" name="url" placeholder="Enter url" value="" type="text">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Success Application </label>
                                        <select class="form-control custom-select" id="success_app_name" name="success_app_name">   
                                            <option value="">sdsr</option>
                                            <option value="">sdsr</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Success Application value</label>
                                        <select class="form-control custom-select" id="success_app_value" name="success_app_value">   
                                        <option value="">Input Validator</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">failed Application name</label>
                                        <select class="form-control custom-select" id="success_app_value" name="success_app_value">   
                                        <option value="">Input Validator</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">failed Application value</label>
                                        <select class="form-control custom-select" id="success_app_value" name="success_app_value">   
                                        <option value="">Input Validator</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="location" style="display: none;">
                                <div class="row">
                                <div class="col-sm-6 form-group">
                                     <label for="bot_application" class="col-form-label" > Next App </label>
                                    <select class="form-control custom-select" id="nxt_app_name" name="nxt_app_name">   
                                        <option value="">Select Next App</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="capture">Capture</option>
                                            <option value="api">Api</option>
                                            <option value="menu">Menu</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                     <label for="bot_application" class="col-form-label">Next App Name </label>
                                    <select class="form-control custom-select" id="nxt_app_name1" name="nxt_app_name1">
                                        <option value="">Select Next App</option>
                                            <option value="text">Text</option>
                                           
                                    </select>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">lat</label>
                                        <input class="form-control" id="url" name="url" placeholder="Enter url" value="" type="text">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">long</label>
                                        <input class="form-control" id="url" name="url" placeholder="Enter url" value="" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="lastName">Location</label>
                                     <textarea class="form-control mt-15 sel_msg" rows="3" placeholder="Enter location"  rows="5" cols="14" style="margin-top: 15px; margin-bottom: 5px; height: 154px;"  id="location" name="location"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-md-6 form-group" id="message">
                                    <label for="lastName">Message</label>
                                     <textarea class="form-control mt-15 sel_msg" rows="3" placeholder="Enter Message"  rows="5" cols="14" style="margin-top: 15px; margin-bottom: 5px; height: 154px;" onkeyup="smsCounter();" maxlength="1000" id="message" name="message"></textarea>
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
        }
    }
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
</script>
@endsection
