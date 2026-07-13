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
																										<select class="form-control" id="lcr_1" name="lcr_1" required="">
																												<option value="">Select Value</option>
																												@foreach($jobrequests as $jobrequest)
																														<option value="{{$jobrequest->id}}"  @if($jobrequest->id === $crane->job_request_id) selected @endif>{{$jobrequest->code}}</option>
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
																										<input type="text" id="purchaseOrder" name="lcr_2" class="form-control" placeholder="Purchase Order" value="{{$crane->lcr_2}}" disabled>
																										<div class="help-block"></div>
																								</div>
																						</div>
																				</div>
																				<div class="col-12 col-sm-4">
																						<div class="form-group mb-0">
																								<div class="controls">
																										<label>Report No.</label>
																										<div class="clearfix">
																												<input type="text" id="precode" class="form-control pr-0" placeholder="Report No" value="{{$crane->job_request->code}} /" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled style="width: 83px; float: left;">
																												<input type="text" id="code" name="code" class="form-control pl-0" placeholder="." value="{{$crane->code}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled  style="width: auto; float: left;">
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
																												<input type="text" class="form-control datepicker-default" id="lcr_6" name="lcr_6" placeholder="Examination Date" value="{{$crane->lcr_6}}" required="" data-validation-required-message="This field is required"/>
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
																												<input type="text" class="form-control dp-date-range-to" id="lcr_7" name="lcr_7" value="{{$crane->lcr_7}}" placeholder="Next Examination Date" required="" data-validation-required-message="This field is required" disabled/>
																										</div>
																										<div class="help-block"></div>
																								</div>
																						</div>
																				</div>
																				<div class="col-12 col-sm-4">
																						<div class="form-group mb-0">
																								<div class="controls">
																										<label>Color Code</label>
																										<input type="text" id="lcr_8" name="lcr_8" class="form-control" placeholder="Color Code" value="{{$crane->lcr_8}}" required="" data-validation-required-message="This field is required"/>
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
																										<label>Work location</label>
																										<input type="text" id="deploc" name="deploc" class="form-control" placeholder="Work location" value="" required="" data-validation-required-message="This field is required" disabled />
																										<div class="help-block"></div>
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
																				<div class="col-12 col-sm-4">
																						<div class="form-group mb-0">
																								<div class="controls">
																										<label>Type of Crane and nature of Power</label>
																										<input type="text" id="lcr_10" name="lcr_10" class="form-control" placeholder="Type of Crane and nature of Power" value="{{$crane->lcr_10}}" required="" data-validation-required-message="This field is required">
																										<div class="help-block"></div>
																								</div>
																						</div>
																				</div>
																				<div class="col-12 col-sm-4">
																						<div class="form-group mb-0">
																								<div class="controls">
																										<label>Name of Manufacturer</label>
																										<input type="text" id="lcr_11" name="lcr_11" class="form-control" placeholder="Name of Manufacturer" value="{{$crane->lcr_11}}" required="" data-validation-required-message="This field is required">
																										<div class="help-block"></div>
																								</div>
																						</div>
																				</div>
																				<div class="col-12 col-sm-4">
																						<div class="form-group mb-0">
																								<div class="controls">
																										<label>Identification Number/chassis number</label>
																										<input type="text" id="lcr_12" name="lcr_12" class="form-control" placeholder="Identification Number/chassis number" value="{{$crane->lcr_12}}" required="" data-validation-required-message="This field is required">
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
																										<input type="text" id="lcr_13" name="lcr_13" class="form-control" placeholder="Model/Type" value="{{$crane->lcr_13}}" required="" data-validation-required-message="This field is required">
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
																												<input type="text" class="form-control dp-date-range-from" id="lcr_14" name="lcr_14" value="{{$crane->lcr_14}}" placeholder="Date of Manufacturer (if known)" />
																										</div>
																										<div class="help-block"></div>
																								</div>
																						</div>
																				</div>
																				<div class="col-12 col-sm-4">
																						<div class="form-group mb-0">
																								<div class="controls">
																										<label>Crane Capacity (SWL)</label>
																										<input type="text" id="lcr_15" name="lcr_15" class="form-control" placeholder="Crane Capacity (SWL)" value="{{$crane->lcr_15}}" required="" data-validation-required-message="This field is required">
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
																										<input type="text" id="lcr_16" name="lcr_16" class="form-control" placeholder="Make and type of automatic safe load indicator(SLI)" value="{{$crane->lcr_16}}" required="" data-validation-required-message="This field is required">
																										<div class="help-block"></div>
																								</div>
																						</div>
																				</div>
																				<div class="col-12 col-sm-4">
																						<div class="form-group mb-0">
																								<div class="controls">
																										<label>SLI Identification number(if fitted)</label>
																										<input type="text" id="lcr_17" name="lcr_17" class="form-control" placeholder="SLI Identification number(if fitted)" value="{{$crane->lcr_17}}" required="" data-validation-required-message="This field is required">
																										<div class="help-block"></div>
																								</div>
																						</div>
																				</div>
																				<div class="col-12 col-sm-4">
																						<div class="form-group mb-0">
																								<div class="controls">
																										<label>Number of falls/lines</label>
																										<input type="text" id="lcr_18" name="lcr_18" class="form-control" placeholder="Number of falls/lines" value="{{$crane->lcr_18}}" required="" data-validation-required-message="This field is required">
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
																										<label>Date of last Through Examination</label>
																										<div class="input-group">
																												<div class="input-group-prepend">
																														<span class="input-group-text"><i class="ft-calendar"></i></span>
																												</div>
																												<input type="text" id="lcr_19" name="lcr_19" class="form-control dp-date-range-from" placeholder="Date of last Through Examination" value="{{$crane->lcr_19}}"  required="" data-validation-required-message="This field is required"/>
																										</div>
																										<div class="help-block"></div>
																								</div>
																						</div>
																				</div>
																				<div class="col-12 col-sm-4">
																						<div class="form-group mb-0">
																								<div class="controls">
																										<label>Certificate Number</label>
																										<input type="text" id="lcr_20" name="lcr_20" class="form-control" placeholder="Certificate Number" value="{{$crane->lcr_20}}" required="" data-validation-required-message="This field is required">
																										<div class="help-block"></div>
																								</div>
																						</div>
																				</div>
																				<div class="col-12 col-sm-4">
																						<div class="form-group mb-0">
																								<div class="controls">
																										<label>Examined by</label>
																										<input type="text" id="lcr_21" name="lcr_21" class="form-control" placeholder="Examined by" value="{{$crane->lcr_21}}" required="" data-validation-required-message="This field is required">
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
																												<input type="text" class="form-control dp-date-range-from" id="lcr_22" name="lcr_22" placeholder="Date of last Load Test" value="{{$crane->lcr_22}}" required="" data-validation-required-message="This field is required"/>
																										</div>
																										<div class="help-block"></div>
																								</div>
																						</div>
																				</div>
																				<div class="col-12 col-sm-4">
																						<div class="form-group mb-0">
																								<div class="controls">
																										<label>Certificate Number</label>
																										<input type="text" id="lcr_23" name="lcr_23" class="form-control" placeholder="Certificate Number" value="{{$crane->lcr_23}}" required="" data-validation-required-message="This field is required">
																										<div class="help-block"></div>
																								</div>
																						</div>
																				</div>
																				<div class="col-12 col-sm-4">
																						<div class="form-group mb-0">
																								<div class="controls">
																										<label>Tested by</label>
																										<input type="text" id="lcr_24" name="lcr_24" class="form-control" placeholder="Tested by" value="{{$crane->lcr_24}}"  required="" data-validation-required-message="This field is required">
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
																										<input type="text" id="lcr_25" name="lcr_25" class="form-control" placeholder="Reference Standard" value="{{$crane->lcr_25}}" required="" data-validation-required-message="This field is required"/>
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
																								<input type="radio" class="custom-control-input" id="lcr_26_y " name="lcr_26" required {{$crane->checkbox_yes($crane->lcr_26)}} />
																								<label class="custom-control-label" for="lcr_26_y ">Yes</label>
																						</div>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_26_n" name="lcr_26" {{$crane->checkbox_no($crane->lcr_26)}} />
																								<label class="custom-control-label" for="lcr_26_n">No</label>
																						</div>
																				</div>
																		</div>
																		<div class="row">
																				<div class="col-8">
																						<p>- If the answer to the above question is YES Has the equipment been installed correctly?</p>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_27_y " name="lcr_27" required {{$crane->checkbox_yes($crane->lcr_27)}} />
																								<label class="custom-control-label" for="lcr_27_y ">Yes</label>
																						</div>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_27_n" name="lcr_27" {{$crane->checkbox_no($crane->lcr_27)}} />
																								<label class="custom-control-label" for="lcr_27_n">No</label>
																						</div>
																				</div>
																		</div>
																</div>
														</div>
												</div>
						          	<h6>* Was the thorough examination carried out</h6>
												<div id="carried" class="card">
														<div class="card-content collapse show">
																<div class="card-body">
																		<div class="row mt-1">
																				<div class="col-8">
																						<p>- Within an interval of 6 months?</p>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																						<input type="radio" class="custom-control-input" id="lcr_28_y " name="lcr_28" required {{$crane->checkbox_yes($crane->lcr_28)}} />
																						<label class="custom-control-label" for="lcr_28_y ">Yes</label>
																				</div>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_28_n" name="lcr_28" {{$crane->checkbox_no($crane->lcr_28)}} />
																								<label class="custom-control-label" for="lcr_28_n">No</label>
																						</div>
																				</div>
																		</div>
																		<div class="row">
																				<div class="col-8">
																						<p>- Within an interval of 12 months?</p>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_29_y " name="lcr_29" required {{$crane->checkbox_yes($crane->lcr_29)}} />
																								<label class="custom-control-label" for="lcr_29_y ">Yes</label>
																						</div>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_29_n" name="lcr_29" {{$crane->checkbox_no($crane->lcr_29)}} />
																								<label class="custom-control-label" for="lcr_29_n">No</label>
																						</div>
																				</div>
																		</div>
																		<div class="row">
																				<div class="col-8">
																						<p>- In accordance with an examination scheme?</p>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_30_y " name="lcr_30" required {{$crane->checkbox_yes($crane->lcr_30)}} />
																								<label class="custom-control-label" for="lcr_30_y ">Yes</label>
																						</div>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_30_n" name="lcr_30" {{$crane->checkbox_no($crane->lcr_30)}} />
																								<label class="custom-control-label" for="lcr_30_n">No</label>
																						</div>
																				</div>
																		</div>
																		<div class="row">
																				<div class="col-8">
																						<p>- After the occurrence of exceptional Circumstances?</p>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_31_y " name="lcr_31" required {{$crane->checkbox_yes($crane->lcr_31)}} />
																								<label class="custom-control-label" for="lcr_31_y ">Yes</label>
																						</div>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_31_n" name="lcr_31" {{$crane->checkbox_no($crane->lcr_31)}} />
																								<label class="custom-control-label" for="lcr_31_n">No</label>
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
																										<label>Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect ( <button id="input1" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">if none state NONE</button> )</label>
																										<input type="text" id="lcr_32" name="lcr_32" class="form-control" placeholder="Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect(if none state NONE)" value="{{$crane->lcr_32}}"  required="" data-validation-required-message="This field is required"/>
																										<div class="help-block"></div>
																								</div>
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
																								<input type="radio" class="custom-control-input" id="lcr_33_y " name="lcr_33" {{$crane->checkbox_yes($crane->lcr_33)}} />
																								<label class="custom-control-label" for="lcr_33_y ">Yes</label>
																						</div>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_33_n" name="lcr_33" {{$crane->checkbox_no($crane->lcr_33)}} />
																								<label class="custom-control-label" for="lcr_33_n">No</label>
																						</div>
																				</div>
																		</div>
																		<div class="row">
																				<div class="col-8">
																						<p>Is the above a defect which is not yet but could become a danger to persons?</p>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_34_y " name="lcr_34" {{$crane->checkbox_yes($crane->lcr_34)}} />
																								<label class="custom-control-label" for="lcr_34_y ">Yes</label>
																						</div>
																				</div>
																				<div class="col-2">
																						<div class="custom-control custom-radio">
																								<input type="radio" class="custom-control-input" id="lcr_34_n" name="lcr_34" {{$crane->checkbox_no($crane->lcr_34)}} />
																								<label class="custom-control-label" for="lcr_34_n">No</label>
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
																								<input type="text" class="form-control dp-date-range-from" id="lcr_35" name="lcr_35" value="{{$crane->lcr_35}}" />
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
																										<label>Particulars of any repair, renewal, or alteration required to remedy the defect identified above:</label>
																										<input type="text" id="lcr_36" name="lcr_36" class="form-control" placeholder="Particulars of any repair, renewal, or alteration   to remedy the defect identified above" value="{{$crane->lcr_36}}" required="" data-validation-required-message="This field is required"/>
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
																										<label>Particulars of any tests carried out as part of the examination( <button id="input2" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">if none state NONE</button> )</label>
																										<input type="text" id="lcr_37" name="lcr_37" class="form-control" placeholder="Particulars of any tests carried out as part of the examination(if none state NONE)" value="{{$crane->lcr_37}}" required="" data-validation-required-message="This field is required"/>
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
												<h6>Load Test Details</h6>
														<div class="card">
																<div class="card-content collapse show">
																		<div class="card-body">
																				<div class="row">
																						<p class="col-12">Safe working load or loads in the case of a crane with a variable operating radius (including a crane with a derricking jib or within ter-changeable jibs of different lengths) the safe working load at various radii of the jib, trolley or crab must be given. Test loads at various radii should be given in column (iii) and in the case of a safe working load, which has been calculated without the application of a test load “nil” should be entered in that column.</p>
																				</div>
																		</div>
																</div>
														</div>
														<div class="card contact">
																<div class="card-content collapse show">
																		<div class="card-body" data-repeater-list="payments">
																				<div class="row">
																						<div class="col-3">
																								<div class="form-group mb-0">
																										<label>Length of jib (1)</label>
																								</div>
																						</div>
																						<div class="col-3">
																								<div class="form-group mb-0">
																										<label>@ Radius(iii) (2)</label>
																								</div>
																						</div>
																						<div class="col-3">
																								<div class="form-group mb-0">
																										<label>Proof Load (3)</label>
																								</div>
																						</div>
																						<div class="col-2">
																								<div class="form-group mb-0">
																										<label>SWL (4)</label>
																								</div>
																						</div>
																						<div class=" col-1"></div>
																				</div>
																				@foreach(json_decode($crane->lcr_38) as $index => $value)
																						<div class="row" data-repeater-item>
																								<div class="col-3">
																										<div class="form-group mb-0">
																												<div class="controls">
																														<input type="text" id="lcr_38" name="lcr_38" class="form-control" placeholder="@ Length of jib (1)" value="{{$value->lcr_38}}" required="" data-validation-required-message="This field is required"/>
																														<div class="help-block"></div>
																												</div>
																										</div>
																								</div>
																								<div class="col-3">
																										<div class="form-group mb-0">
																												<div class="controls">
																														<input type="text" id="lcr_39" name="lcr_39" class="form-control" placeholder="@ Radius(iii) (2)" value="{{$value->lcr_39}}" required="" data-validation-required-message="This field is required"/>
																														<div class="help-block"></div>
																												</div>
																										</div>
																								</div>
																								<div class="col-3">
																										<div class="form-group mb-0">
																												<div class="controls">
																														<input type="text" id="lcr_40" name="lcr_40" class="form-control" placeholder="Proof Load (3)" value="{{$value->lcr_40}}" required="" data-validation-required-message="This field is required"/>
																														<div class="help-block"></div>
																												</div>
																										</div>
																								</div>
																								<div class="col-2">
																										<div class="form-group mb-0">
																												<div class="controls">
																														<input type="text" id="lcr_41" name="lcr_41" class="form-control" placeholder="SWL (4)" value="{{$value->lcr_41}}" required="" data-validation-required-message="This field is required"/>
																														<div class="help-block"></div>
																												</div>
																										</div>
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
														<div class="card">
																<div class="card-content collapse show">
																		<div class="card-body">
																				<div class="row">
																						<p class="col-12">In the case of a crane with a derricking Jib or jibs the maximum radius at which the jib or jibs may be worked</p>
																				</div>
																		</div>
																</div>
														</div>
														<div class="card">
																<div class="card-content collapse show">
																		<div class="card-body">
																				<div class="row">
																						<div class="col-3">
																								<div class="form-group mb-0">
																										<div class="controls">
																												<input type="text" id="lcr_42" name="lcr_42" class="form-control" placeholder="Length of jib (1)" value="{{$crane->lcr_42}}" />
																												<div class="help-block"></div>
																										</div>
																								</div>
																						</div>
																						<div class="col-3">
																								<div class="form-group mb-0">
																										<div class="controls">
																												<input type="text" id="lcr_43" name="lcr_43" class="form-control" placeholder="@ Radius(iii) (2)" value="{{$crane->lcr_43}}" />
																												<div class="help-block"></div>
																										</div>
																								</div>
																						</div>
																						<div class="col-3">
																								<div class="form-group mb-0">
																										<div class="controls">
																												<input type="text" id="lcr_44" name="lcr_44" class="form-control" placeholder="Proof Load (3)" value="{{$crane->lcr_44}}" />
																												<div class="help-block"></div>
																										</div>
																								</div>
																						</div>
																						<div class="col-3">
																								<div class="form-group mb-0">
																										<div class="controls">
																												<input type="text" id="lcr_45" name="lcr_45" class="form-control" placeholder="SWL (4)" value="{{$crane->lcr_45}}" />
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
																										<input type="radio" class="custom-control-input" id="lcr_46_y " name="lcr_46" required {{$crane->checkbox_yes($crane->lcr_46)}} />
																										<label class="custom-control-label" for="lcr_46_y ">Yes</label>
																								</div>
																						</div>
																						<div class="col-2">
																								<div class="custom-control custom-radio">
																										<input type="radio" class="custom-control-input" id="lcr_46_n" name="lcr_46" {{$crane->checkbox_no($crane->lcr_46)}} />
																										<label class="custom-control-label" for="lcr_46_n">No</label>
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
			          formdata.append('lcr_36', $('#lcr_36').val());
                      formdata.append('lcr_2', $('#purchaseOrder').val());
					  formdata.append('publish', 'yes');
			          $.each(checkbox, function(key, val) {
			              if($(this).is(':checked') === true){
			                formdata.append($(this).attr('name'), $(this).attr('id'));
			              }
			          });
			          $.ajax({
			            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			            type: 'POST',
			            url: "{{route('crane.update', $crane->id)}}",
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
			                window.location.href = "{{route('crane.index')}}";
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
				get_report_data($('#lcr_1 option:selected').val(), $('#lcr_1 option:selected').text());
				$('input[name=lcr_27]').prop( "disabled", true );
				$('input[name=lcr_27]').prop( "checked", false );

				$('.steps-validation').on("change", 'input[name=lcr_26]',function(e){
						if (this.id.match('_n'))
						{
								$('input[name=lcr_27]').prop( "disabled", true );
								$('input[name=lcr_27]').prop( "checked", false );
						}
						else
						{
								$('input[name=lcr_27]').prop( "disabled", false );
						}
				});

				$('.steps-validation').on('click','#input1', function(){
						$('#lcr_32, #lcr_36').val("NONE");
						$('#danger input, #lcr_36').prop( "disabled", true );
						$('#danger input[type=radio]').prop( "checked", false );
						$('#lcr_35').val("");
				});

				$('.steps-validation').on('click','#input2', function(){
						$('#lcr_37').val("NONE");
				});

				$('.steps-validation').on('change','#lcr_32', function(){
						$('#danger input, #lcr_36').prop( "disabled", false );
						$('#lcr_36').val("");
				});

				$('.steps-validation').on("change", '#carried input[type=radio]',function(e){
						var id = this.id;
						var name = this.name;
						if (id.match('_y'))
						{
								$('#carried input[type=radio]').each(function(index, value){
										$('#'+value.name+'_n').prop("disabled", true);
										if (value.name !== name)
										{
												$('#'+value.name+'_n').prop("checked", true);
										}
								});
						}
				});
				$('.contact').repeater({
						show: function ()
						{
								$(this).slideDown();
						},
						hide: function(remove)
						{
								var $row = $(this);
								if (typeof Swal !== 'undefined') {
										Swal.fire({
												title: 'Remove Row?',
												text: 'This row will be deleted.',
												type: 'warning',
												icon: 'warning',
												showCancelButton: true,
												confirmButtonText: 'Yes, remove',
												cancelButtonText: 'Cancel',
												confirmButtonClass: 'btn btn-danger',
												cancelButtonClass: 'btn btn-dark ml-1',
												buttonsStyling: false
										}).then(function (result) {
												if (result.value) {
														$row.slideUp(remove);
												}
										});
										return;
								}
								$row.slideUp(remove);
						}
				});
		</script>
@endpush
