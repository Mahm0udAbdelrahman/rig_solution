<?php

namespace App\Services\WorkFlow;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\DropObject\DropObject;
use App\Models\Inspection\Lifting\Crane;
use App\Models\Inspection\Lifting\Defect;
use App\Models\Inspection\Lifting\Forklift;
use App\Models\Inspection\Lifting\Lregister;
use App\Models\Inspection\Lifting\OverheadCrane;
use App\Models\Inspection\Lifting\ThroughExamination;
use App\Models\Inspection\Calibration\CalibrationPressureGauge;
use App\Models\Inspection\Calibration\CalibrationPressureTest;
use App\Models\Inspection\Calibration\CalibrationTorque;
use App\Models\Inspection\Calibration\CalibrationYoke;
use App\Models\Inspection\Ndt\Attached;
use App\Models\Inspection\Ndt\DrawingInspection;
use App\Models\Inspection\Ndt\High2Pressure;
use App\Models\Inspection\Ndt\High3Pressure;
use App\Models\Inspection\Ndt\HighPressure;
use App\Models\Inspection\Ndt\Mpipt;
use App\Models\Inspection\Ndt\Nregister;
use App\Models\Inspection\Ndt\Summary as NdtSummary;
use App\Models\Inspection\Ndt\TreatingIron;
use App\Models\Inspection\Ndt\Ultrasonic;
use App\Models\Inspection\Ndt\Visual;
use App\Models\Inspection\Ndt\WitnessHydro;
use App\Models\Inspection\Tubular\DrillCollar;
use App\Models\Inspection\Tubular\DrillPipe;
use App\Models\Inspection\Tubular\HeavyWeightPipe;
use App\Models\Inspection\Tubular\LinkInspection;
use App\Models\Inspection\Tubular\Pbl;
use App\Models\Inspection\Tubular\PipesSummaryReport;
use App\Models\Inspection\Tubular\ReamerInspection;
use App\Models\Inspection\Tubular\StabilizerInspection;
use App\Models\Inspection\Tubular\SubsDimensional;
use App\Models\Inspection\Tubular\TubingCasing;
use App\Models\Inspection\Tubular\TubingString;
use App\Models\WorkFlow\FileManager;
use App\Services\Inspection\InspectionReportExportMapper;
use App\Services\Inspection\Templates\CraneReportWorksheetRenderer;
use App\Services\Inspection\Templates\DrawingInspectionReportWorksheetRenderer;
use App\Services\Inspection\Templates\DropObjectReportWorksheetRenderer;
use App\Services\Inspection\Templates\ForkliftReportWorksheetRenderer;
use App\Services\Inspection\Templates\GenericCalibrationReportWorksheetRenderer;
use App\Services\Inspection\Templates\GenericNdtReportWorksheetRenderer;
use App\Services\Inspection\Templates\GenericRegisterReportWorksheetRenderer;
use App\Services\Inspection\Templates\GenericTubularReportWorksheetRenderer;
use App\Services\Inspection\Templates\MpiptReportWorksheetRenderer;
use App\Services\Inspection\Templates\OverheadCraneReportWorksheetRenderer;
use App\Services\Inspection\Templates\PblReportWorksheetRenderer;
use App\Services\Inspection\Templates\ThroughExaminationReportWorksheetRenderer;
use App\Services\Inspection\Templates\UltrasonicReportWorksheetRenderer;
use App\Services\Inspection\Templates\VisualReportWorksheetRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use RuntimeException;

class FileManagerInspectionWorkbookExportService
{
    public function __construct(
        private readonly FileManagerExcelExportService $excelExportService,
        private readonly InspectionReportExportMapper $mapper,
        private readonly CraneReportWorksheetRenderer $craneRenderer,
        private readonly ForkliftReportWorksheetRenderer $forkliftRenderer,
        private readonly OverheadCraneReportWorksheetRenderer $overheadCraneRenderer,
        private readonly ThroughExaminationReportWorksheetRenderer $throughExaminationRenderer,
        private readonly DropObjectReportWorksheetRenderer $dropObjectRenderer,
        private readonly PblReportWorksheetRenderer $pblRenderer,
        private readonly MpiptReportWorksheetRenderer $mpiptRenderer,
        private readonly VisualReportWorksheetRenderer $visualRenderer,
        private readonly DrawingInspectionReportWorksheetRenderer $drawingInspectionRenderer,
        private readonly UltrasonicReportWorksheetRenderer $ultrasonicRenderer,
        private readonly GenericNdtReportWorksheetRenderer $genericNdtRenderer,
        private readonly GenericRegisterReportWorksheetRenderer $genericRegisterRenderer,
        private readonly GenericTubularReportWorksheetRenderer $genericTubularRenderer,
        private readonly GenericCalibrationReportWorksheetRenderer $genericCalibrationRenderer,
    ) {
    }

