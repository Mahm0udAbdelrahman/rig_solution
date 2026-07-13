@extends('layouts.app')

@section('content')
<section class="users-list-wrapper">
		<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
						<h4 class="card-title mb-0">Payment {{ $payment->code }}</h4>
						<div>
								@can('update', $payment)
										<a href="{{ route('payment.edit', $payment->id) }}" class="btn btn-sm btn-info">Edit</a>
								@endcan
								@can('create', App\Models\WorkFlow\Accountant::class)
										@if($payment->jobRequest)
												<a
														href="{{ route('accountant.accountantWithJobRequest', ['jobRequest' => $payment->jobRequest->id, 'invoice_id' => $payment->invoice_id, 'payment_id' => $payment->id, 'entry_type' => 'payment', 'amount' => number_format((float)$payment->amount, 2, '.', ''), 'currency' => (optional($payment->invoice)->type ?: (optional($payment->bank)->currency ?: 'USD')), 'reference_no' => $payment->reference_no ?: $payment->code]) }}"
														class="btn btn-sm btn-secondary"
												>Create Accountant Entry</a>
										@endif
								@endcan
								<a href="{{ route('payment.index') }}" class="btn btn-sm btn-light border">Back</a>
						</div>
				</div>
				<div class="card-content">
						<div class="card-body">
								<div class="table-responsive">
										<table class="table table-bordered">
												<tbody>
												<tr>
														<th width="25%">Code</th>
														<td>{{ $payment->code }}</td>
												</tr>
												<tr>
														<th>JCF</th>
														<td>
																@if($payment->jobRequest)
																		<a href="{{ route('jobRequest.show', $payment->jobRequest->id) }}">{{ $payment->jobRequest->code }}</a>
																@else
																		-
																@endif
														</td>
												</tr>
												<tr>
														<th>Client/Supplier</th>
														<td>
																{{ optional(optional($payment->jobRequest)->client)->name ?? optional(optional($payment->jobRequest)->supplier)->name ?? '-' }}
														</td>
												</tr>
												<tr>
														<th>Invoice</th>
														<td>
																@if($payment->invoice)
																		<a href="{{ route('invoice.show', $payment->invoice->id) }}">{{ $payment->invoice->code }}</a>
																@else
																		-
																@endif
														</td>
												</tr>
												<tr>
														<th>Bank Account</th>
														<td>
																@if($payment->bank)
																		@php
																				$bankParts = array_filter([
																						$payment->bank->code,
																						$payment->bank->bank_name,
																						$payment->bank->account_name,
																				]);
																		@endphp
																		{{ implode(' - ', $bankParts) }}
																@else
																		-
																@endif
														</td>
												</tr>
												<tr>
														<th>Payment Date</th>
														<td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') : '-' }}</td>
												</tr>
												<tr>
														<th>Amount</th>
														<td>{{ number_format((float)$payment->amount, 2, '.', '') }} {{ optional($payment->bank)->currency ?: optional($payment->invoice)->type ?: 'USD' }}</td>
												</tr>
												<tr>
														<th>Method</th>
														<td>{{ $payment->method ?: '-' }}</td>
												</tr>
												<tr>
														<th>Reference No.</th>
														<td>{{ $payment->reference_no ?: '-' }}</td>
												</tr>
												<tr>
														<th>Status</th>
														<td>{{ $payment->status ?: '-' }}</td>
												</tr>
												<tr>
														<th>By</th>
														<td>{{ optional(optional($payment->user)->employee)->name ?: 'System' }}</td>
												</tr>
												<tr>
														<th>Note</th>
														<td>{!! $payment->note ?: '-' !!}</td>
												</tr>
												</tbody>
										</table>
								</div>
						</div>
				</div>
		</div>
</section>
@endsection
