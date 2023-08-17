@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.AddOrganisation') }} <small>{{ trans('labels.AddOrganisation') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li><a href="{{ URL::to('admin/merchants')}}"><i class="fa fa-users"></i> {{ trans('labels.Organisation') }}</a></li>
      <li class="active">{{ trans('labels.AddOrganisation') }}</li>
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

                        <!-- form start -->
                         <div class="box-body">
                            {!! Form::open(array('url' =>'admin/addnewmerchant', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                            
                            <div class="form-group">
                              <div class="col-md-6">
                                <hr>
                                <h4>{{ trans('labels.BasicInfo') }} </h4>
                                <hr>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.FirstName') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    <input type="text" name="first_name" id="first_name" class="form-control field-validate">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.FirstNameText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.LastName') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    <input type="text" name="last_name" id="last_name" class="form-control field-validate">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.lastNameText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Telephone') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    {!! Form::text('phone',  '', array('class'=>'form-control', 'id'=>'phone')) !!}
                                   <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                   {{ trans('labels.TelephoneText') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.ParentOrganisation') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    <select name="parent_id" id="parent_id" class="form-control select2">
                                        <option value=''>{{ trans('labels.ParentOrganisation') }}</option>
                                        @if (count($result['parent_org']) > 0)
                                            @foreach ($result['parent_org']  as $key=>$parent_org)
                                              <option value="{{ $parent_org->id }}">{{ $parent_org->company_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ParentOrganisationText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>

                                <hr>
                                <h4>{{ trans('labels.Login Info') }}</h4>
                                <hr>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.EmailAddress') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    {!! Form::text('email',  '', array('class'=>'form-control email-validate', 'id'=>'email')) !!}
                                     <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                     {{ trans('labels.EmailText') }}</span>
                                    <span class="help-block hidden"> {{ trans('labels.EmailError') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Password') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    {!! Form::password('password', array('class'=>'form-control field-validate', 'id'=>'password')) !!}
                	                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                   {{ trans('labels.PasswordText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Status') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    <select class="form-control" name="isActive">
                                          <option value="1">{{ trans('labels.Active') }}</option>
                                          <option value="0">{{ trans('labels.Inactive') }}</option>
									                  </select>
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                  {{ trans('labels.StatusText') }}</span>
                                  </div>
                                </div>
                              <!-- end col-md-6 -->
                              </div>
                              <div class="col-md-6">
                                <hr>
                                <h4>{{ trans('labels.AddressInfo') }}</h4>
                                <hr>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.CompanyName') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    <input type="text" name="company_name" id="company_name" class="form-control field-validate">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CompanyNameText') }}</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.StreetAddress') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    {!! Form::text('address',  '', array('class'=>'form-control', 'id'=>'address')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.StreetAddressText') }}</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Zip/Postal Code') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    {!! Form::text('zip',  '', array('class'=>'form-control', 'id'=>'zip')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.Zip/Postal Code Text') }}</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.City') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    {!! Form::text('city',  '', array('class'=>'form-control', 'id'=>'city')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CityText') }}</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Country') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    <select class="form-control select2" name="country" id="entry_country_id">
                                        <option value="">{{ trans('labels.SelectCountry') }}</option>
                                        @foreach($result['countries'] as $countries_data)
                                        <option value="{{ $countries_data->countries_id }}">{{ $countries_data->countries_name }}</option>
                                   		@endforeach
                                    </select>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CountryText') }}</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.State') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    {!! Form::text('state',  '', array('class'=>'form-control', 'id'=>'state')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.StateText') }}</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Latitude') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    {!! Form::text('latitude',  '', array('class'=>'form-control', 'id'=>'latitude')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.LatitudeText') }}</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Longitude') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    {!! Form::text('longitude',  '', array('class'=>'form-control', 'id'=>'longitude')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.LongitudeText') }}</span>
                                  </div>
                                </div>
                              <!-- end col-md-6 -->
                              </div>
                            <!-- end form-group -->
                            </div>

                            <hr>
                            <h4>{{ trans('labels.CorporateInfo') }}</h4>
                            <hr>

                            <div class="form-group">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.ROC_SCM_NO') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                      {!! Form::text('roc_scm_no','',array('class'=>'form-control', 'id'=>'ROCSCMNO')) !!}
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.ROC_SCM_NOText') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.CorporateEmail') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                      {!! Form::text('corporate_email','',array('class'=>'form-control', 'id'=>'CorporateEmail')) !!}
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.CorporateEmailText') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.SegmentType') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    <select class="form-control" name="segment_type">
                                    @if (count($result['segments']) > 0)
                                      @foreach ($result['segments']  as $key=>$segment)
                                      <option value="{{ $segment->segment_id }}">{{ $segment->segment_name }}</option>
                                      @endforeach
                                    @endif
                                    </select>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.SegmentTypeText') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">Banner</label>
                                  <div class="col-sm-10 col-md-6">
                                    <!-- <input type="file" name="banner" id="banner" class="form-control"> -->
                                    <span style="display:flex;">
                                      <input type="text" id="banner" class="form-control" name="banner" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                      <button class="btn btn-outline-secondary" type="button" id="button-image-banner">Select</button>
                                    </span>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please upload the banner</span>
                                      <div  id ="imageselected">
                                          <br>
                                          <center>OR</center>
                                          <br>
                                          <input type="color" class="form-control" name="banner_color">
                                          <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please choose the banner color</span>
                                      </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Logo') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    <!-- <input type="file" name="logo" id="logo" class="form-control"> -->
                                    <span style="display:flex;">
                                      <input type="text" id="logo" class="form-control" name="logo" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                      <button class="btn btn-outline-secondary" type="button" id="button-image-logo">Select</button>
                                    </span>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please upload the logo</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">Title Color</label>
                                  <div class="col-sm-10 col-md-6">
                                      <input type="color" class="form-control" name="title_color">
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Please choose title color</span>
                                      <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                              <!-- end col-md-6 -->
                              </div>

                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.BRN_NO') }} </label>
                                    <div class="col-sm-10 col-md-6">
                                        {!! Form::text('brn_no','',array('class'=>'form-control', 'id'=>'BRNNO')) !!}
                                        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                        {{ trans('labels.BRN_NOText') }}</span>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.CorporatePhone') }} </label>
                                    <div class="col-sm-10 col-md-6">
                                        {!! Form::text('corporate_phone','',array('class'=>'form-control', 'id'=>'CorporatePhone')) !!}
                                        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                        {{ trans('labels.CorporatePhoneText') }}</span>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Bank1') }} </label>
                                    <div class="col-sm-10 col-md-6">
                                      <select class="form-control" name="bank1">
                                            <option>Select Bank</option>
                                            @if (count($result['banks']) > 0)
                                              @foreach ($result['banks']  as $key=>$bank)
                                              <option value="{{ $bank->bank_id }}">{{ $bank->bank_name }}</option>
                                              @endforeach
                                            @endif
                                      </select>
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.Bank1Text') }}</span>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.BankAccountName1') }} </label>
                                    <div class="col-sm-10 col-md-6">
                                        {!! Form::text('bank_acc_name1','',array('class'=>'form-control', 'id'=>'BankAccountName1')) !!}
                                        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                        {{ trans('labels.BankAccountName1Text') }}</span>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.BankAccountNo1') }} </label>
                                    <div class="col-sm-10 col-md-6">
                                        {!! Form::text('bank_acc_no1','',array('class'=>'form-control', 'id'=>'BankAccountNo1')) !!}
                                        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                        {{ trans('labels.BankAccountNo1Text') }}</span>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Bank2') }} </label>
                                    <div class="col-sm-10 col-md-6">
                                      <select class="form-control" name="bank2">
                                            <option>Select Bank</option>
                                            @if (count($result['banks']) > 0)
                                              @foreach ($result['banks']  as $key=>$bank)
                                              <option value="{{ $bank->bank_id }}">{{ $bank->bank_name }}</option>
                                              @endforeach
                                            @endif
                                      </select>
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.Bank2Text') }}</span>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.BankAccountName2') }} </label>
                                    <div class="col-sm-10 col-md-6">
                                        {!! Form::text('bank_acc_name2','',array('class'=>'form-control', 'id'=>'BankAccountName2')) !!}
                                        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                        {{ trans('labels.BankAccountName2Text') }}</span>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.BankAccountNo2') }} </label>
                                    <div class="col-sm-10 col-md-6">
                                        {!! Form::text('bank_acc_no2','',array('class'=>'form-control', 'id'=>'BankAccountNo2')) !!}
                                        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                        {{ trans('labels.BankAccountNo2Text') }}</span>
                                    </div>
                                  </div>
                              <!-- end col-md-6 -->
                              </div>
                            <!-- end form-group -->
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-2 control-label">{{ trans('labels.Description') }}</label>
                                <div class="col-sm-10 col-md-6">
                                    <textarea id="description" name="description" class="form-control" rows="5" maxlength="7000" onkeyup="textareaLengthCheck(this)"></textarea>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;display:inline;">
                                        {{ trans('labels.EditorText') }}
                                    </span>
                                    <span id="lblRemainingCount" style="float:right;display:inline;"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-2 control-label">{{ trans('labels.Opening Hours') }}</label>
                                <div class="col-sm-10 col-md-6">
                                    <textarea id="opening_hours" name="opening_hours" class="form-control" rows="5" maxlength="7000" onkeyup="textareaLengthCheck(this)"></textarea>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;display:inline;">
                                        {{ trans('labels.OpeningHoursText') }}
                                    </span>
                                    <span id="lblRemainingCount" style="float:right;display:inline;"></span>
                                </div>
                            </div>

                            
                            <hr>
                            <h4>{{ trans('labels.LandingPage') }}</h4>
                            <hr>
                            <div class="form-group">
                                <label for="template_type" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.SelectTemplate') }}</label>
                                <div class="col-sm-10 col-md-4">
                                    <select class="form-control" name="template_type">
                                      @if (count($result['templates']) > 0)
                                        @foreach ($result['templates']  as $key=>$template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                        @endforeach
                                      @endif
								                  	</select>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.SelectTemplateText') }}</span>
                                </div>
                            </div>

                            <!-- default doc -->

                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-2 control-label">Price List Doc</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="file" name="pricelist" id="pricelist" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="pricelist" class="form-control" name="pricelist" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-pricelist">Select</button>
                                </span>
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please upload the Price List</span>
                              </div>
                              <label for="name" class="col-sm-1 col-md-1 control-label">Price List Logo/Image</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="file" name="pricelist_logo" id="pricelist_logo" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="pricelist_logo" class="form-control" name="pricelist_logo" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-pricelist_logo">Select</button>
                                </span>
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please upload the Price List Logo/Image.</span>
                              </div>
                            </div>
                            <!-- extra -->
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-2 control-label">Extra PriceList Label</label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text('extra_pricelist_label','',array('class'=>'form-control', 'id'=>'extra_pricelist_label')) !!}
                              </div>
                              <label for="name" class="col-sm-1 col-md-1 control-label">Extra PriceList Logo/Image</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="file" name="extra_pricelist_logo" id="extra_pricelist_logo" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="extra_pricelist_logo" class="form-control" name="extra_pricelist_logo" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-extra_pricelist_logo">Select</button>
                                </span>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-2 control-label">Promotion Doc</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="file" name="promotion" id="promotion" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="promotion" class="form-control" name="promotion" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-promotion">Select</button>
                                </span>
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please upload the Promotion List</span>
                              </div>
                              <label for="name" class="col-sm-1 col-md-1 control-label">Promotion Logo/Image</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="file" name="promotion_logo" id="promotion_logo" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="promotion_logo" class="form-control" name="promotion_logo" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-promotion_logo">Select</button>
                                </span>
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please upload the Promotion Logo/Image.</span>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-2 control-label">Brochure Doc</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="file" name="brochure" id="brochure" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="brochure" class="form-control" name="brochure" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-brochure">Select</button>
                                </span>
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please upload the Brochure</span>
                              </div>
                              <label for="name" class="col-sm-1 col-md-1 control-label">Brochure Logo/Image</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="file" name="brochure_logo" id="brochure_logo" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="brochure_logo" class="form-control" name="brochure_logo" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-brochure_logo">Select</button>
                                </span>
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please upload the Brochure Logo/Image.</span>
                              </div>
                            </div>
                            <!-- extra -->
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-2 control-label">Extra Brochure Label</label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text('extra_brochure_label','',array('class'=>'form-control', 'id'=>'extra_brochure_label')) !!}
                              </div>
                              <label for="name" class="col-sm-1 col-md-1 control-label">Extra Brochure Logo/Image</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="file" name="extra_brochure_logo" id="extra_brochure_logo" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="extra_brochure_logo" class="form-control" name="extra_brochure_logo" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-extra_brochure_logo">Select</button>
                                </span>
                              </div>
                            </div>

                            <hr>
                            <h4>{{ trans('labels.Setting') }}</h4>
                            <hr>
                            <div class="form-group">
                                <label for="shippingEnvironment" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.Statistics Report') }}</label>
                                <div class="col-sm-10 col-md-4">
                                    <label class=" control-label">
                                        <input type="radio" name="report_view" value="1" class="flat-red"> &nbsp;{{ trans('labels.Yes') }}
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                    <label class=" control-label">
                                        <input type="radio" name="report_view" value="0" class="flat-red" checked>  &nbsp;{{ trans('labels.No') }}
                                    </label>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to view report or not.</span>
                                </div>
                            </div>

                              <!-- /.box-body -->
                              <div class="box-footer text-center">
                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                <a href="{{ URL::to('admin/merchants')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
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
<script type="text/javascript">
    var textarea = document.getElementById('description').value.length;
    if(textarea == 0){
        var count = document.getElementById('lblRemainingCount');
        count.innerHTML = "7000 characters remaining";
    }else if(textarea > 0){
        var charactersLeft = 7000 - textarea;
        var count = document.getElementById('lblRemainingCount');
        count.innerHTML = charactersLeft + " characters remaining";
    }
    function textareaLengthCheck(el) {
        var textArea = el.value.length;
        var charactersLeft = 7000 - textArea;
        var count = document.getElementById('lblRemainingCount');
        count.innerHTML = charactersLeft + " characters remaining";
    }
</script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
<script>
  $('.multi-field-wrapper').each(function() {
      var $wrapper = $('.multi-fields', this);
      $(".add-field", $(this)).click(function(e) {
          $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
      });
      $('.multi-field .remove-field', $wrapper).click(function() {
          if ($('.multi-field', $wrapper).length > 1)
              $(this).parent('.multi-field').remove();
      });
  });
</script>
<!-- multiple standalone button -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // banner
    document.getElementById('button-image-banner').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'banner';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // logo
    document.getElementById('button-image-logo').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'logo';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // pricelist
    document.getElementById('button-image-pricelist').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'pricelist';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // pricelist_logo
    document.getElementById('button-image-pricelist_logo').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'pricelist_logo';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // extra_pricelist_logo
    document.getElementById('button-image-extra_pricelist_logo').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'extra_pricelist_logo';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // promotion
    document.getElementById('button-image-promotion').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'promotion';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // promotion_logo
    document.getElementById('button-image-promotion_logo').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'promotion_logo';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // brochure
    document.getElementById('button-image-brochure').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'brochure';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // brochure_logo
    document.getElementById('button-image-brochure_logo').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'brochure_logo';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // extra_brochure_logo
    document.getElementById('button-image-extra_brochure_logo').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'extra_brochure_logo';

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
@endsection
