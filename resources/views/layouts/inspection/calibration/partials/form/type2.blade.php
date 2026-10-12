<!-- type 2 Gauge -->
@php
	$isNew = !isset($model);
	$PG = \App\Models\Inspection\Calibration\CalibrationPressureGauge::class;
	$currentUnit = $isNew ? 'psi' : $model->read_out_unit;
	$currentDigital = $isNew ? 'Analog' : $model->digital_analog;
	$currentMethod = $isNew ? $PG::CALIBRATION_METHODS['Pressure'] : $model->calibration_method;
	$methodOptions = [];
	foreach ($PG::CALIBRATION_METHODS as $label => $text) {
		$methodOptions[$text] = $label . ' — ' . (str_contains($text, 'DKD-R6-2') ? 'DKD-R6-2:2014' : 'DKD-R6-1:2014') . ', RS-WP-401';
	}
	if ($currentMethod && !isset($methodOptions[$currentMethod])) {
		$methodOptions = [$currentMethod => \Illuminate\Support\Str::limit(str_replace("\n", ' ', $currentMethod), 90)] + $methodOptions;
	}
	$resultRows = 11;
@endphp
<style>
	.pg-results th { background-color: #d9d9d9; color: #000; vertical-align: middle !important; font-size: .85rem; }
	.pg-results td { padding: 0 !important; vertical-align: middle !important; }
	.pg-results .cell-input { text-align: center; height: 2.1rem; }
	.pg-results .cell-input.calc { background: #f4f5fa; }
	.pg-results .unit-row th { background-color: #ffff00; }
	.pg-summary span { font-weight: 700; }
	/* the 3 drop-downs are highlighted like the input cells of the Excel data entry sheet */
	select.pg-excel-list, select.pg-excel-list:focus { background-color: #ffc000 !important; color: #000 !important; font-weight: 600; border-color: #d39e00; }
	select.pg-excel-list option { background: #fff; }
</style>
<div class="calibration-report-type-partial" id="TYPE_2">
	<div class="card">
		<div class="card-body">
			<h3>Equipment Under Calibration</h3>
			<div class="row mt-2">
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('equipment_description', 'Description') }}
						{{ Form::text('equipment_description', $isNew ? 'Pressure Gauge' : null, ['class' => 'form-control', 'placeholder' => 'Pressure Gauge']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('serial_number', 'Serial No') }}
						{{ Form::text('serial_number', null, ['class' => 'form-control', 'placeholder' => 'Serial No']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('code_number', 'Asset Number') }}
						{{ Form::text('code_number', $isNew ? 'N/A' : null, ['class' => 'form-control', 'placeholder' => 'Asset Number']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('manufacturer', 'Manufacturer') }}
						{{ Form::text('manufacturer', null, ['class' => 'form-control', 'placeholder' => 'Manufacturer']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('model', 'Model') }}
						{{ Form::text('model', $isNew ? 'N/A' : null, ['class' => 'form-control', 'placeholder' => 'Model']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('digital_analog', 'Digital Or Analog') }}
						{{ Form::select('digital_analog', $PG::selectOptions($PG::DIGITAL_ANALOG, $currentDigital), $currentDigital, ['class' => 'form-control pg-excel-list']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('read_out_unit', 'Unit') }}
						{{ Form::select('read_out_unit', $PG::selectOptions($PG::UNITS, $currentUnit), $currentUnit, ['class' => 'form-control pg-excel-list', 'id' => 'pg_unit']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<label>Device Range</label>
						<div class="input-group">
							{{ Form::text('range_from', $isNew ? '0' : null, ['class' => 'form-control', 'id' => 'pg_range_from', 'placeholder' => 'From']) }}
							<div class="input-group-append input-group-prepend"><span class="input-group-text">TO</span></div>
							{{ Form::text('range_to', null, ['class' => 'form-control', 'id' => 'pg_range_to', 'placeholder' => 'To']) }}
						</div>
						@if(!$isNew && $model->range && !$model->range_to)
							<small class="text-muted">Old value: {{ $model->range }}</small>
						@endif
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('resolution', 'Resolution') }}
						{{ Form::text('resolution', null, ['class' => 'form-control', 'placeholder' => '10.0']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('type', 'Accuracy Class (%)') }}
						{{ Form::text('type', null, ['class' => 'form-control', 'id' => 'pg_accuracy_class', 'placeholder' => '1.0%']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('unit_segment_location', 'Unit Segment Location') }}
						{{ Form::text('unit_segment_location', $isNew ? 'N/A' : null, ['class' => 'form-control', 'placeholder' => 'N/A']) }}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<h3>Reference Standards Used</h3>
			<div class="row mt-2">
				<div class="col-12">
					<div class="form-group mb-1">
						<label for="reference_standard_picker">Select Reference Standard (Equipment Controlled List)</label>
						@include('layouts.repeated.equipment_picker', [
							'id' => 'reference_standard_picker',
							'fill' => [
								'equipment_no' => '#device_serial_number',
								'equipment_description' => '#device_description',
								'manufacturer' => '#device_manufacturer',
								'model_type' => '#device_model',
								'capacity_range' => '#device_range',
								'calibration_due_date' => '#device_calibration_date',
							],
						])
						<small class="text-muted">Picking an item fills the fields below from the register. Choose "Other (enter manually)" for equipment that is not in the register.</small>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('device_serial_number', 'Serial No.') }}
						{{ Form::text('device_serial_number', null, ['class' => 'form-control', 'id' => 'device_serial_number', 'placeholder' => 'Serial No.']) }}
					</div>
				</div>
				<div class="col-12 col-sm-8">
					<div class="form-group mb-1">
						{{ Form::label('device_description', 'Description') }}
						{{ Form::text('device_description', null, ['class' => 'form-control', 'id' => 'device_description', 'placeholder' => 'USP Pressure Transmitter']) }}
					</div>
				</div>
				<div class="col-12 col-sm-3">
					<div class="form-group mb-1">
						{{ Form::label('device_manufacturer', 'Manufacturer') }}
						{{ Form::text('device_manufacturer', null, ['class' => 'form-control', 'id' => 'device_manufacturer', 'placeholder' => 'Manufacturer']) }}
					</div>
				</div>
				<div class="col-12 col-sm-3">
					<div class="form-group mb-1">
						{{ Form::label('device_model', 'Model') }}
						{{ Form::text('device_model', null, ['class' => 'form-control', 'id' => 'device_model', 'placeholder' => 'Model']) }}
					</div>
				</div>
				<div class="col-12 col-sm-3">
					<div class="form-group mb-1">
						{{ Form::label('device_range', 'Range') }}
						{{ Form::text('device_range', null, ['class' => 'form-control', 'id' => 'device_range', 'placeholder' => '0 - 15000 Psi']) }}
					</div>
				</div>
				<div class="col-12 col-sm-3">
					<div class="form-group mb-1">
						<label for="device_calibration_date">Due Date</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="ft-calendar"></i></span>
							</div>
							{{ Form::text('device_calibration_date', null, ['class' => 'form-control datepicker-default', 'id' => 'device_calibration_date', 'placeholder' => 'Due Date']) }}
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('certificate_number', 'Certificate No.') }}
						{{ Form::text('certificate_number', null, ['class' => 'form-control', 'id' => 'certificate_number', 'placeholder' => 'Certificate No.']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('device_traceability', 'Traceability') }}
						{{ Form::text('device_traceability', $isNew ? 'NIS' : null, ['class' => 'form-control', 'id' => 'device_traceability', 'placeholder' => 'NIS']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('device_accuracy', 'Accuracy (%)') }}
						{{ Form::text('device_accuracy', null, ['class' => 'form-control', 'id' => 'device_accuracy', 'placeholder' => '0.10%']) }}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<h3>Calibration Method</h3>
			<div class="row mt-2">
				<div class="col-12">
					<div class="form-group mb-1">
						{{ Form::select('calibration_method', $methodOptions, $currentMethod, ['class' => 'form-control pg-excel-list', 'id' => 'pg_calibration_method']) }}
						<small class="text-muted d-block mt-50" id="pg_calibration_method_text" style="white-space: pre-line;"></small>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<h3>Calibration Results</h3>
			<div class="row mt-2">
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<label>Permissible error (±) <small class="text-muted">auto = accuracy class × range</small></label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">±</span></div>
							{{ Form::text('max_permissible_error', null, ['class' => 'form-control', 'id' => 'pg_permissible_error', 'placeholder' => '58']) }}
							<div class="input-group-append"><span class="input-group-text js-pg-unit">Bar</span></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<label>Expanded Uncertainty (±)</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">±</span></div>
							{{ Form::text('uncertainty', null, ['class' => 'form-control', 'placeholder' => '33.35']) }}
							<div class="input-group-append"><span class="input-group-text js-pg-unit">Bar</span></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4 pg-summary pt-2">
					Max correction &mdash; Rising: <span id="pg_max_rising">-</span> | Falling: <span id="pg_max_falling">-</span>
				</div>
				<div class="col-12">
					<table class="table table-bordered pg-results text-center mb-0">
						<thead>
							<tr>
								<th>Measured Current Value<br>UUC</th>
								<th>Raising Value<br>REF</th>
								<th>Correction<br><small>(auto)</small></th>
								<th>Falling Value<br>REF</th>
								<th>Correction<br><small>(auto)</small></th>
							</tr>
							<tr class="unit-row">
								<th class="js-pg-unit">Bar</th>
								<th colspan="2" class="js-pg-unit">Bar</th>
								<th colspan="2" class="js-pg-unit">Bar</th>
							</tr>
						</thead>
						<tbody>
							@foreach(range(1, $resultRows) as $index)
								<tr class="js-pg-row">
									@foreach(['instrument_reading' => 'uuc', 'pressure_rising' => 'rising', 'p_error_rising' => 'rising-corr', 'pressure_falling' => 'falling', 'p_error_failing' => 'falling-corr'] as $inputName => $role)
										<td>{{ Form::text("calibration_details[results][$index][$inputName]", null, ['class' => 'form-control cell-input' . (str_ends_with($role, 'corr') ? ' calc' : ''), 'data-role' => $role, 'placeholder' => '...']) }}</td>
									@endforeach
								</tr>
							@endforeach
						</tbody>
					</table>
					<small class="text-muted">Correction = REF − UUC. It is filled automatically and can still be edited.</small>
				</div>
			</div>
		</div>
	</div>
</div>

@push('bottom-child-scripts')
<script>
	(function () {
		function num(v) {
			v = $.trim(String(v || '')).replace('%', '').replace(',', '.');
			return v !== '' && !isNaN(v) ? parseFloat(v) : null;
		}

		function maxAbs(values) {
			return values.reduce(function (best, v) { return best === null || Math.abs(v) > Math.abs(best) ? v : best; }, null);
		}

		function refreshUnit() {
			$('.js-pg-unit').text($.trim($('#pg_unit').val()) || 'psi');
		}

		function refreshPermissibleError(force) {
			var cls = num($('#pg_accuracy_class').val());
			var from = num($('#pg_range_from').val()) || 0;
			var to = num($('#pg_range_to').val());
			var $err = $('#pg_permissible_error');
			if (cls === null || to === null) return;
			if (force || !$.trim($err.val()) || $err.data('auto')) {
				var value = +(Math.abs(to - from) * cls / 100).toFixed(4);
				$err.val(value).data('auto', true);
			}
		}

		function refreshCorrections() {
			var rising = [], falling = [];
			$('.js-pg-row').each(function () {
				var $row = $(this);
				var uuc = num($row.find('[data-role="uuc"]').val());
				[['rising', rising], ['falling', falling]].forEach(function (pair) {
					var ref = num($row.find('[data-role="' + pair[0] + '"]').val());
					var $corr = $row.find('[data-role="' + pair[0] + '-corr"]');
					if (uuc !== null && ref !== null && ($corr.data('auto') || !$.trim($corr.val()))) {
						$corr.val((ref - uuc).toFixed(2)).data('auto', true);
					}
					var c = num($corr.val());
					if (c !== null) pair[1].push(c);
				});
			});
			var maxR = maxAbs(rising), maxF = maxAbs(falling);
			$('#pg_max_rising').text(maxR === null ? '-' : maxR.toFixed(2));
			$('#pg_max_falling').text(maxF === null ? '-' : maxF.toFixed(2));

			var limit = num($('#pg_permissible_error').val());
			var worst = maxAbs([maxR, maxF].filter(function (v) { return v !== null; }));
			if (limit !== null && worst !== null) {
				var inSpec = Math.abs(worst) <= Math.abs(limit);
				$('#specification_hint').text('Suggested from results: ' + (inSpec ? 'in specification' : 'OUT of specification') + ' (max |correction| ' + Math.abs(worst).toFixed(2) + ' vs ±' + limit + ')');
			}
		}

		$(document).on('input change', '#pg_unit', refreshUnit);
		$(document).on('input change', '#pg_accuracy_class, #pg_range_from, #pg_range_to', function () { refreshPermissibleError(false); refreshCorrections(); });
		$(document).on('input', '#pg_permissible_error', function () { $(this).data('auto', false); refreshCorrections(); });
		$(document).on('input', '.js-pg-row [data-role$="-corr"]', function () { $(this).data('auto', false); refreshCorrections(); });
		$(document).on('input change', '.js-pg-row [data-role="uuc"], .js-pg-row [data-role="rising"], .js-pg-row [data-role="falling"]', refreshCorrections);

		function refreshMethodText() {
			$('#pg_calibration_method_text').text($('#pg_calibration_method').val() || '');
		}
		$(document).on('change', '#pg_calibration_method', refreshMethodText);
		// the reference due date is filled from the register when a register item is picked: keep its calendar closed then
		$(document).on('focus click', '#device_calibration_date', function () {
			var input = this;
			if (input.readOnly && typeof $.fn.datepicker === 'function') {
				setTimeout(function () { $(input).datepicker('hide'); }, 0);
			}
		});

		refreshMethodText();
		refreshUnit();
		refreshPermissibleError(false);
		refreshCorrections();
	})();
</script>
@endpush
