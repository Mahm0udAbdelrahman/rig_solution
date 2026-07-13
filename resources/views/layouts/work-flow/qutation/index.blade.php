@extends('layouts.app')

@include('layouts.styles.datatables')

@section('header-bottom')
@include('layouts.repeated.listing-datatable-styles')
@endsection

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
						@can('create', 'App\Models\WorkFlow\Qutation')
								@include('layouts.repeated.workflow-jcf-create-picker', [
										'picker_id' => 'qutation-jcf-create',
										'button_label' => 'Create New Qutation',
										'route_template' => route('qutation.qutationWithJobRequest', ['jobRequest' => '__ID__']),
										'modal_title' => 'Create Qutation From JCF',
										'empty_text' => 'No JCF is currently available to create a Qutation.',
										'jcf_options' => $jcf_create_options ?? [],
								])
						@endcan
						@if ($qutations == 0)
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
																						<th>By</th>
																						<th>Actions</th>
																				</tr>
																				<tr class="filter-row">
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
		'route' => 'qutation',
		'columns' => ['code', 'job_request_code', 'client', 'employee', 'action'],
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
