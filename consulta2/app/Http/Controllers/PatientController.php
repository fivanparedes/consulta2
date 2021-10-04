<?php

namespace App\Http\Controllers;

use App\Models\PatientProfile;
use App\Models\ProfessionalProfile;
use App\Models\Profile;
use App\Models\User;
use DateTime;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{

    // Recovers the correct info depending on the type of login.
    // TODO: Use ROLE instead of PROFILE 
    public function info() {
        $user_profile = Profile::where('user_id', auth()->user()->id)->first();
        $patient_profile = PatientProfile::where('profile_id', $user_profile->id)->first();
        $professional_profile = ProfessionalProfile::where('profile_id', $user_profile->id)->first();
        return view('profile.infoedit')->with([
            'user_profile' => $user_profile,
            'patient_profile' => $patient_profile,
            'professional_profile' => $professional_profile]);
    }

    public function update(Request $request)
    {
        $user_profile = Profile::where('user_id', auth()->user()->id)->first();
        $patient_profile = PatientProfile::where('profile_id', $user_profile->id)->first();
        $professional_profile = ProfessionalProfile::where('profile_id', $user_profile->id)->first();
        $user_profile->update([
            'bornDate' => $request->bornDate,
            'gender' => $request->gender,
            'address' => $request->address
        ]);

        if ($patient_profile != null) {
            $patient_profile->update([
                'bornPlace' => $request->bornPlace,
                'familyGroup' => $request->familyGroup,
                'familyPhone' => $request->familyPhone,
                'civilState' => $request->civilState,
                'scholarity' => $request->scholarity,
                'occupation' => $request->occupation

            ]);
        } else if ($professional_profile != null) {
            $professional_profile->update([
                'licensePlate' => $request->licensePlate,
                'field' => $request->field,
                'specialty' => $request->specialty
            ]);

        }
        return back()->withStatus(__('Perfil actualizado correctamente.'));
    }


    //Creates a patient profile after registering.
    // TODO: make this damn thing work
    public function create() {
        $user = User::find(auth()->user()->id);
        $user->role_id = 4;
        $patient = new PatientProfile([
            'bornPlace' => 'Ej. Posadas',
            'familyGroup' => 'Ej. Conviviendo con los padres y hermanos',
            'familyPhone' => '3764000000',
            'civilState' => 'Ej: Soltero/a',
            'scholarity' => 'Ej: Universitario en curso',
            'occupation' => 'Ej: Atención al cliente'
        ]);
        $patient->save();

        $profile = new Profile([
            'patient_profile_id' => $patient->id,
            'bornDate' => now(),
            'gender' => 'Femenino',
            'phone' => '3764123123',
            'address' => 'Domicilio',
        ]);

        $profile->save();

        $patient->profile()->associate($profile);
        $patient->save();

        $profile->user()->associate($user);

        $user->update([
            'profile_id' => $profile->id
        ]);
        $profile->save();
        $user->save();
        return redirect('/profile/info');
    }

}