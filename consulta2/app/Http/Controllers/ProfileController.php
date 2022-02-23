<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\BusinessHour;
use App\Models\City;
use App\Models\Coverage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use App\Models\PatientProfile;
use App\Models\ProfessionalProfile;
use App\Models\InstitutionProfile;
use App\Models\Lifesheet;
use App\Models\Specialty;
use Google\Service\MyBusinessLodging\Business;
use Illuminate\Support\Facades\Request;

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

    public function register(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $city = City::find(1);
        if ($user->profile != null) {
            return abort(404);
        }
        $profile = new Profile();
        $profile->user()->associate($user);
        $profile->city()->associate($city);
        $profile->save();
        $user->save();
        if ($user->isAbleTo('patient-profile')) {
            $patient = new PatientProfile();
            $lifesheet = new Lifesheet();
            $coverage = Coverage::find(1);
            $patient->profile()->associate($profile);
            $patient->save();
            $lifesheet->coverage()->associate($coverage);
            $lifesheet->patientProfile()->associate($patient);
            $lifesheet->save();
        } else if ($user->isAbleTo('professional-profile')) {
            $business_hours = BusinessHour::all();
            $specialty = Specialty::find(1);
            $institution = InstitutionProfile::find(1);
            $professional = new ProfessionalProfile();
            $professional->specialty()->associate($specialty);
            $professional->profile()->associate($profile);
            $professional->institution()->associate($institution);
            $professional->save();
            $professional->businessHours()->attach($business_hours);
            $professional->save();
            $profile->save();
        }

        return redirect('/profile/info');
    }
}