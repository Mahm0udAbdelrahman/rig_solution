@section('header-bottom')
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
		.logo-top{max-width: 95%;}
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
		.card{ page-break-before: always; counter-increment: page; }
		.inc:after{ content: counter(page) " of " counter(pages); }
		.donw{ max-width: 1150px; height: 1664px; margin: auto; border-radius: 0; box-shadow: none; padding-left: 10px; padding-right: 15px;}
		.emadnew{ font-size: 11px !important; }
		@page{  margin: 0 !important; padding: 0 !important; size: a4;  /* margin-right: 5mm !important; margin-left: 5mm !important;*/ }
</style>
@endsection

@section('content')
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
								<div class="col-3 mb-1" data-type="versions" data-number="{{$edit_report['id']}}" data-label="{{ $edit_report['version_label'] ?? ('REV' . ($key + 1)) }}">
									<a href="{{$edit_report['route']}}" class="btn btn-warning btn-print btn-lg waves-effect waves-light">{{ $edit_report['version_label'] ?? ('REV' . (++$key)) }}: AT-{{$edit_report['edit_at']}} BY: {{$edit_report['edit_by']}}</a>
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
										<div class="row header-top mb-1" style="padding-top: 15px;">
												<div class="col-2 pl-0">
													@if (isset($invoice) && $invoice->invoice_company_type == \App\Models\WorkFlow\Invoice::$INVOICE_LTD_TYPE)
														<img src="{{asset('app-assets/images/logo/ltd-logo.png')}}" class="logo-top" style="max-width: none; width: 14em; margin-top: -10px; margin-left: -10px;"/>
													@else
														<img src="{{asset('app-assets/images/logo/logo.png')}}" class="logo-top" />
													@endif
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
																@if ($invoice->invoice_company_type == \App\Models\WorkFlow\Invoice::$INVOICE_LTD_TYPE)
																	<p class="text-bold-700 white" style="font-size: 1.37rem;">Tax ID No.: 709-320-108</p>
																@else
																	<p class="text-bold-700 white" style="font-size: 1.37rem;">Tax ID No.: 210-838-655</p>
																@endif
														@endif
												</div>
										</div>
										@stack('page_content')
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
				@yield('second_paper')
				
				@php
					$mailcenterComposeUrl = $mailcenter_compose_url ?? null;
					if (!$mailcenterComposeUrl && !empty($for_approve_url) && isset($folder) && !str_contains((string) $folder, 'workflow')) {
						$mailcenterComposeUrl = route('mailCenter.compose.related', ['relatedType' => 'inspection_report', 'relatedId' => $for_approve_url]);
					}
					$downloadPdfUrl = URL('storage/'.$folder.'/'.$imageurl.'.pdf');
					if (!empty($for_approve_url) && isset($folder) && str_contains((string) $folder, 'pdf/inspection') && Route::has('inspection.approval.pdf')) {
						$downloadPdfUrl = route('inspection.approval.pdf', $for_approve_url);
					}
					$downloadExcelUrl = $downloadExcelUrl ?? (isset($invoice) ? route('invoice.exportExcel', $invoice->id) : null);
					$isCurrentApprovedForPdf = (!empty($user_id_approved)) || str_contains((string) ($folder ?? ''), 'workflow') || in_array(Route::currentRouteName(), ['defect.show', 'nregister.show', 'lregister.show', 'drawingInspection.show'], true) || str_contains((string) Route::currentRouteName(), 'lregister') || str_contains((string) Route::currentRouteName(), 'nregister');
					$isCurrentPublishedPdf = $isCurrentApprovedForPdf
						&& (!empty(data_get($model ?? null, 'report.publish')) || !empty($publish ?? null) || str_contains((string) ($folder ?? ''), 'workflow'))
						&& Storage::disk('public')->exists(($folder ?? '').'/'.($imageurl ?? '').'.pdf');
				@endphp
				<!-- action controls card: requires current version to be approved -->
				@if($isCurrentApprovedForPdf)
				<div class="card no-print mt-2">
					  <div class="card-content">
						    <div class="card-body">
							      <div class="row" style="direction: rtl;">
										@if($mailcenterComposeUrl && auth()->user()->can('create', App\Models\WorkFlow\MailCenter::class))
											<a class="btn btn-info btn-print btn-lg ml-1" href="{{ $mailcenterComposeUrl }}">Send via Rig MailCenter <i class="la la-envelope-o mr-50"></i></a>
										@endif
										@if ($isCurrentPublishedPdf)
											<button type="button" id="print" class="btn btn-secondary btn-print btn-lg ml-1">Print Page <i class="la la-paper-plane-o mr-50"></i></button>
											<a class="btn btn-primary btn-print btn-lg ml-1" target="_blank" href="{{ $downloadPdfUrl }}">Download <i class="la la-download mr-50"></i></a>
										@endif
										<button type="button" id="uploadpdf" class="btn btn-dark btn-print btn-lg">Upload / Update PDF <i class="la la-paper-plane-o"></i></button>
							      </div>
						    </div>
					  </div>
				</div>
				@endif
		</section>
