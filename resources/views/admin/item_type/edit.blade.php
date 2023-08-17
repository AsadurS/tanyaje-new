@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.addItem') }} <small>{{ trans('labels.addItem') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i>{{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ url('admin/itemtype/display') }}"><i class="fa fa-list"></i>{{ trans('labels.listingAllItem') }}</a></li>
                <li class="active">{{ trans('labels.addItem') }}</li>
            </ol>
        </section>

        {{-- content --}}
        <section class="content">
            {!! Form::open(['url' => 'admin/itemtype/update', 'method' => 'post', 'class' => 'form-horizontal form-validate', 'enctype' => 'multipart/form-data']) !!}
            <input type="hidden" name="id" value="{{ $data['item_type']->id }}" />
            
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            @if (session()->has('message'))
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    {{ session()->get('message') }}
                                </div>
                            @endif

                            <div class="box-body">
                                <h4>{{ trans('labels.item_type_specific') }} </h4>
                                <hr>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.item_type_name') }}</label>
                                    <div class="col-sm-10 col-md-4">
                                        {!! Form::text('name', $data['item_type']->name, ['class' => 'form-control field-validate', 'id' => 'name']) !!}
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                    </div>
                                </div>

                                <h4>{{ trans('labels.itemTypeAttribute') }} </h4>
                                <hr>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2 col-md-3"></label>
                                    <div class="col-sm-10 col-md-4">
                                        @if($result['item_attribute'])
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th>Item Attribute</th>
                                                </tr>
                                                @foreach($result['item_attribute'] as $item_attribute)
                                                <tr>
                                                    <td>
                                                        <input name="checkAttribute[]" type="checkbox" value="{{ $item_attribute->id }}" 
                                                        @foreach($result['item_type_attributes'] as $item_type_attribute)
                                                            @if($item_type_attribute->item_attribute_id == $item_attribute->id)
                                                            checked
                                                            @endif
                                                        @endforeach
                                                        >
                                                        {{ $item_attribute->name }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box-body">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                    <a href="{{ url('admin/itemtype/display') }}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </section>
        {{-- end content --}}
    </div>
@endsection
