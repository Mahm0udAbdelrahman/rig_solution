@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
    <section id="validation">
        <div class="row">
            <div class="col-12">
                <form action="#" class="steps-validation wizard-circle">
                    @method('PUT')
                    <h6>Step 1</h6>
                    <fieldset>
                        <div class="row form-group">
                            <h6 class="mb-1 col-md-8">Client / Supplier Details</h6>
                            <div class="pb-1 col-md-4 text-right controls">
                                <span class="mr-1"><label for="suporcli">Close it to change to supplier</label></span>
                                <input type="checkbox" class="switchery" id="suporcli" name="suporcli"
                                       @if($jobRequest->supplier_id == NULL) checked @endif />
                            </div>
                        </div>
                        <div class="card border-cyan border-lighten-4">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        {{--<div class="col-md-4">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<div class="controls">--}}
                                                    {{--<label class="m-0">Purchase Order</label>--}}
                                                    {{--<input type="text" id="purchase_order" name="purchase_order" class="form-control"--}}
                                                           {{--value="{{$jobRequest->purchase_order}}" placeholder="purchase_order" aria-invalid="false" required>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label id="csname" class="m-0">Client Name</label>
                                                    <select class="form-control csd searchable-select" id="client" name="csd" required>
                                                        <option value="">Select Value</option>
                                                    </select>
                                                    <input type="hidden" value="1" id="persontype" name="persontype"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label class="m-0">Code Number</label>
                                                    <input type="text" id="code" name="code" class="form-control"
                                                           placeholder="Code Number" aria-invalid="false" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label class="m-0">Contact Name</label>
                                                    <select class="form-control searchable-select" id="contact" name="contact" required>
                                                        <option value="">Select Value</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="clientDepartmentSection" class="col-md-3">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label class="m-0">Client Department</label>
                                                    <select class="form-control searchable-select" id="clientDepartmentsSelect" name="client_department_id" required>
                                                        <option value="">Select Value</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                    </div>
                                    <div class="skin skin-square form-group">
                                        <div class="controls">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label class="m-0 mb-1">Contact Way</label>
                                                            <div class="row input-group">
                                                                <fieldset class="col-md-6">
                                                                    <input type="checkbox" class="contactway"
                                                                           name="contactway" id="phone" required
                                                                           @if(in_array("phone", json_decode($jobRequest->contactway))) checked @endif>
                                                                    <label for="phone">Phone</label>
                                                                </fieldset>
                                                                <fieldset class="col-md-6">
                                                                    <input type="checkbox" class="contactway"
                                                                           name="contactway" id="email"
                                                                           @if(in_array("email", json_decode($jobRequest->contactway))) checked @endif>
                                                                    <label for="email">E-mail</label>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group mb-0">
                                                        <div class="controls">
                                                            <label class="m-0">Contact Date / Time</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i
                                                                                class="ft-calendar"></i></span>
                                                                </div>
                                                                <input type="text"
                                                                       class="form-control dp-date-range-from"
                                                                       id="contactdate" name="contactdate"
                                                                       placeholder="Contact Date / Time" required=""
                                                                       value="{{$jobRequest->contact_date}}"
                                                                       data-validation-required-message="This field is required"/>
                                                            </div>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Subject</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <textarea class="form-control" name="subject" rows="2" required=""
                                                              data-validation-required-message="This field is required"
                                                              placeholder="Subject">{{$jobRequest->subject}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Work Location</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row">
                                                <div class="col-md-3 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="worklocation" required
                                                               name="worklocation" id="rse-yard"
                                                               @if(in_array("rse-yard", json_decode($jobRequest->work_location))) checked @endif>
                                                        <label for="rse-yard">RSE Yard</label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="worklocation" name="worklocation"
                                                               id="rse-lab"
                                                               @if(in_array("rse-lab", json_decode($jobRequest->work_location))) checked @endif>
                                                        <label for="rse-lab">RSE Lab</label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="worklocation" name="worklocation"
                                                               id="client-location"
                                                               @if(in_array("client-location", json_decode($jobRequest->work_location))) checked @endif>
                                                        <label for="client-location">Client Location</label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <fieldset>
                                                        <input type="checkbox" class="worklocation" name="worklocation"
                                                               id="other1"
                                                               @if(in_array("other1", json_decode($jobRequest->work_location))) checked @endif>
                                                        <label for="other1">Other</label>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Department</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row">
                                                @foreach($departments as $department)
                                                    <div class="col-md-3 col-sm-12">
                                                        <fieldset>
                                                            <input type="checkbox" class="department" name="department"
                                                                   required id="{{$department->id}}"
                                                                   @foreach($jobRequest->departments as $jobRequestDepartment)
                                                                   @if($jobRequestDepartment->id == $department->id)
                                                                   checked
                                                                    @endif
                                                                    @endforeach
                                                            >
                                                            <label for="{{$department->id}}">{{$department->name}}</label>
                                                        </fieldset>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Job Required Details</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <textarea class="form-control" name="job_requierd_details" rows="3"
                                                              required=""
                                                              data-validation-required-message="This field is required"
                                                              placeholder="Job Required Details">{{str_replace('<br />', '', $jobRequest->job_requierd_details)}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <h6>Step 2</h6>
                    <fieldset>
                        <h6 class="mb-1">Attention To</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row" id="managers"></div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-0">
                                                        <div class="controls">
                                                            <label class="m-0">Location</label>
                                                            <input type="text" class="form-control" id="deploc"
                                                                   name="deploc" placeholder="Location"
                                                                   value="{{$jobRequest->deploc}}"/>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label class="m-0">Purchase Order</label>
                                                            <input type="text" id="purchase_order" name="purchase_order" class="form-control"
                                                                   value="{{$jobRequest->purchase_order}}" placeholder="purchase_order" aria-invalid="false" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Personal Name / Qualifications Required</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row" id="employees"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Equipment / Material Required</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row">
                                                @foreach($tools as $tool)
                                                    <div class="col-md-3 col-sm-12">
                                                        <fieldset>
                                                            <input type="checkbox" class="tool" name="tool"
                                                                   id="{{strtolower(str_replace(' ', '-', $tool->name))}}"
                                                                   @if(in_array(strtolower(str_replace(' ', '-', $tool->name)), json_decode($jobRequest->tools))) checked @endif
                                                            >
                                                            <label for="{{strtolower(str_replace(' ', '-', $tool->name))}}">{{$tool->name}}</label>
                                                        </fieldset>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Specification</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="skin skin-square form-group mt-1">
                                        <div class="controls">
                                            <div class="row">
                                                @foreach($specifications as $specification)
                                                    <div class="col-md-3 col-sm-12">
                                                        <fieldset>
                                                            <input type="checkbox" class="specification"
                                                                   name="specification"
                                                                   id="{{strtolower(str_replace(' ', '-', $specification->name))}}"
                                                                   @if(in_array(strtolower(str_replace(' ', '-', $specification->name)), json_decode($jobRequest->specification))) checked @endif
                                                            >
                                                            <label for="{{strtolower(str_replace(' ', '-', $specification->name))}}">{{$specification->name}}</label>
                                                        </fieldset>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Scope of Work</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <textarea class="form-control" name="scope_of_work" rows="3"
                                                              placeholder="Scope of Work">{{str_replace('<br />', '', $jobRequest->scope_of_work)}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Start / End Date</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label class="m-0">Start Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="ft-calendar"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control dp-date-range-from"
                                                               id="startdate" name="startdate" placeholder="Start Date"
                                                               value="{{ $start_date_value ?? ($dates[0]->start_at ?? '') }}"/>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-0">
                                                <div class="controls">
                                                    <label class="m-0">End Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="ft-calendar"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control dp-date-range-from"
                                                               id="enddate" name="enddate" placeholder="End Date"
                                                               value="{{ $end_date_value ?? ($dates[0]->end_at ?? '') }}"/>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.scripts.forms')

@extends('layouts.scripts.jcf')

@prepend('child-scripts')
<script>
  $(".steps-validation").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
      finish: 'Update'
    },
    onStepChanging: function (event, currentIndex, newIndex) {
      // Allways allow previous action even if the current form is not valid!
      if (currentIndex > newIndex) {
        return true;
      }
      // Needed in some cases if the user went back (clean up)
      if (currentIndex < newIndex) {
        // To remove error styles
        form.find(".body:eq(" + newIndex + ") label.error").remove();
        form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
      }
      form.validate().settings.ignore = ":disabled,:hidden:not(.select2-hidden-accessible)";
      return form.valid();
    },
    onFinishing: function (event, currentIndex) {
      form.validate().settings.ignore = ":disabled,:hidden:not(.select2-hidden-accessible)";
      return form.valid();
    },
    onFinished: function (event, currentIndex) {
      var form1 = $('.wizard')[0];
      var formdata = new FormData(form1);
      formdata.append('department', JSON.stringify(department));
      formdata.append('contactway', JSON.stringify(contactway));
      formdata.append('worklocation', JSON.stringify(worklocation));
      formdata.append('manager', JSON.stringify(manager));
      formdata.append('eng', JSON.stringify(eng));
      formdata.append('tool', JSON.stringify(tool));
      formdata.append('specification', JSON.stringify(specification));
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: "{{ route('jobRequest.update', $jobRequest->id) }}",
        processData: false,
        contentType: false,
        cache: false,
        data: formdata,
        dataType: "JSON",
        beforeSend: function () {
          $('#submit i').addClass('la la-refresh spinner');
        },
        success: function (data) {
          toastr.info('Good Job !', data.success, {
            positionClass: 'toast-bottom-left',
            "showMethod": "slideDown",
            "hideMethod": "slideUp",
            "progressBar": true,
            timeOut: 1000,
            fadeOut: 1000,
            onHidden: function () {
              window.location.replace("{{route('jobRequest.index')}}");
            }
          });
        },
        error: function (xhr) {
          var errorMessage = 'Update failed. Please check date fields and required values.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
          }
          toastr.error(errorMessage, 'Error', {
            positionClass: 'toast-bottom-left',
            "showMethod": "slideDown",
            "hideMethod": "slideUp",
            "progressBar": true,
            timeOut: 5000
          });
        }
      });
    }
  });
