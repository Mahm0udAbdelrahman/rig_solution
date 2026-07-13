<?php

namespace App\Http\Controllers\Dashboard\Inspection\Tubular;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\GeneralInfo\Specification;
use App\Models\Inspection\Tubular\SubsDimensional;
use App\Models\Persons\Client;
use App\Models\Persons\Supplier;
use App\Models\WorkFlow\JobRequest;
use Illuminate\Http\Request;

// Other
use DB, DataTables, Storage, Auth, Crypt;
use function Symfony\Component\String\length;

class SubsDimensionalController extends Controller
{
    public $page_name = 'Subs Dimensional Inspection Report';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = SubsDimensional::count();
        return view('layouts.inspection.tubular.subsDimensional.index', ['page_name' => $this->page_name('All', $this->page_name), 'reports' => $reports]);
    }

    public function getDataForDataTable(Request $request)
    {
        $data = SubsDimensional::query()
            ->has('report')
            ->whereRaw(
                'subs_dimensionals.id = (
                    SELECT MAX(sd2.id)
                    FROM subs_dimensionals sd2
                    WHERE sd2.job_request_id = subs_dimensionals.job_request_id
                      AND sd2.code = subs_dimensionals.code
                )'
            )
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
                $reportCode = trim(($jobRequestCode ? $jobRequestCode.'/' : '').$row->code, '/');
                if ($reportCode === '') {
                    return '';
                }

                $reportCode = e($reportCode);

                return Auth::user()->can('view', $row)
                    ? '<a href="' . route('subsDimensional.show', $row->id) . '">' . $reportCode . '</a>'
                    : $reportCode;
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

                if (Auth::user()->can('create', SubsDimensional::class) && $report) {
                    $btn .= '<button data-id="' . $report->id . '" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                }

                if (!$report) {
                    return $btn;
                }

                if (!is_null($report->publish)) {
                    if ($jobRequestCode && Storage::disk('public')->exists('pdf/inspection/tubular/subsdimensional/' . $jobRequestCode . '/' . $row->code . '.pdf')) {
                        $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . Storage::url('pdf/inspection/tubular/subsdimensional/' . $jobRequestCode . '/' . $row->code . '.pdf') . '">Download PDF</a>';
                    } else {
                        $btn .= '<a class="btn btn-dark mr-1" href="' . route('subsDimensional.show', $row->id) . '">Open Report</a>';
                    }

                    if (Auth::user()->can('update', $row)) {
                        $btn .= '<a href="' . route('subsDimensional.edit', $row->id) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }
                } else {
                    $btn .= $this->buildInspectionUnpublishedActionButtons($row, SubsDimensional::class, (int) ($row->id), 'subsDimensional.show', 'publish.subsDimensional', 'subsDimensional.edit');
                }


                if (Auth::user()->can('delete', $row)) {
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
                    $row->where(function ($jobRequestQuery) use (&$keyword) {
                        $jobRequestQuery->whereHas('client', function ($row1) use (&$keyword) {
                            $row1->where("name", "like", ["%{$keyword}%"]);
                        })->orWhereHas('supplier', function ($row1) use (&$keyword) {
                            $row1->where("name", "like", ["%{$keyword}%"]);
                        });
                    });
                });
            })
            ->filterColumn('action', function ($query, $keyword) {
                $codes = $this->getUploadedCodes('/tubular/subsdimensional', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->where('publish', null);
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', subs_dimensionals.code)"), $codes);
                        });
                        break;
                    case 'upload':
                        $query->whereHas('report', function ($q) {
                            $q->whereNot('publish', null);
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', subs_dimensionals.code)"), $codes);
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

        return view('layouts.inspection.tubular.subsDimensional.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'jobrequests' => $jobrequests,
            'specificationOptions' => $specifications,
            'inspectionMethods' => SubsDimensional::$INSPECTION_METHOD,
            'equipments' => SubsDimensional::$EQUIPMENTS,
            'pipeTypeOptions' => SubsDimensional::$PIPE_TYPE_OPTIONS,
            'pipeTypeStandards' => SubsDimensional::$PIPE_TYPES_STANDARDS,
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
        $created = SubsDimensional::create($data);
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
            return response()->redirectToRoute('subsDimensional.index');
        }
        return response()->json([$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inspection\Tubular\SubsDimensional subsDimensional
     * @return \Illuminate\Http\Response
     */
    public function show(SubsDimensional $subsDimensional)
    {

        $have_edit = $this->check_if_report_edit(SubsDimensional::class, $subsDimensional);
        $subsDimensional->report = $have_edit->default;
        $model = $subsDimensional;
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
        $oldArray = $subsDimensional->inspection_data ? $subsDimensional->inspection_data : [];
        $newArray = (count($oldArray) > 10) ? array_slice($oldArray, 0, 10) : $oldArray;
        for ($i = count($newArray); $i < 10; $i++) {
            $newArray[] = array_combine(array_map(function ($i) {
                return "input_$i";
            }, range(1, 37)), array_fill(0, 37, ''));
        }
        $subsDimensional->inspection_data = $newArray;
        /** **************************************** */

        return view('layouts.inspection.tubular.subsDimensional.show', [
            'page_name' => $this->page_name,
            'page_text' => '',
            'model' => $subsDimensional,
            'code' => $subsDimensional->job_request->code . '/' . $subsDimensional->code,
            'report_created_at' => $subsDimensional->created_at->format('d/m/Y'),
            'specificationOptions' => $specifications,
            'inspectionMethods' => SubsDimensional::$INSPECTION_METHOD,
            'equipments' => SubsDimensional::$EQUIPMENTS,
            'pipeTypeOptions' => SubsDimensional::$PIPE_TYPE_OPTIONS,
            'pipeTypeStandards' => SubsDimensional::$PIPE_TYPES_STANDARDS,
            'footerData'=> $footerData,
            'pdfurl' => 'storage/pdf/inspection/tubular/subsdimensional/' . $subsDimensional->job_request->code . '/' . $subsDimensional->code . '.pdf',
            'hasPermission' => Auth::user()->hasPermission('subsdimensional', 'approve'),
            'imageurl' => $subsDimensional->job_request->code . '/' . $subsDimensional->code,
            'folder' => 'pdf/inspection/tubular/subsdimensional',
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
     * @param  \App\Models\Inspection\Tubular\SubsDimensional $subsDimensional
     * @return \Illuminate\Http\Response
     */
    public function edit(SubsDimensional $subsDimensional)
    {
        $jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();
        $specifications = Specification::getTubularSpecifications();
        return view('layouts.inspection.tubular.subsDimensional.edit', [
            'model' => $subsDimensional,
            'page_name' => $this->page_name(1, $this->page_name),
            'jobrequests' => $jobrequests,
            'specificationOptions' => $specifications,
            'inspectionMethods' => SubsDimensional::$INSPECTION_METHOD,
            'equipments' => SubsDimensional::$EQUIPMENTS,
            'pipeTypeOptions' => SubsDimensional::$PIPE_TYPE_OPTIONS,
            'pipeTypeStandards' => SubsDimensional::$PIPE_TYPES_STANDARDS,
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
     * @param  \App\Models\Inspection\Tubular\SubsDimensional $subsDimensional
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubsDimensional $subsDimensional)
    {
        $data = $request->all();
        if ($this->shouldForkApprovedInspectionRevision($subsDimensional)) {
            $data['job_request_id'] = $subsDimensional->job_request_id;
            $createdCert = $subsDimensional->create($data);
            $reportUpdateData = [
                'reportable_id' => $createdCert->id,
                'user_id_edit' => Auth::id(),
                'user_id_approved' => null,
                'publish' => null,
            ];
        } else {
            $subsDimensional->update($data);
            $reportUpdateData = [
                'user_id_edit' => Auth::id(),
            ];
        }

        $this->persistInspectionReportState($subsDimensional, $reportUpdateData);
        return response()->redirectToRoute('subsDimensional.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inspection\Tubular\SubsDimensional $subsDimensional
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubsDimensional $subsDimensional)
    {
        $remove = $this->check_edited_reports_for_delete($subsDimensional);

        if ($remove) {
            $pdf = 'inspection/tubular/subsdimensional/' . $subsDimensional->job_request->code . '/' . $subsDimensional->code . '.pdf';
            $snap = 'images/inspection/tubular/subsdimensional/' . $subsDimensional->job_request->code . '/' . $subsDimensional->code;
            $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
            return response()->json([
                'success' => $remove_snap_shots_pdf . $this->action_message(2, $this->page_name)
            ]);
        }
    }

    public function publish(SubsDimensional $subsDimensional)
    {
        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        $specifications = Specification::getTubularSpecifications();
        return view('layouts.inspection.tubular.subsDimensional.publish', [
            'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
            'model' => $subsDimensional,
            'jobrequests' => $jobrequests,
            'specificationOptions' => $specifications,
            'inspectionMethods' => SubsDimensional::$INSPECTION_METHOD,
            'equipments' => SubsDimensional::$EQUIPMENTS,
            'pipeTypeOptions' => SubsDimensional::$PIPE_TYPE_OPTIONS,
            'pipeTypeStandards' => SubsDimensional::$PIPE_TYPES_STANDARDS
        ]);
    }

    public function publishSubmit(Request $request, SubsDimensional $subsDimensional)
    {
        $data = $request->all();
        $job_request = JobRequest::find($request->job_request_id);
        $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;
        $data['code'] = $code;
        $subsDimensional->update($data);
        $this->persistInspectionReportState($subsDimensional, [
            'code' => $code,
            'status' => 1,
            'publish' => 1,
            'user_id_edit' => null,
        ]);
        return response()->redirectToRoute('subsDimensional.index');
    }
}
