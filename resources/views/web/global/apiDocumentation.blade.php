@extends('layouts.master')
@section('title', 'Api Management')
@section('content')
<div class="profile-cover-wrap bg-indigo-light-2">
    {{-- <div class="profile-cover-img" style="background-image:url('{{ asset('dist/img/trans-bg.jpg') }}')"></div> --}}
    <div class="bg-overlay bg-trans-dark-60"></div>
    <div class="container-fluid profile-cover-content py-50">
        <div class="hk-row">
            <div class="col-lg-6">
                <div class="media align-items-center">
                    <div class="media-img-wrap  d-flex">
                        <div class="avatar">
                            <img src="{{ asset('dist/img/1foldericon.png') }}" alt="user" class="avatar-img rounded-circle">
                        </div>
                    </div>
                    <div class="media-body">
                        <div class="text-white text-capitalize display-6 mb-5 font-weight-400">API</div>
                        <div class="font-14 text-white"><span class="mr-5"><span class="font-weight-500 pr-5"> Documentation</span><span class="mr-5"></span></span><span><span class="font-weight-500 pr-5"></span><span></span></span></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="profile-tab bg-white shadow-bottom" >
    <div class="container-fluid">
        <ul class="nav nav-light nav-tabs" role="tablist">
            <li class="nav-item">
                <a href="#tabProfile" data-toggle="tab" class="d-flex h-60p align-items-center nav-link">Send Message</a>
            </li>
            <li class="nav-item">
                <a href="#tabProfileUpdate" data-toggle="tab" class="d-flex h-60p align-items-center nav-link">Receive Webhook Message</a>
            </li>
{{--             <li class="nav-item">
                <a href="#tabPasswordUpdate" data-toggle="tab" class="d-flex h-60p align-items-center nav-link">Password </a>
            </li> --}}
        </ul>
    </div>
</div>

