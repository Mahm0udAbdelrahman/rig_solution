<?php

namespace App\Services\GeneralInfo;

use App\Models\GeneralInfo\EquipmentControlledList;
use App\Models\GeneralInfo\FooterValue;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Whole Equipment Controlled List in the ISO form layout (RS-IMS-P10-F01): logo + title, blue
 * "Rig Solution Engineering / LAST UPDATE" band, colored headings and the Form No / Issue / Revision / Page footer
 * taken from General Info > Footer Values.
 */
class EquipmentControlledListExport
{
    const HEADER_YELLOW = 'FFFF00';
    const HEADER_GREEN = 'A9D08E';
    const SERVICE_GREEN = '00B050';
    const BAND_BLUE = '2F5597';
    const ROW_GREY = 'D9D9D9';

    /**
     * Columns of the form: key => [heading, heading color, width in Excel]
     */
    const COLUMNS = [
        'equipment_description' => ['Equipment Description', self::HEADER_YELLOW, 22],
        'internal_code' => ['Internal Code', self::HEADER_YELLOW, 18],
        'manufacturer' => ['Manufacturer', self::HEADER_YELLOW, 16],
        'model_type' => ['Model / Type', self::HEADER_YELLOW, 15],
        'capacity_range' => ['Capacity / Range', self::HEADER_YELLOW, 14],
        'serial_number' => ['Serial Number', self::HEADER_YELLOW, 18],
        'date_into_service' => ['Date into Service', self::SERVICE_GREEN, 12],
        'interval' => ['Maintenance / Calibration Interval', self::HEADER_YELLOW, 14],
        'calibration_date' => ['Calibration Date', self::HEADER_YELLOW, 12],
        'calibration_due_date' => ['Calibration Due Date', self::HEADER_YELLOW, 12],
        'calibrated_by' => ['Calibrated By', self::HEADER_YELLOW, 10],
        'alarm' => ['Re-calibration Alarm', self::HEADER_GREEN, 13],
        'location_department' => ['Location / Department', self::HEADER_GREEN, 15],
        'status' => ['Status', self::HEADER_GREEN, 17],
        'date_removed_from_service' => ['Date Removed From Service', self::HEADER_GREEN, 16],
    ];

    private $equipments;
    private $footer;

    public function __construct()
    {
        $this->equipments = EquipmentControlledList::query()->orderBy('id')->get();
        $this->footer = FooterValue::query()->where('related_inspection', EquipmentControlledList::class)->first();
    }

    /**
     * One printed row per equipment, formatted like the form (service date 21-Mar-2011, calibration dates 15-12-24)
     */
    public function rows()
    {
        return $this->equipments->map(function (EquipmentControlledList $equipment) {
            return [
                'equipment_description' => $equipment->equipment_description,
                'internal_code' => $equipment->internal_code,
                'manufacturer' => $equipment->manufacturer,
                'model_type' => $equipment->model_type,
                'capacity_range' => $equipment->capacity_range,
                'serial_number' => $equipment->serial_number,
                'date_into_service' => $equipment->date_into_service ? $equipment->date_into_service->format('d-M-Y') : 'N/A',
                'interval' => $equipment->interval,
                'calibration_date' => $equipment->calibration_date ? $equipment->calibration_date->format('d-m-y') : 'N/A',
                'calibration_due_date' => $equipment->calibration_due_date ? $equipment->calibration_due_date->format('d-m-y') : 'N/A',
                'calibrated_by' => $equipment->calibrated_by ?: 'N/A',
                'alarm' => $equipment->iso_alarm,
                'location_department' => $equipment->location_department,
                'status' => $equipment->display_status,
                'date_removed_from_service' => $equipment->date_removed_from_service,
            ];
        });
    }

    /**
     * LAST UPDATE on the band: the latest change to any equipment
     */
    public function lastUpdate()
    {
        $latest = $this->equipments->max('updated_at');

        return Carbon::parse($latest ?: now())->format('d-m-y');
    }

    public function footer()
    {
        return [
            'form_no' => optional($this->footer)->form_no,
            'issue_no' => optional($this->footer)->issue_no,
            'issue_date' => optional($this->footer)->issue_date,
            'revision_no' => optional($this->footer)->revision_no,
            'revision_date' => optional($this->footer)->revision_date,
        ];
    }

    public function filename($extension)
    {
        return trim(($this->footer()['form_no'] ?: 'RS-IMS-P10-F01') . ' Equipment Controlled List ' . now()->format('d-m-Y')) . '.' . $extension;
    }

