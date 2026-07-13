@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
		<section id="form-repeater">
		  	<div class="row">
		    		<div class="col-12">
		      			<form action="#" class="steps-validation wizard-circle">
										@method('PUT')
						        <h6>Step 1</h6>
						        <fieldset>
												<div class="card border-cyan border-lighten-4">
														<div class="card-content">
																<div class="card-body">
																		<div class="row">
																				<div class="col-md-4">
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
																				<fieldset class="col-md-4 form-group">
																						<label>Delivery Promise</label>
																						<div class="input-group">
																								<div class="input-group-prepend">
																										<span class="input-group-text"><i class="ft-calendar"></i></span>
																								</div>
																								<input type="text" name="deliverydate" placeholder="Delivery Promise" class="form-control dp-date-range-from" required="" data-validation-required-message="This field is required" value="{{$job_request_delivery_promise ?? ''}}" />
																						</div>
																				</fieldset>
																				<div class="col-md-4">
																						<div class="form-group">
																								<div class="controls">
																										<label>Delivery Location</label>
																										<input type="text" name="deliverylocation" class="form-control" placeholder="Delivery Location"  required="" data-validation-required-message="This field is required" value="{{$job_request_deploc}}" />
																								</div>
																						</div>
																				</div>

																			<div class="col-md-4">
																				<div class="form-group">
																					<div class="controls">
																						<h6>Date</h6>
																						<div class="input-group">
																							<div class="input-group-prepend">
																								<span class="input-group-text"><i
																											class="ft-calendar"></i></span>
																							</div>
																							<input type="text"
																								   name="creation_date"
																								   placeholder="Creation Data"
																								   class="form-control dp-date-range-from"
																								   required=""
																								   data-validation-required-message="This field is required"
																								   value="{{$qutation->creation_date}}"
																								   readonly/>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																</div>
														</div>
												</div>
												<div class="row">
														<div class="col-12">
																<div class="card">
																		<div class="card-content">
																				<div class="card-body">
																						<h6>Subject</h6>
																						<input type="text" name="subject" class="form-control" placeholder="Qutation Subject" aria-invalid="false" required="" data-validation-required-message="This field is required" value="{{$qutation->subject}}">
																				</div>
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
																								<div class="row">
																										<div class="col-12">
																												<div class="form-group mb-0">
																														<div class="controls">
																																<label>Curreny in <span class="months">
																																	@if($qutation->type=="USD")
																																			USD
																																	@else
																																			EGP
																																	@endif
																																</span></label>
																																<div style="display: inline-block; float: right; margin-left: 10px;">
																																		<input type="checkbox" class="switchery mr-1" id="suporcli" name="suporcli"
																																		@if($qutation->type=="USD")
																																				checked
																																		@endif
																																		>
																																</div>
																														</div>
																												</div>
																										</div>
																								</div>
																								<hr />
																								<div class="row repeater">
																										<div class="table-responsive col-12">
																												<table class="table titems">
																														<thead>
																																<tr>
																																		<th class="col-md-1">Code</th>
																																		<th class="col-md-3">Description</th>
																																		<th class="col-md-1 text-right">Quantity</th>
																																		<th class="col-md-2 text-right">Price In <span class="months">USD</span></th>
																																		<th class="col-md-2 text-right">Per</th>
																																		<th class="col-md-2 text-right">Amount</th>
																																		<th class="col-md-1"></th>
																																</tr>
																														</thead>
																														<tbody data-repeater-list="items">
																																@foreach(json_decode($qutation->items) as $qutationitem)
																																		<tr data-repeater-item>
																																				<td class="col-md-1">
																																						<input type="text" placeholder="Code" name="icode" class="form-control icode text-center" value="{{$qutationitem->icode}}" style="padding: 0;">
																																				</td>
																																				<td class="col-md-3">
																																						<textarea class="form-control" name="idesc" rows="2" required="" data-validation-required-message="This field is required" placeholder="Item Description" aria-invalid="false">{{$qutationitem->idesc}}</textarea>
																																				</td>
																																				<td class="col-md-1">
																																						<input type="number" placeholder="0" name="pquantity" class="form-control pquantity text-center" min=1 style="padding: 0;" value="{{$qutationitem->pquantity}}" />
																																				</td>
																																				<td class="col-md-2">
																																						<input type="number" placeholder="0" name="price" class="form-control price text-center" min=1 style="padding: 0;"  value="{{$qutationitem->price}}" />
																																				</td>
																																				<td class="col-md-2 text-right">
																																						<select name="iper" class="form-control iper">
																																								<option @if($qutationitem->iper =="null") selected @endif value="null">Select Value</option>
																																								<option @if($qutationitem->iper =="each") selected @endif value="each">Each</option>
																																								<option @if($qutationitem->iper =="hour") selected @endif value="hour">Hour</option>
																																								<option @if($qutationitem->iper ==="day") selected @endif value="day">Day</option>
																																								<option @if($qutationitem->iper =="end") selected @endif value="end">End</option>
																																								<option @if($qutationitem->iper =="length") selected @endif value="length">Length</option>
																																								<option @if($qutationitem->iper =="joints") selected @endif value="joints">Joints</option>
																																								<option @if($qutationitem->iper =="rig") selected @endif value="rig">Rig</option>
																																								<option @if($qutationitem->iper =="film") selected @endif value="film">Film</option>
																																								<option @if($qutationitem->iper =="group") selected @endif value="group">Group</option>
																																								<option @if($qutationitem->iper =="other") selected @endif value="other">Other</option>
																																						</select>
																																				</td>
																																				<td class="col-md-2 text-right">
																																						<input type="number" placeholder="0" name="pamount" class="form-control pamount text-center" min=1 style="padding: 0;" value="{{$qutationitem->pamount}}" />
																																				</td>
																																				<td class="col-md-1">
																																						<button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
																																				</td>
																																		</tr>
																																@endforeach
																														</tbody>
																												</table>
																												<div class="form-group overflow-hidden">
																														<div class="col-12">
																																<button type="button" data-repeater-create class="btn btn-info"><i class="ft-plus"></i> Add</button>
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
												<div class="card border-cyan border-lighten-4">
														<div class="card-content">
																<div class="card-body">
																		<div class="row">
																				<div class="col-12 text-center text-sm-left ">
																						<div class="repeater">
																								<h6>General Terms & Conditions</h6>
																								<div data-repeater-list="terms">
																										@foreach(json_decode($qutation->terms) as $term)
																												<div data-repeater-item  class="row ">
																														<div class="controls col-3">
																																<input type="text" name="conditionname" class="form-control" placeholder="Condition Name" aria-invalid="false" value="{{$term->conditionname}}">
																														</div>
																														<div class="controls col-8">
																																<input type="text" name="conditiondesc" class="form-control" required data-validation-required-message="This field is required" placeholder="Condition Desc." aria-invalid="false" value="{{$term->conditiondesc}}">
																														</div>
																														<div class=" col-1">
																																<button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
																														</div>
																												</div>
																										@endforeach
																								</div>
																								<div class=" form-group overflow-hidden mt-1">
																										<button type="button" data-repeater-create class="btn btn-info"><i class="ft-plus"></i> Add</button>
																								</div>
																						</div>
																				</div>
																		</div>
																</div>
														</div>
												</div>
												<div class="card border-cyan border-lighten-4">
														<div class="card-content">
																<div class="card-body">
																		<div class="row">
																				<div class="col-12 text-center text-sm-left ">
																						<div class="repeater">
																								<h6>Payment Terms & Methods</h6>
																								<div data-repeater-list="payments">
																										@foreach(json_decode($qutation->payment_method) as $method)
																												<div data-repeater-item  class="row ">
																														<div class="controls col-3">
																																<input type="text" name="methodname" class="form-control" placeholder="Method Name" aria-invalid="false" value="{{$method->methodname}}">
																														</div>
																														<div class="controls col-8">
																																<input type="text" name="methoddesc" class="form-control" placeholder="Method Desc." required data-validation-required-message="This field is required"  aria-invalid="false" value="{{$method->methoddesc}}">
																														</div>
																														<div class=" col-1">
																																<button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
																														</div>
																												</div>
																										@endforeach
																								</div>
																								<div class=" form-group overflow-hidden mt-1">
																										<button type="button" data-repeater-create class="btn btn-info"><i class="ft-plus"></i> Add</button>
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
								formdata.append('type', type);
						    formdata.append('csd', $('#client option:selected').val());
								$.ajax({
										headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
										type: 'POST',
										url: "{{route('qutation.update', $qutation->id)}}",
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
													window.location.replace("{{route('qutation.index')}}");
												}  });
										},
								});
						}
				});
		</script>
