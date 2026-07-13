@extends('layouts.app')

@section('header')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/ui/jquery-ui.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/toastr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/icheck/icheck.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/toggle/switchery.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/validation/form-validation.css')}}">
@endsection

@section('header-bottom')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/toastr.css')}}">
<style>
  * {
    -webkit-print-color-adjust: exact !important;   /* Chrome, Safari, Edge */
    color-adjust: exact !important;                 /*Firefox*/
  }
  .app-content .wizard.wizard-circle > .steps > ul > li.current ~ li:before, .app-content .wizard.wizard-circle > .steps > ul > li.current ~ li:after, .app-content .wizard.wizard-circle > .steps > ul > li.current:after{ background-color: #fff; }
  p { letter-spacing: inherit; margin-bottom: 0 !important; }
  .custom-control-label{ margin-bottom: 5px;}
  .custom-control-label::after{ top: 0 !important; }
  .skin-square label{ margin-bottom: auto; }
  .bg-dark{ background-color: #d9d9d9 !important; }
  .white{ color: #0a0a0a !important;}
  .logo-top{position: absolute; max-width: 45%;}
  .border-dark{ padding-top: 3px !important; padding-bottom: 3px !important; }
  .card-body{ padding: 1rem 2rem; }
  .middle{ display: flex; align-items: center; }
  .mid11{ display: flex; align-items: center; }
  .just{ justify-content: center; }
  .noncheckedfrom{ background: url({{asset('app-assets/vendors/css/forms/icheck/square/purple.png')}}) -1px -1px no-repeat; display: inline-block; margin-right: 0.6rem; width: 17px; height: 17px; border: 1px solid #d9d9d9; }
  .checked{ background-position: -51px -3px; border-color: #6a5a8c; }
  .mid{ vertical-align: middle; display: flex; padding-top: 3px; padding-bottom: 3px; }
  .text-16, .skin-square label{ font-size: 16px; }
  @page{ margin: 0 !important; padding: 0 !important; margin-right: 5mm !important; margin-left: 5mm !important; }
  @media print{
    *{ -webkit-print-color-adjust: exact !important; color-adjust: exact !important; }
    #emad{ font-size: 13px !important; padding-bottom: 2px !important;}
    .no-print, .no-print *{ display: none !important; }
    .vertical-compact-menu.menu-open .content, .vertical-compact-menu.menu-open .footer{ margin-left: 0 !important;}
    .bg-dark{ -webkit-print-color-adjust: exact; }
    html body .content .content-wrapper{ margin: 0 !important; padding: 0 !important;  }
    p{ font-size: 13px !important; }
    h5{ font-size: 14px !important; }
    #emad{ font-size: 12px !important;}
    .header-top{ position: fixed; width: 100%; margin-top: 5mm; top: 0;}
    label{ font-size: 0.9rem; }
  }
</style>
@endsection

@section('content')
<section class="validation">
  <div id="donw" class="card">
    <div class="card-content">
      <div class="card-body">
        <!-- -->
        @include('layouts.paperheader')
        <h2 class="text-bold-600 text-center mt-1 mb-2">Service Ticket</h2>
        <!-- -->
        <div class="row">
          <div class="col-md-2 bg-dark border-dark">
            <h5 class="mb-0 white text-bold-700">Client</h5>
          </div>
          <div class="col-md-6 border-dark black middle text-16">
            {{$serviceTicket->jobRequest->client->name}}
          </div>
          <div class="col-md-2 bg-dark border-dark">
            <h5 class="mb-0 white text-bold-700">Start Date</h5>
          </div>
          <div class="col-md-2 border-dark black middle text-16">
            {{$serviceTicket->start}}
          </div>
        </div>
        <!-- -->
        <div class="row">
          <div class="col-md-2 bg-dark border-dark">
            <h5 class="mb-0 white text-bold-700">Location</h5>
          </div>
          <div class="col-md-6 border-dark black middle text-16">
            {{$serviceTicket->jobRequest->client->location}}
          </div>
          <div class="col-md-2 bg-dark border-dark">
            <h5 class="mb-0 white text-bold-700">End Date</h5>
          </div>
          <div class="col-md-2 border-dark black middle text-16">
            {{$serviceTicket->end}}
          </div>
        </div>
        <!-- -->
        <div class="row">
          <div class="col-md-2 bg-dark border-dark">
            <h5 class="mb-0 white text-bold-700">JCF Number</h5>
          </div>
          <div class="col-md-2 border-dark black middle text-16">
            {{$serviceTicket->jobRequest->code}}
          </div>
          <div class="col-md-2 bg-dark border-dark">
            <h5 class="mb-0 white text-bold-700">Qutation Number</h5>
          </div>
          <div class="col-md-2 border-dark black middle text-16">
            {{$serviceTicket->jobRequest->qutation->code}}
          </div>
          <div class="col-md-2 bg-dark border-dark">
            <h5 class="mb-0 white text-bold-700">P.K Slip Number</h5>
          </div>
          <div class="col-md-2 border-dark black middle text-16">
            {{$serviceTicket->jobRequest->code}}
          </div>
        </div>
        <!-- -->
        <div class="row mt-1">
          <div class="col-4 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
            <h6 class="text-bold-600 mb-0">Service Description</h6>
          </div>
          <div class="col-2 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
            <h6 class="text-bold-600 mb-0">Quantity</h6>
          </div>
          <div class="col-2 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
            <h6 class="text-bold-600 mb-0">Size</h6>
          </div>
          <div class="col-2 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
            <h6 class="text-bold-600 mb-0">Range</h6>
          </div>
          <div class="col-2 border-dark bg-dark white" style="padding-top: 5px; padding-bottom: 5px;">
            <h6 class="text-bold-600 mb-0">Connection Type</h6>
          </div>
        </div>
        @foreach(json_decode($serviceTicket->services) as $service)
        <div class="row">
          <div class="col-4" style="padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #000;">{{$service->descTextarea}}</div>
          <div class="col-2" style="padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #000;">{{$service->pquantity}}</div>
          <div class="col-2" style="padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #000;">{{$service->size}}</div>
          <div class="col-2" style="padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #000;">{{$service->range}}</div>
          <div class="col-2" style="padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #000;">{{$service->ctype}}</div>
        </div>
        @endforeach
        <!-- -->
        <div class="row mt-1">
            <h5 class="col-12 mb-0 white text-bold-700 bg-dark border-dark" style="width: 100%;">Notice:(To mention any notices during the job here)</h5>
            <p class="col-12 border-dark black middle text-16">{{$serviceTicket->notice}}</p>
        </div>
        <!-- -->
        <div class="row mt-1 ">
            <h5 class="white text-bold-700 col-6 mid" style="padding: 0;">RSE Representative</h5>
            <h5 class="white text-bold-700 col-6 mid" style="padding: 0;">Client Approval</h5>
        </div>
        <div class="row">
          <div class="col-md-6" style="padding-right: 14px;">
            <div class="row">
              <div class="col-md-4 bg-dark border-dark">
                <h5 class="mb-0 white text-bold-700">Name</h5>
              </div>
              <div class="col-md-8 border-dark black middle text-16">
                {{$serviceTicket->user->employee->name}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 bg-dark border-dark">
                <h5 class="mb-0 white text-bold-700">Signature</h5>
              </div>
              <div class="col-md-8 border-dark black middle text-16" style="min-height: 70px;">
                <img class="media-object" src="{{Storage::url('employees/')}}{{$serviceTicket->user->employee->esign}}" alt="" style="max-width: 50%;">
              </div>
            </div>
            <div class="row  ">
              <div class="col-md-4 bg-dark border-dark">
                <h5 class="mb-0 white text-bold-700 text-16">Date</h5>
              </div>
              <div class="col-md-8 border-dark middle">
                {{$serviceTicket->created_at}}
              </div>
            </div>

          </div>
          <!-- -->
          <div class="col-md-6">
            <div class="row  ">
              <div class="col-md-4 bg-dark border-dark">
                <h5 class="mb-0 white text-bold-700 text-16">Name</h5>
              </div>
              <div class="col-md-8 border-dark middle text-16 black">
                {{$serviceTicket->jobRequest->client->name}}
              </div>
            </div>
            <div class="row  ">
              <div class="col-md-4 bg-dark border-dark">
                <h5 class="mb-0 white text-bold-700 text-16">Signature</h5>
              </div>
              <div class="col-md-8 border-dark middle" style="min-height: 70px;">

              </div>
            </div>
            <div class="row  ">
              <div class="col-md-4 bg-dark border-dark">
                <h5 class="mb-0 white text-bold-700 text-16">Date</h5>
              </div>
              <div class="col-md-8 border-dark middle">
                {{$serviceTicket->approval_date}}
              </div>
            </div>

          </div>
          <!-- -->
        </div>
        <!-- -->
        <div class="row mt-1">
          <div class="col-4 text-bold-600 pl-0 middle">
            Form # RSE-GF-01 - ISSUE 05 / Aug 2021
          </div>
          <div class="col-5">
            <img src="{{asset('app-assets/images/footer.jpg')}}" style="max-width: 100%;" />
          </div>
          <div class="col-3 text-right text-bold-600 middle" style="justify-content: flex-end;">
            Page 1 of 1
          </div>
        </div>
        <!-- -->
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-content">
      <div class="card-body">
        <div class="row no-print">
          <button type="button" id="print" class="btn btn-info btn-print btn-lg mr-1"><i class="la la-paper-plane-o mr-50"></i>Print JCF</button>
          <button type="button" id="pdf" class="btn btn-info btn-print btn-lg"><i class="la la-paper-plane-o mr-50"></i>Convert To PDF</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('footer')
<script src="{{asset('app-assets/vendors/js/extensions/toastr.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script>
  $('#print').click(function(){
     window.print();
     return false;
  });

  $(document).on('click','#pdf',function(){
    let pdf = new jsPDF({
       orientation: 'p',
       unit: 'mm',
       format: 'a4',
       putOnlyUsedFonts:true,
       autoSize:true,
    });
    let section=$('#donw');
    var options = {
        format: 'PNG',
        "background": '#000',
    };
    pdf.addHTML(section, 0, 15, options,function(){
      var blob = pdf.output('blob', 'serviceTicket-{{$serviceTicket->code}}.pdf');
      var formData = new FormData();
      formData.append('pdf', blob);
      formData.append('name', 'serviceTicket-{{$serviceTicket->code}}.pdf');
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: "{{route('serviceTicket.generatePdf')}}",
        cache: false,
        data: formData,
        processData: false,
        contentType: false,
        success: function(data){
          toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000 });
        },
      });
    });
  });

</script>
@endsection
