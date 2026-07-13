<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Lifting\Crane;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CraneReportWorksheetRenderer
{
    public function render(Worksheet $sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        /** @var Crane|null $crane */
        $crane = $report->reportable;
        if (!$crane instanceof Crane) {
            return;
        }

        $crane->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'crane2');
        $report->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'user.employee');

        $jobRequest = $report->job_request;
        $clientName = optional($jobRequest->client)->name ?? optional($jobRequest->supplier)->name ?? '';
        $clientLocation = optional($jobRequest->client)->location ?? optional($jobRequest->supplier)->location ?? '';
        $departmentName = optional($jobRequest->clientDepartment)->name ?? '';
        $footerValues = $crane->footer_values;
        $approvedUser = !empty($report->user_id_approved) ? User::query()->with('employee')->find($report->user_id_approved) : null;

        $sheet->setTitle('REV '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));
        $sheet->setShowGridlines(false);
        $sheet->freezePane('A6');
        $sheet->getDefaultRowDimension()->setRowHeight(22);
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageMargins()->setTop(0.2);
        $sheet->getPageMargins()->setRight(0.2);
        $sheet->getPageMargins()->setBottom(0.2);
        $sheet->getPageMargins()->setLeft(0.2);
        $sheet->getPageMargins()->setHeader(0.1);
        $sheet->getPageMargins()->setFooter(0.1);
        $sheet->getPageSetup()->setHorizontalCentered(true);

        $this->configureColumns($sheet);
        $this->addHeaderImages($sheet, $report);

        $row = 1;
        $this->mergeText($sheet, 'B'.$row.':E'.$row, 'Through Examination And / Or Test Certificate Of Cranes', 16, true, Alignment::HORIZONTAL_CENTER);
        $row++;
        $this->mergeText($sheet, 'B'.$row.':E'.$row, 'This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998', 10, false, Alignment::HORIZONTAL_CENTER);
        $this->mergeText(
            $sheet,
            'F1:F3',
            "Head Office: Block# 3053 | Hamdy Ramadan street\n2nd Floor #2 | El-Mearag City | Maadi | Cairo | Egypt\n+20 2 24477058 | +20 1032703368\nrse@rigsolutionz.com\nwww.rigsolutionz.com",
            8,
            false,
            Alignment::HORIZONTAL_RIGHT
        );
        $sheet->getStyle('F1:F3')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        $sheet->getRowDimension(1)->setRowHeight(26);
        $sheet->getRowDimension(2)->setRowHeight(22);
        $sheet->getRowDimension(3)->setRowHeight(36);
        $row += 3;

        $row = $this->labeledValueRow($sheet, $row, 'Name of employer for whom the examination was made', $clientName, 'Address of premises at examination was made', $this->sanitizeText((string) $clientLocation));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Purchase Order', (string) ($jobRequest->purchase_order ?: $crane->lcr_2)],
            ['JCF Number', (string) ($jobRequest->code ?? '')],
            ['Report No', trim(((string) ($jobRequest->code ?? '')).' / '.preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $crane->code), ' /').' - REV: '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT)],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Examination Date', (string) $crane->lcr_6],
            ['Next Exa. Date', (string) $crane->lcr_7],
            ['Color Code', (string) $crane->lcr_8],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Work location', trim($departmentName !== '' ? $departmentName.' / '.(string) $jobRequest->deploc : (string) $jobRequest->deploc));

        $row = $this->tripleValueRow($sheet, $row, [
            ['Type of Crane and nature of Power', (string) $crane->lcr_10],
            ['Name of Manufacturer', (string) $crane->lcr_11],
            ['Identification Number / chassis number', (string) $crane->lcr_12],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Model / Type', (string) $crane->lcr_13],
            ['Date of Manufacturer', (string) $crane->lcr_14],
            ['Crane Capacity (SWL)', (string) $crane->lcr_15],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Automatic safe load indicator', (string) $crane->lcr_16],
            ['SLI Identification No', (string) $crane->lcr_17],
            ['Number of falls / lines', (string) $crane->lcr_18],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Date of last Through Examination', (string) $crane->lcr_19],
            ['Certificate Number', (string) $crane->lcr_20],
            ['Examined by', (string) $crane->lcr_21],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Date of last Load Test', (string) $crane->lcr_22],
            ['Certificate Number', (string) $crane->lcr_23],
            ['Tested by', (string) $crane->lcr_24],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Reference Standard', (string) $crane->lcr_25);

        $row = $this->sectionTitle($sheet, $row, 'Examination Questions');
        $row = $this->questionRow($sheet, $row, 'First examination after installation or assembly at a new site?', (string) $crane->lcr_26);
        $row = $this->questionRow($sheet, $row, 'If yes, has the equipment been installed correctly?', (string) $crane->lcr_27);
        $row = $this->questionRow($sheet, $row, 'Within an interval of 6 months?', (string) $crane->lcr_28);
        $row = $this->questionRow($sheet, $row, 'Within an interval of 12 months?', (string) $crane->lcr_29);
        $row = $this->questionRow($sheet, $row, 'In accordance with an examination scheme?', (string) $crane->lcr_30);
        $row = $this->questionRow($sheet, $row, 'After exceptional circumstances?', (string) $crane->lcr_31);

        $row = $this->singleWideRow($sheet, $row, 'Identification of defect', (string) $crane->lcr_32, 2);
        $row = $this->questionRow($sheet, $row, 'Is the defect of immediate danger to persons?', (string) $crane->lcr_33);
        $row = $this->questionRow($sheet, $row, 'Could the defect become a danger to persons?', (string) $crane->lcr_34);
        $row = $this->singleWideRow($sheet, $row, 'Date by when action is required', (string) $crane->lcr_35);
        $row = $this->singleWideRow($sheet, $row, 'Repair / renewal / alteration required', (string) $crane->lcr_36, 2);
        $row = $this->singleWideRow($sheet, $row, 'Tests carried out as part of the examination', (string) $crane->lcr_37, 2);

        $row = $this->sectionTitle($sheet, $row, 'Load Test Details');
        $row = $this->loadTestTable($sheet, $row, $crane);
        $row = $this->questionRow($sheet, $row, 'Is this equipment safe to operate?', (string) $crane->lcr_46);

        if ($crane->crane2) {
            $row += 1;
            $row = $this->sectionTitle($sheet, $row, 'Second Page Details');
            $row = $this->renderSecondPage($sheet, $row, $crane);
        }

        $row += 1;
        $row = $this->footerBlock($sheet, $row, $report, $approvedUser, $footerValues?->form_no, $footerValues?->issue_no, $footerValues?->issue_date, $footerValues?->revision_no, $footerValues?->revision_date);

    }

    private function configureColumns(Worksheet $sheet): void
    {
        $widths = ['A' => 24, 'B' => 18, 'C' => 18, 'D' => 14, 'E' => 18, 'F' => 22];
        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    }

    private function addHeaderImages(Worksheet $sheet, InspectionReport $report): void
    {
        $reportable = $report->reportable;
        $logoPath = public_path('app-assets/images/logo/combined-logo.png');
        if (method_exists($reportable, 'getInspectionLogoPathAttribute') || isset($reportable->inspection_logo)) {
            $customLogo = trim((string) data_get($reportable, 'inspection_logo'));
            if ($customLogo !== '') {
                $resolved = storage_path('app/public/'.$customLogo);
                if (is_file($resolved)) {
                    $logoPath = $resolved;
                }
            }
        }

        if (is_file($logoPath)) {
            $drawing = new Drawing();
            $drawing->setPath($logoPath);
            $drawing->setHeight(75);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
        }
    }

    private function footerBlock(Worksheet $sheet, int $row, InspectionReport $report, ?User $approvedUser, ?string $formNo, ?string $issueNo, ?string $issueDate, ?string $revisionNo, ?string $revisionDate): int
    {
        $makerName = optional(optional($report->user)->employee)->name ?: '';
        $makerDesc = optional(optional($report->user)->employee)->desc ?: '';
        $makerSign = trim((string) optional(optional($report->user)->employee)->esign);
        $approverName = optional($approvedUser?->employee)->name ?: '';
        $approverSign = trim((string) optional($approvedUser?->employee)->esign);
        $qrValue = url('storage/pdf/inspection/lifting/crane/'.optional($report->job_request)->code.'/'.optional($report->reportable)->code.'.pdf');

        $this->mergeText($sheet, 'A'.$row.':F'.$row, 'This inspection was carried out in compliance with ISO/IEC 17020 & ILAC P15 Rig Solutions confirms that all information obtained or created during its inspection activities is kept confidential by all personnel acting on its behalf', 10, false);
        $this->outline('A'.$row.':F'.$row, $sheet);
        $sheet->getStyle('A'.$row.':F'.$row)->getAlignment()->setWrapText(true);
        $row++;

        $this->mergeText($sheet, 'A'.$row.':B'.$row, 'Company Appointed Examiner', 10, true, Alignment::HORIZONTAL_CENTER, true);
        $this->mergeText($sheet, 'C'.$row.':C'.$row, 'Stamp', 10, true, Alignment::HORIZONTAL_CENTER, true);
        $this->mergeText($sheet, 'D'.$row.':D'.$row, 'QR Code', 10, true, Alignment::HORIZONTAL_CENTER, true);
        $this->mergeText($sheet, 'E'.$row.':F'.$row, 'Person authenticating this report', 10, true, Alignment::HORIZONTAL_CENTER, true);
        $this->outline('A'.$row.':F'.$row, $sheet);
        $row++;

        $startDataRow = $row;
        $this->mergeText($sheet, 'A'.$row.':B'.$row, 'Name: '.$makerName);
        $this->mergeText($sheet, 'E'.$row.':F'.$row, $approverName !== '' ? 'Name: '.$approverName : 'Waiting for approval');
        $row++;
        $this->mergeText($sheet, 'A'.$row.':B'.$row, 'Qualification: '.$makerDesc);
        $this->mergeText($sheet, 'E'.$row.':F'.$row, $approvedUser ? 'Status: Approved' : 'Status: Pending');
        $row++;
        $this->mergeText($sheet, 'A'.$row.':B'.$row, 'Date: '.optional($report->created_at)->format('d/m/Y'));
        $this->mergeText($sheet, 'E'.$row.':F'.$row, !empty($report->user_id_approved) ? 'Approved By User #'.$report->user_id_approved : '');
        $row++;
        $this->mergeText($sheet, 'A'.$row.':B'.$row, 'Signature');
        $this->mergeText($sheet, 'E'.$row.':F'.$row, 'Signature');
        $row++;

        $endDataRow = $row;
        $this->outline('A'.$startDataRow.':B'.$endDataRow, $sheet);
        $this->outline('C'.$startDataRow.':C'.$endDataRow, $sheet);
        $this->outline('D'.$startDataRow.':D'.$endDataRow, $sheet);
        $this->outline('E'.$startDataRow.':F'.$endDataRow, $sheet);
        $sheet->getRowDimension($startDataRow)->setRowHeight(26);
        $sheet->getRowDimension($startDataRow + 1)->setRowHeight(26);
        $sheet->getRowDimension($startDataRow + 2)->setRowHeight(26);
        $sheet->getRowDimension($startDataRow + 3)->setRowHeight(42);

        if ($makerSign !== '') {
            $this->addEmployeeSignature($sheet, 'A'.$row, $makerSign);
        }
        if ($approverSign !== '') {
            $this->addEmployeeSignature($sheet, 'E'.$row, $approverSign);
        }
        if ($approvedUser) {
            $this->addStampImage($sheet, 'C'.($startDataRow + 1));
            $this->addQrImage($sheet, 'D'.($startDataRow + 1), $qrValue);
        }

        $row += 3;
        $row = $this->metadataStrip($sheet, $row, [
            'Form No' => (string) ($formNo ?? ''),
            'Issue No' => (string) ($issueNo ?? ''),
            'Issue Date' => (string) ($issueDate ?? ''),
            'Revision No' => (string) ($revisionNo ?? ''),
            'Revision Date' => (string) ($revisionDate ?? ''),
            'Page No.' => '1 of 1',
        ]);
        $row = $this->addFooterBrandStrip($sheet, $row);

        return $row;
    }

    private function renderSecondPage(Worksheet $sheet, int $row, Crane $crane): int
    {
        $crane2 = $crane->crane2;
        if (!$crane2) {
            return $row;
        }

        $components = [
            ['Main Wire Rope', $crane2->lcr2_1, $crane2->lcr2_4, $crane2->lcr2_2, $crane2->lcr2_3, $crane2->lcr2_5, $crane2->lcr2_6, $crane2->lcr2_7, $crane2->lcr2_8],
            ['Main Block / Hook', $crane2->lcr2_9, $crane2->lcr2_12, $crane2->lcr2_10, $crane2->lcr2_11, $crane2->lcr2_13, $crane2->lcr2_14, $crane2->lcr2_15, $crane2->lcr2_16],
            ['Auxiliary Wire Rope', $crane2->lcr2_17, $crane2->lcr2_20, $crane2->lcr2_18, $crane2->lcr2_19, $crane2->lcr2_21, $crane2->lcr2_22, $crane2->lcr2_23, $crane2->lcr2_24],
            ['Auxiliary Block / Hook', $crane2->lcr2_25, $crane2->lcr2_28, $crane2->lcr2_26, $crane2->lcr2_27, $crane2->lcr2_29, $crane2->lcr2_30, $crane2->lcr2_31, $crane2->lcr2_32],
        ];

        foreach ($components as $component) {
            $row = $this->sectionTitle($sheet, $row, $component[0]);
            $row = $this->labeledValueRow($sheet, $row, 'ID Number', (string) $component[1], 'Description', (string) $component[2]);
            $row = $this->labeledValueRow($sheet, $row, 'Safe Working Load', (string) $component[3], 'Condition', (string) $component[4]);
            $row = $this->tripleValueRow($sheet, $row, [
                ['Certifying Body', (string) $component[5]],
                ['Certificate Number', (string) $component[6]],
                ['Date of Test', (string) $component[7]],
            ]);
            $row = $this->singleWideRow($sheet, $row, 'Defects / Notes', (string) $component[8]);
        }

        $row = $this->sectionTitle($sheet, $row, 'MPI Details');
        $row = $this->tripleValueRow($sheet, $row, [
            ['Standard', (string) $crane2->lcr2_33],
            ['Equipment Type', (string) $crane2->lcr2_34],
            ['Equipment No', (string) $crane2->lcr2_35],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Pole spacing', (string) $crane2->lcr2_36],
            ['Due Date', (string) $crane2->lcr2_37],
            ['Contrast / Indicator', trim((string) $crane2->lcr2_38.' / '.(string) $crane2->lcr2_39, ' /')],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Expire Dates', trim((string) $crane2->lcr2_40.' / '.(string) $crane2->lcr2_41, ' /'));
        $row = $this->singleWideRow($sheet, $row, 'Final Conclusion', (string) html_entity_decode((string) $crane2->lcr2_42), 3);
        $row = $this->singleWideRow($sheet, $row, 'Guide Instructions', (string) html_entity_decode((string) $crane2->lcr2_43), 3);

        return $row;
    }

    private function loadTestTable(Worksheet $sheet, int $row, Crane $crane): int
    {
        $headers = ['Note', 'Jib Length', 'Radius', 'Proof Load', 'SWL', 'Remarks'];
        foreach ($headers as $index => $header) {
            $cell = chr(ord('A') + $index).$row;
            $sheet->setCellValue($cell, $header);
        }
        $this->headerStyle($sheet, 'A'.$row.':F'.$row);
        $row++;

        $testRows = json_decode((string) $crane->lcr_38, true);
        if (!is_array($testRows) || empty($testRows)) {
            $testRows = [[]];
        }

        foreach ($testRows as $testRow) {
            $sheet->setCellValue('A'.$row, 'Variable load details');
            $sheet->setCellValue('B'.$row, (string) ($testRow['lcr_38'] ?? ''));
            $sheet->setCellValue('C'.$row, (string) ($testRow['lcr_39'] ?? ''));
            $sheet->setCellValue('D'.$row, (string) ($testRow['lcr_40'] ?? ''));
            $sheet->setCellValue('E'.$row, (string) ($testRow['lcr_41'] ?? ''));
            $sheet->setCellValue('F'.$row, '');
            $this->outline('A'.$row.':F'.$row, $sheet);
            $row++;
        }

        $sheet->setCellValue('A'.$row, 'Max radius / calculated load details');
        $sheet->setCellValue('B'.$row, (string) $crane->lcr_42);
        $sheet->setCellValue('C'.$row, (string) $crane->lcr_43);
        $sheet->setCellValue('D'.$row, (string) $crane->lcr_44);
        $sheet->setCellValue('E'.$row, (string) $crane->lcr_45);
        $sheet->setCellValue('F'.$row, '');
        $this->outline('A'.$row.':F'.$row, $sheet);

        return $row + 1;
    }

    private function sectionTitle(Worksheet $sheet, int $row, string $title): int
    {
        $this->mergeText($sheet, 'A'.$row.':F'.$row, $title, 11, true, Alignment::HORIZONTAL_LEFT, true);
        $this->outline('A'.$row.':F'.$row, $sheet);

        return $row + 1;
    }

    private function singleWideRow(Worksheet $sheet, int $row, string $label, string $value, int $valueHeightRows = 1): int
    {
        $this->mergeText($sheet, 'A'.$row.':B'.$row, $label, 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'C'.$row.':F'.$row, $value);
        $this->outline('A'.$row.':F'.$row, $sheet);

        if ($valueHeightRows > 1) {
            for ($i = 1; $i < $valueHeightRows; $i++) {
                $row++;
                $this->mergeText($sheet, 'C'.$row.':F'.$row, '');
                $this->outline('C'.$row.':F'.$row, $sheet);
            }
        }

        return $row + 1;
    }

    private function labeledValueRow(Worksheet $sheet, int $row, string $leftLabel, string $leftValue, string $rightLabel, string $rightValue): int
    {
        $this->mergeText($sheet, 'A'.$row.':B'.$row, $leftLabel, 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'C'.$row.':C'.$row, $leftValue);
        $this->mergeText($sheet, 'D'.$row.':E'.$row, $rightLabel, 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'F'.$row.':F'.$row, $rightValue);
        $this->outline('A'.$row.':F'.$row, $sheet);

        return $row + 1;
    }

    private function tripleValueRow(Worksheet $sheet, int $row, array $pairs): int
    {
        $columns = [
            ['A', 'B'],
            ['C', 'D'],
            ['E', 'F'],
        ];

        foreach ($pairs as $index => $pair) {
            [$start, $end] = $columns[$index];
            $label = (string) ($pair[0] ?? '');
            $value = (string) ($pair[1] ?? '');
            $this->mergeText($sheet, $start.$row.':'.$end.$row, $label, 10, true, Alignment::HORIZONTAL_LEFT, true);
            $row2 = $row + 1;
            $this->mergeText($sheet, $start.$row2.':'.$end.$row2, $value);
        }

        $this->outline('A'.$row.':F'.($row + 1), $sheet);

        return $row + 2;
    }

    private function questionRow(Worksheet $sheet, int $row, string $question, string $value): int
    {
        $this->mergeText($sheet, 'A'.$row.':D'.$row, $question);
        $sheet->setCellValue('E'.$row, $this->yesNoMark($value, true));
        $sheet->setCellValue('F'.$row, $this->yesNoMark($value, false));
        $this->headerStyle($sheet, 'E'.$row.':F'.$row, 'YES / NO');
        $this->outline('A'.$row.':F'.$row, $sheet);
        $sheet->getStyle('E'.$row.':F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return $row + 1;
    }

    private function yesNoMark(string $value, bool $yes): string
    {
        $normalized = strtolower(trim($value));

        if ($normalized === '') {
            return $yes ? 'Yes' : 'No';
        }

        if ($yes) {
            return str_contains($normalized, '_y') ? 'YES: X' : 'YES';
        }

        return str_contains($normalized, '_n') ? 'NO: X' : 'NO';
    }

    private function mergeText(Worksheet $sheet, string $range, string $text, int $size = 10, bool $bold = false, string $horizontal = Alignment::HORIZONTAL_LEFT, bool $fillLabel = false): void
    {
        $sheet->mergeCells($range);
        $cell = explode(':', $range)[0];
        $sheet->setCellValue($cell, $text);
        $style = $sheet->getStyle($range);
        $style->getFont()->setBold($bold)->setSize($size);
        $style->getAlignment()->setHorizontal($horizontal)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);

        if ($fillLabel) {
            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');
        }
    }

    private function headerStyle(Worksheet $sheet, string $range, ?string $defaultText = null): void
    {
        if ($defaultText !== null) {
            foreach (explode(':', $range) as $cell) {
                if ($sheet->getCell($cell)->getValue() === null) {
                    $sheet->setCellValue($cell, $defaultText);
                }
            }
        }

        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9D9D9'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '333333'],
                ],
            ],
        ]);
    }

    private function outline(string $range, Worksheet $sheet): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '333333'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);
    }

    private function addEmployeeSignature(Worksheet $sheet, string $coordinates, string $filename): void
    {
        $path = Storage::disk('public')->path('employees/'.$filename);
        if (!is_file($path)) {
            return;
        }

        $drawing = new Drawing();
        $drawing->setPath($path);
        $drawing->setHeight(36);
        $drawing->setCoordinates($coordinates);
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);
        $drawing->setWorksheet($sheet);
    }

    private function addStampImage(Worksheet $sheet, string $coordinates): void
    {
        $path = public_path('app-assets/images/logo/stamp.jpeg');
        if (!is_file($path)) {
            return;
        }

        $drawing = new Drawing();
        $drawing->setPath($path);
        $drawing->setHeight(92);
        $drawing->setCoordinates($coordinates);
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(8);
        $drawing->setWorksheet($sheet);
    }

    private function addQrImage(Worksheet $sheet, string $coordinates, string $value): void
    {
        $value = trim($value);
        if ($value === '') {
            return;
        }

        $barcode = new \Milon\Barcode\DNS2D();
        $base64 = $barcode->getBarcodePNG($value, 'QRCODE');
        $tempPath = tempnam(sys_get_temp_dir(), 'crane_qr_');
        if ($tempPath === false) {
            return;
        }

        $pngPath = $tempPath.'.png';
        @rename($tempPath, $pngPath);
        if (@file_put_contents($pngPath, base64_decode($base64, true) ?: '') === false) {
            @unlink($pngPath);
            return;
        }

        register_shutdown_function(static function () use ($pngPath) {
            @unlink($pngPath);
        });

        $drawing = new Drawing();
        $drawing->setPath($pngPath);
        $drawing->setHeight(78);
        $drawing->setCoordinates($coordinates);
        $drawing->setOffsetX(6);
        $drawing->setOffsetY(10);
        $drawing->setWorksheet($sheet);
    }

    private function metadataStrip(Worksheet $sheet, int $row, array $pairs): int
    {
        $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
        $index = 0;
        foreach ($pairs as $label => $value) {
            $labelColumn = $columns[$index];
            $valueColumn = $columns[$index + 1];
            $this->mergeText($sheet, $labelColumn.$row.':'.$labelColumn.$row, $label, 9, true, Alignment::HORIZONTAL_LEFT, true);
            $this->mergeText($sheet, $valueColumn.$row.':'.$valueColumn.$row, $value, 9);
            $index += 2;
            if ($index >= count($columns)) {
                $this->outline('A'.$row.':F'.$row, $sheet);
                $row++;
                $index = 0;
            }
        }

        if ($index !== 0) {
            $this->outline('A'.$row.':F'.$row, $sheet);
            $row++;
        }

        return $row;
    }

    private function addFooterBrandStrip(Worksheet $sheet, int $row): int
    {
        $path = public_path('app-assets/images/footer-v2.png');
        if (!is_file($path)) {
            return $row;
        }

        $sheet->mergeCells('B'.$row.':E'.$row);
        $sheet->getRowDimension($row)->setRowHeight(44);

        $drawing = new Drawing();
        $drawing->setPath($path);
        $drawing->setHeight(38);
        $drawing->setCoordinates('B'.$row);
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(3);
        $drawing->setWorksheet($sheet);

        return $row + 1;
    }

    private function sanitizeText(string $value): string
    {
        return preg_replace('~[\\\\/:*?"<>\\[\\]|]~', '', $value) ?: $value;
    }
}
