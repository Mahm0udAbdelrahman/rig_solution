{{-- Torque calibration certificate (RS-CAL-P11-F02, ISO 6789-1 / ISO 6789-2), on the grid of the Pressure Gauge certificate.
     Header, client, environmental conditions, signatures and footer are the same as the Pressure Gauge certificate.
     Results are calculated from the raw readings by App\Support\TorqueCalibration. --}}
@php
	$cert = \App\Support\CalibrationCertificate::class;
	$TQ = \App\Models\Inspection\Calibration\CalibrationTorque::class;
	$unit = $model->range_unit ?: 'N·m';
	$analysis = $model->analysis;
	$directionTitles = ['cw' => 'Calibration in Clockwise Direction (CW)', 'ccw' => 'Calibration in Counter-Clockwise Direction (CCW)'];

	$pt = fn ($points) => $cert::rowHeight($points);
	// calculated values: 1 decimal as on the Excel certificate
	$one = fn ($v) => $v === null ? '' : number_format((float) $v, 1, '.', '');
	$withUnit = fn ($v) => trim((string) $v) === '' ? '' : trim((string) $v) . ' ' . $unit;

	$inSpec = ($model->specification_status ?: 'in') !== 'out';
	$statementLines = $model->statement_lines;
	$conformityLines = preg_split('/\n/', $TQ::CONFORMITY_STATEMENT);

	$typeLabel = $TQ::TYPES[(string) $model->type_number] ?? ($model->type_number ? 'Type ' . $model->type_number : '');
	$classLabel = $model->type_letter ? 'Class ' . strtoupper($model->type_letter) : '';
	$permissible = trim((string) $model->permissible_deviation);
	if (is_numeric($permissible)) {
		$permissible = '± ' . $permissible . ' %';
	}

	$approvedEmployee = null;
	if (!empty($user_id_approved)) {
		$approvedEmployee = optional(App\Models\User::with('employee')->find($user_id_approved))->employee;
	}
	$footer = $model->footer_values;
	$colgroup = $cert::colgroup();

	// Stamp + QR belong to the official (uploaded) certificate: once approved they are added to the PDF when it is
	// uploaded, and shown on the page only after that PDF exists.
	$isApproved = !empty($user_id_approved);
	$pdfUploaded = $isApproved && Storage::disk('public')->exists($folder . '/' . $imageurl . '.pdf');
@endphp
@include('layouts.inspection.calibration.partials.show.certificate.styles')

