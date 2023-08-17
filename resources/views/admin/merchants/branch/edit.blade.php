@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.EditSaleAdvisor') }} <small>{{ trans('labels.EditSaleAdvisor') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li><a href="{{ URL::to('admin/merchants/branch/display/*')}}"><i class="fa fa-users"></i> {{ trans('labels.SaleAdvisor') }}</a></li>
      <li class="active">{{ trans('labels.EditSaleAdvisor') }}</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Info boxes -->

    <!-- /.row -->

    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <!-- <div class="box-header">
            <h3 class="box-title">{{ trans('labels.addmerchants') }} </h3>
          </div> -->

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
                            {!! Form::open(array('url' =>'admin/addnewmerchant', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                            <input class="form-control" id="merchant_name" name="user_id" type="hidden" value="">
                            <h4>{{ trans('labels.SaleAdvisorInfo') }}</h4>
                            <hr>
                            <div class="form-group">
                                <label for="profile_photo" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.ProfilePhoto') }}</label>
                                <div class="col-sm-10 col-md-3">
                                    <img src="" alt="profile photo" />
                                </div>
                                <label for="update_profile_photo" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.UpdateProfilePhoto') }}</label>
                                <div class="col-sm-10 col-md-3">
                                    <input type="file" name="profile_photo" id="profile_photo" class="form-control">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.UploadProfilePhotoText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.SaleAdvisorName') }}</label>
                                <div class="col-sm-10 col-md-3">
                                    {!! Form::text('name',  '', array('class'=>'form-control field-validate', 'id'=>'name')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorNameText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                                <label for="position" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.SaleAdvisorPosition') }}</label>
                                <div class="col-sm-10 col-md-3">
                                    {!! Form::text('position',  '', array('class'=>'form-control field-validate', 'id'=>'position')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorPositionText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contact" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.SaleAdvisorContactNo') }}</label>
                                <div class="col-sm-10 col-md-3">
                                    <input type="text" name="contact" id="contact" class="form-control field-validate" onInput="edValueKeyPress()">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SaleAdvisorContactNoText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                                <label for="whatsapp_url" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.WhatsappUrl') }}</label>
                                <div class="col-sm-10 col-md-3">
                                    <span class="form-control" id="lblValue" style="height: 60px;">Link generated based on contact number</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="verification" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.Verification') }}</label>
                                <div class="col-sm-10 col-md-3">
                                    <select class="form-control" name="verification">
                                        <option value="N/A">N/A</option>
                                        <option value="1">Verified</option>
                                        <option value="0">Unverified</option>
                                    </select>
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.VerificationText') }}</span>
                                </div>
                                <label for="waze_url" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.WazeUrl') }}</label>
                                <div class="col-sm-10 col-md-3">
                                    <span class="form-control" id="lblValue" style="height: 60px;">Link generated based on organisation address</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="verification" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.VerifiedSince') }}</label>
                                <div class="col-sm-10 col-md-3">
                                    <input type="date" class="form-control" name="verified_since">
                                </div>
                                <label for="address" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.Address') }}</label>
                                <div class="col-sm-10 col-md-3">
                                    {!! Form::text('address',  '', array('class'=>'form-control', 'id'=>'address')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.AddressText') }}</span>
                                    <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="waze_url" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.VerifiedUntil') }}</label>
                                <div class="col-sm-10 col-md-3">
                                    <input type="date" class="form-control" name="verified_until">
                                </div>
                            </div>

                            <br>
                            <h4>{{ trans('labels.LandingPage') }}</h4>
                            <hr>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="generate_qr" class="col-sm-2 col-md-6 control-label" style="">{{ trans('labels.GenerateQRCode') }}</label>
                                        <div class="col-sm-10 col-md-6">
                                            <label class=" control-label">
                                                <input type="radio" name="generate_qr" value="1" class="flat-red"> &nbsp;{{ trans('labels.Yes') }}
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                            <label class=" control-label">
                                                <input type="radio" name="generate_qr" value="0" class="flat-red">  &nbsp;{{ trans('labels.No') }}
                                            </label>
                                            <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to generate QR Code or not.</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="generate_qr" class="col-sm-2 col-md-6 control-label" style="">{{ trans('labels.QRCode') }}</label>
                                        <div class="col-sm-10 col-md-6">
                                            <label class=" control-label">
                                                <input type="radio" name="generate_qr" value="1" class="flat-red"> &nbsp;{{ trans('labels.Yes') }}
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                            <label class=" control-label">
                                                <input type="radio" name="generate_qr" value="0" class="flat-red">  &nbsp;{{ trans('labels.No') }}
                                            </label>
                                            <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display QR Code or not.</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="contactMe" class="col-sm-2 col-md-6 control-label" style="">{{ trans('labels.ContactMe') }}</label>
                                        <div class="col-sm-10 col-md-6">
                                            <label class=" control-label">
                                                <input type="radio" name="contactMe" value="1" class="flat-red"> &nbsp;{{ trans('labels.Yes') }}
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                            <label class=" control-label">
                                                <input type="radio" name="contactMe" value="0" class="flat-red">  &nbsp;{{ trans('labels.No') }}
                                            </label>
                                            <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Contact Me or not.</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="showMe" class="col-sm-2 col-md-6 control-label" style="">{{ trans('labels.ShowMe') }}</label>
                                        <div class="col-sm-10 col-md-6">
                                            <label class=" control-label">
                                                <input type="radio" name="showMe" value="1" class="flat-red"> &nbsp;{{ trans('labels.Yes') }}
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                            <label class=" control-label">
                                                <input type="radio" name="showMe" value="0" class="flat-red">  &nbsp;{{ trans('labels.No') }}
                                            </label>
                                            <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Show Me or not.</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="askMe" class="col-sm-2 col-md-6 control-label" style="">{{ trans('labels.AskMe') }}</label>
                                        <div class="col-sm-10 col-md-6">
                                            <label class=" control-label">
                                                <input type="radio" name="askMe" value="1" class="flat-red"> &nbsp;{{ trans('labels.Yes') }}
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                            <label class=" control-label">
                                                <input type="radio" name="askMe" value="0" class="flat-red">  &nbsp;{{ trans('labels.No') }}
                                            </label>
                                            <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Ask Me or not.</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="keepMe" class="col-sm-2 col-md-6 control-label" style="">{{ trans('labels.KeepMe') }}</label>
                                        <div class="col-sm-10 col-md-6">
                                            <label class=" control-label">
                                                <input type="radio" name="keepMe" value="1" class="flat-red"> &nbsp;{{ trans('labels.Yes') }}
                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                            <label class=" control-label">
                                                <input type="radio" name="keepMe" value="0" class="flat-red">  &nbsp;{{ trans('labels.No') }}
                                            </label>
                                            <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Choose option to display Keep Me or not.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="whatsappDefaultMessage" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.WhatsappDefaultMessage') }}</label>
                                        <div class="col-sm-10 col-md-7">
                                            <textarea name="whatsappDefaultMessage" id="whatsappDefaultMessage" class="form-control">Hi {advisor name}, I would like to enquire regarding</textarea>
                                            <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.WhatsappDefaultMessageText') }}</span>
                                            <span class="help-block hidden">{{ trans('labels.textRequiredFieldMessage') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="list_file" class="col-sm-2 col-md-3 control-label" style="">{{ trans('labels.ListOfFiles') }}</label>
                                        <div class="col-sm-10 col-md-7">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>{{ trans('labels.Name') }}</th>
                                                    <th>{{ trans('labels.Size') }}</th>
                                                    <th>{{ trans('labels.Type') }}</th>
                                                    <th>{{ trans('labels.Action') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody class="contentAttribute">
                                                    <tr>
                                                        <td colspan="4">{{ trans('labels.NoRecordFound') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h4>{{ trans('labels.Statistics') }}</h4>
                            <hr>
                            <div class="form-group">
                                <label for="verification" class="col-sm-2 col-md-2 control-label" style="">{{ trans('labels.FilterBy') }}</label>
                                <div class="col-sm-10 col-md-2">
                                    <select class="form-control" name="verification">
                                        <option value="">Today</option>
                                        <option value="">Yesterday</option>
                                        <option value="">This Week</option>
                                        <option value="">This Month</option>
                                        <option value="">7 Days</option>
                                        <option value="">30 Days</option>
                                        <option value="">Custom Date Range</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="verification" class="col-sm-2 col-md-2 control-label" style=""></label>
                                <div class="col-sm-10 col-md-8">
                                    <canvas id="myChart" width="400" height="400"></canvas>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="verification" class="col-sm-2 col-md-2 control-label" style=""></label>
                                <div class="col-sm-10 col-md-8">
                                    <table id="example1" class="table table-bordered table-striped" style="text-align:center;">
                                        <thead>
                                        <tr>
                                            <th style="vertical-align:top;">Pageview</th>
                                            <th style="vertical-align:top;">Call</th>
                                            <th style="vertical-align:top;">Whastapp</th>
                                            <th style="vertical-align:top;">Showroom Location</th>
                                            <th style="vertical-align:top;">Brochure</th>
                                            <th style="vertical-align:top;">Price List</th>
                                            <th style="vertical-align:top;">Promotion</th>
                                            <th style="vertical-align:top;">Conversion (Promotion Redemption)</th>
                                            <th style="vertical-align:top;">Consersion (Actions)</th>
                                        </tr>
                                        </thead>
                                        <tbody class="contentAttribute">
                                            <td>8</td>
                                            <td>9</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>8</td>
                                            <td>9</td>
                                            <td>3</td>
                                            <td>5</td>
                                            <td>0</td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            

                            <!-- /.box-body -->
                            <div class="box-footer text-center">
                            <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                            <a href="{{ url()->previous() }}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
    $('.multi-field-wrapper').each(function() {
        var $wrapper = $('.multi-fields', this);
        $(".add-field", $(this)).click(function(e) {
            $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
        });
        $('.multi-field .remove-field', $wrapper).click(function() {
            if ($('.multi-field', $wrapper).length > 1)
                $(this).parent('.multi-field').remove();
        });
    });
    function edValueKeyPress() {
        var edValue = document.getElementById("contact");
        var s = edValue.value;

        var lblValue = document.getElementById("lblValue");
        lblValue.innerText = "https://wa.me/" + s;
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script> 
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['pageview', 'Call', 'Whatsapp', 'Showroom Location', 'Brochure', 'Price List', 'Promotion', 'Conversion (promotion redemption)', 'Convertion (action)'],
        datasets: [{
            label: '# of data',
            data: [8, 9, 4, 5, 8, 9, 3, 5, 0],
            backgroundColor: [
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(54, 162, 235, 0.2)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: 'day'
                }
            }
        }
    }
});
</script>
<style>
    #myChart {
        height: 500px !important;
        width: 700px !important;
    }
</style>
@endsection
