@extends('layouts.app')

@section('header-bottom')
<style>
		.jcf-overview-hero {
				border: 1px solid #d9e2f5;
				box-shadow: 0 8px 22px rgba(43, 57, 108, 0.08);
		}
		.jcf-overview-hero .badge {
				font-size: 0.78rem;
				padding: 6px 10px;
		}
		.jcf-overview-hero-actions .btn {
				margin-right: 8px;
				margin-bottom: 8px;
		}
		.jcf-overview-stats .card {
				border: 1px solid #e2e8f8;
				box-shadow: none;
		}
		.jcf-overview-stats .stat-label {
				font-size: 0.82rem;
				color: #7b86aa;
				text-transform: uppercase;
				letter-spacing: 0.03em;
		}
		.jcf-overview-stats .stat-value {
				font-size: 1.5rem;
				font-weight: 700;
				color: #2f3b66;
		}
		.jcf-overview-section .card-header {
				background: #f7f9ff;
				border-bottom: 1px solid #e6edff;
		}
		.jcf-overview-section .card-title {
				margin: 0;
				font-size: 1rem;
				font-weight: 700;
		}
		.jcf-overview-section .table td,
		.jcf-overview-section .table th {
				vertical-align: middle;
				font-size: 0.92rem;
		}
		.jcf-overview-section .btn {
				margin-right: 6px;
				margin-bottom: 6px;
		}
		.jcf-overview-details .detail-label {
				font-size: 0.78rem;
				text-transform: uppercase;
				letter-spacing: 0.04em;
				color: #8694be;
				margin-bottom: 4px;
		}
		.jcf-overview-details .detail-value {
				font-size: 0.95rem;
				font-weight: 600;
				color: #2f3b66;
		}
		.jcf-overview-details .detail-box {
				border: 1px solid #e6edff;
				background: #fff;
				padding: 12px;
				border-radius: 10px;
				margin-bottom: 12px;
		}
		.jcf-pill {
				display: inline-block;
				padding: 5px 10px;
				border-radius: 999px;
				font-size: 0.78rem;
				border: 1px solid #d0d9f0;
				color: #4a5887;
				background: #f7f9ff;
				margin: 3px 4px 3px 0;
		}
		@media (max-width: 767.98px) {
				.jcf-overview-hero .d-flex {
						display: block !important;
				}
				.jcf-overview-hero-actions {
						margin-top: 12px;
				}
		}
</style>
@endsection

