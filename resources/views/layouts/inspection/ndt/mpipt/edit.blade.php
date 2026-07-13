@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
    <section id="validation">
        <div class="row">
            <div class="col-12">
                <form action="#" class="steps-validation wizard-circle" enctype="multipart/form-data">
                    @method('PUT')
                    <h6>Step 1</h6>
                    <fieldset>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>JCF Number</label>
                                                    <select class="form-control" id="lcr_1" name="lcr_1" required="" disabled>
                                                        <option value="{{$mpipt->job_request->id}}" selected>{{$mpipt->job_request->code}}</option>
                                                    </select>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Purchase Order</label>
                                                    <input disabled type="text" id="purchaseOrder" name="nmpr_2" class="form-control" placeholder="Purchase Order" value="{{$mpipt->nmpr_2}}" >
                                                <div class="help-block"></div></div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Report No.</label>
                                                    <div class="clearfix">
                                                        <input type="text" id="precode" class="form-control pr-0" placeholder="Report No" value="{{$mpipt->job_request->code}} /" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled style="width: 83px; float: left;">
                                                        <input type="text" id="code" name="code" class="form-control pl-0" placeholder="." value="{{$mpipt->code}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled  style="width: auto; float: left;">
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Name of employer for whom the examination was made</label>
                                                    <input type="text" placeholder="Name of employer for whom the examination was made" class="form-control" id="cliname" name="cliname" disabled>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Address of premises at examination was made</label>
                                                    <input type="text" id="cliloc" name="cliloc" class="form-control" value="" placeholder="Address of premises at examination was made" disabled >
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Examination Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="ft-calendar"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control dp-date-range-from" id="nmpr_6" name="nmpr_6" placeholder="Examination Date" data-validation--message="This field is " value="{{$mpipt->nmpr_6}}"/>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Work location</label>
                                                    <input type="text" id="deploc" name="deploc" class="form-control" placeholder="Work location" value="" required="" data-validation-required-message="This field is required" disabled />
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="col-6 col-sm-4">--}}
                                            {{--<div class="form-group mb-0">--}}
                                                {{--<div class="controls">--}}
                                                    {{--<label>Client Department</label>--}}
                                                    {{--<input type="text" id="clientDepartment" name="clientDepartment" class="form-control"--}}
                                                           {{--placeholder="Client Department" value="" required=""--}}
                                                           {{--data-validation-required-message="This field is required"--}}
                                                           {{--disabled/>--}}
                                                    {{--<div class="help-block"></div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Specification</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row">
                                                @foreach($specifications as $specification)
                                                    @if(strtolower(str_replace(' ', '-', $specification->name)) != 'bs' && strtolower(str_replace(' ', '-', $specification->name)) != 'asnt' && strtolower(str_replace(' ', '-', $specification->name)) != 'aws' && strtolower(str_replace(' ', '-', $specification->name)) != 'rse-procedure')
                                                        <div class="col-md-3 col-sm-12">
                                                            <fieldset>
                                                                <input type="checkbox" class="nmpr_8" name="nmpr_8"  id="{{strtolower(str_replace(' ', '-', $specification->name))}}"
                                                                @if(in_array(strtolower(str_replace(' ', '-', $specification->name)), json_decode($mpipt->nmpr_8))) checked @endif />
                                                                <label for="{{strtolower(str_replace(' ', '-', $specification->name))}}">{{$specification->name}}</label>
                                                            </fieldset>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>If Other</label>
                                                            <input type="text" id="nmpr_7" name="nmpr_7" class="form-control" placeholder=" If Other " value="{{$mpipt->nmpr_7}}" data-validation--message="This field is " />
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Edition</label>
                                                            <input type="text" id="nmpr_10" name="nmpr_10" class="form-control" placeholder="Edition" value="{{$mpipt->nmpr_10}}" data-validation--message="This field is " />
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Acceptance Criteria</label>
                                                            <input type="text" id="nmpr_11" name="nmpr_11" class="form-control" placeholder="Acceptance Criteria" value="{{$mpipt->acceptance}}" data-validation--message="This field is "/>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Temperature</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Temperature</label>
                                                    <input type="text" id="nmpr_46" name="nmpr_46" class="form-control pt" placeholder="Temperature" value="{{$mpipt->getMtvalue('nmpr_46', 'nmpr_13')}}" data-validation--message="This field is " />
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Viewing Conditions</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="nmpr_800" name="nmpr_800" id="visible_day_light" @if(is_array(json_decode($mpipt->nmpr_800)) && in_array('visible_day_light', json_decode($mpipt->nmpr_800))) checked @endif>
                                                        <label for="visible_day_light">Visible / Day Light</label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="nmpr_800" name="nmpr_800" id="fluorescent" @if(is_array(json_decode($mpipt->nmpr_800)) && in_array('fluorescent', json_decode($mpipt->nmpr_800))) checked @endif>
                                                        <label for="fluorescent">Fluorescent</label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="nmpr_800" name="nmpr_800" id="black_light" @if(is_array(json_decode($mpipt->nmpr_800)) && in_array('black_light', json_decode($mpipt->nmpr_800))) checked @endif>
                                                        <label for="black_light">Black Light</label>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Intensity</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="nmpr_900" name="nmpr_900" id="lux" @if(is_array(json_decode($mpipt->nmpr_900)) && in_array('lux', json_decode($mpipt->nmpr_900))) checked @endif>
                                                        <label for="lux">>1076 Lux</label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="nmpr_900" name="nmpr_900" id="215lux" @if(is_array(json_decode($mpipt->nmpr_900)) && in_array('215lux', json_decode($mpipt->nmpr_900))) checked @endif>
                                                        <label for="nm">21.5 Lux</label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="nmpr_900" name="nmpr_900" id="nm" @if(is_array(json_decode($mpipt->nmpr_900)) && in_array('nm', json_decode($mpipt->nmpr_900))) checked @endif>
                                                        <label for="nm">365nm</label>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <h6>Step 2</h6>
                    <fieldset>
                        <h6 class="mb-1">Inspection Type</h6>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="skin skin-square form-group">
                                                <div class="controls">
                                                    <fieldset>
                                                    <input type="checkbox" class="specification" name="nmpr_12" id="nmpr_12" @if(!empty(json_decode($mpipt->nmpr_12)) && !empty($mpipt->getMtvalue('nmpr_34', 'nmpr_12'))) checked @endif >
                                                        <label for="nmpr_12">MT</label>
                                                    </fieldset>
                                                    <hr />
                                                    <input type="text" id="nmpr_13" name="nmpr_13" class="form-control eu mt" placeholder="RSE Procedure" value="{{$mpipt->getMtvalue('nmpr_13', 'nmpr_12')}}" data-validation--message="This field is " />
                                                    
                                                    <div class="skin skin-square form-group mt-1">
                                                        <div class="controls">
                                                            <div class="row" style="margin-left: auto; margin-right: auto;">
                                                                <label class="col-md-2 col-sm-12 mb-1 ml-0 pl-2">Current Type</label>
                                                                <div class="col-md-2 col-sm-12">
                                                                    <fieldset>
                                                                        <input type="checkbox" class="nmpr_3400 eu mt" name="nmpr_3400" id="ac" 
                                                                        @if(!empty(json_decode($mpipt->nmpr_12)))
                                                                            @if($mpipt->getMtvalue('ac', 'nmpr_12') === 'on') checked @endif
                                                                        @endif
                                                                        >
                                                                        <label for="ac">AC</label>
                                                                    </fieldset>
                                                                </div>
                                                                <div class="col-md-2 col-sm-12">
                                                                    <fieldset>
                                                                        <input type="checkbox" class="nmpr_3400 eu mt" name="nmpr_3400" id="dc" 
                                                                        @if(!empty(json_decode($mpipt->nmpr_12)))
                                                                            @if($mpipt->getMtvalue('dc', 'nmpr_12') === 'on') checked @endif
                                                                        @endif
                                                                        >
                                                                        <label for="dc">DC</label>
                                                                    </fieldset>
                                                                </div>
                                                                <div class="col-md-3 col-sm-12">
                                                                    <fieldset>
                                                                        <input type="checkbox" class="nmpr_3400 eu mt" name="nmpr_3400" id="permanent-magnet" 
                                                                        @if(!empty(json_decode($mpipt->nmpr_12)))
                                                                            @if($mpipt->getMtvalue('permanent-magnet', 'nmpr_12') === 'on') checked @endif
                                                                        @endif
                                                                        >
                                                                        <label for="permanent-magnet">Permanent Magnet</label>
                                                                    </fieldset>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="skin skin-square form-group mt-1">
                                                        <div class="controls">
                                                            <div class="row" style="margin-left: auto; margin-right: auto;">
                                                                <label class="col-md-2 col-sm-12 mb-1 ml-0 pl-2">Methods</label>
                                                                <div class="col-md-2 col-sm-12">
                                                                    <fieldset>
                                                                        <input type="checkbox" class="nmpr_34 eu mt" name="nmpr_34" id="active" 
                                                                        @if(!empty(json_decode($mpipt->nmpr_12)))
                                                                            @if($mpipt->getMtvalue('active', 'nmpr_12') === 'on') checked @endif
                                                                        @endif
                                                                        >
                                                                        <label for="active">Active</label>
                                                                    </fieldset>
                                                                </div>
                                                                <div class="col-md-2 col-sm-12">
                                                                    <fieldset>
                                                                        <input type="checkbox" class="nmpr_34 eu mt" name="nmpr_34" id="residual" 
                                                                        @if(!empty(json_decode($mpipt->nmpr_12)))
                                                                            @if($mpipt->getMtvalue('residual', 'nmpr_12') === 'on') checked @endif
                                                                        @endif
                                                                        >
                                                                        <label for="residual">Residual</label>
                                                                    </fieldset>
                                                                </div>
                                                                <div class="col-md-6 col-sm-12">
                                                                    <input type="text" id="nmpr_3401" name="nmpr_3401" class="form-control eu mt" placeholder="Demagnetization" value="{{$mpipt->getMtvalue('nmpr_3401', 'nmpr_12')}}" data-validation--message="This field is ">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Equipment Used</th>
                                                                <th scope="col">
                                                                    <fieldset>
                                                                        <input type="checkbox" class="eu" name="nmpr_120" id="nmpr_120" {{$mpipt->checkMtvalue('nmpr_14', 'nmpr_12')}}>
                                                                        <label for="nmpr_120">Magnet</label>
                                                                    </fieldset>
                                                                </th>
                                                                <th scope="col">
                                                                    <fieldset>
                                                                        <input type="checkbox" class="eu" name="nmpr_121" id="nmpr_121" {{$mpipt->checkMtvalue('nmpr_15', 'nmpr_12')}}>
                                                                        <label for="nmpr_121">UV Light</label>
                                                                    </fieldset>
                                                                </th>
                                                                <th scope="col">
                                                                    <fieldset>
                                                                        <input type="checkbox" class="eu" name="nmpr_122" id="nmpr_122" {{$mpipt->checkMtvalue('nmpr_16', 'nmpr_12')}}>
                                                                        <label for="nmpr_122">Coil</label>
                                                                    </fieldset>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">Equipment No</th>
                                                                <td><input type="text" id="nmpr_14" name="nmpr_14" class="form-control magnet mt" placeholder="Contrast/Manufacturer" value="{{$mpipt->getMtvalue('nmpr_14', 'nmpr_12')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_15" name="nmpr_15" class="form-control uvl mt" placeholder="Indicator/ Manufacturer" value="{{$mpipt->getMtvalue('nmpr_15', 'nmpr_12')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_16" name="nmpr_16" class="form-control coil mt" placeholder="Indicator/ Manufacturer" value="{{$mpipt->getMtvalue('nmpr_16', 'nmpr_12')}}" data-validation--message="This field is " ></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Manufacturer</th>
                                                                <td><input type="text" id="nmpr_17" name="nmpr_17" class="form-control magnet mt" placeholder="Contrast/Expire Date" value="{{$mpipt->getMtvalue('nmpr_17', 'nmpr_12')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_18" name="nmpr_18" class="form-control uvl mt" placeholder="Indicator/ Expire Date" value="{{$mpipt->getMtvalue('nmpr_18', 'nmpr_12')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_19" name="nmpr_19" class="form-control coil mt" placeholder="Contrast/Expire Date" value="{{$mpipt->getMtvalue('nmpr_19', 'nmpr_12')}}" data-validation--message="This field is " ></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Calibr. Due Date</th>
                                                                <td><input type="text" id="nmpr_20" name="nmpr_20" class="form-control magnet mt" placeholder="Indicator/ Expire Date" value="{{$mpipt->getMtvalue('nmpr_20', 'nmpr_12')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_21" name="nmpr_21" class="form-control uvl mt" placeholder="Indicator/ Expire Date" value="{{$mpipt->getMtvalue('nmpr_21', 'nmpr_12')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_22" name="nmpr_22" class="form-control coil mt" placeholder="Contrast/Expire Date" value="{{$mpipt->getMtvalue('nmpr_22', 'nmpr_12')}}" data-validation--message="This field is " ></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Equip.Test Criteria</th>
                                                                <td><input type="text" id="nmpr_23" name="nmpr_23" class="form-control magnet mt" placeholder="Indicator/ Expire Date" value="{{$mpipt->getMtvalue('nmpr_23', 'nmpr_12')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_24" name="nmpr_24" class="form-control uvl mt" placeholder="Indicator/ Expire Date" value="{{$mpipt->getMtvalue('nmpr_24', 'nmpr_12')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_25" name="nmpr_25" class="form-control coil mt" placeholder="Indicator/ Expire Date" value="{{$mpipt->getMtvalue('nmpr_25', 'nmpr_12')}}" data-validation--message="This field is " ></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                              
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Solution details</th>
                                                                <th scope="col">Contrast</th>
                                                                <th scope="col">Indicator</th>
                                                                <th scope="col">Poweder</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">Manufacturer</th>
                                                                <td><input type="text" id="nmpr_35" name="nmpr_35" class="form-control eu mt" placeholder="Contrast/Manufacturer" value="{{$mpipt->getMtvalue('nmpr_35', 'nmpr_12')}}" data-validation--message="This field is" ></td>
                                                                <td><input type="text" id="nmpr_36" name="nmpr_36" class="form-control eu mt" placeholder="Indicator/ Manufacturer" value="{{$mpipt->getMtvalue('nmpr_36', 'nmpr_12')}}" data-validation--message="This field is" ></td>
                                                                <td><input type="text" id="nmpr_37" name="nmpr_37" class="form-control eu mt" placeholder="Indicator/ Manufacturer" value="{{$mpipt->getMtvalue('nmpr_37', 'nmpr_12')}}" data-validation--message="This field is" ></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Expire Date</th>
                                                                <td><input type="text" id="nmpr_39" name="nmpr_39" class="form-control eu mt" placeholder="Indicator/ Expire Date" value="{{$mpipt->getMtvalue('nmpr_38', 'nmpr_12')}}" data-validation--message="This field is" ></td>
                                                                <td><input type="text" id="nmpr_38" name="nmpr_38" class="form-control eu mt" placeholder="Contrast/Expire Date" value="{{$mpipt->getMtvalue('nmpr_39', 'nmpr_12')}}" data-validation--message="This field is" ></td>
                                                                <td><input type="text" id="nmpr_40" name="nmpr_40" class="form-control eu mt" placeholder="Contrast/Expire Date" value="{{$mpipt->getMtvalue('nmpr_40', 'nmpr_12')}}" data-validation--message="This field is" ></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <h6>Step 3</h6>
                    <fieldset>
                        <h6 class="mb-1">Inspection Type</h6>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="skin skin-square form-group">
                                                <div class="controls">
                                                    <fieldset>
                                                        <input type="checkbox" class="specification" name="nmpr_26" id="nmpr_26" @if(!empty(json_decode($mpipt->nmpr_13))) checked @endif>
                                                        <label for="nmpr_26">PT</label>
                                                    </fieldset>
                                                    <hr />
                                                    <input type="text" id="nmpr_27" name="nmpr_27" class="form-control sd pt" placeholder="RSE Procedure" value="{{$mpipt->getMtvalue('nmpr_27', 'nmpr_13')}}" data-validation--message="This field is " />
                                                    <hr />
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Spray Details</th>
                                                                <th scope="col">
                                                                    <fieldset>
                                                                        <input type="checkbox" class="sd" name="nmpr_130" id="nmpr_130" {{$mpipt->checkMtvalue('nmpr_28', 'nmpr_13')}}>
                                                                        <label for="nmpr_130">Pentrant</label>
                                                                    </fieldset>
                                                                </th>
                                                                <th scope="col">
                                                                    <fieldset>
                                                                        <input type="checkbox" class="sd" name="nmpr_131" id="nmpr_131" {{$mpipt->checkMtvalue('nmpr_29', 'nmpr_13')}}>
                                                                        <label for="nmpr_131">Cleaner</label>
                                                                    </fieldset>
                                                                </th>
                                                                <th scope="col">
                                                                    <fieldset>
                                                                        <input type="checkbox" class="sd" name="nmpr_132" id="nmpr_132" {{$mpipt->checkMtvalue('nmpr_30', 'nmpr_13')}}>
                                                                        <label for="nmpr_132">Developer</label>
                                                                    </fieldset>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">Manufacturer</th>
                                                                <td><input type="text" id="nmpr_28" name="nmpr_28" class="form-control pentrant pt" placeholder="Pentrant /Manufacturer" value="{{$mpipt->getMtvalue('nmpr_28', 'nmpr_13')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_29" name="nmpr_29" class="form-control cleaner pt" placeholder="Cleaner / Manufacturer" value="{{$mpipt->getMtvalue('nmpr_29', 'nmpr_13')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_30" name="nmpr_30" class="form-control developer pt" placeholder="Developer / Manufacturer" value="{{$mpipt->getMtvalue('nmpr_30', 'nmpr_13')}}" data-validation--message="This field is " ></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Expire Date</th>
                                                                <td><input type="text" id="nmpr_31" name="nmpr_31" class="form-control pentrant pt" placeholder="Pentrant / Expire Date" value="{{$mpipt->getMtvalue('nmpr_31', 'nmpr_13')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_32" name="nmpr_32" class="form-control cleaner pt" placeholder="Cleaner / Expire Date" value="{{$mpipt->getMtvalue('nmpr_32', 'nmpr_13')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_33" name="nmpr_33" class="form-control developer pt" placeholder="Developer / Expire Date" value="{{$mpipt->getMtvalue('nmpr_33', 'nmpr_13')}}" data-validation--message="This field is " ></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Dwell Time (min)</th>
                                                                <td><input type="text" id="nmpr_310" name="nmpr_310" class="form-control pentrant pt" placeholder="Pentrant / Dwell Time (min)" value="{{$mpipt->getMtvalue('nmpr_310', 'nmpr_13')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_320" name="nmpr_320" class="form-control cleaner pt" placeholder="Cleaner / Dwell Time (min)" value="{{$mpipt->getMtvalue('nmpr_320', 'nmpr_13')}}" data-validation--message="This field is " ></td>
                                                                <td><input type="text" id="nmpr_330" name="nmpr_330" class="form-control developer pt" placeholder="Developer / Dwell Time (min)" value="{{$mpipt->getMtvalue('nmpr_330', 'nmpr_13')}}" data-validation--message="This field is " ></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Method Description & Pre-Cleaning Method</h6>
                        <div class="card">
                            <div class="card-content collapse show">
                              <div class="card-body">

                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row" style="margin-left: auto; margin-right: auto;">
                                                <label class="col-md-3 col-sm-12 mb-1 ml-0 pl-2">Penetrant Type</label>
                                                <div class="col-md-3 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="nmpr_311 sd pt" name="nmpr_311" id="water-washable" 
                                                        @if(!empty(json_decode($mpipt->nmpr_13)))
                                                            @if($mpipt->getMtvalue('water-washable', 'nmpr_13') === 'on') checked @endif
                                                        @endif>
                                                        <label for="water-washable">Water Washable</label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="nmpr_311 sd pt" name="nmpr_311" id="solvent-removable" 
                                                        @if(!empty(json_decode($mpipt->nmpr_13)))
                                                            @if($mpipt->getMtvalue('solvent-removable', 'nmpr_13') === 'on') checked @endif
                                                        @endif>
                                                        <label for="solvent-removable">Solvent Removable</label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="nmpr_311 sd pt" name="nmpr_311" id="other700"
                                                        @if(!empty(json_decode($mpipt->nmpr_13)))
                                                            @if($mpipt->getMtvalue('other700', 'nmpr_13') === 'on') checked @endif
                                                        @endif>
                                                        <label for="other700">Other</label>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row" style="margin-left: auto; margin-right: auto;">
                                                <label class="col-md-3 col-sm-12 mb-1 ml-0 pl-2">Penetrant Apply</label>
                                                <div class="col-md-3 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="nmpr_312 sd pt" name="nmpr_312" id="spraying" 
                                                        @if(!empty(json_decode($mpipt->nmpr_13)))
                                                            @if($mpipt->getMtvalue('spraying', 'nmpr_13') === 'on') checked @endif
                                                        @endif>
                                                        <label for="spraying">Spraying</label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="nmpr_312 sd pt" name="nmpr_312" id="brushing" 
                                                        @if(!empty(json_decode($mpipt->nmpr_13)))
                                                            @if($mpipt->getMtvalue('brushing', 'nmpr_13') === 'on') checked @endif
                                                        @endif>
                                                        <label for="brushing">Brushing</label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="nmpr_312 sd pt" name="nmpr_312" id="immersion" 
                                                        @if(!empty(json_decode($mpipt->nmpr_13)))
                                                            @if($mpipt->getMtvalue('immersion', 'nmpr_13') === 'on') checked @endif
                                                        @endif>
                                                        <label for="immersion">Immersion</label>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                  <div class="row">
                                      <div class="col-md-6 col-sm-12">
                                          <div class="form-group">
                                              <div class="controls">
                                                  <label>Developer Apply</label>
                                                  <input type="text" id="nmpr_43" name="nmpr_43" class="form-control sd pt" placeholder="Work location" value="{{$mpipt->getMtvalue('nmpr_43', 'nmpr_13')}}" data-validation--message="This field is" />
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                            </div>
                        </div>
                    </fieldset>
                    <h6>Step 4</h6>
                    <fieldset>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Description</label>
                                                    <textarea id="nmpr_47" name="nmpr_47" class="form-control" placeholder="Description">{{$mpipt->desc}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Identification No</label>
                                                    <input type="text" id="nmpr_48" name="nmpr_48" class="form-control" placeholder="Identification No" value="{{$mpipt->nmpr_28}}" data-validation--message="This field is "/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card repeater">
                            <div class="card-content collapse show">
                                <div class="card-body" data-repeater-list="payments">
                                    <div class="row">
                                      <div class="col-7">
                                        <div class="form-group mb-0">
                                          <label>Attachment Description</label>
                                        </div>
                                      </div>
                                      <div class="col-4">
                                        <div class="form-group mb-0">
                                          <label>Attachment Photo</label>
                                        </div>
                                      </div>
                                      <div class=" col-1"></div>
                                    </div>
                                    @foreach(json_decode($mpipt->nmpr_29) as $value)
                                        <div class="row" data-repeater-item>
                                            <div class="col-5">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea name="nmpr_49" class="form-control" placeholder="Attachment Description">{{$value->nmpr_49}}</textarea>
                                                    <div class="help-block"></div></div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <div class="custom-file">
                                                            <input type="file" name="nmpr_50" class="custom-file-input mpipt-attachment-file" accept="image/*">
                                                            <label class="custom-file-label" style="height: 5.80rem;" aria-describedby="inputGroupFile02">Choose file</label>
                                                        </div>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                @php
                                                    $storedPhoto = trim((string) ($value->nmpr_50 ?? ''));
                                                    $photoPath = $storedPhoto === ''
                                                        ? null
                                                        : (\Illuminate\Support\Str::startsWith($storedPhoto, 'camera/inspection/ndt/mpipts/')
                                                            ? ltrim($storedPhoto, '/')
                                                            : 'camera/inspection/ndt/mpipts/'.ltrim($storedPhoto, '/'));
                                                    $photoExists = $photoPath && is_file(public_path('storage/'.$photoPath));
                                                @endphp
                                                @if($photoExists)
                                                    <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{ asset('storage/'.$photoPath) }}" alt="" />
                                                @else
                                                    <div class="text-muted small pt-2 attachment-photo-placeholder">No image</div>
                                                @endif
                                                <input type=hidden name="lcr_140" value="{{$value->nmpr_50}}" />
                                                <input type=hidden name="lcr_140_old" value="{{$value->nmpr_50}}" />
                                            </div>
                                            <div class="col-1 mt-1 mt-sm-0">
                                                <button type="button" class="btn btn-icon btn-danger mr-1 remove-attachment-photo"><i class="la la-trash"></i></button>

                                            </div>
                                            <div class=" col-1">
                                                <button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class=" form-group overflow-hidden">
                                    <div class="col-12">
                                        <button type="button" data-repeater-create class="btn btn-primary"><i class="ft-plus"></i> Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6>Final Conclusion:</h6>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">

                                    <div class="form-group">
                                        <div class="controls">
                                            <!-- <textarea id="nmpr_51" name="nmpr_51" class="form-control" placeholder="Final Conclusion"></textarea> -->
                                            <div class="row">

                                                <div class="col-8">
                                                        <p>Final result : Accept / Reject ?</p>
                                                </div>
                                                <div class="col-2">
                                                        <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" id="nmpr_51_y" name="nmpr_51" {{$mpipt->checkbox_yes($mpipt->nmpr_30)}}  required>
                                                                <label class="custom-control-label" for="nmpr_51_y">Accept</label>
                                                        </div>
                                                </div>
                                                <div class="col-2">
                                                        <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" id="nmpr_51_n" name="nmpr_51" {{$mpipt->checkbox_no($mpipt->nmpr_30)}}>
                                                                <label class="custom-control-label" for="nmpr_51_n">Reject</label>
                                                        </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.scripts.forms')

@prepend('child-scripts')

    <script src="{{asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('app-assets/js/scripts/forms/form-repeater.js')}}"></script>
    <script>
            $(".steps-validation").steps({
                enableAllSteps: true, 
                headerTag: "h6",
                bodyTag: "fieldset",
                transitionEffect: "fade",
                titleTemplate: '<span class="step">#index#</span> #title#',
                labels: {
                    finish: 'Submit'
                },
                onStepChanging: function (event, currentIndex, newIndex) {
                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex) {
                        return true;
                    }
// Needed in some cases if the user went back (clean up)
                    if (currentIndex < newIndex) {
                        // To remove error styles
                        form.find(".body:eq(" + newIndex + ") label.error").remove();
                        form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                    }
                    return window.validateWizardCurrentStep($(this));
                },
                onFinishing: function (event, currentIndex) {
                    form.validate().settings.ignore = ":disabled";
                    return form.valid();
                },
                onFinished: function (event, currentIndex) {
            
                var wizardForm = $('.steps-validation').first();
                var form1 = wizardForm[0];
                var formdata = new FormData(form1);
                var checkbox = wizardForm.find("input[type=radio]");
                formdata.append('lcr_1', $('#lcr_1').val());
                formdata.append('code', $('#code').val());
              formdata.append('nmpr_2', $('#purchaseOrder').val());
                formdata.append('nmpr_8', JSON.stringify(nmpr_8));
                formdata.append('nmpr_800', JSON.stringify(nmpr_800));
                formdata.append('nmpr_900', JSON.stringify(nmpr_900));
                formdata.append('nmpr_12', JSON.stringify(nmpr_12));
                formdata.append('nmpr_13', JSON.stringify(nmpr_13));

                $.each(checkbox, function(key, val) {
                    if($(this).is(':checked') === true){
                    formdata.append($(this).attr('name'), $(this).attr('id'));
                    }
                });
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    url: "{{route('mpipt.update', $mpipt->id)}}",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formdata,
                    dataType: "JSON",
                    beforeSend:function(){
                        $('#submit i').addClass('la la-refresh spinner');
                    },
                    success: function (data){
                        toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () {
                            window.location.replace("{{route('mpipt.index')}}");
                        }
                        });
                    },
                });
                }
            });
    </script>
