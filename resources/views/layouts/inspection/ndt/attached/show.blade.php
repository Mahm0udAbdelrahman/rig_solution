@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')

<div class="row page_in">
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
        <p class="border-dark pl-1 mb-0 text-16 black">{{$attached->job_request->client->name}}</p>
    </div>
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
        <p class="border-dark pl-1 mb-0 text-16 black">{{preg_replace('~[\\\\/:*?"<>[]|]~', '',$attached->job_request->client->location)}}</p>
    </div>
</div>
<!----------------------------------->
<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">
        {{$attached->job_request->purchase_order ? $attached->job_request->purchase_order : $attached->nar_2}}
    </div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$attached->job_request->code}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Report No</h6></div>

    <div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$attached->id}}">{{$attached->job_request->code}} / {{ preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $attached->code) ?: $attached->code }}</div>

</div>
<!----------------------------------->
<div class="row">
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
  </div>
  <div class="col-6 p-0 pl-1 border-dark text-16 black">
      {{$attached->job_request->clientDepartment ?
        $attached->job_request->clientDepartment->name . ' / '.$attached->job_request->deploc
        : $attached->job_request->deploc}}
  </div>
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Examination Date</h6>
  </div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$attached->nar_4}}</div>
</div>

<div class="row" style="margin-bottom: 3px;">
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Description</h6>
  <p class="border-dark pl-1 mb-0 text-bold-600 text-16 black col-10" style="height: 60px; font-size: 18px !important;">{{$attached->desc}}</p>
</div>

<div class="row">
@foreach(json_decode($attached->nar_6) as $key => $value)
    <div class="col-6 p-0">
    <div class="col-12 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Summery {{$key+1}}</h6></div>
    <div class="col-12 p-0 border-dark mid11" style="height: 350px;">
      <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{Storage::url('camera/inspection/ndt/attached/')}}{{$value->nar_11}}" alt="" />
    </div>
    <div class="col-12 p-0 pl-1 border-dark text-16 text-center text-bold-600 black" style="font-size: 18px !important; height: 150px;">{{$value->nar_10}}</div>
  </div>
  @endforeach
  @if(count(json_decode($attached->nar_6)) < 4)
      @for($i = count(json_decode($attached->nar_6)); $i < 4; $i++)
      <div class="col-6 p-0">
      <div class="col-12 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Summery {{$i+1}}</h6></div>
      <div class="col-12 p-0 border-dark mid11" style="height: 350px;">
      </div>
      <div class="col-12 p-0 pl-1 border-dark text-16 text-center text-bold-600 black" style="font-size: 18px !important; height: 150px;"></div>
      </div>
      @endfor

      @endif
</div>

@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $attached->report->id])
@endpush
