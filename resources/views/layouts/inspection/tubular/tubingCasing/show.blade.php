@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
    <style>
        .summary-row {
            font-size: 17px;
            line-height: 37px;
        }
        .border_left_custom {
            border-left: solid 1px black;
        }
        .inspection_data_radio {
            padding: 0;
            margin: 1px;
            width: 15px;
            height: 15px;
        }
    </style>

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
        <h6 class="white text-bold-600 pl-1 mb-0">Examination Date</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->examination_date}}</div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Specification:</h6>
    <div class="border-dark pl-1 mb-0 text-16 black col-6">
        <div class="row skin skin-square">
            @foreach($specificationOptions as $key => $value)
                @if($key != 'S-008')
                    <div class="specinsp" style="margin-left: 8px;">
          <span class="noncheckedfrom {{in_array($key, $model->specification)? 'checked' : ''}}"
                style="position: relative;top: 3px;"></span>
                        <label>{{$value}}</label>
                    </div>
                @endif
            @endforeach
            <div class="specinsp" style="margin-left: 8px;">
          <span class="noncheckedfrom {{in_array('S-008', $model->specification)? 'checked' : ''}}"
                style="position: relative;top: 3px;"></span>
                <label>Other</label>
            </div>
        </div>
    </div>
    <p class="border-dark mb-0 text-16 black col-1 p-0" style="font-size: 100%;">{{$model->other_specification}}</p>
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-1 middle">Edition</h6>
    <p class="border-dark pl-1 mb-0 text-16 black col-2">{{$model->edition}}</p>
</div>
<div class="row">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Inspection Method:</h6>
    <div class="border-dark pl-1 mb-0 text-16 black col-7">
        <div class="row skin skin-square">
            @foreach($inspectionMethods as $item)
                <div class="specinsp" style="margin-left: 8px;">
          <span class="noncheckedfrom {{in_array($item, $model->inspection_method)? 'checked' : ''}}"
                style="position: relative;top: 3px;"></span>
                    <label>{{$item}}</label>
                </div>
            @endforeach
            <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom {{$model->other_inspection_method ? 'checked' : ''}}"
              style="position: relative;top: 3px;"></span>
                <label>other</label>
            </div>
        </div>
    </div>
    <p class="border-dark mb-0 text-16 black col-3 p-0" style="font-size: 100%;">{{$model->other_inspection_method}}</p>
