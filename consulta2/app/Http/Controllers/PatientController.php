<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\City;
use App\Models\Coverage;
use App\Models\InstitutionProfile;
use App\Models\Lifesheet;
use App\Models\PatientProfile;
use App\Models\Permission;
use App\Models\ProfessionalProfile;
use App\Models\Profile;
use App\Models\Specialty;
use App\Models\User;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade as PDF;

class PatientController extends Controller
{

    public function index() {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('professional-profile')) {
            $patients = new Collection();
            $events = CalendarEvent::where('professional_profile_id', Auth::user()->profile->professionalProfile->id)->get();
            foreach ($events as $event) {
                if ($event->patientProfiles->count() > 0) {
                    foreach ($event->patientProfiles as $profile) {
                        if (!$patients->contains($profile)) {
                            $patients->push($profile);
                        }
                    }
                }
            }

            $medical_histories = $user->profile->professionalProfile->medicalHistories;
            foreach ($medical_histories as $medical_history) {
                if (!$patients->contains($medical_history->patientProfile)) {
                    $patients->push($medical_history->patientProfile);
                }
            }
            
            $patients = PatientProfile::whereIn('id', $patients->toArray());
            $patients = $patients->sortable()->paginate(10);
            return view('patients.index')->with(['patients' => $patients]);
        } elseif ($user->isAbleTo('institution-profile')) {
            $patients = new Collection();
            $professionals = Auth::user()->institutionProfile->professionalProfiles->all(['id']);
            $events = CalendarEvent::whereIn('professional_profile_id', $professionals)->get();
            foreach ($events as $event) {
                if ($event->patientProfiles->count() > 0) {
                    foreach ($event->patientProfiles as $profile) {
                        if (!$patients->contains($profile)) {
                            $patients->push($profile);
                        }
                    }
                }
            }

            $medical_histories = $user->institutionProfile->medicalHistories;
            foreach ($medical_histories as $medical_history) {
                if (!$patients->contains($medical_history->patientProfile)) {
                    $patients->push($medical_history->patientProfile);
                }
            }

            $patients = PatientProfile::whereIn('id', $patients->toArray());
            $patients = $patients->sortable()->paginate(10);
            return view('patients.index')->with(['patients' => $patients]);
        } elseif ($user->isAbleTo('admin-profile')) {
            $patients = PatientProfile::where('id', '>', 0);
            $patients = $patients->sortable()->paginate(10);
            
            return view('patients.index')->with(['patients' => $patients]);
        }
    }

    public function createPDF(Request $request) {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('professional-profile')) {
            $patients = new Collection();
            $events = CalendarEvent::where('professional_profile_id', Auth::user()->profile->professionalProfile->id)->get();
            foreach ($events as $event) {
                if ($event->patientProfiles->count() > 0) {
                    foreach ($event->patientProfiles as $profile) {
                        if (!$patients->contains($profile)) {
                            $patients->push($profile);
                        }
                    }
                }
            }

            $medical_histories = $user->profile->professionalProfile->medicalHistories;
            foreach ($medical_histories as $medical_history) {
                if (!$patients->contains($medical_history->patientProfile)) {
                    $patients->push($medical_history->patientProfile);
                }
            }

            $patients = PatientProfile::whereIn('id', $patients->toArray());
            $patients = $patients->sortable()->paginate(10);
            $pdf = PDF::loadView('patients.pdf',['patients' => $patients]);
            return $pdf->download('pacientes.pdf');
        } elseif ($user->isAbleTo('institution-profile')) {
            $patients = new Collection();
            $professionals = Auth::user()->institutionProfile->professionalProfiles->all(['id']);
            $events = CalendarEvent::whereIn('professional_profile_id', $professionals)->get();
            foreach ($events as $event) {
                if ($event->patientProfiles->count() > 0) {
                    foreach ($event->patientProfiles as $profile) {
                        if (!$patients->contains($profile)) {
                            $patients->push($profile);
                        }
                    }
                }
            }

            $medical_histories = $user->institutionProfile->medicalHistories;
            foreach ($medical_histories as $medical_history) {
                if (!$patients->contains($medical_history->patientProfile)) {
                    $patients->push($medical_history->patientProfile);
                }
            }

            $patients = PatientProfile::whereIn('id', $patients->toArray());
            $patients = $patients->sortable()->paginate(10);
            $pdf = PDF::loadView('patients.pdf',['patients' => $patients]);
            return $pdf->download('pacientes.pdf');
        } elseif ($user->isAbleTo('admin-profile')) {
            $patients = PatientProfile::where('id', '>', 0);
            $patients = $patients->sortable()->paginate(10);

            $pdf = PDF::loadView('patients.pdf',['patients' => $patients]);
            return $pdf->download('pacientes.pdf');
        }
    }

    public function listEvents()
    {
        $usermodel = User::find(auth()->user()->id);
        if (!$usermodel->isAbleTo('patient-profile')) {
            return abort(403);
        }
        $calendarevents = Auth::user()->profile->patientProfile->calendarEvents()->orderBy('start', 'desc')->get();
        return view('patients.events')->with([
            'events' => $calendarevents
        ]);
    }

    public function showEvent($id)
    {
        $event = CalendarEvent::find($id);
        $user = Auth::user();
        $patient = $user->profile->patientProfile;
        return view('patients.show_event')->with(['event' => $event, 'patient' => $patient]);
    }
    public function update(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('patient-profile')) {
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
                    'field' => $request->field
                ]);
                $professional_profile->coverages()->detach();
                $professional_profile->coverages()->attach($request->coverages);
                $specialty = Specialty::find($request->specialty);
                $professional_profile->specialty()->associate($specialty);
                $professional_profile->save();
            }
            return back()->withStatus(__('Perfil actualizado correctamente.'));
        } else {
            $user = User::find(auth()->user()->id);
            if (!$user->hasPermission('PatientController@edit')) {
                return abort(404);
            }

            $patuser = User::find($request->id);
            $patuser->name = $request->user_name;
            $patuser->lastname = $request->user_lastname;
            $patuser->dni = $request->user_dni;
            $patuser->email = $request->email;
            $patuser->password = Hash::make($request->user_dni);
            $patuser->save();

            $patprofile = $patuser->profile;
            $patprofile->bornDate = date_create($request->bornDate);
            $patprofile->gender = $request->gender;
            $patprofile->phone = $request->phone;
            $patprofile->address = $request->address;
            $patprofile->city_id = $request->city;
            $patprofile->user_id = $patuser->id;
            $patprofile->save();

            $patientProfile = $patprofile->patientProfile;
            $patientProfile->bornPlace = $request->bornPlace;
            $patientProfile->familyGroup = $request->familyGroup;
            $patientProfile->familyPhone = $request->familyPhone;
            $patientProfile->civilState = $request->civilState;
            $patientProfile->scholarity = $request->scholarity;
            $patientProfile->occupation = $request->occupation;
            $patientProfile->profile_id = $patprofile->id;
            $patientProfile->save();

            $lifesheet = $patientProfile->lifesheet;
            $lifesheet->coverage_id = $request->coverage;
            $lifesheet->save();

            return back()->withStatus(__('Paciente actualizado correctamente.'));
        }
    }


    //Creates a blank patient profile after registering.
    // TODO: make this damn thing work
    public function create(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if ($request->registering) {
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
                $institution = InstitutionProfile::find(1);
                $professional = new ProfessionalProfile();
                $professional->profile()->associate($profile);
                $professional->institution()->associate($institution);
                $professional->save();
                $profile->save();
            }

            return redirect('/profile/info');
        } else {
            if (!$user->isAbleTo('PatientController@create')) {
                return abort(404);
            }
            return view('patients.create');
        }
        
    }

    public function store(Request $request)
    {
        $user = User::find(auth()->user()->id);
        /* if (!$user->isAbleTo('PatientController@create')) {
            return abort(404);
        } */

        $patuser = new User();
        $patuser->name = $request->user_name;
        $patuser->lastname = $request->user_lastname;
        $patuser->dni = $request->user_dni;
        $patuser->email = $request->user_email;
        $patuser->password = Hash::make($request->user_dni);
        $patuser->save();
        $patuser->attachRole('Patient');

        $patprofile = new Profile();
        $patprofile->bornDate = date_create($request->bornDate);
        $patprofile->gender = $request->gender;
        $patprofile->phone = $request->phone;
        $patprofile->address = $request->address;
        $patprofile->city_id = $request->city;
        $patprofile->user_id = $patuser->id;
        $patprofile->save();

        $patientProfile = new PatientProfile();
        $patientProfile->bornPlace = $request->bornPlace;
        $patientProfile->familyGroup = $request->familyGroup;
        $patientProfile->familyPhone = $request->familyPhone;
        $patientProfile->civilState = $request->civilState;
        $patientProfile->scholarity = $request->scholarity;
        $patientProfile->occupation = $request->occupation;
        $patientProfile->profile_id = $patprofile->id;
        $patientProfile->save();

        $lifesheet = new Lifesheet();
        $lifesheet->patient_profile_id = $patientProfile->id;
        $lifesheet->coverage_id = $request->coverage;
        $lifesheet->save();

        return redirect('/patients');
    }

    public function edit($id) {
        $user = User::find(auth()->user()->id);
        if (!$user->isAbleTo('PatientController@edit')) {
            return abort(404);
        }

        $patient = PatientProfile::find(base64_decode(base64_decode($id)));
        return view('patients.edit')->with(['patient' => $patient]);
    }

    public function destroy($id) {
        PatientProfile::destroy($id);
        return redirect('/patients');
    }
}
