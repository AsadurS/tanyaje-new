@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.Model') }} <small>{{ trans('labels.AddNewModel') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/model/display')}}"><i class="fa fa-database"></i> {{ trans('labels.Model') }}</a></li>
                <li class="active">{{ trans('labels.AddModel') }}</li>
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
                            <h3 class="box-title">{{ trans('labels.AddNewModel') }} </h3>
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
                                            {!! Form::open(array('url' =>'admin/model/add', 'method'=>'post', 'class' => 'form-horizontal form-validate ', 'enctype'=>'multipart/form-data')) !!}
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Make') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="model_make_id" class='form-control select2'>
                                                        @foreach( $result['make'] as $make_data)
                                                            <option value="{{ $make_data->make_id }}"> {{ $make_data->make_name }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.ChooseModelMake') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.ModelName') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('name',  '', array('class'=>'form-control  field-validate', 'id'=>'name'), value(old('name'))) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ModelNameText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                       <!--     <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Image') }}</label>
                                                <div class="col-sm-10 col-md-4" style="display:flex;">
                                                <input type="text" id="image_label" class="form-control" name="image_label" readonly="readonly" aria-label="Image" aria-describedby="button-image">
                                                    <button class="btn btn-outline-secondary" type="button" id="button-image">Select</button>
                                                </div>
                                            </div> -->

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Image') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <span style="display:flex;">
                                                        <input type="text" id="image_label" class="form-control" name="image_label" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                                        <button class="btn btn-outline-secondary" type="button" id="button-image">Select</button>
                                                    </span>
                    
                                                        <br>
                                                      
                                                        <img id="image-preview" src="" width="50%" alt="preview image">
                                                </div>
                                            </div>   

                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.submit') }}</button>
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
@endsection
