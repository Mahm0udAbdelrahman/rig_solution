@php
    $footerAddress = $footerAddress ?? \App\Models\GeneralInfo\FooterAddress::getFooterAddress();
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A4 landscape;
            margin: 4mm 5mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            margin: 0;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td, th {
            border: 1px solid #000;
            padding: 2px 3px;
            vertical-align: middle;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .borderless td {
            border: 0;
            padding: 0;
        }

        .dark {
            background: #d9d9d9;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .page-shell {
            width: 100%;
            height: 198mm;
            position: relative;
            page-break-inside: avoid;
        }

        .content-block {
            padding-bottom: 28mm;
        }

        .header-logo {
            width: 165px;
            height: auto;
        }

        .header-contact {
            font-size: 7.5px;
            line-height: 1.2;
            word-break: break-word;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            line-height: 1.15;
            margin: 0;
        }

        .section-gap {
            margin-top: 4px;
        }

        .record-row td {
            height: 31px;
            text-align: center;
        }

        .footer-box {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .footer-table td {
            font-size: 8px;
            font-weight: bold;
        }

        .footer-image {
            width: 320px;
            height: auto;
        }

        .page-no {
            white-space: nowrap;
        }
    </style>
</head>
<body>
@foreach($subCollections as $r)
    <div class="page-shell">
        <div class="content-block">
            <table class="borderless">
                <tr>
                    <td style="width: 18%;">
                        <img src="{{ $logo_path }}" alt="logo" class="header-logo">
                    </td>
                    <td style="width: 52%;" class="center">
                        <div class="title">{{ $page_name }}</div>
                        @if($page_text !== '')
                            <div>{{ $page_text }}</div>
                        @endif
                    </td>
                    <td style="width: 30%;" class="right header-contact"></td>
                </tr>
            </table>

            <table class="section-gap">
                <tr>
                    <td class="dark" style="width: 14%;">Client Name</td>
                    <td style="width: 36%;">{{ optional($job->client)->name ?: optional($job->supplier)->name }}</td>
                    <td class="dark" style="width: 14%;">Register No.</td>
                    <td style="width: 36%;">{{ $job->code }} / {{ $code }}</td>
                </tr>
            </table>

            <table>
                <tr>
                    <td class="dark" style="width: 14%;">Rig / Location</td>
                    <td style="width: 19%;">{{ $job->deploc }}</td>
                    <td class="dark" style="width: 14%;">Color Code</td>
                    <td style="width: 19%;">{{ $color }}</td>
                    <td class="dark" style="width: 14%;">Date</td>
                    <td style="width: 20%;">{{ $date }}</td>
                </tr>
            </table>

            <table class="section-gap">
                <thead>
                <tr class="center dark">
                    <th style="width: 14%;">ID</th>
                    <th style="width: 17%;">Equipment Description</th>
                    <th style="width: 6%;">SWL</th>
                    <th style="width: 4%;">Gross</th>
                    <th style="width: 8%;">Cert. No</th>
                    <th style="width: 5%;">Test Date</th>
                    <th style="width: 6%;">Tested By</th>
                    <th style="width: 7%;">Report #</th>
                    <th style="width: 5%;">Exam. date</th>
                    <th style="width: 5%;">Due Date</th>
                    <th style="width: 9%;">Attached or location</th>
                    <th style="width: 4%;">Accept</th>
                </tr>
                </thead>
                <tbody>
                @foreach($r as $row)
                    <tr class="record-row">
                        <td>
                            {{ $row->reportable->lcr_12 }}
                            {{ $row->reportable->locr_12 }}
                            {{ $row->reportable->lfr_12 }}
                            @if($row->reportable->lter_10 != null)
                                {{ $row->reportable->lter_10['pop1'] }}
                            @endif
                        </td>
                        <td>
                            {{ $row->reportable->lcr_10 }}
                            {{ $row->reportable->locr_10 }}
                            {{ $row->reportable->lfr_10 }}
                            @if($row->reportable->lter_10 != null)
                                {{ $row->reportable->lter_10['pop20'] }}
                            @endif
                        </td>
                        <td>
                            {{ $row->reportable->lcr_15 }}
                            {{ $row->reportable->locr_15 }}
                            {{ $row->reportable->lfr_15 }}
                            @if($row->reportable->lter_10 != null)
                                {{ $row->reportable->lter_10['pop4'] }}
                            @endif
                        </td>
                        <td>{{ $row->reportable->lter_19 }}</td>
                        <td>
                            {{ $row->reportable->lcr_23 }}
                            {{ $row->reportable->locr_23 }}
                            {{ $row->reportable->lfr_17 }}
                            {{ $row->reportable->lter_21 }}
                        </td>
                        <td>
                            {{ $row->reportable->lcr_22 }}
                            {{ $row->reportable->locr_22 }}
                            {{ $row->reportable->lfr_16 }}
                            {{ $row->reportable->lter_22 }}
                        </td>
                        <td>
                            {{ $row->reportable->lcr_24 }}
                            {{ $row->reportable->locr_24 }}
                            {{ $row->reportable->lfr_18 }}
                            {{ $row->reportable->lter_20 }}
                        </td>
                        <td>{{ $row->job_request->code }} / {{ $row->reportable->code }}</td>
                        <td>
                            {{ $row->reportable->lcr_6 }}
                            {{ $row->reportable->locr_6 }}
                            {{ $row->reportable->lfr_6 }}
                            {{ $row->reportable->lter_6 }}
                        </td>
                        <td>
                            {{ $row->reportable->lcr_7 }}
                            {{ $row->reportable->locr_7 }}
                            {{ $row->reportable->lfr_7 }}
                            {{ $row->reportable->lter_7 }}
                        </td>
                        <td>{{ $row->reportable->lter_15 }}</td>
                        <td>
                            @if(strstr($row->reportable->lcr_46, '_y') || strstr($row->reportable->locr_44, '_y') || strstr($row->reportable->lfr_33, '_y') || strstr($row->reportable->lter_51, '_y'))
                                YES
                            @else
                                NO
                            @endif
                        </td>
                    </tr>
                @endforeach
                @for ($i = count($r); $i < 28; $i++)
                    <tr class="record-row">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>

        <div class="footer-box">
            <table class="footer-table">
                <tr>
                    <td class="dark">Form No</td>
                    <td>{{ optional($model->footer_values)->form_no }}</td>
                    <td class="dark">Issue No</td>
                    <td>{{ optional($model->footer_values)->issue_no }}</td>
                    <td class="dark">Issue Date</td>
                    <td>{{ optional($model->footer_values)->issue_date }}</td>
                    <td class="dark">Revision No</td>
                    <td>{{ optional($model->footer_values)->revision_no }}</td>
                    <td class="dark">Revision Date</td>
                    <td>{{ optional($model->footer_values)->revision_date }}</td>
                    <td class="dark">Page No.</td>
                    <td class="page-no">{{ $loop->iteration }} of {{ $total }}</td>
                </tr>
            </table>
            <table class="borderless" style="margin-top: 4px; text-align: center; width: 100%;">
                <tr>
                    <td class="center" style="font-size: 9px; font-weight: bold;">
                        <div>{{ $footerAddress->getPartsPlainText() }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
</html>
