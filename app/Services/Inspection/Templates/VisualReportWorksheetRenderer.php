<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Ndt\Visual;
use App\Models\User;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VisualReportWorksheetRenderer extends AbstractLiftingReportWorksheetRenderer
{
    public function render(Worksheet $sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        /** @var Visual|null $visual */
        $visual = $report->reportable;
        if (!$visual instanceof Visual) {
            return;
        }

        $visual->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment');
        $report->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'user.employee');

        $approvedUser = !empty($report->user_id_approved) ? User::query()->with('employee')->find($report->user_id_approved) : null;
        $footerValues = $visual->footer_values;

        $this->bootSheet($sheet, 'REV '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));
        $this->configureColumns($sheet);
        $this->addHeaderImages($sheet, $report);

        $row = $this->renderStandardHeader(
            $sheet,
            'Visual Inspection Report',
            'This report complies with the approved NDT workflow and internal inspection form'
        );

        $row = $this->labeledValueRow($sheet, $row, 'Name of employer for whom the examination was made', $this->clientName($report), 'Address of premises at examination was made', $this->sanitizeText((string) optional($report->job_request)->deploc));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Purchase Order', (string) (optional($report->job_request)->purchase_order ?: $visual->nvr_2)],
            ['JCF Number', (string) optional($report->job_request)->code],
            ['Report No', $this->reportNumberWithRevision($report, (string) $visual->code, $revisionNumber)],
        ]);
        $row = $this->labeledValueRow($sheet, $row, 'Work location', $this->workLocation($report), 'Examination Date', (string) $visual->nvr_4);
        $row = $this->singleWideRow($sheet, $row, 'Description', (string) $visual->desc, 2);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Identification No', (string) $visual->nvr_6],
            ['Ref. Standard', (string) $visual->nvr_7],
            ['Acceptance Standard', (string) $visual->acceptance],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Material', (string) $visual->nvr_8],
            ['Instrument Used', (string) $visual->nvr_9],
            ['Material Thickness', (string) $visual->nvr_10],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Light Intensity', (string) $visual->nvr_11],
            ['Welding Process', (string) $visual->nvr_12],
            ['Light Source', (string) $visual->nvr_13],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Type of Joint', (string) $visual->nvr_14],
            ['Location', (string) $visual->nvr_15],
            ['Surface Condition', (string) $visual->nvr_16],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Stage of Exam', (string) $visual->nvr_18);

        $row = $this->sectionTitle($sheet, $row, 'Inspection Summary');
        $summaries = json_decode((string) $visual->nvr_19);
        if (!is_array($summaries) && !$summaries instanceof \Traversable) {
            $summaries = [];
        }

        foreach ((array) $summaries as $index => $summary) {
            $this->mergeText($sheet, 'A'.$row.':D'.$row, 'Summary '.($index + 1), 10, true, Alignment::HORIZONTAL_LEFT, true);
            $this->mergeText($sheet, 'E'.$row.':F'.$row, '');
            $this->outline('A'.$row.':F'.($row + 4), $sheet);
            $sheet->mergeCells('A'.($row + 1).':D'.($row + 4));
            $sheet->setCellValue('A'.($row + 1), (string) data_get($summary, 'nvr_22'));
            $sheet->getStyle('A'.($row + 1).':D'.($row + 4))->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);

            $storedPhoto = trim((string) data_get($summary, 'nvr_23'));
            $photoPath = $storedPhoto === ''
                ? null
                : (Str::startsWith($storedPhoto, 'camera/inspection/ndt/visual/')
                    ? ltrim($storedPhoto, '/')
                    : 'camera/inspection/ndt/visual/'.ltrim($storedPhoto, '/'));
            $this->addStorageImage($sheet, $photoPath, 'E'.($row + 1), 100, 8, 8);

            for ($i = 0; $i < 5; $i++) {
                $sheet->getRowDimension($row + $i)->setRowHeight(24);
            }
            $row += 5;
        }

        if (empty((array) $summaries)) {
            $row = $this->singleWideRow($sheet, $row, 'Summary', 'No summary notes attached.', 2);
        }

        $row = $this->singleWideRow($sheet, $row, 'Final Conclusion', str_contains((string) $visual->nvr_23, '_y') ? 'Accept' : (str_contains((string) $visual->nvr_23, '_n') ? 'Reject' : ''), 1);
        $row++;

        $this->footerBlock(
            $sheet,
            $row,
            $report,
            $approvedUser,
            url('storage/pdf/inspection/ndt/visual/'.optional($report->job_request)->code.'/'.optional($report->reportable)->code.'.pdf'),
            data_get($footerValues, 'form_no'),
            data_get($footerValues, 'issue_no'),
            data_get($footerValues, 'issue_date'),
            data_get($footerValues, 'revision_no'),
            data_get($footerValues, 'revision_date')
        );
    }
}
