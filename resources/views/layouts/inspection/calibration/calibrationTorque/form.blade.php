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
        .due-period-options { display: inline-flex; border: 1px solid #cacfe7; border-radius: .25rem; overflow: hidden; }
        .due-period-options label { margin: 0; padding: .45rem .9rem; font-size: .85rem; font-weight: 600; color: #6b6f82; cursor: pointer; user-select: none; border-left: 1px solid #cacfe7; }
        .due-period-options label:first-child { border-left: 0; }
        .due-period-options input { display: none; }
        .due-period-options label.active { background: #28d094; color: #fff; }
    </style>
    @php
        $duePeriod = old('due_period', isset($model) && $model->due_period ? $model->due_period : 'annual');
        $duePeriodOptions = ['semi_annual' => '6 months', 'annual' => '12 months', 'manual' => 'Manual / blank'];
        if (!isset($duePeriodOptions[$duePeriod])) {
            $duePeriod = 'manual';
        }
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
                                <div class="due-period-options mt-50">
                                    @foreach($duePeriodOptions as $periodValue => $periodLabel)
                                        <label class="{{ $duePeriod === $periodValue ? 'active' : '' }}">
                                            <input type="radio" name="due_period" class="js-due-period" value="{{ $periodValue }}" {{ $duePeriod === $periodValue ? 'checked' : '' }}>{{ $periodLabel }}
                                        </label>
                                    @endforeach
                                </div>
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
                                <label>Calibration Due Date <small class="text-muted" id="due_date_hint"></small></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ft-calendar"></i></span>
                                    </div>
                                    {{ Form::text('due_date', null, ['class' => 'form-control datepicker-default', 'id' => 'due_date', 'placeholder' => 'Blank']) }}
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Issue Date <small class="text-muted">(auto)</small></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ft-calendar"></i></span>
                                    </div>
                                    {{-- set by the system on the day the certificate is created --}}
                                    <input type="text" class="form-control" value="{{ isset($model) && $model->issue_date ? $model->issue_date : '' }}" placeholder="Set when the certificate is created" disabled>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="mt-2 mb-1">Environmental Conditions During Calibration</h5>
                @foreach([
                    ['temperature', 'Temperature', '°C', '1', ['22.8', '23.2']],
                    ['humidity', 'Relative Humidity', '% RH', '5', ['39', '41']],
                ] as [$envKey, $envLabel, $envUnit, $envTolerance, $envPlaceholders])
                    <div class="row js-env" data-env="{{ $envKey }}">
                        <div class="col-12 col-sm-3">
                            <div class="form-group mb-1">
                                <label>{{ $envLabel }} &mdash; Start</label>
                                <div class="input-group">
                                    {{ Form::text($envKey . '_start', null, ['class' => 'form-control js-env-start', 'placeholder' => $envPlaceholders[0], 'required']) }}
                                    <div class="input-group-append"><span class="input-group-text">{{ $envUnit }}</span></div>
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-3">
                            <div class="form-group mb-1">
                                <label>{{ $envLabel }} &mdash; End</label>
                                <div class="input-group">
                                    {{ Form::text($envKey . '_end', null, ['class' => 'form-control js-env-end', 'placeholder' => $envPlaceholders[1]]) }}
                                    <div class="input-group-append"><span class="input-group-text">{{ $envUnit }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-2">
                            <div class="form-group mb-1">
                                <label>Tolerance (±)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">±</span></div>
                                    {{ Form::text($envKey . '_tolerance', isset($model) ? null : $envTolerance, ['class' => 'form-control', 'placeholder' => $envTolerance]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group mb-1">
                                <label>Average / Variation <small class="text-muted">(auto)</small></label>
                                <input type="text" class="form-control js-env-result" disabled>
                            </div>
                        </div>
                    </div>
                @endforeach
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
		@include('layouts.inspection.calibration.partials.form.type4')
	</div>
</fieldset>
<h6>Step 3</h6>
<fieldset>
	@php
		$defaultStatement = implode("\n", \App\Models\Inspection\Calibration\CalibrationTorque::DEFAULT_STATEMENT);
	@endphp
	<div class="card-body">
		<div class="card">
			<div class="card-content collapse show">
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="form-group mb-1">
								<div class="controls">
									{{ Form::label('specification_status', 'Result') }}
									{{ Form::select('specification_status', ['in' => 'The instrument is in specification', 'out' => 'The instrument is out of specification'], null, ['class' => 'form-control', 'id' => 'specification_status']) }}
									<small class="text-muted" id="specification_hint"></small>
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
									<small class="text-muted d-block mb-50">The conformity statement (Pass / Fail) is printed above these lines on every certificate.</small>
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
			return $('.js-due-period:checked').val() || 'annual';
		}

		// 6 / 12 months: the due date follows the calibration date; manual: typed by hand or left blank
		function refreshPeriod() {
			var period = currentPeriod();
			$('.js-due-period').each(function () {
				$(this).closest('label').toggleClass('active', this.checked);
			});
			var manual = !MONTHS[period];
			$('#due_date').prop('readonly', !manual);
			$('#due_date_hint').text(manual ? '(manual, can be blank)' : '(auto)');
		}

		function refreshDueDate() {
			var months = MONTHS[currentPeriod()];
			var calibration = parseDmy($('#calibration_date').val());
			if (months && calibration) {
				$('#due_date').val(computeDueDate(calibration, months));
			}
		}

		$(document).on('change', '.js-due-period', function () {
			refreshPeriod();
			refreshDueDate();
		});
		$(document).on('change', '#calibration_date', refreshDueDate);
		// keep the calendar closed while the due date is automatic
		$(document).on('focus click', '#due_date', function () {
			var input = this;
			if (input.readOnly && typeof $.fn.datepicker === 'function') {
				setTimeout(function () { $(input).datepicker('hide'); }, 0);
			}
		});

		refreshPeriod();
		if (!$('#due_date').val()) {
			refreshDueDate();
		}

		// average and variation (|start - end| / 2) of the environmental readings, as on the Data Input sheet
		function envNumber(v) {
			v = $.trim(String(v || '')).replace(',', '.');
			return v !== '' && !isNaN(v) ? parseFloat(v) : null;
		}
		function refreshEnvironment() {
			$('.js-env').each(function () {
				var start = envNumber($(this).find('.js-env-start').val());
				var end = envNumber($(this).find('.js-env-end').val());
				var values = [start, end].filter(function (v) { return v !== null; });
				var text = '';
				if (values.length) {
					var average = values.reduce(function (a, b) { return a + b; }, 0) / values.length;
					text = 'Average ' + (+average.toFixed(1));
					if (values.length === 2) text += '  /  Variation ' + (+(Math.abs(start - end) / 2).toFixed(2));
				}
				$(this).find('.js-env-result').val(text);
			});
		}
		$(document).on('input change', '.js-env-start, .js-env-end', refreshEnvironment);
		refreshEnvironment();
	})();
</script>
@endpush