    public function download(FileManager $fileRow)
    {
        if (!$this->supportsFile($fileRow)) {
            throw new RuntimeException('Report Excel export is temporarily enabled only for Nregister and Lregister.');
        }

        $report = $this->resolveExportReport($fileRow);
        if (!$report) {
            throw new RuntimeException('This file is not linked to an inspection report that can be exported.');
        }

        return $this->excelExportService->downloadUsingBuilder(
            $this->buildFilename($report),
            fn (Spreadsheet $spreadsheet) => $this->buildWorkbook($spreadsheet, $report)
        );
    }

    public function downloadForReport(InspectionReport $report)
    {
        $revisions = $this->resolveRevisionReports($report);
        $primaryReport = $revisions->isNotEmpty() ? $revisions->first() : $report;

        return $this->excelExportService->downloadUsingBuilder(
            $this->buildFilename($primaryReport),
            fn (Spreadsheet $spreadsheet) => $this->buildWorkbook($spreadsheet, $primaryReport)
        );
    }

    public function storeTemporaryWorkbook(FileManager $fileRow): array
    {
        if (!$this->supportsFile($fileRow)) {
            throw new RuntimeException('Report Excel export is temporarily enabled only for Nregister and Lregister.');
        }

        $report = $this->resolveExportReport($fileRow);
        if (!$report) {
            throw new RuntimeException('This file is not linked to an inspection report that can be exported.');
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'fm_xlsx_');
        if ($tempPath === false) {
            throw new RuntimeException('Unable to prepare a temporary workbook file.');
        }

        $xlsxPath = $tempPath.'.xlsx';
        @rename($tempPath, $xlsxPath);

        $this->excelExportService->saveUsingBuilder(
            $xlsxPath,
            fn (Spreadsheet $spreadsheet) => $this->buildWorkbook($spreadsheet, $report)
        );

        return [
            'path' => $xlsxPath,
            'filename' => $this->buildFilename($report),
        ];
    }

    public function downloadCombinedWorkbook(iterable $fileRows, ?string $filenameBase = null)
    {
        $reports = collect($fileRows)
            ->filter(function ($fileRow) {
                return $fileRow instanceof FileManager;
            })
            ->map(function (FileManager $fileRow) {
                return $this->resolveExportReport($fileRow);
            })
            ->filter()
            ->unique(function (InspectionReport $report) {
                return $this->buildFamilyKey($report);
            })
            ->values();

        if ($reports->isEmpty()) {
            throw new RuntimeException('No selected files could be resolved to exportable report families.');
        }

        $jobRequestIds = $reports->pluck('job_request_id')->filter()->unique()->values();
        $reportableTypes = $reports->pluck('reportable_type')->filter()->unique()->values();
        if ($jobRequestIds->count() !== 1 || $reportableTypes->count() !== 1) {
            throw new RuntimeException('Combined JCF Type export requires files from exactly one JCF and one inspection type.');
        }

        return $this->excelExportService->downloadUsingBuilder(
            $this->buildCombinedFilename($reports, $filenameBase),
            fn (Spreadsheet $spreadsheet) => $this->buildCombinedWorkbook($spreadsheet, $reports)
        );
    }

    public function resolveExportReport(FileManager $fileRow): ?InspectionReport
    {
        $report = $this->resolveMatchingReport($fileRow);
        if (!$report) {
            return null;
        }

        $revisions = $this->resolveRevisionReports($report);
        if ($revisions->isEmpty()) {
            return null;
        }

        return $revisions->first();
    }

    public function buildFamilyKey(InspectionReport $report): string
    {
        $reportableType = (string) $report->reportable_type;
        $jobRequestId = (int) $report->job_request_id;
        $familyCode = $this->normalizeInspectionBaseCode((string) data_get($report->reportable, 'code', $report->code));

        return implode('|', [
            $reportableType,
            $jobRequestId,
            $familyCode,
        ]);
    }

