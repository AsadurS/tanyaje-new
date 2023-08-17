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
        <div class="subbox-left" style="background-image: url({{asset('/images/new/banner-bg.jpg')}});">
            <h3>The New 360° Viewing Experience</h3>
            <img src="{!! asset('/images/new/route-car.gif') !!}">
        </div>
        <!-- START tabbedPanels -->
        <div class="tabPanels full-form-part forgot-password">
            <div class="menuTab">
                @if (Session::get('message'))
                    <div class="title" style="text-align: center;">SUCCESS</div>
                @else
                    <div class="title" style="text-align: center;">Forgot Password ?</div>
                @endif
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
                    <div class="heading">
                        @if (Session::get('message'))
                            <div class="title main-log">{{ Session::get('message') }}</div>
                        @else
                            <div class="title main-log">Enter the email you used when you joined and we will send you link</div>
                        @endif
                    </div>
                    <form method="post" action="{{ route('customer_forget_password') }}">
                        @csrf
                        <div class="row">
                            
                            @if (Session::get('message'))
                                <input type="email" name="email" value="{{old('email')}}" placeholder="youremail@email.com" disabled>
                            @else
                                <label>Enter Email<sup>*</sup></label>
                                <input type="email" name="email" value="{{old('email')}}" placeholder="youremail@email.com" required>
                            @endif
                        </div>
                        <div class="btn-save-sec">
                            @if (!Session::get('message'))
                                <input type="submit" class="savebtn btn" value="Send recovery link">
                            @else
                                <a href="{{ route('customer_login') }}" class="savebtn btn">RETURN LOG IN PAGE</a>
                            @endif
                        </div>

                    </form>
                    <div class="reg-form">
                        @if (!Session::get('message'))    
                            Return <a href="{{ route('customer_login') }}">LOG IN PAGE</a>
                        @endif
                    </div>
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
<!-- END content_part-->

@endsection
