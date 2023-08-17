@extends('newtheme.layouts.main')
@section('meta_for_share')
@endsection

@section('headscript')
    <link rel="stylesheet" href="{{asset('new/css/jquery.mmenu.css')}}">
    <link rel="stylesheet" href="{{asset('new/css/animate.css')}}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('new/css/style.css'). "?lm=221220201120" }}" />
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('home') !!}">Home</a></li>
                <li class="breadcrumb-item"><a href="{!! route('carfilters') !!}">Car</a></li>
                <li class="breadcrumb-item active" aria-current="page">Compare</li>
            </ol>
        </div>
    </nav>
    <section class="compare-section">
        <div class="compare-content">
            {{-- Container Summary --}}
            <div class="container px-2" style="min-width: 800px">
                <div class="row">
                    <div class="col">
                        <div class="heading">
                            <h2 data-aos="fade-up" data-aos-once="ture">Compare Cars</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="car-count">
                            Cars({!! $cars->count() !!})
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2 py-2 align-self-center">
                        <h6>Summary</h6>
                    </div>
                    <div class="col-10 py-2">
                        <div class="row">
                            @foreach($cars as $car)
                                <div class="col-3">
                                    <div class="compare-car-img">
                                        <a  role="button" class="compare-car-close" data-car_id ={!! $car->car_id !!}><i class="fa fa-times"></i></a>
                                        <div class="img-hover-zoom">
                                            <a href="{!! route('car_details',$car->getCarUrl()) !!}"><img src="{{ 'https://manager.spincar.com/web-preview/walkaround-thumb/' . $car->sp_account. '/' . strtolower($car->vim) . '/md'  }}" alt="" ></a>
                                        </div>
                                    </div>
                                    <div class="compare-car-title">
                                        <a href="{!! route('car_details',$car->getCarUrl()) !!}">
                                            <div class="title-container">
                                            <div class="title-car"><h3 style="font-size: 14px !important;">{{$car->title}}</h3></div>
                                            </div>
                                        </a>
                                    </div>
                                    <ul class="compare-car-action">
                                        @if($car->car_merchant)
                                            <li><a href="https://wa.me/{{ str_replace(' ', '', $car->car_merchant->merchant_phone_no) }}" target="_blank" class="btn btn22"><i class="fab fa-whatsapp"></i> WHATSAPP</a></li>
                                            <li><a href="tel:+{{ str_replace(' ', '', $car->car_merchant->merchant_phone_no) }}" target="_blank" class="btn btn33">CALL NOW</a></li>
                                        @endif
                                    </ul>
                                </div>
                            @endforeach
                            @for($i = 4;$i>$cars->count();$i--)
                                <div class="col-3">
                                    <div class="compare-car-search text-center">
                                        <div class="compare-car-search-img">
                                            <img src="{!! asset('images/new/car.svg') !!}">
                                        </div>
                                        <a href="{!! route("carfilters") !!}" role="button" class="add_car_link">+ Add a car from<a>
                                        <a href="{!! route("carfilters") !!}" class="btn btn33">New Search</a>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            {{-- Container Overview --}}
            <div class="container px-2" style="min-width: 800px">
                <div class="row">
                    <div class="col-2">
                        <h6 class="compare-car-accordion">
                            <img src="{!! asset('images/new/minus.svg') !!}" class="cca_img"><span>Overview</span>
                        </h6>
                    </div>
                    <div class="col-10 compare-car-overview-inner">
                        <div class="row">
                            <div class="overview_pt divider"></div>
                            <div class="col-12">
                                <h4>
                                    Price
                                </h4>
                            </div>
                            @foreach($cars as $car)
                                <div class="col-3">
                                    <p class="sp_price">RM {{ number_format($car->price, 2, '.', ',') }}</p>
                                </div>
                            @endforeach
                            @for($i = 4;$i>$cars->count();$i--)
                                <div class="col-3"></div>
                            @endfor
                            <div class="overview_pt"></div>
                        </div>

                        <div class="row">
                            <div class="overview_pt divider"></div>
                            <div class="col-12">
                                <h4>
                                    Condition
                                </h4>
                            </div>
                            @foreach($cars as $car)
                                <div class="col-3">
                                    <p>{!! $car->status !!}</p>
                                </div>
                            @endforeach
                            @for($i = 4;$i>$cars->count();$i--)
                                <div class="col-3"></div>
                            @endfor
                            <div class="overview_pt"></div>
                        </div>

                        <div class="row">
                            <div class="overview_pt divider"></div>
                            <div class="col-12">
                                <h4>
                                    Brand
                                </h4>
                            </div>
                            @foreach($cars as $car)
                                <div class="col-3">
                                    <p>{!! $car->brand->make_name !!}</p>
                                </div>
                            @endforeach
                            @for($i = 4;$i>$cars->count();$i--)
                                <div class="col-3"></div>
                            @endfor
                            <div class="overview_pt"></div>
                        </div>

                        <div class="row">
                            <div class="overview_pt divider"></div>
                            <div class="col-12">
                                <h4>
                                    Model
                                </h4>
                            </div>
                            @foreach($cars as $car)
                                <div class="col-3">
                                    <p>{!! $car->model->model_name !!}</p>
                                </div>
                            @endforeach
                            @for($i = 4;$i>$cars->count();$i--)
                                <div class="col-3"></div>
                            @endfor
                            <div class="overview_pt"></div>
                        </div>

                        <div class="row">
                            <div class="overview_pt divider"></div>
                            <div class="col-12">
                                <h4>
                                    Mileage
                                </h4>
                            </div>
                            @foreach($cars as $car)
                                <div class="col-3">
                                    <p>{!! $car->mileage !!}</p>
                                </div>
                            @endforeach
                            @for($i = 4;$i>$cars->count();$i--)
                                <div class="col-3"></div>
                            @endfor
                            <div class="overview_pt"></div>
                        </div>

                        <div class="row">
                            <div class="overview_pt divider"></div>
                            <div class="col-12">
                                <h4>
                                    Car Type
                                </h4>
                            </div>
                            @foreach($cars as $car)
                                <div class="col-3">
                                    <p>{!! title_case($car->types->type_name) !!}</p>
                                </div>
                            @endforeach
                            @for($i = 4;$i>$cars->count();$i--)
                                <div class="col-3"></div>
                            @endfor
                            <div class="overview_pt"></div>
                        </div>

                        <div class="row">
                            <div class="overview_pt divider"></div>
                            <div class="col-12">
                                <h4>
                                    Fuel Type
                                </h4>
                            </div>
                            @foreach($cars as $car)
                                <div class="col-3">
                                    <p>{!! title_case($car->fuel_type) !!}</p>
                                </div>
                            @endforeach
                            @for($i = 4;$i>$cars->count();$i--)
                                <div class="col-3"></div>
                            @endfor
                            <div class="overview_pt"></div>
                        </div>

                        <div class="row">
                            <div class="overview_pt divider"></div>
                            <div class="col-12">
                                <h4>
                                    Tramission
                                </h4>
                            </div>
                            @foreach($cars as $car)
                                <div class="col-3">
                                    <p>{!! title_case($car->transmission) !!}</p>
                                </div>
                            @endforeach
                            @for($i = 4;$i>$cars->count();$i--)
                                <div class="col-3"></div>
                            @endfor
                            <div class="overview_pt"></div>
                        </div>

                        <div class="row">
                            <div class="overview_pt divider"></div>
                            <div class="col-12">
                                <h4>
                                    Engine Capacity
                                </h4>
                            </div>
                            @foreach($cars as $car)
                                <div class="col-3">
                                    <p>{!! $car->engine_capacity !!}</p>
                                </div>
                            @endforeach
                            @for($i = 4;$i>$cars->count();$i--)
                                <div class="col-3"></div>
                            @endfor
                            <div class="overview_pt"></div>
                        </div>

                        <div class="row">
                            <div class="overview_pt divider"></div>
                            <div class="col-12">
                                <h4>
                                    Seats
                                </h4>
                            </div>
                            @foreach($cars as $car)
                                <div class="col-3">
                                    <p>{!! $car->seats !!}</p>
                                </div>
                            @endforeach
                            @for($i = 4;$i>$cars->count();$i--)
                                <div class="col-3"></div>
                            @endfor
                            <div class="overview_pt"></div>
                        </div>

                        <div class="row">
                            <div class="overview_pt divider"></div>
                            <div class="col-12">
                                <h4>
                                    Color
                                </h4>
                            </div>
                            @foreach($cars as $car)
                                <div class="col-3">
                                    <p>{!! title_case($car->color) !!}</p>
                                </div>
                            @endforeach
                            @for($i = 4;$i>$cars->count();$i--)
                                <div class="col-3"></div>
                            @endfor
                            <div class="overview_pt"></div>
                        </div>
                        <div class="row">
                            <div class="overview_pt divider"></div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Container Features --}}
            <div class="container pt-2 px-2" style="min-width: 800px">
                <div class="row">
                    <div class="col-2">
                        <h6>Features</h6>
                    </div>
                    <div class="col-10">
                        <div class="row compare-car-features-inner">
                            @php
                                $count = 0;
                            @endphp
                            @foreach($cars as $car)
                                @php
                                    $features[$car->car_id] = explode(',',$car->features);
                                    $count = count($features[$car->car_id]) > $count ? count($features[$car->car_id]) : $count;
                                @endphp
                            @endforeach
                            @for($i=0;$i < $count;$i++)
                            @foreach($features as $f)
                                <div class="col-3">
                                    <p>{!! isset($f[$i]) ? $f[$i] : "" !!}</p>
                                </div>
                            @endforeach
                            @for($j = 4;$j>$cars->count();$j--)
                                <div class="col-3"></div>
                            @endfor
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5f17ff7371b9f206"></script>
    <script>
        $(document).ready(function(){


            function scroll () {
              if ($(window).scrollTop() >= 150) {
                $('.header_site').addClass('sticky');
                } else {
                    $('.header_site').removeClass('sticky');
                }


            }

            document.onscroll = scroll;

            $(".compare-car-accordion").click(function(){
                var src = ($(".cca_img").attr("src") === "{!! asset('images/new/minus.svg') !!}")
            ? "{!! asset('images/new/plus.svg') !!}"
            : "{!! asset('images/new/minus.svg') !!}";
                $(".compare-car-overview-inner").slideToggle();
                $(".cca_img").attr('src',src);
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var list = new cookieList("car_compare_list");
            var is_user = "{!! Auth::guard('customer')->check() !!}";
            $(".compare-car-close").on("click",function(){
                var car_id = $(this).data('car_id').toString();
                if(is_user)
                {
                    jQuery.post("{!! route('carcompare.remove') !!}",{car_id: car_id},function(){
                    });
                }
                else
                {
                    list.remove(car_id);
                }
                window.location.reload();
            });

        });

        //This is not production quality, its just demo code.
        var cookieList = function(cookieName) {
        //When the cookie is saved the items will be a comma seperated string
        //So we will split the cookie by comma to get the original array
        var cookie = Cookies.get(cookieName);
        //Load the items or a new array if null.
        var items = cookie ? cookie.split(/,/) : new Array();

        //Return a object that we can use to access the array.
        //while hiding direct access to the declared items array
        //this is called closures see http://www.jibbering.com/faq/faq_notes/closures.html
        return {
            "add": function(val) {
                //Add to the items.
                items.push(val);
                //Save the items to a cookie.
                //EDIT: Modified from linked answer by Nick see
                //      http://stackoverflow.com/questions/3387251/how-to-store-array-in-jquery-cookie
                Cookies.set(cookieName, items.join(','));
            },
            "remove": function (val) {
                //EDIT: Thx to Assef and luke for remove.
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
