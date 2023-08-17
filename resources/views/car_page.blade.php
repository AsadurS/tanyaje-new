@extends('layouts.main')
@section('meta_for_share')
    <meta property="og:title" content="{{ trans('labels.app_name') }} | {{ $car->vim }}">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:image" content="{{ $car_image_url }}">
    <meta property="og:description" content="Tanya-Je translated as “Just Ask” is the first most advanced online digital automotive classified in South East Asia.">
@endsection
@section('content')
<style>
    .holder input::placeholder {
        color: white;
    }

    .btn-whatsapp, .btn-contact{
        height: 30px;
        line-height: 30px;
        min-width: 150px;
        margin-bottom:10px;
    }

    @media (max-width: 767px) and (min-width: 320px)
    {
        .contact-section {
            padding-bottom: 80px;
        }
    }
</style>
<div class="hero" style="background: url(../images/car_quote.jpg) no-repeat scroll top;background-size: cover;">
    <div class="hero-caption">
        <p style="color:black;">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
            Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
    </div>
</div>
@if($car != null)
<div class="info-text-wrap" id="cardetail" style="padding: 20px 0px 35px 0px !important;">
    <div class="padding-container">
        @if (count($errors) > 0)
            @if($errors->any())
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{$errors->first()}}
                </div>
            @endif
        @endif
        <div class="row">
            <div class="col-md-8" style="padding:0px;">
                <div class="col-md-12" style="padding-bottom:10px;">
                    <div style="border: 1px solid black;height:500px;text-align:center;padding-top:250px;">Spin Car</div>
                </div>
                <div class="col-md-12" style="padding-top:10px;padding-bottom:20px;text-align:left;">
                    <div style="border: 1px solid black;background-color:black;color:white;width:150px;padding:3px;padding-left:8px;font-weight: bold;display: inline;font-size:18px;">
                        Car Specification
                    </div>&nbsp;
                    @if($car->pdf !== null)
                    <div style="border: 1px solid black;background-color:grey;color:white;width:150px;padding:3px;font-weight: bold;display: inline;font-size:18px;">
                        <a href="{{asset($car->pdf)}}" download="{{$car->car_id}}" style="color:white;">PDF Download</a>
                    </div>
                    @endif
                    <div style="height:2px;"></div>
                    <div style="border: 1px solid black;padding:10px;text-align:left;height:200px;overflow: auto;">
                        <?php /*echo htmlspecialchars_decode($car->html_editor);*/?>
                        <?php echo "<pre>".$car->html_editor."</pre>";?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div style="background-color:#1a1a1a;float:top;text-align:left;padding-left:20px;padding-right:20px;padding-top:10px;min-height:750px;">
                    <div class="title" style="text-transform:capitalize !important;color:white;padding-bottom:10px;">Seller Info</div>
                    <p style="color:white;padding-bottom:0px;">
                        <span style="font-weight: bold;">Name: {{ $car->merchant_name }}</span> <br>
                        <span style="font-weight: bold;">State: {{ $car->state_name }}</span> <br>
                        <span style="font-weight: bold;">Area: {{ $car->city_name }}</span> <br>
                    </p>
                    <div style="font-weight: bold;color:white;display: inline;vertical-align:top;">Contact: {{ $car->merchant_phone_no }}</div>
                    <br/>
                    <?php if($car->merchant_phone_no != null){?>
                        <div class="contact-section" style="display: inline-block;height:40px;">
                            <a class="btn btn-default btn-whatsapp" style="width:160px;padding:0px;" href="https://wa.me/{{ str_replace(' ', '', $car->merchant_phone_no) }}" target="_blank">
                                <div class="box1" style="border-radius: 12px;background-color:#25d366;font-weight: bold;font-size: 10px;">
                                    <i class="fa fa-whatsapp fa-lg" aria-hidden="true"></i>
                                    WhatsApp
                                </div>
                            </a>
                            <a class="btn btn-default btn-contact" style="width:160px;padding:0px;" href="tel:+{{ str_replace(' ', '', $car->merchant_phone_no) }}" target="_blank">
                                <div class="box1" style="border-radius: 12px;background-color:yellow;color:black; font-weight: bold;font-size: 10px;">
                                    <i class="fa fa-phone fa-lg" aria-hidden="true"></i>
                                    Contact
                                </div>
                            </a>
                        </div>
                    <?php }else{?>
                        <div style="display: inline-block;height:73px;"></div>
                    <?php }?>

                    <div class="addthis_inline_share_toolbox" style="padding-bottom:30px;"></div>

                    <input name="vin" id="vin" type="hidden" value="{{ $car->vim }}">
                    <p style="color:white;font-size:19px;border: 1px solid white;padding-top:3px;padding-bottom:3px;border-top: none;border-left: none;border-right: none;display:none">
                        <span style="font-weight: bold;">VIN:</span> {{ $car->vim }}
                    </p>
{{--                    <p style="color:white;font-size:19px;border: 1px solid white;padding-top:3px;padding-bottom:3px;border-top: none;border-left: none;border-right: none;">--}}
{{--                        <span style="font-weight: bold;">Stock:</span> {{ $car->stock_number }}--}}
{{--                    </p>--}}
                    <p style="color:white;font-size:19px;border: 1px solid white;padding-top:3px;padding-bottom:3px;border-top: none;border-left: none;border-right: none;">
                        <span style="font-weight: bold;">Model:</span> {{ $car->model_name }}
                    </p>
                    <p style="color:white;font-size:19px;border: 1px solid white;padding-top:3px;padding-bottom:3px;border-top: none;border-left: none;border-right: none;">
                        <span style="font-weight: bold;">Make:</span> {{ $car->make_name }}
                    </p>
                    <p style="color:white;font-size:19px;border: 1px solid white;padding-top:3px;padding-bottom:3px;border-top: none;border-left: none;border-right: none;">
                        <span style="font-weight: bold;">Status:</span> @if($car->status==1) New @elseif($car->status==2) Used @elseif($car->status==3) Recond @endif
                    </p>
                    <p style="color:white;font-size:19px;padding-top:3px">
                        <span style="font-weight: bold;">Price:</span> RM{{ number_format($car->price, 2, '.', ',') }}<br>
                    </p>
                    <div class="title" style="text-transform:capitalize !important;color:#00e6b8;padding-bottom:10px;">I'm interested!</div>
                    {!! Form::open(array('url' =>'/enquired', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                    {!! Form::hidden('car_id', $car->car_id, array('class'=>'form-control', 'id'=>'car_id'))!!}
                    {!! Form::hidden('merchant_email', $car->merchant_email, array('class'=>'form-control', 'id'=>'merchant_email'))!!}
                    <div class="row">
                        <div class="col-md-12 holder" style="padding-bottom:5px;">
                            <input type="text" name="name" class="form-control" placeholder="Name" style="background-color:rgba(255, 255, 255, 0);color:white;border:1px solid white;" required>
                        </div>
                        <div class="col-md-12 holder" style="padding-bottom:5px;">
                            <input type="text" name="email" class="form-control" placeholder="Email" style="background-color:rgba(255, 255, 255, 0);color:white;border:1px solid white;" required>
                        </div>
                        <div class="col-md-12 holder" style="padding-bottom:5px;">
                            <input type="text" name="contact" class="form-control" placeholder="Contact" style="background-color:rgba(255, 255, 255, 0);color:white;border:1px solid white;" required>
                        </div>
                    </div>
                    <div class="col-md-12 box" style="text-align:right;padding-top:5px;padding-bottom:5px;padding-right:0px;padding-left:0px;">
                        <button class="btn btn-default btn-lg home-button" type="submit" style="padding:0px;">
                            <div class="box1" style="border-radius: 12px;width:180px;">
                                <span style="font-size: 18px;">Enquired >></span>
                            </div>
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            
        </div>
    </div>
</div>
@endif
<div class="featured-slider-wrap">
    <div class="padding-container">
        <div class="row">
            <div class="col-12 text-center" style="margin:auto;">
                <div class="title" style="text-transform:capitalize !important;">Simple 3 steps to get quote!</div>
            </div>
            <div class="col-12">
                <div class="row">
            <!-- Boxes de Acoes -->
                    <div class="col-md-4 col-sm-12 text-center">
                        <div class="box">							
                            <div class="icon">
                                <div class="image">
                                    <i class="fa fa-reply" style="font-size: 50px;"></i>
                                </div>
                                <div class="clearfix"></div>
                                <div style="text-align: center;">
                                    <span class="title" style="text-transform:capitalize !important;">Submit</span>
                                </div>
                                <div class="align-middle" style="text-align: center;">
                                    <p>Enter and submit details below</p>
                                </div>
                            </div>
                            <div class="space"></div>
                        </div> 
                    </div>
                
                    <div class="col-md-4 col-sm-12 text-center">
                        <div class="box">							
                            <div class="icon">
                                <div class="image">
                                    <i class="fa fa-search" style="font-size: 50px;"></i>
                                </div>
                                <div class="clearfix"></div>
                                <div style="text-align: center;">
                                    <span class="title" style="text-transform:capitalize !important;">Source</span>
                                </div>
                                <div class="align-middle" style="text-align: center;">
                                    <p>
                                        We will source for the <br> highest quote on our network 
                                    </p>
                                </div>
                                
                            </div>
                            <div class="space"></div>
                        </div> 
                    </div>
                
                    <div class="col-md-4 col-sm-12 text-center">
                        <div class="box">							
                            <div class="icon">
                                <div class="image">
                                    <i class="fa fa-calendar" style="font-size: 50px;"></i>
                                </div>
                                <div class="clearfix"></div>
                                <div style="text-align: center;">
                                    <span class="title" style="text-transform:capitalize !important;">Appointment</span>
                                </div>
                                <div class="align-middle" style="text-align: center;">
                                    <p>
                                        Set an appointment to<br> transact if the price is right 
                                    </p>
                                </div>
                            </div>
                            <div class="space"></div>
                        </div> 
                    </div>	
                </div>
            </div>
            <div class="col-12 text-center">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-2 offset-md-4" style="float: right;margin: auto;">
                        <button class="btn btn-secondary start_button" href="quotation_for_car" style="border-radius:8px;background:black;font-size: 13px">Get Started ></button>
                        <img src="{{asset('images/try-free.png')}}" class="g-start">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('inc.year_of_experience')

@include('inc.why_us')

@include('inc.testimonial')

@include('inc.contact_us')

@endsection
@push('scripts')
<script>
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
    var $anchor = $('#cardetail').offset();
    window.scrollTo($anchor.left,$anchor.top - 70);
    $(".btn-submit").click(function(e){
        e.preventDefault();

        var _token = $("input[name='_token']").val();
        var name = $("input[name='name']").val();
        var contact = $("input[name='contact']").val();
        var email = $("input[name='email']").val();
        var model = $("input[name='model']").val();
        var year = $("input[name='year']").val();


        $.ajax({
            url: "/carquotation",
            type:'POST',
            data: {_token:_token, name:name, contact:contact, email:email, model:model, year:year},
            success: function(data) {
                if($.isEmptyObject(data.error)){
                    $("input[name='name']").val('');
                    $("input[name='contact']").val('');
                    $("input[name='email']").val('');
                    $("input[name='model']").val('');
                    $("input[name='year']").val('');
                    swal("Success", data.success, "success")
                }else{
                    printErrorMsg(data.error);
                }
            }
        });

    });
    function printErrorMsg (msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        });
    } 
</script>

<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5f17ff7371b9f206"></script>
@endpush