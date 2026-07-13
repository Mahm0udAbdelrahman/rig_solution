<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// Illuminate\Support
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// App\Models
use App\Models\WorkFlow\JcfStatus;
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\MailCenter;
use App\Models\WorkFlow\Qutation;
use App\Models\WorkFlow\Invoice;
use App\Models\GeneralInfo\Item;
use App\Models\Persons\Client;
use App\Models\User;

// Other
use DB, DataTables, Storage;

class QutationController extends Controller
{

		public $page_name = 'Qutation';

		private function normalizeDateInput($value)
		{
				if ($value === null) {
						return null;
				}

				$value = trim((string)$value);
				if ($value === '') {
						return null;
				}

				$formats = ['d-m-Y', 'Y-m-d', 'd/m/Y', 'Y/m/d', 'd.m.Y', 'm/d/Y'];
				foreach ($formats as $format) {
						try {
								$parsed = Carbon::createFromFormat($format, $value);
								if ($parsed !== false) {
										return $parsed->format('Y-m-d');
								}
						} catch (\Throwable $e) {
								// try next format
						}
				}

				try {
						return Carbon::parse($value)->format('Y-m-d');
				} catch (\Throwable $e) {
						return null;
				}
		}

		private function formatDateForPicker($value)
		{
				if ($value === null) {
						return '';
				}

				$value = trim((string)$value);
				if ($value === '') {
						return '';
				}

				$formats = ['Y-m-d', 'd-m-Y', 'd/m/Y', 'Y/m/d', 'd.m.Y', 'm/d/Y'];
				foreach ($formats as $format) {
						try {
								$parsed = Carbon::createFromFormat($format, $value);
								if ($parsed !== false) {
										return $parsed->format('d-m-Y');
								}
						} catch (\Throwable $e) {
								// try next format
						}
				}

				try {
						return Carbon::parse($value)->format('d-m-Y');
				} catch (\Throwable $e) {
						return '';
				}
		}

    public function __construct()
    {
	      $this->middleware('auth');
	      $this->authorizeResource(Qutation::class, 'qutation');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
				$qutations = Qutation::count();
				$jcfCreateOptions = JobRequest::query()
						->with(['client:id,name', 'supplier:id,name'])
						->whereNotNull('client_id')
						->whereDoesntHave('qutation')
						->orderBy('code', 'desc')
						->get(['id', 'code', 'client_id', 'supplier_id'])
						->mapWithKeys(function (JobRequest $jobRequest) {
								$ownerName = $jobRequest->client->name ?? $jobRequest->supplier->name ?? '';
								$label = $jobRequest->code . ($ownerName !== '' ? ' - ' . $ownerName : '');
								return [$jobRequest->id => $label];
						})
						->toArray();
	      return view('layouts.work-flow.qutation.index', [
						'page_name' => $this->page_name('All', $this->page_name),
						'qutations' => $qutations,
						'jcf_create_options' => $jcfCreateOptions,
				]);
    }

		public function getDataForDataTable(Request $request)
		{
				$data = Qutation::leftJoin('users', 'qutations.user_id', '=', 'users.id')
								->leftJoin('employees', 'employees.id', '=', 'users.employee_id')
								->leftJoin('job_requests', 'job_requests.id', '=', 'qutations.job_request_id')
								->leftJoin('clients', 'job_requests.client_id', '=', 'clients.id')
								->leftJoin('suppliers', 'job_requests.supplier_id', '=', 'suppliers.id')
								->leftJoin('invoices', 'qutations.job_request_id', '=', 'invoices.job_request_id')
								->select([
										'qutations.id as qutation_id',
										'qutations.code as code',
										'employees.name as employee',
										'job_requests.id as job_request_id',
										'job_requests.code as job_request_code',
										'job_requests.client_id as client_id',
										'job_requests.supplier_id as supplier_id',
										'suppliers.name as sup',
										'clients.name as cli',
										'invoices.id as invoice_id',
								]);


				return  Datatables::of($data)
									->addColumn('job_request_code', function($row){
												$report_code = "";
												if(Auth::user()->can('view', JobRequest::find($row->job_request_id)))
												{
														$report_code .= "<a href=".route('jobRequest.show', $row->job_request_id).">";
												}

												$report_code .= $row->job_request_code;

												if(Auth::user()->can('view', JobRequest::find($row->job_request_id)))
												{
														$report_code .= "</a>";
												}
												return $report_code;
										})
									->addColumn('client', function ($row) {
												$client = "";
												if ($row->client_id != null && $row->supplier_id == null)
												{
														if (Auth::user()->can('view', Client::find($row->client_id)))
														{
																$client .= "<a href=".route('client.show', $row->client_id).">";
														}

														$client .= $row->cli;

														if (Auth::user()->can('view', Client::find($row->client_id)))
														{
																$client .= "</a>";
														}
												}
												else
												{
														$client .= $row->sup;
												}
												return $client;
									})
								->addColumn('action', function ($row) {
											$btn = "";

											if (Auth::user()->can('update', Qutation::find($row->qutation_id)))
											{
													$btn .= '<a href="'.route('qutation.qutationWithJobRequestEdit', $row->job_request_id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
											}

											if (Auth::user()->can('delete', Qutation::find($row->qutation_id)))
											{
													$btn .= '<button type="button" data-id="'.$row->qutation_id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
											}

											$btn .= '<div class="btn-group ml-1">
																	<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"></button>
																	<div class="dropdown-menu">';

											if ($row->invoice_id)
											{
													if (Auth::user()->can('view', Invoice::find($row->invoice_id)))
													{
															$btn .= '<a class="dropdown-item" href="'.route('invoice.show', $row->invoice_id).'">Show Invoice</a>';
													}
											}
											else
											{
													if (Auth::user()->can('create', Invoice::class))
													{
														$btn .= '<a class="dropdown-item" href="'.route('invoice.invoiceWithJobRequestNew', $row->job_request_id).'">Convert To Invoice</a>';
													}
											}

											if (Auth::user()->can('create', MailCenter::class) && Auth::user()->can('view', Qutation::find($row->qutation_id)))
											{
													$btn .= '<a class="dropdown-item" href="'.route('mailCenter.compose.related', ['relatedType' => 'qutation', 'relatedId' => $row->qutation_id]).'">Send via Rig MailCenter</a>';
											}

											$btn .= '<div class="dropdown-divider"></div>';
											if (Storage::disk('public')->exists('pdf/workflow/quotation/'.$row->code.'.pdf'))
											{
													$btn .= '<a class="dropdown-item" target="_blank" href="'.URL('storage/pdf/workflow/quotation/'.$row->code.'.pdf').'">Download PDF</a>';
											}
											else
											{
													$btn .= '<a class="dropdown-item" href="'.route('qutation.show', $row->qutation_id).'">Upload PDF</a>';
											}
											$btn .= '</div></div>';
											if ($btn === "")
											{
													return $btn;
											}
											return '<div class="wf-inline-actions">'.$btn.'</div>';
									})
								->editColumn('code', function($row){
											$report_code = "";
											if(Auth::user()->can('view', Qutation::find($row->qutation_id)))
											{
													$report_code .= "<a href=".route('qutation.show', $row->qutation_id).">";
											}

											$report_code .= $row->code;

											if(Auth::user()->can('view', Qutation::find($row->qutation_id)))
											{
													$report_code .= "</a>";
											}
											return $report_code;
									})
								->orderColumn('code', 'qutations.code $1')
								->orderColumn('job_request_code', 'job_requests.code $1')
								->orderColumn('client', 'COALESCE(clients.name, suppliers.name) $1')
								->orderColumn('employee', 'employees.name $1')
								->filterColumn('code', function($query, $keyword){
											$query->whereRaw("qutations.code like ?", ["%{$keyword}%"]);
									})
								->filterColumn('job_request_code', function($query, $keyword){
											$query->whereRaw("job_requests.code like ?", ["%{$keyword}%"]);
									})
								->filterColumn('client', function($query, $keyword){
											$query->whereRaw("COALESCE(clients.name, suppliers.name) like ?", ["%{$keyword}%"]);
									})
								->filterColumn('employee', function($query, $keyword){
											$query->whereRaw("employees.name like ?", ["%{$keyword}%"]);
									})
								->rawColumns(['code', 'job_request_code', 'client', 'action'])
								->make(true);
		}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('jobRequest.index');
    }

