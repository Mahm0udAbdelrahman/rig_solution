@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')

<div class="row page_in">
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
        <p class="border-dark pl-1 mb-0 text-16 black">
          @if($defect->job_request->client)
          {{$defect->job_request->client->name}}
          @else
          {{$defect->job_request->supplier->name}}
          @endif
        </p>
    </div>
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
        <p class="border-dark pl-1 mb-0 text-16 black">
          @if($defect->job_request->client)
          {{preg_replace('~[\\\\/:*?"<>[]|]~', '',$defect->job_request->client->location)}}
          @else
          {{preg_replace('~[\\\\/:*?"<>[]|]~', '',$defect->job_request->supplier->location)}}
          @endif
        </p>
    </div>
</div>
<!----------------------------------->
<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">
        {{$defect->job_request->purchase_order ? $defect->job_request->purchase_order : $defect->ldr_2}}
    </div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$defect->job_request->code}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Report No</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$defect->id}}">
      {{$defect->job_request->code}} / {{ preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $defect->code) ?: $defect->code }}
    </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
  </div>
    <div class="col-6 p-0 pl-1 border-dark text-16 black">
        {{$defect->job_request->clientDepartment?
                   $defect->job_request->clientDepartment->name . ' / '.$defect->job_request->deploc
                   : $defect->job_request->deploc
                  }}
    </div>
  <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Examination Date</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$defect->ldr_6}}</div>
</div>
<!----------------------------------->
<div class="row row-flex" style="margin-top: 5px;">
  <div class="bg-dark col-3 p-0 border-dark">
    <h6 class="white text-bold-600 pl-1 mb-0">ID / Description / Location</h6>
  </div>
  <div class="bg-dark col-1 p-0 border-dark">
    <h6 class="white text-bold-600 pl-1 mb-0">SWL</h6>
  </div>
  <div class="bg-dark col-2 p-0 border-dark">
    <h6 class="white text-bold-600 pl-1 mb-0">Defect</h6>
  </div>
  <div class="bg-dark col-3 p-0 border-dark">
    <h6 class="white text-bold-600 pl-1 mb-0">Recommendation/ Action</h6>
  </div>
  <div class="bg-dark col-3 p-0 border-dark">
    <h6 class="white text-bold-600 pl-1 mb-0">Photo</h6>
  </div>
</div>
@foreach(json_decode($defect->ldr_8) as $item)
  <div class="row">
    <div class="col-3 p-0 pl-1 border-dark text-16 black preline">{{$item->lcr_10}}</div>
    <div class="col-1 p-0 pl-1 border-dark text-16 black preline">{{$item->lcr_11}}</div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black preline">{{$item->lcr_12}}</div>
    <div class="col-3 p-0 pl-1 border-dark text-16 black preline">{{$item->lcr_13}}</div>
    <div class="col-3 p-0 pl-1 border-dark text-16 black text-center" style="height: 215px; padding-top: 0 !important; padding-bottom: 0 !important; ">
      <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{Storage::url('camera/inspection/lifting/defect/')}}{{$item->lcr_14}}" alt="" />
    </div>
  </div>
@endforeach
@if(count(json_decode($defect->ldr_8)) < 5)
    @for($i = 5-count(json_decode($defect->ldr_8)); $i > 0; $i--)
    <div class="row">
      <div class="col-3 p-0 pl-1 border-dark text-16 black preline"></div>
      <div class="col-1 p-0 pl-1 border-dark text-16 black preline"></div>
      <div class="col-2 p-0 pl-1 border-dark text-16 black preline"></div>
      <div class="col-3 p-0 pl-1 border-dark text-16 black preline"></div>
      <div class="col-3 p-0 pl-1 border-dark text-16 black text-center" style="height: 215px; padding-top: 0 !important; padding-bottom: 0 !important; ">

      </div>
    </div>
    @endfor
@endif

@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $defect->report->id])
@endpush
