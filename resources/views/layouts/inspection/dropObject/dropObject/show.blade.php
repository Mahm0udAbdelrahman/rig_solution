@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
    <style>
        .donw {
            min-width: fit-content !important;
            height: 1450px !important;
            /*overflow-x: scroll;*/
        }

        #inspection_data {
            display: contents;
        }

		.cell_p {
			text-align: left;
			font-size: 12px;
		}
        


    </style>
    <input type="hidden" id="page_mode" value="l">
    <!----------------------------------->
    <div class="row page_in">
			<div class="col-6 p-0">
					<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
					<p class="border-dark pl-1 mb-0 text-16 black">
							@if($model->job_request->client)
									{{$model->job_request->client->name}}
							@else
									{{$model->job_request->supplier->name}}
							@endif
					</p>
			</div>
			<div class="col-6 p-0">
					<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
					<p class="border-dark pl-1 mb-0 text-16 black">{{preg_replace('~[\\\\/:*?"<>[]|]~', '',$model->job_request->client? $model->job_request->client->location : $model->job_request->supplier->location)}}</p>
			</div>
	</div>
	<!----------------------------------->
	<div class="row">
			<div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6></div>
			<div class="col-2 p-0 pl-1 border-dark text-16 black">
					{{$model->job_request->purchase_order}}</div>
			<div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6></div>
			<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->job_request->code}}</div>
			<div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Report No</h6></div>

			<div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$model->id}}">{{$code}}</div>

	</div>
	<!----------------------------------->
	<div class="row">
			<div class="bg-dark col-2 p-0 border-dark">
					<h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
			</div>
			<div class="col-6 p-0 pl-1 border-dark text-16 black">
					{{$model->job_request->clientDepartment ?
							$model->job_request->clientDepartment->name . ' / '.$model->job_request->deploc
							: $model->job_request->deploc}}
			</div>
			<div class="bg-dark col-2 p-0 border-dark">
					<h6 class="white text-bold-600 pl-1 mb-0">Survey Date</h6>
			</div>
			<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->survey_date}}</div>
	</div>
	<!----------------------------------->
	<div class="row">
		<div class="bg-dark col-2 p-0 border-dark">
			<h6 class="white text-bold-600 pl-1 mb-0">Inspection Area</h6>
		</div>
		<div class="col-10 p-0 pl-1 border-dark text-16 black">
			{{$model->inspection_area == 'other' ? $model->other_inspection_area : $inspectionAreaOptions[$model->inspection_area]}}
		</div>
	</div>
    <!----------------------------------->
	<div class="row" style="margin-top: 3px;">
		<div class="col-12" style="padding: 0;">
			<table id="inspection_table" style="width: 100%; text-align: center;">
				<thead style="width: 100%;">
					<tr>
						<th class="bg-dark p-0 border-dark white text-center" style="width: 5%;">Item</th>
						<th class="bg-dark p-0 border-dark white text-center" style="width: 15%;">Photo</th>
						<th class="bg-dark p-0 border-dark white text-center" style="width: 7.5%;">Description</th>
						<th class="bg-dark p-0 border-dark white text-center" style="width: 7.5%;">Photo Reference #</th>
						<th class="bg-dark p-0 border-dark white text-center" style="width: 19%;">Fastening Methods</th>
						<th class="bg-dark p-0 border-dark white text-center" style="width: 5%;">Condition</th>
						<th class="bg-dark p-0 border-dark white text-center" style="width: 17%;">Comment</th>
						<th class="bg-dark p-0 border-dark white text-center" style="width: 5%;">Frequency</th>
						<th class="bg-dark p-0 border-dark white text-center" style="width: 19%;">How To Inspect?</th>
					</tr>
				</thead>
				<tbody data-repeater-list="inspection_data">
					@foreach(isset($model) && is_array($model->inspection_data) && count($model->inspection_data) ?
					$model->inspection_data : [[]] as $item)
					<tr data-repeater-item>
						<td class="p-0 border-dark white" style="text-align: center;">
							<span>{{ isset($item['item']) ? $item['item'] : '' }}</span>
						</td>
						<td class="p-0 border-dark white" style="text-align: center;">
							<img src="{{ isset($item['photo']) ? Storage::url($item['photo']) : '' }}"
								style="max-width: 150px; max-height: 150px; width: auto; height: auto;" />
						</td>
						<td class="p-0 border-dark white" style="text-align: center;">
							<span>{{ isset($item['description']) ? $item['description'] : '' }}</span>
						</td>
						<td class="p-0 border-dark white" style="text-align: center;">
							<span>{{ isset($item['photo_reference']) ? $item['photo_reference'] : '' }}</span>
						</td>
						<td class="p-0 border-dark white cell_p" style="font-size: 80%;">
							<p style="margin: 0.5em;">{!! nl2br(e(isset($item['fastening_methods']) ? $item['fastening_methods'] : '')) !!}</p>
						</td>
						<td class="p-0 border-dark white" style="text-align: center;">
							<span>{{ isset($item['condition']) ? $item['condition'] == 'pass' ? 'Pass' : 'Fail' : ''
								}}</span>
						</td>
						<td class="p-0 border-dark white" style="text-align: center; font-size: 80%;">
							<span>{{ isset($item['comment']) ? $item['comment'] : '' }}</span>
						</td>
						<td class="p-0 border-dark white" style="text-align: center;">
							<span>{{ isset($item['frequency']) ? $item['frequency'] : '' }}</span>
						</td>
						<td class="p-0 border-dark white cell_p" style="font-size: 80%;">
							<p style="margin: 0.5em;">{!! nl2br(e(isset($item['how_to_inspect']) ? $item['how_to_inspect'] : '')) !!}</p>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
    <!----------------------------------->
    <!----------------------------------->
		<script>
			document.addEventListener('DOMContentLoaded', function () {
        let rows = document.querySelectorAll('#inspection_table tbody tr');
        let maxHeight = 0;

        rows.forEach(row => {
            const height = row.offsetHeight;
            if (height > maxHeight) maxHeight = height;
        });
				console.log('>>>>>>here>>>>>',maxHeight);
        rows.forEach(row => row.style.height = maxHeight + 'px');
    });
		</script>

    <!----------------------------------->
    @include('layouts.styles.reportfooter-v2')
    @include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $model->report->id])
@endpush
