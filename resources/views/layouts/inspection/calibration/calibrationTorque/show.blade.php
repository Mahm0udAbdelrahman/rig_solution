@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
    <style>
		.bg-dark {
			background-color: #c6d4f4 !important;
		}
		.bg-dark-2 {
			background-color: #8ea9e7 !important;
		}
    </style>
    <!-- <input type="hidden" id="page_mode" value="l"> -->
    <!----------------------------------->
		@include('layouts.inspection.calibration.partials.show.type4')
    <!----------------------------------->
    @include('layouts.styles.reportfooter-v2')
    @include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $model->report->id])
@endpush
