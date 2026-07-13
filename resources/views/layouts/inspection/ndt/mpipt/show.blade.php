@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
@php
    $displayReportCode = trim((string) preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $mpipt->code));
    if ($displayReportCode === '') {
        $displayReportCode = (string) $mpipt->code;
    }
@endphp
<div class="row page_in">
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
        <p class="border-dark pl-1 mb-0 text-16 black">{{$mpipt->job_request->client->name}}</p>
    </div>
    <div class="col-6 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
        <p class="border-dark pl-1 mb-0 text-16 black">{{preg_replace('~[\\\\/:*?"<>[]|]~', '',$mpipt->job_request->client->location)}}</p>
    </div>
</div>
<!----------------------------------->
<div class="row">
    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">
      {{$mpipt->job_request->purchase_order ? $mpipt->job_request->purchase_order : $mpipt->nmpr_2}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6></div>
    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$mpipt->job_request->code}}</div>
    <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Report No</h6></div>

    <div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$mpipt->id}}">{{$mpipt->job_request->code}} / {{$displayReportCode}}</div>

</div>
<!----------------------------------->
<div class="row">
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
  </div>
  <div class="col-6 p-0 pl-1 border-dark text-16 black">
    {{$mpipt->job_request->clientDepartment ?
        $mpipt->job_request->clientDepartment->name . ' / '.$mpipt->job_request->deploc
        : $mpipt->job_request->deploc}}
  </div>
  <div class="bg-dark col-2 p-0 border-dark">
      <h6 class="white text-bold-600 pl-1 mb-0">Examination Date</h6>
  </div>
  <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$mpipt->nmpr_6}}</div>
</div>
<!----------------------------------->
<div class="row" style="margin-bottom: 3px;">
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Specification</h6>
  <div class="border-dark pl-1 mb-0 text-16 black col-6">
    <div class="row skin skin-square">
      <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom @if(in_array('api', json_decode($mpipt->nmpr_8))) checked @endif" style="position: relative;top: 3px;"></span>
        <label>API</label>
      </div>
      <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom @if(in_array('astm', json_decode($mpipt->nmpr_8))) checked @endif" style="position: relative;top: 3px;"></span>
        <label>ASTM</label>
      </div>
      <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom @if(in_array('asme', json_decode($mpipt->nmpr_8))) checked @endif" style="position: relative;top: 3px;"></span>
        <label>ASME</label>
      </div>
      <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom @if(in_array('cutomer-spec', json_decode($mpipt->nmpr_8))) checked @endif" style="position: relative;top: 3px;"></span>
        <label>Customer Spec</label>
      </div>
      <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom @if(in_array('other', json_decode($mpipt->nmpr_8))) checked @endif" style="position: relative;top: 3px;"></span>
        <label>Other</label>
      </div>
    </div>
  </div>
  <p class="border-dark mb-0 text-16 black col-1 p-0" style="font-size: 100%;">{{$mpipt->nmpr_7}}</p>
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-1 middle">Edition</h6>
  <p class="border-dark pl-1 mb-0 text-16 black col-2">{{$mpipt->nmpr_10}}</p>
</div>
<!----------------------------------->
<div class="row" style="margin-bottom: 3px;">
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-3 middle">Acceptance Criteria</h6>
  <p class="border-dark pl-1 mb-0 text-16 black col-9">{{$mpipt->acceptance}}</p>
</div>
<!----------------------------------->
<div class="row" style="margin-bottom: 3px;">
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-8 text-center">Inspection Type</h6>
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 text-center">Temperature</h6>
  @php
    $temperatureValue = $mpipt->getMtvalue('nmpr_46', 'nmpr_13');
    if (($temperatureValue === 'N/A' || $temperatureValue === '') && !empty($mpipt->nmpr_46)) {
        $temperatureValue = $mpipt->nmpr_46;
    }
  @endphp
  <p class="border-dark pl-1 mb-0 text-16 black col-2">{{ $temperatureValue ?: 'N/A' }}</p>
