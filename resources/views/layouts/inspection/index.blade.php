@extends('layouts.app')
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/weather-icons/climacons.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/toastr.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('app-assets/vendors/css/tables/extensions/fixedHeader.dataTables.min.css')}}">
@endsection
@section('header-bottom')
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/colors/palette-climacon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/toastr.css')}}">
    <style>
        .list-group-item {
            min-height: inherit;
        }
    </style>
@endsection
@section('content')
    <section id="weather-cards">
        <div class="row">
            <div class="col-xl-4 col-md-12 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body bg-blue-grey bg-lighten-2 rounded-top">
                            <div class="animated-weather-icons text-center">
                                <svg version="1.1" id="cloudHailAlt1"
                                     class="climacon climacon_cloudHailAlt climacon-blue-grey climacon-darken-2 height-200"
                                     viewBox="15 15 70 70">
                                    <g class="climacon_iconWrap climacon_iconWrap-cloudHailAlt">
                                        <g class="climacon_wrapperComponent climacon_wrapperComponent-hailAlt">
                                            <g class="climacon_component climacon_component-stroke climacon_component-stroke_hailAlt climacon_component-stroke_hailAlt-left">
                                                <circle cx="42" cy="65.498" r="2"></circle>
                                            </g>
                                            <g class="climacon_component climacon_component-stroke climacon_component-stroke_hailAlt climacon_component-stroke_hailAlt-middle">
                                                <circle cx="49.999" cy="65.498" r="2"></circle>
                                            </g>
                                            <g class="climacon_component climacon_component-stroke climacon_component-stroke_hailAlt climacon_component-stroke_hailAlt-right">
                                                <circle cx="57.998" cy="65.498" r="2"></circle>
                                            </g>
                                            <g class="climacon_component climacon_component-stroke climacon_component-stroke_hailAlt climacon_component-stroke_hailAlt-left">
                                                <circle cx="42" cy="65.498" r="2"></circle>
                                            </g>
                                            <g class="climacon_component climacon_component-stroke climacon_component-stroke_hailAlt climacon_component-stroke_hailAlt-middle">
                                                <circle cx="49.999" cy="65.498" r="2"></circle>
                                            </g>
                                            <g class="climacon_component climacon_component-stroke climacon_component-stroke_hailAlt climacon_component-stroke_hailAlt-right">
                                                <circle cx="57.998" cy="65.498" r="2"></circle>
                                            </g>
                                        </g>
                                        <g class="climacon_wrapperComponent climacon_wrapperComponent-cloud">
                                            <path class="climacon_component climacon_component-stroke climacon_component-stroke_cloud"
                                                  d="M63.999,64.941v-4.381c2.39-1.384,3.999-3.961,3.999-6.92c0-4.417-3.581-8-7.998-8c-1.602,0-3.084,0.48-4.334,1.291c-1.23-5.317-5.974-9.29-11.665-9.29c-6.626,0-11.998,5.372-11.998,11.998c0,3.549,1.55,6.728,3.999,8.924v4.916c-4.776-2.768-7.998-7.922-7.998-13.84c0-8.835,7.162-15.997,15.997-15.997c6.004,0,11.229,3.311,13.966,8.203c0.663-0.113,1.336-0.205,2.033-0.205c6.626,0,11.998,5.372,11.998,12C71.998,58.863,68.656,63.293,63.999,64.941z"></path>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="weather-details text-center">
                                <span class="mt-2 block blue-grey darken-3">Reports</span>
                                <span class="font-medium-4 text-bold-500 blue-grey darken-4">Lifting, Inspection</span>
                            </div>
                        </div>
                        <div class="card-footer border-0">
                            <ul class="list-group">
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Lifting\Crane')
                                            <a class="col-10 pt-1 pb-1" class="col-10" href="{{route('crane.index')}}"
                                               class="text-bold-500 blue-grey darken-4">01 - Lifting Crane Report</a>
                                            @can('create', 'App\Models\Inspection\Lifting\Crane')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('crane.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>

                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Lifting\OverheadCrane')
                                            <a class="col-10 pt-1 pb-1" href="{{route('overheadCrane.index')}}"
                                               class="text-bold-500 blue-grey darken-4">02 - Lifting Overhead Crane
                                                Report</a>
                                            @can('create', 'App\Models\Inspection\Lifting\OverheadCrane')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('overheadCrane.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>

                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
										@can('viewAny', 'App\Models\Inspection\Lifting\Forklift')
											<a class="col-10 pt-1 pb-1" href="{{route('forklift.index')}}"
											class="text-bold-500 blue-grey darken-4">03 - Lifting Forklift Report</a>
											@can('create', 'App\Models\Inspection\Lifting\Forklift')
												<a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
												href="{{route('forklift.create')}}"><i class="la la-plus-square"></i></a>
											@endcan
										@endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
										@can('viewAny', 'App\Models\Inspection\Lifting\ThroughExamination')
											<a class="col-10 pt-1 pb-1" href="{{route('throughExamination.index')}}"
											class="text-bold-500 blue-grey darken-4">04 - Lifting Through Examination
												Report</a>
											@can('create', 'App\Models\Inspection\Lifting\ThroughExamination')
												<a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
												href="{{route('throughExamination.create')}}"><i
														class="la la-plus-square"></i></a>
											@endcan
										@endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
										@can('viewAny', 'App\Models\Inspection\Lifting\Defect')
										   <a class="col-10 pt-1 pb-1" href="{{route('defect.index')}}"
										   class="text-bold-500 blue-grey darken-4">05 - Lifting Defect Report
											   Report</a>
										   @can('create', 'App\Models\Inspection\Lifting\Defect')
											   <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
											   href="{{route('defect.create')}}"><i
													   class="la la-plus-square"></i></a>
										   @endcan
									   @endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
										@can('viewAny', 'App\Models\Inspection\Lifting\Lregister')
										   <a class="col-10 pt-1 pb-1" href="{{route('lregister.index')}}"
										   class="text-bold-500 blue-grey darken-4">06 - Lifting Register Report
											   Report</a>
										   @can('create', 'App\Models\Inspection\Lifting\Lregister')
											   <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
											   href="{{route('lregister.create')}}"><i
													   class="la la-plus-square"></i></a>
										   @endcan
									   @endcan
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
				<div class="card">
					<div class="card-content">
						<div class="card-body bg-light bg-lighten-2 rounded-top">
                            <div class="weather-details text-center">
                                <span class="font-medium-4 text-bold-500 blue-grey darken-4">Drop Object Inspection</span>
                            </div>
                        </div>
						<div class="card-footer border-0">
                            <ul class="list-group">
								<li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\DropObject\DropObject')
                                            <a class="col-10 pt-1 pb-1" class="col-10" href="{{route('dropObject.index')}}"
                                               class="text-bold-500 blue-grey darken-4">01 - Drop Object Survey</a>
                                            @can('create', 'App\Models\Inspection\DropObject\DropObject')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('dropObject.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
							</ul>
						</div>
					</div>
				</div>
            </div>
            <div class="col-xl-4 col-md-12 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body bg-amber bg-lighten-4 rounded-top">
                            <div class="animated-weather-icons text-center">
                                <svg version="1.1" id="wind1"
                                     class="climacon climacon_wind climacon-amber climacon-darken-2 height-200"
                                     viewBox="15 15 70 70">
                                    <g class="climacon_iconWrap climacon_iconWrap-wind">
                                        <g class="climacon_wrapperComponent climacon_componentWrap-wind">
                                            <path class="climacon_component climacon_component-stroke climacon_component-wind climacon_component-wind_curl"
                                                  d="M65.999,52L65.999,52h-3c-1.104,0-2-0.895-2-1.999c0-1.104,0.896-2,2-2h3c1.104,0,2-0.896,2-1.999c0-1.105-0.896-2-2-2s-2-0.896-2-2s0.896-2,2-2c0.138,0,0.271,0.014,0.401,0.041c3.121,0.211,5.597,2.783,5.597,5.959C71.997,49.314,69.312,52,65.999,52z"/>
                                            <path class="climacon_component climacon_component-stroke climacon_component-wind"
                                                  d="M55.999,48.001h-2h-6.998H34.002c-1.104,0-1.999,0.896-1.999,2c0,1.104,0.895,1.999,1.999,1.999h2h3.999h3h4h3h3.998h2c3.313,0,6,2.688,6,6c0,3.176-2.476,5.748-5.597,5.959C56.271,63.986,56.139,64,55.999,64c-1.104,0-2-0.896-2-2c0-1.105,0.896-2,2-2s2-0.896,2-2s-0.896-2-2-2h-2h-3.998h-3h-4h-3h-3.999h-2c-3.313,0-5.999-2.686-5.999-5.999c0-3.175,2.475-5.747,5.596-5.959c0.131-0.026,0.266-0.04,0.403-0.04l0,0h12.999h6.998h2c1.104,0,2-0.896,2-2s-0.896-2-2-2s-2-0.895-2-2c0-1.104,0.896-2,2-2c0.14,0,0.272,0.015,0.403,0.041c3.121,0.211,5.597,2.783,5.597,5.959C61.999,45.314,59.312,48.001,55.999,48.001z"/>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="weather-details text-center">
                                <span class="mt-2 block amber darken-2">Reports</span>
                                <span class="font-medium-4 text-bold-500 amber darken-4">NDT, Inspection</span>
                            </div>
                        </div>
                        <div class="card-footer border-0">
                            <ul class="list-group">
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Ndt\Mpipt')
                                            <a class="col-10 pt-1 pb-1" href="{{route('mpipt.index')}}"
                                               class="text-bold-500 amber darken-4">01 - NDT MPI-PT Report</a>
                                            @can('create', 'App\Models\Inspection\Ndt\Mpipt')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('mpipt.create')}}"><i class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Ndt\Visual')
                                            <a class="col-10 pt-1 pb-1" href="{{route('visual.index')}}"
                                               class="text-bold-500 amber darken-4">02 - NDT Visual Report</a>
                                            @can('create', 'App\Models\Inspection\Ndt\Visual')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('visual.create')}}"><i class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Ndt\Ultrasonic')
                                            <a class="col-10 pt-1 pb-1" href="{{route('ultrasonic.index')}}"
                                               class="text-bold-500 amber darken-4">03 - NDT UT Shear Wave Report</a>
                                            @can('create', 'App\Models\Inspection\Ndt\Ultrasonic')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('ultrasonic.create')}}"><i class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Ndt\Summary')
                                            <a class="col-10 pt-1 pb-1" href="{{route('summary.index')}}"
                                               class="text-bold-500 amber darken-4">04 - NDT Summary Report</a>
                                            @can('create', 'App\Models\Inspection\Ndt\Summary')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('summary.create')}}"><i class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Ndt\Attached')
                                            <a class="col-10 pt-1 pb-1" href="{{route('attached.index')}}"
                                               class="text-bold-500 amber darken-4">05 - NDT Attach Report</a>
                                            @can('create', 'App\Models\Inspection\Ndt\Attached')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('attached.create')}}"><i class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Ndt\High3Pressure')
                                            <a class="col-10 pt-1 pb-1" href="{{route('high3Pressure.index')}}"
                                               class="text-bold-500 amber darken-4">06 - NDT High Pressure UT Report</a>
                                            @can('create', 'App\Models\Inspection\Ndt\High3Pressure')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('high3Pressure.create')}}"><i
                                                        class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Ndt\HighPressure')
                                            <a class="col-10 pt-1 pb-1" href="{{route('highPressure.index')}}"
                                               class="text-bold-500 amber darken-4">07 - NDT UTWT Report</a>
                                            @can('create', 'App\Models\Inspection\Ndt\HighPressure')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('highPressure.create')}}"><i class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Ndt\High2Pressure')
                                            <a class="col-10 pt-1 pb-1" href="{{route('high2Pressure.index')}}"
                                               class="text-bold-500 amber darken-4">08 - NDT General UTWT Report</a>
                                            @can('create', 'App\Models\Inspection\Ndt\High2Pressure')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('high2Pressure.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Ndt\WitnessHydro')
                                            <a class="col-10 pt-1 pb-1" href="{{route('witnessHydro.index')}}"
                                               class="text-bold-500 amber darken-4">09 - NDT Witness Hydro Test
                                                Report</a>
                                            @can('create', 'App\Models\Inspection\Ndt\WitnessHydro')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('witnessHydro.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Ndt\TreatingIron')
                                            <a class="col-10 pt-1 pb-1" href="{{route('treatingIron.index')}}"
                                               class="text-bold-500 amber darken-4">10 - NDT Treating Iron Inspection
                                                Report</a>
                                            @can('create', 'App\Models\Inspection\Ndt\TreatingIron')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('treatingIron.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Ndt\DrawingInspection')
                                            <a class="col-10 pt-1 pb-1" href="{{route('drawingInspection.index')}}"
                                               class="text-bold-500 amber darken-4">11 - NDT Drawing Report</a>
                                            @can('create', 'App\Models\Inspection\Ndt\DrawingInspection')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('drawingInspection.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Ndt\Nregister')
                                            <a class="col-10 pt-1 pb-1" href="{{route('nregister.index')}}"
                                               class="text-bold-500 amber darken-4">12 - NDT Register Report</a>
                                            @can('create', 'App\Models\Inspection\Ndt\Nregister')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('nregister.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-12 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body bg-blue bg-lighten-4 rounded-top">
                            <div class="animated-weather-icons text-center">
                                <svg version="1.1" id="cloudDrizzleAlt1"
                                     class="climacon climacon_cloudDrizzleAlt climacon-blue climacon-darken-2 height-200"
                                     viewBox="15 15 70 70">
                                    <g class="climacon_iconWrap climacon_iconWrap-cloudDrizzleAlt">
                                        <g class="climacon_wrapperComponent climacon_wrapperComponent-drizzle">
                                            <path class="climacon_component climacon_component-stroke climacon_component-stroke_drizzle climacon_component-stroke_drizzle-left"
                                                  id="Drizzle-Left_1_1"
                                                  d="M56.969,57.672l-2.121,2.121c-1.172,1.172-1.172,3.072,0,4.242c1.17,1.172,3.07,1.172,4.24,0c1.172-1.17,1.172-3.07,0-4.242L56.969,57.672z"></path>
                                            <path class="climacon_component climacon_component-stroke climacon_component-stroke_drizzle climacon_component-stroke_drizzle-middle"
                                                  d="M50.088,57.672l-2.119,2.121c-1.174,1.172-1.174,3.07,0,4.242c1.17,1.172,3.068,1.172,4.24,0s1.172-3.07,0-4.242L50.088,57.672z"></path>
                                            <path class="climacon_component climacon_component-stroke climacon_component-stroke_drizzle climacon_component-stroke_drizzle-right"
                                                  d="M43.033,57.672l-2.121,2.121c-1.172,1.172-1.172,3.07,0,4.242s3.07,1.172,4.244,0c1.172-1.172,1.172-3.07,0-4.242L43.033,57.672z"></path>
                                        </g>
                                        <g class="climacon_wrapperComponent climacon_wrapperComponent-cloud">
                                            <path class="climacon_component climacon_component-stroke climacon_component-stroke_cloud"
                                                  d="M59.943,41.642c-0.696,0-1.369,0.092-2.033,0.205c-2.736-4.892-7.961-8.203-13.965-8.203c-8.835,0-15.998,7.162-15.998,15.997c0,5.992,3.3,11.207,8.177,13.947c0.276-1.262,0.892-2.465,1.873-3.445l0.057-0.057c-3.644-2.061-6.106-5.963-6.106-10.445c0-6.626,5.372-11.998,11.998-11.998c5.691,0,10.433,3.974,11.666,9.29c1.25-0.81,2.732-1.291,4.332-1.291c4.418,0,8,3.581,8,7.999c0,3.443-2.182,6.371-5.235,7.498c0.788,1.146,1.194,2.471,1.222,3.807c4.666-1.645,8.014-6.077,8.014-11.305C71.941,47.014,66.57,41.642,59.943,41.642z"></path>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="weather-details text-center">
                                <span class="mt-2 block blue darken-2">Reports</span>
                                <span class="font-medium-4 text-bold-500 blue darken-4">Tubular, Inspection</span>
                            </div>

                        </div>
                        <div class="card-footer border-0">
                            <ul class="list-group">
                                <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Tubular\PipesSummaryReport')
                                            <a class="col-10 pt-1 pb-1" href="{{route('pipesSummaryReports.index')}}"
                                               class="text-bold-500 blue-grey darken-4">01 - Summary of Pipes
                                                Inspections Report</a>
                                            @can('create', 'App\Models\Inspection\Tubular\PipesSummaryReport')

                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('pipesSummaryReports.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Tubular\DrillPipe')
                                            <a class="col-10 pt-1 pb-1" href="{{route('drillPipe.index')}}"
                                               class="text-bold-500 blue-grey darken-4">02 - Drill Pipe Inspection
                                                Report</a>
                                            @can('create', 'App\Models\Inspection\Tubular\DrillPipe')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('drillPipe.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Tubular\HeavyWeightPipe')
                                            <a class="col-10 pt-1 pb-1" href="{{route('heavyWeightPipe.index')}}"
                                               class="text-bold-500 blue-grey darken-4">03 - Heavy Weight Drill Pipe
                                                Inspection Report</a>
                                            @can('create', 'App\Models\Inspection\Tubular\HeavyWeightPipe')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('heavyWeightPipe.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Tubular\DrillCollar')
                                            <a class="col-10 pt-1 pb-1" href="{{route('drillCollar.index')}}"
                                               class="text-bold-500 blue-grey darken-4">04 - Drill Collar Inspection
                                                Report</a>
                                            @can('create', 'App\Models\Inspection\Tubular\DrillCollar')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('drillCollar.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Tubular\SubsDimensional')
                                            <a class="col-10 pt-1 pb-1" href="{{route('subsDimensional.index')}}"
                                               class="text-bold-500 blue-grey darken-4">05 - Subs Dimensional Inspection
                                                Report</a>
                                            @can('create', 'App\Models\Inspection\Tubular\SubsDimensional')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('subsDimensional.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Tubular\TubingString')
                                            <a class="col-10 pt-1 pb-1" href="{{route('tubingString.index')}}"
                                               class="text-bold-500 blue-grey darken-4">06 - Tubing String Inspection Sheet
                                            </a>
                                            @can('create', 'App\Models\Inspection\Tubular\TubingString')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('tubingString.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Tubular\StabilizerInspection')
                                            <a class="col-10 pt-1 pb-1" href="{{route('stabilizerInspection.index')}}"
                                               class="text-bold-500 blue-grey darken-4">07 - Stabilizer Inspection Report
                                            </a>
                                            @can('create', 'App\Models\Inspection\Tubular\StabilizerInspection')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('stabilizerInspection.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Tubular\ReamerInspection')
                                            <a class="col-10 pt-1 pb-1" href="{{route('reamerInspection.index')}}"
                                               class="text-bold-500 blue-grey darken-4">08 - Reamer Inspection Report
                                            </a>
                                            @can('create', 'App\Models\Inspection\Tubular\ReamerInspection')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('reamerInspection.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Tubular\LinkInspection')
                                            <a class="col-10 pt-1 pb-1" href="{{route('linkInspection.index')}}"
                                               class="text-bold-500 blue-grey darken-4">09 - Link Inspection Report
                                            </a>
                                            @can('create', 'App\Models\Inspection\Tubular\LinkInspection')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('linkInspection.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Tubular\Pbl')
                                            <a class="col-10 pt-1 pb-1" href="{{route('pbl.index')}}"
                                               class="text-bold-500 blue-grey darken-4">10 - PBL Inspection Report
                                            </a>
                                            @can('create', 'App\Models\Inspection\Tubular\Pbl')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('pbl.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                    <div class="row">
                                        @can('viewAny', 'App\Models\Inspection\Tubular\TubingCasing')
                                            <a class="col-10 pt-1 pb-1" href="{{route('tubingCasing.index')}}"
                                               class="text-bold-500 blue-grey darken-4">11 - Tubing / Casing Inspection Report
                                            </a>
                                            @can('create', 'App\Models\Inspection\Tubular\TubingCasing')
                                                <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                   href="{{route('tubingCasing.create')}}"><i
                                                            class="la la-plus-square"></i></a>
                                            @endcan
                                        @endcan
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </section>
@endsection
