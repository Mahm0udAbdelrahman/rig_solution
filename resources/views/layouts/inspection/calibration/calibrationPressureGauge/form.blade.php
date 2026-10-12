<style>
    .cell-input{
        width: 100%;
        border: none;
        /*margin: 0 1px;*/
    }
    .x_scroller {
        overflow-x: scroll;
        overflow-y:hidden;
        width: 100%;
        white-space: nowrap
    }
    td{
        text-wrap: wrap;
    }
    .vertical-cell{
        text-orientation: mixed;
        text-wrap: nowrap;
        writing-mode: tb-rl;
        transform: rotate(-180deg);
    }
    .standards {
        background-color: white;
    }
    hr{
        margin: 1px 0px;
        border-block-color: black;
    }
    .standards_input{
        border: none;
        background-color: inherit;
        padding: 0;
        text-align: center;
    }
</style>
<style>
    * {
        -webkit-print-color-adjust: exact !important;   /* Chrome, Safari, Edge */
        color-adjust: exact !important;                 /*Firefox*/
    }
    label{ font-size: 0.8rem; }
    .app-content .wizard.wizard-circle > .steps > ul > li.current ~ li:before, .app-content .wizard.wizard-circle > .steps > ul > li.current ~ li:after, .app-content .wizard.wizard-circle > .steps > ul > li.current:after{ background-color: #fff; }
    p { letter-spacing: inherit; margin-bottom: 0 !important; }
    .custom-control-label{ margin-bottom: 5px;}
    .custom-control-label::after{ top: 0 !important; }
    .skin-square label{ margin-bottom: auto; }
    .bg-dark{ background-color: #d9d9d9 !important; }
    .white{ color: #000 !important;}
    .logo-top{width: 225px; height: 115px; margin-top: -1.5em;}
    .border-dark{ /*padding-top: 3px !important; padding-bottom: 3px !important;*/ }
    .card-body{ padding: 1rem 2rem; }
    .middle{ display: flex; align-items: center; }
    .mid11{ display: flex; align-items: center; }
    .just{ justify-content: center; }
    .noncheckedfrom{ background: url({{asset('app-assets/vendors/css/forms/icheck/square/purple.png')}}) -1px -1px no-repeat; display: inline-block; margin-right: 0.6rem; width: 17px; height: 17px; border: 1px solid #d9d9d9;}
    .noncheckedradiofrom{ background: url({{asset('app-assets/vendors/css/forms/icheck/square/purple.png')}}) -1px -1px no-repeat; display: inline-block; margin-right: 0.6rem; width: 17px; height: 17px; border: 1px solid #d9d9d9; border-radius: 15px;}
    .checked{ background-position: -51px -3px; border-color: #6a5a8c; }
    .mid{ vertical-align: middle; display: flex; padding-top: 3px; padding-bottom: 3px; }
    .text-16, .skin-square label{ font-size: 16px; }
    p.bnew{font-size: 22px !important; font-weight: 600 !important;}
    h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6{ color: #000 !important; }
    .h-90{ height: 130px; }
    .h-80{ height: 120px; }
    .h-70{ height: 100px; }
    .h-130{ height: 150px;}
    .h-175{ height: 310px; }
    .hp-250{ height: 250px; }
    .card{ page-break-before: always; counter-increment: page; }
    .inc:after{ content: counter(page) " of " counter(pages); }
    .donw{ max-width: 1150px; height: 1664px; margin: auto; border-radius: 0; box-shadow: none; padding-left: 18px; padding-right: 18px;}
    .emadnew{ font-size: 11px !important; }
    @page{  margin: 0 !important; padding: 0 !important; size: a4;  /* margin-right: 5mm !important; margin-left: 5mm !important;*/ }
</style>
<h6>Step 1</h6>
<fieldset>
    <div class="card">
        <div class="card-content collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="form-group mb-0">
                            <div class="controls">
                                {{ Form::label('job_request_id', 'JCF Number') }}
                                {{ Form::select('job_request_id', ['' => 'Select Value'] + $jobrequests->pluck('code', 'id')->toArray(), null, ['class' => 'form-control', 'id' => 'lcr_1', 'required']) }}
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group mb-0">
                            <div class="controls">
                                {{ Form::label('purchase_order', 'Purchase Order') }}
                                {{ Form::text('purchase_order', isset($model)? $model->job_request->purchase_order: null,['class' => 'form-control', 'placeholder' => 'Purchase Order', 'id' => 'purchaseOrder','disabled']) }}

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Report No.</label>
                                <div class="clearfix">
                                    {!! Form::text('precode', isset($model) ? $model->job_request->code : '', [
                                        'id' => 'precode',
                                        'class' => 'form-control pr-0',
                                        'placeholder' => 'Report No',
                                        'required' => 'required',
                                        'disabled' => 'disabled',
                                        'style' => 'width: 83px; float: left;'
                                    ]) !!}

                                    {!! Form::text('code', null, [
                                        'id' => 'code',
                                        'class' => 'form-control pl-0',
                                        'placeholder' => '.',
                                        'required' => 'required',
                                        'readonly' => 'readonly',
                                        'style' => 'width: auto; float: left;'
                                    ]) !!}
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-content collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group mb-0">
                            <div class="controls">
                                {{ Form::label('cliname', 'Name of employer for whom the examination was made') }}
                                {{ Form::text('cliname', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Name of employer for whom the examination was made',
                                    'disabled' => 'disabled'
                                ]) }}
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group mb-0">
                            <div class="controls">
                                {{ Form::label('cliloc', 'Address of premises at examination was made') }}
                                {{ Form::text('cliloc', null, [
                                    'id' => 'cliloc',
                                    'class' => 'form-control',
                                    'placeholder' => 'Address of premises at examination was made',
                                    'disabled' => 'disabled'
                                ]) }}
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-sm-6">
						<div class="form-group mb-1">
							<div class="controls">
								{{ Form::label('contact_name', 'Contact Name', ['class' => 'form-label']) }}
								{{ Form::text('contact_name', null, [
								'class' => 'form-control',
								'placeholder' => 'Contact Name',
								]) }}
								<div class="help-block"></div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6">
						<div class="form-group mb-1">
							<div class="controls">
								{{ Form::label('contact_info', 'Contact Information', ['class' => 'form-label']) }}
								{{ Form::text('contact_info', null, [
								'class' => 'form-control',
								'placeholder' => 'Contact Information',
								]) }}
								<div class="help-block"></div>
							</div>
						</div>
					</div>
	
				</div>
			</div>
		</div>
    <style>
        .due-period-switch { display: inline-flex; align-items: center; gap: .6rem; margin: 0; cursor: pointer; user-select: none; }
        .due-period-switch input { display: none; }
        .due-period-switch .track { position: relative; width: 46px; height: 24px; border-radius: 12px; background: #ff9149; transition: background .2s; }
        .due-period-switch .track:after { content: ''; position: absolute; top: 3px; left: 3px; width: 18px; height: 18px; border-radius: 50%; background: #fff; transition: left .2s; }
        .due-period-switch input:checked + .track { background: #28d094; }
        .due-period-switch input:checked + .track:after { left: 25px; }
        .due-period-switch .opt { font-size: .9rem; font-weight: 600; color: #98a4b8; }
        .due-period-switch .opt.active { color: #1e2329; }
    </style>
    @php
        $duePeriod = old('due_period', isset($model) && $model->due_period ? $model->due_period : 'annual');
    @endphp
    <div class="card">
        <div class="card-content collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group mb-1">
                            <div class="controls">
                                {{ Form::label('calibration_location', 'Calibration Location') }}
                                {{ Form::text('calibration_location', null, ['class' => 'form-control', 'placeholder' => 'e.g. RSE, Lab']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group mb-1">
                            <div class="controls">
                                <label class="d-block">Calibration Interval (Due Date)</label>
                                <input type="hidden" name="due_period" id="due_period" value="{{ $duePeriod }}">
                                <label class="due-period-switch mt-50" for="due_period_switch">
                                    <span class="opt" data-period="semi_annual">Semi-Annual (6 months)</span>
                                    <input type="checkbox" id="due_period_switch" {{ $duePeriod === 'semi_annual' ? '' : 'checked' }}>
                                    <span class="track"></span>
                                    <span class="opt" data-period="annual">Annual (12 months)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Receipt Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ft-calendar"></i></span>
                                    </div>
                                    {{ Form::text('receipt_date', null, ['class' => 'form-control datepicker-default', 'placeholder' => 'Receipt Date', 'required']) }}
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Calibration Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ft-calendar"></i></span>
                                    </div>
                                    {{ Form::text('calibration_date', null, ['class' => 'form-control datepicker-default', 'id' => 'calibration_date', 'placeholder' => 'Calibration Date', 'required']) }}
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Calibration Due Date <small class="text-muted">(auto)</small></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ft-calendar"></i></span>
                                    </div>
                                    {{ Form::text('due_date', null, ['class' => 'form-control datepicker-default', 'id' => 'due_date', 'placeholder' => 'Due Date', 'required']) }}
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Issue Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ft-calendar"></i></span>
                                    </div>
                                    {{ Form::text('issue_date', null, ['class' => 'form-control datepicker-default', 'placeholder' => 'Issue Date', 'required']) }}
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="mt-2 mb-1">Environmental Conditions During Calibration</h5>
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="form-group mb-1">
                            <div class="controls">
                                <label>Temperature</label>
                                <div class="input-group">
                                    {{ Form::text('temperature', null, ['class' => 'form-control', 'placeholder' => '22.2', 'required']) }}
                                    <div class="input-group-append"><span class="input-group-text">°C</span></div>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group mb-1">
                            <div class="controls">
                                <label>Temperature Tolerance (±)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">±</span></div>
                                    {{ Form::text('temperature_tolerance', isset($model) ? null : '1', ['class' => 'form-control', 'placeholder' => '1']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group mb-1">
                            <div class="controls">
                                <label>Temperature Uncertainty</label>
                                <div class="input-group">
                                    {{ Form::text('temperature_uncertainty', null, ['class' => 'form-control', 'placeholder' => '0.7']) }}
                                    <div class="input-group-append"><span class="input-group-text">°C</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Relative Humidity</label>
                                <div class="input-group">
                                    {{ Form::text('relative_humidity', null, ['class' => 'form-control', 'placeholder' => '39.8', 'required']) }}
                                    <div class="input-group-append"><span class="input-group-text">% RH</span></div>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Humidity Tolerance (±)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">±</span></div>
                                    {{ Form::text('humidity_tolerance', isset($model) ? null : '5', ['class' => 'form-control', 'placeholder' => '5']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Humidity Uncertainty</label>
                                <div class="input-group">
                                    {{ Form::text('humidity_uncertainty', null, ['class' => 'form-control', 'placeholder' => '0.3']) }}
                                    <div class="input-group-append"><span class="input-group-text">% RH</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-content collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <div class="controls">
                                {{ Form::label('deploc', 'Work location') }}
                                {{ Form::text('deploc', null, [
                                    'id' => 'deploc',
                                    'class' => 'form-control',
                                    'placeholder' => 'Work location',
                                    'required' => 'required',
                                    'disabled' => 'disabled'
                                ]) }}
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    {{--<div class="col-6">
                        <div class="form-group mb-0">
                            <div class="controls">
                                {{ Form::label('edition', 'Edition') }}
                                {{ Form::text('edition', null, [
                                    'id' => 'edition',
                                    'class' => 'form-control',
                                    'placeholder' => 'Edition',
                                    'data-validation--message' => 'This field is ',
                                    'required'
                                ]) }}
                            </div>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>
</fieldset>
<h6>Step 2</h6>
<fieldset>
	<div>
		@include('layouts.inspection.calibration.partials.form.type2')
	</div>
</fieldset>
<h6>Step 3</h6>
<fieldset>
	@php
		$defaultStatement = implode("\n", [
			'This calibration was carried out in compliance with ISO/IEC17025:2017.',
			'This calibration certificate refers only to the particular item submitted for calibration.',
			'This certificate shall not be reproduced, unless written permission has been obtained from the lab.',
			'This certificate is valid only with signature and stamp and or QR code',
			'Traceability to international system of units (SI).',
		]);
	@endphp
	<div class="card-body">
		<div class="card">
			<div class="card-content collapse show">
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-sm-6">
							<div class="form-group mb-1">
								<div class="controls">
									{{ Form::label('specification_status', 'Result') }}
									{{ Form::select('specification_status', ['in' => 'The instrument is in specification', 'out' => 'The instrument is out of specification'], null, ['class' => 'form-control', 'id' => 'specification_status']) }}
									<small class="text-muted" id="specification_hint"></small>
								</div>
							</div>
						</div>
						<div class="col-12 col-sm-6">
							<div class="form-group mb-1">
								<div class="controls">
									{{ Form::label('issued_by', 'Issued By (QA)') }}
									{{ Form::text('issued_by', null, ['class' => 'form-control', 'placeholder' => 'QA name']) }}
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group mb-1">
								<div class="controls">
									{{ Form::label('remarks', 'Remarks') }}
									{{ Form::text('remarks', isset($model) ? null : 'N/A', ['class' => 'form-control', 'placeholder' => 'N/A']) }}
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<div class="controls">
									{{ Form::label('statement', 'Statement (one line per point)') }}
									{{ Form::textarea('statement', isset($model) ? null : $defaultStatement, ['class' => 'form-control', 'style' => 'height: 12em;', 'rows' => 6, 'required' => '', 'placeholder' => 'Statement...', 'aria-invalid' => 'false', 'data-validation-required-message' => 'This field is required']) }}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</fieldset>

@push('bottom-child-scripts')
<script>
	(function () {
		var MONTHS = { annual: 12, semi_annual: 6 };

		function pad(n) { return ('0' + n).slice(-2); }

		function parseDmy(value) {
			var m = /^(\d{1,2})-(\d{1,2})-(\d{4})$/.exec($.trim(value || ''));
			return m ? new Date(+m[3], +m[2] - 1, +m[1]) : null;
		}

		// calibration date + period - 1 day, clamped to month end (31-08 + 6 months -> 27-02)
		function computeDueDate(calibration, months) {
			var target = new Date(calibration.getFullYear(), calibration.getMonth() + months, 1);
			var lastDay = new Date(target.getFullYear(), target.getMonth() + 1, 0).getDate();
			target.setDate(Math.min(calibration.getDate(), lastDay));
			target.setDate(target.getDate() - 1);
			return pad(target.getDate()) + '-' + pad(target.getMonth() + 1) + '-' + target.getFullYear();
		}

		function currentPeriod() {
			return $('#due_period_switch').is(':checked') ? 'annual' : 'semi_annual';
		}

		function refreshSwitchLabels() {
			var period = currentPeriod();
			$('#due_period').val(period);
			$('.due-period-switch .opt').each(function () {
				$(this).toggleClass('active', $(this).data('period') === period);
			});
		}

		function refreshDueDate() {
			var calibration = parseDmy($('#calibration_date').val());
			if (calibration) {
				$('#due_date').val(computeDueDate(calibration, MONTHS[currentPeriod()]));
			}
		}

		$(document).on('change', '#due_period_switch', function () {
			refreshSwitchLabels();
			refreshDueDate();
		});
		$(document).on('change', '#calibration_date', refreshDueDate);

		refreshSwitchLabels();
		if (!$('#due_date').val()) {
			refreshDueDate();
		}
	})();
</script>
@endpush
