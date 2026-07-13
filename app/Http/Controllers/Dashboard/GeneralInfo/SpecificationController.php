<?php

namespace App\Http\Controllers\Dashboard\GeneralInfo;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\GeneralInfo\Specification;

// Other
use DB, DataTables, Auth;

class SpecificationController extends Controller
{
    public $page_name = 'Specification';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Specification::class, 'specification');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Specification::count();
        return view('layouts.organization.department.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'departments' => $departments,
            'route' => 'specification',
            'model' => 'App\Models\GeneralInfo\Specification'
        ]);
    }

    public function getDataForDataTable()
		{
        $data = Specification::query();
        return  Datatables::eloquent($data)
              ->addColumn('action', function ($row) {
                    $btn = "";

                    if (Auth::user()->can('update', Specification::find($row->id)))
                    {
                        $btn .= '<a href="'.route('specification.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }

                    if (Auth::user()->can('delete', Specification::find($row->id)))
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
        $last = Specification::orderBy('code', 'desc')->latest()->first();
        $last_id = 'S-000';
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
            'route' => 'specification.store',
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
        $store_specification = Specification::create([
            'code' => $request->code,
            'name' => $request->uname,
            'desc' => $request->desc,
        ]);

        if($store_specification)
        {
            return response()->json([
                'success' => $this->action_message(0, $this->page_name)
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function show(Specification $specification)
    {
        return back()->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function edit(Specification $specification)
    {
        return view('layouts.organization.department.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'route' => 'specification.update',
            'department' => $specification
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Specification $specification)
    {
        $update = $specification->update([
            'code' => $request->code,
            'name' => $request->uname,
            'desc' => $request->desc,
        ]);

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
     * @param  \App\Models\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Specification $specification)
    {
        $remove = $specification->forceDelete();
        if ($remove)
        {
            return response()->json([
                'success' => $this->action_message(2, $this->page_name)
            ]);
        }
    }
}
