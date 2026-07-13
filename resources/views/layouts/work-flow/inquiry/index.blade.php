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
    @if($jobrequests->count() === 0)
    <section id="knowledge-search">
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark text-center white">
                    <div class="card-header mt-3 mb-2">
                        <h1 class="white my-0">
                            You Don't Have Any Packing Slip !
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
    <div class="card">
      <div class="card-content">
        <div class="card-body">
          <div class="table-responsive">
            <table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive">
              <thead>
                <tr>
                  <th>Packing Slip No.</th>
                  <th>JCF</th>
                  <th>Client</th>
                  <th>Contact Person</th>
                  <th>By</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($jobrequests as $jobrequest)
                    <tr>
                      <td><a href="{{route('packingSlip.show', $jobrequest->jobRequest->packingSlip->id)}}">{{$jobrequest->jobRequest->packingSlip->code}}</a></td>
                      <td><a href="{{route('jobRequest.show', $jobrequest->jobRequest->id)}}">{{$jobrequest->jobRequest->code}}</a></td>
                      <td><a href="{{route('client.show', $jobrequest->jobRequest->client_id)}}">{{$jobrequest->jobRequest->client->name}}</a></td>
                      <td>{{$jobrequest->jobrequest->contactPeopleShow->name}}</td>
                      <td>{{$jobrequest->jobRequest->qutation->user->employee->name}}</td>
                      <td>
                        <div class="form-group text-left" style="margin-bottom: auto;">
                          <div class="btn-group mr-1">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">More Actions</button>
                        <!-- -->
                        <div class="dropdown-menu">
                          <div class="dropdown-divider"></div>
                          @if(Storage::disk('public')->exists('packingSlip/packingSlip-'.$jobrequest->jobRequest->packingSlip->code.'.pdf'))
                            <a class="dropdown-item" target="_blank" href="{{URL('storage/packingSlip/packingSlip-'.$jobrequest->jobRequest->packingSlip->code.'.pdf')}}">View as PDF</a>
                          @else
                            <a class="dropdown-item" href="{{route('packingSlip.show', $jobrequest->jobRequest->packingSlip->id)}}">Convert to PDF</a>
                          @endif
                        </div>
                      </div>
                        <!-- -->
                          <a href="{{route('packingSlip.packingSlipWithJobRequestEdit', $jobrequest->jobrequest->id)}}" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>
                          <button type="button" data-id="{{$jobrequest->jobRequest->packingSlip->id}}" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>
                          </div>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!--/ Task List table -->
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
        var url = "{{ route('packingSlip.destroy', ':selectIds') }}";
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
