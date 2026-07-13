@extends('layouts.app')

@include('layouts.styles.datatables')

@section('header-bottom')
<style>
		#users-list thead tr.column-headings th { white-space: nowrap; }
		.workflow-list-toolbar {
				position: sticky;
				top: 78px;
				z-index: 90;
				background: #fff;
				border-bottom: 1px solid #edf1fb;
				padding: 8px 0 6px;
				margin-bottom: 10px;
		}
		#users-list thead tr.filter-row th {
				padding: 6px 6px !important;
				background: #f5f7fb;
				border-top: 0 !important;
		}
		#users-list thead tr.filter-row input,
		#users-list thead tr.filter-row select {
				width: 100%;
				min-width: 90px;
				height: 34px;
				padding: 4px 8px;
				border: 1px solid #d3dcf0;
				border-radius: 6px;
		}
		#users-list td:last-child { white-space: nowrap; }
		#users-list .wf-inline-actions {
				display: inline-flex;
				align-items: center;
				flex-wrap: nowrap;
				gap: 6px;
		}
		.coa-tree,
		.coa-tree ul {
				list-style: none;
				padding-left: 14px;
				margin-bottom: 0;
		}
		.coa-tree li {
				border-left: 1px dashed #d2dbef;
				padding: 8px 0 0 12px;
				position: relative;
		}
		.coa-tree li:before {
				content: '';
				position: absolute;
				left: 0;
				top: 16px;
				width: 10px;
				border-top: 1px dashed #d2dbef;
		}
		.coa-node {
				display: inline-flex;
				align-items: flex-start;
				gap: 8px;
				padding: 6px 10px;
				border-radius: 8px;
				border: 1px solid #dee6fb;
				background: #f8faff;
				font-size: 0.9rem;
		}
		.coa-name-stack {
				display: inline-flex;
				flex-direction: column;
				line-height: 1.3;
		}
		.coa-name-ar {
				font-size: 0.82rem;
				color: #62739a;
				direction: rtl;
		}
		.coa-code {
				font-weight: 700;
				color: #2e3b68;
		}
		.coa-type {
				font-size: 0.75rem;
				padding: 2px 7px;
				border-radius: 999px;
				border: 1px solid #cfd8f0;
				background: #fff;
				color: #56658f;
		}
		.acc-report-card {
				border: 1px solid #e4eafc;
				border-radius: 12px;
				padding: 14px;
				background: #fff;
				height: 100%;
		}
		.acc-report-card .label {
				color: #7383ac;
				font-size: 0.82rem;
				text-transform: uppercase;
				letter-spacing: 0.03em;
		}
		.acc-report-card .value {
				font-size: 1.4rem;
				font-weight: 700;
				color: #2d3a64;
		}
		.import-template-note {
				font-size: 0.82rem;
				color: #7080a6;
				line-height: 1.6;
		}
