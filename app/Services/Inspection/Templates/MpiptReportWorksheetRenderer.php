<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Ndt\Mpipt;
use App\Models\User;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MpiptReportWorksheetRenderer extends AbstractLiftingReportWorksheetRenderer
{
    public function render(Worksheet $sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        /** @var Mpipt|null $mpipt */
        $mpipt = $report->reportable;
        if (!$mpipt instanceof Mpipt) {
            return;
        }

        $mpipt->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment');
        $report->loadMissing('job_request.client', 'job_request.supplier', 'job_request.clientDepartment', 'user.employee');

        $approvedUser = !empty($report->user_id_approved) ? User::query()->with('employee')->find($report->user_id_approved) : null;
        $footerValues = $mpipt->footer_values;

        $this->bootSheet($sheet, 'REV '.str_pad((string) $revisionNumber, 2, '0', STR_PAD_LEFT));
        $this->configureColumns($sheet);
        $this->addHeaderImages($sheet, $report);

        $row = $this->renderStandardHeader(
            $sheet,
            'MT or PT Inspection Report',
            'This report complies with the approved NDT workflow and internal inspection form'
        );

        $row = $this->labeledValueRow($sheet, $row, 'Name of employer for whom the examination was made', $this->clientName($report), 'Address of premises at examination was made', $this->clientLocation($report));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Purchase Order', (string) (optional($report->job_request)->purchase_order ?: $mpipt->nmpr_2)],
            ['JCF Number', (string) optional($report->job_request)->code],
            ['Report No', $this->reportNumberWithRevision($report, (string) $mpipt->code, $revisionNumber)],
        ]);
        $row = $this->labeledValueRow($sheet, $row, 'Work location', $this->workLocation($report), 'Examination Date', (string) $mpipt->nmpr_6);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Specification', $this->specificationText($mpipt)],
            ['Other Spec / Edition', trim(implode(' | ', array_filter([(string) $mpipt->nmpr_7, (string) $mpipt->nmpr_10])))],
            ['Acceptance Criteria', (string) $mpipt->acceptance],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Description', (string) $mpipt->desc, 2);
        $row = $this->singleWideRow($sheet, $row, 'Identification No', (string) $mpipt->nmpr_28);

        $row = $this->sectionTitle($sheet, $row, 'Viewing Conditions / Intensity');
        $row = $this->tripleValueRow($sheet, $row, [
            ['Viewing Conditions', $this->selectedOptions($mpipt->nmpr_800, ['visible_day_light' => 'Visible / Day light', 'fluorescent' => 'Fluorescent', 'black_light' => 'Black Light'])],
            ['Intensity', $this->selectedOptions($mpipt->nmpr_900, ['lux' => '>1076 Lux', '215lux' => '>21.5 Lux', 'nm' => '365nm'])],
            ['Temperature', $this->temperatureValue($mpipt)],
        ]);

        $row = $this->sectionTitle($sheet, $row, 'Magnetic Particle Testing');
        $row = $this->singleWideRow($sheet, $row, 'MT Procedure / Type', trim(implode(' | ', array_filter([
            'Procedure: '.$mpipt->getMtvalue('nmpr_13', 'nmpr_12'),
            $mpipt->getMtvalue('ac', 'nmpr_12') === 'on' ? 'AC' : '',
            $mpipt->getMtvalue('dc', 'nmpr_12') === 'on' ? 'DC' : '',
            $mpipt->getMtvalue('permanent-magnet', 'nmpr_12') === 'on' ? 'Permanent Magnet' : '',
        ]))));
        $row = $this->tripleValueRow($sheet, $row, [
            ['Magnetic Field / Demagnetization', trim(implode(' | ', array_filter([
                $mpipt->getMtvalue('active', 'nmpr_12') === 'on' ? 'Active' : '',
                $mpipt->getMtvalue('residual', 'nmpr_12') === 'on' ? 'Residual' : '',
                'Demag: '.$mpipt->getMtvalue('nmpr_3401', 'nmpr_12'),
            ])))],
            ['Equipment No', trim(implode(' | ', array_filter([
                $mpipt->getMtvalue('nmpr_14', 'nmpr_12'),
                $mpipt->getMtvalue('nmpr_15', 'nmpr_12'),
                $mpipt->getMtvalue('nmpr_16', 'nmpr_12'),
            ])))],
            ['Manufacturers', trim(implode(' | ', array_filter([
                $mpipt->getMtvalue('nmpr_17', 'nmpr_12'),
                $mpipt->getMtvalue('nmpr_18', 'nmpr_12'),
                $mpipt->getMtvalue('nmpr_19', 'nmpr_12'),
            ])))],
        ]);
        $row = $this->tripleValueRow($sheet, $row, [
            ['Calibr. Due Dates', trim(implode(' | ', array_filter([
                $mpipt->getMtvalue('nmpr_20', 'nmpr_12'),
                $mpipt->getMtvalue('nmpr_21', 'nmpr_12'),
                $mpipt->getMtvalue('nmpr_22', 'nmpr_12'),
            ])))],
            ['Test Criteria', trim(implode(' | ', array_filter([
                $mpipt->getMtvalue('nmpr_23', 'nmpr_12'),
                $mpipt->getMtvalue('nmpr_24', 'nmpr_12'),
                $mpipt->getMtvalue('nmpr_25', 'nmpr_12'),
            ])))],
            ['Solution / Contrast / Indicator', trim(implode(' | ', array_filter([
                $mpipt->getMtvalue('nmpr_35', 'nmpr_12'),
                $mpipt->getMtvalue('nmpr_36', 'nmpr_12'),
                $mpipt->getMtvalue('nmpr_37', 'nmpr_12'),
            ])))],
        ]);

        $row = $this->sectionTitle($sheet, $row, 'Liquid Penetrant Testing');
        $row = $this->tripleValueRow($sheet, $row, [
            ['PT Procedure', (string) $mpipt->getMtvalue('nmpr_27', 'nmpr_13')],
            ['Spray Details', trim(implode(' | ', array_filter([
                $mpipt->getMtvalue('nmpr_28', 'nmpr_13'),
                $mpipt->getMtvalue('nmpr_29', 'nmpr_13'),
                $mpipt->getMtvalue('nmpr_30', 'nmpr_13'),
            ])))],
            ['Expire Dates', trim(implode(' | ', array_filter([
                $mpipt->getMtvalue('nmpr_31', 'nmpr_13'),
                $mpipt->getMtvalue('nmpr_32', 'nmpr_13'),
                $mpipt->getMtvalue('nmpr_33', 'nmpr_13'),
            ])))],
        ]);
        $row = $this->singleWideRow($sheet, $row, 'Method Description & Pre-Cleaning Method', trim(implode(' | ', array_filter([
            $mpipt->getMtvalue('water-washable', 'nmpr_13') === 'on' ? 'Water Washable' : '',
            $mpipt->getMtvalue('solvent-removable', 'nmpr_13') === 'on' ? 'Solvent Removable' : '',
            $mpipt->getMtvalue('other700', 'nmpr_13') === 'on' ? 'Other' : '',
            $mpipt->getMtvalue('spraying', 'nmpr_13') === 'on' ? 'Spraying' : '',
            $mpipt->getMtvalue('brushing', 'nmpr_13') === 'on' ? 'Brushing' : '',
            $mpipt->getMtvalue('immersion', 'nmpr_13') === 'on' ? 'Immersion' : '',
            'Developer Apply: '.$mpipt->getMtvalue('nmpr_43', 'nmpr_13'),
        ]))), 2);

        $row = $this->sectionTitle($sheet, $row, 'Inspection Summary');
        $summaries = json_decode((string) $mpipt->nmpr_29);
        if (!is_array($summaries) && !$summaries instanceof \Traversable) {
            $summaries = [];
        }

        foreach ((array) $summaries as $index => $summary) {
            $this->mergeText($sheet, 'A'.$row.':D'.$row, 'Summary '.($index + 1), 10, true, Alignment::HORIZONTAL_LEFT, true);
            $this->mergeText($sheet, 'E'.$row.':F'.$row, '');
            $this->outline('A'.$row.':F'.($row + 3), $sheet);
            $sheet->mergeCells('A'.($row + 1).':D'.($row + 3));
            $sheet->setCellValue('A'.($row + 1), (string) data_get($summary, 'nmpr_49'));
            $sheet->getStyle('A'.($row + 1).':D'.($row + 3))->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);

            $storedPhoto = trim((string) data_get($summary, 'nmpr_50'));
            $photoPath = $storedPhoto === ''
                ? null
                : (Str::startsWith($storedPhoto, 'camera/inspection/ndt/mpipts/')
                    ? ltrim($storedPhoto, '/')
                    : 'camera/inspection/ndt/mpipts/'.ltrim($storedPhoto, '/'));
            $this->addStorageImage($sheet, $photoPath, 'E'.($row + 1), 80, 8, 8);

            for ($i = 0; $i < 4; $i++) {
                $sheet->getRowDimension($row + $i)->setRowHeight(24);
            }
            $row += 4;
        }

        if (empty((array) $summaries)) {
            $row = $this->singleWideRow($sheet, $row, 'Summary', 'No summary notes attached.', 2);
        }

        $row = $this->singleWideRow($sheet, $row, 'Final Conclusion', str_contains((string) $mpipt->nmpr_30, '_y') ? 'Accept' : (str_contains((string) $mpipt->nmpr_30, '_n') ? 'Reject' : ''), 1);

        $row++;
        $this->footerBlock(
            $sheet,
            $row,
            $report,
            $approvedUser,
            url('storage/pdf/inspection/ndt/mpipt/'.optional($report->job_request)->code.'/'.optional($report->reportable)->code.'.pdf'),
            data_get($footerValues, 'form_no'),
            data_get($footerValues, 'issue_no'),
            data_get($footerValues, 'issue_date'),
            data_get($footerValues, 'revision_no'),
            data_get($footerValues, 'revision_date')
        );
    }

    private function specificationText(Mpipt $mpipt): string
    {
        return $this->selectedOptions($mpipt->nmpr_8, [
            'api' => 'API',
            'astm' => 'ASTM',
            'asme' => 'ASME',
            'cutomer-spec' => 'Customer Spec',
            'other' => 'Other',
        ]);
    }

    private function selectedOptions($json, array $map): string
    {
        $decoded = json_decode((string) $json, true);
        if (!is_array($decoded)) {
            return '';
        }

        $labels = [];
        foreach ($decoded as $value) {
            $key = (string) $value;
            $labels[] = $map[$key] ?? $key;
        }

        return implode(', ', array_filter($labels));
    }

    private function temperatureValue(Mpipt $mpipt): string
    {
        $temperatureValue = $mpipt->getMtvalue('nmpr_46', 'nmpr_13');
        if (($temperatureValue === 'N/A' || $temperatureValue === '') && !empty($mpipt->nmpr_46)) {
            $temperatureValue = $mpipt->nmpr_46;
        }

        return (string) ($temperatureValue ?: 'N/A');
    }
}
