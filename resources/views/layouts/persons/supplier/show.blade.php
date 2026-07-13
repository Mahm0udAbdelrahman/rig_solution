@extends('layouts.app')
@section('header')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/toastr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/extensions/fixedHeader.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/validation/form-validation.css')}}">
@endsection
@section('header-bottom')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/toastr.css')}}">
<style>
form .form-actions {
  border-top: none;
  padding: 0 0 20px 0;
  margin-top: 0;
}
</style>
@endsection
@section('content')
<section class="users-list-wrapper">
<div class="row">
<div class="col-md-9">
<div class="card">
<div class="card-content">
<div class="card-body">
<div class="media-list media-bordered">
<div class="media">
<h4 class="media-head text-uppercase">{{$supplier->name}}</h4>
</div>
<div class="media">
<div class="media-left">
<img class="media-object" src="{{Storage::url('suppliers/')}}{{$supplier->logo}}" alt="Supplier Logo" width="120">
</div>
<div class="media-body">
<div class="media-heading text-bold-600 text-uppercase">Code : <span>{{$supplier->code}}</span></div>
<p>{{$supplier->desc}}</p>
<span class="badge bg-teal float-right"  style="font-size:100%;"><i class="la la-map-marker"></i> {{$supplier->location}}</span>
</div>
</div>
<div class="media">
<a href="mailto: {{$supplier->email}}" class="btn mr-1 mb-1 btn-primary"><i class="la la-envelope-o"></i> Send E-mail</a>
@if($supplier->url)
<a href="{{$supplier->url}}" target="_blank" class="btn mr-1 mb-1 btn-secondary"><i class="la la-eye"></i> WebSite URL</a>
@endif
<span class="dropdown">
<button id="btnGroupVerticalDrop6" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="btn btn-info dropdown-toggle dropdown-menu-right"><i class="la la-phone-square"></i> Make A Call</button>
<span aria-labelledby="btnSearchDrop25" class="dropdown-menu mt-1 dropdown-menu-right">
<a class="dropdown-item" href="tel: {{$supplier->tel}}"><i class="la la-arrow-right"></i> {{$supplier->tel}}</a>
</span>
</span>
</div>
</div>
</div>
</div>
</div>
<blockquote class="blockquote border-left-teal border-left-3 border-0-right blockquote-reverse">
<div class="media">
<div class="media-body pl-1">
<h3 class="text-uppercase">{{$supplier->name}} Contact Persons</h3>
</div>
</div>
</blockquote>
<div class="row">
<div class="col-sm-12">
<div class="card">
<div class="card-content">
<div class="card-header">
<div class="card-body">
<div class="table-responsive">
<table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive">
<thead>
<tr>
<th>Name</th>
<th>Job Title</th>
<th>E-mail</th>
<th>Phone</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($supplier->conatctPerson as $responsible)
<tr id="{{$responsible->id}}">
<td>{{$responsible->name}}</td>
<td>{{$responsible->postion}}</td>
<td>{{$responsible->email}}</td>
<td>{{$responsible->tel}}</td>
<td>
<div class="form-group text-left" style="margin-bottom: auto;">
<button type="button" data-id="{{$responsible->id}}" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>
</div>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card">
<div class="card-content collpase show">
<div class="card-body">
<form id="create" class="form-horizontal" novalidate>
<input type="hidden" name="supplierid" value="{{$supplier->id}}" />
<div class="form-body">
<h4 class="form-section"><i class="ft-user"></i> Add Contact Person</h4>
<div class="form-group">
<div class="controls">
<input type="text" id="sname" class="form-control" placeholder="Name" name="sname" required data-validation-required-message="This name field is required">
</div>
</div>
<div class="form-group">
<div class="controls">
<input type="text" id="stitle" class="form-control" placeholder="Job Title" name="stitle" />
</div>
</div>
<div class="form-group">
<div class="controls">
<input type="email" id="semail" class="form-control" placeholder="E-mail" name="semail" />
</div>
</div>
<div class="form-group">
<div class="controls">
<input type="tel" placeholder="Telephone" name="stel" class="form-control" id="stel" required data-validation-required-message="This telephone field is required">
</div>
</div>
</div>
<div class="form-actions">
<button id="submit" type="submit" class="btn btn-primary mr-1">
<i class="la la-check-square-o"></i> Save Changes
</button>
<button type="button" class="btn btn-light">
<i class="ft-x"></i> Cancel
</button>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</section>
@endsection
@section('footer')
<script src="{{asset('app-assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/extensions/toastr.min.js')}}"></script>
<script src="{{asset('app-assets/js/scripts/forms/validation/form-validation.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
@endsection
@section('ajax')
<script>
/**********************************/
var table;
const createdCell = function(cell) {
	let original;
  cell.addEventListener("click", function(e) {
		original = e.target.textContent
    cell.setAttribute('contenteditable', true)
	})
  cell.addEventListener("focusout", function(e) {
		if (original !== e.target.textContent) {
	    const row = table.row(e.target.parentElement)
      cell.setAttribute('contenteditable', true)
    	row.invalidate()
      var url = "{{ route('contactPerson.update', ':selectIds') }}";
      url = url.replace(':selectIds', e.target.parentElement.id);
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'PUT',
        url: url,
        data: {
          name: row.data()[0],
          email: row.data()[2],
          tel: row.data()[3],
          postion: row.data()[1],
        },
        dataType: "JSON",
        success: function (data){
          toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function (){
              window.location.reload();
            }
          });
        },
      });
		}
  })
}
table = $('#users-list').DataTable({
  columnDefs: [
    {
      targets: [0,1,2,3],
      createdCell: createdCell,
    }
  ]
})
/**********************************/
$("#create").submit(function(stay){
  stay.preventDefault();
  var form = $(this)[0];
  var formdata = new FormData(form);
  var url = "{{route('supplier.contactPersonStore', ':id')}}";
  url = url.replace(':id', '{{$supplier->id}}');
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'POST',
    url: url,
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

$('.delete').on('click', function(){
    var selectIds = $(this).data('id');
    Swal.fire({
      title: 'Are You Sure ?',
      text: "This contact person will be permanently deleted!",
      type: 'error',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Delete It !',
      confirmButtonClass: 'btn btn-danger',
      cancelButtonClass: 'btn btn-dark ml-1',
      cancelButtonText: 'Cancel',
      buttonsStyling: false,
    }).then(function (result) {
      if (result.value) {
        var url = "{{ route('contactPerson.destroy', ':selectIds') }}";
        url = url.replace(':selectIds', selectIds);
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: url,
            method: 'DELETE',
            success: function(data){
              toastr.error('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { window.location.reload(); } });
            }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'Your data is safe :)',
          type: 'error',
          confirmButtonClass: 'btn btn-success',
        })
      }
    })
});
</script>
@endsection
