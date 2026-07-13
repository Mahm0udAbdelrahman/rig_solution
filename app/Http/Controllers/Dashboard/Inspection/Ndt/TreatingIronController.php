<?php

namespace App\Http\Controllers\Dashboard\Inspection\Ndt;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\GeneralInfo\Specification;
// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Ndt\TreatingIron;
use App\Models\Persons\Client;
use App\Models\User;

// Other
use DB, DataTables, Storage, Auth, Crypt;

class TreatingIronController extends Controller
{

	public $page_name = 'Treating Iron Inspection Report';

	public function __construct()
	{
		$this->middleware('auth');
		$this->authorizeResource(TreatingIron::class, 'treatingIron');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$treating_irons = TreatingIron::count();
		return view('layouts.inspection.ndt.treatingiron.index', ['page_name' => $this->page_name('All', $this->page_name), 'treating_irons' => $treating_irons]);
	}

	public function getDataForDataTable()
	{
		// $data = TreatingIron::query()->has('report')->orderBy('id', 'Desc');
		$data = TreatingIron::join('job_requests', 'treating_irons.job_request_id', '=', 'job_requests.id')
            ->join('inspection_reports', function ($join) {
                  $join->on('treating_irons.id', '=', 'inspection_reports.reportable_id')
                        ->where('inspection_reports.reportable_type', '=', 'App\Models\Inspection\Ndt\TreatingIron');
              })
            ->join('clients', 'job_requests.client_id', '=', 'clients.id')
            ->leftJoin('client_departments', 'job_requests.client_department_id', '=', 'client_departments.id')
            ->select([
                'treating_irons.id as treating_iron_id',
                'treating_irons.desc',
                // 'treating_irons.acceptance',
                'treating_irons.ntir_13',
                'clients.id as client_id',
                'inspection_reports.id  as report_id',
                'inspection_reports.publish as publish',
                DB::raw("CONCAT(job_requests.code,'/',treating_irons.code) as report_code"),
                // 'through_examinations.lter_10',
                'job_requests.deploc as deploc',
                'client_departments.name as client_department',
                DB::raw("clients.name as client"),
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
				if (Auth::user()->can('view', TreatingIron::find($row->treating_iron_id))) {
					$report_code .= "<a href=" . route('treatingIron.show', $row->treating_iron_id) . ">";
				}

				$report_code .= $row->report_code;

				if (Auth::user()->can('view', TreatingIron::find($row->treating_iron_id))) {
					$report_code .= "</a>";
				}
				return $report_code;
			})
			->addColumn('deploc', function ($row) {

				return $row->deploc;
			})
			->addColumn('action', function ($row) {
				$btn = "";
				if (Auth::user()->can('create', TreatingIron::class)) {
					$btn .= '<button data-id="' . $row->report_id . '" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
				}

				if ($row->publish != NULL) {
					if (Storage::disk('public')->exists('pdf/inspection/ndt/treatingiron/' . $row->report_code . '.pdf')) {
						$btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . Storage::url('pdf/inspection/ndt/treatingiron/' . $row->report_code . '.pdf') . '">Download PDF</a>';
					} else {
						$btn .= '<a class="btn btn-dark mr-1" href="' . route('treatingIron.show', $row->treating_iron_id) . '">Open Report</a>';
					}

					if (Auth::user()->can('update', TreatingIron::find($row->treating_iron_id))) {
						$btn .= '<a href="' . route('treatingIron.edit', $row->treating_iron_id) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
					}
				} else {
					$btn .= $this->buildInspectionUnpublishedActionButtons($row, TreatingIron::class, (int) ($row->treating_iron_id), 'treatingIron.show', 'publish.treatingIron', 'treatingIron.edit');
				}


				if (Auth::user()->can('delete', TreatingIron::find($row->treating_iron_id))) {
					$btn .= '<button type="button" data-id="' . $row->treating_iron_id . '" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
				}
				return $btn;
			})
			->filterColumn('acceptance', function ($query, $keyword) {
				$query->whereRaw("acceptance like ?", ["%{$keyword}%"]);
			})
			->filterColumn('code', function ($query, $keyword) {
				$query->whereRaw("CONCAT(job_requests.code,'/',treating_irons.code) like ?", ["%{$keyword}%"]);
			})
			->filterColumn('deploc', function ($query, $keyword) {
				$query->whereRaw("deploc like ?", ["%{$keyword}%"]);
			})
            ->filterColumn('client_department', function ($query, $keyword) {
                $query->whereRaw("client_departments.name like ?", ["%{$keyword}%"]);
            })
			->filterColumn('client', function ($query, $keyword) {
				$query->whereRaw("clients.name like ?", ["%{$keyword}%"]);
			})
            ->filterColumn('action', function ($query, $keyword) {
                $codes = $this->getUploadedCodes('/ndt/treatingiron');
                switch ($keyword) {
                    case 'publish':
                        $query->whereRaw("inspection_reports.publish IS NULL");
                        break;
                    case 'download':
                        $query->whereRaw("CONCAT(job_requests.code,'/',treating_irons.code) IN ($codes)");
                        break;
                    case 'upload':
                        $query->whereRaw("inspection_reports.publish IS NOT NULL AND CONCAT(job_requests.code,'/',treating_irons.code) NOT IN ($codes)");
                        break;
                }
            })
            
            ->filter(function ($query) {
                $this->applyInspectionGlobalSearch($query, request('search.value'));
            })
            ->rawColumns(['code', 'client', 'action'])
			
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
		$specifications = Specification::getNdtSpecifications();
		$jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();
		return view('layouts.inspection.ndt.treatingiron.add', [
			'page_name' => $this->page_name(0, $this->page_name),
			'specifications' => $specifications,
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

		$path1 = $request->file('ntir_30');
		$path_crypt1 = NULL;
		if ($path1 != NULL) {
			$path_crypt1 = Crypt::encryptString($path1->getClientOriginalName());
			$store1 = $path1->storeAs(
				'public/camera/inspection/ndt/treatingiron',
				$path_crypt1
			);
		}

		$path = $request->file('ntir_44');
		$path_crypt = NULL;
		if ($path != NULL) {
			$path_crypt = Crypt::encryptString($path->getClientOriginalName());
			$store = $path->storeAs(
				'public/camera/inspection/ndt/treatingiron',
				$path_crypt
			);
		}

		$store = treatingiron::create([
			'job_request_id' => $request->lcr_1,
			'ntir_2' => $request->ntir_2,
			'code' => $code,
			'ntir_6' => $request->lcr_60,
			'ntir_7' => $request->lcr_7,
			'ntir_9' => $request->contactway,
			'ntir_10' => $request->ntir_10,
			'ntir_11' => $request->ntir_11,
			'desc' => $request->ntir_12,
			'ntir_13' => $request->ntir_13,
			'ntir_14' => $request->ntir_14,
			'ntir_15' => $request->ntir_15,
			'ntir_16' => $request->ntir_16,
			'ntir_17' => $request->ntir_17,
			'ntir_18' => $request->ntir_18,
			'ntir_19' => $request->ntir_19,
			'ntir_20' => $request->ntir_20,
			'ntir_21' => $request->ntir_21,
			'ntir_22' => $request->ntir_22,
			'ntir_23' => $request->ntir_23,
			'ntir_24' => $request->ntir_24,
			'ntir_25' => $request->ntir_25,
			'ntir_26' => $request->ntir_26,
			'ntir_27' => $request->ntir_27,
			'ntir_28' => $request->ntir_28,
			'ntir_29' => $request->nmpr_12,
			'ntir_30' => $path_crypt1,
			'ntir_31' => $request->ntir_31,
			'ntir_32' => $request->ntir_32,
			'ntir_33' => $request->ntir_33,
			'ntir_34' => $request->ntir_34,
			'ntir_35' => $request->ntir_35,
			'ntir_36' => $request->ntir_36,
			'ntir_37' => $request->ntir_37,
			'ntir_38' => $request->ntir_38,
			'ntir_39' => $request->ntir_39,
			'ntir_40' => $request->ntir_40,
			'ntir_41' => $request->ntir_41,
			'ntir_42' => $request->ntir_42,
			'ntir_43' => json_encode($request['payments']),
			'ntir_44' => $path_crypt,
			'ntir_45' => $request->ntir_46,
			'ntir_46' => $request->ntir_47,
			'ntir_47' => $request->ntir_48,
			'ntir_48' => $request->ntir_49,
			'ntir_49' => $request->ntir_50,
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

		return response()->json([
			'message' => 'Unable to create Treating Iron report.'
		], 500);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\TreatingIron  $treatingIron
	 * @return \Illuminate\Http\Response
	 */
	public function show(TreatingIron $treatingIron)
	{
		$have_edit = $this->check_if_report_edit(TreatingIron::class, $treatingIron);
		$treatingIron->report = $have_edit->default;
		$specificationsList = Specification::getNdtSpecifications()->pluck('name', 'code');
		$treatingIron->specifications = json_decode($treatingIron->ntir_10) ? json_decode($treatingIron->ntir_10) : [];
		
		return view('layouts.inspection.ndt.treatingiron.show', [
			'page_name' => $this->page_name,
			'page_text' => '',
			'treatingIron' => $treatingIron,
			'model' => $treatingIron,
			'code' => $treatingIron->job_request->code . '/' . $treatingIron->code,
			'specificationOptions' => $specificationsList,
			'report_created_at' => $treatingIron->created_at->format('d/m/Y'),
			'pdfurl' => 'storage/pdf/inspection/ndt/treatingiron/' . $treatingIron->job_request->code . '/' . $treatingIron->code . '.pdf',
			'hasPermission' => Auth::user()->hasPermission('treatingiron', 'approve'),
			'imageurl' => $treatingIron->job_request->code . '/' . $treatingIron->code,
			'folder' => 'pdf/inspection/ndt/treatingiron',
			'iso_number' => 'Form # RSE-RF-15 - ISSUE 03 / Jan 2022',
			'page_number' => '1 of 1',
			'have_edit' => $have_edit->edited,
			'user_id_approved' => data_get($treatingIron, 'report.user_id_approved'),
			'for_approve_url' => data_get($treatingIron, 'report.id'),
			'esign' => data_get($treatingIron, 'report.user.employee.esign'),
			'person_make_report' => data_get($treatingIron, 'report.user.employee.name'),
			'person_make_report_desc' => data_get($treatingIron, 'report.user.employee.desc'),
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\TreatingIron  $treatingIron
	 * @return \Illuminate\Http\Response
	 */
	public function edit(TreatingIron $treatingIron)
	{
		$specifications = Specification::getNdtSpecifications();
		$jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();
		return view('layouts.inspection.ndt.treatingiron.edit', [
			'page_name' => $this->page_name(1, $this->page_name),
			'treatingIron' => $treatingIron,
			'jobrequests' => $jobrequests,
			'specifications' => $specifications
		]);
	}

	public function publish(TreatingIron $treatingIron)
	{
		$this->authorize('create', TreatingIron::class);
		$specifications = Specification::getNdtSpecifications();
		$jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();
		return view('layouts.inspection.ndt.treatingiron.publish', [
			'page_name' => $this->page_name(0, $this->page_name) . ' (Publish)',
			'treatingIron' => $treatingIron,
			'jobrequests' => $jobrequests,
			'specifications' => $specifications
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\TreatingIron  $treatingIron
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, TreatingIron $treatingIron)
	{
		$currentReport = $treatingIron->report;

		$code = $this->resolveInspectionSubmittedCode($request, $treatingIron);


		$path1 = $request->file('ntir_30');
		$path_crypt1 = NULL;
		if ($path1 != NULL) {
			$path_crypt1 = Crypt::encryptString($path1->getClientOriginalName());
			$store = $path1->storeAs(
				'public/camera/inspection/ndt/treatingiron',
				$path_crypt1
			);
		} else {
			if ($request->imagedata1 == '' && $request->publish != 'yes') {
				Storage::disk('public')->delete('camera/inspection/ndt/treatingiron/' . $treatingIron->ntir_30);
			}
			$path_crypt1 = $request->imagedata1;
		}

		$path = $request->file('ntir_44');
		$path_crypt = NULL;
		if ($path != NULL) {
			$path_crypt = Crypt::encryptString($path->getClientOriginalName());
			$store = $path->storeAs(
				'public/camera/inspection/ndt/treatingiron',
				$path_crypt
			);
		} else {
			if ($request->imagedata == '' && $request->publish != 'yes') {
				Storage::disk('public')->delete('camera/inspection/ndt/treatingiron/' . $treatingIron->ntir_44);
			}
			$path_crypt = $request->imagedata;
		}

		$data = [
			'job_request_id' => $request->lcr_1,
			'ntir_2' => $request->ntir_2,
			'code' => $code,
			'ntir_6' => $request->lcr_60,
			'ntir_7' => $request->lcr_7,
			'ntir_9' => $request->contactway,
			'ntir_10' => $request->ntir_10,
			'ntir_11' => $request->ntir_11,
			'desc' => $request->ntir_12,
			'ntir_13' => $request->ntir_13,
			'ntir_14' => $request->ntir_14,
			'ntir_15' => $request->ntir_15,
			'ntir_16' => $request->ntir_16,
			'ntir_17' => $request->ntir_17,
			'ntir_18' => $request->ntir_18,
			'ntir_19' => $request->ntir_19,
			'ntir_20' => $request->ntir_20,
			'ntir_21' => $request->ntir_21,
			'ntir_22' => $request->ntir_22,
			'ntir_23' => $request->ntir_23,
			'ntir_24' => $request->ntir_24,
			'ntir_25' => $request->ntir_25,
			'ntir_26' => $request->ntir_26,
			'ntir_27' => $request->ntir_27,
			'ntir_28' => $request->ntir_28,
			'ntir_29' => $request->nmpr_12,
			'ntir_30' => $path_crypt1,
			'ntir_31' => $request->ntir_31,
			'ntir_32' => $request->ntir_32,
			'ntir_33' => $request->ntir_33,
			'ntir_34' => $request->ntir_34,
			'ntir_35' => $request->ntir_35,
			'ntir_36' => $request->ntir_36,
			'ntir_37' => $request->ntir_37,
			'ntir_38' => $request->ntir_38,
			'ntir_39' => $request->ntir_39,
			'ntir_40' => $request->ntir_40,
			'ntir_41' => $request->ntir_41,
			'ntir_42' => $request->ntir_42,
			'ntir_43' => json_encode($request['payments']),
			'ntir_44' => $path_crypt,
			'ntir_45' => $request->ntir_46,
			'ntir_46' => $request->ntir_47,
			'ntir_47' => $request->ntir_48,
			'ntir_48' => $request->ntir_49,
			'ntir_49' => $request->ntir_50,
			'sync' => 0,
		];

		$user_approved = null;

		if ($this->shouldForkApprovedInspectionRevision($treatingIron)) {
			$update = $treatingIron->create($data);
			$report_id = $update->id;
		} else {
			$update = $treatingIron->update($data);
			$report_id = $treatingIron->id;
			$user_approved = $currentReport->user_id_approved;
		}

		if ($update) {
			$user = Auth::id();
			if ($request->publish == 'yes') {
				$user = NULL;
			}

			$reportPersisted = $this->persistInspectionReportState($treatingIron, [
				'code' => $code,
				'status' => 1,
				'publish' => $request->publish == 'yes' ? 1 : $currentReport->publish,
				'user_id_approved' => $user_approved,
				'reportable_id' => $report_id,
				'user_id_edit' => $user,
			]);

			if (!$reportPersisted) {
				return response()->json([
					'message' => 'Unable to update inspection report state.'
				], 500);
			}

			return response()->json([
				'success' => $this->action_message(1, $this->page_name)
			]);
		}

		return response()->json([
			'message' => 'Unable to update Treating Iron report.'
		], 500);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\TreatingIron  $treatingIron
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(TreatingIron $treatingIron)
	{
		$remove = $this->check_edited_reports_for_delete($treatingIron);

		if ($remove) {
			$pdf = 'inspection/ndt/treatingiron/' . $treatingIron->job_request->code . '/' . $treatingIron->code . '.pdf';
			$snap = 'images/inspection/ndt/treatingiron/' . $treatingIron->job_request->code . '/' . $treatingIron->code;
			$remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
			return response()->json([
				'success' => $remove_snap_shots_pdf . $this->action_message(2, $this->page_name)
			]);
		}
	}
}
