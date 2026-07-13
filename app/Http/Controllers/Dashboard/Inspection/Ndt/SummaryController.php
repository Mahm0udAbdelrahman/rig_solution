<?php

namespace App\Http\Controllers\Dashboard\Inspection\Ndt;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Inspection\Ndt\Summary;
use App\Models\WorkFlow\JobRequest;
use App\Models\Persons\Client;
use App\Models\User;
use App\Models\Inspection\InspectionReport;

// Other
use DB, DataTables, Storage, Auth, Crypt;

class SummaryController extends Controller
{
    public $page_name = 'Summary Report';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Summary::class, 'summary');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $summaries = Summary::count();
        return view('layouts.inspection.ndt.summary.index', ['page_name' => $this->page_name('All', $this->page_name), 'summaries' => $summaries]);
    }

    public function getDataForDataTable()
		{
        $data = Summary::query()
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
                  if(Auth::user()->can('view', Summary::find($row->id)))
                  {
                      $report_code .= "<a href=".route('summary.show', $row->id).">";
                  }

                  $report_code .= $row->job_request->code.'/'.$row->code;

                  if(Auth::user()->can('view', Summary::find($row->id)))
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
										if (Auth::user()->can('create', Summary::class))
										{
												$btn .= '<button data-id="'.$row->report->id.'" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
										}

										if ($row->report->publish != NULL)
										{
												if (Storage::disk('public')->exists('pdf/inspection/ndt/summary/'.$row->job_request->code.'/'.$row->report->code.'.pdf'))
												{
														$btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="'.Storage::url('pdf/inspection/ndt/summary/'.$row->job_request->code.'/'.$row->report->code.'.pdf').'">Download PDF</a>';
												}
												else
												{
														$btn .= '<a class="btn btn-dark mr-1" href="'.route('summary.show', $row->id).'">Open Report</a>';
												}

												if (Auth::user()->can('update', Summary::find($row->id)))
												{
														$btn .= '<a href="'.route('summary.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
												}
										}
										else
										{
												$btn .= $this->buildInspectionUnpublishedActionButtons($row, Summary::class, (int) ($row->id), 'summary.show', 'publish.summary', 'summary.edit');
										}


										if (Auth::user()->can('delete', Summary::find($row->id)))
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
                $codes = $this->getUploadedCodes('/ndt/summary', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->where('publish', null);
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', summaries.code)"), $codes);
                        });
                        break;
                    case 'upload':
                        $query->whereHas('report', function ($q) {
                            $q->whereNot('publish', null);
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', summaries.code)"), $codes);
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
      return view('layouts.inspection.ndt.summary.add', ['page_name' => $this->page_name('All', $this->page_name), 'jobrequests' => $jobrequests]);
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
      $store = Summary::create([
        'job_request_id' => $request->lcr_1,
        'nsr_2' => $request->nsr_2,
        'code' => $code,
        'nsr_4' => $request->nsr_6,
        // 'nsr_5' => $request->nsr_7,
        'desc' => $request->nsr_11,
        'nsr_7' => $request->nsr_12,
        'nsr_8' => $request->nsr_13,
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
          // Notification::send(User::all(), new InspectionReport(str_pad($store->code+1, 3,'0',STR_PAD_LEFT), $request->nsr_1));
          return response()->json([
              'last_id' => $store->id,
              'success' => $this->action_message(0, $this->page_name)
          ]);
      }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function show(Summary $summary)
    {
        $have_edit = $this->check_if_report_edit(Summary::class, $summary);
        $summary->report = $have_edit->default;

        return view('layouts.inspection.ndt.summary.show', [
          'page_name' => $this->page_name,
          'page_text'=>'',
          'model' => $summary,
          'summary' => $summary,
          'code' => $summary->job_request->code.'/'.$summary->code,
          'report_created_at' => $summary->created_at->format('d/m/Y'),
          'pdfurl' => 'storage/pdf/inspection/ndt/summary/'.$summary->job_request->code.'/'.$summary->code.'.pdf',
          'hasPermission' => Auth::user()->hasPermission('summary', 'approve'),
          'folder' => 'pdf/inspection/ndt/summary',
          'imageurl' => $summary->job_request->code.'/'.$summary->code,
          'iso_number' => 'Form # RSE-RF-10 - ISSUE 04 / Jan 2022',
          'page_number' => '1 of 1',
          'have_edit' => $have_edit->edited,
          'user_id_approved' => data_get($summary, 'report.user_id_approved'),
          'for_approve_url' => data_get($summary, 'report.id'),
          'esign' => data_get($summary, 'report.user.employee.esign'),
          'person_make_report' => data_get($summary, 'report.user.employee.name'),
          'person_make_report_desc' => data_get($summary, 'report.user.employee.desc'),
        ]);
    }


    public function publish(Summary $summary)
    {
        // $this->authorize('create', Summary::class);
        // $clients = Client::select('id','code','name')->get();
        // $jobrequests = JobRequest::select('id','code')->get();
        // return view('layouts.inspection.ndt.Summary.publish', ['page_name' => "Ndt Summary duplicate", 'Summary' => $Summary, 'clients' => $clients, 'jobrequests' => $jobrequests]);

        $this->authorize('create', Summary::class);

				$jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.summary.publish', [
						'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
						'summary' => $summary,
						'jobrequests' => $jobrequests,

				]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function edit(Summary $summary)
    {


      $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
      return view('layouts.inspection.ndt.summary.edit', [
          'page_name' => $this->page_name(1, $this->page_name),
          'summary' => $summary,
          'jobrequests' => $jobrequests,
      ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Summary $summary)
    {
      $code = $this->resolveInspectionSubmittedCode($request, $summary);


      $data = [
        'job_request_id' => $request->lcr_1,
        'nsr_2' => $request->nsr_2,
        'code' => $code,
        'nsr_4' => $request->nsr_6,
        // 'nsr_5' => $request->nsr_7,
        'desc' => $request->nsr_11,
        'nsr_7' => $request->nsr_12,
        'nsr_8' => $request->nsr_13,
        'sync' => 0,
      ];

      $user_approved = NULL;

      if($this->shouldForkApprovedInspectionRevision($summary))
      {
          $update = $summary->create($data);
          $report_id = $update->id;
      }
      else
      {
          $update = $summary->update($data);
          $report_id = $summary->id;
          $user_approved = $summary->report->user_id_approved;
      }

      if($update)
      {
        $user = Auth::id();
        if ($request->publish == 'yes')
        {
            $user = NULL;
            $last = InspectionReport::where('job_request_id', $request->lcr_1)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
        }

        $this->persistInspectionReportState($summary, [
          'code' => $code,
          'status' => 1,
          'publish' => $request->publish == 'yes' ? 1 : $summary->report->publish,
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
     * @param  \App\Models\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Summary $summary)
    {

      $remove  = $this->check_edited_reports_for_delete($summary);

      if ($remove)
      {
          $pdf = 'inspection/ndt/summary/'.$summary->job_request->code.'/'.$summary->code.'.pdf';
          $snap = 'images/inspection/ndt/summary/'.$summary->job_request->code.'/'.$summary->code;
          $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
          return response()->json([
              'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
          ]);
      }
    }
}
