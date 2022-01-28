<?php

namespace App\Http\Controllers;

use App\Models\Coverage;
use App\Models\Nomenclature;
use App\Models\Practice;
use App\Models\Price;
use GrahamCampbell\SecurityCore\Security;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laratrust;

class PracticeController extends Controller
{
    private $sec;
    public function __construct()
    {
        $this->sec = Security::create(config('security.evil.tags'), "\0");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        if (!$admin->hasRole('Admin')) {
            return abort(403);
        }
        $practices = Practice::where('id', '>', 0);
        if ($request->has('filter1') && $request->filter1 != "") {
            $name = $this->sec->clean($request->filter1);
            $practices = $practices->where('name', 'like', '%' . $name . '%');
        }
        if ($request->has('filter3') && $request->filter3 != "") {
            $time = $this->sec->clean($request->filter3);
            $practices = $practices->where('maxtime', $request->filter2, $time);
        }
        if ($request->has('filter4') && $request->filter4 != "") {
            $os = $this->sec->clean($request->filter4);
            $coverages = Coverage::where('name','like', '%' . $os . '%')->get(['id'])->toArray();
            $practices = $practices->whereIn('coverage_id', $coverages);
        }
        if ($request->has('filter6') && $request->filter6 != "") {
            $pr = $this->sec->clean($request->filter6);
            $prices = Price::where('price', $request->filter5 ,$pr)->get(['id'])->toArray();
            $practices = $practices->whereIn('price_id', $prices);
        }
        $practices = $practices->sortable()->paginate(10);
        return view('practices.index')->with(['practices' => $practices,
            'filter1' => $request->input('filter1') != "" ? $request->input('filter1') : null,
            'filter2' => $request->input('filter2') != "" ? $request->input('filter2') : null,
            'filter3' => $request->input('filter3') != "" ? $request->input('filter3') : null,
            'filter4' => $request->input('filter4') != "" ? $request->input('filter4') : null,
            'filter5' => $request->input('filter5') != "" ? $request->input('filter5') : null,
            'filter6' => $request->input('filter6') != "" ? $request->input('filter6') : null,]);
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
        $practice->allowed_modes = $request->input('allowed_modes');
        
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
        $practice->allowed_modes = $request->input('allowed_modes');
        
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