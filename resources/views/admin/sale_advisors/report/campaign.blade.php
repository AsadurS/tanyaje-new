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
            <div class="col-md-12" style="margin: 0px 15px" >
                @if($user->verified==0)
                  {!! Form::open(array('url' =>"admin/sale_advisors/renew/request/$user->id", 'method'=>'post', 'class' => 'form-horizontal form-validate ', 'enctype'=>'multipart/form-data'))  !!}
                    <h3 >You are currently inactive if you want to be active please click the renew button with payslip.

                    </h3>
                    <div class="form-group">
                        <label for="payslip" class="col-sm-2 col-md-2 control-label" style="">Payslip</label>
                        <div class="col-sm-10 col-md-3">
                            <!-- <input type="file" name="profile_img" id="profile_img" class="form-control"> -->
                            <span style="display:flex;">
                                      <input type="file" name="payslip" style="display: none"
                                             accept="image/jpeg,image/png,application/pdf" id="payslip"
                                             class="form-control">
                                      <input type="text" class="form-control" id="payslip-name" readonly="readonly"
                                             aria-label="payslip" aria-describedby="button-payslip" value="">
                                      <button class="btn btn-outline-secondary filterBun" type="button"
                                              id="button-payslip">Select</button>
                                    </span>
                            <span class="help-block"
                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Upload payslip here</span>

                        </div>


                        <button type="submit"  class="btn  btn-danger filterBun">Renew</button>
                    </div>

                    {!! Form::close() !!}
                @endif
                @if($user->verified == 3)
                        <h3 >A Request Has send to Admin Please wait for confirmation</h3>
                    @endif
            </div>
            <div style="width: 50%">
                <h3> Basic Info </h3>
                <table class="table">
                      <tr>
                          <td>Start date:</td>
                          <td>{{\Carbon\Carbon::parse($user->verified_until)->format('m/d/Y')}}</td>
                      </tr>
                    <tr>
                          <td>Expired date:</td>
                          <td>{{\Carbon\Carbon::parse($user->verified_since)->format('m/d/Y')}}</td>
                      </tr>
                    <tr>
                          <td>Package:</td>
                          <td>{{$user->package}}</td>
                      </tr>
                    @if($user->payslip)
                     <tr>
                         <td></td>
                          <td> <button type="button" for="payslip" class="btn btn-info filterBun"
                                       data-toggle="modal" data-target="#myModal" style="">show
                                  payslip
                              </button></td>
                          <td>
                                  <!-- Modal -->
                                  <div id="myModal" class="modal fade" role="dialog">
                                      <div class="modal-dialog modal-lg">

                                          <!-- Modal content-->
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <button type="button" class="close"
                                                             data-dismiss="modal">&times;
                                                  </button>
                                                  <h4 class="modal-title">Modal Header</h4>
                                              </div>
                                              <div class="modal-body">

                                                  @if( strpos($user->payslip, 'pdf'))

                                                      <embed src="{{url('payslip/'.$user->payslip)}}"
                                                             frameborder="0" width="100%"
                                                             height="400px">

                                                  @else
                                                      <img src="{{url('payslip/'.$user->payslip)}}"
                                                           alt="">
                                                  @endif
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-sm btn-default "
                                                              data-dismiss="modal">Close
                                                      </button>
                                                  </div>
                                              </div>

                                          </div>
                                      </div>
                                  </div>

                          </td>
                      </tr>
                    @endif
                    @if($user->package)
                    <tr>
                         <td></td>
                          <td><button onclick="htmlToExcel('excel-table')" type="button" class="btn btn-primary filterBun"
                              data-toggle="modal"  style="">
                              Download Invoice
                              </button></td>
                      </tr>
                    @endif
                </table>
            </div>
            <br>
          <div class="box-header" style="margin-top: 50px">
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
                          @if  ($top_campaign->status == "1") <strong class="badge bg-green"> {{ trans('labels.Enabled')  }} </strong> @else ($top_campaign->status == 0) <strong class="badge bg-light-grey"> {{ trans('labels.Disabled')  }} </strong>  @endif
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
    <table class='table table-light table-striped table-bordered' id='excel-table'
               style="background-color: transparent; border:2px solid black; margin-top:15px; display: none">
            <tr>
                <td colspan="9" style="text-align: center;">
                                <span style="font-size: 24px;font-weight: bold;">TANYAJE DOT COM SDN
                                    BHD</span>(1375162-K)
                </td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: center;">
                                <span style="font-size:20px;">63, 1st Floor, Jalan SS2/2, Taman
                                    Bahagia,</span>
                </td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: center;">
                    <span style="font-size:20px;">47300 Petaling Jaya, Selangor. </span>
                </td>
            </tr>
        @php
            function addPrefixIfNeeded($number) {
                $number = strval($number); // Convert the input to a string
                $length = strlen($number);

                if ($length < 6) {
                    $prefixLength = 6 - $length;
                    $prefix = str_repeat('0', $prefixLength); // Create a prefix of zeros
                    $number = $prefix . $number; // Concatenate the prefix and the input number
                }

                return $number;
                }
        @endphp
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="4" style="text-align: center;">
                    <span style="font-size:20px; font-weight: bold;">OFFICIAL INVOICE </span>
                </td>
                <td>No.:</td>
                <td><b>SA-{{addPrefixIfNeeded($user->id)}}</b></td>

            </tr>
            <td></td>
            <tr>
                <th></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center">Your Ref.:</th>
                <th class="text-center" colspan="2"></th>
            </tr>
            <tr>
                <td></td>
                <th colspan="2" style="text-align: left" id="organisation_id_name"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th style="text-align: left" id="organisation_id_address"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center">Sales Agent:</th>
                <th class="text-center" colspan="2"></th>
            </tr>
            <tr>
                <td></td>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center">Terms:</th>
                <th class="text-center" colspan="2"></th>
            </tr>
            <tr>
                <td></td>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center">Date:</th>
                <th class="text-center" >{{date("d/m/y")}}</th>
            </tr>
            <tr>
                <th></th>
                <th colspan="5"  style="text-align: left" id="name"></th>
                <th>Page:</th>
                <th class="text-center" colspan="2">1 of 1</th>
            </tr>
            <tr>
                <th></th>
                <th colspan="5"  style="text-align: left" id="phone"></th>
                <th></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th>Item</th>
                <th></th>
                <th class="text-center">Description</th>
                <th class="text-center">Qty</th>
                <th class="text-center">UOM</th>
                <th class="text-center">U/ Price (RM)</th>
                <th class="text-center">Disc.</th>
                <th class="text-center">Total (RM)</th>
            </tr>
            <tr>
                <td></td>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th colspan="3" style="text-align: left" id="basic_full_premium_1"></th>
                <th class="text-center" id="basic_full_premium_1_1"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center" id="basic_full_premium_1_2"></th>
            </tr>
            <tr>
                <td></td>
                <th colspan="2" style="text-align: left" id="basic_full_premium_2"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>

            <tr>
                <td></td>
                <th colspan="2" style="text-align: left" id="basic_full_premium_3"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th colspan="2" style="text-align: left" id="full_premium_3"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th colspan="3" style="text-align: left" id="full_premium_4"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th colspan="2" style="text-align: left" id="full_premium_5"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th colspan="3" style="text-align: left" id="premium_5"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <td></td>
                <th colspan="2" style="text-align: left" id="premium_6"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
        </table>
        <script src="https://code.jquery.com/jquery-3.6.1.min.js"
                integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

        <script type="text/javascript">
            function htmlToExcel(table) {
                let name;

                name ="<?php echo $user->package;?>";

                document.getElementById('organisation_id_name').innerText ="<?php echo $user->user->company_name??'';?>"
                document.getElementById('organisation_id_address').innerText = "<?php echo $user->address;?>"
                document.getElementById('name').innerText = 'ATTENTION: '+"<?php echo $user->merchant_name;?>"
                document.getElementById('phone').innerText = 'TEL: '+"<?php echo $user->merchant_phone_no;?>"
                if (name == 'Basic') {
                    document.getElementById('basic_full_premium_1').innerText = '1. DIGITAL ID BASIC VERSION SUBCRIPTION'
                    document.getElementById('basic_full_premium_1_1').innerText = 1;
                    document.getElementById('basic_full_premium_1_2').innerText = 120.00;
                    document.getElementById('basic_full_premium_2').innerText = '- BROCHURE & PRICELIST'
                    document.getElementById('basic_full_premium_3').innerText = '- 360 CAR INVENTORY'
                }
                if (name == 'Full') {
                    document.getElementById('basic_full_premium_1').innerText = '1. DIGITAL ID BASIC VERSION SUBCRIPTION'
                    document.getElementById('basic_full_premium_1_1').innerText = 1;
                    document.getElementById('basic_full_premium_1_2').innerText = 360.00;
                    document.getElementById('basic_full_premium_2').innerText = '- BROCHURE & PRICELIST'
                    document.getElementById('basic_full_premium_3').innerText = '- 360 CAR INVENTORY'
                    document.getElementById('full_premium_3').innerText = '-CAMPAIGN'
                    document.getElementById('full_premium_4').innerText = '- CALL/ WHATSAPP/ LOCATION'
                    document.getElementById('full_premium_5').innerText = '- GEAR UP/ ACCESORRIES'
                }
                if (name == 'Premium') {
                    document.getElementById('basic_full_premium_1').innerText = '1. DIGITAL ID BASIC VERSION SUBCRIPTION'
                    document.getElementById('basic_full_premium_1_1').innerText = 1;
                    document.getElementById('basic_full_premium_1_2').innerText = 480.00;
                    document.getElementById('basic_full_premium_2').innerText = '- BROCHURE & PRICELIST'
                    document.getElementById('basic_full_premium_3').innerText = '- 360 CAR INVENTORY'
                    document.getElementById('full_premium_3').innerText = '-CAMPAIGN'
                    document.getElementById('full_premium_4').innerText = '- CALL/ WHATSAPP/ LOCATION'
                    document.getElementById('full_premium_5').innerText = '- GEAR UP/ ACCESORRIES'
                    document.getElementById('premium_5').innerText = '- LEADS CAPTURING SYSTEM'
                    document.getElementById('premium_6').innerText = '- NFC TRANSMITTER'
                }
                var uri = 'data:application/vnd.ms-excel;base64,'
                    ,
                    template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
                    , base64 = function (s) {
                        return window.btoa(unescape(encodeURIComponent(s)))
                    }
                    , format = function (s, c) {
                        return s.replace(/{(\w+)}/g, function (m, p) {
                            return c[p];
                        })
                    }

                if (!table.nodeType) table = document.getElementById(table)
                var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
                window.location.href = uri + base64(format(template, ctx))
            }
        </script>

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

   <script>
        document.addEventListener("DOMContentLoaded", function () {
            // profile image
            document.getElementById('button-payslip').addEventListener('click', (event) => {
                event.preventDefault();
                document.getElementById('payslip').click()
            });

        });

        document.getElementById('payslip').addEventListener('change', function (event) {
            if (event.target.files[0]) document.getElementById('payslip-name').value = event.target.files[0].name
            else document.getElementById('payslip-name').value = ''
        })
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
