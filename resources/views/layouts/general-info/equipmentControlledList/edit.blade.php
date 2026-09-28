@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title text-white mb-0">
                        <i class="la la-edit mr-1"></i>Edit Equipment: {{ $equipment->equipment_description }} ({{ $equipment->internal_code }})
                    </h4>
                    <a href="{{ route('equipment-controlled-list.index') }}" class="btn btn-sm btn-light font-weight-bold">
                        <i class="la la-arrow-left"></i> Back to List
                    </a>
                </div>
                <div class="card-content">
                    <div class="card-body p-3">
                        <form id="equipmentEditForm" method="POST" action="{{ route('equipment-controlled-list.update', $equipment->id) }}">
                            @csrf
                            @method('PUT')

                            <!-- SECTION 1: Equipment Info -->
                            <div class="border-bottom pb-2 mb-3">
                                <h5 class="text-primary font-weight-bold mb-2">
                                    <i class="la la-cube mr-1"></i>1. Equipment Specifications & Identification
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="equipment_description" class="font-weight-bold">Equipment Description <span class="text-danger">*</span></label>
                                            <input type="text" id="equipment_description" name="equipment_description" class="form-control" value="{{ old('equipment_description', $equipment->equipment_description) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="internal_code" class="font-weight-bold">Internal Code</label>
                                            <input type="text" id="internal_code" name="internal_code" class="form-control text-uppercase" value="{{ old('internal_code', $equipment->internal_code) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="manufacturer" class="font-weight-bold">Manufacturer</label>
                                            <input type="text" id="manufacturer" name="manufacturer" class="form-control" value="{{ old('manufacturer', $equipment->manufacturer) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="model_type" class="font-weight-bold">Model / Type</label>
                                            <input type="text" id="model_type" name="model_type" class="form-control" value="{{ old('model_type', $equipment->model_type) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="capacity_range" class="font-weight-bold">Capacity / Range</label>
                                            <input type="text" id="capacity_range" name="capacity_range" class="form-control" value="{{ old('capacity_range', $equipment->capacity_range) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="serial_number" class="font-weight-bold">Serial Number</label>
                                            <input type="text" id="serial_number" name="serial_number" class="form-control font-weight-bold text-dark" value="{{ old('serial_number', $equipment->serial_number) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="date_into_service" class="font-weight-bold">Date into Service</label>
                                            <input type="date" id="date_into_service" name="date_into_service" class="form-control" value="{{ $equipment->date_into_service ? \Carbon\Carbon::parse($equipment->date_into_service)->format('Y-m-d') : '' }}">
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
                                            <input type="date" id="calibration_date" name="calibration_date" class="form-control" value="{{ $equipment->calibration_date ? \Carbon\Carbon::parse($equipment->calibration_date)->format('Y-m-d') : '' }}">
                                        </div>
                                    </div>
                                    @php
                                        $intervalOptions = array_keys(\App\Models\GeneralInfo\EquipmentControlledList::$INTERVALS);
                                        $currentInterval = old('interval', $equipment->interval) ?: \App\Models\GeneralInfo\EquipmentControlledList::$DEFAULT_INTERVAL;
                                    @endphp
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold d-block">Calibration Interval</label>
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
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="calibration_due_date" class="font-weight-bold">Calibration Due Date</label>
                                            <input type="date" id="calibration_due_date" name="calibration_due_date" class="form-control" value="{{ $equipment->calibration_due_date ? \Carbon\Carbon::parse($equipment->calibration_due_date)->format('Y-m-d') : '' }}" readonly>
                                            <small class="text-muted">Calculated from Calibration Date + Interval</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="calibrated_by" class="font-weight-bold">Calibrated By</label>
                                            <input type="text" list="calibrated_by_options" id="calibrated_by" name="calibrated_by" class="form-control" value="{{ old('calibrated_by', $equipment->calibrated_by) }}">
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
                                                <option value="Calibrated" {{ $equipment->recalibration_alarm == 'Calibrated' ? 'selected' : '' }}>Calibrated</option>
                                                <option value="Re-Calibrate" {{ $equipment->recalibration_alarm == 'Re-Calibrate' ? 'selected' : '' }}>Re-Calibrate</option>
                                                <option value="Due Soon" {{ $equipment->recalibration_alarm == 'Due Soon' ? 'selected' : '' }}>Due Soon</option>
                                                <option value="N/A" {{ $equipment->recalibration_alarm == 'N/A' ? 'selected' : '' }}>N/A</option>
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
                                            <input type="text" list="location_options" id="location_department" name="location_department" class="form-control" value="{{ old('location_department', $equipment->location_department) }}">
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
                                                <option value="Active" {{ $equipment->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Under Maintenance" {{ $equipment->status == 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                                <option value="Under Calibration" {{ $equipment->status == 'Under Calibration' ? 'selected' : '' }}>Under Calibration</option>
                                                <option value="Out of Service" {{ $equipment->status == 'Out of Service' ? 'selected' : '' }}>Out of Service</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="date_removed_from_service" class="font-weight-bold">Date Removed From Service</label>
                                            <input type="text" id="date_removed_from_service" name="date_removed_from_service" class="form-control" value="{{ old('date_removed_from_service', $equipment->date_removed_from_service) }}" placeholder="Date or reason">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="notes" class="font-weight-bold">Notes / Remarks</label>
                                            <textarea id="notes" name="notes" rows="3" class="form-control">{{ old('notes', $equipment->notes) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end align-items-center mt-3 pt-2 border-top">
                                <a href="{{ route('equipment-controlled-list.index') }}" class="btn btn-secondary mr-1">Cancel</a>
                                <button type="submit" id="updateEquipmentBtn" class="btn btn-primary font-weight-bold px-3">
                                    <i class="la la-save"></i> Update Equipment
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
        var interval = $('input[name="interval"]:checked').val();
        var months = intervalMonths[interval];
        var calDate = $('#calibration_date').val();
        // Older intervals (e.g. Pre-Use) keep their saved due date and stay editable
        $('#calibration_due_date').prop('readonly', !!months);
        if (!months) {
            return;
        }
        if (!calDate) {
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
    $('#calibration_due_date').prop('readonly', !!intervalMonths[$('input[name="interval"]:checked').val()]);

    $('#equipmentEditForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#updateEquipmentBtn');
        $btn.prop('disabled', true).html('<i class="la la-spinner la-spin"></i> Updating...');

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            success: function(response) {
                toastr.success(response.success || 'Equipment updated successfully!', 'Success', {
                    positionClass: 'toast-bottom-left',
                    timeOut: 1500,
                    onHidden: function() {
                        window.location.href = response.redirect || "{{ route('equipment-controlled-list.index') }}";
                    }
                });
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="la la-save"></i> Update Equipment');
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var msg = Object.values(errors).flat().join('<br>');
                    toastr.error(msg, 'Validation Error');
                } else {
                    toastr.error('An error occurred while updating.', 'Error');
                }
            }
        });
    });
});
</script>
@endsection
