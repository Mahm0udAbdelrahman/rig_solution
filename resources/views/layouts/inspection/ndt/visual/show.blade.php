@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')

        <div class="row page_in">
            <div class="col-6 p-0">
                <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
                <p class="border-dark pl-1 mb-0 text-16 black">{{$visual->job_request->client->name}}</p>
            </div>
            <div class="col-6 p-0">
                <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
                <p class="border-dark pl-1 mb-0 text-16 black">{{preg_replace('~[\\\\/:*?"<>[]|]~', '',$visual->job_request->deploc)}}</p>
            </div>
        </div>
        <!----------------------------------->
        <div class="row">
            <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Purchase Order</h6></div>
            <div class="col-2 p-0 pl-1 border-dark text-16 black">
                {{$visual->job_request->purchase_order ? $visual->job_request->purchase_order : $visual->nvr_2}}
            </div>
            <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">JCF Number</h6></div>
            <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$visual->job_request->code}}</div>
            <div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0">Report No</h6></div>
            <div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$visual->id}}">{{$visual->job_request->code}} / {{ preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $visual->code) ?: $visual->code }}</div>
        </div>
        <!----------------------------------->
        <div class="row">
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
          </div>
            <div class="col-6 p-0 pl-1 border-dark text-16 black">
                {{$visual->job_request->clientDepartment ?
                $visual->job_request->clientDepartment->name . ' / '.$visual->job_request->deploc
                : $visual->job_request->deploc}}
            </div>
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Examination Date</h6>
          </div>
          <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_4}}</div>
        </div>
        <!----------------------------------->
        <div class="row">
          <div class="bg-dark col-2 p-0 border-dark middle">
              <h6 class="white text-bold-600 pl-1 mb-0">Description</h6>
          </div>
          <div class="col-10 p-0 pl-1 border-dark text-16 black" style="height: 80px;">{{$visual->desc}}</div>
        </div>
        <!----------------------------------->
        <div class="row">
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Identification No</h6>
          </div>
          <div class="col-4 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_6}}</div>
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Ref. Standard</h6>
          </div>
          <div class="col-4 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_7}}</div>
        </div>
        <!----------------------------------->
        <div class="row">
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Material</h6>
          </div>
          <div class="col-4 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_8}}</div>
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Instrument Used</h6>
          </div>
          <div class="col-4 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_9}}</div>
        </div>
        <!----------------------------------->
        <div class="row">
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Material Thickness</h6>
          </div>
          <div class="col-4 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_10}}</div>
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Light Intensity</h6>
          </div>
          <div class="col-4 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_11}}</div>
        </div>
        <!----------------------------------->
        <div class="row">
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Welding Process</h6>
          </div>
          <div class="col-4 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_12}}</div>
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Light Source</h6>
          </div>
          <div class="col-4 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_13}}</div>
        </div>
        <!----------------------------------->
        <div class="row">
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Type of Joint</h6>
          </div>
          <div class="col-4 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_14}}</div>
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Location</h6>
          </div>
          <div class="col-4 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_15}}</div>
        </div>
        <!----------------------------------->
        <div class="row">
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Surface Condition</h6>
          </div>
          <div class="col-4 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_16}}</div>
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Acceptance Standard</h6>
          </div>
          <div class="col-4 p-0 pl-1 border-dark text-16 black">{{$visual->acceptance}}</div>
        </div>
        <!----------------------------------->
        <div class="row">
          <div class="bg-dark col-2 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Stage of Exam</h6>
          </div>
          <div class="col-10 p-0 pl-1 border-dark text-16 black">{{$visual->nvr_18}}</div>
        </div>
        <!----------------------------------->
        @foreach(json_decode($visual->nvr_19) as $key => $value)
        <div class="row">
          <div class="bg-dark col-12 p-0 border-dark">
              <h6 class="white text-bold-600 pl-1 mb-0">Summery {{$key+1}}</h6>
          </div>
        </div>
        <div class="row">
          <div class="col-6 p-0 pl-1 border-dark text-16 black">{{$value->nvr_22}}</div>
          <div class="col-6 p-1 border-dark mid11" style="height: 300px;">
            @php
              $storedPhoto = trim((string) ($value->nvr_23 ?? ''));
              $photoPath = $storedPhoto === ''
                  ? null
                  : (\Illuminate\Support\Str::startsWith($storedPhoto, 'camera/inspection/ndt/visual/')
                      ? ltrim($storedPhoto, '/')
                      : 'camera/inspection/ndt/visual/'.ltrim($storedPhoto, '/'));
              $photoExists = $photoPath && is_file(public_path('storage/'.$photoPath));
            @endphp
            @if($photoExists)
              <img class="media-object" style="max-height: 100%; max-width: 100%; margin: auto; padding: 0 !important;" src="{{ asset('storage/'.$photoPath) }}" alt="" />
            @endif
          </div>
        </div>
        @endforeach
        @if(count(json_decode($visual->nvr_19)) < 2)
          @for($i = 2-count(json_decode($visual->nvr_19)); $i > 0; $i--)
          <div class="row">
            <div class="bg-dark col-12 p-0 border-dark">
                <h6 class="white text-bold-600 pl-1 mb-0">Summery {{$i+1}}</h6>
            </div>
          </div>
          <div class="row">
            <div class="col-6 p-0 pl-1 border-dark text-16 black"></div>
            <div class="col-6 p-1 border-dark mid11" style="height: 300px;">
            </div>
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
              <span class="noncheckedfrom {{$visual->checkbox_yes($visual->nvr_23)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
              <label class="black" style="font-size: 1.3rem; font-weight: 600;">Accept</label>
          </div>
          <div class="col-2 mid">
              <span class="noncheckedfrom {{$visual->checkbox_no($visual->nvr_23)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
              <label class="black" style="font-size: 1.3rem; font-weight: 600;">Reject</label>
          </div>
        </div>



@include('layouts.styles.reportfooter-v2')
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $visual->report->id])
@endpush
