<?php

namespace App\Http\Controllers\Dashboard\Inspection\Ndt;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\GeneralInfo\Specification;
// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Inspection\Ndt\Ultrasonic;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Models\Persons\Client;
use App\Models\User;

// Other
use DB, DataTables, Storage, Auth, Crypt;

class UltrasonicController extends Controller
{
		public $page_name = 'Ultrasonic Examination Report';

    public function __construct()
    {
	      $this->middleware('auth');
	      $this->authorizeResource(Ultrasonic::class, 'ultrasonic');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
				$ultrasonics = Ultrasonic::count();
	      return view('layouts.inspection.ndt.ultrasonic.index', ['page_name' => $this->page_name('All', $this->page_name), 'ultrasonics' => $ultrasonics]);
    }

		public function getDataForDataTable(Request $request)
		{
				$data = Ultrasonic::query()
            ->has('report')
            ->withAggregate('job_request','code')
            ->orderBy('job_request_code', 'Desc');
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
->addColumn('client', function ($row) {
									$report_code = "";
									if (Auth::user()->can('view', Client::find($row->job_request->client->id)))
									{
										$report_code .= "<a href=".route('client.show', $row->job_request->client->id).">";
									}

									$report_code .= $row->job_request->client->name ;

									if (Auth::user()->can('view', Client::find($row->job_request->client->id)))
									{
											$report_code .= "</a>";
									}
									return $report_code;
							})
							->addColumn('code', function ($row) {
									$report_code = "";
									if(Auth::user()->can('view', Ultrasonic::find($row->id)))
									{
											$report_code .= "<a href=".route('ultrasonic.show', $row->id).">";
									}

									$report_code .= $row->job_request->code.'/'.$row->code;

									if(Auth::user()->can('view', Ultrasonic::find($row->id)))
									{
											$report_code .= "</a>";
									}
									return $report_code;
							})
							->addColumn('deploc', function ($row) {

									return $row->job_request->deploc;
							})
                    ->addColumn('client_department', function ($row) {

                        return $row->job_request->clientDepartment ? $row->job_request->clientDepartment->name : '';
                    })
							->addColumn('action', function ($row) {
										$btn = "";
										if (Auth::user()->can('create', Ultrasonic::class))
										{
												$btn .= '<button data-id="'.$row->report->id.'" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
										}

										if ($row->report->publish != NULL)
										{
												if (Storage::disk('public')->exists('pdf/inspection/ndt/ultrasonic/'.$row->job_request->code.'/'.$row->report->code.'.pdf'))
												{
														$btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="'.Storage::url('pdf/inspection/ndt/ultrasonic/'.$row->job_request->code.'/'.$row->report->code.'.pdf').'">Download PDF</a>';
												}
												else
												{
														$btn .= '<a class="btn btn-dark mr-1" href="'.route('ultrasonic.show', $row->id).'">Open Report</a>';
												}

												if (Auth::user()->can('update', Ultrasonic::find($row->id)))
												{
														$btn .= '<a href="'.route('ultrasonic.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
												}
										}
										else
										{
												$btn .= $this->buildInspectionUnpublishedActionButtons($row, Ultrasonic::class, (int) ($row->id), 'ultrasonic.show', 'publish.ultrasonic', 'ultrasonic.edit');
										}


										if (Auth::user()->can('delete', Ultrasonic::find($row->id)))
										{
												$btn .= '<button type="button" data-id="'.$row->id.'" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
										}
										return $btn;
								})
								->filterColumn('acceptance', function($query, $keyword){
									$query->whereRaw("acceptance like ?", ["%{$keyword}%"]);
							})
							->filterColumn('code', function($query, $keyword){
								$query->whereRaw("code like ?", ["%{$keyword}%"])
								->orWhereHas('job_request', function($row) use (&$keyword){
									$row->where("code", "like", ["%{$keyword}%"]);
								});
							  })
							  ->filterColumn('deploc', function($query, $keyword){
									$query->whereHas('job_request', function($row) use (&$keyword){
										// $row->whereHas('client', function($row1) use (&$keyword){
											$row->where("deploc", "like", ["%{$keyword}%"]);
										// });
									});
								})
                    ->filterColumn('client_department', function ($query, $keyword) {
                        $query->whereHas('job_request', function ($row) use (&$keyword) {
                            $row->whereHas('clientDepartment', function ($row1) use (&$keyword) {
                                $row1->where("name", "like", ["%{$keyword}%"]);
                            });
                        });
                    })
                    ->filterColumn('client', function ($query, $keyword) {
                        $query->whereHas('job_request', function ($row) use (&$keyword) {
                            $row->whereHas('client', function ($row1) use (&$keyword) {
                                $row1->where("name", "like", ["%{$keyword}%"]);
                            });
                        });
                    })
                    ->filterColumn('action', function ($query, $keyword) {
                        $codes = $this->getUploadedCodes('/ndt/ultrasonic', true);
                        switch ($keyword) {
                            case 'publish':
                                $query->whereHas('report', function ($q) {
                                    $q->where('publish', null);
                                });
                                break;
                            case 'download':
                                $query->whereHas('job_request', function ($q) use ($codes) {
                                    $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', ultrasonics.code)"), $codes);
                                });
                                break;
                            case 'upload':
                                $query->whereHas('report', function ($q) {
                                    $q->whereNot('publish', null);
                                });
                                $query->whereHas('job_request', function ($q) use ($codes) {
                                    $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', ultrasonics.code)"), $codes);
                                });

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
				$specifications = DB::table('specifications')->select('id', 'name')->get();
				$jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
	      return view('layouts.inspection.ndt.ultrasonic.add', [
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

        $path = $request->file('nur_47');

        $path_crypt = NULL;

        if($path != NULL)
				{
	          $path_crypt = Crypt::encryptString($path->getClientOriginalName());
	          $store = $path->storeAs(
	          'public/camera/inspection/ndt/ultrasonic',
	          $path_crypt
	          );
        }

				$job_request = JobRequest::find($request->lcr_1);
        $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;

        $store = Ultrasonic::create([
	          'job_request_id' => $request->lcr_1,
	          'nur_2' => $request->nur_2,
	          'code' => $code,
	          // 'nur_4' => $request->nur_6,
	          // 'nur_5' => $request->nur_7,
	          'nur_6' => $request->nur_6,
	          // 'nur_7' => $request->nur_9,
	          'nur_8' => $request->nur_8,
	          'nur_9' => $request->nur_9,
	          'nur_10' => $request->nur_10,
	          'acceptance' => $request->nur_11,

	          'nur_12' => $request->nur_12,
	          'nur_13' => $request->nur_13,
	          'nur_14' => $request->nur_14,
	          'nur_15' => $request->nur_15,
	          'nur_16' => $request->nur_16,
	          'nur_17' => $request->nur_17,
	          'nur_18' => $request->nur_18,
	          'nur_19' => $request->nur_19,
	          'nur_20' => $request->nur_20,

	          'nur_21' => $request->nur_102,

	          'nur_22' => $request->nur_45,
						'nur_23' => $request->nur_46,
	          'nur_24' => $path_crypt,
	          'desc' => $request->nur_48,

	          'nur_26' => $request->nur_49,
	          'nur_27' => $request->nur_50,
	          'nur_28' => $request->nur_51,
	          'nur_29' => $request->nur_52,
	          'nur_30' => $request->nur_53,
	          'nur_31' => $request->nur_54,
	          'nur_32' => $request->nur_55,

	          'nur_33' => json_encode($request['payments']),
						'nur_34' => $request->nur_64,
						'sync' => 0,
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

            return response()->json([
                'last_id' => $store->id,
                'success' => $this->action_message(0, $this->page_name)
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ultrasonic  $ultrasonic
     * @return \Illuminate\Http\Response
     */
    public function show(Ultrasonic $ultrasonic)
    {
		$have_edit = $this->check_if_report_edit(Ultrasonic::class, $ultrasonic);
		$ultrasonic->report = $have_edit->default;
		$specificationsList = Specification::getNdtSpecifications()->pluck('name', 'code');
		$ultrasonic->specifications = json_decode($ultrasonic->nur_8);

        return view('layouts.inspection.ndt.ultrasonic.show', [
			'page_name' => $this->page_name,
			'page_text'=>'',
			'ultrasonic' => $ultrasonic,
			'model' => $ultrasonic,
			'code' => $ultrasonic->job_request->code.'/'.$ultrasonic->code,
			'specificationOptions' => $specificationsList,
			'report_created_at' => $ultrasonic->created_at->format('d/m/Y'),
			'pdfurl' => 'storage/pdf/inspection/ndt/ultrasonic/'.$ultrasonic->job_request->code.'/'.$ultrasonic->code.'.pdf',
			'hasPermission' => Auth::user()->hasPermission('ultrasonic', 'approve'),
			'imageurl' => $ultrasonic->job_request->code.'/'.$ultrasonic->code,
			'folder' => 'pdf/inspection/ndt/ultrasonic',
			'hasPermission' => Auth::user()->hasPermission('ultrasonic', 'approve'),
			'iso_number' => 'Form # RSE-RF-09 - ISSUE 04 / Jan 2022',
			'page_number' => '1 of 1',
			'have_edit' => $have_edit->edited,
			'user_id_approved' => data_get($ultrasonic, 'report.user_id_approved'),
			'for_approve_url' => data_get($ultrasonic, 'report.id'),
			'esign' => data_get($ultrasonic, 'report.user.employee.esign'),
			'person_make_report' => data_get($ultrasonic, 'report.user.employee.name'),
			'person_make_report_desc' => data_get($ultrasonic, 'report.user.employee.desc'),
        ]);
    }

    public function publish(Ultrasonic $ultrasonic)
    {
				$this->authorize('create', Ultrasonic::class);
				$specifications =  Specification::getNdtSpecifications();
				$jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.ultrasonic.publish', [
						'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
						'ultrasonic' => $ultrasonic,
						'jobrequests' => $jobrequests,
						'specifications' => $specifications
				]);
		}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ultrasonic  $ultrasonic
     * @return \Illuminate\Http\Response
     */
    public function edit(Ultrasonic $ultrasonic)
    {
		  $specifications = Specification::getNdtSpecifications();
				$jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
				return view('layouts.inspection.ndt.ultrasonic.edit', [
						'page_name' => $this->page_name(1, $this->page_name),
						'ultrasonic' => $ultrasonic,
						'jobrequests' => $jobrequests,
						'specifications' => $specifications
				]);
		}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ultrasonic  $ultrasonic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ultrasonic $ultrasonic)
    {
				$code = $this->resolveInspectionSubmittedCode($request, $ultrasonic);

				$path = $request->file('nur_47');
				$path_crypt = NULL;
				if ($path != NULL)
				{
						$path_crypt = Crypt::encryptString($path->getClientOriginalName());
						$store = $path->storeAs(
								'public/camera/inspection/ndt/ultrasonic',
								$path_crypt
						);
				}
				else
				{
						if($request->imagedata == '' && $request->publish != 'yes'){
								Storage::disk('public')->delete('camera/inspection/ndt/ultrasonic/'.$ultrasonic->nur_47);
						}
						$path_crypt = $request->imagedata;
				}

	      $data = [
					'job_request_id' => $request->lcr_1,
					'nur_2' => $request->nur_2,
					'code' => $code,
					// 'nur_4' => $request->nur_6,
					// 'nur_5' => $request->nur_7,
					'nur_6' => $request->nur_6,
					// 'nur_7' => $request->nur_9,
					'nur_8' => $request->nur_8,
					'nur_9' => $request->nur_9,
					'nur_10' => $request->nur_10,
					'acceptance' => $request->nur_11,

					'nur_12' => $request->nur_12,
					'nur_13' => $request->nur_13,
					'nur_14' => $request->nur_14,
					'nur_15' => $request->nur_15,
					'nur_16' => $request->nur_16,
					'nur_17' => $request->nur_17,
					'nur_18' => $request->nur_18,
					'nur_19' => $request->nur_19,
					'nur_20' => $request->nur_20,

					'nur_21' => $request->nur_102,

					'nur_22' => $request->nur_45,
					'nur_23' => $request->nur_46,
					'nur_24' => $path_crypt,
					'desc' => $request->nur_48,

					'nur_26' => $request->nur_49,
					'nur_27' => $request->nur_50,
					'nur_28' => $request->nur_51,
					'nur_29' => $request->nur_52,
					'nur_30' => $request->nur_53,
					'nur_31' => $request->nur_54,
					'nur_32' => $request->nur_55,

					'nur_33' => json_encode($request['payments']),
					'nur_34' => $request->nur_64,
					'sync' => 0,
	      ];

		  $user_approved = NULL;

			if($this->shouldForkApprovedInspectionRevision($ultrasonic))
			{
				$update = $ultrasonic->create($data);
				$report_id = $update->id;
			}
			else
			{
				$update = $ultrasonic->update($data);
				$report_id = $ultrasonic->id;
				$user_approved = $ultrasonic->report->user_id_approved;
			}

	      if($update)
	      {
						$user = Auth::id();
						if ($request->publish == 'yes')
						{
								$user = NULL;
								$last = InspectionReport::where('job_request_id', $request->lcr_1)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
						}

		        $this->persistInspectionReportState($ultrasonic, [
					'code' => $code,
					'status' => 1,
					'publish' => $request->publish == 'yes' ? 1 : $ultrasonic->report->publish,
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
     * @param  \App\Models\Ultrasonic  $ultrasonic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ultrasonic $ultrasonic)
    {
		$remove  = $this->check_edited_reports_for_delete($ultrasonic);

		if ($remove)
		{
			$pdf = 'inspection/ndt/ultrasonic/'.$ultrasonic->job_request->code.'/'.$ultrasonic->code.'.pdf';
			$snap = 'images/inspection/ndt/ultrasonic/'.$ultrasonic->job_request->code.'/'.$ultrasonic->code;
			$remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
			return response()->json([
				'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
			]);
		}
    }
}
