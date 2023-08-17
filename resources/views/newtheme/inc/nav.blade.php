<!-- START navnavigation_site -->
@if( !(Request::route()->getName() == "site.merchant" || Request::route()->getName() == "car_details" )  )
<nav class="navnavigation_site">
    <div class="container">
        <ul>
            <li class="{!! request()->fullurl() == route("carfilters") ? 'active' : '' !!}">
                <a href="{{ route("carfilters") }}">
                    Search Car
                </a>
            </li>
            <li>
                <?php
                    $url = "#how_to_buy";
                    if( Route::current()->getName() != "home" )
                    {
                        $url = route("home"). "#how_to_buy";
                    }
                ?>
                <a class="header_link" href="{{ $url }}">
                    How To Buy
                </a>
            </li>
            <li class="{!! request()->fullurl() == route('carcompare') ? 'active' : '' !!}">
                <a href="{{ route('carcompare') }}">
                    Compare Car
                </a>
            </li>
        </ul>
    </div>
</nav>
@endif
<!-- END navnavigation_site -->

@if( Request::route()->getName() == "profile" || Request::route()->getName() == "saved_cars" )
<!-- START navnavigation_site_second-->
<nav class="navnavigation_site_second">
    <div class="container">
        <ul>
            <li {{ Request::route()->getName() == "profile"? "class=active":"" }}>
                <a href="{{ route('profile') }}">
                    Profile
                </a>
            </li>
            <li {{ Request::route()->getName() == "saved_cars"?"class=active":"" }}>
                <a href="{{ route('saved_cars') }}">
                    Saved Cars
                </a>
            </li>
        </ul>
    </div>
</nav>
<!-- END navnavigation_site_second-->
@endif
