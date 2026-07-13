<?php

namespace App\Console\Commands;

use App\Models\Inspection\InspectionReport;
use App\Models\WorkFlow\JobRequest;
use App\Services\Inspection\InspectionReportStorageService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RepairLiftingNaturalRevision extends Command
{
    private const SUPPORTED_TYPES = [
        'crane' => 'App\\Models\\Inspection\\Lifting\\Crane',
        'overheadcrane' => 'App\\Models\\Inspection\\Lifting\\OverheadCrane',
        'forklift' => 'App\\Models\\Inspection\\Lifting\\Forklift',
    ];

    protected $signature = 'inspection:repair-lifting-natural-revision
                            {--type= : crane, overheadcrane, or forklift}
                            {--job= : Job request code, e.g. 026-223}
                            {--source= : Canonical natural code that the revision should inherit, e.g. 001}
                            {--mistaken= : Mistaken promoted natural code to repair, e.g. 002}
                            {--apply : Persist the repair. Omit for audit-only dry-run}
                            {--keep-approval : Preserve user_id_approved on the mistaken row}
                            {--keep-publish : Preserve publish on the mistaken row}';

    protected $description = 'Audit or repair lifting revisions that were mistakenly promoted into a new natural code.';

    public function __construct(private readonly InspectionReportStorageService $storageService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $reportableType = $this->resolveType((string) $this->option('type'));
        if ($reportableType === null) {
            return self::FAILURE;
        }

        $jobCode = trim((string) $this->option('job'));
        $sourceCode = $this->normalizeCode((string) $this->option('source'));
        $mistakenCode = $this->normalizeCode((string) $this->option('mistaken'));

        if ($jobCode === '' || $sourceCode === '' || $mistakenCode === '') {
            $this->error('Options --job, --source, and --mistaken are required.');
            return self::FAILURE;
        }

        if (strcasecmp($sourceCode, $mistakenCode) === 0) {
            $this->error('--source and --mistaken must be different natural codes.');
            return self::FAILURE;
        }

        $jobRequest = JobRequest::query()->where('code', $jobCode)->first();
        if (!$jobRequest) {
            $this->error('Job request not found for code: ' . $jobCode);
            return self::FAILURE;
        }

        $source = $this->findNaturalReport($reportableType, (int) $jobRequest->id, $sourceCode);
        $mistaken = $this->findNaturalReport($reportableType, (int) $jobRequest->id, $mistakenCode);

        if (!$source) {
            $this->error('Source report not found for ' . class_basename($reportableType) . ' / ' . $jobCode . ' / ' . $sourceCode);
            return self::FAILURE;
        }

        if (!$mistaken) {
            $this->error('Mistaken report not found for ' . class_basename($reportableType) . ' / ' . $jobCode . ' / ' . $mistakenCode);
            return self::FAILURE;
        }

        if ((int) $source->id === (int) $mistaken->id) {
            $this->error('Source and mistaken inspection reports resolved to the same row.');
            return self::FAILURE;
        }

        $sourceReportable = $this->resolveReportable($source);
        $mistakenReportable = $this->resolveReportable($mistaken);
        if (!$sourceReportable || !$mistakenReportable) {
            $this->error('Failed to resolve one or both reportable rows.');
            return self::FAILURE;
        }

        $sourcePdf = $this->storageService->resolveExistingPdfPath($source);
        $mistakenPdf = $this->storageService->resolveExistingPdfPath($mistaken);

        $this->info('Targeted lifting revision repair audit');
        $this->table(
            ['Role', 'InspectionReport#', 'Reportable#', 'Type', 'Job', 'Code', 'Approved', 'Published', 'PDF'],
            [
                $this->buildSummaryRow('source', $source, $sourceReportable, $jobCode, $sourcePdf),
                $this->buildSummaryRow('mistaken', $mistaken, $mistakenReportable, $jobCode, $mistakenPdf),
            ]
        );

        $this->newLine();
        $this->line('Planned DB changes on mistaken row:');
        $this->line('- inspection_reports.code: ' . $mistaken->code . ' -> ' . $sourceCode);
        $this->line('- reportable.code: ' . ($mistakenReportable->code ?? '') . ' -> ' . $sourceCode);
        $this->line('- publish: ' . $this->formatNullable($mistaken->publish) . ' -> ' . ($this->option('keep-publish') ? $this->formatNullable($mistaken->publish) : 'null'));
        $this->line('- user_id_approved: ' . $this->formatNullable($mistaken->user_id_approved) . ' -> ' . ($this->option('keep-approval') ? $this->formatNullable($mistaken->user_id_approved) : 'null'));

        if ($mistakenPdf) {
            $this->warn('A PDF exists for the mistaken natural code: ' . $mistakenPdf);
            $this->warn('This command does not move or delete files. Re-upload or manual cleanup may be required after DB repair.');
        }

        if (!$this->option('apply')) {
            $this->info('Dry-run only. Re-run with --apply to persist the repair.');
            return self::SUCCESS;
        }

        DB::transaction(function () use ($mistaken, $mistakenReportable, $sourceCode) {
            $mistakenReportable->forceFill(['code' => $sourceCode])->save();

            $mistaken->code = $sourceCode;
            if (!$this->option('keep-publish')) {
                $mistaken->publish = null;
            }

            if (!$this->option('keep-approval')) {
                $mistaken->user_id_approved = null;
            }

            $mistaken->save();
        });

        $this->newLine();
        $this->info('Repair completed.');
        $this->line('Re-upload the PDF for the repaired revision if you need a fresh published artifact on the canonical code path.');

        return self::SUCCESS;
    }

    private function resolveType(string $requested): ?string
    {
        $normalized = strtolower(trim($requested));
        if ($normalized === '') {
            $this->error('Option --type is required. Allowed values: crane, overheadcrane, forklift.');
            return null;
        }

        $normalized = str_replace(['_', '-', ' '], '', $normalized);

        if (!array_key_exists($normalized, self::SUPPORTED_TYPES)) {
            $this->error('Unsupported --type value: ' . $requested . '. Allowed values: crane, overheadcrane, forklift.');
            return null;
        }

        return self::SUPPORTED_TYPES[$normalized];
    }

    private function normalizeCode(string $code): string
    {
        $code = trim($code);
        if ($code === '') {
            return '';
        }

        if (preg_match('/^\d+$/', $code)) {
            $numeric = (int) $code;
            return $numeric > 999 ? (string) $numeric : str_pad((string) $numeric, 3, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    private function findNaturalReport(string $reportableType, int $jobRequestId, string $code): ?InspectionReport
    {
        return InspectionReport::query()
            ->where('reportable_type', $reportableType)
            ->where('job_request_id', $jobRequestId)
            ->where('code', $code)
            ->where('code', 'not like', '%Duplicated%')
            ->latest('id')
            ->first();
    }

    private function resolveReportable(InspectionReport $report): ?Model
    {
        $reportableType = (string) $report->reportable_type;
        $reportableId = (int) $report->reportable_id;
        if ($reportableType === '' || $reportableId <= 0 || !class_exists($reportableType)) {
            return null;
        }

        $reportable = $report->relationLoaded('reportable')
            ? $report->getRelation('reportable')
            : $reportableType::query()->find($reportableId);

        return $reportable instanceof Model ? $reportable : null;
    }

    private function buildSummaryRow(string $role, InspectionReport $report, Model $reportable, string $jobCode, ?string $pdfPath): array
    {
        return [
            $role,
            '#' . $report->id,
            '#' . $reportable->getKey(),
            class_basename((string) $report->reportable_type),
            $jobCode,
            (string) $report->code,
            $report->user_id_approved ? 'yes' : 'no',
            $report->publish ? 'yes' : 'no',
            $pdfPath ?: '-',
        ];
    }

    private function formatNullable($value): string
    {
        return $value === null || $value === '' ? 'null' : (string) $value;
    }
}
