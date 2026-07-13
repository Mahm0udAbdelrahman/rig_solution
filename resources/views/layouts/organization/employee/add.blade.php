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
                                                    <label>Code</label>
                                                    <input type="text" id="code" name="code" class="form-control" placeholder="Code" value="EMP-{{str_pad(substr($last_id,4)+1, 3,'0',STR_PAD_LEFT)}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Name</label>
                                                    <input type="text" name="uname" class="form-control" placeholder="Employee Name" value="" required="" data-validation-required-message="This name field is required" aria-invalid="false">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 mt-1 mt-sm-0">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>E-mail</label>
                                                    <input type="email" name="uemail" class="form-control" placeholder="Employee Email" value="" required="" data-validation-required-message="This email field is required" aria-invalid="false">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Telephone</label>
                                                    <input type="tel" name="utel" class="form-control" placeholder="Employee Telephone" value="" required="" data-validation-required-message="This telephone field is required" aria-invalid="false">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-1 mt-sm-0">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Upload E-Signture</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="logo" class="custom-file-input" id="inputGroupFile02">
                                                        <label class="custom-file-label" style="height: 2.80rem;" for="inputGroupFile02" aria-describedby="inputGroupFile02">Choose file</label>
                                                    </div>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Departments</h6>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    @if(App\Models\Organization\Department::count() != 0)
                                        <div class="skin skin-square form-group mt-1">
                                            <div class="controls">
                                                <div class="row">
                                                    @foreach(DB::table('departments')->select('id', 'name')->get() as $department)
                                                        <div class="col-md-3 col-sm-12">
                                                            <fieldset>
                                                                <input type="checkbox" class="department" name="department" required id="{{$department->id}}" data-validation-required-message="This department field is required" aria-invalid="false">
                                                                <label for="{{$department->id}}">{{$department->name}}</label>
                                                            </fieldset>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <h1 class="my-0">You Don't Have Any Departments !</h1>
                                                <p class="card-text my-0">Click On This Button Below To Create One.</p>
                                                <br>
                                                <a href="{{route('department.create')}}" class="btn btn-info clear waves-effect waves-light"><i class="la la-plus"></i> Create New Department</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Desc.</label>
                                                <input type="text" name="desc" class="form-control" placeholder="Employee Description" aria-invalid="false">
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
            formdata.append('department', JSON.stringify(department));
            formdata.append('code', $('#code').val());
						$.ajax({
								headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
								type: 'POST',
								url: "{{route('employee.store')}}",
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

    var department = [];
    $('.department').on('ifChecked', function(event){
        var id = this.id;
        department.push(id);
    });

    $('.department').on('ifUnchecked', function(event){
        var id = this.id;
        index2 = department.indexOf(id);
        department.splice(index2, 1);
    });
</script>
@endprepend
