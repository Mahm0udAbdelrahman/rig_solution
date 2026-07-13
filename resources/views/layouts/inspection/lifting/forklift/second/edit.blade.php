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
              <div class="card-body">
                <div class="row">
                  <h5 class="text-bold-600 col-6 pb-0 mb-0 mid">Service Brake</h5>
                  <div class="col-6 text-right">
                    <button id="input1" class="btn btn-icon btn-info waves-effect waves-light " style="margin-left: 3px; margin-right: 3px;" type="button">Service Brake Fields N/A</button>
                  </div>
                </div>
              </div>

          <div id="input11" class="card">
            <div class="card-content collapse show skin skin-square form-group">
              <div class="card-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Function test components</th>
                      <th scope="col">Condition</th>
                      <th scope="col">Justify if not Satisfactory</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th scope="row">Forward</th>
                      <td class="form-group">
                        <!-- <input type="text" id="lfr2_1" name="lfr2_1" class="form-control" placeholder="Condition" value="" required="" data-validation-required-message="This field is required"> -->
                        <div class="controls">
                          <select class="form-control satisfactory" id="lfr2_1" name="lfr2_1" required>
                            <option value="">Select Value</option>
                            <option value="0" <?php if($forklift2->lfr2_1 == 0) echo "selected"; ?>>Satisfactory</option>
                            <option value="1" <?php if($forklift2->lfr2_1 == 1) echo "selected"; ?>>Not Satisfactory</option>
                          </select>
                        </div>
                      </td>
                      <td><input type="text" id="lfr2_2" name="lfr2_2" class="form-control" placeholder="Justify if not Satisfactory" value="{{$forklift2->lfr2_2}}" required="" data-validation-required-message="This field is required" @if($forklift2->lfr2_1 == 0) disabled @endif></td>
                    </tr>
                    <tr>
                      <th scope="row">Reverse</th>
                      <td class="form-group">
                        <!-- <input type="text" id="lfr2_3" name="lfr2_3" class="form-control" placeholder="Condition" value="" required="" data-validation-required-message="This field is required"> -->
                        <div class="controls">
                          <select class="form-control satisfactory" id="lfr2_3" name="lfr2_3" required>
                            <option value="">Select Value</option>
                            <option value="0" <?php if($forklift2->lfr2_3 == 0) echo "selected"; ?>>Satisfactory</option>
                            <option value="1" <?php if($forklift2->lfr2_3 == 1) echo "selected"; ?>>Not Satisfactory</option>
                          </select>
                        </div>
                      </td>
                      <td><input type="text" id="lfr2_4" name="lfr2_4" class="form-control" placeholder="Justify if not Satisfactory" value="{{$forklift2->lfr2_4}}" required="" data-validation-required-message="This field is required" @if($forklift2->lfr2_3 == 0) disabled @endif></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

              <div class="card-body">
                <div class="row">
                  <h5 class="text-bold-600 col-6 pb-0 mb-0 mid">Parking Brake</h5>
                  <div class="col-6 text-right">
                    <button id="input2" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">Parking Brake Fields N/A</button>
                  </div>
                </div>
              </div>

          <div id="input22" class="card">
            <div class="card-content collapse show skin skin-square form-group">
              <div class="card-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Function test components</th>
                      <th scope="col">Condition</th>
                      <th scope="col">Justify if not Satisfactory</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th scope="row">Forward</th>
                      <td>
                        <!-- <input type="text" id="lfr2_5" name="lfr2_5" class="form-control" placeholder="Condition" value="" required="" data-validation-required-message="This field is required"> -->
                        <div class="controls">
                          <select class="form-control satisfactory" id="lfr2_5" name="lfr2_5" required>
                            <option value="">Select Value</option>
                            <option value="0" <?php if($forklift2->lfr2_5 == 0) echo "selected"; ?>>Satisfactory</option>
                            <option value="1" <?php if($forklift2->lfr2_5 == 1) echo "selected"; ?>>Not Satisfactory</option>
                          </select>
                        </div>
                      </td>
                      <td><input type="text" id="lfr2_6" name="lfr2_6" class="form-control" placeholder="Justify if not Satisfactory" value="{{$forklift2->lfr2_6}}" required="" data-validation-required-message="This field is required" @if($forklift2->lfr2_5 == 0) disabled @endif></td>
                    </tr>
                    <tr>
                      <th scope="row">Reverse</th>
                      <td>
                        <!-- <input type="text" id="lfr2_7" name="lfr2_7" class="form-control" placeholder="Condition" value="" required="" data-validation-required-message="This field is required"> -->
                        <div class="controls">
                          <select class="form-control satisfactory" id="lfr2_7" name="lfr2_7" required>
                            <option value="">Select Value</option>
                            <option value="0" <?php if($forklift2->lfr2_7 == 0) echo "selected"; ?>>Satisfactory</option>
                            <option value="1" <?php if($forklift2->lfr2_7 == 1) echo "selected"; ?>>Not Satisfactory</option>
                          </select>
                        </div>
                      </td>
                      <td><input type="text" id="lfr2_8" name="lfr2_8" class="form-control" placeholder="Justify if not Satisfactory" value="{{$forklift2->lfr2_8}}" required="" data-validation-required-message="This field is required"  @if($forklift2->lfr2_7 == 0) disabled @endif></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

              <div class="card-body">
                <div class="row">
                  <h5 class="text-bold-600 col-6 pb-0 mb-0 mid">Steering Operation</h5>
                  <div class="col-6 text-right">
                    <button id="input3" class="btn btn-icon btn-info waves-effect waves-light " style="margin-left: 3px; margin-right: 3px;" type="button">Steering Operation Fields N/A</button>
                  </div>
                </div>
              </div>

          <div id="input33" class="card">
            <div class="card-content collapse show skin skin-square form-group">
              <div class="card-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Function test components</th>
                      <th scope="col">Condition</th>
                      <th scope="col">Justify if not Satisfactory</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th scope="row">Not Excessive Free Play</th>
                      <td>
                        <!-- <input type="text" id="lfr2_9" name="lfr2_9" class="form-control" placeholder="Condition" value="" required="" data-validation-required-message="This field is required"> -->
                        <div class="controls">
                          <select class="form-control satisfactory" id="lfr2_9" name="lfr2_9" required>
                            <option value="">Select Value</option>
                            <option value="0" <?php if($forklift2->lfr2_9 == 0) echo "selected"; ?>>Satisfactory</option>
                            <option value="1" <?php if($forklift2->lfr2_9 == 1) echo "selected"; ?>>Not Satisfactory</option>
                          </select>
                        </div>
                      </td>
                      <td><input type="text" id="lfr2_10" name="lfr2_10" class="form-control" placeholder="Justify if not Satisfactory" value="{{$forklift2->lfr2_10}}" required="" data-validation-required-message="This field is required"  @if($forklift2->lfr2_9 == 0) disabled @endif></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <h5 class="text-bold-600 col-6 pb-0 mb-0 mid">Drive Control</h5>
              <div class="col-6 text-right">
                <button id="input4" class="btn btn-icon btn-info waves-effect waves-light " style="margin-left: 3px; margin-right: 3px;" type="button">Drive Control Fields N/A</button>
              </div>
            </div>
          </div>

      <div id="input44" class="card">
        <div class="card-content collapse show skin skin-square form-group">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Function test components</th>
                  <th scope="col">Condition</th>
                  <th scope="col">Justify if not Satisfactory</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">Forward</th>
                  <td>
                    <!-- <input type="text" id="lfr2_11" name="lfr2_11" class="form-control" placeholder="Condition" value="" required="" data-validation-required-message="This field is required"> -->
                    <div class="controls">
                      <select class="form-control satisfactory" id="lfr2_11" name="lfr2_11" required>
                        <option value="">Select Value</option>
                        <option value="0" <?php if($forklift2->lfr2_11 == 0) echo "selected"; ?>>Satisfactory</option>
                        <option value="1" <?php if($forklift2->lfr2_11 == 1) echo "selected"; ?>>Not Satisfactory</option>
                      </select>
                    </div>
                  </td>
                  <td><input type="text" id="lfr2_12" name="lfr2_12" class="form-control" placeholder="Justify if not Satisfactory" value="{{$forklift2->lfr2_12}}" required="" data-validation-required-message="This field is required"  @if($forklift2->lfr2_11 == 0) disabled @endif></td>
                </tr>
                <tr>
                  <th scope="row">Reverse</th>
                  <td>
                    <!-- <input type="text" id="lfr2_13" name="lfr2_13" class="form-control" placeholder="Condition" value="" required="" data-validation-required-message="This field is required"> -->
                    <div class="controls">
                      <select class="form-control satisfactory" id="lfr2_13" name="lfr2_13" required>
                        <option value="">Select Value</option>
                        <option value="0" <?php if($forklift2->lfr2_13 == 0) echo "selected"; ?>>Satisfactory</option>
                        <option value="1" <?php if($forklift2->lfr2_13 == 1) echo "selected"; ?>>Not Satisfactory</option>
                      </select>
                    </div>
                  </td>
                  <td><input type="text" id="lfr2_14" name="lfr2_14" class="form-control" placeholder="Justify if not Satisfactory" value="{{$forklift2->lfr2_14}}" required="" data-validation-required-message="This field is required"  @if($forklift2->lfr2_13 == 0) disabled @endif></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

          <div class="card-body">
            <div class="row">
              <h5 class="text-bold-600 col-6 pb-0 mb-0 mid">Tilt Control</h5>
              <div class="col-6 text-right">
                <button id="input5" class="btn btn-icon btn-info waves-effect waves-light " style="margin-left: 3px; margin-right: 3px;" type="button">Tilt Control Fields N/A</button>
              </div>
            </div>
          </div>

      <div id="input55" class="card">
        <div class="card-content collapse show skin skin-square form-group">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Function test components</th>
                  <th scope="col">Condition</th>
                  <th scope="col">Justify if not Satisfactory</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">Forward</th>
                  <td>
                    <!-- <input type="text" id="lfr2_15" name="lfr2_15" class="form-control" placeholder="Condition" value="" required="" data-validation-required-message="This field is required"> -->
                    <div class="controls">
                      <select class="form-control satisfactory" id="lfr2_15" name="lfr2_15" required>
                        <option value="">Select Value</option>
                        <option value="0" <?php if($forklift2->lfr2_15 == 0) echo "selected"; ?>>Satisfactory</option>
                        <option value="1" <?php if($forklift2->lfr2_15 == 1) echo "selected"; ?>>Not Satisfactory</option>
                      </select>
                    </div>
                  </td>
                  <td><input type="text" id="lfr2_16" name="lfr2_16" class="form-control" placeholder="Justify if not Satisfactory" value="{{$forklift2->lfr2_16}}" required="" data-validation-required-message="This field is required"  @if($forklift2->lfr2_15 == 0) disabled @endif></td>
                </tr>
                <tr>
                  <th scope="row">Back</th>
                  <td>
                    <!-- <input type="text" id="lfr2_17" name="lfr2_17" class="form-control" placeholder="Condition" value="" required="" data-validation-required-message="This field is required"> -->
                    <div class="controls">
                      <select class="form-control satisfactory" id="lfr2_17" name="lfr2_17" required>
                        <option value="">Select Value</option>
                        <option value="0" <?php if($forklift2->lfr2_17 == 0) echo "selected"; ?>>Satisfactory</option>
                        <option value="1" <?php if($forklift2->lfr2_17 == 1) echo "selected"; ?>>Not Satisfactory</option>
                      </select>
                    </div>
                  </td>
                  <td><input type="text" id="lfr2_18" name="lfr2_18" class="form-control" placeholder="Justify if not Satisfactory" value="{{$forklift2->lfr2_18}}" required="" data-validation-required-message="This field is required"  @if($forklift2->lfr2_17 == 0) disabled @endif></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

          <div class="card-body">
            <div class="row">
              <h5 class="text-bold-600 col-6 pb-0 mb-0 mid">Hoist & Lower Control</h5>
              <div class="col-6 text-right">
                <button id="input6" class="btn btn-icon btn-info waves-effect waves-light " style="margin-left: 3px; margin-right: 3px;" type="button">Hoist & Lower Control Fields N/A</button>
              </div>
            </div>
          </div>

      <div id="input66" class="card">
        <div class="card-content collapse show skin skin-square form-group">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Function test components</th>
                  <th scope="col">Condition</th>
                  <th scope="col">Justify if not Satisfactory</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">Hoist</th>
                  <td>
                    <!-- <input type="text" id="lfr2_19" name="lfr2_19" class="form-control" placeholder="Condition" value="" required="" data-validation-required-message="This field is required"> -->
                    <div class="controls">
                      <select class="form-control satisfactory" id="lfr2_19" name="lfr2_19" required>
                        <option value="">Select Value</option>
                        <option value="0" <?php if($forklift2->lfr2_19 == 0) echo "selected"; ?>>Satisfactory</option>
                        <option value="1" <?php if($forklift2->lfr2_19 == 1) echo "selected"; ?>>Not Satisfactory</option>
                      </select>
                    </div>
                  </td>
                  <td><input type="text" id="lfr2_20" name="lfr2_20" class="form-control" placeholder="Justify if not Satisfactory" value="{{$forklift2->lfr2_20}}" required="" data-validation-required-message="This field is required"  @if($forklift2->lfr2_19 == 0) disabled @endif></td>
                </tr>
                <tr>
                  <th scope="row">Lower</th>
                  <td>
                    <!-- <input type="text" id="lfr2_21" name="lfr2_21" class="form-control" placeholder="Condition" value="" required="" data-validation-required-message="This field is required"> -->
                    <div class="controls">
                      <select class="form-control satisfactory" id="lfr2_21" name="lfr2_21" required>
                        <option value="">Select Value</option>
                        <option value="0" <?php if($forklift2->lfr2_21 == 0) echo "selected"; ?>>Satisfactory</option>
                        <option value="1" <?php if($forklift2->lfr2_21 == 1) echo "selected"; ?>>Not Satisfactory</option>
                      </select>
                    </div>
                  </td>
                  <td><input type="text" id="lfr2_22" name="lfr2_22" class="form-control" placeholder="Justify if not Satisfactory" value="{{$forklift2->lfr2_22}}" required="" data-validation-required-message="This field is required"  @if($forklift2->lfr2_21 == 0) disabled @endif></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
        </fieldset>

        <h6>Step 2</h6>
        <fieldset>
          <h6 class="text-bold-600">Forks</h6>
          <div class="card">
            <div class="card-content form-group">
              <div class="card-body">
                <div class="row ">
                  <div class="col-8 ">
                    <div class="row">
                      <div class="col-6 ">
                        <input type="checkbox" @if($forklift2->lfr2_23 != NULL) checked @endif class="switchery" id="id-information" name="fork" >
                        <span class="mr-1"><label for="id-information">(01) ID/Information</label></span>
                      </div>
                      <div class="col-6">
                        <input type="text" id="lfr2_23" name="lfr2_23" class="form-control" placeholder="" value="{{$forklift2->lfr2_23}}" required="" data-validation-required-message="This field is required" @if($forklift2->lfr2_23 == NULL) disabled @endif />
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <input type="checkbox" @if($forklift2->lfr2_24 != NULL) checked @endif class="switchery" id="thickness" name="fork" >
                        <span class="mr-1"><label for="thickness">(02) Thickness</label></span>
                      </div>
                      <div class="col-6">
                        <input type="text" id="lfr2_24" name="lfr2_24" class="form-control" placeholder="" value="{{$forklift2->lfr2_24}}" required="" data-validation-required-message="This field is required" @if($forklift2->lfr2_24 == NULL) disabled @endif/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <input type="checkbox" @if($forklift2->lfr2_25 != NULL) checked @endif class="switchery" id="blade-width" name="fork" >
                        <span class="mr-1"><label for="blade-width">(03) Blade Width</label></span>
                      </div>
                      <div class="col-6">
                        <input type="text" id="lfr2_25" name="lfr2_25" class="form-control" placeholder="" value="{{$forklift2->lfr2_25}}" required="" data-validation-required-message="This field is required" @if($forklift2->lfr2_25 == NULL) disabled @endif/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6 ">
                        <input type="checkbox" @if($forklift2->lfr2_26 != NULL) checked @endif class="switchery" id="blade-length" name="fork" >
                        <span class="mr-1"><label for="blade-length">(04) Blade Length</label></span>
                      </div>
                      <div class="col-6">
                        <input type="text" id="lfr2_26" name="lfr2_26" class="form-control" placeholder="" value="{{$forklift2->lfr2_26}}" required="" data-validation-required-message="This field is required" @if($forklift2->lfr2_26 == NULL) disabled @endif/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <input type="checkbox" @if($forklift2->lfr2_27 != NULL) checked @endif class="switchery" id="distance-between-hooks" name="fork" >
                        <span class="mr-1"><label for="distance-between-hooks">(05) Distance between hooks</label></span>
                      </div>
                      <div class="col-6">
                        <input type="text" id="lfr2_27" name="lfr2_27" class="form-control" placeholder="" value="{{$forklift2->lfr2_27}}" required="" data-validation-required-message="This field is required" @if($forklift2->lfr2_27 == NULL) disabled @endif/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <input type="checkbox" @if($forklift2->lfr2_28 != NULL) checked @endif class="switchery" id="shank-height" name="fork" >
                        <span class="mr-1"><label for="shank-height">(06) Shank Height ( Back height )</label></span>
                      </div>
                      <div class="col-6">
                        <input type="text" id="lfr2_28" name="lfr2_28" class="form-control" placeholder="" value="{{$forklift2->lfr2_28}}" required="" data-validation-required-message="This field is required" @if($forklift2->lfr2_28 == NULL) disabled @endif/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <input type="checkbox" @if($forklift2->lfr2_29 != NULL) checked @endif class="switchery" id="hanger-type" name="fork" >
                        <span class="mr-1"><label for="hanger-type">(07) Hanger type: Hooks/Tube</label></span>
                      </div>
                      <div class="col-6">
                        <input type="text" id="lfr2_29" name="lfr2_29" class="form-control" placeholder="" value="{{$forklift2->lfr2_29}}" required="" data-validation-required-message="This field is required" @if($forklift2->lfr2_29 == NULL) disabled @endif/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6 mid">
                        <input type="checkbox" @if($forklift2->lfr2_30 != NULL) checked @endif class="switchery" id="shaft-diameter" name="fork" >
                        <span class="mr-1"><label for="shaft-diameter">(08) Shaft diameter</label></span>
                      </div>
                      <div class="col-6">
                        <input type="text" id="lfr2_30" name="lfr2_30" class="form-control" placeholder="" value="{{$forklift2->lfr2_30}}" required="" data-validation-required-message="This field is required" @if($forklift2->lfr2_30 == NULL) disabled @endif/>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="border-dark">
                      <img src="{{asset('app-assets/images/forklift.jpg')}}" style="max-width: 100%;" />
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
                    <p>Forks General Condition</p>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lfr2_31_y " name="lfr2_31" {{$forklift2->checkbox_yes($forklift2->lfr2_31)}} required>
                        <label class="custom-control-label" for="lfr2_31_y ">Accept</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lfr2_31_n" name="lfr2_31" {{$forklift2->checkbox_no($forklift2->lfr2_31)}}>
                        <label class="custom-control-label" for="lfr2_31_n">Reject</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </fieldset>
        <h6>Step 3</h6>
        <fieldset>
          <h6 class="text-bold-600">MPI Details</h6>
          <p>Inspection Method and equipment used</p>
          <button id="input102" class="btn btn-icon btn-info waves-effect waves-light" style="margin-left: 3px; margin-right: 3px;" type="button">All Fields N/A</button>

          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Standard</label>
                          <input type="text" id="lfr2_32" name="lfr2_32" class="form-control" placeholder="Standard" value="{{$forklift2->lfr2_32}}" required="" data-validation-required-message="This field is required"/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Equipment type</label>
                          <input type="text" id="lfr2_33" name="lfr2_33" class="form-control" placeholder="Equipment type" value="{{$forklift2->lfr2_33}}" required="" data-validation-required-message="This field is required">
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Equipment No</label>
                          <input type="text" id="lfr2_34" name="lfr2_34" class="form-control" placeholder="Equipment No" value="{{$forklift2->lfr2_34}}" required="" data-validation-required-message="This field is required">
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <label>Pole spacing</label>
                          <input type="text" id="lfr2_35" name="lfr2_35" class="form-control" placeholder="Pole spacing" value="{{$forklift2->lfr2_35}}" required="" data-validation-required-message="This field is required">
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
                          <label>Due Date</label>
                          <input type="text" class="form-control dp-date-range-from" id="lfr2_36" name="lfr2_36" placeholder="Due Date" value="{{$forklift2->lfr2_36}}" />


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
                          <td><input type="text" id="lfr2_37" name="lfr2_37" class="form-control" placeholder="Contrast/Manufacturer" value="{{$forklift2->lfr2_37}}" required="" data-validation-required-message="This field is required"></td>
                          <td><input type="text" id="lfr2_38" name="lfr2_38" class="form-control" placeholder="Indicator/ Manufacturer" value="{{$forklift2->lfr2_38}}" required="" data-validation-required-message="This field is required"></td>
                        </tr>
                        <tr>
                          <th scope="row">Expire Date</th>
                          <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ft-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control dp-date-range-to" id="lfr2_39" name="lfr2_39" placeholder="Expire Date" value="{{$forklift2->lfr2_39}}" required="" data-validation-required-message="This field is required"/>
                            </div>
                            <div class="help-block"></div></div>
                          </td>
                          <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ft-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control dp-date-range-to" id="lfr2_40" name="lfr2_40" placeholder="Expire Date" value="{{$forklift2->lfr2_40}}" required="" data-validation-required-message="This field is required"/>
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
                            <label>Final Conclusion</label>
                            <textarea id="lfr2_41" name="lfr2_41" class="form-control" placeholder="Final Conclusion" required="" data-validation-required-message="This field is required">{{str_replace('<br />', '', $forklift2->lfr2_41)}}</textarea>
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
                  <div class="col-9">
                    <div class="form-group">
                      <div class="controls">
                          <label>Upload Image</label>
                          <div class="custom-file">
                              <input type="file" class="custom-file-input" id="lfr2_42" name="lfr2_42">
                              <label class="custom-file-label" style="height: 2.80rem;" for="lfr2_42" aria-describedby="lfr2_42">Choose file</label>
                          </div>
                      <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-2 mt-1 mt-sm-0">
                    <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{Storage::url('camera/inspection/lifting/forklifts/second/')}}{{$forklift2->lfr2_42}}" alt="" />
                  </div>
                  <div class="col-1 mt-1 mt-sm-0">
                    <button type="button" data-id="delete" class="btn btn-icon btn-danger mr-1 delete"><i class="la la-trash"></i></button>
                    <input type="hidden" value="{{$forklift2->lfr2_42}}" id="imagedata" />
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
$('.steps-validation').on('change','.satisfactory', function(){
  if($(this).val() == 1){
    $(this).closest('tr').find('input[type=text]').prop( "disabled", false );
  }else{
    $(this).closest('tr').find('input[type=text]').val('N/A');
  }
});

