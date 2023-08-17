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
                <div class="title" style="text-align: center;">Reset your Password ?</div>
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
                        <div class="title main-log"></div>
                    </div>
                    <form method="post" action="{{ route('customer_new_password',$token) }}">
                            @csrf
                             <div class="row">
                                <div class="state-zip-info">
                                    <div class="state-info">
                                        <label>Password</label><br/>
                                        <input type="password" name="password" required>
                                    </div>
                                    <div class="state-info">
                                        <label>Confirm Password</label><br/>
                                        <input type="password" name="confirm_password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="btn-save-sec">
                                <input type="submit" class="savebtn btn" value="Reset Password">
                            </div>
                        </form>
                    <div class="reg-form">Return <a href="{{ route('customer_login') }}">LOG IN PAGE</a></div>
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
