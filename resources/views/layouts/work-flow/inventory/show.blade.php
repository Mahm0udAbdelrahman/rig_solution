@extends('layouts.app')

@section('content')
<section class="users-list-wrapper">
		<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
						<h4 class="card-title mb-0">Inventory {{ $inventory->code }}</h4>
						<div>
								@can('update', $inventory)
										<a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-sm btn-info">Edit</a>
								@endcan
								<a href="{{ route('inventory.index') }}" class="btn btn-sm btn-light border">Back</a>
						</div>
				</div>
				<div class="card-content">
						<div class="card-body">
								<div class="table-responsive">
										<table class="table table-bordered">
												<tbody>
												<tr>
														<th width="25%">Code</th>
														<td>{{ $inventory->code }}</td>
												</tr>
												<tr>
														<th>JCF</th>
														<td>
																@if($inventory->jobRequest)
																		<a href="{{ route('jobRequest.show', $inventory->jobRequest->id) }}">{{ $inventory->jobRequest->code }}</a>
																@else
																		-
																@endif
														</td>
												</tr>
												<tr>
														<th>Client/Supplier</th>
														<td>
																{{ optional(optional($inventory->jobRequest)->client)->name ?? optional(optional($inventory->jobRequest)->supplier)->name ?? '-' }}
														</td>
												</tr>
												<tr>
														<th>Item Name</th>
														<td>{{ $inventory->item_name ?: '-' }}</td>
												</tr>
												<tr>
														<th>SKU</th>
														<td>{{ $inventory->sku ?: '-' }}</td>
												</tr>
												<tr>
														<th>Quantity</th>
														<td>{{ number_format((float)$inventory->quantity, 2, '.', '') }}</td>
												</tr>
												<tr>
														<th>Unit</th>
														<td>{{ $inventory->unit ?: '-' }}</td>
												</tr>
												<tr>
														<th>Location</th>
														<td>{{ $inventory->location ?: '-' }}</td>
												</tr>
												<tr>
														<th>Min Quantity</th>
														<td>{{ number_format((float)$inventory->min_quantity, 2, '.', '') }}</td>
												</tr>
												<tr>
														<th>Status</th>
														<td>{{ $inventory->status ?: '-' }}</td>
												</tr>
												<tr>
														<th>By</th>
														<td>{{ optional(optional($inventory->user)->employee)->name ?: 'System' }}</td>
												</tr>
												<tr>
														<th>Note</th>
														<td>{!! $inventory->note ?: '-' !!}</td>
												</tr>
												</tbody>
										</table>
								</div>
						</div>
				</div>
		</div>
</section>
@endsection
