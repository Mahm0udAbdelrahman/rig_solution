@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
	<section id="validation">
		<div class="row">
			<div class="col-12">
				<form action="#" class="steps-validation wizard-circle">
					<h6>Step 1</h6>
					<fieldset>
						<div class="row form-group">
							<h6 class="mb-1 col-md-8">Client / Supplier Details</h6>
							<div class="pb-1 col-md-4 text-right controls">
								<span class="mr-1"><label for="suporcli">Close it to change to supplier</label></span>
								<input type="checkbox" class="switchery" id="suporcli" name="suporcli" checked>
							</div>
						</div>
						<div class="card border-cyan border-lighten-4">
							<div class="card-content">
								<div class="card-body">
									<div class="row">
										{{--<div class="col-md-4">--}}
											{{--<div class="form-group">--}}
												{{--<div class="controls">--}}
													{{--<label class="m-0">Purchase Order</label>--}}
													{{--<input type="text" id="purchase_order" name="purchase_order" class="form-control"--}}
														   {{--placeholder="purchase_order" aria-invalid="false" required>--}}
												{{--</div>--}}
											{{--</div>--}}
										{{--</div>--}}
										<div class="col-md-3">
											<div class="form-group">
												<div class="controls">
													<label id="csname" class="m-0">Client Name</label>
													<select class="form-control csd searchable-select" id="client" name="csd" required>
														<option value="">Select Value</option>
													</select>
													<input type="hidden" value="1" id="persontype" name="persontype"/>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<div class="controls">
													<label class="m-0">Code Number</label>
													<input type="text" id="code" name="code" class="form-control"
														   placeholder="Code Number" aria-invalid="false" disabled>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<div class="controls">
													<label class="m-0">Contact Name</label>
													<select class="form-control searchable-select" id="contact" name="contact" required>
														<option value="">Select Value</option>
													</select>
												</div>
											</div>
										</div>
										<div id="clientDepartmentSection" class="col-md-3">
											<div class="form-group">
												<div class="controls">
													<label class="m-0">Client Department</label>
													<select class="form-control searchable-select" id="clientDepartmentsSelect" name="client_department_id" required>
														<option value="">Select Value</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="row">

									</div>
									<div class="skin skin-square form-group">
										<div class="controls">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<div class="controls">
															<label class="m-0 mb-1">Contact Way</label>
															<div class="row input-group">
																<fieldset class="col-md-6">
																	<input type="checkbox" class="contactway"
																		   name="contactway" id="phone" required>
																	<label for="phone">Phone</label>
																</fieldset>
																<fieldset class="col-md-6">
																	<input type="checkbox" class="contactway"
																		   name="contactway" id="email">
																	<label for="email">E-mail</label>
																</fieldset>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-8">
													<div class="form-group mb-0">
														<div class="controls">
															<label class="m-0">Contact Date / Time</label>
															<div class="input-group">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i
																				class="ft-calendar"></i></span>
																</div>
																<input type="text"
																	   class="form-control dp-date-range-from"
																	   id="contactdate" name="contactdate"
																	   placeholder="Contact Date / Time" required=""
																	   data-validation-required-message="This field is required"/>
															</div>
															<div class="help-block"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<h6 class="mb-1">Subject</h6>
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<div class="row">
										<div class="col-12">
											<div class="form-group">
												<div class="controls">
													<textarea class="form-control" name="subject" rows="2" required=""
															  data-validation-required-message="This field is required"
															  placeholder="Subject"></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<h6 class="mb-1">Work Location</h6>
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<div class="skin skin-square form-group mt-1">
										<div class="controls">
											<div class="row">
												<div class="col-md-3 col-sm-12">
													<fieldset>
														<input type="checkbox" class="worklocation" required
															   name="worklocation" id="rse-yard">
														<label for="rse-yard">RSE Yard</label>
													</fieldset>
												</div>
												<div class="col-md-3 col-sm-12">
													<fieldset>
														<input type="checkbox" class="worklocation" name="worklocation"
															   id="rse-lab">
														<label for="rse-lab">RSE Lab</label>
													</fieldset>
												</div>
												<div class="col-md-3 col-sm-12">
													<fieldset>
														<input type="checkbox" class="worklocation" name="worklocation"
															   id="client-location">
														<label for="client-location">Client Location</label>
													</fieldset>
												</div>
												<div class="col-md-3 col-sm-12">
													<fieldset>
														<input type="checkbox" class="worklocation" name="worklocation"
															   id="other1">
														<label for="other1">Other</label>
													</fieldset>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<h6 class="mb-1">Department</h6>
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<div class="skin skin-square form-group mt-1">
										<div class="controls">
											<div class="row">
												@foreach($departments as $department)
													<div class="col-md-3 col-sm-12">
														<fieldset>
															<input type="checkbox" class="department" name="department"
																   required id="{{$department->id}}">
															<label for="{{$department->id}}">{{$department->name}}</label>
														</fieldset>
													</div>
												@endforeach
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<h6 class="mb-1">Job Required Details</h6>
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<div class="row">
										<div class="col-12">
											<div class="form-group">
												<div class="controls">
													<textarea class="form-control" name="job_requierd_details" rows="3"
															  required=""
															  data-validation-required-message="This field is required"
															  placeholder="Job Required Details"></textarea>
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
						<h6 class="mb-1">Attention To</h6>
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<div class="skin skin-square form-group mt-1">
										<div class="controls">
											<div class="row">
												<div class="col-md-6">
													<div class="row" id="managers"></div>
												</div>
												<div class="col-md-3">
													<div class="form-group mb-0">
														<div class="controls">
															<label class="m-0">Location</label>
															<input type="text" class="form-control" id="deploc"
																   name="deploc" placeholder="Location"/>
															<div class="help-block"></div>
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<div class="controls">
															<label class="m-0">Purchase Order</label>
															<input type="text" id="purchase_order" name="purchase_order"
																   class="form-control"
																   placeholder="purchase_order" aria-invalid="false"
																   required>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<h6 class="mb-1">Personal Name / Qualifications Required</h6>
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<div class="skin skin-square form-group mt-1">
										<div class="controls">
											<div class="row" id="employees"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<h6 class="mb-1">Equipment / Material Required</h6>
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<div class="skin skin-square form-group mt-1">
										<div class="controls">
											<div class="row">
												@foreach($tools as $tool)
													<div class="col-md-3 col-sm-12">
														<fieldset>
															<input type="checkbox" class="tool" name="tool"
																   id="{{strtolower(str_replace(' ', '-', $tool->name))}}">
															<label for="{{strtolower(str_replace(' ', '-', $tool->name))}}">{{$tool->name}}</label>
														</fieldset>
													</div>
												@endforeach
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<h6 class="mb-1">Specification</h6>
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<div class="skin skin-square form-group mt-1">
										<div class="controls">
											<div class="row">
												@foreach($specifications as $specification)
													<div class="col-md-3 col-sm-12">
														<fieldset>
															<input type="checkbox" class="specification"
																   name="specification"
																   id="{{strtolower(str_replace(' ', '-', $specification->name))}}">
															<label for="{{strtolower(str_replace(' ', '-', $specification->name))}}">{{$specification->name}}</label>
														</fieldset>
													</div>
												@endforeach
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<h6 class="mb-1">Scope of Work</h6>
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<div class="row">
										<div class="col-12">
											<div class="form-group">
												<div class="controls">
													<textarea class="form-control" name="scope_of_work" rows="3"
															  placeholder="Scope of Work"></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<h6 class="mb-1">Start / End Date</h6>
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group mb-0">
												<div class="controls">
													<label class="m-0">Start Date</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text"><i
																		class="ft-calendar"></i></span>
														</div>
														<input type="text" class="form-control dp-date-range-from"
															   id="startdate" name="startdate"
															   placeholder="Start Date"/>
													</div>
													<div class="help-block"></div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group mb-0">
												<div class="controls">
													<label class="m-0">End Date</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text"><i
																		class="ft-calendar"></i></span>
														</div>
														<input type="text" class="form-control dp-date-range-from"
															   id="enddate" name="enddate" placeholder="End Date"/>
													</div>
													<div class="help-block"></div>
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

