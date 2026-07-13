<?php

namespace App\Http\Controllers\Dashboard\Inspection\Lifting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;

// Illuminate\Http
use Illuminate\Http\Request;

// Illuminate\Support
use Illuminate\Support\Facades\Notification;

// App\Models
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Lifting\Defect;
use App\Models\Persons\Client;
use App\Models\User;

// App\Notifications
use App\Notifications\InspectionReports;

// Other
use DB, DataTables, Storage, Auth, Crypt;


class DefectController extends Controller
{
    public $page_name = 'Defect Report';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Defect::class, 'defect');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $defects = Defect::query();
        $this->applyPreferredInspectionFamilyRowConstraint($defects, 'defects', Defect::class);
        $defects = $defects->count();
        return view('layouts.inspection.lifting.defect.index', ['page_name' => $this->page_name('All', $this->page_name), 'defects' => $defects]);
    }

    public function getDataForDataTable()
    {
        // $data = Defect::query()->has('report')->orderBy('id', 'Desc');
        $data = Defect::join('job_requests', 'defects.job_request_id', '=', 'job_requests.id')
            ->join('inspection_reports', function ($join) {
                $join->on('defects.id', '=', 'inspection_reports.reportable_id')
                    ->where('inspection_reports.reportable_type', '=', 'App\Models\Inspection\Lifting\Defect');
            })
            ->join('clients', 'job_requests.client_id', '=', 'clients.id')
            ->leftJoin('client_departments', 'job_requests.client_department_id', '=', 'client_departments.id')
            ->select([
                'defects.id as defect_id',
                'clients.id as client_id',
                'inspection_reports.id  as report_id',
                'inspection_reports.publish as publish',
                DB::raw("CONCAT(job_requests.code,'/',defects.code) as report_code"),
                'job_requests.deploc as deploc',
                'client_departments.name as client_department',
                'defects.ldr_8',
                DB::raw("clients.name as client"),
            ]);
        $this->applyPreferredInspectionFamilyRowConstraint($data, 'defects', Defect::class);

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
->addColumn('id_number', function ($row) {
                $lcr_10 = "";
                foreach (json_decode($row->ldr_8) as $key => $value) {
                    $lcr_10 .= $value->lcr_10;
                    if ($value->lcr_10 != NULL) {
                        $lcr_10 .= ' . <br />';
                    }
                }
                return $lcr_10;
            })
            ->addColumn('client', function ($row) {
                $report_code = "";
                if (Auth::user()->can('view', Client::find($row->client_id))) {
                    $report_code .= "<a href=" . route('client.show', $row->client_id) . ">";
                }

                $report_code .= $row->client;

                if (Auth::user()->can('view', Client::find($row->client_id))) {
                    $report_code .= "</a>";
                }
                return $report_code;
            })
            ->addColumn('code', function ($row) {
                $report_code = "";
                if (Auth::user()->can('view', Defect::find($row->defect_id))) {
                    $report_code .= "<a href=" . route('defect.show', $row->defect_id) . ">";
                }

                $report_code .= $row->report_code;

                if (Auth::user()->can('view', Defect::find($row->defect_id))) {
                    $report_code .= "</a>";
                }
                return $report_code;
            })
            ->addColumn('action', function ($row) {
                $btn = "";
                if (Auth::user()->can('create', Defect::class)) {
                    $btn .= '<button data-id="' . $row->report_id . '" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                }

                if ($row->publish != NULL) {
                    if (Storage::disk('public')->exists('pdf/inspection/lifting/defect/' . $row->report_code . '.pdf')) {
                        $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . Storage::url('pdf/inspection/lifting/defect/' . $row->report_code . '.pdf') . '">Download PDF</a>';
                    } else {
                        $btn .= '<a class="btn btn-dark mr-1" href="' . route('defect.show', $row->defect_id) . '">Open Report</a>';
                    }

                    if (Auth::user()->can('update', Defect::find($row->defect_id))) {
                        $btn .= '<a href="' . route('defect.edit', $row->defect_id) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }
                } else {
                    $btn .= $this->buildInspectionUnpublishedActionButtons($row, Defect::class, (int) ($row->defect_id), 'defect.show', 'publish.defect', 'defect.edit');
                }


                if (Auth::user()->can('delete', Defect::find($row->defect_id))) {
                    $btn .= '<button type="button" data-id="' . $row->defect_id . '" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
                }
                return $btn;
            })
            //   ->filterColumn('id_number', function($query, $keyword){
            //     $query->whereRaw("lter_10 like ?", ["%{$keyword}%"]);
            //   })
            // ->filterColumn('desc', function($query, $keyword){
            //     $query->whereRaw("lter_10 like ?", ["%{$keyword}%"]);
            //   })
            // ->filterColumn('swl', function($query, $keyword){
            //     $query->whereRaw("lter_10 like ?", ["%{$keyword}%"]);
            //   })
            ->filterColumn('code', function ($query, $keyword) {
				$query->whereRaw("CONCAT(job_requests.code,'/',defects.code) like ?", ["%{$keyword}%"]);
			})
            ->filterColumn('id_number', function ($query, $keyword) {
                $query->whereRaw("JSON_EXTRACT(ldr_8, '$[0].\"lcr_10\"') like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('client', function ($query, $keyword) {
                $query->whereHas('job_request', function ($row) use (&$keyword) {
                    $row->whereHas('client', function ($row1) use (&$keyword) {
                        $row1->where("name", "like", ["%{$keyword}%"]);
                    });
                });
            })
            ->filterColumn('deploc', function ($query, $keyword) {
                $query->whereRaw("job_requests.deploc like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('client_department', function ($query, $keyword) {
                $query->whereRaw("client_departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('action', function ($query, $keyword) {
                $codes = $this->getUploadedCodes('/lifting/defect');
                switch ($keyword) {
                    case 'publish':
                        $query->whereRaw("inspection_reports.publish IS NULL");
                        break;
                    case 'download':
                        $query->whereRaw("CONCAT(job_requests.code,'/',defects.code) IN ($codes)");
                        break;
                    case 'upload':
                        $query->whereRaw("inspection_reports.publish IS NOT NULL AND CONCAT(job_requests.code,'/',defects.code) NOT IN ($codes)");
                        break;
                }
            })
            
            ->filter(function ($query) {
                $this->applyInspectionGlobalSearch($query, request('search.value'));
            })
            ->rawColumns(['code', 'id_number', 'client', 'action'])
            
            ->filter(function ($query) {
                $this->applyInspectionGlobalSearch($query, request('search.value'));
            })
            ->make('true');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $jobrequests = DB::table('job_requests')->select('id', 'code', 'purchase_order')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.lifting.defect.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'jobrequests' => $jobrequests
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
        /******************************/
        $newone = [];
        foreach ($request['payments'] as $key => $value) {
            $newone[$key]['lcr_10'] = $value['lcr_10'];
            $newone[$key]['lcr_11'] = $value['lcr_11'];
            $newone[$key]['lcr_12'] = $value['lcr_12'];
            $newone[$key]['lcr_13'] = $value['lcr_13'];
            $path_crypt = '';
            if (isset($value['lcr_14']) && $value['lcr_14'] != NULL) {
                $path_crypt = Crypt::encryptString($value['lcr_14']->getClientOriginalName());
                $store = $value['lcr_14']->storeAs(
                    'public/camera/inspection/lifting/defect',
                    $path_crypt
                );
                $newone[$key]['lcr_14'] = $path_crypt;
            } else {
                $newone[$key]['lcr_14'] = '';
            }
        }

        $job_request = JobRequest::find($request->lcr_1);
        $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;

        /***************************/
        $store = Defect::create([
            'job_request_id' => $request->lcr_1,
            'ldr_2' => $request->lcr_2,
            'code' => $code,
            'ldr_6' => $request->lcr_6,
            // 'ldr_7' => $request->lcr_9,
            'ldr_8' => json_encode($newone),
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
            // Notification::send(User::all(), new InspectionReport(str_pad($store->code+1, 3,'0',STR_PAD_LEFT), $request->lcr_1));
            return response()->json([
                'last_id' => $store->id,
                'success' => $this->action_message(0, $this->page_name)
            ]);
        }
    }

    public function publish(Defect $defect)
    {
        $this->authorize('create', Defect::class);
        $clients = Client::select('id', 'code', 'name')->get();
        $jobrequests = JobRequest::select('id', 'code')->orderBy('id', 'Desc')->get();
        return view('layouts.inspection.lifting.defect.publish', [
            'page_name' => $this->page_name(0, $this->page_name) . ' (Publish)',
            'defect' => $defect,
            'clients' => $clients,
            'jobrequests' => $jobrequests
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Defect  $defect
     * @return \Illuminate\Http\Response
     */
    public function show(Defect $defect)
    {
        $have_edit = $this->check_if_report_edit(Defect::class, $defect);
        $defect->report = $have_edit->default;
        $model = $defect;

        return view('layouts.inspection.lifting.defect.show', [
            'page_name' => $this->page_name,
            'page_text' => '',
            'defect' => $defect,
            'model' => $defect,
            'code' => $defect->job_request->code . '/' . $defect->code,
            'report_created_at' => $defect->created_at->format('d/m/Y'),
            'pdfurl' => 'storage/pdf/inspection/lifting/defect/' . $defect->job_request->code . '/' . $defect->code . '.pdf',
            'hasPermission' => Auth::user()->hasPermission('defect', 'approve'),
            'imageurl' => $defect->job_request->code . '/' . $defect->code,
            'folder' => 'pdf/inspection/lifting/defect',
            'iso_number' => 'Form # RSE-RF-05 - ISSUE 04 / Jan 2022',
            'page_number' => '1 of 1',
            'have_edit' => $have_edit->edited,
            'user_id_approved' => data_get($model, 'report.user_id_approved'),
            'for_approve_url' => data_get($model, 'report.id'),
            'esign' => data_get($model, 'report.user.employee.esign'),
            'person_make_report' => data_get($model, 'report.user.employee.name'),
            'person_make_report_desc' => data_get($model, 'report.user.employee.desc'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Defect  $defect
     * @return \Illuminate\Http\Response
     */
    public function edit(Defect $defect)
    {
        $jobrequests = JobRequest::select('id', 'code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.lifting.defect.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'defect' => $defect,
            'jobrequests' => $jobrequests
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Defect  $defect
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Defect $defect)
    {


        $code = $this->resolveInspectionSubmittedCode($request, $defect);


        $newone = [];
        foreach ($request['payments'] as $key => $value) {
            $newone[$key]['lcr_10'] = $value['lcr_10'];
            $newone[$key]['lcr_11'] = $value['lcr_11'];
            $newone[$key]['lcr_12'] = $value['lcr_12'];
            $newone[$key]['lcr_13'] = $value['lcr_13'];
            $path_crypt = '';
            if (isset($value['lcr_14']) && $value['lcr_14'] != NULL) {
                $path_crypt = Crypt::encryptString($value['lcr_14']->getClientOriginalName());
                $store = $value['lcr_14']->storeAs(
                    'public/camera/inspection/lifting/defect',
                    $path_crypt
                );
                $newone[$key]['lcr_14'] = $path_crypt;
            } else {
                $newone[$key]['lcr_14'] = array_key_exists('lcr_140', $value) ? $value['lcr_140'] : '';
            }
        }

        $data = [
            'job_request_id' => $request->lcr_1,
            'ldr_2' => $request->lcr_2,
            'code' => $code,
            'ldr_6' => $request->lcr_6,
            // 'ldr_7' => $request->lcr_9,
            'ldr_8' => json_encode($newone),
            'sync' => 0,
        ];

        $user_approved = NULL;

        if ($this->shouldForkApprovedInspectionRevision($defect)) {
            $update = $defect->create($data);
            $report_id = $update->id;
        } else {
            $update = $defect->update($data);
            $report_id = $defect->id;
            $user_approved = $defect->report->user_id_approved;
        }

        if ($update) {
            $user = Auth::id();
            if ($request->publish == 'yes') {
                $user = NULL;
                $last = InspectionReport::where('job_request_id', $request->lcr_1)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
                // Notification::send(User::all(), new InspectionReport(str_pad($last->code+1, 3,'0',STR_PAD_LEFT), $request->lcr_1));
            }

            $this->persistInspectionReportState($defect, [
                'code' => $code,
                'status' => 1,
                'publish' => $request->publish == 'yes' ? 1 : $defect->report->publish,
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
     * @param  \App\Models\Defect  $defect
     * @return \Illuminate\Http\Response
     */
    public function destroy(Defect $defect)
    {
        $remove = $this->check_edited_reports_for_delete($defect);

        if ($remove) {
            $pdf = 'inspection/lifting/defect/' . $defect->job_request->code . '/' . $defect->code . '.pdf';
            $snap = 'images/inspection/lifting/defect/' . $defect->job_request->code . '/' . $defect->code;
            $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
            return response()->json([
                'success' => $remove_snap_shots_pdf . $this->action_message(2, $this->page_name)
            ]);
        }
    }
}
