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
      url: "{{route('packingSlip.store')}}",
      processData: false,
      contentType: false,
      cache: false,
      data: formdata,
      dataType: "JSON",
      beforeSend:function(){
        $('#submit i').addClass('la la-refresh spinner');
      },
      success: function (data){
        toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { window.location.replace("{{route('packingSlip.index')}}"); }  });
      },
    });
  });
  </script>
@endsection

