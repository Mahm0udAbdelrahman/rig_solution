{{--
    NDT show / publish pages: a report section's additional equipment (layouts.repeated.equipment_rows_show)
    in a compact table styled like the printed report grid. Renders nothing when the section has no rows.

    @include('layouts.inspection.ndt.partials.report_equipment_show', [
        'model' => $mpipt, 'section' => 'mpi', 'title' => 'Additional MPI equipment',
        'columns' => ['equipment_no' => 'Equipment No.', ...],   // optional
        'grid' => true,       // show pages: wrap in a report grid row (.row > .col-12)
        'rowStyle' => '',     // optional inline style for that row (e.g. 'margin: auto;')
    ])
--}}
@php
    $ndtEquipmentRows = (isset($model) && $model && method_exists($model, 'reportEquipmentFor')) ? $model->reportEquipmentFor($section) : collect();
@endphp
@once
<style>
    .ndt-report-equipment .report-equipment-show { margin: 0; table-layout: fixed; border-collapse: collapse; background-color: #fff; }
    .ndt-report-equipment .report-equipment-show th,
    .ndt-report-equipment .report-equipment-show td { padding: 1px 4px !important; border: 1px solid #424242 !important; font-size: 12px; line-height: 1.35; color: #000 !important; vertical-align: middle; word-wrap: break-word; }
    .ndt-report-equipment .report-equipment-show th { background-color: #d9d9d9 !important; font-weight: 600; }
</style>
@endonce
@if($ndtEquipmentRows->isNotEmpty())
    @php
        $ndtEquipmentShow = ['model' => $model, 'section' => $section, 'title' => $title ?? 'Additional equipment'];
        if (!empty($columns)) {
            $ndtEquipmentShow['columns'] = $columns;
        }
    @endphp
    @if(!empty($grid))
        <div class="row ndt-report-equipment" style="{{ $rowStyle ?? '' }}">
            <div class="col-12 p-0">
                @include('layouts.repeated.equipment_rows_show', $ndtEquipmentShow)
            </div>
        </div>
    @else
        <div class="ndt-report-equipment mt-1">
            @include('layouts.repeated.equipment_rows_show', $ndtEquipmentShow)
        </div>
    @endif
@endif
