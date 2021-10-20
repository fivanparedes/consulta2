<?php

namespace App\Http\Controllers;

use App\Models\ProfessionalProfile;
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
        $_professional = ProfessionalProfile::find($request->id);
    }
}
