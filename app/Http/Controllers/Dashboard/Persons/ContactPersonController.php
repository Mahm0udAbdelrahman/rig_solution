<?php

namespace App\Http\Controllers\Dashboard\Persons;
use App\Http\Controllers\Controller;

use App\Models\Persons\ContactPerson;
use Illuminate\Http\Request;

class ContactPersonController extends Controller
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
     * @param  \App\Models\ContactPerson  $contactPerson
     * @return \Illuminate\Http\Response
     */
    public function show(ContactPerson $contactPerson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactPerson  $contactPerson
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactPerson $contactPerson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactPerson  $contactPerson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactPerson $contactPerson)
    {
      $update = ContactPerson::where('id', $contactPerson->id)->update([
        'name' => $request->name,
        'email' => $request->email,
        'tel' => $request->tel,
        'postion' => $request->	postion,
      ]);
      if($update){
          return response()->json([
              'success' => 'Contact Person Updated successfully.'
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactPerson  $contactPerson
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactPerson $contactPerson)
    {
        ContactPerson::destroy($contactPerson->id);
        return response()->json([
            'success' => 'Contact Person Deleted successfully.'
        ]);
    }
}
