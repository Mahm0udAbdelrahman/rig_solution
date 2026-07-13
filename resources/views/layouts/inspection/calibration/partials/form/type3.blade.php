<!-- TYPE 3 Yoke -->
<div class="calibration-report-type-partial" id="TYPE_3">
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
			<h3>Details of Unit Under Test/Calibration</h3>
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

			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<h3>Test/Calibration Methods and Standard Used</h3>
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
			<h3>Calibration Details</h3>
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
								<th colspan="6" class="text-center tb-title-background p-0 border-dark white">Traceability</th>
							</tr>
							<tr>
								@foreach(['Reference Device', 'Serial Number', 'Calibration Date', 'Certificate Number','Expanded uncertainty', 'Traceability'] as $title)
									<th class="tb-background text-center p-0 border-dark white">{{ $title }}</td>
								@endforeach
							</tr>
						</thead>
						<tbody>
							<tr>
								@foreach(['reference_device', 'serial_number', 'calibration_date', 'certificate_number','expanded_uncertainty', 'traceability'] as $inputName)
									<td class="text-center p-0 border-dark white">{{ Form::text("calibration_details[traceability][$inputName]", null, ['class' => 'form-control cell-input', 'placeholder' => '...']) }}</td>
								@endforeach
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row" style="margin: 40px 0px;">
				<div class="col-12 col-sm-12">
					<div class="form-group mb-1">
						<div class="controls">
							{{ Form::label('calibration_details[calibration_result]', 'Test/Calibration Results', ['class' => 'form-label'])
							}}
							{{ Form::text('calibration_details[calibration_result]', null, [
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
</div>