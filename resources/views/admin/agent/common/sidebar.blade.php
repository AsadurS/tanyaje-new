<style>
    .main-sidebar, .left-side {
        position: fixed;
    }
</style>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">

            <li class="header">{{ trans('labels.navigation') }}</li>


            <li class="treeview {{ Request::is('/agent/dashboard') ? 'active' : '' }}">
                <a href="{{ URL::to('/agent/dashboard')}}">
                    <i class="fa fa-dashboard"></i> <span>{{ trans('labels.header_dashboard') }}</span>
                </a>
            </li>
            <li class="treeview {{ Request::is('/agent/sales-advisor') ? 'active' : '' }}">
                <a href="{{ URL::to('/agent/sales-advisor')}}">
                    <i class="fa fa-dashboard"></i> <span> Sales Advisor</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
