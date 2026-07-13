<li>
		<div class="coa-node">
				<span class="coa-code">{{ $accountNode->code }}</span>
				<span class="coa-name-stack">
						<span>{{ $accountNode->name_en ?: $accountNode->name }}</span>
						<span class="coa-name-ar">{{ $accountNode->name_ar ?: ($accountNode->name_en ?: $accountNode->name) }}</span>
				</span>
				<span class="coa-type">{{ $accountNode->type_label }}</span>
				@if((int)$accountNode->is_active !== 1)
						<span class="badge badge-warning">Inactive</span>
				@endif
				@if(!empty($can_manage_chart_accounts))
						<button type="button" class="btn btn-sm btn-info ml-50" data-toggle="collapse" data-target="#coa-edit-{{ $accountNode->id }}" aria-expanded="false" aria-controls="coa-edit-{{ $accountNode->id }}">
								<i class="la la-pencil"></i>
						</button>
				@endif
		</div>
		@if(!empty($can_manage_chart_accounts))
				<div class="collapse mt-50" id="coa-edit-{{ $accountNode->id }}">
						<div class="card card-body border mb-0">
								<form method="POST" action="{{ route('accountant.chartAccount.update', $accountNode->id) }}">
										@csrf
										@method('PATCH')
										<div class="row">
												<div class="col-md-3 col-12">
														<div class="form-group mb-50">
																<label class="mb-25">Code</label>
																<input type="text" class="form-control form-control-sm" name="code" value="{{ $accountNode->code }}" required>
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group mb-50">
																<label class="mb-25">Name (EN)</label>
																<input type="text" class="form-control form-control-sm" name="name_en" value="{{ $accountNode->name_en ?: $accountNode->name }}" required>
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group mb-50">
																<label class="mb-25">Name (AR)</label>
																<input type="text" class="form-control form-control-sm" name="name_ar" value="{{ $accountNode->name_ar }}" dir="rtl" required>
														</div>
												</div>
												<div class="col-md-3 col-12">
														<div class="form-group mb-50">
																<label class="mb-25">Type</label>
																<select class="form-control form-control-sm" name="type" required>
																		@foreach(($chart_account_type_options ?? []) as $typeValue => $typeLabel)
																				<option value="{{ $typeValue }}" {{ (string)$accountNode->type === (string)$typeValue ? 'selected' : '' }}>{{ $typeLabel }}</option>
																		@endforeach
																</select>
														</div>
												</div>
										</div>
										<div class="row">
												<div class="col-md-4 col-12">
														<div class="form-group mb-50">
																<label class="mb-25">Parent</label>
																<select class="form-control form-control-sm" name="parent_id">
																		<option value="">Root Account</option>
																		@foreach(($chart_account_options ?? []) as $accountId => $accountLabel)
																				@if((int)$accountId !== (int)$accountNode->id)
																						<option value="{{ $accountId }}" {{ (int)$accountNode->parent_id === (int)$accountId ? 'selected' : '' }}>{{ $accountLabel }}</option>
																				@endif
																		@endforeach
																</select>
														</div>
												</div>
												<div class="col-md-5 col-12">
														<div class="form-group mb-50">
																<label class="mb-25">Note</label>
																<input type="text" class="form-control form-control-sm" name="note" value="{{ $accountNode->note }}">
														</div>
												</div>
												<div class="col-md-3 col-12 d-flex align-items-center">
														<div class="custom-control custom-checkbox mr-1">
																<input type="checkbox" class="custom-control-input" id="coa-active-{{ $accountNode->id }}" name="is_active" value="1" {{ (int)$accountNode->is_active === 1 ? 'checked' : '' }}>
																<label class="custom-control-label" for="coa-active-{{ $accountNode->id }}">Active</label>
														</div>
														<button type="submit" class="btn btn-sm btn-primary">
																Save
														</button>
												</div>
										</div>
								</form>
						</div>
				</div>
		@endif

		@if($accountNode->childrenRecursive->isNotEmpty())
				<ul>
						@foreach($accountNode->childrenRecursive as $accountNode)
								@include('layouts.work-flow.accountant.partials.chart-account-node', ['accountNode' => $accountNode])
						@endforeach
				</ul>
		@endif
</li>
