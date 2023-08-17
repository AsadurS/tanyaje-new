<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->

    <!-- Fonts -->
    <link href="{{asset('css/2.191a0a66.chunk.css')}}" rel="stylesheet">
    <link href="{{asset('css/main.611dbe96.chunk.css?lm=170720201739')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />

    @yield('meta_for_share')

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://googleads.g.doubleclick.net/pagead/viewthroughconversion/703300459/?random=1586522956482&amp;cv=9&amp;fst=1586522956482&amp;num=1&amp;guid=ON&amp;resp=GooglemKTybQhCsO&amp;u_h=864&amp;u_w=1536&amp;u_ah=824&amp;u_aw=1536&amp;u_cd=24&amp;u_his=1&amp;u_tz=330&amp;u_java=false&amp;u_nplug=3&amp;u_nmime=4&amp;gtm=2wg432&amp;sendb=1&amp;ig=1&amp;frm=0&amp;url=https%3A%2F%2Fdriveflux.com%2F&amp;tiba=Flux%20-%20A%20Simpler%20Way%20to%20%E2%80%98Own%E2%80%99%20Your%20Perfect%20Car&amp;hn=www.googleadservices.com&amp;async=1&amp;rfmt=3&amp;fmt=4"></script>
    
    <script src="https://cdn.onesignal.com/sdks/OneSignalPageSDKES6.js?v=150801" async=""></script>
    <script src="{{asset('js/modernizr.js')}}"></script>
    <style type="text/css">
        html {
            scroll-behavior: smooth;
        }
        iframe#_hjRemoteVarsFrame {
            display: none !important;
            width: 1px !important;
            height: 1px !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }
        .flex-direction-nav li {
            position: absolute;
            height: 100%;
            width: 40px;
            top: 10%;
        }

        .flex-direction-nav li:first-child {
            left: 0;
        }

        .flex-direction-nav li:last-child {
            right: 0;
        }

        .flex-direction-nav li a {
          display: none;
          height: 100%;
          width: 100%;

          /* image replacement */
          overflow: hidden;
          text-indent: 100%;
          white-space: nowrap;

          transition: background-color 0.2s;
        }

        .flex-direction-nav li a::before, .flex-direction-nav li a::after {
          /* left and right arrows in css only */
          content: '';
          position: absolute;
          left: 50%;
          top: 50%;
          width: 2px;
          height: 13px;
          background-color: white;
        }

        .flex-direction-nav li a::before {
          transform: translateY(-35px) rotate(45deg);
        }

        .flex-direction-nav li a::after {
          transform: translateY(-27px) rotate(-45deg);
        }

        .flex-direction-nav li:last-child a::before {
          transform: translateY(-35px) rotate(-45deg);
        }

        .flex-direction-nav li:last-child a::after {
          transform: translateY(-27px) rotate(45deg);
        }
        ul {
          list-style-type: none;
          margin: 0;
          padding: 0;
        }

    </style>
    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->

    @if( env('APP_URL') == 'http://tanyaje.com.my')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-172026171-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-172026171-1');
    </script>
    @endif
</head>

