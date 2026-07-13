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

    .standards_input{
        border: none;
        background-color: inherit;
        padding: 0;
        text-align: center;
    }
    .border_left_custom {
        border-left: solid 1px black;
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
                    <div class="col-12 col-sm-4">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Examination Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                                             <span class="input-group-text"><i
                                                                         class="ft-calendar"></i></span>
                                    </div>
                                    {{ Form::text('examination_date', null, ['class' => 'form-control datepicker-default', 'id' => 'examination_date', 'placeholder' => 'Examination Date', 'required']) }}
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
                </div>
            </div>
        </div>
    </div>
</fieldset>
<h6>Step 2</h6>
<fieldset>
    <div class="card">
        <div class="card-content collapse show">
            <div class="card-body">
                <h6 class="mb-1">Specification</h6>
                <div class="skin skin-square form-group mt-1">
                    <div class="controls">
                        <div class="row">
                            @foreach($specificationOptions as $specification)
                                <div class="col-md-3 col-sm-12">
                                    <fieldset>
                                        {!! Form::checkbox('specification[]', $specification->code, isset($model)? in_array($specification->code, $model->specification): null, ['id' => $specification->code, 'class' => 'specification']) !!}
                                        {!! Form::label($specification->code, $specification->name) !!}
                                    </fieldset>
                                </div>
                            @endforeach
                        </div>
                        <div class="row" id="other_specification_section" style="display: {{isset($model) && $model->other_specification ? 'default' : 'none'}};">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <div class="controls">
                                        {{ Form::label('other_specification', 'If Other Specifications') }}
                                        {{ Form::text('other_specification', null, [
                                            'id' => 'other_specification',
                                            'class' => 'form-control',
                                            'placeholder' => 'Other Specification',
                                            'disabled' => isset($model) && $model->other_specification ? false : true
                                        ]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <div class="controls">
                                        {{ Form::label('edition', 'Edition') }}
                                        {{ Form::text('edition', null, [
                                            'id' => 'edition',
                                            'class' => 'form-control',
                                            'placeholder' => 'Edition',
                                            'data-validation--message' => 'This field is '
                                        ]) }}
                                    </div>
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
                <h6 class="mb-1">Inspection Method</h6>
                <div class="skin skin-square form-group mt-1">
                    <div class="controls">
                        <div class="row">
                            @foreach($inspectionMethods as $method)
                                <div class="col-md-3 col-sm-12">
                                    <fieldset>
                                        {!! Form::checkbox('inspection_method[]', $method, isset($model)? in_array($method, $model->inspection_method): null, ['id' => 'inspection_'.$method, 'class' => 'inspection_method']) !!}
                                        {!! Form::label('inspection_'.$method, $method) !!}
                                    </fieldset>
                                </div>
                            @endforeach
                            <div class="col-md-3 col-sm-12">
                                <fieldset>
                                    <input type="checkbox" class="inspection_method" id="other_method" data-id="other" {{isset($model->other_inspection_method)? 'checked' : ''}}>
                                    <label for="other_method">Other</label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row" id="other_inspection_method_section"
                             style="display: {{isset($model) && $model->other_inspection_method ? 'block' : 'none'}};">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <div class="controls">
                                        {{ Form::label('other_inspection_method', 'If Other Inspection method') }}
                                        {{ Form::text('other_inspection_method', null, [
                                            'id' => 'other_inspection_method',
                                            'class' => 'form-control',
                                            'placeholder' => 'other Inspection method',
                                            'disabled' => isset($model->other_inspection_method) ? false : true
                                        ]) }}
                                    </div>
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
                <h6 class="mb-1">Equipments Used:</h6>
                <div class="card repeater">
                    @include('layouts.repeated.equipment_list_input', ['model' => isset($model)? $model : null , 'equipments' => $equipments])
                </div>
            </div>
        </div>
    </div>
</fieldset>
<h6>Step 3</h6>
<fieldset>
    <div class="card">
        <div class="card-content collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row mt-1">
                            <div class="col-2">
                                <p>Pipe Status:</p>
                            </div>
                            <div class="col-2">
                                <div class="custom-control custom-radio">
                                    {{ Form::radio('pipe_status', 'New', null, ['class' => 'custom-control-input', 'id' => 'pipe_status_new', 'required']) }}
                                    {{ Form::label('pipe_status_new', 'New', ['class' => 'custom-control-label']) }}
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="custom-control custom-radio">
                                    {{ Form::radio('pipe_status', 'Used', null, ['class' => 'custom-control-input', 'id' => 'pipe_status_used', 'required']) }}
                                    {{ Form::label('pipe_status_used', 'Used', ['class' => 'custom-control-label']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
													<div class="form-group mt-1">
                            <div class="controls">
                                {{ Form::label('pipe_description', 'Pipe Description:') }}
                                {{ Form::text('pipe_description', null, ['class' => 'form-control', 'placeholder' => 'Pipe Description']) }}
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
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('pipe_type', 'Type Of Pipe:') }}
                                {{ Form::text('pipe_type', null, ['class' => 'form-control', 'placeholder' => 'Pipe Type', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('nominal_od', 'Nominal OD:') }}
                                {{ Form::text('nominal_od', null, ['class' => 'form-control', 'placeholder' => 'Nominal OD', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('grade', 'Grade') }}
                                {{ Form::text('grade', null, ['class' => 'form-control', 'placeholder' => 'Grade', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('lbs_ft', 'lbs/ft') }}
                                {{ Form::text('lbs_ft', null, ['class' => 'form-control', 'placeholder' => 'lbs/ft', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('threads', 'Threads:') }}
                                {{ Form::text('threads', null, ['class' => 'form-control', 'placeholder' => 'Threads', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('range', 'Range:') }}
                                {{ Form::text('range', null, ['class' => 'form-control', 'placeholder' => 'Range', 'required']) }}
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
									<div class="col-12">
											<div class="row mt-1">
													<div class="col-3">
															<p>Nominal WallThickness:</p>
													</div>
													<div class="col-2">
														{{ Form::text('nominal_wall_thickness_mm', null, ['class' => 'form-control', 'placeholder' => 'mm', 'required']) }}
													</div>
													<div class="col-2">
														<p>mm</p>
													</div>
													<div class="col-2">
														{{ Form::text('nominal_wall_thickness_inch', null, ['class' => 'form-control', 'placeholder' => 'inch', 'required']) }}
													</div>
													<div class="col-2">
														<p>Inch</p>
													</div>
											</div>
									</div>
									<div class="col-12">
											<div class="row mt-1">
													<div class="col-3">
															<p>5% of Nominal WallThickness:</p>
													</div>
													<div class="col-2">
														{{ Form::text('wall_thickness_1_mm', null, ['class' => 'form-control', 'placeholder' => 'mm', 'required']) }}
													</div>
													<div class="col-2">
														<p>mm</p>
													</div>
													<div class="col-2">
														{{ Form::text('wall_thickness_1_inch', null, ['class' => 'form-control', 'placeholder' => 'inch', 'required']) }}
													</div>
													<div class="col-2">
														<p>Inch</p>
													</div>
											</div>
									</div>
									<div class="col-12">
											<div class="row mt-1">
													<div class="col-3">
															<p>12.5% of Nominal WallThickness:</p>
													</div>
													<div class="col-2">
														{{ Form::text('wall_thickness_2_mm', null, ['class' => 'form-control', 'placeholder' => 'mm', 'required']) }}
													</div>
													<div class="col-2">
														<p>mm</p>
													</div>
													<div class="col-2">
														{{ Form::text('wall_thickness_2_inch', null, ['class' => 'form-control', 'placeholder' => 'inch', 'required']) }}
													</div>
													<div class="col-2">
														<p>Inch</p>
													</div>
											</div>
									</div>
									<div class="col-12">
											<div class="row mt-1">
													<div class="col-3">
															<p>87.5% of Nominal WallThickness:</p>
													</div>
													<div class="col-2">
														{{ Form::text('wall_thickness_3_mm', null, ['class' => 'form-control', 'placeholder' => 'mm', 'required']) }}
													</div>
													<div class="col-2">
														<p>mm</p>
													</div>
													<div class="col-2">
														{{ Form::text('wall_thickness_3_inch', null, ['class' => 'form-control', 'placeholder' => 'inch', 'required']) }}
													</div>
													<div class="col-2">
														<p>Inch</p>
													</div>
											</div>
									</div>
							</div>
					</div>
			</div>
	</div>
</fieldset>
<h6>Step 4</h6>
<fieldset>
    <div class="card repeater">
        <div class="card-content collapse show">
						<div class="card-body">
							<table style="width: 127%; margin-left:-12px; text-align: center; display: contents; font-size: 75%;">
								<thead style="width: 100%;">
									<tr>
										<td class=" bg-dark p-0 border-dark white" style="width: 13%;">
											QUANTITY
										</td>
										<td class=" bg-dark p-0 border-dark white" style="width: 13%;">
											BODY
										</td>
										<td class=" bg-dark p-0 border-dark white" style="width: 13%;">
											COUPLING
										</td>
										<td class=" bg-dark p-0 border-dark white" style="width: 13%;">
											PIN
										</td>
										<td class=" bg-dark p-0 border-dark white" style="width: 48%;">
											REMARKS
										</td>
									</tr>
								</thead>
						
								<tbody data-repeater-list="inspection_data" id="inspection_data">
									@foreach(isset($model) && is_array($model->inspection_data) && count($model->inspection_data ) ?
									$model->inspection_data : $inspection_data_template as $item)
									<tr data-repeater-item>
										<td class="p-0 white">
											<table style="width: 100%; text-align: center;">
												<tr style="height: 22px;">
													<td class=" p-0 border-dark white">
														<input type="text" id="{{'input_1'}}" name="{{'input_1'}}" value="{{$item['input_1']}}"
															class="cell-input" placeholder="..." />
													</td>
												</tr>
											</table>
										</td>
										<td class="p-0 white">
											<table style="width: 100%; text-align: center;">
												<tr style="height: 22px;">
													<td class=" p-0 border-dark white">
														<input type="text" id="{{'input_2'}}" name="{{'input_2'}}" value="{{$item['input_2']}}"
															class="cell-input" placeholder="..." />
													</td>
												</tr>
											</table>
										</td>
										<td class="p-0 white">
											<table style="width: 100%; text-align: center;">
												<tr style="height: 22px;">
													<td class=" p-0 border-dark white">
														<input type="text" id="{{'input_3'}}" name="{{'input_3'}}" value="{{$item['input_3']}}"
															class="cell-input" placeholder="..." />
													</td>
												</tr>
											</table>
										</td>
										<td class="p-0 white">
											<table style="width: 100%; text-align: center;">
												<tr style="height: 22px;">
													<td class=" p-0 border-dark white">
														<input type="text" id="{{'input_4'}}" name="{{'input_4'}}" value="{{$item['input_4']}}"
															class="cell-input" placeholder="..." />
													</td>
												</tr>
											</table>
										</td>
										<td class="p-0 white">
											<table style="width: 100%; text-align: center;">
												<tr style="height: 22px;">
													<td class=" p-0 border-dark white">
														<input type="text" id="{{'input_5'}}" name="{{'input_5'}}" value="{{$item['input_5']}}"
															class="cell-input" placeholder="..." />
													</td>
												</tr>
											</table>
										</td>
										<td>
											<div class=" col-1">
												<button type="button" class="btn btn-danger" data-repeater-delete><i class="ft-x"></i></button>
											</div>
						
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
            <div class=" form-group overflow-hidden">
                <div class="col-12">
                    <button type="button" data-repeater-create class="btn btn-primary"><i
                                class="ft-plus"></i> Add
                    </button>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<h6>Step 5</h6>
<fieldset>
    <div class="card">
        <div class="card-content collapse show">
            <div class="card-body">
								<div class="row mt-1">
									<div class="col-3">
										<p>Abbreviation code used in this report</p>
									</div>
									<div class="col-2">
{{--										{{ Form::text('abbreviation_code', null, ['class' => 'form-control', 'placeholder' => '...', 'required']) }}--}}
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-3">
										<p>Total item inspected:</p>
									</div>
									<div class="col-2">
										{{ Form::text('total_items_inspected', null, ['class' => 'form-control', 'placeholder' => '...', 'required']) }}
									</div>
								</div>
								<div class="row mt-2">
									<div class="col-3">
										<p>Total item ready for use:</p>
									</div>
									<div class="col-2">
										{{ Form::text('total_items_ready', null, ['class' => 'form-control', 'placeholder' => '...', 'required']) }}
									</div>
								</div>
                <div class="row mt-3">
									<div class="col-12">
											<div class="form-group">
													<div class="controls">
															{{ Form::label('comment', 'Comment') }}
															{{ Form::textarea('comment', null, ['class' => 'form-control', 'rows' => 2, 'required' => '', 'placeholder' => 'Comments...', 'aria-invalid' => 'false', 'data-validation-required-message' => 'This field is required']) }}
													</div>
											</div>
									</div>
            </div>
        </div>
    </div>
</fieldset>


