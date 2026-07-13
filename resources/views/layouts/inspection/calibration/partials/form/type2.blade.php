<!-- type 2 Gauge -->
<div class="calibration-report-type-partial" id="TYPE_2">
	{{--<div class="card">
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
	</div>--}}
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
				<div class="col-12 col-sm-4">
					<div class="form-group mb-0">
						<div class="controls">
							{{ Form::label('type', 'Type', ['class' => 'form-label']) }}
							{{ Form::text('type', null, [
							'class' => 'form-control',
							'placeholder' => 'Type',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-0">
						<div class="controls">
							{{ Form::label('resolution', 'Resolution', ['class' => 'form-label']) }}
							{{ Form::text('resolution', null, [
							'class' => 'form-control',
							'placeholder' => 'Resolution',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-0">
						<div class="controls">
							{{ Form::label('read_out_unit', 'Read Out Unit', ['class' => 'form-label']) }}
							{{ Form::text('read_out_unit', null, [
							'class' => 'form-control',
							'placeholder' => 'Read Out Unit',
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
								<th class="tb-background text-center p-0 border-dark white">Instrument Reading</th>
								<th class="tb-background text-center p-0 border-dark white">Pressure Rising </th>
								<th class="tb-background text-center p-0 border-dark white">Pressure Falling</th>
								<th class="tb-background text-center p-0 border-dark white" colspan="4">ΔP Error</th>
							</tr>
							<tr>
								<th class="tb-background text-center p-0 border-dark white">bar</th>
								<th class="tb-background text-center p-0 border-dark white">bar</th>
								<th class="tb-background text-center p-0 border-dark white">bar</th>
								<th class="tb-background text-center p-0 border-dark white">Raising</th>
								<th class="tb-background text-center p-0 border-dark white">Failing</th>
							</tr>
						</thead>
						<tbody>
							@foreach(range(1, 10) as $index)
								<tr>
									@foreach(['instrument_reading', 'pressure_rising', 'pressure_falling', 'p_error_rising', 'p_error_failing'] as $inputName)
										<td class="text-center p-0 border-dark white">{{ Form::text("calibration_details[results][$index][$inputName]", null, ['class' => 'form-control cell-input', 'placeholder' => '...']) }}</td>
									@endforeach
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<div class="row" style="margin: 40px 0px;">
				<div class="col-12 col-sm-6">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('accuracy', 'Accuracy was found', ['class' => 'form-label'])
							}}
							{{ Form::text('accuracy', null, [
							'class' => 'form-control',
							'placeholder' => 'Accuracy was found',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-6">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('uncertainty', 'Uncertainty (bar)', ['class' => 'form-label'])
							}}
							{{ Form::text('uncertainty', null, [
							'class' => 'form-control',
							'placeholder' => 'Uncertainty (bar)',
							]) }}
							<div class="help-block"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>