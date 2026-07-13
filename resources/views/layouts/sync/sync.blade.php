@extends('layouts.app')

@section('content')
    <section class="users-list-wrapper">
      <h5 class="mb-1"><i class="ft-user mr-25"></i>Choose What You Need To Do ?</h5>
      <div class="row">
          <!-- <div class="col-4">
              <button type="button" id="fromserver" class="btn btn-info btn-print btn-lg mr-1 waves-effect waves-light approve col-4 mb-1"><i class="la la-paper-plane-o mr-50"></i>Update Data From Online Server ?</button>
              <div class="card">
                  <div class="card-content">
                      <div class="card-body">
                          <div class="row">

                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-8">
              <button type="button" id="toserver" class="btn btn-info btn-print btn-lg mr-1 waves-effect waves-light approve col-4 mb-1"><i class="la la-paper-plane-o mr-50"></i>Sync Your New Work To Our Server ?</button>
              <div class="card">
                  <div class="card-content">
                      <div class="card-body">
                          <div class="row">

                          </div>
                      </div>
                  </div>
              </div>
          </div> -->
      </div>
    </section>
@endsection

@section('ajax')
<script>

    // $("#fromserver").click(function(){
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        url: "{{route('system.sync_from_server_to_local')}}",
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
      });
      // Echo.channel('App.Models.User.{{Auth::id()}}')
      // .notification((notification) => {
      //     alert();
      // });
    // });

    // $("#toserver").click(function(){
    //   $.ajax({
    //     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //     type: 'POST',
    //     url: "{{route('system.sync')}}",
    //     processData: false,
    //     contentType: false,
    //     cache: false,
    //     dataType: "JSON",
    //     beforeSend:function(){
    //       $('#submit i').addClass('la la-refresh spinner');
    //     },
    //     success: function (data){
    //       toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000 });
    //     },
    //   });
    //   Echo.channel('App.Models.User.{{Auth::id()}}')
    //   .notification((notification) => {
    //       alert();
    //   });
    // });
</script>
@endsection
