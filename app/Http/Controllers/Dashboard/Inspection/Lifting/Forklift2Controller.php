<?php

namespace App\Http\Controllers\Dashboard\Inspection\Lifting;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// App\Models
use App\Models\Inspection\Lifting\Forklift;
use App\Models\Inspection\Lifting\Forklift2;

// Other
use DB, Storage, Crypt;

class Forklift2Controller extends Controller
{
    public $page_name = 'Forklift 2 Report';

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
    public function create(Forklift $forklift)
    {
        return view('layouts.inspection.lifting.forklift.second.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'forklift' => $forklift->id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Forklift $forklift)
    {

      $path = $request->file('lfr2_42');
      $path_crypt = NULL;
      if($path != NULL){
        $path_crypt = Crypt::encryptString($path->getClientOriginalName());
        $store = $path->storeAs(
        'public/camera/inspection/lifting/forklifts/second',
        $path_crypt
        );
      }

      $store_forklift2 = Forklift2::create([
        'forklift_id' => $forklift->id,
        'lfr2_1' => $request->lfr2_1,
        'lfr2_2' => $request->lfr2_2,
        'lfr2_3' => $request->lfr2_3,
        'lfr2_4' => $request->lfr2_4,
        'lfr2_5' => $request->lfr2_5,
        'lfr2_6' => $request->lfr2_6,
        'lfr2_7' => $request->lfr2_7,
        'lfr2_8' => $request->lfr2_8,
        'lfr2_9' => $request->lfr2_9,
        'lfr2_10' => $request->lfr2_10,
        'lfr2_11' => $request->lfr2_11,
        'lfr2_12' => $request->lfr2_12,
        'lfr2_13' => $request->lfr2_13,
        'lfr2_14' => $request->lfr2_14,
        'lfr2_15' => $request->lfr2_15,
        'lfr2_16' => $request->lfr2_16,
        'lfr2_17' => $request->lfr2_17,
        'lfr2_18' => $request->lfr2_18,
        'lfr2_19' => $request->lfr2_19,
        'lfr2_20' => $request->lfr2_20,
        'lfr2_21' => $request->lfr2_21,
        'lfr2_22' => $request->lfr2_22,
        'lfr2_23' => $request->lfr2_23,
        'lfr2_24' => $request->lfr2_24,
        'lfr2_25' => $request->lfr2_25,
        'lfr2_26' => $request->lfr2_26,
        'lfr2_27' => $request->lfr2_27,
        'lfr2_28' => $request->lfr2_28,
        'lfr2_29' => $request->lfr2_29,
        'lfr2_30' => $request->lfr2_30,
        'lfr2_31' => $request->lfr2_31,
        'lfr2_32' => $request->lfr2_32,
        'lfr2_33' => $request->lfr2_33,
        'lfr2_34' => $request->lfr2_34,
        'lfr2_35' => $request->lfr2_35,
        'lfr2_36' => $request->lfr2_36,
        'lfr2_37' => $request->lfr2_37,
        'lfr2_38' => $request->lfr2_38,
        'lfr2_39' => $request->lfr2_39,
        'lfr2_40' => $request->lfr2_40,
        'lfr2_41' => $request->lfr2_41,
        'lfr2_42' => $path_crypt,
      ]);

      if($store_forklift2){
          return response()->json([
            'success' => $this->action_message(0, $this->page_name)
          ]);
      }
    }

    protected function resolveForkliftModels($param): array
    {
        $forklift = null;
        $forklift2 = null;

        if ($param instanceof Forklift) {
            $forklift = $param;
            $forklift2 = $forklift->forklift2;
        } elseif ($param instanceof Forklift2) {
            $forklift2 = $param;
            $forklift = $forklift2->forklift;
        } else {
            $forkliftCandidate = Forklift::find($param);
            if ($forkliftCandidate) {
                $forklift = $forkliftCandidate;
                $forklift2 = $forkliftCandidate->forklift2;
            }

            if (!$forklift2) {
                $forklift2Candidate = Forklift2::find($param);
                if ($forklift2Candidate) {
                    $forklift2 = $forklift2Candidate;
                    $forklift = $forklift ?: $forklift2Candidate->forklift;
                }
            }
        }

        return [$forklift, $forklift2];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Forklift2  $forklift2
     * @return \Illuminate\Http\Response
     */
    public function show(Forklift2 $forklift2, Forklift $forklift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        [$forklift, $forklift2] = $this->resolveForkliftModels($id);
        if (!$forklift || !$forklift2) {
            abort(404);
        }

        return view('layouts.inspection.lifting.forklift.second.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'forklift2' => $forklift2,
            'forklift' => $forklift->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        [$forklift, $forklift2] = $this->resolveForkliftModels($id);
        if (!$forklift || !$forklift2) {
            return response()->json(['error' => 'Inspection record not found.'], 404);
        }

        $isApprovedRevision = $forklift->report && $forklift->report->user_id_approved != null;

        $path = $request->file('lfr2_42');
        $path_crypt = $request->imagedata;
        if($path != NULL){
          if(!$isApprovedRevision && $request->publish != 'yes' && !empty($forklift2->lfr2_42)){
            Storage::disk('public')->delete('camera/inspection/lifting/forklifts/second/'.$forklift2->lfr2_42);
          }
          $path_crypt = Crypt::encryptString($path->getClientOriginalName());
          $store = $path->storeAs(
          'public/camera/inspection/lifting/forklifts/second',
          $path_crypt
          );
        }else{
          if(!$isApprovedRevision && $request->imagedata == '' && $request->publish != 'yes' && !empty($forklift2->lfr2_42)){
            Storage::disk('public')->delete('camera/inspection/lifting/forklifts/second/'.$forklift2->lfr2_42);
          }
        }

        $data = [
          'lfr2_1' => $request->lfr2_1,
          'lfr2_2' => $request->lfr2_2,
          'lfr2_3' => $request->lfr2_3,
          'lfr2_4' => $request->lfr2_4,
          'lfr2_5' => $request->lfr2_5,
          'lfr2_6' => $request->lfr2_6,
          'lfr2_7' => $request->lfr2_7,
          'lfr2_8' => $request->lfr2_8,
          'lfr2_9' => $request->lfr2_9,
          'lfr2_10' => $request->lfr2_10,
          'lfr2_11' => $request->lfr2_11,
          'lfr2_12' => $request->lfr2_12,
          'lfr2_13' => $request->lfr2_13,
          'lfr2_14' => $request->lfr2_14,
          'lfr2_15' => $request->lfr2_15,
          'lfr2_16' => $request->lfr2_16,
          'lfr2_17' => $request->lfr2_17,
          'lfr2_18' => $request->lfr2_18,
          'lfr2_19' => $request->lfr2_19,
          'lfr2_20' => $request->lfr2_20,
          'lfr2_21' => $request->lfr2_21,
          'lfr2_22' => $request->lfr2_22,
          'lfr2_23' => $request->lfr2_23,
          'lfr2_24' => $request->lfr2_24,
          'lfr2_25' => $request->lfr2_25,
          'lfr2_26' => $request->lfr2_26,
          'lfr2_27' => $request->lfr2_27,
          'lfr2_28' => $request->lfr2_28,
          'lfr2_29' => $request->lfr2_29,
          'lfr2_30' => $request->lfr2_30,
          'lfr2_31' => $request->lfr2_31,
          'lfr2_32' => $request->lfr2_32,
          'lfr2_33' => $request->lfr2_33,
          'lfr2_34' => $request->lfr2_34,
          'lfr2_35' => $request->lfr2_35,
          'lfr2_36' => $request->lfr2_36,
          'lfr2_37' => $request->lfr2_37,
          'lfr2_38' => $request->lfr2_38,
          'lfr2_39' => $request->lfr2_39,
          'lfr2_40' => $request->lfr2_40,
          'lfr2_41' => $request->lfr2_41,
          'lfr2_42' => $path_crypt,
        ];

        if ($isApprovedRevision) {
            $update = DB::transaction(function () use ($forklift, $forklift2, $data) {
                $newForklift = $forklift->replicate();
                $newForklift->save();

                $newForklift2 = $forklift2->replicate();
                $newForklift2->fill($data);
                $newForklift2->forklift_id = $newForklift->id;
                $newForklift2->save();

                $this->persistInspectionReportState($forklift, [
                    'status' => 1,
                    'publish' => null,
                    'user_id_approved' => null,
                    'reportable_id' => $newForklift->id,
                    'user_id_edit' => Auth::id(),
                ]);

                return true;
            });
        } else {
            $update = $forklift2->update($data);
        }

        return response()->json([
            'success' => $this->action_message(1, $this->page_name)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Forklift2  $forklift2
     * @return \Illuminate\Http\Response
     */
    public function destroy(Forklift2 $forklift2)
    {
        //
    }
}
