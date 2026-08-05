<?php

namespace App\Http\Controllers\Dashboard\Organization;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

// Illuminate\Support
use Illuminate\Support\Facades\Hash;

// App\Models
use App\Models\User;
use App\Models\Organization\Role;
use App\Models\Organization\Employee;

// Other
use DB, DataTables, Auth;

class UserController extends Controller
{
    public $page_name = 'User';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::count();
        return view('layouts.organization.user.index', ['page_name' => $this->page_name('All', $this->page_name), 'users' => $users]);
    }

    public function getDataForDataTable(Request $request)
		{
        $data = Employee::query()->whereHas('user');
        return  Datatables::eloquent($data)
              ->addColumn('role', function ($row) {
                    $report_code = "";
              			if(Auth::user()->can('view', Role::find($row->user->role->id)))
              			{
              					$report_code .= "<a href=".route('role.show', $row->id).">";
              			}

              			$report_code .= $row->user->role->name;

              			if(Auth::user()->can('view', Role::find($row->user->role->id)))
              			{
              					$report_code .= "</a>";
              			}
              			return $report_code;
                })
              ->addColumn('isSuperAdmin', function ($row) {
                  return $row->user->isSuperAdmin();
                })
              ->addColumn('last_active', function ($row) {
                  return $row->user->last_active_at;
                })
              ->addColumn('status', function ($row) {
                  if ($row->user->is_suspended) {
                      return '<span class="badge badge-danger">موقوف</span>';
                  }
                  return $row->user->isActive();
                })
              ->addColumn('action', function ($row) {
                    $btn = "";

                    if (Auth::user()->can('update', User::find($row->user->id)))
                    {
                        $btn .= '<a href="'.route('user.edit', $row->user->id).'" class="btn btn-icon btn-info mr-1" title="Edit"><i class="la la-pencil"></i></a>';
                        
                        if (!$row->user->isSuperAdmin()) {
                            if ($row->user->is_suspended) {
                                $btn .= '<button type="button" data-id="'.$row->user->id.'" class="btn btn-icon btn-success toggle-status mr-1" title="تفعيل الحساب"><i class="la la-check-circle"></i></button>';
                            } else {
                                $btn .= '<button type="button" data-id="'.$row->user->id.'" class="btn btn-icon btn-warning toggle-status mr-1" title="إيقاف الحساب"><i class="la la-ban"></i></button>';
                            }
                        }
                    }

                    if (Auth::user()->can('delete', User::find($row->user->id)))
                    {
                        $btn .= '<button type="button" data-id="'.$row->user->id.'" class="btn btn-icon btn-danger delete" title="Delete"><i class="la la-trash"></i></button>';
                    }
                    return $btn;
                })
              ->editColumn('name', function($row){
										$report_code = "";
										if(Auth::user()->can('view', Employee::find($row->id)))
										{
												$report_code .= "<a href=".route('employee.show', $row->id).">";
										}

										$report_code .= $row->name;

										if(Auth::user()->can('view', Employee::find($row->id)))
										{
												$report_code .= "</a>";
										}
										return $report_code;
								})
              ->rawColumns(['name', 'role', 'status', 'action'])
              ->make('true');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::doesntHave('user')->get();
        $roles = DB::table('roles')->select('id', 'name')->get();
        return view('layouts.organization.user.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'employees' => $employees,
            'roles' => $roles
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

        $store_user = User::create([
            'employee_id' => $request->employee1,
            'password' => Hash::make($request->upassword),
            'role_id' => $request->role,
            'is_super_admin' => $request->superadmin,
        ]);
        if ($store_user)
        {
            return response()->json([
                'success' => $this->action_message(0, $this->page_name)
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return back()->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = DB::table('roles')->select('id', 'name')->get();
        return view('layouts.organization.user.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $password = $request->upassword;
        if($password != NULL)
        {
            $password = Hash::make($password);
        }
        else
        {
            $password = $user->password;
        }
        $update = User::where('id', $user->id)->update([
          'password' => $password,
          'role_id' => $request->role,
          'is_super_admin' => $request->superadmin,
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $remove = $user->forceDelete();
        if ($remove)
        {
            return response()->json([
                'success' => $this->action_message(2, $this->page_name)
            ]);
        }
    }

    /**
     * Toggle individual user suspension status.
     */
    public function toggleStatus(User $user)
    {
        if (Auth::user()->cannot('update', $user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($user->isSuperAdmin()) {
            return response()->json(['error' => 'لا يمكن إيقاف حساب Super Admin'], 403);
        }

        $user->is_suspended = !$user->is_suspended;
        $user->save();

        $message = $user->is_suspended ? 'تم إيقاف الحساب بنجاح' : 'تم تفعيل الحساب بنجاح';
        return response()->json([
            'success' => $message,
            'is_suspended' => $user->is_suspended
        ]);
    }

    /**
     * Bulk suspend or activate all users.
     */
    public function bulkToggleStatus(Request $request)
    {
        $action = $request->input('action');
        if (!in_array($action, ['suspend_all', 'activate_all'], true)) {
            return response()->json(['error' => 'إجراء غير صالح'], 400);
        }

        if ($action === 'suspend_all') {
            User::where('id', '!=', Auth::id())
                ->where('is_super_admin', '!=', 1)
                ->update(['is_suspended' => 1]);

            return response()->json(['success' => 'تم إيقاف جميع الحسابات بنجاح']);
        } else {
            User::query()->update(['is_suspended' => 0]);

            return response()->json(['success' => 'تم تفعيل جميع الحسابات بنجاح']);
        }
    }
}
