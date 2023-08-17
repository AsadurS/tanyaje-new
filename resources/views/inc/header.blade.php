<header class="site-header  ">
    <nav class="navbar navbar-expand-lg navbar-expand-md">
        <div class="nav-bar-top-container">
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/">Flux</a></div>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav mr-auto order-first">
                <li class="nav-item visible-mobile"> <a class="nav-link" href="/">Home</a> </li>
                <li class="nav-item"> <a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"> <a class="nav-link header_link" href="about">About Us</a>
                </li>
{{--                <li class="nav-item"> <a class="nav-link" href="{{route('car_quotation')}}">Quotation For Car</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item"> <a class="nav-link" href="{{route('insurance_quotation')}}">Quotation For Insurance</a>--}}
{{--                </li>--}}
                <li class="nav-item">
                    <a href="contact_us" class="header_link">
                        Contact Us
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link header_link" href="{{route('car_search')}}">
                        Search Your Car
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link header_link car-seller-login-btn" href="{{route('admin_login')}}" style="padding-left: 10px !important;padding-right: 10px !important; line-height:30px !important;margin-top: 10px;">
                        Car Seller Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="button-sell-ad" href="{{route('car_quotation')}}" style="margin-top:10px;padding:0px 10px !important;">
                        Sell my car
                    </a>
                </li>
            </ul>
            <ul class="social-nav visible-mobile">
                <li>
                    <a href="#" target="_blank" rel="noopener noreferrer"><img src="images/twitter.png" alt="twitter"></a>
                </li>
                <li>
                    <a href="#" target="_blank" rel="noopener noreferrer"><img src="images/facebook.png" alt="facebook"></a>
                </li>
                <li>
                    <a href="#" target="_blank" rel="noopener noreferrer"><img src="images/instagram.png" alt="instagram"></a>
                </li>
            </ul>
            <ul class="terms-nav visible-mobile">
                <li><a href="#">Terms</a>
                </li>
                <li><a href="#">Privacy
                        Policy</a></li>
            </ul>
        </div>
    </nav>
</header>