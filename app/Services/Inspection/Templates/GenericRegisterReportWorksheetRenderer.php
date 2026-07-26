<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Lifting\Defect;
use App\Models\Inspection\Lifting\Lregister;
use App\Models\Inspection\Ndt\Nregister;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericRegisterReportWorksheetRenderer extends AbstractGenericInspectionWorksheetRenderer
{
    public function render(Worksheet $sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        $model = $report->reportable;
        if (!$model instanceof Model) {
            return;
        }

        if ($model instanceof Lregister) {
            $this->renderLregister($sheet, $report, $model, $revisionNumber);
            return;
        }

        if ($model instanceof Nregister) {
            $this->renderNregister($sheet, $report, $model, $revisionNumber);
            return;
        }

        $config = $this->configFor($model);
        if ($config === null) {
            return;
        }

        $this->renderGenericModel(
            $sheet,
            $report,
            $model,
            $revisionNumber,
            $config['title'],
            $config['subtitle'],
            $config['pdf_directory'],
            $config['primary_fields'],
            $config['description_field'],
            $config['skip_fields']
        );
    }

    protected function renderLregister(
        Worksheet $sheet,
        InspectionReport $report,
        Lregister $model,
        int $revisionNumber
    ): void {
        $model->loadMissing('job_request.client', 'job_request.supplier');
        $report->loadMissing('job_request.client', 'job_request.supplier');

        $job = $model->job_request;
        $clientName = optional($job?->client)->name ?: optional($job?->supplier)->name ?: 'N/A';
        $registerNo = (optional($job)->code ?? '') . ' / ' . ($model->code ?? '');
        $rigLocation = (string) (optional($job)->deploc ?? '');
        $colorCode = (string) ($model->color_code ?? '');
        $date = (string) ($model->date ?? '');

        // Boot sheet in Landscape
        $this->bootSheet($sheet, 'REV ' . str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));

        // 12 columns configuration
        $this->configureColumns($sheet, [
            'A' => 16, // ID
            'B' => 30, // Equipment Description
            'C' => 12, // SWL
            'D' => 10, // Gross
            'E' => 16, // Cert. No
            'F' => 14, // Test Date
            'G' => 16, // Tested By
            'H' => 18, // Report #
            'I' => 14, // Exam. date
            'J' => 14, // Due Date
            'K' => 20, // Attached or location
            'L' => 10, // Accept
        ]);

        $this->addHeaderImages($sheet, $report);

        // Row 1 & 2: Header Title
        $sheet->mergeCells('C1:L2');
        $sheet->setCellValue('C1', "Register of Lifting Appliances and\nLifting Accessories");
        $sheet->getStyle('C1:L2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 15, 'color' => ['rgb' => '000000']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // Row 3: Metadata Row 1
        $this->mergeText($sheet, 'A3:B3', 'Client Name', 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'C3:F3', $clientName, 10, true, Alignment::HORIZONTAL_LEFT);
        $this->mergeText($sheet, 'G3:H3', 'Register No.', 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'I3:L3', $registerNo, 10, true, Alignment::HORIZONTAL_LEFT);
        $this->outline('A3:L3', $sheet);
        $sheet->getRowDimension(3)->setRowHeight(24);

        // Row 4: Metadata Row 2
        $this->mergeText($sheet, 'A4:A4', 'Rig / Location', 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'B4:D4', $rigLocation, 10, true, Alignment::HORIZONTAL_LEFT);
        $this->mergeText($sheet, 'E4:E4', 'Color Code', 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'F4:H4', $colorCode, 10, true, Alignment::HORIZONTAL_LEFT);
        $this->mergeText($sheet, 'I4:I4', 'Date', 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'J4:L4', $date, 10, true, Alignment::HORIZONTAL_LEFT);
        $this->outline('A4:L4', $sheet);
        $sheet->getRowDimension(4)->setRowHeight(24);

        // Row 5 & 6: Main Table Headers
        $sheet->mergeCells('A5:A6');
        $sheet->setCellValue('A5', 'ID');

        $sheet->mergeCells('B5:B6');
        $sheet->setCellValue('B5', 'Equipment Description');

        $sheet->mergeCells('C5:C6');
        $sheet->setCellValue('C5', 'SWL');

        $sheet->mergeCells('D5:D6');
        $sheet->setCellValue('D5', 'Gross');

        $sheet->mergeCells('E5:G5');
        $sheet->setCellValue('E5', 'Manufacturer / Test Certificate Details');

        $sheet->mergeCells('H5:J5');
        $sheet->setCellValue('H5', 'Current Certificate Details');

        $sheet->mergeCells('K5:K6');
        $sheet->setCellValue('K5', 'Attached or location');

        $sheet->mergeCells('L5:L6');
        $sheet->setCellValue('L5', 'Accept');

        // Row 6 Sub-headers
        $sheet->setCellValue('E6', 'Cert. No');
        $sheet->setCellValue('F6', 'Test Date');
        $sheet->setCellValue('G6', 'Tested By');

        $sheet->setCellValue('H6', 'Report #');
        $sheet->setCellValue('I6', 'Exam. date');
        $sheet->setCellValue('J6', 'Due Date');

        $this->headerStyle($sheet, 'A5:L6');
        $sheet->getRowDimension(5)->setRowHeight(22);
        $sheet->getRowDimension(6)->setRowHeight(22);

        // Fetch Data Items
        $jobRequestId = $model->job_request_id;
        $items = InspectionReport::query()
            ->with(['reportable', 'job_request'])
            ->where('job_request_id', $jobRequestId)
            ->where('reportable_type', 'not like', '%Ndt%')
            ->where('code', 'not like', '%Duplicated%')
            ->where('reportable_type', '!=', 'App\Models\Inspection\Lifting\Defect')
            ->where('reportable_type', '!=', 'App\Models\Inspection\Lifting\Lregister')
            ->get();

        $currentRow = 7;
        foreach ($items as $item) {
            $rep = $item->reportable;
            if (!$rep) {
                continue;
            }

            // ID
            $id = trim(
                (string) data_get($rep, 'lcr_12') .
                (string) data_get($rep, 'locr_12') .
                (string) data_get($rep, 'lfr_12') .
                (string) data_get(data_get($rep, 'lter_10'), 'pop1')
            );

            // Description
            $desc = trim(
                (string) data_get($rep, 'lcr_10') .
                (string) data_get($rep, 'locr_10') .
                (string) data_get($rep, 'lfr_10') .
                (string) data_get(data_get($rep, 'lter_10'), 'pop20')
            );

            // SWL
            $swl = trim(
                (string) data_get($rep, 'lcr_15') .
                (string) data_get($rep, 'locr_15') .
                (string) data_get($rep, 'lfr_15') .
                (string) data_get(data_get($rep, 'lter_10'), 'pop4')
            );

            // Gross
            $gross = trim((string) data_get($rep, 'lter_19'));

            // Cert No
            $certNo = trim(
                (string) data_get($rep, 'lcr_23') .
                (string) data_get($rep, 'locr_23') .
                (string) data_get($rep, 'lfr_17') .
                (string) data_get($rep, 'lter_21')
            );

            // Test Date
            $testDate = trim(
                (string) data_get($rep, 'lcr_22') .
                (string) data_get($rep, 'locr_22') .
                (string) data_get($rep, 'lfr_16') .
                (string) data_get($rep, 'lter_22')
            );

            // Tested By
            $testedBy = trim(
                (string) data_get($rep, 'lcr_24') .
                (string) data_get($rep, 'locr_24') .
                (string) data_get($rep, 'lfr_18') .
                (string) data_get($rep, 'lter_20')
            );

            // Report #
            $reportNo = trim(optional($item->job_request)->code . '/' . data_get($rep, 'code'));

            // Exam Date
            $examDate = trim(
                (string) data_get($rep, 'lcr_6') .
                (string) data_get($rep, 'locr_6') .
                (string) data_get($rep, 'lfr_6') .
                (string) data_get($rep, 'lter_6')
            );

            // Due Date
            $dueDate = trim(
                (string) data_get($rep, 'lcr_7') .
                (string) data_get($rep, 'locr_7') .
                (string) data_get($rep, 'lfr_7') .
                (string) data_get($rep, 'lter_7')
            );

            // Attached or location
            $attachedLoc = trim((string) data_get($rep, 'lter_15'));

            // Accept
            $lcr46 = (string) data_get($rep, 'lcr_46');
            $locr44 = (string) data_get($rep, 'locr_44');
            $lfr33 = (string) data_get($rep, 'lfr_33');
            $lter51 = (string) data_get($rep, 'lter_51');
            $isAccept = str_contains($lcr46, '_y') || str_contains($locr44, '_y') || str_contains($lfr33, '_y') || str_contains($lter51, '_y');
            $accept = $isAccept ? 'YES' : 'NO';

            $sheet->setCellValue('A' . $currentRow, $id);
            $sheet->setCellValue('B' . $currentRow, $desc);
            $sheet->setCellValue('C' . $currentRow, $swl);
            $sheet->setCellValue('D' . $currentRow, $gross);
            $sheet->setCellValue('E' . $currentRow, $certNo);
            $sheet->setCellValue('F' . $currentRow, $testDate);
            $sheet->setCellValue('G' . $currentRow, $testedBy);
            $sheet->setCellValue('H' . $currentRow, $reportNo);
            $sheet->setCellValue('I' . $currentRow, $examDate);
            $sheet->setCellValue('J' . $currentRow, $dueDate);
            $sheet->setCellValue('K' . $currentRow, $attachedLoc);
            $sheet->setCellValue('L' . $currentRow, $accept);

            $this->outline('A' . $currentRow . ':L' . $currentRow, $sheet);
            $sheet->getStyle('A' . $currentRow . ':L' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getRowDimension($currentRow)->setRowHeight(22);

            $currentRow++;
        }

        // If no items, add an empty row
        if ($items->isEmpty()) {
            $this->outline('A' . $currentRow . ':L' . $currentRow, $sheet);
            $sheet->getRowDimension($currentRow)->setRowHeight(22);
            $currentRow++;
        }

        // Footer block / Metadata strip
        $footerValues = $model->footer_values;
        $row = $currentRow + 1;

        $formNo = data_get($footerValues, 'form_no', 'RSE-RF-06');
        $issueNo = data_get($footerValues, 'issue_no', '05');
        $issueDate = data_get($footerValues, 'issue_date', '1-Jan-2022');
        $revisionNo = data_get($footerValues, 'revision_no', '01');
        $revisionDate = data_get($footerValues, 'revision_date', '24-Mar-2024');

        $this->mergeText($sheet, 'A' . $row . ':B' . $row, 'Form No: ' . $formNo, 9, true, Alignment::HORIZONTAL_CENTER, true);
        $this->mergeText($sheet, 'C' . $row . ':D' . $row, 'Issue No: ' . $issueNo, 9, true, Alignment::HORIZONTAL_CENTER, true);
        $this->mergeText($sheet, 'E' . $row . ':F' . $row, 'Issue Date: ' . $issueDate, 9, true, Alignment::HORIZONTAL_CENTER, true);
        $this->mergeText($sheet, 'G' . $row . ':H' . $row, 'Revision No: ' . $revisionNo, 9, true, Alignment::HORIZONTAL_CENTER, true);
        $this->mergeText($sheet, 'I' . $row . ':J' . $row, 'Revision Date: ' . $revisionDate, 9, true, Alignment::HORIZONTAL_CENTER, true);
        $this->mergeText($sheet, 'K' . $row . ':L' . $row, 'Page No: 1 of 1', 9, true, Alignment::HORIZONTAL_CENTER, true);
        $this->outline('A' . $row . ':L' . $row, $sheet);
    }

    protected function renderNregister(
        Worksheet $sheet,
        InspectionReport $report,
        Nregister $model,
        int $revisionNumber
    ): void {
        $model->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment');
        $report->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment');

        $job = $model->job_request;
        $clientName = optional($job?->client)->name ?: optional($job?->supplier)->name ?: 'N/A';
        $registerNo = (optional($job)->code ?? '') . ' / ' . ($model->code ?? '');
        $workLocation = $job?->clientDepartment
            ? $job->clientDepartment->name . ' / ' . $job->deploc
            : (string) ($job?->deploc ?? '');
        $registerDate = (string) ($model->register_date ?? '');

        // Boot sheet in Landscape
        $this->bootSheet($sheet, 'REV ' . str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));

        // 6 columns configuration
        $this->configureColumns($sheet, [
            'A' => 22, // Identification No
            'B' => 48, // Description
            'C' => 18, // Accept Criteria
            'D' => 18, // Report No
            'E' => 18, // Examination Date
            'F' => 14, // Accept
        ]);

        $this->addHeaderImages($sheet, $report);

        // Header Title
        $sheet->mergeCells('C1:F2');
        $sheet->setCellValue('C1', "NDT Register Report");
        $sheet->getStyle('C1:F2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '000000']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // Metadata Block
        // Row 3: Client & Register Number
        $this->mergeText($sheet, 'A3:A3', 'Client', 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'B3:C3', $clientName, 10, false, Alignment::HORIZONTAL_LEFT);
        $this->mergeText($sheet, 'D3:D3', 'Register Number', 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'E3:F3', $registerNo, 10, false, Alignment::HORIZONTAL_LEFT);
        $this->outline('A3:F3', $sheet);
        $sheet->getRowDimension(3)->setRowHeight(24);

        // Row 4: Work location & Register Date
        $this->mergeText($sheet, 'A4:A4', 'Work location', 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'B4:C4', $workLocation, 10, false, Alignment::HORIZONTAL_LEFT);
        $this->mergeText($sheet, 'D4:D4', 'Register Date', 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'E4:F4', $registerDate, 10, false, Alignment::HORIZONTAL_LEFT);
        $this->outline('A4:F4', $sheet);
        $sheet->getRowDimension(4)->setRowHeight(24);

        // Main Table Headers (Row 5)
        $sheet->setCellValue('A5', 'Identification No');
        $sheet->setCellValue('B5', 'Description');
        $sheet->setCellValue('C5', 'Accept Criteria');
        $sheet->setCellValue('D5', 'Report No');
        $sheet->setCellValue('E5', 'Examination Date');
        $sheet->setCellValue('F5', 'Accept');

        $this->headerStyle($sheet, 'A5:F5');
        $sheet->getRowDimension(5)->setRowHeight(26);

        // Fetch Data Items
        $jobRequestId = $model->job_request_id;
        $items = \App\Models\Inspection\Ndt\Mpipt::query()
            ->with(['job_request'])
            ->where('job_request_id', $jobRequestId)
            ->where('code', 'not like', '%Duplicated%')
            ->get();

        $currentRow = 6;
        foreach ($items as $row) {
            $idNo = (string) ($row->nmpr_28 ?? '');
            $desc = (string) ($row->desc ?? '');
            $acceptCriteria = (string) ($row->acceptance ?? '');
            $reportNo = $row->job_request ? ($row->job_request->code . '/' . $row->code) : '';
            $examDate = (string) ($row->nmpr_6 ?? '');

            $isAccepted = isset($row->nmpr_30) && str_contains((string) $row->nmpr_30, '_y');
            $accept = isset($row->nmpr_30) ? ($isAccepted ? 'Accepted' : 'Rejected') : '';

            $sheet->setCellValue('A' . $currentRow, $idNo);
            $sheet->setCellValue('B' . $currentRow, $desc);
            $sheet->setCellValue('C' . $currentRow, $acceptCriteria);
            $sheet->setCellValue('D' . $currentRow, $reportNo);
            $sheet->setCellValue('E' . $currentRow, $examDate);
            $sheet->setCellValue('F' . $currentRow, $accept);

            $this->outline('A' . $currentRow . ':F' . $currentRow, $sheet);
            $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getRowDimension($currentRow)->setRowHeight(24);

            $currentRow++;
        }

        if ($items->isEmpty()) {
            $sheet->setCellValue('A' . $currentRow, '-');
            $sheet->setCellValue('B' . $currentRow, '-');
            $sheet->setCellValue('C' . $currentRow, '-');
            $sheet->setCellValue('D' . $currentRow, '-');
            $sheet->setCellValue('E' . $currentRow, '-');
            $sheet->setCellValue('F' . $currentRow, '-');
            $this->outline('A' . $currentRow . ':F' . $currentRow, $sheet);
            $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($currentRow)->setRowHeight(24);
            $currentRow++;
        }

        // Footer Block / Metadata strip
        $footerValues = $model->footer_values;
        $row = $currentRow + 1;

        $formNo = data_get($footerValues, 'form_no', 'RS-RF-F27');
        $issueNo = data_get($footerValues, 'issue_no', '01');
        $issueDate = data_get($footerValues, 'issue_date', '1-Sept-2024');
        $revisionNo = data_get($footerValues, 'revision_no', '00');
        $revisionDate = data_get($footerValues, 'revision_date', '1-Sept-2024');

        $row = $this->metadataStrip($sheet, $row, [
            'Form No' => (string) $formNo,
            'Issue No' => (string) $issueNo,
            'Issue Date' => (string) $issueDate,
            'Revision No' => (string) $revisionNo,
            'Revision Date' => (string) $revisionDate,
            'Page No.' => '1 of 1',
        ]);

        $this->addFooterBrandStrip($sheet, $row);
    }

    private function configFor(Model $model): ?array
    {
        return match ($model::class) {
            Defect::class => [
                'title' => 'Lifting Defect Report',
                'subtitle' => 'This report follows the approved lifting defect workflow and internal form',
                'pdf_directory' => 'pdf/inspection/lifting/defect',
                'primary_fields' => [
                    'ldr_6' => 'Examination Date',
                ],
                'description_field' => 'ldr_8',
                'skip_fields' => ['ldr_2'],
            ],
            Lregister::class => [
                'title' => 'Lifting Register',
                'subtitle' => 'This report follows the lifting register workflow and internal form',
                'pdf_directory' => 'pdf/inspection/lifting/lregister',
                'primary_fields' => [
                    'date' => 'Register Date',
                    'color_code' => 'Color Code',
                ],
                'description_field' => null,
                'skip_fields' => [],
            ],
            Nregister::class => [
                'title' => 'NDT Register',
                'subtitle' => 'This report follows the NDT register workflow and internal form',
                'pdf_directory' => 'pdf/inspection/ndt/nregister',
                'primary_fields' => [
                    'register_date' => 'Register Date',
                ],
                'description_field' => null,
                'skip_fields' => [],
            ],
            default => null,
        };
    }
}
