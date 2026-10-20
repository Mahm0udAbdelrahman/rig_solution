@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
<section id="validation">
	<!-- <h3>{{$page_name}}</h3> -->
	<style>
		.logo-top{width: 225px; height: 115px; margin: 25px;}
	</style>
	<div class="card">
		<div class="card-body">
			{!! Form::open(['route' => 'inspectionLogo.store', 'enctype' => 'multipart/form-data']) !!}
			{{ csrf_field() }}
			<div class="row m-2">
				
				<fieldset>
					<div class="col-12">

						<div class="form-group">
							{!! Form::label('name', 'Name') !!}
							{!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
						</div>

						<div class="form-group">
							{!! Form::label('logo', 'Logo') !!}
							<p class="mb-50">Calibration certificates: shown in place of the EGAC logo. Other reports: the whole header logo.</p>
							{!! Form::file('logo', ['class' => 'form-control', 'required' => true, 'onchange' => 'previewImage(this)', 'accept' => 'image/*']) !!}
							<img src=""  class="photo_preview logo-top" />
							<script>
								function previewImage(input) {
									var reader = new FileReader();
									reader.onload = function(e) {
										$(input).siblings('img.photo_preview').attr('src', e.target.result);
									}
									reader.readAsDataURL(input.files[0]);
								}
							</script>
						</div>


						<div class="form-group">
							{!! Form::label('second_logo', 'Second Logo (optional)') !!}
							<p class="mb-50">Calibration certificates: shown in place of the Rig Solution logo. Leave empty to keep the Rig Solution logo.</p>
							{!! Form::file('second_logo', ['class' => 'form-control', 'onchange' => 'previewImage(this)', 'accept' => 'image/*']) !!}
							<img src="" class="photo_preview logo-top" />
						</div>

						<div class="form-group">
							{!! Form::label('related_inspections', 'Related Inspections') !!}
							<p>Tick the reports that use this header logo. Every report is listed; a report marked
								"(now: ...)" uses another header logo and moves to this one when ticked (a report has one header logo).
								Reports that are not ticked on any header logo use the default logo.</p>
							<div class="row mt-2">
								@foreach($inspectionModels as $inspectionType => $list)
								<div class="form-check col-4">
									<h3>{{ $inspectionType }}</h3>
									@foreach($list as $value => $label)
									<div class="row">
										<div class="col-12 custom-control custom-checkbox">
											<input class="form-check-input" type="checkbox" name="related_inspections[]"
												value="{{ $value }}" id="related_inspections_{{ $value }}">
											<label class="form-check-label" for="related_inspections_{{ $value }}">
												{{ $label }}
											</label>
										</div>
									</div>
									@endforeach
								</div>
								@endforeach
							</div>
						</div>

						<div class="form-group">
						{!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
					</div>
					</div>
				</fieldset>

			</div>
			{!! Form::close() !!}
		</div>
	</div>
</section>
@endsection

@extends('layouts.scripts.forms')

