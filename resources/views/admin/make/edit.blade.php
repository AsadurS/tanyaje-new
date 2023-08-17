@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.Make') }}  <small>{{ trans('labels.EditCurrentMake') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/make/display')}}"><i class="fa fa-industry"></i> {{ trans('labels.Make') }}</a></li>
                <li class="active">{{ trans('labels.EditMake') }}</li>
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
                            <h3 class="box-title">{{ trans('labels.EditMakeInfo') }} </h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box box-info">
                                        <br>

                                        @if (session('update'))
                                            <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> {{ session('update') }} </strong>
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


                                            {!! Form::open(array('url' =>'admin/make/update', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                            {!! Form::hidden('id',  $editMake[0]->id , array('class'=>'form-control', 'id'=>'id')) !!}
                                            {!! Form::hidden('oldImage', $editMake[0]->image , array('id'=>'oldImage')) !!}

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Name') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('name',  $editMake[0]->name , array('class'=>'form-control field-validate', 'id'=>'name'), value(old('name'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.MakeNameText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                    <!--        <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Logo') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <div class="modal fade" id="Modalmanufactured" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" id="closemodal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                                    <h3 class="modal-title text-primary" id="myModalLabel">{{ trans('labels.Choose Image') }} </h3>
                                                                </div>
                                                                <div class="modal-body manufacturer-image-embed">
                                                                    @if(isset($allimage))
                                                                    <select class="image-picker show-html " name="image_id" id="select_img">
                                                                        <option value=""></option>
                                                                        @foreach($allimage as $key=>$image)
                                                                        <option data-img-src="{{asset($image->path)}}" class="imagedetail" data-img-alt="{{$key}}" value="{{$image->id}}"> {{$image->id}} </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                <a href="{{url('admin/media/add')}}" target="_blank" class="btn btn-primary pull-left" >{{ trans('labels.Add Image') }}</a>
                                                                <button type="button" class="btn btn-default refresh-image"><i class="fa fa-refresh"></i></button>
                                                                <button type="button" class="btn btn-primary" id="selected" data-dismiss="modal">Done</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {!! Form::button(trans('labels.Add Image'), array('id'=>'newImage','class'=>"btn btn-primary ", 'data-toggle'=>"modal", 'data-target'=>"#Modalmanufactured" )) !!}
                                                    <br>
                                                    <div id="selectedthumbnail" class="selectedthumbnail col-md-5"> </div>
                                                    <div class="closimage">
                                                        <button type="button" class="close pull-left image-close " id="image-close"
                                                            style="display: none; position: absolute;left: 105px; top: 54px; background-color: black; color: white; opacity: 2.2; " aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.UploadMakeImage') }}</span>

                                                </div>
                                            </div> -->

                                       

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Image') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <span style="display:flex;">
                                                        <input type="text" id="image_label" class="form-control" name="image_label" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $editMake[0]->image }}">
                                                        <button class="btn btn-outline-secondary" type="button" id="button-image">Select</button>
                                                    </span>
                    
                                                        <br>
                                                      
                                                        <img id="image-preview" src="{{ $editMake[0]->image }}" width="50%" alt="preview image">                       
                                                </div>
                                            </div>   

                                        <!--   <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label"></label>
                                                <div class="col-sm-10 col-md-4">
                                                <span class="help-block " style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.OldLogo') }}</span>
                                                <br>
                                                <img src="{{asset($editMake[0]->imgpath)}}" alt="" width=" 100px">
                                                </div>
                                            </div> -->
                                             
                                               @php $true="" @endphp

                                               @if($editMake[0]->is_feature == 1)
                                                       @php  $true =true; @endphp

                                                  @else
                                                       
                                                        @php  $true =false; @endphp
                                               @endif

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('Is Feature') }}</label>
                                                <div class="col-sm-10 col-md-4" style="padding-top: 7px;">
                                                    <label style="margin-bottom:0">
                                                        {!! Form::checkbox('is_feature', 1, $true, [ 'id'=>'checkbox_check' ]) !!}
                                                    </label>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('Is Feature') }}</span>
                                                    

                                                </div>
                                            </div>

                                        <input type="hidden" id="imag_exist" name="image_existed" value="{{ $editMake[0]->image }} ">
                                        

                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                                <a href="{{ URL::to('admin/make/display')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
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
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {

document.getElementById('button-image').addEventListener('click', (event) => {
  event.preventDefault();

  window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
});
});

// set file link
function fmSetLink($url) {
document.getElementById('image_label').value = $url;
document.getElementById('image-preview').src = $url;
}
</script>
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