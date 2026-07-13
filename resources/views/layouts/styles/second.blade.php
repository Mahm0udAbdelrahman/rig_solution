@section('second_paper')
<div class="card donw">
		<div class="card-content">
				<div class="card-body">
						<div class="row header-top mb-1" style="padding-top: 15px;">
								<div class="col-2 pl-0">
										<img src="{{asset('app-assets/images/logo/logo.png')}}" class="logo-top" />
								</div>
								<div class="col-7">
										@if (!str_contains($page_text, 'Invoice'))
												@if (str_contains($page_name, 'Crane'))
														<h3 class="text-bold-600 text-center mb-0 mt-3">{{$page_name ?? ''}}</h3>
												@else
														<h1 class="text-bold-600 text-center mb-0 mt-3">{{$page_name ?? ''}}</h1>
												@endif
												<p class="text-center text-bold-600 emadnew" style="margin-bottom: 5px;
														@if (str_contains($page_name, 'Crane'))
															font-size: 14px !important;
														@endif
												">{{$page_text ?? ''}}</p>
										@else
												<h1 class="text-bold-600 text-center white" style="font-size: 5.5rem; margin-bottom: 0;" >{{strtoupper($page_name) ?? ''}}</h1>
												<p class="text-bold-600 text-center mb-0 white" style="font-size: 1.8rem !important;">{{$page_text ?? ''}}</p>
										@endif
								</div>
								<div class="col-3 pr-0 text-right address" >
										<p class="text-bold-700 white">3053 Mahmoud Madkor st; 2nd Floor #11
										El-Maerag City, Cairo</p>
										<p class="text-bold-700 white" >Phone/Fax: +20 2 24477058</p>
										<p class="text-bold-700 white">Cell phone: +20 1032703368</p>
										<p class="text-bold-700 white">Email: rse@rigsolutionz.com</p>
										<p class="text-bold-700 white">Website: www.rigsolutionz.com</p>
										@if (str_contains($page_text, 'Invoice'))
												<p class="text-bold-700 white" style="font-size: 1.37rem;">Tax ID No.: 210-838-655</p>
										@endif
								</div>
						</div>
						@stack('page_content_second')
						<div class="row mt-1">
								<div class="col-4 text-bold-600 pl-0">
										{{$iso_number}}
								</div>
								<div class="col-5">
										<img src="{{asset('app-assets/images/footer.jpg')}}" style="max-width: 100%;" />
								</div>
								<div class="col-3 text-right text-bold-600 pr-0">

								</div>
						</div>
				</div>
		</div>
</div>
@endsection
