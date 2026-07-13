<?php

namespace App\Http\Controllers\Dashboard\Inspection\Tubular;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\GeneralInfo\Specification;
use App\Models\Inspection\Tubular\DrillPipe;
use App\Models\Persons\Client;
use App\Models\Persons\Supplier;
use App\Models\WorkFlow\JobRequest;
use Illuminate\Http\Request;

// Other
use DB, DataTables, Storage, Auth, Crypt;
use function Symfony\Component\String\length;

class DrillPipeController extends Controller
{
    public $page_name = 'Drill Pipe Inspection Report';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = DrillPipe::count();
        return view('layouts.inspection.tubular.drillPipe.index', ['page_name' => $this->page_name('All', $this->page_name), 'reports' => $reports]);
    }

    public function getDataForDataTable(Request $request)
    {
        $data = DrillPipe::query()
            ->has('report')
            ->whereRaw(
                'drill_pipes.id = (
                    SELECT MAX(dp2.id)
                    FROM drill_pipes dp2
                    WHERE dp2.job_request_id = drill_pipes.job_request_id
                      AND dp2.code = drill_pipes.code
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
                if (Auth::user()->can('view', DrillPipe::find($row->id))  ) {
                    $report_code .= "<a href=" . route('drillPipe.show', $row->id) . ">";
                }

                $report_code .= $row->job_request->code . '/' . $row->code;

                if (Auth::user()->can('view', DrillPipe::find($row->id))  ) {
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
                if (Auth::user()->can('create', DrillPipe::class)   ) {
                    $btn .= '<button data-id="' . $row->report->id . '" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                }

                if ($row->report->publish != NULL) {
                    if (Storage::disk('public')->exists('pdf/inspection/tubular/drillpipe/' . $row->job_request->code . '/' . $row->report->code . '.pdf')) {
                        $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . Storage::url('pdf/inspection/tubular/drillpipe/' . $row->job_request->code . '/' . $row->report->code . '.pdf') . '">Download PDF</a>';
                    } else {
                        $btn .= '<a class="btn btn-dark mr-1" href="' . route('drillPipe.show', $row->id) . '">Open Report</a>';
                    }

                    if (Auth::user()->can('update', DrillPipe::find($row->id))  ) {
                        $btn .= '<a href="' . route('drillPipe.edit', $row->id) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }
                } else {
                    $btn .= $this->buildInspectionUnpublishedActionButtons($row, DrillPipe::class, (int) ($row->id), 'drillPipe.show', 'publish.drillPipe', 'drillPipe.edit');
                }


                if (Auth::user()->can('delete', DrillPipe::find($row->id))  ) {
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
                $codes = $this->getUploadedCodes('/tubular/drillpipe', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->where('publish', null);
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', drill_pipes.code)"), $codes);
                        });
                        break;
                    case 'upload':
                        $query->whereHas('report', function ($q) {
                            $q->whereNot('publish', null);
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', drill_pipes.code)"), $codes);
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

        return view('layouts.inspection.tubular.drillPipe.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'jobrequests' => $jobrequests,
            'specificationOptions' => $specifications,
            'inspectionMethods' => DrillPipe::$INSPECTION_METHOD,
            'equipments' => DrillPipe::$EQUIPMENTS,
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
//        dd($data);
        $created = DrillPipe::create($data);
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
            return response()->redirectToRoute('drillPipe.index');
        }
        return response()->json([$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inspection\Tubular\DrillPipe drillPipe
     * @return \Illuminate\Http\Response
     */
    public function show(DrillPipe $drillPipe)
    {

        $have_edit = $this->check_if_report_edit(DrillPipe::class, $drillPipe);
        $drillPipe->report = $have_edit->default;
        $model = $drillPipe;
        $specifications = Specification::getTubularSpecifications()->pluck('name', 'code')->toArray();
        $footerData = [
            'formNo' => 'RS-RF-F18',
            'issueNo' => '04',
            'issueDate' => '1-Mar-2024',
            'revisionNo'=> '00',
            'revisionDate' => '1-Mar-2024',
            'pageNo' => '1 of 1',
        ];

        /** add empty rows to inspection data array & be sure to allow only 20 row on the inspection data*/
        $oldArray = $drillPipe->inspection_data ? $drillPipe->inspection_data : [];
        $newArray = (count($oldArray) > 20) ? array_slice($oldArray, 0, 20) : $oldArray;
        for ($i = count($newArray); $i < 20; $i++) {
            $newArray[] = array_combine(array_map(function ($i) {
                return "input_$i";
            }, range(1, 37)), array_fill(0, 37, ''));
        }
        $drillPipe->inspection_data = $newArray;
        /** **************************************** */

        return view('layouts.inspection.tubular.drillPipe.show', [
            'page_name' => $this->page_name,
            'page_text' => '',
            'model' => $drillPipe,
            'code' => $drillPipe->job_request->code . '/' . $drillPipe->code,
            'report_created_at' => $drillPipe->created_at->format('d/m/Y'),
            'specificationOptions' => $specifications,
            'inspectionMethods' => DrillPipe::$INSPECTION_METHOD,
            'equipments' => DrillPipe::$EQUIPMENTS,
            'footerData'=> $footerData,
            'pdfurl' => 'storage/pdf/inspection/tubular/drillpipe/' . $drillPipe->job_request->code . '/' . $drillPipe->code . '.pdf',
            'hasPermission' => Auth::user()->hasPermission('drillpipe', 'approve'),
            'imageurl' => $drillPipe->job_request->code . '/' . $drillPipe->code,
            'folder' => 'pdf/inspection/tubular/drillpipe',
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
     * @param  \App\Models\Inspection\Tubular\DrillPipe $drillPipe
     * @return \Illuminate\Http\Response
     */
    public function edit(DrillPipe $drillPipe)
    {
        $jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();
        $specifications = Specification::getTubularSpecifications();
        return view('layouts.inspection.tubular.drillPipe.edit', [
            'model' => $drillPipe,
            'page_name' => $this->page_name(1, $this->page_name),
            'jobrequests' => $jobrequests,
            'specificationOptions' => $specifications,
            'inspectionMethods' => DrillPipe::$INSPECTION_METHOD,
            'equipments' => DrillPipe::$EQUIPMENTS,
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
     * @param  \App\Models\Inspection\Tubular\DrillPipe $drillPipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DrillPipe $drillPipe)
    {
        $data = $request->all();
        if ($this->shouldForkApprovedInspectionRevision($drillPipe)) {
            $data['job_request_id'] = $drillPipe->job_request_id;
            $createdCert = $drillPipe->create($data);
            $reportUpdateData = [
                'reportable_id' => $createdCert->id,
                'user_id_edit' => Auth::id(),
                'user_id_approved' => null,
                'publish' => null,
            ];
        } else {
            $drillPipe->update($data);
            $reportUpdateData = [
                'user_id_edit' => Auth::id(),
            ];
        }

        $this->persistInspectionReportState($drillPipe, $reportUpdateData);
        return response()->redirectToRoute('drillPipe.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inspection\Tubular\DrillPipe $drillPipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(DrillPipe $drillPipe)
    {
        $remove = $this->check_edited_reports_for_delete($drillPipe);

        if ($remove) {
            $pdf = 'inspection/tubular/drillpipe/' . $drillPipe->job_request->code . '/' . $drillPipe->code . '.pdf';
            $snap = 'images/inspection/tubular/drillpipe/' . $drillPipe->job_request->code . '/' . $drillPipe->code;
            $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
            return response()->json([
                'success' => $remove_snap_shots_pdf . $this->action_message(2, $this->page_name)
            ]);
        }
    }

    public function publish(DrillPipe $drillPipe)
    {
        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        $specifications = DB::table('specifications')->select('id', 'name', 'code')->get();
        return view('layouts.inspection.tubular.drillPipe.publish', [
            'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
            'model' => $drillPipe,
            'jobrequests' => $jobrequests,
            'specificationOptions' => $specifications,
            'inspectionMethods' => DrillPipe::$INSPECTION_METHOD,
            'equipments' => DrillPipe::$EQUIPMENTS
        ]);
    }

    public function publishSubmit(Request $request, DrillPipe $drillPipe)
    {
        $data = $request->all();
        $job_request = JobRequest::find($request->job_request_id);
        $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;
        $data['code'] = $code;
        $drillPipe->update($data);
        $this->persistInspectionReportState($drillPipe, [
            'code' => $code,
            'status' => 1,
            'publish' => 1,
            'user_id_edit' => null,
        ]);
        return response()->redirectToRoute('drillPipe.index');
    }
}
