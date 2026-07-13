@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')

<section id="validation">
  <div class="row">
    <div class="col-12">
            <form action="#" class="steps-validation wizard-circle"  novalidate enctype="multipart/form-data">
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
                                      <option value="">Select Value</option>
                                      @foreach($jobrequests as $jobrequest)
                                          <option value="{{$jobrequest->id}}"  @if($jobrequest->id === $forklift->job_request_id) selected @endif>{{$jobrequest->code}}</option>
                                      @endforeach
                                  </select>
                              <div class="help-block"></div></div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="form-group mb-0">
                              <div class="controls">
                                  <label>Purchase Order</label>
                                  <input disabled type="text" id="purchaseOrder" name="lfr_2" class="form-control" placeholder="Purchase Order" value="{{$forklift->lfr_2}}" required>
                              <div class="help-block"></div></div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="form-group mb-0">
                              <div class="controls">
                                  <label>Report No.</label>
                                  <div class="clearfix">
                                    <input type="text" id="precode" class="form-control pr-0" placeholder="Report No" value="{{$forklift->job_request->code}} /" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled style="width: 83px; float: left;">
                                    <input type="text" id="code" name="code" class="form-control pl-0" placeholder="." value="{{$forklift->code}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled  style="width: auto; float: left;">
                                  </div>
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
                                      <input type="text" class="form-control datepicker-default" id="lcr_6" name="lcr_6" placeholder="Examination Date"required="" value="{{$forklift->lfr_6}}" data-validation-required-message="This field is required"/>
                                  </div>
                              <div class="help-block"></div></div>
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
                                      <input type="text" class="form-control dp-date-range-to" id="lcr_7" name="lcr_7" placeholder="Next Examination Date"required="" value="{{$forklift->lfr_7}}" data-validation-required-message="This field is required" disabled/>
                                  </div>
                              <div class="help-block"></div></div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="form-group mb-0">
                              <div class="controls">
                                  <label>Color Code</label>
                                  <input type="text" id="lfr_8" name="lfr_8" class="form-control" placeholder="Color Code" value="{{$forklift->lfr_8}}" required="" data-validation-required-message="This field is required"/>
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
                                  <label>Work location</label>
                                  <input type="text" id="deploc" name="deploc" class="form-control" placeholder="Work location" value="" required="" data-validation-required-message="This field is required" disabled />
                              <div class="help-block"></div></div>
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
                                  <label>Equipment Description</label>
                                  <input type="text" id="lfr_10" name="lfr_10" class="form-control" placeholder="Equipment Description" value="{{$forklift->lfr_10}}"  required="" data-validation-required-message="This field is required">
                              <div class="help-block"></div></div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="form-group mb-0">
                              <div class="controls">
                                  <label>Name of Manufacturer</label>
                                  <input type="text" id="lfr_11" name="lfr_11" class="form-control" placeholder="Name of Manufacturer" value="{{$forklift->lfr_11}}"  required="" data-validation-required-message="This field is required">
                              <div class="help-block"></div></div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="form-group mb-0">
                              <div class="controls">
                                  <label>Identification Number/chassis number</label>
                                  <input type="text" id="lfr_12" name="lfr_12" class="form-control" placeholder="Identification Number/chassis number" value="{{$forklift->lfr_12}}" required="" data-validation-required-message="This field is required" >
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
                                  <label>Model/Type</label>
                                  <input type="text" id="lfr_13" name="lfr_13" class="form-control" placeholder="Model/Type" value="{{$forklift->lfr_13}}"  required="" data-validation-required-message="This field is required">
                              <div class="help-block"></div></div>
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
                                      <input type="text" class="form-control dp-date-range-from" id="lfr_14" name="lfr_14" placeholder="Date of Manufacturer (if known)" value="{{$forklift->lfr_14}}" />
                                  </div>
                              <div class="help-block"></div></div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="form-group mb-0">
                              <div class="controls">
                                  <label>Safe Working Load ( SWL)</label>
                                  <input type="text" id="lfr_15" name="lfr_15" class="form-control" placeholder="Crane Capacity (SWL)" value="{{$forklift->lfr_15}}"  required="" data-validation-required-message="This field is required">
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
                                  <label>Date of last Through Examination</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="ft-calendar"></i></span>
                                      </div>
                                      <input type="text" id="lfr_16" name="lfr_16" class="form-control dp-date-range-from" placeholder="Date of last Through Examination" value="{{$forklift->lfr_16}}"  required="" data-validation-required-message="This field is required"/>
                                  </div>
                              <div class="help-block"></div></div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="form-group mb-0">
                              <div class="controls">
                                  <label>Certificate Number</label>
                                  <input type="text" id="lfr_17" name="lfr_17" class="form-control" placeholder="Certificate Number" value="{{$forklift->lfr_17}}"  required="" data-validation-required-message="This field is required">
                              <div class="help-block"></div></div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="form-group mb-0">
                              <div class="controls">
                                  <label>Examined by</label>
                                  <input type="text" id="lfr_18" name="lfr_18" class="form-control" placeholder="Examined by" value="{{$forklift->lfr_18}}" required="" data-validation-required-message="This field is required" >
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
                                  <label>Proof load test Details (if applied)</label>
                                  <input type="text" id="lfr_19" name="lfr_19" class="form-control" placeholder="Proof load test Details (if applied)" value="{{$forklift->lfr_19}}" required="" data-validation-required-message="This field is required"/>
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
                                  <label>Reference Standard</label>
                                  <input type="text" id="lfr_20" name="lfr_20" class="form-control" placeholder="Reference Standard" value="{{$forklift->lfr_20}}" required="" data-validation-required-message="This field is required"/>
                              <div class="help-block"></div></div>
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
                              <input type="radio" class="custom-control-input" id="lfr_21_y " name="lfr_21" {{$forklift->checkbox_yes($forklift->lfr_21)}} required>
                              <label class="custom-control-label" for="lfr_21_y ">Yes</label>
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_21_n" name="lfr_21" {{$forklift->checkbox_no($forklift->lfr_21)}}>
                              <label class="custom-control-label" for="lfr_21_n">No</label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-8">
                          <p>- If the answer to the above question is YES Has the equipment been installed correctly?</p>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_22_y " name="lfr_22" {{$forklift->checkbox_yes($forklift->lfr_22)}} required>
                              <label class="custom-control-label" for="lfr_22_y ">Yes</label>
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_22_n" name="lfr_22" {{$forklift->checkbox_no($forklift->lfr_22)}}>
                              <label class="custom-control-label" for="lfr_22_n">No</label>
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
                              <input type="radio" class="custom-control-input" id="lfr_23_y " name="lfr_23" {{$forklift->checkbox_yes($forklift->lfr_23)}} required>
                              <label class="custom-control-label" for="lfr_23_y ">Yes</label>
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_23_n" name="lfr_23" {{$forklift->checkbox_no($forklift->lfr_23)}}>
                              <label class="custom-control-label" for="lfr_23_n">No</label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-8">
                          <p>- Within an interval of 12 months?</p>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_24_y " name="lfr_24" {{$forklift->checkbox_yes($forklift->lfr_24)}} required>
                              <label class="custom-control-label" for="lfr_24_y ">Yes</label>
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_24_n" name="lfr_24" {{$forklift->checkbox_no($forklift->lfr_24)}}>
                              <label class="custom-control-label" for="lfr_24_n">No</label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-8">
                          <p>- In accordance with an examination scheme?</p>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_25_y " name="lfr_25" {{$forklift->checkbox_yes($forklift->lfr_25)}} required>
                              <label class="custom-control-label" for="lfr_25_y ">Yes</label>
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_25_n" name="lfr_25" {{$forklift->checkbox_no($forklift->lfr_25)}}>
                              <label class="custom-control-label" for="lfr_25_n">No</label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-8">
                          <p>- After the occurrence of exceptional Circumstances?</p>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_26_y " name="lfr_26" {{$forklift->checkbox_yes($forklift->lfr_26)}} required>
                              <label class="custom-control-label" for="lfr_26_y ">Yes</label>
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_26_n" name="lfr_26" {{$forklift->checkbox_no($forklift->lfr_26)}}>
                              <label class="custom-control-label" for="lfr_26_n">No</label>
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
                                  <input type="text" id="lfr_27" name="lfr_27" class="form-control" placeholder="Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect(if none state NONE)" value="{{$forklift->lfr_27}}" required="" data-validation-required-message="This field is required"/>
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
                              <input type="radio" class="custom-control-input" id="lfr_28_y " name="lfr_28" {{$forklift->checkbox_yes($forklift->lfr_28)}}>
                              <label class="custom-control-label" for="lfr_28_y ">Yes</label>
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_28_n" name="lfr_28" {{$forklift->checkbox_no($forklift->lfr_28)}}>
                              <label class="custom-control-label" for="lfr_28_n">No</label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-8">
                          <p>Is the above a defect which is not yet but could become a danger to persons?</p>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_29_y " name="lfr_29" {{$forklift->checkbox_yes($forklift->lfr_29)}}>
                              <label class="custom-control-label" for="lfr_29_y ">Yes</label>
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" id="lfr_29_n" name="lfr_29" {{$forklift->checkbox_no($forklift->lfr_29)}}>
                              <label class="custom-control-label" for="lfr_29_n">No</label>
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
                              <input type="text" class="form-control dp-date-range-from" id="lfr_30" name="lfr_30" {{$forklift->lfr_30}} />
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
                                  <input type="text" id="lfr_31" name="lfr_31" class="form-control" placeholder="Particulars of any repair, renewal, or alteration to remedy the defect identified above" value="{{$forklift->lfr_31}}" required="" data-validation-required-message="This field is required"/>
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
                                  <input type="text" id="lfr_32" name="lfr_32" class="form-control" placeholder="Particulars of any tests carried out as part of the examination(if none state NONE)" value="{{$forklift->lfr_32}}" required="" data-validation-required-message="This field is required"/>
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
                      <div class="row mt-1">
                <div class="col-9 mt-1 mt-sm-0">
                  <div class="form-group">
                    <div class="controls">
                        <label>Upload Image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="lfr_34" name="lfr_34" data-validation-required-message="This field is required">
                            <label class="custom-file-label" style="height: 2.80rem;" for="lfr_34" aria-describedby="lfr_34">Choose file</label>
                        </div>
                    <div class="help-block"></div></div>
                  </div>
                </div>
                <div class="col-2 mt-1 mt-sm-0">
                    <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{Storage::url('camera/inspection/lifting/forklifts/')}}{{$forklift->lfr_34}}" alt="" />
                </div>
                <div class="col-1 mt-1 mt-sm-0">
                    <button type="button" data-id="delete" class="btn btn-icon btn-danger mr-1 delete"><i class="la la-trash"></i></button>
                    <input type="hidden" value="{{$forklift->lfr_34}}" id="imagedata" />
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
                      <input type="radio" class="custom-control-input" id="lfr_33_y " name="lfr_33" {{$forklift->checkbox_yes($forklift->lfr_33)}} required>
                      <label class="custom-control-label" for="lfr_33_y ">Yes</label>
                  </div>
                </div>
                <div class="col-2">
                  <div class="custom-control custom-radio">
                      <input type="radio" class="custom-control-input" id="lfr_33_n" name="lfr_33" {{$forklift->checkbox_no($forklift->lfr_33)}}>
                      <label class="custom-control-label" for="lfr_33_n">No</label>
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
                formdata.append('lcr_1', $('#lcr_1').val());
                formdata.append('code', $('#code').val());
                formdata.append('lfr_2', $('#purchaseOrder').val());
                formdata.append('lcr_7', $('#lcr_7').val());
                formdata.append('lfr_31', $('#lfr_31').val());
                formdata.append('imagedata', $('#imagedata').val());
                formdata.append('publish', 'yes');
                var checkbox = $(".wizard").find("input[type=radio]");
                $.each(checkbox, function(key, val) {
                    if($(this).is(':checked') === true){
                      formdata.append($(this).attr('name'), $(this).attr('id'));
                    }
                });
			          $.ajax({
			            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			            type: 'POST',
			            url: "{{route('forklift.update', $forklift->id)}}",
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
			                window.location.href = "{{route('forklift.index')}}";
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
        $('input[name=lfr_22]').prop( "disabled", true );
        $('input[name=lfr_22]').prop( "checked", false );
        $('.steps-validation').on("change", 'input[name=lfr_21]',function(e){
          if(this.id.match('_n')){
            $('input[name=lfr_22]').prop( "disabled", true );
            $('input[name=lfr_22]').prop( "checked", false );
          }else{
            $('input[name=lfr_22]').prop( "disabled", false );
          }
        });

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

        $('.steps-validation').on('click','#input1', function(){
          $('#lfr_27, #lfr_31').val("NONE");
          $('#danger input, #lfr_31').prop( "disabled", true );
          $('#danger input[type=radio]').prop( "checked", false );
          $('#lfr_30').val("");
        });
        $('.steps-validation').on('click','#input2', function(){
          $('#lfr_32').val("NONE");
        });
        $('.steps-validation').on('change','#lfr_27', function(){
          $('#danger input, #lfr_31').prop( "disabled", false );
          $('#lfr_31').val("");
        });
    </script>
@endpush
