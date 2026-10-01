@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
    @include('layouts.general-info.equipmentControlledList.partials.form-steps', ['equipment' => $equipment])
@endsection

@extends('layouts.scripts.forms')

@prepend('child-scripts')
    @include('layouts.general-info.equipmentControlledList.partials.form-script', ['equipment' => $equipment, 'submitUrl' => route('equipment-controlled-list.update', $equipment->id)])
@endprepend
