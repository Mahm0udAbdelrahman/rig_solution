@extends('layouts.app')

@include('layouts.styles.forms')

@section('content')
<section id="validation">
	<style>
		.logo-top{width: 225px; height: 115px; margin: 25px;}
	</style>
	<div class="card">
		<div class="card-body">
			{!! Form::model($model, ['route' => ['inspectionLogo.update', $model->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
			{{ csrf_field() }}
			<div class="row m-2">
				<!-- <h3>{{$page_name}}</h3> -->
				<fieldset>
					<div class="col-12">

						<div class="form-group">
							{!! Form::label('name', 'Name') !!}
							{!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
						</div>

						<div class="form-group">
							{!! Form::label('logo', 'Logo') !!}
							{!! Form::file('logo', ['class' => 'form-control', 'onchange' => 'previewImage(this)', 'accept' => 'image/*']) !!}
							<img src="{{ asset('storage/' . $model->logo) }}" class="photo_preview logo-top" />
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
							{!! Form::label('related_inspections', 'Related Inspections') !!}
							<p>This logo will be used for all the inspections that don't have a custom logo, meaning
								that
								any inspection that is not listed here already has a custom logo and will not be
								affected by
								this setting.</p>
							<div class="row mt-2">
								@foreach($inspectionModels as $inspectionType => $list)
								<div class="form-check col-4">
									<h3>{{ $inspectionType }}</h3>
									@foreach($list as $value => $label)
									<div class="row">
										<div class="col-12 custom-control custom-checkbox">
											<input class="form-check-input" type="checkbox" name="related_inspections[]"
												value="{{ $value }}" id="related_inspections_{{ $value }}"
												{{ in_array($value, $model->related_inspections) ? 'checked' : '' }}>
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
						{!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
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

