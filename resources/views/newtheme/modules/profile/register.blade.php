@extends('newtheme.layouts.main')
@section('meta_for_share')
    <meta property="og:title" content="Tanyaje">
    <meta property="og:url" content="https://tanyaje.com.my/">
    <meta property="og:image" content="http://cdn.spincar.com/swipetospin-viewers/tanyaje/3118/20200704071604.YL3PQT7T/closeups/cu-0.jpg">
    <meta property="og:description" content="Tanya-Je translated as “Just Ask” is the first most advanced online digital automotive classified in South East Asia.">
@endsection
@section('content')
<!-- START content_part-->
<section class="banner_bar create-ac" style="background-image: url({{asset('/images/new/banner-bg.jpg')}});">
    <div class="container">
    <div class="subbox-main">
       <!-- <div class="subbox-left" style="background-image: url({{asset('/images/new/banner-bg.jpg')}});">
            <h3>The New 360° Viewing Experience</h3>
            <img src="{!! asset('/images/new/route-car.gif') !!}">
        </div> -->
        <!-- START tabbedPanels -->
        <div class="tabPanels full-form-part">
            <div class="menuTab">
                <div class="title" style="text-align: center;">CREATE ACCOUNT</div>
            </div>

            <!-- START panelContainer -->
            <div class="panelContainer">
                <div id="panel1" class="panel">
                    <div class="subbox-right">
                        @if( count($errors) > 0 )
                            <div class="alert alert-danger alert-dismissible">
                                {{--  <button type="button" class="close" data-dismiss="alert">&times;</button> --}}
                                <strong style="font-weight: bold;color: #e4280a;font-size: 16px;">
                                    Sorry  !
                                </strong>
                                <strong style="font-weight: bold;color: #e4280a;">
                                    {{ $errors->first() }}
                                </strong>
                            </div>
                        @endif

                    @if (Session::get('message'))
                        <div class="alert alert-success alert-dismissible">
                            {{--  <button type="button" class="close" data-dismiss="alert">&times;</button> --}}
                            <strong>Success!</strong> {{ Session::get('message') }}
                        </div>
                    @endif

                    <div class="heading">
                        <div class="title main-log">Sign up for account</div>
                    </div>
                    <form method="post" action="{{ route('customer_register') }}">
                            @csrf
                            <div class="row">
                                <label>Your Name<sup>*</sup></label>
                                <input type="text" name="first_name" value="{{old('first_name')}}" placeholder="your name" required>
                            </div>

                            <div class="row">
                                <label>Email<sup>*</sup></label>
                                <input type="email" name="email" value="{{old('email')}}" placeholder="youremail@email.com" required>
                            </div>

                            <div class="row">
                                <label>Mobile Number<sup>*</sup></label>
                                <input type="text" name="phone" value="{{old('phone')}}" placeholder="+601xxxxxxxx" required>
                            </div>

                            <div class="row">
                                <label>Password<sup>*</sup></label>
                                <input id="txtPassword" type="password" name="password" required>
                            </div>

                            <div class="row">
                                <label>Confirm Password<sup>*</sup></label>
                                <input id="txtConfirmPassword" type="password" name="confirm_password" required>
                            </div>

                            <input type="submit" class="savebtn btn" value="Register">
                        </form>

                    <div class="reg-form">Already have Tanya Je account? <a href="{{ route('customer_login') }}">Log in here</a></div>
                </div>
                </div>
                </div>
            </div>
            </div>
            <!-- END panelContainer -->

        </div>
        <!-- END tabbedPanels -->

    </div>
</section>

</section>
<!-- END content_part-->

@endsection
@section('scripts')

<script type="text/javascript">
    window.onload = function () {
        var txtPassword = document.getElementById("txtPassword");
        var txtConfirmPassword = document.getElementById("txtConfirmPassword");
        txtPassword.onchange = ConfirmPassword;
        txtConfirmPassword.onkeyup = ConfirmPassword;
        function ConfirmPassword() {
            txtConfirmPassword.setCustomValidity("");
            if (txtPassword.value != txtConfirmPassword.value) {
                txtConfirmPassword.setCustomValidity("Passwords do not match.");
            }
        }
    }
</script>
@endsection