@endprepend

@extends('layouts.scripts.reportsforms')

@push('bottom-child-scripts')
    <script>
        get_report_data_for_update($('#lcr_1 option:selected').val(), $('#lcr_1 option:selected').text());
        var nmpr_8 = [];
        var nmpr_800 = [];
        var nmpr_900 = [];
        var nmpr_12 = [];
        var nmpr_13 = [];

        /**  */
        function whenCheckRemove(arrayName = null, className, disabledValue){
            $.each($(className), function(value) {
                cls = $(this);
                console.log(disabledValue)
                cls.prop("disabled", disabledValue);

                if(disabledValue === true){
                    cls.val('');
                    onAnyInputChange(arrayName, cls[0]);
                }

            });
        }

        function addtionalRadioCheck(arrayName, obj, flag = true){
            index = arrayName.indexOf(arrayName.find(o => o.name === obj.name));
            if(flag === false){
                index = arrayName.indexOf(arrayName.find(o => o.id === obj.id));
            }
            if(index > -1)
            {
                arrayName.splice(index, 1);
            }

            var radioObj = {};
            radioObj.id = obj.id;
            radioObj.value = 'on';
            radioObj.name = obj.name;
            
            arrayName.push(radioObj);
        }

        /** */
        function onAnyInputChange(arrayName, obj){
            index1 = arrayName.map(function(e) { return e.id; }).indexOf(obj.id);
            if(obj.value !== '')
            {
                var mt = {};
                mt.id = obj.id;
                mt.value = obj.value;
                if(index1 >= 0){
                    arrayName.splice(index1, 1);
                }
                arrayName.push(mt);
            }
            else
            {
                arrayName.splice(index1, 1);
            }

        }

        /** Specification Selection */
        $.each($('.nmpr_8'), function(value) {
          if ($(this).is(':checked')){
            var id = this.id;
            if(id === 'other'){
              $('#nmpr_9').prop("disabled", false);
            }
            nmpr_8.push(id);
          }
        });

        $('.steps-validation').on('ifChecked','.nmpr_8', function(){
          var id = this.id;
          if(id === 'other'){
            $('#nmpr_7').prop("disabled", false);
          }
          nmpr_8.push(id);
        });
        $('.steps-validation').on('ifUnchecked','.nmpr_8', function(event){
          var id = this.id;
          if(id === 'other'){
            $('#nmpr_7').prop("disabled", true).val('');
          }
          index1 = nmpr_8.indexOf(id);
          nmpr_8.splice(index1, 1);
        });

        /** Viewing Conditions */
        $.each($('.nmpr_800'), function(value) {
          if ($(this).is(':checked')){
            var id = this.id;
            nmpr_800.push(id);
          }
        });

        $('.steps-validation').on('ifChecked','.nmpr_800', function(){
          var id = this.id;
          nmpr_800.push(id);
        });
        $('.steps-validation').on('ifUnchecked','.nmpr_800', function(event){
          var id = this.id;
          index1 = nmpr_800.indexOf(id);
          nmpr_800.splice(index1, 1);
        });

        /** Intensity */
        $.each($('.nmpr_900'), function(value) {
          if ($(this).is(':checked')){
            var id = this.id;
            nmpr_900.push(id);
          }
        });

        $('.steps-validation').on('ifChecked','.nmpr_900', function(){
          var id = this.id;
          nmpr_900.push(id);
        });
        $('.steps-validation').on('ifUnchecked','.nmpr_900', function(event){
          var id = this.id;
          index1 = nmpr_900.indexOf(id);
          nmpr_900.splice(index1, 1);
        });

        /***************************************************************/

        /** MT */

        if ($("#nmpr_12").is(':checked')){
          $.each($('.mt'), function(value) {
            index1 = nmpr_12.map(function(e) { return e.id; }).indexOf(this.id);
            if(this.value !== ''){
              var pt = {};
              pt.id = this.id;
              pt.value = this.value;
              if(index1 >= 0){
                nmpr_12.splice(index1, 1);
              }

              
              nmpr_12.push(pt);
            }
          });
          
        }else{
          $.each($('.mt'), function(value) {
            $(this).prop("disabled", true);
          });
        }

        function checkIfChecked(className, arrayName){
            $.each($(className),function(value){
                var obj = this;
                if(this.checked){
                    
                    addtionalRadioCheck(arrayName, obj, false);  
                }else{
                    index = arrayName.indexOf(arrayName.find(o => o.id === obj.id));
                
                    if(index > -1)
                    {
                        arrayName.splice(index, 1);
                    }
                }
            });
        }

        checkIfChecked('.nmpr_34', nmpr_12);
        checkIfChecked('.nmpr_3400', nmpr_12);

        $('.steps-validation').on('ifChecked','#nmpr_12', function(){
          $.each($('.eu'), function(value) {
            $(this).prop("disabled", false);
          });
        });
        
        $('.steps-validation').on('ifUnchecked','#nmpr_12', function(event){
          $("#nmpr_120, #nmpr_121, #nmpr_122", ".nmpr_34", ".nmpr_3400").iCheck('uncheck');
          $.each($('.eu'), function(value) {
            $(this).prop("disabled", true).val('');
            nmpr_12 = [];
          });
        });

        $('.steps-validation').on('ifChecked','#nmpr_120', function(){
            whenCheckRemove(null, '.magnet', false);
        });
        $('.steps-validation').on('ifUnchecked','#nmpr_120', function(){
            whenCheckRemove(nmpr_12, '.magnet', true);
        });

        $('.steps-validation').on('ifChecked','#nmpr_121', function(){
            whenCheckRemove(null, '.uvl', false);
        });
        $('.steps-validation').on('ifUnchecked','#nmpr_121', function(){
            whenCheckRemove(nmpr_12, '.uvl', true);
        });

        $('.steps-validation').on('ifChecked','#nmpr_122', function(){
            whenCheckRemove(null, '.coil', false);
        });
        $('.steps-validation').on('ifUnchecked','#nmpr_122', function(){
            whenCheckRemove(nmpr_12, '.coil', true);
        });
        
        $('.steps-validation').on('ifChecked','.nmpr_34, .nmpr_3400', function(){
            var obj = this;
            addtionalRadioCheck(nmpr_12, obj);
        });
        
        $('.steps-validation').on('change','.mt',function(){
            var obj = this;
            onAnyInputChange(nmpr_12, obj);
        });

        /**************************************************************/
        /** PT */
        
        if ($("#nmpr_26").is(':checked')){
          $.each($('.pt'), function(value) {
            index1 = nmpr_13.map(function(e) { return e.id; }).indexOf(this.id);
            if(this.value !== ''){
              var pt = {};
              pt.id = this.id;
              pt.value = this.value;
              if(index1 >= 0){
                nmpr_13.splice(index1, 1);
              }
              nmpr_13.push(pt);
            }
          });
        }else{
          $.each($('.pt'), function(value) {
            $(this).prop("disabled", true);
          });
        }

        checkIfChecked('.nmpr_311', nmpr_13);
        checkIfChecked('.nmpr_312', nmpr_13);

        $('.steps-validation').on('ifChecked','#nmpr_26', function(){
          $.each($('.sd'), function(value) {
            $(this).prop("disabled", false);
          });
        });
        $('.steps-validation').on('ifUnchecked','#nmpr_26', function(event){
          $("#nmpr_130, #nmpr_131, #nmpr_132").iCheck('uncheck');
          $.each($('.sd'), function(value) {
            $(this).prop("disabled", true).val('');
            nmpr_13 = [];
          });
        });

        $('.steps-validation').on('ifChecked','#nmpr_130', function(){
            whenCheckRemove(null, '.pentrant', false);
        });
        $('.steps-validation').on('ifUnchecked','#nmpr_130', function(){
            whenCheckRemove(nmpr_13, '.pentrant', true);
        });

        $('.steps-validation').on('ifChecked','#nmpr_131', function(){
            whenCheckRemove(null, '.cleaner', false);
        });
        $('.steps-validation').on('ifUnchecked','#nmpr_131', function(){
            whenCheckRemove(nmpr_13, '.cleaner', true);
        });

        $('.steps-validation').on('ifChecked','#nmpr_132', function(){
            whenCheckRemove(null, '.developer', false);
        });
        $('.steps-validation').on('ifUnchecked','#nmpr_132', function(){
            whenCheckRemove(nmpr_13, '.developer', true);
        });

        $('.steps-validation').on('ifChecked','.nmpr_311, .nmpr_312', function(){
            var obj = this;
            addtionalRadioCheck(nmpr_13, obj);
        });
        
        $('.steps-validation').on('change','.pt',function(){
            var obj = this;
            onAnyInputChange(nmpr_13, obj);
            
        });

        /**************************************************************/

        var mpiptAttachmentSeq = 0;
        function reindexMpiptAttachmentInputs() {
            $('.steps-validation [data-repeater-list="payments"] [data-repeater-item]').each(function () {
                var $row = $(this);
                var $file = $row.find('.mpipt-attachment-file').first();
                var $label = $row.find('.custom-file-label').first();
                if (!$file.length || !$label.length) {
                    return;
                }
                mpiptAttachmentSeq += 1;
                var inputId = 'mpipt_attachment_' + mpiptAttachmentSeq;
                $file.attr('id', inputId);
                $label.attr('for', inputId);
            });
        }

        $('.repeater').repeater({
          show: function () {
            var $item = $(this);
            $item.slideDown();
            $item.find('img.media-object').remove();
            $item.find('input[name="lcr_140"], input[name="lcr_140_old"]').val('');
            $item.find('.custom-file-label').text('Choose file');
            if (!$item.find('.attachment-photo-placeholder').length) {
                $('<div class="text-muted small pt-2 attachment-photo-placeholder">No image</div>')
                    .insertBefore($item.find('input[name="lcr_140"]').first());
            }
            setTimeout(reindexMpiptAttachmentInputs, 0);
          },
          hide: function(remove) {
            if (confirm('Are you sure you want to remove this item?')) {
              $(this).slideUp(remove);
              setTimeout(reindexMpiptAttachmentInputs, 0);
            }
          },
        });

        $('.steps-validation').on('change', '.mpipt-attachment-file', function () {
            var file = this.files && this.files.length ? this.files[0] : null;
            var $row = $(this).closest('[data-repeater-item]');
            var $label = $row.find('.custom-file-label').first();
            if ($label.length) {
                $label.text(file ? file.name : 'Choose file');
            }
            if (!file) {
                return;
            }

            $row.find('.attachment-photo-placeholder').remove();
            var $preview = $row.find('img.media-object').first();
            if (!$preview.length) {
                $preview = $('<img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" alt="" />');
                $row.find('input[name="lcr_140"]').first().before($preview);
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                $preview.attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        });

        $('.steps-validation').on('click', '.remove-attachment-photo', function () {
            var $row = $(this).closest('[data-repeater-item]');
            Swal.fire({
              title: 'Are You Sure ?',
              text: "This attachment image will be removed from this report row.",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#6c757d',
              confirmButtonText: 'Yes, Remove It !',
              confirmButtonClass: 'btn btn-danger',
              cancelButtonClass: 'btn btn-dark ml-1',
              cancelButtonText: 'Cancel',
              buttonsStyling: false,
            }).then(function (result) {
              if (result.value) {
                $row.find('input[type="file"][name="nmpr_50"]').val('');
                $row.find('input[name="lcr_140"]').val('');
                $row.find('.custom-file-label').text('Choose file');
                $row.find('img.media-object').remove();
                if (!$row.find('.attachment-photo-placeholder').length) {
                    $('<div class="text-muted small pt-2 attachment-photo-placeholder">No image</div>')
                        .insertBefore($row.find('input[name="lcr_140"]').first());
                }
              }
            });
        });

        reindexMpiptAttachmentInputs();
        
    </script>

@endpush
