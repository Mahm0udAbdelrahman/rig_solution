@php
		$pickerId = $picker_id ?? 'workflow-jcf-picker';
		$buttonClass = $button_class ?? 'btn btn-primary clear mb-1';
		$buttonLabel = $button_label ?? 'Create New';
		$modalTitle = $modal_title ?? 'Select JCF';
		$emptyText = $empty_text ?? 'No JCF available for this action.';
		$routeTemplate = $route_template ?? '';
		$jcfOptions = $jcf_options ?? [];
@endphp

<button type="button"
				class="{{ $buttonClass }} js-open-workflow-jcf-picker"
				data-picker-id="{{ $pickerId }}">
		<i class="la la-plus"></i> {{ $buttonLabel }}
</button>

<script>
		(function () {
				var pickerId = @json($pickerId);
				var routeTemplate = @json($routeTemplate);
				var modalTitle = @json($modalTitle);
				var emptyText = @json($emptyText);
				var optionsMap = @json($jcfOptions);

				function escapeHtml(value) {
						return String(value)
								.replace(/&/g, '&amp;')
								.replace(/</g, '&lt;')
								.replace(/>/g, '&gt;')
								.replace(/"/g, '&quot;')
								.replace(/'/g, '&#039;');
				}

				function openJcfPicker() {
						var entries = Object.keys(optionsMap || {}).map(function (id) {
								return { id: id, label: optionsMap[id] };
						});

						if (!entries.length) {
								Swal.fire({
										title: 'No JCF Available',
										text: emptyText,
										type: 'info',
										confirmButtonClass: 'btn btn-primary',
										buttonsStyling: false
								});
								return;
						}

						var safePickerSuffix = pickerId.replace(/[^a-zA-Z0-9_-]/g, '');
						var searchId = 'jcf-search-' + safePickerSuffix;
						var selectId = 'jcf-select-' + safePickerSuffix;

						var selectOptionsHtml = entries.map(function (entry) {
								return '<option value="' + escapeHtml(entry.id) + '">' + escapeHtml(entry.label) + '</option>';
						}).join('');

						Swal.fire({
								title: modalTitle,
								html:
										'<input id="' + searchId + '" class="swal2-input" placeholder="Search JCF / client..." style="margin:0 0 10px 0;">' +
										'<select id="' + selectId + '" class="swal2-select" size="8" style="display:block;width:100%;height:auto;margin:0;">' + selectOptionsHtml + '</select>',
								showCancelButton: true,
								confirmButtonText: 'Continue',
								cancelButtonText: 'Cancel',
								confirmButtonClass: 'btn btn-primary',
								cancelButtonClass: 'btn btn-light ml-1',
								buttonsStyling: false,
								focusConfirm: false,
								preConfirm: function () {
										var selectEl = document.getElementById(selectId);
										var selected = selectEl ? selectEl.value : '';
										if (!selected) {
												Swal.showValidationMessage('Please choose a JCF first');
												return false;
										}
										return selected;
								},
								didOpen: function () {
										var searchEl = document.getElementById(searchId);
										var selectEl = document.getElementById(selectId);
										if (!searchEl || !selectEl) {
												return;
										}

										var allEntries = entries.slice();
										var renderOptions = function (filterText) {
												var keyword = (filterText || '').toLowerCase().trim();
												var filtered = !keyword
														? allEntries
														: allEntries.filter(function (entry) {
																return String(entry.label).toLowerCase().indexOf(keyword) !== -1
																		|| String(entry.id).toLowerCase().indexOf(keyword) !== -1;
														});

												if (!filtered.length) {
														selectEl.innerHTML = '<option value="" disabled>No matches found</option>';
														selectEl.value = '';
														return;
												}

												selectEl.innerHTML = filtered.map(function (entry) {
														return '<option value="' + escapeHtml(entry.id) + '">' + escapeHtml(entry.label) + '</option>';
												}).join('');
												selectEl.selectedIndex = 0;
										};

										searchEl.addEventListener('input', function () {
												renderOptions(searchEl.value);
										});

										searchEl.focus();
										renderOptions('');
								}
						}).then(function (result) {
								if (!result.value) {
										return;
								}
								window.location.href = routeTemplate.replace('__ID__', result.value);
						});
				}

				document.addEventListener('click', function (event) {
						var trigger = event.target.closest('.js-open-workflow-jcf-picker[data-picker-id="' + pickerId + '"]');
						if (!trigger) {
								return;
						}
						event.preventDefault();
						openJcfPicker();
				});
		})();
</script>
