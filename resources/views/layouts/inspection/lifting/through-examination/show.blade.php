@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')

<div class="row page_in">
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
        <p class="border-dark pl-1 mb-0 text-16 black">
          @if($throughExamination->job_request->client)
          {{$throughExamination->job_request->client->name}}
          @else
          {{$throughExamination->job_request->supplier->name}}
          @endif
        </p>
    </div>
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
        <p class="border-dark pl-1 mb-0 text-16 black">
          @if($throughExamination->job_request->client)
          {{preg_replace('~[\\\\/:*?"<>[]|]~', '',$throughExamination->job_request->client->location)}}
          @else
          {{preg_replace('~[\\\\/:*?"<>[]|]~', '',$throughExamination->job_request->supplier->location)}}
          @endif
        </p>
    </div>
</div>
<!----------------------------------->
<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">
      {{$throughExamination->job_request->purchase_order ? $throughExamination->job_request->purchase_order : $throughExamination->lter_2}}
    </div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$throughExamination->job_request->code}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Report No</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$throughExamination->id}}">{{$throughExamination->job_request->code}} / {{ preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $throughExamination->code) ?: $throughExamination->code }}</div>
</div>
<!----------------------------------->
<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Examination Date</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_6}}</div>
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Next Exam. Date</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_7}}</div>
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Color Code</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_8}}</div>
</div>
<!----------------------------------->
<div class="row">
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
  </div>
  <div class="col-10 p-0 pl-1 border-dark text-16 black">
    {{$throughExamination->job_request->clientDepartment?
      $throughExamination->job_request->clientDepartment->name . ' / '.$throughExamination->job_request->deploc
      : $throughExamination->job_request->deploc
    }}
  </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="col-2 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark text-center">Identification No.</h6>
  </div>
  <div class="col-1 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark text-center">QTY</h6>
  </div>
  <div class="col-6 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Description</h6>
  </div>
  <div class="col-1 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark text-center">SWL</h6>
  </div>
  <div class="col-2 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark text-center">Proof Load</h6>
  </div>
</div>
<div class="row">
  <div class="col-2 p-0">
    <div class="p-0 pl-1 border-dark text-16 black text-center hp-250 preline">{{$throughExamination->lter_10['pop1']}}</div>
  </div>
  <div class="col-1 p-0">
    <div class="p-0 pl-1 border-dark text-16 black text-center hp-250 preline">{{$throughExamination->lter_10['pop2']}}</div>
  </div>
  <div class="col-6 p-0">
    <div class="p-0 pl-1 pr-1 border-dark text-16 black hp-250"><b class="preline">{{$throughExamination->lter_10['pop20']}}</b><p class=" preline">{{$throughExamination->lter_10['pop3']}}</p></div>
  </div>
  <div class="col-1 p-0">
    <div class="p-0 pl-1 border-dark text-16 black text-center hp-250 preline">{{$throughExamination->lter_10['pop4']}}</div>
  </div>
  <div class="col-2 p-0">
    <div class="p-0 pl-1 border-dark text-16 black text-center hp-250 preline">{{$throughExamination->lter_10['pop5']}}</div>
  </div>
</div>
<!----------------------------------->
<div class="row">
    <div class="col-3 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Attached to/Location</h6></div>
    <div class="col-5 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_15}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Tare Weight</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_16}}</div>
</div>
<!----------------------------------->
<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Last examination</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_17}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Date of Manufacture</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_18}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Gross Mass</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_19}}</div>
</div>
<!----------------------------------->
<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Load Tested By</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_20}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Test Certificate #</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_21}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Date Of Test</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_22}}</div>
</div>
<!----------------------------------->
<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Reference Standard</h6></div>
    <div class="col-10 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_23}}</div>
