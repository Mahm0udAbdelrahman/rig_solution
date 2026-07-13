<?php

namespace App\Console\Commands;

use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Ndt\DrawingInspection;
use App\Models\Persons\Client;
use App\Models\Persons\ClientDepartment;
use App\Models\WorkFlow\FileManager;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class AuditCustomerDrawingPortalVisibility extends Command
{
    protected $signature = 'portal:audit-drawing-visibility
                            {--client-id= : Client id for customer guard scope}
                            {--client-email= : Client email for customer guard scope}
                            {--client-name= : Partial client name for customer guard scope}
                            {--department-id= : Client department id for department guard scope}
                            {--department-email= : Client department email for department guard scope}
                            {--job-code= : Optional JCF/job request code filter}
                            {--show=hidden : hidden|visible|all}
                            {--limit=200 : Maximum rows to print}';

    protected $description = 'Audit why Drawing Inspection reports do or do not appear in the client area portal.';

    public function handle(): int
    {
        $scope = $this->resolveScope();
        if ($scope === false) {
            return self::FAILURE;
        }

        $show = strtolower(trim((string) $this->option('show')));
        if (!in_array($show, ['hidden', 'visible', 'all'], true)) {
            $this->error('Invalid --show value. Use hidden, visible, or all.');
            return self::FAILURE;
        }

        $limit = max(1, (int) $this->option('limit'));
        $jobCodeFilter = trim((string) $this->option('job-code'));

        $reports = InspectionReport::query()
            ->with([
                'job_request:id,code,client_id,client_department_id,purchase_order,deploc',
                'job_request.client:id,name,email',
                'job_request.clientDepartment:id,name,email,client_id',
                'reportable',
            ])
            ->where('reportable_type', DrawingInspection::class)
            ->when($scope !== null, function (Builder $query) use ($scope) {
                $query->whereHas('job_request', function (Builder $jobRequestQuery) use ($scope) {
                    if ($scope['guard'] === 'customer') {
                        $jobRequestQuery->where('client_id', $scope['id']);
                        return;
                    }

                    $jobRequestQuery->where('client_department_id', $scope['id']);
                });
            })
            ->when($jobCodeFilter !== '', function (Builder $query) use ($jobCodeFilter) {
                $query->whereHas('job_request', function (Builder $jobRequestQuery) use ($jobCodeFilter) {
                    $jobRequestQuery->where('code', 'like', '%'.$jobCodeFilter.'%');
                });
            })
            ->orderBy('job_request_id')
            ->orderBy('id')
            ->get();

        if ($reports->isEmpty()) {
            $this->warn('No Drawing Inspection rows matched the requested scope.');
            return self::SUCCESS;
        }

        $rows = [];
        $visibleCount = 0;
        $hiddenCount = 0;

        foreach ($reports as $report) {
            $audit = $this->auditReport($report, $scope);
            if ($audit['visible']) {
                $visibleCount++;
            } else {
                $hiddenCount++;
            }

            if ($show === 'visible' && !$audit['visible']) {
                continue;
            }

            if ($show === 'hidden' && $audit['visible']) {
                continue;
            }

            $rows[] = [
                $report->id,
                $audit['customer'],
                $audit['department'],
                $audit['job_code'],
                $audit['report_code'],
                $audit['approved'],
                $audit['published'],
                $audit['pdf_exists'],
                $audit['visible'] ? 'yes' : 'no',
                $audit['pdf_path'] ?: '-',
                implode(' | ', $audit['reasons']),
            ];
        }

        $headerLabel = $scope === null
            ? 'all customers/departments'
            : ($scope['guard'] === 'customer'
                ? 'customer #'.$scope['id'].' '.$scope['label']
                : 'department #'.$scope['id'].' '.$scope['label']);

        $this->info('Drawing portal visibility audit for '.$headerLabel);
        $this->line('Total drawing reports in scope: '.$reports->count());
        $this->line('Visible in portal: '.$visibleCount);
        $this->line('Hidden from portal: '.$hiddenCount);
        $this->newLine();

        if (empty($rows)) {
            $this->warn('No rows matched the current --show filter.');
            return self::SUCCESS;
        }

        $printedRows = array_slice($rows, 0, $limit);
        $this->table(
            ['IR#', 'Client', 'Department', 'JCF', 'Report', 'Approved', 'Published', 'PDF', 'Visible', 'PDF Path', 'Reasons'],
            $printedRows
        );

        if (count($rows) > $limit) {
            $this->warn('Output truncated to '.$limit.' rows. Increase --limit if needed.');
        }

        return self::SUCCESS;
    }

    private function auditReport(InspectionReport $report, ?array $scope): array
    {
        $jobRequest = $report->job_request;
        $jobCode = trim((string) optional($jobRequest)->code);
        $reportCode = trim((string) ($report->code ?? ''));
        $scopeMatches = $this->reportMatchesScope($report, $scope);
        $notDuplicated = stripos($reportCode, 'duplicated') === false;
        $hasApproval = !empty($report->user_id_approved);
        $isPublished = !empty($report->publish) && (int) $report->publish !== 0;
        $pdfPath = $this->resolvePortalPdfPath($report, $jobCode);
        $pdfExists = $pdfPath !== null;

        $reasons = [];
        if (!$scopeMatches) {
            $reasons[] = 'outside requested portal scope';
        }
        if (!$notDuplicated) {
            $reasons[] = 'duplicated code excluded by portal';
        }
        if (!$hasApproval) {
            $reasons[] = 'missing approval';
        }
        if (!$isPublished) {
            $reasons[] = 'publish flag is null/0';
        }
        if (!$pdfExists) {
            $reasons[] = 'pdf missing from expected portal path';
        }

        if (empty($reasons)) {
            $reasons[] = 'visible';
        }

        return [
            'customer' => trim((string) optional(optional($jobRequest)->client)->name) ?: '-',
            'department' => trim((string) optional(optional($jobRequest)->clientDepartment)->name) ?: '-',
            'job_code' => $jobCode !== '' ? $jobCode : '-',
            'report_code' => $reportCode !== '' ? $reportCode : '-',
            'approved' => $hasApproval ? 'yes' : 'no',
            'published' => $isPublished ? 'yes' : 'no',
            'pdf_exists' => $pdfExists ? 'yes' : 'no',
            'pdf_path' => $pdfPath,
            'visible' => empty(array_diff($reasons, ['visible'])),
            'reasons' => $reasons,
        ];
    }

    private function reportMatchesScope(InspectionReport $report, ?array $scope): bool
    {
        if ($scope === null) {
            return true;
        }

        $jobRequest = $report->job_request;
        if (!$jobRequest) {
            return false;
        }

        if ($scope['guard'] === 'customer') {
            return (int) ($jobRequest->client_id ?? 0) === (int) $scope['id'];
        }

        return (int) ($jobRequest->client_department_id ?? 0) === (int) $scope['id'];
    }

    private function resolvePortalPdfPath(InspectionReport $report, string $jobCode): ?string
    {
        if ($jobCode === '' || empty($report->code)) {
            return null;
        }

        $relativePdfPath = 'pdf/inspection/ndt/drawinginspection/'.$jobCode.'/'.$report->code.'.pdf';
        if (Storage::disk('public')->exists($relativePdfPath)) {
            return $relativePdfPath;
        }

        $basename = basename(str_replace('\\', '/', $relativePdfPath));

        $fileRow = FileManager::query()
            ->select(['path'])
            ->where('module', 'inspection')
            ->where('is_inspection', 1)
            ->where('is_available', 1)
            ->where('extension', 'pdf')
            ->where('job_request_code', $jobCode)
            ->where(function (Builder $query) use ($report, $basename) {
                $query
                    ->where('entity_code', $report->code)
                    ->orWhere('path', 'like', '%/'.$basename);
            })
            ->first();

        return $fileRow?->path;
    }

    private function resolveScope(): array|null|false
    {
        $clientId = (int) $this->option('client-id');
        $clientEmail = trim((string) $this->option('client-email'));
        $clientName = trim((string) $this->option('client-name'));
        $departmentId = (int) $this->option('department-id');
        $departmentEmail = trim((string) $this->option('department-email'));

        $customerInputs = array_filter([
            $clientId > 0 ? 'client-id' : null,
            $clientEmail !== '' ? 'client-email' : null,
            $clientName !== '' ? 'client-name' : null,
        ]);

        $departmentInputs = array_filter([
            $departmentId > 0 ? 'department-id' : null,
            $departmentEmail !== '' ? 'department-email' : null,
        ]);

        if (!empty($customerInputs) && !empty($departmentInputs)) {
            $this->error('Choose customer scope or department scope, not both.');
            return false;
        }

        if (count($customerInputs) > 1) {
            $this->error('Use only one of --client-id, --client-email, or --client-name.');
            return false;
        }

        if (count($departmentInputs) > 1) {
            $this->error('Use only one of --department-id or --department-email.');
            return false;
        }

        if (empty($customerInputs) && empty($departmentInputs)) {
            return null;
        }

        if (!empty($customerInputs)) {
            $clientQuery = Client::query()
                ->when($clientId > 0, fn (Builder $query) => $query->whereKey($clientId))
                ->when($clientEmail !== '', fn (Builder $query) => $query->where('email', $clientEmail))
                ->when($clientName !== '', fn (Builder $query) => $query->where('name', 'like', '%'.$clientName.'%'));

            $matches = $clientQuery->limit(2)->get();
            if ($matches->count() > 1) {
                $this->error('Client scope is ambiguous. Use --client-id or --client-email instead of partial --client-name.');
                return false;
            }

            $client = $matches->first();

            if (!$client) {
                $this->error('Client scope could not be resolved.');
                return false;
            }

            return [
                'guard' => 'customer',
                'id' => (int) $client->id,
                'label' => trim((string) ($client->name ?? $client->email ?? '')),
            ];
        }

        $department = ClientDepartment::query()
            ->when($departmentId > 0, fn (Builder $query) => $query->whereKey($departmentId))
            ->when($departmentEmail !== '', fn (Builder $query) => $query->where('email', $departmentEmail))
            ->first();

        if (!$department) {
            $this->error('Department scope could not be resolved.');
            return false;
        }

        return [
            'guard' => 'clientDepartments',
            'id' => (int) $department->id,
            'label' => trim((string) ($department->name ?? $department->email ?? '')),
        ];
    }
}
