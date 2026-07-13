<div class="row page_in">
	<div class="col-12 p-0 d-flex align-items-center justify-content-center border-dark"
		style="height: 3em; background-color: blue;">
		<h1 class="text-white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			{{$custom_page_name}}
		</h1>
	</div>
</div>
<!----------------------------------->
<div class="row page_in">
	<div class="col-6 p-0">
		<h6 class="bg-dark-2 white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was
			made </h6>
		<p class="border-dark pl-1 mb-0 text-16 black">
			@if($model->job_request->client)
			{{$model->job_request->client->name}}
			@else
			{{$model->job_request->supplier->name}}
			@endif
		</p>
	</div>
	<div class="col-6 p-0">
		<h6 class="bg-dark-2 white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:
		</h6>
		<p class="border-dark pl-1 mb-0 text-16 black">{{preg_replace('~[\\\\/:*?"<>[]|]~',
				'',$model->job_request->client? $model->job_request->client->location :
				$model->job_request->supplier->location)}}</p>
	</div>
</div>
<!----------------------------------->
<div class="row">
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">
		{{$model->job_request->purchase_order}}</div>
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->job_request->code}}</div>
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Report No</h6>
	</div>

	<div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$model->id}}">{{$code}}</div>

</div>
<!----------------------------------->
<div class="row">
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Certificate issue Date</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->issue_date}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Calibration Date</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->calibration_date}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Due Date: </h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->due_date}}</div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Calibration Location and Environmental Conditions
		</h6>
	</div>
</div>
<div class="row">
	<div class="bg-dark col-4 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Address of premises where Calibration was made </h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">
		{{$model->job_request->clientDepartment ?
		$model->job_request->clientDepartment->name . ' / '.$model->job_request->deploc
		: $model->job_request->deploc}}
	</div>
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Temperature</h6>
	</div>
	<div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->temperature}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Relative Humidity:</h6>
	</div>
	<div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->relative_humidity}}</div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Details of Unit Under Calibration
		</h6>
	</div>
</div>
<div class="row">
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Equipment Description</h6>
	</div>
	<div class="col-10 p-0 pl-1 border-dark text-16 black">{{$model->equipment_description}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Manufacturer: </h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->manufacturer}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Model</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->model}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Serial Number</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->serial_number}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Range</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->range}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Type/Class</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->type}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Resolution</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->resolution}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Receipt Date: </h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$model->receipt_date}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Max Permissible Error:: </h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$model->max_permissible_error}}</div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Details of Reference Equipment Used
		</h6>
	</div>
</div>
<div class="row">
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Equipment Description</h6>
	</div>
	<div class="col-10 p-0 pl-1 border-dark text-16 black">{{$model->device_description}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Manufacturer: </h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->device_manufacturer}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Model</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->device_model}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Serial Number</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->device_serial_number}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Rang / Capacity</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->device_range}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Resolution</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->device_resolution}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Cal. Due Date </h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->device_calibration_date}}</div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Calibration Methods and Standard Used
		</h6>
	</div>
</div>
<div class="row">
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Calibration Method</h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$model->calibration_method}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Reported Unit</h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$model->reported_unit}}</div>
</div>
<!----------------------------------->
@if($model->calibration_details['calibration_show_option'] == 'both' ||
$model->calibration_details['calibration_show_option'] == 'clockwise')
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Calibration in Clockwise Direction
		</h6>
	</div>
</div>
<div class="row">
	<div class="col-12" style="padding: 0;">
		<table style="width: 100%; text-align: center;">
			<thead>
				<tr>
					@foreach(['Applied Torque <small>(N.m.)</small>', 'UUT <small>(Avg.)</small>', 'Correction
					<small>(N.m.)</small>', 'U<small>EXP %</small>'] as $header)
					<th class="bg-dark p-0 border-dark white text-center" style="width: 25%;">{!! $header !!}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach(range(1, 3) as $index)
				<tr>
					@foreach(['torque', 'uut', 'correction', 'uexp'] as $inputName)
					<td class="p-0 border-dark white text-center" style="width: 25%;">
						{{ isset($model->calibration_details['clockwise'][$index][$inputName]) ?
						$model->calibration_details['clockwise'][$index][$inputName] : 'NA' }}
					</td>
					@endforeach
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
<!----------------------------------->
@if($model->calibration_details['calibration_show_option'] == 'both' ||
$model->calibration_details['calibration_show_option']== 'counter_clockwise')
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Calibration in Counter Clockwise Direction
		</h6>
	</div>
</div>
<div class="row">
	<div class="col-12" style="padding: 0;">
		<table style="width: 100%; text-align: center;">
			<thead>
				<tr>
					@foreach(['Applied Torque <small>(N.m.)</small>', 'UUT <small>(Avg.)</small>', 'Correction
					<small>(N.m.)</small>', 'U<small>EXP %</small>'] as $header)
					<th class="bg-dark p-0 border-dark white text-center" style=" width: 25%;">{!! $header !!}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach(range(1, 3) as $index)
				<tr>
					@foreach(['torque', 'uut', 'correction', 'uexp'] as $inputName)
					<td class="p-0 border-dark white text-center" style="width: 25%;">
						{{ isset($model->calibration_details['counter_clockwise'][$index][$inputName]) ?
						$model->calibration_details['counter_clockwise'][$index][$inputName] : 'NA' }}
					</td>
					@endforeach
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Statement
		</h6>
	</div>
</div>
<div class="row">
	<div class="col-12 p-0 pl-1 border-dark black">
		{!! nl2br(e($model->statement)) !!}
	</div>
</div>