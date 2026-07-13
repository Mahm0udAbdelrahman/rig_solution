@extends('layouts.app')

@include('layouts.styles.datatables')

@section('content')
<style>
	.logo-top{width: 225px; height: 115px; margin: 25px;}
</style>
<section class="users-list-wrapper">
	<div class="users-list">
		@if (!$dataList->count())
		@include('layouts.repeated.nodata', ['route' => 'inspectionLogo'])
		@else

		{{--@can('create', 'App\Models\GeneralInfo\inspectionLogo')--}}
		<a href="{{route('inspectionLogo.create')}}" class="btn btn-primary clear"><i class="la la-plus"></i> Create
			New {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}}</a>
		{{--@endcan--}}
		<div class="card">
			<div class="card-content">
				<div class="card-body">
					<div class="table-responsive">
						<table id="users-list"
							class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
							<thead>
								<tr>
									<th>Name</th>
									<th>Logo</th>
									<th>Related Inspections</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($dataList as $row)
								<tr>
									<td>{{ $row['name'] }}</td>
									<td>
										<img src="{{ asset('storage/' . $row['logo']) }}" class="logo-top"/>
									</td>
									<td>
										<div class="m-1">
											<ul style="/* list-style-type: none; */">
												<li style="list-style-type: none; line-height: 0.5px;"></li>
												@foreach ($row['related_inspections'] as $relatedInspection)
												<li>{{array_slice(explode('\\', $relatedInspection), -1)[0]}}</li>
												@endforeach
											</ul>
										</div>
									</td>
									<td>
										<!-- Edit Button -->
										<a href="{{route('inspectionLogo.edit', $row->id)}}"
											class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>
										
										<!-- Delete Button -->
										<form action="{{ route('inspectionLogo.destroy', $row->id) }}" method="POST" style="display:inline;">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-danger" onclick="return confirmDelete(event, this, '{{ $row->name }}')"><i class="la la-trash"></i></button>
										</form>

										<script>
											function confirmDelete(event, btn, name) {
												event.preventDefault()
												swal({
													title: 'Are you sure?',
													text: "You won't be able to revert this! You are about to delete"+ '"' + name + '" Logo!',
													icon: 'warning',
													showCancelButton: true,
													confirmButtonClass: 'btn btn-danger',
													cancelButtonClass: 'btn btn-dark ml-1',
													confirmButtonColor: '#3085d6',
													cancelButtonColor: '#d33',
													cancelButtonText: 'Cancel',
													confirmButtonText: 'Yes, delete it!'
												}).then((result) => {
													if (result.value) {
														console.log("CONFIRm>>>>>>>>>",btn);
														btn.form.submit()
													}
												})
											}
										</script>
										<!--  -->
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</section>
@endsection
@section('footer')
<script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.fixedHeader.min.js')}}"></script>

<script>
	const table = $('#users-list').DataTable({
		ordering: true,
		// stateSave: true,
		processing: true
	});
</script>
@endSection