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
@yield('header')
<!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/material.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/material-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/material-colors.css')}}">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
          href="{{asset('app-assets/css/core/menu/menu-types/material-vertical-compact-menu.css')}}">
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
<body class="vertical-layout vertical-compact-menu material-vertical-layout material-layout 2-columns fixed-navbar"
      data-open="click" data-menu="vertical-compact-menu" data-col="2-columns">


<div class="card no-print mt-2">
    <div class="card-content fixed-top">
        <div class="card-body">
            <div class="row" style="direction: rtl;">
                @if (Storage::disk('public')->exists($folder.'/'.$imageurl.'.pdf'))
                    <button type="button" id="print" class="btn btn-secondary btn-print btn-lg ml-1">Print Page <i class="la la-paper-plane-o mr-50"></i></button>
                @endif

                <div class="btn-group ml-1" style="direction: ltr;">
                    <button type="button" class="btn btn-primary btn-print btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="la la-download mr-50"></i> Download <i class="la la-angle-down ml-50"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if (Storage::disk('public')->exists($folder.'/'.$imageurl.'.pdf'))
                            <a class="dropdown-item" target="_blank" href="{{URL('storage/'.$folder.'/'.$imageurl.'.pdf')}}">
                                <i class="la la-file-pdf-o text-danger mr-1" style="font-size: 1.2rem;"></i> Download PDF
                            </a>
                        @else
                            <span class="dropdown-item disabled text-muted">
                                <i class="la la-file-pdf-o mr-1" style="font-size: 1.2rem;"></i> Download PDF (Upload PDF first)
                            </span>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('lregister.exportExcel', $lregister->id) }}">
                            <i class="la la-file-excel-o text-success mr-1" style="font-size: 1.2rem;"></i> Download Excel
                        </a>
                    </div>
                </div>

                <button type="button" id="uploadpdf" class="btn btn-dark btn-print btn-lg">Upload / Update PDF <i class="la la-paper-plane-o"></i></button>
            </div>
        </div>
    </div>
</div>
<br/>
<!-- BEGIN: Content-->
@php  $index = 1; @endphp
@foreach($subCollections as $subCollection)
@include('layouts.inspection.lifting.lregister.pdf.pdf-page', ['r' => $subCollection, 'page_number' => 'Page '.$index.' of '.$total, 'id'=> 'page_'.$index])
@php $index++; @endphp
@endforeach
<!-- END: Content-->
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
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

