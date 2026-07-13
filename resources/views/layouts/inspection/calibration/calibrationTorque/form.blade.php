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
        <div class="card-content collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Receipt Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
										<span class="input-group-text">
											<i class="ft-calendar"></i>
										</span>
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
										<span class="input-group-text">
											<i class="ft-calendar"></i>
										</span>
                                    </div>
                                    {{ Form::text('calibration_date', null, ['class' => 'form-control datepicker-default', 'placeholder' => 'Calibration Date', 'required']) }}
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Due Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
										<span class="input-group-text">
											<i class="ft-calendar"></i>
										</span>
                                    </div>
                                    {{ Form::text('due_date', null, ['class' => 'form-control datepicker-default', 'placeholder' => 'Due Date', 'required']) }}
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
										<span class="input-group-text">
											<i class="ft-calendar"></i>
										</span>
                                    </div>
                                    {{ Form::text('issue_date', null, ['class' => 'form-control datepicker-default', 'placeholder' => 'Issue Date', 'required']) }}
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
				</div>
				<div class="row" style="margin-top: 40px;">
                    <div class="col-12 col-sm-6">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Temperature</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
										<span class="input-group-text">
											<i class="ft-calendar"></i>
										</span>
                                    </div>
                                    {{ Form::text('temperature', null, ['class' => 'form-control', 'placeholder' => 'Temperature', 'required']) }}
                                </div>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Relative Humidity</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
										<span class="input-group-text">
											<i class="ft-calendar"></i>
										</span>
                                    </div>
                                    {{ Form::text('relative_humidity', null, ['class' => 'form-control', 'placeholder' => 'Relative Humidity', 'required']) }}
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
	<div class="card-body">
		<h4>Statement</h4>
		<div class="card">
			<div class="card-content collapse show">
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="form-group">
								<div class="controls">
									{{ Form::label('statement', 'Statement') }}
									{{ Form::textarea('statement', null, ['class' => 'form-control', 'style' => 'height: 24em;', 'rows' => 12, 'required' => '', 'placeholder' => 'Statement...', 'aria-invalid' => 'false', 'data-validation-required-message' => 'This field is required']) }}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</fieldset>

<script src="{{asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
<!-- <script src="{{asset('app-assets/js/scripts/forms/form-repeater.js')}}"></script> -->
<script>
	// 

  $('.repeater').repeater({
    show: function () {
	  $(this).find('img').attr('src', '');
      $(this).slideDown();
	  $(this).slideUp(remove);
    },
    hide: function (remove) {
      if (confirm('Are you sure you want to remove this item?')) {
        $(this).slideUp(remove);
      }
    }
  });
</script>
