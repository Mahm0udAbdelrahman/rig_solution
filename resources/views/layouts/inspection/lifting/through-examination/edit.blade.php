@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')

<section id="form-repeater">
  <div class="row">
    <div class="col-12">
      <form action="#" class="steps-validation wizard-circle form emad repeater-emad"  novalidate enctype="multipart/form-data">
        @method('PUT')
        <!----------------------------------------------->
        <h6>Step 1</h6>
        <fieldset>
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row ">
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>JCF Number</label>
                            <select class="form-control" id="lcr_1" name="lcr_1" disabled>
                              <option value="{{$throughExamination->job_request->id}}" selected>{{$throughExamination->job_request->code}}</option>

                            </select>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Purchase Order</label>
                            <input disabled type="text" id="purchaseOrder" name="lter_2" class="form-control" placeholder="Purchase Order" value="{{$throughExamination->lter_2}}" >
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Report No.</label>
                            <div class="clearfix">
                              <input type="text" id="precode" class="form-control pr-0" placeholder="Report No" value="{{$throughExamination->job_request->code}} /" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled style="width: 83px; float: left;">
                              <input type="text" id="code" name="code" class="form-control pl-0" placeholder="." value="{{$throughExamination->code}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled  style="width: auto; float: left;">
                            </div>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
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
          <!-------------------------------------------------------------------------------->
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
                                <input type="text" class="form-control datepicker-default" id="lcr_6" name="lcr_6" placeholder="Examination Date" value="{{$throughExamination->lter_6}}" required/>
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
                                <input type="text" class="form-control dp-date-range-to" id="lcr_7" name="lcr_7" value="{{$throughExamination->lter_7}}" placeholder="Next Examination Date" disabled/>
                            </div>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Color Code</label>
                            <input type="text" id="lter_8" name="lter_8" class="form-control" placeholder="Color Code" value="{{$throughExamination->lter_8}}" required/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                  <div class="row">
                      <div class="col-6">
                          <div class="form-group mb-0">
                              <div class="controls">
                                  <label>Work location</label>
                                  <input type="text" id="deploc" name="deploc" class="form-control"
                                         placeholder="Work location" value="" required=""
                                         data-validation-required-message="This field is required" disabled/>
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
        <!----------------------------------------------->
        <h6>Step 2</h6>
        <fieldset>
          <div class="card contact">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="table-responsive col-12">
                    <table class="table titems">
                      <thead>
                        <tr>
                          <th class="col-md-2">Identification No</th>
                          <th class="col-md-1">Quantity</th>
                          <th class="col-md-4">Description</th>
                          <th class="col-md-2">SWL</th>
                          <th class="col-md-2">Proof Load</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="col-md-2">
                            <textarea id="lter_10" name="lter_10" class="form-control" placeholder="Identification No" rows="13" style="resize: none;">{{$throughExamination->lter_10['pop1']}}</textarea>
                          </td>
                          <td class="col-md-1">
                            <textarea id="lter_11" name="lter_11" class="form-control" placeholder="Qty" rows="13" style="resize: none;">{{$throughExamination->lter_10['pop2']}}</textarea>
                          </td>
                          <td class="col-md-4">
                            <input type="text" id="lter_120" name="lter_120" class="form-control" placeholder="Title" value="{{$throughExamination->lter_10['pop20']}}" />
                            <textarea id="lter_12" name="lter_12" class="form-control" placeholder="Description" rows="13" style="resize: none;">{{$throughExamination->lter_10['pop3']}}</textarea>
                          </td>
                          <td class="col-md-2 text-right">
                            <textarea id="lter_13" name="lter_13" class="form-control" placeholder="SWL" rows="13" style="resize: none;">{{$throughExamination->lter_10['pop4']}}</textarea>
                          </td>
                          <td class="col-md-2 text-right">
                            <textarea id="lter_14" name="lter_14" class="form-control" placeholder="Proof Load" rows="13" style="resize: none;">{{$throughExamination->lter_10['pop5']}}</textarea>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row ">
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Attached to/Location</label>
                            <input type="text" id="lter_15" name="lter_15" class="form-control" placeholder="Attached to/Location" value="{{$throughExamination->lter_15}}" required>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Tare Weight</label>
                            <input type="text" id="lter_16" name="lter_16" class="form-control" placeholder="Tare Weight" value="{{$throughExamination->lter_16}}" required>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Last examination</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ft-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control dp-date-range-from" id="lter_17" name="lter_17" placeholder="Last examination" value="{{$throughExamination->lter_17}}" required/>
                            </div>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Date of Manufacture</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ft-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control dp-date-range-from" id="lter_18" name="lter_18" placeholder="Date of Manufacture" value="{{$throughExamination->lter_18}}" required/>
                            </div>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Gross Mass</label>
                            <input type="text" id="lter_19" name="lter_19" class="form-control" placeholder="Gross Mass" value="{{$throughExamination->lter_19}}" required/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Load Tested by</label>
                            <input type="text" id="lter_20" name="lter_20" class="form-control" placeholder="Load Tested by" value="{{$throughExamination->lter_20}}" required/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Test Certificate #</label>
                            <input type="text" id="lter_21" name="lter_21" class="form-control" placeholder="Test Certificate #" value="{{$throughExamination->lter_21}}" required/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Date of Manufacture</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ft-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control dp-date-range-from" id="lter_22" name="lter_22" placeholder="Date of Manufacture" value="{{$throughExamination->lter_22}}" required/>
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
                  <div class="col-12">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Reference Standard</label>
                            <input type="text" id="lter_23" name="lter_23" class="form-control" placeholder="Reference Standard" value="{{$throughExamination->lter_23}}" required/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </fieldset>
        <!----------------------------------------------->
        <h6>Step 3</h6>
        <fieldset>

          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row mt-1">
                  <div class="col-8">
                    <p>- Is this the first examination after installation or assembly at a new site or location?</p>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_24_y " name="lter_24" {{$throughExamination->checkbox_yes($throughExamination->lter_24)}}  required>
                        <label class="custom-control-label" for="lter_24_y ">Yes</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_24_n" name="lter_24" {{$throughExamination->checkbox_no($throughExamination->lter_24)}} >
                        <label class="custom-control-label" for="lter_24_n">No</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-8">
                    <p>- If the answer to the above question is YES Has the equipment been installed correctly?</p>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_25_y " name="lter_25" {{$throughExamination->checkbox_yes($throughExamination->lter_25)}} >
                        <label class="custom-control-label" for="lter_25_y ">Yes</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_25_n" name="lter_25" {{$throughExamination->checkbox_no($throughExamination->lter_25)}} >
                        <label class="custom-control-label" for="lter_25_n">No</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
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
                        <input type="radio" class="custom-control-input" id="lter_26_y " name="lter_26" {{$throughExamination->checkbox_yes($throughExamination->lter_26)}}  required>
                        <label class="custom-control-label" for="lter_26_y ">Yes</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_26_n" {{$throughExamination->checkbox_no($throughExamination->lter_26)}}  name="lter_26">
                        <label class="custom-control-label" for="lter_26_n">No</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-8">
                    <p>- Within an interval of 12 months?</p>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_27_y " name="lter_27" {{$throughExamination->checkbox_yes($throughExamination->lter_27)}} >
                        <label class="custom-control-label" for="lter_27_y ">Yes</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_27_n" name="lter_27" {{$throughExamination->checkbox_no($throughExamination->lter_27)}} >
                        <label class="custom-control-label" for="lter_27_n">No</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-8">
                    <p>- In accordance with an examination scheme?</p>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_28_y " name="lter_28" {{$throughExamination->checkbox_yes($throughExamination->lter_28)}} >
                        <label class="custom-control-label" for="lter_28_y ">Yes</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_28_n" name="lter_28" {{$throughExamination->checkbox_no($throughExamination->lter_28)}} >
                        <label class="custom-control-label" for="lter_28_n">No</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-8">
                    <p>- After the occurrence of exceptional Circumstances?</p>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_29_y " name="lter_29" {{$throughExamination->checkbox_yes($throughExamination->lter_29)}} >
                        <label class="custom-control-label" for="lter_29_y ">Yes</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_29_n" name="lter_29" {{$throughExamination->checkbox_no($throughExamination->lter_29)}} >
                        <label class="custom-control-label" for="lter_29_n">No</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect( <button id="input1" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">if none state NONE</button> )</label>
                            <input type="text" id="lter_30" name="lter_30" class="form-control" placeholder="Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect(if none state NONE)" value="{{$throughExamination->lter_30}}" required/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <div id="danger" class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-8">
                    <p>Is the above a defect which is of immediate danger to persons?</p>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_31_y " name="lter_31" {{$throughExamination->checkbox_yes($throughExamination->lter_31)}}>
                        <label class="custom-control-label" for="lter_31_y ">Yes</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_31_n" name="lter_31" {{$throughExamination->checkbox_no($throughExamination->lter_31)}}>
                        <label class="custom-control-label" for="lter_31_n">No</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-8">
                    <p>Is the above a defect which is not yet but could become a danger to persons?</p>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_32_y " name="lter_32" {{$throughExamination->checkbox_yes($throughExamination->lter_32)}}>
                        <label class="custom-control-label" for="lter_32_y ">Yes</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_32_n" name="lter_32" {{$throughExamination->checkbox_no($throughExamination->lter_32)}}>
                        <label class="custom-control-label" for="lter_32_n">No</label>
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
                        <input type="text" class="form-control dp-date-range-from" id="lter_33" name="lter_33" value="{{$throughExamination->lter_33}}" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Particulars of any repair, renewal, or alteration   to remedy the defect identified above:</label>
                            <input type="text" id="lter_34" name="lter_34" class="form-control" placeholder="Particulars of any repair, renewal, or alteration   to remedy the defect identified above" value="{{$throughExamination->lter_34}}" required/>
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
                            <label>Particulars of any tests carried out as part of the examination( <button id="input2" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">if none state NONE</button> )</label>
                            <input type="text" id="lter_35" name="lter_35" class="form-control" placeholder="Particulars of any repair, renewal, or alteration   to remedy the defect identified above" value="{{$throughExamination->lter_35}}" required/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row mt-1">
                  <div class="col-9 mt-1 mt-sm-0">
                    <div class="form-group">
                      <div class="controls">
                          <label>Upload Image</label>
                          <div class="custom-file">
                              <input type="file" class="custom-file-input" id="lter_36" name="lter_36">
                              <label class="custom-file-label" style="height: 2.80rem;" for="lter_36" aria-describedby="lter_34">Choose file</label>
                          </div>
                      <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-2">
                      @php
                        $throughImagePath = !empty($throughExamination->lter_36)
                          ? 'camera/inspection/lifting/throughexaminations/'.$throughExamination->lter_36
                          : null;
                      @endphp
                      @if($throughImagePath && Storage::disk('public')->exists($throughImagePath))
                        <img class="media-object" src="{{ Storage::url($throughImagePath) }}" alt="Through Examination Image" width="64">
                      @else
                        <small class="text-muted">No image uploaded</small>
                      @endif
                    </div>
                    <div class=" col-1">
                      <button type="button" data-id="delete" class="btn btn-icon btn-danger mr-1 delete"><i class="la la-trash"></i></button>
                      <input type="hidden" value="{{$throughExamination->lter_36}}" id="imagedata" />

                    </div>
                </div>
              </div>
            </div>
          </div>
        </fieldset>
        <!----------------------------------------------->
        <h6>Step 4</h6>
        <fieldset>
          <h6 class="text-bold-600">MPI Details</h6>
          <p>Inspection Method and equipment used</p>
          <button id="input111" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">All Fields N/A</button>
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Standard</label>
                          <input type="text" id="lter_37" name="lter_37" class="form-control in111" placeholder="Standard" value="{{$throughExamination->lter_37}}" required="" data-validation-required-message="This field is required"/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Equipment type</label>
                          <input type="text" id="lter_38" name="lter_38" class="form-control in111" placeholder="Equipment type" value="{{$throughExamination->lter_38}}" required="" data-validation-required-message="This field is required">
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Equipment No</label>
                          <input type="text" id="lter_39" name="lter_39" class="form-control in111" placeholder="Equipment No" value="{{$throughExamination->lter_39}}" required="" data-validation-required-message="This field is required">
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Pole spacing</label>
                          <input type="text" id="lter_40" name="lter_40" class="form-control in111" placeholder="Pole spacing" value="{{$throughExamination->lter_40}}" required="" data-validation-required-message="This field is required">
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group mb-0">
                        <div class="controls">

                          <label>Due Date</label>
                          <input type="text" class="form-control in111 dp-date-range-from" id="lter_41" name="lter_41" placeholder="Due Date"  value="{{$throughExamination->lter_41}}" />

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
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Solution details</th>
                          <th scope="col">Contrast</th>
                          <th scope="col">Indicator</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">Manufacturer</th>
                          <td><input type="text" id="lter_42" name="lter_42" class="form-control in111" placeholder="Contrast/Manufacturer" value="{{$throughExamination->lter_42}}" required="" data-validation-required-message="This field is required"></td>
                          <td><input type="text" id="lter_43" name="lter_43" class="form-control in111" placeholder="Indicator/ Manufacturer" value="{{$throughExamination->lter_43}}" required="" data-validation-required-message="This field is required"></td>
                        </tr>
                        <tr>
                          <th scope="row">Expire Date</th>
                          <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ft-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control dp-date-range-to in111" id="lter_44" name="lter_44" placeholder="Expire Date" required="" value="{{$throughExamination->lter_44}}" data-validation-required-message="This field is required"/>
                            </div>
                            <div class="help-block"></div></div>
                          </td>
                          <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ft-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control dp-date-range-to in111" id="lter_45" name="lter_45" placeholder="Expire Date" required="" value="{{$throughExamination->lter_45}}" data-validation-required-message="This field is required"/>
                            </div>
                            <div class="help-block"></div></div>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>NDT Conclusion</label>
                            <input type="text" id="lter_46" name="lter_46" class="form-control in111" placeholder="NDT Conclusion" value="{{$throughExamination->lter_46}}"   />
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <h6 class="text-bold-600">Load Cell Details</h6>
          <button id="input222" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">All Fields N/A</button>

          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Range</label>
                          <input type="text" id="lter_47" name="lter_47" class="form-control in222" placeholder="Standard" value="{{$throughExamination->lter_47}}" required="" data-validation-required-message="This field is required"/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Manufacturer</label>
                          <input type="text" id="lter_48" name="lter_48" class="form-control in222" placeholder="Equipment type" value="{{$throughExamination->lter_48}}" required="" data-validation-required-message="This field is required">
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Serial No</label>
                          <input type="text" id="lter_49" name="lter_49" class="form-control in222" placeholder="Equipment No" value="{{$throughExamination->lter_49}}" required="" data-validation-required-message="This field is required">
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Calibration Due Date</label>
                          <input type="text" id="lter_50" name="lter_50" class="form-control in222" placeholder="Pole spacing" value="{{$throughExamination->lter_50}}" required="" data-validation-required-message="This field is required">
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row mt-1">
                  <div class="col-8">
                    <p>Is this equipment safe to operate?</p>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_51_y " name="lter_51" {{$throughExamination->checkbox_yes($throughExamination->lter_51)}} required>
                        <label class="custom-control-label" for="lter_51_y ">Yes</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lter_51_n" name="lter_51" {{$throughExamination->checkbox_no($throughExamination->lter_51)}}>
                        <label class="custom-control-label" for="lter_51_n">No</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
        </fieldset>
        <!----------------------------------------------->
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
              var form1 = $('.steps-validation')[0];
              var formdata = new FormData(form1);
              formdata.append('lcr_1', $('#lcr_1').val());
              formdata.append('code', $('#code').val());
              formdata.append('lcr_7', $('#lcr_7').val());
              // formdata.append('lter_9', $('#lter_9').val());
              formdata.append('new', JSON.stringify({pop1 : $('#lter_10').val(), pop20 : $('#lter_120').val(), pop2 : $('#lter_11').val(), pop3 : $('#lter_12').val(), pop4 : $('#lter_13').val(), pop5 : $('#lter_14').val(), }));
              formdata.append('lter_34', $('#lter_34').val());
            formdata.append('lter_2', $('#purchaseOrder').val());
              formdata.append('imagedata', $('#imagedata').val());
              var checkbox = $(".wizard").find("input[type=radio]");
              $.each(checkbox, function(key, val) {
                  if($(this).is(':checked') === true){
                    formdata.append($(this).attr('name'), $(this).attr('id'));
                  }
              });
			          $.ajax({
			            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			            type: 'POST',
			            url: "{{route('throughExamination.update', $throughExamination->id)}}",
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
			                window.location.replace("{{route('throughExamination.index')}}");
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
$('.steps-validation').on('click','#input111', function(){
  $('#steps-uid-0-p-3 .in111').each(function(index, value){
    $(value).val("N/A");
  });
});

$('.steps-validation').on('click','#input222', function(){
  $('#steps-uid-0-p-3 .in222').each(function(index, value){
    $(value).val("N/A");
  });
});

/************************************************/
$('input[name=lter_25]').prop( "disabled", true );
$('input[name=lter_25]').prop( "checked", false );
$('.steps-validation').on("change", 'input[name=lter_24]',function(e){
  if(this.id.match('_n')){
    $('input[name=lter_25]').prop( "disabled", true );
    $('input[name=lter_25]').prop( "checked", false );
  }else{
    $('input[name=lter_25]').prop( "disabled", false );
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
/************************************************/
$('.steps-validation').on('click','#input1', function(){
  $('#lter_30, #lter_34').val("NONE");
  $('#danger input, #lter_34').prop( "disabled", true );
  $('#danger input[type=radio]').prop( "checked", false );
  $('#lter_33').val("");
});
$('.steps-validation').on('click','#input2', function(){
  $('#lter_35').val("NONE");
});
/************************************************/
$('.steps-validation').on('change','#lter_30', function(){
  $('#danger input, #lter_34').prop( "disabled", false );
  $('#lter_34').val("");
});
</script>
@endpush
