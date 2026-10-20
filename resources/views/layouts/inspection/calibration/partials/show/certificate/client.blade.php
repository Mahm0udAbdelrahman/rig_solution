{{--
    Shared client block of all calibration certificates (Excel rows 6-11).
    @include('layouts.inspection.calibration.partials.show.certificate.client', ['model' => $model])
--}}
@php
	$cert = \App\Support\CalibrationCertificate::class;
	$jobRequest = $model->job_request;
	$party = $jobRequest->client ?: $jobRequest->supplier;
@endphp
<table class="blk">
	{!! $cert::colgroup() !!}
	<tr style="height: {{ $cert::rowHeight(36) }}">
		<td colspan="5" class="abadi b c f22 pgx-dark g15">Name of Client for whom the examination was made</td>
		<td colspan="6" class="abadi b c f22 pgx-dark g15">Address of Client</td>
	</tr>
	<tr style="height: {{ $cert::rowHeight(37.8) }}">
		<td colspan="5" class="val b">{{ optional($party)->name }}</td>
		<td colspan="6" class="val">{{ preg_replace('~[\\\\/:*?"<>[]|]~', '', (string) optional($party)->location) }}</td>
	</tr>
	<tr style="height: {{ $cert::rowHeight(30) }}">
		<td colspan="2" class="lab">Purchase Order :</td>
		<td colspan="3" class="val l">{{ $jobRequest->purchase_order }}</td>
		<td colspan="2" class="lab">JCF Number:</td>
		<td colspan="4" class="abadi b c f18 pgx-dark">{{ $jobRequest->code }}</td>
	</tr>
	<tr style="height: {{ $cert::rowHeight(38.4) }}">
		<td colspan="2" class="lab">Calibration Location:</td>
		<td colspan="3" class="val l">{{ $cert::location($model) }}</td>
		<td colspan="2" class="lab">Calibration Date:</td>
		<td colspan="4" class="val">{{ $cert::date($model, 'calibration_date') }}</td>
	</tr>
	<tr style="height: {{ $cert::rowHeight(38.4) }}">
		<td colspan="2" class="lab">Receipt Date:</td>
		<td colspan="3" class="val l">{{ $cert::date($model, 'receipt_date') }}</td>
		<td colspan="2" class="lab">Calibration Due Date</td>
		<td colspan="4" class="val">{{ $cert::date($model, 'due_date') }}</td>
	</tr>
	<tr style="height: {{ $cert::rowHeight(31.8) }}">
		<td colspan="2" class="lab">Contact Name:</td>
		<td colspan="3" class="val l">{{ $model->contact_name ?: $jobRequest->contactName() }}</td>
		<td colspan="2" class="lab">Contact information:</td>
		<td colspan="4" class="val">{{ $model->contact_info ?: $jobRequest->contactInfo() }}</td>
	</tr>
</table>
