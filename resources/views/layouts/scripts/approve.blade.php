@section('ajax')
		<script>
				$(document).on('click','#approve',function(){
						$.ajax({
								headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
								type: 'POST',
								data: {
										id: "{{$id}}",
										table: "{{$table}}"
								},
								url: "{{route('userApprove')}}",
								success: function(data){
										toastr.info('Good Job !', data.success, { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1000, fadeOut: 1000, onHidden: function () { window.location.reload(); }  });
								},
						});
				});
		</script>
@endsection