</div>
<div class="row" style="margin-bottom: 3px;">
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Viewing Conditions</h6>
  <div class="border-dark pl-1 mb-0 text-16 black col-6">
    <div class="row skin skin-square">
      <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom @if(is_array(json_decode($mpipt->nmpr_800)) && in_array('visible_day_light', json_decode($mpipt->nmpr_800))) checked @endif" style="position: relative;top: 3px;"></span>
        <label>Visible / Day light</label>
      </div>
      <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom @if(is_array(json_decode($mpipt->nmpr_800)) && in_array('fluorescent', json_decode($mpipt->nmpr_800))) checked @endif" style="position: relative;top: 3px;"></span>
        <label>Fluorescent</label>
      </div>
      <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom @if(is_array(json_decode($mpipt->nmpr_800)) && in_array('black_light', json_decode($mpipt->nmpr_800))) checked @endif" style="position: relative;top: 3px;"></span>
        <label>Black Light</label>
      </div>
    </div>
  </div>
  
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-1 middle">Intensity</h6>
  <div class="border-dark pl-1 mb-0 text-16 black col-3">
    <div class="row skin skin-square">
      <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom @if(is_array(json_decode($mpipt->nmpr_900)) && in_array('lux', json_decode($mpipt->nmpr_900))) checked @endif" style="position: relative;top: 3px;"></span>
        <label style="font-size: 75%; font-weight: bold;">>1076 Lux</label>
      </div>
      <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom @if(is_array(json_decode($mpipt->nmpr_900)) && in_array('215lux', json_decode($mpipt->nmpr_900))) checked @endif" style="position: relative;top: 3px;"></span>
        <label style="font-size: 75%; font-weight: bold;">>21.5 Lux</label>
      </div>
      <div class="specinsp" style="margin-left: 8px;">
        <span class="noncheckedfrom @if(is_array(json_decode($mpipt->nmpr_900)) && in_array('nm', json_decode($mpipt->nmpr_900))) checked @endif" style="position: relative;top: 3px;"></span>
        <label style="font-size: 75%; font-weight: bold;">365nm</label>
      </div>
    </div>
  </div>

</div>

