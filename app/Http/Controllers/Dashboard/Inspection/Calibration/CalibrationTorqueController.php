<?php

namespace App\Http\Controllers\Dashboard\Inspection\Calibration;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\Inspection\Calibration\CalibrationTorque as Calibration;
use App\Models\Persons\Client;
use App\Models\Persons\Supplier;
use App\Models\WorkFlow\JobRequest;
use Illuminate\Http\Request;

// Other
use DB, DataTables, Storage, Auth, Crypt;
use function Symfony\Component\String\length;

class CalibrationTorqueController extends Controller
{
    public $page_name = 'Calibration Certificate (Torque) Report';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = Calibration::count();
        return view('layouts.inspection.calibration.calibrationTorque.index', ['page_name' => $this->page_name('All', $this->page_name), 'reports' => $reports]);
    }

    public function getDataForDataTable(Request $request)
    {
        $data = Calibration::query()
				->has('report')
				->withAggregate('job_request','code')
				->orderBy('job_request_code', 'Desc');

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
                if ($row->job_request->client) {
                    if (Auth::user()->can('view', Client::find($row->job_request->client->id)) ) {
                        $report_code .= "<a href=" . route('client.show', $row->job_request->client->id) . ">";
                    }

                    $report_code .= $row->job_request->client->name;

                    if (Auth::user()->can('view', Client::find($row->job_request->client->id)) ) {
                        $report_code .= "</a>";
                    }
                } else {
                    if (Auth::user()->can('view', Supplier::find($row->job_request->supplier->id)) ) {
                        $report_code .= "<a href=" . route('client.show', $row->job_request->supplier->id) . ">";
                    }

                    $report_code .= $row->job_request->supplier->name;

                    if (Auth::user()->can('view', Supplier::find($row->job_request->supplier->id)) ) {
                        $report_code .= "</a>";
                    }

                }
                return $report_code;
            })
            ->addColumn('code', function ($row) {
                $report_code = "";
                if (Auth::user()->can('view', Calibration::find($row->id))  ) {
                    $report_code .= "<a href=" . route('calibrationTorque.show', $row->id) . ">";
                }

                $report_code .= $row->job_request->code . '/' . $row->code;

                if (Auth::user()->can('view', Calibration::find($row->id))  ) {
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
                if (Auth::user()->can('create', Calibration::class)   ) {
                    $btn .= '<button data-id="' . $row->report->id . '" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                }

                if ($row->report->publish != NULL) {
                    if (Storage::disk('public')->exists('pdf/inspection/calibration/calibrationTorque/' . $row->job_request->code . '/' . $row->code . '.pdf')) {
                        $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . Storage::url('pdf/inspection/calibration/calibrationTorque/' . $row->job_request->code . '/' . $row->code . '.pdf') . '">Download PDF</a>';
                    } else {
                        $btn .= '<a class="btn btn-dark mr-1" href="' . route('calibrationTorque.show', $row->id) . '">Open Report</a>';
                    }

                    if (Auth::user()->can('update', Calibration::find($row->id))  ) {
                        $btn .= '<a href="' . route('calibrationTorque.edit', $row->id) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }
                } else {
                    $btn .= $this->buildInspectionUnpublishedActionButtons($row, Calibration::class, (int) ($row->id), 'calibrationTorque.show', 'publish.calibrationTorque', 'calibrationTorque.edit');
                }


                if (Auth::user()->can('delete', Calibration::find($row->id))  ) {
                    $btn .= '<button type="button" data-id="' . $row->id . '" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
                }
                return $btn;
            })
            ->filterColumn('code', function ($query, $keyword) {
                $query->whereRaw("code like ?", ["%{$keyword}%"])
                    ->orWhereHas('job_request', function ($row) use (&$keyword) {
                        $row->where("code", "like", ["%{$keyword}%"]);
                    });
            })
            ->filterColumn('deploc', function ($query, $keyword) {
                $query->whereHas('job_request', function ($row) use (&$keyword) {
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
                $codes = $this->getUploadedCodes('/calibration/calibrationTorque', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->where('publish', null);
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', calibration_torques.code)"), $codes);
                        });
                        break;
                    case 'upload':
                        $query->whereHas('report', function ($q) {
                            $q->whereNot('publish', null);
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', calibration_torques.code)"), $codes);
                        });

                        break;
                }
            })
			->filter(function ($query) use ($request) {
				$this->applyInspectionGlobalSearch($query, $request->input('search.value'));
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
        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();

        return view('layouts.inspection.calibration.calibrationTorque.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'jobrequests' => $jobrequests,
            'inspection_data_template' => [
                array_combine(array_map(function ($i) {
                    return "input_$i";
                }, range(1, 37)), array_fill(0, 37, ''))
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $created = Calibration::create($data);
        if ($created) {
            $created->report()->create([
                'job_request_id' => $request->job_request_id,
                'code' => $data['code'],
                'status' => 1,
                'publish' => null,
                'sync' => 1,
                'user_id' => Auth::id(),
            ]);
            return response()->redirectToRoute('calibrationTorque.index');
        }
        return response()->json([$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inspection\Calibration\Calibration calibration
     * @return \Illuminate\Http\Response
     */
    public function show(Calibration $calibrationTorque)
    {
		$have_edit = $this->check_if_report_edit(Calibration::class, $calibrationTorque);
        $calibrationTorque->report = $have_edit->default;
        $model = $calibrationTorque;
        $footerData = [
            'formNo' => 'RS-RF-F18',
            'issueNo' => '04',
            'issueDate' => '1-Mar-2024',
            'revisionNo'=> '00',
            'revisionDate' => '1-Mar-2024',
            'pageNo' => '1 of 1',
        ];

        return view('layouts.inspection.calibration.calibrationTorque.show', [
            'page_name' => '', //$this->page_name,
            'custom_page_name' => $this->page_name,
            'page_text' => '',
            'model' => $calibrationTorque,
            'code' => $calibrationTorque->job_request->code . '/' . $calibrationTorque->code,
            'report_created_at' => $calibrationTorque->created_at->format('d/m/Y'),
            'footerData'=> $footerData,
            'pdfurl' => 'storage/pdf/inspection/calibration/calibrationTorque/' . $calibrationTorque->job_request->code . '/' . $calibrationTorque->code . '.pdf',
			'hasPermission' => Auth::user()->hasPermission('calibrationtorque', 'approve'),
            'imageurl' => $calibrationTorque->job_request->code . '/' . $calibrationTorque->code,
            'folder' => 'pdf/inspection/calibration/calibrationTorque',
            'iso_number' => 'Form # RSE-RF-07 - ISSUE 07 / Jul 2023',
            'page_number' => '1 of 1',
            'have_edit' => $have_edit->edited,
            'user_id_approved' => data_get($model, 'report.user_id_approved'),
            'for_approve_url' => data_get($model, 'report.id'),
            'esign' => data_get($model, 'report.user.employee.esign'),
            'person_make_report' => data_get($model, 'report.user.employee.name'),
            'person_make_report_desc' => data_get($model, 'report.user.employee.desc'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Calibration $calibrationTorque
     * @return \Illuminate\Http\Response
     */
    public function edit(Calibration $calibrationTorque)
    {
        $jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();

		return view('layouts.inspection.calibration.calibrationTorque.edit', [
            'model' => $calibrationTorque,
            'page_name' => $this->page_name(1, $this->page_name),
            'jobrequests' => $jobrequests,
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Calibration $calibrationTorque
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calibration $calibrationTorque)
    {
        $data = $request->all();
		$inspectionData = $data['inspection_data'] ?? [];
		$oldInspectionData = $calibrationTorque->inspection_data;
		// Handle photo file uploads
		foreach ($inspectionData as $key => $item) {
			if (isset($item['photo']) && $request->hasFile("inspection_data.{$key}.photo")) {
				$item['photo'] = $request->file("inspection_data.{$key}.photo")->store('photos', 'public');
			} else if (isset($oldInspectionData[$key]['photo'])) {
				$item['photo'] = $oldInspectionData[$key]['photo'];
			}
			$inspectionData[$key] = $item;
		}
        $data['inspection_data'] = $inspectionData;

		if ($this->shouldForkApprovedInspectionRevision($calibrationTorque)) {
            $data['job_request_id'] = $calibrationTorque->job_request_id;
            $createdCert = $calibrationTorque->create($data);
            $reportUpdateData = [
                'reportable_id' => $createdCert->id,
                'user_id_edit' => Auth::id(),
                'user_id_approved' => null,
                'publish' => null,
            ];
        } else {
            $calibrationTorque->update($data);
            $reportUpdateData = [
                'user_id_edit' => Auth::id(),
            ];
        }

        $this->persistInspectionReportState($calibrationTorque, $reportUpdateData);
        return response()->redirectToRoute('calibrationTorque.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inspection\Calibration\Calibration $calibration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calibration $calibrationTorque)
    {
        $remove = $this->check_edited_reports_for_delete($calibrationTorque);

        if ($remove) {
            $pdf = 'inspection/calibration/calibrationTorque/' . $calibrationTorque->job_request->code . '/' . $calibrationTorque->code . '.pdf';
            $snap = 'images/inspection/calibration/calibrationTorque/' . $calibrationTorque->job_request->code . '/' . $calibrationTorque->code;
            $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
            return response()->json([
                'success' => $remove_snap_shots_pdf . $this->action_message(2, $this->page_name)
            ]);
        }
    }

    public function publish(Calibration $calibrationTorque)
    {
        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.calibration.calibrationTorque.publish', [
            'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
            'model' => $calibrationTorque,
            'jobrequests' => $jobrequests,

		]);
    }

    public function publishSubmit(Request $request, Calibration $calibrationTorque)
    {
        $data = $request->all();
        $job_request = JobRequest::find($request->job_request_id);
        $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;
        $data['code'] = $code;
        $calibrationTorque->update($data);
        $this->persistInspectionReportState($calibrationTorque, [
            'code' => $code,
            'status' => 1,
            'publish' => 1,
            'user_id_edit' => null,
        ]);
        return response()->redirectToRoute('calibrationTorque.index');
    }
}
