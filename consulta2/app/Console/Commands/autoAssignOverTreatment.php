<?php

namespace App\Console\Commands;

use App\Models\CalendarEvent;
use App\Models\Cite;
use App\Models\ConsultType;
use App\Models\Practice;
use App\Models\Reminder;
use App\Models\Treatment;
use Cron\DayOfMonthField;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class autoAssignOverTreatment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta2:autoassign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asigna turnos en base a tratamientos existentes, asigna turnos en caso de que no estén todos los asignados por mes.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    function getAllDaysInAMonth($year, $month, $day = 'Monday', $daysError = 3)
    {
        $dateString = 'first ' . $day . ' of ' . $year . '-' . $month;

        if (!strtotime($dateString)) {
            throw new Exception('"' . $dateString . '" is not a valid strtotime');
        }

        $startDay = new DateTime($dateString);

        if ($startDay->format('j') > $daysError) {
            $startDay->modify('- 7 days');
        }

        $days = array();

        while ($startDay->format('Y-m') <= $year . '-' . str_pad($month, 2, 0, STR_PAD_LEFT)) {
            $days[] = clone ($startDay);
            $startDay->modify('+ 7 days');
        }

        return $days;
    }

    /**
     * Busca inteligentemente una hora dada un tiempo de preferencia y dias de preferencia
     * @param mixed $time tiempo directamente sacado de la bbdd
     * @param mixed $days dias sacados directamente de la bbdd
     */
    private function findAvailableTime($time, $days, $month):string {
        $arr = explode(';', $days);
        foreach ($arr as $day) {
            switch ($day) {
                case '1':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->year, date_create($month)->month, "Monday") as $key => $value) {
                        $chosendate = $value . ' ' . $time;
                        $event = CalendarEvent::where('start', date_create($chosendate))->exists();
                        if (!$event) {
                            return $chosendate;
                        }
                    }
                    break;
                case '2':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->year, date_create($month)->month, "Tuesday") as $key => $value) {
                        $chosendate = $value . ' ' . $time;
                        $event = CalendarEvent::where('start', date_create($chosendate))->exists();
                        if (!$event) {
                            return $chosendate;
                        }
                    }
                    break;
                case '3':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->year, date_create($month)->month, "Wednesday") as $key => $value) {
                        $chosendate = $value . ' ' . $time;
                        $event = CalendarEvent::where('start', date_create($chosendate))->exists();
                        if (!$event) {
                            return $chosendate;
                        }
                    }
                    break;
                case '4':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->year, date_create($month)->month, "Thursday") as $key => $value) {
                        $chosendate = $value . ' ' . $time;
                        $event = CalendarEvent::where('start', date_create($chosendate))->exists();
                        if (!$event) {
                            return $chosendate;
                        }
                    }
                    break;
                case '5':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->year, date_create($month)->month, "Friday") as $key => $value) {
                        $chosendate = $value . ' ' . $time;
                        $event = CalendarEvent::where('start', date_create($chosendate))->exists();
                        if (!$event) {
                            return $chosendate;
                        }
                    }
                    break;
                case '6':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->year, date_create($month)->month, "Saturday") as $key => $value) {
                        $chosendate = $value . ' ' . $time;
                        $event = CalendarEvent::where('start', date_create($chosendate))->exists();
                        if (!$event) {
                            return $chosendate;
                        }
                    }
                    break;
                case '7':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->year, date_create($month)->month, "Sunday") as $key => $value) {
                        $chosendate = $value . ' ' . $time;
                        $event = CalendarEvent::where('start', date_create($chosendate))->exists();
                        if (!$event) {
                            return $chosendate;
                        }
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        }
        return "";
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $treatments = Treatment::where('start', '<', now())->where('end', '>', now())->get();
        foreach ($treatments as $treatment) {
            $thisMonth = now()->format('M-Y');
            $thisMonthCites = new Collection();
            foreach ($treatment->cites as $cite) {
                if (date_create($cite->calendarEvent->start)->format('M-Y') == $thisMonth) {
                    $thisMonthCites->push($cite);
                }
            }
            if (count($thisMonthCites) == 0) {
                try {
                    DB::beginTransaction();
                    /**
                     * Copypaste directo del EventController
                     * TODO: ITERAR TANTAS VECES COMO times_per_month HAYA
                     */
                    $_practice = Practice::where('name', $treatment->description)->first();
                    $_consult_type = ConsultType::whereHas('practices', function ($q) use ($_practice) {
                        $q->where('id', '=', $_practice->id);
                    })->first();
                    $selectedDate = $this->findAvailableTime($treatment->preferred_time, $treatment->preferred_days, $thisMonth);
                    $currentDate = strtotime($selectedDate);
                    $futureDate = $currentDate + (60 * $_practice->maxtime);
                    $formatDate = date("Y-m-d H:i:s", $futureDate);
                    $_event = new CalendarEvent([
                        'title' => $treatment->medicalHistory->patientProfile->profile->user->name . ' ' . $treatment->medicalHistory->patientProfile->profile->user->lastname,
                        'start' => $selectedDate,
                        'end' => $formatDate,
                        'approved' => 0,
                        'confirmed' => false,
                        'isVirtual' => false,
                    ]);
                    $_patient = $treatment->medicalHistory->patientProfile;


                    $_event->consultType()->associate($_consult_type);

                    
                    $professional = $treatment->medicalHistory->professionalProfile;
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

                    $user = $treatment->medicalHistory->patientProfile->profile->user;
                        Mail::send('external.created', [
                            'user' => $user,
                            'event' => $_event
                        ], function ($message) use ($user) {
                            $message->to($user->email, $user->name . ' ' . $user->lastname)->subject('Consulta2 | Turno pendiente de aprobación');
                            $message->from('sistema@consulta2.com', 'Consulta2');
                        });
                        $reminder = new Reminder();
                        $reminder->calendarEvent()->associate($_event);
                        $reminder->user()->associate($user);
                        $reminder->save();
                    
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    throw $th;
                }
            }
        }
    }
}
