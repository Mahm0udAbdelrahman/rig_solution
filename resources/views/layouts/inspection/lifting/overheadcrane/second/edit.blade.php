@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
    <section id="validation">
        <div class="row">
            <div class="col-12">
                <form action="#" class="steps-validation wizard-circle">
                  @method('PUT')
                  <h6>Main Wire Rope</h6>
                  <fieldset>
                      <button id="input1" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">All Fields N/A</button>
                      <div class="card">
                          <div class="card-content collapse show">
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>ID Number</label>
                                                  <input type="text" placeholder="ID Number" class="form-control" id="locr2_1" name="locr2_1" required="" data-validation-required-message="This field is required" value="{{$overheadCrane2->locr2_1}}">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Safe Working Load</label>
                                                  <input type="text" placeholder="Safe Working Load" class="form-control" id="locr2_2" name="locr2_2" required="" data-validation-required-message="This field is required" value="{{$overheadCrane2->locr2_2}}">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Condition</label>
                                                  <input type="text" placeholder="Condition" class="form-control" id="locr2_3" name="locr2_3" required="" data-validation-required-message="This field is required" value="{{$overheadCrane2->locr2_3}}">
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
                                                  <label>Description</label>
                                                  <input type="text" id="locr2_4" name="locr2_4" class="form-control" placeholder="Description" required="" data-validation-required-message="This field is required" value="{{$overheadCrane2->locr2_4}}">
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
                                                  <label>Name of certifying body</label>
                                                  <input type="text" id="locr2_5" name="locr2_5" class="form-control" placeholder="Name of certifying body" value="{{$overheadCrane2->locr2_5}}" required="" data-validation-required-message="This field is required" >
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Certificate Number</label>
                                                  <input type="text" id="locr2_6" name="locr2_6" class="form-control" placeholder="Certificate Number" value="{{$overheadCrane2->locr2_6}}" required="" data-validation-required-message="This field is required">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Date of Test</label>
                                                  <div class="input-group">
                                                      <div class="input-group-prepend">
                                                          <span class="input-group-text"><i class="ft-calendar"></i></span>
                                                      </div>
                                                      <input type="text" class="form-control dp-date-range-from" id="locr2_7" name="locr2_7" placeholder="Date of Test" required="" data-validation-required-message="This field is required" value="{{$overheadCrane2->locr2_7}}"/>
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
                                      <div class="col-12">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Particulars of defects found which are or could become a danger to person or none</label>
                                                  <input type="text" id="locr2_8" name="locr2_8" class="form-control" placeholder="Particulars of defects found which are or could become a danger to person or none" value="{{$overheadCrane2->locr2_8}}"  required="" data-validation-required-message="This field is required"/>
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </fieldset>
                  <h6>Main Block/Hook</h6>
                  <fieldset>
                      <button id="input2" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">All Fields N/A</button>
                      <div class="card">
                          <div class="card-content collapse show">
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>ID Number</label>
                                                  <input type="text" placeholder="ID Number" class="form-control" id="locr2_9" name="locr2_9" required="" value="{{$overheadCrane2->locr2_9}}" data-validation-required-message="This field is required">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Safe Working Load</label>
                                                  <input type="text" placeholder="Safe Working Load" class="form-control" id="locr2_10" name="locr2_10" value="{{$overheadCrane2->locr2_10}}" required="" data-validation-required-message="This field is required">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Condition</label>
                                                  <input type="text" placeholder="Condition" class="form-control" id="locr2_11" name="locr2_11" required="" value="{{$overheadCrane2->locr2_11}}" data-validation-required-message="This field is required">
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
                                                  <label>Description</label>
                                                  <input type="text" id="locr2_12" name="locr2_12" class="form-control" value="{{$overheadCrane2->locr2_12}}" placeholder="Description"  required="" data-validation-required-message="This field is required">
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
                                                  <label>Name of certifying body</label>
                                                  <input type="text" id="locr2_13" name="locr2_13" class="form-control" placeholder="Name of certifying body" value="{{$overheadCrane2->locr2_13}}" required="" data-validation-required-message="This field is required" >
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Certificate Number</label>
                                                  <input type="text" id="locr2_14" name="locr2_14" class="form-control" placeholder="Certificate Number" value="{{$overheadCrane2->locr2_14}}" required="" data-validation-required-message="This field is required" >
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Date of Test</label>
                                                  <div class="input-group">
                                                      <div class="input-group-prepend">
                                                          <span class="input-group-text"><i class="ft-calendar"></i></span>
                                                      </div>
                                                      <input type="text" class="form-control dp-date-range-from" id="locr2_15" name="locr2_15" placeholder="Date of Test" required="" value="{{$overheadCrane2->locr2_15}}" data-validation-required-message="This field is required"/>
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
                              <div class="col-12">
                                <div class="form-group mb-0">
                                    <div class="controls">
                                        <label>Particulars of defects found which are or could become a danger to person or none</label>
                                        <input type="text" id="locr2_16" name="locr2_16" class="form-control" placeholder="Particulars of defects found which are or could become a danger to person or none" value="{{$overheadCrane2->locr2_16}}" required="" data-validation-required-message="This field is required"/>
                                    <div class="help-block"></div></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </fieldset>
                  <h6>Auxiliary wire rope</h6>
                  <fieldset>
                      <button id="input3" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">All Fields N/A</button>
                      <div class="card">
                          <div class="card-content collapse show">
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>ID Number</label>
                                                  <input type="text" placeholder="ID Number" class="form-control" id="locr2_17" name="locr2_17" value="{{$overheadCrane2->locr2_17}}" required="" data-validation-required-message="This field is required">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Safe Working Load</label>
                                                  <input type="text" placeholder="Safe Working Load" class="form-control" id="locr2_18" name="locr2_18" value="{{$overheadCrane2->locr2_18}}" required="" data-validation-required-message="This field is required">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Condition</label>
                                                  <input type="text" placeholder="Condition" class="form-control" id="locr2_19" name="locr2_19" required="" value="{{$overheadCrane2->locr2_19}}" data-validation-required-message="This field is required">
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
                                                  <label>Description</label>
                                                  <input type="text" id="locr2_20" name="locr2_20" class="form-control" value="{{$overheadCrane2->locr2_20}}" placeholder="Description"  required="" data-validation-required-message="This field is required">
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
                                                <label>Name of certifying body</label>
                                                <input type="text" id="locr2_21" name="locr2_21" class="form-control" placeholder="Name of certifying body" value="{{$overheadCrane2->locr2_21}}"  required="" data-validation-required-message="This field is required">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                      <div class="form-group mb-0">
                                          <div class="controls">
                                              <label>Certificate Number</label>
                                              <input type="text" id="locr2_22" name="locr2_22" class="form-control" placeholder="Certificate Number" value="{{$overheadCrane2->locr2_22}}" required="" data-validation-required-message="This field is required" >
                                              <div class="help-block"></div>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group mb-0">
                                            <div class="controls">
                                                <label>Date of Test</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="ft-calendar"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control dp-date-range-from" id="locr2_23" name="locr2_23" placeholder="Date of Test" value="{{$overheadCrane2->locr2_23}}" required="" data-validation-required-message="This field is required"/>
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
                                      <div class="col-12">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Particulars of defects found which are or could become a danger to person or none</label>
                                                  <input type="text" id="locr2_24" name="locr2_24" class="form-control" placeholder="Particulars of defects found which are or could become a danger to person or none" value="{{$overheadCrane2->locr2_24}}" required="" data-validation-required-message="This field is required"  />
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </fieldset>
                  <h6>Auxiliary Hook</h6>
                  <fieldset>
                      <button id="input4" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">All Fields N/A</button>
                      <div class="card">
                          <div class="card-content collapse show">
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>ID Number</label>
                                                  <input type="text" placeholder="ID Number" class="form-control" id="locr2_25" name="locr2_25" required="" value="{{$overheadCrane2->locr2_25}}" data-validation-required-message="This field is required">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Safe Working Load</label>
                                                  <input type="text" placeholder="Safe Working Load" class="form-control" id="locr2_26" name="locr2_26" required="" value="{{$overheadCrane2->locr2_26}}" data-validation-required-message="This field is required">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Condition</label>
                                                  <input type="text" placeholder="Condition" class="form-control" id="locr2_27" name="locr2_27" required="" value="{{$overheadCrane2->locr2_27}}" data-validation-required-message="This field is required">
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
                                                  <label>Description</label>
                                                  <input type="text" id="locr2_28" name="locr2_28" class="form-control" value="{{$overheadCrane2->locr2_28}}" placeholder="Description"  required="" data-validation-required-message="This field is required">
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
                                                  <label>Name of certifying body</label>
                                                  <input type="text" id="locr2_29" name="locr2_29" class="form-control" placeholder="Name of certifying body" value="{{$overheadCrane2->locr2_29}}"  required="" data-validation-required-message="This field is required">
                                              <div class="help-block"></div></div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Certificate Number</label>
                                                  <input type="text" id="locr2_30" name="locr2_30" class="form-control" placeholder="Certificate Number" value="{{$overheadCrane2->locr2_30}}"  required="" data-validation-required-message="This field is required">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-4">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Date of Test</label>
                                                  <div class="input-group">
                                                      <div class="input-group-prepend">
                                                          <span class="input-group-text"><i class="ft-calendar"></i></span>
                                                      </div>
                                                      <input type="text" class="form-control dp-date-range-from" id="locr2_31" name="locr2_31" placeholder="Date of Test" required="" value="{{$overheadCrane2->locr2_31}}" data-validation-required-message="This field is required"/>
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
                                      <div class="col-12">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Particulars of defects found which are or could become a danger to person or none</label>
                                                  <input type="text" id="locr2_32" name="locr2_32" class="form-control" placeholder="Particulars of defects found which are or could become a danger to person or none" value="{{$overheadCrane2->locr2_32}}" required="" data-validation-required-message="This field is required"/>
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </fieldset>
                  <h6>MPI Details</h6>
                  <fieldset>
                      <h6 class="text-bold-600">MPI Details</h6>
                      <p>Inspection Method and equipment used</p>
                      <button id="input5" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">All Fields N/A</button>
                      <div class="card">
                          <div class="card-content collapse show">
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-12 col-sm-3">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Standard</label>
                                                  <input type="text" id="locr2_33" name="locr2_33" class="form-control" placeholder="Standard" value="{{$overheadCrane2->locr2_33}}" required="" data-validation-required-message="This field is required"/>
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-3">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Equipment type</label>
                                                  <input type="text" id="locr2_34" name="locr2_34" class="form-control" placeholder="Equipment type" value="{{$overheadCrane2->locr2_34}}" required="" data-validation-required-message="This field is required">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-3">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Equipment No</label>
                                                  <input type="text" id="locr2_35" name="locr2_35" class="form-control" placeholder="Equipment No" value="{{$overheadCrane2->locr2_35}}" required="" data-validation-required-message="This field is required">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-12 col-sm-3">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Pole spacing</label>
                                                  <input type="text" id="locr2_36" name="locr2_36" class="form-control" placeholder="Pole spacing" value="{{$overheadCrane2->locr2_36}}" required="" data-validation-required-message="This field is required">
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
                                                  <label>Due Date</label>
                                                  <input type="text" class="form-control dp-date-range-from" id="locr2_37" name="locr2_37" placeholder="Due Date" value="{{$overheadCrane2->locr2_37}}" />
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
                                                      <td><input type="text" id="locr2_38" name="locr2_38" class="form-control" placeholder="Contrast/Manufacturer" value="{{$overheadCrane2->locr2_38}}" required="" data-validation-required-message="This field is required"></td>
                                                      <td><input type="text" id="locr2_39" name="locr2_39" class="form-control" placeholder="Indicator/ Manufacturer" value="{{$overheadCrane2->locr2_39}}" required="" data-validation-required-message="This field is required"></td>
                                                  </tr>
                                                  <tr>
                                                      <th scope="row">Expire Date</th>
                                                      <td><input type="text" id="locr2_40" name="locr2_40" class="form-control" placeholder="Contrast/Expire Date" value="{{$overheadCrane2->locr2_40}}" required="" data-validation-required-message="This field is required"></td>
                                                      <td><input type="text" id="locr2_41" name="locr2_41" class="form-control" placeholder="Indicator/ Expire Date" value="{{$overheadCrane2->locr2_41}}" required="" data-validation-required-message="This field is required"></td>
                                                  </tr>
                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </fieldset>
                  <h6>Final Conclusion</h6>
                  <fieldset>
                      <div class="card">
                          <div class="card-content collapse show">
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-12">
                                          <div class="form-group mb-0">
                                              <div class="controls">
                                                  <label>Final Conclusion</label>
                                                  <textarea id="locr2_42" name="locr2_42" class="form-control" placeholder="Final Conclusion" required="" data-validation-required-message="This field is required">{{$overheadCrane2->locr2_42}}</textarea>
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
                                                  <label>Guide Instructions</label>
                                                  <textarea id="locr2_43" name="locr2_43" class="form-control" placeholder="Guide Instructions" required="" data-validation-required-message="This field is required">{{$overheadCrane2->locr2_43}}</textarea>
                                                  <div class="help-block"></div>
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
    <script>
        $('.steps-validation').on('click','#input1', function(){
          $('#steps-uid-0-p-0 input[type=text]').each(function(index, value){
            $(value).val("N/A");
          });
        });

        $('.steps-validation').on('click','#input2', function(){
          $('#steps-uid-0-p-1 input[type=text]').each(function(index, value){
            $(value).val("N/A");
          });
        });

        $('.steps-validation').on('click','#input3', function(){
          $('#steps-uid-0-p-2 input[type=text]').each(function(index, value){
            $(value).val("N/A");
          });
        });

        $('.steps-validation').on('click','#input4', function(){
          $('#steps-uid-0-p-3 input[type=text]').each(function(index, value){
            $(value).val("N/A");
          });
        });

        $('.steps-validation').on('click','#input5', function(){
          $('#steps-uid-0-p-4 input[type=text]').each(function(index, value){
            $(value).val("N/A");
          });
        });
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
								//alert("Submitted!");
								var form1 = $('.wizard')[0];
								var formdata = new FormData(form1);
                var checkbox = $(".wizard").find("input[type=radio]");
                $.each(checkbox, function(key, val) {
                    if($(this).is(':checked') === true){
                      formdata.append($(this).attr('name'), $(this).attr('id'));
                    }
                });
								$.ajax({
									headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
									type: 'POST',
									url: "{{route('overhead_crane2_edit.update', $overheadCrane)}}",
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
										}  });
									},
								});
						}
				});
    </script>
@endprepend('child-scripts')
