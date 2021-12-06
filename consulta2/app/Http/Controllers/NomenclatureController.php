<?php

namespace App\Http\Controllers;

use App\Models\Nomenclature;
use App\Models\Specialty;
use Illuminate\Http\Request;

class NomenclatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nomenclatures = Nomenclature::all();
        return view('nomenclatures.index')->with(['nomenclatures' => $nomenclatures]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('nomenclatures.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nomenclature = new Nomenclature();
        $nomenclature->code = $request->input('code');
        $nomenclature->description = strtoupper($request->input('description'));
        
        $specialty = Specialty::find($request->input('specialty'));
        $nomenclature->specialty()->associate($specialty);
        $nomenclature->save();
        return redirect('/nomenclatures');
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
        $nomenclature = Nomenclature::find(base64_decode(base64_decode($id)));
        return view('nomenclatures.edit')->with(['nomenclature' => $nomenclature]);
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
        $nomenclature = Nomenclature::find($id);
        $nomenclature->code = $request->input('code');
        $nomenclature->description = strtoupper($request->input('description'));
        
        $specialty = Specialty::find($request->input('specialty'));
        if ($nomenclature->specialty != $specialty) {
            $nomenclature->specialty()->dissociate();
        }
        $nomenclature->specialty()->associate($specialty);
        $nomenclature->save();
        return redirect('/nomenclatures');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $nomenclature = Nomenclature::find($id);
        $nomenclature->destroy($id);
        return redirect('/nomenclatures');
    }
}