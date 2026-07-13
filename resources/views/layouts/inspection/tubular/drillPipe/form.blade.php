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
                                        {!! Form::label($method, $method) !!}
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
						<div class="col-12 col-sm-4">
							<div class="form-group">
								<div class="controls">
									{{ Form::label('joint_description', 'Joint Description:') }}
									{{ Form::text('joint_description', null, ['class' => 'form-control', 'placeholder' => 'Joint Description',
									'required']) }}
								</div>
							</div>
						</div>
						<div class="col-12 col-sm-4">
							<div class="form-group">
								<div class="controls">
									{{ Form::label('internal_service_order', 'Internal Service Order:') }}
									{{ Form::text('internal_service_order', null, ['class' => 'form-control', 'placeholder' => 'Internal Service Order',
									'required']) }}
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
                                {{ Form::label('joint_class', 'Joint Class:') }}
                                {{ Form::text('joint_class', null, ['class' => 'form-control', 'placeholder' => 'Joint Class', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('grade', 'Grade:') }}
                                {{ Form::text('grade', null, ['class' => 'form-control', 'placeholder' => 'Grade', 'required']) }}
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
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('weight', 'Weight:') }}
                                {{ Form::text('weight', null, ['class' => 'form-control', 'placeholder' => 'Weight', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('nom_w_t', 'Nom_w_t:') }}
                                {{ Form::text('nom_w_t', null, ['class' => 'form-control', 'placeholder' => 'Nom_w_t', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('conn', 'Conn:') }}
                                {{ Form::text('conn', null, ['class' => 'form-control', 'placeholder' => 'Conn', 'required']) }}
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
                                {{ Form::label('joint_od', 'Joint (OD):') }}
                                {{ Form::text('joint_od', null, ['class' => 'form-control', 'placeholder' => 'Joint (OD)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('joint_id', 'Joint (ID):') }}
                                {{ Form::text('joint_id', null, ['class' => 'form-control', 'placeholder' => 'Joint (ID)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('t_joint_od', 'Tool Joint (OD):') }}
                                {{ Form::text('t_joint_od', null, ['class' => 'form-control', 'placeholder' => 't_joint_od', 'required']) }}
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
            <div class="card-body x_scroller">

                <table style="width: 127%; margin-left:-12px; text-align: center;">
                    <thead style="width: 100%;">
                    <tr>
                        <td  class=" bg-dark p-0 border-dark white" style="width: 8%;">
                            <table style="width: 100%; text-align: center;">
                                <tr>
                                    <td colspan="3" style="height: 62px;">Joints Details</td>
                                </tr>
                                <tr>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 50%">joint NO</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 50%">S/No</td>
                                </tr>
                            </table>
                        </td>
                        <td class=" bg-dark p-0 border-dark white" style="width: 42%;">
                            <table style="width: 100%; text-align: center;">
                                <tr>Box or Upper Connection</tr>
                                <tr>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">Tong<br/>min</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">D<br/>min</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">Df<br/>max</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">seal<br/>min</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">SHW<br/>min</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">Qc<br/>max</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">CBL<br/>min</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">CBW<br/>min</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">Lbc<br/>min</td>
                                    <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 7.6%; height: 63px;">F.rep.</td>
                                    <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 7.6%">Reface</td>
                                    <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 7.6%">HB</td>
                                    <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 7.6%">Cond.</td>
                                </tr>
                                <tr>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%; height: 22px;">
                                        {{ Form::text('standards[input_1]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                        {{ Form::text('standards[input_2]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                        {{ Form::text('standards[input_3]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                        {{ Form::text('standards[input_4]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                        {{ Form::text('standards[input_5]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                        {{ Form::text('standards[input_6]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                        {{ Form::text('standards[input_7]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                        {{ Form::text('standards[input_8]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 7.6%">
                                        {{ Form::text('standards[input_9]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                    {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                    {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                    {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                </tr>
                            </table>

                        </td>
                        <td class=" bg-dark p-0 border-dark white" style="width: 42%;">
                            <table style="width: 100%; text-align: center;">
                                <tr>Pin or Lower Connection</tr>
                                <tr>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 40px;">Tong<br/>min</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 40px;">DM<br/>min</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 40px;">d<br/>max</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 40px;">Df<br/>max</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 40px;">seal<br/>min</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 40px;">Lpc<br/>max</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 40px;">PNL<br/>max</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 40px;">PCD<br/>max</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 40px;">PND<br/>max</td>
                                    <td class=" bg-dark p-0 border-dark white" style="width: 40px;">PL<br/>+/-</td>
                                    <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 40px; height: 63px;">f.repair</td>
                                    <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 40px;">Reface</td>
                                    <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 40px;">HB</td>
                                    <td class=" bg-dark p-0 border-dark white vertical-cell" style="width: 40px;">Cond.</td>
                                </tr>
                                <tr>
                                    <td class=" bg-dark p-0 border-dark white" style="; height: 22px;">
                                        {{ Form::text('standards[input_10]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="">
                                        {{ Form::text('standards[input_11]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="">
                                        {{ Form::text('standards[input_12]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="">
                                        {{ Form::text('standards[input_13]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="">
                                        {{ Form::text('standards[input_14]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="">
                                        {{ Form::text('standards[input_15]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="">
                                        {{ Form::text('standards[input_16]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="">
                                        {{ Form::text('standards[input_17]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    <td class=" bg-dark p-0 border-dark white" style="">
                                        {{ Form::text('standards[input_18]', null, ['class' => 'form-control standards', 'placeholder' => '...']) }}
                                    </td>
                                    {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                    {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                    {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                    {{--<td class=" bg-dark p-0 border-dark white" style="width: 7.6%"></td>--}}
                                </tr>
                            </table>

                        </td>
                        <td  class=" bg-dark p-0 border-dark white" style="width: 8%;">
                            <table style="width: 100%; text-align: center;">
                                <tr>Pipe Body</tr>
                                <tr>
                                    <td class=" bg-dark p-0 border-dark white" style="">main Rem. W.T</td>
                                    <td class=" bg-dark p-0 border-dark white" style="">OD<br/>Wear</td>
                                    <td class=" bg-dark p-0 border-dark white vertical-cell" style="">EMI</td>
                                    <td class=" bg-dark p-0 border-dark white vertical-cell" style="">S.Area</td>
                                    <td class=" bg-dark p-0 border-dark white" style="">Corr.<br/>IN</td>
                                    <td class=" bg-dark p-0 border-dark white" style="">Corr.<br/>Out</td>
                                    <td class=" bg-dark p-0 border-dark white" style="">IPC</td>
                                    <td class=" bg-dark p-0 border-dark white" style="">Bent</td>
                                </tr>
                                <tr style="height: 22px;"></tr>
                            </table>
                        </td>
                    </tr>
                    </thead>

                    <tbody data-repeater-list="inspection_data">
                    @foreach(isset($model) && is_array($model->inspection_data) && count($model->inspection_data )  ? $model->inspection_data : $inspection_data_template as $item)
                    <tr data-repeater-item>
                        <td class="p-0 white" style="height: 22px;">
                            <table style="width: 100%; text-align: center;">
                                <tr>
                                    <td class=" p-0 border-dark white" style="width: 50%">
                                        <input type="text" id="{{'input_1'}}" name="{{'input_1'}}"  value="{{$item['input_1']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class=" p-0 border-dark white" style="width: 50%">
                                        <input type="text" id="{{'input_2'}}" name="{{'input_2'}}"  value="{{$item['input_2']}}" class="cell-input" placeholder="..." />
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="height: 22px;">
                            <table style="width: 100%; text-align: center;">
                                <tr>
                                    <td class="p-0 border-dark white" style="width: 7.6%; height: 22px;">
                                        <input type="text" id="{{'input_3'}}" name="{{'input_3'}}"  value="{{$item['input_3']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <input type="text" id="{{'input_4'}}" name="{{'input_4'}}"  value="{{$item['input_4']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <input type="text" id="{{'input_5'}}" name="{{'input_5'}}"  value="{{$item['input_5']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <input type="text" id="{{'input_6'}}" name="{{'input_6'}}"  value="{{$item['input_6']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <input type="text" id="{{'input_7'}}" name="{{'input_7'}}"  value="{{$item['input_7']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <input type="text" id="{{'input_8'}}" name="{{'input_8'}}"  value="{{$item['input_8']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <input type="text" id="{{'input_9'}}" name="{{'input_9'}}"  value="{{$item['input_9']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <input type="text" id="{{'input_10'}}" name="{{'input_10'}}" value="{{$item['input_10']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <input type="text" id="{{'input_11'}}" name="{{'input_11'}}" value="{{$item['input_11']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <input type="text" id="{{'input_12'}}" name="{{'input_12'}}" value="{{$item['input_12']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <input type="text" id="{{'input_13'}}" name="{{'input_13'}}" value="{{$item['input_13']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <input type="text" id="{{'input_14'}}" name="{{'input_14'}}" value="{{$item['input_14']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 7.6%">
                                        <input type="text" id="{{'input_15'}}" name="{{'input_15'}}" value="{{$item['input_15']}}" class="cell-input" placeholder="..." />
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="height: 22px;">
                            <table style="width: 100%; text-align: center;">
                                <tr>
                                    <td class="p-0 border-dark white" style="; height: 22px;">
                                        <input type="text" id="{{'input_16'}}" name="{{'input_16'}}" value="{{$item['input_16']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="; height: 22px;">
                                        <input type="text" id="{{'input_17'}}" name="{{'input_17'}}" value="{{$item['input_17']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="">
                                        <input type="text" id="{{'input_18'}}" name="{{'input_18'}}" value="{{$item['input_18']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="">
                                        <input type="text" id="{{'input_19'}}" name="{{'input_19'}}" value="{{$item['input_19']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="">
                                        <input type="text" id="{{'input_20'}}" name="{{'input_20'}}" value="{{$item['input_20']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="">
                                        <input type="text" id="{{'input_21'}}" name="{{'input_21'}}" value="{{$item['input_21']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="">
                                        <input type="text" id="{{'input_22'}}" name="{{'input_22'}}" value="{{$item['input_22']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="">
                                        <input type="text" id="{{'input_23'}}" name="{{'input_23'}}" value="{{$item['input_23']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="">
                                        <input type="text" id="{{'input_24'}}" name="{{'input_24'}}" value="{{$item['input_24']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="">
                                        <input type="text" id="{{'input_25'}}" name="{{'input_25'}}" value="{{$item['input_25']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="">
                                        <input type="text" id="{{'input_26'}}" name="{{'input_26'}}" value="{{$item['input_26']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="">
                                        <input type="text" id="{{'input_27'}}" name="{{'input_27'}}" value="{{$item['input_27']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="">
                                        <input type="text" id="{{'input_28'}}" name="{{'input_28'}}" value="{{$item['input_28']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="">
                                        <input type="text" id="{{'input_29'}}" name="{{'input_29'}}" value="{{$item['input_29']}}" class="cell-input" placeholder="..." />
                                    </td>
                                </tr>
                            </table>

                        </td>
                        <td>
                            <table style="width: 100%; text-align: center;">
                                    <td class="p-0 border-dark white" style="width: 12.5%">
                                        <input type="text" id="{{'input_30'}}" name="{{'input_30'}}" value="{{$item['input_30']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 12.5%">
                                        <input type="text" id="{{'input_31'}}" name="{{'input_31'}}" value="{{$item['input_31']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 12.5%">
                                        <input type="text" id="{{'input_32'}}" name="{{'input_32'}}" value="{{$item['input_32']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 12.5%">
                                        <input type="text" id="{{'input_33'}}" name="{{'input_33'}}" value="{{$item['input_33']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 12.5%">
                                        <input type="text" id="{{'input_34'}}" name="{{'input_34'}}" value="{{$item['input_34']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 12.5%">
                                        <input type="text" id="{{'input_35'}}" name="{{'input_35'}}" value="{{$item['input_35']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 12.5%">
                                        <input type="text" id="{{'input_36'}}" name="{{'input_36'}}" value="{{$item['input_36']}}" class="cell-input" placeholder="..." />
                                    </td>
                                    <td class="p-0 border-dark white" style="width: 12.5%">
                                        <input type="text" id="{{'input_37'}}" name="{{'input_37'}}" value="{{$item['input_37']}}" class="cell-input" placeholder="..." />
                                    </td>
                                </tr>
                            </table>

                        </td>
                        <td>
                            <div class=" col-1">
                                <button type="button" class="btn btn-danger" data-repeater-delete><i
                                            class="ft-x"></i></button>
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
                <h4>Final Conclusion</h4>
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            {{ Form::label('comment', 'Comment') }}
                                            {{ Form::textarea('comment', null, ['class' => 'form-control', 'rows' => 2, 'required' => '', 'placeholder' => 'Conclusion...', 'aria-invalid' => 'false', 'data-validation-required-message' => 'This field is required']) }}
                                        </div>
                                    </div>
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
<script src="{{asset('app-assets/js/scripts/forms/form-repeater.js')}}"></script>
<script>
  $('.repeater').each(function () {
    var $repeater = $(this);
    if ($repeater.data('inspectionTubularRepeaterBound')) {
      return;
    }

    $repeater.data('inspectionTubularRepeaterBound', true);
    $repeater.repeater({
      show: function () {
        $(this).slideDown();
      },
      hide: function (remove) {
        var $item = $(this);

        if (window.Swal && typeof window.Swal.fire === 'function') {
          window.Swal.fire({
            title: 'Delete row?',
            text: 'This row will be removed from the report.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#f44336'
          }).then(function (result) {
            if (result.isConfirmed) {
              $item.slideUp(150, function () {
                remove.call(this);
              });
            }
          });
          return;
        }

        if (confirm('Delete this row?')) {
          $item.slideUp(150, function () {
            remove.call(this);
          });
        }
      }
    });
  });
</script>