</div>
<!----------------------------------->
<div class="row row-flex border-dark">
  <div class="col-6">
    <div class="row pt-1">
      <div class="col-8">
        <p class="black">- Is this the first examination after installation or assembly at a new site or location?</p>
      </div>
      <div class="col-2 mid">
        <span class="noncheckedfrom {{$throughExamination->checkbox_yes($throughExamination->lter_24)}}"></span>
        <label class="black">Yes</label>
      </div>
      <div class="col-2 mid">
        <span class="noncheckedfrom {{$throughExamination->checkbox_no($throughExamination->lter_24)}}"></span>
        <label class="black">No</label>
      </div>
    </div>
    <div class="row mt-1">
      <div class="col-8">
        <p class="black">- If the answer to the above question is YES Has the equipment been installed correctly?</p>
      </div>
      <div class="col-2 mid">
        <span class="noncheckedfrom {{$throughExamination->checkbox_yes($throughExamination->lter_25)}}"></span>
        <label class="black">Yes</label>
      </div>
      <div class="col-2 mid">
        <span class="noncheckedfrom {{$throughExamination->checkbox_no($throughExamination->lter_25)}}"></span>
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
      <div class="col-2 mid pb-0">
        <span class="noncheckedfrom {{$throughExamination->checkbox_yes($throughExamination->lter_26)}}"></span>
        <label class="black">Yes</label>
      </div>
      <div class="col-2 mid pb-0">
        <span class="noncheckedfrom {{$throughExamination->checkbox_no($throughExamination->lter_26)}}"></span>
        <label class="black">No</label>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <p class="black">- Within an interval of 12 months?</p>
      </div>
      <div class="col-2 mid pb-0">
        <span class="noncheckedfrom {{$throughExamination->checkbox_yes($throughExamination->lter_27)}}"></span>
        <label class="black">Yes</label>
      </div>
      <div class="col-2 mid pb-0">
        <span class="noncheckedfrom {{$throughExamination->checkbox_no($throughExamination->lter_27)}}"></span>
        <label class="black">No</label>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <p class="black">- In accordance with an examination scheme?</p>
      </div>
      <div class="col-2 mid pb-0">
        <span class="noncheckedfrom {{$throughExamination->checkbox_yes($throughExamination->lter_28)}}"></span>
        <label class="black">Yes</label>
      </div>
      <div class="col-2 mid pb-0">
        <span class="noncheckedfrom {{$throughExamination->checkbox_no($throughExamination->lter_28)}}"></span>
        <label class="black">No</label>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <p class="black" style="font-size: 97%;">- After the occurrence of exceptional Circumstances?</p>
      </div>
      <div class="col-2 mid pb-0">
        <span class="noncheckedfrom {{$throughExamination->checkbox_yes($throughExamination->lter_29)}}"></span>
        <label class="black">Yes</label>
      </div>
      <div class="col-2 mid pb-0">
        <span class="noncheckedfrom {{$throughExamination->checkbox_no($throughExamination->lter_29)}}"></span>
        <label class="black">No</label>
      </div>
    </div>
  </div>
</div>
<!----------------------------------->
<h6 class="bg-dark white text-bold-600 p-0 pl-1 mb-0 row border-dark">Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect</h6>
<div class="row">
  <div class="col-8">
    <div class="row">
      <div class="col-12 pl-1 pr-1 mb-0 text-16 black border-dark" style="height: 35px; padding-top: 5px; padding-bottom: 5px;">
          {{$throughExamination->lter_30}}
      </div>
    </div>
    <div class="row row-flex border-dark">
      <div class="col-12" style="padding-top: 8px; padding-bottom: 8px;">
        <div class="row row-flex">
          <div class="col-10">
            <p class="black">Is the above a defect which is of immediate danger to persons?</p>
          </div>
          <div class="col-2 mid pl-0">
            <span class="noncheckedfrom {{$throughExamination->checkbox_yes($throughExamination->lter_31)}}"></span>
            <label class="black" style="margin-right: 5px;">Yes</label>

            <span class="noncheckedfrom {{$throughExamination->checkbox_no($throughExamination->lter_31)}}"></span>
            <label class="black">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-10">
            <p class="black smallper">Is the above a defect which is not yet but could become a danger to persons?</p>
          </div>
          <div class="col-2 mid pl-0">
            <span class="noncheckedfrom {{$throughExamination->checkbox_yes($throughExamination->lter_32)}}"></span>
            <label class="black" style="margin-right: 5px;">Yes</label>

            <span class="noncheckedfrom {{$throughExamination->checkbox_no($throughExamination->lter_32)}}"></span>
            <label class="black">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-9">
            <p class="black">If the answer of the above question is Yes state date by when</p>
          </div>
          <div class="col-3">
            <p class="border-dark pl-1 mb-0" style="min-height: 20px;">{{$throughExamination->lter_33}}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark smallper">Particulars of any repair, renewal, or alteration required to remedy the defect identified above</h6>
      </div>
      <div class="col-12 pl-1 pr-1 mb-0 text-16 black border-dark" style=" padding-top: 5px; padding-bottom: 5px;">
          {{$throughExamination->lter_34}}
      </div>
    </div>
    <div class="row">
      <div class="col-12 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Particulars of any tests carried out as part of the examination(if none state NONE)</h6>
      </div>
      <div class="col-12 pl-1 pr-1 mb-0 text-16 black border-dark" style=" padding-top: 5px; padding-bottom: 5px;">
          {{$throughExamination->lter_35}}
      </div>
    </div>
  </div>
  <div class="col-4 p-1 border-dark mid11" style="height: 220px;">
    @php
      $throughImagePath = !empty($throughExamination->lter_36)
        ? 'camera/inspection/lifting/throughexaminations/'.$throughExamination->lter_36
        : null;
    @endphp
    @if($throughImagePath && Storage::disk('public')->exists($throughImagePath))
      <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%; margin: auto;" src="{{ Storage::url($throughImagePath) }}" alt="Through Examination Image" />
    @else
      <div class="text-muted text-center">No image uploaded</div>
    @endif
  </div>
