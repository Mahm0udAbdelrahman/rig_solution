@extends('layouts.app')

@include('layouts.styles.datatables')

@section('header-bottom')
@include('layouts.repeated.listing-datatable-styles')
<style>
		.listing-table-shell #users-list td:nth-child(7),
		.listing-table-shell #users-list td:nth-child(8) {
				white-space: nowrap;
		}
</style>
@endsection

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
						@if ($jobrequests == 0)
								@include('layouts.repeated.nodata', ['route' => 'jobRequest'])
						@else
								@can('create', 'App\Models\WorkFlow\JobRequest')
										<a href="{{route('jobRequest.create')}}" class="btn btn-primary clear"><i class="la la-plus"></i> Create New {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}}</a>
								@endcan
								<div class="card">
										<div class="card-content">
												<div class="card-body listing-table-shell">
														<div class="listing-table-toolbar is-sticky">
																<div class="listing-toolbar-presets">
																		<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-smart-preset is-active" data-preset="">All</button>
																		<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-smart-preset" data-preset="in_progress">In Progress</button>
																		<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-smart-preset" data-preset="ready">Ready</button>
																		<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-smart-preset" data-preset="completed">Completed without invoice</button>
																		<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-smart-preset" data-preset="canceled">Canceled</button>
																		<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-smart-preset" data-preset="has_invoice">With Invoice</button>
																		<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-smart-preset" data-preset="client_only">Clients</button>
																		<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-smart-preset" data-preset="supplier_only">Suppliers</button>
																</div>
																<div class="listing-toolbar-actions">
																		<button type="button" class="btn btn-sm btn-light border listing-toolbar-clear js-clear-jcf-filters js-clear-datatable-filters">Clear Filters</button>
																</div>
														</div>
														<div class="table-responsive">
																<table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
																		<thead class="jcf-list-head">
																				<tr class="column-headings">
																						<th width="10%">Code</th>
																						<th width="15%">Client/Supplier</th>
																						<th>Department</th>
																						<th>Location</th>
																						<th>By</th>
																						<th>Inspector</th>
																						<th width="16%">Actions</th>
																						<th width="18%">Status</th>
																						<th>Invoice</th>
																				</tr>
																				<tr class="filter-row">
																						<th></th>
																						<th></th>
																						<th></th>
																						<th></th>
																						<th></th>
																						<th></th>
																						<th></th>
																						<th></th>
																						<th></th>
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

@include('layouts.scripts.datatables', [
		'route' => 'jobRequest',
		'columns' => ['code', 'client', 'client_department','deploc', 'employee', 'inspector', 'action', 'other_action', 'invoice'],
		'datatable_options' => [
				'ordering' => true,
				'order' => [[0, 'desc']],
				'useFilterRow' => true,
				'fixedHeader' => ['header' => true, 'headerOffset' => 78],
				'filterDebounceMs' => 250,
		],
		'non_orderable_columns' => ['action'],
		'non_searchable_columns' => ['action'],
		'disable_column_filters' => ['action'],
])

@section('ajax')
<script>
		function resetJcfListFilters() {
				table.search('');
				table.columns().search('');
				$('#users-list thead tr.filter-row').find('input, select').val('');
		}

		$(document).on('click', '.js-smart-preset', function () {
				var $this = $(this);
				window.currentSmartPreset = $this.data('preset') || '';
				$('.js-smart-preset').removeClass('is-active');
				$this.addClass('is-active');

				resetJcfListFilters();
				table.draw();
		});

		$(document).on('click', '.js-clear-jcf-filters', function () {
				window.currentSmartPreset = '';
				$('.js-smart-preset').removeClass('is-active');
				$('.js-smart-preset[data-preset=""]').addClass('is-active');
		});

		table.on('click', '.convert', function(){
				id=$(this).data('id');
				type=$(this).data('type');
				Swal.fire({
						title: "Change Status",
						text: "This {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}} Ready For Invoicing ?",
						type: 'info',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Ready For Invoicing',
						confirmButtonClass: 'btn btn-success',
						cancelButtonClass: 'btn btn-danger ml-1',
						cancelButtonText: 'Back !',
						buttonsStyling: false,
				}).then(function (result) {
						if (result.value)
						{
								var url = "{{ route('jcfStatus.update', ':id') }}";
								url = url.replace(':id', id);
								$.ajax({
										headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
										type: 'POST',
										url: url,
										data: {
												type:type,
										},
										dataType: "JSON",
										beforeSend:function(){
												$('#submit i').addClass('la la-refresh spinner');
										},
										success: function (data){
												toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { table.draw(); }});
										},
								});
						}
						else if (result.dismiss === Swal.DismissReason.cancel)
						{
								Swal.fire({
										title: 'Nothing Happend !',
										text: "Your {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}} is safe :)",
										type: 'error',
										confirmButtonClass: 'btn btn-success',
								})
						}
				})
		});

		table.on('click', '.end_at', function(){
				id=$(this).data('id');
				type=$(this).data('type');
				Swal.fire({
						title: 'Update JCF status?',
						text: "Choose whether to cancel this JCF or mark it completed without invoice.",
						type: 'info',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Cancel JCF !',
						confirmButtonClass: 'btn btn-danger',
						cancelButtonClass: 'btn btn-success ml-1',
						cancelButtonText: 'Complete Without Invoice',
						buttonsStyling: false,
				}).then(function (result) {
						if (result.value)
						{
								var url = "{{ route('jcfStatus.update', ':id') }}";
								url = url.replace(':id', id);
								$.ajax({
										headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
										type: 'POST',
										url: url,
										data: {
												type:type,
										},
										dataType: "JSON",
										beforeSend:function(){
												$('#submit i').addClass('la la-refresh spinner');
										},
										success: function (data){
												toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { table.draw(); }});
										},
								});
						}
						else if (result.dismiss === Swal.DismissReason.cancel)
						{
								var url = "{{ route('jcfStatus.update', ':id') }}";
								url = url.replace(':id', id);
								$.ajax({
										headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
										type: 'POST',
										url: url,
										data: {
												type:"cm",
										},
										dataType: "JSON",
										beforeSend:function(){
												$('#submit i').addClass('la la-refresh spinner');
										},
										success: function (data){
												toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { table.draw(); }});
										},
								});
						}
						else if (result.dismiss === Swal.DismissReason.backdrop || result.dismiss === Swal.DismissReason.close || result.dismiss === Swal.DismissReason.esc)
						{
								return;
						}
				})
		});

</script>
@endsection
