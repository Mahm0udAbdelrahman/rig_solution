@extends('layouts.app')
@section('header')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/extensions/toastr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/extensions/fixedHeader.dataTables.min.css')}}">
@endsection
@section('header-bottom')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/toastr.css')}}">
@endsection
@section('content')
<section class="users-list-wrapper">
    <div class="users-list">
      @if($suppliers->count() === 0)
      <section id="knowledge-search">
          <div class="row">
              <div class="col-12">
                  <div class="card bg-dark text-center white">
                      <div class="card-header mt-3 mb-0">
                          <h1 class="white my-0">
                              You Don't Have Any Supplier !
                          </h1>
                      </div>
                      <div class="card-body p-0 mb-2">
                          <p class="card-text my-0">
                              Click On This Button Below To Create One.
                          </p>
                          <br />
                          <a href="{{ route('supplier.create') }}" class="btn btn-light clear"><i class="la la-plus"></i> Create New Supplier</a>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      @else
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <!-- datatable start -->
                    <div class="table-responsive">
                        <table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $supplier)
                                <tr>
                                    <td>
                                      @can('view', $supplier)
                                      <a href="{{route('supplier.show', $supplier->id)}}">
                                      @endcan
                                        <img class="media-object " src="{{Storage::url('suppliers/')}}{{$supplier->logo}}" alt="" width="64">
                                      @can('view', $supplier)
                                      </a>
                                      @endcan
                                    </td>
                                    <td>{{$supplier->code}}</td>
                                    <td>
                                      @can('view', $supplier)
                                      <a href="{{route('supplier.show', $supplier->id)}}">
                                      @endcan
                                        {{$supplier->name}}
                                      @can('view', $supplier)
                                      </a>
                                      @endcan
                                    </td>
                                    <td><a href="mailto: {{$supplier->email}}">{{$supplier->email}}</a></td>
                                    <td>
                                      <div class="form-group text-left" style="margin-bottom: auto;">
                                        @can('view', $supplier)
                                        <a href="{{route('supplier.show', $supplier->id)}}" class="btn btn-icon btn-success mr-1 btn11">Contact Persons</a>
                                        @endcan
                                        @can('update', $supplier)
                                          <a href="{{route('supplier.edit', $supplier->id)}}" class="btn btn-icon btn-info mr-1 btn11"><i class="la la-pencil"></i></a>
                                        @endcan
                                        @can('delete', $supplier)
                                          <button type="button" data-id="{{$supplier->id}}" class="btn btn-icon btn-danger mr-1 btn11 delete"><i class="la la-trash"></i></button>
                                        @endcan
                                      </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- datatable ends -->
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
@section('footer')
<!-- BEGIN: Page Vendor JS-->
<script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<!-- END: Page Vendor JS-->
@endsection
@section('ajax')
<script src="{{asset('app-assets/vendors/js/extensions/toastr.min.js')}}"></script>
<script>
$(document).ready(function() {
  var tableResponsive = $('.dataex-fixh-responsive').DataTable({
      responsive: true
  });
  new $.fn.dataTable.FixedHeader(tableResponsive,{
      header: true,
      headerOffset: $('.header-navbar').outerHeight()
  });
});
$('.delete').on('click', function(){
    var selectIds = $(this).data('id');
    Swal.fire({
      title: 'Are You Sure ?',
      text: "This {{ucfirst(str_replace('All ', '', substr($page_name, 0, -1)))}} will be permanently deleted!",
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
        var url = "{{ route('supplier.destroy', ':selectIds') }}";
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
