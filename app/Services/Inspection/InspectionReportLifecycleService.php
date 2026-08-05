<?php

namespace App\Services\Inspection;

use App\Models\Inspection\InspectionReport;
use App\Support\InspectionRevisionWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class InspectionReportLifecycleService
{
    public function persistForOwner($reportOwner, array $attributes): bool
    {
        if (!is_object($reportOwner) || !method_exists($reportOwner, 'report')) {
            return false;
        }

        $report = $reportOwner->report()->first();
        if (!$report) {
            return false;
        }

        return $this->persistReport($report, $attributes);
    }

    public function persistReport(InspectionReport $report, array $attributes): bool
    {
        $reportableChanged = array_key_exists('reportable_id', $attributes) || array_key_exists('reportable_type', $attributes);
        $editorStateChanged = array_key_exists('user_id_edit', $attributes);
        $isDuplicatedClone = stripos((string) ($attributes['code'] ?? $report->code), 'duplicated') !== false
            || stripos((string) $report->code, 'duplicated') !== false;
        $isPublishTransition = ((int) ($attributes['publish'] ?? 0) === 1);
        $supportsRevisionWorkflow = InspectionRevisionWorkflow::supports((string) ($attributes['reportable_type'] ?? $report->reportable_type));
        $approvedSourceEditedIntoNewOwner = $supportsRevisionWorkflow
            && !$isDuplicatedClone
            && $reportableChanged
            && !empty($report->user_id_approved);

        if (!$isPublishTransition) {
            $attributes['user_id_approved'] = null;
            $attributes['publish'] = null;

            $this->resetApprovalForReportFamily($report);
        }

        if ($isDuplicatedClone && ($reportableChanged || $editorStateChanged) && !$isPublishTransition) {
            $attributes['publish'] = null;
            $attributes['user_id_approved'] = null;
        }

        if ($isDuplicatedClone && $isPublishTransition) {
            $nextNaturalCode = $this->resolveNextNaturalCode(
                (int) $report->job_request_id,
                (int) $report->id
            );

            if ($nextNaturalCode !== '') {
                $attributes['code'] = $nextNaturalCode;
                $this->syncReportableCode(
                    (string) ($attributes['reportable_type'] ?? $report->reportable_type),
                    (int) ($attributes['reportable_id'] ?? $report->reportable_id),
                    $nextNaturalCode
                );
            }

            // Publishing a clone promotes it into a natural-code revision that still
            // requires approval, so it must leave the duplicated publish queue.
            $attributes['publish'] = null;
            $attributes['user_id_approved'] = null;
        }

        if ($approvedSourceEditedIntoNewOwner) {
            return $this->createPendingRevisionReport($report, $attributes);
        }

        return (bool) InspectionReport::query()
            ->whereKey($report->id)
            ->update($attributes);
    }

    public function createDuplicateRecord(InspectionReport $sourceReport, Model $duplicatedReportable, ?int $actorId = null): InspectionReport
    {
        return $duplicatedReportable->report()->create([
            'job_request_id' => $sourceReport->job_request_id,
            'code' => $sourceReport->code . ' - Duplicated',
            'status' => 1,
            'publish' => null,
            'user_id_approved' => null,
            'sync' => 1,
            'user_id' => $actorId,
        ]);
    }

    public function markPublished(InspectionReport $report): bool
    {
        return $this->persistReport($report, [
            'publish' => 1,
            'user_id_approved' => $report->user_id_approved,
        ]);
    }

    public function resolveInheritedApprovedBy(InspectionReport $sourceReport): ?int
    {
        $approvedBy = (int) ($sourceReport->user_id_approved ?? 0);
        if ($approvedBy > 0) {
            return $approvedBy;
        }

        $fallback = (int) ($sourceReport->user_id_edit ?: $sourceReport->user_id);
        if ($fallback > 0) {
            return $fallback;
        }

        return null;
    }

    public function resolveNextNaturalCode(int $jobRequestId, int $excludeReportId = 0): string
    {
        if ($jobRequestId <= 0) {
            return '';
        }

        $max = InspectionReport::query()
            ->where('job_request_id', $jobRequestId)
            ->when($excludeReportId > 0, function ($query) use ($excludeReportId) {
                $query->where('id', '!=', $excludeReportId);
            })
            ->where('code', 'not like', '%Duplicated%')
            ->pluck('code')
            ->map(function ($code) {
                if (preg_match('/(\d+)$/', (string) $code, $matches)) {
                    return (int) $matches[1];
                }

                return is_numeric($code) ? (int) $code : 0;
            })
            ->max();

        $next = ((int) $max) + 1;

        return $next > 999 ? (string) $next : str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    public function syncReportableCode(string $reportableType, int $reportableId, string $code): void
    {
        if ($reportableType === '' || $reportableId <= 0 || $code === '' || !class_exists($reportableType)) {
            return;
        }

        $reportable = $reportableType::query()->find($reportableId);
        if (!$reportable || !method_exists($reportable, 'getTable')) {
            return;
        }

        $table = (string) $reportable->getTable();
        if ($table === '' || !Schema::hasColumn($table, 'code')) {
            return;
        }

        if ((string) ($reportable->code ?? '') === $code) {
            return;
        }

        $reportable->forceFill(['code' => $code])->save();
    }

    private function createPendingRevisionReport(InspectionReport $sourceReport, array $attributes): bool
    {
        $targetReportableType = (string) ($attributes['reportable_type'] ?? $sourceReport->reportable_type);
        $targetReportableId = (int) ($attributes['reportable_id'] ?? $sourceReport->reportable_id);

        if ($targetReportableType === '' || $targetReportableId <= 0) {
            return false;
        }

        $alreadyExists = InspectionReport::query()
            ->where('reportable_type', $targetReportableType)
            ->where('reportable_id', $targetReportableId)
            ->exists();

        if ($alreadyExists) {
            return false;
        }

        $created = InspectionReport::query()->create([
            'job_request_id' => (int) ($attributes['job_request_id'] ?? $sourceReport->job_request_id),
            'code' => (string) ($attributes['code'] ?? $sourceReport->code),
            'status' => (int) ($attributes['status'] ?? $sourceReport->status ?? 1),
            'publish' => null,
            'user_id' => (int) ($attributes['user_id'] ?? $sourceReport->user_id),
            'user_id_edit' => array_key_exists('user_id_edit', $attributes) ? $attributes['user_id_edit'] : null,
            'user_id_approved' => null,
            'reportable_id' => $targetReportableId,
            'reportable_type' => $targetReportableType,
            'sync' => (int) ($attributes['sync'] ?? $sourceReport->sync ?? 1),
            'updated' => $attributes['updated'] ?? $sourceReport->updated,
        ]);

        return (bool) $created;
    }

    private function resetApprovalForReportFamily(InspectionReport $report): void
    {
        $jobRequestId = (int) ($report->job_request_id ?? 0);
        $code = trim((string) ($report->code ?? ''));
        if ($jobRequestId <= 0 || $code === '') {
            return;
        }

        $baseCode = trim((string) preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', $code));
        if ($baseCode === '') {
            return;
        }

        InspectionReport::query()
            ->where('job_request_id', $jobRequestId)
            ->where(function ($query) use ($baseCode) {
                $query->where('code', $baseCode)
                    ->orWhere('code', 'like', $baseCode . ' - Duplicated%');
            })
            ->update([
                'user_id_approved' => null,
                'publish' => null,
            ]);
    }
}