</div>
<!----------------------------------->
<h6 class="bg-dark white text-bold-600 p-0 pl-1 mb-0 row border-dark">Inspection Method and equipment used</h6>
<div class="row">
  <div class="col-8 p-0">
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Standard:</h6>
      <p class="col-3 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_37}}</p>
      <h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Equipment type</h6>
      <p class="col-3 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_38}}</p>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Equipment No</h6>
      <p class="col-3 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_39}}</p>


      <h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Due Date</h6>
      <p class="col-3 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_41}}</p>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Pole spacing</h6>
      <p class="col-9 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_40}}</p>
    </div>
  </div>
  <div class=" col-4 p-0">
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark smallper" style="font-size: 12px">Solution details</h6>
      <h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark text-16">Contrast</h6>
      <h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark text-16">Indicator</h6>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark">Manufacturer</h6>
      <p class="col-4 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_42}}</p>
      <p class="col-4 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_43}}</p>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark">Expire Date</h6>
      <p class="col-4 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_44}}</p>
      <p class="col-4 p-0 pl-1 border-dark text-16 black">{{$throughExamination->lter_45}}</p>
    </div>
  </div>
</div>
<!----------------------------------->
<div class="row">
    <h6 class="bg-dark white col-2 p-0 pl-1 mb-0 border-dark middle">NDT Conclusion</h6>
    <p class="col-10 p-0 pl-1 border-dark text-16 black" style="">{{$throughExamination->lter_46}}</p>
</div>
<div class="row">
  <h6 class="bg-dark white col-2 p-0 pl-1 mb-0 border-dark middle">Load Cell Details</h6>
  <div class="col-10">
    <div class="row">
      <h6 class="bg-dark white col-2 p-0 pl-1 mb-0 border-dark">Range</h6>
      <div class="col-4 border-dark text-16 black">{{$throughExamination->lter_47}}</div>
      <h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Manufacturer</h6>
      <div class="col-3 border-dark text-16 black">{{$throughExamination->lter_48}}</div>
    </div>
    <div class="row">
      <h6 class="bg-dark white col-2 p-0 pl-1 mb-0 border-dark">Serial No.</h6>
      <div class="col-4 border-dark text-16 black">{{$throughExamination->lter_49}}</div>
      <h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Due Date</h6>
      <div class="col-3 border-dark text-16 black">{{$throughExamination->lter_50}}</div>
    </div>
  </div>
</div>
<!----------------------------------->
<div class="row row-flex border-dark">
  <div class="col-8">
    <p class="black bnew">Is this equipment safe to operate?</p>
  </div>
  <div class="col-2">
    <span class="noncheckedfrom {{$throughExamination->checkbox_yes($throughExamination->lter_51)}}" style="    width: 20px;
height: 20px;
top: 5px;
position: relative;"></span>
    <label class="black" style="font-size: 1.3rem; font-weight: 600;">Yes</label>
  </div>
  <div class="col-2">
    <span class="noncheckedfrom {{$throughExamination->checkbox_no ($throughExamination->lter_51)}}" style="    width: 20px;
height: 20px;
top: 5px;
position: relative;"></span>
    <label class="black" style="font-size: 1.3rem; font-weight: 600;">No</label>
  </div>
</div>
<div class="row row-flex border-dark">
  <p class="col-12 black" style="padding-top: 2px; padding-bottom: 2px;">Note: Due date / color code doesn't guarantee that the equipment remains serviceable, so the normal visual inspection are still required prior to use</p>
</div>
@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $throughExamination->report->id])
@endpush
