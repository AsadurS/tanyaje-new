@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.editItem') }} <small>{{ trans('labels.editItem') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{url('admin/car/display')}}"><i class="fa fa-car"></i>{{ trans('labels.listingAllItem') }}</a></li>
                <li class="active">{{ trans('labels.editItem') }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->

            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">

                    {!! Form::open(array('url' =>'admin/car/update', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                    {!! Form::hidden('car_id', $result['car']->car_id, array('class'=>'form-control', 'id'=>'car_id'))!!}
                    {!! Form::hidden('oldImage', $result['car']->image , array('id'=>'oldImage')) !!}
                    {!! Form::hidden('oldPdf', $result['car']->pdf , array('id'=>'oldPdf')) !!}
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">

                                      @if(session()->has('message'))
                                          <div class="alert alert-success alert-dismissible" role="alert">
                                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                              {{ session()->get('message') }}
                                          </div>
                                      @endif
                                        <!-- /.box-header -->
                                        <!-- form start -->
                                        <div class="box-body">

                                            <h4>{{ trans('labels.itemSpecific') }} </h4>
                                            <hr>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.ListingName') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('title', $result['car']->title, array('class'=>'form-control field-validate', 'id'=>'title'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ListingNameTest') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="item_type" class="col-sm-2 col-md-3 control-label">{{ trans('labels.itemType') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="item_type_id" class='form-control' id="item_type_id" onchange="getItemType(this);">
                                                        @foreach( $result['item_type'] as $item_type )
                                                         <option value="{{ $item_type->id }}" {{ $result['car']->item_type_id == $item_type->id? "selected": "" }}>{{ $item_type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.chooseItemType') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="sc_account" class="col-sm-2 col-md-3 control-label">{{ trans('labels.SpincarAccount') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="sp_account" class='form-control' id="sp_account" onchange="getval(this);">
                                                        <option value="tanyaje" {{ $result['car']->sp_account == "tanyaje"? "selected": "" }}>Tanyaje </option>
                                                        <option value="simedarbymalaysia" {{ $result['car']->sp_account == "simedarbymalaysia"? "selected": "" }}>Sime Darby Malaysia</option>
                                                        <option value="no_sp_account" {{ $result['car']->sp_account == "no_sp_account"? "selected": "" }}>No Account</option>
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.ChooseSpinCarAccount') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group no_spin_car default_attribute">
                                                <label for="pdf" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Images') }} </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <span style="display:flex;">
                                                        <input type="text" id="car_image" class="form-control" name="car_image" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['car']->image }}">
                                                        <button class="btn btn-outline-secondary" type="button" id="button-image-car_image">Select</button>
                                                    </span>
                                                    @if($result['car']->image)
                                                        <br>
                                                        @if (file_exists(public_path('/images/logo/'.$result['car']->image)))
                                                            <img src="{{asset('/images/items/'.$result['car']->image)}}" width="50%" alt="logo">
                                                        @else
                                                            <img src="{{ $result['car']->image }}" width="100px" alt=""> 
                                                        @endif 
                                                    @endif
                                                    <!-- <input type="file" name="car_image[]" id="car_image" multiple><br>
                                                    <span class="help-block" style="color:gray;font-weight: normal;font-size: 11px;margin-top: 0;">{{ trans('labels.CarImageText') }}</span>

                                                    @if($result['car_images'])
                                                    <table class="table table-bordered table-striped">
                                                        <tr>
                                                            <th>Image</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        @foreach($result['car_images'] as $car_imagess)
                                                        <tr>
                                                            <td><img src="{{asset('images/items/'.$car_imagess->filename)}}" style="width:150px;"></td>
                                                            <td>
                                                                <a href="{{ URL::to('admin/car/deletecarimage/' . $car_imagess->id) }}" class="badge bg-red btn-delete">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i> Remove
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                    @endif -->
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.VIN') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('vim', $result['car']->vim, array('class'=>'form-control', 'id'=>'vim'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.VINText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.YearMake') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('year_make', $result['car']->year_make, array('class'=>'form-control', 'id'=>'year_make')) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.CarYearMakeText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.CarYearMakeText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Brand') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="make_id" class='form-control select2' id="choose_make_id">
                                                        <option value="">{{ trans('labels.SelectBrand') }}</option>
                                                        @foreach( $result['make'] as $make_data)
                                                            <option
                                                                    @if( $make_data->make_id == $result['car']->make_id)
                                                                    selected
                                                                    @endif
                                                                    value="{{ $make_data->make_id }}"> {{ $make_data->make_name }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ChooseBrand') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group ">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Model') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="model_id" class='form-control modelContent select2'>
                                                        <option value="">{{ trans('labels.SelectModel') }}</option>
                                                        @foreach( $result['model'] as $model_data)
                                                            <option
                                                                    @if( $model_data->model_id == $result['car']->model_id)
                                                                    selected
                                                                    @endif
                                                                    value="{{ $model_data->model_id }}"> {{ $model_data->model_name }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ChooseModel') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.link_variant') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="variant_id" class='form-control select2'>
                                                        <option value="">{{ trans('labels.SelectVariant') }}</option>
                                                        @foreach( $result['variants'] as $variant_data)
                                                            <option
                                                                    @if( $variant_data->variant_id == $result['car']->variant_id)
                                                                    selected
                                                                    @endif
                                                                    value="{{ $variant_data->variant_id }}"> {{ $variant_data->variant_name }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.SelectVariant') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Category') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="type_id" class='form-control'>
                                                        <option value="">{{ trans('labels.SelectCategory') }}</option>
                                                        @foreach( $result['type'] as $type_data)
                                                            <option
                                                                    @if( $type_data->type_id == $result['car']->type_id)
                                                                    selected
                                                                    @endif
                                                                    value="{{ $type_data->type_id }}"> {{ $type_data->type_name }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ChooseCategory') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="status" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Condition') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control" name="status">
                                                        <option @if($result['car']->status == 0)
                                                            selected
                                                            @endif
                                                            value="0">{{ trans('labels.NewArrivals') }}</option>
                                                        <option @if($result['car']->status == 1)
                                                            selected
                                                            @endif
                                                            value="1">{{ trans('labels.New') }}</option>
                                                        <option @if($result['car']->status == 2)
                                                            selected
                                                            @endif
                                                            value="2">{{ trans('labels.Used') }}</option>
                                                        <option @if($result['car']->status == 3)
                                                                selected
                                                                @endif
                                                                value="3">{{ trans('labels.Recond') }}</option>
                                                        <option @if($result['car']->status == 4)
                                                                selected
                                                                @endif
                                                                value="4">{{ trans('labels.Sold') }}</option>
                                                    </select><span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ChooseConditionText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="pdf" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Pdf') }} </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <!-- <input type="file" name="pdf"><br> -->
                                                    <span style="display:flex;">
                                                        <input type="text" id="pdf" class="form-control" name="pdf" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['car']->pdf }}">
                                                        <button class="btn btn-outline-secondary" type="button" id="button-image-pdf">Select</button>
                                                    </span>
                                                    <span class="help-block" style="color:gray;font-weight: normal;font-size: 11px;margin-top: 0;">{{ trans('labels.PdfText') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="link" class="col-sm-2 col-md-3 control-label">{{ trans('Extra Link') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('extra_link',  $result['car']->extra_link, array('class'=>'form-control', 'id'=>'extra_link'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('Extra Link') }}</span>
                                                    {{--                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>--}}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="link" class="col-sm-2 col-md-3 control-label">{{ trans('Extra Link Label') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('extra_link_label',  $result['car']->extra_link_label, array('class'=>'form-control', 'id'=>'extra_link_label'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('Extra Link Label') }}</span>
                                                    {{--                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>--}}
                                                </div>
                                            </div>
                                            <div class="form-group default_attribute">
                                                <label for="oldpdf" class="col-sm-2 col-md-3 control-label"></label>
                                                <div class="col-sm-10 col-md-4">
                                                    <span class="help-block " style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.OldPdf') }}</span>
                                                    @if($result['car']->pdf !== null)
                                                    <a href="{{asset($result['car']->pdf)}}" download="{{$result['car']->car_id}}">download</a>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Description') }}</label>
                                                <div class="col-sm-10 col-md-8">
                                                    <textarea id="text-area" name="html_editor" class="form-control" rows="5" maxlength="7000" onkeyup="textareaLengthCheck(this)">{{stripslashes($result['car']->html_editor)}}</textarea>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;display:inline;">
                                                        {{ trans('labels.EditorText') }} 
                                                    </span> 
                                                    <span id="lblRemainingCount" style="float:right;display:inline;"></span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Price') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('price', $result['car']->price, array('class'=>'form-control field-validate', 'id'=>'price')) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.CarPriceText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.CarPriceText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.FuelType') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <input type="radio" name="fuel_type" value="" @if( is_null($result['car']->fuel_type)) checked @endif @if(empty($result['car']->fuel_type)) checked @endif> None&nbsp;
                                                    <input type="radio" name="fuel_type" value="petrol" @if( $result['car']->fuel_type == "petrol") checked @endif> Petrol&nbsp;
                                                    <input type="radio" name="fuel_type" value="diesel" @if( $result['car']->fuel_type == "diesel") checked @endif> Diesel&nbsp;
                                                    <input type="radio" name="fuel_type" value="hybrid" @if( $result['car']->fuel_type == "hybrid") checked @endif> Hybrid&nbsp;
                                                    <input type="radio" name="fuel_type" value="electric" @if( $result['car']->fuel_type == "electric") checked @endif> Electric
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.ChooseFuelTypeText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.ChooseFuelTypeText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Features') }}</label>
                                                <div class="col-sm-10 col-md-8" style="padding-right:0px;padding-left:0px;">
                                                    <?php $features_data = explode(',', $result['car']->features);$num = count($features_data);?>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="3rd Rear Seats" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "3rd Rear Seats"){echo "checked";}}?>> 3rd Rear Seats&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Auxiliary Audio Input" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Auxiliary Audio Input"){echo "checked";}}?>> Auxiliary Audio Input&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Cloth Seats" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Cloth Seats"){echo "checked";}}?>> Cloth Seats&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Overhead Airbags" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Overhead Airbags"){echo "checked";}}?>> Overhead Airbags&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Power Seats" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Power Seats"){echo "checked";}}?>> Power Seats&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Turbo Charged Engine" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Turbo Charged Engine"){echo "checked";}}?>> Turbo Charged Engine
                                                    </div>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="ABS Brakes" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "ABS Brakes"){echo "checked";}}?>> ABS Brakes&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Blind Spot Monitor" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Blind Spot Monitor"){echo "checked";}}?>> Blind Spot Monitor&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="DVD Video System" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "DVD Video System"){echo "checked";}}?>> DVD Video System&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Parking Sensors" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Parking Sensors"){echo "checked";}}?>> Parking Sensors&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Rear A/C Seats" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Rear A/C Seats"){echo "checked";}}?>> Rear A/C Seats&nbsp;
                                                    </div>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="AM/FM Stereo" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "AM/FM Stereo"){echo "checked";}}?>> AM/FM Stereo&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Bluetooh" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Bluetooh"){echo "checked";}}?>> Bluetooh&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Leather Seats" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Leather Seats"){echo "checked";}}?>> Leather Seats&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Power Locks" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Power Locks"){echo "checked";}}?>> Power Locks&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Rear View Camera" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Rear View Camera"){echo "checked";}}?>> Rear View Camera&nbsp;
                                                    </div>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Keyless Entry System" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Keyless Entry System"){echo "checked";}}?>> Keyless Entry System&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="USB" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "USB"){echo "checked";}}?>> USB&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Navigation Systems" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Navigation Systems"){echo "checked";}}?>> Navigation Systems&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Power Mirrors" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Power Mirrors"){echo "checked";}}?>> Power Mirrors&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Push Start" <?php for($i=0;$i<$num;$i++){if($features_data[$i] == "Push Start"){echo "checked";}}?>> Push Start&nbsp;
                                                    </div>
                                                    <div class="col-sm-10 col-md-12">
                                                        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;display:inline;">
                                                            {{ trans('labels.SelectFeaturesText') }}
                                                        </span>
                                                        <span class="help-block hidden">{{ trans('labels.SelectFeaturesText') }}</span>&nbsp;&nbsp;
                                                        <input id="selectAll" type="checkbox" name="checkall" value="Tick All"> Tick All
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Seats') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {{Form::number('seats', $result['car']->seats,['min'=>1,'max'=>18,'class'=>'form-control'])}}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.CarSeatsText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.CarSeatsText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Transmission') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <input type="text" name="transmission" class="form-control" value="{{$result['car']->transmission}}">
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.ChooseTransmissionText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.ChooseTransmissionText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Mileage') }}</label>
                                                <div class="col-sm-10 col-md-8" style="padding-right:0px;padding-left:0px;">
                                                    <?php $mileage_data = explode(',', $result['car']->mileage);$num = count($mileage_data);?>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input type="checkbox" name="mileage[]" value="0" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "0"){echo "checked";}}?>> 0 <br>
                                                        <input type="checkbox" name="mileage[]" value="0 - 5000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "0 - 5000"){echo "checked";}}?>> 0 - 5000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="5001 - 10000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "5001 - 10000"){echo "checked";}}?>> 5001 - 10000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="10001 - 20000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "10001 - 20000"){echo "checked";}}?>> 10001 - 20000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="20001 - 30000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "20001 - 30000"){echo "checked";}}?>> 20001 - 30000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="30001 - 40000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "30001 - 40000"){echo "checked";}}?>> 30001 - 40000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="40001 - 50000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "40001 - 50000"){echo "checked";}}?>> 40001 - 50000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="50001 - 60000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "50001 - 60000"){echo "checked";}}?>> 50001 - 60000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="60001 - 70000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "60001 - 70000"){echo "checked";}}?>> 60001 - 70000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="70001 - 80000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "70001 - 80000"){echo "checked";}}?>> 70001 - 80000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="80001 - 90000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "80001 - 90000"){echo "checked";}}?>> 80001 - 90000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="90001 - 100000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "90001 - 100000"){echo "checked";}}?>> 90001 - 100000&nbsp;<br>
                                                    </div>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input type="checkbox" name="mileage[]" value="100001 - 110000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "100001 - 110000"){echo "checked";}}?>> 100001 - 110000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="110001 - 120000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "110001 - 120000"){echo "checked";}}?>> 110001 - 120000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="120001 - 130000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "120001 - 130000"){echo "checked";}}?>> 120001 - 130000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="130001 - 140000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "130001 - 140000"){echo "checked";}}?>> 130001 - 140000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="140001 - 150000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "140001 - 150000"){echo "checked";}}?>> 140001 - 150000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="150001 - 160000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "150001 - 160000"){echo "checked";}}?>> 150001 - 160000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="160001 - 170000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "160001 - 170000"){echo "checked";}}?>> 160001 - 170000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="170001 - 180000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "170001 - 180000"){echo "checked";}}?>> 170001 - 180000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="180001 - 190000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "180001 - 190000"){echo "checked";}}?>> 180001 - 190000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="190001 - 200000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "190001 - 200000"){echo "checked";}}?>> 190001 - 200000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="200001 - 210000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "200001 - 210000"){echo "checked";}}?>> 200001 - 210000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="210001 - 220000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "210001 - 220000"){echo "checked";}}?>> 210001 - 220000&nbsp;<br>
                                                    </div>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input type="checkbox" name="mileage[]" value="220001 - 230000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "220001 - 230000"){echo "checked";}}?>> 220001 - 230000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="230001 - 240000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "230001 - 240000"){echo "checked";}}?>> 230001 - 240000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="240001 - 250000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "240001 - 250000"){echo "checked";}}?>> 240001 - 250000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="250001 - 260000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "250001 - 260000"){echo "checked";}}?>> 250001 - 260000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="260001 - 270000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "260001 - 270000"){echo "checked";}}?>> 260001 - 270000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="270001 - 280000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "270001 - 280000"){echo "checked";}}?>> 270001 - 280000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="280001 - 290000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "280001 - 290000"){echo "checked";}}?>> 280001 - 290000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="290001 - 300000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "290001 - 300000"){echo "checked";}}?>> 290001 - 300000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="300001 - 310000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "300001 - 310000"){echo "checked";}}?>> 300001 - 310000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="310001 - 320000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "310001 - 320000"){echo "checked";}}?>> 310001 - 320000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="320001 - 330000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "320001 - 330000"){echo "checked";}}?>> 320001 - 330000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="330001 - 340000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "330001 - 340000"){echo "checked";}}?>> 330001 - 340000&nbsp;<br>
                                                    </div>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input type="checkbox" name="mileage[]" value="340001 - 350000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "340001 - 350000"){echo "checked";}}?>> 340001 - 350000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="350001 - 360000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "350001 - 360000"){echo "checked";}}?>> 350001 - 360000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="360001 - 370000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "360001 - 370000"){echo "checked";}}?>> 360001 - 370000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="370001 - 380000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "370001 - 380000"){echo "checked";}}?>> 370001 - 380000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="380001 - 390000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "380001 - 390000"){echo "checked";}}?>> 380001 - 390000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="390001 - 400000" <?php for($i=0;$i<$num;$i++){if($mileage_data[$i] == "390001 - 400000"){echo "checked";}}?>> 390001 - 400000&nbsp;<br>
                                                    </div>
                                                    <div class="col-sm-10 col-md-12">
                                                        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                            {{ trans('labels.CarMileageText') }}
                                                        </span>
                                                        <span class="help-block hidden">{{ trans('labels.CarMileageText') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Color') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('color', $result['car']->color, array('class'=>'form-control', 'id'=>'color')) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.ColorText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.ColorText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.EngineCapacity') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('engine_capacity', $result['car']->engine_capacity, array('class'=>'form-control', 'id'=>'engine_capacity')) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.CarEngineCapacityText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.CarEngineCapacityText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="merchant" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Organisation') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="merchant_id" class='form-control field-validate select2'>
                                                        <option value="">{{ trans('labels.SelectOrganisationText') }}</option>
                                                        @if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT )
                                                            @foreach( $result['merchant'] as $merchant_data)
                                                                @if($merchant_data->id == auth()->user()->id)
                                                                <option value="{{ $merchant_data->id }}" @if($result['car']->user_id == $merchant_data->id) selected @endif> {{ $merchant_data->company_name ?? $merchant_data->first_name." ".$merchant_data->last_name }}</option>
                                                                @endif
                                                            @endforeach
                                                        @elseif( Auth()->user()->role_id == \App\Models\Core\User::ROLE_NORMAL_ADMIN || Auth()->user()->role_id == \App\Models\Core\User::ROLE_SUPER_ADMIN )
                                                            @foreach( $result['merchant'] as $merchant_data)
                                                                <option value="{{ $merchant_data->id }}" @if($result['car']->user_id == $merchant_data->id) selected @endif> {{ $merchant_data->company_name ?? $merchant_data->first_name." ".$merchant_data->last_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SelectOrganisationText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group default_attribute">
                                                <label for="merchant" class="col-sm-2 col-md-3 control-label">{{ trans('labels.IsSold') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control" name="is_sold">
                                                        <option value="0">{{ trans('labels.No') }}</option>
                                                        <option value="1" {{ $result['car']->is_sold ? 'selected' : '' }}>{{ trans('labels.Yes') }}</option>
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.IsSoldText') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group default_attribute">
                                                <label for="merchant" class="col-sm-2 col-md-3 control-label">{{ trans('labels.IsAirtimeHide') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control" name="is_airtime_hide">
                                                        <option value="0">{{ trans('labels.No') }}</option>
                                                        <option value="1" {{ $result['car']->is_airtime_hide ? 'selected' : '' }}>{{ trans('labels.Yes') }}</option>
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.IsAirtimeHideText') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="merchant" class="col-sm-2 col-md-3 control-label">{{ trans('labels.IsPublish') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control" name="is_publish">
                                                        <option value="1">{{ trans('labels.Yes') }}</option>
                                                        <option value="0" {{ $result['car']->is_publish ? '' : 'selected' }}>{{ trans('labels.No') }}</option>
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.IsPublishText') }}</span>
                                                </div>
                                            </div>

                                            <!-- additional fields -->
                                            <div id="json_data">
                                                
                                            </div>

                                            <!-- /.box-body -->
                                            <!-- /.box-footer -->
                                        </div>
                                </div>
                            </div>

                        </div>


                        <!-- /.box-body -->
                    </div>

                    <div class="box default_attribute">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box-body">
                                        <h4>{{ trans('labels.VehicleLocation') }} </h4>
                                        <hr>
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.State') }}
                                            </label>
                                            <div class="col-sm-10 col-md-4">
                                                <select name="state_id" class='form-control' id="choose_state_id">
                                                    <option value="">{{ trans('labels.SelectState') }}</option>
                                                    @foreach( $result['state'] as $state_data)
                                                        <option
                                                                @if( $state_data->state_id == $result['car']->state_id)
                                                                selected
                                                                @endif
                                                                value="{{ $state_data->state_id }}"> {{ $state_data->state_name }} </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ChooseState') }}</span>
                                                <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.City') }}
                                            </label>
                                            <div class="col-sm-10 col-md-4">
                                                <select name="city_id" class='form-control cityContent'>
                                                    <option value="">{{ trans('labels.SelectCity') }}</option>
                                                    @foreach( $result['city'] as $city_data)
                                                        <option
                                                                @if( $city_data->city_id == $result['car']->city_id)
                                                                selected
                                                                @endif
                                                                value="{{ $city_data->city_id }}"> {{ $city_data->city_name }} </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ChooseCity') }}</span>
                                                <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box-body">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                            <a href="{{url('admin/car/display')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>


<script src="{!! asset('admin/plugins/jQuery/jQuery-2.2.0.min.js') !!}"></script>

<script type="text/javascript">
$(document).ready(function() {
        $('select[name="model_id"]').on('change', function() {
            var MakeID = $(this).val();
            if(MakeID) {
                $.ajax({
                    url: 'ajax/'+MakeID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {                    
                        $('select[name="variant_id"]').empty();
                        $('select[name="variant_id"]').append('<option value="">{{ trans('labels.SelectVariant') }}</option>');
                        $.each(data, function(key, value) {
                            $('select[name="variant_id"]').append('<option value="'+ value.variant_id +'">'+ value.variant_name +'</option>');
                        });


                    }
                });
            }else{
                $('select[name="model_id"]').empty();
            }
        });
    });
</script>

<script type="text/javascript">
    var textarea = document.getElementById('text-area').value.length;
    if(textarea == 0){
        var count = document.getElementById('lblRemainingCount');
        count.innerHTML = "7000 characters remaining";
    }else if(textarea > 0){
        var charactersLeft = 7000 - textarea;
        var count = document.getElementById('lblRemainingCount');
        count.innerHTML = charactersLeft + " characters remaining";
    }
    function textareaLengthCheck(el) {
        var textArea = el.value.length;
        var charactersLeft = 7000 - textArea;
        var count = document.getElementById('lblRemainingCount');
        count.innerHTML = charactersLeft + " characters remaining";
    }

    $(function() {
        $('#selectAll').click(function() {
            if ($(this).prop('checked')) {
                $('.your_checkbox_class').prop('checked', true);
            } else {
                $('.your_checkbox_class').prop('checked', false);
            }
        });
        //for multiple languages
        
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        // CKEDITOR.replace('editor');

        

        //bootstrap WYSIHTML5 - text editor
        $(".textarea").wysihtml5();

    });

    $('input[type="checkbox"][name="mileage[]"]').on('change', function() {
        $('input[name="mileage[]"]').not(this).prop('checked', false);
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var item_sel = $("#item_type_id").val();
        if(item_sel == '1'){
            $(".default_attribute").css("display", "block");
        }
        else{
            $(".default_attribute").css("display", "none");
        }

        type_id = item_sel;
        attribute_ajax(type_id);

        var spin_acc_img = $("#sp_account").val();
        if(spin_acc_img == 'no_sp_account'){
            $(".no_spin_car").css("display", "block");
        }
        else{
            $(".no_spin_car").css("display", "none");
        }
    });

    function getval(sel)
    {
        if(sel.value == 'no_sp_account'){
            $(".no_spin_car").css("display", "block");
        }
        else{
            $(".no_spin_car").css("display", "none");
        }
    }

    function getItemType(sel)
    {
        if(sel.value == '1'){
            $(".default_attribute").css("display", "block");
        }
        else{
            $(".default_attribute").css("display", "none");
        }

        type_id = sel.value;
        attribute_ajax(type_id);
    }

    function attribute_ajax(type_id){
        // ajax
        var itemId = type_id;
        var car_id = $("#car_id").val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
           type:'POST',
           url:"{{ route('getItemAttribute') }}",
           data:{id:itemId,car_id:car_id},
           success:function(result){
                $('#json_data').empty();
                console.log(result['dynamic_attribute']);
                var attribute_no = result['dynamic_attribute'].length;
                var value_no = result['item_value'].length;
                var balannce_no = attribute_no - value_no;
                console.log(balannce_no);
                if(result['dynamic_attribute'].length > 0){
                    var dynamic_attribute = result['dynamic_attribute'];
                    for(var x=0; x<dynamic_attribute.length; x++){
                        if(dynamic_attribute[x]['html_type'] == 'text'){
                            // console.log('text####'+x,dynamic_attribute[x]['input_prefix']);
                            if(result['item_value'].length > 0){
                                for(var a=0; a<result['item_value'].length; a++){
                                    if( result['item_value'][a]['id'] == dynamic_attribute[x]['item_attribute_id'] ){
                                        // console.log(result['item_value'][a]['user_value']);
                                        var input_text = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='text' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control' value='"+result['item_value'][a]['user_value']+"'></div></div>";
                                        $("#json_data").append(input_text);
                                    }
                                }
                            }
                            else{
                                var input_text = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='text' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control'></div></div>";
                                $("#json_data").append(input_text);
                            }
                            
                            // add more fields that not cover by item_value
                            // if (typeof result['item_value'][x] === 'undefined'){
                            //     var input_text = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='text' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control'></div></div>";
                            //     $("#json_data").append(input_text);
                            //     console.log(input_text);
                            // }

                        }
                        else if(dynamic_attribute[x]['html_type'] == 'time'){
                            // console.log('time####'+x,dynamic_attribute[x]['input_prefix']);
                            if(result['item_value'].length > 0){
                                for(var a=0; a<result['item_value'].length; a++){
                                    if( result['item_value'][a]['id'] == dynamic_attribute[x]['item_attribute_id'] ){
                                        // console.log(result['item_value'][a]['user_value']);
                                        var input_time = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='time' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control' value='"+result['item_value'][a]['user_value']+"'></div></div>";
                                        $("#json_data").append(input_time);
                                    }
                                }
                            }
                            else{
                                var input_time = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='time' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control' value=''></div></div>";
                                $("#json_data").append(input_time);
                            }
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'date'){
                            // console.log('date####'+x,dynamic_attribute[x]['input_prefix']);
                            if(result['item_value'].length > 0){
                                for(var a=0; a<result['item_value'].length; a++){
                                    if( result['item_value'][a]['id'] == dynamic_attribute[x]['item_attribute_id'] ){
                                        // console.log(result['item_value'][a]['user_value']);
                                        var input_date = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='date' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control' value='"+result['item_value'][a]['user_value']+"'></div></div>";
                                        $("#json_data").append(input_date);
                                    }
                                }
                            }
                            else{
                                var input_date = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='date' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control' value=''></div></div>";
                                $("#json_data").append(input_date);
                            }
                            
                            
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'number'){
                            // console.log('number####'+x,dynamic_attribute[x]['input_prefix']);
                            if(result['item_value'].length > 0){
                                for(var a=0; a<result['item_value'].length; a++){
                                    if( result['item_value'][a]['id'] == dynamic_attribute[x]['item_attribute_id'] ){
                                        // console.log(result['item_value'][a]['user_value']);
                                        var input_number = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='number' name='"+dynamic_attribute[x]['input_prefix']+"' step='0.01' class='form-control' value='"+result['item_value'][a]['user_value']+"'></div></div>";
                                        $("#json_data").append(input_number);
                                    }
                                }
                            }
                            else{
                                var input_number = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='number' name='"+dynamic_attribute[x]['input_prefix']+"' step='0.01' class='form-control' value=''></div></div>";
                                $("#json_data").append(input_number);
                            }
                            
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'textarea'){
                            // console.log('textarea####'+x,dynamic_attribute[x]['input_prefix']);
                            if(result['item_value'].length > 0){
                                for(var a=0; a<result['item_value'].length; a++){
                                    if( result['item_value'][a]['id'] == dynamic_attribute[x]['item_attribute_id'] ){
                                        var input_textarea = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><textarea name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control'>"+result['item_value'][a]['user_value']+"</textarea></div></div>";
                                        $("#json_data").append(input_textarea);
                                    }
                                }
                            }
                            else{
                                var input_textarea = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><textarea name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control'></textarea></div></div>";
                                $("#json_data").append(input_textarea);
                            }
                            
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'file'){
                            // console.log('file####'+x,dynamic_attribute[x]['input_prefix']);
                            if(result['item_value'].length > 0){
                                for(var a=0; a<result['item_value'].length; a++){
                                    if( result['item_value'][a]['id'] == dynamic_attribute[x]['item_attribute_id'] ){
                                        // var input_file = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='hidden' name='old_"+dynamic_attribute[x]['input_prefix']+"' class='form-control' value='"+result['item_value'][a]['user_value']+"'><input type='file' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control'><br><img src='{{ url("images/items/") }}/" + result['item_value'][a]['user_value'] +"' style='width:50%;'></div></div>";
                                        var input_file = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><span style='display:flex;'><input type='text' id='ref-"+dynamic_attribute[x]['input_prefix']+"' class='form-control' name='"+dynamic_attribute[x]['input_prefix']+"' readonly='readonly' aria-label='Image' aria-describedby='button-image' value='" + result['item_value'][a]['user_value'] +"'><button class='btn btn-outline-secondary' type='button' id='"+dynamic_attribute[x]['input_prefix']+"' onClick='reply_click(this.id)'>Select</button></span></div></div>";
                                        $("#json_data").append(input_file);
                                    }
                                }
                            }
                            else{
                                // var input_file = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='file' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control'></div></div>";
                                var input_file = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><span style='display:flex;'><input type='text' id='ref-"+dynamic_attribute[x]['input_prefix']+"' class='form-control' name='"+dynamic_attribute[x]['input_prefix']+"' readonly='readonly' aria-label='Image' aria-describedby='button-image' value=''><button class='btn btn-outline-secondary' type='button' id='"+dynamic_attribute[x]['input_prefix']+"' onClick='reply_click(this.id)'>Select</button></span></div></div>";
                                $("#json_data").append(input_file);
                            }
                            
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'select'){
                            // console.log('select####'+x,dynamic_attribute[x]['input_prefix']);
                            var input_select = "<div class='form-group'><label for='merchant' class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><select name='"+dynamic_attribute[x]['input_prefix']+"' id='select"+x+"' class='form-control'><option value=''>Option</option></select></div></div>";
                            $("#json_data").append(input_select);
                            
                            if(result['dynamic_attribute_value'].length > 0){
                                var dynamic_attribute_value = result['dynamic_attribute_value'];
                                for(var y=0; y<dynamic_attribute_value.length; y++){
                                    if( dynamic_attribute_value[y]['input_prefix'] == dynamic_attribute[x]['input_prefix'] ){
                                        $("#select"+x).append('<option value=' + dynamic_attribute_value[y]['value_id'] + '>' + dynamic_attribute_value[y]['option'] + '</option>');
                                    }
                                }
                            }

                            if(result['item_value'].length > 0){
                                for(var a=0; a<result['item_value'].length; a++){
                                    if( result['item_value'][a]['id'] == dynamic_attribute[x]['item_attribute_id'] ){
                                        current_value = result['item_value'][a]['user_value'];
                                        if(current_value){
                                            $('#select'+x+' option[value="'+current_value+'"]').prop('selected', true);
                                        }
                                        else{
                                            $('#select'+x+' option[value=""]').prop('selected', true);
                                        }
                                    }
                                }
                            }
                            
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'checkbox'){
                            // console.log('checkbox####'+x,dynamic_attribute[x]['input_prefix']);
                            var input_checkbox = '<div class="form-group"><label for="merchant" class="col-sm-2 col-md-3 control-label">'+dynamic_attribute[x]['name']+'</label><div class="col-sm-10 col-md-4"><span id="checkbox_add'+x+'"></span></div></div>';
                            $("#json_data").append(input_checkbox);

                            if(result['dynamic_attribute_value'].length > 0){
                                var dynamic_attribute_value = result['dynamic_attribute_value'];
                                for(var y=0; y<dynamic_attribute_value.length; y++){
                                    if( dynamic_attribute_value[y]['input_prefix'] == dynamic_attribute[x]['input_prefix'] ){
                                        $("#checkbox_add"+x).append('<input type="checkbox" name="'+dynamic_attribute[x]['input_prefix']+'[]" value="'+dynamic_attribute_value[y]['value_id']+'"> ' + dynamic_attribute_value[y]['option'] + '<br>');
                                    }
                                }
                            }

                            if(result['item_value'].length > 0){
                                for(var a=0; a<result['item_value'].length; a++){
                                    if( result['item_value'][a]['id'] == dynamic_attribute[x]['item_attribute_id'] ){
                                        current_value = result['item_value'][a]['user_value'];
                                        var val = result['item_value'][a]['user_value'].split(',');
                                        for(var b=0; b<val.length; b++){
                                            $('#checkbox_add'+x+' input[value="'+val[b]+'"]').prop('checked', true);
                                        }
                                    }
                                }
                            }
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'radio'){
                            // console.log('radio####'+x,dynamic_attribute[x]['input_prefix']);
                            var input_radio = '<div class="form-group"><label for="merchant" class="col-sm-2 col-md-3 control-label">'+dynamic_attribute[x]['name']+'</label><div class="col-sm-10 col-md-4"><span id="radio_add'+x+'"></span></div></div>';
                            $("#json_data").append(input_radio);

                            if(result['dynamic_attribute_value'].length > 0){
                                var dynamic_attribute_value = result['dynamic_attribute_value'];
                                for(var y=0; y<dynamic_attribute_value.length; y++){
                                    if( dynamic_attribute_value[y]['input_prefix'] == dynamic_attribute[x]['input_prefix'] ){
                                        $("#radio_add"+x).append('<input type="radio" name="'+dynamic_attribute[x]['input_prefix']+'" value="'+dynamic_attribute_value[y]['value_id']+'"> ' + dynamic_attribute_value[y]['option'] + '<br>');
                                    }
                                }
                            }

                            if(result['item_value'].length > 0){
                                for(var a=0; a<result['item_value'].length; a++){
                                    if( result['item_value'][a]['id'] == dynamic_attribute[x]['item_attribute_id'] ){
                                        current_value = result['item_value'][a]['user_value'];
                                        if(current_value){
                                            // $('#select'+x+' option[value="'+current_value+'"]').prop('selected', true);
                                            $('#radio_add'+x+' input[value="'+current_value+'"]').prop('checked', true);
                                        }
                                    }
                                }
                            }
                        }
                        else{

                        }
                        
                    }
                }
                else{
                    $('#json_data').empty();
                }
           }
        });
    }
</script>
<script type="text/javascript">
    $('.btn-delete').on('click', function(e) {
    e.preventDefault();

    if (confirm("Do you want to permanently remove this image?")) {
        location = $(this).attr('href');
    }
    })
</script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
<!-- multiple standalone button -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // car image
    document.getElementById('button-image-car_image').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'car_image';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // pdf
    document.getElementById('button-image-pdf').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'pdf';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
  });

  // input
  let inputId = '';

  // dynamic doc manager
  function reply_click(clicked_id){
    var selected = clicked_id;
    console.log(selected);
    inputId = 'ref-'+selected;
    window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
  }

  // set file link
  function fmSetLink($url) {
    document.getElementById(inputId).value = $url;
  }
</script>

<style>
    .no_spin_car{
        display:none;
    }
</style>
@endsection