</script>
@endprepend

@push('bottom-child-scripts')
    <script>
      var url = "";
      var route = "";
      var client_id = "";
      if ($('#suporcli').is(':checked')) {
        $('#csname').text('Client Name');
        route = "{{route('data.forJcf', 'clients')}}";
        url = "{{route('client.contactPersonShow', ':id')}}";
        $('#persontype').val('1');
        client_id = "{{$jobRequest->client_id}}";
				$("#clientDepartmentsSelect").prop('disabled', false).trigger('change.select2');
        $('#clientDepartmentSection').show();
      }
      else {
        $('#csname').text('Supplier Name');
        route = "{{route('data.forJcf', 'suppliers')}}";
        url = "{{route('supplier.contactPersonShow', ':id')}}";
        $('#persontype').val('2');
        client_id = "{{$jobRequest->supplier_id}}";
        $('#clientDepartmentsSelect').val(null).trigger('change.select2');
				$("#clientDepartmentsSelect").prop('disabled', true).trigger('change.select2');
        $('#clientDepartmentSection').hide();
      }

      getclinetOrSupplier(route, client_id);

      // $('.steps-validation').on("DOMSubtreeModified", '#client', function (e) {
      //   $('#client option[value="' + client_id + '"]').attr('selected', 'selected');
      // });

      url = url.replace(':id', client_id);
			var contact_id = "{{$jobRequest->contact_people_id}}";
      getcodeAndContactPersons(url, contact_id);

      // $('.steps-validation').on("DOMSubtreeModified", '#contact', function (e) {
      //   $('#contact option[value="' + contact_id + '"]').attr('selected', 'selected');
      // });

      function getClientDepartments(url){
        $('#clientDepartmentsSelect').html('<option value="">Select Value</option>');
        if (typeof refreshSearchableSelectInstance === 'function') {
          refreshSearchableSelectInstance('#clientDepartmentsSelect');
        }
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
            if (typeof refreshSearchableSelectInstance === 'function') {
              refreshSearchableSelectInstance('#clientDepartmentsSelect');
            }
            if (typeof autoSelectIfSingle === 'function') {
              autoSelectIfSingle($('#clientDepartmentsSelect'), "{{$jobRequest->client_department_id}}");
            } else {
						  $('#clientDepartmentsSelect').val("{{$jobRequest->client_department_id}}").trigger('change.select2');
            }
          }
        });
      }
      getClientDepartments("{{route('client.departmentShow', ':id')}}".replace(':id', client_id));

      // $('.steps-validation').on("DOMSubtreeModified", '#clientDepartmentsSelect', function (e) {
      //   var clientDeparmentId = "{{$jobRequest->client_department_id}}";
      //   console.log("HERE CHANGE>>>", clientDeparmentId);
      //   $('#clientDepartmentsSelect option[value="' + clientDeparmentId + '"]').attr('selected', 'selected');
      // });

      $(".contactway").each(function (index, value) {
        if ($(this).is(':checked')) {
          contactway.push(value.id);
        }
      });

      $(".worklocation").each(function (index, value) {
        if ($(this).is(':checked')) {
          worklocation.push(value.id);
        }
      });

      $(".department").each(function (index, value) {
        if ($(this).is(':checked')) {
          department.push(value.id);
        }
      });

      $(".tool").each(function (index, value) {
        if ($(this).is(':checked')) {
          tool.push(value.id);
        }
      });

      $(".specification").each(function (index, value) {
        if ($(this).is(':checked')) {
          specification.push(value.id);
        }
      });

      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: "{{route('department.showManagers')}}",
        cache: false,
        data: {
          "department": department
        },
        success: function (data) {
          $('#managers, #employees').html('');
          var managers_job = "{{$jobRequest->managers}}";
          $.each(data.managers, function (key1, value1) {
            var checkOrNot11;
            if (managers_job.includes(value1)) {
              checkOrNot11 = "checked";
            }
            else {
              checkOrNot11 = "";
            }
            $('#managers').append('<div class="col-md-6 col-sm-12"><fieldset><input type="checkbox" class="manager" name="manager" required data-id="' + value1 + '" id="manager' + key1 + '" ' + checkOrNot11 + ' /><label for="manager' + key1 + '">' + value1 + '</label></fieldset></div>');
          });
          $(".manager").each(function (index, value) {
            if ($(this).is(':checked')) {
              manager.push($(value).data('id'));
            }
          });
          var employee_job = "{{$jobRequest->employees}}";
          $.each(data.employees, function (key, value) {
            var checkOrNot12;
            if (employee_job.includes(value)) {
              checkOrNot12 = "checked";
            }
            else {
              checkOrNot12 = "";
            }
            $('#employees').append('<div class="col-md-3 col-sm-12"><fieldset><input type="checkbox" class="eng" name="eng" required data-id="' + value + '" id="employee' + key + '" ' + checkOrNot12 + '/><label for="employee' + key + '">' + value + '</label></fieldset></div>');
          });
          $(".eng").each(function (index, value) {
            if ($(this).is(':checked')) {
              eng.push($(value).data('id'));
            }
          });
          $('.manager, .eng').iCheck({
            checkboxClass: 'icheckbox_square-green',
          });
        },
      });
    </script>
@endpush
