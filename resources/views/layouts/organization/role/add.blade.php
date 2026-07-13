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
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <h5 class="mb-1"><i class="ft-user mr-25"></i>Role Info</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Name</label>
                                                    <input type="text" name="uname" class="form-control" placeholder="Role Name" value="" required="" data-validation-required-message="This name field is required" aria-invalid="false">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            $folders = ['app-flow' => ['WorkFlow', 'Persons', 'Organization', 'GeneralInfo'], 'inspection' => ['Lifting', 'Ndt', 'Tubular', 'DropObject',  'Calibration']];
                            foreach ($folders as $key => $value)
                            {
                                echo '<h5 class="ml-1 mb-2">'.ucwords(str_replace('-',' ',$key)).'</h5>';
                                foreach ($value as $key1 => $value1)
                                {
                                    echo '
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="bg-info text-white p-1 text-bold-700" style="border-radius: .45rem">'.ucwords(str_replace('-',' ',$value1)).'</h5>
                                            <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                                            <div class="heading-elements">
                                                <ul class="list-inline mb-0 white pt-1">
                                                    <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                                    <li><a data-action="close"><i class="ft-x"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-content collapse">
                                            <div class="card-body ml-2 mr-2">
                                                <div class="row">
                                                    <table class="table form-group mb-0">
                                                        <thead>
                                                            <tr class="border-solid">
                                                                <th></th>
                                                                ';
                                                                if ($key == 'inspection' || $value1 == 'WorkFlow')
                                                                {
                                                                    echo '<th>Approve</th>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<th></th>';
                                                                }
                                                                echo '
                                                                <th>All</th>
                                                                <th>Show</th>
                                                                <th>Create</th>
                                                                <th>Edit</th>
                                                                <th>Remove</th>';
                                                                if ($value1 == 'WorkFlow')
                                                                {
                                                                    echo '
                                                                    <th>Delete File</th>
                                                                    <th>Send</th>
                                                                    <th>Templates</th>
                                                                    <th>Settings</th>
                                                                    <th>Logs</th>';
                                                                }
                                                                echo '
                                                            </tr>
                                                        </thead>
                                                        <tbody class="controls">';
                                                            App\Http\Controllers\CustomController::get_classes(ucfirst($key), ucwords(str_replace('-',' ',$value1)), '', '');
                                                        echo '</tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                }
                            }
                        ?>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.scripts.forms')

@extends('layouts.scripts.role')

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
						formdata.append('permissions', JSON.stringify(permissions));
						$.ajax({
								headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
								type: 'POST',
								url: "{{route('role.store')}}",
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
