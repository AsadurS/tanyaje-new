@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.linkPromoEmail') }}  <small>{{ trans('labels.linkPromoEmail') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
    
                <li class="active">{{ trans('labels.linkPromoEmail') }}</li>
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
                            <h3 class="box-title">{{ trans('labels.linkPromoEmail') }} </h3>
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


                                        {!! Form::open(array('url' =>'admin/updateemailtemplate', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                           
                                        <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">Customer Subject</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('customer_subject',  $result['admins'][0]->customer_subject, array('class'=>'form-control  field-validate', 'id'=>'serial'), value(old('customer_subject'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;"></span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.promoEmailCustomer') }} </label>
                                                <div class="col-sm-10 col-md-7">
                                                    <textarea name="customer_email" id="customer_email" class="form-control form-control-solid" >{!! $result['admins'][0]->customer_email ?? 'N/A' !!}</textarea>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.promoEmailCustomerDesc') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">Organiser Subject</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('organiser_subject',  $result['admins'][0]->organiser_subject, array('class'=>'form-control  field-validate', 'id'=>'organiser_subject'), value(old('organiser_subject'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;"></span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.promoEmailOrg') }} </label>
                                                <div class="col-sm-10 col-md-7">
                                                    <textarea name="organiser_email" id="organiser_email" class="form-control form-control-solid" >{!! $result['admins'][0]->organiser_email ?? 'N/A' !!}</textarea>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.promoEmailOrgDesc') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">Admin Subject</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('admin_subject',  $result['admins'][0]->admin_subject, array('class'=>'form-control  field-validate', 'id'=>'admin_subject'), value(old('admin_subject'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;"></span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.promoEmailAdmin') }} </label>
                                                <div class="col-sm-10 col-md-7">
                                                    <textarea name="admin_email" id="admin_email" class="form-control form-control-solid" >{!! $result['admins'][0]->admin_email ?? 'N/A' !!}</textarea>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.promoEmailAdminDesc') }}</span>
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

    var editor = CKEDITOR.replace( 'customer_email', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );
    var editor1 = CKEDITOR.replace( 'organiser_email', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );
    var editor2 = CKEDITOR.replace( 'admin_email', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );
   // CKEDITOR.replace( 'customer_email' );
  //  CKEDITOR.replace( 'organiser_email' );
  //  CKEDITOR.replace( 'admin_email' );

     var imageid=$('#imag_exist').val();

   //  $('.datetimepicker').datepicker();
 
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