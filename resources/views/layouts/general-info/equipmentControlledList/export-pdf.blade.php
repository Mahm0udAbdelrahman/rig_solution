{{-- Equipment Controlled List in the ISO form layout (RS-IMS-P10-F01); "Page X of Y" is drawn by EquipmentControlledListExport --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Equipment Controlled List</title>
    <style>
        @page { margin: 78px 22px 44px 22px; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 6.6px; color: #000; margin: 0; }

        .page-header { position: fixed; top: -66px; left: 0; right: 0; height: 56px; }
        .page-header .logo { height: 46px; }
        .page-header .title { position: absolute; right: 0; top: 6px; text-align: right; }
        .page-header .title .name { font-size: 17px; font-weight: bold; }
        .page-header .title .system { font-size: 7px; font-weight: bold; margin-top: 2px; }

        .page-footer { position: fixed; bottom: -30px; left: 0; right: 0; height: 14px; font-size: 7px; }
        .page-footer table { width: 100%; border-collapse: collapse; }
        .page-footer td { padding: 0; white-space: nowrap; }

        .band { width: 100%; border-collapse: collapse; background: #2F5597; }
        .band { border: 1px solid #000; border-bottom: none; }
        .band td { padding: 3px 6px; }
        .band .company { font-size: 15px; color: #fff; }
        .band .company span { color: #f00; }
        .band .last-update { color: #FFC000; font-size: 12px; font-weight: bold; text-align: right; letter-spacing: 1px; }

        table.list { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.list th, table.list td { border: 0.6px solid #000; padding: 2.5px 2px; text-align: center; vertical-align: middle; word-wrap: break-word; }
        table.list thead { display: table-header-group; }
        table.list th { font-size: 6.4px; font-weight: bold; }
        table.list td.left { text-align: left; }
        table.list tr.grey td { background: #D9D9D9; }
        table.list tr td.service { background: #00B050; font-size: 6px; }
        table.list tr { page-break-inside: avoid; }
    </style>
</head>
<body>
    <div class="page-header">
        <img class="logo" src="{{ $logo }}">
        <div class="title">
            <div class="name">Equipment Controlled List</div>
            <div class="system">Integrated Management System</div>
        </div>
    </div>

    <div class="page-footer">
        <table>
            <tr>
                <td style="width: 9%;">Form No {{ $footer['form_no'] }}</td>
                <td style="width: 6%;">Issue No. {{ $footer['issue_no'] }}</td>
                <td style="width: 21%;">Issue date: {{ $footer['issue_date'] }}</td>
                <td style="width: 13%;">Revision No {{ $footer['revision_no'] }}</td>
                <td style="width: 41%;">Revision Date {{ $footer['revision_date'] }}</td>
                <td style="width: 10%;"></td>
            </tr>
        </table>
    </div>

    <table class="band">
        <tr>
            <td class="company"><span>R</span>ig <span>S</span>olution <span>E</span>ngineering</td>
            <td class="last-update">LAST UPDATE &nbsp;&nbsp;&nbsp;&nbsp; {{ $lastUpdate }}</td>
        </tr>
    </table>

    @php
        $widths = [
            'equipment_description' => 9.5, 'internal_code' => 8, 'manufacturer' => 7, 'model_type' => 6.5, 'capacity_range' => 6,
            'serial_number' => 7.5, 'date_into_service' => 5.2, 'interval' => 5.8, 'calibration_date' => 5, 'calibration_due_date' => 5,
            'calibrated_by' => 4.5, 'alarm' => 5.5, 'location_department' => 6.5, 'status' => 8, 'date_removed_from_service' => 10,
        ];
    @endphp
    <table class="list">
        <thead>
            <tr>
                @foreach($columns as $key => $column)
                    <th style="width: {{ $widths[$key] }}%; background: #{{ $column[1] }};">{{ $column[0] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $index => $row)
                <tr class="{{ $index % 2 === 0 ? 'grey' : '' }}">
                    @foreach($columns as $key => $column)
                        <td class="{{ $key === 'equipment_description' ? 'left' : '' }} {{ $key === 'date_into_service' ? 'service' : '' }}">{{ $row[$key] }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
