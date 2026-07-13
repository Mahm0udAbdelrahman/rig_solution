@extends('layouts.app')

@extends('layouts.styles.paper')

@push('page_content')
@foreach($reports as $report)
    <div class="row page_out">
        <h6 class="col-2 bg-dark white text-bold-600 p-0 mb-0 border-dark" style="padding-left: 5px !important; padding-top: 2px !important;">Customer</h6>
        <p class="col-6 border-dark p-0 mb-0 text-16 black" style="padding-left: 5px !important;">
            @if($lregister->job_request->client)
                {{$lregister->job_request->client->name}}
            @else
                {{$lregister->job_request->supplier->name}}
            @endif
        </p>
        <h6 class="col-2 bg-dark white text-bold-600 p-0 mb-0 border-dark" style="padding-left: 5px !important; padding-top: 2px !important;">Register Date</h6>
        <p class="col-2 border-dark p-0 mb-0 text-16 black" style="padding-left: 5px !important;">{{$lregister->date}}</p>
    </div>
    <div class="row">
        <h6 class="col-2 bg-dark white text-bold-600 p-0 mb-0 border-dark" style="padding-left: 5px !important; padding-top: 2px !important;">Rig/Location</h6>
        <p class="col-4 border-dark p-0 mb-0 text-16 black" style="padding-left: 5px !important;">{{$lregister->job_request->deploc}}</p>
        <h6 class="col-1 bg-dark white text-bold-600 p-0 mb-0 border-dark" style="padding-left: 5px !important; padding-top: 2px !important;">Color Code</h6>
        <p class="col-1 border-dark p-0 mb-0 text-16 black" style="padding-left: 5px !important;">{{$lregister->color_code}}</p>
        <h6 class="col-2 bg-dark white text-bold-600 p-0 mb-0 border-dark" style="padding-left: 5px !important; padding-top: 2px !important;">Report No.</h6>
        <p class="col-2 border-dark p-0 mb-0 text-16 black" style="padding-left: 5px !important;">{{$lregister->job_request->code}} / {{$lregister->code}}</p>
    </div>
    <div class="row page_out" style="height: auto;">
        <table style="width: 100%;">
            <thead>
                <tr class="text-ceter">
                    <th class="border-dark bg-dark white text-bold-600 text-center" width=5%>ID</th>
                    <th class="border-dark bg-dark white text-bold-600 text-center">Equipment Description</th>
                    <th class="border-dark bg-dark white text-bold-600 text-center" width="2%">SWL</th>
                    <th class="border-dark bg-dark white text-bold-600 text-center" width="2%">Gross</th>
                    <th class="border-dark bg-dark white text-bold-600 text-center">Certificate No</th>
                    <th class="border-dark bg-dark white text-bold-600 text-center">Date of Test</th>
                    <th class="border-dark bg-dark white text-bold-600 text-center">Tested By</th>
                    <th class="border-dark bg-dark white text-bold-600 text-center">Report #</th>
                    <th class="border-dark bg-dark white text-bold-600 text-center">Exam. date</th>
                    <th class="border-dark bg-dark white text-bold-600 text-center">Next Exam.</th>
                    <th class="border-dark bg-dark white text-bold-600 text-center">Attached or location</th>
                    <th class="border-dark bg-dark white text-bold-600 text-center" width="2%">Accept</th>
                </tr>
            </thead>
            <tbody class="text-center">

                @foreach($report as $report_after_chunk)
                @if(!strstr( $report_after_chunk->reportable_type, 'App\Models\Inspection\Ndt' ) && $report_after_chunk->reportable_type != 'App\Models\Inspection\Lifting\Defect' && $report_after_chunk->reportable_type != 'App\Models\Inspection\Lifting\Lregister')

                  <tr>
                    <td class="border-dark">

                        {{$report_after_chunk->reportable->lcr_12}}
                        {{$report_after_chunk->reportable->locr_12}}
                        {{$report_after_chunk->reportable->lfr_12}}
                        @if($report_after_chunk->reportable->lter_10 != null)
                          {{$report_after_chunk->reportable->lter_10['pop1']}}
                        @endif
                    </td>
                    <td class="border-dark">
                      {{$report_after_chunk->reportable->lcr_10}}
                      {{$report_after_chunk->reportable->locr_10}}
                      {{$report_after_chunk->reportable->lfr_10}}
                      @if($report_after_chunk->reportable->lter_10 != null)
                        {{$report_after_chunk->reportable->lter_10['pop20']}}
                      @endif
                    </td>
                    <td class="border-dark">
                      {{$report_after_chunk->reportable->lcr_15}}
                      {{$report_after_chunk->reportable->locr_15}}
                      {{$report_after_chunk->reportable->lfr_15}}
                      @if($report_after_chunk->reportable->lter_10 != null)
                        {{$report_after_chunk->reportable->lter_10['pop4']}}
                      @endif
                    </td>
                    <td class="border-dark">{{$report_after_chunk->reportable->lter_19}}</td>
                    <td class="border-dark">
                      {{$report_after_chunk->reportable->lcr_23}}
                      {{$report_after_chunk->reportable->locr_23}}
                      {{$report_after_chunk->reportable->lfr_17}}
                      {{$report_after_chunk->reportable->lter_21}}
                    </td>
                    <td class="border-dark">
                      {{$report_after_chunk->reportable->lcr_22}}
                      {{$report_after_chunk->reportable->locr_22}}
                      {{$report_after_chunk->reportable->lfr_16}}
                      {{$report_after_chunk->reportable->lter_22}}
                    </td>
                    <td class="border-dark">
                      {{$report_after_chunk->reportable->lcr_24}}
                      {{$report_after_chunk->reportable->locr_24}}
                      {{$report_after_chunk->reportable->lfr_18}}
                      {{$report_after_chunk->reportable->lter_20}}
                    </td>
                    <td class="border-dark">
                      @php ($goto = '') @endphp
                      @switch($report_after_chunk->reportable_type)
                        @case('App\Models\Inspection\Lifting\Crane')
                          @php ($goto = route('crane.show', $report_after_chunk->reportable->id)) @endphp
                          @break

                        @case('App\ModelsInspection\Lifting\OverheadCrane')
                          @php ($goto = route('overheadCrane.show', $report_after_chunk->reportable->id)) @endphp
                          @break

                        @case('App\ModelsInspection\Lifting\Forklift')
                          @php ($goto = route('forklift.show', $report_after_chunk->reportable->id)) @endphp
                          @break

                        @case('App\ModelsInspection\Lifting\ThroughExamination')
                          @php ($goto = route('throughExamination.show', $report_after_chunk->reportable->id)) @endphp
                          @break

                      @endswitch

                      <a href="{{$goto}}" target="_blank">
                        {{$lregister->job_request->code}} / {{$report_after_chunk->reportable->code}}
                      </a>
                    </td>
                    <td class="border-dark">
                      {{$report_after_chunk->reportable->lcr_6}}
                      {{$report_after_chunk->reportable->locr_6}}
                      {{$report_after_chunk->reportable->lfr_6}}
                      {{$report_after_chunk->reportable->lter_6}}
                    </td>
                    <td class="border-dark">
                      {{$report_after_chunk->reportable->lcr_7}}
                      {{$report_after_chunk->reportable->locr_7}}
                      {{$report_after_chunk->reportable->lfr_7}}
                      {{$report_after_chunk->reportable->lter_7}}
                    </td>
                    <td class="border-dark">{{$report_after_chunk->reportable->lter_15}}</td>
                    <td class="border-dark">
                      @if(strstr($report_after_chunk->reportable->lcr_46, '_y') || strstr($report_after_chunk->reportable->locr_44, '_y') || strstr($report_after_chunk->reportable->lfr_33, '_y') || strstr($report_after_chunk->reportable->lter_51, '_y'))
                        YES
                      @else
                        NO
                      @endif
                    </td>
                </tr>

                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach

@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $lregister->report->id])
@endpush
