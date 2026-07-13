@extends('layouts.app')

@include('layouts.styles.datatables')

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
						@if ($lregisters == 0)
								@include('layouts.repeated.nodata', ['route' => 'lregister'])
						@else
								@can('create', 'App\Models\Inspection\Lifting\Lregister')
										<a href="{{route('lregister.create')}}" class="btn btn-primary clear"><i class="la la-plus"></i> Create New {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}}</a>
								@endcan
								<div class="card">
										<div class="card-content">
												<div class="card-body">
														<div class="table-responsive">
																<table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
																		<thead>
																				<tr>
																						<th>Code</th>

				                                    <th width="55%">Client</th>
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

@include('layouts.scripts.datatables', ['route' => 'lregister', 'columns' => ['code', 'client', 'action'],
'select_fields' => ['action' => ['' => 'ALL', 'upload' => 'Upload PDF', 'download' => 'Download PDF', 'publish' => 'Need to Publish']]])
