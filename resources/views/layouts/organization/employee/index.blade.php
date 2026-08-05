@extends('layouts.app')

@include('layouts.styles.datatables')

@section('header-bottom')
    @include('layouts.repeated.listing-datatable-styles')
@endsection

@section('content')
    <section class="users-list-wrapper">
        <div class="users-list">
            @if ($employees == 0)
                @include('layouts.repeated.nodata', ['route' => 'employee'])
            @else
                @can('create', 'App\Models\Organization\Employee')
                    <a href="{{route('employee.create')}}" class="btn btn-primary clear"><i class="la la-plus"></i> Create New {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}}</a>
                @endcan
                <div class="card">
                    <div class="card-content">
                        <div class="card-body listing-table-shell">
                            <div class="listing-table-toolbar">
                                <div class="listing-toolbar-presets">
                                    <span class="text-muted small">Filter and search this list by column.</span>
                                </div>
                                <div class="listing-toolbar-actions">
                                    <button type="button" class="btn btn-sm btn-light border listing-toolbar-clear js-clear-datatable-filters">Clear Filters</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
                                    <thead>
                                        <tr class="column-headings">
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Assistant</th>
                                            <th>Department</th>
                                            <th>Email</th>
                                            <th>Telephone</th>
                                            <th>E-Sign</th>
                                            <th>Desc.</th>
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
    'route' => 'employee',
    'columns' => ['code', 'name', 'is_assistant', 'departments', 'email', 'tel', 'esign', 'desc', 'action'],
    'datatable_options' => [
        'ordering' => true,
        'order' => [[0, 'desc']],
        'useFilterRow' => true,
        'fixedHeader' => ['header' => true, 'headerOffset' => 78],
        'filterDebounceMs' => 250,
    ],
    'non_orderable_columns' => ['is_assistant', 'departments', 'esign', 'action'],
    'non_searchable_columns' => ['esign', 'action'],
    'disable_column_filters' => ['is_assistant', 'esign', 'action'],
])
