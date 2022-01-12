@extends('layouts.master')
@section('title')
    Change-password - {{ Config::get('app.name') }}
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
            <div class="col-sm-12 mb-5">
                <div class="comman-heading">
                    <h1> Change Password </h1>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                @if(session('status') == "password-updated")
                    <div class="mb-4 text-sm font-medium text-green-600" style="color: #fff;">
                        <p>Your password updated successfully.</p>
                    </div>
                @endif
                <form action="{{ route('user-password.update') }}" method="POST" class="comman-form change-paswrd-form">
                    @csrf
                    @method('put')
                    <div class="form-group placeholder-label">
                        <label for="current_password" class="label-txt" >Current Password <span>*</span></label>
                        <input type="password" id="password" name="current_password">
                        <i class="las la-eye eyebutton" id="togglePassword" ></i>
                        @if ($errors->has('current_password'))
                            <div class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('current_password') }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="form-group placeholder-label">
                        <label for="password" class="label-txt" >New Password <span>*</span></label>
                        <input type="password" id="NewPassword" name="password">
                        <i class="las la-eye eyebutton" id="toggleNewPassword" ></i>
                        @if ($errors->has('password'))
                            <div class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="form-group placeholder-label">
                        <label for="password_confirmation" class="label-txt" >Confirm New Password <span>*</span></label>
                        <input type="password" id="ConfirmPassword" name="password_confirmation">
                        <i class="las la-eye eyebutton" id="toggleConfirmPassword" ></i>
                        @if ($errors->has('password_confirmation'))
                            <div class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary comman-form-btn"> Save </button>
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
    const toggleNewPassword = document.querySelector('#toggleNewPassword');
    const NewPassword = document.querySelector('#NewPassword');

    toggleNewPassword.addEventListener('click', function (e) {

    const type = NewPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    NewPassword.setAttribute('type', type);

    this.classList.toggle('la-eye-slash');
    });

    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const ConfirmPassword = document.querySelector('#ConfirmPassword');

    toggleConfirmPassword.addEventListener('click', function (e) {

    const type = ConfirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    ConfirmPassword.setAttribute('type', type);

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

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button>
                    {{ __('Reset Password') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout> --}}
