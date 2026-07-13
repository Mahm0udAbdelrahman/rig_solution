<?php
    $salma = '';
    $suzy = '';
    switch (strstr($last_id,'-',true))
    {
        case 'CLI':
            $salma = 'CLI-';
            $suzy = 'Client';
            break;

        default:
            $salma = 'SUP-';
            $suzy = 'Supplier';
            break;
    }
?>

@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
    <section id="validation">
        <div class="row">
            <div class="col-12">
                <form action="#" class="steps-validation wizard-circle">
                    <h6>Step 1</h6>
                    <fieldset>
                        <h5 class="mb-1"><i class="ft-user mr-25"></i>Module Info</h5>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>{{$suzy}} Code</label>
                                                    <div class="clearfix">
                                                      <input type="text" id="code" name="code" class="form-control" placeholder="Code" value="{{$salma}}{{str_pad(substr($last_id,4)+1, 3,'0',STR_PAD_LEFT)}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>{{$suzy}} E-mail</label>
                                                    <input type="email" name="uemail" class="form-control" placeholder="{{$suzy}} Email" value="">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>{{$suzy}} Fax</label>
                                                    <input type="text" name="fax" class="form-control" placeholder="{{$suzy}} fax" value="" >
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>{{$suzy}} Password</label>
                                                    <input type="password" name="upassword" class="form-control" name="password" placeholder="Password" value="" required="" data-validation-required-message="This password field is required" aria-invalid="false">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12 col-sm-6 mt-1 mt-sm-0">
                                          <div class="form-group">
                                              <div class="controls">
                                                  <label>{{$suzy}} Name</label>
                                                  <input type="text" name="uname" class="form-control" placeholder="{{$suzy}} name" value="" required="" data-validation-required-message="This name field is required" aria-invalid="false">
                                                  <div class="help-block"></div>
                                              </div>
                                          </div>

                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>{{$suzy}} Telephone</label>
                                                    <input type="tel" name="utel" class="form-control" placeholder="{{$suzy}} Telephone" value="">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>{{$suzy}} Tax Card</label>
                                                    <input type="text" name="tax_card" class="form-control" placeholder="{{$suzy}} tax card" value="" >
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Confirm Password</label>
                                                    <input type="password" name="password2" placeholder="Confirm Password" data-validation-match-match="upassword" class="form-control" required="" data-validation-required-message="This password field is required" aria-invalid="false">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <h5 class="mb-1"><i class="ft-user mr-25"></i>Additional Info</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Location</label>
                                                <input type="text" name="address" class="form-control" placeholder="{{$suzy}} Address" aria-invalid="false">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>WebSite Url</label>
                                                    <input type="url" class="form-control" name="url" id="url" placeholder="WebSite Url" >
                                                <div class="help-block"></div></div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 mt-1 mt-sm-0">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Upload Logo</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="logo" class="custom-file-input" id="inputGroupFile02">
                                                        <label class="custom-file-label" style="height: 2.80rem;" for="inputGroupFile02" aria-describedby="inputGroupFile02">Choose file</label>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Desc.</label>
                                                <input type="text" name="desc" class="form-control" placeholder="{{$suzy}} Description" aria-invalid="false">
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
						var form1 = $('.wizard')[0];
						var formdata = new FormData(form1);
						formdata.append('code', $('#code').val());
						$.ajax({
								headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
								type: 'POST',
								url: "{{route($route)}}",
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

                      // window.location.reload();
                      window.location.replace("{{route('client.index')}}");


                    }  });
								},
						});
				}
		});
</script>
@endprepend
