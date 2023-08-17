@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.CampaignDashboard') }}</h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/sale_advisors/dashboard/')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.CampaignDashboard') }}</li>
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
                    {!! Form::open(array('url' =>'admin/sale_advisors/filtercampaignreport', 'method'=>'get', 'class' => 'form-horizontal form-validate ', 'enctype'=>'multipart/form-data')) !!}
                      <div class="form-group row">
                        <div class="col-xs-2">
                          <label for="filterBy" class="control-label" style="">{{ trans('labels.FilterBy') }}</label>
                          @php
                            $selectfilterBy = request()->has('filterBy') ? request()->get('filterBy') : '';
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

                        <!-- filter hide -->
                        <div style="display:none">
                          
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
                    <table class="table"> 
                      <tr> 
                        <th rowspan="2" class="box-1" style="background-image:url({{asset('/images/active.jpg')}});">
                          <span class="number_style">{{ count($result['total_campaigns']) }}</span><br>
                          <span class="text_style">Total Active Campaigns</span>
                        </th> 
                        <td class="box-2" style="background-image:url({{asset('/images/share.jpg')}});"><span class="number_style">{{ $result['total_share'] }}</span><br><span class="text_style">Total Shares</span></td> 
                        <td class="box-3" style="background-image:url({{asset('/images/click.jpg')}});"><span class="number_style">{{ $result['total_click'] }}</span><br><span class="text_style">Total Click To View</span></td> 
                      </tr> 
                      <tr> 
                        <td class="box-4" style="background-image:url({{asset('/images/interested.jpg')}});"><span class="number_style">{{ $result['total_interest'] }}</span><br><span class="text_style">Total Interested</span></td> 
                        <td class="box-5" style="background-image:url({{asset('/images/responds.jpg')}});"><span class="number_style">{{ $result['total_response'] }}</span><br><span class="text_style">Total Responds</span></td> 
                      </tr> 
                    </table>
                  </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                  <span style="display:flex;align-items:baseline;">
                    <h3>Top 5 Campaigns</h3>&nbsp;&nbsp;&nbsp;<a href="{{ URL::to('admin/sale_advisors/campaignfullreport')}}">VIEW ALL</a>
                  </span>
                  <table id="organisation" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style="vertical-align: top;">Status</th>
                        <th style="vertical-align: top;">Campaign</th>
                        <th style="vertical-align: top;">Share</th>
                        <th style="vertical-align: top;">Click/Open</th>
                        <th style="vertical-align: top;">Interest</th>
                        <th style="vertical-align: top;">Response</th>
                        <th style="vertical-align: top;">Action</th>           
                      </tr>
                    </thead>
                    <tbody>
                      @if (count($result['top_campaigns']) > 0)
                        @foreach($result['top_campaigns'] as $top_campaign)
                        <tr>
                          <td>
                          @if  ($top_campaign->status == "1") <strong class="badge bg-green"> {{ trans('labels.Enabled')  }} </strong> @else ($top_campaign->status == 0") <strong class="badge bg-light-grey"> {{ trans('labels.Disabled')  }} </strong>  @endif 
                          </td>
                          <td>{{ $top_campaign->campaign_name }}</td>
                          <td>{{ $top_campaign->share }}</td>
                          <td>{{ $top_campaign->click }}</td>
                          <td>{{ $top_campaign->interest }}</td>
                          <td>{{ $top_campaign->response }}</td>
                          <td><a href="{{ URL::to('admin/sale_advisors/filtercampaignfullreport?filterBy='.$selectfilterBy.'&campaign_id='.$top_campaign->campaign_id)}}">View</a></td>
                        </tr>
                        @endforeach
                      @else
                        <tr>
                          <td colspan="7">{{ trans('labels.NoRecordFound') }}</td>
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
    /* background: #ffd4a0; */
    padding: 30px 40px;
    width: 33.33%;
    text-align: center !important; 
    vertical-align: middle !important;
    background-size: cover;
    background-position: bottom;
  }
  .box-2{
    /* background: #bde0ff; */
    padding: 30px 40px;
    width: 33.33%;
    text-align: center !important; 
    vertical-align: middle !important;
    background-size: cover;
    background-position: bottom;
  }
  .box-3{
    /* background: #ffb1ca; */
    padding: 30px 40px;
    width: 33.33%;
    text-align: center !important; 
    vertical-align: middle !important;
    background-size: cover;
    background-position: bottom;
  }
  .box-4{
    /* background: #fff57c; */
    padding: 30px 40px;
    width: 33.33%;
    text-align: center !important; 
    vertical-align: middle !important;
    background-size: cover;
    background-position: bottom;
  }
  .box-5{
    /* background: #9cffad; */
    padding: 30px 40px;
    width: 33.33%;
    text-align: center !important; 
    vertical-align: middle !important;
    background-size: cover;
    background-position: bottom;
  }
  .text_style{
    font-size: 30px;
    color: #000000;
    font-weight: 300;
  }
  .number_style{
    font-size: 50px;
    color: #000000;
  }
</style>
@endsection
