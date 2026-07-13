@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
    <section id="validation">
        <div class="row">
            <div class="col-12">
                <form action="#" class="steps-validation wizard-circle" enctype="multipart/form-data">
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
                                                    <select class="form-control" id="lcr_1" name="lcr_1">
                                                        <option value="">Select Value</option>
                                                        @foreach($jobrequests as $jobrequest)
                                                            <option value="{{$jobrequest->id}}">{{$jobrequest->code}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Purchase Order</label>
                                                    <input disabled type="text" id="purchaseOrder" name="nhpr_2" class="form-control"
                                                           placeholder="Purchase Order" value="">
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
                                                               placeholder="Report No" value="" required=""
                                                               data-validation-required-message="This code field is required"
                                                               aria-invalid="false" style="width: 83px; float: left;"
                                                               disabled/>
                                                        <input type="text" id="code" name="code"
                                                               class="form-control pl-0" placeholder="." value=""
                                                               required=""
                                                               data-validation-required-message="This code field is required"
                                                               aria-invalid="false" style="width: auto; float: left;"
                                                               disabled/>
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
                                                               required=""
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
                                                               disabled/>
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
                                                            <input type="checkbox" class="nhpr_11" name="nhpr_11"
                                                                   id="{{strtolower(str_replace(' ', '-', $specification->name))}}">
                                                            <label for="{{strtolower(str_replace(' ', '-', $specification->name))}}">{{$specification->name}}</label>
                                                        </fieldset>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- <hr/> -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group" id="other_specification_section">
                                                        <div class="controls">
                                                            <label>If Other</label>
                                                            <input type="text" id="nhpr_12" name="nhpr_12"
                                                                   class="form-control" placeholder="If Other" value=""
                                                                   data-validation--message="This field is "/>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<hr />
									<div class="row">
										<div class="col-12 col-sm-6">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Edition</label>
                                                    <input type="text" id="edition" name="edition" class="form-control"
                                                           placeholder="Edition" value="" required=""
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
                                                    <textarea id="nhpr_9" name="nhpr_9" class="form-control"
                                                              placeholder="Description"></textarea>
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
                                                    <input type="text" id="nhpr_10" name="nhpr_10" class="form-control"
                                                           placeholder="Identification No" value=""
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
                                                    <input type="text" id="nhpr_13" name="nhpr_13" class="form-control"
                                                           placeholder="Acceptance Criteria" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Drawing Number</label>
                                                    <input type="text" id="nhpr_14" name="nhpr_14" class="form-control"
                                                           placeholder="Drawing Number" value=""
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
                                                    <input type="text" id="nhpr_15" name="nhpr_15" class="form-control"
                                                           placeholder="Material" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Material Thickness</label>
                                                    <input type="text" id="nhpr_16" name="nhpr_16" class="form-control"
                                                           placeholder="Material Thickness" value=""
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
                                                    <input type="text" id="nhpr_17" name="nhpr_17" class="form-control"
                                                           placeholder="Surface Condition" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Surface Temperature</label>
                                                    <input type="text" id="nhpr_18" name="nhpr_18" class="form-control"
                                                           placeholder="Surface Temperature" value=""
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
                                                    <input type="text" id="nhpr_19" name="nhpr_19" class="form-control"
                                                           placeholder="Model" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Serial Number</label>
                                                    <input type="text" id="nhpr_20" name="nhpr_20" class="form-control"
                                                           placeholder="Serial Number" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Manufacturer</label>
                                                    <input type="text" id="nhpr_21" name="nhpr_21" class="form-control"
                                                           placeholder="Manufacturer" value=""
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
                                                    <input type="text" id="nhpr_22" name="nhpr_22" class="form-control"
                                                           placeholder="Coupling Type" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Sound Velocity</label>
                                                    <input type="text" id="nhpr_23" name="nhpr_23" class="form-control"
                                                           placeholder="Sound Velocity" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Search Unit</label>
                                                    <input type="text" id="nhpr_24" name="nhpr_24" class="form-control"
                                                           placeholder="Search Unit" value=""
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
                                                    <input type="text" id="nhpr_25" name="nhpr_25" class="form-control"
                                                           placeholder="Probe Frequency" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Probe Dia.</label>
                                                    <input type="text" id="nhpr_26" name="nhpr_26" class="form-control"
                                                           placeholder="Probe Dia." value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Calibration Block</label>
                                                    <input type="text" id="nhpr_27" name="nhpr_27" class="form-control"
                                                           placeholder="Search Unit" value=""
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
                                                    <input type="text" id="nhpr_30" name="nhpr_30" class="form-control"
                                                           placeholder="Calibration Due" value=""
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
                                                                   id="nh2pr_28_y" name="nh2pr_28" required>
                                                            <label class="custom-control-label"
                                                                   for="nh2pr_28_y">Yes</label>
                                                        </div>
                                                        <div class="col-md-5 custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="nh2pr_28_y" name="nh2pr_28">
                                                            <label class="custom-control-label"
                                                                   for="nh2pr_28_y">No</label>
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
                                                                   id="nh2pr_29_y" name="nh2pr_29" required>
                                                            <label class="custom-control-label"
                                                                   for="nh2pr_29_y">Yes</label>
                                                        </div>
                                                        <div class="col-md-5 custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="nh2pr_29_n" name="nh2pr_29">
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
                                        {{--<div class="col-1"></div>--}}
                                    </div>
                                    <div class="row repeater-item" data-repeater-item>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <textarea id="ntir_433" name="ntir_433" class="form-control"
                                                              placeholder="Point Description"></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <textarea id="ntir_434" name="ntir_434" class="form-control"
                                                              placeholder="Line Size"></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <textarea id="ntir_435" name="ntir_435" class="form-control"
                                                              placeholder="Minimum Wall Thickness"></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <textarea id="ntir_436" name="ntir_436" class="form-control update-function"
                                                              placeholder="( 0°)"></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <textarea id="ntir_437" name="ntir_437" class="form-control  update-function"
                                                              placeholder="( 90°)"></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <textarea id="ntir_438" name="ntir_438" class="form-control  update-function"
                                                              placeholder="( 180°)"></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <textarea id="ntir_439" name="ntir_439" class="form-control  update-function"
                                                              placeholder="( 270°)"></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <textarea id="ntir_440" name="ntir_440" class="form-control"
                                                              placeholder="Result" disabled></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <textarea id="ntir_441" name="ntir_441" class="form-control update-function"
                                                              placeholder="Remarks"></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <textarea id="ntir_442" name="ntir_442" class="form-control"
                                                              placeholder="Remarks" disabled></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <textarea id="ntir_443" name="ntir_443" class="form-control"
                                                              placeholder="Remarks" disabled></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <textarea id="ntir_444" name="ntir_444" class="form-control"
                                                              placeholder="Remarks"></textarea>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        {{--</div>--}}


                                        {{--<div class=" col-1">--}}
                                            <button type="button" class="btn btn-danger" data-repeater-delete><i
                                                        class="ft-x"></i></button>
                                        </div>
                                    </div>
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
                                                    <textarea id="nhpr_31" name="nhpr_31" class="form-control"
                                                              placeholder="Final Conclusion"></textarea>
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
{{--<script src="{{asset('app-assets/js/scripts/forms/form-repeater.js')}}"></script>--}}
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
      formdata.append('code', $('#code').val());
      formdata.append('lcr_7', $('#lcr_7').val());
      formdata.append('nhpr_2', $('#purchaseOrder').val());
      formdata.append('nhpr_11', JSON.stringify(nhpr_11));
      // formdata.append('nur_8', JSON.stringify(nur_8));
      // formdata.append('nur_102', JSON.stringify(nur_102));
      // Append disabled inputs manually if they match the specific pattern
      form.find(':input').each(function () {
        var $this = $(this);
        var name = $this.attr('name');
        var value = $this.val();

        // Check if the input is disabled and matches the pattern
        if ($this.is(':disabled') && name && name.match(/^payments\[\d+\]\[ntir_\d+\]$/)) {
          formdata.append(name, value);
        }
      });
      $.each(checkbox, function (key, val) {
        if ($(this).is(':checked') === true) {
          formdata.append($(this).attr('name'), $(this).attr('id'));
        }
      });
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: "{{route('high3Pressure.store')}}",
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
    // $('.repeater-item').each(function() {
    //   updateValues($(this));
    // });
  });
</script>
@endprepend

@extends('layouts.scripts.reportsforms')

@push('bottom-child-scripts')
    <script>
      $('#nhpr_12').prop("disabled", true).val('');
      $('#other_specification_section').hide();
      var nhpr_11 = [];
      /**************************************************************/
      $('.steps-validation').on('ifChecked', '.nhpr_11', function () {
        var id = this.id;
        if (id === 'other') {
          $('#nhpr_12').prop("disabled", false);
          $('#other_specification_section').show();
        }
        nhpr_11.push(id);
      });
      $('.steps-validation').on('ifUnchecked', '.nhpr_11', function (event) {
        var id = this.id;
        if (id === 'other') {
          $('#nhpr_12').prop("disabled", true).val('');
          $('#other_specification_section').hide();
        };
        index1 = nhpr_11.indexOf(id);
        nhpr_11.splice(index1, 1);
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
