<?php

namespace App\Http\Controllers;

use App\Models\Nomenclature;
use App\Models\Specialty;
use GrahamCampbell\Security\Facades\Security;
use Illuminate\Http\Request;

class NomenclatureController extends Controller
{
    private $sec;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nomenclatures = Nomenclature::where('id', '>', 0);
        if ($request->has('filter1') && $request->filter1 != "") {
            $code = $this->sec->clean($request->filter1);
            $nomenclatures = $nomenclatures->where('code', 'like', '%' . $code . '%');
        }
        if ($request->has('filter2') && $request->filter2 != "") {
            $desc = $this->sec->clean($request->filter2);
            $nomenclatures = $nomenclatures->where('description', 'like', '%' . $desc . '%');
        }
        if ($request->has('filter3') && $request->filter3 != "") {
            $spec = $this->sec->clean($request->filter3);
            $specialties = Specialty::where('displayname', 'like', '%' . $spec . '%')->get(['id'])->toArray();
            $nomenclatures = $nomenclatures->whereIn('specialty_id', $specialties);
        }
        $nomenclatures = $nomenclatures->sortable()->paginate(10);
        return view('nomenclatures.index')->with([
            'nomenclatures' => $nomenclatures,
            'filter1' => $request->input('filter1') != "" ? $request->input('filter1') : null,
            'filter2' => $request->input('filter2') != "" ? $request->input('filter2') : null,
            'filter3' => $request->input('filter3') != "" ? $request->input('filter3') : null,
        ]);
    }

    public function __construct()
    {
        $this->sec = Security::create(config('security.evil.tags'), "\0");
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
        $nomenclature->code = $this->sec->clean($request->input('code'));
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
