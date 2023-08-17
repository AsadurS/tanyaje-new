@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.linkCampaignEmail') }}  <small>{{ trans('labels.linkCampaignEmail') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
    
                <li class="active">{{ trans('labels.linkCampaignEmail') }}</li>
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
                            <h3 class="box-title">{{ trans('labels.linkCampaignEmail') }} </h3>
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


                                        {!! Form::open(array('url' =>'admin/updatecampaignemailtemplate', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                           
                                        <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">Sale Advisor Subject</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('sa_subject',  $result['admins'][0]->sa_subject, array('class'=>'form-control  field-validate', 'id'=>'serial'), value(old('sa_subject'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;"></span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.campaignEmailSa') }} </label>
                                                <div class="col-sm-10 col-md-7">
                                                    <textarea name="sa_email" id="sa_email" class="form-control form-control-solid" >{!! $result['admins'][0]->sa_email ?? 'N/A' !!}</textarea>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.campaignEmailSaDesc') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                        
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>






<script type="text/javascript">
    
$(document).ready(function(){

    var editor = CKEDITOR.replace( 'sa_email', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );

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

  
   });




</script>