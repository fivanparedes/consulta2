<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::all()->toQuery();
        $cities = $cities->sortable()->paginate(10);
        return view('cities.index', ['cities' => $cities]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $city = new City();
        $city->name = $request->name;
        $city->zipcode = $request->zipcode;
        $city->province_id = $request->province_id;
        $city->save();
        return redirect('/cities');
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
        $city = City::find(base64_decode(base64_decode($id)));
        return view('cities.edit', ['city' => $city]);
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
        $city = City::find($id);
        $city->name = $request->name;
        $city->zipcode = $request->zipcode;
        $city->province_id = $request->province_id;
        $city->save();
        return redirect('/cities');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        City::destroy($id);
        return redirect('/cities');
    }

    public function getInstitutions(Request $request) {
        if ($request->ajax()) {
            $city = City::find($request->id);
            $institutions = $city->institutions->toArray();
            return response()->json($institutions);
        }
    }
}
