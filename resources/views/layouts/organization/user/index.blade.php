@extends('layouts.app')

@include('layouts.styles.datatables')

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
						@if ($users == 0)
								@include('layouts.repeated.nodata', ['route' => 'user'])
						@else
								@can('create', 'App\Models\User')
										<a href="{{route('user.create')}}" class="btn btn-primary clear"><i class="la la-plus"></i> Create New {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}}</a>
								@endcan
								@can('update', 'App\Models\User')
										<button type="button" id="suspend-all-users" class="btn btn-warning clear ml-1"><i class="la la-ban"></i> توقيف جميع الحسابات</button>
										<button type="button" id="activate-all-users" class="btn btn-success clear ml-1"><i class="la la-check-circle"></i> تفعيل جميع الحسابات</button>
								@endcan
								<div class="card">
										<div class="card-content">
												<div class="card-body">
														<div class="table-responsive">
																<table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
																		<thead>
																				<tr>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th>Super Admin</th>
                                            <th>Last Activity</th>
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
@endsection

@include('layouts.scripts.datatables', ['route' => 'user', 'columns' => ['name', 'email', 'role', 'isSuperAdmin', 'last_active', 'status', 'action']])

@section('ajax')
<script>
    $(document).ready(function() {
        $(document).on('click', '.toggle-status', function(e){
            e.preventDefault();
            var userId = $(this).data('id');
            var url = "{{ route('user.toggleStatus', ':id') }}".replace(':id', userId);
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: url,
                method: 'POST',
                success: function(data) {
                    if (data.success) {
                        toastr.success(data.success, 'نجاح', {
                            positionClass: 'toast-bottom-left',
                            timeOut: 1500,
                            onHidden: function() {
                                if (window.currentDataTable) {
                                    window.currentDataTable.draw(false);
                                } else if (typeof table !== 'undefined') {
                                    table.draw(false);
                                }
                            }
                        });
                    }
                },
                error: function(xhr) {
                    var errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'حدث خطأ غير متوقع';
                    toastr.error(errorMsg, 'خطأ', { positionClass: 'toast-bottom-left' });
                }
            });
        });

        $(document).on('click', '#suspend-all-users', function(e){
            e.preventDefault();
            Swal.fire({
                title: 'هل أنت تأكد؟',
                text: "سيتم إيقاف جميع حسابات المستخدمين (باستثناء حساب الأدمن الرئيسي) وتسجيل خروجهم فوراً!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff9149',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، إيقاف الكل',
                cancelButtonText: 'إلغاء',
                confirmButtonClass: 'btn btn-warning',
                cancelButtonClass: 'btn btn-dark ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: "{{ route('user.bulkToggleStatus') }}",
                        method: 'POST',
                        data: { action: 'suspend_all' },
                        success: function(data) {
                            toastr.warning(data.success, 'تم التوقيف', {
                                positionClass: 'toast-bottom-left',
                                timeOut: 1500,
                                onHidden: function() {
                                    if (window.currentDataTable) {
                                        window.currentDataTable.draw(false);
                                    } else if (typeof table !== 'undefined') {
                                        table.draw(false);
                                    }
                                }
                            });
                        },
                        error: function(xhr) {
                            var errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'حدث خطأ أثناء التوقيف الجماعي';
                            toastr.error(errorMsg, 'خطأ', { positionClass: 'toast-bottom-left' });
                        }
                    });
                }
            });
        });

        $(document).on('click', '#activate-all-users', function(e){
            e.preventDefault();
            Swal.fire({
                title: 'هل أنت تأكد؟',
                text: "سيتم إعادة تفعيل جميع حسابات المستخدمين ويمكنهم التسجيل من جديد!",
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28d094',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، تفعيل الكل',
                cancelButtonText: 'إلغاء',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-dark ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: "{{ route('user.bulkToggleStatus') }}",
                        method: 'POST',
                        data: { action: 'activate_all' },
                        success: function(data) {
                            toastr.success(data.success, 'تم التفعيل', {
                                positionClass: 'toast-bottom-left',
                                timeOut: 1500,
                                onHidden: function() {
                                    if (window.currentDataTable) {
                                        window.currentDataTable.draw(false);
                                    } else if (typeof table !== 'undefined') {
                                        table.draw(false);
                                    }
                                }
                            });
                        },
                        error: function(xhr) {
                            var errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'حدث خطأ أثناء التفعيل الجماعي';
                            toastr.error(errorMsg, 'خطأ', { positionClass: 'toast-bottom-left' });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
