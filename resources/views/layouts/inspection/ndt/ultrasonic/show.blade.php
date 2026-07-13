@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
@php
    $ultrasonicSpecifications = is_array($ultrasonic->specifications ?? $null)
        ? $ultrasonic->specifications
        : (json_decode($ultrasonic->nur_8 ?? '[]', true) ?: []);
@endphp

<div class="row page_in">
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
        <p class="border-dark pl-1 mb-0 text-16 black">{{$ultrasonic->job_request->client->name}}</p>
    </div>
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
        <p class="border-dark pl-1 mb-0 text-16 black">{{preg_replace('~[\\\\/:*?"<>[]|]~', '',$ultrasonic->job_request->client->location)}}</p>
    </div>
</div>

<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">
        {{$ultrasonic->job_request->purchase_order ? $ultrasonic->job_request->purchase_order : $ultrasonic->nur_2}}
    </div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$ultrasonic->job_request->code}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Report No</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$ultrasonic->id}}">{{$ultrasonic->job_request->code}} / {{ preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $ultrasonic->code) ?: $ultrasonic->code }}</div>
</div>

<div class="row">
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
  </div>
  <div class="col-6 p-0 pl-1 border-dark text-16 black">
      {{$ultrasonic->job_request->clientDepartment ?
        $ultrasonic->job_request->clientDepartment->name . ' / '.$ultrasonic->job_request->deploc
        : $ultrasonic->job_request->deploc}}
  </div>
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Examination Date</h6>
  </div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$ultrasonic->nur_6}}</div>
</div>

<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Specification</h6></div>
    <div class="border-dark pl-1 mb-0 text-16 black col-6">
		<div class="row skin skin-square">
			@foreach($specificationOptions as $key => $value)
				@if($key != 'S-008')
				<div class="specinsp" style="margin-left: 8px;">
	  <span class="noncheckedfrom {{in_array(str_replace(' ', '-', strtolower($value)), $ultrasonicSpecifications, true)? 'checked' : ''}}"
			style="position: relative;top: 3px;"></span>
					<label>{{$value}}</label>
				</div>
				@endif
			@endforeach
				<div class="specinsp" style="margin-left: 8px;">
	  <span class="noncheckedfrom {{in_array('other', $ultrasonicSpecifications, true)? 'checked' : ''}}"
			style="position: relative;top: 3px;"></span>
					<label>Other</label>
				</div>
		</div>
	</div>
    <div class="col-1 p-0 pl-1 border-dark text-16 black">{{$ultrasonic->nur_9}}</div>
    <div class="col-1 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Edition</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$ultrasonic->nur_10}}</div>
</div>

<div class="row" style="margin-bottom: 3px;">
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-3 middle">Acceptance Criteria</h6>
  <p class="border-dark pl-1 mb-0 text-16 black col-9">{{$ultrasonic->acceptance}}</p>
</div>

<div class="row" style="margin-bottom: 3px;">
  <h6 class="col-12 bg-dark white text-bold-600 pl-1 mb-0 border-dark col-3 text-center">Equipment and Technique</h6>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Model</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_12}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Serial Number</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_13}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Manufacturer</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_14}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Couplant Type</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_15}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Cable Type</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_16}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Calibration Block</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_17}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Search Unit</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_18}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Manufacturer</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_19}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Technique</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_20}}</div>
</div>

<div class="row">
<div class="col-6 border-dark">

  <table style="width: 106%;
position: relative;
left: -15px;
display: table;
">
    <thead style="width: 100%;">
    <tr>

    <td  class=" bg-dark p-0 pl-1 border-dark white text-bold-600" style="width: 33%;">Probe angle</td>
      <td class=" bg-dark p-0 pl-1 border-dark white text-bold-600" style="width: 16.75%;">0 <sup>0</sup></td>
      <td class=" bg-dark p-0 pl-1 border-dark white text-bold-600" style="width: 16.75%;">45 <sup>0</sup></td>
      <td class=" bg-dark p-0 pl-1 border-dark white text-bold-600" style="width: 16.75%;">60 <sup>0</sup></td>
      <td class=" bg-dark p-0 pl-1 border-dark white text-bold-600" style="width: 16.75%;">70 <sup>0</sup></td>
    </tr>
  </thead>
  <tbody style="width: 100%;">
    <tr>
      <td class=" bg-dark p-0 pl-1 border-dark white text-bold-600">Serial No</td>
        <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_21', 'nur_21')}}</td>
        <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_22', 'nur_21')}}</td>
        <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_23', 'nur_21')}}</td>
        <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_24', 'nur_21')}}</td>
      </tr>
      <tr>
        <td class=" bg-dark p-0 pl-1 border-dark white text-bold-600">Dimension</td>
        <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_25', 'nur_21')}}</td>
        <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_26', 'nur_21')}}</td>
        <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_27', 'nur_21')}}</td>
        <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_28', 'nur_21')}}</td>
        </tr>
        <tr>
          <td class=" bg-dark p-0 pl-1 border-dark white text-bold-600">Frequency</td>
          <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_29', 'nur_21')}}</td>
          <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_30', 'nur_21')}}</td>
          <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_31', 'nur_21')}}</td>
          <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_32', 'nur_21')}}</td>
          </tr>
          <tr>
            <td class=" bg-dark p-0 pl-1 border-dark white text-bold-600">Sensitivity</td>
            <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_33', 'nur_21')}}</td>
            <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_34', 'nur_21')}}</td>
            <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_35', 'nur_21')}}</td>
            <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_36', 'nur_21')}}</td>
            </tr>
            <tr>
              <td class=" bg-dark p-0 pl-1 border-dark white text-bold-600">Reference Gain</td>
              <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_37', 'nur_21')}}</td>
              <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_38', 'nur_21')}}</td>
              <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_39', 'nur_21')}}</td>
              <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_40', 'nur_21')}}</td>
              </tr>
              <tr>
                <td class=" bg-dark p-0 pl-1 border-dark white text-bold-600">Range</td>
                <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_41', 'nur_21')}}</td>
                <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_42', 'nur_21')}}</td>
                <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_43', 'nur_21')}}</td>
                <td class="p-0 pl-1 border-dark white text-bold-600">{{$ultrasonic->getMtvalue('nur_44', 'nur_21')}}</td>
                </tr>
  </tbody>
