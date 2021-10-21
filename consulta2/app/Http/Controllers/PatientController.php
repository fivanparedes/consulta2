<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Coverage;
use App\Models\InstitutionProfile;
use App\Models\Lifesheet;
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

    public function update(Request $request)
    {
        $user_profile = Profile::where('user_id', auth()->user()->id)->first();
        $patient_profile = PatientProfile::where('profile_id', $user_profile->id)->first();
        $professional_profile = ProfessionalProfile::where('profile_id', $user_profile->id)->first();
        $user_profile->update([
            'bornDate' => $request->bornDate,
            'phone' => $request->phone,
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


    //Creates a blank patient profile after registering.
    // TODO: make this damn thing work
    public function create() {
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
        if ($user->isAbleTo('_consulta2_patient_profile_perm')) {
            $patient = new PatientProfile();
            $lifesheet = new Lifesheet();
            $coverage = Coverage::find(1);
            $lifesheet->coverage()->associate($coverage);
            $lifesheet->patientProfile()->associate($patient);
            $patient->profile()->associate($profile);
            $patient->save();
            $lifesheet->save();
        } else if ($user->isAbleTo('_consulta2_professional_profile_perm')) {
            $institution = InstitutionProfile::find(1);
            $professional = new ProfessionalProfile();
            $professional->profile()->associate($profile);
            $professional->institution()->associate($institution);
            $professional->save();
            $profile->save();
        }
        
        return redirect('/profile/info');
    }

}