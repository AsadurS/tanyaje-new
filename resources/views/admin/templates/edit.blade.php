@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.edittemplates') }} <small>{{ trans('labels.edittemplates') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li><a href="{{ URL::to('admin/templates')}}"><i class="fa fa-users"></i> {{ trans('labels.templates') }}</a></li>
      <li class="active">{{ trans('labels.edittemplates') }}</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Info boxes -->

    <!-- /.row -->

    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">{{ trans('labels.edittemplates') }} </h3>
          </div>

          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
              		<div class="box box-info">
                        <br>

                        @if(session()->has('message'))
                            <div class="alert alert-success" role="alert">
						  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                {{ session()->get('message') }}
                            </div>
                        @endif

                        @if(session()->has('errorMessage'))
                            <div class="alert alert-danger" role="alert">
						  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                {{ session()->get('errorMessage') }}
                            </div>
                        @endif

                        <!-- form start -->
                         <div class="box-body">
                            {!! Form::open(array('url' =>'admin/updateTemplate', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                            {!! Form::hidden('myid', $result['myid'], array('id'=>'myid')) !!}
                            <h4>{{ trans('labels.TemplateInfo') }} </h4>
                            <hr>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.TemplateName') }} </label>
                                <div class="col-sm-10 col-md-3">
                                    {!! Form::text('name',  $result['admins'][0]->name, array('class'=>'form-control field-validate', 'id'=>'name')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.TemplateNameText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                                <label for="created_at" class="col-sm-2 col-md-1 control-label">{{ trans('labels.CreatedDateTime') }} </label>
                                <div class="col-sm-10 col-md-3">
                                    <input class="form-control" type="text" value="{{  $result['admins'][0]->created_at }}" style="cursor:default;" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_modified" class="col-sm-2 col-md-3 control-label">{{ trans('labels.LastModified') }} By</label>
                                <div class="col-sm-10 col-md-3">
                                    <input class="form-control" type="text" value="{{  $result['last_edit_by'] ?? 'N/A' }}" style="cursor:default;" readonly>
                                </div>
                                <label for="updated_at" class="col-sm-2 col-md-1 control-label">{{ trans('labels.ModifiedDateTime') }} </label>
                                <div class="col-sm-10 col-md-3">
                                    <input class="form-control" type="text" value="{{ $result['admins'][0]->updated_at ?? 'N/A' }}" style="cursor:default;" readonly>
                                </div>
                            </div>
                            <!-- preview image -->
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.PreviewImage') }} </label>
                                <div class="col-sm-10 col-md-7">
                                    <!-- <input type="hidden" name="old_preview_image" value="{{$result['admins'][0]->preview_image}}">
                                    <input type="file" name="preview_image" id="preview_image" class="form-control"><br>
                                    <img src="{{asset('/images/template/'.$result['admins'][0]->preview_image)}}" width="200px" alt=""> -->
                                  <span style="display:flex;">
                                    <input type="text" id="preview_image" class="form-control" name="preview_image" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->preview_image }}">
                                    <button class="btn btn-outline-secondary" type="button" id="button-image-preview">Select</button>
                                  </span>
                                  @if($result['admins'][0]->preview_image)
                                    <br>
                                    @if (file_exists(public_path('/images/template/'.$result['admins'][0]->preview_image)))
                                      <img src="{{asset('/images/template/'.$result['admins'][0]->preview_image)}}" width="200px" alt="">
                                    @else
                                      <img src="{{ $result['admins'][0]->preview_image }}" width="200px" alt="">
                                    @endif 
                                  @endif
                                </div>
                            </div>
                            <!-- main template code -->
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.MainTemplateCode') }} </label>
                                <div class="col-sm-10 col-md-7">
                                    <textarea name="template_code" id="template_code" class="template_code form-control form-control-solid">{!! $result['admins'][0]->template_code !!}</textarea>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.TemplateCodeText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.LiteMainTemplateCode') }} </label>
                                <div class="col-sm-10 col-md-7">
                                    <textarea name="lite_template_code" id="lite_template_code" class="lite_template_code form-control form-control-solid">{!! $result['admins'][0]->lite_template_code !!}</textarea>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.TemplateCodeText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                            </div>
                            <!-- askme template code -->
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.AskMeTemplate') }} </label>
                                <div class="col-sm-10 col-md-7">
                                    <textarea name="askme_code" id="askme_code" class="askme_code form-control form-control-solid" >{!! $result['admins'][0]->askme_code !!}</textarea>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.TemplateCodeText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                            </div>
                            <!-- showme template code -->
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.ShowMeTemplate') }} </label>
                                <div class="col-sm-10 col-md-7">
                                    <textarea name="showme_code" id="showme_code" class="showme_code form-control form-control-solid">{!! $result['admins'][0]->showme_code !!}</textarea>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.TemplateCodeText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                            </div>
                            <!-- showme template code -->
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.KeepMeTemplate') }} </label>
                                <div class="col-sm-10 col-md-7">
                                    <textarea name="keepme_code" id="keepme_code" class="keepme_code form-control form-control-solid">{!! $result['admins'][0]->keepme_code !!}</textarea>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.TemplateCodeText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                            </div>

                            <!-- css template -->
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.CssCode') }} </label>
                                <div class="col-sm-10 col-md-7">
                                    <textarea name="css_code" id="css_code" class="css_code form-control form-control-solid">{!! $result['admins'][0]->css_code !!}</textarea>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.TemplateCodeText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                            </div>

                            <!-- header template -->
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.HeaderTemplate') }} </label>
                                <div class="col-sm-10 col-md-7">
                                    <textarea name="header_code" id="header_code" class="header_code form-control form-control-solid ">{!! $result['admins'][0]->header_code !!}</textarea>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.TemplateCodeText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                            </div>

                            <!-- footer template -->
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.FooterTemplate') }} </label>
                                <div class="col-sm-10 col-md-7">
                                    <textarea name="footer_code" id="footer_code" class="footer_code form-control form-control-solid">{!! $result['admins'][0]->footer_code !!}</textarea>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.TemplateCodeText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                            </div>

                            <!-- footer show company with.. -->
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.FooterShowCompanyWith') }} </label>
                                <div class="col-sm-10 col-md-7">
                                    <label class=" control-label">
                                        <input type="radio" name="show_company_with" value="name" class="flat-red" @if($result['admins'][0]->show_company_with == 'name') checked @endif> &nbsp;Name
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                    <label class=" control-label">
                                        <input type="radio" name="show_company_with" value="logo" class="flat-red" @if($result['admins'][0]->show_company_with == 'logo') checked @endif>  &nbsp;Logo
                                    </label>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.FooterShowCompanyWithText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                            </div>

                            <!-- standard/lite landing page background -->
                            <div class="form-group">
                              <!-- standard bg -->
                              <label for="StandardLandingPageBackground" class="col-sm-2 col-md-3 control-label">{{ trans('labels.StandardLandingPageBackground') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <!-- <input type="hidden" name="old_standard_lp_bg" value="{{$result['admins'][0]->standard_lp_bg}}">
                                <input type="file" name="standard_lp_bg" id="standard_lp_bg" class="form-control">
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.StandardLandingPageBackgroundText') }}</span><br>
                                <img src="{{asset('/images/template/'.$result['admins'][0]->standard_lp_bg)}}" width="200px" alt="">
                                 -->
                                <span style="display:flex;">
                                  <input type="text" id="standard_lp_bg" class="form-control" name="standard_lp_bg" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->standard_lp_bg }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-standard">Select</button>
                                </span>
                                @if($result['admins'][0]->standard_lp_bg)
                                  <br>
                                  @if (file_exists(public_path('/images/template/'.$result['admins'][0]->standard_lp_bg)))
                                    <img src="{{asset('/images/template/'.$result['admins'][0]->standard_lp_bg)}}" width="100px" alt="">
                                  @else
                                    <img src="{{ $result['admins'][0]->standard_lp_bg }}" width="100px" alt=""> 
                                  @endif 
                                @endif
                              </div>
                              <!-- lite bg -->
                              <label for="LiteLandingPageBackground" class="col-sm-2 col-md-1 control-label">{{ trans('labels.LiteLandingPageBackground') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <!-- <input type="hidden" name="old_lite_lp_bg" value="{{$result['admins'][0]->lite_lp_bg}}">
                                <input type="file" name="lite_lp_bg" id="lite_lp_bg" class="form-control">
                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.LiteLandingPageBackgroundText') }}</span><br>
                                <img src="{{asset('/images/template/'.$result['admins'][0]->lite_lp_bg)}}" width="200px" alt=""> -->

                                <span style="display:flex;">
                                  <input type="text" id="lite_lp_bg" class="form-control" name="lite_lp_bg" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->lite_lp_bg }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-lite">Select</button>
                                </span>
                                @if($result['admins'][0]->lite_lp_bg)
                                  <br>
                                  @if (file_exists(public_path('/images/template/'.$result['admins'][0]->lite_lp_bg)))
                                    <img src="{{asset('/images/template/'.$result['admins'][0]->lite_lp_bg)}}" width="100px" alt="">
                                  @else
                                    <img src="{{ $result['admins'][0]->lite_lp_bg }}" width="100px" alt=""> 
                                  @endif 
                                @endif
                              </div>
                            </div>

                            <!-- icon start --------------------------------------------------------------------------------------------- -->
                            <div class="form-group">
                              <!-- Call Icon -->
                              <label for="TemplateCallIcon" class="col-sm-2 col-md-3 control-label">{{ trans('labels.TemplateCallIcon') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="call_icon" class="form-control" name="call_icon" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->call_icon }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image">Select</button>
                                </span>
                                @if($result['admins'][0]->call_icon)
                                  <br>
                                  @if (file_exists(public_path('/images/template/'.$result['admins'][0]->call_icon)))
                                    <img src="{{asset('/images/template/'.$result['admins'][0]->call_icon)}}" width="100px" alt="">
                                  @else
                                    <img src="{{ $result['admins'][0]->call_icon }}" width="100px" alt=""> 
                                  @endif 
                                @endif
                              </div>
                              <!-- Email Icon -->
                              <label for="TemplateEmailIcon" class="col-sm-2 col-md-1 control-label">{{ trans('labels.TemplateEmailIcon') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="email_icon" class="form-control" name="email_icon" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->email_icon }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-email">Select</button>
                                </span>
                                @if($result['admins'][0]->email_icon)
                                  <br>
                                  @if (file_exists(public_path('/images/template/'.$result['admins'][0]->email_icon)))
                                    <img src="{{asset('/images/template/'.$result['admins'][0]->email_icon)}}" width="100px" alt="">
                                  @else
                                    <img src="{{ $result['admins'][0]->email_icon }}" width="100px" alt=""> 
                                  @endif 
                                @endif
                              </div>
                            </div>
                            
                            <div class="form-group">
                              <!-- Whatsapp Icon -->
                              <label for="TemplateWhatsappIcon" class="col-sm-2 col-md-3 control-label">{{ trans('labels.TemplateWhatsappIcon') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="whatsapp_icon" class="form-control" name="whatsapp_icon" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->whatsapp_icon }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-whatsapp">Select</button>
                                </span>
                                @if($result['admins'][0]->whatsapp_icon)
                                  <br>
                                  @if (file_exists(public_path('/images/template/'.$result['admins'][0]->whatsapp_icon)))
                                    <img src="{{asset('/images/template/'.$result['admins'][0]->whatsapp_icon)}}" width="100px" alt="">
                                  @else
                                    <img src="{{ $result['admins'][0]->whatsapp_icon }}" width="100px" alt=""> 
                                  @endif 
                                @endif
                              </div>
                              <!-- Direction Icon -->
                              <label for="TemplateDirectionIcon" class="col-sm-2 col-md-1 control-label">{{ trans('labels.TemplateDirectionIcon') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="direction_icon" class="form-control" name="direction_icon" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->direction_icon }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-direction">Select</button>
                                </span>
                                @if($result['admins'][0]->direction_icon)
                                  <br>
                                  @if (file_exists(public_path('/images/template/'.$result['admins'][0]->direction_icon)))
                                    <img src="{{asset('/images/template/'.$result['admins'][0]->direction_icon)}}" width="100px" alt="">
                                  @else
                                    <img src="{{ $result['admins'][0]->direction_icon }}" width="100px" alt=""> 
                                  @endif 
                                @endif
                              </div>
                            </div>

                            <div class="form-group">
                              <!-- 360 Icon -->
                              <label for="Template360Icon" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Template360Icon') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="A360_icon" class="form-control" name="A360_icon" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->A360_icon }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-a360">Select</button>
                                </span>
                                @if($result['admins'][0]->A360_icon)
                                  <br>
                                  @if (file_exists(public_path('/images/template/'.$result['admins'][0]->A360_icon)))
                                    <img src="{{asset('/images/template/'.$result['admins'][0]->A360_icon)}}" width="100px" alt="">
                                  @else
                                    <img src="{{ $result['admins'][0]->A360_icon }}" width="100px" alt=""> 
                                  @endif 
                                @endif
                              </div>
                              <!-- 360 Title -->
                              <label for="Template360Title" class="col-sm-2 col-md-1 control-label">{{ trans('labels.Template360Title') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="a360_title" class="form-control" name="a360_title" value="{{$result['admins'][0]->a360_title}}">
                                </span>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="Template360Icon" class="col-sm-2 col-md-3 control-label"></label>
                              <div class="col-sm-10 col-md-3">
                              </div>
                              <!-- 360 Redirect Url -->
                              <label for="Template360Title" class="col-sm-2 col-md-1 control-label">{{ trans('labels.RedirectUrl') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="a360_redirect_url" class="form-control" name="a360_redirect_url" value="{{$result['admins'][0]->a360_redirect_url}}">
                                </span>
                              </div>
                            </div>

                            <div class="form-group">
                              <!-- Askme Icon -->
                              <label for="TemplateAskMeIcon" class="col-sm-2 col-md-3 control-label">{{ trans('labels.TemplateAskMeIcon') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="askme_icon" class="form-control" name="askme_icon" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->askme_icon }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-askme">Select</button>
                                </span>
                                @if($result['admins'][0]->askme_icon)
                                  <br>
                                  @if (file_exists(public_path('/images/template/'.$result['admins'][0]->askme_icon)))
                                    <img src="{{asset('/images/template/'.$result['admins'][0]->askme_icon)}}" width="100px" alt="">
                                  @else
                                    <img src="{{ $result['admins'][0]->askme_icon }}" width="100px" alt=""> 
                                  @endif 
                                @endif
                              </div>
                              <!-- Askme Title -->
                              <label for="TemplateAskMeTitle" class="col-sm-2 col-md-1 control-label">{{ trans('labels.TemplateAskMeTitle') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="askme_title" class="form-control" name="askme_title" value="{{$result['admins'][0]->askme_title}}">
                                </span>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="Template360Icon" class="col-sm-2 col-md-3 control-label"></label>
                              <div class="col-sm-10 col-md-3">
                              </div>
                              <!-- Askme Redirect Url -->
                              <label for="Template360Title" class="col-sm-2 col-md-1 control-label">{{ trans('labels.RedirectUrl') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="askme_redirect_url" class="form-control" name="askme_redirect_url" value="{{$result['admins'][0]->askme_redirect_url}}">
                                </span>
                              </div>
                            </div>

                            <div class="form-group">
                              <!-- Promotion Icon -->
                              <label for="TemplatePromotionIcon" class="col-sm-2 col-md-3 control-label">{{ trans('labels.TemplatePromotionIcon') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="promotion_icon" class="form-control" name="promotion_icon" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{ $result['admins'][0]->promotion_icon }}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-promotion">Select</button>
                                </span>
                                @if($result['admins'][0]->promotion_icon)
                                  <br>
                                  @if (file_exists(public_path('/images/template/'.$result['admins'][0]->promotion_icon)))
                                    <img src="{{asset('/images/template/'.$result['admins'][0]->promotion_icon)}}" width="100px" alt="">
                                  @else
                                    <img src="{{ $result['admins'][0]->promotion_icon }}" width="100px" alt=""> 
                                  @endif 
                                @endif
                              </div>
                              <!-- Promotion Title -->
                              <label for="TemplatePromotionTitle" class="col-sm-2 col-md-1 control-label">{{ trans('labels.TemplatePromotionTitle') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="promotion_title" class="form-control" name="promotion_title" value="{{$result['admins'][0]->promotion_title}}">
                                </span>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="Template360Icon" class="col-sm-2 col-md-3 control-label"></label>
                              <div class="col-sm-10 col-md-3">
                              </div>
                              <!-- Promotion Redirect Url -->
                              <label for="Template360Title" class="col-sm-2 col-md-1 control-label">{{ trans('labels.RedirectUrl') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="promotion_redirect_url" class="form-control" name="promotion_redirect_url" value="{{$result['admins'][0]->promotion_redirect_url}}">
                                </span>
                              </div>
                            </div>

                            <!-- campaign icon -->
                            <div class="form-group">
                              <!-- campaign Icon -->
                              <label for="TemplatePromotionIcon" class="col-sm-2 col-md-3 control-label">{{ trans('labels.TemplateCampaignIcon') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="campaign_icon" class="form-control" name="campaign_icon" readonly="readonly" aria-label="Image" aria-describedby="button-image" value="{{$result['admins'][0]->campaign_icon}}">
                                  <button class="btn btn-outline-secondary" type="button" id="button-image-campaign">Select</button>
                                </span>
                                @if($result['admins'][0]->campaign_icon)
                                  <br>
                                  @if (file_exists(public_path('/images/template/'.$result['admins'][0]->campaign_icon)))
                                    <img src="{{asset('/images/template/'.$result['admins'][0]->campaign_icon)}}" width="100px" alt="">
                                  @else
                                    <img src="{{ $result['admins'][0]->campaign_icon }}" width="100px" alt=""> 
                                  @endif 
                                @endif
                              </div>
                              <!-- campaign Title -->
                              <label for="TemplatePromotionTitle" class="col-sm-2 col-md-1 control-label">{{ trans('labels.TemplateCampaignTitle') }}</label>
                              <div class="col-sm-10 col-md-3">
                                <span style="display:flex;">
                                  <input type="text" id="campaign_title" class="form-control" name="campaign_title" value="{{$result['admins'][0]->campaign_title}}">
                                </span>
                              </div>
                            </div>

                            <!-- Icon end ---------------------------------------------------------------------------------------------------- -->

                            <h4>{{ trans('labels.Colours') }} </h4>
                            <hr>
                            <div class="form-group">
                                <label for="Colours1" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.Colours1') }}</label>
                                <div class="col-sm-10 col-md-3">
                                  <input type="color" id="colorpicker" name="color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{ $result['admins'][0]->colour1 ?? '#0400ff' }}" class="form-control">
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.Colours1Text') }}</span>
                                </div>
                                <div class="col-sm-2 col-md-3">
                                  <input type="text" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{ $result['admins'][0]->colour1 ?? '#0400ff' }}" id="hexcolor" class="form-control"></input>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Colours2" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.Colours2') }}</label>
                                <div class="col-sm-10 col-md-3">
                                  <input type="color" id="colorpicker2" name="color2" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{ $result['admins'][0]->colour2 ?? '#fff700' }}" class="form-control">
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.Colours2Text') }}</span>
                                </div>
                                <div class="col-sm-2 col-md-3">
                                  <input type="text" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{ $result['admins'][0]->colour2 ?? '#fff700' }}" id="hexcolor2" class="form-control"></input>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Colours3" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.Colours3') }}</label>
                                <div class="col-sm-10 col-md-3">
                                  <input type="color" id="colorpicker3" name="color3" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{ $result['admins'][0]->colour3 ?? '#0400ff' }}" class="form-control">
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.Colours3Text') }}</span>
                                </div>
                                <div class="col-sm-2 col-md-3">
                                  <input type="text" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{ $result['admins'][0]->colour3 ?? '#0400ff' }}" id="hexcolor3" class="form-control"></input>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Colours4" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.Colours4') }}</label>
                                <div class="col-sm-10 col-md-3">
                                  <input type="color" id="colorpicker4" name="color4" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{ $result['admins'][0]->colour4 ?? '#0400ff' }}" class="form-control">
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.Colours4Text') }}</span>
                                </div>
                                <div class="col-sm-2 col-md-3">
                                  <input type="text" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{ $result['admins'][0]->colour4 ?? '#0400ff' }}" id="hexcolor4" class="form-control"></input>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Colours5" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.Colours5') }}</label>
                                <div class="col-sm-10 col-md-3">
                                  <input type="color" id="colorpicker5" name="color5" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{ $result['admins'][0]->colour5 ?? '#0400ff' }}" class="form-control">
                                </div>
                                <div class="col-sm-2 col-md-3">
                                  <input type="text" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="{{ $result['admins'][0]->colour5 ?? '#0400ff' }}" id="hexcolor5" class="form-control"></input>
                                </div>
                            </div>
                            
                            <!-- /.box-body -->
                            <div class="box-footer text-center">
                            <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>

                            @if(Auth()->user()->role_id != \App\Models\Core\User::ROLE_MERCHANT)
                            <a href="{{ URL::to('admin/templates')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
                            @endif
                            </div>
                              <!-- /.box-footer -->
                            {!! Form::close() !!}
                        </div>
                  </div>
              </div>
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

    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
<div style="height: 0px;">
    <div id="fm"></div>
</div>
<script>
    // CKEDITOR.replace( 'template_code', {});
    var editor = CKEDITOR.replace( 'template_code', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );
    var editor5 = CKEDITOR.replace( 'lite_template_code', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );
    var editor2 = CKEDITOR.replace( 'askme_code', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );
    var editor3 = CKEDITOR.replace( 'showme_code', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );
    var editor4 = CKEDITOR.replace( 'keepme_code', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );
    var editor7 = CKEDITOR.replace( 'header_code', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );
    var editor8 = CKEDITOR.replace( 'footer_code', {filebrowserImageBrowseUrl: '/file-manager/ckeditor', customConfig: '/js/ckeditor.config.js'} );

    // The "change" event is fired whenever a change is made in the editor.
    // editor.on( 'change', function( evt ) {
    //     var s = evt.editor.getData();
    //     $('#lblValue').html(s);
    // });

    // editor2.on( 'change', function( evt ) {
    //     var s2 = evt.editor.getData();
    //     $('#lblValue_askme').html(s2);
    // });

    // editor3.on( 'change', function( evt ) {
    //     var s3 = evt.editor.getData();
    //     $('#lblValue_showme').html(s3);
    // });

    // editor4.on( 'change', function( evt ) {
    //     var s4 = evt.editor.getData();
    //     $('#lblValue_keepme').html(s4);
    // });

    $(document).ready(function() {
      $('#colorpicker').on('input', function() {
        $('#hexcolor').val(this.value);
      });
      $('#hexcolor').on('input', function() {
        $('#colorpicker').val(this.value);
      });

      $('#colorpicker2').on('input', function() {
        $('#hexcolor2').val(this.value);
      });
      $('#hexcolor2').on('input', function() {
        $('#colorpicker2').val(this.value);
      });

      $('#colorpicker3').on('input', function() {
        $('#hexcolor3').val(this.value);
      });
      $('#hexcolor3').on('input', function() {
        $('#colorpicker3').val(this.value);
      });

      $('#colorpicker4').on('input', function() {
        $('#hexcolor4').val(this.value);
      });
      $('#hexcolor4').on('input', function() {
        $('#colorpicker4').val(this.value);
      });

      $('#colorpicker5').on('input', function() {
        $('#hexcolor5').val(this.value);
      });
      $('#hexcolor5').on('input', function() {
        $('#colorpicker5').val(this.value);
      });
    });
</script>
<!-- multiple standalone button -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // preview image
    document.getElementById('button-image-preview').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'preview_image';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // call icon
    document.getElementById('button-image').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'call_icon';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // email icon
    document.getElementById('button-image-email').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'email_icon';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // whatsapp icon
    document.getElementById('button-image-whatsapp').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'whatsapp_icon';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // direction icon
    document.getElementById('button-image-direction').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'direction_icon';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // a360 icon 
    document.getElementById('button-image-a360').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'A360_icon';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // askme icon
    document.getElementById('button-image-askme').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'askme_icon';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // promotion icon
    document.getElementById('button-image-promotion').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'promotion_icon';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // campaign icon
    document.getElementById('button-image-campaign').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'campaign_icon';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // template standard
    document.getElementById('button-image-standard').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'standard_lp_bg';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
    // template lite
    document.getElementById('button-image-lite').addEventListener('click', (event) => {
      event.preventDefault();

      inputId = 'lite_lp_bg';

      window.open('/file-manager/fm-button', 'fm', 'width=1000px,height=700px');
    });
  });

  // input
  let inputId = '';

  // set file link
  function fmSetLink($url) {
    document.getElementById(inputId).value = $url;
  }
</script>
@endsection
