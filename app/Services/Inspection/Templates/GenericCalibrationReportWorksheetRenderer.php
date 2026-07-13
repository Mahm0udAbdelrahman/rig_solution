<?php

namespace App\Services\Inspection\Templates;

use App\Models\Inspection\Calibration\CalibrationPressureGauge;
use App\Models\Inspection\Calibration\CalibrationPressureTest;
use App\Models\Inspection\Calibration\CalibrationTorque;
use App\Models\Inspection\Calibration\CalibrationYoke;
use App\Models\Inspection\InspectionReport;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericCalibrationReportWorksheetRenderer extends AbstractGenericInspectionWorksheetRenderer
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
            'This report complies with the calibration workflow and approved internal form',
            $config['pdf_directory'],
            $config['primary_fields'],
            null,
            ['statement', 'calibration_details']
        );
    }

    private function configFor(Model $model): ?array
    {
        return match ($model::class) {
            CalibrationPressureGauge::class => $this->calibrationConfig('Calibration Certificate (Pressure Gauge)', 'calibrationPressureGauge'),
            CalibrationPressureTest::class => $this->calibrationConfig('Calibration Certificate (Pressure Test)', 'calibrationPressureTest'),
            CalibrationTorque::class => $this->calibrationConfig('Calibration Certificate (Torque)', 'calibrationTorque'),
            CalibrationYoke::class => $this->calibrationConfig('Calibration Certificate (Yoke)', 'calibrationYoke'),
            default => null,
        };
    }

    private function calibrationConfig(string $title, string $directory): array
    {
        return [
            'title' => $title,
            'pdf_directory' => 'pdf/inspection/calibration/'.$directory,
            'primary_fields' => [
                'receipt_date' => 'Receipt Date',
                'calibration_date' => 'Calibration Date',
                'due_date' => 'Due Date',
                'issue_date' => 'Issue Date',
                'serial_number' => 'Serial Number',
                'certificate_number' => 'Certificate Number',
                'manufacturer' => 'Manufacturer',
                'model' => 'Model',
                'range' => 'Range',
            ],
            'description_field' => null,
            'skip_fields' => [],
        ];
    }
}
