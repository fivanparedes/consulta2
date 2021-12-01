<?php

namespace App\Http\Controllers;

use App\Models\Coverage;
use App\Models\Nomenclature;
use App\Models\Practice;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laratrust;

class PracticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = Auth::user();
        if (!$admin->hasRole('Admin')) {
            return abort(403);
        }
        $practices = Practice::all();
        return view('practices.index')->with(['practices' => $practices]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->hasRole('Admin')) {
            return abort(403);
        }
        $coverages = Coverage::all();
        $nomenclatures = Nomenclature::all();
        return view('practices.create')->with([
            'coverages' => $coverages,
            'nomenclatures' => $nomenclatures
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
        $practice = new Practice();
        $practice->name = $request->input('name');
        $practice->maxtime = $request->input('maxtime');
        $practice->description = $request->input('description');
        
        $coverage = Coverage::find($request->input('coverage'));
        $practice->coverage()->associate($coverage);

        $nomenclature = Nomenclature::find($request->input('nomenclature'));
        $practice->nomenclature()->associate($nomenclature);
        $practice->save();

        $price = new Price();
        $price->price = $request->input('price');
        $price->copayment = $request->input('copayment');
        $price->coverage()->associate($coverage);
        $price->practice()->associate($practice);
        $price->save();

        return redirect('/practices');
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
        $practice = Practice::find(base64_decode(base64_decode($id)));
        $coverages = Coverage::all();
        $nomenclatures = Nomenclature::all();
        return view('practices.edit')->with([
            'practice' => $practice,
            'coverages' => $coverages,
            'nomenclatures' => $nomenclatures
        ]);
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
        $practice = Practice::find($id);
        $practice->name = $request->input('name');
        $practice->maxtime = $request->input('maxtime');
        $practice->description = $request->input('description');
        
        $coverage = Coverage::find($request->input('coverage'));
        if ($practice->coverage != $coverage) {
            $practice->coverage()->dissociate($practice->coverage);
            $practice->coverage()->associate($coverage);
        }
        $practice->coverage()->associate($coverage);

        $nomenclature = Nomenclature::find($request->input('nomenclature'));
        if ($practice->nomenclature != $nomenclature) {
            $practice->nomenclature()->dissociate($practice->nomenclature);
            $practice->nomenclature()->associate($nomenclature);
        }
        $practice->save();

        $price = $practice->price;
        $price->price = $request->input('price');
        $price->copayment = $request->input('copayment');
        $price->coverage()->associate($coverage);
        $price->practice()->associate($practice);
        $price->save();

        return redirect('/practices');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Practice::destroy($id);
        return redirect('/practices');
    }
}