<?php

namespace App\Http\Controllers\Dashboard\Inspection\Lifting;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// App\Models
use App\Models\WorkFlow\JobRequest;
use App\Models\Inspection\Lifting\Crane;
use App\Models\Inspection\Lifting\Crane2;

// Other
use DB;

class Crane2Controller extends Controller
{
		public $page_name = 'Crane 2 Report';
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
    public function create(Crane $crane)
    {
				if (!$crane->crane2)
				{
						return view('layouts.inspection.lifting.crane.second.add', [
								'page_name' => $this->page_name(0, $this->page_name),
								'crane' => $crane->id
						]);
				}
				else
				{
						return redirect(route('crane.index'));
				}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Crane $crane)
    {
	      $store_crane2 = Crane2::create([
		        'crane_id' => $crane->id,
		        'lcr2_1' => $request->lcr2_1,
		        'lcr2_2' => $request->lcr2_2,
		        'lcr2_3' => $request->lcr2_3,
		        'lcr2_4' => $request->lcr2_4,
		        'lcr2_5' => $request->lcr2_5,
		        'lcr2_6' => $request->lcr2_6,
		        'lcr2_7' => $request->lcr2_7,
		        'lcr2_8' => $request->lcr2_8,
		        'lcr2_9' => $request->lcr2_9,
		        'lcr2_10' => $request->lcr2_10,
		        'lcr2_11' => $request->lcr2_11,
		        'lcr2_12' => $request->lcr2_12,
		        'lcr2_13' => $request->lcr2_13,
		        'lcr2_14' => $request->lcr2_14,
		        'lcr2_15' => $request->lcr2_15,
		        'lcr2_16' => $request->lcr2_16,
		        'lcr2_17' => $request->lcr2_17,
		        'lcr2_18' => $request->lcr2_18,
		        'lcr2_19' => $request->lcr2_19,
		        'lcr2_20' => $request->lcr2_20,
		        'lcr2_21' => $request->lcr2_21,
		        'lcr2_22' => $request->lcr2_22,
		        'lcr2_23' => $request->lcr2_23,
		        'lcr2_24' => $request->lcr2_24,
		        'lcr2_25' => $request->lcr2_25,
		        'lcr2_26' => $request->lcr2_26,
		        'lcr2_27' => $request->lcr2_27,
		        'lcr2_28' => $request->lcr2_28,
		        'lcr2_29' => $request->lcr2_29,
		        'lcr2_30' => $request->lcr2_30,
		        'lcr2_31' => $request->lcr2_31,
		        'lcr2_32' => $request->lcr2_32,
		        'lcr2_33' => $request->lcr2_33,
		        'lcr2_34' => $request->lcr2_34,
		        'lcr2_35' => $request->lcr2_35,
		        'lcr2_36' => $request->lcr2_36,
		        'lcr2_37' => $request->lcr2_37,
		        'lcr2_38' => $request->lcr2_38,
		        'lcr2_39' => $request->lcr2_39,
		        'lcr2_40' => $request->lcr2_40,
		        'lcr2_41' => $request->lcr2_41,
		        'lcr2_42' => $request->lcr2_42,
		        'lcr2_43' => $request->lcr2_43,
	      ]);

	      if ($store_crane2)
				{
	          return response()->json([
	              'success' => $this->action_message(0, $this->page_name)
	          ]);
	      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Crane2  $crane2
     * @return \Illuminate\Http\Response
     */
    public function show(Crane2 $crane2)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Crane2  $crane2
     * @return \Illuminate\Http\Response
     */
    public function edit(Crane2 $crane2, Crane $crane)
    {
        return view('layouts.inspection.lifting.crane.second.edit', [
						'page_name' => $this->page_name(1, $this->page_name),
						'crane2' => $crane2,
						'crane' => $crane2->crane->id
				]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Crane2  $crane2
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Crane2 $crane2, Crane $crane)
    {
        $data = [
            'lcr2_1' => $request->lcr2_1,
            'lcr2_2' => $request->lcr2_2,
            'lcr2_3' => $request->lcr2_3,
            'lcr2_4' => $request->lcr2_4,
            'lcr2_5' => $request->lcr2_5,
            'lcr2_6' => $request->lcr2_6,
            'lcr2_7' => $request->lcr2_7,
            'lcr2_8' => $request->lcr2_8,
            'lcr2_9' => $request->lcr2_9,
            'lcr2_10' => $request->lcr2_10,
            'lcr2_11' => $request->lcr2_11,
            'lcr2_12' => $request->lcr2_12,
            'lcr2_13' => $request->lcr2_13,
            'lcr2_14' => $request->lcr2_14,
            'lcr2_15' => $request->lcr2_15,
            'lcr2_16' => $request->lcr2_16,
            'lcr2_17' => $request->lcr2_17,
            'lcr2_18' => $request->lcr2_18,
            'lcr2_19' => $request->lcr2_19,
            'lcr2_20' => $request->lcr2_20,
            'lcr2_21' => $request->lcr2_21,
            'lcr2_22' => $request->lcr2_22,
            'lcr2_23' => $request->lcr2_23,
            'lcr2_24' => $request->lcr2_24,
            'lcr2_25' => $request->lcr2_25,
            'lcr2_26' => $request->lcr2_26,
            'lcr2_27' => $request->lcr2_27,
            'lcr2_28' => $request->lcr2_28,
            'lcr2_29' => $request->lcr2_29,
            'lcr2_30' => $request->lcr2_30,
            'lcr2_31' => $request->lcr2_31,
            'lcr2_32' => $request->lcr2_32,
            'lcr2_33' => $request->lcr2_33,
            'lcr2_34' => $request->lcr2_34,
            'lcr2_35' => $request->lcr2_35,
            'lcr2_36' => $request->lcr2_36,
            'lcr2_37' => $request->lcr2_37,
            'lcr2_38' => $request->lcr2_38,
            'lcr2_39' => $request->lcr2_39,
            'lcr2_40' => $request->lcr2_40,
            'lcr2_41' => $request->lcr2_41,
            'lcr2_42' => $request->lcr2_42,
            'lcr2_43' => $request->lcr2_43,
        ];

        if ($crane->report && $crane->report->user_id_approved != null) {
            $update = DB::transaction(function () use ($crane, $crane2, $data) {
                $newCrane = $crane->replicate();
                $newCrane->save();

                $newCrane2 = $crane2->replicate();
                $newCrane2->fill($data);
                $newCrane2->crane_id = $newCrane->id;
                $newCrane2->save();

                $this->persistInspectionReportState($crane, [
                    'status' => 1,
                    'publish' => null,
                    'user_id_approved' => null,
                    'reportable_id' => $newCrane->id,
                    'user_id_edit' => Auth::id(),
                ]);

                return true;
            });
        } else {
            $update = $crane2->update($data);
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
     * @param  \App\Models\Crane2  $crane2
     * @return \Illuminate\Http\Response
     */
    public function destroy(Crane2 $crane2)
    {
        //
    }
}
