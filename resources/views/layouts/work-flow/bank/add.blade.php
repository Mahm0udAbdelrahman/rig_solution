@extends('layouts.app')

@section('content')
<section class="users-list-wrapper">
		<div class="card">
				<div class="card-content">
						<div class="card-body">
								<form method="POST" action="{{ route('bank.store') }}">
										@csrf
										@if($errors->any())
												<div class="alert alert-danger">
														<strong>Please fix the following before saving:</strong>
														<ul class="mb-0 mt-50">
																@foreach($errors->all() as $error)
																		<li>{{ $error }}</li>
																@endforeach
														</ul>
												</div>
										@endif

										<div class="row">
												<div class="col-md-4 col-12">
														<div class="form-group">
																<label>Bank Name</label>
																<input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}" required>
														</div>
												</div>
												<div class="col-md-4 col-12">
														<div class="form-group">
																<label>Account Name</label>
																<input type="text" name="account_name" class="form-control" value="{{ old('account_name') }}" required>
														</div>
												</div>
												<div class="col-md-4 col-12">
														<div class="form-group">
																<label>Account Number</label>
																<input type="text" name="account_number" class="form-control" value="{{ old('account_number') }}" required>
														</div>
												</div>
										</div>

										<div class="row">
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>IBAN (Optional)</label>
																<input type="text" name="iban" class="form-control" value="{{ old('iban') }}">
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Currency</label>
																<input type="text" name="currency" class="form-control" value="{{ old('currency', 'USD') }}" required>
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Bank Chart Account</label>
																<select class="form-control" name="chart_account_id">
																		<option value="">Select account</option>
																		@foreach(($chart_account_options ?? []) as $accountId => $accountLabel)
																				<option value="{{ $accountId }}" {{ (string)old('chart_account_id') === (string)$accountId ? 'selected' : '' }}>
																						{{ $accountLabel }}
																				</option>
																		@endforeach
																</select>
																<small class="text-muted">Used for automatic accountant entries from approved bank transactions.</small>
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Opening Balance</label>
																<input type="number" step="0.01" name="opening_balance" class="form-control" value="{{ old('opening_balance', '0.00') }}" required>
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Opening Date</label>
																<input type="date" name="opening_date" class="form-control" value="{{ old('opening_date', $opening_date ?? date('Y-m-d')) }}">
														</div>
												</div>
										</div>

										<div class="row">
												<div class="col-12">
														<div class="form-group">
																<label>Note</label>
																<textarea class="form-control" name="note" rows="4">{{ old('note') }}</textarea>
														</div>
												</div>
										</div>

										<div class="row">
												<div class="col-12">
														<div class="form-group mb-0">
																<div class="custom-control custom-checkbox">
																		<input type="checkbox" class="custom-control-input" id="is-active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
																		<label class="custom-control-label" for="is-active">Active bank account</label>
																</div>
														</div>
												</div>
										</div>

										<div class="d-flex">
												<button type="submit" class="btn btn-primary mr-1">Save Bank Account</button>
												<a href="{{ route('bank.index') }}" class="btn btn-light border">Cancel</a>
										</div>
								</form>
						</div>
				</div>
		</div>
</section>
@endsection
