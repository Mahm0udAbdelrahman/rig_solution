{{-- Equipment add / edit wizard (same Step 1 / 2 / 3 layout as the other add forms); $equipment is null when creating --}}
@php
    $equipment = $equipment ?? null;
    $intervalOptions = array_keys(\App\Models\GeneralInfo\EquipmentControlledList::$INTERVALS);
    $currentInterval = ($equipment ? $equipment->interval : null) ?: \App\Models\GeneralInfo\EquipmentControlledList::$DEFAULT_INTERVAL;
    $currentAlarm = $equipment ? $equipment->recalibration_alarm : 'Calibrated';
    $currentStatus = ($equipment ? $equipment->status : null) ?: 'Active';
@endphp
<section id="validation">
    <div class="row">
        <div class="col-12">
            <form action="#" id="equipmentForm" class="steps-validation wizard-circle">
                @if($equipment)
                    @method('PUT')
                @endif

                <h6>Step 1</h6>
                <fieldset>
                    <h5 class="mb-1"><i class="la la-cube mr-25"></i>Equipment Info</h5>
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <div class="controls">
                                                <label for="equipment_description">Equipment Description <span class="text-danger">*</span></label>
                                                <input type="text" id="equipment_description" name="equipment_description" class="form-control" placeholder="e.g. Water Bag, Load Cell, Flow Detector UT" value="{{ $equipment->equipment_description ?? '' }}" required data-validation-required-message="This description field is required">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <div class="controls">
                                                <label for="internal_code">Internal Code</label>
                                                <input type="text" id="internal_code" name="internal_code" class="form-control text-uppercase" placeholder="e.g. RS-NL-WB-72-01" value="{{ $equipment->internal_code ?? '' }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="manufacturer">Manufacturer</label>
                                            <input type="text" id="manufacturer" name="manufacturer" class="form-control" placeholder="e.g. Seaflex, DILLON, GE Inspection" value="{{ $equipment->manufacturer ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="model_type">Model / Type</label>
                                            <input type="text" id="model_type" name="model_type" class="form-control" placeholder="e.g. WB35, EDXTREME, E600, DM-4" value="{{ $equipment->model_type ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="capacity_range">Capacity / Range</label>
                                            <input type="text" id="capacity_range" name="capacity_range" class="form-control" placeholder="e.g. 35-Ton, 5-Ton, 0-1000 PSI, N/A" value="{{ $equipment->capacity_range ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="serial_number">Serial Number</label>
                                            <input type="text" id="serial_number" name="serial_number" class="form-control" placeholder="e.g. 2372, WB3508, 1009541" value="{{ $equipment->serial_number ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="date_into_service_display">Date into Service</label>
                                            @include('layouts.general-info.equipmentControlledList.partials.date-input', ['name' => 'date_into_service', 'value' => $equipment->date_into_service ?? null])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <h6>Step 2</h6>
                <fieldset>
                    <h5 class="mb-1"><i class="la la-sliders mr-25"></i>Maintenance & Calibration</h5>
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="calibration_date_display">Calibration Date</label>
                                            @include('layouts.general-info.equipmentControlledList.partials.date-input', ['name' => 'calibration_date', 'value' => $equipment->calibration_date ?? null])
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label class="d-block">Calibration Interval</label>
                                            <div class="pt-50">
                                                @foreach($intervalOptions as $i => $intervalOption)
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="interval_{{ $i }}" name="interval" value="{{ $intervalOption }}" class="custom-control-input interval-radio" {{ $currentInterval == $intervalOption ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="interval_{{ $i }}">{{ $intervalOption == 'Annual' ? 'Annual (1 Year)' : $intervalOption }}</label>
                                                    </div>
                                                @endforeach
                                                {{-- Keep an older interval (e.g. Pre-Use) so it is not lost on update --}}
                                                @if(!in_array($currentInterval, $intervalOptions))
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="interval_legacy" name="interval" value="{{ $currentInterval }}" class="custom-control-input interval-radio" checked>
                                                        <label class="custom-control-label" for="interval_legacy">{{ $currentInterval }}</label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="calibration_due_date_display">Calibration Due Date</label>
                                            @include('layouts.general-info.equipmentControlledList.partials.date-input', ['name' => 'calibration_due_date', 'value' => $equipment->calibration_due_date ?? null, 'readonly' => true])
                                            <small class="text-muted">Calculated from Calibration Date + Interval</small>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="calibrated_by">Calibrated By</label>
                                            <input type="text" list="calibrated_by_options" id="calibrated_by" name="calibrated_by" class="form-control" placeholder="e.g. OMEGA, NIS, First, In-House" value="{{ $equipment->calibrated_by ?? '' }}">
                                            <datalist id="calibrated_by_options">
                                                <option value="OMEGA">
                                                <option value="NIS">
                                                <option value="First">
                                                <option value="Seaflex">
                                                <option value="Rig Solution Lab">
                                                <option value="N/A">
                                            </datalist>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="recalibration_alarm">Re-calibration Alarm</label>
                                            <select id="recalibration_alarm" name="recalibration_alarm" class="form-control">
                                                @foreach(['Calibrated', 'Re-Calibrate', 'Due Soon', 'N/A'] as $alarmOption)
                                                    <option value="{{ $alarmOption }}" {{ $currentAlarm == $alarmOption ? 'selected' : '' }}>{{ $alarmOption }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <h6>Step 3</h6>
                <fieldset>
                    <h5 class="mb-1"><i class="la la-map-marker mr-25"></i>Location, Status & Notes</h5>
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="location_department">Location / Department</label>
                                            <input type="text" list="location_options" id="location_department" name="location_department" class="form-control" placeholder="e.g. Store, Workshop, NDT Lab" value="{{ $equipment ? $equipment->location_department : 'Store' }}">
                                            <datalist id="location_options">
                                                <option value="Store">
                                                <option value="Workshop">
                                                <option value="NDT Lab">
                                                <option value="Lifting Lab">
                                                <option value="Rig Site">
                                                <option value="Main Yard">
                                            </datalist>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="status">Current Status</label>
                                            <select id="status" name="status" class="form-control">
                                                @foreach(['Active', 'Under Maintenance', 'Under Calibration', 'Out of Service'] as $statusOption)
                                                    <option value="{{ $statusOption }}" {{ $currentStatus == $statusOption ? 'selected' : '' }}>{{ $statusOption }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="date_removed_from_service">Date Removed From Service</label>
                                            <input type="text" id="date_removed_from_service" name="date_removed_from_service" class="form-control" placeholder="Date or reason (if removed)" value="{{ $equipment->date_removed_from_service ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="notes">Notes / Remarks</label>
                                            <textarea id="notes" name="notes" rows="3" class="form-control" placeholder="Any additional notes or comments (e.g. need check date, certificate ref, etc.)...">{{ $equipment->notes ?? '' }}</textarea>
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
