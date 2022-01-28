<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use App\Models\Cite;
use App\Models\ConsultType;
use App\Models\Profile;
use App\Models\PatientProfile;
use App\Models\Practice;
use App\Models\ProfessionalProfile;
use App\Models\Reminder;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laratrust;
use App\Models\NonWorkableDay;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user_profile = Profile::where('user_id', auth()->user()->id)->first();
        $patient_profile = PatientProfile::where('profile_id', $user_profile->id)->first();
        $professional_profile = ProfessionalProfile::where('profile_id', $user_profile->id)->first();
        if ($request->ajax()) {
            if ($patient_profile != null) {
                // TODO: order query for patient users
                $userdata = CalendarEvent::where('user_id', auth()->user()->id)
                    ->whereDate('start', '>=', $request->start)
                    ->whereDate('end',   '<=', $request->end)
                    ->get(['id', 'title', 'start', 'end']);

                $forbidata = CalendarEvent::where('user_id', '<>', auth()->user()->id)
                    ->whereDate('start', '>=', $request->start)
                    ->whereDate('end',   '<=', $request->end)
                    ->get(['id', 'start', 'end']);
                $data = $userdata->concat($forbidata);  //Concat both the user events and the foreign events, but without revealing their identity.
            } else if ($professional_profile != null) {
                $data = CalendarEvent::whereDate('start', '>=', $request->start)
                    ->whereDate('end',   '<=', $request->end)
                    ->get(['id', 'title', 'start', 'end']);
            }

            return response()->json($data);
        }
        return view('calendar');
    }

    public function showAvailableTimes(Request $request)
    {
        if ($request->ajax()) {
            try {

                //Find the appropiate consult type
                $professional = ProfessionalProfile::find($request->input('profid'));
                $hours = $professional->businessHours;
                $consult = ConsultType::find($request->consultid);
                $arr = array();

                //Find the availability of hours given a date
                $dayturns = CalendarEvent::where('professional_profile_id', $professional->id)->where('start', 'like', '%' . $request->currentdate . '%')->get();
                $nonworkabledays = NonWorkableDay::where('professional_profile_id', $professional->id)
                    ->where('from', '<=', $request->currentdate)->where('to', '>=', $request->currentdate)->get();
                foreach ($hours as $hour) {
                    $datestring = $request->currentdate . ' ' . $hour->time;
                    $date = date_create($datestring);
                    $days = explode(";", $consult->availability);       //Availability of days stored in db
                    if (!$dayturns->contains('start', $datestring) && $nonworkabledays->count() == 0 && in_array($date->format('N'), $days)) {    //Check if business hour is in available day, and it's not in event
                        if ($consult->businessHours->contains($hour)) {     //if day available and not in event, check if exists in consult type.
                            array_push($arr, $hour->time);
                        }
                    }
                }

                //Extract the practice matching with the coverage of the patient
                $copayment = 0;
                $practices = $consult->practices;
                foreach ($practices as $practice) {
                    //check if user has available coverage and it's not particular (id 1)
                    if ((Auth::user()->profile->patientProfile->lifesheet->coverage == $practice->coverage) && ($practice->coverage_id != 1)) {
                        $copayment = $practice->price->copayment;
                        return response()->json([
                            "content" => $arr,
                            "covered" => 1,
                            "requiresAuth" => $consult->requires_auth,
                            "practice" => $practice->id,
                            "allowed_modes" => $practice->allowed_modes,
                            "price" => $copayment,
                        ]);
                        break;
                    }
                }

                if (count($arr) == 0) {
                    return response()->json([
                        "content" => "empty",
                        "dayturns" => count($dayturns),
                        "datestring" => $datestring
                    ]);
                } else {
                    $particular = $consult->practices->where('coverage_id', 1)->first();
                    if ($particular == null) {
                        $particular = $consult->practices->first();
                    }

                    return response()->json([
                        "content" => $arr,
                        "covered" => 0,
                        "requiresAuth" => $consult->requires_auth,
                        "practice" => $particular->id,
                        "allowed_modes" => $particular->allowed_modes,
                        "price" => $particular->price->price
                    ]);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    "content" => "empty",
                    "error" => $th->getMessage()
                ]);
            }
        }
    }

    public function manageEvents(Request $request)
    {
        $user = User::find(auth()->user()->id);
        switch ($request->type) {
            case 'create':
                $calendarEvent = new CalendarEvent([
                    'title' => auth()->user()->name . ' ' . auth()->user()->lastname,
                    'start' => $request->start,
                    'end' => $request->end
                ]);
                $calendarEvent->save();
                $calendarEvent->user()->associate($user);
                $calendarEvent->save();

                $cite = new Cite([
                    'assisted' => false,
                    'isVirtual' => false,
                ]);
                $cite->calendarEvent()->associate($calendarEvent);
                $cite->save();

                return response()->json($calendarEvent);
                break;

            case 'update':
                $calendarEvent = CalendarEvent::find($request->id)->update([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                ]);

                return response()->json($calendarEvent);
                break;

            case 'delete':
                $calendarEvent = CalendarEvent::find($request->id)->delete();
                $cite = Cite::where('calendar_event_id', $request->id)->delete();
                return response()->json($calendarEvent);
                break;

            default:
                break;
        }
    }

    public function confirm(Request $request)
    {
        $selectedDate = new DateTime($request->date);
        $hour = explode(':', $request->time);
        $selectedDate->setTime($hour[0], $hour[1], 0, 0);
        $_professional = ProfessionalProfile::find($request->input('profid'));
        $_consult_type = ConsultType::find($request->input('consult-type'));
        $practice = Practice::find($request->practice);
        return view('events.confirm')->with([
            'professional' => $_professional,
            'selectedDate' => $selectedDate->format('d/m/Y H:i'),
            'consult_type' => $_consult_type,
            'isVirtual' => $request->input('isVirtual'),
            'practice' => $practice
        ]);
    }

    /* * Function to store new Events via the new system
        * */
    public function store(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $_practice = Practice::find($request->input('practice-id'));
        if ($user->hasRole('Professional')) {
            $user = User::where('dni', $request->input('dni'))->first();
            
        } else {
            if (!$user->isAbleTo('patient-profile')) {
                return abort(403);
            }
        }
        

        try {
            DB::beginTransaction();
            $selectedDate = $request->input('date');
            $_practice = Practice::find($request->input('practice-id'));
            $_consult_type = ConsultType::find($request->input('consult-type'));
            
            $currentDate = strtotime($selectedDate);
            $futureDate = $currentDate + (60 * $_practice->maxtime);
            $formatDate = date("Y-m-d H:i:s", $futureDate);
            $_event = new CalendarEvent([
                'title' => $user->name . ' ' . $user->lastname,
                'start' => $selectedDate,
                'end' => $formatDate,
                'approved' => $_consult_type->requires_auth ? 0 : 1,
                'confirmed' => false,
                'isVirtual' => boolval($request->input('isVirtual')),
            ]);
            $_patient = PatientProfile::where('profile_id', $user->profile->id)->first();


            $_event->consultType()->associate($_consult_type);

            $profuser = User::find($request->profid);
            $professional = $profuser->profile->professionalProfile;
            $_event->professionalProfile()->associate($professional);
            $_event->save();
            $_event->patientProfiles()->attach($_patient->id);
            $_event->save();
            $covered = true;
            if ($_patient->lifesheet->coverage->id == 1) {
                $covered = false;
            }
            $_cite = new Cite([
                'assisted' => false,
                'covered' => $covered,
                'paid' => false
            ]);
            $_cite->calendarEvent()->associate($_event);
            $_cite->practice()->associate($_practice);
            $_cite->save();

            $_cite->save();

            if ($_consult_type->requires_auth != 0) {
                Mail::send('external.created', [
                    'user' => $user,
                    'event' => $_event
                ], function ($message) use ($user){
                    $message->to($user->email, $user->name . ' ' . $user->lastname)->subject('Consulta2 | Turno pendiente de aprobación');
                    $message->from('sistema@consulta2.com', 'Consulta2');
                });
                $reminder = new Reminder();
                $reminder->calendarEvent()->associate($_event);
                $reminder->user()->associate($user);
                $reminder->save();
            } else {
                Mail::send('external.created', [
                    'user' => $user,
                    'event' => $_event
                ], function ($message) use ($user) {
                    $message->to($user->email, $user->name . ' ' . $user->lastname)->subject('Consulta2 | Turno agendado exitosamente');
                    $message->from('sistema@consulta2.com', 'Consulta2');
                });
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        if ($user->hasRole('Patient')) {
            return redirect('/professionals/list')->with(['justregistered' => true]);
        } else {
            return back()->withStatus('Turno registrado.');
        }
        
    }

    public function massCancel(Request $request) {
        $user = User::find(auth()->user()->id);
        if (!$user->hasRole('Professional')) {
            return abort(404);
        }
        try {
            DB::beginTransaction();
            $user = User::find(auth()->user()->id);
            $events = CalendarEvent::where('professional_profile_id', $user->profile->professionalProfile->id)
                ->where('start', '>=', date_create($request->from))->orWhere('start', '<=', date_create($request->input('to')))->get();
            if ($events->count() > 0) {
                foreach ($events as $event) {
                    $patients = $event->patientProfiles;
                    $event->delete();
                    foreach ($patients as $patient) {
                        $data = array(
                            'email' => $patient->profile->user->email,
                            'fullname' => $patient->profile->user->name . ' ' . $patient->profile->user->lastname
                        );

                        Mail::send('external.prof_deleted', [
                            'user' => $user,
                            'event' => $event
                        ], function ($message) use ($data) {
                            $message->to($data['email'], $data['fullname'])->subject('Consulta2 | Turno cancelado');
                            $message->from('sistema@consulta2.com', 'Consulta2');
                        });
                    }
                }
            }
            $non = new NonWorkableDay();
            $non->concept = $request->concept != "" ? $request->concept : "Dado de baja por medio de panel de sesiones y consultas.";
            $non->from = $request->from;
            $non->to = $request->to;
            $non->professional_profile_id = $user->profile->professionalProfile->id;
            $non->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors('Error al dar de baja los turnos.');
        }
        
        return back();
    }

    public function delete(Request $request)
    {

        $event = CalendarEvent::find($request->id);
        $patients = $event->patientProfiles;
        $user = User::find(Auth::user()->id);
        $event->delete();
        if ($user->hasRole('Professional')) {
            foreach ($patients as $patient) {
                $data = array(
                    'email' => $patient->profile->user->email,
                    'fullname' => $patient->profile->user->name . ' ' . $patient->profile->user->lastname
                );

                Mail::send('external.prof_deleted', [
                    'user' => $user,
                    'event' => $event
                ], function ($message) use ($data) {
                    $message->to($data['email'], $data['fullname'])->subject('Consulta2 | Turno cancelado');
                    $message->from('sistema@consulta2.com', 'Consulta2');
                });
                return redirect('/cite');
            }
        }

        return redirect('/profile/events')->withStatus('Turno cancelado.');
    }

    public function externalCancel(Request $request)
    {
        $event = CalendarEvent::find(decrypt($request->id));
        return view('external.confirm_deletion')->with(['event' => $event]);
    }

    public function externalDelete(Request $request)
    {
        $event = CalendarEvent::find($request->id)->delete();
        return view('external.deleted');
    }
}
