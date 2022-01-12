@extends('layouts.master')

@section('title')
    Admin Verification Pending - {{ Config::get('app.name') }}
@endsection

@section('content')
<section class="register-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 mb-5">
                <div class="comman-heading">
                    <h1> Waiting for Admin Approval </h1>
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



