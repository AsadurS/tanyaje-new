@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.Make') }} <small>{{ trans('labels.ListingAllMake') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li class=" active">{{ trans('labels.Make') }}</li>
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
                            {{--<h3 class="box-title">{{ trans('labels.ListingAllMake') }} </h3>--}}

                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-6 form-inline">
                                                <form name='filter' id="registration" class="filter  " method="get" action="{{url('admin/make/filter')}}">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <div class="input-group-form search-panel ">
                                                      <select type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" name="FilterBy" id="FilterBy" >
                                                        <option value="" selected disabled hidden>{{trans('labels.Filter By')}}</option>
                                                        <option value="Name" @if(isset($name)) @if  ($name == "Name") {{ 'selected' }} @endif @endif>{{trans('labels.Name')}}</option>
                                                      </select>
                                                      <input type="text" class="form-control input-group-form " name="parameter" placeholder="{{trans('labels.Search')}}..." id="parameter" @if(isset($param)) value="{{$param}}" @endif>
                                                      <button class="btn btn-primary " type="submit" id="submit"><span class="glyphicon glyphicon-search"></span></button>
                                                      @if(isset($param,$name))  <a class="btn btn-danger " href="{{URL::to('admin/make/display')}}"><i class="fa fa-ban" aria-hidden="true"></i> </a>@endif
                                                    </div>
                                                </form>
                                                <div class="col-lg-4 form-inline" id="contact-form12"></div>
                                        </div>
                                    </div>
                                </div>

                            <div class="box-tools pull-right">
                                <a href="{{ URL::to('admin/make/add') }}" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddNew') }}</a>
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
                                            <th>@sortablelink('make_id', trans('labels.ID') )</th>
                                            <th>@sortablelink( 'make_name', trans('labels.Name') )</th>
                                            <th>{{ trans('labels.Logo') }}</th>
                                            <th>{{ trans('Is featute') }}</th>
                                            <th>{{ trans('labels.Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($make)>0)
                                            @foreach ($make  as $key=>$value)
                                                <tr>
                                                    <td>{{ $value->id }}</td>
                                                    <td>{{ $value->name }}</td>
                                                 <td><img src="{{asset($value->imgpath)}}" alt="" width=" 100px"></td>
                                                    <td>
                                                        @if($value->is_feature==1)
                                                         Feature
                                                         @endif   
                                                    </td>
                                                    <td>
                                                        <a data-toggle="tooltip" data-placement="bottom" title="Edit" href="{{ URL::to('admin/make/edit/'.$value->id)}}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                        <a id="makeFrom" make_id='{{ $value->id }}' data-toggle="tooltip" data-placement="bottom" title="Delete" data-href="{{url('admin/make/delete')}}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                                                </tr>
                                            @endforeach

                                        @else
                                            <tr>
                                                <td colspan="5">{{ trans('labels.NoRecordFound') }}</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    @if($make != null)
                                    <div class="col-xs-12 text-right">
                                        {{$make->links()}}
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

            <!-- Main row -->

            <!-- deleteMakeModal -->
            <div class="modal fade" id="makeModal" tabindex="-1" role="dialog" aria-labelledby="deleteMakeModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="deleteMakeModalLabel">{{ trans('labels.DeleteMake') }}</h4>
                        </div>
                        {!! Form::open(array('url' =>'admin/make/delete', 'name'=>'deleteMake', 'id'=>'deleteMake', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                        {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
                        {!! Form::hidden('make_id',  '', array('class'=>'form-control', 'id'=>'make_id')) !!}
                        <div class="modal-body">
                            <p>{{ trans('labels.DeleteMakeText') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ trans('labels.Delete') }}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
