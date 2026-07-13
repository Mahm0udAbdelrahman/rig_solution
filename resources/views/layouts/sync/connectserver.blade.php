@extends('layouts.app')
@section('header')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/toastr.css')}}">
<style>
  .position-relative.has-icon-left .form-control{ padding-left: 3rem !important; }
</style>
@endsection
@section('header-bottom')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/toastr.css')}}">
@endsection
@section('content')
<div class="content-body">
    <section class="row flexbox-container">
        <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">
                <div class="card border-grey border-lighten-3 px-2 py-2 m-0">
                    <div class="card-header border-0 pb-0">

                        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2"><span>Enter Password To Start Sync.</span></h6>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form-horizontal" id="create" novalidate>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="password" class="form-control" id="password" required>

                                    <div class="form-control-position">
                                        <i class="la la-lock"></i>
                                    </div>
                                </fieldset>
                                <button type="submit" class="btn btn-outline-info btn-lg btn-block"><i class="ft-unlock"></i> Enter Password</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>
@endsection
@section('footer')
<script src="{{asset('app-assets/vendors/js/extensions/toastr.min.js')}}"></script>
@endsection

@section('ajax')
<script>
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'GET',
    url: "{{route('queue.start')}}",
    processData: false,
    contentType: false,
    cache: false,
    dataType: "JSON",
    beforeSend:function(){
      $('#submit i').addClass('la la-refresh spinner');
    },
    success: function (data){
      toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000 });
    },
    error: function (ajaxContext) {

    }
  });

  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'GET',
    url: "{{route('socket.start')}}",
    processData: false,
    contentType: false,
    cache: false,
    dataType: "JSON",
    beforeSend:function(){
      $('#submit i').addClass('la la-refresh spinner');
    },
    success: function (data){
      toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000 });
    },
    error: function (ajaxContext) {

    }
  });

  $("#create").submit(function(stay){
    stay.preventDefault();
    var form = $(this)[0];
    var formdata = new FormData(form);
    formdata.append('password', $('#password').val());
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      url: "{{route('system.get_api_access_token')}}",
      processData: false,
      contentType: false,
      cache: false,
      data: formdata,
      dataType: "JSON",
      beforeSend:function(){
        $('#submit i').addClass('la la-refresh spinner');
      },
      success: function (data){
        toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { window.location.reload(); }  });
      },
    });
  });
</script>
@endsection
