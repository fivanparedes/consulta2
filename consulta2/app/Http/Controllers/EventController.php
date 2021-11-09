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
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
            $profuser = User::find($request->profid);
            $professional = $profuser->profile->professionalProfile;
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
                        array_push($arr, $hour->time);
                    }
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
                    "content" => $arr
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
        $selectedDate = $request->input('date') . ' ' . $request->input('time');
        $_prof = User::find($request->input('profid'));
        $_professional = $_prof->profile->professionalProfile;
        $_consult_type = ConsultType::find($request->input('consult-type'));
        return view('events.confirm')->with([
            'professional' => $_professional,
            'selectedDate' => $selectedDate,
            'consult_type' => $_consult_type
        ]);
    }

    /* * Function to store new Events via the new system
        * */
    public function store(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if (!$user->isAbleTo('_consulta2_patient_profile_perm')) {
            return abort(403);
        }
        $selectedDate = $request->input('date');
        $_consult_type = ConsultType::find($request->input('consult-type'));

        $_practice = $_consult_type->practices->first();
        $currentDate = strtotime($selectedDate);
        $futureDate = $currentDate + (60 * $_practice->maxtime);
        $formatDate = date("Y-m-d H:i:s", $futureDate);
        $_event = new CalendarEvent([
            'start' => $selectedDate,
            'end' => $formatDate,
            'approved' => true,
            'confirmed' => false,
            'isVirtual' => false,
        ]);
        $_patient = PatientProfile::where('profile_id', Auth::user()->profile->id)->first();
        $_patient->calendarEvents()->attach($_event->id);

        $_event->consultType()->associate($_consult_type);

        $profuser = User::find($request->profid);
        $professional = $profuser->profile->professionalProfile;
        $_event->professionalProfile()->associate($professional);

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
        $_event->save();

        return redirect('/professionals/list')->with(['justregistered' => true]);
    }
}