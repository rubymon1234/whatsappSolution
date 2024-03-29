@extends('layouts.master')
@section('title', 'Message Management')
@section('content')
 <!-- select2 CSS -->
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Daterangepicker CSS -->
<link href="{{ asset('dist/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
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
        <div class="col-xl-9">
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
                                     <textarea class="form-control mt-15 sel_msg" rows="3" placeholder="Enter Message"  rows="5" cols="14" style="margin-top: 15px; margin-bottom: 5px; height: 154px;" onkeyup="smsCounter();" maxlength="10000" id="message" name="message"></textarea>
                                     <div class="custom-control custom-checkbox checkbox-primary">
                                        <input type="checkbox" class="custom-control-input" id="optOut" name="optOut" checked="checked">
                                        <label class="custom-control-label" for="optOut">Opt-Out</label>
                                        <span class="btn btn-danger btn-xs pull-right btn-rounded" maxlength="10000" id="msg_count_id">10000</span>
                                    </div>
                                 </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 form-group">
                                    {{-- <label for="schedule"> Schedule </label> <br>--}}
                                    <div class="toggle toggle-light toggle-bg-primary toggle2">
                                    </div>
                                    <input type="hidden" name="is_scheduled" id="is_scheduled">
                                </div>
                                <div class="col-md-3 form-group schduleRow">
                                    {{-- <label for="">Date</label> --}}
                                    <input type="date" class="form-control" id="sch_date" name="sch_date" min="{{ date('Y-m-d')}}">
                                </div>
                                <div class="col-md-2 form-group schduleRow">
                                    {{-- <label for="">Time</label> --}}
                                    <input type="text" class="form-control input-timepicker" id="sch_time" name="sch_time">
                                </div>
                            </div>
                            <button class="btn btn-primary pull-center" id="sendBtn" style="margin-left: 45%;" type="submit">Send</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xl-3">
            <div class="card card-profile-feed" style="height: 100%;">
                <div class="card-header card-header-action">
                    <h6 style="text-align:center;">Addressbook</h6>
                </div>
                <hr style=" margin-top: 0px; margin-bottom: 0px;">
                <div id="grouTot" class="fp1" style="padding: 0px;">
                    <table class="table table-bordered" style="margin-bottom: 0px;">
                        <thead>
                            <tr>
                                <th colspan="3" style="text-align:center;">
                                    Groups
                                </th>
                                {{-- <th colspan="2">Select All</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupDetails as $group)
                                <tr class="group_test">
                                    <td style="text-align:center;">
                                      <input type="checkbox" value="{{$group->id}}" id="group_{{$group->id}}" name="group_{{$group->id}}" onclick="groupSelect({{$group->id}})"></td>
                                    <td>
                                        {{$group->group_name}} 
                                    </td>
                                    <td>
                                        @php
                                    $groupCount = \App\Helpers\Helper::getGroupContactCount($group->id);
                                    @endphp
                                        <span class="badge badge-info">{{$groupCount}}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <table class="table table-bordered">
                        <tbody>
                          <tr style="border-top: #e0e3e4 1px solid;">
                            <td><strong class="Themecolor">Selected Group's </strong></td>
                                  <td><span class="badge badge-group" id="totalGroup" style="">  0 </span></td>
                          </tr>
                          <tr>
                            <td><strong class="Themecolor">Total Group's  </strong></td>
                                  <td><span class="badge badge-group" id="totgrp1" style="">{{ count($groupDetails) }}</span></td>
                          </tr>
                        </tbody>
                      </table>
                 </div>
            </div>
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
<!-- Daterangepicker JavaScript -->
<script src="{{ asset('dist/js/moment.min.js') }}"></script>
<script src="{{ asset('dist/js/daterangepicker.js') }}"></script>
<style type="text/css">
    .daterangepicker.ltr .drp-calendar.left{
        display: none;
    }
    .daterangepicker .drp-selected {
        display: none;
    }
</style>
<script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script type="text/javascript">
    $('.input-timepicker').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 1,
        timePickerSeconds: true,
        locale: {
            format: 'HH:mm:ss'
        },
        onSelect: function(d,s) {
            alert('s');
        },
    }).on('show.daterangepicker', function (ev, picker) {
        picker.container.find(".calendar-table").hide();
    }).change(function(date) {
    });
    $('.input-timepicker').val('00:00:00');
    $('.input-timepicker').change(function(e){
        selectedDate = $('.input-timepicker').val();
        myArr = selectedDate.split(" ");
        $('.input-timepicker').val(myArr[2]);
    });
     $('#is_scheduled').on({
            change : function() {
                if(this.checked) {
                    $('.schedule_cls').show();
                    $('#scheduled_date').prop('required',true);
                    $('#scheduled_time').prop('required',true);
                }else{
                    $('.schedule_cls').hide();
                    $('#scheduled_date').val('');
                    $('#scheduled_time').val('');
                    $('#scheduled_date').prop('required',false);
                    $('#scheduled_time').prop('required',false);
                }
            },
        });
    $(document).on('click', '.toggle2', function (e) {
        var scheduleOn = $(".toggle-on").hasClass('active');
        if(scheduleOn){
            $('.schduleRow').show();
            $('#is_scheduled').val(1);
        }else{
            $('.schduleRow').hide();
            $('#is_scheduled').val(0);
        }
    });
        $('#sendMessageForm').submit(function(e){
            e.preventDefault();
            $("#error_message").text('');
            $("#success_message").text('');
            $("#error_message").hide();
            $("#success_message").hide();
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
                            $('#sendMessageForm')[0].reset();
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
    function groupSelect(id){
        var groupsel = '';
        $('#grouTot :checked').each(function() {
            if(groupsel!='')groupsel+=',';
           groupsel+=$(this).val();
            var id2 = $(this).val();
           $('#group_'+id2).attr('checked', false);  
        });
        if(groupsel==''){
             document.getElementById('totalGroup').innerHTML=0;
        }else{
            var separated = groupsel.split(",");
            $.each(separated, function(index, chunk) {
                var countNumbers = index+1;
                document.getElementById('totalGroup').innerHTML=countNumbers;
            });
        }
        $.ajax(
            {
                url: '{{ route('ajax.group.contacts') }}',
                dataType: 'json', // what to expect back from the PHP script
                cache: false,
                data: { 
                        _token: "{{ csrf_token() }}",
                        group_ids : groupsel ,
                    },
                type: 'POST' ,
                beforeSend: function () {
                    $('.preloader-it').show();
                },
                complete: function () {
                    $('.preloader-it').hide();
                },
                success: function (result) {
                    $('#loading').hide();
                    result = result.response;
                    var res1=result.replace(/\s/g, "\n");
                    tot = res1.replace(/^\s*$[\n\r]{1,}/gm, '');
                    tot = tot.trim();
                    var elem = tot.split('\n');
                    var str =elem.join(' ');
                    var str = str.replace(/\s/g,'\n');
                    document.getElementById('mobile').value =str;
                    checkNumberCount();
                },
                error: function (response) {
                    console.log('Server error');
                }
            }
        );
    };
</script>

@endsection
