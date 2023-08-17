@if(count($result['type']))
<!-- START carTypesArea -->
<section class="carTypesArea">
    <div class="container">
        <div class="heading">
            <h2>Search By <span> Car Types</span></h2>
        </div>
        <div class="itemBox disflexArea">
{{--           @foreach($result['type'] as $type)--}}
{{--            <div class="item">--}}
{{--                <img src="{{asset('new/images/carTypeImg01.png')}}" alt="">--}}
{{--                <h3>{{$type->type_name}}</h3>--}}
{{--            </div>--}}
{{--            @endforeach--}}
            <div class="item">
                <a href="{{ route("carfilters") . "?categories=5" }}">
                    <img src="{{asset('new/images/carTypeImg01.png')}}" alt="">
                    <h3>Convertibles</h3>
                </a>
            </div>
            <div class="item">
                <a href="{{ route("carfilters") . "?categories=11" }}">
                    <img src="{{asset('new/images/carTypeImg02.png')}}" alt="">
                    <h3>Coupes</h3>
                </a>
            </div>
            <div class="item">
                <a href="{{ route("carfilters") . "?categories=9" }}">
                    <img src="{{asset('new/images/carTypeImg03.png')}}" alt="">
                    <h3>Ute</h3>
                </a>
            </div>
            <div class="item">
                <a href="{{ route("carfilters") . "?categories=7" }}">
                    <img src="{{asset('new/images/carTypeImg04.png')}}" alt="">
                    <h3>Sedans</h3>
                </a>
            </div>
            <div class="item">
                <a href="{{ route("carfilters") . "?categories=10" }}">
                    <img src="{{asset('new/images/carTypeImg05.png')}}" alt="">
                    <h3>Wagon</h3>
                </a>
            </div>
            <div class="item">
                <a href="{{ route("carfilters") . "?categories=6" }}">
                    <img src="{{asset('new/images/carTypeImg06.png')}}" alt="">
                    <h3>Hatchback</h3>
                </a>
            </div>
            <div class="item">
                <a href="{{ route("carfilters") . "?categories=8" }}">
                    <img src="{{asset('new/images/carTypeImg07.png')}}" alt="">
                    <h3>SUV</h3>
                </a>
            </div>
            <div class="item">
                <a href="{{ route("carfilters") . "?categories=12" }}">
                    <img src="{{asset('new/images/carTypeImg08.png')}}" alt="">
                    <h3>MPV</h3>
                </a>
            </div>
{{--            <div class="item">--}}
{{--                <a href="#">--}}
{{--                    <img src="{{asset('new/images/carTypeImg09.png')}}" alt="">--}}
{{--                    <h3>Lorry</h3>--}}
{{--                </a>--}}
{{--            </div>--}}
        </div>
    </div>
</section>
<!-- END carTypesArea -->
@endif