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
            @foreach($model->specification as $item)
                <div class="specinsp" style="margin-left: 8px;">
                    <span class="noncheckedfrom checked" style="position: relative;top: 3px;"></span>
                    <label>{{$specificationOptions[$item]}}</label>
                </div>
            @endforeach
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
            @foreach($model->equipment_no ?? [] as $item)
                <div class="specinsp" style=" min-width: 145px;">
                    {{--<span class="noncheckedfrom checked" style="position: relative;top: 3px;"></span>--}}
                    @if(is_array($item) && array_key_exists('equipment_used', $item))
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
            @foreach($model->equipment_no ?? [] as $item)
                {{--<div style="margin-right: 4px; margin-left: 4px;">--}}
                <p class="border-dark pl-1 mb-0 text-16 black"
                   style="min-width: 145px;">{{is_array($item) ? ($item['equipment_no_value'] ?? '') : ''}}</p>
                {{--</div>--}}
            @endforeach
        </div>
    </div>
</div>
<div class="row">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Joint Description:</h6>
    <div class="border-dark pl-1 mb-0 text-16 black col-10">
        {{$model->connection}}
    </div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
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
    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Coated:</h6>
    </div>
    <div class="col-6 p-0 pl-1 border-dark text-16 black">
        <div class="row skin skin-square">
            @foreach(['Good', 'Fair', 'Poor'] as $item)
                <div class="specinsp" style="margin-left: 40px;">
                    <span class="noncheckedradiofrom {{$model->coated === $item ? 'checked' : ''}}"
                          style="position: relative;top: 3px;"></span>
                    <label>{{$item}}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!----------------------------------->
<div class="row">
    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Pipe OD:</h6>
    </div>
    <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->pipe_od}}</div>

    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Pipe Grade:</h6>
    </div>
    <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->pipe_grade}}</div>

    <div class="bg-dark col-1 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">lbs/ft:</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->lbs_ft}}</div>

    <div class="bg-dark col-1 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Weight</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->weight}}</div>
