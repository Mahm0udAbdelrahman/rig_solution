@extends('layouts.app')

@extends('layouts.styles.paper')

@push('page_content')
		<div class="row page_in">
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">Client</h5>
				</div>
				<div class="col-md-6 border-dark black middle text-16">
						@if($serviceTicket->jobRequest->client)
								{{$serviceTicket->jobRequest->client->name}}
						@else
								{{$serviceTicket->jobRequest->supplier->name}}
						@endif
				</div>
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">Start Date</h5>
				</div>
				<div class="col-md-2 border-dark black middle text-16">
						{{$serviceTicket->start}}
				</div>
		</div>
		<div class="row">
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">Location</h5>
				</div>
				<div class="col-md-6 border-dark black middle text-16">
					{{$serviceTicket->jobRequest->clientDepartment ?
				 $serviceTicket->jobRequest->clientDepartment->name . ' / '.$serviceTicket->jobRequest->deploc
				 : $serviceTicket->jobRequest->deploc}}
				</div>
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">End Date</h5>
				</div>
				<div class="col-md-2 border-dark black middle text-16">
						{{$serviceTicket->end}}
				</div>
		</div>
		<div class="row">
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">JCF Number</h5>
				</div>
				<div class="col-md-2 border-dark black middle text-16">
						{{$serviceTicket->jobRequest->code}}
				</div>
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">Qutation No.</h5>
				</div>
				<div class="col-md-2 border-dark black middle text-16">
						@if($serviceTicket->jobRequest->qutation)
								{{$serviceTicket->jobRequest->qutation->code}}
						@endif
				</div>
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">P.K Slip Number</h5>
				</div>
				<div class="col-md-2 border-dark black middle text-16">
						@if($serviceTicket->jobRequest->packingSlip)
								{{$serviceTicket->jobRequest->packingSlip->code}}
						@endif
				</div>
		</div>
		<div class="row mt-1">
				<div class="col-7 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">Service Description</h5>
				</div>
				<div class="col-1 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">QTY</h5>
				</div>
				<div class="col-1 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">Size</h5>
				</div>
				<div class="col-1 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">Range</h5>
				</div>
				<div class="col-2 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">Connection Type</h5>
				</div>
		</div>
		@foreach(json_decode($serviceTicket->services) as $service)
				<div class="row border-dark">
						<div class="col-7 black middle text-16" style="height: 58px; border-right: 1px solid #000;">{{$service->descTextarea}}</div>
						<div class="col-1 black middle text-16 text-center" style=" border-right: 1px solid #000;" >

							 @if($service->pquantity != ''){{$service->pquantity}} @else -- @endif @if(isset($service->iper)) @if($service->iper !== "null") / {{ucwords($service->iper)}} @endif @endif</div>
						<div class="col-1 black middle text-16" style=" border-right: 1px solid #000;" >{{$service->size}}</div>
						<div class="col-1 black middle text-16" style=" border-right: 1px solid #000;" >{{$service->range}}</div>
						<div class="col-2 black middle text-16" >{{$service->ctype}}</div>
				</div>
		@endforeach
		@if(count(json_decode($serviceTicket->services)) < 15)
				@for($i = 15-count(json_decode($serviceTicket->services)); $i > 0; $i--)
						<div class="row border-dark">
								<div class="col-7 black middle text-16" style="height: 58px; border-right: 1px solid #000;"></div>
								<div class="col-1 black middle text-16" style=" border-right: 1px solid #000;" ></div>
								<div class="col-1 black middle text-16" style=" border-right: 1px solid #000;" ></div>
								<div class="col-1 black middle text-16" style=" border-right: 1px solid #000;" ></div>
								<div class="col-2 black middle text-16" ></div>
						</div>
				@endfor
		@endif
		<div class="row mt-1">
				<h5 class="col-12 mb-0 white text-bold-700 bg-dark border-dark" style="width: 100%;">Notice:(To mention any notices during the job here)</h5>
				<p class="col-12 border-dark black middle text-16" style="height: 120px;">{{$serviceTicket->notice}}</p>
		</div>
		<div class="row mt-1">
				<h5 class="white text-bold-700 col-6 mid" style="padding: 0;">RSE Representative</h5>
				<h5 class="white text-bold-700 col-6 mid" style="padding: 0;">Client Approval</h5>
		</div>
		<div class="row">
				<div class="col-md-6" style="padding-right: 14px;">
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700">Name</h5>
								</div>
								<div class="col-md-8 border-dark black middle text-16">

												{{$serviceTicket->user->employee->name}}

								</div>
						</div>
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700">Signature</h5>
								</div>
								<div class="col-md-8 border-dark black middle text-16" style="height: 115px !important;">

												<img class="media-object" style="max-width: 100%;
width: max-content;
height: 100%;
margin: auto;" src="{{Storage::url('employees/')}}{{$serviceTicket->user->employee->esign}}" alt="" >

								</div>
						</div>
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700 text-16">Date</h5>
								</div>
								<div class="col-md-8 border-dark middle"></div>
						</div>
				</div>
				<div class="col-md-6">
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700 text-16">Name</h5>
								</div>
								<div class="col-md-8 border-dark middle text-16 black"></div>
						</div>
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700 text-16">Signature</h5>
								</div>
								<div class="col-md-8 border-dark middle" style="height: 115px !important;"></div>
						</div>
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700 text-16">Date</h5>
								</div>
								<div class="col-md-8 border-dark middle"></div>
						</div>
				</div>
		</div>
@endpush
