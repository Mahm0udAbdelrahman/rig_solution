@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
    <style>
        .summary-row {
            font-size: 17px;
            line-height: 37px;
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
    <div class="border-dark pl-1 mb-0 text-16 black col-7">
        <div class="row skin skin-square">
            @foreach($equipments as $item)
                <div class="specinsp" style="margin-left: 8px;">
          <span class="noncheckedfrom {{in_array($item, $model->equipment_used)? 'checked' : ''}}"
                style="position: relative;top: 3px;"></span>
                    <label>{{$item}}</label>
                </div>
            @endforeach
            <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom {{$model->other_equipment? 'checked' : ''}}"
              style="position: relative;top: 3px;"></span>
                <label>other</label>
            </div>
        </div>
    </div>
    <p class="border-dark mb-0 text-16 black col-3 p-0" style="font-size: 100%;">{{$model->other_equipment}}</p>
</div>
<div class="row">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Equipment No:</h6>
    <div class="border-dark pl-1 mb-0 text-16 black col-10">
        <div class="row">
            @foreach($model->equipment_no as $item)
                {{--<div style="margin-right: 4px; margin-left: 4px;">--}}
                <p class="border-dark pl-1 mb-0 text-16 black"
                   style="min-width: 75px;">{{$item['equipment_no_value']}}</p>
                {{--</div>--}}
            @endforeach
        </div>
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
        <h6 class="white text-bold-600 pl-1 mb-0">Nominal Size (OD)</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->nominal_size}}
    </div>

    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Nom Wall</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 rest {{$model->nom_wall < 650 ? 'red' : 'black'}}">{{$model->nom_wall}}</div>
</div>
<div class="row">
    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Pipe Grade:</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->pipe_grade}}</div>

    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Tooljoint OD:</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->tool_joint_od}}</div>

    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Tooljoint ID</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->tool_joint_id}}</div>
</div>
<div class="row">
    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Weight:</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->weight}}</div>

    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Threads:</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->threads}}</div>

    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Class</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$model->class}}</div>
