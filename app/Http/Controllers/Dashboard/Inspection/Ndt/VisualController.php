<?php

namespace App\Http\Controllers\Dashboard\Inspection\Ndt;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Inspection\Ndt\Visual;
use App\Models\GeneralInfo\Specification;
use App\Models\Persons\Client;
use App\Models\Persons\Supplier;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;

// Other
use DB, DataTables, Storage, Auth, Crypt;

class VisualController extends Controller
{
    public $page_name = 'Visual Examination Reports';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Visual::class, 'visual');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visuals = Visual::count();
        return view('layouts.inspection.ndt.visual.index', ['page_name' => $this->page_name('All', $this->page_name), 'visuals' => $visuals]);
    }

    public function getDataForDataTable()
		{
        $data = Visual::query()
            ->has('report')
            ->withAggregate('job_request','code')
            ->orderBy('job_request_code', 'Desc');
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
->addColumn('client', function ($row) {
                  $report_code = "";
                  if($row->job_request->client) {
                      if (Auth::user()->can('view', Client::find($row->job_request->client->id))) {
                          $report_code .= "<a href=" . route('client.show', $row->job_request->client->id) . ">";
                      }

                      $report_code .= $row->job_request->client->name;

                      if (Auth::user()->can('view', Client::find($row->job_request->client->id))) {
                          $report_code .= "</a>";
                      }
                  } elseif ($row->job_request->supplier ){
                      if (Auth::user()->can('view', Supplier::find($row->job_request->supplier->id))) {
                          $report_code .= "<a href=" . route('supplier.show', $row->job_request->supplier->id) . ">";
                      }

                      $report_code .= $row->job_request->supplier->name;

                      if (Auth::user()->can('view', Supplier::find($row->job_request->supplier->id))) {
                          $report_code .= "</a>";
                      }

                  }
                  return $report_code;
              })
              ->addColumn('code', function ($row) {
                  $report_code = "";
                  if(Auth::user()->can('view', Visual::find($row->id)))
                  {
                      $report_code .= "<a href=".route('visual.show', $row->id).">";
                  }

                  $report_code .= $row->job_request->code.'/'.$row->code;

                  if(Auth::user()->can('view', Visual::find($row->id)))
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
										if (Auth::user()->can('create', Visual::class))
										{
												$btn .= '<button data-id="'.$row->report->id.'" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
										}

										if ($row->report->publish != NULL)
										{
												if (Storage::disk('public')->exists('pdf/inspection/ndt/visual/'.$row->job_request->code.'/'.$row->report->code.'.pdf'))
												{
														$btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="'.Storage::url('pdf/inspection/ndt/visual/'.$row->job_request->code.'/'.$row->report->code.'.pdf').'">Download PDF</a>';
												}
												else
												{
														$btn .= '<a class="btn btn-dark mr-1" href="'.route('visual.show', $row->id).'">Open Report</a>';
												}

												if (Auth::user()->can('update', Visual::find($row->id)))
												{
														$btn .= '<a href="'.route('visual.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
												}
										}
										else
										{
												$btn .= $this->buildInspectionUnpublishedActionButtons($row, Visual::class, (int) ($row->id), 'visual.show', 'publish.visual', 'visual.edit');
										}


										if (Auth::user()->can('delete', Visual::find($row->id)))
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
                        ->filterColumn('client', function($query, $keyword){
                              $query->whereHas('job_request', function($row) use (&$keyword){
                                  $row->whereHas('client', function($row1) use (&$keyword){
                                      $row1->where("name", "like", ["%{$keyword}%"]);
                                  });
                              });
                          })
            ->filterColumn('action', function ($query, $keyword) {
                $codes = $this->getUploadedCodes('/ndt/visual', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->where('publish', null);
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', visuals.code)"), $codes);
                        });
                        break;
                    case 'upload':
//                        $query->whereRaw("inspection_reports.publish IS NOT NULL AND CONCAT(job_requests.code,'/',forklifts.code) NOT IN ($codes)");
                        $query->whereHas('report', function ($q) {
                            $q->whereNot('publish', null);
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', visuals.code)"), $codes);
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
        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.visual.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'jobrequests' => $jobrequests,
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
        $paymentsArr = $request['payments'] ? $request['payments'] : [0 => ['nvr_22' => '']];
        foreach($paymentsArr as $key => $value)
        {
            $newone[$key]['nvr_22'] = $value['nvr_22'];
            $path_crypt = '';
            if (isset($value['nvr_23']) && $value['nvr_23'] != NULL)
            {
                $path_crypt = Crypt::encryptString($value['nvr_23']->getClientOriginalName());
                $store = $value['nvr_23']->storeAs(
                'public/camera/inspection/ndt/visual',
                $path_crypt
                );
                $newone[$key]['nvr_23'] = $path_crypt;
            }
            else
            {
                $newone[$key]['nvr_23'] = '';
            }
        }

        $job_request = JobRequest::find($request->lcr_1);
				$code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;

        $store = Visual::create([
            'job_request_id' => $request->lcr_1,
            'nvr_2' => $request->nvr_2,
            'code' => $code,
            'nvr_4' => $request->nvr_6,
            'desc' => $request->nvr_8,
            'nvr_6' => $request->nvr_9,
            'nvr_7' => $request->nvr_10,
            'nvr_8' => $request->nvr_11,
            'nvr_9' => $request->nvr_12,
            'nvr_10' => $request->nvr_13,
            'nvr_11' => $request->nvr_14,
            'nvr_12' => $request->nvr_15,
            'nvr_13' => $request->nvr_16,
            'nvr_14' => $request->nvr_17,
            'nvr_15' => $request->nvr_18,
            'nvr_16' => $request->nvr_19,
            'acceptance' => $request->nvr_20,
            'nvr_18' => $request->nvr_21,
            'nvr_19' => json_encode($newone),
            'nvr_23' => $request->nvr_26,
            'sync' => 0,
            // 'nvr_24' => $request->nvr_7,
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
              'success' => $path_crypt
          ]);
      }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Visual  $visual
     * @return \Illuminate\Http\Response
     */
    public function show(Visual $visual)
    {
        $have_edit = $this->check_if_report_edit(Visual::class, $visual);
        $visual->report = $have_edit->default;

        return view('layouts.inspection.ndt.visual.show', [
            'page_name' => $this->page_name,
            'page_text'=>'',
            'visual' => $visual,
            'model' => $visual,
            'code' => $visual->job_request->code.'/'.$visual->code,
            'report_created_at' => $visual->created_at->format('d/m/Y'),
            'pdfurl' => 'storage/pdf/inspection/ndt/visual/'.$visual->job_request->code.'/'.$visual->code.'.pdf',
            'imageurl' => $visual->job_request->code.'/'.$visual->code,
            'folder' => 'pdf/inspection/ndt/visual',
            'hasPermission' => Auth::user()->hasPermission('visual', 'approve'),
            'iso_number' => 'Form # RSE-RF-08 - ISSUE 03 / Jan 2022',
            'page_number' => '1 of 1',
            'have_edit' => $have_edit->edited,
            'user_id_approved' => data_get($visual, 'report.user_id_approved'),
            'for_approve_url' => data_get($visual, 'report.id'),
            'esign' => data_get($visual, 'report.user.employee.esign'),
            'person_make_report' => data_get($visual, 'report.user.employee.name'),
            'person_make_report_desc' => data_get($visual, 'report.user.employee.desc'),
        ]);
    }


    public function publish(Visual $visual)
    {
        $this->authorize('create', Visual::class);
        $jobrequests = JobRequest::select('id','code')->get();
        return view('layouts.inspection.ndt.visual.publish', [
						'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
						'visual' => $visual,
						'jobrequests' => $jobrequests,
				]);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Visual  $visual
     * @return \Illuminate\Http\Response
     */
    public function edit(Visual $visual)
    {
        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.visual.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'visual' => $visual,
            'jobrequests' => $jobrequests,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visual  $visual
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visual $visual)
    {
        $code = $this->resolveInspectionSubmittedCode($request, $visual);

        $newone = [];
				$savedOne = json_decode($visual->nvr_19, true);
        $paymentsArr = $request['payments']? $request['payments'] : [0 => ['nvr_22'=> '']];
        foreach($paymentsArr as $key => $value)
        {
            $newone[$key]['nvr_22'] = $value['nvr_22'];
            $path_crypt = '';
            if(isset($value['nvr_23']) && $value['nvr_23'] != NULL)
            {
                $path_crypt = Crypt::encryptString($value['nvr_23']->getClientOriginalName());
                $store = $value['nvr_23']->storeAs(
                'public/camera/inspection/ndt/visual',
                $path_crypt
                );
                $newone[$key]['nvr_23'] = $path_crypt;
					} else {
						$newone[$key]['nvr_23'] =
						array_key_exists($key, $savedOne) ?
						$savedOne[$key]['nvr_23'] : "";
					}
        }

        $data = [
            'job_request_id' => $request->lcr_1,
            'nvr_2' => $request->nvr_2,
            'code' => $code,
            'nvr_4' => $request->nvr_6,
            'desc' => $request->nvr_8,
            'nvr_6' => $request->nvr_9,
            'nvr_7' => $request->nvr_10,
            'nvr_8' => $request->nvr_11,
            'nvr_9' => $request->nvr_12,
            'nvr_10' => $request->nvr_13,
            'nvr_11' => $request->nvr_14,
            'nvr_12' => $request->nvr_15,
            'nvr_13' => $request->nvr_16,
            'nvr_14' => $request->nvr_17,
            'nvr_15' => $request->nvr_18,
            'nvr_16' => $request->nvr_19,
            'acceptance' => $request->nvr_20,
            'nvr_18' => $request->nvr_21,
            'nvr_19' => json_encode($newone),
            'nvr_23' => $request->nvr_26,
            'sync' => 0,
        ];

        $user_approved = NULL;

        if($this->shouldForkApprovedInspectionRevision($visual))
        {
            $update = $visual->create($data);
            $report_id = $update->id;
        }
        else
        {
            $update = $visual->update($data);
            $report_id = $visual->id;
            $user_approved = $visual->report->user_id_approved;
        }

        if($update)
        {
            $user = Auth::id();
            if ($request->publish == 'yes')
            {
                $user = NULL;
                $last = InspectionReport::where('job_request_id', $request->lcr_1)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
            }

            $this->persistInspectionReportState($visual, [
                'code' => $code,
                'status' => 1,
                'publish' => $request->publish == 'yes' ? 1 : $visual->report->publish,
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
     * @param  \App\Models\Visual  $visual
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visual $visual)
    {
        $remove  = $this->check_edited_reports_for_delete($visual);

        if ($remove)
        {
            $pdf = 'inspection/ndt/visual/'.$visual->job_request->code.'/'.$visual->code.'.pdf';
            $snap = 'images/inspection/ndt/visual/'.$visual->job_request->code.'/'.$visual->code;
            $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
            return response()->json([
                'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
            ]);
        }
    }
}
