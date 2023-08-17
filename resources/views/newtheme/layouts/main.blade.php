<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tanyaje') }}</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="icon" href="{{asset('new/images/favicon.ico')}}" type="image/x-icon"/>
    <link href="{{asset('new/images/favicon.png')}}" rel="apple-touch-icon" sizes="76x76" />
    <link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <link href="{{asset('js/select2.min.css')}}" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{asset('new/css/style.css') . "?lm=221220201120" }}" />
    @yield('styles')

    @yield('meta_for_share')

    @if( config('app.url') == 'https://tanyaje.com.my' || config('app.url') == 'http://tanyaje.com.my' )
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-172026171-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-172026171-1');
    </script>

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '389114995404371');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" src="https://www.facebook.com/tr?id=389114995404371&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->


    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PBNCK2G');</script>
    <!-- End Google Tag Manager -->
    @endif

    @yield('headscript')
</head>

<body>
    <script src="//integrator.swipetospin.com"></script>
    <!-- START wrapper -->
    <div id="wrapper">
        <section class="loader_section">
            <div class="loader-inner">
                <span class="loader loader-quart"></span>
            </div>
        </section>
        @includeif('newtheme.inc.header')

        @yield('content')

        @includeif('newtheme.inc.footer')

        <a href="#" class="back-to-top" style="display: none;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>

    </div>
    <!-- END wrapper -->
    @if (session('saved_cars'))
		    <script>
		    	var session_msg = {!! session('saved_cars') !!};


                // var saved_cars = JSON.stringify(session_msg);
                 localStorage.setItem('addToList', JSON.stringify(session_msg));
                 var token = '{{ csrf_token() }}';

		    </script>
        @endif
    <script src="{{asset('new/js/jquery-3.4.1.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="{{asset('new/js/custom.js')}}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://googleads.g.doubleclick.net/pagead/viewthroughconversion/703300459/?random=1586522956482&amp;cv=9&amp;fst=1586522956482&amp;num=1&amp;guid=ON&amp;resp=GooglemKTybQhCsO&amp;u_h=864&amp;u_w=1536&amp;u_ah=824&amp;u_aw=1536&amp;u_cd=24&amp;u_his=1&amp;u_tz=330&amp;u_java=false&amp;u_nplug=3&amp;u_nmime=4&amp;gtm=2wg432&amp;sendb=1&amp;ig=1&amp;frm=0&amp;url=https%3A%2F%2Fdriveflux.com%2F&amp;tiba=Flux%20-%20A%20Simpler%20Way%20to%20%E2%80%98Own%E2%80%99%20Your%20Perfect%20Car&amp;hn=www.googleadservices.com&amp;async=1&amp;rfmt=3&amp;fmt=4"></script>

    <script src="https://cdn.onesignal.com/sdks/OneSignalPageSDKES6.js?v=150801" async=""></script>
    <script src="{{asset('js/modernizr.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
    <script src="{{asset('js/select2.min.js')}}" type='text/javascript'></script>

    <script>

        var base_url = '{{ url("/") }}';
        var token = '{{ csrf_token() }}';
        $( document ).ready(function() {
            $.ajaxSetup({
                beforeSend: function (xhr)
                {
                    xhr.setRequestHeader( 'X-CSRF-TOKEN', token);

                }
            });
            $( "#overlay" ).click(function() {
                $( ".menu" ).slideToggle( "fast", function() {
                // Animation complete.
                });
            });
            $(".merchant_name").select2()
            jQuery(".merchant_name").on('change',function(){
                jQuery(this).parents("form").submit();
            });
        });

            </script>
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

    @if( config('app.url') == 'https://tanyaje.com.my' || config('app.url') == 'http://tanyaje.com.my' )
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBNCK2G" height="0" width="0" style="display:none;visibility:hidden">
        </iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
    @yield('scripts')

    @stack('scripts')
</body>

</html>
