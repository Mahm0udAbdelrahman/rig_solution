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
                                                    <select class="form-control" id="lcr_1" name="lcr_1" required=""
                                                            disabled>
                                                        <option value="{{$high3Pressure->job_request->id}}"
                                                                selected>{{$high3Pressure->job_request->code}}</option>
                                                    </select>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Purchase Order</label>
                                                    <input disabled type="text" id="purchaseOrder" name="nh2pr_2" class="form-control"
                                                           placeholder="Purchase Order"
                                                           value="{{$high3Pressure->nh2pr_2}}">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Report No.</label>
                                                    <div class="clearfix">
                                                        <input type="text" id="precode" class="form-control pr-0"
                                                               placeholder="Report No"
                                                               value="{{$high3Pressure->job_request->code}} /"
                                                               required=""
                                                               data-validation-required-message="This code field is required"
                                                               aria-invalid="false" disabled
                                                               style="width: 83px; float: left;">
                                                        <input type="text" id="code" name="code"
                                                               class="form-control pl-0" placeholder="."
                                                               value="{{$high3Pressure->code}}" required=""
                                                               data-validation-required-message="This code field is required"
                                                               aria-invalid="false" disabled
                                                               style="width: auto; float: left;">
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
                                                    <input type="text"
                                                           placeholder="Name of employer for whom the examination was made"
                                                           class="form-control" id="cliname" name="cliname" disabled/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Address of premises at examination was made</label>
                                                    <input type="text" id="cliloc" name="cliloc" class="form-control"
                                                           value=""
                                                           placeholder="Address of premises at examination was made"
                                                           disabled/>
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
                                                    <label>Examination Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="ft-calendar"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control datepicker-default"
                                                               id="lcr_6" name="lcr_6" placeholder="Examination Date"
                                                               required="" value="{{$high3Pressure->nh2pr_6}}"
                                                               data-validation-required-message="This field is required"/>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Next Examination Date / <span id="months">6</span>
                                                        Months</label>
                                                    <div style="display: inline-block; float: right;">
                                                        <input type="checkbox" class="switchery mr-1" id="suporcli"
                                                               name="suporcli" checked>
                                                    </div>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="ft-calendar"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control dp-date-range-to"
                                                               id="lcr_7" name="lcr_7"
                                                               placeholder="Next Examination Date" required=""
                                                               data-validation-required-message="This field is required"
                                                               value="{{$high3Pressure->nh2pr_7}}" disabled/>
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
                                                    <label>Work location</label>
                                                    <input type="text" id="deploc" name="deploc" class="form-control"
                                                           placeholder="Work location" value="" required=""
                                                           data-validation-required-message="This field is required"
                                                           disabled/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="col-12 col-sm-6">--}}
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
						<div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <h6 class="mb-1">Specification</h6>
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row">
                                                @foreach($specifications as $specification)
                                                    <div class="col-md-3 col-sm-12">
                                                        <fieldset>
                                                            <input type="checkbox" class="nh2pr_11" name="nh2pr_11"
                                                                   id="{{strtolower(str_replace(' ', '-', $specification->name))}}"
                                                                   @if(in_array(strtolower(str_replace(' ', '-', $specification->name)), json_decode($high3Pressure->nh2pr_11))) checked @endif
                                                                   @if(strtolower(str_replace(' ', '-', $specification->name))== 'other' && $high3Pressure->nh2pr_12) checked @endif
                                                            >
                                                            <label for="{{strtolower(str_replace(' ', '-', $specification->name))}}">{{$specification->name}}</label>
                                                        </fieldset>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- <hr/> -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group" id="other_specification_section" style="display: none;">
                                                        <div class="controls">
                                                            <label>If Other</label>
                                                            <input type="text" id="nh2pr_12" name="nh2pr_12"
                                                                   class="form-control" placeholder="If Other"
                                                                   value="{{$high3Pressure->nh2pr_12}}"
                                                                   data-validation--message="This field is "/>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
										
                                        </div>
                                    </div>
									<hr>
									<div class="row">
										<div class="col-12 col-sm-6">
											<div class="form-group mb-0">
												<div class="controls">
													<label>Edition</label>
													<input type="text" id="edition" name="edition" class="form-control"
														placeholder="Edition" value="{{$high3Pressure->edition}}" required=""
														data-validation-required-message="This field is required"
													/>
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
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Description</label>
                                                    <textarea id="nh2pr_9" name="nh2pr_9" class="form-control"
                                                              placeholder="Description">{{$high3Pressure->desc}}</textarea>
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
                                                    <input type="text" id="nh2pr_10" name="nh2pr_10" class="form-control"
                                                           placeholder="Identification No"
                                                           value="{{$high3Pressure->nh2pr_10}}"
                                                           data-validation--message="This field is "/>
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
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Acceptance Criteria</label>
                                                    <input type="text" id="nh2pr_13" name="nh2pr_13" class="form-control"
                                                           placeholder="Acceptance Criteria"
                                                           value="{{$high3Pressure->acceptance}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Drawing Number</label>
                                                    <input type="text" id="nh2pr_14" name="nh2pr_14" class="form-control"
                                                           placeholder="Drawing Number"
                                                           value="{{$high3Pressure->nh2pr_14}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Material</label>
                                                    <input type="text" id="nh2pr_15" name="nh2pr_15" class="form-control"
                                                           placeholder="Material" value="{{$high3Pressure->nh2pr_15}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Material Thickness</label>
                                                    <input type="text" id="nh2pr_16" name="nh2pr_16" class="form-control"
                                                           placeholder="Material Thickness"
                                                           value="{{$high3Pressure->nh2pr_16}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Surface Condition</label>
                                                    <input type="text" id="nh2pr_17" name="nh2pr_17" class="form-control"
                                                           placeholder="Surface Condition"
                                                           value="{{$high3Pressure->nh2pr_17}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Surface Temperature</label>
                                                    <input type="text" id="nh2pr_18" name="nh2pr_18" class="form-control"
                                                           placeholder="Surface Temperature"
                                                           value="{{$high3Pressure->nh2pr_18}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6>Equipment instrument</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Model</label>
                                                    <input type="text" id="nh2pr_19" name="nh2pr_19" class="form-control"
                                                           placeholder="Model" value="{{$high3Pressure->nh2pr_19}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Serial Number</label>
                                                    <input type="text" id="nh2pr_20" name="nh2pr_20" class="form-control"
                                                           placeholder="Serial Number"
                                                           value="{{$high3Pressure->nh2pr_20}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Manufacturer</label>
                                                    <input type="text" id="nh2pr_21" name="nh2pr_21" class="form-control"
                                                           placeholder="Manufacturer"
                                                           value="{{$high3Pressure->nh2pr_21}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Coupling Type</label>
                                                    <input type="text" id="nh2pr_22" name="nh2pr_22" class="form-control"
                                                           placeholder="Coupling Type"
                                                           value="{{$high3Pressure->nh2pr_22}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Sound Velocity</label>
                                                    <input type="text" id="nh2pr_23" name="nh2pr_23" class="form-control"
                                                           placeholder="Sound Velocity"
                                                           value="{{$high3Pressure->nh2pr_23}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Search Unit</label>
                                                    <input type="text" id="nh2pr_24" name="nh2pr_24" class="form-control"
                                                           placeholder="Search Unit" value="{{$high3Pressure->nh2pr_24}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Probe Frequency</label>
                                                    <input type="text" id="nh2pr_25" name="nh2pr_25" class="form-control"
                                                           placeholder="Probe Frequency"
                                                           value="{{$high3Pressure->nh2pr_25}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Probe Dia.</label>
                                                    <input type="text" id="nh2pr_26" name="nh2pr_26" class="form-control"
                                                           placeholder="Probe Dia." value="{{$high3Pressure->nh2pr_26}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Calibration Block</label>
                                                    <input type="text" id="nh2pr_27" name="nh2pr_27" class="form-control"
                                                           placeholder="Search Unit" value="{{$high3Pressure->nh2pr_27}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Calibration Due</label>
                                                    <input type="text" id="nh2pr_30" name="nh2pr_30" class="form-control"
                                                           placeholder="Calibration Due"
                                                           value="{{$high3Pressure->nh2pr_30}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>


                       <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">

                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Calibration Sheet Attached:</label>
                                                    <div class="row input-group">
                                                        <div class="col-md-5 custom-control custom-radio ml-2">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="nh2pr_28_y" name="nh2pr_28"
                                                                   {{$high3Pressure->checkbox_yes($high3Pressure->nh2pr_28)}} required>
                                                            <label class="custom-control-label"
                                                                   for="nh2pr_28_y">Yes</label>
                                                        </div>
                                                        <div class="col-md-5 custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="nh2pr_28_n"
                                                                   name="nh2pr_28" {{$high3Pressure->checkbox_no($high3Pressure->nh2pr_28)}}>
                                                            <label class="custom-control-label"
                                                                   for="nh2pr_28_n">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Sketch Attached</label>
                                                    <div class="row input-group">
                                                        <div class="col-md-5 custom-control custom-radio ml-2">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="nh2pr_29_y" name="nh2pr_29"
                                                                   {{$high3Pressure->checkbox_yes($high3Pressure->nh2pr_29)}} required>
                                                            <label class="custom-control-label"
                                                                   for="nh2pr_29_y">Yes</label>
                                                        </div>
                                                        <div class="col-md-5 custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="nh2pr_29_n"
                                                                   name="nh2pr_29" {{$high3Pressure->checkbox_no($high3Pressure->nh2pr_29)}}>
                                                            <label class="custom-control-label"
                                                                   for="nh2pr_29_n">No</label>
                                                        </div>
                                                    </div>
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
                        <div class="card repeater">
                            <div class="card-content collapse show">
                                <div class="card-body" data-repeater-list="payments">
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>Point</label>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>Point Description</label>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>Line Size (I.D)</label>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>( 0°)</label>
                                            </div>
                                        </div>

                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>( 90°)</label>
                                            </div>
                                        </div>

                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>( 180°)</label>
                                            </div>
                                        </div>

                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>( 270°)</label>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>Minimum Reading</label>
                                            </div>
                                        </div>

                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>Minimum Allowable Thickness</label>
                                            </div>
                                        </div>

                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>DIMINUATION</label>
                                            </div>
                                        </div>

                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>% OF LOSS </label>
                                            </div>
                                        </div>

                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>Remarks</label>
                                            </div>
                                        </div>
                                        <div class="col-1"></div>
                                    </div>
                                    @foreach(json_decode($high3Pressure->nh2pr_31) as $value)
                                        <div class="row repeater-item" data-repeater-item>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_433" name="ntir_433" class="form-control"
                                                                  placeholder="Point Description">{{$value->ntir_433}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_434" name="ntir_434" class="form-control"
                                                                  placeholder="Line Size">{{$value->ntir_434}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_435" name="ntir_435" class="form-control"
                                                                  placeholder="Minimum Wall Thickness">{{$value->ntir_435}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_436" name="ntir_436" class="form-control update-function"
                                                                  placeholder="( 0°)">{{$value->ntir_436}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_437" name="ntir_437" class="form-control update-function"
                                                                  placeholder="( 90°)">{{$value->ntir_437}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_438" name="ntir_438" class="form-control update-function"
                                                                  placeholder="( 180°)">{{$value->ntir_438}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_439" name="ntir_439" class="form-control update-function"
                                                                  placeholder="( 270°)">{{$value->ntir_439}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_440" name="ntir_440" class="form-control"
                                                                  placeholder="" disabled>{{$value->ntir_440}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_441" name="ntir_441" class="form-control update-function"
                                                                  placeholder="">{{$value->ntir_441}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_442" name="ntir_442" class="form-control"
                                                                  placeholder="" disabled>{{$value->ntir_442}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_443" name="ntir_443" class="form-control"
                                                                  placeholder="" disabled>{{$value->ntir_443}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_444" name="ntir_444" class="form-control"
                                                                  placeholder="Remarks">{{$value->ntir_444}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            {{--</div>--}}


                                            {{--<div class=" col-1">--}}
                                                <button type="button" class="btn btn-danger" data-repeater-delete><i
                                                            class="ft-x"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class=" form-group overflow-hidden">
                                    <div class="col-12">
                                        <button type="button" data-repeater-create class="btn btn-primary"><i
                                                    class="ft-plus"></i> Add
                                        </button>
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
                                                    <label>Final Conclusion:</label>
                                                    <textarea id="nh2pr_31" name="nh2pr_31" class="form-control"
                                                              placeholder="Final Conclusion">{{$high3Pressure->nh2pr_32}}</textarea>
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
      // nmpr_12.push({
      // id:'nmpr_34',
      // value: nmpr_34
      // });
      var form1 = $('.wizard')[0];
      var formdata = new FormData(form1);
      var checkbox = $(".wizard").find("input[type=radio]");
      formdata.append('lcr_1', $('#lcr_1').val());
      formdata.append('code', $('#code').val());
      formdata.append('lcr_7', $('#lcr_7').val());
      formdata.append('nh2pr_2', $('#purchaseOrder').val());
      formdata.append('nh2pr_11', JSON.stringify(nh2pr_11));
      // formdata.append('nur_102', JSON.stringify(nur_102));
      $.each(checkbox, function (key, val) {
        if ($(this).is(':checked') === true) {
          formdata.append($(this).attr('name'), $(this).attr('id'));
        }
      });
      form.find(':input').each(function () {
        var $this = $(this);
        var name = $this.attr('name');
        var value = $this.val();

        // Check if the input is disabled and matches the pattern
        if ($this.is(':disabled') && name && name.match(/^payments\[\d+\]\[ntir_\d+\]$/)) {
          formdata.append(name, value);
        }
      });
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: "{{route('high3Pressure.update', $high3Pressure->id)}}",
        processData: false,
        contentType: false,
        cache: false,
        data: formdata,
        dataType: "JSON",
        beforeSend: function () {
          $('#submit i').addClass('la la-refresh spinner');
        },
        success: function (data) {
          toastr.info('Good Job !', data.success, {
            positionClass: 'toast-bottom-left',
            "showMethod": "slideDown",
            "hideMethod": "slideUp",
            "progressBar": true,
            timeOut: 1000,
            fadeOut: 1000,
            onHidden: function () {
              window.location.replace("{{route('high3Pressure.index')}}");
            }
          });
        },
      });
    }
  });

  $(document).ready(function() {
    // Function to update values based on inputs
    function updateValues(repeater) {
      console.log('update function trigger', repeater);
      // Select inputs within the specific repeater instance
      const inputs = repeater.find('textarea[id^="ntir_436"], textarea[id^="ntir_437"], textarea[id^="ntir_438"], textarea[id^="ntir_439"]');
      const ntir_440 = repeater.find('#ntir_440');
      const ntir_441 = repeater.find('#ntir_441');
      const ntir_442 = repeater.find('#ntir_442');
      const ntir_443 = repeater.find('#ntir_443');

      // Get values from inputs
      const values = inputs.map(function() {
        return parseFloat($(this).val()) || 0;
      }).get();
      const [ntir_436, ntir_437, ntir_438, ntir_439] = values;

      // Update ntir_440 (minimum of the first four inputs)
      ntir_440.val(Math.min(ntir_436, ntir_437, ntir_438, ntir_439));

      // Update ntir_442 (Input 5 - Input 6)
      const input_5 = parseFloat(ntir_440.val()) || 0;
      const input_6 = parseFloat(ntir_441.val()) || 0;
	  const ntir_442_result = input_5 - input_6;
      ntir_442.val(parseFloat(ntir_442_result.toFixed(4)));

      // Update ntir_443 (Input 7 ÷ Input 6 x 100 as percentage)
      const input_7 = parseFloat(ntir_442.val()) || 0;
	  const ntir_443_result = input_6 !== 0 ? (input_7 / input_6) * 100 : 0;
      ntir_443.val(parseFloat(ntir_443_result.toFixed(4)));
    }

    // Attach event listeners to repeater elements
    $(document).on('change', '.update-function', function(e) {
      const $repeater = $(e.target).closest('.repeater-item');
      console.log($repeater);
      updateValues($repeater);
    });

    // Optionally, initialize values for existing repeater items
    $('.repeater-item').each(function() {
      updateValues($(this));
    });
  });
</script>
@endprepend

@extends('layouts.scripts.reportsforms')

@push('bottom-child-scripts')
    <script>
      get_report_data_for_update($('#lcr_1 option:selected').val(), $('#lcr_1 option:selected').text());
      var nh2pr_11 = [];
      /**************************************************************/
      $.each($('.nh2pr_11'), function (value) {
        console.log('>>>',value, $(this).is(':checked'));
        if ($(this).is(':checked')) {
          var id = this.id;
          if (id === 'other') {
            $('#other_specification_section').show();
            $('#nh2pr_12').prop("disabled", false);
          }
          nh2pr_11.push(id);
        }
      });
      $('.steps-validation').on('ifChecked', '.nh2pr_11', function () {
        var id = this.id;
        if (id === 'other') {
          $('#nh2pr_12').prop("disabled", false);
          $('#other_specification_section').show();
        }
        nh2pr_11.push(id);
      });
      $('.steps-validation').on('ifUnchecked', '.nh2pr_11', function (event) {
        var id = this.id;
        if (id === 'other') {
          $('#nh2pr_12').prop("disabled", true).val('');
          $('#other_specification_section').show();
        }
        index1 = nh2pr_11.indexOf(id);
        nh2pr_11.splice(index1, 1);
      });

      $('.repeater').repeater({
        show: function () {
          $(this).slideDown();
          // $(this).find('img').attr('src', '');
        },
        hide: function (remove) {
          if (confirm('Are you sure you want to remove this item?')) {
            $(this).slideUp(remove);
          }
        },
      });
    </script>
@endpush
