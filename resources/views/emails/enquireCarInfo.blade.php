<div style="width: 100%; display:block;">

<p>
	<strong>
   		Hi {{ $data['car']->merchant_name }}!
   	</strong>
	<br><br>

	<strong>
	*****************<br/>
	Customer Detail    <br/>
	*****************<br/>
	</strong>
	<table>
		<tbody>
			<tr>
				<td style="width:50%">Member Name</td>
				<td>:</td>
				<td>{{ $data['member_name']}}</td>
			</tr>
			<tr>
				<td>Member Email</td>
				<td>:</td>
				<td>{{ $data['member_email'] }}</td>
			</tr>
			<tr>
				<td>Member Contact</td>
				<td>:</td>
				<td>{{ $data['member_contact'] }}</td>
			</tr>
		</tbody>
	</table>
	<br>

	<strong>
	*****************<br/>
	Car Detail    <br/>
	*****************<br/>
	</strong>
	<table>
		<tbody>
			<tr>
				<td style="width:50%">Vehicle Identification Number</td>
				<td>:</td>
				<td>{{ $data['car']->vim }}</td>
			</tr>
			<tr>
				<td>Stock</td>
				<td>:</td>
				<td>{{ $data['car']->stock_number }}</td>
			</tr>
			<tr>
				<td>Model</td>
				<td>:</td>
				<td>{{ $data['car']->model_name }}</td>
			</tr>
			<tr>
				<td>Make</td>
				<td>:</td>
				<td>{{ $data['car']->make_name }}</td>
			</tr>
			<tr>
				<td>Status</td>
				<td>:</td>
				<td>@if($data['car']->status==1) New @elseif($data['car']->status==2) Used @endif</td>
			</tr>
			<tr>
				<td>Price</td>
				<td>:</td>
				<td>{{ $data['car']->price }}</td>
			</tr>
		</tbody>
	</table>
	<br>

	<br><br>
	Please help this customer to enquire the car.
	<br/><br/>
	<strong>{{ trans('labels.Sincerely') }},</strong>
	<br>
	Tanya-je Team
</p>
</div>