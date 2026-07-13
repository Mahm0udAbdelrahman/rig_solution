@extends('layouts.app')

@section('header')
<link rel="stylesheet" href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">
@endsection

@section('header-bottom')
<style>
.staff-grid{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:18px}
.staff-card{background:#fff;border:1px solid #e7ecf8;border-radius:18px;box-shadow:0 12px 30px rgba(22,34,51,.06);overflow:hidden}
.staff-head{padding:18px 20px;border-bottom:1px solid #eef2fb;display:flex;justify-content:space-between;gap:12px;align-items:flex-start;flex-wrap:wrap}
.staff-body{padding:20px}
.staff-title{margin:0;color:#263553;font-size:1.12rem;font-weight:800}
.staff-sub{margin:5px 0 0;color:#7d8ba5;font-size:.85rem}
.staff-hero{grid-column:span 12;background:radial-gradient(circle at top right,rgba(90,70,214,.12),transparent 28%),linear-gradient(135deg,#fff 0%,#f7f9ff 55%,#fbfcff 100%)}
.staff-toolbar,.staff-filters,.staff-pills{display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end}
.staff-toolbar{justify-content:space-between}
.staff-pill{display:inline-flex;align-items:center;justify-content:center;padding:8px 12px;border-radius:999px;border:1px solid #dfe6fb;background:#f8faff;color:#42527a;font-size:.78rem;font-weight:700;text-decoration:none!important}
.staff-pill:hover{background:#eef3ff;color:#31449a}
.staff-pill.active{background:#5a46d6;border-color:#5a46d6;color:#fff;box-shadow:0 10px 18px rgba(90,70,214,.18)}
.staff-kpis{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:12px;margin-top:18px}
.staff-kpi{border:1px solid #e7ecf8;border-radius:14px;background:rgba(255,255,255,.94);padding:15px}
.staff-kpi b{display:block;color:#293860;font-size:1.7rem;line-height:1.1}
.staff-kpi small,.staff-note{color:#8693aa}
.staff-kpi span{display:block;color:#7b89a6;font-size:.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.04em}
.span-12{grid-column:span 12}.span-8{grid-column:span 8}.span-6{grid-column:span 6}.span-4{grid-column:span 4}
.staff-table{margin:0}.staff-table thead th{border-top:0;border-bottom:1px solid #e8eef9;color:#6f7f9d;font-size:.8rem;font-weight:800;text-transform:uppercase;white-space:nowrap}
.staff-table thead th a{color:inherit;text-decoration:none}.staff-table tbody td{vertical-align:middle}
.staff-rank{width:28px;height:28px;border-radius:50%;background:#eef3ff;color:#4b5fc9;display:inline-flex;align-items:center;justify-content:center;font-size:.76rem;font-weight:800}
.staff-highlight{background:#f8faff}
.staff-link{color:#2c3f68;font-weight:700;text-decoration:none!important}.staff-link:hover{color:#4a5fd2}
.staff-link-muted{color:#556785;text-decoration:none!important;font-weight:700}
.staff-link-muted:hover{color:#4a5fd2}
.staff-mini{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin-bottom:14px}
.staff-stat{border:1px solid #ebf0fb;border-radius:12px;background:#fbfcff;padding:12px}
.staff-stat span{display:block;color:#8290a8;font-size:.74rem;font-weight:800}.staff-stat b{display:block;margin-top:5px;color:#2d3c60;font-size:1.05rem}
.staff-chips{display:flex;flex-wrap:wrap;gap:9px}.staff-chip{display:inline-flex;gap:8px;align-items:center;border:1px solid #e8edf8;border-radius:999px;background:#fbfcff;padding:7px 11px;color:#33435f;font-size:.82rem;font-weight:700}
.staff-chip em{min-width:24px;height:24px;border-radius:999px;background:#5868de;color:#fff;display:inline-flex;align-items:center;justify-content:center;padding:0 8px;font-size:.74rem;font-style:normal;font-weight:800}
.staff-score-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin:14px 0}
.staff-score{border:1px solid #e7ecf8;border-radius:14px;background:#f8faff;padding:14px}
.staff-score span{display:block;color:#7d8ba5;font-size:.74rem;font-weight:800;text-transform:uppercase;letter-spacing:.04em}
.staff-score b{display:block;margin-top:6px;color:#2d3c60;font-size:1.2rem;line-height:1.1}
.staff-score small{display:block;margin-top:6px;color:#8a95ab;font-size:.77rem;line-height:1.4}
.staff-score.system{background:linear-gradient(135deg,#f7f6ff 0%,#eef1ff 100%)}
.staff-score.management{background:linear-gradient(135deg,#fffaf1 0%,#fff4dc 100%)}
.staff-score.customer{background:linear-gradient(135deg,#f4fbff 0%,#eaf8ff 100%)}
.staff-bars{display:flex;flex-direction:column;gap:12px}.staff-bar{display:grid;grid-template-columns:minmax(120px,1.4fr) 3fr auto;gap:10px;align-items:center}
.staff-bar-highlight{padding:10px 12px;border:1px solid #dfe5fb;border-radius:14px;background:linear-gradient(135deg,#f8f9ff 0%,#eef2ff 100%);box-shadow:0 10px 20px rgba(90,70,214,.08)}
.staff-bar-highlight .staff-link{color:#3a4fbe}
.staff-track{height:12px;border-radius:999px;background:#eef2fb;overflow:hidden}.staff-fill{height:12px;border-radius:999px;background:linear-gradient(90deg,#5b48d8 0%,#3d8bfd 100%)}
.staff-timeline{display:grid;grid-template-columns:repeat(auto-fit,minmax(72px,1fr));gap:12px}.staff-tile{border:1px solid #edf1fb;border-radius:14px;background:#fbfcff;padding:12px 10px;min-height:180px;display:flex;flex-direction:column;justify-content:flex-end;gap:10px}
.staff-tile-bars{display:flex;justify-content:center;gap:8px;align-items:flex-end;min-height:108px}.staff-vbar{width:18px;border-radius:10px 10px 4px 4px;min-height:4px}.staff-vbar.jcf{background:linear-gradient(180deg,#59c084 0%,#2f9b66 100%)}.staff-vbar.report{background:linear-gradient(180deg,#6f5bff 0%,#4a38cb 100%)}
.staff-tile-label{text-align:center;color:#677795;font-size:.74rem;font-weight:700;line-height:1.35}.staff-tile-meta{display:flex;justify-content:center;gap:8px;flex-wrap:wrap}
.staff-tile-pill{display:inline-flex;gap:4px;align-items:center;border-radius:999px;padding:4px 8px;background:#eef2fb;color:#566785;font-size:.72rem;font-weight:800}
.staff-dot{width:8px;height:8px;border-radius:50%;display:inline-block}.staff-dot.jcf{background:#2f9b66}.staff-dot.report{background:#4a38cb}
.staff-select .select2-container{width:100%!important}.staff-select .select2-selection--single{height:40px!important;border-radius:12px!important;border-color:#dfe6fb!important}.staff-select .select2-selection__rendered{line-height:38px!important;padding-left:12px!important}.staff-select .select2-selection__arrow{height:38px!important}
.staff-count-badge{display:inline-flex;align-items:center;justify-content:center;min-width:28px;height:28px;border-radius:999px;background:#eef2fb;color:#4f61c8;padding:0 10px;font-size:.78rem;font-weight:800}
@media (max-width:1399.98px){.staff-kpis{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media (max-width:1199.98px){.span-8,.span-6,.span-4{grid-column:span 12}}
@media (max-width:767.98px){.staff-kpis,.staff-mini,.staff-score-grid{grid-template-columns:1fr}.staff-bar{grid-template-columns:1fr}}
</style>
@endsection

@section('content')
@php
    $base = ['window'=>$selected_window,'user_id'=>$selected_user_id,'section'=>$selected_section,'tab'=>$selected_tab,'sort_by'=>$selected_sort_by,'sort_direction'=>$selected_sort_direction];
    $sortUrl = function($column) use ($base,$selected_sort_by,$selected_sort_direction){ return route('organization.staffPerformance', array_merge($base,['sort_by'=>$column,'sort_direction'=>($selected_sort_by===$column && $selected_sort_direction==='asc') ? 'desc' : 'asc'])); };
    $focusUrl = function($id) use ($selected_window,$selected_section,$selected_tab,$selected_sort_by,$selected_sort_direction){ return route('organization.staffPerformance',['window'=>$selected_window,'user_id'=>$id,'section'=>$selected_section,'tab'=>$selected_tab,'sort_by'=>$selected_sort_by,'sort_direction'=>$selected_sort_direction]); };
    $overviewUrl = route('organization.staffPerformance', array_merge($base,['tab'=>'overview']));
    $chartsUrl = route('organization.staffPerformance', array_merge($base,['tab'=>'charts']));
    $resetUrl = route('organization.staffPerformance',['window'=>$selected_window,'tab'=>$selected_tab]);
    $employeeChartMax = max(1, (int) collect($employee_chart_rows)->max('total_activity'));
    $sectionChartMax = max(1, (int) collect($section_rows)->max('count'));
    $typeChartMax = max(1, (int) collect($type_rows)->max('count'));
    $subjectChartMax = max(1, (int) collect($jcf_subject_rows)->max('count'));
    $timelineMax = max(1, (int) collect($timeline_rows)->map(fn($row)=>max((int)$row['jcf_count'], (int)$row['report_count']))->max());
@endphp
<section class="users-list-wrapper">
    <div class="users-list">
        <div class="staff-grid">
            <div class="staff-card staff-hero">
                <div class="staff-head">
                    <div>
                        <h4 class="staff-title">Staff Performance</h4>
                        <p class="staff-sub">
                            {{ $selected_user_id > 0
                                ? 'Focused performance analytics for the selected employee across JCF creation and inspection output.'
                                : 'Employee analytics across JCF creation and inspection report production, with drill-down and chart views.' }}
                        </p>
                    </div>
                    <span class="staff-pill">Window: {{ $window_options[$selected_window]['label'] ?? '30 Days' }}</span>
                </div>
                <div class="staff-body">
                    <div class="staff-toolbar">
                        <div class="staff-pills">
                            @foreach($window_options as $windowKey => $windowMeta)
                                <a href="{{ route('organization.staffPerformance', array_merge($base,['window'=>$windowKey])) }}" class="staff-pill {{ $selected_window === $windowKey ? 'active' : '' }}">{{ $windowMeta['label'] }}</a>
                            @endforeach
                        </div>
                        <form method="GET" class="staff-filters">
                            <input type="hidden" name="window" value="{{ $selected_window }}">
                            <input type="hidden" name="tab" value="{{ $selected_tab }}">
                            <input type="hidden" name="sort_by" value="{{ $selected_sort_by }}">
                            <input type="hidden" name="sort_direction" value="{{ $selected_sort_direction }}">
                            <div class="staff-select">
                                <label class="staff-note d-block mb-25">Employee</label>
                                <select id="staff-performance-user-id" class="form-control searchable-select" name="user_id" data-placeholder="Search employee...">
                                    <option value="0">All Employees</option>
                                    @foreach($creators as $creator)
                                        <option value="{{ $creator['id'] }}" @selected($selected_user_id === (int) $creator['id'])>{{ $creator['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="staff-select">
                                <label class="staff-note d-block mb-25">Inspection Section</label>
                                <select id="staff-performance-section" class="form-control searchable-select" name="section" data-placeholder="Search section...">
                                    <option value="all">All Sections</option>
                                    @foreach($section_options as $option)
                                        <option value="{{ $option['key'] }}" @selected($selected_section === $option['key'])>{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="staff-pill active border-0">Apply Filters</button>
                            <a href="{{ $resetUrl }}" class="staff-pill">Reset</a>
                        </form>
                    </div>

                    <div class="staff-kpis">
                        @if($selected_user_id > 0 && !empty($focused_employee))
                            <div class="staff-kpi"><span>Employee JCFs</span><b>{{ $focused_employee['jcf_count'] ?? 0 }}</b><small>{{ $focused_employee_insights['jcf_share'] ?? 0 }}% of scoped JCF activity.</small></div>
                            <div class="staff-kpi"><span>Employee Reports</span><b>{{ $focused_employee['report_count'] ?? 0 }}</b><small>{{ $focused_employee_insights['report_share'] ?? 0 }}% of scoped report activity.</small></div>
                            <div class="staff-kpi"><span>Total Contribution</span><b>{{ $focused_employee['total_activity'] ?? 0 }}</b><small>{{ $focused_employee_insights['activity_share'] ?? 0 }}% of total scoped activity.</small></div>
                            <div class="staff-kpi"><span>Sections Covered</span><b>{{ $focused_employee['sections_count'] ?? 0 }}</b><small>Distinct inspection sections handled.</small></div>
                            <div class="staff-kpi"><span>Reports Per JCF</span><b>{{ $focused_employee_insights['reports_per_jcf'] ?? 0 }}</b><small>Inspection volume against assigned JCF workload.</small></div>
                            <div class="staff-kpi"><span>Latest Activity</span><b>{{ $focused_employee_insights['last_activity_label'] ?? '-' }}</b><small>{{ $focused_employee['top_section'] ?? '-' }} / {{ $focused_employee['top_type'] ?? '-' }}</small></div>
                        @else
                            <div class="staff-kpi"><span>JCFs in Window</span><b>{{ $jcf_count }}</b><small>Forms created in selected period.</small></div>
                            <div class="staff-kpi"><span>Inspection Reports</span><b>{{ $report_count }}</b><small>Non-duplicated reports in scope.</small></div>
                            <div class="staff-kpi"><span>Active JCF Employees</span><b>{{ $active_jcf_employees }}</b><small>Employees with at least one JCF.</small></div>
                            <div class="staff-kpi"><span>Active Inspection Employees</span><b>{{ $active_inspection_employees }}</b><small>Employees with at least one report.</small></div>
                            <div class="staff-kpi"><span>Top Employee</span><b>{{ $top_employee['name'] ?? '-' }}</b><small>{{ $top_employee['total_activity'] ?? 0 }} total activity.</small></div>
                            <div class="staff-kpi"><span>Top Section</span><b>{{ $top_section['label'] ?? '-' }}</b><small>{{ $top_section['count'] ?? 0 }} report(s).</small></div>
                        @endif
                    </div>

                        <div class="staff-toolbar mt-1">
                            <div class="staff-pills">
                                <a href="{{ $overviewUrl }}" class="staff-pill {{ $selected_tab === 'overview' ? 'active' : '' }}">Overview</a>
                                <a href="{{ $chartsUrl }}" class="staff-pill {{ $selected_tab === 'charts' ? 'active' : '' }}">Charts</a>
                            </div>
                            <span class="staff-note">{{ $selected_window === 'overall' ? 'Overall mode is active. Sort the employee table and click any employee name to drill down.' : 'Sort the employee table and click any employee name to drill down.' }}</span>
                        </div>
                    </div>
                </div>

            @if($selected_tab === 'overview')
                <div class="staff-card span-8">
                    <div class="staff-head">
                        <div>
                            <h4 class="staff-title">Top Employees</h4>
                            <p class="staff-sub">Searchable filters, sortable metrics, and employee drill-down into JCFs and reports.</p>
                        </div>
                        <span class="staff-note">Focused employee stays visible even after sorting.</span>
                    </div>
                    <div class="staff-body">
                        <div class="table-responsive">
                            <table class="table table-striped staff-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><a href="{{ $sortUrl('name') }}">Employee</a></th>
                                        <th><a href="{{ $sortUrl('jcf_count') }}">JCFs</a></th>
                                        <th><a href="{{ $sortUrl('report_count') }}">Reports</a></th>
                                        <th><a href="{{ $sortUrl('total_activity') }}">Total</a></th>
                                        <th><a href="{{ $sortUrl('top_section') }}">Top Section</a></th>
                                        <th><a href="{{ $sortUrl('top_type') }}">Top Type</a></th>
                                        <th><a href="{{ $sortUrl('last_activity_at') }}">Latest</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee_rows as $index => $row)
                                        <tr class="{{ ($focused_employee['user_id'] ?? 0) === $row['user_id'] ? 'staff-highlight' : '' }}">
                                            <td><span class="staff-rank">{{ $index + 1 }}</span></td>
                                            <td><a href="{{ $focusUrl($row['user_id']) }}" class="staff-link">{{ $row['name'] }}</a></td>
                                            <td>{{ $row['jcf_count'] }}</td>
                                            <td>{{ $row['report_count'] }}</td>
                                            <td><strong>{{ $row['total_activity'] }}</strong></td>
                                            <td>{{ $row['top_section'] }}</td>
                                            <td>{{ $row['top_type'] }}</td>
                                            <td>{{ optional($row['last_activity_at'])->diffForHumans() ?: '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center text-muted">No staff activity in selected window.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="staff-card span-4">
                    <div class="staff-head">
                        <div>
                            <h4 class="staff-title">Focused Employee</h4>
                            <p class="staff-sub">{{ $selected_user_id > 0 ? 'Focused performance analysis for the selected employee.' : 'Click any employee to open a focused performance review.' }}</p>
                        </div>
                        @if($selected_user_id > 0)
                            <a href="{{ route('organization.staffPerformance',['window'=>$selected_window,'section'=>$selected_section,'tab'=>$selected_tab,'sort_by'=>$selected_sort_by,'sort_direction'=>$selected_sort_direction]) }}" class="staff-pill">Clear Focus</a>
                        @endif
                    </div>
                    <div class="staff-body">
                        <h3 class="mb-25">{{ $focused_employee['name'] ?? 'No employee selected' }}</h3>
                        <div class="staff-note mb-1">{{ $selected_user_id > 0 ? 'Employee drill-down active. Cards and portfolios now describe this employee directly.' : 'Top employee in current scope.' }}</div>
                        <div class="staff-mini">
                            <div class="staff-stat"><span>JCFs</span><b>{{ $focused_employee['jcf_count'] ?? 0 }}</b></div>
                            <div class="staff-stat"><span>Reports</span><b>{{ $focused_employee['report_count'] ?? 0 }}</b></div>
                            <div class="staff-stat"><span>Top Section</span><b>{{ $focused_employee['top_section'] ?? '-' }}</b></div>
                            <div class="staff-stat"><span>Reports / JCF</span><b>{{ $focused_employee_insights['reports_per_jcf'] ?? 0 }}</b></div>
                        </div>
                        @if($selected_user_id > 0)
                            <div class="staff-note mb-50">Evaluation Scores</div>
                            <div class="staff-score-grid">
                                <div class="staff-score management">
                                    <span>Management</span>
                                    <b>{{ $focused_evaluation_scores['management'] ?? 'Pending' }}</b>
                                    <small>Administrative rating has not been entered yet.</small>
                                </div>
                                <div class="staff-score customer">
                                    <span>Customer</span>
                                    <b>{{ $focused_evaluation_scores['customer'] ?? 'Pending' }}</b>
                                    <small>Customer satisfaction score is not linked yet.</small>
                                </div>
                                <div class="staff-score system">
                                    <span>System</span>
                                    <b>{{ $focused_evaluation_scores['system'] ?? 0 }}/100</b>
                                    <small>{{ $focused_evaluation_scores['system_label'] ?? 'No calculated score yet.' }}</small>
                                </div>
                            </div>
                            <div class="staff-mini">
                                <div class="staff-stat"><span>JCF Share</span><b>{{ $focused_employee_insights['jcf_share'] ?? 0 }}%</b></div>
                                <div class="staff-stat"><span>Report Share</span><b>{{ $focused_employee_insights['report_share'] ?? 0 }}%</b></div>
                                <div class="staff-stat"><span>Activity Share</span><b>{{ $focused_employee_insights['activity_share'] ?? 0 }}%</b></div>
                                <div class="staff-stat"><span>Latest Activity</span><b>{{ $focused_employee_insights['last_activity_label'] ?? '-' }}</b></div>
                            </div>
                        @else
                            <div class="staff-mini">
                                <div class="staff-stat"><span>Top Type</span><b>{{ $focused_employee['top_type'] ?? '-' }}</b></div>
                                <div class="staff-stat"><span>Latest Activity</span><b>{{ optional($focused_employee['last_activity_at'] ?? null)->diffForHumans() ?: '-' }}</b></div>
                            </div>
                        @endif
                        <div class="mb-1">
                            <div class="staff-note mb-50">Section Distribution</div>
                            <div class="staff-chips">
                                @forelse($focused_section_rows as $row)
                                    <span class="staff-chip">{{ $row['label'] }} <em>{{ $row['count'] }}</em></span>
                                @empty
                                    <span class="staff-note">No section breakdown for current focus.</span>
                                @endforelse
                            </div>
                        </div>
                        <div>
                            <div class="staff-note mb-50">Top Types</div>
                            <div class="staff-chips">
                                @forelse($focused_type_rows as $row)
                                    <span class="staff-chip">{{ $row['type'] }} <em>{{ $row['count'] }}</em></span>
                                @empty
                                    <span class="staff-note">No type breakdown for current focus.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="staff-card span-6">
                    <div class="staff-head">
                        <div>
                            <h4 class="staff-title">{{ $selected_user_id > 0 ? 'Employee JCF Portfolio' : 'Recent JCF Activity' }}</h4>
                            <p class="staff-sub">{{ $selected_user_id > 0 ? 'All JCFs in current scope for the focused employee.' : 'Latest JCFs in current scope.' }}</p>
                        </div>
                        @if($selected_user_id > 0)
                            <span class="staff-count-badge">{{ count($focused_jcf_rows) }}</span>
                        @endif
                    </div>
                    <div class="staff-body">
                        <div class="table-responsive">
                            <table class="table table-striped staff-table">
                                <thead>
                                    <tr>
                                        <th>JCF</th>
                                        <th>Subject</th>
                                        @if($selected_user_id === 0)
                                            <th>Employee</th>
                                        @endif
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($jcfRowsToRender = $selected_user_id > 0 ? $focused_jcf_rows : $recent_jcfs)
                                    @forelse($jcfRowsToRender as $row)
                                        <tr>
                                            <td><a href="{{ $row['overview_url'] }}" class="staff-link"><strong>{{ $row['code'] }}</strong></a></td>
                                            <td title="{{ $row['subject'] }}">{{ \Illuminate\Support\Str::limit($row['subject'], 52, '..') }}</td>
                                            @if($selected_user_id === 0)
                                                <td>{{ $row['employee_name'] }}</td>
                                            @endif
                                            <td>{{ optional($row['created_at'])->diffForHumans() ?: '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $selected_user_id === 0 ? 4 : 3 }}" class="text-center text-muted">
                                                {{ $selected_user_id > 0 ? 'No JCFs for the focused employee in selected scope.' : 'No JCF activity in selected scope.' }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="staff-card span-6">
                    <div class="staff-head">
                        <div>
                            <h4 class="staff-title">{{ $selected_user_id > 0 ? 'Employee Reports Portfolio' : 'Recent Inspection Reports' }}</h4>
                            <p class="staff-sub">{{ $selected_user_id > 0 ? 'All inspection reports in current scope for the focused employee.' : 'Latest inspection reports in current scope.' }}</p>
                        </div>
                        @if($selected_user_id > 0)
                            <span class="staff-count-badge">{{ count($focused_report_rows) }}</span>
                        @endif
                    </div>
                    <div class="staff-body">
                        <div class="table-responsive">
                            <table class="table table-striped staff-table">
                                <thead>
                                    <tr>
                                        <th>Report</th>
                                        <th>Type</th>
                                        @if($selected_user_id > 0)
                                            <th>Section</th>
                                        @else
                                            <th>Employee</th>
                                        @endif
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($reportRowsToRender = $selected_user_id > 0 ? $focused_report_rows : $recent_reports)
                                    @forelse($reportRowsToRender as $row)
                                        <tr>
                                            <td>
                                                @if(!empty($row['show_url']))
                                                    <a href="{{ $row['show_url'] }}" class="staff-link"><strong>{{ $row['report_no'] }}</strong></a>
                                                @else
                                                    <strong>{{ $row['report_no'] }}</strong>
                                                @endif
                                            </td>
                                            <td>{{ $row['type'] }}</td>
                                            @if($selected_user_id > 0)
                                                <td>{{ $row['section'] }}</td>
                                            @else
                                                <td>{{ $row['employee_name'] }}</td>
                                            @endif
                                            <td>{{ optional($row['created_at'])->diffForHumans() ?: '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                {{ $selected_user_id > 0 ? 'No inspection reports for the focused employee in selected scope.' : 'No report activity in selected scope.' }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="staff-card span-4"><div class="staff-head"><div><h4 class="staff-title">Inspection Sections</h4><p class="staff-sub">Distribution by section.</p></div></div><div class="staff-body"><div class="staff-chips">@forelse($section_rows as $row)<span class="staff-chip">{{ $row['label'] }} <em>{{ $row['count'] }}</em></span>@empty<span class="staff-note">No section breakdown available.</span>@endforelse</div></div></div>
                <div class="staff-card span-4"><div class="staff-head"><div><h4 class="staff-title">Top Inspection Types</h4><p class="staff-sub">Most produced report types.</p></div></div><div class="staff-body"><div class="staff-chips">@forelse($type_rows as $row)<span class="staff-chip">{{ $row['type'] }} <em>{{ $row['count'] }}</em></span>@empty<span class="staff-note">No type breakdown available.</span>@endforelse</div></div></div>
                <div class="staff-card span-4"><div class="staff-head"><div><h4 class="staff-title">Top JCF Subjects</h4><p class="staff-sub">Most common work scopes.</p></div></div><div class="staff-body"><div class="staff-chips">@forelse($jcf_subject_rows as $row)<span class="staff-chip">{{ \Illuminate\Support\Str::limit($row['subject'], 38, '..') }} <em>{{ $row['count'] }}</em></span>@empty<span class="staff-note">No JCF subject breakdown available.</span>@endforelse</div></div></div>
            @else
                <div class="staff-card span-12">
                    <div class="staff-head"><div><h4 class="staff-title">Activity Timeline</h4><p class="staff-sub">JCF creation versus inspection production across the selected window.</p></div></div>
                    <div class="staff-body">
                        <div class="staff-timeline">
                            @forelse($timeline_rows as $row)
                                <div class="staff-tile">
                                    <div class="staff-tile-bars">
                                        <div class="staff-vbar jcf" style="height: {{ max(4, (int) round(($row['jcf_count'] / $timelineMax) * 100)) }}px;"></div>
                                        <div class="staff-vbar report" style="height: {{ max(4, (int) round(($row['report_count'] / $timelineMax) * 100)) }}px;"></div>
                                    </div>
                                    <div class="staff-tile-label">{{ $row['label'] }}</div>
                                    <div class="staff-tile-meta">
                                        <span class="staff-tile-pill"><span class="staff-dot jcf"></span>{{ $row['jcf_count'] }}</span>
                                        <span class="staff-tile-pill"><span class="staff-dot report"></span>{{ $row['report_count'] }}</span>
                                    </div>
                                </div>
                            @empty
                                <span class="staff-note">No timeline activity in current scope.</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="staff-card span-6"><div class="staff-head"><div><h4 class="staff-title">Top Employees Chart</h4><p class="staff-sub">Highest combined JCF and report activity.</p></div></div><div class="staff-body"><div class="staff-bars">@forelse($employee_chart_rows as $row)<div class="staff-bar {{ $selected_user_id > 0 && $selected_user_id === (int) $row['user_id'] ? 'staff-bar-highlight' : '' }}"><a href="{{ $focusUrl($row['user_id']) }}" class="staff-link">{{ \Illuminate\Support\Str::limit($row['name'],22,'..') }}</a><div class="staff-track"><div class="staff-fill" style="width: {{ max(6, (int) round(($row['total_activity'] / $employeeChartMax) * 100)) }}%;"></div></div><strong>{{ $row['total_activity'] }}</strong></div>@empty<span class="staff-note">No employee activity in current scope.</span>@endforelse</div></div></div>
                <div class="staff-card span-6"><div class="staff-head"><div><h4 class="staff-title">Inspection Sections Chart</h4><p class="staff-sub">Report volume by section.</p></div></div><div class="staff-body"><div class="staff-bars">@forelse($section_rows as $row)<div class="staff-bar"><span>{{ $row['label'] }}</span><div class="staff-track"><div class="staff-fill" style="width: {{ max(6, (int) round(($row['count'] / $sectionChartMax) * 100)) }}%;"></div></div><strong>{{ $row['count'] }}</strong></div>@empty<span class="staff-note">No section breakdown available.</span>@endforelse</div></div></div>
                <div class="staff-card span-6"><div class="staff-head"><div><h4 class="staff-title">Top Inspection Types Chart</h4><p class="staff-sub">Most produced report types.</p></div></div><div class="staff-body"><div class="staff-bars">@forelse($type_rows as $row)<div class="staff-bar"><span>{{ \Illuminate\Support\Str::limit($row['type'],24,'..') }}</span><div class="staff-track"><div class="staff-fill" style="width: {{ max(6, (int) round(($row['count'] / $typeChartMax) * 100)) }}%;"></div></div><strong>{{ $row['count'] }}</strong></div>@empty<span class="staff-note">No type breakdown available.</span>@endforelse</div></div></div>
                <div class="staff-card span-6"><div class="staff-head"><div><h4 class="staff-title">Top JCF Subjects Chart</h4><p class="staff-sub">Most common work scopes.</p></div></div><div class="staff-body"><div class="staff-bars">@forelse($jcf_subject_rows as $row)<div class="staff-bar"><span>{{ \Illuminate\Support\Str::limit($row['subject'],28,'..') }}</span><div class="staff-track"><div class="staff-fill" style="width: {{ max(6, (int) round(($row['count'] / $subjectChartMax) * 100)) }}%;"></div></div><strong>{{ $row['count'] }}</strong></div>@empty<span class="staff-note">No JCF subject breakdown available.</span>@endforelse</div></div></div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('footer')
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script>
$(function () {
    if (typeof $.fn.select2 !== 'function') return;
    $('#staff-performance-user-id, #staff-performance-section').each(function () {
        const $select = $(this);
        $select.select2({
            width: '100%',
            minimumResultsForSearch: 0,
            dropdownAutoWidth: true,
            placeholder: $select.data('placeholder') || '',
        });
    });
});
</script>
@endsection
