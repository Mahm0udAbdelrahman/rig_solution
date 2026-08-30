@php
	$footerAddress = $footerAddress ?? \App\Models\GeneralInfo\FooterAddress::getFooterAddress();
@endphp
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
										@if (str_contains($page_text, 'Invoice'))
												<p class="text-bold-700 white" style="font-size: 1.37rem;">Tax ID No.: 210-838-655</p>
										@endif
								</div>
						</div>
						@stack('page_content_second')
						<div class="d-flex justify-content-between align-items-center mt-1" style="font-size: 8px; font-weight: 700; color: #000; width: 100%;">
								@if(isset($iso_number) && $iso_number)
								<div class="text-nowrap mr-1" style="font-size: 8px;">
										{{$iso_number}}
								</div>
								@endif
								<div class="text-center text-nowrap flex-grow-1" style="font-size: 8px;">
										{!! $footerAddress->getPartsHtml() !!}
								</div>
						</div>
				</div>
		</div>
</div>
@endsection
