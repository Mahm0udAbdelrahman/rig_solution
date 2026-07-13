<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// Illuminate\Support
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// App\Models
use App\Models\WorkFlow\ServiceTicket;
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\JcfStatus;
use App\Models\WorkFlow\MailCenter;
use App\Models\Persons\Client;
use App\Models\User;

// Other
use DB, DataTables, Storage;

class ServiceTicketController extends Controller
{
		public $page_name = 'Service Ticket';

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
      $this->authorizeResource(ServiceTicket::class, 'serviceTicket');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
				$servicetickets = ServiceTicket::count();
				$jcfCreateOptions = JobRequest::query()
						->with(['client:id,name', 'supplier:id,name'])
						->whereNotNull('client_id')
						->whereDoesntHave('serviceTicket')
						->orderBy('code', 'desc')
						->get(['id', 'code', 'client_id', 'supplier_id'])
						->mapWithKeys(function (JobRequest $jobRequest) {
								$ownerName = $jobRequest->client->name ?? $jobRequest->supplier->name ?? '';
								$label = $jobRequest->code . ($ownerName !== '' ? ' - ' . $ownerName : '');
								return [$jobRequest->id => $label];
						})
						->toArray();
	      return view('layouts.work-flow.serviceticket.index', [
						'page_name' => $this->page_name('All', $this->page_name),
						'servicetickets' => $servicetickets,
						'jcf_create_options' => $jcfCreateOptions,
				]);
    }

		public function getDataForDataTable(Request $request)
		{
			 $data = ServiceTicket::leftJoin('users', 'service_tickets.user_id', '=', 'users.id')
							 ->leftJoin('employees', 'employees.id', '=', 'users.employee_id')
							 ->leftJoin('job_requests', 'job_requests.id', '=', 'service_tickets.job_request_id')
							 ->leftJoin('clients', 'job_requests.client_id', '=', 'clients.id')
							 ->leftJoin('suppliers', 'job_requests.supplier_id', '=', 'suppliers.id')
							 ->select([
									 'service_tickets.id as qutation_id',
									 'service_tickets.code as code',
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

										 if (Auth::user()->can('update', ServiceTicket::find($row->qutation_id)))
										 {
										        if($row->job_request_id){
												    $btn .= '<a href="'.route('serviceTicket.serviceTicketWithJobRequestEdit', $row->job_request_id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
										        }else{
										            $btn .= 'emad';
										        }
										 }

										 if (Auth::user()->can('delete', ServiceTicket::find($row->qutation_id)))
										 {
												 $btn .= '<button type="button" data-id="'.$row->qutation_id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
										 }

										 $btn .= '<div class="btn-group ml-1">
																 <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"></button>
																 <div class="dropdown-menu">';

										 if (Auth::user()->can('create', MailCenter::class) && Auth::user()->can('view', ServiceTicket::find($row->qutation_id)))
										 {
												 $btn .= '<a class="dropdown-item" href="'.route('mailCenter.compose.related', ['relatedType' => 'service_ticket', 'relatedId' => $row->qutation_id]).'">Send via Rig MailCenter</a>';
												 $btn .= '<div class="dropdown-divider"></div>';
										 }

										 if (Storage::disk('public')->exists('pdf/workflow/serviceticket/'.$row->code.'.pdf'))
										 {
												 $btn .= '<a class="dropdown-item" target="_blank" href="'.URL('storage/pdf/workflow/serviceticket/'.$row->code.'.pdf').'">Download PDF</a>';
										 }
										 else
										 {
												 $btn .= '<a class="dropdown-item" href="'.route('serviceTicket.show', $row->qutation_id).'">Upload PDF</a>';
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
										 if(Auth::user()->can('view', ServiceTicket::find($row->qutation_id)))
										 {
												 $report_code .= "<a href=".route('serviceTicket.show', $row->qutation_id).">";
										 }

										 $report_code .= $row->code;

										 if(Auth::user()->can('view', ServiceTicket::find($row->qutation_id)))
										 {
												 $report_code .= "</a>";
										 }
										 return $report_code;
								 })
							 ->orderColumn('code', 'service_tickets.code $1')
							 ->orderColumn('job_request_code', 'job_requests.code $1')
							 ->orderColumn('client', 'COALESCE(clients.name, suppliers.name) $1')
							 ->orderColumn('employee', 'employees.name $1')
							 ->filterColumn('code', function($query, $keyword){
										 $query->whereRaw("service_tickets.code like ?", ["%{$keyword}%"]);
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

    public function serviceTicketWithJobRequest(JobRequest $jobRequest)
    {
	      $this->authorize('create', ServiceTicket::class);

				$jobRequestLocation = $jobRequest->clientDepartment
						? $jobRequest->clientDepartment->name . ' / ' . $jobRequest->deploc
						: $jobRequest->deploc;

				$prefillStartDate = '';
				$prefillEndDate = '';
				if ($jobRequest->jcf_status) {
						$prefillStartDate = $this->formatDateForPicker($jobRequest->jcf_status->start_at);
						$prefillEndDate = $this->formatDateForPicker($jobRequest->jcf_status->end_at);
				}

	      return view('layouts.work-flow.serviceticket.add', [
						'page_name' => $this->page_name(0, $this->page_name),
						'job_request_id' => $jobRequest->id,
						'job_request_code' => $jobRequest->code,
						'deploc' => $jobRequestLocation,
						'job_request_location' => $jobRequestLocation,
						'prefill_start_date' => $prefillStartDate,
						'prefill_end_date' => $prefillEndDate,
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
			$last = ServiceTicket::orderBy('code', 'desc')->latest()->first();
      $last_id = "";
      if (!$last || !str_contains($last, substr(date('Y'), 1))){
        	$last_id = 'S.T-'.substr(date('Y'), 1).'-001';
      }else{
					$last_id = (int)substr($last->code,8);
					$new_id = $last_id+1;
					$last_id = 'S.T-'.substr(date('Y'), 1).'-'.($new_id > 999 ? $new_id : str_pad($new_id, 3,'0',STR_PAD_LEFT));
      }
      $store_service_ticket = ServiceTicket::create([
	        'code' => $last_id,
	        'location' => $request->location,
	        'start' => $this->normalizeDateInput($request->from),
	        'end' => $this->normalizeDateInput($request->to),
	        'job_request_id' => $request->csd,
	        'services' => json_encode($request->items),
	        'notice' => $request->notice,
	        'user_id' => Auth::id(),
	        'sync' => 0,
      ]);

      if ($store_service_ticket)
			{
	        JcfStatus::where('job_request_id', $request->csd)->update(['status' => 3]);
	        return response()->json([
	            'success' => $this->action_message(0, $this->page_name)
	        ]);
      }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceTicket  $serviceTicket
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceTicket $serviceTicket)
    {
				return view('layouts.work-flow.serviceticket.show', [
						'page_name' => $this->page_name,
						'page_text' => 'No. : '.$serviceTicket->code,
						'serviceTicket' => $serviceTicket,
						'person_make_report' => '',
						'person_make_report_desc' => '',
						'report_created_at' => '',
						'esign' => '',
						'pdfurl' => 'storage/pdf/workflow/serviceticket/'.$serviceTicket->code.'.pdf',
						'for_approve_url' => '',
						'imageurl' => $serviceTicket->code,
						'folder' => 'pdf/workflow/serviceticket',
						'user_id_approved' => '',
						'hasPermission' => '',
						'mailcenter_compose_url' => route('mailCenter.compose.related', ['relatedType' => 'service_ticket', 'relatedId' => $serviceTicket->id]),
						'iso_number' => 'Form # RSE-GF-05 - ISSUE 04 / Nov 2021',
						'page_number' => '1 of 1'
				]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceTicket  $serviceTicket
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceTicket $serviceTicket)
    {
        return abort(404);
    }

    public function serviceTicketWithJobRequestEdit(JobRequest $jobRequest)
    {
        $this->authorize('update', $jobRequest->serviceTicket, ServiceTicket::class);

				$jobRequestLocation = $jobRequest->clientDepartment
						? $jobRequest->clientDepartment->name . ' / ' . $jobRequest->deploc
						: $jobRequest->deploc;

				$prefillStartDate = '';
				$prefillEndDate = '';
				if ($jobRequest->serviceTicket) {
						$prefillStartDate = $this->formatDateForPicker($jobRequest->serviceTicket->start);
						$prefillEndDate = $this->formatDateForPicker($jobRequest->serviceTicket->end);
				}

				if ($prefillStartDate === '' && $jobRequest->jcf_status) {
						$prefillStartDate = $this->formatDateForPicker($jobRequest->jcf_status->start_at);
				}
				if ($prefillEndDate === '' && $jobRequest->jcf_status) {
						$prefillEndDate = $this->formatDateForPicker($jobRequest->jcf_status->end_at);
				}

        return view('layouts.work-flow.serviceticket.edit', [
            'job_request_id' => $jobRequest->id,
            'job_request_code' => $jobRequest->code,
            'deploc' => $jobRequestLocation,
            'job_request_location' => $jobRequest->serviceTicket && $jobRequest->serviceTicket->location
                ? $jobRequest->serviceTicket->location
                : $jobRequestLocation,
            'prefill_start_date' => $prefillStartDate,
            'prefill_end_date' => $prefillEndDate,
            'serviceTicket' => $jobRequest->serviceticket,
            'page_name' => $this->page_name(1, $this->page_name),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceTicket  $serviceTicket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceTicket $serviceTicket)
    {
        $update = ServiceTicket::where('id', $serviceTicket->id)->update([
	          'location' => $request->location,
	          'start' => $this->normalizeDateInput($request->from),
	          'end' => $this->normalizeDateInput($request->to),
	          'job_request_id' => $request->csd,
	          'services' => json_encode($request->items),
	          'notice' => $request->notice,
        ]);
        return response()->json([
            'success' => $this->action_message(1, $this->page_name),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceTicket  $serviceTicket
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceTicket $serviceTicket)
    {
				$remove = $serviceTicket->forceDelete();
				if ($remove)
				{
						$pdf = 'pdf/workflow/serviceticket/'.$serviceTicket->code.'.pdf';
						$snap = 'images/pdf/workflow/serviceticket/'.$serviceTicket->code;
						$remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
						return response()->json([
								'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
						]);
				}
    }
}
