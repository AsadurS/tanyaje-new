@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.addItem') }} <small>{{ trans('labels.addItem') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{url('admin/car/display')}}"><i class="fa fa-car"></i>{{ trans('labels.listingAllItem') }}</a></li>
                <li class="active">{{ trans('labels.addItem') }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->

            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">

                    {!! Form::open(array('url' =>'admin/car/add', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
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

                                        <!-- form start -->
                                        <div class="box-body">

                                            <h4>{{ trans('labels.itemSpecific') }} </h4>
                                            <hr> 
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.ListingName') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('title',  '', array('class'=>'form-control field-validate', 'id'=>'title'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.ListingNameTest') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="item_type" class="col-sm-2 col-md-3 control-label">{{ trans('labels.itemType') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="item_type_id" class='form-control' id="item_type_id" onchange="getItemType(this);">
                                                        @foreach( $result['item_type'] as $item_type )
                                                         <option value="{{ $item_type->id }}">{{ $item_type->name }}</option>
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
                                                        <option value="no_sp_account">No Account</option>
                                                        <option value="tanyaje">Tanyaje</option>
                                                        <option value="simedarbymalaysia">Sime Darby Malaysia</option>
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
                                                    <!-- <input type="file" name="car_image[]" id="car_image" multiple><br> -->
                                                    <span style="display:flex;">
                                                        <input type="text" id="car_image" class="form-control" name="car_image" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                                        <button class="btn btn-outline-secondary" type="button" id="button-image-car_image">Select</button>
                                                    </span>
                                                    <span class="help-block" style="color:gray;font-weight: normal;font-size: 11px;margin-top: 0;">{{ trans('labels.CarImageText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute" >
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.VIN') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('vim',  '', array('class'=>'form-control', 'id'=>'vim'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.VINText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.YearMake') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('year_make', '', array('class'=>'form-control', 'id'=>'year_make')) !!}
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
                                                            <option value="{{ $make_data->make_id }}"> {{ $make_data->make_name }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.ChooseBrand') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Model') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="model_id" class='form-control modelContent select2'>
                                                        <option value="">{{ trans('labels.SelectModel') }}</option>
                                                        @foreach( $result['model'] as $model_data)
                                                            <option value="{{ $model_data->model_id }}"> {{ $model_data->model_name }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.ChooseModel') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.link_variant') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="variant_id" class='form-control select2'>
                                                        <option value="">{{ trans('labels.SelectVariant') }}</option>
                                                    
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
                                                    <select name="type_id" class='form-control '>
                                                        <option value="">{{ trans('labels.SelectCategory') }}</option>
                                                        @foreach( $result['type'] as $type_data)
                                                            <option value="{{ $type_data->type_id }}"> {{ $type_data->type_name }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.ChooseCategory') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="status" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Condition') }} </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control" name="status">
                                                        <option value="0">{{ trans('labels.NewArrivals') }}</option>
                                                        <option value="1">{{ trans('labels.New') }}</option>
                                                        <option value="2">{{ trans('labels.Used') }}</option>
                                                        <option value="3">{{ trans('labels.Recond') }}</option>
                                                        <option value="4">{{ trans('labels.Sold') }}</option>
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ChooseConditionText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="pdf" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Pdf') }} </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <!-- <input type="file" name="pdf"><br> -->
                                                    <span style="display:flex;">
                                                        <input type="text" id="pdf" class="form-control" name="pdf" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="">
                                                        <button class="btn btn-outline-secondary" type="button" id="button-image-pdf">Select</button>
                                                    </span>
                                                    <span class="help-block" style="color:gray;font-weight: normal;font-size: 11px;margin-top: 0;">{{ trans('labels.PdfText') }}</span>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <label for="link" class="col-sm-2 col-md-3 control-label">{{ trans('labels.CarExtraLink') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('extra_link',  '', array('class'=>'form-control', 'id'=>'extra_link'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.CarExtraLink') }}</span>
                                                    {{--                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>--}}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="link" class="col-sm-2 col-md-3 control-label">{{ trans('labels.CarExtraLinkLabel') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('extra_link_label',  '', array('class'=>'form-control', 'id'=>'extra_link_label'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.CarExtraLinkLabel') }}</span>
                                                    {{--                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>--}}
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Description') }}</label>
                                                <div class="col-sm-10 col-md-8">
                                                    <textarea id="text-area" name="html_editor" class="form-control" rows="5" maxlength="7000" onkeyup="textareaLengthCheck(this)"></textarea>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;display:inline;">
                                                        {{ trans('labels.EditorText') }} 
                                                    </span>
                                                    <span id="lblRemainingCount" style="float:right;display:inline;"></span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Price') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('price', '', array('class'=>'form-control field-validate', 'id'=>'price')) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.CarPriceText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.CarPriceText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.FuelType') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <input type="radio" name="fuel_type" value=""> None&nbsp;
                                                    <input type="radio" name="fuel_type" value="petrol"> Petrol&nbsp;
                                                    <input type="radio" name="fuel_type" value="diesel"> Diesel&nbsp;
                                                    <input type="radio" name="fuel_type" value="hybrid"> Hybrid&nbsp;
                                                    <input type="radio" name="fuel_type" value="electric"> Electric
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.ChooseFuelTypeText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.ChooseFuelTypeText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Features') }}</label>
                                                <div class="col-sm-10 col-md-8" style="padding-right:0px;padding-left:0px;">
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="3rd Rear Seats"> 3rd Rear Seats&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Auxiliary Audio Input"> Auxiliary Audio Input&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Cloth Seats"> Cloth Seats&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Overhead Airbags"> Overhead Airbags&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Power Seats"> Power Seats&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Turbo Charged Engine"> Turbo Charged Engine
                                                    </div>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="ABS Brakes"> ABS Brakes&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Blind Spot Monitor"> Blind Spot Monitor&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="DVD Video System"> DVD Video System&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Parking Sensors"> Parking Sensors&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Rear A/C Seats"> Rear A/C Seats&nbsp;
                                                    </div>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="AM/FM Stereo"> AM/FM Stereo&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Bluetooh"> Bluetooh&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Leather Seats"> Leather Seats&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Power Locks"> Power Locks&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Rear View Camera"> Rear View Camera&nbsp;
                                                    </div>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Keyless Entry System"> Keyless Entry System&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="USB"> USB&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Navigation Systems"> Navigation Systems&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Power Mirrors"> Power Mirrors&nbsp;<br>
                                                        <input class="your_checkbox_class" type="checkbox" name="features[]" value="Push Start"> Push Start&nbsp;
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
                                                    {{Form::number('seats', '',['min'=>1,'max'=>18,'class'=>'form-control'])}}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.CarSeatsText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.CarSeatsText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Transmission') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <input type="text" name="transmission" class="form-control">
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.ChooseTransmissionText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.ChooseTransmissionText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Mileage') }}</label>
                                                <div class="col-sm-10 col-md-8" style="padding-right:0px;padding-left:0px;">
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input type="checkbox" name="mileage[]" value="0"> 0 <br>
                                                        <input type="checkbox" name="mileage[]" value="0 - 5000"> 0 - 5000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="5001 - 10000"> 5001 - 10000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="10001 - 20000"> 10001 - 20000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="20001 - 30000"> 20001 - 30000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="30001 - 40000"> 30001 - 40000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="40001 - 50000"> 40001 - 50000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="50001 - 60000"> 50001 - 60000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="60001 - 70000"> 60001 - 70000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="70001 - 80000"> 70001 - 80000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="80001 - 90000"> 80001 - 90000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="90001 - 100000"> 90001 - 100000&nbsp;<br>
                                                    </div>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input type="checkbox" name="mileage[]" value="100001 - 110000"> 100001 - 110000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="110001 - 120000"> 110001 - 120000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="120001 - 130000"> 120001 - 130000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="130001 - 140000"> 130001 - 140000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="140001 - 150000"> 140001 - 150000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="150001 - 160000"> 150001 - 160000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="160001 - 170000"> 160001 - 170000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="170001 - 180000"> 170001 - 180000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="180001 - 190000"> 180001 - 190000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="190001 - 200000"> 190001 - 200000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="200001 - 210000"> 200001 - 210000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="210001 - 220000"> 210001 - 220000&nbsp;<br>
                                                    </div>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input type="checkbox" name="mileage[]" value="220001 - 230000"> 220001 - 230000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="230001 - 240000"> 230001 - 240000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="240001 - 250000"> 240001 - 250000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="250001 - 260000"> 250001 - 260000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="260001 - 270000"> 260001 - 270000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="270001 - 280000"> 270001 - 280000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="280001 - 290000"> 280001 - 290000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="290001 - 300000"> 290001 - 300000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="300001 - 310000"> 300001 - 310000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="310001 - 320000"> 310001 - 320000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="320001 - 330000"> 320001 - 330000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="330001 - 340000"> 330001 - 340000&nbsp;<br>
                                                    </div>
                                                    <div class="col-sm-10 col-md-3" style="padding-right:0px;">
                                                        <input type="checkbox" name="mileage[]" value="340001 - 350000"> 340001 - 350000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="350001 - 360000"> 350001 - 360000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="360001 - 370000"> 360001 - 370000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="370001 - 380000"> 370001 - 380000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="380001 - 390000"> 380001 - 390000&nbsp;<br>
                                                        <input type="checkbox" name="mileage[]" value="390001 - 400000"> 390001 - 400000&nbsp;<br>
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
                                                    {!! Form::text('color', '', array('class'=>'form-control', 'id'=>'color')) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.ColorText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.ColorText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group default_attribute">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.EngineCapacity') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('engine_capacity', '', array('class'=>'form-control', 'id'=>'engine_capacity')) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                        {{ trans('labels.CarEngineCapacityText') }}
                                                    </span>
                                                    <span class="help-block hidden">{{ trans('labels.CarEngineCapacityText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="merchant" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Merchant') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="merchant_id" class='form-control field-validate select2'>
                                                        <option value="">{{ trans('labels.SelectMerchant') }}</option>
                                                        @if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT )
                                                            @foreach( $result['merchant'] as $merchant_data)
                                                                @if($merchant_data->id == auth()->user()->id)
                                                                <option value="{{ $merchant_data->id }}" selected> {{ $merchant_data->company_name ?? $merchant_data->first_name." ".$merchant_data->last_name }}</option>
                                                                @endif
                                                            @endforeach
                                                        @elseif( Auth()->user()->role_id == \App\Models\Core\User::ROLE_NORMAL_ADMIN || Auth()->user()->role_id == \App\Models\Core\User::ROLE_SUPER_ADMIN )
                                                            @foreach( $result['merchant'] as $merchant_data)
                                                                <option value="{{ $merchant_data->id }}"> {{ $merchant_data->company_name ?? $merchant_data->first_name." ".$merchant_data->last_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.ChooseMerchantText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group default_attribute">
                                                <label for="merchant" class="col-sm-2 col-md-3 control-label">{{ trans('labels.IsSold') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control" name="is_sold">
                                                        <option value="0">{{ trans('labels.No') }}</option>
                                                        <option value="1">{{ trans('labels.Yes') }}</option>
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
                                                        <option value="1">{{ trans('labels.Yes') }}</option>
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
                                                        <option value="0">{{ trans('labels.No') }}</option>
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.IsPublishText') }}</span>
                                                </div>
                                            </div>

                                            <!-- additional fields -->
                                            <div id="json_data">
                                                
                                            </div>
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
                                                        <option value="{{ $state_data->state_id }}"> {{ $state_data->state_name }} </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.ChooseState') }}</span>
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
                                                        <option value="{{ $city_data->city_id }}"> {{ $city_data->city_name }} </option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                      {{ trans('labels.ChooseCity') }}</span>
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
        $('select[name="model_id"]').on('change', function() {
            var MakeID = $(this).val();
            if(MakeID) {
                $.ajax({
                    url: 'add/ajax/'+MakeID,
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
           type:'POST',
           url:"{{ route('getItemAttribute') }}",
           data:{id:itemId},
           success:function(result){
                $('#json_data').empty();

                if(result['dynamic_attribute'].length > 0){
                    var dynamic_attribute = result['dynamic_attribute'];
                    for(var x=0; x<dynamic_attribute.length; x++){
                        if(dynamic_attribute[x]['html_type'] == 'text'){
                            console.log('text####'+x,dynamic_attribute[x]['input_prefix']);
                            var input_text = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='text' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control'></div></div>";
                            $("#json_data").append(input_text);
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'time'){
                            console.log('time####'+x,dynamic_attribute[x]['input_prefix']);
                            var input_text = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='time' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control'></div></div>";
                            $("#json_data").append(input_text);
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'date'){
                            console.log('date####'+x,dynamic_attribute[x]['input_prefix']);
                            var input_text = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='date' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control'></div></div>";
                            $("#json_data").append(input_text);
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'number'){
                            console.log('number####'+x,dynamic_attribute[x]['input_prefix']);
                            var input_text = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='number' name='"+dynamic_attribute[x]['input_prefix']+"' step='0.01' class='form-control'></div></div>";
                            $("#json_data").append(input_text);
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'textarea'){
                            console.log('textarea####'+x,dynamic_attribute[x]['input_prefix']);
                            var input_textarea = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><textarea name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control'></textarea></div></div>";
                            $("#json_data").append(input_textarea);
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'file'){
                            console.log('file####'+x,dynamic_attribute[x]['input_prefix']);
                            // var input_file = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><input type='file' name='"+dynamic_attribute[x]['input_prefix']+"' class='form-control'></div></div>";
                            var input_file = "<div class=form-group><label for=merchant class='col-sm-2 col-md-3 control-label'>"+dynamic_attribute[x]['name']+"</label><div class='col-sm-10 col-md-4'><span style='display:flex;'><input type='text' id='ref-"+dynamic_attribute[x]['input_prefix']+"' class='form-control' name='"+dynamic_attribute[x]['input_prefix']+"' readonly='readonly' aria-label='Image' aria-describedby='button-image' value=''><button class='btn btn-outline-secondary' type='button' id='"+dynamic_attribute[x]['input_prefix']+"' onClick='reply_click(this.id)'>Select</button></span></div></div>";
                            $("#json_data").append(input_file);

                            // <span style='display:flex;'><input type='text' id='ref"+dynamic_attribute[x]['input_prefix']+"' class='form-control' name='"+dynamic_attribute[x]['input_prefix']+"' readonly='readonly' aria-label='Image' aria-describedby='button-image' value=''><button class='btn btn-outline-secondary' type='button' id='"+dynamic_attribute[x]['input_prefix']+"' onClick='reply_click(this.id)'>Select</button></span>
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'select'){
                            console.log('select####'+x,dynamic_attribute[x]['input_prefix']);
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
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'checkbox'){
                            console.log('checkbox####'+x,dynamic_attribute[x]['input_prefix']);
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
                        }
                        else if(dynamic_attribute[x]['html_type'] == 'radio'){
                            console.log('radio####'+x,dynamic_attribute[x]['input_prefix']);
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
