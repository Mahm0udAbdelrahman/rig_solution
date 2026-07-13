@section('header')
	  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/ui/jquery-ui.min.css')}}">
	  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
	  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/icheck/icheck.css')}}">
	  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/toggle/switchery.min.css')}}">
	  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/selects/select2.min.css')}}">
	  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/validation/form-validation.css')}}">
@endsection
@section('header-bottom')
	  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/wizard.css')}}">
	  <style>
	    select{ margin-top: 5px; }
	    .app-content .wizard.wizard-circle > .steps > ul > li.current ~ li:before, .app-content .wizard.wizard-circle > .steps > ul > li.current ~ li:after, .app-content .wizard.wizard-circle > .steps > ul > li.current:after{ background-color: #fff; }
	    .form-group label.danger{ display: block; width: 100%; text-align: center; margin-left: auto; background: #f44336; color: #fff !important; font-weight: 600; padding: 2px 0 4px 0; }
	    .form-group .icheckbox_square-green label.danger{ font-size: 0; padding: 10px; background: none; border: 1px solid #f44336; position: absolute; }
		.searchable-select + .select2-container .select2-selection--single {
			height: 36px;
			border-color: #e6e6e6;
		}
		.searchable-select + .select2-container .select2-selection--single .select2-selection__rendered {
			line-height: 34px;
			font-size: .96rem;
		}
		.searchable-select + .select2-container .select2-selection--single .select2-selection__arrow {
			height: 34px;
		}
		.searchable-select-dropdown .select2-search--dropdown .select2-search__field {
			min-height: 32px;
			padding: 6px 8px;
			font-size: .95rem;
			border-radius: 6px;
			border: 1px solid #d7dfef;
		}
		.searchable-select-dropdown .select2-results__option {
			font-size: .95rem;
			padding: 7px 10px;
		}
		.modern-datepicker-popup.ui-datepicker {
			width: 15.4rem;
			padding: 6px 6px 5px;
			border: 1px solid #dce4f4;
			border-radius: 12px;
			background: #ffffff;
			box-shadow: 0 14px 34px rgba(24, 51, 99, 0.18);
			z-index: 1200 !important;
		}
		.modern-datepicker-popup .ui-datepicker-header {
			background: linear-gradient(135deg, #5a46d6, #3f7ce8);
			border: 0;
			border-radius: 9px;
			padding: 6px 5px;
			color: #fff;
		}
		.modern-datepicker-popup .ui-datepicker-title {
			margin: 0 1.8rem;
			font-size: 0.97rem;
			font-weight: 600;
		}
		.modern-datepicker-popup .ui-datepicker-prev,
		.modern-datepicker-popup .ui-datepicker-next {
			top: 50%;
			transform: translateY(-50%);
			width: 28px;
			height: 28px;
			border-radius: 50%;
			transition: background-color .15s ease;
		}
		.modern-datepicker-popup .ui-datepicker-prev:hover,
		.modern-datepicker-popup .ui-datepicker-next:hover {
			background: rgba(255, 255, 255, 0.22);
		}
		.modern-datepicker-popup .ui-datepicker-prev span,
		.modern-datepicker-popup .ui-datepicker-next span {
			filter: brightness(2.2);
		}
		.modern-datepicker-popup .ui-datepicker-calendar th {
			padding: 5px 0;
			font-size: 0.83rem;
			color: #7a89ad;
			font-weight: 600;
			text-transform: uppercase;
		}
		.modern-datepicker-popup .ui-datepicker-calendar td {
			padding: 1px;
		}
		.modern-datepicker-popup .ui-datepicker-calendar .ui-state-default {
			border: 0;
			border-radius: 7px;
			background: transparent;
			color: #2f3e60;
			text-align: center;
			font-size: 0.97rem;
			padding: 5px 0;
		}
		.modern-datepicker-popup .ui-datepicker-calendar .ui-state-hover {
			background: #edf2ff;
			color: #304a8d;
		}
		.modern-datepicker-popup .ui-datepicker-calendar .ui-state-active {
			background: linear-gradient(135deg, #5a46d6, #3f7ce8);
			color: #fff;
		}
		.modern-datepicker-popup .ui-datepicker-calendar .ui-state-highlight {
			border: 1px dashed #8ca6ea;
			background: #f5f8ff;
		}
		.modern-datepicker-popup .ui-datepicker-buttonpane {
			border-top: 1px solid #e8edf7;
			margin-top: 8px;
			padding-top: 8px;
		}
		.modern-datepicker-popup .ui-datepicker-buttonpane button {
			border: 1px solid #d4ddf2;
			background: #fff;
			border-radius: 8px;
			padding: 3px 9px;
			font-size: .82rem;
			color: #4a5d87;
		}
		.modern-datepicker-popup select.ui-datepicker-month,
		.modern-datepicker-popup select.ui-datepicker-year {
			border: 1px solid rgba(255, 255, 255, 0.35);
			background: rgba(255, 255, 255, 0.16);
			color: #fff;
			border-radius: 8px;
			height: 28px;
			font-size: .82rem;
			margin: 0 2px;
		}
		input.dp-date-range-from,
		input.dp-date-range-to {
			cursor: pointer;
			background-color: #fff !important;
		}
	  </style>
@endsection
