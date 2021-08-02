@extends('layouts.public')
@section('title', 'Login')
@section('content')
<div class="hk-pg-wrapper hk-auth-wrapper">
    <header class="d-flex justify-content-between align-items-center">
        <a class="d-flex auth-brand" href="#">
            {{-- <img class="brand-img" src="javascript:void(0)" alt="brand" /> --}}
            @php
            $domain = \App\Helpers\Helper::getFqdn($_SERVER['SERVER_NAME']);
            $domainDetail = \App\Helpers\Helper::getDomainDetail($domain);
            @endphp
            <h3 style="color: white;">{{ $domainDetail->company_name }}</h3>{{-- </img> --}}
        </a>

    </header>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-5 pa-0">
                <div id="owl_demo_1" class="owl-carousel dots-on-item owl-theme">
                    <div class="fadeOut item auth-cover-img overlay-wrap" style="background-image:url({{ asset('dist/img/bg2.jpg') }});">
                        <div class="auth-cover-info py-xl-0 pt-100 pb-50">
                            <div class="auth-cover-content text-center w-xxl-75 w-sm-90 w-xs-100">
                                <h1 class="display-3 text-white mb-20">WE DELIVER SUCCESS</h1>
                                <p class="text-white">Get more traffic. Acquire more customers. Sell more stuff. {{ $domainDetail->company_name }} works for businesses of all sizes.</p>
                            </div>
                        </div>
                        <div class="bg-overlay bg-trans-dark-50"></div>
                    </div>
                    <div class="fadeOut item auth-cover-img overlay-wrap" style="background-image:url({{ asset('dist/img/bg1.jpg') }});">
                        <div class="auth-cover-info py-xl-0 pt-100 pb-50">
                            <div class="auth-cover-content text-center w-xxl-75 w-sm-90 w-xs-100">
                                <h1 class="display-3 text-white mb-20">PROMOTE YOUR BUSINESS THROUGH MOBILE MARKETING</h1>
                                <p class="text-white">Create seamless WhatsApp customer journeys in minutes</p>
                            </div>
                        </div>
                        <div class="bg-overlay bg-trans-dark-50"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 pa-0">
                <div class="auth-form-wrap py-xl-0 py-50">
                    <div class="auth-form w-xxl-55 w-xl-75 w-sm-90 w-xs-100">
                        <form method="POST" action="{{ route('login') }}">
                        @csrf
                            <h1 class="display-4 mb-10">Welcome Back :)</h1>
                            <p class="mb-30">Sign in to your account and enjoy unlimited perks.</p>
                            <div class="form-group">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}{{ $errors->has('error') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="email" required autofocus>
                                @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}{{ $errors->has('error') ? ' is-invalid' : '' }}" placeholder="password" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                @if($errors->has('error'))
                                    <span class="invalid-feedback" role="alert" style="display: block;">
                                    <strong>{{ $errors->first('error') }}</strong>
                                    </span>
                                @endif
                                </div>
                            </div>
                             <div class="custom-control custom-checkbox mb-25">
                                 <a class="d-flex auth-brand font-14">{{ __('Forgot Your Password?') }} </a>
                            </div>
                            <button class="btn btn-primary btn-block" type="submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
