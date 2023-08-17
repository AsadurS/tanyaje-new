@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.EditOrganisation') }} <small>{{ trans('labels.EditOrganisation') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li><a href="{{ URL::to('admin/merchants')}}"><i class="fa fa-users"></i> {{ trans('labels.Organisation') }}</a></li>
      <li class="active">{{ trans('labels.EditOrganisation') }}</li>
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
            <h3 class="box-title">{{ trans('labels.editmerchant') }} </h3>
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

                        @if (count($errors) > 0)
                          @if($errors->any())
                          <div class="alert alert-success alert-dismissible" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              {{$errors->first()}}
                          </div>
                          @endif
                        @endif

                        <!-- form start -->
                         <div class="box-body">
                            {!! Form::open(array('url' =>'admin/updatemerchant', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                            {!! Form::hidden('myid', $result['myid'], array('id'=>'myid')) !!}
                            
                            <div class="form-group">

                              <div class="col-md-6">
                                <hr>
                                <h4>{{ trans('labels.BasicInfo') }}</h4>
                                <hr>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.id') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    <input type="text" name="id_display" id="id_display" value="{{ $result['admins'][0]->id }}" class="form-control" readonly>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.FirstName') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    <input type="text" name="first_name" id="first_name" value="{{ $result['admins'][0]->first_name }}" class="form-control field-validate">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.FirstNameText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.LastName') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    <input type="text" name="last_name" id="last_name" value="{{ $result['admins'][0]->last_name }}" class="form-control field-validate">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.lastNameText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Telephone') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    {!! Form::text('phone',  $result['admins'][0]->phone, array('class'=>'form-control', 'id'=>'phone')) !!}
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
                                              <option value="{{ $parent_org->id }}" @if($parent_org->id == $result['admins'][0]->parent_id) selected @endif>{{ $parent_org->company_name }}</option>
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
                                     {!! Form::text('email',  $result['admins'][0]->email, array('class'=>'form-control email-validate', 'id'=>'email')) !!}
                                     <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                     {{ trans('labels.EmailText') }}</span>
                                    <span class="help-block hidden"> {{ trans('labels.EmailError') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.changePassword') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    {!! Form::checkbox('changePassword', 'yes', null, ['class' => '', 'id'=>'change-passowrd']) !!}
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Password') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    {!! Form::password('password', array('class'=>'form-control', 'id'=>'password')) !!}
                	                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.PasswordText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Status') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    <select class="form-control" name="isActive">
                                          <option value="1" @if($result['admins'][0]->status==1) selected @endif>{{ trans('labels.Active') }}</option>
                                          <option value="0" @if($result['admins'][0]->status==0) selected @endif>{{ trans('labels.Inactive') }}</option>
									                  </select>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.StatusText') }}</span>
                                  </div>
                                </div>
                              </div>

                              <div class="col-md-6">
                                <hr>
                                <h4>{{ trans('labels.AddressInfo') }}</h4>
                                <hr>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.CompanyName') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    @if($result['admins'])
                                    <!-- {!! Form::text('company_name', $result['admins'][0]->company_name, array('class'=>'form-control', 'id'=>'company_name')) !!} -->
                                    <input type="text" name="company_name" id="company_name" value="{{ $result['admins'][0]->company_name }}" class="form-control">
                                    @else
                                    <!-- {!! Form::text('company_name', '', array('class'=>'form-control', 'id'=>'company_name')) !!} -->
                                    <input type="text" name="company_name" id="company_name" class="form-control">
                                    @endif
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CompanyNameText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.StreetAddress') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    @if($result['address'])
                                    {!! Form::text('address', $result['address'][0]->entry_street_address, array('class'=>'form-control', 'id'=>'address')) !!}
                                    @else
                                    {!! Form::text('address', '', array('class'=>'form-control', 'id'=>'address')) !!}
                                    @endif
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.StreetAddressText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Zip/Postal Code') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    @if($result['address'])
                                    {!! Form::text('zip', $result['address'][0]->entry_postcode, array('class'=>'form-control', 'id'=>'zip')) !!}
                                    @else
                                    {!! Form::text('zip', '', array('class'=>'form-control', 'id'=>'zip')) !!}
                                    @endif
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.Zip/Postal Code Text') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.City') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    @if($result['address'])
                                    {!! Form::text('city', $result['address'][0]->entry_city, array('class'=>'form-control', 'id'=>'city')) !!}
                                    @else
                                    {!! Form::text('city', '', array('class'=>'form-control', 'id'=>'city')) !!}
                                    @endif
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CityText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Country') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    <select class="form-control" name="country" id="entry_country_id">
                                        <option value="">{{ trans('labels.SelectCountry') }}</option>
                                        @foreach($result['countries'] as $countries_data)
                                        <option value="{{ $countries_data->countries_id }}" @if($result['address']) @if($result['address'][0]->entry_country_id == $countries_data->countries_id) selected @endif @endif>{{ $countries_data->countries_name }}</option>
                                   		@endforeach
                                    </select>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CountryText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.State') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    @if($result['address'])
                                    {!! Form::text('state', $result['address'][0]->entry_state, array('class'=>'form-control', 'id'=>'state')) !!}
                                    @else
                                    {!! Form::text('state', '', array('class'=>'form-control', 'id'=>'state')) !!}
                                    @endif
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.StateText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Latitude') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    @if($result['address'])
                                    {!! Form::text('latitude', $result['address'][0]->entry_latitude, array('class'=>'form-control', 'id'=>'latitude')) !!}
                                    @else
                                    {!! Form::text('latitude', '', array('class'=>'form-control', 'id'=>'latitude')) !!}
                                    @endif
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.LatitudeText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Longitude') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    @if($result['address'])
                                    {!! Form::text('longitude', $result['address'][0]->entry_longitude, array('class'=>'form-control', 'id'=>'longitude')) !!}
                                    @else
                                    {!! Form::text('longitude', '', array('class'=>'form-control', 'id'=>'longitude')) !!}
                                    @endif
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.LongitudeText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            <hr>
                            <h4>{{ trans('labels.CorporateInfo') }}</h4>
                            <hr>
                            <div class="form-group">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.ROC_SCM_NO') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                      {!! Form::text('roc_scm_no',  $result['admins'][0]->roc_scm_no, array('class'=>'form-control', 'id'=>'ROCSCMNO')) !!}
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.ROC_SCM_NOText') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.CorporateEmail') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                      {!! Form::text('corporate_email', $result['admins'][0]->corporate_email ,array('class'=>'form-control', 'id'=>'CorporateEmail')) !!}
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
                                        <option value="{{ $segment->segment_id }}" @if($segment->segment_id == $result['admins'][0]->segment_id) selected @endif>{{ $segment->segment_name }}</option>
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
                                    <!-- <input type="hidden" name="old_banner" id="old_banner" value="{{ $result['admins'][0]->banner }}">
                                    <input type="file" name="banner" id="banner" class="form-control"><br>
                                    @if($result['admins'][0]->banner)
                                      <img src="{{asset('images/banner/'.$result['admins'][0]->banner)}}" alt="banner photo" style="width:50%;"/><br>
                                    @endif -->
                                    <span style="display:flex;">
                                        <input type="text" id="banner" class="form-control" name="banner" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->banner }}">
                                        <button class="btn btn-outline-secondary" type="button" id="button-image-banner">Select</button>
                                    </span>
                                    @if($result['admins'][0]->banner)
                                        <br>
                                        @if (file_exists(public_path('/images/banner/'.$result['admins'][0]->banner)))
                                            <img src="{{asset('/images/banner/'.$result['admins'][0]->banner)}}" width="50%" alt="banner">
                                        @else
                                            <img src="{{ $result['admins'][0]->banner }}" width="100px" alt=""> 
                                        @endif 
                                    @endif
                                    <br>
                                    <center>OR</center>
                                    <br>
                                    <input type="color" class="form-control" name="banner_color" value="{!! $result['admins'][0]->banner_color !!}">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please choose the banner color</span>
                                </div>
                                </div>


                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Logo') }}</label>
                                  <div class="col-sm-10 col-md-6">
                                    <!-- <input type="hidden" name="old_logo" id="old_logo" value="{{ $result['admins'][0]->logo }}">
                                    <input type="file" name="logo" id="logo" class="form-control"><br>
                                    @if($result['admins'][0]->logo)
                                      <img src="{{asset('images/logo/'.$result['admins'][0]->logo)}}" alt="logo photo" style="width:50%;"/><br>
                                    @endif -->
                                    <span style="display:flex;">
                                        <input type="text" id="logo" class="form-control" name="logo" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->logo }}">
                                        <button class="btn btn-outline-secondary" type="button" id="button-image-logo">Select</button>
                                    </span>
                                    @if($result['admins'][0]->logo)
                                        <br>
                                        @if (file_exists(public_path('/images/logo/'.$result['admins'][0]->logo)))
                                            <img src="{{asset('/images/logo/'.$result['admins'][0]->logo)}}" width="50%" alt="logo">
                                        @else
                                            <img src="{{ $result['admins'][0]->logo }}" width="100px" alt=""> 
                                        @endif 
                                    @endif
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">Title Color</label>
                                  <div class="col-sm-10 col-md-6">
                                      <input type="color" class="form-control" name="title_color" value="{!! $result['admins'][0]->title_color !!}">
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Please choose title color</span>
                                      <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                              <!-- close col-md-6  -->
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.BRN_NO') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                      {!! Form::text('brn_no',$result['admins'][0]->brn_no,array('class'=>'form-control', 'id'=>'BRNNO')) !!}
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.BRN_NOText') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.CorporatePhone') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                      {!! Form::text('corporate_phone',$result['admins'][0]->corporate_phone,array('class'=>'form-control', 'id'=>'CorporatePhone')) !!}
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.CorporatePhoneText') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Bank1') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    <select class="form-control" name="bank1">
                                        @if (count($result['banks']) > 0)
                                          <option>Select</option>
                                          @foreach ($result['banks']  as $key=>$bank)
                                          <option value="{{ $bank->bank_id }}" @if($bank->bank_id == $result['admins'][0]->bank_id1) selected @endif>{{ $bank->bank_name }}</option>
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
                                      {!! Form::text('bank_acc_name1',$result['admins'][0]->bank_acc_name1,array('class'=>'form-control', 'id'=>'BankAccountName1')) !!}
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.BankAccountName1Text') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.BankAccountNo1') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                      {!! Form::text('bank_acc_no1',$result['admins'][0]->bank_acc_no1,array('class'=>'form-control', 'id'=>'BankAccountNo1')) !!}
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.BankAccountNo1Text') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.Bank2') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                    <select class="form-control" name="bank2">
                                        @if (count($result['banks']) > 0)
                                          <option>Select</option>
                                          @foreach ($result['banks']  as $key=>$bank)
                                          <option value="{{ $bank->bank_id }}" @if($bank->bank_id == $result['admins'][0]->bank_id2) selected @endif>{{ $bank->bank_name }}</option>
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
                                      {!! Form::text('bank_acc_name2',$result['admins'][0]->bank_acc_name2,array('class'=>'form-control', 'id'=>'BankAccountName2')) !!}
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.BankAccountName2Text') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.BankAccountNo2') }} </label>
                                  <div class="col-sm-10 col-md-6">
                                      {!! Form::text('bank_acc_no2',$result['admins'][0]->bank_acc_no2,array('class'=>'form-control', 'id'=>'BankAccountNo2')) !!}
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.BankAccountNo2Text') }}</span>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-2 control-label">{{ trans('labels.Description') }}</label>
                              <div class="col-sm-10 col-md-6">
                                  <textarea id="description" name="description" class="form-control" rows="5" maxlength="7000" onkeyup="textareaLengthCheck(this)">{!! $result['admins'][0]->description !!}</textarea>
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;display:inline;">
                                      {{ trans('labels.EditorText') }}
                                  </span>
                                  <span id="lblRemainingCount" style="float:right;display:inline;"></span>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-2 control-label">{{ trans('labels.Opening Hours') }}</label>
                              <div class="col-sm-10 col-md-6">
                                  <textarea id="opening_hours" name="opening_hours" class="form-control" rows="5" maxlength="7000" onkeyup="textareaLengthCheck(this)">{!! $result['admins'][0]->opening_hours !!}</textarea>
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;display:inline;">
                                      {{ trans('labels.OpeningHoursText') }}
                                  </span>
                                  <span id="lblRemainingCount" style="float:right;display:inline;"></span>
                              </div>
                            </div>

                                <!-- mark -->

                            <hr>
                            <h4>{{ trans('labels.LandingPage') }}</h4>
                            <hr>
                            <div class="form-group">
                                <label for="template_type" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.SelectTemplate') }}</label>
                                <div class="col-sm-10 col-md-4">
                                    <select class="form-control" name="template_type">
                                        @if (count($result['templates']) > 0)
                                          <option>Select</option>
                                          @foreach ($result['templates']  as $key=>$template)
                                          <option value="{{ $template->id }}" @if($template->id == $result['admins'][0]->template_id) selected @endif>{{ $template->name }}</option>
                                          @endforeach
                                        @endif
								                  	</select>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.SelectTemplateText') }}</span>
                                </div>
                            </div>

                            <!-- Docs -->

                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-2 control-label">Price List Doc</label>
                              <div class="col-sm-10 col-md-4">
                                  <!-- <input type="hidden" name="old_pricelist" id="old_pricelist" value="{{ $result['admins'][0]->pricelist }}">
                                  @if($result['admins'][0]->pricelist)
                                    <i class="fa fa-file" aria-hidden="true"></i> <a href="{{asset('images/organisation/pricelist/'.$result['admins'][0]->pricelist)}}" target="_blank">View Price List</a> 
                                    <button type="button" class="btn-del btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal" data-whatever="pricelist"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>
                                    <br>
                                  @endif
                                  <input type="file" name="pricelist" id="pricelist" class="form-control"> -->
                                  <span style="display:flex;">
                                    <input type="text" id="pricelist" class="form-control" name="pricelist" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->pricelist }}">
                                    <button class="btn btn-outline-secondary" type="button" id="button-image-pricelist">Select</button>
                                    @if($result['admins'][0]->pricelist)
                                      <button type="button" class="btn-del btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="pricelist"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    @endif
                                  </span>
                                  @if($result['admins'][0]->pricelist)
                                    <br>
                                    @if (file_exists(public_path('/images/organisation/pricelist/'.$result['admins'][0]->pricelist)))
                                        <i class="fa fa-file" aria-hidden="true"></i> <a href="{{asset('images/organisation/pricelist/'.$result['admins'][0]->pricelist)}}" target="_blank">Preview</a> 
                                    @else
                                        <i class="fa fa-file" aria-hidden="true"></i> <a href="{{ $result['admins'][0]->pricelist }}" target="_blank">Preview</a> 
                                    @endif 
                                  @endif
                              </div>
                              <label for="name" class="col-sm-1 col-md-1 control-label">Price List Logo/Image</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="hidden" name="old_pricelist_logo" id="old_pricelist_logo" value="{{ $result['admins'][0]->pricelist_logo }}">
                                @if($result['admins'][0]->pricelist_logo)
                                  <i class="fa fa-picture-o" aria-hidden="true"></i> <a href="{{asset('images/organisation/pricelist/'.$result['admins'][0]->pricelist_logo)}}" target="_blank">View Price List Logo/Image</a>
                                  <button type="button" class="btn-del btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal" data-whatever="pricelist_logo"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>
                                  <br>
                                @endif
                                <input type="file" name="pricelist_logo" id="pricelist_logo" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="pricelist_logo" class="form-control" name="pricelist_logo" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->pricelist_logo }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-pricelist_logo">Select</button>
                                  @if($result['admins'][0]->pricelist_logo)
                                    <button type="button" class="btn-del btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="pricelist_logo"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                  @endif
                                </span>
                                @if($result['admins'][0]->pricelist_logo)
                                  <br>
                                  @if (file_exists(public_path('/images/organisation/pricelist/'.$result['admins'][0]->pricelist_logo)))
                                      <i class="fa fa-file" aria-hidden="true"></i> <a href="{{asset('images/organisation/pricelist/'.$result['admins'][0]->pricelist_logo)}}" target="_blank">Preview</a> 
                                  @else
                                      <i class="fa fa-file" aria-hidden="true"></i> <a href="{{ $result['admins'][0]->pricelist_logo }}" target="_blank">Preview</a> 
                                  @endif 
                                @endif
                              </div>
                            </div>
                            <!-- extra -->
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-2 control-label">Extra PriceList Label</label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text('extra_pricelist_label',$result['admins'][0]->extra_pricelist_label,array('class'=>'form-control', 'id'=>'extra_pricelist_label')) !!}
                              </div>
                              <label for="name" class="col-sm-1 col-md-1 control-label">Extra PriceList Logo/Image</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="hidden" name="old_extra_pricelist_logo" id="old_extra_pricelist_logo" value="{{ $result['admins'][0]->extra_pricelist_logo }}">
                                @if($result['admins'][0]->extra_pricelist_logo)
                                  <i class="fa fa-picture-o" aria-hidden="true"></i> <a href="{{asset('images/organisation/pricelist/'.$result['admins'][0]->extra_pricelist_logo)}}" target="_blank">View Extra Price List Logo/Image</a>
                                  <button type="button" class="btn-del btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal" data-whatever="extra_pricelist_logo"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>
                                  <br>
                                @endif
                                <input type="file" name="extra_pricelist_logo" id="extra_pricelist_logo" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="extra_pricelist_logo" class="form-control" name="extra_pricelist_logo" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->extra_pricelist_logo }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-extra_pricelist_logo">Select</button>
                                  @if($result['admins'][0]->extra_pricelist_logo)
                                    <button type="button" class="btn-del btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="extra_pricelist_logo"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                  @endif
                                </span>
                                @if($result['admins'][0]->extra_pricelist_logo)
                                  <br>
                                  @if (file_exists(public_path('/images/organisation/pricelist/'.$result['admins'][0]->extra_pricelist_logo)))
                                      <i class="fa fa-file" aria-hidden="true"></i> <a href="{{asset('images/organisation/pricelist/'.$result['admins'][0]->extra_pricelist_logo)}}" target="_blank">Preview</a> 
                                  @else
                                      <i class="fa fa-file" aria-hidden="true"></i> <a href="{{ $result['admins'][0]->extra_pricelist_logo }}" target="_blank">Preview</a> 
                                  @endif 
                                @endif
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-2 control-label">Promotion Doc</label>
                              <div class="col-sm-10 col-md-4">
                                  <!-- <input type="hidden" name="old_promotion" id="old_promotion" value="{{ $result['admins'][0]->promotion }}">
                                  @if($result['admins'][0]->promotion)
                                    <i class="fa fa-file" aria-hidden="true"></i> <a href="{{asset('images/organisation/promotion/'.$result['admins'][0]->promotion)}}" target="_blank">View Promotion List</a>
                                    <button type="button" class="btn-del btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal" data-whatever="promotion"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>
                                    <br>
                                  @endif
                                  <input type="file" name="promotion" id="promotion" class="form-control"> -->
                                  <span style="display:flex;">
                                    <input type="text" id="promotion" class="form-control" name="promotion" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->promotion }}">
                                    <button class="btn btn-outline-secondary" type="button" id="button-image-promotion">Select</button>
                                    @if($result['admins'][0]->promotion)
                                      <button type="button" class="btn-del btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="promotion"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    @endif
                                  </span>
                                  @if($result['admins'][0]->promotion)
                                    <br>
                                    @if (file_exists(public_path('/images/organisation/promotion/'.$result['admins'][0]->promotion)))
                                        <i class="fa fa-file" aria-hidden="true"></i> <a href="{{asset('images/organisation/promotion/'.$result['admins'][0]->promotion)}}" target="_blank">Preview</a> 
                                    @else
                                        <i class="fa fa-file" aria-hidden="true"></i> <a href="{{ $result['admins'][0]->promotion }}" target="_blank">Preview</a> 
                                    @endif 
                                  @endif
                              </div>
                              <label for="name" class="col-sm-1 col-md-1 control-label">Promotion Logo/Image</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="hidden" name="old_promotion_logo" id="old_promotion_logo" value="{{ $result['admins'][0]->promotion_logo }}">
                                @if($result['admins'][0]->promotion_logo)
                                  <i class="fa fa-picture-o" aria-hidden="true"></i> <a href="{{asset('images/organisation/promotion/'.$result['admins'][0]->promotion_logo)}}" target="_blank">View Promotion Logo/Image</a>
                                  <button type="button" class="btn-del btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal" data-whatever="promotion_logo"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>
                                  <br>
                                @endif
                                <input type="file" name="promotion_logo" id="promotion_logo" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="promotion_logo" class="form-control" name="promotion_logo" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->promotion_logo }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-promotion_logo">Select</button>
                                  @if($result['admins'][0]->promotion_logo)
                                    <button type="button" class="btn-del btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="promotion_logo"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                  @endif
                                </span>
                                @if($result['admins'][0]->promotion_logo)
                                  <br>
                                  @if (file_exists(public_path('/images/organisation/promotion/'.$result['admins'][0]->promotion_logo)))
                                      <i class="fa fa-file" aria-hidden="true"></i> <a href="{{asset('images/organisation/promotion/'.$result['admins'][0]->promotion_logo)}}" target="_blank">Preview</a> 
                                  @else
                                      <i class="fa fa-file" aria-hidden="true"></i> <a href="{{ $result['admins'][0]->promotion_logo }}" target="_blank">Preview</a> 
                                  @endif 
                                @endif
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-2 control-label">Brochure Doc</label>
                              <div class="col-sm-10 col-md-4">
                                  <input type="hidden" name="old_brochure" id="old_brochure" value="{{ $result['admins'][0]->brochure }}">
                                  <!-- @if($result['admins'][0]->brochure)
                                    <i class="fa fa-file" aria-hidden="true"></i> <a href="{{asset('images/organisation/brochure/'.$result['admins'][0]->brochure)}}" target="_blank">View Brochure List</a>
                                    <button type="button" class="btn-del btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal" data-whatever="brochure"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>
                                    <br>
                                  @endif
                                  <input type="file" name="brochure" id="brochure" class="form-control"> -->
                                  <span style="display:flex;">
                                    <input type="text" id="brochure" class="form-control" name="brochure" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->brochure }}">
                                    <button class="btn btn-outline-secondary" type="button" id="button-image-brochure">Select</button>
                                    @if($result['admins'][0]->brochure)
                                      <button type="button" class="btn-del btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="brochure"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    @endif
                                  </span>
                                  @if($result['admins'][0]->brochure)
                                    <br>
                                    @if (file_exists(public_path('/images/organisation/brochure/'.$result['admins'][0]->brochure)))
                                        <i class="fa fa-file" aria-hidden="true"></i> <a href="{{asset('images/organisation/brochure/'.$result['admins'][0]->brochure)}}" target="_blank">Preview</a> 
                                    @else
                                        <i class="fa fa-file" aria-hidden="true"></i> <a href="{{ $result['admins'][0]->brochure }}" target="_blank">Preview</a> 
                                    @endif 
                                  @endif
                              </div>
                              <label for="name" class="col-sm-1 col-md-1 control-label">Brochure Logo/Image</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="hidden" name="old_brochure_logo" id="old_brochure_logo" value="{{ $result['admins'][0]->brochure_logo }}">
                                @if($result['admins'][0]->brochure_logo)
                                  <i class="fa fa-picture-o" aria-hidden="true"></i> <a href="{{asset('images/organisation/brochure/'.$result['admins'][0]->brochure_logo)}}" target="_blank">View Promotion Logo/Image</a>
                                  <button type="button" class="btn-del btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal" data-whatever="brochure_logo"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>
                                  <br>
                                @endif
                                <input type="file" name="brochure_logo" id="brochure_logo" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="brochure_logo" class="form-control" name="brochure_logo" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->brochure_logo }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-brochure_logo">Select</button>
                                  @if($result['admins'][0]->brochure_logo)
                                    <button type="button" class="btn-del btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="brochure_logo"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                  @endif
                                </span>
                                @if($result['admins'][0]->brochure_logo)
                                  <br>
                                  @if (file_exists(public_path('/images/organisation/brochure/'.$result['admins'][0]->brochure_logo)))
                                      <i class="fa fa-file" aria-hidden="true"></i> <a href="{{asset('images/organisation/brochure/'.$result['admins'][0]->brochure_logo)}}" target="_blank">Preview</a> 
                                  @else
                                      <i class="fa fa-file" aria-hidden="true"></i> <a href="{{ $result['admins'][0]->brochure_logo }}" target="_blank">Preview</a> 
                                  @endif 
                                @endif
                              </div>
                            </div>
                            <!-- extra -->
                            <div class="form-group">
                              <label for="name" class="col-sm-2 col-md-2 control-label">Extra Brochure Label</label>
                              <div class="col-sm-10 col-md-4">
                                {!! Form::text('extra_brochure_label',$result['admins'][0]->extra_brochure_label,array('class'=>'form-control', 'id'=>'extra_brochure_label')) !!}
                              </div>
                              <label for="name" class="col-sm-1 col-md-1 control-label">Extra Brochure Logo/Image</label>
                              <div class="col-sm-10 col-md-4">
                                <!-- <input type="hidden" name="old_extra_brochure_logo" id="old_extra_brochure_logo" value="{{ $result['admins'][0]->extra_brochure_logo }}">
                                @if($result['admins'][0]->extra_brochure_logo)
                                  <i class="fa fa-picture-o" aria-hidden="true"></i> <a href="{{asset('images/organisation/brochure/'.$result['admins'][0]->extra_brochure_logo)}}" target="_blank">View Extra Price List Logo/Image</a>
                                  <button type="button" class="btn-del btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal" data-whatever="extra_brochure_logo"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>
                                  <br>
                                @endif
                                <input type="file" name="extra_brochure_logo" id="extra_brochure_logo" class="form-control"> -->
                                <span style="display:flex;">
                                  <input type="text" id="extra_brochure_logo" class="form-control" name="extra_brochure_logo" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->extra_brochure_logo }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-extra_brochure_logo">Select</button>
                                  @if($result['admins'][0]->extra_brochure_logo)
                                    <button type="button" class="btn-del btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="extra_brochure_logo"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                  @endif
                                </span>
                                @if($result['admins'][0]->extra_brochure_logo)
                                  <br>
                                  @if (file_exists(public_path('/images/organisation/brochure/'.$result['admins'][0]->extra_brochure_logo)))
                                      <i class="fa fa-file" aria-hidden="true"></i> <a href="{{asset('images/organisation/brochure/'.$result['admins'][0]->extra_brochure_logo)}}" target="_blank">Preview</a> 
                                  @else
                                      <i class="fa fa-file" aria-hidden="true"></i> <a href="{{ $result['admins'][0]->extra_brochure_logo }}" target="_blank">Preview</a> 
                                  @endif 
                                @endif
                              </div>
                            </div>

                            <div class="form-group">
                                <label for="document_manager" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.DocumentManager') }}</label>
                                <div class="col-sm-10 col-md-9">

                                  <table class="table table-bordered table-striped">
                                    <tr>
                                      <th>Name / Label</th>
                                      <th>Doc</th>
                                      <th>Link</th>
                                      <th>Logo</th>
                                      <th>Show in</th>
                                      <th>Sort Order</th>
                                      <th>Action</th>
                                    </tr>
                                    @if (count($result['documents']) > 0)
                                    @foreach ($result['documents']  as $key=>$document)
                                    <tr id="todo_{{$document->id}}">
                                      <td>{{ $document->name }}</td>
                                      <td>
                                        @if($document->attachment)
                                            @if (file_exists(public_path('/images/organisation/documents/'.$document->attachment)))
                                              <a href="{{asset('images/organisation/documents/'.$document->attachment)}}" target="_blank"> View</a> 
                                            @else
                                              <a href="{{$document->attachment}}" target="_blank"> View</a> 
                                            @endif 
                                        @else
                                        NA
                                        @endif
                                      </td>
                                      <td style="text-transform: none;">@if( $document->link ) {{$document->link  }} @else NA @endif</td>
                                      <td> 
                                        @if($document->logo)
                                            @if (file_exists(public_path('/images/organisation/documents/'.$document->logo)))
                                              <a href="{{asset('images/organisation/documents/'.$document->logo)}}" target="_blank"> View</a> 
                                            @else
                                              <a href="{{$document->logo}}" target="_blank"> View</a> 
                                            @endif 
                                        @else
                                        NA
                                        @endif
                                      </td>
                                      <td>@if($document->is_askme == '0') No @elseif($document->is_askme == '1') AskMe @elseif($document->is_askme == '2') Tools @else NA @endif</td>
                                      <td>
                                        @if($document->sort_order == null)
                                          NA
                                        @else
                                          {{ $document->sort_order }}
                                        @endif
                                      </td>
                                      <td>
                                        <a data-id="{{ $document->id }}" class="badge bg-blue" data-toggle="modal" data-target="#editModal" 
                                          data-whatever="{{ $document->id }}" data-whatever2="{{ $document->name }}" data-whatever3="{{ $document->attachment }}" data-whatever4="{{ $document->logo }}" data-whatever5="{{ $document->link }}" data-whatever6="{{ $document->is_askme }}" data-whatever7="{{ $document->size }}" data-whatever8="{{ $document->type }}" data-whatever9="{{ $document->sort_order }}">
                                          <i class="fa fa-edit " aria-hidden="true"></i>
                                        </a>
                                        <a branch_id = "{{ $document->id }}" class="badge bg-red deleteBranchModal"><i class="fa fa-trash " aria-hidden="true"></i></a>
                                      </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                      <td colspan="7">
                                        <button type="button" class="add-doc form-control" style="background: #787878;color: white;" onclick="doc_open()">Add document/link</button>
                                      </td>
                                    </tr>
                                    @else
                                    <tr>
                                      <td colspan="7">No data.</td>
                                    </tr>
                                    <tr>
                                      <td colspan="7">
                                        <button type="button" class="add-doc form-control" style="background: #787878;color: white;" onclick="doc_open()">Add document/link</button>
                                      </td>
                                    </tr>
                                    @endif
                                  </table>
                                  <br>
                                  <div class="add_more_doc table-responsive">
                                    <table class="table table-bordered table-striped" id="user_table">
                                      <thead>
                                        <tr>
                                            <th style="vertical-align: middle;" width="20%">Document Name / Label</th>
                                            <th style="vertical-align: middle;" width="10%">Link (if any)</th>
                                            <th style="vertical-align: middle;" width="20%">Doc Attachment (if any)</th>
                                            <th style="vertical-align: middle;" width="20%">Logo</th>
                                            <th style="vertical-align: middle;" width="10%">Show Link in</th>
                                            <th style="vertical-align: middle;" width="10%">Sort Order</th>
                                            <th style="vertical-align: middle;" width="10%">Action</th>
                                        </tr>
                                      </thead>
                                      <tbody class="appendHere">

                                      </tbody>
                                      <tfoot>
                                        <tr>
                                            <td colspan="2" align="right">&nbsp;</td>
                                            <td>
                                          @csrf
                                        </td>
                                        </tr>
                                      </tfoot>
                                    </table>
                                  </div>
                                </div>
                            </div>

                            <br><br>
                            

                            <hr>
                            <h4>{{ trans('labels.Setting') }}</h4>
                            <hr>
                            <div class="form-group">
                                <label for="shippingEnvironment" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.Statistics Report') }}</label>
                                <div class="col-sm-10 col-md-4">
                                    <label class=" control-label">
                                        <input type="radio" name="report_view" value="1" class="flat-red" @if($result['admins'][0]->report_view==1) checked @endif > &nbsp;{{ trans('labels.Yes') }}
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                    <label class=" control-label">
                                        <input type="radio" name="report_view" value="0" class="flat-red" @if($result['admins'][0]->report_view==0) checked @endif >  &nbsp;{{ trans('labels.No') }}
                                    </label>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to view report or not.</span>
                                </div>
                            </div>

                            <!-- /.box-body -->
                            <div class="box-footer text-center">
                            <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>

                            @if(Auth()->user()->role_id != \App\Models\Core\User::ROLE_MERCHANT)
                            <a href="{{ URL::to('admin/merchants')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
                            @endif
                            </div>
                              <!-- /.box-footer -->
                            {!! Form::close() !!}
                        </div>
                  </div>
              </div>
            </div>

            <!-- deleteBranchModal -->
            <div class="modal fade" id="deleteBranchModal" tabindex="-1" role="dialog" aria-labelledby="deleteBranchModalLabel">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="deleteBranchModalLabel">{{ trans('labels.DeleteDocument') }}</h4>
                      </div>
                      {!! Form::open(array('url' =>'admin/merchants/deleteDocument', 'name'=>'deleteBranch', 'id'=>'deleteBranch', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                      {!! Form::hidden('branch_id',  '', array('class'=>'form-control', 'id'=>'branch_id')) !!}
                      {!! Form::hidden('org_id',  '', array('class'=>'form-control', 'id'=>'org_id')) !!}
                      <div class="modal-body">
                          <p>{{ trans('labels.DeleteDocumentText') }}</p>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Cancel') }}</button>
                              <button type="submit" class="btn btn-primary" id="deleteAddressBtn">{{ trans('labels.Delete') }}</button>
                          </div>
                          {!! Form::close() !!}
                      </div>
                  </div>
              </div>
            </div>

            <!-- edit doc -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="editModalLabel">Edit Document</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  {!! Form::open(array('url' =>'admin/merchants/updateDocManager', 'name'=>'updateDocManager', 'id'=>'updateDocManager', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                  <div class="modal-body">
                    <input type="hidden" class="form-control" name="doc_id" id="doc_id" value="">
                    <input type="hidden" class="form-control" name="old_doc_attach" id="old_doc_attach" value="">
                    <!-- <input type="hidden" class="form-control" name="old_doc_attach_size" id="old_doc_attach_size" value=""> -->
                    <!-- <input type="hidden" class="form-control" name="old_doc_attach_type" id="old_doc_attach_type" value=""> -->
                    <input type="hidden" class="form-control" name="old_doc_logo" id="old_doc_logo" value="">
                    Doc Name/Label:  <input type="text" class="form-control" name="doc_name" id="doc_name" value=""><br>
                    Link:  <input type="text" class="form-control" name="doc_link" id="doc_link" value=""><br>
                    Doc Attachment:  <span style="display:flex;"><input type="text" id="ref-doc_attach" class="form-control" name="doc_attach" readonly="readonly" aria-label="Image" aria-describedby="button-image" value=""><button class="btn btn-outline-secondary" type="button" id="doc_attach" onClick="reply_click(this.id)">Select</button></span><br>
                    Logo:  <span style="display:flex;"><input type="text" id="ref-logo_attach" class="form-control" name="logo_attach" readonly="readonly" aria-label="Image" aria-describedby="button-image" value=""><button class="btn btn-outline-secondary" type="button" id="logo_attach" onClick="reply_click(this.id)">Select</button></span><br>
                    Show in: 
                    <select name="selectEditDoc" id="selectEditDoc" class="form-control">
                      <option value="0">No</option>
                      <option value="1">AskMe</option>
                      <option value="2">Tools</option>
                    </select><br>
                    Sort Order:  <input type="number" class="form-control" name="doc_sort_order" id="doc_sort_order" value=""><br>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                  {!! Form::close() !!}
                </div>
              </div>
            </div>

            <!-- delete file -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Delete this document?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  {!! Form::open(array('url' =>'admin/merchants/deleteFile', 'name'=>'deleteBranch', 'id'=>'deleteBranch', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                  <div class="modal-body">
                    <p>Are you sure you want to delete this file?</p>
                    <input type="hidden" class="form-control" name="field_name" id="field_name" value="">
                    <input type="hidden" name="org_id" value="{{ $result['admins'][0]->id }}">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Delete</button>
                  </div>
                  {!! Form::close() !!}
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
<script>
  $(document).ready(function(){
    $('#exampleModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Button that triggered the modal
      var recipient = button.data('whatever'); // Extract info from data-* attributes
      console.log(recipient);
      var modal = $(this);
      modal.find('.modal-title').text('Delete this document?');
      $('#field_name').val(recipient);
    });

    $('#editModal').on('show.bs.modal', function (event) {
      var data1 = '';var data2 = '';var data3 = '';var data4 = '';var data5 = '';var data6 = '';var data7 = '';var data8 = '';var data9 = '';
      var button = $(event.relatedTarget); // Button that triggered the modal
      var data1 = button.data('whatever');
      var data2 = button.data('whatever2'); // Extract info from data-* attributes
      var data3 = button.data('whatever3');
      var data4 = button.data('whatever4');
      var data5 = button.data('whatever5');
      var data6 = button.data('whatever6');
      var data7 = button.data('whatever7');
      var data8 = button.data('whatever8');
      var data9 = button.data('whatever9');
      var modal = $(this);
      modal.find('.modal-title').text('Edit Document');
      $('#doc_id').val(data1);
      $('#doc_name').val(data2);
      $('#ref-doc_attach').val(data3);
      $('#ref-logo_attach').val(data4);
      $('#doc_link').val(data5);
      if(data6){
          $('#selectEditDoc option[value="'+data6+'"]').prop('selected', true);
          console.log(data6);
      }
      $('#doc_sort_order').val(data9);
      // $('#old_doc_attach_size').val(data3);
      // $('#old_doc_attach_type').val(data3);
    });
  });
  
</script>
<script>
  $(document).ready(function(){

    var count = 1;

    dynamic_field(count);
    
    function dynamic_field(number)
    {
      html = '<tr>';
            html += '<td><input type="text" name="document_name[]" class="form-control" /></td>';
            html += '<td><input type="text" name="link[]" class="form-control" /></td>';
            html += '<td><span style="display:flex;"><input type="text" id="ref-doc'+number+'" class="form-control" name="document_attachment[]" readonly="readonly" aria-label="Image" aria-describedby="button-image" value=""><button class="btn btn-outline-secondary" type="button" id="doc'+number+'" onClick="reply_click(this.id)">Select</button></span></td>';
            html += '<td><span style="display:flex;"><input type="text" id="ref-logo'+number+'" class="form-control" name="doc_logo[]" readonly="readonly" aria-label="Image" aria-describedby="button-image" value=""><button class="btn btn-outline-secondary" type="button" id="logo'+number+'" onClick="reply_click(this.id)">Select</button></span></td>';
            html += '<td><select name="is_askme[]" class="form-control"><option value="0">No</option><option value="1">AskMe</option><option value="2">Tools</option></select></td>';
            html += '<td><input type="number" name="sort_order[]" class="form-control" /></td>';
            if(number > 1)
            {
                html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove">Remove</button></td></tr>';
                $('tbody.appendHere').append(html);
            }
            else
            {   
                html += '<td><button type="button" name="add" id="add" class="btn btn-success">Add</button></td></tr>';
                $('tbody.appendHere').html(html);
            }
    }

    $(document).on('click', '#add', function(){
      count++;
      dynamic_field(count);
    });

    $(document).on('click', '.remove', function(){
      count--;
      $(this).closest("tr").remove();
    });
  });
</script>
<script>
  function doc_open()
    {
        $(".add_more_doc").css("display", "block");
    }
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

  // dynamic doc manager
  function reply_click(clicked_id){
    var selected = clicked_id;
    console.log(selected);
    inputId = 'ref-'+selected;
    window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
  }

  // set file link
  function fmSetLink($url) {
    document.getElementById(inputId).value = $url;
  }
</script>

<style>
  .add_more_doc {
    display:none;
  }
  .btn-del{
    background: red;
    border: 0;
  }
</style>
@endsection
