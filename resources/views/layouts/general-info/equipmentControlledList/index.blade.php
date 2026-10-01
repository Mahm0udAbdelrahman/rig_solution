@extends('layouts.app')

@include('layouts.styles.datatables')

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
						@if ($totalEquipments == 0)
								@include('layouts.repeated.nodata', ['route' => auth()->user()->can('create', App\Models\GeneralInfo\EquipmentControlledList::class) ? 'equipment-controlled-list' : null])
						@else
								@can('create', 'App\Models\GeneralInfo\EquipmentControlledList')
										<a href="{{route('equipment-controlled-list.create')}}" class="btn btn-primary clear"><i class="la la-plus"></i> Create New {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}}</a>
								@endcan
								<div class="card">
										<div class="card-content">
												<div class="card-body">
														<div class="table-responsive">
																<table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
																		<thead>
																				<tr>
																					<th>Internal Code</th>
																					<th width="15%">Description</th>
																					<th>Serial No</th>
																					<th>Manufacturer</th>
																					<th>Model / Type</th>
																					<th>Capacity / Range</th>
																					<th>Calib. Date</th>
																					<th>Due Date</th>
																					<th>Calibrated By</th>
																					<th>Location</th>
																					<th>Alarm</th>
																					<th>Status</th>
																					<th>Actions</th>
																				</tr>
																		</thead>
																</table>
														</div>
												</div>
										</div>
								</div>
						@endif
				</div>
		</section>

<!-- View Details Modal -->
<div class="modal fade" id="equipmentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="equipmentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="equipmentDetailsModalLabel"><i class="la la-info-circle mr-1"></i>Equipment Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="equipmentDetailsContent">
                <div class="text-center p-3">
                    <i class="la la-spinner la-spin font-large-2 text-primary"></i>
                    <p class="mt-1">Loading equipment data...</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary text-white font-weight-bold px-3 shadow-sm" style="color: #ffffff !important; background-color: #4f5d73; border-color: #4f5d73;" data-dismiss="modal">
                    <i class="la la-times mr-1" style="color: #ffffff !important;"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Certificate Modal -->
<div class="modal fade" id="certificateModal" tabindex="-1" role="dialog" aria-labelledby="certificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="certificateForm" enctype="multipart/form-data">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="certificateModalLabel"><i class="la la-certificate mr-1"></i>Equipment Certificate</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="currentCertificate" class="alert alert-light mb-2 d-none">
                        <span class="font-weight-bold">Current certificate:</span>
                        <a href="#" target="_blank" id="currentCertificateLink"></a>
                        <div class="small text-muted mt-50">Uploading a new file will replace it.</div>
                    </div>
                    <div class="form-group mb-0">
                        <label for="certificateFile">Certificate file (PDF or image, max 10 MB)</label>
                        <input type="file" class="form-control" id="certificateFile" name="certificate" accept=".pdf,.jpg,.jpeg,.png,.webp,application/pdf,image/*" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="certificateSubmit"><i class="la la-upload mr-1"></i>Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@include('layouts.scripts.datatables', ['route' => 'equipment-controlled-list',
'columns' => ['internal_code', 'equipment_description', 'serial_number', 'manufacturer', 'model_type', 'capacity_range', 'calibration_date', 'calibration_due_date', 'calibrated_by', 'location_department', 'recalibration_alarm', 'status', 'action'],
'select_fields' => ['status' => ['' => 'ALL', 'Active' => 'Active', 'Under Maintenance' => 'Under Maintenance', 'Under Calibration' => 'Under Calibration', 'Out of Service' => 'Out of Service']],
'disable_column_filters' => ['recalibration_alarm', 'action'],
'non_orderable_columns' => ['action'],
'non_searchable_columns' => ['action'],
'datatable_options' => ['ordering' => true, 'order' => [[0, 'asc']]]])

