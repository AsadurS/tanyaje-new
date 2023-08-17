@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.templates') }} <small>{{ trans('labels.templates') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.templates') }}</li>
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
            <!-- <div class="title-search pull-left">
              <h3 class="box-title">{{ trans('labels.templates') }} </h3>
            </div> -->
            <div class="box-tools pull-right">
            	<a href="{{ URL::to('admin/addtemplates')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.addtemplates') }}</a>
            </div>
          </div>

          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
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
            <div class="row">
              <div class="col-xs-12"><br>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>{{ trans('labels.ID') }}</th>
                      <th>{{ trans('labels.TemplateName') }}</th>
                      <th>{{ trans('labels.Preview') }}</th>
                      <th>{{ trans('labels.CreatedDateTime') }}</th>
                      <th>{{ trans('labels.ModifiedDateTime') }}</th>
                      <th> {{ trans('labels.Action') }} </th>                      
                    </tr>
                  </thead>
                  <tbody>
                    @if (count($result['admins']) > 0)
                      @foreach ($result['admins']  as $key=>$admin)
                      <tr>
                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->name }}</td>
                        <td>
                          @if($admin->preview_image)
                            @if (file_exists(public_path('/images/template/'.$admin->preview_image)))
                              <img src="{{asset('/images/template/'.$admin->preview_image)}}" width="100px" alt="">
                            @else
                              <img src="{{$admin->preview_image}}" width="100px" alt="">
                            @endif
                            <!-- <span class="form-control" style="height:auto;">{!! $admin->template_code !!}</span> -->
                          @else
                            N/A
                          @endif
                        </td>
                        <td>{{ $admin->created_at }}</td>
                        <td>
                          @if($admin->updated_at)
                            {{ $admin->updated_at }}
                          @else
                            N/A
                          @endif
                        </td>
                        <td>
                          <ul class="nav table-nav">
                            <li class="dropdown">
                              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                {{ trans('labels.Action') }} <span class="caret"></span>
                              </a>
                              <ul class="dropdown-menu">
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="edittemplate/{{ $admin->id }}">{{ trans('labels.Edit') }}</a></li>
                                  <li role="presentation" class="divider"></li>
                                  <li role="presentation"><a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteMemberFrom"
                                    users_id="{{ $admin->id }}">{{ trans('labels.Delete') }}</a></li>
                              </ul>
                            </li>
                          </ul>
								        </td>
                      </tr>
                      @endforeach
                    @else
                    <tr>
                      <td colspan="5">{{ trans('labels.NoRecordFound') }}</td>
                    </tr>
                    @endif
                  </tbody>
                </table>
                @if($result['admins'] != null)
                  <div class="col-xs-12 text-right">
                    @if(app('request')->input('FilterBy'))
                        {!! $result['admins']->appends(request()->except('page'))->render() !!}
                    @else
                        {{$result['admins']->links()}}
                    @endif 
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

    <!-- deleteAdminModal -->
	<div class="modal fade" id="deleteMemberModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdminModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="deleteAdminModalLabel">{{ trans('labels.Delete') }}</h4>
                    </div>
                    {!! Form::open(array('url' =>'admin/deleteTemplate', 'name'=>'deleteTemplate', 'id'=>'deleteTemplate', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                    {!! Form::hidden('action', 'delete', array('class'=>'form-control')) !!}
                    {!! Form::hidden('users_id', '', array('class'=>'form-control', 'id'=>'users_id')) !!}
                    <div class="modal-body">
                        <p>{{ trans('labels.DeleteTemplateText') }}</p>
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

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

<!-- <script>
  $(document).ready( function () {
    $("#example1").dataTable({
        "aoColumnDefs": [{ 'bSortable': false, 'aTargets': [3] }],
        "bSort": false,
        dom: 'Blfrtip',
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 rows', '25 rows', '50 rows', 'Show all']
        ],
        buttons: [
            'excelHtml5'
        ]
    });
  } );
</script> -->
<style>
  .dataTables_wrapper .dataTables_length {
    float: left;
    margin-bottom: 24px;
  }
  .dataTables_wrapper .dataTables_paginate {
    float: right;
    text-align: right;
    padding-top: .25em;
    margin-top: 24px;
  }
  .dataTables_wrapper .dataTables_info {
    clear: both;
    float: left;
    padding-top: .755em;
    margin-top: 24px;
  }
  .table-nav ul.dropdown-menu {
    padding: 2px 0;
    width: 100%;
  }
</style>
@endsection