    public function qutationWithJobRequest(JobRequest $jobRequest)
    {
      	$this->authorize('create', Qutation::class);

				$jobRequestDeliveryPromise = '';
				if ($jobRequest->jcf_status && $jobRequest->jcf_status->end_at) {
						$jobRequestDeliveryPromise = $this->formatDateForPicker($jobRequest->jcf_status->end_at);
				}

      	return view('layouts.work-flow.qutation.add', [
					'page_name' => $this->page_name(0, $this->page_name),
					'job_request_id' => $jobRequest->id,
					'job_request_code' => $jobRequest->code,
					'job_request_deploc' => $jobRequest->deploc,
					'job_request_delivery_promise' => $jobRequestDeliveryPromise,
				]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
			$last = Qutation::orderBy('code', 'desc')->latest()->first();
			$last_id = "";
			if (!$last || !str_contains($last, substr(date('Y'), 1)))
			{
					$last_id = 'Q-'.substr(date('Y'), 1).'-001';
			}
			else
			{
					$last_id = (int)substr($last->code,6);
					$new_id = $last_id+1;
					$last_id = 'Q-'.substr(date('Y'), 1).'-'.($new_id > 999 ? $new_id : str_pad($new_id, 3,'0',STR_PAD_LEFT));
			}
      $store_qutation = Qutation::create([
	        'code' => $last_id,
	        'job_request_id' => $request->csd,
	        'delivery' => $this->normalizeDateInput($request->deliverydate),
	        'location' => $request->deliverylocation,
	        'subject' => $request->subject,
	        'payment_method' => json_encode($request['payments']),
	        'terms' => json_encode($request['terms']),
	        'items' => json_encode($request['items']),
	        'type' => $request->type,
	        'creation_date' => $request->creation_date,
	        'sync' => 0,
	        'user_id' => Auth::id(),
	        'user_id_approved' => '',
      ]);

      if ($store_qutation)
			{
	        JcfStatus::where('job_request_id', $request->csd)->update(['status' => 2]);
	        return response()->json([
	            'success' => $this->action_message(0, $this->page_name)
	        ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Qutation  $qutation
     * @return \Illuminate\Http\Response
     */
    public function show(Qutation $qutation)
    {
				return view('layouts.work-flow.qutation.show', [
		        'page_name' => $this->page_name,
		        'page_text' => 'No. : '.$qutation->code,
		        'qutation' => $qutation,
		        'person_make_report' => '',
		        'person_make_report_desc' => '',
		        'report_created_at' => '',
		        'esign' => '',
		        'pdfurl' => 'storage/pdf/workflow/quotation/'.$qutation->code.'.pdf',
		        'for_approve_url' => '',
						'imageurl' => $qutation->code,
		        'folder' => 'pdf/workflow/quotation',
		        'user_id_approved' => '',
		        'hasPermission' => '',
		        'mailcenter_compose_url' => route('mailCenter.compose.related', ['relatedType' => 'qutation', 'relatedId' => $qutation->id]),
		        'iso_number' => 'Form # RSE-GF-02 - ISSUE 03 / Nov 2021',
		        'page_number' => '1 of 1'
	      ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Qutation  $qutation
     * @return \Illuminate\Http\Response
     */
    public function edit(Qutation $qutation)
    {
        return abort(404);
    }

		public function qutationWithJobRequestEdit(JobRequest $jobRequest)
    {
		      $this->authorize('update', $jobRequest->qutation);

					$jobRequestDeliveryPromise = '';
					if ($jobRequest->qutation && $jobRequest->qutation->delivery) {
							$jobRequestDeliveryPromise = $this->formatDateForPicker($jobRequest->qutation->delivery);
					} elseif ($jobRequest->jcf_status && $jobRequest->jcf_status->end_at) {
							$jobRequestDeliveryPromise = $this->formatDateForPicker($jobRequest->jcf_status->end_at);
					}

		      return view('layouts.work-flow.qutation.edit', [
							'page_name' => $this->page_name(1, $this->page_name),
							'job_request_id' => $jobRequest->id,
							'job_request_code' => $jobRequest->code,
							'job_request_deploc' => $jobRequest->deploc,
							'qutation' => $jobRequest->qutation,
							'job_request_delivery_promise' => $jobRequestDeliveryPromise,
					]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Qutation  $qutation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Qutation $qutation)
    {
	      $update = Qutation::where('id', $qutation->id)->update([
		        'delivery' => $this->normalizeDateInput($request->deliverydate),
		        'location' => $request->deliverylocation,
		        'subject' => $request->subject,
		        'payment_method' =>  json_encode($request['payments']),
		        'terms' =>  json_encode($request['terms']),
		        'items' => json_encode($request['items']),
		        'type' => $request->type,
                'creation_date' => $request->creation_date,
	      ]);
	      if($update){
	        return response()->json([
	            'success' => $this->action_message(1, $this->page_name)
	        ]);
	      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Qutation  $qutation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Qutation $qutation)
    {
				$remove = $qutation->forceDelete();
				if ($remove)
				{
						$pdf = 'pdf/workflow/quotation/'.$qutation->code.'.pdf';
						$snap = 'images/pdf/workflow/quotation/'.$qutation->code;
						$remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
						return response()->json([
								'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
						]);
				}
    }
}
