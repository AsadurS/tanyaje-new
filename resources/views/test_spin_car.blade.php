@extends('layouts.main')
@section('content')
    <div class="featured-slider-wrap">
        Test Spin Car
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
@endsection