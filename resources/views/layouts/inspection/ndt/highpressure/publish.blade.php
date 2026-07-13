@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
<section id="validation">
	<div class="row">
		<div class="col-12">
			<form action="#" class="steps-validation wizard-circle" enctype="multipart/form-data">
				@method('PUT')
				<h6>Step 1</h6>
				<fieldset>
					<div class="card">
						<div class="card-content collapse show">
							<div class="card-body">
								<div class="row">
									<div class="col-12 col-sm-4">
										<div class="form-group mb-0">
											<div class="controls">
												<label>JCF Number</label>

												<select class="form-control" id="lcr_1" name="lcr_1" required="">
													@foreach($jobrequests as $jobrequest)
													<option value="{{$jobrequest->id}}" @if($jobrequest->id ===
														$highPressure->job_request_id) selected
														@endif>{{$jobrequest->code}}</option>
													@endforeach
												</select>
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-4">
										<div class="form-group mb-0">
											<div class="controls">
												<label>Purchase Order</label>
												<input disabled type="text" id="purchaseOrder" name="nhpr_2"
													class="form-control" placeholder="Purchase Order"
													value="{{$highPressure->nhpr_2}}">
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-4">
										<div class="form-group mb-0">
											<div class="controls">
												<label>Report No.</label>
												<div class="clearfix">
													<input type="text" id="precode" class="form-control pr-0"
														placeholder="Report No"
														value="{{$highPressure->job_request->code}} /" required=""
														data-validation-required-message="This code field is required"
														aria-invalid="false" disabled style="width: 83px; float: left;">
													<input type="text" id="code" name="code" class="form-control pl-0"
														placeholder="." value="{{$highPressure->code}}" required=""
														data-validation-required-message="This code field is required"
														aria-invalid="false" disabled style="width: auto; float: left;">
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
												<label>Name of employer for whom the examination was made</label>
												<input type="text"
													placeholder="Name of employer for whom the examination was made"
													class="form-control" id="cliname" name="cliname" disabled />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-6">
										<div class="form-group mb-0">
											<div class="controls">
												<label>Address of premises at examination was made</label>
												<input type="text" id="cliloc" name="cliloc" class="form-control"
													value="" placeholder="Address of premises at examination was made"
													disabled />
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
													<input type="text" class="form-control datepicker-default"
														id="lcr_60" name="lcr_60" placeholder="Examination Date"
														required="" value="{{$highPressure->nhpr_6}}"
														data-validation-required-message="This field is required" />
												</div>
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-4">
										<div class="form-group mb-0">
											<div class="controls">
												<label>Next Examination Date / <span id="months">12</span>
													Months</label>
												<div style="display: inline-block; float: right;">
													<input type="checkbox" class="switchery mr-1" id="suporcli"
														name="suporcli" checked>
												</div>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i
																class="ft-calendar"></i></span>
													</div>
													<input type="text" class="form-control dp-date-range-to" id="lcr_70"
														name="lcr_70" placeholder="Next Examination Date" required=""
														data-validation-required-message="This field is required"
														value="{{$highPressure->nhpr_7}}" disabled />
												</div>
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-4">
										<div class="form-group mb-0">
											<div class="controls">
												<label>Work location</label>
												<input type="text" id="deploc" name="deploc" class="form-control"
													placeholder="Work location" value="" required=""
													data-validation-required-message="This field is required"
													disabled />
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
									<div class="col-12">
										<div class="form-group">
											<div class="controls">
												<label>Description</label>
												<textarea id="nhpr_9" name="nhpr_9" class="form-control"
													placeholder="Description">{{$highPressure->desc}}</textarea>
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
										<div class="form-group">
											<div class="controls">
												<label>Identification No</label>
												<input type="text" id="nhpr_10" name="nhpr_10" class="form-control"
													placeholder="Identification No" value="{{$highPressure->nhpr_10}}"
													data-validation--message="This field is " />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-content">
							<div class="card-body">
								<h6 class="mb-1">Specification</h6>
								<div class="skin skin-square form-group mt-1">
									<div class="controls">
										<div class="row">
											@foreach($specifications as $specification)
											<div class="col-md-3 col-sm-12">
												<fieldset>
													<input type="checkbox" class="nhpr_11" name="nhpr_11[]" value="{{$specification->code}}"
														id="{{strtolower(str_replace(' ', '-', $specification->name))}}"
														@if(in_array($specification->code, json_decode($highPressure->nhpr_11)))
													checked @endif
													>
													<label
														for="{{strtolower(str_replace(' ', '-', $specification->name))}}">{{$specification->name}}</label>
												</fieldset>
											</div>
											@endforeach
										</div>
										<!-- <hr /> -->
										<div class="row">
											<div class="col-12">
												<div class="form-group" id="other_specification_section">
													<div class="controls">
														<label>If Other</label>
														<input type="text" id="nhpr_12" name="nhpr_12"
															class="form-control" placeholder="If Other"
															value="{{$highPressure->nhpr_12}}"
															data-validation--message="This field is " />
														<div class="help-block"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<hr />
									<div class="row">
										<div class="col-12 col-sm-6">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label>Edition</label>
                                                    <input type="text" id="edition" name="edition" class="form-control"
                                                           placeholder="Edition" value="{{$highPressure->edition}}" required=""
                                                           data-validation-required-message="This field is required"
                                                    />
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
						<div class="card-content">
							<div class="card-body">
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<div class="controls">
												<label>Acceptance Criteria</label>
												<input type="text" id="nhpr_13" name="nhpr_13" class="form-control"
													placeholder="Acceptance Criteria"
													value="{{$highPressure->acceptance}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<div class="controls">
												<label>Drawing Number</label>
												<input type="text" id="nhpr_14" name="nhpr_14" class="form-control"
													placeholder="Drawing Number" value="{{$highPressure->nhpr_14}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-content">
							<div class="card-body">
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<div class="controls">
												<label>Material</label>
												<input type="text" id="nhpr_15" name="nhpr_15" class="form-control"
													placeholder="Material" value="{{$highPressure->nhpr_15}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<div class="controls">
												<label>Material Thickness</label>
												<input type="text" id="nhpr_16" name="nhpr_16" class="form-control"
													placeholder="Material Thickness" value="{{$highPressure->nhpr_16}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<div class="controls">
												<label>Surface Condition</label>
												<input type="text" id="nhpr_17" name="nhpr_17" class="form-control"
													placeholder="Surface Condition" value="{{$highPressure->nhpr_17}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<div class="controls">
												<label>Surface Temperature</label>
												<input type="text" id="nhpr_18" name="nhpr_18" class="form-control"
													placeholder="Surface Temperature" value="{{$highPressure->nhpr_18}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<h6>Equipment instrument</h6>
					<div class="card">
						<div class="card-content">
							<div class="card-body">
								<div class="row">
									<div class="col-4">
										<div class="form-group">
											<div class="controls">
												<label>Model</label>
												<input type="text" id="nhpr_19" name="nhpr_19" class="form-control"
													placeholder="Model" value="{{$highPressure->nhpr_19}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<div class="controls">
												<label>Serial Number</label>
												<input type="text" id="nhpr_20" name="nhpr_20" class="form-control"
													placeholder="Serial Number" value="{{$highPressure->nhpr_20}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<div class="controls">
												<label>Manufacturer</label>
												<input type="text" id="nhpr_21" name="nhpr_21" class="form-control"
													placeholder="Manufacturer" value="{{$highPressure->nhpr_21}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<div class="form-group">
											<div class="controls">
												<label>Coupling Type</label>
												<input type="text" id="nhpr_22" name="nhpr_22" class="form-control"
													placeholder="Coupling Type" value="{{$highPressure->nhpr_22}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<div class="controls">
												<label>Sound Velocity</label>
												<input type="text" id="nhpr_23" name="nhpr_23" class="form-control"
													placeholder="Sound Velocity" value="{{$highPressure->nhpr_23}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<div class="controls">
												<label>Search Unit</label>
												<input type="text" id="nhpr_24" name="nhpr_24" class="form-control"
													placeholder="Search Unit" value="{{$highPressure->nhpr_24}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<div class="form-group">
											<div class="controls">
												<label>Probe Frequency</label>
												<input type="text" id="nhpr_25" name="nhpr_25" class="form-control"
													placeholder="Probe Frequency" value="{{$highPressure->nhpr_25}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<div class="controls">
												<label>Probe Dia.</label>
												<input type="text" id="nhpr_26" name="nhpr_26" class="form-control"
													placeholder="Probe Dia." value="{{$highPressure->nhpr_26}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<div class="controls">
												<label>Calibration Block</label>
												<input type="text" id="nhpr_27" name="nhpr_27" class="form-control"
													placeholder="Search Unit" value="{{$highPressure->nhpr_27}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>


					<div class="card">
						<div class="card-content">
							<div class="card-body">
								<div class="row">
									<div class="col-6">

										<div class="form-group">
											<div class="controls">
												<label>Calibration Sheet Attached:</label>
												<div class="row input-group">
													<div class="col-md-5 ml-2 custom-control custom-radio">
														<input type="radio" class="custom-control-input" id="nhpr_28_y"
															name="nhpr_28" value="nhpr_28_y"
															{{$highPressure->checkbox_yes($highPressure->nhpr_28)}}
														required>
														<label class="custom-control-label" for="nhpr_28_y">Yes</label>
													</div>
													<div class="col-md-5 custom-control custom-radio">
														<input type="radio" class="custom-control-input" id="nhpr_28_n"
															name="nhpr_28" value="nhpr_28_n"
															{{$highPressure->checkbox_no($highPressure->nhpr_28)}}>
														<label class="custom-control-label" for="nhpr_28_n">No</label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<div class="controls">
												<label>Sketch Attached</label>
												<div class="row input-group">
													<div class="col-md-5 ml-2 custom-control custom-radio">
														<input type="radio" class="custom-control-input" id="nhpr_29_y"
															name="nhpr_29" value="nhpr_29_y"
															{{$highPressure->checkbox_yes($highPressure->nhpr_29)}}
														required>
														<label class="custom-control-label" for="nhpr_29_y">Yes</label>
													</div>
													<div class="col-md-5 custom-control custom-radio">
														<input type="radio" class="custom-control-input" id="nhpr_29_n"
															name="nhpr_29" value="nhpr_29_n"
															{{$highPressure->checkbox_no($highPressure->nhpr_29)}}>
														<label class="custom-control-label" for="nhpr_29_n">No</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-12">
										<div class="form-group">
											<div class="controls">
												<label>Calibration Due</label>
												<input type="text" id="nhpr_30" name="nhpr_30" class="form-control"
													placeholder="Calibration Due" value="{{$highPressure->nhpr_30}}"
													data-validation--message="This field is " />
												<div class="help-block"></div>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>


				</fieldset>
				<h6>Step 3</h6>
				<fieldset>
					<div class="card repeater">
						<div class="card-content collapse show">
							<div class="card-body" data-repeater-list="payments">
								<div class="row">
									<div class="col-2">
										<div class="form-group mb-0">
											<label>Point Description</label>
										</div>
									</div>
									<div class="col-1">
										<div class="form-group mb-0">
											<label>Line Size</label>
										</div>
									</div>
									<div class="col-1">
										<div class="form-group mb-0">
											<label>Minimum Wall Thickness</label>
										</div>
									</div>
									<div class="col-1">
										<div class="form-group mb-0">
											<label>( 0°)</label>
										</div>
									</div>

									<div class="col-1">
										<div class="form-group mb-0">
											<label>( 90°)</label>
										</div>
									</div>

									<div class="col-1">
										<div class="form-group mb-0">
											<label>( 180°)</label>
										</div>
									</div>

									<div class="col-1">
										<div class="form-group mb-0">
											<label>( 270°)</label>
										</div>
									</div>

									<div class="col-1">
										<div class="form-group mb-0">
											<label>Result</label>
										</div>
									</div>

									<div class="col-1">
										<div class="form-group mb-0">
											<label>Remarks</label>
										</div>
									</div>
									<div class="col-1"></div>
								</div>
								@foreach(json_decode($highPressure->nhpr_31) as $value)
								<div class="row" data-repeater-item>
									<div class="col-2">
										<div class="form-group mb-0">
											<div class="controls">
												<textarea id="ntir_433" name="ntir_433" class="form-control"
													placeholder="Point Description">{{$value->ntir_433}}</textarea>
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-1">
										<div class="form-group mb-0">
											<div class="controls">
												<textarea id="ntir_434" name="ntir_434" class="form-control"
													placeholder="Line Size">{{$value->ntir_434}}</textarea>
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-1">
										<div class="form-group mb-0">
											<div class="controls">
												<textarea id="ntir_435" name="ntir_435" class="form-control"
													placeholder="Minimum Wall Thickness">{{$value->ntir_435}}</textarea>
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-1">
										<div class="form-group mb-0">
											<div class="controls">
												<textarea id="ntir_436" name="ntir_436" class="form-control"
													placeholder="( 0°)">{{$value->ntir_436}}</textarea>
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-1">
										<div class="form-group mb-0">
											<div class="controls">
												<textarea id="ntir_437" name="ntir_437" class="form-control"
													placeholder="( 90°)">{{$value->ntir_437}}</textarea>
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-1">
										<div class="form-group mb-0">
											<div class="controls">
												<textarea id="ntir_438" name="ntir_438" class="form-control"
													placeholder="( 180°)">{{$value->ntir_438}}</textarea>
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-1">
										<div class="form-group mb-0">
											<div class="controls">
												<textarea id="ntir_439" name="ntir_439" class="form-control"
													placeholder="( 270°)">{{$value->ntir_439}}</textarea>
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-1">
										<div class="form-group mb-0">
											<div class="controls">
												<textarea id="ntir_440" name="ntir_440" class="form-control"
													placeholder="Result">{{$value->ntir_440}}</textarea>
												<div class="help-block"></div>
											</div>
										</div>
									</div>
									<div class="col-2">
										<div class="form-group mb-0">
											<div class="controls">
												<textarea id="ntir_441" name="ntir_441" class="form-control"
													placeholder="Remarks">{{$value->ntir_441}}</textarea>
												<div class="help-block"></div>
											</div>
										</div>
									</div>


									<div class=" col-1">
										<button type="button" class="btn btn-danger" data-repeater-delete> <i
												class="ft-x"></i></button>
									</div>
								</div>
								@endforeach
							</div>
							<div class=" form-group overflow-hidden">
								<div class="col-12">
									<button type="button" data-repeater-create class="btn btn-primary"><i
											class="ft-plus"></i> Add</button>
								</div>
							</div>
						</div>
					</div>
					<h6>Final Conclusion:</h6>
					<div class="card">
						<div class="card-content collapse show">
							<div class="card-body">

								<div class="form-group">
									<div class="controls">
										<!-- <textarea id="nhpr_31" name="nhpr_31" class="form-control" placeholder="Final Conclusion"></textarea> -->
										<div class="row">

											<div class="col-8">
												<p>NDT result : Accept / Reject ?</p>
											</div>
											<div class="col-2">
												<div class="custom-control custom-radio">
													<input type="radio" class="custom-control-input" id="nhpr_31_y"
														name="nhpr_31"
														value="nhpr_31_y"
														{{$highPressure->checkbox_yes($highPressure->nhpr_32)}}
													required>
													<label class="custom-control-label" for="nhpr_31_y">Accept</label>
												</div>
											</div>
											<div class="col-2">
												<div class="custom-control custom-radio">
													<input type="radio" class="custom-control-input" id="nhpr_31_n"
														name="nhpr_31"
														value="nhpr_31_n"
														{{$highPressure->checkbox_no($highPressure->nhpr_32)}}>
													<label class="custom-control-label" for="nhpr_31_n">Reject</label>
												</div>
											</div>


										</div>

									</div>
								</div>

							</div>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</section>
