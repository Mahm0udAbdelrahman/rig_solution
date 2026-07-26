@php
	$footerAddress = $footerAddress ?? \App\Models\GeneralInfo\FooterAddress::getFooterAddress();
@endphp
<style>
		* {
				-webkit-print-color-adjust: exact !important;   /* Chrome, Safari, Edge */
				color-adjust: exact !important;                 /*Firefox*/
		}
		label{ font-size: 0.8rem; }
		.app-content .wizard.wizard-circle > .steps > ul > li.current ~ li:before, .app-content .wizard.wizard-circle > .steps > ul > li.current ~ li:after, .app-content .wizard.wizard-circle > .steps > ul > li.current:after{ background-color: #fff; }
		p { letter-spacing: inherit; margin-bottom: 0 !important; }
		.custom-control-label{ margin-bottom: 5px;}
		.custom-control-label::after{ top: 0 !important; }
		.skin-square label{ margin-bottom: auto; }
		.bg-dark{ background-color: #d9d9d9 !important; }
		.white{ color: #000 !important;}
		.logo-top{width: 225px; height: 115px; margin-top: -1.5em;}
		.border-dark{ /*padding-top: 3px !important; padding-bottom: 3px !important;*/ }
		.card-body{ padding: 1rem 2rem; }
		.middle{ display: flex; align-items: center; }
		.mid11{ display: flex; align-items: center; }
		.just{ justify-content: center; }
		.noncheckedfrom{ background: url({{asset('app-assets/vendors/css/forms/icheck/square/purple.png')}}) -1px -1px no-repeat; display: inline-block; margin-right: 0.6rem; width: 17px; height: 17px; border: 1px solid #d9d9d9;}
		.noncheckedradiofrom{ background: url({{asset('app-assets/vendors/css/forms/icheck/square/purple.png')}}) -1px -1px no-repeat; display: inline-block; margin-right: 0.6rem; width: 17px; height: 17px; border: 1px solid #d9d9d9; border-radius: 15px;}
		.checked{ background-position: -51px -3px; border-color: #6a5a8c; }
		.mid{ vertical-align: middle; display: flex; padding-top: 3px; padding-bottom: 3px; }
		.text-16, .skin-square label{ font-size: 16px; }
		p.bnew{font-size: 22px !important; font-weight: 600 !important;}
		h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6{ color: #000 !important; }
		.h-90{ height: 130px; }
		.h-80{ height: 120px; }
		.h-70{ height: 100px; }
		.h-130{ height: 150px;}
		.h-175{ height: 310px; }
		.hp-250{ height: 250px; }
		.card{ page-break-before: always; counter-increment: page; }
		.inc:after{ content: counter(page) " of " counter(pages); }
		.donw{ max-width: 1150px; height: 1664px; margin: auto; border-radius: 0; box-shadow: none; padding-left: 18px; padding-right: 18px;}
		.emadnew{ font-size: 11px !important; }
		@page{  margin: 0 !important; padding: 0 !important; size: a4;  /* margin-right: 5mm !important; margin-left: 5mm !important;*/ }
</style>

@if(isset($have_edit) && count($have_edit) > 1 )
<div class="card no-print">
	<div class="card-content">
		<div class="card-body">
			<div class="row">
				<div class="col-12">
					<h5>All Versions</h5>
				</div>
			</div>
			<hr />
			<div class="row">
				@foreach($have_edit as $key => $edit_report)
				<div class="col-3 mb-1" data-type="versions" data-number="{{$edit_report['id']}}">
					<a href="{{$edit_report['route']}}"
						class="btn btn-warning btn-print btn-lg waves-effect waves-light">REV{{++$key}}:
						AT-{{$edit_report['edit_at']}} BY: {{$edit_report['edit_by']}}</a>
				</div>
				@endforeach
			</div>
		</div>
	</div>
</div>
@endif
<section class="validation mb-1">
	<div class="card donw">
		<div class="card-content">
			<div class="card-body">
				@if(Route::currentRouteName() != "drawingInspection.show")
				<!-- drawing inspection has its own header on its show blade -->
				<div class="row header-top mb-1" style="padding-top: 5px;">
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
						@elseif (request()->route()->getName() && str_contains(request()->route()->getName(),
						'high3Pressure'))
						<h3 class="text-bold-600 text-center mb-0 mt-2">{{$page_name ?? ''}}</h3>
						<h4 class="text-bold-600 text-center mb-0">(High Pressure Line)</h4>
						@else
						<h1 class="text-bold-600 text-center mb-0 mt-2"> {{$page_name ?? ''}}</h1>
						@endif
						<p class="text-center text-bold-600 emadnew" style="margin-bottom: 5px;
																		@if (str_contains($page_name, 'Crane'))
																			font-size: 14px !important;
																		@endif
																">{{$page_text ?? ''}}</p>
						@else
						<h1 class="text-bold-600 text-center white" style="font-size: 5.5rem; margin-bottom: 0;">
							{{strtoupper($page_name) ?? ''}}</h1>
						<p class="text-bold-600 text-center mb-0 white" style="font-size: 1.8rem !important;">
							{{$page_text ?? ''}}</p>
						@endif
					</div>
					<div class="col-3 pr-0 text-right address"></div>
				</div>
				@endif







				<style>
					.summary-row {
						font-size: 17px;
						line-height: 37px;
					}

					#inspection_data {
						display: contents;
					}

					/*.vertical-cell{
            text-orientation: mixed;
            text-wrap: nowrap;
            writing-mode: tb-rl;
            transform: rotate(-180deg);
            border: solid 2px;
        }*/
					.standards {
						background-color: white;
					}

					hr {
						margin: 1px 0px;
						border-block-color: black;
					}

					.standards_input {
						border: none;
						background-color: inherit;
						padding: 0;
						text-align: center;
					}
				</style>
				<!----------------------------------->
				<div class="row">
					<div class="bg-dark col-2 p-0 border-dark">
						<h6 class="white text-bold-600 pl-1 mb-0">Client</h6>
					</div>
					<div class="col-4 p-0 pl-1 border-dark text-16 black">
						@if($model->job_request->client)
						{{$model->job_request->client->name}}
						@else
						{{$model->job_request->supplier->name}}
						@endif
					</div>
					<div class="bg-dark col-2 p-0 border-dark">
						<h6 class="white text-bold-600 pl-1 mb-0">Register Number</h6>
					</div>
					<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$code}}</div>
				</div>
				<!----------------------------------->
				<div class="row">
					<div class="bg-dark col-2 p-0 border-dark">
						<h6 class="white text-bold-600 pl-1 mb-0">Work location</h6>
					</div>
					<div class="col-4 p-0 pl-1 border-dark text-16 black">
						{{$model->job_request->clientDepartment ?
						$model->job_request->clientDepartment->name . ' / '.$model->job_request->deploc
						: $model->job_request->deploc}}
					</div>
					<div class="bg-dark col-2 p-0 border-dark">
						<h6 class="white text-bold-600 pl-1 mb-0">Register Date</h6>
					</div>
					<div class="col-4 p-0 pl-1 border-dark text-16 black">{{$model->register_date}}</div>
				</div>
				<!----------------------------------->
				<div class="row" style="margin-top:3px;/* page-break-before:always;*/">
					<table
						style="width: 100%; margin: auto; background: white !important;/* page-break-before: always;*/">
						<thead>
							<tr class="text-ceter">
								<td class="border-dark bg-dark white text-bold-600 text-center" width="15%">
									Identification No
								</td>
								<td class="border-dark bg-dark white text-bold-600 text-center" width="35%">
									Description
								</td>
								<td class="border-dark bg-dark white text-bold-600 text-center" width="15%">
									Accept Criteria
								</td>
								<td class="border-dark bg-dark white text-bold-600 text-center" width="12%">
									Report No
								</td>
								<td class="border-dark bg-dark white text-bold-600 text-center" width="13%">
									Examination Date
								</td>
								<td class="border-dark bg-dark white text-bold-600 text-center" width="10%">
									Accept
								</td>
							</tr>
						</thead>
						<tbody>
							@foreach($chunk_data as $row)
							<tr>
								<td style="height: 60px;" class="border-dark white text-center">{{isset($row->nmpr_28)
									? $row->nmpr_28 : ''}}</td>
								<td class="border-dark white text-center">{{isset($row->desc) ? $row->desc : ''}}</td>
								<td class="border-dark white text-center">{{isset($row->acceptance) ? $row->acceptance :
									''}}</td>
								<td class="border-dark white text-center">{{isset($row->job_request) ?
									$row->job_request->code . '/' . $row->code : ''}}</td>
								<td class="border-dark white text-center">{{isset($row->nmpr_6) ? $row->nmpr_6 : ''}}
								</td>
								<td class="border-dark white text-center">{{isset($row->nmpr_30) ?
									$row->checkbox_yes($row->nmpr_30) == 'checked' ? 'Accepted' : 'Rejected' : ''}}</td>
							</tr>
							@endforeach
							
							@for ($i = count($chunk_data); $i < 18; $i++) <tr>
								<td  style="height: 60px;" class="border-dark white text-center">-</td>
								<td class="border-dark white text-center">-</td>
								<td class="border-dark white text-center">-</td>
								<td class="border-dark white text-center">-</td>
								<td class="border-dark white text-center">-</td>
								<td class="border-dark white text-center">-</td>
								</tr>
							@endfor
						</tbody>
					</table>
				</div>
				<!----------------------------------->
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
					<div class="col-1 p-0 pl-1 border-dark">{{$model->footer_values->revision_no}}</div>

					<div class="bg-dark col-1 p-0 border-dark">
						<span style="margin-left: 3px;">Revision Date</span>
					</div>
					<div class="col-1 p-0 pl-1 border-dark">{{$model->footer_values->revision_date}}</div>

					<div class="bg-dark col-1 p-0 border-dark">
						<span style="margin-left: 3px;">Page No.</span>
					</div>
					<div class="col-1 p-0 pl-1 border-dark">{{$page_number}}</div>
				</div>
				<div class="row mt-1 text-center" style="font-size: 10px; font-weight: 700; color: #000; justify-content: center; width: 100%;">
					<div class="col-12 text-center">
						<p class="mb-0">{{ $footerAddress->address_line_1 }}</p>
						<p class="mb-0">{{ $footerAddress->address_line_2 }}</p>
						<p class="mb-0">
							<i class="ft-phone"></i> : {{ $footerAddress->phone }} |
							<i class="ft-smartphone"></i> : {{ $footerAddress->mobile }} |
							<i class="ft-mail"></i> : {{ $footerAddress->email }} |
							Website: <a href="http://{{ str_replace(['http://', 'https://'], '', $footerAddress->website) }}" target="_blank">{{ $footerAddress->website }}</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</section>
