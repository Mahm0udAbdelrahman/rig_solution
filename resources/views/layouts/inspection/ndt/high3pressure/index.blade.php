@extends('layouts.app')

@include('layouts.styles.datatables')

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
						@if ($high3_pressures == 0)
								@include('layouts.repeated.nodata', ['route' => 'high3Pressure'])
						@else
								@can('create', 'App\Models\Inspection\Ndt\High3Pressure')
										<a href="{{route('high3Pressure.create')}}" class="btn btn-primary clear"><i class="la la-plus"></i> Create New {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}}</a>
								@endcan
								<div class="card">
										<div class="card-content">
												<div class="card-body">
														<div class="table-responsive">
																<table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
																		<thead>
																				<tr>
																					<th>Code</th>
																					<th width="15%">ID No.</th>
																					<th width="7%">Department</th>
																					<th width="8%">Work location</th>
																					<th width="15%">Acceptance Criteria</th>
																					<th width="15%">Desc.</th>
																					<th width="15%">Client</th>
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

@include('layouts.scripts.datatables', ['route' => 'high3Pressure', 'columns' => ['code', 'nh2pr_10', 'client_department', 'deploc', 'acceptance', 'desc', 'client', 'action'],
'select_fields' => ['action' => ['' => 'ALL', 'upload' => 'Upload PDF', 'download' => 'Download PDF', 'publish' => 'Need to Publish']]])
