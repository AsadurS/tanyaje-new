@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.Promotion') }}  <small>{{ trans('labels.EditPromo') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/promotions')}}"><i class="fa fa-industry"></i> {{ trans('labels.Promotion') }}</a></li>
                <li class="active">{{ trans('labels.EditPromo') }}</li>
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
                            <h3 class="box-title">{{ trans('labels.EditPromoInfo') }} </h3>
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


                                        {!! Form::open(array('url' =>'admin/updatepromotions', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                            {!! Form::hidden('myid', $result['myid'], array('id'=>'myid')) !!}
                                           
                                            
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoName') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('promo_name',   $result['admins'][0]->promotion_name, array('class'=>'form-control  field-validate', 'id'=>'promo_name'), value(old('promo_name'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addPromoNametext') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoMain') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <span style="display:flex;">
                                                        <input type="text" id="main_image" class="form-control" name="main_image" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->main_image }}">
                                                        <button class="btn btn-outline-secondary" type="button" id="button-image-main_image">Select</button>
                                                    </span>
                                                    @if($result['admins'][0]->main_image)
                                                        <br>
                                                        @if (file_exists(public_path('/images/promotion/'.$result['admins'][0]->main_image)))
                                                            <img src="{{asset('/images/promotion/'.$result['admins'][0]->main_image)}}" width="50%" alt="main image">
                                                        @else
                                                            <img src="{{ $result['admins'][0]->main_image }}" width="100px" alt=""> 
                                                        @endif 
                                                    @endif
                                                    <!-- <input type="hidden" name="old_main_image" value="{{$result['admins'][0]->main_image}}">
                                                    <input type="file" name="main_image" id="main_image" class="form-control"><br>
                                                    <img src="{{asset('images/promotion/'.$result['admins'][0]->main_image)}}" alt="main image" style="width:50%;"/> -->
                                                </div>
                                            </div>    

                                            <div class="form-group">
                                                <label for="TemplateCallIcon" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.addPromoAddImage') }}</label>
                                                <div class="col-sm-8 col-md-3">
                                                    <span style="display:flex;">
                                                        <input type="text" id="additional_images1" class="form-control" name="additional_images1" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->additional_images1 }}">
                                                        <button class="btn btn-outline-secondary" type="button" id="button-image-additional_images1">Select</button>
                                                    </span>
                                                    @if($result['admins'][0]->additional_images1)
                                                        <br>
                                                        @if (file_exists(public_path('/images/promotion/'.$result['admins'][0]->additional_images1)))
                                                            <img src="{{asset('/images/promotion/'.$result['admins'][0]->additional_images1)}}" width="50%" alt="main image">
                                                        @else
                                                            <img src="{{ $result['admins'][0]->additional_images1 }}" width="100px" alt=""> 
                                                        @endif 
                                                    @endif
                                                    <!-- <input type="hidden" name="old_additional_images1" value="{{$result['admins'][0]->additional_images1}}">
                                                    <input type="file" name="additional_images1" id="additional_images1" class="form-control"><br>
                                                    @if($result['admins'][0]->additional_images1)
                                                        <img src="{{asset('images/promotion/'.$result['admins'][0]->additional_images1)}}" alt="main image" style="width:50%;"/>
                                                    @endif -->
                                                </div>                                    
                                                <div class="col-sm-8 col-md-3">
                                                    <span style="display:flex;">
                                                        <input type="text" id="additional_images2" class="form-control" name="additional_images2" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->additional_images2 }}">
                                                        <button class="btn btn-outline-secondary" type="button" id="button-image-additional_images2">Select</button>
                                                    </span>
                                                    @if($result['admins'][0]->additional_images2)
                                                        <br>
                                                        @if (file_exists(public_path('/images/promotion/'.$result['admins'][0]->additional_images2)))
                                                            <img src="{{asset('/images/promotion/'.$result['admins'][0]->additional_images2)}}" width="50%" alt="main image">
                                                        @else
                                                            <img src="{{ $result['admins'][0]->additional_images2 }}" width="100px" alt=""> 
                                                        @endif 
                                                    @endif
                                                    <!-- <input type="hidden" name="old_additional_images2" value="{{$result['admins'][0]->additional_images2}}">
                                                    <input type="file" name="additional_images2" id="additional_images2" class="form-control"><br>
                                                    @if($result['admins'][0]->additional_images2)
                                                        <img src="{{asset('images/promotion/'.$result['admins'][0]->additional_images2)}}" alt="main image" style="width:50%;"/>
                                                    @endif -->
                                                </div>
                                                <div class="col-sm-8 col-md-3">
                                                    <span style="display:flex;">
                                                        <input type="text" id="additional_images3" class="form-control" name="additional_images3" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->additional_images3 }}">
                                                        <button class="btn btn-outline-secondary" type="button" id="button-image-additional_images3">Select</button>
                                                    </span>
                                                    @if($result['admins'][0]->additional_images3)
                                                        <br>
                                                        @if (file_exists(public_path('/images/promotion/'.$result['admins'][0]->additional_images3)))
                                                            <img src="{{asset('/images/promotion/'.$result['admins'][0]->additional_images3)}}" width="50%" alt="main image">
                                                        @else
                                                            <img src="{{ $result['admins'][0]->additional_images3 }}" width="100px" alt=""> 
                                                        @endif 
                                                    @endif
                                                    <!-- <input type="hidden" name="old_additional_images3" value="{{$result['admins'][0]->additional_images3}}">
                                                    <input type="file" name="additional_images3" id="additional_images3" class="form-control"><br>
                                                    @if($result['admins'][0]->additional_images3)
                                                        <img src="{{asset('images/promotion/'.$result['admins'][0]->additional_images3)}}" alt="main image" style="width:50%;"/>
                                                    @endif -->
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
                                                                    <option value="{{ $organisation->id }}" selected>{{ $organisation->first_name }} {{ $organisation->last_name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                @foreach($result['organisations'] as $organisation)
                                                                <option value="{{ $organisation->id }}" @if($result['admins'][0]->organisation == $organisation->id) selected @endif>{{ $organisation->first_name }} {{ $organisation->last_name }}</option>
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
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoSegment') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="segment_id" class='form-control field-validate' id="segment_id">
                                                        <option value="">{{ trans('labels.addPromoSegmenttext') }}</option>
                                                        @if($result['segments'])
                                                            @foreach($result['segments'] as $segment)
                                                            <option value="{{ $segment->segment_id }}" @if($result['admins'][0]->segment_id == $segment->segment_id) selected @endif>{{ $segment->segment_name }}</option>
                                                            @endforeach
                                                        @endif
                                                       
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.addPromoSegmenttext') }}</span>
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
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoMax') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::number('max_validation',  $result['admins'][0]->max_redeem, array('class'=>'form-control  field-validate', 'id'=>'max_validation'), value(old('max_validation'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addPromoMaxtext') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                     
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoSerial') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::number('serial',  $result['admins'][0]->serial_number, array('class'=>'form-control  field-validate', 'id'=>'serial'), value(old('serial'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addPromoSerialtext') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoPrefix') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('prefix',  $result['admins'][0]->prefix, array('class'=>'form-control  field-validate', 'id'=>'prefix'), value(old('prefix'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addPromoPrefixtext') }}</span>
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
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoPrint') }} </label>
                                                <div class="col-sm-10 col-md-7">
                                                    <textarea name="fine_print" id="fine_print" class="form-control form-control-solid" >{!! $result['admins'][0]->fine_print !!}</textarea>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addPromoPrinttext') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoLocal') }} </label>
                                                <div class="col-sm-10 col-md-7">
                                                    <textarea name="location" id="location" class="form-control form-control-solid" >{!! $result['admins'][0]->location !!}</textarea>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addPromoLocaltext') }}</span>
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
                                            
                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                                <a href="{{ URL::to('admin/promotions')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
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
    document.getElementById('button-image-main_image').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'main_image';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // additional_image1
    document.getElementById('button-image-additional_images1').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'additional_images1';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // additional_image2
    document.getElementById('button-image-additional_images2').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'additional_images2';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // additional_image3
    document.getElementById('button-image-additional_images3').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'additional_images3';

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