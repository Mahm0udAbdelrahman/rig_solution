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
        .vertical-cell{
            /* text-orientation: mixed; */
            /* text-wrap: nowrap; */
            writing-mode: tb;
            /* transform: rotate(-180deg); */
            /* border: solid 2px; */
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
        <p class="border-dark mb-0 text-16 black col-2 p-0" style="font-size: 100%;">{{$model->other_specification}}</p>
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-1 middle">Edition</h6>
        <p class="border-dark pl-1 mb-0 text-16 black col-1">{{$model->edition}}</p>
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
        <div class="border-dark pl-1 mb-0 text-16 black col-6">
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
			<div class="col-4 p-0">
				<div class="row skin skin-square ml-0" style="width: 100%;">
					<div class="specinsp" style="width: 35%;">
						<p class="bg-dark border-dark text-16 black middle">Joint Description</p>
					</div>
					<div class="specinsp border-dark" style="min-width: 65%;">
						<p class=" text-16 black">{{$model->joint_description}}</p>
					</div>
				</div>
			</div>        
    </div>
    <div class="row">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Equipment No:</h6>
        <div class="border-dark pl-1 mb-0 text-16 black col-6">
            <div class="row">
                @foreach($model->equipment_no as $item)
                    {{--<div style="margin-right: 4px; margin-left: 4px;">--}}
                    <p class="border-dark pl-1 mb-0 text-16 black"
                       style="min-width: 145px;">{{$item['equipment_no_value']}}</p>
                    {{--</div>--}}
                @endforeach
            </div>
        </div>
				<div class="col-4 p-0">
					<div class="row skin skin-square ml-0" style="width: 100%;">
						<div class="specinsp" style="width: 35%;">
							<p class="bg-dark border-dark text-16 black middle">Internal S.O</p>
						</div>
						<div class="specinsp border-dark" style="min-width: 65%;">
							<p class=" text-16 black">{{$model->internal_service_order}}</p>
						</div>
					</div>
				</div>    
    </div>
    <!----------------------------------->
    <div class="row" style="margin-top: 3px;">
        <div class="col-12" style="padding: 0;">
            <table style="width: 100%;">
                <tr>
                    <td class="border-dark bg-dark white text-bold-600 text-center">
                        <span>Joint Class</span>
                    </td>
                    <td class="border-dark black text-center" style="min-width: 22px;">
                        <span>{{$model->joint_class}}</span>
                    </td>
                    <td class="border-dark bg-dark white text-bold-600 text-center">
                        <span>Grade</span>
                    </td>
                    <td class="border-dark black text-center" style="min-width: 22px;">
                        <span>{{$model->grade}}</span>
                    </td>
                    <td class="border-dark bg-dark white text-bold-600 text-center">
                        <span>Range</span>
                    </td>
                    <td class="border-dark black text-center" style="min-width: 22px;">
                        <span>{{$model->range}}</span>
                    </td>
                    <td class="border-dark bg-dark white text-bold-600 text-center">
                        <span>Weight</span>
                    </td>
                    <td class="border-dark black text-center" style="min-width: 22px;">
                        <span>{{$model->weight}}</span>
                    </td>
                    <td class="border-dark bg-dark white text-bold-600 text-center">
                        <span>Nom W.T</span>
                    </td>
                    <td class="border-dark black text-center" style="min-width: 22px;">
                        <span>{{$model->nom_w_t}}</span>
                    </td>
                    <td class="border-dark bg-dark white text-bold-600 text-center">
                        <span>OD</span>
                    </td>
                    <td class="border-dark black text-center" style="min-width: 22px;">
                        <span>{{$model->joint_od}}</span>
                    </td>
                    <td class="border-dark bg-dark white text-bold-600 text-center">
                        <span>ID</span>
                    </td>
                    <td class="border-dark black text-center" style="min-width: 22px;">
                        <span>{{$model->joint_id}}</span>
                    </td>
                    <td class="border-dark bg-dark white text-bold-600 text-center">
                        <span>T/Joint OD</span>
                    </td>
                    <td class="border-dark black text-center" style="min-width: 22px;">
                        <span>{{$model->t_joint_od}}</span>
                    </td>
                    <td class="border-dark bg-dark white text-bold-600 text-center">
                        <span>Conn.</span>
                    </td>
                    <td class="border-dark black text-center" style="min-width: 22px;">
                        <span>{{$model->conn}}</span>
                    </td>
                </tr>
            </table>

        </div>
    </div>
    <!----------------------------------->
    <div class="row" style="margin-top: 3px;">
        <div class="col-12" style="padding: 0;">
            <table style="width: 127%; margin-left:-12px; text-align: center; table-layout: fixed;" class="text-bold-600 text-center"
                   id="inspection_data">
                <thead style="width: 100%; font-size: 70%;">
                <tr>
                    <td class=" bg-dark p-0 border-dark white" style="width: 8%;">
                        <table style="width: 100%; text-align: center;">
                            <tr>
                                <td colspan="3" style="height: 80px;">Joints Details</td>
                            </tr>
                            <tr>
                                <td class=" bg-dark p-0 border-dark white" style="width: 30%; height: 22px;">J/NO</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 70%">S/No</td>
                            </tr>
                        </table>
                    </td>
                    <td class=" bg-dark p-0 border-dark white" style="width: 40%;">
                        <table style="width: 100%; text-align: center; table-layout: fixed;">
                            <tr>Box or Upper Connection</tr>
                            <tr>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">Tong<br/>min</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">D<br/>min</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">Df<br/>max</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">seal<br/>min</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">SHW<br/>min</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">Qc<br/>max</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">CBL<br/>min</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">CBW<br/>min</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">Lbc<br/>min</td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell"
                                    style="width: 7.6%; height: 71px;">F.rep.
                                </td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 7.6%">Reface</td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 7.6%">HB</td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 7.6%">Cond.</td>
                            </tr>
                            <tr>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%; height: 22px;">
                                    <span>{{$model->standards? $model->standards['input_1'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                    <span>{{$model->standards? $model->standards['input_2'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                    <span>{{$model->standards? $model->standards['input_3'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                    <span>{{$model->standards? $model->standards['input_4'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                    <span>{{$model->standards? $model->standards['input_5'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                    <span>{{$model->standards? $model->standards['input_6'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                    <span>{{$model->standards? $model->standards['input_7'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                    <span>{{$model->standards? $model->standards['input_8'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                    <span>{{$model->standards? $model->standards['input_9'] : ''}}</span>
                                </td>
                                {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                            </tr>
                        </table>

                    </td>
                    <td class=" bg-dark p-0 border-dark white" style="width: 40%;">
                        <table style="width: 100%; text-align: center;" table-layout: fixed;>
                            <tr>Pin or Lower Connection</tr>
                            <tr>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.14%;">Tong<br/>min</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.14%;">DM<br/>min</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.14%;">d<br/>max</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.14%;">Df<br/>max</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.14%;">seal<br/>min</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.14%;">Lpc<br/>max</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.14%;">PNL<br/>max</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.14%;">PCD<br/>max</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.14%;">PND<br/>max</td>
                                <td class=" bg-dark p-0 border-dark white" style="width: 7.14%;">PL<br/>+/-</td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell"
                                    style="width: 7.14%; height: 71px;">F-repair
                                </td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 7.14%;">Re-face
                                </td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 7.14%;">HB</td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 7.14%;">Cond.</td>
                            </tr>
                            <tr>
                                <td class=" bg-dark p-0 border-dark white" style="; height: 22px;">
                                    <span>{{$model->standards? $model->standards['input_10'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="">
                                    <span>{{$model->standards? $model->standards['input_11'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="">
                                    <span>{{$model->standards? $model->standards['input_12'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="">
                                    <span>{{$model->standards? $model->standards['input_13'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="">
                                    <span>{{$model->standards? $model->standards['input_14'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="">
                                    <span>{{$model->standards? $model->standards['input_15'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="">
                                    <span>{{$model->standards? $model->standards['input_16'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="">
                                    <span>{{$model->standards? $model->standards['input_17'] : ''}}</span>
                                </td>
                                <td class=" bg-dark p-0 border-dark white" style="">
                                    <span>{{$model->standards? $model->standards['input_18'] : ''}}</span>
                                </td>
                                {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                            </tr>
                        </table>

                    </td>
                    <td class=" bg-dark p-0 border-dark white" style="width: 12%;">
                        <table style="width: 100%; text-align: center; table-layout: fixed;">
                            <tr>Pipe Body</tr>
                            <tr style="height: 62px;">
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 12.5%;">main Rem. W.T</td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 12.5%;">OD Wear</td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 12.5%;">EMI</td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 12.5%;">S-Area</td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 12.5%;">Corr-IN</td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 12.5%;">Corr-Out</td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 12.5%;">IPC</td>
                                <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 12.5%;">Bent</td>
                            </tr>
                            <tr style="height: 22px;"></tr>
                        </table>
                    </td>
                </tr>
                </thead>

                <tbody data-repeater-list="inspection_data" style="font-size: 70%;">
                @foreach(isset($model) ? $model->inspection_data : $inspection_data_template as $item)
                    <tr data-repeater-item>
                        <td class="p-0 white" style="height: 22px;">
                            <table style="width: 100%; text-align: center; table-layout: fixed;">
                                <tr>
                                    <td class=" p-0 border-dark white" style="width: 30%; height: 22px;">
                                        <span>{{$item['input_1']}}</span>
                                    </td>
                                    <td class=" p-0 border-dark white" style="width: 70%">
                                        <span>{{$item['input_2']}}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="height: 22px;">
                            <table style="width: 100%; text-align: center; table-layout: fixed;">
                                <tr>
                                    <td class="p-0 border-dark white" style="width: 7.6%; height: 22px;">
                                        <span>{{$item['input_3']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <span>{{$item['input_4']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <span>{{$item['input_5']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <span>{{$item['input_6']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <span>{{$item['input_7']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <span>{{$item['input_8']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <span>{{$item['input_9']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <span>{{$item['input_10']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <span>{{$item['input_11']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <span>{{$item['input_12']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <span>{{$item['input_13']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <span>{{$item['input_14']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <span>{{$item['input_15']}}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="height: 22px;">
                            <table style="width: 100%; text-align: center; table-layout: fixed;">
                                <tr>
                                    <td class="p-0 border-dark white" style="width: 7.14%; height: 22px;">
                                        <span>{{$item['input_16']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.14%; height: 22px;">
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
                                    <td class="p-0 border-dark white" style="width: 7.14%;">
                                        <span>{{$item['input_23']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.14%;">
                                        <span>{{$item['input_24']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.14%;">
                                        <span>{{$item['input_25']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.14%;">
                                        <span>{{$item['input_26']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.14%;">
                                        <span>{{$item['input_27']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.14%;">
                                        <span>{{$item['input_28']}}</span>
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.14%;">
                                        <span>{{$item['input_29']}}</span>
                                    </td>
                                </tr>
                            </table>

                        </td>
                        <td>
                            <table style="width: 100%; text-align: center; table-layout: fixed;">
                                <td class="p-0 border-dark white" style="width: 12.5%; height: 22px;">
                                    <span>{{$item['input_30']}}</span>
                                </td>
                                <td class="p-0 border-dark white" style="width: 12.5%">
                                    <span>{{$item['input_31']}}</span>
                                </td>
                                <td class="p-0 border-dark white" style="width: 12.5%">
                                    <span>{{$item['input_32']}}</span>
                                </td>
                                <td class="p-0 border-dark white" style="width: 12.5%">
                                    <span>{{$item['input_33']}}</span>
                                </td>
                                <td class="p-0 border-dark white" style="width: 12.5%">
                                    <span>{{$item['input_34']}}</span>
                                </td>
                                <td class="p-0 border-dark white" style="width: 12.5%">
                                    <span>{{$item['input_35']}}</span>
                                </td>
                                <td class="p-0 border-dark white" style="width: 12.5%">
                                    <span>{{$item['input_36']}}</span>
                                </td>
                                <td class="p-0 border-dark white" style="width: 12.5%">
                                    <span>{{$item['input_37']}}</span>
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
        <div class="col-12 bg-dark p-0 border-dark white">
            <table style="width: 98%; border-collapse: collapse; border: none; margin: 0 auto; font-size: 10px; line-height: 12px; display: contents;">
                <tr style="border-left: 1px;">
                    <td style="margin: 1px">LB Box Tong Space</td>
                    <td>QC - Box C'bore Diameter</td>
                    <td>DF - Bevel Diameter</td>
                    <td>PND - Pin Nose Diameter</td>
                    <td>PC - Internal Plastic Coating</td>
                    <td>SD = Shoulder Damage</td>
                    <td>BW = Box Widening</td>
                    <td>ODT = Out of Dimensional Tolerance</td>
                    <td>IPC:-</td>
                    <td>Slip Area = S.Area</td>
                    <td>JTS Class:-</td>
                </tr>
                <tr>
                    <td>D - Outside Diameter</td>
                    <td>CBL - Box C'bore Depth</td>
                    <td>PL - Pin Lead</td>
                    <td>PCD - Pin Cyl. Diameter</td>
                    <td>HB = Hard Banding</td>
                    <td>TD = Thread Damage</td>
                    <td>CO = Corrosion</td>
                    <td>W = Washout</td>
                    <td>1 : 100%</td>
                    <td>P = Pass</td>
                    <td>New - One White Band</td>
                </tr>
                <tr>
                    <td>DF - Bevel Diameter</td>
                    <td>LPB - Pin Tong Space</td>
                    <td>PNL - Pin Neck Length</td>
                    <td>SHW - Shoulder Width</td>
                    <td>NH = Need Hardbanding</td>
                    <td>TE = Thread Elongation</td>
                    <td>GT = Galled Thread</td>
                    <td>C = Crack</td>
                    <td>2 : 70%</td>
                    <td>F = Fail</td>
                    <td>Premium Class - Two White Band</td>
                </tr>
                <tr>
                    <td>SW - Seal Width</td>
                    <td>d - I.D. of Pin</td>
                    <td>Lpc - Length Pin Connection</td>
                    <td>CBW - Box C'bore Wall</td>
                    <td>FL = Flashed</td>
                    <td>ST = Stretched Thread</td>
                    <td>Pt = Pitting</td>
                    <td>M = Flatspot / Mash</td>
                    <td>3 : 50%</td>
                    <td></td>
                    <td>Class II - Yellow</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Lbc - Length Box Connection</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>4 : > 50%</td>
                    <td></td>
                    <td>Class III - Orange <br/> Scrape - Red</td>
                </tr>
            </table>
        </div>
    </div>
    <!----------------------------------->
    @include('layouts.styles.reportfooter-v2')
    @include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $model->report->id])
@endpush
