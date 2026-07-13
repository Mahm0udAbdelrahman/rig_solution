@extends('layouts.app')

@include('layouts.styles.datatables')

@section('header-bottom')
@include('layouts.repeated.listing-datatable-styles')
@endsection

@section('content')
<section class="users-list-wrapper">
    <div class="users-list">
        @php
            $expenseQuickFilters = [
                'all' => 'All',
                'paid' => 'Paid',
                'unpaid' => 'Unpaid',
                'partial' => 'Partial',
                'pending' => 'Pending Payment',
            ];
            $selectedPaymentState = $selected_payment_state ?? 'all';
        @endphp
        @can('create', App\Models\WorkFlow\Expense::class)
            <a href="{{ route('expense.create') }}" class="btn btn-primary clear mb-1"><i class="la la-plus"></i> Create New Expense</a>
        @endcan

        <div class="mb-1 d-flex flex-wrap align-items-center">
            @foreach($expenseQuickFilters as $filterKey => $filterLabel)
                @php
                    $isActiveQuickFilter = $selectedPaymentState === $filterKey;
                    $queryParams = array_filter([
                        'employee_id' => $selected_employee_id ?? null,
                        'entry_status' => $selected_entry_status ?? null,
                        'payment_state' => $filterKey === 'all' ? null : $filterKey,
                    ], function ($value) {
                        return $value !== null && $value !== '';
                    });
                @endphp
                <a
                    href="{{ route('expense.index', $queryParams) }}"
                    class="btn btn-sm mr-50 mb-50 {{ $isActiveQuickFilter ? 'btn-primary' : 'btn-outline-primary' }}"
                >
                    {{ strtoupper($filterLabel) }}
                </a>
            @endforeach
        </div>

        @if ($expenses == 0)
            @include('layouts.repeated.nodata', ['route' => 'expense'])
        @else
            <div class="card">
                <div class="card-content">
                    <div class="card-body listing-table-shell">
                        <div class="listing-table-toolbar is-sticky">
                            <div class="listing-toolbar-actions ml-auto">
                                <button type="button" class="btn btn-sm btn-light border listing-toolbar-clear js-clear-datatable-filters">Clear Filters</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
                                <thead class="workflow-list-head">
                                <tr class="column-headings">
                                    <th>Code</th>
                                    <th>Date</th>
                                    <th>Employee</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Currency</th>
                                    <th>Bank</th>
                                    <th>Status</th>
                                    <th>Payment State</th>
                                    <th>Accountant</th>
                                    <th>By</th>
                                    <th>Actions</th>
                                </tr>
                                <tr class="filter-row">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@include('layouts.scripts.datatables', [
    'route' => 'expense',
    'route_param' => ['employee_id' => $selected_employee_id, 'entry_status' => $selected_entry_status, 'payment_state' => $selected_payment_state],
    'columns' => ['code', 'expense_date', 'expense_employee_name', 'category', 'amount', 'currency', 'bank_account', 'status', 'payment_state', 'accountant_code', 'creator_name', 'action'],
    'datatable_options' => [
        'ordering' => true,
        'order' => [[0, 'desc']],
        'useFilterRow' => true,
        'fixedHeader' => ['header' => true, 'headerOffset' => 78],
        'filterDebounceMs' => 250,
    ],
    'non_orderable_columns' => ['action', 'payment_state'],
    'non_searchable_columns' => ['action'],
    'disable_column_filters' => ['action'],
])
