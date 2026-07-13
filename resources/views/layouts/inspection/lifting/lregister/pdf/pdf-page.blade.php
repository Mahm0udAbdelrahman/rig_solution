<style>
                * {
                    -webkit-print-color-adjust: exact !important; /* Chrome, Safari, Edge */
                    color-adjust: exact !important; /*Firefox*/
                }

                label {
                    font-size: 0.8rem;
                }

                .app-content .wizard.wizard-circle > .steps > ul > li.current ~ li:before, .app-content .wizard.wizard-circle > .steps > ul > li.current ~ li:after, .app-content .wizard.wizard-circle > .steps > ul > li.current:after {
                    background-color: #fff;
                }

                p {
                    letter-spacing: inherit;
                    margin-bottom: 0 !important;
                }

                .custom-control-label {
                    margin-bottom: 5px;
                }

                .custom-control-label::after {
                    top: 0 !important;
                }

                .skin-square label {
                    margin-bottom: auto;
                }

                .bg-dark {
                    background-color: #d9d9d9 !important;
                }

                .white {
                    color: #000 !important;
                }

                .logo-top {
                    width: 225px;
                    height: 115px;
                    margin-top: -1.5em;
                }

                .border-dark { /*padding-top: 3px !important; padding-bottom: 3px !important;*/
                }

                .card-body {
                    padding: 1rem 2rem;
                }

                .middle {
                    display: flex;
                    align-items: center;
                }

                .mid11 {
                    display: flex;
                    align-items: center;
                }

                .just {
                    justify-content: center;
                }

                .noncheckedfrom {
                    background: url({{asset('app-assets/vendors/css/forms/icheck/square/purple.png')}}) -1px -1px no-repeat;
                    display: inline-block;
                    margin-right: 0.6rem;
                    width: 17px;
                    height: 17px;
                    border: 1px solid #d9d9d9;
                }

                .noncheckedradiofrom {
                    background: url({{asset('app-assets/vendors/css/forms/icheck/square/purple.png')}}) -1px -1px no-repeat;
                    display: inline-block;
                    margin-right: 0.6rem;
                    width: 17px;
                    height: 17px;
                    border: 1px solid #d9d9d9;
                    border-radius: 15px;
                }

                .checked {
                    background-position: -51px -3px;
                    border-color: #6a5a8c;
                }

                .mid {
                    vertical-align: middle;
                    display: flex;
                    padding-top: 3px;
                    padding-bottom: 3px;
                }

                .text-16, .skin-square label {
                    font-size: 16px;
                }

                p.bnew {
                    font-size: 22px !important;
                    font-weight: 600 !important;
                }

                h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
                    color: #000 !important;
                }

                .h-90 {
                    height: 130px;
                }

                .h-80 {
                    height: 120px;
                }

                .h-70 {
                    height: 100px;
                }

                .h-130 {
                    height: 150px;
                }

                .h-175 {
                    height: 310px;
                }

                .hp-250 {
                    height: 250px;
                }

                .card {
                    page-break-before: always;
                    counter-increment: page;
                }

                .inc:after {
                    content: counter(page) " of " counter(pages);
                }

                .donw {
                    max-width: 1150px;
                    height: 1300px;
                    margin: auto;
                    border-radius: 0;
                    box-shadow: none;
                    padding-left: 18px;
                    padding-right: 18px;
                }

                .emadnew {
                    font-size: 11px !important;
                }

                @page {
                    margin: 0 !important;
                    padding: 0 !important;
                    size: a4;  /* margin-right: 5mm !important; margin-left: 5mm !important;*/
                }
            </style>

            <section class="validation mb-1">
                <div class="card donw" id="{{$id}}">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row header-top mb-1" style="padding-top: 5px;">
                                <div class="col-3 pl-0">
									@if ($model->inspection_logo)
										<img src="{{ asset('storage/' . $model->inspection_logo) }}" class="logo-top" />
									@else
										<img src="{{asset('app-assets/images/logo/combined-logo.png')}}" class="logo-top" />
									@endif
                                
                                </div>
                                <div class="col-6">
                                    @if (!str_contains($page_text, 'Invoice'))
                                        @if (str_contains($page_name, 'Crane'))
                                            <h3 class="text-bold-600 text-center mb-0 mt-2">{{$page_name ?? ''}}</h3>
                                        @else
                                            <h1 class="text-bold-600 text-center mb-0 mt-2">{{$page_name ?? ''}}</h1>
                                        @endif
                                        <p class="text-center text-bold-600 emadnew" style="margin-bottom: 5px;
                                        @if (str_contains($page_name, 'Crane'))
                                                font-size: 14px !important;
                                        @endif
                                                ">{{$page_text ?? ''}}</p>
                                    @else
                                        <h1 class="text-bold-600 text-center white"
                                            style="font-size: 5.5rem; margin-bottom: 0;">{{strtoupper($page_name) ?? ''}}</h1>
                                        <p class="text-bold-600 text-center mb-0 white"
                                           style="font-size: 1.8rem !important;">{{$page_text ?? ''}}</p>
                                    @endif
                                </div>
                                <div class="col-3 pr-0 text-right address" style="font-size: 10px;">
                                    <div class="text-bold-700 white"
                                         style="display: flex; flex-direction: column; align-items: flex-end; text-align: right;  width: 105%; margin-left: -12px;">
                                        <p>Head Office: Block# 3053|Hamdy Ramadan street</p>
                                        <p>2nd Floor #2 |El-Mearag City|Maadi|Cairo|Egypt</p>
                                        <p>
                                            <i class="ft-phone"></i> : +20 2 24477058 |
                                            <i class="ft-smartphone"></i> : +20 1032703368
                                        </p>
                                        <p>
                                            <i class="ft-mail"></i> : rse@rigsolutionz.com
                                        </p>
                                        <p>
                                            Website: <a href="www.rigsolutionz.com">www.rigsolutionz.com</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <table width="100%" style="font-size: 80%; margin: auto; background: white;">
                                <tr>
                                    <td class="border-dark bg-dark white text-bold-600" style="padding-left: 3px;" width="16.1%">
                                        Client Name
                                    </td>
                                    <td class="border-dark white text-bold-600" width="51.7%" style="padding-left: 3px;">
                                        {{ optional($job->client)->name ?: optional($job->supplier)->name }}
                                    </td>
                                    <td class="border-dark bg-dark white text-bold-600" width="16.1%" style="padding-left: 3px;">
                                        Register No.
                                    </td>
                                    <td class="border-dark white text-bold-600" width="16.1%" style="padding-left: 3px;">
                                        {{$job->code}} / {{$code}}
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" style="font-size: 80%; margin: auto; background: white;">
                                <tr>
                                    <td class="border-dark bg-dark white text-bold-600"style="padding-left: 3px;" width="16.1%">
                                        Rig / Location
                                    </td>
                                    <td class="border-dark white text-bold-600"style="padding-left: 3px;" width="19.5%">
                                        {{$job->deploc}}
                                    </td>
                                    <td class="border-dark bg-dark white text-bold-600"style="padding-left: 3px;" width="16.1%">
                                        Color Code
                                    </td>
                                    <td class="border-dark white text-bold-600" style="padding-left: 3px;" width="16.1%">
                                        {{$color}}
                                    </td>
                                    <td class="border-dark bg-dark white text-bold-600" style="padding-left: 3px;" width="16.1%">
                                        Date
                                    </td>
                                    <td class="border-dark white text-bold-600" style="padding-left: 3px;" width="16.1%">
                                        {{$date}}
                                    </td>
                                </tr>
                            </table>
                            </div>
                            {{--<body class="snappy_new">--}}
                            <div class="row" style="margin-top:3px;/* page-break-before:always;*/">
                                <table style="width: 100%; margin: auto; background: white !important;/* page-break-before: always;*/">
                                    <thead>
                                    <tr class="text-ceter" style="font-size: 75% !important">
                                        <td class="border-dark bg-dark white text-bold-600 text-center" width="10%">
                                            ID
                                        </td>
                                        <td class="border-dark bg-dark white text-bold-600 text-center" width="20%">
                                            Equipment Description
                                        </td>
                                        <td class="border-dark bg-dark white text-bold-600 text-center" width="8%">
                                            SWL
                                        </td>
                                        <td class="border-dark bg-dark white text-bold-600 text-center" width="5%">
                                            Gross
                                        </td>
                                        <td colspan="3" width="21%"
                                            style="background: #d9d9d9; border: 1px solid #424242 !important;">
                                            <table width="100%">
                                                <tr style="width: 100%; border-bottom: 1px solid #424242 !important;">
                                                    <td class="white text-bold-600 text-center"
                                                        style="font-size: 98%; ">Manufacturer / Test Certificate Details
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="100%">
                                                <tr>
                                                    <td class="white text-bold-600 text-center"
                                                        style="border-right: 1px solid #424242;width: 33%;">Cert. No
                                                    </td>
                                                    <td class="white text-bold-600 text-center"
                                                        style="border-right: 1px solid #424242;width: 26%;">Test Date
                                                    </td>
                                                    <td class="white text-bold-600 text-center" style="width: 39.7%;">
                                                        Tested By
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td colspan="3" width="18%"
                                            style="background: #d9d9d9; border: 1px solid #424242 !important;">
                                            <table width="100%">
                                                <tr style="width: 100%; border-bottom: 1px solid #424242 !important;">
                                                    <td class="white text-bold-600 text-center"
                                                        style="font-size: 98%; ">Current Certificate Details
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="100%">
                                                <tr>
                                                    <td class="white text-bold-600 text-center"
                                                        style="border-right: 1px solid #424242;width: 36%;font-size: 91%;">
                                                        Report #
                                                    </td>
                                                    <td class="white text-bold-600 text-center"
                                                        style="border-right: 1px solid #424242;width: 32%;">Exam. date
                                                    </td>
                                                    <td class="white text-bold-600 text-center" style="width: 32%;">Due
                                                        Date
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="border-dark bg-dark white text-bold-600 text-center" width="10%">
                                            Attached or location
                                        </td>
                                        <td class="border-dark bg-dark white text-bold-600 text-center" width="3%">
                                            Accept
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody style="color: #000; ">

                                    @foreach($r as $report_after_chunk)
                                        @if(strstr( $report_after_chunk->reportable_type, 'App\Models\Inspection\Lifting' ) && $report_after_chunk->reportable_type != 'App\Models\Inspection\Lifting\Defect' && $report_after_chunk->reportable_type != 'App\Models\Inspection\Lifting\Lregister')
                                            <tr style="font-size: 75% !important; text-align: center;">
                                                <td class="border-dark" width="10%">
                                                    {{$report_after_chunk->reportable->lcr_12}}
                                                    {{$report_after_chunk->reportable->locr_12}}
                                                    {{$report_after_chunk->reportable->lfr_12}}
                                                    @if($report_after_chunk->reportable->lter_10 != null)
                                                        {{$report_after_chunk->reportable->lter_10['pop1']}}
                                                    @endif
                                                </td>
                                                <td class="border-dark" width="20%">
                                                    {{$report_after_chunk->reportable->lcr_10}}
                                                    {{$report_after_chunk->reportable->locr_10}}
                                                    {{$report_after_chunk->reportable->lfr_10}}
                                                    @if($report_after_chunk->reportable->lter_10 != null)
                                                        {{$report_after_chunk->reportable->lter_10['pop20']}}
                                                    @endif
                                                </td>
                                                <td class="border-dark" width="8%">
                                                    {{$report_after_chunk->reportable->lcr_15}}
                                                    {{$report_after_chunk->reportable->locr_15}}
                                                    {{$report_after_chunk->reportable->lfr_15}}
                                                    @if($report_after_chunk->reportable->lter_10 != null)
                                                        {{$report_after_chunk->reportable->lter_10['pop4']}}
                                                    @endif
                                                </td>
                                                <td class="border-dark" width="5%">
                                                    {{$report_after_chunk->reportable->lter_19}}
                                                </td>
                                                <td class="border-dark" width="9.2%">
                                                    {{$report_after_chunk->reportable->lcr_23}}
                                                    {{$report_after_chunk->reportable->locr_23}}
                                                    {{$report_after_chunk->reportable->lfr_17}}
                                                    {{$report_after_chunk->reportable->lter_21}}
                                                </td>
                                                <td class="border-dark" width="7.2%">
                                                    {{$report_after_chunk->reportable->lcr_22}}
                                                    {{$report_after_chunk->reportable->locr_22}}
                                                    {{$report_after_chunk->reportable->lfr_16}}
                                                    {{$report_after_chunk->reportable->lter_22}}
                                                </td>
                                                <td class="border-dark" width="11%">
                                                    {{$report_after_chunk->reportable->lcr_24}}
                                                    {{$report_after_chunk->reportable->locr_24}}
                                                    {{$report_after_chunk->reportable->lfr_18}}
                                                    {{$report_after_chunk->reportable->lter_20}}
                                                </td>
                                                <td class="border-dark" width="8%">
                                                    {{$report_after_chunk->job_request->code}}
                                                    / {{$report_after_chunk->reportable->code}}
                                                </td>
                                                <td class="border-dark" width="7%">
                                                    {{$report_after_chunk->reportable->lcr_6}}
                                                    {{$report_after_chunk->reportable->locr_6}}
                                                    {{$report_after_chunk->reportable->lfr_6}}
                                                    {{$report_after_chunk->reportable->lter_6}}
                                                </td>
                                                <td class="border-dark" width="7%">
                                                    {{$report_after_chunk->reportable->lcr_7}}
                                                    {{$report_after_chunk->reportable->locr_7}}
                                                    {{$report_after_chunk->reportable->lfr_7}}
                                                    {{$report_after_chunk->reportable->lter_7}}
                                                </td>
                                                <td class="border-dark" width="10%">
                                                    {{$report_after_chunk->reportable->lter_15}}
                                                </td>
                                                <td class="border-dark" width="3%">
                                                    @if(strstr($report_after_chunk->reportable->lcr_46, '_y') || strstr($report_after_chunk->reportable->locr_44, '_y') || strstr($report_after_chunk->reportable->lfr_33, '_y') || strstr($report_after_chunk->reportable->lter_51, '_y'))
                                                        YES
                                                    @else
                                                        NO
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    @php
                                        $rowCount = $r->count();
                                        $remainingRows = max(0, 28 - $rowCount);
                                    @endphp
                                    @for($i = 0; $i < $remainingRows; $i++)
                                        <tr style="font-size: 70% !important; text-align: center;">
                                            <td class="border-dark" width="10%" style="height: 3em;"></td>
                                            <td class="border-dark" width="20%" style="height: 3em;"></td>
                                            <td class="border-dark" width="8%" style="height: 3em;"></td>
                                            <td class="border-dark" width="5%" style="height: 3em;"></td>
                                            <td class="border-dark" width="4%" style="height: 3em;"></td>
                                            <td class="border-dark" width="7%" style="height: 3em;"></td>
                                            <td class="border-dark" width="11%" style="height: 3em;"></td>
                                            <td class="border-dark" width="8%" style="height: 3em;"></td>
                                            <td class="border-dark" width="7%" style="height: 3em;"></td>
                                            <td class="border-dark" width="7%" style="height: 3em;"></td>
                                            <td class="border-dark" width="10%" style="height: 3em;"></td>
                                            <td class="border-dark" width="3%" style="height: 3em;"></td>
                                        </tr>
                                    @endfor

                                    </tbody>
                                </table>
                            </div>
                            <div class="row" style="font-size: 12px; color: black; font-weight: 600; margin-top: 3px;">
                                <div class="col-1 bg-dark p-0 border-dark">
                                    <span style="margin-left: 3px;">Form No</span>
                                </div>
                                <div class="col-1 p-0 pl-1 border-dark">{{$model->footer_values->form_no}}</div>

                                <div class="bg-dark col-1 p-0 border-dark">
                                    <span style="margin-left: 3px;">Issue No</span>
                                </div>
                                <div class="col-1 p-0 pl-1 border-dark">{{$model->footer_values->issue_no}}</div>

                                <div class="bg-dark col-1 p-0 border-dark">
                                    <span style="margin-left: 3px;">Issue Date</span>
                                </div>
                                <div class="col-1 p-0 pl-1 border-dark">{{$model->footer_values->issue_date}}</div>

                                <div class="bg-dark col-1 p-0 border-dark">
                                    <span style="margin-left: 3px;">Revision No</span>
                                </div>
                                <div class="col-1 p-0 pl-1 border-dark">{{$model->footer_values->revision_no}}</div>

                                <div class="bg-dark col-1 p-0 border-dark">
                                    <span style="margin-left: 3px;">Revision Date</span>
                                </div>
                                <div class="col-1 p-0 pl-1 border-dark">{{$model->footer_values->revision_date}}</div>

                                <div class="bg-dark col-1 p-0 border-dark">
                                    <span style="margin-left: 3px;">Page No.</span>
                                </div>
                                <div class="col-1 p-0 pl-1 border-dark">{{$page_number}}</div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-3 text-bold-600 pl-0">{{--{{$iso_number}}--}}</div>
                                <div class="col-6">
                                    <img src="{{asset('app-assets/images/footer-v2.png')}}"
                                         style="width: 40em; height: 3.5em;"/>
                                </div>
                                <div class="col-3 text-right text-bold-600 pr-0"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
