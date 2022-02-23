<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all()->toQuery();
        $countries = $countries->sortable()->paginate(10);
        return view('countries.index', ['countries' => $countries]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('countries.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|filled|max:70',
            'code' => 'required|numeric|max:1000'
        ]);
        $country = new Country();
        $country->name = $request->name;
        $country->code = $request->code;
        $country->save();
        return redirect('/countries');
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
        $country = Country::find(base64_decode(base64_decode($id)));
        return view('countries.edit', ['country' => $country]);
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
        $request->validate([
            'name' => 'required|string|filled|max:70',
            'code' => 'required|numeric|max:1000'
        ]);
        $country = Country::find($id);
        $country->name = $request->name;
        $country->code = $request->code;
        $country->save();
        return redirect('/countries');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Country::destroy($id);
        return redirect('/countries');
    }

    public function getProvinces(Request $request) {
        if ($request->ajax()) {
            $country = Country::find($request->id);
            $provinces = $country->provinces->toArray();
            if (count($provinces) > 0) {
                return response()->json($provinces);
            } else {
                return response()->json(['status' => 'error']);
            }
        }
       
    }
}
