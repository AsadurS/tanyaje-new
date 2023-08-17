@extends('newtheme.layouts.main')
@section('styles')
<link rel="stylesheet" href="{{asset('new/css/popup.css') }}?lm=120920201428" />
<link href="{{asset('js/select2.min.css')}}" rel='stylesheet' type='text/css'>
<link href="{{asset('new/css/owl.carousel.min.css')}}" rel='stylesheet' type='text/css'>
<link href="{{asset('new/css/owl.theme.default.min.css')}}" rel='stylesheet' type='text/css'>
@endsection
@section('meta_for_share')
    <meta property="og:title" content="{{ trans('labels.website_title') }}">
    <meta property="og:url" content="https://tanyaje.com.my/">
    <meta property="og:image" content="{{asset('new/images/logo4.png')}}">
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="400" />
    <meta property="og:image:type" content="image/png">
    <meta property="og:description" content="Tanya-Je translated as “Just Ask” is the first most advanced online digital automotive classified in South East Asia.">
@endsection
@section('content')

    @include('newtheme.modules.home.partials.banner')

    <!-- START content_part-->
    <section id="content_part">


        @include('newtheme.modules.home.partials.clients')

    </section>
    <!-- END content_part-->

    <!-- Modal HTML embedded directly into document -->

@endsection

@push("scripts")
    <script type="text/javascript" src="{!! asset('new/js/owl.carousel.min.js') !!}"></script>
    <script>
        // Smooth scrolling when clicking an anchor link
        $(document).on('click', 'a[href^="#"]', function (event) {
            event.preventDefault();

            $('html, body').animate({
                scrollTop: $($.attr(this, 'href')).offset().top
            }, 1500);
        });

        $('a[id^="condition"]').click(function(){
            var conditionId = $(this).data('value');
            $('.menuTab a').removeClass("active");
            $(this).addClass("active");
            $('input[name="condition"]').val(conditionId);

            //update list by merchant
            jQuery.post('/getMerchantByCondition',{
                condition_id: conditionId
            },function(response){
                response = JSON.parse(response);
                html = '<option value="">Listed By</option>';
                jQuery.each(response.data,function(i,val)
                {
                    html += '<option value="'+val.merchant_id+'">'+val.merchant_name+'</option>';
                });
                jQuery(".merchant_name").html(html);
            });
        });

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="{{asset('js/select2.min.js')}}" type='text/javascript'></script>
    <script type="text/javascript" src="{!! asset('new/js/owl.carousel.min.js') !!}"></script>
    <script>

       

        jQuery(document).ready(function(){

            $(document).on("click",".slider_section .item a",function(){
                url = jQuery(this).attr('href');
                gtag('event', 'Slide Show', {
                    'event_category': 'Event',
                    'event_label': 'Slide Show Click',
                    'value': url
                });
            });

            $('.owl-carousel').owlCarousel({
                loop:true,
                margin:10,
                nav:true,
                autoplay:true,
                autoplayTimeout:3000,
                autoplayHoverPause:true,
                responsive:{
                    0:{
                        items:1
                    }
                }
            })
            // Initialize select2
            var select2 = $(".merchant_name").select2();
            $('#search-title').select2({
                placeholder: "What you looking for? Type your keywords here",
                minimumInputLength: 3,
                tags: [],
                ajax: {
                    url: '/titlecar',
                    dataType: 'json',
                    type: "GET",
                    delay: 250,
                    data: function (term) {
                        return {
                            param: term
                        };
                    }
                }
            });

            var list = new cookieList("car_compare_list");
            var is_user = "{!! Auth::guard('customer')->check() !!}";
            jQuery(".compare_checkbox").on('click',function(){
                if(jQuery(this).prop('checked') == true) {
                    if(list.length() > 3)
                    {
                        alert("You can't campare more then 4 cars");
                        return false;
                    }
                    else
                    {
                        if(is_user)
                        {
                            jQuery.post("{!! route('carcompare.add') !!}",{car_id: $(this).val()},function(response){
                                alert(response);
                            });
                        }
                        else
                        {
                            list.add($(this).val());
                        }

                    }
                }
                else{
                        if(is_user)
                        {
                            jQuery.post("{!! route('carcompare.remove') !!}",{car_id: $(this).val()},function(response){
                                alert(response);
                            });
                        }
                        else
                        {
                            list.remove($(this).val());
                        }
                }
            });
        });

        var cookieList = function(cookieName) {
            var cookie = Cookies.get(cookieName);
            var items = cookie ? cookie.split(/,/) : new Array();
            return {
                "add": function(val) {
                    //Add to the items.
                    items.push(val);
                    Cookies.set(cookieName, items.join(','));
                },
                "remove": function (val) {
                    indx = items.indexOf(val);
                    if(indx!=-1) items.splice(indx, 1);
                    Cookies.set(cookieName, items.join(','));
                },
                "length": function () {
                    return items.length;
                    },
                "items": function() {
                    //Get all the items.
                    return items;
                }
            }
        }
    </script>

@endpush
