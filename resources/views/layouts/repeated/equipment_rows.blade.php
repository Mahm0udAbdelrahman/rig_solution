{{--
    "Additional equipment" list: rows added with a button, each with its own equipment picker.
    Saved into report_equipment by HasReportEquipment::syncReportEquipment($request) in the controller's store/update.

    @include('layouts.repeated.equipment_rows', [
        'section' => 'ut_instrument',                 // unique per list within the report ([a-z0-9_])
        'model' => $model ?? null,                    // report being edited (uses HasReportEquipment)
        'title' => 'Additional equipment',
        'types' => ['Flow Detector UT', 'UT STD Block'], // optional: only list these kinds of equipment
        'columns' => ['equipment_no', 'equipment_description', 'manufacturer', 'calibration_due_date'], // optional: fields shown per row
        'checks' => ['#nmpr_12'],                     // optional: tick these checkboxes when a row is added
    ])
--}}
@php
    $rowsColumns = $columns ?? ['equipment_no', 'equipment_description', 'manufacturer', 'calibration_due_date'];
    $rowsExisting = (isset($model) && $model && method_exists($model, 'reportEquipmentFor')) ? $model->reportEquipmentFor($section) : collect();
@endphp
@include('layouts.repeated.equipment_picker_assets')
<div class="report-equipment-rows mt-1" data-section="{{ $section }}" @if(!empty($checks)) data-checks='@json(array_values((array) $checks))' @endif>
    <input type="hidden" name="report_equipment_sections[]" value="{{ $section }}">
    <div class="d-flex justify-content-between align-items-center mb-50">
        <label class="mb-0 font-weight-bold">{{ $title ?? 'Additional equipment' }}</label>
        <button type="button" class="btn btn-sm btn-outline-primary report-equipment-add"><i class="la la-plus"></i> Add equipment</button>
    </div>
    <div class="report-equipment-list">
        @foreach($rowsExisting as $rowIndex => $rowEquipment)
            @include('layouts.repeated.equipment_rows_row', ['section' => $section, 'index' => $rowIndex, 'row' => $rowEquipment, 'columns' => $rowsColumns, 'types' => $types ?? null])
        @endforeach
    </div>
    <template class="report-equipment-template">
        @include('layouts.repeated.equipment_rows_row', ['section' => $section, 'index' => '__INDEX__', 'row' => null, 'columns' => $rowsColumns, 'types' => $types ?? null])
    </template>
</div>

@once
<script>
// Form wizards move their markup with jQuery, which runs inline scripts again: bind only once
if (!window.__equipmentRowsScript) {
window.__equipmentRowsScript = true;
window.addEventListener('load', function () {
    var $ = window.jQuery;
    if (!$) return;
    var rowSeq = 0;

    $(document).on('click', '.report-equipment-add', function () {
        var $rows = $(this).closest('.report-equipment-rows');
        if (typeof window.checkEquipmentToggles === 'function') window.checkEquipmentToggles($rows.data('checks'));
        var html = $rows.find('template.report-equipment-template').html().replace(/__INDEX__/g, 'n' + Date.now() + (rowSeq++));
        var $row = $(html).appendTo($rows.find('.report-equipment-list'));
        if (typeof window.initEquipmentPickers === 'function') window.initEquipmentPickers($row);
    });

    $(document).on('click', '.report-equipment-remove', function () {
        $(this).closest('.report-equipment-row').remove();
    });
});
}
</script>
@endonce
