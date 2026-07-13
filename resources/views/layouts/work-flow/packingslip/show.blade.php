@extends('layouts.app')

@extends('layouts.styles.paper')

@push('page_content')
		<div class="row page_in">
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">Client</h5>
				</div>
				<div class="col-md-10 border-dark black middle text-16">
						{{$packingSlip->jobRequest->client->name}}
				</div>
		</div>
		<div class="row">
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">Location</h5>
				</div>
			<div class="col-md-10 border-dark black middle text-16">
				{{$packingSlip->jobRequest->clientDepartment ?
				 $packingSlip->jobRequest->clientDepartment->name . ' / '.$packingSlip->jobRequest->deploc
				 : $packingSlip->jobRequest->deploc}}
			</div>
		</div>
		<div class="row">
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">JCF Number</h5>
				</div>
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">Qutation No.</h5>
				</div>
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">Client P.O</h5>
				</div>
				<div class="col-md-4 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">Shipping Methods</h5>
				</div>
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">Order Date</h5>
				</div>
		</div>
		<div class="row">
				<div class="col-md-2 border-dark black middle text-16">
						{{$packingSlip->jobRequest->code}}
				</div>
				<div class="col-md-2 border-dark black middle text-16">
						@if($packingSlip->jobRequest->qutation)
								{{$packingSlip->jobRequest->qutation->code}}
						@endif
				</div>
				<div class="col-md-2 border-dark black middle text-16">
						{{$packingSlip->po}}
				</div>
				<div class="col-md-4 border-dark black middle text-16">
						{{$packingSlip->shppingmethods}}
				</div>
				<div class="col-md-2 border-dark black middle text-16">
						{{$packingSlip->orderdate}}
				</div>
		</div>
		<div class="row mt-1">
				<div class="col-1 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">Code</h5>
				</div>
				<div class="col-8 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">Item Description</h5>
				</div>
				<div class="col-1 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">Type</h5>
				</div>
				<div class="col-1 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">Orderd QTY</h5>
				</div>
				<div class="col-1 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">Shipping QTY</h5>
				</div>
		</div>
		@if($packingSlip->items != "null")
				@foreach(json_decode($packingSlip->items) as $item)
						<div class="row border-dark">
								<div class="col-1 black middle text-16" style="border-right: 1px solid #000;">{{$item->itemcode}}</div>
								<div class="col-8 black text-16 middle" style="height: 58px; border-right: 1px solid #000;">{{$item->itemdesc}}</div>
								<div class="col-1 black middle text-16"  style="border-right: 1px solid #000;">
										@if(isset($item->iper))
												@if($item->iper !== "null")
														{{ucwords($item->iper)}}
												@endif
										@endif
								</div>
								<div class="col-1 black middle text-16" style="border-right: 1px solid #000;" >{{$item->orderdqty}}</div>
								<div class="col-1 black middle text-16">{{$item->shippingqty}}</div>
						</div>
				@endforeach
				@if(count(json_decode($packingSlip->items)) < 12)
						@for($i = 12-count(json_decode($packingSlip->items)); $i > 0; $i--)
								<div class="row border-dark">
										<div class="col-1 black middle text-16" style="border-right: 1px solid #000;" ><p class="text-center" style="position: absolute; margin: auto; left:0; right:0;"></p></div>
										<div class="col-8 black text-16" style="height: 58px; border-right: 1px solid #000;"></div>
										<div class="col-1 black middle text-16 " style="border-right: 1px solid #000;" ><p class="text-center" style="position: absolute; margin: auto; left:0; right:0;"></p></div>
										<div class="col-1 black middle text-16 " style="border-right: 1px solid #000;"><p class="text-center" style="position: absolute; margin: auto; left:0; right:0;"></div>
										<div class="col-1 black middle text-16 " ><p class="text-center" style="position: absolute; margin: auto; left:0; right:0;"></p></div>
								</div>
						@endfor
				@endif
		@endif
		<div class="row mt-1">
				<h5 class="col-2 mb-0 white text-bold-700 bg-dark border-dark middle" style="width: 100%;">Acceptance Notice</h5>
				<p class="col-10 border-dark black middle text-16" style="height: 100px;">{{$packingSlip->notice}}</p>
		</div>
		<div class="row mt-1">
				<h5 class="col-12 mb-0 white text-bold-700 bg-dark border-dark middle" style="width: 100%;">Transportation Method</h5>
		</div>
		<div class="row">
				<div class="col-6">
						<div class="row" style="margin-top: 8px;">
								<h6 class="col-3 mb-0 white text-bold-700 middle" style="width: 100%;">Transportation Company</h5>
								<p class="col-9 border-dark" style="height: 50px;"></p>
						</div>
						<div class="row" style="margin-top: 8px;">
								<h6 class="col-3 mb-0 white text-bold-700 middle" style="width: 100%;">Vehicle Plate No.</h5>
								<p class="col-9 border-dark" style="height: 50px;"></p>
						</div>
				</div>
				<div class="col-6">
				<div class="row" style="margin-top: 8px;">
						<h6 class="col-3 mb-0 white text-bold-700 middle" style="width: 100%; padding-left: 30px;">Vehicle Driver Name</h5>
						<p class="col-9 border-dark" style="height: 50px;"></p>
				</div>
				<div class="row" style="margin-top: 8px;">
						<h6 class="col-3 mb-0 white text-bold-700 middle" style="width: 100%; padding-left: 30px;">Driver Signature</h5>
						<p class="col-9 border-dark" style="height: 50px;"></p>
				</div>
				</div>
		</div>
		<div class="row mt-1">
				<div class="col-6 pl-0 pr-0">
						<h5 class="white text-bold-700 bg-dark border-dark middle pl-1" style="width: 100%;">Received By</h5>
						<div class="row mr-0" style="margin-top: 8px;">
								<h6 class="col-3 mb-0 white text-bold-700 middle" style="width: 100%; padding-left: 30px;">Name</h5>
								<p class="col-9 border-dark" style="height: 50px;"></p>
						</div>
						<div class="row mr-0" style="margin-top: 8px;">
								<h6 class="col-3 mb-0 white text-bold-700 middle" style="width: 100%; padding-left: 30px;">Date</h5>
								<p class="col-9 border-dark" style="height: 50px;"></p>
						</div>
						<div class="row mr-0" style="margin-top: 8px;">
								<h6 class="col-3 mb-0 white text-bold-700 middle" style="width: 100%; padding-left: 30px;">Signature</h5>
								<p class="col-9 border-dark" style="height: 50px;"></p>
						</div>
				</div>
				<div class="col-6 pr-0">
						<h5 class="white text-bold-700 bg-dark border-dark middle pl-1" style="width: 100%;">Delivered By</h5>
						<div class="row mr-0" style="margin-top: 8px;">
								<h6 class="col-3 mb-0 white text-bold-700 middle" style="width: 100%; padding-left: 30px;">Name</h5>
								<p class="col-9 border-dark" style="height: 50px;"></p>
						</div>
						<div class="row mr-0" style="margin-top: 8px;">
								<h6 class="col-3 mb-0 white text-bold-700 middle" style="width: 100%; padding-left: 30px;">Date</h5>
								<p class="col-9 border-dark" style="height: 50px;"></p>
						</div>
						<div class="row mr-0" style="margin-top: 8px;">
								<h6 class="col-3 mb-0 white text-bold-700 middle" style="width: 100%; padding-left: 30px;">Signature</h5>
								<p class="col-9 border-dark" style="height: 50px;"></p>
						</div>
				</div>
		</div>
@endpush