<div class="row" style="margin-bottom: 3px;">
  
  <div class="col-6 p-0">
    
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-4 middle">Inspection Type</h6>
      <p class="border-dark pl-1 mb-0 text-16 black col-2 skin skin-square">
        <span class="noncheckedfrom @if(!empty(json_decode($mpipt->nmpr_12)) && !empty($mpipt->getMtvalue('nmpr_34', 'nmpr_12'))) checked @endif" style="position: relative; top: 3px;"></span>
        <label>MT</label>
      </p>
      <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-4 middle">RSE Procedure</h6>
      <p class="border-dark pl-1 mb-0 text-16 black col-2">{{$mpipt->getMtvalue('nmpr_13', 'nmpr_12')}}</p>
    </div>

    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Current Type</h6>
      <div class="border-dark p-0 mb-0 text-16 black col-9">
        <div class="row skin skin-square">
          <div class="specinsp" style="margin-left: 25px;">
            <span class="noncheckedfrom
              @if(!empty(json_decode($mpipt->nmpr_12)))
                @if($mpipt->getMtvalue('ac', 'nmpr_12') === 'on') checked @endif
              @endif
            " style="position: relative;top: 3px;"></span>
            <label>AC</label>
          </div>
          <div class="specinsp" style="margin-left: 8px;">
            <span class="noncheckedfrom
              @if(!empty(json_decode($mpipt->nmpr_12)))
                  @if($mpipt->getMtvalue('dc', 'nmpr_12') === 'on') checked @endif
              @endif
            " style="position: relative;top: 3px;"></span>
            <label>DC</label>
          </div>
          <div class="specinsp" style="margin-left: 8px;">
            <span class="noncheckedfrom
              @if(!empty(json_decode($mpipt->nmpr_12)))
                  @if($mpipt->getMtvalue('permanent-magnet', 'nmpr_12') === 'on') checked @endif
              @endif
            " style="position: relative;top: 3px;"></span>
            <label>Permanentt Magnet</label>
          </div>
        </div>
      </div>
    </div>

    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Magnetic Field</h6>
      <div class="border-dark p-0 mb-0 text-16 black col-3">
        <div class="row skin skin-square">
          <div class="specinsp" style="margin-left: 16px;">
            <span class="noncheckedfrom 
              @if(!empty(json_decode($mpipt->nmpr_12)))
                @if($mpipt->getMtvalue('active', 'nmpr_12') === 'on') checked @endif
              @endif
            " style="position: relative;top: 3px; margin-right: 0;"></span>
            <label style="font-size: 75%; font-weight: bold;">Active</label>
          </div>
          <div class="specinsp" style="margin-left: 3px;">
            <span class="noncheckedfrom
              @if(!empty(json_decode($mpipt->nmpr_12)))
                @if($mpipt->getMtvalue('residual', 'nmpr_12') === 'on') checked @endif
              @endif
            " style="position: relative;top: 3px; margin-right: 0;"></span>
            <label style="font-size: 75%; font-weight: bold;">Residual</label>
          </div>
        </div>
      </div>
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Demagnetization</h6>
      <p class="border-dark p-0 mb-0 text-16 black col-3">
        {{$mpipt->getMtvalue('nmpr_3401', 'nmpr_12')}}
      </p>
    </div>

    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Equipment Used</h6>
      <p class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Magnet</p>
      <p class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">UV Light</p>
      <p class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Coil</p>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Equipment No.</h6>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_14', 'nmpr_12')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_15', 'nmpr_12')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_16', 'nmpr_12')}}</p>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Manufacturer</h6>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_17', 'nmpr_12')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_18', 'nmpr_12')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_19', 'nmpr_12')}}</p>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Calibr. Due Date</h6>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_20', 'nmpr_12')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_21', 'nmpr_12')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_22', 'nmpr_12')}}</p>
    </div>

    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle f80">Test Criteria</h6>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_23', 'nmpr_12')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_24', 'nmpr_12')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_25', 'nmpr_12')}}</p>
    </div>

    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Solution Details</h6>
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Contrast</h6>
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Indicator</h6>
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Poweder</h6>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Manufacturer</h6>
      <p class="border-dark p-0 mb-0 text-16 black col-3" style="font-size: 100%;">{{$mpipt->getMtvalue('nmpr_35', 'nmpr_12')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3" style="font-size: 100%;">{{$mpipt->getMtvalue('nmpr_36', 'nmpr_12')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3" style="font-size: 100%;">{{$mpipt->getMtvalue('nmpr_37', 'nmpr_12')}}</p>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Expire Date</h6>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_39', 'nmpr_12')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_38', 'nmpr_12')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_40', 'nmpr_12')}}</p>
    </div>

  </div>
  <div class="col-6 p-0">

    <div class="row" style="margin: auto;">
      <p class="border-dark pl-1 mb-0 text-16 black col-4 skin skin-square">
        <span class="noncheckedfrom @if(!empty(json_decode($mpipt->nmpr_13))) checked @endif" style="position: relative; top: 3px;"></span>
        <label>PT</label>
      </p>
      <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-4 middle">RSE Procedure</h6>
      <p class="border-dark pl-1 mb-0 text-16 black col-4">{{$mpipt->getMtvalue('nmpr_27', 'nmpr_13')}}</p>
    </div>

    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-12 text-center" style="padding:1px 0px !important;">Liquid Penetration Testing Equipment & Technique</h6>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-12 text-center" style="padding:1px 0px !important;">PT Technique</h6>
    </div>

    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Spray Details</h6>
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Pentrant</h6>
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Cleaner</h6>
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Developer</h6>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Manufacturer</h6>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_28', 'nmpr_13')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_29', 'nmpr_13')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_30', 'nmpr_13')}}</p>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Expire Date</h6>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_31', 'nmpr_13')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_32', 'nmpr_13')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_33', 'nmpr_13')}}</p>
    </div>

    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Dwell Time (min)</h6>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_310', 'nmpr_13')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_320', 'nmpr_13')}}</p>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_330', 'nmpr_13')}}</p>
    </div>

    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-12 text-center" style="padding: 2px 0 1px !important;">Method Description & Pre-Cleaning Method</h6>
    </div>

    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle" style="padding: 2px 0px !important;">Penetrant Type</h6>
      <div class="border-dark p-0 mb-0 text-16 black col-9">
        <div class="row skin skin-square">
          <div class="specinsp" style="margin-left: 25px;">
            <span class="noncheckedfrom 
              @if(!empty(json_decode($mpipt->nmpr_13)))
                  @if($mpipt->getMtvalue('water-washable', 'nmpr_13') === 'on') checked @endif
              @endif
              " style="position: relative;top: 3px;"></span>
            <label style="font-size: 15px !important;">Water Washable</label>
          </div>
          <div class="specinsp" style="margin-left: 8px;">
            <span class="noncheckedfrom
              @if(!empty(json_decode($mpipt->nmpr_13)))
                  @if($mpipt->getMtvalue('solvent-removable', 'nmpr_13') === 'on') checked @endif
              @endif
            " style="position: relative;top: 3px;"></span>
            <label style="font-size: 15px !important;">Solvent Removable</label>
          </div>
          <div class="specinsp" style="margin-left: 8px;">
            <span class="noncheckedfrom
              @if(!empty(json_decode($mpipt->nmpr_13)))
                  @if($mpipt->getMtvalue('other700', 'nmpr_13') === 'on') checked @endif
              @endif
            " style="position: relative;top: 3px;"></span>
            <label style="font-size: 15px !important;">Other</label>
          </div>
        </div>
      </div>
    </div>

    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Penetrant Apply</h6>
      <div class="border-dark p-0 mb-0 text-16 black col-9">
        <div class="row skin skin-square">
          <div class="specinsp" style="margin-left: 25px;">
            <span class="noncheckedfrom
              @if(!empty(json_decode($mpipt->nmpr_13)))
                  @if($mpipt->getMtvalue('spraying', 'nmpr_13') === 'on') checked @endif
              @endif
            " style="position: relative;top: 3px;"></span>
            <label>Spraying</label>
          </div>
          <div class="specinsp" style="margin-left: 8px;">
            <span class="noncheckedfrom
              @if(!empty(json_decode($mpipt->nmpr_13)))
                  @if($mpipt->getMtvalue('brushing', 'nmpr_13') === 'on') checked @endif
              @endif
            " style="position: relative;top: 3px;"></span>
            <label>Brushing</label>
          </div>
          <div class="specinsp" style="margin-left: 8px;">
            <span class="noncheckedfrom
              @if(!empty(json_decode($mpipt->nmpr_13)))
                  @if($mpipt->getMtvalue('immersion', 'nmpr_13') === 'on') checked @endif
              @endif
            " style="position: relative;top: 3px;"></span>
            <label>Immersion</label>
          </div>
        </div>
      </div>
    </div>

    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Developer Apply</h6>
      <p class="border-dark p-0 mb-0 text-16 black col-9">{{$mpipt->getMtvalue('nmpr_43', 'nmpr_13')}}</p>
      {{--<h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Equip C<sup>0<sup></h6>
      <p class="border-dark p-0 mb-0 text-16 black col-3">{{$mpipt->getMtvalue('nmpr_46', 'nmpr_13')}}</p>--}}
    </div>

  </div>
