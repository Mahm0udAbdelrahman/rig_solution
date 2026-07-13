<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// Illuminate\Support
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route as RouteFacade;
use Carbon\Carbon;

// App\Models
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\JcfStatus;
use App\Models\WorkFlow\Qutation;
use App\Models\WorkFlow\PackingSlip;
use App\Models\WorkFlow\ServiceTicket;
use App\Models\WorkFlow\Invoice;
use App\Models\WorkFlow\Payment;
use App\Models\WorkFlow\Inventory;
use App\Models\WorkFlow\Accountant;
use App\Models\Organization\Department;
use App\Models\Organization\Employee;
use App\Models\Persons\Client;

// Other
use DB, DataTables, Storage;

class JobRequestController extends Controller
{
		public $page_name = 'Job Control Form';

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

		private function formatDateOutput($value, $format = 'd-m-Y')
		{
				if ($value === null) {
						return '';
				}

				try {
						return Carbon::parse($value)->format($format);
				} catch (\Throwable $e) {
						return '';
				}
		}

		private function decodeArrayValue($value): array
		{
				if ($value === null) {
						return [];
				}

				if (is_array($value)) {
						return collect($value)->map(function ($item) {
								return trim((string)$item);
						})->filter()->values()->all();
				}

				$raw = trim((string)$value);
				if ($raw === '') {
						return [];
				}

				$decoded = json_decode($raw, true);
				if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
						return collect($decoded)->map(function ($item) {
								if (is_array($item)) {
										return '';
								}
								return trim((string)$item);
						})->filter()->values()->all();
				}

