@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
    @include('layouts.general-info.equipmentControlledList.partials.form-steps')
@endsection

@extends('layouts.scripts.forms')

@prepend('child-scripts')
    @include('layouts.general-info.equipmentControlledList.partials.form-script', ['submitUrl' => route('equipment-controlled-list.store')])
@endprepend
