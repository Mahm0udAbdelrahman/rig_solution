<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Lifting\Defect;
use App\Models\Inspection\Lifting\Lregister;
use App\Models\Inspection\Ndt\Nregister;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericRegisterReportWorksheetRenderer extends AbstractGenericInspectionWorksheetRenderer
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
            $config['subtitle'],
            $config['pdf_directory'],
            $config['primary_fields'],
            $config['description_field'],
            $config['skip_fields']
        );
    }

    private function configFor(Model $model): ?array
    {
        return match ($model::class) {
            Defect::class => [
                'title' => 'Lifting Defect Report',
                'subtitle' => 'This report follows the approved lifting defect workflow and internal form',
                'pdf_directory' => 'pdf/inspection/lifting/defect',
                'primary_fields' => [
                    'ldr_6' => 'Examination Date',
                ],
                'description_field' => 'ldr_8',
                'skip_fields' => ['ldr_2'],
            ],
            Lregister::class => [
                'title' => 'Lifting Register',
                'subtitle' => 'This report follows the lifting register workflow and internal form',
                'pdf_directory' => 'pdf/inspection/lifting/lregister',
                'primary_fields' => [
                    'date' => 'Register Date',
                    'color_code' => 'Color Code',
                ],
                'description_field' => null,
                'skip_fields' => [],
            ],
            Nregister::class => [
                'title' => 'NDT Register',
                'subtitle' => 'This report follows the NDT register workflow and internal form',
                'pdf_directory' => 'pdf/inspection/ndt/nregister',
                'primary_fields' => [
                    'register_date' => 'Register Date',
                ],
                'description_field' => null,
                'skip_fields' => [],
            ],
            default => null,
        };
    }
}
