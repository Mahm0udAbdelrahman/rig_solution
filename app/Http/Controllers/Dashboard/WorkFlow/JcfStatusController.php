<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\WorkFlow\JcfStatus;

class JcfStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JcfStatus  $jcfStatus
     * @return \Illuminate\Http\Response
     */
    public function show(JcfStatus $jcfStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JcfStatus  $jcfStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(JcfStatus $jcfStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JcfStatus  $jcfStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JcfStatus $jcfStatus)
    {
        $update = JcfStatus::where('id', $jcfStatus->id)->update([
          	'comment' => $request->type,
        ]);
        if ($update)
				{
	          return response()->json([
	            	'success' => "Job Control Form Closed Successfully."
	          ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JcfStatus  $jcfStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(JcfStatus $jcfStatus)
    {
        //
    }
}
