@extends('layouts.app')

@section('content')
<section class="users-list-wrapper">
		<div class="card">
				<div class="card-content">
						<div class="card-body">
								<form method="POST" action="{{ route('inventory.update', $inventory->id) }}">
										@csrf
										@method('PATCH')

										<div class="row">
												<div class="col-md-4 col-12">
														<div class="form-group">
																<label>JCF Number</label>
																<select class="form-control" name="job_request_id" required>
																		<option value="">Select JCF</option>
																		@foreach(($job_request_options ?? []) as $jobRequestId => $jobRequestLabel)
																				<option value="{{ $jobRequestId }}" {{ (string)old('job_request_id', $inventory->job_request_id) === (string)$jobRequestId ? 'selected' : '' }}>
																						{{ $jobRequestLabel }}
																				</option>
																		@endforeach
																</select>
														</div>
												</div>
												<div class="col-md-4 col-12">
														<div class="form-group">
																<label>Item Name</label>
																<input type="text" name="item_name" class="form-control" value="{{ old('item_name', $inventory->item_name) }}" required>
														</div>
												</div>
												<div class="col-md-4 col-12">
														<div class="form-group">
																<label>SKU</label>
																<input type="text" name="sku" class="form-control" value="{{ old('sku', $inventory->sku) }}">
														</div>
												</div>
										</div>

										<div class="row">
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Quantity</label>
																<input type="text" name="quantity" class="form-control" value="{{ old('quantity', number_format((float)$inventory->quantity, 2, '.', '')) }}" placeholder="0.00">
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Unit</label>
																<input type="text" name="unit" class="form-control" value="{{ old('unit', $inventory->unit) }}" placeholder="PCS / SET / HR">
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Location</label>
																<input type="text" name="location" class="form-control" value="{{ old('location', $inventory->location) }}">
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group">
																<label>Min Quantity</label>
																<input type="text" name="min_quantity" class="form-control" value="{{ old('min_quantity', number_format((float)$inventory->min_quantity, 2, '.', '')) }}" placeholder="0.00">
														</div>
												</div>
										</div>

										<div class="row">
												<div class="col-md-4 col-12">
														<div class="form-group">
																<label>Status</label>
																@php($statusValue = old('status', $inventory->status))
																<select class="form-control" name="status">
																		<option value="available" {{ $statusValue === 'available' ? 'selected' : '' }}>Available</option>
																		<option value="reserved" {{ $statusValue === 'reserved' ? 'selected' : '' }}>Reserved</option>
																		<option value="issued" {{ $statusValue === 'issued' ? 'selected' : '' }}>Issued</option>
																		<option value="damaged" {{ $statusValue === 'damaged' ? 'selected' : '' }}>Damaged</option>
																</select>
														</div>
												</div>
												<div class="col-md-8 col-12">
														<div class="form-group">
																<label>Note</label>
																<textarea class="form-control" name="note" rows="3">{{ old('note', $inventory->note) }}</textarea>
														</div>
												</div>
										</div>

										<div class="d-flex">
												<button type="submit" class="btn btn-primary mr-1">Update Inventory</button>
												<a href="{{ route('inventory.show', $inventory->id) }}" class="btn btn-light border">Back</a>
										</div>
								</form>
						</div>
				</div>
		</div>
</section>
@endsection
