{{--
    Equipment picker: a searchable drop-down of the Equipment Controlled List (search by serial no. or name).
    Choosing an item copies its values into other form fields, which become read-only.

    @include('layouts.repeated.equipment_picker', [
        'fill' => ['equipment_no' => '#nmpr_14', 'manufacturer' => '#nmpr_17', 'calibration_due_date' => '#nmpr_20'],
        'toggleWith' => '#nmpr_120',          // optional: picker is enabled only while this checkbox is checked
        'checks' => ['#nmpr_12', '#nmpr_120'], // optional: tick these checkboxes (in order) when an item is picked
        'clearWith' => '#nmpr_120',           // optional: clear the picker when this checkbox is unticked (stays enabled)
        'scope' => '[data-repeater-item]',    // optional: look up fill targets inside this closest container
        'match' => 'equipment_no',            // optional: key used to re-select the saved item on edit pages
        'id' => 'mpi_equipment_picker',       // optional: id for the select
        'types' => ['Yoke'],                  // optional: only list equipment with these descriptions
        'name' => 'report_equipment[...]',    // optional: submit the chosen register id under this name
        'selected' => 12,                     // optional: register id to select on load (kept even if now out of service)
        'placeholder' => 'Select equipment',
    ])

    Fill keys: equipment_no (serial, or internal code when no serial), serial_number, internal_code,
    equipment_description, manufacturer, model_type, capacity_range, calibrated_by,
    calibration_date, calibration_due_date (dates as dd-mm-yyyy).
--}}
@php
    $pickerFill = $fill ?? [];
    $pickerMatch = $match ?? (array_key_exists('equipment_no', $pickerFill) ? 'equipment_no' : array_key_first($pickerFill));
    $pickerTypes = array_map('strtolower', $types ?? []);
    $pickerSelected = $selected ?? null;
    $pickerEquipments = \App\Models\GeneralInfo\EquipmentControlledList::pickerOptions()
        ->filter(fn ($equipment) => !$pickerTypes || in_array(strtolower(trim($equipment->equipment_description)), $pickerTypes))
        ->values();
    if ($pickerSelected && !$pickerEquipments->contains('id', $pickerSelected)) {
        $pickerSavedEquipment = \App\Models\GeneralInfo\EquipmentControlledList::find($pickerSelected);
        if ($pickerSavedEquipment) {
            $pickerEquipments->prepend($pickerSavedEquipment);
        }
    }
@endphp
<select class="form-control searchable-select equipment-picker {{ $class ?? '' }}"
        @if(!empty($id)) id="{{ $id }}" @endif
        @if(!empty($name)) name="{{ $name }}" @endif
        data-placeholder="{{ $placeholder ?? 'Search serial no. or name' }}"
        data-fill='@json($pickerFill)'
        data-match="{{ $pickerMatch }}"
        @if(!empty($toggleWith)) data-toggle-with="{{ $toggleWith }}" disabled @endif
        @if(!empty($checks)) data-checks='@json(array_values((array) $checks))' @endif
        @if(!empty($clearWith)) data-clear-with="{{ $clearWith }}" @endif
        @if(!empty($scope)) data-scope="{{ $scope }}" @endif>
    <option value=""></option>
    @foreach($pickerEquipments as $pickerEquipment)
        <option value="{{ $pickerEquipment->id }}" data-equipment='@json($pickerEquipment->picker_data)' @if($pickerSelected == $pickerEquipment->id) selected @endif>{{ $pickerEquipment->picker_label }}</option>
    @endforeach
</select>

@include('layouts.repeated.equipment_picker_assets')
