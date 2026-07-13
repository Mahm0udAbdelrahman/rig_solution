<?php

namespace App\Http\Controllers\Dashboard\Inspection\Ndt;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Inspection\Ndt\HighPressure;
use App\Models\GeneralInfo\Specification;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Models\Persons\Client;
use App\Models\User;

// Other
use DB, DataTables, Storage, Auth, Crypt;

class HighPressureController extends Controller
{
    public $page_name = 'Ultra Sonic Wall Thickness Report';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(HighPressure::class, 'highPressure');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $high_pressures = HighPressure::count();
        return view('layouts.inspection.ndt.highpressure.index', ['page_name' => $this->page_name('All', $this->page_name), 'high_pressures' => $high_pressures]);
    }

    public function getDataForDataTable()
		{
        $data = HighPressure::query()->has('report')->orderBy('job_request_id', 'Desc');
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
                  if(Auth::user()->can('view', HighPressure::find($row->id)))
                  {
                      $report_code .= "<a href=".route('highPressure.show', $row->id).">";
                  }

                  $report_code .= $row->job_request->code.'/'.$row->code;

                  if(Auth::user()->can('view', HighPressure::find($row->id)))
                  {
                      $report_code .= "</a>";
                  }
                  return $report_code;
              })
              ->addColumn('deploc', function ($row) {

                  return $row->job_request->deploc;
              })
            ->addColumn('client_department', function ($row) {

                return $row->job_request->clientDepartment? $row->job_request->clientDepartment->name : '';
            })
              ->addColumn('action', function ($row) {
										$btn = "";
										if (Auth::user()->can('create', HighPressure::class))
										{
												$btn .= '<button data-id="'.$row->report->id.'" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
										}

										if ($row->report->publish != NULL)
										{
												if (Storage::disk('public')->exists('pdf/inspection/ndt/highpressure/'.$row->job_request->code.'/'.$row->report->code.'.pdf'))
												{
														$btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="'.Storage::url('pdf/inspection/ndt/highpressure/'.$row->job_request->code.'/'.$row->report->code.'.pdf').'">Download PDF</a>';
												}
												else
												{
														$btn .= '<a class="btn btn-dark mr-1" href="'.route('highPressure.show', $row->id).'">Open Report</a>';
												}

												if (Auth::user()->can('update', HighPressure::find($row->id)))
												{
														$btn .= '<a href="'.route('highPressure.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
												}
										}
										else
										{
												$btn .= $this->buildInspectionUnpublishedActionButtons($row, HighPressure::class, (int) ($row->id), 'highPressure.show', 'publish.highPressure', 'highPressure.edit');
										}


										if (Auth::user()->can('delete', HighPressure::find($row->id)))
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
                $codes = $this->getUploadedCodes('/ndt/highpressure', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->where('publish', null);
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', high_pressures.code)"), $codes);
                        });
                        break;
                    case 'upload':
//                        $query->whereRaw("inspection_reports.publish IS NOT NULL AND CONCAT(job_requests.code,'/',forklifts.code) NOT IN ($codes)");
                        $query->whereHas('report', function ($q) {
                            $q->whereNot('publish', null);
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', high_pressures.code)"), $codes);
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
      // $specifications = DB::table('specifications')->select('id', 'name')->get();
      // $jobrequests = JobRequest::select('id','code')->orderBy('id', 'desc')->get();
      // return view('layouts.inspection.ndt.highpressure.add', ['page_name' => 'Create High Pressure Wall Thickness Report', 'jobrequests' => $jobrequests, 'specifications' => $specifications]);

      $specifications = Specification::getNdtSpecifications();
      $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
      return view('layouts.inspection.ndt.highpressure.add', [
          'page_name' => $this->page_name(0, $this->page_name),
          // 'ultrasonic' => $ultrasonic,
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
        $job_request = JobRequest::find($request->lcr_1);
        $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;
        $store = HighPressure::create([
          'job_request_id' => $request->lcr_1,
          'nhpr_2' => $request->nhpr_2,
          'code' => $code,
          // 'nhpr_4',
          // 'nhpr_5',
          'nhpr_6' => $request->lcr_60,
          'nhpr_7' => $request->lcr_7,
          // 'nhpr_8',
          'desc' => $request->nhpr_9,
		  'edition' => $request->edition,
          'nhpr_10' => $request->nhpr_10,
          'nhpr_11' => json_encode($request->nhpr_11),
          'nhpr_12' => $request->nhpr_12,
          'acceptance' => $request->nhpr_13,
          'nhpr_14' => $request->nhpr_14,
          'nhpr_15' => $request->nhpr_15,
          'nhpr_16' => $request->nhpr_16,
          'nhpr_17' => $request->nhpr_17,
          'nhpr_18' => $request->nhpr_18,
          'nhpr_19' => $request->nhpr_19,
          'nhpr_20' => $request->nhpr_20,
          'nhpr_21' => $request->nhpr_21,
          'nhpr_22' => $request->nhpr_22,
          'nhpr_23' => $request->nhpr_23,
          'nhpr_24' => $request->nhpr_24,
          'nhpr_25' => $request->nhpr_25,
          'nhpr_26' => $request->nhpr_26,
          'nhpr_27' => $request->nhpr_27,
          'nhpr_28' => $request->nhpr_28,
          'nhpr_29' => $request->nhpr_29,
          'nhpr_30' => $request->nhpr_30,
          'nhpr_31' => json_encode($request['payments']),
          'nhpr_32' => $request->nhpr_31,

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
     * @param  \App\Models\HighPressure  $highPressure
     * @return \Illuminate\Http\Response
     */
    public function show(HighPressure $highPressure)
    {
        $have_edit = $this->check_if_report_edit(HighPressure::class, $highPressure);
		$highPressure->report = $have_edit->default;
		$specificationsList = Specification::getNdtSpecifications()->pluck('name', 'code');
		$highPressure->specifications = json_decode($highPressure->nhpr_11);
        return view('layouts.inspection.ndt.highpressure.show', [
            'page_name' => $this->page_name,
            'page_text' => '',
            'highPressure' => $highPressure,
            'model' => $highPressure,
            'code' => $highPressure->job_request->code . '/' . $highPressure->code,
			'specificationOptions' => $specificationsList,
            'report_created_at' => $highPressure->created_at->format('d/m/Y'),
            'pdfurl' => 'storage/pdf/inspection/ndt/highpressure/' . $highPressure->job_request->code . '/' . $highPressure->code . '.pdf',
            'hasPermission' => Auth::user()->hasPermission('highpressure', 'approve'),
            'imageurl' => $highPressure->job_request->code . '/' . $highPressure->code,
            'folder' => 'pdf/inspection/ndt/highpressure',
            'iso_number' => 'Form # RSE-RF-13 - ISSUE 03 / Jan 2022',
            'page_number' => '1 of 1',
            'have_edit' => $have_edit->edited,
            'user_id_approved' => data_get($highPressure, 'report.user_id_approved'),
            'for_approve_url' => data_get($highPressure, 'report.id'),
            'esign' => data_get($highPressure, 'report.user.employee.esign'),
            'person_make_report' => data_get($highPressure, 'report.user.employee.name'),
            'person_make_report_desc' => data_get($highPressure, 'report.user.employee.desc'),
        ]);
    }

    public function publish(HighPressure $highPressure)
    {
				$this->authorize('create', HighPressure::class);
				$specifications = Specification::getNdtSpecifications();
				$jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.highpressure.publish', [
						'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
						'highPressure' => $highPressure,
						'jobrequests' => $jobrequests,
						'specifications' => $specifications
				]);
		}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HighPressure  $highPressure
     * @return \Illuminate\Http\Response
     */
    public function edit(HighPressure $highPressure)
    {
      // $clients = Client::select('id','code','name')->get();
      // $jobrequests = JobRequest::select('id','code')->orderBy('id', 'desc')->get();
      // return view('layouts.inspection.ndt.highpressure.edit', ['page_name' => "High Pressure Wall Thickness edit", 'highPressure' => $highPressure, 'clients' => $clients, 'jobrequests' => $jobrequests]);

      $specifications = Specification::getNdtSpecifications();
      $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
	//   dd(json_decode($highPressure->nhpr_11));
      return view('layouts.inspection.ndt.highpressure.edit', [
          'page_name' => $this->page_name(1, $this->page_name),
          'highPressure' => $highPressure,
          'jobrequests' => $jobrequests,
          'specifications' => $specifications
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HighPressure  $highPressure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HighPressure $highPressure)
    {
      if ($request->publish == 'yes')
      {
          $job_request = JobRequest::find($request->lcr_1);
          $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;
      }
      else
      {
          $code = $request->code;
      }
      $data = [
        'job_request_id' => $request->lcr_1,
        'nhpr_2' => $request->nhpr_2,
        'code' => $code,
        // 'nhpr_4',
        // 'nhpr_5',
        'nhpr_6' => $request->lcr_60,
        'nhpr_7' => $request->lcr_7,
        // 'nhpr_8',
        'desc' => $request->nhpr_9,
		'edition' => $request->edition,
        'nhpr_10' => $request->nhpr_10,
        'nhpr_11' => json_encode($request->nhpr_11),
        'nhpr_12' => $request->nhpr_12 ? $request->nhpr_12 : '',
        'acceptance' => $request->nhpr_13,
        'nhpr_14' => $request->nhpr_14,
        'nhpr_15' => $request->nhpr_15,
        'nhpr_16' => $request->nhpr_16,
        'nhpr_17' => $request->nhpr_17,
        'nhpr_18' => $request->nhpr_18,
        'nhpr_19' => $request->nhpr_19,
        'nhpr_20' => $request->nhpr_20,
        'nhpr_21' => $request->nhpr_21,
        'nhpr_22' => $request->nhpr_22,
        'nhpr_23' => $request->nhpr_23,
        'nhpr_24' => $request->nhpr_24,
        'nhpr_25' => $request->nhpr_25,
        'nhpr_26' => $request->nhpr_26,
        'nhpr_27' => $request->nhpr_27,
        'nhpr_28' => $request->nhpr_28,
        'nhpr_29' => $request->nhpr_29,
        'nhpr_30' => $request->nhpr_30,
        'nhpr_31' => json_encode($request['payments']),
        'nhpr_32' => $request->nhpr_31,
      ];

      $user_approved = NULL;

      if($highPressure->report->user_id_approved != NULL)
      {
          $update = $highPressure->create($data);
          $report_id = $update->id;
      }
      else
      {
          $update = $highPressure->update($data);
          $report_id = $highPressure->id;
          $user_approved = $highPressure->report->user_id_approved;
      }

      if($update)
      {
          $user = Auth::id();
          if ($request->publish == 'yes')
          {
              $user = NULL;
              $last = InspectionReport::where('job_request_id', $request->lcr_1)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
          }

          $this->persistInspectionReportState($highPressure, [
              'code' => $code,
              'status' => 1,
              'publish' => $request->publish == 'yes' ? 1 : $highPressure->report->publish,
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
     * @param  \App\Models\HighPressure  $highPressure
     * @return \Illuminate\Http\Response
     */
    public function destroy(HighPressure $highPressure)
    {
      $remove  = $this->check_edited_reports_for_delete($highPressure);

      if ($remove)
      {
          $pdf = 'inspection/ndt/highpressure/'.$highPressure->job_request->code.'/'.$highPressure->code.'.pdf';
          $snap = 'images/inspection/ndt/highpressure/'.$highPressure->job_request->code.'/'.$highPressure->code;
          $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
          return response()->json([
              'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
          ]);
      }
    }
}
