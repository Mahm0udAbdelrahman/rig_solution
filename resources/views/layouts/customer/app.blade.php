<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta name="description" content="">
      <meta name="keywords" content="Amir Elsayed">
      <meta name="author" content="Emad Elrouby">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{$page_name ?? ''}} - {{config('app.name')}}</title>
      <link rel="apple-touch-icon" href="{{asset('app-assets/images/ico/apple-icon-120.png')}}">
      <link rel="shortcut icon" type="image/x-icon" href="{{asset('app-assets/images/ico/favicon.ico')}}">
      <style>
        /* quicksand-300 - latin */
        @font-face {
        font-family: 'Quicksand';
        font-style: normal;
        font-weight: 300;
        src: local(''),
             url('{{asset("app-assets/fonts/quicksand-v30-latin/quicksand-v30-latin-300.woff2")}}') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
             url('{{asset("app-assets/fonts/quicksand-v30-latin/quicksand-v30-latin-300.woff")}}') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* quicksand-regular - latin */
        @font-face {
        font-family: 'Quicksand';
        font-style: normal;
        font-weight: 400;
        src: local(''),
             url('{{asset("app-assets/fonts/quicksand-v30-latin/quicksand-v30-latin-regular.woff2")}}') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
             url('{{asset("app-assets/fonts/quicksand-v30-latin/quicksand-v30-latin-regular.woff")}}') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* quicksand-500 - latin */
        @font-face {
        font-family: 'Quicksand';
        font-style: normal;
        font-weight: 500;
        src: local(''),
             url('{{asset("app-assets/fonts/quicksand-v30-latin/quicksand-v30-latin-500.woff2")}}') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
             url('{{asset("app-assets/fonts/quicksand-v30-latin/quicksand-v30-latin-500.woff")}}') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* quicksand-700 - latin */
        @font-face {
        font-family: 'Quicksand';
        font-style: normal;
        font-weight: 700;
        src: local(''),
             url('{{asset("app-assets/fonts/quicksand-v30-latin/quicksand-v30-latin-700.woff2")}}') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
             url('{{asset("app-assets/fonts/quicksand-v30-latin/quicksand-v30-latin-700.woff")}}') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-300 - latin */
        @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 300;
          src: local(''),
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-300.woff2")}}') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-300.woff")}}') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-regular - latin */
        @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 400;
          src: local(''),
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-regular.woff2")}}') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-regular.woff")}}') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-600 - latin */
        @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 600;
          src: local(''),
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-600.woff2")}}') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-600.woff")}}') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-700 - latin */
        @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 700;
          src: local(''),
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-700.woff2")}}') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-700.woff")}}') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-300italic - latin */
        @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 300;
          src: local(''),
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-300italic.woff2")}}') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-300italic.woff")}}') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-italic - latin */
        @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 400;
          src: local(''),
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-italic.woff2")}}') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-italic.woff")}}') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-600italic - latin */
        @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 600;
          src: local(''),
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-600italic.woff2")}}') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-600italic.woff")}}') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        /* open-sans-700italic - latin */
        @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 700;
          src: local(''),
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-700italic.woff2")}}') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
               url('{{asset("app-assets/fonts/open-sans-v34-latin/open-sans-v34-latin-700italic.woff")}}') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

      </style>
      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/material-icons/material-icons.css')}}">

      <!-- BEGIN: Vendor CSS-->
      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/material-vendors.min.css')}}">

      <!-- END: Vendor CSS-->
      @yield('header')
      <!-- BEGIN: Theme CSS-->
      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/material.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/components.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap-extended.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/material-extended.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/material-colors.css')}}">
      <!-- END: Theme CSS-->

      <!-- BEGIN: Page CSS-->
      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/menu/menu-types/material-vertical-compact-menu.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/colors/material-palette-gradient.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/mobiriseicons/24px/mobirise/style.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/simple-line-icons/style.min.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/page-users.css')}}">
      <!-- END: Page CSS-->
      <style>
        textarea, .preline{
          white-space: pre-wrap !important;
        }
        .preline{
          white-space: pre-line !important;
        }
        .customer-portal-pill-btn{
          display:inline-flex;
          align-items:center;
          justify-content:center;
          min-height:42px;
          padding:0.6rem 1.15rem;
          border-radius:999px;
          border:1px solid #d8e1ef;
          background:#fff;
          color:#41546f;
          font-weight:700;
          line-height:1;
          box-shadow:none;
          text-decoration:none !important;
          transition:all .15s ease;
        }
        .customer-portal-pill-btn:hover{
          background:#f5f8ff;
          color:#22344c;
          border-color:#c7d5ea;
        }
        .customer-portal-pill-btn.is-active{
          background:#3246d3;
          border-color:#3246d3;
          color:#fff !important;
        }
        .customer-portal-pill-btn.is-success{
          background:#1e9e73;
          border-color:#1e9e73;
          color:#fff !important;
        }
        .customer-portal-pill-btn.is-outline{
          background:#fff;
          color:#3246d3;
          border-color:#cfd9ec;
        }
      </style>

      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/toastr.css')}}">
      @yield('header-bottom')

  </head>
  <!-- END: Head-->

  <!-- BEGIN: Body-->
