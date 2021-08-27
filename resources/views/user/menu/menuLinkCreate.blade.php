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
        <h6 class="hk-pg-title">@yield('title') :: Create Menu</h6>
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
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="firstName"> Name </label>
                                        <input class="form-control" id="name" name="name" placeholder="Enter name" value=""
                                            type="text" value="{{ old('name') }}">
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="appName" class="col-form-label">App Name</label>
                                        <select class="form-control custom-select" id="appName" name="appName"
                                            onchange="__getAppName(this.value, 'appValue')">
                                            <option value="null">null</option>
                                            <option value="text">Text</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="appValue" class="col-form-label">App Value </label>
                                        <select class="form-control custom-select" id="appValue" name="appValue"
                                            onchange="__checkAppValueCondition(this.value, 'appName')">
                                            <option value="null">null</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="" class="col-form-label m_sel_image">Invalid App Name </label>
                                        <select class="form-control custom-select" id="invalidAppName" name="invalidAppName"
                                            onchange="__getAppName(this.value, 'invalidAppValue')">
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
                                        <label for="" class="col-form-label m_sel_image">Invalid App value</label>
                                        <select class="form-control custom-select" id="invalidAppValue"
                                            name="invalidAppValue"
                                            onchange="__checkAppValueCondition(this.value, 'invalidAppName')">
                                            <option value="null">null</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->any())
                                    <label class="control-label" for="inputError" style="color: #dd4b39"><i
                                            class="fa fa-times-circle-o"></i>
                                        {{ implode(' | ', $errors->all(':message')) }} .</label>
                                    <br>
                                @endif
                                <button class="btn btn-primary pull-center" id="nextButton" style="margin-left: 45%;"
                                    type="button">Next</button>
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
                                    <div class="card bg-light mb-3 col-sm-6">
                                        <div class="row">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Key</th>
                                                        <th scope="col">App Name</th>
                                                        <th scope="col">App Value</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="keyData">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 form-group">
                                                <label for="keyAppName" class="col-form-label">App Name</label>
                                                <select class="form-control custom-select" id="keyAppName" name="keyAppName"
                                                    onchange="__getAppName(this.value, 'keyAppValue')">
                                                    <option value="null">null</option>
                                                    <option value="text">Text</option>
                                                    <option value="image">Image</option>
                                                    <option value="video">Video</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label for="keyAppValue" class="col-form-label">App Value </label>
                                                <select class="form-control custom-select" id="keyAppValue" name="keyAppValue"
                                                    onchange="__checkAppValueCondition(this.value, 'keyAppName')">
                                                    <option value="null">null</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="inputKey"> Key </label>
                                                <input class="form-control" id="inputKey" name="inputKey" placeholder="Enter Key" value=""
                                                    type="text" value="{{ old('inputKey') }}">
                                                <div class="key-exist" style="display: none; color: red;">
                                                    Key already exist!!
                                                </div>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <button class="btn btn-primary pull-center" id="addKey" style="margin-top: 30px;" type="button" onclick="addToKeySet();">Create</button>
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
                                    type="button">Create</button>
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
        function __getAppName(combination, targetId) {
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
                },
                error: function(response) {
                    $('.preloader-it').hide();
                    $('#' + targetId).html(result.response);
                }
            });
        }

        function __checkAppValueCondition(value, targetId) {
            let appNameElementVal = $("#" + targetId).val();
            if ((value == 'null' || value == null) && (appNameElementVal != 'null' && appNameElementVal != null)) {
                alert("Next App name should be null if Next App value is null");
                $("#" + targetId).val("null");
            }
        }

        $("#sendBtn").click(function(event) {
            event.preventDefault();
            if (!__appValueValidationCheck()) {
                $("#keySetId").val(JSON.stringify(keyList));
                $("form").submit();
            }
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

        $("#nextButton").click(function() {
            $("#nextWrapper").show();
            $("#initialWrapper").hide();
            $(".card-text.name span").text($("#name").val());
            $(".card-text.appName span").text($("#appName").val());
            $(".card-text.invalidAppName span").text($("#invalidAppName").val());
        });

        $("#backBtn").click(function() {
            $("#nextWrapper").hide();
            $("#initialWrapper").show();
        });
        function __listKeyValues() {
            let response = "";
            keyList.forEach((value, key) => {
                response += "<tr><th scope='row'>"+ value.inputKey +"</th><td>"+ value.keyAppName +"</td><td>"+ value.keyAppValue +"</td><td><button type='button'onclick='removeKey("+ key +")' class='btn btn-link'>Remove</button></td></tr>";
            });
            $("#keyData").html(response);
        }

        function addToKeySet() {
            if(__checkValidEntry()) {
                if(!__checkKeyExist()) {
                    $(".key-exist").hide();
                    keyList.push({
                        "inputKey": $("#inputKey").val(),
                        "keyAppName": $("#keyAppName").val(),
                        "keyAppValue": $("#keyAppValue option:selected").text(),
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
            keyList.splice(index, 1);
            __listKeyValues();
        }
        
    </script>
@endsection