$('.steps-validation').on('click','#input1', function(){
  $('#input11 select').each(function(index, value){
    $(value).val("0");
  });
  $('#input11 input[type=text]').each(function(index, value){
    $(value).val("N/A");
  });
});

$('.steps-validation').on('click','#input2', function(){
  $('#input22 select').each(function(index, value){
    $(value).val("0");
  });
  $('#input22 input[type=text]').each(function(index, value){
    $(value).val("N/A");
  });
});

$('.steps-validation').on('click','#input3', function(){
  $('#input33 select').each(function(index, value){
    $(value).val("0");
  });
  $('#input33 input[type=text]').each(function(index, value){
    $(value).val("N/A");
  });
});

$('.steps-validation').on('click','#input4', function(){
  $('#input44 select').each(function(index, value){
    $(value).val("0");
  });
  $('#input44 input[type=text]').each(function(index, value){
    $(value).val("N/A");
  });
});

$('.steps-validation').on('click','#input5', function(){
  $('#input55 select').each(function(index, value){
    $(value).val("0");
  });
  $('#input55 input[type=text]').each(function(index, value){
    $(value).val("N/A");
  });
});

$('.steps-validation').on('click','#input6', function(){
  $('#input66 select').each(function(index, value){
    $(value).val("0");
  });
  $('#input66 input[type=text]').each(function(index, value){
    $(value).val("N/A");
  });
});

