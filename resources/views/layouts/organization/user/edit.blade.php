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
                            <h6 class="mb-1 col-md-8">Super Admin ?</h6>
                            <div class="pb-1 col-md-4 text-right controls">
                                <span class="mr-1"><label for="suporcli">Open it to make user superadmin</label></span>
                                <input type="checkbox" class="switchery" id="suporcli" name="suporcli" @if($user->is_super_admin == 1) checked @endif>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <h5 class="mb-1"><i class="ft-user mr-25"></i>Personal Info</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Select Employee</label>
                                                    <select class="form-control" id="employee1" name="employee1" required>
                                                        <option value="{{$user->employee->id}}">{{$user->employee->name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Select Role</label>
                                                    <select class="form-control" id="role" name="role" required>
                                                        <option value="">Select Value</option>
                                                        @foreach($roles as $role)
                                                            <option value="{{$role->id}}" @if($user->role_id == $role->id) selected @endif>{{$role->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Password</label>
                                                    <input type="password" name="upassword" class="form-control" name="password" placeholder="Password" value="" data-validation-required-message="This password field is required" aria-invalid="false">
                                                <div class="help-block"></div></div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 mt-1 mt-sm-0">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Confirm Password</label>
                                                    <input type="password" name="password2" placeholder="Confirm Password" data-validation-match-match="upassword" class="form-control" data-validation-required-message="This password field is required" aria-invalid="false">
                                                <div class="help-block"></div></div>
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
                formdata.append('superadmin', superadmin);
    						$.ajax({
    								headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    								type: 'POST',
    								url: "{{route('user.update', $user->id)}}",
    								processData: false,
    								contentType: false,
    								cache: false,
    								data: formdata,
    								dataType: "JSON",
    								beforeSend:function(){
    										$('#submit i').addClass('la la-refresh spinner');
    								},
    								success: function (data){
    										toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { window.location.reload(); }  });
    								},
    						});
    				}
    		});

        var superadmin = 0;

        if ($('#suporcli').is(':checked'))
    		{
    				superadmin = 1;
    		}

        $('.steps-validation').on("change", '#suporcli',function (e){
            if(this.checked == true)
            {
                superadmin = 1;
            }
            else
            {
                superadmin = 0;
            }
        });
    </script>
@endprepend
