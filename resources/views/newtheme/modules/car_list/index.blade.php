@extends('newtheme.layouts.main')
@section('meta_for_share')
	<meta property="og:title" content="{{ trans('labels.app_name') }} | Search Car">
    <meta property="og:url" content="https://tanyaje.com.my/">
	<meta property="og:image" content="{{asset('new/images/logo4.png')}}">
	<meta property="og:image:width" content="400" />
	<meta property="og:image:height" content="400" />
	<meta property="og:image:type" content="image/png">
	<meta property="og:description" content="Tanya-Je translated as 'Just Ask' is the first most advanced online digital automotive classified in South East Asia.">
	<style type="text/css">
    	.hide{
    		display: none;
		}
		.fiter-open{overflow-y:hidden;}
        .fliter_part.fixed.open {
    height: 100vh;
    overflow: auto !important;
}
    	.removeToList i{
    		color: red !important;
        }
        .filter-button{display: none}



@media (max-width:768px){
    .leftSideBar{position: relative; z-index: 9;}
    .fliter_part.fixed { position: fixed; top:0px; background: #fff; left: 0; width: 95%; margin: 0 auto; right: 0; z-index: 9; }
    .fliter_part form{display: none}
    .fliter_part .autoBtn{display: none !important}
    .leftSideBar .fliterText {    padding: 15px 15px 0;}
    .leftSideBar .fliterText.active h3{background: url(../images/new/minus.svg); background-position: right top; background-repeat: no-repeat;  background-size: 20px;}
    .leftSideBar .fliterText h3{background: url(../images/new/plus.svg); background-position: right top; background-repeat: no-repeat; background-size: 20px;}

}

    </style>
@endsection
@section('content')

<!-- START breadcrumb_sec -->
<div class="breadcrumb_sec">
    <div class="container">
        <ul>
			<?php if(isset($filter_condition) && $filter_condition != ""){ ?>
				<li><a href="#">
				<?php
					if($filter_condition == 1){
						echo "New";
					}else if($filter_condition == 2){
						echo "Used";
					}else if($filter_condition == 3){
						echo "Recond";
					}
				?></a></li>
			<?php } ?>

			<?php if(isset($state_name) && $state_name != ""){ ?>
				<li><a href="#">{{ ucwords(strtolower($state_name)) }}</a></li>
			<?php } ?>

			<?php if(isset($category_name) && $category_name != ""){ ?>
				<li><a href="#">{{ ucwords(strtolower($category_name)) }}</a></li>
			<?php } ?>

			<?php if(isset($brand_name) && $brand_name != ""){ ?>
				<li><a href="#">{{ ucwords(strtolower($brand_name)) }}</a></li>
			<?php } ?>

			<?php if(isset($model_name) && $model_name != ""){ ?>
				<li><a href="#">{{ ucwords(strtolower($model_name)) }}</a></li>
			<?php } ?>
        </ul>
    </div>
</div>
<!-- END breadcrumb_sec -->

<!-- START content_part-->
<section id="content_part">

    <!-- START searchFittetArea -->
    <section class="searchFittetArea searchFittetAreaMinh">
        <div class="container_lg">

            <!-- START leftSideBar -->
            <aside class="leftSideBar">
                <a class="filter-button" href="#">
                    Filterss
                </a>
                <div class="fliter_part">
                    <div class="fliterText">
                        <h3>Filters <?php if(isset($filter_data)){
						echo "(".count($filter_data).")";

						}?></h3>
						<?php if(isset($filter_data)){
							for($i=0;$i<count($filter_data);$i++){
						?>
						<?php if(isset($filter_data[$i]['features'])){
							for($j=0;$j<count($filter_data[$i]['features']);$j++){

						?>
		           <a href="#" id="<?php echo $filter_data[$i]['features'][$j]; ?>"
							 class="autoBtn">
								<?php echo $filter_data[$i]['features'][$j]; ?>
								 <i class="fas fa-times" onclick="myfunction('<?php echo $filter_data[$i]['features'][$j]; ?>' ,'<?php echo $filter_data[$i]['n']; ?>')" >

								 </i></a>
						<?php
							}
						?>
						<?php
						}else if(isset($filter_data[$i]['fuel_type'])){
							for($j=0;$j<count($filter_data[$i]['fuel_type']);$j++){

						?>
							<a href="#" class="autoBtn"
					 id="<?php echo $filter_data[$i]['fuel_type'][$j]; ?>"><?php echo $filter_data[$i]['fuel_type'][$j]; ?> <i class="fas fa-times" onclick="myfunction('<?php echo $filter_data[$i]['fuel_type'][$j]; ?>','<?php echo $filter_data[$i]['n']; ?>')"></i></a>
						<?php
							}
						}else if(isset($filter_data[$i]['transmission'])){
							for($j=0;$j<count($filter_data[$i]['transmission']);$j++){

						?>
				<a href="#" class="autoBtn"  id="<?php echo $filter_data[$i]['transmission'][$j]; ?>"><?php echo $filter_data[$i]['transmission'][$j]; ?>


			<i class="fas fa-times"  onclick="myfunction('<?php echo $filter_data[$i]['transmission'][$j]; ?>','<?php echo $filter_data[$i]['n']; ?>')"
						></i></a>
						<?php
							}
						}else{
						?>
			<a href="#" class="autoBtn"  id="<?php echo $filter_data[$i]['name']; ?>"><?php echo $filter_data[$i]['name']; ?>
			<i  class="fas fa-times close" onclick="myfunction('<?php echo $filter_data[$i]['name']; ?>','<?php echo $filter_data[$i]['n']; ?> ')"></i></a>
			<?php
			} } } ?>
                    </div>
					 <form action="{{route('carfilters')}}" method="get">
						<div class="filter_over">
						<div class="accordionBox">
							<div class="accordion-item">
								<h4>Condition <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content">
									<div class="form-group">
									<input <?php if(isset($filter_condition) && $filter_condition == 1){ echo "checked"; } ?> name="condition" value="1" class="checkFilterCondition <?php if(isset($filter_condition) && $filter_condition == 1){ echo "con_".$filter_condition; } ?> " type="checkbox" id="text1"
									>

									<label for="text1">New</label>
									</div>
									<div class="form-group">
										<input <?php if(isset($filter_condition) && $filter_condition == 2){ echo "checked"; } ?> name="condition" value="2" class="checkFilterCondition <?php if(isset($filter_condition) && $filter_condition == 2){ echo "con_".$filter_condition; } ?> " type="checkbox"
										  id="text2" >
										<label for="text2">Used</label>
									</div>
									<div class="form-group">
									<input <?php if(isset($filter_condition) && $filter_condition == 3){ echo "checked"; } ?> name="condition" value="3" class="checkFilterCondition <?php if(isset($filter_condition) && $filter_condition == 3){ echo "con_".$filter_condition; } ?> " type="checkbox" id="text3">

										<label for="text3">Recond</label>
									</div>
								</div>
							</div>
						</div>
						<div class="accordionBox">
							<div class="accordion-item">
								<h4>State <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content custom_scroll_bar">
									@foreach($state as $state_data)
										<div class="form-group">
								<input class="checkFilterState " <?php if(isset($filter_state_id) && $filter_state_id == $state_data->state_id){ echo "checked"; } ?>  name="state" type="checkbox"id="state_{{ $state_data->state_id }}" value="{{ $state_data->state_id }}" >
											<label for="state_{{ $state_data->state_id }}">{{ $state_data->state_name}}</label>
										</div>
									@endforeach
								</div>
							</div>
						</div>
						<div class="accordionBox">
							<div class="accordion-item">
								<h4>Brand <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content custom_scroll_bar filter_car_brand">

									@foreach( $make as $make_data)
										<div class="form-group">
											<input class="checkFilterBrand" value="{{ $make_data->make_id }}" <?php if(isset($filter_brand) && $filter_brand == $make_data->make_id){ echo "checked"; } ?> name="brand" type="checkbox" id="brand_{{ $make_data->make_id }}">
											<label for="brand_{{ $make_data->make_id }}">{{ $make_data->make_name }}</label>
										</div>
									@endforeach
								</div>
							</div>
						</div>
						<div class="accordionBox">
							<div class="accordion-item">
								<h4>Model <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content custom_scroll_bar filter_car_model">
									@foreach( $model as $model_data)
										<div class="form-group">
											<input class="checkFilterModel" <?php if(isset($filter_model) && $filter_model == $model_data->model_id){ echo "checked"; } ?> name="model" type="checkbox" id="model_{{ $model_data->model_id }}" value="{{ $model_data->model_id }}">
											<label for="model_{{ $model_data->model_id }}">{{ $model_data->model_name }}</label>
										</div>
									@endforeach
								</div>
							</div>
						</div>
						<div class="accordionBox">
							<div class="accordion-item">
								<h4>Categories <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content custom_scroll_bar">
									@foreach( $type as $type_data)
										<div class="form-group">
											<input class="checkFilterType" <?php if(isset($filter_categories) && $filter_categories == $type_data->type_id){ echo "checked"; } ?> name="categories" value="{{ $type_data->type_id }}" type="checkbox" id="type_{{ $type_data->type_id }}">
											<label for="type_{{ $type_data->type_id }}">{{ $type_data->type_name }}</label>
										</div>
									@endforeach
								</div>
							</div>
						</div>
						<div class="accordionBox">
							<div class="accordion-item">
								<h4>Year make <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content plr20">
									<div class="myrBox disflexArea algnflexArea">
										<div class="subBox">
											<input value="{{ $start_make_year }}" name="start_make_year" id="start_make_year" type="text" placeholder="{{ trim($min_year_make) }}">
										</div>
										<strong>to</strong>
										<div class="subBox">
											<input value="{{ $last_make_year }}" name="last_make_year" id="last_make_year" type="text" placeholder="{{ trim($max_year_make) }}">
										</div>
									</div>
									<div id="rangeSlider" class="range-slider">
										<div class="range-group">
											<input class="range-input range_input_year_mark" value="{{ trim($start_make_year) }}" min="{{ trim($min_year_make) }}" max="{{ trim($max_year_make) }}" step="1" type="range" data-type="1">
											<input class="range-input range_input_year_mark" value="{{ trim($last_make_year) }}" min="{{ trim($min_year_make) }}" max="{{ trim($max_year_make) }}" step="1" type="range" data-type="2">
										</div>
									</div>
								</div>
							</div>
						</div>
							<div class="accordionBox">
								<div class="accordion-item">
									<h4>Mileage <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
									<div class="content custom_scroll_bar">
										@foreach($mileage_ranges as $mileage_range)
											<div class="form-group">
												<input class="checkFilterMileage" <?php if(isset($filter_mileage) && $filter_mileage ==  "" . $mileage_range[0] . "-" . $mileage_range[1]  ){ echo "checked"; } ?> name="mileage_range" value="{{ $mileage_range[0] . "-" . $mileage_range[1] }}" type="checkbox" id="mileage_{{ $mileage_range[0] }}-{{ $mileage_range[1] }}">
												<label for="mileage_{{ $mileage_range[0] }}-{{ $mileage_range[1] }}">{{ $mileage_range[0] }} - {{ $mileage_range[1] }}</label>
											</div>
										@endforeach
									</div>
								</div>
							</div>
						<div class="accordionBox">
							<div class="accordion-item">
								<h4>Price <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content plr20">
									<div class="myrBox disflexArea algnflexArea">
										<div class="subBox">
											<input value="{{ $start_price != ""? round($start_price): "" }}" name="start_price" id="start_price" type="text" placeholder="{{ $min_price }}">
										</div>
										<strong>to</strong>
										<div class="subBox">
											<input value="{{ $last_price != ""? round($last_price): "" }}" name="last_price" id="last_price" type="text" placeholder="{{ $max_price }}">
										</div>
									</div>
									<div id="rangeSlider" class="range-slider">
										<div class="range-group">
											<input class="range-input range_input_price" value="{{ round(trim($start_price)) }}" min="{{ $min_price }}" max="{{ $max_price }}" step="1" type="range" data-type="1">
											<input class="range-input range_input_price" value="{{ round(trim($last_price)) }}" min="{{ $min_price }}" max="{{ $max_price }}" step="1" type="range" data-type="2">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="accordionBox">
							<div class="accordion-item">
								<h4>Fuel Type <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content">
									<div class="form-group fuelText">
										<input class="checkFilterFuleType petrol" <?php if(isset($filter_fuel_type)){ if(in_array("petrol", $filter_fuel_type)){ echo "checked"; } } ?> name="fuel_type[]" type="checkbox" id="Petrol" value="petrol">
										<label for="Petrol">Petrol</label>
									</div>

									<div class="form-group fuelText">
										<input class="checkFilterFuleType diesel" <?php if(isset($filter_fuel_type)){ if(in_array("diesel", $filter_fuel_type)){ echo "checked"; } } ?> name="fuel_type[]" value="diesel" type="checkbox" id="Diesel">
										<label for="Diesel">Diesel</label>
									</div>
									<div class="form-group fuelText">
										<input class="checkFilterFuleType hybrid" <?php if(isset($filter_fuel_type)){ if(in_array("hybrid", $filter_fuel_type)){ echo "checked"; } } ?> name="fuel_type[]" value="hybrid" type="checkbox" id="Hybrid">
										<label for="Hybrid">Hybrid</label>
									</div>
									<div class="form-group fuelText">
										<input class="checkFilterFuleType electric" <?php if(isset($filter_fuel_type)){ if(in_array("electric", $filter_fuel_type)){ echo "checked"; } } ?> name="fuel_type[]" value="electric" type="checkbox" id="Electric">
										<label for="Electric">Electric</label>
									</div>
								</div>
							</div>
						</div>

						<div class="accordionBox">
							<div class="accordion-item">
								<h4>Transmission <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content">
									<div class="form-group">
										<input class="checkFilterTransmission <?php if(isset($filter_transmission)){ if(in_array("automatic", $filter_transmission)){ echo "automatic"; } } ?>"
										 <?php if(isset($filter_transmission)){ if(in_array("automatic", $filter_transmission)){ echo "checked"; } } ?> name="transmission[]" value="automatic" type="checkbox" id="Automatic">
										<label for="Automatic">Automatic</label>
									</div>

									<div class="form-group">
										<input class="checkFilterTransmission <?php if(isset($filter_transmission)){ if(in_array("manual", $filter_transmission)){ echo "manual"; } } ?>

										" <?php if(isset($filter_transmission)){ if(in_array("manual", $filter_transmission)){ echo "checked"; } } ?> value="manual" name="transmission[]" type="checkbox" id="Manual">
										<label for="Manual">Manual</label>
									</div>
								</div>
							</div>
						</div>

						<div class="accordionBox">
							<div class="accordion-item">
								<h4>Engine Capacity <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content plr20">
									<div class="myrBox disflexArea algnflexArea">
										<div class="subBox">
											<input value="{{ $start_engine_capacity }}" id="start_engine_capacity" name="start_engine_capacity" type="text" placeholder="{{ $min_engine_capacity }}">
										</div>
										<strong>to</strong>
										<div class="subBox">
											<input value="{{ $last_engine_capacity }}" id="last_engine_capacity" name="last_engine_capacity" type="text" placeholder="{{ $max_engine_capacity }}">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="accordionBox">
							<div class="accordion-item">
								<h4>Seats <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content plr20">
									<div class="myrBox disflexArea algnflexArea">
										<div class="subBox">
											<input value="{{ $start_seats }}" id="start_seats" name="start_seats" type="text" placeholder="{{ $min_seats }}">
										</div>
										<strong>to</strong>
										<div class="subBox">
											<input value="{{ $last_seats }}" id="last_seats" name="last_seats" type="text" placeholder="{{ $max_seats }}">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="accordionBox">
							<div class="accordion-item">
								<h4>Color <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content custom_scroll_bar">
									@foreach($color as $color)
										<div class="form-group">
											<input class="checkFilterColor" <?php if(isset($filter_color) && $filter_color == $color->color){ echo "checked"; } ?>  name="color" type="checkbox"id="color_{{ $color->color }}" value="{{ $color->color }}" >
											<label for="color_{{ $color->color }}">{{ strtoupper($color->color) }}</label>
										</div>
									@endforeach
								</div>
							</div>
						</div>
						<div class="accordionBox fliterTextt ">
							<div class="accordion-item">
								<h4>Features <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content custom_scroll_bar">
									<div class="disflexArea">
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("3rd Rear Seats", $filter_features)){ echo "checked"; } } ?> name="features[]" type="checkbox" value="3rd Rear Seats"  class="3rd_Rear_Seats" id="3rd Rear Seats">
											<label for="3rd Rear Seats">3rd Rear Seats</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Auxiliary Audio Input", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Auxiliary Audio Input" type="checkbox" class="Auxiliary_Audio_Input" id="Auxiliary Audio Input">
											<label for="Auxiliary Audio Input">Auxiliary Audio Input</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Cloth Seats", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Cloth Seats" type="checkbox" class="Cloth_Seats" id="Cloth Seats">
											<label for="Cloth Seats">Cloth Seats</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Overhead Airbags", $filter_features)){ echo "checked"; } } ?> name="features[]" type="checkbox" value="Overhead Airbags"  class="Overhead_Airbags" id="Overhead Airbags">
											<label for="Overhead Airbags">Overhead Airbags</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Power Seats", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Power Seats" type="checkbox"  class="Power_Seats" id="Power Seats">
											<label for="Power Seats">Power Seats</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Turbo Charged Engine", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Turbo Charged Engine" type="checkbox" class="Turbo_Charged_Engine" id="Turbo Charged Engine">
											<label for="Turbo Charged Engine">Turbo Charged Engine</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("ABS Brakes", $filter_features)){ echo "checked"; } } ?> name="features[]" value="ABS Brakes" type="checkbox" class="ABS_Brakes" id="ABS Brakes">
											<label for="ABS Brakes">ABS Brakes</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Blind Spot Monitor", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Blind Spot Monitor" type="checkbox" class="Blind_Spot_Monitor" id="Blind Spot Monitor">
											<label for="Blind Spot Monitor">Blind Spot Monitor</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("DVD Video System", $filter_features)){ echo "checked"; } } ?> name="features[]" value="DVD Video System" type="checkbox" class="DVD_Video_System" id="DVD Video System">
											<label for="DVD Video System">DVD Video System</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Parking Sensors", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Parking Sensors" type="checkbox" class="Parking_Sensors" id="Parking Sensors">
											<label for="Parking Sensors">Parking Sensors</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Rear A/C Seats", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Rear A/C Seats" type="checkbox" class="Rear_A_C_Seats" id="Rear A/C Seats">
											<label for="Rear A/C Seats">Rear A/C Seats</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("AM/FM Stereo", $filter_features)){ echo "checked"; } } ?> name="features[]" value="AM/FM Stereo" type="checkbox"  class="AM_FM_Stereo" id="AM/FM Stereo">
											<label for="AM/FM Stereo">AM/FM Stereo</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Bluetooh", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Bluetooh" type="checkbox" class="Bluetooh" id="Bluetooh">
											<label for="Bluetooh">Bluetooh</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Leather Seats", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Leather Seats" type="checkbox" class="Leather_Seats" id="Leather Seats" >
											<label for="Leather Seats">Leather Seats</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Power Locks", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Power Locks" type="checkbox" class="Power_Locks" id="Power Locks">
											<label for="Power Locks">Power Locks</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Rear View Camera", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Rear View Camera" type="checkbox" class="Rear_View_Camera" id="Rear View Camera">
											<label for="Rear View Camera">Rear View Camera</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Keyless Entry System", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Keyless Entry System" type="checkbox" class="Keyless_Entry_System" id="Keyless Entry System">
											<label for="Keyless Entry System">Keyless Entry System</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("USB", $filter_features)){ echo "checked"; } } ?> name="features[]" value="USB" type="checkbox" class="USB" id="USB">
											<label for="USB">USB</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Navigation Systems", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Navigation Systems" class="Navigation_Systems" type="checkbox" id="Navigation Systems">
											<label for="Navigation Systems">Navigation Systems</label>
										</div>
										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Power Mirrors", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Power Mirrors" type="checkbox"  class="Power_Mirrors" id="Power Mirrors">
											<label for="Power Mirrors">Power Mirrors</label>
										</div>

										<div class="form-group">
											<input <?php if(isset($filter_features)){ if(in_array("Push Start", $filter_features)){ echo "checked"; } } ?> name="features[]" value="Push Start" type="checkbox" class="Push_Start" id="Push Start">
											<label for="Push Start">Push Start</label>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="accordionBox">
							<div class="accordion-item">
								<h4>Listed By <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
								<div class="content custom_scroll_bar">
									@foreach($merchant as $m)
										<div class="form-group">
											<input class="checkFilterMerchant  <?php if(isset($selected_merchant) &&
											 $selected_merchant == $m->id){ echo $selected_merchant ; } ?> "
											<?php if(isset($selected_merchant) &&
											 $selected_merchant == $m->id){ echo "checked"; } ?>  name="merchant" type="checkbox"id="merchant_{{ $m->company_name }}" value="{{ $m->id }}" >
											<label for="merchant_{{ $m->company_name }}">{{ substrwords(strtoupper($m->company_name), 22) }}</label>
										</div>
									@endforeach
								</div>
							</div>
						</div>
						<button type="submit" class="btn filter_search_btn">SEARCH</button>
					</div>
					</form>
                </div>

                <!-- <div class="fliter_part fliterTextt">

                </div> -->
            </aside>
            <!-- END leftSideBar -->
            <!-- START usedHondaBox -->
            <article class="usedHondaBox popularsArea">
                <div class="textbox select-box">
                    <h3>{{ $search_message }}</h3>
					@php
						$url = Request::url(); // url without query
						$query = Request::query(); // query
						//Replace parameter:
						$newFullUrl = $url.'?'. http_build_query(array_merge($query, ['sort' => '']));

					@endphp
					<select id="sort_cars">
						<option data-url="{!! request()->fullUrlWithQuery(['sort' => '']) !!}">Select Order Option</a></option>
						<option data-url="{!! request()->fullUrlWithQuery(['sort' => 'updated_at', 'direction' => 'desc']) !!}" {!! Request::get('sort') == "updated_at" && Request::get('direction') == "desc" ? "selected" : "" !!}>Latest Update</a></option>
						<option data-url="{!! request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'asc']) !!}" {!! Request::get('sort') == "price" && Request::get('direction') == "asc" ? "selected" : "" !!}>Price Low to High</a></option>
						<option data-url="{!! request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'desc']) !!}" {!! Request::get('sort') == "price" && Request::get('direction') == "desc" ? "selected" : "" !!}>Price High to Low</a></option>
						<option data-url="{!! request()->fullUrlWithQuery(['sort' => 'year_make', 'direction' => 'desc']) !!}" {!! Request::get('sort') == "year_make" && Request::get('direction') == "desc" ? "selected" : "" !!}>Year New to Old</a></option>
						<option data-url="{!! request()->fullUrlWithQuery(['sort' => 'year_make', 'direction' => 'asc']) !!}" {!! Request::get('sort') == "year_make" && Request::get('direction') == "asc" ? "selected" : "" !!}>Year Old to New</a></option>
						<option data-url="{!! request()->fullUrlWithQuery(['sort' => 'mileage', 'direction' => 'asc']) !!}" {!! Request::get('sort') == "mileage" && Request::get('direction') == "asc" ? "selected" : "" !!}>Mileage Low to High</a></option>
						<option data-url="{!! request()->fullUrlWithQuery(['sort' => 'mileage', 'direction' => 'desc']) !!}" {!! Request::get('sort') == "mileage" && Request::get('direction') == "desc" ? "selected" : "" !!}>Mileage High to Low</a></option>
					</select>
                </div>


                <div id="car-item-list" class="itemBox disflexArea d_item_box">
                    @if(!empty($car))
                        @foreach($car as $i => $rs)

                            <div class="item">
                                    @if($airtime)
                                        <span class="sub_spon">Airtime</span>
                                        @php
                                            $car_url = App\Models\Core\Cars::find($rs->car_id)->getCarUrl();
                                        @endphp
                                    @endif
                                <div class="image">
{{--                                    <img class="Bgimage" src="{{ 'https://manager.spincar.com/web-preview/walkaround-thumb/tanyaje/' . strtolower($rs->vim) . '/md'  }}" alt="">--}}
                                    <a href="{{ route('car_details', $airtime ? $car_url : $rs->getCarUrl()) }}">
                                        <img src="{{ 'https://manager.spincar.com/web-preview/walkaround-thumb/' . $rs->sp_account . '/' . strtolower($rs->vim) . '/md'  }}" alt="" >
                                    </a>
                                </div>
                                <div class="sub">
                                	<span class="addToList" id="addButton{{$rs->car_id}}" data-id="{{$rs->car_id}}">
                                    	<i class="far fa-heart"></i>
                                    </span>
                                    <span class="removeToList hide" id="removeButton{{$rs->car_id}}" data-id="{{$rs->car_id}}">
                                    	<i class="fa fa-heart red"></i>
									</span>
									<a href="{{ route('car_details',$airtime ? $car_url : $rs->getCarUrl()) }}">
										<div class="title-container">
										<div class="year-car">{{$rs->year_make}}</div> <div class="title-car"><h3>{{$rs->title}}</h3></div>
										</div>
									</a>
									<p>Listed by: <a class="listed-by" href="{{ $rs->merchant_slug? route('site.merchant',$rs->merchant_slug): '#' }}">{{ $rs->merchant_name }}</a></p>
									<h4>RM {{ number_format($rs->price, 2, '.', ',') }}</h4>
									<span class="vin" style="display: none;">{{ $rs->vim }}</span>
                                    <ul>
										@if(isset($rs->mileage) && $rs->mileage !="")
                                        <li><img src="{{asset('images/new/popularIcon01.png')}}" alt="">{{ isset($rs->mileage) && $rs->mileage !="" ? convertMileage($rs->mileage) ." km": "Unknown" }}</li>
                                        @endif

										@if(isset($rs->city_name) && $rs->city_name !="")
										<li><img src="{{asset('images/new/popularIcon02.png')}}" alt="">{{ ucwords($rs->city_name) }}, {{ ucwords($rs->state_name) }}</li>
                                        @endif

										@if(isset($rs->color) && $rs->color !="")
										<li><img src="{{asset('images/new/popularIcon03.png')}}" alt="">{{ isset($rs->color)? convertColor($rs->color) : "Unknown" }}</li>
										@endif

										@if(isset($rs->fuel_type) && $rs->fuel_type !="")
										<li><img src="{{asset('images/new/popularIcon04.png')}}" alt="">{{ isset($rs->fuel_type)? ucwords($rs->fuel_type): "Unknown" }}</li>
										@endif

                                    </ul>
                                    <div class="AddtobOX" style="padding-bottom: 20px;">
                                       <div class="form-group">
                                            @if(Auth::guard('customer')->user())
                                                <input type="checkbox" id="compare{!! $rs->car_id !!}" class="compare_checkbox" value="{!! $rs->car_id !!}" {!! in_array($rs->car_id, $cookies) ? 'checked' : '' !!}>
                                            @else
                                                <input type="checkbox" id="compare{!! $rs->car_id !!}" class="compare_checkbox" value="{!! $rs->car_id !!}" {!! in_array($rs->car_id, $cookies) ? 'checked' : '' !!}>
                                            @endif
                                            <label for="compare{!! $rs->car_id !!}">Add to compare</label>
                                       </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    @endif
                </div>
				<div class="col-xs-12 text-right">
                    @if(!$airtime)
                        {{ $car->appends(\Request::except('page'))->links() }}
                    @else
                        <div class ="load_more">
                            <a href="#car-item-list" data-href="{!! request()->fullUrl() !!}" data-perpage="{!! $airtime_per_page !!}" data-totalpage="{!! $airtime_total_pages !!}" class="btn load_more_btn">Load more</a>
				        </div>
                        {{-- <ul class="pagination">
                            @if($current_page == 1)
                                <li class="disabled"><span>&laquo;</span></li>
                            @else
                                <li><a href="{!! request()->fullUrlWithQuery(['page' => $current_page - 1]) !!}" rel="prev">&laquo;</a></li>
                            @endif
                            @for($i = 1; $i <= $airtime_total_pages;$i++)
                                @if($airtime_total_pages > 10)
                                    @if($current_page < 5)
                                        @if($i == $current_page || ($i > ($current_page - 5) && $i < ($current_page + 5)) || $i > ($airtime_total_pages - 2))
                                            @if($i == $current_page)
                                                <li class="active"><span>{!! $i !!}</span></li>
                                            @else
                                                <li><a href="{!! request()->fullUrlWithQuery(['page' => $i]) !!}">{!! $i !!}</a></li>
                                            @endif
                                        @else
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                            @php
                                                $i = $airtime_total_pages - 2;
                                            @endphp
                                        @endif
                                    @elseif($current_page > ($airtime_total_pages - 5))
                                        @if($i == $current_page || ($i > ($current_page - 5) && $i < ($current_page + 5)) || $i < 3)
                                            @if($i == $current_page)
                                                <li class="active"><span>{!! $i !!}</span></li>
                                            @else
                                                <li><a href="{!! request()->fullUrlWithQuery(['page' => $i]) !!}">{!! $i !!}</a></li>
                                            @endif
                                        @else
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                            @php
                                                $i = $current_page - 5;
                                            @endphp
                                        @endif
                                    @else
                                        @if($i < 3)
                                            <li><a href="{!! request()->fullUrlWithQuery(['page' => $i]) !!}">{!! $i !!}</a></li>
                                        @endif

                                        @if($i > ($airtime_total_pages - 2))
                                            <li><a href="{!! request()->fullUrlWithQuery(['page' => $i]) !!}">{!! $i !!}</a></li>
                                        @endif
                                        @if($i == $current_page || ($i > ($current_page - 5) && $i < ($current_page + 5)))
                                            @if($i == $current_page)
                                                <li class="active"><span>{!! $i !!}</span></li>
                                            @else
                                                <li><a href="{!! request()->fullUrlWithQuery(['page' => $i]) !!}">{!! $i !!}</a></li>
                                            @endif
                                        @else
                                            @if($i == 3)
                                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                            @endif

                                            @if($i == ($current_page + 5))
                                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                            @endif

                                        @endif
                                    @endif
                                @else
                                    @if($i == $current_page)
                                        <li class="active"><span>{!! $i !!}</span></li>
                                    @else
                                        <li><a href="{!! request()->fullUrlWithQuery(['page' => $i]) !!}">{!! $i !!}</a></li>
                                    @endif
                                @endif
                            @endfor
                            @if($current_page == $airtime_total_pages)
                                <li class="disabled"><span>&raquo;</span></li>
                            @else
                                <li><a href="{!! request()->fullUrlWithQuery(['page' => $current_page + 1]) !!}" rel="next">&raquo;</a></li>
                            @endif
                        </ul> --}}
                    @endif
                </div>

            </article>

            <!-- END usedHondaBox -->
        </div>
    </section>
    <!-- END searchFittetArea -->

