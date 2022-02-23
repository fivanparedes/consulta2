<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\City;
use App\Models\Coverage;
use App\Models\InstitutionProfile;
use App\Models\Lifesheet;
use App\Models\MedicalHistory;
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
use Carbon\Carbon;
use DateTimeZone;
use Spatie\GoogleCalendar\Event;

class PatientController extends Controller
{

    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('professional-profile')) {
            $col = new Collection();

            $medical_histories = $user->profile->professionalProfile->medicalHistories;
            foreach ($medical_histories as $medical_history) {
                $col->push($medical_history->patientProfile);
            }
            $events = CalendarEvent::where('professional_profile_id', $user->profile->professionalProfile->id);
            foreach ($events as $calendarevent) {
                if (count($calendarevent->patientProfiles) > 0) {
                    foreach ($calendarevent->patientProfiles as $patientProfile) {
                        if (!$col->contains($patientProfile)) {
                            $col->push($patientProfile);
                        }
                    }
                }
            }

            $patients = PatientProfile::whereIn('id', $col->toArray(['id']));
            if ($request->has('filter1') && $request->filter1 != "") {
                $user_ids = array();
                foreach ($col as $item) {
                    if (strpos($item->profile->user->name, $request->filter1) !== FALSE || strpos($item->profile->user->lastname, $request->filter1) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            if ($request->has('filter2') && $request->filter2 != "") {
                $user_ids = array();
                foreach ($col as $item) {
                    if (strpos((string)$item->profile->user->dni, (string)$request->filter2) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            if ($request->has('filter3') && $request->filter3 != "") {
                $user_ids = array();
                foreach ($col as $item) {
                    if (strpos((string)$item->lifesheet->coverage->name, (string)$request->filter3) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            if ($request->has('filter4') && $request->filter4 != "") {
                $user_ids = array();
                foreach ($col as $item) {
                    if (strpos((string)$item->profile->city->name, (string)$request->filter4) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }

            $patients = $patients->sortable()->paginate(10);

            return view('patients.index')->with([
                'patients' => $patients,
                'filter1' => $request->filter1 != "" ? $request->filter1 : "",
                'filter2' => $request->filter2 != "" ? $request->filter2 : "",
                'filter3' => $request->filter3 != "" ? $request->filter3 : "",
                'filter4' => $request->filter4 != "" ? $request->filter4 : "",
            ]);
        } elseif ($user->isAbleTo('institution-profile')) {
            $patients = new Collection();
            $professionals = Auth::user()->institutionProfile->professionalProfiles->toArray(['id']);
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
            if (count($medical_histories) > 0) {
                foreach ($medical_histories as $medical_history) {
                    if (!$patients->contains($medical_history->patientProfile)) {
                        $patients->push($medical_history->patientProfile);
                    }
                }
            }


            $patients = PatientProfile::whereIn('id', $patients->toArray());
            if ($request->has('filter1') && $request->filter1 != "") {
                $user_ids = array();
                foreach ($patients->get() as $item) {
                    if (strpos($item->profile->user->name, $request->filter1) !== FALSE || strpos($item->profile->user->lastname, $request->filter1) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            if ($request->has('filter2') && $request->filter2 != "") {
                $user_ids = array();
                foreach ($patients->get() as $item) {
                    if (strpos((string)$item->profile->user->dni, (string)$request->filter2) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            if ($request->has('filter3') && $request->filter3 != "") {
                $user_ids = array();
                foreach ($patients->get() as $item) {
                    if (strpos((string)$item->lifesheet->coverage->name, (string)$request->filter3) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            if ($request->has('filter4') && $request->filter4 != "") {
                $user_ids = array();
                foreach ($patients->get() as $item) {
                    if (strpos((string)$item->profile->city->name, (string)$request->filter4) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            $patients = $patients->sortable()->paginate(10);
            return view('patients.index')->with(['patients' => $patients,
                'filter1' => $request->filter1 != "" ? $request->filter1 : "",
                'filter2' => $request->filter2 != "" ? $request->filter2 : "",
                'filter3' => $request->filter3 != "" ? $request->filter3 : "",
                'filter4' => $request->filter4 != "" ? $request->filter4 : "",]);
        } elseif ($user->isAbleTo('admin-profile')) {
            $patients = PatientProfile::all()->toQuery();
            if ($request->has('filter1') && $request->filter1 != "") {
                $user_ids = array();
                foreach ($patients->get() as $item) {
                    if (strpos($item->profile->user->name, $request->filter1) !== FALSE || strpos($item->profile->user->lastname, $request->filter1) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            if ($request->has('filter2') && $request->filter2 != "") {
                $user_ids = array();
                foreach ($patients->get() as $item) {
                    if (strpos((string)$item->profile->user->dni, (string)$request->filter2) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            if ($request->has('filter3') && $request->filter3 != "") {
                $user_ids = array();
                foreach ($patients->get() as $item) {
                    if (strpos((string)$item->lifesheet->coverage->name, (string)$request->filter3) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            if ($request->has('filter4') && $request->filter4 != "") {
                $user_ids = array();
                foreach ($patients->get() as $item) {
                    if (strpos((string)$item->profile->city->name, (string)$request->filter4) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            $patients = $patients->sortable()->paginate(10);

            return view('patients.index')->with(['patients' => $patients,
                'filter1' => $request->filter1 != "" ? $request->filter1 : "",
                'filter2' => $request->filter2 != "" ? $request->filter2 : "",
                'filter3' => $request->filter3 != "" ? $request->filter3 : "",
                'filter4' => $request->filter4 != "" ? $request->filter4 : "",]);
        }
    }

    public function createPDF(Request $request)
    {
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
            $pdf = PDF::loadView('patients.pdf', ['patients' => $patients]);
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
            $pdf = PDF::loadView('patients.pdf', ['patients' => $patients,
                'filter1' => $request->filter1 != "" ? $request->filter1 : "",
                'filter2' => $request->filter2 != "" ? $request->filter2 : "",
                'filter3' => $request->filter3 != "" ? $request->filter3 : "",
                'filter4' => $request->filter4 != "" ? $request->filter4 : "",]);
            return $pdf->download('pacientes.pdf');
        } elseif ($user->isAbleTo('admin-profile')) {
            $patients = PatientProfile::all()->toQuery();
            if ($request->has('filter1') && $request->filter1 != "") {
                $user_ids = array();
                foreach ($patients->get() as $item) {
                    if (strpos($item->profile->user->name, $request->filter1) !== FALSE || strpos($item->profile->user->lastname, $request->filter1) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            if ($request->has('filter2') && $request->filter2 != "") {
                $user_ids = array();
                foreach ($patients->get() as $item) {
                    if (strpos((string)$item->profile->user->dni, (string)$request->filter2) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            if ($request->has('filter3') && $request->filter3 != "") {
                $user_ids = array();
                foreach ($patients->get() as $item) {
                    if (strpos((string)$item->lifesheet->coverage->name, (string)$request->filter3) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            if ($request->has('filter4') && $request->filter4 != "") {
                $user_ids = array();
                foreach ($patients->get() as $item) {
                    if (strpos((string)$item->profile->city->name, (string)$request->filter4) !== FALSE) {
                        array_push($user_ids, $item->id);
                    }
                }

                $patients = $patients->whereIn('id', $user_ids);
            }
            $patients = $patients->get();
            $pdf = PDF::loadView('patients.pdf', ['patients' => $patients,
                'filter1' => $request->filter1 != "" ? $request->filter1 : "",
                'filter2' => $request->filter2 != "" ? $request->filter2 : "",
                'filter3' => $request->filter3 != "" ? $request->filter3 : "",
                'filter4' => $request->filter4 != "" ? $request->filter4 : "",]);
            return $pdf->download('pacientes.pdf');
        }
    }

    public function listEvents()
    {
        $usermodel = User::find(auth()->user()->id);
        if (!$usermodel->isAbleTo('patient-profile')) {
            return abort(404);
        }
        $calendarevents = Auth::user()->profile->patientProfile->calendarEvents;
        if ($calendarevents->count() > 0) {
            $calendarevents = $calendarevents->toQuery();
            $calendarevents = $calendarevents->sortable()->paginate(10);
        }

        return view('patients.events')->with([
            'events' => $calendarevents
        ]);
    }

    public function showEvent($id)
    {
        $event = CalendarEvent::find($id);
        $gevent = null;
        /* if ($event->gid == null) {
            $gevents = Event::get();
            foreach ($gevents as $item) {
                if ($item->startDateTime == new Carbon($event->start, new DateTimeZone("-0300"))) {
                    $gevent = $item;
                    break;
                }
            }
        } else {
            $gevent = Event::find($event->gid);
        } */

        //dd($gevents);
        //$gevent = null;

        if ($event->gid == null && isset($gevent->id)) {
            $event->gid = $gevent->id;
            $event->save();
        }
        $user = Auth::user();
        $patient = $user->profile->patientProfile;
        return view('patients.show_event')->with(['event' => $event, 'patient' => $patient, 'gevent' => $gevent]);
    }
    public function update(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $request->validate([
            'bornDate' => 'required|date_format:Y-m-d|before:' . date('Y-m-d'),
            'gender' => 'required|string',
            'phone' => 'required',
            'address' => 'required|string|filled|max:100',
            'bornPlace' => 'required|string',
            'familyGroup' => 'required|string|filled|max:100',
            'familyPhone' => 'required',
            'civilState' => 'required|string|filled|max:40',
            'scholarity' => 'required|string|filled|max:20',
            'occupation' => 'required|string|filled|max:20'
        ]);
        if ($user->isAbleTo('patient-profile') || $user->isAbleTo('professional-profile')) {
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

    public function create(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if ($user->profile == null) {
            return redirect('/profile/registered');
        }
        if (!$user->isAbleTo('PatientController@create')) {
            return abort(404);
        }
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $user = User::find(auth()->user()->id);
        /* if (!$user->isAbleTo('PatientController@create')) {
            return abort(404);
        } */

        $request->validate([
            'user_name' => 'required|string|filled|max:60',
            'user_lastname' => 'required|string|filled|max:60',
            'user_dni' => 'required|numeric|unique:users,dni',
            'user_email' => 'required|email:strict|unique:users,email',
            'bornDate' => 'required|date_format:Y-m-d|before:' . date('Y-m-d'),
            'gender' => 'required|string',
            'phone' => 'required',
            'address' => 'required|string|filled|max:100',
            'bornPlace' => 'required|string',
            'familyGroup' => 'required|string|filled|max:100',
            'familyPhone' => 'required',
            'civilState' => 'required|string|filled|max:40',
            'scholarity' => 'required|string|filled|max:20',
            'occupation' => 'required|string|filled|max:20'
        ]);

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
        $lifesheet->coverage_id = $request->has('coverage') ? $request->coverage : 1;
        $lifesheet->save();

        if (!$user->isAbleTo('admin-profile')) {
            $medical_history = new MedicalHistory();
            $medical_history->patient_profile_id = $patientProfile->id;
            if ($user->isAbleTo('professional-profile')) {
                $medical_history->professional_profile_id = $user->profile->professionalProfile->id;
            } else if ($user->isAbleTo('institution-profile')) {
                $medical_history->institution_id = $user->institutionProfile->id;
            }

            $medical_history->save();
        }

        return redirect('/manage/patients');
    }

    public function edit($id)
    {
        $user = User::find(auth()->user()->id);
        if (!$user->isAbleTo('PatientController@edit')) {
            return abort(404);
        }

        $patient = PatientProfile::find(base64_decode(base64_decode($id)));
        return view('patients.edit')->with(['patient' => $patient]);
    }

    public function destroy($id)
    {
        PatientProfile::destroy($id);
        return redirect('/patients');
    }
}
