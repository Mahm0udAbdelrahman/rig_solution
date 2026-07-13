@extends('layouts.app')

@section('content')
<section class="users-list-wrapper">
    @php
        $canManageExpensePayment = auth()->user()->can('update', $expense) && auth()->user()->can('create', App\Models\WorkFlow\Payment::class);
        $existingPayment = $expense->payment;
        $quickPaymentDate = old(
            'payment_date',
            $existingPayment && $existingPayment->payment_date
                ? \Carbon\Carbon::parse($existingPayment->payment_date)->format('Y-m-d')
                : ($expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') : now()->format('Y-m-d'))
        );
        $quickPaymentAmount = old(
            'payment_amount',
            $existingPayment
                ? number_format((float)$existingPayment->amount, 2, '.', '')
                : number_format((float)$expense->amount, 2, '.', '')
        );
        $quickPaymentMethod = old('payment_method', $existingPayment->method ?? '');
        $quickPaymentReference = old('payment_reference_no', $existingPayment->reference_no ?? ($expense->reference_no ?: $expense->code));
        $quickPaymentStatus = old(
            'payment_status',
            $existingPayment->status ?? ((int)$expense->is_posted === 1 ? 'approved' : 'pending')
        );
        $quickPaymentBankId = old('payment_bank_account_id', $existingPayment->bank_account_id ?? $expense->bank_account_id);
        $quickPaymentNote = old(
            'payment_note',
            $existingPayment->note ?? ('Linked from Expense '.$expense->code)
        );
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
            <h4 class="card-title mb-0">Expense {{ $expense->code }}</h4>
            <div>
                @if($canManageExpensePayment)
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#expense-quick-payment-modal">
                        {{ $existingPayment ? 'Update Payment' : 'Add Payment' }}
                    </button>
                @endif
                @can('update', $expense)
                    <a href="{{ route('expense.edit', $expense->id) }}" class="btn btn-sm btn-info">Edit</a>
                @endcan
                <a href="{{ route('expense.index') }}" class="btn btn-sm btn-light border">Back</a>
            </div>
        </div>
        <div class="card-content">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th width="25%">Code</th>
                            <td>{{ $expense->code }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('d-m-Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Employee</th>
                            <td>{{ optional($expense->employee)->name ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>JCF</th>
                            <td>
                                @if($expense->jobRequest)
                                    <a href="{{ route('jobRequest.show', $expense->jobRequest->id) }}">{{ $expense->jobRequest->code }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $expense->category ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>{{ number_format((float)$expense->amount, 2, '.', '') }} {{ optional($expense->bank)->currency ?: ($expense->currency ?: 'USD') }}</td>
                        </tr>
                        <tr>
                            <th>Expense Account</th>
                            <td>{{ optional($expense->expenseAccount)->code ? (optional($expense->expenseAccount)->code.' - '.optional($expense->expenseAccount)->display_name) : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bank</th>
                            <td>
                                @if($expense->bank)
                                    <a href="{{ route('bank.show', $expense->bank->id) }}">{{ $expense->bank->code }} - {{ $expense->bank->bank_name }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Bank Transaction</th>
                            <td>{{ optional($expense->bankTransaction)->code ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Payment</th>
                            <td>
                                @if($expense->payment)
                                    <a href="{{ route('payment.show', $expense->payment->id) }}">{{ $expense->payment->code }}</a>
                                    @if($canManageExpensePayment)
                                        <button type="button" class="btn btn-sm btn-outline-primary ml-1" data-toggle="modal" data-target="#expense-quick-payment-modal">Update Here</button>
                                    @endif
                                @else
                                    <span class="text-muted">No linked payment</span>
                                    @if($canManageExpensePayment)
                                        <button type="button" class="btn btn-sm btn-outline-primary ml-1" data-toggle="modal" data-target="#expense-quick-payment-modal">Add Now</button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Accountant Entry</th>
                            <td>
                                @if($expense->accountant)
                                    <a href="{{ route('accountant.show', $expense->accountant->id) }}">{{ $expense->accountant->code }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Reference No.</th>
                            <td>{{ $expense->reference_no ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if((int)$expense->is_posted === 1)
                                    <span class="badge badge-success">Approved</span>
                                @elseif($expense->status === 'pending_approval')
                                    <span class="badge badge-warning">Pending Approval</span>
                                @elseif($expense->status === 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>By</th>
                            <td>{{ optional(optional($expense->user)->employee)->name ?: 'System' }}</td>
                        </tr>
                        <tr>
                            <th>Approved By</th>
                            <td>{{ optional(optional($expense->approvedBy)->employee)->name ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Note</th>
                            <td>{{ $expense->note ?: '-' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                @php
                    $canApproveExpense = auth()->user()->isSuperAdmin()
                        || auth()->user()->hasPermission('expense', 'approve')
                        || auth()->user()->hasPermission('expense', 'all')
                        || auth()->user()->hasPermission('accountant', 'approve')
                        || auth()->user()->hasPermission('accountant', 'all')
                        || auth()->user()->hasPermission('bank', 'approve')
                        || auth()->user()->hasPermission('bank', 'all');
                @endphp

                @can('update', $expense)
                    <div class="mt-2">
                        @if((int)$expense->is_posted !== 1 && in_array((string)$expense->status, ['draft', 'rejected'], true))
                            <form method="POST" action="{{ route('expense.submitForApproval', $expense->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">Submit For Approval</button>
                            </form>
                        @endif

                        @if((string)$expense->status === 'pending_approval' && $canApproveExpense)
                            <form method="POST" action="{{ route('expense.approve', $expense->id) }}" class="d-inline js-confirm-action" data-confirm-title="Approve Expense" data-confirm-text="Approve and post this expense?" data-confirm-icon="question" data-confirm-confirm="Approve">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('expense.reject', $expense->id) }}" class="d-inline js-confirm-action" data-confirm-title="Reject Expense" data-confirm-text="Reject this expense?" data-confirm-icon="warning" data-confirm-confirm="Reject" data-confirm-class="btn btn-warning">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">Reject</button>
                            </form>
                        @endif

                        @if((int)$expense->is_posted === 1 && $canApproveExpense)
                            <form method="POST" action="{{ route('expense.unpost', $expense->id) }}" class="d-inline js-confirm-action" data-confirm-title="Unpost Expense" data-confirm-text="Unpost this expense and move it back to Draft?" data-confirm-icon="warning" data-confirm-confirm="Unpost" data-confirm-class="btn btn-dark">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-dark">Unpost</button>
                            </form>
                        @endif
                    </div>
                @endcan
            </div>
        </div>
    </div>

    @if($canManageExpensePayment)
        <div class="modal fade" id="expense-quick-payment-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('expense.quickPayment.store', $expense->id) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $existingPayment ? 'Update Linked Payment' : 'Add Payment To Expense' }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="existing_payment_id" value="{{ $existingPayment->id ?? '' }}">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label>Payment Date</label>
                                        <input type="date" name="payment_date" class="form-control {{ $errors->has('payment_date') ? 'is-invalid' : '' }}" value="{{ $quickPaymentDate }}">
                                        @error('payment_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" name="payment_amount" class="form-control {{ $errors->has('payment_amount') ? 'is-invalid' : '' }}" value="{{ $quickPaymentAmount }}" placeholder="0.00" required>
                                        @error('payment_amount')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control {{ $errors->has('payment_status') ? 'is-invalid' : '' }}" name="payment_status">
                                            <option value="pending" {{ $quickPaymentStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="received" {{ $quickPaymentStatus === 'received' ? 'selected' : '' }}>Received</option>
                                            <option value="approved" {{ $quickPaymentStatus === 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ $quickPaymentStatus === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        @error('payment_status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        @if((int)$expense->is_posted === 1)
                                            <small class="text-muted d-block mt-50">Posted expense enforces approved/received payment status.</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label>Method</label>
                                        <input type="text" name="payment_method" class="form-control {{ $errors->has('payment_method') ? 'is-invalid' : '' }}" value="{{ $quickPaymentMethod }}" placeholder="Bank Transfer / Cash">
                                        @error('payment_method')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label>Reference No.</label>
                                        <input type="text" name="payment_reference_no" class="form-control {{ $errors->has('payment_reference_no') ? 'is-invalid' : '' }}" value="{{ $quickPaymentReference }}" placeholder="TXN / Voucher">
                                        @error('payment_reference_no')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label>Bank Account (Optional)</label>
                                        <select class="form-control {{ $errors->has('payment_bank_account_id') ? 'is-invalid' : '' }}" name="payment_bank_account_id">
                                            <option value="">No bank</option>
                                            @foreach(($bank_options ?? []) as $bankId => $bankLabel)
                                                <option value="{{ $bankId }}" {{ (string)$quickPaymentBankId === (string)$bankId ? 'selected' : '' }}>
                                                    {{ $bankLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('payment_bank_account_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label>Payment Note</label>
                                <textarea class="form-control {{ $errors->has('payment_note') ? 'is-invalid' : '' }}" name="payment_note" rows="3" placeholder="Optional note">{{ $quickPaymentNote }}</textarea>
                                @error('payment_note')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light border" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">{{ $existingPayment ? 'Update Payment' : 'Add Payment' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</section>
@endsection

@section('ajax')
<script>
    (function () {
        var hasErrors = {{ $errors->any() ? 'true' : 'false' }};
        if (hasErrors) {
            var modal = document.getElementById('expense-quick-payment-modal');
            if (modal && typeof window.jQuery !== 'undefined') {
                window.jQuery(modal).modal('show');
            }
        }
    }());
</script>
@endsection
