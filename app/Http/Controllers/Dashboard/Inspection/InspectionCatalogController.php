<?php

namespace App\Http\Controllers\Dashboard\Inspection;

use App\Http\Controllers\Controller;
use App\Models\Inspection\InspectionReport;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InspectionCatalogController extends Controller
{
    public function index(): View
    {
        return view('layouts.inspection.index', [
            'page_name' => 'Inspections',
        ]);
    }

    public function lifting(): View
    {
        return $this->renderCatalog('lifting');
    }

    public function ndt(): View
    {
        return $this->renderCatalog('ndt');
    }

    public function tubular(): View
    {
        return $this->renderCatalog('tubular');
    }

    public function dropObject(): View
    {
        return $this->renderCatalog('drop_object');
    }

    public function calibration(): View
    {
        return $this->renderCatalog('calibration');
    }

    public function overview(): View
    {
        return $this->renderCatalog(null);
    }

    private function renderCatalog(?string $sectionKey): View
    {
        $sections = $this->buildSections();

        if ($sectionKey !== null) {
            $section = $sections->firstWhere('key', $sectionKey);
            abort_if($section === null, 404);
            abort_if(($section['visible_item_count'] ?? 0) === 0, 403);

            return view('layouts.inspection.catalog', [
                'page_name' => $section['page_name'],
                'catalog_mode' => 'section',
                'catalog_sections' => collect([$section]),
                'catalog_summary' => $this->buildSummary(collect([$section])),
                'catalog_spotlights' => $this->buildSpotlights($section),
                'catalog_recent_activity' => $this->buildRecentActivity(collect([$section])),
                'catalog_active_section' => $section,
            ]);
        }

        return view('layouts.inspection.catalog', [
            'page_name' => 'All Inspections',
            'catalog_mode' => 'all',
            'catalog_sections' => $sections,
            'catalog_summary' => $this->buildSummary($sections),
            'catalog_spotlights' => $this->buildAllOverviewSpotlights($sections),
            'catalog_recent_activity' => $this->buildRecentActivity($sections),
            'catalog_active_section' => null,
        ]);
    }

    private function buildSections(): Collection
    {
        $definition = collect($this->catalogDefinition());
        $reportMetrics = $this->resolveReportMetricsByType($definition);

        return $definition
            ->map(function (array $section) use ($reportMetrics): array {
                $visibleItems = collect($section['items'])
                    ->map(function (array $item) use ($reportMetrics): array {
                        $item['can_view'] = auth()->user()->can('viewAny', $item['policy']);
                        $item['can_create'] = auth()->user()->can('create', $item['policy']);
                        $item['is_visible'] = $item['can_view'] || $item['can_create'];
                        $item['index_url'] = $item['can_view'] ? route($item['index_route']) : null;
                        $item['create_url'] = $item['can_create'] ? route($item['create_route']) : null;
                        $item['primary_url'] = $item['index_url'] ?? $item['create_url'];
                        $item['display_title'] = $item['short_title'] ?? $item['title'];
                        $item['full_title'] = $item['title'];
                        $item['icon_class'] = $item['icon_class'] ?? 'la la-file-text-o';
                        $metrics = $reportMetrics[$item['policy']] ?? null;
                        $item['total_count'] = $item['can_view'] ? (int) ($metrics['total_count'] ?? 0) : 0;
                        $item['need_approve_count'] = $item['can_view'] ? (int) ($metrics['need_approve_count'] ?? 0) : 0;
                        $item['need_publish_count'] = $item['can_view'] ? (int) ($metrics['need_publish_count'] ?? 0) : 0;
                        $item['published_count'] = $item['can_view'] ? (int) ($metrics['published_count'] ?? 0) : 0;
                        $item['need_approve_url'] = $this->buildPresetUrl($item, 'need_approve');
                        $item['need_publish_url'] = $this->buildPresetUrl($item, 'need_publish');
                        $item['filter_tags'] = array_values(array_filter([
                            'all',
                            $item['need_approve_count'] > 0 ? 'need_approve' : null,
                            $item['need_publish_count'] > 0 ? 'need_publish' : null,
                            ($item['need_approve_count'] > 0 || $item['need_publish_count'] > 0) ? 'queues' : null,
                            $item['published_count'] > 0 ? 'published_heavy' : null,
                            ($item['total_count'] === 0 && $item['can_create']) ? 'empty_new' : null,
                        ]));
                        $item['keywords'] = implode(' ', array_filter([
                            $item['display_title'] ?? '',
                            $item['title'] ?? '',
                            $item['group'] ?? '',
                            implode(' ', $item['search_terms'] ?? []),
                        ]));

                        return $item;
                    })
                    ->filter(function (array $item): bool {
                        return $item['is_visible'] === true;
                    })
                    ->values();

                $section['items'] = $visibleItems;
                $section['visible_item_count'] = $visibleItems->count();
                $section['creatable_item_count'] = $visibleItems->where('can_create', true)->count();
                $section['pending_approve_count'] = (int) $visibleItems->sum('need_approve_count');
                $section['pending_publish_count'] = (int) $visibleItems->sum('need_publish_count');
                $section['published_count'] = (int) $visibleItems->sum('published_count');
                $section['total_report_count'] = (int) $visibleItems->sum('total_count');
                $section['grouped_items'] = $this->buildGroupedItems($visibleItems);
                $section['filter_counts'] = [
                    'all' => $section['visible_item_count'],
                    'need_approve' => (int) $visibleItems->filter(fn (array $item): bool => (int) ($item['need_approve_count'] ?? 0) > 0)->count(),
                    'need_publish' => (int) $visibleItems->filter(fn (array $item): bool => (int) ($item['need_publish_count'] ?? 0) > 0)->count(),
                    'queues' => (int) $visibleItems->filter(fn (array $item): bool => ((int) ($item['need_approve_count'] ?? 0) > 0) || ((int) ($item['need_publish_count'] ?? 0) > 0))->count(),
                    'published_heavy' => (int) $visibleItems->filter(fn (array $item): bool => (int) ($item['published_count'] ?? 0) > 0)->count(),
                    'empty_new' => (int) $visibleItems->filter(fn (array $item): bool => ((int) ($item['total_count'] ?? 0) === 0) && !empty($item['can_create']))->count(),
                ];
                $section['pinned_items'] = $visibleItems
                    ->filter(fn (array $item): bool => !empty($item['pinned']))
                    ->sortBy('order')
                    ->take(3)
                    ->values()
                    ->all();
                if (empty($section['pinned_items'])) {
                    $section['pinned_items'] = $visibleItems->sortBy('order')->take(min(3, $visibleItems->count()))->values()->all();
                }
                $section['focus_panels'] = $this->buildFocusPanels($visibleItems, $section['pinned_items']);
                $section['index_url'] = route($section['route']);

                return $section;
            })
            ->filter(function (array $section): bool {
                return $section['visible_item_count'] > 0;
            })
            ->values();
    }

    private function buildSummary(Collection $sections): array
    {
        return [
            'section_count' => $sections->count(),
            'report_count' => (int) $sections->sum('visible_item_count'),
            'creatable_count' => (int) $sections->sum('creatable_item_count'),
            'pending_approve_count' => (int) $sections->sum('pending_approve_count'),
            'pending_publish_count' => (int) $sections->sum('pending_publish_count'),
        ];
    }

    private function buildAllOverviewSpotlights(Collection $sections): array
    {
        $approvalLead = $sections
            ->flatMap(function (array $section) {
                return collect($section['items'] ?? [])->map(function (array $item) use ($section) {
                    $item['section_title'] = $section['title'] ?? 'Inspection';
                    $item['section_key'] = $section['key'] ?? null;

                    return $item;
                });
            })
            ->filter(fn (array $item): bool => (int) ($item['need_approve_count'] ?? 0) > 0)
            ->sort(function (array $left, array $right): int {
                return [(int) ($right['need_approve_count'] ?? 0), (int) ($left['order'] ?? 0)]
                    <=> [(int) ($left['need_approve_count'] ?? 0), (int) ($right['order'] ?? 0)];
            })
            ->first();

        $publishLead = $sections
            ->flatMap(function (array $section) {
                return collect($section['items'] ?? [])->map(function (array $item) use ($section) {
                    $item['section_title'] = $section['title'] ?? 'Inspection';
                    $item['section_key'] = $section['key'] ?? null;

                    return $item;
                });
            })
            ->filter(fn (array $item): bool => (int) ($item['need_publish_count'] ?? 0) > 0)
            ->sort(function (array $left, array $right): int {
                return [(int) ($right['need_publish_count'] ?? 0), (int) ($left['order'] ?? 0)]
                    <=> [(int) ($left['need_publish_count'] ?? 0), (int) ($right['order'] ?? 0)];
            })
            ->first();

        $mostActiveSection = $sections
            ->sort(function (array $left, array $right): int {
                return [(int) ($right['total_report_count'] ?? 0), (int) ($left['visible_item_count'] ?? 0)]
                    <=> [(int) ($left['total_report_count'] ?? 0), (int) ($right['visible_item_count'] ?? 0)];
            })
            ->first();

        return array_values(array_filter([
            $this->makeMetricSpotlight(
                'Need Approve',
                (int) $sections->sum('pending_approve_count'),
                $approvalLead['display_title'] ?? 'No pending approvals',
                $approvalLead
                    ? 'Largest approval queue currently sits in ' . ($approvalLead['section_title'] ?? 'Inspection') . '.'
                    : 'All visible report types are currently approved.',
                $this->buildPresetUrl($approvalLead, 'need_approve'),
                $approvalLead ? 'Open Queue' : 'Queue Clear'
            ),
            $this->makeMetricSpotlight(
                'Need Publish',
                (int) $sections->sum('pending_publish_count'),
                $publishLead['display_title'] ?? 'No pending publish',
                $publishLead
                    ? 'The highest publish backlog is in ' . ($publishLead['section_title'] ?? 'Inspection') . '.'
                    : 'No cloned reports are waiting for publish.',
                $this->buildPresetUrl($publishLead, 'need_publish'),
                $publishLead ? 'Open Queue' : 'Queue Clear'
            ),
            $this->makeMetricSpotlight(
                'Most Active Section',
                (int) ($mostActiveSection['total_report_count'] ?? 0),
                $mostActiveSection['title'] ?? 'No activity yet',
                $mostActiveSection
                    ? 'Jump to the busiest section and continue from its report groups.'
                    : 'No inspection report activity found yet.',
                $this->buildOverviewTabUrl($mostActiveSection),
                $mostActiveSection ? 'Open Section' : 'No Section'
            ),
        ]));
    }

    private function buildSpotlights(array $section): array
    {
        $items = collect($section['items'] ?? []);
        $viewableItems = $items->where('can_view', true)->values();
        $needApproveLead = $viewableItems
            ->filter(fn (array $item): bool => (int) ($item['need_approve_count'] ?? 0) > 0)
            ->sort(function (array $left, array $right): int {
                return [(int) ($right['need_approve_count'] ?? 0), (int) ($left['order'] ?? 0)]
                    <=> [(int) ($left['need_approve_count'] ?? 0), (int) ($right['order'] ?? 0)];
            })
            ->first();
        $needPublishLead = $viewableItems
            ->filter(fn (array $item): bool => (int) ($item['need_publish_count'] ?? 0) > 0)
            ->sort(function (array $left, array $right): int {
                return [(int) ($right['need_publish_count'] ?? 0), (int) ($left['order'] ?? 0)]
                    <=> [(int) ($left['need_publish_count'] ?? 0), (int) ($right['order'] ?? 0)];
            })
            ->first();
        $mostActive = $viewableItems
            ->sort(function (array $left, array $right): int {
                return [(int) ($right['total_count'] ?? 0), (int) ($left['order'] ?? 0)]
                    <=> [(int) ($left['total_count'] ?? 0), (int) ($right['order'] ?? 0)];
            })
            ->first();

        return array_values(array_filter([
            $this->makeMetricSpotlight(
                'Need Approve',
                (int) ($section['pending_approve_count'] ?? 0),
                $needApproveLead['display_title'] ?? 'No pending approvals',
                $needApproveLead
                    ? 'Largest approval queue in this section.'
                    : 'All visible report types are currently approved.',
                $this->buildPresetUrl($needApproveLead, 'need_approve'),
                $needApproveLead ? 'Open Queue' : 'Queue Clear'
            ),
            $this->makeMetricSpotlight(
                'Need Publish',
                (int) ($section['pending_publish_count'] ?? 0),
                $needPublishLead['display_title'] ?? 'No pending publish',
                $needPublishLead
                    ? 'Cloned duplicated reports are still waiting for publish.'
                    : 'No cloned reports are waiting for publish.',
                $this->buildPresetUrl($needPublishLead, 'need_publish'),
                $needPublishLead ? 'Open Queue' : 'Queue Clear'
            ),
            $this->makeMetricSpotlight(
                'Most Active Type',
                (int) ($mostActive['total_count'] ?? 0),
                $mostActive['display_title'] ?? 'No report activity',
                $mostActive
                    ? 'Highest report volume in this section.'
                    : 'No report activity found yet for this section.',
                $mostActive['index_url'] ?? null,
                $mostActive ? 'Open Listing' : 'No Listing'
            ),
        ]));
    }

    private function makeMetricSpotlight(string $label, int $value, string $title, string $description, ?string $url, string $actionLabel): array
    {
        return [
            'label' => $label,
            'value' => $value,
            'description' => $description,
            'title' => $title,
            'url' => $url,
            'action_label' => $actionLabel,
        ];
    }

    private function buildPresetUrl(?array $item, string $preset): ?string
    {
        if (!$item || empty($item['index_url'])) {
            return null;
        }

        return $item['index_url'] . '?smart_preset=' . urlencode($preset);
    }

    private function buildOverviewTabUrl(?array $section): ?string
    {
        if (!$section || empty($section['key'])) {
            return null;
        }

        return route('inspection.catalog.overview') . '#inspection-overview-tab-' . $section['key'];
    }

    private function buildRecentActivity(Collection $sections): array
    {
        $itemIndex = $sections
            ->flatMap(function (array $section) {
                return collect($section['items'] ?? [])->mapWithKeys(function (array $item) use ($section): array {
                    return [
                        $item['policy'] => [[
                            'title' => $item['display_title'] ?? $item['title'],
                            'section_title' => $section['title'] ?? 'Inspection',
                            'section_key' => $section['key'] ?? null,
                            'index_url' => $item['index_url'] ?? null,
                            'icon_class' => $item['icon_class'] ?? 'la la-file-text-o',
                        ]],
                    ];
                });
            })
            ->all();

        if (empty($itemIndex)) {
            return [];
        }

        return InspectionReport::query()
            ->select(['code', 'reportable_type', 'publish', 'user_id_edit', 'created_at', 'updated_at'])
            ->whereIn('reportable_type', array_keys($itemIndex))
            ->orderByRaw('COALESCE(updated_at, created_at) DESC')
            ->limit(6)
            ->get()
            ->map(function (InspectionReport $report) use ($itemIndex): ?array {
                $item = $itemIndex[$report->reportable_type][0] ?? null;
                if (!$item) {
                    return null;
                }

                $activityLabel = 'Created';
                if ((int) ($report->publish ?? 0) === 1) {
                    $activityLabel = 'Published';
                } elseif (!empty($report->user_id_edit)) {
                    $activityLabel = 'Edited';
                }

                return [
                    'code' => $report->code,
                    'title' => $item['title'],
                    'section_title' => $item['section_title'],
                    'section_key' => $item['section_key'],
                    'icon_class' => $item['icon_class'],
                    'activity_label' => $activityLabel,
                    'activity_at' => optional($report->updated_at ?? $report->created_at)->format('d M Y H:i'),
                    'url' => $item['index_url'],
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function buildGroupedItems(Collection $items): array
    {
        $groups = [];

        foreach ($items as $item) {
            $groupKey = trim((string) ($item['group'] ?? 'Other'));
            if (!isset($groups[$groupKey])) {
                $groups[$groupKey] = [
                    'title' => $groupKey,
                    'accent_key' => Str::slug($groupKey),
                    'items' => [],
                ];
            }

            $groups[$groupKey]['items'][] = $item;
        }

        return array_values(array_map(function (array $group): array {
            $group['items'] = collect($group['items'])->sortBy('order')->values()->all();
            $group['item_count'] = count($group['items']);

            return $group;
        }, $groups));
    }

    private function buildFocusPanels(Collection $items, array $pinnedItems): array
    {
        $viewableItems = $items->where('can_view', true)->values();

        $sortByMetric = function (Collection $source, string $metric) {
            return $source
                ->sort(function (array $left, array $right) use ($metric): int {
                    return [(int) ($right[$metric] ?? 0), (int) ($left['order'] ?? 0)]
                        <=> [(int) ($left[$metric] ?? 0), (int) ($right['order'] ?? 0)];
                })
                ->take(3)
                ->values()
                ->all();
        };

        return [
            [
                'key' => 'manual',
                'label' => 'Pinned',
                'description' => 'Curated defaults for this section.',
                'items' => $pinnedItems,
            ],
            [
                'key' => 'need_approve',
                'label' => 'Need Approve',
                'description' => 'Top report types waiting for approval.',
                'items' => $sortByMetric(
                    $viewableItems->filter(fn (array $item): bool => (int) ($item['need_approve_count'] ?? 0) > 0),
                    'need_approve_count'
                ),
            ],
            [
                'key' => 'need_publish',
                'label' => 'Need Publish',
                'description' => 'Top report types waiting for publish.',
                'items' => $sortByMetric(
                    $viewableItems->filter(fn (array $item): bool => (int) ($item['need_publish_count'] ?? 0) > 0),
                    'need_publish_count'
                ),
            ],
            [
                'key' => 'active',
                'label' => 'Most Active',
                'description' => 'Highest report volume in this section.',
                'items' => $sortByMetric($viewableItems, 'total_count'),
            ],
        ];
    }

    private function resolveReportMetricsByType(Collection $definition): array
    {
        $reportableTypes = $definition
            ->flatMap(function (array $section) {
                return collect($section['items'])->pluck('policy');
            })
            ->unique()
            ->values();

        if ($reportableTypes->isEmpty()) {
            return [];
        }

        return InspectionReport::query()
            ->select([
                'reportable_type',
                DB::raw('COUNT(*) as total_count'),
                DB::raw("SUM(CASE WHEN user_id_approved IS NULL AND (publish IS NULL OR publish = 0) AND code NOT LIKE '%Duplicated%' THEN 1 ELSE 0 END) as need_approve_count"),
                DB::raw("SUM(CASE WHEN (publish IS NULL OR publish = 0) AND code LIKE '%Duplicated%' THEN 1 ELSE 0 END) as need_publish_count"),
                DB::raw('SUM(CASE WHEN publish IS NOT NULL AND publish <> 0 THEN 1 ELSE 0 END) as published_count'),
            ])
            ->whereIn('reportable_type', $reportableTypes->all())
            ->groupBy('reportable_type')
            ->get()
            ->mapWithKeys(function (InspectionReport $report): array {
                return [
                    $report->reportable_type => [
                        'total_count' => (int) ($report->total_count ?? 0),
                        'need_approve_count' => (int) ($report->need_approve_count ?? 0),
                        'need_publish_count' => (int) ($report->need_publish_count ?? 0),
                        'published_count' => (int) ($report->published_count ?? 0),
                    ],
                ];
            })
            ->all();
    }

    private function catalogDefinition(): array
    {
        return [
            [
                'key' => 'lifting',
                'title' => 'Lifting',
                'page_name' => 'Lifting Inspection Reports',
                'description' => 'Cranes, forklifts, lifting accessories, and lifting registers.',
                'route' => 'inspection.catalog.lifting',
                'theme' => 'lifting',
                'featured_order' => 1,
                'items' => [
                    $this->item(1, 'Lifting Crane Report', 'App\Models\Inspection\Lifting\Crane', 'crane.index', 'crane.create', ['group' => 'Core Equipment', 'short_title' => 'Crane', 'icon_class' => 'la la-upload', 'pinned' => true]),
                    $this->item(2, 'Lifting Overhead Crane Report', 'App\Models\Inspection\Lifting\OverheadCrane', 'overheadCrane.index', 'overheadCrane.create', ['group' => 'Core Equipment', 'short_title' => 'Overhead Crane', 'icon_class' => 'la la-building-o']),
                    $this->item(3, 'Lifting Forklift Report', 'App\Models\Inspection\Lifting\Forklift', 'forklift.index', 'forklift.create', ['group' => 'Core Equipment', 'short_title' => 'Forklift', 'icon_class' => 'la la-truck', 'pinned' => true]),
                    $this->item(4, 'Lifting Through Examination Report', 'App\Models\Inspection\Lifting\ThroughExamination', 'throughExamination.index', 'throughExamination.create', ['group' => 'Assurance And Follow-Up', 'short_title' => 'Through Examination', 'icon_class' => 'la la-check-square-o']),
                    $this->item(5, 'Lifting Defect Report', 'App\Models\Inspection\Lifting\Defect', 'defect.index', 'defect.create', ['group' => 'Assurance And Follow-Up', 'short_title' => 'Defect', 'icon_class' => 'la la-warning']),
                    $this->item(6, 'Lifting Register Report', 'App\Models\Inspection\Lifting\Lregister', 'lregister.index', 'lregister.create', ['group' => 'Assurance And Follow-Up', 'short_title' => 'Register', 'icon_class' => 'la la-book', 'pinned' => true]),
                ],
            ],
            [
                'key' => 'ndt',
                'title' => 'NDT',
                'page_name' => 'NDT Inspection Reports',
                'description' => 'MPI-PT, visual, ultrasonic, witness hydro, treating iron, and drawing reports.',
                'route' => 'inspection.catalog.ndt',
                'theme' => 'ndt',
                'featured_order' => 2,
                'items' => [
                    $this->item(1, 'NDT MPI-PT Report', 'App\Models\Inspection\Ndt\Mpipt', 'mpipt.index', 'mpipt.create', ['group' => 'Surface And Visual', 'short_title' => 'MPI-PT', 'icon_class' => 'la la-magic', 'pinned' => true]),
                    $this->item(2, 'NDT Visual Report', 'App\Models\Inspection\Ndt\Visual', 'visual.index', 'visual.create', ['group' => 'Surface And Visual', 'short_title' => 'Visual', 'icon_class' => 'la la-eye', 'pinned' => true]),
                    $this->item(3, 'NDT UT Shear Wave Report', 'App\Models\Inspection\Ndt\Ultrasonic', 'ultrasonic.index', 'ultrasonic.create', ['group' => 'Ultrasonic', 'short_title' => 'UT Shear Wave', 'icon_class' => 'la la-signal']),
                    $this->item(4, 'NDT Summary Report', 'App\Models\Inspection\Ndt\Summary', 'summary.index', 'summary.create', ['group' => 'Hydro And Support', 'short_title' => 'Summary', 'icon_class' => 'la la-bar-chart']),
                    $this->item(5, 'NDT Attach Report', 'App\Models\Inspection\Ndt\Attached', 'attached.index', 'attached.create', ['group' => 'Hydro And Support', 'short_title' => 'Attach', 'icon_class' => 'la la-paperclip']),
                    $this->item(6, 'NDT High Pressure UT Report', 'App\Models\Inspection\Ndt\High3Pressure', 'high3Pressure.index', 'high3Pressure.create', ['group' => 'Ultrasonic', 'short_title' => 'High Pressure UT', 'icon_class' => 'la la-tachometer']),
                    $this->item(7, 'NDT UTWT Report', 'App\Models\Inspection\Ndt\HighPressure', 'highPressure.index', 'highPressure.create', ['group' => 'Ultrasonic', 'short_title' => 'UTWT', 'icon_class' => 'la la-area-chart']),
                    $this->item(8, 'NDT General UTWT Report', 'App\Models\Inspection\Ndt\High2Pressure', 'high2Pressure.index', 'high2Pressure.create', ['group' => 'Ultrasonic', 'short_title' => 'General UTWT', 'icon_class' => 'la la-line-chart']),
                    $this->item(9, 'NDT Witness Hydro Test Report', 'App\Models\Inspection\Ndt\WitnessHydro', 'witnessHydro.index', 'witnessHydro.create', ['group' => 'Hydro And Support', 'short_title' => 'Witness Hydro', 'icon_class' => 'la la-tint']),
                    $this->item(10, 'NDT Treating Iron Inspection Report', 'App\Models\Inspection\Ndt\TreatingIron', 'treatingIron.index', 'treatingIron.create', ['group' => 'Hydro And Support', 'short_title' => 'Treating Iron', 'icon_class' => 'la la-wrench']),
                    $this->item(11, 'NDT Drawing Report', 'App\Models\Inspection\Ndt\DrawingInspection', 'drawingInspection.index', 'drawingInspection.create', ['group' => 'Hydro And Support', 'short_title' => 'Drawing', 'icon_class' => 'la la-pencil-square-o']),
                    $this->item(12, 'NDT Register Report', 'App\Models\Inspection\Ndt\Nregister', 'nregister.index', 'nregister.create', ['group' => 'Hydro And Support', 'short_title' => 'Register', 'icon_class' => 'la la-book', 'pinned' => true]),
                ],
            ],
            [
                'key' => 'tubular',
                'title' => 'Tubular',
                'page_name' => 'Tubular Inspection Reports',
                'description' => 'Pipes, collars, stabilizers, reamers, links, and tubing inspection reports.',
                'route' => 'inspection.catalog.tubular',
                'theme' => 'tubular',
                'featured_order' => 3,
                'items' => [
                    $this->item(1, 'Summary of Pipes Inspections Report', 'App\Models\Inspection\Tubular\PipesSummaryReport', 'pipesSummaryReports.index', 'pipesSummaryReports.create', ['group' => 'Overview', 'short_title' => 'Pipes Summary', 'icon_class' => 'la la-dashboard', 'pinned' => true]),
                    $this->item(2, 'Drill Pipe Inspection Report', 'App\Models\Inspection\Tubular\DrillPipe', 'drillPipe.index', 'drillPipe.create', ['group' => 'Pipe Body', 'short_title' => 'Drill Pipe', 'icon_class' => 'la la-minus']),
                    $this->item(3, 'Heavy Weight Drill Pipe Inspection Report', 'App\Models\Inspection\Tubular\HeavyWeightPipe', 'heavyWeightPipe.index', 'heavyWeightPipe.create', ['group' => 'Pipe Body', 'short_title' => 'Heavy Weight Pipe', 'icon_class' => 'la la-ellipsis-h']),
                    $this->item(4, 'Drill Collar Inspection Report', 'App\Models\Inspection\Tubular\DrillCollar', 'drillCollar.index', 'drillCollar.create', ['group' => 'Pipe Body', 'short_title' => 'Drill Collar', 'icon_class' => 'la la-circle-o']),
                    $this->item(5, 'Subs Dimensional Inspection Report', 'App\Models\Inspection\Tubular\SubsDimensional', 'subsDimensional.index', 'subsDimensional.create', ['group' => 'Connections And Tools', 'short_title' => 'Subs Dimensional', 'icon_class' => 'la la-compress']),
                    $this->item(6, 'Tubing String Inspection Sheet', 'App\Models\Inspection\Tubular\TubingString', 'tubingString.index', 'tubingString.create', ['group' => 'Pipe Body', 'short_title' => 'Tubing String', 'icon_class' => 'la la-bars']),
                    $this->item(7, 'Stabilizer Inspection Report', 'App\Models\Inspection\Tubular\StabilizerInspection', 'stabilizerInspection.index', 'stabilizerInspection.create', ['group' => 'Connections And Tools', 'short_title' => 'Stabilizer', 'icon_class' => 'la la-life-ring', 'pinned' => true]),
                    $this->item(8, 'Reamer Inspection Report', 'App\Models\Inspection\Tubular\ReamerInspection', 'reamerInspection.index', 'reamerInspection.create', ['group' => 'Connections And Tools', 'short_title' => 'Reamer', 'icon_class' => 'la la-gear']),
                    $this->item(9, 'Link Inspection Report', 'App\Models\Inspection\Tubular\LinkInspection', 'linkInspection.index', 'linkInspection.create', ['group' => 'Connections And Tools', 'short_title' => 'Link', 'icon_class' => 'la la-link']),
                    $this->item(10, 'PBL Inspection Report', 'App\Models\Inspection\Tubular\Pbl', 'pbl.index', 'pbl.create', ['group' => 'Connections And Tools', 'short_title' => 'PBL', 'icon_class' => 'la la-plug']),
                    $this->item(11, 'Tubing / Casing Inspection Report', 'App\Models\Inspection\Tubular\TubingCasing', 'tubingCasing.index', 'tubingCasing.create', ['group' => 'Pipe Body', 'short_title' => 'Tubing / Casing', 'icon_class' => 'la la-columns']),
                ],
            ],
            [
                'key' => 'drop_object',
                'title' => 'Drop Object',
                'page_name' => 'Drop Object Inspection Reports',
                'description' => 'Drop object survey and related risk inspection reports.',
                'route' => 'inspection.catalog.dropObject',
                'theme' => 'drop-object',
                'featured_order' => 1,
                'items' => [
                    $this->item(1, 'Drop Object Survey', 'App\Models\Inspection\DropObject\DropObject', 'dropObject.index', 'dropObject.create', ['group' => 'Survey', 'short_title' => 'Drop Object Survey', 'icon_class' => 'la la-cube', 'pinned' => true]),
                ],
            ],
            [
                'key' => 'calibration',
                'title' => 'Calibration',
                'page_name' => 'Calibration Inspection Reports',
                'description' => 'Pressure gauge, torque, pressure test, and yoke certificates.',
                'route' => 'inspection.catalog.calibration',
                'theme' => 'calibration',
                'featured_order' => 1,
                'items' => [
                    $this->item(1, 'Calibration Certificate (Pressure Gauge)', 'App\Models\Inspection\Calibration\CalibrationPressureGauge', 'calibrationPressureGauge.index', 'calibrationPressureGauge.create', ['group' => 'Certificates', 'short_title' => 'Pressure Gauge', 'icon_class' => 'la la-dashboard', 'pinned' => true]),
                    $this->item(2, 'Calibration Certificate (Torque)', 'App\Models\Inspection\Calibration\CalibrationTorque', 'calibrationTorque.index', 'calibrationTorque.create', ['group' => 'Certificates', 'short_title' => 'Torque', 'icon_class' => 'la la-rotate-right', 'pinned' => true]),
                    $this->item(3, 'Calibration Certificate (Pressure Test)', 'App\Models\Inspection\Calibration\CalibrationPressureTest', 'calibrationPressureTest.index', 'calibrationPressureTest.create', ['group' => 'Certificates', 'short_title' => 'Pressure Test', 'icon_class' => 'la la-tachometer']),
                    $this->item(4, 'Calibration Certificate (Yoke)', 'App\Models\Inspection\Calibration\CalibrationYoke', 'calibrationYoke.index', 'calibrationYoke.create', ['group' => 'Certificates', 'short_title' => 'Yoke', 'icon_class' => 'la la-magnet']),
                ],
            ],
        ];
    }

    private function item(int $order, string $title, string $policy, string $indexRoute, string $createRoute, array $meta = []): array
    {
        return array_merge([
            'order' => $order,
            'title' => $title,
            'policy' => $policy,
            'index_route' => $indexRoute,
            'create_route' => $createRoute,
        ], $meta);
    }
}

