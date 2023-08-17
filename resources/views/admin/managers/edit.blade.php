@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.editmanager') }} <small>{{ trans('labels.editmanager') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li><a href="{{ URL::to('admin/managers')}}"><i class="fa fa-users"></i> {{ trans('labels.managers') }}</a></li>
      <li class="active">{{ trans('labels.editmanager') }}</li>
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
            <h3 class="box-title">{{ trans('labels.editmanager') }} </h3>
          </div> 

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
                            {!! Form::open(array('url' =>'admin/updatemanager', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                            {!! Form::hidden('myid', $result['myid'], array('id'=>'myid')) !!}
                            <!--div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.AdminType') }} </label>
                                  <div class="col-sm-10 col-md-4">
                                    <select class="form-control" name="adminType">
                                    @foreach($result['adminTypes'] as $adminType)
                                          <option value="{{$adminType->user_types_id}}" @if($result['admins'][0]->role_id==$adminType->user_types_id) selected @endif>{{$adminType->user_types_name}}</option>
                                    @endforeach
									                  </select>
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                  {{ trans('labels.AdminTypeText') }}</span>
                                  </div>
                            </div>
                            <hr-->
                            
                            <h4>{{ trans('labels.PersonalInfo') }} </h4>
                            <hr>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.FirstName') }} </label>
                                  <div class="col-sm-10 col-md-4">
                                    {!! Form::text('first_name',  $result['admins'][0]->first_name, array('class'=>'form-control field-validate', 'id'=>'first_name')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.FirstNameText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.LastName') }} </label>
                                  <div class="col-sm-10 col-md-4">
                                    {!! Form::text('last_name',  $result['admins'][0]->last_name, array('class'=>'form-control field-validate', 'id'=>'last_name')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.lastNameText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Telephone') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                    {!! Form::text('phone',  $result['admins'][0]->phone, array('class'=>'form-control', 'id'=>'phone')) !!}
                                   <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                   {{ trans('labels.TelephoneText') }}</span>
                                  </div>
                                </div>
                              <hr>
                                <h4>{{ trans('labels.AddressInfo') }}</h4>
                                <hr>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.StreetAddress') }}</label>
                                  <div class="col-sm-10 col-md-4">
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
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Zip/Postal Code') }} </label>
                                  <div class="col-sm-10 col-md-4">
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
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.City') }} </label>
                                  <div class="col-sm-10 col-md-4">
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
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Country') }}</label>
                                  <div class="col-sm-10 col-md-4">
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
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.State') }} </label>
                                  <div class="col-sm-10 col-md-4">
                                    @if($result['address'])
                                    {!! Form::text('state', $result['address'][0]->entry_state, array('class'=>'form-control', 'id'=>'state')) !!}
                                    @else
                                    {!! Form::text('state', '', array('class'=>'form-control', 'id'=>'state')) !!}
                                    @endif
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.StateText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                
                                <hr>
                                <h4>{{ trans('labels.Login Info') }}</h4>
                                <hr>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.EmailAddress') }} </label>
                                  <div class="col-sm-10 col-md-4">
                                     {!! Form::text('email',  $result['admins'][0]->email, array('class'=>'form-control email-validate', 'id'=>'email')) !!}
                                     <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                     {{ trans('labels.EmailText') }}</span>
                                    <span class="help-block hidden"> {{ trans('labels.EmailError') }}</span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.changePassword') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                    {!! Form::checkbox('changePassword', 'yes', null, ['class' => '', 'id'=>'change-passowrd']) !!}
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Password') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                    {!! Form::password('password', array('class'=>'form-control', 'id'=>'password')) !!}
                	                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                   {{ trans('labels.PasswordText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Status') }} </label>
                                  <div class="col-sm-10 col-md-4">
                                    <select class="form-control" name="isActive">
                                          <option value="1" @if($result['admins'][0]->status==1) selected @endif>{{ trans('labels.Active') }}</option>
                                          <option value="0" @if($result['admins'][0]->status==0) selected @endif>{{ trans('labels.Inactive') }}</option>
									</select>
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                  {{ trans('labels.StatusText') }}</span>
                                  </div>
                                </div>

                              <!-- /.box-body -->
                              <div class="box-footer text-center">
                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>

                                @if(Auth()->user()->role_id != \App\Models\Core\User::ROLE_MERCHANT)
                                <a href="{{ URL::to('admin/managers')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
                                @endif
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
@endsection
