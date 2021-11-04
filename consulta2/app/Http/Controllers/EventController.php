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

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user_profile = Profile::where('user_id', auth()->user()->id)->first();
        $patient_profile = PatientProfile::where('profile_id', $user_profile->id)->first();
        $professional_profile = ProfessionalProfile::where('profile_id', $user_profile->id)->first();
        if($request->ajax()) {
            if ($patient_profile != null) {
                // TODO: order query for patient users
                $userdata = CalendarEvent::where('user_id', auth()->user()->id)
                ->whereDate('start', '>=', $request->start)
                ->whereDate('end',   '<=', $request->end)
                ->get(['id', 'title','start', 'end']);

                $forbidata = CalendarEvent::where('user_id', '<>', auth()->user()->id)
                ->whereDate('start', '>=', $request->start)
                ->whereDate('end',   '<=', $request->end)
                ->get(['id','start', 'end']);
                $data = $userdata->concat($forbidata);  //Concat both the user events and the foreign events, but without revealing their identity.
            } else if($professional_profile != null) {
                $data = CalendarEvent::whereDate('start', '>=', $request->start)
                ->whereDate('end',   '<=', $request->end)
                ->get(['id', 'title', 'start', 'end']);
            }
            
            return response()->json($data);
        }
        return view('calendar');
    }
    
    public function showAvailableEvents(Request $request) {
        if ($request->ajax()) {
            $professional = ProfessionalProfile::where('id', $request->profid)->first();
            $hours = $professional->businessHours;
            $dayturns = CalendarEvent::where('start', 'like', '%'.$request->currendate.'%')->get();
            foreach ($hours as $hour) {
                $date = $request->currentdate . ' ' . $hour->time;

            }
        }
    }

    public function manageEvents(Request $request)
    {
        $user = User::find(auth()->user()->id);
        switch ($request->type) {
           case 'create':
              $calendarEvent = new CalendarEvent([
                  'title' => auth()->user()->name.' '.auth()->user()->lastname,
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

    /* * Function to store new Events via the new system
        * */
    public function store(Request $request) {
            $_event = new CalendarEvent([
                'start' => $request->input('time'),
                'end' => $request->end,
                'approved' => true,
                'confirmed' => false,
                'isVirtual' => false
            ]);
            $_patient = PatientProfile::where('profile_id', Auth::user()->profile->id);
            $_event->patientProfile()->attach($_patient->id);

            $_consult_type = ConsultType::find($request->input('consult_type'));
            $_event->consultType()->associate($_consult_type);

            $_professional = ProfessionalProfile::find($request->input('profid'));
            $_event->professionalProfile()->associate($_professional);
            
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

            $_practice = Practice::where('consult_type_id', $request->input('consult_type'))->first();

            $_cite->practice()->associate($_practice);
            $_event->save();

            return redirect('/professionals/list');
    }
}