@endsection

@extends('layouts.scripts.forms')

@prepend('child-scripts')
<script src="{{asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
<script src="{{asset('app-assets/js/scripts/forms/form-repeater.js')}}"></script>
<script>
	$(".steps-validation").steps({
		headerTag: "h6",
		bodyTag: "fieldset",
		transitionEffect: "fade",
		titleTemplate: '<span class="step">#index#</span> #title#',
		labels: {
			finish: 'Submit'
		},
		onStepChanging: function (event, currentIndex, newIndex) {
			// Allways allow previous action even if the current form is not valid!
			if (currentIndex > newIndex) {
				return true;
			}
// Needed in some cases if the user went back (clean up)
			if (currentIndex < newIndex) {
				// To remove error styles
				form.find(".body:eq(" + newIndex + ") label.error").remove();
				form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
			}
			return window.validateWizardCurrentStep($(this));
		},
		onFinishing: function (event, currentIndex) {
			form.validate().settings.ignore = ":disabled";
			return form.valid();
		},
		onFinished: function (event, currentIndex) {
			var form1 = $('.wizard')[0];
			var formdata = new FormData(form1);
			formdata.append('lcr_1', $('#lcr_1').val());
			formdata.append('code', $('#code').val());
			formdata.append('nhpr_2', $('#purchaseOrder').val());
			formdata.append('lcr_7', $('#lcr_70').val());
			formdata.append('publish', 'yes');
			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				type: 'POST',
				url: "{{route('highPressure.update', $highPressure->id)}}",
				processData: false,
				contentType: false,
				cache: false,
				data: formdata,
				dataType: "JSON",
				beforeSend: function () {
					$('#submit i').addClass('la la-refresh spinner');
				},
				success: function (data) {
					toastr.info('Good Job !', data.success, {
						positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () {
							window.location.replace("{{route('highPressure.index')}}");
						}
					});
				},
			});
		}
	});
