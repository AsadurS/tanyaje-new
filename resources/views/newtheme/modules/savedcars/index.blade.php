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

    <!-- START savedCarArea -->
    <section class="popularsArea savedCarArea">
        <div class="container">
            <div class="heading" style="height:30px">
                <button type="submit" class="Searchbtn">COMPARE</button>
                <h4>Saved Cars</h4>
            </div>

            <div class="itemBox disflexArea">
                <div class="item">
                    <div class="image">
                        <a href="#"> <img src="{{asset('new/images/popular_img01.jpg')}}" alt=""></a>
                    </div>
                    <div class="sub">
                        <i class="fa fa-heart red"></i>
                        <div class="title-container">
                            <a href="#"><div class="year-car">2017</div> <div class="title-car"><h3>TOYOTA ALPHARD SC</h3></div></a>
                        </div>
                        <p>Listed by: NZ Wheels Malaysia</p>
                        <h4>RM 319,000</h4>
                        <ul>
                            <li><img src="{{asset('new/images/popularIcon01.png')}}" alt="">52,435 km</li>
                            <li><img src="{{asset('new/images/popularIcon02.png')}}" alt="">Selangor</li>
                            <li><img src="{{asset('new/images/popularIcon03.png')}}" alt="">White Color</li>
                            <li><img src="{{asset('new/images/popularIcon04.png')}}" alt="">Petrol</li>
                        </ul>
                    </div>
                </div>
                <div class="item">
                    <div class="image">
                        <a href="#"> <img src="{{asset('new/images/popular_img02.jpg')}}" alt=""></a>
                    </div>
                    <div class="sub">
                        <i class="fa fa-heart red"></i>
                        <div class="title-container">
                            <a href="#"><div class="year-car">2017</div> <div class="title-car"><h3>PERODUA AXIA 998</h3></div></a>
                        </div>
                        <p>Listed by: FCC CAR TRADE SDN BHD</p>
                        <h4>RM 21,800</h4>
                        <ul>
                            <li><img src="{{asset('new/images/popularIcon01.png')}}" alt="">52,435 km</li>
                            <li><img src="{{asset('new/images/popularIcon02.png')}}" alt="">Selangor</li>
                            <li><img src="{{asset('new/images/popularIcon03.png')}}" alt="">White Color</li>
                            <li><img src="{{asset('new/images/popularIcon04.png')}}" alt="">Petrol</li>
                        </ul>
                    </div>
                </div>
                <div class="item">
                    <div class="image">
                        <a href="#"> <img src="{{asset('new/images/popular_img03.jpg')}}" alt=""></a>
                    </div>
                    <div class="sub">
                        <i class="fa fa-heart red"></i>
                        <div class="title-container">
                            <a href="#"><div class="year-car">2015</div> <div class="title-car"><h3>PERODUA MYVI 1.5</h3></div></a>
                        </div>
                        <p>Listed by: NZ Wheels Malaysia</p>
                        <h4>RM 33,800</h4>
                        <ul>
                            <li><img src="{{asset('new/images/popularIcon01.png')}}" alt="">52,435 km</li>
                            <li><img src="{{asset('new/images/popularIcon02.png')}}" alt="">Selangor</li>
                            <li><img src="{{asset('new/images/popularIcon03.png')}}" alt="">White Color</li>
                            <li><img src="{{asset('new/images/popularIcon04.png')}}" alt="">Petrol</li>
                        </ul>
                    </div>
                </div>
                <div class="item">
                    <div class="image">
                        <a href="#"> <img src="{{asset('new/images/popular_img04.jpg')}}" alt=""></a>
                    </div>
                    <div class="sub">
                        <i class="fa fa-heart red"></i>
                        <div class="title-container">
                            <a href="#"><div class="year-car">2014</div> <div class="title-car"><h3>TOYOTA VIOS FULL SP</h3></div></a>
                        </div>
                        <p>Listed by: FCC CAR TRADE SDN BHD</p>
                        <h4>RM 48,668</h4>
                        <ul>
                            <li><img src="{{asset('new/images/popularIcon01.png')}}" alt="">52,435 km</li>
                            <li><img src="{{asset('new/images/popularIcon02.png')}}" alt="">Selangor</li>
                            <li><img src="{{asset('new/images/popularIcon03.png')}}" alt="">White Color</li>
                            <li><img src="{{asset('new/images/popularIcon04.png')}}" alt="">Petrol</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- END savedCarArea -->

</section>
<!-- END content_part-->

@endsection