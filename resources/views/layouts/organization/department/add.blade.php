<?php
    $emad = substr($last_id,2);
    $salma = '';
    $suzy = '';
    switch (strstr($last_id,'-',true))
    {
        case 'T':
            $salma = 'T-';
            $suzy = 'Tool';
            break;

        case 'S':
            $salma = 'S-';
            $suzy = 'Specification';
            break;

        default:
            $salma = 'D-';
            $suzy = 'Department';
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
                                        <div class="col-12 @if($suzy == 'Department') col-sm-4 @else col-sm-6 @endif">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Code</label>
                                                    <input type="text" id="code" name="code" class="form-control" placeholder="Code" required="" data-validation-required-message="This code field is required" aria-invalid="false" value="{{$salma}}{{str_pad($emad+1, 3,'0',STR_PAD_LEFT)}}" disabled>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 @if($suzy == 'Department') col-sm-4 @else col-sm-6 @endif mt-1 mt-sm-0">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Name</label>
                                                    <input type="text" name="uname" class="form-control" placeholder="{{$suzy}} Name" value="" required="" data-validation-required-message="This name field is required" aria-invalid="false">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($suzy == 'Department')
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Manager</label>
                                                    <select class="form-control" id="employee1" name="employee1" required>
                                                        <option value="">Select Value</option>
                                                        @foreach(DB::table('employees')->get() as $employee)
                                                            <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
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
										toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { window.location.reload(); }  });
								},
						});
				}
		});
</script>
@endprepend
