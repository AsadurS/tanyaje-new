@extends('newtheme.layouts.main')
@section('meta_for_share')
    <meta property="og:title" content="{{ trans('labels.app_name') }}">
    <meta property="og:url" content="https://tanyaje.com.my/">
    <meta property="og:image" content="{{asset('new/images/logo4.png')}}">
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="400" />
    <meta property="og:image:type" content="image/png">
    <meta property="og:description" content="Tanya-Je translated as “Just Ask” is the first most advanced online digital automotive classified in South East Asia.">
@endsection
@section('styles')
<link rel="stylesheet" href="{{asset('admin/css/cropper.css') . "?lm=071220201309" }}" />
<style>
div#PicModal .modal-header.text-center { display: none; }
div#PicModal .modal-dialog { max-width: 900px;width:100%; margin: 0 auto; text-align: center; position: absolute; left: 50%; overflow: hidden; transform: translateX(-50%);}
.modal-footer button#crop{max-width:100px;}
div#PicModal a.close-modal{top:7px;right:0px}
div#PicModal {box-shadow: none;background: transparent;max-width: 900px;margin: 0 auto;width: 100%;left: 50%;transform: translatex(-50%);}
.blocker {z-index: 999;}
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
                    <li class="active"><a href="{!! route('profile') !!}">my profile</a></li>
                    <li><a href="{!! route('wishList') !!}">saved cards</a></li>
                </ul>
                <a href="{{ route('customer_logout') }}" class="profile-signout customer_logout">SIGN OUT</a>
            </div>
            <div class="profile-right">
            <form method="post" action="{{ route('profile') }}">
            @csrf
                <div class="main-info">
                    <h3>Account infomation</h3>
                        <div class="info">
                            <div class="col-2">
                                <div class="row">
                                    <label>First Name <sup>*</sup></label>
                                    <input type="text" name="first_name" value="{{$customerData['customers']->first_name}}" required>
                                </div>
                                <div class="row">
                                    <label>Last Name <sup>*</sup></label>
                                    <input type="text" name="last_name" value="{{$customerData['customers']->last_name}}" required>
                                </div>
                            </div>
                            <div class="col-1">
                                <div class="row">
                                    <label>Password <sup>*</sup></label>
                                    <div class="change-num">
                                        <input class="form_password" type="password" name="password" placeholder="*****************" disabled>
                                        <a href="#ChangePasswordModal" class="change_password" rel="modal:open">Change Password</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <div class="col-2">
                                <div class="row">
                                    <label>D.O.B <sup>*</sup></label>
                                    <input type="date" name="dob" value="{{$customerData['customers']->dob}}" required>
                                </div>
                                <div class="row">
                                    <label>Gender <sup>*</sup></label>
                                    <input type="text" name="gender" value="{{$customerData['customers']->gender}}" required>
                                </div>
                            </div>
                            <div class="col-1"></div>
                        </div>
                    </div>
                    <div class="contact-info">
                        <h3>contact infomation</h3>
                        <div class="info">
                            <div class="row">
                                <div class="field-part">
                                    <label>Email <sup>*</sup></label>
                                    <input type="email" name="email" value="{{$customerData['customers']->email}}" disabled>
                                </div>
                                <div class="blank"></div>
                            </div>
                            <div class="row">
                                <div class="field-part">
                                    <label>Mobile Number <sup>*</sup></label>
                                    <div class="change-num">
                                        <input class="form_phone" type="text" name="phone" value="{{$customerData['customers']->phone}}" required disabled>
                                        <a href="#ChangeNumberModal" class="change_request" rel="modal:open">Change Number</a>
                                    </div>
                                </div>
                                <div class="blank"></div>
                            </div>
                        </div>
                    </div>
                    <div class="location-info">
                    <h3>location infomation</h3>
                        <div class="info">
                            <div class="row">
                                <label>State</label>
                                <select name="state_id" required>
                                    <option value="">Please select your state</option>
                                    @foreach ($states as $state)
                                    @php
                                        if($state->state_id == $customerData['customers']->state_id){
                                            $selected = 'selected';
                                        }else{
                                            $selected = '';
                                        }
                                    @endphp
                                        <option {{$selected}} value="{{$state->state_id}}">{{$state->state_name}}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="row">
                                <label>Zip</label>
                                <input type="text" name="entry_postcode" value="{{!empty($customerData['customers_address']) ? $customerData['customers_address']->entry_postcode : '' }}" required>
                            </div>
                        </div>
                    </div>
                <div class="profile-update">
                   <input type="submit" value="Update Profile">
                   <!-- <a href="#">update profile</a> -->
                </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="crop-popup">
