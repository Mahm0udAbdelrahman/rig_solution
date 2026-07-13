<?php

namespace App\Http\Controllers\Dashboard\Inspection\Lifting;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// App\Models
use App\Models\Inspection\Lifting\OverheadCrane;
use App\Models\Inspection\Lifting\OverheadCrane2;
use App\Models\WorkFlow\JobRequest;

// Other
use DB;

class OverheadCrane2Controller extends Controller
{
    public $page_name = 'Lifting Overhead Crane 2 Report';

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
    public function create(OverheadCrane $overheadCrane)
    {
        if (!$overheadCrane->overheadCrane2)
				{
						return view('layouts.inspection.lifting.overheadcrane.second.add', [
								'page_name' => $this->page_name(0, $this->page_name),
								'overheadCrane' => $overheadCrane->id
						]);
				}
				else
				{
						return redirect(route('overheadCrane.index'));
				}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, OverheadCrane $overheadCrane)
    {
        $store_overhead_crane2 = OverheadCrane2::create([
            'overhead_crane_id' => $overheadCrane->id,
            'locr2_1' => $request->locr2_1,
            'locr2_2' => $request->locr2_2,
            'locr2_3' => $request->locr2_3,
            'locr2_4' => $request->locr2_4,
            'locr2_5' => $request->locr2_5,
            'locr2_6' => $request->locr2_6,
            'locr2_7' => $request->locr2_7,
            'locr2_8' => $request->locr2_8,
            'locr2_9' => $request->locr2_9,
            'locr2_10' => $request->locr2_10,
            'locr2_11' => $request->locr2_11,
            'locr2_12' => $request->locr2_12,
            'locr2_13' => $request->locr2_13,
            'locr2_14' => $request->locr2_14,
            'locr2_15' => $request->locr2_15,
            'locr2_16' => $request->locr2_16,
            'locr2_17' => $request->locr2_17,
            'locr2_18' => $request->locr2_18,
            'locr2_19' => $request->locr2_19,
            'locr2_20' => $request->locr2_20,
            'locr2_21' => $request->locr2_21,
            'locr2_22' => $request->locr2_22,
            'locr2_23' => $request->locr2_23,
            'locr2_24' => $request->locr2_24,
            'locr2_25' => $request->locr2_25,
            'locr2_26' => $request->locr2_26,
            'locr2_27' => $request->locr2_27,
            'locr2_28' => $request->locr2_28,
            'locr2_29' => $request->locr2_29,
            'locr2_30' => $request->locr2_30,
            'locr2_31' => $request->locr2_31,
            'locr2_32' => $request->locr2_32,
            'locr2_33' => $request->locr2_33,
            'locr2_34' => $request->locr2_34,
            'locr2_35' => $request->locr2_35,
            'locr2_36' => $request->locr2_36,
            'locr2_37' => $request->locr2_37,
            'locr2_38' => $request->locr2_38,
            'locr2_39' => $request->locr2_39,
            'locr2_40' => $request->locr2_40,
            'locr2_41' => $request->locr2_41,
            'locr2_42' => $request->locr2_42,
            'locr2_43' => $request->locr2_43,
        ]);

        if ($store_overhead_crane2)
        {
            return response()->json([
                'success' => $this->action_message(0, $this->page_name)
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OverheadCrane2  $overheadCrane2
     * @return \Illuminate\Http\Response
     */
    public function show(OverheadCrane2 $overheadCrane2)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OverheadCrane2  $overheadCrane2
     * @return \Illuminate\Http\Response
     */
    public function edit(OverheadCrane2 $overheadCrane2, OverheadCrane $overheadCrane)
    {
        return view('layouts.inspection.lifting.overheadcrane.second.edit', [
						'page_name' => $this->page_name(1, $this->page_name),
						'overheadCrane2' => $overheadCrane2,
						'overheadCrane' => $overheadCrane2->overheadCrane->id
				]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OverheadCrane2  $overheadCrane2
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OverheadCrane2 $overheadCrane2, OverheadCrane $overheadCrane)
    {
        $data = [
            'locr2_1' => $request->locr2_1,
            'locr2_2' => $request->locr2_2,
            'locr2_3' => $request->locr2_3,
            'locr2_4' => $request->locr2_4,
            'locr2_5' => $request->locr2_5,
            'locr2_6' => $request->locr2_6,
            'locr2_7' => $request->locr2_7,
            'locr2_8' => $request->locr2_8,
            'locr2_9' => $request->locr2_9,
            'locr2_10' => $request->locr2_10,
            'locr2_11' => $request->locr2_11,
            'locr2_12' => $request->locr2_12,
            'locr2_13' => $request->locr2_13,
            'locr2_14' => $request->locr2_14,
            'locr2_15' => $request->locr2_15,
            'locr2_16' => $request->locr2_16,
            'locr2_17' => $request->locr2_17,
            'locr2_18' => $request->locr2_18,
            'locr2_19' => $request->locr2_19,
            'locr2_20' => $request->locr2_20,
            'locr2_21' => $request->locr2_21,
            'locr2_22' => $request->locr2_22,
            'locr2_23' => $request->locr2_23,
            'locr2_24' => $request->locr2_24,
            'locr2_25' => $request->locr2_25,
            'locr2_26' => $request->locr2_26,
            'locr2_27' => $request->locr2_27,
            'locr2_28' => $request->locr2_28,
            'locr2_29' => $request->locr2_29,
            'locr2_30' => $request->locr2_30,
            'locr2_31' => $request->locr2_31,
            'locr2_32' => $request->locr2_32,
            'locr2_33' => $request->locr2_33,
            'locr2_34' => $request->locr2_34,
            'locr2_35' => $request->locr2_35,
            'locr2_36' => $request->locr2_36,
            'locr2_37' => $request->locr2_37,
            'locr2_38' => $request->locr2_38,
            'locr2_39' => $request->locr2_39,
            'locr2_40' => $request->locr2_40,
            'locr2_41' => $request->locr2_41,
            'locr2_42' => $request->locr2_42,
            'locr2_43' => $request->locr2_43,
        ];

        if ($overheadCrane->report && $overheadCrane->report->user_id_approved != null) {
            $update = DB::transaction(function () use ($overheadCrane, $overheadCrane2, $data) {
                $newOverheadCrane = $overheadCrane->replicate();
                $newOverheadCrane->save();

                $newOverheadCrane2 = $overheadCrane2->replicate();
                $newOverheadCrane2->fill($data);
                $newOverheadCrane2->overhead_crane_id = $newOverheadCrane->id;
                $newOverheadCrane2->save();

                $this->persistInspectionReportState($overheadCrane, [
                    'status' => 1,
                    'publish' => null,
                    'user_id_approved' => null,
                    'reportable_id' => $newOverheadCrane->id,
                    'user_id_edit' => Auth::id(),
                ]);

                return true;
            });
        } else {
            $update = $overheadCrane2->update($data);
        }

        if($update)
        {
            return response()->json([
                'success' => $this->action_message(1, $this->page_name)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OverheadCrane2  $overheadCrane2
     * @return \Illuminate\Http\Response
     */
    public function destroy(OverheadCrane2 $overheadCrane2)
    {
        //
    }
}
