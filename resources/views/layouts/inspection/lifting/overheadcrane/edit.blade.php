@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
    <section id="validation">
        <div class="row">
            <div class="col-12">
                <form action="#" class="steps-validation wizard-circle">
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
																												<option value="{{$overheadcrane->job_request->id}}" selected>{{$overheadcrane->job_request->code}}</option>
																										</select>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Purchase Order</label>
                                                    <input type="text" id="purchaseOrder" name="locr_2" class="form-control" placeholder="Purchase Order" value="{{$overheadcrane->locr_2}}" required="" disabled>
                                                <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Report No.</label>
                                                  <div class="clearfix">
                                                      <input type="text" id="precode" class="form-control pr-0" placeholder="Report No" value="{{$overheadcrane->job_request->code}} /" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled style="width: 83px; float: left;">
                                                      <input type="text" id="code" name="code" class="form-control pl-0" placeholder="." value="{{$overheadcrane->code}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled  style="width: auto; float: left;">
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
                                                          <input type="text" class="form-control datepicker-default" id="lcr_6" name="lcr_6" placeholder="Examination Date"required="" data-validation-required-message="This field is required" value="{{$overheadcrane->locr_6}}" />
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Next Examination Date / <span id="months">6</span> Months</label>
                                                    <div style="display: inline-block; float: right;">
                                                        <input type="checkbox" class="switchery mr-1" id="suporcli" name="suporcli" checked>
                                                    </div>
                                                    <div class="input-group">
                                                          <div class="input-group-prepend">
                                                              <span class="input-group-text"><i class="ft-calendar"></i></span>
                                                          </div>
                                                          <input type="text" class="form-control dp-date-range-to" id="lcr_7" name="lcr_7" placeholder="Next Examination Date"required="" data-validation-required-message="This field is required" value="{{$overheadcrane->locr_7}}" disabled/>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Color Code</label>
                                                    <input type="text" id="locr_8" name="locr_8" class="form-control" placeholder="Color Code" value="{{$overheadcrane->locr_8}}"required="" data-validation-required-message="This field is required"/>
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
                                        <div class="col-6">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Work location</label>
                                                    <input type="text" id="deploc" name="deploc" class="form-control" placeholder="Work location" value="" required="" data-validation-required-message="This field is required" disabled />
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="col-6">--}}
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
                    </fieldset>
                    <h6>Step 2</h6>
                    <fieldset>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Type of Crane and nature of Power</label>
                                                    <input type="text" id="locr_10" name="locr_10" class="form-control" placeholder="Type of Crane and nature of Power" value="{{$overheadcrane->locr_10}}"  >
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Name of Manufacturer</label>
                                                    <input type="text" id="locr_11" name="locr_11" class="form-control" placeholder="Name of Manufacturer" value="{{$overheadcrane->locr_11}}" >
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Identification Number/part number</label>
                                                    <input type="text" id="locr_12" name="locr_12" class="form-control" placeholder="Identification Number/part number" value="{{$overheadcrane->locr_12}}"  >
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
                                                    <label>Model/Type</label>
                                                    <input type="text" id="locr_13" name="locr_13" class="form-control" placeholder="Model/Type" value="{{$overheadcrane->locr_13}}" />
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Date of Manufacturer (if known)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="ft-calendar"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control dp-date-range-from" id="locr_14" name="locr_14" placeholder="Date of Manufacturer (if known)" value="{{$overheadcrane->locr_14}}" />
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Crane Capacity (SWL)</label>
                                                    <input type="text" id="locr_15" name="locr_15" class="form-control" placeholder="Crane Capacity (SWL)" value="{{$overheadcrane->locr_15}}"  >
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
                                                    <label>Make and type of automatic safe load indicator(SLI)</label>
                                                    <input type="text" id="locr_16" name="locr_16" class="form-control" placeholder="Make and type of automatic safe load indicator(SLI)" value="{{$overheadcrane->locr_16}}" />
                                                <div class="help-block"></div></div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>SLI Identification number(if fitted)</label>
                                                    <input type="text" id="locr_17" name="locr_17" class="form-control" placeholder="SLI Identification number(if fitted)" value="{{$overheadcrane->locr_17}}" />
                                                <div class="help-block"></div></div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Number of falls/lines</label>
                                                    <input type="text" id="locr_18" name="locr_18" class="form-control" placeholder="Number of falls/lines" value="{{$overheadcrane->locr_18}}" />
                                                <div class="help-block"></div></div>
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
                                                    <label>Last Examination Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="ft-calendar"></i></span>
                                                        </div>
                                                        <input type="text" id="locr_19" name="locr_19" class="form-control dp-date-range-from" placeholder="Last Examination Date" value="{{$overheadcrane->locr_19}}" />
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Certificate Number</label>
                                                    <input type="text" id="locr_20" name="locr_20" class="form-control" placeholder="Certificate Number" value="{{$overheadcrane->locr_20}}" >
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Examined by</label>
                                                    <input type="text" id="locr_21" name="locr_21" class="form-control" placeholder="Examined by" value="{{$overheadcrane->locr_21}}" >
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
                                                    <label>Date of last Load Test</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="ft-calendar"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control dp-date-range-from" id="locr_22" name="locr_22" placeholder="Date of last Load Test" value="{{$overheadcrane->locr_22}}" />
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Certificate Number</label>
                                                    <input type="text" id="locr_23" name="locr_23" class="form-control" placeholder="Certificate Number" value="{{$overheadcrane->locr_23}}" >
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Tested by</label>
                                                    <input type="text" id="locr_24" name="locr_24" class="form-control" placeholder="Tested by" value="{{$overheadcrane->locr_24}}"  >
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
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Reference Standard</label>
                                                    <input type="text" id="locr_25" name="locr_25" class="form-control" placeholder="Reference Standard" value="{{$overheadcrane->locr_25}}" />
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
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row mt-1">
                                        <div class="col-8">
                                            <p>- Is this the first examination after installation or assembly at a new site or location?</p>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_26_y " name="locr_26" {{$overheadcrane->checkbox_yes($overheadcrane->locr_26)}}>
                                                <label class="custom-control-label" for="locr_26_y ">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_26_n" name="locr_26" {{$overheadcrane->checkbox_no($overheadcrane->locr_26)}}>
                                                <label class="custom-control-label" for="locr_26_n">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <p>- If the answer to the above question is YES Has the equipment been installed correctly?</p>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_27_y " name="locr_27" {{$overheadcrane->checkbox_yes($overheadcrane->locr_27)}}>
                                                <label class="custom-control-label" for="locr_27_y ">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_27_n" name="locr_27" {{$overheadcrane->checkbox_no($overheadcrane->locr_27)}}>
                                                <label class="custom-control-label" for="locr_27_n">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="carried" class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row mt-1">
                                        <div class="col-8">
                                            <p>- Within an interval of 6 months?</p>
                                        </div>
                                      <div class="col-2">
                                          <div class="custom-control custom-radio">
                                              <input type="radio" class="custom-control-input" id="locr_28_y " name="locr_28" {{$overheadcrane->checkbox_yes($overheadcrane->locr_28)}}>
                                              <label class="custom-control-label" for="locr_28_y ">Yes</label>
                                          </div>
                                      </div>
                                      <div class="col-2">
                                          <div class="custom-control custom-radio">
                                              <input type="radio" class="custom-control-input" id="locr_28_n" name="locr_28" {{$overheadcrane->checkbox_no($overheadcrane->locr_28)}}>
                                              <label class="custom-control-label" for="locr_28_n">No</label>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <p>- Within an interval of 12 months?</p>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_29_y " name="locr_29" {{$overheadcrane->checkbox_yes($overheadcrane->locr_29)}}>
                                                <label class="custom-control-label" for="locr_29_y ">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_29_n" name="locr_29" {{$overheadcrane->checkbox_no($overheadcrane->locr_29)}}>
                                                <label class="custom-control-label" for="locr_29_n">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <p>- In accordance with an examination scheme?</p>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_30_y " name="locr_30" {{$overheadcrane->checkbox_yes($overheadcrane->locr_30)}}>
                                                <label class="custom-control-label" for="locr_30_y ">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_30_n" name="locr_30" {{$overheadcrane->checkbox_no($overheadcrane->locr_30)}}>
                                                <label class="custom-control-label" for="locr_30_n">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <p>- After the occurrence of exceptional Circumstances?</p>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_31_y " name="locr_31" {{$overheadcrane->checkbox_yes($overheadcrane->locr_31)}}>
                                                <label class="custom-control-label" for="locr_31_y ">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_31_n" name="locr_31" {{$overheadcrane->checkbox_no($overheadcrane->locr_31)}}>
                                                <label class="custom-control-label" for="locr_31_n">No</label>
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
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect( <button id="input1" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">if none state NONE</button> )</label>
                                                    <input type="text" id="locr_32" name="locr_32" class="form-control" placeholder="Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect(if none state NONE)" value="{{$overheadcrane->locr_32}}" />
                                                <div class="help-block"></div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="danger" class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <p>Is the above a defect which is of immediate danger to persons?</p>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_33_y " name="locr_33" {{$overheadcrane->checkbox_yes($overheadcrane->locr_33)}}>
                                                <label class="custom-control-label" for="locr_33_y ">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_33_n" name="locr_33" {{$overheadcrane->checkbox_no($overheadcrane->locr_33)}}>
                                                <label class="custom-control-label" for="locr_33_n">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <p>Is the above a defect which is not yet but could become a danger to persons?</p>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_34_y " name="locr_34" {{$overheadcrane->checkbox_yes($overheadcrane->locr_34)}}>
                                                <label class="custom-control-label" for="locr_34_y ">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="locr_34_n" name="locr_34" {{$overheadcrane->checkbox_no($overheadcrane->locr_34)}}>
                                                <label class="custom-control-label" for="locr_34_n">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <p>If the answer of the above question is Yes state date by when</p>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="ft-calendar"></i></span>
                                                </div>
                                                <input type="text" class="form-control dp-date-range-from" id="locr_35" name="locr_35" value="{{$overheadcrane->locr_35}}" />
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
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Particulars of any repair, renewal, or alteration   to remedy the defect identified above:</label>
                                                    <input type="text" id="locr_36" name="locr_36" class="form-control" placeholder="Particulars of any repair, renewal, or alteration   to remedy the defect identified above" value="{{$overheadcrane->locr_36}}"   />
                                                <div class="help-block"></div></div>
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
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Particulars of any tests carried out as part of the examination( <button id="input2" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">if none state NONE</button> )</label>
                                                    <input type="text" id="locr_37" name="locr_37" class="form-control" placeholder="Particulars of any repair, renewal, or alteration   to remedy the defect identified above" value="{{$overheadcrane->locr_37}}" />
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6>Proof Load Test Details</h6>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>1-Performance test</label>
                                                    <input type="text" id="locr_38" name="locr_38" class="form-control" placeholder="(performance test should be carried out after function test with SWL)" value="{{$overheadcrane->locr_38}}"   />
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
                                        <div class="col-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Main Girder crane Span</label>
                                                    <input type="text" id="locr_39" name="locr_39" class="form-control" placeholder="Main Girder crane Span" value="{{$overheadcrane->locr_39}}"   />
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Maximum Deflection allowable</label>
                                                    <input type="text" id="locr_40" name="locr_40" class="form-control" placeholder="Maximum Deflection allowable" value="{{$overheadcrane->locr_40}}" />
                                                <div class="help-block"></div></div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Actual deflection</label>
                                                    <input type="text" id="locr_41" name="locr_41" class="form-control" placeholder="Actual deflection" value="{{$overheadcrane->locr_41}}" />
                                                <div class="help-block"></div></div>
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
                                              <div class="form-group mb-0">
                                                  <div class="controls">
                                                      <label>2-Over Load test</label>
                                                      <input type="text" id="locr_42" name="locr_42" class="form-control" placeholder="Over Load test" value="{{$overheadcrane->locr_42}}" />
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
                                              <div class="form-group mb-0">
                                                  <div class="controls">
                                                      <label>Deflection of Main Girder Crane During over load</label>
                                                      <input type="text" id="locr_43" name="locr_43" class="form-control" placeholder="Deflection of Main Girder Crane During over load" value="{{$overheadcrane->locr_43}}" />
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
                                      <div class="row mt-1">
                                          <div class="col-8">
                                              <p>Is this equipment safe to operate?</p>
                                          </div>
                                          <div class="col-2">
                                              <div class="custom-control custom-radio">
                                                  <input type="radio" class="custom-control-input" id="locr_44_y " name="locr_44" {{$overheadcrane->checkbox_yes($overheadcrane->locr_44)}}>
                                                  <label class="custom-control-label" for="locr_44_y ">Yes</label>
                                              </div>
                                          </div>
                                          <div class="col-2">
                                              <div class="custom-control custom-radio">
                                                  <input type="radio" class="custom-control-input" id="locr_44_n" name="locr_44" {{$overheadcrane->checkbox_no($overheadcrane->locr_44)}}>
                                                  <label class="custom-control-label" for="locr_44_n">No</label>
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
			              $(this).find(".body:eq(" + newIndex + ") label.error").remove();
			              $(this).find(".body:eq(" + newIndex + ") .error").removeClass("error");
			          }
			          return window.validateWizardCurrentStep($(this));
			      },
			      onFinishing: function (event, currentIndex) {
			          $(this).validate().settings.ignore = ":disabled";
			          return $(this).valid();
			      },
			      onFinished: function (event, currentIndex) {
			          var form1 = $('.wizard')[0];
			          var formdata = new FormData(form1);
			          var checkbox = $(".wizard").find("input[type=radio]");
                formdata.append('lcr_1', $('#lcr_1').val());
			          formdata.append('code', $('#code').val());
                formdata.append('lcr_7', $('#lcr_7').val());
                    formdata.append('locr_2', $('#purchaseOrder').val());
                formdata.append('locr_36', $('#locr_36').val());
			          $.each(checkbox, function(key, val) {
			              if($(this).is(':checked') === true){
			                formdata.append($(this).attr('name'), $(this).attr('id'));
			              }
			          });
			          $.ajax({
			            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			            type: 'POST',
			            url: "{{route('overheadCrane.update', $overheadcrane->id)}}",
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
                      window.location.href = "{{route('overheadCrane.index')}}";
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
      $('.steps-validation').on('click','#input1', function(){
        $('#locr_32, #locr_36').val("NONE");
        $('#danger input, #locr_36').prop( "disabled", true );
        $('#danger input[type=radio]').prop( "checked", false );
        $('#locr_35').val("");
      });
      $('.steps-validation').on('click','#input2', function(){
        $('#locr_37').val("NONE");
      });
      $('.steps-validation').on('change','#locr_32', function(){
        $('#danger input, #locr_36').prop( "disabled", false );
        $('#locr_36').val("");
      });
      /************************************************/
      $('input[name=locr_27]').prop( "disabled", true );
      $('input[name=locr_27]').prop( "checked", false );
      $('.steps-validation').on("change", 'input[name=locr_26]',function(e){
        if(this.id.match('_n')){
          $('input[name=locr_27]').prop( "disabled", true );
          $('input[name=locr_27]').prop( "checked", false );
        }else{
          $('input[name=locr_27]').prop( "disabled", false );
        }
      });
      /************************************************/
      $('.steps-validation').on("change", '#carried input[type=radio]',function(e){
        var id = this.id;
        var name = this.name;
        if(id.match('_y')){
          $('#carried input[type=radio]').each(function(index, value){
            $('#'+value.name+'_n').prop("disabled", true);
            if(value.name !== name){
              $('#'+value.name+'_n').prop("checked", true);
            }
          });
        }
      });
    </script>
@endpush
