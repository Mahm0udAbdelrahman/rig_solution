@section('second_paper')

	<div class="card donw">
		<div class="card-content">
			<div class="card-body">
				<div class="row header-top mb-1" style="padding-top: 15px;">
					<div class="col-3 pl-0">
						@if ($model->inspection_logo)
							<img src="{{ asset('storage/' . $model->inspection_logo) }}" class="logo-top" />
						@else
							<img src="{{asset('app-assets/images/logo/combined-logo.png')}}" class="logo-top" />
						@endif
					</div>
					<div class="col-6">
						@if (!str_contains($page_text, 'Invoice'))
							@if (str_contains($page_name, 'Crane'))
								<h3 class="text-bold-600 text-center mb-0 mt-2">{{$page_name ?? ''}}</h3>
							@else
								<h1 class="text-bold-600 text-center mb-0 mt-2">{{$page_name ?? ''}}</h1>
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
					<div class="col-3 pr-0 text-right address" style="font-size: 10px;">
						<div class="text-bold-700 white" style="display: flex; flex-direction: column; align-items: flex-end; text-align: right;  width: 105%; margin-left: -12px;">
							<p>Head Office: Block# 3053|Hamdy Ramadan street</p>
							<p>2nd Floor #2 |El-Mearag City|Maadi|Cairo|Egypt</p>
							<p>
								<i class="ft-phone"></i> : +20 2 24477058 |
								<i class="ft-smartphone"></i> : +20 1032703368
							</p>
							<p>
								<i class="ft-mail"></i> : rse@rigsolutionz.com
							</p>
							<p>
								Website: <a href="www.rigsolutionz.com">www.rigsolutionz.com</a>
							</p>
						</div>
					</div>
				</div>
				@stack('page_content_second')
				<div class="row" style="font-size: 12px; color: black; font-weight: 600; margin-top: 3px;">
					<div class="col-1 bg-dark p-0 border-dark">
						<span style="margin-left: 3px;">Form No</span>
					</div>
					<div class="col-1 p-0 pl-1 border-dark">{{$model->footer_values->form_no}}</div>

					<div class="bg-dark col-1 p-0 border-dark">
						<span style="margin-left: 3px;">Issue No</span>
					</div>
					<div class="col-1 p-0 pl-1 border-dark">{{$model->footer_values->issue_no}}</div>

					<div class="bg-dark col-1 p-0 border-dark">
						<span style="margin-left: 3px;">Issue Date</span>
					</div>
					<div class="col-1 p-0 pl-1 border-dark">{{$model->footer_values->issue_date}}</div>

					<div class="bg-dark col-1 p-0 border-dark">
						<span style="margin-left: 3px;">Revision No</span>
					</div>
					<div class="col-1 p-0 pl-1 border-dark">{{ $revision_display_no ?? $model->footer_values->revision_no }}</div>

					<div class="bg-dark col-1 p-0 border-dark">
						<span style="margin-left: 3px;">Revision Date</span>
					</div>
					<div class="col-1 p-0 pl-1 border-dark">{{ $revision_display_date ?? $model->footer_values->revision_date }}</div>

					<div class="bg-dark col-1 p-0 border-dark">
						<span style="margin-left: 3px;">Page No.</span>
					</div>
					<div class="col-1 p-0 pl-1 border-dark">2 of 2</div>
				</div>
				<div class="row mt-1">
					<div class="col-3 text-bold-600 pl-0">{{--{{$iso_number}}--}}</div>
					<div class="col-6">
						<img src="{{asset('app-assets/images/footer-v2.png')}}" style="width: 40em; height: 3.5em;" />
					</div>
					<div class="col-3 text-right text-bold-600 pr-0"></div>
				</div>
			</div>
		</div>
	</div>
@endsection
