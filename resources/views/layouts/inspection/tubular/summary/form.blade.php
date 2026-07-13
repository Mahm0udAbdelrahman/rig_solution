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
                <div class="skin skin-square form-group mt-1">
                    <div class="controls">
                        <div class="row">
                            @foreach($equipments as $equipment)
                                <div class="col-md-3 col-sm-12">
                                    <fieldset>
                                        {!! Form::checkbox('equipment_used[]', $equipment, isset($model)? in_array($equipment, $model->equipment_used): null, ['id' => 'equipment'.$equipment, 'class' => 'equipment_used']) !!}
                                        {!! Form::label($equipment, $equipment) !!}
                                    </fieldset>
                                </div>
                            @endforeach
                            <div class="col-md-3 col-sm-12">
                                <fieldset>
                                    <input type="checkbox" class="equipment_used" id="other_equipment_checkbox" data-id="other" {{isset($model->other_equipment)? 'checked' : ''}}>
                                    <label for="other_equipment_checkbox">Other</label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row" id="other_equipment_section" style="display: {{isset($model) && $model->other_equipment ? 'block' : 'none'}};;">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <div class="controls">
                                        {{ Form::label('other_equipment', 'If Other Equipment') }}
                                        {{ Form::text('other_equipment', null, [
                                            'id' => 'other_equipment',
                                            'class' => 'form-control',
                                            'placeholder' => 'other equipment',
                                            'disabled' =>  isset($model->other_equipment) ? false : true
                                        ]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>

            <div class="card repeater">
                <div class="card-content collapse show">
                    <div class="card-body" data-repeater-list="equipment_no">
                        <div class="row">
                            <div class="col-7">
                                <div class="form-group mb-0">
                                    <label>Equipment No.</label>
                                </div>
                            </div>
                            <div class=" col-1"></div>
                        </div>
                        @foreach(isset($model) ? $model->equipment_no : [['equipment_no_value' => '']] as $item)
                            <div class="row" data-repeater-item>
                                <div class="col-7">
                                    <div class="form-group mb-0">
                                        <div class="controls">
                                            <input type="text" id="equipment_no_value" name="equipment_no_value"
                                                   class="form-control" placeholder="Equipment No."
                                                   value="{{$item['equipment_no_value']}}"/>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-1">
                                    <button type="button" class="btn btn-danger" data-repeater-delete><i
                                                class="ft-x"></i></button>
                                </div>
                            </div>
                        @endforeach
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
                        <div class="row mt-1">
                            <div class="col-4">
                                <p>Pipe Status:</p>
                            </div>
                            <div class="col-4">
                                <div class="custom-control custom-radio">
                                    {{ Form::radio('pipe_status', 'New', null, ['class' => 'custom-control-input', 'id' => 'pipe_status_new', 'required']) }}
                                    {{ Form::label('pipe_status_new', 'New', ['class' => 'custom-control-label']) }}
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="custom-control custom-radio">
                                    {{ Form::radio('pipe_status', 'Used', null, ['class' => 'custom-control-input', 'id' => 'pipe_status_used', 'required']) }}
                                    {{ Form::label('pipe_status_used', 'Used', ['class' => 'custom-control-label']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('nominal_size', 'Nominal Size (OD):') }}
                                {{ Form::text('nominal_size', null, ['class' => 'form-control', 'placeholder' => 'Nominal Size (OD)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('nom_wall', 'Nom Wall:') }}
                                {{ Form::text('nom_wall', null, ['class' => 'form-control', 'placeholder' => 'Nom Wall', 'required']) }}
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
                                {{ Form::label('pipe_grade', 'Pipe Grade:') }}
                                {{ Form::text('pipe_grade', null, ['class' => 'form-control', 'placeholder' => 'Pipe Grade', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('tool_joint_od', 'ToolJoint (OD):') }}
                                {{ Form::text('tool_joint_od', null, ['class' => 'form-control', 'placeholder' => 'ToolJoint (OD)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('tool_joint_id', 'ToolJoint (ID):') }}
                                {{ Form::text('tool_joint_id', null, ['class' => 'form-control', 'placeholder' => 'ToolJoint (ID)', 'required']) }}
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
                                {{ Form::label('threads', 'Threads:') }}
                                {{ Form::text('threads', null, ['class' => 'form-control', 'placeholder' => 'Threads', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('class', 'Class:') }}
                                {{ Form::text('class', null, ['class' => 'form-control', 'placeholder' => 'Class', 'required']) }}
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
                        <div class="row mt-1">
                            <div class="col-4">
                                <p>HardFaced:</p>
                            </div>
                            <div class="col-2">
                                <div class="custom-control custom-radio">
                                    {{ Form::radio('hard_faced', 'Yes', null, ['class' => 'custom-control-input', 'id' => 'hard_faced_yes', 'required']) }}
                                    {{ Form::label('hard_faced_yes', 'Yes', ['class' => 'custom-control-label']) }}
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="custom-control custom-radio">
                                    {{ Form::radio('hard_faced', 'No', null, ['class' => 'custom-control-input', 'id' => 'hard_faced_no']) }}
                                    {{ Form::label('hard_faced_no', 'No', ['class' => 'custom-control-label']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-8">
                        <div class="row mt-1">
                            <div class="col-2">
                                <p>Coated:</p>
                            </div>
                            <div class="col-2">
                                <div class="custom-control custom-radio">
                                    {{ Form::radio('coated', 'Good',null, ['class' => 'custom-control-input', 'id' => 'coated_good', 'required']) }}
                                    {{ Form::label('coated_good', 'Good', ['class' => 'custom-control-label']) }}
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="custom-control custom-radio">
                                    {{ Form::radio('coated', 'Fair', null, ['class' => 'custom-control-input', 'id' => 'coated_fair']) }}
                                    {{ Form::label('coated_fair', 'Fair', ['class' => 'custom-control-label']) }}
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="custom-control custom-radio">
                                    {{ Form::radio('coated', 'Poor', null, ['class' => 'custom-control-input', 'id' => 'coated_poor']) }}
                                    {{ Form::label('coated_poor', 'Poor', ['class' => 'custom-control-label']) }}
                                </div>
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
    <h3>A- Joints Details</h3>
    <div class="card">
        <div class="card-content collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('jp_ready_use', 'Joints Premium Class Ready For Use (JTS):') }}
                                {{ Form::text('jp_ready_use', null, ['class' => 'form-control', 'placeholder' => 'Joints Premium (JTS)', 'data-validation--message' => 'This field is', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('jp_ready_use_comment', 'Comment') }}
                                {{ Form::text('jp_ready_use_comment', null, ['class' => 'form-control', 'placeholder' => 'Joints Premium Comment', 'data-validation--message' => 'This field is', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('jp_need_recut', 'Joints Premium, Need Re-Cut:') }}
                                {{ Form::text('jp_need_recut', null, ['class' => 'form-control', 'placeholder' => 'Need Re-Cut (JTS)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('jp_need_recut_comment', 'Comment') }}
                                {{ Form::text('jp_need_recut_comment', null, ['class' => 'form-control', 'placeholder' => 'Need Re-Cut Comment', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('jp_recut_pin_box', 'Joints Need Re-Cut Pin & Box (JTS):') }}
                                {{ Form::text('jp_recut_pin_box', null, ['class' => 'form-control', 'placeholder' => 'Re-Cut Pin & Box (JTS)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('jp_recut_pin_box_comment', 'Comment') }}
                                {{ Form::text('jp_recut_pin_box_comment', null, ['class' => 'form-control', 'placeholder' => 'Comment', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('jp_recut_pin', 'Joints Need Re-Cut Pin Only (PIN):') }}
                                {{ Form::text('jp_recut_pin', null, ['class' => 'form-control', 'placeholder' => 'Re-Cut Pin Only (PIN)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('jp_recut_pin_comment', 'Comment') }}
                                {{ Form::text('jp_recut_pin_comment', null, ['class' => 'form-control', 'placeholder' => 'Comment', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('jp_recut_box', 'Joints Need Re-Cut Box Only (Box):') }}
                                {{ Form::text('jp_recut_box', null, ['class' => 'form-control', 'placeholder' => 'Re-Cut Box Only (Box)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('jp_recut_box_comment', 'Comment') }}
                                {{ Form::text('jp_recut_box_comment', null, ['class' => 'form-control', 'placeholder' => 'Comment', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('joints_class_2', 'Joints Class II (JTS):') }}
                                {{ Form::text('joints_class_2', null, ['class' => 'form-control', 'placeholder' => 'Class II (JTS)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('joints_class_2_comment', 'Comment') }}
                                {{ Form::text('joints_class_2_comment', null, ['class' => 'form-control', 'placeholder' => 'Comment', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('joints_class_3', 'Joints Class III (JTS):') }}
                                {{ Form::text('joints_class_3', null, ['class' => 'form-control', 'placeholder' => 'Class III (JTS)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('joints_class_3_comment', 'Comment') }}
                                {{ Form::text('joints_class_3_comment', null, ['class' => 'form-control', 'placeholder' => 'Comment', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('joints_junk', 'Joints Junk (JTS):') }}
                                {{ Form::text('joints_junk', null, ['class' => 'form-control', 'placeholder' => 'Junk (JTS)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('joints_junk_comment', 'Comment') }}
                                {{ Form::text('joints_junk_comment', null, ['class' => 'form-control', 'placeholder' => 'Comment', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('total_joints', 'Total Joints Inspected (JTS):') }}
                                {{ Form::text('total_joints', null, ['class' => 'form-control', 'placeholder' => 'Total Joints (JTS)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('total_joints_comment', 'Comment') }}
                                {{ Form::text('total_joints_comment', null, ['class' => 'form-control', 'placeholder' => 'Comment', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</fieldset>
<h6>Step 5</h6>
<fieldset>
    <h4>B- Repaired Details</h4>
    <div class="card">
        <div class="card-content collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('connections_manually', 'Connections Manually Thread Repaired(Conns):') }}
                                {{ Form::text('connections_manually', null, ['class' => 'form-control', 'placeholder' => 'Connections Manually Thread Repaired', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('connections_manually_comment', 'Comment') }}
                                {{ Form::text('connections_manually_comment', null, ['class' => 'form-control', 'placeholder' => 'Comment', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('total_boxs', 'Total Boxes Refaced(Box):') }}
                                {{ Form::text('total_boxs', null, ['class' => 'form-control', 'placeholder' => 'Boxes Refaced(Box)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('total_boxs_comment', 'Comment') }}
                                {{ Form::text('total_boxs_comment', null, ['class' => 'form-control', 'placeholder' => 'Comment', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('total_pins', 'Total Pins Refaced(Pin):') }}
                                {{ Form::text('total_pins', null, ['class' => 'form-control', 'placeholder' => 'Pins Refaced(Pin)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('total_pins_comment', 'Comment') }}
                                {{ Form::text('total_pins_comment', null, ['class' => 'form-control', 'placeholder' => 'Comment', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('total_straightened', 'Joints Straightened(JTS):') }}
                                {{ Form::text('total_straightened', null, ['class' => 'form-control', 'placeholder' => 'Joints Straightened(JTS)', 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('total_straightened_comment', 'Comment') }}
                                {{ Form::text('total_straightened_comment', null, ['class' => 'form-control', 'placeholder' => 'Comment', 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
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
</fieldset>

