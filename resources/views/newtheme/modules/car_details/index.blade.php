@extends('newtheme.layouts.main')
@section('meta_for_share')
    <meta property="og:title" content="{{ $share_car_title }}">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:image" content="{{ $car_image_url }}">
    <meta property="og:description" content="{{ $share_car_description }}">
@endsection

@section('headscript')
    <link rel="stylesheet" href="{{asset('new/css/jquery.mmenu.css')}}">
    <link rel="stylesheet" href="{{asset('new/css/animate.css')}}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('new/css/style.css'). "?lm=221220201120" }}" />
@endsection

@section('content')
    <script src="//integrator.swipetospin.com"></script>

    <!-- START content_part-->
    <section id="content_part">

        <!-- Start Single Product Page Header -->
        <section class="single-product-page-container">
            <!-- container -->
            <div class="container">
                <!-- header-nav-container -->
{{--                <div class="header-nav-container">--}}
{{--                    <ul>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route("carfilters") . "?condition=" . $status }}">--}}
{{--                                @if( $status == 1 )--}}
{{--                                    New--}}
{{--                                @elseif( $status == 2 )--}}
{{--                                    Used--}}
{{--                                @elseif(  $status == 3 )--}}
{{--                                    Recond--}}
{{--                                @endif--}}
{{--                                Cars Search--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route("carfilters") . "?state=" . $state_id }}">--}}
{{--                                {{ ucwords(strtolower($state_name)) }}--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route("carfilters") . "?categories=" . $type_id }}">--}}
{{--                                {{ ucwords(strtolower($type_name)) }}--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route("carfilters") . "?brand=" . $make_id }}">--}}
{{--                                {{ ucwords(strtolower($make_name)) }}--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route("carfilters") . "?model=" . $model_id }}">--}}
{{--                                {{ ucwords(strtolower($model_name)) }}--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
                <!-- header-nav-container -->

                <!-- Signle Product Main Title -->
                <h2 class="single-product-main-title">
                    <span>{{ $year_make }}</span>
                    {{ $title }}
                </h2>

                <!-- main-title -->

                <!-- header-options -->
                <div class="header-options-container">
                    <div class="disflexArea header-options-handler">
                        <div class="header-options-leftBox">
                            <div class="place-container">
                                <img src="{{asset('new/images/popularIcon02.png')}}" alt="popular-icon" title="place" />
                                <p>{{ ucwords($city_name) }}, {{ ucwords($state_name) }}</p>
                            </div>
                        </div>
                        <div class="header-options-rightBox icon-detail">
                            <ul>
                                <li>
                                    <script type="text/javascript">
                                        var addthis_config =
                                        {
                                            services_compact : 'whatsapp, wechat, facebook, messenger, print, email, more',
                                            services_expanded : 'whatsapp, wechat, facebook, messenger, print, email, tumblr, ' +
                                                'linkedin, gmail, pinterest, twitter, blogger, amazonwishlist, aolmail, foursquare, baidu, ' +
                                                'bitly, link, mailto, evernote, favorites, flipboard, google, googletranslate, kakao, myspace, ' +
                                                'hotmail, paypalme, pdfmyurl, pinboard, pinterest_share, plurk, reddit, skype, slack, sms, telegram, ' +
                                                'tencentqq, tencentweibo, trello, venmo, viber, wordpress, yahoomail, '
                                        }
                                    </script>
                                    <a class="addthis_button_compact addthis_64x64_style">
                                        <!-- <img src="{{asset('new/images/share-2.svg')}}" width="28" height="28" border="0" alt="Share to Twitter"> -->
                                        <i class="fa fa-share-alt" aria-hidden="true"></i>
                                        <span style="position: relative;padding-left: 5px;float: right;">
                                            Share
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="header-options-rightBox-container">
                                        <!-- <img src="{{asset('new/images/heart.svg')}}" alt="save" title="save" />
                                        <a href="#">Save</a> -->
                                        <span class="addToList" id="addButton{{$car_id}}" data-id="{{$car_id}}">
                                            <i class="far fa-heart"></i>
                                            <a href="javascript:;">Save</a>
                                        </span>
                                        <span class="removeToList hide" id="removeButton{{$car_id}}" data-id="{{$car_id}}">
                                            <i class="fa fa-heart red"></i>
                                            <a href="javascript:;">Remove</a>
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="header-options-rightBox-container">
                                        <!-- <img src="{{asset('new/images/square.svg')}}" alt="add-to-compare" title="add-to-compare" />
                                        <a href="javascript:;">Add to compare</a> -->
                                        @if(Auth::guard('customer')->user())
                                            <input type="checkbox" id="compare{!! $car_id !!}" class="compare_checkbox" value="{!! $car_id !!}" {!! in_array($car_id, $cookies) ? 'checked' : '' !!}>
                                        @else
                                            <input type="checkbox" id="compare{!! $car_id !!}" class="compare_checkbox" value="{!! $car_id !!}" {!! in_array($car_id, $cookies) ? 'checked' : '' !!}>
                                        @endif
                                        <label for="compare{!! $car_id !!}">Add to compare</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- header-options -->

                <!-- Photos Product -->
                <div class="photo-product-container">
                    <div class="row">
                        <div id="spin-car-section" class="spin-car-section">Spin Car</div>
    {{--                    <div class=" col-md-6 col-sm-12 viwer-product-3d-handler">--}}
    {{--                        <div--}}
    {{--                                class="viwer-product-3d cloudimage-360 img-fluid"--}}
    {{--                                data-folder="images/"--}}
    {{--                                data-filename="honda-car-{index}.jpg"--}}
    {{--                                data-amount="4">--}}
    {{--                            <div class="btn-container">--}}
    {{--                                <button class="exterior">exterior</button>--}}
    {{--                                <button class="interior">interior</button>--}}
    {{--                            </div>--}}
    {{--                            <div class="viwer-product-3d-icon">--}}
    {{--                                <img src="{{asset('new/images/360 degree view.svg')}}" alt="360 degree view" title="360 degree view">--}}
    {{--                                Drag to 360 degree view--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="col-md-6 col-sm-12 car-item-handler">--}}
    {{--                        <div class="row">--}}
    {{--                            <div class="col-md-6 col-sm-12 car-item">--}}
    {{--                                <img src="{{ 'https://manager.spincar.com/web-preview/walkaround-thumb/tanyaje/' . strtolower($vim) . '/md'  }}" alt="engine-car" title="engine-car" class="img-fluid">--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}

                    </div>
                </div>
                <!-- photos product -->
            </div>
            <!-- container -->
        </section>
        <!-- End Single Product Page Header-->

        <!--  Product Info -->
        <section class="product-info-section">
            <!-- container -->
            <div class="container">

                <!-- Product Info Container -->
                <div class="product-info-container" style="position: relative">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="main-title-handler">
                                <div class="main-title">
                                    <h2 class="product-info-main-title">
{{--                                        <span>vin:</span>--}}
                                        <span id="vin" class="vin" style="display: none">{{ $vim }}</span>
                                    </h2>
                                    <h2 class="product-info-main-title-listed-by">
                                        <span>listed by:</span>
                                        <a href="{{ $merchant_slug ? route('site.merchant',$merchant_slug) : '#'}}">
                                            {{ $merchant_name }}
                                        </a>
                                    </h2>
                                </div>
                                <div class="brand">
{{--                                    <img src="{{asset('new/images/carbrandImg-07.png')}}" alt="brand" title="brand">--}}
                                </div>
                            </div>
                            <div class="main-product-features">
                                <div class="row">
                                    <div class="col-md-8 offset-col-4">
                                        <div class="row">
                                            @if(isset($color) && $color !="")
                                            <div class="col-lg-6 col-md-12">
                                                <div class="feature-handler">
{{--                                                    <img src="{{asset('new/images/color.svg')}}" alt="silver color" title="silver color">--}}
                                                    <h3>{{ isset($color)? $color: "Unknown" }}</h3>
                                                </div>
                                            </div>
                                            @endif

                                            @if(isset($type_name) && $type_name !="")
                                            <div class="col-lg-6 col-md-12">
                                                <div class="feature-handler">
{{--                                                    <img src="{{asset('new/images/car Categories.svg')}}" alt="sedans" title="sedans">--}}
                                                    <h3>{{ isset($type_name)? $type_name: "Unknown" }}</h3>
                                                </div>
                                            </div>
                                            @endif

                                            @if(isset($engine_capacity) && $engine_capacity !="")
                                            <div class="col-lg-6 col-md-12">
                                                <div class="feature-handler">
{{--                                                    <img src="{{asset('new/images/engine capacity.svg')}}" alt="engine capacity" title="engine capacity">--}}
                                                    <h3>{{ isset($engine_capacity)? $engine_capacity: "Unknown" }}</h3>
                                                </div>
                                            </div>
                                            @endif

                                            @if(isset($fuel_type) && $fuel_type !="")
                                            <div class="col-lg-6 col-md-12">
                                                <div class="feature-handler">
{{--                                                    <img src="{{asset('new/images/petrol.svg')}}" alt="petrol" title="petrol">--}}
                                                    <h3>{{ isset($fuel_type)? $fuel_type: "Unknown" }}</h3>
                                                </div>
                                            </div>
                                            @endif

                                            @if(isset($seats) && $seats !="")
                                            <div class="col-lg-6 col-md-12">
                                                <div class="feature-handler">
{{--                                                    <img src="{{asset('new/images/car seats.svg')}}" alt="car seats" title="car seats">--}}
                                                    <h3>{{ isset($seats)? $seats. " seats" : "Unknown" }}</h3>
                                                </div>
                                            </div>
                                            @endif

                                            @if(isset($transmission) && $transmission !="")
                                            <div class="col-lg-6 col-md-12">
                                                <div class="feature-handler">
{{--                                                    <img src="{{asset('new/images/car gear.svg')}}" alt="car gear" title="car gear">--}}
                                                    <h3>{{ isset($transmission)? $transmission: "Unknown" }}</h3>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="main-product-features">
                                <h2 class="features-title">features</h2>
                                <div class="row">
                                    <div class="col-md-8 offset-col-4">
                                        <div class="row">
                                            @if( count($features) > 0 && $features[0] != "" )
                                                @for($i=0;$i<count($features);$i++)
                                                <div class="col-lg-6 col-md-12 features-info-handler" style="padding:0 0">
                                                    <h3>{{ $features[$i] }}</h3>
                                                </div>
                                                @endfor
                                            @else
                                                <div style="padding-left:10px">
                                                    No Features Found Or Input By Seller
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(isset($description))
                            <div class="main-product-features">
                                <h2 class="features-title">Description</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div>
                                            <pre style="font-family: 'Red Hat Display', sans-serif; font-size: 16px">{!! $description !!}</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-5 col-lg-4 offset-lg-1">
                            <div class="card sticky-top">
                                <div class="card-body">
                                    <h5 class="text-center card-title">RM {{ number_format($price, 2, '.', ',') }} </h5>
                                    @if(isset($mileage) && $mileage !="")
                                    <div class="features">
                                        <div class="feature">
{{--                                            <img src="{{asset('new/images/mileage.svg')}}" alt="mileage" title="mileage" class="img-fluid">--}}
                                            mileage:
                                        </div>
                                        <div class="value">
                                            {{ isset($mileage) && $mileage !="" ? convertMileage($mileage) . " km": "Unknown" }}
                                        </div>
                                    </div>
                                    @endif

                                    <div class="features">
                                        <div class="feature">
{{--                                            <img src="{{asset('new/images/brand.svg')}}" alt="brand" title="brand" class="img-fluid">--}}
                                            brand:
                                        </div>
                                        <div class="value">
                                            {{ isset($make_name)? $make_name: "Unknown" }}
                                        </div>
                                    </div>
                                    <div class="features">
                                        <div class="feature">
{{--                                            <img src="{{asset('new/images/Model.svg')}}" alt="model" title="model" class="img-fluid">--}}
                                            model:
                                        </div>
                                        <div class="value">
                                            {{ isset($model_name)? ucwords(strtolower($model_name)): "Unknown" }}
                                        </div>
                                    </div>
                                    <div class="features">
                                        <div class="feature">
{{--                                            <img src="{{asset('new/images/Year.svg')}}" alt="year" title="year" class="img-fluid">--}}
                                            year:
                                        </div>
                                        <div class="value">
                                            {{ isset($year_make)? $year_make: "Unknown" }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="varify-inner">
{{--                                        <img src="{!! asset('images/new/varified.png') !!}" alt="varified">--}}
                                    </div>
                                    <div class="card-footer">
                                        <ul>
                                            <li>
                                                <a href="tel:{!! $merchant_phone_no !!}" style="padding-left: 0px;">
                                                    <img src="{!! asset('new/images/call.png') !!}" alt="call" title="call">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://wa.me/{!! $merchant_phone_no !!}" >
                                                    <img src="{!! asset('new/images/whatsup.png') !!}" alt="whatsapp"  title="whatsapp">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ $direction_google_map_url }}" target="_blank">
                                                    <img src="{!! asset('new/images/location.png') !!}" alt="direction"  title="direction">
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
{{--                                <div class="card-footer">--}}
{{--                                    <div class="btn-handler">--}}
{{--                                        https://wa.me/{{ str_replace(' ', '', $merchant_phone_no) }}--}}
{{--                                        <a href="{{ $whatsapp_message }}" class="btn-whatsapp" target="_blank">--}}
{{--                                            <img src="{{asset('new/images/whatsapp.svg')}}" alt="whatsapp" title="whatsapp">--}}
{{--                                            whatsapp now--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                    <div class="btn-handler">--}}
{{--                                        <a href="tel:+{{ str_replace(' ', '', $merchant_phone_no) }}" class="btn-call">--}}
{{--                                            call now--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Info Container -->
            </div>
            <!-- container -->
        </section>
        <!--  Product Info -->

{{--        <!-- Sticky Top Car Info -->--}}
{{--        <section class="sticky-top-car-section" id="sticky-top-car-section">--}}
{{--            <!-- container -->--}}
{{--            <div class="container">--}}
{{--                <div class="sticky-top-car-container">--}}
{{--                    <div class="basic-info">--}}
{{--                        <div class="single-product-main-title">--}}
{{--                            <span>{{ $year_make }}</span>--}}
{{--                            {{ substrwords($title, 45) }}--}}
{{--                            <span class="car-price">RM {{ number_format($price, 0, '.', ',') }} </span>--}}
{{--                        </div>--}}

{{--                        <div class="header-options-container">--}}
{{--                        <div class="disflexArea header-options-handler detail-inner-sec">--}}
{{--                            <div class="header-options-leftBox">--}}
{{--                                <ul>--}}
{{--                                    <li>--}}
{{--                                        <div class="header-options-rightBox-container">--}}
{{--                                            <img src="{{asset('new/images/popularIcon02.png')}}" alt="popular-icon" title="place">--}}
{{--                                            <span>{{ ucwords($city_name) }}, {{ ucwords($state_name) }}</span>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <div class="header-options-rightBox-container">--}}
{{--                                            <img src="{{asset('new/images/mileage.svg')}}" alt="mileage" title="mileage" width="34" height="30">--}}
{{--                                            <span>{{ isset($mileage) && $mileage !="" ? convertMileage($mileage) . " km": "Unknown" }}</span>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                            <div class="header-options-rightBox icon-detail">--}}
{{--                                <ul>--}}
{{--                                    <li>--}}
{{--                                        <a class="addthis_button_compact" href="#">--}}
{{--                                            <i class="fa fa-share-alt" aria-hidden="true"></i>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                   <li>--}}
{{--                                        <div class="header-options-rightBox-container">--}}
{{--                                            <span class="addToList" id="addButton{{$car_id}}" data-id="{{$car_id}}">--}}
{{--                                                <i class="far fa-heart"></i>--}}
{{--                                            </span>--}}
{{--                                            <span class="removeToList hide" id="removeButton{{$car_id}}" data-id="{{$car_id}}">--}}
{{--                                                <i class="fa fa-heart red"></i>--}}
{{--                                            </span>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <div class="header-options-rightBox-container">--}}
{{--                                            @if(Auth::guard('customer')->user())--}}
{{--                                                <input type="checkbox" id="sticky_compare{!! $car_id !!}" class="compare_checkbox" value="{!! $car_id !!}" {!! in_array($car_id, $cookies) ? 'checked' : '' !!}>--}}
{{--                                            @else--}}
{{--                                                <input type="checkbox" id="sticky_compare{!! $car_id !!}" class="compare_checkbox" value="{!! $car_id !!}" {!! in_array($car_id, $cookies) ? 'checked' : '' !!}>--}}
{{--                                            @endif--}}
{{--                                            <label for="sticky_compare{!! $car_id !!}">compare</label>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    </div>--}}
{{--                    <div class="contact-info">--}}
{{--                        <div class="btn-handler">--}}
{{--                            <ul>--}}
{{--                                --}}{{-- <li>--}}
{{--                                    <div style="display: flex">--}}
{{--                                        <div class="car_price_mobile_sect">--}}
{{--                                            <span class="car_price_mobile">RM {{ number_format($price, 0, '.', ',') }} </span>--}}
{{--                                        </div>--}}
{{--                                        <div>--}}
{{--                                            <a class="addthis_button_compact mobile" href="#">--}}
{{--                                                <img src="{{asset('new/images/share-2.svg')}}" width="28" height="28" border="0" alt="Share to Twitter">--}}
{{--                                                <div class="btn_share">--}}
{{--                                                    Share--}}
{{--                                                </div>--}}
{{--                                            </a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </li> --}}
{{--                                <li>--}}
{{--                                    https://wa.me/{{ str_replace(' ', '', $merchant_phone_no) }}--}}
{{--                                    <a href="{{ $whatsapp_message }}" class="btn-whatsapp" target="_blank">--}}
{{--                                        <img src="{{asset('new/images/whatsapp.svg')}}" alt="whatsapp" title="whatsapp">--}}
{{--                                        whatsapp--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="tel:+{{ str_replace(' ', '', $merchant_phone_no) }}" class="btn-call">--}}
{{--                                        call now--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <!-- container -->--}}
{{--        </section>--}}
{{--        <!-- Sticky Top Car Info -->--}}

        <!-- Location -->
        <section class="location">
            <!-- container -->
            <div class="container">
                <h2 class="main-title">location</h2>
                <div class="location-general-info">
                    <div class="location-handler">
                        <iframe src="{{ $google_map_url }}"
                            width="100%"
                            height="450"
                            frameborder="0"
                            allowfullscreen=""
                            aria-hidden="false"
                            tabindex="0">
                        </iframe>
                    </div>
                    <div class="location-info">
                        <div class="row" style="align-items: center;">
                            <div class="col-lg-6 col-md-6">
                                <p class="lead address">
    {{--                                A-03-01, Block A Sunway Geo Avenue, Jalan Lagoon Selatan, Bandar Sunway, 47500 Subang Jaya, Selangor--}}
                                    {{ $address }}
                                </p>
                            </div>
                            <div class="col-lg-2 offset-lg-4 col-md-4 offset-md-2" style="padding-right: 0;">
                                <div class="btn-handler">
                                    <a href="{{ $direction_google_map_url }}" target="_blank">
                                        get direction
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- container -->
        </section>
        <!-- Location -->

       <!-- Seller Section -->
       <section class="seller-section">
           <!-- container -->
           <div class="container">
               <div class="seller-section-handler">
                   <div class="seller-header">
                       <div class="brand logo-sec">
                           @if(!empty($merchant_logo_id))
                               @php
                                   $logo = DB::table('images')
                                       ->leftJoin('image_categories', 'images.id', '=', 'image_categories.image_id')
                                       ->select('path','images.id','image_type')
                                       ->where('images.id', $merchant_logo_id)
                                       ->where('image_categories.image_type','ACTUAL')
                                       ->first();
                               @endphp
                               <img src="/{!! $logo->path !!}" alt="logo" title="brand" style="width:80px; height:80px; margin-top:unset; padding: unset;">
                           @endif
                       </div>
                       <div class="titles">
                           <h2>{{ $merchant_name }}</h2>
                           <span>Join in {!! Carbon\Carbon::parse($created_at)->format('F Y') !!}</span>
                       </div>
                   </div>
                   <div class="about-seller">
                       @if(!empty($merchant_info))
                       <h3>About this seller</h3>
                       <p class="lead">
                           {!! $merchant_info !!}
                       </p>
                       @endif
                   </div>
                   <div class="seller-contact">
                       <div class="contact-seller-handler">
                           <a href="tel:+{{ str_replace(' ', '', $merchant_phone_no) }}" class="contact-seller-btn">
                               contact seller
                           </a>
                       </div>
                       <div class="view-seller-handler">
                           <div class="btn-handler">
                               <a href="{{ $merchant_slug ? route('site.merchant',$merchant_slug) : '#'}}" class="view-from-seller">
                                   @if( $ttl_car_count > 0 )
{{--                                   view all {{ $ttl_car_count }} cars from this seller--}}
                                       view all
                                   @endif
                               </a>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
           <!-- container -->
       </section>
       <!-- Seller Section -->


        <!-- recommended cars -->
        <section class="popularsArea">
            <!-- container -->
            <div class="container">
{{--                <h2 class="main-title">Recommended Cars for You</h2>--}}
                <h2 class="main-title">Recommended for You</h2>
                <div class="itemBox disflexArea">
                    @if(!empty($random_car))
                        @foreach($random_car as $rs)
                            <div class="item">
                                <div class="image">
                                    {{--                                    <img class="Bgimage" src="{{ 'https://manager.spincar.com/web-preview/walkaround-thumb/tanyaje/' . strtolower($rs->vim) . '/md'  }}" alt="">--}}
                                    <a href="{{ route("car_details",$rs->getCarUrl()) }}">
                                        <img src="{{ 'https://manager.spincar.com/web-preview/walkaround-thumb/' . $rs->sp_account . '/' . strtolower($rs->vim) . '/md'  }}" alt="">
                                    </a>
                                </div>
                                <div class="sub">
                                <span class="addToList" id="addButton{{$rs->car_id}}" data-id="{{$rs->car_id}}">
                                    	<i class="far fa-heart"></i>
                                    </span>
                                    <span class="removeToList hide" id="removeButton{{$rs->car_id}}" data-id="{{$rs->car_id}}">
                                    	<i class="fa fa-heart red"></i>
                                    </span>
                                    <a href="{{ route("car_details",$rs->getCarUrl()) }}">
                                        <div class="title-container">
                                            <div class="year-car">{{$rs->year_make}}</div><div class="title-car"><h3 style="font-size: 17px !important;font-weight: 700 !important;">{{$rs->title}}</h3></div>
                                        </div>
                                    </a>
                                    <p>Listed by: <a class="listed-by" href="{{ $rs->merchant_slug ? route('site.merchant',$rs->merchant_slug): '#' }}">{{ $rs->merchant_name }}</a></p>
                                    <h4>RM {{ number_format($rs->price, 2, '.', ',') }} </h4>
                                    <span class="vin" style="display: none;">{{ $rs->vim }}</span>
                                    <ul>
                                        @if(isset($rs->mileage) && $rs->mileage !="")
                                        <li><img src="{{asset('new/images/popularIcon01.png')}}" alt="">{{ isset($rs->mileage)? convertMileage($rs->mileage) ." km": "Unknown" }} </li>
                                        @endif

                                        @if(isset($rs->city_name) && $rs->city_name !="")
                                        <li><img src="{{asset('new/images/popularIcon02.png')}}" alt="">{{ ucwords($rs->city_name) }}, {{ ucwords($rs->state_name) }}</li>
                                        @endif

                                        @if(isset($rs->color) && $rs->color !="")
                                        <li><img src="{{asset('new/images/popularIcon03.png')}}" alt="">{{ isset($rs->color)? convertColor($rs->color) : "Unknown" }}</li>
                                        @endif

                                        @if(isset($rs->fuel_type) && $rs->fuel_type !="")
                                        <li><img src="{{asset('new/images/popularIcon04.png')}}" alt="">{{ isset($rs->fuel_type)? ucwords($rs->fuel_type): "Unknown" }}</li>
                                        @endif
                                    </ul>
                                    <div class="AddtobOX" style="padding-bottom: 20px;">
                                        <div class="form-group">
                                            @if(Auth::guard('customer')->user())
                                                <input type="checkbox" id="compare{!! $rs->car_id !!}" class="compare_checkbox" value="{!! $rs->car_id !!}" {!! in_array($rs->car_id, $cookies) ? 'checked' : '' !!}>
                                            @else
                                                <input type="checkbox" id="compare{!! $rs->car_id !!}" class="compare_checkbox" value="{!! $rs->car_id !!}" {!! in_array($rs->car_id, $cookies) ? 'checked' : '' !!}>
                                            @endif
                                            <label for="compare{!! $rs->car_id !!}">Add to compare</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
            <!-- container -->
        </section>
        <!-- recommended cars -->

    </section>
    <!-- END content_part-->

@endsection

@push('scripts')
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5f17ff7371b9f206"></script>
    <script>
        // // When the user scrolls the page, execute myFunction
        // window.onscroll = function() {myFunction()};

        // // Get the navbar
        // var navbar = document.getElementById("sticky-top-car-section");
        //
        // // Get the offset position of the navbar
        // var sticky = navbar.offsetTop;
        //
        // // Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
        // function myFunction() {
        //     if ($(window).width() > 767) {
        //         if (window.pageYOffset >= sticky) {
        //             navbar.classList.add("sticky")
        //             $(".card.sticky-top").hide();
        //             $(".sticky-top-car-section").show();
        //
        //         } else {
        //             navbar.classList.remove("sticky");
        //             $(".card.sticky-top").show();
        //             $(".sticky-top-car-section").hide();
        //         }
        //     }
        //     else
        //     {
        //         if (window.pageYOffset + 500 >= sticky  ) {
        //             navbar.classList.add("sticky")
        //             $(".card.sticky-top").hide();
        //             $(".sticky-top-car-section").show();
        //
        //         } else {
        //             navbar.classList.remove("sticky");
        //             $(".card.sticky-top").show();
        //             $(".sticky-top-car-section").hide();
        //         }
        //     }
        // }

        jQuery(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var car_viewed = new cookieList("car_viewed");
            var car_id = "{!! $car_id !!}"
            console.log(car_viewed.items());
            if($.inArray(car_id, car_viewed.items()) == -1)
            {
                jQuery.post("{!! route('car_viewed') !!}",{car_id: car_id},function(response){
                });
                car_viewed.add(car_id);
            }


            var list = new cookieList("car_compare_list");
            var is_user = "{!! Auth::guard('customer')->check() !!}";
            jQuery(".compare_checkbox").on('click',function(){
                if(jQuery(this).prop('checked') == true) {
                    if(list.length() > 3)
                    {
                        alert("You can't campare more then 4 cars");
                        return false;
                    }
                    else
                    {
                        if(is_user)
                        {
                            jQuery.post("{!! route('carcompare.add') !!}",{car_id: $(this).val()},function(response){
                                alert(response);
                            });
                        }
                        else
                        {
                            list.add($(this).val());
                        }
                        if(jQuery(this).attr('id').indexOf("sticky") != -1)
                        {
                            jQuery(jQuery(this).attr('id').replace("sticky_","#")).attr('checked','checked');
                        }
                        else
                        {
                            jQuery("#sticky_"+jQuery(this).attr('id')).attr('checked','checked');
                        }

                    }
                }
                else{
                        if(is_user)
                        {
                            jQuery.post("{!! route('carcompare.remove') !!}",{car_id: $(this).val()},function(response){
                                alert(response);
                            });
                        }
                        else
                        {
                            list.remove($(this).val());
                        }
                        if(jQuery(this).attr('id').indexOf("sticky") != -1)
                        {
                            jQuery(jQuery(this).attr('id').replace("sticky_","#")).removeAttr('checked');
                        }
                        else
                        {
                            jQuery("#sticky_"+jQuery(this).attr('id')).removeAttr('checked');
                        }
                }
            });
        });



        var cookieList = function(cookieName) {
            var cookie = Cookies.get(cookieName);
            var items = cookie ? cookie.split(/,/) : new Array();
            return {
                "add": function(val) {
                    //Add to the items.
                    items.push(val);
                    Cookies.set(cookieName, items.join(','));
                },
                "remove": function (val) {
                    indx = items.indexOf(val);
                    if(indx!=-1) items.splice(indx, 1);
                    Cookies.set(cookieName, items.join(','));
                },
                "length": function () {
                    return items.length;
                    },
                "items": function() {
                    //Get all the items.
                    return items;
                }
            }
        }

        //keep trace user call merchant
        jQuery(document).ready(function(){
            var message = "Car ID: {{ $car_id }}"+ " | " + "VIN: {{ $vim }}" + " | " + "Title: {{ $title }}";
            $(".btn-whatsapp").on("click",function(){
                gtag('event', 'Contact Using Whatsapp', {
                    'event_category': 'Event',
                    'event_label': 'Whatsapp',
                    'value': message
                });
            });

            $(".btn-call").on("click",function(){
                gtag('event', 'Contact Using Phone', {
                    'event_category': 'Event',
                    'event_label': 'Phone',
                    'value': message
                });
            });
        });

    </script>
@endpush
