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
                                                    <select class="form-control" id="lcr_1" name="lcr_1" required="">
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
                                                    <input disabled type="text" id="purchaseOrder" name="nwhr_2" class="form-control"
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
                                                               aria-invalid="false" disabled
                                                               style="width: 83px; float: left;">
                                                        <input type="text" id="code" name="code"
                                                               class="form-control pl-0" placeholder="." value=""
                                                               required=""
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
                                                           class="form-control" id="cliname" name="cliname" disabled>
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
                                                           disabled>
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
                        <h6 class="mb-1">Specification</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row">
                                                @foreach($specifications as $specification)
                                                    <div class="col-md-3 col-sm-12">
                                                        <fieldset>
                                                            <input type="checkbox" class="nwhr_9" name="nwhr_9"
                                                                   id="{{strtolower(str_replace(' ', '-', $specification->name))}}">
                                                            <label for="{{strtolower(str_replace(' ', '-', $specification->name))}}">{{$specification->name}}</label>
                                                        </fieldset>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <hr/>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>If Other</label>
                                                            <input type="text" id="nwhr_10" name="nwhr_10"
                                                                   class="form-control" placeholder="If Other" value=""
                                                                   data-validation--message="This field is " disabled/>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Edition</label>
                                                            <input type="text" id="nwhr_39" name="nwhr_39"
                                                                   class="form-control" placeholder="Edition" value=""
                                                                   data-validation--message="This field is "/>
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
                    </fieldset>
                    <h6>Step 2</h6>
                    <fieldset>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Acceptance Criteria</label>
                                                    <input type="text" id="nwhr_13" name="nwhr_13" class="form-control"
                                                           placeholder="Acceptance Criteria" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="skin skin-square form-group">
                                                <div class="controls">
                                                    <label class="m-0 mb-1">Test Type</label>
                                                    <div class="row input-group">
                                                        <fieldset class="col-md-6">
                                                            <input type="checkbox" class="contactway" name="contactway"
                                                                   id="hydrostatic" required>
                                                            <label for="hydrostatic">Hydrostatic</label>
                                                        </fieldset>
                                                        <fieldset class="col-md-6">
                                                            <input type="checkbox" class="contactway" name="contactway"
                                                                   id="pneumatic">
                                                            <label for="pneumatic">Pneumatic</label>
                                                        </fieldset>
                                                    </div>
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
                                                    <textarea id="nwhr_14" name="nwhr_14" class="form-control"
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
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Identification No</label>
                                                    <input type="text" id="nwhr_15" name="nwhr_15" class="form-control"
                                                           placeholder="Identification No" value=""
                                                           data-validation--message="This field is "/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Pipe Test Data</label>
                                                    <input type="text" id="nwhr_16" name="nwhr_16" class="form-control"
                                                           placeholder="Pipe Test Data" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Length of Line</label>
                                                    <input type="text" id="nwhr_17" name="nwhr_17" class="form-control"
                                                           placeholder="Length of Line" value=""
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
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Type of Pipe</label>
                                                    <input type="text" id="nwhr_18" name="nwhr_18" class="form-control"
                                                           placeholder="Type of Pipe" value=""
                                                           data-validation--message="This field is "/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Size of Pipe</label>
                                                    <input type="text" id="nwhr_19" name="nwhr_19" class="form-control"
                                                           placeholder="Size of Pipe" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
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
                        <h6 class="mb-1">Test Parameters</h6>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Test Fluid</label>
                                                    <input type="text" id="nwhr_20" name="nwhr_20" class="form-control"
                                                           placeholder="Test Fluid" value=""
                                                           data-validation--message="This field is "/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Required test Pressure</label>
                                                    <input type="text" id="nwhr_21" name="nwhr_21" class="form-control"
                                                           placeholder="Required test Pressure" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Test Fluid Temperature</label>
                                                    <input type="text" id="nwhr_22" name="nwhr_22" class="form-control"
                                                           placeholder="Test Fluid Temperature" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Test Temperature</label>
                                                    <input type="text" id="nwhr_23" name="nwhr_23" class="form-control"
                                                           placeholder="Test Temperature" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Start Time:</label>
                                                    <input type="text" id="nwhr_24" name="nwhr_24" class="form-control"
                                                           placeholder="Start Time:" value=""
                                                           data-validation--message="This field is "/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>End Time</label>
                                                    <input type="text" id="nwhr_25" name="nwhr_25" class="form-control"
                                                           placeholder="End Time" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Test duration:</label>
                                                    <input type="text" id="nwhr_26" name="nwhr_26" class="form-control"
                                                           placeholder="Test duration:" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Actual Hold Time</label>
                                                    <input type="text" id="nwhr_27" name="nwhr_27" class="form-control"
                                                           placeholder="Actual Hold Time" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Test Equipment / Pressure Range</h6>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Pressure Range</label>
                                                    <input type="text" id="nwhr_28" name="nwhr_28" class="form-control"
                                                           placeholder="Pressure Range" value=""
                                                           data-validation--message="This field is "/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Type</label>
                                                    <input type="text" id="nwhr_29" name="nwhr_29" class="form-control"
                                                           placeholder="Type" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Actual Test</label>
                                                    <input type="text" id="nwhr_30" name="nwhr_30" class="form-control"
                                                           placeholder="Actual Test" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Calibration Due Date</label>
                                                    <input type="text" id="nwhr_31" name="nwhr_31" class="form-control"
                                                           placeholder="Calibration Due Date" value=""
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Test Pressure Result</h6>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Pressure Test Result</label>
                                                    <div class="row input-group">
                                                        <div class="col-md-6 custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="nwhr_32_y" name="nwhr_32" required>
                                                            <label class="custom-control-label"
                                                                   for="nwhr_32_y">Pass</label>
                                                        </div>
                                                        <div class="col-md-6 custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="nwhr_32_n" name="nwhr_32">
                                                            <label class="custom-control-label"
                                                                   for="nwhr_32_n">Fail</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Line Loss</label>
                                                    <div class="row input-group">
                                                        <div class="col-md-6 custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="nwhr_33_y" name="nwhr_33" required>
                                                            <label class="custom-control-label"
                                                                   for="nwhr_33_y">Yes</label>
                                                        </div>
                                                        <div class="col-md-6 custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   id="nwhr_33_n" name="nwhr_33">
                                                            <label class="custom-control-label"
                                                                   for="nwhr_33_n">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Amount Loss</label>
                                                    <input type="text" id="nwhr_34" name="nwhr_34" class="form-control"
                                                           placeholder="Amount Loss" value=""
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
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Reason of Line loss</label>
                                                    <textarea id="nwhr_35" name="nwhr_35" class="form-control"
                                                              placeholder="Reason of Line loss"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Sketch</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="nwhr_36" class="custom-file-input"
                                                               id="nwhr_36">
                                                        <label class="custom-file-label" style="height: 5.8rem;"
                                                               for="nwhr_36" aria-describedby="inputGroupFile02">Choose
                                                            file</label>
                                                    </div>
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
                                                    <label>Corrective Measures Taken</label>
                                                    <textarea id="nwhr_37" name="nwhr_37" class="form-control"
                                                              placeholder="Corrective Measures Taken"></textarea>
                                                </div>
                                            </div>
                                        </div>
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
                                            <!-- <textarea id="nwhr_38" name="nwhr_38" class="form-control" placeholder="Final Conclusion"></textarea> -->
                                            <div class="row">

                                                <div class="col-8">
                                                    <p>Final result : Accept / Reject ?</p>
                                                </div>
                                                <div class="col-2">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" id="nwhr_38_y "
                                                               name="nwhr_38" required>
                                                        <label class="custom-control-label"
                                                               for="nwhr_38_y ">Accept</label>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" id="nwhr_38_n"
                                                               name="nwhr_38">
                                                        <label class="custom-control-label"
                                                               for="nwhr_38_n">Reject</label>
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
      var form1 = $('.wizard')[0];
      var formdata = new FormData(form1);
      var checkbox = $(".wizard").find("input[type=radio]");
      formdata.append('code', $('#code').val());
      formdata.append('nwhr_2', $('#purchaseOrder').val());
      formdata.append('lcr_7', $('#lcr_7').val());
      formdata.append('nwhr_9', JSON.stringify(nwhr_9));
      formdata.append('contactway', JSON.stringify(contactway));
      $.each(checkbox, function (key, val) {
        if ($(this).is(':checked') === true) {
          formdata.append($(this).attr('name'), $(this).attr('id'));
        }
      });
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: "{{route('witnessHydro.store')}}",
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
              window.location.replace("{{route('witnessHydro.index')}}");
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
      var nwhr_9 = [];
      var contactway = [];

      $('.contactway').on('ifChecked', function (event) {
        var id = this.id;
        contactway.push(id);
      });
      $('.contactway').on('ifUnchecked', function (event) {
        var id = this.id;
        index = contactway.indexOf(id);
        contactway.splice(index, 1);
      });

      $('.steps-validation').on('ifChecked', '.nwhr_9', function () {
        var id = this.id;
        if (id === 'other') {
          $('#nwhr_10').prop("disabled", false);
        }
        nwhr_9.push(id);
      });

      $('.steps-validation').on('ifUnchecked', '.nwhr_9', function (event) {
        var id = this.id;
        if (id === 'other') {
          $('#nwhr_10').prop("disabled", true).val('');
        }
        index1 = nwhr_9.indexOf(id);
        nwhr_9.splice(index1, 1);
      });
    </script>
@endpush