</section>
<div class="back-top-top">
	<div class="back-top-top-inner">
	<i class="fa fa-arrow-up" aria-hidden="true"></i>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>

<script type="text/javascript">

	function myfunction($id,$n)
	{
	 	document.getElementById($id).style.display = 'none';

	 	//condition
	 	if($n==1)
	 	{
		  	$(".<?php if(isset($filter_condition)){ echo "con_".$filter_condition; } ?>").prop("checked", false);
	 	}

	 	//state
		if($n==2)
		{
			$("#<?php if(isset($filter_state_id)){ echo "state_".$filter_state_id; } ?>").prop("checked", false);
	 	}

		//brand
	 	if($n==3)
	 	{
			$("#<?php if(isset($filter_brand)){ echo "brand_".$filter_brand; } ?>").prop("checked", false);
	 	}

	 	//model
	   	if($n==4)
	   	{
			$("#<?php if(isset($filter_model)){ echo "model_".$filter_model; } ?>").prop("checked", false);
	  	}

	   	//type
	  	if($n==5)
	  	{
			$("#<?php if(isset($filter_categories)){ echo "type_".$filter_categories; } ?>").prop("checked", false);
	  	}

	  	//year make
		if($n==6 || $n==7 )
		{
			if( $id.includes("Year Make Start") )
			{
				$('#start_make_year').val("");
			}
			else if( $id.includes("Year Make End") )
			{
				$('#last_make_year').val("");
			}
		}

		//price
		if($n==10 || $n==11 )
		{
			if( $id.includes("Min Price") )
			{
				$('#start_price').val("");
			}
			else if( $id.includes("Max Price") )
			{
				$('#last_price').val("");
			}
		}

	  	//fuel type
	 	if($n==12)
	 	{
			$("."+$id).prop("checked", false);
			/* $("#petrol").prop("checked", false);
			$("#diesel").prop("checked", false);
			$("#hybrid").prop("checked", false); */
	  	}

	 	//Transmission
	  	if($n==13)
	  	{
			$("."+$id).prop("checked", false);
			// $("#Automatic1").prop("checked", false);
			// $("#Manual1").prop("checked", false);
	  	}

		//Engine Capacity
		if($n==14 || $n==15 )
		{
			if( $id.includes("Min Engine Capacity") )
			{
				$('#start_engine_capacity').val("");
			}
			else if( $id.includes("Max Engine Capacity") )
			{
				$('#last_engine_capacity').val("");
			}
		}

		//Seat
		if($n==16 || $n==17 )
		{
			if( $id.includes("Min Seat") )
			{
				$('#start_seats').val("");
			}
			else if( $id.includes("Max Seat") )
			{
				$('#last_seats').val("");
			}
		}

	  	//color
	  	if($n==18)
	  	{
	  		$("#<?php if(isset($filter_color)){ echo "color_".$filter_color; } ?>").prop("checked", false);

	 	}

	  	//features
	  	if($n==19)
	  	{
	  		if($id.includes("Push Start"))
			{
				$(".Push_Start").prop("checked", false);
			}

			if($id.includes("Power Mirrors"))
			{
				$(".Power_Mirrors").prop("checked", false);
			}

			if($id.includes("Navigation Systems"))
			{
				$(".Navigation_Systems").prop("checked", false);
			}

			if($id.includes("USB")) {
				$(".USB").prop("checked", false);
			}

			if($id.includes("Keyless Entry System")) {
				$(".Keyless_Entry_System").prop("checked", false);
			}

			if($id.includes("Rear View Camera")) {
				$(".Rear_View_Camera").prop("checked", false);
			}

			if($id.includes("Power Locks")) {
				$(".Power_Locks").prop("checked", false);
			}

			if($id.includes("Leather Seats")) {
				$(".Leather_Seats").prop("checked", false);
			}

			if($id.includes("Bluetooh")) {
				$(".Bluetooh").prop("checked", false);
			}

			if($id.includes("AM/FM Stereo")) {
				$(".AM_FM_Stereo").prop("checked", false);
			}

			if($id.includes("Rear A/C Seats")) {
				$(".Rear_A_C_Seats").prop("checked", false);
			}

			if($id.includes("Parking Sensors")) {
				$(".Parking_Sensors").prop("checked", false);
			}

			if($id.includes("DVD Video System")) {
				$(".DVD_Video_System").prop("checked", false);
			}

			if($id.includes("Blind Spot Monitor")) {
				$(".Blind_Spot_Monitor").prop("checked", false);
			}

			if($id.includes("ABS Brakes")) {
				$(".ABS_Brakes").prop("checked", false);
			}

			if($id.includes("Turbo Charged Engine")) {
				$(".Turbo_Charged_Engine").prop("checked", false);
			}

			if($id.includes("Power Seats")) {
				$(".Power_Seats").prop("checked", false);
			}

			if($id.includes("Overhead Airbags")) {
				$(".Overhead_Airbags").prop("checked", false);
			}

			if($id.includes("Cloth Seats")) {
				$(".Cloth_Seats").prop("checked", false);
			}

			if($id.includes("Auxiliary Audio Input")) {
				$(".Auxiliary_Audio_Input").prop("checked", false);
			}

			if($id.includes("3rd Rear Seats"))
			{
				$(".3rd_Rear_Seats").prop("checked", false);
			}

	  	}

	  	//merchant
		if($n==20)
		{
			$(".<?php if(isset($selected_merchant)){ echo $selected_merchant; } ?>").prop("checked", false);
	   	}

	}

	$("#sort_cars").on("change",function(){
		window.location = jQuery(this).find('option:selected').data('url');
	});
	//make year
	$( "#start_make_year" ).change(function() {
		verifiedMakeYear();
	});

	$( "#last_make_year" ).change(function() {
		verifiedMakeYear();
	});

	var minMakeYear = "{{ $min_year_make }}";
	var maxMakeYear = "{{ $max_year_make }}";
	function verifiedMakeYear()
	{
		var startMakeYear = $('#start_make_year').val();
		var lastMakeYear = $('#last_make_year').val();
		if( startMakeYear != "" && lastMakeYear!="" && startMakeYear > lastMakeYear )
		{
			alert( "Start Make Year is not allow more than End Make Year" );
			$('#start_make_year').val(minMakeYear);
			$('#last_make_year').val(maxMakeYear);
		}
	}

	//price
	$( "#start_price" ).change(function() {
		verifiedPrice();
	});

	$( "#last_price" ).change(function() {
		verifiedPrice();
	});

	var minPrice = "{{ $min_price }}";
	var maxPrice = "{{ $max_price }}";
	function verifiedPrice()
	{
		var startPrice = $('#start_price').val();
		var lastPrice = $('#last_price').val();
		if( startPrice != "" && lastPrice!="" && startPrice > lastPrice )
		{
			alert( "Start Price is not allow more than End Price" );
			$('#start_price').val(minPrice);
			$('#last_price').val(maxPrice);
		}
	}

	//seat
	$( "#start_seats" ).change(function() {
		verifiedSeats();
	});

	$( "#last_seats" ).change(function() {
		verifiedSeats();
	});

	var minSeat = "{{ $min_seats }}";
	var maxSeat = "{{ $max_seats }}";
	function verifiedSeats()
	{
		var startSeat = $('#start_seats').val();
		var lastSeat = $('#last_seats').val();
		if( startSeat != "" && lastSeat!="" && startSeat > lastSeat )
		{
			alert( "Start Seat is not allow more than End Seat" );
			$('#start_seats').val(minSeat);
			$('#last_seats').val(maxSeat);
		}
	}

	//engine_capacity
	$( "#start_engine_capacity" ).change(function() {
		verifiedEngineCapacity();
	});

	$( "#last_engine_capacity" ).change(function() {
		verifiedEngineCapacity();
	});

	var minEngineCapacity = "{{ $min_engine_capacity }}";
	var maxEngineCapacity = "{{ $max_engine_capacity }}";
	function verifiedEngineCapacity()
	{
		var startEngineCapacity = $('#start_engine_capacity').val();
		var lastEngineCapacity = $('#last_engine_capacity').val();
		if( startEngineCapacity != "" && lastEngineCapacity!="" && startEngineCapacity > lastEngineCapacity )
		{
			alert( "Start Engine Capacity is not allow more than End Engine Capacity" );
			$('#start_engine_capacity').val(minEngineCapacity);
			$('#last_engine_capacity').val(maxEngineCapacity);
		}
	}

	jQuery(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            jQuery(".load_more_btn").on("click",function(e){
                var _this = $(this);
                var $link = $(e.target);
                console.log(+new Date() - $link.attr('id'));
                if(!$link.attr('id') || +new Date() - $link.attr('id') > 3000) {
                    jQuery(".loader_section").show();
                    $link.attr('id', +new Date());
                    window.location.hash='';
                    $.get(jQuery(this).data('href'),function(response){
                        if($(_this).data("perpage") >= 25 && $(_this).data("totalpage") > 1)
                        {
                            $('.itemBox').prepend($('.itemBox',response).html());
                        }
                        else
                        {
                            $('#car-item-list').html($('#car-item-list',response).html());
                        }
                        window.location.hash='';
                        jQuery(".loader_section").hide();
                    });
                }
            })

            jQuery(".filter_car_brand .checkFilterBrand").on("click",function(){
                jQuery.post('/getModel',{make_id: $(this).val()},function(response){
                    response = JSON.parse(response);
                    html = '';
                    jQuery.each(response.data,function(i,val)
                    {	html += '<div class="form-group"> <input class="checkFilterModel" name="model" type="checkbox" id="model_'+val.	model_id+'" value="'+val.model_id+'"> <label for="model_'+val.model_id+'">'+val.model_name+'</label> </div>';
                    });
                    jQuery(".filter_car_model").html(html);
                });
            });
            var list = new cookieList("car_compare_list");
            var is_user = "{!! Auth::guard('customer')->check() !!}";
            jQuery(".compare_checkbox").on('click',function(){
                if(jQuery(this).prop('checked') == true) {
                    if(list.length() > 3)
                    {
                        alert("You can't campare more then 4 cars");
                        return false;
                    }
                    else
                    {
                        if(is_user)
                        {
                            jQuery.post("{!! route('carcompare.add') !!}",{car_id: $(this).val()},function(response){
                                alert(response);
                            });
                        }
                        else
                        {
                            list.add($(this).val());
                        }

                    }
                }
                else{
                        if(is_user)
                        {
                            jQuery.post("{!! route('carcompare.remove') !!}",{car_id: $(this).val()},function(response){
                                alert(response);
                            });
                        }
                        else
                        {
                            list.remove($(this).val());
                        }
                }
            });
        });



        var cookieList = function(cookieName) {
            var cookie = Cookies.get(cookieName);
            var items = cookie ? cookie.split(/,/) : new Array();
            return {
                "add": function(val) {
                    //Add to the items.
                    items.push(val);
                    Cookies.set(cookieName, items.join(','));
                },
                "remove": function (val) {
                    indx = items.indexOf(val);
                    if(indx!=-1) items.splice(indx, 1);
                    Cookies.set(cookieName, items.join(','));
                },
                "length": function () {
                    return items.length;
                    },
                "items": function() {
                    //Get all the items.
                    return items;
                }
            }
        }

