{{-- Read-only list of a report section's additional equipment (show / publish pages). Renders nothing when empty. --}}
@php
    $showRows = (isset($model) && $model && method_exists($model, 'reportEquipmentFor')) ? $model->reportEquipmentFor($section) : collect();
    $showColumns = $columns ?? ['equipment_no' => 'Equipment No.', 'equipment_description' => 'Equipment', 'manufacturer' => 'Manufacturer', 'calibration_due_date' => 'Calibration Due'];
@endphp
@if($showRows->isNotEmpty())
    <table class="table table-sm table-bordered mb-0 report-equipment-show" style="width: 100%;">
        <thead>
            <tr>
                <th colspan="{{ count($showColumns) }}">{{ $title ?? 'Additional equipment' }}</th>
            </tr>
            <tr>
                @foreach($showColumns as $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($showRows as $showRow)
                <tr>
                    @foreach(array_keys($showColumns) as $field)
                        <td>{{ $showRow->$field ?: 'N/A' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
