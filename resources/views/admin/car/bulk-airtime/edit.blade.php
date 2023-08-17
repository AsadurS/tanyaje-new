@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.bulkairtime') }} <small>{{ trans('labels.BulkEditAirtime') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="">{{ trans('labels.Car') }}</li>
      <li class="active">{{ trans('labels.BulkEditAirtime') }}</li>
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
            <h3 class="box-title">{{ trans('labels.BulkEditAirtime') }} </h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="form-group">
              <label for="name" class="col-md-2 control-label">{{ trans('labels.MerchantName') }}
              </label>
              <div class="col-md-4">
                <select class="form-control select2" id="merchant-airtime">
                  <option value="">{{ trans('labels.choosemerchant') }}</option>
                  @foreach ($merchants as $merchant)
                    <option value="{{$merchant->id}}">{{$merchant->merchant_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <select id="airtime-cars-options" multiple="multiple" style="display: none;">
            </select>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
@endsection
