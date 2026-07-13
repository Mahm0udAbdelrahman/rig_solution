@extends('layouts.app')
@section('header')
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/ui/jquery-ui.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/toastr.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/icheck/icheck.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/toggle/switchery.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/validation/form-validation.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/checkboxes-radios.css')}}">
@endsection
@section('header-bottom')
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/toastr.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/selects/select2.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/ui/jqueryui.css')}}">
  <style>
    table.titems .select2-container--classic .select2-selection--single, table.titems .select2-container--default .select2-selection--single, table.titems input[type="number"], table.titems input[type="text"]{ border: none !important; background: none !important;  }
    table.titems .select2-container--classic .select2-selection--single, table.titems .select2-container--default .select2-selection--single, table.titems .select2-container .select2-selection--single .select2-selection__rendered{ padding-left: inherit !important; }
  </style>
@endsection
@section('content')
<section id="form-repeater">
  <form id="create" class="form emad repeater-emad" novalidate>
    @method('PUT')
    <input type="hidden" id="ccode" name="ccode" value="S.T-{{$jobRequest->code}}" />
    <div class="card border-cyan border-lighten-4">
      <div class="card-content">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="controls">
                  <label id="csname">JCF Number</label>
                  <select class="form-control csd searchable-select" id="client" name="csd" required disabled>
                    <option value="">Select Value</option>
                    @foreach($jcfs as $jcf)
                      <option value="{{$jcf->id}}"
                        @if($jobRequest->id === $jcf->id)
                          selected
                        @endif
                        >{{$jcf->code}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <fieldset class="col-md-6 form-group">
              <label>Start Date</label>
              <div class="input-group">
                  <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ft-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control dp-date-range-from" name="from" value="{{$jobRequest->serviceTicket->start}}" />
              </div>
            </fieldset>
            <fieldset class="col-md-6 form-group">
              <label>End Date</label>
              <div class="input-group">
                  <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ft-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control dp-date-range-to" name="to"  value="{{$jobRequest->serviceTicket->end}}" />
              </div>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-content collapse show">
            <div id="invoice-template" class="card-body">
              <div id="invoice-items-details" class="pt-2">
                <div class="row repeater">
                  <div class="table-responsive col-12">
                    <table class="table titems">
                      <thead>
                        <tr>
                          <th class="col-md-4">Service Description</th>
                          <th class="col-md-2 text-right">Quantity</th>
                          <th class="col-md-2 text-right">Size</th>
                          <th class="col-md-2 text-right">Range</th>
                          <th class="col-md-2 text-right">Connection Type</th>
                          <th class="col-md-1"></th>
                        </tr>
                      </thead>
                      <tbody data-repeater-list="items">

                        @foreach(json_decode($jobRequest->serviceTicket->services) as $service)

                        <tr data-repeater-item>
                          <td class="col-md-4">
                            <textarea class="form-control" name="descTextarea" rows="2" required="" data-validation-required-message="This field is required" placeholder="Job Required Details" aria-invalid="false">{{$service->descTextarea}}</textarea>
                          </td>
                          <td class="col-md-2">
                            <input type="number" placeholder="0" name="pquantity" class="form-control pquantity text-center" min=1 required data-validation-required-message="This field is required" value="{{$service->pquantity}}" style="padding: 0;">
                          </td>
                          <td class="col-md-2">
                            <input type="text" placeholder="0" name="size" class="form-control price text-center" required data-validation-required-message="This field is required" value="{{$service->size}}" style="padding: 0;">
                          </td>
                          <td class="col-md-2 text-right">
                            <input type="text" placeholder="0" name="range" class="form-control pamount text-center" min=1 required data-validation-required-message="This field is required" value="{{$service->range}}" style="padding: 0;">
                          </td>
                          <td class="col-md-2 text-right">
                            <input type="text" placeholder="0" name="ctype" class="form-control pamount text-center" min=1 required data-validation-required-message="This field is required" value="{{$service->ctype}}" style="padding: 0;">
                          </td>
                          <td class="col-md-1"><button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button></td>
                        </tr>

                        @endforeach


                      </tbody>
                    </table>
                    <div class="form-group overflow-hidden">
                      <div class="col-12">
                        <button type="button" data-repeater-create class="btn btn-primary"><i class="ft-plus"></i> Add</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-content">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <label id="notice">Notice:(To mention any notices during the job here)</label>
              <textarea class="form-control" id="notice" name="notice" rows="2" placeholder="Notice:(To mention any notices during the job here)" aria-invalid="false">{{$jobRequest->serviceTicket->notice}}</textarea>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
    <div class="card-content">
      <div class="card-body">
        <div class="row">
          <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
            <button id="submit" type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1 waves-effect waves-light"><i class="la la-check-square-o"></i> Save
            changes</button>
            <button type="reset" class="btn btn-light waves-effect waves-light">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  </form>
</section>
@endsection

@section('footer')
  <script src="{{asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
  <script src="{{asset('app-assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
  <script src="{{asset('app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
  <script src="{{asset('app-assets/vendors/js/forms/icheck/icheck.min.js')}}"></script>
  <script src="{{asset('app-assets/vendors/js/extensions/toastr.min.js')}}"></script>
  <script src="{{asset('app-assets/vendors/js/forms/toggle/bootstrap-switch.min.js')}}"></script>
  <script src="{{asset('app-assets/vendors/js/forms/toggle/switchery.min.js')}}"></script>
  <script src="{{asset('app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js')}}"></script>
@endsection

@section('ajax')
  <script src="{{asset('app-assets/js/scripts/forms/validation/form-validation.js')}}"></script>
  <script src="{{asset('app-assets/js/scripts/forms/form-repeater.js')}}"></script>
  <script src="{{asset('app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
  <script src="{{asset('app-assets/js/scripts/ui/jquery-ui/date-pickers.js')}}"></script>
  <script>
  $('.repeater').repeater({
    show: function () {
      $(this).slideDown();
    },
    hide: function(remove) {
      if (confirm('Are you sure you want to remove this item?')) {
        $(this).slideUp(remove);
      }
    }
  });

  $("#create").submit(function(stay){
    stay.preventDefault();
    var form = $(this)[0];
    var formdata = new FormData(form);
    formdata.append('ccode', $('#ccode').val());
    formdata.append('csd', $('#client option:selected').val());
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      url: "{{route('serviceTicket.update', $jobRequest->serviceTicket->id)}}",
      processData: false,
      contentType: false,
      cache: false,
      data: formdata,
      dataType: "JSON",
      beforeSend:function(){
        $('#submit i').addClass('la la-refresh spinner');
      },
      success: function (data){
        toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { window.location.replace("{{route('serviceTicket.index')}}"); }  });
      },
    });
  });
  </script>
@endsection

