
@extends('layouts.app')

@section('header-bottom')
<style>
    .financial-dashboard .fd-hero {
        background: linear-gradient(135deg, #f8f9ff 0%, #eef4ff 100%);
        border: 1px solid #e4e9f7;
        border-radius: .65rem;
    }
    .financial-dashboard .fd-chip {
        display: inline-block;
        margin: .15rem .25rem .15rem 0;
        padding: .2rem .55rem;
        border-radius: 999px;
        background: #f4f6fb;
        border: 1px solid #e5e9f5;
        color: #59637b;
        font-size: .76rem;
        font-weight: 600;
    }
    .financial-dashboard .fd-kpi {
        border-left: 4px solid #5c6bc0;
        border-radius: .6rem;
    }
    .financial-dashboard .fd-kpi.success { border-left-color: #28a745; }
    .financial-dashboard .fd-kpi.warning { border-left-color: #ff9800; }
    .financial-dashboard .fd-kpi.danger { border-left-color: #e53935; }
    .financial-dashboard .fd-kpi.info { border-left-color: #29b6f6; }
    .financial-dashboard .fd-mini-title {
        font-size: .78rem;
        color: #79829a;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .02em;
    }
    .financial-dashboard .fd-action-btn {
        min-width: 170px;
        margin-right: .4rem;
        margin-bottom: .4rem;
    }
    .financial-dashboard .fd-flow-track {
        height: 8px;
        border-radius: 999px;
        background: #edf1fb;
        overflow: hidden;
    }
    .financial-dashboard .fd-flow-fill {
        height: 8px;
        border-radius: 999px;
    }
    .financial-dashboard .fd-table thead th {
        white-space: nowrap;
        font-size: .78rem;
        text-transform: uppercase;
        color: #6d768f;
    }
</style>
@endsection

@section('content')
@php
    $selectedBank = collect($bank_options ?? [])->firstWhere('id', (int)($filters['bank_account_id'] ?? 0));
    $maxDailyFlow = max(1.0, (float)collect($daily_flow ?? [])->max(function ($row) {
        return max((float)($row['in_total'] ?? 0), (float)($row['out_total'] ?? 0));
    }));
@endphp

<section class="users-list-wrapper financial-dashboard">
    <div class="card fd-hero">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <div>
                    <h4 class="mb-50">Financial Control Center</h4>
                    <div class="text-muted mb-75">Specialized view for banks, payments, expenses, and approvals workflow.</div>
                    <div>
                        <span class="fd-chip">Range: {{ $filters['from_date'] ?? '-' }} -> {{ $filters['to_date'] ?? '-' }}</span>
                        <span class="fd-chip">Bank: {{ $selectedBank ? ($selectedBank->code.' - '.$selectedBank->bank_name) : 'All Banks' }}</span>
                        <span class="fd-chip">Currency: {{ !empty($filters['currency']) ? $filters['currency'] : 'All' }}</span>
                        <span class="fd-chip">Status: {{ $filters['status'] ?? 'all' }}</span>
                        <span class="fd-chip">Direction: {{ $filters['direction'] ?? 'all' }}</span>
                        <span class="fd-chip">Source: {{ $filters['source_type'] ?? 'all' }}</span>
                    </div>
                </div>
                <div class="text-right mt-1 mt-lg-0">
                    <div class="fd-mini-title">Action Hub</div>
                    <div>
                        @if($can_create_bank ?? false)
                            <a href="{{ route('bank.create') }}" class="btn btn-sm btn-outline-primary fd-action-btn"><i class="la la-university mr-25"></i> New Bank Account</a>
                        @endif
                        @if($can_create_payment ?? false)
                            <a href="{{ route('payment.create') }}" class="btn btn-sm btn-outline-primary fd-action-btn"><i class="la la-credit-card mr-25"></i> New Payment</a>
                        @endif
                        @if($can_create_expense ?? false)
                            <a href="{{ route('expense.create') }}" class="btn btn-sm btn-outline-primary fd-action-btn"><i class="la la-money mr-25"></i> New Expense</a>
                        @endif
                        @if($can_create_accountant ?? false)
                            <a href="{{ route('accountant.create') }}" class="btn btn-sm btn-outline-primary fd-action-btn"><i class="la la-calculator mr-25"></i> New Accountant Entry</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h4 class="card-title mb-0">Live Filters</h4></div>
        <div class="card-body">
            <form method="GET" action="{{ route('bank.dashboard') }}" id="financial-dashboard-filters">
                <div class="row">
                    <div class="col-md-2 col-12"><div class="form-group mb-1"><label>From Date</label><input type="date" class="form-control" name="from_date" value="{{ $filters['from_date'] ?? '' }}"></div></div>
                    <div class="col-md-2 col-12"><div class="form-group mb-1"><label>To Date</label><input type="date" class="form-control" name="to_date" value="{{ $filters['to_date'] ?? '' }}"></div></div>
                    <div class="col-md-3 col-12"><div class="form-group mb-1"><label>Bank Account</label>
                        <select class="form-control js-fd-autosubmit" name="bank_account_id">
                            <option value="">All Banks</option>
                            @foreach(($bank_options ?? []) as $bankOption)
                                <option value="{{ $bankOption->id }}" {{ (string)($filters['bank_account_id'] ?? '') === (string)$bankOption->id ? 'selected' : '' }}>{{ $bankOption->code }} - {{ $bankOption->bank_name }} ({{ strtoupper((string)$bankOption->currency) }})</option>
                            @endforeach
                        </select>
                    </div></div>
                    <div class="col-md-2 col-12"><div class="form-group mb-1"><label>Currency</label>
                        <select class="form-control js-fd-autosubmit" name="currency">
                            <option value="">All</option>
                            @foreach(($currency_options ?? []) as $currency)
                                <option value="{{ $currency }}" {{ (string)($filters['currency'] ?? '') === (string)$currency ? 'selected' : '' }}>{{ $currency }}</option>
                            @endforeach
                        </select>
                    </div></div>
                    <div class="col-md-3 col-12"><div class="form-group mb-1"><label>Search</label><input type="text" class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Code / Ref / Bank / Note"></div></div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-12"><div class="form-group mb-1"><label>Status</label>
                        <select class="form-control js-fd-autosubmit" name="status">
                            <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>All</option>
                            <option value="posted" {{ ($filters['status'] ?? '') === 'posted' ? 'selected' : '' }}>Posted</option>
                            <option value="pending_approval" {{ ($filters['status'] ?? '') === 'pending_approval' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ ($filters['status'] ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div></div>
                    <div class="col-md-2 col-12"><div class="form-group mb-1"><label>Direction</label>
                        <select class="form-control js-fd-autosubmit" name="direction">
                            <option value="all" {{ ($filters['direction'] ?? 'all') === 'all' ? 'selected' : '' }}>All</option>
                            <option value="in" {{ ($filters['direction'] ?? '') === 'in' ? 'selected' : '' }}>Inflow</option>
                            <option value="out" {{ ($filters['direction'] ?? '') === 'out' ? 'selected' : '' }}>Outflow</option>
                        </select>
                    </div></div>
                    <div class="col-md-3 col-12"><div class="form-group mb-1"><label>Source</label>
                        <select class="form-control js-fd-autosubmit" name="source_type">
                            <option value="all" {{ ($filters['source_type'] ?? 'all') === 'all' ? 'selected' : '' }}>All Sources</option>
                            <option value="manual" {{ ($filters['source_type'] ?? '') === 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="payment_auto" {{ ($filters['source_type'] ?? '') === 'payment_auto' ? 'selected' : '' }}>Payment Auto</option>
                            <option value="expense_auto" {{ ($filters['source_type'] ?? '') === 'expense_auto' ? 'selected' : '' }}>Expense Auto</option>
                            <option value="expense_payment_auto" {{ ($filters['source_type'] ?? '') === 'expense_payment_auto' ? 'selected' : '' }}>Expense Payment Auto</option>
                        </select>
                    </div></div>
                    <div class="col-md-5 col-12 d-flex align-items-end">
                        <div class="mb-1"><button type="submit" class="btn btn-primary mr-50"><i class="la la-filter mr-25"></i> Apply</button><a href="{{ route('bank.dashboard') }}" class="btn btn-light border"><i class="la la-refresh mr-25"></i> Reset</a></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-2 col-md-4 col-12"><div class="card fd-kpi info"><div class="card-body"><div class="fd-mini-title">Banks In Scope</div><h4 class="mb-0">{{ (int)$total_banks }}</h4></div></div></div>
        <div class="col-xl-2 col-md-4 col-12"><div class="card fd-kpi"><div class="card-body"><div class="fd-mini-title">Active Banks</div><h4 class="mb-0">{{ (int)$active_banks }}</h4></div></div></div>
        <div class="col-xl-2 col-md-4 col-12"><div class="card fd-kpi warning"><div class="card-body"><div class="fd-mini-title">Filtered Transactions</div><h4 class="mb-0">{{ (int)$filtered_transactions_count }}</h4></div></div></div>
        <div class="col-xl-2 col-md-4 col-12"><div class="card fd-kpi danger"><div class="card-body"><div class="fd-mini-title">Pending Approvals</div><h4 class="mb-0">{{ (int)$pending_transactions }}</h4></div></div></div>
        <div class="col-xl-2 col-md-4 col-12"><div class="card fd-kpi success"><div class="card-body"><div class="fd-mini-title">Posted</div><h4 class="mb-0">{{ (int)$posted_transactions }}</h4></div></div></div>
        <div class="col-xl-2 col-md-4 col-12"><div class="card fd-kpi"><div class="card-body"><div class="fd-mini-title">Average Ticket</div><h4 class="mb-0">{{ number_format((float)$average_posted_amount, 2, '.', ',') }}</h4></div></div></div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Cashflow Analysis</h4></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-50"><span class="text-muted">Posted Inflow</span><strong class="text-success">{{ number_format((float)$total_inflow, 2, '.', ',') }}</strong></div>
                    <div class="d-flex justify-content-between mb-50"><span class="text-muted">Posted Outflow</span><strong class="text-danger">{{ number_format((float)$total_outflow, 2, '.', ',') }}</strong></div>
                    <div class="d-flex justify-content-between mb-75"><span class="text-muted">Net</span><strong class="{{ (float)$net_flow >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format((float)$net_flow, 2, '.', ',') }}</strong></div>
                    @php
                        $flowBase = max(1.0, (float)max((float)$total_inflow, (float)$total_outflow));
                        $inWidth = min(100, (int)round(((float)$total_inflow / $flowBase) * 100));
                        $outWidth = min(100, (int)round(((float)$total_outflow / $flowBase) * 100));
                    @endphp
                    <div class="mb-50"><div class="d-flex justify-content-between"><small>Inflow Weight</small><small>{{ $inWidth }}%</small></div><div class="fd-flow-track"><div class="fd-flow-fill bg-success" style="width: {{ $inWidth }}%"></div></div></div>
                    <div><div class="d-flex justify-content-between"><small>Outflow Weight</small><small>{{ $outWidth }}%</small></div><div class="fd-flow-track"><div class="fd-flow-fill bg-danger" style="width: {{ $outWidth }}%"></div></div></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Approval Queues</h4></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-75"><span><i class="la la-university text-primary mr-25"></i>Bank Transactions</span><a href="{{ route('bank.dashboard', array_merge($filters ?? [], ['status' => 'pending_approval'])) }}" class="badge badge-warning">{{ $pending_queues['bank'] ?? '-' }}</a></div>
                    @if($can_view_payments ?? false)
                        <div class="d-flex justify-content-between align-items-center mb-75"><span><i class="la la-credit-card text-primary mr-25"></i>Payments</span><a href="{{ route('payment.index') }}" class="badge badge-warning">{{ $pending_queues['payment'] ?? '-' }}</a></div>
                    @endif
                    @if($can_view_expenses ?? false)
                        <div class="d-flex justify-content-between align-items-center mb-75"><span><i class="la la-money text-primary mr-25"></i>Expenses</span><a href="{{ route('expense.index', ['entry_status' => 'pending_approval']) }}" class="badge badge-warning">{{ $pending_queues['expense'] ?? '-' }}</a></div>
                    @endif
                    @if($can_view_accountant ?? false)
                        <div class="d-flex justify-content-between align-items-center"><span><i class="la la-calculator text-primary mr-25"></i>Accountant Entries</span><a href="{{ route('accountant.index', ['entry_status' => 'pending_approval']) }}" class="badge badge-warning">{{ $pending_queues['accountant'] ?? '-' }}</a></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Scope Highlights</h4></div>
                <div class="card-body">
                    <div class="mb-75">
                        <div class="text-muted">Largest Transaction</div>
                        @if($largest_transaction)
                            <div class="font-weight-bold">{{ $largest_transaction->code }}</div>
                            <div class="small {{ $largest_transaction->direction === 'out' ? 'text-danger' : 'text-success' }}">{{ number_format((float)$largest_transaction->amount, 2, '.', ',') }} {{ strtoupper((string)optional($largest_transaction->bank)->currency) }}</div>
                        @else
                            <div class="small text-muted">No transactions in current scope.</div>
                        @endif
                    </div>
                    <div class="mb-75"><div class="text-muted">Latest Posted Date</div><div class="font-weight-bold">{{ !empty($latest_posted_date) ? \Carbon\Carbon::parse($latest_posted_date)->format('d-m-Y') : '-' }}</div></div>
                    <div>
                        <div class="text-muted">Due Invoices</div>
                        @if($can_view_invoices ?? false)
                            <div class="font-weight-bold">{{ (int)$due_invoices_count }} invoices</div>
                            <div class="small text-danger">{{ number_format((float)$due_invoices_amount, 2, '.', ',') }}</div>
                        @else
                            <div class="small text-muted">No permission to view invoice data.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center"><h4 class="card-title mb-0">Filtered Bank Transactions</h4><a href="{{ route('bank.index') }}" class="btn btn-sm btn-light border">Open Banks</a></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered mb-0 fd-table">
                            <thead><tr><th>Date</th><th>Code</th><th>Bank</th><th>Direction</th><th>Category</th><th>Source</th><th class="text-right">Amount</th><th>Status</th><th>Open</th></tr></thead>
                            <tbody>
                                @forelse($recent_transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_date ? \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') : '-' }}</td>
                                        <td class="font-weight-bold">{{ $transaction->code }}</td>
                                        <td>{{ optional($transaction->bank)->code ?: '-' }}</td>
                                        <td>@if($transaction->direction === 'out')<span class="badge badge-danger">Out</span>@else<span class="badge badge-success">In</span>@endif</td>
                                        <td>{{ \Illuminate\Support\Str::headline((string)$transaction->category) }}</td>
                                        <td>{{ \Illuminate\Support\Str::headline((string)$transaction->source_type) }}</td>
                                        <td class="text-right {{ $transaction->direction === 'out' ? 'text-danger' : 'text-success' }}">{{ number_format((float)$transaction->amount, 2, '.', ',') }}</td>
                                        <td>
                                            @if((int)$transaction->is_posted === 1)
                                                <span class="badge badge-success">Posted</span>
                                            @elseif($transaction->status === 'pending_approval')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($transaction->status === 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($transaction->status === 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @else
                                                <span class="badge badge-secondary">Draft</span>
                                            @endif
                                        </td>
                                        <td>@if(optional($transaction->bank)->id)<a href="{{ route('bank.show', $transaction->bank->id) }}" class="btn btn-sm btn-outline-primary">Bank</a>@else-@endif</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="text-center text-muted">No transactions matched current filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Daily Posted Flow</h4></div>
                <div class="card-body">
                    @forelse(collect($daily_flow)->take(-10) as $row)
                        @php
                            $inPct = min(100, (int)round((((float)$row['in_total']) / $maxDailyFlow) * 100));
                            $outPct = min(100, (int)round((((float)$row['out_total']) / $maxDailyFlow) * 100));
                        @endphp
                        <div class="mb-1">
                            <div class="d-flex justify-content-between"><small>{{ \Carbon\Carbon::parse($row['date'])->format('d M') }}</small><small class="{{ (float)$row['net_total'] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format((float)$row['net_total'], 2, '.', ',') }}</small></div>
                            <div class="mb-25"><div class="fd-flow-track"><div class="fd-flow-fill bg-success" style="width: {{ $inPct }}%"></div></div></div>
                            <div><div class="fd-flow-track"><div class="fd-flow-fill bg-danger" style="width: {{ $outPct }}%"></div></div></div>
                        </div>
                    @empty
                        <div class="text-muted">No posted daily flow in selected scope.</div>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Balances by Currency</h4></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0 fd-table">
                            <thead><tr><th>Currency</th><th class="text-right">Balance</th></tr></thead>
                            <tbody>
                                @forelse($balances_by_currency as $row)
                                    <tr><td>{{ strtoupper((string)$row->currency) ?: '-' }}</td><td class="text-right">{{ number_format((float)$row->total_balance, 2, '.', ',') }}</td></tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted">No balances.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Bank Accounts Snapshot</h4></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover mb-0 fd-table">
                            <thead><tr><th>Bank</th><th>Status</th><th class="text-right">Balance</th><th class="text-center">Pending</th><th class="text-center">Posted</th><th>Open</th></tr></thead>
                            <tbody>
                                @forelse($bank_accounts_snapshot as $bankAccount)
                                    <tr>
                                        <td>{{ $bankAccount->code }} - {{ $bankAccount->bank_name }}</td>
                                        <td>@if((int)$bankAccount->is_active === 1)<span class="badge badge-success">Active</span>@else<span class="badge badge-secondary">Inactive</span>@endif</td>
                                        <td class="text-right">{{ number_format((float)$bankAccount->current_balance, 2, '.', ',') }} {{ strtoupper((string)$bankAccount->currency) }}</td>
                                        <td class="text-center">{{ (int)$bankAccount->pending_transaction_count }}</td>
                                        <td class="text-center">{{ (int)$bankAccount->posted_transaction_count }}</td>
                                        <td><a href="{{ route('bank.show', $bankAccount->id) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">No bank accounts in selected scope.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Due Invoices (Scope-Aware)</h4></div>
                <div class="card-body p-0">
                    @if(!($can_view_invoices ?? false))
                        <div class="p-1"><div class="alert alert-light mb-0">You do not have permission to view invoice-related data.</div></div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover mb-0 fd-table">
                                <thead><tr><th>Invoice</th><th>JCF</th><th>Client/Supplier</th><th class="text-right">Due</th></tr></thead>
                                <tbody>
                                    @forelse($due_invoices as $invoice)
                                        <tr><td><a href="{{ route('invoice.show', $invoice->invoice_id) }}">{{ $invoice->invoice_code }}</a></td><td>{{ $invoice->job_request_code ?: '-' }}</td><td>{{ $invoice->client_name ?: ($invoice->supplier_name ?: '-') }}</td><td class="text-right text-danger">{{ number_format((float)$invoice->due_amount, 2, '.', ',') }} {{ strtoupper((string)$invoice->invoice_currency) }}</td></tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted">No due invoices matched current scope.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('ajax')
<script>
    (function () {
        var form = document.getElementById('financial-dashboard-filters');
        if (!form) {
            return;
        }
        var autoInputs = form.querySelectorAll('.js-fd-autosubmit');
        autoInputs.forEach(function (input) {
            input.addEventListener('change', function () {
                form.submit();
            });
        });
    }());
</script>
@endsection
