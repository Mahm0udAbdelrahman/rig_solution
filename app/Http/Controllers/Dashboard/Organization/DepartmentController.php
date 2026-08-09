<?php

namespace App\Http\Controllers\Dashboard\Organization;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

// App\Models
use App\Models\Organization\Department;
use App\Models\Organization\Employee;

// Illuminate\Support
use Illuminate\Support\Collection;

// Other
use DB, DataTables, Auth;

class DepartmentController extends Controller
{
    public $page_name = 'Department';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Department::class, 'department');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::count();
        return view('layouts.organization.department.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'route' => 'department',
            'model' => 'App\Models\Organization\Department',
            'departments' => $departments
        ]);
    }

    public function getDataForDataTable()
		{
        $data = Department::query()->whereHas('employee');
        return  Datatables::eloquent($data)
              ->addColumn('supervisor', function ($row) {
                  return $row->employee->name;
                })
              ->addColumn('action', function ($row) {
                    $btn = "";

                    if (Auth::user()->can('update', Department::find($row->id)))
                    {
                        $btn .= '<a href="'.route('department.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }

                    if (Auth::user()->can('delete', Department::find($row->id)))
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
        $last = Department::orderBy('code', 'desc')->latest()->first();
        $last_id = 'D-000';
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
            'route' => 'department.store',
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
        $store_department = Department::create([
            'code' => $request->code,
            'name' => $request->uname,
            'employee_id' => $request->employee1,
            'desc' => $request->desc,
        ]);

        if ($store_department)
        {
            return response()->json([
                'success' => $this->action_message(0, $this->page_name)
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        return back()->withInput();
    }

    public function showManagers(Request $request)
    {
        $departmentIds = collect((array) $request->department)
            ->filter()
            ->map(function ($value) {
                return (int) $value;
            })
            ->values();

        $managerNames = collect();
        $inspectorNames = collect();
        $assistantNames = collect();

        if ($departmentIds->isNotEmpty()) {
            $departments = Department::query()
                ->whereIn('id', $departmentIds)
                ->with(['employee', 'employees'])
                ->get();

            foreach ($departments as $department) {
                if ($department->employee && $department->employee->name) {
                    $managerNames->push($department->employee->name);
                }

                foreach ($department->employees as $employee) {
                    if ($employee->name) {
                        if ($employee->is_assistant) {
                            $assistantNames->push($employee->name);
                        } else {
                            $inspectorNames->push($employee->name);
                        }
                    }
                }
            }
        }

        // Fresh databases may have no explicit department members yet.
        if ($managerNames->isEmpty()) {
            $firstEmployeeName = Employee::query()->orderBy('id')->value('name');
            if ($firstEmployeeName) {
                $managerNames->push($firstEmployeeName);
            }
        }

        // If no engineers are linked yet, expose managers so Step 2 is usable.
        if ($inspectorNames->isEmpty()) {
            $nonAssistants = Employee::query()
                ->where(function ($q) {
                    $q->where('is_assistant', 0)->orWhereNull('is_assistant');
                })
                ->pluck('name');

            if ($nonAssistants->isNotEmpty()) {
                $inspectorNames = $nonAssistants;
            } else {
                $inspectorNames = $managerNames;
            }
        }

        if ($assistantNames->isEmpty()) {
            $allAssistants = Employee::query()
                ->where('is_assistant', 1)
                ->pluck('name');

            if ($allAssistants->isNotEmpty()) {
                $assistantNames = $allAssistants;
            }
        }

        return response()->json([
            'managers' => $managerNames->unique()->values(),
            'employees' => $inspectorNames->unique()->values(),
            'assistants' => $assistantNames->unique()->values(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        return view('layouts.organization.department.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'route' => 'department.update',
            'department' => $department
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $update = $department->update([
            'code' => $request->code,
            'name' => $request->uname,
            'employee_id' => $request->employee1,
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
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $remove = $department->forceDelete();
        if ($remove)
        {
            return response()->json([
                'success' => $this->action_message(2, $this->page_name)
            ]);
        }
    }
}
