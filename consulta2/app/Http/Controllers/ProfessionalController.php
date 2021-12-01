<?php

namespace App\Http\Controllers;

use App\Models\BusinessHours;
use Illuminate\Support\Facades\Auth;
use App\Models\ConsultType;
use App\Models\Permission;
use App\Models\ProfessionalProfile;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfessionalController extends Controller
{
    public function index() {
        $_professionals = ProfessionalProfile::where('status', '<>', 0)->get();
        return view('professionals.index')->with(['professionals' => $_professionals]);
    }

    public function adminList() {
        $professionals = ProfessionalProfile::all();
        return view('admin.professionals')->with(['professionals' => $professionals]);
    }

    public function edit(Request $request) {
        $professional = ProfessionalProfile::find($request->id);
        return view('admin.professionals_edit')->with(['professional' => $professional]);
    }

    public function save(Request $request) {
        $professional = ProfessionalProfile::find($request->id);
        $professional->update([
            'status' => $request->input('status')
        ]);
        $turnperm = Permission::where('name', 'receive-consults')->first();
        if ($request->input('status') == 1) {
            $professional->profile->user->attachPermission($turnperm);
            $professional->save();
        } else {
            $professional->profile->user->detachPermission($turnperm);
            $professional->save();
        }
        

        return redirect('/admin/professionals');
    }

    public function show(Request $request) {
        $covered = false;
        $_prof = Profile::where('user_id', $request->id)->first();

        $_professional = ProfessionalProfile::find($request->id);

        if (!$_professional->profile->user->isAbleTo('receive-consults')) {
            return abort(403);
        }

        $patient = Auth::user()->profile->patientProfile;
        
        $_consults = $_professional->consultTypes->where('visible', true);

        $_workingHours = $_professional->businessHours()->get();
        if ($patient->lifesheet->coverage->id != 1) {
            if ($_professional->coverages->contains($patient->lifesheet->coverage)) {
                $covered = true;
            }
        }

            return view('professionals.show')
                ->with([
                    'professional' => $_professional,
                    'patient' => $patient,
                    'covered' => $covered,
                    'workingHours' => $_workingHours,
                    'consulttypes' => $_consults
        ]);
        
    }
}