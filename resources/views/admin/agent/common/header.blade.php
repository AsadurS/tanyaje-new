
<header class="main-header">


    <!-- Logo -->
    @if(Auth::guard('agent')->check())
      <a href="{{ URL::to('admin/sale_advisors/dashboard')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini" style="font-size:12px"><b>{{ trans('labels.admin') }}</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
           <b>Agent</b>

        </span>
      </a>
    @endif


    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" id="linkid" data-toggle="offcanvas" role="button">
        <span class="sr-only">{{ trans('labels.toggle_navigation') }}</span>
      </a>
		<div id="countdown" style="
    width: 350px;
    margin-top: 13px !important;
    position: absolute;
    font-size: 16px;
    color: #ffffff;
    display: inline-block;
    margin-left: -175px;
    left: 50%;
"></div>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">


          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

              <span class="hidden-xs">

                  {{ Auth::guard('agent')->user()->first_name }} {{ Auth::guard('agent')->user()->last_name }}

              </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">

                <p>

                        {{ Auth::guard('agent')->user()->first_name }} {{ Auth::guard('agent')->user()->last_name }}
                    <small style="position: fixed">Agent</small>

                </p>
              </li>
              <!-- Menu Footer-->
              <li >
                <div class="pull-left" >

                    <a href="{{ URL::to('admin/admin/profile')}}" class="btn btn-default btn-flat">{{ trans('labels.profile_link')}}</a>
                </div>
                <div class="pull-right">

                    <a href="{{ URL::to('admin/logout')}}" class="btn btn-default btn-flat">{{ trans('labels.sign_out') }}</a>

                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!--<li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>
    </nav>
  </header>
