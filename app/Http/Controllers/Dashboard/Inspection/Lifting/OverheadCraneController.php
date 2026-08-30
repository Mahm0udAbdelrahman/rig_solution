<?php

namespace App\Http\Controllers\Dashboard\Inspection\Lifting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Inspection\Lifting\OverheadCrane;
use App\Models\Inspection\Lifting\OverheadCrane2;
use App\Models\Persons\Client;
use App\Models\User;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;

// Other
use DB, DataTables, Storage, Auth, Crypt;

class OverheadCraneController extends Controller
{
    public $page_name = 'Lifting Overhead Crane Report';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(OverheadCrane::class, 'overheadCrane');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $overhead_cranes = OverheadCrane::query();
        $this->applyPreferredInspectionFamilyRowConstraint($overhead_cranes, 'overhead_cranes', OverheadCrane::class);
        $overhead_cranes = $overhead_cranes->count();
        return view('layouts.inspection.lifting.overheadcrane.index', ['page_name' => $this->page_name('All', $this->page_name), 'overhead_cranes' => $overhead_cranes]);
    }

    public function getDataForDataTable()
		{
        $data = OverheadCrane::join('job_requests', 'overhead_cranes.job_request_id', '=', 'job_requests.id')
            ->join('inspection_reports', function ($join) {
                    $join->on('overhead_cranes.id', '=', 'inspection_reports.reportable_id')
                     ->where('inspection_reports.reportable_type', '=', 'App\Models\Inspection\Lifting\OverheadCrane');
                })
            ->join('clients', 'job_requests.client_id', '=', 'clients.id')
            ->leftJoin('client_departments', 'job_requests.client_department_id', '=', 'client_departments.id')
            ->select([
                'overhead_cranes.id as overhead_crane_id',
                'clients.id as client_id',
                'inspection_reports.id  as report_id',
                'inspection_reports.publish as publish',
                DB::raw("CONCAT(job_requests.code,'/',overhead_cranes.code) as report_code"),
                'job_requests.deploc as deploc',
                'client_departments.name as client_department',
                'overhead_cranes.locr_12 as id_number',
                'overhead_cranes.locr_10 as desc',
                DB::raw("clients.name as client"),
            ]);
        $this->applyPreferredInspectionFamilyRowConstraint($data, 'overhead_cranes', OverheadCrane::class);
        // \Log::debug($data);    
            $this->applyInspectionApprovalPresetFilter($data, request('smart_preset'));
            return  Datatables::eloquent($data)
            
            ->addColumn('approval_status', function ($row) {
                return $this->inspectionApprovalStatusValueByRow($row);
            })
            ->addColumn('publish_status', function ($row) {
                return $this->inspectionPublishStatusValueByRow($row);
            })
            ->addColumn('revision_count', function ($row) {
                return $this->inspectionRevisionCountValueByRow($row);
            })
->editColumn('report_code', function($row){
                $report_code = "";
                if(Auth::user()->can('view', OverheadCrane::find($row->overhead_crane_id)))
                {
                        $report_code .= "<a href=".route('overheadCrane.show', $row->overhead_crane_id).">";
                }

                $report_code .= $row->report_code;

                if(Auth::user()->can('view', OverheadCrane::find($row->overhead_crane_id)))
                {
                        $report_code .= "</a>";
                }
                return $report_code;
            })
            ->editColumn('client', function ($row) {
                $report_code = "";
                if (Auth::user()->can('view', Client::find($row->client_id)))
                {
                $report_code .= "<a href=".route('client.show', $row->client_id).">";
                }

                $report_code .= $row->client;

                if (Auth::user()->can('view', Client::find($row->client_id)))
                {
                    $report_code .= "</a>";
                }
                return $report_code;
            })
            ->addColumn('action', function ($row) {
                $btn = "";
                if (Auth::user()->can('create', OverheadCrane::class))
                {
                        $btn .= '<button data-id="'.$row->report_id.'" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                }

                if ($row->publish != NULL)
                {
                        if (Storage::disk('public')->exists('pdf/inspection/lifting/overheadcrane/'.$row->report_code.'.pdf'))
                        {
                            $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="'.Storage::url('pdf/inspection/lifting/overheadcrane/'.$row->report_code.'.pdf').'">Download PDF</a>';
                        }
                        else
                        {
                            $btn .= '<a class="btn btn-dark mr-1" href="'.route('overheadCrane.show', $row->overhead_crane_id).'">Open Report</a>';
                        }

                        if (Auth::user()->can('update', OverheadCrane::find($row->overhead_crane_id)))
                        {
                            $btn .= '<div class="btn-group mr-1">
                          <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="la la-pencil"></i></button>
                          <div class="dropdown-menu">
                              <a href="'.route('overheadCrane.edit', $row->overhead_crane_id).'" class="dropdown-item">Edit 1st Page</a>
                              <div class="dropdown-divider"></div>';
                              if (OverheadCrane::find($row->overhead_crane_id)->overheadcrane2)
                                {
                                    $btn .= '<a href="'.route('overhead_crane2_edit.overhead_crane', $row->overhead_crane_id).'" class="dropdown-item">Edit 2nd Page</a>';
                                }
                                else
                                {
                                    $btn .= '<a href="'.route('overhead_crane2_create.overhead_crane', $row->overhead_crane_id).'" class="dropdown-item">Create 2nd Page</a>';
                                }
                $btn .= '   </div>
                        </div>';
                        }
                }
                else
                {
                        $btn .= $this->buildInspectionUnpublishedMultiPageActionButtons(
                            OverheadCrane::class,
                            (int) ($row->overhead_crane_id),
                            'overheadCrane.show',
                            'publish.overheadCrane',
                            'overheadCrane.edit',
                            'overheadcrane2',
                            'overhead_crane2_create.overhead_crane',
                            'overhead_crane2_edit.overhead_crane'
                        );
                }


                if (Auth::user()->can('delete', OverheadCrane::find($row->overhead_crane_id)))
                {
                        $btn .= '<button type="button" data-id="'.$row->overhead_crane_id.'" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
                }
                return $btn;
            })
            ->filterColumn('report_code', function($query, $keyword){
                $query->whereRaw("CONCAT(job_requests.code,'/',overhead_cranes.code) like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('id_number', function($query, $keyword){
                $query->whereRaw("overhead_cranes.locr_12 like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('desc', function($query, $keyword){
                $query->whereRaw("overhead_cranes.locr_10 like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('client', function($query, $keyword){
                $query->whereRaw("clients.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('deploc', function ($query, $keyword) {
                $query->whereRaw("job_requests.deploc like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('client_department', function ($query, $keyword) {
                $query->whereRaw("client_departments.name like ?", ["%{$keyword}%"]);
            })
                ->filterColumn('action', function ($query, $keyword) {
                    $codes = $this->getUploadedCodes('/lifting/overheadcrane');
                    switch ($keyword) {
                        case 'publish':
                            $query->whereRaw("inspection_reports.publish IS NULL");
                            break;
                        case 'download':
                            $query->whereRaw("CONCAT(job_requests.code,'/',overhead_cranes.code) IN ($codes)");
                            break;
                        case 'upload':
                            $query->whereRaw("inspection_reports.publish IS NOT NULL AND CONCAT(job_requests.code,'/',overhead_cranes.code) NOT IN ($codes)");
                            break;
                    }
                })
            
            ->filter(function ($query) {
                $this->applyInspectionGlobalSearch($query, request('search.value'));
            })
            ->rawColumns(['report_code', 'client', 'action'])
            
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
        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.lifting.overheadcrane.add', [
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
        $job_request = JobRequest::find($request->lcr_1);
        $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;

        $store = OverheadCrane::create([
            'job_request_id' => $request->lcr_1,
            'locr_2' => $request->locr_2,
            'code' => $code,
            'locr_6' => $request->lcr_6,
            'locr_7' => $request->lcr_7,
            'locr_8' => $request->locr_8,
            'locr_10' => $request->locr_10,
            'locr_11' => $request->locr_11,
            'locr_12' => $request->locr_12,
            'locr_13' => $request->locr_13,
            'locr_14' => $request->locr_14,
            'locr_15' => $request->locr_15,
            'locr_16' => $request->locr_16,
            'locr_17' => $request->locr_17,
            'locr_18' => $request->locr_18,
            'locr_19' => $request->locr_19,
            'locr_20' => $request->locr_20,
            'locr_21' => $request->locr_21,
            'locr_22' => $request->locr_22,
            'locr_23' => $request->locr_23,
            'locr_24' => $request->locr_24,
            'locr_25' => $request->locr_25,
            'locr_26' => $request->locr_26,
            'locr_27' => $request->locr_27,
            'locr_28' => $request->locr_28,
            'locr_29' => $request->locr_29,
            'locr_30' => $request->locr_30,
            'locr_31' => $request->locr_31,
            'locr_32' => $request->locr_32,
            'locr_33' => $request->locr_33,
            'locr_34' => $request->locr_34,
            'locr_35' => $request->locr_35,
            'locr_36' => $request->locr_36,
            'locr_37' => $request->locr_37,
            'locr_38' => $request->locr_38,
            'locr_39' => $request->locr_39,
            'locr_40' => $request->locr_40,
            'locr_41' => $request->locr_41,
            'locr_42' => $request->locr_42,
            'locr_43' => $request->locr_43,
            'locr_44' => $request->locr_44,
            'sync' => 0
        ]);

      if($store)
      {
          $store->report()->create([
              'job_request_id' => $request->lcr_1,
              'code' => $code,
              'status' => 1,
              'publish' => null,
              'sync' => 1,
              'user_id' => Auth::id(),
          ]);
          // Notification::send(User::all(), new InspectionReport(str_pad($store->code+1, 3,'0',STR_PAD_LEFT), $request->locr_1));
          return response()->json([
              'last_id' => $store->id,
              'success' => $this->action_message(0, $this->page_name)
          ]);
      }
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OverheadCrane  $overheadCrane
     * @return \Illuminate\Http\Response
     */
    public function show(OverheadCrane $overheadCrane)
    {
        $have_edit = $this->check_if_report_edit(OverheadCrane::class, $overheadCrane);
		$overheadCrane->report = $have_edit->default;
        $model = $overheadCrane;
        $revisionDisplay = $this->resolveInspectionRevisionDisplayMeta(OverheadCrane::class, $overheadCrane);

        return view('layouts.inspection.lifting.overheadcrane.show', [
            'page_name' => 'Through Examination And / Or Test Certificate Of Overhead Cranes',
            'page_text' => 'This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998?',
            'overheadcrane' => $overheadCrane,
            'model' => $overheadCrane,
            'code' => $overheadCrane->job_request->code.'/'.$overheadCrane->code,
            'overheadcrane2' => $overheadCrane->overhead_crane2,
            'report_created_at' => $overheadCrane->created_at->format('d/m/Y'),
            'pdfurl' => 'storage/pdf/inspection/lifting/overheadcrane/'.$overheadCrane->job_request->code.'/'.$overheadCrane->code.'.pdf',
            'imageurl' => $overheadCrane->job_request->code.'/'.$overheadCrane->code,
            'folder' => 'pdf/inspection/lifting/overheadcrane',
            'hasPermission' => Auth::user()->hasPermission('overheadcrane', 'approve'),
            'iso_number' => 'Form # RSE-RF-02 - ISSUE 05 / Jan 2022',
            'page_number' => $overheadCrane->overhead_crane2? '1 of 2' : '1 of 1',
            'have_edit' => $have_edit->edited,
			'revision_display_no' => $revisionDisplay['number'],
			'revision_display_date' => $revisionDisplay['date'],
			'user_id_approved' => data_get($model, 'report.user_id_approved'),
			'for_approve_url' => data_get($model, 'report.id'),
			'esign' => data_get($model, 'report.user.employee.esign'),
			'person_make_report' => data_get($model, 'report.user.employee.name'),
			'person_make_report_desc' => data_get($model, 'report.user.employee.desc'),
        ]);
    }

    public function publish(OverheadCrane $overheadCrane)
    {
      $this->authorize('create', OverheadCrane::class);
      $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
      return view('layouts.inspection.lifting.overheadcrane.publish', [
          'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
          'overheadcrane' => $overheadCrane,
          'jobrequests' => $jobrequests
      ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OverheadCrane  $overheadCrane
     * @return \Illuminate\Http\Response
     */
    public function edit(OverheadCrane $overheadCrane)
    {
        return view('layouts.inspection.lifting.overheadcrane.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'overheadcrane' => $overheadCrane,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OverheadCrane  $overheadCrane
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OverheadCrane $overheadCrane)
    {
        $isApprovedRevision = $this->shouldForkApprovedInspectionRevision($overheadCrane);
        $code = $this->resolveInspectionSubmittedCode($request, $overheadCrane);

        $data = [
            'job_request_id' => $request->lcr_1,
            'locr_2' => $request->locr_2,
            'code' => $code,
            'locr_6' => $request->lcr_6,
            'locr_7' => $request->lcr_7,
            'locr_8' => $request->locr_8,
            'locr_10' => $request->locr_10,
            'locr_11' => $request->locr_11,
            'locr_12' => $request->locr_12,
            'locr_13' => $request->locr_13,
            'locr_14' => $request->locr_14,
            'locr_15' => $request->locr_15,
            'locr_16' => $request->locr_16,
            'locr_17' => $request->locr_17,
            'locr_18' => $request->locr_18,
            'locr_19' => $request->locr_19,
            'locr_20' => $request->locr_20,
            'locr_21' => $request->locr_21,
            'locr_22' => $request->locr_22,
            'locr_23' => $request->locr_23,
            'locr_24' => $request->locr_24,
            'locr_25' => $request->locr_25,
            'locr_26' => $request->locr_26,
            'locr_27' => $request->locr_27,
            'locr_28' => $request->locr_28,
            'locr_29' => $request->locr_29,
            'locr_30' => $request->locr_30,
            'locr_31' => $request->locr_31,
            'locr_32' => $request->locr_32,
            'locr_33' => $request->locr_33,
            'locr_34' => $request->locr_34,
            'locr_35' => $request->locr_35,
            'locr_36' => $request->locr_36,
            'locr_37' => $request->locr_37,
            'locr_38' => $request->locr_38,
            'locr_39' => $request->locr_39,
            'locr_40' => $request->locr_40,
            'locr_41' => $request->locr_41,
            'locr_42' => $request->locr_42,
            'locr_43' => $request->locr_43,
            'locr_44' => $request->locr_44,
            'sync' => 0,
        ];

        $user_approved = NULL;

		if($isApprovedRevision)
		{
			$update = $overheadCrane->create($data);
			$report_id = $update->id;
		}
		else
		{
			$update = $overheadCrane->update($data);
			$report_id = $overheadCrane->id;
			$user_approved = $overheadCrane->report->user_id_approved;
		}

        if($update)
        {
            $user = Auth::id();
            if($request->publish == 'yes')
            {
                $user = NULL;
                $last = InspectionReport::where('job_request_id', $request->lcr_1)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
            }

            $this->persistInspectionReportState($overheadCrane, [
                'code' => $code,
                'status' => 1,
                'publish' => $request->publish == 'yes' ? 1 : $overheadCrane->report->publish,
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
     * @param  \App\Models\OverheadCrane  $overheadCrane
     * @return \Illuminate\Http\Response
     */
    public function destroy(OverheadCrane $overheadCrane)
    {
        $remove  = $this->check_edited_reports_for_delete($overheadCrane);

        if ($remove)
        {
            $pdf = 'inspection/lifting/overheadcrane/'.$overheadCrane->job_request->code.'/'.$overheadCrane->code.'.pdf';
            $snap = 'images/inspection/lifting/overheadcrane/'.$overheadCrane->job_request->code.'/'.$overheadCrane->code;
            $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
            return response()->json([
                'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
            ]);
        }
    }
}
