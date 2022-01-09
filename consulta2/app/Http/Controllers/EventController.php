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
                $professional = ProfessionalProfile::find($request->input('profid'));
                $hours = $professional->businessHours;
                $consult = ConsultType::find($request->consultid);
                $arr = array();
                $dayturns = CalendarEvent::where('start', 'like', '%' . $request->currentdate . '%')->get();
                foreach ($hours as $hour) {
                    $datestring = $request->currentdate . ' ' . $hour->time;
                    $date = date_create($datestring);
                    $days = explode(";", $consult->availability);
                    if (!$dayturns->contains('start', $datestring) && in_array($date->format('N'), $days)) {
                        if ($consult->businessHours->contains($hour)) {
                            array_push($arr, substr($hour->time, 0, 2) .'.'.substr($hour->time,3,2));
                        }
                    }
                }

                $copayment = 0;
                $practices = $consult->practices;
                foreach ($practices as $practice) {
                    if (Auth::user()->profile->patientProfile->lifesheet->coverage == $practice->coverage) {
                        $copayment = $practice->price->copayment;
                        return response()->json([
                            "content" => $arr,
                            "covered" => 1,
                            "requiresAuth" => $consult->requires_auth,
                            "practice" => $practice->id,
                            "price" => $practice->price->price,
                            "copayment" => $copayment
                        ]);
                    }
                }

                if (count($arr) == 0) {
                    return response()->json([
                        "content" => "empty",
                        "dayturns" => count($dayturns),
                        "datestring" => $datestring
                    ]);
                } else {
                    return response()->json([
                        "content" => $arr,
                        "requiresAuth" => $consult->requires_auth,
                        "price" => $copayment
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
        $hour = explode('.', $request->time);
        $selectedDate->setTime($hour[0], $hour[1], 0, 0);
        $_professional = ProfessionalProfile::find($request->input('profid'));
        $_consult_type = ConsultType::find($request->input('consult-type'));
        $practice = $_consult_type->practices->where('coverage_id', Auth::user()->profile->patientProfile->lifesheet->coverage_id)->first();
        if (!isset($practice)) {
            $practice = $_consult_type->practices->where('coverage_id', 1)->first();
        }
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
        if ($user->hasRole('Professional')) {
            $user = User::where('dni', $request->input('dni'))->first();
            $_practice = Practice::find($request->input('practice'));
        } else {
            if (!$user->isAbleTo('_consulta2_patient_profile_perm')) {
                return abort(403);
            }
        }
        

        try {
            DB::beginTransaction();
            $selectedDate = $request->input('date');

            $_consult_type = null;
            if (!isset($_practice)) {
                $_consult_type = ConsultType::find($request->input('consult-type'));
                $_practice = $_consult_type->practices->where('coverage_id', $user->profile->patientProfile->lifesheet->coverage_id)->first();
            } else {
                $_consult_type = ConsultType::where('name', $request->input('consult-type'))->first();
            }
            
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
        if (Auth::user()->hasRole('Patient')) {
            return redirect('/professionals/list')->with(['justregistered' => true]);
        } else {
            return back()->withStatus('Turno registrado.');
        }
        
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
