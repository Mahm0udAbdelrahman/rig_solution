<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Tubular\Pbl;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PblReportWorksheetRenderer extends AbstractLiftingReportWorksheetRenderer
{
    public function render(Worksheet $sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        /** @var Pbl|null $pbl */
        $pbl = $report->reportable;
        if (!$pbl instanceof Pbl) {
            return;
        }

        $pbl->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment');
        $report->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'user.employee');

        $approvedUser = !empty($report->user_id_approved) ? User::query()->with('employee')->find($report->user_id_approved) : null;
        $footerValues = $pbl->footer_values;

        $this->bootSheet($sheet, 'REV '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));
        $this->configureColumns($sheet);
        $this->addHeaderImages($sheet, $report);

        $row = $this->renderStandardHeader(
            $sheet,
            'Pin / Box Log',
            'This report complies with the tubular inspection workflow and approved internal form'
        );

        $row = $this->labeledValueRow($sheet, $row, 'Name of employer for whom the examination was made', $this->clientName($report), 'Address of premises at examination was made', $this->clientLocation($report));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Purchase Order', (string) optional($report->job_request)->purchase_order],
            ['JCF Number', (string) optional($report->job_request)->code],
            ['Report No', $this->reportNumberWithRevision($report, (string) $pbl->code, $revisionNumber)],
        ]);
        $row = $this->labeledValueRow($sheet, $row, 'Work location', $this->workLocation($report), 'Examination Date', (string) $pbl->examination_date);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Specification', $this->specificationText($pbl)],
            ['Inspection Method', implode(', ', array_filter((array) $pbl->inspection_method))],
            ['Edition', (string) $pbl->edition],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Inspection Description', (string) $pbl->inspection_description],
            ['Description', (string) $pbl->description],
            ['Identification No', (string) $pbl->identification_no],
        ]);

        $equipmentRows = array_filter(array_map(function ($item) {
            $used = trim((string) data_get($item, 'equipment_used'));
            if (strtolower($used) === 'other') {
                $used = trim((string) data_get($item, 'other_equipment'));
            }
            $number = trim((string) data_get($item, 'equipment_no_value'));

            return trim($used.' '.$number);
        }, (array) $pbl->equipment_no));
        $row = $this->singleWideRow($sheet, $row, 'Equipment Used / Equipment No', implode(' | ', $equipmentRows), 2);

        $row = $this->sectionTitle($sheet, $row, 'Inspection Data');
        foreach ((array) $pbl->inspection_data as $index => $item) {
            $row = $this->singleWideRow($sheet, $row, 'Joint '.($index + 1).' - Sub Type / Serial / Overall Length', trim(implode(' | ', array_filter([
                (string) data_get($item, 'input_1'),
                (string) data_get($item, 'input_2'),
                (string) data_get($item, 'input_13'),
            ]))));
            $row = $this->singleWideRow($sheet, $row, $this->firstConnectionHeader($pbl), $this->measurementText($item, 3, 12), 2);
            $row = $this->singleWideRow($sheet, $row, $this->secondConnectionHeader($pbl), $this->measurementText($item, 13, 22), 2);
        }

        $row = $this->sectionTitle($sheet, $row, 'Inspection Photos');
        foreach ((array) $pbl->inspection_data as $index => $item) {
            $this->mergeText($sheet, 'A'.$row.':C'.$row, 'Photo '.($index + 1), 10, true, Alignment::HORIZONTAL_LEFT, true);
            $this->mergeText($sheet, 'D'.$row.':F'.$row, trim((string) data_get($item, 'photo_description')));
            $this->outline('A'.$row.':F'.($row + 3), $sheet);
            $this->addStorageImage($sheet, data_get($item, 'photo'), 'A'.$row, 90, 12, 8);
            $sheet->getRowDimension($row)->setRowHeight(24);
            for ($i = 1; $i <= 3; $i++) {
                $sheet->getRowDimension($row + $i)->setRowHeight(24);
            }
            $row += 4;
        }

        $row = $this->tripleValueRow($sheet, $row, [
            ['Connection Defective (Red)', (string) $pbl->connection_defective],
            ['Connection Accepted (White)', (string) $pbl->connection_accepted],
            ['Connection to be Repaired', (string) $pbl->connection_to_be_repaired],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Total Connection Inspected', (string) $pbl->total_connection_inspected],
            ['Joints to be Repaired', (string) $pbl->joints_to_be_repaired],
            ['Total Joints Inspected', (string) $pbl->total_joints_inspected],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Comments', trim((string) $pbl->comment), 3);

        $row++;
        $this->footerBlock(
            $sheet,
            $row,
            $report,
            $approvedUser,
            url('storage/pdf/inspection/tubular/pbl/'.optional($report->job_request)->code.'/'.optional($report->reportable)->code.'.pdf'),
            data_get($footerValues, 'form_no'),
            data_get($footerValues, 'issue_no'),
            data_get($footerValues, 'issue_date'),
            data_get($footerValues, 'revision_no'),
            data_get($footerValues, 'revision_date')
        );
    }

    private function specificationText(Pbl $pbl): string
    {
        $parts = [];
        foreach ((array) $pbl->specification as $item) {
            $parts[] = $item === 'S-008' ? 'Other' : $item;
        }
        $text = implode(', ', array_filter($parts));
        if (trim((string) $pbl->other_specification) !== '') {
            $text .= ($text !== '' ? ' | ' : '').'Other: '.trim((string) $pbl->other_specification);
        }

        return $text;
    }

    private function firstConnectionHeader(Pbl $pbl): string
    {
        return data_get($pbl->standards, 'type') === 'type_1' ? 'Pin Connection' : 'Box Connection';
    }

    private function secondConnectionHeader(Pbl $pbl): string
    {
        return data_get($pbl->standards, 'type') === 'type_3' ? 'Box Connection' : 'Pin Connection';
    }

    private function measurementText(array $item, int $startIndex, int $endIndex): string
    {
        $chunks = [];
        $number = 1;
        for ($index = $startIndex; $index <= $endIndex; $index++) {
            $value = trim((string) data_get($item, 'input_'.$index));
            if ($value !== '') {
                $chunks[] = 'M'.$number.': '.$value;
            }
            $number++;
        }

        return implode(' | ', $chunks);
    }
}
