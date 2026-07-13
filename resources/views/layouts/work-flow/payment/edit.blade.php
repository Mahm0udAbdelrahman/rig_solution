@extends('layouts.app')

@section('content')
<section class="users-list-wrapper">
		<div class="card">
				<div class="card-content">
						<div class="card-body">
								<form method="POST" action="{{ route('payment.update', $payment->id) }}">
										@csrf
										@method('PATCH')

										<div class="row">
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>JCF Number</label>
																<select class="form-control" name="job_request_id" required>
																		<option value="">Select JCF</option>
																		@foreach(($job_request_options ?? []) as $jobRequestId => $jobRequestLabel)
																				<option value="{{ $jobRequestId }}" {{ (string)old('job_request_id', $payment->job_request_id) === (string)$jobRequestId ? 'selected' : '' }}>
																						{{ $jobRequestLabel }}
																				</option>
																		@endforeach
																</select>
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Invoice (Optional)</label>
																<select class="form-control" name="invoice_id">
																		<option value="">No invoice</option>
																		@foreach(($invoice_options ?? []) as $invoiceId => $invoiceLabel)
																				<option value="{{ $invoiceId }}" {{ (string)old('invoice_id', $payment->invoice_id) === (string)$invoiceId ? 'selected' : '' }}>
																						{{ $invoiceLabel }}
																				</option>
																		@endforeach
																</select>
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Bank Account (Optional)</label>
																<select class="form-control" name="bank_account_id">
																		<option value="">No bank link</option>
																		@foreach(($bank_options ?? []) as $bankId => $bankLabel)
																				<option value="{{ $bankId }}" {{ (string)old('bank_account_id', $payment->bank_account_id) === (string)$bankId ? 'selected' : '' }}>
																						{{ $bankLabel }}
																				</option>
																		@endforeach
																</select>
																<small class="text-muted">Changing bank/amount on posted transaction is blocked.</small>
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Payment Date</label>
																<input
																		type="date"
																		name="payment_date"
																		class="form-control"
																		value="{{ old('payment_date', $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : '') }}"
																>
														</div>
												</div>
										</div>

										<div class="row">
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Amount</label>
																<input type="text" name="amount" class="form-control" value="{{ old('amount', number_format((float)$payment->amount, 2, '.', '')) }}" placeholder="0.00">
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Method</label>
																<input type="text" name="method" class="form-control" value="{{ old('method', $payment->method) }}" placeholder="Bank Transfer / Cash">
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Reference No.</label>
																<input type="text" name="reference_no" class="form-control" value="{{ old('reference_no', $payment->reference_no) }}" placeholder="TXN / Voucher">
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Status</label>
																<select class="form-control" name="status">
																		@php($statusValue = old('status', $payment->status))
																		<option value="pending" {{ $statusValue === 'pending' ? 'selected' : '' }}>Pending</option>
																		<option value="received" {{ $statusValue === 'received' ? 'selected' : '' }}>Received</option>
																		<option value="approved" {{ $statusValue === 'approved' ? 'selected' : '' }}>Approved</option>
																		<option value="rejected" {{ $statusValue === 'rejected' ? 'selected' : '' }}>Rejected</option>
																</select>
														</div>
												</div>
										</div>

										<div class="row">
												<div class="col-12">
														<div class="form-group">
																<label>Note</label>
																<textarea class="form-control" name="note" rows="4" placeholder="Optional note">{{ old('note', $payment->note) }}</textarea>
														</div>
												</div>
										</div>

										<div class="d-flex">
												<button type="submit" class="btn btn-primary mr-1">Update Payment</button>
												<a href="{{ route('payment.show', $payment->id) }}" class="btn btn-light border">Back</a>
										</div>
								</form>
						</div>
				</div>
		</div>
</section>
@endsection
