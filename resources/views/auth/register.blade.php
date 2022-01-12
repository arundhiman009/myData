@extends('layouts.master')
@section('title')
    Register - {{ Config::get('app.name') }}
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
                @if (session('status'))
                    <div class="mb-4 text-sm font-medium text-green-600" style="color: #fff;">
                        {{ session('status') }}
                    </div>
                @endif
                
                <div class="comman-heading-login">
                    <h1> Sign Up </h1>
                </div>
                <form action="{{ route('register') }}" method="POST" class="comman-form">
                    @csrf
                    <div class="form-group">
                        <label for="user-name" class="label-txt">User name</label>
                        <input type="text" id="user-name" name="username" value="{{ @old('username') }}">
                        @if ($errors->has('username'))
                            <div class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('username') }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="email-address" class="label-txt" >Email Address <span>*</span></label>
                        <input type="text" id="email-address" name="email" value="{{ @old('email') }}">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </div>
                        @endif
                        
                    </div>
                    <div class="form-group">
                        <label for="password" class="label-txt" >Password <span>*</span></label>
                        <input type="password" id="password" name="password">
                         <i class="las la-eye eyebutton" id="togglePassword" ></i>
                        @if ($errors->has('password'))
                            <div class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="label-txt"> Confirm Password <span>*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation">
                         <i class="las la-eye eyebutton" id="togglepassword_confirmation" ></i>
                        @if ($errors->has('password'))
                            <div class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary comman-form-btn">Signup</button>
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

                    <p>Already have an account.  <a href="{{ route('login') }}"> Login</a></p>
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

    const togglepassword_confirmation = document.querySelector('#togglepassword_confirmation');
    const password_confirmation = document.querySelector('#password_confirmation');

    togglepassword_confirmation.addEventListener('click', function (e) {

    const type = password_confirmation.getAttribute('type') === 'password' ? 'text' : 'password';
    password_confirmation.setAttribute('type', type);

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

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex items-center">
                            <x-jet-checkbox name="terms" id="terms"/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-jet-button class="ml-4">
                    {{ __('Register') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout> --}}
