@extends('layouts.styles.second-v2')

@push('page_content_second')

<div class="row page_in">
  <div class="col-6 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Function test components</h6>
  </div>
  <div class="col-2 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Condition</h6>
  </div>
  <div class="col-4 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Justify if not Satisfactory</h6>
  </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="bg-dark border-dark middle col-3 p-0">
    <h6 class="white text-bold-600 pl-1 mb-0">Service Brake</h6>
  </div>
  <div class="col-9 p-0">
    <div class="row" style="margin-left: 0; margin-right: 0;">
      <div class="bg-dark border-dark col-4">
        <h6 class="white text-bold-600 mb-0">Forward</h6>
      </div>
      <div class="col-3 border-dark text-16 black">{{$forklift2->satisfactory($forklift2->lfr2_1)}}</div>
      <div class="col-5 border-dark text-16 black">{{$forklift2->lfr2_2}}</div>
    </div>
    <div class="row" style="margin-left: 0; margin-right: 0;">
      <div class="bg-dark border-dark col-4">
        <h6 class="white text-bold-600 mb-0">Reverse</h6>
      </div>
      <div class="col-3 border-dark text-16 black">{{$forklift2->satisfactory($forklift2->lfr2_3)}}</div>
      <div class="col-5 border-dark text-16 black">{{$forklift2->lfr2_4}}</div>
    </div>
  </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="bg-dark border-dark middle col-3 p-0">
    <h6 class="white text-bold-600 pl-1 mb-0">Parking Brake</h6>
  </div>
  <div class="col-9 p-0">
    <div class="row" style="margin-left: 0; margin-right: 0;">
      <div class="bg-dark border-dark col-4">
        <h6 class="white text-bold-600 mb-0">Forward</h6>
      </div>
      <div class="col-3 border-dark text-16 black">{{$forklift2->satisfactory($forklift2->lfr2_5)}}</div>
      <div class="col-5 border-dark text-16 black">{{$forklift2->lfr2_6}}</div>
    </div>
    <div class="row" style="margin-left: 0; margin-right: 0;">
      <div class="bg-dark border-dark col-4">
        <h6 class="white text-bold-600 mb-0">Reverse</h6>
      </div>
      <div class="col-3 border-dark text-16 black">{{$forklift2->satisfactory($forklift2->lfr2_7)}}</div>
      <div class="col-5 border-dark text-16 black">{{$forklift2->lfr2_8}}</div>
    </div>
  </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="bg-dark border-dark middle col-3 p-0">
    <h6 class="white text-bold-600 pl-1 mb-0">Steering Operation</h6>
  </div>
  <div class="col-9 p-0">
    <div class="row" style="margin-left: 0; margin-right: 0;">
      <div class="bg-dark border-dark col-4">
        <h6 class="white text-bold-600 mb-0">Not Excessive Free Play</h6>
      </div>
      <div class="col-3 border-dark text-16 black">{{$forklift2->satisfactory($forklift2->lfr2_9)}}</div>
      <div class="col-5 border-dark text-16 black">{{$forklift2->lfr2_10}}</div>
    </div>
  </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="bg-dark border-dark middle col-3 p-0">
    <h6 class="white text-bold-600 pl-1 mb-0">Drive Control</h6>
  </div>
  <div class="col-9 p-0">
    <div class="row" style="margin-left: 0; margin-right: 0;">
      <div class="bg-dark border-dark col-4">
        <h6 class="white text-bold-600 mb-0">Forward</h6>
      </div>
      <div class="col-3 border-dark text-16 black">{{$forklift2->satisfactory($forklift2->lfr2_11)}}</div>
      <div class="col-5 border-dark text-16 black">{{$forklift2->lfr2_12}}</div>
    </div>
    <div class="row" style="margin-left: 0; margin-right: 0;">
      <div class="bg-dark border-dark col-4">
        <h6 class="white text-bold-600 mb-0">Reverse</h6>
      </div>
      <div class="col-3 border-dark text-16 black">{{$forklift2->satisfactory($forklift2->lfr2_13)}}</div>
      <div class="col-5 border-dark text-16 black">{{$forklift2->lfr2_14}}</div>
    </div>
  </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="bg-dark border-dark middle col-3 p-0">
    <h6 class="white text-bold-600 pl-1 mb-0">Tilt Control</h6>
  </div>
  <div class="col-9 p-0">
    <div class="row" style="margin-left: 0; margin-right: 0;">
      <div class="bg-dark border-dark col-4">
        <h6 class="white text-bold-600 mb-0">Forward</h6>
      </div>
      <div class="col-3 border-dark text-16 black">{{$forklift2->satisfactory($forklift2->lfr2_15)}}</div>
      <div class="col-5 border-dark text-16 black">{{$forklift2->lfr2_16}}</div>
    </div>
    <div class="row" style="margin-left: 0; margin-right: 0;">
      <div class="bg-dark border-dark col-4">
        <h6 class="white text-bold-600 mb-0">Back</h6>
      </div>
      <div class="col-3 border-dark text-16 black">{{$forklift2->satisfactory($forklift2->lfr2_17)}}</div>
      <div class="col-5 border-dark text-16 black">{{$forklift2->lfr2_18}}</div>
    </div>
  </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="bg-dark border-dark middle col-3 p-0">
    <h6 class="white text-bold-600 pl-1 mb-0">Hoist & Lower Control</h6>
  </div>
  <div class="col-9 p-0">
    <div class="row" style="margin-left: 0; margin-right: 0;">
      <div class="bg-dark border-dark col-4">
        <h6 class="white text-bold-600 mb-0">Hoist</h6>
      </div>
      <div class="col-3 border-dark text-16 black">{{$forklift2->satisfactory($forklift2->lfr2_19)}}</div>
      <div class="col-5 border-dark text-16 black">{{$forklift2->lfr2_20}}</div>
    </div>
    <div class="row" style="margin-left: 0; margin-right: 0;">
      <div class="bg-dark border-dark col-4">
        <h6 class="white text-bold-600 mb-0">Lower</h6>
      </div>
      <div class="col-3 border-dark text-16 black">{{$forklift2->satisfactory($forklift2->lfr2_21)}}</div>
      <div class="col-5 border-dark text-16 black">{{$forklift2->lfr2_22}}</div>
    </div>
  </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="col-12 p-0">
    <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Forks</h6>
  </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="border-dark col-6 p-0" style="padding-top: 0 !important; padding-bottom: 0 !important;">
    <div class="border-dark row" style="margin-left: 0; margin-right: 0;">
      <h6 class="col-5 white text-bold-600 p-0 pl-1 mb-0 middle" style="padding-top: 8px !important; padding-bottom: 8px !important;">(1) ID/Information</h6>
      <div class="col-7 text-16 black middle" style="height: 35px;">{{$forklift2->lfr2_23}}</div>
    </div>
    <div class="border-dark row" style="margin-left: 0; margin-right: 0;">
      <h6 class="col-5 white text-bold-600 p-0 pl-1 mb-0 middle" style="padding-top: 7px !important; padding-bottom: 7px !important;">(2) Thickness</h6>
      <div class="col-7 text-16 black middle" style="height: 35px;">{{$forklift2->lfr2_24}}</div>
    </div>
    <div class="border-dark row" style="margin-left: 0; margin-right: 0;">
      <h6 class="col-5 white text-bold-600 p-0 pl-1 mb-0 middle" style="padding-top: 7px !important; padding-bottom: 7px !important;">(3) Blade Width</h6>
      <div class="col-7 text-16 black middle" style="height: 35px;">{{$forklift2->lfr2_25}}</div>
    </div>
    <div class="border-dark row" style="margin-left: 0; margin-right: 0;">
      <h6 class="col-5 white text-bold-600 p-0 pl-1 mb-0 middle" style="padding-top: 7px !important; padding-bottom: 7px !important;">(4) Blade Length</h6>
      <div class="col-7 text-16 black middle" style="height: 35px;">{{$forklift2->lfr2_26}}</div>
    </div>
    <div class="border-dark row" style="margin-left: 0; margin-right: 0;">
      <h6 class="col-5 white text-bold-600 p-0 pl-1 mb-0 middle" style="padding-top: 7px !important; padding-bottom: 7px !important;">(5) Distance between hooks</h6>
      <div class="col-7 text-16 black middle" style="height: 35px;">{{$forklift2->lfr2_27}}</div>
    </div>
    <div class="border-dark row" style="margin-left: 0; margin-right: 0;">
      <h6 class="col-5 white text-bold-600 p-0 pl-1 mb-0 middle" style="padding-top: 7px !important; padding-bottom: 7px !important;">(6) Shank Height(Back height)</h6>
      <div class="col-7 text-16 black middle" style="height: 35px;">{{$forklift2->lfr2_28}}</div>
    </div>
    <div class="border-dark row" style="margin-left: 0; margin-right: 0;">
      <h6 class="col-5 white text-bold-600 p-0 pl-1 mb-0 middle" style="padding-top: 8px !important; padding-bottom: 8px !important;">(7) Hanger type: Hooks/Tube</h6>
      <div class="col-7 text-16 black middle" style="height: 35px;">{{$forklift2->lfr2_29}}</div>
    </div>
    <div class="border-dark row" style="margin-left: 0; margin-right: 0;">
      <h6 class="col-5 white text-bold-600 p-0 pl-1 mb-0 middle" style="padding-top: 8px !important; padding-bottom: 8px !important;">(8) Shaft diameter</h6>
      <div class="col-7 text-16 black middle" style="height: 35px;">{{$forklift2->lfr2_30}}</div>
    </div>
  </div>
  <div class="col-6 p-0">
    <div class="border-dark text-center">
      <img src="{{asset('app-assets/images/forklift.jpg')}}" style="max-width: 100%;" />
    </div>
  </div>
