<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Ndt\DrawingInspection;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DrawingInspectionReportWorksheetRenderer extends AbstractLiftingReportWorksheetRenderer
{
    public function render(Worksheet $sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        /** @var DrawingInspection|null $drawingInspection */
        $drawingInspection = $report->reportable;
        if (!$drawingInspection instanceof DrawingInspection) {
            return;
        }

        $drawingInspection->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment');
        $report->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'user.employee');

        $approvedUser = !empty($report->user_id_approved) ? User::query()->with('employee')->find($report->user_id_approved) : null;

        $this->bootSheet($sheet, 'REV '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));
        $this->configureColumns($sheet);
        $this->addHeaderImages($sheet, $report);

        $row = $this->renderStandardHeader(
            $sheet,
            'Drawing Inspection Report',
            'Drawing-based inspection worksheet exported from the approved report template'
        );

        $row = $this->tripleValueRow($sheet, $row, [
            ['Client Name', $this->clientName($report)],
            ['Work Location', $this->workLocation($report)],
            ['Drawing No', $this->reportNumberWithRevision($report, (string) $drawingInspection->code, $revisionNumber)],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Description', (string) $drawingInspection->description],
            ['Dimension', (string) $drawingInspection->dimension],
            ['Pressure', (string) $drawingInspection->pressure],
        ]);
        $row = $this->labeledValueRow($sheet, $row, 'Identification No', (string) $drawingInspection->identification_no, 'Date', (string) $drawingInspection->examination_date);

        $row = $this->sectionTitle($sheet, $row, 'Drawing Image');
        $this->mergeText($sheet, 'A'.$row.':F'.($row + 18), '');
        $this->outline('A'.$row.':F'.($row + 18), $sheet);
        $this->addStorageImage(
            $sheet,
            trim((string) $drawingInspection->report_image) !== '' ? 'camera/inspection/ndt/drawinginspection/'.trim((string) $drawingInspection->report_image) : null,
            'A'.$row,
            430,
            10,
            10
        );
        for ($i = 0; $i <= 18; $i++) {
            $sheet->getRowDimension($row + $i)->setRowHeight(24);
        }
        $row += 19;

        $preparedBy = (string) data_get($report, 'user.employee.name');
        $preparedSign = trim((string) data_get($report, 'user.employee.esign'));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Comments', (string) $drawingInspection->comment],
            ['Prepared By', $preparedBy],
            ['Prepared Sign', $preparedSign !== '' ? 'Attached' : ''],
        ]);
        if ($preparedSign !== '') {
            $this->addEmployeeSignature($sheet, 'E'.($row - 1), $preparedSign);
        }

        $this->footerBlock(
            $sheet,
            $row,
            $report,
            $approvedUser,
            url('storage/pdf/inspection/ndt/drawinginspection/'.optional($report->job_request)->code.'/'.optional($report->reportable)->code.'.pdf'),
            'RS-RF-F18',
            '04',
            '1-Mar-2024',
            '00',
            '1-Mar-2024'
        );
    }
}
