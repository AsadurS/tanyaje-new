@extends('admin.layout')
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> {{ trans('labels.item_type') }} <small>{{ trans('labels.item_type') }}...</small> </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
        <li class="active">{{ trans('labels.item_type') }}</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              {{ session()->get('message') }}
            </div>
          @endif

          @if (session()->has('error_warning'))
            <div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              {{ session()->get('error_warning') }}
            </div>
          @endif

          @if (session('update'))
            <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong> {{ session('update') }} </strong>
            </div>
          @endif

          <div class="box">
            <div class="box-header">
              <div class="pull-right">
                <a href="{{ URL::to('admin/itemtype/add') }}" type="button" class="btn btn-block btn-primary">{{ trans('labels.add_item_type') }}</a>
              </div>
            </div>

            <div class="box-body">
              <div class="row">
                <div class="col-xs-12">
                  <div class="row">
                    <div class="col-xs-12">
                      @if (count($errors) > 0)
                        @if ($errors->any())
                          <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ $errors->first() }}
                          </div>
                        @endif
                      @endif
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12">
                  <table id="table-listing" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-left">{{ trans('labels.ID') }}</th>
                        <th class="text-left">{{ trans('labels.Name') }}</th>
                        <th class="text-right">{{ trans('labels.Action') }}</th>
                      </tr>
                    </thead>
                    <tbody class="contentAttribute">
                      @foreach ($data['item_types'] as $item_type)
                        <tr>
                          <td class="text-left">{{ $item_type->id }}</td>
                          <td class="text-left">{{ $item_type->name }}</td>
                          <td class="text-right">
                            <a href="{{ URL::to('admin/itemtype/edit/' . $item_type->id) }}" class="badge bg-light-blue">
                              <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a>
                            <a href="{{ URL::to('admin/itemtype/delete/' . $item_type->id) }}" class="badge bg-red btn-delete">
                              <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <script src="{!! asset('admin/plugins/jQuery/jQuery-2.2.0.min.js') !!}"></script>
  <script type="text/javascript">
    $('.btn-delete').on('click', function(e) {
      e.preventDefault();

      if (confirm("{{ trans('labels.itemtype_confirm_delete') }}")) {
        location = $(this).attr('href');
      }
    })
  </script>

@endsection
