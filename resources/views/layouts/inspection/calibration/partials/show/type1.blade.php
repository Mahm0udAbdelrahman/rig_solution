<div class="row page_in">
	<div class="col-12 p-0 d-flex align-items-center justify-content-center border-dark"
		style="height: 3em; background-color: blue;">
		<h1 class="text-white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Calibration / Test Certificate
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
		<h6 class="white text-bold-600 pl-1 mb-0">Receipt Date</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->receipt_date}}</div>

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
<div class="row">
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Date of Issue</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->issue_date}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Contact Name</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->contact_name}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Contact Information</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->contact_info}}</div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Test Location, Address and Environmental Conditions
		</h6>
	</div>
</div>
<div class="row">
	<div class="bg-dark col-4 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Location of where Test made </h6>
	</div>
	<div class="col-8 p-0 pl-1 border-dark text-16 black">
		{{$model->job_request->clientDepartment ?
		$model->job_request->clientDepartment->name . ' / '.$model->job_request->deploc
		: $model->job_request->deploc}}
	</div>
	<div class="bg-dark col-4 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Temperature & Relative Humidity</h6>
	</div>
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Temperature</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->temperature}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Relative Humidity:</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->relative_humidity}}</div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Details of Unit Under Test
		</h6>
	</div>
</div>
<div class="row">
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Equipment Description</h6>
	</div>
	<div class="col-10 p-0 pl-1 border-dark text-16 black">{{$model->equipment_description}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Code Number</h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$model->code_number}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Serial Number</h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$model->serial_number}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Manufacturer: </h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->manufacturer}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Model</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->model}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Range</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->range}}</div>
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
		<h6 class="white text-bold-600 pl-1 mb-0">Device Description </h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$model->device_description}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Serial Number</h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$model->device_serial_number}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Manufacturer: </h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->device_manufacturer}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Model</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->device_model}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Rang</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->device_range}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Calibration Date</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->device_calibration_date}}</div>
	
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Certificate Number </h6>
	</div>
	<div class="col-6 p-0 pl-1 border-dark text-16 black">{{$model->certificate_number}}</div>
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
	<div class="col-10 p-0 pl-1 border-dark text-16 black">{{$model->calibration_method}}</div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Test Parameter
		</h6>
	</div>
</div>
<div class="row">
	<table class="table m-0" style="text-align: center;">
		<thead>
			<tr style="vertical-align: middle;">
				<th class="bg-dark p-0 border-dark white text-center" rowspan="2" style="vertical-align: middle; width: 14%;">Test Fluid</th>
				<th class="bg-dark p-0 border-dark white text-center" rowspan="2" style="vertical-align: middle; width: 15%;">Required Test Pressure</th>
				<th class="bg-dark p-0 border-dark white text-center" rowspan="2" style="vertical-align: middle; width: 13%;">Unit</th>
				<th class="bg-dark p-0 border-dark white text-center" rowspan="2" style="vertical-align: middle; width: 13%;">Actual Test</th>
				<th class="bg-dark p-0 border-dark white text-center" rowspan="2" style="vertical-align: middle; width: 15%;">Test Fluid Temperature</th>
				<th class="bg-dark p-0 border-dark white text-center" colspan="3" style="vertical-align: middle; width: 30%;">Test Time</th>
			</tr>
			<tr style="vertical-align: middle;">
				<th class="bg-dark p-0 border-dark white text-center" style="vertical-align: middle; width: 10%;">start</th>
				<th class="bg-dark p-0 border-dark white text-center" style="vertical-align: middle; width: 10%;">end</th>
				<th class="bg-dark p-0 border-dark white text-center" style="vertical-align: middle; width: 10%;">duration</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				@foreach(['test_fluid', 'required_test_pressure', 'unit', 'actual_test', 'test_fluid_temperature', 'test_time_start', 'test_time_end', 'test_time_duration'] as $inputName)
					<td class="p-0 border-dark white text-center">
						{{ isset($model->calibration_details['test_parameters'][$inputName]) ? $model->calibration_details['test_parameters'][$inputName] : 'NA' }}
					</td>
				@endforeach
			</tr>
		</tbody>
	</table>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Pressure Test Result / Conclusion
		</h6>
	</div>
</div>
<div class="row">
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Pass or Fail</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->calibration_details['result_condition']}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Loss</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->calibration_details['result_loss'] ? $model->calibration_details['result_loss'] : 'NA'}}</div>
	
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Loss Amount</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->calibration_details['result_loss_amount'] ? $model->calibration_details['result_loss_amount'] : 'None'}}</div>
</div>

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