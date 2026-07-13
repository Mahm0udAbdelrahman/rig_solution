<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Ndt\Attached;
use App\Models\Inspection\Ndt\High2Pressure;
use App\Models\Inspection\Ndt\High3Pressure;
use App\Models\Inspection\Ndt\HighPressure;
use App\Models\Inspection\Ndt\Summary;
use App\Models\Inspection\Ndt\TreatingIron;
use App\Models\Inspection\Ndt\WitnessHydro;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericNdtReportWorksheetRenderer extends AbstractGenericInspectionWorksheetRenderer
{
    public function render(Worksheet $sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        $model = $report->reportable;
        if (!$model instanceof Model) {
            return;
        }

        $config = $this->configFor($model);
        if ($config === null) {
            return;
        }

        $this->renderGenericModel(
            $sheet,
            $report,
            $model,
            $revisionNumber,
            $config['title'],
            'This report complies with the approved NDT workflow and internal inspection form',
            $config['pdf_directory'],
            $config['primary_fields'],
            $config['description_field'],
            $config['skip_fields']
        );
    }

    private function configFor(Model $model): ?array
    {
        return match ($model::class) {
            TreatingIron::class => [
                'title' => 'Treating Iron Inspection Report',
                'pdf_directory' => 'pdf/inspection/ndt/treatingiron',
                'primary_fields' => [
                    'ntir_7' => 'Next Examination Date',
                    'ntir_9' => 'Reference Standard',
                    'ntir_10' => 'Acceptance Standard',
                    'ntir_11' => 'Instrument / Method',
                    'ntir_13' => 'Identification No',
                    'ntir_14' => 'Serial No',
                ],
                'description_field' => 'desc',
                'skip_fields' => [],
            ],
            WitnessHydro::class => [
                'title' => 'Witness Hydro Test Report',
                'pdf_directory' => 'pdf/inspection/ndt/witnesshydro',
                'primary_fields' => [
                    'nwhr_7' => 'Next Examination Date',
                    'nwhr_8' => 'Color Code',
                    'acceptance' => 'Acceptance Standard',
                    'nwhr_15' => 'Identification No',
                    'nwhr_16' => 'Serial No',
                    'nwhr_17' => 'Certificate No',
                ],
                'description_field' => 'desc',
                'skip_fields' => [],
            ],
            Attached::class => [
                'title' => 'Attached Inspection Report',
                'pdf_directory' => 'pdf/inspection/ndt/attached',
                'primary_fields' => [
                    'nar_4' => 'Examination Date',
                    'nar_6' => 'Attachment / Status',
                ],
                'description_field' => 'desc',
                'skip_fields' => [],
            ],
            HighPressure::class => [
                'title' => 'High Pressure Inspection Report',
                'pdf_directory' => 'pdf/inspection/ndt/highpressure',
                'primary_fields' => [
                    'nhpr_7' => 'Next Examination Date',
                    'edition' => 'Edition',
                    'acceptance' => 'Acceptance Standard',
                    'nhpr_10' => 'Identification No',
                    'nhpr_11' => 'Material',
                    'nhpr_12' => 'Thickness',
                ],
                'description_field' => 'desc',
                'skip_fields' => [],
            ],
            High2Pressure::class => [
                'title' => 'High Pressure Inspection Report 2',
                'pdf_directory' => 'pdf/inspection/ndt/high2pressure',
                'primary_fields' => [
                    'nh2pr_7' => 'Next Examination Date',
                    'edition' => 'Edition',
                    'acceptance' => 'Acceptance Standard',
                    'nh2pr_10' => 'Identification No',
                    'nh2pr_11' => 'Material',
                    'nh2pr_12' => 'Thickness',
                ],
                'description_field' => 'desc',
                'skip_fields' => [],
            ],
            High3Pressure::class => [
                'title' => 'High Pressure Inspection Report 3',
                'pdf_directory' => 'pdf/inspection/ndt/high3pressure',
                'primary_fields' => [
                    'nh3pr_7' => 'Next Examination Date',
                    'edition' => 'Edition',
                    'acceptance' => 'Acceptance Standard',
                    'nh3pr_10' => 'Identification No',
                    'nh3pr_11' => 'Material',
                    'nh3pr_12' => 'Thickness',
                ],
                'description_field' => 'desc',
                'skip_fields' => [],
            ],
            Summary::class => [
                'title' => 'NDT Summary Report',
                'pdf_directory' => 'pdf/inspection/ndt/summary',
                'primary_fields' => [
                    'nsr_4' => 'Examination Date',
                    'nsr_7' => 'Summary Reference',
                    'nsr_8' => 'Summary Notes',
                ],
                'description_field' => 'desc',
                'skip_fields' => [],
            ],
            default => null,
        };
    }
}
