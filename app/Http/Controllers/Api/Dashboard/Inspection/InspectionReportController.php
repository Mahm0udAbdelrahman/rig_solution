<?php

namespace App\Http\Controllers\Api\Dashboard\Inspection;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Inspection\InspectionReport;
use App\Models\WorkFlow\JobRequest;

// App\Http\Resources
use App\Http\Resources\InspectionReportCollection;

class InspectionReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      	return InspectionReportCollection::collection(InspectionReport::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInspectionReportRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $report, $table)
    {
      $done = [];
      foreach ($request->localbody as $key => $value) {
        $model2 = $report.'2';
        /** JCF Code **/
        $jcf_id = JobRequest::where('jcf_ref', $value['jcf_ref'])->first()->id;
        /** Report Code From ITSELF **/
        $last = InspectionReport::where('job_request_id', $jcf_id)->orderBy('code', 'DESC')->where('code', 'not LIKE', "%Duplicated")->first();
        if(!$last){
          $last = '001';
        }else{
          if(str_contains($last->code, '/')){
            $emad = substr($last->code, strpos($last->code, "/") + 1);
            $last = str_pad($emad+1, 3,'0',STR_PAD_LEFT);
          }else{
            $last = str_pad($last->code+1, 3,'0',STR_PAD_LEFT);
          }
        }
        $value[$table]['job_request_id'] = $jcf_id;
        $value[$table]['code'] = $last;
        $value[$table]['sync'] = 1;
        $store = $report::create($value[$table]);
        if($store){
          $store->report()->create([
            'job_request_id' => $jcf_id,
            'code' => $last,
            'status' => $value['report']['status'],
            'publish' => $value['report']['publish'],
            'sync' => $value['report']['sync'],
            'updated' => $value['report']['updated'],
            'user_id' => $value['report']['user_id'],
            'user_id_edit' => $value['report']['user_id_edit'],
            'user_id_approved' => $value['report']['user_id_approved'],
          ]);
          $done[$key] = $value[$table]['id'];
          if($value[$table.'2'] != null){
            $value[$table.'2'][$table.'_id'] = $store->id;
            $store = $model2::create($value[$table.'2']);
          }
        }
      }
      return response()->json([
        'done' => $done,
        'success' => $report.' Synced From Local To Server Successfully !',
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InspectionReport  $inspectionReport
     * @return \Illuminate\Http\Response
     */
    public function show(InspectionReport $inspectionReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InspectionReport  $inspectionReport
     * @return \Illuminate\Http\Response
     */
    public function edit(InspectionReport $inspectionReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInspectionReportRequest  $request
     * @param  \App\Models\InspectionReport  $inspectionReport
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInspectionReportRequest $request, InspectionReport $inspectionReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InspectionReport  $inspectionReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(InspectionReport $inspectionReport)
    {
        //
    }
}
