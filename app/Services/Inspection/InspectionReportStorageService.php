<?php

namespace App\Services\Inspection;

use App\Models\Inspection\InspectionReport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class InspectionReportStorageService
{
    public function candidatePdfPaths(InspectionReport $report): array
    {
        $section = $this->resolveSectionKey((string) $report->reportable_type);
        $slug = strtolower(class_basename((string) $report->reportable_type));
        $reportCode = trim((string) $report->code);

        if ($section === '' || $slug === '' || $reportCode === '') {
            return [];
        }

        $candidates = [];
        $jobRequestCode = $this->resolveJobRequestCode($report);
        $reportableCode = $this->resolveReportableCode($report);

        foreach (array_unique(array_filter([$reportCode, $reportableCode])) as $code) {
            if ($jobRequestCode !== '') {
                $candidates[] = 'pdf/inspection/' . $section . '/' . $slug . '/' . $jobRequestCode . '/' . $code . '.pdf';
            }

            $candidates[] = 'pdf/inspection/' . $section . '/' . $slug . '/' . $code . '.pdf';
        }

        return array_values(array_unique($candidates));
    }

    public function publishedPdfExists(InspectionReport $report): bool
    {
        foreach ($this->candidatePdfPaths($report) as $path) {
            if (Storage::disk('public')->exists($path)) {
                return true;
            }
        }

        return false;
    }

    public function resolveExistingPdfPath(InspectionReport $report): ?string
    {
        foreach ($this->candidatePdfPaths($report) as $path) {
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }

        return null;
    }

    private function resolveSectionKey(string $reportableType): string
    {
        $parts = explode('\\', $reportableType);
        $inspectionIndex = array_search('Inspection', $parts, true);
        if ($inspectionIndex === false) {
            return '';
        }

        return strtolower((string) ($parts[$inspectionIndex + 1] ?? ''));
    }

    private function resolveJobRequestCode(InspectionReport $report): string
    {
        $reportable = $this->resolveReportable($report);
        if (!$reportable || !method_exists($reportable, 'job_request')) {
            return '';
        }

        $jobRequest = $reportable->relationLoaded('job_request')
            ? $reportable->getRelation('job_request')
            : $reportable->job_request()->first();

        return trim((string) ($jobRequest->code ?? ''));
    }

    private function resolveReportableCode(InspectionReport $report): string
    {
        $reportable = $this->resolveReportable($report);
        if (!$reportable || !method_exists($reportable, 'getTable')) {
            return '';
        }

        $table = (string) $reportable->getTable();
        if ($table === '' || !Schema::hasColumn($table, 'code')) {
            return '';
        }

        return trim((string) ($reportable->code ?? ''));
    }

    private function resolveReportable(InspectionReport $report): mixed
    {
        if ($report->relationLoaded('reportable')) {
            return $report->getRelation('reportable');
        }

        $reportableType = (string) $report->reportable_type;
        $reportableId = (int) $report->reportable_id;
        if ($reportableType === '' || $reportableId <= 0 || !class_exists($reportableType)) {
            return null;
        }

        return $reportableType::query()->find($reportableId);
    }
}
