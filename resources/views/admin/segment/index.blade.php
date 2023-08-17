@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.Segments') }} <small>{{ trans('labels.Segments') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.Segments') }}</li>
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
            <!-- <div class="title-search pull-left">
              <h3 class="box-title">{{ trans('labels.templates') }} </h3>
            </div> -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6 form-inline">
                            <form name='filter' id="registration" class="filter  " method="get" action="{{url('admin/filterSegment')}}">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="input-group-form search-panel ">
                                    <select type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" name="FilterBy" id="FilterBy" >
                                    <option value="" selected disabled hidden>{{trans('labels.Filter By')}}</option>
                                    <option value="Name" @if(isset($name)) @if  ($name == "Name") {{ 'selected' }} @endif @endif>{{trans('labels.SegmentName')}}</option>
                                    </select>
                                    <input type="text" class="form-control input-group-form " name="parameter" placeholder="{{trans('labels.Search')}}..." id="parameter" @if(isset($param)) value="{{$param}}" @endif>
                                    <button class="btn btn-primary " type="submit" id="submit"><span class="glyphicon glyphicon-search"></span></button>
                                    @if(isset($param,$name))  <a class="btn btn-danger " href="{{URL::to('admin/segments')}}"><i class="fa fa-ban" aria-hidden="true"></i> </a>@endif
                                </div>
                            </form>
                            <div class="col-lg-4 form-inline" id="contact-form12"></div>
                    </div>
                </div>
            </div>
            <div class="box-tools pull-right">
            	<a href="{{ URL::to('admin/addsegments')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.addSegment') }}</a>
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
              <div class="col-xs-12"><br>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th style="text-align:center;">{{ trans('labels.ID') }}</th>
                      <th>{{ trans('labels.SegmentName') }}</th>
                      <th>{{ trans('labels.Organisation') }}</th>
                      <th style="text-align:center;">{{ trans('labels.Sort') }}</th>
                      <th style="text-align:center;"> {{ trans('labels.Action') }} </th>                      
                    </tr>
                  </thead>
                  <tbody>
                    @if (count($segment) > 0)
                      @foreach ($segment as $key=>$admin)
                      <tr>
                        <td style="text-align:center;">{{ $admin->segment_id }}</td>
                        <td>{{ $admin->segment_name }}</td>
                        <td>
                          @php
                          $organisation = DB::table('users')
                              ->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
                              ->select('users.*','user_types.*')
                              ->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
                              ->where('users.segment_id','=', $admin->segment_id)
                              ->count();
                          @endphp
                          {!! Form::open(array('url' =>'admin/merchantsSegment', 'method'=>'get', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                          <input type="hidden" name="segment_id" value="{{ $admin->segment_id }}">
                          <button type="submit" formtarget="_blank" class="organisation_segment">List Of Organisation ({{$organisation}})</button>
                          {!! Form::close() !!}
                        </td>
                        <td style="text-align:center;">{{ $admin->sort }}</td>
                        <td style="text-align:center;">
                          <ul class="nav table-nav">
                            <li class="dropdown">
                              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                {{ trans('labels.Action') }} <span class="caret"></span>
                              </a>
                              <ul class="dropdown-menu">
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="editsegments/{{ $admin->segment_id }}">{{ trans('labels.Edit') }}</a></li>
                                  <li role="presentation" class="divider"></li>
                                  <li role="presentation"><a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteMemberFrom"
                                    users_id="{{ $admin->segment_id }}">{{ trans('labels.Delete') }}</a></li>
                              </ul>
                            </li>
                          </ul>
								        </td>
                      </tr>
                      @endforeach
                    @else
                    <tr>
                      <td colspan="5">{{ trans('labels.NoRecordFound') }}</td>
                    </tr>
                    @endif
                  </tbody>
                </table>
                  @if($segment != null)
                    <div class="col-xs-12 text-right">
                      @if(app('request')->input('FilterBy'))
                          {!! $segment->appends(request()->except('page'))->render() !!}
                      @else
                          {{$segment->links()}}
                      @endif 
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

    <!-- deleteAdminModal -->
	<div class="modal fade" id="deleteMemberModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdminModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="deleteAdminModalLabel">{{ trans('labels.Delete') }}</h4>
                    </div>
                    {!! Form::open(array('url' =>'admin/deletesegments', 'name'=>'deleteSegment', 'id'=>'deleteSegment', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                    {!! Form::hidden('action', 'delete', array('class'=>'form-control')) !!}
                    {!! Form::hidden('users_id', '', array('class'=>'form-control', 'id'=>'users_id')) !!}
                    <div class="modal-body">
                        <p>{{ trans('labels.DeleteSegmentText') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('labels.Delete') }}</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content notificationContent">

                </div>
            </div>
        </div>

	

    <!-- Main row -->

    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

<!-- <script>
  $(document).ready( function () {
    $("#example1").dataTable({
        "aoColumnDefs": [{ 'bSortable': false, 'aTargets': [3] }],
        "bSort": false,
        dom: 'Blfrtip',
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 rows', '25 rows', '50 rows', 'Show all']
        ],
        buttons: [
            'excelHtml5'
        ]
    });
  } );
</script> -->
<style>
  .dataTables_wrapper .dataTables_length {
    float: left;
    margin-bottom: 24px;
  }
  .dataTables_wrapper .dataTables_paginate {
    float: right;
    text-align: right;
    padding-top: .25em;
    margin-top: 24px;
  }
  .dataTables_wrapper .dataTables_info {
    clear: both;
    float: left;
    padding-top: .755em;
    margin-top: 24px;
  }
  .organisation_segment {
    background-color: white;
    border-radius: 10px;
    padding: 3px 10px;
    color: blue;
    font-weight: 500;
    border: 2px solid #c6c6c6;
  }
  .table-nav ul.dropdown-menu {
    padding: 2px 0;
    width: 100%;
    text-align: center;
  }
</style>
@endsection
