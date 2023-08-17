@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.editItemAttribute') }} <small>{{ trans('labels.editItemAttribute') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i>{{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ url('admin/itemattribute/display') }}"><i class="fa fa-list"></i>{{ trans('labels.ListingItemAttributes') }}</a></li>
                <li class="active">{{ trans('labels.editItemAttribute') }}</li>
            </ol>
        </section>

        {{-- content --}}
        <section class="content">
            {!! Form::open(['url' => 'admin/itemattribute/update', 'method' => 'post', 'class' => 'form-horizontal form-validate', 'enctype' => 'multipart/form-data']) !!}
            <input type="hidden" name="id" value="{{ $result['admins'][0]->id }}" />
            
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

                            <div class="box-body">
                                <h4>{{ trans('labels.item_attribute_specific') }} </h4>
                                <hr>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.itemAttributeName') }}</label>
                                    <div class="col-sm-10 col-md-4">
                                        <input type="text" name="name" id="name" value="{{$result['admins'][0]->name}}" placeholder="Example: item_name" class="form-control field-validate">
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.SortOrder') }}</label>
                                    <div class="col-sm-10 col-md-4">
                                        <input type="number" name="sort_no" id="sort_no" value="{{$result['admins'][0]->sort_no}}" class="form-control field-validate">
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.Header') }}</label>
                                    <div class="col-sm-10 col-md-4">
                                        <select name="header" class="form-control field-validate">
                                            <option value="0" @if($result['admins'][0]->header == '0') selected @endif>No</option>
                                            <option value="1" @if($result['admins'][0]->header == '1') selected @endif>Yes</option>
                                        </select>
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.itemAttributeHtmlType') }}</label>
                                    <div class="col-sm-10 col-md-4">
                                        <select name="html_type" id="html_type" class="form-control" onchange="getval(this);">
                                            <option>Please choose the html type</option>
                                            <option value="text" @if($result['admins'][0]->html_type == 'text') selected @endif>Text</option>
                                            <option value="date" @if($result['admins'][0]->html_type == 'date') selected @endif>Date</option>
                                            <option value="time" @if($result['admins'][0]->html_type == 'time') selected @endif>Time</option>
                                            <option value="file" @if($result['admins'][0]->html_type == 'file') selected @endif>File/Image</option>
                                            <option value="number" @if($result['admins'][0]->html_type == 'number') selected @endif>Number</option>
                                            <option value="radio" @if($result['admins'][0]->html_type == 'radio') selected @endif>Radio</option>
                                            <option value="select" @if($result['admins'][0]->html_type == 'select') selected @endif>Select</option>
                                            <option value="textarea" @if($result['admins'][0]->html_type == 'textarea') selected @endif>Textarea</option>
                                            <option value="checkbox" @if($result['admins'][0]->html_type == 'checkbox') selected @endif>Checkbox</option>
                                        </select>
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                    </div>
                                </div>

                                <div class="form-group" id="isItemImg">  
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.showAsItemImages') }}</label>
                                    <div class="col-sm-10 col-md-4">
                                        <select name="is_item_img" class="form-control">
                                            <option value="0" @if($result['admins'][0]->is_item_img == '0') selected @endif>No</option>
                                            <option value="1" @if($result['admins'][0]->is_item_img == '1') selected @endif>Yes</option>
                                        </select>
                                    </div>
                                </div> 

                                @if(count($result['attribute_value']) > 0)
                                <div class="form-group" id="itemAttributeValue">  
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.itemAttributeValue') }}</label>
                                    <div class="col-sm-10 col-md-6">
                                        <form name="add_attribute_value" id="add_attribute_value">  
                                            <div class="table-responsive">  
                                                <table class="table" id="dynamic_field">  
                                                    @foreach($result['attribute_value'] as $att_value)
                                                        <input type="hidden" name="attribute_id[]" id="attribute_id[]" value="{{ $att_value->id }}">
                                                        <tr>  
                                                            <td><input type="text" name="attributeValue[]" placeholder="Enter your Attribute Value" class="form-control name_list" value="{{$att_value->attribute_value}}" /></td>  
                                                            <td>
                                                                <a href="{{ URL::to('admin/itemattribute/deleteAtrributeValue/' . $att_value->id) }}" class="badge bg-red btn-delete">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i> Remove
                                                                </a>
                                                            </td>  
                                                        </tr>  
                                                    @endforeach
                                                        <tr>  
                                                            <td><input type="text" name="attributeValue[]" placeholder="Enter your Attribute Value" class="form-control name_list" /></td>  
                                                            <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                                        </tr>  
                                                </table>  
                                            </div>  
                                        </form>  
                                    </div>
                                </div> 
                                @else
                                <div class="form-group" id="itemAttributeValue" style="display:block;">  
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.itemAttributeValue') }}</label>
                                    <div class="col-sm-10 col-md-6">
                                        <form name="add_attribute_value" id="add_attribute_value">  
                                            <div class="table-responsive">  
                                                <table class="table" id="dynamic_field">  
                                                    @foreach($result['attribute_value'] as $att_value)
                                                        <tr>  
                                                            <td><input type="text" name="attributeValue[]" placeholder="Enter your Attribute Value" class="form-control name_list" value="{{$att_value->name}}" /></td>  
                                                            <td>
                                                                <a href="{{ URL::to('admin/itemattribute/deleteAtrributeValue/' . $att_value->id) }}" class="badge bg-red btn-delete">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i> Remove
                                                                </a>
                                                            </td>  
                                                        </tr>  
                                                    @endforeach
                                                        <tr>  
                                                            <td><input type="text" name="attributeValue[]" placeholder="Enter your Attribute Value" class="form-control name_list" /></td>  
                                                            <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                                        </tr>  
                                                </table>  
                                            </div>  
                                        </form>  
                                    </div>
                                </div> 
                                @endif 

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="box-body">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                                <a href="{{ url('admin/itemattribute/display') }}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
                                            </div>
                                        </div>
                                    </div>
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

    <!-- script -->
    <script src="{!! asset('admin/plugins/jQuery/jQuery-2.2.0.min.js') !!}"></script>
    <script type="text/javascript">
        $('.btn-delete').on('click', function(e) {
        e.preventDefault();

        if (confirm("{{ trans('labels.itemtype_confirm_delete') }}")) {
            location = $(this).attr('href');
        }
        })
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var current_type = $('#html_type').val();
            if(current_type == 'radio' || current_type == 'select' || current_type == 'checkbox'){
                $("#itemAttributeValue").css("display", "block");
            }
            else{
                $("#itemAttributeValue").css("display", "none");
            }
            if(current_type == 'file'){
                $("#isItemImg").css("display", "block");
            }
            else{
                $("#isItemImg").css("display", "none");
            }
        });

        function getval(sel)
        {
            if(sel.value == 'radio' || sel.value == 'select' || current_type == 'checkbox'){
                $("#itemAttributeValue").css("display", "block");
            }
            else{
                $("#itemAttributeValue").css("display", "none");
            }
            if(sel.value == 'file'){
                $("#isItemImg").css("display", "block");
            }
            else{
                $("#isItemImg").css("display", "none");
            }
        }
    </script>
    <!-- dynamic row -->
    <script>  
        $(document).ready(function(){  
            var i=1;  
            $('#add').click(function(){  
                i++;  
                $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="attributeValue[]" placeholder="Enter your Attribute Value" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
            });  
            $(document).on('click', '.btn_remove', function(){  
                var button_id = $(this).attr("id");   
                $('#row'+button_id+'').remove();  
            });  
            $('#submit').click(function(){            
                $.ajax({  
                        url:"name.php",  
                        method:"POST",  
                        data:$('#add_attribute_value').serialize(),  
                        success:function(data)  
                        {  
                            alert(data);  
                            $('#add_attribute_value')[0].reset();  
                        }  
                });  
            });  
        });  
    </script>

    <!-- style -->
    <style>
        /* #itemAttributeValue{
            display:none;
        } */
        .btn_remove {
            font-size: 12px;
        }
    </style>
@endsection
