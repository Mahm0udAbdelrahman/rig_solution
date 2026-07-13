@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
<div class="row page_in">
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
        <p class="border-dark pl-1 mb-0 text-16 black">{{$high2Pressure->job_request->client->name}}</p>
    </div>
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
        <p class="border-dark pl-1 mb-0 text-16 black">{{preg_replace('~[\\\\/:*?"<>[]|]~', '',$high2Pressure->job_request->client->location)}}</p>
    </div>
</div>

<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">
        {{$high2Pressure->job_request->purchase_order ? $high2Pressure->job_request->purchase_order : $high2Pressure->nh2pr_2}}
    </div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$high2Pressure->job_request->code}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Report No</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$high2Pressure->id}}">{{$high2Pressure->job_request->code}} / {{ preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $high2Pressure->code) ?: $high2Pressure->code }}</div>
</div>

<div class="row">
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
  </div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">
      {{$high2Pressure->job_request->clientDepartment ?
        $high2Pressure->job_request->clientDepartment->name . ' / '.$high2Pressure->job_request->deploc
        : $high2Pressure->job_request->deploc}}
  </div>
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Examination Date</h6>
  </div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$high2Pressure->nh2pr_6}}</div>
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Next Exam. Date</h6>
  </div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$high2Pressure->nh2pr_7}}</div>
</div>

<div class="row">
	<div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Specification</h6></div>
	<div class="border-dark pl-1 mb-0 text-16 black col-6">
            <div class="row skin skin-square">
                @foreach($specificationOptions as $key => $value)
                    @if($key != 'S-008')
                    <div class="specinsp" style="margin-left: 8px;">
          <span class="noncheckedfrom {{in_array($key, $high2Pressure->specifications)? 'checked' : ''}}"
                style="position: relative;top: 3px;"></span>
                        <label>{{$value}}</label>
                    </div>
                    @endif
                @endforeach
                    <div class="specinsp" style="margin-left: 8px;">
          <span class="noncheckedfrom {{in_array('S-008', $high2Pressure->specifications)? 'checked' : ''}}"
                style="position: relative;top: 3px;"></span>
                        <label>Other</label>
                    </div>
            </div>
        </div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_12}}</div>

	<div class="col-1 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Ediation</h6></div>
	<div class="col-1 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->edition}}</div>
</div>
<div class="row">
	<div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Acceptance Criteria</h6></div>
	<div class="col-10 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->acceptance}}</div>
</div>


<div class="row" style="min-height: 7em;">
    <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Description</h6></div>
    <div class="col-10 p-0 pl-1 border-dark text-16 black middle preline">{{$high2Pressure->desc}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Identification No</h6></div>
  <div class="col-4 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_10}}</div>

  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Drawing Number</h6></div>
  <div class="col-4 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_14}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Material</h6></div>
  <div class="col-4 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_15}}</div>

  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Material Thickness</h6></div>
  <div class="col-4 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_16}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Surface Condition</h6></div>
  <div class="col-4 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_17}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Surface Temperature</h6></div>
  <div class="col-4 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_18}}</div>
</div>

<div class="row" style="margin-bottom: 3px;">
  <h6 class="col-12 bg-dark white text-bold-600 pl-1 mb-0 border-dark col-3 text-center">Equipment instrument</h6>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Model</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_19}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Serial Number</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_20}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Manufacturer</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_21}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Couplant Type</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_22}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Sound Velocity</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_23}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Search Unit</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_24}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Probe Frequency</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_25}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Probe Dia.</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_26}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Calibration Block</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_27}}</div>
</div>


  <div class="row border-dark skin skin-square">
      <fieldset class="col-md-2 text-16 black">
         Sheet Attached:
      </fieldset>
      <fieldset class="col-md-1">
          <span class="noncheckedfrom {{$high2Pressure->checkbox_yes($high2Pressure->nh2pr_28)}}"></span>
          <label for="hydrostatic">Yes</label>
      </fieldset>
      <fieldset class="col-md-1">
          <span class="noncheckedfrom {{$high2Pressure->checkbox_no($high2Pressure->nh2pr_28)}}"></span>
          <label for="pneumatic">No</label>
      </fieldset>
      <fieldset class="col-md-2 text-16 black">
        Sketch Attached:
      </fieldset>
      <fieldset class="col-md-1">
          <span class="noncheckedfrom {{$high2Pressure->checkbox_yes($high2Pressure->nh2pr_29)}}"></span>
          <label for="hydrostatic">Yes</label>
      </fieldset>
      <fieldset class="col-md-1">
          <span class="noncheckedfrom {{$high2Pressure->checkbox_no($high2Pressure->nh2pr_29)}}"></span>
          <label for="pneumatic">No</label>
      </fieldset>
      <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Calibration Due</h6></div>
      <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$high2Pressure->nh2pr_30}}</div>
  </div>
  <div class="row" style="margin-bottom: 3px;">
    <div class="col-12 border-dark mid11" style="height: 160px;">
      <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{Storage::url('camera/inspection/ndt/high2Pressure/')}}{{$high2Pressure->nh2pr_31}}" alt="" />

    </div>
  </div>

