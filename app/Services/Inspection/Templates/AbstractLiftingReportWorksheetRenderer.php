<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class AbstractLiftingReportWorksheetRenderer
{
    protected function bootSheet(Worksheet $sheet, string $title): void
    {
        $sheet->setTitle($title);
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
    }

    protected function configureColumns(Worksheet $sheet, array $widths = ['A' => 24, 'B' => 18, 'C' => 18, 'D' => 14, 'E' => 18, 'F' => 22]): void
    {
        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    }

    protected function addHeaderImages(Worksheet $sheet, InspectionReport $report): void
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

        if (!is_file($logoPath)) {
            return;
        }

        $drawing = new Drawing();
        $drawing->setPath($logoPath);
        $drawing->setHeight(75);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
    }

    protected function addPublicImage(Worksheet $sheet, string $relativePath, string $coordinates, int $height, int $offsetX = 5, int $offsetY = 5): void
    {
        $path = public_path(trim($relativePath, '/'));
        if (!is_file($path)) {
            return;
        }

        $drawing = new Drawing();
        $drawing->setPath($path);
        $drawing->setHeight($height);
        $drawing->setCoordinates($coordinates);
        $drawing->setOffsetX($offsetX);
        $drawing->setOffsetY($offsetY);
        $drawing->setWorksheet($sheet);
    }

    protected function addStorageImage(Worksheet $sheet, ?string $relativePath, string $coordinates, int $height, int $offsetX = 5, int $offsetY = 5): void
    {
        $relativePath = trim((string) $relativePath);
        if ($relativePath === '' || !Storage::disk('public')->exists($relativePath)) {
            return;
        }

        $path = Storage::disk('public')->path($relativePath);
        if (!is_file($path)) {
            return;
        }

        $drawing = new Drawing();
        $drawing->setPath($path);
        $drawing->setHeight($height);
        $drawing->setCoordinates($coordinates);
        $drawing->setOffsetX($offsetX);
        $drawing->setOffsetY($offsetY);
        $drawing->setWorksheet($sheet);
    }

    protected function renderStandardHeader(Worksheet $sheet, string $title, string $subtitle): int
    {
        $row = 1;
        $this->mergeText($sheet, 'B'.$row.':E'.$row, $title, 16, true, Alignment::HORIZONTAL_CENTER);
        $row++;
        $this->mergeText($sheet, 'B'.$row.':E'.$row, $subtitle, 10, false, Alignment::HORIZONTAL_CENTER);
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

        return 4;
    }

    protected function footerBlock(
        Worksheet $sheet,
        int $row,
        InspectionReport $report,
        ?User $approvedUser,
        string $qrValue,
        ?string $formNo,
        ?string $issueNo,
        ?string $issueDate,
        ?string $revisionNo,
        ?string $revisionDate
    ): int {
        $makerName = optional(optional($report->user)->employee)->name ?: '';
        $makerDesc = optional(optional($report->user)->employee)->desc ?: '';
        $makerSign = trim((string) optional(optional($report->user)->employee)->esign);
        $approverName = optional($approvedUser?->employee)->name ?: '';
        $approverSign = trim((string) optional($approvedUser?->employee)->esign);

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

        return $this->addFooterBrandStrip($sheet, $row);
    }

    protected function sectionTitle(Worksheet $sheet, int $row, string $title): int
    {
        $this->mergeText($sheet, 'A'.$row.':F'.$row, $title, 11, true, Alignment::HORIZONTAL_LEFT, true);
        $this->outline('A'.$row.':F'.$row, $sheet);

        return $row + 1;
    }

    protected function singleWideRow(Worksheet $sheet, int $row, string $label, string $value, int $valueHeightRows = 1): int
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

    protected function labeledValueRow(Worksheet $sheet, int $row, string $leftLabel, string $leftValue, string $rightLabel, string $rightValue): int
    {
        $this->mergeText($sheet, 'A'.$row.':B'.$row, $leftLabel, 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'C'.$row.':C'.$row, $leftValue);
        $this->mergeText($sheet, 'D'.$row.':E'.$row, $rightLabel, 10, true, Alignment::HORIZONTAL_LEFT, true);
        $this->mergeText($sheet, 'F'.$row.':F'.$row, $rightValue);
        $this->outline('A'.$row.':F'.$row, $sheet);

        return $row + 1;
    }

    protected function tripleValueRow(Worksheet $sheet, int $row, array $pairs): int
    {
        $columns = [['A', 'B'], ['C', 'D'], ['E', 'F']];
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

    protected function questionRow(Worksheet $sheet, int $row, string $question, string $value): int
    {
        $this->mergeText($sheet, 'A'.$row.':D'.$row, $question);
        $sheet->setCellValue('E'.$row, $this->yesNoMark($value, true));
        $sheet->setCellValue('F'.$row, $this->yesNoMark($value, false));
        $this->headerStyle($sheet, 'E'.$row.':F'.$row, 'YES / NO');
        $this->outline('A'.$row.':F'.$row, $sheet);
        $sheet->getStyle('E'.$row.':F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return $row + 1;
    }

    protected function yesNoMark(string $value, bool $yes): string
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

    protected function satisfactoryText(?string $value): string
    {
        return trim((string) $value) === '0' ? 'Satisfactory' : 'Not Satisfactory';
    }

    protected function mergeText(Worksheet $sheet, string $range, string $text, int $size = 10, bool $bold = false, string $horizontal = Alignment::HORIZONTAL_LEFT, bool $fillLabel = false): void
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

    protected function headerStyle(Worksheet $sheet, string $range, ?string $defaultText = null): void
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
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9D9D9']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '333333']]],
        ]);
    }

    protected function outline(string $range, Worksheet $sheet): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '333333']]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);
    }

    protected function addEmployeeSignature(Worksheet $sheet, string $coordinates, string $filename): void
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

    protected function addStampImage(Worksheet $sheet, string $coordinates): void
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

    protected function addQrImage(Worksheet $sheet, string $coordinates, string $value): void
    {
        $value = trim($value);
        if ($value === '') {
            return;
        }

        $barcode = new \Milon\Barcode\DNS2D();
        $base64 = $barcode->getBarcodePNG($value, 'QRCODE');
        $tempPath = tempnam(sys_get_temp_dir(), 'inspection_qr_');
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

    protected function metadataStrip(Worksheet $sheet, int $row, array $pairs): int
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

    protected function addFooterBrandStrip(Worksheet $sheet, int $row): int
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

    protected function clientName(InspectionReport $report): string
    {
        return optional($report->job_request->client)->name ?? optional($report->job_request->supplier)->name ?? '';
    }

    protected function clientLocation(InspectionReport $report): string
    {
        $location = optional($report->job_request->client)->location ?? optional($report->job_request->supplier)->location ?? '';

        return $this->sanitizeText((string) $location);
    }

    protected function workLocation(InspectionReport $report): string
    {
        $jobRequest = $report->job_request;
        $departmentName = optional($jobRequest->clientDepartment)->name ?? '';
        $location = trim((string) $jobRequest->deploc);

        return $departmentName !== '' && $location !== '' ? $departmentName.' / '.$location : ($departmentName !== '' ? $departmentName : $location);
    }

    protected function reportNumberWithRevision(InspectionReport $report, string $code, int $revisionNumber): string
    {
        $jobCode = (string) optional($report->job_request)->code;
        $code = trim((string) preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', $code));

        return trim($jobCode.' / '.$code, ' /').' - REV: '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT);
    }

    protected function sanitizeText(string $value): string
    {
        return preg_replace('~[\\\\/:*?"<>\\[\\]|]~', '', $value) ?: $value;
    }
}
