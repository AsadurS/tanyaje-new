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
    <!-- START content_part-->
    <section id="content_part">
        <!-- banner section start here -->
        <section class="banner-cars">
            <div class="container">
                @if(!empty($merchant->banner_id))
                    @php
                        $banner = DB::table('images')
                                    ->leftJoin('image_categories', 'images.id', '=', 'image_categories.image_id')
                                    ->select('path','images.id','image_type')
                                    ->where('images.id',$merchant->banner_id)
                                    ->where('image_categories.image_type','ACTUAL')
                                    ->first();
                    @endphp
                    @if( isset($banner->path))
                    <img src="/{!! $banner->path !!}" alt="cover">
                    @endif
                @else
                    <!-- <div class="banner-bg-img" style="background-color:{!! $merchant->banner_color !!};"></div> -->
                    <div class="banner-bg-img" style="background-image:url('https://cdn.spincar.com/swipetospin-viewers/tanyaje/za3596/20200906030554.W44WTY8C/thumb-md.jpg')"></div>
                @endif
                <div class="banner-bottom sticky-banner">
                    <div class="row">
                        <div class="col-2 logo-sec">
                            @if(!empty($merchant->logo_id))
                                @php
                                    $logo = DB::table('images')
                                                ->leftJoin('image_categories', 'images.id', '=', 'image_categories.image_id')
                                                ->select('path','images.id','image_type')
                                                ->where('images.id',$merchant->logo_id)
                                                ->where('image_categories.image_type','ACTUAL')
                                                ->first();
                                @endphp
                                @if(isset( $logo->path ))
                                <img src="/{!! $logo->path !!}" alt="cover">
                                @endif
                            @else
                                <img src="{!! asset('images/new/shop-logo.png') !!}" alt="shop-logo">
                            @endif
                        </div>
                        <div class="col-7 car-cont">
                            <div class="bottom-banner-detail">
                                <h3>{!! $merchant->roc_scm_no ? "ROC/SCM No : ".$merchant->roc_scm_no : "" !!}</h3>
                                <h2 style="color: {!! $merchant->title_color !!}">{!! $merchant->merchant_name !!}</h2>
                                <!-- <div class="row car-cont-inner">
                                <img src="{!! asset('new/images/popularIcon02.png') !!}" alt="">
                                <p> {!! $merchant->address !!} <a href="{!! $merchant->direction_google_map_url !!}" target="_blank">Get Directions</a></p>
                                </div> -->
                            </div>
                        </div>
                        <div class="col-3 varify">
                            <div class="varify-inner">
                                @if($merchant->verified)
{{--                                    <img src="{!! asset('images/new/varified.png') !!}" alt="varified">--}}
                                    @php
                                        $dbDate = \Carbon\Carbon::parse($merchant->created_at);
                                        $diffYears = \Carbon\Carbon::now()->diffInYears($dbDate);
                                    @endphp
                                    @if($diffYears > 0)
                                        <strong>{!! $diffYears !!}<sup>YRS</sup></strong>
                                    @endif
                                @endif
                                <div class="card-footer">
                                    <ul>
                                        <li>
                                            <a href="tel:{!! $merchant->merchant_phone_no !!}" class="btn-call">
                                                <img src="{!! asset('new/images/call.png') !!}" alt="call" title="call">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://wa.me/{!! $merchant->merchant_phone_no !!}" class="btn-whatsapp">
                                                <img src="{!! asset('new/images/whatsup.png') !!}" alt="whatsapp" title="whatsapp">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ $merchant->google_map_not_embed }}" class="btn-call">
                                                <img src="{!! asset('new/images/location.png') !!}" alt="direction" title="direction">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="banner-bottom">
                    <div class="row">
                        <div class="col-2 logo-sec">
                            @if(!empty($merchant->logo_id))
                                @php
                                    $logo = DB::table('images')
                                                ->leftJoin('image_categories', 'images.id', '=', 'image_categories.image_id')
                                                ->select('path','images.id','image_type')
                                                ->where('images.id',$merchant->logo_id)
                                                ->where('image_categories.image_type','ACTUAL')
                                                ->first();
                                @endphp
                                <img src="/{!! $logo->path !!}" alt="cover">
                            @else
                                <img src="{!! asset('images/new/shop-logo.png') !!}" alt="shop-logo">
                            @endif
                        </div>
                        <div class="col-7 car-cont">
                            <div class="bottom-banner-detail">
                                <h3>{!! $merchant->roc_scm_no ? "ROC/SCM No : ".$merchant->roc_scm_no : "" !!}</h3>
                                <h2 style="color: {!! $merchant->title_color !!}">{!! $merchant->merchant_name !!}</h2>
                                <div class="row car-cont-inner">
                                    <div class="col-3">
                                        <h4>Location</h4>
                                        <h3>{!! title_case($merchant->location) !!}</h3>
                                    </div>
                                    <div class="col-3">
{{--                                        <h4>Current Cars Available</h4>--}}
                                        <h4>Available Products</h4>
                                        <h3>{!! $cars->total() !!}</h3>
                                    </div>
                                    <div class="col-3">
                                        <h4>Join Date</h4>
                                        <h3>{!! Carbon\Carbon::parse($merchant->created_at)->format('F Y') !!}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3 varify">
                            <div class="varify-inner">
                                @if($merchant->verified)
{{--                                    <img src="{!! asset('images/new/varified.png') !!}" alt="varified">--}}
                                    @php
                                        $dbDate = \Carbon\Carbon::parse($merchant->created_at);
                                        $diffYears = \Carbon\Carbon::now()->diffInYears($dbDate);
                                    @endphp
                                    @if($diffYears > 0)
                                        <strong>{!! $diffYears !!}<sup>YRS</sup></strong>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- banner section end here -->
        <!-- Slider section end here -->
        <section class="slider_section main-top">
            <div class="container">
                <div class="owl-carousel owl-theme ">
                    @foreach($sliders as $slider)
                        <div class="item">
                            <a href="{!! $slider->sliders_url !!}" target="_blank">
                                <img src="/{!! $slider->path !!}">
                            </a>
                        </div>
                    @endforeach
                </>
            </div>
        </section>
        <!-- Slider section end here -->
    </section>
    <!-- END content_part-->
    <!-- END content_part-->
    <!-- search section start here -->

    <section class="car-search">
        <div class="container">
            <div class="car-search-left">
                <div class="left-top-search">
                    <div class="all-search-list">
                        <h4>All {!! $cars->firstItem() !!} - {!! $cars->lastItem() !!} of {!! $cars->total()  !!}</h4>
                    </div>
                    <div class="search-category">
                        <form id="search_form" method="get" action="{!! request()->fullUrl() !!}">
                            <div class="search-text">
                                    <input type="text" name="q" class="textbox" placeholder="Search" value="{!! Request::get('q') !!}">
                                    @if(Request::get('sort'))
                                        <input type="hidden" name="sort" class="textbox" placeholder="Search" value="{!! Request::get('sort') !!}">
                                        <input type="hidden" name="direction" class="textbox" placeholder="Search" value="{!! Request::get('direction') !!}">
                                    @endif
                                    <i class="fa fa-search search_btn" aria-hidden="true"></i>
                            </div>
                            <div class="select-category">
                                <label>Sort by: </label>
                                <select id="sort_cars">
                                    <option data-url="{!! request()->fullUrlWithQuery(['sort' => '']) !!}">Select Option</a></option>
                                    <option data-url="{!! request()->fullUrlWithQuery(['sort' => 'updated_at', 'direction' => 'desc']) !!}" {!! Request::get('sort') == "updated_at" && Request::get('direction') == "desc" ? "selected" : "" !!}>Latest Update</a></option>
                                    <option data-url="{!! request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'asc']) !!}" {!! Request::get('sort') == "price" && Request::get('direction') == "asc" ? "selected" : "" !!}>Price Low to High</a></option>
                                    <option data-url="{!! request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'desc']) !!}" {!! Request::get('sort') == "price" && Request::get('direction') == "desc" ? "selected" : "" !!}>Price High to Low</a></option>
                                    <option data-url="{!! request()->fullUrlWithQuery(['sort' => 'year_make', 'direction' => 'desc']) !!}" {!! Request::get('sort') == "year_make" && Request::get('direction') == "desc" ? "selected" : "" !!}>Year New to Old</a></option>
                                    <option data-url="{!! request()->fullUrlWithQuery(['sort' => 'year_make', 'direction' => 'asc']) !!}" {!! Request::get('sort') == "year_make" && Request::get('direction') == "asc" ? "selected" : "" !!}>Year Old to New</a></option>
                                    <option data-url="{!! request()->fullUrlWithQuery(['sort' => 'mileage', 'direction' => 'asc']) !!}" {!! Request::get('sort') == "mileage" && Request::get('direction') == "asc" ? "selected" : "" !!}>Mileage Low to High</a></option>
                                    <option data-url="{!! request()->fullUrlWithQuery(['sort' => 'mileage', 'direction' => 'desc']) !!}" {!! Request::get('sort') == "mileage" && Request::get('direction') == "desc" ? "selected" : "" !!}>Mileage High to Low</a></option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                @foreach($cars as $car)
                <div class="car-listing">
                    <div class="car-image">
                        <a href="{!! route('car_details',$car->getCarUrl()) !!}" target="_blank"><img src="{{ 'https://manager.spincar.com/web-preview/walkaround-thumb/' . $car->sp_account . '/' . strtolower($car->vim) . '/md'  }}" alt="" ></a>
                        {{-- <img src="../new/images/pic-quantity.png" class="pic"> --}}
                    </div>
                    <div class="car-likes mob">
                        <div class="likes-top">
{{--                            <div class="like-heart addToList" id="addButton{{$car->car_id}}" data-id="{{$car->car_id}}">--}}
{{--                                <i class="far fa-heart"></i>--}}
{{--                            </div>--}}
{{--                            <div class="like-heart removeToList hide" id="removeButton{{$car->car_id}}" data-id="{{$car->car_id}}">--}}
{{--                                <i class="fa fa-heart red"></i>--}}
{{--                            </div>--}}
                            <div class="date-time">
                                <p>{!! Carbon\Carbon::parse($car->created_at)->format('d M Y, H:i') !!}</p>
                            </div>
                        </div>
                   </div>
                    <div class="car-detail">
                        <div class="used">
                            <img src="{!! asset('new/images/label.png') !!}"><span>@if($car->status==1) New @elseif($car->status==2) Used @elseif($car->status==3) Recond @endif</span>
                        </div>
                        <div class="list-detail">
                            <h3><a href="{!! route('car_details',$car->getCarUrl()) !!}" target="_blank"><span class="date">{!! $car->year_make !!}</span>{{ Illuminate\Support\Str::limit($car->title, 40, '...') }}</a></h3>
                            <h4>RM {{ number_format($car->price, 2, '.', ',') }}</h4>
                            <span class="vin" style="display: none;">{{ $car->vim }}</span>
                            <ul>
                                @if(isset($car->mileage) && $car->mileage !="")
                                <li><img src="{!! asset('new/images/popularIcon01.png') !!}" alt="">{{ !empty($car->mileage)? convertMileage($car->mileage) ." km": "Unknown" }}</li>
                                @endif

                                @if(isset($car->engine_capacity) && $car->engine_capacity !="")
                                <li><img src="{!! asset('new/images/engine.png') !!}" alt="engine">{!! !empty($car->engine_capacity)? $car->engine_capacity : "Unknown" !!}</li>
                                @endif

                                @if(isset($car->color) && $car->color !="")
                                <li><img src="{{asset('images/new/popularIcon03.png')}}" alt="">{{ isset($car->color)? convertColor($car->color) : "Unknown" }}</li>
                                @endif

                                @if(isset($car->fuel_type) && $car->fuel_type !="")
                                <li><img src="{!! asset('new/images/popularIcon04.png') !!}" alt="">{{ !empty($car->fuel_type)? ucwords($car->fuel_type): "Unknown" }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="car-likes desk">
                        <div class="likes-top">
{{--                            <div class="like-heart addToList" id="addButton{{$car->car_id}}" data-id="{{$car->car_id}}">--}}
{{--                                <i class="far fa-heart"></i>--}}
{{--                            </div>--}}
{{--                            <div class="like-heart removeToList hide" id="removeButton{{$car->car_id}}" data-id="{{$car->car_id}}">--}}
{{--                                <i class="fa fa-heart red"></i>--}}
{{--                            </div>--}}
                            <div class="date-time" style="margin-top:60px;">
                                <p>{!! Carbon\Carbon::parse($car->created_at)->format('d M Y, H:i') !!}</p>
                            </div>
                        </div>
                        <div class="likes-bottom" >
                        <div class="AddtobOX" style="padding-bottom: 20px;">
{{--                            <div class="form-group">--}}
{{--                                @if(Auth::guard('customer')->user())--}}
{{--                                    <input type="checkbox" id="compare{!! $car->car_id !!}" class="compare_checkbox" value="{!! $car->car_id !!}" {!! in_array($car->car_id, $cookies) ? 'checked' : '' !!}>--}}
{{--                                @else--}}
{{--                                    <input type="checkbox" id="compare{!! $car->car_id !!}" class="compare_checkbox" value="{!! $car->car_id !!}" {!! in_array($car->car_id, $cookies) ? 'checked' : '' !!}>--}}
{{--                                @endif--}}
{{--                                <label for="compare{!! $car->car_id !!}">Add to compare</label>--}}
{{--                            </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="merchant_pagination">
                    {{ $cars->onEachSide(1)->appends(request()->except(['page']))->links() }}
                </div>
            </div>
            <div class="car-search-right">
                <div class="search-right-btn-top">
                    <div class="card-footer">
                        <div class="btn-handler">
                            <a href="https://wa.me/{!! $merchant->merchant_phone_no !!}" class="btn-whatsapp">
                                <img src="{!! asset('new/images/whatsapp.svg') !!}" alt="whatsapp" title="whatsapp">
                                whatsapp now
                            </a>
                        </div>
                        <div class="btn-handler">
                            <a href="tel:{!! $merchant->merchant_phone_no !!}" class="btn-call">
                                call now
                            </a>
                        </div>
                    </div>
                    <div class="enquiry-form">
                        <span>or</span>
                        <form id="merchant_inquiry_form" action="{!! route('site.merchant.inquiry',array($merchant->merchant_id)) !!}" method="post">
                            <h3>Enquiry Now</h3>
                            <p class="submit_message"></p>
                            <input type="text" name="name" placeholder="Your name" required>
                            <input type="email" name="email" placeholder="Your email" required email>
                            <input type="text" name="phone" placeholder="Your phone number" required>
                            <textarea name="message" placeholder="Your message" tabindex="5"></textarea>
                            <input type="hidden" name="branch_email" value="{{ $merchant->branch_email }}">
                            <input type="submit" value="Send Message" class="submit">
                        </form>
                    </div>
                </div>
                <div class="right-map-bottom">
                    <h3>About this seller</h3>
                    <iframe src="{{ $merchant->google_map_url }}" width="100%" height="450" frameborder="0" allowfullscreen="" aria-hidden="false" tabindex="0">
                    </iframe>
                    <p>{!! $merchant->description !!}</p>
                    <ul>
                        @if($merchant->opening_hours)
                            <li><strong>Opening Hours</strong></li>
                            <pre style="font-family: 'Red Hat Display', sans-serif; font-size: 16px">{!! $merchant->opening_hours !!}</pre>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- search sectoion end here -->
@endsection

@push("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="{{asset('js/select2.min.js')}}" type='text/javascript'></script>
    <script type="text/javascript" src="{!! asset('new/js/owl.carousel.min.js') !!}"></script>
    <script>
        jQuery(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            jQuery(window).on("scroll",function()
            {   var fromTop = $(".car-search").scrollTop();
                if ($(this).scrollTop() > fromTop && !$('sticky-banner').hasClass('stick') ) {
                    $('.sticky-banner').addClass('stick');
                } else if ( $(this).scrollTop() <= fromTop ) {
                    $('.sticky-banner').removeClass('stick');
                }
            });

            jQuery(".car_brand").on("change",function(){
                jQuery.post('/getModel',{make_id: $(this).val()},function(response){
                    response = JSON.parse(response);
                    html = '<option value="">Models</option>';
                    jQuery.each(response.data,function(i,val)
                    {
                        html += '<option value="'+val.model_id+'">'+val.model_name+'</option>"';
                    });
                    jQuery(".car_model").html(html);
                });
            });

            jQuery(".search_btn").on("click",function(){
                jQuery("#search_form").submit();
            })

            jQuery("#merchant_inquiry_form").on("submit",function(e){
                e.preventDefault();
                var _this = $(this);
                $.post(_this.attr("action"), _this.serialize(),function(response){
                    jQuery(".submit_message").addClass(response.status).html(response.message);
                    if(response.status == "success")
                    {
                        jQuery(_this)[0].reset();
                    }
                })
            })

            $("#sort_cars").on("change",function(){
		        window.location = jQuery(this).find('option:selected').data('url');
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
            //select2.data('select2').$selection.css('height', '40px');

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

                        //addToMyList
            var proCount = 0;
            var tempProId = [];
            if( localStorage.getItem('addToList') != null ){
                tempProId = JSON.parse(localStorage.getItem('addToList'));
                $.each(tempProId , function(index, val) {
                    $('.car-likes').each(function(item){
                        $(this).find('#removeButton'+val).addClass('show').removeClass('hide');
                        $(this).find('#addButton'+val).addClass('hide').removeClass('show');
                    });
                });
            }
            $('.addToList').click(function(e){
                var proId = e.target.parentElement.dataset.id;
                proCount = $('#addButton'+proId).length;
                for (var i = 1;i <= proCount ;i++) {
                    tempProId.push(proId);
                }
                localStorage.setItem('addToList', JSON.stringify(tempProId));
                setTimeout(function(){
                    $('#addButton'+proId).addClass('hide').removeClass('show');
                    $('#removeButton'+proId).addClass('show').removeClass('hide');
                }, 100);
            });

            //removeToList
            $('.removeToList').click(function(e){
                var proId = e.target.parentElement.dataset.id;
                $('#addButton'+proId).addClass('show').removeClass('hide');
                $('#removeButton'+proId).addClass('hide').removeClass('show');
                proCount = $('#removeButton'+proId).length;
                for (var i = 1;i <= proCount;i++) {
                var index = tempProId.indexOf(proId);
                if (index > -1) {
                    var index = tempProId.splice(index, 1);
                }
                }
                localStorage.setItem('addToList', JSON.stringify(tempProId));
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
