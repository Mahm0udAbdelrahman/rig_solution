@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
<section id="validation">
  <div class="row">
    <div class="col-12">
      <form action="#" class="steps-validation wizard-circle" enctype="multipart/form-data">
        @method('PUT')
        <h6>Step 1</h6>
        <fieldset>
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>JCF Number</label>
                            <select class="form-control" id="lcr_1" name="lcr_1" required="" disabled>
                                <option value="{{$summary->job_request->id}}" selected>{{$summary->job_request->code}}</option>
                            </select>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Purchase Order</label>
                            <input disabled type="text" id="purchaseOrder" name="nsr_2" class="form-control" placeholder="Purchase Order" value="{{$summary->nsr_2}}" >
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                      <div class="form-group mb-0">
                          <div class="controls">
                              <label>Report No.</label>
                              <div class="clearfix">
                                <input type="text" id="precode" class="form-control pr-0" placeholder="Report No" value="{{$summary->job_request->code}} /" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled style="width: 83px; float: left;">
                                <input type="text" id="code" name="code" class="form-control pl-0" placeholder="." value="{{$summary->code}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled  style="width: auto; float: left;">
                              </div>
                              <div class="help-block"></div>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
              <div class="card-content collapse show">
                  <div class="card-body">
                      <div class="row">
                          <div class="col-12 col-sm-6">
                              <div class="form-group mb-0">
                                  <div class="controls">
                                      <label>Name of employer for whom the examination was made</label>
                                      <input type="text" placeholder="Name of employer for whom the examination was made" class="form-control" id="cliname" name="cliname" disabled>
                                      <div class="help-block"></div>
                                  </div>
                              </div>
                          </div>
                          <div class="col-12 col-sm-6">
                              <div class="form-group mb-0">
                                  <div class="controls">
                                      <label>Address of premises at examination was made</label>
                                      <input type="text" id="cliloc" name="cliloc" class="form-control" value="" placeholder="Address of premises at examination was made" disabled >
                                      <div class="help-block"></div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Examination Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ft-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control dp-date-range-from" id="nsr_6" name="nsr_6" placeholder="Examination Date" data-validation--message="This field is " value="{{$summary->nsr_4}}"/>
                            </div>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Work location</label>
                            <input type="text" id="deploc" name="deploc" class="form-control" placeholder="Work location" value="" required="" data-validation-required-message="This field is required" disabled />
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  {{--<div class="col-12 col-sm-4">--}}
                        {{--<div class="form-group mb-0">--}}
                            {{--<div class="controls">--}}
                                {{--<label>Client Department</label>--}}
                                {{--<input type="text" id="clientDepartment" name="clientDepartment" class="form-control"--}}
                                       {{--placeholder="Client Department" value="" required=""--}}
                                       {{--data-validation-required-message="This field is required"--}}
                                       {{--disabled/>--}}
                                {{--<div class="help-block"></div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                {{--</div>--}}
                </div>
              </div>
            </div>
          </div>
          <h6 class="mb-1">Description</h6>
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <div class="controls">
                        <input type="text" id="nsr_11" name="nsr_11" class="form-control" placeholder="Description" value="{{$summary->desc}}" data-validation--message="This field is "/>
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
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <div class="controls">
                        <label>Summary 1</label>
                        <textarea id="nsr_12" name="nsr_12" class="form-control" placeholder="Summary 1" rows="10">{{$summary->nsr_7}}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <h6>Final Conclusion:</h6>
          <div class="card">
              <div class="card-content collapse show">
                  <div class="card-body">

                              <div class="form-group">
                                  <div class="controls">
                                      <label for="nsr_13">NDT result : Accept / Reject ?</label>
                                      <textarea id="nsr_13" name="nsr_13" class="form-control" placeholder="NDT result : Accept / Reject ?">{{$summary->nsr_8}}</textarea>
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

@prepend('child-scripts')
		<script src="{{asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
		<script src="{{asset('app-assets/js/scripts/forms/form-repeater.js')}}"></script>
		<script>
				$(".steps-validation").steps({
			      headerTag: "h6",
			      bodyTag: "fieldset",
			      transitionEffect: "fade",
			      titleTemplate: '<span class="step">#index#</span> #title#',
			      labels: {
			          finish: 'Submit'
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
			          return window.validateWizardCurrentStep($(this));
			      },
			      onFinishing: function (event, currentIndex) {
			          form.validate().settings.ignore = ":disabled";
			          return form.valid();
			      },
			      onFinished: function (event, currentIndex) {

                var form1 = $('.wizard')[0];
                var formdata = new FormData(form1);
                var checkbox = $(".wizard").find("input[type=radio]");
                formdata.append('lcr_1', $('#lcr_1').val());
                formdata.append('code', $('#code').val());
                formdata.append('nsr_2', $('#purchaseOrder').val());
                $.each(checkbox, function(key, val) {
                    if($(this).is(':checked') === true){
                      formdata.append($(this).attr('name'), $(this).attr('id'));
                    }
                });
			          $.ajax({
			            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			            type: 'POST',
                  url: "{{route('summary.update', $summary->id)}}",
			            processData: false,
			            contentType: false,
			            cache: false,
			            data: formdata,
			            dataType: "JSON",
			            beforeSend:function(){
			              $('#submit i').addClass('la la-refresh spinner');
			            },
			            success: function (data){
			              toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () {
			                window.location.replace("{{route('summary.index')}}");
			              }
			            });
			            },
			          });
			      }
			  });
		</script>
@endprepend

@extends('layouts.scripts.reportsforms')


@push('bottom-child-scripts')
    <script>
        get_report_data_for_update($('#lcr_1 option:selected').val(), $('#lcr_1 option:selected').text());

        </script>
        @endpush
