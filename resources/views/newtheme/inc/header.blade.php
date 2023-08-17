<!-- START header_site -->
<header class="header_site mob-toggle">
    <div class="container disflexArea algnflexArea">
        <div class="logo">
            @if( !(Route::currentRouteName() == 'site.merchant' || Route::currentRouteName() == 'car_details' ) )
            <a href="{{route('home')}}">
                <img src="{{asset('new/images/landingpagelogo.png')}}" alt="" style="width:180px">
            </a>
            @endif
        </div>

        @if( !(Route::currentRouteName() == 'site.merchant' || Route::currentRouteName() == 'car_details' ) )
        <div class="nav-links">
            <a href="#" id="overlay">
                <div class="icon"></div>
            </a>
        <!--   <div class="menu">
                <ul>
                    <li>
                        <?php
                            $url = "#how_to_buy";
                            if( Route::current()->getName() != "home" )
                            {
                                $url = route("home"). "#how_to_buy";
                            }
                        ?>
                        <a class="header_link" href="{{ $url }}">How To Buy</a>
                    </li>
                    <li class="{!! request()->fullurl() == route("wishList") ? 'active' : '' !!}">
                        <a href="{{ route('wishList') }}">Save Car</a>
                    </li>
                    <li class="{!! request()->fullurl() == route('carcompare') ? 'active' : '' !!}">
                        <a href="{{ route('carcompare') }}">Compare Car</a>
                    </li>
                </ul>
            </div>
            <form action="{{route('carfilters')}}" method="get">
            @php
                $merchants = DB::table('users')
                    ->select('users.id','users.company_name')
                    ->where('users.role_id',11)
                    ->where('users.status',1)
                    ->where('users.company_name',"!=",null)
                    ->orderBy('users.company_name','ASC')->get();
            @endphp
            <select name="merchant" class="customFild merchant_name">
                <option value="">Listed By</option>
                    @foreach($merchants as $merchants)
                    <option value="{{$merchants->id}}">{{ $merchants->company_name }}</option>
                    @endforeach
            </select>
            </form> -->
            <div class="userPart">
                <ul>
                    @if(!Auth::guard('customer')->user())
                    <li class="userDropdown login login-main">
                            <a href="javascript:void(0)" class="circle">Login</i></a>
                            <div class="dropdown-menu" style="display: none;">
                                <ul>
                                    <li><strong>User</strong></li>
                                    <li>
                                        <a href="{{ route('customer_login') }}">Login</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('customer_register') }}">Register</a>
                                    </li>
                                    <!-- <li>
                                        <a href="javascript:void(0)">My Rewards</a>
                                    </li> -->
                               
                                </ul>
                               
                            </div>
                        </li>
                        <!-- <a class="login-main" href="{{ route('customer_login') }}">Login</a> -->
                    @else
                        <li class="userDropdown login">
                            @php
                                $source_path = public_path() . "/uploads/users/".Auth::guard('customer')->user()->avatar;
                                if (file_exists($source_path))
                                {
                                    $avatar_url = asset("/uploads/users/".Auth::guard('customer')->user()->avatar);
                                }
                                else
                                {
                                    $avatar_url = asset('images/default_user.jpg');
                                }
                            @endphp
                            <a href="javascript:void(0)" class="circle" style="background-image: url('{{ $avatar_url }}');"><i class="fas fa-caret-down"></i></a>
                            <div class="dropdown-menu" style="display: none;">
                                <ul>
                                    <li>
                                        <a href="{{ route('profile') }}">Profile</a>
                                    </li>
                                   <!--  <li>
                                        <a href="{{ route('wishList') }}">Save Car</a>
                                    </li> -->
                                    <li>
                                        <a href="{{ route('promotions') }}">Promotions</a>
                                    </li> 
                                     <li>
                                        <a href="{{ route('myrewards') }}">My Redemptions</a>
                                    </li> 
                                    <li>
                                        <a href="{{ route('customer_logout') }}" class="customer_logout">Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        @endif
    </div>
</header>
<!-- END header-site -->
