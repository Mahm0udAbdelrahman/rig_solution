@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
<section id="validation">
  <div class="row">
    <div class="col-12">
      <form action="#" class="steps-validation wizard-circle">
        <h6>Step 1</h6>
        <fieldset>
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>JCF Number</label>
                            <select class="form-control" id="lcr_1" name="lcr_1" required="">
                              <option value="">Select Value</option>
                              @foreach($jobrequests as $jobrequest)
                                  <option value="{{$jobrequest->id}}">{{$jobrequest->code}}</option>
                              @endforeach
                            </select>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Report No.</label>
                            <div class="clearfix">
                              <input type="text" id="precode" class="form-control pr-0" placeholder="Report No" value="" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled style="width: 83px; float: left;">
                              <input type="text" id="code" name="code" class="form-control pl-0" placeholder="." value="" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled  style="width: auto; float: left;">
                            </div>
                        <div class="help-block"></div></div>
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
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Examination Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ft-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control datepicker-default" id="lcr_60" name="lcr_60" placeholder="Examination Date" required="" data-validation-required-message="This field is required"/>
                            </div>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                      <div class="controls">
                        <label>Color Code</label>
                        <input type="text" id="lcr_70" name="lcr_70" class="form-control" value="" placeholder=" Color Code "  >
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

@prepend('child-scripts')
<script>
    function parseLregisterSubmitError(xhr) {
        var fallback = 'Failed to submit Lifting Register.';

        if (!xhr) {
            return fallback;
        }

        if (xhr.responseJSON) {
            if (xhr.responseJSON.message) {
                return xhr.responseJSON.message;
            }
            if (xhr.responseJSON.error) {
                return xhr.responseJSON.error;
            }
            if (xhr.responseJSON.errors) {
                var firstKey = Object.keys(xhr.responseJSON.errors)[0];
                if (firstKey && xhr.responseJSON.errors[firstKey] && xhr.responseJSON.errors[firstKey][0]) {
                    return xhr.responseJSON.errors[firstKey][0];
                }
            }
        }

        if (xhr.responseText && typeof xhr.responseText === 'string') {
            var titleMatch = xhr.responseText.match(/<title>(.*?)<\/title>/i);
            if (titleMatch && titleMatch[1]) {
                return titleMatch[1].trim();
            }
        }

        return fallback;
    }

    var lregisterSubmitInProgress = false;

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
                $(this).find(".body:eq(" + newIndex + ") label.error").remove();
                $(this).find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }
            return window.validateWizardCurrentStep($(this));
    },
        onFinishing: function (event, currentIndex) {
            return window.validateWizardCurrentStep($(this));
        },
        onFinished: function (event, currentIndex) {
            if (lregisterSubmitInProgress) {
                return false;
            }

            var form1 = $('.steps-validation')[0];
            if (!form1) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Submit Failed',
                        text: 'Unable to initialize submit form.',
                        type: 'error',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-danger'
                    });
                }
                return false;
            }

            var formdata = new FormData(form1);
            formdata.append('lcr_1', $('#lcr_1').val());
            formdata.append('lcr_60', $('#lcr_60').val());
            formdata.append('lcr_70', $('#lcr_70').val());
            lregisterSubmitInProgress = true;
            $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type: 'POST',
              url: "{{route('lregister.store')}}",
              processData: false,
              contentType: false,
              cache: false,
              data: formdata,
              dataType: "JSON",
              beforeSend:function(){
                $('#submit i').addClass('la la-refresh spinner');
              },
              success: function (data){
                lregisterSubmitInProgress = false;
                toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () {
window.location.href = "{{route('lregister.index')}}";
                }
              });
              },
              error: function (xhr) {
                lregisterSubmitInProgress = false;
                var responseMessage = parseLregisterSubmitError(xhr);

                if (typeof Swal !== 'undefined') {
                  Swal.fire({
                    title: 'Submit Failed',
                    text: responseMessage,
                    type: 'error',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-danger'
                  });
                  return;
                }

                if (typeof toastr !== 'undefined') {
                  toastr.error(responseMessage, 'Submit Failed');
                }
              },
              complete: function () {
                $('#submit i').removeClass('la la-refresh spinner');
              }
            });

            return false;
        }
    });
</script>

@endprepend

@extends('layouts.scripts.reportsforms')
