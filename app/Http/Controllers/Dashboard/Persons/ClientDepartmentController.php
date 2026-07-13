<?php

namespace App\Http\Controllers\Dashboard\Persons;

use App\Models\Persons\ClientDepartment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Crypt, Hash;


class ClientDepartmentController extends Controller
{
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Persons\ClientDepartment  $clientDepartment
     * @return \Illuminate\Http\Response
     */
    public function show(ClientDepartment $clientDepartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Persons\ClientDepartment  $clientDepartment
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientDepartment $clientDepartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Persons\ClientDepartment  $clientDepartment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientDepartment $clientDepartment)
    {
        $update = ClientDepartment::where('id', $clientDepartment->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'description' => $request->description,
        ]);
        if($update){
            return response()->json([
                'success' => 'Client Department Updated successfully.'
            ]);
        }
    }

    public function updatePassword(Request $request, ClientDepartment $clientDepartment)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $clientDepartment->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => 'Client Department password updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Persons\ClientDepartment  $clientDepartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientDepartment $clientDepartment)
    {
        ClientDepartment::destroy($clientDepartment->id);
        return response()->json([
            'success' => 'Client Department Deleted successfully.'
        ]);
    }
}
