{{--
    Shared header of all calibration certificates (Excel rows 1-5): first logo (dynamic) + RIG logo | title, certificate no, issue date.
    @include('layouts.inspection.calibration.partials.show.certificate.header', ['model' => $model, 'code' => $code])
--}}
@php
	$cert = \App\Support\CalibrationCertificate::class;
@endphp
<table>
	{!! $cert::colgroup() !!}
	<tr style="height: {{ $cert::rowHeight(59.4) }}">
		<td colspan="5" rowspan="5" style="vertical-align: top; padding: 0;">
			<div class="logos">
				<img src="{{ $cert::firstLogoUrl($model) }}" class="first" alt="" />
				<img src="{{ asset($cert::RIG_LOGO) }}" class="rig" alt="Rig Solution" />
			</div>
		</td>
		<td colspan="6"></td>
	</tr>
	<tr style="height: {{ $cert::rowHeight(59.4) }}">
		<td colspan="6" class="aptos b c" style="font-size: 32.6px; color: #e26714; white-space: nowrap;">CALIBRATION CERTIFICATE</td>
	</tr>
	<tr style="height: {{ $cert::rowHeight(59.4) }}"><td colspan="6"></td></tr>
	<tr style="height: {{ $cert::rowHeight(59.4) }}">
		<td colspan="2"></td>
		<td class="aptos b r f22 g15" style="color: #0070c0; border-top: 2px dashed #000; border-bottom: 3px solid #000; white-space: nowrap;">Certificate No:</td>
		<td colspan="2" class="aptos b c f22 g15" style="color: #0070c0; border-top: 2px dashed #000; border-bottom: 3px solid #000; white-space: nowrap;" data-type="code" data-id="{{ $model->id }}">{{ $code }}</td>
		<td></td>
	</tr>
	<tr style="height: {{ $cert::rowHeight(58.95) }}">
		<td colspan="2"></td>
		<td class="aptos b r f20" style="color: #0070c0;">Issue date:</td>
		<td colspan="2" class="aptos b c f20 pgx-dark">{{ $cert::date($model, 'issue_date') }}</td>
		<td></td>
	</tr>
</table>