</div>
<div class="row">
    <div class="bg-dark col-2 p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Hard faced:</h6>
    </div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">
        <div class="row skin skin-square">
            @foreach(['Yes', 'No'] as $item)
                <div class="specinsp" style="margin-left: 30px;">
                    <span class="noncheckedradiofrom {{$model->hard_faced === $item ? 'checked' : ''}}"
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
<div class="row" style="margin-top: 3px; font-size: 2em;">
    <div class="col-12 bg-dark p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Final Inspection Summary</h6>
    </div>
    <div class="col-12 p-0 pl-1 border-dark black">
        {{--          A- Joints Details:               --}}
        <div class="row" style="font-weight: 900; font-size: 17px; margin-top: 1.1em;">
            <div class="col-5">
                <u><span>A- Joints Details:</span></u>
            </div>
            <div class="col-7">
                <u></u><span>Comment:</span></u>
            </div>
        </div>

        <div class="row summary-row" >
            <div class="col-5">
                <p style="display: flex; justify-content: space-evenly;">
                    <span style="width: 80%; text-align: left;">Joints Premium Class Ready For Use</span>
                    <span style="margin: 0 8px;"><u>{{$model->jp_ready_use}}</u></span>
                    <span><strong> JTS </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->jp_ready_use_comment}}</p></u>
            </div>
        </div>

        <div class="row summary-row" >
            <div class="col-5">
                <p style="display: flex; justify-content: space-evenly;">
                    <span style="width: 80%; text-align: left;">Joints Premium, Need Re-Cut:</span>
                    <span style="margin: 0 8px;"><u>{{$model->jp_need_recut}}</u></span>
                    <span><strong> JTS </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->jp_need_recut_comment}}</p></u>
            </div>
        </div>

        <div class="row summary-row" >
            <div class="col-5">
                <p style="display: flex; justify-content: space-evenly; margin-left: 22px;">
                    <span style="width: 80%; text-align: left;">Joints Need Re-Cut Pin & Box:</span>
                    <span style="margin: 0 8px;"><u>{{$model->jp_recut_pin_box}}</u></span>
                    <span><strong> JTS </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->jp_recut_pin_box_comment}}</p></u>
            </div>
        </div>
        <div class="row summary-row" >
            <div class="col-5">
                <p style="display: flex; justify-content: space-evenly; margin-left: 22px;">
                    <span style="width: 80%; text-align: left;">Joints Need Re-Cut Pin Only:</span>
                    <span style="margin: 0 8px;"><u>{{$model->jp_recut_pin}}</u></span>
                    <span><strong> PIN </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->jp_recut_pin_comment}}</p></u>
            </div>
        </div>
        <div class="row summary-row" >
            <div class="col-5">
                <p style="display: flex; justify-content: space-evenly; margin-left: 22px;">
                    <span style="width: 80%; text-align: left;">Joints Need Re-Cut Box Only:</span>
                    <span style="margin: 0 8px;"><u>{{$model->jp_recut_box}}</u></span>
                    <span><strong> Box </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->jp_recut_box_comment}}</p></u>
            </div>
        </div>

        <div class="row summary-row" >
            <div class="col-5">
                <p style="display: flex; justify-content: space-evenly;">
                    <span style="width: 80%; text-align: left;">Joints Class II:</span>
                    <span style="margin: 0 8px;"><u>{{$model->joints_class_2}}</u></span>
                    <span><strong> JTS </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->joints_class_2_comment}}</p></u>
            </div>
        </div>

        <div class="row summary-row" >
            <div class="col-5">
                <p style="display: flex; justify-content: space-evenly;">
                    <span style="width: 80%; text-align: left;">Joints Class III:</span>
                    <span style="margin: 0 8px;"><u>{{$model->joints_class_3}}</u></span>
                    <span><strong> JTS </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->joints_class_3_comment}}</p></u>
            </div>
        </div>

        <div class="row summary-row" >
            <div class="col-5">
                <p style="display: flex; justify-content: space-evenly;">
                    <span style="width: 80%; text-align: left;">Joints Junk:</span>
                    <span style="margin: 0 8px;"><u>{{$model->joints_junk}}</u></span>
                    <span><strong> JTS </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->joints_junk_comment}}</p></u>
            </div>
        </div>

        <div class="row summary-row" >
            <div class="col-5">
                <p style="display: flex; justify-content: space-evenly;">
                    <span style="width: 80%; text-align: left;">Total Joints Inspected:</span>
                    <span style="margin: 0 8px;"><u>{{$model->total_joints}}</u></span>
                    <span><strong> JTS </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->total_joints_comment}}</p></u>
            </div>
        </div>

        {{--          B- Repaired Details:              --}}
        <div class="row" style="font-weight: 900; font-size: 17px;">
            <div class="col-5">
                <u><span>B- Repaired Details:</span></u>
            </div>
        </div>

        <div class="row summary-row" >
            <div class="col-5" style="white-space: nowrap;">
                <p style="display: flex; justify-content: space-evenly;  width: 104%;">
                    <span style="width: 80%; text-align: left;">Connections Manually Thread Repaired: </span>
                    <span style="margin: 0 8px;"><u>{{$model->connections_manually}}</u></span>
                    <span><strong> Conns </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->connections_manually_comment}}</p></u>
            </div>
        </div>

        <div class="row summary-row" >
            <div class="col-5">
                <p style="display: flex; justify-content: space-evenly;">
                    <span style="width: 80%; text-align: left;">Total Boxes Refaced: </span>
                    <span style="margin: 0 8px;"><u>{{$model->total_boxs}}</u></span>
                    <span><strong> Box </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->total_boxs_comment}}</p></u>
            </div>
        </div>

        <div class="row summary-row" >
            <div class="col-5">
                <p style="display: flex; justify-content: space-evenly;">
                    <span style="width: 80%; text-align: left;">Total Pins Refaced: </span>
                    <span style="margin: 0 8px;"><u>{{$model->total_pins}}</u></span>
                    <span><strong> Pin </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->total_pins_comment}}</p></u>
            </div>
        </div>

        <div class="row summary-row" style="margin-bottom: 1em;">
            <div class="col-5">
                <p style="display: flex; justify-content: space-evenly;">
                    <span style="width: 80%; text-align: left;">Joints Straightened:</span>
                    <span style="margin: 0 8px;"><u>{{$model->total_straightened}}</u></span>
                    <span><strong> JTS </strong></span>
                </p>
            </div>
            <div class="col-7">
                <u><p>{{$model->total_straightened_comment}}</p></u>
            </div>
        </div>
    </div>
</div>
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
    <div class="col-12 bg-dark p-0 border-dark">
        <h6 class="white text-bold-600 pl-1 mb-0">Comments</h6>
    </div>
    <div class="col-12 p-0 pl-1 border-dark black" style="min-height: 9.5em;">
        {!! nl2br(e($model->comment)) !!}
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
