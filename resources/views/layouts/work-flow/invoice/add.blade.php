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
																	<input type="hidden" name="invoice_company_type" value="{{$invoice_company_type}}">
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
																	                  <label>
																												@if($qutation)
																														Quotation No.
																												@else
																														Contract No.
																												@endif
																										</label>
																	                  <input type="text" name="contract" @if($qutation) disabled @endif class="form-control" placeholder=
																										@if($qutation)
																												"Quotation No."
																										@else
																												"Contract No."
																										@endif
																										aria-invalid="false" required="" data-validation-required-message="This field is required" value=
																										@if($qutation)
																												"{{$qutation->code}}"
																										@else
																												""
																										@endif
																										/>
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
																				<div class="col-md-12">
																						<div class="form-group">
																								<div class="controls">
																										<label>Client P.O code</label>
																										<input type="text" name="cpo" class="form-control" placeholder="Client P.O code" />
																								</div>
																						</div>
																				</div>
																		</div>
																</div>
														</div>
												</div>
												@if($qutation)
														<div class="card border-cyan border-lighten-4">
																<div class="card-content">
																		<div class="card-body">
																				<div class="row">
																						<div class="col-12">
																								<div class="form-group mb-0">
																										<div class="controls">
																												<label>Curreny in
																														<span class="months">
																																@if($qutation->type=="USD")
																																		USD
																																@else
																																		EGP
																																@endif
																														</span>
																												</label>
																												<div style="display: inline-block; float: right; margin-left: 10px;">
																														<input type="checkbox" class="switchery mr-1" id="suporcli" name="suporcli"
																																@if($qutation->type == "USD")
																																		checked
																																@endif
																														/>
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
																														<th class="col-md-5">Description</th>
																														<th class="col-md-1 text-right">Quantity</th>
																														<th class="col-md-1 text-right">Price</th>
																														<th class="col-md-2 text-right">Per</th>
																														<th class="col-md-1 text-right">Amount</th>
																														<th class="col-md-1"></th>
																												</tr>
																										</thead>
																										<tbody data-repeater-list="items">
																												@foreach(json_decode($qutation->items) as $qutationitem)
																														<tr data-repeater-item>
																																<td class="col-md-1">
																																		<input type="text" placeholder="Code" name="icode" value="{{$qutationitem->icode}}" class="form-control icode text-center" style="padding: 0;" />
																																</td>
																																<td class="col-md-5">
																																		<textarea class="form-control" name="idesc" rows="2" placeholder="Item Description" aria-invalid="false">{{$qutationitem->idesc}}</textarea>
																																</td>
																																<td class="col-md-1">
																																		<input type="number" placeholder="0" name="pquantity" class="form-control pquantity text-center" min=0 style="padding: 0;" value="{{$qutationitem->pquantity}}" />
																																</td>
																																<td class="col-md-1">
																																		<input type="number" placeholder="0" name="price" class="form-control price text-center" min=0 style="padding: 0;" value="{{$qutationitem->price}}" />
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
																																<td class="col-md-1 text-right">
																																		<input type="number" placeholder="0" name="pamount" class="form-control pamount text-center" min=0 style="padding: 0;" value="{{$qutationitem->pamount}}" />
																																</td>
																																<td class="col-md-1"><button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button></td>
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
												@else
														<div class="card border-cyan border-lighten-4">
																<div class="card-content">
																		<div class="card-body">
																				<div class="row">
																						<div class="col-12">
																								<div class="form-group mb-0">
																										<div class="controls">
																												<label>Curreny in
																														<span class="months">USD</span>
																												</label>
																												<div style="display: inline-block; float: right; margin-left: 10px;">
																														<input type="checkbox" class="switchery mr-1" id="suporcli" name="suporcli" checked />
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
																														<th class="col-md-5">Description</th>
																														<th class="col-md-1 text-right">Quantity</th>
																														<th class="col-md-1 text-right">Price</th>
																														<th class="col-md-2 text-right">Per</th>
																														<th class="col-md-1 text-right">Amount</th>
																														<th class="col-md-1"></th>
																												</tr>
																										</thead>
																										<tbody data-repeater-list="items">
																												<tr data-repeater-item>
																														<td class="col-md-1">
																																<input type="text" placeholder="Code" name="icode" value="" class="form-control icode text-center" style="padding: 0;">
																														</td>
																														<td class="col-md-5">
																																<textarea class="form-control" name="idesc" rows="2" placeholder="Item Description" aria-invalid="false"></textarea>
																														</td>
																														<td class="col-md-1">
																																<input type="number" placeholder="0" name="pquantity" class="form-control pquantity text-center" min=0 style="padding: 0;" value="">
																														</td>
																														<td class="col-md-1">
																																<input type="number" placeholder="0" name="price" class="form-control price text-center" min=0 style="padding: 0;" value="">
																														</td>
																														<td class="col-md-2 text-right">
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
																																		<option value="group">Group</option>
																																		<option value="other">Other</option>
																																</select>
																														</td>
																														<td class="col-md-1 text-right">
																																<input type="number" placeholder="0" name="pamount" class="form-control pamount text-center" min=0 style="padding: 0;" value="">
																														</td>
																														<td class="col-md-1"><button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button></td>
																												</tr>
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
												@endif
												<div class="card border-cyan border-lighten-4">
														<div class="card-content">
																<div class="card-body">
																		<div class="row">
																				<div class="col-7 repeater">
																						<div class="repeater">
																								<h6>Payment Terms & Methods</h6>
																								<div data-repeater-list="payments">
																										@if($qutation)
																												@foreach(json_decode($qutation->payment_method) as $method)
																														<div data-repeater-item  class="row ">
																																<div class="controls col-3">
																																		<input type="text" name="methodname" class="form-control" placeholder="Method Name" aria-invalid="false"  value="{{$method->methodname}}" />
																																</div>
																																<div class="controls col-8">
																																		<input type="text" name="methoddesc" class="form-control" placeholder="Method Desc." aria-invalid="false" value="{{$method->methoddesc}}" />
																																</div>
																																<div class=" col-1">
																																		<button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
																																</div>
																														</div>
																												@endforeach
																										@else
																												<div data-repeater-item  class="row ">
																														<div class="controls col-3">
																																<input type="text" name="methodname" class="form-control" placeholder="Method Name" aria-invalid="false" value="" />
																														</div>
																														<div class="controls col-8">
																																<input type="text" name="methoddesc" class="form-control" placeholder="Method Desc." aria-invalid="false" value="" />
																														</div>
																														<div class=" col-1">
																																<button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
																														</div>
																												</div>
																										@endif
																								</div>
																						</div>
																						<div class=" form-group overflow-hidden mt-1">
																								<button type="button" data-repeater-create class="btn btn-info"><i class="ft-plus"></i> Add</button>
																						</div>
																				</div>
																				<div class="col-sm-5 col-12">
																						<h6>Total due</h6>
																						<div class="table-responsive">
																								<table class="table">
																										<tbody>
																												<tr>
																														<td>Sub Total</td>
																														<td class="text-right subtotal"> 0.00 <span class="months">USD</span></td>
																												</tr>
																												<tr class="success">
																														<td>
																																<div class="form-group mb-0">
																																		<div class="controls">
																																				<label style="margin-left: 0 !important;">TAX <span class="months1">(14%)</span></label>
																																				<div style="display: inline-block; margin-left: 10px;">
																																						<input type="checkbox" class="switchery mr-1" id="suporcli1" name="suporcli1" checked />
																																				</div>
																																		</div>
																																</div>
																														</td>
																														<td class="text-right tax"> 0.00 <span class="months">$</span></td>
																												</tr>
																												<tr class="pink">
																														<td class="text-bold-800">
																																<label style="display: inline-block; float: left; padding-top: 8px;">Withholding TAX</label>
																																<select name="discountTax" class="form-control discountTax" style="width: 60px; padding-left: 0; float: left; margin-left: 15px;" required>
																																		<option selected value="0">0%</option>
																																		<option value="1">1%</option>
																																		<option value="3">3%</option>
																																</select>
																														</td>
																														<td class="text-bold-800 text-right discountTaxAfter">- 0.00 <span class="months">$</span></td>
																												</tr>
																												<tr>
																														<td class="text-bold-800">Total</td>
																														<td class="text-bold-800 text-right total"> 0.00 <span class="months">$</span></td>
																												</tr>
																										</tbody>
																								</table>
																						</div>
																				</div>
												</div>
										</div>
								</div>
						</div>
						@include('layouts.work-flow.invoice._already-paid-fields')
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
								var subtotalValue = parseFloat(String($('.subtotal').text()).replace(/[^0-9.\-]/g, '')) || 0;
								var taxValue = parseFloat(String($('.tax').text()).replace(/[^0-9.\-]/g, '')) || 0;
								var totalValue = parseFloat(String($('.total').text()).replace(/[^0-9.\-]/g, '')) || 0;
								formdata.append('csd', $('#client option:selected').val());
						    formdata.append('subtotal', subtotalValue.toFixed(2));
						    formdata.append('tax', taxValue.toFixed(2));
						    formdata.append('discountTaxAfter', (parseFloat(t) || 0).toFixed(2));
						    formdata.append('total', totalValue.toFixed(2));
						    formdata.append('type', type);
								$.ajax({
										headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
										type: 'POST',
										url: "{{route('invoice.store')}}",
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
													window.location.replace("{{route('invoice.index', ['type'=>$invoice_company_type])}}");
												}  });
										},
								});
						}
				});
		</script>
