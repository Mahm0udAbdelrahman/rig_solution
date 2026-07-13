@extends('layouts.styles.paper-v2')

@push('page_content')

<div class="row page_in">
  <div class="col-6 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
    <p class="border-dark pl-1 mb-0 text-16 black">
      @if($forklift->job_request->client)
      {{$forklift->job_request->client->name}}
      @else
      {{$forklift->job_request->supplier->name}}
      @endif
    </p>
  </div>
  <div class="col-6 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
    <p class="border-dark pl-1 mb-0 text-16 black">{{preg_replace('~[\\\\/:*?"<>[]|]~', '', $forklift->job_request->client->location)}}</p>
  </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">
    {{$forklift->job_request->purchase_order ? $forklift->job_request->purchase_order : $forklift->lfr_2}}</div>
  <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$forklift->job_request->code}}</div>
  <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Report No</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$forklift->job_request->code}} / {{ preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $forklift->code) ?: $forklift->code }}@if(!empty($revision_display_no)) - REV: {{$revision_display_no}}@endif</div>
</div>
<!----------------------------------->
<div class="row">
  <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Examination Date</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$forklift->lfr_6}}</div>
  <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Next Exa. Date</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$forklift->lfr_7}}</div>
  <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Color Code</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$forklift->lfr_8}}</div>
</div>
<!----------------------------------->
<div class="row">
  <div class="bg-dark col-2 p-0 border-dark">
    <h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
  </div>
  <div class="col-10 p-0 pl-1 border-dark text-16 black">
    {{$forklift->job_request->clientDepartment?
                 $forklift->job_request->clientDepartment->name . ' / '.$forklift->job_request->deploc
                 : $forklift->job_request->deploc
                }}
  </div>
</div>
<!----------------------------------->
<div class="row row-flex">
  <div class="col-4 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Equipment Description</h6>
    <p class="border-dark pl-1 mb-0 text-16 black" style="height: 75px;">{{$forklift->lfr_10}}</p>
  </div>
  <div class="col-4 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of Manufacturer</h6>
    <p class="border-dark pl-1 mb-0 text-16 black" style="height: 75px;">{{$forklift->lfr_11}}</p>
  </div>
  <div class="col-4 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Identification Number/chassis number</h6>
    <p class="border-dark pl-1 mb-0 text-16 black" style="height: 75px;">{{$forklift->lfr_12}}</p>
  </div>
</div>
<!----------------------------------->
<div class="row row-flex">
  <div class="col-4 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Model/Type</h6>
    <p class="border-dark pl-1 mb-0 text-16 black" style="height: 75px;">{{$forklift->lfr_13}}</p>
  </div>
  <div class="col-4 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Date of Manufacturer (if known)</h6>
    <p class="border-dark pl-1 mb-0 text-16 black" style="height: 75px;">{{$forklift->lfr_14}}</p>
  </div>
  <div class="col-4 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Safe Working Load ( SWL)</h6>
    <p class="border-dark pl-1 mb-0 text-16 black" style="height: 75px;">{{$forklift->lfr_15}}</p>
  </div>
</div>
<!----------------------------------->
<div class="row row-flex">
  <div class="col-4 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Date of last Through Examination</h6>
    <p class="border-dark pl-1 mb-0 text-16 black" style="height: 75px;">{{$forklift->lfr_16}}</p>
  </div>
  <div class="col-4 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Certificate Number</h6>
    <p class="border-dark pl-1 mb-0 text-16 black" style="height: 75px;">{{$forklift->lfr_17}}</p>
  </div>
  <div class="col-4 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Examined by</h6>
    <p class="border-dark pl-1 mb-0 text-16 black" style="height: 75px;">{{$forklift->lfr_18}}</p>
  </div>
</div>
<!----------------------------------->
<div class="row row-flex">
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Proof load test Details(if applied)</h6>
  </div>
  <div class="col-10 p-0 pl-1 border-dark text-16 black">{{$forklift->lfr_19}}</div>
</div>
<!----------------------------------->
<div class="row row-flex">
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Reference Standard</h6>
  </div>
  <div class="col-10 p-0 pl-1 border-dark text-16 black">{{$forklift->lfr_20}}</div>