@endsection

@section('footer')
		<script>
				var pdfDependenciesPromise = null;

				function loadScriptOnce(src, readyCheck) {
						if (typeof readyCheck === 'function' && readyCheck()) {
								return Promise.resolve();
						}

						return new Promise(function (resolve, reject) {
								var existing = document.querySelector('script[data-dynamic-src="' + src + '"]');
								if (existing) {
										existing.addEventListener('load', function () { resolve(); }, { once: true });
										existing.addEventListener('error', function () { reject(new Error('Failed to load ' + src)); }, { once: true });
										return;
								}

								var script = document.createElement('script');
								script.src = src;
								script.async = true;
								script.dataset.dynamicSrc = src;
								script.onload = function () { resolve(); };
								script.onerror = function () { reject(new Error('Failed to load ' + src)); };
								document.body.appendChild(script);
						});
				}

				function loadPdfDependencies() {
						if (!pdfDependenciesPromise) {
								pdfDependenciesPromise = loadScriptOnce(
										"{{ asset('app-assets/js/html2canvas.js') }}",
										function () { return typeof window.html2canvas === 'function'; }
								).then(function () {
										return loadScriptOnce(
												"{{ asset('app-assets/js/jspdf.js') }}",
												function () { return typeof window.jspdf !== 'undefined' && typeof window.jspdf.jsPDF === 'function'; }
										);
								});
						}

						return pdfDependenciesPromise;
				}

				function take_snap_shot(){
						var captureElements = document.querySelectorAll('.donw');
						var snapshots = Array.prototype.map.call(captureElements, function (element) {
								return html2canvas(element).then(function (canvas) {
										return canvas.toDataURL('image/png').replace('image/png', 'image/octet-stream');
								});
						});

						return Promise.all(snapshots).then(function (images) {
								var formData = new FormData();
								formData.append('imageurl', '{{$imageurl}}');
								formData.append('folder', '{{$folder}}');
								formData.append('image', JSON.stringify(images));
								return $.ajax({
										headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
										type: 'POST',
										url: "{{route('report.makeImageForPdf', $for_approve_url)}}",
										cache: false,
										data: formData,
										processData: false,
										contentType: false
								}).then(function () {
										return images;
								});
						});
				}

				function convert_pdf(images)
				{
				  		const pdf_page_mode = $('#page_mode').val() || 'p';

						var doc = new jspdf.jsPDF(pdf_page_mode, 'pt','a4',true);
						var width = doc.internal.pageSize.getWidth();
						var height = doc.internal.pageSize.getHeight();
						for (var i = 0; i < images.length; ++i){
								doc.addImage(images[i], "PNG", 0, 0, width, height, "alias"+i, 'FAST')
								if (i+1 != images.length)
								{
										doc.addPage()
								}
						}
						var blob = doc.output("blob");
						var formData = new FormData();
						formData.append('pdf', blob);
						formData.append('imageurl', '{{$imageurl}}');
						formData.append('folder', '{{$folder}}');
						@if(!empty($for_approve_url))
						formData.append('report_id', '{{$for_approve_url}}');
						@endif
						return $.ajax({
								headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
								type: 'POST',
								url: "{{route('report.generatePdf')}}",
								cache: false,
								data: formData,
								processData: false,
								contentType: false,
								success: function(data){
										toastr.info('Good Job !', (data && data.success) ? data.success : 'PDF Uploaded Successfully !', { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 1500 });
										setTimeout(function () {
												window.location.reload();
										}, 600);
								},
						});
				}

				function print_pdf()
				{
						printWindow = window.open("{{ $downloadPdfUrl }}");
						printWindow.window.print();
				}

				$(document).on('click','#uploadpdf',function(){
						loadPdfDependencies()
								.then(function () {
										return take_snap_shot();
								})
								.then(function (images) {
										return convert_pdf(images);
								})
								.catch(function (error) {
										console.error(error);
										toastr.error('Capture failed', 'Unable to build the full PDF', { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 3000, fadeOut: 1000 });
								});
				});

				$('#print').click(function(){
						print_pdf();
				});

				$(document).bind("keyup ", function(e){
					console.log(e.keyCode);
					if (e.keyCode == 80)
					{
						if (document.getElementById("print"))
						{
							print_pdf();
						}
						else
						{
							loadPdfDependencies()
									.then(function () {
											return take_snap_shot();
									})
									.then(function (images) {
											return convert_pdf(images);
									})
									.catch(function (error) {
											console.error(error);
											toastr.error('Capture failed', 'Unable to build the full PDF', { positionClass: 'toast-bottom-left', "showMethod": "slideDown", "hideMethod": "slideUp", "progressBar": true, timeOut: 3000, fadeOut: 1000 });
									});
						}
						return false;
					}
				});

				$('[data-type="versions"]').each(function(key){
					var id = $('.donw [data-type="code"]').data('id');
					var number = $(this).data('number');
					var label = String($(this).data('label') || '');
					if(number == id)
					{
						if (label.toUpperCase().indexOf('REV') === 0) {
							$('.donw [data-type="code"][data-id="'+ id +'"]').append(' - ' + label).css('font-size', '100%');
						}
					}
				});

				$(document).on('click', '.js-export-page-excel', function(e) {
						e.preventDefault();
						var pageElement = document.querySelector('.donw') || document.querySelector('.page_in') || document.querySelector('body');
						if (!pageElement) return;

						var clone = pageElement.cloneNode(true);
						clone.querySelectorAll('.no-print').forEach(function(el) { el.remove(); });

						var headers = [];
						var values = [];
						var keyValuePairs = [];

						function addPair(h, v) {
								h = String(h || '').replace(/[\n\r]+/g, ' ').replace(/\s+/g, ' ').trim().replace(/:$/, '');
								v = String(v || '').replace(/[\n\r]+/g, ' ').replace(/\s+/g, ' ').trim();
								if (!h || !v) return;
								if (h.length > 90) h = h.substring(0, 90) + '...';

								var finalHeader = h;
								var count = 1;
								while (headers.includes(finalHeader)) {
										count++;
										finalHeader = h + ' (' + count + ')';
								}

								headers.push(finalHeader);
								values.push(v);
								keyValuePairs.push({ label: finalHeader, value: v });
						}

						var rows = clone.querySelectorAll('.row');
						rows.forEach(function(row) {
								var children = Array.from(row.children);
								var curLabel = '';
								var curVal = '';

								for (var i = 0; i < children.length; i++) {
										var child = children[i];
										var text = child.innerText.replace(/\s+/g, ' ').trim();
										if (!text) continue;

										var isBg = child.classList.contains('bg-dark') || child.querySelector('.bg-dark') || child.tagName === 'H6';

										if (isBg) {
												if (curLabel && curVal) {
														addPair(curLabel, curVal);
														curLabel = '';
														curVal = '';
												}
												curLabel = text;
										} else {
												if (curLabel) {
														curVal = curVal ? curVal + ' | ' + text : text;
												}
										}
								}
								if (curLabel && curVal) {
										addPair(curLabel, curVal);
								}
						});

						var tables = clone.querySelectorAll('table');
						tables.forEach(function(tbl) {
								var tblRows = tbl.querySelectorAll('tr');
								tblRows.forEach(function(tr) {
										var cells = tr.querySelectorAll('td, th');
										if (cells.length >= 2) {
												var c1 = cells[0].innerText.trim();
												var c2 = Array.from(cells).slice(1).map(function(c) { return c.innerText.trim(); }).filter(Boolean).join(' | ');
												if (c1 && c2 && c1.length < 60) {
														addPair(c1, c2);
												}
										}
								});
						});

						function escapeHtml(str) {
								return String(str || '')
										.replace(/&/g, '&amp;')
										.replace(/</g, '&lt;')
										.replace(/>/g, '&gt;')
										.replace(/"/g, '&quot;');
						}

						var table1Html = '<h3 style="font-family:Calibri, Arial, sans-serif; font-size:12pt; color:#1E293B;">Inspection Record Summary</h3>' +
								'<table border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse; width:100%; font-family:Calibri, Arial, sans-serif; font-size:10pt;">' +
								'<thead><tr style="background-color:#1E293B; color:#FFFFFF; font-weight:bold; text-align:center;">';

						headers.forEach(function(h) {
								table1Html += '<th style="background-color:#1E293B; color:#FFFFFF; padding:6px 10px; border:1px solid #000000; text-align:center; white-space:nowrap;">' + escapeHtml(h) + '</th>';
						});

						table1Html += '</tr></thead><tbody><tr style="text-align:center;">';

						values.forEach(function(v) {
								table1Html += '<td style="padding:6px 10px; border:1px solid #000000; text-align:center; vertical-align:middle;">' + escapeHtml(v) + '</td>';
						});

						table1Html += '</tr></tbody></table>';

						var table2Html = '<br/><br/><h3 style="font-family:Calibri, Arial, sans-serif; font-size:12pt; color:#00A5BB;">Record Field Details (Key - Value List)</h3>' +
								'<table border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse; width:700px; font-family:Calibri, Arial, sans-serif; font-size:10pt;">' +
								'<thead><tr style="background-color:#00A5BB; color:#FFFFFF; font-weight:bold;">' +
								'<th style="background-color:#00A5BB; color:#FFFFFF; padding:6px 10px; border:1px solid #000000; width:280px; text-align:left;">Field Name / Column</th>' +
								'<th style="background-color:#00A5BB; color:#FFFFFF; padding:6px 10px; border:1px solid #000000; width:420px; text-align:left;">Value / Record</th>' +
								'</tr></thead><tbody>';

						keyValuePairs.forEach(function(pair, idx) {
								var bg = (idx % 2 === 0) ? '#F8FAFC' : '#FFFFFF';
								table2Html += '<tr style="background-color:' + bg + ';">' +
										'<td style="padding:6px 10px; border:1px solid #000000; font-weight:bold; background-color:#F1F5F9;">' + escapeHtml(pair.label) + '</td>' +
										'<td style="padding:6px 10px; border:1px solid #000000;">' + escapeHtml(pair.value) + '</td>' +
										'</tr>';
						});

						table2Html += '</tbody></table>';

						var excelStyles = '<style>' +
								'body { font-family: Calibri, "Segoe UI", Arial, sans-serif; font-size: 10pt; color: #000000; background-color: #ffffff; padding: 15px; }\n' +
								'table { border-collapse: collapse !important; margin-bottom: 15px !important; }\n' +
								'th { font-weight: bold !important; font-size: 10pt !important; }\n' +
								'td { font-size: 10pt !important; }\n' +
								'</style>';

						var htmlHeader = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' +
								'<head><meta charset="utf-8"/>' +
								'<!--[if gte mso 9]><xml><' + 'x:ExcelWorkbook><' + 'x:ExcelWorksheets><' + 'x:ExcelWorksheet><' + 'x:Name>Report Data</' + 'x:Name><' + 'x:WorksheetOptions><' + 'x:DisplayGridlines/></' + 'x:WorksheetOptions></' + 'x:ExcelWorksheet></' + 'x:ExcelWorksheets></' + 'x:ExcelWorkbook></xml><![endif]-->' +
								excelStyles +
								'</head><body>';

						var fullHtml = htmlHeader + table1Html + table2Html + '</body></html>';

						var blob = new Blob(['\ufeff' + fullHtml], {
								type: 'application/vnd.ms-excel;charset=utf-8'
						});
						var url = URL.createObjectURL(blob);
						var a = document.createElement('a');
						a.href = url;
						a.download = '{{ $imageurl ?? "document" }}' + '.xls';
						document.body.appendChild(a);
						a.click();
						document.body.removeChild(a);
						URL.revokeObjectURL(url);
				});

		</script>
@endsection