</script>
@endprepend

@extends('layouts.scripts.reportsforms')

@push('bottom-child-scripts')
<script>
	var nhpr_11 = [];
	/**************************************************************/
	$.each($('.nhpr_11'), function (value) {
		if ($(this).is(':checked')) {
			var id = this.id;
			if (id === 'other') {
				$('#nhpr_12').prop("disabled", false);
			}
			nhpr_11.push(id);
		}
	});
	$('.steps-validation').on('ifChecked', '.nhpr_11', function () {
		var id = this.id;
		if (id === 'other') {
			$('#nhpr_12').prop("disabled", false);
			$('#other_specification_section').show();
		}
		nhpr_11.push(id);
	});
	$('.steps-validation').on('ifUnchecked', '.nhpr_11', function (event) {
		var id = this.id;
		if (id === 'other') {
			$('#nhpr_12').prop("disabled", true).val('');
			$('#other_specification_section').hide();
		}
		index1 = nhpr_11.indexOf(id);
		nhpr_11.splice(index1, 1);
	});

	$('.repeater').repeater({
		show: function () {
			$(this).slideDown();
			// $(this).find('img').attr('src', '');
		},
		hide: function (remove) {
			if (confirm('Are you sure you want to remove this item?')) {
				$(this).slideUp(remove);
			}
		},
	});
	var pop = 12;
	$('.steps-validation').on("change", '#suporcli', function (e) {
		$('#lcr_60, #lcr_70').val('');
		if (this.checked == true) {
			pop = 12;
		}
		else {
			pop = 6;
		}
		$('#months').text(pop);
	});

	$(".datepicker-default").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd-mm-yy',
		constrainInput: false,
	}).on("change", function () {
		var date = new Date($(this).datepicker("getDate").toISOString());
		date.setMonth(date.getMonth() + pop);
		date.setDate(date.getDate() - 1);
		$('#lcr_70').val(('0' + date.getDate()).slice(-2) + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + date.getFullYear());
	});
</script>
@endpush