<div class="modal fade change-password" id="PicModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header text-center">
                Upload Image
            </div>
            <div class="modal-body">
                <form action="{!! route('photo_upload') !!}" method="post" enctype="multipart/form-data" >
                    @csrf
                    <img id="canvasToThumb" class='thumbnail-img' data-src="" />
                    <input type="hidden" id="photo"  name="photo" />
                    <input type="hidden" id="x"  name="coord_x" />
                    <input type="hidden" id="y"  name="coord_y" />
                    <input type="hidden" id="w"  name="size_w"/>
                    <input type="hidden" id="h"  name="size_h"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" aria-hidden="true"   class="btn btn-primary"  id="crop">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>


<div class="modal fade change-password" id="ChangePasswordModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header text-center">
                Change Password
            </div>
            <div class="modal-body">
                <form class="modal-form" action="{!! route('change_password') !!}" method="post" >
                    @csrf
                    <div class="row">
                        <label>Old Password <sup>*</sup></label>
                        <div class="change-num">
                            <input type="password" name="old_password" placeholder="*****************">
                        </div>
                    </div>
                    <div class="row">
                        <label>New Password <sup>*</sup></label>
                        <div class="change-num">
                            <input type="password" name="password" placeholder="*****************">
                        </div>
                    </div>
                    <div class="row">
                        <label>Confirm Password <sup>*</sup></label>
                        <div class="change-num">
                            <input type="password" name="password_confirmation" placeholder="*****************">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#close" rel="modal:close" class="btn btn-primary">Cancel</a>
                <button type="button" data-dismiss="modal" class="btn btn-primary btn-update">Submit</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade change-password" id="ChangeNumberModal">
<div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header text-center">
                Change Password
            </div>
            <div class="modal-body">
                <form class="modal-form" action="{!! route('change_number') !!}" method="post">
                    @csrf
                    <div class="row">
                        <label>Current Phone number <sup>*</sup></label>
                        <div class="change-num">
                            <input type="test" name="old_phone" placeholder="+6112345647890">
                        </div>
                    </div>
                    <div class="row">
                        <label>New Phone Number <sup>*</sup></label>
                        <div class="change-num">
                            <input type="test" name="phone" placeholder="+6112345647890">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#close" rel="modal:close" class="btn btn-primary">Cancel</a>
                <button type="button" data-dismiss="modal" class="btn btn-primary btn-update">Submit</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection
@section('scripts')
<script src="{!! asset('admin/js/plupload.full.min.js') !!}"></script>
<script src="{!! asset('admin/js/cropper.js') !!}"></script>
<script>
    var upload_url = "{{ asset('plupload/upload.php') }}";
    var tmp_url = "{!! asset('/tmp/') !!}";
    var swf_url = "{{ asset('plupload/Moxie.swf') }}";
    var xap_url = "{{asset('plupload/Moxie.xap')}}";
    jQuery(function(){
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'LogoSelection',
            container: document.getElementById('PicSection'),
            url : upload_url,
            multi_selection:false,
            filters : {
                max_file_size : '3mb',
                mime_types: [
                    {title : "Image files", extensions : "jpg,png,jpeg"},
                ]
            },
            flash_swf_url : swf_url,
            silverlight_xap_url : xap_url,
            init: {
                PostInit: function() {
                },
                FilesAdded: function(up, files) {
                    files[0].name = Math.random().toString(36).substring(7)+files[0].name;
                    files[0].name = files[0].name.trim();
                    uploader.start();
                    jQuery('.loader').fadeToggle('medium');
                },
                FileUploaded: function(up,file)
                {

                    $("#PicModal .thumbnail-img").attr('src','/tmp/' + file.name);
                    $("#PicModal #photo").attr('value',file.name);
                    $("#PicModal").modal();
                    crop_init();
                    $("#loading").hide();
                },
                Error: function(up, err) {
                    alert(err.message);
                }
            }
        });

        plupload.addFileFilter('max_img_resolution', function(maxRes, file, cb) {
            $('#console').html('');
            var self = this, img = new o.Image();

            function finalize(result) {
            // cleanup
            img.destroy();
            img = null;

            // if rule has been violated in one way or another, trigger an error
            if (!result) {
                self.trigger('Error', {
                code : plupload.IMAGE_DIMENSIONS_ERROR,
                message : "Please upload image with resolution 261 X 280 pixels or above",
                file : file
                });

            }
            cb(result);
            }

            img.onload = function() {
            // check if resolution cap is not exceeded
            finalize(img.width * img.height >= maxRes);

            };

            img.onerror = function() {
            finalize(false);

            };

            img.load(file.getSource());
        });

        uploader.init();

        var image = document.getElementById('canvasToThumb');
        var cropper;
        function crop_init(){
            var jcrop_api, boundx, boundy;
            function showCoords(c) { // show all coords
                $('#x').val(c.x);
                $('#y').val(c.y);
                $('#w').val(c.w);
                $('#h').val(c.h);
            }

            // cropper start

            var options = {
                aspectRatio: 1/1,
                center: true,
                highlight: true,
                background: true,
                cropBoxResizable: true,
                modal:true,
                dragMode:'none',
                viewMode:1,
                zoomOnWheel: false,
                minContainerWidth:200,
                minContainerHeight:200,

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
            $('#crop').click(function(e) {
                $('#loading').show();
                var croppedImageDataURL = cropper.getCroppedCanvas().toDataURL("image/png");
                jQuery('#previewDivLawyer img').attr('src',croppedImageDataURL).css({'width':'294px','height':'294px'});
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
                                jQuery('#user_avatar').attr('src',$post.photo);
                                $.modal.close();
                                cropper.destroy();
                                window.location.reload();
                            },
                            error: function () {
                                $('#loading').hide();
                                alert("Something went wrong, Please try after sometime.");
                            }
                            });
                });
                return false;
            });
        }

    var users = {
        init: function() {
            jQuery(document).on('click','.btn-update',function(e){
                //jQuery('.pre-loader').show();
                var _this = jQuery(this);
                e.preventDefault();
                users.fire(_this,"save");
            });
        },
        fire: function(_this,action) {
            var _f = _this.parents('.modal').find(".modal-form");
            console.log(_f);
            var method = action == "save" ? _f.attr('method') : _this.data('method');
            var url = action == "save" ? _f.attr('action') : _this.data('url');
            var data = action == "delete" ? {_token: jQuery('meta[name="_token"]').attr('content')} : _f.serialize();
            var ajax = {
                fire: function(){
                    $.ajax({
                        type: method,
                        url: url,
                        data: data,      
                    })
                    .done(function(response) {
                        ajax.success(response)
                    })
                    .fail(function(response) {
                        ajax.error(response)
                    });
                },
                success: function(success){
                    //jQuery('.pre-loader').hide();
                    console.log(success);
                    $.each(success, function (key, value) {
                        if(key == "phone")
                        {
                            jQuery(".form_phone").val(value);
                        }
                        else if(key == 'password')
                        {
                            jQuery(".form_password").val(value);
                        }
                    });
                    $.modal.close();
                    
                },
                error: function(error){
                    jQuery('.pre-loader').hide();
                    jQuery(_f).find(".has-error").remove();
                    var response = JSON.parse(error.responseText);
                    $.each(error.responseJSON, function (key, value) {
                        if(key == 'agree_terms')
                        {
                            jQuery('.confirm-read-tc').find('.has-error').remove();
                            jQuery('.confirm-read-tc').append("<span class='has-error'>"+ value +"</span>");
                        }
                        else
                        {
                            var input = '[name=' + key + ']';
                            jQuery(_f).find(input).parent().find(".has-error").length == 0 ? jQuery(_f).find(input).parent().append("<span class='has-error'>"+ value +"</span>") : jQuery(_f).find(input).parent().find('.has-error').html(value);
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