<div class="tab-content mt-sm-30 mt-30">
    <div class="tab-content mt-sm-30 mt-30" style="margin-right: 12px;
    margin-left: 14px;">
    @include('errors.status')
    </div>
        <div class="tab-pane fade show" id="tabProfile">
            <div class="container-fluid">
                <div class="hk-row">
                    <div class="col-xl-3">
                        <div class="card">
                            <h6 class="card-header">
                                
                            </h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center" href="#tabProfile1" data-toggle="tab" onclick="updatetoggle('#tabProfile1')" id="tabli_1" style="cursor: pointer;">
                                    <i class="ion ion-md-sunny mr-15"></i>Send Text{{-- <span class="badge badge-light badge-pill ml-15">06</span> --}}
                                </li>
                                <li class="list-group-item d-flex align-items-center" href="#tabProfile1" data-toggle="tab" onclick="updatetoggle('#tabProfile1')" id="tabli_1" style="cursor: pointer;">
                                    <i class="ion ion-md-sunny mr-15"></i>Send Image{{-- <span class="badge badge-light badge-pill ml-15">06</span> --}}
                                </li>
                                <li class="list-group-item d-flex align-items-center" href="#tabProfile1" data-toggle="tab" onclick="updatetoggle('#tabProfile1')" id="tabli_1" style="cursor: pointer;">
                                    <i class="ion ion-md-sunny mr-15"></i>Send Video{{-- <span class="badge badge-light badge-pill ml-15">06</span> --}}
                                </li>
                                <li class="list-group-item d-flex align-items-center" href="#tabProfile1" data-toggle="tab" onclick="updatetoggle('#tabProfile1')" id="tabli_1" style="cursor: pointer;">
                                    <i class="ion ion-md-sunny mr-15"></i>Send Document{{-- <span class="badge badge-light badge-pill ml-15">06</span> --}}
                                </li>
                                <li class="list-group-item d-flex align-items-center" href="#tabProfile2" data-toggle="tab" onclick="updatetoggle('#tabProfile2')" id="tabli_2" style="cursor: pointer;">
                                    <i class="ion ion-md-unlock mr-15"></i>Status Code{{-- <span class="badge badge-light badge-pill ml-15">14</span> --}}
                                </li>
                                {{-- <li class="list-group-item d-flex align-items-center">
                                    <i class="ion ion-md-bookmark mr-15"></i>Terms of use<span class="badge badge-light badge-pill ml-15">10</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="ion ion-md-filing mr-15"></i>Documentation<span class="badge badge-light badge-pill ml-15">27</span>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <div id="tabProfile1" style="display: none;">
                            <section class="hk-sec-wrapper">
                            <h5 class="hk-sec-title">Installation (Install Node.js,Grunt)</h5>
                            <p class="mb-20">Use of these tools are completely optional.</p>
                                    <ul class="list-ul mt-20 mb-20 pl-15">
                                        <li class="mb-10 txt-dark">Node.js and NPM. You can download Node.js from here <a href="https://nodejs.org." target="_blank">https://nodejs.org.</a> Npm comes bundled with Node.js</li>
                                        <li class="mb-10 txt-dark">Next you need to install bower.</li>
                                        <li class="txt-dark">At last install grunt using <code>npm install -g grunt-cli</code> and <code>npm install grunt --save-dev</code></li>
                                    </ul>
                                    <p>After installing all the required frameworks, components and dependencies, go to the root folder of and run the following commands from the command line:</p>
                                    <ul class="list-ul mt-20 mb-20 pl-15">
                                        <li class="mb-10 txt-dark">npm install</li>
                                        <li class="mb-10 txt-dark">grunt dist</li>
                                        <li class="txt-dark">grunt</li>
                                    </ul>
                                    <p>If everything was installed correctly, you should see the jQuery version of Griffin running in <strong>http://localhost:9000/</strong></p>
                                        <pre class="bg-violet-light-5 pt-25">                               &lt;div class="card bg-primary text-center"&gt;
                                            &lt;div class="twitter-slider-wrap card-body"&gt;
                                                &lt;div class="twitter-icon text-center mb-15"&gt;
                                                    &lt;i class="fa fa-twitter"&gt;&lt;/i&gt;
                                                &lt;/div&gt;
                                                &lt;div id="tweets_fetch" class="owl-carousel owl-theme"&gt;&lt;/div&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                        </pre>
                                    <br>
                                    <p><strong>Above all procedures are optional you can directly use the compiled file which we provided you.</strong></p>
                        </section>
                        </div>
                        <div class="card card-lg" id="tabProfile2" style="display: none;">
                            <h3 class="card-header border-bottom-0">
                                Status Code
                            </h3>
                            <div class="card-body"  >
                                <div class="col-sm">
                                    <h6 class="hk-sec-title"> Api 2----</h6><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade show" id="tabProfileUpdate">
              <div class="container-fluid">
                  <div class="hk-row">
                      <div class="col-xl-3">
                          <div class="card">
                              <h6 class="card-header">
                                  Category
                              </h6>
                              <ul class="list-group list-group-flush">

                                  <li class="list-group-item d-flex align-items-center" href="#tabProfile1" data-toggle="tab" onclick="updatetoggle('#tabProfile1')" id="tabli_1" style="cursor: pointer;">
                                      <i class="ion ion-md-sunny mr-15"></i>Receive Sent Message Report{{-- <span class="badge badge-light badge-pill ml-15">06</span> --}}
                                  </li>
                                  <li class="list-group-item d-flex align-items-center" href="#tabProfile1" data-toggle="tab" onclick="updatetoggle('#tabProfile1')" id="tabli_1" style="cursor: pointer;">
                                      <i class="ion ion-md-sunny mr-15"></i>Receive Sent Message Delivery Report{{-- <span class="badge badge-light badge-pill ml-15">06</span> --}}
                                  </li>
                                  <li class="list-group-item d-flex align-items-center" href="#tabProfile1" data-toggle="tab" onclick="updatetoggle('#tabProfile1')" id="tabli_1" style="cursor: pointer;">
                                      <i class="ion ion-md-sunny mr-15"></i>Receive Inbound Message Report{{-- <span class="badge badge-light badge-pill ml-15">06</span> --}}
                                  </li>
                                  <li class="list-group-item d-flex align-items-center" href="#tabProfile1" data-toggle="tab" onclick="updatetoggle('#tabProfile1')" id="tabli_1" style="cursor: pointer;">
                                      <i class="ion ion-md-sunny mr-15"></i>Receive Instance Token Authentication{{-- <span class="badge badge-light badge-pill ml-15">06</span> --}}
                                  </li>
                                  <li class="list-group-item d-flex align-items-center" href="#tabProfile2" data-toggle="tab" onclick="updatetoggle('#tabProfile2')" id="tabli_2" style="cursor: pointer;">
                                      <i class="ion ion-md-unlock mr-15"></i>Status Code{{-- <span class="badge badge-light badge-pill ml-15">14</span> --}}
                                  </li>
                                  {{-- <li class="list-group-item d-flex align-items-center">
                                      <i class="ion ion-md-bookmark mr-15"></i>Terms of use<span class="badge badge-light badge-pill ml-15">10</span>
                                  </li>
                                  <li class="list-group-item d-flex align-items-center">
                                      <i class="ion ion-md-filing mr-15"></i>Documentation<span class="badge badge-light badge-pill ml-15">27</span>
                                  </li> --}}
                              </ul>
                          </div>
                      </div>
                      <div class="col-xl-9">
                          <div id="tabProfile1" style="display: none;">
                              <section class="hk-sec-wrapper">
                              <h5 class="hk-sec-title">Installation (Install Node.js,Grunt)</h5>
                              <p class="mb-20">Use of these tools are completely optional.</p>
                                      <ul class="list-ul mt-20 mb-20 pl-15">
                                          <li class="mb-10 txt-dark">Node.js and NPM. You can download Node.js from here <a href="https://nodejs.org." target="_blank">https://nodejs.org.</a> Npm comes bundled with Node.js</li>
                                          <li class="mb-10 txt-dark">Next you need to install bower.</li>
                                          <li class="txt-dark">At last install grunt using <code>npm install -g grunt-cli</code> and <code>npm install grunt --save-dev</code></li>
                                      </ul>
                                      <p>After installing all the required frameworks, components and dependencies, go to the root folder of and run the following commands from the command line:</p>
                                      <ul class="list-ul mt-20 mb-20 pl-15">
                                          <li class="mb-10 txt-dark">npm install</li>
                                          <li class="mb-10 txt-dark">grunt dist</li>
                                          <li class="txt-dark">grunt</li>
                                      </ul>
                                      <p>If everything was installed correctly, you should see the jQuery version of Griffin running in <strong>http://localhost:9000/</strong></p>
                                          <pre class="bg-violet-light-5 pt-25">                               &lt;div class="card bg-primary text-center"&gt;
                                              &lt;div class="twitter-slider-wrap card-body"&gt;
                                                  &lt;div class="twitter-icon text-center mb-15"&gt;
                                                      &lt;i class="fa fa-twitter"&gt;&lt;/i&gt;
                                                  &lt;/div&gt;
                                                  &lt;div id="tweets_fetch" class="owl-carousel owl-theme"&gt;&lt;/div&gt;
                                              &lt;/div&gt;
                                          &lt;/div&gt;
                                      </pre>
                                      <br>
                                      <p><strong>Above all procedures are optional you can directly use the compiled file which we provided you.</strong></p>
                          </section>
                          </div>
                          <div class="card card-lg" id="tabProfile2" style="display: none;">
                              <h3 class="card-header border-bottom-0">
                                  Status Code
                              </h3>
                              <div class="card-body"  >
                                  <div class="col-sm">
                                      <h6 class="hk-sec-title"> Api 2----</h6><br>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
    </div>
<script type="text/javascript">
    function updatetoggle(id){
        if(id =='#tabProfile1'){
            $(id).show();
            $("#tabProfile2").hide();
            $("#tabli_1").addClass('active');
            $("#tabli_2").removeClass('active');
        }else if(id =='#tabProfile2'){
            $(id).show();
            $("#tabProfile1").hide();
            $("#tabli_1").removeClass('active');
            $("#tabli_2").addClass('active');
        }
        return;
    }
</script>
@endsection
