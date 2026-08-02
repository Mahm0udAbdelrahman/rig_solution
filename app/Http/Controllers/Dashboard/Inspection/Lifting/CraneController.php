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
use App\Models\Inspection\Lifting\Crane;
use App\Models\Inspection\Lifting\Crane2;
use App\Models\Persons\Client;
use App\Models\User;

// App\Notifications
use App\Notifications\InspectionReports;

// Other
use DB, DataTables, Storage, Auth;

class CraneController extends Controller
{
	public $page_name = 'Lifting Crane Report';

	public function __construct()
	{
		$this->middleware('auth');
		$this->authorizeResource(Crane::class, 'crane');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$cranes = Crane::query();
		$this->applyPreferredInspectionFamilyRowConstraint($cranes, 'cranes', Crane::class);
		$cranes = $cranes->count();
		return view('layouts.inspection.lifting.crane.index', ['page_name' => $this->page_name('All', $this->page_name), 'cranes' => $cranes]);
	}

	public function getDataForDataTable(Request $request)
	{
        // select data from throughexaminations and join in clients and job requets tables.
		$data = Crane::join('job_requests', 'cranes.job_request_id', '=', 'job_requests.id')
			->join('inspection_reports', function ($join) {
				$join->on('cranes.id', '=', 'inspection_reports.reportable_id')
					->where('inspection_reports.reportable_type', '=', 'App\Models\Inspection\Lifting\Crane');
			})
			->join('clients', 'job_requests.client_id', '=', 'clients.id')
			->leftJoin('client_departments', 'job_requests.client_department_id', '=', 'client_departments.id')
			->select([
				'cranes.id as crane_id',
				'clients.id as client_id',
				'inspection_reports.id  as report_id',
				'inspection_reports.publish as publish',
				DB::raw("CONCAT(job_requests.code,'/',cranes.code) as report_code"),
                'job_requests.deploc as deploc',
                'client_departments.name as client_department',
				'cranes.lcr_12 as id_number',
				'cranes.lcr_10 as desc',
				DB::raw("clients.name as client"),
			]);
		$this->applyPreferredInspectionFamilyRowConstraint($data, 'cranes', Crane::class);

        $this->applyInspectionApprovalPresetFilter($data, request('smart_preset'));

		$res = Datatables::of($data)
			
            ->addColumn('approval_status', function ($row) {
                return $this->inspectionApprovalStatusValueByRow($row);
            })
            ->addColumn('publish_status', function ($row) {
                return $this->inspectionPublishStatusValueByRow($row);
            })
            ->addColumn('revision_count', function ($row) {
                return $this->inspectionRevisionCountValueByRow($row);
            })
->editColumn('report_code', function ($row) {
				$report_code = "";
				if (Auth::user()->can('view', Crane::find($row->crane_id))) {
					$report_code .= "<a href=" . route('crane.show', $row->crane_id) . ">";
				}

				$report_code .= $row->report_code;

				if (Auth::user()->can('view', Crane::find($row->crane_id))) {
					$report_code .= "</a>";
				}
				return $report_code;
			})
			->editColumn('client', function ($row) {
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
			->filterColumn('report_code', function ($query, $keyword) {
				$query->whereRaw("CONCAT(job_requests.code,'/',cranes.code) like ?", ["%{$keyword}%"]);
			})
			->filterColumn('id_number', function ($query, $keyword) {
				$query->whereRaw("cranes.lcr_12 like ?", ["%{$keyword}%"]);
			})
			->filterColumn('desc', function ($query, $keyword) {
				$query->whereRaw("cranes.lcr_10 like ?", ["%{$keyword}%"]);
			})
			->filterColumn('client', function ($query, $keyword) {
				$query->whereRaw("clients.name like ?", ["%{$keyword}%"]);
			})
            ->filterColumn('deploc', function ($query, $keyword) {
				$query->whereRaw("job_requests.deploc like ?", ["%{$keyword}%"]);
			})
            ->filterColumn('client_department', function ($query, $keyword) {
				$query->whereRaw("client_departments.name like ?", ["%{$keyword}%"]);
			})
            ->filterColumn('action', function ($query, $keyword) {
                $codes = $this->getUploadedCodes('/lifting/crane');
                switch ($keyword) {
                    case 'publish':
                        $query->whereRaw("inspection_reports.publish IS NULL");
                        break;
                    case 'download':
                        $query->whereRaw("CONCAT(job_requests.code,'/',cranes.code) IN ($codes)");
                        break;
                    case 'upload':
                        $query->whereRaw("inspection_reports.publish IS NOT NULL AND CONCAT(job_requests.code,'/',cranes.code) NOT IN ($codes)");
                        break;
                }
			})
			->addColumn('action', function ($row) {
				$btn = "";
				if (Auth::user()->can('create', Crane::class)) {
					$btn .= '<button data-id="' . $row->report_id . '" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
				}

				if ($row->publish != NULL) {
					if (Storage::disk('public')->exists('pdf/inspection/lifting/crane/' . $row->report_code . '.pdf')) {
						$btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . Storage::url('pdf/inspection/lifting/crane/' . $row->report_code . '.pdf') . '">Download PDF</a>';
					} else {
						$btn .= '<a class="btn btn-dark mr-1" href="' . route('crane.show', $row->crane_id) . '">Open Report</a>';
					}

					if (Auth::user()->can('update', Crane::find($row->crane_id))) {
						$btn .= '<div class="btn-group mr-1">
                              					<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="la la-pencil"></i></button>
                              					<div class="dropdown-menu">
                                  					<a href="' . route('crane.edit', $row->crane_id) . '" class="dropdown-item">Edit 1st Page</a>
                                  					<div class="dropdown-divider"></div>';
						if (Crane::find($row->crane_id)->crane2) {
							$btn .= '<a href="' . route('crane2_edit.crane', Crane::find($row->crane_id)->crane2->id) . '" class="dropdown-item">Edit 2nd Page</a>';
						} else {
							$btn .= '<a href="' . route('crane2_create.crane', $row->crane_id) . '" class="dropdown-item">Create 2nd Page</a>';
						}
						$btn .= '   </div>
						                        </div>';
					}
				} else {
					$btn .= $this->buildInspectionUnpublishedMultiPageActionButtons(
						Crane::class,
						(int) ($row->crane_id),
						'crane.show',
						'publish.crane',
						'crane.edit',
						'crane2',
						'crane2_create.crane',
						'crane2_edit.crane'
					);
				}


				if (Auth::user()->can('delete', Crane::find($row->crane_id))) {
					$btn .= '<button type="button" data-id="' . $row->crane_id . '" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
				}
				return $btn;
			})
			
            ->filter(function ($query) {
                $this->applyInspectionGlobalSearch($query, request('search.value'));
            })
			->rawColumns(['report_code', 'client', 'action'])
			
            ->filter(function ($query) {
                $this->applyInspectionGlobalSearch($query, request('search.value'));
            })
			->make(true);
    		return $res;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$jobrequests = DB::table('job_requests')->select('id', 'code', 'purchase_order')->orderBy('id', 'desc')->get();
		return view('layouts.inspection.lifting.crane.add', [
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

		$store = Crane::create([
			'job_request_id' => $request->lcr_1,
			'lcr_2' => $request->lcr_2,
			'code' => $code,
			'lcr_6' => $request->lcr_6,
			'lcr_7' => $request->lcr_7,
			'lcr_8' => $request->lcr_8,
			'lcr_10' => $request->lcr_10,
			'lcr_11' => $request->lcr_11,
			'lcr_12' => $request->lcr_12,
			'lcr_13' => $request->lcr_13,
			'lcr_14' => $request->lcr_14,
			'lcr_15' => $request->lcr_15,
			'lcr_16' => $request->lcr_16,
			'lcr_17' => $request->lcr_17,
			'lcr_18' => $request->lcr_18,
			'lcr_19' => $request->lcr_19,
			'lcr_20' => $request->lcr_20,
			'lcr_21' => $request->lcr_21,
			'lcr_22' => $request->lcr_22,
			'lcr_23' => $request->lcr_23,
			'lcr_24' => $request->lcr_24,
			'lcr_25' => $request->lcr_25,
			'lcr_26' => $request->lcr_26,
			'lcr_27' => $request->lcr_27,
			'lcr_28' => $request->lcr_28,
			'lcr_29' => $request->lcr_29,
			'lcr_30' => $request->lcr_30,
			'lcr_31' => $request->lcr_31,
			'lcr_32' => $request->lcr_32,
			'lcr_33' => $request->lcr_33,
			'lcr_34' => $request->lcr_34,
			'lcr_35' => $request->lcr_35,
			'lcr_36' => $request->lcr_36,
			'lcr_37' => $request->lcr_37,
			'lcr_38' => json_encode($request['payments']),
			'lcr_42' => $request->lcr_42,
			'lcr_43' => $request->lcr_43,
			'lcr_44' => $request->lcr_44,
			'lcr_45' => $request->lcr_45,
			'lcr_46' => $request->lcr_46,
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
	 * @param  \App\Models\Crane  $crane
	 * @return \Illuminate\Http\Response
	 */
	public function show(Crane $crane)
	{
		$have_edit = $this->check_if_report_edit(Crane::class, $crane);
		$crane->report = $have_edit->default;
		$model = $crane;
		$revisionDisplay = $this->resolveInspectionRevisionDisplayMeta(Crane::class, $crane);

		return view('layouts.inspection.lifting.crane.show', [
			'page_name' => 'Through Examination And / Or Test Certificate Of Cranes',
			'page_text' => 'This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998?',
			'crane' => $crane,
			'code' => $crane->job_request->code . '/' . $crane->code,
			'crane2' => $crane->crane2,
			'model' => $crane,
			'report_created_at' => $crane->created_at->format('d/m/Y'),
			'pdfurl' => 'storage/pdf/inspection/lifting/crane/' . $crane->job_request->code . '/' . $crane->code . '.pdf',
			'imageurl' => $crane->job_request->code . '/' . $crane->code,
			'folder' => 'pdf/inspection/lifting/crane',
			'hasPermission' => Auth::user()->hasPermission('crane', 'approve'),
			'iso_number' => 'Form # RSE-RF-01 - ISSUE 05 / Jan 2022',
			'page_number' =>  $crane->crane2 ? '1 of 2': '1 of 1',
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

	public function publish(Crane $crane)
	{
		$this->authorize('create', Crane::class);
		$jobrequests = DB::table('job_requests')->select('id', 'code', 'purchase_order')->orderBy('id', 'desc')->get();
		return view('layouts.inspection.lifting.crane.publish', [
			'page_name' => $this->page_name(0, $this->page_name) . ' (Publish)',
			'crane' => $crane,
			'jobrequests' => $jobrequests
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Crane  $crane
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Crane $crane)
	{
		return view('layouts.inspection.lifting.crane.edit', [
			'page_name' => $this->page_name(1, $this->page_name),
			'crane' => $crane,
			// 'jobrequests' => $jobrequests
		]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Crane  $crane
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Crane $crane)
	{
		$isApprovedRevision = $this->shouldForkApprovedInspectionRevision($crane);
		$code = $this->resolveInspectionSubmittedCode($request, $crane);

		$data = [
			'job_request_id' => $request->lcr_1,
			'lcr_2' => $request->lcr_2,
			'code' => $code,
			'lcr_6' => $request->lcr_6,
			'lcr_7' => $request->lcr_7,
			'lcr_8' => $request->lcr_8,
			'lcr_10' => $request->lcr_10,
			'lcr_11' => $request->lcr_11,
			'lcr_12' => $request->lcr_12,
			'lcr_13' => $request->lcr_13,
			'lcr_14' => $request->lcr_14,
			'lcr_15' => $request->lcr_15,
			'lcr_16' => $request->lcr_16,
			'lcr_17' => $request->lcr_17,
			'lcr_18' => $request->lcr_18,
			'lcr_19' => $request->lcr_19,
			'lcr_20' => $request->lcr_20,
			'lcr_21' => $request->lcr_21,
			'lcr_22' => $request->lcr_22,
			'lcr_23' => $request->lcr_23,
			'lcr_24' => $request->lcr_24,
			'lcr_25' => $request->lcr_25,
			'lcr_26' => $request->lcr_26,
			'lcr_27' => $request->lcr_27,
			'lcr_28' => $request->lcr_28,
			'lcr_29' => $request->lcr_29,
			'lcr_30' => $request->lcr_30,
			'lcr_31' => $request->lcr_31,
			'lcr_32' => $request->lcr_32,
			'lcr_33' => $request->lcr_33,
			'lcr_34' => $request->lcr_34,
			'lcr_35' => $request->lcr_35,
			'lcr_36' => $request->lcr_36,
			'lcr_37' => $request->lcr_37,
			'lcr_38' => json_encode($request['payments']),
			'lcr_42' => $request->lcr_42,
			'lcr_43' => $request->lcr_43,
			'lcr_44' => $request->lcr_44,
			'lcr_45' => $request->lcr_45,
			'lcr_46' => $request->lcr_46,
			'sync' => 0,
		];

		$user_approved = NULL;

		if ($isApprovedRevision) {
			$update = $crane->create($data);
			/************Attach CRAN2 if exist *********/
            $crane2 = Crane2::where('crane_id', $crane->id)->first();
            if ($crane2) {
                $newCrane2 = $crane2->replicate();
                $newCrane2->crane_id = $update->id;
                $newCrane2->save();
            }
			/************ *****************************/
			$report_id = $update->id;
		} else {
			$update = $crane->update($data);
			$report_id = $crane->id;
			$user_approved = $crane->report->user_id_approved;
		}


		if ($update) {
			$user = Auth::id();
			if ($request->publish == 'yes') {
				$user = NULL;
				$last = InspectionReport::where('job_request_id', $request->lcr_1)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
			}

			$this->persistInspectionReportState($crane, [
				'code' => $code,
				'status' => 1,
				'publish' => $request->publish == 'yes' ? 1 : $crane->report->publish,
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
	 * @param  \App\Models\Crane  $crane
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Crane $crane)
	{
		$remove = $this->check_edited_reports_for_delete($crane);

		if ($remove) {
			$pdf = 'inspection/lifting/crane/' . $crane->job_request->code . '/' . $crane->code . '.pdf';
			$snap = 'images/inspection/lifting/crane/' . $crane->job_request->code . '/' . $crane->code;
			$remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
			return response()->json([
				'success' => $remove_snap_shots_pdf . $this->action_message(2, $this->page_name)
			]);
		}
	}
}
