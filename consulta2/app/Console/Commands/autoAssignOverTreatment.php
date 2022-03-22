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
     * @return array array de fechas
     */
    private function findAvailableTime($time, $days, $month): array
    {
        $arr = explode(';', $days);
        $chosendates = array();
        echo "Hora elegida: " . $time . "\n";
        foreach ($arr as $day) {
            switch ($day) {
                case '1':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->format('Y'), date_create($month)->format('m'), "Monday") as $key => $value) {
                        $chosendate = $value->format('Y-m-d') . ' ' . $time;
                        $event = CalendarEvent::where('active', true)->where('start','>=', date_create($chosendate))->exists();
                        if (!$event) {
                            array_push($chosendates, $chosendate);
                        }
                    }
                    break;
                case '2':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->format('Y'), date_create($month)->format('m'), "Tuesday") as $key => $value) {
                        $chosendate = $value->format('Y-m-d') . ' ' . $time;
                        $event = CalendarEvent::where('active', true)->where('start','>=', date_create($chosendate))->exists();
                        if (!$event) {
                            array_push($chosendates, $chosendate);
                        }
                    }
                    break;
                case '3':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->format('Y'), date_create($month)->format('m'), "Wednesday") as $key => $value) {
                        $chosendate = $value->format('Y-m-d') . ' ' . $time;
                        $event = CalendarEvent::where('active', true)->where('start','>=', date_create($chosendate))->where('end', '<', date_create($chosendate))->exists();
                        if (!$event) {
                            array_push($chosendates, $chosendate);
                        }
                    }
                    break;
                case '4':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->format('Y'), date_create($month)->format('m'), "Thursday") as $key => $value) {
                        $chosendate = $value->format('Y-m-d') . ' ' . $time;
                        $event = CalendarEvent::where('active', true)->where('start','>=', date_create($chosendate))->where('end', '<', date_create($chosendate))->exists();
                        if (!$event) {
                            array_push($chosendates, $chosendate);
                        }
                    }
                    break;
                case '5':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->format('Y'), date_create($month)->format('m'), "Friday") as $key => $value) {
                        $chosendate = $value->format('Y-m-d') . ' ' . $time;
                        $event = CalendarEvent::where('active', true)->where('start','>=', date_create($chosendate))->where('end', '<', date_create($chosendate))->exists();
                        if (!$event) {
                            array_push($chosendates, $chosendate);
                        }
                    }
                    break;
                case '6':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->format('Y'), date_create($month)->format('m'), "Saturday") as $key => $value) {
                        $chosendate = $value->format('Y-m-d') . ' ' . $time;
                        $event = CalendarEvent::where('active', true)->where('start','>=', date_create($chosendate))->where('end', '<', date_create($chosendate))->exists();
                        if (!$event) {
                            array_push($chosendates, $chosendate);
                        }
                    }
                    break;
                case '7':
                    # code...
                    foreach ($this->getAllDaysInAMonth(date_create($month)->format('Y'), date_create($month)->format('m'), "Sunday") as $key => $value) {
                        $chosendate = $value->format('Y-m-d') . ' ' . $time;
                        $event = CalendarEvent::where('active', true)->where('start', '>=',date_create($chosendate))->where('end', '<', date_create($chosendate))->exists();
                        if (!$event) {
                            array_push($chosendates, $chosendate);
                        }
                    }
                    break;

                default:
                    # code...
                    break;
            }
        }
        sort($chosendates);
        return $chosendates;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $treatments = Treatment::where('start', '<', now())->where('end', '>', now())->get();
        if ($treatments->count() > 0) {
            foreach ($treatments as $treatment) {
                echo "Encontrado tratamiento N°" . $treatment->id . " correspondiente al paciente " . $treatment->medicalHistory->patientProfile->getFullName() . "\n";
                $thisMonth = now()->format('M-Y');
                echo "Mes actual: " . $thisMonth . "\n";
                $thisMonthCites = new Collection();
                if (count($treatment->cites) > 0) {
                    echo "Existen ". count($treatment->cites)." consultas hechas. \n";
                    foreach ($treatment->cites as $cite) {
                    $citeTime = date_create($cite->calendarEvent->start)->format('M-Y');
                    
                    if ($citeTime == $thisMonth) {
                        $thisMonthCites->push($cite);
                    }
                }
                }
                
                if (count($thisMonthCites) == 0) {
                    try {
                        DB::beginTransaction();
                        /**
                         * Copypaste directo del EventController
                         * TODO: ITERAR TANTAS VECES COMO times_per_month HAYA
                         */

                        $_consult_type = ConsultType::where('name', $treatment->description)->first();
                        $_practice = $_consult_type->practices->where('coverage_id', $treatment->medicalHistory->patientProfile->lifesheet->coverage_id)->first();
                        $datearray = $this->findAvailableTime($treatment->preferred_hour, $treatment->preferred_days, $thisMonth);
                        $i = 0;
                        foreach ($datearray as $date) {
                            if ($i == $treatment->times_per_month) {
                                break;
                            }
                            echo "Fecha calculada: " . $date . "\n";
                            $selectedDate = $date;
                            $currentDate = strtotime($selectedDate);
                            $futureDate = $currentDate + (60 * $_practice->maxtime);
                            $formatDate = date("Y-m-d H:i:s", $futureDate);
                            echo "Hora de atención: " . $date . "\n";
                            echo "Creando evento en la BBDD...\n";
                            $_event = new CalendarEvent([
                                'title' => $treatment->medicalHistory->patientProfile->profile->user->name . ' ' . $treatment->medicalHistory->patientProfile->profile->user->lastname,
                                'start' => $selectedDate,
                                'end' => $formatDate,
                                'approved' => 1,
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
                            echo "Creando consulta asociada...\n";
                            $_cite = new Cite([
                                'assisted' => false,
                                'covered' => $covered,
                                'paid' => false
                            ]);
                            $_cite->calendarEvent()->associate($_event);
                            $_cite->practice()->associate($_practice);
                            $_cite->medicalHistory()->associate($treatment->medicalHistory);
                            $_cite->treatment()->associate($treatment);

                            $_cite->save();
                            $companyName = DB::table('settings')->where('name', 'company-name')->first(['value']);
                            $user = $treatment->medicalHistory->patientProfile->profile->user;
                            Mail::send('external.created', [
                                'user' => $user,
                                'event' => $_event
                            ], function ($message) use ($user, $companyName) {
                                $message->to($user->email, $user->name . ' ' . $user->lastname)->subject($companyName->value.' | Turno pendiente de aprobación');
                                $message->from('sistema@consulta2.com', $companyName->value);
                            });
                            $reminder = new Reminder();
                            $reminder->calendarEvent()->associate($_event);
                            $reminder->user()->associate($user);
                            $reminder->save();
                            $i += 1;
                        }


                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        throw $th;
                    }
                } else {
                    echo "Ya se crearon los turnos para este mes. Saltando...\n";
                }
            }
        }
    }
}
