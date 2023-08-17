@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.Campaigns') }} <small>{{ trans('labels.Campaigns') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.Campaigns') }}</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Info boxes -->

    <!-- /.row -->

    <div class="row">
      <div class="col-md-12">
        <div class="box">
            <div class="row">
              <div class="col-xs-12">

                @if(session()->has('message'))
                    <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session()->get('message') }}
                    </div>
                @endif

                @if(session()->has('errorMessage'))
                    <div class="alert alert-danger" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session()->get('errorMessage') }}
                    </div>
                @endif
            
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
          <div class="box-header">
            <!-- <div class="title-search pull-left">
              <h3 class="box-title">{{ trans('labels.templates') }} </h3>
            </div> -->
                <!-- search filter -->
                {!! Form::open(array('url' =>'admin/filtercampaign', 'method'=>'get', 'class' => 'search-filter form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                              
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
                                @if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT )
                                    @foreach($result['organisation'] as $organisations)
                                        @if (count($result['org_list']) > 0)
                                        @foreach($result['org_list'] as $org_lists)
                                            @if($organisations->id == $org_lists)
                                            <option value="{{ $organisations->id }}">{{ $organisations->company_name }}</option>
                                            @endif
                                        @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    @foreach ($result['organisation']  as $key=>$admin)
                                    <option value="{{ $admin->id }}">{{ $admin->company_name }}</option>
                                    @endforeach
                                @endif
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
                @if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT )
                  @if($result['isHq'] == '1')
                  <a href="{{ URL::to('admin/addcampaigns')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.add_campaign') }}</a>
                  @else
                  <a href="{{ URL::to('admin/syncCampaign')}}" type="button" class="btn btn-block btn-primary">Refresh</a>
                  @endif
                @else
                <a href="{{ URL::to('admin/addcampaigns')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.add_campaign') }}</a>
                @endif
            </div>
          </div>

          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12"><br>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>{{ trans('labels.CampaignID') }}</th>
                        <th>{{ trans('labels.CampaignName') }}</th>
                        <th>{{ trans('labels.CampaignStatus') }}</th>
                        <th>{{ trans('labels.CampaignPeriod') }}</th> 
                        <th>{{ trans('labels.CampaignShare') }}</th> 
                        <th>{{ trans('labels.CampaignClick') }}</th> 
                        <th>{{ trans('labels.CampaignInterest') }}</th> 
                        <th>{{ trans('labels.CampaignResponses') }}</th>  
                        @if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT )
                          @if($result['isHq'] == '1')
                            <th>{{ trans('labels.CampaignAction') }}</th>      
                          @endif    
                        @else
                        <th>{{ trans('labels.CampaignAction') }}</th>      
                        @endif
                    </tr>
                  </thead>
                  <tbody>
                    @if (count($result['admins']) > 0)
                        @foreach ($result['admins']  as $key=>$admin)
                        <tr>
                            <th>{{ $admin->campaign_id }}</th>
                            <th>{{ $admin->campaign_name }}</th>
                            <td>  
                              <!-- <a data-id="{{ $admin->campaign_id }}" data-toggle="modal" data-target="#editModal" style="cursor: pointer;"
                                data-whatever="{{ $admin->campaign_id }}" data-whatever2="{{ $admin->status }}" >
                                @if  ($admin->status == "1") 
                                  <strong class="badge bg-green"> {{ trans('labels.Enabled')  }} </strong> 
                                @else ($admin->status == 0") 
                                  <strong class="badge bg-light-grey"> {{ trans('labels.Disabled')  }} </strong>  
                                @endif 
                              </a> -->
                              <?php 
                                $currentUser = Auth::user()->id;
                              ?>
                              <input type="checkbox" data-id="{{$admin->campaign_id}}" data-id2="{{$currentUser}}" class="toggle-class" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Enabled" data-off="Disabled" {{ $admin->status ? 'checked' : '' }} onclick='handleClick(this);'>
                            </td>
                            <th>
                            {{ date('d M y h:i A', strtotime($admin->period_start)) }}
                            - 
                            {{ date('d M y h:i A', strtotime($admin->period_end)) }}
                            </th>
                            <th>{{ $admin->share ?? 'NA' }}</th>
                            <th>{{ $admin->click ?? 'NA' }}</th>
                            <th>{{ $admin->interest ?? 'NA' }}</th>
                            <th>{{ $admin->response ?? 'NA' }}</th>
                            @if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT )
                              @if($result['isHq'] == '1')
                              <th>
                                  <a data-toggle="tooltip" data-placement="bottom"  href="editcampaigns/{{ $admin->campaign_id }}" class="badge bg-light-blue">
                                      <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                  </a>
                        
                                  <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteMemberFrom"
                                  users_id ="{{ $admin->campaign_id }}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>
                              </th>    
                              @endif
                            @else
                              <th>
                                  <a data-toggle="tooltip" data-placement="bottom"  href="editcampaigns/{{ $admin->campaign_id }}" class="badge bg-light-blue">
                                      <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                  </a>
                        
                                  <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteMemberFrom"
                                  users_id ="{{ $admin->campaign_id }}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>
                              </th>    
                            @endif
                        </tr>
                        @endforeach
                    @else
                    <tr>
                      @if($result['isHq'] == '1')
                      <td colspan="9">{{ trans('labels.NoRecordFound') }}</td>
                      @else
                      <td colspan="8">{{ trans('labels.NoRecordFound') }}</td>
                      @endif
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
                    {!! Form::open(array('url' =>'admin/deletecampaigns', 'name'=>'deletecampaigns', 'id'=>'deletecampaigns', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                    {!! Form::hidden('action', 'delete', array('class'=>'form-control')) !!}
                    {!! Form::hidden('users_id', '', array('class'=>'form-control', 'id'=>'users_id')) !!}
                    <div class="modal-body">
                        <p>{{ trans('labels.CampaignDelete') }}</p>
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
<script src = "https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"  />
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

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
<script>
  $(".toggle-class").change(function() {
    var status = $(this).prop('checked') == true ? 1 : 0; 
    var campaign_id = $(this).data('id'); 
    var current_user = $(this).data('id2');
    $.ajaxSetup({
                headers:
                { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
    $.ajax({
        type: "GET",
        dataType: "json",
        url: '/admin/changeStatus',
        data: {'status': status, 'campaign_id': campaign_id, 'current_user': current_user},
        error: function (data) {
            alert('Status change failed. Please get help from Upper Management');
            location.reload();
        },
        success: function(data){
            alert(data.success)
        }
    });
  });
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
