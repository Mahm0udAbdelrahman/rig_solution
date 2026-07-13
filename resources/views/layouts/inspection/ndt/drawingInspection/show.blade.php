@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
<style>
	#reportImageDiv {
		height: 75em;
	}

	#reportImageDiv img {
		width: 100%;
		height: 100%;
	}

	.logo-top {
		margin-top: 0;
		margin-left: 1rem;
		height: 75%;
		width: 95%;
	}

	.donw {
		min-width: fit-content !important;
		height: 1450px !important;
		/*overflow-x: scroll;*/
	}
</style>

<input type="hidden" id="page_mode" value="l">

<!--  Inspection Header -->
<div class="row header-top" style="padding-top: 5px; margin-bottom: 3px;">
	<div class="col-2 pl-0 border-dark" style="height: 132px;">
		@if ($model->inspection_logo)
			<img src="{{ asset('storage/' . $model->inspection_logo) }}" class="logo-top" />
		@else
			<img src="{{asset('app-assets/images/logo/combined-logo.png')}}" class="logo-top" />
		@endif
	</div>
	<div class="col-10 pl-0 pr-0">
		<div class="row" style="margin: 0; min-height: 44px;">
			<div class="col-2 p-0 border-dark pl-1 align-content-center">
				<h6 class="white text-bold-600 mb-0">Client Name</h6>
			</div>
			<div class="col-4 p-0 border-dark pl-1 text-16 black align-content-center">
				{{$model->job_request->client->name}}
			</div>

			<div class="col-2 p-0 border-dark pl-1 align-content-center">
				<h6 class="white text-bold-600 mb-0">Work Location</h6>
			</div>
			<div class="col-4 p-0 border-dark pl-1 text-16 black align-content-center">
				{{$model->job_request->clientDepartment ?
				$model->job_request->clientDepartment->name . ' / '.$model->job_request->deploc
				: $model->job_request->deploc}}
			</div>
		</div>

		<div class="row" style="margin: 0; min-height: 44px;">
			<div class="col-2 p-0 border-dark pl-1 align-content-center">
				<h6 class="white text-bold-600 mb-0">Description</h6>
			</div>
			<div class="col-6 p-0 border-dark pl-1 text-16 black align-content-center">
				{{$model->description}}
			</div>

			<div class="col-2 p-0 border-dark pl-1 align-content-center">
				<h6 class="white text-bold-600 mb-0">Drawing No</h6>
			</div>
			<div class="col-2 p-0 border-dark pl-1 text-16 black align-content-center" data-type="code" data-id="{{$model->id}}">{{$code}}</div>
		</div>

		<div class="row" style="margin: 0; min-height: 44px;">
			<div class="col-2 p-0 border-dark pl-1 align-content-center">
				<h6 class="white text-bold-600 mb-0">Dimension</h6>
			</div>
			<div class="col-2 p-0 border-dark pl-1 text-16 black align-content-center">
				{{$model->dimension}}
			</div>
			<div class="col-2 p-0 border-dark pl-1 align-content-center">
				<h6 class="white text-bold-600 mb-0">Pressure</h6>
			</div>
			<div class="col-2 p-0 border-dark pl-1 text-16 black align-content-center">
				{{$model->pressure}}
			</div>
			<div class="col-2 p-0 border-dark pl-1 align-content-center">
				<h6 class="white text-bold-600 mb-0">Identification No</h6>
			</div>
			<div class="col-2 p-0 border-dark pl-1 text-16 black align-content-center">{{$model->identification_no}}</div>
		</div>

	</div>
</div>
<!--  -->
<!----------------------------------->
<div class="row" style="margin-top: 3px;">
	<!-- <div class="col-12 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0">Report</h6>
			</div> -->
	<div class="col-12 border-dark m-0 p-0" id="reportImageDiv">
		<img src="{{isset($model) && $model->report_image ? Storage::url('camera/inspection/ndt/drawinginspection/').$model->report_image : '#'}}"
			alt="report Image">
	</div>
</div>

<!----------------------------------->
<!-- <div class="row" style="margin-top: 3px;">
        <div class="col-12 bg-dark p-0 border-dark">
            <h6 class="white text-bold-600 pl-1 mb-0">Comments</h6>
        </div>
        <div class="col-12 border-dark m-0 p-0">
            <div class="row" style="padding: 5px 20px; min-height: 7em;">
                <div class="col-12 p-0 black" style="padding: 2px;">
                    <p style="margin-left: 5px;">{{$model->comment}}</p>
                </div>
            </div>
        </div>
    </div> -->
<div class="row">
	<div class="col-1 bg-dark p-0 border-dark tex" style="align-content: center;">
		<h6 class="white text-bold-600 pl-1 mb-0">Comments</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black" style="align-content: center;"> {{$model->comment}}</div>

	<div class="bg-dark col-1 p-0 border-dark" style="align-content: center;">
		<h6 class="white text-bold-600 pl-1 mb-0">Date</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black" style="align-content: center;">{{$model->examination_date}}
	</div>

	<div class="bg-dark col-1 p-0 border-dark" style="align-content: center;">
		<h6 class="white text-bold-600 pl-1 mb-0">Inspector</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black" style="align-content: center;">{{$person_make_report}}</div>

	<div class="bg-dark col-1 p-0 border-dark" style="align-content: center;">
		<h6 class="white text-bold-600 pl-1 mb-0">Sign</h6>
	</div>
	<div class="col-2 p-0 pl-1 border-dark text-16 black" style="align-content: center;">
		<img class="media-object" style="width: 105px; height: 36px; margin: auto;"
			src="{{Storage::url('employees/')}}{{$esign}}" alt="" />
	</div>

</div>
<!----------------------------------->
<!----------------------------------->
<!-- @include('layouts.styles.reportfooter-v2') -->
@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $model->report->id])
@endpush
