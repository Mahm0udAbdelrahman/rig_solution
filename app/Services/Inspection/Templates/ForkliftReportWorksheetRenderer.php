<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Lifting\Forklift;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ForkliftReportWorksheetRenderer extends AbstractLiftingReportWorksheetRenderer
{
    public function render(Worksheet $sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        /** @var Forklift|null $forklift */
        $forklift = $report->reportable;
        if (!$forklift instanceof Forklift) {
            return;
        }

        $forklift->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'forklift2');
        $report->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'user.employee');
        $approvedUser = !empty($report->user_id_approved) ? User::query()->with('employee')->find($report->user_id_approved) : null;
        $footerValues = $forklift->footer_values;

        $this->bootSheet($sheet, 'REV '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));
        $this->configureColumns($sheet);
        $this->addHeaderImages($sheet, $report);

        $row = $this->renderStandardHeader(
            $sheet,
            'Through Examination Certificate Of Wheel Loader / Forklift Truck',
            'This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998'
        );

        $row = $this->labeledValueRow($sheet, $row, 'Name of employer for whom the examination was made', $this->clientName($report), 'Address of premises at examination was made', $this->clientLocation($report));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Purchase Order', (string) ($report->job_request->purchase_order ?: $forklift->lfr_2)],
            ['JCF Number', (string) optional($report->job_request)->code],
            ['Report No', $this->reportNumberWithRevision($report, (string) $forklift->code, $revisionNumber)],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Examination Date', (string) $forklift->lfr_6],
            ['Next Exa. Date', (string) $forklift->lfr_7],
            ['Color Code', (string) $forklift->lfr_8],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Work location', $this->workLocation($report));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Equipment Description', (string) $forklift->lfr_10],
            ['Name of Manufacturer', (string) $forklift->lfr_11],
            ['Identification Number / chassis number', (string) $forklift->lfr_12],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Model / Type', (string) $forklift->lfr_13],
            ['Date of Manufacturer', (string) $forklift->lfr_14],
            ['Safe Working Load (SWL)', (string) $forklift->lfr_15],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Date of last Through Examination', (string) $forklift->lfr_16],
            ['Certificate Number', (string) $forklift->lfr_17],
            ['Examined by', (string) $forklift->lfr_18],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Proof load test Details (if applied)', (string) $forklift->lfr_19);
        $row = $this->singleWideRow($sheet, $row, 'Reference Standard', (string) $forklift->lfr_20);

        $row = $this->sectionTitle($sheet, $row, 'Examination Questions');
        $row = $this->questionRow($sheet, $row, 'First examination after installation or assembly at a new site?', (string) $forklift->lfr_21);
        $row = $this->questionRow($sheet, $row, 'If yes, has the equipment been installed correctly?', (string) $forklift->lfr_22);
        $row = $this->questionRow($sheet, $row, 'Within an interval of 6 months?', (string) $forklift->lfr_23);
        $row = $this->questionRow($sheet, $row, 'Within an interval of 12 months?', (string) $forklift->lfr_24);
        $row = $this->questionRow($sheet, $row, 'In accordance with an examination scheme?', (string) $forklift->lfr_25);
        $row = $this->questionRow($sheet, $row, 'After exceptional circumstances?', (string) $forklift->lfr_26);

        $row = $this->singleWideRow($sheet, $row, 'Identification of any part found to have a defect', (string) $forklift->lfr_27, 2);
        $row = $this->questionRow($sheet, $row, 'Is the defect of immediate danger to persons?', (string) $forklift->lfr_28);
        $row = $this->questionRow($sheet, $row, 'Could the defect become a danger to persons?', (string) $forklift->lfr_29);
        $row = $this->singleWideRow($sheet, $row, 'Date by when action is required', (string) $forklift->lfr_30);
        $row = $this->singleWideRow($sheet, $row, 'Repair / renewal / alteration required', (string) $forklift->lfr_31, 2);
        $row = $this->singleWideRow($sheet, $row, 'Tests carried out as part of the examination', (string) $forklift->lfr_32, 2);

        $row = $this->sectionTitle($sheet, $row, 'Equipment Image');
        $this->mergeText($sheet, 'A'.$row.':F'.$row, '');
        $this->outline('A'.$row.':F'.($row + 4), $sheet);
        $this->addStorageImage($sheet, !empty($forklift->lfr_34) ? 'camera/inspection/lifting/forklifts/'.$forklift->lfr_34 : null, 'B'.$row, 150, 8, 8);
        $row += 5;

        $row = $this->questionRow($sheet, $row, 'Is this equipment safe to operate?', (string) $forklift->lfr_33);

        if ($forklift->forklift2) {
            $row++;
            $row = $this->sectionTitle($sheet, $row, 'Second Page Details');
            $row = $this->renderSecondPage($sheet, $row, $forklift);
        }

        $row++;
        $this->footerBlock(
            $sheet,
            $row,
            $report,
            $approvedUser,
            url('storage/pdf/inspection/lifting/forklift/'.optional($report->job_request)->code.'/'.optional($report->reportable)->code.'.pdf'),
            data_get($footerValues, 'form_no'),
            data_get($footerValues, 'issue_no'),
            data_get($footerValues, 'issue_date'),
            data_get($footerValues, 'revision_no'),
            data_get($footerValues, 'revision_date')
        );
    }

    private function renderSecondPage(Worksheet $sheet, int $row, Forklift $forklift): int
    {
        $forklift2 = $forklift->forklift2;
        if (!$forklift2) {
            return $row;
        }

        $row = $this->sectionTitle($sheet, $row, 'Function test components');
        $components = [
            ['Service Brake / Forward', $forklift2->lfr2_1, $forklift2->lfr2_2],
            ['Service Brake / Reverse', $forklift2->lfr2_3, $forklift2->lfr2_4],
            ['Parking Brake / Forward', $forklift2->lfr2_5, $forklift2->lfr2_6],
            ['Parking Brake / Reverse', $forklift2->lfr2_7, $forklift2->lfr2_8],
            ['Steering Operation / Not Excessive Free Play', $forklift2->lfr2_9, $forklift2->lfr2_10],
            ['Drive Control / Forward', $forklift2->lfr2_11, $forklift2->lfr2_12],
            ['Drive Control / Reverse', $forklift2->lfr2_13, $forklift2->lfr2_14],
            ['Tilt Control / Forward', $forklift2->lfr2_15, $forklift2->lfr2_16],
            ['Tilt Control / Back', $forklift2->lfr2_17, $forklift2->lfr2_18],
            ['Hoist & Lower Control / Hoist', $forklift2->lfr2_19, $forklift2->lfr2_20],
            ['Hoist & Lower Control / Lower', $forklift2->lfr2_21, $forklift2->lfr2_22],
        ];

        foreach ($components as [$label, $condition, $note]) {
            $this->mergeText($sheet, 'A'.$row.':C'.$row, $label, 10, true, Alignment::HORIZONTAL_LEFT, true);
            $this->mergeText($sheet, 'D'.$row.':D'.$row, $this->satisfactoryText((string) $condition));
            $this->mergeText($sheet, 'E'.$row.':F'.$row, (string) $note);
            $this->outline('A'.$row.':F'.$row, $sheet);
            $row++;
        }

        $row = $this->sectionTitle($sheet, $row, 'Forks');
        $forkRows = [
            ['ID / Information', $forklift2->lfr2_23],
            ['Thickness', $forklift2->lfr2_24],
            ['Blade Width', $forklift2->lfr2_25],
            ['Blade Length', $forklift2->lfr2_26],
            ['Distance between hooks', $forklift2->lfr2_27],
            ['Shank Height (Back height)', $forklift2->lfr2_28],
            ['Hanger type: Hooks / Tube', $forklift2->lfr2_29],
            ['Shaft diameter', $forklift2->lfr2_30],
        ];
        foreach ($forkRows as [$label, $value]) {
            $row = $this->singleWideRow($sheet, $row, (string) $label, (string) $value);
        }

        $this->mergeText($sheet, 'A'.$row.':C'.($row + 5), '');
        $this->outline('A'.$row.':C'.($row + 5), $sheet);
        $this->addPublicImage($sheet, 'app-assets/images/forklift.jpg', 'A'.$row, 130, 12, 10);
        $this->mergeText($sheet, 'D'.$row.':F'.$row, 'Forks General Condition', 10, true, Alignment::HORIZONTAL_LEFT, true);
        $sheet->setCellValue('D'.($row + 1), str_contains((string) $forklift2->lfr2_31, '_y') ? 'ACCEPT: X' : 'ACCEPT');
        $sheet->setCellValue('E'.($row + 1), str_contains((string) $forklift2->lfr2_31, '_n') ? 'REJECT: X' : 'REJECT');
        $this->headerStyle($sheet, 'D'.($row + 1).':E'.($row + 1));
        $this->outline('D'.$row.':F'.($row + 5), $sheet);
        $row += 6;

        $row = $this->sectionTitle($sheet, $row, 'MPI Details (Inspection Method and equipment used)');
        $row = $this->tripleValueRow($sheet, $row, [
            ['Standard', (string) $forklift2->lfr2_32],
            ['Equipment Type', (string) $forklift2->lfr2_33],
            ['Equipment No', (string) $forklift2->lfr2_34],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Pole spacing', (string) $forklift2->lfr2_35],
            ['Due Date', (string) $forklift2->lfr2_36],
            ['Contrast / Indicator', trim((string) $forklift2->lfr2_37.' / '.(string) $forklift2->lfr2_38, ' /')],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Expire Dates', trim((string) $forklift2->lfr2_39.' / '.(string) $forklift2->lfr2_40, ' /'));
        $row = $this->singleWideRow($sheet, $row, 'Final Conclusion', trim((string) html_entity_decode((string) $forklift2->lfr2_41)), 3);

        $this->mergeText($sheet, 'A'.$row.':D'.($row + 3), '');
        $this->outline('A'.$row.':D'.($row + 3), $sheet);
        $this->addStorageImage($sheet, !empty($forklift2->lfr2_42) ? 'camera/inspection/lifting/forklifts/second/'.$forklift2->lfr2_42 : null, 'A'.$row, 120, 8, 8);
        $this->mergeText($sheet, 'E'.$row.':F'.($row + 3), 'I hereby certify that the Equipment described in this certificate was tested and or examined with accessories gears by a competent person in a manner set forth on the 1st page of this certificate; that a careful examination of the said machinery and gear by a competent person after the test and or examination showed it had withstood with the proof load without injury or permanent deformation and that the Safe Working load of the above describe machinery and gears as shown in page 1', 9, false);
        $this->outline('E'.$row.':F'.($row + 3), $sheet);

        return $row + 4;
    }
}
