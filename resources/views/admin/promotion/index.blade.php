@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.Promotion') }} <small>{{ trans('labels.Promotion') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.Promotion') }}</li>
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
                <!-- search filter -->
                {!! Form::open(array('url' =>'admin/filterpromotions', 'method'=>'get', 'class' => 'search-filter form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                              
                                <div class="form-group row">
                    
                                <div class="col-xs-2">
                                <label>Name :</label>
                                    <input class="form-control" id="filter_name" name="filter_name" type="text" placeholder="Name">
                                </div>
                                <div class="col-xs-2">
                                  <label>Start Date :</label>
                                    <input class="form-control" id="start_date" name="start_date" type="date">
                                    <label>End Date :</label>
                                    <input class="form-control" id="end_date" name="end_date" type="date">
                                </div>
                                <div class="col-xs-2">
                                <label>Organisation :</label>
                                    <select id="organisation_id" name="organisation_id" class="form-control select2">
                                        <option value="">Organisation Name</option>
                                        @if (count($result['organisation']) > 0)
                                            @foreach ($result['organisation']  as $key=>$admin)
                                                <option value="{{ $admin->id }}">{{ $admin->company_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                 <div class="col-xs-2">
                                 <label>Segment :</label>
                                    <select id="segment_type" name="segment_type" class="form-control">
                                        <option value="">Segments</option>
                                        @if (count($result['segments']) > 0)
                                            @foreach ($result['segments']  as $key=>$segment)
                                                <option value="{{ $segment->segment_id }}">{{ $segment->segment_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div> 

                              
                                <div class="col-xs-2">
                                <label>Status :</label>
                                                    <select name="status_id" class='form-control field-validate' id="status_id">
                                                        <option value="">{{ trans('labels.SelectStatus') }}</option>
                                                        <option value="1"> {{ trans('labels.Enabled') }} </option>
                                                        <option value="0"> {{ trans('labels.Disabled') }} </option>
                                                    </select>
                                </div> 
                                </div>

                                <div class="form-group row">
                                <div class="col-xs-2">
                                    <button type="submit" class="btn btn-primary">Search <span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                </div>
                                </div>
                            {!! Form::close() !!}
            <div class="box-tools pull-right">
            	<a href="{{ URL::to('admin/addpromotions')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.add_promotion') }}</a>
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
                    <th>{{ trans('labels.PromoID') }}</th>
                      <th> @sortablelink('promotion_name',  trans('labels.PromoName') ) </th>
                      <th> {{ trans('labels.PromoSegments') }} </th>
                      <th> @sortablelink('period_start', trans('labels.PeriodStartEnd') ) </th> 
                      <th> @sortablelink('promotion_clicks', trans('labels.PromoClick') )  </th> 
                      <th> {{ trans('labels.PromoRedeem')  }} </th> 
                      <th>  @sortablelink('status', trans('labels.PromoStatus') ) </th> 
                      <th> {{ trans('labels.PromoAction')  }} </th>          
                              
                    </tr>
                  </thead>
                  <tbody>
                    @if (count($result['admins']) > 0)
                      @foreach ($result['admins']  as $key=>$admin)
                      <tr>
                        <td>{{ $admin->promotion_id }}</td>
                        <td>{{ $admin->promotion_name  }}</td>
                        <td>      
                          @if($admin->segment_id)
                            @foreach ($result['segments']  as $key=>$segment)
                              @if($admin->segment_id == $segment->segment_id)
                              <strong class="badge" style="background-color: #1000ff !important;">{{$segment->segment_name}}</strong>
                              @endif
                            @endforeach
                          @else
                            <strong class="badge" style="background-color: #676666 !important;">N/A</strong>
                          @endif 
                        </td>
                        <td>
                          {{ date('d M y h:i A', strtotime($admin->period_start)) }}
                          - 
                          {{ date('d M y h:i A', strtotime($admin->period_end)) }}
                        </td>
                        <td>{{ $admin->promotion_clicks }} </td>
                        <td> <a href="redeemlisting/{{ $admin->promotion_id }}"> {{ $admin->promotion_redeem_history }} </a> </td>
                        <td>  @if  ($admin->status == "1") <strong class="badge bg-green"> {{ trans('labels.Enabled')  }} </strong> @else ($admin->status == 0") <strong class="badge bg-light-grey"> {{ trans('labels.Disabled')  }} </strong>  @endif </td>
                        <td>
                        <a data-toggle="tooltip" data-placement="bottom"  href="editpromotion/{{ $admin->promotion_id }}" class="badge bg-light-blue">
                          <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                      
                         <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteMemberFrom"
                         users_id ="{{ $admin->promotion_id }}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        
								        </td>
                      </tr>
                      @endforeach
                    @else
                    <tr>
                      <td colspan="8">{{ trans('labels.NoRecordFound') }}</td>
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

    <!-- /.row -->

    <!-- deleteAdminModal -->
	<div class="modal fade" id="deleteMemberModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdminModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="deleteAdminModalLabel">{{ trans('labels.Delete') }}</h4>
                    </div>
                    {!! Form::open(array('url' =>'admin/deletepromotions', 'name'=>'deletepromotions', 'id'=>'deletepromotions', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                    {!! Form::hidden('action', 'delete', array('class'=>'form-control')) !!}
                    {!! Form::hidden('users_id', '', array('class'=>'form-control', 'id'=>'users_id')) !!}
                    <div class="modal-body">
                        <p>{{ trans('labels.PromoDelete') }}</p>
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

<script>
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
</script>
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
</style>
@endsection