<body class="vertical-layout vertical-compact-menu menu-hide material-vertical-layout material-layout 2-columns fixed-navbar" data-open="click" data-menu="vertical-compact-menu" data-col="2-columns">
  @php
      $customerUser = Auth::guard('customer')->user();
      $departmentUser = Auth::guard('clientDepartments')->user();
      $displayUser = $customerUser ?: $departmentUser;
      $overviewRoute = $departmentUser ? route('department.overview') : route('customer.overview');
      $reportsRoute = $departmentUser ? route('department.reports') : route('customer.reports');
      $avatarPath = $displayUser && !empty($displayUser->logo)
          ? Storage::url('persons/clients/').$displayUser->logo
          : asset('app-assets/images/portrait/small/avatar-s-1.png');
  @endphp

    <!-- BEGIN: Header-->
    <nav class="no-print header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-light navbar-shadow navbar-brand-center">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item"><a class="" style="text-align: center; padding: 8px 0;" href="index.html"><img class="brand-logo" alt="" style="width: 48%;" src="{{asset('app-assets/images/logo/logo.png')}}"></a></li>
                    <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="material-icons mt-50">more_vert</i></a></li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
                    </ul>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="material-icons">notifications_none</i><span id="notinumber" class="badge badge-pill badge-danger badge-up badge-glow">1</span></a>
                            <ul id="messages" class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span></h6><span class="notification-tag badge badge-danger float-right m-0">5 New</span>
                                </li>
                                <li class="scrollable-container media-list w-100"><a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left align-self-center"><i class="material-icons icon-bg-circle bg-teal mr-0">insert_drive_file</i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Generate monthly report</h6><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Last month</time></small>
                                            </div>
                                        </div>
                                    </a></li>
                                <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center" href="javascript:void(0)">Read all notifications</a></li>
                            </ul>
                        </li>
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="material-icons">mail_outline</i></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0"><span class="grey darken-2">Messages</span></h6><span class="notification-tag badge badge-warning float-right m-0">4 New</span>
                                </li>
                                <li class="scrollable-container media-list w-100"><a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left"><span class="avatar avatar-sm avatar-away rounded-circle"><img src="{{asset('app-assets/images/portrait/small/avatar-s-6.png')}}" alt="avatar"><i></i></span></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Eric Alsobrook</h6>
                                                <p class="notification-text font-small-3 text-muted">We have project party this saturday.</p><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">last month</time></small>
                                            </div>
                                        </div>
                                    </a></li>
                                <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center" href="javascript:void(0)">Read all messages</a></li>
                            </ul>
                        </li>
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"><span class="mr-1 user-name text-bold-700">{{ optional($displayUser)->name }}</span><span class="avatar avatar-online"><img src="{{ $avatarPath }}" alt="avatar"><i></i></span></a>
                            <div class="dropdown-menu dropdown-menu-right">
                              <a class="dropdown-item" href="#"><i class="material-icons">person_outline</i> Edit Profile</a>
                            
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('customer.logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('customer.logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"><i class="material-icons">power_settings_new</i> {{ __('Logout') }}</a>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END: Header-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
      <div class="content-header row no-print">
          <div class="content-header-light col-12">
              <div class="row">
                  <div class="content-header-left col-md-9 col-12 mb-2">
                      <h3 class="content-header-title">{{ $page_name ?? 'Customer Portal' }}</h3>
                      <div class="row breadcrumbs-top">
                          <div class="breadcrumb-wrapper col-12">
                              <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page" style="padding-left: 0;">
                                  <a href="{{ $overviewRoute }}">
                                    Overview
                                  </a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                  <a href="{{ $reportsRoute }}">
                                    Published Certificates
                                  </a>
                                </li>
                              </ol>
                          </div>
                      </div>
                      <div class="mt-1 d-flex flex-wrap" style="gap: .5rem;">
                          <a href="{{ $overviewRoute }}" class="customer-portal-pill-btn {{ request()->routeIs('customer.overview', 'department.overview') ? 'is-active' : 'is-outline' }}">Overview</a>
                          <a href="{{ $reportsRoute }}" class="customer-portal-pill-btn {{ request()->routeIs('customer.reports', 'department.reports') ? 'is-active' : 'is-outline' }}">Published Certificates</a>
                      </div>
                  </div>
                  <div class="content-header-right col-md-3 col-12">
                      <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                          <button class="btn btn-primary round dropdown-toggle dropdown-menu-right box-shadow-2 px-2 mb-1" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Support</button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="#"> Live Chat</a>
                            <div class="dropdown-divider"></div><a class="dropdown-item" href="mailto: support@alamiir.com"> Send E-mail</a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="content-overlay"></div>
      <div class="content-wrapper">
          <div class="content-body">
            @yield('content')
          </div>
      </div>
    </div>
    <!-- END: Content-->

  <div class="sidenav-overlay"></div>
  <div class="drag-target"></div>

  <!-- BEGIN: Footer-->
  <footer class="footer footer-static footer-light navbar-border navbar-shadow no-print">
      <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
          <span class="float-md-left d-block d-md-inline-block">
              Copyright &copy; {{date('Y')}} <a class="text-bold-800 grey darken-2" href="#" target="_blank">{{config('app.name')}}</a>
          </span>
          <!--<span class="float-md-right d-none d-lg-block">-->
          <!--    Made By Amiir Elsayed<span id="scroll-top"></span>-->
          <!--</span>-->
      </p>
  </footer>
  <!-- END: Footer-->
  <!-- BEGIN: Vendor JS-->
  <script src="{{asset('app-assets/vendors/js/material-vendors.min.js')}}"></script>
  <!-- BEGIN Vendor JS-->

  @yield('footer')

  <script src="{{asset('app-assets/vendors/js/extensions/toastr.min.js')}}"></script>


  <!-- BEGIN: Theme JS-->
  <script src="{{asset('app-assets/js/core/app-menu.js')}}"></script>
  <script src="{{asset('app-assets/js/core/app.js')}}"></script>
  <!-- END: Theme JS-->

  <!-- BEGIN: Page JS-->
  <script src="{{asset('app-assets/js/scripts/pages/material-app.js')}}"></script>
  <script src="{{asset('app-assets/js/scripts/navs/navs.js')}}"></script>
  <!-- END: Page JS-->
  <script src="{{asset('js/app.js')}}"></script>
  @yield('ajax')

  </body>
  <!-- END: Body-->

</html>
