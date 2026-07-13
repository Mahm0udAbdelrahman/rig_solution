@extends('layouts.app')

@include('layouts.styles.datatables')

@section('header-bottom')
@include('layouts.repeated.listing-datatable-styles')
@endsection

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
						@if(auth()->user()->can('viewAny', App\Models\WorkFlow\Bank::class) && (auth()->user()->hasPermission('financial', 'show') || auth()->user()->hasPermission('financial', 'all')))
								<a href="{{ route('bank.dashboard') }}" class="btn btn-secondary clear mb-1"><i class="la la-bar-chart"></i> Financial Dashboard</a>
						@endif
						@can('create', App\Models\WorkFlow\Bank::class)
								<a href="{{ route('bank.create') }}" class="btn btn-primary clear mb-1"><i class="la la-plus"></i> Create New Bank Account</a>
						@endcan
						@if ($banks == 0)
								@include('layouts.repeated.nodata', ['route' => 'bank'])
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
																						<th>Bank</th>
																						<th>Account Name</th>
																						<th>Account Number</th>
																						<th>Currency</th>
																						<th>Current Balance</th>
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
		'route' => 'bank',
		'columns' => ['code', 'bank_name', 'account_name', 'account_number', 'currency', 'current_balance', 'status', 'employee', 'action'],
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
