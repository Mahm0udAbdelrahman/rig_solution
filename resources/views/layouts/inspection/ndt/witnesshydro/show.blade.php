@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
<div class="row page_in">
	<div class="col-6 p-0">
		<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made
		</h6>
		<p class="border-dark pl-1 mb-0 text-16 black">
			@if($witnessHydro->job_request->client)
			{{$witnessHydro->job_request->client->name}}
			@else
			{{$witnessHydro->job_request->supplier->name}}
			@endif
		</p>
	</div>
	<div class="col-6 p-0">
		<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
		<p class="border-dark pl-1 mb-0 text-16 black">
			@if($witnessHydro->job_request->client)
			{{preg_replace('~[\\\\/:*?"<>[]|]~', '', $witnessHydro->job_request->client->location)}}
				@else
				{{preg_replace('~[\\\\/:*?"<>[]|]~', '', $witnessHydro->job_request->supplier->location)}}
					@endif
		</p>
	</div>
</div>
<div class="row">
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Purchase Order</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">
		{{$witnessHydro->job_request->purchase_order ? $witnessHydro->job_request->purchase_order
		:$witnessHydro->nwhr_2}}
	</div>
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">JCF Number</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->job_request->code}}</div>
	<div class="bg-dark col-2 p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Report No</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$witnessHydro->id}}">
		{{$witnessHydro->job_request->code}} / {{ preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $witnessHydro->code) ?: $witnessHydro->code }}
	</div>
</div>

<div class="row">
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Work location</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">
		{{$witnessHydro->job_request->clientDepartment ?
		$witnessHydro->job_request->clientDepartment->name . ' / '.$witnessHydro->job_request->deploc
		: $witnessHydro->job_request->deploc}}
	</div>
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Examination Date</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_6}}</div>
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Next Exa. Date</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_7}}</div>

</div>
<div class="row">
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Specification</h6>
	</div>
	<div class="col-6 p-0 pl-1 border-dark text-16 black">
		<div class="row skin skin-square">
			@foreach($specificationOptions as $key => $spec)
			@if($key != 'S-008')
			<div class="specinsp" style="margin-left: 8px;">
				<span
					class="noncheckedfrom {{in_array(strtolower(str_replace(' ', '-', $spec)), $witnessHydro->specifications)? 'checked' : ''}}"
					style="position: relative;top: 3px;"></span>
				<label>{{$spec}}</label>
			</div>
			@endif
			@endforeach
			<div class="specinsp" style="margin-left: 8px;">
				<span class="noncheckedfrom {{in_array('other', $witnessHydro->specifications)? 'checked' : ''}}"
					style="position: relative;top: 3px;"></span>
				<label>Other</label>
			</div>
		</div>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_10}}</div>
	<div class="col-1 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Edition</h6>
	</div>
	<div class="col-1 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_39}}</div>
</div>

<div class="row">
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Acceptance Criteria</h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->acceptance}}</div>
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Test Type</h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">
		<div class="row skin skin-square">
			{{--@foreach(json_decode($witnessHydro->nwhr_8) as $key => $spec)
			<div class="col-md-6 col-sm-12 mid">
				<span class="noncheckedfrom checked"></span>
				<label for="{{$spec}}">{{str_replace('-', ' ',$spec)}}</label>
			</div>
			@endforeach--}}

			@foreach(['hydrostatic', 'pneumatic'] as $item)
				<div class="col-md-6 col-sm-12 mid">
					<span class="noncheckedfrom
					@if(in_array($item, json_decode($witnessHydro->nwhr_8))) checked @endif"></span>

					<label for="{{$item}}">{{str_replace('-', ' ',$item)}}</label>
				</div>
			@endforeach

		</div>
	</div>
</div>
<div class="row">
	<div class="col-2 bg-dark p-0 border-dark middle">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Description</h6>
	</div>
	<div class="col-10 p-0 pl-1 border-dark text-16 black middle preline" style="height: 50px;">{{$witnessHydro->desc}}
	</div>
</div>
<div class="row">
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Identification No</h6>
	</div>
	<div class="col-10 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_15}}</div>
</div>
<div class="row">
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Pipe Test Data</h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_16}}</div>
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Length of Line</h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_17}}</div>
</div>
<div class="row">
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Type of Pipe</h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_18}}</div>
	<div class="col-2 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Size of Pipe</h6>
	</div>
	<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_19}}</div>
