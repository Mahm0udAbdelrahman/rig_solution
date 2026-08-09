@extends('layouts.app')

@extends('layouts.styles.paper')

@push('page_content')
		<div class="row page_in">
				<div class="col-md-8 bg-dark border-dark white" style="padding-top: 1rem !important; padding-bottom: 1rem !important;">
						<h3 class="mb-0 white text-bold-700">{{$page_name}}</h3>
						<p class="mb-0">{{$page_text}}</p>
				</div>
				<div class="col-md-2 border-dark emad-table middle just">
						<h3 class="mb-0 p-0 text-bold-700 emad-table-in ">JCF No.</h3>
				</div>
				<div class="col-md-2 border-dark emad-table middle just">
						<h3 class="mb-0 p-0 text-bold-700 emad-table-in ">{{$jobRequest->code}}</h3>
				</div>
		</div>
		<div class="row skin skin-square">
				@php($jcfComment = trim((string) ($jobRequestStatus->comment ?? '')))
				<div class="col-md-4 border-dark">
						<h5 class="mb-0 text-bold-700">Job Control Status</h5>
				</div>
				<div class="col-md-2 border-dark mid">
						<span class="noncheckedfrom @if($jcfComment === '') checked @endif"></span>
						<label>Open</label>
				</div>
				<div class="col-md-2 border-dark mid">
						<span class="noncheckedfrom @if($jcfComment !== '') checked @endif"></span>
						<label>Close</label>
				</div>
				<div class="col-md-2 border-dark mid">
						<span class="noncheckedfrom @if($jobRequestStatus->comment == 'cm') checked @endif"></span>
						<label>Completed</label>
				</div>
				<div class="col-md-2 border-dark mid">
						<span class="noncheckedfrom @if($jobRequestStatus->comment == 'cc') checked @endif"></span>
						<label>Cancelled</label>
				</div>
		</div>
		<div class="row skin skin-flat">
				<div class="col-md-2 bg-dark border-dark middle">
						<h5 class="mb-0 white text-bold-700">@if($jobRequest->client != NULL) Client @else Supplier @endif Name</h5>
				</div>
				<div class="col-md-2 border-dark middle black text-16">
						@if($jobRequest->client != NULL)
								{{$jobRequest->client->name}}
						@else
								{{$jobRequest->supplier->name}}
						@endif
				</div>
				<div class="col-md-2 bg-dark border-dark middle">
						<h5 class="mb-0 white text-bold-700">@if($jobRequest->client != NULL) Client @else Supplier @endif Code</h5>
				</div>
				<div class="col-md-2 border-dark middle black text-16">
						@if($jobRequest->client != NULL)
								{{$jobRequest->client->code}}
						@else
								{{$jobRequest->supplier->code}}
						@endif
				</div>
				<div class="col-md-2 bg-dark border-dark middle">
						<h5 class="mb-0 white text-bold-700">Contact Name</h5>
				</div>
				<div class="col-md-2 border-dark middle black text-16">
						@if($jobRequest->contactPeopleShow)
								{{$jobRequest->contactPeopleShow->name}}
						@endif
				</div>
		</div>
		<div class="row skin skin-square">
				<div class="col-md-2 bg-dark border-dark">
					<h5 class="mb-0 white text-bold-700">Contact Way</h5>
				</div>
				<div class="col-md-6 border-dark mid">
						@if(in_array("phone", json_decode($jobRequest->contactway)))
								<span class="noncheckedfrom checked"></span>
								<label style="margin-right: 0.6rem;">Phone: @if($jobRequest->contactPeopleShow) {{$jobRequest->contactPeopleShow->tel}} @endif</label>
						@endif
						@if(in_array("email", json_decode($jobRequest->contactway)))
								<span class="noncheckedfrom checked"></span>
								<label>E-mail: {{$jobRequest->contactPeopleShow->email}}</label>
						@endif
				</div>
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">Date/Time</h5>
				</div>
				<div class="col-md-2 border-dark middle black">
						{{$contact_date ?? ''}}
				</div>
		</div>
		<div class="row skin skin-square">
				<div class="col-md-2 bg-dark border-dark middle">
						<h5 class="mb-0 white text-bold-700">Subject</h5>
				</div>
				<div class="col-md-10 border-dark black text-16" style="height: 80px;">
						{{$jobRequest->subject}}
				</div>
		</div>
		<div class="row skin skin-square">
				<div class="col-md-2 bg-dark border-dark">
						<h5 class="mb-0 white text-bold-700">Work Location</h5>
				</div>
				<div class="col-md-10 border-dark">
						<div class="row">
								<div class="col-md-3 col-sm-12 mid" style="border-right: 1px solid #424242 !important;">
										<span class="noncheckedfrom @if(in_array("rse-yard", json_decode($jobRequest->work_location))) checked @endif"></span>
										<label style="margin-top: 1px; margin-bottom: 2px;">RSE Yard</label>
								</div>
								<div class="col-md-3 col-sm-12 mid" style="border-right: 1px solid #424242 !important;">
										<span class="noncheckedfrom @if(in_array("rse-lab", json_decode($jobRequest->work_location))) checked @endif"></span>
										<label>RSE Lab</label>
								</div>
								<div class="col-md-3 col-sm-12 mid" style="border-right: 1px solid #424242 !important;">
										<span class="noncheckedfrom @if(in_array("client-location", json_decode($jobRequest->work_location))) checked @endif"></span>
										<label>Client Location</label>
								</div>
								<div class="col-md-3 col-sm-12 mid" style="border-right: 1px solid #424242 !important;">
										<span class="noncheckedfrom @if(in_array("other1", json_decode($jobRequest->work_location))) checked @endif"></span>
										<label>Other</label>
								</div>
						</div>
				</div>
		</div>
		<div class="row skin skin-square">
				<div class="col-md-2 bg-dark border-dark middle">
						<h5 class="mb-0 white text-bold-700">Department</h5>
				</div>
				<div class="col-md-10 border-dark">
						<div class="row">
						@foreach($departments as $key=>$department)
									<div class="col-md-4 col-sm-12 mid" style="border-right: 1px solid #424242 !important;">
											<span class="noncheckedfrom
												@foreach($jobRequest->departments as $jobRequestDepartment)
														@if($jobRequestDepartment->id == $department->id)
																checked
														@endif
												@endforeach">
											</span>
											<label>{{$department->name}}</label>
									</div>
						@endforeach
						</div>
				</div>
		</div>
		<div class="row">

				<div class="col-md-2 bg-dark border-dark middle">
					<h5 class="mb-0 white text-bold-700">Job Required Details</h5>
				</div>
				<div class="col-md-10 border-dark black text-16 middle" style="height: 117px;">
						{!!html_entity_decode($jobRequest->job_requierd_details)!!}
				</div>
		</div>
		<div class="row mt-1">
				<div class="col-md-12 bg-dark white border-dark" style="padding-top: 1rem !important; padding-bottom: 1rem !important;">
						<h3 class="mb-0 white text-bold-700">JOB ORDER INSTRUCTION</h3>
						<p class="mb-0">(to be filled by Authorized department Person)</p>
				</div>
		</div>
		<div class="row skin skin-square">
				<div class="col-md-1 border-dark middle">
						<h5 class="mb-0 text-bold-700">ATT.:</h5>
				</div>
				<div class="col-md-4 border-dark " style="height: 66px;">
						<div class="row">
								@foreach(json_decode($jobRequest->managers) as $key=>$manager)
										<div class="col-md-12 col-sm-12 mid">
												<span class="noncheckedfrom checked"></span>
												<label>{{ucwords($manager)}}</label>
										</div>
								@endforeach
						</div>
				</div>
				{{--<div class="col-md-3 border-dark middle">--}}
						{{--<h5 class="mb-0 text-bold-700 text-16">Date : {{$jobRequestStatus->start_at}}</h5>--}}
				{{--</div>--}}
				<div class="col-md-2 border-dark p-0" style="padding: 0 !important;">
						<h5 class="mb-0 text-bold-700 bg-dark white text-16 pl-1" style="border-bottom: 1px solid #424242;">Purchase Order</h5>
						<p class="pl-1 black text-16">{{$jobRequest->purchase_order}}</p>
				</div>
			@if($jobRequest->client != NULL)
				<div class="col-md-2 border-dark p-0" style="padding: 0 !important;">
					<h5 class="mb-0 text-bold-700 bg-dark white text-16 pl-1" style="border-bottom: 1px solid #424242;">
						Department</h5>
					<p class="pl-1 black text-16">{{$jobRequest->clientDepartment ? $jobRequest->clientDepartment->name: '-'}}</p>
				</div>

				<div class="col-md-3 border-dark p-0" style="padding: 0 !important;">
					<h5 class="mb-0 text-bold-700 bg-dark white text-16 pl-1" style="border-bottom: 1px solid #424242;">
						Location</h5>
					<p class="pl-1 black text-16">{{$jobRequest->deploc}}</p>
				</div>
			@else
				<div class="col-md-5 border-dark p-0" style="padding: 0 !important;">
					<h5 class="mb-0 text-bold-700 bg-dark white text-16 pl-1" style="border-bottom: 1px solid #424242;">
						Location</h5>
					<p class="pl-1 black text-16">{{$jobRequest->deploc}}</p>
				</div>

			@endif
		</div>
		<div class="row skin skin-square">
				<div class="col-md-2 bg-dark border-dark middle">
					<h6 class="mb-0 white text-bold-700">Personal name/Qualifications Required</h6>
				</div>
				<div class="col-md-4 border-dark" style="min-height: 61px;">
						<div class="row">
								@foreach($jobRequest->employees->filter(function($emp) { return !$emp->is_assistant; }) as $key => $employee)
										<div class="col-md-6 col-sm-12 mid">
											<span class="noncheckedfrom checked"></span>
											<label>{{$employee->name}}</label>
										</div>
								@endforeach
						</div>
				</div>
				<div class="col-md-2 bg-dark border-dark middle">
					<h6 class="mb-0 white text-bold-700">Assistant Required</h6>
				</div>
				<div class="col-md-4 border-dark" style="min-height: 61px;">
						<div class="row">
								@foreach($jobRequest->employees->filter(function($emp) { return (bool)$emp->is_assistant; }) as $key => $employee)
										<div class="col-md-6 col-sm-12 mid">
											<span class="noncheckedfrom checked"></span>
											<label>{{$employee->name}}</label>
										</div>
								@endforeach
						</div>
				</div>
		</div>
		<div class="row skin skin-square">
				<div class="col-md-2 bg-dark border-dark middle">
						<h5 class="mb-0 white text-bold-700">Equipment / Material Required</h5>
				</div>
				<div class="col-md-10 border-dark" style="height: 94px;">
						<div class="row">
								@foreach(json_decode($jobRequest->tools) as $key => $tool)
										<div class="col-md-3 col-sm-12 mid">
												<span class="noncheckedfrom checked"></span>
												<label for="{{$tool}}">{{str_replace('-', ' ',$tool)}}</label>
										</div>
								@endforeach
						</div>
				</div>
		</div>
		<div class="row">
				<div class="col-md-2 bg-dark border-dark middle">
						<h5 class="mb-0 white text-bold-700">Scope of Work</h5>
				</div>
				<div class="col-md-10 border-dark black text-16 middle" style="height: 185px;">
						{!!html_entity_decode($jobRequest->scope_of_work)!!}
				</div>
		</div>
		<div class="row skin skin-square">
				<div class="col-md-2 bg-dark border-dark middle">
						<h5 class="mb-0 white text-bold-700">Specification</h5>
				</div>
				<div class="col-md-10 border-dark">
						<div class="row">
								@foreach($specifications as $key => $specification)
										<div class="col-md-4 col-sm-12 mid" style="border-right: 1px solid #424242 !important;">
												<span class="noncheckedfrom @if(in_array(strtolower(str_replace(' ', '-',$specification->name)), json_decode($jobRequest->specification))) checked @endif"></span>
												<label>{{$specification->name}}</label>
										</div>
								@endforeach
						</div>
				</div>
		</div>
		<div class="row mt-1">
				<div class="col-md-12 bg-dark white border-dark" style="padding-top: 1rem !important; padding-bottom: 1rem !important;">
						<h3 class="mb-0 white text-bold-700">DOCUMENT INFORMATION</h3>
						<p class="mb-0">(to be filled by specific department Person)</p>
				</div>
		</div>
		<div class="row">
				<div class="col-md-4" style="padding-right: 14px;">
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700">Quot No.</h5>
								</div>
								<div class="col-md-8 border-dark black middle text-16">
										@if($jobRequest->qutation)
												{{$jobRequest->qutation->code}}
										@endif
								</div>
						</div>
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700 text-16">Inqu. No.</h5>
								</div>
								<div class="col-md-8 border-dark middle">

								</div>
						</div>
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700 text-16">P.K No.</h5>
								</div>
								<div class="col-md-8 border-dark black middle text-16">
										@if($jobRequest->packingSlip)
												{{$jobRequest->packingSlip->code}}
										@endif
								</div>
						</div>
				</div>
				<div class="col-md-4">
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h6 class="mb-0 white text-bold-700">Start Date</h5>
								</div>
								<div class="col-md-8 border-dark black middle text-16">
										{{$document_start_at ?? ''}}
								</div>
						</div>
						<div class="row  ">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700 text-16">MR No.</h5>
								</div>
								<div class="col-md-8 border-dark middle">

								</div>
						</div>
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700 text-16">S.T No.</h5>
								</div>
								<div class="col-md-8 border-dark black middle text-16">
										@if($jobRequest->serviceTicket)
											{{$jobRequest->serviceTicket->code}}
										@endif
								</div>
						</div>
				</div>
				<div class="col-md-4">
						<div class="row  ">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700 text-16">End Date</h5>
								</div>
								<div class="col-md-8 border-dark middle text-16 black">
										{{$document_end_at ?? ''}}
								</div>
						</div>
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h5 class="mb-0 white text-bold-700 text-16">MRR No.</h5>
								</div>
								<div class="col-md-8 border-dark middle">

								</div>
						</div>
						<div class="row">
								<div class="col-md-4 bg-dark border-dark">
										<h6 class="mb-0 white text-bold-700 text-16" style="font-size: 110%;">Invoice No.</h5>
								</div>
								<div class="col-md-8 border-dark middle text-16 black">
										@if($jobRequest->invoice)
												{{$jobRequest->invoice->code}}
										@endif
								</div>
						</div>
				</div>
		</div>
@endpush