// $("#close").click(function(){

// $(this).data("id").hide();
// });



// $(".check").click(function(){
//         $("#myCheck").prop("checked", true);
//     });
//     $(".uncheck").click(function(){
//         $("#myCheck").prop("checked", false);
//     });

$(document).ready(function(){

//addToMyList
var proCount = 0;
var tempProId = [];
if( localStorage.getItem('addToList') != null ){
    tempProId = JSON.parse(localStorage.getItem('addToList'));
  	$.each(tempProId , function(index, val) {
		$('.item .sub').each(function(item){
			$(this).find('#removeButton'+val).addClass('show').removeClass('hide');
			$(this).find('#addButton'+val).addClass('hide').removeClass('show');
		});
    });
   }
$('.addToList').click(function(e){
	var proId = e.target.parentElement.dataset.id;
	proCount = $('#addButton'+proId).length;
	for (var i = 1;i <= proCount ;i++) {
	   	tempProId.push(proId);
	   }
	localStorage.setItem('addToList', JSON.stringify(tempProId));
	setTimeout(function(){
		$('#addButton'+proId).addClass('hide').removeClass('show');
	    $('#removeButton'+proId).addClass('show').removeClass('hide');
	}, 100);
});

	//removeToList
	$('.removeToList').click(function(e){
		var proId = e.target.parentElement.dataset.id;
		$('#addButton'+proId).addClass('show').removeClass('hide');
	    $('#removeButton'+proId).addClass('hide').removeClass('show');
	    proCount = $('#removeButton'+proId).length;
	    for (var i = 1;i <= proCount;i++) {
	      var index = tempProId.indexOf(proId);
	      if (index > -1) {
	        var index = tempProId.splice(index, 1);
	     }
	    }
	    localStorage.setItem('addToList', JSON.stringify(tempProId));
	});

	jQuery(window).scroll(function() {
    if ($(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
        $('.back-top-top').fadeIn(200);    // Fade in the arrow
    } else {
        $('.back-top-top').fadeOut(200);   // Else fade out the arrow
    }
});
$('.back-top-top').click(function() {      // When arrow is clicked
    $('body,html').animate({
        scrollTop : 0                       // Scroll to top of body
    }, 500);
});
});

if ($(window).width() < 768) {

$(function() {
    var top = $('.fliter_part').offset().top - parseFloat($('.fliter_part').css('marginTop').replace(/auto/, 0));
    var footTop = $('.footer_part').offset().top - parseFloat($('.footer_part').css('marginTop').replace(/auto/, 0));

    var maxY = footTop - $('.fliter_part').outerHeight();

    $(window).scroll(function(evt) {
        var y = $(this).scrollTop();
        if (y > top) {

//Quand scroll, ajoute une classe ".fixed" et supprime le Css existant
            if (y < maxY) {
                $('.fliter_part').addClass('fixed').removeAttr('style');
            } else {

//Quand la sidebar arrive au footer, supprime la classe "fixed" précèdement ajouté
                $('.fliter_part').removeClass('fixed').css({
                    position: 'absolute',
                    top: (maxY - top) + 'px'
                });
            }
        } else {
            $('.fliter_part').removeClass('fixed');
        }
    });
});
$(document).ready(function() {
  $('.fliterText').on('click', function() {
	$('body').toggleClass('fiter-open');
    $(this).siblings('form').slideToggle();
    $(this).toggleClass('active');
    $(this).find(".autoBtn").slideToggle();
    $(this).parent().toggleClass('open');
  })
});
}
</script>
<!-- END content_part-->


@endsection
