<div class="row page_in">
	<div class="col-12 p-0 d-flex align-items-center justify-content-center border-dark"
		style="height: 3em; background-color: blue;">
		<h1 class="text-white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Calibration/Test Certificate
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
		<h6 class="white text-bold-600 pl-1 mb-0">Date of issue</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->issue_date}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Contact Name</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->contact_name}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Contact information </h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->contact_info}}</div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Test/Calibration Location and Environmental Conditions
		</h6>
	</div>
</div>
<div class="row">
	<div class="bg-dark col-4 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Location of where Calibration made </h6>
	</div>
	<div class="col-8 p-0 pl-1 border-dark text-16 black">
		{{$model->job_request->clientDepartment ?
		$model->job_request->clientDepartment->name . ' / '.$model->job_request->deploc
		: $model->job_request->deploc}}
	</div>
	<div class="bg-dark col-4 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Tempreture & Relative Humidity</h6>
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
			Details of Unit Under Test/Calibration
		</h6>
	</div>
</div>
<div class="row">
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Equipment Description</h6>
	</div>
	<div class="col-6 p-0 pl-1 border-dark text-16 black">{{$model->equipment_description}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Serial Number</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->serial_number}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Manufacturer: </h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->manufacturer}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Model</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->model}}</div>

	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Code</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->code_number}}</div>
</div>
<!----------------------------------->

<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Test/Calibration Methods and Standard Used
		</h6>
	</div>
</div>
<div class="row">
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0">Test Method</h6>
	</div>
	<div class="col-10 p-0 pl-1 border-dark text-16 black">{{$model->calibration_method}}</div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Traceability
		</h6>
	</div>
</div>
<div class="row">
	<div class="col-12" style="padding: 0;">
		<table style="width: 100%; text-align: center;">
			<thead>
				<tr>
					@foreach(['Reference Device', 'Serial Number', 'Calibration Date', 'Certificate Number','Expanded uncertainty', 'Traceability'] as $title)
					@endforeach
					<th class="bg-dark p-0 border-dark white text-center" style="width: 40%;">Reference Device</th>
					<th class="bg-dark p-0 border-dark white text-center" style="width: 8%;">Serial Number</th>
					<th class="bg-dark p-0 border-dark white text-center" style="width: 8%;">Calibration Date</th>
					<th class="bg-dark p-0 border-dark white text-center" style="width: 30%;">Certificate Number</th>
					<th class="bg-dark p-0 border-dark white text-center" style="width: 7%;">Expanded uncertainty</th>
					<th class="bg-dark p-0 border-dark white text-center" style="width: 7%;">Traceability</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					@foreach(['reference_device', 'serial_number', 'calibration_date', 'certificate_number','expanded_uncertainty', 'traceability'] as $inputName)
						<td class="p-0 border-dark white text-center" style="height: 7em;">{{ isset($model->calibration_details['traceability'][$inputName]) ? $model->calibration_details['traceability'][$inputName] : 'NA'}}</td>
					@endforeach
				</tr>
			</tbody>
		</table>
	</div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark-2 p-0 d-flex align-items-center justify-content-center border-dark">
		<h6 class="white text-bold-600 text-center mb-0 mt-auto mb-auto"
			style="display: flex; align-items: center; justify-content: center;">
			Test/Calibration Results
		</h6>
	</div>
</div>
<div class="row">
	<div class="col-12 p-0 pl-1 border-dark black" style="min-height: 5em;">
		{!! isset($model->calibration_details['calibration_result']) ? $model->calibration_details['calibration_result'] : '' !!}
	</div>
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
	<div class="col-12 p-0 pl-1 border-dark black" style="min-height: 12em;">
		{!! nl2br(e($model->statement)) !!}
	</div>
</div>