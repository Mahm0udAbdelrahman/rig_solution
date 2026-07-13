<!-- type 4 Torque -->
<div class="calibration-report-type-partial" id="TYPE_4">
	<!-- <div class="card">
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
	</div> -->
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
							{{ Form::label('max_permissible_error', 'Max Permissible Error', ['class' => 'form-label']) }}
							{{ Form::text('max_permissible_error', null, [
							'class' => 'form-control',
							'placeholder' => 'Max Permissible Error',
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
							{{ Form::label('device_resolution', 'Resolution', ['class' => 'form-label']) }}
							{{ Form::text('device_resolution', null, [
							'class' => 'form-control',
							'placeholder' => 'Resolution',
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

			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<h3>Calibration Methods and Standard Used</h3>
			<div class="row" style="margin: 40px 0px;">
				<div class="col-12 col-sm-6">
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
				<div class="col-12 col-sm-6">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('reported_unit', 'Reported Unit', ['class' => 'form-label']) }}
							{{ Form::text('reported_unit', null, [
							'class' => 'form-control',
							'placeholder' => 'Reported Unit',
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
			<h3>Calibration Details</h3>
			<div class="row" style="margin: 40px 0px;">
				<div class="col-12 col-sm-6">
					<div class="form-group mb-1"><div class="controls">
						{{ Form::label('calibration_show_option', 'Show Calibration Details:') }}
						{{ Form::select('calibration_details[calibration_show_option]', ['both' => 'Both', 'clockwise' => 'Clockwise', 'counter_clockwise' => 'Counter Clockwise'], null, ['class' => 'form-control', 'id' => 'calibration_show_option', 'onchange' => 'showCalibrationDetails()']) }}
						<div class="help-block"></div>
					</div>
					</div>
				</div>
			</div>
			<div class="row" style="margin: 40px 0px;">
				<div class="col-12 col-sm-12" id="clockwise-table">
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
								<th colspan="4" class="text-center tb-title-background p-0 border-dark white">Calibration in Clockwise Direction</th>
							</tr>
							<tr>
								<th class="tb-background text-center p-0 border-dark white">Applied Torque <small>(N.m.)</small></th>
								<th class="tb-background text-center p-0 border-dark white">UUT <small>(Avg.)</small></th>
								<th class="tb-background text-center p-0 border-dark white">Correction <small>(N.m.)</small></th>
								<th class="tb-background text-center p-0 border-dark white">U<small>EXP %</small></th>
							</tr>
						</thead>
						<tbody>
							@foreach(range(1, 3) as $index)
								<tr>
									@foreach(['torque', 'uut', 'correction', 'uexp'] as $inputName)
										<td class="text-center p-0 border-dark white">{{ Form::text("calibration_details[clockwise][$index][$inputName]", null, ['class' => 'form-control cell-input', 'placeholder' => '...']) }}</td>
									@endforeach
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="col-12 col-sm-12" id="counter-clockwise-table">
					<table class="table">
						<thead>
							<tr>
								<th colspan="4" class="text-center tb-title-background p-0 border-dark white">Calibration in Counter Clockwise Direction</th>
							</tr>
							<tr>
								<th class="tb-background text-center p-0 border-dark white">Applied Torque <small>(N.m.)</small></th>
								<th class="tb-background text-center p-0 border-dark white">UUT <small>(Avg.)</small></th>
								<th class="tb-background text-center p-0 border-dark white">Correction <small>(N.m.)</small></th>
								<th class="tb-background text-center p-0 border-dark white">U<small>EXP %</small></th>
							</tr>
						</thead>
						<tbody>
							@foreach(range(1, 3) as $index)
								<tr>
									@foreach(['torque', 'uut', 'correction', 'uexp'] as $inputName)
										<td class="text-center p-0 border-dark white">{{ Form::text("calibration_details[counter_clockwise][$index][$inputName]", null, ['class' => 'form-control cell-input', 'placeholder' => '...']) }}</td>
									@endforeach
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<script>
			function showCalibrationDetails() {
				const value = $('#calibration_show_option').val();
				console.log("calibration_show_option>>>>>>>>>>>",value);
				if (value === 'both') {
					$('#clockwise-table').show();
					$('#counter-clockwise-table').show();
				} else if (value === 'clockwise') {
					$('#clockwise-table').show();
					$('#counter-clockwise-table').hide();
				} else if (value === 'counter_clockwise') {
					$('#clockwise-table').hide();
					$('#counter-clockwise-table').show();
				}
			}
			showCalibrationDetails();
		</script>
	</div>
</div>