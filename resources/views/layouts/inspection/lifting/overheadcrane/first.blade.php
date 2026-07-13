@extends('layouts.styles.paper-v2')

@push('page_content')
    <div class="row page_in">
        <div class="col-6 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
            <p class="border-dark pl-1 mb-0 text-16 black">
                @if($overheadcrane->job_request->client)
                    {{$overheadcrane->job_request->client->name}}
                @else
                    {{$overheadcrane->job_request->supplier->name}}
                @endif
            </p>
        </div>
        <div class="col-6 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">
                @if($overheadcrane->job_request->client)
                    {{preg_replace('~[\\\\/:*?"<>[]|]~', '',$overheadcrane->job_request->client->location)}}
                @else
                    {{preg_replace('~[\\\\/:*?"<>[]|]~', '',$overheadcrane->job_request->supplier->location)}}
                @endif
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6></div>
        <div class="col-2 p-0 pl-1 border-dark text-16 black">
            {{$overheadcrane->job_request->purchase_order ? $overheadcrane->job_request->purchase_order : $overheadcrane->locr_2}}
        </div>
        <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6></div>
        <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$overheadcrane->job_request->code}}</div>
        <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Report No</h6></div>
        <div class="col-2 p-0 pl-1 border-dark text-16 black">
            {{$overheadcrane->job_request->code}} / {{ preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $overheadcrane->code) ?: $overheadcrane->code }}@if(!empty($revision_display_no)) - REV: {{$revision_display_no}}@endif
        </div>
    </div>
    <div class="row">
        <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Examination Date</h6></div>
        <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$overheadcrane->locr_6}}</div>
        <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Next Exa. Date</h6></div>
        <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$overheadcrane->locr_7}}</div>
        <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Color Code</h6></div>
        <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$overheadcrane->locr_8}}</div>
    </div>
    <div class="row">
        <div class="bg-dark col-2 p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
        </div>
        <div class="col-10 p-0 pl-1 border-dark text-16 black">
            {{$overheadcrane->job_request->clientDepartment?
                 $overheadcrane->job_request->clientDepartment->name . ' / '.$overheadcrane->job_request->deploc
                 : $overheadcrane->job_request->deploc
                }}
        </div>
    </div>
    <div class="row row-flex">
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Type of Crane and nature of Power</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">{{$overheadcrane->locr_10}}</p>
        </div>
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of Manufacturer</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">{{$overheadcrane->locr_11}}</p>
        </div>
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Identification Number/Part Number</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">{{$overheadcrane->locr_12}}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Model/Type</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">{{$overheadcrane->locr_13}}</p>
        </div>
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Date of Manufacturer (if known)</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">{{$overheadcrane->locr_14}}</p>
        </div>
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Crane Capacity ( SWL)</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">{{$overheadcrane->locr_15}}</p>
        </div>
    </div>
    <div class="row row-flex row-flex">
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark" id="emad">Make and type of automatic (SLI)</h6>
            <p class="pl-1 mb-0 border-dark text-16 black">{{$overheadcrane->locr_16}}</p>
        </div>
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">SLI Identification number(if fitted)</h6>
            <p class="pl-1 mb-0 border-dark text-16 black">{{$overheadcrane->locr_17}}</p>
        </div>
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Number of falls/lines</h6>
            <p class="pl-1 mb-0 border-dark text-16 black">{{$overheadcrane->locr_18}}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Last Examination Date:</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">{{$overheadcrane->locr_19}}</p>
        </div>
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Certificate Number</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">{{$overheadcrane->locr_20}}</p>
        </div>
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Examined by</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">{{$overheadcrane->locr_21}}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Date of last Load Test</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">{{$overheadcrane->locr_22}}</p>
        </div>
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Certificate Number</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">{{$overheadcrane->locr_23}}</p>
        </div>
        <div class="col-4 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Tested by</h6>
            <p class="border-dark pl-1 mb-0 text-16 black">{{$overheadcrane->locr_24}}</p>
        </div>
    </div>
    <div class="row row-flex ">
        <div class="bg-dark col-2 p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Reference Standard</h6>
        </div>
        <div class="col-10 p-0 pl-1 border-dark text-16 black">{{$overheadcrane->locr_25}}</div>
    </div>
    <div class="row row-flex border-dark">
        <div class="col-6">
            <div class="row pt-1">
                <div class="col-8">
                    <p class="black">- Is this the first examination after installation or assembly at a new site or location?</p>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_yes($overheadcrane->locr_26)}}"></span>
                    <label class="black">Yes</label>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_no($overheadcrane->locr_26)}}"></span>
                    <label class="black">No</label>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-8">
                    <p class="black">- If the answer to the above question is YES Has the equipment been installed correctly?</p>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_yes($overheadcrane->locr_27)}}"></span>
                    <label class="black">Yes</label>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_no($overheadcrane->locr_27)}}"></span>
                    <label class="black">No</label>
                </div>
            </div>
        </div>
        <div class="col-6">
            <p class="text-bold-600" style="padding-bottom: 5px;">* Was the through examination carried out</p>
            <div class="row">
                <div class="col-8">
                    <p class="black">- Within an interval of 6 months?</p>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_yes($overheadcrane->locr_28)}}"></span>
                    <label class="black">Yes</label>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_no($overheadcrane->locr_28)}}"></span>
                    <label class="black">No</label>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <p class="black">- Within an interval of 12 months?</p>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_yes($overheadcrane->locr_29)}}"></span>
                    <label class="black">Yes</label>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_no($overheadcrane->locr_29)}}"></span>
                    <label class="black">No</label>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <p class="black">- In accordance with an examination scheme?</p>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_yes($overheadcrane->locr_30)}}"></span>
                    <label class="black">Yes</label>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_no($overheadcrane->locr_30)}}"></span>
                    <label class="black">No</label>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <p class="black">- After the occurrence of exceptional Circumstances?</p>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_yes($overheadcrane->locr_31)}}"></span>
                    <label class="black">Yes</label>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_no($overheadcrane->locr_31)}}"></span>
                    <label class="black">No</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark" style="font-size: 14px;">Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect(if none state NONE)</h6>
        </div>
        <div class="col-12 p-0 border-dark pl-1 mb-0 text-16 black" style="padding-top: 5px; padding-bottom: 5px;">
            {{$overheadcrane->locr_32}}
        </div>
    </div>
    <div class="row row-flex border-dark">
        <div class="col-12" style="padding-top: 8px; padding-bottom: 8px;">
            <div class="row row-flex">
                <div class="col-8">
                    <p class="black">Is the above a defect which is of immediate danger to persons?</p>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_yes($overheadcrane->locr_33)}}"></span>
                    <label class="black">Yes</label>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_no($overheadcrane->locr_33)}}"></span>
                    <label class="black">No</label>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <p class="black">Is the above a defect which is not yet but could become a danger to persons?</p>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_yes($overheadcrane->locr_34)}}"></span>
                    <label class="black">Yes</label>
                </div>
                <div class="col-2 mid">
                    <span class="noncheckedfrom {{$overheadcrane->checkbox_no($overheadcrane->locr_34)}}"></span>
                    <label class="black">No</label>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <p class="black">If the answer of the above question is Yes state date by when</p>
                </div>
                <div class="col-4">
                    <p class="border-dark pl-1 mb-0" style="min-height: 28px;">{{$overheadcrane->locr_35}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Particulars of any repair, renewal, or alteration required to remedy the defect identified above</h6>
        </div>
        <div class="col-12 p-0 border-dark pl-1 mb-0 text-16 black" style=" padding-top: 5px; padding-bottom: 5px;">
            {{$overheadcrane->locr_36}}
        </div>
    </div>
    <div class="row">
        <div class="col-12 p-0">
            <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Particulars of any tests carried out as part of the examination(if none state NONE)</h6>
        </div>
        <div class="col-12 p-0 border-dark pl-1 mb-0 text-16 black" style=" padding-top: 5px; padding-bottom: 5px;">
            {{$overheadcrane->locr_37}}
        </div>
    </div>
    <h6 class="row bg-dark white text-bold-600 pl-1 mb-0 border-dark">Proof Load Test Details</h6>
    <div class="row">
        <h6 class="bg-dark col-4 p-0 white text-bold-600 pl-1 mb-0 border-dark">1-Performance test:</h6>
        <p class="col-8 border-dark text-16 black">{{$overheadcrane->locr_38}}</p>
    </div>
    <div class="row">
        <h6 class="bg-dark col-4 p-0 white text-bold-600 pl-1 mb-0 border-dark">Main Girder crane Span</h6>
        <p class="col-8 border-dark text-16 black">{{$overheadcrane->locr_39}}</p>
    </div>
    <div class="row">
        <h6 class="bg-dark col-4 p-0 white text-bold-600 pl-1 mb-0 border-dark">Maximum Deflection allowable:</h6>
        <p class="col-8 border-dark text-16 black">{{$overheadcrane->locr_40}}</p>
    </div>
    <div class="row">
        <h6 class="bg-dark col-4 p-0 white text-bold-600 pl-1 mb-0 border-dark">Actual deflection:</h6>
        <p class="col-8 border-dark text-16 black">{{$overheadcrane->locr_41}}</p>
    </div>
    <div class="row">
        <h6 class="bg-dark col-4 p-0 white text-bold-600 pl-1 mb-0 border-dark">2-Over Load test:</h6>
        <p class="col-8 border-dark text-16 black">{{$overheadcrane->locr_42}}</p>
    </div>
    <div class="row">
        <h6 class="bg-dark col-4 p-0 white text-bold-600 pl-1 mb-0 border-dark">Deflection of Main Girder Crane During over load:</h6>
        <p class="col-8 border-dark text-16 black">{{$overheadcrane->locr_43}}</p>
    </div>
    <div class="row row-flex border-dark">
        <div class="col-8">
            <p class="black bnew">Is this equipment safe to operate?</p>
        </div>
        <div class="col-2">
            <span class="noncheckedfrom {{$overheadcrane->checkbox_yes($overheadcrane->locr_44)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
            <label class="black" style="font-size: 1.3rem; font-weight: 600;">Yes</label>
        </div>
        <div class="col-2">
            <span class="noncheckedfrom {{$overheadcrane->checkbox_no ($overheadcrane->locr_44)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
            <label class="black" style="font-size: 1.3rem; font-weight: 600;">No</label>
        </div>
    </div>
    <div class="row row-flex border-dark">
        <p class="col-12 black" style="padding-top: 8px; padding-bottom: 8px; line-height: 16px;">I hereby certify that the overhead crane described in this certificate was tested with accessories gears by a competent person in a manner set forth
        on the reverse side of this certificate; that a careful examination of the said machinery and gear by a competent person after the test showed it had
        withstood with the proof load without injury or permanent deformation and that the Safe Working load of the above describe machinery and gears is
        as shown in above in load test details table.</p>
    </div>
    @include('layouts.styles.reportfooter-v2')
		@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $overheadcrane->report->id])
@endpush
