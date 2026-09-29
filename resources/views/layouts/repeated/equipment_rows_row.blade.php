@php
    $rowLabels = [
        'equipment_no' => 'Equipment / Serial No.',
        'equipment_description' => 'Equipment',
        'manufacturer' => 'Manufacturer',
        'model_type' => 'Model / Type',
        'capacity_range' => 'Capacity / Range',
        'calibration_due_date' => 'Calibration Due',
    ];
    $rowName = 'report_equipment[' . $section . '][' . $index . ']';
@endphp
<div class="form-row report-equipment-row align-items-center mb-50">
    <div class="col-4">
        @include('layouts.repeated.equipment_picker', [
            'name' => $rowName . '[equipment_id]',
            'selected' => $row->equipment_controlled_list_id ?? null,
            'types' => $types ?? null,
            'scope' => '.report-equipment-row',
            'fill' => collect(array_keys($rowLabels))->mapWithKeys(fn ($field) => [$field => '.re-' . $field])->all(),
        ])
    </div>
    @foreach($rowLabels as $field => $label)
        @if(in_array($field, $columns))
            <div class="col">
                <input type="text" class="form-control re-{{ $field }}" name="{{ $rowName }}[{{ $field }}]" value="{{ $row->$field ?? '' }}" placeholder="{{ $label }}" title="{{ $label }}">
            </div>
        @else
            <input type="hidden" class="re-{{ $field }}" name="{{ $rowName }}[{{ $field }}]" value="{{ $row->$field ?? '' }}">
        @endif
    @endforeach
    <div class="col-auto">
        <button type="button" class="btn btn-sm btn-danger report-equipment-remove" title="Remove"><i class="ft-x"></i></button>
    </div>
</div>
