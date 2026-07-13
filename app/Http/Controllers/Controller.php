<?php

namespace App\Http\Controllers;

use App\Models\Inspection\InspectionReport;
use App\Services\Inspection\InspectionReportLifecycleService;
use App\Support\InspectionRevisionWorkflow;
use App\Models\WorkFlow\FileManager;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function action_message($type, $page_name)
	{
		$action = '';
		$message = '';
		switch ($type)
		{
			case 0:
				$action = ' Created ';
				break;
			case 1:
				$action = ' Updated ';
				break;
			case 2:
				$action = ' Deleted ';
				break;
		}
		$message = $page_name.$action.'Successfully !';
		return $message;
	}

	public function page_name($type, $page_name)
	{
		$action = '';
		$message = '';
		if ($type == 'All')
		{
			$message = 'All '.$page_name.'s';
		}
		else
		{
			switch ($type)
			{
				case 0:
				$action = 'Create ';
				break;
				case 1:
				$action = 'Edit ';
				break;
			}
			$message = $action.$page_name;
		}
		return $message;
	}

	public function remove_snap_shots_pdf($pdf, $snap)
	{
		$message = "";
		if (\Storage::disk('public')->exists($snap))
		{
			\Storage::disk('public')->deleteDirectory($snap);
			FileManager::query()->where('disk', 'public')->where('path', 'like', trim($snap, '/').'%')->delete();
			$message .= "Snap , ";
		}
		if (\Storage::disk('public')->exists($pdf))
		{
			\Storage::disk('public')->delete($pdf);
			FileManager::query()->where('disk', 'public')->where('path', ltrim($pdf, '/'))->delete();
			$message .= "PDF , ";
		}

		return $message;
	}

    protected function storageUrlWithVersion(string $path): string
    {
        $normalizedPath = ltrim($path, '/');
        $url = Storage::url($normalizedPath);

        try {
            if (Storage::disk('public')->exists($normalizedPath)) {
                return $url . '?v=' . Storage::disk('public')->lastModified($normalizedPath);
            }
        } catch (\Throwable $e) {
            // Fall back to the plain storage URL if metadata lookup fails.
        }

        return $url;
    }

	public function check_if_report_edit($report, $current_report)
	{
		$have_edit = new \stdClass();
        $have_edit->default = null;
        $have_edit->current = null;
        $have_edit->latest = null;
        $have_edit->edited = [];

        $reportClass = is_string($report) ? $report : get_class($current_report);
        $jobRequestId = (int) data_get($current_report, 'job_request.id');
        $currentCode = trim((string) data_get($current_report, 'code'));

        $reports_related = collect();
        $approved_reports_related = collect();
        $approvedRevisionIndex = 0;
        $select_report = null;
        if (is_string($reportClass) && class_exists($reportClass) && $jobRequestId > 0 && $currentCode !== '') {
            $select_report = $reportClass::query()
                ->select('id', 'created_at')
                ->with('report.user.employee')
                ->where('job_request_id', $jobRequestId);

            $this->applyInspectionCodeFamilyConstraint($select_report, $currentCode);
            $reports_related = (clone $select_report)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();

            $approved_reports_related = $reports_related->filter(function ($reportable) {
                return $this->isNaturalApprovedInspectionReportable($reportable);
            })->values();
        }

		if(count($approved_reports_related) != 0)
		{	
            $latestRelatedReportable = $approved_reports_related->last();
            if ($latestRelatedReportable && isset($latestRelatedReportable->report) && $latestRelatedReportable->report) {
                $have_edit->latest = $latestRelatedReportable->report;
            }
		} elseif ($reports_related->count() !== 0) {
            $latestFamilyReportable = $reports_related->last();
            $latestFamilyInspectionReport = $latestFamilyReportable
                ? $this->resolveInspectionReportForReportable($latestFamilyReportable)
                : null;

            if ($latestFamilyInspectionReport) {
                $have_edit->latest = $latestFamilyInspectionReport;
            }
		}

        $currentRouteName = \Route::currentRouteName();
        foreach ($reports_related as $report_data) {
            $inspectionReport = $this->resolveInspectionReportForReportable($report_data);
            if (!$inspectionReport) {
                continue;
            }

            if ($this->supportsInspectionRevisionWorkflow((string) $inspectionReport->reportable_type)
                && stripos((string) $inspectionReport->code, 'duplicated') !== false) {
                continue;
            }

            $versionLabel = $this->resolveInspectionVersionLabel($report_data, $inspectionReport, $approvedRevisionIndex);

            $have_edit->edited[] = [
                'id' => $report_data->id,
                'route' => $currentRouteName ? route($currentRouteName, $report_data->id) : '#',
                'edit_at' => optional($report_data->created_at)->toDateString() ?? '',
                'edit_by' => (string) data_get($inspectionReport, 'user.employee.name', ''),
                'approved' => (bool) data_get($inspectionReport, 'user_id_approved'),
                'version_label' => $versionLabel,
            ];
        }

        if (isset($current_report->report) && $current_report->report) {
            $have_edit->current = $current_report->report;
        }

        if (!$have_edit->current) {
            $fallbackReport = InspectionReport::query()
                ->where('reportable_type', get_class($current_report))
                ->where('reportable_id', $current_report->id)
                ->latest('id')
                ->first();

            if ($fallbackReport) {
                $have_edit->current = $fallbackReport;
            }
        }

        if ($have_edit->current) {
            $have_edit->default = $have_edit->current;
        } elseif ($have_edit->latest) {
            $have_edit->default = $have_edit->latest;
        }

        if (!$have_edit->default) {
            $have_edit->default = $this->buildMissingInspectionReportPlaceholder();
        }

        if (!$have_edit->current) {
            $have_edit->current = $have_edit->default;
        }

        if (!$have_edit->latest) {
            $have_edit->latest = $have_edit->default;
        }

		return $have_edit;
	}

    private function buildMissingInspectionReportPlaceholder(): \stdClass
    {
        $employee = new \stdClass();
        $employee->esign = null;
        $employee->name = '';
        $employee->desc = '';

        $user = new \stdClass();
        $user->employee = $employee;

        $report = new \stdClass();
        $report->id = null;
        $report->code = '';
        $report->user_id_approved = null;
        $report->user = $user;

        return $report;
    }

	public function check_edited_reports_for_delete($report)
	{
        $reportClass = is_object($report) ? get_class($report) : '';
        $reportableId = (int) data_get($report, 'id');

        if ($reportClass === '' || $reportableId <= 0) {
            return false;
        }

        $reportable = $reportClass::query()->find($reportableId);
        if (!$reportable) {
            return false;
        }

        DB::transaction(function () use ($reportClass, $reportable) {
            InspectionReport::query()
                ->where('reportable_type', $reportClass)
                ->where('reportable_id', (int) $reportable->id)
                ->delete();

            $reportable->forceDelete();
        });

		return true;
	}

	public function getUploadedCodes($dir, $returnArr = false) {
        $path = Storage::disk('public')->path('pdf/inspection/'.trim($dir, '/'));
        $pathes = glob($path.'/*/*.pdf') ?: [];

        array_walk($pathes, function (&$item) use ($path) {
            $prefix = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            $item = str_replace($prefix, '', $item);
            $item = str_replace(['\\', '/'], '/', $item);
            $item = preg_replace('/\.pdf$/i', '', $item);
        });

        if ($returnArr) {
            return $pathes;
        }

        return empty($pathes) ? "''" : "'" . implode("','", $pathes) . "'";
    }

    public function applyInspectionGlobalSearch($query, $searchValue, $inspectionDataColumn = 'inspection_data')
    {
        $searchValue = trim((string) $searchValue);
        if ($searchValue === '') {
            return;
        }

        $model = method_exists($query, 'getModel') ? $query->getModel() : null;
        $table = $model && method_exists($model, 'getTable') ? (string) $model->getTable() : '';

        $searchDataColumn = $this->resolveInspectionSearchColumn($query, $inspectionDataColumn, $table);
        $searchCodeColumn = $this->resolveInspectionSearchColumn($query, 'code', $table);

        $query->where(function ($builder) use ($searchValue, $searchDataColumn, $searchCodeColumn) {
            $hasSearchClause = false;

            if ($searchDataColumn !== null) {
                $builder->where($searchDataColumn, 'like', "%{$searchValue}%");
                $hasSearchClause = true;
            }

            if ($searchCodeColumn !== null) {
                if ($hasSearchClause) {
                    $builder->orWhere($searchCodeColumn, 'like', "%{$searchValue}%");
                } else {
                    $builder->where($searchCodeColumn, 'like', "%{$searchValue}%");
                }
                $hasSearchClause = true;
            }

            if (method_exists($builder, 'orWhereHas')) {
                if ($hasSearchClause) {
                    $builder->orWhereHas('job_request', function ($jobRequestQuery) use ($searchValue) {
                        $jobRequestQuery->where('code', 'like', "%{$searchValue}%");
                    });
                } else {
                    $builder->whereHas('job_request', function ($jobRequestQuery) use ($searchValue) {
                        $jobRequestQuery->where('code', 'like', "%{$searchValue}%");
                    });
                }
            }
        });
    }

    public function applyInspectionApprovalPresetFilter($query, ?string $smartPreset = null): void
    {
        $preset = $this->normalizeInspectionPreset($smartPreset ?? request('smart_preset', ''));
        if (!in_array($preset, ['approved', 'unapproved', 'need_publish', 'published', 'unpublished'], true)) {
            $this->applyInspectionRequestedOrdering($query);
            return;
        }

        if ($this->queryJoinsInspectionReportsTable($query)) {
            if ($preset === 'approved') {
                $this->applyApprovedConstraint($query, 'inspection_reports');
                $this->applyInspectionRequestedOrdering($query);
                return;
            }

            if ($preset === 'unapproved') {
                $this->applyNeedApproveConstraint($query, 'inspection_reports');
                $this->applyInspectionRequestedOrdering($query);
                return;
            }

            if ($preset === 'published') {
                $this->applyPublishedConstraint($query, 'inspection_reports.publish');
                $this->applyInspectionRequestedOrdering($query);
                return;
            }

            if ($preset === 'need_publish') {
                $this->applyNeedPublishConstraint($query, 'inspection_reports');
                $this->applyInspectionRequestedOrdering($query);
                return;
            }

            $this->applyUnpublishedConstraint($query, 'inspection_reports.publish');
            $this->applyInspectionRequestedOrdering($query);
            return;
        }

        if ($this->queryTargetsInspectionReportsTable($query)) {
            if ($preset === 'approved') {
                $this->applyApprovedConstraint($query);
                $this->applyInspectionRequestedOrdering($query);
                return;
            }

            if ($preset === 'unapproved') {
                $this->applyNeedApproveConstraint($query);
                $this->applyInspectionRequestedOrdering($query);
                return;
            }

            if ($preset === 'published') {
                $this->applyPublishedConstraint($query, 'publish');
                $this->applyInspectionRequestedOrdering($query);
                return;
            }

            if ($preset === 'need_publish') {
                $this->applyNeedPublishConstraint($query);
                $this->applyInspectionRequestedOrdering($query);
                return;
            }

            $this->applyUnpublishedConstraint($query, 'publish');
            $this->applyInspectionRequestedOrdering($query);
            return;
        }

        if (method_exists($query, 'whereHas') && $this->queryModelHasInspectionReportRelation($query)) {
            $query->whereHas('report', function ($reportQuery) use ($preset) {
                $this->applyInspectionPresetToReportQuery($reportQuery, $preset);
            });
            $this->applyInspectionRequestedOrdering($query);
            return;
        }

        if ($preset === 'approved') {
            $this->applyApprovedConstraint($query, 'inspection_reports');
            $this->applyInspectionRequestedOrdering($query);
            return;
        }

        if ($preset === 'unapproved') {
            $this->applyNeedApproveConstraint($query, 'inspection_reports');
            $this->applyInspectionRequestedOrdering($query);
            return;
        }

        if ($preset === 'published') {
            $this->applyPublishedConstraint($query, 'inspection_reports.publish');
            $this->applyInspectionRequestedOrdering($query);
            return;
        }

        if ($preset === 'need_publish') {
            $this->applyNeedPublishConstraint($query, 'inspection_reports');
            $this->applyInspectionRequestedOrdering($query);
            return;
        }

        $this->applyUnpublishedConstraint($query, 'inspection_reports.publish');
        $this->applyInspectionRequestedOrdering($query);
    }

    public function inspectionApprovalStatusValueByRow($row): string
    {
        return $this->resolveInspectionApprovedByUserIdFromRow($row) ? 'approved' : 'unapproved';
    }

    public function inspectionPublishStatusValueByRow($row): string
    {
        return $this->resolveInspectionPublishValueFromRow($row) ? 'published' : 'unpublished';
    }

    public function inspectionRevisionCountValueByRow($row): int
    {
        $meta = $this->resolveInspectionRevisionMetaFromRow($row);
        if (!$meta) {
            return 1;
        }

        return $this->resolveInspectionRevisionCount($meta['reportable_type'], $meta['job_request_id'], $meta['code']);
    }

    private function resolveInspectionApprovedByUserIdFromRow($row): ?int
    {
        if (is_object($row)) {
            if (isset($row->user_id_approved) && $row->user_id_approved) {
                return (int) $row->user_id_approved;
            }

            if (isset($row->report_user_id_approved) && $row->report_user_id_approved) {
                return (int) $row->report_user_id_approved;
            }

            $relatedReport = $this->resolveInspectionReportRelationFromRow($row);
            if ($relatedReport && isset($relatedReport->user_id_approved) && $relatedReport->user_id_approved) {
                return (int) $relatedReport->user_id_approved;
            }

            if (isset($row->report_id) && $row->report_id) {
                return $this->resolveInspectionApprovedByReportId((int) $row->report_id);
            }
        }

        if (is_array($row)) {
            if (!empty($row['user_id_approved'])) {
                return (int) $row['user_id_approved'];
            }

            if (!empty($row['report_user_id_approved'])) {
                return (int) $row['report_user_id_approved'];
            }

            if (!empty($row['report']['user_id_approved'])) {
                return (int) $row['report']['user_id_approved'];
            }

            if (!empty($row['report_id'])) {
                return $this->resolveInspectionApprovedByReportId((int) $row['report_id']);
            }
        }

        return null;
    }

    private function resolveInspectionApprovedByReportId(int $reportId): ?int
    {
        static $approvalCache = [];

        if ($reportId <= 0) {
            return null;
        }

        if (array_key_exists($reportId, $approvalCache)) {
            return $approvalCache[$reportId];
        }

        $approvedBy = InspectionReport::query()
            ->where('id', $reportId)
            ->value('user_id_approved');

        $approvalCache[$reportId] = $approvedBy ? (int) $approvedBy : null;

        return $approvalCache[$reportId];
    }

    private function resolveInspectionPublishValueFromRow($row): ?int
    {
        if (is_object($row)) {
            if (isset($row->publish) && $row->publish !== null) {
                return (int) $row->publish;
            }

            if (isset($row->report_publish) && $row->report_publish !== null) {
                return (int) $row->report_publish;
            }

            $relatedReport = $this->resolveInspectionReportRelationFromRow($row);
            if ($relatedReport && isset($relatedReport->publish) && $relatedReport->publish !== null) {
                return (int) $relatedReport->publish;
            }

            if (isset($row->report_id) && $row->report_id) {
                return $this->resolveInspectionPublishByReportId((int) $row->report_id);
            }
        }

        if (is_array($row)) {
            if (array_key_exists('publish', $row) && $row['publish'] !== null) {
                return (int) $row['publish'];
            }

            if (array_key_exists('report_publish', $row) && $row['report_publish'] !== null) {
                return (int) $row['report_publish'];
            }

            if (isset($row['report']) && is_array($row['report']) && array_key_exists('publish', $row['report']) && $row['report']['publish'] !== null) {
                return (int) $row['report']['publish'];
            }

            if (!empty($row['report_id'])) {
                return $this->resolveInspectionPublishByReportId((int) $row['report_id']);
            }
        }

        return null;
    }

    private function resolveInspectionPublishByReportId(int $reportId): ?int
    {
        static $publishCache = [];

        if ($reportId <= 0) {
            return null;
        }

        if (array_key_exists($reportId, $publishCache)) {
            return $publishCache[$reportId];
        }

        $publish = InspectionReport::query()
            ->where('id', $reportId)
            ->value('publish');

        $publishCache[$reportId] = $publish !== null ? (int) $publish : null;

        return $publishCache[$reportId];
    }

    private function resolveInspectionRevisionMetaFromRow($row): ?array
    {
        if (is_object($row)) {
            if ($row instanceof Model
                && isset($row->job_request_id, $row->code)
                && $row->job_request_id
                && trim((string) $row->code) !== '') {
                return [
                    'reportable_type' => get_class($row),
                    'job_request_id' => (int) $row->job_request_id,
                    'code' => trim((string) $row->code),
                ];
            }

            $relatedReport = $this->resolveInspectionReportRelationFromRow($row);
            if ($relatedReport
                && isset($relatedReport->reportable_type, $relatedReport->job_request_id, $relatedReport->code)
                && $relatedReport->reportable_type
                && $relatedReport->job_request_id
                && trim((string) $relatedReport->code) !== '') {
                return [
                    'reportable_type' => (string) $relatedReport->reportable_type,
                    'job_request_id' => (int) $relatedReport->job_request_id,
                    'code' => trim((string) $relatedReport->code),
                ];
            }

            if (isset($row->report_id) && $row->report_id) {
                return $this->resolveInspectionRevisionMetaByReportId((int) $row->report_id);
            }
        }

        if (is_array($row)) {
            if (!empty($row['reportable_type']) && !empty($row['job_request_id']) && !empty($row['code'])) {
                return [
                    'reportable_type' => (string) $row['reportable_type'],
                    'job_request_id' => (int) $row['job_request_id'],
                    'code' => trim((string) $row['code']),
                ];
            }

            if (!empty($row['report']) && is_array($row['report'])
                && !empty($row['report']['reportable_type'])
                && !empty($row['report']['job_request_id'])
                && !empty($row['report']['code'])) {
                return [
                    'reportable_type' => (string) $row['report']['reportable_type'],
                    'job_request_id' => (int) $row['report']['job_request_id'],
                    'code' => trim((string) $row['report']['code']),
                ];
            }

            if (!empty($row['report_id'])) {
                return $this->resolveInspectionRevisionMetaByReportId((int) $row['report_id']);
            }
        }

        return null;
    }

    private function resolveInspectionReportRelationFromRow($row): ?Model
    {
        if (!is_object($row) || !($row instanceof Model)) {
            return null;
        }

        try {
            $report = $row->getRelationValue('report');
        } catch (\Throwable $e) {
            $report = null;
        }

        return $report instanceof Model ? $report : null;
    }

    private function resolveInspectionRevisionMetaByReportId(int $reportId): ?array
    {
        static $metaCache = [];

        if ($reportId <= 0) {
            return null;
        }

        if (array_key_exists($reportId, $metaCache)) {
            return $metaCache[$reportId];
        }

        $report = InspectionReport::query()
            ->select('reportable_type', 'job_request_id', 'code')
            ->where('id', $reportId)
            ->first();

        if (!$report || !$report->reportable_type || !$report->job_request_id || trim((string) $report->code) === '') {
            $metaCache[$reportId] = null;
            return null;
        }

        $metaCache[$reportId] = [
            'reportable_type' => (string) $report->reportable_type,
            'job_request_id' => (int) $report->job_request_id,
            'code' => trim((string) $report->code),
        ];

        return $metaCache[$reportId];
    }

    private function resolveInspectionRevisionCount(string $reportableType, int $jobRequestId, string $code): int
    {
        static $countCache = [];

        $reportableType = trim($reportableType);
        $code = trim($code);
        $baseCode = $this->normalizeInspectionBaseCode($code);
        if ($reportableType === '' || $jobRequestId <= 0 || $code === '' || !$this->supportsInspectionRevisionWorkflow($reportableType)) {
            return 1;
        }

        $cacheKey = $reportableType.'|'.$jobRequestId.'|'.($baseCode !== '' ? $baseCode : $code);
        if (array_key_exists($cacheKey, $countCache)) {
            return $countCache[$cacheKey];
        }

        if (!class_exists($reportableType) || !is_subclass_of($reportableType, Model::class)) {
            $countCache[$cacheKey] = 1;
            return 1;
        }

        $countQuery = InspectionReport::query()
            ->where('reportable_type', $reportableType)
            ->where('job_request_id', $jobRequestId)
            ->whereNotNull('user_id_approved')
            ->where('code', 'not like', '%Duplicated%');
        $this->applyInspectionCodeFamilyConstraint($countQuery, $code);
        $count = $countQuery->count();

        $countCache[$cacheKey] = max(1, (int) $count);

        return $countCache[$cacheKey];
    }

    private function applyInspectionRequestedOrdering($query): void
    {
        $smartSortColumn = trim((string) request('smart_sort_column', ''));
        $orderDirection = strtolower((string) request('smart_sort_dir', request('order.0.dir', 'desc')));
        if (!in_array($orderDirection, ['asc', 'desc'], true)) {
            $orderDirection = 'desc';
        }

        $columnName = $smartSortColumn;
        if ($columnName === '') {
            $orderIndex = request('order.0.column');
            if ($orderIndex === null || $orderIndex === '') {
                return;
            }
            $columnName = (string) (request("columns.$orderIndex.name") ?: request("columns.$orderIndex.data"));
        }

        if ($columnName === '' || in_array($columnName, ['action', 'approval_status', 'publish_status', 'revision_count'], true)) {
            return;
        }

        if (method_exists($query, 'reorder')) {
            $query->reorder();
        }

        $model = method_exists($query, 'getModel') ? $query->getModel() : null;
        $table = $model && method_exists($model, 'getTable') ? (string) $model->getTable() : '';

        if ($table !== '') {
            if (in_array($columnName, ['code', 'report_code'], true)
                && Schema::hasColumn($table, 'job_request_id')
                && Schema::hasColumn($table, 'code')) {
                $baseCodeExpression = "TRIM(SUBSTRING_INDEX({$table}.code, ' - Duplicated', 1))";
                $duplicatedRankExpression = "CASE WHEN {$table}.code LIKE '%Duplicated%' THEN 1 ELSE 0 END";

                $query->orderBy(
                    DB::table('job_requests')
                        ->select('code')
                        ->whereColumn('job_requests.id', $table.'.job_request_id')
                        ->limit(1),
                    $orderDirection
                );
                $query->orderByRaw("{$baseCodeExpression} {$orderDirection}");
                $query->orderByRaw("{$duplicatedRankExpression} asc");
                $query->orderBy($table.'.code', $orderDirection);
                return;
            }

            if (in_array($columnName, ['deploc', 'work_location', 'location'], true)
                && Schema::hasColumn($table, 'job_request_id')) {
                $query->orderBy(
                    DB::table('job_requests')
                        ->select('deploc')
                        ->whereColumn('job_requests.id', $table.'.job_request_id')
                        ->limit(1),
                    $orderDirection
                );
                return;
            }

            if ($columnName === 'client_department' && Schema::hasColumn($table, 'job_request_id')) {
                $query->orderBy(
                    DB::table('job_requests')
                        ->leftJoin('client_departments', 'client_departments.id', '=', 'job_requests.client_department_id')
                        ->select('client_departments.name')
                        ->whereColumn('job_requests.id', $table.'.job_request_id')
                        ->limit(1),
                    $orderDirection
                );
                return;
            }

            if (in_array($columnName, ['client', 'client_supplier'], true)
                && Schema::hasColumn($table, 'job_request_id')) {
                $query->orderBy(
                    DB::table('job_requests')
                        ->leftJoin('clients', 'clients.id', '=', 'job_requests.client_id')
                        ->leftJoin('suppliers', 'suppliers.id', '=', 'job_requests.supplier_id')
                        ->selectRaw('COALESCE(clients.name, suppliers.name)')
                        ->whereColumn('job_requests.id', $table.'.job_request_id')
                        ->limit(1),
                    $orderDirection
                );
                return;
            }

            if (Schema::hasColumn($table, $columnName)) {
                $query->orderBy($table.'.'.$columnName, $orderDirection);
                return;
            }
        }

        $selectedAliases = $this->extractSelectedAliases($query);
        if (in_array($columnName, $selectedAliases, true) && preg_match('/^[A-Za-z0-9_]+$/', $columnName)) {
            $query->orderBy($columnName, $orderDirection);
        }
    }

    private function extractSelectedAliases($query): array
    {
        $queryObject = method_exists($query, 'getQuery') ? $query->getQuery() : $query;
        $columns = is_object($queryObject) && property_exists($queryObject, 'columns') ? ($queryObject->columns ?: []) : [];

        $aliases = [];
        foreach ($columns as $column) {
            $columnSql = '';
            if (is_string($column)) {
                $columnSql = $column;
            } elseif (is_object($column) && method_exists($column, 'getValue')) {
                $columnSql = (string) $column->getValue(DB::connection()->getQueryGrammar());
            } elseif (is_object($column) && method_exists($column, '__toString')) {
                $columnSql = (string) $column;
            }

            if ($columnSql === '') {
                continue;
            }

            if (preg_match('/\bas\s+`?([A-Za-z0-9_]+)`?/i', $columnSql, $matches)) {
                $aliases[] = $matches[1];
                continue;
            }

            $normalized = trim(str_replace('`', '', $columnSql));
            if (preg_match('/^[A-Za-z0-9_]+$/', $normalized)) {
                $aliases[] = $normalized;
            }
        }

        return array_values(array_unique($aliases));
    }

    private function queryJoinsInspectionReportsTable($query): bool
    {
        $queryObject = method_exists($query, 'getQuery') ? $query->getQuery() : $query;
        $joins = is_object($queryObject) && property_exists($queryObject, 'joins') ? ($queryObject->joins ?: []) : [];

        foreach ($joins as $join) {
            $table = null;
            if (is_object($join) && property_exists($join, 'table')) {
                $table = $join->table;
            }

            if (is_string($table) && preg_match('/\binspection_reports\b/i', $table)) {
                return true;
            }

            if (is_object($table) && method_exists($table, '__toString')) {
                $tableSql = (string) $table;
                if (preg_match('/\binspection_reports\b/i', $tableSql)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function queryTargetsInspectionReportsTable($query): bool
    {
        $model = method_exists($query, 'getModel') ? $query->getModel() : null;
        if (!$model || !method_exists($model, 'getTable')) {
            return false;
        }

        return (string) $model->getTable() === 'inspection_reports';
    }

    private function queryModelHasInspectionReportRelation($query): bool
    {
        $model = method_exists($query, 'getModel') ? $query->getModel() : null;
        return is_object($model) && method_exists($model, 'report');
    }

    private function normalizeInspectionPreset(?string $preset): string
    {
        $normalized = strtolower(trim((string) $preset));
        $normalized = str_replace([' ', '-'], '_', $normalized);

        if (in_array($normalized, ['aprroved'], true)) {
            return 'approved';
        }

        if (in_array($normalized, ['not_approved', 'notapproved'], true)) {
            return 'unapproved';
        }

        if (in_array($normalized, ['not_aprroved', 'notaprroved'], true)) {
            return 'unapproved';
        }

        if (in_array($normalized, ['need_approve', 'needapprove'], true)) {
            return 'unapproved';
        }

        if (in_array($normalized, ['punlished'], true)) {
            return 'published';
        }

        if (in_array($normalized, ['not_published', 'notpublished'], true)) {
            return 'unpublished';
        }

        if (in_array($normalized, ['not_punlished', 'notpunlished'], true)) {
            return 'unpublished';
        }

        if (in_array($normalized, ['need_publish', 'needpublish'], true)) {
            return 'need_publish';
        }

        return $normalized;
    }

    private function applyInspectionPresetToReportQuery($reportQuery, string $preset): void
    {
        if ($preset === 'approved') {
            $this->applyApprovedConstraint($reportQuery);
            return;
        }

        if ($preset === 'unapproved') {
            $this->applyNeedApproveConstraint($reportQuery);
            return;
        }

        if ($preset === 'published') {
            $this->applyPublishedConstraint($reportQuery, 'publish');
            return;
        }

        if ($preset === 'need_publish') {
            $this->applyNeedPublishConstraint($reportQuery);
            return;
        }

        if ($preset === 'unpublished') {
            $this->applyUnpublishedConstraint($reportQuery, 'publish');
        }
    }

    private function applyPublishedConstraint($query, string $column): void
    {
        $query->whereNotNull($column)
            ->where($column, '!=', 0);
    }

    private function applyApprovedConstraint($query, ?string $table = null): void
    {
        $prefix = $table ? trim($table).'.' : '';

        $query->whereNotNull($prefix.'user_id_approved');
        $query->where($prefix.'code', 'NOT LIKE', '%Duplicated%');
    }

    private function applyUnpublishedConstraint($query, string $column): void
    {
        $query->where(function ($builder) use ($column) {
            $builder->whereNull($column)
                ->orWhere($column, 0);
        });
    }

    private function applyNeedPublishConstraint($query, ?string $table = null): void
    {
        $prefix = $table ? trim($table).'.' : '';

        $this->applyUnpublishedConstraint($query, $prefix.'publish');
        $query->where(function ($builder) use ($prefix) {
            $builder->where($prefix.'code', 'like', '%Duplicated%');
        });
    }

    private function applyNeedApproveConstraint($query, ?string $table = null): void
    {
        $prefix = $table ? trim($table).'.' : '';

        $query->whereNull($prefix.'user_id_approved');
        $this->applyUnpublishedConstraint($query, $prefix.'publish');
        $query->where($prefix.'code', 'NOT LIKE', '%Duplicated%');
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

        $duplicatedPattern = $this->escapeLikeValue($baseCode) . ' - Duplicated%';
        $query->where(function ($builder) use ($column, $baseCode, $exactCode, $duplicatedPattern) {
            $builder->where($column, $baseCode)
                ->orWhere($column, 'like', $duplicatedPattern);

            if (strcasecmp($exactCode, $baseCode) !== 0) {
                $builder->orWhere($column, $exactCode);
            }
        });
    }

    protected function applyLatestInspectionFamilyRowConstraint($query, string $table): void
    {
        $table = trim($table);
        if ($table === '') {
            return;
        }

        $query->whereNotExists(function ($subQuery) use ($table) {
            $subQuery->select(DB::raw(1))
                ->from(DB::raw($table.' as newer_revisions'))
                ->whereColumn('newer_revisions.job_request_id', $table.'.job_request_id')
                ->whereRaw(
                    "TRIM(SUBSTRING_INDEX(newer_revisions.code, ' - Duplicated', 1)) = TRIM(SUBSTRING_INDEX({$table}.code, ' - Duplicated', 1))"
                )
                ->where(function ($nestedQuery) use ($table) {
                    $nestedQuery->whereColumn('newer_revisions.created_at', '>', $table.'.created_at')
                        ->orWhere(function ($tieBreakerQuery) use ($table) {
                            $tieBreakerQuery->whereColumn('newer_revisions.created_at', $table.'.created_at')
                                ->whereColumn('newer_revisions.id', '>', $table.'.id');
                        });
                });
        });
    }

    protected function applyPreferredInspectionFamilyRowConstraint($query, string $table, string $reportableType): void
    {
        $table = trim($table);
        $reportableType = trim($reportableType);
        if ($table === '' || $reportableType === '') {
            return;
        }

        $preset = $this->normalizeInspectionPreset(request('smart_preset', ''));
        if (!in_array($preset, ['approved', 'unapproved', 'need_publish', 'published', 'unpublished'], true)) {
            return;
        }

        $showDuplicatedRows = $preset === 'need_publish';
        $showNaturalRowsOnly = in_array($preset, ['approved', 'unapproved', 'published', 'unpublished'], true);
        $codeOperator = $showDuplicatedRows ? 'like' : 'not like';
        $codePattern = '%Duplicated%';

        if ($showDuplicatedRows || $showNaturalRowsOnly) {
            $query->where($table . '.code', $codeOperator, $codePattern);
        }

        $query->whereNotExists(function ($subQuery) use ($table, $showDuplicatedRows, $showNaturalRowsOnly, $codeOperator, $codePattern) {
            $subQuery->select(DB::raw(1))
                ->from(DB::raw($table.' as newer_revisions'))
                ->whereColumn('newer_revisions.job_request_id', $table.'.job_request_id')
                ->whereRaw(
                    "TRIM(SUBSTRING_INDEX(newer_revisions.code, ' - Duplicated', 1)) = TRIM(SUBSTRING_INDEX({$table}.code, ' - Duplicated', 1))"
                );

            if ($showDuplicatedRows || $showNaturalRowsOnly) {
                $subQuery->where('newer_revisions.code', $codeOperator, $codePattern);
            }

            $subQuery
                ->where(function ($newerQuery) use ($table) {
                    $newerQuery->whereColumn('newer_revisions.created_at', '>', $table.'.created_at')
                        ->orWhere(function ($sameTimeQuery) use ($table) {
                            $sameTimeQuery->whereColumn('newer_revisions.created_at', $table.'.created_at')
                                ->whereColumn('newer_revisions.id', '>', $table.'.id');
                        });
                });
        });
    }

    protected function resolveInspectionRevisionDisplayMeta(string $reportClass, Model $currentReport): array
    {
        $jobRequestId = (int) data_get($currentReport, 'job_request_id');
        $currentCode = trim((string) data_get($currentReport, 'code'));
        $currentId = (int) data_get($currentReport, 'id');

        if ($reportClass === ''
            || !class_exists($reportClass)
            || $jobRequestId <= 0
            || $currentCode === ''
            || $currentId <= 0
            || !$this->supportsInspectionRevisionWorkflow($reportClass)) {
            return [
                'number' => null,
                'date' => null,
                'index' => 0,
            ];
        }

        if (!$this->isNaturalApprovedInspectionReportable($currentReport)) {
            return [
                'number' => null,
                'date' => null,
                'index' => 0,
            ];
        }

        $relatedReports = $reportClass::query()
            ->select(['id', 'created_at'])
            ->with('report')
            ->where('job_request_id', $jobRequestId)
            ->orderBy('created_at')
            ->orderBy('id');

        $this->applyInspectionCodeFamilyConstraint($relatedReports, $currentCode);

        $relatedReports = $relatedReports->get()->filter(function ($report) {
            return $this->isNaturalApprovedInspectionReportable($report);
        })->values();

        if ($relatedReports->count() <= 1) {
            return [
                'number' => null,
                'date' => null,
                'index' => 0,
            ];
        }

        $index = $relatedReports->search(function ($report) use ($currentId) {
            return (int) data_get($report, 'id') === $currentId;
        });

        if ($index === false) {
            $index = 0;
        }

        $createdAt = data_get($currentReport, 'created_at');
        $formattedDate = '';
        if ($createdAt instanceof \Carbon\CarbonInterface) {
            $formattedDate = $createdAt->format('j-M-Y');
        } elseif (!empty($createdAt)) {
            try {
                $formattedDate = \Carbon\Carbon::parse($createdAt)->format('j-M-Y');
            } catch (\Throwable $e) {
                $formattedDate = '';
            }
        }

        return [
            'number' => str_pad((string) (((int) $index) + 1), 2, '0', STR_PAD_LEFT),
            'date' => $formattedDate,
            'index' => ((int) $index) + 1,
        ];
    }

    private function isNaturalApprovedInspectionReportable($reportable): bool
    {
        $report = $this->resolveInspectionReportForReportable($reportable);
        if (!$report
            || !$this->supportsInspectionRevisionWorkflow((string) $report->reportable_type)
            || empty($report->user_id_approved)) {
            return false;
        }

        return stripos((string) $report->code, 'duplicated') === false;
    }

    protected function shouldForkApprovedInspectionRevision($reportable): bool
    {
        return $this->isNaturalApprovedInspectionReportable($reportable);
    }

    protected function resolveInspectionSubmittedCode(Request $request, $reportable, string $jobRequestInputKey = 'lcr_1'): string
    {
        $currentReport = $this->resolveInspectionReportForReportable($reportable);
        $currentCode = trim((string) data_get($reportable, 'code'));
        $reportCode = trim((string) data_get($currentReport, 'code'));
        $requestedCode = trim((string) $request->input('code', $currentCode !== '' ? $currentCode : $reportCode));
        $isApprovedRevision = $this->shouldForkApprovedInspectionRevision($reportable);
        $isDuplicatedClone = stripos($currentCode, 'duplicated') !== false
            || stripos($reportCode, 'duplicated') !== false;

        if ($request->input('publish') === 'yes' && !$isApprovedRevision && !$isDuplicatedClone) {
            $jobRequest = \App\Models\WorkFlow\JobRequest::find((int) $request->input($jobRequestInputKey));
            if ($jobRequest) {
                return (string) json_decode(json_encode(\App\Http\Controllers\CustomController::getReportData($jobRequest)))->original->lastcode;
            }
        }

        if ($requestedCode !== '') {
            return $requestedCode;
        }

        return $currentCode !== '' ? $currentCode : $reportCode;
    }

    private function resolveInspectionVersionLabel($reportable, InspectionReport $report, int &$approvedRevisionIndex): string
    {
        $reportableType = (string) $report->reportable_type;
        $supportsRevision = $this->supportsInspectionRevisionWorkflow($reportableType);
        $isDuplicated = stripos((string) $report->code, 'duplicated') !== false;
        $isApproved = !empty($report->user_id_approved);

        if ($supportsRevision && !$isDuplicated && $isApproved) {
            $approvedRevisionIndex++;
            return 'REV' . $approvedRevisionIndex;
        }

        if ($isDuplicated) {
            return 'PENDING PUBLISH';
        }

        if ($supportsRevision) {
            return 'NEED APPROVE';
        }

        return $isApproved ? 'APPROVED' : 'PENDING';
    }

    private function supportsInspectionRevisionWorkflow(?string $reportableType): bool
    {
        return InspectionRevisionWorkflow::supports($reportableType);
    }

    protected function buildInspectionUnpublishedActionButtons($row, string $modelClass, int $modelId, string $showRoute, string $publishRoute, ?string $editRoute = null): string
    {
        $record = class_exists($modelClass) ? $modelClass::find($modelId) : null;
        $isDuplicatedRevision = $this->resolveInspectionIsDuplicatedFromRow($row);
        $btn = '';

        if (!$isDuplicatedRevision) {
            $btn .= '<a class="btn btn-dark mr-1" href="' . route($showRoute, $modelId) . '">Open Report</a>';
        }

        $canPublish = auth()->user()->can('create', $modelClass)
            || ($record && auth()->user()->can('create', $record));

        if ($isDuplicatedRevision && $canPublish) {
            $btn .= '<a href="' . route($publishRoute, $modelId) . '" class="btn btn-icon btn-info mr-1">Publish</a>';
        }

        if ($editRoute !== null && $record && auth()->user()->can('update', $record)) {
            $btn .= '<a href="' . route($editRoute, $modelId) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
        }

        return $btn;
    }

    protected function buildInspectionUnpublishedMultiPageActionButtons(
        string $modelClass,
        int $modelId,
        string $showRoute,
        string $publishRoute,
        string $editRoute,
        string $secondRelation,
        string $secondCreateRoute,
        string $secondEditRoute
    ): string {
        $record = class_exists($modelClass) ? $modelClass::find($modelId) : null;
        if (!$record) {
            return '';
        }

        $isDuplicatedRevision = $this->resolveInspectionIsDuplicatedFromRow($record);
        $btn = '';

        if (!$isDuplicatedRevision) {
            $btn .= '<a class="btn btn-dark mr-1" href="' . route($showRoute, $modelId) . '">Open Report</a>';
        }

        $canPublish = auth()->user()->can('create', $modelClass)
            || auth()->user()->can('create', $record);

        if ($isDuplicatedRevision && $canPublish) {
            $btn .= '<a href="' . route($publishRoute, $modelId) . '" class="btn btn-icon btn-info mr-1">Publish</a>';
        }

        if (auth()->user()->can('update', $record)) {
            $btn .= '<div class="btn-group mr-1">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="la la-pencil"></i></button>
                <div class="dropdown-menu">
                    <a href="' . route($editRoute, $modelId) . '" class="dropdown-item">Edit 1st Page</a>
                    <div class="dropdown-divider"></div>';

            $secondPage = data_get($record, $secondRelation);
            if ($secondPage) {
                $btn .= '<a href="' . route($secondEditRoute, $secondPage->id) . '" class="dropdown-item">Edit 2nd Page</a>';
            } else {
                $btn .= '<a href="' . route($secondCreateRoute, $modelId) . '" class="dropdown-item">Create 2nd Page</a>';
            }

            $btn .= '</div></div>';
        }

        return $btn;
    }

    private function resolveInspectionIsDuplicatedFromRow($row): bool
    {
        if (is_object($row)) {
            if (isset($row->code) && stripos((string) $row->code, 'duplicated') !== false) {
                return true;
            }

            if (isset($row->report_code) && stripos((string) $row->report_code, 'duplicated') !== false) {
                return true;
            }

            if (isset($row->report_internal_code) && stripos((string) $row->report_internal_code, 'duplicated') !== false) {
                return true;
            }

            $relatedReport = $this->resolveInspectionReportRelationFromRow($row);
            if ($relatedReport && isset($relatedReport->code) && stripos((string) $relatedReport->code, 'duplicated') !== false) {
                return true;
            }
        }

        if (is_array($row)) {
            if (!empty($row['code']) && stripos((string) $row['code'], 'duplicated') !== false) {
                return true;
            }

            if (!empty($row['report_code']) && stripos((string) $row['report_code'], 'duplicated') !== false) {
                return true;
            }

            if (!empty($row['report_internal_code']) && stripos((string) $row['report_internal_code'], 'duplicated') !== false) {
                return true;
            }

            if (!empty($row['report']['code']) && stripos((string) $row['report']['code'], 'duplicated') !== false) {
                return true;
            }
        }

        return false;
    }

    private function resolveInspectionReportForReportable($reportable): ?InspectionReport
    {
        if (!$reportable) {
            return null;
        }

        $relatedReport = data_get($reportable, 'report');
        if ($relatedReport instanceof InspectionReport) {
            return $relatedReport;
        }

        $reportableClass = is_object($reportable) ? get_class($reportable) : '';
        $reportableId = (int) data_get($reportable, 'id');
        if ($reportableClass === '' || $reportableId <= 0) {
            return null;
        }

        return InspectionReport::query()
            ->where('reportable_type', $reportableClass)
            ->where('reportable_id', $reportableId)
            ->latest('id')
            ->first();
    }

    protected function persistInspectionReportState($reportOwner, array $attributes): bool
    {
        return app(InspectionReportLifecycleService::class)->persistForOwner($reportOwner, $attributes);
    }

    private function resolveNextInspectionNaturalCode(int $jobRequestId, int $excludeReportId = 0): string
    {
        return app(InspectionReportLifecycleService::class)->resolveNextNaturalCode($jobRequestId, $excludeReportId);
    }

    private function syncInspectionReportableCode(string $reportableType, int $reportableId, string $code): void
    {
        app(InspectionReportLifecycleService::class)->syncReportableCode($reportableType, $reportableId, $code);
    }

    private function resolveInspectionSearchColumn($query, string $columnName, string $defaultTable = ''): ?string
    {
        $columnName = trim($columnName);
        if ($columnName === '') {
            return null;
        }

        $candidate = str_replace('`', '', $columnName);
        if (strpos($candidate, '.') !== false) {
            [$table, $column] = explode('.', $candidate, 2);
            if ($table !== '' && $column !== '' && Schema::hasColumn($table, $column)) {
                return $table.'.'.$column;
            }
            return null;
        }

        if ($defaultTable !== '' && Schema::hasColumn($defaultTable, $candidate)) {
            return $defaultTable.'.'.$candidate;
        }

        $aliases = $this->extractSelectedAliases($query);
        if (in_array($candidate, $aliases, true) && preg_match('/^[A-Za-z0-9_]+$/', $candidate)) {
            return $candidate;
        }

        return null;
    }

}