</style>
@endsection

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
						@if(session('success'))
								<div class="alert alert-success">{{ session('success') }}</div>
						@endif
						@if(session('error'))
								<div class="alert alert-danger">{{ session('error') }}</div>
						@endif

						@can('create', 'App\Models\WorkFlow\Accountant')
								<div class="d-flex flex-wrap mb-1">
										@include('layouts.repeated.workflow-jcf-create-picker', [
												'picker_id' => 'accountant-jcf-create',
												'button_label' => 'Create New Accountant Entry (From JCF)',
												'route_template' => route('accountant.accountantWithJobRequest', ['jobRequest' => '__ID__']),
												'modal_title' => 'Create Accountant Entry From JCF',
												'empty_text' => 'No JCF is currently available to create accountant entry.',
												'jcf_options' => $jcf_create_options ?? [],
										])
										<a href="{{ route('accountant.create') }}" class="btn btn-light border mb-1 ml-1">
												<i class="la la-plus"></i> Create Entry Without JCF
										</a>
								</div>
						@endcan

						@php
								$currentTab = $active_tab ?? 'entries';
								$currentStatusFilter = $selected_entry_status ?? '';
						@endphp

						<div class="card">
								<div class="card-content">
										<div class="card-body">
												<ul class="nav nav-tabs nav-top-border no-hover-bg nav-justified mb-2">
														<li class="nav-item">
																<a class="nav-link {{ $currentTab === 'entries' ? 'active' : '' }}" href="{{ route('accountant.index', ['tab' => 'entries']) }}">
																		<i class="la la-book mr-50"></i> Journal Entries
																</a>
														</li>
														<li class="nav-item">
																<a class="nav-link {{ $currentTab === 'chart-accounts' ? 'active' : '' }}" href="{{ route('accountant.index', ['tab' => 'chart-accounts']) }}">
																		<i class="la la-sitemap mr-50"></i> Chart of Accounts
																</a>
														</li>
														<li class="nav-item">
																<a class="nav-link {{ $currentTab === 'advanced-reports' ? 'active' : '' }}" href="{{ route('accountant.index', ['tab' => 'advanced-reports']) }}">
																		<i class="la la-bar-chart mr-50"></i> Advanced Reports
																</a>
														</li>
												</ul>

												@if($currentTab === 'entries')
														<div class="workflow-list-toolbar">
																<div class="d-flex justify-content-between align-items-center flex-wrap">
																		<div class="mb-50">
																				<a href="{{ route('accountant.index', ['tab' => 'entries']) }}" class="btn btn-sm {{ $currentStatusFilter === '' ? 'btn-primary' : 'btn-light border' }} mr-50 mb-50">
																						All Entries
																				</a>
																				<a href="{{ route('accountant.index', ['tab' => 'entries', 'entry_status' => 'pending_approval']) }}" class="btn btn-sm {{ $currentStatusFilter === 'pending_approval' ? 'btn-warning' : 'btn-light border' }} mb-50">
																						Pending Approval Queue
																						@if(($pending_approval_count ?? 0) > 0)
																								<span class="badge badge-pill badge-danger ml-50">{{ $pending_approval_count }}</span>
																						@endif
																				</a>
																		</div>
																		<button type="button" class="btn btn-sm btn-light border js-clear-workflow-filters">Clear Filters</button>
																</div>
														</div>
														<div class="table-responsive">
																<table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
																		<thead class="workflow-list-head">
																				<tr class="column-headings">
																						<th>Code</th>
																						<th>JCF</th>
																						<th>Client/Supplier</th>
																						<th>Posting Date</th>
																						<th>Entry Type</th>
																						<th>Debit Account</th>
																						<th>Credit Account</th>
																						<th>Amount</th>
																						<th>Currency</th>
																						<th>Status</th>
																						<th>Invoice</th>
																						<th>Payment</th>
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
																						<th></th>
																						<th></th>
																						<th></th>
																						<th></th>
																						<th></th>
																				</tr>
																		</thead>
																</table>
														</div>
												@endif

												@if($currentTab === 'chart-accounts')
														<div class="row">
																<div class="col-xl-4 col-12">
																		<div class="card border">
																				<div class="card-header">
																						<h4 class="card-title mb-0">Add Chart Account</h4>
																				</div>
																				<div class="card-body">
																						<form method="POST" action="{{ route('accountant.chartAccount.store') }}">
																								@csrf
																								<div class="form-group">
																										<label>Account Code</label>
																										<input type="text" class="form-control" name="code" placeholder="Ex: 1210" required>
																								</div>
																								<div class="form-group">
																										<label>Account Name (English)</label>
																										<input type="text" class="form-control" name="name_en" placeholder="Ex: Customer Receivable" required>
																								</div>
																								<div class="form-group">
																										<label>اسم الحساب (عربي)</label>
																										<input type="text" class="form-control" name="name_ar" placeholder="مثال: العملاء" dir="rtl" required>
																								</div>
																								<div class="form-group">
																										<label>Type</label>
																										<select class="form-control" name="type" required>
																												@foreach(($chart_account_type_options ?? []) as $typeValue => $typeLabel)
																														<option value="{{ $typeValue }}">{{ $typeLabel }}</option>
																												@endforeach
																										</select>
																								</div>
																								<div class="form-group">
																										<label>Parent Account (Optional)</label>
																										<select class="form-control" name="parent_id">
																												<option value="">Root Account</option>
																												@foreach(($chart_account_options ?? []) as $accountId => $accountLabel)
																														<option value="{{ $accountId }}">{{ $accountLabel }}</option>
																												@endforeach
																										</select>
																								</div>
																								<div class="form-group">
																										<label>Note</label>
																										<textarea class="form-control" name="note" rows="3"></textarea>
																								</div>
																								<div class="form-group">
																										<div class="custom-control custom-checkbox">
																												<input type="checkbox" class="custom-control-input" id="chart-account-active" name="is_active" value="1" checked>
																												<label class="custom-control-label" for="chart-account-active">Active</label>
																										</div>
																								</div>
																								<button type="submit" class="btn btn-primary">Add Account</button>
																						</form>
																				</div>
																		</div>
																		<div class="card border">
																				<div class="card-header">
																						<h4 class="card-title mb-0">Import / Export Tree</h4>
																				</div>
																				<div class="card-body">
																						<div class="d-flex flex-wrap mb-1">
																								<a href="{{ route('accountant.chartAccount.export') }}" class="btn btn-sm btn-success mr-1 mb-1">
																										<i class="la la-download"></i> Export Current Tree CSV
																								</a>
																								<a href="{{ route('accountant.chartAccount.template') }}" class="btn btn-sm btn-info mb-1">
																										<i class="la la-file-text-o"></i> Download Standard Template
																								</a>
																						</div>
																						<div class="import-template-note mb-1">
																								Required columns:
																								<code>code,name_en,name_ar,type,parent_code,is_active,note</code>
																						</div>
																						<form method="POST" action="{{ route('accountant.chartAccount.import') }}" enctype="multipart/form-data">
																								@csrf
																								<div class="form-group">
																										<label>Import CSV File</label>
																										<input type="file" class="form-control" name="chart_accounts_file" accept=".csv,text/csv" required>
																								</div>
																								<button type="submit" class="btn btn-primary btn-sm">
																										<i class="la la-upload"></i> Import Tree
																								</button>
																						</form>
																				</div>
																		</div>
																</div>
																<div class="col-xl-8 col-12">
																		<div class="card border">
																				<div class="card-header">
																						<h4 class="card-title mb-0">Accounting Tree | شجرة الحسابات</h4>
																				</div>
																				<div class="card-body">
																						@if(($chart_accounts_roots ?? collect())->isEmpty())
																								<p class="text-muted mb-0">No chart accounts found.</p>
																						@else
																								<ul class="coa-tree">
																										@foreach($chart_accounts_roots as $accountNode)
																												@include('layouts.work-flow.accountant.partials.chart-account-node', ['accountNode' => $accountNode])
																										@endforeach
																								</ul>
																						@endif
																				</div>
																		</div>
																</div>
														</div>
												@endif

												@if($currentTab === 'advanced-reports')
														<form method="GET" action="{{ route('accountant.index') }}">
																<input type="hidden" name="tab" value="advanced-reports">
																<div class="row">
																		<div class="col-md-2 col-12">
																				<div class="form-group">
																						<label>Date From</label>
																						<input type="date" class="form-control" name="report_date_from" value="{{ $report_date_from ?: '' }}">
																				</div>
																		</div>
																		<div class="col-md-2 col-12">
																				<div class="form-group">
																						<label>Date To</label>
																						<input type="date" class="form-control" name="report_date_to" value="{{ $report_date_to ?: '' }}">
																				</div>
																		</div>
																		<div class="col-md-3 col-12">
																				<div class="form-group">
																						<label>Ledger Account</label>
																						<select class="form-control" name="ledger_account_id">
																								<option value="">Select account (optional)</option>
																								@foreach(($chart_account_options ?? []) as $accountId => $accountLabel)
																										<option value="{{ $accountId }}" {{ (string)$ledger_account_id === (string)$accountId ? 'selected' : '' }}>{{ $accountLabel }}</option>
																								@endforeach
																						</select>
																				</div>
																		</div>
																		<div class="col-md-2 col-12">
																				<div class="form-group">
																						<label>Currency</label>
																						<select class="form-control" name="report_currency">
																								<option value="">All</option>
																								@foreach(($report_currency_options ?? []) as $currencyOption)
																										<option value="{{ $currencyOption }}" {{ (string)($report_currency ?? '') === (string)$currencyOption ? 'selected' : '' }}>{{ $currencyOption }}</option>
																								@endforeach
																						</select>
																				</div>
																		</div>
																		<div class="col-md-3 col-12 d-flex align-items-center">
																				<button type="submit" class="btn btn-primary mr-1">Apply</button>
																				<a href="{{ route('accountant.index', ['tab' => 'advanced-reports']) }}" class="btn btn-light border">Reset</a>
																		</div>
																</div>
														</form>

														@php
																$exportQuery = [
																		'report_date_from' => $report_date_from,
																		'report_date_to' => $report_date_to,
																		'ledger_account_id' => $ledger_account_id,
																		'report_currency' => $report_currency,
																];
														@endphp
														<div class="d-flex flex-wrap mb-2">
																<a href="{{ route('accountant.advancedReports.exportExcel', $exportQuery) }}" class="btn btn-sm btn-success mr-1 mb-1">
																		<i class="la la-file-excel-o"></i> Export Excel (CSV)
																</a>
																<a href="{{ route('accountant.advancedReports.exportPdf', $exportQuery) }}" class="btn btn-sm btn-danger mb-1">
																		<i class="la la-file-pdf-o"></i> Export PDF
																</a>
														</div>

														@if(($is_mixed_currency ?? false) && empty($report_currency))
																<div class="alert alert-warning">
																		Selected data contains multiple currencies. Totals below are aggregated; for strict accuracy apply a currency filter.
																</div>
														@endif

														<div class="card border mb-2">
																<div class="card-header d-flex justify-content-between align-items-center">
																		<h4 class="card-title mb-0">Accounting Period Lock</h4>
																</div>
																<div class="card-body">
																		@php
																				$canManagePeriods = auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('accountant', 'approve') || auth()->user()->hasPermission('accountant', 'all');
																		@endphp
																		<div class="row">
																				<div class="col-lg-6 col-12 mb-1">
																						@if($canManagePeriods)
																								<form method="POST" action="{{ route('accountant.period.close') }}" class="form-inline">
																										@csrf
																										<label class="mr-1">Close:</label>
																										<input type="number" min="2000" max="2100" class="form-control mr-1 mb-50" name="period_year" value="{{ now()->format('Y') }}" style="max-width:110px;">
																										<select class="form-control mr-1 mb-50" name="period_month" style="max-width:120px;">
																												@for($month = 1; $month <= 12; $month++)
																														<option value="{{ $month }}" {{ (int)now()->format('m') === $month ? 'selected' : '' }}>{{ str_pad((string)$month, 2, '0', STR_PAD_LEFT) }}</option>
																												@endfor
																										</select>
																										<input type="text" class="form-control mr-1 mb-50" name="period_note" placeholder="Optional note" style="max-width:220px;">
																										<button type="submit" class="btn btn-warning btn-sm mb-50">Close Period</button>
																								</form>
																						@else
																								<span class="text-muted">You do not have permission to close accounting periods.</span>
																						@endif
																				</div>
																				<div class="col-lg-6 col-12 mb-1">
																						@if($canManagePeriods)
																								<form method="POST" action="{{ route('accountant.period.reopen') }}" class="form-inline">
																										@csrf
																										<label class="mr-1">Reopen:</label>
																										<input type="number" min="2000" max="2100" class="form-control mr-1 mb-50" name="period_year" value="{{ now()->format('Y') }}" style="max-width:110px;">
																										<select class="form-control mr-1 mb-50" name="period_month" style="max-width:120px;">
																												@for($month = 1; $month <= 12; $month++)
																														<option value="{{ $month }}" {{ (int)now()->format('m') === $month ? 'selected' : '' }}>{{ str_pad((string)$month, 2, '0', STR_PAD_LEFT) }}</option>
																												@endfor
																										</select>
																										<button type="submit" class="btn btn-secondary btn-sm mb-50">Reopen Period</button>
																								</form>
																						@else
																								<span class="text-muted">Read-only period lock table.</span>
																						@endif
																				</div>
																		</div>
																		<div class="table-responsive">
																				<table class="table table-sm table-bordered mb-0">
																						<thead>
																						<tr>
																								<th>Period</th>
																								<th>Status</th>
																								<th>Closed By</th>
																								<th>Closed At</th>
																								<th>Note</th>
																						</tr>
																						</thead>
																						<tbody>
																						@forelse(($accounting_periods ?? collect()) as $period)
																								<tr>
																										<td>{{ sprintf('%04d-%02d', $period->year, $period->month) }}</td>
																										<td>
																												@if((int)$period->is_closed === 1)
																														<span class="badge badge-danger">Closed</span>
																												@else
																														<span class="badge badge-success">Open</span>
																												@endif
																										</td>
																										<td>{{ optional(optional($period->closedBy)->employee)->name ?: '-' }}</td>
																										<td>{{ $period->closed_at ? \Carbon\Carbon::parse($period->closed_at)->format('d-m-Y H:i') : '-' }}</td>
																										<td>{{ $period->note ?: '-' }}</td>
																								</tr>
																						@empty
																								<tr><td colspan="5" class="text-center text-muted">No period locks yet.</td></tr>
																						@endforelse
																						</tbody>
																				</table>
																		</div>
																</div>
														</div>

														<div class="card border">
																<div class="card-header"><h4 class="card-title mb-0">Currency Totals</h4></div>
																<div class="card-body">
																		<div class="table-responsive">
																				<table class="table table-bordered table-sm">
																						<thead>
																						<tr>
																								<th>Currency</th>
																								<th class="text-right">Debit</th>
																								<th class="text-right">Credit</th>
																								<th class="text-right">Difference</th>
																						</tr>
																						</thead>
																						<tbody>
																						@forelse(($currency_totals ?? collect()) as $row)
																								<tr>
																										<td>{{ $row['currency'] }}</td>
																										<td class="text-right">{{ number_format((float)$row['debit'], 2, '.', ',') }}</td>
																										<td class="text-right">{{ number_format((float)$row['credit'], 2, '.', ',') }}</td>
																										<td class="text-right">{{ number_format((float)$row['difference'], 2, '.', ',') }}</td>
																								</tr>
																						@empty
																								<tr><td colspan="4" class="text-center text-muted">No posted data.</td></tr>
																						@endforelse
																						</tbody>
																				</table>
																		</div>
																</div>
														</div>

														<div class="row mb-2">
																<div class="col-xl-3 col-md-6 col-12 mb-1">
																		<div class="acc-report-card">
																				<div class="label">Trial Balance Diff</div>
																				<div class="value">{{ number_format((float)($trial_balance_totals['difference'] ?? 0), 2, '.', ',') }}</div>
																		</div>
																</div>
																<div class="col-xl-3 col-md-6 col-12 mb-1">
																		<div class="acc-report-card">
																				<div class="label">Net Income</div>
																				<div class="value">{{ number_format((float)($income_statement_totals['net_income'] ?? 0), 2, '.', ',') }}</div>
																		</div>
																</div>
																<div class="col-xl-3 col-md-6 col-12 mb-1">
																		<div class="acc-report-card">
																				<div class="label">Assets</div>
																				<div class="value">{{ number_format((float)($balance_sheet['assets_total'] ?? 0), 2, '.', ',') }}</div>
																		</div>
																</div>
																<div class="col-xl-3 col-md-6 col-12 mb-1">
																		<div class="acc-report-card">
																				<div class="label">Liabilities + Equity</div>
																				<div class="value">{{ number_format((float)($balance_sheet['liabilities_and_equity_total'] ?? 0), 2, '.', ',') }}</div>
																		</div>
																</div>
														</div>

														<div class="card border">
																<div class="card-header"><h4 class="card-title mb-0">Trial Balance</h4></div>
																<div class="card-body">
																		<div class="table-responsive">
																				<table class="table table-bordered table-sm">
																						<thead>
																						<tr>
																								<th>Code</th>
																								<th>Account</th>
																								<th>Type</th>
																								<th>Currency</th>
																								<th class="text-right">Debit</th>
																								<th class="text-right">Credit</th>
																								<th class="text-right">Net</th>
																						</tr>
																						</thead>
																						<tbody>
																						@forelse($trial_balance_rows as $row)
																								<tr>
																										<td>{{ $row['code'] }}</td>
																										<td>{{ $row['name'] }}</td>
																										<td>{{ ucfirst($row['type']) }}</td>
																										<td>{{ $row['currency'] ?? '-' }}</td>
																										<td class="text-right">{{ number_format((float)$row['debit'], 2, '.', ',') }}</td>
																										<td class="text-right">{{ number_format((float)$row['credit'], 2, '.', ',') }}</td>
																										<td class="text-right">{{ number_format((float)$row['net'], 2, '.', ',') }}</td>
																								</tr>
																						@empty
																								<tr><td colspan="7" class="text-center text-muted">No posted entries for selected filters.</td></tr>
																						@endforelse
																						</tbody>
																						<tfoot>
																						<tr>
																								<th colspan="4" class="text-right">Totals</th>
																								<th class="text-right">{{ number_format((float)($trial_balance_totals['debit'] ?? 0), 2, '.', ',') }}</th>
																								<th class="text-right">{{ number_format((float)($trial_balance_totals['credit'] ?? 0), 2, '.', ',') }}</th>
																								<th class="text-right">{{ number_format((float)($trial_balance_totals['difference'] ?? 0), 2, '.', ',') }}</th>
																						</tr>
																						</tfoot>
																				</table>
																		</div>
																</div>
														</div>

														<div class="card border">
																<div class="card-header"><h4 class="card-title mb-0">Income Statement</h4></div>
																<div class="card-body">
																		<div class="table-responsive">
																				<table class="table table-bordered table-sm">
																						<thead>
																						<tr>
																								<th>Code</th>
																								<th>Account</th>
																								<th>Type</th>
																								<th>Currency</th>
																								<th class="text-right">Amount</th>
																						</tr>
																						</thead>
																						<tbody>
																						@forelse($income_statement_rows as $row)
																								<tr>
																										<td>{{ $row['code'] }}</td>
																										<td>{{ $row['name'] }}</td>
																										<td>{{ ucfirst($row['type']) }}</td>
																										<td>{{ $row['currency'] ?? '-' }}</td>
																										<td class="text-right">{{ number_format((float)$row['normal_amount'], 2, '.', ',') }}</td>
																								</tr>
																						@empty
																								<tr><td colspan="5" class="text-center text-muted">No income statement data.</td></tr>
																						@endforelse
																						</tbody>
																						<tfoot>
																						<tr>
																								<th colspan="4" class="text-right">Total Revenue</th>
																								<th class="text-right">{{ number_format((float)($income_statement_totals['revenue'] ?? 0), 2, '.', ',') }}</th>
																						</tr>
																						<tr>
																								<th colspan="4" class="text-right">Total Expense</th>
																								<th class="text-right">{{ number_format((float)($income_statement_totals['expense'] ?? 0), 2, '.', ',') }}</th>
																						</tr>
																						<tr>
																								<th colspan="4" class="text-right">Net Income</th>
																								<th class="text-right">{{ number_format((float)($income_statement_totals['net_income'] ?? 0), 2, '.', ',') }}</th>
																						</tr>
																						</tfoot>
																				</table>
																		</div>
																</div>
														</div>

														<div class="card border">
																<div class="card-header"><h4 class="card-title mb-0">Balance Sheet</h4></div>
																<div class="card-body">
																		<div class="table-responsive">
																				<table class="table table-bordered table-sm">
																						<thead>
																						<tr>
																								<th>Code</th>
																								<th>Account</th>
																								<th>Type</th>
																								<th>Currency</th>
																								<th class="text-right">Balance</th>
																						</tr>
																						</thead>
																						<tbody>
																						@forelse(($balance_sheet['rows'] ?? collect()) as $row)
																								<tr>
																										<td>{{ $row['code'] }}</td>
																										<td>{{ $row['name'] }}</td>
																										<td>{{ ucfirst($row['type']) }}</td>
																										<td>{{ $row['currency'] ?? '-' }}</td>
																										<td class="text-right">{{ number_format((float)$row['balance'], 2, '.', ',') }}</td>
																								</tr>
																						@empty
																								<tr><td colspan="5" class="text-center text-muted">No balance sheet data.</td></tr>
																						@endforelse
																						</tbody>
																						<tfoot>
																						<tr>
																								<th colspan="4" class="text-right">Assets Total</th>
																								<th class="text-right">{{ number_format((float)($balance_sheet['assets_total'] ?? 0), 2, '.', ',') }}</th>
																						</tr>
																						<tr>
																								<th colspan="4" class="text-right">Liabilities + Equity</th>
																								<th class="text-right">{{ number_format((float)($balance_sheet['liabilities_and_equity_total'] ?? 0), 2, '.', ',') }}</th>
																						</tr>
																						<tr>
																								<th colspan="4" class="text-right">Difference</th>
																								<th class="text-right">{{ number_format((float)($balance_sheet['difference'] ?? 0), 2, '.', ',') }}</th>
																						</tr>
																						</tfoot>
																				</table>
																		</div>
																</div>
														</div>

														<div class="card border">
																<div class="card-header"><h4 class="card-title mb-0">General Ledger</h4></div>
																<div class="card-body">
																		@if(!$ledger_account_id)
																				<p class="text-muted mb-0">Select `Ledger Account` above to show detailed ledger lines.</p>
																		@else
																				<div class="table-responsive">
																						<table class="table table-bordered table-sm">
																								<thead>
																								<tr>
																										<th>Date</th>
																										<th>Entry</th>
																										<th>Reference</th>
																										<th>Memo</th>
																										<th>Side</th>
																										<th>Currency</th>
																										<th class="text-right">Debit</th>
																										<th class="text-right">Credit</th>
																										<th class="text-right">Running Balance</th>
																								</tr>
																								</thead>
																								<tbody>
																								@forelse($ledger_rows as $row)
																										<tr>
																												<td>{{ $row['posting_date'] }}</td>
																												<td>{{ $row['entry_code'] }}</td>
																												<td>{{ $row['reference_no'] }}</td>
																												<td>{{ $row['memo'] }}</td>
																												<td>{{ $row['side'] }}</td>
																												<td>{{ $row['currency'] ?? '-' }}</td>
																												<td class="text-right">{{ number_format((float)$row['debit'], 2, '.', ',') }}</td>
																												<td class="text-right">{{ number_format((float)$row['credit'], 2, '.', ',') }}</td>
																												<td class="text-right">{{ number_format((float)$row['running_balance'], 2, '.', ',') }}</td>
																										</tr>
																								@empty
																										<tr><td colspan="9" class="text-center text-muted">No ledger entries in selected range.</td></tr>
																								@endforelse
																								</tbody>
																								<tfoot>
																								<tr>
																										<th colspan="6" class="text-right">Totals</th>
																										<th class="text-right">{{ number_format((float)($ledger_totals['debit'] ?? 0), 2, '.', ',') }}</th>
																										<th class="text-right">{{ number_format((float)($ledger_totals['credit'] ?? 0), 2, '.', ',') }}</th>
																										<th class="text-right">{{ number_format((float)($ledger_totals['balance'] ?? 0), 2, '.', ',') }}</th>
																								</tr>
																								</tfoot>
																						</table>
																				</div>
																		@endif
																</div>
														</div>
												@endif
										</div>
								</div>
						</div>
				</div>
		</section>
@endsection

@if(($active_tab ?? 'entries') === 'entries')
		@include('layouts.scripts.datatables', [
				'route' => 'accountant',
				'route_param'=> ['job_request_id' => $selected_job_request_id, 'entry_status' => $selected_entry_status ?? ''],
				'columns' => ['code', 'job_request_code', 'client', 'posting_date', 'entry_type', 'debit_account', 'credit_account', 'amount', 'currency', 'status', 'invoice_code', 'payment_code', 'employee', 'action'],
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
@endif

@section('ajax')
<script>
		function resetWorkflowListFilters() {
				if (typeof table === 'undefined') {
						return;
				}
				table.search('');
				table.columns().search('');
				$('#users-list thead tr.filter-row').find('input, select').val('');
		}

		$(document).on('click', '.js-clear-workflow-filters', function () {
				resetWorkflowListFilters();
				table.draw();
		});
</script>
@endsection
