<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Inspection\InspectionReport;
use App\Models\WorkFlow\FileManager;
use App\Models\WorkFlow\JobRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortalController extends Controller
{
    public function customerOverview()
    {
        return $this->renderOverview('customer');
    }

    public function customerReports()
    {
        return $this->renderReports('customer');
    }

    public function departmentOverview()
    {
        return $this->renderOverview('department');
    }

    public function departmentReports()
    {
        return $this->renderReports('department');
    }

    private function renderOverview(string $scope)
    {
        $publishedReports = $this->overviewPublishedReportsQuery($scope)
            ->with(['job_request:id,code,purchase_order,deploc', 'reportable'])
            ->get();
        $visibleReportRows = $this->buildPublishedReportRows($publishedReports);

        $viewData = [
            'page_name' => 'Customer Overview',
            'portal_scope' => $scope,
            'portal_scope_label' => $scope === 'department' ? 'Department Portal' : 'Customer Portal',
            'overview_cards' => [
                [
                    'label' => 'Published Inspections',
                    'value' => $visibleReportRows->count(),
                    'note' => 'Certificates currently visible in the portal.',
                ],
            ],
            'inspection_section_cards' => $this->buildInspectionSectionCardsFromRows($visibleReportRows),
            'recent_published_reports' => $visibleReportRows->take(8)->values(),
        ];

        return view('layouts.customer.overview', $viewData);
    }

    private function renderReports(string $scope)
    {
        $perPage = (int) request('per_page', 25);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 25;
        }

        $search = trim((string) request('q', ''));
        $reportNoFilter = trim((string) request('report_no', ''));
        $identifierFilter = trim((string) request('identifier', ''));
        $equipmentFilter = trim((string) request('equipment', ''));
        $examDateFilter = trim((string) request('exam_date', ''));
        $purchaseOrderFilter = trim((string) request('purchase_order', ''));
        $internalServiceOrderFilter = trim((string) request('internal_service_order', ''));
        $workLocationFilter = trim((string) request('work_location', ''));
        $selectedTypeFilter = trim((string) request('type_filter', ''));
        $sortBy = (string) request('sort_by', 'updated_at');
        $sortDirection = strtolower((string) request('sort_direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        $reportsQuery = $this->customerPublishedReportsBaseQuery($scope)
            ->select([
                'inspection_reports.id',
                'inspection_reports.job_request_id',
                'inspection_reports.code',
                'inspection_reports.publish',
                'inspection_reports.reportable_type',
                'inspection_reports.reportable_id',
                'inspection_reports.updated_at',
            ])
            ->join('job_requests', 'inspection_reports.job_request_id', '=', 'job_requests.id');

        if ($selectedTypeFilter !== '' && $selectedTypeFilter !== 'all') {
            $reportsQuery->where('inspection_reports.reportable_type', $selectedTypeFilter);
        }

        if ($search !== '') {
            $normalizedSearch = $this->normalizeCustomerSearchToken($search);
            $reportsQuery->where(function (Builder $query) use ($search, $normalizedSearch) {
                $query->where('inspection_reports.code', 'like', '%'.$search.'%')
                    ->orWhere('inspection_reports.reportable_type', 'like', '%'.$search.'%')
                    ->orWhere('job_requests.code', 'like', '%'.$search.'%')
                    ->orWhere('job_requests.purchase_order', 'like', '%'.$search.'%')
                    ->orWhere('job_requests.deploc', 'like', '%'.$search.'%')
                    ->orWhereHasMorph('reportable', '*', function (Builder $reportableQuery) use ($search) {
                        $this->applyCustomerReportableTextFilter(
                            $reportableQuery,
                            $search,
                            $this->customerReportGlobalSearchColumns(),
                            true
                        );
                    });
                if ($normalizedSearch !== '') {
                    $query->orWhereRaw(
                        "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(inspection_reports.reportable_type), '\\\\', ''), ' ', ''), '-', ''), '_', ''), '/', ''), ':', '') LIKE ?",
                        ['%'.$normalizedSearch.'%']
                    );
                }
            });
        }

        if ($reportNoFilter !== '') {
            $reportsQuery->where(function (Builder $query) use ($reportNoFilter) {
                $query->where('inspection_reports.code', 'like', '%'.$reportNoFilter.'%')
                    ->orWhere('job_requests.code', 'like', '%'.$reportNoFilter.'%')
                    ->orWhereRaw("CONCAT(job_requests.code, '/', inspection_reports.code) like ?", ['%'.$reportNoFilter.'%']);
            });
        }

        if ($identifierFilter !== '') {
            $reportsQuery->whereHasMorph('reportable', '*', function (Builder $reportableQuery) use ($identifierFilter) {
                $this->applyCustomerReportableTextFilter($reportableQuery, $identifierFilter, $this->customerReportIdentifierColumns());
            });
        }

        if ($equipmentFilter !== '') {
            $reportsQuery->whereHasMorph('reportable', '*', function (Builder $reportableQuery) use ($equipmentFilter) {
                $this->applyCustomerReportableTextFilter(
                    $reportableQuery,
                    $equipmentFilter,
                    array_values(array_unique(array_merge(
                        $this->customerReportEquipmentColumns(),
                        $this->customerReportIdentifierColumns()
                    )))
                );
            });
        }

        if ($examDateFilter !== '') {
            $reportsQuery->whereHasMorph('reportable', '*', function (Builder $reportableQuery) use ($examDateFilter) {
                $this->applyCustomerReportableTextFilter($reportableQuery, $examDateFilter, $this->customerReportExaminationDateColumns());
            });
        }

        if ($purchaseOrderFilter !== '') {
            $reportsQuery->where('job_requests.purchase_order', 'like', '%'.$purchaseOrderFilter.'%');
        }

        if ($internalServiceOrderFilter !== '') {
            $reportsQuery->whereHasMorph('reportable', '*', function (Builder $reportableQuery) use ($internalServiceOrderFilter) {
                $this->applyCustomerReportableTextFilter($reportableQuery, $internalServiceOrderFilter, $this->customerReportInternalServiceOrderColumns());
            });
        }

        if ($workLocationFilter !== '') {
            $reportsQuery->where('job_requests.deploc', 'like', '%'.$workLocationFilter.'%');
        }

        switch ($sortBy) {
            case 'type':
                $reportsQuery->orderBy('inspection_reports.reportable_type', $sortDirection);
                break;
            case 'report_no':
                $reportsQuery
                    ->orderBy('job_requests.code', $sortDirection)
                    ->orderBy('inspection_reports.code', $sortDirection);
                break;
            case 'purchase_order':
                $reportsQuery->orderBy('job_requests.purchase_order', $sortDirection);
                break;
            case 'work_location':
                $reportsQuery->orderBy('job_requests.deploc', $sortDirection);
                break;
            default:
                $reportsQuery->orderBy('inspection_reports.updated_at', $sortDirection);
                break;
        }

        $reports = $reportsQuery
            ->with([
                'job_request:id,code,purchase_order,deploc',
                'reportable',
            ])
            ->get();

        $visibleReportRows = $this->buildPublishedReportRows($reports)->values();
        $currentPage = max(1, (int) request('page', 1));
        $reportRows = new LengthAwarePaginator(
            $visibleReportRows->forPage($currentPage, $perPage)->values(),
            $visibleReportRows->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
                'pageName' => 'page',
            ]
        );

        $typeOptions = $this->customerPortalTypeOptions()
            ->map(function ($type) {
                return [
                    'value' => (string) $type,
                    'label' => $this->customerReportTypeLabel((string) $type),
                ];
            })
            ->sortBy('label')
            ->values();

        return view('layouts.customer.reports.reports', [
            'page_name' => 'Published Inspection Certificates',
            'report_rows' => $reportRows,
            'type_options' => $typeOptions,
            'selected_type_filter' => $selectedTypeFilter,
        ]);
    }

    private function baseScopeQuery(string $scope): Builder
    {
        return $this->applyPortalScopeToJobRequestQuery(JobRequest::query(), $scope);
    }

    private function applyPortalScopeToJobRequestQuery(Builder $query, string $scope): Builder
    {
        if ($scope === 'department') {
            $departmentId = (int) Auth::guard('clientDepartments')->id();
            return $query->where('client_department_id', $departmentId);
        }

        $customerId = (int) Auth::guard('customer')->id();
        return $query->where('client_id', $customerId);
    }

    private function overviewPublishedReportsQuery(string $scope): Builder
    {
        $query = $this->customerPublishedReportsBaseQuery($scope)
            ->select([
                'id',
                'job_request_id',
                'code',
                'publish',
                'user_id_approved',
                'reportable_type',
                'reportable_id',
                'updated_at',
                'created_at',
            ])
            ->with(['job_request:id,code'])
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at');

        return $query;
    }

    private function buildInspectionSectionCards(Collection $publishedReports): Collection
    {
        return $publishedReports
            ->groupBy(function (InspectionReport $report) {
                return $this->resolveInspectionSectionLabel((string) $report->reportable_type);
            })
            ->map(function (Collection $reports, string $label) {
                $latestReport = $reports->sortByDesc(function (InspectionReport $report) {
                    return optional($report->updated_at)->timestamp ?: optional($report->created_at)->timestamp;
                })->first();

                $latestCode = optional($latestReport)->code;
                if ($latestReport && optional($latestReport->job_request)->code) {
                    $latestCode = $latestReport->job_request->code.'/'.$latestReport->code;
                }

                return [
                    'label' => $label,
                    'count' => $reports->count(),
                    'latest_code' => $latestCode,
                ];
            })
            ->sortByDesc('count')
            ->values();
    }

    private function buildInspectionSectionCardsFromRows(Collection $publishedReportRows): Collection
    {
        return $publishedReportRows
            ->groupBy(function (array $row) {
                return (string) ($row['section'] ?? 'Inspection');
            })
            ->map(function (Collection $rows, string $label) {
                $latestRow = $rows->first();

                return [
                    'label' => $label,
                    'count' => $rows->count(),
                    'latest_code' => (string) ($latestRow['report_no'] ?? ''),
                    'latest_pdf_url' => (string) ($latestRow['pdf_url'] ?? ''),
                ];
            })
            ->sortByDesc('count')
            ->values();
    }

    private function resolveInspectionSectionLabel(string $reportableType): string
    {
        $cleanType = str_replace('App\\Models\\Inspection\\', '', $reportableType);
        $segments = array_values(array_filter(explode('\\', $cleanType)));
        return $segments[0] ?? 'Inspection';
    }

    private function resolveInspectionTypeLabel(string $reportableType): string
    {
        $cleanType = str_replace('App\\Models\\Inspection\\', '', $reportableType);
        $segments = array_values(array_filter(explode('\\', $cleanType)));
        $className = end($segments) ?: 'Report';

        return trim(preg_replace('/(?<!^)[A-Z]/', ' $0', $className));
    }

    private function buildPublishedReportRows(Collection $reports): Collection
    {
        return $reports
            ->map(function (InspectionReport $report) {
                $jobRequest = $report->job_request;
                $jobCode = (string) optional($jobRequest)->code;
                $pdfPath = $this->resolveCustomerReportPdfPath($report, $jobCode);

                return [
                    'type' => $this->customerReportTypeLabel((string) $report->reportable_type),
                    'section' => $this->customerReportSectionLabel((string) $report->reportable_type),
                    'report_no' => $jobCode.'/'.$report->code,
                    'identifier' => $this->customerReportIdentifier($report->reportable, (string) $report->reportable_type),
                    'equipment' => $this->customerReportEquipment($report->reportable, (string) $report->reportable_type),
                    'notes' => $this->customerReportNotes($report->reportable, (string) $report->reportable_type),
                    'examination_date' => $this->customerReportExaminationDate($report->reportable),
                    'purchase_order' => (string) ($jobRequest->purchase_order ?? ''),
                    'internal_service_order' => $this->customerReportInternalServiceOrder($report->reportable),
                    'work_location' => (string) ($jobRequest->deploc ?? ''),
                    'pdf_path' => $pdfPath,
                    'pdf_url' => $pdfPath ? Storage::disk('public')->url($pdfPath) : null,
                ];
            })
            ->filter(function (array $row) {
                return !empty($row['pdf_url']);
            })
            ->values();
    }

    private function customerReportSectionLabel(?string $reportableType): string
    {
        $segments = $this->customerReportableSegments($reportableType);

        return ucfirst(strtolower((string) ($segments[0] ?? 'Inspection')));
    }

    private function customerReportTypeLabel(?string $reportableType): string
    {
        $basename = class_basename((string) $reportableType);
        if ($basename === '') {
            return 'Unknown';
        }

        return trim((string) preg_replace('/(?<!^)[A-Z]/', ' $0', $basename));
    }

    private function customerReportIdentifier($reportable, ?string $reportableType = null): string
    {
        if (!$reportable) {
            return '';
        }

        if (($mappedValue = $this->customerTubularMappedIdentifier($reportable, $reportableType)) !== null) {
            return $mappedValue;
        }

        if (isset($reportable->lter_10) && !empty($reportable->lter_10) && isset($reportable->lter_10['pop1'])) {
            return (string) $reportable->lter_10['pop1'];
        }

        if (isset($reportable->ldr_8) && !empty($reportable->ldr_8)) {
            $parts = [];
            $decoded = json_decode((string) $reportable->ldr_8);
            foreach ((array) $decoded as $item) {
                $value = data_get($item, 'lcr_10');
                if (!empty($value)) {
                    $parts[] = (string) $value;
                }
            }
            if (!empty($parts)) {
                return implode(' | ', $parts);
            }
        }

        return $this->customerReportFirstNonEmptyField($reportable, $this->customerReportIdentifierColumns());
    }

    private function customerReportEquipment($reportable, ?string $reportableType = null): string
    {
        if (!$reportable) {
            return '';
        }

        if (($mappedValue = $this->customerTubularMappedEquipment($reportable, $reportableType)) !== null) {
            return $mappedValue;
        }

        if (isset($reportable->lter_10) && !empty($reportable->lter_10) && isset($reportable->lter_10['pop20'])) {
            return (string) $reportable->lter_10['pop20'];
        }

        foreach (['equipment_used', 'equipment_no', 'inspection_data', 'ldr_8', 'lter_10'] as $field) {
            $normalizedValue = $this->customerReportNormalizeCompositeField(data_get($reportable, $field));
            if ($normalizedValue !== '') {
                return $normalizedValue;
            }
        }

        return $this->customerReportFirstNonEmptyField($reportable, $this->customerReportEquipmentColumns());
    }

    private function customerReportNotes($reportable, ?string $reportableType = null): string
    {
        if (!$reportable) {
            return '';
        }

        return $this->customerTubularMappedNotes($reportable, $reportableType);
    }

    private function customerReportExaminationDate($reportable): string
    {
        if (!$reportable) {
            return '';
        }

        return $this->customerReportFirstNonEmptyField($reportable, $this->customerReportExaminationDateColumns());
    }

    private function customerReportIdentifierColumns(): array
    {
        return [
            'lter_10',
            'ldr_8',
            'identification_no',
            'joint_no',
            'joint_number',
            'joint_id',
            'tool_joint_id',
            'joint_size',
            'equipment_no',
            'serial_number',
            'device_serial_number',
            'material_no',
            'tool_number',
            'lcr_12',
            'locr_12',
            'lfr_12',
            'nmpr_28',
            'ntir_13',
            'nvr_6',
            'nur_26',
            'nwhr_15',
            'nhpr_10',
            'nh2pr_10',
        ];
    }

    private function customerReportEquipmentColumns(): array
    {
        return [
            'lter_10',
            'ldr_8',
            'equipment_used',
            'equipment_no',
            'equipment_number',
            'name',
            'equipment_name',
            'item_name',
            'desc',
            'description',
            'joint_description',
            'material_description',
            'dc_description',
            'inspection_description',
            'pipe_description',
            'equipment_description',
            'device_description',
            'lcr_10',
            'locr_10',
            'lfr_10',
            'inspection_data',
            'nur_12',
        ];
    }

    private function customerReportInternalServiceOrderColumns(): array
    {
        return [
            'internal_service_order',
            'internal_so',
            'service_order',
            'service_order_no',
            'service_order_number',
            'so_no',
            'so_number',
        ];
    }

    private function customerReportGlobalSearchColumns(): array
    {
        return array_values(array_unique(array_merge(
            $this->customerReportIdentifierColumns(),
            $this->customerReportEquipmentColumns(),
            $this->customerReportExaminationDateColumns(),
            $this->customerReportInternalServiceOrderColumns(),
            [
                'comment',
                'comments',
                'note',
                'notes',
                'remark',
                'remarks',
                'subject',
                'scope_of_work',
                'joint_description',
                'sub_description',
                'other_equipment',
                'connection',
                'pipe_grade',
                'pipe_status',
                'location',
            ]
        )));
    }

    private function customerPublishedReportsBaseQuery(string $scope): Builder
    {
        return InspectionReport::query()
            ->whereNotNull('inspection_reports.publish')
            ->where('inspection_reports.publish', '!=', 0)
            ->whereNotNull('inspection_reports.reportable_type')
            ->whereNotNull('inspection_reports.reportable_id')
            ->where('inspection_reports.code', 'not like', '%Duplicated%')
            ->where(function (Builder $query) {
                $query->whereNotNull('inspection_reports.user_id_approved')
                    ->orWhereIn('inspection_reports.reportable_type', $this->customerPublishedWithoutApprovalExceptionTypes());
            })
            ->whereHas('job_request', function (Builder $query) use ($scope) {
                $this->applyPortalScopeToJobRequestQuery($query, $scope);
            });
    }

    private function customerPublishedWithoutApprovalExceptionTypes(): array
    {
        return [
            \App\Models\Inspection\Ndt\Nregister::class,
            \App\Models\Inspection\Ndt\DrawingInspection::class,
            \App\Models\Inspection\Lifting\Lregister::class,
        ];
    }

    private function customerPortalTypeOptions(): Collection
    {
        $modelsRoot = app_path('Models');
        $inspectionRoot = app_path('Models/Inspection');

        return collect(File::allFiles($inspectionRoot))
            ->map(function ($file) use ($modelsRoot) {
                $normalizedModelsRoot = str_replace('\\', '/', $modelsRoot);
                $normalizedPath = str_replace('\\', '/', $file->getPathname());
                $relativePath = Str::after($normalizedPath, $normalizedModelsRoot . '/');
                $class = 'App\\Models\\' . str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    $relativePath
                );

                return $class;
            })
            ->filter(function (string $class) {
                $base = class_basename($class);

                if ($base === 'InspectionReport') {
                    return false;
                }

                if (preg_match('/\d+$/', $base) === 1) {
                    return false;
                }

                return class_exists($class);
            })
            ->unique()
            ->values();
    }

    private function customerReportExaminationDateColumns(): array
    {
        return [
            'examination_date',
            'ldr_6',
            'lcr_6',
            'locr_6',
            'lfr_6',
            'lter_6',
            'nmpr_6',
            'ntir_6',
            'nvr_4',
            'nur_6',
            'nsr_4',
            'nwhr_6',
            'nhpr_6',
            'nh2pr_6',
            'nar_4',
        ];
    }

    private function applyCustomerReportableTextFilter(Builder $reportableQuery, string $search, array $candidateColumns, bool $includeAllSearchableColumns = false): void
    {
        $table = $reportableQuery->getModel()->getTable();
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
        $availableColumns = array_values(array_intersect($candidateColumns, $columns));
        if ($includeAllSearchableColumns) {
            $availableColumns = array_values(array_unique(array_merge(
                $availableColumns,
                array_values(array_diff($columns, $this->customerReportExcludedSearchColumns()))
            )));
        }
        $searchTokens = array_values(array_filter(preg_split('/\s+/', trim($search))));
        $searchVariants = $this->customerSearchVariants($search);
        $normalizedSearch = $this->normalizeCustomerSearchToken($search);

        if (empty($availableColumns)) {
            $reportableQuery->whereRaw('1 = 0');
            return;
        }

        $qualifiedColumns = array_map(function (string $column) use ($reportableQuery) {
            return $reportableQuery->getModel()->qualifyColumn($column);
        }, $availableColumns);

        $reportableQuery->where(function (Builder $query) use ($qualifiedColumns, $searchTokens, $search, $normalizedSearch, $searchVariants) {
            $tokens = !empty($searchTokens) ? $searchTokens : [$search];

            foreach ($tokens as $token) {
                $variants = $this->customerSearchVariants($token);

                $query->where(function (Builder $tokenQuery) use ($qualifiedColumns, $variants) {
                    foreach ($qualifiedColumns as $qualifiedColumn) {
                        foreach ($variants as $variant) {
                            $tokenQuery->orWhereRaw('LOWER(CAST('.$qualifiedColumn.' AS CHAR)) LIKE ?', ['%'.mb_strtolower($variant).'%']);
                            $normalizedVariant = $this->normalizeCustomerSearchToken($variant);
                            if ($normalizedVariant === '') {
                                continue;
                            }
                            $tokenQuery->orWhereRaw(
                                "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(CAST($qualifiedColumn AS CHAR)), ' ', ''), '-', ''), '_', ''), '/', ''), ':', '') LIKE ?",
                                ['%'.$normalizedVariant.'%']
                            );
                        }
                    }
                });
            }

            if ($normalizedSearch !== '' && count($tokens) > 1) {
                $query->orWhere(function (Builder $tokenQuery) use ($qualifiedColumns, $searchVariants) {
                    foreach ($qualifiedColumns as $qualifiedColumn) {
                        foreach ($searchVariants as $variant) {
                            $normalizedVariant = $this->normalizeCustomerSearchToken($variant);
                            if ($normalizedVariant === '') {
                                continue;
                            }
                            $tokenQuery->orWhereRaw(
                                "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(CAST($qualifiedColumn AS CHAR)), ' ', ''), '-', ''), '_', ''), '/', ''), ':', '') LIKE ?",
                                ['%'.$normalizedVariant.'%']
                            );
                        }
                    }
                });
            }
        });
    }

    private function normalizeCustomerSearchToken(string $value): string
    {
        $value = mb_strtolower(trim($value));
        $value = str_replace([' ', '-', '_', '/', '\\'], '', $value);

        return $value;
    }

    private function customerSearchVariants(string $value): array
    {
        $variants = [trim($value)];
        $trimmed = trim($value);

        foreach (['d-m-Y', 'd/m/Y', 'Y-m-d', 'Y/m/d'] as $format) {
            try {
                $date = Carbon::createFromFormat($format, $trimmed);
            } catch (\Throwable $e) {
                continue;
            }

            $variants[] = $date->format('d-m-Y');
            $variants[] = $date->format('d/m/Y');
            $variants[] = $date->format('Y-m-d');
            $variants[] = $date->format('Y/m/d');
            $variants[] = $date->format('dmY');
            $variants[] = $date->format('Ymd');
            $variants[] = $date->format('j-n-Y');
            $variants[] = $date->format('j/n/Y');
            break;
        }

        return array_values(array_unique(array_filter($variants)));
    }

    private function customerReportExcludedSearchColumns(): array
    {
        return [
            'id',
            'job_request_id',
            'user_id',
            'user_id_edit',
            'user_id_approved',
            'sync',
            'updated',
            'created_at',
            'updated_at',
            'deleted_at',
            'reportable_id',
            'reportable_type',
            'publish',
            'status',
        ];
    }

    private function customerReportNormalizeCompositeField($value): string
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        if (!is_array($value)) {
            return '';
        }

        $parts = [];

        foreach ($value as $item) {
            if (is_array($item)) {
                foreach ([
                    'equipment_used',
                    'other_equipment',
                    'equipment_no_value',
                    'description',
                    'joint_description',
                    'lcr_10',
                    'locr_10',
                    'lfr_10',
                ] as $candidateKey) {
                    $candidateValue = trim((string) data_get($item, $candidateKey, ''));
                    if ($candidateValue !== '') {
                        $parts[] = $candidateValue;
                    }
                }
                continue;
            }

            $scalarValue = trim((string) $item);
            if ($scalarValue !== '') {
                $parts[] = $scalarValue;
            }
        }

        $parts = array_values(array_unique(array_filter($parts)));

        return implode(' | ', $parts);
    }

    private function customerReportInternalServiceOrder($reportable): string
    {
        if (!$reportable) {
            return '';
        }

        return $this->customerReportFirstNonEmptyField($reportable, $this->customerReportInternalServiceOrderColumns());
    }

    private function resolveCustomerReportPdfPath(InspectionReport $report, string $jobCode): ?string
    {
        if ($jobCode === '' || empty($report->code) || empty($report->reportable_type)) {
            return null;
        }

        $relativePdfPath = $this->customerReportPdfRelativePath((string) $report->reportable_type, $jobCode, (string) $report->code);
        if (Storage::disk('public')->exists($relativePdfPath)) {
            return $relativePdfPath;
        }

        $fileRow = FileManager::query()
            ->select(['path'])
            ->where('module', 'inspection')
            ->where('is_inspection', 1)
            ->where('is_available', 1)
            ->where('extension', 'pdf')
            ->where('job_request_code', $jobCode)
            ->where(function (Builder $query) use ($report, $relativePdfPath) {
                $query
                    ->where('entity_code', $report->code)
                    ->orWhere('path', 'like', '%/'.basename(str_replace('\\', '/', $relativePdfPath)));
            })
            ->first();

        return $fileRow?->path;
    }

    private function customerReportPdfRelativePath(string $reportableType, string $jobCode, string $reportCode): string
    {
        $customFolder = match ($reportableType) {
            \App\Models\Inspection\Calibration\CalibrationPressureGauge::class => 'inspection/calibration/calibrationPressureGauge',
            \App\Models\Inspection\Calibration\CalibrationPressureTest::class => 'inspection/calibration/calibrationPressureTest',
            \App\Models\Inspection\Calibration\CalibrationTorque::class => 'inspection/calibration/calibrationTorque',
            \App\Models\Inspection\Calibration\CalibrationYoke::class => 'inspection/calibration/calibrationYoke',
            \App\Models\Inspection\Tubular\PipesSummaryReport::class => 'inspection/tubular/summary',
            default => strtolower(str_replace('\\', '/', str_replace('App\\Models\\', '', $reportableType))),
        };

        return 'pdf/' . trim($customFolder, '/') . '/' . $jobCode . '/' . $reportCode . '.pdf';
    }

    private function customerReportableSegments(?string $reportableType): array
    {
        $clean = str_replace('App\\Models\\Inspection\\', '', (string) $reportableType);

        return array_values(array_filter(explode('\\', $clean), function ($segment) {
            return $segment !== '';
        }));
    }

    private function customerReportFirstNonEmptyField($reportable, array $fields): string
    {
        foreach ($fields as $field) {
            $value = data_get($reportable, $field);

            $normalizedComposite = $this->customerReportNormalizeCompositeField($value);
            if ($normalizedComposite !== '') {
                return $normalizedComposite;
            }

            if ($value !== null && trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        return '';
    }

    private function customerTubularMappedIdentifier($reportable, ?string $reportableType): ?string
    {
        return match (class_basename((string) $reportableType)) {
            'DrillPipe', 'HeavyWeightPipe', 'DrillCollar', 'SubsDimensional', 'TubingCasing' => '',
            'TubingString' => trim((string) data_get($reportable, 'tool_joint_id', '')),
            'StabilizerInspection' => trim((string) data_get($reportable, 'tool_number', '')),
            'ReamerInspection', 'LinkInspection' => trim((string) (
                data_get($reportable, 'material_no')
                ?: $this->customerReportNormalizeCompositeField(data_get($reportable, 'equipment_no'))
            )),
            'Pbl' => trim((string) data_get($reportable, 'identification_no', '')),
            default => null,
        };
    }

    private function customerTubularMappedEquipment($reportable, ?string $reportableType): ?string
    {
        return match (class_basename((string) $reportableType)) {
            'DrillPipe', 'HeavyWeightPipe' => trim((string) data_get($reportable, 'joint_description', '')),
            // In Drill Collar reports the D/C description is the client-facing joint description.
            'DrillCollar' => trim((string) data_get($reportable, 'dc_description', '')),
            'SubsDimensional' => trim((string) (
                data_get($reportable, 'description')
                ?: data_get($reportable, 'inspection_description')
            )),
            // Tubing String has no dedicated joint_description column; connection is the closest joint-facing descriptor.
            'TubingString' => trim((string) data_get($reportable, 'connection', '')),
            'StabilizerInspection', 'ReamerInspection', 'LinkInspection' => trim((string) data_get($reportable, 'material_description', '')),
            'Pbl' => trim((string) (
                data_get($reportable, 'description')
                ?: data_get($reportable, 'inspection_description')
            )),
            'TubingCasing' => trim((string) data_get($reportable, 'pipe_description', '')),
            default => null,
        };
    }

    private function customerTubularMappedNotes($reportable, ?string $reportableType): string
    {
        return match (class_basename((string) $reportableType)) {
            'SubsDimensional' => trim((string) (
                data_get($reportable, 'description') !== '' && data_get($reportable, 'description') !== null
                    ? 'Sub Description: '.trim((string) data_get($reportable, 'description'))
                    : ''
            )),
            'Pbl' => trim((string) (
                data_get($reportable, 'description') !== '' && data_get($reportable, 'description') !== null
                    ? 'Sub Description: '.trim((string) data_get($reportable, 'description'))
                    : ''
            )),
            'StabilizerInspection' => 'ID Number / Equipment Description',
            'ReamerInspection', 'LinkInspection' => 'ID Number / Equipment Description',
            default => '',
        };
    }
}
