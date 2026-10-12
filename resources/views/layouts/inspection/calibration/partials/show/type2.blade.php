{{-- Pressure Gauge calibration certificate — mirrors the "Calibration Certificate" sheet of RS-CAL-P11-F01 Pressure Gauge.xlsx (columns B..L).
     Header, client and environmental conditions are shared by all calibration certificates (partials/show/certificate). --}}
@php
	$cert = \App\Support\CalibrationCertificate::class;
	$unit = $model->read_out_unit ?: 'psi';
	$results = $model->calibration_results;
	$resultRows = $results['rows'];
	$rowCount = max(count($resultRows), 1);

	$pt = fn ($points) => $cert::rowHeight($points);
	$num = fn ($v, $decimals = 2) => (is_numeric($v) ? number_format((float) $v, $decimals, '.', '') : $v);
	$pct = fn ($v, $decimals) => is_numeric($v) ? number_format((float) $v, $decimals, '.', '') . '%' : $v;

	$inSpec = ($model->specification_status ?: 'in') !== 'out';
	$statementLines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $model->statement)), 'strlen'));

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

	{{-- Rows 13-18: equipment under calibration --}}
	<table class="blk">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(27.6) }}"><td colspan="11" class="sec">Equipment Under Calibration</td></tr>
		<tr style="height: {{ $pt(39.75) }}">
			<td colspan="2" class="labt">Description:</td>
			<td colspan="3" class="val">{{ $model->equipment_description }}</td>
			<td colspan="2" class="labt">Resolution:</td>
			<td style="border-right: 0;"></td>
			<td class="val" style="border-left: 0;">{{ $model->resolution }}</td>
			<td colspan="2" class="val l pgx-yellow">{{ $unit }}</td>
		</tr>
		<tr style="height: {{ $pt(39.75) }}">
			<td colspan="2" class="labt">Serial No:</td>
			<td colspan="3" class="val">{{ $model->serial_number }}</td>
			<td colspan="2" class="labt">Accuracy Class:</td>
			<td colspan="4" class="val">{{ $pct($model->type, 1) }}</td>
		</tr>
		<tr style="height: {{ $pt(39.75) }}">
			<td colspan="2" class="labt">Asset Number:</td>
			<td colspan="3" class="val">{{ $model->code_number }}</td>
			<td colspan="2" class="labt">Device Range:</td>
			@if($model->range_from !== null || $model->range_to !== null)
				<td class="val r" style="border-right: 0;">{{ $model->range_from }}</td>
				<td class="val pgx-mid" style="border-left: 0; border-right: 0;">TO</td>
				<td class="val l" style="border-left: 0;">{{ $model->range_to }}</td>
				<td class="val l pgx-yellow">{{ $unit }}</td>
			@else
				<td colspan="4" class="val">{{ $model->range }}</td>
			@endif
		</tr>
		<tr style="height: {{ $pt(39.75) }}">
			<td colspan="2" class="labt">Manufacturer:</td>
			<td colspan="3" class="val">{{ $model->manufacturer }}</td>
			<td colspan="2" class="labt">Digital Or Analog:</td>
			<td colspan="4" class="val pgx-amber">{{ $model->digital_analog }}</td>
		</tr>
		<tr style="height: {{ $pt(39.75) }}">
			<td colspan="2" class="labt">Model:</td>
			<td colspan="3" class="val">{{ $model->model }}</td>
			<td colspan="2" class="labt">Unit Segment Location:</td>
			<td colspan="4" class="val">{{ $model->unit_segment_location }}</td>
		</tr>
	</table>

	@include('layouts.inspection.calibration.partials.show.certificate.environment', ['model' => $model])

	{{-- Rows 24-28: reference standards --}}
	<table class="blk">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(32.25) }}"><td colspan="11" class="sec">Reference Standards Used:</td></tr>
		<tr style="height: {{ $pt(30) }}">
			<td colspan="4" class="hd">Description.</td>
			<td colspan="3" class="hd">Certificate No.</td>
			<td class="hd">Due Date</td>
			<td colspan="3" class="hd">Traceability.</td>
		</tr>
		<tr style="height: {{ $pt(30) }}">
			<td colspan="4" class="verdana c f18 pgx-dark">{{ $model->device_description }}</td>
			<td colspan="3" class="verdana c f18 pgx-dark pgx-yellow">{{ $model->certificate_number }}</td>
			<td class="verdana c f18 pgx-dark">{{ $model->certificateDate('device_calibration_date') ?: '--' }}</td>
			<td colspan="3" class="verdana c f18 pgx-mid">{{ $model->device_traceability }}</td>
		</tr>
		<tr style="height: {{ $pt(31.95) }}">
			<td colspan="2" class="hd">Serial,No.</td>
			<td colspan="2" class="hd">Manufacturer.</td>
			<td colspan="2" class="hd">Model.</td>
			<td colspan="2" class="hd">Range.</td>
			<td colspan="3" class="hd">Accuracy</td>
		</tr>
		<tr style="height: {{ $pt(40.2) }}">
			<td colspan="2" class="verdana c f18">{{ $model->device_serial_number }}</td>
			<td colspan="2" class="verdana c f18">{{ $model->device_manufacturer }}</td>
			<td colspan="2" class="verdana c f18">{{ $model->device_model }}</td>
			<td colspan="2" class="verdana c f16">{{ $model->device_range }}</td>
			<td colspan="3" class="verdana c f18 pgx-yellow">{{ $pct($model->device_accuracy, 2) }}</td>
		</tr>
	</table>

	{{-- Rows 30-32: calibration method --}}
	<table class="blk">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(25.95) }}"><td colspan="11" class="sec">Calibration Method</td></tr>
		<tr style="height: {{ $pt(55.8) }}"><td colspan="11" class="abadi c f18">{!! nl2br(e(rtrim((string) $model->calibration_method, ". \n"))) !!}.</td></tr>
	</table>

	{{-- Rows 34-51: calibration results --}}
	<table class="blk">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(28.2) }}"><td colspan="11" class="sec">Calibration Results</td></tr>
		<tr style="height: {{ $pt(48.9) }}">
			<td colspan="11" class="verdana l f16" style="border-bottom: 1px dashed #000;">The instrument was calibrated against laboratory standards whose values are traceable to recognized National Standards. The uncertainty limits quoted refer to the measured values only, with no account being taken of the instruments ability to maintain its calibration.</td>
		</tr>
		<tr class="nb tnr b f18" style="height: {{ $pt(43.2) }}">
			<td colspan="2" class="c pgx-dark">Calibration Range</td>
			<td class="r">{{ $model->range_from }}</td>
			<td class="pgx-dark">{{ $unit }}</td>
			<td class="c pgx-dark">TO</td>
			<td class="r">{{ $model->range_to }}</td>
			<td class="l pgx-dark">{{ $unit }}</td>
			<td colspan="2" class="c pgx-dark">Permissible error: &nbsp; ±</td>
			<td class="r">{{ $model->max_permissible_error }}</td>
			<td class="pgx-dark">{{ $unit }}</td>
		</tr>
		<tr style="height: {{ $pt(72) }}">
			<td colspan="2" class="rh">Measured Current Value<br>UUC</td>
			<td class="rh">Raising Value<br>REF</td>
			<td class="rh">Correction</td>
			<td class="rh">Max Correction<br>Erorr</td>
			<td class="rh">Falling Value<br>REF</td>
			<td class="rh">Correction</td>
			<td class="rh">Max Correction<br>Erorr</td>
			<td colspan="3" class="rh">Expanded Uncertainty</td>
		</tr>
		<tr style="height: {{ $pt(30) }}">
			<td colspan="2" class="aptos b c f20 pgx-mid pgx-yellow">{{ $unit }}</td>
			<td colspan="3" class="aptos b c f20 pgx-mid pgx-yellow">{{ $unit }}</td>
			<td colspan="3" class="aptos b c f20 pgx-mid pgx-yellow">{{ $unit }}</td>
			<td class="aptos b r f20 pgx-dark g15">±</td>
			<td colspan="2" class="aptos b l f20 pgx-mid pgx-yellow">{{ $unit }}</td>
		</tr>
		@foreach(range(0, $rowCount - 1) as $i)
			@php $row = $resultRows[$i] ?? []; @endphp
			<tr style="height: {{ $pt(36) }}">
				<td colspan="2" class="val">{{ $num($row['instrument_reading'] ?? '') }}</td>
				<td class="val">{{ $num($row['pressure_rising'] ?? '') }}</td>
				<td class="val">{{ $num($row['p_error_rising'] ?? '') }}</td>
				@if($i === 0)
					<td rowspan="{{ $rowCount }}" class="val">{{ $num($results['max_rising']) }}</td>
				@endif
				<td class="val">{{ $num($row['pressure_falling'] ?? '') }}</td>
				<td class="val">{{ $num($row['p_error_failing'] ?? '') }}</td>
				@if($i === 0)
					<td rowspan="{{ $rowCount }}" class="val">{{ $num($results['max_falling']) }}</td>
					<td rowspan="{{ $rowCount }}" colspan="3" class="val">{{ $num($model->uncertainty) }}</td>
				@endif
			</tr>
		@endforeach
		<tr class="nb" style="height: {{ $pt(47.4) }}">
			<td class="tnr b c f20 pgx-mid">Remarks:</td>
			<td colspan="10" class="tnr l f16">{{ $model->remarks ?: 'N/A' }}</td>
		</tr>
	</table>

	{{-- Rows 53-58: specification + statement --}}
	<table class="blk">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(24) }}">
			<td colspan="11" class="tnr b l f20 pgx-dark g25">{{ $inSpec ? 'The instrument is in specification:' : 'The instrument is out of specification:' }}</td>
		</tr>
		@foreach($statementLines as $line)
			<tr class="nb" style="height: {{ $pt(27.6) }}"><td colspan="11" class="verdana f14" style="font-size: 10.5px;">{{ $line }}</td></tr>
		@endforeach
	</table>

	{{-- Rows 60-64: signatures --}}
	<table class="blk">
		{!! $colgroup !!}
		<tr style="height: {{ $pt(57) }}">
			<td colspan="2" class="tnr b c f20 pgx-dark g25">Calibrated By<br>(Lab Technician)</td>
			<td colspan="2" class="tnr b c f20 pgx-dark g25">Issued By<br>(QA)</td>
			<td colspan="2" class="tnr b c f20 pgx-dark g25">Reviewed By<br>(Lab Technical Manager)</td>
			<td colspan="2" class="tnr b c f20 pgx-dark g25">Stamp</td>
			<td colspan="3" class="tnr b c f20 pgx-dark g25">QR Code</td>
		</tr>
		<tr style="height: {{ $pt(63) }}">
			<td colspan="2" class="corsiva c f24" style="border-bottom: 0;">{{ $person_make_report }}</td>
			<td colspan="2" class="corsiva c f24" style="border-bottom: 0;">{{ $model->issued_by }}</td>
			<td colspan="2" class="corsiva c f24" style="border-bottom: 0;">
				@if(!empty($user_id_approved))
					{{ optional($approvedEmployee)->name }}
				@endif
			</td>
			<td colspan="2" rowspan="3" class="c" style="position: relative;">
				@if($isApproved)
					<img src="{{ asset('app-assets/images/logo/stamp.jpeg') }}" class="{{ $pdfUploaded ? '' : 'stamp-on-upload' }}" style="width: 10em; height: 7.5em;" alt="" />
					@unless($pdfUploaded)
						<div class="stamp-pending-note no-print" data-html2canvas-ignore>Stamp &amp; QR are added when the PDF is uploaded</div>
					@endunless
				@endif
			</td>
			<td colspan="3" rowspan="3" class="c">
				@if($isApproved)
					<img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(URL($pdfurl), 'QRCODE', 3, 3) }}" class="{{ $pdfUploaded ? '' : 'stamp-on-upload' }}" style="max-width: 118px; max-height: 118px;" alt="QR" />
				@endif
			</td>
		</tr>
		<tr style="height: {{ $pt(60.6) }}">
			<td colspan="2" class="c" style="border-top: 0; border-bottom: 0;">
				@if($esign)
					<img src="{{ Storage::url('employees/') }}{{ $esign }}" style="max-width: 130px; max-height: 40px;" alt="" />
				@endif
			</td>
			<td colspan="2" style="border-top: 0; border-bottom: 0;"></td>
			<td colspan="2" class="c" style="border-top: 0; border-bottom: 0;">
				@if(empty($user_id_approved))
					@if($hasPermission)
						<button type="button" id="approve" class="btn btn-warning btn-sm btn-print waves-effect waves-light approve"><i class="la la-paper-plane-o mr-50"></i>Approve</button>
					@else
						<span class="info verdana f16">Waiting For Approve</span>
					@endif
				@elseif(optional($approvedEmployee)->esign)
					<img src="{{ Storage::url('employees/') }}{{ $approvedEmployee->esign }}" style="max-width: 130px; max-height: 40px;" alt="" />
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
