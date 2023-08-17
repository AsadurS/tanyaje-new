@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.titleOrgReport') }} <small>{{ trans('labels.titleOrgReport') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.titleOrgReport') }}</li>
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
                    {!! Form::open(array('url' =>'admin/filterorganisationreport', 'method'=>'get', 'class' => 'form-horizontal form-validate ', 'enctype'=>'multipart/form-data')) !!}
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
                          <input class="form-control" id="name" name="name" type="text" placeholder="Name" value="{{ request()->has('name') ? request()->get('name') : '' }}">
                          <input class="form-control" id="ID" name="ID" type="text" placeholder="ID" value="{{ request()->has('ID') ? request()->get('ID') : '' }}">
                          <input class="form-control" id="brn_no" name="brn_no" type="text" placeholder="BRN" value="{{ request()->has('brn_no') ? request()->get('brn_no') : '' }}">
                          <input class="form-control" id="roc_no" name="roc_no" type="text" placeholder="ROC" value="{{ request()->has('roc_no') ? request()->get('roc_no') : '' }}">
                          @php
                            $selectSegment = request()->has('segment_type') ? request()->get('segment_type') : '';
                          @endphp
                          <select name="segment_type" class="form-control">
                              <option value="" selected>Segments</option>
                              @if (count($result['segments']) > 0)
                                @foreach ($result['segments']  as $key=>$segment)
                                <option value="{{ $segment->segment_id }}" @if($selectSegment == $segment->segment_id) selected @endif >{{ $segment->segment_name }}</option>
                                @endforeach
                              @endif
                          </select>
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

            <div class="form-group">
              <div class="col-sm-12 col-md-12" style="padding-bottom:50px;">
                  <canvas id="myChart" width="400" height="100"></canvas>
              </div>
            </div>

            <!-- filter -->
            <div class="row">
              <div class="col-xs-12">
                <!-- search filter -->
                {!! Form::open(array('url' =>'admin/filterorganisationreport', 'method'=>'get', 'class' => 'search-filter form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                  <div class="form-group row">
                    <div class="col-xs-2">
                      <input class="form-control" id="name" name="name" type="text" placeholder="Name" value="{{ request()->has('name') ? request()->get('name') : '' }}">
                    </div>
                    <div class="col-xs-1">
                      <input class="form-control" id="ID" name="ID" type="text" placeholder="ID" value="{{ request()->has('ID') ? request()->get('ID') : '' }}">
                    </div>
                    <div class="col-xs-1">
                      <input class="form-control" id="brn_no" name="brn_no" type="text" placeholder="BRN" value="{{ request()->has('brn_no') ? request()->get('brn_no') : '' }}">
                    </div>
                    <div class="col-xs-1">
                      <input class="form-control" id="roc_no" name="roc_no" type="text" placeholder="ROC" value="{{ request()->has('roc_no') ? request()->get('roc_no') : '' }}">
                    </div>
                    <!-- <div class="col-xs-2">
                      <select name="segment_type" class="form-control">
                          <option value="" selected>Segments</option>
                          @if (count($result['segments']) > 0)
                            @foreach ($result['segments']  as $key=>$segment)
                            <option value="{{ $segment->segment_id }}" @if($selectSegment == $segment->segment_id) selected @endif >{{ $segment->segment_name }}</option>
                            @endforeach
                          @endif
                      </select>
                    </div> -->

                    <!-- hide date filter -->
                    <div style="display:none;">
                      <select class="form-control" name="filterBy" id="filterBy" onchange="javascript:handleSelect(this)">
                          <option value="">Default</option>
                          <option value="today" @if($selectfilterBy == "today") selected @endif>Today</option>
                          <option value="yesterday" @if($selectfilterBy == "yesterday") selected @endif>Yesterday</option>
                          <option value="thisweek" @if($selectfilterBy == "thisweek") selected @endif>This Week</option>
                          <option value="thismonth" @if($selectfilterBy == "thismonth") selected @endif>This Month</option>
                          <option value="7day" @if($selectfilterBy == "7day") selected @endif>7 Days</option>
                          <option value="30day" @if($selectfilterBy == "30day") selected @endif>30 Days</option>
                          <option value="customdate" @if($selectfilterBy == "customdate") selected @endif>Custom Date Range</option>
                      </select>
                      <input type="date" name="fromDate" id="fromDate" class="form-control" value="{{ request()->has('fromDate') ? request()->get('fromDate') : '' }}">
                      <input type="date" name="toDate" id="toDate" class="form-control" value="{{ request()->has('toDate') ? request()->get('toDate') : '' }}">
                    </div>
                    <!-- end date filter -->

                    <div class="col-xs-2">
                      <button type="submit" class="btn btn-primary">Search <span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                      <a href="{{ url('/admin/organisationreport') }}" class="btn btn-primary"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                    </div>
                  </div>
                {!! Form::close() !!}
              </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                  <table id="organisation" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style="vertical-align: top;">ID</th>
                        <th style="vertical-align: top;">{{ trans('labels.reportOrganisation') }}</th>
                        <th style="vertical-align: top;">{{ trans('labels.pageView') }}</th>
                        <th style="vertical-align: top;">{{ trans('labels.reportCall') }}</th>
                        <th style="vertical-align: top;">{{ trans('labels.whatsApp') }}</th>
                        <th style="vertical-align: top;">{{ trans('labels.reportShowroom') }}</th>
                        <th style="vertical-align: top;"> {{ trans('labels.reportTotal')  }} </th>           
                      </tr>
                    </thead>
                    <tbody>
                      @if (count($result['admins']) > 0)
                      @foreach($result['admins'] as $admin)
                        @if($admin->company_id != null)
                        <tr>
                          <td>{{ $admin->company_id }}</td>
                          <td>{{ $admin->company_name }}</td>
                          <td>{{ $admin->pageview }}</td>
                          <td>{{ $admin->countCall }}</td>
                          <td>{{ $admin->whatsapp }}</td>
                          <td>{{ $admin->waze }}</td>
                          <td>
                          @php
                              $total = $result['total_pageview'];
                              $pageview = $admin->pageview;
                              if($total != '0'){
                                $percentage = ($pageview/$total)*100;
                                $percentage = number_format($percentage, 2, '.', ',');
                              }
                              else{
                                $percentage = '0';
                              }
                            @endphp
                            <div class="progress-bar progress-bar-striped" role="progressbar" style="width:{{ $percentage ? $percentage : '0' }}%;height: 20px;margin-right:5px;" aria-valuemin="0" aria-valuemax="100"></div> {{ $percentage ? $percentage : '0' }}%
                          </td>
                        </tr>
                        @endif
                      @endforeach
                      @else 
                        <tr>
                          <td colspan="7">{{ trans('labels.NoRecordFound') }}</td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                  @if (count($result['admins']) > 0)
                  <div class="col-xs-12 text-right">
                    {{-- {{$result['admins']->links()}} --}}
                    {!! $result['admins']->appends(request()->except('page'))->render() !!}
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

<script>
  var ctx = document.getElementById('myChart').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($result['chartsLabel']),
        datasets: [
          {
            label: 'Page View',
            data: @json($result['chartsPageview']),
            backgroundColor:'rgba(10, 52, 162, 0.1)',
            borderColor:'rgba(10, 52, 162, 1)',
            borderWidth: 4
          },
          {
            label: 'Action',
            data: @json($result['chartsCall']),
            backgroundColor:'rgba(138, 196, 252, 0.1)',
            borderColor:'rgba(138, 196, 252, 1)',
            borderWidth: 4
          }
        ]
    },
    options:{
      animation: true,
      plugins: {
        title: {
          display: true,
          text: ["Organisation's Pageview and Call Statistics", @json($result['chartsSubTitle']) ],
          font: {
            size: 14
          }
        },
        legend: {
          display: true,
          position: 'top',
          align: 'start',
          labels: {    
            usePointStyle: true,
            fillStyle:'rgba(10, 52, 162, 1)',
            boxWidth:3,
            font: {
              size: 15,
              style: 'bold',
            }
          }
        }
      },
      scales: {
        x: {
          offset: true
        },
        y :{
          min : 0,
          stepSize: 10,
          ticks: {
              beginAtZero:true
          }
        }
      }
    }
  });
</script>

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
</style>
@endsection
