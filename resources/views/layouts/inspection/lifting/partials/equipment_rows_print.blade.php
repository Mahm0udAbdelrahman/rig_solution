{{--
    Lifting certificates (show page = the printed / uploaded PDF): a report section's additional equipment,
    styled like the surrounding grey-header grid. Renders nothing when the section has no rows.

    @include('layouts.inspection.lifting.partials.equipment_rows_print', ['model' => $crane2, 'section' => 'mpi'])
--}}
@php
    $printSections = [
        'mpi' => [
            'title' => 'Additional MPI equipment',
            'columns' => ['equipment_no' => 'Equipment No.', 'equipment_description' => 'Equipment', 'manufacturer' => 'Manufacturer', 'calibration_due_date' => 'Calibration Due'],
        ],
        'load_test' => [
            'title' => 'Additional load test equipment',
            'columns' => ['equipment_no' => 'Equipment / Serial No.', 'equipment_description' => 'Equipment', 'capacity_range' => 'Capacity / Range', 'manufacturer' => 'Manufacturer', 'calibration_due_date' => 'Calibration Due'],
        ],
    ];
    $printModel = $model ?? null;
    $printHasRows = $printModel && method_exists($printModel, 'reportEquipmentFor') && $printModel->reportEquipmentFor($section)->isNotEmpty();
@endphp
@if($printHasRows)
    @once
        <style>
            .lifting-report-equipment .report-equipment-show { margin: 0; background: #fff; }
            .lifting-report-equipment .report-equipment-show th,
            .lifting-report-equipment .report-equipment-show td { border: 1px solid #424242 !important; padding: 1px 0.5rem; font-size: 14px; color: #000; vertical-align: middle; }
            .lifting-report-equipment .report-equipment-show th { background-color: #d9d9d9 !important; font-weight: 600; }
        </style>
    @endonce
    <div class="row lifting-report-equipment">
        <div class="col-12 p-0">
            @include('layouts.repeated.equipment_rows_show', [
                'model' => $printModel,
                'section' => $section,
                'title' => $title ?? ($printSections[$section]['title'] ?? 'Additional equipment'),
                'columns' => $columns ?? ($printSections[$section]['columns'] ?? null),
            ])
        </div>
    </div>
@endif