@section('ajax')
<script>
$(document).ready(function() {
    var table = window.currentDataTable;
    if (!table) {
        return;
    }

    // Alarm presets next to Clear Filters, styled like the inspection All / Approved / Need Approve pills
    var alarmFilter = '';
    var $presets = $('<div class="listing-toolbar-presets"></div>')
        .append('<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-equipment-preset is-active" data-preset="">All ({{ $totalEquipments }})</button>')
        .append('<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-equipment-preset" data-preset="calibrated">Calibrated</button>')
        .append('<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-equipment-preset" data-preset="alarm">Re-calibration Due ({{ $alarmDueCount }})</button>');
    $('.listing-table-toolbar .listing-toolbar-actions').first().after($presets);

    table.on('preXhr.dt', function(e, settings, data) {
        data.alarm_filter = alarmFilter;
    });

    $(document).on('click', '.js-equipment-preset', function() {
        alarmFilter = $(this).data('preset') || '';
        $('.js-equipment-preset').removeClass('is-active');
        $(this).addClass('is-active');
        table.draw();
    });

    // bound on the button itself so it runs before the shared (delegated) Clear Filters redraw
    $('.js-clear-datatable-filters').on('click', function() {
        alarmFilter = '';
        $('.js-equipment-preset').removeClass('is-active');
        $('.js-equipment-preset[data-preset=""]').addClass('is-active');
    });

    // Upload / Replace Certificate
    var certificateUploadUrl = '';

    $(document).on('click', '.upload-certificate', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var url = $(this).data('url');

        certificateUploadUrl = "{{ route('equipment-controlled-list.index') }}/" + id + "/certificate";
        $('#certificateForm')[0].reset();

        if (url) {
            $('#currentCertificateLink').attr('href', url).text(name || 'View certificate');
            $('#currentCertificate').removeClass('d-none');
            $('#certificateSubmit').html('<i class="la la-upload mr-1"></i>Replace');
        } else {
            $('#currentCertificate').addClass('d-none');
            $('#certificateSubmit').html('<i class="la la-upload mr-1"></i>Upload');
        }

        $('#certificateModal').modal('show');
    });

    $('#certificateForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#certificateSubmit');
        var label = $btn.html();

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: certificateUploadUrl,
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'JSON',
            beforeSend: function() {
                $btn.prop('disabled', true).html('<i class="la la-refresh spinner mr-1"></i>Uploading...');
            },
            success: function(response) {
                toastr.success(response.success, 'Done !', { positionClass: 'toast-bottom-left', progressBar: true, timeOut: 1500 });
                $('#certificateModal').modal('hide');
                window.currentDataTable.ajax.reload(null, false);
            },
            error: function(xhr) {
                var message = 'Error occurred while uploading the certificate.';
                if (xhr.status === 403) {
                    message = 'You do not have permission to upload certificates.';
                } else if (xhr.status === 413) {
                    message = 'The file is too large for the server.';
                } else if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.certificate) {
                    message = xhr.responseJSON.errors.certificate[0];
                }
                toastr.error(message, 'Error');
            },
            complete: function() {
                $btn.prop('disabled', false).html(label);
            }
        });
    });

    // View Details Modal
    $(document).on('click', '.view-details', function() {
        var id = $(this).data('id');
        var showUrl = "{{ route('equipment-controlled-list.index') }}/" + id;

        $('#equipmentDetailsModal').modal('show');
        $('#equipmentDetailsContent').html('<div class="text-center p-3"><i class="la la-spinner la-spin font-large-2 text-primary"></i><p class="mt-1">Loading...</p></div>');

        $.ajax({
            url: showUrl,
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {
                if (res.success) {
                    var item = res.data;
                    var html = `
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="font-weight-bold text-muted small">EQUIPMENT DESCRIPTION</label>
                                <div class="font-large-1 text-primary font-weight-bold">${item.equipment_description || 'N/A'}</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="font-weight-bold text-muted small">INTERNAL CODE</label>
                                <div><span class="badge badge-info p-1 font-medium-1">${item.internal_code || 'N/A'}</span></div>
                            </div>
                        </div>
                        <hr class="my-1">
                        <div class="row mt-2">
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Manufacturer</label>
                                <div>${item.manufacturer || 'N/A'}</div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Model / Type</label>
                                <div>${item.model_type || 'N/A'}</div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Capacity / Range</label>
                                <div>${item.capacity_range || 'N/A'}</div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Serial Number</label>
                                <div><strong class="text-dark">${item.serial_number || 'N/A'}</strong></div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Date into Service</label>
                                <div>${res.formatted_service_date || 'N/A'}</div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Interval</label>
                                <div><span class="badge badge-light">${item.interval || 'N/A'}</span></div>
                            </div>
                        </div>
                        <hr class="my-1">
                        <div class="row mt-2 bg-light p-2 rounded">
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Calibration Date</label>
                                <div>${res.formatted_cal_date || 'N/A'}</div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Calibration Due Date</label>
                                <div><strong class="text-danger">${res.formatted_due_date || 'N/A'}</strong></div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Calibrated By</label>
                                <div>${item.calibrated_by || 'N/A'}</div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Alarm Status</label>
                                <div><span class="badge badge-warning">${item.recalibration_alarm || 'N/A'}</span></div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Location / Department</label>
                                <div>${item.location_department || 'N/A'}</div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Current Status</label>
                                <div><span class="badge badge-success">${item.status || 'Active'}</span></div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 mb-2">
                                <label class="font-weight-bold text-muted small">Date Removed From Service</label>
                                <div>${item.date_removed_from_service || 'N/A'}</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="font-weight-bold text-muted small">Notes / Remarks</label>
                                <div>${item.notes || 'No remarks recorded.'}</div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-12 mb-1">
                                <label class="font-weight-bold text-muted small">Certificate</label>
                                <div>${item.certificate_url
                                    ? `<a href="${item.certificate_url}" target="_blank"><i class="la la-certificate"></i> ${$('<div>').text(item.certificate_name || 'View certificate').html()}</a> <span class="text-muted small">(uploaded ${res.formatted_certificate_date})</span>`
                                    : 'No certificate uploaded.'}</div>
                            </div>
                        </div>
                    `;
                    $('#equipmentDetailsContent').html(html);
                } else {
                    $('#equipmentDetailsContent').html('<div class="alert alert-danger mb-0">Failed to load equipment data.</div>');
                }
            },
            error: function() {
                $('#equipmentDetailsContent').html('<div class="alert alert-danger mb-0">An error occurred while fetching details.</div>');
            }
        });
    });
});
</script>
@endsection
