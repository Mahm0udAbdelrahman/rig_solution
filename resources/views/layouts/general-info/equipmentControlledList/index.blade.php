@extends('layouts.app')

@include('layouts.styles.datatables')

@section('content')
<div class="content-body">
    <!-- Quick Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up border-top-primary border-top-3">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="info">{{ $totalEquipments }}</h3>
                                <h6 class="text-muted font-small-3">Total Controlled Equipments</h6>
                            </div>
                            <div>
                                <i class="la la-cube info font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up border-top-success border-top-3">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="success">{{ $activeEquipments }}</h3>
                                <h6 class="text-muted font-small-3">Active Equipments</h6>
                            </div>
                            <div>
                                <i class="la la-check-circle success font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up border-top-warning border-top-3">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="warning">{{ $underMaintenance + $underCalibration }}</h3>
                                <h6 class="text-muted font-small-3">Maintenance / Calibration</h6>
                            </div>
                            <div>
                                <i class="la la-wrench warning font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up border-top-danger border-top-3">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="danger">{{ $recalibrateEquipments }}</h3>
                                <h6 class="text-muted font-small-3">Re-Calibrate (Due Date Passed)</h6>
                            </div>
                            <div>
                                <i class="la la-refresh danger font-large-2 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Section -->
    <section class="users-list-wrapper">
        <div class="users-list">
            <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
                <div>
                    @can('create', App\Models\GeneralInfo\EquipmentControlledList::class)
                    <a href="{{ route('equipment-controlled-list.create') }}" class="btn btn-primary font-weight-bold shadow-sm">
                        <i class="la la-plus"></i> Add New Equipment
                    </a>
                    @endcan
                    <div class="btn-group ml-50">
                        <button type="button" class="btn btn-success font-weight-bold shadow-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="la la-download"></i> Export ISO Form
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('equipment-controlled-list.export', 'pdf') }}"><i class="la la-file-pdf-o mr-50"></i> PDF</a>
                            <a class="dropdown-item" href="{{ route('equipment-controlled-list.export', 'excel') }}"><i class="la la-file-excel-o mr-50"></i> Excel</a>
                        </div>
                    </div>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm filter-status-btn active" data-status="">All Status</button>
                    <button type="button" class="btn btn-outline-success btn-sm filter-status-btn" data-status="Active">Active</button>
                    <button type="button" class="btn btn-outline-warning btn-sm filter-status-btn" data-status="Under Maintenance">Under Maintenance</button>
                    <button type="button" class="btn btn-outline-info btn-sm filter-status-btn" data-status="Under Calibration">Under Calibration</button>
                    <button type="button" class="btn btn-outline-danger btn-sm filter-status-btn" data-status="Re-Calibrate">Re-Calibrate</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm filter-status-btn" data-status="Out of Service">Out of Service</button>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" style="border-radius: 4px 4px 0 0;">
                    <h4 class="card-title text-white mb-0"><i class="la la-table mr-1"></i>Equipment Controlled List</h4>
                    <span class="badge badge-light text-primary font-weight-bold">Integrated Management System</span>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="equipment-table" class="table table-striped table-bordered dataex-fixh-responsive row-grouping w-100">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Description</th>
                                        <th>Internal Code</th>
                                        <th>Manufacturer</th>
                                        <th>Model / Type</th>
                                        <th>Capacity / Range</th>
                                        <th>Serial No</th>
                                        <th>Service Date</th>
                                        <th>Interval</th>
                                        <th>Calib. Date</th>
                                        <th>Due Date</th>
                                        <th>Calibrated By</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Date Removed</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

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

@section('footer')
<script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.fixedHeader.min.js') }}"></script>

<script>
$(document).ready(function() {
    var statusFilter = '';

    var table = $('#equipment-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('getDataForDataTable.equipment-controlled-list') }}",
            data: function (d) {
                d.status_filter = statusFilter;
            }
        },
        columns: [
            { data: 'equipment_description', name: 'equipment_description' },
            { data: 'internal_code', name: 'internal_code' },
            { data: 'manufacturer', name: 'manufacturer' },
            { data: 'model_type', name: 'model_type' },
            { data: 'capacity_range', name: 'capacity_range' },
            { data: 'serial_number', name: 'serial_number' },
            { data: 'date_into_service', name: 'date_into_service' },
            { data: 'interval', name: 'interval' },
            { data: 'calibration_date', name: 'calibration_date' },
            { data: 'calibration_due_date', name: 'calibration_due_date' },
            { data: 'calibrated_by', name: 'calibrated_by' },
            { data: 'location_department', name: 'location_department' },
            { data: 'status', name: 'status' },
            { data: 'date_removed_from_service', name: 'date_removed_from_service' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        dom: '<"top"lfB>rt<"bottom"ip><"clear">',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search equipment...",
            lengthMenu: "Show _MENU_ records",
        }
    });

    // Filter Buttons Click
    $('.filter-status-btn').on('click', function() {
        $('.filter-status-btn').removeClass('active');
        $(this).addClass('active');
        statusFilter = $(this).data('status');
        table.ajax.reload();
    });

    // Delete Item with SweetAlert
    $(document).on('click', '.delete', function() {
        var id = $(this).data('id');
        var deleteUrl = "{{ route('equipment-controlled-list.index') }}/" + id;

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Are You Sure ?',
                text: "This item will be permanently deleted!",
                type: 'error',
                showCancelButton: true,
                confirmButtonColor: '#ff4961',
                cancelButtonColor: '#2c303b',
                confirmButtonText: 'YES, DELETE IT !',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-dark ml-1',
                cancelButtonText: 'CANCEL',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: deleteUrl,
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function(response) {
                            toastr.success(response.success || 'Item deleted successfully', 'Deleted !', {
                                positionClass: 'toast-bottom-left',
                                showMethod: 'slideDown',
                                hideMethod: 'slideUp',
                                progressBar: true,
                                timeOut: 1500
                            });
                            table.ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            toastr.error('Error occurred while deleting item.', 'Error');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Cancelled',
                        text: 'Your data is safe :)',
                        type: 'info',
                        confirmButtonClass: 'btn btn-success',
                    });
                }
            });
        } else {
            if (confirm('Are you sure you want to delete this equipment?')) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: deleteUrl,
                    type: 'DELETE',
                    dataType: 'JSON',
                    success: function(response) {
                        toastr.success(response.success || 'Item deleted successfully');
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        toastr.error('Error occurred while deleting item.');
                    }
                });
            }
        }
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
                table.ajax.reload(null, false);
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
                                <label class="font-weight-bold text-muted small">Location / Department</label>
                                <div>${item.location_department || 'N/A'}</div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold text-muted small">Current Status</label>
                                <div><span class="badge ${res.display_status === 'Re-Calibrate' ? 'badge-danger' : 'badge-success'}">${res.display_status}</span></div>
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