</div>
<div class="row">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Equipment Used:</h6>
    <div class="border-dark pl-1 mb-0 text-16 black col-10">
        <div class="row skin skin-square">
            @foreach($model->equipment_no as $item)
                <div class="specinsp" style=" min-width: 145px;">
                    {{--<span class="noncheckedfrom checked" style="position: relative;top: 3px;"></span>--}}
                    @if(array_key_exists('equipment_used', $item))
                        <p class="border-dark pl-1 mb-0 text-16 black" style="min-width: 105px;">
                            {{$item['equipment_used'] == 'Other'? $item['other_equipment'] : $item['equipment_used'] }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    {{--<p class="border-dark mb-0 text-16 black col-3 p-0" style="font-size: 100%;">{{$model->other_equipment}}</p>--}}
</div>
<div class="row">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Equipment No:</h6>
    <div class="border-dark pl-1 mb-0 text-16 black col-10">
        <div class="row">
            @foreach($model->equipment_no as $item)
                {{--<div style="margin-right: 4px; margin-left: 4px;">--}}
                <p class="border-dark pl-1 mb-0 text-16 black"
                   style="min-width: 145px;">{{$item['equipment_no_value']}}</p>
                {{--</div>--}}
            @endforeach
        </div>
    </div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Description:</h6>
    </div>
    <div class="col-6 p-0 pl-1 border-dark text-16 black">
			<span>{{$model->pipe_description}}</span>
    </div>
    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Pipe Status:</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">
        <div class="row skin skin-square">
            @foreach(['New', 'Used'] as $item)
                <div class="specinsp" style="margin-left: 8px;">
                    <span class="noncheckedradiofrom {{$model->pipe_status === $item ? 'checked' : ''}}"
                          style="position: relative;top: 3px;"></span>
                    <label>{{$item}}</label>
                </div>
            @endforeach
        </div>
    </div>
  
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<div class="col-12 bg-dark p-0 border-dark">
			<h6 class="white text-bold-600 pl-1 mb-0">Pipe Details</h6>
	</div>
</div>
<div class="row">

			<div class="col-5 border-dark text-16 black">
				<div class="row my-1 pl-1">
					<div class="col-4 p-0 text-bold-600">Type of Pipe:</div>
					<div class="col-8 p-0">{{$model->pipe_type}}</div>
				</div>
				<div class="row mb-1 pl-1">
					<div class="col-4 p-0 text-bold-600">Nominal OD:</div>
					<div class="col-8 p-0">{{$model->nominal_od}}</div>
				</div>
				<div class="row mb-1 pl-1">
					<div class="col-4 p-0 text-bold-600">Grade:</div>
					<div class="col-8 p-0">{{$model->grade}}</div>
				</div>
				<div class="row mb-1 pl-1">
					<div class="col-4 p-0 text-bold-600">Lbs/Ft:</div>
					<div class="col-8 p-0">{{$model->lbs_ft}}</div>
				</div>
				<div class="row mb-1 pl-1">
					<div class="col-4 p-0 text-bold-600">Threads:</div>
					<div class="col-8 p-0">{{$model->threads}}</div>
				</div>
				<div class="row mb-1 pl-1">
					<div class="col-4 p-0 text-bold-600">Range:</div>
					<div class="col-8 p-0">{{$model->range}}</div>
				</div>
			</div>

			<div class="col-7 border-dark text-16 black" style="width: 50%;">
				<div class="row my-1 pl-1">
					<div class="col-6 p-0 text-bold-600">Nominal WallThickness:</div>
					<div class="col-2 p-0">{{$model->nominal_wall_thickness_mm}}</div>
					<div class="col-1 p-0"><span class="text-bold-600">mm</span></div>
					<div class="col-2 p-0"><span>{{$model->nominal_wall_thickness_inch}}</span></div>
					<div class="col-1 p-0"><span class="text-bold-600 mr-1">Inch</span></div>
				</div>
				
				<div class="row my-1 pl-1">
					<div class="col-6 p-0 text-bold-600">5% of Nominal WallThickness:</div>
					<div class="col-2 p-0">{{$model->wall_thickness_1_mm}}</div>
					<div class="col-1 p-0"><span class="text-bold-600">mm</span></div>
					<div class="col-2 p-0"><span>{{$model->wall_thickness_1_inch}}</span></div>
					<div class="col-1 p-0"><span class="text-bold-600  mr-1">Inch</span></div>
				</div>
				<div class="row my-1 pl-1">
					<div class="col-6 p-0 text-bold-600">12.5% of Nominal WallThickness:</div>
					<div class="col-2 p-0">{{$model->wall_thickness_2_mm}}</div>
					<div class="col-1 p-0"><span class="text-bold-600">mm</span></div>
					<div class="col-2 p-0"><span>{{$model->wall_thickness_2_inch}}</span></div>
					<div class="col-1 p-0"><span class="text-bold-600  mr-1">Inch</span></div>
				</div>
				<div class="row my-1 pl-1">
					<div class="col-6 p-0 text-bold-600">87.5% of Nominal WallThickness:</div>
					<div class="col-2 p-0">{{$model->wall_thickness_3_mm}}</div>
					<div class="col-1 p-0"><span class="text-bold-600 ">mm</span></div>
					<div class="col-2 p-0"><span>{{$model->wall_thickness_3_inch}}</span></div>
					<div class="col-1 p-0"><span class="text-bold-600  mr-1">Inch</span></div>
				</div>
				
			</div>

</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
    <div class="col-12" style="padding: 0;">
        <table style="width: 100%;" class="text-center">
            <thead style="width: 100%;">
            <tr>
                <td  class=" bg-dark p-0 border-dark white text-bold-600" style="width: 13%;">
									<span>QUANTITY</span>
                </td>
                <td  class=" bg-dark p-0 border-dark white text-bold-600" style="width: 13%;">
                  <span>BODY</span>
                </td>
                <td  class=" bg-dark p-0 border-dark white text-bold-600" style="width: 13%;">
									<span>COUPLING</span>
                </td>
                <td  class=" bg-dark p-0 border-dark white text-bold-600" style="width: 13%;">
									<span>PIN</span>
                </td>
                <td  class=" bg-dark p-0 border-dark white text-bold-600" style="width: 48%;">
									<span>REMARKS</span>
                </td>
            </tr>
            </thead>

            <tbody data-repeater-list="inspection_data">
            @foreach(isset($model) && is_array($model->inspection_data) && count($model->inspection_data )  ? $model->inspection_data : $inspection_data_template as $index => $item)
                <tr data-repeater-item>
                    <td class="p-0 white">
											<table style="width: 100%; text-align: center;">
												<tr style="height: 38px;">
														<td class=" p-0 border-dark white">
																<span>{{$item['input_1']}}</span>
														</td>
												</tr>
										</table>
                    </td>
                    <td class="p-0 white">
											<table style="width: 100%; text-align: center;">
												<tr style="height: 38px;">
														<td class=" p-0 border-dark white">
																<span>{{$item['input_2']}}</span>
														</td>
												</tr>
										</table>
                    </td>
                    <td class="p-0 white">
											<table style="width: 100%; text-align: center;">
												<tr style="height: 38px;">
														<td class=" p-0 border-dark white">
																<span>{{$item['input_3']}}</span>
														</td>
												</tr>
										</table>
                    </td>
                    <td class="p-0 white">
											<table style="width: 100%; text-align: center;">
												<tr style="height: 38px;">
														<td class=" p-0 border-dark white">
																<span>{{$item['input_4']}}</span>
														</td>
												</tr>
										</table>
                    </td>
                    <td class="p-0 white">
                        <table style="width: 100%; text-align: center;">
                            <tr style="height: 38px;">
                                <td class=" p-0 border-dark white">
                                    <span>{{$item['input_5']}}</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
    <div class="col-12 bg-dark p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Comments</h6>
    </div>
		<div class="col-12 p-0 pl-1 border-dark black" style="min-height: 3.5em;">
			{!! nl2br(e($model->comment)) !!}
		</div>
</div>
<!----------------------------------->
<div class="row">
	<div class="col-5 border-dark text-16 black">
		<div class="row my-1 pl-1">
			<div class="col-9 p-0 text-bold-600">Abbreviation Code Used In This Report</div>
{{--			<div class="col-3 p-0">{{$model->abbreviation_code}}</div>--}}
		</div>
	</div>
	<div class="col-7 border-dark text-16 black" style="width: 50%;">
		<div class="row my-1 pl-1">
			<div class="col-9 p-0 text-bold-600">Total Items Inspected:</div>
			<div class="col-3 p-0">{{$model->total_items_inspected}}</div>
		</div>

		<div class="row my-1 pl-1">
			<div class="col-9 p-0 text-bold-600">Total items ready for use:</div>
			<div class="col-3 p-0">{{$model->total_items_ready}}</div>
		</div>

	</div>

</div>
<!----------------------------------->
<div class="row">
    <div class="col-12 bg-dark p-0 border-dark white">
        <table style="width: 98%; border-collapse: collapse; border: none; margin: 0 auto; font-size: 13px; line-height: 13px;">
            <tr >
                <td>FLD: Full Length Drift</td>
                <td>VTI: Visual Thread Insp.</td>
                <td>MPI: Magnetic Particle Insp.</td>
                <td>EMI: Electro Magnetic Insp.</td>
            </tr>
						<tr >
                <td>EAI: End Area Insp.</td>
                <td>WT: Wall Thickness</td>
                <td>TGI: Thread Gauging Insp.</td>
                <td>A1: Distance from pin face to Triangle base</td>
            </tr>
        </table>
    </div>
</div>
<!----------------------------------->
@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $model->report->id])
@endpush
