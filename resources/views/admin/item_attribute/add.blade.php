@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.add_item_attribute') }} </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i>{{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ url('admin/itemattribute/display') }}"><i class="fa fa-list"></i>{{ trans('labels.ItemAttribute_pageTitle') }}</a></li>
                <li class="active">{{ trans('labels.add_item_attribute') }}</li>
            </ol>
        </section>

        {{-- content --}}
        <section class="content">
            {!! Form::open(['url' => 'admin/itemattribute/insert', 'method' => 'post', 'class' => 'form-horizontal form-validate', 'enctype' => 'multipart/form-data']) !!}
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
                                <h4>{{ trans('labels.item_attribute_specific') }} </h4>
                                <hr>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.itemAttributeName') }}</label>
                                    <div class="col-sm-10 col-md-4">
                                        <input type="text" name="name" id="name" placeholder="Example: Item Name" class="form-control field-validate">
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.SortOrder') }}</label>
                                    <div class="col-sm-10 col-md-4">
                                        <input type="number" name="sort_no" id="sort_no" class="form-control field-validate">
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.Header') }}</label>
                                    <div class="col-sm-10 col-md-4">
                                        <select name="header" class="form-control field-validate">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.itemAttributeHtmlType') }}</label>
                                    <div class="col-sm-10 col-md-4">
                                        <select name="html_type" class="form-control" onchange="getval(this);">
                                            <option>Please choose the html type</option>
                                            <option value="text">Text</option>
                                            <option value="date">Date</option>
                                            <option value="time">Time</option>
                                            <option value="file">File/Image</option>
                                            <option value="number">Number</option>
                                            <option value="radio">Radio</option>
                                            <option value="select">Select</option>
                                            <option value="textarea">Textarea</option>
                                            <option value="checkbox">Checkbox</option>
                                        </select>
                                        <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                    </div>
                                </div>

                                <div class="form-group" id="isItemImg">  
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.showAsItemImages') }}</label>
                                    <div class="col-sm-10 col-md-4">
                                        <select name="is_item_img" class="form-control">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>  

                                <div class="form-group" id="itemAttributeValue">  
                                    <label for="name" class="control-label col-sm-2 col-md-3">{{ trans('labels.itemAttributeValue') }}</label>
                                    <div class="col-sm-10 col-md-6">
                                        <form name="add_attribute_value" id="add_attribute_value">  
                                            <div class="table-responsive">  
                                                <table class="table" id="dynamic_field">  
                                                        <tr>  
                                                            <td><input type="text" name="attributeValue[]" placeholder="Enter your Attribute Value" class="form-control name_list" /></td>  
                                                            <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                                        </tr>  
                                                </table>  
                                            </div>  
                                        </form>  
                                    </div>
                                </div>  

                                <!-- /.box-body -->
                                <div class="box-footer text-center">
                                    <button type="submit" class="btn btn-primary">{{ trans('labels.submit') }}</button>
                                    <a href="{{ url('admin/itemattribute/display') }}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
                                </div>
                                <!-- /.box-footer -->
                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
        </section>
        {{-- end content --}}
    </div>

    <!-- script -->
    <script src="{!! asset('admin/plugins/jQuery/jQuery-2.2.0.min.js') !!}"></script>
    <script type="text/javascript">
        function getval(sel)
        {
            if(sel.value == 'radio' || sel.value == 'select' || sel.value == 'checkbox' ){
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
        #itemAttributeValue{
            display:none;
        }
        .btn_remove {
            font-size: 12px;
        }
        #isItemImg{
            display:none;
        }
    </style>
@endsection
