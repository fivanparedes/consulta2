<?php

namespace App\Http\Controllers;

use App\Models\Coverage;
use App\Models\PatientProfile;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LifesheetController extends Controller
{
    public function index() {
        
    }

    public function update(Request $request) {
        $patient = Auth::user()->profile->patientProfile;
        $lifesheet = $patient->lifesheet;
        $chosencoverage = Coverage::find($request->input('coverage'));

        $lifesheet->update([
            'diseases' => $request->diseases,
            'surgeries' => $request->surgeries,
            'medication' => $request->medication,
            'smokes' => $request->smokes,
            'drinks' => $request->drinks,
            'exercises' => $request->exercises
        ]);

        $lifesheet->coverage()->associate($chosencoverage);
        $lifesheet->save();
        return back()->withStatus(__('Hoja de vida actualizada.'));
    }

    public function show(Request $request) {
        $profile = Profile::where('user_id', auth()->user()->id)->first();
        $patient = $profile->patientProfile;
        $lifesheet = $patient->lifesheet;
        $coverages = Coverage::all();
        return view('profile.lifesheet')->with([
            'lifesheet' => $lifesheet,
            'coverages' => $coverages
        ]);
    }
}