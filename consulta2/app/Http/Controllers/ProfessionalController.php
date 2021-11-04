<?php

namespace App\Http\Controllers;

use App\Models\BusinessHours;
use App\Models\ProfessionalProfile;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfessionalController extends Controller
{
    public function index() {
        $_professionals = DB::table('professional_profiles')
            ->join('institution_profiles', 'institution_profiles.id', '=', 'professional_profiles.institution_id')
            ->join('profiles', 'profiles.id', '=', 'professional_profiles.profile_id')
            ->join('users', 'users.id', '=', 'profiles.user_id')->get();
        return view('professionals.index')->with(['professionals' => $_professionals]);
    }

    public function show(Request $request) {
        $_professional = DB::table('professional_profiles')
            ->join('institution_profiles', 'institution_profiles.id', '=', 'professional_profiles.institution_id')
            ->join('profiles', 'profiles.id', '=', 'professional_profiles.profile_id')
            ->join('users', 'users.id', '=', 'profiles.user_id')
            ->where('users.id', $request->id)->first();
        $_prof = Profile::where('user_id', $request->id)->first();

        $_workingHours = $_prof->professionalProfile->businessHours()->get();

            return view('professionals.show')
                ->with([
                    'professional' => $_professional,
                    'workingHours' => $_workingHours
        ]);
        
    }
}