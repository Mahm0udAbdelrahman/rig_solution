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
																				<div class="col-md-12">
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
																		</div>
																		<div class="row">
																				<div class="col-md-4">
																						<div class="form-group">
																								<div class="controls">
																										<label>Client P.O</label>
																										<input type="text" name="po" class="form-control" placeholder="Client P.O" value="{{$prefill_client_po ?? ''}}" />
																								</div>
																						</div>
																				</div>
																				<div class="col-md-4">
																						<div class="form-group">
																								<div class="controls">
																										<label>Shipping Method</label>
																										<input type="text" name="shippingmethod" class="form-control" placeholder="Shipping Method" value="{{$prefill_shipping_method ?? ''}}" />
																								</div>
																						</div>
																				</div>
																				<fieldset class="col-md-4 form-group">
																						<label>Order Date</label>
																						<div class="input-group">
																								<div class="input-group-prepend">
																										<span class="input-group-text"><i class="ft-calendar"></i></span>
																								</div>
																								<input type="text" class="form-control dp-date-range-from" name="from" placeholder="Order Date" required value="{{$jobrequests_stat}}" />
																						</div>
																				</fieldset>
																		</div>
																</div>
														</div>
												</div>
												<div class="card border-cyan border-lighten-4">
											      <div class="card-content">
												        <div class="card-body">
																		<div class="row">
																				<div class="col-12 text-center text-sm-left repeater">
																						<div data-repeater-list="payments">
																								<h6>Items</h6>
																								@if($qutations)
															                      @foreach(json_decode($qutations->items) as $item)
																												<div data-repeater-item class="row">
																														<div class="controls col-1">
																																<input type="text" name="itemcode" class="form-control" placeholder="Code" aria-invalid="false" value="{{$item->icode}}" >
																														</div>
																														<div class="controls col-5">
																																<input type="text" name="itemdesc" class="form-control" placeholder="Item Desc." aria-invalid="false" value="{{$item->idesc}}" required data-validation-required-message="This field is required" />
																														</div>
																														<div class="controls col-3">
																																<select name="iper" class="form-control iper">
																																		<option @if($item->iper =="null") selected @endif value="null">Select Value</option>
																																		<option @if($item->iper =="each") selected @endif value="each">Each</option>
																																		<option @if($item->iper =="hour") selected @endif value="hour">Hour</option>
																																		<option @if($item->iper ==="day") selected @endif value="day">Day</option>
																																		<option @if($item->iper =="end") selected @endif value="end">End</option>
																																		<option @if($item->iper =="length") selected @endif value="length">Length</option>
																																		<option @if($item->iper =="joints") selected @endif value="joints">Joints</option>
																																		<option @if($item->iper =="rig") selected @endif value="rig">Rig</option>
																																		<option @if($item->iper =="film") selected @endif value="film">Film</option>
																																		<option @if($item->iper =="group") selected @endif value="group">Group</option>
																																		<option @if($item->iper =="other") selected @endif value="other">Other</option>
																																</select>
																														</div>
																														<div class="controls col-1">
																																<input type="text" name="orderdqty" class="form-control" placeholder="Orderd Qty." aria-invalid="false">
																														</div>
																														<div class="controls col-1">
																																<input type="text" name="shippingqty" class="form-control" placeholder="Shipping Qty." aria-invalid="false">
																														</div>
																														<div class=" col-1">
																																<button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
																														</div>
																												</div>
																										@endforeach
																								@else
																										<div data-repeater-item class="row">
																												<div class="controls col-1">
																														<input type="text" name="itemcode" class="form-control" placeholder="Code" aria-invalid="false" value="" >
																												</div>
																												<div class="controls col-5">
																														<input type="text" name="itemdesc" class="form-control" required data-validation-required-message="This field is required" placeholder="Item Desc." aria-invalid="false" value="">
																												</div>
																												<div class="controls col-3">
																														<select name="iper" class="form-control iper">
																																<option value="null">Select Value</option>
																																<option value="each">Each</option>
																																<option value="hour">Hour</option>
																																<option value="day">Day</option>
																																<option value="end">End</option>
																																<option value="length">Length</option>
																																<option value="joints">Joints</option>
																																<option value="rig">Rig</option>
																																<option value="film">Film</option>
																																<option value="other">Other</option>
																														</select>
																												</div>
																												<div class="controls col-1">
																														<input type="text" name="orderdqty" class="form-control" placeholder="Orderd Qty." aria-invalid="false">
																												</div>
																												<div class="controls col-1">
																														<input type="text" name="shippingqty" class="form-control" placeholder="Shipping Qty." aria-invalid="false">
																												</div>
																												<div class=" col-1">
																														<button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
																												</div>
																										</div>
																								@endif
																						</div>
																						<div class=" form-group overflow-hidden mt-1">
																							<button type="button" data-repeater-create class="btn btn-info"><i class="ft-plus"></i> Add</button>
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
																						<div class="form-group">
																								<div class="controls">
																										<label>Acceptance Notice</label>
																										<textarea class="form-control" name="descTextarea" rows="2"  placeholder="Acceptance Notice " aria-invalid="false"></textarea>
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
								$.ajax({
										headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
										type: 'POST',
										url: "{{route('packingSlip.store')}}",
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
													window.location.replace("{{route('packingSlip.index')}}");
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
						if (confirm('Are you sure you want to remove this item?')) {
								$(this).slideUp(remove);
						}
				}
		});
		</script>
@endpush

