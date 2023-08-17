@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.link_variant') }}  <small>{{ trans('labels.editvariant') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/model/display')}}"><i class="fa fa-database"></i> {{ trans('labels.link_variant') }}</a></li>
                <li class="active">{{ trans('labels.editvariant') }}</li>
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
                            <h3 class="box-title">{{ trans('labels.editvariant') }} </h3>
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


                                            {!! Form::open(array('url' =>'admin/variant/update', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                            {!! Form::hidden('id',  $editModel[0]->variant_id , array('class'=>'form-control', 'id'=>'id')) !!}
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Make') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="model_make_id" class='form-control field-validate select2'>
                                                        @foreach( $result['make'] as $make_data)
                                                            <option
                                                                    @if( $make_data->make_id == $editModel[0]->make_id)
                                                                    selected
                                                                    @endif
                                                                    value="{{ $make_data->make_id }}"> {{ $make_data->make_name }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ChooseModelMake') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Model') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                               <!--     <select name="model_make_id" class='form-control'>
                                                       @foreach( $result['model'] as $model_data)
                                                           <option value="{{ $model_data->model_id }}"> {{ $model_data->model_name }} </option>
                                                        @endforeach
                                                    </select> -->
                                                    <select name="models_id" class='form-control select2'>
                                                    @foreach( $result['model'] as $model_data)
                                                            <option
                                                                    @if( $model_data->model_id == $editModel[0]->model_id)
                                                                    selected
                                                                    @endif
                                                                    value="{{ $model_data->model_id }}"> {{ $model_data->model_name }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.ChooseModel') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.ModelName') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('name',  $editModel[0]->name , array('class'=>'form-control field-validate', 'id'=>'name'), value(old('name'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ModelNameText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                         

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Image') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <span style="display:flex;">
                                                        <input type="text" id="image_label" class="form-control" name="image_label" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $editModel[0]->image }}">
                                                        <button class="btn btn-outline-secondary" type="button" id="button-image">Select</button>
                                                    </span>
                    
                                                        <br>
                                                      
                                                        <img id="image-preview" src="{{ $editModel[0]->image }}" width="50%" alt="preview image">                       
                                                </div>
                                            </div>   

                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                                <a href="{{ URL::to('admin/model/display')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
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
    <script src="{!! asset('admin/plugins/jQuery/jQuery-2.2.0.min.js') !!}"></script>

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
    $(document).ready(function() {
        $('select[name="model_make_id"]').on('change', function() {
            var MakeID = $(this).val();
            if(MakeID) {
                $.ajax({
                    url: 'ajax/'+MakeID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {                    
                        $('select[name="models_id"]').empty();
                        $('select[name="models_id"]').append('<option value="">{{ trans('labels.SelectModel') }}</option>');
                        $.each(data, function(key, value) {
                            $('select[name="models_id"]').append('<option value="'+ value.model_id +'">'+ value.model_name +'</option>');
                        });


                    }
                });
            }else{
                $('select[name="models_id"]').empty();
            }
        });
    });
</script>

@endsection
