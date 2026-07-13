<?php

namespace App\Http\Controllers\Dashboard\Inspection\Tubular;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\GeneralInfo\Specification;
use App\Models\Inspection\Tubular\StabilizerInspection;
use App\Models\Persons\Client;
use App\Models\Persons\Supplier;
use App\Models\WorkFlow\JobRequest;
use Illuminate\Http\Request;

// Other
use DB, DataTables, Storage, Auth, Crypt;
use function Symfony\Component\String\length;

class StabilizerInspectionController extends Controller
{
    public $page_name = 'Stabilizer Inspection Report';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = StabilizerInspection::count();
        return view('layouts.inspection.tubular.stabilizerInspection.index', ['page_name' => $this->page_name('All', $this->page_name), 'reports' => $reports]);
    }

    public function getDataForDataTable(Request $request)
    {
        $data = StabilizerInspection::query()
            ->has('report')
            ->with([
                'job_request.client',
                'job_request.supplier',
                'job_request.clientDepartment',
                'report',
            ])
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
                $jobRequest = $row->job_request;
                if (!$jobRequest) {
                    return '';
                }

                $client = $jobRequest->client;
                if ($client) {
                    $label = e($client->name);
                    return Auth::user()->can('view', $client)
                        ? '<a href="' . route('client.show', $client->id) . '">' . $label . '</a>'
                        : $label;
                }

                $supplier = $jobRequest->supplier;
                if ($supplier) {
                    $label = e($supplier->name);
                    return Auth::user()->can('view', $supplier)
                        ? '<a href="' . route('supplier.show', $supplier->id) . '">' . $label . '</a>'
                        : $label;
                }

                return '';
            })
            ->addColumn('code', function ($row) {
                $jobRequestCode = optional($row->job_request)->code;
                $reportCode = trim(($jobRequestCode ? $jobRequestCode . '/' : '') . $row->code, '/');
                if ($reportCode === '') {
                    return '';
                }

                $reportCode = e($reportCode);

                return Auth::user()->can('view', $row)
                    ? '<a href="' . route('stabilizerInspection.show', $row->id) . '">' . $reportCode . '</a>'
                    : $reportCode;
            })
            ->addColumn('material_description', function ($row) {

                return $row->material_description;
            })
            ->addColumn('tool_number', function ($row) {

                return $row->tool_number;
            })
            ->addColumn('deploc', function ($row) {
                return (string) optional($row->job_request)->deploc;
            })
            ->addColumn('client_department', function ($row) {
                return optional(optional($row->job_request)->clientDepartment)->name ?: '';
            })
            ->addColumn('action', function ($row) {
                $btn = "";
                $report = $row->report;
                $jobRequestCode = optional($row->job_request)->code;

                if (Auth::user()->can('create', StabilizerInspection::class) && $report) {
                    $btn .= '<button data-id="' . $report->id . '" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                }

                if (!$report) {
                    return $btn;
                }

                if (!is_null($report->publish)) {
                    if ($jobRequestCode && Storage::disk('public')->exists('pdf/inspection/tubular/stabilizerinspection/' . $jobRequestCode . '/' . $row->code . '.pdf')) {
                        $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . Storage::url('pdf/inspection/tubular/stabilizerinspection/' . $jobRequestCode . '/' . $row->code . '.pdf') . '">Download PDF</a>';
                    } else {
                        $btn .= '<a class="btn btn-dark mr-1" href="' . route('stabilizerInspection.show', $row->id) . '">Open Report</a>';
                    }

                    if (Auth::user()->can('update', $row)) {
                        $btn .= '<a href="' . route('stabilizerInspection.edit', $row->id) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }
                } else {
                    $btn .= $this->buildInspectionUnpublishedActionButtons($row, StabilizerInspection::class, (int) ($row->id), 'stabilizerInspection.show', 'publish.stabilizerInspection', 'stabilizerInspection.edit');
                }


                if (Auth::user()->can('delete', $row)) {
                    $btn .= '<button type="button" data-id="' . $row->id . '" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
                }
                return $btn;
            })
            ->filterColumn('acceptance', function ($query, $keyword) {
                $query->whereRaw("acceptance like ?", ["%{$keyword}%"]);
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
                    $row->where(function ($jobRequestQuery) use (&$keyword) {
                        $jobRequestQuery->whereHas('client', function ($row1) use (&$keyword) {
                            $row1->where("name", "like", ["%{$keyword}%"]);
                        })->orWhereHas('supplier', function ($row1) use (&$keyword) {
                            $row1->where("name", "like", ["%{$keyword}%"]);
                        });
                    });
                });
            })
            ->filterColumn('tool_number', function ($query, $keyword) {
                $query->where('tool_number', "like", ["%{$keyword}%"]);
            })
            ->filterColumn('material_description', function ($query, $keyword) {
                $query->where('material_description', "like", ["%{$keyword}%"]);
            })
            ->filterColumn('action', function ($query, $keyword) {
                $codes = $this->getUploadedCodes('/tubular/stabilizerinspection', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->whereNull('publish');
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', stabilizer_inspections.code)"), $codes);
                        });
                        break;
                    case 'upload':
                        $query->whereHas('report', function ($q) {
                            $q->whereNotNull('publish');
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', stabilizer_inspections.code)"), $codes);
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

        return view('layouts.inspection.tubular.stabilizerInspection.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'jobrequests' => $jobrequests,
            'specificationOptions' => $specifications,
            'inspectionMethods' => StabilizerInspection::$INSPECTION_METHOD,
            'equipments' => StabilizerInspection::$EQUIPMENTS,
            'pipeTypeOptions' => StabilizerInspection::$PIPE_TYPE_OPTIONS,
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
        $created = StabilizerInspection::create($data);
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
            return response()->redirectToRoute('stabilizerInspection.index');
        }
        return response()->json([$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inspection\Tubular\StabilizerInspection stabilizerInspection
     * @return \Illuminate\Http\Response
     */
    public function show(StabilizerInspection $stabilizerInspection)
    {
//dd($stabilizerInspection->dimensions_data['input_a']);
        $have_edit = $this->check_if_report_edit(StabilizerInspection::class, $stabilizerInspection);
        $stabilizerInspection->report = $have_edit->default;
        $model = $stabilizerInspection;
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
        $oldArray = $stabilizerInspection->inspection_data ? $stabilizerInspection->inspection_data : [];
        $newArray = (count($oldArray) > 20) ? array_slice($oldArray, 0, 20) : $oldArray;
        for ($i = count($newArray); $i < 20; $i++) {
            $newArray[] = array_combine(array_map(function ($i) {
                return "input_$i";
            }, range(1, 37)), array_fill(0, 37, ''));
        }
        $stabilizerInspection->inspection_data = $newArray;
        /** **************************************** */

        return view('layouts.inspection.tubular.stabilizerInspection.show', [
            'page_name' => $this->page_name,
            'page_text' => '',
            'model' => $stabilizerInspection,
            'model2' => false,
            'code' => $stabilizerInspection->job_request->code . '/' . $stabilizerInspection->code,
            'report_created_at' => $stabilizerInspection->created_at->format('d/m/Y'),
            'specificationOptions' => $specifications,
            'inspectionMethods' => StabilizerInspection::$INSPECTION_METHOD,
            'equipments' => StabilizerInspection::$EQUIPMENTS,
            'pipeTypeOptions' => StabilizerInspection::$PIPE_TYPE_OPTIONS,
            'footerData'=> $footerData,
            'pdfurl' => 'storage/pdf/inspection/tubular/stabilizerinspection/' . $stabilizerInspection->job_request->code . '/' . $stabilizerInspection->code . '.pdf',
            'hasPermission' => Auth::user()->can('approve', $stabilizerInspection),
            'imageurl' => $stabilizerInspection->job_request->code . '/' . $stabilizerInspection->code,
            'folder' => 'pdf/inspection/tubular/stabilizerinspection',
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
     * @param  \App\Models\Inspection\Tubular\StabilizerInspection $stabilizerInspection
     * @return \Illuminate\Http\Response
     */
    public function edit(StabilizerInspection $stabilizerInspection)
    {
        $jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();
        $specifications = Specification::getTubularSpecifications();
        return view('layouts.inspection.tubular.stabilizerInspection.edit', [
            'model' => $stabilizerInspection,
            'page_name' => $this->page_name(1, $this->page_name),
            'jobrequests' => $jobrequests,
            'specificationOptions' => $specifications,
            'inspectionMethods' => StabilizerInspection::$INSPECTION_METHOD,
            'equipments' => StabilizerInspection::$EQUIPMENTS,
            'pipeTypeOptions' => StabilizerInspection::$PIPE_TYPE_OPTIONS,
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
     * @param  \App\Models\Inspection\Tubular\StabilizerInspection $stabilizerInspection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StabilizerInspection $stabilizerInspection)
    {
        $data = $request->all();
        if ($this->shouldForkApprovedInspectionRevision($stabilizerInspection)) {
            $data['job_request_id'] = $stabilizerInspection->job_request_id;
            $createdCert = $stabilizerInspection->create($data);
            $reportUpdateData = [
                'reportable_id' => $createdCert->id,
                'user_id_edit' => Auth::id(),
                'user_id_approved' => null,
                'publish' => null,
            ];
        } else {
            $stabilizerInspection->update($data);
            $reportUpdateData = [
                'user_id_edit' => Auth::id(),
            ];
        }

        $this->persistInspectionReportState($stabilizerInspection, $reportUpdateData);
        return response()->redirectToRoute('stabilizerInspection.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inspection\Tubular\StabilizerInspection $stabilizerInspection
     * @return \Illuminate\Http\Response
     */
    public function destroy(StabilizerInspection $stabilizerInspection)
    {
        $remove = $this->check_edited_reports_for_delete($stabilizerInspection);

        if ($remove) {
            $pdf = 'inspection/tubular/stabilizerinspection/' . $stabilizerInspection->job_request->code . '/' . $stabilizerInspection->code . '.pdf';
            $snap = 'images/inspection/tubular/stabilizerinspection/' . $stabilizerInspection->job_request->code . '/' . $stabilizerInspection->code;
            $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
            return response()->json([
                'success' => $remove_snap_shots_pdf . $this->action_message(2, $this->page_name)
            ]);
        }
    }

    public function publish(StabilizerInspection $stabilizerInspection)
    {
        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        $specifications = Specification::getTubularSpecifications();
        return view('layouts.inspection.tubular.stabilizerInspection.publish', [
            'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
            'model' => $stabilizerInspection,
            'jobrequests' => $jobrequests,
            'specificationOptions' => $specifications,
            'inspectionMethods' => StabilizerInspection::$INSPECTION_METHOD,
            'equipments' => StabilizerInspection::$EQUIPMENTS,
            'pipeTypeOptions' => StabilizerInspection::$PIPE_TYPE_OPTIONS,
        ]);
    }

    public function publishSubmit(Request $request, StabilizerInspection $stabilizerInspection)
    {
        $data = $request->all();
        $job_request = JobRequest::find($request->job_request_id);
        $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;
        $data['code'] = $code;
        $stabilizerInspection->update($data);
        $this->persistInspectionReportState($stabilizerInspection, [
            'code' => $code,
            'status' => 1,
            'publish' => 1,
            'user_id_edit' => null,
        ]);
        return response()->redirectToRoute('stabilizerInspection.index');
    }
}