</div>
<!----------------------------------->
<div class="row row-flex border-dark" style="padding-top: 4px; padding-bottom: 4px;">
  <div class="col-8 middle">
    <p class="black">Forks General Condition</p>
  </div>
  <div class="col-2 mid">
      <span class="noncheckedfrom {{$forklift2->checkbox_yes($forklift2->lfr2_31)}}"></span>
      <label class="black">Accept</label>
  </div>
  <div class="col-2 mid">
      <span class="noncheckedfrom {{$forklift2->checkbox_no($forklift2->lfr2_31)}}"></span>
      <label class="black">Reject</label>
  </div>
</div>
<!----------------------------------->
<h6 class="bg-dark white text-bold-600 p-0 pl-1 mb-0 row border-dark">MPI Details ( Inspection Method and equipment used )</h6>
<div class="row" style="margin-bottom: 3px;">
  <div class="col-7 p-0">
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Standard:</h6>
      <p class="col-3 p-0 pl-1 border-dark text-16 black">{{$forklift2->lfr2_32}}</p>
      <h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Equipment type</h6>
      <p class="col-3 p-0 pl-1 border-dark text-16 black">{{$forklift2->lfr2_33}}</p>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Equipment No</h6>
      <p class="col-3 p-0 pl-1 border-dark text-16 black">{{$forklift2->lfr2_34}}</p>


      <h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Due Date</h6>
      <p class="col-3 p-0 pl-1 border-dark text-16 black">{{$forklift2->lfr2_36}}</p>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Pole spacing</h6>
      <p class="col-9 p-0 pl-1 border-dark text-16 black">{{$forklift2->lfr2_35}}</p>


    </div>
  </div>
  <div class=" col-5 p-0">
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark text-16">Solution details</h6>
      <h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark text-16">Contrast</h6>
      <h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark text-16">Indicator</h6>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark text-16">Manufacturer</h6>
      <p class="col-4 p-0 pl-1 border-dark text-14 black">{{$forklift2->lfr2_37}}</p>
      <p class="col-4 p-0 pl-1 border-dark text-14 black">{{$forklift2->lfr2_38}}</p>
    </div>
    <div class="row" style="margin: auto;">
      <h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark text-16">Expire Date</h6>
      <p class="col-4 p-0 pl-1 border-dark text-14 black">{{$forklift2->lfr2_39}}</p>
      <p class="col-4 p-0 pl-1 border-dark text-14 black">{{$forklift2->lfr2_40}}</p>
    </div>
  </div>
