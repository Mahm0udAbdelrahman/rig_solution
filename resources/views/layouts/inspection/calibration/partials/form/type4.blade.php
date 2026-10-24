<!-- type 4 Torque — data entry of RS-CAL-P11-F02 Raw data-UNcert-Certificate Torque.xlsx ("Data Input" sheet) -->
@php
	$isNew = !isset($model);
	$TQ = \App\Models\Inspection\Calibration\CalibrationTorque::class;
	$details = $isNew ? [] : (array) $model->calibration_details;
	// a new certificate starts from the reference equipment of the latest torque certificate
	$previousReference = isset($previous) && $previous ? (array) data_get($previous->calibration_details, 'reference', []) : [];
	$currentUnit = $isNew ? 'N·m' : ($model->range_unit ?: 'N·m');
	$currentDirection = $isNew ? 'cw' : (count($model->directions) === 2 ? 'cad' : $model->directions[0]);
	$directionLabels = ['cw' => 'Clockwise (CW)', 'ccw' => 'Counter-clockwise (CCW)'];
	$romans = ['I', 'II', 'III', 'IV'];
	// field of calibration_details: saved value, or the default of a new certificate
	$detail = fn (string $path, $default = null) => $isNew ? $default : data_get($details, $path);
	$legacy = $details['legacy'] ?? null;
