@extends('layouts.app')

@include('layouts.styles.datatables')

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
						@if ($throughexaminations == 0)
								@include('layouts.repeated.nodata', ['route' => 'throughExamination'])
						@else
								@can('create', 'App\Models\Inspection\Lifting\ThroughExamination')
										<a href="{{route('throughExamination.create')}}" class="btn btn-primary clear"><i class="la la-plus"></i> Create New {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}}</a>
								@endcan
								<div class="card">
										<div class="card-content">
												<div class="card-body">
														<div class="table-responsive">
																<table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
																		<thead>
																				<tr>
																						<th>Report No.</th>
																						<th>ID No.</th>
																						<th>Desc.</th>
																						<th>SWL</th>
																						<th>Client</th>
																					<th>Department</th>
																					<th>Work Location</th>
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

@include('layouts.scripts.datatables', [
	'route' => 'throughExamination',
	'columns' => ['code', 'id_number', 'desc', 'swl', 'client', 'client_department', 'deploc', 'action'],
	'select_fields' => ['action' => ['' => 'ALL', 'upload' => 'Upload PDF', 'download' => 'Download PDF', 'publish' => 'Need to Publish']],
	'datatable_options' => ['filterDebounceMs' => 550, 'searchDelay' => 500]
])