@endprepend

@push('bottom-child-scripts')
		<script>
				var t = parseFloat($(".discountTax option:selected").val()) || 0;
				var type = $('#suporcli').is(':checked') ? "USD" : "EGP";
				var type1 = $('#suporcli1').is(':checked') ? "(14%)" : "(0%)";
				var type2 = $('#suporcli1').is(':checked') ? 14 : 0;

				function toNumber(value) {
						if (value === undefined || value === null) {
								return 0;
						}
						var normalized = String(value).replace(/[^0-9.\-]/g, '');
						var parsed = parseFloat(normalized);
						return isNaN(parsed) ? 0 : parsed;
				}

				function formatMoney(value) {
						return toNumber(value).toFixed(2);
				}

				function updateSummary() {
						var sum = 0;
						$('.pamount').each(function () {
								sum += toNumber($(this).val());
						});

						var taxAmount = (sum * type2) / 100;
						var withholdingAmount = (sum * t) / 100;
						var totalAmount = sum + taxAmount - withholdingAmount;

						$(".subtotal").text(formatMoney(sum) + " " + type);
						$(".tax").text(formatMoney(taxAmount) + " " + type);
						$(".discountTaxAfter").text("- " + formatMoney(withholdingAmount) + " " + type);
						$(".total").text(formatMoney(totalAmount) + " " + type);
						syncAlreadyPaidAmount(false);
				}

				function syncAlreadyPaidAmount(force) {
						var toggle = $('#already-paid-toggle');
						var paymentAmountInput = $('input[name="payment_amount"]');
						if (!toggle.length || !paymentAmountInput.length || !toggle.is(':checked')) {
								return;
						}

						if (!force && $.trim(paymentAmountInput.val()) !== '') {
								return;
						}

						paymentAmountInput.val(formatMoney($('.total').text()));
				}

				function toggleAlreadyPaidSection(forceAmountSync) {
						var toggle = $('#already-paid-toggle');
						var paymentSection = $('#already-paid-fields');
						if (!toggle.length || !paymentSection.length) {
								return;
						}

						var enabled = toggle.is(':checked');
						paymentSection.toggleClass('d-none', !enabled);

						if (enabled) {
								var paymentDateInput = $('input[name="payment_date"]');
								if (paymentDateInput.length && $.trim(paymentDateInput.val()) === '') {
										paymentDateInput.val('{{ old('payment_date', now()->format('Y-m-d')) }}');
								}

								syncAlreadyPaidAmount(forceAmountSync === true);
						}
				}

				function calcamount(qty, unitprice, name){
						var amount = toNumber(qty) * toNumber(unitprice);
						$('.pamount[name="' + name + '"]').val(formatMoney(amount));
						updateSummary();
				}

				$('.discountTax').on("change", function () {
						t = toNumber(this.value);
						updateSummary();
				});

				$('#suporcli').on("change", function () {
						type = this.checked ? "USD" : "EGP";
						$('.months').text(type);
						updateSummary();
				});

				$('#suporcli1').on("change", function () {
						type1 = this.checked ? "(14%)" : "(0%)";
						type2 = this.checked ? 14 : 0;
						$('.months1').text(type1);
						updateSummary();
				});

				$('#already-paid-toggle').on('change', function () {
						toggleAlreadyPaidSection(true);
				});

				$('.pquantity, .price').on('change keyup', function () {
						calcamount(
								$(this).closest('tr').find('.pquantity').val(),
								$(this).closest('tr').find('.price').val(),
								$(this).closest('tr').find('.pamount').attr('name')
						);
				});

				$('.repeater').repeater({
						show: function ()
						{
								$(this).slideDown();
								$(this).find('.iper').val("null");
								$(this).find('.pquantity, .price').on('change keyup', function(){
										calcamount(
												$(this).closest('tr').find('.pquantity').val(),
												$(this).closest('tr').find('.price').val(),
												$(this).closest('tr').find('.pamount').attr('name')
										);
								});
								updateSummary();
						},
						hide: function(remove)
						{
								if (confirm('Are you sure you want to remove this item?'))
								{
										$(this).slideUp(remove);
										setTimeout(updateSummary, 0);
								}
						}
				});

				updateSummary();
				toggleAlreadyPaidSection(false);
		</script>
@endpush

