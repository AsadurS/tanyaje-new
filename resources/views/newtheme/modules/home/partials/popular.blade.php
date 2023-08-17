<!-- START popularsArea -->
<section class="popularsArea">
    <div class="container">
        <div class="heading">
            <h2>See What’s Popular</h2>
        </div>

        <div class="itemBox disflexArea">
        @foreach($car as $ca)
            <div class="item">
                <div class="image">
{{--                    <img class="Bgimage" src="{{ 'https://manager.spincar.com/web-preview/walkaround-thumb/tanyaje/' . strtolower($ca->vim) . '/md' }}" alt="">--}}
                    <a href="{{ route("car_details",$ca->getCarUrl()) }}">
                        <img src="{{ 'https://manager.spincar.com/web-preview/walkaround-thumb/' . $ca->sp_account . '/' . strtolower($ca->vim) . '/md' }}" alt="">
                    </a>
                </div>
                <div class="sub">
                <span class="addToList" id="addButton{{$ca->car_id}}" data-id="{{$ca->car_id}}">
                        <i class="far fa-heart"></i>
                    </span>
                    <span class="removeToList hide" id="removeButton{{$ca->car_id}}" data-id="{{$ca->car_id}}">
                        <i class="fa fa-heart red"></i>
                    </span>
                    <a href="{{ route("car_details",$ca->getCarUrl()) }}">
                        <div class="title-container">
                            <div class="year-car">{{$ca->year_make}}</div><div class="title-car"><h3>{{ $ca->title }}</h3></div>
                        </div>
                    </a>
                    <p>Listed by: <a class="listed-by" href="{{ $ca->merchant_slug ? route('site.merchant',$ca->merchant_slug): '#' }}">{{$ca->merchant_name}}</a></p>
                    <h4>RM {{ number_format($ca->price, 2, '.', ',') }}</h4>
                    <span class="vin" style="display: none;">{{ $ca->vim }}</span>
                    <ul>
                        @if(isset($ca->mileage) && $ca->mileage !="")
                        <li><img src="{{asset('new/images/popularIcon01.png')}}" alt="">{{ isset($ca->mileage)? convertMileage($ca->mileage) ." km": "Unknown" }}</li>
                        @endif

                        @if(isset($ca->city->city_name) && $ca->city->city_name !="")
                        <li><img src="{{asset('new/images/popularIcon02.png')}}" alt="">{{ $ca->city ? ucwords( $ca->city->city_name) : '' }}, {{ $ca->state? ucwords($ca->state->state_name): '' }}</li>
                        @endif

                        @if(isset($ca->color) && $ca->color !="")
                        <li><img src="{{asset('new/images/popularIcon03.png')}}" alt="">{{ isset($ca->color)? convertColor($ca->color) : "Unknown" }}</li>
                        @endif

                        @if(isset($ca->fuel_type) && $ca->fuel_type !="")
                        <li><img src="{{asset('new/images/popularIcon04.png')}}" alt="">{{ isset($ca->fuel_type)? ucwords($ca->fuel_type): "Unknown" }}</li>
                        @endif
                    </ul>
                    <div class="AddtobOX" style="padding-bottom: 20px;">
                        <div class="form-group">
                             @if(Auth::guard('customer')->user())
                                 <input type="checkbox" id="compare{!! $ca->car_id !!}" class="compare_checkbox" value="{!! $ca->car_id !!}" {!! in_array($ca->car_id, $cookies) ? 'checked' : '' !!}>
                             @else
                                 <input type="checkbox" id="compare{!! $ca->car_id !!}" class="compare_checkbox" value="{!! $ca->car_id !!}" {!! in_array($ca->car_id, $cookies) ? 'checked' : '' !!}>
                             @endif
                             <label for="compare{!! $ca->car_id !!}">Add to compare</label>
                        </div>
                     </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="moreBtn">
            <a href="{{ route("carfilters") }}">
                BROWSE MORE CARS
            </a>
        </div>

    </div>
</section>
<!-- END popularsArea -->
