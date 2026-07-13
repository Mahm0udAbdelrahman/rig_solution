<?php

namespace App\Http\Controllers\Dashboard\Inspection\Ndt;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Ndt\Attached;
use App\Models\Persons\Client;
use App\Models\WorkFlow\JobRequest;
use App\Models\User;

// Other
use DB, DataTables, Storage, Auth, Crypt;

class AttachedController extends Controller
{
    public $page_name = 'Attached Report';

    public function __construct()
		{
				$this->middleware('auth');
				$this->authorizeResource(Attached::class, 'attached');
		}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attacheds = Attached::count();
        return view('layouts.inspection.ndt.attached.index', ['page_name' => $this->page_name('All', $this->page_name), 'attacheds' => $attacheds]);
    }

    public function getDataForDataTable()
		{
        $data = Attached::query()
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
                  if(Auth::user()->can('view', Attached::find($row->id)))
                  {
                      $report_code .= "<a href=".route('attached.show', $row->id).">";
                  }

                  $report_code .= $row->job_request->code.'/'.$row->code;

                  if(Auth::user()->can('view', Attached::find($row->id)))
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
										if (Auth::user()->can('create', Attached::class))
										{
												$btn .= '<button data-id="'.$row->report->id.'" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
										}

										if ($row->report->publish != NULL)
										{
												if (Storage::disk('public')->exists('pdf/inspection/ndt/attached/'.$row->job_request->code.'/'.$row->report->code.'.pdf'))
												{
														$btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="'.Storage::url('pdf/inspection/ndt/attached/'.$row->job_request->code.'/'.$row->report->code.'.pdf').'">Download PDF</a>';
												}
												else
												{
														$btn .= '<a class="btn btn-dark mr-1" href="'.route('attached.show', $row->id).'">Open Report</a>';
												}

												if (Auth::user()->can('update', Attached::find($row->id)))
												{
														$btn .= '<a href="'.route('attached.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
												}
										}
										else
										{
												$btn .= $this->buildInspectionUnpublishedActionButtons($row, Attached::class, (int) ($row->id), 'attached.show', 'publish.attached', 'attached.edit');
										}


										if (Auth::user()->can('delete', Attached::find($row->id)))
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
                $codes = $this->getUploadedCodes('/ndt/attached', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->where('publish', null);
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', attacheds.code)"), $codes);
                        });
                        break;
                    case 'upload':
//                        $query->whereRaw("inspection_reports.publish IS NOT NULL AND CONCAT(job_requests.code,'/',forklifts.code) NOT IN ($codes)");
                        $query->whereHas('report', function ($q) {
                            $q->whereNot('publish', null);
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', attacheds.code)"), $codes);
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
      return view('layouts.inspection.ndt.attached.add', ['page_name' => $this->page_name('All', $this->page_name), 'jobrequests' => $jobrequests]);
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
      foreach($request['payments'] as $key => $value)
      {
          $newone[$key]['nar_10'] = $value['nar_10'];
          $path_crypt = '';
          if (isset($value['nar_11']) && $value['nar_11'] != NULL)
          {
              $path_crypt = Crypt::encryptString($value['nar_11']->getClientOriginalName());
              $store = $value['nar_11']->storeAs(
                'public/camera/inspection/ndt/attached',
                $path_crypt
              );
              $newone[$key]['nar_11'] = $path_crypt;
          }
          else
          {
              $newone[$key]['nar_11'] = '';
          }
      }

      $job_request = JobRequest::find($request->lcr_1);
      $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;

      /***************************/
      $store = Attached::create([
        'job_request_id' => $request->lcr_1,
        'nar_2' => $request->nar_2,
        'code' => $code,
        'nar_4' => $request->nar_6,
        'desc' => $request->nar_15,
        'nar_6' => json_encode($newone),
        'sync' => 0,
      ]);

      if($store){
          $store->report()->create([
            'job_request_id' => $request->lcr_1,
            'code' => $code,
            'status' => 1,
            'publish' => null,
            'sync' => 1,
            'user_id' => Auth::id(),
          ]);
          // Notification::send(User::all(), new InspectionReport(str_pad($store->code+1, 3,'0',STR_PAD_LEFT), $request->nar_1));
          return response()->json([
              'last_id' => $store->id,
              'success' => $this->action_message(0, $this->page_name)
          ]);
      }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attached  $attached
     * @return \Illuminate\Http\Response
     */
    public function show(Attached $attached)
    {
      $have_edit = $this->check_if_report_edit(Attached::class, $attached);
      $attached->report = $have_edit->default;

      return view('layouts.inspection.ndt.attached.show', [
        'page_name' => $this->page_name,
        'page_text'=>'',
        'attached' => $attached,
        'model' => $attached,
        'code' => $attached->job_request->code.'/'.$attached->code,
        'report_created_at' => $attached->created_at->format('d/m/Y'),
        'pdfurl' => 'storage/pdf/inspection/ndt/attached/'.$attached->job_request->code.'/'.$attached->code.'.pdf',
        'hasPermission' => Auth::user()->hasPermission('attached', 'approve'),
        'folder' => 'pdf/inspection/ndt/attached',
        'imageurl' => $attached->job_request->code.'/'.$attached->code,
        'iso_number' => 'Form # RSE-RF-11 - ISSUE 04 / Jan 2022',
        'page_number' => '1 of 1',
        'have_edit' => $have_edit->edited,
        'user_id_approved' => data_get($attached, 'report.user_id_approved'),
        'for_approve_url' => data_get($attached, 'report.id'),
        'esign' => data_get($attached, 'report.user.employee.esign'),
        'person_make_report' => data_get($attached, 'report.user.employee.name'),
        'person_make_report_desc' => data_get($attached, 'report.user.employee.desc'),
      ]);

    }

    public function publish(Attached $attached)
    {
        // $this->authorize('create', Summary::class);
        // $clients = Client::select('id','code','name')->get();
        // $jobrequests = JobRequest::select('id','code')->get();
        // return view('layouts.inspection.ndt.Summary.publish', ['page_name' => "Ndt Summary duplicate", 'Summary' => $Summary, 'clients' => $clients, 'jobrequests' => $jobrequests]);

        $this->authorize('create', Attached::class);

				$jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.attached.publish', [
						'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
						'attached' => $attached,
						'jobrequests' => $jobrequests,

				]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attached  $attached
     * @return \Illuminate\Http\Response
     */
    public function edit(Attached $attached)
    {
      // $clients = Client::select('id','code','name')->get();
      // $jobrequests = JobRequest::select('id','code')->orderBy('id', 'desc')->get();
      // return view('layouts.inspection.ndt.attached.edit', ['page_name' => "Attached edit", 'attached' => $attached, 'clients' => $clients, 'jobrequests' => $jobrequests]);

      $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
      return view('layouts.inspection.ndt.attached.edit', [
          'page_name' => $this->page_name(1, $this->page_name),
          'attached' => $attached,
          'jobrequests' => $jobrequests,
      ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attached  $attached
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attached $attached)
    {

      $code = $this->resolveInspectionSubmittedCode($request, $attached);

      $newone = [];
      foreach($request['payments'] as $key => $value)
      {
          $newone[$key]['nar_10'] = $value['nar_10'];
          $path_crypt = '';
          if (isset($value['nar_11']) && $value['nar_11'] != NULL)
          {
              $path_crypt = Crypt::encryptString($value['nar_11']->getClientOriginalName());
              $store = $value['nar_11']->storeAs(
                  'public/camera/inspection/ndt/attached',
                  $path_crypt
              );
              $newone[$key]['nar_11'] = $path_crypt;
          }
          else
          {
              $newone[$key]['nar_11'] = $value['imagedata'];
          }
      }

      $data = [
        'job_request_id' => $request->lcr_1,
        'nar_2' => $request->nar_2,
        'code' => $code,
        'nar_4' => $request->nar_6,
        'desc' => $request->nar_15,
        'nar_6' => json_encode($newone),
        'sync' => 0,
      ];

      $user_approved = NULL;

      if($this->shouldForkApprovedInspectionRevision($attached))
      {
        $update = $attached->create($data);
        $report_id = $update->id;
      }
      else
      {
        $update = $attached->update($data);
        $report_id = $attached->id;
        $user_approved = $attached->report->user_id_approved;
      }

      if($update)
      {
          $user = Auth::id();
          if ($request->publish == 'yes')
          {
              $user = NULL;
              $last = InspectionReport::where('job_request_id', $request->lcr_1)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
          }

          $this->persistInspectionReportState($attached, [
              'code' => $code,
              'status' => 1,
              'publish' => $request->publish == 'yes' ? 1 : $attached->report->publish,
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
     * @param  \App\Models\Attached  $attached
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attached $attached)
    {
      $remove  = $this->check_edited_reports_for_delete($attached);

      if ($remove)
      {
          $pdf = 'inspection/ndt/attached/'.$attached->job_request->code.'/'.$attached->code.'.pdf';
          $snap = 'images/inspection/ndt/attached/'.$attached->job_request->code.'/'.$attached->code;
          $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
          return response()->json([
              'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
          ]);
      }
    }
}
