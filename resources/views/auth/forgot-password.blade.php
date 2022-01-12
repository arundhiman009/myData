@extends('layouts.master')

@section('title')
    Forgot Password - {{ Config::get('app.name') }}
@endsection

@section('content')
<section class="register-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 mb-5">
                <div class="comman-heading">
                    <h1> Forgot Password </h1>
                </div>
            </div>
            <div class="col-sm-12 mb-5">
                <p class="text-center">Don't worry! Just fill in your email and we'll send you a link to reset your password.</p>
            </div>
            <div class="col-sm-6 col-lg-4">
                <form class="comman-form forget-form" action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="label-txt">Email Address <span>*</span></label>
                        <input type="text" id="name" name="email" value="{{ @old('email') }}" />
                        @if ($errors->has('email'))
                            <div class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary comman-form-btn">Email Password Reset Link</button>
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-center">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection