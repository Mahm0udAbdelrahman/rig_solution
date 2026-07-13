<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
		<!-- BEGIN: Head-->
	  <head>
	      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	      <meta http-equiv="X-UA-Compatible" content="IE=edge">
	      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	      <meta name="description" content="">
	      <meta name="keywords" content="Amiir Elsayed">
	      <meta name="author" content="Emad Elrouby">
	      <meta name="csrf-token" content="{{ csrf_token() }}">
	      <title>{{$page_name ?? ''}} - {{config('app.name')}}</title>
	      <link rel="apple-touch-icon" href="{{asset('app-assets/images/ico/apple-icon-120.png')}}">
	      <link rel="shortcut icon" type="image/x-icon" href="{{asset('app-assets/images/ico/favicon.ico')}}">

	      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/material-icons/material-icons.css')}}">

	      <!-- BEGIN: Vendor CSS-->
	      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/material-vendors.min.css')}}">
				<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/toastr.css')}}">
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

	      <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/toastr.css')}}">
	      @yield('header-bottom')
				<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/custom.css')}}">

	  </head>
  	<!-- END: Head-->

  <!-- BEGIN: Body-->
  <body class="vertical-layout vertical-compact-menu material-vertical-layout material-layout 2-columns fixed-navbar" data-open="click" data-menu="vertical-compact-menu" data-col="2-columns">

    <!-- BEGIN: Header-->
    <nav class="no-print header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-light navbar-shadow navbar-brand-center">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item"><a class="" style="text-align: center; padding: 8px 0;" href="{{ route('dashboard.home') }}"><img class="brand-logo" alt="" style="width: 48%;" src="{{asset('app-assets/images/logo/logo.png')}}"></a></li>
                    <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="material-icons mt-50">more_vert</i></a></li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle" href="#"><i class="ft-menu"></i></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
                    </ul>
                    <ul class="nav navbar-nav float-right">
												@if(!strstr(URL::current(), 'rigsolutionz'))
                        <li class="dropdown dropdown-notification nav-item dark"><a class="nav-link nav-link-label" href="{{route('system.connect_server')}}"><i class="material-icons">call_split</i></a>
												@endif
                        @if($navbar_notifications_enabled)
                        <li class="dropdown dropdown-notification nav-item">
                            <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
                                <i class="material-icons">notifications_none</i>
                                <span id="navbar-notifications-badge" class="badge badge-pill badge-danger badge-up badge-glow {{ ($navbar_notifications_badge_count ?? 0) > 0 ? '' : 'd-none' }}">{{ $navbar_notifications_badge_count ?? 0 }}</span>
                            </a>
                            <ul id="navbar-notifications-menu" class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span></h6>
                                    <span id="navbar-notifications-header-count" class="notification-tag badge badge-danger float-right m-0">{{ $navbar_notifications_badge_count ?? 0 }} Updates</span>
                                </li>
                                <li id="navbar-notifications-list" class="scrollable-container media-list w-100">
                                    @forelse(($navbar_notifications_items ?? []) as $item)
                                        @php
                                            $isApprovalQueue = ($item['type'] ?? 'notification') === 'approval_queue';
                                            $isActionCenter = ($item['type'] ?? 'notification') === 'action_center';
                                            $isUnread = (bool) ($item['is_unread'] ?? false);
                                            $actionLevel = $item['status'] ?? 'primary';
                                            $iconBg = $isActionCenter
                                                ? ($actionLevel === 'danger' ? 'bg-danger' : ($actionLevel === 'warning' ? 'bg-warning' : ($actionLevel === 'success' ? 'bg-success' : ($actionLevel === 'info' ? 'bg-info' : 'bg-primary'))))
                                                : ($isApprovalQueue ? 'bg-warning' : ($isUnread ? 'bg-teal' : 'bg-blue-grey'));
                                            $iconName = $item['icon'] ?? ($isActionCenter ? 'offline_bolt' : ($isApprovalQueue ? 'assignment_late' : 'notifications'));
                                        @endphp
                                        <a href="{{ $item['url'] ?? '#' }}" class="navbar-feed-item {{ $isUnread ? 'bg-light' : '' }}">
                                            <div class="media navbar-feed-media">
                                                <div class="media-left align-self-center"><i class="material-icons icon-bg-circle navbar-feed-icon {{ $iconBg }} mr-0">{{ $iconName }}</i></div>
                                                <div class="media-body navbar-feed-body">
                                                    <h6 class="media-heading navbar-feed-heading">
                                                        <span class="navbar-feed-title">{{ $item['title'] ?? 'Notification' }}</span>
                                                        @if($isActionCenter && !empty($item['count'])) <span class="badge navbar-feed-count badge-{{ $actionLevel }}">{{ $item['count'] }}</span>@endif
                                                    </h6>
                                                    <p class="notification-text font-small-3 text-muted mb-0 navbar-feed-text">{{ $item['message'] ?? '' }}</p>
                                                    <small><time class="media-meta text-muted navbar-feed-time">{{ $item['created_at_human'] ?? '' }}</time></small>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="px-2 py-1 text-muted text-center">No notifications.</div>
                                    @endforelse
                                </li>
                                <li class="dropdown-menu-footer d-flex">
                                    <a class="dropdown-item text-muted text-center" href="{{ route('mailCenter.index', ['tab' => 'notifications']) }}">Open center</a>
                                    <form id="navbar-notifications-read-all-form" method="POST" action="{{ route('mailCenter.notifications.readAll') }}" class="w-100 {{ ($navbar_notifications_unread_count ?? 0) > 0 ? '' : 'd-none' }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-muted text-center border-0 bg-transparent w-100">Mark all read</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($navbar_mailcenter_enabled)
                        <li class="dropdown dropdown-notification nav-item">
                            <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
                                <i class="material-icons">mail_outline</i>
                                <span id="navbar-mailcenter-badge" class="badge badge-pill badge-warning badge-up badge-glow {{ ($navbar_mailcenter_action_count ?? 0) > 0 ? '' : 'd-none' }}">{{ $navbar_mailcenter_action_count ?? 0 }}</span>
                            </a>
                            <ul id="navbar-mailcenter-menu" class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0"><span class="grey darken-2">Rig MailCenter</span></h6>
                                    <span id="navbar-mailcenter-header-count" class="notification-tag badge badge-warning float-right m-0">{{ $navbar_mailcenter_action_count ?? 0 }} Action</span>
                                </li>
                                <li id="navbar-mailcenter-list" class="scrollable-container media-list w-100">
                                    @forelse(($navbar_mailcenter_messages ?? []) as $mailMessage)
                                        <a href="{{ $mailMessage['url'] ?? '#' }}" class="navbar-feed-item">
                                            <div class="media navbar-feed-media">
                                                <div class="media-left align-self-center"><i class="material-icons icon-bg-circle navbar-feed-icon bg-warning mr-0">mail_outline</i></div>
                                                <div class="media-body navbar-feed-body">
                                                    <h6 class="media-heading navbar-feed-heading"><span class="navbar-feed-title">{{ $mailMessage['code'] ?? '-' }} - {{ ucfirst(str_replace('_', ' ', $mailMessage['status'] ?? '')) }}</span></h6>
                                                    <p class="notification-text font-small-3 text-muted mb-0 navbar-feed-text">{{ $mailMessage['subject'] ?? '' }}</p>
                                                    <small><time class="media-meta text-muted navbar-feed-time">{{ $mailMessage['updated_at_human'] ?? '' }}</time></small>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="px-2 py-1 text-muted text-center">No mail activity.</div>
                                    @endforelse
                                </li>
                                <li class="dropdown-menu-footer">
                                    <a class="dropdown-item text-muted text-center" href="{{ route('mailCenter.index', ['tab' => 'messages']) }}">Open Rig MailCenter</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @php
                            $authUser = Auth::user();
                            $authEmployee = optional($authUser)->employee;
                            $authDisplayName = optional($authEmployee)->name ?: 'User';
                            $authRoleName = optional($authUser->role ?? null)->name ?: ($authUser->is_super_admin ? 'Super Admin' : 'User');
                            $authAvatarUrl = optional($authEmployee)->avatar_url;
                            $authAvatarInitials = optional($authEmployee)->avatar_initials ?: 'U';
                        @endphp
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"><span class="mr-1 user-name text-bold-700">{{ $authDisplayName }}</span><span class="avatar avatar-online app-user-avatar-shell">
                            @if($authAvatarUrl)
                                <img src="{{ $authAvatarUrl }}" alt="avatar">
                            @else
                                <span class="app-user-avatar-fallback">{{ $authAvatarInitials }}</span>
                            @endif
                            <i></i></span></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="dropdown-item-text px-1 py-75 border-bottom">
                                    <div class="font-weight-bold">{{ $authDisplayName }}</div>
                                    <small class="text-muted d-block">{{ $authRoleName }}</small>
                                    @if(optional($authEmployee)->email)
                                        <small class="text-muted d-block">{{ $authEmployee->email }}</small>
                                    @endif
                                </div>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="material-icons">person_outline</i> My Profile</a>
                                <a class="dropdown-item" href="{{ route('mailCenter.index', ['tab' => 'notifications']) }}"><i class="material-icons">notifications_none</i> Notifications</a>
                                <a class="dropdown-item" href="{{ route('dashboard.home') }}"><i class="material-icons">dashboard</i> Dashboard</a>
                                <!-- @if((bool) $authUser->is_super_admin)
                                    <a class="dropdown-item" href="{{ route('system.maintenance.index') }}"><i class="material-icons">build_circle</i> System Maintenance</a>
                                @endif
                                <div class="dropdown-divider"></div> -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"><i class="material-icons">power_settings_new</i> {{ __('Logout') }}</a>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END: Header-->
    @include('layouts.navbar')
    <?php
    /*use App\Http\Controllers\CustomController;
    CustomController::navbar([
      ['model' => 'dashboard', 'icon' => 'mbri-desktop', 'slug' => 'Dashboard', 'sub' => ''],
      ['model' => 'work-flow', 'icon' => 'la la-cogs', 'slug' => 'Work Flow', 'sub' => [
          ['model' => 'jobRequest', 'icon' => 'mbri-edit', 'slug' => 'Job Control Form (JCF)', 'sub' => ''],
          ['model' => 'qutation', 'icon' => 'la la-file-text', 'slug' => 'Quotation / Contract', 'sub' => ''],
          ['model' => 'packingSlip', 'icon' => 'icon-list', 'slug' => 'Packing Slip', 'sub' => ''],
          ['model' => 'serviceTicket', 'icon' => 'icon-list', 'slug' => 'Service Ticket', 'sub' => ''],
          ['model' => 'invoice', 'icon' => 'la la-money', 'slug' => 'Invoice', 'sub' => '']
        ]
      ],
      ['model' => 'inspection', 'icon' => 'la la-certificate', 'slug' => 'Inspection', 'sub' => ''],
      ['model' => 'persons', 'icon' => 'mbri-user', 'slug' => 'Persons', 'sub' => ''],
      ['model' => 'organization', 'icon' => 'la la-sitemap', 'slug' => 'Organization', 'sub' => [
          ['model' => 'department', 'icon' => 'la la-tasks', 'slug' => 'Department', 'sub' => ''],
          ['model' => 'employee', 'icon' => 'icon-bag', 'slug' => 'Employee', 'sub' => ''],
          ['model' => '', 'icon' => 'icon-bag', 'slug' => 'System Admins', 'sub' => [
              ['model' => 'user', 'icon' => 'icon-users', 'slug' => 'User', 'sub' => ''],
              ['model' => 'role', 'icon' => 'icon-users', 'slug' => 'Role', 'sub' => '']
            ]
          ],
        ]
      ],
      ['model' => 'general-info', 'icon' => 'la la-info-circle', 'slug' => 'General Info', 'sub' => [
          ['model' => 'item', 'icon' => 'la la-cube', 'slug' => 'Item', 'sub' => ''],
          ['model' => 'tool', 'icon' => 'la la-legal', 'slug' => 'Tool', 'sub' => ''],
          ['model' => 'specification', 'icon' => 'la la-odnoklassniki-square', 'slug' => 'Specification', 'sub' => '']
        ]
      ]
    ]);*/
    ?>
    <!-- BEGIN: Content-->
    <div class="app-content content">
      <div class="content-header row no-print">
          <div class="content-header-light col-12">
              <div class="row">
                  <div class="content-header-left col-md-9 col-12 mb-2">
                      <h3 class="content-header-title">{{$page_name ?? ''}}</h3>
                      <div class="row breadcrumbs-top">
                          <div class="breadcrumb-wrapper col-12">
                              <ol class="breadcrumb">
                                  {{$segments=''}}
                                  @foreach(request()->segments() as $segment)
                                    <li class="breadcrumb-item" aria-current="page" style="padding-left: 0;">
                                      @if($segment === collect(request()->segments())->last())
                                        <span>{{ucwords(str_replace('-',' ',$segment))}}</span>
                                      @else
                                        <a href="{{$segments .= '/'.$segment}}">{{ucwords(str_replace('-',' ',$segment))}}</a>
                                      @endif
                                    </li>
                                  @endforeach
                              </ol>
                          </div>
                      </div>
                  </div>
                  <div class="content-header-right col-md-3 col-12">
                      <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                          <button class="btn btn-primary round dropdown-toggle dropdown-menu-right box-shadow-2 px-2 mb-1" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Support (Actual)</button>
                          <div class="dropdown-menu">
                            <!--<a class="dropdown-item" href="component-alerts.html"> Live Chat</a>-->
                            <a class="dropdown-item" href="http://wa.me/+201114735020"> Send Whatsapp to Eng/Amiir</a>
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
	<script src="{{asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
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
  <!-- <script src="{{asset('js/app.js')}}"></script> -->
	<!-- <script src="{{asset('build/assets/app.e80b3859.js')}}"></script> -->
  @yield('ajax')

  <script>
  (function () {
    const defaultPlaceholders = new WeakMap();

    const getFileLabelText = function (input) {
      if (!input || !input.files || !input.files.length) {
        return '';
      }

      if (input.files.length === 1) {
        return input.files[0].name;
      }

      return input.files.length + ' files selected';
    };

    const syncCustomFileInput = function (input) {
      const wrapper = input.closest('.custom-file');
      const label = wrapper ? wrapper.querySelector('.custom-file-label') : null;
      if (!label) {
        return;
      }

      if (!defaultPlaceholders.has(label)) {
        defaultPlaceholders.set(label, (label.textContent || 'Choose file').trim());
      }

      const selectedText = getFileLabelText(input);
      label.textContent = selectedText || defaultPlaceholders.get(label);
      wrapper.classList.toggle('is-filled', !!selectedText);
      wrapper.classList.toggle('is-multiple', !!(input.files && input.files.length > 1));
    };

    const bindFileInput = function (input) {
      if (!input || input.dataset.fileUiBound === '1') {
        return;
      }

      input.dataset.fileUiBound = '1';
      input.addEventListener('change', function () {
        syncCustomFileInput(input);
      });

      input.addEventListener('focus', function () {
        const wrapper = input.closest('.custom-file');
        if (wrapper) {
          wrapper.classList.add('is-focused');
        }
      });

      input.addEventListener('blur', function () {
        const wrapper = input.closest('.custom-file');
        if (wrapper) {
          wrapper.classList.remove('is-focused');
        }
      });

      syncCustomFileInput(input);
    };

    const initializeFileInputs = function (root) {
      const scope = root || document;
      scope.querySelectorAll('input[type="file"]').forEach(bindFileInput);
    };

    // Keep file picker clickable even when label overlays input or has mismatched "for".
    document.addEventListener('click', function (event) {
      const label = event.target.closest('.custom-file-label');
      if (!label) {
        return;
      }

      const wrapper = label.closest('.custom-file');
      if (!wrapper) {
        return;
      }

      const input = wrapper.querySelector('input[type="file"]');
      if (!input || input.disabled) {
        return;
      }

      bindFileInput(input);
      event.preventDefault();
      input.click();
    });

    // Dynamic rows (repeaters) may inject new file inputs after DOM ready.
    document.addEventListener('change', function (event) {
      const target = event.target;
      if (!target || !target.matches || !target.matches('input[type="file"]')) {
        return;
      }

      bindFileInput(target);
      syncCustomFileInput(target);
    });

    document.addEventListener('DOMContentLoaded', function () {
      initializeFileInputs(document);
    });

    window.initializeSystemFileInputs = initializeFileInputs;
  }());
  </script>

  <script>
  (function (window, document, $) {
    const TARGET_PATHS = ['/dashboard/work-flow/', '/dashboard/inspection/'];
    const LOCKED_TEXT_SUFFIX = '...';
    const TRANSIENT_UNLOCK_MS = 2500;
    const transientLockTimers = new WeakMap();
    const getSubmitGuardMode = function (form) {
      if (!form) {
        return 'default';
      }

      const mode = String(form.getAttribute('data-submit-guard-mode') || '').trim().toLowerCase();
      return mode || 'default';
    };

    const isTargetForm = function (form) {
      if (!form || !form.action) {
        return false;
      }

      const method = String((form.getAttribute('method') || 'post')).toUpperCase();
      if (method === 'GET') {
        return false;
      }

      return TARGET_PATHS.some(function (segment) {
        return form.action.indexOf(segment) !== -1;
      });
    };

    const generateSubmissionToken = function () {
      if (window.crypto && typeof window.crypto.randomUUID === 'function') {
        return window.crypto.randomUUID();
      }

      return 'submit_' + Date.now() + '_' + Math.random().toString(36).slice(2, 12);
    };

    const ensureSubmissionToken = function (form) {
      if (!isTargetForm(form)) {
        return null;
      }

      let tokenInput = form.querySelector('input[name="_submission_token"]');
      if (!tokenInput) {
        tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_submission_token';
        form.appendChild(tokenInput);
      }

      if (!tokenInput.value) {
        tokenInput.value = generateSubmissionToken();
      }

      return tokenInput;
    };

    const setElementLocked = function (element) {
      if (!element || element.dataset.submitGuardLocked === '1') {
        return;
      }

      element.dataset.submitGuardLocked = '1';
      if ('disabled' in element) {
        element.disabled = true;
      } else {
        element.setAttribute('aria-disabled', 'true');
        element.style.pointerEvents = 'none';
        element.style.opacity = '0.6';
      }

      if (element.tagName === 'BUTTON' || element.tagName === 'INPUT') {
        const currentLabel = element.tagName === 'INPUT' ? element.value : element.textContent;
        const trimmedLabel = (currentLabel || '').trim();
        if (trimmedLabel !== '' && !trimmedLabel.endsWith(LOCKED_TEXT_SUFFIX)) {
          element.dataset.submitGuardLabel = currentLabel;
          if (element.tagName === 'INPUT') {
            element.value = trimmedLabel + LOCKED_TEXT_SUFFIX;
          } else {
            element.textContent = trimmedLabel + LOCKED_TEXT_SUFFIX;
          }
        }
      }
    };

    const restoreElementLock = function (element) {
      if (!element || element.dataset.submitGuardLocked !== '1') {
        return;
      }

      delete element.dataset.submitGuardLocked;
      if ('disabled' in element) {
        element.disabled = false;
      } else {
        element.removeAttribute('aria-disabled');
        element.style.pointerEvents = '';
        element.style.opacity = '';
      }

      if (element.dataset.submitGuardLabel) {
        if (element.tagName === 'INPUT') {
          element.value = element.dataset.submitGuardLabel;
        } else {
          element.textContent = element.dataset.submitGuardLabel;
        }
        delete element.dataset.submitGuardLabel;
      }
    };

    const getLockableElements = function (form) {
      if (!form) {
        return [];
      }

      return Array.from(form.querySelectorAll('button[type="submit"], input[type="submit"], .actions a[href="#finish"]'));
    };

    const lockForm = function (form, permanent) {
      if (!isTargetForm(form)) {
        return;
      }

      ensureSubmissionToken(form);

      if (permanent) {
        const pendingTimer = transientLockTimers.get(form);
        if (pendingTimer) {
          window.clearTimeout(pendingTimer);
          transientLockTimers.delete(form);
        }
        form.dataset.submitGuardPermanent = '1';
      } else if (form.dataset.submitGuardPermanent === '1') {
        return;
      }

      form.dataset.submitGuardLocked = '1';
      getLockableElements(form).forEach(setElementLocked);

      if (!permanent) {
        const pendingTimer = transientLockTimers.get(form);
        if (pendingTimer) {
          window.clearTimeout(pendingTimer);
        }
        transientLockTimers.set(form, window.setTimeout(function () {
          unlockTransientForm(form);
        }, TRANSIENT_UNLOCK_MS));
      }
    };

    const unlockTransientForm = function (form) {
      if (!form) {
        return;
      }

      const pendingTimer = transientLockTimers.get(form);
      if (pendingTimer) {
        window.clearTimeout(pendingTimer);
        transientLockTimers.delete(form);
      }

      delete form.dataset.submitGuardPermanent;
      delete form.dataset.submitGuardLocked;
      getLockableElements(form).forEach(restoreElementLock);
    };

    const primeForms = function (root) {
      const scope = root || document;
      scope.querySelectorAll('form').forEach(function (form) {
        ensureSubmissionToken(form);
      });
    };

    document.addEventListener('DOMContentLoaded', function () {
      primeForms(document);
    });

    document.addEventListener('submit', function (event) {
      const form = event.target;
      if (!isTargetForm(form)) {
        return;
      }

      ensureSubmissionToken(form);

      if (form.dataset.submitGuardPermanent === '1') {
        event.preventDefault();
        return;
      }

      lockForm(form, getSubmitGuardMode(form) !== 'transient');
    }, true);

    document.addEventListener('click', function (event) {
      const trigger = event.target.closest('button[type="submit"], input[type="submit"], .actions a[href="#finish"]');
      if (!trigger) {
        return;
      }

      const form = trigger.closest('form');
      if (!isTargetForm(form)) {
        return;
      }

      if (form.dataset.submitGuardLocked === '1') {
        event.preventDefault();
        event.stopPropagation();
        return;
      }

      if (typeof form.reportValidity === 'function' && !form.reportValidity()) {
        return;
      }

      window.setTimeout(function () {
        if (form.dataset.submitGuardPermanent === '1' || form.dataset.submitGuardLocked === '1') {
          return;
        }

        lockForm(form, false);
      }, 0);
    }, true);

    if ($) {
      $(document).ajaxComplete(function () {
        document.querySelectorAll('form[data-submit-guard-locked="1"]').forEach(function (form) {
          unlockTransientForm(form);
        });
      });
    }

    window.initializeDuplicateSubmitGuard = primeForms;
  }(window, document, window.jQuery));
  </script>

  <script>
  (function (window, $) {
    const config = {
      enabled: @json((bool) ($navbar_polling_enabled ?? false)),
      intervalMs: @json((int) ($navbar_polling_interval_ms ?? 60000)),
      url: @json($navbar_polling_url ?? null)
    };

    if (!config.enabled || !config.url || !$) {
      return;
    }

    const escapeHtml = function (value) {
      return $('<div>').text(value || '').html();
    };

    const setBadge = function ($element, count) {
      if (!$element.length) {
        return;
      }

      if ((count || 0) > 0) {
        $element.text(count).removeClass('d-none');
        return;
      }

      $element.text('0').addClass('d-none');
    };

    const renderMailItems = function (items) {
      if (!items || !items.length) {
        return '<div class="px-2 py-1 text-muted text-center">No mail activity.</div>';
      }

      return items.map(function (item) {
        return '<a href="' + escapeHtml(item.url) + '" class="navbar-feed-item">' +
          '<div class="media navbar-feed-media">' +
            '<div class="media-left align-self-center"><i class="material-icons icon-bg-circle navbar-feed-icon bg-warning mr-0">mail_outline</i></div>' +
            '<div class="media-body navbar-feed-body">' +
              '<h6 class="media-heading navbar-feed-heading"><span class="navbar-feed-title">' + escapeHtml(item.code) + ' - ' + escapeHtml(String(item.status || '').split('_').join(' ')) + '</span></h6>' +
              '<p class="notification-text font-small-3 text-muted mb-0 navbar-feed-text">' + escapeHtml(item.subject) + '</p>' +
              '<small><time class="media-meta text-muted navbar-feed-time">' + escapeHtml(item.updated_at_human) + '</time></small>' +
            '</div>' +
          '</div>' +
        '</a>';
      }).join('');
    };

    const renderNotificationItems = function (items) {
      if (!items || !items.length) {
        return '<div class="px-2 py-1 text-muted text-center">No notifications.</div>';
      }

      const actionIconBg = function (level) {
        if (level === 'danger') {
          return 'bg-danger';
        }
        if (level === 'warning') {
          return 'bg-warning';
        }
        if (level === 'success') {
          return 'bg-success';
        }
        if (level === 'info') {
          return 'bg-info';
        }
        return 'bg-primary';
      };

      return items.map(function (item) {
        const isApprovalQueue = item.type === 'approval_queue';
        const isActionCenter = item.type === 'action_center';
        const isUnread = !!item.is_unread;
        const iconBg = isActionCenter ? actionIconBg(item.status) : (isApprovalQueue ? 'bg-warning' : (isUnread ? 'bg-teal' : 'bg-blue-grey'));
        const iconName = item.icon || (isActionCenter ? 'offline_bolt' : (isApprovalQueue ? 'assignment_late' : 'notifications'));
        const countBadge = isActionCenter && (item.count || 0) > 0
          ? '<span class="badge navbar-feed-count badge-' + escapeHtml(item.status || 'primary') + '">' + escapeHtml(item.count) + '</span>'
          : '';

        return '<a href="' + escapeHtml(item.url) + '" class="navbar-feed-item ' + (isUnread ? 'bg-light' : '') + '">' +
          '<div class="media navbar-feed-media">' +
            '<div class="media-left align-self-center"><i class="material-icons icon-bg-circle navbar-feed-icon ' + iconBg + ' mr-0">' + iconName + '</i></div>' +
            '<div class="media-body navbar-feed-body">' +
              '<h6 class="media-heading navbar-feed-heading"><span class="navbar-feed-title">' + escapeHtml(item.title) + '</span>' + countBadge + '</h6>' +
              '<p class="notification-text font-small-3 text-muted mb-0 navbar-feed-text">' + escapeHtml(item.message) + '</p>' +
              '<small><time class="media-meta text-muted navbar-feed-time">' + escapeHtml(item.created_at_human) + '</time></small>' +
            '</div>' +
          '</div>' +
        '</a>';
      }).join('');
    };

    const applyPayload = function (payload) {
      if (!payload) {
        return;
      }

      $('#navbar-mailcenter-list').html(renderMailItems(payload.navbar_mailcenter_messages || []));
      $('#navbar-notifications-list').html(renderNotificationItems(payload.navbar_notifications_items || []));

      const mailActionCount = payload.navbar_mailcenter_action_count || 0;
      const notificationBadgeCount = payload.navbar_notifications_badge_count || 0;
      const unreadNotificationCount = payload.navbar_notifications_unread_count || 0;

      setBadge($('#navbar-mailcenter-badge'), mailActionCount);
      setBadge($('#navbar-notifications-badge'), notificationBadgeCount);
      $('#navbar-mailcenter-header-count').text(mailActionCount + ' Action');
      $('#navbar-notifications-header-count').text(notificationBadgeCount + ' Updates');
      $('#navbar-notifications-read-all-form').toggleClass('d-none', unreadNotificationCount <= 0);
    };

    let isLoading = false;
    const poll = function () {
      if (isLoading) {
        return;
      }

      isLoading = true;
      $.ajax({
        url: config.url,
        method: 'GET',
        cache: false,
        dataType: 'json'
      }).done(function (payload) {
        applyPayload(payload);
      }).always(function () {
        isLoading = false;
      });
    };

    window.setInterval(poll, Math.max(15000, config.intervalMs || 60000));
  })(window, window.jQuery);
  </script>

  <script>
  (function () {
    document.addEventListener('submit', function (event) {
      const form = event.target;
      if (!(form instanceof HTMLFormElement)) {
        return;
      }

      if (!form.classList.contains('js-confirm-action')) {
        return;
      }

      if (form.dataset.confirmed === '1') {
        return;
      }

      event.preventDefault();

      const title = form.getAttribute('data-confirm-title') || 'Are you sure?';
      const text = form.getAttribute('data-confirm-text') || 'Please confirm this action.';
      const icon = form.getAttribute('data-confirm-icon') || 'warning';
      const confirmText = form.getAttribute('data-confirm-confirm') || 'Confirm';
      const cancelText = form.getAttribute('data-confirm-cancel') || 'Cancel';
      const confirmButtonClass = form.getAttribute('data-confirm-class') || 'btn btn-primary';
      const cancelButtonClass = form.getAttribute('data-cancel-class') || 'btn btn-light ml-1';

      const proceed = function () {
        form.dataset.confirmed = '1';
        form.submit();
      };

      if (window.Swal && typeof window.Swal.fire === 'function') {
        window.Swal.fire({
          title: title,
          text: text,
          icon: icon,
          type: icon,
          showCancelButton: true,
          confirmButtonText: confirmText,
          cancelButtonText: cancelText,
          confirmButtonClass: confirmButtonClass,
          cancelButtonClass: cancelButtonClass,
          buttonsStyling: false,
        }).then(function (result) {
          if (result.isConfirmed || result.value === true) {
            proceed();
          }
        });
        return;
      }

      if (window.confirm(text)) {
        proceed();
      }
    }, true);
  }());
  </script>

  <script>
  // Echo.private('App.Models.User.{{Auth::id()}}')
  // .notification((notification) => {
  //
  //   toastr.info('Good Job !', notification.message, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000 });
  //
  // });
  </script>

  </body>
  <!-- END: Body-->

</html>
