<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\DropObject\DropObject;
use App\Models\Inspection\InspectionReport;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DropObjectReportWorksheetRenderer extends AbstractLiftingReportWorksheetRenderer
{
    public function render(Worksheet $sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        /** @var DropObject|null $dropObject */
        $dropObject = $report->reportable;
        if (!$dropObject instanceof DropObject) {
            return;
        }

        $dropObject->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment');
        $report->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'user.employee');

        $approvedUser = !empty($report->user_id_approved) ? User::query()->with('employee')->find($report->user_id_approved) : null;

        $this->bootSheet($sheet, 'REV '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));
        $this->configureColumns($sheet, ['A' => 8, 'B' => 18, 'C' => 16, 'D' => 20, 'E' => 16, 'F' => 20]);
        $this->addHeaderImages($sheet, $report);

        $row = $this->renderStandardHeader(
            $sheet,
            'Drop Object Survey',
            'This report complies with the requirements of the inspection workflow and approved survey forms'
        );

        $row = $this->labeledValueRow($sheet, $row, 'Name of employer for whom the examination was made', $this->clientName($report), 'Address of premises at examination was made', $this->clientLocation($report));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Purchase Order', (string) optional($report->job_request)->purchase_order],
            ['JCF Number', (string) optional($report->job_request)->code],
            ['Report No', $this->reportNumberWithRevision($report, (string) $dropObject->code, $revisionNumber)],
        ]);
        $row = $this->labeledValueRow($sheet, $row, 'Work location', $this->workLocation($report), 'Survey Date', (string) $dropObject->survey_date);
        $row = $this->singleWideRow($sheet, $row, 'Inspection Area', $this->inspectionAreaLabel($dropObject), 1);

        $row = $this->sectionTitle($sheet, $row, 'Inspection Items');
        $headers = ['Item', 'Photo', 'Description', 'Photo Ref #', 'Fastening / Condition', 'Comment / How To Inspect'];
        foreach ($headers as $index => $header) {
            $column = chr(ord('A') + $index);
            $sheet->setCellValue($column.$row, $header);
        }
        $this->headerStyle($sheet, 'A'.$row.':F'.$row);
        $row++;

        $items = is_array($dropObject->inspection_data) && !empty($dropObject->inspection_data)
            ? $dropObject->inspection_data
            : [[]];

        foreach ($items as $item) {
            $sheet->setCellValue('A'.$row, (string) data_get($item, 'item'));
            $sheet->setCellValue('B'.$row, trim((string) data_get($item, 'photo_reference')) !== '' ? (string) data_get($item, 'photo_reference') : (trim((string) data_get($item, 'photo')) !== '' ? 'Attached' : ''));
            $sheet->setCellValue('C'.$row, (string) data_get($item, 'description'));
            $sheet->setCellValue('D'.$row, (string) data_get($item, 'photo_reference'));
            $sheet->setCellValue(
                'E'.$row,
                trim(implode("\n", array_filter([
                    trim((string) data_get($item, 'fastening_methods')),
                    trim((string) data_get($item, 'condition')) !== '' ? 'Condition: '.ucfirst((string) data_get($item, 'condition')) : '',
                    trim((string) data_get($item, 'frequency')) !== '' ? 'Frequency: '.(string) data_get($item, 'frequency') : '',
                ])))
            );
            $sheet->setCellValue(
                'F'.$row,
                trim(implode("\n", array_filter([
                    trim((string) data_get($item, 'comment')),
                    trim((string) data_get($item, 'how_to_inspect')) !== '' ? 'How To Inspect: '.(string) data_get($item, 'how_to_inspect') : '',
                ])))
            );
            $this->outline('A'.$row.':F'.$row, $sheet);
            $sheet->getRowDimension($row)->setRowHeight(74);
            $sheet->getStyle('A'.$row.':F'.$row)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);

            $photoPath = trim((string) data_get($item, 'photo'));
            if ($photoPath !== '') {
                $this->addStorageImage($sheet, $photoPath, 'B'.$row, 60, 6, 6);
            }

            $row++;
        }

        $row++;
        $this->footerBlock(
            $sheet,
            $row,
            $report,
            $approvedUser,
            url('storage/pdf/inspection/dropobject/dropobject/'.optional($report->job_request)->code.'/'.optional($report->reportable)->code.'.pdf'),
            'RS-RF-F18',
            '04',
            '1-Mar-2024',
            '00',
            '1-Mar-2024'
        );
    }

    private function inspectionAreaLabel(DropObject $dropObject): string
    {
        $key = trim((string) $dropObject->inspection_area);
        if ($key === 'other') {
            return trim((string) $dropObject->other_inspection_area);
        }

        return (string) (DropObject::$INSPECTION_AREA_OPTIONS[$key] ?? $key);
    }
}
