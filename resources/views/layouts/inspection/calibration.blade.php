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
                                    <span class="font-medium-4 text-bold-500 blue darken-4">Calibration, Calibrations</span>
                                </div>

                            </div>
                            <div class="card-footer border-0">
                                <ul class="list-group">
                                    <li class="list-group-item pt-0 pb-0">
                                    <div class="row">
                                                @can('viewAny', 'App\Models\Inspection\Calibration\CalibrationPressureGauge')
                                                <a class="col-10 pt-1 pb-1" class="col-10" href="{{route('calibrationPressureGauge.index')}}"
                                                class="text-bold-500 blue-grey darken-4">01 - Calibration Certificate (Pressure Gauge)</a>
                                                @can('create', 'App\Models\Inspection\Calibration\CalibrationPressureGauge')
                                                    <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                    href="{{route('calibrationPressureGauge.create')}}"><i
                                                                class="la la-plus-square"></i></a>
                                                @endcan
                                            @endcan
                                            @can('viewAny', 'App\Models\Inspection\Calibration\CalibrationTorque')
                                                <a class="col-10 pt-1 pb-1" class="col-10" href="{{route('calibrationTorque.index')}}"
                                                class="text-bold-500 blue-grey darken-4">02 - Calibration Certificate (Torque)</a>
                                                @can('create', 'App\Models\Inspection\Calibration\CalibrationTorque')
                                                    <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                    href="{{route('calibrationTorque.create')}}"><i
                                                                class="la la-plus-square"></i></a>
                                                @endcan
                                            @endcan
                                            @can('viewAny', 'App\Models\Inspection\Calibration\CalibrationPressureTest')
                                                <a class="col-10 pt-1 pb-1" class="col-10" href="{{route('calibrationPressureTest.index')}}"
                                                class="text-bold-500 blue-grey darken-4">03 - Calibration Certificate (Pressure Test)</a>
                                                @can('create', 'App\Models\Inspection\Calibration\CalibrationPressureTest')
                                                    <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                    href="{{route('calibrationPressureTest.create')}}"><i
                                                                class="la la-plus-square"></i></a>
                                                @endcan
                                            @endcan
                                            @can('viewAny', 'App\Models\Inspection\Calibration\CalibrationYoke')
                                                <a class="col-10 pt-1 pb-1" class="col-10" href="{{route('calibrationYoke.index')}}"
                                                class="text-bold-500 blue-grey darken-4">04 - Calibration Certificate (Yoke)</a>
                                                @can('create', 'App\Models\Inspection\Calibration\CalibrationYoke')
                                                    <a class="col-2 btn btn-icon btn-pure success waves-effect waves-light pt-1 pb-1"
                                                    href="{{route('calibrationYoke.create')}}"><i
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
                
				
            </div>
        </div>
    </section>
@endsection
