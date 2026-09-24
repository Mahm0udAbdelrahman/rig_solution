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
                                <h3 class="danger">{{ $alarmDueCount }}</h3>
                                <h6 class="text-muted font-small-3">Re-calibration Alarms / Due</h6>
                            </div>
                            <div>
                                <i class="la la-bell danger font-large-2 float-right"></i>
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
                    <a href="{{ route('equipment-controlled-list.create') }}" class="btn btn-primary font-weight-bold shadow-sm">
                        <i class="la la-plus"></i> Add New Equipment
                    </a>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm filter-status-btn active" data-status="">All Status</button>
                    <button type="button" class="btn btn-outline-success btn-sm filter-status-btn" data-status="Active">Active</button>
                    <button type="button" class="btn btn-outline-warning btn-sm filter-status-btn" data-status="Under Maintenance">Under Maintenance</button>
                    <button type="button" class="btn btn-outline-info btn-sm filter-status-btn" data-status="Under Calibration">Under Calibration</button>
                    <button type="button" class="btn btn-outline-danger btn-sm filter-status-btn" data-status="Out of Service">Out of Service</button>
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
                                        <th>Alarm</th>
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
@endsection

@section('footer')
<script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.fixedHeader.min.js') }}"></script>

<script>
$(document).ready(function() {
    var statusFilter = '';
    var alarmFilter = '';

    var table = $('#equipment-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('getDataForDataTable.equipmentControlledList') }}",
            data: function (d) {
                d.status_filter = statusFilter;
                d.alarm_filter = alarmFilter;
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
            { data: 'recalibration_alarm', name: 'recalibration_alarm' },
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
