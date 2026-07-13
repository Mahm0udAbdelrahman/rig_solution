@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
<section id="validation">
  <div class="row">
    <div class="col-12">
      <form action="#" class="steps-validation wizard-circle" enctype="multipart/form-data">
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
                            <select class="form-control" id="lcr_1" name="lcr_1">
                                <option value="">Select Value</option>
                                @foreach($jobrequests as $jobrequest)
                                    <option value="{{$jobrequest->id}}">{{$jobrequest->code}}</option>
                                @endforeach
                            </select>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Purchase Order</label>
                            <input type="text" id="purchaseOrder" name="lcr_2" class="form-control" placeholder="Purchase Order" disabled>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
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
                  <div class="col-12">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Examination Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ft-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control datepicker-default" id="lcr_6" name="lcr_6" placeholder="Examination Date" required="" data-validation-required-message="This field is required"/>
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
                      <div class="col-6">
                          <div class="form-group mb-0">
                              <div class="controls">
                                  <label>Work location</label>
                                  <input type="text" id="deploc" name="deploc" class="form-control"
                                         placeholder="Work location" value="" required=""
                                         data-validation-required-message="This field is required" disabled/>
                                  <div class="help-block"></div>
                              </div>
                          </div>
                      </div>
                      {{--<div class="col-6">--}}
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
        </fieldset>
        <h6>Step 2</h6>
        <fieldset>
          <div class="card repeater">
            <div class="card-content collapse show">
              <div class="card-body" data-repeater-list="payments">
                <!-- -->
                <div class="row">
                  <div class="col-3">
                    <div class="form-group mb-0">
                      <label>ID/Description/ Location</label>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="form-group mb-0">
                      <label>SWL</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group mb-0">
                      <label>Defect</label>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group mb-0">
                      <label>Recommendation/ Action</label>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group mb-0">
                      <label>Photo</label>
                    </div>
                  </div>
                  <div class=" col-1"></div>
                </div>
                <div class="row" data-repeater-item>
                  <div class="col-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <textarea name="lcr_10" class="form-control" placeholder="ID / Description / Location"></textarea>
                          <div class="help-block"></div>
                        </div>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <textarea name="lcr_11" class="form-control" placeholder="SWL"></textarea>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <textarea name="lcr_12" class="form-control" placeholder="Defect"></textarea>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <textarea name="lcr_13" class="form-control" placeholder="Recommendation/ Action"></textarea>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <div class="defect-file-field">
                              <input type="file" name="lcr_14" class="form-control-file defect-photo-input" accept="image/*">
                              <small class="text-muted defect-photo-label d-block mt-25">No file chosen</small>
                          </div>
                          <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class=" col-1">
                    <button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
                  </div>
                </div>
                <!-- -->
              </div>
              <div class=" form-group overflow-hidden">
                <div class="col-12">
                  <button type="button" data-repeater-create class="btn btn-primary"><i class="ft-plus"></i> Add</button>
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
			              $(this).find(".body:eq(" + newIndex + ") label.error").remove();
			              $(this).find(".body:eq(" + newIndex + ") .error").removeClass("error");
			          }
			          return window.validateWizardCurrentStep($(this));
			      },
			      onFinishing: function (event, currentIndex) {
			          $(this).validate().settings.ignore = ":disabled";
			          return $(this).valid();
			      },
			      onFinished: function (event, currentIndex) {

                var form1 = $('.steps-validation')[0];
                var formdata = new FormData(form1);

                formdata.append('lcr_1', $('#lcr_1 option:selected').val());
                formdata.append('code', $('#code').val());
                formdata.append('lcr_2', $('#purchaseOrder').val());

			          $.ajax({
			            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			            type: 'POST',
			            url: "{{route('defect.store')}}",
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
			                window.location.replace("{{route('defect.index')}}");
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
$('.steps-validation').on('change', 'input[type=file][name="lcr_14"]', function () {
  var fileName = (this.files && this.files.length) ? this.files[0].name : 'No file chosen';
  $(this).closest('.defect-file-field').find('.defect-photo-label').text(fileName);
});

$('.repeater').repeater({
  show: function () {
    $(this).slideDown();
    $(this).find('img').attr('src', '');
    $(this).find('.defect-photo-label').text('No file chosen');
  },
  hide: function(remove) {
    var row = $(this);
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Are You Sure ?',
        text: 'This item will be permanently removed!',
        type: 'warning',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel',
        confirmButtonClass: 'btn btn-danger',
        cancelButtonClass: 'btn btn-dark ml-1',
        buttonsStyling: false
      }).then(function(result) {
        if (result.value) {
          row.slideUp(remove);
        }
      });
      return;
    }

    row.slideUp(remove);
  },
});
</script>
@endpush
