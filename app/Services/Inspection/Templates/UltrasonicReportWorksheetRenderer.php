<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Ndt\Ultrasonic;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UltrasonicReportWorksheetRenderer extends AbstractLiftingReportWorksheetRenderer
{
    public function render(Worksheet $sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        /** @var Ultrasonic|null $ultrasonic */
        $ultrasonic = $report->reportable;
        if (!$ultrasonic instanceof Ultrasonic) {
            return;
        }

        $ultrasonic->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment');
        $report->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'user.employee');

        $approvedUser = !empty($report->user_id_approved) ? User::query()->with('employee')->find($report->user_id_approved) : null;
        $footerValues = $ultrasonic->footer_values;

        $this->bootSheet($sheet, 'REV '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));
        $this->configureColumns($sheet);
        $this->addHeaderImages($sheet, $report);

        $row = $this->renderStandardHeader(
            $sheet,
            'Ultrasonic Inspection Report',
            'This report complies with the approved NDT workflow and internal inspection form'
        );

        $row = $this->labeledValueRow($sheet, $row, 'Name of employer for whom the examination was made', $this->clientName($report), 'Address of premises at examination was made', $this->clientLocation($report));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Purchase Order', (string) (optional($report->job_request)->purchase_order ?: $ultrasonic->nur_2)],
            ['JCF Number', (string) optional($report->job_request)->code],
            ['Report No', $this->reportNumberWithRevision($report, (string) $ultrasonic->code, $revisionNumber)],
        ]);
        $row = $this->labeledValueRow($sheet, $row, 'Work location', $this->workLocation($report), 'Examination Date', (string) $ultrasonic->nur_6);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Specification', $this->ultrasonicSpecificationText($ultrasonic)],
            ['Other / Edition', trim(implode(' | ', array_filter([(string) $ultrasonic->nur_9, (string) $ultrasonic->nur_10])))],
            ['Acceptance Criteria', (string) $ultrasonic->acceptance],
        ]);

        $row = $this->sectionTitle($sheet, $row, 'Equipment and Technique');
        $row = $this->tripleValueRow($sheet, $row, [
            ['Model', (string) $ultrasonic->nur_12],
            ['Serial Number', (string) $ultrasonic->nur_13],
            ['Manufacturer', (string) $ultrasonic->nur_14],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Couplant Type', (string) $ultrasonic->nur_15],
            ['Cable Type', (string) $ultrasonic->nur_16],
            ['Calibration Block', (string) $ultrasonic->nur_17],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Search Unit', (string) $ultrasonic->nur_18],
            ['Manufacturer', (string) $ultrasonic->nur_19],
            ['Technique', (string) $ultrasonic->nur_20],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Probe Angle Table', trim(implode(' | ', array_filter([
            '0: '.implode(', ', array_filter([$ultrasonic->getMtvalue('nur_21', 'nur_21'), $ultrasonic->getMtvalue('nur_25', 'nur_21'), $ultrasonic->getMtvalue('nur_29', 'nur_21'), $ultrasonic->getMtvalue('nur_33', 'nur_21'), $ultrasonic->getMtvalue('nur_37', 'nur_21'), $ultrasonic->getMtvalue('nur_41', 'nur_21')])),
            '45: '.implode(', ', array_filter([$ultrasonic->getMtvalue('nur_22', 'nur_21'), $ultrasonic->getMtvalue('nur_26', 'nur_21'), $ultrasonic->getMtvalue('nur_30', 'nur_21'), $ultrasonic->getMtvalue('nur_34', 'nur_21'), $ultrasonic->getMtvalue('nur_38', 'nur_21'), $ultrasonic->getMtvalue('nur_42', 'nur_21')])),
            '60: '.implode(', ', array_filter([$ultrasonic->getMtvalue('nur_23', 'nur_21'), $ultrasonic->getMtvalue('nur_27', 'nur_21'), $ultrasonic->getMtvalue('nur_31', 'nur_21'), $ultrasonic->getMtvalue('nur_35', 'nur_21'), $ultrasonic->getMtvalue('nur_39', 'nur_21'), $ultrasonic->getMtvalue('nur_43', 'nur_21')])),
            '70: '.implode(', ', array_filter([$ultrasonic->getMtvalue('nur_24', 'nur_21'), $ultrasonic->getMtvalue('nur_28', 'nur_21'), $ultrasonic->getMtvalue('nur_32', 'nur_21'), $ultrasonic->getMtvalue('nur_36', 'nur_21'), $ultrasonic->getMtvalue('nur_40', 'nur_21'), $ultrasonic->getMtvalue('nur_44', 'nur_21')])),
        ]))), 3);
        $row = $this->questionRow($sheet, $row, 'Calibration Sheet Attached?', (string) $ultrasonic->nur_22);
        $row = $this->singleWideRow($sheet, $row, 'Comment', (string) $ultrasonic->nur_23, 2);

        $row = $this->sectionTitle($sheet, $row, 'Inspection Image');
        $this->mergeText($sheet, 'A'.$row.':F'.($row + 6), '');
        $this->outline('A'.$row.':F'.($row + 6), $sheet);
        $this->addStorageImage($sheet, trim((string) $ultrasonic->nur_24) !== '' ? 'camera/inspection/ndt/ultrasonic/'.trim((string) $ultrasonic->nur_24) : null, 'A'.$row, 150, 10, 8);
        for ($i = 0; $i <= 6; $i++) {
            $sheet->getRowDimension($row + $i)->setRowHeight(24);
        }
        $row += 7;

        $row = $this->tripleValueRow($sheet, $row, [
            ['Description', (string) $ultrasonic->desc],
            ['Identification No', (string) $ultrasonic->nur_26],
            ['Welding Process', (string) $ultrasonic->nur_27],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Material', (string) $ultrasonic->nur_28],
            ['Material Thickness', (string) $ultrasonic->nur_29],
            ['Surface Condition', (string) $ultrasonic->nur_30],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Surface Temperature', (string) $ultrasonic->nur_31],
            ['Light Intensity', (string) $ultrasonic->nur_32],
            ['Final Result', str_contains((string) $ultrasonic->nur_34, '_y') ? 'Accept' : (str_contains((string) $ultrasonic->nur_34, '_n') ? 'Reject' : '')],
        ]);

        $row = $this->sectionTitle($sheet, $row, 'Results Table');
        $headers = ['Drawing No', 'Item', 'Joint No', 'Welder No', 'Tested Length', 'Evaluation / Result / Remarks'];
        foreach ($headers as $index => $header) {
            $column = chr(ord('A') + $index);
            $sheet->setCellValue($column.$row, $header);
        }
        $this->headerStyle($sheet, 'A'.$row.':F'.$row);
        $row++;

        $results = json_decode((string) $ultrasonic->nur_33);
        if (!is_array($results) && !$results instanceof \Traversable) {
            $results = [];
        }

        foreach ((array) $results as $value) {
            $sheet->setCellValue('A'.$row, (string) data_get($value, 'nur_56'));
            $sheet->setCellValue('B'.$row, (string) data_get($value, 'nur_57'));
            $sheet->setCellValue('C'.$row, (string) data_get($value, 'nur_58'));
            $sheet->setCellValue('D'.$row, (string) data_get($value, 'nur_59'));
            $sheet->setCellValue('E'.$row, (string) data_get($value, 'nur_60'));
            $sheet->setCellValue('F'.$row, trim(implode(' | ', array_filter([
                (string) data_get($value, 'nur_61'),
                (string) data_get($value, 'nur_62'),
                (string) data_get($value, 'nur_63'),
            ]))));
            $this->outline('A'.$row.':F'.$row, $sheet);
            $sheet->getStyle('A'.$row.':F'.$row)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
            $row++;
        }

        if (empty((array) $results)) {
            $row = $this->singleWideRow($sheet, $row, 'Results', 'No result rows attached.', 2);
        }

        $this->footerBlock(
            $sheet,
            $row,
            $report,
            $approvedUser,
            url('storage/pdf/inspection/ndt/ultrasonic/'.optional($report->job_request)->code.'/'.optional($report->reportable)->code.'.pdf'),
            data_get($footerValues, 'form_no'),
            data_get($footerValues, 'issue_no'),
            data_get($footerValues, 'issue_date'),
            data_get($footerValues, 'revision_no'),
            data_get($footerValues, 'revision_date')
        );
    }

    private function ultrasonicSpecificationText(Ultrasonic $ultrasonic): string
    {
        $decoded = json_decode((string) ($ultrasonic->nur_8 ?? '[]'), true);
        if (!is_array($decoded)) {
            return '';
        }

        return implode(', ', array_filter(array_map(function ($value) {
            $value = str_replace('-', ' ', (string) $value);
            return ucwords($value);
        }, $decoded)));
    }
}
