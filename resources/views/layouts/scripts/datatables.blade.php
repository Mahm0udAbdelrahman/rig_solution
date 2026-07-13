@section('footer')
		<script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
		<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.fixedHeader.min.js')}}"></script>

		<script>
				var columnNames = [
					@foreach($columns as $column)
                    	'{{$column}}',
					@endforeach
				];
				var selectFields = {};
				@isset($select_fields)selectFields = {!! json_encode($select_fields) !!};@endisset

				var datatableOptions = {};
				@isset($datatable_options)datatableOptions = {!! json_encode($datatable_options) !!};@endisset

				var nonOrderableColumns = [];
				@isset($non_orderable_columns)nonOrderableColumns = {!! json_encode($non_orderable_columns) !!};@endisset

				var nonSearchableColumns = [];
				@isset($non_searchable_columns)nonSearchableColumns = {!! json_encode($non_searchable_columns) !!};@endisset

				var disableColumnFilters = [];
				@isset($disable_column_filters)disableColumnFilters = {!! json_encode($disable_column_filters) !!};@endisset
				
				var route = "{{ route('getDataForDataTable.'.$route) }}";
				@isset($route_param)
					route = "{{ route('getDataForDataTable.'.$route, $route_param) }}";
				@endisset

				var isInspectionListing = route.indexOf('/inspection/') !== -1;
				var $tableElement = $('#users-list');
				var $tableHead = $tableElement.find('thead');
				var $headingRow = $tableHead.find('tr').first();
				var $cardBody = $tableElement.closest('.card-body');
				var $usersList = $tableElement.closest('.users-list');
				var $card = $tableElement.closest('.card');

				if ($cardBody.length) {
						$cardBody.addClass('listing-table-shell');
				}

				if ($headingRow.length) {
						$headingRow.addClass('column-headings');
				}

				if (!$tableHead.find('tr.filter-row').length && $headingRow.length) {
						var $filterRow = $headingRow.clone(false).removeClass('column-headings').addClass('filter-row');
						$filterRow.find('th').empty().removeAttr('width');
						$tableHead.append($filterRow);
				}

				if ($usersList.length && !$usersList.children('.listing-table-toolbar').length) {
						var $toolbar = $('<div class="listing-table-toolbar is-sticky"></div>');
						var $toolbarActions = $('<div class="listing-toolbar-actions"></div>');
						var $toolbarPresets = $('<div class="listing-toolbar-presets"></div>');
						var $toolbarSecondary = $('<div class="listing-toolbar-actions"></div>');
						var $primaryButtons = $usersList.children('a.btn, button.btn').not('.js-clear-datatable-filters');

						$primaryButtons.each(function () {
								$(this).addClass('listing-toolbar-pill');
								$toolbarActions.append(this);
						});

						if (isInspectionListing) {
								$toolbarPresets.append('<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-dt-inspection-smart-preset is-active" data-preset="">All</button>');
								$toolbarPresets.append('<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-dt-inspection-smart-preset" data-preset="approved">Approved</button>');
								$toolbarPresets.append('<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-dt-inspection-smart-preset" data-preset="need_approve">Need Approve</button>');
								$toolbarPresets.append('<button type="button" class="btn btn-sm btn-outline-primary listing-toolbar-pill js-dt-inspection-smart-preset" data-preset="need_publish">Need Publish</button>');
						}

						$toolbarSecondary.append('<button type="button" class="btn btn-outline-secondary listing-toolbar-clear js-clear-datatable-filters">Clear Filters</button>');
						$toolbar.append($toolbarActions);
						if ($toolbarPresets.children().length) {
								$toolbar.append($toolbarPresets);
						}
						$toolbar.append($toolbarSecondary);

						if ($card.length) {
								$card.before($toolbar);
						} else {
								$usersList.prepend($toolbar);
						}
				}

				var currentSmartPresetValue = '';
				if (isInspectionListing && typeof URLSearchParams !== 'undefined') {
						var presetFromQuery = new URLSearchParams(window.location.search).get('smart_preset') || '';
						currentSmartPresetValue = presetFromQuery;
				}
				window.currentSmartPreset = currentSmartPresetValue;

				if (isInspectionListing && currentSmartPresetValue !== '') {
						$('.js-dt-inspection-smart-preset').removeClass('is-active');
						$('.js-dt-inspection-smart-preset[data-preset="' + currentSmartPresetValue + '"]').addClass('is-active');
				}

				var columnsConfig = columnNames.map(function (columnName) {
						var config = {
								data: columnName,
								name: columnName,
								orderable: nonOrderableColumns.indexOf(columnName) === -1,
								searchable: nonSearchableColumns.indexOf(columnName) === -1
						};

						return config;
				});

				var shouldOrder = isInspectionListing ? true : (datatableOptions.hasOwnProperty('ordering') ? !!datatableOptions.ordering : false);
				var orderBy = datatableOptions.hasOwnProperty('order') ? datatableOptions.order : (isInspectionListing ? [[0, 'desc']] : []);
				if (isInspectionListing && (!Array.isArray(orderBy) || !orderBy.length)) {
						orderBy = [[0, 'desc']];
				}
				var useFilterRow = datatableOptions.hasOwnProperty('useFilterRow') ? !!datatableOptions.useFilterRow : true;
				var fixedHeaderOptions = datatableOptions.hasOwnProperty('fixedHeader') ? datatableOptions.fixedHeader : false;
				var filterDebounceMs = datatableOptions.hasOwnProperty('filterDebounceMs') ? parseInt(datatableOptions.filterDebounceMs, 10) : 220;
				var searchDelayMs = datatableOptions.hasOwnProperty('searchDelay') ? parseInt(datatableOptions.searchDelay, 10) : null;
				var columnFilterTimers = {};

				if (!$tableHead.find('tr.filter-row').length) {
						useFilterRow = false;
				}

				var pendingInspectionSortStart = null;
				var lastInspectionStart = 0;

				var table = $('#users-list').DataTable({
						ordering: shouldOrder,
						order: orderBy,
						orderCellsTop: useFilterRow,
						fixedHeader: fixedHeaderOptions,
						searchDelay: searchDelayMs,
						// stateSave: true,
						processing: true,
						serverSide: true,
						ajax: {
								url: route,
								data: function (d) {
										d.smart_preset = currentSmartPresetValue || window.currentSmartPreset || '';
										if (isInspectionListing && pendingInspectionSortStart !== null) {
												d.start = pendingInspectionSortStart;
										}
										var smartSortColumn = '';
										var smartSortDir = 'desc';
										if (Array.isArray(d.order) && d.order.length) {
												var orderItem = d.order[0] || {};
												var orderColumnIndex = parseInt(orderItem.column, 10);
												if (!isNaN(orderColumnIndex) && Array.isArray(d.columns) && d.columns[orderColumnIndex]) {
														smartSortColumn = d.columns[orderColumnIndex].name || d.columns[orderColumnIndex].data || '';
												}
												smartSortDir = (orderItem.dir || 'desc');
										}
										d.smart_sort_column = smartSortColumn;
										d.smart_sort_dir = smartSortDir;
								}
						},
						columns: columnsConfig,
						// search: {
						// 	"regex": true
						// },
						initComplete: function () {
								this.api().columns().every(function (index) {
										var column = this;
										var columnName = columnNames[index];
										var headerCell = $(column.header());
										var headerText = $('#users-list thead tr:first-child th').eq(index).text().trim() || headerCell.text().trim();
										if (isInspectionListing && (columnName === 'code' || columnName === 'report_code')) {
												headerText = 'JCF / Report No.';
										}

										if (isInspectionListing && columnName === 'action') {
												return;
										}

										if (disableColumnFilters.indexOf(columnName) !== -1) {
												return;
										}

										var input = document.createElement("input");

                                    	if (selectFields.hasOwnProperty(columnNames[index])) {
                                            input = document.createElement("select");
                                            var options = selectFields[columnNames[index]];
                                            var keys = Object.keys(options);
											keys.forEach(function(v) {
											    var option = document.createElement('option');
											    option.text = options[v];
                                                option.value = v;
											    input.appendChild(option);
											});
										}

										var filterCell = headerCell;
										if (useFilterRow) {
												filterCell = $('#users-list thead tr.filter-row th').eq(index);
										} else {
												headerCell.empty();
										}

										$(input)
										.addClass('dt-column-filter')
										.attr('data-column-filter', columnName)
										.appendTo(filterCell.empty())
										.attr('placeholder', headerText);

										var isSelectFilter = input.tagName === 'SELECT';
										if (isSelectFilter) {
												$(input).on('change', function () {
														column.search($(this).val(), false, false, true).draw();
												});
										} else {
												$(input).on('keyup change', function () {
														var value = $(this).val();
														clearTimeout(columnFilterTimers[columnName]);
														columnFilterTimers[columnName] = setTimeout(function () {
																column.search(value, false, false, true).draw();
														}, filterDebounceMs);
												});
										}
										
								});

								if (isInspectionListing) {
										var $globalSearchLabel = $('#users-list_filter label');
										var $globalSearchInput = $globalSearchLabel.find('input');
										if ($globalSearchLabel.length && $globalSearchInput.length) {
												$globalSearchLabel.contents().filter(function () {
														return this.nodeType === 3;
												}).remove();
												$globalSearchLabel.prepend('Search JCF / report / serial: ');
												$globalSearchInput.attr('placeholder', 'JCF, report number, or serial no');
										}
								}
						},
				});
				window.currentDataTable = table;

				if (isInspectionListing) {
						table.on('page.dt', function () {
								var info = table.page.info();
								lastInspectionStart = info ? info.start : 0;
						});

						table.on('xhr.dt', function () {
								pendingInspectionSortStart = null;
								var info = table.page.info();
								lastInspectionStart = info ? info.start : lastInspectionStart;
						});
				}

				function resetSharedDataTableFilters() {
						table.search('');
						table.columns().search('');
						$('#users-list thead tr.filter-row').find('input, select').val('');
				}

				$('#users-list thead').on('click', 'tr.filter-row th', function (e) {
						e.stopPropagation();
				});

				$('#users-list thead').on('click', 'tr.column-headings th', function () {
						if (!isInspectionListing) {
								return;
						}
						var index = $(this).index();
						if (index < 0) {
								return;
						}
						var columnName = columnNames[index] || '';
						if (nonOrderableColumns.indexOf(columnName) !== -1 || $(this).hasClass('sorting_disabled')) {
								return;
						}
						var info = table.page.info();
						pendingInspectionSortStart = info ? info.start : lastInspectionStart;
				});

				$(document).on('click', '.js-clear-datatable-filters', function () {
						currentSmartPresetValue = '';
						window.currentSmartPreset = '';
						pendingInspectionSortStart = 0;

						$('.js-smart-preset').removeClass('is-active');
						$('.js-smart-preset[data-preset=""]').addClass('is-active');
						$('.js-dt-inspection-smart-preset').removeClass('is-active');
						$('.js-dt-inspection-smart-preset[data-preset=""]').addClass('is-active');

						resetSharedDataTableFilters();
						if (table.page) {
								table.page('first').draw('page');
								return;
						}
						table.draw();
				});

				$(document).on('click', '.js-dt-inspection-smart-preset', function () {
						currentSmartPresetValue = $(this).data('preset') || '';
						window.currentSmartPreset = currentSmartPresetValue;
						pendingInspectionSortStart = 0;
						$('.js-dt-inspection-smart-preset').removeClass('is-active');
						$(this).addClass('is-active');
						resetSharedDataTableFilters();
						if (table.page) {
								table.page('first').draw('page');
								return;
						}
						table.draw();
				});

				function positionListingDropdown($dropdown) {
						var $menu = $dropdown.data('floatingDropdownMenu');
						var $toggle = $dropdown.find('[data-toggle="dropdown"]').first();
						if (!$menu || !$menu.length || !$toggle.length) {
								return;
						}

						var toggleOffset = $toggle.offset();
						if (!toggleOffset) {
								return;
						}

						var toggleHeight = $toggle.outerHeight() || 0;
						var toggleWidth = $toggle.outerWidth() || 0;
						var viewportLeft = $(window).scrollLeft();
						var viewportTop = $(window).scrollTop();
						var viewportWidth = $(window).width();
						var viewportHeight = $(window).height();
						var menuWidth = $menu.outerWidth() || 280;
						var menuHeight = $menu.outerHeight() || 320;
						var left = toggleOffset.left + toggleWidth - menuWidth;
						var top = toggleOffset.top + toggleHeight + 6;

						if (left < viewportLeft + 8) {
								left = toggleOffset.left;
						}

						if (left + menuWidth > viewportLeft + viewportWidth - 8) {
								left = viewportLeft + viewportWidth - menuWidth - 8;
						}

						if (left < viewportLeft + 8) {
								left = viewportLeft + 8;
						}

						if (top + menuHeight > viewportTop + viewportHeight - 8) {
								var upwardTop = toggleOffset.top - menuHeight - 6;
								if (upwardTop >= viewportTop + 8) {
										top = upwardTop;
								} else {
										top = Math.max(viewportTop + 8, viewportTop + viewportHeight - menuHeight - 8);
								}
						}

						$menu.css({
								top: top,
								left: left,
								right: 'auto',
								bottom: 'auto'
						});
				}

				$(document).on('shown.bs.dropdown', '.listing-table-shell .dropdown, .listing-table-shell .btn-group', function () {
						var $dropdown = $(this);
						var $menu = $dropdown.find('.dropdown-menu').first();
						if (!$menu.length || $dropdown.data('floatingDropdownActive')) {
								return;
						}

						$dropdown.data('floatingDropdownActive', true);
						$dropdown.data('floatingDropdownParent', $menu.parent());
						$dropdown.data('floatingDropdownNext', $menu.next());
						$dropdown.data('floatingDropdownMenu', $menu);

						$menu.addClass('wf-floating-dropdown-menu').appendTo('body');
						positionListingDropdown($dropdown);
				});

				$(window).on('scroll resize', function () {
						$('.listing-table-shell .dropdown.show, .listing-table-shell .btn-group.show').each(function () {
								positionListingDropdown($(this));
						});
				});

				$(document).on('hide.bs.dropdown', '.listing-table-shell .dropdown, .listing-table-shell .btn-group', function () {
						var $dropdown = $(this);
						var $menu = $dropdown.data('floatingDropdownMenu');
						var $parent = $dropdown.data('floatingDropdownParent');
						var $next = $dropdown.data('floatingDropdownNext');

						if (!$menu || !$menu.length || !$parent || !$parent.length) {
								return;
						}

						$menu.removeClass('wf-floating-dropdown-menu').css({
								top: '',
								left: '',
								right: '',
								bottom: ''
						});

						if ($next && $next.length) {
								$menu.insertBefore($next);
						} else {
								$parent.append($menu);
						}

						$dropdown.removeData('floatingDropdownMenu');
						$dropdown.removeData('floatingDropdownParent');
						$dropdown.removeData('floatingDropdownNext');
						$dropdown.removeData('floatingDropdownActive');
				});

				table.on('click', '.duplicate', function(){
				    var id = $(this).data('id');
				    var url = "{{ route('report.duplicate', ':id') }}";
				    url = url.replace(':id', id);
				    $.ajax({
				        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				        url: url,
				        method: 'GET',
				        success: function(data)
								{
				          	toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { table.draw(); } });
				        }
				    });
			  });

				table.on('click', '.delete', function(){
						var selectIds = $(this).data('id');
						Swal.fire({
								title: 'Are You Sure ?',
								text: "This item will be permanently deleted!",
								type: 'error',
								showCancelButton: true,
								confirmButtonColor: '#3085d6',
								cancelButtonColor: '#d33',
								confirmButtonText: 'Yes, Delete It !',
								confirmButtonClass: 'btn btn-danger',
								cancelButtonClass: 'btn btn-dark ml-1',
								cancelButtonText: 'Cancel',
								buttonsStyling: false,
						}).then(function (result) {
								if (result.value)
								{
										var url = "{{ route($route.'.destroy', ':selectIds') }}";
										url = url.replace(':selectIds', selectIds);
										$.ajax({
												headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
												url: url,
												method: 'DELETE',
												success: function(data){
														toastr.error('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { table.draw(); } });
												}
										});
								}
								else if (result.dismiss === Swal.DismissReason.cancel)
								{
										Swal.fire({
												title: 'Cancelled',
												text: 'Your data is safe :)',
												type: 'error',
												confirmButtonClass: 'btn btn-success',
										})
								}
						})
				});
		</script>
@endsection
