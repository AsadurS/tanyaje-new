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
           <!-- search filter -->
           {!! Form::open(array('url' =>'admin/filterRedemption', 'method'=>'get', 'class' => 'search-filter form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                              
                              <div class="form-group row">
                  
                      
                              <div class="col-xs-2">
                              <label>Promotion :</label>
                                  <select id="promotion_id" name="promotion_id" class="form-control select2">
                                      <option value="">Promotion</option>
                                      @if (count($result['promotions']) > 0)
                                          @foreach ($result['promotions']  as $key=>$promotions)
                                              <option value="{{ $promotions->promotion_id }}">{{ $promotions->promotion_name }}</option>
                                          @endforeach
                                      @endif
                                  </select>
                              </div>
                               <div class="col-xs-2">
                               <label> Customer :</label>
                                  <select id="customer_id" name="customer_id" class="form-control select2">
                                      <option value="">Customer</option>
                                      @if (count($result['customer']) > 0)
                                          @foreach ($result['customer']  as $key=>$customer)
                                              <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                                          @endforeach
                                      @endif
                                  </select>
                              </div> 
                              <div class="col-xs-2">
                                <label>Start Date :</label>
                                  <input class="form-control" id="start_date" name="start_date" type="date">
                                  <label>End Date :</label>
                                  <input class="form-control" id="end_date" name="end_date" type="date">
                              </div>
                              <div class="col-xs-2">
                               <label> Referral :</label>
                                  <select id="referal_id" name="referal_id" class="form-control select2">
                                      <option value="">Referral</option>
                                      @if (count($result['salesagentname']) > 0)
                                          @foreach ($result['salesagentname']  as $key=>$referral)
                                              <option value="{{ $referral->id }}">{{ $referral->merchant_name }} </option>
                                          @endforeach
                                      @endif
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
