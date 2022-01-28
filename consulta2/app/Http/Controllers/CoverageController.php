<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Coverage;
use GrahamCampbell\SecurityCore\Security;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Covers;
use Barryvdh\DomPDF\Facade as PDF;

class CoverageController extends Controller
{
    private $sec;

    public function __construct()
    {
        $this->sec = Security::create(["\/", "\\", "\(", "\)", "\;"], "\0");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $coverages = Coverage::where('id', '>', 0);
        if ($request->has('filter1') && $request->filter1 != "") {
            $name = $this->sec->clean($request->filter1);
            $coverages = $coverages->where('name', 'like', '%'.$name.'%');
        }
        if ($request->has('filter2') && $request->filter2 != "") {
            $dir = $this->sec->clean($request->filter2);
            $coverages = $coverages->where('address', 'like', '%' . $dir . '%');
            
        }
        if ($request->has('filter3') && $request->filter3 != "") {
            $tel = $this->sec->clean($request->filter3);
            $coverages = $coverages->where('phone', 'like', '%' . $tel . '%');
        }
        if ($request->has('filter4') && $request->filter4 != "") {
            $city = $this->sec->clean($request->filter4);
            $cities = City::where('name', 'like', '%' . $city . '%')->get(['id'])->toArray();
            $coverages = $coverages->whereIn('city_id', $cities);
        }

        $coverages = $coverages->sortable()->paginate(10);
        return view('coverages.index')->with([
            'coverages' => $coverages,
            'filter1' => $request->input('filter1') != "" ? $request->input('filter1') : null,
            'filter2' => $request->input('filter2') != "" ? $request->input('filter2') : null,
            'filter3' => $request->input('filter3') != "" ? $request->input('filter3') : null,
            'filter4' => $request->input('filter4') != "" ? $request->input('filter4') : null,
        ]);
    }

    public function createPDF(Request $request) {
        $coverages = Coverage::where('id', '>', 0);
        if ($request->has('filter1') && $request->filter1 != "") {
            $name = $this->sec->clean($request->filter1);
            $coverages = $coverages->where('name', 'like', '%' . $name . '%');
        }
        if ($request->has('filter2') && $request->filter2 != "") {
            $dir = $this->sec->clean($request->filter2);
            $coverages = $coverages->where('address', 'like', '%' . $dir . '%');
        }
        if ($request->has('filter3') && $request->filter3 != "") {
            $tel = $this->sec->clean($request->filter3);
            $coverages = $coverages->where('phone', 'like', '%' . $tel . '%');
        }
        if ($request->has('filter4') && $request->filter4 != "") {
            $city = $this->sec->clean($request->filter4);
            $cities = City::where('name', 'like', '%' . $city . '%')->get(['id'])->toArray();
            $coverages = $coverages->whereIn('city_id', $cities);
        }

        $coverages = $coverages->all();
        $pdf = PDF::loadView('coverages.pdf',[
            'coverages' => $coverages,
            'filter1' => $request->input('filter1') != "" ? $request->input('filter1') : null,
            'filter2' => $request->input('filter2') != "" ? $request->input('filter2') : null,
            'filter3' => $request->input('filter3') != "" ? $request->input('filter3') : null,
            'filter4' => $request->input('filter4') != "" ? $request->input('filter4') : null,
        ]);
        return $pdf->download('obras_sociales.pdf');
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