</div>
<div class="row">
    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Drift OD:</h6>
    </div>
    <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->drift_od}}</div>

    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Connection:</h6>
    </div>
    <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->connection}}</div>

    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Tool Joint OD:</h6>
    </div>
    <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->tool_joint_od}}</div>

    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Tool Joint ID</h6>
    </div>
    <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$model->tool_joint_id}}</div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
    <div class="col-12" style="padding: 0;">
        <table style="width: 100%;" class="text-center">
            <thead style="width: 100%; font-size: 85%;">
            <tr>
                <td  class=" bg-dark p-0 border-dark white" style="width: 20%;">
                    <table style="width: 100%; text-align: center;">
                        <tr>
                            <td colspan="10">Complete Drill Pipe</td>
                        </tr>
                        <tr>
                            <td class="bg-dark p-0 border-dark white" style="width: 15%">No.</td>
                            <td class="bg-dark p-0 border-dark white" style="width: 35%">S.No</td>
                            <td class="bg-dark p-0 border-dark white" style="width: 40%">
                                <table style="width: 100%; text-align: center;">
                                    <tr>
                                        <td colspan="10">Class</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-dark p-0 border-dark white" style="width: 20%;">N</td>
                                        <td class="bg-dark p-0 border-dark white" style="width: 20%;">I</td>
                                        <td class="bg-dark p-0 border-dark white" style="width: 20%;">II</td>
                                        <td class="bg-dark p-0 border-dark white" style="width: 20%;">III</td>
                                        <td class="bg-dark p-0 border-dark white" style="width: 20%;">SC</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td  class=" bg-dark p-0 border-dark white" style="width: 20%;">
                    <table style="width: 100%; text-align: center;">
                        <tr>
                            <td colspan="10">Pipe Body and Upset Area</td>
                        </tr>
                        <tr>
                            <td class="bg-dark p-0 border-dark white" style="width: 20%">S.A cond.</td>
                            <td class="bg-dark p-0 border-dark white" style="width: 20%">CRK</td>
                            <td class="bg-dark p-0 border-dark white" style="width: 20%">
                                <table style="width: 100%; text-align: center;">
                                    <tr>
                                        <td colspan="10">Pit</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-dark p-0 border-dark white" style="width: 50%;">in</td>
                                        <td class="bg-dark p-0 border-dark white" style="width: 50%;">out</td>
                                    </tr>
                                </table>
                            </td>
                            <td class="bg-dark p-0 border-dark white" style="width: 20%">W.T</td>
                            <td class="bg-dark p-0 border-dark white" style="width: 20%">EMI</td>
                        </tr>
                    </table>
                </td>
                <td  class=" bg-dark p-0 border-dark white" style="width: 20%;">
                    <table style="width: 100%; text-align: center;">
                        <tr>
                            <td colspan="10">Tong Space</td>
                        </tr>
                        <tr>
                            <td class="bg-dark p-0 border-dark white" style="width: 35%">
                                <table style="width: 100%; text-align: center;">
                                    <tr>
                                        <td colspan="10">Length</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-dark p-0 border-dark white" style="width: 50%;">Box</td>
                                        <td class="bg-dark p-0 border-dark white" style="width: 50%;">Pin</td>
                                    </tr>
                                </table>
                            </td>
                            <td class="bg-dark p-0 border-dark white" style="width: 35%">
                                <table style="width: 100%; text-align: center;">
                                    <tr>
                                        <td colspan="10">OD</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-dark p-0 border-dark white" style="width: 50%;">Box</td>
                                        <td class="bg-dark p-0 border-dark white" style="width: 50%;">Pin</td>
                                    </tr>
                                </table>
                            </td>
                            <td class="bg-dark p-0 border-dark white" style="width: 30%">
                                <table style="width: 100%; text-align: center;">
                                    <tr>
                                        <td colspan="10">ID</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-dark p-0 border-dark white">Pin</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td  class=" bg-dark p-0 border-dark white" style="width: 20%;">
                    <table style="width: 100%; text-align: center;">
                        <tr>
                            <td colspan="10">Connection</td>
                        </tr>
                        <tr>
                            <td class="bg-dark p-0 border-dark white" style="width: 50%">
                                <table style="width: 100%; text-align: center;">
                                    <tr>
                                        <td colspan="10">Field Repair</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-dark p-0 border-dark white" style="width: 50%;">Box</td>
                                        <td class="bg-dark p-0 border-dark white" style="width: 50%;">Pin</td>
                                    </tr>
                                </table>
                            </td>
                            <td class="bg-dark p-0 border-dark white" style="width: 50%">
                                <table style="width: 100%; text-align: center;">
                                    <tr>
                                        <td colspan="10">condition</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-dark p-0 border-dark white" style="width: 50%;">Box</td>
                                        <td class="bg-dark p-0 border-dark white" style="width: 50%;">Pin</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td  class=" bg-dark p-0 border-dark white" style="width: 20%;">
                    Comment
                </td>
            </tr>
            </thead>

            <tbody data-repeater-list="inspection_data" style="font-size: 85%;">
            @foreach(isset($model) && is_array($model->inspection_data) && count($model->inspection_data )  ? $model->inspection_data : $inspection_data_template as $index => $item)
                <tr data-repeater-item>
                    <td class="p-0 white" style="width: 20%;">
                        <table style="width: 100%; text-align: center;">
                            <tr style="height: 23px;">
                                <td class=" p-0 border-dark white" style="width: 15%;">
                                    <span>{{$item['input_1']}}</span>
                                </td>
                                <td class=" p-0 border-dark white" style="width: 35%;">
                                    <span>{{$item['input_2']}}</span>
                                </td>
                                <td class=" p-0 border-dark white" style="width: 40%;">
                                    <table style="width: 100%; text-align: center;">
                                        <tr>
                                            <td class="p-0 " style="width: 20%;">
                                                {{--<input name='{{"radio".$index}}' type="radio" value="N" {{isset($item['input_3']) && $item['input_3'] === 'N' ? 'checked': ''}} />--}}
                                                <span class="noncheckedradiofrom inspection_data_radio {{$item['input_3']=== 'N' ? 'checked' : ''}}"></span>
                                            </td>
                                            <td class="p-0 " style="width: 20%;">
                                                {{--<input name='{{"radio".$index}}' type="radio" value="I" {{isset($item['input_3']) && $item['input_3'] === 'N' ? 'checked': ''}} >--}}
                                                <span class="noncheckedradiofrom inspection_data_radio {{$item['input_3']=== 'I' ? 'checked' : ''}}"></span>
                                            </td>
                                            <td class="p-0 " style="width: 20%;">
                                                {{--<input name='{{"radio".$index}}' type="radio" value="II" {{isset($item['input_3']) && $item['input_3'] === 'N' ? 'checked': ''}} >--}}
                                                <span class="noncheckedradiofrom inspection_data_radio {{$item['input_3']=== 'II' ? 'checked' : ''}}"></span>
                                            </td>
                                            <td class="p-0 " style="width: 20%;">
                                                {{--<input name='{{"radio".$index}}' type="radio" value="III" {{isset($item['input_3']) && $item['input_3'] === 'N' ? 'checked': ''}} >--}}
                                                <span class="noncheckedradiofrom inspection_data_radio {{$item['input_3']=== 'III' ? 'checked' : ''}}"></span>
                                            </td>
                                            <td class="p-0 " style="width: 20%;">
                                                {{--<input name='{{"radio".$index}}' type="radio" value="SC" {{isset($item['input_3']) && $item['input_3'] === 'N' ? 'checked': ''}} >--}}
                                                <span class="noncheckedradiofrom inspection_data_radio {{$item['input_3']=== 'SC' ? 'checked' : ''}}"></span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="p-0 white" style="width: 20%;">
                        <table style="width: 100%; text-align: center;">
                            <tr style="height: 22px;">
                                <td class="p-0 border-dark white" style="width: 20%">
                                    <span>{{$item['input_4']}}</span>
                                </td>
                                <td class="p-0 border-dark white" style="width: 20%">
                                    <span>{{$item['input_5']}}</span>
                                </td>
                                <td class="p-0 border-dark white" style="width: 20%">
                                    <table style="width: 100%; text-align: center;">
                                        <tr style="height: 22px;">
                                            <td class="p-0 white" style="width: 50%;">
                                                <span>{{$item['input_6']}}</span>
                                            </td>
                                            <td class="p-0 border_left_custom white" style="width: 50%;">
                                                <span>{{$item['input_7']}}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="p-0 border-dark white" style="width: 20%">
                                    <span>{{$item['input_8']}}</span>
                                </td>
                                <td class="p-0 border-dark white" style="width: 20%">
                                    <span>{{$item['input_9']}}</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="p-0 white" style="width: 20%;">
                        <table style="width: 100%; text-align: center;">
                            <tr style="height: 22px;">
                                <td class="p-0 border-dark white" style="width: 35%">
                                    <table style="width: 100%; text-align: center;">
                                        <tr style="height: 22px;">
                                            <td class="p-0 white" style="width: 50%;">
                                                <span>{{$item['input_10']}}</span>
                                            </td>
                                            <td class="p-0 border_left_custom white" style="width: 50%;">
                                                <span>{{$item['input_11']}}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="p-0 border-dark white" style="width: 35%">
                                    <table style="width: 100%; text-align: center;">
                                        <tr style="height: 22px;">
                                            <td class="p-0 white" style="width: 50%;">
                                                <span>{{$item['input_12']}}</span>
                                            </td>
                                            <td class="p-0 border_left_custom white" style="width: 50%;">
                                                <span>{{$item['input_13']}}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="p-0 border-dark white" style="width: 30%">
                                    <table style="width: 100%; text-align: center;">
                                        <tr style="height: 20px;">
                                            <td class="p-0 white">
                                                <span>{{$item['input_14']}}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="p-0 white" style="width: 20%;">
                        <table style="width: 100%; text-align: center;">
                            <tr style="height: 22px;">
                                <td class="p-0 border-dark white" style="width: 50%">
                                    <table style="width: 100%; text-align: center;">
                                        <tr style="height: 22px;">
                                            <td class="p-0  white" style="width: 50%;">
                                                <span>{{$item['input_15']}}</span>
                                            </td>
                                            <td class="p-0 border_left_custom white" style="width: 50%;">
                                                <span>{{$item['input_16']}}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="p-0 border-dark white" style="width: 50%">
                                    <table style="width: 100%; text-align: center;">
                                        <tr style="height: 21px;">
                                            <td class="p-0 white" style="width: 50%;">
                                                <span>{{$item['input_17']}}</span>
                                            </td>
                                            <td class="p-0 border_left_custom white" style="width: 50%;">
                                                <span>{{$item['input_18']}}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="p-0 white" style="width: 20%;">
                        <table style="width: 100%; text-align: center;">
                            <tr style="height: 23px;">
                                <td class=" p-0 border-dark white">
                                    <span>{{$item['input_19']}}</span>
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

    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">New Pipes</h6>
    </div>
    <div class="col-10 p-0 pl-1 border-dark text-16 black">
        <div class="row">
        <div class="col-2">{{$model->new_pipes}}</div>
        <div class="col-1"><strong>JTS</strong></div>
        <div class="col-9">{{$model->new_pipes_comment}}</div>
        </div>
    </div>
    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Premium Class</h6>
    </div>
    <div class="col-10 p-0 pl-1 border-dark text-16 black">
        <div class="row">
        <div class="col-2">{{$model->premium_class}}</div>
        <div class="col-1"><strong>JTS</strong></div>
        <div class="col-9">{{$model->premium_class_comment}}</div>
        </div>
    </div>
    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Joints Class II</h6>
    </div>
    <div class="col-10 p-0 pl-1 border-dark text-16 black">
        <div class="row">
        <div class="col-2">{{$model->joints_class_2}}</div>
        <div class="col-1"><strong>JTS</strong></div>
        <div class="col-9">{{$model->joints_class_2_comment}}</div>
        </div>
    </div>
    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Joints Class III</h6>
    </div>
    <div class="col-10 p-0 pl-1 border-dark text-16 black">
        <div class="row">
        <div class="col-2">{{$model->joints_class_3}}</div>
        <div class="col-1"><strong>JTS</strong></div>
        <div class="col-9">{{$model->joints_class_3_comment}}</div>
        </div>
    </div>
    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Joints Junk</h6>
    </div>
    <div class="col-10 p-0 pl-1 border-dark text-16 black">
        <div class="row">
        <div class="col-2">{{$model->joints_junk}}</div>
        <div class="col-1"><strong>JTS</strong></div>
        <div class="col-9">{{$model->joints_junk_comment}}</div>
        </div>
    </div>
</div>
<!----------------------------------->
<div class="row">
    <div class="col-12 bg-dark p-0 border-dark white">
        <table style="width: 98%; border-collapse: collapse; border: none; margin: 0 auto; font-size: 13px; line-height: 13px;">
            <tr >
                <td>SD: Shoulder Damage</td>
                <td>TD: Thread Damage</td>
                <td>B: Bottle neck</td>
                <td>TE: Thread Elongation</td>
                <td>BW: Box Widening</td>
                <td>G: Gouge</td>
            </tr>
            <tr >
                <td>CO: Corrosion</td>
                <td>P: Pitting</td>
                <td>GS: Gouge in slip area</td>
                <td>W : Washout</td>
                <td>C: Crack</td>
                <td>OL: Overlap</td>
            </tr>
            <tr >
                <td>M : Flat spot / Mash</td>
                <td>RF: Refaced Connection</td>
                <td>TW: Tool joint wear</td>
                <td>B.B : Bore Back</td>
                <td>S.R.G= Stress Relief Groove</td>
                <td>Y: Yes , N: No</td>
            </tr>
        </table>
    </div>
</div>
<!----------------------------------->
@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $model->report->id])
@endpush
