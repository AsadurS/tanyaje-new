@extends('layouts.main')
@section('meta_for_share')
    <meta property="og:title" content="{{ trans('labels.app_name') }} | Car Quotation">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:image" content="{{asset('new/images/logo4.png')}}">
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="400" />
    <meta property="og:image:type" content="image/png">
    <meta property="og:description" content="Tanya-Je translated as “Just Ask” is the first most advanced online digital automotive classified in South East Asia.">
@endsection
@section('content')
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

<div class="featured-slider-wrap" id="quotation_for_car" style="padding: 41px 0px 90px !important;">
    <div class="container">
        <div class="alert alert-danger print-error-msg" style="display:none">
            <ul></ul>
        </div>
        <div class="row align-items-center">
            <div class="col-12">
                <div style="color:black;text-align: center;padding:10px 0px 36px;font-size: initial;">
                    <span>Simply fill up the form below and we will give you the valuation</span>
                    <br>
                    <span>of your car in 24 hours, <span style="font-weight:bold;">for FREE</span></span>
                    
                </div>
            </div>
            <div class="col-2">
            </div>
            <div class="col-8">
                <form>
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="name" class="form-control" placeholder="Name">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="email" class="form-control" placeholder="Email">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="contact" class="form-control" placeholder="Contact">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="model" class="form-control" placeholder="Model">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="year" class="form-control" placeholder="Year">
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <br>
                        <div class="col-12">
                            <div style="text-align: center;">
                                <button class="btn btn-secondary btn-submit" style="border-radius: 12px;background: black;">Proceed ></button>
                            </div>
                        </div>
                    </form>
            </div>
            <div class="col-2">
            </div>
        </div>
    </div>
</div>

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
@endpush