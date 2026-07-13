@extends('layouts.app')

@include('layouts.styles.datatables')

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
                    @if ($finances == 0)
								@include('layouts.repeated.nodata', ['route' => 'finance'])
						@else
								<div class="card">
										<div class="card-content">
												<div class="card-body">
														<div class="table-responsive">
																<table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping without">
																		<thead>
																				<tr>
																					<th>INVOICE NO.</th>
																					<th>CLIENT</th>
																					<th>Department</th>
																					<th>JCF NO.</th>
																					<th>Invoice Date</th>
																					<th>Invoice Currency</th>
																					<th>Sub Total</th>
                                                                                    <th>Total Amount</th>
                                                                                    <th>Receipt Date</th>
                                                                                    <th>Outstand Days</th>
                                                                                    <th>Paid Amount</th>
                                                                                    <th>Currency</th>
                                                                                    <th>Rate</th>
                                                                                    <th>Method</th>
                                                                                    <th>Method Ref.</th>
                                                                                    <th>Method Amount</th>
                                                                                    <th>Collect Date</th>
                                                                                    <th>Outstand Amount</th>
                                                                                    <th>Status</th>
                                                                                    <th>Vat</th>
                                                                                    <th>Hold. Tax</th>
																					
																				</tr>
																		</thead>
																</table>
														</div>
												</div>
										</div>
								</div>
					@endif
				</div>
		</section>
@endsection


@include('layouts.scripts.datatables', ['route' => 'finance', 'columns' => [
    'invoice_no',           // INVOICE NO.
    'client',               // CLIENT
    'department',           // Department
    'jcf_no',               // JCF NO.
    'invoice_date',         // Invoice Date
    'invoice_currency',     // $
    'sub_total',            // Sub Total
    'total_amount',         // Total Amount
    'receipt_date',         // Receipt Date
    'outstand_days',        // Outstand Days
    'paid_amount',          // Paid Amount
    'currency',             // Currency
    'rate',                 // Rate
    'method',               // Method
    'method_ref',           // Method Ref.
    'method_amount',        // Method Amount
    'collect_date',         // Collect Date
    'outstand_amount',      // Outstand Amount
    'status',               // Status
    'vat_amount',           // Vat Amount
    'holding_tax_amount'   // Holding Tax Amount
]])

@section('ajax')
<script>
$(document).ready(function() {

    const editableCols = [8,10,11,12,13,14,15,16,17];

    // Enum values: key = DB value, value = display label
    const enumOptions = {
        11: { 'EGP': 'EGP', 'USD': 'USD' }, // paid_currency
        13: { 'cash': 'Cash', 'transfare': 'Transfer', 'cheque': 'Cheque', 'other': 'Other' }, // method
    };

    $('#users-list tbody').on('click', 'td', function() {
        const $cell = $(this);
        const colIndex = $cell.index();

        if (!editableCols.includes(colIndex)) return;
        if ($cell.find('input, select').length > 0) return;

        const originalText = $cell.text().trim();

        // Check if column is enum
        if (enumOptions[colIndex]) {
            const $select = $('<select class="form-control"></select>');

            // Build dropdown using key = DB value, value = label
            Object.entries(enumOptions[colIndex]).forEach(([val, label]) => {
                const $option = $('<option></option>').val(val).text(label);
                // Select current value (match DB value or label)
                if (val === originalText.toLowerCase() || label === originalText) $option.attr('selected', 'selected');
                $select.append($option);
            });

            $cell.html($select);
            $select.focus();

            $select.on('blur change', function() {
                let newValue = $(this).val(); // DB value
                if (newValue === "") newValue = null; // handle nullable
                const newLabel = $(this).find('option:selected').text(); // display label

                if (newValue === originalText || newLabel === originalText) {
                    $cell.text(originalText);
                    return;
                }

                $cell.text(newLabel);
                saveCell($cell, colIndex, newValue);
            });

        } else { // normal input
            const $input = $('<input type="text" class="form-control" />').val(originalText);
            $cell.html($input);
            $input.focus();

            $input.on('blur', function() {
                let newValue = $(this).val().trim();
                if (newValue === "") newValue = null; // handle nullable

                if (newValue === originalText) {
                    $cell.text(originalText);
                    return;
                }

                $cell.text(newValue ?? '');
                saveCell($cell, colIndex, newValue);
            });

            $input.on('keydown', function(e) {
                if (e.key === 'Enter') $(this).blur();
            });
        }
    });

    function saveCell($cell, colIndex, value) {
        const rowId = $cell.closest('tr').data('id'); // invoice_id
        let data = {};

        switch(colIndex) {
            case 8: data.receipt_date = value; break;
            // case 9: data.outstand_days = value; break;
            case 10: data.paid_amount = value; break;
            case 11: data.paid_currency = value; break;
            case 12: data.exchange_rate = value; break;
            case 13: data.method = value; break;
            case 14: data.method_number = value; break;
            case 15: data.method_amount = value; break;
            case 16: data.collect_date = value; break;
            case 17: data.outstand_amount = value; break;
        }

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: '{{ route("finance.store") }}', // use finance.store
            type: 'POST',
            data: {
                invoice_id: rowId,
                ...data
            },
            success: function(res) {
                toastr.success('Saved successfully!');
            },
            error: function(err) {
                toastr.error('Save failed!');
            }
        });
    }

});
</script>

@endsection