<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = Province::all()->toQuery();
        $provinces = $provinces->sortable()->paginate(10);
        return view('provinces.index', ['provinces' => $provinces]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('provinces.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $province = new Province();
        $province->name = $request->name;
        $province->country_id = $request->country_id;
        $province->save();
        return redirect('/provinces');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $province = Province::find(base64_decode(base64_decode($id)));
        return view('provinces.edit', ['province' => $province]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $province = Province::find($id);
        $province->name = $request->name;
        $province->country_id = $request->country_id;
        $province->save();
        return redirect('/provinces');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Province::destroy($id);
        return redirect('/provinces');
    }

    public function getCities(Request $request) {
        if ($request->ajax()) {
            $province = Province::find($request->id);
            $cities = $province->cities->toArray();
            return response()->json($cities);
        }
    }
}