</table>
<div class="row skin skin-square">
    <fieldset class="col-md-6 text-16 black">
      Calibration Sheet Attached:
    </fieldset>
    <fieldset class="col-md-3">
        <span class="noncheckedfrom {{$ultrasonic->checkbox_yes($ultrasonic->nur_22)}}"></span>
        <label for="hydrostatic">Yes</label>
    </fieldset>
    <fieldset class="col-md-3">
        <span class="noncheckedfrom {{$ultrasonic->checkbox_no($ultrasonic->nur_22)}}"></span>
        <label for="pneumatic">No</label>
    </fieldset>
</div>
<div class="row">
  <h6 class="col-12 bg-dark white text-bold-600 pl-1 mb-0 border-dark col-3">Comment</h6>
<div class="col-12 border-dark mid11 black" style="height: 75px;">

{{$ultrasonic->nur_23}}
</div>
</div>
</div>
<div class="col-6 p-0 pl-1 border-dark mid11" style="height: 275px;">
  <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{Storage::url('camera/inspection/ndt/ultrasonic/')}}{{$ultrasonic->nur_24}}" alt="" />

</div>
</div>

<div class="row">
    <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Description</h6></div>
    <div class="col-10 p-0 pl-1 border-dark text-16 black middle preline" style="height: 30px;">{{$ultrasonic->desc}}</div>
</div>
<div class="row">
    <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Identification No</h6></div>
    <div class="col-10 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_26}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Welding Process</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_27}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Material</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_28}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Material Thickness</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_29}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Surface Condition</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_30}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Surface Temperature</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_31}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Light Intensity</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$ultrasonic->nur_32}}</div>
</div>





<div class="row">
<div class="col-12 p-0 border-dark">

  <table style="width: 100%; text-align: center;">
    <thead style="width: 100%;">
    <tr>

    <td  class=" bg-dark p-0 border-dark white text-bold-600">Drawing Number</td>
      <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 25%;">Item</td>
      <td class=" bg-dark p-0 border-dark white text-bold-600">Joint No</td>
      <td class=" bg-dark p-0 border-dark white text-bold-600">Welder no</td>
      <td class=" bg-dark p-0 border-dark white text-bold-600">Tested Length</td>
      <td class=" bg-dark p-0 border-dark white text-bold-600">Evaluation</td>
      <td class=" bg-dark p-0 border-dark white text-bold-600">Result</td>
      <td class=" bg-dark p-0 border-dark white text-bold-600">Remarks</td>
    </tr>
  </thead>
  <tbody style="width: 100%;">
    @foreach(json_decode($ultrasonic->nur_33) as $value)
    <tr>
      <td class="p-0 border-dark white text-bold-600" style="height: 41px;">{{$value->nur_56}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->nur_57}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->nur_58}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->nur_59}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->nur_60}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->nur_61}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->nur_62}}</td>
        <td class="p-0 border-dark white text-bold-600">{{$value->nur_63}}</td>
      </tr>
      @endforeach
      @if(count(json_decode($ultrasonic->nur_33)) < 8)
          @for($i = 8-count(json_decode($ultrasonic->nur_33)); $i > 0; $i--)
          <tr>
            <td class="p-0 border-dark white text-bold-600" style="height: 41px;">-</td>
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
  <p class="border-dark pl-1 mb-0 text-16 black col-12 text-center" style="font-size: 80%;">
    Note: P: Porosity C: Crack IP: Incomplete Penetration IF: Incomplete Fusion S: Slag EP: Excess Penetration CP: Cluster Porosity CON: Concavity BSR: Before Stress Relief ASR: After Stress Relief
  </p>
</div>


<div class="row border-dark" style="margin-bottom: 3px;">
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Final Conclusion:</h6>

  <div class="col-6">
      <p class="black bnew">Final result Accept / Reject ?</p>
  </div>
  <div class="col-2 mid">
      <span class="noncheckedfrom {{$ultrasonic->checkbox_yes($ultrasonic->nur_34)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
      <label class="black" style="font-size: 1.3rem; font-weight: 600;">Accept</label>
  </div>
  <div class="col-2 mid">
      <span class="noncheckedfrom {{$ultrasonic->checkbox_no($ultrasonic->nur_34)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
      <label class="black" style="font-size: 1.3rem; font-weight: 600;">Reject</label>
  </div>
</div>

@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $ultrasonic->report->id])
@endpush