<div class="pgx page_in">
	@include('layouts.inspection.calibration.partials.show.certificate.header', ['model' => $model, 'code' => $code])

	@include('layouts.inspection.calibration.partials.show.certificate.client', ['model' => $model])

	{{-- Equipment under calibration --}}
	<table class="blk">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(27.6) }}"><td colspan="11" class="sec">Equipment Under Calibration</td></tr>
		<tr style="height: {{ $pt(36) }}">
			<td colspan="2" class="labt">Device Description:</td>
			<td colspan="9" class="val">{{ $model->equipment_description }}</td>
		</tr>
		@foreach([
			['Code No.:', $model->code_number, 'Serial Number:', $model->serial_number],
			['Manufacturer:', $model->manufacturer, 'Model/Type:', $model->model],
			['Range:', $withUnit($model->range_value), 'Type / Class:', trim($typeLabel . ($typeLabel && $classLabel ? ', ' : '') . $classLabel)],
			['Resolution:', $withUnit($model->resolution_value), 'Scale interval:', $withUnit($model->scale_interval_value)],
			['Permissible deviation:', $permissible, 'Calibration direction:', $TQ::DIRECTIONS[count($model->directions) === 2 ? 'cad' : $model->directions[0]]],
		] as [$leftLabel, $leftValue, $rightLabel, $rightValue])
			<tr style="height: {{ $pt(36) }}">
				<td colspan="2" class="labt">{{ $leftLabel }}</td>
				<td colspan="3" class="val">{{ $leftValue }}</td>
				<td colspan="2" class="labt">{{ $rightLabel }}</td>
				<td colspan="4" class="val">{{ $rightValue }}</td>
			</tr>
		@endforeach
	</table>

	@include('layouts.inspection.calibration.partials.show.certificate.environment', ['model' => $model])

	{{-- Reference equipment --}}
	<table class="blk">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(30) }}"><td colspan="11" class="sec">Reference Equipment Used</td></tr>
		<tr style="height: {{ $pt(28) }}">
			<td colspan="4" class="hd">Description.</td>
			<td colspan="3" class="hd">Certificate No.</td>
			<td class="hd">Due Date</td>
			<td colspan="3" class="hd">Traceability.</td>
		</tr>
		<tr style="height: {{ $pt(30) }}">
			<td colspan="4" class="verdana c f18 pgx-dark">{{ $model->reference_equipment_description }}</td>
			<td colspan="3" class="verdana c f18 pgx-dark">{{ $model->calibration_certificate_no }}</td>
			<td class="verdana c f18 pgx-dark">{{ $cert::date($model, 'calibration_due_date') ?: '--' }}</td>
			<td colspan="3" class="verdana c f18 pgx-mid">{{ $model->traceability }}</td>
		</tr>
		<tr style="height: {{ $pt(28) }}">
			<td colspan="2" class="hd">Serial,No.</td>
			<td colspan="3" class="hd">Manufacturer.</td>
			<td colspan="3" class="hd">Model.</td>
			<td colspan="3" class="hd">Range.</td>
		</tr>
		<tr style="height: {{ $pt(32) }}">
			<td colspan="2" class="verdana c f18">{{ $model->reference_serial_number }}</td>
			<td colspan="3" class="verdana c f18">{{ $model->reference_manufacturer }}</td>
			<td colspan="3" class="verdana c f18">{{ $model->reference_model }}</td>
			<td colspan="3" class="verdana c f16">{{ $model->reference_range }}</td>
		</tr>
	</table>

	{{-- Calibration method --}}
	<table class="blk">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(25.95) }}"><td colspan="11" class="sec">Calibration Method</td></tr>
		<tr style="height: {{ $pt(48) }}"><td colspan="11" class="abadi c f18">{{ $TQ::CALIBRATION_METHOD }}</td></tr>
	</table>

	{{-- Calibration results: one table per calibrated direction --}}
	<table class="blk">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(28.2) }}"><td colspan="11" class="sec">Calibration Results</td></tr>
		@foreach($model->directions as $direction)
			@php
				$rows = $analysis['directions'][$direction] ?? [];
				$hasReadings = collect($rows)->contains(fn ($row) => $row['xr'] !== null);
				$legacyRows = $hasReadings ? [] : $model->legacyResults($direction);
			@endphp
			<tr style="height: {{ $pt(28) }}"><td colspan="11" class="tnr b c f20 pgx-dark g15">{{ $directionTitles[$direction] }}</td></tr>
			@if($legacyRows)
				<tr style="height: {{ $pt(48) }}">
					<td colspan="2" class="rh">Applied Torque</td>
					<td colspan="3" class="rh">UUT (Avg.)</td>
					<td colspan="3" class="rh">Correction</td>
					<td colspan="3" class="rh">Relative Expanded Uncertainty ±</td>
				</tr>
				<tr style="height: {{ $pt(26) }}">
					<td colspan="2" class="aptos b c f20 pgx-mid">{{ $unit }}</td>
					<td colspan="3" class="aptos b c f20 pgx-mid">{{ $unit }}</td>
					<td colspan="3" class="aptos b c f20 pgx-mid">{{ $unit }}</td>
					<td colspan="3" class="aptos b c f20 pgx-mid">%</td>
				</tr>
				@foreach($legacyRows as $legacyRow)
					<tr style="height: {{ $pt(32) }}">
						<td colspan="2" class="val">{{ $legacyRow['torque'] ?? '' }}</td>
						<td colspan="3" class="val">{{ $legacyRow['uut'] ?? '' }}</td>
						<td colspan="3" class="val">{{ $legacyRow['correction'] ?? '' }}</td>
						<td colspan="3" class="val">{{ ltrim(str_replace(['±', '\u00b1'], '', (string) ($legacyRow['uexp'] ?? ''))) }}</td>
					</tr>
				@endforeach
			@else
				<tr style="height: {{ $pt(56) }}">
					<td colspan="2" class="rh">Target torque value</td>
					<td colspan="3" class="rh">Average reference torque</td>
					<td colspan="2" class="rh">Mean value of the measurement error</td>
					<td colspan="2" class="rh">Relative Expanded Uncertainty ±</td>
					<td colspan="2" class="rh">Relative measurement uncertainty interval ±</td>
				</tr>
				<tr style="height: {{ $pt(26) }}">
					<td colspan="2" class="aptos b c f20 pgx-mid">{{ $unit }}</td>
					<td colspan="3" class="aptos b c f20 pgx-mid">{{ $unit }}</td>
					<td colspan="2" class="aptos b c f20 pgx-mid">%</td>
					<td colspan="2" class="aptos b c f20 pgx-mid">W (%)</td>
					<td colspan="2" class="aptos b c f20 pgx-mid">W' (%)</td>
				</tr>
				@foreach($rows as $row)
					<tr style="height: {{ $pt(32) }}">
						<td colspan="2" class="val">{{ $row['xa'] === null ? '' : rtrim(rtrim(number_format($row['xa'], 4, '.', ''), '0'), '.') }}</td>
						<td colspan="3" class="val">{{ $one($row['xr']) }}</td>
						<td colspan="2" class="val">{{ $one($row['mean_error']) }}</td>
						<td colspan="2" class="val">{{ $one($row['W']) }}</td>
						<td colspan="2" class="val">{{ $one($row['W_interval']) }}</td>
					</tr>
				@endforeach
			@endif
		@endforeach
		<tr class="nb" style="height: {{ $pt(40) }}">
			<td class="tnr b c f20 pgx-mid">Remarks:</td>
			<td colspan="10" class="tnr l f16">{{ $model->remarks ?: 'N/A' }}</td>
		</tr>
	</table>

	{{-- Conformity + statement --}}
	<table class="blk">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(24) }}"><td colspan="11" class="tnr b l f20 pgx-dark g25">Conformity Statement:</td></tr>
		<tr class="nb">
			<td colspan="11" class="verdana f14" style="font-size: 10.5px; padding-top: 3px; padding-bottom: 3px;">{!! implode('<br>', array_map('e', $conformityLines)) !!}</td>
		</tr>
		<tr style="height: {{ $pt(24) }}">
			<td colspan="11" class="tnr b l f20 pgx-dark g25">{{ $inSpec ? 'The instrument is in specification:' : 'The instrument is out of specification:' }}</td>
		</tr>
		@foreach($statementLines as $line)
			<tr class="nb" style="height: {{ $pt(24) }}"><td colspan="11" class="verdana f14" style="font-size: 10.5px;">{{ $line }}</td></tr>
		@endforeach
	</table>

	{{-- Rows 60-64: signatures (taller than the Excel rows so the signatures and stamp have room) --}}
	<table class="blk">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(57) }}">
			<td colspan="2" class="tnr b c f20 pgx-dark g25">Calibrated By<br>(Lab Technician)</td>
			<td colspan="2" class="tnr b c f20 pgx-dark g25">Issued By<br>(QA)</td>
			<td colspan="2" class="tnr b c f20 pgx-dark g25">Reviewed By<br>(Lab Technical Manager)</td>
			<td colspan="2" class="tnr b c f20 pgx-dark g25">Stamp</td>
			<td colspan="3" class="tnr b c f20 pgx-dark g25">QR Code</td>
		</tr>
		<tr style="height: {{ $pt(90) }}">
			<td colspan="2" class="corsiva c f24" style="border-bottom: 0;">{{ $person_make_report }}</td>
			<td colspan="2" class="corsiva c f24" style="border-bottom: 0;">{{ optional($issuer)->name ?: $model->issued_by }}</td>
			<td colspan="2" class="corsiva c f24" style="border-bottom: 0;">
				@if(!empty($user_id_approved))
					{{ optional($approvedEmployee)->name }}
				@endif
			</td>
			<td colspan="2" rowspan="3" class="c" style="position: relative;">
				@if($isApproved)
					<img src="{{ asset('app-assets/images/logo/stamp.jpeg') }}" class="{{ $pdfUploaded ? '' : 'stamp-on-upload' }}" style="width: 12.5em; height: 9.4em;" alt="" />
					@unless($pdfUploaded)
						<div class="stamp-pending-note no-print" data-html2canvas-ignore>Stamp &amp; QR are added when the PDF is uploaded</div>
					@endunless
				@endif
			</td>
			<td colspan="3" rowspan="3" class="c">
				@if($isApproved)
					<img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(URL($pdfurl), 'QRCODE', 3, 3) }}" class="{{ $pdfUploaded ? '' : 'stamp-on-upload' }}" style="width: 140px; height: 140px; image-rendering: pixelated;" alt="QR" />
				@endif
			</td>
		</tr>
		<tr style="height: {{ $pt(90) }}">
			<td colspan="2" class="c" style="border-top: 0; border-bottom: 0;">
				@if($esign)
					<img src="{{ Storage::url('employees/') }}{{ $esign }}" style="max-width: 130px; max-height: 55px;" alt="" />
				@endif
			</td>
			<td colspan="2" class="c" style="border-top: 0; border-bottom: 0;">
				@if($issuer)
					@if($issuer->esign)
						<img src="{{ Storage::url('employees/') }}{{ $issuer->esign }}" style="max-width: 130px; max-height: 55px;" alt="" />
					@endif
				@elseif(!$model->issued_by)
					@if($canIssue)
						<button type="button" id="issue" class="btn btn-warning btn-sm btn-print waves-effect waves-light"><i class="la la-check mr-50"></i>Issue (QA)</button>
					@else
						<span class="info verdana f16">Waiting For QA</span>
					@endif
				@endif
			</td>
			<td colspan="2" class="c" style="border-top: 0; border-bottom: 0;">
				@if(empty($user_id_approved))
					@if($hasPermission)
						<button type="button" id="approve" class="btn btn-warning btn-sm btn-print waves-effect waves-light approve"><i class="la la-paper-plane-o mr-50"></i>Approve</button>
					@else
						<span class="info verdana f16">Waiting For Approve</span>
					@endif
				@elseif(optional($approvedEmployee)->esign)
					<img src="{{ Storage::url('employees/') }}{{ $approvedEmployee->esign }}" style="max-width: 130px; max-height: 55px;" alt="" />
				@endif
			</td>
		</tr>
		<tr style="height: {{ $pt(50) }}">
			<td colspan="2" class="tnr c f20" style="border-top: 0;">….......................</td>
			<td colspan="2" class="tnr c f20" style="border-top: 0;">….......................</td>
			<td colspan="2" class="tnr c f20" style="border-top: 0;">…..........................</td>
		</tr>
	</table>

	{{-- Rows 66-67: form footer --}}
	<table class="blk" style="margin-top: 8px;">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(18) }}">
			<td colspan="2" class="corsiva b c f14 g25" style="color: #0a1e31; border-bottom: 1px dashed #000;">Form No.</td>
			<td colspan="2" class="tnr b c f14 g25" style="color: #0a1e31; border-bottom: 1px dashed #000;">Issue No.</td>
			<td colspan="2" class="tnr b c f14 g25" style="color: #0a1e31; border-bottom: 1px dashed #000;">Issue Date.</td>
			<td colspan="2" class="tnr b c f14 g25" style="color: #0a1e31; border-bottom: 1px dashed #000;">Revision No.</td>
			<td colspan="3" class="tnr b c f14 g25" style="color: #0a1e31; border-bottom: 1px dashed #000;">Revision Date.</td>
		</tr>
		<tr style="height: {{ $pt(18.6) }}">
			<td colspan="2" class="corsiva c f14">{{ optional($footer)->form_no }}</td>
			<td colspan="2" class="tnr c f14">{{ optional($footer)->issue_no }}</td>
			<td colspan="2" class="tnr c f14">{{ optional($footer)->issue_date }}</td>
			<td colspan="2" class="tnr c f14">{{ optional($footer)->revision_no }}</td>
			<td colspan="3" class="tnr c f14">{{ optional($footer)->revision_date }}</td>
		</tr>
	</table>
</div>
