@extends('layouts.master')

@section('title')
Resend Verification Email - {{ Config::get('app.name') }}
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('css/laddaButton.css')}}">


<section class="register-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 mb-5">
                <div class="comman-heading">
                    <h1> Resend Verification Email </h1>
                </div>
            </div>
            <div class="col-sm-12 mb-5">
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 font-medium text-sm text-green-600 text-center">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif
            </div>
            <div class="col-sm-5">
                <form class="comman-form login-form" method="POST" action="{{ route('verification.send') }}" >
                    @csrf
                   
                    <button type="submit" class="ladda-button expand-left btn btn-primary comman-form-btn"><span class="label">Resend Verification Email</span> <span class="spinner"></span></button>
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="btn btn-primary comman-form-btn">
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    
</section>
@endsection


{{-- <x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-jet-button type="submit">
                        {{ __('Resend Verification Email') }}
                    </x-jet-button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </x-jet-authentication-card>
</x-guest-layout> --}}