@endprepend

@push('bottom-child-scripts')
		<script>
				function calcamount(qty, unitprice, name){
					var amount = Number(qty*unitprice).toFixed(2);
					$('.pamount[name="'+name+'"]').val(amount);
				}

				var type = "USD";

				if ($('#suporcli').is(':checked'))
				{
					type = "USD";
				}
				else
				{
					type = "EGP";
				}

				$('#suporcli').on("change" ,function (e){
						$('[data-repeater-list="items"]').empty();
						if (this.checked == true)
						{
								type = "USD";
						}
						else
						{
								type = "EGP";
						}
						$('.months').text(type);
				});

				$('.pquantity, .price').on('change keyup',function(){
						calcamount($(this).closest('tr').find('.pquantity').val(), $(this).closest('tr').find('.price').val(), $(this).closest('tr').find('.pamount').attr('name'))
				});

				$('.repeater').repeater({
				    show: function () {
					      $(this).slideDown();
					     $(this).find('.iper').val("null");
					      $(this).find('.pquantity, .price').on('change keyup',function(){
					        	calcamount($(this).closest('tr').find('.pquantity').val(), $(this).closest('tr').find('.price').val(), $(this).closest('tr').find('.pamount').attr('name'));
					      });
				    },
				    hide: function(remove) {
					      if (confirm('Are you sure you want to remove this item?')) {
					        	$(this).slideUp(remove);
					      }
				    }
			  });

		</script>
@endpush

