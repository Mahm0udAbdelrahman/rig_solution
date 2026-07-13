@extends('layouts.app')

@section('header-bottom')
<style>
		.dashboard-grid {
				display: grid;
				grid-template-columns: repeat(12, minmax(0, 1fr));
				gap: 16px;
		}
		.dashboard-card {
				border: 1px solid #e8ecf5;
				border-radius: 12px;
				background: #fff;
				box-shadow: 0 6px 18px rgba(22, 34, 51, 0.05);
		}
		.dashboard-card-head {
				padding: 14px 16px;
				border-bottom: 1px solid #eef2fb;
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 10px;
				flex-wrap: wrap;
		}
		.dashboard-card-title {
				margin: 0;
				font-size: 1rem;
				font-weight: 700;
				color: #26304a;
		}
		.workspace-title {
				font-size: 1.1rem;
				font-weight: 700;
				color: #2b3562;
				margin-bottom: 4px;
		}
		.workspace-hint {
				color: #6f7fa0;
				font-size: .85rem;
				margin: 0;
		}
		.workspace-metrics {
				display: grid;
				grid-template-columns: repeat(4, minmax(0, 1fr));
				gap: 10px;
				margin-top: 14px;
		}
		.workspace-metric {
				border: 1px solid #e8edfb;
				border-radius: 10px;
				background: #f9fbff;
				padding: 10px 12px;
		}
		.workspace-metric-label {
				display: block;
				font-size: .76rem;
				color: #7485a7;
		}
		.workspace-metric-value {
				display: block;
				font-size: 1.18rem;
				font-weight: 700;
				color: #2d3768;
				line-height: 1.1;
				margin-top: 4px;
		}
		.workspace-badges {
				display: flex;
				flex-wrap: wrap;
				gap: 8px;
				margin-top: 12px;
		}
		.dashboard-card-body {
				padding: 16px;
		}
		.kpi-wrap {
				grid-column: span 12;
				display: grid;
				grid-template-columns: repeat(12, minmax(0, 1fr));
				gap: 12px;
		}
		.kpi-card {
				grid-column: span 3;
				border-radius: 12px;
				padding: 14px;
				border: 1px solid #e9edfa;
				background: linear-gradient(145deg, #ffffff 0%, #f8faff 100%);
				transition: all .16s ease;
				text-decoration: none !important;
		}
		.kpi-card--financial {
				background: linear-gradient(145deg, #ffffff 0%, #eef8ff 100%);
				border-color: #d9ebff;
		}
		.kpi-card--workflow {
				background: linear-gradient(145deg, #ffffff 0%, #f6f4ff 100%);
				border-color: #e8e0ff;
		}
		.kpi-card:hover {
				transform: translateY(-2px);
				box-shadow: 0 8px 16px rgba(47, 67, 125, 0.12);
		}
		.kpi-label {
				font-size: .85rem;
				color: #60708f;
				margin-bottom: 6px;
				display: block;
		}
		.kpi-value {
				font-size: 1.55rem;
				font-weight: 700;
				color: #2a3562;
				line-height: 1.1;
		}
		.kpi-sub {
				font-size: .75rem;
				color: #8793aa;
				margin-top: 6px;
				display: block;
		}
		.scope-switch {
				display: flex;
				flex-wrap: wrap;
				gap: 8px;
		}
		.scope-btn {
				border: 1px solid #dce4fa;
				background: #f8faff;
				color: #44527a;
				border-radius: 999px;
				padding: 5px 10px;
				font-size: .76rem;
				font-weight: 700;
				cursor: pointer;
		}
		.scope-btn.is-active {
				background: #5a46d6;
				border-color: #5a46d6;
				color: #fff;
		}
		.state-pill {
				font-size: .74rem;
				padding: 3px 10px;
				border-radius: 999px;
				font-weight: 700;
		}
		.state-open { color: #6a5f00; background: #fff6cf; }
		.state-ready { color: #0d5b71; background: #d8f6ff; }
		.state-completed { color: #0d6a35; background: #daf8e5; }
		.state-canceled { color: #7a1d1d; background: #ffe0e0; }
		.section-actions { grid-column: span 7; }
		.section-shortcuts { grid-column: span 5; }
		.section-snapshot { grid-column: span 12; }
		.section-inspection-approvals { grid-column: span 12; }
		.section-permissions { grid-column: span 12; }
		.section-recent { grid-column: span 12; }
		.action-row {
				display: flex;
				align-items: flex-start;
				justify-content: space-between;
				gap: 10px;
				padding: 12px;
				border: 1px solid #e9eef8;
				border-radius: 10px;
				margin-bottom: 10px;
				background: #fbfcff;
		}
		.action-title {
				font-size: .95rem;
				font-weight: 700;
				color: #2b3453;
				margin-bottom: 2px;
		}
		.action-desc {
				font-size: .8rem;
				color: #697997;
				margin: 0;
		}
		.queue-chip {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 10px;
				border: 1px solid #e8eef9;
				border-radius: 10px;
				background: #fbfcff;
				padding: 10px 12px;
				margin-bottom: 8px;
		}
		a.queue-chip {
				text-decoration: none !important;
				color: #2a3562;
				transition: all .15s ease;
		}
		a.queue-chip:hover {
				border-color: #cad5f6;
				background: #f5f8ff;
		}
		a.queue-chip.is-active {
				border-color: #5a46d6;
				background: #f1eeff;
		}
		.shortcut-grid {
				display: grid;
				grid-template-columns: repeat(2, minmax(0, 1fr));
				gap: 10px;
		}
		.shortcut-btn {
				display: flex;
				align-items: center;
				gap: 8px;
				padding: 10px 12px;
				border-radius: 8px;
				background: #f7f9ff;
				border: 1px solid #e4e9fb;
				font-weight: 600;
				color: #2a3562 !important;
				text-decoration: none !important;
		}
		.shortcut-btn:hover {
				background: #eff3ff;
		}
		.snapshot-grid {
				display: grid;
				grid-template-columns: repeat(4, minmax(0, 1fr));
				gap: 10px;
		}
		.snapshot-item {
				border: 1px solid #e6ecfa;
				border-radius: 10px;
				padding: 11px 12px;
				background: #fbfcff;
		}
		.snapshot-label {
				font-size: .76rem;
				color: #7282a3;
				display: block;
		}
		.snapshot-value {
				font-size: 1.05rem;
				font-weight: 700;
				color: #2a3562;
				display: block;
				margin-top: 4px;
		}
		.permission-table th {
				font-size: .78rem;
				text-transform: uppercase;
				letter-spacing: .02em;
				color: #5a6a8a;
		}
		.permission-check {
				font-size: 1rem;
		}
		.recent-table th {
				font-size: .78rem;
				text-transform: uppercase;
				letter-spacing: .02em;
				color: #5a6a8a;
				border-top: 0 !important;
		}
		.recent-table td {
				vertical-align: middle !important;
		}
		.recent-link {
				color: #5a46d6;
				font-weight: 700;
		}
		.inspection-approval-toolbar {
				display: flex;
				align-items: flex-end;
				justify-content: space-between;
				gap: 12px;
				flex-wrap: wrap;
				margin-bottom: 12px;
		}
		.inspection-approval-user-form {
				display: flex;
				align-items: flex-end;
				gap: 8px;
				margin: 0;
		}
		.inspection-approval-user-form label {
				margin: 0;
				font-size: .78rem;
				color: #627292;
				font-weight: 700;
		}
		.inspection-approval-user-form select {
				min-width: 220px;
		}
		@media (max-width: 1199.98px) {
				.kpi-card { grid-column: span 4; }
				.section-actions { grid-column: span 12; }
				.section-shortcuts { grid-column: span 12; }
				.workspace-metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); }
				.snapshot-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
		}
		@media (max-width: 767.98px) {
				.kpi-card { grid-column: span 6; }
				.shortcut-grid { grid-template-columns: 1fr; }
				.workspace-metrics { grid-template-columns: 1fr; }
				.snapshot-grid { grid-template-columns: 1fr; }
				.inspection-approval-user-form {
						width: 100%;
				}
				.inspection-approval-user-form select {
						min-width: 100%;
				}
		}
</style>
@endsection

@section('content')
@php
		$visibleModules = collect($module_permissions ?? [])->filter(function ($module) {
				return !empty($module['visible']);
		})->values();
@endphp
<section class="users-list-wrapper">
		<div class="dashboard-grid">
				<div class="dashboard-card" style="grid-column: span 12;">
						<div class="dashboard-card-body">
								<div class="workspace-title">{{ $dashboard_mode_label ?? 'Workspace' }}</div>
								<p class="workspace-hint mb-0">{{ $dashboard_mode_hint ?? 'Dashboard view is rendered from your current permissions.' }}</p>
								@if(!empty($access_badges))
										<div class="workspace-badges">
												@foreach($access_badges as $badge)
														<span class="badge badge-light border">{{ $badge }}</span>
												@endforeach
										</div>
								@endif
								<div class="workspace-metrics">
										<div class="workspace-metric">
												<span class="workspace-metric-label">Visible Modules</span>
												<span class="workspace-metric-value">{{ $scope_summary['module_total'] ?? 0 }}</span>
										</div>
										<div class="workspace-metric">
												<span class="workspace-metric-label">Workflow Modules</span>
												<span class="workspace-metric-value">{{ $scope_summary['workflow_modules'] ?? 0 }}</span>
										</div>
										<div class="workspace-metric">
												<span class="workspace-metric-label">Financial Modules</span>
												<span class="workspace-metric-value">{{ $scope_summary['financial_modules'] ?? 0 }}</span>
										</div>
										<div class="workspace-metric">
												<span class="workspace-metric-label">Approval-Capable Modules</span>
												<span class="workspace-metric-value">{{ $scope_summary['approval_modules'] ?? 0 }}</span>
										</div>
								</div>
						</div>
				</div>

				<div class="dashboard-card" style="grid-column: span 12;">
						<div class="dashboard-card-head">
								<h4 class="dashboard-card-title">Workflow Overview</h4>
								<div class="scope-switch">
										<button type="button" class="scope-btn js-kpi-scope" data-scope="all">
												All ({{ $scope_summary['module_total'] ?? 0 }})
										</button>
										<button type="button" class="scope-btn js-kpi-scope" data-scope="workflow">
												Workflow ({{ $scope_summary['workflow_modules'] ?? 0 }})
										</button>
										<button type="button" class="scope-btn js-kpi-scope" data-scope="financial">
												Financial ({{ $scope_summary['financial_modules'] ?? 0 }})
										</button>
								</div>
								@if(!empty($show_jcf_states))
										<div>
												<span class="state-pill state-open">Open: {{ $jcf_states['open'] ?? 0 }}</span>
												<span class="state-pill state-ready">Ready: {{ $jcf_states['ready'] ?? 0 }}</span>
												<span class="state-pill state-completed">Completed: {{ $jcf_states['completed'] ?? 0 }}</span>
												<span class="state-pill state-canceled">Canceled: {{ $jcf_states['canceled'] ?? 0 }}</span>
										</div>
								@endif
						</div>
						<div class="dashboard-card-body">
								<div class="kpi-wrap">
										@forelse($kpi_cards ?? [] as $card)
												<a class="kpi-card kpi-card--{{ $card['tone'] ?? 'workflow' }}" href="{{ $card['url'] }}" data-kpi-scope="{{ $card['scope'] ?? 'workflow' }}">
														<span class="kpi-label">{{ $card['label'] }}</span>
														<span class="kpi-value">{{ $card['value'] }}</span>
														<span class="kpi-sub">{{ $card['sub'] }}</span>
												</a>
										@empty
												<div class="alert alert-light mb-0 w-100">No modules are available for your current role.</div>
										@endforelse
										<div class="alert alert-light mb-0 w-100 d-none" id="kpi-empty-state">No KPI cards match this scope.</div>
								</div>
						</div>
				</div>

				@if(!empty($has_financial_snapshot))
				<div class="dashboard-card section-snapshot">
						<div class="dashboard-card-head">
								<h4 class="dashboard-card-title">Financial Snapshot</h4>
								@if(!empty($can_access_financial_tab))
										<a href="{{ route('bank.dashboard') }}" class="btn btn-sm btn-primary">Open Financial Dashboard</a>
								@endif
						</div>
						<div class="dashboard-card-body">
								<div class="snapshot-grid">
										@if(isset($financial_snapshot['active_banks']) && $financial_snapshot['active_banks'] !== null)
												<div class="snapshot-item">
														<span class="snapshot-label">Active Banks</span>
														<span class="snapshot-value">{{ $financial_snapshot['active_banks'] }}</span>
												</div>
										@endif
										@if(isset($financial_snapshot['bank_balance']) && $financial_snapshot['bank_balance'] !== null)
												<div class="snapshot-item">
														<span class="snapshot-label">Total Bank Balance</span>
														<span class="snapshot-value">{{ number_format((float) $financial_snapshot['bank_balance'], 2, '.', ',') }}</span>
												</div>
										@endif
										@if(isset($financial_snapshot['pending_bank_transactions']) && $financial_snapshot['pending_bank_transactions'] !== null)
												<div class="snapshot-item">
														<span class="snapshot-label">Pending Bank Transactions</span>
														<span class="snapshot-value">{{ $financial_snapshot['pending_bank_transactions'] }}</span>
												</div>
										@endif
										@if(isset($financial_snapshot['pending_accountant_entries']) && $financial_snapshot['pending_accountant_entries'] !== null)
												<div class="snapshot-item">
														<span class="snapshot-label">Pending Accountant Entries</span>
														<span class="snapshot-value">{{ $financial_snapshot['pending_accountant_entries'] }}</span>
												</div>
										@endif
										@if(isset($financial_snapshot['pending_expenses']) && $financial_snapshot['pending_expenses'] !== null)
												<div class="snapshot-item">
														<span class="snapshot-label">Pending Expenses</span>
														<span class="snapshot-value">{{ $financial_snapshot['pending_expenses'] }}</span>
												</div>
										@endif
										@if(isset($financial_snapshot['pending_payments']) && $financial_snapshot['pending_payments'] !== null)
												<div class="snapshot-item">
														<span class="snapshot-label">Pending Payments</span>
														<span class="snapshot-value">{{ $financial_snapshot['pending_payments'] }}</span>
												</div>
										@endif
										@if(isset($financial_snapshot['due_invoices_count']) && $financial_snapshot['due_invoices_count'] !== null)
												<div class="snapshot-item">
														<span class="snapshot-label">Invoices with Due Amount</span>
														<span class="snapshot-value">{{ $financial_snapshot['due_invoices_count'] }}</span>
												</div>
										@endif
										@if(isset($financial_snapshot['due_invoices_amount']) && $financial_snapshot['due_invoices_amount'] !== null)
												<div class="snapshot-item">
														<span class="snapshot-label">Total Due Invoices Amount</span>
														<span class="snapshot-value">{{ number_format((float) $financial_snapshot['due_invoices_amount'], 2, '.', ',') }}</span>
												</div>
										@endif
										@if(isset($financial_snapshot['paid_expenses_count']) && $financial_snapshot['paid_expenses_count'] !== null)
												<div class="snapshot-item">
														<span class="snapshot-label">Paid Expenses</span>
														<span class="snapshot-value">{{ $financial_snapshot['paid_expenses_count'] }}</span>
												</div>
										@endif
										@if(isset($financial_snapshot['unpaid_expenses_count']) && $financial_snapshot['unpaid_expenses_count'] !== null)
												<div class="snapshot-item">
														<span class="snapshot-label">Unpaid Expenses</span>
														<span class="snapshot-value">{{ $financial_snapshot['unpaid_expenses_count'] }}</span>
												</div>
										@endif
								</div>
						</div>
				</div>
				@endif

				@if(!empty($inspection_approval_widget))
				<div class="dashboard-card section-inspection-approvals">
						<div class="dashboard-card-head">
								<h4 class="dashboard-card-title">Inspection Approvals Monitor</h4>
								<a href="{{ route('inspection.all') }}" class="btn btn-sm btn-outline-primary">Open Inspections</a>
						</div>
						<div class="dashboard-card-body">
								<div class="inspection-approval-toolbar">
										<div class="scope-switch">
												@foreach(($inspection_approval_widget['window_options'] ?? []) as $windowKey => $windowMeta)
														<a href="{{ route('dashboard.home', ['inspection_approval_window' => $windowKey, 'inspection_approval_user_id' => $inspection_approval_widget['selected_user_id'] ?? 0, 'inspection_approval_type' => $inspection_approval_widget['selected_type'] ?? 'all']) }}" class="scope-btn {{ ($inspection_approval_widget['selected_window'] ?? '1h') === $windowKey ? 'is-active' : '' }}">
																{{ $windowMeta['label'] ?? strtoupper($windowKey) }}
														</a>
												@endforeach
										</div>
										<form method="GET" class="inspection-approval-user-form">
												<input type="hidden" name="inspection_approval_window" value="{{ $inspection_approval_widget['selected_window'] ?? '1h' }}">
												<input type="hidden" name="inspection_approval_type" value="{{ $inspection_approval_widget['selected_type'] ?? 'all' }}">
												<label for="inspection-approval-user-id">Employee</label>
												<select id="inspection-approval-user-id" class="form-control form-control-sm" name="inspection_approval_user_id" onchange="this.form.submit()">
														<option value="0">All Employees</option>
														@foreach(($inspection_approval_widget['creators'] ?? []) as $creator)
																<option value="{{ $creator['id'] }}" @selected((int) ($inspection_approval_widget['selected_user_id'] ?? 0) === (int) $creator['id'])>
																		{{ $creator['name'] }}
																</option>
														@endforeach
												</select>
										</form>
								</div>

								<div class="scope-switch mb-1">
										<a href="{{ $inspection_approval_widget['all_type_url'] ?? route('dashboard.home') }}" class="scope-btn {{ ($inspection_approval_widget['selected_type'] ?? 'all') === 'all' ? 'is-active' : '' }}">
												All Types ({{ $inspection_approval_widget['approved_total_overall'] ?? 0 }})
										</a>
										@foreach(($inspection_approval_widget['by_type'] ?? []) as $typeItem)
												<a href="{{ $typeItem['url'] }}" class="scope-btn {{ !empty($typeItem['is_selected']) ? 'is-active' : '' }}">
														{{ $typeItem['module'] }} ({{ $typeItem['count'] }})
												</a>
										@endforeach
								</div>

								<div class="snapshot-grid mb-1">
										<div class="snapshot-item">
												<span class="snapshot-label">Approved Reports (Filtered)</span>
												<span class="snapshot-value">{{ $inspection_approval_widget['approved_total'] ?? 0 }}</span>
										</div>
										<div class="snapshot-item">
												<span class="snapshot-label">Window Start</span>
												<span class="snapshot-value">{{ optional($inspection_approval_widget['window_started_at'] ?? null)->format('d-m-Y H:i') ?: '-' }}</span>
										</div>
										<div class="snapshot-item">
												<span class="snapshot-label">Window End</span>
												<span class="snapshot-value">{{ optional($inspection_approval_widget['window_ends_at'] ?? null)->format('d-m-Y H:i') ?: '-' }}</span>
										</div>
								</div>

								<div class="row">
										<div class="col-xl-4 col-12 mb-1 mb-xl-0">
												<div class="font-weight-bold mb-1">Approvals By Report Type</div>
												@forelse(($inspection_approval_widget['by_type'] ?? []) as $typeRow)
														<a href="{{ $typeRow['url'] }}" class="queue-chip {{ !empty($typeRow['is_selected']) ? 'is-active' : '' }}">
																<span>{{ $typeRow['module'] }}</span>
																<span class="badge badge-primary">{{ $typeRow['count'] }}</span>
														</a>
												@empty
														<div class="alert alert-light mb-0">No approved reports in selected window.</div>
												@endforelse
										</div>
										<div class="col-xl-8 col-12">
												<div class="font-weight-bold mb-1">Latest Approved Reports</div>
												<div class="table-responsive">
														<table class="table table-striped recent-table mb-0">
																<thead>
																<tr>
																		<th>Code</th>
																		<th>Type</th>
																		<th>Employee</th>
																		<th>Approved</th>
																		<th>Actions</th>
																</tr>
																</thead>
																<tbody>
																@forelse(($inspection_approval_widget['recent'] ?? []) as $item)
																		<tr>
																				<td>
																						@if(!empty($item['can_open']))
																								<a class="recent-link" href="{{ $item['open_url'] }}">{{ $item['code'] }}</a>
																						@else
																								<span>{{ $item['code'] }}</span>
																						@endif
																				</td>
																				<td>{{ $item['module'] }}</td>
																				<td>{{ $item['creator'] }}</td>
																				<td>{{ optional($item['approved_at'])->diffForHumans() }}</td>
																				<td>
																						<div class="d-flex align-items-center" style="gap:8px;">
																								@if(!empty($item['can_open']))
																										<a href="{{ $item['open_url'] }}" class="btn btn-sm btn-outline-primary">Open</a>
																								@endif
																								@if(!empty($item['download_url']))
																										<a href="{{ $item['download_url'] }}" target="_blank" class="btn btn-sm btn-outline-secondary">PDF</a>
																								@endif
																						</div>
																				</td>
																		</tr>
																@empty
																		<tr>
																				<td colspan="5" class="text-center text-muted">No approved reports in selected window.</td>
																		</tr>
																@endforelse
																</tbody>
														</table>
												</div>
										</div>
								</div>
						</div>
				</div>
				@endif

				<div class="dashboard-card section-actions">
						<div class="dashboard-card-head">
								<h4 class="dashboard-card-title">Action Center</h4>
								<span class="badge badge-danger">{{ count($action_items ?? []) }}</span>
						</div>
						<div class="dashboard-card-body">
								@if(!empty($approval_queues))
										<div class="mb-2">
												@foreach($approval_queues as $queue)
														<div class="queue-chip">
																<div>
																		<strong>{{ $queue['label'] }}</strong>
																</div>
																<div class="d-flex align-items-center" style="gap: 8px;">
																		<span class="badge badge-{{ $queue['level'] }}">{{ $queue['count'] }}</span>
																		<a href="{{ $queue['url'] }}" class="btn btn-sm btn-{{ $queue['level'] }}">Open</a>
																</div>
														</div>
												@endforeach
										</div>
								@endif
								@if(!empty($action_items))
										@foreach($action_items as $action)
												<div class="action-row">
														<div>
																<div class="action-title">{{ $action['title'] }} <span class="badge badge-{{ $action['level'] }}">{{ $action['count'] }}</span></div>
																<p class="action-desc">{{ $action['description'] }}</p>
														</div>
														<a href="{{ $action['url'] }}" class="btn btn-sm btn-{{ $action['level'] }}">{{ $action['cta'] }}</a>
												</div>
										@endforeach
								@else
										<div class="alert alert-success mb-0">No pending actions right now.</div>
								@endif
						</div>
				</div>

				<div class="dashboard-card section-shortcuts">
						<div class="dashboard-card-head">
								<h4 class="dashboard-card-title">Quick Shortcuts</h4>
						</div>
						<div class="dashboard-card-body">
								<div class="shortcut-grid">
										@forelse($shortcuts as $shortcut)
												<a class="shortcut-btn" href="{{ $shortcut['url'] }}">
														<i class="{{ $shortcut['icon'] }}"></i>
														<span>{{ $shortcut['label'] }}</span>
												</a>
										@empty
												<div class="alert alert-light mb-0">No shortcuts available for current role.</div>
										@endforelse
								</div>
						</div>
				</div>

				@if((bool) (auth()->user()->is_super_admin ?? false))
				<div class="dashboard-card section-permissions">
						<div class="dashboard-card-head">
								<h4 class="dashboard-card-title">Permissions Matrix</h4>
								<span class="badge badge-info">{{ $visibleModules->count() }} module(s)</span>
						</div>
						<div class="dashboard-card-body">
								@if($visibleModules->isNotEmpty())
										<div class="table-responsive">
												<table class="table table-striped permission-table mb-0">
														<thead>
														<tr>
																<th>Module</th>
																<th>Scope</th>
																<th>View</th>
																<th>Create</th>
																<th>Approve</th>
																<th>Open</th>
														</tr>
														</thead>
														<tbody>
														@foreach($visibleModules as $module)
																<tr>
																		<td>{{ $module['label'] }}</td>
																		<td><span class="badge badge-light border">{{ ucfirst($module['scope']) }}</span></td>
																		<td>
																				@if(!empty($module['view']))
																						<i class="la la-check text-success permission-check"></i>
																				@else
																						<i class="la la-minus text-muted permission-check"></i>
																				@endif
																		</td>
																		<td>
																				@if(!empty($module['create']))
																						<i class="la la-check text-success permission-check"></i>
																				@else
																						<i class="la la-minus text-muted permission-check"></i>
																				@endif
																		</td>
																		<td>
																				@if(!empty($module['approve']))
																						<i class="la la-check text-success permission-check"></i>
																				@else
																						<i class="la la-minus text-muted permission-check"></i>
																				@endif
																		</td>
																		<td>
																				<a href="{{ $module['url'] }}" class="btn btn-sm btn-outline-primary">Open</a>
																		</td>
																</tr>
														@endforeach
														</tbody>
												</table>
										</div>
								@else
										<div class="alert alert-light mb-0">No module permissions are assigned for this account yet.</div>
								@endif
						</div>
				</div>
				@endif

				<div class="dashboard-card section-recent">
						<div class="dashboard-card-head">
								<h4 class="dashboard-card-title">Recent Activity</h4>
						</div>
						<div class="dashboard-card-body">
								<div class="table-responsive">
										<table class="table table-striped recent-table mb-0">
												<thead>
												<tr>
														<th>Module</th>
														<th>Code</th>
														<th>Details</th>
														<th>Created</th>
												</tr>
												</thead>
												<tbody>
												@forelse($recent_items as $item)
														<tr>
																<td>{{ $item['module'] }}</td>
																<td><a class="recent-link" href="{{ $item['url'] }}">{{ $item['code'] }}</a></td>
																<td>{{ $item['title'] }}</td>
																<td>{{ optional($item['time'])->diffForHumans() }}</td>
														</tr>
												@empty
														<tr>
																<td colspan="4" class="text-center text-muted">No recent activity yet.</td>
														</tr>
												@endforelse
												</tbody>
										</table>
								</div>
						</div>
				</div>
		</div>
</section>
<script>
		document.addEventListener('DOMContentLoaded', function () {
				const buttons = Array.from(document.querySelectorAll('.js-kpi-scope'));
				const cards = Array.from(document.querySelectorAll('[data-kpi-scope]'));
				const emptyState = document.getElementById('kpi-empty-state');
				const defaultScope = @json($default_scope ?? 'all');

				if (!buttons.length || !cards.length) {
						return;
				}

				const applyScope = function (scope) {
						let visibleCount = 0;

						cards.forEach(function (card) {
								const cardScope = card.getAttribute('data-kpi-scope') || 'workflow';
								const isVisible = scope === 'all' || scope === cardScope;
								card.classList.toggle('d-none', !isVisible);
								if (isVisible) {
										visibleCount += 1;
								}
						});

						buttons.forEach(function (button) {
								const isActive = button.getAttribute('data-scope') === scope;
								button.classList.toggle('is-active', isActive);
						});

						if (emptyState) {
								emptyState.classList.toggle('d-none', visibleCount > 0);
						}
				};

				buttons.forEach(function (button) {
						button.addEventListener('click', function () {
								applyScope(button.getAttribute('data-scope') || 'all');
						});
				});

				applyScope(defaultScope);
		});
</script>
@endsection
