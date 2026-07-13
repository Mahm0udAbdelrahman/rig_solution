<?php

namespace App\Console\Commands;

use App\Models\Inspection\InspectionReport;
use App\Services\Inspection\InspectionReportLifecycleService;
use App\Services\Inspection\InspectionReportStorageService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RepairInspectionPublishState extends Command
{
    private const INSPECTION_TYPE_LIKE = 'App\\\\Models\\\\Inspection\\\\%';

    protected $signature = 'inspection:repair-publish-state
                            {--type= : Full reportable class or basename, e.g. App\\Models\\Inspection\\Ndt\\DrawingInspection or DrawingInspection}
                            {--dry-run : Preview only without persisting changes}';

    protected $description = 'Repair inspection publish state using actual PDF file presence and approval fallback.';

    public function __construct(
        private readonly InspectionReportStorageService $storageService,
        private readonly InspectionReportLifecycleService $lifecycleService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $resolvedType = $this->resolveRequestedType((string) $this->option('type'));
        if ($resolvedType === false) {
            return self::FAILURE;
        }

        $isDryRun = (bool) $this->option('dry-run');
        $reports = InspectionReport::query()
            ->where('reportable_type', 'like', self::INSPECTION_TYPE_LIKE)
            ->when($resolvedType !== null, function ($query) use ($resolvedType) {
                $query->where('reportable_type', $resolvedType);
            })
            ->orderBy('job_request_id')
            ->orderBy('id')
            ->get();

        if ($reports->isEmpty()) {
            $this->info('No inspection reports matched the requested scope.');
            return self::SUCCESS;
        }

        $fixedPublished = 0;
        $resetToNeedPublish = 0;
        $fixedApproval = 0;
        $scanned = 0;

        $runner = function () use ($reports, $isDryRun, &$fixedPublished, &$resetToNeedPublish, &$fixedApproval, &$scanned) {
            foreach ($reports as $report) {
                $scanned++;
                $pdfExists = $this->storageService->publishedPdfExists($report);
                $fallbackApprovedBy = $this->lifecycleService->resolveInheritedApprovedBy($report);
                $updates = [];
                $actions = [];

                if (!empty($report->publish) && !$pdfExists) {
                    $updates['publish'] = null;
                    $actions[] = 'publish->null';
                    $resetToNeedPublish++;
                }

                if (empty($report->publish) && $pdfExists) {
                    $updates['publish'] = 1;
                    $actions[] = 'publish->1';
                    $fixedPublished++;
                }

                $targetPublish = $updates['publish'] ?? $report->publish;
                if (!empty($targetPublish) && empty($report->user_id_approved) && !empty($fallbackApprovedBy)) {
                    $updates['user_id_approved'] = $fallbackApprovedBy;
                    $actions[] = 'approval<-' . $fallbackApprovedBy;
                    $fixedApproval++;
                }

                if (empty($updates)) {
                    continue;
                }

                $this->line('#' . $report->id . ' [' . class_basename((string) $report->reportable_type) . '] ' . implode(' | ', $actions));

                if (!$isDryRun) {
                    InspectionReport::query()->whereKey($report->id)->update($updates);
                }
            }
        };

        if ($isDryRun) {
            $runner();
        } else {
            DB::transaction($runner);
        }

        $this->newLine();
        $this->info('Scanned reports: ' . $scanned);
        $this->info('Rows marked published from existing PDFs: ' . $fixedPublished);
        $this->info('Rows reset to Need Publish due to missing PDFs: ' . $resetToNeedPublish);
        $this->info('Published rows with approval repaired: ' . $fixedApproval);
        $this->info($isDryRun ? 'Dry-run only. No changes were written.' : 'Publish state repair completed.');

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

        if ($availableTypes->contains($requestedType)) {
            return $requestedType;
        }

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
}
