<?php

namespace App\Http\Controllers\Dashboard\Inspection\Ndt;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\GeneralInfo\Specification;
use App\Models\Inspection\Ndt\DrawingInspection;
use App\Models\Persons\Client;
use App\Models\Persons\Supplier;
use App\Models\WorkFlow\JobRequest;
use Illuminate\Http\Request;

// Other
use DB, DataTables, Storage, Auth, Crypt;
use function Symfony\Component\String\length;

class DrawingInspectionController extends Controller
{
    public $page_name = 'Drawing Inspection Report';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = DrawingInspection::count();
        return view('layouts.inspection.ndt.drawingInspection.index', ['page_name' => $this->page_name('All', $this->page_name), 'reports' => $reports]);
    }

    public function getDataForDataTable(Request $request)
    {
        $data = DrawingInspection::query()
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
                    ? '<a href="' . route('drawingInspection.show', $row->id) . '">' . $reportCode . '</a>'
                    : $reportCode;
            })
            ->addColumn('deploc', function ($row) {
                return (string) optional($row->job_request)->deploc;
            })
            ->addColumn('identification_no', function ($row) {
                return $row->identification_no;
            })
            ->addColumn('description', function ($row) {
                return $row->description;
            })
            ->addColumn('client_department', function ($row) {
                return optional(optional($row->job_request)->clientDepartment)->name ?: '';
            })
            ->addColumn('action', function ($row) {
                $btn = "";
                $report = $row->report;
                $jobRequestCode = optional($row->job_request)->code;

                if (Auth::user()->can('create', DrawingInspection::class) && $report) {
                    $btn .= '<button data-id="' . $report->id . '" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                }

                if (!$report) {
                    return $btn;
                }

                if (!is_null($report->publish)) {
                    if ($jobRequestCode && Storage::disk('public')->exists('pdf/inspection/ndt/drawinginspection/' . $jobRequestCode . '/' . $row->code . '.pdf')) {
                        $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . Storage::url('pdf/inspection/ndt/drawinginspection/' . $jobRequestCode . '/' . $row->code . '.pdf') . '">Download PDF</a>';
                    } else {
                        $btn .= '<a class="btn btn-dark mr-1" href="' . route('drawingInspection.show', $row->id) . '">Open Report</a>';
                    }

                    if (Auth::user()->can('update', $row)) {
                        $btn .= '<a href="' . route('drawingInspection.edit', $row->id) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }
                } else {
                    $btn .= $this->buildInspectionUnpublishedActionButtons($row, DrawingInspection::class, (int) ($row->id), 'drawingInspection.show', 'publish.drawingInspection', 'drawingInspection.edit');
                }

                if (Auth::user()->can('delete', $row)) {
                    $btn .= '<button type="button" data-id="' . $row->id . '" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
                }
                return $btn;
            })
            ->filterColumn('acceptance', function ($query, $keyword) {
                $query->whereRaw("acceptance like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('identification_no', function ($query, $keyword) {
                $query->whereRaw("identification_no like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('description', function ($query, $keyword) {
                $query->whereRaw("description like ?", ["%{$keyword}%"]);
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
                $codes = $this->getUploadedCodes('/ndt/drawinginspection', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->whereNull('publish');
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', drawing_inspections.code)"), $codes);
                        });
                        break;
                    case 'upload':
                        $query->whereHas('report', function ($q) {
                            $q->whereNotNull('publish');
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', drawing_inspections.code)"), $codes);
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

        return view('layouts.inspection.ndt.drawingInspection.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'jobrequests' => $jobrequests,
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
				$data['report_image'] = null;
				if ($request->hasFile('report_image')) {
					$file = $request->file('report_image');
					$uniqueFileName =  time() . '.' . $file->getClientOriginalExtension();
					$directory = 'camera/inspection/ndt/drawinginspection';
					$file->storeAs($directory, $uniqueFileName, 'public');
					$data['report_image'] = $uniqueFileName;
				}				
        $created = DrawingInspection::create($data);
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
            return response()->redirectToRoute('drawingInspection.index');
        }
        return response()->json([$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inspection\Ndt\DrawingInspection drawingInspection
     * @return \Illuminate\Http\Response
     */
    public function show(DrawingInspection $drawingInspection)
    {
        $have_edit = $this->check_if_report_edit(DrawingInspection::class, $drawingInspection);
        $drawingInspection->report = $have_edit->default;
        $footerData = [
            'formNo' => 'RS-RF-F18',
            'issueNo' => '04',
            'issueDate' => '1-Mar-2024',
            'revisionNo'=> '00',
            'revisionDate' => '1-Mar-2024',
            'pageNo' => '1 of 1',
        ];

        return view('layouts.inspection.ndt.drawingInspection.show', [
            'page_name' => $this->page_name,
            'page_text' => '',
            'model' => $drawingInspection,
            'model2' => false,
            'code' => $drawingInspection->job_request->code . '/' . $drawingInspection->code,
            'report_created_at' => $drawingInspection->created_at->format('d/m/Y'),
            'footerData'=> $footerData,
            'pdfurl' => 'storage/pdf/inspection/ndt/drawinginspection/' . $drawingInspection->job_request->code . '/' . $drawingInspection->code . '.pdf',
            'hasPermission' => Auth::user()->can('approve', $drawingInspection),
            'imageurl' => $drawingInspection->job_request->code . '/' . $drawingInspection->code,
            'folder' => 'pdf/inspection/ndt/drawinginspection',
            'iso_number' => 'Form # RSE-RF-07 - ISSUE 07 / Jul 2023',
            'page_number' => '1 of 1',
            'have_edit' => $have_edit->edited,
            'user_id_approved' => data_get($drawingInspection, 'report.user_id_approved'),
            'for_approve_url' => data_get($drawingInspection, 'report.id'),
            'esign' => data_get($drawingInspection, 'report.user.employee.esign'),
            'person_make_report' => data_get($drawingInspection, 'report.user.employee.name'),
            'person_make_report_desc' => data_get($drawingInspection, 'report.user.employee.desc'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inspection\Ndt\DrawingInspection $drawingInspection
     * @return \Illuminate\Http\Response
     */
    public function edit(DrawingInspection $drawingInspection)
    {
        // return $drawingInspection;
        $jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.drawingInspection.edit', [
            'model' => $drawingInspection,
            'page_name' => $this->page_name(1, $this->page_name),
            'jobrequests' => $jobrequests,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Inspection\Ndt\DrawingInspection $drawingInspection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DrawingInspection $drawingInspection)
    {
		$data = $request->all();
		// dd($data);
		// $data['report_image'] = null;
		if ($request->hasFile('report_image')) {
			$file = $request->file('report_image');
			$uniqueFileName =  time() . '.' . $file->getClientOriginalExtension();
			$directory = 'camera/inspection/ndt/drawinginspection';
			$file->storeAs($directory, $uniqueFileName, 'public');
			$data['report_image'] = $uniqueFileName;
		}	
        if ($drawingInspection->report->user_id_approved != NULL) {
            $data['job_request_id'] = $drawingInspection->job_request_id;
            $createdCert = DrawingInspection::create($data);
            $sourceReport = $drawingInspection->report;

            $createdCert->report()->create([
                'job_request_id' => $sourceReport->job_request_id ?: $drawingInspection->job_request_id,
                'code' => $sourceReport->code ?: $drawingInspection->code,
                'status' => 1,
                'publish' => null,
                'sync' => $sourceReport->sync ?? 1,
                'user_id' => $sourceReport->user_id ?: Auth::id(),
                'user_id_edit' => Auth::id(),
                'user_id_approved' => null,
            ]);

            return response()->redirectToRoute('drawingInspection.index');
        } else {
            $drawingInspection->update($data);
            $reportUpdateData = [
                'user_id_edit' => Auth::id(),
            ];
        }

        $this->persistInspectionReportState($drawingInspection, $reportUpdateData);
        return response()->redirectToRoute('drawingInspection.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inspection\Ndt\DrawingInspection $drawingInspection
     * @return \Illuminate\Http\Response
     */
    public function destroy(DrawingInspection $drawingInspection)
    {
        $remove = $this->check_edited_reports_for_delete($drawingInspection);

        if ($remove) {
            $pdf = 'inspection/ndt/drawinginspection/' . $drawingInspection->job_request->code . '/' . $drawingInspection->code . '.pdf';
            $snap = 'images/inspection/ndt/drawinginspection/' . $drawingInspection->job_request->code . '/' . $drawingInspection->code;
            $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
            return response()->json([
                'success' => $remove_snap_shots_pdf . $this->action_message(2, $this->page_name)
            ]);
        }
    }

    public function publish(DrawingInspection $drawingInspection)
    {
        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.ndt.drawingInspection.publish', [
            'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
            'model' => $drawingInspection,
            'jobrequests' => $jobrequests,
        ]);
    }

    public function publishSubmit(Request $request, DrawingInspection $drawingInspection)
    {
        $data = $request->all();
		// $data['report_image'] = null;
		if ($request->hasFile('report_image')) {
			$file = $request->file('report_image');
			$uniqueFileName =  time() . '.' . $file->getClientOriginalExtension();
			$directory = 'camera/inspection/ndt/drawinginspection';
			$file->storeAs($directory, $uniqueFileName, 'public');
			$data['report_image'] = $uniqueFileName;
		}	
        $job_request = JobRequest::find($request->job_request_id);
        $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;
        $data['code'] = $code;
        $drawingInspection->update($data);
        $this->persistInspectionReportState($drawingInspection, [
            'code' => $code,
            'status' => 1,
            'publish' => 1,
            'user_id_edit' => null,
        ]);
        return response()->redirectToRoute('drawingInspection.index');
    }
}
