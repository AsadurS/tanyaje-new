@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @if($result['item_type_id'])
                @if($result['item_type'])
                    @foreach($result['item_type'] as $item_t)
                        @if($item_t->id == $result['item_type_id'])
                            <h1> {{$item_t->name}} Listing <small> Listing all Item Type ({{$item_t->name}})...</small> </h1>
                        @endif
                    @endforeach
                @endif
            @else
            <h1> {{ trans('labels.Item') }} <small> {{ trans('labels.ListingAllItem') }}...</small> </h1>
            @endif
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li class="active"> {{ trans('labels.Item') }}</li>
            </ol>
        </section>

        <!--  content -->
        <section class="content">
            <!-- Info boxes -->

            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            @if($result['item_type_id'])
                                @if($result['item_type_id'] == '1')
                                    <div class="col-lg-8 form-inline" id="contact-form">
                                        <form  name='registration' id="registration" class="registration" method="get" action="{{url('admin/car/filter')}}">
                                            <input type="hidden"  value="{{csrf_token()}}">
                                            <div class="input-group-form search-panel ">
                                                @if( $result['item_type'] )
                                                    <select name="change_view" class="btn btn-default dropdown-toggle form-control" onchange="javascript:handleSelect(this)">
                                                        <option value="">Change Item Listing View</option>
                                                        @foreach( $result['item_type'] as $change_item )
                                                            @if($result['item_type_id'])
                                                            <option value="{{$change_item->id}}" @if($change_item->id == $result['item_type_id']) selected @endif>{{$change_item->name}}</option>
                                                            @else
                                                            <option value="{{$change_item->id}}">{{$change_item->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif
                                                <select type="button" class="btn btn-default dropdown-toggle form-control" data-toggle="dropdown" name="FilterBy" id="FilterBy"  >
                                                    <option value="" selected disabled hidden>{{trans('labels.Filter By')}}</option>
                                                    <option value="Merchant"  @if(isset($name)) @if  ($name == "Merchant") {{ 'selected' }} @endif @endif>{{ trans('labels.Organisation') }}</option>
                                                    <option value="VIN"  @if(isset($name)) @if  ($name == "VIN") {{ 'selected' }} @endif @endif>{{trans('labels.VIN')}}</option>
                                                    <option value="Make" @if(isset($name)) @if  ($name == "Make") {{ 'selected' }}@endif @endif>{{trans('labels.Brand')}}</option>
                                                    <option value="Model" @if(isset($name)) @if  ($name == "Model") {{ 'selected' }}@endif @endif>{{trans('labels.Model')}}</option>
                                                    <!-- <option value="Type" @if(isset($name)) @if  ($name == "Type") {{ 'selected' }}@endif @endif>{{trans('labels.item_type')}}</option> -->
                                                </select>
                                                <input type="text" class="form-control input-group-form " name="parameter" placeholder="Search term..." id="parameter" @if(isset($param)) value="{{$param}}" @endif >
                                                <button class="btn btn-primary " id="submit" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                                @if(isset($param,$name))  <a class="btn btn-danger " href="{{url('admin/car/display')}}"><i class="fa fa-ban" aria-hidden="true"></i> </a>@endif
                                            </div>
                                        </form>
                                        <div class="col-lg-4 form-inline" id="contact-form12"></div>
                                    </div>
                                @else
                                    <div class="col-lg-8 form-inline" id="contact-form">
                                        <form  name='registration' id="registration" class="registration" method="get" action="{{url('admin/car/filter')}}">
                                            <input type="hidden"  value="{{csrf_token()}}">
                                            <div class="input-group-form search-panel ">
                                                @if( $result['item_type'] )
                                                    <select name="change_view" class="btn btn-default dropdown-toggle form-control" onchange="javascript:handleSelect(this)">
                                                        <option value="">Change Item Listing View</option>
                                                        @foreach( $result['item_type'] as $change_item )
                                                            @if($result['item_type_id'])
                                                            <option value="{{$change_item->id}}" @if($change_item->id == $result['item_type_id']) selected @endif>{{$change_item->name}}</option>
                                                            @else
                                                            <option value="{{$change_item->id}}">{{$change_item->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif
                                                <select type="button" class="btn btn-default dropdown-toggle form-control" data-toggle="dropdown" name="FilterBy" id="FilterBy"  >
                                                    <option value="" selected disabled hidden>{{trans('labels.Filter By')}}</option>
                                                    <option value="Merchant"  @if(isset($name)) @if  ($name == "Merchant") {{ 'selected' }} @endif @endif>{{ trans('labels.Organisation') }}</option>
                                                    <option value="ItemName"  @if(isset($name)) @if  ($name == "ItemName") {{ 'selected' }} @endif @endif>{{ trans('labels.ItemName') }}</option>
                                                    <!-- <option value="VIN"  @if(isset($name)) @if  ($name == "VIN") {{ 'selected' }} @endif @endif>{{trans('labels.VIN')}}</option>
                                                    <option value="Make" @if(isset($name)) @if  ($name == "Make") {{ 'selected' }}@endif @endif>{{trans('labels.Brand')}}</option>
                                                    <option value="Model" @if(isset($name)) @if  ($name == "Model") {{ 'selected' }}@endif @endif>{{trans('labels.Model')}}</option> -->
                                                    <!-- <option value="Type" @if(isset($name)) @if  ($name == "Type") {{ 'selected' }}@endif @endif>{{trans('labels.item_type')}}</option> -->
                                                </select>
                                                <input type="text" class="form-control input-group-form " name="parameter" placeholder="Search term..." id="parameter" @if(isset($param)) value="{{$param}}" @endif >
                                                <button class="btn btn-primary " id="submit" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                                @if(isset($param,$name))  <a class="btn btn-danger " href="{{url('admin/car/display')}}"><i class="fa fa-ban" aria-hidden="true"></i> </a>@endif
                                            </div>
                                        </form>
                                        <div class="col-lg-4 form-inline" id="contact-form12"></div>
                                    </div>
                                @endif
                            @else
                                <div class="col-lg-8 form-inline" id="contact-form">
                                    <form  name='registration' id="registration" class="registration" method="get" action="{{url('admin/car/filter')}}">
                                        <input type="hidden"  value="{{csrf_token()}}">
                                        <div class="input-group-form search-panel ">
                                            @if( $result['item_type'] )
                                                <select name="change_view" class="btn btn-default dropdown-toggle form-control" onchange="javascript:handleSelect(this)">
                                                    <option value="">Change Item Listing View</option>
                                                    @foreach( $result['item_type'] as $change_item )
                                                        @if($result['item_type_id'])
                                                        <option value="{{$change_item->id}}" @if($change_item->id == $result['item_type_id']) selected @endif>{{$change_item->name}}</option>
                                                        @else
                                                        <option value="{{$change_item->id}}">{{$change_item->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            @endif
                                            <select type="button" class="btn btn-default dropdown-toggle form-control" data-toggle="dropdown" name="FilterBy" id="FilterBy"  >
                                                <option value="" selected disabled hidden>{{trans('labels.Filter By')}}</option>
                                                <option value="Merchant"  @if(isset($name)) @if  ($name == "Merchant") {{ 'selected' }} @endif @endif>{{ trans('labels.Organisation') }}</option>
                                                <option value="VIN"  @if(isset($name)) @if  ($name == "VIN") {{ 'selected' }} @endif @endif>{{trans('labels.VIN')}}</option>
                                                <option value="Make" @if(isset($name)) @if  ($name == "Make") {{ 'selected' }}@endif @endif>{{trans('labels.Brand')}}</option>
                                                <option value="Model" @if(isset($name)) @if  ($name == "Model") {{ 'selected' }}@endif @endif>{{trans('labels.Model')}}</option>
                                                <!-- <option value="Type" @if(isset($name)) @if  ($name == "Type") {{ 'selected' }}@endif @endif>{{trans('labels.item_type')}}</option> -->
                                            </select>
                                            <input type="text" class="form-control input-group-form " name="parameter" placeholder="Search term..." id="parameter" @if(isset($param)) value="{{$param}}" @endif >
                                            <button class="btn btn-primary " id="submit" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                            @if(isset($param,$name))  <a class="btn btn-danger " href="{{url('admin/car/display')}}"><i class="fa fa-ban" aria-hidden="true"></i> </a>@endif
                                        </div>
                                    </form>
                                    <div class="col-lg-4 form-inline" id="contact-form12"></div>
                                </div>
                            @endif
                            
                            <div class="box-tools pull-right">
                                <a href="{{url('admin/car/add')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddNewItem') }}</a>
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>@sortablelink('car_id', trans('labels.ID') )</th>
                                            <th>{{ trans('labels.item_type') }}</th>
                                            <th>@sortablelink('merchant_id', trans('labels.Organisation') )</th>
                                            @if(count($result['item_header']) > 0)
                                                <th>{{ trans('labels.item_title') }}</th>
                                                @foreach($result['item_header'] as $dynamic_header)
                                                    <th>{{ $dynamic_header->name }}</th>
                                                @endforeach
                                            @else
                                                <th>@sortablelink('vim', trans('labels.VIN') )</th>
                                                <th>@sortablelink('make_name', trans('labels.Brand') )</th>
                                                <th>@sortablelink('model_name', trans('labels.Model') )</th>
                                                <th>@sortablelink('price', trans('labels.Price') )</th>
                                                <th>{{ trans('labels.Image') }}</th>
                                                <th>@sortablelink('status', trans('labels.Condition'))</th>
                                            @endif
                                            <th>{{ trans('labels.Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($car as $key=>$value)
                                            <tr>
                                                <td>{{ $value->car_id }}</td>
                                                <td>{{ $value->item_type_name ?? 'NA' }}</td>
                                                <td>{{ $value->company_name ?? 'NA' }}</td>
                                                @if(count($result['item_header']) > 0)
                                                    <td>{{ $value->title ?? 'NA' }}</td>
                                                    @foreach($result['item_header'] as $dynamic_header)
                                                        @php 
                                                            $car_extra = DB::table('car_extra')
                                                                            ->LeftJoin('item_attributes_value','item_attributes_value.id','=','car_extra.item_attribute_value')
                                                                            ->where('car_extra.cars_id','=',$value->car_id)
                                                                            ->where('car_extra.item_attribute_id','=',$dynamic_header->item_attribute_id)
                                                                            ->first();
                                                        @endphp
                                                        @if($dynamic_header->html_type == 'file')
                                                        <td>
                                                            @if($car_extra)
                                                                <img src="{{ $car_extra->item_attribute_value }}" style="width:100px;">
                                                            @else
                                                                NA
                                                            @endif
                                                        </td>
                                                        @else
                                                        <td>
                                                            @if($car_extra)
                                                                @if($car_extra->attribute_value)
                                                                {{$car_extra->attribute_value}}
                                                                @else
                                                                {{$car_extra->item_attribute_value}}
                                                                @endif
                                                            @else
                                                                NA
                                                            @endif
                                                        </td>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <td>{{ $value->vim ?? 'NA' }}</td>
                                                    <td>{{ $value->make_name ?? 'NA' }}</td>
                                                    <td>{{ $value->model_name ?? 'NA' }}</td>
                                                    <td>{{ number_format($value->price, 2, '.', ',') }}</td>
                                                    <td>
                                                        <img src="{{ 'https://manager.spincar.com/web-preview/walkaround-thumb/' . $value->sp_account . '/' . strtolower($value->vim) . '/md'  }}" alt="" width=" 100px">
                                                    </td>
                                                    <td>
                                                        @if($value->status==0)
                                                        <span class="label label-default">
                                                            {{ trans('labels.NewArrivals') }}
                                                        </span>
                                                        @elseif($value->status==1)
                                                        <span class="label label-default">
                                                            {{ trans('labels.New') }}
                                                        </span>
                                                        @elseif($value->status==2)
                                                        <span class="label label-success">
                                                            {{ trans('labels.Used') }}
                                                        </span>
                                                        @elseif($value->status==3)
                                                        <span class="label label-info">
                                                            {{ trans('labels.Recond') }}
                                                        </span>
                                                        @elseif($value->status==4)
                                                        <span class="label label-info">
                                                            {{ trans('labels.Sold') }}
                                                        </span>
                                                        @endif
                                                    </td>
                                                @endif

                                                <td>
                                                    <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Edit') }}" href="{{url('admin/car/edit/'.$value->car_id)}}" class="badge bg-light-blue">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a>
                                                    <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteCarId" car_id ="{{ $value->car_id }}" class="badge bg-red">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @if($car != null)
                                      <div class="col-xs-12 text-right">
                                        @if(app('request')->input('FilterBy'))
                                            {!! $car->appends(request()->except('page'))->render() !!}
                                        @else
                                            {{$car->links()}}
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
            <!-- deleteCarModal -->
            <div class="modal fade" id="deleteCarModal" tabindex="-1" role="dialog" aria-labelledby="deleteCarModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="deleteCarModalLabel">{{ trans('labels.DeleteCar') }}</h4>
                        </div>
                        {!! Form::open(array('url' =>'admin/car/delete', 'name'=>'deleteCar', 'id'=>'deleteCar', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                        {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
                        {!! Form::hidden('id',  '', array('class'=>'form-control', 'id'=>'car_id')) !!}
                        <div class="modal-body">
                            <p>{{ trans('labels.DeleteCarText') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Cancel') }}</button>
                            <button type="submit" class="btn btn-primary" id="deleteCar">{{ trans('labels.Delete') }}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <!--  row -->

            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <script src="{!! asset('admin/plugins/jQuery/jQuery-2.2.0.min.js') !!}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#item_type_add').on('change', function() {
                var $form = $(this).closest('form');
                $form.find('input[type=submit]').click();
            });
        });
    </script>
    <script type="text/javascript">
        function handleSelect(elm)
        {
            var APP_URL = {!! json_encode(url('/')) !!};
            window.location = APP_URL+'/admin/car/display/'+elm.value;
        }
    </script>
@endsection
