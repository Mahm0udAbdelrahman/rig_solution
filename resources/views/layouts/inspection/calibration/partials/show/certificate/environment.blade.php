{{--
    Shared environmental conditions block of all calibration certificates (Excel rows 20-22).
    Tolerance / uncertainty columns stay empty for reports that do not record them.
    @include('layouts.inspection.calibration.partials.show.certificate.environment', ['model' => $model])
--}}
@php
	$cert = \App\Support\CalibrationCertificate::class;
	$environment = [
		['Temperature:', data_get($model, 'temperature'), '°C', data_get($model, 'temperature_tolerance'), data_get($model, 'temperature_uncertainty'), 39],
		['Relative Humidity:', data_get($model, 'relative_humidity'), '% RH', data_get($model, 'humidity_tolerance'), data_get($model, 'humidity_uncertainty'), 32.4],
	];
@endphp
<table class="blk">
	{!! $cert::colgroup() !!}
	<tr style="height: {{ $cert::rowHeight(39) }}"><td colspan="11" class="sec">Environmental Conditions During Calibration</td></tr>
	@foreach($environment as [$label, $value, $envUnit, $tolerance, $uncertainty, $height])
		<tr class="nb" style="height: {{ $cert::rowHeight($height) }}">
			<td colspan="2"></td>
			<td colspan="2" class="verdana b c f16 pgx-mid">{{ $label }}</td>
			<td class="verdana r f16">{{ $value }}</td>
			<td class="verdana b f16 pgx-mid">{{ $envUnit }}</td>
			<td class="verdana b l f16 pgx-mid">@if($tolerance !== null && $tolerance !== '')±{{ $tolerance }}@endif</td>
			<td class="verdana r f16">{{ $uncertainty }}</td>
			<td class="verdana b f16 pgx-mid">@if($uncertainty !== null && $uncertainty !== ''){{ $envUnit }}@endif</td>
			<td colspan="2"></td>
		</tr>
	@endforeach
</table>