</div>
<div class="row">
	<div class="col-12 bg-dark p-0 border-dark">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Test Parameters</h6>
	</div>
</div>
<div class="row">
	<div class="col-6">
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Test Fluid</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_20}}</div>
		</div>
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Required test Pressure</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_21}}</div>
		</div>
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Test Fluid Temperature</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_22}}</div>
		</div>
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Test Temperature</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_23}}</div>
		</div>
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Start Time</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_24}}</div>
		</div>
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">End Time</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_25}}</div>
		</div>
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Test duration</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_26}}</div>
		</div>
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Actual Hold Time</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_27}}</div>
		</div>
		<div class="row">
			<div class="col-12 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Test Equipment / Pressure Range</h6>
			</div>
		</div>
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Pressure Range</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_28}}</div>
		</div>
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Type</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_29}}</div>
		</div>
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Actual Test</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_30}}</div>
		</div>
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Calibration Due Date</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_31}}</div>
		</div>
		<div class="row">
			<div class="col-12 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Test Pressure Result</h6>
			</div>
		</div>

		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Pressure Test Result</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{-- $witnessHydro->nwhr_32 --}}
				<div class="row skin skin-square">
					<fieldset class="col-md-5 ml-2">
						<span class="noncheckedfrom {{$witnessHydro->checkbox_yes($witnessHydro->nwhr_32)}}"></span>
						<label for="hydrostatic">Pass</label>
					</fieldset>
					<fieldset class="col-md-5">
						<span class="noncheckedfrom {{$witnessHydro->checkbox_no($witnessHydro->nwhr_32)}}"></span>
						<label for="pneumatic">Fail</label>
					</fieldset>
				</div>
			</div>
		</div>
		<div class="row skin skin-square">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Line Loss</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">
				<div class="row">
					<fieldset class="col-md-5 ml-2">
						<span class="noncheckedfrom {{$witnessHydro->checkbox_yes($witnessHydro->nwhr_33)}}"></span>
						<label for="hydrostatic">Yes</label>
					</fieldset>
					<fieldset class="col-md-5">
						<span class="noncheckedfrom {{$witnessHydro->checkbox_no($witnessHydro->nwhr_33)}}"></span>
						<label for="pneumatic">No</label>
					</fieldset>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-4 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Amount Loss</h6>
			</div>
			<div class="col-8 p-0 pl-1 border-dark text-16 black">{{$witnessHydro->nwhr_34}}</div>
		</div>
		<div class="row">
			<div class="col-12 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Reason of Line loss</h6>
			</div>
		</div>
		<div class="row">
			<div class="col-12 p-0 pl-1 border-dark text-16 black preline" style="height: 80px;">
				{{$witnessHydro->nwhr_35}}</div>
		</div>
	</div>
	<div class="col-6 p-0 pl-1 border-dark" style="height: 584px;">
		<img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;"
			src="{{Storage::url('camera/inspection/ndt/witnesshydro/')}}{{$witnessHydro->nwhr_36}}" alt="" />
	</div>
</div>
<div class="row">
	<div class="col-2 bg-dark p-0 border-dark middle">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Corrective Measures Taken</h6>
	</div>
	<div class="col-10 p-0 pl-1 border-dark text-16 black middle preline" style="height: 110px;">
		{{$witnessHydro->nwhr_37}}</div>
</div>

<div class="row border-dark" style="margin-bottom: 3px;">
	<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Final Conclusion:</h6>

	<div class="col-6">
		<p class="black bnew">Final result Accept / Reject ?</p>
	</div>
	<div class="col-2 mid">
		<span class="noncheckedfrom {{$witnessHydro->checkbox_yes($witnessHydro->nwhr_38)}}"
			style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
		<label class="black" style="font-size: 1.3rem; font-weight: 600;">Accept</label>
	</div>
	<div class="col-2 mid">
		<span class="noncheckedfrom {{$witnessHydro->checkbox_no($witnessHydro->nwhr_38)}}"
			style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
		<label class="black" style="font-size: 1.3rem; font-weight: 600;">Reject</label>
	</div>
</div>
@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $witnessHydro->report->id])
@endpush
