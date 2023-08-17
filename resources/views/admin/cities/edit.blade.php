@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.EditCity') }} <small>{{ trans('labels.EditCity') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{url('admin/cities/display')}}"><i class="fa fa-building-o"></i>{{ trans('labels.ListingAllCities') }}</a></li>
                <li class="active">{{ trans('labels.EditCity') }}</li>
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
                            <h3 class="box-title">{{ trans('labels.EditCity') }}</h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box box-info"><br>

                                      @if(session()->has('message'))
                                          <div class="alert alert-success alert-dismissible" role="alert">
                                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                              {{ session()->get('message') }}
                                          </div>
                                      @endif
                                        <!-- /.box-header -->
                                        <!-- form start -->
                                        <div class="box-body">

                                            {!! Form::open(array('url' =>'admin/cities/update', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                            {!! Form::hidden('city_id', $result['cities']->city_id, array('class'=>'form-control', 'id'=>'city_name'))!!}

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.State') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="city_state_id" class='form-control field-validate'>
                                                        @foreach( $result['state'] as $state_data)
                                                            <option
                                                                    @if( $state_data->state_id == $result['cities']->city_state_id)
                                                                    selected
                                                                    @endif
                                                                    value="{{ $state_data->state_id }}"> {{ $state_data->state_name }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ChooseCityState') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.CityName') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('city_name', $result['cities']->city_name, array('class'=>'form-control field-validate', 'id'=>'city_name'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CityNameText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                                <a href="{{url('admin/cities/display')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
                                            </div>
                                            <!-- /.box-footer -->
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

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
