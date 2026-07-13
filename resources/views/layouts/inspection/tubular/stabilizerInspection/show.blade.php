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
            width: 50em;
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
                        <label>{{$specificationOptions[$item] ?? $item}}</label>
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
                @forelse($model->equipment_no as $item)
                    @php
                        $equipmentLabel = $item['equipment_used'] === 'Other'
                            ? ($item['other_equipment'] ?: 'Other')
                            : ($item['equipment_used'] ?: 'N/A');
                    @endphp
                    <div class="specinsp" style=" min-width: 145px;">
                        <p class="border-dark pl-1 mb-0 text-16 black" style="min-width: 105px;">
                            {{$equipmentLabel}}
                        </p>
                    </div>
                @empty
                    <div class="specinsp" style=" min-width: 145px;">
                        <p class="border-dark pl-1 mb-0 text-16 black" style="min-width: 105px;">N/A</p>
                    </div>
                @endforelse
            </div>
        </div>
        {{--<p class="border-dark mb-0 text-16 black col-3 p-0" style="font-size: 100%;">{{$model->other_equipment}}</p>--}}
    </div>
    <div class="row">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Equipment No:</h6>
        <div class="border-dark pl-1 mb-0 text-16 black col-10">
            <div class="row">
                @forelse($model->equipment_no as $item)
                    <p class="border-dark pl-1 mb-0 text-16 black"
                       style="min-width: 145px;">{{$item['equipment_no_value'] ?: 'N/A'}}</p>
                @empty
                    <p class="border-dark pl-1 mb-0 text-16 black"
                       style="min-width: 145px;">N/A</p>
                @endforelse
            </div>
        </div>
    </div>
    <!----------------------------------->
    <div class="row" style="margin-top: 3px;">
        <div class="bg-dark col-2 p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Material Description:</h6>
        </div>
        <div class="col-10 p-0 pl-1 border-dark text-16 black">
            {{$model->material_description}}
        </div>


        <div class="bg-dark col-2 p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Tool Number:</h6>
        </div>
        <div class="col-6 p-0 pl-1 border-dark text-16 black">
            {{$model->tool_number}}
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
                <div class="col-4" style="padding-left: 22px;">
                    <div class="row mt-1">
                        <div class="col-4 m-0 p-0">
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
                                    Stabilizer and Blade Dimension
                                </td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " colspan="10">Blade</td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="width: 36%;">Front</td>
                                <td class="border-dark white  text-bold-600 " style="width: 32%;">Middle</td>
                                <td class="border-dark white  text-bold-600 " style="width: 32%;">Back</td>
                            </tr>
                            <tr>
                                <td class="border-dark white" >{{$model->dimensions_data['front']}}</td>
                                <td class="border-dark white" >{{$model->dimensions_data['middle']}}</td>
                                <td class="border-dark white" >{{$model->dimensions_data['back']}}</td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: left;"> <span style=" margin-left: 5px;">A Fishing Neck</span></td>
                                <td class="border-dark white" colspan="10">{{$model->dimensions_data['input_a']}}</td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: left;"> <span style=" margin-left: 5px;">B Blade Length</span></td>
                                <td class="border-dark white" colspan="10">{{$model->dimensions_data['input_b']}}</td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: left;"> <span style=" margin-left: 5px;">C Down Length</span></td>
                                <td class="border-dark white" colspan="10">{{$model->dimensions_data['input_c']}}</td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: left;"> <span style=" margin-left: 5px;">D Overall Length</span></td>
                                <td class="border-dark white" colspan="10">{{$model->dimensions_data['input_d']}}</td>
                            </tr>
                            <tr>
                                <td class="border-dark white  text-bold-600 " style="text-align: left;"> <span style=" margin-left: 5px;">E Blade Width</span></td>
                                <td class="border-dark white" colspan="10">{{$model->dimensions_data['input_e']}}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{--Image on the Right Side--}}
                <div class="col-8">
                    @if($model->pipe_type === 'type_1')
                        <img src="{{asset('app-assets/images/inspections/stabilizer-inspection/1.png')}}"
                             class="inspection-img"/>
                    @else
                        <img src="{{asset('app-assets/images/inspections/stabilizer-inspection/2.png')}}"
                             class="inspection-img"/>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!----------------------------------->
    <div class="row" style="margin-top: 3px;">
        <div class="col-12 bg-dark p-0 border-dark text-center">
            <h6 class="white text-bold-600 pl-1 mb-0">Material Dimensions</h6>
        </div>

        <div class="col-12">
            <div class="row">
                <table style="width: 100%;" class="text-center border">
                    <tr>
                        <td class=" bg-dark p-0 border-dark white  text-bold-600 " width="5%">BOX</td>
                        <td>
                            <table style="width: 100%;" class="text-center border">
                                <tr>
                                    <td class="border-dark white  text-bold-600 " style="width: 10%;">OD</td>
                                    <td class="border-dark white  text-bold-600 " style="width: 10%;">Bevel Dia.</td>
                                    <td class="border-dark white  text-bold-600 " style="width: 10%;">Seal Width</td>
                                    <td class="border-dark white  text-bold-600 " style="width: 10%;">Qc Dia.</td>
                                    <td class="border-dark white  text-bold-600 " style="width: 10%;">Qc Depth</td>
                                    <td class="border-dark white  text-bold-600 " style="width: 10%;">Lbc</td>
                                    <td class="border-dark white  text-bold-600 " style="width: 10%;">B.B Dia</td>
                                    <td class="border-dark white  text-bold-600 " style="width: 10%;">BRB Length</td>
                                    <td class="border-dark white  text-bold-600 " style="width: 10%;">Thread</td>
                                    <td class="border-dark white  text-bold-600 " style="width: 10%;">Condition</td>
                                </tr>
                                <tr>
                                    <td class="border-dark white">{{$model->inspection_data['input_1']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_2']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_3']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_4']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_5']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_6']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_7']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_8']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_9']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_10']}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table style="width: 100%;" class="text-center border">
                    <tr>
                        <td class=" bg-dark p-0 border-dark white  text-bold-600" width="5%">
                            @if($model->pipe_type === 'type_1')
                                BOX
                            @else
                                PIN
                            @endif
                        </td>
                        <td>
                            <table style="width: 100%;" class="text-center border">
                                <tr>
                                    @if($model->pipe_type === 'type_1')
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">OD</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Bevel Dia.</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Seal Width</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Qc Dia.</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Qc Depth</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Lbc</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">B.B Dia</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">BRB Length</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Thread</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Condition</td>
                                    @else
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">OD</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">ID</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Bevel Dia.</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Seal Width</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Lpc</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">SRG Length</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">SRG Dia.</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Tong space</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Thread</td>
                                        <td class="border-dark white  text-bold-600 " style="width: 10%;">Condition</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td class="border-dark white">{{$model->inspection_data['input_11']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_12']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_13']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_14']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_15']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_16']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_17']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_18']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_19']}}</td>
                                    <td class="border-dark white">{{$model->inspection_data['input_20']}}</td>

                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!----------------------------------->
    <div class="row" style="margin-top: 3px;">
        <div class="col-12 bg-dark p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Comments</h6>
        </div>

        <div class="col-12 border-dark m-0 p-0">
            <h6 class="white text-bold-600 pl-1 mb-0 under-line">A - Result:</h6>
            <div class="row" style="padding: 0px 30px;">
                <div class="col-2 p-0">
                    <h6 class="white text-bold-600 pl-1 mb-0">Body Condition:</h6>
                </div>
                <div class="col-4 p-0 border-dark black">
                    <span style="margin-left: 5px;">{{$model->body_condition}}</span>
                </div>

                <div class="col-2 p-0 ">
                    <h6 class="white text-bold-600 pl-1 mb-0">Blades Conditions:</h6>
                </div>
                <div class="col-4 p-0 border-dark black">
                    <span style="margin-left: 5px;">{{$model->blades_condition}}</span>
                </div>

                <div class="col-2 p-0">
                    <h6 class="white text-bold-600 pl-1 mb-0">HF Condition: </h6>
                </div>
                <div class="col-4 p-0 border-dark black">
                    <span style="margin-left: 5px;">{{$model->hf_condition}}</span>
                </div>

                <div class="col-2 p-0">
                    <h6 class="white text-bold-600 pl-1 mb-0">Blades Diameter: </h6>
                </div>
                <div class="col-4 p-0 border-dark black">
                    <span style="margin-left: 5px;">{{$model->blades_diameter}}</span>
                </div>

            </div>

            <h6 class="white text-bold-600 pl-1 mb-0 mt-1 under-line">B - Additional Comment:</h6>
            <div class="row" style="padding: 5px 20px; min-height: 5em;">
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
                    <td>TD = Thread Damage </td>
                    <td>ST = Stretched Threads </td>
                    <td>CR = Crack </td>
                    <td>RB = Rebevel</td>
                </tr>
                <tr >
                    <td>W = Wash out</td>
                    <td>RF = Reface</td>
                    <td>Cor = Corrosion</td>
                    <td>BB = Belled Box</td>
                    <td>NDF = No Defects found</td>

                </tr>
                <tr >
                    <td>STD = Standard Connection</td>
                    <td>SRG = Stress Relief Groove </td>
                    <td>BRB = Bore Back Box</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    <!----------------------------------->
    @include('layouts.styles.reportfooter-v2')
    @include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $model->report->id])
@endpush