</div>
<!----------------------------------->
<div class="row border-dark" style="padding-top: 0 !important; padding-bottom: 0 !important;">
  <div class="col-8">
    <div class="row">
      <div class="col-12 p-0">
        <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Final Conclusion</h6>
      </div>
      <div class="col-12 pl-1 pr-1 mb-0 text-16 black" style="height: 130px; padding-top: 5px; padding-bottom: 5px;">
          {!!nl2br(html_entity_decode($forklift2->lfr2_41))!!}
      </div>
    </div>
  </div>
  <div class="col-4 p-1 border-dark mid11" style="max-height: 150px;">
    <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{Storage::url('camera/inspection/lifting/forklifts/second/')}}{{$forklift2->lfr2_42}}" alt="" />
  </div>
</div>
<!----------------------------------->
<div class="row">
  <div class="border-dark col-12 pl-1 mb-0 black">
    I hereby certify that the Equipment described in this certificate was tested and or examined with accessories gears by a competent person in
    a manner set forth on the 1st page of this certificate; that a careful examination of the said machinery and gear by a competent person after
    the test and or examination showed it had withstood with the proof load without injury or permanent deformation and that the Safe Working
    load of the above describe machinery and gears as shown in page 1
  </div>
</div>

@include('layouts.styles.reportfooter-v2')
@endpush
