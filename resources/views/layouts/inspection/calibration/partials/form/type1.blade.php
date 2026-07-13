<!-- type 1 test -->
<div class="calibration-report-type-partial" id="TYPE_1">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-12 col-sm-6">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('contact_name', 'Contact Name', ['class' => 'form-label']) }}
							{{ Form::text('contact_name', null, [
							'class' => 'form-control',
							'placeholder' => 'Contact Name',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-6">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('contact_info', 'Contact Information', ['class' => 'form-label']) }}
							{{ Form::text('contact_info', null, [
							'class' => 'form-control',
							'placeholder' => 'Contact Information',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<h3>Details of Unit Under Calibration</h3>
			<div class="row" style="margin: 40px 0px;">
				<div class="col-12 col-sm-12">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('equipment_description', 'Equipment Description', ['class' => 'form-label'])
							}}
							{{ Form::text('equipment_description', null, [
							'class' => 'form-control',
							'placeholder' => 'Equipment Description',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('manufacturer', 'Manufacturer', ['class' => 'form-label']) }}
							{{ Form::text('manufacturer', null, [
							'class' => 'form-control',
							'placeholder' => 'Manufacturer',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-0">
						<div class="controls">
							{{ Form::label('model', 'Model', ['class' => 'form-label']) }}
							{{ Form::text('model', null, [
							'class' => 'form-control',
							'placeholder' => 'Model',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-0">
						<div class="controls">
							{{ Form::label('serial_number', 'Serial Number', ['class' => 'form-label']) }}
							{{ Form::text('serial_number', null, [
							'class' => 'form-control',
							'placeholder' => 'Serial Number',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-0">
						<div class="controls">
							{{ Form::label('code_number', 'Code', ['class' => 'form-label']) }}
							{{ Form::text('code_number', null, [
							'class' => 'form-control',
							'placeholder' => 'Code',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-0">
						<div class="controls">
							{{ Form::label('range', 'Range', ['class' => 'form-label']) }}
							{{ Form::text('range', null, [
							'class' => 'form-control',
							'placeholder' => 'Range',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<h3>Details of Reference Equipment Used</h3>
			<div class="row" style="margin: 40px 0px;">
				<div class="col-12 col-sm-12">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('device_description', 'Equipment Description', ['class' => 'form-label'])
							}}
							{{ Form::text('device_description', null, [
							'class' => 'form-control',
							'placeholder' => 'Equipment Description',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('device_manufacturer', 'Manufacturer', ['class' => 'form-label']) }}
							{{ Form::text('device_manufacturer', null, [
							'class' => 'form-control',
							'placeholder' => 'Manufacturer',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('device_model', 'Model', ['class' => 'form-label']) }}
							{{ Form::text('device_model', null, [
							'class' => 'form-control',
							'placeholder' => 'Model',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('device_serial_number', 'Serial Number', ['class' => 'form-label']) }}
							{{ Form::text('device_serial_number', null, [
							'class' => 'form-control',
							'placeholder' => 'Serial Number',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('device_range', 'Range', ['class' => 'form-label']) }}
							{{ Form::text('device_range', null, [
							'class' => 'form-control',
							'placeholder' => 'Range',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('device_calibration_date', 'cal. Due Date', ['class' => 'form-label datepicker-default']) }}
							<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="ft-calendar"></i>
								</span>
							</div>
							{{ Form::text('device_calibration_date', null, [
							'class' => 'form-control datepicker-default',
							 'placeholder' => 'cal. Due Date',
							  ]) }}
							</div>
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('certificate_number', 'Certificate Number', ['class' => 'form-label']) }}
							{{ Form::text('certificate_number', null, [
							'class' => 'form-control',
							'placeholder' => 'Certificate Number',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<h3>Calibration Methods and Standard Used</h3>
			<div class="row" style="margin: 40px 0px;">
				<div class="col-12 col-sm-12">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('calibration_method', 'Calibration Method', ['class' => 'form-label'])
							}}
							{{ Form::text('calibration_method', null, [
							'class' => 'form-control',
							'placeholder' => 'Calibration Method',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<h3>Calibration Test Results</h3>
			<div class="row" style="margin: 40px 0px;">
				<div class="col-12 col-sm-12">
					<style>
						.tb-title-background {
							background-color: rgba(142,169,231,255);
						}
						.tb-background {
							background-color: rgba(198,212,244,255);
						}
					</style>
					<table class="table" style="text-align: center;">
						<thead>
							<tr>
								<th class="tb-title-background text-center p-0 border-dark white" colspan="8">Test Parameter</th>
							</tr>
							<tr>
								<th class="tb-background text-center p-0 border-dark white" rowspan="2">Test Fluid</th>
								<th class="tb-background text-center p-0 border-dark white" rowspan="2">Required Test Pressure</th>
								<th class="tb-background text-center p-0 border-dark white" rowspan="2">Unit</th>
								<th class="tb-background text-center p-0 border-dark white" rowspan="2">Actual Test</th>
								<th class="tb-background text-center p-0 border-dark white" rowspan="2">Test Fluid Temperature</th>
								<th class="tb-background text-center p-0 border-dark white" colspan="3">Test Time</th>
							</tr>
							<tr>
								<th class="tb-background text-center p-0 border-dark white">start</th>
								<th class="tb-background text-center p-0 border-dark white">end</th>
								<th class="tb-background text-center p-0 border-dark white">duration</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								@foreach(['test_fluid', 'required_test_pressure', 'unit', 'actual_test', 'test_fluid_temperature', 'test_time_start', 'test_time_end', 'test_time_duration'] as $inputName)
									<td class="text-center p-0 border-dark white">{{ Form::text("calibration_details[test_parameters][$inputName]", null, ['class' => 'form-control cell-input', 'placeholder' => '...']) }}</td>
								@endforeach
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row" style="margin: 40px 0px;">
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('test_result', 'Test Result Condition') }}
							{{ Form::select('calibration_details[result_condition]', ['pass' => 'Pass', 'fail' => 'Fail'], null, ['class' => 'form-control']) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('test_result', 'Loss') }}
							{{ Form::text('calibration_details[result_loss]', null, ['class' => 'form-control']) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('test_result', 'Loss Amount') }}
							{{ Form::text('calibration_details[result_loss_amount]', null, ['class' => 'form-control']) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>