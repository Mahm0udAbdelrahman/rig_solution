<?php

namespace App\Services\WorkFlow;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileManagerExcelExportService
{
    public function download(string $filename, string $sheetTitle, array $columns, iterable $rows, array $metaLines = []): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $this->populateSheet($spreadsheet->getActiveSheet(), $sheetTitle, $columns, $rows, $metaLines);

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function downloadWorkbook(string $filename, array $sheets): StreamedResponse
    {
        $sheetDefinitions = collect($sheets)->filter(function ($sheet) {
            return is_array($sheet) && !empty($sheet['columns']);
        })->values();

        if ($sheetDefinitions->isEmpty()) {
            $sheetDefinitions = collect([[
                'title' => 'Report',
                'columns' => ['Field', 'Value'],
                'rows' => [['No data', '']],
                'meta_lines' => [],
            ]]);
        }

        $spreadsheet = new Spreadsheet();
        $existingTitles = [];

        foreach ($sheetDefinitions as $index => $sheetDefinition) {
            $sheet = $index === 0 ? $spreadsheet->getActiveSheet() : $spreadsheet->createSheet();
            $resolvedTitle = $this->resolveUniqueSheetTitle((string) ($sheetDefinition['title'] ?? 'Sheet '.($index + 1)), $existingTitles);
            $existingTitles[] = $resolvedTitle;

            $this->populateSheet(
                $sheet,
                $resolvedTitle,
                (array) ($sheetDefinition['columns'] ?? []),
                $sheetDefinition['rows'] ?? [],
                (array) ($sheetDefinition['meta_lines'] ?? [])
            );
        }

        $spreadsheet->setActiveSheetIndex(0);

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function downloadUsingBuilder(string $filename, callable $builder): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $builder($spreadsheet);

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function saveUsingBuilder(string $path, callable $builder): void
    {
        $spreadsheet = new Spreadsheet();
        $builder($spreadsheet);

        try {
            $writer = new Xlsx($spreadsheet);
            $writer->save($path);
        } finally {
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }
    }

    private function normalizeSheetTitle(string $sheetTitle): string
    {
        $normalized = preg_replace('/[\\\\\\/\\?\\*\\:\\[\\]]+/', ' ', trim($sheetTitle));
        $normalized = $normalized !== '' ? $normalized : 'Export';

        return mb_substr($normalized, 0, 31);
    }

    private function resolveUniqueSheetTitle(string $title, array $existingTitles): string
    {
        $baseTitle = $this->normalizeSheetTitle($title);
        $candidate = $baseTitle;
        $counter = 2;

        while (in_array($candidate, $existingTitles, true)) {
            $suffix = ' '.$counter;
            $candidate = mb_substr($baseTitle, 0, max(1, 31 - mb_strlen($suffix))).$suffix;
            $counter++;
        }

        return $candidate;
    }

    private function columnLetter(int $column): string
    {
        return Coordinate::stringFromColumnIndex(max($column, 1));
    }

    private function normalizeCellValue($value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '';
    }

    private function populateSheet(Worksheet $sheet, string $sheetTitle, array $columns, iterable $rows, array $metaLines = []): void
    {
        $columns = array_values($columns);
        $rowsCollection = $rows instanceof Collection ? $rows->values() : collect($rows)->values();
        $sheet->setTitle($this->normalizeSheetTitle($sheetTitle));

        $currentRow = 1;
        if (!empty($metaLines)) {
            foreach ($metaLines as $metaLine) {
                $sheet->setCellValue('A'.$currentRow, (string) $metaLine);
                $sheet->mergeCells('A'.$currentRow.':'.$this->columnLetter(count($columns)).$currentRow);
                $currentRow++;
            }

            $currentRow++;
        }

        $headerRow = $currentRow;
        foreach ($columns as $index => $heading) {
            $sheet->setCellValue($this->columnLetter($index + 1).$headerRow, (string) $heading);
        }

        $dataStartRow = $headerRow + 1;
        foreach ($rowsCollection as $rowIndex => $row) {
            $normalizedRow = is_array($row) ? array_values($row) : array_values((array) $row);
            foreach ($normalizedRow as $columnIndex => $value) {
                $sheet->setCellValue(
                    $this->columnLetter($columnIndex + 1).($dataStartRow + $rowIndex),
                    $this->normalizeCellValue($value)
                );
            }
        }

        $lastColumnLetter = $this->columnLetter(count($columns));
        $lastDataRow = max($headerRow, $dataStartRow + max($rowsCollection->count() - 1, 0));

        $sheet->getStyle('A'.$headerRow.':'.$lastColumnLetter.$headerRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '2F5597'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D9E2F3'],
                ],
            ],
        ]);

        if (!empty($metaLines)) {
            $sheet->getStyle('A1:A'.count($metaLines))->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
        }

        if ($rowsCollection->isNotEmpty()) {
            $sheet->getStyle('A'.$dataStartRow.':'.$lastColumnLetter.$lastDataRow)->applyFromArray([
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E9EDF5'],
                    ],
                ],
            ]);
        }

        $sheet->freezePane('A'.($headerRow + 1));
        $sheet->setAutoFilter('A'.$headerRow.':'.$lastColumnLetter.$headerRow);

        for ($column = 1; $column <= count($columns); $column++) {
            $sheet->getColumnDimension($this->columnLetter($column))->setAutoSize(true);
        }

        $sheet->getDefaultRowDimension()->setRowHeight(-1);
    }
}