				return collect(explode(',', $raw))->map(function ($item) {
						return trim((string)$item);
				})->filter()->values()->all();
		}

		private function countJsonItems($value): int
		{
				if ($value === null || trim((string)$value) === '') {
						return 0;
				}

				$decoded = json_decode((string)$value, true);
				if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
						return 0;
				}

				return count($decoded);
		}

		private function getJcfStatusMeta(?string $comment): array
		{
				switch ($comment) {
						case 'in':
								return ['label' => 'Ready', 'class' => 'info'];
						case 'cm':
								return ['label' => 'Completed', 'class' => 'success'];
						case 'cc':
								return ['label' => 'Canceled', 'class' => 'danger'];
						default:
								return ['label' => 'In Progress', 'class' => 'warning'];
				}
		}

		private function normalizeJcfStatusKeyword(?string $keyword): string
		{
				$normalized = strtolower(trim((string) $keyword));
				$normalized = str_replace(['_', '-'], ' ', $normalized);
				$normalized = preg_replace('/\s+/', ' ', $normalized) ?? $normalized;

				return trim($normalized);
		}

		private function applyJcfInProgressConstraint($query): void
		{
				$query->where(function ($statusQuery) {
						$statusQuery->whereNull('jcf_statuses.comment')
								->orWhere('jcf_statuses.comment', '')
								->orWhereRaw('TRIM(jcf_statuses.comment) = ?', ['']);
				});
		}

		private function applyJcfStatusFilterByKeyword($query, ?string $keyword): void
		{
				$normalized = $this->normalizeJcfStatusKeyword($keyword);
				if ($normalized === '') {
						return;
				}

				// Match what user sees in listing labels first.
				if (
						$normalized === 'in'
						|| $normalized === 'in progress'
						|| $normalized === 'inprogress'
						|| $normalized === 'progress'
						|| $normalized === 'open'
						|| str_starts_with('in progress', $normalized)
						|| str_starts_with('open', $normalized)
				) {
						$this->applyJcfInProgressConstraint($query);
						return;
				}

				if (
						$normalized === 'ready'
						|| $normalized === 'rdy'
						|| str_starts_with('ready', $normalized)
				) {
						$query->where('jcf_statuses.comment', 'in');
						return;
				}

				if (
						$normalized === 'completed'
						|| $normalized === 'complete'
						|| $normalized === 'done'
						|| $normalized === 'cm'
						|| str_starts_with('completed', $normalized)
						|| str_starts_with('complete', $normalized)
				) {
						$query->where('jcf_statuses.comment', 'cm');
						return;
				}

				if (
						$normalized === 'canceled'
						|| $normalized === 'cancelled'
						|| $normalized === 'cancel'
						|| $normalized === 'cc'
						|| str_starts_with('canceled', $normalized)
						|| str_starts_with('cancelled', $normalized)
				) {
						$query->where('jcf_statuses.comment', 'cc');
						return;
				}

				$query->whereRaw("LOWER(COALESCE(jcf_statuses.comment, '')) like ?", ['%'.$normalized.'%']);
		}

		private function resolveInspectionShowUrl(?string $reportableType, $reportableId): ?string
		{
				if ($reportableType === null || $reportableId === null) {
						return null;
				}

				$routePrefixes = [
						'App\Models\Inspection\Lifting\Crane' => 'crane',
						'App\Models\Inspection\Lifting\OverheadCrane' => 'overheadCrane',
						'App\Models\Inspection\Lifting\Forklift' => 'forklift',
						'App\Models\Inspection\Lifting\ThroughExamination' => 'throughExamination',
						'App\Models\Inspection\Lifting\Defect' => 'defect',
						'App\Models\Inspection\Lifting\Lregister' => 'lregister',
						'App\Models\Inspection\Ndt\Mpipt' => 'mpipt',
						'App\Models\Inspection\Ndt\Visual' => 'visual',
						'App\Models\Inspection\Ndt\Summary' => 'summary',
						'App\Models\Inspection\Ndt\Attached' => 'attached',
						'App\Models\Inspection\Ndt\Ultrasonic' => 'ultrasonic',
						'App\Models\Inspection\Ndt\WitnessHydro' => 'witnessHydro',
						'App\Models\Inspection\Ndt\TreatingIron' => 'treatingIron',
						'App\Models\Inspection\Ndt\HighPressure' => 'highPressure',
						'App\Models\Inspection\Ndt\High2Pressure' => 'high2Pressure',
						'App\Models\Inspection\Ndt\High3Pressure' => 'high3Pressure',
						'App\Models\Inspection\Ndt\DrawingInspection' => 'drawingInspection',
						'App\Models\Inspection\Ndt\Nregister' => 'nregister',
						'App\Models\Inspection\Tubular\PipesSummaryReport' => 'pipesSummaryReports',
						'App\Models\Inspection\Tubular\DrillPipe' => 'drillPipe',
						'App\Models\Inspection\Tubular\HeavyWeightPipe' => 'heavyWeightPipe',
						'App\Models\Inspection\Tubular\DrillCollar' => 'drillCollar',
						'App\Models\Inspection\Tubular\SubsDimensional' => 'subsDimensional',
						'App\Models\Inspection\Tubular\TubingString' => 'tubingString',
						'App\Models\Inspection\Tubular\StabilizerInspection' => 'stabilizerInspection',
						'App\Models\Inspection\Tubular\ReamerInspection' => 'reamerInspection',
						'App\Models\Inspection\Tubular\LinkInspection' => 'linkInspection',
						'App\Models\Inspection\Tubular\Pbl' => 'pbl',
						'App\Models\Inspection\Tubular\TubingCasing' => 'tubingCasing',
						'App\Models\Inspection\DropObject\DropObject' => 'dropObject',
						'App\Models\Inspection\Calibration\CalibrationPressureGauge' => 'calibrationPressureGauge',
						'App\Models\Inspection\Calibration\CalibrationPressureTest' => 'calibrationPressureTest',
						'App\Models\Inspection\Calibration\CalibrationTorque' => 'calibrationTorque',
						'App\Models\Inspection\Calibration\CalibrationYoke' => 'calibrationYoke',
				];

				$routePrefix = $routePrefixes[$reportableType] ?? null;
				if ($routePrefix === null) {
						return null;
				}

				$routeName = $routePrefix . '.show';
				if (!RouteFacade::has($routeName)) {
						return null;
				}

				try {
						return route($routeName, $reportableId);
				} catch (\Throwable $e) {
						return null;
				}
		}

		private function formatInspectionTypeLabel(?string $reportableType): string
		{
				if ($reportableType === null || trim($reportableType) === '') {
						return 'Unknown';
				}

				$clean = str_replace('App\\Models\\Inspection\\', '', $reportableType);
				$segments = explode('\\', $clean);
				$segments = array_map(function ($segment) {
						$label = preg_replace('/(?<!^)[A-Z]/', ' $0', $segment);
						return trim((string)$label);
				}, $segments);

				return implode(' / ', array_filter($segments));
		}

    public function __construct()
    {
	      $this->middleware('auth');
	      $this->authorizeResource(JobRequest::class, 'jobRequest');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
				$jobrequests = JobRequest::count();
        return view('layouts.work-flow.jcf.index', ['page_name' => $this->page_name('All', $this->page_name), 'jobrequests' => $jobrequests]);
    }

		public function getDataForDataTable(Request $request)
		{
				$data = JobRequest::leftJoin('clients', 'job_requests.client_id', '=', 'clients.id')
								->leftJoin('suppliers', 'job_requests.supplier_id', '=', 'suppliers.id')
								->leftJoin('users', 'job_requests.user_id', '=', 'users.id')
								->leftJoin('employees as creator_employees', 'creator_employees.id', '=', 'users.employee_id')
								->leftJoin('invoices', 'job_requests.id', '=', 'invoices.job_request_id')
								->leftJoin('qutations', 'job_requests.id', '=', 'qutations.job_request_id')
								->leftJoin('jcf_statuses', 'job_requests.id', '=', 'jcf_statuses.job_request_id')
								->leftJoin('packing_slips', 'job_requests.id', '=', 'packing_slips.job_request_id')
								->leftJoin('service_tickets', 'job_requests.id', '=', 'service_tickets.job_request_id')
								->leftJoin('employee_job_request', 'job_requests.id', '=', 'employee_job_request.job_request_id')
								->leftJoin('employees as inspector_employees', 'inspector_employees.id', '=', 'employee_job_request.employee_id')
								->leftJoin('client_departments', 'job_requests.client_department_id', '=', 'client_departments.id')
								->select([
										'jcf_statuses.id as status',
										'jcf_statuses.comment as comment',
										'job_requests.id as job_request_id',
										'job_requests.code as job_request_code',
										'job_requests.client_id as client_id',
										'job_requests.supplier_id as supplier_id',
										'job_requests.deploc as deploc',
										'suppliers.name as sup',
										'clients.name as cli',
										'creator_employees.name as employee',
										'inspector_employees.name as inspector_name',
										'qutations.id as qutation_id',
										'packing_slips.id as packing_slip_id',
										'service_tickets.id as service_ticket_id',
										'invoices.id as invoice_id',
										'invoices.code as invoice',
										'client_departments.name as client_department',
								]);

				switch ($request->get('smart_preset')) {
						case 'in_progress':
								$this->applyJcfInProgressConstraint($data);
								break;
						case 'ready':
								$data->where('jcf_statuses.comment', 'in');
								break;
						case 'completed':
								$data->where('jcf_statuses.comment', 'cm');
								break;
						case 'canceled':
								$data->where('jcf_statuses.comment', 'cc');
								break;
						case 'has_invoice':
								$data->whereNotNull('invoices.code');
								break;
						case 'without_invoice':
								$data->whereNull('invoices.code');
								break;
						case 'client_only':
								$data->whereNotNull('job_requests.client_id')->whereNull('job_requests.supplier_id');
								break;
						case 'supplier_only':
								$data->whereNotNull('job_requests.supplier_id')->whereNull('job_requests.client_id');
								break;
				}

				return  Datatables::of($data)
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
								->addColumn('inspector', function($row){
										return $row->inspector_name ?? '';
								})
								->addColumn('action', function ($row) {
									$btn = "";
									$jobRequestModel = JobRequest::find($row->job_request_id);

									if ($jobRequestModel && Auth::user()->can('view', $jobRequestModel))
									{
											$btn .= '<a href="'.route('jobRequest.overview', $row->job_request_id).'" class="btn btn-icon btn-primary mr-1" title="Overview"><i class="la la-eye"></i></a>';
									}

									if ($jobRequestModel && Auth::user()->can('update', $jobRequestModel))
									{
											$btn .= '<a href="'.route('jobRequest.edit', $row->job_request_id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
									}

									if ($jobRequestModel && Auth::user()->can('delete', $jobRequestModel))
									{
											$btn .= '<button type="button" data-id="'.$row->job_request_id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
									}
									if ($btn === "")
									{
											return $btn;
									}
									return '<div class="jcf-inline-actions">'.$btn.'</div>';
								})
							->addColumn('other_action', function ($row) {
										$btn = '<div class="jcf-inline-status"><div class="btn-group mr-1 wf-table-dropdown">
																<button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false">More</button>
																<div class="dropdown-menu dropdown-menu-right wf-table-dropdown-menu">';
										// In case client
										if ($row->client_id != null && $row->supplier_id == null)
										{
												$btn .= '<h6 class="dropdown-header">Workflow</h6>';
												$qutation_find = Qutation::find($row->qutation_id);
												switch ($qutation_find) {
													case false:
															if (Auth::user()->can('create', Qutation::class))
															{
																	$btn .= '<a class="dropdown-item" href="'.route('qutation.qutationWithJobRequest', $row->job_request_id).'">Make Quotation</a>';
															}
															break;

													default:
															if (Auth::user()->can('view', $qutation_find))
															{
																	$btn .= '<a class="dropdown-item" href="'.route('qutation.show', $row->qutation_id).'">View Quotation</a>';
															}
															break;
												}

												$invoice_find = Invoice::find($row->invoice_id);
												switch ($invoice_find) {
													case false:
															if (Auth::user()->can('create', Invoice::class))
															{
																	// if ($qutation_find)
																	// {
																	// 		$btn .= '<a class="dropdown-item" href="'.route('invoice.invoiceWithJobRequest', $qutation_find->id).'">Make Invoice</a>';
																	// }
																	// else
																	// {
																			$btn .= '<a class="dropdown-item" href="'.route('invoice.invoiceWithJobRequestNew', ['jobRequest' => $row->job_request_id, 'invoice_company_type' => Invoice::$INVOICE_RSE_TYPE]).'">Make RSE Invoice</a>';
																			$btn .= '<a class="dropdown-item" href="'.route('invoice.invoiceWithJobRequestNew', ['jobRequest' => $row->job_request_id, 'invoice_company_type' => Invoice::$INVOICE_LTD_TYPE]).'">Make LTD Invoice</a>';
																	// }
															}
															break;

													default:
															if (Auth::user()->can('view', $invoice_find))
															{
																	$btn .= '<a class="dropdown-item" href="'.route('invoice.show', $row->invoice_id).'">View Invoice</a>';
															}
															break;
												}

												$packing_slip_find = PackingSlip::find($row->packing_slip_id);
												switch ($packing_slip_find) {
													case false:
															if (Auth::user()->can('create', PackingSlip::class))
															{
																	$btn .= '<a class="dropdown-item" href="'.route('packingSlip.packingSlipWithJobRequest', $row->job_request_id).'">Make Packing Slip</a>';
															}
															break;

													default:
															if (Auth::user()->can('view', $packing_slip_find))
															{
																	$btn .= '<a class="dropdown-item" href="'.route('packingSlip.show', $row->packing_slip_id).'">View Packing Slip</a>';
															}
															break;
												}

												$service_ticket_find = ServiceTicket::find($row->service_ticket_id);
												switch ($service_ticket_find) {
													case false:
															if (Auth::user()->can('create', ServiceTicket::class))
															{
																	$btn .= '<a class="dropdown-item" href="'.route('serviceTicket.serviceTicketWithJobRequest', $row->job_request_id).'">Make Service Ticket</a>';
															}
															break;

													default:
															if (Auth::user()->can('view', $service_ticket_find))
															{
																	$btn .= '<a class="dropdown-item" href="'.route('serviceTicket.show', $row->service_ticket_id).'">View Service Ticket</a>';
															}
															break;
												}
										}

										$paymentCount = Payment::where('job_request_id', $row->job_request_id)->count();
										$inventoryCount = Inventory::where('job_request_id', $row->job_request_id)->count();
										$accountantCount = Accountant::where('job_request_id', $row->job_request_id)->count();
										$btn .= '<div class="dropdown-divider"></div><h6 class="dropdown-header">Finance &amp; Ops</h6>';

										if (Auth::user()->can('viewAny', Payment::class))
										{
												$btn .= '<a class="dropdown-item" href="'.route('payment.index', ['job_request_id' => $row->job_request_id]).'">View Payments ('.$paymentCount.')</a>';
										}
										if (Auth::user()->can('create', Payment::class))
										{
												$btn .= '<a class="dropdown-item" href="'.route('payment.paymentWithJobRequest', $row->job_request_id).'">Create Payment</a>';
										}

										if (Auth::user()->can('viewAny', Inventory::class))
										{
												$btn .= '<a class="dropdown-item" href="'.route('inventory.index', ['job_request_id' => $row->job_request_id]).'">View Inventory ('.$inventoryCount.')</a>';
										}
										if (Auth::user()->can('create', Inventory::class))
										{
												$btn .= '<a class="dropdown-item" href="'.route('inventory.inventoryWithJobRequest', $row->job_request_id).'">Create Inventory</a>';
										}

										if (Auth::user()->can('viewAny', Accountant::class))
										{
												$btn .= '<a class="dropdown-item" href="'.route('accountant.index', ['job_request_id' => $row->job_request_id]).'">View Accountant Entries ('.$accountantCount.')</a>';
										}
										if (Auth::user()->can('create', Accountant::class))
										{
												$btn .= '<a class="dropdown-item" href="'.route('accountant.accountantWithJobRequest', $row->job_request_id).'">Create Accountant Entry</a>';
										}

										$btn .= '<div class="dropdown-divider"></div><h6 class="dropdown-header">Files</h6>';
										if (Storage::disk('public')->exists('/pdf/workflow/jcf/'.$row->job_request_code.'.pdf'))
										{
												$btn .= '<a class="dropdown-item" target="_blank" href="'.URL('storage/pdf/workflow/jcf/'.$row->job_request_code.'.pdf').'">Download PDF</a>';
										}
										else
										{
												$btn .= '<a class="dropdown-item" href="'.route('jobRequest.show', $row->job_request_id).'">Upload PDF</a>';
										}
										$btn .= '</div></div>';

										// What is jcf status
										switch ($row->comment) {
												case 'in':
														$btn .= '<button type="button" class="btn btn-info end_at" data-id="'.$row->status.'" data-type="cc">Ready</button>';
														break;
												case 'cm':
														$btn .= '<p class="btn btn-success mr-0">Completed</p>';
														break;
												case 'cc':
														$btn .= '<p class="btn btn-danger">Canceled</p>';
														break;
												default:
														$btn .= '<button type="button" class="btn btn-warning convert" data-id="'.$row->status.'" data-type="in">In Progress</button>';
														break;
										}
										$btn .= '</div>';
										return $btn;
								})
							->editColumn('code', function($row){
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
							->editColumn('invoice', function($row){
									$btn = "";
									if (Auth::user()->can('view', Invoice::find($row->invoice_id)))
									{
											$btn .= '<a href="'.route('invoice.show', $row->invoice_id).'">';
									}

									$btn .= $row->invoice;

									if (Auth::user()->can('view', Invoice::find($row->invoice_id)))
									{
											$btn .= '</a>';
									}
									return $btn;
							})
							->orderColumn('code', 'job_requests.code $1')
							->orderColumn('client', 'COALESCE(clients.name, suppliers.name) $1')
							->orderColumn('client_department', 'client_departments.name $1')
							->orderColumn('deploc', 'job_requests.deploc $1')
							->orderColumn('employee', 'creator_employees.name $1')
							->orderColumn('inspector', 'inspector_employees.name $1')
							->orderColumn('invoice', 'invoices.code $1')
							->orderColumn('other_action', 'jcf_statuses.comment $1')
							->filterColumn('code', function($query, $keyword){
									$query->whereRaw("job_requests.code like ?", ["%{$keyword}%"]);
								})
							->filterColumn('client', function($query, $keyword){
									$query->whereRaw("COALESCE(clients.name, suppliers.name) like ?", ["%{$keyword}%"]);
								})
							->filterColumn('deploc', function($query, $keyword){
									$query->whereRaw("job_requests.deploc like ?", ["%{$keyword}%"]);
								})
							->filterColumn('client_department', function($query, $keyword){
									$query->whereRaw("client_departments.name like ?", ["%{$keyword}%"]);
								})
							->filterColumn('employee', function($query, $keyword){
									$query->whereRaw("creator_employees.name like ?", ["%{$keyword}%"]);
								})
							->filterColumn('invoice', function($query, $keyword){
									$query->whereRaw("invoices.code like ?", ["%{$keyword}%"]);
								})
							->filterColumn('inspector', function($query, $keyword){
									$query->whereRaw("inspector_employees.name like ?", ["%{$keyword}%"]);
							})	
							->filterColumn('other_action', function($query, $keyword){
								$this->applyJcfStatusFilterByKeyword($query, $keyword);
							})
							->rawColumns(['code', 'client', 'action', 'other_action', 'invoice'])
							->make(true);
		}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = DB::table('departments')->select('id', 'name')->get();
        $specifications = DB::table('specifications')->select('id', 'name')->get();
        $tools = DB::table('tools')->select('id', 'name')->get();
        return view('layouts.work-flow.jcf.add', [
	          'page_name' => $this->page_name(0, $this->page_name),
	          'departments' => $departments,
	          'specifications' => $specifications,
	          'tools' => $tools,
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
        $client = NULL;
        $supplier = NULL;
        $clientDepartment = NUll;

        switch ($request->persontype) {
            case '1':
                $client = $request->csd;
                $clientDepartment = $request->client_department_id;
                break;
            case '2':
                $supplier = $request->csd;
                break;
        }

	      $sync = 0;

				$CLIENT_NO = rand(1, 10);
				$CLIENT_NAME = 'LocalMachine';

	      $jcf_ref = $CLIENT_NO.str_shuffle($CLIENT_NAME).rand(1, 99999999999999);

				// To generate jcf reference 1st if it's on server
	      if (str_contains(env('APP_URL'), 'rigsolutionz'))
				{
		        $sync = 1;
		        $jcf_ref = 'sr'.$jcf_ref;
	      }
				else
				{
	        	$jcf_ref = 'cl'.$jcf_ref;
	      }

				$last = JobRequest::orderBy('code', 'desc')->latest()->first();
				$last_id = "";
				if (!$last || !str_contains($last, substr(date('Y'), 1)))
				{
						$last_id = substr(date('Y'), 1).'-001';
				}
				else
				{
						$last_id = (int)substr($last->code,4);
						$new_id = $last_id+1;
						$last_id = substr(date('Y'), 1).'-'.($new_id > 999 ? $new_id : str_pad($new_id, 3,'0',STR_PAD_LEFT));
				}

	      $store_job_request = JobRequest::create([
		        'code' => $last_id,
		        'purchase_order' => $request->purchase_order,
		        'client_id' => $client,
		        'supplier_id' => $supplier,
		        'contact_people_id' => $request->contact,
		        'client_department_id' => $clientDepartment,
		        'subject' => $request->subject,
		        'work_location' => $request->worklocation,
		        'contactway' => $request->contactway,
		        'user_id' => Auth::id(),
		        'job_requierd_details' => str_replace('.', '.<br />',$request->job_requierd_details),
		        'contact_date' => $this->normalizeDateInput($request->contactdate),
		        'managers' => $request->manager,
		        'tools' => $request->tool,
		        'scope_of_work' => str_replace('.', '.<br />',$request->scope_of_work),
		        'specification' => $request->specification,
		        'deploc' => $request->deploc,
		        'sync' => $sync,
		        'updated' => 0,
		        'jcf_ref' => $jcf_ref,
	      ]);

	      if ($store_job_request)
				{
		        $departments = Department::find(json_decode($request->department));
		        $store_job_request->departments()->attach($departments);
		        if($request->eng)
						{
			          $employees = Employee::whereIn('name', json_decode($request->eng))->get();
			          $store_job_request->employees()->attach($employees);
		        }
		        JcfStatus::create([
			          'job_request_id' => $store_job_request->id,
			          'status' => 1,
			          'start_at' => $this->normalizeDateInput($request->startdate),
			          'end_at' => $this->normalizeDateInput($request->enddate),
		        ]);
		        return response()->json([
		          	'success' => $this->action_message(0, $this->page_name)
		        ]);
	      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobRequest  $jobRequest
     * @return \Illuminate\Http\Response
     */
    public function show(JobRequest $jobRequest)
    {
				$departments = DB::table('departments')->select('id', 'name')->get();
				$specifications = DB::table('specifications')->select('id', 'name')->get();
				$tools = DB::table('tools')->select('id', 'name')->get();
	      $jobRequestStatus = JcfStatus::where('job_request_id', $jobRequest->id)->get()[0];
				$serviceTicket = $jobRequest->serviceTicket;
				$documentStartDate = optional($serviceTicket)->start ?: optional($jobRequestStatus)->start_at;
				$documentEndDate = optional($serviceTicket)->end ?: optional($jobRequestStatus)->end_at;
	      return view('layouts.work-flow.jcf.show', [
		        'page_name' => $this->page_name.' (JCF)',
		        'page_text' => '(This part to be filled by Person receiving inquiry/open JCF no.)',
		        'jobRequest' => $jobRequest,
		        'departments' => $departments,
		        'jobRequestStatus' => $jobRequestStatus,
		        'contact_date' => $this->formatDateOutput($jobRequest->contact_date, 'd/m/Y'),
		        'document_start_at' => $this->formatDateOutput($documentStartDate, 'd/m/Y'),
		        'document_end_at' => $this->formatDateOutput($documentEndDate, 'd/m/Y'),
		        'specifications' => $specifications,
		        'tools' => $tools,
		        'person_make_report' => '',
		        'person_make_report_desc' => '',
		        'report_created_at' => $jobRequest->created_at->format('d/m/Y'),
		        'esign' => '',
		        'pdfurl' => 'storage/pdf/workflow/jcf/'.$jobRequest->code.'.pdf',
		        'for_approve_url' => '',
						'imageurl' => $jobRequest->code,
		        'folder' => 'pdf/workflow/jcf',
		        'user_id_approved' => '',
		        'hasPermission' => '',
		        'iso_number' => 'Form # RSE-GF-01 - ISSUE 05 / Nov 2021',
		        'page_number' => '1 of 1'
	      ]);
    }

		public function overview(JobRequest $jobRequest)
		{
				$this->authorize('view', $jobRequest);

				$jobRequest->load([
						'client:id,name',
						'supplier:id,name',
						'contactPeopleShow:id,name,email,tel',
						'clientDepartment:id,name',
						'user.employee:id,name',
						'employees:id,name',
						'departments:id,name',
						'jcf_status:id,job_request_id,status,comment,start_at,end_at',
				]);

				$canViewQutation = Auth::user()->can('viewAny', Qutation::class);
				$canCreateQutation = Auth::user()->can('create', Qutation::class);
				$canViewPackingSlip = Auth::user()->can('viewAny', PackingSlip::class);
				$canCreatePackingSlip = Auth::user()->can('create', PackingSlip::class);
				$canViewServiceTicket = Auth::user()->can('viewAny', ServiceTicket::class);
				$canCreateServiceTicket = Auth::user()->can('create', ServiceTicket::class);
				$canViewInvoice = Auth::user()->can('viewAny', Invoice::class);
				$canCreateInvoice = Auth::user()->can('create', Invoice::class);
				$canViewPayment = Auth::user()->can('viewAny', Payment::class);
				$canCreatePayment = Auth::user()->can('create', Payment::class);
				$canViewInventory = Auth::user()->can('viewAny', Inventory::class);
				$canCreateInventory = Auth::user()->can('create', Inventory::class);
				$canViewAccountant = Auth::user()->can('viewAny', Accountant::class);
				$canCreateAccountant = Auth::user()->can('create', Accountant::class);

				$qutations = Qutation::where('job_request_id', $jobRequest->id)
						->with('user.employee:id,name')
						->latest()
						->get();
				$packingSlips = PackingSlip::where('job_request_id', $jobRequest->id)
						->with('user.employee:id,name')
						->latest()
						->get();
				$serviceTickets = ServiceTicket::where('job_request_id', $jobRequest->id)
						->with('user.employee:id,name')
						->latest()
						->get();
				$invoices = Invoice::where('job_request_id', $jobRequest->id)
						->with('user.employee:id,name')
						->latest()
						->get();
				$payments = Payment::where('job_request_id', $jobRequest->id)
						->with(['user.employee:id,name', 'invoice:id,code,invoice_company_type'])
						->latest()
						->get();
				$inventories = Inventory::where('job_request_id', $jobRequest->id)
						->with('user.employee:id,name')
						->latest()
						->get();
				$accountants = Accountant::where('job_request_id', $jobRequest->id)
						->with(['user.employee:id,name', 'invoice:id,code,invoice_company_type', 'payment:id,code'])
						->latest()
						->get();

				if ($canViewQutation) {
						$qutations = $qutations->filter(function (Qutation $qutation) {
								return Auth::user()->can('view', $qutation);
						})->values();
				} else {
						$qutations = collect();
				}

				if ($canViewPackingSlip) {
						$packingSlips = $packingSlips->filter(function (PackingSlip $packingSlip) {
								return Auth::user()->can('view', $packingSlip);
						})->values();
				} else {
						$packingSlips = collect();
				}

				if ($canViewServiceTicket) {
						$serviceTickets = $serviceTickets->filter(function (ServiceTicket $serviceTicket) {
								return Auth::user()->can('view', $serviceTicket);
						})->values();
				} else {
						$serviceTickets = collect();
				}

				if ($canViewInvoice) {
						$invoices = $invoices->filter(function (Invoice $invoice) {
								return Auth::user()->can('view', $invoice);
						})->values();
				} else {
						$invoices = collect();
				}

				if ($canViewPayment) {
						$payments = $payments->filter(function (Payment $payment) {
								return Auth::user()->can('view', $payment);
						})->values();
				} else {
						$payments = collect();
				}

				if ($canViewInventory) {
						$inventories = $inventories->filter(function (Inventory $inventory) {
								return Auth::user()->can('view', $inventory);
						})->values();
				} else {
						$inventories = collect();
				}

				if ($canViewAccountant) {
						$accountants = $accountants->filter(function (Accountant $accountant) {
								return Auth::user()->can('view', $accountant);
						})->values();
				} else {
						$accountants = collect();
				}

				$invoicesRse = $invoices->where('invoice_company_type', Invoice::$INVOICE_RSE_TYPE)->values();
				$invoicesLtd = $invoices->where('invoice_company_type', Invoice::$INVOICE_LTD_TYPE)->values();
				$paymentTotalAmount = $payments->sum(function ($payment) {
						return (float) ($payment->amount ?? 0);
				});
				$inventoryLowStockCount = $inventories->filter(function ($inventory) {
						$minimum = (float) ($inventory->min_quantity ?? 0);
						$quantity = (float) ($inventory->quantity ?? 0);
						return $minimum > 0 && $quantity <= $minimum;
				})->count();
				$accountantPendingCount = $accountants->filter(function ($accountant) {
						return strtolower((string) $accountant->status) === 'pending_approval';
				})->count();

				$inspectionRows = $jobRequest->inspection_reports()
						->latest()
						->get()
						->map(function ($inspectionReport) use ($jobRequest) {
								$reportableModel = $inspectionReport->reportable;
								if ($reportableModel === null || !Auth::user()->can('view', $reportableModel)) {
										return null;
								}

								$relativePdfPath = 'pdf/' . strtolower(str_replace('App\\Models\\', '', (string)$inspectionReport->reportable_type)) . '/' . $jobRequest->code . '/' . $inspectionReport->code . '.pdf';
								$pdfUrl = Storage::disk('public')->exists($relativePdfPath) ? Storage::url($relativePdfPath) : null;

								return [
										'id' => $inspectionReport->id,
										'type_label' => $this->formatInspectionTypeLabel($inspectionReport->reportable_type),
										'display_code' => $jobRequest->code . ' / ' . $inspectionReport->code,
										'status' => (int)$inspectionReport->publish === 1 ? 'Published' : 'Draft',
										'status_class' => (int)$inspectionReport->publish === 1 ? 'success' : 'secondary',
										'updated_at' => $this->formatDateOutput($inspectionReport->updated_at, 'd-m-Y H:i'),
										'open_url' => $this->resolveInspectionShowUrl($inspectionReport->reportable_type, $inspectionReport->reportable_id),
										'pdf_url' => $pdfUrl,
								];
						})
						->filter()
						->values();

				$inspectionTypeSummary = $inspectionRows
						->groupBy('type_label')
						->map(function ($rows) {
								return $rows->count();
						})
						->sortDesc();

				$statusMeta = $this->getJcfStatusMeta(optional($jobRequest->jcf_status)->comment);
				$ownerName = optional($jobRequest->client)->name ?: optional($jobRequest->supplier)->name;
				$ownerType = $jobRequest->client_id ? 'Client' : 'Supplier';
				$createdByName = optional(optional($jobRequest->user)->employee)->name ?: 'System';
				$serviceTicket = $jobRequest->serviceTicket;
				$displayStartDate = optional($serviceTicket)->start ?: optional($jobRequest->jcf_status)->start_at;
				$displayEndDate = optional($serviceTicket)->end ?: optional($jobRequest->jcf_status)->end_at;

				return view('layouts.work-flow.jcf.overview', [
						'page_name' => 'JCF Overview',
						'jobRequest' => $jobRequest,
						'owner_name' => $ownerName ?: '-',
						'owner_type' => $ownerType,
						'created_by_name' => $createdByName,
						'status_meta' => $statusMeta,
						'contact_date' => $this->formatDateOutput($jobRequest->contact_date, 'd/m/Y'),
						'status_start_at' => $this->formatDateOutput($displayStartDate, 'd/m/Y'),
						'status_end_at' => $this->formatDateOutput($displayEndDate, 'd/m/Y'),
						'created_at_formatted' => $this->formatDateOutput($jobRequest->created_at, 'd-m-Y H:i'),
						'updated_at_formatted' => $this->formatDateOutput($jobRequest->updated_at, 'd-m-Y H:i'),
						'contact_way_list' => $this->decodeArrayValue($jobRequest->contactway),
						'work_location_list' => $this->decodeArrayValue($jobRequest->work_location),
						'manager_list' => $this->decodeArrayValue($jobRequest->managers),
						'tool_list' => $this->decodeArrayValue($jobRequest->tools),
						'specification_list' => $this->decodeArrayValue($jobRequest->specification),
						'department_names' => $jobRequest->departments->pluck('name')->filter()->values(),
						'inspector_names' => $jobRequest->employees->pluck('name')->filter()->values(),
						'qutations' => $qutations,
						'packing_slips' => $packingSlips,
						'service_tickets' => $serviceTickets,
						'invoices_rse' => $invoicesRse,
						'invoices_ltd' => $invoicesLtd,
						'invoices_total' => $invoices,
						'payments' => $payments,
						'inventories' => $inventories,
						'accountants' => $accountants,
						'inspection_rows' => $inspectionRows,
						'inspection_type_summary' => $inspectionTypeSummary,
						'can_view_qutation' => $canViewQutation,
						'can_create_qutation' => $canCreateQutation,
						'can_view_packing_slip' => $canViewPackingSlip,
						'can_create_packing_slip' => $canCreatePackingSlip,
						'can_view_service_ticket' => $canViewServiceTicket,
						'can_create_service_ticket' => $canCreateServiceTicket,
						'can_view_invoice' => $canViewInvoice,
						'can_create_invoice' => $canCreateInvoice,
						'can_view_payment' => $canViewPayment,
						'can_create_payment' => $canCreatePayment,
						'can_view_inventory' => $canViewInventory,
						'can_create_inventory' => $canCreateInventory,
						'can_view_accountant' => $canViewAccountant,
						'can_create_accountant' => $canCreateAccountant,
						'payment_total_amount' => $paymentTotalAmount,
						'inventory_low_stock_count' => $inventoryLowStockCount,
						'accountant_pending_count' => $accountantPendingCount,
						'qutation_items_count' => $qutations->sum(function ($qutation) {
								return $this->countJsonItems($qutation->items);
						}),
						'packing_items_count' => $packingSlips->sum(function ($packingSlip) {
								return $this->countJsonItems($packingSlip->items);
						}),
						'service_items_count' => $serviceTickets->sum(function ($serviceTicket) {
								return $this->countJsonItems($serviceTicket->services);
						}),
				]);
		}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobRequest  $jobRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(JobRequest $jobRequest)
    {
				$departments = DB::table('departments')->select('id', 'name')->get();
        $specifications = DB::table('specifications')->select('id', 'name')->get();
        $tools = DB::table('tools')->select('id', 'name')->get();
        $dates = DB::table('jcf_statuses')->where('job_request_id', $jobRequest->id)->select('start_at', 'end_at')->get();
				$serviceTicket = $jobRequest->serviceTicket;
				$startDate = $this->formatDateOutput(optional($serviceTicket)->start ?: optional($dates->first())->start_at, 'd/m/Y');
				$endDate = $this->formatDateOutput(optional($serviceTicket)->end ?: optional($dates->first())->end_at, 'd/m/Y');
        return view('layouts.work-flow.jcf.edit', [
	          'page_name' => $this->page_name(1, $this->page_name),
	          'jobRequest' => $jobRequest,
	          'departments' => $departments,
	          'specifications' => $specifications,
	          'tools' => $tools,
	          'dates' => $dates,
	          'start_date_value' => $startDate,
	          'end_date_value' => $endDate,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\JobRequest $jobRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobRequest $jobRequest)
    {
        $client = NULL;
        $supplier = NULL;
        $clientDepartment = NUll;
        switch ($request->persontype) {
            case '1':
                $client = $request->csd;
                $clientDepartment = $request->client_department_id;
                break;

            case '2':
                $supplier = $request->csd;
                break;
        }

        $update = JobRequest::where('id', $jobRequest->id)->update([
            'purchase_order' => $request->purchase_order,
            'client_id' => $client,
            'supplier_id' => $supplier,
            'contact_people_id' => $request->contact,
            'client_department_id' => $clientDepartment,
            'subject' => $request->subject,
            'work_location' => $request->worklocation,
            'contactway' => $request->contactway,
            'job_requierd_details' => str_replace('.', '.<br />', $request->job_requierd_details),
            'contact_date' => $this->normalizeDateInput($request->contactdate),
            'managers' => $request->manager,
            'tools' => $request->tool,
            'scope_of_work' => str_replace('.', '.<br />', $request->scope_of_work),
            'specification' => $request->specification,
            'deploc' => $request->deploc,
            'updated' => 1,
        ]);

        if ($update) {
            $jobRequest->departments()->sync(json_decode($request->department));
            if ($request->eng) {
                $employees = Employee::whereIn('name', json_decode($request->eng))->get();
                $jobRequest->employees()->sync($employees);
            }
            JcfStatus::where('job_request_id', $jobRequest->id)->update([
                'start_at' => $this->normalizeDateInput($request->startdate),
                'end_at' => $this->normalizeDateInput($request->enddate),
            ]);
            return response()->json([
                'success' => $this->action_message(1, $this->page_name)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobRequest  $jobRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobRequest $jobRequest)
    {
        $remove = $jobRequest->forceDelete();
				if ($remove)
				{
						$pdf = '/pdf/workflow/jcf/'.$jobRequest->code.'.pdf';
						$snap = 'images/jcf/'.$jobRequest->code;
						$remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
		        return response()->json([
		          	'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
		        ]);
	      }
    }
}
