<?php

namespace App\Http\Controllers\Dashboard\Inspection\Ndt;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\GeneralInfo\Specification;
// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Inspection\Ndt\WitnessHydro;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Models\Persons\Client;
use App\Models\User;

// Other
use DB, DataTables, Storage, Auth, Crypt;

class WitnessHydroController extends Controller
{
		public $page_name = 'Witness Hydro Test Report';

		public function __construct()
		{
				$this->middleware('auth');
				$this->authorizeResource(WitnessHydro::class, 'witnessHydro');
		}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	      $witness_hydross = WitnessHydro::count();
	      return view('layouts.inspection.ndt.witnesshydro.index', ['page_name' => $this->page_name('All', $this->page_name), 'witness_hydross' => $witness_hydross]);
    }

		public function getDataForDataTable(Request $request)
		{
				// select data from throughexaminations and join in clients and job requets tables.
				$data = WitnessHydro::join('job_requests', 'witness_hydros.job_request_id', '=', 'job_requests.id')
								->join('inspection_reports', function ($join) {
											$join->on('witness_hydros.id', '=', 'inspection_reports.reportable_id')
													 ->where('inspection_reports.reportable_type', '=', 'App\Models\Inspection\Ndt\WitnessHydro');
									})
								->join('clients', 'job_requests.client_id', '=', 'clients.id')
                                ->leftJoin('client_departments', 'job_requests.client_department_id', '=', 'client_departments.id')
                                ->select([
										'witness_hydros.nwhr_15',
										'witness_hydros.id as witness_hydro_id',
										'witness_hydros.acceptance as acceptance',
										'witness_hydros.desc as desc',
										'clients.id as client_id',
										'inspection_reports.id  as report_id',
										'inspection_reports.publish as publish',
										DB::raw("CONCAT(job_requests.code,'/',witness_hydros.code) as report_code"),
										'job_requests.deploc as deploc',
                                        'client_departments.name as client_department',
										DB::raw("clients.name as client"),
								]);

				$this->applyInspectionApprovalPresetFilter($data, request('smart_preset'));

				return  Datatables::of($data)
								
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
											if(Auth::user()->can('view', WitnessHydro::find($row->witness_hydro_id)))
											{
													$report_code .= "<a href=".route('witnessHydro.show', $row->witness_hydro_id).">";
											}

											$report_code .= $row->report_code;

											if(Auth::user()->can('view', WitnessHydro::find($row->witness_hydro_id)))
											{
													$report_code .= "</a>";
											}
											return $report_code;
									})
								->editColumn('client', function($row){
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
							 	->filterColumn('report_code', function($query, $keyword){
									 	$query->whereRaw("CONCAT(job_requests.code,'/',witness_hydros.code) like ?", ["%{$keyword}%"]);
							 		})
							 	->filterColumn('deploc', function($query, $keyword){
									 	$query->whereRaw("job_requests.deploc like ?", ["%{$keyword}%"]);
							 		})
                                ->filterColumn('client_department', function ($query, $keyword) {
                                    $query->whereRaw("client_departments.name like ?", ["%{$keyword}%"]);
                                })
							 	->filterColumn('acceptance', function($query, $keyword){
									 	$query->whereRaw("witness_hydros.acceptance like ?", ["%{$keyword}%"]);
							 		})
								->filterColumn('desc', function($query, $keyword){
									 	$query->whereRaw("witness_hydros.desc like ?", ["%{$keyword}%"]);
							 		})
							 	->filterColumn('client', function($query, $keyword){
									 	$query->whereRaw("clients.name like ?", ["%{$keyword}%"]);
							 		})
							->addColumn('action', function ($row) {
										$btn = "";
										if (Auth::user()->can('create', WitnessHydro::class))
										{
												$btn .= '<button data-id="'.$row->report_id.'" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
										}

										if ($row->publish != NULL)
										{
												if (Storage::disk('public')->exists('pdf/inspection/ndt/witnesshydro/'.$row->report_code.'.pdf'))
												{
														$btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="'.Storage::url('pdf/inspection/ndt/witnesshydro/'.$row->report_code.'.pdf').'">Download PDF</a>';
												}
												else
												{
														$btn .= '<a class="btn btn-dark mr-1" href="'.route('witnessHydro.show', $row->witness_hydro_id).'">Open Report</a>';
												}

												if (Auth::user()->can('update', WitnessHydro::find($row->witness_hydro_id)))
												{

														$btn .= '<a href="'.route('witnessHydro.edit', $row->witness_hydro_id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
												}
										}
										else
										{
												$btn .= $this->buildInspectionUnpublishedActionButtons($row, WitnessHydro::class, (int) ($row->witness_hydro_id), 'witnessHydro.show', 'publish.witnessHydro', 'witnessHydro.edit');
										}


										if (Auth::user()->can('delete', WitnessHydro::find($row->witness_hydro_id)))
										{
												$btn .= '<button type="button" data-id="'.$row->witness_hydro_id.'" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
										}
										return $btn;
					    	})
                    ->filterColumn('action', function ($query, $keyword) {
                        $codes = $this->getUploadedCodes('/ndt/witnesshydro');
                        switch ($keyword) {
                            case 'publish':
                                $query->whereRaw("inspection_reports.publish IS NULL");
                                break;
                            case 'download':
                                $query->whereRaw("CONCAT(job_requests.code,'/',witness_hydros.code) IN ($codes)");
                                break;
                            case 'upload':
                                $query->whereRaw("inspection_reports.publish IS NOT NULL AND CONCAT(job_requests.code,'/',witness_hydros.code) NOT IN ($codes)");
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
						 	->make(true);
		}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	      $specifications = Specification::getNdtSpecifications();
	      $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
	      return view('layouts.inspection.ndt.witnesshydro.add', [
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
				$path = $request->file('nwhr_36');
				$path_crypt = NULL;
				if ($path != NULL)
				{
						$path_crypt = Crypt::encryptString($path->getClientOriginalName());
						$store = $path->storeAs(
								'public/camera/inspection/ndt/witnesshydro',
								$path_crypt
						);
				}

				$job_request = JobRequest::find($request->lcr_1);
				$code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;
        if (!in_array('other', json_decode($request->nwhr_9))) {
            $request->nwhr_10 = '';
        }
        $store = WitnessHydro::create([
	          'job_request_id' => $request->lcr_1,
	          'nwhr_2' => $request->nwhr_2,
	          'code' => $code,
	          'nwhr_6' => $request->lcr_6,
	          'nwhr_7' => $request->lcr_7,
						'nwhr_8' => $request->contactway,
	          'nwhr_9' => $request->nwhr_9,
	          'nwhr_10' => $request->nwhr_10,
	          // 'nwhr_11' => $request->nwhr_11,
	          // 'nwhr_12' => $request->nwhr_12,
	          'acceptance' => $request->nwhr_13,
	          'desc' => $request->nwhr_14,
	          'nwhr_15' => $request->nwhr_15,
	          'nwhr_16' => $request->nwhr_16,
	          'nwhr_17' => $request->nwhr_17,
	          'nwhr_18' => $request->nwhr_18,
	          'nwhr_19' => $request->nwhr_19,
	          'nwhr_20' => $request->nwhr_20,
	          'nwhr_21' => $request->nwhr_21,
	          'nwhr_22' => $request->nwhr_22,
	          'nwhr_23' => $request->nwhr_23,
	          'nwhr_24' => $request->nwhr_24,
	          'nwhr_25' => $request->nwhr_25,
	          'nwhr_26' => $request->nwhr_26,
	          'nwhr_27' => $request->nwhr_27,
	          'nwhr_28' => $request->nwhr_28,
	          'nwhr_29' => $request->nwhr_29,
	          'nwhr_30' => $request->nwhr_30,
	          'nwhr_31' => $request->nwhr_31,
	          'nwhr_32' => $request->nwhr_32,
	          'nwhr_33' => $request->nwhr_33,
	          'nwhr_34' => $request->nwhr_34,
	          'nwhr_35' => $request->nwhr_35,
	          'nwhr_36' => $path_crypt,
	          'nwhr_37' => $request->nwhr_37,
	          'nwhr_38' => $request->nwhr_38,
						'nwhr_39' => $request->nwhr_39,
        ]);

        if ($store)
				{
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
     * @param  \App\Models\WitnessHydro  $witnessHydro
     * @return \Illuminate\Http\Response
     */
    public function show(WitnessHydro $witnessHydro)
    {
		$have_edit = $this->check_if_report_edit(WitnessHydro::class, $witnessHydro);
		$witnessHydro->report = $have_edit->default;
		$specificationsList = Specification::getNdtSpecifications()->pluck('name', 'code');
		$witnessHydro->specifications = json_decode($witnessHydro->nwhr_9) ? json_decode($witnessHydro->nwhr_9) : [];
		return view('layouts.inspection.ndt.witnesshydro.show', [
			'page_name' => $this->page_name,
			'page_text'=>'',
			'witnessHydro' => $witnessHydro,
			'model' => $witnessHydro,
			'code' => $witnessHydro->job_request->code.'/'.$witnessHydro->code,
			'specificationOptions' => $specificationsList,
			'report_created_at' => $witnessHydro->created_at->format('d/m/Y'),
			'pdfurl' => 'storage/pdf/inspection/ndt/witnesshydro/'.$witnessHydro->job_request->code.'/'.$witnessHydro->code.'.pdf',
			'imageurl' => $witnessHydro->job_request->code.'/'.$witnessHydro->code,
			'folder' => 'pdf/inspection/ndt/witnesshydro',
			'hasPermission' => Auth::user()->hasPermission('witnesshydro', 'approve'),
			'iso_number' => 'Form # RSE-RF-14 - ISSUE 03 / Jan 2022',
			'page_number' => '1 of 1',
            'have_edit' => $have_edit->edited,
            'user_id_approved' => data_get($witnessHydro, 'report.user_id_approved'),
            'for_approve_url' => data_get($witnessHydro, 'report.id'),
            'esign' => data_get($witnessHydro, 'report.user.employee.esign'),
            'person_make_report' => data_get($witnessHydro, 'report.user.employee.name'),
            'person_make_report_desc' => data_get($witnessHydro, 'report.user.employee.desc'),
        ]);
    }

		public function publish(WitnessHydro $witnessHydro)
    {
        $this->authorize('create', WitnessHydro::class);
				$specifications = Specification::getNdtSpecifications();
				$jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.witnesshydro.publish', [
						'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
						'witnessHydro' => $witnessHydro,
						'jobrequests' => $jobrequests,
						'specifications' => $specifications
				]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WitnessHydro  $witnessHydro
     * @return \Illuminate\Http\Response
     */
    public function edit(WitnessHydro $witnessHydro)
    {
				$specifications = Specification::getNdtSpecifications();
				$jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
				return view('layouts.inspection.ndt.witnesshydro.edit', [
						'page_name' => $this->page_name(1, $this->page_name),
						'witnessHydro' => $witnessHydro,
						'jobrequests' => $jobrequests,
						'specifications' => $specifications
				]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WitnessHydro  $witnessHydro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WitnessHydro $witnessHydro)
    {
				$code = $this->resolveInspectionSubmittedCode($request, $witnessHydro);

				$path = $request->file('nwhr_36');
				$path_crypt = NULL;
				if ($path != NULL)
				{
						$path_crypt = Crypt::encryptString($path->getClientOriginalName());
						$store = $path->storeAs(
								'public/camera/inspection/ndt/witnesshydro',
								$path_crypt
						);
				}
				else
				{
						if($request->imagedata == '' && $request->publish != 'yes'){
								Storage::disk('public')->delete('camera/inspection/ndt/witnesshydro/'.$witnessHydro->nwhr_36);
						}
						$path_crypt = $request->imagedata;
				}

				$data = [
						'job_request_id' => $request->lcr_1,
						'nwhr_2' => $request->nwhr_2,
						'code' => $code,
						'nwhr_6' => $request->lcr_6,
						'nwhr_7' => $request->lcr_7,
						'nwhr_8' => $request->contactway,
						'nwhr_9' => $request->nwhr_9,
						'nwhr_10' => $request->nwhr_10,
						// 'nwhr_11' => $request->nwhr_11,
						// 'nwhr_12' => $request->nwhr_12,
						'acceptance' => $request->nwhr_13,
						'desc' => $request->nwhr_14,
						'nwhr_15' => $request->nwhr_15,
						'nwhr_16' => $request->nwhr_16,
						'nwhr_17' => $request->nwhr_17,
						'nwhr_18' => $request->nwhr_18,
						'nwhr_19' => $request->nwhr_19,
						'nwhr_20' => $request->nwhr_20,
						'nwhr_21' => $request->nwhr_21,
						'nwhr_22' => $request->nwhr_22,
						'nwhr_23' => $request->nwhr_23,
						'nwhr_24' => $request->nwhr_24,
						'nwhr_25' => $request->nwhr_25,
						'nwhr_26' => $request->nwhr_26,
						'nwhr_27' => $request->nwhr_27,
						'nwhr_28' => $request->nwhr_28,
						'nwhr_29' => $request->nwhr_29,
						'nwhr_30' => $request->nwhr_30,
						'nwhr_31' => $request->nwhr_31,
						'nwhr_32' => $request->nwhr_32,
						'nwhr_33' => $request->nwhr_33,
						'nwhr_34' => $request->nwhr_34,
						'nwhr_35' => $request->nwhr_35,
						'nwhr_36' => $path_crypt,
						'nwhr_37' => $request->nwhr_37,
						'nwhr_38' => $request->nwhr_38,
						'nwhr_39' => $request->nwhr_39,
						'sync' => 0,
				];

				$user_approved = NULL;

				if($this->shouldForkApprovedInspectionRevision($witnessHydro))
				{
					$update = $witnessHydro->create($data);
					$report_id = $update->id;
				}
				else
				{
					$update = $witnessHydro->update($data);
					$report_id = $witnessHydro->id;
					$user_approved = $witnessHydro->report->user_id_approved;
				}

				if ($update)
				{
						$user = Auth::id();
						if ($request->publish == 'yes')
						{
								$user = NULL;
								$last = InspectionReport::where('job_request_id', $request->lcr_1)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
						}

						$this->persistInspectionReportState($witnessHydro, [
								'code' => $code,
								'status' => 1,
								'publish' => $request->publish == 'yes' ? 1 : $witnessHydro->report->publish,
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
     * @param  \App\Models\WitnessHydro  $witnessHydro
     * @return \Illuminate\Http\Response
     */
    public function destroy(WitnessHydro $witnessHydro)
    {
		$remove  = $this->check_edited_reports_for_delete($witnessHydro);
		if ($remove)
		{
			$pdf = 'inspection/ndt/witnesshydro/'.$witnessHydro->job_request->code.'/'.$witnessHydro->code.'.pdf';
			$snap = 'images/inspection/ndt/witnesshydro/'.$witnessHydro->job_request->code.'/'.$witnessHydro->code;
			$remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
			return response()->json([
					'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
			]);
		}
    }
}
