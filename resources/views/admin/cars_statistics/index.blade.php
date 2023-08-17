@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.Statistics Report') }} <small>{{ $report }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ $report }}</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Info boxes -->

    <!-- /.row -->

    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">{{ $report }} </h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">

            <div class="row">
              <div class="col-xs-12">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                    @if($request->get('type') == "car")
                      <th>Id</th>
                      <th>VIN</th>
                    @elseif($request->get('type') == "model")
                      <th>Brand</th>
                    @elseif($request->get('type') == "price")
                      <th>State</th>
                    @endif
                      <th>{{ trans('labels.'.title_case($request->get('type'))) }}</th>
                      <th>{{ trans('labels.visit') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                 @if($cars->count() > 0)
                    @foreach ($cars as $key => $car)
                        <tr>
                            @if($request->get('type') == "car")
                            <td>{{ $car->car_id }}</td>
                            <td><a href="{!! url('admin/car/filter?FilterBy=VIN&parameter='.$car->vim) !!}" target="_blank">{{ $car->vim }}</a></td>
                            @elseif($request->get('type') == "model")
                            <td>{!! $car->brand !!}</td>
                            @endif
                            <td>{{ $car->title }}</td>
                            @if($request->get('type') == "price")
                            <td>{{ $car->min_price }} - {{$car->max_price}}</td>
                            @endif
                            <td>{{ $car->visits }}</td>
                        </tr>
                    @endforeach
                 @else
                 <tr>
                 	<td colspan="4">
                 		{{ trans('labels.NoRecordFound') }}
                    </td>
                 </tr>
                 @endif
                 </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>

    <!-- Main row -->

    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
@endsection