<script src="{{asset('app-assets/js/html2canvas.js')}}"></script>
<script src="{{asset('app-assets/js/jspdf.js')}}"></script>
<script>

  function setPdfUploadState(isLoading) {
    var $icon = $('#uploadpdf i');
    $('#uploadpdf').prop('disabled', isLoading);
    if (isLoading) {
      $icon.removeClass('la la-paper-plane-o').addClass('la la-refresh spinner');
      return;
    }

    $icon.removeClass('la la-refresh spinner').addClass('la la-paper-plane-o');
  }

  function captureReportPagesSequentially() {
    var captureElements = Array.prototype.slice.call(document.querySelectorAll('.donw'));
    var images = [];

    return captureElements.reduce(function (chain, element) {
      return chain.then(function () {
        return html2canvas(element, {
          backgroundColor: '#ffffff',
          scale: 2,
          useCORS: true,
          scrollX: 0,
          scrollY: 0,
          logging: false
        }).then(function (canvas) {
          images.push(canvas.toDataURL('image/png').replace('image/png', 'image/octet-stream'));
        });
      });
    }, Promise.resolve()).then(function () {
      if (!images.length || images.length !== captureElements.length) {
        throw new Error('capture_incomplete');
      }

      return images;
    });
  }

  function uploadCapturedSnapshots(imagesArray) {
    var formData = new FormData();
    formData.append('imageurl', '{{$imageurl}}');
    formData.append('folder', '{{$folder}}');
    formData.append('image', JSON.stringify(imagesArray));

    return $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type: 'POST',
      url: "{{ route('report.makeImageForPdf', $for_approve_url) }}",
      cache: false,
      data: formData,
      processData: false,
      contentType: false
    }).then(function () {
      return imagesArray;
    });
  }

  function convert_pdf(images)
  {
    if (!images || !images.length) {
      return Promise.reject(new Error('pdf_images_missing'));
    }

    var doc = new jspdf.jsPDF('p', 'mm', 'a4',true);
    const pageWidth = 210;
    const pageHeight = 297;

    for (var i = 0; i < images.length; i++) {
      doc.addImage(images[i], 'PNG', 0, 0, pageWidth, pageHeight, 'alias' + i, 'FAST');
      if(i < images.length - 1){
        doc.addPage();
      }
    }
    var blob = doc.output('blob');

    var formData = new FormData();
    formData.append('folder', '{{$folder}}');
    formData.append('imageurl', '{{$imageurl}}');
    formData.append('pdf', blob, 'lregister-report.pdf');
    @if(!empty($for_approve_url))
    formData.append('report_id', '{{$for_approve_url}}');
    @endif
    return $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      url: "{{route('report.generatePdf')}}",
      cache: false,
      data: formData,
      processData: false,
      contentType: false,
      success: function(data){
        toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () {
          window.location.reload();
        } });
      },
      error: function(xhr){
        var message = 'Upload failed';
        if (xhr.responseJSON && xhr.responseJSON.errors) {
          message = Object.values(xhr.responseJSON.errors).flat().join(' | ');
        }
        toastr.error(message, 'The pdf failed to upload.', { positionClass: 'toast-bottom-left', showMethod: 'slideDown', hideMethod: 'slideUp', progressBar: true, timeOut: 4000, fadeOut: 1000 });
      },
    });
  }

  function print_pdf()
  {
    printWindow = window.open("{{URL('storage/'.$folder.'/'.$imageurl.'.pdf')}}");
    printWindow.window.print();
  }

  $(document).on('click','#uploadpdf',function(){
    setPdfUploadState(true);
    captureReportPagesSequentially()
      .then(uploadCapturedSnapshots)
      .then(convert_pdf)
      .then(function () {
        setPdfUploadState(false);
      })
      .catch(function () {
        setPdfUploadState(false);
        toastr.error('Capture failed', 'Unable to build the full PDF', {
          positionClass: 'toast-bottom-left',
          showMethod: 'slideDown',
          hideMethod: 'slideUp',
          progressBar: true,
          timeOut: 3000,
          fadeOut: 1000
        });
      });
  });

  $('#print').click(function(){
    print_pdf();
  });

  $(document).bind("keyup ", function(e){
    console.log(e.keyCode);
    if (e.keyCode == 80)
    {
      if (document.getElementById("print"))
      {
        print_pdf();
      }
      else
      {
        setPdfUploadState(true);
        captureReportPagesSequentially()
          .then(uploadCapturedSnapshots)
          .then(convert_pdf)
          .then(function () {
            setPdfUploadState(false);
          })
          .catch(function () {
            setPdfUploadState(false);
            toastr.error('Capture failed', 'Unable to build the full PDF', {
              positionClass: 'toast-bottom-left',
              showMethod: 'slideDown',
              hideMethod: 'slideUp',
              progressBar: true,
              timeOut: 3000,
              fadeOut: 1000
            });
          });
      }
      return false;
    }
  });

  $('[data-type="versions"]').each(function(key){
    var id = $('.donw [data-type="code"]').data('id');
    var number = $(this).data('number');
    if(number == id)
    {
      $('.donw [data-type="code"][data-id="'+ id +'"]').append(' - REV: ' + String(++key).padStart(2,"0")).css('font-size', '100%');
    }
  });

</script>

</body>
<!-- END: Body-->

</html>

