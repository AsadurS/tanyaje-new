@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Basic Information </h1>

    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->

        <!-- /.row -->

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <div class="title-search pull-left">
                            <div class="col-md-12" style="margin: 0px 15px">
                                @if($user->verified==0)
                                {!! Form::open(array('url' =>"admin/sale_advisors/renew/request/$user->id", 'method'=>'post', 'class' => 'form-horizontal form-validate ', 'enctype'=>'multipart/form-data')) !!}
                                <h3>You are currently inactive if you want to be active please click the renew button with payslip.

                                </h3>

                                @endif
                                @if($user->verified == 3)
                                <h3>A Request Has send to Admin Please wait for confirmation</h3>
                                @endif
                            </div>
                        </div>


                        <!-- /.box-header -->
                        <div class="box-body">



                            <!-- filter -->

                            <!-- endfilter -->

                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="organisation" class="table table-bordered table-striped">

                                        <tr>
                                            <td>Organisation</td>
                                            <td>{{$admin->company_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Employee ID</td>
                                            <td>{{$user->merchant_emp_id}}</td>
                                        </tr>
                                        <tr>
                                            <td>Full Name</td>
                                            <td>{{$user->merchant_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Email Address</td>
                                            <td>{{$user->address}}</td>
                                        </tr>
                                        <tr>
                                            <td>Contact Number</td>
                                            <td>{{$user->merchant_phone_no}}</td>
                                        </tr>

                                        <tr>
                                            <td>Position</td>
                                            <td>{{$user->sa_position}}</td>
                                        </tr>
                                        <tr>
                                            <td>Postcode</td>
                                            <td>{{$user->merchant_postcode}}</td>
                                        </tr>
                                        <tr>
                                            <td>Current Status</td>
                                            <td>
                                                @if($user->verified==0)
                                                <strong class="badge bg-warning">Inactive </strong>
                                                @elseif($user->verified==1)
                                                <strong class="badge bg-green">Verified </strong>

                                                @elseif($user->verified==2)
                                                <strong class="badge bg-green">Active </strong>
                                                @elseif($user->verified==3)
                                                <strong class="badge bg-green">Pending </strong>
                                                @else
                                                <strong class="badge bg-light-grey">Unpublished </strong>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>State/City</td>
                                            <td>{{$user->state_name}}/{{$user->city_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Whatsapp URL</td>
                                            <td>{{$user->whatsapp_url}}</td>
                                        </tr>
                                        <tr>
                                            <td>Waze URL</td>
                                            <td>{{$user->waze_url}}</td>
                                        </tr>

                                        <td>Start date:</td>
                                        <td>{{\Carbon\Carbon::parse($user->verified_until)->format('m/d/Y')}}</td>
                                        </tr>
                                        <tr>
                                            <td>Expired date:</td>
                                            <td>{{\Carbon\Carbon::parse($user->verified_since)->format('m/d/Y')}}</td>
                                        </tr>
                                        <tr>
                         <td></td>
                          <td><button onclick="htmlToExcel('excel-table')" type="button" class="btn btn-primary filterBun"
                              data-toggle="modal"  style="">
                              Download Invoice
                              </button></td>
                      </tr>
                    <tr>
                        <td></td>
                        <td> {!! Form::open(array('url' =>"admin/sale_advisors/renew/request/$user->id", 'method'=>'post', 'class' => 'form-horizontal form-validate ', 'enctype'=>'multipart/form-data'))  !!}
                    

                    </h3>
                    <div class="form-group">
                        <label for="payslip" class="col-sm-2 col-md-2 control-label" style="">Payslip</label>
                        <div class="col-sm-10 col-md-3">
                            <!-- <input type="file" name="profile_img" id="profile_img" class="form-control"> -->
                           


                        <button type="submit"  class="btn  btn-danger filterBun">Delete Payslip</button>
                    </div>

                    {!! Form::close() !!}</td>
                    </tr>
                                    </table>
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

                name ="Basic";

                document.getElementById('organisation_id_name').innerText ="<?php echo $admin->company_name??'';?>"
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

                .filterButton {
                    position: absolute;
                    top: 50%;
                    transform: translateY(74%);
                }
            </style>
            @endsection