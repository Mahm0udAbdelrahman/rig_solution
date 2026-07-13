@extends('layouts.app')

@extends('layouts.styles.paper')

@push('page_content')
		<div class="row border-dark white page_in" style="padding: 0 !important;">
				<div class="col-2 bg-dark">
						<h5 class="mb-0 white text-bold-700">Client:</h5>
				</div>
				<div class="col-10 text-bold-600 border-dark" style="border-top: none; border-bottom: none; font-size: 16px;">
						{{$qutation->jobRequest->client->name}}
				</div>
		</div>
		<div class="row border-dark white" style="padding: 0 !important;">
				<div class="col-2 bg-dark">
						<h5 class="mb-0 white text-bold-700">Subject:</h5>
				</div>
				<div class="col-10 border-dark"style="border-top: none; border-bottom: none; border-right: none; font-size: 16px;">
						{{$qutation->subject}}
				</div>
		</div>
		<div class="row border-dark white" style="padding: 0 !important;">
				<div class="col-2 bg-dark">
						<h5 class="mb-0 white text-bold-700">ATT.:</h5>
				</div>
				<div class="col-6 border-dark" style="border-top: none; border-bottom: none; font-size: 16px;">
						@if($qutation->jobRequest->contactPeopleShow)
								{{$qutation->jobRequest->contactPeopleShow->name}}
						@endif
				</div>
				<div class="col-2 bg-dark">
						<h5 class="mb-0 white text-bold-700">Date:</h5>
				</div>
				<div class="col-2 border-dark"style="border-top: none; border-bottom: none; border-right: none; font-size: 16px;">
						{{$qutation->creation_date}}
				</div>
		</div>
		<div class="row mt-1">
				<div class="col-2 border-dark bg-dark white text-center">
						<h5 class="mb-0 white text-bold-700">JCF No</h5>
				</div>
				<div class="col-2 border-dark bg-dark white text-center">
						<h5 class="mb-0 white text-bold-700">Client Ref.</h5>
				</div>
				<div class="col-4 border-dark bg-dark white text-center">
						<h5 class="mb-0 white text-bold-700">Delivery Promise</h5>
				</div>
				<div class="col-2 border-dark bg-dark white text-center">
						<h5 class="mb-0 white text-bold-700">Delivery Location</h5>
				</div>
				<div class="col-2 border-dark bg-dark white text-center">
						<h5 class="mb-0 white text-bold-700">Currency Code</h5>
				</div>
		</div>
		<div class="row mb-1">
				<div class="col-2 border-dark black  text-16 text-center">
						{{$qutation->jobRequest->code}}
				</div>
				<div class="col-2 border-dark black  text-16 text-center">
						{{$qutation->jobRequest->client->code}}
				</div>
				<div class="col-4 border-dark black  text-16 text-center">
						{{$qutation->delivery}}
				</div>
				<div class="col-2 border-dark black  text-16 text-center">
						{{$qutation->location}}
				</div>
				<div class="col-2 border-dark black  text-16 text-center">
						{{$qutation->type}}
				</div>
		</div>
		<div class="row">
				<div class="col-1 border-dark bg-dark white text-center" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">Code</h5>
				</div>
				<div class="col-7 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">DESCRIPTION</h5>
				</div>
				<div class="col-1 border-dark bg-dark white text-center" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">QTY</h5>
				</div>
				<div class="col-1 border-dark bg-dark white text-center" style="padding-top: 5px; padding-bottom: 5px;">
						<h5 class="mb-0 white text-bold-700">Price</h5>
				</div>
				<div class="col-2 border-dark bg-dark white text-center" style="padding-top: 5px; padding-bottom: 5px;">
					<h5 class="mb-0 white text-bold-700">Amount</h5>
				</div>
		</div>
		@foreach(json_decode($qutation->items) as $key => $item)
				<div class="row border-dark">
						<div class="col-1 black middle text-16" style="border-right: 1px solid #000;" ><p class="text-center" style="position: absolute; margin: auto; left:0; right:0;">{{$item->icode}}</p></div>
						<div class="col-7 black text-16 middle" style="height: 58px; border-right: 1px solid #000;">{{$item->idesc}}</div>
						<div class="col-1 black middle text-16 " style="border-right: 1px solid #000;" ><p class="text-center" style="position: absolute; margin: auto; left:0; right:0;">{{$item->pquantity}}</p></div>
						<div class="col-1 black middle text-16 " style="border-right: 1px solid #000;">@if($item->iper !== "null")<p class="text-center" style="position: absolute; margin: auto; left:0; right:0;">{{$item->price}} / {{ucwords($item->iper)}}</p>@endif</div>
						<div class="col-2 black middle text-16 " ><p class="text-center" style="position: absolute; margin: auto; left:0; right:0;">{{$item->pamount}}</p></div>
				</div>
		@endforeach
		@if(count(json_decode($qutation->items)) < 10)
				@for($i = 10-count(json_decode($qutation->items)); $i > 0; $i--)
						<div class="row border-dark">
								<div class="col-1 black middle text-16" style="border-right: 1px solid #000;" ><p class="text-center" style="position: absolute; margin: auto; left:0; right:0;"></p></div>
								<div class="col-7 black text-16" style="height: 58px; border-right: 1px solid #000;"></div>
								<div class="col-1 black middle text-16 " style="border-right: 1px solid #000;" ><p class="text-center" style="position: absolute; margin: auto; left:0; right:0;"></p></div>
								<div class="col-1 black middle text-16 " style="border-right: 1px solid #000;"><p class="text-center" style="position: absolute; margin: auto; left:0; right:0;"></div>
								<div class="col-2 black middle text-16 " ><p class="text-center" style="position: absolute; margin: auto; left:0; right:0;"></p></div>
						</div>
				@endfor
		@endif
		<div class="row row-flex mt-1">
				<div class="col-12 p-0">
						<h5 class="bg-dark white text-bold-700 pl-1 mb-0 border-dark">1. General Terms & Conditions:</h5>
						@foreach(json_decode($qutation->terms) as $terms)
								<div class="row border-dark" style="margin: auto;">
										<div class="col-12 black middle text-16" >{{$terms->conditionname}} - {{$terms->conditiondesc}}</div>
								</div>
						@endforeach
						@if(count(json_decode($qutation->terms)) < 6)
								@for($i = 6-count(json_decode($qutation->terms)); $i > 0; $i--)
										<div class="row border-dark" style="margin: auto;">
												<div class="col-12 black middle text-16" ><p><br /></p></div>
										</div>
								@endfor
						@endif
						<h5 class="bg-dark white text-bold-700 pl-1 mb-0 mt-1 border-dark">2. Payment Terms:</h5>
						@foreach(json_decode($qutation->payment_method) as $payment_method)
								<div class="row border-dark" style="margin: auto;">
										<div class="col-12 black middle text-16 " >{{$payment_method->methodname}} - {{$payment_method->methoddesc}}</div>
								</div>
						@endforeach
						@if(count(json_decode($qutation->payment_method)) < 3)
								@for($i = 3-count(json_decode($qutation->payment_method)); $i > 0; $i--)
										<div class="row border-dark" style="margin: auto;">
												<div class="col-12 black middle text-16 " ><p><br /></p></div>
										</div>
								@endfor
						@endif
				</div>
		</div>
		<div class="row row-flex mt-1">
				<div class="col-4 p-0 border-dark text-center" style="padding-top: 0 !important;">
						<h6 class="bg-dark white mb-0 text-bold-600 border-dark"> Prepared By</h6>
						<h5 class="pl-1 pt-2" style="padding-top: 5px; padding-bottom: 5px;">{{$qutation->user->employee->name}}</h5>
						<img class="media-object" src="{{Storage::url('employees/')}}{{$qutation->user->employee->esign}}" alt="" style="max-width: 50%;">
				</div>
				<div class="col-4 p-0 border-dark" style="padding-top: 0 !important;">
						<h6 class="bg-dark white text-center text-bold-600 mb-0 border-dark"> Stamp</h6>
						<div class="text-center">
								<img src="{{asset('app-assets/images/logo/stamp.jpeg')}}" style="max-width: 40%; position: relative;" />
						</div>
				</div>
				<div class="col-4 p-0 border-dark text-center" style="padding-top: 0 !important;">
						<h6 class="bg-dark white text-bold-600 mb-0 border-dark"> Approved By</h6>
						@if($qutation->user_id_approved==Null)
								@if(Auth::user()->hasPermission('qutation', 'approve'))
										<button type="button" id="approve" class="btn btn-info btn-print btn-lg mr-1"><i class="la la-paper-plane-o mr-50"></i>Approve This Qutation</button>
								@else
										<p class="pl-1 mb-0 text-16 black info mt-2" style="padding-top: 5px; padding-bottom: 5px;">Waiting For Approve</p>
								@endif
						@else
								<h5 class="pl-1 pt-2 text-center" style="padding-top: 5px; padding-bottom: 5px;">{{$qutation->user_approved->employee->name}}</h5>
								<img class="media-object" src="{{Storage::url('employees/')}}{{$qutation->user_approved->employee->esign}}" alt="" style="max-width: 50%;">
						@endif
				</div>
		</div>
		@include('layouts.scripts.approve', ['table' => 'qutations', 'id' => $qutation->id])
@endpush
