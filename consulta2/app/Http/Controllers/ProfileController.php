<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use App\Models\PatientProfile;
use App\Models\ProfessionalProfile;
use App\Models\InstitutionProfile;

class ProfileController extends Controller
{
    // Recovers the correct info depending on the type of login.
    // TODO: Use ROLE instead of PROFILE 
    public function info() {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('patient-profile')) {
            $user_profile = Profile::where('user_id', '=', auth()->user()->id)->first();
            $patient_profile = PatientProfile::where('profile_id', $user_profile->id)->first();
            return view('profile.infoedit')->with([
                'user_profile' => $user_profile,
                'patient_profile' => $patient_profile]);
        } else if ($user->isAbleTo('institution-profile')) {
            $institution_profile = InstitutionProfile::where('user_id', auth()->user()->id)->first();
            return view('profile.instedit')->with([
                'institution' => $institution_profile
            ]);
        } else if ($user->isAbleTo('professional-profile')) {
            $user_profile = Profile::where('user_id', '=', auth()->user()->id)->first();
            $professional_profile = ProfessionalProfile::where('profile_id', $user_profile->id)->first();
            return view('profile.infoedit')->with([
                'user_profile' => $user_profile,
                'professional_profile' => $professional_profile]);
        } else {
            return abort(404);
        }
        
    }
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        auth()->user()->update($request->all());

        return back()->withStatus(__('Perfil actualizado exitosamente.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Contraseña actualizada exitosamente.'));
    }
}