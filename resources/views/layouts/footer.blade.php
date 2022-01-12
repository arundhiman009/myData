{{-- Footer start --}}
<footer class="footer comman-bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="footer-logo">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="{{ Config::get('app.name') }} logo" class="img-fluid">
                </div>
                <p>I must explain to you how all this mistaken idea of denouncing pleasure and praising pain</p>
            </div>
            <div class="col-sm-6 col-lg-3">
                <h4>Useful Links</h4>
                <ul class="usful-link">
                    <li><a href="javascript:void(0)"> Privacy policy </a></li>
                    <li><a href="javascript:void(0)"> FAQ </a></li>
                    <li><a href="javascript:void(0)"> Terms & Conditions </a></li>
                </ul>
            </div>
            <div class="col-sm-6 col-lg-3">
                <h4>Payment methods</h4>
                <ul class="payment-ways">
                    <li class="d-flex">
                        <a href="javascript:void(0)"> <img src="{{ asset('assets/images/visa.png') }}" class="img-fulid" alt=""></a>
                        <a href="javascript:void(0)"> <img src="{{ asset('assets/images/paypal.png') }}" class="img-fulid" alt=""></a>
                        
                    </li>
                    <li class="d-flex"><a href="javascript:void(0)"> <img src="{{ asset('assets/images/master-card.png') }}"class="img-fulid" alt=""> </a>
                        <a href="javascript:void(0)" class="ml-2"> <img src="{{ asset('assets/images/amricam.png') }}" class="img-fulid" alt=""></a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-6 col-lg-3">
                <h4>Follow Us</h4>
                <ul class="d-flex social-icon">
                    <li><a href="javascript:void(0)"> <i class="lab la-facebook-f"></i> </a></li>
                    <li><a href="javascript:void(0)"> <i class="lab la-youtube"></i> </a></li>
                    <li><a href="javascript:void(0)"> <i class="lab la-twitter"></i> </a></li>
                </ul>
            </div>
        </div>        
        <div class="row border-top copy-right">   
            <div class="col-sm-12 py-3">
                <p class="text-center"> &#169;2021 {{env('APP_NAME')}}. All rights reserved.</p>                
            </div>
        </div>
    </div>
</footer>
{{-- Footer End --}}




