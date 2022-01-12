@extends('layouts.master')
@section('title')
    Login - {{ Config::get('app.name') }}
@endsection
@section('page-css')
<style>
.eyebutton{
    float: right;
    position: relative;
    top: -24px;
}
</style>
@endsection
@section('content')
<section class="register-page">
    <div class="container">
        <div class="row justify-content-center">
            
            <div class="col-sm-7 col-lg-4">
               
                 <div class="comman-heading-login">
                    <h1> Sign In </h1>
                </div>
                @if (session('status'))
                    <div class="mb-4 text-sm font-medium text-green-600" style="color: #fff;">
                        {{ session('status') }}
                    </div>
                @endif
                <form action="{{ route('login') }}" method="POST" class="comman-form login-form">
                    @csrf
                    <div class="form-group placeholder-label">
                        <label for="email-adders" class="label-txt" >Email Address <span>*</span></label>
                        <input type="text" id="email-adders" name="email" value="{{ @old('email') }}">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </div>
                        @endif
                        
                    </div>
                    <div class="form-group placeholder-label">
                        <label for="password" class="label-txt">Password <span>*</span></label>
                        <input type="password" id="password" name="password">
                        <i class="las la-eye eyebutton" id="togglePassword" ></i>
                        @if ($errors->has('password'))
                            <div class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="form-group form-check d-flex justify-content-between">
                        <div class="custom-checkbox d-flex">
                            <input type="checkbox" class="form-check-input" id="checkbox" name="remember_me">
                            <label for="checkbox">Remember Me</label>
                        </div>
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                      </div>

                    <button type="submit" class="btn btn-primary comman-form-btn">Signin</button>
                    <div class="user-login">
                        <span>or</span>
                    </div>

                    <div class="col-sm-12 mb-3">
                        <div class="login-social">
                            <ul class="d-flex list-unstyled justify-content-center">
                                <li><a href="{{ url('login/facebook') }}"> <i class="lab la-facebook-f"></i></a></li>
                                <li><a href="{{ url('login/google') }}"> <img src="{{ asset('assets/images/google.png') }}" class="img-fluid" alt=""></a></li>

                            </ul>
                        </div>
                    </div>

                                 
                    <p>Don't have an account? <a href="{{ route('register') }}">Signup </a></p>
                  </form>
            </div>
        </div>
    </div>
    
</section>
@endsection
@section('page-scripts')
    <script type="text/javascript">
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {

    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);

    this.classList.toggle('la-eye-slash');
    });
    </script>

@endsection

{{-- <x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-jet-button class="ml-4">
                    {{ __('Log in') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout> --}}
