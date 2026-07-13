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
                                <option value="">Select Value</option>
                                @foreach($jobrequests as $jobrequest)
                                    <option value="{{$jobrequest->id}}"  @if($jobrequest->id === $attached->job_request_id) selected @endif>{{$jobrequest->code}}</option>
                                @endforeach
                            </select>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Purchase Order</label>
                            <input disabled type="text" id="purchaseOrder" name="nar_2" class="form-control" placeholder="Purchase Order" value="{{$attached->nar_2}}" >
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Report No.</label>
                            <div class="clearfix">
                              <input type="text" id="precode" class="form-control pr-0" placeholder="Report No" value="{{$attached->job_request->code}} /" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled style="width: 83px; float: left;">
                              <input type="text" id="code" name="code" class="form-control pl-0" placeholder="." value="{{$attached->code}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled  style="width: auto; float: left;">
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
                  <div class="col-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Examination Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ft-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control datepicker-default" value="{{$attached->nar_4}}" id="nar_6" name="nar_6" placeholder="Examination Date" required="" data-validation-required-message="This field is required"/>
                            </div>
                        <div class="help-block"></div></div>
                    </div>
                  </div>

                  <div class="col-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Work location</label>
                            <input type="text" id="deploc" name="deploc" class="form-control" placeholder="Work location" value="" required="" data-validation-required-message="This field is required" disabled />
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
                  <div class="col-12">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Description</label>
                            <input type="text" id="nar_15" name="nar_15" class="form-control" placeholder="Description" value="{{$attached->desc}}" required="" data-validation-required-message="This field is required"/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card repeater">
            <div class="card-content collapse show">
              <div class="card-body" data-repeater-list="payments">
                <!-- -->
                <div class="row">
                  <div class="col-6">
                    <div class="form-group mb-0">
                      <label>Attachment Description</label>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group mb-0">
                      <label>Attachment Photo</label>
                    </div>
                  </div>
                  <div class=" col-1"></div>
                </div>
                @foreach(json_decode($attached->nar_6) as $value)
                <div class="row" data-repeater-item>
                  <div class="col-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <textarea id="nar_10" name="nar_10" class="form-control" placeholder="Attachment Description">{{$value->nar_10}}</textarea>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group mb-0">
                        <div class="controls">
                          <div class="custom-file">
                              <input type="file" name="nar_11" class="custom-file-input" style="height: 5.80rem;" id="nar_11">
                              <label class="custom-file-label" style="height: 5.80rem;" for="nar_11" aria-describedby="inputGroupFile02">Choose file</label>
                          </div>
                          <div class="help-block"></div></div>
                    </div>
                  </div>

                  <div class="col-2">
                      <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{Storage::url('camera/inspection/ndt/attached/')}}{{$value->nar_11}}" alt="" />
                  </div>
                  <div class="col-1 mt-1 mt-sm-0">
                      <button type="button" data-id="delete" class="btn btn-icon btn-danger mr-1 delete"><i class="la la-trash"></i></button>
                      <input type="hidden" value="{{$value->nar_11}}" name="imagedata" />
                  </div>
                  <div class=" col-1">
                      <button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
                  </div>
                </div>
                @endforeach
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
                formdata.append('nar_2', $('#purchaseOrder').val());
                formdata.append('publish', 'yes');
			          $.ajax({
			            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			            type: 'POST',
			            url: "{{route('attached.update', $attached->id)}}",
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
			                window.location.replace("{{route('attached.index')}}");
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
$('.repeater').repeater({
  show: function () {
    $(this).slideDown();
    $(this).find('img').attr('src', '');
  },
  hide: function(remove) {
    if (confirm('Are you sure you want to remove this item?')) {
      $(this).slideUp(remove);
    }
  },
});
</script>
@endpush
