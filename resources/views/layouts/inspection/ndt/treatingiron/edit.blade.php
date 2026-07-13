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
                                                        <option value="{{$treatingIron->job_request->id}}"
                                                                selected>{{$treatingIron->job_request->code}}</option>
                                                    </select>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Purchase Order</label>
                                                    <input disabled type="text" id="purchaseOrder" name="ntir_2" class="form-control"
                                                           placeholder="Purchase Order"
                                                           value="{{$treatingIron->ntir_2}}">
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
                                                               value="{{$treatingIron->job_request->code}} /"
                                                               required=""
                                                               data-validation-required-message="This code field is required"
                                                               aria-invalid="false" disabled
                                                               style="width: 83px; float: left;">
                                                        <input type="text" id="code" name="code"
                                                               class="form-control pl-0" placeholder="."
                                                               value="{{$treatingIron->code}}" required=""
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
                                                        <input type="text" value="{{$treatingIron->ntir_6}}"
                                                               class="form-control datepicker-default" id="lcr_60"
                                                               name="lcr_60" placeholder="Examination Date" required=""
                                                               data-validation-required-message="This field is required"/>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Next Examination Date / <span id="months">12</span>
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
                                                               id="lcr_70" value="{{$treatingIron->ntir_7}}"
                                                               name="lcr_70" placeholder="Next Examination Date"
                                                               required=""
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
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-12">
                                            <div class="skin skin-square form-group">
                                                <div class="controls">
                                                    <label class="m-0 mb-1">Inspection Method</label>
                                                    <div class="row input-group">
                                                        <fieldset class="col-md-4">
                                                            <input type="checkbox" class="contactway" name="contactway"
                                                                   id="visual-Examination"
                                                                   @if(in_array('visual-Examination', json_decode($treatingIron->ntir_9))) checked
                                                                   @endif required>
                                                            <label for="visual-Examination">Visual Examination</label>
                                                        </fieldset>
                                                        <fieldset class="col-md-4">
                                                            <input type="checkbox" class="contactway" name="contactway"
                                                                   id="mpi-inspection"
                                                                   @if(in_array('mpi-inspection', json_decode($treatingIron->ntir_9))) checked @endif>
                                                            <label for="mpi-inspection">MPI Inspection</label>
                                                        </fieldset>
                                                        <fieldset class="col-md-4">
                                                            <input type="checkbox" class="contactway" name="contactway"
                                                                   id="ut-wall-thickness"
                                                                   @if(in_array('ut-wall-thickness', json_decode($treatingIron->ntir_9))) checked @endif>
                                                            <label for="ut-wall-thickness">UT Wall Thickness</label>
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
                            <div class="card-content">
                                <div class="card-body">
                                    <h6 class="mb-1">Specification</h6>
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row">

                                                @foreach($specifications as $specification)
                                                    @if(strtolower(str_replace(' ', '-', $specification->name)) != 'bs' && strtolower(str_replace(' ', '-', $specification->name)) != 'asnt' && strtolower(str_replace(' ', '-', $specification->name)) != 'aws' && strtolower(str_replace(' ', '-', $specification->name)) != 'rse-procedure')
                                                        <div class="col-md-3 col-sm-12">
                                                            <fieldset>
                                                                <input type="checkbox" class="ntir_10" name="ntir_10"
                                                                       id="{{strtolower(str_replace(' ', '-', $specification->name))}}"
                                                                       @if(in_array(strtolower(str_replace(' ', '-', $specification->name)), json_decode($treatingIron->ntir_10))) checked @endif />
                                                                <label for="{{strtolower(str_replace(' ', '-', $specification->name))}}">{{$specification->name}}</label>
                                                            </fieldset>
                                                        </div>
                                                    @endif
                                                @endforeach

                                            </div>
                                            <hr/>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>If Other</label>
                                                            <input type="text" id="ntir_11" name="ntir_11"
                                                                   class="form-control" placeholder="If Other"
                                                                   value="{{$treatingIron->ntir_11}}"
                                                                   data-validation--message="This field is "/>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Edition</label>
                                                            <input type="text" id="ntir_50" name="ntir_50"
                                                                   class="form-control" placeholder="Edition"
                                                                   value="{{$treatingIron->ntir_49}}"
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
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Description</label>
                                                    <textarea id="ntir_12" name="ntir_12" class="form-control"
                                                              placeholder="Description">{{$treatingIron->desc}}</textarea>
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
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Identification No</label>
                                                    <input type="text" id="ntir_13" name="ntir_13" class="form-control"
                                                           placeholder="Identification No"
                                                           value="{{$treatingIron->ntir_13}}"
                                                           data-validation--message="This field is "/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Type</label>
                                                    <input type="text" id="ntir_14" name="ntir_14" class="form-control"
                                                           placeholder="Type" value="{{$treatingIron->ntir_14}}"
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
                    <h6>Section A- Visual Examination</h6>
                    <fieldset>
                        <h6 class="mb-1">A-1: Dimension</h6>
                        <div class="card visual-Examination-section">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Length</label>
                                                    <input type="text" id="ntir_15" name="ntir_15" class="form-control"
                                                           placeholder="Length" value="{{$treatingIron->ntir_15}}"
                                                           data-validation--message="This field is "/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>OD</label>
                                                    <input type="text" id="ntir_16" name="ntir_16" class="form-control"
                                                           placeholder="OD" value="{{$treatingIron->ntir_16}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>ID</label>
                                                    <input type="text" id="ntir_17" name="ntir_17" class="form-control"
                                                           placeholder="ID" value="{{$treatingIron->ntir_17}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Angle (Elbow)</label>
                                                    <input type="text" id="ntir_18" name="ntir_18" class="form-control"
                                                           placeholder="Angle (Elbow)"
                                                           value="{{$treatingIron->ntir_18}}"
                                                           data-validation--message="This field is "/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Lug</label>
                                                    <input type="text" id="ntir_19" name="ntir_19" class="form-control"
                                                           placeholder="Lug" value="{{$treatingIron->ntir_19}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Other</label>
                                                    <input type="text" id="ntir_20" name="ntir_20" class="form-control"
                                                           placeholder="Other" value="{{$treatingIron->ntir_20}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">A-2: Conditions</h6>
                        <div class="card visual-Examination-section">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Thread</label>
                                                    <input type="text" id="ntir_21" name="ntir_21" class="form-control"
                                                           placeholder="Thread" value="{{$treatingIron->ntir_21}}"
                                                           data-validation--message="This field is "/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Nut</label>
                                                    <input type="text" id="ntir_22" name="ntir_22" class="form-control"
                                                           placeholder="Nut" value="{{$treatingIron->ntir_22}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Seal Areat</label>
                                                    <input type="text" id="ntir_23" name="ntir_23" class="form-control"
                                                           placeholder="Seal Areat" value="{{$treatingIron->ntir_23}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Internal</label>
                                                    <input type="text" id="ntir_24" name="ntir_24" class="form-control"
                                                           placeholder="Internal" value="{{$treatingIron->ntir_24}}"
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
                                                    <label>Swivel</label>
                                                    <input type="text" id="ntir_25" name="ntir_25" class="form-control"
                                                           placeholder="Swivel" value="{{$treatingIron->ntir_25}}"
                                                           data-validation--message="This field is "/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Straightness</label>
                                                    <input type="text" id="ntir_26" name="ntir_26" class="form-control"
                                                           placeholder="Straightness" value="{{$treatingIron->ntir_26}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>NPST Conn.</label>
                                                    <input type="text" id="ntir_27" name="ntir_27" class="form-control"
                                                           placeholder="NPST Conn." value="{{$treatingIron->ntir_27}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Other</label>
                                                    <input type="text" id="ntir_28" name="ntir_28" class="form-control"
                                                           placeholder="Other" value="{{$treatingIron->ntir_28}}"
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
                    <h6>Section B- Magnetic Particle Inspection:</h6>
                    <fieldset>
                        <div class="card mpi-inspection-section">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="skin skin-square form-group">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">Equipment Used</th>
                                                        <th scope="col">
                                                            <fieldset>
                                                                <input type="checkbox" class="eu" name="nmpr_120"
                                                                       id="nmpr_120" {{$treatingIron->checkMtvalue('nmpr_14', 'ntir_29')}}>
                                                                <label for="nmpr_120">Magnet</label>
                                                            </fieldset>
                                                        </th>
                                                        <th scope="col">
                                                            <fieldset>
                                                                <input type="checkbox" class="eu" name="nmpr_121"
                                                                       id="nmpr_121" {{$treatingIron->checkMtvalue('nmpr_15', 'ntir_29')}}>
                                                                <label for="nmpr_121">UV Light</label>
                                                            </fieldset>
                                                        </th>
                                                        <th scope="col">
                                                            <fieldset>
                                                                <input type="checkbox" class="eu" name="nmpr_122"
                                                                       id="nmpr_122" {{$treatingIron->checkMtvalue('nmpr_16', 'ntir_29')}}>
                                                                <label for="nmpr_122">Coil</label>
                                                            </fieldset>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <th scope="row">Equipment No</th>
                                                        <td><input type="text" id="nmpr_14" name="nmpr_14"
                                                                   class="form-control magnet mt"
                                                                   placeholder="Contrast/Manufacturer"
                                                                   value="{{$treatingIron->getMtvalue('nmpr_14', 'ntir_29')}}"
                                                                   data-validation--message="This field is " disabled>
                                                        </td>
                                                        <td><input type="text" id="nmpr_15" name="nmpr_15"
                                                                   class="form-control uvl mt"
                                                                   placeholder="Indicator/ Manufacturer"
                                                                   value="{{$treatingIron->getMtvalue('nmpr_15', 'ntir_29')}}"
                                                                   data-validation--message="This field is " disabled>
                                                        </td>
                                                        <td><input type="text" id="nmpr_16" name="nmpr_16"
                                                                   class="form-control coil mt"
                                                                   placeholder="Indicator/ Manufacturer"
                                                                   value="{{$treatingIron->getMtvalue('nmpr_16', 'ntir_29')}}"
                                                                   data-validation--message="This field is " disabled>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Manufacturer</th>
                                                        <td><input type="text" id="nmpr_17" name="nmpr_17"
                                                                   class="form-control magnet mt"
                                                                   placeholder="Contrast/Expire Date"
                                                                   value="{{$treatingIron->getMtvalue('nmpr_17', 'ntir_29')}}"
                                                                   data-validation--message="This field is " disabled>
                                                        </td>
                                                        <td><input type="text" id="nmpr_18" name="nmpr_18"
                                                                   class="form-control uvl mt"
                                                                   placeholder="Indicator/ Expire Date"
                                                                   value="{{$treatingIron->getMtvalue('nmpr_18', 'ntir_29')}}"
                                                                   data-validation--message="This field is " disabled>
                                                        </td>
                                                        <td><input type="text" id="nmpr_19" name="nmpr_19"
                                                                   class="form-control coil mt"
                                                                   placeholder="Contrast/Expire Date"
                                                                   value="{{$treatingIron->getMtvalue('nmpr_19', 'ntir_29')}}"
                                                                   data-validation--message="This field is " disabled>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Calibr. Due Date</th>
                                                        <td><input type="text" id="nmpr_20" name="nmpr_20"
                                                                   class="form-control magnet mt"
                                                                   placeholder="Indicator/ Expire Date"
                                                                   value="{{$treatingIron->getMtvalue('nmpr_20', 'ntir_29')}}"
                                                                   data-validation--message="This field is " disabled>
                                                        </td>
                                                        <td><input type="text" id="nmpr_21" name="nmpr_21"
                                                                   class="form-control uvl mt"
                                                                   placeholder="Indicator/ Expire Date"
                                                                   value="{{$treatingIron->getMtvalue('nmpr_21', 'ntir_29')}}"
                                                                   data-validation--message="This field is " disabled>
                                                        </td>
                                                        <td><input type="text" id="nmpr_22" name="nmpr_22"
                                                                   class="form-control coil mt"
                                                                   placeholder="Contrast/Expire Date"
                                                                   value="{{$treatingIron->getMtvalue('nmpr_22', 'ntir_29')}}"
                                                                   data-validation--message="This field is " disabled>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Equip.Test Criteria</th>
                                                        <td><input type="text" id="nmpr_23" name="nmpr_23"
                                                                   class="form-control magnet mt"
                                                                   placeholder="Indicator/ Expire Date"
                                                                   value="{{$treatingIron->getMtvalue('nmpr_23', 'ntir_29')}}"
                                                                   data-validation--message="This field is " disabled>
                                                        </td>
                                                        <td><input type="text" id="nmpr_24" name="nmpr_24"
                                                                   class="form-control uvl mt"
                                                                   placeholder="Indicator/ Expire Date"
                                                                   value="{{$treatingIron->getMtvalue('nmpr_24', 'ntir_29')}}"
                                                                   data-validation--message="This field is " disabled>
                                                        </td>
                                                        <td><input type="text" id="nmpr_25" name="nmpr_25"
                                                                   class="form-control coil mt"
                                                                   placeholder="Indicator/ Expire Date"
                                                                   value="{{$treatingIron->getMtvalue('nmpr_25', 'ntir_29')}}"
                                                                   data-validation--message="This field is " disabled>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
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
                                                        <input type="file" class="custom-file-input" id="ntir_30"
                                                               name="ntir_30">
                                                        <label class="custom-file-label" style="height: 2.80rem;"
                                                               for="ntir_30" aria-describedby="ntir_30">Choose
                                                            file</label>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            @if(isset($treatingIron->ntir_30))
                                                <img class="media-object"
                                                     src="{{Storage::url('camera/inspection/ndt/treatingiron/')}}{{$treatingIron->ntir_30}}"
                                                     alt="" width="64">
                                            @endif
                                        </div>
                                        <div class=" col-1">
                                            <button type="button" data-id="delete"
                                                    class="btn btn-icon btn-danger mr-1 delete"><i
                                                        class="la la-trash"></i></button>
                                            <input type="hidden" value="{{$treatingIron->ntir_30}}" name="imagedata1"/>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <h6>Section C- Ultrasonic wall thickness</h6>
                    <fieldset>
                        <h6>C-1: Equipment and Technique</h6>
                        <div class="card ut-wall-thickness-section">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Model</label>
                                                    <input type="text" id="ntir_31" name="ntir_31" class="form-control"
                                                           placeholder="Model" value="{{$treatingIron->ntir_31}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Serial Number</label>
                                                    <input type="text" id="ntir_32" name="ntir_32" class="form-control"
                                                           placeholder="Serial Number"
                                                           value="{{$treatingIron->ntir_32}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Cable Type</label>
                                                    <input type="text" id="ntir_33" name="ntir_33" class="form-control"
                                                           placeholder="Cable Type" value="{{$treatingIron->ntir_33}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Sound Velocity</label>
                                                    <input type="text" id="ntir_34" name="ntir_34" class="form-control"
                                                           placeholder="Sound Velocity"
                                                           value="{{$treatingIron->ntir_34}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Probe Dia.</label>
                                                    <input type="text" id="ntir_35" name="ntir_35" class="form-control"
                                                           placeholder="Probe Dia." value="{{$treatingIron->ntir_35}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Probe Frequency</label>
                                                    <input type="text" id="ntir_36" name="ntir_36" class="form-control"
                                                           placeholder="Probe Frequency"
                                                           value="{{$treatingIron->ntir_36}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card ut-wall-thickness-section">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Manufacturer</label>
                                                    <input type="text" id="ntir_37" name="ntir_37" class="form-control"
                                                           placeholder="Manufacturer" value="{{$treatingIron->ntir_37}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Calibration Due</label>
                                                    <input type="text" id="ntir_38" name="ntir_38" class="form-control"
                                                           placeholder="Calibration Due"
                                                           value="{{$treatingIron->ntir_38}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Search Unit</label>
                                                    <input type="text" id="ntir_39" name="ntir_39" class="form-control"
                                                           placeholder="Search Unit" value="{{$treatingIron->ntir_39}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Manufacturer</label>
                                                    <input type="text" id="ntir_40" name="ntir_40" class="form-control"
                                                           placeholder="Manufacturer" value="{{$treatingIron->ntir_40}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Couplant Type</label>
                                                    <input type="text" id="ntir_41" name="ntir_41" class="form-control"
                                                           placeholder="Couplant Type"
                                                           value="{{$treatingIron->ntir_41}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Calibration Block</label>
                                                    <input type="text" id="ntir_42" name="ntir_42" class="form-control"
                                                           placeholder="Calibration Block"
                                                           value="{{$treatingIron->ntir_42}}"
                                                           data-validation--message="This field is "/>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">C-2: Reading (mm)</h6>
                        <div class="card repeater ut-wall-thickness-section">
                            <div class="card-content collapse show">
                                <div class="card-body" data-repeater-list="payments">
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-group mb-0">
                                                <label>Minimum Thickness</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group mb-0">
                                                <label>Section A</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group mb-0">
                                                <label>Section B</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group mb-0">
                                                <label>Section C</label>
                                            </div>
                                        </div>
                                        <div class="col-1"></div>
                                    </div>
                                    @php
                                        $default = [
                                            (object)['ntir_433' => '', 'ntir_434' => '', 'ntir_435' => '', 'ntir_436' => '', 'ntir_437' => '', 'ntir_438' => '', 'ntir_439' => '', 'ntir_440' => '', 'ntir_441' => '', 'ntir_442' => '']
                                        ];
                                        $ntir_43_data = json_decode($treatingIron->ntir_43) ?? $default;
                                    @endphp
                                    @foreach($ntir_43_data as $key => $value)
                                        <div class="row" data-repeater-item>
                                            <div class="col-2">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_433" name="ntir_433" class="form-control"
                                                                  placeholder="Nominal thickness">{{$value->ntir_433}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_434" name="ntir_434" class="form-control"
                                                                  placeholder="A1">{{$value->ntir_434}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_435" name="ntir_435" class="form-control"
                                                                  placeholder="A2">{{$value->ntir_435}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_436" name="ntir_436" class="form-control"
                                                                  placeholder="A3">{{$value->ntir_436}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_437" name="ntir_437" class="form-control"
                                                                  placeholder="B1">{{$value->ntir_437}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_438" name="ntir_438" class="form-control"
                                                                  placeholder="B2">{{$value->ntir_438}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_439" name="ntir_439" class="form-control"
                                                                  placeholder="B3">{{$value->ntir_439}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_440" name="ntir_440" class="form-control"
                                                                  placeholder="C1">{{$value->ntir_440}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_441" name="ntir_441" class="form-control"
                                                                  placeholder="C2">{{$value->ntir_441}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-group mb-0">
                                                    <div class="controls">
                                                        <textarea id="ntir_442" name="ntir_442" class="form-control"
                                                                  placeholder="C3">{{$value->ntir_442}}</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" col-1">
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
                                        <div class="col-9">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Upload Image</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="ntir_44"
                                                               name="ntir_44">
                                                        <label class="custom-file-label" style="height: 2.80rem;"
                                                               for="ntir_44" aria-describedby="ntir_44">Choose
                                                            file</label>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            @if(isset($treatingIron->ntir_44))
                                                <img class="media-object"
                                                     src="{{Storage::url('camera/inspection/ndt/treatingiron/')}}{{$treatingIron->ntir_44}}"
                                                     alt="" width="64">
                                            @endif
                                        </div>
                                        <div class=" col-1">
                                            <button type="button" data-id="delete"
                                                    class="btn btn-icon btn-danger mr-1 delete"><i
                                                        class="la la-trash"></i></button>
                                            <input type="hidden" value="{{$treatingIron->ntir_44}}" name="imagedata"/>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <h6 class="mb-1">Final Conclusion and Comment</h6>
                    <fieldset>
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row mt-1">
                                        <div class="col-8">
                                            <p>A: Visual inspection:</p>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input visual-Examination"
                                                       id="ntir_46_y" name="ntir_46"
                                                       {{$treatingIron->checkbox_yes($treatingIron->ntir_45)}} disabled>
                                                <label class="custom-control-label" for="ntir_46_y">Pass</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input visual-Examination"
                                                       id="ntir_46_n" name="ntir_46"
                                                       {{$treatingIron->checkbox_no($treatingIron->ntir_45)}} disabled>
                                                <label class="custom-control-label" for="ntir_46_n">Fail</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-8">
                                            <p>B: MPI inspection:</p>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input mpi-inspection"
                                                       id="ntir_47_y" name="ntir_47"
                                                       {{$treatingIron->checkbox_yes($treatingIron->ntir_46)}} disabled>
                                                <label class="custom-control-label" for="ntir_47_y">Pass</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input mpi-inspection"
                                                       id="ntir_47_n" name="ntir_47"
                                                       {{$treatingIron->checkbox_no($treatingIron->ntir_46)}} disabled>
                                                <label class="custom-control-label" for="ntir_47_n">Fail</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-8">
                                            <p>C: UT Thickness:</p>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input ut-wall-thickness"
                                                       id="ntir_48_y" name="ntir_48"
                                                       {{$treatingIron->checkbox_yes($treatingIron->ntir_47)}} disabled>
                                                <label class="custom-control-label" for="ntir_48_y">Pass</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input ut-wall-thickness"
                                                       id="ntir_48_n" name="ntir_48"
                                                       {{$treatingIron->checkbox_no($treatingIron->ntir_47)}} disabled>
                                                <label class="custom-control-label" for="ntir_48_n">Fail</label>
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
                                            <!-- <textarea id="ntir_49" name="ntir_49" class="form-control" placeholder="Final Conclusion"></textarea> -->
                                            <div class="row">

                                                <div class="col-8">
                                                    <p>Final result : Accept / Reject ?</p>
                                                </div>
                                                <div class="col-2">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" id="ntir_49_y "
                                                               name="ntir_49"
                                                               {{$treatingIron->checkbox_yes($treatingIron->ntir_48)}}  required>
                                                        <label class="custom-control-label"
                                                               for="ntir_49_y ">Accept</label>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" id="ntir_49_n"
                                                               name="ntir_49" {{$treatingIron->checkbox_no($treatingIron->ntir_48)}}>
                                                        <label class="custom-control-label"
                                                               for="ntir_49_n">Reject</label>
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
      var wizardForm = $('.steps-validation').first();
      var form1 = wizardForm.get(0);
      if (!(form1 instanceof HTMLFormElement)) {
        toastr.error('Treating Iron form is not ready for submit.', 'Submit failed', {
          positionClass: 'toast-bottom-left',
          progressBar: true
        });
        return;
      }

      nmpr_12 = [];
      var formdata = new FormData(form1);
      var checkbox = wizardForm.find("input[type=radio]");
			/* ********************** */
			if ($("#nmpr_120").is(':checked')) {
				$.each($('.magnet'), function (value) {
					if (this.value !== '') {
						let mt = {}
						console.log("<<<<>>>>", this.id);
						mt.id = this.id;
						mt.value = this.value;
						nmpr_12.push(mt);
					}
				});
			}
			if ($("#nmpr_121").is(':checked')) {
				$.each($('.uvl'), function (value) {
					if (this.value !== '') {
						let mt = {}
						mt.id = this.id;
						mt.value = this.value;
						nmpr_12.push(mt);
					}
				});
			}
			if ($("#nmpr_122").is(':checked')) {
				$.each($('.coil'), function (value) {
					if (this.value !== '') {
						let mt = {}
						mt.id = this.id;
						mt.value = this.value;
						nmpr_12.push(mt);
					}
				});
			}
			console.log(">>>>>>>>>>>>>>>>>>>>>>nmpr_12",nmpr_12);
			/* ********************** */
      formdata.append('lcr_1', $('#lcr_1').val());
      formdata.append('code', $('#code').val());
      formdata.append('lcr_7', $('#lcr_70').val());
      formdata.append('ntir_2', $('#purchaseOrder').val());
      formdata.append('contactway', JSON.stringify(contactway));
      formdata.append('ntir_10', JSON.stringify(ntir_10));
      formdata.append('nmpr_12', JSON.stringify(nmpr_12));
      $.each(checkbox, function (key, val) {
        if ($(this).is(':checked') === true) {
          formdata.append($(this).attr('name'), $(this).attr('id'));
        }
      });

      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',

        url: "{{route('treatingIron.update', $treatingIron->id)}}",
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
              window.location.replace("{{route('treatingIron.index')}}");
            }
          });
        },
        error: function (xhr) {
          window.__inspectionAjaxErrorSilenceUntil = Date.now() + 1500;
          var message = 'Unable to submit Treating Iron report.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
          }

          toastr.error(message, 'Submit failed', {
            positionClass: 'toast-bottom-left',
            "showMethod": "slideDown",
            "hideMethod": "slideUp",
            "progressBar": true,
            timeOut: 4000,
            fadeOut: 1000,
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
      var ntir_10 = [];
      var nmpr_12 = [];
      var mt = {};
      var contactway = [];

			$(document).ready(function () {
				$('.contactway').each(function () {
					var id = this.id;
					toggleInspectionType(id, $(this).is(':checked'));
				});

				mpi_check();
			});
			function mpi_check() {
				$.each($('.magnet'), function (value) {
					$(this).prop("disabled", $("#nmpr_120").is(':checked') ? false : true);
				});
				$.each($('.uvl'), function (value) {
					$(this).prop("disabled", $("#nmpr_121").is(':checked') ? false : true);
				});
				$.each($('.coil'), function (value) {
					$(this).prop("disabled", $("#nmpr_122").is(':checked') ? false : true);
				});
			}

			function toggleInspectionType(id, checked) {
				if (!checked) {
					$('.' + id + '-section').find('input[type="text"], textarea').val('NA');
					$('.' + id + '-section').find('input, button, input[type="checkbox"], textarea').prop('disabled', true);
				} else {
					$('.' + id + '-section').find('input[type="text"], textarea').each(function() {
					    if ($(this).val() === 'NA') {
					        $(this).val('');
					    }
					});
					$('.' + id + '-section').find('input, button, input[type="checkbox"], textarea').prop('disabled', false);
				}
			}


      $(".contactway").each(function (index, value) {
        if ($(this).is(':checked')) {
          $('.' + this.id).prop("disabled", false);
          contactway.push(value.id);
        } 
      });


      $('.contactway').on('ifChecked', function (event) {
        var id = this.id;
				toggleInspectionType(id, true);
        contactway.push(id);
      });
      $('.contactway').on('ifUnchecked', function (event) {
        var id = this.id;
				toggleInspectionType(id, false);
        index = contactway.indexOf(id);
        contactway.splice(index, 1);
      });

      $.each($('.ntir_10'), function (value) {
        if ($(this).is(':checked')) {
          var id = this.id;
          if (id === 'other') {
            $('#ntir_11').prop("disabled", false);
          }
          ntir_10.push(id);
        }
      });

      $('.steps-validation').on('ifChecked', '.ntir_10', function () {
        var id = this.id;
        if (id === 'other') {
          $('#ntir_11').prop("disabled", false);
        }
        ntir_10.push(id);
      });
      $('.steps-validation').on('ifUnchecked', '.ntir_10', function (event) {
        var id = this.id;
        if (id === 'other') {
          $('#ntir_11').prop("disabled", true).val('');
        }
        index1 = ntir_10.indexOf(id);
        ntir_10.splice(index1, 1);
      });
      if ($("#nmpr_120").is(':checked')) {

        $.each($('.magnet'), function (value) {

          if (this.value !== '') {
            mt = {}
            mt.id = this.id;
            mt.value = this.value;
            // nmpr_12.push(mt);
          }

          $(this).prop("disabled", false);

        });
      } else {
        $("#nmpr_120").prop("disabled", false);
      }


      if ($("#nmpr_121").is(':checked')) {

        $.each($('.uvl'), function (value) {
          if (this.value !== '') {
            mt.id = this.id;
            mt.value = this.value;
            // nmpr_12.push(mt);
          }
          $(this).prop("disabled", false);
        });
      } else {
        $("#nmpr_121").prop("disabled", false);
      }

      if ($("#nmpr_122").is(':checked')) {

        $.each($('.coil'), function (value) {
          if (this.value !== '') {
            mt.id = this.id;
            mt.value = this.value;
            // nmpr_12.push(mt);
          }
          $(this).prop("disabled", false);
        });
      } else {
        $("#nmpr_122").prop("disabled", false);
      }
      $('.steps-validation').on('ifChecked', '#nmpr_120', function () {
        $.each($('.magnet'), function (value) {
          if (this.value !== '') {
            mt.id = this.id;
            mt.value = this.value;
            // nmpr_12.push(mt);
          }
          $(this).prop("disabled", false);
        });

      });
      $('.steps-validation').on('ifUnchecked', '#nmpr_120', function (event) {
        $.each($('.magnet'), function (value) {
          index1 = nmpr_12.map(function (e) {
            return e.id;
          }).indexOf(this.id);
          nmpr_12.splice(index1, 1);
          $(this).prop("disabled", true).val('');
        });
      });

      $('.steps-validation').on('ifChecked', '#nmpr_121', function () {
        $.each($('.uvl'), function (value) {
          if (this.value !== '') {
            mt.id = this.id;
            mt.value = this.value;
            // nmpr_12.push(mt);
          }
          $(this).prop("disabled", false);
        });
      });
      $('.steps-validation').on('ifUnchecked', '#nmpr_121', function (event) {
        $.each($('.uvl'), function (value) {
          index1 = nmpr_12.map(function (e) {
            return e.id;
          }).indexOf(this.id);
          nmpr_12.splice(index1, 1);
          $(this).prop("disabled", true).val('');
        });
      });

      $('.steps-validation').on('ifChecked', '#nmpr_122', function () {
        $.each($('.coil'), function (value) {
          if (this.value !== '') {
            mt.id = this.id;
            mt.value = this.value;
            // nmpr_12.push(mt);
          }
          $(this).prop("disabled", false);
        });
      });
      $('.steps-validation').on('ifUnchecked', '#nmpr_122', function (event) {
        $.each($('.coil'), function (value) {
          index1 = nmpr_12.map(function (e) {
            return e.id;
          }).indexOf(this.id);
          nmpr_12.splice(index1, 1);
          $(this).prop("disabled", true).val('');
        });
      });


      $('.steps-validation').on('ifChecked', '.contactway', function () {
        $('.' + this.id).prop("disabled", false);
      });

      $('.steps-validation').on('ifUnchecked', '.contactway', function () {
        $('.' + this.id).prop({"disabled": true, "checked": false});
      });
      // $('.steps-validation').on('change','.mt', function(){
      // 	alert();
      // 	index1 = nmpr_12.map(function(e) { return e.id; }).indexOf(this.id);
      // 	if(this.value !== ''){
      // 		var mt = {};
      // 		mt.id = this.id;
      // 		mt.value = this.value;
      // 		if(index1 >= 0){
      //
      // 			nmpr_12.splice(index1, 1);
      // 		}
      // 		nmpr_12.push(mt);
      // 	}
      // 	else
      // 	{
      // 		nmpr_12.splice(index1, 1);
      // 	}
      // });
      $('.repeater').repeater({
        show: function () {
          $(this).slideDown();
          $(this).find('img').attr('src', '');
        },
        hide: function (remove) {
          if (confirm('Are you sure you want to remove this item?')) {
            $(this).slideUp(remove);
          }
        },
      });

      var pop = 12;
      $('.steps-validation').on("change", '#suporcli', function (e) {
        $('#lcr_60, #lcr_70').val('');
        if (this.checked == true) {
          pop = 12;
        }
        else {
          pop = 6;
        }
        $('#months').text(pop);
      });

      $(".datepicker-default").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
        constrainInput: false,
      }).on("change", function () {
        var date = new Date($(this).datepicker("getDate").toISOString());
        date.setMonth(date.getMonth() + pop);
        date.setDate(date.getDate() - 1);
        $('#lcr_70').val(('0' + date.getDate()).slice(-2) + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + date.getFullYear());
      });
    </script>
@endpush
