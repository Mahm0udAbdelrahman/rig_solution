@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
<div class="row page_in">
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
        <p class="border-dark pl-1 mb-0 text-16 black">{{$highPressure->job_request->client->name}}</p>
    </div>
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
        <p class="border-dark pl-1 mb-0 text-16 black">{{preg_replace('~[\\\\/:*?"<>[]|]~', '',$highPressure->job_request->client->location)}}</p>
    </div>
</div>

<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">
        {{$highPressure->job_request->purchase_order ? $highPressure->job_request->purchase_order : $highPressure->nhpr_2}}
    </div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$highPressure->job_request->code}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Report No</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$highPressure->id}}">{{$highPressure->job_request->code}} / {{$highPressure->report->code}}</div>
</div>

<div class="row">
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
  </div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">
      {{$highPressure->job_request->clientDepartment ?
          $highPressure->job_request->clientDepartment->name . ' / '.$highPressure->job_request->deploc
          : $highPressure->job_request->deploc}}
  </div>
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Examination Date</h6>
  </div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$highPressure->nhpr_6}}</div>
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Next Exam. Date</h6>
  </div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$highPressure->nhpr_7}}</div>
</div>

<div class="row">
	<div class="col-2 bg-dark p-0 border-dark middle">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Specification</h6>
	</div>
	<div class="border-dark pl-1 mb-0 text-16 black col-6">
		<div class="row skin skin-square">
			@foreach($specificationOptions as $key => $value)
				@if($key != 'S-008')
				<div class="specinsp" style="margin-left: 8px;">
	  <span class="noncheckedfrom {{in_array($key, $highPressure->specifications)? 'checked' : ''}}"
			style="position: relative;top: 3px;"></span>
					<label>{{$value}}</label>
				</div>
				@endif
			@endforeach
				<div class="specinsp" style="margin-left: 8px;">
	  <span class="noncheckedfrom {{in_array('other', $highPressure->specifications)? 'checked' : ''}}"
			style="position: relative;top: 3px;"></span>
					<label>Other</label>
				</div>
		</div>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_12}}</div>
	<div class="col-1 bg-dark p-0 border-dark middle">
		<h6 class="white text-bold-600 pl-1 mb-0 mid">Ediation</h6>
	</div>
	<div class="col-1 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->edition}}</div>
</div>
<div class="row">
	<div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Acceptance Criteria</h6></div>
  <div class="col-10 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->acceptance}}</div>
</div>

<div class="row">
    <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Description</h6></div>
    <div class="col-10 p-0 pl-1 border-dark text-16 black middle preline">{{$highPressure->desc}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Identification No</h6></div>
  <div class="col-4 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_10}}</div>

  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Drawing Number</h6></div>
  <div class="col-4 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_14}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Material</h6></div>
  <div class="col-4 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_15}}</div>

  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Material Thickness</h6></div>
  <div class="col-4 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_16}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Surface Condition</h6></div>
  <div class="col-4 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_17}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Surface Temperature</h6></div>
  <div class="col-4 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_18}}</div>
</div>

<div class="row" style="margin-bottom: 3px;">
  <h6 class="col-12 bg-dark white text-bold-600 pl-1 mb-0 border-dark col-3 text-center">Equipment instrument</h6>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Model</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_19}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Serial Number</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_20}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Manufacturer</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_21}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Couplant Type</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_22}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Sound Velocity</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_23}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Search Unit</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_24}}</div>
</div>

<div class="row">
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Probe Frequency</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_25}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Probe Dia.</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_26}}</div>
  <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Calibration Block</h6></div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_27}}</div>
</div>

<div class="row border-dark skin skin-square">
    <fieldset class="col-md-2 text-16 black">
       Sheet Attached:
    </fieldset>
    <fieldset class="col-md-1">
        <span class="noncheckedfrom {{$highPressure->checkbox_yes($highPressure->nhpr_28)}}"></span>
        <label for="hydrostatic">Yes</label>
    </fieldset>
    <fieldset class="col-md-1">
        <span class="noncheckedfrom {{$highPressure->checkbox_no($highPressure->nhpr_28)}}"></span>
        <label for="pneumatic">No</label>
    </fieldset>
    <fieldset class="col-md-2 text-16 black">
      Sketch Attached:
    </fieldset>
    <fieldset class="col-md-1">
        <span class="noncheckedfrom {{$highPressure->checkbox_yes($highPressure->nhpr_29)}}"></span>
        <label for="hydrostatic">Yes</label>
    </fieldset>
    <fieldset class="col-md-1">
        <span class="noncheckedfrom {{$highPressure->checkbox_no($highPressure->nhpr_29)}}"></span>
        <label for="pneumatic">No</label>
    </fieldset>
    <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Calibration Due</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black middle">{{$highPressure->nhpr_30}}</div>
</div>

<div class="row" style="margin-bottom: 3px;">
  <h6 class="col-12 bg-dark white text-bold-600 pl-1 mb-0 border-dark col-3 text-center">Measurement Data sheet</h6>
</div>



<div class="row">
<div class="col-12 p-0 border-dark">

  <table style="width: 100%; text-align: center;">
    <thead style="width: 100%;">
    <tr>

    <td  class=" bg-dark p-0 border-dark white text-bold-600" style="width: 30%;">Point Description</td>
      <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 5%;">Line Size (Diameter)</td>
      <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 5%;">Minimum Wall Thickness</td>
      <td  colspan="4" class=" bg-dark p-0 border-dark white text-bold-600">
          <table style="width: 100%;">
            <tr>
              <td colspan="4">Thickness Gauged Readings (mm) in O,clock Orientation</td>
            </tr>
            <tr>

              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 25%">(0 <sup>0</sup>)</td>
              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 25%">(90 <sup>0</sup>)</td>
              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 25%">(180 <sup>0</sup>)</td>
              <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 25%">(270 <sup>0</sup>)</td>
            </tr>
          </table>

      </td>

      <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 15%;">Result</td>
      <td class=" bg-dark p-0 border-dark white text-bold-600" style="width: 20%;">Remarks</td>
    </tr>
  </thead>
  <tbody style="width: 100%;">
    @foreach(json_decode($highPressure->nhpr_31) as $value)
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
      </tr>
      @endforeach
      @if(count(json_decode($highPressure->nhpr_31)) < 14)
          @for($i = 14-count(json_decode($highPressure->nhpr_31)); $i > 0; $i--)
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
      <span class="noncheckedfrom {{$highPressure->checkbox_yes($highPressure->nhpr_32)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
      <label class="black" style="font-size: 1.3rem; font-weight: 600;">Accept</label>
  </div>
  <div class="col-2 mid">
      <span class="noncheckedfrom {{$highPressure->checkbox_no($highPressure->nhpr_32)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
      <label class="black" style="font-size: 1.3rem; font-weight: 600;">Reject</label>
  </div>
</div>
@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $highPressure->report->id])
@endpush
