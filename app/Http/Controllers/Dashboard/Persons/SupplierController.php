<?php

namespace App\Http\Controllers\Dashboard\Persons;
use App\Http\Controllers\Controller;

// Illuminate\Http
use Illuminate\Http\Request;

// App\Models
use App\Models\Persons\Supplier;

// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Crypt;
// use Illuminate\Support\Facades\Storage;

// Other
use DB, DataTables, Auth, Storage, Crypt, Hash;

class SupplierController extends Controller
{
    public $page_name = 'Supplier';
    public $person_type = 's';

    public function __construct()
    {
      $this->middleware('auth');
      $this->authorizeResource(Supplier::class, 'supplier');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
         $clients = Supplier::count();
         return view('layouts.persons.client.index', [
             'page_name' => $this->page_name('All', $this->page_name),
             'clients' => $clients,
             'route' => 'supplier',
             'model' => 'App\Models\Persons\Supplier',
             'type' => $this->person_type,
         ]);
     }

     public function getDataForDataTable()
 		{
         $data = Supplier::query();
         return  Datatables::eloquent($data)
               ->addColumn('action', function ($row) {
                     $btn = "";

                     if (Auth::user()->can('view', Supplier::find($row->id)))
                     {
                         $btn .= '<a href="'.route('supplier.show', $row->id).'" class="btn btn-icon btn-success mr-1 btn11">Contact Persons</a>';
                     }

                     if (Auth::user()->can('update', Supplier::find($row->id)))
                     {
                         $btn .= '<a href="'.route('supplier.edit', $row->id).'" class="btn btn-icon btn-info mr-1"><i class="la la-pencil"></i></a>';
                     }

                     if (Auth::user()->can('delete', Supplier::find($row->id)))
                     {
                         $btn .= '<button type="button" data-id="'.$row->id.'" class="btn btn-icon btn-danger delete"><i class="la la-trash"></i></button>';
                     }
                     if ($btn === '') {
                         return '';
                     }
                     return '<div class="wf-inline-actions">'.$btn.'</div>';
                 })
               ->editColumn('logo', function($row){
 										return '<img class="media-object" src="'.Storage::url('persons/suppliers/').$row->logo.'" alt="" width="64">';
 								})
              ->editColumn('code', function($row){
                    $report_code = "";
                    if(Auth::user()->can('view', Supplier::find($row->id)))
                    {
                        $report_code .= "<a href=".route('supplier.show', $row->id).">";
                    }

                    $report_code .= $row->code;

                    if(Auth::user()->can('view', Supplier::find($row->id)))
                    {
                        $report_code .= "</a>";
                    }
                    return $report_code;
								})
               ->rawColumns(['logo', 'code' ,'action'])
               ->make('true');
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $last = Supplier::orderBy('id', 'desc')->latest()->first();
        $last_id = 'SUP-000';
        if (!$last)
        {
            $last_id;
        }
        else
        {
            $last_id = $last->code;
        }
        return view('layouts.persons.client.add', [
            'page_name' => $this->page_name(0, $this->page_name),
            'last_id' => $last_id,
            'route' => 'supplier.store'
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
            $path_crypt = Crypt::encryptString($path->getClientOriginalName());
            $store = $path->storeAs(
                'public/persons/suppliers',
                $path_crypt
            );
        }

        $store_supplier = Supplier::create([
            'code' => $request->code,
            'name' => $request->uname,
            'email' => $request->uemail,
            'tel' => $request->utel,
            'location' => $request->address,
            'tax_card' => $request->tax_card,
            'fax' => $request->fax,
            'url' => $request->url,
            'desc' => $request->desc,
            'logo' => $path_crypt,
        ]);

        if ($store_supplier)
        {
            return response()->json([
                'success' => $this->action_message(0, $this->page_name)
            ]);
        }
    }

    public function contactPersonStore(Request $request, Supplier $supplier)
    {
        $store_contact_person = $supplier->conatctPerson()->create([
            'name' => $request->sname,
            'email' => $request->semail,
            'tel' => $request->stel,
            'postion' => $request->stitle,
        ]);

        if ($store_contact_person)
        {
            return response()->json([
                'success' => 'Contact Person Created successfully.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        return view('layouts.persons.supplier.show', ['page_name' => $this->page_name, 'route' => 'supplier', 'client' => $supplier, 'supplier' => $supplier]);
    }

    public function contactPersonShow(Supplier $supplier)
    {
        return response()->json([
            'contactperson' => $supplier->conatctPerson,
            'code' => $supplier->code,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('layouts.persons.client.edit', [
            'page_name' => $this->page_name(1, $this->page_name),
            'route' => 'supplier.update',
            'client' => $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $password = $request->password;
        if ($password != NULL)
        {
            $password = Hash::make($password);
        }
        else
        {
            $password = $supplier->password;
        }

        $path = $request->file('logo');
        if ($path != NULL)
        {
            Storage::disk('public')->delete('persons/suppliers/'.$supplier->logo);
            $path_crypt = Crypt::encryptString($path->getClientOriginalName());
            $store = $path->storeAs(
                'public/persons/suppliers',
                $path_crypt
            );
        }
        else
        {
            if($request->imagedata == '')
            {
                Storage::disk('public')->delete('persons/suppliers/'.$supplier->logo);
            }
            $path_crypt = $request->imagedata;
        }

        $update = $supplier->update([
            'name' => $request->uname,
            'password' => Hash::make($request->password),
            'email' => $request->uemail,
            'tel' => $request->utel,
            'location' => $request->address,
            'tax_card' => $request->tax_card,
            'fax' => $request->fax,
            'url' => $request->url,
            'desc' => $request->desc,
            'logo' => $path_crypt,
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
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        if (Storage::disk('public')->exists('persons/suppliers/'.$supplier->logo))
        {
            Storage::disk('public')->delete('persons/suppliers/'.$supplier->logo);
        }
        $remove = $supplier->forceDelete();
        if ($remove)
        {
            return response()->json([
                'success' => $this->action_message(2, $this->page_name)
            ]);
        }
    }
}
