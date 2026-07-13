<?php

namespace App\Http\Controllers\Api\Dashboard\WorkFlow;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// Illuminate\Support
use Illuminate\Support\Facades\Http;

// App\Models
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\JcfStatus;

// App\Http\Resources
use App\Http\Resources\JobRequestCollection;

// Other
use DB;


class JobRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return JobRequestCollection::collection(JobRequest::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	      $done = [];
	      foreach ($request->localbody as $key => $value)
				{
		        $last = JobRequest::orderBy('code', 'desc')->latest()->first();
		        $last_id = "";
		        if (!$last || !str_contains($last, substr(date('Y'), 1)))
						{
		          	$last_id = substr(date('Y'), 1).'-001';
		        }
						else
						{
								$last_id = (int)substr($last->code,4);
								$last_id = substr(date('Y'), 1).'-'.str_pad($last_id+1, 3,'0',STR_PAD_LEFT);
		        }
		        $value['jcf']['code'] = $last_id;
		        $value['jcf']['sync'] = 1;
		        $store_job_request = JobRequest::create($value['jcf']);
		        if ($store_job_request)
						{
			          $store_job_request->departments()->attach(($value['departments']));
			          if ($value['engineers'])
								{
			            	$store_job_request->employees()->attach($value['engineers']);
			          }
			          $value['status']['job_request_id'] = $store_job_request->id;
			          JcfStatus::create($value['status']);
			          $done[$key] = $value['jcf']['id'];
		        }
	      }
	      return response()->json([
		        'done' => $done,
		        'success' => 'JCF Synced From Local To Server Successfully !'
	      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(JobRequest $jobRequestapi)
    {
        return new JobRequestCollection($jobRequestapi);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobRequest $jobRequestapi)
    {

      $update = $jobRequestapi->update([
        // 'code' => $last_id,
        'client_id' => $request->input('client_id'),
        'supplier_id' => $request->input('supplier_id'),
        'contact_people_id' => $request->input('contact_people_id'),
        'subject' => $request->input('subject'),
        'work_location' => json_encode($request->input('work_location')),
        'contactway' => json_encode($request->input('contactway')),
        'job_requierd_details' => $request->input('job_requierd_details'),
        'contact_date' => $request->input('contact_date'),
        'managers' => json_encode($request->input('managers')),
        'tools' => json_encode($request->input('tools')),
        'scope_of_work' => $request->input('scope_of_work'),
        'specification' => json_encode($request->input('specification')),
        'deploc' => $request->input('deploc'),
      ]);

      return response()->json([
        'success' => 'Job Control Form created successfully.'
      ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobRequest $jobRequestapi)
    {
        $jobRequestapi->delete();
        return response(null, 204);
    }
}
