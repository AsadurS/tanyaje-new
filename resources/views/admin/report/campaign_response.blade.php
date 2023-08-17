@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.titleCampaignResponse') }}</h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active"><a href="{{ URL::to('admin/campaignreport')}}">{{ trans('labels.CampaignDashboard') }}</a></li>
      <li class="active">{{ trans('labels.titleCampaignResponse') }}</li>
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
                  <div class="col-xs-12">
                    {!! Form::open(array('url' =>'admin/filtercampaignsresponse', 'method'=>'get', 'class' => 'form-horizontal form-validate ', 'enctype'=>'multipart/form-data')) !!}
                      <div class="form-group row">
                        <div class="col-xs-2">
                          <label for="filterBy" class="control-label" style="">{{ trans('labels.FilterBy') }}</label>
                          @php
                            $selectfilterBy = request()->has('filterBy') ? request()->get('filterBy') : '';
                            $selectOrg = request()->has('organisation_id') ? request()->get('organisation_id') : '';
                            $selectCampaign = request()->has('campaign_id') ? request()->get('campaign_id') : '';
                          @endphp
                          <select class="form-control" name="filterBy" id="filterBy" onchange="javascript:handleSelect(this)">
                              <option value="">Default (last 30 days)</option>
                              <option value="today" @if($selectfilterBy == "today") selected @endif>Today</option>
                              <option value="yesterday" @if($selectfilterBy == "yesterday") selected @endif>Yesterday</option>
                              <option value="thisweek" @if($selectfilterBy == "thisweek") selected @endif>This Week</option>
                              <option value="thismonth" @if($selectfilterBy == "thismonth") selected @endif>This Month</option>
                              <option value="7day" @if($selectfilterBy == "7day") selected @endif>7 Days</option>
                              <option value="30day" @if($selectfilterBy == "30day") selected @endif>30 Days</option>
                              <option value="customdate" @if($selectfilterBy == "customdate") selected @endif>Custom Date Range</option>
                          </select>
                        </div>  
                      
                        <div class="col-xs-2 customFieldFrom" style="display:none;">
                          <label for="fromDate" class="control-label">From</label>
                          <input type="date" name="fromDate" id="fromDate" class="form-control" value="{{ request()->has('fromDate') ? request()->get('fromDate') : '' }}">
                        </div>

                        <div class="col-xs-2 customFieldTo" style="display:none;">
                          <label for="toDate" class="control-label" >To</label>
                          <input type="date" name="toDate" id="toDate" class="form-control" value="{{ request()->has('toDate') ? request()->get('toDate') : '' }}">
                        </div>

                        <div class="col-xs-2">
                            <label for="fromDate" class="control-label">Campaign</label>
                            <select id="campaign_id" name="campaign_id" class="form-control select2">
                                <option value="">All Campaigns</option>
                                @if (count($result['campaignn']) > 0)
                                    @if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT )
                                        @foreach($result['campaignn'] as $campaignns)
                                            @if (count($result['org_list']) > 0)
                                            @foreach($result['org_list'] as $org_lists)
                                                @if($campaignns->org_id == $org_lists)
                                                <option value="{{ $campaignns->campaign_id }}">{{ $campaignns->campaign_name }}</option>
                                                @endif
                                            @endforeach
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach ($result['campaignn']  as $key=>$campaignns)
                                        <option value="{{ $campaignns->campaign_id }}" @if($selectCampaign == $campaignns->campaign_id) selected @endif >{{ $campaignns->campaign_name }}</option>
                                        @endforeach
                                    @endif
                                @endif
                            </select>
                        </div>

                        <div class="col-xs-2">
                            <label for="fromDate" class="control-label">Organisation</label>
                            <select id="organisation_id" name="organisation_id" class="form-control select2">
                                <option value="">All Organisation</option>
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
                                        @foreach ($result['organisation']  as $key=>$organisations)
                                        <option value="{{ $organisations->id }}" @if($selectOrg == $organisations->id) selected @endif >{{ $organisations->company_name }}</option>
                                        @endforeach
                                    @endif
                                @endif
                            </select>
                        </div>

                        <!-- filter hide -->
                        <div style="display:none">
                          <input class="form-control" id="name" name="name" type="text" placeholder="Name" value="{{ request()->has('name') ? request()->get('name') : '' }}">
                          <input class="form-control" id="ID" name="ID" type="text" placeholder="ID" value="{{ request()->has('ID') ? request()->get('ID') : '' }}">
                          <input class="form-control" id="brn_no" name="brn_no" type="text" placeholder="BRN" value="{{ request()->has('brn_no') ? request()->get('brn_no') : '' }}">
                          <input class="form-control" id="roc_no" name="roc_no" type="text" placeholder="ROC" value="{{ request()->has('roc_no') ? request()->get('roc_no') : '' }}">
                          
                        </div>
                        <!-- end filter -->

                        <div class="col-xs-2">
                          <button type="submit" class="btn btn-primary filterButton">Filter</button>
                        </div>

                    {!! Form::close() !!}
                  </div>
                </div>
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
                  <table id="organisation" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style="vertical-align: top;">Date</th>
                        <th style="vertical-align: top;">Campaign</th>
                        <th style="vertical-align: top;">Status</th>
                        <th style="vertical-align: top;">Organisation</th>
                        <th style="vertical-align: top;">Sale Advisor</th>
                        <th style="vertical-align: top;">Name</th>
                        <th style="vertical-align: top;">Email</th>
                        <th style="vertical-align: top;">Contact</th>        
                      </tr>
                    </thead>
                    <tbody>
                        @if (count($result['campaign_response']) > 0)
                            @foreach($result['campaign_response'] as $campaign_responses)
                            <tr>
                                <td>{{ $campaign_responses->created_at }}</td>
                                <td>{{ $campaign_responses->campaign_name }}</td>
                                <td>
                                @if  ($campaign_responses->status == "1") <strong class="badge bg-green"> {{ trans('labels.Enabled')  }} </strong> @else ($campaign_responses->status == 0") <strong class="badge bg-light-grey"> {{ trans('labels.Disabled')  }} </strong>  @endif 
                                </td>
                                <td>{{ $campaign_responses->org_name ?? 'Null' }}</td>
                                <td>{{ $campaign_responses->sa_name ?? 'Null' }}</td>
                                <td>{{ $campaign_responses->name }}</td>
                                <td>{{ $campaign_responses->email }}</td>
                                <td>{{ $campaign_responses->contact }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">{{ trans('labels.NoRecordFound') }}</td>
                            </tr>
                        @endif
                    </tbody>
                  </table>
                  @if (count($result['campaign_response']) > 0)
                  <div class="col-xs-12 text-right">
                    {{-- {{$result['campaign_response']->links()}} --}}
                    {!! $result['campaign_response']->appends(request()->except('page'))->render() !!}
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



<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src = "https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.0/chart.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-adapter-moment/1.0.0/chartjs-adapter-moment.min.js"></script> 



<script type="text/javascript">
  $(document).ready(function() {
    var filterbyselected = $('#filterBy').val();
    if(filterbyselected == '7day' || filterbyselected == '30day'){
      $(".customFieldFrom").css("display", "block");
      $(".customFieldTo").css("display", "none");
      $('#toDate').val('');
    }
    else if(filterbyselected == 'customdate'){
      $(".customFieldFrom").css("display", "block");
      $(".customFieldTo").css("display", "block");
    }
    else{
      $(".customFieldFrom").css("display", "none");
      $(".customFieldTo").css("display", "none");
      $('#fromDate').val('');
      $('#toDate').val('');
    }
  });

  function handleSelect(elm)
  {
    selectedValue = elm.value;  
    if(selectedValue == '7day' || selectedValue == '30day'){
      $(".customFieldFrom").css("display", "block");
      $(".customFieldTo").css("display", "none");
      $('#toDate').val('');
    }
    else if(selectedValue == 'customdate'){
      $(".customFieldFrom").css("display", "block");
      $(".customFieldTo").css("display", "block");
    }
    else{
      $(".customFieldFrom").css("display", "none");
      $(".customFieldTo").css("display", "none");
      $('#fromDate').val('');
      $('#toDate').val('');
    }
  }
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
  .filterButton{
    position: absolute;
    top: 50%;
    transform: translateY(74%);
  }
  .box-1{
    background: #ffd4a0;
    padding: 30px 40px;
    width: 33.33%;
    text-align: center !important; 
    vertical-align: middle !important;
  }
  .box-2{
    background: #bde0ff;
    padding: 30px 40px;
    width: 33.33%;
    text-align: center !important; 
    vertical-align: middle !important;
  }
  .box-3{
    background: #ffb1ca;
    padding: 30px 40px;
    width: 33.33%;
    text-align: center !important; 
    vertical-align: middle !important;
  }
  .box-4{
    background: #fff57c;
    padding: 30px 40px;
    width: 33.33%;
    text-align: center !important; 
    vertical-align: middle !important;
  }
  .box-5{
    background: #9cffad;
    padding: 30px 40px;
    width: 33.33%;
    text-align: center !important; 
    vertical-align: middle !important;
  }
</style>
@endsection
