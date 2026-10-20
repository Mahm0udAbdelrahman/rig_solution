{{--
    Shared environmental conditions block of all calibration certificates (Excel rows 20-22).
    Tolerance / variation columns stay empty for reports that do not record them
    (the variation is stored in the temperature_uncertainty / humidity_uncertainty fields).
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
	@foreach($environment as [$label, $value, $envUnit, $tolerance, $variation, $height])
		@php $hasVariation = $variation !== null && $variation !== ''; @endphp
		<tr class="nb" style="height: {{ $cert::rowHeight($height) }}">
			<td></td>
			<td colspan="2" class="verdana b c f16 pgx-mid">{{ $label }}</td>
			<td class="verdana r f16">{{ $value }}</td>
			<td class="verdana b f16 pgx-mid">{{ $envUnit }}</td>
			<td class="verdana b l f16 pgx-mid">@if($tolerance !== null && $tolerance !== '')±{{ $tolerance }}@endif</td>
			<td colspan="2" class="verdana b r f16 pgx-mid">@if($hasVariation)Variation:@endif</td>
			<td class="verdana r f16">{{ $variation }}</td>
			<td class="verdana b f16 pgx-mid">@if($hasVariation){{ $envUnit }}@endif</td>
			<td></td>
		</tr>
	@endforeach
</table>
