@extends('layouts.customer.app')

@section('content')
@php
    $currentSortBy = request('sort_by', 'updated_at');
    $currentSortDirection = request('sort_direction', 'desc');
    $truncateCell = function (?string $value, int $limit = 25) {
        $value = trim((string) $value);
        if ($value === '') {
            return '-';
        }
        return \Illuminate\Support\Str::length($value) > $limit
            ? \Illuminate\Support\Str::limit($value, $limit, '..')
            : $value;
    };
    $sortUrl = function (string $column) use ($currentSortBy, $currentSortDirection) {
        $direction = $currentSortBy === $column && $currentSortDirection === 'asc' ? 'desc' : 'asc';
        return request()->fullUrlWithQuery([
            'sort_by' => $column,
            'sort_direction' => $direction,
            'page' => 1,
        ]);
    };
    $sortIndicator = function (string $column) use ($currentSortBy, $currentSortDirection) {
        if ($currentSortBy !== $column) {
            return '';
        }
        return $currentSortDirection === 'asc' ? ' ↑' : ' ↓';
    };
@endphp
<style>
    .customer-reports-panel {
        border: 1px solid #dfe3eb;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 14px 32px rgba(31, 45, 61, 0.08);
    }
    .customer-reports-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #edf1f7;
        flex-wrap: wrap;
    }
    .customer-reports-title {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #23344d;
    }
    .customer-reports-subtitle {
        color: #7a889d;
        font-size: 0.88rem;
    }
    .customer-reports-body {
        padding: 1rem 1.25rem 1.2rem;
    }
    .customer-reports-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    .customer-reports-sort-hint {
        color: #7a889d;
        font-size: 0.82rem;
    }
    .customer-reports-table td,
    .customer-reports-table th {
        vertical-align: middle;
        white-space: nowrap;
    }
    .customer-truncated-cell {
        max-width: 280px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .customer-reports-pagination .pagination {
        margin-bottom: 0;
        gap: 0.35rem;
        flex-wrap: wrap;
    }
    .customer-reports-pagination nav > div:first-child {
        display: none;
    }
    .customer-reports-pagination .page-item {
        margin: 0;
    }
    .customer-reports-pagination .page-link {
        min-width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.55rem;
        border: 1px solid #d8e1ef;
        color: #41546f;
        background: #fff;
        padding: 0 0.8rem;
        line-height: 1;
        box-shadow: none;
    }
    .customer-reports-pagination .page-item.active .page-link {
        background: #3246d3;
        border-color: #3246d3;
        color: #fff;
        font-weight: 700;
    }
    .customer-reports-pagination .page-item.disabled .page-link {
        background: #f4f7fb;
        border-color: #e4eaf3;
        color: #97a5b8;
    }
    .customer-reports-pagination .page-link:hover {
        background: #f5f8ff;
        color: #22344c;
    }
    .customer-sort-link {
        color: inherit;
        text-decoration: none;
        font-weight: 700;
    }
    .customer-sort-link:hover {
        color: #3246d3;
    }
    .customer-sort-link::after {
        content: ' ↕';
        color: #9aa8bb;
        font-weight: 400;
    }
</style>
<style>
    .customer-column-filters-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-bottom: 0.85rem;
        flex-wrap: wrap;
    }
    .customer-reports-filter-row th {
        padding: 0.55rem 0.5rem 0.8rem;
        background: #fff;
        border-bottom: 1px solid #edf1f7;
        vertical-align: bottom;
    }
    .customer-reports-filter-row label {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        color: #7a889d;
        margin-bottom: 0.35rem;
        white-space: nowrap;
    }
    .customer-reports-filter-row .form-control {
        width: 100%;
        min-width: 0;
    }
</style>

