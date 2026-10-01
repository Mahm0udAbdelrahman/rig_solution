{{-- Wizard + due date logic for the equipment add / edit form; $submitUrl is the store or update URL --}}
@include('layouts.general-info.equipmentControlledList.partials.date-fields')
<script>
		var intervalMonths = @json(\App\Models\GeneralInfo\EquipmentControlledList::$INTERVALS);

		// Calibration Due Date = Calibration Date + selected interval (clamped to month end, e.g. 31-Aug + 6 Months = 28/29-Feb)
		function updateDueDate() {
				var months = intervalMonths[$('input[name="interval"]:checked').val()];
				var calDate = $('#calibration_date').val();
				// Older intervals (e.g. Pre-Use) keep their saved due date and stay editable
				$('#calibration_due_date_display').prop('readonly', !!months);
				if (!months) {
						return;
				}
				if (!calDate) {
						setDateField('calibration_due_date', '');
						return;
				}
				var parts = calDate.split('-').map(Number);
				var target = new Date(parts[0], parts[1] - 1 + months, 1);
				var lastDay = new Date(target.getFullYear(), target.getMonth() + 1, 0).getDate();
				target.setDate(Math.min(parts[2], lastDay));
				var pad = function(n) { return String(n).padStart(2, '0'); };
				setDateField('calibration_due_date', target.getFullYear() + '-' + pad(target.getMonth() + 1) + '-' + pad(target.getDate()));
		}

		$(document).on('change input', 'input[name="interval"], #calibration_date', updateDueDate);
		$(function () {
				@if(empty($equipment))
						updateDueDate();
				@else
						$('#calibration_due_date_display').prop('readonly', !!intervalMonths[$('input[name="interval"]:checked').val()]);
				@endif
		});

		$(".steps-validation").steps({
				headerTag: "h6",
				bodyTag: "fieldset",
				transitionEffect: "fade",
				titleTemplate: '<span class="step">#index#</span> #title#',
				labels: {
						finish: 'Save'
				},
				onStepChanging: function (event, currentIndex, newIndex) {
						// Allways allow previous action even if the current form is not valid!
						if (currentIndex > newIndex)
						{
								return true;
						}
						// Needed in some cases if the user went back (clean up)
						if (currentIndex < newIndex)
						{
								// To remove error styles
								form.find(".body:eq(" + newIndex + ") label.error").remove();
								form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
						}
						form.validate().settings.ignore = ":disabled,:hidden";
						return form.valid();
				},
				onFinishing: function (event, currentIndex) {
						form.validate().settings.ignore = ":disabled";
						return form.valid();
				},
				onFinished: function (event, currentIndex) {
						var $form = $('#equipmentForm');
						var formdata = new FormData($form[0]);
						var $finish = $form.find('a[href="#finish"]');
						$.ajax({
								headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
								type: 'POST',
								url: "{{ $submitUrl }}",
								processData: false,
								contentType: false,
								cache: false,
								data: formdata,
								dataType: "JSON",
								beforeSend: function () {
										$finish.addClass('disabled').html('<i class="la la-refresh spinner"></i> Saving...');
								},
								success: function (data) {
										toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () {
												window.location.replace(data.redirect || "{{ route('equipment-controlled-list.index') }}");
										} });
								},
								error: function (xhr) {
										$finish.removeClass('disabled').html('Save');
										var errors = xhr.responseJSON && xhr.responseJSON.errors;
										if (xhr.status === 422 && errors) {
												window.showInspectionServerErrors && window.showInspectionServerErrors($form, errors);
												toastr.error(Object.values(errors).flat().join('<br>'), 'Validation Error', { positionClass: 'toast-bottom-left' });
										} else if (xhr.status === 403) {
												toastr.error('You do not have permission to save this equipment.', 'Error', { positionClass: 'toast-bottom-left' });
										} else {
												toastr.error('An error occurred while saving.', 'Error', { positionClass: 'toast-bottom-left' });
										}
								}
						});
				}
		});
</script>
