@extends('layouts.app')

@include('layouts.styles.datatables')

@section('content')
    <section class="users-list-wrapper">
        <div class="users-list">
            @if ($departments == 0)
                @include('layouts.repeated.nodata', ['route' => '$route'])
            @else
                @can('create', $model)
                    <a href="{{route($route.'.create')}}" class="btn btn-primary clear"><i class="la la-plus"></i> Create New {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}}</a>
                @endcan
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Name</th>
                                            @if($route == 'department')
                                                <th>Manager</th>
                                            @endif
                                            <th>Actions</th>
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

@php
    $columns = ['code', 'name', 'action'];
@endphp

@if($route == 'department')
    @php
        array_splice( $columns, 2, 0, 'supervisor' )
    @endphp
@endif

@include('layouts.scripts.datatables', ['route' => $route, 'columns' => $columns])
