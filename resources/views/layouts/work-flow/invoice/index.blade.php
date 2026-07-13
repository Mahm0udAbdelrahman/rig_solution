@extends('layouts.app')

@include('layouts.styles.datatables')

@section('header-bottom')
@include('layouts.repeated.listing-datatable-styles')
@endsection

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
						@php
								$invoiceTypeLabel = strtoupper($invoice_company_type ?? \App\Models\WorkFlow\Invoice::$INVOICE_RSE_TYPE);
						@endphp
						@can('create', 'App\Models\WorkFlow\Invoice')
								@include('layouts.repeated.workflow-jcf-create-picker', [
										'picker_id' => 'invoice-jcf-create-'.$invoiceTypeLabel,
										'button_label' => 'Create New '.$invoiceTypeLabel.' Invoice',
										'route_template' => route('invoice.invoiceWithJobRequestNew', ['jobRequest' => '__ID__', 'invoice_company_type' => $invoice_company_type]),
										'modal_title' => 'Create '.$invoiceTypeLabel.' Invoice From JCF',
										'empty_text' => 'No JCF is currently available to create a '.$invoiceTypeLabel.' Invoice.',
										'jcf_options' => $jcf_create_options ?? [],
								])
						@endcan
						@if ($invoices == 0)
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
																					<th>P.O</th>
																					<th>Client Department</th>
																					<th>Location</th>
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
		'route' => 'invoice',
		'route_param'=> ['invoice_company_type' => $invoice_company_type],
		'columns' => ['code', 'job_request_code', 'client', 'cpo', 'client_department', 'deploc', 'employee', 'action'],
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
