@extends('admin.layoutLlogin')
@section('content')
<style>
	.wrapper{
		display:  none !important;
	}

  .field-icon {
    float: right;
    margin-left: -25px;
    margin-top: -25px;
    position: relative;
    z-index: 3;
    padding-right: 45px;
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
    <p class="login-box-msg">{{ trans('labels.login_text') }}</p>

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

    @if(Session::has('loginError'))
        <div class="alert alert-danger" role="alert">
              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
              <span class="sr-only">{{ trans('labels.Error') }}:</span>
              {!! session('loginError') !!}

              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(Session::has('loginSuccess'))
        <div class="alert alert-success" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            {!! session('loginSuccess') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {!! Form::open(array('url' =>'admin/checkLogin', 'method'=>'post', 'class'=>'form-validate')) !!}

       <div class="form-group has-feedback">
        {!! Form::email('email', '', array('class'=>'form-control email-validate', 'id'=>'email')) !!}
        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                     {{ trans('labels.AdminEmailText') }}</span>
       <span class="help-block hidden"> {{ trans('labels.AdminEmailText') }}</span>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
       <input id="password-field" type="password" name='password' class='form-control field-validate' value="">
       <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
       <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                   {{ trans('labels.AdminPasswordText') }}</span>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
       <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>

      </div>
  	  <img src="">
      <div class="row">

        <!-- /.col -->
        <div class="col-xs-4">
          {!! Form::submit(trans('labels.login'), array('id'=>'login', 'class'=>'btn btn-primary btn-block btn-flat' )) !!}
        </div>
        <!-- /.col -->
      </div>
    {!! Form::close() !!}
    <div class="row">
      <a href="{{ URL::to('admin/register')}}" class="btn btn-link">{{ trans('labels.Register a new merchant') }}</a>
      <a href="{{ URL::to('sale_advisor/login')}}" class="btn btn-link">{{ trans('labels.LoginAsSaleAdvisor') }}</a>
    </div>
  </div>

  <!-- /.login-box-body -->
</div>
