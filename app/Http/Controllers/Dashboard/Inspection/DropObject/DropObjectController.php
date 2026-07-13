<?php

namespace App\Http\Controllers\Dashboard\Inspection\DropObject;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\GeneralInfo\Specification;
use App\Models\Inspection\DropObject\DropObject;
use App\Models\Persons\Client;
use App\Models\Persons\Supplier;
use App\Models\WorkFlow\JobRequest;
use Illuminate\Http\Request;

// Other
use DB, DataTables, Storage, Auth, Crypt;
use function Symfony\Component\String\length;

class DropObjectController extends Controller
{
    public $page_name = 'Drop Object Survey';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = DropObject::count();
        return view('layouts.inspection.dropObject.dropObject.index', ['page_name' => $this->page_name('All', $this->page_name), 'reports' => $reports]);
    }

    public function getDataForDataTable(Request $request)
    {
        $data = DropObject::query()
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
                if (Auth::user()->can('view', DropObject::find($row->id))  ) {
                    $report_code .= "<a href=" . route('dropObject.show', $row->id) . ">";
                }

                $report_code .= $row->job_request->code . '/' . $row->code;

                if (Auth::user()->can('view', DropObject::find($row->id))  ) {
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
                if (Auth::user()->can('create', DropObject::class)   ) {
                    $btn .= '<button data-id="' . $row->report->id . '" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                }

                if ($row->report->publish != NULL) {
                    if (Storage::disk('public')->exists('pdf/inspection/dropobject/dropobject/' . $row->job_request->code . '/' . $row->code . '.pdf')) {
                        $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . Storage::url('pdf/inspection/dropobject/dropobject/' . $row->job_request->code . '/' . $row->code . '.pdf') . '">Download PDF</a>';
                    } else {
                        $btn .= '<a class="btn btn-dark mr-1" href="' . route('dropObject.show', $row->id) . '">Open Report</a>';
                    }

                    if (Auth::user()->can('update', DropObject::find($row->id))  ) {
                        $btn .= '<a href="' . route('dropObject.edit', $row->id) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }
                } else {
                    $btn .= $this->buildInspectionUnpublishedActionButtons($row, DropObject::class, (int) ($row->id), 'dropObject.show', 'publish.dropObject', 'dropObject.edit');
                }


                if (Auth::user()->can('delete', DropObject::find($row->id))  ) {
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
                $codes = $this->getUploadedCodes('/dropobject/dropobject', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->where('publish', null);
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', drop_objects.code)"), $codes);
                        });
                        break;
                    case 'upload':
                        $query->whereHas('report', function ($q) {
                            $q->whereNot('publish', null);
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', drop_objects.code)"), $codes);
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

        return view('layouts.inspection.dropObject.dropObject.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'jobrequests' => $jobrequests,
            'inspectionAreaOptions' => DropObject::$INSPECTION_AREA_OPTIONS,
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
        $inspectionData = $data['inspection_data'] ?? [];

        // Handle photo file uploads
        foreach ($inspectionData as $key => $item) {
            if (isset($item['photo']) && $request->hasFile("inspection_data.{$key}.photo")) {
                $item['photo'] = $request->file("inspection_data.{$key}.photo")->store('photos', 'public');
				$inspectionData[$key] = $item;
            }
        }
        $data['inspection_data'] = $inspectionData;
        $created = DropObject::create($data);
        if ($created) {
            $created->report()->create([
                'job_request_id' => $request->job_request_id,
                'code' => $data['code'],
                'status' => 1,
                'publish' => null,
                'sync' => 1,
                'user_id' => Auth::id(),
            ]);
            return response()->redirectToRoute('dropObject.index');
        }
        return response()->json([$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inspection\DropObject\DropObject dropObject
     * @return \Illuminate\Http\Response
     */
    public function show(DropObject $dropObject)
    {
        $have_edit = $this->check_if_report_edit(DropObject::class, $dropObject);
        $dropObject->report = $have_edit->default;
        $model = $dropObject;
        $footerData = [
            'formNo' => 'RS-RF-F18',
            'issueNo' => '04',
            'issueDate' => '1-Mar-2024',
            'revisionNo'=> '00',
            'revisionDate' => '1-Mar-2024',
            'pageNo' => '1 of 1',
        ];

        /** add empty rows to inspection data array & be sure to allow only 20 row on the inspection data*/
        // $oldArray = $dropObject->inspection_data ? $dropObject->inspection_data : [];
        // $newArray = (count($oldArray) > 20) ? array_slice($oldArray, 0, 20) : $oldArray;
        // for ($i = count($newArray); $i < 20; $i++) {
        //     $newArray[] = array_combine(array_map(function ($i) {
        //         return "input_$i";
        //     }, range(1, 37)), array_fill(0, 37, ''));
        // }
        // $dropObject->inspection_data = $newArray;
        /** **************************************** */

        return view('layouts.inspection.dropObject.dropObject.show', [
            'page_name' => $this->page_name,
            'page_text' => '',
            'model' => $dropObject,
            'code' => $dropObject->job_request->code . '/' . $dropObject->code,
            'report_created_at' => $dropObject->created_at->format('d/m/Y'),
            'footerData'=> $footerData,
			'inspectionAreaOptions' => DropObject::$INSPECTION_AREA_OPTIONS,
            'pdfurl' => 'storage/pdf/inspection/dropobject/dropobject/' . $dropObject->job_request->code . '/' . $dropObject->code . '.pdf',
            'hasPermission' => Auth::user()->hasPermission('dropobject', 'approve'),
            'imageurl' => $dropObject->job_request->code . '/' . $dropObject->code,
            'folder' => 'pdf/inspection/dropobject/dropobject',
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
     * @param  \App\Models\Inspection\DropObject\DropObject $dropObject
     * @return \Illuminate\Http\Response
     */
    public function edit(DropObject $dropObject)
    {
        $jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();

		return view('layouts.inspection.dropObject.dropObject.edit', [
            'model' => $dropObject,
            'page_name' => $this->page_name(1, $this->page_name),
            'jobrequests' => $jobrequests,
			'inspectionAreaOptions' => DropObject::$INSPECTION_AREA_OPTIONS,
	]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Inspection\DropObject\DropObject $dropObject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DropObject $dropObject)
    {
        $data = $request->all();
		$inspectionData = $data['inspection_data'] ?? [];
		$oldInspectionData = $dropObject->inspection_data;
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

		if ($this->shouldForkApprovedInspectionRevision($dropObject)) {
            $data['job_request_id'] = $dropObject->job_request_id;
            $createdCert = $dropObject->create($data);
            $reportUpdateData = [
                'reportable_id' => $createdCert->id,
                'user_id_edit' => Auth::id(),
                'user_id_approved' => null,
                'publish' => null,
            ];
        } else {
            $dropObject->update($data);
            $reportUpdateData = [
                'user_id_edit' => Auth::id(),
            ];
        }

        $this->persistInspectionReportState($dropObject, $reportUpdateData);
        return response()->redirectToRoute('dropObject.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inspection\DropObject\DropObject $dropObject
     * @return \Illuminate\Http\Response
     */
    public function destroy(DropObject $dropObject)
    {
        $remove = $this->check_edited_reports_for_delete($dropObject);

        if ($remove) {
            $pdf = 'inspection/dropobject/dropobject/' . $dropObject->job_request->code . '/' . $dropObject->code . '.pdf';
            $snap = 'images/inspection/dropobject/dropobject/' . $dropObject->job_request->code . '/' . $dropObject->code;
            $remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
            return response()->json([
                'success' => $remove_snap_shots_pdf . $this->action_message(2, $this->page_name)
            ]);
        }
    }

    public function publish(DropObject $dropObject)
    {
        $jobrequests = DB::table('job_requests')->select('id','code')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.dropObject.dropObject.publish', [
            'page_name' => $this->page_name(0, $this->page_name).' (Publish)',
            'model' => $dropObject,
            'jobrequests' => $jobrequests,
			'inspectionAreaOptions' => DropObject::$INSPECTION_AREA_OPTIONS
        ]);
    }

    public function publishSubmit(Request $request, DropObject $dropObject)
    {
        $data = $request->all();
		$inspectionData = $data['inspection_data'] ?? [];
		$oldInspectionData = $dropObject->inspection_data;
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
		
        $job_request = JobRequest::find($request->job_request_id);
        $code = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;
        $data['code'] = $code;
        $dropObject->update($data);
        $this->persistInspectionReportState($dropObject, [
            'code' => $code,
            'status' => 1,
            'publish' => 1,
            'user_id_edit' => null,
        ]);
        return response()->redirectToRoute('dropObject.index');
    }
}
