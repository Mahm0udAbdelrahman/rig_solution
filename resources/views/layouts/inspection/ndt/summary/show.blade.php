@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
<div class="row">
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
        <p class="border-dark pl-1 mb-0 text-16 black">{{$summary->job_request->client->name}}</p>
    </div>
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
        <p class="border-dark pl-1 mb-0 text-16 black">{{preg_replace('~[\\\\/:*?"<>[]|]~', '',$summary->job_request->client->location)}}</p>
    </div>
</div>
<!----------------------------------->
<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">
        {{$summary->job_request->purchase_order ? $summary->job_request->purchase_order :$summary->nsr_2}}
    </div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$summary->job_request->code}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Report No</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$summary->id}}">{{$summary->job_request->code}}/{{$summary->code}}</div>
</div>
<!----------------------------------->
<div class="row">
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
  </div>
  <div class="col-6 p-0 pl-1 border-dark text-16 black">
      {{$summary->job_request->clientDepartment ?
        $summary->job_request->clientDepartment->name . ' / '.$summary->job_request->deploc
        : $summary->job_request->deploc}}
  </div>
  <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Examination Date</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$summary->nsr_4}}</div>
</div>
<div class="row">
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Description</h6>
  </div>
  <div class="col-10 p-0 pl-1 border-dark text-16 black">{{$summary->desc}}</div>
</div>
<div class="row">
  <div class="col-12 p-0">
      <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Summary</h6>

      <div class="p-0 pl-1 border-dark text-16 black h-175 preline" style="height: 950px;">
        {{$summary->nsr_7}}
      </div>
  </div>
</div>

<div class="row border-dark" style="margin-bottom: 3px;">
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Final Conclusion:</h6>

  <div class="col-10 border-dark p-0 pl-1 text-16 black preline">
    @if($summary->checkbox_yes($summary->nsr_8) == 'checked')
        Accept
    @elseif($summary->checkbox_no($summary->nsr_8) == 'checked')
        Reject
    @else
        {{$summary->nsr_8}}
    @endif
  </div>
</div>

@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $summary->report->id])
@endpush
