@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.Segment') }}  <small>{{ trans('labels.EditCurrentSegment') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/segments')}}"><i class="fa fa-industry"></i> {{ trans('labels.Segment') }}</a></li>
                <li class="active">{{ trans('labels.EditSegment') }}</li>
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
                            <h3 class="box-title">{{ trans('labels.EditSegmentInfo') }} </h3>
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
                                    <!-- /.box-header -->
                                        <!-- form start -->
                                        <div class="box-body">


                                            {!! Form::open(array('url' =>'admin/updateSegments', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                            {!! Form::hidden('myid', $result['myid'], array('id'=>'myid')) !!}
                                            
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.SegmentName') }}<span style="color:red;">*</span></label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('segment_name', $result['admins'][0]->segment_name, array('class'=>'form-control  field-validate', 'id'=>'segment_name')) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SegmentNameText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Sort') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('sort', $result['admins'][0]->sort, array('class'=>'form-control ', 'id'=>'sort')) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.sortText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                                <a href="{{ URL::to('admin/segments')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
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
  
  
   });




</script>