@extends('layouts.scripts.jcf')

@prepend('child-scripts')
<script>
		$(".steps-validation").steps({
				headerTag: "h6",
				bodyTag: "fieldset",
				transitionEffect: "fade",
				titleTemplate: '<span class="step">#index#</span> #title#',
				labels: {
						finish: 'Save'
				},
				onStepChanging: function (event, currentIndex, newIndex) {
						// Allways allow previous action even if the current form is not valid!
						if (currentIndex > newIndex)
						{
								return true;
						}
						// Needed in some cases if the user went back (clean up)
						if (currentIndex < newIndex)
						{
								// To remove error styles
								form.find(".body:eq(" + newIndex + ") label.error").remove();
								form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
						}
						form.validate().settings.ignore = ":disabled,:hidden:not(.select2-hidden-accessible)";
						return form.valid();
				},
				onFinishing: function (event, currentIndex) {
						form.validate().settings.ignore = ":disabled,:hidden:not(.select2-hidden-accessible)";
						return form.valid();
				},
				onFinished: function (event, currentIndex) {
						var form1 = $('.wizard')[0];
						var formdata = new FormData(form1);
						formdata.append('department', JSON.stringify(department));
						formdata.append('contactway', JSON.stringify(contactway));
						formdata.append('worklocation', JSON.stringify(worklocation));
						formdata.append('manager', JSON.stringify(manager));
						formdata.append('eng', JSON.stringify(eng));
						formdata.append('tool', JSON.stringify(tool));
						formdata.append('specification', JSON.stringify(specification));
						$.ajax({
								headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
								type: 'POST',
								url: "{{route('jobRequest.store')}}",
								processData: false,
								contentType: false,
								cache: false,
								data: formdata,
								dataType: "JSON",
								beforeSend:function(){
										$('#submit i').addClass('la la-refresh spinner');
								},
								success: function (data){
										toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () {
											window.location.replace("{{route('jobRequest.index')}}");
										}  });
								},
								error: function (xhr) {
										var errorMessage = 'Save failed. Please check date fields and required values.';
										if (xhr.responseJSON && xhr.responseJSON.message) {
												errorMessage = xhr.responseJSON.message;
										}
										toastr.error(errorMessage, 'Error', { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 5000 });
								}
						});
				}
		});
</script>

@endprepend
