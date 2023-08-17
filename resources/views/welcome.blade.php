<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    </head>
    <style>
        body {
            background:#ddd;
        }
        #app {
            background: #fff;
            max-width: 320px;
            position: relative;
            height: 540px;
            margin: 0 auto;
        }
    </style>
    <body>
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
            <div class="content">
                <div id="app">
                    <sales-header 
                        :image="'{{asset('new/images/sales/header-img.jpg')}}'"
                    ></sales-header>
                    <sales-landing 
                        :image="'{{asset('new/images/sales/landing.jpg')}}'"
                    ></sales-landing> 
                    <sales-footer 
                        :template="'1'"
                        :image_callnow="'{{asset('new/images/sales/call-now.svg')}}'"
                        :image1="'{{asset('new/images/sales/footer-btngroup1.jpg')}}'"
                        :image2="'{{asset('new/images/sales/footer-btngroup2.jpg')}}'"
                    ></sales-footer>
                </div>
            </div>
        </div>
        
        <script src="{{mix('js/app.js')}}"></script>
    </body>
</html>
