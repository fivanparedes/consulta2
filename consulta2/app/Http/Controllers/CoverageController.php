<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Coverage;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Covers;

class CoverageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coverages = Coverage::all();
        return view('coverages.index')->with(['coverages' => $coverages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('coverages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $coverage = new Coverage();
        $coverage->name = $request->input('name');
        $coverage->address = $request->input('address');
        $coverage->phone = $request->input('phone');
        $city = City::find($request->input('city'));
        $coverage->city()->associate($city);
        $coverage->save();
        return redirect('/coverages');
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
        $coverage = Coverage::find(base64_decode(base64_decode($id)));
        return view('coverages.edit')->with(['coverage' => $coverage]);
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
        $coverage = Coverage::find($id);
        $coverage->name = $request->input('name');
        $coverage->address = $request->input('address');
        $coverage->phone = $request->input('phone');
        $city = City::find($request->input('city'));
        $coverage->city()->dissociate();
        $coverage->city()->associate($city);
        $coverage->save();
        return redirect('/coverages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Coverage::destroy($id);
        return redirect('/coverage');
    }
}
