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
            font-size: 8.5px;
            margin: 0;
            color: #000;
        }

        .page-shell {
            width: 100%;
            height: 198mm;
            position: relative;
            page-break-inside: avoid;
        }

        .page-block {
            width: 100%;
            page-break-inside: avoid;
        }

        .content-block {
            padding-bottom: 22mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td, th {
            border: 1px solid #000;
            padding: 2px 4px;
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

        .header-logo {
            width: 165px;
            height: auto;
        }

        .footer-image {
            width: 320px;
            height: auto;
        }

        .page-title {
            font-size: 21px;
            font-weight: bold;
            margin: 0;
        }

        .page-subtitle {
            font-size: 9px;
            font-weight: bold;
            margin: 2px 0 0;
        }

        .record-row td {
            height: 24px;
        }

        .footer-table td {
            font-size: 8.5px;
            font-weight: bold;
        }

        .footer-box {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .header-contact {
            font-size: 8px;
            line-height: 1.2;
            word-break: break-word;
        }
    </style>
</head>
<body>
@foreach($subCollections as $chunk)
    <div class="page-shell">
    <div class="page-block">
    <div class="content-block">
    <table class="borderless">
        <tr>
            <td style="width: 18%;">
                <img src="{{ $logo_path }}" alt="logo" class="header-logo">
            </td>
            <td style="width: 52%;" class="center">
                <p class="page-title">{{ $page_name }}</p>
                @if($page_text !== '')
                    <p class="page-subtitle">{{ $page_text }}</p>
                @endif
            </td>
            <td style="width: 30%;" class="right header-contact">
                <div><strong>Head Office: Block# 3053|Hamdy Ramadan street</strong></div>
                <div><strong>2nd Floor #2 |El-Mearag City|Maadi|Cairo|Egypt</strong></div>
                <div><strong>+20 2 24477058 | +20 1032703368</strong></div>
                <div><strong>rse@rigsolutionz.com</strong></div>
                <div><strong>www.rigsolutionz.com</strong></div>
            </td>
        </tr>
    </table>

    <table style="margin-top: 4px;">
        <tr>
            <td class="dark" style="width: 14%;">Client</td>
            <td style="width: 36%;">{{ optional($model->job_request->client)->name ?: optional($model->job_request->supplier)->name }}</td>
            <td class="dark" style="width: 14%;">Register Number</td>
            <td style="width: 36%;">{{ $code }}</td>
        </tr>
        <tr>
            <td class="dark">Work location</td>
            <td>{{ $model->job_request->clientDepartment ? $model->job_request->clientDepartment->name.' / '.$model->job_request->deploc : $model->job_request->deploc }}</td>
            <td class="dark">Register Date</td>
            <td>{{ $model->register_date }}</td>
        </tr>
    </table>

    <table style="margin-top: 4px;">
        <thead>
        <tr class="center dark">
            <th style="width: 18%;">Identification No</th>
            <th style="width: 33%;">Description</th>
            <th style="width: 15%;">Accept Criteria</th>
            <th style="width: 12%;">Report No</th>
            <th style="width: 14%;">Examination Date</th>
            <th style="width: 8%;">Accept</th>
        </tr>
        </thead>
        <tbody>
        @foreach($chunk as $row)
            <tr class="record-row center">
                <td>{{ $row->nmpr_28 ?? '' }}</td>
                <td>{{ $row->desc ?? '' }}</td>
                <td>{{ $row->acceptance ?? '' }}</td>
                <td>{{ optional($row->job_request)->code ? $row->job_request->code.'/'.$row->code : '' }}</td>
                <td>{{ $row->nmpr_6 ?? '' }}</td>
                <td>{{ isset($row->nmpr_30) ? ($row->checkbox_yes($row->nmpr_30) === 'checked' ? 'Accepted' : 'Rejected') : '' }}</td>
            </tr>
        @endforeach
        @for ($i = count($chunk); $i < 18; $i++)
            <tr class="record-row center">
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
        @endfor
        </tbody>
    </table>

    </div>
    <div class="footer-box">
        <table class="footer-table" style="margin-top: 4px;">
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
                <td>{{ $loop->iteration }} of {{ $total }}</td>
            </tr>
        </table>

        <table class="borderless" style="margin-top: 4px;">
            <tr>
                <td class="center">
                    <img src="{{ $footer_image_path }}" alt="footer" class="footer-image">
                </td>
            </tr>
        </table>
    </div>
    </div>
    </div>

    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
</html>
