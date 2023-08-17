@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> {{ trans('labels.AdminProfile') }} </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
            <li class="active">{{ trans('labels.AdminProfile') }} </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">

            <!-- /.col -->
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#profile" data-toggle="tab">{{ trans('labels.Profile') }}</a></li>
                        <li><a href="#passwordDiv" data-toggle="tab">{{ trans('labels.Password') }}</a></li>
                        @if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT)
                        <li><a href="{{ URL::to('admin/merchants/branch/display/' . auth()->user()->id . '/')}}" >Branch Contacts</a></li>
                        @endif
                    </ul>
                    <div class="tab-content">
                        <div class=" active tab-pane" id="profile">

                          @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                {{$errors->first()}}
                            </div>
                        @endif
                        <!-- The timeline -->
                           {!! Form::open(array('url' =>'admin/admin/update', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                              {!! Form::hidden('myid', auth()->user()->myid, array('class'=>'form-control', 'id'=>'myid'))!!}
                              <div class="form-group">
                                <label for="inputCompanyName" class="col-sm-2 control-label">{{ trans('labels.CompanyName') }}</label>
                                <div class="col-sm-10">
                                  {!! Form::text('company_name', Auth()->user()->company_name, array('class'=>'form-control', 'id'=>'company_name'))!!}
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                  {{ trans('labels.CompanyNameText') }}</span>
                                </div>
                              </div>

                              <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">{{ trans('labels.AdminFirstName') }}</label>
                                <div class="col-sm-10">
                                  {!! Form::text('first_name', Auth()->user()->first_name, array('class'=>'form-control', 'id'=>'first_name'))!!}
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                  {{ trans('labels.AdminFirstNameText') }}</span>
                                </div>
                              </div>

                              <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">{{ trans('labels.LastName') }}</label>

                                <div class="col-sm-10">
                                  {!! Form::text('last_name', auth()->user()->last_name, array('class'=>'form-control', 'id'=>'last_name'))!!}
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                  {{ trans('labels.AdminLastNameText') }}</span>
                                </div>
                              </div>

                              <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">{{ trans('labels.Address') }} </label>
                                <div class="col-sm-10">
                                  {!! Form::text('address', $result['admin']->entry_street_address, array('class'=>'form-control', 'id'=>'address'))!!}
                                  <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                  {{ trans('labels.AddressText') }}</span>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="inputExperience" class="col-sm-2 control-label">{{ trans('labels.City') }}
                                </label>

                                <div class="col-sm-10">
                                 {!! Form::text('city', $result['admin']->entry_city, array('class'=>'form-control', 'id'=>'city'))!!}
                                 <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CityText') }}</span>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="inputSkills" class="col-sm-2 control-label">{{ trans('labels.Country') }}</label>
                                           <div class="col-sm-10">
                                    <select class="form-control" name="country" id="entry_country_id">
                                        <option value="">{{ trans('labels.SelectCountry') }}</option>
                                        @foreach($result['countries'] as $countries)
                                            <option @if($result['admin']->entry_country_id==$countries->countries_id) selected @endif value="{{ $countries->countries_id }}">{{ $countries->countries_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CountryText') }}</span>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="inputSkills" class="col-sm-2 control-label">{{ trans('labels.State') }}</label>
                                             <div class="col-sm-10">
                                   <select class="form-control zoneContent" name="state">
                                        <option value="">{{ trans('labels.SelectZone') }}</option>
                                        @foreach($result['zones'] as $zones)
                                            <option @if($result['admin']->entry_state==$zones->zone_id) selected @endif value="{{ $zones->zone_id }}">{{ $zones->zone_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SelectZoneText') }}</span>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="inputExperience" class="col-sm-2 control-label">{{ trans('labels.ZipCode') }}</label>

                                <div class="col-sm-10">
                                 {!! Form::text('zip', $result['admin']->entry_postcode, array('class'=>'form-control', 'id'=>'zip'))!!}
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="inputExperience" class="col-sm-2 control-label">{{ trans('labels.Phone') }}</label>

                                <div class="col-sm-10">
                                 {!! Form::text('phone', auth()->user()->phone, array('class'=>'form-control', 'id'=>'phone'))!!}
                                 <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                 {{ trans('labels.PhoneText') }}</span>
                                </div>
                              </div>

                              @if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT)
                                <hr>
                                        <h4>{{ trans('labels.Merchant Site') }}</h4>
                                        <hr>
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.ROC_SCM_NO') }} </label>
                                            <div class="col-sm-10 col-md-4">
                                                {!! Form::text('roc_scm_no',  auth()->user()->roc_scm_no, array('class'=>'form-control', 'id'=>'ROCSCMNO')) !!}
                                                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                                {{ trans('labels.ROC_SCM_NOText') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label for="name" class="col-sm-2 col-md-3 control-label">Banner</label>
                                        <div class="col-sm-10 col-md-4">
                                            {{--{!! Form::file('newImage', array('id'=>'newImage')) !!}--}}
                                            <!-- Modal -->
                                                <div class="modal fade embed-images" id="newBannerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" id ="closemodal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                                <h3 class="modal-title text-primary" id="myModalLabel">{{ trans('labels.Choose Image') }} </h3>
                                                            </div>
                                                            <div class="modal-body manufacturer-image-embed">
                                                                @if(isset($allimage))
                                                                    <select class="image-picker show-html " name="banner_id" id="select_banner">
                                                                        <option  value=""></option>
                                                                        @foreach($allimage as $key=>$image)
                                                                            <option data-img-src="{{asset($image->path)}}"  class="imagedetail" data-img-alt="{{$key}}" value="{{$image->id}}"> {{$image->id}} </option>
                                                                        @endforeach
                                                                    </select>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <a href="{{url('admin/media/add')}}" target="_blank" class="btn btn-primary pull-left" >{{ trans('labels.Add Icon') }}</a>
                                                                <button type="button" class="btn btn-default refresh-image"><i class="fa fa-refresh"></i></button>
                                                                <button type="button" class="btn btn-success" id="selectedICONE" data-dismiss="modal">{{ trans('labels.Done') }}</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div  id ="imageselected">
                                                    {!! Form::button('Add Banner', array('id'=>'newBanner','class'=>"btn btn-primary", 'data-toggle'=>"modal", 'data-target'=>"#newBannerModal" )) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please choose the banner</span>
                                                    <div class="closimage">
                                                        <button type="button" class="close pull-right" id="image-close" style="display: none; position: absolute;left: 91px; top: 54px; background-color: black; color: white; opacity: 2.2;" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div  id="selectedthumbnail"></div>
                                                    <br>
                                                    {!! Form::hidden('old_banner_id', auth()->user()->banner_id, array('id'=>'oldBanner')) !!}
                                                    @if(auth()->user()->banner_id)
                                                        @php
                                                            $banner = DB::table('images')
                                                                        ->leftJoin('image_categories', 'images.id', '=', 'image_categories.image_id')
                                                                        ->select('path','images.id','image_type')
                                                                        ->where('images.id',auth()->user()->banner_id)
                                                                        ->where('image_categories.image_type','ACTUAL')
                                                                        ->first();
                                                        @endphp
                                                        @if(isset( $banner->path ))
                                                        <img width="80px" src="{{asset($banner->path)}}" class="">
                                                        @endif
                                                        <a href="javascript:;" class="btn remove_banner">Remove Banner</a>
                                                    @endif
                                                    <br>
                                                    <center>OR</center>
                                                    <br>
                                                    <input type="color" class="form-control" name="banner_color" value="{!! auth()->user()->banner_color !!}">
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please choose the banner color</span>
                                                </div>
                                        </div>
                                    </div>

                                        <div class="form-group">
                                        <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Logo') }}</label>
                                        <div class="col-sm-10 col-md-4">
                                            <!-- Modal -->
                                                <div class="modal fade embed-images" id="newLogoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" id ="closemodal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                                <h3 class="modal-title text-primary" id="myModalLabel">{{ trans('labels.Choose Image') }} </h3>
                                                            </div>
                                                            <div class="modal-body manufacturer-image-embed">
                                                                @if(isset($allimage))
                                                                    <select class="image-picker show-html " name="logo_id" id="select_logo">
                                                                        <option  value=""></option>
                                                                        @foreach($allimage as $key=>$image)
                                                                            <option data-img-src="{{asset($image->path)}}"  class="imagedetail" data-img-alt="{{$key}}" value="{{$image->id}}"> {{$image->id}} </option>
                                                                        @endforeach
                                                                    </select>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <a href="{{url('admin/media/add')}}" target="_blank" class="btn btn-primary pull-left" >{{ trans('labels.Add Icon') }}</a>
                                                                <button type="button" class="btn btn-default refresh-image"><i class="fa fa-refresh"></i></button>
                                                                <button type="button" class="btn btn-success" id="selectedICONE" data-dismiss="modal">{{ trans('labels.Done') }}</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div  id ="imageselected">
                                                    {!! Form::button('Add Logo', array('id'=>'newImage','class'=>"btn btn-primary", 'data-toggle'=>"modal", 'data-target'=>"#newLogoModal" )) !!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 3px;">Please choose the logo</span>
                                                    <div class="closimage">
                                                        <button type="button" class="close pull-right" id="image-close" style="display: none; position: absolute;left: 91px; top: 54px; background-color: black; color: white; opacity: 2.2;" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div  id="selectedthumbnail"></div>
                                                    <br>
                                                    {!! Form::hidden('old_logo_id', auth()->user()->logo_id, array('id'=>'oldImage')) !!}
                                                    @if(auth()->user()->logo_id)
                                                        @php
                                                            $logo = DB::table('images')
                                                                        ->leftJoin('image_categories', 'images.id', '=', 'image_categories.image_id')
                                                                        ->select('path','images.id','image_type')
                                                                        ->where('images.id',auth()->user()->logo_id)
                                                                        ->where('image_categories.image_type','ACTUAL')
                                                                        ->first();
                                                        @endphp
                                                        @if(isset( $logo->path ))
                                                        <img width="80px" src="{{asset($logo->path)}}" class="">
                                                        @endif
                                                    @endif

                                                </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                    <label for="name" class="col-sm-2 col-md-3 control-label">Title Color</label>
                                    <div class="col-sm-10 col-md-4">
                                        <input type="color" class="form-control" name="title_color" value="{!! auth()->user()->title_color !!}">
                                        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Please choose title color</span>
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                    </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Description') }}</label>
                                        <div class="col-sm-10 col-md-8">
                                            <textarea id="description" name="description" class="form-control" rows="5" maxlength="7000" onkeyup="textareaLengthCheck(this)">{!! auth()->user()->description !!}</textarea>
                                            <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;display:inline;">
                                                {{ trans('labels.EditorText') }}
                                            </span>
                                            <span id="lblRemainingCount" style="float:right;display:inline;"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Opening Hours') }}</label>
                                        <div class="col-sm-10 col-md-8">
                                            <textarea id="opening_hours" name="opening_hours" class="form-control" rows="5" maxlength="7000" onkeyup="textareaLengthCheck(this)">{!! auth()->user()->opening_hours !!}</textarea>
                                            <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;display:inline;">
                                                {{ trans('labels.OpeningHoursText') }}
                                            </span>
                                            <span id="lblRemainingCount" style="float:right;display:inline;"></span>
                                        </div>
                                    </div>
                                @endif
                              <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                  <button type="submit" class="btn btn-success">{{ trans('labels.Submit') }}</button>
                                </div>
                              </div>
                            {!! Form::close() !!}
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="passwordDiv">
                         {!! Form::open(array('url' =>'admin/admin/updatepassword', 'onSubmit'=>'return validatePasswordForm()', 'id'=>'updateAdminPassword', 'name'=>'updateAdminPassword' , 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}

                          <div class="form-group form-group-email">
                            <label for="email" class="col-sm-2 control-label">{{ trans('labels.Email') }}</label>
                                      <div class="col-sm-10">
                              <input type="text" class="form-control" id="email" value="{{$result['admin']->email}}" name="email" placeholder="email">
                              <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.AdminPasswordRestriction') }}</span>
                              <span style="display: none" class="help-block"></span>
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">{{ trans('labels.NewPassword') }}</label>
                                      <div class="col-sm-10">
                              <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
                              <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.AdminPasswordRestriction') }}</span>
                              <span style="display: none" class="help-block"></span>
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="re-password" class="col-sm-2 control-label">{{ trans('labels.Re-EnterPassword') }}</label>
                                      <div class="col-sm-10">
                              <input type="password" class="form-control" id="re_password" name="re_password" placeholder="Re-Enter Password">
                              <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.AdminPasswordRestriction') }}</span>
                              <span style="display: none" class="help-block"></span>
                            </div>
                        </div>

                          <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                              <button type="submit" class="btn btn-danger">{{ trans('labels.Submit') }}</button>
                            </div>
                          </div>
                        </form>
                        </div>
                        <!-- /.tab-pane -->

                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->
</div>
@endsection
