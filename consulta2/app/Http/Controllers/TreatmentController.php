<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(auth()->user()->id);
        $arr = array();
        if (count($user->profile->professionalProfile->medicalHistories) > 0) {
            foreach ($user->profile->professionalProfile->medicalHistories as $medical_history) {
                if (count($medical_history->treatments) > 0) {
                    foreach ($medical_history->treatments as $treatment) {
                        array_push($arr, $treatment->id);
                    }
                }
            }
        }

        $treatments = Treatment::whereIn('id', $arr)->sortable()->paginate(10);

        return view('treatments.index')->with(['treatments' => $treatments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('professional-profile')) {
            return view('treatments.create')->with(['medical_histories' => $user->profile->professionalProfile->medicalHistories]);
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $treatment = new Treatment();
        $treatment->name = $request->name;
        $treatment->description = $request->description;
        $treatment->times_per_month = $request->times_per_month;
        $treatment->start = date_create($request->start);
        $treatment->end = date_create($request->end);
        $treatment->medical_history_id = $request->medical_history_id;
        $treatment->save();
        return redirect('/treatments');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Treatment  $treatment
     * @return \Illuminate\Http\Response
     */
    public function show(Treatment $treatment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find(auth()->user()->id);
        $treatment = Treatment::find(base64_decode(base64_decode($id)));
        if ($user->isAbleTo('professional-profile')) {
            return view('treatments.edit')->with(['treatment' => $treatment, 'medical_histories' => $user->profile->professionalProfile->medicalHistories]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Treatment  $treatment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Treatment $treatment)
    {
        //$treatment = Treatment::find($request->id);
        $treatment->name = $request->name;
        $treatment->times_per_month = $request->times_per_month;
        $treatment->start = date_create($request->start);
        $treatment->end = date_create($request->end);
        $treatment->save();
        return redirect('/treatments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Treatment  $treatment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Treatment $treatment)
    {
        Treatment::destroy($treatment->id);
        return redirect('/treatments');
    }
}
