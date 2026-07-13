<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Lifting\OverheadCrane;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OverheadCraneReportWorksheetRenderer extends AbstractLiftingReportWorksheetRenderer
{
    public function render(Worksheet $sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        /** @var OverheadCrane|null $overheadCrane */
        $overheadCrane = $report->reportable;
        if (!$overheadCrane instanceof OverheadCrane) {
            return;
        }

        $overheadCrane->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'overheadcrane2');
        $report->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'user.employee');
        $approvedUser = !empty($report->user_id_approved) ? User::query()->with('employee')->find($report->user_id_approved) : null;
        $footerValues = $overheadCrane->footer_values;

        $this->bootSheet($sheet, 'REV '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));
        $this->configureColumns($sheet);
        $this->addHeaderImages($sheet, $report);

        $row = $this->renderStandardHeader(
            $sheet,
            'Through Examination And / Or Test Certificate Of Overhead Cranes',
            'This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998'
        );

        $row = $this->labeledValueRow($sheet, $row, 'Name of employer for whom the examination was made', $this->clientName($report), 'Address of premises at examination was made', $this->clientLocation($report));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Purchase Order', (string) ($report->job_request->purchase_order ?: $overheadCrane->locr_2)],
            ['JCF Number', (string) optional($report->job_request)->code],
            ['Report No', $this->reportNumberWithRevision($report, (string) $overheadCrane->code, $revisionNumber)],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Examination Date', (string) $overheadCrane->locr_6],
            ['Next Exa. Date', (string) $overheadCrane->locr_7],
            ['Color Code', (string) $overheadCrane->locr_8],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Work location', $this->workLocation($report));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Type of Crane and nature of Power', (string) $overheadCrane->locr_10],
            ['Name of Manufacturer', (string) $overheadCrane->locr_11],
            ['Identification Number / Part Number', (string) $overheadCrane->locr_12],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Model / Type', (string) $overheadCrane->locr_13],
            ['Date of Manufacturer', (string) $overheadCrane->locr_14],
            ['Crane Capacity (SWL)', (string) $overheadCrane->locr_15],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Make and type of automatic (SLI)', (string) $overheadCrane->locr_16],
            ['SLI Identification number', (string) $overheadCrane->locr_17],
            ['Number of falls / lines', (string) $overheadCrane->locr_18],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Last Examination Date', (string) $overheadCrane->locr_19],
            ['Certificate Number', (string) $overheadCrane->locr_20],
            ['Examined by', (string) $overheadCrane->locr_21],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Date of last Load Test', (string) $overheadCrane->locr_22],
            ['Certificate Number', (string) $overheadCrane->locr_23],
            ['Tested by', (string) $overheadCrane->locr_24],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Reference Standard', (string) $overheadCrane->locr_25);

        $row = $this->sectionTitle($sheet, $row, 'Examination Questions');
        $row = $this->questionRow($sheet, $row, 'First examination after installation or assembly at a new site?', (string) $overheadCrane->locr_26);
        $row = $this->questionRow($sheet, $row, 'If yes, has the equipment been installed correctly?', (string) $overheadCrane->locr_27);
        $row = $this->questionRow($sheet, $row, 'Within an interval of 6 months?', (string) $overheadCrane->locr_28);
        $row = $this->questionRow($sheet, $row, 'Within an interval of 12 months?', (string) $overheadCrane->locr_29);
        $row = $this->questionRow($sheet, $row, 'In accordance with an examination scheme?', (string) $overheadCrane->locr_30);
        $row = $this->questionRow($sheet, $row, 'After exceptional circumstances?', (string) $overheadCrane->locr_31);

        $row = $this->singleWideRow($sheet, $row, 'Identification of any defect found', (string) $overheadCrane->locr_32, 2);
        $row = $this->questionRow($sheet, $row, 'Is the defect of immediate danger to persons?', (string) $overheadCrane->locr_33);
        $row = $this->questionRow($sheet, $row, 'Could the defect become a danger to persons?', (string) $overheadCrane->locr_34);
        $row = $this->singleWideRow($sheet, $row, 'Date by when action is required', (string) $overheadCrane->locr_35);
        $row = $this->singleWideRow($sheet, $row, 'Repair / renewal / alteration required', (string) $overheadCrane->locr_36, 2);
        $row = $this->singleWideRow($sheet, $row, 'Tests carried out as part of the examination', (string) $overheadCrane->locr_37, 2);

        $row = $this->sectionTitle($sheet, $row, 'Proof Load Test Details');
        $row = $this->singleWideRow($sheet, $row, '1- Performance test', (string) $overheadCrane->locr_38);
        $row = $this->singleWideRow($sheet, $row, 'Main Girder crane Span', (string) $overheadCrane->locr_39);
        $row = $this->singleWideRow($sheet, $row, 'Maximum Deflection allowable', (string) $overheadCrane->locr_40);
        $row = $this->singleWideRow($sheet, $row, 'Actual deflection', (string) $overheadCrane->locr_41);
        $row = $this->singleWideRow($sheet, $row, '2- Over Load test', (string) $overheadCrane->locr_42);
        $row = $this->singleWideRow($sheet, $row, 'Deflection of Main Girder Crane During over load', (string) $overheadCrane->locr_43);
        $row = $this->questionRow($sheet, $row, 'Is this equipment safe to operate?', (string) $overheadCrane->locr_44);

        if ($overheadCrane->overheadcrane2) {
            $row++;
            $row = $this->sectionTitle($sheet, $row, 'Second Page Details');
            $row = $this->renderSecondPage($sheet, $row, $overheadCrane);
        }

        $row++;
        $this->footerBlock(
            $sheet,
            $row,
            $report,
            $approvedUser,
            url('storage/pdf/inspection/lifting/overheadcrane/'.optional($report->job_request)->code.'/'.optional($report->reportable)->code.'.pdf'),
            data_get($footerValues, 'form_no'),
            data_get($footerValues, 'issue_no'),
            data_get($footerValues, 'issue_date'),
            data_get($footerValues, 'revision_no'),
            data_get($footerValues, 'revision_date')
        );
    }

    private function renderSecondPage(Worksheet $sheet, int $row, OverheadCrane $overheadCrane): int
    {
        $page2 = $overheadCrane->overheadcrane2;
        if (!$page2) {
            return $row;
        }

        $components = [
            ['Main Wire Rope', $page2->locr2_1, $page2->locr2_4, $page2->locr2_2, $page2->locr2_3, $page2->locr2_5, $page2->locr2_6, $page2->locr2_7, $page2->locr2_8],
            ['Main Block / Hook', $page2->locr2_9, $page2->locr2_12, $page2->locr2_10, $page2->locr2_11, $page2->locr2_13, $page2->locr2_14, $page2->locr2_15, $page2->locr2_16],
            ['Auxiliary Wire Rope', $page2->locr2_17, $page2->locr2_20, $page2->locr2_18, $page2->locr2_19, $page2->locr2_21, $page2->locr2_22, $page2->locr2_23, $page2->locr2_24],
            ['Auxiliary Block / Hook (Overhaul ball)', $page2->locr2_25, $page2->locr2_28, $page2->locr2_26, $page2->locr2_27, $page2->locr2_29, $page2->locr2_30, $page2->locr2_31, $page2->locr2_32],
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

        $row = $this->sectionTitle($sheet, $row, 'MPI Details (Inspection Method and equipment used)');
        $row = $this->tripleValueRow($sheet, $row, [
            ['Standard', (string) $page2->locr2_33],
            ['Equipment Type', (string) $page2->locr2_34],
            ['Equipment No', (string) $page2->locr2_35],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Pole spacing', (string) $page2->locr2_36],
            ['Due Date', (string) $page2->locr2_37],
            ['Contrast / Indicator', trim((string) $page2->locr2_38.' / '.(string) $page2->locr2_39, ' /')],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Expire Dates', trim((string) $page2->locr2_40.' / '.(string) $page2->locr2_41, ' /'));
        $row = $this->singleWideRow($sheet, $row, 'Final Conclusion', trim((string) html_entity_decode((string) $page2->locr2_42)), 3);
        $row = $this->singleWideRow($sheet, $row, 'Guide Instructions', trim((string) html_entity_decode((string) $page2->locr2_43)), 3);
        $row = $this->singleWideRow($sheet, $row, 'Certification Statement', 'I hereby certify that the overhead crane described in this certificate was tested with accessories gears by a competent person in a manner set forth on the reverse side of this certificate; that a careful examination of the said machinery and gear by a competent person after the test showed it had withstood with the proof load without injury or permanent deformation and that the Safe Working load of the above describe machinery and gears is as shown in the load test details table.', 3);

        return $row;
    }
}
