@extends('newtheme.layouts.main')
@section('meta_for_share')
    <meta property="og:title" content="{{ trans('labels.app_name') }}">
    <meta property="og:url" content="https://tanyaje.com.my/">
    <meta property="og:image" content="{{asset('new/images/logo4.png')}}">
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="400" />
    <meta property="og:image:type" content="image/png">
    <meta property="og:description" content="Tanya-Je translated as “Just Ask” is the first most advanced online digital automotive classified in South East Asia.">
@endsection
@section('content')

<!-- START content_part-->
<section id="content_part">

    <!-- START aboutUsArea -->
    <section class="aboutUsArea">
        <div class="container">
            <div class="left-pane">
                <ul>
                    <li class="active">
                        <div class="menu-item">
                            About Us
                        </div>
                    </li>
                    <li>
                        <div class="menu-item">
                            <a href="{{ route('contact_us') }}">
                            Contact Us
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="menu-item">
                            <a href="{{ route('term_of_service') }}">
                                Term Of Service
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="menu-item">
                            <a href="{{ route('privacy_policy') }}">
                                Privacy Policy
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="right-pane">
                <div class="heading">
                    About Tanya-Je
                </div>
                <div>
                    <p>
                        Car browsing websites aren’t something new; that said, Tanya-Je is not the first nor will it be the last carlisting website here in Malaysia, but surely, it is one of the most unique.
                    </p>
                    <p>
                        At Tanya-Je, you will get a 360° view of any vehicle's interiors and exteriors, along with close-up views of certain areas; You'll get exactly what you see, so you can save time in making an appointment for the car that you’re interested in.
                    </p>
                    <p>
                        With us, you can search for both old and new cars, filter the vehicles by their make, year of manufacturing, location and more by just a few clicks. , you can search for cars both old and new to fit your needs. Filter the vehicles by their make, year manufactured, location and more with just a few clicks.
                    </p>
                    <p>
                        So, list your vehicles or start browsing for your perfect ride on Tanya-Je today!
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- END aboutUsArea -->

</section>
<!-- END content_part-->

@endsection