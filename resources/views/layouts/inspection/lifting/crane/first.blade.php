@extends('layouts.styles.paper-v2')

@push('page_content')
		<div class="row page_in">
				<div class="col-6 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
						<p class="border-dark pl-1 mb-0 text-16 black">
								@if($crane->job_request->client)
										{{$crane->job_request->client->name}}
								@else
										{{$crane->job_request->supplier->name}}
								@endif
						</p>
				</div>
				<div class="col-6 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">
								@if($crane->job_request->client)
										{{preg_replace('~[\\\\/:*?"<>[]|]~', '', $crane->job_request->client->location)}}
								@else
										{{preg_replace('~[\\\\/:*?"<>[]|]~', '', $crane->job_request->supplier->location)}}
								@endif
						</p>
				</div>
		</div>
		<div class="row">
				<div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Purchase Order</h6></div>
				<div class="col-2 p-0 pl-1 border-dark text-16 black">
					{{$crane->job_request->purchase_order ? $crane->job_request->purchase_order : $crane->lcr_2}}
				</div>
				<div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">JCF Number</h6></div>
				<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$crane->job_request->code}}</div>
				<div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Report No</h6></div>
				<div class="col-2 p-0 pl-1 border-dark text-16 black">
						{{$crane->job_request->code}} / {{ preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $crane->code) ?: $crane->code }}@if(!empty($revision_display_no)) - REV: {{$revision_display_no}}@endif
				</div>
		</div>
		<div class="row">
				<div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Examination Date</h6></div>
				<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$crane->lcr_6}}</div>
				<div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Next Exa. Date</h6></div>
				<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$crane->lcr_7}}</div>
				<div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Color Code</h6></div>
				<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$crane->lcr_8}}</div>
		</div>
		<div class="row">
				<div class="bg-dark col-2 p-0 border-dark">
						<h6 class="white text-bold-600 pl-1 mb-0 mid">Work location</h6>
				</div>
			<div class="col-10 p-0 pl-1 border-dark text-16 black">
				{{$crane->job_request->clientDepartment?
                 $crane->job_request->clientDepartment->name . ' / '.$crane->job_request->deploc
                 : $crane->job_request->deploc
                }}
			</div>
		</div>
		<div class="row row-flex">
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Type of Crane and nature of Power</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">{{$crane->lcr_10}}</p>
				</div>
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of Manufacturer</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">{{$crane->lcr_11}}</p>
				</div>
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Identification Number/chassis number</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">{{$crane->lcr_12}}</p>
				</div>
		</div>
		<div class="row">
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Model/Type</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">{{$crane->lcr_13}}</p>
				</div>
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Date of Manufacturer (if known)</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">{{$crane->lcr_14}}</p>
				</div>
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Crane Capacity ( SWL)</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">{{$crane->lcr_15}}</p>
				</div>
		</div>
		<div class="row row-flex row-flex">
				<div class="col-6 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark" id="emad">Make and type of automatic safe load indicator(SLI)</h6>
						<p class="pl-1 mb-0 border-dark text-16 black">{{$crane->lcr_16}}</p>
				</div>
				<div class="col-3 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0  border-dark">SLI Identification number(if fitted)</h6>
						<p class="pl-1 mb-0 border-dark text-16 black">{{$crane->lcr_17}}</p>
				</div>
				<div class="col-3 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Number of falls/lines</h6>
						<p class="pl-1 mb-0 border-dark text-16 black">{{$crane->lcr_18}}</p>
				</div>
		</div>
		<div class="row">
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Date of last Through Examination</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">{{$crane->lcr_19}}</p>
				</div>
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Certificate Number</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">{{$crane->lcr_20}}</p>
				</div>
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Examined by</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">{{$crane->lcr_21}}</p>
				</div>
		</div>
		<div class="row">
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Date of last Load Test</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">{{$crane->lcr_22}}</p>
				</div>
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Certificate Number</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">{{$crane->lcr_23}}</p>
				</div>
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Tested by</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">{{$crane->lcr_24}}</p>
				</div>
		</div>
		<div class="row row-flex">
				<div class="bg-dark col-2 p-0 border-dark">
						<h6 class="white text-bold-600 pl-1 mb-0 mid">Reference Standard</h6>
				</div>
				<div class="col-10 p-0 pl-1 border-dark text-16 black">{{$crane->lcr_25}}</div>
		</div>
		<div class="row row-flex border-dark">
				<div class="col-6 pt-1">
						<div class="row ">
								<div class="col-8">
										<p class="black">- Is this the first examination after installation or assembly at a new site or location?</p>
								</div>
								<div class="col-2 mid">
										<span class="noncheckedfrom {{$crane->checkbox_yes($crane->lcr_26)}}"></span>
										<label class="black">Yes</label>
								</div>
								<div class="col-2 mid">
										<span class="noncheckedfrom {{$crane->checkbox_no($crane->lcr_26)}}"></span>
										<label class="black">No</label>
								</div>
						</div>
						<div class="row mt-1 ifyou">
								<div class="col-8">
										<p class="black">- If the answer to the above question is YES Has the equipment been installed correctly?</p>
								</div>
								<div class="col-2 mid">
										<span class="noncheckedfrom {{$crane->checkbox_yes($crane->lcr_27)}}"></span>
										<label class="black">Yes</label>
								</div>
								<div class="col-2 mid">
										<span class="noncheckedfrom {{$crane->checkbox_no($crane->lcr_27)}}"></span>
										<label class="black">No</label>
								</div>
						</div>
				</div>
				<div class="col-6">
						<p class="text-bold-600">* Was the through examination carried out</p>
						<div class="row">
								<div class="col-8">
										<p class="black">- Within an interval of 6 months?</p>
								</div>
								<div class="col-2 mid pnone">
										<span class="noncheckedfrom {{$crane->checkbox_yes($crane->lcr_28)}}"></span>
										<label class="black">Yes</label>
								</div>
								<div class="col-2 mid pnone">
										<span class="noncheckedfrom {{$crane->checkbox_no($crane->lcr_28)}}"></span>
										<label class="black">No</label>
								</div>
						</div>
						<div class="row">
								<div class="col-8">
										<p class="black">- Within an interval of 12 months?</p>
								</div>
								<div class="col-2 mid pnone">
										<span class="noncheckedfrom {{$crane->checkbox_yes($crane->lcr_29)}}"></span>
										<label class="black">Yes</label>
								</div>
								<div class="col-2 mid pnone">
										<span class="noncheckedfrom {{$crane->checkbox_no($crane->lcr_29)}}"></span>
										<label class="black">No</label>
								</div>
						</div>
						<div class="row">
								<div class="col-8">
										<p class="black">- In accordance with an examination scheme?</p>
								</div>
								<div class="col-2 mid pnone">
										<span class="noncheckedfrom {{$crane->checkbox_yes($crane->lcr_30)}}"></span>
										<label class="black">Yes</label>
								</div>
								<div class="col-2 mid pnone">
										<span class="noncheckedfrom {{$crane->checkbox_no($crane->lcr_30)}}"></span>
										<label class="black">No</label>
								</div>
						</div>
						<div class="row">
								<div class="col-8">
										<p class="black">- After exceptional Circumstances?</p>
								</div>
								<div class="col-2 mid pnone">
										<span class="noncheckedfrom {{$crane->checkbox_yes($crane->lcr_31)}}"></span>
										<label class="black">Yes</label>
								</div>
								<div class="col-2 mid pnone">
										<span class="noncheckedfrom {{$crane->checkbox_no($crane->lcr_31)}}"></span>
										<label class="black">No</label>
								</div>
						</div>
				</div>
		</div>
		<div class="row">
				<div class="col-12 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect</h6>
				</div>
				<div class="col-12 border-dark pl-1 pr-1 mb-0 text-16 black">
						{{$crane->lcr_32}}
				</div>
		</div>
		<div class="row row-flex border-dark">
				<div class="col-12">
						<div class="row row-flex">
								<div class="col-8">
										<p class="black">Is the above a defect which is of immediate danger to persons?</p>
								</div>
								<div class="col-2 mid">
										<span class="noncheckedfrom {{$crane->checkbox_yes($crane->lcr_33)}}"></span>
										<label class="black">Yes</label>
								</div>
								<div class="col-2 mid">
										<span class="noncheckedfrom {{$crane->checkbox_no($crane->lcr_33)}}"></span>
										<label class="black">No</label>
								</div>
						</div>
						<div class="row">
								<div class="col-8">
										<p class="black">Is the above a defect which is not yet but could become a danger to persons?</p>
								</div>
								<div class="col-2 mid">
										<span class="noncheckedfrom {{$crane->checkbox_yes($crane->lcr_34)}}"></span>
										<label class="black">Yes</label>
								</div>
								<div class="col-2 mid">
										<span class="noncheckedfrom {{$crane->checkbox_no($crane->lcr_34)}}"></span>
										<label class="black">No</label>
								</div>
						</div>
						<div class="row">
								<div class="col-8">
										<p class="black">If the answer of the above question is Yes state date by when</p>
								</div>
								<div class="col-4">
										<p class="border-dark pl-1 text-16 black" style="min-height: 28px;">{{$crane->lcr_35}}</p>
								</div>
						</div>
				</div>
		</div>
		<div class="row">
				<div class="col-12 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Particulars of any repair, renewal, or alteration required to remedy the defect identified above</h6>
				</div>
				<div class="col-12 border-dark pl-1 pr-1 mb-0 text-16 black">
						{{$crane->lcr_36}}
				</div>
		</div>
		<div class="row">
				<div class="col-12 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Particulars of any tests carried out as part of the examination(if none state NONE)</h6>
				</div>
				<div class="col-12 border-dark pl-1 pr-1 mb-0 text-16 black ">
						{{$crane->lcr_37}}
				</div>
		</div>
		<div class="row">
				<div class="col-12 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Load Test Details</h6>
						<div class="row row-flex row-flex m-0">
								<div class="col-6 border-dark p-0 ">
										<p class="pl-1 pr-1 mb-0 black jib" style="/*padding-top: 5px; padding-bottom: 5px;*/ font-size: 12px;">Safe working load or loads in the case of a crane with a variable operating radius (including a crane with a derricking jib or within ter-changeable jibs of different lengths) the safe working load at various radii of the jib, trolley or crab must be given. Test loads at various radii should be given in column (iii) and in the case of a safe working load, which has been calculated without the application of a test load “nil” should be entered in that column.</p>
								</div>
								<div class="col-1 border-dark p-0 text-center" style="padding-top: 0 !important;">
										<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Jib Length</h6>
										@foreach(json_decode($crane->lcr_38) as $index => $value)
												<p class="pl-1 pr-1 mb-0 text-16 black" style="padding-top: 5px; padding-bottom: 5px;">{{$value->lcr_38}}</p>
										@endforeach
								</div>
								<div class="col-1 border-dark p-0 text-center" style="padding-top: 0 !important;">
										<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Radius</h6>
										@foreach(json_decode($crane->lcr_38) as $index => $value)
												<p class="pl-1 pr-1 mb-0 text-16 black" style="padding-top: 5px; padding-bottom: 5px;">{{$value->lcr_39}}</p>
										@endforeach
								</div>
								<div class="col-2 border-dark p-0 text-center" style="padding-top: 0 !important;">
										<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Proof Load</h6>
										@foreach(json_decode($crane->lcr_38) as $index => $value)
												<p class="pl-1 pr-1 mb-0 text-16 black" style="padding-top: 5px; padding-bottom: 5px;">{{$value->lcr_40}}</p>
										@endforeach
								</div>
								<div class="col-2 border-dark p-0 text-center" style="padding-top: 0 !important;">
										<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">SWL</h6>
										@foreach(json_decode($crane->lcr_38) as $index => $value)
												<p class="pl-1 pr-1 mb-0 text-16 black" style="padding-top: 5px; padding-bottom: 5px;">{{$value->lcr_41}}</p>
										@endforeach
								</div>
						</div>
						<div class="row row-flex row-flex m-0">
								<div class="col-6 border-dark p-0">
										<p class="pl-1 pr-1 mb-0 black jib" style="label{ font-size: 0.9rem; }padding-top: 5px; padding-bottom: 5px;">In the case of a crane with a derricking Jib or jibs the maximum radius at which the jib or jibs may b</p>
								</div>
								<div class="col-1 border-dark p-0 text-16 black"><p class="pl-1 pr-1 mb-0 text-center" style="padding-top: 5px; padding-bottom: 5px;">{{$crane->lcr_42}}</p></div>
								<div class="col-1 border-dark p-0 text-16 black"><p class="pl-1 pr-1 mb-0 text-center" style="padding-top: 5px; padding-bottom: 5px;">{{$crane->lcr_43}}</p></div>
								<div class="col-2 border-dark p-0 text-16 black"><p class="pl-1 pr-1 mb-0 text-center" style="padding-top: 5px; padding-bottom: 5px;">{{$crane->lcr_44}}</p></div>
								<div class="col-2 border-dark p-0 text-16 black"><p class="pl-1 pr-1 mb-0 text-center" style="padding-top: 5px; padding-bottom: 5px;">{{$crane->lcr_45}}</p></div>
						</div>
				</div>
		</div>
		<div class="row row-flex border-dark" style="padding-top: 4px; padding-bottom: 4px;">
				<div class="col-8">
						<p class="black bnew">Is this equipment safe to operate?</p>
				</div>
				<div class="col-2 mid">
						<span class="noncheckedfrom {{$crane->checkbox_yes($crane->lcr_46)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
						<label class="black" style="font-size: 1.3rem; font-weight: 600;">Yes</label>
				</div>
				<div class="col-2 mid">
						<span class="noncheckedfrom {{$crane->checkbox_no($crane->lcr_46)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
						<label class="black" style="font-size: 1.3rem; font-weight: 600;">No</label>
				</div>
		</div>
		<div class="row row-flex border-dark">
			<p class="col-12 black" style="padding-top: 4px; padding-bottom: 4px;">Note: Due date / color code doesn't guarantee that the equipment remains serviceable, so the normal visual inspection are still required prior to use</p>
		</div>
		@include('layouts.styles.reportfooter-v2')
		@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $crane->report->id])
@endpush