@section('content')
<section class="jcf-overview-wrapper">
		<div class="row">
				<div class="col-12">
						<div class="card jcf-overview-hero">
								<div class="card-body">
										<div class="d-flex justify-content-between align-items-start">
												<div>
														<div class="d-flex align-items-center flex-wrap">
																<h4 class="mb-0 mr-2">JCF {{ $jobRequest->code }}</h4>
																<span class="badge badge-{{ $status_meta['class'] ?? 'secondary' }}">{{ $status_meta['label'] ?? 'Unknown' }}</span>
														</div>
														<p class="mb-0 text-muted mt-1">
																{{ $owner_type }}: <strong>{{ $owner_name }}</strong>
																@if($jobRequest->clientDepartment)
																		| Department: <strong>{{ $jobRequest->clientDepartment->name }}</strong>
																@endif
																@if($jobRequest->deploc)
																		| Location: <strong>{{ $jobRequest->deploc }}</strong>
																@endif
														</p>
												</div>
												<div class="jcf-overview-hero-actions text-md-right">
														<a href="{{ route('jobRequest.index') }}" class="btn btn-sm btn-light border">Back To JCF List</a>
														@can('update', $jobRequest)
																<a href="{{ route('jobRequest.edit', $jobRequest->id) }}" class="btn btn-sm btn-info">Edit JCF</a>
														@endcan
														@if(Storage::disk('public')->exists('pdf/workflow/jcf/' . $jobRequest->code . '.pdf'))
																<a target="_blank" href="{{ URL('storage/pdf/workflow/jcf/' . $jobRequest->code . '.pdf') }}" class="btn btn-sm btn-primary">Open JCF PDF</a>
														@else
																<a href="{{ route('jobRequest.show', $jobRequest->id) }}" class="btn btn-sm btn-primary">Generate JCF PDF</a>
														@endif
												</div>
										</div>
								</div>
						</div>
				</div>
		</div>

		<div class="row jcf-overview-stats">
				@if($can_view_qutation)
				<div class="col-xl-2 col-lg-3 col-sm-6">
						<div class="card">
								<div class="card-body">
										<div class="stat-label">Quotations</div>
										<div class="stat-value">{{ $qutations->count() }}</div>
										<div class="small text-muted">{{ $qutation_items_count }} line items</div>
								</div>
						</div>
				</div>
				@endif
				@if($can_view_packing_slip)
				<div class="col-xl-2 col-lg-3 col-sm-6">
						<div class="card">
								<div class="card-body">
										<div class="stat-label">Packing Slips</div>
										<div class="stat-value">{{ $packing_slips->count() }}</div>
										<div class="small text-muted">{{ $packing_items_count }} line items</div>
								</div>
						</div>
				</div>
				@endif
				@if($can_view_service_ticket)
				<div class="col-xl-2 col-lg-3 col-sm-6">
						<div class="card">
								<div class="card-body">
										<div class="stat-label">Service Tickets</div>
										<div class="stat-value">{{ $service_tickets->count() }}</div>
										<div class="small text-muted">{{ $service_items_count }} service lines</div>
								</div>
						</div>
				</div>
				@endif
				@if($can_view_invoice)
				<div class="col-xl-2 col-lg-3 col-sm-6">
						<div class="card">
								<div class="card-body">
										<div class="stat-label">Invoices</div>
										<div class="stat-value">{{ $invoices_total->count() }}</div>
										<div class="small text-muted">RSE {{ $invoices_rse->count() }} / LTD {{ $invoices_ltd->count() }}</div>
								</div>
						</div>
				</div>
				@endif
				@if($can_view_payment)
				<div class="col-xl-2 col-lg-3 col-sm-6">
						<div class="card">
								<div class="card-body">
										<div class="stat-label">Payments</div>
										<div class="stat-value">{{ $payments->count() }}</div>
										<div class="small text-muted">{{ number_format((float)$payment_total_amount, 2, '.', '') }} USD</div>
								</div>
						</div>
				</div>
				@endif
				@if($can_view_inventory)
				<div class="col-xl-2 col-lg-3 col-sm-6">
						<div class="card">
								<div class="card-body">
										<div class="stat-label">Inventory</div>
										<div class="stat-value">{{ $inventories->count() }}</div>
										<div class="small text-muted">{{ $inventory_low_stock_count }} low stock</div>
								</div>
						</div>
				</div>
				@endif
				@if($can_view_accountant)
				<div class="col-xl-2 col-lg-3 col-sm-6">
						<div class="card">
								<div class="card-body">
										<div class="stat-label">Accountant</div>
										<div class="stat-value">{{ $accountants->count() }}</div>
										<div class="small text-muted">{{ $accountant_pending_count }} pending approval</div>
								</div>
						</div>
				</div>
				@endif
				<div class="col-xl-2 col-lg-3 col-sm-6">
						<div class="card">
								<div class="card-body">
										<div class="stat-label">Inspection Reports</div>
										<div class="stat-value">{{ $inspection_rows->count() }}</div>
										<div class="small text-muted">All linked inspections for this JCF</div>
								</div>
						</div>
				</div>
		</div>

		<div class="row">
				<div class="col-xl-8 col-12">
						<div class="jcf-overview-section">
								@if($can_view_qutation)
										<div class="card">
												<div class="card-header d-flex justify-content-between align-items-center">
														<h4 class="card-title">Quotations</h4>
														<div>
																@if($can_create_qutation && $qutations->isEmpty())
																		<a href="{{ route('qutation.qutationWithJobRequest', $jobRequest->id) }}" class="btn btn-sm btn-primary">Create Quotation</a>
																@endif
														</div>
												</div>
												<div class="card-body">
														@if($qutations->isEmpty())
																<p class="mb-0 text-muted">No quotation linked to this JCF yet.</p>
														@else
																<div class="table-responsive">
																		<table class="table table-bordered table-striped">
																				<thead>
																				<tr>
																						<th>Code</th>
																						<th>Creation Date</th>
																						<th>Delivery Promise</th>
																						<th>Location</th>
																						<th>By</th>
																						<th>Actions</th>
																				</tr>
																				</thead>
																				<tbody>
																				@foreach($qutations as $qutation)
																						<tr>
																								<td>{{ $qutation->code }}</td>
																								<td>{{ $qutation->creation_date ?: ($qutation->created_at ? $qutation->created_at->format('d-m-Y') : '-') }}</td>
																								<td>{{ $qutation->delivery ? \Carbon\Carbon::parse($qutation->delivery)->format('d-m-Y') : '-' }}</td>
																								<td>{{ $qutation->location ?: '-' }}</td>
																								<td>{{ optional(optional($qutation->user)->employee)->name ?: 'System' }}</td>
																								<td class="text-nowrap">
																										@can('view', $qutation)
																												<a href="{{ route('qutation.show', $qutation->id) }}" class="btn btn-sm btn-outline-primary">View</a>
																										@endcan
																										@can('update', $qutation)
																												<a href="{{ route('qutation.qutationWithJobRequestEdit', $jobRequest->id) }}" class="btn btn-sm btn-outline-info">Edit</a>
																										@endcan
																										@if(Storage::disk('public')->exists('pdf/workflow/quotation/' . $qutation->code . '.pdf'))
																												<a target="_blank" href="{{ URL('storage/pdf/workflow/quotation/' . $qutation->code . '.pdf') }}" class="btn btn-sm btn-outline-secondary">PDF</a>
																										@endif
																								</td>
																						</tr>
																				@endforeach
																				</tbody>
																		</table>
																</div>
														@endif
												</div>
										</div>
								@endif

								@if($can_view_packing_slip)
										<div class="card">
												<div class="card-header d-flex justify-content-between align-items-center">
														<h4 class="card-title">Packing Slips</h4>
														<div>
																@if($can_create_packing_slip && $packing_slips->isEmpty())
																		<a href="{{ route('packingSlip.packingSlipWithJobRequest', $jobRequest->id) }}" class="btn btn-sm btn-primary">Create Packing Slip</a>
																@endif
														</div>
												</div>
												<div class="card-body">
														@if($packing_slips->isEmpty())
																<p class="mb-0 text-muted">No packing slip linked to this JCF yet.</p>
														@else
																<div class="table-responsive">
																		<table class="table table-bordered table-striped">
																				<thead>
																				<tr>
																						<th>Code</th>
																						<th>Order Date</th>
																						<th>Client P.O</th>
																						<th>Shipping Method</th>
																						<th>By</th>
																						<th>Actions</th>
																				</tr>
																				</thead>
																				<tbody>
																				@foreach($packing_slips as $packingSlip)
																						<tr>
																								<td>{{ $packingSlip->code }}</td>
																								<td>{{ $packingSlip->orderdate ? \Carbon\Carbon::parse($packingSlip->orderdate)->format('d-m-Y') : '-' }}</td>
																								<td>{{ $packingSlip->po ?: '-' }}</td>
																								<td>{{ $packingSlip->shppingmethods ?: '-' }}</td>
																								<td>{{ optional(optional($packingSlip->user)->employee)->name ?: 'System' }}</td>
																								<td class="text-nowrap">
																										@can('view', $packingSlip)
																												<a href="{{ route('packingSlip.show', $packingSlip->id) }}" class="btn btn-sm btn-outline-primary">View</a>
																										@endcan
																										@can('update', $packingSlip)
																												<a href="{{ route('packingSlip.packingSlipWithJobRequestEdit', $jobRequest->id) }}" class="btn btn-sm btn-outline-info">Edit</a>
																										@endcan
																										@if(Storage::disk('public')->exists('pdf/workflow/packingslip/' . $packingSlip->code . '.pdf'))
																												<a target="_blank" href="{{ URL('storage/pdf/workflow/packingslip/' . $packingSlip->code . '.pdf') }}" class="btn btn-sm btn-outline-secondary">PDF</a>
																										@endif
																								</td>
																						</tr>
																				@endforeach
																				</tbody>
																		</table>
																</div>
														@endif
												</div>
										</div>
								@endif

								@if($can_view_service_ticket)
										<div class="card">
												<div class="card-header d-flex justify-content-between align-items-center">
														<h4 class="card-title">Service Tickets</h4>
														<div>
																@if($can_create_service_ticket && $service_tickets->isEmpty())
																		<a href="{{ route('serviceTicket.serviceTicketWithJobRequest', $jobRequest->id) }}" class="btn btn-sm btn-primary">Create Service Ticket</a>
																@endif
														</div>
												</div>
												<div class="card-body">
														@if($service_tickets->isEmpty())
																<p class="mb-0 text-muted">No service ticket linked to this JCF yet.</p>
														@else
																<div class="table-responsive">
																		<table class="table table-bordered table-striped">
																				<thead>
																				<tr>
																						<th>Code</th>
																						<th>Location</th>
																						<th>Start</th>
																						<th>End</th>
																						<th>By</th>
																						<th>Actions</th>
																				</tr>
																				</thead>
																				<tbody>
																				@foreach($service_tickets as $serviceTicket)
																						<tr>
																								<td>{{ $serviceTicket->code }}</td>
																								<td>{{ $serviceTicket->location ?: '-' }}</td>
																								<td>{{ $serviceTicket->start ? \Carbon\Carbon::parse($serviceTicket->start)->format('d-m-Y') : '-' }}</td>
																								<td>{{ $serviceTicket->end ? \Carbon\Carbon::parse($serviceTicket->end)->format('d-m-Y') : '-' }}</td>
																								<td>{{ optional(optional($serviceTicket->user)->employee)->name ?: 'System' }}</td>
																								<td class="text-nowrap">
																										@can('view', $serviceTicket)
																												<a href="{{ route('serviceTicket.show', $serviceTicket->id) }}" class="btn btn-sm btn-outline-primary">View</a>
																										@endcan
																										@can('update', $serviceTicket)
																												<a href="{{ route('serviceTicket.serviceTicketWithJobRequestEdit', $jobRequest->id) }}" class="btn btn-sm btn-outline-info">Edit</a>
																										@endcan
																										@if(Storage::disk('public')->exists('pdf/workflow/serviceticket/' . $serviceTicket->code . '.pdf'))
																												<a target="_blank" href="{{ URL('storage/pdf/workflow/serviceticket/' . $serviceTicket->code . '.pdf') }}" class="btn btn-sm btn-outline-secondary">PDF</a>
																										@endif
																								</td>
																						</tr>
																				@endforeach
																				</tbody>
																		</table>
																</div>
														@endif
												</div>
										</div>
								@endif

								@if($can_view_invoice)
										<div class="card">
												<div class="card-header d-flex justify-content-between align-items-center">
														<h4 class="card-title">Invoices (RSE / LTD)</h4>
														<div>
																@if($can_create_invoice && $invoices_rse->isEmpty())
																		<a href="{{ route('invoice.invoiceWithJobRequestNew', ['jobRequest' => $jobRequest->id, 'invoice_company_type' => \App\Models\WorkFlow\Invoice::$INVOICE_RSE_TYPE]) }}" class="btn btn-sm btn-primary">Create RSE Invoice</a>
																@endif
																@if($can_create_invoice && $invoices_ltd->isEmpty())
																		<a href="{{ route('invoice.invoiceWithJobRequestNew', ['jobRequest' => $jobRequest->id, 'invoice_company_type' => \App\Models\WorkFlow\Invoice::$INVOICE_LTD_TYPE]) }}" class="btn btn-sm btn-dark">Create LTD Invoice</a>
																@endif
														</div>
												</div>
												<div class="card-body">
														@if($invoices_total->isEmpty())
																<p class="mb-0 text-muted">No invoice linked to this JCF yet.</p>
														@else
																<div class="table-responsive">
																		<table class="table table-bordered table-striped">
																				<thead>
																				<tr>
																						<th>Code</th>
																						<th>Type</th>
																						<th>C.P.O</th>
																						<th>Total</th>
																						<th>By</th>
																						<th>Actions</th>
																				</tr>
																				</thead>
																				<tbody>
																				@foreach($invoices_total as $invoice)
																						<tr>
																								<td>{{ $invoice->code }}</td>
																								<td><span class="badge badge-{{ $invoice->invoice_company_type === 'LTD' ? 'dark' : 'primary' }}">{{ $invoice->invoice_company_type }}</span></td>
																								<td>{{ $invoice->cpo ?: '-' }}</td>
																								<td>{{ number_format((float)($invoice->total ?: 0), 2) }} USD</td>
																								<td>{{ optional(optional($invoice->user)->employee)->name ?: 'System' }}</td>
																								<td class="text-nowrap">
																										@can('view', $invoice)
																												<a href="{{ route('invoice.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary">View</a>
																										@endcan
																										@if(Storage::disk('public')->exists('pdf/workflow/invoice/' . $invoice->invoice_company_type . '/' . $invoice->code . '.pdf'))
																												<a target="_blank" href="{{ URL('storage/pdf/workflow/invoice/' . $invoice->invoice_company_type . '/' . $invoice->code . '.pdf') }}" class="btn btn-sm btn-outline-secondary">PDF</a>
																										@endif
																								</td>
																						</tr>
																				@endforeach
																				</tbody>
																		</table>
																</div>
														@endif
												</div>
										</div>
								@endif

								@if($can_view_payment)
										<div class="card">
												<div class="card-header d-flex justify-content-between align-items-center">
														<h4 class="card-title">Payments</h4>
														<div>
																@if($can_create_payment)
																		<a href="{{ route('payment.paymentWithJobRequest', $jobRequest->id) }}" class="btn btn-sm btn-primary">Create Payment</a>
																@endif
														</div>
												</div>
												<div class="card-body">
														@if($payments->isEmpty())
																<p class="mb-0 text-muted">No payment linked to this JCF yet.</p>
														@else
																<div class="mb-2">
																		<span class="jcf-pill">Total Paid/Planned: {{ number_format((float)$payment_total_amount, 2, '.', '') }} USD</span>
																</div>
																<div class="table-responsive">
																		<table class="table table-bordered table-striped">
																				<thead>
																				<tr>
																						<th>Code</th>
																						<th>Date</th>
																						<th>Amount</th>
																						<th>Method</th>
																						<th>Invoice</th>
																						<th>By</th>
																						<th>Actions</th>
																				</tr>
																				</thead>
																				<tbody>
																				@foreach($payments as $payment)
																						<tr>
																								<td>{{ $payment->code }}</td>
																								<td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') : '-' }}</td>
																								<td>{{ number_format((float)($payment->amount ?: 0), 2, '.', '') }} USD</td>
																								<td>{{ $payment->method ?: '-' }}</td>
																								<td>
																										@if($payment->invoice)
																												@can('view', $payment->invoice)
																														<a href="{{ route('invoice.show', $payment->invoice->id) }}">{{ $payment->invoice->code }}</a>
																												@else
																														{{ $payment->invoice->code }}
																												@endcan
																										@else
																												-
																										@endif
																								</td>
																								<td>{{ optional(optional($payment->user)->employee)->name ?: 'System' }}</td>
																								<td class="text-nowrap">
																										@can('view', $payment)
																												<a href="{{ route('payment.show', $payment->id) }}" class="btn btn-sm btn-outline-primary">View</a>
																										@endcan
																										@can('update', $payment)
																												<a href="{{ route('payment.edit', $payment->id) }}" class="btn btn-sm btn-outline-info">Edit</a>
																										@endcan
																								</td>
																						</tr>
																				@endforeach
																				</tbody>
																		</table>
																</div>
														@endif
												</div>
										</div>
								@endif

								@if($can_view_inventory)
										<div class="card">
												<div class="card-header d-flex justify-content-between align-items-center">
														<h4 class="card-title">Inventory</h4>
														<div>
																@if($can_create_inventory)
																		<a href="{{ route('inventory.inventoryWithJobRequest', $jobRequest->id) }}" class="btn btn-sm btn-primary">Create Inventory Entry</a>
																@endif
														</div>
												</div>
												<div class="card-body">
														@if($inventories->isEmpty())
																<p class="mb-0 text-muted">No inventory record linked to this JCF yet.</p>
														@else
																@if($inventory_low_stock_count > 0)
																		<div class="mb-2">
																				<span class="jcf-pill">Low Stock: {{ $inventory_low_stock_count }}</span>
																		</div>
																@endif
																<div class="table-responsive">
																		<table class="table table-bordered table-striped">
																				<thead>
																				<tr>
																						<th>Code</th>
																						<th>Item</th>
																						<th>SKU</th>
																						<th>Qty</th>
																						<th>Min Qty</th>
																						<th>Location</th>
																						<th>Status</th>
																						<th>By</th>
																						<th>Actions</th>
																				</tr>
																				</thead>
																				<tbody>
																				@foreach($inventories as $inventory)
																						<tr>
																								<td>{{ $inventory->code }}</td>
																								<td>{{ $inventory->item_name ?: '-' }}</td>
																								<td>{{ $inventory->sku ?: '-' }}</td>
																								<td>{{ number_format((float)($inventory->quantity ?: 0), 2, '.', '') }} {{ $inventory->unit ?: '' }}</td>
																								<td>{{ number_format((float)($inventory->min_quantity ?: 0), 2, '.', '') }}</td>
																								<td>{{ $inventory->location ?: '-' }}</td>
																								<td>{{ $inventory->status ?: '-' }}</td>
																								<td>{{ optional(optional($inventory->user)->employee)->name ?: 'System' }}</td>
																								<td class="text-nowrap">
																										@can('view', $inventory)
																												<a href="{{ route('inventory.show', $inventory->id) }}" class="btn btn-sm btn-outline-primary">View</a>
																										@endcan
																										@can('update', $inventory)
																												<a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-sm btn-outline-info">Edit</a>
																										@endcan
																								</td>
																						</tr>
																				@endforeach
																				</tbody>
																		</table>
																</div>
														@endif
												</div>
										</div>
								@endif

								@if($can_view_accountant)
										<div class="card">
												<div class="card-header d-flex justify-content-between align-items-center">
														<h4 class="card-title">Accountant Entries</h4>
														<div>
																@if($can_create_accountant)
																		<a href="{{ route('accountant.accountantWithJobRequest', $jobRequest->id) }}" class="btn btn-sm btn-primary">Create Accountant Entry</a>
																@endif
														</div>
												</div>
												<div class="card-body">
														@if($accountants->isEmpty())
																<p class="mb-0 text-muted">No accountant entry linked to this JCF yet.</p>
														@else
																<div class="table-responsive">
																		<table class="table table-bordered table-striped">
																				<thead>
																				<tr>
																						<th>Code</th>
																						<th>Type</th>
																						<th>Amount</th>
																						<th>Status</th>
																						<th>Invoice</th>
																						<th>Payment</th>
																						<th>By</th>
																						<th>Actions</th>
																				</tr>
																				</thead>
																				<tbody>
																				@foreach($accountants as $accountant)
																						<tr>
																								<td>{{ $accountant->code }}</td>
																								<td>{{ $accountant->entry_type ?: '-' }}</td>
																								<td>{{ number_format((float)($accountant->amount ?: 0), 2, '.', '') }} {{ $accountant->currency ?: 'USD' }}</td>
																								<td>{{ $accountant->status ?: '-' }}</td>
																								<td>
																										@if($accountant->invoice)
																												@can('view', $accountant->invoice)
																														<a href="{{ route('invoice.show', $accountant->invoice->id) }}">{{ $accountant->invoice->code }}</a>
																												@else
																														{{ $accountant->invoice->code }}
																												@endcan
																										@else
																												-
																										@endif
																								</td>
																								<td>
																										@if($accountant->payment)
																												@can('view', $accountant->payment)
																														<a href="{{ route('payment.show', $accountant->payment->id) }}">{{ $accountant->payment->code }}</a>
																												@else
																														{{ $accountant->payment->code }}
																												@endcan
																										@else
																												-
																										@endif
																								</td>
																								<td>{{ optional(optional($accountant->user)->employee)->name ?: 'System' }}</td>
																								<td class="text-nowrap">
																										@can('view', $accountant)
																												<a href="{{ route('accountant.show', $accountant->id) }}" class="btn btn-sm btn-outline-primary">View</a>
																										@endcan
																										@can('update', $accountant)
																												<a href="{{ route('accountant.edit', $accountant->id) }}" class="btn btn-sm btn-outline-info">Edit</a>
																										@endcan
																								</td>
																						</tr>
																				@endforeach
																				</tbody>
																		</table>
																</div>
														@endif
												</div>
										</div>
								@endif

								<div class="card">
										<div class="card-header d-flex justify-content-between align-items-center">
												<h4 class="card-title">Inspections Linked To This JCF</h4>
												<span class="badge badge-light">{{ $inspection_rows->count() }} Reports</span>
										</div>
										<div class="card-body">
												@if($inspection_type_summary->isNotEmpty())
														<div class="mb-2">
																@foreach($inspection_type_summary as $typeLabel => $count)
																		<span class="jcf-pill">{{ $typeLabel }}: {{ $count }}</span>
																@endforeach
														</div>
												@endif

												@if($inspection_rows->isEmpty())
														<p class="mb-0 text-muted">No inspection reports are visible for this JCF.</p>
												@else
														<div class="table-responsive">
																<table class="table table-bordered table-striped">
																		<thead>
																		<tr>
																				<th>Type</th>
																				<th>Report No.</th>
																				<th>Status</th>
																				<th>Updated At</th>
																				<th>Actions</th>
																		</tr>
																		</thead>
																		<tbody>
																		@foreach($inspection_rows as $inspectionRow)
																				<tr>
																						<td>{{ $inspectionRow['type_label'] }}</td>
																						<td>{{ $inspectionRow['display_code'] }}</td>
																						<td><span class="badge badge-{{ $inspectionRow['status_class'] }}">{{ $inspectionRow['status'] }}</span></td>
																						<td>{{ $inspectionRow['updated_at'] ?: '-' }}</td>
																						<td class="text-nowrap">
																								@if(!empty($inspectionRow['open_url']))
																										<a href="{{ $inspectionRow['open_url'] }}" class="btn btn-sm btn-outline-primary">Open</a>
																								@endif
																								@if(!empty($inspectionRow['pdf_url']))
																										<a target="_blank" href="{{ $inspectionRow['pdf_url'] }}" class="btn btn-sm btn-outline-secondary">PDF</a>
																								@endif
																						</td>
																				</tr>
																		@endforeach
																		</tbody>
																</table>
														</div>
												@endif
										</div>
								</div>
						</div>
				</div>

				<div class="col-xl-4 col-12">
						<div class="jcf-overview-details">
								<div class="card">
										<div class="card-header">
												<h4 class="card-title">JCF Details</h4>
										</div>
										<div class="card-body">
												<div class="detail-box">
														<div class="detail-label">Subject</div>
														<div class="detail-value">{{ $jobRequest->subject ?: '-' }}</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Purchase Order</div>
														<div class="detail-value">{{ $jobRequest->purchase_order ?: '-' }}</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Contact Person</div>
														<div class="detail-value">{{ optional($jobRequest->contactPeopleShow)->name ?: '-' }}</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Contact Date</div>
														<div class="detail-value">{{ $contact_date ?: '-' }}</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Planned Start / End</div>
														<div class="detail-value">{{ $status_start_at ?: '-' }} / {{ $status_end_at ?: '-' }}</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Created By</div>
														<div class="detail-value">{{ $created_by_name }}</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Created / Updated</div>
														<div class="detail-value">{{ $created_at_formatted }} / {{ $updated_at_formatted }}</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Contact Way</div>
														<div>
																@if(count($contact_way_list))
																		@foreach($contact_way_list as $item)
																				<span class="jcf-pill">{{ $item }}</span>
																		@endforeach
																@else
																		<span class="text-muted">-</span>
																@endif
														</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Work Location</div>
														<div>
																@if(count($work_location_list))
																		@foreach($work_location_list as $item)
																				<span class="jcf-pill">{{ $item }}</span>
																		@endforeach
																@else
																		<span class="text-muted">-</span>
																@endif
														</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Departments</div>
														<div>
																@if($department_names->isNotEmpty())
																		@foreach($department_names as $name)
																				<span class="jcf-pill">{{ $name }}</span>
																		@endforeach
																@else
																		<span class="text-muted">-</span>
																@endif
														</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Inspectors</div>
														<div>
																@if($inspector_names->isNotEmpty())
																		@foreach($inspector_names as $name)
																				<span class="jcf-pill">{{ $name }}</span>
																		@endforeach
																@else
																		<span class="text-muted">-</span>
																@endif
														</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Managers</div>
														<div>
																@if(count($manager_list))
																		@foreach($manager_list as $item)
																				<span class="jcf-pill">{{ $item }}</span>
																		@endforeach
																@else
																		<span class="text-muted">-</span>
																@endif
														</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Tools</div>
														<div>
																@if(count($tool_list))
																		@foreach($tool_list as $item)
																				<span class="jcf-pill">{{ $item }}</span>
																		@endforeach
																@else
																		<span class="text-muted">-</span>
																@endif
														</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Specifications</div>
														<div>
																@if(count($specification_list))
																		@foreach($specification_list as $item)
																				<span class="jcf-pill">{{ $item }}</span>
																		@endforeach
																@else
																		<span class="text-muted">-</span>
																@endif
														</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Job Required Details</div>
														<div class="detail-value">{!! $jobRequest->job_requierd_details ?: '-' !!}</div>
												</div>
												<div class="detail-box">
														<div class="detail-label">Scope Of Work</div>
														<div class="detail-value">{!! $jobRequest->scope_of_work ?: '-' !!}</div>
												</div>
										</div>
								</div>
						</div>
				</div>
		</div>
</section>
@endsection
