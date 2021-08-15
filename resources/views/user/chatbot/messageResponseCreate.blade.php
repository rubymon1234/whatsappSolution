@extends('layouts.master')
@section('title', 'Scrub Management')
@section('content')
 <!-- select2 CSS -->
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
    @include('errors.status')
   <h6 class="hk-pg-title">@yield('title') :: Create Campaign</h6>
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
                                    <label for="firstName">Campaign name </label>
                                    <input class="form-control" id="scrub_name" name="scrub_name" placeholder="Enter campaign name" value="" type="text" value="{{ old('scrub_name') }}">
                                    <div class="invalid-feedback">
                                        Please provide a valid campaign name.
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="lastName">Instance </label>
                                    <select class="form-control custom-select select2" id="instance" name="instance">
                                        <option value="">Select Instance</option>
                                            
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
