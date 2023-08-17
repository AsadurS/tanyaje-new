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
            <div class="left-pane" >
                <ul>
                    <li>
                        <div class="menu-item">
                            <a href="{{ route("about_us") }}">
                                About Us
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="menu-item">
                            Contact Us
                        </div>
                    </li>
                    <li class="active">
                        <div class="menu-item">
                            Contact Us For Voucher
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
                        Fill out the details to redeem the voucher {{ $voucher_name }}
                    </p>

                    <div class="itemBox">
                        {!! Form::open(array('url' =>'contactusforvoucher', 'method'=>'post', 'class'=>'form-validate')) !!}
                        <div class="row">
                            <div class="fullname-ic-info">
                                <div class="fullname-info">
                                    <label>Full Name</label><br/>
                                    <input id="full_name" name="full_name" type="text" value="" required>
                                </div>
                                <div class="ic-info">
                                    <label>IC</label><br/>
                                    <input id="ic" name="ic" type="text" value="" required>
                                </div>
                            </div>
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
                            <label>Address</label><br>
                            <input type="text" name="entry_street_address" value="" required="">
                        </div>

                        <div class="row">
                            <div class="state-zip-info">
                                <div class="state-info">
                                    <label>State</label><br>
                                    <select name="state" required="">
                                        <option value="">Select State</option>

                                        @foreach ($states as $state)
                                            <option value="{{$state->state_name}}">{{$state->state_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="state-info">
                                    <label>Zip</label><br>
                                    <input type="text" name="entry_postcode" value="" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label>Remark</label><br/>
                            <input id="remark" name="remark" type="text" value="" required>
                        </div>

                        <div class="btn-send-now-sec">
                            <input type="hidden" name="voucher_name" value="{{ $voucher_name }}">
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