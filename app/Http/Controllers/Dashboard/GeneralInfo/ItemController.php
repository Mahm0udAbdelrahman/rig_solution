<?php

namespace App\Http\Controllers\Dashboard\GeneralInfo;
use App\Http\Controllers\Controller;

use App\Models\item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
      $this->authorizeResource(item::class, 'item');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = item::all();
        return view('layouts.item.index', ['page_name' => 'All Items', 'items' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $last = item::orderBy('code', 'desc')->latest()->first();
        $last_id = 'I-000';
        if (!$last){
          $last_id;
        }else{
          $last_id = $last->code;
        }
        return view('layouts.item.add', ['page_name' => 'Create Item', 'last_id' => $last_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $store_item = item::create([
          'code' => $request->code,
          'name' => $request->uname,
          'priceInEgp' => $request->egp,
          'priceInDollar' => $request->dollar,
          'desc' => $request->desc,
      ]);

      if($store_item){
          return response()->json([
              'success' => 'Item Created successfully.'
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(item $item)
    {
        return $item;
    }

    public function showMulti(Request $request)
    {
        return item::find([1,2,3]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(item $item)
    {
        return view('layouts.item.edit', ['page_name' => 'Update Item', 'item' => $item]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, item $item)
    {
      $update = item::where('id', $item->id)->update([
        'code' => $request->code,
        'name' => $request->uname,
        'priceInEgp' => $request->egp,
        'priceInDollar' => $request->dollar,
        'desc' => $request->desc,
      ]);

      if($update){
          return response()->json([
              'success' => 'Item Updated successfully.'
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(item $item)
    {
      $remove_item = item::destroy($item->id);
      if($remove_item){
          return response()->json([
              'success' => 'Item Deleted successfully.'
          ]);
      }
    }
}
