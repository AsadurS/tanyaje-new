@extends('admin.agent.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.AddSaleAdvisor') }} <small>{{ trans('labels.AddSaleAdvisor') }}...</small></h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i
                                class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/saleAdvisor/*')}}"><i
                                class="fa fa-users"></i> {{ trans('labels.SaleAdvisor') }}</a></li>
                <li class="active">{{ trans('labels.AddSaleAdvisor') }}</li>
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
                                            {!! Form::open(array('url' =>'agent/sales-advisor-insert', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                            @csrf
                                            <!-- <input class="form-control" id="merchant_name" name="user_id" type="hidden" value=""> -->
                                            <h4>{{ trans('labels.SaleAdvisorInfo') }}</h4>
                                            <hr>
                                            <label for="profile_photo" class="col-sm-2 col-md-2 control-label"
                                                   s>{{ trans('labels.Organisation') }}</label>
                                            <div class="col-sm-10 col-md-3">
                                                <select name="organisation_id" id="organisation_id"
                                                        class="form-control field-validate select2">
                                                    <option>Organisations</option>
                                                    @if (count($result['admins']) > 0)
                                                        @foreach ($result['admins']  as $key=>$admin)
                                                            @if ($admin->company_name)
                                                                <option value="{{ $admin->id }}"
                                                                >{{ $admin->company_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span class="help-block"
                                                      style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SelectOrganisationText') }}</span>
                                                <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                            </div>
                                            <div class="form-group">
                                                <label for="profile_img" class="col-sm-2 col-md-2 control-label"
                                                       style="display: none">{{ trans('labels.UploadProfilePhoto') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <!-- <input type="file" name="profile_img" id="profile_img" class="form-control"> -->
                                                    <span style="display: none">
                                      <input type="text" id="profile_img" class="form-control" name="profile_img"
                                             readonly="readonly" aria-label="Image" aria-describedby="button-image"
                                             value="">
                                      <button class="btn btn-outline-secondary" type="button"
                                              id="button-image-profile_img">Select</button>
                                    </span>
                                                    <span class="help-block"
                                                          style="display: none">{{ trans('labels.UploadProfilePhotoText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <label for="contact" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorEmpID') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="text" name="merchant_emp_id" id="merchant_emp_id"
                                                           class="form-control">
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorEmpIDText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                                <label for="name" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorName') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    {!! Form::text('merchant_name',  '', array('class'=>'form-control field-validate', 'id'=>'merchant_name')) !!}
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorNameText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorEmail') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    {!! Form::text('merchant_email',  '', array('class'=>'form-control field-validate', 'id'=>'merchant_email')) !!}
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorEmailText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                                <label for="contact" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorContactNo') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="text" name="merchant_phone_no" id="merchant_phone_no"
                                                           class="form-control field-validate"
                                                           onInput="edValueKeyPress()">
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorContactNoText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="position" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorPassword') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    {!! Form::password('password', array('class'=>'form-control', 'id'=>'password')) !!}
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                   {{ trans('labels.PasswordText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                                <label for="address" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.Address') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <!-- {!! Form::text('address',  '', array('class'=>'form-control field-validate', 'id'=>'address')) !!} -->
                                                    <textarea name="address" id="address"
                                                              class="form-control field-validate"></textarea>
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.AddressText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="position" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorPosition') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    {!! Form::text('sa_position',  '', array('class'=>'form-control field-validate', 'id'=>'sa_position')) !!}
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorPositionText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                                <label for="contact" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.SaleAdvisorPostcode') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="text" name="merchant_postcode" id="merchant_postcode"
                                                           class="form-control field-validate">
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorPostcodeText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="state" class="col-sm-2 col-md-2 control-label" style="">State/City</label>
                                                <div class="col-sm-10 col-md-3" style="display:flex !important;">
                                                    <select name="state_id" id="state_id"
                                                            class="form-control field-validate select2"
                                                            style="width:50% !important;margin: 0px 3px;">
                                                        <option>State</option>
                                                        @if (count($result['state']) > 0)
                                                            @foreach ($result['state']  as $key=>$states)
                                                                <option value="{{ $states->state_id }}">{{ $states->state_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <select name="city_id" id="city_id"
                                                            class="form-control field-validate select2"
                                                            style="width:50% !important;margin: 0px 3px;">
                                                        <option>City</option>
                                                        @if (count($result['city']) > 0)
                                                            @foreach ($result['city']  as $key=>$cities)
                                                                <option value="{{ $cities->city_id }}">{{ $cities->city_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="verification" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.VerifiedSince') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="date" class="form-control" name="verified_since"
                                                           id="verified_since">
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.VerifiedSinceText') }}</span>
                                                </div>
                                                <label for="whatsapp_url" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.WhatsappUrl') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input class="form-control" name="whatsapp_url" id="lblValue"
                                                           style="height: 60px;overflow: auto;"
                                                           value="Link generated based on contact number" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="waze_url" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.VerifiedUntil') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="date" class="form-control" name="verified_until">
                                                    <span class="help-block"
                                                          style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.VerifiedUntilText') }}</span>
                                                </div>
                                                <label for="waze_url" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ trans('labels.WazeUrl') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input class="form-control" name="waze_url" id="waze_url"
                                                           style="height: 60px;overflow: auto;"
                                                           value="Link generated based on organisation address"
                                                           readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">

                                                <label for="waze_url" class="col-sm-2 col-md-2 control-label"
                                                       style="">{{ 'Package'  }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <select name="package" id="package"
                                                            class="form-control field-validate select2">
                                                        <option>Select Basic</option>
                                                        <option value="Basic">Basic</option>
                                                        <option value="Full">Full</option>
                                                        <option value="Premium">Premium</option>
                                                        <option value="Plain Format">Plain Format</option>
                                                    </select>
                                                </div>
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
                                            </div>


                                            <br>
                                            <h4 style="display: none">{{ trans('labels.LandingPage') }}</h4>
                                            <hr>
                                            <div class="form-group" style="display: none">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="generate_qr" class="col-sm-2 col-md-5 control-label"
                                                               style="">{{ trans('labels.GenerateQRCode') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="generate_qr" value="1"
                                                                       class="flat-red" checked>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="generate_qr" value="0"
                                                                       class="flat-red"> &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to generate QR Code or not.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="display_qr" class="col-sm-2 col-md-5 control-label"
                                                               style="">{{ trans('labels.QRCode') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="display_qr" value="1"
                                                                       class="flat-red" checked>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="display_qr" value="0"
                                                                       class="flat-red"> &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display QR Code or not.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="contactMe" class="col-sm-2 col-md-5 control-label"
                                                               style="">{{ trans('labels.ContactMe') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="contactMe" value="1"
                                                                       class="flat-red" checked>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="contactMe" value="0"
                                                                       class="flat-red"> &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Contact Me or not.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="showMe" class="col-sm-2 col-md-5 control-label"
                                                               style="">{{ trans('labels.ShowMe') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="showMe" value="1"
                                                                       class="flat-red" checked>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="showMe" value="0"
                                                                       class="flat-red"> &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Show Me or not.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="askMe" class="col-sm-2 col-md-5 control-label"
                                                               style="">{{ trans('labels.AskMe') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="askMe" value="1"
                                                                       class="flat-red" checked>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="askMe" value="0"
                                                                       class="flat-red"> &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Ask Me or not.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="keepMe" class="col-sm-2 col-md-5 control-label"
                                                               style="">{{ trans('labels.KeepMe') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="keepMe" value="1"
                                                                       class="flat-red" checked>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="keepMe" value="0"
                                                                       class="flat-red"> &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Keep Me or not.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="campaign" class="col-sm-2 col-md-5 control-label"
                                                               style="">{{ trans('labels.Campaigns') }}</label>
                                                        <div class="col-sm-10 col-md-6">
                                                            <label class=" control-label">
                                                                <input type="radio" name="campaign" value="1"
                                                                       class="flat-red" checked>
                                                                &nbsp;{{ trans('labels.Yes') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="campaign" value="0"
                                                                       class="flat-red"> &nbsp;{{ trans('labels.No') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Campaigns or not.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <label for="whatsappDefaultMessage"
                                                               class="col-sm-2 col-md-2 control-label"
                                                               style="">{{ trans('labels.WhatsappDefaultMessage') }}</label>
                                                        <div class="col-sm-10 col-md-8">
                                                            <textarea name="whatsappDefaultMessage"
                                                                      id="whatsappDefaultMessage" class="form-control">Hi {advisor name}, i’m interested to know more about your product listed in your Tanya-Je Digital MU. Please contact me.</textarea>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.WhatsappDefaultMessageText') }}</span>
                                                            <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="name" class="col-sm-2 col-md-2 control-label"
                                                               style="">{{ trans('labels.LandingPageVersion') }}</label>
                                                        <div class="col-sm-10 col-md-8">
                                                            <label class=" control-label">
                                                                <input type="radio" name="landingpage_version" value="0"
                                                                       class="flat-red" checked>
                                                                &nbsp;{{ trans('labels.StandardLandingPage') }}
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                            <label class=" control-label">
                                                                <input type="radio" name="landingpage_version" value="1"
                                                                       class="flat-red">
                                                                &nbsp;{{ trans('labels.LiteLandingPage') }}
                                                            </label>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Please choose the Landing Page version.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="landingpage_images"
                                                               class="col-sm-2 col-md-2 control-label"
                                                               style="">{{ trans('labels.LandingPageImageLogo') }}</label>
                                                        <div class="col-sm-10 col-md-8">
                                                            <textarea name="landingpage_images" id="landingpage_images"
                                                                      class="landingpage_images form-control"></textarea>
                                                            <span class="help-block"
                                                                  style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.LandingPageImageLogoText') }}</span>
                                                            <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                        </div>
                                                    </div>
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
        <!-- /.content -->
    </div>
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"> -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
        });
    </script>
    <script type="text/javascript">
        var editor = CKEDITOR.replace('landingpage_images', {
            filebrowserImageBrowseUrl: '/file-manager/ckeditor',
            customConfig: '/js/ckeditor.config.js'
        });
    </script>
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
        }
    </script>
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
@endsection
