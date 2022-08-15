@extends('layouts.master')
@section('title', 'Message Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title"> @yield('title') :: Inbound </h6>
        </div>
    </div>
    <div class="">
    {{-- <div class="d-flex"> --}}
        <form name="searchForm" action="" method="get">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="firstName"> </label>
                    <select class="form-control select2" name="instance_id">
                        <option value="">Select Instance</option>
                        @foreach($instanceList as $instance)
                            <option value="{{$instance->id}}" {{ ($instance_id === (int)$instance->id) ? 'selected' : '' }} >{{$instance->instance_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="lastName">  </label>
                    <input type="text" class="form-control form-control-sm new" placeholder="number" aria-controls="datable_1" name="number" value="{{ $number}}">
                </div>
                <div class="col-md-3 form-group">
                <button class="btn btn-tool btn-danger" name="search" value="search" style="margin-top: 19px;"  >Search </button>
                <button class="btn btn-tool btn-info" name="download" value="download" style="margin-top: 19px;" onclick="return bulkMessage(this.value);">Bulk <?php echo strtolower('reply') ?> </button>
                </div> 
                
            </div>
        </form>
        {{-- </div> --}}
        </div>
    <style type="text/css">
        .form-control-sm, .custom-select-sm {
            height: calc(1.8125rem + 11px);
        }
        label{
            margin-bottom: 0px ! important;
        }
    </style>
    <div class="row">
        <div class="col-xl-12">
            <div class="hk-row">
                <div class="table-responsive" style="padding: 1%;">
                    <table class="table table-hover table-bordered mb-0">
                        <thead>
                            <tr>
                                <th style="width: 0px;">Check</th>
                                <th>#</th>
                                <th>Number </th>
                                <th>Instance</th>
                                <th>Message</th>
                                <th>Received Time</th>
                                <th>Webhook Response</th>
                                <th>Status</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inboundMessages as $key=>$inbound)
                                <tr>
                                    <td class="serial">
                                        <span class="name">
                                            <input type="checkbox" class="bulkCheck" name="permissn[]" class="" id="check_{{ $inbound->number }}_{{ $inbound->instance_token }}" value="{{ $inbound->number }}_{{ $inbound->instance_token }}"></span> </td>
                                    <td> #{{ $key + $inboundMessages->firstItem() }} </td>
                                    <td><span class="product">{{ $inbound->number }}</span> 
                                    </td>
                                    @php
                                       $instanceDetai = App\Models\Instance::where('token',$inbound->instance_token)->first();
                                    @endphp
                                    <td><span class="badge badge-info">{{ $instanceDetai->instance_name }}</span></td>
                                   
                                    <td ><?php
                                        $string = strip_tags(rawurldecode($inbound->message));
                                        if (strlen($string) > 10) {

                                            // truncate string
                                            $stringCut = substr($string, 0, 10);
                                            $endPoint = strrpos($stringCut, ' ');
                                            //if the string doesn't contain any space then it will cut without word basis.
                                            $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                            $string .= '... <a href="javascript::void(0)" data-toggle="modal" data-target="#web_hook_model_message_'.$inbound->id.'"><i class="fa fa-eye" data-toggle="tooltip" data-original-title="'.rawurldecode($inbound->message).'"></a>';
                                        }
                                        ?>
                                        <?php  echo $string ?></td>
                                    </td>
                                    <td>{{ $inbound->updated_at }} </td>
                                    <td><a href="javascript::void(0)" data-toggle="modal" data-target="#web_hook_model_{{ $inbound->id }}">
                                            <i class="fa fa-eye" data-toggle="tooltip" data-original-title="view web hook responses" ></i></a>&nbsp;&nbsp;</td>
                                    <td>
                                        @if($inbound->is_status==0)
                                            <span class="badge badge-warning">Queued</span>
                                        @elseif($inbound->is_status==2)
                                        <span class="badge badge-info">Sending</span>
                                        @elseif($inbound->is_status==1)
                                            <span class="badge badge-success">Sent </span>
                                        @elseif($inbound->is_status==3)
                                            <span class="badge badge-danger">cancelled </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:void()">
                                        <button class="btn btn-secondary btn-wth-icon btn-rounded icon-right btn-xs" onclick="doubleConfirm('{{ $inbound->number }}', '{{ $inbound->instance_token }}')"><span class="btn-text">Reply</span> <span class="icon-label"><span class="feather-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 16 12 12 8"></polyline><line x1="8" y1="12" x2="16" y2="12"></line></svg></span> </span>
                                        </button></a>
                                    </td>
                                </tr>
                                <div class="modal fade show" id="web_hook_model_message_{{ $inbound->id }}" tabindex="-1" role="dialog" aria-labelledby="web_hook_model_message_{{ $inbound->id }}" aria-modal="true">
                                        <div class="modal-dialog modal-sm" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Message</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{ rawurldecode($inbound->message) }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @empty
                                <tr>
                                    <td colspan="9"> No Inbound Messages in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($inboundMessages->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$inboundMessages->firstItem()}} to {{$inboundMessages->lastItem()}} of {{$inboundMessages->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $inboundMessages->appends(Request::get('qa'))->links() }}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- End Webhook Model--}}
    @php
    foreach ($inboundMessages as $v => $webHook) { @endphp
        <div class="modal fade show" id="web_hook_model_{{ $webHook->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLarge01" aria-modal="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Web hook responses</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="table-wrap">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Status</th>
                                                <th>Response</th>
                                                <th>start time</th>
                                                <th>end time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $webHook->web_hook_url_response_code }}</td>
                                                <td>{{ $webHook->web_hook_url_response }}</td>
                                                <td>{{ $webHook->web_hook_url_start_time }}</td>
                                                <td>{{ $webHook->web_hook_url_end_time }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @php }
    @endphp
    
    {{-- End Webhook Model--}}
    <div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
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
                    <form id="sendMessageForm" method="POST" action="{{ route('ajax.create.inbound.campaign') }}" enctype="multipart/form-data">
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
                            <input type="text"  class="form-control" disabled="disabled">
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
                                     <div class="custom-control custom-checkbox checkbox-primary">
                                        <input type="checkbox" class="custom-control-input" id="optOut" name="optOut" checked="checked">
                                        <label class="custom-control-label" for="optOut">Opt-Out</label>
                                        <span class="btn btn-danger btn-xs pull-right btn-rounded" maxlength="1000" id="msg_count_id">1000</span>
                                    </div>
                                 </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                         <button class="btn btn-primary" id="sendBtn" type="submit">Send</button>
                    </div> 
                    
                </div></div>
                </form> 
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="{{ asset('dist/js/jquery.min.js') }}"></script>
    <script type="text/javascript">
        var bulkData = [];
        $(".bulkCheck").on('click', function(event){
            let key = $(this).val();
            if($('#check_'+ $(this).val()).is(":checked") == true){
                let newVal = $(this).val().split('_');
                bulkData.push({
                    uniquevalue: key,
                    number: newVal[0],
                    instance_token:newVal[1] 
                });
            }else{
                 bulkData.filter(function(elem,i){
                    if(elem.uniquevalue ==key){
                        bulkData.splice(i, 1);
                    }
                });
            }
        });
        function doubleConfirm(number, instance){
            if (confirm('Are you sure you want to continue ?')){
                bulkData.push({
                    uniquevalue: instance,
                    number: number,
                    instance_token:instance
                });
                $("#exampleModal").modal('show');
                return false;
            }
        }
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
            formData.append('bulkData', JSON.stringify(bulkData));
            if(bulkData.length !=0){
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
            }else{
                alert("Checked is missing..");
            }
            
        }
    });
        function bulkMessage($event){
            if (confirm('Are you sure you want to sent this campaign ?')){
                $("#exampleModal").modal('show');
                return false;
            }
            return false;
        }
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
    $(document).on('change', '#message_type', function (e) {
        var selected_dt = $(this).val();
        $('.sel_image').hide();
        $('.sel_document').hide();
        $('.sel_audio').hide();
        $('.sel_video').hide();
        $('.sel_image').prop('required',false);
        $('.sel_document').prop('required',false);
        $('.sel_audio').prop('required',false);
        $('.sel_video').prop('required',false);
        $('#message').prop('required',false);
        if(selected_dt==''){
            return false;
        }else{
            if (selected_dt=='image') {
                $('.sel_image').show();
                $('.sel_image').prop('required',true);
                $('#message').prop('required',true);
                $('.sel_msg').show();
            }
            else if (selected_dt=='document') {
                $('.sel_document').show();
                $('.sel_document').prop('required',true);
                $('#message').prop('required',false);
                $('.sel_msg').hide();
            }
            else if (selected_dt=='audio') {
                $('.sel_audio').show();
                $('.sel_audio').prop('required',true);
                $('#message').prop('required',false);
                $('.sel_msg').hide();            
            }
            else if (selected_dt=='video') {
                $('.sel_video').show();
                $('.sel_video').prop('required',true);
                $('#message').prop('required',true);
                $('.sel_msg').show();
            }
            else{
                $('.sel_image').hide();
                $('.sel_document').hide();
                $('.sel_audio').hide();
                $('.sel_video').hide();

                $('.sel_image').prop('required',false);
                $('.sel_document').prop('required',false);
                $('.sel_audio').prop('required',false);
                $('.sel_video').prop('required',false);
                $('#message').prop('required',true);
                $('.sel_msg').show();
            }
        }     
        });
    function smsCounter() {
        var msg = $('#message').val();
        var char_count  = msg.length;
        /*var msg_count   = 1;
        if(char_count>15){
            msg_count = char_count / 15;
        }
        var counter_text = char_count +' / ' + msg_count;*/

        var maxLength = 1000;
        var counter_text = maxLength - char_count;
        //alert(msg);
        $('#msg_count_id').empty().html(counter_text); 
    }
    
    </script>
@endsection
