<?php

namespace App\Http\Controllers\Dashboard\Organization;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

// App\Models
use App\Models\Organization\Employee;
use App\Models\Organization\Department;

// use Illuminate\Support\Facades\Crypt;
// use Illuminate\Support\Facades\Storage;

// Other
use DB, DataTables, Auth, Storage;


class EmployeeController extends Controller
{
    public $page_name = 'Employee';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Employee::class, 'employee');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::count();
        return view('layouts.organization.employee.index', ['page_name' => $this->page_name('All', $this->page_name), 'employees' => $employees]);
    }

    public function getDataForDataTable(Request $request)
		{
        $data = Employee::query()
            ->with('departments:id,name')
            ->whereHas('departments');
        return  Datatables::eloquent($data)
              ->addColumn('departments', function ($row) {
                    return $row->departments->map(function($value){
                        return "<code>".$value->name."</code>";
                    })->implode(',');
                })
              ->addColumn('action', function ($row) {
                    $btn = "";

                    if (Auth::user()->can('update', Employee::find($row->id)))
                    {
                        $btn .= '<a href="'.route('employee.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                    }

                    if (Auth::user()->can('delete', Employee::find($row->id)))
                    {
                        $btn .= '<button type="button" data-id="'.$row->id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
                    }
                    if ($btn === '') {
                        return '';
                    }
                    return '<div class="wf-inline-actions">'.$btn.'</div>';
                })
              ->editColumn('esign', function($row){
                    $esign = trim((string) ($row->esign ?? ''));
                    if ($esign === '') {
                        return '<span class="text-muted">-</span>';
                    }

                    $url = $row->esign_url;
                    if (empty($url)) {
                        return '<span class="badge badge-light-danger">Missing</span>';
                    }

                    $extension = strtolower((string) pathinfo($esign, PATHINFO_EXTENSION));
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'], true);

                    if ($isImage) {
                        return '<img class="media-object" src="'.$url.'" alt="E-Sign" width="64">';
                    }

                    return '<a href="'.$url.'" target="_blank" class="badge badge-light-primary">Open File</a>';
								})
              ->filterColumn('departments', function ($query, $keyword) {
                    $query->whereHas('departments', function (Builder $builder) use ($keyword) {
                        $builder->where('name', 'like', "%{$keyword}%");
                    });
                })
              ->rawColumns(['esign' ,'departments', 'action'])
              ->make('true');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $last = Employee::orderBy('code', 'desc')->latest()->first();
        $last_id = 'EMP-000';
        if (!$last)
        {
            $last_id;
        }
        else
        {
            $last_id = $last->code;
        }
        return view('layouts.organization.employee.add', [
            'page_name' => $this->page_name(0, $this->page_name),
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
        $path = $request->file('logo');
        $path_crypt = NULL;
        if ($path != NULL)
        {
            $path_crypt = uniqid('esign_', true).'.'.$path->getClientOriginalExtension();
            $this->storeUploadedFileToPublicStorage($path, 'employees', $path_crypt);
        }
        $store_employee = Employee::create([
            'code' => $request->code,
            'name' => $request->uname,
            'email' => $request->uemail,
            'tel' => $request->utel,
            'esign' => $path_crypt,
            'desc' => $request->desc,
        ]);

        if ($store_employee)
        {
            $departments = Department::find(json_decode($request->department));
            $store_employee->departments()->attach($departments);
            return response()->json([
                'success' => $this->action_message(0, $this->page_name)
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return back()->withInput();
        // return Employee::select('id')->where('email', '=' ,'info@rig.com')->first()->id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $employee->loadMissing(['departments', 'user.role']);

        return view('layouts.organization.employee.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'employee' => $employee
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'uname' => ['required', 'string', 'max:255'],
            'uemail' => ['required', 'email', 'max:255', 'unique:employees,email,'.$employee->id],
            'utel' => ['nullable', 'string', 'max:50'],
            'desc' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'upassword' => ['nullable', 'string', 'min:6', 'same:upassword_confirmation'],
            'upassword_confirmation' => ['nullable', 'string', 'min:6'],
        ]);

        $path = $request->file('logo');
        if ($path != NULL)
        {
            $this->deleteEmployeeFileFromAllLocations('employees/'.$employee->esign);
            $path_crypt = uniqid('esign_', true).'.'.$path->getClientOriginalExtension();
            $this->storeUploadedFileToPublicStorage($path, 'employees', $path_crypt);
        }
        else
        {
            if($request->imagedata == '')
            {
                $this->deleteEmployeeFileFromAllLocations('employees/'.$employee->esign);
            }
            $path_crypt = $request->imagedata;
        }

        $avatarPath = $employee->avatar;
        if ($request->hasFile('avatar'))
        {
            if (!empty($avatarPath))
            {
                $this->deleteEmployeeFileFromAllLocations('employees/avatars/'.$avatarPath);
            }

            $avatarFile = $request->file('avatar');
            $avatarPath = uniqid('avatar_', true).'.'.$avatarFile->getClientOriginalExtension();
            $this->storeUploadedFileToPublicStorage($avatarFile, 'employees/avatars', $avatarPath);
        }
        else if ($request->avatardata === '')
        {
            if (!empty($avatarPath))
            {
                $this->deleteEmployeeFileFromAllLocations('employees/avatars/'.$avatarPath);
            }
            $avatarPath = null;
        }

        $update = Employee::where('id', $employee->id)->update([
            'name' => $request->uname,
            'email' => $request->uemail,
            'tel' => $request->utel,
            'esign' => $path_crypt,
            'avatar' => $avatarPath,
            'desc' => $request->desc,
        ]);

        if ($update)
        {
            $employee->departments()->sync(json_decode($request->department));

            $employee->loadMissing('user');
            if ($employee->user && !empty($request->upassword))
            {
                $employee->user->update([
                    'password' => Hash::make($request->upassword),
                ]);
            }

            return response()->json([
                'success' => $this->action_message(1, $this->page_name)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $this->deleteEmployeeFileFromAllLocations('employees/'.$employee->esign);
        $this->deleteEmployeeFileFromAllLocations('employees/avatars/'.$employee->avatar);
        $remove = $employee->forceDelete();
        if ($remove)
        {
            return response()->json([
                'success' => $this->action_message(2, $this->page_name)
            ]);
        }
    }

    private function storeUploadedFileToPublicStorage($file, string $relativeDirectory, string $filename): void
    {
        $relativeDirectory = trim(str_replace('\\', '/', $relativeDirectory), '/');
        $targetDirectory = public_path('storage/'.$relativeDirectory);
        if (!is_dir($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true, true);
        }

        $file->move($targetDirectory, $filename);
    }

    private function deleteEmployeeFileFromAllLocations(?string $relativePath): void
    {
        $relativePath = trim((string)$relativePath);
        if ($relativePath === '') {
            return;
        }

        $relativePath = str_replace('\\', '/', $relativePath);
        Storage::disk('public')->delete($relativePath);

        $publicFilePath = public_path('storage/'.$relativePath);
        if (is_file($publicFilePath)) {
            @unlink($publicFilePath);
        }
    }
}
