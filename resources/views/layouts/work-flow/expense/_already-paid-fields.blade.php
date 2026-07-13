@php
    $existingPayment = $existing_payment ?? null;
    $alreadyPaidChecked = old('already_paid', $existingPayment ? 1 : 0);
    $paymentDateValue = old(
        'payment_date',
        $existingPayment && $existingPayment->payment_date
            ? \Carbon\Carbon::parse($existingPayment->payment_date)->format('Y-m-d')
            : ($expense_date ?? now()->format('Y-m-d'))
    );
    $paymentAmountValue = old(
        'payment_amount',
        $existingPayment ? number_format((float) $existingPayment->amount, 2, '.', '') : old('amount', '')
    );
    $paymentMethodValue = old('payment_method', $existingPayment->method ?? '');
    $paymentReferenceValue = old('payment_reference_no', $existingPayment->reference_no ?? old('reference_no', ''));
    $paymentStatusValue = old('payment_status', $existingPayment->status ?? 'pending');
    $paymentNoteValue = old('payment_note', $existingPayment->note ?? '');
    $paymentBankAccountId = old('payment_bank_account_id', $existingPayment->bank_account_id ?? old('bank_account_id', null));
@endphp

<div class="card border-cyan border-lighten-4 mt-1">
    <div class="card-content">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Already Paid?</label>
                            <div style="display: inline-block; float: right; margin-left: 10px;">
                                <input
                                    type="checkbox"
                                    class="switchery mr-1"
                                    id="expense-already-paid-toggle"
                                    name="already_paid"
                                    value="1"
                                    @checked($alreadyPaidChecked)
                                />
                            </div>
                            <p class="text-muted mb-0 mt-1">
                                Enable this to create or update one linked payment while saving this expense.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="expense-already-paid-fields" class="mt-2 {{ $alreadyPaidChecked ? '' : 'd-none' }}">
                <input type="hidden" name="existing_payment_id" value="{{ $existingPayment->id ?? '' }}">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label>Payment Date</label>
                            <input type="date" name="payment_date" class="form-control {{ $errors->has('payment_date') ? 'is-invalid' : '' }}" value="{{ $paymentDateValue }}">
                            @error('payment_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="text" name="payment_amount" class="form-control {{ $errors->has('payment_amount') ? 'is-invalid' : '' }}" value="{{ $paymentAmountValue }}" placeholder="0.00">
                            @error('payment_amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label>Method</label>
                            <input type="text" name="payment_method" class="form-control {{ $errors->has('payment_method') ? 'is-invalid' : '' }}" value="{{ $paymentMethodValue }}" placeholder="Bank Transfer / Cash">
                            @error('payment_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control {{ $errors->has('payment_status') ? 'is-invalid' : '' }}" name="payment_status">
                                <option value="pending" {{ $paymentStatusValue === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="received" {{ $paymentStatusValue === 'received' ? 'selected' : '' }}>Received</option>
                                <option value="approved" {{ $paymentStatusValue === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $paymentStatusValue === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label>Reference No.</label>
                            <input type="text" name="payment_reference_no" class="form-control {{ $errors->has('payment_reference_no') ? 'is-invalid' : '' }}" value="{{ $paymentReferenceValue }}" placeholder="TXN / Voucher">
                            @error('payment_reference_no')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label>Bank Account (Optional)</label>
                            <select class="form-control {{ $errors->has('payment_bank_account_id') ? 'is-invalid' : '' }}" name="payment_bank_account_id" id="expense-payment-bank-account">
                                <option value="">No bank</option>
                                @foreach(($bank_options ?? []) as $bankId => $bankLabel)
                                    <option value="{{ $bankId }}" {{ (string)$paymentBankAccountId === (string)$bankId ? 'selected' : '' }}>
                                        {{ $bankLabel }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_bank_account_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form-group mb-0">
                            <label>Payment Note</label>
                            <textarea class="form-control {{ $errors->has('payment_note') ? 'is-invalid' : '' }}" name="payment_note" rows="3" placeholder="Optional note">{{ $paymentNoteValue }}</textarea>
                            @error('payment_note')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
