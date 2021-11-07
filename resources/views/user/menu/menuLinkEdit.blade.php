@extends('layouts.master')
@section('title', 'Menu')
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
        <h6 class="hk-pg-title">@yield('title') :: Edit Menu</h6>
        <p class="mb-20"></p>
        <div class="row">
            <div class="col-xl-12">
                <form id="scrubForm" method="POST" action="{{ route('user.chat.bot.menu.update') }}"
                    enctype="multipart/form-data">
                    <section class="hk-sec-wrapper" id="initialWrapper">
                        <div class="row">
                            <div class="col-sm">
                                {{ csrf_field() }}
                                <input type="hidden" name="keySet" id="keySetId">
                                <input type="hidden" name="id" id="id" value="{{ $id }}">
                                <input type="hidden" id="menuRemoveRow" name="menuRemoveRow">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="firstName"> Name </label>
                                        <input class="form-control" id="name" name="name" placeholder="Enter name"
                                            type="text" value="{{ $name }}">
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="appName" class="col-form-label">App Name</label>
                                        <select class="form-control custom-select select2" id="appName" name="appName"
                                            onchange="__getAppName(this.value, 'appValue')">
                                            <option value=""></option>
                                            <option value="text">TEXT</option>
                                            <option value="image">IMAGE</option>
                                            <option value="video">VIDEO</option>
                                            <option value="button">BUTTON</option>
                                            <option value="list">LIST</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="appValue" class="col-form-label">App Value </label>
                                        <select class="form-control custom-select select2" id="appValue" name="appValue"
                                            onchange="__checkAppValueCondition(this.value, 'appName')">
                                            <option value=""></option>

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Invalid App Name </label>
                                        <select class="form-control custom-select select2" id="invalidAppName" name="invalidAppName"
                                            onchange="__getAppName(this.value, 'invalidAppValue')">
                                            <option value=""></option>
                                            <option value="text">TEXT</option>
                                            <option value="image">IMAGE</option>
                                            <option value="video">VIDEO</option>
                                            <option value="capture">CAPTURE</option>
                                            <option value="api">API</option>
                                            <option value="timeCondition">TIME CONDITION</option>
                                            <option value="location">LOCATION</option>
                                            <option value="menu">MENU</option>
                                            <option value="button">BUTTON</option>
                                            <option value="list">LIST</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Invalid App value</label>
                                        <select class="form-control custom-select select2" id="invalidAppValue"
                                            name="invalidAppValue"
                                            onchange="__checkAppValueCondition(this.value, 'invalidAppName')">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-sm-12 form-group">
                                    <label for="button_title" class="col-form-label" >KeySet</label>
                                    @php
                                    foreach ($menuInput as $key => $keySet) { 
                                    $appValueList = [];
                                    @endphp
                                    <div class="input-group mb-3 item" id="inputFormRow" >
                                        <select class="form-control" id="keyAppName_{{$key}}" name="appNameSet[]" onchange="__getAppName(this.value, 'keyAppValue_{{$key}}')">
                                            <option value="">SELECT APPNAME</option>
                                            <option @php 
                                            if($keySet->keyAppName=='text'){ echo 'selected';}  @endphp value="text">TEXT</option>
                                            <option @php if(strtolower($keySet->keyAppName)=='image'){ echo 'selected';} @endphp value="image">IMAGE</option>
                                            <option @php if(strtolower($keySet->keyAppName=='video')){ echo 'selected';} @endphp value="video">VIDEO</option>
                                            <option @php if(strtolower($keySet->keyAppName)=='capture'){ echo 'selected';} @endphp  value="capture">CAPTURE</option>
                                            <option @php if(strtolower($keySet->keyAppName)=='api'){ echo 'selected';} @endphp  value="api">API</option>
                                            <option @php if(strtolower($keySet->keyAppName)=='timeCondition'){ echo 'selected';} @endphp  value="timeCondition">TIME CONDITION</option>
                                            <option  @php if(strtolower($keySet->keyAppName)=='location'){ echo 'selected';} @endphp value="location">LOCATION</option>
                                            <option @php if(strtolower($keySet->keyAppName)=='menu'){ echo 'selected';} @endphp  value="menu">MENU</option>
                                            <option @php if(strtolower($keySet->keyAppName)=='button'){ echo 'selected';} @endphp  value="button">BUTTON</option>
                                            <option @php if(strtolower($keySet->keyAppName)=='list'){ echo 'selected';} @endphp  value="list">LIST</option>
                                        </select> &nbsp;
                                        @php
                                        $appValueList = \App\Helpers\Helper::getNextAppNameHelpher(strtolower($keySet->keyAppName),$keySet->keyAppValueInInt);
                                        @endphp
                                        <select class="form-control" id="keyAppValue_{{$key}}" name="appValueSet[]"
                                           onchange="handleChange(this.value,'keyAppValue_{{$key}}'');">
                                           <option value="">SELECT APPVALUE</option>
                                           @php
                                           foreach ($appValueList as $v) { @endphp
                                                <option @php if($keySet->keyAppValueInInt==$v->id){ echo 'selected';} @endphp value="{{ $v->id }}">{{ $v->name }}</option>
                                           @php }
                                           @endphp
                                        </select>&nbsp;
                                        <select class="form-control" id="key_type_{{$key}}" onchange="handleChange(this.value,'keyAppValue',{{$key}});" name="type[]" >
                                            <option value="key" @php if($keySet->type=='key'){ echo 'selected';} @endphp > KEY </option>
                                            <option value="button" @php if($keySet->type=='button'){ echo 'selected';} @endphp > BUTTON </option>
                                            <option value="list" @php if($keySet->type=='list'){ echo 'selected';} @endphp> LIST </option></select>
                                        @php
                                    $appBodiesList = \App\Helpers\Helper::getNextAppNameHelpher($keySet->type);
                                        @endphp
                                        <select class="form-control" id="key_type_app_name_{{$key}}" onchange="addDropdown(this.value,{{$key}});" name="key1[]" @php 
                                         if($keySet->type=='button' || $keySet->type=='list'){ echo 'style="display:block;"'; }else{ echo 'style="display:none"'; } @endphp>
                                            @php
                                            foreach ($appBodiesList as $b) { @endphp
                                                <option @php if($keySet->set_key_primary==$b->id){ echo 'selected';} @endphp value="{{ $b->id }}">{{ $b->name }}</option>
                                            @php }
                                            @endphp
                                            
                                        </select>
                                        @php
                                        $appBodiessList = \App\Helpers\Helper::getBodiesHelpher($keySet->type,$keySet->set_key_primary);
                                        @endphp
                                        &nbsp;<select class="form-control" id="key_type_app_bodies_{{$key}}" onchange="handleChange(this.value,'0');" name="key2[]" @php 
                                         if($keySet->type=='button' || $keySet->type=='list'){ echo 'style="display:block;"'; }else{ echo 'style="display:none"'; } @endphp>
                                             @php
                                            foreach ($appBodiessList as $c) { @endphp
                                                <option @php if($keySet->set_key_secondary==$c->id){ echo 'selected';} @endphp value="{{ $c->id }}">{{ $c->body }}</option>
                                            @php } @endphp
                                            </select>
                                        &nbsp;
                                       
                                        <input class="form-control" id="inputKey_keyAppValue_{{$key}}" name="inputKey[]" placeholder="Enter Key" value="{{ rawurldecode($keySet->inputKey)}}" type="text" 
                                         @php 
                                         if($keySet->type=='key'){ echo 'style="display:block;"'; }else{ echo 'style="display:none"'; } @endphp >
                                        
                                        <div class="input-group-append">
                                            <button id="removeRow" type="button" class="btn btn-danger" attr-interactiveKey="{{$keySet->id}}">Remove</button>
                                        </div>
                                    </div>
                                    @php }
                                    @endphp
                                    <div id="newRow"></div>
                                    <br/>
                                    <button id="addRow" type="button" class="btn btn-info">Add Key</button>
                                </div>
                                </div>
                                @if ($errors->any())
                                    <label class="control-label" for="inputError" style="color: #dd4b39"><i
                                            class="fa fa-times-circle-o"></i>
                                        {{ implode(' | ', $errors->all(':message')) }} .</label>
                                    <br>
                                @endif
                                <button class="btn btn-primary pull-center" id="sendBtn" style="margin-left: 45%;"
                                    type="button">Update</button>
                            </div>
                        </div>
                    </section>
                    <section class="hk-sec-wrapper" id="nextWrapper" style="display: none">
                        <div class="row">
                            <div class="col-sm">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <div class="card bg-light mb-3">
                                            <div class="card-header">Menu Summary
                                                <button class="btn btn-primary pull-center" id="backBtn"
                                                    style="float: right;" type="button">Edit</button>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text name">Name : <span>Hai</span></p>
                                                <p class="card-text appName">App Name : <span>Hai</span>
                                                <p class="card-text invalidAppName">Invalid App Name : <span>Hai</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                @if ($errors->any())
                                    <label class="control-label" for="inputError" style="color: #dd4b39"><i
                                            class="fa fa-times-circle-o"></i>
                                        {{ implode(' | ', $errors->all(':message')) }} .</label>
                                    <br>
                                @endif
                                <button class="btn btn-primary pull-center" id="sendBtn" style="margin-left: 45%;"
                                    type="button">Update</button>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('dist/js/jquery.min.js') }}"></script>
    <script type="text/javascript">
    var keyList = [];
        function __getAppName(combination, targetId, needDefaultValue=false, defaultValue="") {
            $.ajax({
                url: '{{ route('ajax.message.request.appname') }}',
                dataType: 'json', // what to expect back from the PHP script
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    combination: combination
                },
                type: 'POST',
                beforeSend: function() {
                    $('.preloader-it').show();
                },
                complete: function() {
                    $('.preloader-it').hide();
                },
                success: function(result) {
                    $('.preloader-it').hide();
                    $('#' + targetId).html(result.response);
                    if(needDefaultValue) {
                        $('#' + targetId).val(defaultValue);
                    }
                },
                error: function(response) {
                    $('.preloader-it').hide();
                    $('#' + targetId).html(result.response);
                    if(needDefaultValue) {
                        $('#' + targetId).val(defaultValue);
                    }
                }
            });
        }

        function __checkAppValueCondition(value, targetId) {
            let appNameElementVal = $("#" + targetId).val();
            if ((value == 'null' || value == null) && (appNameElementVal != 'null' && appNameElementVal != null)) {
                alert("App name should be null if App value is null");
                $("#" + targetId).val("null");
            }
        }

        $("#sendBtn").click(function(event) {
            event.preventDefault();
            $("#scrubForm").submit();
           /* if (!__appValueValidationCheck()) {
                $("#keySetId").val(JSON.stringify(keyList));
                $("form").submit();
            }*/
        });

        function __appValueValidationCheck() {
            let hasFormError = false;
            $("select").removeClass("errorClass");
            $("#appValue, #invalidAppValue").each((e, value) => {
                if ($(value).val() == "") {
                    $("#nextWrapper").hide();
                    $("#initialWrapper").show();
                    $(value).addClass("errorClass");
                    hasFormError = true;
                }
            });
            if ($("#name").val() == "") {
                $("#nextWrapper").hide();
                $("#initialWrapper").show();
                $("#name").addClass("errorClass");
                hasFormError = true;
            }
            return hasFormError;
        }

        /*$("#nextButton").click(function() {
            $("#nextWrapper").show();
            $("#initialWrapper").hide();
            $(".card-text.name span").text($("#name").val());
            $(".card-text.appName span").text($("#appName").val());
            $(".card-text.invalidAppName span").text($("#invalidAppName").val());
        });

        $("#backBtn").click(function() {
            $("#nextWrapper").hide();
            $("#initialWrapper").show();
        });*/
        function __listKeyValues() {
            let response = "";
            keyList.forEach((value, key) => {
                response += "<tr><th style='text-transform: lowercase;'>"+ decodeURIComponent(value.inputKey) +"</th><td>"+ value.keyAppName +"</td><td>"+ value.keyAppValue +"</td><td><button type='button'onclick='removeKey("+ key +")' class='btn btn-link'>Remove</button></td></tr>";
            });
            $("#keyData").html(response);
        }

        function addToKeySet() {
            if(__checkValidEntry()) {
                if(!__checkKeyExist()) {
                    $(".key-exist").hide();
                    keyList.push({
                        "inputKey": decodeURIComponent($("#inputKey").val()),
                        "keyAppName": $("#keyAppName").val().toUpperCase(),
                        "keyAppValue": $("#keyAppValue option:selected").text().toUpperCase(),
                        "keyAppValueInInt":  $("#keyAppValue").val()
                    });
                    __listKeyValues();
                    __getAppName("null", 'keyAppValue');
                    $("#inputKey").val("");
                    $("#keyAppName").val("null");
                    $("#keyAppValue").val("null");
                } else {
                    $(".key-exist").show();
                }
            }
        }

        function __checkKeyExist() {
            response = false;
            if($("#inputKey").val() == "") {
                $(".key-exist").text("Key should not be empty");
                return true;
            } else {
                keyList.forEach((value, key) => {
                    if(value.inputKey == $("#inputKey").val()) {
                        response = true;
                    }
                });
                $(".key-exist").text("Key already exist!! ");
            }
            return response;
        }

        function __checkValidEntry() {
            response = true;
            let appName = $("#keyAppName").val();
            let appValue = $("#keyAppValue").val();
            if(appValue == "" || (appValue == "null" && appName != 'null')) {
                response = false;
                alert("Next App name should be null if Next App value is null");
            }
            return response;
        }

        function removeKey(index) {
            $.ajax({
                url: '{{ route('user.menu.delete.key') }}',
                dataType: 'json', // what to expect back from the PHP script
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: keyList[index]['id']
                },
                type: 'POST',
                beforeSend: function() {
                    $('.preloader-it').show();
                },
                complete: function() {
                    $('.preloader-it').hide();
                },
                success: function(result) {
                    $('.preloader-it').hide();
                    keyList.splice(index, 1);
                    __listKeyValues();
                },
                error: function(response) {
                    $('.preloader-it').hide();    
                    keyList.splice(index, 1);
                    __listKeyValues();
                }
            });
        }
        $("#addRow").click(function () {
        var html = '';
        id_value ="keyAppValue_"+$('.item').length;
        id_app_name ="keyAppName_"+$('.item').length;
        tuple = $('.item').length;
        console.log(tuple);
        html += '<div id="inputFormRow" class="item">';
        html += '<div class="input-group mb-3">';
        html += '<select class="form-control custom-select select2" id="'+id_app_name+'" name="appNameSet[]" onchange="__getAppName(this.value,\''+id_value+'\')"><option value="">SELECT APPNAME</option><option value="text">TEXT</option><option value="image">IMAGE</option><option value="video">VIDEO</option><option value="capture">CAPTURE</option><option value="api">API</option><option value="timeCondition">TIME CONDITION</option><option value="location">LOCATION</option><option value="menu">MENU</option><option value="button">BUTTON</option><option value="list">LIST</option></select>&nbsp;';
        html += '<select class="form-control custom-select select2" id="'+id_value+'" name="appValueSet[]" onchange="__checkAppValueCondition(this.value, \''+id_app_name+'\');"><option value=""> SELECT APPVALUE </option></select>&nbsp;';

        html += '<select class="form-control custom-select select2" id="key_type_'+tuple+'" onchange="handleChange(this.value,\''+id_value+'\',\''+tuple+'\');" name="type[]"><option value="key"> KEY </option><option value="button"> BUTTON </option><option value="list"> LIST </option></select>&nbsp;';

        html += '<select class="form-control custom-select select2" id="key_type_app_name_'+tuple+'" onchange="addDropdown(this.value,\''+tuple+'\');" style="display:none;" name="key1[]"></select>&nbsp;';
        html += '<select class="form-control custom-select select2" id="key_type_app_bodies_'+tuple+'" onchange="handleChange(this.value,\''+tuple+'\',\''+tuple+'\');" style="display:none;" name="key2[]"></select>&nbsp;';
        html += '<input class="form-control" id="inputKey_'+id_value+'" name="inputKey[]" placeholder="Enter Key" type="text" value="">';
        html += '<div class="input-group-append">';
        html += '<button id="removeRow" type="button" class="btn btn-danger">Remove</button>';
        html += '</div>';
        html += '</div>';
        $('#newRow').append(html);
    });
    function handleChange(app_name,key_remove_id,item){
        var app_name = $("#key_type_"+item).val();
        //$("#key_type_app_bodies_"+item).empty();
        console.log(key_remove_id);
        if(app_name =='button' || app_name =="list"){
            $("#inputKey_keyAppValue_"+item).css('display','none');
            $("#key_type_app_name_"+item).css('display','block');
            $("#key_type_app_bodies_"+item).css('display','block');
            app_value = $("#keyAppValue_"+item).val();
            __getAppName(app_name,'key_type_app_name_'+item);

        }else{
            $("#key_type_app_name_"+item).css('display','none');
            $("#key_type_app_bodies_"+item).css('display','none');
            $("#inputKey_keyAppValue_"+item).css('display','block');
        }
    }
    function addDropdown(app_value,item){
        var app_name = $("#key_type_"+item).val();
        console.log(item,app_name);
        $.ajax({
                url: '{{ route('ajax.message.request.bodies') }}',
                dataType: 'json', // what to expect back from the PHP script
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    combination: app_name,
                    body_id: app_value
                },
                type: 'POST',
                beforeSend: function() {
                    $('.preloader-it').show();
                },
                complete: function() {
                    $('.preloader-it').hide();
                },
                success: function(result) {
                    $('.preloader-it').hide();
                    console.log(result);
                    if(result){
                        $("#key_type_app_bodies_"+item).empty();
                        $("#key_type_app_bodies_"+item).append(result.response);
                    }
                },
                error: function(response) {
                    $('.preloader-it').hide();
                    //$('#' + targetId).html(result.response);
                }
            });
    }
    // remove row
    removeArr = [];
    $(document).on('click', '#removeRow', function () {
        removeArr.push($(this).attr('attr-interactiveKey'));
        $("#menuRemoveRow").val(removeArr);
        $(this).closest('#inputFormRow').remove();
        console.log(removeArr);
    });
        function __loadDefaultValues() {
            let appName = "{{ $app_name }}".toLowerCase();
            let appValue = "{{ $app_value }}".toLowerCase();
            let invalidAppName = "{{ $invalid_app_name }}".toLowerCase();
            let invalidAppValue = "{{ $invalid_app_value }}".toLowerCase();
            __getAppName(appName, 'appValue', true, appValue);
            $("#appName").val(appName);
            __getAppName(invalidAppName, 'invalidAppValue', true, invalidAppValue);
            $("#invalidAppName").val(invalidAppName);

            <?php 
               /* for($i=0; $i< count($menuInput) ; $i++) {
                    foreach($allMenu as $menu) {
                        if(($menu->type === $menuInput[$i]->keyAppName) && $menu->id == $menuInput[$i]->keyAppValueInInt) {
                            $menuInput[$i]->keyAppValue = $menu->name;                        
                        }
                    }
                   ?>
                    var inputKey = '<?php echo rawurldecode($menuInput[$i]->inputKey) ?>';
                    var keyAppName = '<?php echo $menuInput[$i]->keyAppName?>';
                    var keyAppValueInInt = <?php echo $menuInput[$i]->keyAppValueInInt ?>;
                    var keyAppValue = '<?php echo $menuInput[$i]->keyAppValue?>';
                    var id = <?php echo $menuInput[$i]->id?>;
                   
                    keyList.push({
                        inputKey : inputKey,
                        keyAppName : keyAppName,
                        keyAppValueInInt : keyAppValueInInt,
                        keyAppValue : keyAppValue,
                        id : id
                   })
                   <?php 
                }*/
            ?>
            __listKeyValues();
        }

        __loadDefaultValues();
        
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
