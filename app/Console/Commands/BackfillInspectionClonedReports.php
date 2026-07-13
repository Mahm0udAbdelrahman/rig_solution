<?php

namespace App\Console\Commands;

use App\Models\Inspection\InspectionReport;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BackfillInspectionClonedReports extends Command
{
    private const INSPECTION_TYPE_LIKE = 'App\\\\Models\\\\Inspection\\\\%';

    protected $signature = 'inspection:backfill-cloned-reports
                            {--type= : Full reportable class or basename, e.g. App\\Models\\Inspection\\Ndt\\DrawingInspection or DrawingInspection}
                            {--dry-run : Preview only without persisting changes}';

    protected $description = 'Backfill inspection cloned reports so approved-source duplicates surface as publish-ready and edited clones move to the next natural serial.';

    public function handle(): int
    {
        $resolvedType = $this->resolveRequestedType((string) $this->option('type'));
        if ($resolvedType === false) {
            return self::FAILURE;
        }

        $isDryRun = (bool) $this->option('dry-run');

        $query = InspectionReport::query()
            ->where('code', 'like', '%Duplicated%')
            ->where('reportable_type', 'like', self::INSPECTION_TYPE_LIKE);

        if ($resolvedType !== null) {
            $query->where('reportable_type', $resolvedType);
        }

        $reports = $query
            ->orderBy('job_request_id')
            ->orderBy('updated_at')
            ->orderBy('id')
            ->get();

        if ($reports->isEmpty()) {
            $this->info('No duplicated inspection reports matched the requested scope.');
            return self::SUCCESS;
        }

        $maxNaturalCodeCache = [];
        $approvalBackfilled = 0;
        $recodedEditedClones = 0;
        $scanned = 0;

        $runner = function () use (
            $reports,
            $isDryRun,
            &$maxNaturalCodeCache,
            &$approvalBackfilled,
            &$recodedEditedClones,
            &$scanned
        ) {
            foreach ($reports as $report) {
                $scanned++;
                $baseCode = $this->normalizeDuplicatedBaseCode((string) $report->code);
                if ($baseCode === '') {
                    continue;
                }

                $source = InspectionReport::query()
                    ->where('job_request_id', $report->job_request_id)
                    ->where('reportable_type', $report->reportable_type)
                    ->where('code', $baseCode)
                    ->where('code', 'not like', '%Duplicated%')
                    ->orderByDesc('id')
                    ->first();

                $dirty = false;
                $logParts = [];

                $sourceApprovedBy = $this->resolveInheritedApprovedBy($source);
                if ($source && !empty($sourceApprovedBy) && empty($report->user_id_approved)) {
                    $approvalBackfilled++;
                    $logParts[] = 'approval<-' . $sourceApprovedBy;
                    if (!$isDryRun) {
                        $report->user_id_approved = $sourceApprovedBy;
                    }
                    $dirty = true;
                }

                if (!empty($report->user_id_edit)) {
                    $nextNaturalCode = $this->resolveNextNaturalCode($report, $maxNaturalCodeCache);
                    if ($nextNaturalCode !== '' && (string) $report->code !== $nextNaturalCode) {
                        $recodedEditedClones++;
                        $logParts[] = 'code:' . $report->code . '->' . $nextNaturalCode;
                        if (!$isDryRun) {
                            $report->code = $nextNaturalCode;
                            $this->syncReportableCode($report, $nextNaturalCode);
                        }
                        $dirty = true;
                    }
                }

                if ($dirty && !$isDryRun) {
                    $report->save();
                }

                if (!empty($logParts)) {
                    $this->line('#' . $report->id . ' [' . class_basename($report->reportable_type) . '] ' . implode(' | ', $logParts));
                }
            }
        };

        if ($isDryRun) {
            $runner();
        } else {
            DB::transaction($runner);
        }

        $this->newLine();
        $this->info('Scanned duplicated clones: ' . $scanned);
        $this->info('Approval backfilled: ' . $approvalBackfilled);
        $this->info('Edited clones recoded: ' . $recodedEditedClones);
        $this->info($isDryRun ? 'Dry-run only. No changes were written.' : 'Backfill completed.');

        return self::SUCCESS;
    }

    private function resolveRequestedType(string $requestedType): string|null|false
    {
        $requestedType = trim($requestedType);
        if ($requestedType === '') {
            return null;
        }

        if (class_exists($requestedType) && is_subclass_of($requestedType, Model::class)) {
            return $requestedType;
        }

        $availableTypes = InspectionReport::query()
            ->where('reportable_type', 'like', self::INSPECTION_TYPE_LIKE)
            ->distinct()
            ->pluck('reportable_type');

        $match = $availableTypes->first(function ($type) use ($requestedType) {
            return strtolower(class_basename($type)) === strtolower($requestedType);
        });

        if ($match) {
            return $match;
        }

        $guessedNamespaces = [
            'App\\Models\\Inspection\\Lifting\\',
            'App\\Models\\Inspection\\Ndt\\',
            'App\\Models\\Inspection\\Tubular\\',
            'App\\Models\\Inspection\\DropObject\\',
            'App\\Models\\Inspection\\Calibration\\',
        ];

        foreach ($guessedNamespaces as $namespace) {
            $candidate = $namespace . $requestedType;
            if (class_exists($candidate) && is_subclass_of($candidate, Model::class)) {
                return $candidate;
            }
        }

        $this->error('Unknown inspection reportable type: ' . $requestedType);
        return false;
    }

    private function normalizeDuplicatedBaseCode(string $code): string
    {
        $code = trim($code);
        if ($code === '') {
            return '';
        }

        return trim((string) preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', $code));
    }

    private function resolveNextNaturalCode(InspectionReport $report, array &$maxNaturalCodeCache): string
    {
        $jobRequestId = (int) $report->job_request_id;
        if ($jobRequestId <= 0) {
            return '';
        }

        if (!array_key_exists($jobRequestId, $maxNaturalCodeCache)) {
            $maxNaturalCodeCache[$jobRequestId] = (int) (InspectionReport::query()
                ->where('job_request_id', $jobRequestId)
                ->where('code', 'not like', '%Duplicated%')
                ->pluck('code')
                ->map(function ($code) {
                    if (preg_match('/(\d+)$/', (string) $code, $matches)) {
                        return (int) $matches[1];
                    }

                    return is_numeric($code) ? (int) $code : 0;
                })
                ->max() ?? 0);
        }

        $maxNaturalCodeCache[$jobRequestId]++;
        $next = $maxNaturalCodeCache[$jobRequestId];

        return $next > 999 ? (string) $next : str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    private function syncReportableCode(InspectionReport $report, string $nextNaturalCode): void
    {
        $reportableType = (string) $report->reportable_type;
        $reportableId = (int) $report->reportable_id;

        if ($nextNaturalCode === '' || $reportableType === '' || $reportableId <= 0 || !class_exists($reportableType)) {
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

        if ((string) ($reportable->code ?? '') === $nextNaturalCode) {
            return;
        }

        $reportable->forceFill(['code' => $nextNaturalCode])->save();
    }

    private function resolveInheritedApprovedBy(?InspectionReport $source): ?int
    {
        if (!$source) {
            return null;
        }

        $approvedBy = (int) ($source->user_id_approved ?? 0);
        if ($approvedBy > 0) {
            return $approvedBy;
        }

        $fallback = (int) ($source->user_id_edit ?: $source->user_id);
        return $fallback > 0 ? $fallback : null;
    }
}
