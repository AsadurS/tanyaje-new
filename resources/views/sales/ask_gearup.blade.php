<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-172026171-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-172026171-1');
        </script>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tanyeje - @foreach($data['sale_advisor'] as $sa){{$sa->merchant_name}}@endforeach - Show Me</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <!-- Styles -->
        <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/salesapp.css?v='. $data['asset_version'] .'') }}" rel="stylesheet">
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,400;0,900;1,400;1,900&display=swap');
        #app {
            font-family:'Red Hat Display'
        }
        @foreach($data['template'] as $sa)
            {{ $sa->css_code }}
        @endforeach
        </style>
    </head>
    <body class="webapp" style="background-image:url('{{ asset('new/images/sales/bg.jpg') }}')">
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
            <div class="frameOuter container">
                <div class="row align-items-center">
                    @include('sales.common.desktopintro')
                    <div class="frame col-md-auto">
                        <img src="{{asset('new/images/sales/phone-container.png')}}" class="phone-container">
                        <img src="{{asset('new/images/sales/phone-container.png')}}" class="overlap-phone-container">
                        @include('sales.common.stickyhelp', [ 'openSticky' => true ])
                        <div id="app" class="{{  $data['sale_advisor'][0]->landingpage_version == 1 ? "lite" : "full" }}"  v-cloak>
                            <sales-header
                                :link_landing="'{{ url($data['sa_base_url']) }}'"
                                :link_verify="'{{ url($data['sa_base_url'].'/verify') }}'"
                                :link_showme="'{{ url($data['sa_base_url'].'/show/make') }}'"
                                :link_askme="'{{ url($data['sa_base_url'].'/ask') }}'"
                                :link_keepme="'{{ url($data['sa_base_url'].'/keep') }}'"
                                :image="'{{asset('new/images/sales/header-image.jpg')}}'"
                                :template="{{ $data['template'] }}"
                                :breadcrumb="{title:'Gear Up', link: '{{url($data['sa_base_url'])}}/ask'}"
                                :sadata="{{ $data['sale_advisor'][0] }}"
                            ></sales-header>
                            <sales-ask-brochure 
                                :doctype="'Gear Up'"
                                :askme="{{ $data['askme'] }}"
                            ></sales-ask-brochure>
                            <!--<sales-subfooter
                                :image_callnow="'{{asset('new/images/sales/call-now.svg')}}'"
                                :image1="'{{asset('new/images/sales/footer-btngroup1.jpg')}}'"
                                :template="{{ $data['template'] }}"
                            ></sales-subfooter>-->
                            <!--<sales-footer
                                :link_showme="'{{ url($data['sa_base_url'].'/show') }}'"
                                :link_askme="'{{ url($data['sa_base_url'].'/ask') }}'"
                                :link_keepme="'{{ url($data['sa_base_url'].'/keep') }}'"
                                :image_callnow="'{{asset('new/images/sales/call-now.svg')}}'"
                                :image1="'{{asset('new/images/sales/footer-btngroup1.jpg')}}'"
                                :image2="'{{asset('new/images/sales/footer-btngroup2.jpg')}}'"
                                :template="{{ $data['template'] }}"
                                :sadata="{{ $data['sale_advisor'][0] }}"
                                :simplifymenu="1"
                            ></sales-footer>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <img src="{{ asset('new/images/sales/desktop_footerbar-top.png') }}">
            <div class="footer-content" style="background-image:url('{{ asset('new/images/sales/desktop_footerbarbg.png') }}')">
                <div class="container">
                <img src="{{ asset('new/images/sales/desktop_digitalmu.png') }}">
                <a href="#" class="button tryfree">TRY IT FREE</a>
                <img style="margin-left:auto" src="{{ asset('new/images/sales/desktop_poweredby.png') }}">
                </div>
            </div>
            <div class="copyright">
                &copy; Tanyaje Copyrights Reserved 2021
            </div>
        </div>
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-6123635cae3732b3"></script>
        <script src="{{asset('js/app.js?v='. $data['asset_version'] .'')}}"></script>
        <script src="{{asset('new/js/qrcode.min.js')}}"></script>
        @include('sales.common.scripts', [ 'openSticky' => true ])
    </body>
</html>
