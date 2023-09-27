<!DOCTYPE html>
<html>

<!-- meta contains meta taga, css and fontawesome icons etc -->
@include('admin.common.meta')
<!-- ./end of meta -->
@yield('styles')
<body class=" hold-transition skin-blue sidebar-mini">
<!-- wrapper -->
<div class="wrapper">

    <!-- header contains top navbar -->
    @include('admin.agent.common.header')
    <!-- ./end of header -->

    <!-- left sidebar -->
    @include('admin.agent.common.sidebar')
    <!-- ./end of left sidebar -->

    <!-- dynamic content -->
    @yield('content')
    <!-- ./end of dynamic content -->

    <!-- right sidebar -->
    @include('admin.agent.common.controlsidebar')
    <!-- ./right sidebar -->
    @include('admin.agent.common.footer')
</div>
<!-- ./wrapper -->

<!-- all js scripts including custom js -->
@include('admin.common.scripts')
<!-- ./end of js scripts -->
@yield('scripts')
</body>
</html>
