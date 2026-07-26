<?php

namespace App\Http\Controllers\Dashboard\Inspection\Lifting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Inspection\Lifting\Lregister;
use App\Models\Persons\Client;
use App\Models\User;
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\InspectionReport;
use App\Services\WorkFlow\FileManagerInspectionWorkbookExportService;

use Barryvdh\DomPDF\Facade\Pdf as PDF2;
use h4cc\WKHTMLToPDF\WKHTMLToPDF as PDF0;
use Illuminate\Support\Facades\View;

// Other
use DB, DataTables, Storage, Auth, Crypt, PDF;

class LregisterController extends Controller
{

    public $page_name = 'Lifting Register Report';


    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Lregister::class, 'lregister');

    }//end __construct()


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lregisters = Lregister::count();
        return view('layouts.inspection.lifting.lregister.index', ['page_name' => $this->page_name('All', $this->page_name), 'lregisters' => $lregisters]);

    }//end index()


    public function getDataForDataTable()
    {
        $data = Lregister::query()
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
        ->addColumn(
            'client',
            function ($row) {
                $report_code = '';
                $client = ($row->job_request && $row->job_request->client) ? $row->job_request->client : null;
                if (!$client) {
                    return 'N/A';
                }
                
                if (Auth::user()->can('view', $client)) {
                        $report_code .= '<a href='.route('client.show', $client->id).'>';
                }

                $report_code .= $client->name;

                if (Auth::user()->can('view', $client)) {
                      $report_code .= '</a>';
                }

                return $report_code;
            }
        )->addColumn(
            'code',
            function ($row) {
                $report_code = '';
                if (Auth::user()->can('view', Lregister::find($row->id))) {
                        $report_code .= '<a href='.route('lregister.show', $row->id).'>';
                }

                $report_code .= $row->job_request->code.'/'.$row->report->code;

                if (Auth::user()->can('view', Lregister::find($row->id))) {
                      $report_code .= '</a>';
                }

                return $report_code;
            }
        )->addColumn(
            'action',
            function ($row) {
                                $btn = '';
                                // if (Auth::user()->can('create', Lregister::class))
                                // {
                                // $btn .= '<button data-id="'.$row->report->id.'" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
                                // }
                if ($row->report->publish != null) {
                    if (Storage::disk('public')->exists('pdf/inspection/lifting/lregister/'.$row->job_request->code.'/'.$row->report->code.'.pdf')) {
                                        $btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="'.Storage::url('pdf/inspection/lifting/lregister/'.$row->job_request->code.'/'.$row->report->code.'.pdf').'">Download PDF</a>';
                    } else {
                                            $btn .= '<a class="btn btn-dark mr-1" href="'.route('lregister.show', $row->id).'">Open Report</a>';
                    }

                    if (Auth::user()->can('update', Lregister::find($row->id))) {
                            $btn .= '<a href="'.route('lregister.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }
                } else {
                    if (Auth::user()->can('create', Lregister::find($row->id))) {
                                        $btn .= '<a href="'.route('lregister.show', $row->id).'" class="btn btn-dark mr-1">Open Report</a>';
                    }
                }//end if

                if (Auth::user()->can('delete', Lregister::find($row->id))) {
                        $btn .= '<button type="button" data-id="'.$row->id.'" class="btn btn-icon btn-danger delete mr-1"><i class="la la-trash"></i></button>';
                }

                                return $btn;
            }
        )
        ->filterColumn('code', function($query, $keyword){
            $query->whereRaw("code like ?", ["%{$keyword}%"])->orWhereHas('job_request', function($row) use (&$keyword){
                $row->where("code", "like", ["%{$keyword}%"]);
            });
        })
      ->filterColumn('client', function($query, $keyword){
            $query->whereHas('job_request', function($row) use (&$keyword){
                $row->whereHas('client', function($row1) use (&$keyword){
                    $row1->where("name", "like", ["%{$keyword}%"]);
                });
            });
        })
        ->filterColumn('action', function ($query, $keyword) {
                $codes = $this->getUploadedCodes('/lifting/lregister', true);
                switch ($keyword) {
                    case 'publish':
                        $query->whereHas('report', function ($q) {
                            $q->where('publish', null);
                        });
                        break;
                    case 'download':
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereIn(DB::raw("CONCAT(job_requests.code, '/', lregisters.code)"), $codes);
                        });
                        break;
                    case 'upload':
//                        $query->whereRaw("inspection_reports.publish IS NOT NULL AND CONCAT(job_requests.code,'/',forklifts.code) NOT IN ($codes)");
                        $query->whereHas('report', function ($q) {
                            $q->whereNot('publish', null);
                        });
                        $query->whereHas('job_request', function ($q) use ($codes) {
                            $q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', lregisters.code)"), $codes);
                        });

                        break;
                }
})
        
            ->filter(function ($query) {
                $this->applyInspectionGlobalSearch($query, request('search.value'));
            })
        ->rawColumns(['code', 'client', 'action'])->make('true');

    }//end getDataForDataTable()


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobrequests = DB::table('job_requests')->select('id', 'code', 'purchase_order')->orderBy('id', 'desc')->get();
        return view('layouts.inspection.lifting.lregister.add', ['page_name' => $this->page_name(0, $this->page_name), 'jobrequests' => $jobrequests]);

    }//end create()


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $job_request = JobRequest::find($request->lcr_1);
        $code        = json_decode(json_encode(CustomController::getReportData($job_request)))->original->lastcode;

        $store = Lregister::create(
            [
                'job_request_id' => $request->lcr_1,
                'date'           => $request->lcr_60,
                'code'           => $code,
                'color_code'     => $request->lcr_70,
                'sync'           => 0,
            ]
        );

        if ($store) {
            $store->report()->create(
                [
                    'job_request_id' => $request->lcr_1,
                    'code'           => $code,
                    'status'         => 1,
                    'publish' => null,
                    'sync'           => 1,
                    'user_id'        => Auth::id(),
                ]
            );
            // Notification::send(User::all(), new InspectionReport(str_pad($store->code+1, 3,'0',STR_PAD_LEFT), $request->lcr_1));
            return response()->json(
                [
                    'last_id' => $store->id,
                    'success' => $this->action_message(0, $this->page_name),
                ]
            );
        }

    }//end store()


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lregister $lregister
     * @return \Illuminate\Http\Response
     */
    public function getForShow(Lregister $lregister)
    {
        $data = InspectionReport::query()->where('job_request_id', $lregister->job_request->id);
        $this->applyInspectionApprovalPresetFilter($data, request('smart_preset'));
        return Datatables::eloquent($data)->addColumn(
            'id',
            function ($row) {
                $id  = $row->reportable->lcr_12;
                $id .= $row->reportable->locr_12;
                $id .= $row->reportable->lfr_12;
                if ($row->reportable->lter_10 != null) {
                    $id .= $row->reportable->lter_10['pop1'];
                }

                return $id;
            }//end getForShow()
        )->make(true);

    }//end getForShow()


    public function show(Lregister $lregister)
    {
        $data = InspectionReport::query()
            ->where('job_request_id', $lregister->job_request->id)
            ->where('reportable_type', 'not like', '%Ndt%')
            ->where('code', 'not like', '%Duplicated%')
            ->where('reportable_type', '!=', 'App\Models\Inspection\Lifting\Defect')
            ->where('reportable_type', '!=', 'App\Models\Inspection\Lifting\Lregister')
            ->get();

        /** manually split data into separate lists each list should be in a single page*/
        $subCollections = $data->chunk(28);
        $pdfPath = 'pdf/inspection/lifting/lregister/' . $lregister->job_request->code . '/' . $lregister->code . '.pdf';
        $pdfExists = Storage::disk('public')->exists($pdfPath);
        return view('layouts.inspection.lifting.lregister.pdf.new-test', [
            'subCollections' => $subCollections,
            'user_id_approved' => data_get($lregister, 'report.user_id_approved'),
            'page_name' => 'Register of Lifting Appliances and Lifting Accessories',
            'page_text' => '',
            'lregister' => $lregister,
            'model' => $lregister,
            'for_approve_url' => $lregister->report->id,
            'total' => $subCollections->count(),
            'pdfurl' => 'storage/' . $pdfPath,
            'pdf_exists' => $pdfExists,
            'pdf_download_url' => $this->storageUrlWithVersion($pdfPath),
            'hasPermission' => Auth::user()->hasPermission('lregister', 'approve'),
            'imageurl' => $lregister->job_request->code . '/' . $lregister->code,
            'folder' => 'pdf/inspection/lifting/lregister',
            'code' => $lregister->code,
            'job' => $lregister->job_request,
            'date' => $lregister->date,
            'color' => $lregister->color_code,
        ]);

    }//end show()


    public function exportExcel(Lregister $lregister, FileManagerInspectionWorkbookExportService $exportService)
    {
        if (!$lregister->report) {
            return redirect()->back()->with('error', 'Report record not found.');
        }

        try {
            return $exportService->downloadForReport($lregister->report);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


                /**
                 * Show the form for editing the specified resource.
                 *
                 * @param  \App\Models\Lregister $lregister
                 * @return \Illuminate\Http\Response
                 */
    public function edit(Lregister $lregister)
    {

    }//end edit()


                /**
                 * Update the specified resource in storage.
                 *
                 * @param  \Illuminate\Http\Request $request
                 * @param  \App\Models\Lregister    $lregister
                 * @return \Illuminate\Http\Response
                 */
    public function update(Request $request, Lregister $lregister)
    {

    }//end update()


                /**
                 * Remove the specified resource from storage.
                 *
                 * @param  \App\Models\Lregister $lregister
                 * @return \Illuminate\Http\Response
                 */
    public function destroy(Lregister $lregister)
    {
        $remove_lregister = DB::transaction(function () use ($lregister) {
            InspectionReport::query()
                ->where('reportable_type', Lregister::class)
                ->where('reportable_id', (int) $lregister->id)
                ->delete();

            return (bool) Lregister::query()->whereKey($lregister->id)->delete();
        });

        if ($remove_lregister) {
            return response()->json(
                ['success' => 'Register deleted successfully']
            );
        }

    }//end destroy()


}//end class


