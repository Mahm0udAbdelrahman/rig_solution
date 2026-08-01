<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
		<!-- BEGIN: Head-->
	  <head>
	      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	      <meta http-equiv="X-UA-Compatible" content="IE=edge">
	      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	      <meta name="description" content="">
	      <meta name="keywords" content="KeenDeer">
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
                                @if((bool) $authUser->is_super_admin)
                                    <a class="dropdown-item" href="{{ route('system.maintenance.index') }}"><i class="material-icons">build_circle</i> System Maintenance</a>
                                @endif
                                <div class="dropdown-divider"></div>
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
                          <button class="btn btn-primary round dropdown-toggle dropdown-menu-right box-shadow-2 px-2 mb-1" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Support</button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="component-alerts.html"> Live Chat</a>
                            <div class="dropdown-divider"></div><a class="dropdown-item" href="mailto: support@keendeer.com"> Send E-mail</a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="content-overlay"></div>
      <div class="content-wrapper">
          <div class="content-body">

{{--
<!-- upload actions top -->
@if($user_id_approved != Null || strpos( $folder, 'workflow' ) || Route::currentRouteName() == "defect.show" ||
Route::currentRouteName() == "nregister.show" || Route::currentRouteName() == "drawingInspection.show")
<div class="card no-print mb-2">
	<div class="card-content">
		<div class="card-body">
			<div class="row" style="direction: rtl;">
				@can('create', App\Models\WorkFlow\MailCenter::class)
				<a class="btn btn-info btn-print btn-lg ml-1" href="{{ route('mailCenter.compose.related', ['relatedType' => 'inspection_report', 'relatedId' => $for_approve_url]) }}">Send via Rig MailCenter <i class="la la-envelope-o mr-50"></i></a>
				@endcan
				@if (!empty($pdf_exists))
				<button type="button" id="print" class="btn btn-secondary btn-print btn-lg ml-1">Print Page <i
						class="la la-paper-plane-o mr-50"></i></button>
				@endif

				@if (!empty($pdf_exists))
				<a class="btn btn-primary btn-print btn-lg ml-1" target="_blank" href="{{ $pdf_download_url ?? URL('storage/'.$folder.'/'.$imageurl.'.pdf') }}">Download <i class="la la-download mr-50"></i></a>
				@endif
				<button type="button" id="uploadpdf" class="btn btn-dark btn-print btn-lg">Upload / Update PDF <i
						class="la la-paper-plane-o"></i></button>
			</div>
		</div>
	</div>
</div>
@endif  --}}

