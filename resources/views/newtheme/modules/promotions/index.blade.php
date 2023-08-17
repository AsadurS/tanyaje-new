@extends('newtheme.layouts.main')
@section('meta_for_share')
<meta property="og:title" content="{{ trans('labels.app_name') }}">
<meta property="og:url" content="https://tanyaje.com.my/">
<meta property="og:image" content="{{asset('new/images/logo4.png')}}">
<meta property="og:image:width" content="400" />
<meta property="og:image:height" content="400" />
<meta property="og:image:type" content="image/png">
<meta property="og:description"
    content="Tanya-Je translated as “Just Ask” is the first most advanced online digital automotive classified in South East Asia.">
@endsection
@section('styles')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="{{asset('admin/css/cropper.css') . "?lm=071220201309" }}" />

<style>
      ul.pagination li a{
    color: #000;
}
.row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
    z-index: 3;
    color: #000;
    cursor: default;
    background-color: #ffde00;
    border-color: #ffde00;
}
    div#PicModal .modal-header.text-center {
        display: none;
    }

    div#PicModal .modal-dialog {
        max-width: 900px;
        width: 100%;
        margin: 0 auto;
        text-align: center;
        position: absolute;
        left: 50%;
        overflow: hidden;
        transform: translateX(-50%);
    }

    .modal-footer button#crop {
        max-width: 100px;
    }

    div#PicModal a.close-modal {
        top: 7px;
        right: 0px
    }

    div#PicModal {
        box-shadow: none;
        background: transparent;
        max-width: 900px;
        margin: 0 auto;
        width: 100%;
        left: 50%;
        transform: translatex(-50%);
    }

    .blocker {
        z-index: 999;
    }

    .profile-right.promo div {
        margin-bottom: 20px;

    }

    .promo-image {
        width: 100%;
    }

    .disflexArea:after,
    .disflexArea:before {
        content: none;
    }

    .profile-left h3 {
        margin-top: 0px;
        margin-bottom: 0px;
    }

    .main-infos label {
        padding-top: 10px;
    }

    .profile-left ul li:first-child {
        border-top: 1px solid #f26b21;

    }

    .header-title {
        background: #f26b21;
        padding: 0 10px !important;
    }

    .header-title h3 {
        margin-top: 0px;
        color: #fff;
        padding: 10px 0;
        font-weight: bold;
    }

  .date {
        color: #f26b21;
    }
    .profile-inner {

    padding-bottom: 20px;
}
.profile-right.promo .modal-header{
    margin-bottom:0px!important;
    border-bottom: 0px!important;
}
.profile-right.promo .modal-header .close{
    color: #fff;
    background: #f26b21;
    opacity: 1;
}

    #carousel-custom {
        float: left;
        width: 50%;
    }

    #carousel-custom .carousel-indicators {
        margin: 10px 0 0;
        overflow: auto;
        position: static;
        text-align: left;
        white-space: nowrap;
        width: 100%;
    }

    #carousel-custom .carousel-indicators li {
        background-color: transparent;
        -webkit-border-radius: 0;
        border-radius: 0;
        display: inline-block;
        height: auto;
        margin: 0 !important;
        width: auto;
    }

    #carousel-custom .carousel-indicators li img {
        display: block;
        opacity: 0.5;
    }

    #carousel-custom .carousel-indicators li.active img {
        opacity: 1;
    }

    #carousel-custom .carousel-indicators li:hover img {
        opacity: 0.75;
    }

    #carousel-custom .carousel-outer {
        position: relative;
    }

    .modal {
        max-width: none;
        width: 100%;
        background: transparent;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        -o-box-shadow: none;
        -ms-box-shadow: none;
        box-shadow: none;
        text-align: left;
    }
    .promo-detail > a > label{
        color:#000;
    }
    .promo-by {
        font-style: italic;
    }

    .claim-button {
        padding: 5px 35px;
        font-size: 16px;
        color: #fff;
        display: inline-block;
        text-transform: uppercase;
        font-weight: 700;
        background: #f26b21;
        position: absolute;
        bottom: 20px;
    }
    .claim-button:hover{
        color:#000;
        text-decoration:none;
        border:1px solid #000;
    }

    .d-flex {
        display: flex;
    }

    .row.d-flex>div {
        flex: 1;
        padding: 1em;
    }

    .terms-condition {
        color: #f26b21;
        margin-bottom:5px!important;
    }

    .promo-pop label {
        font-size: 20px;
    }
    .carousel-indicators img{
        max-width:100px;
        max-height:100px
    }
    .carousel-inner > .item > img{
        max-height:250px;
    }
   .redeem-span{
    position: absolute;
    top: 30%;
    left: 30%;
    background: #f26b21;
    color: #000;
    border: 2px solid #000;
    font-weight: 700;
    padding: 10px 20px;
   }
