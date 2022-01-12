@extends('layouts.master')

@section('title')
    {{ Config::get('app.name') }}
@endsection

@section('content')
<!-- Banner section -->
<section>
    <div class="owl-carousel banner-carousel owl-theme">
        <div class="owl-slide">
            <div class="owl--text">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 pl-0">
                            <div class="banner-content">
                                <h1>$25</h1>
                                <h2> Referral Bonus </h2>
                                <p>Share with your friends and you both earn $25. </p>
                                <a href="{{ route('social.share') }}" class="btn cusn-bg-grident"><i class="la la-share-alt-square"></i>
                                Share Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!--   <div class="owl-slide">
            <div class="owl--text">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 pl-0">
                            <div class="banner-content">
                                <h1>$25</h1>
                                <h2> Referral Bonus </h2>
                                <p>Share with your friends and you both earn $25. </p>
                                <a href="javascript:void(0)" class="btn cusn-bg-grident">Share Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="owl-slide">
            <div class="owl--text">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 pl-0">
                            <div class="banner-content">
                                <h1>$25</h1>
                                <h2> Referral Bonus </h2>
                                <p>Share with your friends and you both earn $25. </p>
                                <a href="javascript:void(0)" class="btn cusn-bg-grident">Share Now</a>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</section>
<!-- Banner section End-->
<!-- Four box section Start-->
<section class="four-box">
    <div class="container">
        <div class="row ">
            <div class="col-sm-6 col-lg-3">
                <div class="video-thumnail">
                    <img src="{{ asset('assets/images/Vs-logo.png') }}" alt="Vs-logo" class="img-fluid">
                </div>
                <h3>Video Slots</h3>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="video-thumnail">
                    <img src="{{ asset('assets/images/keno.png') }}" alt="keno-logo" class="img-fluid">
                </div>
                <h3>Keno</h3>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="video-thumnail">
                    <img src="{{ asset('assets/images/poker.png') }}" alt="Poker-logo" class="img-fluid">
                </div>
                <h3>Poker</h3>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="video-thumnail">
                    <img src="{{ asset('assets/images/The-Wild-Life.png') }}" alt="The-Wild-Life" class="img-fluid">
                </div>
                <h3>Specialty Slots</h3>
            </div>
        </div>
    </div>
</section>
<!-- Four box section End-->
<!-- Download Now Start-->
@if(!empty($games))
<section class="game-download">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 mb-5">
                <div class="comman-heading">
                    <h1>Download Now</h1>
                </div>
            </div>

            <div class="col-sm-10">
                <div class="row">
                     @foreach($games as $game)
                    <div class="col-sm-6 col-lg-5 offset-lg-1">
                        <div class="video-game">
                            <span>
                                <img src="{{ asset('assets/images/'.$game->image)    }}" alt="video-game" class="img-fluid">
                                <div class="content-details fadeIn-bottom">
                                    <a href="{{$game->download_path}}" class="btn play-btn" target="_blank">Download Now</a>
                                </div>
                            </span>
                        </div>
                        <h3>{{$game->name}}</h3>
                    </div>
                    @endforeach
                 
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!-- Download Now End-->
<!-- Lastest Game Start-->
<section class="lastest-game">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 mb-5">
                <div class="comman-heading">
                    <h1> Download Now </h1>
                </div>
            </div>
            <!-- 1 -->
            <div class="col-sm-6 col-md-3">
                <div class="video-game">
                    <span>
                        <img src="{{ asset('assets/images/game-thum/img-1.jpg') }}" alt="Woods of Magic" class="img-fluid">
                        <div class="content-details fadeIn-bottom">
                            <a href="javascript:void(0)" class="btn play-btn">Play Now</a>
                        </div>
                    </span>
                </div>
                <h3>Woods of Magic 2</h3>
            </div>
            <!-- 2 -->
            <div class="col-sm-6 col-md-3">
                <div class="video-game">
                    <span>
                        <img src="{{ asset('assets/images/game-thum/img-2.jpg') }}" alt="Maya Adventures 2" class="img-fluid">
                        <div class="content-details fadeIn-bottom">
                            <a href="javascript:void(0)" class="btn play-btn">Play Now</a>
                        </div>
                    </span>
                </div>
                <h3>Maya Adventures 2</h3>
            </div>
            <!-- 3 -->
            <div class="col-sm-6 col-md-3">
                <div class="video-game">
                    <span>
                        <img src="{{ asset('assets/images/game-thum/img-3.jpg') }}" alt="Tuthankhamons Gold 2" class="img-fluid">
                        <div class="content-details fadeIn-bottom">
                            <a href="javascript:void(0)" class="btn play-btn">Play Now</a>
                        </div>
                    </span>
                </div>
                <h3>Tuthankhamons Gold 2</h3>
            </div>
            <!-- 4 -->
            <div class="col-sm-6 col-md-3">
                <div class="video-game">
                    <span>
                        <img src="{{ asset('assets/images/game-thum/img-4.jpg') }}" alt="Diamond's cave" class="img-fluid">
                        <div class="content-details fadeIn-bottom">
                            <a href="javascript:void(0)" class="btn play-btn">Play Now</a>
                        </div>
                    </span>
                </div>
                <h3>Diamond's cave</h3>
            </div>
            <!-- 5 -->
            <div class="col-sm-6 col-md-3">
                <div class="video-game">
                    <span>
                        <img src="{{ asset('assets/images/game-thum/img-5.jpg') }}" alt="Undersea treasures" class="img-fluid">
                        <div class="content-details fadeIn-bottom">
                            <a href="javascript:void(0)" class="btn play-btn">Play Now</a>
                        </div>
                    </span>
                </div>
                <h3>Undersea treasures</h3>
            </div>
            <!-- 6 -->
            <div class="col-sm-6 col-md-3">
                <div class="video-game">
                    <span>
                        <img src="{{ asset('assets/images/game-thum/img-6.jpg') }}" alt="Golden Buddha 2" class="img-fluid">
                        <div class="content-details fadeIn-bottom">
                            <a href="javascript:void(0)" class="btn play-btn">Play Now</a>
                        </div>
                    </span>
                </div>
                <h3>Golden Buddha 2</h3>
            </div>
            <!-- 7 -->
            <div class="col-sm-6 col-md-3">
                <div class="video-game">
                    <span>
                        <img src="{{ asset('assets/images/game-thum/img-7.jpg') }}" alt="Green Dragon" class="img-fluid">                        
                        <div class="content-details fadeIn-bottom">
                            <a href="javascript:void(0)" class="btn play-btn">Play Now</a>
                        </div>
                    </span>
                </div>
                <h3>Green Dragon</h3>

            </div>
            <!-- 8 -->
            <div class="col-sm-6 col-md-3">
                <div class="video-game">
                    <span>
                        <img src="{{ asset('assets/images/game-thum/img-8.jpg') }}" alt="Diamond's" class="img-fluid">
                        <div class="content-details fadeIn-bottom">
                            <a href="javascript:void(0)" class="btn play-btn">Play Now</a>
                        </div>
                    </span>
                </div>
                <h3>Diamond's</h3>
            </div>
        </div>
    </div>
