<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use App\Models\Cite;
use App\Models\ConsultType;
use App\Models\MedicalHistory;
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
use DateTimeZone;
use Google\Service\Calendar\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;
use Barryvdh\DomPDF\Facade as PDF;
class EventController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = User::find(auth()->user()->id);
            $data = array();
            if ($user->isAbleTo('patient-profile')) {
                foreach ($user->profile->patientProfile->calendarEvents->where('active', true) as $event) {
                    $obj = [
                        "id" => $event->id,
                        "start" => $event->start,
                        "end" => $event->end,
                        "title" => $event->professionalProfile->getFullName()
                    ];
                    array_push($data, $obj);
                }

            } elseif ($user->isAbleTo('professional-profile')) {
                foreach($user->profile->professionalProfile->calendarEvents->where('active', true) as $event) {
                    $obj = [
                        "id" => $event->id,
                        "start" => $event->start,
                        "end" => $event->end,
                        "title" => $event->patientProfiles->first()->getFullName()
                    ];
                    array_push($data, $obj);
                }
            }

            return response()->json($data);
        }
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
                $dayturns = CalendarEvent::where('active', true)->where('professional_profile_id', $professional->id)->where('start', 'like', '%' . $request->currentdate . '%')->get();
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
        $total = $request->price;
        return view('events.confirm')->with([
            'professional' => $_professional,
            'selectedDate' => $selectedDate->format('d/m/Y H:i'),
            'consult_type' => $_consult_type,
            'isVirtual' => $request->input('isVirtual'),
            'practice' => $practice,
            'total' => $total
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
            
        } 
        $profuser = User::find($request->profid);
        $professional = $profuser->profile->professionalProfile;

        try {
            DB::beginTransaction();
            $selectedDate = $request->input('date');
            
            $_practice = Practice::find($request->input('practice-id'));
            $_consult_type = ConsultType::find($request->input('consult-type'));
            //dd(date_create_from_format('d/m/Y H:i', $selectedDate)->format('Y-m-d h:i:s'));
            $partialdate = date_create_from_format('d/m/Y H:i', $selectedDate)->format('Y-m-d h:i:s');
            $carbonDate = new Carbon($partialdate, new DateTimeZone("-0300"));
            //$currentDate = date($selectedDate);
            //dd($carbonDate);
            $futureDate = $carbonDate;
            $futureDate = $futureDate->addHour();
            $_event = new CalendarEvent([
                'title' => $user->name . ' ' . $user->lastname,
                'start' => new Carbon($partialdate, new DateTimeZone("-0300")),
                'end' => $futureDate->setTimezone("-0300"),
                'approved' => $_consult_type->requires_auth ? 0 : 1,
                'confirmed' => false,
                'isVirtual' => boolval($request->input('isVirtual')),
                'active' => true
            ]);
            $_patient = PatientProfile::where('profile_id', $user->profile->id)->first();


            $_event->consultType()->associate($_consult_type);

            
            $_event->professionalProfile()->associate($professional);
            $_event->save();
            $_event->patientProfiles()->attach($_patient->id);
            $_event->save();
            $covered = true;
            if ($_patient->lifesheet->coverage->id == 1) {
                $covered = false;
            }

            $medical_history = $_patient->medicalHistories->where('professional_profile_id', $professional->id)->first();
            if ($medical_history == null) {
                $medical_history = new MedicalHistory();
                $medical_history->indate = $selectedDate;
                $medical_history->patient_profile_id = $_patient->id;
                $medical_history->professional_profile_id = $professional->id;
                $medical_history->save();
            }
            $_cite = new Cite();

            $_cite->assisted = false;
            $_cite->covered = $covered;
            $_cite->paid = false;
            $_cite->total = $request->total;
            $_cite->calendarEvent()->associate($_event);
            $_cite->practice()->associate($_practice);
            $_cite->medicalHistory()->associate($medical_history);
            $_cite->save();

            $_cite->save();

            
            $gevent = new GoogleCalendarEvent();
            $gevent->name = $_consult_type->name;
            $gevent->description = 'Turno agendado por medio de Consulta2.';
            $gevent->startDateTime = new Carbon($partialdate, new DateTimeZone("-0300"));;
            $gevent->endDateTime = $futureDate->setTimezone("-0300");
            $gevent->addAttendee([
                'email' => $_patient->profile->user->email,
                'name' => $_patient->profile->user->name . ' ' . $_patient->profile->user->lastname,
            ]);
            $gevent->addAttendee(['email' => $professional->profile->user->email]);

            $gevent->save();

            if ($_consult_type->requires_auth != 0) {
                Mail::send('external.created', [
                    'user' => $user,
                    'event' => $_event
                ], function ($message) use ($user){
                    $companyName = DB::table('settings')->where('name', 'company-name')->first(['value']);
                    $companyName = $companyName->value;
                    $message->to($user->email, $user->name . ' ' . $user->lastname)->subject($companyName->value.' | Turno pendiente de aprobación');
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
                    $companyName = DB::table('settings')->where('name', 'company-name')->first(['value']);
                    $message->to($user->email, $user->name . ' ' . $user->lastname)->subject($companyName->value.' | Turno agendado exitosamente');
                    $message->from('sistema@consulta2.com', 'Consulta2');
                });
            }

            DB::commit();
            $gevents = GoogleCalendarEvent::get();
            foreach ($gevents as $item) {
                if ($item->startDateTime == new Carbon($_event->start)) {
                    $_event->gid = $item->id;
                    $_event->save();
                    break;
                }
            }
            if ($user->hasRole('Patient')) {
                return redirect()->route('events.success', ['event' => $_event]);
            } else {
                return back()->withStatus('Turno registrado.');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return back()->withErrors('turno', 'Error al agendar turno, intente de nuevo');
        }
        
        
    }

    public function success(CalendarEvent $event) {
        $gevent = GoogleCalendarEvent::find($event->gid);
        return view('events.success')->with(['event' => $event, 'gevent' => $gevent]);
    }

    public function massCancel(Request $request) {
        $user = User::find(auth()->user()->id);
        if (!$user->hasRole('Professional')) {
            return abort(404);
        }
        try {
            DB::beginTransaction();
            $user = User::find(auth()->user()->id);
            $events = CalendarEvent::where('active', true)->where('professional_profile_id', $user->profile->professionalProfile->id)
                ->where('start', '>=', date_create($request->from))->where('start', '<=', date_create($request->input('to')))->get();
            if ($events->count() > 0) {
                foreach ($events as $event) {
                    $patients = $event->patientProfiles;
                    $event->active = false;
                    $event->save();
                    foreach ($patients as $patient) {
                        $data = array(
                            'email' => $patient->profile->user->email,
                            'fullname' => $patient->profile->user->name . ' ' . $patient->profile->user->lastname
                        );

                        Mail::send('external.prof_deleted', [
                            'user' => $user,
                            'event' => $event
                        ], function ($message) use ($data) {
                            $companyName = DB::table('settings')->where('name', 'company-name')->first(['value']);
                            $message->to($data['email'], $data['fullname'])->subject($companyName->value.' | Turno cancelado');
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
        DB::beginTransaction();
        try {
            $event = CalendarEvent::find($request->id);
            if ($event->gid != null) {
                $gevent = \Spatie\GoogleCalendar\Event::find($event->gid);
                if (isset($gevent)) {
                    $gevent->delete();
                }
            }
            $patients = $event->patientProfiles;
            $user = User::find(Auth::user()->id);
            $event->active = false;
            $event->save();
            
            DB::commit();
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
                        $companyName = DB::table('settings')->where('name', 'company-name')->first(['value']);
                        $message->to($data['email'], $data['fullname'])->subject($companyName->value.' | Turno cancelado');
                        $message->from('sistema@consulta2.com', 'Consulta2');
                    });
                    return redirect('/cite');
                }
            }
        } catch (\Throwable $th) {
            dd($th);
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
        $event = CalendarEvent::find($request->id);
        $event->active = false;
        $event->save();
        return view('external.deleted');
    }

    public function createTicket(CalendarEvent $event, $id) {
        $event = CalendarEvent::find($id);
        $companyLogo = DB::table('settings')->where('name', 'company-logo')->first(['value']);
        $pdf = PDF::loadView('events.pdf', ['event' => $event, 'companyLogo' => $companyLogo->value]);
        return $pdf->download('comprobante_turno_agendado'.$event->id.'.pdf');
    }
}
