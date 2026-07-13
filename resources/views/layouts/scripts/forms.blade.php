@section('footer')
		<script src="{{asset('app-assets/vendors/js/extensions/jquery.steps.min.js')}}"></script>
		<script src="{{asset('app-assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
		<script src="{{asset('app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
		<script src="{{asset('app-assets/vendors/js/forms/icheck/icheck.min.js')}}"></script>
		<script src="{{asset('app-assets/vendors/js/forms/toggle/bootstrap-switch.min.js')}}"></script>
		<script src="{{asset('app-assets/vendors/js/forms/toggle/switchery.min.js')}}"></script>
		<script src="{{asset('app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
		<script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
		<script src="{{asset('app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js')}}"></script>
@endsection
@section('ajax')
		<script src="{{asset('app-assets/js/scripts/ui/jquery-ui/date-pickers.js')}}"></script>
		<script>
				var form = $(".steps-validation").show();
				window.form = form;

				window.getWizardFieldLabel = function ($field) {
						if (!$field || !$field.length) {
								return '';
						}

						var id = $field.attr('id');
						if (id) {
								var $forLabel = $('label[for="' + id + '"]').first();
								if ($forLabel.length) {
										return $.trim($forLabel.text());
								}
						}

						var $containerLabel = $field.closest('.controls, .form-group').find('label').first();
						if ($containerLabel.length) {
								return $.trim($containerLabel.text());
						}

						return $field.attr('name') || '';
				};

				window.showWizardValidationMessage = function ($form, $firstInvalid) {
						var label = window.getWizardFieldLabel($firstInvalid);
						var message = label ? ('Please complete required field: ' + label) : 'Please complete required fields in this step to continue.';

						if ($firstInvalid && $firstInvalid.length) {
								var offset = $firstInvalid.offset();
								if (offset && typeof offset.top !== 'undefined') {
										$('html, body').animate({ scrollTop: Math.max(offset.top - 120, 0) }, 250);
								}
								try {
										$firstInvalid.focus();
								} catch (e) {
										// no-op: hidden/non-focusable elements should not break wizard flow
								}
						}

						if (typeof Swal !== 'undefined') {
								Swal.fire({
										title: 'Missing Required Data',
										text: message,
										type: 'warning',
										icon: 'warning',
										confirmButtonText: 'OK',
								});
								return;
						}

						if (typeof toastr !== 'undefined') {
								toastr.warning(message, 'Validation');
								return;
						}

						console.warn('Wizard validation message:', message);
				};

				window.getWizardCurrentStepContainer = function ($form) {
						var $currentBody = $form.find('.body.current').first();
						if ($currentBody.length) {
								return $currentBody;
						}

						var $visibleBody = $form.find('.body:visible').first();
						if ($visibleBody.length) {
								return $visibleBody;
						}

						var $visibleFieldset = $form.find('fieldset:visible').first();
						if ($visibleFieldset.length) {
								return $visibleFieldset;
						}

						return $();
				};

				window.manualValidateWizardVisibleStep = function ($form) {
						var $stepContainer = window.getWizardCurrentStepContainer($form);
						if (!$stepContainer.length) {
								return { valid: true, firstInvalid: $() };
						}

						var invalid = [];
						var checkedRadioGroups = {};

						$stepContainer.find(':input[required]').each(function () {
								var $input = $(this);
								if ($input.prop('disabled')) {
										return;
								}
								if ($input.is(':hidden') && !$input.hasClass('select2-hidden-accessible')) {
										return;
								}

								var type = ($input.attr('type') || '').toLowerCase();
								if (type === 'radio') {
										var name = $input.attr('name');
										if (!name || checkedRadioGroups[name]) {
												return;
										}
										checkedRadioGroups[name] = true;
										var groupSelector = 'input[type="radio"][name="' + name.replace(/"/g, '\\"') + '"]';
										var $group = $stepContainer.find(groupSelector).filter(function () {
												return !$(this).prop('disabled');
										});
										if (!$group.is(':checked')) {
												invalid.push($input[0]);
										}
										return;
								}

								if (type === 'checkbox') {
										if (!$input.is(':checked')) {
												invalid.push($input[0]);
										}
										return;
								}

								var value = ($input.val() || '').toString().trim();
								if (value === '') {
										invalid.push($input[0]);
								}
						});

						return {
								valid: invalid.length === 0,
								firstInvalid: invalid.length ? $(invalid[0]) : $()
						};
				};

				window.validateWizardCurrentStep = function (formElement) {
						try {
								var $form = formElement && formElement.jquery ? formElement : $(formElement || '.steps-validation').first();
								if ($form.length && !$form.is('form')) {
										$form = $form.closest('form.steps-validation');
								}
								if (!$form.length) {
										$form = $('.steps-validation').first();
								}
								if (!$form.length) {
										return true;
								}

								window.form = $form;
								form = $form;

								$form.validate().settings.ignore = ":disabled,:hidden";
								var validatorResult = $form.valid();
								var manualResult = window.manualValidateWizardVisibleStep($form);

								// Fallback: if plugin validator fails because of non-visible artifacts
								// but all required inputs in current visible step are valid, allow moving on.
								if (!validatorResult && manualResult.valid) {
										return true;
								}

								if (!manualResult.valid) {
										window.showWizardValidationMessage($form, manualResult.firstInvalid);
										return false;
								}

								return validatorResult;
						} catch (error) {
								console.error('Wizard validation exception:', error);
								// Fail-open to avoid silent stuck wizard state.
								return true;
						}
				};

				window.resolveInspectionAjaxErrorMessage = function (xhr, fallbackMessage) {
						if (xhr && xhr.responseJSON) {
								if (xhr.responseJSON.message) {
										return xhr.responseJSON.message;
								}
								if (xhr.responseJSON.errors) {
										var firstField = Object.keys(xhr.responseJSON.errors)[0];
										if (firstField && xhr.responseJSON.errors[firstField] && xhr.responseJSON.errors[firstField][0]) {
												return xhr.responseJSON.errors[firstField][0];
										}
								}
						}

						if (xhr && xhr.responseText) {
								var trimmed = xhr.responseText.toString().trim();
								if (trimmed !== '' && trimmed.length <= 300) {
										return trimmed;
								}
						}

						return fallbackMessage || 'Unable to complete this inspection request.';
				};

				window.buildInspectionFormData = function ($form, serializer) {
						var formData = new FormData();
						if ($form && $form.length) {
								$.each($form.serializeArray(), function (_, field) {
										formData.append(field.name, field.value);
								});
						}

						if (typeof serializer === 'function') {
								serializer(formData, $form);
						}

						return formData;
				};

				window.setInspectionSubmitState = function ($button, isBusy, busyLabel) {
						if (!$button || !$button.length) {
								return;
						}

						if (typeof $button.data('inspection-original-label') === 'undefined') {
								$button.data('inspection-original-label', $.trim($button.text()));
						}

						if (isBusy) {
								$button.prop('disabled', true).addClass('disabled');
								if (busyLabel) {
										$button.text(busyLabel);
								}
								return;
						}

						$button.prop('disabled', false).removeClass('disabled');
						$button.text($button.data('inspection-original-label'));
				};

				window.submitInspectionAjax = function (options) {
						var settings = $.extend({
								form: '.steps-validation',
								button: '.actions a[href="#finish"], .btn-finish, .inspection-submit-btn',
								method: 'POST',
								url: window.location.href,
								redirectTo: null,
								busyLabel: 'Submitting...',
								successMessage: 'Inspection saved successfully.',
								errorMessage: 'Unable to submit inspection data.',
								serializer: null,
								onSuccess: null,
								onError: null
						}, options || {});

						var $form = settings.form && settings.form.jquery ? settings.form : $(settings.form).first();
						if ($form.length && !$form.is('form')) {
								$form = $form.closest('form');
						}

						var $button = settings.button && settings.button.jquery ? settings.button : $(settings.button).first();
						var formData = window.buildInspectionFormData($form, settings.serializer);

						window.setInspectionSubmitState($button, true, settings.busyLabel);

						return $.ajax({
								type: settings.method,
								url: settings.url,
								data: formData,
								processData: false,
								contentType: false,
								success: function (response) {
										if (typeof settings.onSuccess === 'function') {
												settings.onSuccess(response, $form, $button);
										}

										if (typeof toastr !== 'undefined' && settings.successMessage) {
												toastr.success(settings.successMessage, 'Success');
										}

										if (settings.redirectTo) {
												window.location.href = typeof settings.redirectTo === 'function'
														? settings.redirectTo(response)
														: settings.redirectTo;
										}
								},
								error: function (xhr) {
										var message = window.resolveInspectionAjaxErrorMessage(xhr, settings.errorMessage);
										window.__inspectionAjaxErrorSilenceUntil = Date.now() + 1200;

										if (typeof settings.onError === 'function') {
												settings.onError(xhr, message, $form, $button);
										}

										if (typeof toastr !== 'undefined') {
												toastr.error(message, 'Submit failed', {
														positionClass: 'toast-bottom-left',
														showMethod: 'slideDown',
														hideMethod: 'slideUp',
														progressBar: true,
														timeOut: 4500,
														fadeOut: 1000,
												});
										}
								},
								complete: function () {
										window.setInspectionSubmitState($button, false);
								}
						});
				};

				if (!window.__inspectionAjaxErrorHandlerBound) {
						$(document).ajaxError(function (event, xhr, settings) {
								if (!window.location.pathname.includes('/dashboard/inspection/')) {
										return;
								}

								var method = ((settings && settings.type) || 'GET').toUpperCase();
								if (method === 'GET') {
										return;
								}

								if (window.__inspectionAjaxErrorSilenceUntil && Date.now() < window.__inspectionAjaxErrorSilenceUntil) {
										return;
								}

								var message = window.resolveInspectionAjaxErrorMessage(xhr, 'Unable to submit inspection data.');
								var signature = [
										(settings && settings.url) || window.location.pathname,
										xhr && typeof xhr.status !== 'undefined' ? xhr.status : '0',
										message
								].join('|');

								if (window.__lastInspectionAjaxErrorSignature === signature
										&& window.__lastInspectionAjaxErrorAt
										&& (Date.now() - window.__lastInspectionAjaxErrorAt) < 1500) {
										return;
								}

								window.__lastInspectionAjaxErrorSignature = signature;
								window.__lastInspectionAjaxErrorAt = Date.now();

								if (typeof toastr !== 'undefined') {
										toastr.error(message, 'Submit failed', {
												positionClass: 'toast-bottom-left',
												showMethod: 'slideDown',
												hideMethod: 'slideUp',
												progressBar: true,
												timeOut: 4500,
												fadeOut: 1000,
										});
										return;
								}

								console.error('Inspection AJAX error:', message, xhr);
						});

						window.__inspectionAjaxErrorHandlerBound = true;
				}

				var makeSelect2AwareIgnore = function (ignoreValue) {
						if (typeof ignoreValue !== 'string') {
								return ignoreValue;
						}

						if (ignoreValue.indexOf(':hidden') === -1 || ignoreValue.indexOf('select2-hidden-accessible') !== -1) {
								return ignoreValue;
						}

						return ignoreValue.replace(':hidden', ':hidden:not(.select2-hidden-accessible)');
				};

				if (typeof $.validator !== 'undefined' && $.validator.prototype && !$.validator.prototype._rigSelect2AwarePatchApplied) {
						var originalValidatorElements = $.validator.prototype.elements;
						$.validator.prototype.elements = function () {
								var originalIgnore = this.settings.ignore;
								this.settings.ignore = makeSelect2AwareIgnore(originalIgnore);
								try {
										return originalValidatorElements.call(this);
								} finally {
										this.settings.ignore = originalIgnore;
								}
						};
						$.validator.prototype._rigSelect2AwarePatchApplied = true;
				}

				if (typeof $.fn.select2 === 'function') {
						window.initSearchableSelect = function($select){
								if (!$select || !$select.length) {
										return;
								}
								if ($select.hasClass('select2-hidden-accessible')) {
										var initializedInstance = $select.data('select2');
										if (initializedInstance && initializedInstance.$container) {
												initializedInstance.$container.attr('data-searchable-select', '1');
										}
										return;
								}
								var placeholderText = $select.attr('data-placeholder') || $select.find('option[value=""]').first().text() || 'Select Value';
								$select.select2({
										width: '100%',
										placeholder: placeholderText,
										allowClear: !$select.prop('required'),
										minimumResultsForSearch: 0,
										dropdownCssClass: 'searchable-select-dropdown'
								});
								var instance = $select.data('select2');
								if (instance && instance.$container) {
										instance.$container.attr('data-searchable-select', '1');
								}
						};

						window.initAllSearchableSelects = function(scope){
								var $scope = scope ? $(scope) : $(document);
								$scope.find('select.searchable-select').each(function () {
										window.initSearchableSelect($(this));
								});
						};

						window.refreshSearchableSelect = function(selector){
								var $select = selector instanceof jQuery ? selector : $(selector);
								if (!$select.length) {
										return;
								}
								if (!$select.hasClass('select2-hidden-accessible')) {
										window.initSearchableSelect($select);
										return;
								}
								$select.trigger('change.select2');
						};

						$(document).off('mousedown.searchableSelect').on('mousedown.searchableSelect', '.select2-container[data-searchable-select="1"] .select2-selection--single', function (event) {
								var $container = $(this).closest('.select2-container');
								var $select = $container.prev('select.searchable-select');
								if (!$select.length || $select.prop('disabled') || $container.hasClass('select2-container--open')) {
										return;
								}
								event.preventDefault();
								$select.select2('open');
						});

						$(document).off('select2:open.searchableSelect').on('select2:open.searchableSelect', function () {
								var searchField = document.querySelector('.select2-container--open .select2-search__field');
								if (searchField) {
										searchField.focus();
										searchField.select();
								}
						});
				}

				$('.steps-validation').on('click','.delete', function(){
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
			      if (result.value) {
			        $('#imagedata').val("");
                    if ($('#previewimagedata').length) {
                      $('#previewimagedata').attr('src', "#");
                    }
			      } else if (result.dismiss === Swal.DismissReason.cancel) {
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
		@stack('child-scripts')
		<script>
				if (typeof window.initAllSearchableSelects === 'function') {
						window.initAllSearchableSelects($('.steps-validation'));
				}
		</script>
		<script>
				$(".steps-validation").validate({
						ignore: 'input[type=hidden]:not(.select2-hidden-accessible)', // ignore hidden fields except select2-backed selects
						errorClass: 'danger',
						successClass: 'success',
						highlight: function (element, errorClass) {
								$(element).removeClass(errorClass);
						},
						unhighlight: function (element, errorClass) {
								$(element).removeClass(errorClass);
						},
						errorPlacement: function (error, element) {
								error.insertAfter(element);
						},
						rules: {
								email: {
										email: true
								}
						}
				});
		</script>
		@stack('bottom-child-scripts')
		<script src="{{asset('app-assets/js/scripts/forms/validation/form-validation.js')}}"></script>
@endsection
