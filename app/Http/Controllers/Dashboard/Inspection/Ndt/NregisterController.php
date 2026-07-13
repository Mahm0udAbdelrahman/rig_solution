<?php

namespace App\Http\Controllers\Dashboard\Inspection\Ndt;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inspection\InspectionReport;
use App\Models\Inspection\Ndt\Mpipt;
use App\Models\Inspection\Ndt\Nregister;
use App\Models\Persons\Client;
use App\Models\Persons\Supplier;

use DB, DataTables, Storage, Auth, Crypt;

class NregisterController extends Controller
{

	public $page_name = 'NDT Register Report';

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$reports = Nregister::count();
		return view('layouts.inspection.ndt.nregister.index', ['page_name' => $this->page_name('All', $this->page_name), 'reports' => $reports]);
	}

	public function getDataForDataTable()
	{
		$data = Nregister::query()
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
					if (Auth::user()->can('view', Client::find($row->job_request->client->id))) {
						$report_code .= "<a href=" . route('client.show', $row->job_request->client->id) . ">";
					}

					$report_code .= $row->job_request->client->name;

					if (Auth::user()->can('view', Client::find($row->job_request->client->id))) {
						$report_code .= "</a>";
					}
				} else {
					if (Auth::user()->can('view', Supplier::find($row->job_request->supplier->id))) {
						$report_code .= "<a href=" . route('client.show', $row->job_request->supplier->id) . ">";
					}

					$report_code .= $row->job_request->supplier->name;

					if (Auth::user()->can('view', Supplier::find($row->job_request->supplier->id))) {
						$report_code .= "</a>";
					}
				}
				return $report_code;
			})
			->addColumn('code', function ($row) {
				$report_code = "";
				if (Auth::user()->can('view', Nregister::find($row->id))) {
					$report_code .= "<a href=" . route('nregister.show', $row->id) . ">";
				}

				$report_code .= $row->job_request->code . '/' . $row->code;

				if (Auth::user()->can('view', Nregister::find($row->id))) {
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
				// if (Auth::user()->can('create', Nregister::class)) {
				// 	$btn .= '<button data-id="' . $row->report->id . '" class="btn btn-primary mr-1 duplicate"><i class="la la-clone"></i></button>';
				// }

				if ($row->report->publish != NULL) {
					if (Storage::disk('public')->exists('pdf/inspection/ndt/nregister/' . $row->job_request->code . '/' . $row->report->code . '.pdf')) {
						$btn .= '<a class="btn btn-secondary mr-1" target="_blank" href="' . Storage::url('pdf/inspection/ndt/nregister/' . $row->job_request->code . '/' . $row->report->code . '.pdf') . '">Download PDF</a>';
					} else {
						$btn .= '<a class="btn btn-dark mr-1" href="' . route('nregister.show', $row->id) . '">Open Report</a>';
					}

					if (Auth::user()->can('update', Nregister::find($row->id))) {
						$btn .= '<a href="' . route('nregister.edit', $row->id) . '" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
					}
				} else {
					if (Auth::user()->can('create', Nregister::find($row->id))) {
						$btn .= '<a href="' . route('nregister.show', $row->id) . '" class="btn btn-dark mr-1">Open Report</a>';
					}
				}


				if (Auth::user()->can('delete', Nregister::find($row->id))) {
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
					$row->whereHas('client', function ($row1) use (&$keyword) {
						$row1->where("name", "like", ["%{$keyword}%"]);
					});
				});
			})
			->filterColumn('action', function ($query, $keyword) {
				$codes = $this->getUploadedCodes('/ndt/nregister', true);
				switch ($keyword) {
					case 'publish':
						$query->whereHas('report', function ($q) {
							$q->where('publish', null);
						});
						break;
					case 'download':
						$query->whereHas('job_request', function ($q) use ($codes) {
							$q->whereIn(DB::raw("CONCAT(job_requests.code, '/', nregisters.code)"), $codes);
						});
						break;
					case 'upload':
						$query->whereHas('report', function ($q) {
							$q->whereNot('publish', null);
						});
						$query->whereHas('job_request', function ($q) use ($codes) {
							$q->whereNotIn(DB::raw("CONCAT(job_requests.code, '/', nregisters.code)"), $codes);
						});

						break;
				}
			})
			
            ->filter(function ($query) {
                $this->applyInspectionGlobalSearch($query, request('search.value'));
            })
			->rawColumns(['code', 'client', 'action'])
			
            ->filter(function ($query) {
                $this->applyInspectionGlobalSearch($query, request('search.value'));
            })
			->make('true');
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();

		return view('layouts.inspection.ndt.nregister.add', [
			'page_name' => $this->page_name(0, $this->page_name),
			'jobrequests' => $jobrequests,
		]);
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$data = $request->all();
		$created = Nregister::create($data);
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
			return response()->redirectToRoute('nregister.index');
		}
		return response()->json([$data]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Nregister  $nregister
	 * @return \Illuminate\Http\Response
	 */
	public function show(Nregister $nregister)
	{
		$have_edit = $this->check_if_report_edit(Nregister::class, $nregister);
		$nregister->report = $have_edit->default;
		$footerData = [
			'formNo' => 'RS-RF-F18',
			'issueNo' => '04',
			'issueDate' => '1-Mar-2024',
			'revisionNo' => '00',
			'revisionDate' => '1-Mar-2024',
			'pageNo' => '1 of 1',
		];

		$data = Mpipt::query()
			->where('job_request_id', $nregister->job_request->id)
			->where('code', 'not like', '%Duplicated%')
			->get();

		/** manually split data into separate lists each list should be in a single page*/
        $subCollections = $data->chunk(18);
		$pageCount = $subCollections->count();
		$pdfPath = 'pdf/inspection/ndt/nregister/' . $nregister->job_request->code . '/' . $nregister->code . '.pdf';

		return view('layouts.inspection.ndt.nregister.show', [
			'subCollections' => $subCollections,
			'total' => $pageCount,
			'page_name' => $this->page_name,
			'page_text' => '',
			'model' => $nregister,
			'code' => $nregister->job_request->code . '/' . $nregister->code,
			'report_created_at' => $nregister->created_at->format('d/m/Y'),
			'footerData' => $footerData,
			'pdfurl' => 'storage/' . $pdfPath,
			'pdf_exists' => Storage::disk('public')->exists($pdfPath),
			'pdf_download_url' => $this->storageUrlWithVersion($pdfPath),
			'hasPermission' => Auth::user()->hasPermission('nregister', 'approve'),
			'imageurl' => $nregister->job_request->code . '/' . $nregister->code,
			'folder' => 'pdf/inspection/ndt/nregister',
			'iso_number' => 'Form # RSE-RF-07 - ISSUE 07 / Jul 2023',
			'page_number' => '1 of 1',
			'have_edit' => $have_edit->edited,
			'user_id_approved' => data_get($nregister, 'report.user_id_approved'),
			'for_approve_url' => data_get($nregister, 'report.id'),
			'esign' => data_get($nregister, 'report.user.employee.esign'),
			'person_make_report' => data_get($nregister, 'report.user.employee.name'),
			'person_make_report_desc' => data_get($nregister, 'report.user.employee.desc'),
		]);
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Nregister  $nregister
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Nregister $nregister)
	{
		$jobrequests = DB::table('job_requests')->select('id', 'code')->orderBy('id', 'desc')->get();

		return view('layouts.inspection.ndt.nregister.edit', [
			'model' => $nregister,
			'jobrequests' => $jobrequests,
			'page_name' => $this->page_name(1, $this->page_name),
		]);
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \App\Http\Requests\UpdateNregisterRequest  $request
	 * @param  \App\Models\Nregister  $nregister
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Nregister $nregister)
	{
		$data = $request->all();
		$nregister->update($data);
		$reportUpdateData = [
			'user_id_edit' => Auth::id(),
			'user_id_approved' => null,
			'publish' => null,
		];
		$this->persistInspectionReportState($nregister, $reportUpdateData);
		return response()->redirectToRoute('nregister.index');
	}

	public function publish(Nregister $nregister)
	{
		return redirect()->route('nregister.show', $nregister);
	}

	public function publishSubmit(Request $request, Nregister $nregister)
	{
		return redirect()->route('nregister.show', $nregister);
	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Nregister  $nregister
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Nregister $nregister)
	{
		$remove = $this->check_edited_reports_for_delete($nregister);

		if ($remove) {
			$pdf = 'pdf/inspection/ndt/nregister/' . $nregister->job_request->code . '/' . $nregister->code . '.pdf';
			$snap = 'images/inspection/ndt/nregister/' . $nregister->job_request->code . '/' . $nregister->code;
			$remove_snap_shots_pdf = $this->remove_snap_shots_pdf($pdf, $snap);
			return response()->json([
				'success' => $remove_snap_shots_pdf . $this->action_message(2, $this->page_name)
			]);
		}
	}
}
