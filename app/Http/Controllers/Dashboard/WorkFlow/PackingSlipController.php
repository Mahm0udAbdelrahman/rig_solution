<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// Illuminate\Support
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// App\Models
use App\Models\WorkFlow\PackingSlip;
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\JcfStatus;
use App\Models\Persons\Client;
use App\Models\User;

// Other
use DB, DataTables, Storage;

class PackingSlipController extends Controller
{
		public $page_name = 'Packing Slip';

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
	      $this->authorizeResource(PackingSlip::class, 'packingSlip');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
		      $packingslips = PackingSlip::count();
					$jcfCreateOptions = JobRequest::query()
							->with(['client:id,name', 'supplier:id,name'])
							->whereNotNull('client_id')
							->whereDoesntHave('packingSlip')
							->orderBy('code', 'desc')
							->get(['id', 'code', 'client_id', 'supplier_id'])
							->mapWithKeys(function (JobRequest $jobRequest) {
									$ownerName = $jobRequest->client->name ?? $jobRequest->supplier->name ?? '';
									$label = $jobRequest->code . ($ownerName !== '' ? ' - ' . $ownerName : '');
									return [$jobRequest->id => $label];
							})
							->toArray();
		      return view('layouts.work-flow.packingslip.index', [
					 			'page_name' => $this->page_name('All', $this->page_name),
								'packingslips' => $packingslips,
								'jcf_create_options' => $jcfCreateOptions,
					]);
     }

		 public function getDataForDataTable(Request $request)
 		{
				$data = PackingSlip::leftJoin('users', 'packing_slips.user_id', '=', 'users.id')
								->leftJoin('employees', 'employees.id', '=', 'users.employee_id')
								->leftJoin('job_requests', 'job_requests.id', '=', 'packing_slips.job_request_id')
								->leftJoin('clients', 'job_requests.client_id', '=', 'clients.id')
								->leftJoin('suppliers', 'job_requests.supplier_id', '=', 'suppliers.id')
								->select([
										'packing_slips.id as qutation_id',
										'packing_slips.code as code',
										'employees.name as employee',
										'job_requests.id as job_request_id',
										'job_requests.code as job_request_code',
										'job_requests.client_id as client_id',
										'job_requests.supplier_id as supplier_id',
										'suppliers.name as sup',
										'clients.name as cli',
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

											if (Auth::user()->can('update', PackingSlip::find($row->qutation_id)))
											{
													$btn .= '<a href="'.route('packingSlip.packingSlipWithJobRequestEdit', $row->job_request_id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
											}

											if (Auth::user()->can('delete', PackingSlip::find($row->qutation_id)))
											{
													$btn .= '<button type="button" data-id="'.$row->qutation_id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
											}

											$btn .= '<div class="btn-group ml-1">
																	<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"></button>
																	<div class="dropdown-menu">';

											if (Storage::disk('public')->exists('pdf/workflow/packingslip/'.$row->code.'.pdf'))
											{
													$btn .= '<a class="dropdown-item" target="_blank" href="'.URL('storage/pdf/workflow/packingslip/'.$row->code.'.pdf').'">Download PDF</a>';
											}
											else
											{
													$btn .= '<a class="dropdown-item" href="'.route('packingSlip.show', $row->qutation_id).'">Upload PDF</a>';
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
											if(Auth::user()->can('view', PackingSlip::find($row->qutation_id)))
											{
													$report_code .= "<a href=".route('packingSlip.show', $row->qutation_id).">";
											}

											$report_code .= $row->code;

											if(Auth::user()->can('view', PackingSlip::find($row->qutation_id)))
											{
													$report_code .= "</a>";
											}
											return $report_code;
									})
								->orderColumn('code', 'packing_slips.code $1')
								->orderColumn('job_request_code', 'job_requests.code $1')
								->orderColumn('client', 'COALESCE(clients.name, suppliers.name) $1')
								->orderColumn('employee', 'employees.name $1')
								->filterColumn('code', function($query, $keyword){
											$query->whereRaw("packing_slips.code like ?", ["%{$keyword}%"]);
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

    public function packingSlipWithJobRequest(JobRequest $jobRequest)
    {
	      $this->authorize('create', PackingSlip::class);
				$jobRequestStatus = JcfStatus::where('job_request_id', $jobRequest->id)->first();
				$prefillOrderDateRaw = optional($jobRequestStatus)->start_at ?: $jobRequest->contact_date;
				$prefillShippingMethod = '';
				if ($jobRequest->qutation && $jobRequest->qutation->location) {
						$prefillShippingMethod = $jobRequest->qutation->location;
				} else {
						$prefillShippingMethod = $jobRequest->deploc ?? '';
				}

	      return view('layouts.work-flow.packingslip.add', [
						'jobrequests_stat' => $this->formatDateForPicker($prefillOrderDateRaw),
						'job_request_id' => $jobRequest->id,
						'job_request_code' => $jobRequest->code,
						'qutations' => $jobRequest->qutation,
						'prefill_client_po' => $jobRequest->purchase_order ?? '',
						'prefill_shipping_method' => $prefillShippingMethod,
						'page_name' => $this->page_name(0, $this->page_name),
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
				$last = PackingSlip::orderBy('code', 'desc')->latest()->first();
				$last_id = "";
				if (!$last || !str_contains($last, substr(date('Y'), 1))){
						$last_id = 'P.S-'.substr(date('Y'), 1).'-001';
				}else{
						$last_id = (int)substr($last->code,8);
						$new_id = $last_id+1;
						$last_id = 'P.S-'.substr(date('Y'), 1).'-'.($new_id > 999 ? $new_id : str_pad($new_id, 3,'0',STR_PAD_LEFT));
				}
	      $store_packingslip = PackingSlip::create([
		        'code' => $last_id,
		        'job_request_id' => $request->csd,
		        'po' => $request->po,
		        'shppingmethods' => $request->shippingmethod,
		        'orderdate' => $this->normalizeDateInput($request->from),
		        'items' => json_encode($request['payments']),
		        'notice' => $request->descTextarea,
		        'user_id' => Auth::id(),
		        'sync' => 0,
	      ]);
	      JcfStatus::where('job_request_id', $request->csd)->update(['status' => 3]);
	      return response()->json([
	          'success' => $this->action_message(0, $this->page_name)
	      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PackingSlip  $packingSlip
     * @return \Illuminate\Http\Response
     */
    public function show(PackingSlip $packingSlip)
    {
				return view('layouts.work-flow.packingslip.show', [
						'page_name' => $this->page_name,
						'page_text' => 'No. : '.$packingSlip->code,
						'packingSlip' => $packingSlip,
						'person_make_report' => '',
						'person_make_report_desc' => '',
						'report_created_at' => '',
						'esign' => '',
						'pdfurl' => 'storage/pdf/workflow/packingslip/'.$packingSlip->code.'.pdf',
						'for_approve_url' => '',
						'imageurl' => $packingSlip->code,
						'folder' => 'pdf/workflow/packingslip',
						'user_id_approved' => '',
						'hasPermission' => '',
						'iso_number' => 'Form # RSE-GF-03 - ISSUE 04 / Nov 2021	',
						'page_number' => '1 of 1'
				]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PackingSlip  $packingSlip
     * @return \Illuminate\Http\Response
     */
    public function edit(PackingSlip $packingSlip)
    {
        return abort(404);
    }

    public function packingSlipWithJobRequestEdit(JobRequest $jobRequest)
    {
	      $this->authorize('update', $jobRequest->packingSlip, PackingSlip::class);
				$jobRequestStatus = JcfStatus::where('job_request_id', $jobRequest->id)->first();
				$prefillOrderDateRaw = $jobRequest->packingSlip->orderdate
						?: (optional($jobRequestStatus)->start_at ?: $jobRequest->contact_date);
				$prefillClientPo = $jobRequest->packingSlip->po ?: ($jobRequest->purchase_order ?? '');
				$prefillShippingMethod = $jobRequest->packingSlip->shppingmethods;
				if (!$prefillShippingMethod) {
						$prefillShippingMethod = ($jobRequest->qutation && $jobRequest->qutation->location)
								? $jobRequest->qutation->location
								: ($jobRequest->deploc ?? '');
				}

	      return view('layouts.work-flow.packingslip.edit', [
						'job_request_id' => $jobRequest->id,
						'job_request_code' => $jobRequest->code,
						'packingSlip' => $jobRequest->packingslip,
						'prefill_order_date' => $this->formatDateForPicker($prefillOrderDateRaw),
						'prefill_client_po' => $prefillClientPo,
						'prefill_shipping_method' => $prefillShippingMethod,
						'page_name' => $this->page_name(1, $this->page_name),
				]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PackingSlip  $packingSlip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PackingSlip $packingSlip)
    {
      $update_packingSlip = PackingSlip::where('id', $packingSlip->id)->update([
	        'job_request_id' => $request->csd,
	        'po' => $request->po,
	        'shppingmethods' => $request->shippingmethod,
	        'orderdate' => $this->normalizeDateInput($request->from),
	        'items' => json_encode($request['payments']),
	        'notice' => $request->descTextarea,
      ]);
      if($update_packingSlip)
			{
	        return response()->json([
	            'success' => $this->action_message(1, $this->page_name),
	        ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PackingSlip  $packingSlip
     * @return \Illuminate\Http\Response
     */
    public function destroy(PackingSlip $packingSlip)
    {
				$remove = $packingSlip->forceDelete();
				if ($remove)
				{
						$pdf = 'pdf/workflow/packingslip/'.$packingSlip->code.'.pdf';
						$snap = 'images/pdf/workflow/packingslip/'.$packingSlip->code;
						$remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
						return response()->json([
								'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
						]);
				}
    }
}
