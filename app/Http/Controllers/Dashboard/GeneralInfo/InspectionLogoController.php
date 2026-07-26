<?php

namespace App\Http\Controllers\Dashboard\GeneralInfo;

use App\Http\Controllers\Controller;
use App\Models\GeneralInfo\InspectionLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class InspectionLogoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inspectionLogos = InspectionLogo::all();
		// dd($inspectionLogos[0]->related_inspections);
        return view('layouts.organization.inspectionLogo.index', [
            'page_name' => 'All Custom Header Logos',
            'dataList' => $inspectionLogos,
			'inspectionModels' => InspectionLogo::getAllInspectionModels()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$inspectionOptions = InspectionLogo::getAllInspectionModels();
		// dd($inspectionOptions);
        return view('layouts.organization.inspectionLogo.create', [
            'page_name' => 'Create Header Logo',
            'inspectionModels' => InspectionLogo::getAllInspectionModels()
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
        $data = $request->all(['name', 'related_inspections']);
        if (!$request->hasFile('logo')) {
            return response()->json(['error' => 'Logo file is required'], 422);
        }
		$data['logo'] = $request->file('logo')->store('images/inspection-logos', 'public');
        InspectionLogo::create($data);
        return redirect()->route('inspectionLogo.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd("show", $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(InspectionLogo $inspectionLogo)
    {
        return view('layouts.organization.inspectionLogo.edit', [
            'model' => $inspectionLogo,
            'page_name' => 'Edit Header Logo',
			'inspectionModels' => InspectionLogo::getAllInspectionModels($inspectionLogo)

        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InspectionLogo $inspectionLogo)
    {
        $data = $request->all();
		if ($request->hasFile('logo')) {
			$data['logo'] = $request->file('logo')->store('images/inspection-logos', 'public');
        }
        $inspectionLogo->update($data);
        return response()->redirectToRoute('inspectionLogo.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd($id);
    }
}
