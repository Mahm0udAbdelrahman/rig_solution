@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
		<section id="form-repeater">
				<div class="row">
						<div class="col-12">
								<form action="#" class="steps-validation wizard-circle">
										<h6>Step 1</h6>
										<fieldset>
												<div class="card border-cyan border-lighten-4">
														<div class="card-content">
																<div class="card-body">
																		<div class="row">
																				<div class="col-md-6">
																						<div class="form-group">
																								<div class="controls">
																								<label id="csname">JCF Number</label>
																										<select class="form-control csd searchable-select" id="client" name="csd" required disabled>
																												<option value="">Select Value</option>
																												<option value="{{$job_request_id}}" selected>{{$job_request_code}}</option>
																										</select>
																								</div>
																						</div>
																				</div>
																				<div class="col-md-6">
															              <div class="form-group">
																                  <div class="controls">
																                      <label>Location</label>
																                      <input type="text" id="location" name="location" class="form-control" placeholder="Location" value="{{$job_request_location ?? $deploc}}" required="" disabled>
																                  <div class="help-block"></div></div>
															              </div>
														            </div>
																		</div>
																		<div class="row">
																				<fieldset class="col-md-6 form-group">
																							<label>Start Date</label>
																							<div class="input-group">
																										<div class="input-group-prepend">
																													<span class="input-group-text"><i class="ft-calendar"></i></span>
																										</div>
																										<input type="text" placeholder="Start Date" value="{{$prefill_start_date ?? ''}}" class="form-control dp-date-range-from" name="from" required />
																							</div>
																				</fieldset>
																				<fieldset class="col-md-6 form-group">
																							<label>End Date</label>
																							<div class="input-group">
																									<div class="input-group-prepend">
																											<span class="input-group-text"><i class="ft-calendar"></i></span>
																									</div>
																									<input type="text" placeholder="End Date" value="{{$prefill_end_date ?? ''}}" class="form-control dp-date-range-to" name="to" required />
																							</div>
																				</fieldset>
																		</div>
																</div>
														</div>
												</div>
												<div class="row">
									      		<div class="col-12">
												        <div class="card">
													          <div class="card-content collapse show">
														            <div id="invoice-template" class="card-body">
															              <div id="invoice-items-details" class="pt-2">
																                <div class="row repeater">
																	                  <div class="table-responsive col-12">
																		                    <table class="table titems">
																			                      <thead>
																				                        <tr>
																					                          <th class="col-md-5">Service Description</th>
																					                          <th class="col-md-1 text-right">Quantity</th>
																					                          <th class="col-md-2 text-right">Per</th>
																					                          <th class="col-md-1 text-right">Size</th>
																					                          <th class="col-md-1 text-right">Range</th>
																					                          <th class="col-md-1 text-right">Connection Type</th>
																					                          <th class="col-md-1"></th>
																				                        </tr>
																			                      </thead>
																			                      <tbody data-repeater-list="items">
																				                        <tr data-repeater-item>
																					                          <td class="col-md-5">
																				                            		<textarea class="form-control" name="descTextarea" rows="2" required="" data-validation-required-message="This field is required" placeholder="Service Description" aria-invalid="false" required="" data-validation-required-message="This field is required"></textarea>
																					                          </td>
																					                          <td class="col-md-1">
																					                            	<input type="number" placeholder="0" name="pquantity" class="form-control pquantity text-center" min=1 style="padding: 0;">
																					                          </td>
																					                          <td class="col-md-2 text-right">
																						                            <select name="iper" class="form-control iper">
																							                              <option value="null">Select Value</option>
																							                              <option value="unit">Unit</option>
																							                              <option value="hour">Hour</option>
																							                              <option value="day">Day</option>
																							                              <option value="end">End</option>
																							                              <option value="length">Length</option>
																							                              <option value="joints">Joints</option>
																							                              <option value="rig">Rig</option>
																							                              <option value="film">Film</option>
																							                              <option value="group">Group</option>
																							                              <option value="other">Other</option>
																						                            </select>
																					                          </td>
																					                          <td class="col-md-1">
																					                            	<input type="text" placeholder="0" name="size" class="form-control price text-center" style="padding: 0;">
																					                          </td>
																					                          <td class="col-md-1 text-right">
																					                            	<input type="text" placeholder="0" name="range" class="form-control pamount text-center" min=1 style="padding: 0;">
																					                          </td>
																					                          <td class="col-md-1 text-right">
																					                            	<input type="text" placeholder="0" name="ctype" class="form-control pamount text-center" min=1 style="padding: 0;">
																					                          </td>
																					                          <td class="col-md-1"><button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button></td>
																				                        </tr>
																			                      </tbody>
																		                    </table>
																		                    <div class="form-group overflow-hidden">
																			                      <div class="col-12">
																			                        	<button type="button" data-repeater-create class="btn btn-primary"><i class="ft-plus"></i> Add</button>
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
												<div class="card">
											      <div class="card-content">
												        <div class="card-body">
													          <div class="row">
														            <div class="col-12">
															              <label id="notice">Notice:(To mention any notices during the job here)</label>
															              <textarea class="form-control" id="notice" name="notice" rows="2" placeholder="Notice:(To mention any notices during the job here)" aria-invalid="false"></textarea>
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
								form.validate().settings.ignore = ":disabled,:hidden";
								return form.valid();
						},
						onFinishing: function (event, currentIndex) {
								form.validate().settings.ignore = ":disabled";
								return form.valid();
						},
						onFinished: function (event, currentIndex) {
								var form1 = $('.wizard')[0];
								var formdata = new FormData(form1);
						    formdata.append('csd', $('#client option:selected').val());
						    formdata.append('location', $('#location').val());
								$.ajax({
										headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
										type: 'POST',
										url: "{{route('serviceTicket.store')}}",
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
													window.location.replace("{{route('serviceTicket.index')}}");
												}  });
										},
								});
						}
				});
		</script>
@endprepend

@push('bottom-child-scripts')
		<script>
		$('.repeater').repeater({
				show: function () {
						$(this).slideDown();
				},
				hide: function(remove) {
						var $item = $(this);
						Swal.fire({
								title: 'Remove Item?',
								text: 'Are you sure you want to remove this item?',
								type: 'warning',
								showCancelButton: true,
								confirmButtonColor: '#d33',
								cancelButtonColor: '#6c757d',
								confirmButtonText: 'Yes, Remove',
								cancelButtonText: 'Cancel',
								confirmButtonClass: 'btn btn-danger',
								cancelButtonClass: 'btn btn-light ml-1',
								buttonsStyling: false,
						}).then(function (result) {
								if (result.value) {
										$item.slideUp(remove);
								}
						});
				}
		});
		</script>
@endpush

