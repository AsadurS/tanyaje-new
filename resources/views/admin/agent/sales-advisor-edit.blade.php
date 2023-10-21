@extends('admin.agent.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.EditSaleAdvisor') }} <small>{{ trans('labels.EditSaleAdvisor') }}...</small></h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i
                                class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/saleAdvisor/*')}}"><i
                                class="fa fa-users"></i> {{ trans('labels.SaleAdvisor') }}</a></li>
                <li class="active">{{ trans('labels.EditSaleAdvisor') }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->

            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <!-- <div class="box-header">
            <h3 class="box-title">{{ trans('labels.addmerchants') }} </h3>
          </div> -->

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box box-info">
                                        <br>

                                        @if(session()->has('message'))
                                            <div class="alert alert-success" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close"><span aria-hidden="true">&times;</span>
                                                </button>
                                                {{ session()->get('message') }}
                                            </div>
                                        @endif

                                        @if(session()->has('errorMessage'))
                                            <div class="alert alert-danger" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close"><span aria-hidden="true">&times;</span>
                                                </button>
                                                {{ session()->get('errorMessage') }}
                                            </div>
                                        @endif

                                        <!-- form start -->
                                        <div class="box-body">
                                            {!! Form::open(array('url' =>'agent/sales-advisor-update', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                            <input class="form-control" id="saleAdvisor_id" name="saleAdvisor_id"
                                                   type="hidden" value="{{$result['sale_advisor'][0]->id}}">
                                            <h4>{{ trans('labels.SaleAdvisorInfo') }}</h4>
                                            <hr>
                                            <input type="hidden" name="organisation_slug" id="organisation_slug"
                                                   value="{{ $result['sale_advisor'][0]->slug }}">
                                            <div class="form-group">
                                                <label for="contact" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.ProfileId') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="text" name="profile_id" id="profile_id"
                                                           value="{{$result['sale_advisor'][0]->id}}"
                                                           class="form-control" readonly>
                                                </div>
                                                <label for="profile_photo" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.Organisation') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <select name="organisation_id" id="organisation_id"
                                                            class="form-control select2" onchange="edValueKeyPress()">
                                                        <option>Organisations</option>
                                                        @if (count($result['admins']) > 0)
                                                            @foreach ($result['admins']  as $key=>$admin)
                                                                @if ($result['sale_advisor'][0]->user_id)
                                                                    <option value="{{ $admin->id }}"
                                                                            @if($admin->id == $result['sale_advisor'][0]->user_id) selected @endif>{{ $admin->company_name }}</option>
                                                                @else
                                                                    <option value="{{ $admin->id }}">{{ $admin->company_name }}</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SelectOrganisationText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group" >
                                                <label style="display: none" for="profile_photo" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.ProfilePhoto') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                    <span style="display:flex;">
                                        <input type="text" id="profile_img" class="form-control" name="profile_img"
                                               readonly="readonly" aria-label="Image" aria-describedby="button-image"
                                               value="{{ $result['sale_advisor'][0]->profile_img }}" style="display: none">
                                        <button class="btn btn-outline-secondary" type="button"
                                                id="button-image-profile_img" style="display: none">Select</button>
                                    </span>
                                                    @if($result['sale_advisor'][0]->profile_img)
                                                        <br>
                                                        @if (file_exists(public_path('/images/sale-advisor/'.$result['sale_advisor'][0]->profile_img)))
                                                            <img src="{{asset('/images/sale-advisor/'.$result['sale_advisor'][0]->profile_img)}}"
                                                                 width="50%" alt="main image">
                                                        @else
                                                            <img src="{{ $result['sale_advisor'][0]->profile_img }}"
                                                                 width="100px" alt="">
                                                        @endif
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <label for="contact" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorEmpID') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="text" name="merchant_emp_id" id="merchant_emp_id"
                                                           value="{{$result['sale_advisor'][0]->merchant_emp_id}}"
                                                           class="form-control">
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorEmpIDText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                                <label for="name" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorName') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="text" name="merchant_name" id="merchant_name"
                                                           class="form-control field-validate"
                                                           value="{{$result['sale_advisor'][0]->merchant_name}}"
                                                           onInput="edValueKeyPress()">
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorNameText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorEmail') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    {!! Form::text('merchant_email',$result['sale_advisor'][0]->merchant_email, array('class'=>'form-control field-validate', 'id'=>'merchant_email')) !!}
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorEmailText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                                <label for="contact" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorContactNo') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="text" name="merchant_phone_no" id="merchant_phone_no"
                                                           class="form-control" onInput="edValueKeyPress()"
                                                           value="{{ $result['sale_advisor'][0]->merchant_phone_no }}">
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorContactNoText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="position" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorPosition') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    {!! Form::text('sa_position',$result['sale_advisor'][0]->sa_position, array('class'=>'form-control', 'id'=>'sa_position')) !!}
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorPositionText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                                <label for="address" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.Address') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <!-- {!! Form::text('address',$result['sale_advisor'][0]->address, array('class'=>'form-control', 'id'=>'address')) !!} -->
                                                    <textarea name="address" id="address"
                                                              class="form-control">{!! $result['sale_advisor'][0]->address !!}</textarea>
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.AddressText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display: none">
                                                <label for="verification" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.Verification') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <select class="form-control" name="verified" id="verified">
                                                        <option value="2"
                                                                @if($result['sale_advisor'][0]->verified == '2') selected @endif>
                                                            N/A
                                                        </option>
                                                        <option value="1"
                                                                @if($result['sale_advisor'][0]->verified == '1') selected @endif>
                                                            Verified
                                                        </option>
                                                        <option value="0"
                                                                @if($result['sale_advisor'][0]->verified == '0') selected @endif>
                                                            Unverified
                                                        </option>
                                                    </select>
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.VerificationText') }}</span>
                                                </div>
                                                <label for="contact" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorPostcode') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="text" name="merchant_postcode" id="merchant_postcode"
                                                           value="{{$result['sale_advisor'][0]->merchant_postcode}}"
                                                           class="form-control">
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorPostcodeText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="verification" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.VerifiedSince') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    @if($result['sale_advisor'][0]->verified_since)
                                                        <input type="date" class="form-control" name="verified_since"
                                                               id="verified_since"
                                                               value="{{ Carbon\Carbon::parse($result['sale_advisor'][0]->verified_since)->format('Y-m-d') }}">
                                                    @else
                                                        <input type="date" class="form-control" name="verified_since"
                                                               id="verified_since">
                                                    @endif
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.VerifiedSinceText') }}</span>
                                                </div>
                                                <label for="state" class="col-sm-2 col-md-2 control-label" style="">State/City</label>
                                                <div class="col-sm-10 col-md-3" style="display:flex !important;">
                                                    <select name="state_id" id="state_id" class="form-control select2"
                                                            style="width:50% !important;margin: 0px 3px;">
                                                        <option>State</option>
                                                        @if (count($result['state']) > 0)
                                                            @foreach ($result['state']  as $key=>$states)
                                                                <option value="{{ $states->state_id }}"
                                                                        @if($result['sale_advisor'][0]->state_id == $states->state_id) selected @endif>{{ $states->state_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <select name="city_id" id="city_id" class="form-control select2"
                                                            style="width:50% !important;margin: 0px 3px;">
                                                        <option>City</option>
                                                        @if (count($result['city']) > 0)
                                                            @foreach ($result['city']  as $key=>$cities)
                                                                <option value="{{ $cities->city_id }}"
                                                                        @if($result['sale_advisor'][0]->city_id == $cities->city_id) selected @endif>{{ $cities->city_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="waze_url" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.VerifiedUntil') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    @if($result['sale_advisor'][0]->verified_until)
                                                        <input type="date" id="verified-until" class="form-control"
                                                               name="verified_until"
                                                               value="{{ Carbon\Carbon::parse($result['sale_advisor'][0]->verified_until)->format('Y-m-d') }}">
                                                    @else
                                                        <input type="date" id="verified-until" class="form-control"
                                                               name="verified_until" id="verified_until">
                                                    @endif
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.VerifiedUntilText') }}</span>
                                                </div>
                                                <label for="whatsapp_url" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.WhatsappUrl') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input class="form-control" name="whatsapp_url" id="lblValue"
                                                           style="overflow: auto;"
                                                           value="{{ $result['sale_advisor'][0]->whatsapp_url }}"
                                                           readonly>
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    <button type="button" onclick="edValueKeyPress()">Refresh the link</button></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="profile_url" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaProfileUrl') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="hidden" class="form-control" name="sa_profile_url"
                                                           id="lblValue3" style="overflow: auto;"
                                                           value="{{ $result['sale_advisor'][0]->sa_profile_url }}"
                                                           readonly>
                                                    <input class="form-control" name="sa_profile_fullurl"
                                                           id="lblValue3full" style="overflow: auto;"
                                                           value="{{ URL::to($result['sale_advisor'][0]->sa_profile_url) }}"
                                                           readonly>
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    <button type="button" onclick="edValueKeyPress()">Refresh the link</button></span>
                                                </div>
                                                <label for="waze_url" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.WazeUrl') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input class="form-control" name="waze_url" id="waze_url"
                                                           style="height: 60px;overflow: auto;"
                                                           value="{{ $result['sale_advisor'][0]->waze_url }}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="waze_url" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ 'Package'  }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <select name="package" id="package"
                                                            class="form-control field-validate select2">
                                                        <option>select Package</option>
                                                        <option @if($result['sale_advisor'][0]->package == 'Basic') selected
                                                                @endif value="Basic">Basic
                                                        </option>
                                                        <option @if($result['sale_advisor'][0]->package == 'Full') selected
                                                                @endif  value="Full">Full
                                                        </option>
                                                        <option @if($result['sale_advisor'][0]->package == 'Premium') selected
                                                                @endif value="Premium">Premium
                                                        </option>
                                                        <option @if($result['sale_advisor'][0]->package == 'Plain Format') selected
                                                                @endif value="Plain Format">Plain Format
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name"
                                                       class="col-sm-2 col-md-2 control-label">{{ trans('labels.changePassword') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    {!! Form::checkbox('changePassword', 'yes', null, ['class' => '', 'id'=>'change-passowrd']) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name"
                                                       class="col-sm-2 col-md-2 control-label">{{ trans('labels.Password') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    {!! Form::password('password', array('class'=>'form-control', 'id'=>'password')) !!}
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.PasswordText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                                <div class="col-sm-2 col-md-2 control-label">
                                                    <button type="button" onclick="htmlToExcel('excel-table')"
                                                            class="btn btn-info btn-sm">invoice
                                                    </button>
                                                </div>

                                            </div>
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
                                      <button class="btn btn-outline-secondary" type="button"
                                              id="button-payslip">Select</button>
                                    </span>
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Upload payslip here</span>

                                                </div>
                                                @if($result['sale_advisor'][0]->payslip)
                                                    <button type="button" for="payslip" class="btn btn-info"
                                                            data-toggle="modal" data-target="#myModal" style="">show
                                                        payslip
                                                    </button>

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
                                                                    @if( strpos($result['sale_advisor'][0]->payslip, 'pdf'))
                                                                        <embed src="{{url('payslip/'.$result['sale_advisor'][0]->payslip)}}"
                                                                               frameborder="0" width="100%"
                                                                               height="400px">
                                                                    @else
                                                                        <img src="{{url('payslip/'.$result['sale_advisor'][0]->payslip)}}"
                                                                             alt="">
                                                                    @endif
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default"
                                                                                data-dismiss="modal">Close
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <br>
                                            <h4 style="display: none">{{ trans('labels.LandingPage') }}</h4>
                                            <hr>
                                            <div class="form-group" style="display: none">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="generate_qr" class="col-sm-2 col-md-6 control-label"
                                                               style="">{{ trans('labels.GenerateQRCode') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="generate_qr" value="1"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->generate_qr == '1') checked @endif>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="generate_qr" value="0"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->generate_qr == '0' || $result['sale_advisor'][0]->generate_qr == null) checked @endif>
                                                                &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to generate QR Code or not.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="display_qr" class="col-sm-2 col-md-6 control-label"
                                                               style="">{{ trans('labels.QRCode') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="display_qr" value="1"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->display_qr == '1') checked @endif>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="display_qr" value="0"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->display_qr == '0' || $result['sale_advisor'][0]->display_qr == null) checked @endif>
                                                                &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display QR Code or not.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="contactMe" class="col-sm-2 col-md-6 control-label"
                                                               style="">{{ trans('labels.ContactMe') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="contactMe" value="1"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->contactMe == '1') checked @endif>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="contactMe" value="0"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->contactMe == '0' || $result['sale_advisor'][0]->contactMe == null) checked @endif>
                                                                &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Contact Me or not.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="showMe" class="col-sm-2 col-md-6 control-label"
                                                               style="">{{ trans('labels.ShowMe') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="showMe" value="1"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->showMe == '1') checked @endif>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="showMe" value="0"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->showMe == '0' || $result['sale_advisor'][0]->showMe == null) checked @endif>
                                                                &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Show Me or not.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="askMe" class="col-sm-2 col-md-6 control-label"
                                                               style="">{{ trans('labels.AskMe') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="askMe" value="1"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->askMe == '1') checked @endif>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="askMe" value="0"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->askMe == '0' || $result['sale_advisor'][0]->askMe == null) checked @endif>
                                                                &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Ask Me or not.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="keepMe" class="col-sm-2 col-md-6 control-label"
                                                               style="">{{ trans('labels.KeepMe') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="keepMe" value="1"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->keepMe == '1') checked @endif>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="keepMe" value="0"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->keepMe == '0' || $result['sale_advisor'][0]->keepMe == null) checked @endif>
                                                                &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Keep Me or not.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="keepMe" class="col-sm-2 col-md-6 control-label"
                                                               style="">{{ trans('labels.Campaigns') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="campaign" value="1"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->campaign == '1') checked @endif>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="campaign" value="0"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->campaign == '0' || $result['sale_advisor'][0]->campaign == null) checked @endif>
                                                                &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Campaign or not.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="whatsappDefaultMessage"
                                                               class="col-sm-2 col-md-3 control-label"
                                                               style="">{{ trans('labels.WhatsappDefaultMessage') }}</label>
                                                        <div class="col-sm-10 col-md-7">
                                                            <textarea name="whatsappDefaultMessage"
                                                                      id="whatsappDefaultMessage"
                                                                      class="form-control">{!! $result['sale_advisor'][0]->whatsapp_default_message !!}</textarea>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.WhatsappDefaultMessageText') }}</span>
                                                            <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="keepMe" class="col-sm-2 col-md-3 control-label"
                                                               style="">{{ trans('labels.LandingPageVersion') }}</label>
                                                        <div class="col-sm-10 col-md-7">
                                                            <label class=" control-label">
                                                                <input type="radio" name="landingpage_version" value="0"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->landingpage_version == '0') checked @endif>
                                                                &nbsp;{{ trans('labels.StandardLandingPage') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="landingpage_version" value="1"
                                                                       class="flat-red"
                                                                       @if($result['sale_advisor'][0]->landingpage_version == '1') checked @endif>
                                                                &nbsp;{{ trans('labels.LiteLandingPage') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Please choose the Landing Page version.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="landingpage_images"
                                                               class="col-sm-2 col-md-3 control-label"
                                                               style="">{{ trans('labels.LandingPageImageLogo') }}</label>
                                                        <div class="col-sm-10 col-md-7">
                                                            <textarea name="landingpage_images" id="landingpage_images"
                                                                      class="landingpage_images form-control">{!! $result['sale_advisor'][0]->landingpage_images ?? '' !!}</textarea>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.LandingPageImageLogoText') }}</span>
                                                            <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group">
                                        <label for="list_file" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.ListOfFiles') }}</label>
                                        <div class="col-sm-10 col-md-7">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Document Name</th>
                                                </tr>
                                                </thead>
                                                <tbody class="contentAttribute">
                                                    @if (count($result['documents']) > 0)
                                                        @foreach ($result['documents']  as $key=>$document)
                                                            @if($document->user_id == $result['sale_advisor'][0]->user_id)
                                                                <tr>
                                                                    <td>
@if (file_exists(public_path('/images/organisation/documents/'.$document->attachment)))
                                                                    <a href="{{asset('images/organisation/documents/'.$document->attachment)}}" target="_blank">{{ $document->name }}</a>


                                                                @else
                                                                    <a href="{{ $document->attachment }}" target="_blank">{{ $document->name }}</a>


                                                                @endif
                                                                </td>
                                                            </tr>


                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="4">{{ trans('labels.NoRecordFound') }}</td>
                                                        </tr>


                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div> -->
                                                </div>
                                            </div>

                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit"
                                                        class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                                <a href="{{ url()->previous() }}" type="button"
                                                   class="btn btn-default">{{ trans('labels.back') }}</a>
                                            </div>
                                            <!-- /.box-footer -->
                                            {!! Form::close() !!}

                                            <h4>{{ trans('labels.Statistics') }}</h4>
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-xs-12">
                                                    {!! Form::open(array('url' =>'admin/editfiltersalesreport/'.$result['sale_advisor'][0]->id, 'method'=>'get', 'class' => 'form-horizontal form-validate ', 'enctype'=>'multipart/form-data')) !!}
                                                    <div class="form-group row">
                                                        <div class="col-xs-2">
                                                            <label for="filterBy" class="control-label"
                                                                   style="">{{ trans('labels.FilterBy') }}</label>
                                                            @php
                                                                $selectfilterBy = request()->has('filterBy') ? request()->get('filterBy') : '';
                                                            @endphp
                                                            <select class="form-control" name="filterBy" id="filterBy"
                                                                    onchange="handleSelect(this)">
                                                                <option value="">Default (last 30 days)</option>
                                                                <option value="today"
                                                                        @if($selectfilterBy == "today") selected @endif>
                                                                    Today
                                                                </option>
                                                                <option value="yesterday"
                                                                        @if($selectfilterBy == "yesterday") selected @endif>
                                                                    Yesterday
                                                                </option>
                                                                <option value="thisweek"
                                                                        @if($selectfilterBy == "thisweek") selected @endif>
                                                                    This Week
                                                                </option>
                                                                <option value="thismonth"
                                                                        @if($selectfilterBy == "thismonth") selected @endif>
                                                                    This Month
                                                                </option>
                                                                <option value="7day"
                                                                        @if($selectfilterBy == "7day") selected @endif>7
                                                                    Days
                                                                </option>
                                                                <option value="30day"
                                                                        @if($selectfilterBy == "30day") selected @endif>
                                                                    30 Days
                                                                </option>
                                                                <option value="customdate"
                                                                        @if($selectfilterBy == "customdate") selected @endif>
                                                                    Custom Date Range
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <div class="col-xs-2 customFieldFrom" style="display:none;">
                                                            <label for="fromDate" class="control-label">From</label>
                                                            <input type="date" name="fromDate" id="fromDate"
                                                                   class="form-control"
                                                                   value="{{ request()->has('fromDate') ? request()->get('fromDate') : '' }}">
                                                        </div>

                                                        <div class="col-xs-2 customFieldTo" style="display:none;">
                                                            <label for="toDate" class="control-label">To</label>
                                                            <input type="date" name="toDate" id="toDate"
                                                                   class="form-control"
                                                                   value="{{ request()->has('toDate') ? request()->get('toDate') : '' }}">
                                                        </div>

                                                        <div class="col-xs-2">
                                                            <button type="submit" class="btn btn-primary filterButton">
                                                                Filter
                                                            </button>
                                                        </div>

                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="">
                                                        <canvas id="myChart" width="400" height="100"></canvas>
                                                    </div>
                                                    <br>
                                                </div>

                                                <div class="form-group">
                                                    <div class="">
                                                        <table id="example1" class="table table-bordered table-striped"
                                                               style="text-align:center;">
                                                            <thead>
                                                            <tr>
                                                                <th style="vertical-align:top;">Pageview</th>
                                                                <th style="vertical-align:top;">Call</th>
                                                                <th style="vertical-align:top;">Whastapp</th>
                                                                <th style="vertical-align:top;">Showroom Location</th>
                                                                <th style="vertical-align:top;">Brochure</th>
                                                                <th style="vertical-align:top;">Price List</th>
                                                                <th style="vertical-align:top;">Promotion</th>
                                                                <th style="vertical-align:top;">Conversion (Promotion
                                                                    Redemption)
                                                                </th>
                                                                <th style="vertical-align:top;">Consersion (Actions)
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody class="contentAttribute">
                                                            @if (count($result['admins']) > 0)
                                                                <tr>
                                                                    <td>{{ $result['admins_sa'][0]->pageview }}</td>
                                                                    <td>{{ $result['admins_sa'][0]->countCall }}</td>
                                                                    <td>{{ $result['admins_sa'][0]->whatsapp }}</td>
                                                                    <td>{{ $result['admins_sa'][0]->waze }}</td>
                                                                    <td>{{ $result['admins_sa'][0]->brochure }}</td>
                                                                    <td>{{ $result['admins_sa'][0]->pricelist }}</td>
                                                                    <td>{{ $result['admins_sa'][0]->promotion }}</td>
                                                                    <td>{{ $result['admins_sa'][0]->redemption }}</td>
                                                                    <td>{{ $result['admins_sa'][0]->action }}</td>
                                                                </tr>
                                                            @else
                                                                <tr>
                                                                    <td colspan="9">{{ trans('labels.NoRecordFound') }}</td>
                                                                </tr>
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

                <!-- Main row -->

                <!-- /.row -->
        </section>
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
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="4" style="text-align: center;">
                    <span style="font-size:20px; font-weight: bold;">OFFICIAL INVOICE </span>
                </td>
                <td>No.:</td>
                <td><b>SA-{{addPrefixIfNeeded($result['sale_advisor'][0]->id)}}</b></td>

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
        <!-- /.content -->
    </div>

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
            <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"> -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
        $('.multi-field-wrapper').each(function () {
            var $wrapper = $('.multi-fields', this);
            $(".add-field", $(this)).click(function (e) {
                $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
            });
            $('.multi-field .remove-field', $wrapper).click(function () {
                if ($('.multi-field', $wrapper).length > 1)
                    $(this).parent('.multi-field').remove();
            });
        });

        function edValueKeyPress() {
            var edValue = document.getElementById("merchant_phone_no");
            var s = edValue.value;

            var lblValue = document.getElementById("lblValue");
            lblValue.value = "https://wa.me/" + s;

            var edValue2 = document.getElementById("organisation_slug");
            var s2 = edValue2.value;

            var organisation = $("#organisation_id option:selected").text();
            let organisation_slug = organisation.replace(/ /g, '-');

            var lblValue3 = document.getElementById("lblValue3");
            lblValue3.value = "/sale-advisor/" + organisation_slug + "/" + s2;

            var base_url = window.location.origin;
            var lblValue3full = document.getElementById("lblValue3full");
            lblValue3full.value = base_url + "/sale-advisor/" + organisation_slug + "/" + s2;

        }
    </script>

    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
        });
    </script>
    <div>
        <div id="fm"></div>
    </div>
    <script type="text/javascript">
        // CKEDITOR
        var editor = CKEDITOR.replace('landingpage_images', {filebrowserImageBrowseUrl: '/file-manager/ckeditor'});
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.0/chart.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-adapter-moment/1.0.0/chartjs-adapter-moment.min.js"></script>  -->
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
                        backgroundColor: 'rgba(10, 52, 162, 0.1)',
                        borderColor: 'rgba(10, 52, 162, 1)',
                        borderWidth: 4
                    },
                    {
                        label: 'Action',
                        data: @json($result['chartsCall']),
                        backgroundColor: 'rgba(138, 196, 252, 0.1)',
                        borderColor: 'rgba(138, 196, 252, 1)',
                        borderWidth: 4
                    }

                ]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: [@json($result['sale_advisor'][0]->merchant_name) +"'s Statistics", @json($result['chartsSubTitle']) ],
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
                            fillStyle: 'rgba(10, 52, 162, 1)',
                            boxWidth: 3,
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
                    y: {
                        min: 0,
                        stepSize: 10,
                        ticks: {
                            beginAtZero: true
                        }
                    }
                }
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            var filterbyselected = $('#filterBy').val();
            if (filterbyselected == '7day' || filterbyselected == '30day') {
                $(".customFieldFrom").css("display", "block");
                $(".customFieldTo").css("display", "none");
                $('#toDate').val('');
            } else if (filterbyselected == 'customdate') {
                $(".customFieldFrom").css("display", "block");
                $(".customFieldTo").css("display", "block");
            } else {
                $(".customFieldFrom").css("display", "none");
                $(".customFieldTo").css("display", "none");
                $('#fromDate').val('');
                $('#toDate').val('');
            }
        });

        function handleSelect(elm) {
            selectedValue = elm.value;
            if (selectedValue == '7day' || selectedValue == '30day') {
                $(".customFieldFrom").css("display", "block");
                $(".customFieldTo").css("display", "none");
                $('#toDate').val('');
            } else if (selectedValue == 'customdate') {
                $(".customFieldFrom").css("display", "block");
                $(".customFieldTo").css("display", "block");
            } else {
                $(".customFieldFrom").css("display", "none");
                $(".customFieldTo").css("display", "none");
                $('#fromDate').val('');
                $('#toDate').val('');
            }
        }
    </script>
    <!-- <style>
        #myChart {
            height: 500px !important;
            width: 700px !important;
        }
    </style> -->



    <!-- multiple standalone button -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // profile image
            document.getElementById('button-image-profile_img').addEventListener('click', (event) => {
                event.preventDefault();

                inputId = 'profile_img';

                window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
            });
        });

        // input
        let inputId = '';

        // set file link
        function fmSetLink($url) {
            document.getElementById(inputId).value = $url;
        }
    </script>
    <script>
        $(document).ready(function () {

            $('#verified-until').on('change', function () {
                let selectedDate = $(this).val().split('-');

                //let todayDate = new Date().getDate();
                let todayDate = new Date().toLocaleDateString().split('/').reverse();
                if (Number(selectedDate[0]) > Number(todayDate[0])) {
                    document.getElementById('verified').value = 1
                } else if (Number(selectedDate[0]) === Number(todayDate[0])) {
                    if (Number(selectedDate[1]) > Number(todayDate[2])) {
                        document.getElementById('verified').value = 1
                    } else if (Number(selectedDate[1]) === Number(todayDate[2])) {
                        if (Number(selectedDate[2]) >= Number(todayDate[1])) {
                            document.getElementById('verified').value = 1
                        }
                    }
                }

                // You can perform any desired actions with the selected date here
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
            integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <script type="text/javascript">
        function htmlToExcel(table) {
            let name;
            let admins = "<?php  $result['admins']; ?>"
            console.log(admins,123)

            name = document.getElementById('package').value;
            var optionElement = document.getElementById('organisation_id').options[document.getElementById('organisation_id').selectedIndex];
            var optionName = optionElement.textContent;
            document.getElementById('organisation_id_name').innerText ="Dealer name: "+  optionName;
            document.getElementById('organisation_id_address').innerText = document.getElementById('address').value
            document.getElementById('name').innerText = 'ATTENTION: '+document.getElementById('merchant_name').value
            document.getElementById('phone').innerText = 'TEL: '+document.getElementById('merchant_phone_no').value
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
        .filterButton {
            position: absolute;
            top: 50%;
            transform: translateY(74%);
        }
    </style>
@endsection
