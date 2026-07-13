<?php

namespace App\Http\Controllers\Dashboard\GeneralInfo;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\GeneralInfo\Tool;

// Other
use DB, DataTables, Auth;

class ToolController extends Controller
{
    public $page_name = 'Tool';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Tool::class, 'tool');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Tool::count();
        return view('layouts.organization.department.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'departments' => $departments,
            'route' => 'tool',
            'model' => 'App\Models\GeneralInfo\Tool'
        ]);
    }

    public function getDataForDataTable()
		{
        $data = Tool::query();
        return  Datatables::eloquent($data)
              ->addColumn('action', function ($row) {
                    $btn = "";

                    if (Auth::user()->can('update', Tool::find($row->id)))
                    {
                        $btn .= '<a href="'.route('tool.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }

                    if (Auth::user()->can('delete', Tool::find($row->id)))
                    {
                        $btn .= '<button type="button" data-id="'.$row->id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
                    }
                    return $btn;
                })
              ->rawColumns(['action'])
              ->make('true');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $last = Tool::orderBy('code', 'desc')->latest()->first();
        $last_id = 'T-000';
        if (!$last)
        {
            $last_id;
        }
        else
        {
            $last_id = $last->code;
        }
        return view('layouts.organization.department.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'route' => 'tool.store',
            'last_id' => $last_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $store_tool = Tool::create([
            'code' => $request->code,
            'name' => $request->uname,
            'desc' => $request->desc,
        ]);

        if($store_tool)
        {
            return response()->json([
                'success' => $this->action_message(0, $this->page_name)
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function show(Tool $tool)
    {
        return back()->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function edit(Tool $tool)
    {
        return view('layouts.organization.department.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'route' => 'tool.update',
            'department' => $tool
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tool $tool)
    {
        $update = $tool->update([
            'code' => $request->code,
            'name' => $request->uname,
            'desc' => $request->desc,
        ]);

        if ($update)
        {
            return response()->json([
                'success' => $this->action_message(1, $this->page_name)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tool $tool)
    {
        $remove = $tool->forceDelete();
        if ($remove)
        {
            return response()->json([
                'success' => $this->action_message(2, $this->page_name)
            ]);
        }
    }
}
