<?php

namespace App\Http\Controllers\Dashboard\Inspection\Lifting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Inspection\Lifting\Forklift;
use App\Models\Persons\Client;
use App\Models\User;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;

// Other
use DB, DataTables, Storage, Auth, Crypt;

class ForkliftController extends Controller
{
    public $page_name = 'Forklift Report';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Forklift::class, 'forklift');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forklifts = Forklift::query();
        $this->applyPreferredInspectionFamilyRowConstraint($forklifts, 'forklifts', Forklift::class);
        $forklifts = $forklifts->count();
        return view('layouts.inspection.lifting.forklift.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'forklifts' => $forklifts
        ]);
    }

    public function getDataForDataTable(Request $request)
		{
				// select data from throughexaminations and join in clients and job requets tables.
        $data = Forklift::join('job_requests', 'forklifts.job_request_id', '=', 'job_requests.id')
              ->join('inspection_reports', function ($join) {
                      $join->on('forklifts.id', '=', 'inspection_reports.reportable_id')
                      ->where('inspection_reports.reportable_type', '=', 'App\Models\Inspection\Lifting\Forklift');
                  })
              ->join('clients', 'job_requests.client_id', '=', 'clients.id')
            ->leftJoin('client_departments', 'job_requests.client_department_id', '=', 'client_departments.id')
            ->select([
                  'forklifts.id as forklift_id',
                  'clients.id as client_id',
                  'inspection_reports.id  as report_id',
                  'inspection_reports.publish as publish',
                  DB::raw("CONCAT(job_requests.code,'/',forklifts.code) as report_code"),
                  'job_requests.deploc as deploc',
                'client_departments.name as client_department',
                'forklifts.lfr_12 as id_number',
                  'forklifts.lfr_10 as desc',
                  DB::raw("clients.name as client"),
              ]);
        $this->applyPreferredInspectionFamilyRowConstraint($data, 'forklifts', Forklift::class);

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
                  if(Auth::user()->can('view', Forklift::find($row->forklift_id)))
                  {
                          $report_code .= "<a href=".route('forklift.show', $row->forklift_id).">";
                  }

                  $report_code .= $row->report_code;

                  if(Auth::user()->can('view', Forklift::find($row->forklift_id)))
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
                if (Auth::user()->can('create', Forklift::class))
                {
                        $btn .= '<button data-id="'.$row->report_id.'" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                }

                if ($row->publish != NULL)
                {
                        if (Storage::disk('public')->exists('pdf/inspection/lifting/forklift/'.$row->report_code.'.pdf'))
                        {
                                $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="'.Storage::url('pdf/inspection/lifting/forklift/'.$row->report_code.'.pdf').'">Download PDF</a>';
                        }
                        else
                        {
                                $btn .= '<a class="btn btn-dark mr-1" href="'.route('forklift.show', $row->forklift_id).'">Open Report</a>';
                        }

                        if (Auth::user()->can('update', Forklift::find($row->forklift_id)))
                        {
                                // $btn .= '<a href="'.route('forklift.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
    $btn .= '<div class="btn-group mr-1">
                          <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="la la-pencil"></i></button>
                          <div class="dropdown-menu">
                              <a href="'.route('forklift.edit', $row->forklift_id).'" class="dropdown-item">Edit 1st Page</a>
                              <div class="dropdown-divider"></div>';
                              if (Forklift::find($row->forklift_id)->forklift2)
                                                                {
                                    $btn .= '<a href="'.route('forklift2_edit.forklift', $row->forklift_id).'" class="dropdown-item">Edit 2nd Page</a>';
                                                                }
                                                                else
                                                                {
                                                                        $btn .= '<a href="'.route('forklift2_create.forklift', $row->forklift_id).'" class="dropdown-item">Create 2nd Page</a>';
                                                                }
                $btn .= '   </div>
                        </div>';
}
                }
                else
                {
                        $btn .= $this->buildInspectionUnpublishedMultiPageActionButtons(
                            Forklift::class,
                            (int) ($row->forklift_id),
                            'forklift.show',
                            'publish.forklift',
                            'forklift.edit',
                            'forklift2',
                            'forklift2_create.forklift',
                            'forklift2_edit.forklift'
                        );
                }


                if (Auth::user()->can('delete', Forklift::find($row->forklift_id)))
                {
                        $btn .= '<button type="button" data-id="'.$row->forklift_id.'" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
                }
                return $btn;
    })

							 	->filterColumn('report_code', function($query, $keyword){
                  $query->whereRaw("CONCAT(job_requests.code,'/',forklifts.code) like ?", ["%{$keyword}%"]);
							 		})
						 	->filterColumn('id_number', function($query, $keyword){
								 	$query->whereRaw("forklifts.lfr_12 like ?", ["%{$keyword}%"]);
						 		})
						 	->filterColumn('desc', function($query, $keyword){
								 	$query->whereRaw("forklifts.lfr_10 like ?", ["%{$keyword}%"]);
						 		})

              // ->filterColumn('code', function($query, $keyword){
              //       $query->whereHas('job_request', function($row) use (&$keyword){
              //           $row->where("code", "like", ["%{$keyword}%"]);
              //       });
              //   })
              ->filterColumn('client', function($query, $keyword){
                    $query->whereHas('job_request', function($row) use (&$keyword){
                        $row->whereHas('client', function($row1) use (&$keyword){
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
                        $codes = $this->getUploadedCodes('/lifting/forklift');
                        switch ($keyword) {
                            case 'publish':
                                $query->whereRaw("inspection_reports.publish IS NULL");
                                break;
                            case 'download':
                                $query->whereRaw("CONCAT(job_requests.code,'/',forklifts.code) IN ($codes)");
                                break;
                            case 'upload':
                                $query->whereRaw("inspection_reports.publish IS NOT NULL AND CONCAT(job_requests.code,'/',forklifts.code) NOT IN ($codes)");
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
          $jobrequests = JobRequest::select('id','code')->orderBy('id', 'desc')->get();
          return view('layouts.inspection.lifting.forklift.add', [
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

      $path = $request->file('lfr_34');
      $path_crypt = NULL;
      if($path != NULL)
      {
          $path_crypt = Crypt::encryptString($path->getClientOriginalName());
          $store = $path->storeAs(
          'public/camera/inspection/lifting/forklifts',
          $path_crypt
          );
      }

      $job_request = JobRequest::find($request->lcr_1);
      $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;

      $store = Forklift::create([
        'job_request_id' => $request->lcr_1,
        'lfr_2' => $request->lfr_2,
        'code' => $code,
        'lfr_6' => $request->lcr_6,
        'lfr_7' => $request->lcr_7,
        'lfr_8' => $request->lfr_8,
        'lfr_10' => $request->lfr_10,
        'lfr_11' => $request->lfr_11,
        'lfr_12' => $request->lfr_12,
        'lfr_13' => $request->lfr_13,
        'lfr_14' => $request->lfr_14,
        'lfr_15' => $request->lfr_15,
        'lfr_16' => $request->lfr_16,
        'lfr_17' => $request->lfr_17,
        'lfr_18' => $request->lfr_18,
        'lfr_19' => $request->lfr_19,
        'lfr_20' => $request->lfr_20,
        'lfr_21' => $request->lfr_21,
        'lfr_22' => $request->lfr_22,
        'lfr_23' => $request->lfr_23,
        'lfr_24' => $request->lfr_24,
        'lfr_25' => $request->lfr_25,
        'lfr_26' => $request->lfr_26,
        'lfr_27' => $request->lfr_27,
        'lfr_28' => $request->lfr_28,
        'lfr_29' => $request->lfr_29,
        'lfr_30' => $request->lfr_30,
        'lfr_31' => $request->lfr_31,
        'lfr_32' => $request->lfr_32,
        'lfr_33' => $request->lfr_33,
        'lfr_34' => $path_crypt,
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
          // Notification::send(User::all(), new InspectionReport(str_pad($store->code+1, 3,'0',STR_PAD_LEFT), $request->lfr_1));
          return response()->json([
              'last_id' => $store->id,
              'success' => $this->action_message(0, $this->page_name)
          ]);
      }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Forklift  $forklift
     * @return \Illuminate\Http\Response
     */
    public function show(Forklift $forklift)
    {
      $have_edit = $this->check_if_report_edit(Forklift::class, $forklift);
		  $forklift->report = $have_edit->default;
      $model = $forklift;
      $revisionDisplay = $this->resolveInspectionRevisionDisplayMeta(Forklift::class, $forklift);

      return view('layouts.inspection.lifting.forklift.show', [
        'page_name' => 'Through Examination Certificate Of Wheel Loader / Forklift Truck',
        'page_text' => 'This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998',
        'forklift' => $forklift,
        'model' => $forklift,
        'code' => $forklift->job_request->code.'/'.$forklift->code,
        'forklift2' => $forklift->forklift2,
        'report_created_at' => $forklift->created_at->format('d/m/Y'),
        'pdfurl' => 'storage/pdf/inspection/lifting/forklift/'.$forklift->job_request->code.'/'.$forklift->code.'.pdf',
        'imageurl' => $forklift->job_request->code.'/'.$forklift->code,
        'folder' => 'pdf/inspection/lifting/forklift',
        'hasPermission' => Auth::user()->hasPermission('forklift', 'approve'),
        'iso_number' => 'Form # RSE-RF-03 - ISSUE 05 / Jan 2022',
        'page_number' =>  $forklift->forklift2 ? '1 of 2': '1 of 1',
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

    public function publish(Forklift $forklift)
    {
        $this->authorize('create', Forklift::class);
        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
  			return view('layouts.inspection.lifting.forklift.publish', [
  				'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
  				'forklift' => $forklift,
  				'jobrequests' => $jobrequests
  			]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Forklift  $forklift
     * @return \Illuminate\Http\Response
     */
    public function edit(Forklift $forklift)
    {
        $jobrequests = JobRequest::select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.lifting.forklift.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'forklift' => $forklift,
            // 'jobrequests' => $jobrequests
          ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Forklift  $forklift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Forklift $forklift)
    {
      $isApprovedRevision = $this->shouldForkApprovedInspectionRevision($forklift);
      $code = $this->resolveInspectionSubmittedCode($request, $forklift);

      $path = $request->file('lfr_34');
      $path_crypt = NULL;
      if($path != NULL){
        if($request->publish != 'yes'){
          Storage::disk('public')->delete('camera/inspection/lifting/forklifts/'.$forklift->lfr_34);
        }
        $path_crypt = Crypt::encryptString($path->getClientOriginalName());
        $store = $path->storeAs(
        'public/camera/inspection/lifting/forklifts',
        $path_crypt
        );
      }else{
        if($request->imagedata == '' && $request->publish != 'yes'){
          Storage::disk('public')->delete('camera/inspection/lifting/forklifts/'.$forklift->lfr_34);
        }
        $path_crypt = $request->imagedata;
      }

      $data = [
        'job_request_id' => $request->lcr_1,
        'lfr_2' => $request->lfr_2,
        'code' => $code,
        'lfr_6' => $request->lcr_6,
        'lfr_7' => $request->lcr_7,
        'lfr_8' => $request->lfr_8,
        'lfr_10' => $request->lfr_10,
        'lfr_11' => $request->lfr_11,
        'lfr_12' => $request->lfr_12,
        'lfr_13' => $request->lfr_13,
        'lfr_14' => $request->lfr_14,
        'lfr_15' => $request->lfr_15,
        'lfr_16' => $request->lfr_16,
        'lfr_17' => $request->lfr_17,
        'lfr_18' => $request->lfr_18,
        'lfr_19' => $request->lfr_19,
        'lfr_20' => $request->lfr_20,
        'lfr_21' => $request->lfr_21,
        'lfr_22' => $request->lfr_22,
        'lfr_23' => $request->lfr_23,
        'lfr_24' => $request->lfr_24,
        'lfr_25' => $request->lfr_25,
        'lfr_26' => $request->lfr_26,
        'lfr_27' => $request->lfr_27,
        'lfr_28' => $request->lfr_28,
        'lfr_29' => $request->lfr_29,
        'lfr_30' => $request->lfr_30,
        'lfr_31' => $request->lfr_31,
        'lfr_32' => $request->lfr_32,
        'lfr_33' => $request->lfr_33,
        'lfr_34' => $path_crypt,
        'sync' => 0,
      ];

      $user_approved = NULL;

      if($isApprovedRevision)
      {
        $update = $forklift->create($data);
        $update->forklift2()->save($forklift->forklift2);
        $report_id = $update->id;
      }
      else
      {
        $update = $forklift->update($data);
        $report_id = $forklift->id;
        $user_approved = $forklift->report->user_id_approved;
      }

      if($update)
      {
        $user = Auth::id();
        if($request->publish == 'yes'){
          $user = NULL;
          $last = InspectionReport::where('job_request_id', $request->lfr_1)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
          // Notification::send(User::all(), new InspectionReport(str_pad($last->code+1, 3,'0',STR_PAD_LEFT), $request->lfr_1));
        }

        $this->persistInspectionReportState($forklift, [
          'code' => $code,
          'status' => 1,
          'publish' => $request->publish == 'yes' ? 1 : $forklift->report->publish,
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
     * @param  \App\Models\Forklift  $forklift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Forklift $forklift)
    {

      $remove  = $this->check_edited_reports_for_delete($forklift);

      if ($remove)
      {
          $pdf = 'inspection/lifting/forklift/'.$forklift->job_request->code.'/'.$forklift->code.'.pdf';
          $snap = 'images/inspection/lifting/forklift/'.$forklift->job_request->code.'/'.$forklift->code;
          $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
          return response()->json([
              'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
          ]);
      }
    }
}
