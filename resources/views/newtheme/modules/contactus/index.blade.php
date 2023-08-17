@extends('newtheme.layouts.main')
@section('meta_for_share')
    <meta property="og:title" content="{{ trans('labels.app_name') }} | Contact Us">
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
    @if (count($errors) > 0)
        @if($errors->any())
            <div class="alert alert-success alert-dismissible" role="alert">
                {{$errors->first()}}
            </div>
        @endif
    @endif

    <!-- START aboutUsArea -->
    <section class="contactUsArea">
        <div class="container">
            <div class="left-pane">
                <ul>
                    <li>
                        <div class="menu-item">
                            <a href="{{ route("about_us") }}">
                                About Us
                            </a>
                        </div>
                    </li>
                    <li class="active">
                        <div class="menu-item">
                            Contact Us
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
                    By mail or phone
                </div>
                <div>
                    <div class="phone-sec">
                        <img src="{{asset('new/images/phone.svg')}}" alt="phone" title="phone" width="20px" height="20px">
                        <a href="#">+6012-6443833</a>
                    </div>

                    <div class="email-sec">
                        <img src="{{asset('new/images/email.svg')}}" alt="email" title="email" width="20px" height="20px">
                        <a href="#">info@tanyaje.com.my</a>
                    </div>

                    <p class="help-us-improve">
                        Help us improve by giving us your valued feedback. Your comments are much appreciated.
                    </p>

                    <div class="itemBox">
                        {!! Form::open(array('url' =>'contactus', 'method'=>'post', 'class'=>'form-validate')) !!}
                        <div class="row">
                            <label>Full Name</label><br/>
                            <input id="full_name" name="full_name" type="text" value="" required>
                        </div>

                        <div class="row">
                            <div class="phone-email-info">
                                <div class="phone-info">
                                    <label>Phone</label><br/>
                                    <input id="phone" name="phone" type="text" value="" required>
                                </div>
                                <div class="email-info">
                                    <label>Email</label><br/>
                                    <input id="email" name="email" type="email" value="" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label>Message</label><br/>
                            <input id="message" name="message" type="text" value="" required>
                        </div>

                        <div class="btn-send-now-sec">
                            <button type="submit" class="SendNowbtn btn">SEND NOW</button>
                            <div class="row">
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END aboutUsArea -->

</section>
<!-- END content_part-->

@endsection