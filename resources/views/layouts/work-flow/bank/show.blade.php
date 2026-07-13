@extends('layouts.app')

@section('content')
<section class="users-list-wrapper">
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
            <h4 class="card-title mb-0">Bank Account {{ $bank->code }}</h4>
            <div>
                @can('update', $bank)
                    <a href="{{ route('bank.edit', $bank->id) }}" class="btn btn-sm btn-info">Edit</a>
                @endcan
                @if(auth()->user()->can('viewAny', App\Models\WorkFlow\Bank::class) && (auth()->user()->hasPermission('financial', 'show') || auth()->user()->hasPermission('financial', 'all')))
                    <a href="{{ route('bank.dashboard') }}" class="btn btn-sm btn-secondary">Dashboard</a>
                @endif
                <a href="{{ route('bank.index') }}" class="btn btn-sm btn-light border">Back</a>
            </div>
        </div>
        <div class="card-content">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 col-12">
                        <div class="border rounded p-1">
                            <div class="text-muted">Current Balance</div>
                            <div class="h4 mb-0">{{ number_format((float)$bank->current_balance, 2, '.', '') }} {{ $bank->currency }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="border rounded p-1">
                            <div class="text-muted">Posted Inflow</div>
                            <div class="h5 mb-0 text-success">{{ number_format((float)$posted_in_total, 2, '.', '') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="border rounded p-1">
                            <div class="text-muted">Posted Outflow</div>
                            <div class="h5 mb-0 text-danger">{{ number_format((float)$posted_out_total, 2, '.', '') }}</div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th width="25%">Bank Name</th>
                            <td>{{ $bank->bank_name }}</td>
                        </tr>
                        <tr>
                            <th>Account Name</th>
                            <td>{{ $bank->account_name }}</td>
                        </tr>
                        <tr>
                            <th>Account Number</th>
                            <td>{{ $bank->account_number }}</td>
                        </tr>
                        <tr>
                            <th>IBAN</th>
                            <td>{{ $bank->iban ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Currency</th>
                            <td>{{ $bank->currency }}</td>
                        </tr>
                        <tr>
                            <th>Bank Chart Account</th>
                            <td>{{ optional($bank->chartAccount)->code ? (optional($bank->chartAccount)->code.' - '.optional($bank->chartAccount)->display_name) : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Opening Balance</th>
                            <td>{{ number_format((float)$bank->opening_balance, 2, '.', '') }}</td>
                        </tr>
                        <tr>
                            <th>Opening Date</th>
                            <td>{{ $bank->opening_date ? \Carbon\Carbon::parse($bank->opening_date)->format('d-m-Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ (int)$bank->is_active === 1 ? 'Active' : 'Inactive' }}</td>
                        </tr>
                        <tr>
                            <th>Pending Approval Transactions</th>
                            <td>{{ (int)$pending_count }}</td>
                        </tr>
                        <tr>
                            <th>Note</th>
                            <td>{{ $bank->note ?: '-' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                @can('update', $bank)
                    <hr>
                    <h5 class="mb-1">Add Bank Transaction</h5>
                    <form method="POST" action="{{ route('bank.transactions.store', $bank->id) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label>Direction</label>
                                    <select class="form-control" name="direction" required>
                                        <option value="in" {{ old('direction', 'in') === 'in' ? 'selected' : '' }}>In</option>
                                        <option value="out" {{ old('direction') === 'out' ? 'selected' : '' }}>Out</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control" name="category" required>
                                        @foreach(['deposit','withdraw','transfer','adjustment','fee','other'] as $cat)
                                            <option value="{{ $cat }}" {{ old('category', 'deposit') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', date('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending_approval" {{ old('status') === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                                        <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label>Reference</label>
                                    <input type="text" name="reference_no" class="form-control" value="{{ old('reference_no') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>Counter Account</label>
                                    <select class="form-control" name="counter_account_id">
                                        <option value="">Auto by direction</option>
                                        @foreach(($chart_account_options ?? []) as $accountId => $accountLabel)
                                            <option value="{{ $accountId }}" {{ (string)old('counter_account_id') === (string)$accountId ? 'selected' : '' }}>{{ $accountLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea class="form-control" name="note" rows="2">{{ old('note') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Add Transaction</button>
                    </form>
                @endcan

                <hr>
                <h5 class="mb-1">Transactions (Latest 100)</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                        <tr>
                            <th>Code</th>
                            <th>Date</th>
                            <th>Direction</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Counter Account</th>
                            <th>Payment</th>
                            <th>Accountant</th>
                            <th>Status</th>
                            <th>Reference</th>
                            <th>By</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $canApproveBankTx = auth()->user()->isSuperAdmin()
                                || auth()->user()->hasPermission('bank', 'approve')
                                || auth()->user()->hasPermission('bank', 'all')
                                || auth()->user()->hasPermission('accountant', 'approve')
                                || auth()->user()->hasPermission('accountant', 'all');
                        @endphp
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->code }}</td>
                                <td>{{ $transaction->transaction_date ? \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') : '-' }}</td>
                                <td>{{ strtoupper($transaction->direction) }}</td>
                                <td>{{ ucfirst($transaction->category) }}</td>
                                <td class="{{ $transaction->direction === 'out' ? 'text-danger' : 'text-success' }}">
                                    {{ number_format((float)$transaction->amount, 2, '.', '') }}
                                </td>
                                <td>
                                    @if($transaction->counterAccount)
                                        {{ $transaction->counterAccount->code }} - {{ $transaction->counterAccount->display_name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->payment)
                                        <a href="{{ route('payment.show', $transaction->payment->id) }}">{{ $transaction->payment->code }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->accountant)
                                        <a href="{{ route('accountant.show', $transaction->accountant->id) }}">{{ $transaction->accountant->code }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if((int)$transaction->is_posted === 1)
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($transaction->status === 'pending_approval')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($transaction->status === 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->reference_no ?: '-' }}</td>
                                <td>{{ optional(optional($transaction->user)->employee)->name ?: 'System' }}</td>
                                <td>
                                    @can('update', $bank)
                                        @if((int)$transaction->is_posted !== 1 && in_array((string)$transaction->status, ['draft', 'rejected'], true))
                                            <form method="POST" action="{{ route('bank.transactions.submitForApproval', [$bank->id, $transaction->id]) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Submit</button>
                                            </form>
                                        @endif

                                        @if($transaction->status === 'pending_approval' && $canApproveBankTx)
                                            <form method="POST" action="{{ route('bank.transactions.approve', [$bank->id, $transaction->id]) }}" class="d-inline js-confirm-action" data-confirm-title="Approve Transaction" data-confirm-text="Approve and post this bank transaction?" data-confirm-icon="question" data-confirm-confirm="Approve">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('bank.transactions.reject', [$bank->id, $transaction->id]) }}" class="d-inline js-confirm-action" data-confirm-title="Reject Transaction" data-confirm-text="Reject this bank transaction?" data-confirm-icon="warning" data-confirm-confirm="Reject" data-confirm-class="btn btn-warning">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">Reject</button>
                                            </form>
                                        @endif

                                        @if((int)$transaction->is_posted === 1 && $canApproveBankTx)
                                            <form method="POST" action="{{ route('bank.transactions.unpost', [$bank->id, $transaction->id]) }}" class="d-inline js-confirm-action" data-confirm-title="Unpost Transaction" data-confirm-text="Unpost this transaction and move it back to Draft?" data-confirm-icon="warning" data-confirm-confirm="Unpost" data-confirm-class="btn btn-dark">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-dark">Unpost</button>
                                            </form>
                                        @endif

                                        @if((int)$transaction->is_posted !== 1)
                                            <form method="POST" action="{{ route('bank.transactions.destroy', [$bank->id, $transaction->id]) }}" class="d-inline js-confirm-action" data-confirm-title="Delete Transaction" data-confirm-text="Delete this draft transaction?" data-confirm-icon="warning" data-confirm-confirm="Delete" data-confirm-class="btn btn-danger">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted">No transactions.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
