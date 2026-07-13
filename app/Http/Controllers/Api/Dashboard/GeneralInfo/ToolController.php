<?php

namespace App\Http\Controllers\Api\Dashboard\GeneralInfo;

use App\Http\Controllers\Controller;

use App\Models\GeneralInfo\Tool;
use App\Http\Resources\ToolCollection;
use Illuminate\Http\Request;

class ToolController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Tool::all();
        $all   = ToolCollection::collection($model);
        $ids   = ToolCollection::collection(Tool::select('id')->get());
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
     * @param  \App\Models\Tool $tool
     * @return \Illuminate\Http\Response
     */
    public function show(Tool $tool)
    {

    }//end show()


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tool $tool
     * @return \Illuminate\Http\Response
     */
    public function edit(Tool $tool)
    {

    }//end edit()


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Tool         $tool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tool $tool)
    {

    }//end update()


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tool $tool
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tool $tool)
    {

    }//end destroy()


}//end class
