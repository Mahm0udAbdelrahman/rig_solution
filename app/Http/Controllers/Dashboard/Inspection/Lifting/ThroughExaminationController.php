<?php

namespace App\Http\Controllers\Dashboard\Inspection\Lifting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;

// Illuminate\Http
use Illuminate\Http\Request;

// Illuminate\Support
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

// App\Models
use App\Models\Inspection\Lifting\ThroughExamination;
use App\Models\Persons\Client;
use App\Models\Persons\Supplier;
use App\Models\User;
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\FileManager;
use App\Models\Inspection\InspectionReport;

// App\Notifications
use App\Notifications\InspectionReports;

// Other
use Auth, DB, DataTables;

class ThroughExaminationController extends Controller
{

    public $page_name = 'Through Examination Report';
	/**
	 * Make full authorize of model based on policy
	 *
	 */
    public function __construct()
    {
      	$this->middleware('auth');
      	$this->authorizeResource(ThroughExamination::class, 'throughExamination');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
				$throughexaminations = ThroughExamination::count();
				return view('layouts.inspection.lifting.through-examination.index', ['page_name' => $this->page_name('All', $this->page_name), 'throughexaminations' => $throughexaminations]);
    }

		public function getDataForDataTable()
		{
      $canViewThroughExamination = Auth::user()->hasPermission('throughexamination', 'show');
      $canUpdateThroughExamination = Auth::user()->hasPermission('throughexamination', 'edit');
      $canDeleteThroughExamination = Auth::user()->hasPermission('throughexamination', 'delete');
      $canViewClient = Auth::user()->hasPermission('client', 'show');
      $canViewSupplier = Auth::user()->hasPermission('supplier', 'show');

      $latestRows = ThroughExamination::query()
            ->selectRaw('MAX(through_examinations.id) as id')
            ->groupBy('through_examinations.job_request_id', 'through_examinations.code');

      $data = ThroughExamination::query()
            ->joinSub($latestRows, 'latest_through_examinations', function ($join) {
                  $join->on('through_examinations.id', '=', 'latest_through_examinations.id');
              })
            ->join('job_requests', 'through_examinations.job_request_id', '=', 'job_requests.id')
            ->join('inspection_reports', function ($join) {
                  $join->on('through_examinations.id', '=', 'inspection_reports.reportable_id')
                        ->where('inspection_reports.reportable_type', '=', 'App\Models\Inspection\Lifting\ThroughExamination');
              })
            ->leftJoin('clients', 'job_requests.client_id', '=', 'clients.id')
            ->leftJoin('suppliers', 'job_requests.supplier_id', '=', 'suppliers.id')
            ->leftJoin('client_departments', 'job_requests.client_department_id', '=', 'client_departments.id')
            ->leftJoin('file_managers as inspection_pdfs', function ($join) {
                  $join->on('inspection_pdfs.job_request_code', '=', 'job_requests.code')
                        ->on('inspection_pdfs.entity_code', '=', 'through_examinations.code')
                        ->where('inspection_pdfs.module', '=', 'inspection')
                        ->where('inspection_pdfs.category', '=', 'lifting/throughexamination')
                        ->where('inspection_pdfs.extension', '=', 'pdf')
                        ->where('inspection_pdfs.is_available', '=', 1);
              })
          ->select([
                'through_examinations.id',
                'through_examinations.id as through_examination_id',
                'through_examinations.job_request_id',
                'through_examinations.code as inspection_code',
                'clients.id as client_id',
                'suppliers.id as supplier_id',
                'inspection_reports.id as report_id',
                'inspection_reports.code as inspection_report_code',
                'inspection_reports.user_id_approved as user_id_approved',
                'inspection_reports.publish as publish',
                DB::raw("CONCAT(job_requests.code,'/',through_examinations.code) as report_code"),
                'job_requests.code as job_request_code',
                'job_requests.deploc as deploc',
                'client_departments.name as client_department',
                'through_examinations.lter_10',
                'inspection_pdfs.path as pdf_path',
                DB::raw("COALESCE(clients.name, suppliers.name) as client"),
            ]);

				$this->applyInspectionApprovalPresetFilter($data, request('smart_preset'));

				return  Datatables::eloquent($data)
->addColumn('id_number', function ($row) {
                    if(!empty($row->lter_10['pop1']))
                    {
                        return $row->lter_10['pop1'];
                    }
                })
              ->addColumn('desc', function ($row) {
                    if(!empty($row->lter_10['pop20']))
                    {
                        return $row->lter_10['pop20'];
                    }
                })
              ->addColumn('swl', function ($row) {
                    if(!empty($row->lter_10['pop4']))
                    {
                        return $row->lter_10['pop4'];
                    }
                })
              ->addColumn('client', function ($row) use ($canViewClient, $canViewSupplier) {
                  $label = e((string) $row->client);
                  if ($label === '') {
                      return '';
                  }

                  if (!empty($row->client_id) && $canViewClient) {
                      return '<a href="' . route('client.show', $row->client_id) . '">' . $label . '</a>';
                  }

                  if (!empty($row->supplier_id) && $canViewSupplier) {
                      return '<a href="' . route('supplier.show', $row->supplier_id) . '">' . $label . '</a>';
                  }

                  return $label;
              })
            ->addColumn('code', function ($row) use ($canViewThroughExamination) {
                $reportCode = e((string) $row->report_code);

                if ($canViewThroughExamination) {
                    return '<a href="' . route('throughExamination.show', $row->through_examination_id) . '">' . $reportCode . '</a>';
                }

                return $reportCode;

              })
              ->addColumn('action', function ($row) use ($canUpdateThroughExamination, $canDeleteThroughExamination) {
										$btn = "";
										if (Auth::user()->can('create', ThroughExamination::class))
										{
                        $btn .= '<button data-id="'.$row->report_id.'" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                    }
                      if ($row->publish != NULL)
                      {
                          if (!empty($row->pdf_path))
                          {
                              $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="'.Storage::url((string) $row->pdf_path).'">Download PDF</a>';
                          }
                          else
                          {
                              $btn .= '<a class="btn btn-dark mr-1" href="'.route('throughExamination.show', $row->through_examination_id).'">Open Report</a>';
                          }

                          if ($canUpdateThroughExamination)
                          {
                              $btn .= '<a href="'.route('throughExamination.edit', $row->through_examination_id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                          }
                      }
                      else
                      {
                          $btn .= $this->buildInspectionUnpublishedActionButtons($row, ThroughExamination::class, (int) ($row->through_examination_id), 'throughExamination.show', 'publish.throughExamination', 'throughExamination.edit');
                      }

										if ($canDeleteThroughExamination)
										{
												$btn .= '<button type="button" data-id="'.$row->through_examination_id.'" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
										}
										return $btn;
					    	})

						 	->filterColumn('id_number', function($query, $keyword){
								 	$query->whereRaw("lter_10 like ?", ["%{$keyword}%"]);
						 		})
						 	->filterColumn('desc', function($query, $keyword){
								 	$query->whereRaw("lter_10 like ?", ["%{$keyword}%"]);
						 		})
						 	->filterColumn('swl', function($query, $keyword){
								 	$query->whereRaw("lter_10 like ?", ["%{$keyword}%"]);
			 	        })
              ->filterColumn('code', function($query, $keyword){
                $query->whereRaw("CONCAT(job_requests.code,'/',through_examinations.code) like ?", ["%{$keyword}%"]);
                })
              ->filterColumn('client', function($query, $keyword){
                $query->where(function ($builder) use ($keyword) {
                    $builder->whereRaw("clients.name like ?", ["%{$keyword}%"])
                        ->orWhereRaw("suppliers.name like ?", ["%{$keyword}%"]);
                });
                })
                    ->filterColumn('deploc', function ($query, $keyword) {
                        $query->whereRaw("job_requests.deploc like ?", ["%{$keyword}%"]);
                    })
                    ->filterColumn('client_department', function ($query, $keyword) {
                        $query->whereRaw("client_departments.name like ?", ["%{$keyword}%"]);
                    })
                    ->filterColumn('action', function ($query, $keyword) {
                        switch ($keyword) {
                            case 'publish':
                                $query->whereNull('inspection_reports.publish');
                                break;
                            case 'download':
                                $query->whereNotNull('inspection_pdfs.path');
                                break;
                            case 'upload':
                                $query->whereNotNull('inspection_reports.publish');
                                $query->whereNull('inspection_pdfs.path');
                                break;
                        }
                    })

            ->filter(function ($query) {
                $this->applyInspectionGlobalSearch($query, request('search.value'));
            })
							->rawColumns(['code', 'client', 'action'])
              ->toJson();
		}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      	// $jobrequests = JobRequest::select('id','code')->orderBy('id', 'desc')->get();
      	// return view('layouts.inspection.lifting.through-examination.add', ['page_name' => 'Lifting Through Examination Report', 'jobrequests' => $jobrequests]);

        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.lifting.through-examination.add', [
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

      $path = $request->file('lter_36');
      $path_crypt = NULL;
      if($path != NULL){
        $path_crypt = Crypt::encryptString($path->getClientOriginalName());
        $store = $path->storeAs(
        'public/camera/inspection/lifting/throughexaminations',
        $path_crypt
        );
      }

      $job_request = JobRequest::find($request->lcr_1);
      $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;


      $store = ThroughExamination::create([
        'job_request_id' => $request->lcr_1,
        'lter_2' => $request->lter_2,
        'code' => $code,
        'lter_6' => $request->lcr_6,
        'lter_7' => $request->lcr_7,
        'lter_8' => $request->lter_8,
        // 'lter_9' => $request->lter_9,
        'lter_10' => json_decode($request->new),
        'lter_15' => $request->lter_15,
        'lter_16' => $request->lter_16,
        'lter_17' => $request->lter_17,
        'lter_18' => $request->lter_18,
        'lter_19' => $request->lter_19,
        'lter_20' => $request->lter_20,
        'lter_21' => $request->lter_21,
        'lter_22' => $request->lter_22,
        'lter_23' => $request->lter_23,
        'lter_24' => $request->lter_24,
        'lter_25' => $request->lter_25,
        'lter_26' => $request->lter_26,
        'lter_27' => $request->lter_27,
        'lter_28' => $request->lter_28,
        'lter_29' => $request->lter_29,
        'lter_30' => $request->lter_30,
        'lter_31' => $request->lter_31,
        'lter_32' => $request->lter_32,
        'lter_33' => $request->lter_33,
        'lter_34' => $request->lter_34,
        'lter_35' => $request->lter_35,
        'lter_36' => $path_crypt,
        'lter_37' => $request->lter_37,
        'lter_38' => $request->lter_38,
        'lter_39' => $request->lter_39,
        'lter_40' => $request->lter_40,
        'lter_41' => $request->lter_41,
        'lter_42' => $request->lter_42,
        'lter_43' => $request->lter_43,
        'lter_44' => $request->lter_44,
        'lter_45' => $request->lter_45,
        'lter_46' => $request->lter_46,
        'lter_47' => $request->lter_47,
        'lter_48' => $request->lter_48,
        'lter_49' => $request->lter_49,
        'lter_50' => $request->lter_50,
        'lter_51' => $request->lter_51,
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
          // Notification::send(User::all(), new InspectionReport(str_pad($store->code+1, 3,'0',STR_PAD_LEFT), $request->lter_1));
          return response()->json([
              'last_id' => $store->id,
              'success' => $this->action_message(0, $this->page_name)
          ]);
      }

      return response()->json([
          'message' => 'Unable to create Through Examination report.'
      ], 500);
    }

    public function publish(ThroughExamination $throughExamination)
    {
        // $this->authorize('create', ThroughExamination::class);
        // // $clients = Client::select('id','code','name')->get();
        // $jobrequests = JobRequest::select('id','code')->get();
        // return view('layouts.inspection.lifting.through-examination.publish', ['page_name' => "lifting through examination duplicate", 'throughExamination' => $throughExamination, 'jobrequests' => $jobrequests]);


        $this->authorize('create', ThroughExamination::class);
  			$jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
  			return view('layouts.inspection.lifting.through-examination.publish', [
  				'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
  				'throughExamination' => $throughExamination,
  				'jobrequests' => $jobrequests
  			]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ThroughExamination  $throughExamination
     * @return \Illuminate\Http\Response
     */
    public function show(ThroughExamination $throughExamination)
    {
      $have_edit = $this->check_if_report_edit(ThroughExamination::class, $throughExamination);
      $throughExamination->report = $have_edit->default;

      return view('layouts.inspection.lifting.through-examination.show', [
        'page_name' => 'Thorough Examination Certificate Of Lifting Equipment',
        'page_text'=>'This report complies with the requirements of the Lifting Operations and Lifting Equipment Regulations 1998',
        'throughExamination' => $throughExamination,
        'model' => $throughExamination,
        'code' => $throughExamination->job_request->code.'/'.$throughExamination->code,
        'report_created_at' => $throughExamination->created_at->format('d/m/Y'),
        'pdfurl' => 'storage/pdf/inspection/lifting/throughexamination/'.$throughExamination->job_request->code.'/'.$throughExamination->code.'.pdf',
        'imageurl' => $throughExamination->job_request->code.'/'.$throughExamination->code,
        'folder' => 'pdf/inspection/lifting/throughexamination',
        'hasPermission' => Auth::user()->hasPermission('throughexamination', 'approve'),
        'iso_number' => 'Form # RSE-RF-04 - ISSUE 05 / Jan 2022',
        'page_number' => '1 of 1',
        'have_edit' => $have_edit->edited,
        'user_id_approved' => data_get($throughExamination, 'report.user_id_approved'),
        'for_approve_url' => data_get($throughExamination, 'report.id'),
        'esign' => data_get($throughExamination, 'report.user.employee.esign'),
        'person_make_report' => data_get($throughExamination, 'report.user.employee.name'),
        'person_make_report_desc' => data_get($throughExamination, 'report.user.employee.desc'),
      ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ThroughExamination  $throughExamination
     * @return \Illuminate\Http\Response
     */
    public function edit(ThroughExamination $throughExamination)
    {
      // $jobrequests = JobRequest::select('id','code')->orderBy('id', 'desc')->get();
      // return view('layouts.inspection.lifting.through-examination.edit', ['page_name' => "Through Examination edit", 'throughExamination' => $throughExamination, 'jobrequests' => $jobrequests]);

      // $jobrequests = JobRequest::select('id','code')->orderBy('id', 'desc')->get();
      return view('layouts.inspection.lifting.through-examination.edit', [
          'page_name' => $this->page_name(1, $this->page_name),
          'throughExamination' => $throughExamination,
          // 'jobrequests' => $jobrequests
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ThroughExamination  $throughExamination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ThroughExamination $throughExamination)
    {
      $currentReport = $throughExamination->report;

      if (!$currentReport) {
        return response()->json([
            'message' => 'Unable to locate the current inspection report state.'
        ], 422);
      }

      $code = $this->resolveInspectionSubmittedCode($request, $throughExamination);

        $path = $request->file('lter_36');
        $path_crypt = NULL;
        if($path != NULL){
          if($request->publish != 'yes'){
            Storage::disk('public')->delete('camera/inspection/lifting/throughexaminations/'.$throughExamination->lter_36);
          }
          $path_crypt = Crypt::encryptString($path->getClientOriginalName());
          $store = $path->storeAs(
          'public/camera/inspection/lifting/throughexaminations',
          $path_crypt
          );
        }else{
          if($request->imagedata == '' && $request->publish != 'yes'){
            Storage::disk('public')->delete('camera/inspection/lifting/throughexaminations/'.$throughExamination->lter_36);
          }
          $path_crypt = $request->imagedata;
        }

        $data = [
          'job_request_id' => $request->lcr_1,
          'lter_2' => $request->lter_2,
          'code' => $code,
          'lter_6' => $request->lcr_6,
          'lter_7' => $request->lcr_7,
          'lter_8' => $request->lter_8,
          // 'lter_9' => $request->lter_9,
          'lter_10' => json_decode($request->new),
          'lter_15' => $request->lter_15,
          'lter_16' => $request->lter_16,
          'lter_17' => $request->lter_17,
          'lter_18' => $request->lter_18,
          'lter_19' => $request->lter_19,
          'lter_20' => $request->lter_20,
          'lter_21' => $request->lter_21,
          'lter_22' => $request->lter_22,
          'lter_23' => $request->lter_23,
          'lter_24' => $request->lter_24,
          'lter_25' => $request->lter_25,
          'lter_26' => $request->lter_26,
          'lter_27' => $request->lter_27,
          'lter_28' => $request->lter_28,
          'lter_29' => $request->lter_29,
          'lter_30' => $request->lter_30,
          'lter_31' => $request->lter_31,
          'lter_32' => $request->lter_32,
          'lter_33' => $request->lter_33,
          'lter_34' => $request->lter_34,
          'lter_35' => $request->lter_35,
          'lter_36' => $path_crypt,
          'lter_37' => $request->lter_37,
          'lter_38' => $request->lter_38,
          'lter_39' => $request->lter_39,
          'lter_40' => $request->lter_40,
          'lter_41' => $request->lter_41,
          'lter_42' => $request->lter_42,
          'lter_43' => $request->lter_43,
          'lter_44' => $request->lter_44,
          'lter_45' => $request->lter_45,
          'lter_46' => $request->lter_46,
          'lter_47' => $request->lter_47,
          'lter_48' => $request->lter_48,
          'lter_49' => $request->lter_49,
          'lter_50' => $request->lter_50,
          'lter_51' => $request->lter_51,
          'sync' => 0,
        ];

        $user_approved = null;

        if($this->shouldForkApprovedInspectionRevision($throughExamination))
        {
          $update = ThroughExamination::query()->create($data);
          $report_id = $update->id;
        }
        else
        {
          $update = $throughExamination->update($data);
          $report_id = $throughExamination->id;
          $user_approved = $currentReport->user_id_approved;
        }

        if($update)
        {
          $user = Auth::id();
          if($request->publish == 'yes'){
            $user = NULL;
            // Notification::send(User::all(), new InspectionReport(str_pad($last->code+1, 3,'0',STR_PAD_LEFT), $request->lter_1));
          }

          $reportPersisted = $this->persistInspectionReportState($throughExamination, [
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

          if ($request->publish == 'yes' && $this->shouldForkApprovedInspectionRevision($throughExamination)) {
            InspectionReport::query()
              ->whereKey($currentReport->id)
              ->update([
                'publish' => null,
                'user_id_approved' => null,
              ]);
          }

          return response()->json([
            'success' => $this->action_message(1, $this->page_name)
          ]);
        }

        return response()->json([
            'message' => 'Unable to update Through Examination report.'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ThroughExamination  $throughExamination
     * @return \Illuminate\Http\Response
     */
    public function destroy(ThroughExamination $throughExamination)
    {
      $remove  = $this->check_edited_reports_for_delete($throughExamination);

      if ($remove)
      {
          $pdf = 'inspection/lifting/throughexamination/'.$throughExamination->job_request->code.'/'.$throughExamination->code.'.pdf';
          $snap = 'images/inspection/lifting/throughexamination/'.$throughExamination->job_request->code.'/'.$throughExamination->code;
          $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
          return response()->json([
              'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
          ]);
      }
    }
}



