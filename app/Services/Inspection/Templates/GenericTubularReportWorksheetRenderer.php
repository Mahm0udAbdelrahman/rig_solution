<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Tubular\DrillCollar;
use App\Models\Inspection\Tubular\DrillPipe;
use App\Models\Inspection\Tubular\HeavyWeightPipe;
use App\Models\Inspection\Tubular\LinkInspection;
use App\Models\Inspection\Tubular\PipesSummaryReport;
use App\Models\Inspection\Tubular\ReamerInspection;
use App\Models\Inspection\Tubular\StabilizerInspection;
use App\Models\Inspection\Tubular\SubsDimensional;
use App\Models\Inspection\Tubular\TubingCasing;
use App\Models\Inspection\Tubular\TubingString;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericTubularReportWorksheetRenderer extends AbstractGenericInspectionWorksheetRenderer
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
            'This report complies with the tubular inspection workflow and approved internal form',
            $config['pdf_directory'],
            $config['primary_fields'],
            $config['description_field'],
            $config['skip_fields']
        );
    }

    private function configFor(Model $model): ?array
    {
        return match ($model::class) {
            DrillPipe::class => $this->tubularConfig('Drill Pipe Inspection Report', 'drillPipe', 'joint_description'),
            DrillCollar::class => $this->tubularConfig('Drill Collar Inspection Report', 'drillCollar', 'joint_description'),
            HeavyWeightPipe::class => $this->tubularConfig('Heavy Weight Pipe Inspection Report', 'heavyWeightPipe', 'joint_description'),
            LinkInspection::class => $this->tubularConfig('Link Inspection Report', 'linkInspection', 'material_description'),
            ReamerInspection::class => $this->tubularConfig('Reamer Inspection Report', 'reamerInspection', 'material_description'),
            StabilizerInspection::class => $this->tubularConfig('Stabilizer Inspection Report', 'stabilizerInspection', 'material_description'),
            SubsDimensional::class => $this->tubularConfig('Subs Dimensional Report', 'subsDimensional', 'material_description'),
            TubingCasing::class => $this->tubularConfig('Tubing / Casing Report', 'tubingCasing', 'joint_description'),
            TubingString::class => $this->tubularConfig('Tubing String Report', 'tubingString', 'joint_description'),
            PipesSummaryReport::class => [
                'title' => 'Pipes Summary Report',
                'pdf_directory' => 'pdf/inspection/tubular/summary',
                'primary_fields' => [
                    'examination_date' => 'Examination Date',
                    'edition' => 'Edition',
                    'pipe_status' => 'Pipe Status',
                    'nominal_size' => 'Nominal Size',
                    'pipe_grade' => 'Pipe Grade',
                    'weight' => 'Weight',
                ],
                'description_field' => 'comment',
                'skip_fields' => [],
            ],
            default => null,
        };
    }

    private function tubularConfig(string $title, string $directory, string $descriptionField): array
    {
        return [
            'title' => $title,
            'pdf_directory' => 'pdf/inspection/tubular/'.$directory,
            'primary_fields' => [
                'examination_date' => 'Examination Date',
                'edition' => 'Edition',
                'joint_class' => 'Joint Class',
                'grade' => 'Grade',
                'range' => 'Range',
                'weight' => 'Weight',
                'nom_w_t' => 'Nom. W.T',
                'conn' => 'Connection',
            ],
            'description_field' => $descriptionField,
            'skip_fields' => [],
        ];
    }
}
