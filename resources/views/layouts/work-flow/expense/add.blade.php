@extends('layouts.app')

@section('content')
<section class="users-list-wrapper">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('expense.store') }}">
                    @csrf
                    @if($errors->any())
                        <div id="expense-form-errors" class="alert alert-danger">
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
                                <label>Employee</label>
                                <select class="form-control {{ $errors->has('employee_id') ? 'is-invalid' : '' }}" name="employee_id" required>
                                    <option value="">Select employee</option>
                                    @foreach(($employee_options ?? []) as $employeeId => $employeeLabel)
                                        <option value="{{ $employeeId }}" {{ (string)old('employee_id') === (string)$employeeId ? 'selected' : '' }}>
                                            {{ $employeeLabel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label>JCF (Optional)</label>
                                <select class="form-control" name="job_request_id">
                                    <option value="">No JCF</option>
                                    @foreach(($job_request_options ?? []) as $jobRequestId => $jobRequestLabel)
                                        <option value="{{ $jobRequestId }}" {{ (string)old('job_request_id') === (string)$jobRequestId ? 'selected' : '' }}>
                                            {{ $jobRequestLabel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense_date ?? date('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Category</label>
                                <input type="text" name="category" class="form-control" value="{{ old('category', 'general') }}" placeholder="Travel, Accommodation, ...">
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" name="amount" class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" value="{{ old('amount') }}" placeholder="0.00" required>
                                @error('amount')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Currency</label>
                                <input type="text" id="expense-currency" name="currency" class="form-control {{ $errors->has('currency') ? 'is-invalid' : '' }}" value="{{ old('currency', 'USD') }}">
                                <small id="expense-currency-hint" class="text-muted d-block mt-50">No bank selected: you can set currency manually.</small>
                                @error('currency')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Status</label>
                                @php($statusValue = old('status', 'draft'))
                                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status">
                                    <option value="draft" {{ $statusValue === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending_approval" {{ $statusValue === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                                    <option value="approved" {{ $statusValue === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $statusValue === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label>Expense Account</label>
                                <select class="form-control {{ $errors->has('expense_account_id') ? 'is-invalid' : '' }}" name="expense_account_id">
                                    <option value="">Auto (default expense account)</option>
                                    @foreach(($expense_account_options ?? []) as $accountId => $accountLabel)
                                        <option value="{{ $accountId }}" {{ (string)old('expense_account_id') === (string)$accountId ? 'selected' : '' }}>
                                            {{ $accountLabel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('expense_account_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label>Bank Account (Optional)</label>
                                <select class="form-control {{ $errors->has('bank_account_id') ? 'is-invalid' : '' }}" id="expense-bank-account" name="bank_account_id">
                                    <option value="">No bank</option>
                                    @foreach(($bank_options ?? []) as $bankId => $bankLabel)
                                        <option
                                            value="{{ $bankId }}"
                                            data-currency="{{ $bank_currency_map[$bankId] ?? '' }}"
                                            {{ (string)old('bank_account_id') === (string)$bankId ? 'selected' : '' }}
                                        >
                                            {{ $bankLabel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_account_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label>Reference No.</label>
                                <input type="text" name="reference_no" class="form-control" value="{{ old('reference_no') }}">
                            </div>
                        </div>
                        <div class="col-md-8 col-12">
                            <div class="form-group">
                                <label>Note</label>
                                <textarea class="form-control" name="note" rows="3">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    @include('layouts.work-flow.expense._already-paid-fields')

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is-posted" name="is_posted" value="1" {{ old('is_posted', '0') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is-posted">Post this expense immediately</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary mr-1">Save Expense</button>
                        <a href="{{ route('expense.index') }}" class="btn btn-light border">Cancel</a>
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
        var errorBox = document.getElementById('expense-form-errors');
        var bankSelect = document.getElementById('expense-bank-account');
        var currencyInput = document.getElementById('expense-currency');
        var currencyHint = document.getElementById('expense-currency-hint');
        var amountInput = document.querySelector('input[name="amount"]');
        var alreadyPaidToggle = document.getElementById('expense-already-paid-toggle');
        var alreadyPaidFields = document.getElementById('expense-already-paid-fields');
        var paymentAmountInput = document.querySelector('input[name="payment_amount"]');
        var paymentDateInput = document.querySelector('input[name="payment_date"]');
        var paymentBankInput = document.getElementById('expense-payment-bank-account');

        if (errorBox) {
            errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        if (!bankSelect || !currencyInput) {
            return;
        }

        var toNumber = function (value) {
            var normalized = String(value || '').replace(/[^0-9.\-]/g, '');
            var parsed = parseFloat(normalized);
            return Number.isFinite(parsed) ? parsed : 0;
        };

        var formatMoney = function (value) {
            return (Math.round((value + Number.EPSILON) * 100) / 100).toFixed(2);
        };

        var syncCurrencyByBank = function () {
            var selectedOption = bankSelect.options[bankSelect.selectedIndex] || null;
            var selectedCurrency = selectedOption ? String(selectedOption.getAttribute('data-currency') || '').trim().toUpperCase() : '';

            if (bankSelect.value && selectedCurrency !== '') {
                currencyInput.value = selectedCurrency;
                currencyInput.readOnly = true;
                currencyInput.classList.add('bg-light');
                if (currencyHint) {
                    currencyHint.textContent = 'Currency locked to selected bank account: ' + selectedCurrency;
                }
                return;
            }

            currencyInput.readOnly = false;
            currencyInput.classList.remove('bg-light');
            if (String(currencyInput.value || '').trim() === '') {
                currencyInput.value = 'USD';
            }
            if (currencyHint) {
                currencyHint.textContent = 'No bank selected: you can set currency manually.';
            }
        };

        var toggleAlreadyPaidSection = function () {
            if (!alreadyPaidToggle || !alreadyPaidFields) {
                return;
            }

            var enabled = alreadyPaidToggle.checked;
            alreadyPaidFields.classList.toggle('d-none', !enabled);
            if (!enabled) {
                return;
            }

            if (paymentDateInput && String(paymentDateInput.value || '').trim() === '') {
                paymentDateInput.value = '{{ old('payment_date', $expense_date ?? now()->format('Y-m-d')) }}';
            }
            if (paymentAmountInput && String(paymentAmountInput.value || '').trim() === '' && amountInput) {
                paymentAmountInput.value = formatMoney(toNumber(amountInput.value));
            }
            if (paymentBankInput && !paymentBankInput.value && bankSelect.value) {
                paymentBankInput.value = bankSelect.value;
            }
        };

        bankSelect.addEventListener('change', syncCurrencyByBank);
        bankSelect.addEventListener('change', function () {
            if (alreadyPaidToggle && alreadyPaidToggle.checked && paymentBankInput && !paymentBankInput.value) {
                paymentBankInput.value = bankSelect.value;
            }
        });

        if (amountInput) {
            amountInput.addEventListener('input', function () {
                if (alreadyPaidToggle && alreadyPaidToggle.checked && paymentAmountInput && String(paymentAmountInput.value || '').trim() === '') {
                    paymentAmountInput.value = formatMoney(toNumber(amountInput.value));
                }
            });
        }

        if (alreadyPaidToggle) {
            alreadyPaidToggle.addEventListener('change', toggleAlreadyPaidSection);
        }

        if (paymentBankInput) {
            paymentBankInput.addEventListener('change', function () {
                if (!bankSelect.value && paymentBankInput.value) {
                    bankSelect.value = paymentBankInput.value;
                    syncCurrencyByBank();
                }
            });
        }

        syncCurrencyByBank();
        toggleAlreadyPaidSection();
    }());
</script>
@endsection
