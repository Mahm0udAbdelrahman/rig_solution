@extends('layouts.customer.app')

@include('layouts.styles.datatables')

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
								<div class="card">
										<div class="card-content">
												<div class="card-body">
														<button type="button" class="btn btn-success mr-1" style="margin-bottom: 15px" onclick="handleDownload()"> Download All</button>
														<div class="table-responsive">
																<table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
																		<thead>
																				<tr>
																						<th>Type</th>
																						<th>Report No.</th>
																						<th>ID</th>
																						<th>Equipment</th>
																						<th>Exam. Date</th>
																						<th>PO</th>
																						<th>Internal SO</th>
																						<th>Work Location</th>
																				</tr>
																		</thead>
																</table>
														</div>
												</div>
										</div>
								</div>
				</div>
		</section>
@endsection

@section('footer')
		<script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
		<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.fixedHeader.min.js')}}"></script>

		<script>
			function handleDownload(e) {
				const filePathes = [];
				document.querySelectorAll('#users-list tbody tr td a').forEach(function (a) {
					// Check if data-path exists before trying to use it
					const path = a.getAttribute('data-path');
					if (path) {
						filePathes.push(path.replaceAll('\\', '/'));
					} else {
						console.warn('Missing data-path attribute on link:', a.href);
					}
				});

				// Only proceed if we have files to download
				if (filePathes.length === 0) {
					alert('No files available to download');
					return;
				}

				const currentGuard = "{{\Illuminate\Support\Facades\Auth::getDefaultDriver()}}";
				const url = currentGuard == 'clientDepartments' ? "{{route('department.downloadFiles')}}" : "{{route('client.downloadFiles')}}"
				var downloadLink = document.createElement('a');
				downloadLink.href = url + '?filePaths='+ JSON.stringify(filePathes);
				document.body.appendChild(downloadLink);
				downloadLink.click();
			}
				var columnNames = [
					'type',
					'report_no',
					'id',
					'equipment',
					'exam_date',
					'po',
					'internal_so',
					'work_location',
				];
				var selectFields = {};
				@isset($select_fields)selectFields = {!! json_encode($select_fields) !!};@endisset
				
			    var route = "{{ Auth::getDefaultDriver() == 'clientDepartments' ? route('getDataForDataTable.departmentReports') : route('getDataForDataTable.customerReports') }}";
				@isset($route_param)
					route = "{{ Auth::getDefaultDriver() == 'clientDepartments' ? route('getDataForDataTable.departmentReports', $route_param) : route('getDataForDataTable.customerReports', $route_param) }}";
				@endisset

				var table = $('#users-list').DataTable({
						ordering: true,
						// stateSave: true,
						processing: true,
						serverSide: true,
						ajax: {
							url: route,
							data: function(d) {
								// Store column-specific search values
								d.columnSearch = {};
								$('#users-list thead input, #users-list thead select').each(function(index) {
									if($(this).val()) {
										d.columnSearch[columnNames[index]] = $(this).val();
									}
								});
							},
							dataSrc: function(json) {
								// Filter out rows where report_no contains 'PDF Dose not Exists'
								json.data = json.data.filter(function(row) {
									return !row.report_no || !row.report_no.includes('PDF Dose not Exists');
								});
								return json.data;
							}
						},
						columns: [
								{data: 'type', visible: false},
								{data: 'report_no', className: 'text-center'},
								{data: 'id'},
								{data: 'equipment'},
								{data: 'exam_date'},
								{data: 'po'},
								{data: 'internal_so'},
								{data: 'work_location', name: 'work_location'},
						],
						// search: {
						// 	"regex": true
						// },
						rowGroup: {
							dataSrc: 'type',
							startRender: function(rows, group) {
								return $('<tr>')
									.append('<td colspan="7">' + group + '</td>')
									.addClass('table-group');
							}
						},
						order: [[0, 'desc']], // Order by the type column for grouping
						initComplete: function () {
								this.api().columns().every(function (index) {
										var column = this;
                                    	var input = document.createElement("input");

                                    	if (selectFields.hasOwnProperty(columnNames[index])) {
                                            input = document.createElement("select");
                                            var options = selectFields[columnNames[index]];
                                            var keys = Object.keys(options);
											keys.forEach(function(v) {
											    console.log(v, options[v]);
											    var option = document.createElement('option');
											    option.text = options[v];
                                                option.value = v;
											    input.appendChild(option);
											});
										}
										var placeholder_val = $(column.header()).text();
										
										$(input).appendTo($(column.header()).empty()).attr('placeholder', placeholder_val)
										.on('change', function () {
												// Instead of using column.search, we'll trigger a full table redraw
												// which will include our custom column search parameters
												table.draw();
										});
										
								});
						},
						stateLoadParams: function(settings, data) {
								for (i = 0; i < data.columns["length"]; i++) {
										var col_search_val = data.columns[i].search.search;
										if (col_search_val != "") {
												$("input").val(col_search_val);
												console.log(col_search_val);
										}
								}
						},
				});
				var label = document.querySelector('#users-list_filter label');
				if (label) {
					label.firstChild.nodeValue = "Search by Serial Number:";
				}

		</script>
@endsection

