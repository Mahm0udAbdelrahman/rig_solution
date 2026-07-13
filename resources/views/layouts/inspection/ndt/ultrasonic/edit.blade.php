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
                                <option value="{{$ultrasonic->job_request->id}}" selected>{{$ultrasonic->job_request->code}}</option>
                            </select>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Purchase Order</label>
                            <input disabled type="text" id="purchaseOrder" name="nur_2" class="form-control" placeholder="Purchase Order" value="{{$ultrasonic->nur_2}}" >
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Report No.</label>
                            <div class="clearfix">
                              <input type="text" id="precode" class="form-control pr-0" placeholder="Report No" value="{{$ultrasonic->job_request->code}} /" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled style="width: 83px; float: left;">
                              <input type="text" id="code" name="code" class="form-control pl-0" placeholder="." value="{{$ultrasonic->code}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled  style="width: auto; float: left;">
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
                                <input type="text" class="form-control dp-date-range-from" id="nur_6" name="nur_6" placeholder="Examination Date" data-validation--message="This field is " value="{{$ultrasonic->nur_6}}"/>
                            </div>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Work location</label>
                            <input type="text" id="deploc" name="deploc" class="form-control" placeholder="Work location" value="" required="" data-validation-required-message="This field is required" disabled />
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  {{--<div class="col-12 col-sm-4">--}}
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
                            <input type="checkbox" class="nur_8" name="nur_8"  id="{{strtolower(str_replace(' ', '-', $specification->name))}}"
                             @if(in_array(strtolower(str_replace(' ', '-', $specification->name)), json_decode($ultrasonic->nur_8))) checked @endif
                            >
                            <label for="{{strtolower(str_replace(' ', '-', $specification->name))}}">{{$specification->name}}</label>
                          </fieldset>
                        </div>
                      @endforeach
                    </div>
                    <hr />
                    <div class="row">
                      <div class="col-12 col-sm-6">
                        <div class="form-group">
                          <div class="controls">
                            <label>If Other</label>
                            <input type="text" id="nur_9" name="nur_9" class="form-control" placeholder="If Other" value="{{$ultrasonic->nur_9}}" data-validation--message="This field is "  />
                            <div class="help-block"></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-6">
                        <div class="form-group">
                          <div class="controls">
                            <label>Edition</label>
                            <input type="text" id="nur_10" name="nur_10" class="form-control" placeholder="Edition" value="{{$ultrasonic->nur_10}}" data-validation--message="This field is " />
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
          <h6 class="mb-1">Acceptance Criteria</h6>
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <div class="controls">
                        <input type="text" id="nur_11" name="nur_11" class="form-control" placeholder="Acceptance Criteria" value="{{$ultrasonic->acceptance}}" data-validation--message="This field is "/>
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
          <h6 class="mb-1">Equipment and Technique</h6>
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Model</label>
                        <input type="text" id="nur_12" name="nur_12" class="form-control" placeholder="Model" value="{{$ultrasonic->nur_12}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Serial Number</label>
                        <input type="text" id="nur_13" name="nur_13" class="form-control" placeholder="Serial Number" value="{{$ultrasonic->nur_13}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Manufacturer</label>
                        <input type="text" id="nur_14" name="nur_14" class="form-control" placeholder="Manufacturer" value="{{$ultrasonic->nur_14}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Couplant Type</label>
                        <input type="text" id="nur_15" name="nur_15" class="form-control" placeholder="Couplant Type" value="{{$ultrasonic->nur_15}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Cable Type</label>
                        <input type="text" id="nur_16" name="nur_16" class="form-control" placeholder="Cable Type" value="{{$ultrasonic->nur_16}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Calibration Block</label>
                        <input type="text" id="nur_17" name="nur_17" class="form-control" placeholder="Calibration Block" value="{{$ultrasonic->nur_17}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Search Unit</label>
                        <input type="text" id="nur_18" name="nur_18" class="form-control" placeholder="Search Unit" value="{{$ultrasonic->nur_18}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Manufacturer</label>
                        <input type="text" id="nur_19" name="nur_19" class="form-control" placeholder="Manufacturer" value="{{$ultrasonic->nur_19}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Technique</label>
                        <input type="text" id="nur_20" name="nur_20" class="form-control" placeholder="Technique" value="{{$ultrasonic->nur_20}}" data-validation--message="This field is " />
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
                  <div class="col-12 skin skin-square">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Probe angle</th>
                          <th scope="col">
                            <fieldset>
                              <input type="checkbox" class="eu" name="nur_120" id="nur_120" {{$ultrasonic->checkMtvalue('nur_21', 'nur_21')}} />
                              <label for="nur_120">0 <sup>0</sup></label>
                            </fieldset>
                          </th>
                          <th scope="col">
                            <fieldset>
                              <input type="checkbox" class="eu" name="nur_121" id="nur_121" {{$ultrasonic->checkMtvalue('nur_22', 'nur_21')}} />
                              <label for="nur_121">45 <sup>0</sup></label>
                            </fieldset>
                          </th>
                          <th scope="col">
                            <fieldset>
                              <input type="checkbox" class="eu" name="nur_122" id="nur_122" {{$ultrasonic->checkMtvalue('nur_23', 'nur_21')}} />
                              <label for="nur_122">60 <sup>0</sup></label>
                            </fieldset>
                          </th>
                          <th scope="col">
                            <fieldset>
                              <input type="checkbox" class="eu" name="nur_123" id="nur_123" {{$ultrasonic->checkMtvalue('nur_24', 'nur_21')}} />
                              <label for="nur_123">70 <sup>0</sup></label>
                            </fieldset>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">Serial No</th>
                          <td><input type="text" id="nur_21" name="nur_21" class="form-control magnet mt" placeholder="Serial No" value="{{$ultrasonic->getMtvalue('nur_21', 'nur_21')}}" data-validation--message="This field is" /></td>
                          <td><input type="text" id="nur_22" name="nur_22" class="form-control uvl mt" placeholder="Serial No" value="{{$ultrasonic->getMtvalue('nur_22', 'nur_21')}}" data-validation--message="This field is " /></td>
                          <td><input type="text" id="nur_23" name="nur_23" class="form-control coil mt" placeholder="Serial No" value="{{$ultrasonic->getMtvalue('nur_23', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_24" name="nur_24" class="form-control calat mt" placeholder="Serial No" value="{{$ultrasonic->getMtvalue('nur_24', 'nur_21')}}" data-validation--message="This field is "  /></td>
                        </tr>
                        <tr>
                          <th scope="row">Dimension</th>
                          <td><input type="text" id="nur_25" name="nur_25" class="form-control magnet mt" placeholder="Dimension" value="{{$ultrasonic->getMtvalue('nur_25', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_26" name="nur_26" class="form-control uvl mt" placeholder="Dimension" value="{{$ultrasonic->getMtvalue('nur_26', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_27" name="nur_27" class="form-control coil mt" placeholder="Dimension" value="{{$ultrasonic->getMtvalue('nur_27', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_28" name="nur_28" class="form-control calat mt" placeholder="Dimension" value="{{$ultrasonic->getMtvalue('nur_28', 'nur_21')}}" data-validation--message="This field is "  /></td>
                        </tr>
                        <tr>
                          <th scope="row">Frequency</th>
                          <td><input type="text" id="nur_29" name="nur_29" class="form-control magnet mt" placeholder="Frequency" value="{{$ultrasonic->getMtvalue('nur_29', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_30" name="nur_30" class="form-control uvl mt" placeholder="Frequency" value="{{$ultrasonic->getMtvalue('nur_30', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_31" name="nur_31" class="form-control coil mt" placeholder="Frequency" value="{{$ultrasonic->getMtvalue('nur_31', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_32" name="nur_32" class="form-control calat mt" placeholder="Frequency" value="{{$ultrasonic->getMtvalue('nur_32', 'nur_21')}}" data-validation--message="This field is "  /></td>
                        </tr>
                        <tr>
                          <th scope="row">Sensitivity</th>
                          <td><input type="text" id="nur_33" name="nur_33" class="form-control magnet mt" placeholder="Sensitivity" value="{{$ultrasonic->getMtvalue('nur_33', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_34" name="nur_34" class="form-control uvl mt" placeholder="Sensitivity" value="{{$ultrasonic->getMtvalue('nur_34', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_35" name="nur_35" class="form-control coil mt" placeholder="Sensitivity" value="{{$ultrasonic->getMtvalue('nur_35', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_36" name="nur_36" class="form-control calat mt" placeholder="Sensitivity" value="{{$ultrasonic->getMtvalue('nur_36', 'nur_21')}}" data-validation--message="This field is "  /></td>
                        </tr>
                        <tr>
                          <th scope="row">Reference Gain</th>
                          <td><input type="text" id="nur_37" name="nur_37" class="form-control magnet mt" placeholder="Reference Gain" value="{{$ultrasonic->getMtvalue('nur_37', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_38" name="nur_38" class="form-control uvl mt" placeholder="Reference Gain" value="{{$ultrasonic->getMtvalue('nur_38', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_39" name="nur_39" class="form-control coil mt" placeholder="Reference Gain" value="{{$ultrasonic->getMtvalue('nur_39', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_40" name="nur_40" class="form-control calat mt" placeholder="Reference Gain" value="{{$ultrasonic->getMtvalue('nur_40', 'nur_21')}}" data-validation--message="This field is "  /></td>
                        </tr>
                        <tr>
                          <th scope="row">Range</th>
                          <td><input type="text" id="nur_41" name="nur_41" class="form-control magnet mt" placeholder="Range" value="{{$ultrasonic->getMtvalue('nur_41', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_42" name="nur_42" class="form-control uvl mt" placeholder="Range" value="{{$ultrasonic->getMtvalue('nur_42', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_43" name="nur_43" class="form-control coil mt" placeholder="Range" value="{{$ultrasonic->getMtvalue('nur_43', 'nur_21')}}" data-validation--message="This field is "  /></td>
                          <td><input type="text" id="nur_44" name="nur_44" class="form-control calat mt" placeholder="Range" value="{{$ultrasonic->getMtvalue('nur_44', 'nur_21')}}" data-validation--message="This field is "  /></td>
                        </tr>
                      </tbody>
                    </table>
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
                    <p>Calibration Sheet Attached</p>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="nur_45_y" name="nur_45" {{$ultrasonic->checkbox_yes($ultrasonic->nur_22)}} required>
                        <label class="custom-control-label" for="nur_45_y">Yes</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="nur_45_n" name="nur_45" {{$ultrasonic->checkbox_no($ultrasonic->nur_22)}}>
                        <label class="custom-control-label" for="nur_45_n">No</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Comment</label>
                        <textarea id="nur_46" name="nur_46" class="form-control" placeholder="">{{$ultrasonic->nur_23}}</textarea>
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
                  <div class="col-9">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Sketch</label>
                          <div class="custom-file">
                              <input type="file" name="nur_47" class="custom-file-input" id="nur_47">
                              <label class="custom-file-label" style="height: 2.80rem;" for="nur_47" aria-describedby="inputGroupFile02">Choose file</label>
                          </div>
                          <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-2">
                      <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{Storage::url('camera/inspection/ndt/ultrasonic/')}}{{$ultrasonic->nur_24}}" alt="" />
                  </div>
                  <div class="col-1 mt-1 mt-sm-0">
                      <button type="button" data-id="delete" class="btn btn-icon btn-danger mr-1 delete"><i class="la la-trash"></i></button>
                      <input type="hidden" value="{{$ultrasonic->nur_24}}" name="imagedata" />
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
                        <textarea id="nur_48" name="nur_48" class="form-control" placeholder="Description">{{$ultrasonic->desc}}</textarea>
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
                        <input type="text" id="nur_49" name="nur_49" class="form-control" placeholder="Identification No" value="{{$ultrasonic->nur_26}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Welding Process</label>
                        <input type="text" id="nur_50" name="nur_50" class="form-control" placeholder="Welding Process" value="{{$ultrasonic->nur_27}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Material</label>
                        <input type="text" id="nur_51" name="nur_51" class="form-control" placeholder="Material" value="{{$ultrasonic->nur_28}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Material Thickness</label>
                        <input type="text" id="nur_52" name="nur_52" class="form-control" placeholder="Material Thickness" value="{{$ultrasonic->nur_29}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Surface Condition</label>
                        <input type="text" id="nur_53" name="nur_53" class="form-control" placeholder="Surface Condition" value="{{$ultrasonic->nur_30}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Surface Temperature</label>
                        <input type="text" id="nur_54" name="nur_54" class="form-control" placeholder="Surface Temperature" value="{{$ultrasonic->nur_31}}" data-validation--message="This field is " />
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Light Intensity</label>
                        <input type="text" id="nur_55" name="nur_55" class="form-control" placeholder="Light Intensity" value="{{$ultrasonic->nur_32}}" data-validation--message="This field is " />
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
          <div class="card repeater">
            <div class="card-content collapse show">
              <div class="card-body" data-repeater-list="payments">
                <!-- -->
                <div class="row">
                  <div class="col-1">
                    <div class="form-group mb-0">
                      <label>Drawing Number</label>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group mb-0">
                      <label>Item</label>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="form-group mb-0">
                      <label>Joint No</label>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="form-group mb-0">
                      <label>Welder No</label>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="form-group mb-0">
                      <label>Tested Length</label>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="form-group mb-0">
                      <label>Evaluation</label>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="form-group mb-0">
                      <label>Result</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group mb-0">
                      <label>Remarks</label>
                    </div>
                  </div>
                  <div class=" col-1"></div>
                </div>

                @foreach(json_decode($ultrasonic->nur_33) as $value)
                <div class="row" data-repeater-item>
                  <div class="col-1">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <input type="text" id="nur_56" name="nur_56" class="form-control" placeholder="Drawing Number" value="{{$value->nur_56}}" required="" data-validation-required-message="This field is required"/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <input type="text" id="nur_57" name="nur_57" class="form-control" placeholder="Item" value="{{$value->nur_57}}" required="" data-validation-required-message="This field is required"/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <input type="text" id="nur_58" name="nur_58" class="form-control" placeholder="Joint No" value="{{$value->nur_58}}" required="" data-validation-required-message="This field is required"/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <input type="text" id="nur_59" name="nur_59" class="form-control" placeholder="Welder No" value="{{$value->nur_59}}" required="" data-validation-required-message="This field is required"/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <input type="text" id="nur_60" name="nur_60" class="form-control" placeholder="Tested Length" value="{{$value->nur_60}}" required="" data-validation-required-message="This field is required"/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <input type="text" id="nur_61" name="nur_61" class="form-control" placeholder="Evaluation" value="{{$value->nur_61}}" required="" data-validation-required-message="This field is required"/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <input type="text" id="nur_62" name="nur_62" class="form-control" placeholder="Result" value="{{$value->nur_62}}" required="" data-validation-required-message="This field is required"/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <input type="text" id="nur_63" name="nur_63" class="form-control" placeholder="Remarks" value="{{$value->nur_63}}" required="" data-validation-required-message="This field is required"/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class=" col-1">
                    <button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
                  </div>
                </div>
                @endforeach
                <!-- -->
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
                                      <!-- <textarea id="nur_64" name="nur_64" class="form-control" placeholder="Final Conclusion"></textarea> -->
                                      <div class="row">

                                          <div class="col-8">
                                              <p>NDT result : Accept / Reject ?</p>
                                          </div>
                                          <div class="col-2">
                                              <div class="custom-control custom-radio">
                                                  <input type="radio" class="custom-control-input" id="nur_64_y " name="nur_64" {{$ultrasonic->checkbox_yes($ultrasonic->nur_34)}}  required>
                                                  <label class="custom-control-label" for="nur_64_y ">Accept</label>
                                              </div>
                                          </div>
                                          <div class="col-2">
                                              <div class="custom-control custom-radio">
                                                  <input type="radio" class="custom-control-input" id="nur_64_n" name="nur_64" {{$ultrasonic->checkbox_no($ultrasonic->nur_34)}}>
                                                  <label class="custom-control-label" for="nur_64_n">Reject</label>
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
                // nmpr_12.push({
                // id:'nmpr_34',
                // value: nmpr_34
                // });
                var form1 = $('.wizard')[0];
                var formdata = new FormData(form1);
                var checkbox = $(".wizard").find("input[type=radio]");
                formdata.append('lcr_1', $('#lcr_1').val());
                formdata.append('code', $('#code').val());
                formdata.append('nur_2', $('#purchaseOrder').val());
                formdata.append('nur_8', JSON.stringify(nur_8));
                formdata.append('nur_102', JSON.stringify(nur_102));
                $.each(checkbox, function(key, val) {
                    if($(this).is(':checked') === true){
                      formdata.append($(this).attr('name'), $(this).attr('id'));
                    }
                });
			          $.ajax({
			            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			            type: 'POST',
                  url: "{{route('ultrasonic.update', $ultrasonic->id)}}",
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
			                window.location.replace("{{route('ultrasonic.index')}}");
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
// get_report_data_for_update($('#lcr_1 option:selected').val(), $('#lcr_1 option:selected').text());
// var nur_8 = [];
// var mt = {};
// var nur_102 = [];
// /**************************************************************/
// $.each($('.nur_8'), function(value) {
// 	if ($(this).is(':checked')){
// 		var id = this.id;
// 		if(id === 'other'){
// 			$('#nur_9').prop("disabled", false);
// 		}
// 		nur_8.push(id);
// 	}
// });
//
//
// $('.steps-validation').on('ifChecked','.nur_8', function(){
//   var id = this.id;
//   if(id === 'other'){
//     $('#nur_9').prop("disabled", false);
//   }
//   nur_8.push(id);
// });
// $('.steps-validation').on('ifUnchecked','.nur_8', function(event){
//   var id = this.id;
//   if(id === 'other'){
//     $('#nur_9').prop("disabled", true).val('');
//   }
//   index1 = nur_8.indexOf(id);
//   nur_8.splice(index1, 1);
// });
// /***************************************************************/
// if ($("#nur_120").is(':checked')){
//
// 	$.each($('.magnet'), function(value) {
//
// 		if(this.value !== ''){
// 			mt = {};
// 			mt.id = this.id;
// 			mt.value = this.value;
// 			nur_102.push(mt);
// 		}
//
// 		$(this).prop("disabled", false);
//
// 	});
//
//   console.log(nur_102);
// }else{
// 	$("#nur_120").prop("disabled", false);
// }
//
//
// if ($("#nur_121").is(':checked')){
//
// 	$.each($('.uvl'), function(value) {
//
// 		if(this.value !== ''){
// 			mt = {};
// 			mt.id = this.id;
// 			mt.value = this.value;
// 			nur_102.push(mt);
// 		}
//
// 		$(this).prop("disabled", false);
//
// 	});
//
// }else{
// 	$("#nur_121").prop("disabled", false);
// }
//
// if ($("#nur_122").is(':checked')){
//
// 	$.each($('.coil'), function(value) {
//
// 		if(this.value !== ''){
// 			mt = {};
// 			mt.id = this.id;
// 			mt.value = this.value;
// 			nur_102.push(mt);
// 		}
//
// 		$(this).prop("disabled", false);
//
// 	});
//
// }else{
// 	$("#nur_122").prop("disabled", false);
// }
//
// if ($("#nur_123").is(':checked')){
//
// 	$.each($('.calat'), function(value) {
//
// 		if(this.value !== ''){
// 			mt = {};
// 			mt.id = this.id;
// 			mt.value = this.value;
// 			nur_102.push(mt);
// 		}
//
// 		$(this).prop("disabled", false);
//
// 	});
//
// }else{
// 	$("#nur_123").prop("disabled", false);
// }
//
// $('.steps-validation').on('ifChecked','#nur_120', function(){
//   $.each($('.magnet'), function(value) {
//     $(this).prop("disabled", false);
//   });
// });
// $('.steps-validation').on('ifUnchecked','#nur_120', function(event){
//   $.each($('.magnet'), function(value) {
//     $(this).prop("disabled", true).val('');
//   });
// });
//
// $('.steps-validation').on('ifChecked','#nur_121', function(){
//   $.each($('.uvl'), function(value) {
//     $(this).prop("disabled", false);
//   });
// });
// $('.steps-validation').on('ifUnchecked','#nur_121', function(event){
//   $.each($('.uvl'), function(value) {
//     $(this).prop("disabled", true).val('');
//   });
// });
//
// $('.steps-validation').on('ifChecked','#nur_122', function(){
//   $.each($('.coil'), function(value) {
//     $(this).prop("disabled", false);
//   });
// });
// $('.steps-validation').on('ifUnchecked','#nur_122', function(event){
//   $.each($('.coil'), function(value) {
//     $(this).prop("disabled", true).val('');
//   });
// });
//
// $('.steps-validation').on('ifChecked','#nur_123', function(){
//   $.each($('.calat'), function(value) {
//     $(this).prop("disabled", false);
//   });
// });
// $('.steps-validation').on('ifUnchecked','#nur_123', function(event){
//   $.each($('.calat'), function(value) {
//     $(this).prop("disabled", true).val('');
//   });
// });
// $('.steps-validation').on('change','.mt', function(){
//   index1 = nur_102.map(function(e) { return e.id; }).indexOf(this.id);
//   if(this.value !== ''){
//     var mt = {};
//     mt.id = this.id;
//     mt.value = this.value;
//     if(index1 >= 0){
//       nur_102.splice(index1, 1);
//     }
//     nur_102.push(mt);
//   }else{
//     nur_102.splice(index1, 1);
//   }
// });
// if ($("#nur_121").is(':checked')){
//
// 	$.each($('.uvl'), function(value) {
// 		if(this.value !== ''){
// 			mt.id = this.id;
// 			mt.value = this.value;
// 			nur_102.push(mt);
// 		}
// 		$(this).prop("disabled", false);
// 	});
// }else{
// 	$("#nur_121").prop("disabled", false);
// }
//
// if ($("#nur_122").is(':checked')){
//
// 	$.each($('.coil'), function(value) {
// 		if(this.value !== ''){
// 			mt.id = this.id;
// 			mt.value = this.value;
// 			nur_102.push(mt);
// 		}
// 		$(this).prop("disabled", false);
// 	});
// }else{
// 	$("#nur_122").prop("disabled", false);
// }
//
// if ($("#nur_123").is(':checked')){
//
// 	$.each($('.calat'), function(value) {
// 		if(this.value !== ''){
// 			mt.id = this.id;
// 			mt.value = this.value;
// 			nur_102.push(mt);
// 		}
// 		$(this).prop("disabled", false);
// 	});
// }else{
// 	$("#nur_123").prop("disabled", false);
// }


// $('.steps-validation').on('ifChecked','#nur_120', function(){
//   $.each($('.magnet'), function(value) {
//     $(this).prop("disabled", false);
//   });
// });
// $('.steps-validation').on('ifUnchecked','#nur_120', function(event){
//   $.each($('.magnet'), function(value) {
//     $(this).prop("disabled", true).val('');
//   });
// });
//
// $('.steps-validation').on('ifChecked','#nur_121', function(){
//   $.each($('.uvl'), function(value) {
//     $(this).prop("disabled", false);
//   });
// });
// $('.steps-validation').on('ifUnchecked','#nur_121', function(event){
//   $.each($('.uvl'), function(value) {
//     $(this).prop("disabled", true).val('');
//   });
// });
//
// $('.steps-validation').on('ifChecked','#nur_122', function(){
//   $.each($('.coil'), function(value) {
//     $(this).prop("disabled", false);
//   });
// });
// $('.steps-validation').on('ifUnchecked','#nur_122', function(event){
//   $.each($('.coil'), function(value) {
//     $(this).prop("disabled", true).val('');
//   });
// });
//
// $('.steps-validation').on('ifChecked','#nur_123', function(){
//   $.each($('.calat'), function(value) {
//     $(this).prop("disabled", false);
//   });
// });
// $('.steps-validation').on('ifUnchecked','#nur_123', function(event){
//   $.each($('.calat'), function(value) {
//     $(this).prop("disabled", true).val('');
//   });
// });
// /****************************************************************/
// $('.steps-validation').on('change','.mt', function(){
//   index1 = nur_102.map(function(e) { return e.id; }).indexOf(this.id);
//   if(this.value !== ''){
//     var mt = {};
//     mt.id = this.id;
//     mt.value = this.value;
//     if(index1 >= 0){
//       nur_102.splice(index1, 1);
//     }
//     nur_102.push(mt);
//   }else{
//     nur_102.splice(index1, 1);
//   }
// });
// $('.repeater').repeater({
//   show: function () {
//     $(this).slideDown();
//     $(this).find('img').attr('src', '');
//   },
//   hide: function(remove) {
//     if (confirm('Are you sure you want to remove this item?')) {
//       $(this).slideUp(remove);
//     }
//   },
// });


get_report_data_for_update($('#lcr_1 option:selected').val(), $('#lcr_1 option:selected').text());

var nur_8 = [];
var nur_102 = [];
/**************************************************************/
$.each($('.nur_8'), function(value) {
	if ($(this).is(':checked')){
		var id = this.id;
		if(id === 'other'){
			$('#nur_9').prop("disabled", false);
		}
		nur_8.push(id);
	}
});
$('.steps-validation').on('ifChecked','.nur_8', function(){
  var id = this.id;
  if(id === 'other'){
    $('#nur_9').prop("disabled", false);
  }
  nur_8.push(id);
});
$('.steps-validation').on('ifUnchecked','.nur_8', function(event){
  var id = this.id;
  if(id === 'other'){
    $('#nur_9').prop("disabled", true).val('');
  }
  index1 = nur_8.indexOf(id);
  nur_8.splice(index1, 1);
});
/***************************************************************/
if ($("#nur_120").is(':checked')){
	$.each($('.magnet'), function(value)
  {
  		if(this.value !== '')
      {
    			mt = {};
    			mt.id = this.id;
    			mt.value = this.value;
    			nur_102.push(mt);
  		}
  		$(this).prop("disabled", false);
	});
}else{
    $("#nur_120").prop("disabled", false);
    $.each($('.magnet'), function(value) {
      $(this).prop("disabled", true).val('');
    });
}
$('.steps-validation').on('ifChecked','#nur_120', function(){
  $.each($('.magnet'), function(value) {
    $(this).prop("disabled", false);
  });
});
$('.steps-validation').on('ifUnchecked','#nur_120', function(event){
  $.each($('.magnet'), function(value) {
    $(this).prop("disabled", true).val('');
    index1 = nur_102.map(function(e) { return e.id; }).indexOf(this.id);
    nur_102.splice(index1, 1);
  });
});

if ($("#nur_121").is(':checked')){
	$.each($('.uvl'), function(value)
  {
  		if(this.value !== '')
      {
    			mt = {};
    			mt.id = this.id;
    			mt.value = this.value;
    			nur_102.push(mt);
  		}
  		$(this).prop("disabled", false);
	});
}else{
    $("#nur_121").prop("disabled", false);
    $.each($('.uvl'), function(value) {
      $(this).prop("disabled", true).val('');
    });
}
$('.steps-validation').on('ifChecked','#nur_121', function(){
  $.each($('.uvl'), function(value) {
    $(this).prop("disabled", false);
  });
});
$('.steps-validation').on('ifUnchecked','#nur_121', function(event){
  $.each($('.uvl'), function(value) {
    $(this).prop("disabled", true).val('');
    index1 = nur_102.map(function(e) { return e.id; }).indexOf(this.id);
    nur_102.splice(index1, 1);
  });
});

if ($("#nur_122").is(':checked')){
	$.each($('.coil'), function(value)
  {
  		if(this.value !== '')
      {
    			mt = {};
    			mt.id = this.id;
    			mt.value = this.value;
    			nur_102.push(mt);
  		}
  		$(this).prop("disabled", false);
	});
}else{
    $("#nur_122").prop("disabled", false);
    $.each($('.coil'), function(value) {
      $(this).prop("disabled", true).val('');
    });
}
$('.steps-validation').on('ifChecked','#nur_122', function(){
  $.each($('.coil'), function(value) {
    $(this).prop("disabled", false);
  });
});
$('.steps-validation').on('ifUnchecked','#nur_122', function(event){
  $.each($('.coil'), function(value) {
    $(this).prop("disabled", true).val('');
    index1 = nur_102.map(function(e) { return e.id; }).indexOf(this.id);
    nur_102.splice(index1, 1);
  });
});

if ($("#nur_123").is(':checked')){
	$.each($('.calat'), function(value)
  {
  		if(this.value !== '')
      {
    			mt = {};
    			mt.id = this.id;
    			mt.value = this.value;
    			nur_102.push(mt);
  		}
  		$(this).prop("disabled", false);
	});
}else{
    $("#nur_123").prop("disabled", false);
    $.each($('.calat'), function(value) {
      $(this).prop("disabled", true).val('');
    });
}
$('.steps-validation').on('ifChecked','#nur_123', function(){
  $.each($('.calat'), function(value) {
    $(this).prop("disabled", false);
  });
});
$('.steps-validation').on('ifUnchecked','#nur_123', function(event){
  $.each($('.calat'), function(value) {
    $(this).prop("disabled", true).val('');
    index1 = nur_102.map(function(e) { return e.id; }).indexOf(this.id);
    nur_102.splice(index1, 1);
  });
});
/****************************************************************/
$('.steps-validation').on('change','.mt', function(){
  index1 = nur_102.map(function(e) { return e.id; }).indexOf(this.id);
  if(this.value !== ''){
    var mt = {};
    mt.id = this.id;
    mt.value = this.value;
    if(index1 >= 0){
      nur_102.splice(index1, 1);
    }
    nur_102.push(mt);
  }else{
    alert();
    nur_102.splice(index1, 1);
  }
  console.log(nur_102);
});
$('.repeater').repeater({
  show: function () {
    $(this).slideDown();
    $(this).find('img').attr('src', '');
  },
  hide: function(remove) {
    if (confirm('Are you sure you want to remove this item?')) {
      $(this).slideUp(remove);
    }
  },
});
</script>
@endpush
