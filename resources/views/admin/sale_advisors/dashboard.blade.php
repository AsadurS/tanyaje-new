@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <small>{{ trans('labels.title_dashboard') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li class="active"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    {{--<script src="{!! asset('plugins/jQuery/jQuery-2.2.0.min.js') !!}"></script>--}}

    {{--<script src="{!! asset('dist/js/pages/dashboard2.js') !!}"></script>--}}
@endsection
