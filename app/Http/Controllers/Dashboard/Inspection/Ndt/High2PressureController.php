<?php

namespace App\Http\Controllers\Dashboard\Inspection\Ndt;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Inspection\Ndt\High2Pressure;
use App\Models\GeneralInfo\Specification;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Models\Persons\Client;
use App\Models\User;

// Other
use DB, DataTables, Storage, Auth, Crypt;

class High2PressureController extends Controller
{

    public $page_name = 'General Ultra Sonic Wall Thickness Report';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(High2Pressure::class, 'high2Pressure');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $high2_pressures = High2Pressure::count();
      return view('layouts.inspection.ndt.high2pressure.index', ['page_name' => $this->page_name('All', $this->page_name), 'high2_pressures' => $high2_pressures]);
    }

    public function getDataForDataTable(Request $request)
		{
        $data = High2Pressure::query()
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
                  if(Auth::user()->can('view', High2Pressure::find($row->id)))
                  {
                      $report_code .= "<a href=".route('high2Pressure.show', $row->id).">";
                  }

                  $report_code .= $row->job_request->code.'/'.$row->code;

                  if(Auth::user()->can('view', High2Pressure::find($row->id)))
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
										if (Auth::user()->can('create', High2Pressure::class))
										{
												$btn .= '<button data-id="'.$row->report->id.'" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
										}

										if ($row->report->publish != NULL)
										{
												if (Storage::disk('public')->exists('pdf/inspection/ndt/high2pressure/'.$row->job_request->code.'/'.$row->report->code.'.pdf'))
												{
														$btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="'.Storage::url('pdf/inspection/ndt/high2pressure/'.$row->job_request->code.'/'.$row->report->code.'.pdf').'">Download PDF</a>';
												}
												else
												{
														$btn .= '<a class="btn btn-dark mr-1" href="'.route('high2Pressure.show', $row->id).'">Open Report</a>';
												}

												if (Auth::user()->can('update', High2Pressure::find($row->id)))
												{
														$btn .= '<a href="'.route('high2Pressure.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
												}
										}
										else
										{
												$btn .= $this->buildInspectionUnpublishedActionButtons($row, High2Pressure::class, (int) ($row->id), 'high2Pressure.show', 'publish.high2Pressure', 'high2Pressure.edit');
										}


										if (Auth::user()->can('delete', High2Pressure::find($row->id)))
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
                $codes = $this->getUploadedCodes('/ndt/high2pressure', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->where('publish', null);
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', high2_pressures.code)"), $codes);
                        });
                        break;
                    case 'upload':
//                        $query->whereRaw("inspection_reports.publish IS NOT NULL AND CONCAT(job_requests.code,'/',forklifts.code) NOT IN ($codes)");
                        $query->whereHas('report', function ($q) {
                            $q->whereNot('publish', null);
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', high2_pressures.code)"), $codes);
                        });

                        break;
                }
            })
						// keep global search useful for JCF/report while still covering the inner serial number
						->filter(function ($query) use ($request) {
							$this->applyInspectionGlobalSearch($query, $request->input('search.value'), 'nh2pr_32');
						})
            ->rawColumns(['code', 'client', 'action'])
            ->make('true');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		// $jobrequests = JobRequest::select('id','code')->orderBy('id', 'desc')->get();
		// return view('layouts.inspection.ndt.high2pressure.add', ['page_name' => 'Create Ultrasonic Wall Thickness Report 2', 'jobrequests' => $jobrequests, 'specifications' => $specifications]);
	  $specifications = Specification::getNdtSpecifications();
      $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
      return view('layouts.inspection.ndt.high2pressure.add', [
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

      $path = $request->file('nhpr_33');
      $path_crypt = NULL;
      if($path != NULL){
        $path_crypt = Crypt::encryptString($path->getClientOriginalName());
        $store = $path->storeAs(
        'public/camera/inspection/ndt/high2Pressure',
        $path_crypt
        );
      }

      $store = High2Pressure::create([
        'job_request_id' => $request->lcr_1,
        'nh2pr_2' => $request->nhpr_2,
        'code' => $code,
        // 'nhpr_4',
        // 'nhpr_5',
        'nh2pr_6' => $request->lcr_6,
        'nh2pr_7' => $request->lcr_7,
        // 'nhpr_8',
        'desc' => $request->nhpr_9,
        'edition' => $request->edition,
        'nh2pr_10' => $request->nhpr_10,
        'nh2pr_11' => json_encode($request->nhpr_11),
        'nh2pr_12' => $request->nhpr_12,
        'acceptance' => $request->nhpr_13,
        'nh2pr_14' => $request->nhpr_14,
        'nh2pr_15' => $request->nhpr_15,
        'nh2pr_16' => $request->nhpr_16,
        'nh2pr_17' => $request->nhpr_17,
        'nh2pr_18' => $request->nhpr_18,
        'nh2pr_19' => $request->nhpr_19,
        'nh2pr_20' => $request->nhpr_20,
        'nh2pr_21' => $request->nhpr_21,
        'nh2pr_22' => $request->nhpr_22,
        'nh2pr_23' => $request->nhpr_23,
        'nh2pr_24' => $request->nhpr_24,
        'nh2pr_25' => $request->nhpr_25,
        'nh2pr_26' => $request->nhpr_26,
        'nh2pr_27' => $request->nhpr_27,
        'nh2pr_28' => $request->nhpr_28,
        'nh2pr_29' => $request->nhpr_29,
        'nh2pr_30' => $request->nhpr_30,
        'nh2pr_31' => $path_crypt,
        'nh2pr_32' => json_encode($request['payments']),
        'nh2pr_33' => $request->nhpr_35,
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
     * @param  \App\Models\High2Pressure  $high2Pressure
     * @return \Illuminate\Http\Response
     */
    public function show(High2Pressure $high2Pressure)
    {
      $have_edit = $this->check_if_report_edit(High2Pressure::class, $high2Pressure);
	  $high2Pressure->report = $have_edit->default;
	  $specificationsList = Specification::getNdtSpecifications()->pluck('name', 'code');
	  $high2Pressure->specifications = json_decode($high2Pressure->nh2pr_11) ? json_decode($high2Pressure->nh2pr_11) : [];
    //  dd($high2Pressure); 
	  return view('layouts.inspection.ndt.high2pressure.show', [
        'page_name' => $this->page_name,
        'page_text'=>'',
        'high2Pressure' => $high2Pressure,
        'model' => $high2Pressure,
        'code' => $high2Pressure->job_request->code.'/'.$high2Pressure->code,
		'specificationOptions' => $specificationsList,
        'report_created_at' => $high2Pressure->created_at->format('d/m/Y'),
        'pdfurl' => 'storage/pdf/inspection/ndt/high2pressure/'.$high2Pressure->job_request->code.'/'.$high2Pressure->code.'.pdf',
        'hasPermission' => Auth::user()->hasPermission('high2pressure', 'approve'),
        'imageurl' => $high2Pressure->job_request->code.'/'.$high2Pressure->code,
        'folder' => 'pdf/inspection/ndt/high2pressure',
        'iso_number' => 'Form # RSE-RF-14 - ISSUE 03 / Jan 2022',
        'page_number' => '1 of 1',
        'have_edit' => $have_edit->edited,
        'user_id_approved' => data_get($high2Pressure, 'report.user_id_approved'),
        'for_approve_url' => data_get($high2Pressure, 'report.id'),
        'esign' => data_get($high2Pressure, 'report.user.employee.esign'),
        'person_make_report' => data_get($high2Pressure, 'report.user.employee.name'),
        'person_make_report_desc' => data_get($high2Pressure, 'report.user.employee.desc'),
      ]);
    }

    public function publish(High2Pressure $high2Pressure)
    {
        // die($high2Pressure);
				$this->authorize('create', High2Pressure::class);
				$specifications = Specification::getNdtSpecifications();
				$jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.high2pressure.publish', [
						'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
						'high2Pressure' => $high2Pressure,
						'jobrequests' => $jobrequests,
						'specifications' => $specifications
				]);
		}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\High2Pressure  $high2Pressure
     * @return \Illuminate\Http\Response
     */
    public function edit(High2Pressure $high2Pressure)
    {
      // $clients = Client::select('id','code','name')->get();
      // $jobrequests = JobRequest::select('id','code')->orderBy('id', 'desc')->get();
      // return view('layouts.inspection.ndt.high2pressure.edit', ['page_name' => "Ultrasonic Wall Thickness Report 2 edit", 'high2Pressure' => $high2Pressure, 'clients' => $clients, 'jobrequests' => $jobrequests]);
      $specifications = Specification::getNdtSpecifications();
      $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
      return view('layouts.inspection.ndt.high2pressure.edit', [
          'page_name' => $this->page_name(1, $this->page_name),
          'high2Pressure' => $high2Pressure,
          'jobrequests' => $jobrequests,
          'specifications' => $specifications
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\High2Pressure  $high2Pressure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, High2Pressure $high2Pressure)
    {
      $code = $this->resolveInspectionSubmittedCode($request, $high2Pressure);

      $path = $request->file('nhpr_33');
      $path_crypt = NULL;
      if ($path != NULL)
      {
          $path_crypt = Crypt::encryptString($path->getClientOriginalName());
          $store = $path->storeAs(
              'public/camera/inspection/ndt/high2Pressure',
              $path_crypt
          );
      }
      else
      {
          if($request->imagedata == '' && $request->publish != 'yes'){
              Storage::disk('public')->delete('camera/inspection/ndt/high2Pressure/'.$high2Pressure->nh2pr_33);
          }
          $path_crypt = $request->imagedata;
      }

      $data = [
        'job_request_id' => $request->lcr_1,
        'nh2pr_2' => $request->nhpr_2,
        'code' => $code,
        // 'nhpr_4',
        // 'nhpr_5',
        'nh2pr_6' => $request->lcr_6,
        'nh2pr_7' => $request->lcr_7,
        // 'nhpr_8',
        'desc' => $request->nhpr_9,
        'edition' => $request->edition,
        'nh2pr_10' => $request->nhpr_10,
        'nh2pr_11' => json_encode($request->nhpr_11),
        'nh2pr_12' => $request->nhpr_12,
        'acceptance' => $request->nhpr_13,
        'nh2pr_14' => $request->nhpr_14,
        'nh2pr_15' => $request->nhpr_15,
        'nh2pr_16' => $request->nhpr_16,
        'nh2pr_17' => $request->nhpr_17,
        'nh2pr_18' => $request->nhpr_18,
        'nh2pr_19' => $request->nhpr_19,
        'nh2pr_20' => $request->nhpr_20,
        'nh2pr_21' => $request->nhpr_21,
        'nh2pr_22' => $request->nhpr_22,
        'nh2pr_23' => $request->nhpr_23,
        'nh2pr_24' => $request->nhpr_24,
        'nh2pr_25' => $request->nhpr_25,
        'nh2pr_26' => $request->nhpr_26,
        'nh2pr_27' => $request->nhpr_27,
        'nh2pr_28' => $request->nhpr_28,
        'nh2pr_29' => $request->nhpr_29,
        'nh2pr_30' => $request->nhpr_30,
        'nh2pr_31' => $path_crypt,
        'nh2pr_32' => json_encode($request['payments']),
        'nh2pr_33' => $request->nhpr_35,
        'sync' => 0,
      ];

      $user_approved = NULL;

      if($this->shouldForkApprovedInspectionRevision($high2Pressure))
      {
        $update = $high2Pressure->create($data);
        $report_id = $update->id;
      }
      else
      {
        $update = $high2Pressure->update($data);
        $report_id = $high2Pressure->id;
        $user_approved = $high2Pressure->report->user_id_approved;
      }

      if($update)
      {
          $user = Auth::id();
          if ($request->publish == 'yes')
          {
              $user = NULL;
              $last = InspectionReport::where('job_request_id', $request->lcr_1)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
          }

          $this->persistInspectionReportState($high2Pressure, [
              'code' => $code,
              'status' => 1,
              'publish' => $request->publish == 'yes' ? 1 : $high2Pressure->report->publish,
              'user_id_approved' => $user_approved,
				      'reportable_id' => $report_id,
              'user_id_edit' => $user,
          ]);

          return response()->json([
              'success' => $this->action_message(1, $this->page_name)
          ]);
      }

        // $update_high2pressure = High2Pressure::where('id', $high2Pressure->id)->update([
        //   'job_request_id' => $request->nh2pr_1,
        //   'nh2pr_2' => $request->nh2pr_2,
        //   'nh2pr_3' => $request->nh2pr_3,
        //   'nh2pr_4' => $request->nh2pr_6,
        //   'nh2pr_5' => $request->nh2pr_7,
        //   'nh2pr_6' => $request->nh2pr_8,
        //   'nh2pr_7' => $request->nh2pr_9,
        //   'nh2pr_8' => $request->nh2pr_10,
        //   'nh2pr_9' => $request->nh2pr_11,
        //   'nh2pr_10' => $request->nh2pr_12,
        //   'nh2pr_11' => $request->nh2pr_13,
        //   'nh2pr_12' => $request->nh2pr_14,
        //   'nh2pr_13' => $request->nh2pr_15,
        //   'nh2pr_14' => $request->nh2pr_16,
        //   'nh2pr_15' => $request->nh2pr_17,
        //   'nh2pr_16' => $request->nh2pr_18,
        //   'nh2pr_17' => $request->nh2pr_19,
        //   'nh2pr_18' => $request->nh2pr_20,
        //   'nh2pr_19' => $request->nh2pr_21,
        //   'nh2pr_20' => $request->nh2pr_22,
        //   'nh2pr_21' => $request->nh2pr_23,
        //   'nh2pr_22' => $request->nh2pr_24,
        //   'nh2pr_23' => $request->nh2pr_25,
        //   'nh2pr_24' => $request->nh2pr_26,
        //   'nh2pr_25' => $request->nh2pr_27,
        //   'nh2pr_26' => $request->nh2pr_28,
        //   'nh2pr_27' => $request->nh2pr_29,
        //   'nh2pr_28' => $request->nh2pr_30,
        //   'nh2pr_29' => $request->nh2pr_32,
        //   'nh2pr_30' =>  $path_crypt,
        //   'nh2pr_31' => json_encode($request['payments']),
        // ]);
        // if($update_high2pressure)
        // {
        //     $high2Pressure->job_requests()->sync([$request->nh2pr_1 => ['status' => 2]]);
        //     $high2Pressure->users()->sync([$high2Pressure->users[0]->id => ['user_id_edit' => Auth::id()]]);
        //     return response()->json([
        //         'success' => 'high2Pressure Report updated successfully'
        //     ]);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\High2Pressure  $high2Pressure
     * @return \Illuminate\Http\Response
     */
    public function destroy(High2Pressure $high2Pressure)
    {
      $remove  = $this->check_edited_reports_for_delete($high2Pressure);

      if ($remove)
      {
          $pdf = 'inspection/ndt/high2pressure/'.$high2Pressure->job_request->code.'/'.$high2Pressure->code.'.pdf';
          $snap = 'images/inspection/ndt//high2pressure/'.$high2Pressure->job_request->code.'/'.$high2Pressure->code;
          $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
          return response()->json([
              'success' => $remove_snap_shots_pdf.$this->action_message(2, $this->page_name)
          ]);
      }
    }
}
