@extends('layouts.app')

@section('content')
<section class="users-list-wrapper">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('accountant.update', $accountant->id) }}" id="accountant-entry-form">
                    @csrf
                    @method('PATCH')
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
                                <label>JCF Number (Optional)</label>
                                <select class="form-control" name="job_request_id">
                                    <option value="">No JCF</option>
                                    @foreach(($job_request_options ?? []) as $jobRequestId => $jobRequestLabel)
                                        <option value="{{ $jobRequestId }}" {{ (string)old('job_request_id', $accountant->job_request_id) === (string)$jobRequestId ? 'selected' : '' }}>
                                            {{ $jobRequestLabel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label>Invoice (Optional)</label>
                                <select class="form-control" name="invoice_id">
                                    <option value="">No invoice</option>
                                    @foreach(($invoice_options ?? []) as $invoiceId => $invoiceLabel)
                                        <option value="{{ $invoiceId }}" {{ (string)old('invoice_id', $accountant->invoice_id) === (string)$invoiceId ? 'selected' : '' }}>
                                            {{ $invoiceLabel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label>Payment (Optional)</label>
                                <select class="form-control" name="payment_id">
                                    <option value="">No payment</option>
                                    @foreach(($payment_options ?? []) as $paymentId => $paymentLabel)
                                        <option value="{{ $paymentId }}" {{ (string)old('payment_id', $accountant->payment_id) === (string)$paymentId ? 'selected' : '' }}>
                                            {{ $paymentLabel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Posting Date</label>
                                <input type="date" name="posting_date" class="form-control" value="{{ old('posting_date', $posting_date ?? date('Y-m-d')) }}">
                                @if(($posting_period_status ?? 'open') === 'closed')
                                    <small class="text-danger d-block mt-50">Selected period is closed.</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Reference No</label>
                                <input type="text" name="reference_no" class="form-control" value="{{ old('reference_no', $accountant->reference_no) }}" placeholder="Optional reference">
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Entry Type</label>
                                @php($entryTypeValue = old('entry_type', $accountant->entry_type))
                                <select class="form-control" name="entry_type" required>
                                    <option value="">Select type</option>
                                    <option value="accrual" {{ $entryTypeValue === 'accrual' ? 'selected' : '' }}>Accrual</option>
                                    <option value="cash" {{ $entryTypeValue === 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="adjustment" {{ $entryTypeValue === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                                    <option value="invoice" {{ $entryTypeValue === 'invoice' ? 'selected' : '' }}>Invoice (Legacy)</option>
                                    <option value="payment" {{ $entryTypeValue === 'payment' ? 'selected' : '' }}>Payment (Legacy)</option>
                                    <option value="expense" {{ $entryTypeValue === 'expense' ? 'selected' : '' }}>Expense</option>
                                    <option value="bank" {{ $entryTypeValue === 'bank' ? 'selected' : '' }}>Bank</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Currency</label>
                                <input type="text" name="currency" class="form-control" value="{{ old('currency', $accountant->currency ?: 'USD') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Total Debit</label>
                                <input type="text" id="entry-total-debit" class="form-control" value="{{ old('amount', number_format((float)$accountant->amount, 2, '.', '')) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Total Credit</label>
                                <input type="text" id="entry-total-credit" class="form-control" value="{{ old('amount', number_format((float)$accountant->amount, 2, '.', '')) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Legacy Amount (auto)</label>
                                <input type="text" name="amount" id="entry-legacy-amount" class="form-control" value="{{ old('amount', number_format((float)$accountant->amount, 2, '.', '')) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Status</label>
                                @php($statusValue = old('status', $accountant->status))
                                <select class="form-control" name="status">
                                    <option value="draft" {{ $statusValue === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending_approval" {{ $statusValue === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                                    <option value="approved" {{ $statusValue === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $statusValue === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card border-light">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Journal Lines</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-entry-line">Add Line</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0" id="entry-lines-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 42%;">Account</th>
                                            <th style="width: 16%;">Type</th>
                                            <th style="width: 16%;">Amount</th>
                                            <th style="width: 20%;">Note</th>
                                            <th style="width: 6%;">#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="entry-lines-body">
                                        @foreach(($initial_entry_lines ?? []) as $lineIndex => $entryLine)
                                            <tr data-line-row>
                                                <td>
                                                    <select class="form-control" name="entry_lines[{{ $lineIndex }}][chart_account_id]">
                                                        <option value="">Select account</option>
                                                        @foreach(($chart_account_options ?? []) as $accountId => $accountLabel)
                                                            <option value="{{ $accountId }}" {{ (string)($entryLine['chart_account_id'] ?? '') === (string)$accountId ? 'selected' : '' }}>{{ $accountLabel }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    @php($lineType = strtolower((string)($entryLine['line_type'] ?? ($lineIndex === 0 ? 'debit' : 'credit'))))
                                                    <select class="form-control" name="entry_lines[{{ $lineIndex }}][line_type]">
                                                        <option value="debit" {{ $lineType === 'debit' ? 'selected' : '' }}>Debit</option>
                                                        <option value="credit" {{ $lineType === 'credit' ? 'selected' : '' }}>Credit</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control js-line-amount" name="entry_lines[{{ $lineIndex }}][amount]" value="{{ $entryLine['amount'] ?? '' }}" placeholder="0.00">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="entry_lines[{{ $lineIndex }}][note]" value="{{ $entryLine['note'] ?? '' }}" placeholder="Optional">
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button type="button" class="btn btn-sm btn-outline-danger js-remove-line">&times;</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <small id="entry-lines-balance-note" class="d-block mt-1 text-muted">Entry is balanced.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Note</label>
                                <textarea class="form-control" name="note" rows="4">{{ old('note', $accountant->note) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is-posted" name="is_posted" value="1" {{ (string)old('is_posted', (int)$accountant->is_posted) === '1' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is-posted">Post this journal entry immediately</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex mt-1">
                        <button type="submit" class="btn btn-primary mr-1">Update Entry</button>
                        <a href="{{ route('accountant.show', $accountant->id) }}" class="btn btn-light border">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('ajax')
<script>
(function () {
    var form = document.getElementById('accountant-entry-form');
    if (!form) {
        return;
    }

    var body = document.getElementById('entry-lines-body');
    var addButton = document.getElementById('add-entry-line');
    var totalDebitInput = document.getElementById('entry-total-debit');
    var totalCreditInput = document.getElementById('entry-total-credit');
    var legacyAmountInput = document.getElementById('entry-legacy-amount');
    var balanceNote = document.getElementById('entry-lines-balance-note');
    var chartAccountOptions = @json($chart_account_options ?? []);

    var toNumber = function (value) {
        var normalized = String(value || '').replace(/[^0-9.\-]/g, '');
        var parsed = parseFloat(normalized);
        return Number.isFinite(parsed) ? parsed : 0;
    };

    var formatMoney = function (value) {
        return (Math.round((value + Number.EPSILON) * 100) / 100).toFixed(2);
    };

    var buildAccountOptionsHtml = function () {
        var html = '<option value="">Select account</option>';
        Object.keys(chartAccountOptions).forEach(function (accountId) {
            html += '<option value="' + accountId + '">' + String(chartAccountOptions[accountId]).replace(/"/g, '&quot;') + '</option>';
        });
        return html;
    };

    var renumberRows = function () {
        var rows = body.querySelectorAll('tr[data-line-row]');
        rows.forEach(function (row, index) {
            row.querySelectorAll('input,select').forEach(function (input) {
                var name = input.getAttribute('name') || '';
                input.setAttribute('name', name.replace(/entry_lines\[\d+\]/, 'entry_lines[' + index + ']'));
            });
        });
    };

    var recalcTotals = function () {
        var debit = 0;
        var credit = 0;
        var rows = body.querySelectorAll('tr[data-line-row]');

        rows.forEach(function (row) {
            var lineTypeInput = row.querySelector('select[name*="[line_type]"]');
            var amountInput = row.querySelector('input[name*="[amount]"]');
            var amount = toNumber(amountInput ? amountInput.value : 0);
            if (!lineTypeInput) {
                return;
            }

            if (lineTypeInput.value === 'debit') {
                debit += amount;
            } else {
                credit += amount;
            }
        });

        if (totalDebitInput) {
            totalDebitInput.value = formatMoney(debit);
        }
        if (totalCreditInput) {
            totalCreditInput.value = formatMoney(credit);
        }
        if (legacyAmountInput) {
            legacyAmountInput.value = formatMoney(debit);
        }

        var balanced = Math.abs(debit - credit) < 0.0001 && debit > 0;
        if (balanceNote) {
            balanceNote.textContent = balanced
                ? 'Entry is balanced.'
                : 'Entry is not balanced. Debit must equal credit and both must be > 0.';
            balanceNote.classList.toggle('text-success', balanced);
            balanceNote.classList.toggle('text-danger', !balanced);
        }
    };

    var appendRow = function () {
        var row = document.createElement('tr');
        row.setAttribute('data-line-row', '1');
        row.innerHTML = '' +
            '<td><select class="form-control" name="entry_lines[0][chart_account_id]">' + buildAccountOptionsHtml() + '</select></td>' +
            '<td><select class="form-control" name="entry_lines[0][line_type]"><option value="debit">Debit</option><option value="credit">Credit</option></select></td>' +
            '<td><input type="text" class="form-control js-line-amount" name="entry_lines[0][amount]" placeholder="0.00"></td>' +
            '<td><input type="text" class="form-control" name="entry_lines[0][note]" placeholder="Optional"></td>' +
            '<td class="text-center align-middle"><button type="button" class="btn btn-sm btn-outline-danger js-remove-line">&times;</button></td>';
        body.appendChild(row);
        renumberRows();
        recalcTotals();
    };

    body.addEventListener('click', function (event) {
        var removeButton = event.target.closest('.js-remove-line');
        if (!removeButton) {
            return;
        }

        var rows = body.querySelectorAll('tr[data-line-row]');
        if (rows.length <= 2) {
            return;
        }

        var row = removeButton.closest('tr[data-line-row]');
        if (row) {
            row.remove();
            renumberRows();
            recalcTotals();
        }
    });

    body.addEventListener('input', function (event) {
        if (event.target.matches('input[name*="[amount]"]')) {
            recalcTotals();
        }
    });

    body.addEventListener('change', function (event) {
        if (event.target.matches('select[name*="[line_type]"]')) {
            recalcTotals();
        }
    });

    if (addButton) {
        addButton.addEventListener('click', function () {
            appendRow();
        });
    }

    form.addEventListener('submit', function (event) {
        recalcTotals();
        var debit = toNumber(totalDebitInput ? totalDebitInput.value : 0);
        var credit = toNumber(totalCreditInput ? totalCreditInput.value : 0);
        if (!(debit > 0 && Math.abs(debit - credit) < 0.0001)) {
            event.preventDefault();
            if (balanceNote) {
                balanceNote.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    renumberRows();
    recalcTotals();
})();
</script>
@endsection
