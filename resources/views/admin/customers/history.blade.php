@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.promoDetail') }} <small>{{ trans('labels.promoDetail') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.promoDetail') }}</li>
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
                            <form name='filter' id="registration" class="filter  " method="get" action="">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="input-group-form search-panel ">
                                    <select type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" name="FilterBy" id="FilterBy" >
                                    <option value="" selected disabled hidden>{{trans('labels.Filter By')}}</option>
                                    <option value="Customer" @if(isset($name)) @if  ($name == "Customer") {{ 'selected' }} @endif @endif>{{trans('labels.filterCustomer')}}</option>
                                    <option value="Promotion" @if(isset($name)) @if  ($name == "Promotion") {{ 'selected' }} @endif @endif>{{trans('labels.filterPromotion')}}</option>
                                    <option value="Date" @if(isset($name)) @if  ($name == "Date") {{ 'selected' }} @endif @endif>{{trans('labels.filterDate')}}</option>
                                    <option value="Referral" @if(isset($name)) @if  ($name == "Referral") {{ 'selected' }} @endif @endif>{{trans('labels.filterReferral')}}</option>
                                    </select>
                                    <input type="text" class="form-control input-group-form " name="parameter" placeholder="{{trans('labels.Search')}}..." id="parameter" @if(isset($param)) value="{{$param}}" @endif>
                                    <button class="btn btn-primary " type="submit" id="submit"><span class="glyphicon glyphicon-search"></span></button>
                                    @if(isset($param,$name))  <a class="btn btn-danger " href="{{URL::to('admin/promotions/display')}}"><i class="fa fa-ban" aria-hidden="true"></i> </a>@endif
                                </div>
                            </form>
                            <div class="col-lg-4 form-inline" id="contact-form12"></div>
                    </div>
                </div>
            </div>
            <div class="box-tools pull-right">
           <!-- 	<a href="{{ URL::to('admin/addpromotions')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.add_promotion') }}</a>-->
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
                   
                      <th>{{ trans('labels.redeemID')  }}</th>
                      <th> {{ trans('labels.redeemPromotion')  }}</th>
                      <th> {{ trans('labels.redeemCustomer')  }} </th> 
                      <th> {{ trans('labels.redeemDate')  }} </th> 
                      <th> {{ trans('labels.redeemSerial')  }} </th> 
                      <th> {{ trans('labels.redeemRefer')  }} </th> 
                      <th> {{ trans('labels.PromoAction')  }} </th>       
                              
                    </tr>
                  </thead>
                  <tbody>
                    @if (count($result['admins']) > 0)
                      @foreach ($result['admins']  as $admin)
                      <tr>
                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->promotion_name }}</td>
                        <td><a href="{{url('admin/customers/edit')}}/{{ $admin->customer_id }}">{{ $admin->customer_name }} </a></td>
                        <td>{{ $admin->redeem_date }}</td>
                        <td>{{ $admin->serial_prefix }}</td>
                        <td>{{ $admin->referral_name }}</td>

                        <td>
                        <a data-toggle="tooltip" data-placement="bottom"   href="{{url('admin/editredeems')}}/{{ $admin->id }}" class="badge bg-light-blue">
                          <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                         <a data-toggle="tooltip" data-placement="bottom" id="deleteMemberFrom" users_id ="{{ $admin->id }}" class="badge bg-red">
                             <i class="fa fa-trash" aria-hidden="true"></i>
                         </a>
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
                    {!! Form::open(array('url' =>'admin/deleteredeems', 'name'=>'deleteredeems', 'id'=>'deleteredeems', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                    {!! Form::hidden('action', 'delete', array('class'=>'form-control')) !!}
                    {!! Form::hidden('users_id', '', array('class'=>'form-control', 'id'=>'users_id')) !!}
                    <div class="modal-body">
                        <p>{{ trans('labels.RedeemDelete') }}</p>
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