    public function supportsFile(FileManager $fileRow): bool
    {
        $path = ltrim(str_replace('\\', '/', (string) $fileRow->path), '/');
        if (
            (int) $fileRow->is_inspection !== 1
            || (int) $fileRow->is_available !== 1
            || strtolower((string) $fileRow->extension) !== 'pdf'
            || !str_starts_with($path, 'pdf/inspection/')
        ) {
            return false;
        }

        $entityType = $this->mapper->fileEntityTypeFromPath($fileRow->category, $fileRow->entity_type);
        $reportableType = $this->resolveReportableClassFromEntityType($entityType, (string) $fileRow->category);
        if ($reportableType === null) {
            return false;
        }

        if (!in_array($reportableType, [Lregister::class, Nregister::class], true)) {
            return false;
        }

        return $this->resolveRendererForType($reportableType) !== null;
    }

    private function resolveMatchingReport(FileManager $fileRow): ?InspectionReport
    {
        $jobCode = trim((string) $fileRow->job_request_code);
        $reportCode = trim((string) $fileRow->entity_code);
        $entityType = $this->mapper->fileEntityTypeFromPath($fileRow->category, $fileRow->entity_type);

        if ($jobCode === '' || $reportCode === '') {
            return null;
        }

        $reports = InspectionReport::query()
            ->with([
                'job_request.client',
                'job_request.supplier',
                'job_request.clientDepartment',
                'reportable',
                'user.employee',
            ])
            ->where('code', $reportCode)
            ->whereHas('job_request', function ($query) use ($jobCode) {
                $query->where('code', $jobCode);
            })
            ->get();

        if ($reports->isEmpty()) {
            $fallback = $this->resolveMatchingReportViaReportable($fileRow, $jobCode, $reportCode, $entityType);

            return $fallback ?: $this->resolveMatchingReportViaCodeFamily($fileRow, $jobCode, $reportCode, $entityType);
        }

        if ($entityType !== '') {
            $exact = $reports->first(function (InspectionReport $report) use ($entityType) {
                return $this->mapper->entityTypeFromReport($report) === $entityType;
            });

            if ($exact) {
                return $exact;
            }
        }

        return $reports->first();
    }

    private function resolveMatchingReportViaReportable(FileManager $fileRow, string $jobCode, string $reportCode, string $entityType): ?InspectionReport
    {
        $reportableType = $this->resolveReportableClassFromEntityType($entityType, (string) $fileRow->category);
        if ($reportableType === null) {
            return null;
        }

        /** @var class-string<Model> $reportableType */
        $reportables = $reportableType::query()
            ->whereHas('job_request', function ($query) use ($jobCode) {
                $query->where('code', $jobCode);
            });

        $this->applyInspectionCodeFamilyConstraint($reportables, $reportCode);

        $reportable = $reportables->latest('id')->first();
        if (!$reportable) {
            return null;
        }

        return InspectionReport::query()
            ->with([
                'job_request.client',
                'job_request.supplier',
                'job_request.clientDepartment',
                'reportable',
                'user.employee',
            ])
            ->where('reportable_type', $reportableType)
            ->where('reportable_id', (int) $reportable->id)
            ->latest('id')
            ->first();
    }

    private function resolveMatchingReportViaCodeFamily(FileManager $fileRow, string $jobCode, string $reportCode, string $entityType): ?InspectionReport
    {
        $reportableType = $this->resolveReportableClassFromEntityType($entityType, (string) $fileRow->category);

        $query = InspectionReport::query()
            ->with([
                'job_request.client',
                'job_request.supplier',
                'job_request.clientDepartment',
                'reportable',
                'user.employee',
            ])
            ->whereHas('job_request', function ($builder) use ($jobCode) {
                $builder->where('code', $jobCode);
            });

        if ($reportableType !== null) {
            $query->where('reportable_type', $reportableType);
        }

        $this->applyInspectionCodeFamilyConstraint($query, $reportCode, 'code');

        return $query->latest('id')->first();
    }

    private function resolveReportableClassFromEntityType(string $entityType, string $category = ''): ?string
    {
        $entityType = strtolower(trim($entityType));
        $category = strtolower(trim($category));

        return match (strtolower(trim($entityType))) {
            'crane' => Crane::class,
            'defect' => Defect::class,
            'forklift' => Forklift::class,
            'lregister' => Lregister::class,
            'overheadcrane' => OverheadCrane::class,
            'throughexamination' => ThroughExamination::class,
            'dropobject' => DropObject::class,
            'pbl' => Pbl::class,
            'mpipt' => Mpipt::class,
            'nregister' => Nregister::class,
            'visual' => Visual::class,
            'drawinginspection' => DrawingInspection::class,
            'ultrasonic' => Ultrasonic::class,
            'treatingiron' => TreatingIron::class,
            'witnesshydro' => WitnessHydro::class,
            'attached' => Attached::class,
            'highpressure' => HighPressure::class,
            'high2pressure' => High2Pressure::class,
            'high3pressure' => High3Pressure::class,
            'summary' => str_contains($category, 'tubular/') ? PipesSummaryReport::class : NdtSummary::class,
            'drillpipe' => DrillPipe::class,
            'drillcollar' => DrillCollar::class,
            'heavyweightpipe' => HeavyWeightPipe::class,
            'linkinspection' => LinkInspection::class,
            'reamerinspection' => ReamerInspection::class,
            'stabilizerinspection' => StabilizerInspection::class,
            'subsdimensional' => SubsDimensional::class,
            'tubingcasing' => TubingCasing::class,
            'tubingstring' => TubingString::class,
            'pipessummaryreport' => PipesSummaryReport::class,
            'calibrationpressuregauge' => CalibrationPressureGauge::class,
            'calibrationpressuretest' => CalibrationPressureTest::class,
            'calibrationtorque' => CalibrationTorque::class,
            'calibrationyoke' => CalibrationYoke::class,
            default => null,
        };
    }