</style>
@endsection
@section('content')

<!-- profile pahe section -->
<section class="profile-main">
    
    <div class="container">
        <div class="profile-inner">
            <div class="profile-left">
                <h3>my account</h3>
                @php
                $source_path = public_path() . "/uploads/users/".$customerData['customers']->avatar;
                if (file_exists($source_path))
                {
                $avatar_url = asset("/uploads/users/".$customerData['customers']->avatar);
                }
                else
                {
                $avatar_url = asset('images/default_user.jpg');
                }
                @endphp
                <div class="upload-img" id="PicSection" style="background-image: url('{{ $avatar_url }}');">
                    <!-- <img src="{{ $avatar_url }}" id="user_avatar"> -->
                    <a href="javascript:;" id="LogoSelection" class="no-pf-img">upload</a>
                </div>
                <h4>{!! $customerData['customers']->user_name !!}</h4>
                <h4>Join since {!! Date('Y',strtotime($customerData['customers']->created_at)) !!}</h4>
                <ul>
                    <li><a href="{!! route('profile') !!}">my profile</a></li>
                    <li class="active"><a href="{!! route('promotions') !!}">Latest Promotions</a></li>
                    <li><a href="{!! route('myrewards') !!}">My Promotions</a></li>
                </ul>
                <a href="{{ route('customer_logout') }}" class="profile-signout customer_logout">SIGN OUT</a>
            </div>
            <div class="profile-right promo">
                <div class="main-infos">
                    <div class="header-title">
                        <h3>Promotion</h3>
                    </div>
                    @if (session('message'))
                                            <div class="alert alert-success alert-dismissable custom-success-box">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> {{ session('message') }} </strong>
                                            </div>
                                        @endif
                                        @if (session('error'))
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> {{ session('error') }} </strong>
                                            </div>
                                        @endif
                    <div class="row">
                        
               
                        @if (count($customerData['promo']) > 0)
                        @foreach ($customerData['promo'] as $key=>$customerData)
                        <div class="col-md-4 promo-detail">
                        <a href="#" onclick="clickPromo('{{ $customerData->promotion_id }}', 'promotion_click')" data-toggle="modal" data-target="#promoPop{{ $customerData->promotion_id }}">
                        
                            @if (file_exists(public_path('/images/promotion/'.$customerData->main_image)))
                            <img style="max-height:150px;" src="{{asset('images/promotion')}}/{{ $customerData->main_image  }}" alt="promo" class="promo-image">
                            @else
                            <img style="max-height:150px;" src="{{ $customerData->main_image }}" alt="promo" class="promo-image">
                            @endif 
                                @if($customerData->redeemed)
                                <span class="redeem-span">REDEEMED </span>
                                @endif
                            <a href="#" data-toggle="modal" data-target="#promoPop{{ $customerData->promotion_id  }}">
                                <label>{{ $customerData->promotion_name  }} </label></a>


                            <p class="date"> {{ Carbon\Carbon::parse($customerData->period_start)->format('Y-m-d') }}
                                till {{ Carbon\Carbon::parse($customerData->period_end)->format('Y-m-d') }} </p>
            </a>
                        </div>

                        <div class="modal fade " id="promoPop{{ $customerData->promotion_id  }}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content ">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>

                                    </div>
                                    <div class="modal-body promo-pop">
                                        <div class="row d-flex">
                                            <div id='carousel-custom' class='carousel slide' data-ride='carousel'>
                                                <div class='carousel-outer'>
                                                    <!-- Wrapper for slides -->
                                                    <div class='carousel-inner'>
                                                        <div class='item active'>
                                                         <!--    <img src="{{asset('images/promotion')}}/{{ $customerData->main_image  }}" alt='' />--> 
                                                         @if (file_exists(public_path('/images/promotion/'.$customerData->main_image)))
                                                        <img src="{{asset('images/promotion')}}/{{ $customerData->main_image }}" alt="">
                                                        @else
                                                            <img src="{{ $customerData->main_image }}" alt=""> 
                                                        @endif 
                                                        </div>
                                                        @if($customerData->additional_images1)
                                                        <div class='item'>
                                                        <img src="{{ $customerData->additional_images1 }}" alt='' />
                                                     </div>
                                                     @endif
                                                     @if($customerData->additional_images2)
                                                        <div class='item'>
                                                        <img src="{{ $customerData->additional_images2 }}" alt='' />
                                                        </div>
                                                        @endif
                                                        @if($customerData->additional_images3)
                                                        <div class='item'>
                                                        <img src="{{ $customerData->additional_images3 }}" alt='' />
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Indicators -->
                                                <ol class='carousel-indicators'>
                                                    <li data-target='#carousel-custom' data-slide-to='0' class='active'>
                                                    @if (file_exists(public_path('/images/promotion/'.$customerData->main_image)))
                                                        <img src="{{asset('images/promotion')}}/{{ $customerData->main_image }}" alt="TEST">
                                                        @else
                                                            <img src="{{ $customerData->main_image }}" alt=""> 
                                                        @endif </li>
                                                        @if($customerData->additional_images1)
                                                    <li data-target='#carousel-custom' data-slide-to='1'><img
                                                            src="{{ $customerData->additional_images1 }}"  alt='' /></li>
                                                            @endif
                                                            @if($customerData->additional_images2)
                                                    <li data-target='#carousel-custom' data-slide-to='2'><img
                                                            src="{{ $customerData->additional_images2 }}" alt='' /></li>
                                                            @endif
                                                    @if($customerData->additional_images3)
                                                    <li data-target='#carousel-custom' data-slide-to='3'><img
                                                            src="{{ $customerData->additional_images3 }}" alt='' /></li>
                                                     @endif
                                                </ol>
                                            </div>
                                            <div class="col-md-6">
                                                <label> {{ $customerData->promotion_name  }}</label>
                                                <p class="promo-by"> by  
                                                {{ $customerData->org_first  }} {{ $customerData->org_last  }}
                                                    </p>

                                                <p class="promo-desc">
                                                    {!! $customerData->description  !!}


                                

                                                </p>
                                                <p class="date"> {{ Carbon\Carbon::parse($customerData->period_start)->format('Y-m-d') }}
                                till {{ Carbon\Carbon::parse($customerData->period_end)->format('Y-m-d') }} </p>

                                {!! Form::open(array('url' =>'redeemcoupon', 'name'=>'redeemcoupon', 'id'=>'redeemcoupon', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                                    {!! Form::hidden('redeem_id_coupon', $customerData->promotion_id, array('class'=>'form-control', 'id'=>'redeem_id_coupon')) !!}
                                    {!! Form::hidden('salesagent_id', $salesagent, array('class'=>'form-control', 'id'=>'salesagent_id')) !!}
                                    {!! Form::hidden('org_id', $customerData->organisation, array('class'=>'form-control', 'id'=>'org_id')) !!}
                                 

                     @if($customerData->redeemed)
                     <button type="submit" class="btn btn-primary claim-button" disabled>REDEEMED</button>
                    @else
                    <button type="submit" class="btn btn-primary claim-button">CLAIM NOW</button>
                    @endif
                                    {!! Form::close() !!}


                                    
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12 terms-condition">
                                                <label>Terms and Conditions apply: </label>
                                            
                                            </div>
                                            <div class="col-md-12 ">
                                            {!! $customerData->fine_print !!} 
            </div>
                                        </div>

                                    </div>

                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->

                        @endforeach
                        @else

                        <h3>{{ trans('labels.NoRecordFound') }}</h3>

                        @endif

                    </div>

                </div>
            </div>
        </div>
        {{ $promos->links() }}
        
</section>

<div class="crop-popup">
    <div class="modal fade change-password" id="PicModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header text-center">
                    Upload Image
                </div>
                <div class="modal-body">
                    <form action="{!! route('photo_upload') !!}" method="post" enctype="multipart/form-data">
                        @csrf
                        <img id="canvasToThumb" class='thumbnail-img' data-src="" />
                        <input type="hidden" id="photo" name="photo" />
                        <input type="hidden" id="x" name="coord_x" />
                        <input type="hidden" id="y" name="coord_y" />
                        <input type="hidden" id="w" name="size_w" />
                        <input type="hidden" id="h" name="size_h" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-primary"
                        id="crop">Save</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>







@endsection
@section('scripts')



<script src="{!! asset('admin/js/plupload.full.min.js') !!}"></script>
<script src="{!! asset('admin/js/cropper.js') !!}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>




<script>
function clickPromo(promoid, event) {
    $.ajax({
        url: "/eventtracker",
        type:"POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            "event": event,
            "promoid": promoid
        },
        success:function(response){
         //   window.location.href  = link;
        },
        async: true
    });
}


    var upload_url = "{{ asset('plupload/upload.php') }}";
    var tmp_url = "{!! asset('/tmp/') !!}";
    var swf_url = "{{ asset('plupload/Moxie.swf') }}";
    var xap_url = "{{asset('plupload/Moxie.xap')}}";
    jQuery(function () {
        var uploader = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'LogoSelection',
            container: document.getElementById('PicSection'),
            url: upload_url,
            multi_selection: false,
            filters: {
                max_file_size: '3mb',
                mime_types: [{
                    title: "Image files",
                    extensions: "jpg,png,jpeg"
                }, ]
            },
            flash_swf_url: swf_url,
            silverlight_xap_url: xap_url,
            init: {
                PostInit: function () {},
                FilesAdded: function (up, files) {
                    files[0].name = Math.random().toString(36).substring(7) + files[0].name;
                    files[0].name = files[0].name.trim();
                    uploader.start();
                    jQuery('.loader').fadeToggle('medium');
                },
                FileUploaded: function (up, file) {

                    $("#PicModal .thumbnail-img").attr('src', '/tmp/' + file.name);
                    $("#PicModal #photo").attr('value', file.name);
                    $("#PicModal").modal();
                    crop_init();
                    $("#loading").hide();
                },
                Error: function (up, err) {
                    alert(err.message);
                }
            }
        });

        plupload.addFileFilter('max_img_resolution', function (maxRes, file, cb) {
            $('#console').html('');
            var self = this,
                img = new o.Image();

            function finalize(result) {
                // cleanup
                img.destroy();
                img = null;

                // if rule has been violated in one way or another, trigger an error
                if (!result) {
                    self.trigger('Error', {
                        code: plupload.IMAGE_DIMENSIONS_ERROR,
                        message: "Please upload image with resolution 261 X 280 pixels or above",
                        file: file
                    });

                }
                cb(result);
            }

            img.onload = function () {
                // check if resolution cap is not exceeded
                finalize(img.width * img.height >= maxRes);

            };

            img.onerror = function () {
                finalize(false);

            };

            img.load(file.getSource());
        });

        uploader.init();

        var image = document.getElementById('canvasToThumb');
        var cropper;

        function crop_init() {
            var jcrop_api, boundx, boundy;

            function showCoords(c) { // show all coords
                $('#x').val(c.x);
                $('#y').val(c.y);
                $('#w').val(c.w);
                $('#h').val(c.h);
            }

            // cropper start

            var options = {
                aspectRatio: 1 / 1,
                center: true,
                highlight: true,
                background: true,
                cropBoxResizable: true,
                modal: true,
                dragMode: 'none',
                viewMode: 1,
                zoomOnWheel: false,
                minContainerWidth: 200,
                minContainerHeight: 200,

                ready: function (e) {
                    console.log(e.type);
                },
                crop: function (e) {
                    var data = e.detail;
                    $('#x').val(Math.round(data.x));
                    $('#y').val(Math.round(data.y));
                    $('#w').val(Math.round(data.height));
                    $('#h').val(Math.round(data.width));
                },
                zoom: function (e) {
                    console.log(e.type, e.detail.ratio);
                }
            };
            cropper = new Cropper(image, options);
            // cropper end
            $('#crop').click(function (e) {
                $('#loading').show();
                var croppedImageDataURL = cropper.getCroppedCanvas().toDataURL("image/png");
                jQuery('#previewDivLawyer img').attr('src', croppedImageDataURL).css({
                    'width': '294px',
                    'height': '294px'
                });
                cropper.getCroppedCanvas().toBlob(function (blob) {
                    var formData = new FormData();
                    formData.append('croppedImage', blob);
                    formData.append('photo', $("#PicModal #photo").val());
                    formData.append('_token', $('#PicModal input[name="_token"]').val());
                    // Use `jQuery.ajax` method
                    $.ajax('/photo_upload', {
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function ($post) {
                            $('#loading').hide();
                            jQuery('#user_avatar').attr('src', $post.photo);
                            $.modal.close();
                            cropper.destroy();
                            window.location.reload();
                        },
                        error: function () {
                            $('#loading').hide();
                            alert(
                                "Something went wrong, Please try after sometime.");
                        }
                    });
                });
                return false;
            });
        }

        var users = {
            init: function () {
                jQuery(document).on('click', '.btn-update', function (e) {
                    //jQuery('.pre-loader').show();
                    var _this = jQuery(this);
                    e.preventDefault();
                    users.fire(_this, "save");
                });
            },
            fire: function (_this, action) {
                var _f = _this.parents('.modal').find(".modal-form");
                console.log(_f);
                var method = action == "save" ? _f.attr('method') : _this.data('method');
                var url = action == "save" ? _f.attr('action') : _this.data('url');
                var data = action == "delete" ? {
                    _token: jQuery('meta[name="_token"]').attr('content')
                } : _f.serialize();
                var ajax = {
                    fire: function () {
                        $.ajax({
                                type: method,
                                url: url,
                                data: data,
                            })
                            .done(function (response) {
                                ajax.success(response)
                            })
                            .fail(function (response) {
                                ajax.error(response)
                            });
                    },
                    success: function (success) {
                        //jQuery('.pre-loader').hide();
                        console.log(success);
                        $.each(success, function (key, value) {
                            if (key == "phone") {
                                jQuery(".form_phone").val(value);
                            } else if (key == 'password') {
                                jQuery(".form_password").val(value);
                            }
                        });
                        $.modal.close();

                    },
                    error: function (error) {
                        jQuery('.pre-loader').hide();
                        jQuery(_f).find(".has-error").remove();
                        var response = JSON.parse(error.responseText);
                        $.each(error.responseJSON, function (key, value) {
                            if (key == 'agree_terms') {
                                jQuery('.confirm-read-tc').find('.has-error').remove();
                                jQuery('.confirm-read-tc').append(
                                    "<span class='has-error'>" + value + "</span>");
                            } else {
                                var input = '[name=' + key + ']';
                                jQuery(_f).find(input).parent().find(".has-error")
                                    .length == 0 ? jQuery(_f).find(input).parent()
                                    .append("<span class='has-error'>" + value +
                                        "</span>") : jQuery(_f).find(input).parent()
                                    .find('.has-error').html(value);
                            }
                        });
                    }
                }
                ajax.fire();
            }
        }
        users.init();
    });

</script>
@endsection
  