<section class="users-list-wrapper">
    <div class="users-list">
        <div class="customer-reports-panel">
            <div class="customer-reports-head">
                <div>
                    <h4 class="customer-reports-title">Published Inspection Certificates</h4>
                    <div class="customer-reports-subtitle">Only the current page is rendered to keep portal loading fast.</div>
                </div>
                <button type="button" class="customer-portal-pill-btn is-success" onclick="handleDownloadCurrentPage()">
                    Download Current Page
                </button>
            </div>

            <div class="customer-reports-body">
                <div class="customer-reports-controls">
                    <form method="get" action="" class="form-inline" style="gap: .75rem; flex-wrap: wrap;">
                        <input type="hidden" name="report_no" value="{{ request('report_no') }}">
                        <input type="hidden" name="identifier" value="{{ request('identifier') }}">
                        <input type="hidden" name="equipment" value="{{ request('equipment') }}">
                        <input type="hidden" name="exam_date" value="{{ request('exam_date') }}">
                        <input type="hidden" name="purchase_order" value="{{ request('purchase_order') }}">
                        <input type="hidden" name="internal_service_order" value="{{ request('internal_service_order') }}">
                        <input type="hidden" name="work_location" value="{{ request('work_location') }}">
                        <input type="hidden" name="type_filter" value="{{ $selected_type_filter }}">
                        <div class="d-flex align-items-center">
                            <label for="per_page" class="mr-50 text-muted mb-0">Show</label>
                            <select id="per_page" name="per_page" class="form-control mr-50" onchange="this.form.submit()">
                                @foreach([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" {{ (int) request('per_page', 25) === $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                            <span class="text-muted">entries</span>
                        </div>
                        <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Search report no / type / identification / joint / equipment / description / P.O / internal S.O / location / date" style="min-width: 320px;">
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'updated_at') }}">
                        <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">
                        <button type="submit" class="customer-portal-pill-btn is-active">Search</button>
                        @if(request()->filled('q') || request()->filled('type_filter'))
                            <a href="{{ request()->url() }}?per_page={{ request('per_page', 25) }}" class="customer-portal-pill-btn is-outline">Clear</a>
                        @endif
                    </form>
                    <div class="customer-reports-sort-hint">
                        Click column titles to sort.
                    </div>
                </div>

                <form method="get" action="" id="customer-column-filters-form">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 25) }}">
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'updated_at') }}">
                    <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <div class="customer-column-filters-actions">
                        <button type="submit" class="customer-portal-pill-btn is-active">Search</button>
                        <a href="{{ request()->url() }}?per_page={{ request('per_page', 25) }}" class="customer-portal-pill-btn is-outline">Clear</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped customer-reports-table mb-0">
                        <thead>
                            <tr class="customer-reports-filter-row">
                                <th>
                                    <label for="type_filter">Type</label>
                                    <select id="type_filter" name="type_filter" class="form-control">
                                        <option value="">All Types</option>
                                        @foreach($type_options as $typeOption)
                                            <option value="{{ $typeOption['value'] }}" {{ $selected_type_filter === $typeOption['value'] ? 'selected' : '' }}>
                                                {{ $typeOption['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <label for="report_no">Report No.</label>
                                    <input id="report_no" type="text" name="report_no" class="form-control" value="{{ request('report_no') }}" placeholder="Report No.">
                                </th>
                                <th>
                                    <label for="identifier">ID</label>
                                    <input id="identifier" type="text" name="identifier" class="form-control" value="{{ request('identifier') }}" placeholder="ID">
                                </th>
                                <th>
                                    <label for="equipment">Equipment</label>
                                    <input id="equipment" type="text" name="equipment" class="form-control" value="{{ request('equipment') }}" placeholder="Equipment / Equipment No. / Joint No. / Joint Description / ID">
                                </th>
                                <th>
                                    <label for="exam_date">Exam. Date</label>
                                    <input id="exam_date" type="text" name="exam_date" class="form-control" value="{{ request('exam_date') }}" placeholder="Exam. Date">
                                </th>
                                <th>
                                    <label for="purchase_order">P.O</label>
                                    <input id="purchase_order" type="text" name="purchase_order" class="form-control" value="{{ request('purchase_order') }}" placeholder="P.O">
                                </th>
                                <th>
                                    <label for="internal_service_order">Internal S.O</label>
                                    <input id="internal_service_order" type="text" name="internal_service_order" class="form-control" value="{{ request('internal_service_order') }}" placeholder="Internal S.O">
                                </th>
                                <th>
                                    <label for="work_location">Work Location</label>
                                    <input id="work_location" type="text" name="work_location" class="form-control" value="{{ request('work_location') }}" placeholder="Work Location">
                                </th>
                            </tr>
                            <tr>
                                <th><a href="{{ $sortUrl('type') }}" class="customer-sort-link">Type{!! $sortIndicator('type') !!}</a></th>
                                <th><a href="{{ $sortUrl('report_no') }}" class="customer-sort-link">Report No.{!! $sortIndicator('report_no') !!}</a></th>
                                <th>Identification Number</th>
                                <th>Equipment Description</th>
                                <th>Exam. Date</th>
                                <th><a href="{{ $sortUrl('purchase_order') }}" class="customer-sort-link">P.O{!! $sortIndicator('purchase_order') !!}</a></th>
                                <th>Internal S.O</th>
                                <th><a href="{{ $sortUrl('work_location') }}" class="customer-sort-link">Work Location{!! $sortIndicator('work_location') !!}</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report_rows as $row)
                                <tr>
                                    <td>{{ $row['type'] }}</td>
                                    <td>
                                        @if(!empty($row['pdf_url']))
                                            <a class="btn btn-info btn-sm" target="_blank" href="{{ $row['pdf_url'] }}" data-path="{{ $row['pdf_path'] }}">
                                                {{ $row['report_no'] }}
                                            </a>
                                        @else
                                            <span class="btn btn-secondary btn-sm disabled" title="PDF is not available yet">
                                                {{ $row['report_no'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="customer-truncated-cell" title="{{ $row['identifier'] ?: '-' }}">{{ $truncateCell($row['identifier']) }}</td>
                                    <td class="customer-truncated-cell" title="{{ $row['equipment'] ?: '-' }}">{{ $truncateCell($row['equipment'], 45) }}</td>
                                    <td>{{ $row['examination_date'] ?: '-' }}</td>
                                    <td>{{ $row['purchase_order'] ?: '-' }}</td>
                                    <td>{{ $row['internal_service_order'] ?: '-' }}</td>
                                    <td>{{ $row['work_location'] ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No published inspection certificates found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        </table>
                    </div>
                </form>

                @if(method_exists($report_rows, 'links'))
                    <div class="d-flex justify-content-between align-items-center mt-1 flex-wrap">
                        <div class="text-muted small mb-50">
                            Showing {{ $report_rows->firstItem() ?? 0 }} to {{ $report_rows->lastItem() ?? 0 }} of {{ $report_rows->total() }} entries
                        </div>
                        <div class="customer-reports-pagination">
                            {{ $report_rows->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('footer')
<script>
  function handleDownloadCurrentPage() {
    const filePaths = [];
    document.querySelectorAll('a[data-path]').forEach(function (a) {
      filePaths.push(a.getAttribute('data-path').replaceAll('\\', '/'));
    });

    if (!filePaths.length) {
      return;
    }

    const currentGuard = "{{ \Illuminate\Support\Facades\Auth::guard('clientDepartments')->check() ? 'clientDepartments' : 'customer' }}";
    const url = currentGuard === 'clientDepartments' ? "{{ route('department.downloadFiles') }}" : "{{ route('client.downloadFiles') }}";
    const downloadLink = document.createElement('a');
    downloadLink.href = url + '?filePaths=' + JSON.stringify(filePaths);
    document.body.appendChild(downloadLink);
    downloadLink.click();
    downloadLink.remove();
  }

</script>
@endsection