</div>
<!----------------------------------->
<div class="row row-flex border-dark">
  <div class="col-6 pt-1">
    <div class="row ">
      <div class="col-8">
        <p class="black">- Is this the first examination after installation or assembly at a new site or location?</p>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_yes($forklift->lfr_21)}}"></span>
          <label class="black">Yes</label>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_no($forklift->lfr_21)}}"></span>
          <label class="black">No</label>
      </div>
    </div>
    <div class="row mt-1">
      <div class="col-8">
        <p class="black">- If the answer to the above question is YES Has the equipment been installed correctly?</p>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_yes($forklift->lfr_22)}}"></span>
          <label class="black">Yes</label>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_no($forklift->lfr_22)}}"></span>
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
          <span class="noncheckedfrom {{$forklift->checkbox_yes($forklift->lfr_23)}}"></span>
          <label class="black">Yes</label>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_no($forklift->lfr_23)}}"></span>
          <label class="black">No</label>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <p class="black">- Within an interval of 12 months?</p>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_yes($forklift->lfr_24)}}"></span>
          <label class="black">Yes</label>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_no($forklift->lfr_24)}}"></span>
          <label class="black">No</label>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <p class="black">- In accordance with an examination scheme?</p>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_yes($forklift->lfr_25)}}"></span>
          <label class="black">Yes</label>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_no($forklift->lfr_25)}}"></span>
          <label class="black">No</label>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <p class="black">- After the occurrence of exceptional Circumstances?</p>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_yes($forklift->lfr_26)}}"></span>
          <label class="black">Yes</label>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_no($forklift->lfr_26)}}"></span>
          <label class="black">No</label>
      </div>
    </div>
  </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="col-12 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect(if none state NONE)</h6>
  </div>
  <div class="col-12 border-dark pl-1 pr-1 mb-0 text-16 black" style="padding-top: 5px; padding-bottom: 5px; ">
      {{$forklift->lfr_27}}
  </div>
</div>
<!----------------------------------->
<div class="row row-flex border-dark">
  <div class="col-12">
    <div class="row row-flex">
      <div class="col-8">
        <p class="black">Is the above a defect which is of immediate danger to persons?</p>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_yes($forklift->lfr_28)}}"></span>
          <label class="black">Yes</label>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_no($forklift->lfr_28)}}"></span>
          <label class="black">No</label>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <p class="black">Is the above a defect which is not yet but could become a danger to persons?</p>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_yes($forklift->lfr_29)}}"></span>
          <label class="black">Yes</label>
      </div>
      <div class="col-2 mid">
          <span class="noncheckedfrom {{$forklift->checkbox_no($forklift->lfr_29)}}"></span>
          <label class="black">No</label>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <p class="black">If the answer of the above question is Yes state date by when</p>
      </div>
      <div class="col-4">
        <p class="border-dark pl-1" style="min-height: 28px;">{{$forklift->lfr_30}}</p>
      </div>
    </div>
  </div>
</div>
<!----------------------------------->
<div class="row border-dark" style="padding-top: 0 !important; padding-bottom: 0 !important;">
  <div class="col-8">
    <!----------------------------------->
    <div class="row">
      <div class="col-12 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Particulars of any repair, renewal, or alteration required to remedy the defect identified above</h6>
      </div>
      <div class="col-12 pl-1 pr-1 mb-0 text-16 black" style="height: 100px; padding-top: 5px; padding-bottom: 5px;">
          {{$forklift->lfr_31}}
      </div>
    </div>
    <!----------------------------------->
    <div class="row">
      <div class="col-12 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Particulars of any tests carried out as part of the examination(if none state NONE)</h6>
      </div>
      <div class="col-12 pl-1 pr-1 mb-0 text-16 black" style="height: 100px; padding-top: 5px; padding-bottom: 5px;">
          {{$forklift->lfr_32}}
      </div>
    </div>
    <!----------------------------------->
  </div>
  <div class="col-4 p-1 border-dark mid11" style="max-height: 304px;">
    <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{Storage::url('camera/inspection/lifting/forklifts/')}}{{$forklift->lfr_34}}" alt="" />
  </div>
</div>
<!----------------------------------->
<div class="row row-flex border-dark" style="padding-top: 4px; padding-bottom: 4px;">
  <div class="col-8">
    <p class="black bnew">Is this equipment safe to operate?</p>
  </div>
  <div class="col-2 mid">
      <span class="noncheckedfrom {{$forklift->checkbox_yes($forklift->lfr_33)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
      <label class="black" style="font-size: 1.3rem; font-weight: 600;">Yes</label>
  </div>
  <div class="col-2 mid">
      <span class="noncheckedfrom {{$forklift->checkbox_no($forklift->lfr_33)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
      <label class="black" style="font-size: 1.3rem; font-weight: 600;">No</label>
  </div>
</div>

@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $forklift->report->id])
@endpush
