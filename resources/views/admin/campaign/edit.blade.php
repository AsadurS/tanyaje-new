@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.Campaigns') }}  <small>{{ trans('labels.EditCampaigns') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/campaigns')}}"><i class="fa fa-industry"></i> {{ trans('labels.Campaigns') }}</a></li>
                <li class="active">{{ trans('labels.EditCampaigns') }}</li>
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
                            <h3 class="box-title">{{ trans('labels.EditCampaigns') }} </h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box box-info">
                                        <br>

                                        @if (session('message'))
                                            <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> {{ session('message') }} </strong>
                                            </div>
                                        @endif

                                        @if (count($errors) > 0)
                                            @if($errors->any())
                                                <div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    {{$errors->first()}}
                                                </div>
                                        @endif
                                    @endif
                                    <!-- /.box-header -->
                                        <!-- form start -->
                                        <div class="box-body">


                                        {!! Form::open(array('url' =>'admin/updatecampaigns', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                            {!! Form::hidden('myid', $result['myid'], array('id'=>'myid')) !!}
                                           
                                            
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.campaign_name') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('campaign_name',   $result['admins'][0]->campaign_name, array('class'=>'form-control  field-validate', 'id'=>'campaign_name'), value(old('campaign_name'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addCampaignNametext') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.CampaignImage') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <span style="display:flex;">
                                                        <input type="text" id="campaign_image" class="form-control" name="campaign_image" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->campaign_image }}">
                                                        <button class="btn btn-outline-secondary" type="button" id="button-image-campaign_image">Select</button>
                                                    </span>
                                                    @if($result['admins'][0]->campaign_image)
                                                        <br>
                                                        @if (file_exists(public_path('/images/campaign/'.$result['admins'][0]->campaign_image)))
                                                            <img src="{{asset('/images/promotion/'.$result['admins'][0]->campaign_image)}}" width="50%" alt="main image">
                                                        @else
                                                            <img src="{{ $result['admins'][0]->campaign_image }}" width="100px" alt=""> 
                                                        @endif 
                                                    @endif
                                                </div>
                                            </div>  

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoOrg') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="organization_id" class='form-control field-validate select2' id="organization_id">
                                                        
                                                        @if($result['organisations'])
                                                            @if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT )
                                                                @foreach($result['organisations'] as $organisation)
                                                                    @if($organisation->id == auth()->user()->id)
                                                                    <option value="{{ $organisation->id }}" selected>{{ $organisation->company_name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                @foreach($result['organisations'] as $organisation)
                                                                <option value="{{ $organisation->id }}" @if($result['admins'][0]->org_id == $organisation->id) selected @endif>{{ $organisation->company_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.addPromoOrgtext') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoStart') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="date" class="form-control" name="period_start" id="period_start" value="{{ Carbon\Carbon::parse($result['admins'][0]->period_start)->format('Y-m-d') }}">
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addPromoStarttext') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                                <label for="name" class="col-sm-2 col-md-1 control-label">{{ trans('labels.addPromoEnd') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="date" class="form-control" name="period_end" id="period_end" value="{{ Carbon\Carbon::parse($result['admins'][0]->period_end)->format('Y-m-d') }}">
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addPromoEndtext') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoStartTime') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="time" class="form-control" name="start_time" id="start_time" value="{{ Carbon\Carbon::parse($result['admins'][0]->period_start)->format('H:i') }}">
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addPromoStartTimetext') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                                <label for="name" class="col-sm-2 col-md-1 control-label">{{ trans('labels.addPromoEndTime') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    <input type="time" class="form-control" name="end_time" id="end_time" value="{{ Carbon\Carbon::parse($result['admins'][0]->period_end)->format('H:i') }}">
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addPromoEndTimetext') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoDesc') }} </label>
                                                <div class="col-sm-10 col-md-7">
                                                    <textarea name="description" id="description" class="form-control form-control-solid" >{!! $result['admins'][0]->description !!}</textarea>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addPromoDesctext') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Status') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="status_id" class='form-control field-validate' id="status_id">
                                                        <option value="">{{ trans('labels.SelectStatus') }}</option>
                                                            <option value="0" {{ $result['admins'][0]->status == "0"? "selected": "" }}> {{ trans('labels.Disabled') }} </option>
                                                            <option value="1" {{ $result['admins'][0]->status == "1"? "selected": "" }}> {{ trans('labels.Enabled') }} </option>
                                                       
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.SelectStatus') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.campaign_response_text') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('response_text',  $result['admins'][0]->response_text, array('class'=>'form-control ', 'id'=>'response_text'), value(old('response_text'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.campaign_response_textText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.campaign_interested_action') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="interest_form" class='form-control field-validate' id="interest_form">
                                                        <option value="0" {{ $result['admins'][0]->interest_form == "0"? "selected": "" }}> {{ trans('labels.whatsapp_redirect') }} </option>
                                                        <option value="1" {{ $result['admins'][0]->interest_form == "1"? "selected": "" }}> {{ trans('labels.contact_form') }} </option>
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.campaign_interested_actionText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="Colours1" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.campaign_response_text_color') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                <input type="color" id="colorpicker" name="color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{$result['admins'][0]->response_text_color}}" class="form-control">
                                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.campaign_response_text_colorText') }}</span>
                                                </div>
                                                <div class="col-sm-2 col-md-3">
                                                <input type="text" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{$result['admins'][0]->response_text_color}}" id="hexcolor" class="form-control"></input>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="Colours2" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.campaign_response_button_color') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                <input type="color" id="colorpicker2" name="color2" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{$result['admins'][0]->response_button_color}}" class="form-control">
                                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.campaign_response_button_colorText') }}</span>
                                                </div>
                                                <div class="col-sm-2 col-md-3">
                                                <input type="text" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{$result['admins'][0]->response_button_color}}" id="hexcolor2" class="form-control"></input>
                                                </div>
                                            </div>
                                            
                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                                <a href="{{ URL::to('admin/campaigns')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<script type="text/javascript">
    
$(document).ready(function(){

     var imageid=$('#imag_exist').val();
 
  // alert(imageid);
     if(imageid>0){
        $("#checkbox_check").attr('disabled', false);

     }else{

        $("#checkbox_check").attr('disabled', 'disabled');
     }

     $('#selected').on('click', function(){
     var imageid=$('.image-picker').val();


     if(imageid){
        $("#checkbox_check").attr('disabled', false);

     }else{

        $("#checkbox_check").attr('disabled', 'disabled');
     }
  
   });
  // CKEDITOR.replace( 'description' );
  //   CKEDITOR.replace( 'location' );
   //  CKEDITOR.replace( 'fine_print' );
     var editor = CKEDITOR.replace( 'description', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );
     var editor1 = CKEDITOR.replace( 'location', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );
     var editor2 = CKEDITOR.replace( 'fine_print', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );
  
   });
</script>
<!-- multiple standalone button -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // main image
    document.getElementById('button-image-campaign_image').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'campaign_image';

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
<script type="text/javascript">
    $(document).ready(function() {
      $('#colorpicker').on('input', function() {
        $('#hexcolor').val(this.value);
      });
      $('#hexcolor').on('input', function() {
        $('#colorpicker').val(this.value);
      });

      $('#colorpicker2').on('input', function() {
        $('#hexcolor2').val(this.value);
      });
      $('#hexcolor2').on('input', function() {
        $('#colorpicker2').val(this.value);
      });
    });
</script>