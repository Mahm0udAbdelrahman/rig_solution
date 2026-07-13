@extends('layouts.app')

@include('layouts.styles.datatables')

@section('header-bottom')
@include('layouts.repeated.listing-datatable-styles')
@endsection

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
						@can('create', 'App\Models\WorkFlow\Inventory')
								@include('layouts.repeated.workflow-jcf-create-picker', [
										'picker_id' => 'inventory-jcf-create',
										'button_label' => 'Create New Inventory Record',
										'route_template' => route('inventory.inventoryWithJobRequest', ['jobRequest' => '__ID__']),
										'modal_title' => 'Create Inventory Record From JCF',
										'empty_text' => 'No JCF is currently available to create inventory record.',
										'jcf_options' => $jcf_create_options ?? [],
								])
						@endcan
						@if ($inventories == 0)
								@include('layouts.repeated.nodata', ['route' => null])
						@else
								<div class="card">
										<div class="card-content">
												<div class="card-body listing-table-shell">
														<div class="listing-table-toolbar is-sticky">
																<div class="listing-toolbar-actions ml-auto">
																		<button type="button" class="btn btn-sm btn-light border listing-toolbar-clear js-clear-datatable-filters">Clear Filters</button>
																</div>
														</div>
														<div class="table-responsive">
																<table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
																		<thead class="workflow-list-head">
																				<tr class="column-headings">
																						<th>Code</th>
																						<th>JCF</th>
																						<th>Client/Supplier</th>
																						<th>Item</th>
																						<th>SKU</th>
																						<th>Qty</th>
																						<th>Unit</th>
																						<th>Location</th>
																						<th>Status</th>
																						<th>By</th>
																						<th>Actions</th>
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
		'route' => 'inventory',
		'route_param'=> ['job_request_id' => $selected_job_request_id],
		'columns' => ['code', 'job_request_code', 'client', 'item_name', 'sku', 'quantity', 'unit', 'location', 'status', 'employee', 'action'],
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
