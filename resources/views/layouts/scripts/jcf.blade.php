@push('child-scripts')
		<script>
				function refreshSearchableSelectInstance(selector){
						if (typeof window.refreshSearchableSelect !== 'function') {
								return;
						}
						window.refreshSearchableSelect($(selector));
				}
				function getRealSelectableValues($select){
						return $select.find('option').map(function () {
								var value = $(this).val();
								return (value === undefined || value === null || value === '') ? null : value;
						}).get();
				}
				function setSelectValueAndNotify($select, value){
						if (!$select || !$select.length) {
								return;
						}
						$select.val(value).trigger('change.select2').trigger('change');
				}
				function autoSelectIfSingle($select, selectedValue){
						if (!$select || !$select.length) {
								return;
						}
						if (selectedValue !== null && selectedValue !== undefined && selectedValue !== '' && $select.find('option[value="' + selectedValue + '"]').length) {
								setSelectValueAndNotify($select, selectedValue);
								return;
						}
						var values = getRealSelectableValues($select);
						if (values.length === 1) {
								setSelectValueAndNotify($select, values[0]);
						}
				}
				/***************************************************************************/
				/*** This Function To Get Client / Supplier Data ***/
				function getclinetOrSupplier(url, selectedValue = null){
						$('#client').html('<option value="">Select Value</option>');
						$('#contact').html('<option value="">Select Value</option>');
						$('#clientDepartmentsSelect').html('<option value="">Select Value</option>');
						refreshSearchableSelectInstance('#client');
						refreshSearchableSelectInstance('#contact');
						refreshSearchableSelectInstance('#clientDepartmentsSelect');
						$('#code').val('');
						$('#contact').val(null).trigger('change.select2');
						$('#clientDepartmentsSelect').val(null).trigger('change.select2');
						$.ajax({
								headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
								type: 'GET',
								url: url,
								dataType: "JSON",
								success: function (data) {
											$.each(data, function( index, value ) {
													$('#client').append("<option value='"+value.id+"'> "+value.name+"</option>");
											});
											refreshSearchableSelectInstance('#client');
											$('#client').val(selectedValue).trigger('change.select2');
								},
						});
				}
				// getclinetOrSupplier("{{route('data.forJcf', 'clients')}}");
				/***************************************************************************/
				/*** This Function To Get Client / Supplier Code & Contact Persons Data ***/
				function getcodeAndContactPersons(url, selectedValue = null){
						$('#contact').html('<option value="">Select Value</option>');
						refreshSearchableSelectInstance('#contact');
						$('#contact').val(null).trigger('change.select2');
						$.ajax({
									headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
									type: 'GET',
									url: url,
									dataType: "JSON",
									success: function (data) {
											$('#code').val(data.code);
											$.each(data.contactperson, function( index, value ) {
													$('#contact').append("<option value='"+value.id+"'> "+value.name+"</option>");
											});
											refreshSearchableSelectInstance('#contact');
											autoSelectIfSingle($('#contact'), selectedValue);
									},
						});
				}
				/***************************************************************************/
				/*** This Function To Get Related Client Departments ***/
				function getClientDepartments(url, selectedValue = null){
						$('#clientDepartmentsSelect').html('<option value="">Select Value</option>');
						refreshSearchableSelectInstance('#clientDepartmentsSelect');
						$('#clientDepartmentsSelect').val(null).trigger('change.select2');
						$.ajax({
									headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
									type: 'GET',
									url: url,
									dataType: "JSON",
									success: function (data) {
											$.each(data.departments, function( index, value ) {
													$('#clientDepartmentsSelect').append("<option value='"+value.id+"'> "+value.name+"</option>");
											});
											refreshSearchableSelectInstance('#clientDepartmentsSelect');
											autoSelectIfSingle($('#clientDepartmentsSelect'), selectedValue);
									},
						});
				}
				/***************************************************************************/
				/*** This Function To Get Managers & Employees ***/
				function getManagersAndEmployees(){
						$.ajax({
								headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
								type: 'POST',
								url: "{{route('department.showManagers')}}",
								cache: false,
								data: {
										"department":department
								},
								success: function(data){
										$('#managers, #employees').html('');
										$.each(data.managers, function( key1, value1 ){
												$('#managers').append('<div class="col-md-6 col-sm-12"><fieldset><input type="checkbox" class="manager" name="manager" data-id="'+value1+'" id="manager'+key1+'" /><label for="manager'+key1+'">'+value1+'</label></fieldset></div>');
										});
										$.each(data.employees, function( key, value ) {
												$('#employees').append('<div class="col-md-3 col-sm-12"><fieldset><input type="checkbox" class="eng" name="eng" data-id="'+value+'" id="employee'+key+'" /><label for="employee'+key+'">'+value+'</label></fieldset></div>');
										});
										$('.manager, .eng').iCheck({
												checkboxClass: 'icheckbox_square-green',
										});
								},
						});
				}
				/***************************************************************************/
				var contactway = [];
				/***************************************************************************/
				$('.contactway').on('ifChecked', function(event){
						var id = this.id;
						contactway.push(id);
				});
				$('.contactway').on('ifUnchecked', function(event){
						var id = this.id;
						index = contactway.indexOf(id);
						contactway.splice(index, 1);
				});
				/***************************************************************************/
				var worklocation = [];
				/***************************************************************************/
				$('.worklocation').on('ifChecked', function(event){
						var id = this.id;
						worklocation.push(id);
				});
				$('.worklocation').on('ifUnchecked', function(event){
						var id = this.id;
						index1 = worklocation.indexOf(id);
						worklocation.splice(index1, 1);
				});
				/***************************************************************************/
				var department = [];
				/***************************************************************************/
				$('.department').on('ifChecked', function(event){
						var id = this.id;
						department.push(id);
						getManagersAndEmployees();
				});
				$('.department').on('ifUnchecked', function(event){
						var id = this.id;
						index2 = department.indexOf(id);
						department.splice(index2, 1);
						getManagersAndEmployees();
				});
				/***************************************************************************/
				var manager = [];
				/***************************************************************************/
				$('.steps-validation').on("ifChecked", '.manager',function (e){
						var id = $(this).data('id');
						manager.push(id);
				});
				$('.steps-validation').on("ifUnchecked", '.manager',function (e){
						var id = $(this).data('id');
						index = manager.indexOf(id);
						manager.splice(index, 1);
				});
				/***************************************************************************/
				var eng = [];
				/***************************************************************************/
				$('.steps-validation').on("ifChecked", '.eng',function (e){
						var id = $(this).data('id');
						eng.push(id);
				});
				$('.steps-validation').on("ifUnchecked", '.eng',function (e){
						var id = $(this).data('id');
						index = eng.indexOf(id);
						eng.splice(index, 1);
				});
				/***************************************************************************/
				var tool = [];
				/***************************************************************************/
				$('.tool').on('ifChecked', function(event){
					var id = this.id;
					tool.push(id);
				});
				$('.tool').on('ifUnchecked', function(event){
					var id = this.id;
					index = tool.indexOf(id);
					tool.splice(index, 1);
				});
				/***************************************************************************/
				var specification = [];
				/***************************************************************************/
				$('.specification').on('ifChecked', function(event){
					var id = this.id;
					specification.push(id);
				});
				$('.specification').on('ifUnchecked', function(event){
					var id = this.id;
					index = specification.indexOf(id);
					specification.splice(index, 1);
				});
				/***************************************************************************/
				url = "{{route('client.contactPersonShow', ':id')}}";
				/***************************************************************************/
				$('.steps-validation').on("change", '#suporcli',function (e){
						if(this.checked == true)
						{
								$('#csname').text('Client Name');
								getclinetOrSupplier("{{route('data.forJcf', 'clients')}}");
								url = "{{route('client.contactPersonShow', ':id')}}";
								$('#persontype').val('1');
								$("#clientDepartmentsSelect").prop('disabled', false).trigger('change.select2');
								$('#clientDepartmentSection').show();
						}
						else
						{
								$('#csname').text('Supplier Name');
								getclinetOrSupplier("{{route('data.forJcf', 'suppliers')}}");
								url = "{{route('supplier.contactPersonShow', ':id')}}";
								$('#persontype').val('2');
								$('#clientDepartmentsSelect').val(null).trigger('change.select2');
								$("#clientDepartmentsSelect").prop('disabled', true).trigger('change.select2');
								$('#clientDepartmentSection').hide();
						}
				});
				/***************************************************************************/
				$('.steps-validation').on("change", '#client',function (e){
						var id = $(this).val();
						const tempUrl = url.replace(':id', id);
						getcodeAndContactPersons(tempUrl);

						if($('#suporcli').is(":checked")){
							getClientDepartments("{{route('client.departmentShow', ':id')}}".replace(':id', id))
						}
				});
				/***************************************************************************/
				@if(request()->routeIs('jobRequest.create'))
						getclinetOrSupplier("{{route('data.forJcf', 'clients')}}");
				@endif
				if (typeof window.initAllSearchableSelects === 'function') {
						window.initAllSearchableSelects($('.steps-validation'));
				}
		</script>
@endpush
