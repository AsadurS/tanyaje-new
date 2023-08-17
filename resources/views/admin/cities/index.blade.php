@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.Cities') }} <small> {{ trans('labels.ListingAllCities') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li class="active"> {{ trans('labels.Cities') }}</li>
            </ol>
        </section>

        <!--  content -->
        <section class="content">
            <!-- Info boxes -->

            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="col-lg-6 form-inline" id="contact-form">
                                <form  name='registration' id="registration" class="registration" method="get" action="{{url('admin/cities/filter')}}">
                                    <input type="hidden"  value="{{csrf_token()}}">
                                    <div class="input-group-form search-panel ">
                                        <select type="button" class="btn btn-default dropdown-toggle form-control" data-toggle="dropdown" name="FilterBy" id="FilterBy"  >
                                            <option value="" selected disabled hidden>{{trans('labels.Filter By')}}</option>
                                            <option value="City"  @if(isset($name)) @if  ($name == "City") {{ 'selected' }} @endif @endif>{{trans('labels.City')}}</option>
                                            <option value="State" @if(isset($name)) @if  ($name == "State") {{ 'selected' }}@endif @endif>{{trans('labels.State')}}</option>
                                        </select>
                                        <input type="text" class="form-control input-group-form " name="parameter" placeholder="Search term..." id="parameter" @if(isset($param)) value="{{$param}}" @endif >
                                        <button class="btn btn-primary " id="submit" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                        @if(isset($param,$name))  <a class="btn btn-danger " href="{{url('admin/cities/display')}}"><i class="fa fa-ban" aria-hidden="true"></i> </a>@endif
                                    </div>
                                </form>
                                <div class="col-lg-4 form-inline" id="contact-form12"></div>
                            </div>
                            <div class="box-tools pull-right">
                                <a href="{{url('admin/cities/add')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddCity') }}</a>
                            </div>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    @if (count($errors) > 0)
                                        @if($errors->any())
                                            <div class="alert alert-success alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                {{$errors->first()}}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>@sortablelink('city_id', trans('labels.ID') )</th>
                                            <th>@sortablelink('city_name', trans('labels.City') )</th>
                                            <th>@sortablelink('state_name', trans('labels.State') )</th>
                                            <th>{{ trans('labels.Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($cities as $key=>$city)
                                            <tr>
                                                <td>{{ $city->city_id }}</td>
                                                <td>{{ $city->city_name }}</td>
                                                <td>{{ $city->state_name }}</td>
                                                <td>
                                                    <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Edit') }}" href="{{url('admin/cities/edit/'.$city->city_id)}}" class="badge bg-light-blue">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a>
                                                    <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteCityId" city_id ="{{ $city->city_id }}" class="badge bg-red">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @if($cities != null)
                                      <div class="col-xs-12 text-right">
                                          {{$cities->links()}}
                                      </div>
                                    @endif
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
            <!-- deleteCityModal -->
            <div class="modal fade" id="deleteCityModal" tabindex="-1" role="dialog" aria-labelledby="deleteCityModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="deleteCityModalLabel">{{ trans('labels.DeleteCity') }}</h4>
                        </div>
                        {!! Form::open(array('url' =>'admin/cities/delete', 'name'=>'deleteCity', 'id'=>'deleteCity', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                        {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
                        {!! Form::hidden('id',  '', array('class'=>'form-control', 'id'=>'city_id')) !!}
                        <div class="modal-body">
                            <p>{{ trans('labels.DeleteCityText') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Cancel') }}</button>
                            <button type="submit" class="btn btn-primary" id="deleteCity">{{ trans('labels.Delete') }}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <!--  row -->

            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
