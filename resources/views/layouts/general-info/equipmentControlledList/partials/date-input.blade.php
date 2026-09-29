{{-- Dashboard-style date field: calendar icon + dd-mm-yyyy picker, posting Y-m-d through a hidden input named $name --}}
@php
    $dateValue = !empty($value) ? \Carbon\Carbon::parse($value) : null;
@endphp
<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="ft-calendar"></i></span>
    </div>
    <input type="text" id="{{ $name }}_display" class="form-control date-display" data-target="#{{ $name }}"
           placeholder="{{ $placeholder ?? 'dd-mm-yyyy' }}" autocomplete="off"
           value="{{ $dateValue ? $dateValue->format('d-m-Y') : '' }}" @if(!empty($readonly)) readonly @endif>
    <input type="hidden" id="{{ $name }}" name="{{ $name }}" value="{{ $dateValue ? $dateValue->format('Y-m-d') : '' }}">
</div>
