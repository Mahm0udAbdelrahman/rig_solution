<?php

namespace App\Http\Controllers\Dashboard\Persons;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// Illuminate\Support
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Crypt;
// use Illuminate\Support\Facades\Storage;

// App\Models
use App\Models\Persons\Client;
use App\Models\Inspection\InspectionReport;
use App\Models\WorkFlow\JobRequest;
use App\Models\User;
use Illuminate\Support\Collection;

// Other
use DB, DataTables, Auth, Storage, Crypt, Hash;

class ClientController extends Controller
{
    public $page_name = 'Client';
    public $person_type = 'c';

    public function __construct()
    {
	      $this->middleware('auth');
	      $this->authorizeResource(Client::class, 'client');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::count();
        return view('layouts.persons.client.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'clients' => $clients,
            'route' => 'client',
            'model' => 'App\Models\Persons\Client',
            'type' => $this->person_type,
        ]);
    }

    public function getDataForDataTable()
		{
        $data = Client::query();
        return  Datatables::eloquent($data)
              ->addColumn('action', function ($row) {
                    $btn = "";

                    if (Auth::user()->can('view', Client::find($row->id)))
                    {
                        $btn .= '<a href="'.route('client.show', $row->id).'" class="btn btn-icon btn-success mr-1 btn11">Overview</a>';
                    }

                    if (Auth::user()->can('update', Client::find($row->id)))
                    {
                        $btn .= '<a href="'.route('client.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }

                    if (Auth::user()->can('delete', Client::find($row->id)))
                    {
                        $btn .= '<button type="button" data-id="'.$row->id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
                    }
                    if ($btn === '') {
                        return '';
                    }
                    return '<div class="wf-inline-actions">'.$btn.'</div>';
                })
              ->editColumn('logo', function($row){
										return '<img class="media-object" src="'.Storage::url('persons/clients/').$row->logo.'" alt="" width="64">';
								})
              ->editColumn('code', function($row){
                    $report_code = "";
                    if(Auth::user()->can('view', Client::find($row->id)))
                    {
                        $report_code .= "<a href=".route('client.show', $row->id).">";
                    }

                    $report_code .= $row->code;

                    if(Auth::user()->can('view', Client::find($row->id)))
                    {
                        $report_code .= "</a>";
                    }
                    return $report_code;
								})
              ->rawColumns(['logo', 'code' ,'action'])
              ->make('true');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $last = Client::orderBy('id', 'desc')->latest()->first();
        $last_id = 'CLI-000';
        if (!$last)
        {
            $last_id;
        }
        else
        {
            $last_id = $last->code;
        }
        return view('layouts.persons.client.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'last_id' => $last_id,
            'route' => 'client.store'
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
        $path = $request->file('logo');
        $path_crypt = NULL;
        if ($path != NULL)
        {
            $path_crypt = Crypt::encryptString($path->getClientOriginalName());
            $store = $path->storeAs(
                'public/persons/clients',
                $path_crypt
            );
        }

        $store_client = Client::create([
            'code' => $request->code,
            'name' => $request->uname,
            'password' => Hash::make($request->upassword),
            'email' => $request->uemail,
            'tel' => $request->utel,
            'tax_card' => $request->tax_card,
            'fax' => $request->fax,
            'location' => $request->address,
            'url' => $request->url,
            'desc' => $request->desc,
            'logo' => $path_crypt,
        ]);

        if($store_client){
            return response()->json([
                'success' => $this->action_message(0, $this->page_name)
            ]);
        }
    }

    public function contactPersonStore(Request $request, Client $client)
    {
        $store_contact_person = $client->conatctPerson()->create([
            'name' => $request->sname,
            'email' => $request->semail,
            'tel' => $request->stel,
            'postion' => $request->stitle,
        ]);

        if($store_contact_person)
        {
            return response()->json([
                'success' => 'Contact Person Created successfully.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        $client->load(['conatctPerson', 'departments']);

        $jobRequests = JobRequest::query()
            ->where('client_id', $client->id)
            ->with([
                'clientDepartment:id,name',
                'inspection_reports' => function ($query) {
                    $query->with(['job_request:id,code', 'reportable'])
                        ->orderByDesc('updated_at');
                },
                'invoice.payments',
                'payments',
                'qutation',
                'packingSlip',
                'serviceTicket',
            ])
            ->orderByDesc('id')
            ->get();

        $publishedReports = $this->publishedReportsFromJobRequests($jobRequests);
        $invoiceSnapshots = $this->buildInvoiceSnapshots($jobRequests);
        $payments = $jobRequests->flatMap(function (JobRequest $jobRequest) {
            return $jobRequest->payments;
        })->sortByDesc(function ($payment) {
            return $payment->payment_date ?: optional($payment->created_at)->timestamp;
        })->values();

        return view('layouts.persons.client.show', [
            'page_name' => 'Client Overview',
            'route' => 'client',
            'client' => $client,
            'overview_cards' => [
                [
                    'label' => 'Published Inspections',
                    'value' => $publishedReports->count(),
                    'note' => 'Published inspection certificates linked to this client.',
                ],
                [
                    'label' => 'Open JCFs',
                    'value' => $jobRequests->count(),
                    'note' => 'Job control forms registered for this client.',
                ],
                [
                    'label' => 'Departments',
                    'value' => $client->departments->count(),
                    'note' => 'Client departments with portal access.',
                ],
                [
                    'label' => 'Contact Persons',
                    'value' => $client->conatctPerson->count(),
                    'note' => 'Registered contact entries for this client.',
                ],
            ],
            'inspection_section_cards' => $this->buildInspectionSectionCards($publishedReports),
            'recent_published_reports' => $this->buildPublishedReportRows(
                $publishedReports
                    ->sortByDesc(function (InspectionReport $report) {
                        return optional($report->updated_at)->timestamp ?: optional($report->created_at)->timestamp;
                    })
                    ->take(8)
                    ->values()
            ),
            'invoice_snapshots' => $invoiceSnapshots->take(8)->values(),
            'recent_payments' => $this->buildRecentPaymentRows($payments->take(8)),
            'workflow_rows' => $this->buildWorkflowRows($jobRequests),
        ]);
    }

    public function contactPersonShow(Client $client)
    {
        return response()->json([
            'contactperson' => $client->conatctPerson,
            'code' => $client->code,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return view('layouts.persons.client.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'route' => 'client.update',
            'client' => $client
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $password = $request->upassword;
        if ($password != NULL)
        {
            $password = Hash::make($password);
        }
        else
        {
            $password = $client->password;
        }

        $path = $request->file('logo');
        if ($path != NULL)
        {
            Storage::disk('public')->delete('persons/clients/'.$client->logo);
            $path_crypt = Crypt::encryptString($path->getClientOriginalName());
            $store = $path->storeAs(
                'public/persons/clients',
                $path_crypt
            );
        }
        else
        {
            if($request->imagedata == '')
            {
                Storage::disk('public')->delete('persons/clients/'.$client->logo);
            }
            $path_crypt = $request->imagedata;
        }

        $update = $client->update([
            'name' => $request->uname,
            'password' => $password,
            'email' => $request->uemail,
            'tel' => $request->utel,
            'tax_card' => $request->tax_card,
            'fax' => $request->fax,
            'location' => $request->address,
            'url' => $request->url,
            'desc' => $request->desc,
            'logo' => $path_crypt,
        ]);

        if ($update)
        {
            return response()->json([
                'success' => $this->action_message(1, $this->page_name)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        if (Storage::disk('public')->exists('persons/clients/'.$client->logo))
        {
            Storage::disk('public')->delete('persons/clients/'.$client->logo);
        }
        $remove = $client->forceDelete();
        if ($remove)
        {
            return response()->json([
                'success' => $this->action_message(2, $this->page_name)
            ]);
        }
    }

    public function departmentStore(Request $request, Client $client)
    {
        $store_department = $client->departments()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'description' => $request->description,
        ]);

        if($store_department)
        {
            return response()->json([
                'success' => 'Department Created successfully.'
            ]);
        }
    }

    public function departmentShow(Client $client)
    {
        return response()->json([
            'departments' => $client->departments,
            'code' => $client->code,
        ]);
    }

    private function publishedReportsFromJobRequests(Collection $jobRequests): Collection
    {
        return $jobRequests->flatMap(function (JobRequest $jobRequest) {
            return $jobRequest->inspection_reports;
        })->filter(function (InspectionReport $report) {
            return (int) ($report->publish ?? 0) !== 0 && $report->reportable !== null;
        })->values();
    }

    private function buildInspectionSectionCards(Collection $publishedReports): Collection
    {
        return $publishedReports
            ->groupBy(function (InspectionReport $report) {
                $cleanType = str_replace('App\\Models\\Inspection\\', '', (string) $report->reportable_type);
                $segments = array_values(array_filter(explode('\\', $cleanType)));
                return $segments[0] ?? 'Inspection';
            })
            ->map(function (Collection $reports, string $label) {
                $latestReport = $reports->sortByDesc(function (InspectionReport $report) {
                    return optional($report->updated_at)->timestamp ?: optional($report->created_at)->timestamp;
                })->first();

                $latestCode = optional($latestReport)->code;
                if ($latestReport && optional($latestReport->job_request)->code) {
                    $latestCode = $latestReport->job_request->code.'/'.$latestReport->code;
                }

                return [
                    'label' => $label,
                    'count' => $reports->count(),
                    'latest_code' => $latestCode,
                ];
            })
            ->sortByDesc('count')
            ->values();
    }

    private function buildInvoiceSnapshots(Collection $jobRequests): Collection
    {
        return $jobRequests
            ->map(function (JobRequest $jobRequest) {
                $invoice = $jobRequest->invoice;
                if (!$invoice) {
                    return null;
                }

                $paidAmount = (float) $invoice->payments->sum(function ($payment) {
                    return (float) ($payment->amount ?? 0);
                });
                $totalAmount = (float) ($invoice->total ?? 0);
                $dueAmount = max($totalAmount - $paidAmount, 0);

                return [
                    'job_request_code' => $jobRequest->code,
                    'job_request_url' => route('jobRequest.show', $jobRequest->id),
                    'invoice_code' => $invoice->code,
                    'invoice_url' => route('invoice.show', $invoice->id),
                    'total_amount' => $totalAmount,
                    'paid_amount' => $paidAmount,
                    'due_amount' => $dueAmount,
                    'created_at' => $invoice->created_at,
                ];
            })
            ->filter()
            ->sortByDesc(function (array $invoice) {
                return optional($invoice['created_at'])->timestamp;
            })
            ->values();
    }

    private function buildWorkflowRows(Collection $jobRequests): Collection
    {
        return $jobRequests
            ->map(function (JobRequest $jobRequest) {
                $publishedCount = $jobRequest->inspection_reports
                    ->filter(function (InspectionReport $report) {
                        return (int) ($report->publish ?? 0) !== 0;
                    })
                    ->count();

                return [
                    'job_request_code' => $jobRequest->code,
                    'job_request_url' => route('jobRequest.show', $jobRequest->id),
                    'department' => optional($jobRequest->clientDepartment)->name ?: '-',
                    'location' => $jobRequest->deploc ?: ($jobRequest->work_location ?: '-'),
                    'quotation' => $jobRequest->qutation !== null,
                    'packing_slip' => $jobRequest->packingSlip !== null,
                    'service_ticket' => $jobRequest->serviceTicket !== null,
                    'invoice' => $jobRequest->invoice !== null,
                    'payment' => $jobRequest->payments->isNotEmpty(),
                    'published_inspections' => $publishedCount,
                ];
            })
            ->sortByDesc('job_request_code')
            ->values();
    }

    private function buildPublishedReportRows(Collection $reports): Collection
    {
        return $reports->map(function (InspectionReport $report) {
            $pdfPath = $this->resolveInspectionPdfPath($report);
            $sectionLabel = 'Inspection';
            $typeLabel = trim(preg_replace('/(?<!^)[A-Z]/', ' $0', class_basename((string) $report->reportable_type)));

            $cleanType = str_replace('App\\Models\\Inspection\\', '', (string) $report->reportable_type);
            $segments = array_values(array_filter(explode('\\', $cleanType)));
            if (!empty($segments[0])) {
                $sectionLabel = $segments[0];
            }

            return [
                'certificate_code' => optional($report->job_request)->code.'/'.(string) $report->code,
                'type_label' => $typeLabel,
                'section_label' => $sectionLabel,
                'published_label' => 'Published',
                'open_url' => $pdfPath ? Storage::url($pdfPath) : null,
                'download_url' => $pdfPath ? Storage::url($pdfPath) : null,
            ];
        })->values();
    }

    private function buildRecentPaymentRows(Collection $payments): Collection
    {
        return $payments->map(function ($payment) {
            return [
                'payment_code' => $payment->code,
                'payment_url' => route('payment.show', $payment->id),
                'job_request_code' => optional($payment->jobRequest)->code ?: '-',
                'job_request_url' => $payment->jobRequest ? route('jobRequest.show', $payment->jobRequest->id) : null,
                'payment_date' => $payment->payment_date ?: optional($payment->created_at)->toDateString(),
                'method' => $payment->method ?: '-',
                'amount' => (float) ($payment->amount ?? 0),
            ];
        })->values();
    }

    private function resolveInspectionPdfPath(InspectionReport $inspectionReport): ?string
    {
        $jobCode = optional($inspectionReport->job_request)->code;
        $reportCode = trim((string) ($inspectionReport->code ?? ''));
        $reportableType = trim((string) ($inspectionReport->reportable_type ?? ''));

        if ($jobCode === null || trim($jobCode) === '' || $reportCode === '' || $reportableType === '') {
            return null;
        }

        $suffix = trim($jobCode).'/'.$reportCode.'.pdf';
        $cleanType = str_replace('App\\Models\\Inspection\\', '', $reportableType);
        $segments = array_values(array_filter(explode('\\', $cleanType)));
        $module = strtolower($segments[0] ?? '');
        $class = $segments[1] ?? '';
        $classLower = strtolower($class);
        $classCamel = $class !== '' ? lcfirst($class) : '';

        $candidates = [];
        if ($module !== '' && $classLower !== '') {
            $candidates[] = 'pdf/inspection/'.$module.'/'.$classLower.'/'.$suffix;
        }
        if ($module !== '' && $classCamel !== '' && $classCamel !== $classLower) {
            $candidates[] = 'pdf/inspection/'.$module.'/'.$classCamel.'/'.$suffix;
        }
        if ($reportableType === 'App\\Models\\Inspection\\Tubular\\PipesSummaryReport') {
            $candidates[] = 'pdf/inspection/tubular/summary/'.$suffix;
        }

        foreach (array_values(array_unique($candidates)) as $path) {
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