<body>
    <script src="//integrator.swipetospin.com"></script>
    <!-- <div id="app"> -->
    <div>
        <div>
            <div class="Toastify"></div>
            <div class="page landing-page">
                @includeif('inc.header')

                @yield('content')

                @includeif('inc.footer')
            </div>
        </div>
    </div>
{{--    <script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=fd6e4170-9459-4d43-97c5-224b556aa024" async=""></script>--}}
{{--    <script id="stripe-js" src="https://js.stripe.com/v3/" async=""></script>--}}
    <script>
        ! function(f) {
            function e(e) {
                for (var r, t, n = e[0], o = e[1], u = e[2], l = 0, a = []; l < n.length; l++) t = n[l], Object.prototype.hasOwnProperty.call(i, t) && i[t] && a.push(i[t][0]), i[t] = 0;
                for (r in o) Object.prototype.hasOwnProperty.call(o, r) && (f[r] = o[r]);
                for (s && s(e); a.length;) a.shift()();
                return p.push.apply(p, u || []), c()
            }

            function c() {
                for (var e, r = 0; r < p.length; r++) {
                    for (var t = p[r], n = !0, o = 1; o < t.length; o++) {
                        var u = t[o];
                        0 !== i[u] && (n = !1)
                    }
                    n && (p.splice(r--, 1), e = l(l.s = t[0]))
                }
                return e
            }
            var t = {},
                i = {
                    1: 0
                },
                p = [];

            function l(e) {
                if (t[e]) return t[e].exports;
                var r = t[e] = {
                    i: e,
                    l: !1,
                    exports: {}
                };
                return f[e].call(r.exports, r, r.exports, l), r.l = !0, r.exports
            }
            l.m = f, l.c = t, l.d = function(e, r, t) {
                l.o(e, r) || Object.defineProperty(e, r, {
                    enumerable: !0,
                    get: t
                })
            }, l.r = function(e) {
                "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
                    value: "Module"
                }), Object.defineProperty(e, "__esModule", {
                    value: !0
                })
            }, l.t = function(r, e) {
                if (1 & e && (r = l(r)), 8 & e) return r;
                if (4 & e && "object" == typeof r && r && r.__esModule) return r;
                var t = Object.create(null);
                if (l.r(t), Object.defineProperty(t, "default", {
                        enumerable: !0,
                        value: r
                    }), 2 & e && "string" != typeof r)
                    for (var n in r) l.d(t, n, function(e) {
                        return r[e]
                    }.bind(null, n));
                return t
            }, l.n = function(e) {
                var r = e && e.__esModule ? function() {
                    return e.default
                } : function() {
                    return e
                };
                return l.d(r, "a", r), r
            }, l.o = function(e, r) {
                return Object.prototype.hasOwnProperty.call(e, r)
            }, l.p = "/";
            var r = this["webpackJsonp@flux/legacy-react"] = this["webpackJsonp@flux/legacy-react"] || [],
                n = r.push.bind(r);
            r.push = e, r = r.slice();
            for (var o = 0; o < r.length; o++) e(r[o]);
            var s = n;
            c()
        }([])
    </script>
    
    <script src="{{asset('js/2.8ed5f0df.chunk.js')}}"></script>
    <script src="{{asset('js/main.ab087fe6.chunk.js')}}"></script>
    <script src="{{asset('js/masonry.pkgd.min.js')}}"></script>
    <script src="{{asset('js/jquery.flexslider-min.js')}}"></script>
    <script>
    $('.header_link').click(function(){
        var $href = $(this).attr('href');
        var $anchor = $('#'+$href).offset();
        window.scrollTo($anchor.left,$anchor.top);
        return false;
    });
    $('.cd-testimonials-wrapper').flexslider({
        //declare the slider items
        selector: ".cd-testimonials > li",
        animation: "slide",
        //do not add navigation for paging control of each slide
        controlNav: false,
        slideshow: false,
        //Allow height of the slider to animate smoothly in horizontal mode
        smoothHeight: true,
        start: function(){
            $('.cd-testimonials').children('li').css({
                'opacity': 1,
                'position': 'relative'
            });
        }
    });
    $('.cd-testimonials-all-wrapper').children('ul').masonry({
        itemSelector: '.cd-testimonials-item'
    });
    
    $("ul.flex-direction-nav > li:first").mouseover(function(){
        $("ul.flex-direction-nav > li > a:first").text('');
        $( "ul.flex-direction-nav > li:first > a" ).show();
    });
    $("ul.flex-direction-nav > li:last").mouseover(function(){
        $("ul.flex-direction-nav > li > a:last").text('');
        $( "ul.flex-direction-nav > li:last > a" ).show();
    });
    $(".flex-direction-nav").mouseleave(function(){
        $( "ul.flex-direction-nav > li > a" ).hide();
    }); 
    </script>
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    @stack('scripts')
</body>

</html>