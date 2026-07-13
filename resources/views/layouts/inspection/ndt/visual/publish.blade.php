@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
<section id="form-repeater">
  <div class="row">
    <div class="col-12">
      <form action="#" class="steps-validation wizard-circle form emad repeater-emad"  novalidate enctype="multipart/form-data">
        @method('PUT')
        <!----------------------------------------------->
        <h6>Step 1</h6>
        <fieldset>
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row ">
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>JCF Number</label>
                            <select class="form-control" id="lcr_1" name="lcr_1" required="" >
                                <option value="">Select Value</option>
                                @foreach($jobrequests as $jobrequest)
                                    <option value="{{$jobrequest->id}}"  @if($jobrequest->id == $visual->job_request_id) selected @endif>{{$jobrequest->code}}</option>
                                @endforeach
                            </select>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Purchase Order</label>
                            <input disabled type="text" id="purchaseOrder" name="nvr_2" class="form-control" placeholder="Purchase Order" value="{{$visual->nvr_2}}" >
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Report No.</label>
                            <div class="clearfix">
                              <input type="text" id="precode" class="form-control pr-0" placeholder="Report No" value="{{$visual->job_request->code}} /" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled style="width: 83px; float: left;">
                              <input type="text" id="nvr_3" name="nvr_3" class="form-control pl-0" placeholder="." value="{{$visual->code}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled  style="width: auto; float: left;">
                            </div>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                      <div class="controls">
                        <label>Name of employer for whom the examination was made</label>
                        <input type="text" placeholder="Name of employer for whom the examination was made" class="form-control" id="nvr_4" name="nvr_4" value="{{$visual->job_request->client->name}}" disabled>
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                      <div class="controls">
                        <label>Address of premises at examination was made</label>
                        <input type="text" id="nvr_5" name="nvr_5" class="form-control" value="{{$visual->job_request->client->location}}" placeholder="Address of premises at examination was made"  disabled>
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
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
                                <input type="text" class="form-control datepicker-default" id="nvr_6" name="nvr_6" placeholder="Examination Date" value="{{$visual->nvr_4}}" required/>
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
          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Description</label>
                            <input type="text" id="nvr_8" name="nvr_8" class="form-control" placeholder="Description" value="{{$visual->desc}}" required/>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </fieldset>
        <!----------------------------------------------->
        <h6>Step 2</h6>
        <fieldset>

          <!-------------------------------------------------------------------------------->
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="row ">
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Identification No</label>
                            <input type="text" id="nvr_9" name="nvr_9" class="form-control" placeholder="Identification No" value="{{$visual->nvr_6}}" required>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Ref. Standard</label>
                            <input type="text" id="nvr_10" name="nvr_10" class="form-control" placeholder="Ref. Standard" value="{{$visual->nvr_7}}" required>
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
                <div class="row ">
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Material</label>
                            <input type="text" id="nvr_11" name="nvr_11" class="form-control" placeholder="Material" value="{{$visual->nvr_8}}" required>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Instrument Used</label>
                            <input type="text" id="nvr_12" name="nvr_12" class="form-control" placeholder="Instrument Used" value="{{$visual->nvr_9}}" required>
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
                <div class="row ">
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Material Thickness</label>
                            <input type="text" id="nvr_13" name="nvr_13" class="form-control" placeholder="Material Thickness" value="{{$visual->nvr_10}}" required>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Light Intensity</label>
                            <input type="text" id="nvr_14" name="nvr_14" class="form-control" placeholder="Light Intensity" value="{{$visual->nvr_11}}" required>
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
                <div class="row ">
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Welding Process</label>
                            <input type="text" id="nvr_15" name="nvr_15" class="form-control" placeholder="Welding Process" value="{{$visual->nvr_12}}" required>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Light Source</label>
                            <input type="text" id="nvr_16" name="nvr_16" class="form-control" placeholder="Light Source" value="{{$visual->nvr_13}}" required>
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
                <div class="row ">
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Type of Joint</label>
                            <input type="text" id="nvr_17" name="nvr_17" class="form-control" placeholder="Type of Joint" value="{{$visual->nvr_14}}" required>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Location</label>
                            <input type="text" id="nvr_18" name="nvr_18" class="form-control" placeholder="Location" value="{{$visual->nvr_15}}" required>
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
                <div class="row ">
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Surface Condition</label>
                            <input type="text" id="nvr_19" name="nvr_19" class="form-control" placeholder="Surface Condition" value="{{$visual->nvr_16}}" required>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Acceptance Standard</label>
                            <input type="text" id="nvr_20" name="nvr_20" class="form-control" placeholder="Acceptance Standard" value="{{$visual->acceptance}}" required>
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
                <div class="row ">
                  <div class="col-12">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <label>Stage of Exam</label>
                            <input type="text" id="nvr_21" name="nvr_21" class="form-control" placeholder="Stage of Exam" value="{{$visual->nvr_18}}" required>
                        <div class="help-block"></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-------------------------------------------------------------------------------->
        </fieldset>
        <!----------------------------------------------->
        <h6>Step 3</h6>
        <fieldset>
        <div class="card repeater">
          <div class="card-content collapse show">
            <div class="card-body" data-repeater-list="payments">
              <label>Visual Report Photos And Desc.</label>
              @foreach(json_decode($visual->nvr_19) as $key => $value)
              <div data-repeater-item>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group mb-0">
                        <div class="controls">
                            <textarea name="nvr_22" class="form-control" placeholder="">{{$value->nvr_22}}</textarea>
                            <div class="help-block"></div>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-1">
                <div class="col-2">
                    @php
                      $storedPhoto = trim((string) ($value->nvr_23 ?? ''));
                      $photoPath = $storedPhoto === ''
                          ? null
                          : (\Illuminate\Support\Str::startsWith($storedPhoto, 'camera/inspection/ndt/visual/')
                              ? ltrim($storedPhoto, '/')
                              : 'camera/inspection/ndt/visual/'.ltrim($storedPhoto, '/'));
                      $photoExists = $photoPath && is_file(public_path('storage/'.$photoPath));
                    @endphp
                    @if($photoExists)
                      <img class="media-object" src="{{ asset('storage/'.$photoPath) }}" alt="" width="64">
                    @else
                      <div class="text-muted small pt-2">No image</div>
                    @endif
                    <input type=hidden name="nvr_230" value="{{$value->nvr_23 ?? ''}}" />
                  </div>
                  <div class="col-9">
                    <div class="form-group mb-0">
                      <div class="controls">
                        <div class="custom-file">
                          <input type="file" name="nvr_23" class="custom-file-input">
                          <label class="custom-file-label" style="height: 2.80rem;" for="nvr_23" aria-describedby="inputGroupFile02">Choose file</label>
                        </div>
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                  <div class=" col-1">
                    <button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
                  </div>
                </div>
              </div>
              @endforeach

            </div>
            <div class=" form-group overflow-hidden">
              <div class="col-12">
                <button type="button" data-repeater-create class="btn btn-primary"><i class="ft-plus"></i> Add</button>
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
                                      <!-- <textarea id="nvr_26" name="nvr_26" class="form-control" placeholder="Final Conclusion"></textarea> -->
                                      <div class="row">

                                          <div class="col-8">
                                              <p>NDT result : Accept / Reject ?</p>
                                          </div>
                                          <div class="col-2">
                                              <div class="custom-control custom-radio">
                                                  <input type="radio" class="custom-control-input" id="nvr_26_y " name="nvr_26" {{$visual->checkbox_yes($visual->nvr_23)}}  required>
                                                  <label class="custom-control-label" for="nvr_26_y ">Accept</label>
                                              </div>
                                          </div>
                                          <div class="col-2">
                                              <div class="custom-control custom-radio">
                                                  <input type="radio" class="custom-control-input" id="nvr_26_n" name="nvr_26" {{$visual->checkbox_no($visual->nvr_23)}}>
                                                  <label class="custom-control-label" for="nvr_26_n">Reject</label>
                                              </div>
                                          </div>


                                    </div>

                                  </div>
                              </div>

                  </div>
              </div>
          </div>
        </fieldset>
        <!----------------------------------------------->
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
                formdata.append('code', $('#code').val());
                    formdata.append('nvr_2', $('#purchaseOrder').val());
			          $.each(checkbox, function(key, val) {
			              if($(this).is(':checked') === true){
			                	formdata.append($(this).attr('name'), $(this).attr('id'));
			              }
			          });
                formdata.append('publish', 'yes');
			          $.ajax({
			            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			            type: 'POST',
                  url: "{{route('visual.update', $visual->id)}}",
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
			                window.location.replace("{{route('visual.index')}}");
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
