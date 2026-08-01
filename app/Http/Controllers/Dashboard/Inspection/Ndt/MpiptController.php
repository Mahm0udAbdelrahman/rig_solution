<?php
namespace App\Http\Controllers\Dashboard\Inspection\Ndt;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Inspection\Ndt\Mpipt;
use App\Models\GeneralInfo\Specification;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;

// Other
use DB, DataTables, Storage, Auth, Crypt;

class MpiptController extends Controller
{
    public $page_name = 'MT or PT Inspection Report';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Mpipt::class, 'mpipt');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mpipts = Mpipt::count();
        return view('layouts.inspection.ndt.mpipt.index', ['page_name' => $this->page_name('All', $this->page_name), 'mpipts' => $mpipts]);
    }

    public function getDataForDataTable()
    {
        $canViewMpipt = Auth::user()->hasPermission('mpipt', 'show');
        $canUpdateMpipt = Auth::user()->hasPermission('mpipt', 'edit');
        $canDeleteMpipt = Auth::user()->hasPermission('mpipt', 'delete');
        $canViewClient = Auth::user()->hasPermission('client', 'show');
        $canViewSupplier = Auth::user()->hasPermission('supplier', 'show');

        $latestRows = Mpipt::query()
            ->selectRaw('MAX(mpipts.id) as id')
            ->groupBy('mpipts.job_request_id', 'mpipts.code');

        $data = Mpipt::query()
            ->joinSub($latestRows, 'latest_mpipts', function ($join) {
                $join->on('mpipts.id', '=', 'latest_mpipts.id');
            })
            ->join('job_requests', 'mpipts.job_request_id', '=', 'job_requests.id')
            ->join('inspection_reports', function ($join) {
                $join->on('mpipts.id', '=', 'inspection_reports.reportable_id')
                    ->where('inspection_reports.reportable_type', '=', 'App\Models\Inspection\Ndt\Mpipt');
            })
            ->leftJoin('clients', 'job_requests.client_id', '=', 'clients.id')
            ->leftJoin('suppliers', 'job_requests.supplier_id', '=', 'suppliers.id')
            ->leftJoin('client_departments', 'job_requests.client_department_id', '=', 'client_departments.id')
            ->leftJoin('file_managers as inspection_pdfs', function ($join) {
                $join->on('inspection_pdfs.job_request_code', '=', 'job_requests.code')
                    ->on('inspection_pdfs.entity_code', '=', 'mpipts.code')
                    ->where('inspection_pdfs.module', '=', 'inspection')
                    ->where('inspection_pdfs.category', '=', 'ndt/mpipt')
                    ->where('inspection_pdfs.extension', '=', 'pdf')
                    ->where('inspection_pdfs.is_available', '=', 1);
            })
            ->select([
                'mpipts.id',
                'mpipts.id as mpipt_id',
                'mpipts.job_request_id',
                'mpipts.code as inspection_code',
                'mpipts.nmpr_28',
                'mpipts.acceptance',
                'mpipts.desc as desc',
                'clients.id as client_id',
                'suppliers.id as supplier_id',
                'inspection_reports.id as report_id',
                'inspection_reports.code as inspection_report_code',
                'inspection_reports.user_id_approved as user_id_approved',
                'inspection_reports.publish as publish',
                DB::raw("CONCAT(job_requests.code,'/',mpipts.code) as report_code"),
                'job_requests.code as job_request_code',
                'job_requests.deploc as deploc',
                'client_departments.name as client_department',
                'inspection_pdfs.path as pdf_path',
                DB::raw("COALESCE(clients.name, suppliers.name) as client"),
            ]);

        $this->applyInspectionApprovalPresetFilter($data, request('smart_preset'));

        return Datatables::eloquent($data)

            ->addColumn('approval_status', function ($row) {
                return $this->inspectionApprovalStatusValueByRow($row);
            })
            ->addColumn('publish_status', function ($row) {
                return $this->inspectionPublishStatusValueByRow($row);
            })
            ->addColumn('revision_count', function ($row) {
                return $this->inspectionRevisionCountValueByRow($row);
            })
            ->addColumn('client', function ($row) use ($canViewClient, $canViewSupplier) {
                $label = e((string) $row->client);
                if ($label === '') {
                    return '';
                }

                if (!empty($row->client_id) && $canViewClient) {
                    return '<a href="' . route('client.show', $row->client_id) . '">' . $label . '</a>';
                }

                if (!empty($row->supplier_id) && $canViewSupplier) {
                    return '<a href="' . route('supplier.show', $row->supplier_id) . '">' . $label . '</a>';
                }

                return $label;
            })
            ->addColumn('code', function ($row) use ($canViewMpipt) {
                $reportCode = (string) $row->report_code;
                if ($reportCode === '') {
                    return '';
                }

                $reportCode = e($reportCode);

                return $canViewMpipt
                    ? '<a href="' . route('mpipt.show', $row->mpipt_id) . '">' . $reportCode . '</a>'
                    : $reportCode;
            })
            ->addColumn('deploc', function ($row) {
                return (string) $row->deploc;
            })
            ->addColumn('client_department', function ($row) {
                return (string) ($row->client_department ?? '');
            })
            ->addColumn('action', function ($row) use ($canUpdateMpipt, $canDeleteMpipt) {
                $btn = "";

                if (Auth::user()->can('create', Mpipt::class)) {
                    if (!empty($row->report_id)) {
                        $btn .= '<button data-id="' . $row->report_id . '" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                    }
                }

                if (empty($row->report_id)) {
                    return $btn;
                }

                if (!is_null($row->publish)) {
                    if (!empty($row->pdf_path)) {
                        $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . Storage::url((string) $row->pdf_path) . '">Download PDF</a>';
                    } else {
                        $btn .= '<a class="btn btn-dark mr-1" href="' . route('mpipt.show', $row->mpipt_id) . '">Open Report</a>';
                    }

                    if ($canUpdateMpipt) {
                        $btn .= '<a href="' . route('mpipt.edit', $row->mpipt_id) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }
                } else {
                    $btn .= $this->buildInspectionUnpublishedActionButtons($row, Mpipt::class, (int) ($row->mpipt_id), 'mpipt.show', 'publish.mpipt', 'mpipt.edit');
                }


                if ($canDeleteMpipt) {
                    $btn .= '<button type="button" data-id="' . $row->mpipt_id . '" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
                }
                return $btn;
            })
            ->filterColumn('nmpr_28', function ($query, $keyword) {
                $query->whereRaw("mpipts.nmpr_28 like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('acceptance', function ($query, $keyword) {
                $query->whereRaw("mpipts.acceptance like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('desc', function ($query, $keyword) {
                $query->whereRaw("`mpipts`.`desc` like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('code', function ($query, $keyword) {
                $query->whereRaw("CONCAT(job_requests.code,'/',mpipts.code) like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('deploc', function ($query, $keyword) {
                $query->whereRaw("job_requests.deploc like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('client_department', function ($query, $keyword) {
                $query->whereRaw("client_departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('client', function ($query, $keyword) {
                $query->where(function ($builder) use ($keyword) {
                    $builder->whereRaw("clients.name like ?", ["%{$keyword}%"])
                        ->orWhereRaw("suppliers.name like ?", ["%{$keyword}%"]);
                });
            })
            ->filterColumn('action', function ($query, $keyword) {
                switch ($keyword) {
                    case 'publish':
                        $query->whereNull('inspection_reports.publish');
                        break;
                    case 'download':
                        $query->whereNotNull('inspection_pdfs.path');
                        break;
                    case 'upload':
                        $query->whereNotNull('inspection_reports.publish');
                        $query->whereNull('inspection_pdfs.path');
                        break;
                }
            })

            ->filter(function ($query) {
                $this->applyInspectionGlobalSearch($query, request('search.value'));
            })
            ->rawColumns(['code', 'client', 'action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $specifications = DB::table('specifications')->select('id', 'name')->get();
        $jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.mpipt.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'jobrequests' => $jobrequests,
            'specifications' => $specifications
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
        $newone = [];
        foreach ($request['payments'] as $key => $value) {
            $newone[$key]['nmpr_49'] = $value['nmpr_49'];
            $path_crypt = '';
            if (isset($value['nmpr_50']) && $value['nmpr_50'] != NULL) {
                $path_crypt = Crypt::encryptString($value['nmpr_50']->getClientOriginalName());
                $store = $value['nmpr_50']->storeAs(
                    'public/camera/inspection/ndt/mpipts',
                    $path_crypt
                );
                $newone[$key]['nmpr_50'] = $path_crypt;
            } else {
                $newone[$key]['nmpr_50'] = '';
            }
        }

        $job_request = JobRequest::find($request->lcr_1);
        $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;

        $store = Mpipt::create([
            'job_request_id' => $request->lcr_1,
            'nmpr_2' => $request->nmpr_2,
            'code' => $code,
            'nmpr_6' => $request->nmpr_6,
            'nmpr_7' => $request->nmpr_7,
            'nmpr_8' => $request->nmpr_8,
            'nmpr_10' => $request->nmpr_10,
            'acceptance' => $request->nmpr_11,
            'nmpr_12' => $request->nmpr_12,
            'nmpr_13' => $request->nmpr_13,
            'desc' => $request->nmpr_47,
            'nmpr_28' => $request->nmpr_48,
            'nmpr_29' => json_encode($newone),
            'nmpr_30' => $request->nmpr_51,
            'nmpr_800' => $request->nmpr_800,
            'nmpr_900' => $request->nmpr_900,
//            'temperature' => $request->temperature,
            'sync' => 0,
        ]);

        if ($store) {
            $store->report()->create([
                'job_request_id' => $request->lcr_1,
                'code' => $code,
                'status' => 1,
                'publish' => null,
                'sync' => 1,
                'user_id' => Auth::id(),
            ]);
            return response()->json([
                'last_id' => $store->id,
                'success' => $this->action_message(0, $this->page_name)
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mpipt  $mpipt
     * @return \Illuminate\Http\Response
     */
    public function show(Mpipt $mpipt)
    {
        $have_edit = $this->check_if_report_edit(Mpipt::class, $mpipt);
        $mpipt->report = $have_edit->default;

        // return $have_edit->default->user_id_approved;

        return view('layouts.inspection.ndt.mpipt.show', [
            'page_name' => $this->page_name,
            'page_text' => '',
            'mpipt' => $mpipt,
            'model' => $mpipt,
            'code' => $mpipt->job_request->code . '/' . $mpipt->code,
            'report_created_at' => $mpipt->created_at->format('d/m/Y'),
            'pdfurl' => 'storage/pdf/inspection/ndt/mpipt/' . $mpipt->job_request->code . '/' . $mpipt->code . '.pdf',
            'hasPermission' => Auth::user()->hasPermission('mpipt', 'approve'),
            'imageurl' => $mpipt->job_request->code . '/' . $mpipt->code,
            'folder' => 'pdf/inspection/ndt/mpipt',
            'iso_number' => 'Form # RSE-RF-07 - ISSUE 07 / Jul 2023',
            'page_number' => '1 of 1',
            'have_edit' => $have_edit->edited,
            'user_id_approved' => data_get($mpipt, 'report.user_id_approved'),
            'for_approve_url' => data_get($mpipt, 'report.id'),
            'esign' => data_get($mpipt, 'report.user.employee.esign'),
            'person_make_report' => data_get($mpipt, 'report.user.employee.name'),
            'person_make_report_desc' => data_get($mpipt, 'report.user.employee.desc'),
        ]);
    }

    public function publish(Mpipt $mpipt)
    {
        $this->authorize('create', Mpipt::class);
        $specifications = DB::table('specifications')->select('id', 'name')->get();
        $jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.mpipt.publish', [
            'page_name' => $this->page_name(0, $this->page_name) . ' (Publish)',
            'mpipt' => $mpipt,
            'jobrequests' => $jobrequests,
            'specifications' => $specifications
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mpipt  $mpipt
     * @return \Illuminate\Http\Response
     */
    public function edit(Mpipt $mpipt)
    {
        $specifications = DB::table('specifications')->select('id', 'name')->get();
        $jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.mpipt.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'mpipt' => $mpipt,
            'jobrequests' => $jobrequests,
            'specifications' => $specifications
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mpipt  $mpipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mpipt $mpipt)
    {
        $code = $this->resolveInspectionSubmittedCode($request, $mpipt);

        $newone = [];
        foreach ($request['payments'] as $key => $value) {
            $newone[$key]['nmpr_49'] = $value['nmpr_49'];
            $path_crypt = '';
            if (isset($value['nmpr_50']) && $value['nmpr_50'] != NULL) {
                $path_crypt = Crypt::encryptString($value['nmpr_50']->getClientOriginalName());
                $store = $value['nmpr_50']->storeAs(
                    'public/camera/inspection/ndt/mpipts',
                    $path_crypt
                );
                $newone[$key]['nmpr_50'] = $path_crypt;
            } else {
                $newone[$key]['nmpr_50'] = $value['lcr_140'];
            }
        }

        $data = [
            'job_request_id' => $request->lcr_1,
            'nmpr_2' => $request->nmpr_2,
            'code' => $code,
            'nmpr_6' => $request->nmpr_6,
            'nmpr_7' => $request->nmpr_7,
            'nmpr_8' => $request->nmpr_8,
            'nmpr_10' => $request->nmpr_10,
            'acceptance' => $request->nmpr_11,
            'nmpr_12' => $request->nmpr_12,
            'nmpr_13' => $request->nmpr_13,
            'desc' => $request->nmpr_47,
            'nmpr_28' => $request->nmpr_48,
            'nmpr_29' => json_encode($newone),
            'nmpr_30' => $request->nmpr_51,
            'nmpr_800' => $request->nmpr_800,
            'nmpr_900' => $request->nmpr_900,
            'sync' => 0,
//            'temperature' => $request->temperature
        ];

        $user_approved = NULL;

        if ($this->shouldForkApprovedInspectionRevision($mpipt)) {
            $update = $mpipt->create($data);
            $report_id = $update->id;
        } else {
            $update = $mpipt->update($data);
            $report_id = $mpipt->id;
            $user_approved = $mpipt->report->user_id_approved;
        }

        if ($update) {
            $user = Auth::id();
            if ($request->publish == 'yes') {
                $user = NULL;
                $last = InspectionReport::where('job_request_id', $request->lcr_1)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
            }

            $this->persistInspectionReportState($mpipt, [
                'code' => $code,
                'status' => 1,
                'publish' => $request->publish == 'yes' ? 1 : $mpipt->report->publish,
                'user_id_approved' => $user_approved,
                'reportable_id' => $report_id,
                'user_id_edit' => $user,
            ]);

            return response()->json([
                'success' => $this->action_message(1, $this->page_name)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mpipt  $mpipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mpipt $mpipt)
    {
        $remove = $this->check_edited_reports_for_delete($mpipt);

        if ($remove) {
            $pdf = 'inspection/ndt/mpipt/' . $mpipt->job_request->code . '/' . $mpipt->code . '.pdf';
            $snap = 'images/inspection/ndt/mpipt/' . $mpipt->job_request->code . '/' . $mpipt->code;
            $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
            return response()->json([
                'success' => $remove_snap_shots_pdf . $this->action_message(2, $this->page_name)
            ]);
        }
    }
}
