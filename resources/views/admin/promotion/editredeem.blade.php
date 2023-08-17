@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.EditRedeemTile') }}  <small>{{ trans('labels.EditRedeemInfo') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/promotions')}}"><i class="fa fa-industry"></i> {{ trans('labels.Promotion') }}</a></li>
                <li class="active">{{ trans('labels.EditRedeemInfo') }}</li>
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
                            <h3 class="box-title">{{ trans('labels.EditRedeemInfo') }} </h3>
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


                                        {!! Form::open(array('url' =>'admin/updateredeem', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                            {!! Form::hidden('myid', $result['myid'], array('id'=>'myid')) !!}
                                           
                                            
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.redeemPromotion') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="promotion_id" class='form-control field-validate select2' id="promotion_id">
                                                        <option>{{ trans('labels.redeemPromotionText') }}</option>
                                                        @if (count($result['promos']) > 0)
                                                        @foreach ($result['promos']  as $key=>$promotions)
                                                        <option value="{{ $promotions->promotion_id }}"  {{ $result['admins'][0]->promotion_id == $promotions->promotion_id? "selected": "" }}>{{ $promotions->promotion_name }}</option>
                                                        @endforeach
                                                        @endif
                                                       
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.redeemPromotionText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                     


                                        


                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.redeemDates') }}</label>
                                                <div class="col-sm-10 col-md-3">
                                                    {!! Form::datetime('redeem_date', $result['admins'][0]->redeem_date, array('class'=>'form-control  field-validate datetimepicker', 'id'=>'redeem_date'), value(old('redeem_date'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.redeemDates') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                     
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.addPromoSerial') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('serial',  $result['admins'][0]->serial_prefix, array('class'=>'form-control  field-validate', 'id'=>'serial'), value(old('serial'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.addPromoSerialtext') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                   


                                            

                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                             
                                                <a href="{{ URL::to('admin/redeemlisting')}}/{{ $result['admins'][0]->promotion_id }}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
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