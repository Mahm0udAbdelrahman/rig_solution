@php
    $existingPayment = $existing_payment ?? null;
    $linkedPaymentCount = (int) ($linked_payment_count ?? 0);
    $alreadyPaidChecked = old('already_paid', $existingPayment ? 1 : 0);
    $paymentDateValue = old(
        'payment_date',
        $existingPayment && $existingPayment->payment_date
            ? \Carbon\Carbon::parse($existingPayment->payment_date)->format('Y-m-d')
            : now()->format('Y-m-d')
    );
    $paymentAmountValue = old(
        'payment_amount',
        $existingPayment ? number_format((float) $existingPayment->amount, 2, '.', '') : ''
    );
    $paymentMethodValue = old('payment_method', $existingPayment->method ?? '');
    $paymentReferenceValue = old('payment_reference_no', $existingPayment->reference_no ?? '');
    $paymentStatusValue = old('payment_status', $existingPayment->status ?? 'received');
    $paymentNoteValue = old('payment_note', $existingPayment->note ?? '');
    $paymentBankAccountId = old('payment_bank_account_id', $existingPayment->bank_account_id ?? null);
@endphp

<div class="card border-cyan border-lighten-4">
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
                                    id="already-paid-toggle"
                                    name="already_paid"
                                    value="1"
                                    @checked($alreadyPaidChecked)
                                />
                            </div>
                            <p class="text-muted mb-0 mt-1">
                                Enable this to create or update one linked payment with the invoice save action.
                            </p>
                            @if($linkedPaymentCount > 0)
                            <p class="text-muted mb-0 mt-1">
                                Existing linked payments are not deleted automatically if this switch is turned off.
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($linkedPaymentCount > 1)
            <div class="alert alert-warning mt-1 mb-0">
                This invoice already has multiple linked payments. The inline form below edits the first linked payment only.
            </div>
            @endif

            <div id="already-paid-fields" class="mt-2 {{ $alreadyPaidChecked ? '' : 'd-none' }}">
                <input type="hidden" name="existing_payment_id" value="{{ $existingPayment->id ?? '' }}">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label>Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ $paymentDateValue }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="text" name="payment_amount" class="form-control" value="{{ $paymentAmountValue }}" placeholder="0.00">
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label>Method</label>
                            <input type="text" name="payment_method" class="form-control" value="{{ $paymentMethodValue }}" placeholder="Bank Transfer / Cash">
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="payment_status">
                                <option value="pending" {{ $paymentStatusValue === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="received" {{ $paymentStatusValue === 'received' ? 'selected' : '' }}>Received</option>
                                <option value="approved" {{ $paymentStatusValue === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $paymentStatusValue === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label>Reference No.</label>
                            <input type="text" name="payment_reference_no" class="form-control" value="{{ $paymentReferenceValue }}" placeholder="TXN / Voucher">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label>Bank Account (Optional)</label>
                            <select class="form-control" name="payment_bank_account_id">
                                <option value="">No bank</option>
                                @foreach(($bank_options ?? []) as $bankId => $bankLabel)
                                    <option value="{{ $bankId }}" {{ (string)$paymentBankAccountId === (string)$bankId ? 'selected' : '' }}>
                                        {{ $bankLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form-group mb-0">
                            <label>Payment Note</label>
                            <textarea class="form-control" name="payment_note" rows="3" placeholder="Optional note">{{ $paymentNoteValue }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