    private function resolveRevisionReports(InspectionReport $report): Collection
    {
        $reportable = $report->reportable;
        $reportableType = (string) $report->reportable_type;
        $jobRequestId = (int) $report->job_request_id;
        $code = trim((string) data_get($reportable, 'code', $report->code));

        if (!$reportable || $reportableType === '' || $jobRequestId <= 0 || $code === '' || !class_exists($reportableType)) {
            return collect([$report]);
        }

        /** @var class-string<Model> $reportableType */
        $relatedReportables = $reportableType::query()->where('job_request_id', $jobRequestId);
        $this->applyInspectionCodeFamilyConstraint($relatedReportables, $code);

        return $relatedReportables
            ->orderBy('created_at')
            ->get()
            ->map(function ($relatedReportable) use ($reportableType) {
                return InspectionReport::query()
                    ->with([
                        'job_request.client',
                        'job_request.supplier',
                        'job_request.clientDepartment',
                        'reportable',
                        'user.employee',
                    ])
                    ->where('reportable_type', $reportableType)
                    ->where('reportable_id', (int) $relatedReportable->id)
                    ->latest('id')
                    ->first();
            })
            ->filter()
            ->values();
    }

    private function buildFilename(InspectionReport $report): string
    {
        $jobCode = preg_replace('/[^A-Za-z0-9_-]+/', '-', (string) optional($report->job_request)->code);
        $reportCode = preg_replace('/[^A-Za-z0-9_-]+/', '-', $this->normalizeInspectionBaseCode((string) ($report->code ?? '')));
        $type = strtolower(class_basename((string) $report->reportable_type));
        $base = trim(implode('-', array_filter([$jobCode, $reportCode, $type])), '-');

        return ($base !== '' ? $base : 'inspection-report').'-workbook.xlsx';
    }

    private function buildWorkbook(Spreadsheet $spreadsheet, InspectionReport $report): void
    {
        $revisions = $this->resolveRevisionReports($report);
        if ($revisions->isEmpty()) {
            throw new RuntimeException('No revisions were found for the selected report.');
        }

        foreach ($revisions->values() as $index => $revisionReport) {
            $sheet = $index === 0 ? $spreadsheet->getActiveSheet() : $spreadsheet->createSheet();
            $this->renderSheet($sheet, $revisionReport, $index + 1, $revisions->count());
        }

        $spreadsheet->setActiveSheetIndex(0);
    }

    private function buildCombinedWorkbook(Spreadsheet $spreadsheet, Collection $reports): void
    {
        $existingTitles = [];
        $sheetIndex = 0;

        foreach ($reports as $report) {
            $familyLabel = $this->normalizeInspectionBaseCode((string) data_get($report->reportable, 'code', $report->code));
            $familyLabel = $familyLabel !== '' ? $familyLabel : 'Report';

            $revisions = $this->resolveRevisionReports($report);
            if ($revisions->isEmpty()) {
                continue;
            }

            foreach ($revisions->values() as $revisionIndex => $revisionReport) {
                $sheet = $sheetIndex === 0 ? $spreadsheet->getActiveSheet() : $spreadsheet->createSheet();
                $this->renderSheet($sheet, $revisionReport, $revisionIndex + 1, $revisions->count());

                $desiredTitle = $revisions->count() > 1
                    ? $familyLabel.' R'.str_pad((string) ($revisionIndex + 1), 2, '0', STR_PAD_LEFT)
                    : $familyLabel;

                $resolvedTitle = $this->resolveUniqueSheetTitle($desiredTitle, $existingTitles);
                $sheet->setTitle($resolvedTitle);
                $existingTitles[] = $resolvedTitle;
                $sheetIndex++;
            }
        }

        if ($sheetIndex === 0) {
            throw new RuntimeException('No workbook sheets could be generated for the selected JCF type.');
        }

        $spreadsheet->setActiveSheetIndex(0);
    }

