@extends('layouts.app')
@section('header')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/toastr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/icheck/icheck.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/toggle/switchery.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/validation/form-validation.css')}}">
@endsection
@section('header-bottom')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/toastr.css')}}">
@endsection
@section('content')
<section class="users-edit">
  <div class="card">
    <div class="card-content">
      <div class="card-body">
        <form id="create" novalidate enctype="multipart/form-data">
          <div class="row">
            <div class="col-12 col-sm-6">
              <h5 class="mb-1"><i class="ft-user mr-25"></i>Personal Info</h5>
            </div>
          </div>
          <?php
            $emad = substr($last_id,4);
          ?>
          <div class="row">
            <div class="col-12 col-sm-6">
              <div class="form-group">
                  <div class="controls">
                      <label>Code</label>
                      <input type="text" id="code" name="code" class="form-control" placeholder="Code" value="SUP-{{str_pad($emad+1, 3,'0',STR_PAD_LEFT)}}" required="" data-validation-required-message="This code field is required" aria-invalid="false" disabled>
                  <div class="help-block"></div></div>
              </div>
              <div class="form-group">
                  <div class="controls">
                      <label>Name</label>
                      <input type="text" name="uname" class="form-control" placeholder="Supplier Name" value="" required="" data-validation-required-message="This name field is required" aria-invalid="false">
                  <div class="help-block"></div></div>
              </div>
            </div>
            <div class="col-12 col-sm-6 mt-1 mt-sm-0">
              <div class="form-group">
                  <div class="controls">
                      <label>E-mail</label>
                      <input type="email" name="uemail" class="form-control" placeholder="Supplier Email" value="" required="" data-validation-required-message="This email field is required" aria-invalid="false">
                  <div class="help-block"></div></div>
              </div>
              <div class="form-group">
                  <div class="controls">
                      <label>Telephone</label>
                      <input type="tel" name="utel" class="form-control" placeholder="Supplier Telephone" value="" required="" data-validation-required-message="This telephone field is required" aria-invalid="false">
                  <div class="help-block"></div></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-6">
              <h5 class="mb-1"><i class="ft-user mr-25"></i>Additional Info</h5>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                  <label>Location</label>
                  <input type="text" name="address" class="form-control" placeholder="Supplier Address" aria-invalid="false">
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
                  <div class="controls">
                      <label>WebSite Url</label>
                      <input type="url" class="form-control" name="url" id="url" placeholder="WebSite Url" >
                  <div class="help-block"></div></div>
              </div>
            </div>
            <div class="col-12 col-sm-6 mt-1 mt-sm-0">
              <div class="form-group">
                <div class="controls">
                    <label>Upload Logo</label>
                    <div class="custom-file">
                        <input type="file" name="logo" class="custom-file-input" id="inputGroupFile02">
                        <label class="custom-file-label" style="height: 2.80rem;" for="inputGroupFile02" aria-describedby="inputGroupFile02">Choose file</label>
                    </div>
                <div class="help-block"></div></div>

              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                  <label>Desc.</label>
                  <input type="text" name="desc" class="form-control" placeholder="Supplier Description" aria-invalid="false">
              </div>
            </div>
          </div>
          <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
              <button id="submit" type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1 waves-effect waves-light"><i class="la la-check-square-o"></i> Save
                  changes</button>
              <button type="reset" class="btn btn-light waves-effect waves-light">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection

@section('footer')
<script src="{{asset('app-assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/forms/icheck/icheck.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/extensions/toastr.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/forms/toggle/bootstrap-switch.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/forms/toggle/switchery.min.js')}}"></script>
<script src="{{asset('app-assets/js/scripts/forms/validation/form-validation.js')}}"></script>
@endsection

@section('ajax')
<script>
  /*var modules;
  var roles = [];
  var permissions = [];

  $('.custom-control-input').prop( "disabled", true );
  var elems = document.querySelectorAll('input.switchery');
  for (var i = 0; i < elems.length; i++) {
    var changeCheckbox = document.querySelector('#'+elems[i].id);
    changeCheckbox.onchange = function() {
      var parent_id = $(this).closest('.top');
      if(this.checked == true){
        modules = this.id;
        permissions.push({
            modules : modules,
            roles : [],
        });
        parent_id.find('.custom-control-input').prop( "disabled", false );
      }else{
        var v = this.id;
        index = permissions.findIndex(x => x.modules === v);
        permissions.splice(index, 1);
        parent_id.find('.custom-control-input').prop( "disabled", true ).iCheck( "uncheck");
      }
    };
  }

  $('.custom-control-input').on('ifChecked', function(event){
    var parent_id = $(this).closest('.top').find('.switchery').attr('id');
    roles = $(this).attr('id').replace(parent_id+"-", "");
    $.each(permissions, function (i, value) {
        if(value.modules === parent_id){
          value.roles.push(roles);
        }
    });
  });

  $('.custom-control-input').on('ifUnchecked', function(event){
    var parent_id = $(this).closest('.top').find('.switchery').attr('id');
    var v = $(this).attr('id').replace(parent_id+"-", "");
    $.each(permissions, function (i, value) {
        if(value.modules === parent_id){
            $.each(value.roles, function (ii, value1) {
                if(value1===v){
                    value.roles.splice(ii, 1);
                }
            });
        }
    });
  });*/

  $("#create").submit(function(stay){
    stay.preventDefault();
    var form = $(this)[0];
    var formdata = new FormData(form);
    formdata.append('code', $('#code').val());
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      url: "{{route('supplier.store')}}",
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
