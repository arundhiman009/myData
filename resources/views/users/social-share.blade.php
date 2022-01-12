@extends('layouts.admin.admin-app')

@section('title')
Dashboard - {{ Config::get('app.name') }}
@endsection

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}" />
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 text-center px-3">
                    <div class="share-content">
                        <div class="top-header-content">
                            <h5>Refer a friend</h5>
                            <h2>{{$info->tag ?? 'Give $25 & Get $25'}}</h2>
                            <p>
                                {{$info->content ?? "Invite your friends to join TVC and they'll get up to a 25 match bonus on their first deposit. Plus, they will also get upto a 100 match bonus on their first bet. You will also get a $25 bonus for each referral once they make a first deposit. To receive your bonus, your friend must use your unique link to sign up."}}
                            </p>
                        </div>
                        <div class="share-body-content pt-0 col-sm-10 m-auto">
                            <h3 class="mb-3">Share Links via Email</h3>
                            <form class="send_invitation">
                                <div class="input-group mb-3">
                                    <input type="email" id="email" name="email" class="form-control required user-email" required placeholder="Enter email address">
                                    <div class="input-group-append">
                                        <button class="btn btn-warning text-white">Send Invites</button>
                                    </div>
                                </div>
                                <label id="email-error" class="email-error is-invalid" for="email" style="display:none"></label>
                            </form>
                            <div class="share-links">
                                <p>Or</p>
                                <form class="form-inline justify-content-center">
                                    <div class="input-group mb-3">
                                        <label class="d-block w-100 mb-2">Share Your Link </label>
                                        <input type="text" class="form-control share-link-input" id="ref_code" readonly value="{{ $referal_link }}">
                                        <button type="button" class="btn cusn-bg-grident share-link-btn" id="ref_btn" data-toggle="tooltip" data-placement="top" title="Copy to clipboard" onclick="myFunction()" onmouseout="outFunc()">Copy</button>
                                    </div>
                                </form>
                                <div class="col" id="social-links">
                                    <a href="{{ Share::page($referal_link, 'Join now')->facebook()->getRawLinks() }}" class="btn btn-primary social-button"> 
                                        <i class="fab fa-facebook-square"></i> Facebook
                                    </a>
                                    <a href="{{ Share::page($referal_link, 'Join now')->twitter()->getRawLinks() }}" class="btn btn-info ml-3 social-button"> 
                                        <i class="fab fa-twitter-square"></i> Twitter
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('page-scripts')

<script>
    $("form.send_invitation").validate({
        errorClass: 'is-invalid',
        submitHandler: function (form) {
            let userEmail = $(".user-email").val();
            SwalProgress();
            $.ajax({
                url: "{{route('social.share.email')}}",
                method: 'POST',
                data: {
                    invitation_email: userEmail
                }
            }).done(function (res) {
                SwalHideProgress();
                if (res.success) {
                    $(".user-email").val('');
                    SwalAlert({
                        title: "Success!",
                        text: res.message,
                        icon: "success"
                    });
                } else {
                    SwalAlert({
                        title: "Oops!",
                        text: res.message,
                        icon: "error"
                    });
                }
            }).fail(function (err, xhr) {
                SwalHideProgress();
                let response = JSON.parse(err.responseText);
                var errorString = '';
                $.each(response.errors, function (key, value) {
                    errorString = value;
                });
                SwalAlert({
                    title: "Oops!",
                    text: errorString,
                    icon: "error"
                });
            });
        }
    })

</script>
<script src="{{ asset('js/share.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>

@endsection
