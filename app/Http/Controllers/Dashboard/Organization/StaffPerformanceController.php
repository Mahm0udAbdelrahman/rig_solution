<?php

namespace App\Http\Controllers\Dashboard\Organization;

use App\Http\Controllers\Controller;
use App\Models\Inspection\InspectionReport;
use App\Models\User;
use App\Models\WorkFlow\JobRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class StaffPerformanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && (bool) $user->isSuperAdmin(), 403);

        $windowOptions = [
            'overall' => ['label' => 'Overall', 'seconds' => null],
            '7d' => ['label' => '7 Days', 'seconds' => 604800],
            '30d' => ['label' => '30 Days', 'seconds' => 2592000],
            '90d' => ['label' => '90 Days', 'seconds' => 7776000],
            '180d' => ['label' => '180 Days', 'seconds' => 15552000],
        ];
        $sortOptions = [
            'name' => 'Employee',
            'jcf_count' => 'JCFs',
            'report_count' => 'Reports',
            'total_activity' => 'Total',
            'top_section' => 'Top Section',
            'top_type' => 'Top Type',
            'last_activity_at' => 'Latest',
        ];
        $tabOptions = ['overview', 'charts'];

        $selectedWindow = (string) request('window', '30d');
        if (!array_key_exists($selectedWindow, $windowOptions)) {
            $selectedWindow = '30d';
        }

        $selectedTab = (string) request('tab', 'overview');
        if (!in_array($selectedTab, $tabOptions, true)) {
            $selectedTab = 'overview';
        }

        $selectedSortBy = (string) request('sort_by', 'total_activity');
        if (!array_key_exists($selectedSortBy, $sortOptions)) {
            $selectedSortBy = 'total_activity';
        }

        $selectedSortDirection = strtolower((string) request('sort_direction', 'desc')) === 'asc' ? 'asc' : 'desc';
        $selectedUserId = max(0, (int) request('user_id', 0));
        $selectedSection = strtolower(trim((string) request('section', 'all')));

        $sectionOptions = $this->inspectionSectionOptions();
        if ($selectedSection !== 'all' && !$sectionOptions->contains(fn (array $row) => $row['key'] === $selectedSection)) {
            $selectedSection = 'all';
        }

        $windowStartedAt = $selectedWindow === 'overall'
            ? null
            : now()->subSeconds((int) ($windowOptions[$selectedWindow]['seconds'] ?? 2592000));
        $windowEndedAt = now();

        $inspectionScopeQuery = InspectionReport::query()
            ->whereNotNull('user_id')
            ->where('code', 'not like', '%Duplicated%')
            ->whereNotNull('reportable_type');

        if ($windowStartedAt) {
            $inspectionScopeQuery->where('created_at', '>=', $windowStartedAt);
        }

        if ($selectedSection !== 'all') {
            $inspectionScopeQuery->where('reportable_type', 'like', 'App\\Models\\Inspection\\'.ucfirst($selectedSection).'\\%');
        }

        $inspectionBaseQuery = clone $inspectionScopeQuery;
        if ($selectedUserId > 0) {
            $inspectionBaseQuery->where('user_id', $selectedUserId);
        }

        $inspectionActivityByUserQuery = clone $inspectionScopeQuery;

        $jcfScopeQuery = JobRequest::query()
            ->whereNotNull('user_id');

        if ($windowStartedAt) {
            $jcfScopeQuery->where('created_at', '>=', $windowStartedAt);
        }

        $jcfBaseQuery = clone $jcfScopeQuery;
        if ($selectedUserId > 0) {
            $jcfBaseQuery->where('user_id', $selectedUserId);
        }

        $jcfActivityByUserQuery = clone $jcfScopeQuery;

        $reportCount = (int) (clone $inspectionBaseQuery)->count();
        $jcfCount = (int) (clone $jcfBaseQuery)->count();
        $activeInspectionEmployees = (int) (clone $inspectionBaseQuery)->distinct('user_id')->count('user_id');
        $activeJcfEmployees = (int) (clone $jcfBaseQuery)->distinct('user_id')->count('user_id');

        $sectionRows = (clone $inspectionBaseQuery)
            ->selectRaw('reportable_type, COUNT(*) as total')
            ->groupBy('reportable_type')
            ->get()
            ->reduce(function (array $carry, InspectionReport $row) {
                $section = $this->resolveInspectionSectionMeta((string) $row->reportable_type);
                if (!isset($carry[$section['key']])) {
                    $carry[$section['key']] = [
                        'key' => $section['key'],
                        'label' => $section['label'],
                        'count' => 0,
                    ];
                }
                $carry[$section['key']]['count'] += (int) ($row->total ?? 0);
                return $carry;
            }, []);

        $sectionRows = collect($sectionRows)->sortByDesc('count')->values();

        $typeRows = (clone $inspectionBaseQuery)
            ->selectRaw('reportable_type, COUNT(*) as total')
            ->groupBy('reportable_type')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                return [
                    'type' => $this->resolveInspectionTypeLabel((string) $row->reportable_type),
                    'section' => $this->resolveInspectionSectionMeta((string) $row->reportable_type)['label'],
                    'count' => (int) ($row->total ?? 0),
                ];
            })
            ->values();

        $jcfSubjectRows = (clone $jcfBaseQuery)
            ->selectRaw("COALESCE(NULLIF(subject, ''), NULLIF(job_requierd_details, ''), 'Unspecified') as subject_label, COUNT(*) as total")
            ->groupBy('subject_label')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                return [
                    'subject' => (string) ($row->subject_label ?? 'Unspecified'),
                    'count' => (int) ($row->total ?? 0),
                ];
            })
            ->values();

        $jcfByUser = (clone $jcfActivityByUserQuery)
            ->selectRaw('user_id, COUNT(*) as total, MAX(created_at) as last_created_at')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $reportsByUser = (clone $inspectionActivityByUserQuery)
            ->selectRaw('user_id, COUNT(*) as total, MAX(created_at) as last_created_at')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $employeeBreakdowns = (clone $inspectionActivityByUserQuery)
            ->selectRaw('user_id, reportable_type, COUNT(*) as total')
            ->groupBy('user_id', 'reportable_type')
            ->get()
            ->groupBy('user_id')
            ->map(function (Collection $rows) {
                $sectionCounts = [];
                $typeCounts = [];

                foreach ($rows as $row) {
                    $section = $this->resolveInspectionSectionMeta((string) $row->reportable_type)['label'];
                    $type = $this->resolveInspectionTypeLabel((string) $row->reportable_type);
                    $sectionCounts[$section] = ($sectionCounts[$section] ?? 0) + (int) ($row->total ?? 0);
                    $typeCounts[$type] = ($typeCounts[$type] ?? 0) + (int) ($row->total ?? 0);
                }

                arsort($sectionCounts);
                arsort($typeCounts);

                return [
                    'sections' => $sectionCounts,
                    'types' => $typeCounts,
                ];
            });

        $creators = User::query()
            ->where('is_active', 1)
            ->whereHas('employee')
            ->with('employee:id,name')
            ->get(['id', 'employee_id'])
            ->map(function (User $candidate) {
                return [
                    'id' => (int) $candidate->id,
                    'name' => optional($candidate->employee)->name ?: ('User #'.$candidate->id),
                ];
            })
            ->sortBy('name')
            ->values();

        $creatorMap = $creators->keyBy('id');

        $activityUserIds = collect($jcfByUser->keys())
            ->merge($reportsByUser->keys())
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $employeeRowsRanked = $activityUserIds
            ->map(function ($userId) use ($jcfByUser, $reportsByUser, $creatorMap, $employeeBreakdowns) {
                $userId = (int) $userId;
                $jcf = $jcfByUser->get($userId);
                $report = $reportsByUser->get($userId);
                $breakdown = $employeeBreakdowns->get($userId, ['sections' => [], 'types' => []]);
                $latestJcfAt = !empty($jcf?->last_created_at) ? Carbon::parse($jcf->last_created_at) : null;
                $latestReportAt = !empty($report?->last_created_at) ? Carbon::parse($report->last_created_at) : null;
                $lastActivityAt = $latestJcfAt && $latestReportAt
                    ? ($latestJcfAt->greaterThan($latestReportAt) ? $latestJcfAt : $latestReportAt)
                    : ($latestJcfAt ?: $latestReportAt);

                return [
                    'user_id' => $userId,
                    'name' => $creatorMap->get($userId)['name'] ?? ('User #'.$userId),
                    'jcf_count' => (int) ($jcf->total ?? 0),
                    'report_count' => (int) ($report->total ?? 0),
                    'total_activity' => (int) ($jcf->total ?? 0) + (int) ($report->total ?? 0),
                    'sections_count' => count($breakdown['sections']),
                    'top_section' => array_key_first($breakdown['sections']) ?: '-',
                    'top_type' => array_key_first($breakdown['types']) ?: '-',
                    'last_activity_at' => $lastActivityAt,
                ];
            })
            ->sortByDesc('total_activity')
            ->values();

        $topEmployee = $employeeRowsRanked->first();
        $topSection = $sectionRows->first();
        $topJcfSubject = $jcfSubjectRows->first();
        $focusedEmployee = $selectedUserId > 0
            ? $employeeRowsRanked->firstWhere('user_id', $selectedUserId)
            : $topEmployee;

        $employeeRows = $this->sortEmployeeRows($employeeRowsRanked, $selectedSortBy, $selectedSortDirection)
            ->take(20)
            ->values();

        if (!empty($focusedEmployee) && !$employeeRows->contains(fn (array $row) => $row['user_id'] === (int) $focusedEmployee['user_id'])) {
            $employeeRows = collect([$focusedEmployee])
                ->merge($employeeRows)
                ->unique('user_id')
                ->take(20)
                ->values();
        }

        $focusedUserId = (int) ($focusedEmployee['user_id'] ?? 0);
        $focusedTypeRows = collect();
        $focusedSectionRows = collect();
        $focusedJcfRows = collect();
        $focusedReportRows = collect();
        $focusedEmployeeInsights = [
            'jcf_share' => 0.0,
            'report_share' => 0.0,
            'activity_share' => 0.0,
            'reports_per_jcf' => 0.0,
            'last_activity_label' => '-',
        ];
        $focusedEvaluationScores = [
            'management' => null,
            'customer' => null,
            'system' => 0.0,
            'system_label' => 'No calculated score yet.',
        ];

        if ($focusedUserId > 0) {
            $focusedTypeRows = (clone $inspectionScopeQuery)
                ->where('user_id', $focusedUserId)
                ->selectRaw('reportable_type, COUNT(*) as total')
                ->groupBy('reportable_type')
                ->orderByDesc('total')
                ->limit(8)
                ->get()
                ->map(function ($row) {
                    return [
                        'type' => $this->resolveInspectionTypeLabel((string) $row->reportable_type),
                        'section' => $this->resolveInspectionSectionMeta((string) $row->reportable_type)['label'],
                        'count' => (int) ($row->total ?? 0),
                    ];
                })
                ->values();

            $focusedSectionRows = (clone $inspectionScopeQuery)
                ->where('user_id', $focusedUserId)
                ->selectRaw('reportable_type, COUNT(*) as total')
                ->groupBy('reportable_type')
                ->get()
                ->reduce(function (array $carry, InspectionReport $row) {
                    $section = $this->resolveInspectionSectionMeta((string) $row->reportable_type);
                    if (!isset($carry[$section['key']])) {
                        $carry[$section['key']] = [
                            'key' => $section['key'],
                            'label' => $section['label'],
                            'count' => 0,
                        ];
                    }
                    $carry[$section['key']]['count'] += (int) ($row->total ?? 0);
                    return $carry;
                }, []);

            $focusedSectionRows = collect($focusedSectionRows)->sortByDesc('count')->values();

            $focusedJcfRows = (clone $jcfScopeQuery)
                ->where('user_id', $focusedUserId)
                ->orderByDesc('created_at')
                ->get(['id', 'code', 'subject', 'job_requierd_details', 'created_at'])
                ->map(function (JobRequest $jobRequest) {
                    return [
                        'id' => (int) $jobRequest->id,
                        'code' => (string) $jobRequest->code,
                        'subject' => (string) ($jobRequest->subject ?: $jobRequest->job_requierd_details ?: 'Unspecified'),
                        'created_at' => $jobRequest->created_at,
                        'overview_url' => route('jobRequest.overview', $jobRequest->id),
                    ];
                })
                ->values();

            $focusedReportRows = (clone $inspectionScopeQuery)
                ->where('user_id', $focusedUserId)
                ->with(['job_request:id,code'])
                ->orderByDesc('created_at')
                ->get(['id', 'job_request_id', 'code', 'reportable_type', 'reportable_id', 'created_at'])
                ->map(function (InspectionReport $report) {
                    return [
                        'id' => (int) $report->id,
                        'report_no' => (string) optional($report->job_request)->code.'/'.$report->code,
                        'type' => $this->resolveInspectionTypeLabel((string) $report->reportable_type),
                        'section' => $this->resolveInspectionSectionMeta((string) $report->reportable_type)['label'],
                        'created_at' => $report->created_at,
                        'show_url' => $this->resolveInspectionReportShowUrl($report),
                    ];
                })
                ->values();

            $focusedEmployeeInsights = [
                'jcf_share' => $jcfCount > 0 ? round((((int) ($focusedEmployee['jcf_count'] ?? 0)) / $jcfCount) * 100, 1) : 0.0,
                'report_share' => $reportCount > 0 ? round((((int) ($focusedEmployee['report_count'] ?? 0)) / $reportCount) * 100, 1) : 0.0,
                'activity_share' => ($jcfCount + $reportCount) > 0
                    ? round((((int) ($focusedEmployee['total_activity'] ?? 0)) / ($jcfCount + $reportCount)) * 100, 1)
                    : 0.0,
                'reports_per_jcf' => (int) ($focusedEmployee['jcf_count'] ?? 0) > 0
                    ? round(((int) ($focusedEmployee['report_count'] ?? 0)) / max(1, (int) ($focusedEmployee['jcf_count'] ?? 0)), 1)
                    : round((float) ($focusedEmployee['report_count'] ?? 0), 1),
                'last_activity_label' => optional($focusedEmployee['last_activity_at'] ?? null)->diffForHumans() ?: '-',
            ];

            $systemScore = min(
                100,
                round(
                    ($focusedEmployeeInsights['report_share'] * 0.35)
                    + ($focusedEmployeeInsights['activity_share'] * 0.35)
                    + min($focusedEmployeeInsights['reports_per_jcf'] * 10, 20)
                    + min(((int) ($focusedEmployee['sections_count'] ?? 0)) * 5, 10),
                    1
                )
            );

            $focusedEvaluationScores = [
                'management' => null,
                'customer' => null,
                'system' => $systemScore,
                'system_label' => $systemScore >= 80
                    ? 'High output and broad contribution in the selected scope.'
                    : ($systemScore >= 60
                        ? 'Solid operational contribution in the selected scope.'
                        : 'Needs more activity or wider contribution to improve score.'),
            ];
        }

        $recentJcfs = (clone $jcfBaseQuery)
            ->with('user.employee:id,name')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get(['id', 'code', 'subject', 'job_requierd_details', 'user_id', 'created_at'])
            ->map(function (JobRequest $jobRequest) {
                return [
                    'id' => (int) $jobRequest->id,
                    'code' => (string) $jobRequest->code,
                    'subject' => (string) ($jobRequest->subject ?: $jobRequest->job_requierd_details ?: 'Unspecified'),
                    'employee_name' => optional(optional($jobRequest->user)->employee)->name ?: ('User #'.$jobRequest->user_id),
                    'created_at' => $jobRequest->created_at,
                    'overview_url' => route('jobRequest.overview', $jobRequest->id),
                ];
            })
            ->values();

        $recentReports = (clone $inspectionBaseQuery)
            ->with(['job_request:id,code', 'user.employee:id,name'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get(['id', 'job_request_id', 'code', 'reportable_type', 'reportable_id', 'user_id', 'created_at'])
            ->map(function (InspectionReport $report) {
                return [
                    'id' => (int) $report->id,
                    'report_no' => (string) optional($report->job_request)->code.'/'.$report->code,
                    'type' => $this->resolveInspectionTypeLabel((string) $report->reportable_type),
                    'employee_name' => optional(optional($report->user)->employee)->name ?: ('User #'.$report->user_id),
                    'created_at' => $report->created_at,
                    'show_url' => $this->resolveInspectionReportShowUrl($report),
                ];
            })
            ->values();

        $timelineRows = $this->buildTimelineRows(
            clone $jcfBaseQuery,
            clone $inspectionBaseQuery,
            $windowStartedAt,
            $windowEndedAt,
            $selectedWindow
        );

        return view('layouts.organization.staff-performance.index', [
            'page_name' => 'Staff Performance',
            'window_options' => $windowOptions,
            'sort_options' => $sortOptions,
            'selected_window' => $selectedWindow,
            'selected_user_id' => $selectedUserId,
            'selected_section' => $selectedSection,
            'selected_tab' => $selectedTab,
            'selected_sort_by' => $selectedSortBy,
            'selected_sort_direction' => $selectedSortDirection,
            'window_started_at' => $windowStartedAt,
            'window_ends_at' => $windowEndedAt,
            'creators' => $creators,
            'section_options' => $sectionOptions,
            'jcf_count' => $jcfCount,
            'report_count' => $reportCount,
            'active_jcf_employees' => $activeJcfEmployees,
            'active_inspection_employees' => $activeInspectionEmployees,
            'top_employee' => $topEmployee,
            'top_section' => $topSection,
            'top_jcf_subject' => $topJcfSubject,
            'employee_rows' => $employeeRows,
            'employee_chart_rows' => $employeeRowsRanked->take(8)->values(),
            'section_rows' => $sectionRows,
            'type_rows' => $typeRows,
            'timeline_rows' => $timelineRows,
            'jcf_subject_rows' => $jcfSubjectRows,
            'focused_employee' => $focusedEmployee,
            'focused_employee_insights' => $focusedEmployeeInsights,
            'focused_evaluation_scores' => $focusedEvaluationScores,
            'focused_type_rows' => $focusedTypeRows,
            'focused_section_rows' => $focusedSectionRows,
            'focused_jcf_rows' => $focusedJcfRows,
            'focused_report_rows' => $focusedReportRows,
            'recent_jcfs' => $recentJcfs,
            'recent_reports' => $recentReports,
        ]);
    }

    private function inspectionSectionOptions(): Collection
    {
        return InspectionReport::query()
            ->whereNotNull('reportable_type')
            ->distinct()
            ->pluck('reportable_type')
            ->map(function ($type) {
                return $this->resolveInspectionSectionMeta((string) $type);
            })
            ->unique('key')
            ->sortBy('label')
            ->values();
    }

    private function resolveInspectionSectionMeta(string $reportableType): array
    {
        $cleanType = str_replace('App\\Models\\Inspection\\', '', $reportableType);
        $segments = array_values(array_filter(explode('\\', $cleanType)));
        $sectionKey = strtolower((string) ($segments[0] ?? 'inspection'));
        $sectionLabel = trim((string) preg_replace('/(?<!^)[A-Z]/', ' $0', $segments[0] ?? 'Inspection'));

        return [
            'key' => $sectionKey !== '' ? $sectionKey : 'inspection',
            'label' => $sectionLabel !== '' ? $sectionLabel : 'Inspection',
        ];
    }

    private function resolveInspectionTypeLabel(string $reportableType): string
    {
        $cleanType = str_replace('App\\Models\\Inspection\\', '', $reportableType);
        $segments = array_values(array_filter(explode('\\', $cleanType)));
        $className = end($segments) ?: 'Report';

        return trim((string) preg_replace('/(?<!^)[A-Z]/', ' $0', $className));
    }

    private function sortEmployeeRows(Collection $rows, string $sortBy, string $sortDirection): Collection
    {
        return $rows
            ->sortBy(function (array $row) use ($sortBy) {
                return match ($sortBy) {
                    'name' => strtolower((string) ($row['name'] ?? '')),
                    'jcf_count' => (int) ($row['jcf_count'] ?? 0),
                    'report_count' => (int) ($row['report_count'] ?? 0),
                    'top_section' => strtolower((string) ($row['top_section'] ?? '')),
                    'top_type' => strtolower((string) ($row['top_type'] ?? '')),
                    'last_activity_at' => optional($row['last_activity_at'] ?? null)->timestamp ?? 0,
                    default => (int) ($row['total_activity'] ?? 0),
                };
            }, SORT_NATURAL, $sortDirection === 'desc')
            ->values();
    }

    private function buildTimelineRows($jcfQuery, $inspectionQuery, ?Carbon $windowStartedAt, Carbon $windowEndedAt, string $selectedWindow): Collection
    {
        $bucketDays = match ($selectedWindow) {
            'overall' => 30,
            '7d' => 1,
            '90d' => 7,
            '180d' => 15,
            default => 3,
        };

        $jcfDailyCounts = (clone $jcfQuery)
            ->selectRaw('DATE(created_at) as activity_date, COUNT(*) as total')
            ->groupBy('activity_date')
            ->pluck('total', 'activity_date');

        $inspectionDailyCounts = (clone $inspectionQuery)
            ->selectRaw('DATE(created_at) as activity_date, COUNT(*) as total')
            ->groupBy('activity_date')
            ->pluck('total', 'activity_date');

        if ($windowStartedAt === null) {
            $firstJcfDate = collect($jcfDailyCounts->keys())->filter()->sort()->first();
            $firstInspectionDate = collect($inspectionDailyCounts->keys())->filter()->sort()->first();
            $firstActivityDate = collect([$firstJcfDate, $firstInspectionDate])->filter()->sort()->first();
            $windowStartedAt = $firstActivityDate
                ? Carbon::parse((string) $firstActivityDate)->startOfDay()
                : $windowEndedAt->copy()->startOfDay();
        }

        $rows = collect();
        $cursor = $windowStartedAt->copy()->startOfDay();
        $windowEndDay = $windowEndedAt->copy()->endOfDay();

        while ($cursor->lessThanOrEqualTo($windowEndDay)) {
            $bucketStart = $cursor->copy();
            $bucketEnd = $cursor->copy()->addDays($bucketDays - 1)->endOfDay();

            if ($bucketEnd->greaterThan($windowEndDay)) {
                $bucketEnd = $windowEndDay->copy();
            }

            $jcfCount = 0;
            $inspectionCount = 0;
            $dayCursor = $bucketStart->copy();

            while ($dayCursor->lessThanOrEqualTo($bucketEnd)) {
                $dayKey = $dayCursor->toDateString();
                $jcfCount += (int) ($jcfDailyCounts[$dayKey] ?? 0);
                $inspectionCount += (int) ($inspectionDailyCounts[$dayKey] ?? 0);
                $dayCursor->addDay();
            }

            $rows->push([
                'label' => $bucketStart->isSameDay($bucketEnd)
                    ? $bucketStart->format('d M')
                    : $bucketStart->format('d M').' - '.$bucketEnd->format('d M'),
                'jcf_count' => $jcfCount,
                'report_count' => $inspectionCount,
            ]);

            $cursor = $bucketEnd->copy()->addDay()->startOfDay();
        }

        return $rows;
    }

    private function resolveInspectionReportShowUrl(InspectionReport $report): ?string
    {
        $reportableId = (int) ($report->reportable_id ?? 0);
        if ($reportableId <= 0) {
            return null;
        }

        $routeName = match (class_basename((string) $report->reportable_type)) {
            'Crane' => 'crane.show',
            'OverheadCrane' => 'overheadCrane.show',
            'Forklift' => 'forklift.show',
            'ThroughExamination' => 'throughExamination.show',
            'Defect' => 'defect.show',
            'Lregister' => 'lregister.show',
            'Mpipt' => 'mpipt.show',
            'Visual' => 'visual.show',
            'Ultrasonic' => 'ultrasonic.show',
            'Summary' => 'summary.show',
            'Attached' => 'attached.show',
            'WitnessHydro' => 'witnessHydro.show',
            'HighPressure' => 'highPressure.show',
            'High2Pressure' => 'high2Pressure.show',
            'High3Pressure' => 'high3Pressure.show',
            'TreatingIron' => 'treatingIron.show',
            'DrawingInspection' => 'drawingInspection.show',
            'Nregister' => 'nregister.show',
            'PipesSummaryReport' => 'pipesSummaryReports.show',
            'DrillPipe' => 'drillPipe.show',
            'HeavyWeightPipe' => 'heavyWeightPipe.show',
            'DrillCollar' => 'drillCollar.show',
            'SubsDimensional' => 'subsDimensional.show',
            'TubingString' => 'tubingString.show',
            'StabilizerInspection' => 'stabilizerInspection.show',
            'ReamerInspection' => 'reamerInspection.show',
            'LinkInspection' => 'linkInspection.show',
            'Pbl' => 'pbl.show',
            'TubingCasing' => 'tubingCasing.show',
            'DropObject' => 'dropObject.show',
            'CalibrationPressureGauge' => 'calibrationPressureGauge.show',
            'CalibrationPressureTest' => 'calibrationPressureTest.show',
            'CalibrationYoke' => 'calibrationYoke.show',
            'CalibrationTorque' => 'calibrationTorque.show',
            default => null,
        };

        return $routeName ? route($routeName, $reportableId) : null;
    }
}
