<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="editManufacturerLabel">{{ trans('labels.EditBranch') }}</h4>
</div>

{!! Form::open(array('url' =>'admin/merchants/updateBranch', 'name'=>'editBranchFrom', 'id'=>'editBranchFrom', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
{!! Form::hidden('user_id', $data['user_id'], array('class'=>'form-control')) !!}
{!! Form::hidden('branch_id', $data['merchant_branch'][0]->id, array('class'=>'form-control')) !!}
<div class="modal-body">
    <div class="form-group">
        <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.MerchantName') }}</label>
        <div class="col-sm-10 col-md-8">
            {!! Form::text('merchant_name', $data['merchant_branch'][0]->merchant_name, array('class'=>'form-control field-validate', 'id'=>'entry_merchant_name')) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.MerchantSlug') }}</label>
        <div class="col-sm-10 col-md-8">
            {!! Form::text('slug', $data['merchant_branch'][0]->slug, array('class'=>'form-control field-validate', 'id'=>'entry_merchant_slug')) !!}
        </div>
    </div>

    <div class="form-group">
        <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.EmailAddress') }} </label>
        <div class="col-sm-10 col-md-8">
            {!! Form::text('merchant_email', $data['merchant_branch'][0]->merchant_email, array('class'=>'form-control field-validate', 'id'=>'entry_merchant_email')) !!}
        </div>
    </div>

    <div class="form-group">
        <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.MerchantContactNumber') }}</label>
        <div class="col-sm-10 col-md-8">
            {!! Form::text('merchant_phone_no', $data['merchant_branch'][0]->merchant_phone_no, array('class'=>'form-control field-validate', 'id'=>'entry_phone')) !!}
        </div>
    </div>

    <div class="form-group">
        <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.MerchantPayment') }}</label>
        <div class="col-sm-10 col-md-8">
            {!! Form::text('merchant_payment', $data['merchant_branch'][0]->merchant_payment, array('class'=>'form-control field-validate', 'id'=>'entry_payment')) !!}
        </div>
    </div>

    <div class="form-group">
        <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.State') }}</label>
        <div class="col-sm-10 col-md-8">
            <select id="choose_state_id" class="form-control field-validate" name="state_id">
                @foreach($data['state'] as $state_data)
                <option @if($data['merchant_branch'][0]->state_id == $state_data->state_id )
                    selected
                    @endif
                    value="{{ $state_data->state_id }}">{{ $state_data->state_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.City') }}</label>
        <div class="col-sm-10 col-md-8">
            <select id="city_id" class="form-control cityContent field-validate" name="city_id">
                @foreach($data['city'] as $city_data)
                <option @if($data['merchant_branch'][0]->city_id == $city_data->city_id )
                    selected
                    @endif
                    value="{{ $city_data->city_id }}">{{ $city_data->city_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Status') }}</label>
        <div class="col-sm-10 col-md-8">
            <select id="is_default" class="form-control" name="is_default">
                <option @if($data['merchant_branch'][0]->is_default == 0 )
                    selected
                    @endif
                    value="0">No</option>
                <option @if($data['merchant_branch'][0]->is_default == 1 )
                    selected
                    @endif
                    value="1">Yes</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
    <button type="button" class="btn btn-primary form-validate" id="updateBranch">{{ trans('labels.Submit') }}</button>
</div>
{!! Form::close() !!}
