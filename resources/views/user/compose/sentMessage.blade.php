@extends('layouts.master')
@section('title', 'Message Management')
@section('content')
 <!-- select2 CSS -->
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">

<div class="alert alert-danger alert-dismissable" id="error_message" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <p class="errorText"></p>
</div>
<div class="alert alert-success alert-dismissable" id="success_message" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

</div>
<div class="alert alert-warning alert-dismissable" id="warning_message" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

</div>

   <h6 class="hk-pg-title">@yield('title') :: Compose </h6>
   <p class="mb-20"></p>
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <div class="row">
                    <div class="col-sm">
                        <form id="sendMessageForm" method="POST" action="{{ route('user.compose.sent.message') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="firstName">Campaign </label>
                                    <input class="form-control" id="campaign" name="campaign" placeholder="Enter campaign name" value="" type="text">
                                    <div class="invalid-feedback">
                                        Please provide a valid campaign.
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
                                    <label for="lastName">Select combination</label>
                                     <select class="form-control custom-select select2" id="message_type" name="message_type">
                                        <option value="">Select Combination</option>
                                            <option value="text">Text Only</option>
                                            <option value="image">Text + Image</option>
                                            <option value="video">Text + Video</option>
                                            <option value="audio">Audio Only</option>
                                            <option value="document">Document Only</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please provide a combination.
                                    </div>
                                 </div>
                                 <div class="col-sm-6 form-group">
                                <label for="message_type" class="col-form-label sel_image" style="display: none;">Select Image:</label>
                                <input type="file" class="form-control sel_image" id="photo" name="photo" accept=".png, .jpg, .jpeg" style="display: none;"><span class="sel_image text-sm" style="display: none;font-size:  9px;"><b>* Please upload - jpeg, jpg or PNG images with less than 4 MB size.</b></span>

                                <label for="message_type" class="col-form-label sel_document" style="display: none;">Select Document:</label>
                                <input type="file" class="form-control sel_document" id="doc_file" name="doc_file" accept=".doc, .xls , .ppt, .docx, .xlsx, .pptx, .pdf, .txt" style="display: none;">
                                <span class="sel_document text-sm" style="display: none;font-size:  9px;"><b>* Please upload - doc, xls , ppt, docx, xlsx, pptx, pdf or txt documents with less than 4 MB size.</b></span>

                                <label for="message_type" class="col-form-label sel_audio" style="display: none;">Select Audio:</label>
                                <input type="file" class="form-control sel_audio" id="audio_file" name="audio_file" accept=".aac, .mp3, .amr, .mpeg" style="display: none;">
                                <span class="sel_audio text-sm" style="display: none;font-size:  9px;"><b>*Please upload - aac, mp3, amr or mpeg audio with less than 4 MB size.</b></span>

                                <label for="message_type" class="col-form-label sel_video" style="display: none;">Select Video:</label>
                                <input type="file" class="form-control sel_video" id="video_file" name="video_file" accept=".mp4, .3gpp" style="display: none;">
                                <span class="sel_video text-sm" style="display: none;font-size:  9px;"><b>* Please upload - mp4, or 3gpp video with less than 4 MB size.</b></span>
                            </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-6 form-group">
                                    <label for="">Send To</label>
                                     <textarea class="form-control mt-15" name="mobile" id="mobile" rows="3" placeholder="Enter Mobile Numbers With Country Code"  rows="5" cols="14" style="margin-top: 15px; margin-bottom: 5px; height: 154px;" onkeypress="return isNumberKey(event);" wrap="HARD"></textarea>
                                     <span class="btn btn-success btn-xs" id="removeDups">Remove Duplicate</span>
                                     <span class="btn btn-danger btn-xs" id="clearNums">Clear Numbers</span>
                                     <span class="btn btn-info btn-xs pull-right btn-rounded" id="num-counter">0</span>
                                 </div>
                                 <div class="col-md-6 form-group">
                                    <label for="lastName">Message</label>
                                     <textarea class="form-control mt-15 sel_msg" rows="3" placeholder="Enter Message"  rows="5" cols="14" style="margin-top: 15px; margin-bottom: 5px; height: 154px;" onkeyup="smsCounter();" id="message" name="message"></textarea>
                                    <span class="btn btn-danger btn-xs pull-right btn-rounded" maxlength="1000">1000</span>
                                 </div>
                            </div>
                            <button class="btn btn-primary pull-center" id="sendBtn" style="margin-left: 45%;" type="submit">Send</button>
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
</style>
<script src="{{ asset('dist/js/jquery.min.js') }}"></script>
<script src="{{ asset('dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('dist/js/select2-data.js') }}"></script>
<script src="{{ asset('dist/js/custom-script.js') }}"></script>
<script type="text/javascript">

        $('#sendMessageForm').submit(function(e){
            e.preventDefault();
            $("#error_message").text('');
            $("#error_message").hide();
            valid = validationError();
            if(valid===true){
            //$('#sendBtn').prop('disabled', true);
            var form    = $(this);
            let formData = new FormData(this);

            $.ajax(
                {
                    url  : form.attr('action'),
                    dataType: 'json', //what to expect back from the PHP script
                    data : formData,
                    cache: false,
                    type : form.attr('method'),
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('.preloader-it').show();
                    },
                    complete: function () {
                        $('.preloader-it').hide();
                    },
                    success: function (response) {
                        $('.preloader-it').hide();
                        console.log(response);
                        if(!response.success){
                            $("#error_message").show();
                            if(response.validator){
                                serverValidation(response.response.customMessages);
                            }else{
                               $("#error_message").text(response.response);
                            }
                        }else{
                            $("#success_message").show();
                            $("#success_message").text(response.response);
                            /*window.setTimeout( function(){
                                location.reload();
                            }, 4000);*/
                        }
                    },
                    error: function (response) {
                        console.log("d",response);
                    }
                }
            );
        }
    });
    function serverValidation(customMessages){
        message = '';
        for(key in customMessages) {
          if (customMessages.hasOwnProperty(key)) {
             message+= customMessages[key]+"  ,  ";
          }
        }
        $("#error_message").show();
        $("#error_message").text(message);
    }
    function validationError(){

        i = 0;
        campaign        = $("#campaign").val();
        instance        = $("#instance").val();
        instance        = $("#instance").val();
        message_type    = $("#message_type").val();
        mobile          = $("#mobile").val();
        //message         = $("#message").val();

        if(campaign =='' || campaign =='undefined'){
            $("#campaign").addClass('is-invalid');
            i++;
        }else{
            $("#campaign").removeClass("is-invalid");
        }
        if(instance =='' || instance =='undefined'){
            $("#instance").addClass('is-invalid');
            i++;
        }else{
            $("#instance").removeClass("is-invalid");
        }
        if(message_type =='' || message_type =='undefined'){
            $("#message_type").addClass('is-invalid');
            i++;
        }else{
            $("#message_type").removeClass("is-invalid");
        }
        if(mobile =='' || mobile =='undefined'){
            $("#mobile").addClass('is-invalid');
            i++;
        }else{
            $("#mobile").removeClass("is-invalid");
        }
        /*if(message =='' || message =='undefined'){
            $("#message").addClass('is-invalid');
            i++;
        }else{
            $("#message").removeClass("is-invalid");
        }*/
        if(i ==0){
            return true;
        }else{
            return false;
        }
    }
</script>
@endsection
