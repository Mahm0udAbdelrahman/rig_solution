@extends('layouts.app')

@extends('layouts.styles.paper')

@push('page_content')
		<div class="row border-dark white page_in" style="padding: 0 !important; border-bottom: none;">
				<div class="col-3" style="background: #00a5bb !important;">
						<h5 class="mb-0 white text-bold-700" style="color: #fff !important;">Client:</h5>
				</div>
				<div class="col-5 border-dark text-bold-600" style="border-top: none; border-bottom: none; border-right: none; font-size: 16px;">
						{{$invoice->jobRequest->client->name}}
				</div>
				<div class="col-2 bg-dark" style="background: #00a5bb !important;">
						<h5 class="mb-0 white text-bold-700" style="color: #fff !important;">Tax Card:</h5>
				</div>
				<div class="col-2 border-dark text-bold-600" style="border-top: none; border-bottom: none; border-right: none; font-size: 16px;">
						{{$invoice->jobRequest->client->tax_card}}
				</div>
		</div>
		<div class="row border-dark white" style="padding: 0 !important; border-bottom: none;">
				<div class="col-3 bg-dark" style="background: #00a5bb !important;">
						<h5 class="mb-0 white text-bold-700" style="color: #fff !important;">Address:</h5>
				</div>
				<div class="col-9 border-dark text-bold-600" style="border-top: none; border-bottom: none; border-right: none;">
						{{$invoice->jobRequest->client->location}}
				</div>
		</div>
		<div class="row border-dark white" style="padding: 0 !important;">
				<div class="col-3 bg-dark" style="background: #00a5bb !important;">
						<h5 class="mb-0 white text-bold-700" style="color: #fff !important;">Department / Location:</h5>
				</div>
				<div class="col-6 border-dark text-bold-600" style="border-top: none; border-bottom: none; border-right: none;">
					{{$invoice->jobRequest->clientDepartment ?
				 $invoice->jobRequest->clientDepartment->name . ' / '.$invoice->jobRequest->deploc
				 : $invoice->jobRequest->deploc}}
				</div>
				<div class="col-1 bg-dark" style="background: #00a5bb !important;">
						<h5 class="mb-0 white text-bold-700" style="color: #fff !important;">Date:</h5>
				</div>
				<div class="col-2 border-dark text-bold-600" style="border-top: none; border-bottom: none; border-right: none; font-size: 16px;">
						{{date('d-m-Y', strtotime($invoice->created_at->toDateString()))}}
				</div>
		</div>
		<div class="row mt-1">
				<div class="col-3 border-dark bg-dark white" style="border-bottom: none; border-right: none;">
						<h5 class="mb-0 white text-bold-700 text-center">JCF No</h5>
				</div>
				<div class="col-3 border-dark bg-dark white text-center" style="border-bottom: none; border-right: none;">
						<h5 class="mb-0 white text-bold-700">@if($invoice->jobRequest->qutation)Quotaion @else Contract @endif No</h5>
				</div>
				<div class="col-3 border-dark bg-dark white text-center" style="border-bottom: none; border-right: none;">
						<h5 class="mb-0 white text-bold-700">Client P.O</h5>
				</div>
				<div class="col-3 border-dark bg-dark white text-center" style="border-bottom: none;">
						<h5 class="mb-0 white text-bold-700">Currency Code</h5>
				</div>
		</div>
		<div class="row mb-1">
				<div class="col-3 border-dark black text-16 text-center" style="border-right: none;">
						{{$invoice->jobRequest->code}}
				</div>
				<div class="col-3 border-dark black text-16 text-center" style="border-right: none;">
						@if($invoice->jobRequest->qutation)
								{{$invoice->jobRequest->qutation->code}}
						@else
								{{$invoice->contract}}
						@endif
				</div>
				<div class="col-3 border-dark black text-16 text-center" style="border-right: none;">
						{{$invoice->cpo}}
				</div>
				<div class="col-3 border-dark black text-16 text-center">
						{{$invoice->type}}
				</div>
		</div>
		<div class="row">
				<div class="col-1 border-dark bg-dark white text-center" style="padding-top: 5px; padding-bottom: 5px; border-bottom: none; border-right: none;">
						<h5 class="mb-0 white text-bold-700">Code</h5>
				</div>
				<div class="col-7 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px; border-bottom: none; border-right: none;">
						<h5 class="mb-0 white text-bold-700">DESCRIPTION</h5>
				</div>
				<div class="col-1 border-dark bg-dark white text-center" style="padding-top: 5px; padding-bottom: 5px; border-bottom: none; border-right: none;">
						<h5 class="mb-0 white text-bold-700">QTY</h5>
				</div>
				<div class="col-1 border-dark bg-dark white text-center" style="padding-top: 5px; padding-bottom: 5px; border-bottom: none; border-right: none;">
						<h5 class="mb-0 white text-bold-700">Price</h5>
				</div>
				<div class="col-2 border-dark bg-dark white text-center" style="padding-top: 5px; padding-bottom: 5px; border-bottom: none;">
						<h5 class="mb-0 white text-bold-700">Amount</h5>
				</div>
		</div>
		@foreach(json_decode($invoice->items) as $item)
		<div class="row">
				<div class="col-1 black middle text-16 border-dark" style=" border-bottom: none; border-right: none;">
						<p class="text-center" style="position: absolute; margin: auto; left:0; right:0;">{{$item->icode}}</p>
				</div>
				<div class="col-7 black text-16 border-dark middle" style="height: 58px; border-bottom: none; border-right: none;">{{$item->idesc}}</div>
				<div class="col-1 black middle text-16 border-dark" style="border-bottom: none; border-right: none;">
						<p class="text-center text-bold-700" style="position: absolute; margin: auto; left:0; right:0;">{{$item->pquantity}}</p>
				</div>
				<div class="col-1 black middle text-16 border-dark" style="border-bottom: none; border-right: none;">
						<p class="text-center text-bold-700" style="position: absolute; margin: auto; left:0; right:0;">@if($item->iper !== "null"){{$item->price}} / {{ucwords($item->iper)}}@endif</p>
				</div>
				<div class="col-2 black middle text-16 border-dark" style="border-bottom: none; ">
						<p class="text-center text-bold-700" style="position: absolute; margin: auto; left:0; right:0;">{{$item->pamount}}</p>
				</div>
		</div>
		@endforeach
		@if(count(json_decode($invoice->items)) < 11)
				@for($i = 11-count(json_decode($invoice->items)); $i > 0; $i--)
						<div class="row">
								<div class="col-1 black middle text-16 border-dark" style=" border-bottom: none; border-right: none;">
										<p class="text-center" style="position: absolute; margin: auto; left:0; right:0;"></p>
								</div>
								<div class="col-7 black text-16 border-dark" style="height: 58px; border-bottom: none; border-right: none;"></div>
								<div class="col-1 black middle text-16 border-dark" style="border-bottom: none; border-right: none;">
										<p class="text-center text-bold-700" style="position: absolute; margin: auto; left:0; right:0;"></p>
								</div>
								<div class="col-1 black middle text-16 border-dark" style="border-bottom: none; border-right: none;">
										<p class="text-center text-bold-700" style="position: absolute; margin: auto; left:0; right:0;"></p>
								</div>
								<div class="col-2 black middle text-16 border-dark" style="border-bottom: none; ">
										<p class="text-center text-bold-700" style="position: absolute; margin: auto; left:0; right:0;"></p>
								</div>
						</div>
				@endfor
		@endif
		<div class="row row-flex">
				<div class="col-8 p-0 border-dark middle" style="border-right: none;">
						<h3 style="position: absolute; left: 0; right: 0; margin: auto;" class="text-center text-bold-700">
								Only {{$invoice->numberTowords($invoice->total)}} {{$invoice->type}}
						</h3>
				</div>
				<div class="col-sm-4 col-12 border-dark">
						@php
								$subTotal = (float) $invoice->sub_total;
								$discountType = $invoice->discount_type ?? 'percentage';
								$discountValue = (float) ($invoice->discount_value ?? 0);
								$discountAmount = (float) ($invoice->discount_amount ?? 0);
								$netSubTotal = max(0, $subTotal - $discountAmount);
								$taxAmount = (float) $invoice->tax;
								$withholdingRate = (float) $invoice->withholding;
								$withholdingAmount = ($netSubTotal * $withholdingRate) / 100;
								$totalAmount = (float) $invoice->total;
						@endphp
						<div class="table-responsive">
								<table class="table">
										<tbody>
												<tr>
														<td>Sub Total</td>
														<td class="text-right subtotal" style="font-weight: 700 !important;"> {{number_format($subTotal, 2, '.', '')}}  <span class="months">{{$invoice->type}}</span></td>
												</tr>
												@if($discountAmount > 0)
												<tr class="pink">
														<td>Discount @if($discountType == 'percentage')({{number_format($discountValue, 2, '.', '')}}%)@endif</td>
														<td class="text-right discount" style="font-weight: 700 !important;">- {{number_format($discountAmount, 2, '.', '')}} <span class="months">{{$invoice->type}}</span></td>
												</tr>
												@endif
												<tr class="pink">
														<td>TAX (@if($taxAmount == 0) 0% @else 14% @endif)</td>
														<td class="text-right tax" style="font-weight: 700 !important;"> {{number_format($taxAmount, 2, '.', '')}}  <span class="months">{{$invoice->type}}</span></td>
												</tr>
												<tr class="pink">
														<td>Withholding TAX ( {{number_format($withholdingRate, 2, '.', '')}}% )</td>
														<td class="text-right tax" style="font-weight: 700 !important;">- {{number_format($withholdingAmount, 2, '.', '')}} <span class="months">{{$invoice->type}}</span></td>
												</tr>
												<tr>
														<td class="text-bold-800">Total</td>
														<td class="text-bold-800 text-right total" style="font-weight: 700 !important;"> {{number_format($totalAmount, 2, '.', '')}} <span class="months">{{$invoice->type}}</span></td>
												</tr>
										</tbody>
								</table>
						</div>
				</div>
		</div>
		<div class="row mt-1">
				<div class="col-12" style="padding-right: 0; padding-left: 0;">
						<h5 class="bg-dark white text-bold-700 pl-1 mb-0 border-dark" style="border-bottom: 0;">1. Payment Terms:</h5>
						@foreach(json_decode($invoice->terms) as $terms)
								<div class="row border-dark" style="margin: auto;">
										<div class="col-12 black middle text-16" >{{$terms->methodname}} - {{$terms->methoddesc}}</div>
								</div>
						@endforeach
						@if(count(json_decode($invoice->terms)) < 3)
								@for($i = 3-count(json_decode($invoice->terms)); $i > 0; $i--)
										<div class="row border-dark" style="margin: auto;">
												<div class="col-12 black middle text-16 " ><p><br /></p></div>
										</div>
								@endfor
						@endif
				</div>
		</div>
		<div class="row row-flex mt-1">

			<div class="col-9 p-0 border-dark" style="border-right: none;">
					@if ($invoice->invoice_company_type == \App\Models\WorkFlow\Invoice::$INVOICE_LTD_TYPE)
						<h6 class="bg-dark white mb-0 text-bold-600 border-dark pl-1 mb-1" style="padding-top: 5px; padding-bottom: 5px; border-top: none; border-left: none; border-right: none;">Bank Details: RIG SOLUTION ENGINEERING</h6>
						<h6 class="white text-bold-700 pl-1 mb-0" style="padding-top: 5px; padding-bottom: 5px;">Commercial International Bank (CIB) - Address: El-Meraj, City, Cairo, Egypt</h6>
						<p class="white text-bold-600 pl-1">EGP AC: 100056967332 - IBAN AC. NO: EG470010019600000100056967332 <br /> USD AC: 100056968247 - IBAN AC. NO: EG770010019600000100056968247</p>
						<br />
						<br />
				
					@else
						<h6 class="bg-dark white mb-0 text-bold-600 border-dark pl-1 mb-1" style="padding-top: 5px; padding-bottom: 5px; border-top: none; border-left: none; border-right: none;">Bank Details: RIG SOLUTION ENGINEERING</h6>
						<h6 class="white text-bold-700 pl-1 mb-0" style="padding-top: 5px; padding-bottom: 5px;">1- FABMISR Bank , Cairo, Egypt - SWIFT Code: NBADEGCAXXX</h6>
						<p class="white text-bold-600 pl-1">EGP AC: 004791650001 - IBAN AC. NO: EG570019003500000004791650001 <br /> USD AC: 004791650002 - IBAN AC. NO: EG300019003500000004791650002</p>
						<h6 class="white text-bold-700 pl-1 mb-0" style="padding-top: 5px; padding-bottom: 5px;">2- Commercial International Bank (CIB) Nasr City, Cairo, Egypt - SWIFT Code: CIBEEGCX014</h6>
						<p class="white text-bold-600 pl-1 mb-1">EGP AC: 100016303911 - IBAN AC. NO: EG730010001400000100016303911 <br /> USD AC: 100036213323 - IBAN AC. NO: EG640010001400000100036213323</p>
					@endif
				</div>
				<div class="col-3 p-0 border-dark text-center">
						<h6 class="bg-dark white mb-0 text-bold-600 border-dark pl-1" style="padding-top: 5px; padding-bottom: 5px; border-top: none; border-left: none; border-right: none;"> Issued By</h6>
						<h5 class="pl-1 pt-2" style="padding-top: 5px; padding-bottom: 5px;">{{$invoice->user->employee->name}}</h5>
						<img class="media-object" src="{{Storage::url('employees/')}}{{$invoice->user->employee->esign}}" alt="" style="max-width: 50%;">
				</div>
		</div>
@endpush