    public function downloadPdf()
    {
        $pdf = PDF::loadView('layouts.general-info.equipmentControlledList.export-pdf', [
            'columns' => self::COLUMNS,
            'rows' => $this->rows(),
            'lastUpdate' => $this->lastUpdate(),
            'footer' => $this->footer(),
            'logo' => 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('app-assets/images/logo/logo.png'))),
        ])->setPaper('letter', 'landscape');

        // "Page X of Y" at the right end of the footer line, on every page
        $dompdf = $pdf->getDomPDF();
        $dompdf->render();
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont('Helvetica');
        $canvas->page_text($canvas->get_width() - 62, $canvas->get_height() - 20.5, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, 5.3, [0, 0, 0]);

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $this->filename('pdf') . '"',
        ]);
    }

    public function downloadExcel()
    {
        $spreadsheet = $this->buildSpreadsheet();

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $this->filename('xlsx'), [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function buildSpreadsheet()
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri')->setSize(10);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Equipment Controlled List');
        $sheet->setShowGridlines(false);

        $keys = array_keys(self::COLUMNS);
        $lastCol = $this->col(count($keys));
        $headerRow = 5;
        $firstDataRow = $headerRow + 1;

        foreach ($keys as $i => $key) {
            $sheet->getColumnDimension($this->col($i + 1))->setWidth(self::COLUMNS[$key][2]);
        }

        // Logo (left) and form title (right)
        $drawing = new Drawing();
        $drawing->setPath(public_path('app-assets/images/logo/logo.png'));
        $drawing->setHeight(55);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $sheet->getRowDimension(1)->setRowHeight(24);
        $sheet->getRowDimension(2)->setRowHeight(16);
        $sheet->getRowDimension(3)->setRowHeight(16);

        $sheet->mergeCells('K1:' . $lastCol . '1');
        $sheet->setCellValue('K1', 'Equipment Controlled List');
        $sheet->getStyle('K1')->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle('K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->mergeCells('K2:' . $lastCol . '2');
        $sheet->setCellValue('K2', 'Integrated Management System');
        $sheet->getStyle('K2')->getFont()->setBold(true)->setSize(8);
        $sheet->getStyle('K2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Blue band: Rig Solution Engineering ... LAST UPDATE dd-mm-yy
        $band = 4;
        $sheet->getRowDimension($band)->setRowHeight(24);
        $sheet->mergeCells('A' . $band . ':K' . $band);
        $title = new RichText();
        foreach ([['R', 'FF0000'], ['ig ', 'FFFFFF'], ['S', 'FF0000'], ['olution ', 'FFFFFF'], ['E', 'FF0000'], ['ngineering', 'FFFFFF']] as [$text, $color]) {
            $title->createTextRun($text)->getFont()->setSize(16)->setColor(new Color('FF' . $color));
        }
        $sheet->setCellValue('A' . $band, $title);
        $sheet->getStyle('A' . $band)->getFont()->setSize(16)->setColor(new Color('FFFFFFFF'));
        $sheet->mergeCells('L' . $band . ':M' . $band);
        $sheet->setCellValue('L' . $band, 'LAST UPDATE');
        $sheet->mergeCells('N' . $band . ':' . $lastCol . $band);
        $sheet->setCellValue('N' . $band, $this->lastUpdate());
        $sheet->getStyle('L' . $band . ':' . $lastCol . $band)->getFont()->setBold(true)->setSize(14)->setColor(new Color('FFFFC000'));
        $sheet->getStyle('L' . $band . ':' . $lastCol . $band)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $band . ':' . $lastCol . $band)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A' . $band . ':' . $lastCol . $band)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::BAND_BLUE);

        // Column headings
        $sheet->getRowDimension($headerRow)->setRowHeight(36);
        foreach ($keys as $i => $key) {
            $cell = $this->col($i + 1) . $headerRow;
            $sheet->setCellValue($cell, self::COLUMNS[$key][0]);
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::COLUMNS[$key][1]);
        }
        $sheet->getStyle('A' . $headerRow . ':' . $lastCol . $headerRow)->getFont()->setBold(true)->setSize(9);

        // Data
        $row = $firstDataRow;
        foreach ($this->rows() as $index => $values) {
            foreach ($keys as $i => $key) {
                $sheet->setCellValueExplicit($this->col($i + 1) . $row, (string) ($values[$key] ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            }
            if ($index % 2 === 0) {
                $sheet->getStyle('A' . $row . ':' . $lastCol . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::ROW_GREY);
            }
            $row++;
        }
        $lastRow = max($firstDataRow, $row - 1);

        $serviceCol = $this->col(array_search('date_into_service', $keys) + 1);
        $sheet->getStyle($serviceCol . $firstDataRow . ':' . $serviceCol . $lastRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::SERVICE_GREEN);
        $sheet->getStyle($serviceCol . $firstDataRow . ':' . $serviceCol . $lastRow)->getFont()->setSize(8);

        $table = 'A' . $headerRow . ':' . $lastCol . $lastRow;
        $sheet->getStyle($table)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($table)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        $sheet->getStyle('A' . $firstDataRow . ':A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->setAutoFilter('A' . $headerRow . ':' . $lastCol . $lastRow);
        $sheet->freezePane('A' . $firstDataRow);

        // Print like the form: landscape on Letter, one page wide, headings on every page, ISO footer
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_LETTER)
            ->setFitToWidth(1)
            ->setFitToHeight(0)
            ->setRowsToRepeatAtTopByStartAndEnd($headerRow, $headerRow);
        $sheet->getPageMargins()->setTop(0.4)->setBottom(0.6)->setLeft(0.25)->setRight(0.25)->setFooter(0.25);

        $footer = array_map(function ($value) {
            return str_replace('&', '&&', (string) $value);
        }, $this->footer());
        $sheet->getHeaderFooter()->setOddFooter(
            '&L&8Form No ' . $footer['form_no'] . '      Issue No. ' . $footer['issue_no'] . '      Issue date: ' . $footer['issue_date']
            . '&C&8Revision No ' . $footer['revision_no'] . '            Revision Date ' . $footer['revision_date']
            . '&R&8Page &P of &N'
        );

        $sheet->setSelectedCell('A' . $firstDataRow);

        return $spreadsheet;
    }

    private function col($index)
    {
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index);
    }
}
