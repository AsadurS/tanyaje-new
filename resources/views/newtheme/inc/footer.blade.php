<!-- START footer_part -->
<footer class="footer_part {{ Route::currentRouteName() == 'home' ? 'home-footer' : '' }}">
    <div class="container disflexArea">
        <dicv class="footerLogo">
            @if( !(Route::currentRouteName() == 'site.merchant' || Route::currentRouteName() == 'car_details') )
            <a href="{{ route("home") }}">
                <img src="{{asset('new/images/whitelogo.png')}}" alt="">
            </a>
            @endif
        </dicv>
        <div class="socialIcon">
            <ul>
                @if( !(Route::currentRouteName() == 'site.merchant' ) )
                <li>
                    <a target="_blank" href="https://www.facebook.com/tanyaje.my">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </li>
                <li>
                    <a target="_blank" href="https://www.instagram.com/tanyajemy/">
                    <i class="fab fa-instagram"></i>
                    </a>
                </li>
                <li>
                    <a target="_blank" href="https://www.youtube.com/channel/UCcGVll2iQ-KuyOZ1NLck64g">
                    <i class="fab fa-youtube"></i>
                    </a>
                </li>
                @endif
            </ul>
        </div>

    </div>

    <!-- START copyright -->
    <div class="copyright">
        <div class="container disflexArea">
            <div class=" leftBox">
                <p>&copy; Tanyaje Copyrights Reserved 2020</p>
            </div>
        <!--    <div class="rightBox">
                <ul>
                    @if( !(Route::currentRouteName() == 'site.merchant' ) )
                    <li>
                        <a href="{{ route("about_us") }}">
                            About Us
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("contact_us") }}">
                            Contact Us
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("term_of_service") }}">
                            Terms of Service
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("privacy_policy") }}">
                            Privacy Policy
                        </a>
                    </li>
                    @endif
                </ul>
            </div> -->
        </div>
    </div>
    <!-- END copyright -->
</footer>
<!-- END footer_part -->