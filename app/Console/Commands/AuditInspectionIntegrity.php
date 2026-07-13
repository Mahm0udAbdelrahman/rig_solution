<?php

namespace App\Console\Commands;

use App\Models\Inspection\InspectionReport;
use App\Services\Inspection\InspectionReportStorageService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class AuditInspectionIntegrity extends Command
{
    private const INSPECTION_TYPE_LIKE = 'App\\\\Models\\\\Inspection\\\\%';

    protected $signature = 'inspection:audit-integrity
                            {--type= : Full reportable class or basename, e.g. App\\Models\\Inspection\\Ndt\\DrawingInspection or DrawingInspection}';

    protected $description = 'Audit inspection clone/publish/pdf/code integrity without writing any changes.';

    public function __construct(private readonly InspectionReportStorageService $storageService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $resolvedType = $this->resolveRequestedType((string) $this->option('type'));
        if ($resolvedType === false) {
            return self::FAILURE;
        }

        $query = InspectionReport::query()
            ->where('reportable_type', 'like', self::INSPECTION_TYPE_LIKE);

        if ($resolvedType !== null) {
            $query->where('reportable_type', $resolvedType);
        }

        $reports = $query
            ->orderBy('job_request_id')
            ->orderBy('id')
            ->get();

        if ($reports->isEmpty()) {
            $this->info('No inspection reports matched the requested scope.');
            return self::SUCCESS;
        }

        $sections = [];
        $naturalCodeBuckets = [];

        foreach ($reports as $report) {
            $section = $this->resolveSectionKey((string) $report->reportable_type);
            if (!isset($sections[$section])) {
                $sections[$section] = [
                    'total_reports' => 0,
                    'published_reports' => 0,
                    'broken_clone_states' => 0,
                    'publish_mismatches' => 0,
                    'missing_pdfs' => 0,
                    'duplicated_codes' => 0,
                ];
            }

            $sections[$section]['total_reports']++;

            if (!empty($report->publish)) {
                $sections[$section]['published_reports']++;
            }

            if ($this->isPublishMismatch($report)) {
                $sections[$section]['publish_mismatches']++;
            }

            if ($this->isBrokenCloneState($report)) {
                $sections[$section]['broken_clone_states']++;
            }

            if (!empty($report->publish) && !$this->publishedPdfExists($report)) {
                $sections[$section]['missing_pdfs']++;
            }

            if (stripos((string) $report->code, 'duplicated') === false) {
                $bucketKey = $report->job_request_id . '|' . (string) $report->code;
                $naturalCodeBuckets[$bucketKey][] = $section;
            }
        }

        foreach ($naturalCodeBuckets as $bucketSections) {
            if (count($bucketSections) <= 1) {
                continue;
            }

            foreach ($bucketSections as $section) {
                if (isset($sections[$section])) {
                    $sections[$section]['duplicated_codes']++;
                }
            }
        }

        $rows = [];
        foreach ($sections as $section => $metrics) {
            $rows[] = [
                ucfirst($section),
                $metrics['total_reports'],
                $metrics['published_reports'],
                $metrics['broken_clone_states'],
                $metrics['publish_mismatches'],
                $metrics['missing_pdfs'],
                $metrics['duplicated_codes'],
            ];
        }

        $this->table(
            ['Section', 'Total', 'Published', 'Broken Clones', 'Publish Mismatch', 'Missing PDFs', 'Duplicate Codes'],
            $rows
        );

        $totals = collect($sections)->reduce(function ($carry, $metrics) {
            foreach ($metrics as $key => $value) {
                $carry[$key] = ($carry[$key] ?? 0) + $value;
            }

            return $carry;
        }, []);

        $this->newLine();
        $this->info('Inspection integrity audit completed.');
        $this->line('Total reports: ' . ($totals['total_reports'] ?? 0));
        $this->line('Published reports: ' . ($totals['published_reports'] ?? 0));
        $this->line('Broken clone states: ' . ($totals['broken_clone_states'] ?? 0));
        $this->line('Publish mismatches: ' . ($totals['publish_mismatches'] ?? 0));
        $this->line('Missing published PDFs: ' . ($totals['missing_pdfs'] ?? 0));
        $this->line('Duplicate natural codes: ' . ($totals['duplicated_codes'] ?? 0));

        return self::SUCCESS;
    }

    private function isBrokenCloneState(InspectionReport $report): bool
    {
        if (stripos((string) $report->code, 'duplicated') === false) {
            return false;
        }

        $baseCode = $this->normalizeDuplicatedBaseCode((string) $report->code);
        if ($baseCode === '') {
            return false;
        }

        $source = InspectionReport::query()
            ->where('job_request_id', $report->job_request_id)
            ->where('reportable_type', $report->reportable_type)
            ->where('code', $baseCode)
            ->where('code', 'not like', '%Duplicated%')
            ->latest('id')
            ->first();

        return $source && !empty($source->user_id_approved) && empty($report->user_id_approved);
    }

    private function isPublishMismatch(InspectionReport $report): bool
    {
        return !empty($report->publish) && empty($report->user_id_approved);
    }

    private function publishedPdfExists(InspectionReport $report): bool
    {
        return $this->storageService->publishedPdfExists($report);
    }

    private function normalizeDuplicatedBaseCode(string $code): string
    {
        $code = trim($code);
        if ($code === '') {
            return '';
        }

        return trim((string) preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', $code));
    }

    private function resolveSectionKey(string $reportableType): string
    {
        $parts = explode('\\', $reportableType);
        $inspectionIndex = array_search('Inspection', $parts, true);
        if ($inspectionIndex === false) {
            return 'unknown';
        }

        return strtolower((string) ($parts[$inspectionIndex + 1] ?? 'unknown'));
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
