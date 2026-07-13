<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Lifting\ThroughExamination;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ThroughExaminationReportWorksheetRenderer extends AbstractLiftingReportWorksheetRenderer
{
    public function render(Worksheet $sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        /** @var ThroughExamination|null $throughExamination */
        $throughExamination = $report->reportable;
        if (!$throughExamination instanceof ThroughExamination) {
            return;
        }

        $throughExamination->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment');
        $report->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'user.employee');
        $approvedUser = !empty($report->user_id_approved) ? User::query()->with('employee')->find($report->user_id_approved) : null;
        $footerValues = $throughExamination->footer_values;

        $this->bootSheet($sheet, 'REV '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));
        $this->configureColumns($sheet);
        $this->addHeaderImages($sheet, $report);

        $row = $this->renderStandardHeader(
            $sheet,
            'Thorough Examination Certificate Of Lifting Equipment',
            'This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998'
        );

        $row = $this->labeledValueRow($sheet, $row, 'Name of employer for whom the examination was made', $this->clientName($report), 'Address of premises at examination was made', $this->clientLocation($report));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Purchase Order', (string) ($report->job_request->purchase_order ?: $throughExamination->lter_2)],
            ['JCF Number', (string) optional($report->job_request)->code],
            ['Report No', $this->reportNumberWithRevision($report, (string) $throughExamination->code, $revisionNumber)],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Examination Date', (string) $throughExamination->lter_6],
            ['Next Exam. Date', (string) $throughExamination->lter_7],
            ['Color Code', (string) $throughExamination->lter_8],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Work location', $this->workLocation($report));

        $row = $this->sectionTitle($sheet, $row, 'Equipment Details');
        $equipment = is_array($throughExamination->lter_10) ? $throughExamination->lter_10 : [];
        $row = $this->tripleValueRow($sheet, $row, [
            ['Identification No.', (string) ($equipment['pop1'] ?? '')],
            ['QTY', (string) ($equipment['pop2'] ?? '')],
            ['Description', trim((string) ($equipment['pop20'] ?? '')."\n".(string) ($equipment['pop3'] ?? ''))],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['SWL', (string) ($equipment['pop4'] ?? '')],
            ['Proof Load', (string) ($equipment['pop5'] ?? '')],
            ['Attached to / Location', (string) $throughExamination->lter_15],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Tare Weight', (string) $throughExamination->lter_16],
            ['Last examination', (string) $throughExamination->lter_17],
            ['Date of Manufacture', (string) $throughExamination->lter_18],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Gross Mass', (string) $throughExamination->lter_19],
            ['Load Tested By', (string) $throughExamination->lter_20],
            ['Test Certificate #', (string) $throughExamination->lter_21],
        ]);
        $row = $this->labeledValueRow($sheet, $row, 'Date Of Test', (string) $throughExamination->lter_22, 'Reference Standard', (string) $throughExamination->lter_23);

        $row = $this->sectionTitle($sheet, $row, 'Examination Questions');
        $row = $this->questionRow($sheet, $row, 'First examination after installation or assembly at a new site?', (string) $throughExamination->lter_24);
        $row = $this->questionRow($sheet, $row, 'If yes, has the equipment been installed correctly?', (string) $throughExamination->lter_25);
        $row = $this->questionRow($sheet, $row, 'Within an interval of 6 months?', (string) $throughExamination->lter_26);
        $row = $this->questionRow($sheet, $row, 'Within an interval of 12 months?', (string) $throughExamination->lter_27);
        $row = $this->questionRow($sheet, $row, 'In accordance with an examination scheme?', (string) $throughExamination->lter_28);
        $row = $this->questionRow($sheet, $row, 'After exceptional circumstances?', (string) $throughExamination->lter_29);

        $row = $this->singleWideRow($sheet, $row, 'Identification of defect', (string) $throughExamination->lter_30, 2);
        $row = $this->questionRow($sheet, $row, 'Is the defect of immediate danger to persons?', (string) $throughExamination->lter_31);
        $row = $this->questionRow($sheet, $row, 'Could the defect become a danger to persons?', (string) $throughExamination->lter_32);
        $row = $this->singleWideRow($sheet, $row, 'Date by when action is required', (string) $throughExamination->lter_33);
        $row = $this->singleWideRow($sheet, $row, 'Repair / renewal / alteration required', (string) $throughExamination->lter_34, 2);
        $row = $this->singleWideRow($sheet, $row, 'Tests carried out as part of the examination', (string) $throughExamination->lter_35, 2);

        $row = $this->sectionTitle($sheet, $row, 'Image');
        $this->mergeText($sheet, 'A'.$row.':F'.$row, '');
        $this->outline('A'.$row.':F'.($row + 4), $sheet);
        $this->addStorageImage($sheet, !empty($throughExamination->lter_36) ? 'camera/inspection/lifting/throughexaminations/'.$throughExamination->lter_36 : null, 'B'.$row, 150, 10, 8);
        $row += 5;

        $row = $this->sectionTitle($sheet, $row, 'Inspection Method and equipment used');
        $row = $this->tripleValueRow($sheet, $row, [
            ['Standard', (string) $throughExamination->lter_37],
            ['Equipment Type', (string) $throughExamination->lter_38],
            ['Equipment No', (string) $throughExamination->lter_39],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Pole spacing', (string) $throughExamination->lter_40],
            ['Due Date', (string) $throughExamination->lter_41],
            ['Contrast / Indicator', trim((string) $throughExamination->lter_42.' / '.(string) $throughExamination->lter_43, ' /')],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Expire Dates', trim((string) $throughExamination->lter_44.' / '.(string) $throughExamination->lter_45, ' /'));
        $row = $this->singleWideRow($sheet, $row, 'NDT Conclusion', (string) $throughExamination->lter_46, 2);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Load Cell Range', (string) $throughExamination->lter_47],
            ['Load Cell Manufacturer', (string) $throughExamination->lter_48],
            ['Load Cell Serial No.', (string) $throughExamination->lter_49],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Load Cell Due Date', (string) $throughExamination->lter_50);
        $row = $this->questionRow($sheet, $row, 'Is this equipment safe to operate?', (string) $throughExamination->lter_51);
        $row = $this->singleWideRow($sheet, $row, 'Note', "Due date / color code doesn't guarantee that the equipment remains serviceable, so the normal visual inspection are still required prior to use", 2);

        $row++;
        $this->footerBlock(
            $sheet,
            $row,
            $report,
            $approvedUser,
            url('storage/pdf/inspection/lifting/throughexamination/'.optional($report->job_request)->code.'/'.optional($report->reportable)->code.'.pdf'),
            data_get($footerValues, 'form_no'),
            data_get($footerValues, 'issue_no'),
            data_get($footerValues, 'issue_date'),
            data_get($footerValues, 'revision_no'),
            data_get($footerValues, 'revision_date')
        );
    }
}
