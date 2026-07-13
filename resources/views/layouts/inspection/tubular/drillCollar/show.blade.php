@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
    <style>
        .summary-row {
            font-size: 17px;
            line-height: 37px;
        }

        .donw {
            min-width: fit-content !important;
            height: 1450px !important;
            /*overflow-x: scroll;*/
        }

        #inspection_data {
            display: contents;
        }
        /*.vertical-cell{
            text-orientation: mixed;
            text-wrap: nowrap;
            writing-mode: tb-rl;
            transform: rotate(-180deg);
            border: solid 2px;
        }*/
        .standards {
            background-color: white;
        }
        hr{
            margin: 1px 0px;
            border-block-color: black;
        }
        .standards_input{
            border: none;
            background-color: inherit;
            padding: 0;
            text-align: center;
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
    <div class="row" style="margin-top: 3px; min-height: 4em;">
       {{--<div class="col-12" style="padding: 0;">
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td class="border-dark bg-dark white text-bold-600 text-center" style="width: 13%;">
                        <span>Inspection Description</span>
                    </td>
                    <td class="border-dark black text-center" style="width: 28%;">
                        <span>{{$model->inspection_description}}</span>
                    </td>
                    <td class="border-dark bg-dark white text-bold-600 text-center" style="width: 10%;">
                        <span>Joint Description</span>
                    </td>
                    <td class="border-dark black text-center" style="width: 24%;">
                        <span>{{$model->dc_description}}</span>
                    </td>

                    <td class="border-dark bg-dark white text-bold-600 text-center" style="width: 5%;">
                        <span>Size</span>
                    </td>
                    <td class="border-dark black text-center" style="width: 5%">
                        <span>{{$model->size}}</span>
                    </td>

                    <td class="border-dark bg-dark white text-bold-600 text-center" style="width: 10%;">
                        <span>Connection Type</span>
                    </td>
                    <td class="border-dark black text-center" style="width: 5%">
                        <span>{{$model->connection}}</span>
                    </td>
                </tr>
            </table>
        </div>--}}
        <div class="bg-dark col-2 p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Joint Description</h6>
        </div>
        <div class="col-4 p-0 pl-1 border-dark text-16 black">
            {{$model->dc_description}}
        </div>

        <div class="bg-dark col-1 p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Size</h6>
        </div>
        <div class="col-2 p-0 pl-1 border-dark text-16 black">
            {{$model->size}}
        </div>

        <div class="bg-dark col-2 p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Connection Type</h6>
        </div>
        <div class="col-1 p-0 pl-1 border-dark text-16 black">
            {{$model->connection}}
        </div>

    </div>
		    <!----------------------------------->
				<div class="row" style="min-height: 4em;">
					<div class="bg-dark col-2 p-0 border-dark">
							<h6 class="white text-bold-600 pl-1 mb-0">Inspection Applied</h6>
					</div>
					<div class="col-6 p-0 pl-1 border-dark text-16 black">
							{{$model->inspection_applied}}
					</div>
	
					<div class="bg-dark col-2 p-0 border-dark">
							<h6 class="white text-bold-600 pl-1 mb-0">Internal Service Order</h6>
					</div>
					<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->internal_service_order}}</div>
			</div>
	
    <!----------------------------------->
    <div class="row" style="margin-top: 3px;">
        <div class="col-12" style="padding: 0;">
            <table style="width: 127%; margin-left:-12px; text-align: center;" class="text-bold-600 text-center"
                   id="inspection_data">
                <thead style="width: 100%; font-size: 70%;">
                <tr>
                    <td  class=" bg-dark p-0 border-dark white" style="width: 5%;">
                        <table style="width: 100%; text-align: center;">
                            <tr>
                                <td style="height: 62px;">Serial Number</td>
                            </tr>
                        </table>
                    </td>
                    <td class=" bg-dark p-0 border-dark white" style="width: 40%;">
                        <table style="width: 100%; text-align: center;">
                            <tr>Box Connection Dimension</tr>
                            <tr>
                                <td class=" bg-dark p-0 border-dark white" style="width: 10%">
                                    Box<br/>Tong<br/>
                                    <hr/>
                                    {{ Form::text('standards[input_1]', '< 10', ['id' => 'standards_input_1', 'class' => 'standards_input', 'readonly']) }}

                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 10%">D</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 10%">DF</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 10%">Seal Width</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 10%">
                                    QC<br/>Max
                                    <hr/>
                                    {{ Form::text('standards[input_2]', '4 9/64', ['id' => 'standards_input_2', 'class' => 'standards_input', 'readonly']) }}

                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 10%">
                                    CBL<br/>Min
                                    <hr/>
                                    {{ Form::text('standards[input_3]', '9/16', ['id' => 'standards_input_3', 'class' => 'standards_input', 'readonly']) }}
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 10%">Lbc</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 10%">
                                    DCB
                                    <hr/>
                                    {{ Form::text('standards[input_4]', '<3 30/64', ['id' => 'standards_input_4', 'class' => 'standards_input', 'readonly']) }}
                                    <hr/>
                                    {{ Form::text('standards[input_5]', '>3 31/64', ['id' => 'standards_input_5', 'class' => 'standards_input', 'readonly']) }}

                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 10%">BBCL</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 10%">Condition</td>
                            </tr>
                        </table>

                    </td>
                    <td class=" bg-dark p-0 border-dark white" style="width: 41%;">
                        <table style="width: 100%; text-align: center;">
                            <tr>Pin Connection Dimension</tr>
                            <tr>
                                <td class=" bg-dark p-0 border-dark white" style="width: 9%;">
                                    Pin<br/>Tong
                                    <hr/>
                                    {{ Form::text('standards[input_6]', '> 10', ['id' => 'standards_input_6', 'class' => 'standards_input', 'readonly']) }}
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 9%;">D</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 9%;">d</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 9%;">DF</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 9%;">Seal<br>Width</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 9%;">
                                    Lpc<br/>Max
                                    <hr/>
                                    {{ Form::text('standards[input_7]', '4 1/16', ['id' => 'standards_input_7', 'class' => 'standards_input', 'readonly']) }}
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 9%;">
                                    DRG
                                    <hr/>
                                    {{ Form::text('standards[input_8]', '<3 31/64', ['id' => 'standards_input_8', 'class' => 'standards_input', 'readonly']) }}
                                    <hr/>
                                    {{ Form::text('standards[input_9]', '<3 33/64', ['id' => 'standards_input_9', 'class' => 'standards_input', 'readonly']) }}

                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 9%;">
                                    SRGL
                                    <hr/>
                                    {{ Form::text('standards[input_10]', '< 23/32', ['id' => 'standards_input_10', 'class' => 'standards_input', 'readonly']) }}
                                    <hr/>
                                    {{ Form::text('standards[input_11]', '>1 1/32', ['id' => 'standards_input_11', 'class' => 'standards_input', 'readonly']) }}

                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 9%;">Slip Recess</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 9%;">Elevator Recess</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 9%;">Condition</td>
                            </tr>
                        </table>

                    </td>
                    <td class=" bg-dark p-0 border-dark white" style="width: 4%;">
                        <table style="width: 100%; text-align: center;">
                            <tr>
                                <td style="height: 62px;">Overall Length</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                </thead>

                <tbody data-repeater-list="inspection_data" style="font-size: 70%;">
                @foreach(isset($model) ? $model->inspection_data : $inspection_data_template as $item)
                    <tr data-repeater-item>
                        <td class="p-0 white" style="height: 22px;">
                            <table style="width: 100%; text-align: center;">
                                <tr>
                                    <td class=" p-0 border-dark white" style="width: 30%; height: 22px;">
                                        <span>{{$item['input_1']}}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="height: 22px;">
                            <table style="width: 100%; text-align: center;">
                                <tr>
                                    <td class="p-0 border-dark white" style="width: 10%; height: 22px;">
                                        <span>{{$item['input_2']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 10%; height: 22px;">
                                        <span>{{$item['input_3']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 10%">
                                        <span>{{$item['input_4']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 10%">
                                        <span>{{$item['input_5']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 10%">
                                        <span>{{$item['input_6']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 10%">
                                        <span>{{$item['input_7']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 10%">
                                        <span>{{$item['input_8']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 10%">
                                        <span>{{$item['input_9']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 10%">
                                        <span>{{$item['input_10']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 10%">
                                        <span>{{$item['input_11']}}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="height: 22px;">
                            <table style="width: 100%; text-align: center;">
                                <tr>
                                    <td class="p-0 border-dark white" style="width: 7.6%; height: 22px;">
                                        <span>{{$item['input_12']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%; height: 22px;">
                                        <span>{{$item['input_13']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%; height: 22px;">
                                        <span>{{$item['input_14']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%; height: 22px;">
                                        <span>{{$item['input_15']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%; height: 22px;">
                                        <span>{{$item['input_16']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%; height: 22px;">
                                        <span>{{$item['input_17']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.14%;">
                                        <span>{{$item['input_18']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.14%;">
                                        <span>{{$item['input_19']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.14%;">
                                        <span>{{$item['input_20']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.14%;">
                                        <span>{{$item['input_21']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.14%;">
                                        <span>{{$item['input_22']}}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="height: 22px;">
                            <table style="width: 100%; text-align: center;">
                                <tr>
                                    <td class=" p-0 border-dark white" style="height: 22px;">
                                        <span>{{isset($item['input_23']) ? $item['input_23'] : ''}}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>

                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!----------------------------------->
    <div class="row" style="margin-top: 1px; font-weight: 90%;">
        <div class="bg-dark col-3 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Connection Defective (Red)</h6></div>
        <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->connection_defective}}</div>

        <div class="bg-dark col-3 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Connection Accepted (White)</h6></div>
        <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->connection_accepted}}</div>

        <div class="bg-dark col-3 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Connection to be Repaired</h6></div>
        <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->connection_to_be_repaired}}</div>
    </div>
    <div class="row" style="font-size: 90%;">
        <div class="bg-dark col-3 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Total Connection Inspected</h6></div>
        <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->total_connection_inspected}}</div>

        <div class="bg-dark col-3 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Joints to be Repaired</h6></div>
        <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->joints_to_be_repaired}}</div>

        <div class="bg-dark col-3 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Total Joints Inspected</h6></div>
        <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->total_joints_inspected}}</div>
    </div>
    <div class="row" style="margin-top: 3px;">
        <div class="col-12 bg-dark p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Comments</h6>
        </div>
        <div class="col-12 p-0 pl-1 border-dark black" >
            {!! nl2br(e($model->comment)) !!}
        </div>
    </div>
    <!----------------------------------->
    <div class="row">
        <div class="col-12 bg-dark p-0 border-dark white">
            <table style="width: 98%; border-collapse: collapse; border: none; margin: 0 auto; font-size: 10px; line-height: 12px;">
                <tr style="border-left: 1px;">
                    <td style="margin: 1px">LB: Box Tong Space</td>
                    <td>SW: Seal Width</td>
                    <td>DCB: Boreback Cyl Diameter</td>
                    <td>d :I.D. of Pin</td>
                    <td>DRG: Stress Relief Groove Dia.</td>
                    <td>SD: Shoulder Damage</td>
                    <td>BW: Box Widening</td>
                    <td>W: Washout</td>
                </tr>
                <tr>
                    <td>D: Outside Diameter</td>
                    <td>QC: Box C'bore Diameter</td>
                    <td>BBCL : Boreback Cyl Length</td>
                    <td>DF: Bevel Diameter</td>
                    <td>SRGL: Stress Relief Groove Length</td>
                    <td>TD: Thread Damage</td>
                    <td>CO: Corrosion</td>
                    <td>C: Crack</td>
                </tr>
                <tr>
                    <td>DF: Bevel Diameter</td>
                    <td>CBL: Box C'bore Depth</td>
                    <td>LPB: Pin Tong Space</td>
                    <td>PL: Pin Lead</td>
                    <td>< / >: minimum / maximum</td>
                    <td>TE: Thread Elongation</td>
                    <td>P: Pitting</td>
                    <td>M: Flatspot / Mash</td>
                </tr>

            </table>
        </div>
    </div>
    <!----------------------------------->
    @include('layouts.styles.reportfooter-v2')
    @include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $model->report->id])
@endpush