@endphp
<style>
	.tq-table th { background-color: #d9d9d9; color: #000; vertical-align: middle !important; font-size: .8rem; text-align: center; padding: .35rem !important; }
	.tq-table td { padding: 0 !important; vertical-align: middle !important; }
	.tq-table td.label-cell { background: #f4f5fa; font-weight: 600; font-size: .8rem; padding: 0 .5rem !important; white-space: nowrap; }
	.tq-table .cell-input { text-align: center; height: 2rem; border: 0; width: 100%; }
	.tq-table .calc { background: #f4f5fa; font-weight: 600; text-align: center; font-size: .85rem; }
	.tq-results th, .tq-results td { text-align: center; font-size: .85rem; }
	.tq-results td.num { font-variant-numeric: tabular-nums; }
	select.pg-excel-list, select.pg-excel-list:focus { background-color: #ffc000 !important; color: #000 !important; font-weight: 600; border-color: #d39e00; }
	select.pg-excel-list option { background: #fff; }
	.tq-section-note { font-size: .8rem; color: #6b6f82; }
</style>
<div class="calibration-report-type-partial" id="TYPE_4">
	{{-- Equipment under calibration --}}
	<div class="card">
		<div class="card-body">
			<h3>Equipment Under Calibration</h3>
			<div class="row mt-2">
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('equipment_description', 'Device Description') }}
						{{ Form::text('equipment_description', $isNew ? 'Torque Wrench' : null, ['class' => 'form-control', 'placeholder' => 'Torque Wrench']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('code_number', 'Code No.') }}
						{{ Form::text('code_number', $isNew ? 'N/A' : null, ['class' => 'form-control', 'placeholder' => 'N/A']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('serial_number', 'Serial Number') }}
						{{ Form::text('serial_number', null, ['class' => 'form-control', 'placeholder' => 'Serial Number']) }}
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
						{{ Form::label('model', 'Model / Type') }}
						{{ Form::text('model', null, ['class' => 'form-control', 'placeholder' => 'Model / Type']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<label>Range</label>
						<div class="input-group">
							{{ Form::text('range_value', null, ['class' => 'form-control js-tq', 'id' => 'tq_range', 'placeholder' => '1500']) }}
							<div class="input-group-append" style="width: 45%;">
								{{ Form::select('range_unit', array_combine($TQ::UNITS, $TQ::UNITS) + ($currentUnit && !in_array($currentUnit, $TQ::UNITS, true) ? [$currentUnit => $currentUnit] : []), $currentUnit, ['class' => 'form-control pg-excel-list js-tq', 'id' => 'tq_unit']) }}
							</div>
						</div>
					</div>
				</div>
				<div class="col-6 col-sm-2">
					<div class="form-group mb-1">
						{{ Form::label('type_number', 'Type') }}
						{{ Form::select('type_number', $TQ::TYPES, $isNew ? '2' : null, ['class' => 'form-control pg-excel-list']) }}
					</div>
				</div>
				<div class="col-6 col-sm-2">
					<div class="form-group mb-1">
						{{ Form::label('type_letter', 'Class') }}
						{{ Form::select('type_letter', array_combine($TQ::CLASSES, $TQ::CLASSES), $isNew ? 'A' : strtoupper((string) $model->type_letter), ['class' => 'form-control pg-excel-list']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<label>Resolution, r</label>
						<div class="input-group">
							{{ Form::text('resolution_value', null, ['class' => 'form-control js-tq', 'id' => 'tq_resolution', 'placeholder' => '10']) }}
							<div class="input-group-append"><span class="input-group-text js-tq-unit">N·m</span></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						<label>Scale interval</label>
						<div class="input-group">
							{{ Form::text('scale_interval_value', null, ['class' => 'form-control', 'placeholder' => '10']) }}
							<div class="input-group-append"><span class="input-group-text js-tq-unit">N·m</span></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('permissible_deviation', 'Permissible deviation (±)') }}
						{{ Form::select('permissible_deviation', $TQ::PERMISSIBLE_DEVIATIONS + ($isNew || isset($TQ::PERMISSIBLE_DEVIATIONS[(string) $model->permissible_deviation]) || !$model->permissible_deviation ? [] : [$model->permissible_deviation => $model->permissible_deviation]), $isNew ? '4' : null, ['class' => 'form-control pg-excel-list js-tq', 'id' => 'tq_permissible']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('calibration_direction', 'Calibration direction') }}
						{{ Form::select('calibration_direction', $TQ::DIRECTIONS, $currentDirection, ['class' => 'form-control pg-excel-list js-tq', 'id' => 'tq_direction']) }}
					</div>
				</div>
			</div>
		</div>
	</div>

	{{-- Reference equipment --}}
	<div class="card">
		<div class="card-body">
			<h3>Reference Equipment Used</h3>
			<div class="row mt-2">
				<div class="col-12">
					<div class="form-group mb-1">
						<label for="reference_standard_picker">Select Reference Standard (Equipment Controlled List)</label>
						@include('layouts.repeated.equipment_picker', [
							'id' => 'reference_standard_picker',
							'fill' => [
								'equipment_no' => '#reference_serial_number',
								'equipment_description' => '#reference_equipment_description',
								'manufacturer' => '#reference_manufacturer',
								'model_type' => '#reference_model',
								'capacity_range' => '#reference_range',
								'calibration_due_date' => '#calibration_due_date',
							],
						])
						<small class="text-muted">Picking an item fills the fields below from the register. Choose "Other (enter manually)" for equipment that is not in the register.</small>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('reference_equipment_description', 'Description') }}
						{{ Form::text('reference_equipment_description', $isNew ? ($previous->reference_equipment_description ?? 'Reference Torque device') : null, ['class' => 'form-control', 'id' => 'reference_equipment_description', 'placeholder' => 'Reference Torque device']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('reference_serial_number', 'Serial No.') }}
						{{ Form::text('reference_serial_number', $isNew ? ($previous->reference_serial_number ?? null) : null, ['class' => 'form-control', 'id' => 'reference_serial_number', 'placeholder' => 'Serial No.']) }}
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="form-group mb-1">
						{{ Form::label('reference_manufacturer', 'Manufacturer') }}
						{{ Form::text('reference_manufacturer', $isNew ? ($previous->reference_manufacturer ?? null) : null, ['class' => 'form-control', 'id' => 'reference_manufacturer', 'placeholder' => 'Norbar']) }}
					</div>
				</div>
				<div class="col-12 col-sm-3">
					<div class="form-group mb-1">
						{{ Form::label('reference_model', 'Model') }}
						{{ Form::text('reference_model', $isNew ? ($previous->reference_model ?? null) : null, ['class' => 'form-control', 'id' => 'reference_model', 'placeholder' => 'Model']) }}
					</div>
				</div>
				<div class="col-12 col-sm-3">
					<div class="form-group mb-1">
						{{ Form::label('reference_range', 'Range') }}
						{{ Form::text('reference_range', $isNew ? ($previous->reference_range ?? null) : null, ['class' => 'form-control', 'id' => 'reference_range', 'placeholder' => '30-1500 N·m']) }}
					</div>
				</div>
				<div class="col-12 col-sm-3">
					<div class="form-group mb-1">
						{{ Form::label('calibration_certificate_no', 'Calibration certificate No.') }}
						{{ Form::text('calibration_certificate_no', $isNew ? ($previous->calibration_certificate_no ?? null) : null, ['class' => 'form-control', 'id' => 'calibration_certificate_no', 'placeholder' => 'Certificate No.']) }}
					</div>
				</div>
				<div class="col-12 col-sm-3">
					<div class="form-group mb-1">
						<label for="calibration_due_date">Due Date</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text"><i class="ft-calendar"></i></span></div>
							{{ Form::text('calibration_due_date', $isNew ? ($previous->calibration_due_date ?? null) : null, ['class' => 'form-control datepicker-default', 'id' => 'calibration_due_date', 'placeholder' => 'Due Date']) }}
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-3">
					<div class="form-group mb-1">
						{{ Form::label('traceability', 'Traceability') }}
						{{ Form::text('traceability', $isNew ? ($previous->traceability ?? 'NIS') : null, ['class' => 'form-control', 'placeholder' => 'NIS']) }}
					</div>
				</div>
				<div class="col-12">
					<label class="mt-1 mb-50">Reference device calibration (from its certificate) &mdash; corrected value X<sub>r</sub> = A·x + B·x² + C·x³ (x in N·m)</label>
					<table class="table table-bordered tq-table mb-0">
						<thead>
							<tr><th style="width: 22%;">Direction</th><th>A</th><th>B</th><th>C</th><th>U (%) <small>k = 2</small></th></tr>
						</thead>
						<tbody>
							@foreach($directionLabels as $direction => $directionLabel)
								<tr class="js-tq-dir" data-direction="{{ $direction }}">
									<td class="label-cell">{{ $directionLabel }}</td>
									@foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'u' => 'U %'] as $coefficient => $placeholder)
										<td>{{ Form::text("calibration_details[reference][$direction][$coefficient]", $detail("reference.$direction.$coefficient", $previousReference[$direction][$coefficient] ?? null), ['class' => 'cell-input js-tq', 'placeholder' => $placeholder]) }}</td>
									@endforeach
								</tr>
							@endforeach
						</tbody>
					</table>
					<small class="text-muted">Leave A = 1, B = 0, C = 0 when the reference readings need no correction.</small>
				</div>
			</div>
		</div>
	</div>

	{{-- Readings --}}
	<div class="card">
		<div class="card-body">
			<h3>Calibration Results</h3>
			<p class="tq-section-note mb-1">Enter the reference device readings as taken. Target values are % of the range (filled from the range, can be changed).</p>

			<label class="mb-50">Preloading (maximum torque, 3 times per direction)</label>
			<table class="table table-bordered tq-table mb-2">
				<thead>
					<tr><th style="width: 22%;">Direction</th><th>Max <span class="js-tq-unit">N·m</span></th><th>1st</th><th>2nd</th><th>3rd</th></tr>
				</thead>
				<tbody>
					@foreach($directionLabels as $direction => $directionLabel)
						<tr class="js-tq-dir" data-direction="{{ $direction }}">
							<td class="label-cell">{{ $directionLabel }}</td>
							<td>{{ Form::text("calibration_details[preload][$direction][max]", $detail("preload.$direction.max"), ['class' => 'cell-input js-tq-max-target', 'placeholder' => '...']) }}</td>
							@foreach([1, 2, 3] as $index)
								<td>{{ Form::text("calibration_details[preload][$direction][$index]", $detail("preload.$direction.$index"), ['class' => 'cell-input', 'placeholder' => '...']) }}</td>
							@endforeach
						</tr>
					@endforeach
				</tbody>
			</table>

			@foreach($directionLabels as $direction => $directionLabel)
				<div class="js-tq-dir" data-direction="{{ $direction }}">
					<label class="mb-50">{{ $directionLabel }} readings</label>
					<table class="table table-bordered tq-table mb-2">
						<thead>
							<tr>
								<th style="width: 10%;">Target</th>
								<th style="width: 14%;">Target value X<sub>a</sub> <span class="js-tq-unit">N·m</span></th>
								@foreach(['1st', '2nd', '3rd', '4th', '5th'] as $readingLabel)
									<th>{{ $readingLabel }} Reading</th>
								@endforeach
							</tr>
						</thead>
						<tbody>
							@foreach($TQ::TARGET_PERCENTS as $point => $percent)
								<tr>
									<td class="label-cell text-center">{{ $percent }}%</td>
									<td>
										@if($direction === 'cw')
											{{ Form::text("calibration_details[targets][$point]", $detail("targets.$point"), ['class' => 'cell-input calc js-tq js-tq-target', 'data-point' => $point, 'data-percent' => $percent, 'placeholder' => '...']) }}
										@else
											<input type="text" class="cell-input calc js-tq-target-copy" data-point="{{ $point }}" disabled>
										@endif
									</td>
									@foreach(range(0, 4) as $reading)
										<td>{{ Form::text("calibration_details[readings][$direction][$point][$reading]", $detail("readings.$direction.$point.$reading"), ['class' => 'cell-input js-tq', 'placeholder' => '...']) }}</td>
									@endforeach
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@endforeach
		</div>
	</div>

	{{-- Uncertainty measurements (ISO 6789-2) --}}
	<div class="card">
		<div class="card-body">
			<h3>Uncertainty Estimation Measurement</h3>
			<p class="tq-section-note mb-1">Measured at the lowest target value (T<sub>min</sub>). A part left empty is not counted in the uncertainty.</p>
			@php
				$uncertaintyParts = [
					['reproducibility', 'Reproducibility (b_rep)', array_map(fn ($r) => 'Sequence ' . $r, $romans), 5],
					['geometric', 'Geometric effects of the output drive of the torque tool (b_od)', ['Position 1', 'Position 2', 'Position 3', 'Position 4'], 10],
					['interface', 'Variation due to the interface between the torque tool and the calibration system (b_int)', ['Position 1', 'Position 2', 'Position 3', 'Position 4'], 10],
					['loading_point', 'Variation of the torque loading point (b_l)', ['Position 1 (-10 mm)', 'Position 2 (+10 mm)'], 10],
				];
			@endphp
			<div class="row">
				@foreach($uncertaintyParts as [$part, $partLabel, $columns, $rows])
					<div class="{{ count($columns) > 2 ? 'col-12 col-lg-6' : 'col-12 col-lg-6' }}">
						<label class="mb-50">{{ $partLabel }}</label>
						<table class="table table-bordered tq-table mb-2 js-tq-part" data-part="{{ $part }}">
							<thead>
								<tr>
									<th style="width: 8%;">#</th>
									@foreach($columns as $columnLabel)
										<th>{{ $columnLabel }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach(range(0, $rows - 1) as $row)
									<tr>
										<td class="label-cell text-center">{{ $row + 1 }}</td>
										@foreach($columns as $column => $columnLabel)
											<td>{{ Form::text("calibration_details[$part][$column][$row]", $detail("$part.$column.$row"), ['class' => 'cell-input js-tq', 'data-column' => $column, 'placeholder' => '...']) }}</td>
										@endforeach
									</tr>
								@endforeach
								<tr>
									<td class="label-cell text-center">Avg</td>
									@foreach($columns as $column => $columnLabel)
										<td class="calc js-tq-avg" data-column="{{ $column }}">-</td>
									@endforeach
								</tr>
							</tbody>
						</table>
					</div>
				@endforeach
			</div>
		</div>
	</div>

	{{-- Live results --}}
	<div class="card">
		<div class="card-body">
			<h3>Results <small class="text-muted">(calculated, as printed on the certificate)</small></h3>
			<div id="tq_results"><p class="tq-section-note">Enter the readings to see the results.</p></div>
		</div>
	</div>

	@if($legacy)
		{{-- results of a certificate made before the raw data entry: kept until readings are entered --}}
		@foreach($legacy as $legacyDirection => $legacyRows)
			@foreach($legacyRows as $legacyIndex => $legacyRow)
				@foreach((array) $legacyRow as $legacyKey => $legacyValue)
					<input type="hidden" name="calibration_details[legacy][{{ $legacyDirection }}][{{ $legacyIndex }}][{{ $legacyKey }}]" value="{{ $legacyValue }}">
				@endforeach
			@endforeach
		@endforeach
	@endif
</div>

@push('bottom-child-scripts')
<script>
	(function () {
		var previewUrl = @json(route('calibrationTorque.preview'));

		function num(v) {
			v = $.trim(String(v || '')).replace(',', '.');
			return v !== '' && !isNaN(v) ? parseFloat(v) : null;
		}
		function fmt(v, d) {
			return v === null || v === undefined ? '-' : Number(v).toFixed(d === undefined ? 1 : d);
		}

		function refreshUnit() {
			$('.js-tq-unit').text($('#tq_unit').val() || 'N·m');
		}

		function currentDirections() {
			var value = $('#tq_direction').val();
			return value === 'cad' ? ['cw', 'ccw'] : [value === 'ccw' ? 'ccw' : 'cw'];
		}

		function refreshDirections() {
			var directions = currentDirections();
			$('.js-tq-dir').each(function () {
				$(this).toggle(directions.indexOf($(this).data('direction')) !== -1);
			});
		}

		// target values = % of the range, unless typed by hand
		function refreshTargets(force) {
			var range = num($('#tq_range').val());
			$('.js-tq-target').each(function () {
				var $input = $(this);
				if (range !== null && (force || !$.trim($input.val()) || $input.data('auto'))) {
					$input.val(+(range * $input.data('percent') / 100).toFixed(4)).data('auto', true);
				}
				$('.js-tq-target-copy[data-point="' + $input.data('point') + '"]').val($input.val());
			});
			if (range !== null) {
				$('.js-tq-max-target').each(function () {
					if (!$.trim($(this).val()) || $(this).data('auto')) $(this).val(range).data('auto', true);
				});
			}
		}

		function refreshAverages() {
			$('.js-tq-part').each(function () {
				var $table = $(this);
				$table.find('.js-tq-avg').each(function () {
					var column = $(this).data('column');
					var values = $table.find('input[data-column="' + column + '"]').map(function () { return num(this.value); }).get().filter(function (v) { return v !== null; });
					$(this).text(values.length ? fmt(values.reduce(function (a, b) { return a + b; }, 0) / values.length, 2) : '-');
				});
			});
		}

		function renderResults(data) {
			var labels = { cw: 'Calibration in Clockwise Direction (CW)', ccw: 'Calibration in Counter-Clockwise Direction (CCW)' };
			var unit = $('#tq_unit').val() || 'N·m';
			var html = '';
			$.each(data.analysis.directions, function (direction, rows) {
				html += '<label class="mt-50 mb-50">' + labels[direction] + '</label>'
					+ '<div class="table-responsive"><table class="table table-bordered tq-results mb-1"><thead><tr>'
					+ '<th>Target X<sub>a</sub> (' + unit + ')</th><th>Average reference X&#772;<sub>r</sub> (' + unit + ')</th>'
					+ '<th>Mean error a&#772;<sub>s</sub> (%)</th><th>w (%)</th><th>W (%)</th><th>W\' (%)</th>'
					+ '<th class="text-muted">w<sub>md</sub> / w<sub>r</sub> / w<sub>rep</sub> / w<sub>od</sub> / w<sub>int</sub> / w<sub>l</sub> / w<sub>re</sub></th>'
					+ '</tr></thead><tbody>';
				rows.forEach(function (row) {
					var budget = row.budget ? $.map(['wmd', 'wr', 'wrep', 'wod', 'wint', 'wl', 'wre'], function (k) { return fmt(row.budget[k], 3); }).join(' / ') : '-';
					var over = data.limit !== null && row.W_interval !== null && Math.abs(row.W_interval) > data.limit;
					html += '<tr><td class="num">' + (row.xa === null ? '-' : row.xa) + '</td><td class="num">' + fmt(row.xr) + '</td>'
						+ '<td class="num">' + fmt(row.mean_error) + '</td><td class="num">' + fmt(row.w, 3) + '</td><td class="num">' + fmt(row.W) + '</td>'
						+ '<td class="num' + (over ? ' text-danger font-weight-bold' : '') + '">' + fmt(row.W_interval) + '</td>'
						+ '<td class="num text-muted">' + budget + '</td></tr>';
				});
				html += '</tbody></table></div>';
			});
			html += '<small class="text-muted">b<sub>rep</sub> ' + fmt(data.analysis.brep, 2) + ' &nbsp; b<sub>od</sub> ' + fmt(data.analysis.bod, 2)
				+ ' &nbsp; b<sub>int</sub> ' + fmt(data.analysis.bint, 2) + ' &nbsp; b<sub>l</sub> ' + fmt(data.analysis.bl, 2) + ' ' + unit + '</small>';
			$('#tq_results').html(html);

			if (data.in_spec !== null) {
				$('#specification_hint').text('Suggested from results: ' + (data.in_spec ? 'in specification' : 'OUT of specification')
					+ " (largest |W'| " + fmt(data.worst) + ' % vs permissible deviation ±' + data.limit + ' %)');
			}
		}

		var timer = null, pending = null;
		function refreshResults() {
			clearTimeout(timer);
			timer = setTimeout(function () {
				if (pending) pending.abort();
				var payload = $('[name^="calibration_details["], #tq_unit, #tq_resolution, #tq_direction, #tq_permissible').serialize();
				pending = $.ajax({
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					type: 'POST', url: previewUrl, data: payload, dataType: 'json',
					success: renderResults,
				});
			}, 300);
		}

		$(document).on('input change', '#tq_unit', function () { refreshUnit(); refreshResults(); });
		$(document).on('input change', '#tq_direction', function () { refreshDirections(); refreshResults(); });
		$(document).on('input change', '#tq_range', function () { refreshTargets(false); refreshResults(); });
		$(document).on('input', '.js-tq-target', function () { $(this).data('auto', false); refreshTargets(false); });
		$(document).on('input', '.js-tq-max-target', function () { $(this).data('auto', false); });
		$(document).on('input change', '.js-tq', function () { refreshAverages(); refreshResults(); });
		// the reference due date is filled from the register when a register item is picked: keep its calendar closed then
		$(document).on('focus click', '#calibration_due_date', function () {
			var input = this;
			if (input.readOnly && typeof $.fn.datepicker === 'function') {
				setTimeout(function () { $(input).datepicker('hide'); }, 0);
			}
		});

		refreshUnit();
		refreshDirections();
		refreshTargets(false);
		refreshAverages();
		refreshResults();
	})();
</script>
@endpush
