<footer class="">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col"><a class="navbar-brand" href="/">Flux</a></div>
            <div class="col text-right">
                <ul class="social-nav">
                    <li>
                        <a href="#"><img src="{{asset('images/facebook.png')}}" alt="facebook"></a>
                    </li>
                    <li>
                        <a href="#"><img src="{{asset('images/twitter.png')}}" alt="twitter"></a>
                    </li>
                    <li>
                        <a href="#"><img src="{{asset('images/instagram.png')}}" alt="instagram"></a>
                    </li>
                </ul>
            </div>
        </div>
        <ul class="footer-nav">
            <li><a href="/">Home</a></li>
            <li> <a href="#">About Us</a> </li>
            <li> <a href="{{route('car_quotation')}}">Quotation For Car</a> </li>
            <li> <a href="{{route('insurance_quotation')}}" >Quotation For Insurance</a> </li>
            <li> <a href="#">Contact Us</a></li>
        </ul>
        <div class="row align-items-center copy-info">
            <div class="col hide-mobile">© Tanyaje Copyrights Reserved 2020</div>
            <div class="col text-right">
                <ul class="terms-nav">
                    <li><a href="#">Terms</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>