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
                        <h5 class="mb-1"><i class="ft-user mr-25"></i>Module Info</h5>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    @php
                                        $avatarUrl = $employee->avatar_url;
                                        $esignUrl = $employee->esign_url;
                                        $hasAvatarFile = !empty($avatarUrl);
                                        $hasEsignFile = !empty($esignUrl);
                                        $esignExtension = strtolower(pathinfo((string) $employee->esign, PATHINFO_EXTENSION));
                                        $isEsignImage = in_array($esignExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'], true);
                                    @endphp
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Code</label>
                                                    <input type="text" id="code" name="code" class="form-control" placeholder="Code" value="{{$employee->code}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Name</label>
                                                    <input type="text" name="uname" class="form-control" placeholder="Employee Name" value="{{$employee->name}}" required="" data-validation-required-message="This name field is required" aria-invalid="false">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 mt-1 mt-sm-0">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>E-mail</label>
                                                    <input type="email" name="uemail" class="form-control" placeholder="Employee Email" value="{{$employee->email}}" required="" data-validation-required-message="This email field is required" aria-invalid="false">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Telephone</label>
                                                    <input type="tel" name="utel" class="form-control" placeholder="Employee Telephone" value="{{$employee->tel}}" required="" data-validation-required-message="This telephone field is required" aria-invalid="false">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($employee->user)
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-light border mb-1">
                                                    <strong>Linked User Account:</strong>
                                                    <span class="ml-50">{{ optional($employee->user->role)->name ?: ($employee->user->is_super_admin ? 'Super Admin' : 'User') }}</span>
                                                    <span class="ml-1 badge badge-{{ $employee->user->is_active ? 'success' : 'secondary' }}">{{ $employee->user->is_active ? 'Active' : 'Inactive' }}</span>
                                                    @if(auth()->user()->can('update', $employee->user))
                                                        <a href="{{ route('user.edit', $employee->user->id) }}" class="btn btn-sm btn-outline-primary float-right">Open User Settings</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-9 mt-1 mt-sm-0">
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
                                        <div class="col-2 mt-1 mt-sm-0">
                                            @if($hasEsignFile && $isEsignImage)
                                                <img class="media-object" style="max-width: 100%; width: fit-content; height: 100%;" src="{{ $esignUrl }}" alt="" />
                                            @elseif($hasEsignFile)
                                                <a href="{{ $esignUrl }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">Open</a>
                                            @else
                                                <small class="text-muted d-block mt-1">No signature</small>
                                            @endif
                                        </div>
                                        <div class="col-1 mt-1 mt-sm-0">
                                          <button type="button" data-id="delete-esign" class="btn btn-icon btn-danger mr-1 delete-esign"><i class="la la-trash"></i></button>
                                          <input type="hidden" value="{{$employee->esign}}" id="imagedata" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-9 mt-1 mt-sm-0">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Upload Avatar</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="avatar" class="custom-file-input" id="inputGroupAvatar">
                                                        <label class="custom-file-label" style="height: 2.80rem;" for="inputGroupAvatar" aria-describedby="inputGroupAvatar">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 mt-1 mt-sm-0">
                                            @if($hasAvatarFile)
                                                <img class="media-object rounded-circle" style="max-width: 100%; width: fit-content; height: 64px;" src="{{ $avatarUrl }}" alt="" />
                                            @else
                                                <small class="text-muted d-block mt-1">No avatar</small>
                                            @endif
                                        </div>
                                        <div class="col-1 mt-1 mt-sm-0">
                                            <button type="button" data-id="delete-avatar" class="btn btn-icon btn-danger mr-1 delete-avatar"><i class="la la-trash"></i></button>
                                            <input type="hidden" value="{{ $employee->avatar }}" id="avatardata" />
                                        </div>
                                    <div class="row">
                                        <div class="col-12 mt-1">
                                            <div class="form-group mb-0">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="is_assistant" class="custom-control-input" id="is_assistant" value="1" {{ $employee->is_assistant ? 'checked' : '' }}>
                                                    <label class="custom-control-label font-weight-bold text-primary" for="is_assistant">
                                                        <i class="ft-user-check mr-50"></i>Technician
                                                    </label>
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
                                                                <input type="checkbox" class="department" name="department" required id="{{$department->id}}" data-validation-required-message="This department field is required" aria-invalid="false"
                                                                @foreach($employee->departments as $employeeDepartment)
                                                                    @if($employeeDepartment->id === $department->id)
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
                                                <input type="text" name="desc" class="form-control" placeholder="Employee Description" value="{{ $employee->desc }}" aria-invalid="false">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($employee->user)
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <h6 class="mb-1"><i class="ft-lock mr-25"></i>User Security (Merged from Profile)</h6>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>New Password</label>
                                                <input type="password" name="upassword" class="form-control" autocomplete="new-password">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Confirm Password</label>
                                                <input type="password" name="upassword_confirmation" class="form-control" autocomplete="new-password">
                                            </div>
                                        </div>
                                    </div>
                                    @if((int) auth()->id() === (int) $employee->user->id)
                                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">Open My Profile</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
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
            formdata.append('imagedata', $('#imagedata').val());
            formdata.append('avatardata', $('#avatardata').val());
						$.ajax({
								headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
								type: 'POST',
								url: "{{ route('employee.update', $employee->id) }}",
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

    $("input:checkbox[name=department]:checked").each(function() {
        department.push(this.id);
    });

    $('.department').on('ifChecked', function(event){
        var id = this.id;
        department.push(id);
    });

    $('.department').on('ifUnchecked', function(event){
        var id = this.id;
        index2 = department.indexOf(id);
        department.splice(index2, 1);
    });

    $('.steps-validation').on('click','.delete-esign', function(){
        Swal.fire({
          title: 'Are You Sure ?',
          text: "This item will be permanently deleted!",
          type: 'error',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Delete It !',
          confirmButtonClass: 'btn btn-danger',
          cancelButtonClass: 'btn btn-dark ml-1',
          cancelButtonText: 'Cancel',
          buttonsStyling: false,
        }).then(function (result) {
            if (result.value)
            {
                $('#imagedata').val("");
            }
            else if (result.dismiss === Swal.DismissReason.cancel)
            {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your data is safe :)',
                    type: 'error',
                    confirmButtonClass: 'btn btn-success',
                })
            }
        })
    });

    $('.steps-validation').on('click','.delete-avatar', function(){
        Swal.fire({
          title: 'Are You Sure ?',
          text: "This avatar will be permanently deleted!",
          type: 'error',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Delete It !',
          confirmButtonClass: 'btn btn-danger',
          cancelButtonClass: 'btn btn-dark ml-1',
          cancelButtonText: 'Cancel',
          buttonsStyling: false,
        }).then(function (result) {
            if (result.value)
            {
                $('#avatardata').val("");
            }
            else if (result.dismiss === Swal.DismissReason.cancel)
            {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your data is safe :)',
                    type: 'error',
                    confirmButtonClass: 'btn btn-success',
                })
            }
        })
    });
</script>
@endprepend