</div>
<!----------------------------------->
<div class="row" style="margin-bottom: 3px;">
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Description</h6>
  <p class="border-dark pl-1 mb-0 text-bold-600 text-16 black col-10" style="height: 65px; font-size: 18px !important;">{{$mpipt->desc}}</p>
</div>
<!----------------------------------->
<div class="row" style="margin-bottom: 3px;">
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Identification No</h6>
  <p class="border-dark pl-1 mb-0 text-16 text-bold-600 black col-10" style="font-size: 18px !important;">{{$mpipt->nmpr_28}}</p>
</div>
<!----------------------------------->
@foreach(json_decode($mpipt->nmpr_29) as $key => $value)
  <div class="row">
    <div class="col-12 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Summery {{$key+1}}</h6></div>
    <div class="col-8 p-0 pl-1 border-dark text-16 text-center text-bold-600 black" style="font-size: 18px !important;">{{$value->nmpr_49}}</div>
    <div class="col-4 p-0 border-dark mid11" style="height: 250px;">
      @php
        $storedPhoto = trim((string) ($value->nmpr_50 ?? ''));
        $photoPath = $storedPhoto === ''
            ? null
            : (\Illuminate\Support\Str::startsWith($storedPhoto, 'camera/inspection/ndt/mpipts/')
                ? ltrim($storedPhoto, '/')
                : 'camera/inspection/ndt/mpipts/'.ltrim($storedPhoto, '/'));
        $photoExists = $photoPath && is_file(public_path('storage/'.$photoPath));
      @endphp
      @if($photoExists)
        <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{ asset('storage/'.$photoPath) }}" alt="" />
      @endif
    </div>
  </div>
@endforeach
@if(count(json_decode($mpipt->nmpr_29)) < 2)
    @for($i = 2-count(json_decode($mpipt->nmpr_29)); $i > 0; $i--)
    <div class="row">
      <div class="col-12 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Summery {{$i+1}}</h6></div>
      <div class="col-8 p-0 pl-1 border-dark text-16 text-center text-bold-600 black" style="font-size: 18px !important;"></div>
      <div class="col-4 p-0 border-dark mid11" style="height: 250px;"></div>
    </div>
    @endfor
@endif
<!----------------------------------->
<div class="row border-dark" style="margin-bottom: 3px;">
  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Final Conclusion:</h6>

  <div class="col-6">
      <p class="black bnew">Final result Accept / Reject ?</p>
  </div>
  <div class="col-2 mid">
      <span class="noncheckedfrom {{$mpipt->checkbox_yes($mpipt->nmpr_30)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
      <label class="black" style="font-size: 1.3rem; font-weight: 600;">Accept</label>
  </div>
  <div class="col-2 mid">
      <span class="noncheckedfrom {{$mpipt->checkbox_no($mpipt->nmpr_30)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
      <label class="black" style="font-size: 1.3rem; font-weight: 600;">Reject</label>
  </div>
</div>
@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $mpipt->report->id])
@endpush
