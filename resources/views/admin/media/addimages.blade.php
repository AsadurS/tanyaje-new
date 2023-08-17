@extends('admin.layout')
@section('styles')
<link rel="stylesheet" href="{!! asset('/admin/css/cropper.css') !!}">

@endsection
@section('content')
<style>
 .box-header-inner {
    display: flex;
    align-items: flex-start;
    justify-content: flex-end;
}
.box-header-inner > div {
    margin-left: 5px;
}
.box-header h3{position: absolute;top:20px;}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> {{ trans('labels.AddNewImage') }} <small>{{ trans('labels.ListingAllImage') }}...</small> </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i>
                    {{ trans('labels.breadcrumb_dashboard') }}</a></li>
            <li class="active">{{ trans('labels.AddNewImage') }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->

        <!-- /.row -->
        <!-- /.row -->

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans('labels.ListingAllImage') }} </h3>
                        <div class="box-header-inner">
                        <div id="LogoSection">
                            <a href="javascript:;" id="LogoSelection" class="btn btn-primary">{{ trans('labels.AddLogo') }}</a>
                        </div>
                        <div id="BannerSection">
                            <a href="javascript:;" id="BannerSelection" class="btn btn-primary">{{ trans('labels.AddBanner') }}</a>
                        </div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#myModal">{{ trans('labels.AddNew') }}</button>
                        </div>
                    </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">
                                @if (count($errors) > 0)
                                @if($errors->any())
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                    {{$errors->first()}}
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            @if(isset($images))
                            @foreach($images as $image)
                            <div class="col-xs-4 col-md-2 margin-bottomset">
                                <div class="thumbnail">
                                    <div class="caption">
                                        <p><a href="{{url('admin/media/deleteimage')}}/{{$image->id}}"
                                                class="label label-danger">Delete</a></p>
                                    </div>
                                    <img src="{{asset($image->path)}}" alt="...">
                                </div>
                            </div>
                            @endforeach
                            @endif

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
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add File Here</h4>
                    </div>
                    <div class="modal-body">
                        <p>Click or Drop Images in the Box for Upload.</p>
                        <form action="{{ url('admin/media/uploadimage') }}" enctype="multipart/form-data"
                            class="dropzone " id="my-dropzone">
                            {{ csrf_field() }}
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" disabled="disabled" id="compelete"
                            data-dismiss="modal">Done</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal -->
        <div class="modal fade" id="myModaldetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                        <h3 class="modal-title text-primary" id="myModalLabel">Image Details</h3>
                    </div>

                    {!! Form::open(array('url' =>'admin/deleteimage', 'method'=>'post', 'class' => 'form-horizontal',
                    'enctype'=>'multipart/form-data', 'onsubmit' => 'return ConfirmDelete()')) !!}
                    <div class="image_embed">

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="myDeleteImage"
                            data-toggle="modal">Delete</button>
                        {{--<a href="#myModal2" role="button" type="submit" class="btn btn-danger" data-toggle="modal">Delete</a>--}}
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>


                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div id="myModal2" class="modal modal-child" data-backdrop-limit="1" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel" aria-hidden="true" data-modal-parent="#myModal">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmation!!</h4>
                    </div>
                    <div class="modal-body">
                        <p>You are sure to delete It!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="myDeleteImage"
                            data-toggle="modal">Delete</button>
                        <button class="btn btn-default" data-dismiss="modal" data-dismiss="modal"
                            aria-hidden="true">Cancel</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="LogoModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">
                    <div class="modal-header text-center">
                        Upload Image
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="upload_form" name="upload_form" name='_token' action="#" method="post" enctype="multipart/form-data" >
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

        <div class="modal fade" id="BannerModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">
                    <div class="modal-header text-center">
                        Upload Image
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="upload_form" name="upload_form" name='_token' action="#" method="post" enctype="multipart/form-data" >
                            @csrf
                            <img id="canvasToThumbBanner" class='thumbnail-img' data-src="" />
                            <input type="hidden" id="photo"  name="photo" />
                            <input type="hidden" id="x"  name="coord_x" />
                            <input type="hidden" id="y"  name="coord_y" />
                            <input type="hidden" id="w"  name="size_w"/>
                            <input type="hidden" id="h"  name="size_h"/>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" aria-hidden="true"   class="btn btn-primary"  id="cropbanner">Save</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
<script src="{!! asset('admin/js/plupload.full.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('admin/js/cropper.js') !!}"></script>
<script>
    var upload_url = "{{ asset('plupload/upload.php') }}";
    var tmp_url = "{!! asset('/tmp/') !!}";
    var swf_url = "{{ asset('plupload/Moxie.swf') }}";
    var xap_url = "{{asset('plupload/Moxie.xap')}}";
