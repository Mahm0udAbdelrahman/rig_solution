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
                    <div class="col-12 col-sm-4">
                        <div class="form-group mb-0">
                            <div class="controls">
                                <label>Survey Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
										<span class="input-group-text">
											<i class="ft-calendar"></i>
										</span>
                                    </div>
                                    {{ Form::text('survey_date', null, ['class' => 'form-control datepicker-default', 'id' => 'examination_date', 'placeholder' => 'Survey Date', 'required']) }}
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
    <div class="card repeater">
        <div class="card-content collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <div class="controls">
                                {{ Form::label('inspection_area', 'Inspection Area') }}
                                {{ Form::select('inspection_area', $inspectionAreaOptions, null, ['class' => 'form-control', 'id' => 'inspection_area', 'required', 'onchange' => 'showhideOtherInspectionArea()']) }}
                                <div class="help-block"></div>
                                <div id="other_inspection_area_section" style="display: {{ isset($model) && $model->inspection_area == 'other' ? '' : 'none' }};">
                                    {{ Form::text('other_inspection_area', null, ['class' => 'form-control', 'id' => 'other_inspection_area', 'placeholder' => 'Other Inspection Area']) }}
                                </div>
                            </div>
							<script>
								function showhideOtherInspectionArea() {
									var inspection_area =  $("#inspection_area").val();
									if (inspection_area === 'other') {
										$('#other_inspection_area_section').show();
									}
									else {
										$('#other_inspection_area_section').hide();
										$('#other_inspection_area').prop("disabled", true).val('');
									}
								}
							</script>
                        </div>
                    </div>
                </div>
<table style="width: 100%; margin-left:-12px; text-align: center; display: contents;">
	<thead style="width: 100%;">
		<tr>
			<th class="bg-dark p-0 border-dark white text-center" style="width: 5%;">Item</th>
			<th class="bg-dark p-0 border-dark white text-center" style="width: 15%;">Photo</th>
			<th class="bg-dark p-0 border-dark white text-center" style="width: 7.5%;">Description</th>
			<th class="bg-dark p-0 border-dark white text-center" style="width: 7.5%;">Photo Reference #</th>
			<th class="bg-dark p-0 border-dark white text-center" style="width: 19%;">Fastening Methods</th>
			<th class="bg-dark p-0 border-dark white text-center" style="width: 5%;">Condition</th>
			<th class="bg-dark p-0 border-dark white text-center" style="width: 17%;">Comment</th>
			<th class="bg-dark p-0 border-dark white text-center" style="width: 5%;">Frequency</th>
			<th class="bg-dark p-0 border-dark white text-center" style="width: 19%;">How To Inspect?</th>
		</tr>
	</thead>

    <tbody data-repeater-list="inspection_data">
        @foreach(isset($model) && is_array($model->inspection_data) && count($model->inspection_data) ? $model->inspection_data : [[]] as $item)
            <tr data-repeater-item>
                <td class="p-0 border-dark white" style="text-align: center;">
                    <input type="text" name="item" value="{{ isset($item['item']) ? $item['item'] : '' }}" class="cell-input" placeholder="..." />
                </td>
                <td class="p-0 border-dark white" style="text-align: center;">
                    <div>
                        <input type="file" name="photo" class="cell-input" onchange="previewImage(this)" accept="image/*" />
                        <img src="{{ isset($item['photo']) ? Storage::url($item['photo']) : '' }}" style="max-width: 100px; max-height: 100px;" class="photo_preview" />
                    </div>
                </td>
                <script>
                    function previewImage(input) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $(input).siblings('img.photo_preview').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                </script>
                <td class="p-0 border-dark white" style="text-align: center;">
                    <input type="text" name="description" value="{{ isset($item['description']) ? $item['description'] : '' }}" class="cell-input" placeholder="..." />
                </td>
                <td class="p-0 border-dark white" style="text-align: center;">
                    <input type="text" name="photo_reference" value="{{ isset($item['photo_reference']) ? $item['photo_reference'] : '' }}" class="cell-input" placeholder="..." />
                </td>
                <td class="p-0 border-dark white" style="text-align: center;">
                    <textarea name="fastening_methods" class="cell-input" placeholder="..." rows="6">{{ isset($item['fastening_methods']) ? $item['fastening_methods'] : '' }}</textarea>
                </td>
                <td class="p-0 border-dark white" style="text-align: center;">
                    <select name="condition" class="cell-input" style="width: 100%;">
                        <option value="pass" {{ isset($item['condition']) && $item['condition'] == 'pass' ? 'selected' : '' }}>Pass</option>
                        <option value="fail" {{ isset($item['condition']) && $item['condition'] == 'fail' ? 'selected' : '' }}>Fail</option>
                    </select>
                </td>
                <td class="p-0 border-dark white" style="text-align: center;">
                    <!-- <input type="text" name="comment" value="{{ isset($item['comment']) ? $item['comment'] : '' }}" class="cell-input" placeholder="..." /> -->
										<textarea name="comment" class="cell-input" placeholder="..." rows="6">{{ isset($item['comment']) ? $item['comment'] : ''  }}</textarea>

                </td>
                <td class="p-0 border-dark white" style="text-align: center;">
                    <input type="text" name="frequency" value="{{ isset($item['frequency']) ? $item['frequency'] : '' }}" class="cell-input" placeholder="..." />
                </td>
                <td class="p-0 border-dark white" style="text-align: center;">
                    <textarea name="how_to_inspect" class="cell-input" placeholder="..." rows="6">{{ isset($item['how_to_inspect']) ? $item['how_to_inspect'] : '' }}</textarea>
                </td>
                <td>
                    <div class="col-1">
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