$('.steps-validation').on('click','#input102', function(){
  $('#steps-uid-0-p-2 input[type=text]').each(function(index, value){
    $(value).val("N/A");
  });
});


$('.steps-validation').on("change", '#id-information',function (e){
  if(this.checked == true){
    $('#lfr2_23').prop('disabled', false);
  }else{
    $('#lfr2_23').prop('disabled', true);
    $('#lfr2_23').val("");
  }
});

$('.steps-validation').on("change", '#thickness',function (e){
  if(this.checked == true){
    $('#lfr2_24').prop('disabled', false);
  }else{
    $('#lfr2_24').prop('disabled', true);
    $('#lfr2_24').val("");
  }
});

$('.steps-validation').on("change", '#blade-width',function (e){
  if(this.checked == true){
    $('#lfr2_25').prop('disabled', false);
  }else{
    $('#lfr2_25').prop('disabled', true);
    $('#lfr2_25').val("");
  }
});

$('.steps-validation').on("change", '#blade-length',function (e){
  if(this.checked == true){
    $('#lfr2_26').prop('disabled', false);
  }else{
    $('#lfr2_26').prop('disabled', true);
    $('#lfr2_26').val("");
  }
});

$('.steps-validation').on("change", '#distance-between-hooks',function (e){
  if(this.checked == true){
    $('#lfr2_27').prop('disabled', false);
  }else{
    $('#lfr2_27').prop('disabled', true);
    $('#lfr2_27').val("");
  }
});

$('.steps-validation').on("change", '#shank-height',function (e){
  if(this.checked == true){
    $('#lfr2_28').prop('disabled', false);
  }else{
    $('#lfr2_28').prop('disabled', true);
    $('#lfr2_28').val("");
  }
});

$('.steps-validation').on("change", '#hanger-type',function (e){
  if(this.checked == true){
    $('#lfr2_29').prop('disabled', false);
  }else{
    $('#lfr2_29').prop('disabled', true);
    $('#lfr2_29').val("");
  }
});

$('.steps-validation').on("change", '#shaft-diameter',function (e){
  if(this.checked == true){
    $('#lfr2_30').prop('disabled', false);
  }else{
    $('#lfr2_30').prop('disabled', true);
    $('#lfr2_30').val("");
  }
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
          url: "{{route('forklift2_edit.update', $forklift)}}",
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
            }  });
          },
        });
    }
});
</script>

@endprepend
