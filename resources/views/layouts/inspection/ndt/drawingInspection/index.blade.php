@extends('layouts.app')

@include('layouts.styles.datatables')

@section('content')
    <section class="users-list-wrapper">
        <div class="users-list">
            @if ($reports == 0)
                @include('layouts.repeated.nodata', ['route' => 'drawingInspection'])
            @else
                @can('create', 'App\Models\Inspection\Ndt\DrawingInspection')
                    <a href="{{route('drawingInspection.create')}}" class="btn btn-primary clear"><i class="la la-plus"></i> Create
                        New {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}}</a>
                @endcan
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="users-list"
                                       class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
                                    <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Client</th>
                                        <th>Department</th>
                                        <th>Work Location</th>
                                        <th>Identification</th>
                                        <th>Description</th>
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
@if ($reports != 0)
    @include('layouts.scripts.datatables', ['route' => 'drawingInspection', 'columns' => ['code', 'client', 'client_department','deploc', 'identification_no', 'description', 'action'],
    'select_fields' => ['action' => ['' => 'ALL', 'upload' => 'Upload PDF', 'download' => 'Download PDF', 'publish' => 'Need to Publish']]])
@endif