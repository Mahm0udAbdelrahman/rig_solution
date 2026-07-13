<?php

namespace App\Http\Controllers\Dashboard\Organization;
use App\Http\Controllers\Controller;

// Illuminate\Http
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

// Illuminate\Support
use Illuminate\Support\Facades\Auth;

// App\Models
use App\Models\Organization\Role;

// Other
use DB, DataTables;

class RoleController extends Controller
{
    public $page_name = 'Role';

    public function __construct()
    {
	      $this->middleware('auth');
	      $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::count();
        return view('layouts.organization.role.index', ['roles' => $roles, 'page_name' => $this->page_name('All', $this->page_name)]);
    }

    public function getDataForDataTable()
		{
        $data = DB::table('roles')->select('id', 'name');
        return  Datatables::of($data)
              ->addColumn('action', function ($row) {
                    $btn = "";

                    if (Auth::user()->can('update', Role::find($row->id)))
                    {
                        $btn .= '<a href="'.route('role.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }

                    if (Auth::user()->can('delete', Role::find($row->id)))
                    {
                        $btn .= '<button type="button" data-id="'.$row->id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
                    }
                    return $btn;
                })
              ->editColumn('name', function($row){
										$report_code = "";
										if(Auth::user()->can('view', Role::find($row->id)))
										{
												$report_code .= "<a href=".route('role.show', $row->id).">";
										}

										$report_code .= $row->name;

										if(Auth::user()->can('view', Role::find($row->id)))
										{
												$report_code .= "</a>";
										}
										return $report_code;
								})
              ->rawColumns(['name', 'action'])
              ->make('true');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('layouts.organization.role.add', ['page_name' => $this->page_name(0, $this->page_name)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        $store_role = Role::create([
            'name' => $request->uname,
            'roles' => $request->permissions,
        ]);
        if ($store_role)
        {
            return response()->json([
                'success' => $this->action_message(0, $this->page_name)
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return view('layouts.organization.role.show', [
            'role' => $role,
            'page_name' => $this->page_name
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('layouts.organization.role.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'role' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRoleRequest  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
      $update = Role::where('id', $role->id)->update([
        'name' => $request->uname,
        'roles' => $request->permissions,
      ]);

      if($update){
          return response()->json([
              'success' => $this->action_message(1, $this->page_name)
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
      $remove = $role->forceDelete();
      if($remove){
          return response()->json([
              'success' => $this->action_message(2, $this->page_name)
          ]);
      }
    }
}
