@extends('layouts.main')
@section('content')
<link href="{{asset('js/select2.min.css')}}" rel='stylesheet' type='text/css'>
<style>
    .box a:hover .box1 {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .box button:hover .box1 {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .donate-now {
        list-style-type: none;
        margin: 10px 0 0 0;
        padding: 0;
    }

    .donate-now li {
        float: left;
        margin: 0 5px 0 0;
        width: 90px;
        height: 40px;
        position: relative;
    }

    .donate-now label,
    .donate-now input {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    .donate-now input[type="radio"] {
        opacity: 0.01;
        z-index: 100;
    }

    .donate-now input[type="radio"]:checked+label,
    .Checked+label {
        background: black;
    }

    .donate-now label {
        padding: 5px;
        border: 1px solid #CCC;
        cursor: pointer;
        z-index: 90;
        color: white;
        font-size: 15px;
        font-weight: bold;
        border-radius: 15px;
    }

    .donate-now label:hover {
        background: grey;
    }

    @media only screen and (min-width: 1200px) {
        .container {
            padding-left:200px;
            padding-right:200px;
        }
    }

    @media only screen and (min-width: 1200px) {
        .home-slide {
            padding-left:200px;
            padding-right:200px;
        }
    }

    @media only screen and (min-width: 767px) {
        .home-slide .info-text-wrap .row .row1 {
            padding-bottom:20px;
        }
    }

    @media only screen and (max-width: 767px) {
        .home-slide .info-text-wrap .row .row1 {
            padding-bottom:5px;
        }
    }

    /************
    start ddSlick
    *************/

    #imagedropdownlisttype .dd-selected-text,
    #imagedropdownlisttype .dd-option-text
    {
        line-height: unset !important;
        font-weight: normal;
        padding-left: 10px;
        float: left;
    }

    #imagedropdownlisttype .dd-option
    {
        border-bottom: solid 1px #FFF;
    }

    #imagedropdownlisttype label
    {
        margin-bottom: unset;
    }

    #imagedropdownlisttype .dd-select
    {
        border-radius: 5px;
    }

    #imagedropdownlisttype ul
    {
        padding: unset;
    }

    #imagedropdownlisttype ul li
    {
        margin: unset;
    }

    #imagedropdownlisttype .dd-selected
    {
        padding: 7px;
    }
    /************
    end ddSlick
    *************/

    .select2-container .select2-selection--single {
        font-size: 15px;
        height: 40px;
        padding-left: 11px;
        padding-top: 4px;
        font-family: Arial, Helvetica, sans-serif;
        text-align: left;
    }

    .select2-selection__arrow {
        height: 35px !important;
    }

    .car-list .col-md-4
    {
        flex: 0 0 20%;
        max-width: 20%;
    }

    @media (min-width: 1241px)
    {
        .car-img-div
        {
            height: 100%;
        }

        .car-img
        {
            width: 100%;
            height: 100%;
            /*object-fit: cover;*/
            background-size: cover
        }
    }

    @media (max-width: 1240px) and (min-width: 992px)
    {
        .car-img-div
        {
            height: 150px;
        }

        .car-img
        {
            width: 204px;
            height: 153px;
        }
    }

    @media (max-width: 992px) and (min-width: 768px)
    {
        .car-img-div
        {
            height: 150px;
        }

        .car-img
        {
            width: 204px;
            height: 153px;
        }
    }

    @media (max-width: 767px) and (min-width: 320px)
    {
        .car-list .col-md-4
        {
            flex: unset;
            max-width: unset;
        }

        .car-img
        {
            width: unset;
            height: unset;
        }
    }
</style>
{!! Form::open(array('url' =>'/carsearch', 'method'=>'get', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
<?php 
    if(isset($_GET['c0'])){$status = $_GET['c0'];}else{$status=0;}
    if(isset($_GET['c1'])){$price = $_GET['c1'];}else{$price=0;}
    if(isset($_GET['price_from'])){$price_from = $_GET['price_from'];}else{$price_from='';}
    if(isset($_GET['price_to'])){$price_to = $_GET['price_to'];}else{$price_to='';}
    if(isset($_GET['c2'])){$type = $_GET['c2'];}else{$type=0;}
    if(isset($_GET['c3'])){$make = $_GET['c3'];}else{$make=0;}
    if(isset($_GET['c4'])){$model = $_GET['c4'];}else{$model=0;}
    if(isset($_GET['year_from'])){$year_from = $_GET['year_from'];}else{$year_from='';}
    if(isset($_GET['year_to'])){$year_to = $_GET['year_to'];}else{$year_to='';}
    if(isset($_GET['c5'])){$state = $_GET['c5'];}else{$state=0;}
    if(isset($_GET['c6'])){$city = $_GET['c6'];}else{$city=0;}
    if(isset($_GET['c7'])){$company_name = $_GET['c7'];}else{$company_name=0;}
?>
<div class="car-search home-slide" id="home_content">
    <div>
        <div class="row">
            <div class="col-12 text-center box" >
            <div class="row align-items-center">
                <div class="col-md-5">
                    <p style="color:white;font-size:25px;padding-bottom:0px;font-weight: bold;">Find Your Car</p>
                </div>
                <div class="col-md-7">
                    <ul class="donate-now">
                        <li>
                            <input type="radio" id="All" name="c0" checked="checked" value="0" <?php if($status==0){echo "checked";}?>/>
                            <label for="All">All</label>
                        </li>
                        <li>
                            <input type="radio" id="New" name="c0" value="1" <?php if($status==1){echo "checked";}?>/>
                            <label for="New">New</label>
                        </li>
                        <li>
                            <input type="radio" id="Used" name="c0" value="2" <?php if($status==2){echo "checked";}?>/>
                            <label for="Used">Used</label>
                        </li>
                        <li>
                            <input type="radio" id="Recond" name="c0" value="3" <?php if($status==3){echo "checked";}?>/>
                            <label for="Recond">Recond</label>
                        </li>
                    </ul>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="info-text-wrap" style="padding: 30px 0px 10px 0px !important;border-radius: 12px;background-color:rgba(0, 0, 0, 0.5);">
        <div class="padding-container">
            <div class="row align-items-center">
                <div class="col-md-4 row1">
                    <!--select name="c1" class="form-control field-validate">
                        <option value="0"> Price (low to expensive) </option>
                        <option value="1" <?php if($price==1){echo "selected";}?>>< 10000</option>
                        <option value="2" <?php if($price==2){echo "selected";}?>>10001 - 50000</option>
                        <option value="3" <?php if($price==3){echo "selected";}?>>50001 - 100000</option>
                        <option value="4" <?php if($price==4){echo "selected";}?>>100000 - 500000</option>
                        <option value="5" <?php if($price==5){echo "selected";}?>>> 500000</option>
                    </select-->
                    <div style="display:flex;">
                        <input type="text" name="price_from" id="price_from" class="form-control field-validate" placeholder="Price From" style="margin-right:5px;" value="{{ $price_from }}">
                        <input type="text" name="price_to" id="price_to" class="form-control field-validate" placeholder="Price To" style="margin-left:5px;" value="{{ $price_to }}">
                    </div>
                </div>
                <div class="col-md-4 row1">
                    <div style="display:flex;">
                        <input type="text" name="year_from" id="year_from" class="form-control field-validate" placeholder="Year From (Year Make)" style="margin-right:5px;" value="{{ $year_from }}">
                        <input type="text" name="year_to" id="year_to" class="form-control field-validate" placeholder="Year To (Year Make)" style="margin-left:5px;" value="{{ $year_to }}">
                    </div>
                </div>
                <div class="col-md-4 row1">
                    <!--select name="c2" class='form-control field-validate'>
                        <option value="0"> Car Type </option>
                        <?php if(!empty($result['type'])) { ?>
                        @foreach( $result['type'] as $type_data)
                            <option
                                @if( $type_data->type_id == $type)
                                selected
                                @endif
                                value="{{ $type_data->type_id }}"> {{ $type_data->type_name }}
                            </option>
                        @endforeach
                        <?php }?>
                    </select-->

                    <select id="imagedropdownlisttype" class='form-control field-validate'>
                        <option value="0"> Car Type </option>
                        <?php if(!empty($result['type'])) { ?>
                        @foreach( $result['type'] as $type_data)
                            <option value="{{ $type_data->type_id }}"
                                    data-imagesrc="{{ $type_data->imgpath }}"
                                    @if( $type_data->type_id == $type)
                                    selected="selected"
                                    @endif
                            >
                                {{ $type_data->type_name }}
                            </option>
                        @endforeach
                        <?php }?>
                    </select>
                    <input name="c2" class="c2" type="hidden" value="0">
                </div>
                <div class="col-md-4 row1">
                    <select name="c3" class='form-control field-validate' id="choose_make_id">
                        <option value="0"> Select Make </option>
                        <?php if(!empty($result['make'])) { ?>
                        @foreach( $result['make'] as $make_data)
                            <option
                                @if( $make_data->make_id == $make)
                                selected
                                @endif
                                value="{{ $make_data->make_id }}"> {{ $make_data->make_name }} 
                            </option>
                        @endforeach
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-4 row1">
                    <select name="c4" class='form-control modelContent field-validate'>
                        <option value="0"> Select Model </option>
                        <?php if(!empty($result['model'])) { ?>
                        @foreach( $result['model'] as $model_data)
                            <option
                                @if( $model_data->model_id == $model)
                                selected
                                @endif
                                value="{{ $model_data->model_id }}"> {{ $model_data->model_name }} 
                            </option>
                        @endforeach
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-4 row1">
                    <select name="c5" class='form-control field-validate' id="choose_state_id">
                        <option value="0"> State </option>
                        <?php if(!empty($result['state'])) { ?>
                        @foreach( $result['state'] as $state_data)
                            <option
                                @if( $state_data->state_id == $state)
                                selected
                                @endif
                                value="{{ $state_data->state_id }}"> {{ $state_data->state_name }} 
                            </option>
                        @endforeach
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-4 row1">
                    <select name="c6" class='form-control cityContent field-validate'>
                        <option value="0"> City </option>
                        <?php if(!empty($result['city'])) { ?>
                        @foreach( $result['city'] as $city_data)
                            <option
                                @if( $city_data->city_id == $city)
                                selected
                                @endif
                                value="{{ $city_data->city_id }}"> {{ $city_data->city_name }} 
                            </option>
                        @endforeach
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-4 row1">
                    <select name="c7" id='selCompanyName' class='form-control companyNameContent field-validate' style="width:100%;">
                        <option value="0"> Listed By </option>
                        <?php if(!empty($result['company_name'])) { ?>
                        @foreach( $result['company_name'] as $company_name_data)
                            <option
                                @if( $company_name_data->id == $company_name)
                                selected
                                @endif
                                value="{{ $company_name_data->id }}"> {{ $company_name_data->company_name }} 
                            </option>
                        @endforeach
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-4 row1">
                    <button class="btn btn-default btn-lg home-button" type="submit" style="padding:0px; float: right;">
                        <div class="box1" style="border-radius: 12px;width:220px;">
                            <span style="font-size: 16px;">Search Cars >></span>
                        </div>
                    </button>
                </div>
                <!--div class="col-md-12 text-center box">
                    <button class="btn btn-default btn-lg home-button" type="submit" style="padding:0px;">
                        <div class="box1" style="border-radius: 12px;width:220px;">
                            <span style="font-size: 16px;">Search Cars >></span>
                        </div>
                    </button>
                </div-->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center box" style="padding-top:40px;">
            <p style="color:white;font-size:30px;padding-bottom:0px;font-weight: bold;">Asean #1st Car Virtual Viewing</p>
        </div>
    </div>
</div>
{!! Form::close() !!}
<div class="info-text-wrap text1" style="background-color:#DCDCDC;padding-top:30px;padding-bottom:30px;">
    <div class="container">
        <div class="row align-items-center" style="border: 1.5px solid #00FF66;background-color:white;">
            <div class="col-sm-12 col-md-12" style="padding-top:40px;">
                <p class="col-12 text-center text-dark" style="font-weight: bold;font-size:27px;">Lorem ipsum dolor sit amet</p>
            </div>
            <div class="col-sm-12 order-md-last">
                <div class="row">
                    <div class="col-sm-12">
                        <p class="col-12 text-center pb-lg-5 text-dark" style="font-weight: normal;font-size:18px;">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="featured-slider-wrap car-list" id="car" style="padding-top:50px;padding-bottom:20px;">
    <div class="padding-container">
        <div class="row">
            <div class="col-12">
                <div class="row">
            <!-- Boxes de Acoes -->
                    <?php $i=0;?>
                    @foreach ($car as $key=>$value)<?php $i++;?>
                    <div class="col-md-4 col-sm-12 text-center">
                        <div class="box">
                            <a href="{{url('carpage/'.$value->car_id)}}">
                            <div class="box1">							
                            <div class="icon" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                                <div class="image car-img-div">
                                    <img src="{{ 'https://manager.spincar.com/web-preview/walkaround-thumb/' . $value->sp_account . '/' . strtolower($value->vim) . '/md'  }}" class="img-fluid car-img" alt="figure">
                                </div>
                                <div class="clearfix"></div>
                                <div style="background-color:#333333;text-align: left;padding-left:10px;height:3.0em;">
                                    <span class="title" style="color: #0090ff;font-size: 15px;display: block;text-overflow: ellipsis;word-wrap: break-word;overflow: hidden;max-height: 3.0em;line-height: 1.5em;">{{ $value->title }}</span>
                                </div>
                                <div class="align-middle" style="background-color:#333333;text-align: left;padding-left:10px;font-size: 12px;">
                                    <p style="color: white;text-align: justify;padding-bottom:10px;">
                                        <span style="font-weight: bold;">Make:</span> {{ $value->make_name }}<br>
                                        <span style="font-weight: bold;">Model:</span> {{ $value->model_name }}<br>
                                        <span style="font-weight: bold;">Status:</span> @if($value->status==1) New @elseif($value->status==2) Used @elseif($value->status==3) Recond @endif  <br/>
                                        <span style="font-weight: bold;">Price:</span> RM{{ number_format($value->price, 2, '.', ',') }}
                                    </p>
                                </div>
                            </div>
                            </div>
                            </a>
                            <div class="space" style="height:20px;"></div>
                        </div> 
                    </div>
                    @endforeach
                    <?php if($i == 0){?>
                        <div class="col-xs-12 text-right" style="font-size:20px;">
                            Car not found!
                        </div>
                    <?php }?>
                </div>
                <div class="col-xs-12 text-right">
                    {{ $car->appends(\Request::except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
    <!--div class="row">
        <div class="col-12 text-center box">
            <a class="btn btn-default btn-lg home-button" style="font-weight: bold;font-size: 18px;width:300px;" href="{{route('car_search')}}"><div class="box1" style="border-radius: 12px;">Browse more cars >></div></a>
        </div>
    </div-->
</div>

@include('inc.why_us')

@include('inc.contact_us')

@endsection
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{asset('js/select2.min.js')}}" type='text/javascript'></script>
    <script src="{{asset('js/jquery.ddslick.min.js')}}" type="text/javascript"></script>
    <script>
        $('#imagedropdownlisttype').ddslick({
            imagePosition:"right",
            selectText: "Car Type",
            width: '100%',
            background: '#FFF',
            onSelected: function(selectedData)
            {
                //callback function: do something with selectedData;
                $('.c2').val(selectedData.selectedData.value);
            }
        });

        $(window).scroll(function() {
            var scroll = $(window).scrollTop();

            if (scroll >= 100) {
                $(".site-header").addClass("fixed");
            }
            if(scroll < 100){
                $(".site-header").removeClass("fixed");
            }
        });
        $('.start_button').click(function(){
            var $href = $(this).attr('href');
            var $anchor = $('#'+$href).offset();
            window.scrollTo($anchor.left,$anchor.top);
            return false;
        });
        url = new URL(window.location.href);
        if (url.searchParams.get('c0')) {
            var $anchor = $('#car').offset();
            window.scrollTo($anchor.left,$anchor.top - 70);
        }

        //onchange get model agains make
        jQuery(document).on('change', '#choose_make_id', function(e){

            var make_id = $(this).val();
            $.ajax({
                url: "{{ URL::to('/getModel')}}",
                dataType: 'json',
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "make_id" : make_id,
                },
                success: function(data)
                {
                    if(data.data.length>0)
                    {
                        //alert(2);
                        var i;
                        var showData = [];
                        showData[0] = "<option value='0'> Select Model </option>";
                        for (i = 0; i < data.data.length; ++i) {
                                            var j= i +1;
                            showData[j] = "<option value='"+data.data[i].model_id+"'>"+data.data[i].model_name+"</option>";
                        }
                        $('.selectmodel').show();
                    }
                    else
                    {
                        showData = "<option value=''></option>"
                        $('.selectmodel').hide();
                        $('.othermodel').show();
                    }
                    $(".modelContent").html(showData);
                }
            });
        });

        //onchange get city agains state
        jQuery(document).on('change', '#choose_state_id', function(e){

            var state_id = $(this).val();
            $.ajax({
                url: "{{ URL::to('/getCity')}}",
                dataType: 'json',
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "state_id" : state_id,
                },
                success: function(data)
                {
                    if(data.data.length>0)
                    {
                        //alert(2);
                        var i;
                        var showData = [];
                        showData[0] = "<option value='0'> City </option>";
                        for (i = 0; i < data.data.length; ++i) {
                                            var j= i +1;
                            showData[j] = "<option value='"+data.data[i].city_id+"'>"+data.data[i].city_name+"</option>";
                        }
                        $('.selectcity').show();
                    }
                    else
                    {
                        showData = "<option value=''></option>"
                        $('.selectcity').hide();
                        $('.othercity').show();
                    }
                    $(".cityContent").html(showData);
                }
            });
        });

        $(document).ready(function(){
            // Initialize select2
            var select2 = $("#selCompanyName").select2();
        });
    </script>
    <script>
        var car_img_width = 204;
        var car_img_height = 156;

        $('.car-img').on('load', function () {
            //retrieve image size
            var img = $('.car-img-div')[0];
            var width = img.clientWidth;
            var height = img.clientHeight;
            var message = 'width: ' + width + ' height: ' + height;
            console.log(message)

            if( width > car_img_width )
            {
                car_img_width = width;
                localStorage.setItem('car_img_width',width);
            }

            if( height > car_img_height )
            {
                car_img_height = height;
                localStorage.setItem('car_img_height', height);
            }

            var styleSetting = 'width:' + car_img_width + 'px; height:' + car_img_height + 'px;';
            $(this).attr('style', styleSetting)
        });

        $( window ).resize(function() {
            localStorage.setItem('car_img_width',0);
            localStorage.setItem('car_img_height', 0);
        });

        setTimeout(function()
        {
            var width = localStorage.getItem('car_img_width');
            var height = localStorage.getItem('car_img_height');

            if( width > car_img_width )
            {
                car_img_width = width;
            }

            if( height > car_img_height )
            {
                car_img_height = height;
            }

            var styleSetting = 'width:' + car_img_width + 'px; height:' + car_img_height + 'px;';
            $('.car-img').attr('style', styleSetting)
        }, 1000);
    </script>

@endpush