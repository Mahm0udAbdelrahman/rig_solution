<?php

namespace App\Http\Controllers\Api\Dashboard\WorkFlow;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceCollection;
use App\Models\Invoice;
use App\Models\JobRequest;
use App\Models\JcfStatus;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return InvoiceCollection::collection(Invoice::all());
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
      $done = [];
      foreach ($request->localbody as $key => $value) {
        $last = Invoice::orderBy('code', 'desc')->latest()->first();
        $last_id = "";
        if (!$last || !str_contains($last, substr(date('Y'), 1))){
          $last_id = 'INV-'.substr(date('Y'), 1).'-001';
        }else{
          $last_id = 'INV-'.substr(date('Y'), 1).'-'.str_pad(substr($last->code, 8)+1, 3,'0',STR_PAD_LEFT);
        }
        $jcf_id = JobRequest::where('jcf_ref', $value['jcf_ref'])->first()->id;
        $value['invoice']['job_request_id'] = $jcf_id;
        $value['invoice']['code'] = $last_id;
        $value['invoice']['sync'] = 1;
        $store_invoice = Invoice::create($value['invoice']);
        if($store_invoice){
          JcfStatus::where('job_request_id', $jcf_id)->update(['comment' => 'cm']);
          $done[$key] = $value['invoice']['id'];
        }
      }
      return response()->json([
        'done' => $done,
        'success' => 'Invoice Synced From Local To Server Successfully !',
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
