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
        .inspection-img {
            display: flex;
            margin: 0px auto;
            width: 30em;
        }
    </style>

    @php
        $specificationRows = is_array($model->specification) ? $model->specification : [];
        $inspectionMethodRows = is_array($model->inspection_method) ? $model->inspection_method : [];
        $rawEquipmentRows = $model->equipment_no;
        if (is_string($rawEquipmentRows)) {
            $decodedEquipmentRows = json_decode($rawEquipmentRows, true);
            $rawEquipmentRows = json_last_error() === JSON_ERROR_NONE ? $decodedEquipmentRows : [];
        }
        if (is_array($rawEquipmentRows) && array_key_exists('equipment_used', $rawEquipmentRows)) {
            $rawEquipmentRows = [$rawEquipmentRows];
        }

        $equipmentRows = collect(is_array($rawEquipmentRows) ? $rawEquipmentRows : [])
            ->map(function ($item) {
                if (is_string($item)) {
                    $decodedItem = json_decode($item, true);
                    $item = json_last_error() === JSON_ERROR_NONE ? $decodedItem : [];
                } elseif (is_object($item)) {
                    $item = (array) $item;
                }

                if (!is_array($item)) {
                    return null;
                }

                $equipmentUsed = trim((string) (
                    $item['equipment_used']
                    ?? $item['equipment']
                    ?? $item['used_equipment']
                    ?? ''
                ));

                $otherEquipment = trim((string) (
                    $item['other_equipment']
                    ?? $item['equipment_other']
                    ?? $item['other']
                    ?? ''
                ));

                $equipmentNumber = trim((string) (
                    $item['equipment_no_value']
                    ?? $item['equipment_no']
                    ?? $item['equipment_number']
                    ?? $item['number']
                    ?? ''
                ));

                return [
                    'equipment_used' => $equipmentUsed,
                    'other_equipment' => $otherEquipment,
                    'equipment_no_value' => $equipmentNumber,
                ];
            })
            ->filter(function ($item) {
                return is_array($item)
                    && ($item['equipment_used'] !== '' || $item['other_equipment'] !== '' || $item['equipment_no_value'] !== '');
            })
            ->values();
    @endphp

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
                @foreach($specificationRows as $item)
                    <div class="specinsp" style="margin-left: 8px;">
                        <span class="noncheckedfrom checked" style="position: relative;top: 3px;"></span>
                        <label>{{ $specificationOptions[$item] ?? $item }}</label>
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
          <span class="noncheckedfrom {{ in_array($item, $inspectionMethodRows, true) ? 'checked' : '' }}"
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
                @foreach($equipmentRows as $item)
                    <div class="specinsp" style=" min-width: 145px;">
                        {{--<span class="noncheckedfrom checked" style="position: relative;top: 3px;"></span>--}}
                        @if(array_key_exists('equipment_used', $item) && trim((string) ($item['equipment_used'] ?? '')) !== '')
                            <p class="border-dark pl-1 mb-0 text-16 black" style="min-width: 105px;">
                                {{ ($item['equipment_used'] ?? '') === 'Other' ? ($item['other_equipment'] ?? '') : ($item['equipment_used'] ?? '') }}
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
                @foreach($equipmentRows as $item)
                    {{--<div style="margin-right: 4px; margin-left: 4px;">--}}
                    <p class="border-dark pl-1 mb-0 text-16 black"
                       style="min-width: 145px;">{{ $item['equipment_no_value'] ?? '' }}</p>
                    {{--</div>--}}
                @endforeach
            </div>
        </div>
    </div>
    <!----------------------------------->
    <div class="row" style="margin-top: 3px;">
        <div class="bg-dark col-2 p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Equipment Description:</h6>
        </div>
        <div class="col-10 p-0 pl-1 border-dark text-16 black">
            {{$model->material_description}}
        </div>


        <div class="bg-dark col-2 p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">ID Number:</h6>
        </div>
        <div class="col-6 p-0 pl-1 border-dark text-16 black">
            {{$model->material_no}}
        </div>

        <div class="bg-dark col-2 p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Condition:</h6>
        </div>
        <div class="col-2 p-0 pl-1 border-dark text-16 black">
            <div class="row skin skin-square">
                @foreach(['New', 'Used'] as $item)
                    <div class="specinsp" style="margin-left: 8px;">
                    <span class="noncheckedradiofrom {{$model->condition === $item ? 'checked' : ''}}"
                          style="position: relative;top: 3px;"></span>
                        <label>{{$item}}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-dark col-2 p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Inspection Applied:</h6>
        </div>
        <div class="col-10 p-0 pl-1 border-dark text-16 black">
            {{$model->inspection_applied}}
        </div>
    </div>
    <!----------------------------------->
    <div class="row" style="margin-top: 3px;">
        <div class="col-12 bg-dark p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Material Dimensions</h6>
        </div>
        <div class="col-12">
            <div class="row" style="border: solid black 1px;">
                {{--Data on the Left Side--}}
                <div class="col-5" style="padding-left: 22px;">
                    <div class="row mt-1 bg-dark p-0 border-dark text-bold-600 white text-center">
                        <div class="col-12 ">
                            <p class="white text-bold-600">Link Dimension and dimensions</p>
                        </div>
                    </div>
                    <div class="row p-1 border-dark text-bold-600 white">
                        <div class="col-4">
                            <h6>Dimensions In:</h6>
                        </div>

                        @foreach(['Inch', 'Cm'] as $item)
                            <div  class="col-4 m-0 p-0">
                    <span class="noncheckedradiofrom {{$model->dimensions_data['unit'] === $item ? 'checked' : ''}}"
                          style="position: relative;top: 3px;"></span>
                                <label style="color: black; font-size: 1rem;">{{$item}}</label>
                            </div>
                        @endforeach

                    </div>

                    <div class="row mt-1">
                        <table style="width: 100%;" class="text-center border">
                            <tr>
                                <td class=" bg-dark p-0 border-dark white  text-bold-600 " colspan="10">
                                    Upper Eye
                                </td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: center; width: 16%;">G</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_g1']}}</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_g2']}}</td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: center; width: 16%;">F</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_f1']}}</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_f2']}}</td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: center; width: 16%;">E</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_e1']}}</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_e2']}}</td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: center; width: 16%;">H</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_h1']}}</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_h2']}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="row " style="margin-top: 3px;">
                        <table style="width: 100%;" class="text-center border">
                            <tr>
                                <td class=" bg-dark p-0 border-dark white  text-bold-600 " colspan="10">
                                    Lower Eye
                                </td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: center; width: 16%;">A</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_a1']}}</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_a2']}}</td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: center; width: 16%;">B</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_b1']}}</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_b2']}}</td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: center; width: 16%;">C</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_c1']}}</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_c2']}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="row " style="margin-top: 3px;">
                        <table style="width: 100%;" class="text-center border">
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: center; width: 16%;">D (OD)</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_d1']}}</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_d2']}}</td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: center; width: 16%;">L (Length)</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_l1']}}</td>
                                <td class="border-dark white"  style="width: 42%;">{{$model->dimensions_data['input_l2']}}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{--Image on the Right Side--}}
                <div class="col-7">
                    <img src="{{asset('app-assets/images/inspections/link-inspection/1.png')}}" class="inspection-img"/>
                </div>
            </div>
        </div>
    </div>
    <!----------------------------------->
    <!----------------------------------->
    <div class="row" style="margin-top: 3px;">
        <div class="col-12 bg-dark p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Comments</h6>
        </div>
        <div class="col-12 border-dark m-0 p-0">
            <div class="row" style="padding: 5px 20px; min-height: 7em;">
                <div class="col-12 p-0 border-dark black" style="padding: 2px;">
                    <p style="margin-left: 5px;">
                        {!! nl2br(e($model->comment)) !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!----------------------------------->
    <div class="row">
        <div class="col-12 bg-dark p-0 border-dark white">
            <table style="width: 98%; border-collapse: collapse; border: none; margin: 0 auto; font-size: 13px; line-height: 13px;">
                <tr >
                    <td>SD = Seal Damage</td>
                    <td>TE = Thread Elongation </td>
                    <td>CO = Corrosion </td>
                    <td>W = Washout </td>
                    <td>M = Flatspot / Mash</td>
                    <td>B.B = Bore Back</td>
                </tr>
                <tr >
                    <td>TD = Thread Damage</td>
                    <td>BW = Box Widening</td>
                    <td>P = Pitting</td>
                    <td>C = Crack</td>
                    <td>RF = Refaced Connection</td>
                    <td>S.R.G= Stress Relief Groove</td>
                </tr>
            </table>
        </div>
    </div>
    <!----------------------------------->
    @include('layouts.styles.reportfooter-v2')
    @include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $model->report->id])
@endpush