<div class="row" style="margin-bottom: 3px;">
  <h6 class="col-12 bg-dark white text-bold-600 pl-1 mb-0 border-dark col-3 text-center">Measurement Data sheet</h6>
</div>



<div class="row">
<div class="col-12 p-0 border-dark">

  <table style="width: 100%; text-align: center;">
    <thead style="width: 100%;">
    <tr>

    <td  class=" bg-dark p-0 border-dark white text-bold-600" style="width: 15%;">Serial Number</td>
      <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 5%;">Nominal Wall Thickness</td>
      <td  colspan="3" class=" bg-dark p-0 border-dark white text-bold-600">
          <table style="width: 100%;">
            <tr>
              <td colspan="3" style="height: 40px;">Section A</td>
            </tr>
            <tr>

              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 33.3333%">A1</td>
              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 33.3333%">A2</td>
              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 33.3333%">A3</td>

            </tr>
          </table>

      </td>
      <td  colspan="3" class=" bg-dark p-0 border-dark white text-bold-600">
          <table style="width: 100%;">
            <tr>
              <td colspan="3" style="height: 40px;">Section B</td>
            </tr>
            <tr>

              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 33.3333%">B1</td>
              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 33.3333%">B2</td>
              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 33.3333%">B3</td>

            </tr>
          </table>

      </td>
      <td  colspan="3" class=" bg-dark p-0 border-dark white text-bold-600">
          <table style="width: 100%;">
            <tr>
              <td colspan="3" style="height: 40px;">Section C</td>
            </tr>
            <tr>

              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 33.3333%">C1</td>
              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 33.3333%">C2</td>
              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 33.3333%">C3</td>

            </tr>
          </table>

      </td>

      <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 10%;">Result</td>
      <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 20%;">Remarks</td>
    </tr>
  </thead>
  <tbody style="width: 100%;">
    @foreach(json_decode($high2Pressure->nh2pr_32) as $value)
    <tr>
      <td class="p-0 border-dark white text-bold-600" style="height: 41px;">{{$value->ntir_433}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->ntir_434}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->ntir_435}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->ntir_436}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->ntir_437}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->ntir_438}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->ntir_439}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->ntir_440}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->ntir_441}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->ntir_442}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->ntir_443}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->ntir_444}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->ntir_445}}</td>
      </tr>
      @endforeach
      @if(count(json_decode($high2Pressure->nh2pr_32)) < 8)
          @for($i = 8-count(json_decode($high2Pressure->nh2pr_32)); $i > 0; $i--)
          <tr>
            <td class="p-0 border-dark white text-bold-600" style="height: 41px;">-</td>
              <td class="p-0 border-dark white text-bold-600">-</td>
              <td class="p-0 border-dark white text-bold-600">-</td>
              <td class="p-0 border-dark white text-bold-600">-</td>
              <td class="p-0 border-dark white text-bold-600">-</td>
              <td class="p-0 border-dark white text-bold-600">-</td>
              <td class="p-0 border-dark white text-bold-600">-</td>
              <td class="p-0 border-dark white text-bold-600">-</td>
              <td class="p-0 border-dark white text-bold-600">-</td>
              <td class="p-0 border-dark white text-bold-600">-</td>
              <td class="p-0 border-dark white text-bold-600">-</td>
              <td class="p-0 border-dark white text-bold-600">-</td>
              <td class="p-0 border-dark white text-bold-600">-</td>
            </tr>
          @endfor
      @endif
  </tbody>
</table>
</div>
</div>




<div class="row">
  <p class="border-dark bg-dark pl-1 mb-0 text-16 black col-6 text-center" style="font-size: 80%;">
    S: substantially corroded point
  </p>
<p class="border-dark bg-dark pl-1 mb-0 text-16 black col-6 text-center" style="font-size: 80%;">
  R: is Renewal or wasted point
    </p>
</div>

<div class="row border-dark" style="margin-bottom: 3px;">
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Final Conclusion:</h6>

  <div class="col-6">
      <p class="black bnew">NDT result Accept / Reject ?</p>
  </div>
  <div class="col-2 mid">
      <span class="noncheckedfrom {{$high2Pressure->checkbox_yes($high2Pressure->nh2pr_33)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
      <label class="black" style="font-size: 1.3rem; font-weight: 600;">Accept</label>
  </div>
  <div class="col-2 mid">
      <span class="noncheckedfrom {{$high2Pressure->checkbox_no($high2Pressure->nh2pr_33)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
      <label class="black" style="font-size: 1.3rem; font-weight: 600;">Reject</label>
  </div>
</div>
@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $high2Pressure->report->id])
@endpush
