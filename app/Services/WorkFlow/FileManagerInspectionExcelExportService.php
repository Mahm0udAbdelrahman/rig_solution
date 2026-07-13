<?php

namespace App\Services\WorkFlow;

use App\Models\Inspection\InspectionReport;
use App\Models\User;
use App\Models\WorkFlow\FileManager;
use App\Services\Inspection\InspectionReportExportMapper;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class FileManagerInspectionExcelExportService
{
    public function __construct(
        private readonly InspectionReportExportMapper $mapper,
    ) {
    }

    public function buildRows(Collection $fileRows): Collection
    {
        $fileRows = $fileRows->values();
        if ($fileRows->isEmpty()) {
            return collect();
        }

        $reports = $this->loadCandidateReports($fileRows);
        $reportIndex = $this->indexReports($reports);
        $approvedUsers = $this->approvedUsersIndex($reports);

        return $fileRows->map(function (FileManager $fileRow) use ($reportIndex, $approvedUsers) {
            $report = $this->resolveMatchingReport($fileRow, $reportIndex);
            $jobRequest = $report?->job_request;
            $reportable = $report?->reportable;
            $approvedName = $report && !empty($report->user_id_approved)
                ? ($approvedUsers[(int) $report->user_id_approved] ?? '')
                : '';

            return [
                'jcf_code' => (string) ($fileRow->job_request_code ?? optional($jobRequest)->code ?? ''),
                'report_code' => (string) ($fileRow->entity_code ?? $report?->code ?? ''),
                'full_report_no' => trim((string) (($fileRow->job_request_code ?? optional($jobRequest)->code ?? '').' / '.($fileRow->entity_code ?? $report?->code ?? '')), ' /'),
                'section' => $report ? $this->mapper->sectionLabel($report->reportable_type) : ucfirst((string) strtok((string) $fileRow->category, '/')),
                'inspection_type' => $report ? $this->mapper->typeLabel($report->reportable_type) : (string) ($fileRow->entity_type ?? ''),
                'client_supplier' => optional(optional($jobRequest)->client)->name
                    ?? optional(optional($jobRequest)->supplier)->name
                    ?? '',
                'department' => optional($jobRequest?->clientDepartment)->department ?? optional($jobRequest?->clientDepartment)->name ?? '',
                'work_location' => (string) ($jobRequest->deploc ?? ''),
                'purchase_order' => (string) ($jobRequest->purchase_order ?? ''),
                'identification_no' => $this->mapper->identifier($reportable),
                'equipment' => $this->mapper->equipment($reportable),
                'examination_date' => $this->mapper->examinationDate($reportable),
                'approval_status' => $this->mapper->approvalStatus($report),
                'publish_status' => $this->mapper->publishStatus($report),
                'approved_by' => $approvedName,
                'file_name' => (string) $fileRow->filename,
                'file_path' => (string) $fileRow->path,
                'file_url' => method_exists($fileRow, 'publicUrl') ? (string) $fileRow->publicUrl() : '',
                'generated_at' => optional($fileRow->generated_at)?->format('Y-m-d H:i:s') ?: '',
                'file_available' => (int) ($fileRow->is_available ?? 0) === 1 ? 'Yes' : 'No',
                'matched_report_id' => (string) ($report->id ?? ''),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'JCF Code',
            'Report Code',
            'Full Report No',
            'Section',
            'Inspection Type',
            'Client / Supplier',
            'Department',
            'Work Location',
            'Purchase Order',
            'Identification No',
            'Equipment',
            'Examination Date',
            'Approval Status',
            'Publish Status',
            'Approved By',
            'File Name',
            'File Path',
            'File URL',
            'Generated At',
            'File Available',
            'Matched Report ID',
        ];
    }

    public function rowValues(Collection $rows): Collection
    {
        return $rows->map(function (array $row) {
            return [
                $row['jcf_code'] ?? '',
                $row['report_code'] ?? '',
                $row['full_report_no'] ?? '',
                $row['section'] ?? '',
                $row['inspection_type'] ?? '',
                $row['client_supplier'] ?? '',
                $row['department'] ?? '',
                $row['work_location'] ?? '',
                $row['purchase_order'] ?? '',
                $row['identification_no'] ?? '',
                $row['equipment'] ?? '',
                $row['examination_date'] ?? '',
                $row['approval_status'] ?? '',
                $row['publish_status'] ?? '',
                $row['approved_by'] ?? '',
                $row['file_name'] ?? '',
                $row['file_path'] ?? '',
                $row['file_url'] ?? '',
                $row['generated_at'] ?? '',
                $row['file_available'] ?? '',
                $row['matched_report_id'] ?? '',
            ];
        });
    }

    private function loadCandidateReports(Collection $fileRows): EloquentCollection
    {
        $jobCodes = $fileRows->pluck('job_request_code')->filter()->unique()->values();
        $reportCodes = $fileRows->pluck('entity_code')->filter()->unique()->values();

        if ($jobCodes->isEmpty() || $reportCodes->isEmpty()) {
            return new EloquentCollection();
        }

        return InspectionReport::query()
            ->with([
                'job_request.client',
                'job_request.supplier',
                'job_request.clientDepartment',
                'reportable',
            ])
            ->whereIn('code', $reportCodes)
            ->whereHas('job_request', function ($query) use ($jobCodes) {
                $query->whereIn('code', $jobCodes);
            })
            ->get();
    }

    private function indexReports(EloquentCollection $reports): array
    {
        $index = [];

        foreach ($reports as $report) {
            $jobCode = (string) optional($report->job_request)->code;
            $reportCode = (string) ($report->code ?? '');
            $entityType = $this->mapper->entityTypeFromReport($report);

            if ($jobCode === '' || $reportCode === '') {
                continue;
            }

            if ($entityType !== '') {
                $index[$jobCode.'|'.$reportCode.'|'.$entityType] = $report;
            }

            $index[$jobCode.'|'.$reportCode] ??= $report;
        }

        return $index;
    }

    private function resolveMatchingReport(FileManager $fileRow, array $reportIndex): ?InspectionReport
    {
        $jobCode = (string) ($fileRow->job_request_code ?? '');
        $reportCode = (string) ($fileRow->entity_code ?? '');
        $entityType = $this->mapper->fileEntityTypeFromPath($fileRow->category, $fileRow->entity_type);

        if ($jobCode === '' || $reportCode === '') {
            return null;
        }

        if ($entityType !== '') {
            $exactKey = $jobCode.'|'.$reportCode.'|'.$entityType;
            if (isset($reportIndex[$exactKey])) {
                return $reportIndex[$exactKey];
            }
        }

        return $reportIndex[$jobCode.'|'.$reportCode] ?? null;
    }

    private function approvedUsersIndex(EloquentCollection $reports): array
    {
        $approvedIds = $reports->pluck('user_id_approved')->filter()->unique()->map(fn ($id) => (int) $id)->values();
        if ($approvedIds->isEmpty()) {
            return [];
        }

        return User::query()
            ->with('employee')
            ->whereIn('id', $approvedIds)
            ->get()
            ->mapWithKeys(function (User $user) {
                $label = optional($user->employee)->name ?: ('User #'.$user->id);

                return [(int) $user->id => $label];
            })
            ->all();
    }
}
