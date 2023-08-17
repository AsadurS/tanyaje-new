@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.SaleAdvisor') }} <small>{{ trans('labels.SaleAdvisor') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li class="active">{{ trans('labels.SaleAdvisor') }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    @if (session('update'))
                        <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong> {{ session('update') }} </strong>
                        </div>
                    @endif

                    <div class="box">
                        <div class="box-header">
                            
                            <!-- search filter -->
                            <form class="search_filter" role="form" >
                                <div class="form-group row">
                                <div class="col-xs-2">
                                    <h3 class="box-title" style="line-height: 2em;">{{ trans('labels.ListingSaleAdvisor') }}</h3>
                                </div>
                                <div class="col-xs-1">
                                    <input class="form-control" id="ex2" type="text" placeholder="ID">
                                </div>
                                <div class="col-xs-2">
                                    <input class="form-control" id="ex1" type="text" placeholder="Name">
                                </div>
                                <div class="col-xs-2">
                                    <select class="form-control">
                                        <option>Organisations</option>
                                        @if (count($data['admins']) > 0)
                                            @foreach ($data['admins']  as $key=>$admin)
                                                <option value="{{ $admin->id }}">{{ $admin->first_name }} {{ $admin->last_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xs-2">
                                    <select class="form-control">
                                        <option>Segments</option>
                                        @if (count($data['segments']) > 0)
                                            @foreach ($data['segments']  as $key=>$segment)
                                                <option value="{{ $segment->segment_id }}">{{ $segment->segment_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xs-2">
                                    <button type="submit" class="btn btn-primary">Search <span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                </div>
                                </div>
                            </form>
                            <div class="box-tools pull-right">
                                <!-- <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#addBranchModal">{{ trans('labels.AddSaleAdvisor') }}</button> -->
                                <a href="{{ URL::to('admin/merchants/addsaleadvisor')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddSaleAdvisor') }}</a>
                            </div>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
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
                                      </div>
                                  </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('labels.ID') }}</th>
                                            <th>{{ trans('labels.Name') }}</th>
                                            <th>{{ trans('labels.Organisation') }}</th>
                                            <th>{{ trans('labels.Email') }}</th>
                                            <th>{{ trans('labels.Phone') }}</th>
                                            <th>{{ trans('labels.Verified') }}</th>
                                            <th>{{ trans('labels.Hit') }}</th>
                                            <th>{{ trans('labels.Status') }}</th>
                                            <th>{{ trans('labels.Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody class="contentAttribute">

                                        @if (count($data['merchant_branch']) > 0)
                                            @foreach($data['merchant_branch'] as $merchant_branch)
                                                <tr>
                                                    <td>{{ $merchant_branch->id }}</td>
                                                    <td>
                                                        {{ $merchant_branch->merchant_name }}<br>
                                                    </td>
                                                    <td>ABC Motor</td>
                                                    <td>
                                                        {{ $merchant_branch->merchant_email }}<br>
                                                    </td>
                                                    <td>
                                                        {{ $merchant_branch->merchant_phone_no }}<br>
                                                    </td>
                                                    <td>
                                                        @if($merchant_branch->is_default==1)
                                                            <i class="fa fa-check-circle" style="font-size:24px;color: #03b900;"></i>
                                                        @else
                                                            <i class="fa fa-times-circle" style="font-size:24px;color:red;"></i>
                                                        @endif
                                                        
                                                    </td>
                                                    <td>
                                                        10
                                                    </td>
                                                    <td>
                                                        @if($merchant_branch->is_default==1)
                                                            <strong class="badge bg-green">{{trans('labels.Active')}} </strong>
                                                        @else
                                                            <strong class="badge bg-light-grey">{{trans('labels.InActive')}} </strong>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="badge bg-green" href="{!! route('copy_merchant_cars',['branch_id' => $merchant_branch->id,'user_id' => $data['user_id']]) !!}" ><i class="fa fa-copy" aria-hidden="true"></i></a>
                                                        <a class="badge bg-light-blue CopyBranchModal" user_id = '{{ $data['user_id'] }}' branch_id = "{{ $merchant_branch->id }}" ><i class="fa fa-copy" aria-hidden="true"></i></a>
                                                        <!-- <a class="badge bg-light-blue editBranchModal" user_id = '{{ $data['user_id'] }}' branch_id = "{{ $merchant_branch->id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> -->
                                                        <a href="{{ URL::to('admin/merchants/editsaleadvisor')}}" user_id = '{{ $data['user_id'] }}' branch_id = "{{ $merchant_branch->id }}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                        <a branch_id = "{{ $merchant_branch->id }}" class="badge bg-red deleteBranchModal"><i class="fa fa-trash " aria-hidden="true"></i></a></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6">{{ trans('labels.NoRecordFound') }}</td>
                                            </tr>
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="box-footer text-center">
                                <a href="{{ URL::to('admin/merchants')}}" class="btn btn-primary">{{ trans('labels.SaveComplete') }}</a>
                            </div>
                            <!-- /.box-body -->
                        </div>

                        <!-- copyBranchModal -->
                        <div class="modal fade" id="CopyBranchModal" tabindex="-1" role="dialog" aria-labelledby="copyBranchModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="copyBranchModalLabel">{{ trans('labels.CopyBranch') }}</h4>
                                    </div>
                                    {!! Form::open(array('url' =>'route("copy_branch_cars")', 'name'=>'copyBranchFrom', 'id'=>'CopyBranchFrom', 'method'=>'post', 'class' => 'form-horizontal form-validate')) !!}
                                    {!! Form::hidden('branch_id', '', array('class'=>'form-control branch_input')) !!}
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Branch') }}</label>
                                            <div class="col-sm-10 col-md-8">
                                                <select id="user_id" class="form-control BranchContent c-field-validate" name="user_id">
                                                    <option value="">{{ trans('labels.SelectBranch') }}</option>
                                                    @foreach($data['merchant_branch'] as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->merchant_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
                                        <button type="button" class="btn btn-primary form-validate" id="CopyBranch">{{ trans('labels.CopyBranch') }}</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>

                        <!-- addBranchModal -->
                        <div class="modal fade" id="addBranchModal" tabindex="-1" role="dialog" aria-labelledby="addBranchModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="addBranchModalLabel">{{ trans('labels.AddSaleAdvisor') }}</h4>
                                    </div>
                                    {!! Form::open(array('url' =>'', 'name'=>'addBranchFrom', 'id'=>'addBranchFrom', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                                    {!! Form::hidden('user_id',  $data['user_id'] , array('class'=>'form-control', 'id'=>'merchant_name')) !!}
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.MerchantName') }}</label>
                                            <div class="col-sm-10 col-md-8">
                                                {!! Form::text('merchant_name',  '', array('class'=>'form-control field-validate', 'id'=>'merchant_name')) !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.EmailAddress') }} </label>
                                            <div class="col-sm-10 col-md-8">
                                                {!! Form::text('merchant_email', '', array('class'=>'form-control field-validate', 'id'=>'merchant_email')) !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.MerchantContactNumber') }}</label>
                                            <div class="col-sm-10 col-md-8">
                                                {!! Form::text('merchant_phone_no',  '', array('class'=>'form-control field-validate', 'id'=>'merchant_phone_no')) !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.MerchantPayment') }}</label>
                                            <div class="col-sm-10 col-md-8">
                                                {!! Form::text('merchant_payment',  '', array('class'=>'form-control field-validate', 'id'=>'merchant_payment')) !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.State') }}</label>
                                            <div class="col-sm-10 col-md-8">
                                                <select id="choose_state_id" class="form-control field-validate" name="state_id">
                                                    <option value="">{{ trans('labels.SelectState') }}</option>
                                                    @foreach($data['state'] as $state_data)
                                                        <option value="{{ $state_data->state_id }}">{{ $state_data->state_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.City') }}</label>
                                            <div class="col-sm-10 col-md-8">
                                                <select id="city_id" class="form-control cityContent field-validate" name="city_id">
                                                    <option value="">{{ trans('labels.SelectCity') }}</option>
                                                    @foreach($data['city'] as $city_data)
                                                        <option value="{{ $city_data->city_id }}">{{ $city_data->city_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Status') }}</label>
                                            <div class="col-sm-10 col-md-8">
                                                <select id="is_default" class="form-control" name="is_default">
                                                    <option value="0">{{ trans('labels.No') }}</option>
                                                    <option value="1">{{ trans('labels.Yes') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
                                        <button type="button" class="btn btn-primary form-validate" id="addBranch">{{ trans('labels.AddBranch') }}</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>

                        <!-- editBranchModal -->
                        <div class="modal fade" id="editBranchModal" tabindex="-1" role="dialog" aria-labelledby="editBranchModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content editContent">

                                </div>
                            </div>
                        </div>

                        <!-- deleteBranchModal -->
                        <div class="modal fade" id="deleteBranchModal" tabindex="-1" role="dialog" aria-labelledby="deleteBranchModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="deleteBranchModalLabel">{{ trans('labels.DeleteBranch') }}</h4>
                                    </div>
                                    {!! Form::open(array('url' =>'admin/merchants/deleteBranch', 'name'=>'deleteBranch', 'id'=>'deleteBranch', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                                    {!! Form::hidden('user_id',  '', array('class'=>'form-control', 'id'=>'user_id')) !!}
                                    {!! Form::hidden('branch_id',  '', array('class'=>'form-control', 'id'=>'branch_id')) !!}
                                    <div class="modal-body">
                                        <p>{{ trans('labels.DeleteBranchText') }}</p>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Cancel') }}</button>
                                            <button type="submit" class="btn btn-primary" id="deleteAddressBtn">{{ trans('labels.Delete') }}</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- Main row -->


            <!-- /.row -->

            <!-- Main row -->

    </div>


    <!-- /.row -->
    </section>
    <!-- /.content -->
    </div>
@endsection