</section>
<!-- Lastest Game End-->
<!-- How To Play  -->
<section class="comman-bg-dark py-5 how-play">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 mb-5">
                <div class="comman-heading">
                    <h1> How to play </h1>
                </div>
            </div>
            <div class="col-sm-4 col-lg-3">
                <div class="step-play-game py-5">
                    <div class="step-play-count">
                        <h1>1</h1>
                    </div>
                    <div class="step-content">
                        <img src="{{ asset('assets/images/steps-icon/add-user.png') }}" alt="Add User"
                            class="img-fluid">
                        <h2>Sign UP <br> (Create Account)</h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-lg-3 offset-lg-1">
                <div class="step-play-game py-5">
                    <div class="step-play-count">
                        <h1>2</h1>
                    </div>
                    <div class="step-content">
                        <img src="{{ asset('assets/images/steps-icon/invest.png') }}" alt="Add User" class="img-fluid">
                        <h2>Fund Your Account</h2>
                        <!-- <h2></h2> -->
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-lg-3 offset-lg-1">
                <div class="step-play-game py-5">
                    <div class="step-play-count">
                        <h1>3</h1>
                    </div>
                    <div class="step-content">
                        <img src="{{ asset('assets/images/steps-icon/live-streaming.png') }}" alt="Add User"
                            class="img-fluid">
                        <h2>Start Playing Live</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Features -->
<section class="features">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 mb-5">
                <div class="comman-heading">
                    <h1> Features </h1>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="features-content">
                    <img src="{{ asset('assets/images/online-money.png') }}" alt="">
                    <h3>Fast Desposits</h3>
                    <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain
                        was born and I will give you a complete
                    </p>
                </div>
            </div>
            <!-- 2 -->
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="features-content">
                    <img src="{{ asset('assets/images/online-money.png') }}" alt="">
                    <h3>Safe & Secure</h3>
                    <p>Vro eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum
                        deleniti atque corrupti quos dolores et quas
                    </p>
                </div>
            </div>
            <!-- 3 -->
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="features-content">
                    <img src="{{ asset('assets/images/online-money.png') }}" alt="">
                    <h3>Deposit Assistance</h3>
                    <p>when our power of choice is untrammelled and when nothing prevents our being able to do what we
                        like best, every pleasure is to be
                    </p>
                </div>
            </div>
            <!-- 4 -->
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="features-content">
                    <img src="{{ asset('assets/images/online-money.png') }}" alt="">
                    <h3>Quick Cashouts</h3>
                    <p>No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those
                        who do not know how to pursue pleasure
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('page-scripts')
<script>
    $(document).ready(function () {
        $(".owl-carousel").owlCarousel();
    });
</script>
@endsection