jQuery(function(){

            uploader = new plupload.Uploader({
		        runtimes : 'html5,flash,silverlight,html4',
		        browse_button : 'LogoSelection',
		        container: document.getElementById('LogoSection'),
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

		             $("#LogoModal .thumbnail-img").attr('src','/tmp/' + file.name);
		             $("#LogoModal #photo").attr('value',file.name);
		             $("#LogoModal").modal('show');
		             $("#loading").hide();
		            },
		            UploadProgress: function(up, file) {
					    $('#lawyerProgress').fadeIn();
					    $('#lawyerBar').css('width', file.percent + '%');
					  },
		            UploadComplete: function(up, files){
		                $('#lawyerProgress').fadeOut();
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
		    $('#LogoModal').on('shown.bs.modal', function (event){
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
		                      formData.append('photo', $("#LogoModal #photo").val());
                              formData.append('_token', $('#LogoModal input[name="_token"]').val());
		                      // Use `jQuery.ajax` method
		                      $.ajax('/admin/media/uploadimage', {
		                        method: "POST",
		                        data: formData,
		                        processData: false,
		                        contentType: false,
		                        success: function ($post) {
		                          $('#loading').hide();
		                          jQuery('#profile_pic').val($post.photo);
		                          $('#LogoModal').modal('hide');
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
		        }).on('hide.bs.modal', function (event){
		           cropper.destroy();
		        });




                var uploader1 = new plupload.Uploader({
		        runtimes : 'html5,flash,silverlight,html4',
		        browse_button : 'BannerSelection',
		        container: document.getElementById('BannerSection'),
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
		                uploader1.start();
		                jQuery('.loader').fadeToggle('medium');
		            },
		            FileUploaded: function(up,file)
		            {
		             $("#BannerModal .thumbnail-img").attr('src','/tmp/' + file.name);
		             $("#BannerModal #photok").attr('value',file.name);

		             $("#BannerModal").modal('show');
		             $("#loading").hide();
		            },
		            UploadProgress: function(up, file) {
					      $('#lawyerProgress').fadeIn();
					      $('#lawyerBar').css('width', file.percent + '%');
					  },
		            UploadComplete: function(up, files){
		                $('#lawyerProgress').fadeOut();
		            },
		            Error: function(up, err) {
		            	console.log(err);
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
		    uploader1.init();



        var image_edit_lawyer = document.getElementById('canvasToThumbBanner');
	    var cropper2;
	    $('#BannerModal').on('shown.bs.modal', function (event){
	        var jcrop_api, boundx, boundy;
	        function showCoords(c) { // show all coords
	          $('#x').val(c.x);
	          $('#y').val(c.y);
	          $('#w').val(c.w);
	          $('#h').val(c.h);
	        }

	        // cropper start

	        var options_edit_lawyer = {
	            aspectRatio: 16/4,
	            center: true,
	            highlight: true,
	            background: true,
	            cropBoxResizable: true,
	            modal:true,
	            dragMode:'none',
	            viewMode:1,
	            zoomOnWheel: false,
	            minContainerWidth:1150,
	            minContainerHeight:250,

	            ready: function (e) {
	                console.log(e.type);
	            },
	            crop: function (e) {
	              var data = e.detail;
	              console.log(e.type);
	              $('#x').val(Math.round(data.x));
	              $('#y').val(Math.round(data.y));
	              $('#w').val(Math.round(data.height));
	              $('#h').val(Math.round(data.width));
	            },
	            zoom: function (e) {
	                console.log(e.type, e.detail.ratio);
	                }
	        };
	        cropper2 = new Cropper(image_edit_lawyer, options_edit_lawyer);
	        // cropper end
	        $('#cropbanner').click(function(e) {
	            $('#loading').show();
	            var croppedImageDataURL = cropper2.getCroppedCanvas().toDataURL("image/png");
	            jQuery('#previewDivLawyer img').attr('src',croppedImageDataURL).css({'width':'294px','height':'294px'});
	            cropper2.getCroppedCanvas().toBlob(function (blob) {
	                      var formData = new FormData();
	                      formData.append('croppedImage', blob);
	                      formData.append('photo', $("#BannerModal #photok").val());
                          formData.append('_token', $('#BannerModal input[name="_token"]').val());

	                      // Use `jQuery.ajax` method
	                      $.ajax('/admin/media/uploadimage', {
	                        method: "POST",
	                        data: formData,
	                        processData: false,
	                        contentType: false,
	                        success: function ($post) {
	                          $('#loading').hide();
	                          jQuery('#profile_edit_pic').val($post.photo);
	                          $('#BannerModal').modal('hide');
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
	        }).on('hide.bs.modal', function (event){
	           cropper2.destroy();
	        });
});
</script>
@endsection