    private function renderSheet($sheet, InspectionReport $report, int $revisionNumber, int $revisionCount): void
    {
        $renderer = $this->resolveRendererForType((string) $report->reportable_type);

        if ($renderer === null) {
            throw new RuntimeException('Template workbook export is not available yet for '.class_basename((string) $report->reportable_type).'.');
        }

        $renderer->render($sheet, $report, $revisionNumber, $revisionCount);
    }

    private function buildCombinedFilename(Collection $reports, ?string $filenameBase = null): string
    {
        if ($filenameBase !== null && trim($filenameBase) !== '') {
            $base = preg_replace('/[^A-Za-z0-9_-]+/', '-', trim($filenameBase));

            return ($base !== '' ? $base : 'inspection-reports').'.xlsx';
        }

        /** @var InspectionReport|null $firstReport */
        $firstReport = $reports->first();
        $jobCode = preg_replace('/[^A-Za-z0-9_-]+/', '-', (string) optional($firstReport?->job_request)->code);
        $type = strtolower(class_basename((string) $firstReport?->reportable_type));
        $base = trim(implode('-', array_filter([$jobCode, $type, 'all-reports'])), '-');

        return ($base !== '' ? $base : 'inspection-reports').'.xlsx';
    }

    private function resolveUniqueSheetTitle(string $title, array $existingTitles): string
    {
        $baseTitle = $this->normalizeSheetTitle($title);
        $candidate = $baseTitle;
        $counter = 2;

        while (in_array($candidate, $existingTitles, true)) {
            $suffix = ' '.$counter;
            $candidate = mb_substr($baseTitle, 0, max(1, 31 - mb_strlen($suffix))).$suffix;
            $counter++;
        }

        return $candidate;
    }

    private function normalizeSheetTitle(string $sheetTitle): string
    {
        $normalized = preg_replace('/[\\\\\\/\\?\\*\\:\\[\\]]+/', ' ', trim($sheetTitle));
        $normalized = $normalized !== '' ? $normalized : 'Export';

        return mb_substr($normalized, 0, 31);
    }

    private function resolveRendererForType(string $reportableType): mixed
    {
        return match ($reportableType) {
            Crane::class,
            Forklift::class,
            OverheadCrane::class,
            ThroughExamination::class,
            DropObject::class,
            Pbl::class,
            Mpipt::class,
            Visual::class,
            DrawingInspection::class,
            Ultrasonic::class => null,
            TreatingIron::class,
            WitnessHydro::class,
            Attached::class,
            HighPressure::class,
            High2Pressure::class,
            High3Pressure::class,
            NdtSummary::class => null,
            Defect::class,
            Lregister::class,
            Nregister::class => $this->genericRegisterRenderer,
            DrillPipe::class,
            DrillCollar::class,
            HeavyWeightPipe::class,
            LinkInspection::class,
            ReamerInspection::class,
            StabilizerInspection::class,
            SubsDimensional::class,
            TubingCasing::class,
            TubingString::class,
            PipesSummaryReport::class => null,
            CalibrationPressureGauge::class,
            CalibrationPressureTest::class,
            CalibrationTorque::class,
            CalibrationYoke::class => null,
            default => null,
        };
    }

    private function normalizeInspectionBaseCode(string $code): string
    {
        $code = trim($code);
        if ($code === '') {
            return '';
        }

        return trim((string) preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', $code));
    }

    private function escapeLikeValue(string $value): string
    {
        return addcslashes($value, '\\%_');
    }

    private function applyInspectionCodeFamilyConstraint($query, string $code, string $column = 'code'): void
    {
        $exactCode = trim($code);
        if ($exactCode === '') {
            return;
        }

        $baseCode = $this->normalizeInspectionBaseCode($exactCode);
        if ($baseCode === '') {
            $query->where($column, $exactCode);
            return;
        }

        $duplicatedPattern = $this->escapeLikeValue($baseCode).' - Duplicated%';
        $query->where(function ($builder) use ($column, $baseCode, $exactCode, $duplicatedPattern) {
            $builder->where($column, $baseCode)
                ->orWhere($column, 'like', $duplicatedPattern);

            if (strcasecmp($exactCode, $baseCode) !== 0) {
                $builder->orWhere($column, $exactCode);
            }
        });
    }
}
