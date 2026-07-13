<?php

namespace App\Http\Controllers\Api\Dashboard\GeneralInfo;

use App\Http\Controllers\Controller;

use App\Models\GeneralInfo\Specification;
use App\Http\Resources\ToolCollection;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Specification::all();
        $all   = ToolCollection::collection($model);
        $ids   = ToolCollection::collection(Specification::select('id')->get());
        return response()->json(
            [
                'all' => $all,
                'ids' => $ids,
            ]
        );

    }//end index()


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }//end create()


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }//end store()


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Specification $specification
     * @return \Illuminate\Http\Response
     */
    public function show(Specification $specification)
    {

    }//end show()


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Specification $specification
     * @return \Illuminate\Http\Response
     */
    public function edit(Specification $specification)
    {

    }//end edit()


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Specification $specification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Specification $specification)
    {

    }//end update()


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Specification $specification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Specification $specification)
    {

    }//end destroy()


}//end class
