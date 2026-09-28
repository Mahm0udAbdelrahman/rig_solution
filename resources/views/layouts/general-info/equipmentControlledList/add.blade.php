@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title text-white mb-0">
                        <i class="la la-plus-circle mr-1"></i>Add New Equipment to Controlled List
                    </h4>
                    <a href="{{ route('equipment-controlled-list.index') }}" class="btn btn-sm btn-light font-weight-bold">
                        <i class="la la-arrow-left"></i> Back to List
                    </a>
                </div>
                <div class="card-content">
                    <div class="card-body p-3">
                        <form id="equipmentAddForm" method="POST" action="{{ route($route) }}">
                            @csrf

                            <!-- SECTION 1: Equipment Info -->
                            <div class="border-bottom pb-2 mb-3">
                                <h5 class="text-primary font-weight-bold mb-2">
                                    <i class="la la-cube mr-1"></i>1. Equipment Specifications & Identification
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="equipment_description" class="font-weight-bold">Equipment Description <span class="text-danger">*</span></label>
                                            <input type="text" id="equipment_description" name="equipment_description" class="form-control" placeholder="e.g. Water Bag, Load Cell, Flow Detector UT" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="internal_code" class="font-weight-bold">Internal Code</label>
                                            <input type="text" id="internal_code" name="internal_code" class="form-control text-uppercase" placeholder="e.g. RS-NL-WB-72-01">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="manufacturer" class="font-weight-bold">Manufacturer</label>
                                            <input type="text" id="manufacturer" name="manufacturer" class="form-control" placeholder="e.g. Seaflex, DILLON, GE Inspection">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="model_type" class="font-weight-bold">Model / Type</label>
                                            <input type="text" id="model_type" name="model_type" class="form-control" placeholder="e.g. WB35, EDXTREME, E600, DM-4">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="capacity_range" class="font-weight-bold">Capacity / Range</label>
                                            <input type="text" id="capacity_range" name="capacity_range" class="form-control" placeholder="e.g. 35-Ton, 5-Ton, 0-1000 PSI, N/A">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="serial_number" class="font-weight-bold">Serial Number</label>
                                            <input type="text" id="serial_number" name="serial_number" class="form-control font-weight-bold text-dark" placeholder="e.g. 2372, WB3508, 1009541">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="date_into_service" class="font-weight-bold">Date into Service</label>
                                            <input type="date" id="date_into_service" name="date_into_service" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 2: Maintenance & Calibration -->
                            <div class="border-bottom pb-2 mb-3">
                                <h5 class="text-primary font-weight-bold mb-2">
                                    <i class="la la-sliders mr-1"></i>2. Maintenance & Calibration Tracking
                                </h5>
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="calibration_date" class="font-weight-bold">Calibration Date</label>
                                            <input type="date" id="calibration_date" name="calibration_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold d-block">Calibration Interval</label>
                                            <div class="pt-50">
                                                @foreach(array_keys(\App\Models\GeneralInfo\EquipmentControlledList::$INTERVALS) as $i => $intervalOption)
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="interval_{{ $i }}" name="interval" value="{{ $intervalOption }}" class="custom-control-input interval-radio" {{ $intervalOption == \App\Models\GeneralInfo\EquipmentControlledList::$DEFAULT_INTERVAL ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="interval_{{ $i }}">{{ $intervalOption == 'Annual' ? 'Annual (1 Year)' : $intervalOption }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="calibration_due_date" class="font-weight-bold">Calibration Due Date</label>
                                            <input type="date" id="calibration_due_date" name="calibration_due_date" class="form-control" readonly>
                                            <small class="text-muted">Calculated from Calibration Date + Interval</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="calibrated_by" class="font-weight-bold">Calibrated By</label>
                                            <input type="text" list="calibrated_by_options" id="calibrated_by" name="calibrated_by" class="form-control" placeholder="e.g. OMEGA, NIS, First, In-House">
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
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="recalibration_alarm" class="font-weight-bold">Re-calibration Alarm</label>
                                            <select id="recalibration_alarm" name="recalibration_alarm" class="form-control">
                                                <option value="Calibrated">Calibrated</option>
                                                <option value="Re-Calibrate">Re-Calibrate</option>
                                                <option value="Due Soon">Due Soon</option>
                                                <option value="N/A">N/A</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 3: Status, Location & Remarks -->
                            <div class="mb-3">
                                <h5 class="text-primary font-weight-bold mb-2">
                                    <i class="la la-map-marker mr-1"></i>3. Location, Status & Notes
                                </h5>
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="location_department" class="font-weight-bold">Location / Department</label>
                                            <input type="text" list="location_options" id="location_department" name="location_department" class="form-control" placeholder="e.g. Store, Workshop, NDT Lab" value="Store">
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
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="status" class="font-weight-bold">Current Status</label>
                                            <select id="status" name="status" class="form-control font-weight-bold">
                                                <option value="Active" selected>Active</option>
                                                <option value="Under Maintenance">Under Maintenance</option>
                                                <option value="Under Calibration">Under Calibration</option>
                                                <option value="Out of Service">Out of Service</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="date_removed_from_service" class="font-weight-bold">Date Removed From Service</label>
                                            <input type="text" id="date_removed_from_service" name="date_removed_from_service" class="form-control" placeholder="Date or reason (if removed)">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="notes" class="font-weight-bold">Notes / Remarks</label>
                                            <textarea id="notes" name="notes" rows="3" class="form-control" placeholder="Any additional notes or comments (e.g. need check date, certificate ref, etc.)..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end align-items-center mt-3 pt-2 border-top">
                                <a href="{{ route('equipment-controlled-list.index') }}" class="btn btn-secondary mr-1">Cancel</a>
                                <button type="submit" id="saveEquipmentBtn" class="btn btn-primary font-weight-bold px-3">
                                    <i class="la la-check"></i> Save Equipment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
$(document).ready(function() {
    var intervalMonths = @json(\App\Models\GeneralInfo\EquipmentControlledList::$INTERVALS);

    // Calibration Due Date = Calibration Date + selected interval (clamped to month end, e.g. 31-Aug + 6 Months = 28/29-Feb)
    function updateDueDate() {
        var months = intervalMonths[$('input[name="interval"]:checked').val()];
        var calDate = $('#calibration_date').val();
        if (!months || !calDate) {
            $('#calibration_due_date').val('');
            return;
        }
        var parts = calDate.split('-').map(Number);
        var target = new Date(parts[0], parts[1] - 1 + months, 1);
        var lastDay = new Date(target.getFullYear(), target.getMonth() + 1, 0).getDate();
        target.setDate(Math.min(parts[2], lastDay));
        var pad = function(n) { return String(n).padStart(2, '0'); };
        $('#calibration_due_date').val(target.getFullYear() + '-' + pad(target.getMonth() + 1) + '-' + pad(target.getDate()));
    }

    $('input[name="interval"], #calibration_date').on('change input', updateDueDate);
    updateDueDate();

    $('#equipmentAddForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#saveEquipmentBtn');
        $btn.prop('disabled', true).html('<i class="la la-spinner la-spin"></i> Saving...');

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            success: function(response) {
                toastr.success(response.success || 'Equipment added successfully!', 'Success', {
                    positionClass: 'toast-bottom-left',
                    timeOut: 1500,
                    onHidden: function() {
                        window.location.href = response.redirect || "{{ route('equipment-controlled-list.index') }}";
                    }
                });
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="la la-check"></i> Save Equipment');
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var msg = Object.values(errors).flat().join('<br>');
                    toastr.error(msg, 'Validation Error');
                } else {
                    toastr.error('An error occurred while saving.', 'Error');
                }
            }
        });
    });
});
</script>
@endsection
