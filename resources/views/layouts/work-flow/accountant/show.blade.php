@extends('layouts.app')

@section('content')
<section class="users-list-wrapper">
		@php
				$canApprove = auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('accountant', 'approve') || auth()->user()->hasPermission('accountant', 'all'));
		@endphp
		<div class="card">
				@if(session('success'))
						<div class="alert alert-success mb-0">{{ session('success') }}</div>
				@endif
				@if($errors->any())
						<div class="alert alert-danger mb-0">
								<ul class="mb-0">
										@foreach($errors->all() as $error)
												<li>{{ $error }}</li>
										@endforeach
								</ul>
						</div>
				@endif
				<div class="card-header d-flex justify-content-between align-items-center">
						<h4 class="card-title mb-0">Accountant Entry {{ $accountant->code }}</h4>
						<div>
								@if((int)$accountant->is_posted !== 1 && in_array((string)$accountant->status, ['draft', 'rejected'], true))
										@can('update', $accountant)
												<form method="POST" action="{{ route('accountant.submitForApproval', $accountant->id) }}" class="d-inline js-confirm-action" data-confirm-title="Submit For Approval" data-confirm-text="Submit this accountant entry for approval?" data-confirm-icon="question" data-confirm-confirm="Submit">
														@csrf
														<button type="submit" class="btn btn-sm btn-warning">Submit For Approval</button>
												</form>
										@endcan
								@endif
								@if((string)$accountant->status === 'pending_approval')
										@if($canApprove)
												<form method="POST" action="{{ route('accountant.approve', $accountant->id) }}" class="d-inline js-confirm-action" data-confirm-title="Approve Entry" data-confirm-text="Approve and post this accountant entry?" data-confirm-icon="question" data-confirm-confirm="Approve">
														@csrf
														<button type="submit" class="btn btn-sm btn-success">Approve</button>
												</form>
												<form method="POST" action="{{ route('accountant.reject', $accountant->id) }}" class="d-inline js-confirm-action" data-confirm-title="Reject Entry" data-confirm-text="Reject this accountant entry?" data-confirm-icon="warning" data-confirm-confirm="Reject" data-confirm-class="btn btn-danger">
														@csrf
														<button type="submit" class="btn btn-sm btn-danger">Reject</button>
												</form>
										@endif
								@endif
								@if((int)$accountant->is_posted === 1)
										@if($canApprove)
												<form method="POST" action="{{ route('accountant.unpost', $accountant->id) }}" class="d-inline js-confirm-action" data-confirm-title="Unpost Entry" data-confirm-text="Unpost this accountant entry and move it back to Draft?" data-confirm-icon="warning" data-confirm-confirm="Unpost" data-confirm-class="btn btn-outline-warning">
														@csrf
														<button type="submit" class="btn btn-sm btn-outline-warning">Unpost</button>
												</form>
										@endif
								@endif
								@can('update', $accountant)
										<a href="{{ route('accountant.edit', $accountant->id) }}" class="btn btn-sm btn-info">Edit</a>
								@endcan
								<a href="{{ route('accountant.index') }}" class="btn btn-sm btn-light border">Back</a>
						</div>
				</div>
				<div class="card-content">
						<div class="card-body">
								<div class="table-responsive">
										<table class="table table-bordered">
												<tbody>
												<tr>
														<th width="25%">Code</th>
														<td>{{ $accountant->code }}</td>
												</tr>
												<tr>
														<th>JCF</th>
														<td>
																@if($accountant->jobRequest)
																		<a href="{{ route('jobRequest.show', $accountant->jobRequest->id) }}">{{ $accountant->jobRequest->code }}</a>
																@else
																		-
																@endif
														</td>
												</tr>
												<tr>
														<th>Client/Supplier</th>
														<td>
																{{ optional(optional($accountant->jobRequest)->client)->name ?? optional(optional($accountant->jobRequest)->supplier)->name ?? '-' }}
														</td>
												</tr>
												<tr>
														<th>Invoice</th>
														<td>
																@if($accountant->invoice)
																		<a href="{{ route('invoice.show', $accountant->invoice->id) }}">{{ $accountant->invoice->code }}</a>
																@else
																		-
																@endif
														</td>
												</tr>
												<tr>
														<th>Payment</th>
														<td>
																@if($accountant->payment)
																		<a href="{{ route('payment.show', $accountant->payment->id) }}">{{ $accountant->payment->code }}</a>
																@else
																		-
																@endif
														</td>
												</tr>
												<tr>
														<th>Entry Type</th>
														<td>{{ $accountant->entry_type ?: '-' }}</td>
												</tr>
												<tr>
														<th>Posting Date</th>
														<td>{{ $accountant->posting_date ? \Carbon\Carbon::parse($accountant->posting_date)->format('d-m-Y') : '-' }}</td>
												</tr>
												<tr>
														<th>Reference No</th>
														<td>{{ $accountant->reference_no ?: '-' }}</td>
												</tr>
												<tr>
														<th>Debit Account</th>
														<td>
																@if($accountant->debitAccount)
																		{{ $accountant->debitAccount->code }} - {{ $accountant->debitAccount->display_name }}
																@else
																		-
																@endif
														</td>
												</tr>
												<tr>
														<th>Credit Account</th>
														<td>
																@if($accountant->creditAccount)
																		{{ $accountant->creditAccount->code }} - {{ $accountant->creditAccount->display_name }}
																@else
																		-
																@endif
														</td>
												</tr>
												<tr>
														<th>Amount</th>
														<td>{{ number_format((float)$accountant->amount, 2, '.', '') }} {{ $accountant->currency ?: 'USD' }}</td>
												</tr>
												<tr>
														<th>Journal Lines</th>
														<td>
																@if($accountant->lines->count() > 0)
																		<div class="table-responsive">
																				<table class="table table-sm table-bordered mb-0">
																						<thead>
																						<tr>
																								<th>#</th>
																								<th>Account</th>
																								<th>Type</th>
																								<th>Amount</th>
																								<th>Note</th>
																						</tr>
																						</thead>
																						<tbody>
																						@foreach($accountant->lines as $line)
																								<tr>
																										<td>{{ $line->line_order }}</td>
																										<td>{{ optional($line->chartAccount)->code ? (optional($line->chartAccount)->code.' - '.optional($line->chartAccount)->display_name) : '-' }}</td>
																										<td>{{ ucfirst((string)$line->line_type) }}</td>
																										<td>{{ number_format((float)$line->amount, 2, '.', '') }} {{ $line->currency ?: ($accountant->currency ?: 'USD') }}</td>
																										<td>{{ $line->note ?: '-' }}</td>
																								</tr>
																						@endforeach
																						</tbody>
																				</table>
																		</div>
																@else
																		-
																@endif
														</td>
												</tr>
												<tr>
														<th>Status</th>
														<td>{{ $accountant->status ?: '-' }}</td>
												</tr>
												<tr>
														<th>Posted</th>
														<td>{{ (int)$accountant->is_posted === 1 ? 'Yes' : 'No' }}</td>
												</tr>
												<tr>
														<th>By</th>
														<td>{{ optional(optional($accountant->user)->employee)->name ?: 'System' }}</td>
												</tr>
												<tr>
														<th>Approved By</th>
														<td>{{ optional(optional($accountant->approvedBy)->employee)->name ?: '-' }}</td>
												</tr>
												<tr>
														<th>Approved At</th>
														<td>{{ $accountant->approved_at ? \Carbon\Carbon::parse($accountant->approved_at)->format('d-m-Y H:i') : '-' }}</td>
												</tr>
												<tr>
														<th>Note</th>
														<td>{{ $accountant->note ?: '-' }}</td>
												</tr>
												</tbody>
										</table>
								</div>
								@if($accountant->audits->count() > 0)
										<hr>
										<h5 class="mb-1">Audit Trail</h5>
										<div class="table-responsive">
												<table class="table table-sm table-bordered">
														<thead>
														<tr>
																<th>#</th>
																<th>Action</th>
																<th>From</th>
																<th>To</th>
																<th>By</th>
																<th>Date</th>
														</tr>
														</thead>
														<tbody>
														@foreach($accountant->audits->sortByDesc('id')->values() as $index => $audit)
																<tr>
																		<td>{{ $index + 1 }}</td>
																		<td>{{ $audit->action }}</td>
																		<td>{{ $audit->from_status ?: '-' }}</td>
																		<td>{{ $audit->to_status ?: '-' }}</td>
																		<td>{{ optional(optional($audit->changedBy)->employee)->name ?: 'System' }}</td>
																		<td>{{ $audit->created_at ? \Carbon\Carbon::parse($audit->created_at)->format('d-m-Y H:i') : '-' }}</td>
																</tr>
														@endforeach
														</tbody>
												</table>
										</div>
								@endif
						</div>
				</div>
		</div>
</section>
@endsection
