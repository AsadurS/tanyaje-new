@extends('admin.layoutLlogin')
@section('content')
<style>
	.wrapper{
		display:  none !important;
	}
</style>
<div class="login-box">
  <div class="login-logo">

  	@if(empty($web_setting[15]->value))
        @if($web_setting[66]->value=='1' and $web_setting[67]->value=='0')
      		<img src="{{asset('images/admin_logo/logo-android-blue-v1.png')}}" class="ionic-hide">
        	<img src="{{asset('images/admin_logo/logo-ionic-blue-v1.png')}}" class="android-hide">
        @elseif($web_setting[66]->value=='1' and $web_setting[67]->value=='1' or $web_setting[66]->value=='0' and $web_setting[67]->value=='1')
{{--   			<img src="{{asset('images/admin_logo/logo-laravel-blue-v1.png')}}" class="website-hide">--}}
            <img src="{{asset('new/images/logo2.png')}}" class="website-hide" style="width:200px; height: 50px;">
    	@endif
    @else
    	<img style="width: 60%" src="{{asset('').$web_setting[15]->value}}">
    @endif

    <div style="
    font-size: 25px;
"><b> {{ trans('labels.welcome_message') }}</b>{{ trans('labels.welcome_message_to') }}</div>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">{{ trans('labels.register_text') }}</p>

    <!-- if email or password are not correct -->
    @if( count($errors) > 0)
    	@foreach($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                  <span class="sr-only">{{ trans('labels.Error') }}:</span>
                  {{ $error }}
            </div>
         @endforeach
    @endif

    @if(Session::has('registerError'))
        <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            {!! session('registerError') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {!! Form::open(array('url' =>'admin/registerLogin', 'method'=>'post', 'class'=>'form-validate')) !!}

      <div class="form-group">
        {!! Form::text('first_name',  '', array('class'=>'form-control field-validate', 'id'=>'first_name')) !!}
        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.FirstNameText') }}</span>
        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
      </div>

      <div class="form-group">
        {!! Form::text('last_name',  '', array('class'=>'form-control field-validate', 'id'=>'last_name')) !!}
        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.lastNameText') }}</span>
        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
      </div> 
      <div class="form-group">
        {!! Form::text('phone',  '', array('class'=>'form-control field-validate', 'id'=>'phone')) !!}
        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.TelephoneText') }}</span>
        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
      </div>

      <div class="form-group has-feedback">
        {!! Form::email('email', '', array('class'=>'form-control email-validate', 'id'=>'email')) !!}
        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.AdminEmailText') }}</span>
        <span class="help-block hidden"> {{ trans('labels.AdminEmailText') }}</span>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        {!! Form::password('password', array('class'=>'form-control field-validate', 'id'=>'password')) !!}
        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.PasswordText') }}</span>
        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        {!! Form::password('password_repeat', array('class'=>'form-control field-validate', 'id'=>'password_repeat')) !!}
        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.PasswordRepeatText') }}</span>
        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      
  	  <img src="">
      <div class="row">

        <!-- /.col -->
        <div class="col-xs-4">
          {!! Form::submit(trans('labels.register'), array('id'=>'register', 'class'=>'btn btn-primary btn-block btn-flat' )) !!}
        </div>
        <!-- /.col -->
      </div>
    {!! Form::close() !!}
    <div class="row">
      <a href="{{ URL::to('admin/login')}}" class="btn btn-link">{{ trans('labels.I already have a merchant') }}</a>
    </div>
  </div>

  <!-- /.login-box-body -->
</div>
