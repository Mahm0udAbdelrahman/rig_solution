<?php

namespace App\Http\Controllers\Dashboard\Inspection\Tubular;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\GeneralInfo\Specification;
use App\Models\Inspection\Tubular\DrillCollar;
use App\Models\Persons\Client;
use App\Models\Persons\Supplier;
use App\Models\WorkFlow\JobRequest;
use Illuminate\Http\Request;

// Other
use DB, DataTables, Storage, Auth, Crypt;
use function Symfony\Component\String\length;

class DrillCollarController extends Controller
{
    public $page_name = 'Drill Collar Inspection Report';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = DrillCollar::count();
        return view('layouts.inspection.tubular.drillCollar.index', ['page_name' => $this->page_name('All', $this->page_name), 'reports' => $reports]);
    }

    public function getDataForDataTable(Request $request)
    {
        $data = DrillCollar::query()
            ->has('report')
            ->whereRaw(
                'drill_collars.id = (
                    SELECT MAX(dc2.id)
                    FROM drill_collars dc2
                    WHERE dc2.job_request_id = drill_collars.job_request_id
                      AND dc2.code = drill_collars.code
                )'
            )
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
                if (Auth::user()->can('view', DrillCollar::find($row->id))  ) {
                    $report_code .= "<a href=" . route('drillCollar.show', $row->id) . ">";
                }

                $report_code .= $row->job_request->code . '/' . $row->code;

                if (Auth::user()->can('view', DrillCollar::find($row->id))  ) {
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
                if (Auth::user()->can('create', DrillCollar::class)   ) {
                    $btn .= '<button data-id="' . $row->report->id . '" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                }

                if ($row->report->publish != NULL) {
                    if (Storage::disk('public')->exists('pdf/inspection/tubular/drillcollar/' . $row->job_request->code . '/' . $row->code . '.pdf')) {
                        $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . Storage::url('pdf/inspection/tubular/drillcollar/' . $row->job_request->code . '/' . $row->code . '.pdf') . '">Download PDF</a>';
                    } else {
                        $btn .= '<a class="btn btn-dark mr-1" href="' . route('drillCollar.show', $row->id) . '">Open Report</a>';
                    }

                    if (Auth::user()->can('update', DrillCollar::find($row->id))  ) {
                        $btn .= '<a href="' . route('drillCollar.edit', $row->id) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }
                } else {
                    $btn .= $this->buildInspectionUnpublishedActionButtons($row, DrillCollar::class, (int) ($row->id), 'drillCollar.show', 'publish.drillCollar', 'drillCollar.edit');
                }


                if (Auth::user()->can('delete', DrillCollar::find($row->id))  ) {
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
                $codes = $this->getUploadedCodes('/tubular/drillcollar', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->where('publish', null);
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', drill_collars.code)"), $codes);
                        });
                        break;
                    case 'upload':
                        $query->whereHas('report', function ($q) {
                            $q->whereNot('publish', null);
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', drill_collars.code)"), $codes);
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
        $specifications = Specification::getTubularSpecifications();

        return view('layouts.inspection.tubular.drillCollar.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'jobrequests' => $jobrequests,
            'specificationOptions' => $specifications,
            'inspectionMethods' => DrillCollar::$INSPECTION_METHOD,
            'equipments' => DrillCollar::$EQUIPMENTS,
            'pipeTypeOptions' => DrillCollar::$PIPE_TYPE_OPTIONS,
            'pipeTypeStandards' => DrillCollar::$PIPE_TYPES_STANDARDS,
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
        $data  = $request->all();
        $created = DrillCollar::create($data);
        $code = $data['code'];
        if ($created) {
            $created->report()->create([
                'job_request_id' => $request->job_request_id,
                'code' => $code,
                'status' => 1,
                'publish' => null,
                'sync' => 1,
                'user_id' => Auth::id(),
            ]);
            return response()->redirectToRoute('drillCollar.index');
        }
        return response()->json([$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inspection\Tubular\DrillCollar drillCollar
     * @return \Illuminate\Http\Response
     */
    public function show(DrillCollar $drillCollar)
    {
        $have_edit = $this->check_if_report_edit(DrillCollar::class, $drillCollar);
        $drillCollar->report = $have_edit->default;
        $model = $drillCollar;
        $specifications = Specification::getTubularSpecifications()->pluck('name', 'code')->toArray();
        $footerData = [
            'formNo' => 'RS-RF-F18',
            'issueNo' => '04',
            'issueDate' => '1-Mar-2024',
            'revisionNo'=> '00',
            'revisionDate' => '1-Mar-2024',
            'pageNo' => '1 of 1',
        ];

        /** add empty rows to inspection data array & be sure to allow only 10 row on the inspection data*/
        $oldArray = $drillCollar->inspection_data ? $drillCollar->inspection_data : [];
        $newArray = (count($oldArray) > 10) ? array_slice($oldArray, 0, 10) : $oldArray;
        for ($i = count($newArray); $i < 10; $i++) {
            $newArray[] = array_combine(array_map(function ($i) {
                return "input_$i";
            }, range(1, 37)), array_fill(0, 37, ''));
        }
        $drillCollar->inspection_data = $newArray;
        /** **************************************** */

        return view('layouts.inspection.tubular.drillCollar.show', [
            'page_name' => $this->page_name,
            'page_text' => '',
            'model' => $drillCollar,
            'code' => $drillCollar->job_request->code . '/' . $drillCollar->code,
            'report_created_at' => $drillCollar->created_at->format('d/m/Y'),
            'specificationOptions' => $specifications,
            'inspectionMethods' => DrillCollar::$INSPECTION_METHOD,
            'equipments' => DrillCollar::$EQUIPMENTS,
            'pipeTypeOptions' => DrillCollar::$PIPE_TYPE_OPTIONS,
            'pipeTypeStandards' => DrillCollar::$PIPE_TYPES_STANDARDS,
            'footerData'=> $footerData,
            'pdfurl' => 'storage/pdf/inspection/tubular/drillcollar/' . $drillCollar->job_request->code . '/' . $drillCollar->code . '.pdf',
            'hasPermission' => Auth::user()->hasPermission('drillcollar', 'approve'),
            'imageurl' => $drillCollar->job_request->code . '/' . $drillCollar->code,
            'folder' => 'pdf/inspection/tubular/drillcollar',
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
     * @param  \App\Models\Inspection\Tubular\DrillCollar $drillCollar
     * @return \Illuminate\Http\Response
     */
    public function edit(DrillCollar $drillCollar)
    {
        $jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();
        $specifications = Specification::getTubularSpecifications();
        return view('layouts.inspection.tubular.drillCollar.edit', [
            'model' => $drillCollar,
            'page_name' => $this->page_name(1, $this->page_name),
            'jobrequests' => $jobrequests,
            'specificationOptions' => $specifications,
            'inspectionMethods' => DrillCollar::$INSPECTION_METHOD,
            'equipments' => DrillCollar::$EQUIPMENTS,
            'pipeTypeOptions' => DrillCollar::$PIPE_TYPE_OPTIONS,
            'pipeTypeStandards' => DrillCollar::$PIPE_TYPES_STANDARDS,
            'inspection_data_template' => [
                array_combine(array_map(function ($i) {
                    return "input_$i";
                }, range(1, 37)), array_fill(0, 37, ''))
            ],        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Inspection\Tubular\DrillCollar $drillCollar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DrillCollar $drillCollar)
    {
        $data = $request->all();
        if ($this->shouldForkApprovedInspectionRevision($drillCollar)) {
            $data['job_request_id'] = $drillCollar->job_request_id;
            $createdCert = $drillCollar->create($data);
            $reportUpdateData = [
                'reportable_id' => $createdCert->id,
                'user_id_edit' => Auth::id(),
                'user_id_approved' => null,
                'publish' => null,
            ];
        } else {
            $drillCollar->update($data);
            $reportUpdateData = [
                'user_id_edit' => Auth::id(),
            ];
        }

        $this->persistInspectionReportState($drillCollar, $reportUpdateData);
        return response()->redirectToRoute('drillCollar.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inspection\Tubular\DrillCollar $drillCollar
     * @return \Illuminate\Http\Response
     */
    public function destroy(DrillCollar $drillCollar)
    {
        $remove = $this->check_edited_reports_for_delete($drillCollar);

        if ($remove) {
            $pdf = 'inspection/tubular/drillcollar/' . $drillCollar->job_request->code . '/' . $drillCollar->code . '.pdf';
            $snap = 'images/inspection/tubular/drillcollar/' . $drillCollar->job_request->code . '/' . $drillCollar->code;
            $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
            return response()->json([
                'success' => $remove_snap_shots_pdf . $this->action_message(2, $this->page_name)
            ]);
        }
    }

    public function publish(DrillCollar $drillCollar)
    {
        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        $specifications = Specification::getTubularSpecifications();
        return view('layouts.inspection.tubular.drillCollar.publish', [
            'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
            'model' => $drillCollar,
            'jobrequests' => $jobrequests,
            'specificationOptions' => $specifications,
            'inspectionMethods' => DrillCollar::$INSPECTION_METHOD,
            'equipments' => DrillCollar::$EQUIPMENTS,
            'pipeTypeOptions' => DrillCollar::$PIPE_TYPE_OPTIONS,
            'pipeTypeStandards' => DrillCollar::$PIPE_TYPES_STANDARDS
        ]);
    }

    public function publishSubmit(Request $request, DrillCollar $drillCollar)
    {
        $data = $request->all();
        $job_request = JobRequest::find($request->job_request_id);
        $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;
        $data['code'] = $code;
        $drillCollar->update($data);
        $this->persistInspectionReportState($drillCollar, [
            'code' => $code,
            'status' => 1,
            'publish' => 1,
            'user_id_edit' => null,
        ]);
        return response()->redirectToRoute('drillCollar.index');
    }
}
