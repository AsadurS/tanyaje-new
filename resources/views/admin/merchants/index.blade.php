@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.Organisation') }} <small>{{ trans('labels.Organisation') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.Organisation') }}</li>
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
            <div class="title-search">
              <!-- <input type="text" id="search" class="form-control" style="margin-left: 10px;" placeholder="Type to filter"> -->

              <!-- search filter -->
              {!! Form::open(array('url' =>'admin/filtermerchant', 'method'=>'get', 'class' => 'search-filter form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                <div class="form-group row">
                  <div class="col-xs-2">
                    <input class="form-control" id="name" name="name" type="text" placeholder="Name">
                  </div>
                  <div class="col-xs-1">
                    <input class="form-control" id="ID" name="ID" type="text" placeholder="ID">
                  </div>
                  <div class="col-xs-1">
                    <input class="form-control" id="brn_no" name="brn_no" type="text" placeholder="BRN">
                  </div>
                  <div class="col-xs-1">
                    <input class="form-control" id="roc_no" name="roc_no" type="text" placeholder="ROC">
                  </div>
                  <div class="col-xs-2">
                    <select name="segment_type" class="form-control">
                        <option value="" selected>Segments</option>
                        @if (count($result['segments']) > 0)
						              @foreach ($result['segments']  as $key=>$segment)
                          <option value="{{ $segment->segment_id }}">{{ $segment->segment_name }}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                  <div class="col-xs-2">
                    <button type="submit" class="btn btn-primary">Search <span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                  </div>
                </div>
              {!! Form::close() !!}
            </div>
            <div class="box-tools pull-right">
            	<a href="{{ URL::to('admin/addmerchants')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddOrganisation') }}</a>
            </div>
          </div>

          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">

              @if (count($errors) > 0)
                @if($errors->any())
                <div class="alert alert-success alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  {{$errors->first()}}
                </div>
                @endif
              @endif

              @if(session()->has('message'))
                <div class="alert alert-success" role="alert">
						  	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  {{ session()->get('message') }}
                </div>
              @endif
          
              </div>

            </div>
            <div class="row">
              <div class="col-xs-12">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>{{ trans('labels.ID') }}</th>
                      <th style="width: 230px;">{{ trans('labels.CompanyName') }}</th>
                      <th>{{ trans('labels.Email') }}</th>
                      <th>{{ trans('labels.Segment') }}</th>
                      <th>{{ trans('labels.Template') }}</th>
                      <th>{{ trans('labels.Inventory') }}</th>
                      <th>{{ trans('labels.SaleAgent') }}</th> 

                      <th> {{ trans('labels.Action') }} </th>                      
                    </tr>
                  </thead>
                  <tbody>
                  @if (count($result['admins']) > 0)
						        @foreach ($result['admins']  as $key=>$admin)
                    <tr>
                      <td>{{ $admin->id }}</td>
                      <td>
                        {{ $admin->company_name ?? 'NA' }}<br>
                        - {{ $admin->first_name }} {{ $admin->last_name }}
                      </td>
                      <td style="text-transform: none !important;">{{ $admin->email }} </td>
                      <td>
                        @if($admin->segment_id)
                          @foreach ($result['segments']  as $key=>$segment)
                            @if($admin->segment_id == $segment->segment_id)
                            <strong class="badge" style="background-color: #1000ff !important;">{{$segment->segment_name}}</strong>
                            @endif
                          @endforeach
                        @else
                          <strong class="badge" style="background-color: #676666 !important;">N/A</strong>
                        @endif
                      </td>
                      <td>
                        <!-- @if($admin->template_id)
                          @foreach ($result['templates']  as $key=>$template)
                            @if($admin->template_id == $template->id)
                              @if($template->preview_image)
                                <img src="{{asset('/images/template/'.$template->preview_image)}}" width="100px" alt="">
                              @else
                                <strong class="badge" style="background-color: #676666 !important;">N/A</strong>
                              @endif
                            @endif
                          @endforeach
                        @else
                          <strong class="badge" style="background-color: #676666 !important;">N/A</strong>
                        @endif -->

                        @if($admin->template_id)
                          @foreach ($result['templates']  as $key=>$template)
                            @if($admin->template_id == $template->id)
                              @if($template->preview_image)
                                <!-- <img src="{{asset('/images/template/'.$template->preview_image)}}" width="100px" alt=""> -->
                                @if (file_exists(public_path('/images/template/'.$template->preview_image)))
                                  <img src="{{asset('/images/template/'.$template->preview_image)}}" width="100px" alt="">
                                @else
                                  <img src="{{$template->preview_image}}" width="100px" alt="">
                                @endif
                              @else
                                <strong class="badge" style="background-color: #676666 !important;">N/A</strong>
                              @endif
                            @endif
                          @endforeach
                        @else
                          <strong class="badge" style="background-color: #676666 !important;">N/A</strong>
                        @endif
                      </td>
                      <td>
                        @php
                          $itemCar = DB::table('cars')
                            ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                            ->LeftJoin('models','cars.model_id','=','models.model_id')
                            ->LeftJoin('users','cars.merchant_id','=','users.id')
                            ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.first_name')
                            ->where('user_id', '=', $admin->id)
                            ->where('item_type_id', '=', '1')
                            ->distinct()
                            ->count('cars.vim');

                            $itemOther = DB::table('cars')
                              ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                              ->LeftJoin('models','cars.model_id','=','models.model_id')
                              ->LeftJoin('users','cars.merchant_id','=','users.id')
                              ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.first_name')
                              ->where('user_id', '=', $admin->id)
                              ->where('item_type_id', '!=', '1')
                              ->distinct()
                              ->count('cars.car_id');
                            
                            $item = $itemCar + $itemOther;
                        @endphp

                        @if($item > 0)
                          @if($admin->company_name != '')
                            <form  name='registration' id="registration" class="registration" method="get" action="{{url('admin/car/filter')}}">
                                <input type="hidden"  value="{{csrf_token()}}">
                                <div class="input-group-form search-panel ">
                                    <input type="hidden" name="change_view" value="">
                                    <select type="button" style="display:none;" class="btn btn-default dropdown-toggle form-control" data-toggle="dropdown" name="FilterBy" id="FilterBy"  >
                                        <option value="" selected disabled hidden>{{trans('labels.Filter By')}}</option>
                                        <option value="Merchant"  @if(isset($name)) @if  ($name == "Merchant") {{ 'selected' }} @endif @endif>{{ trans('labels.Merchant') }}</option>
                                        <option value="VIN"  @if(isset($name)) @if  ($name == "VIN") {{ 'selected' }} @endif @endif>{{trans('labels.VIN')}}</option>
                                        <option value="Make" @if(isset($name)) @if  ($name == "Make") {{ 'selected' }}@endif @endif>{{trans('labels.Brand')}}</option>
                                        <option value="Model" @if(isset($name)) @if  ($name == "Model") {{ 'selected' }}@endif @endif>{{trans('labels.Model')}}</option>
                                        <option value="Organisation" selected>{{trans('labels.Organisation')}}</option>
                                    </select>
                                    <input type="hidden" class="form-control input-group-form " name="parameter" placeholder="Search term..." id="parameter" value="{{$admin->company_name}}">
                                    <button class="btn btn-primary " id="submit" type="submit" formtarget="_blank" style="padding: 2px 5px;background-color: #1000ff;border-color: transparent;font-weight: 600;">{{ $item }}</button>
                                    @if(isset($param,$name))  <a class="btn btn-danger " href="{{url('admin/car/display')}}"><i class="fa fa-ban" aria-hidden="true"></i> </a>@endif
                                </div>
                            </form>
                          @else
                            <button class="btn btn-primary" style="padding: 2px 5px;background-color: #676666;border-color: transparent;font-weight: 600;">NA</button>
                          @endif
                        @else
                          <button class="btn btn-primary" style="padding: 2px 5px;background-color: #676666;border-color: transparent;font-weight: 600;">{{ $item }}</button>
                        @endif
                      </td>
                      @php $current_time = isset($admin->last_login_at) ? date('d/m/y h:i A', strtotime($admin->last_login_at)): ''; @endphp
                      <!-- <td>{{  $current_time }}</td> -->
                      @php
                        $branch = DB::table('merchant_branch')
                          ->where('merchant_branch.user_id', '=', $admin->id)
                          ->count();
                      @endphp
                      <td>
                        @if($branch > 0)
                          <a href="{{url('admin/saleAdvisor/'.$admin->id) }}" class="btn btn-primary " target="_blank" style="padding: 2px 5px;background-color: #1000ff;border-color: transparent;font-weight: 600;">{{ $branch }}</a>
                        @else
                          <a href="#" class="btn btn-primary " target="_blank" style="padding: 2px 5px;background-color: #676666;border-color: transparent;font-weight: 600;">{{ $branch }}</a>
                        @endif
                      </td>
								      <td>
                          <ul class="nav table-nav">
                            <li class="dropdown">
                              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                {{ trans('labels.Action') }} <span class="caret"></span>
                              </a>
                              <ul class="dropdown-menu">
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="editmerchant/{{ $admin->id }}">{{ trans('labels.EditOrganisation') }}</a></li>
                                  <li role="presentation" class="divider"></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('admin/saleAdvisor/'.$admin->id) }}">{{ trans('labels.SalesAdvisor') }}</a></li>
                                  <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('admin/merchants/branch/display/'.$admin->id) }}">{{ trans('labels.EditBranch') }}</a></li> -->
                                  <li role="presentation" class="divider"></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::to('admin/addsaleadvisor/'.$admin->id)}}">{{ trans('labels.AddSalesAdvisor') }}</a></li>
                                  <li role="presentation" class="divider"></li>
                                  <li role="presentation">   <a class="menuitem CopyBranchModal"  branch_id = "{{ $admin->id }}" >Duplicate Organisation Items</a> </li>
                                  <li role="presentation" class="divider"></li>
                                  <li role="presentation"><a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.DeleteMerchant') }}" id="deleteMemberFrom"
                                    users_id="{{ $admin->id }}">{{ trans('labels.DeleteOrganisation') }}</a></li>
                              </ul>
                            </li>
                          </ul>
								      </td>
							      </tr>
						        @endforeach
                  @else
                    <tr>
                      <td colspan="8">{{ trans('labels.NoRecordFound') }}</td>
                    </tr>
                  @endif
                  </tbody>
                </table>
                @if (count($result['admins']) > 0)
                  <div class="col-xs-12 text-right">
                    {{-- {{$result['admins']->links()}} --}}
                    {!! $result['admins']->appends(request()->except('page'))->render() !!}
                  </div>
                 @endif
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

     <!-- copyBranchModal -->
     <div class="modal fade" id="CopyBranchModal" tabindex="-1" role="dialog" aria-labelledby="copyBranchModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="copyBranchModalLabel">Duplicate All Items to Select Organisation</h4>
                                    </div>
                                    {!! Form::open(array('url' =>'admin/duplicateItems', 'name'=>'copyBranchFrom', 'id'=>'CopyBranchFrom', 'method'=>'post', 'class' => 'form-horizontal form-validate')) !!}
                                          {!! Form::hidden('branch_id', '', array('class'=>'form-control branch_input')) !!}
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Organisation') }}</label>
                                            <div class="col-sm-10 col-md-8">
                                            <select id="user_id" class="form-control BranchContent c-field-validate" name="user_id">
                                                        <option value="">{{ trans('labels.SelectOrganisationText') }}</option>
                                                        @if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT )
                                                            @foreach( $result['merchant'] as $merchant_data)
                                                                @if($merchant_data->user_id == auth()->user()->id)
                                                                <option value="{{ $merchant_data->id }}"> {{ $merchant_data->company_name ?? $merchant_data->first_name." ".$merchant_data->last_name }}</option>
                                                                @endif
                                                            @endforeach
                                                        @elseif( Auth()->user()->role_id == \App\Models\Core\User::ROLE_NORMAL_ADMIN || Auth()->user()->role_id == \App\Models\Core\User::ROLE_SUPER_ADMIN )
                                                            @foreach( $result['merchant'] as $merchant_data)
                                                                <option value="{{ $merchant_data->id }}"> {{ $merchant_data->company_name ?? $merchant_data->first_name." ".$merchant_data->last_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
                                        <button type="button" class="btn btn-primary form-validate" id="CopyBranch">Duplicate Item</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>

    <!-- deleteAdminModal -->
	<div class="modal fade" id="deleteMemberModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdminModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="deleteAdminModalLabel">{{ trans('labels.Delete') }}</h4>
                    </div>
                    {!! Form::open(array('url' =>'admin/deletemerchant', 'name'=>'deleteMerchant', 'id'=>'deleteMerchant', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                    {!! Form::hidden('action', 'delete', array('class'=>'form-control')) !!}
                    {!! Form::hidden('users_id', '', array('class'=>'form-control', 'id'=>'users_id')) !!}
                    <div class="modal-body">
                        <p>{{ trans('labels.DeleteOrganisationText') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('labels.Delete') }}</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content notificationContent">

                </div>
            </div>
        </div>

    <!-- Main row -->

    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
@endsection