<!-------------------------- BEGIN: Content--------------------------------------->
@php $index = 1; @endphp
@foreach($subCollections as $subCollection)
@include('layouts.inspection.ndt.nregister.page', ['chunk_data' => $subCollection, 'page_number' => 'Page '.$index.' of
'.$total, 'id'=> 'page_'.$index])
@php $index++; @endphp
@endforeach
<!-------------------------- END: Content ---------------------------------------->


<!-- upload actions -->
<!-- these inspection does require approval before upload the pdf -->
@if($user_id_approved != Null || strpos( $folder, 'workflow' ) || Route::currentRouteName() == "defect.show" ||
Route::currentRouteName() == "nregister.show" || Route::currentRouteName() == "drawingInspection.show")
<div class="card no-print mt-2">
	<div class="card-content">
		<div class="card-body">
			<div class="row" style="direction: rtl;">
				@can('create', App\Models\WorkFlow\MailCenter::class)
				<a class="btn btn-info btn-print btn-lg ml-1" href="{{ route('mailCenter.compose.related', ['relatedType' => 'inspection_report', 'relatedId' => $for_approve_url]) }}">Send via Rig MailCenter <i class="la la-envelope-o mr-50"></i></a>
				@endcan
				@if (!empty($pdf_exists))
				<button type="button" id="print" class="btn btn-secondary btn-print btn-lg ml-1">Print Page <i
						class="la la-paper-plane-o mr-50"></i></button>

				<div class="btn-group ml-1" style="direction: ltr;">
					<button type="button" class="btn btn-primary btn-print btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="la la-download mr-50"></i> Download
					</button>
					<div class="dropdown-menu dropdown-menu-right shadow-lg p-1" style="min-width: 280px; width: max-content; border-radius: 8px;">
						<h6 class="dropdown-header text-bold-600 px-1 mb-0" style="color: #4B4B4B; white-space: nowrap;"><i class="la la-download"></i> Choose Format / اختر الصيغة:</h6>
						<div class="dropdown-divider my-1"></div>
						<a class="dropdown-item py-2 px-1" target="_blank" href="{{ $pdf_download_url ?? URL('storage/'.$folder.'/'.$imageurl.'.pdf') }}" style="font-size: 14px; border-radius: 5px;">
							<i class="la la-file-pdf-o text-danger font-medium-3 mr-1" style="vertical-align: middle;"></i> <strong>PDF</strong> Document
						</a>
						<a class="dropdown-item py-2 px-1" href="{{ route('nregister.exportExcel', $model->id) }}" style="font-size: 14px; border-radius: 5px;">
							<i class="la la-file-excel-o text-success font-medium-3 mr-1" style="vertical-align: middle;"></i> <strong>Excel</strong> Spreadsheet
						</a>
					</div>
				</div>
				@endif
				<button type="button" id="uploadpdf" class="btn btn-dark btn-print btn-lg">Upload / Update PDF <i
						class="la la-paper-plane-o"></i></button>
			</div>
		</div>
	</div>
</div>
@endif
<!---------------------------->



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
	{{--  <span class="float-md-right d-none d-lg-block">
		Made By Keen Deer<span id="scroll-top"></span>  --}}
	</span>
</p>
</footer>
<!-- END: Footer-->
<!-- BEGIN: Vendor JS-->
<script src="{{asset('app-assets/vendors/js/material-vendors.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<!-- BEGIN Vendor JS-->


<script src="{{asset('app-assets/vendors/js/extensions/toastr.min.js')}}"></script>


<!-- BEGIN: Theme JS-->
<script src="{{asset('app-assets/js/core/app-menu.js')}}"></script>
<script src="{{asset('app-assets/js/core/app.js')}}"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="{{asset('app-assets/js/scripts/pages/material-app.js')}}"></script>
<script src="{{asset('app-assets/js/scripts/navs/navs.js')}}"></script>
<!-- END: Page JS-->
<!-- upload actions scripts -->
<script src="{{asset('app-assets/js/html2canvas.js')}}"></script>
<script src="{{asset('app-assets/js/jspdf.js')}}"></script>
<script>
	function setPdfUploadState(isLoading) {
		var $button = $('#uploadpdf');
		var $icon = $button.find('i');
		if (isLoading) {
			$icon.removeClass('la la-paper-plane-o').addClass('la la-refresh spinner');
			$button.prop('disabled', true);
			return;
		}

		$icon.removeClass('la la-refresh spinner').addClass('la la-paper-plane-o');
		$button.prop('disabled', false);
	}

	function captureReportPagesSequentially() {
		var captureElements = Array.prototype.slice.call(document.querySelectorAll('.donw'));
		var images = [];

		return captureElements.reduce(function (chain, element) {
			return chain.then(function () {
				return html2canvas(element, {
					backgroundColor: '#ffffff',
					scale: 1,
					useCORS: true,
					scrollX: 0,
					scrollY: 0,
					logging: false
				}).then(function (canvas) {
					images.push(canvas.toDataURL('image/jpeg', 0.72));
				});
			});
		}, Promise.resolve()).then(function () {
			if (!images.length || images.length !== captureElements.length) {
				throw new Error('capture_incomplete');
			}

			return images;
		});
	}

	function extractUploadErrorMessage(error) {
		if (error && error.responseJSON && error.responseJSON.errors) {
			return Object.values(error.responseJSON.errors).flat().join(' | ');
		}

		if (error && error.responseJSON && error.responseJSON.success) {
			return error.responseJSON.success;
		}

		if (error && error.statusText) {
			return error.statusText;
		}

		if (error && error.message) {
			return error.message;
		}

		return 'Unable to build the full PDF';
	}

	function uploadCapturedSnapshots(images) {
		var formData = new FormData();
		formData.append('imageurl', '{{$imageurl}}');
		formData.append('folder', '{{$folder}}');
		formData.append('image', JSON.stringify(images));

		return $.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			type: 'POST',
			url: "{{route('report.makeImageForPdf', $for_approve_url)}}",
			cache: false,
			data: formData,
			processData: false,
			contentType: false
		}).then(function () {
			return images;
		});
	}

	function convert_pdf(images) {
		if (!images || !images.length) {
			return Promise.reject(new Error('pdf_images_missing'));
		}

		const pdf_page_mode = $('#page_mode').val() || 'p';

		var doc = new jspdf.jsPDF(pdf_page_mode, 'pt', 'a4', true);
		var width = doc.internal.pageSize.getWidth();
		var height = doc.internal.pageSize.getHeight();
		for (var i = 0; i < images.length; ++i) {
			doc.addImage(images[i], "JPEG", 0, 0, width, height, "alias" + i, 'FAST');
			if (i + 1 != images.length) {
				doc.addPage()
			}
		}
		var blob = doc.output('blob');
		var formData = new FormData();
		formData.append('pdf', blob, 'nregister-report.pdf');
		formData.append('imageurl', '{{$imageurl}}');
		formData.append('folder', '{{$folder}}');
		@if(!empty($for_approve_url))
		formData.append('report_id', '{{$for_approve_url}}');
		@endif

		return $.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			type: 'POST',
			url: "{{route('report.generatePdf')}}",
			cache: false,
			data: formData,
			processData: false,
			contentType: false
		});
	}

	function print_pdf() {
		printWindow = window.open("{{URL('storage/'.$folder.'/'.$imageurl.'.pdf')}}");
		printWindow.window.print();
	}

	$(document).on('click', '#uploadpdf', function () {
		setPdfUploadState(true);
		captureReportPagesSequentially()
			.then(convert_pdf)
			.then(function (data) {
				setPdfUploadState(false);
				if ($('.btn-group').length === 0) {
					var pdfUrl = "{{ $pdf_download_url ?? URL('storage/'.$folder.'/'.$imageurl.'.pdf') }}";
					var excelUrl = "{{ route('nregister.exportExcel', $model->id) }}";
					var buttonsHtml = 
						'<button type="button" id="print" class="btn btn-secondary btn-print btn-lg ml-1">Print Page <i class="la la-paper-plane-o mr-50"></i></button>' +
						'<div class="btn-group ml-1" style="direction: ltr;">' +
							'<button type="button" class="btn btn-primary btn-print btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
								'<i class="la la-download mr-50"></i> Download' +
							'</button>' +
							'<div class="dropdown-menu dropdown-menu-right shadow-lg p-1" style="min-width: 280px; width: max-content; border-radius: 8px;">' +
								'<h6 class="dropdown-header text-bold-600 px-1 mb-0" style="color: #4B4B4B; white-space: nowrap;"><i class="la la-download"></i> Choose Format / اختر الصيغة:</h6>' +
								'<div class="dropdown-divider my-1"></div>' +
								'<a class="dropdown-item py-2 px-1" target="_blank" href="' + pdfUrl + '" style="font-size: 14px; border-radius: 5px;">' +
									'<i class="la la-file-pdf-o text-danger font-medium-3 mr-1" style="vertical-align: middle;"></i> <strong>PDF</strong> Document' +
								'</a>' +
								'<a class="dropdown-item py-2 px-1" href="' + excelUrl + '" style="font-size: 14px; border-radius: 5px;">' +
									'<i class="la la-file-excel-o text-success font-medium-3 mr-1" style="vertical-align: middle;"></i> <strong>Excel</strong> Spreadsheet' +
								'</a>' +
							'</div>' +
						'</div>';
					$('#uploadpdf').before(buttonsHtml);
				}

				toastr.info('Good Job !', (data && data.success) ? data.success : 'PDF Uploaded Successfully !', { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1500 });
				setTimeout(function () {
					window.location.reload();
				}, 600);
			})
			.catch(function (error) {
				setPdfUploadState(false);
				toastr.error(extractUploadErrorMessage(error), 'Unable to build the full PDF', { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 4000, fadeOut: 1000 });
			});
	});

	$('#print').click(function () {
		print_pdf();
	});

	$(document).bind("keyup ", function (e) {
		console.log(e.keyCode);
		if (e.keyCode == 80) {
			if (document.getElementById("print")) {
				print_pdf();
			}
			else {
				setPdfUploadState(true);
				captureReportPagesSequentially()
					.then(convert_pdf)
					.then(function (data) {
						setPdfUploadState(false);
						if ($('.btn-group').length === 0) {
							var pdfUrl = "{{ $pdf_download_url ?? URL('storage/'.$folder.'/'.$imageurl.'.pdf') }}";
							var excelUrl = "{{ route('nregister.exportExcel', $model->id) }}";
							var buttonsHtml = 
								'<button type="button" id="print" class="btn btn-secondary btn-print btn-lg ml-1">Print Page <i class="la la-paper-plane-o mr-50"></i></button>' +
								'<div class="btn-group ml-1" style="direction: ltr;">' +
									'<button type="button" class="btn btn-primary btn-print btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
										'<i class="la la-download mr-50"></i> Download' +
									'</button>' +
									'<div class="dropdown-menu dropdown-menu-right shadow-lg p-1" style="min-width: 280px; width: max-content; border-radius: 8px;">' +
										'<h6 class="dropdown-header text-bold-600 px-1 mb-0" style="color: #4B4B4B; white-space: nowrap;"><i class="la la-download"></i> Choose Format / اختر الصيغة:</h6>' +
										'<div class="dropdown-divider my-1"></div>' +
										'<a class="dropdown-item py-2 px-1" target="_blank" href="' + pdfUrl + '" style="font-size: 14px; border-radius: 5px;">' +
											'<i class="la la-file-pdf-o text-danger font-medium-3 mr-1" style="vertical-align: middle;"></i> <strong>PDF</strong> Document' +
										'</a>' +
										'<a class="dropdown-item py-2 px-1" href="' + excelUrl + '" style="font-size: 14px; border-radius: 5px;">' +
											'<i class="la la-file-excel-o text-success font-medium-3 mr-1" style="vertical-align: middle;"></i> <strong>Excel</strong> Spreadsheet' +
										'</a>' +
									'</div>' +
								'</div>';
							$('#uploadpdf').before(buttonsHtml);
						}

						toastr.info('Good Job !', (data && data.success) ? data.success : 'PDF Uploaded Successfully !', { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1500 });
						setTimeout(function () {
							window.location.reload();
						}, 600);
					})
					.catch(function (error) {
						setPdfUploadState(false);
						toastr.error(extractUploadErrorMessage(error), 'Unable to build the full PDF', { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 4000, fadeOut: 1000 });
					});
			}
			return false;
		}
	});

	$('[data-type="versions"]').each(function (key) {
		var id = $('.donw [data-type="code"]').data('id');
		var number = $(this).data('number');
		var label = String($(this).data('label') || '');
		if (number == id) {
			if (label.toUpperCase().indexOf('REV') === 0) {
				$('.donw [data-type="code"][data-id="' + id + '"]').append(' - ' + label).css('font-size', '100%');
			}
		}
	});

</script>
<!--  -->

</body>

</html>
