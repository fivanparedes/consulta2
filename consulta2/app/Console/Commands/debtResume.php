<?php

namespace App\Console\Commands;

use App\Models\Coverage;
use App\Models\Reminder;
use App\Models\Treatment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class debtResume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta2:senddebtresumes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía a cada paciente partícipe de un tratamiento un resumen de las deudas que posea.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $treatments = Treatment::where('start', '<', now())->where('end', '>', now())->get();
        if (count($treatments) > 0) {
            foreach ($treatments as $treatment) {
                $cites = $treatment->cites->whereNotNull('resume')->where('paid', false)->get();
                $citecount = count($cites);
                switch ($citecount) {
                    case 0:
                        echo "No hay consultas impagas en este tratamiento.\n";
                        break;
                    case 1:
                        echo "Deudor debe 1 consulta. Enviando recordatorio...";
                        $cite = $cites->first();
                        $data = array(
                            'fullname' => $treatment->medicalHistory->patientProfile->getFullName(),
                            'email' => $treatment->medicalHistory->patientProfile->profile->user->email
                        );

                        $reminder = new Reminder();
                        $reminder->calendar_event_id = $cite->calendar_event_id;
                        $reminder->sent = now();
                        $reminder->user_id = $treatment->medicalHistory->patientProfile->profile->user->id;
                        $reminder->save();

                        Mail::send('external.debtremind', [
                            'event' => $cite->calendarEvent,
                            'reminder' => $reminder,
                            'patient' => $treatment->medicalHistory->patientProfile
                        ], function ($message) use ($data) {
                            $message->to($data['email'], $data['fullname'])->subject('Consulta2 | Recordatorio de deuda ');
                            $message->from('sistema@consulta2.com', 'Consulta2');
                        });
                        echo "Enviado un email a " . $data['email'] . "\n";
                        break;
                    case 2:
                        echo "Deudor debe 2 consultas. Calculando total a pagar...\n";
                        $total = 0; 
                        foreach ($cites as $cite) {
                            $debt = $cite->practice->price->price;
                            $total = (float)$debt + (float)($debt * 0.1); 
                        }
                        echo "Debe $". (string)$total."\n";

                        $data = array(
                            'fullname' => $treatment->medicalHistory->patientProfile->getFullName(),
                            'email' => $treatment->medicalHistory->patientProfile->profile->user->email
                        );
                        $cite = $cites->first();
                        Mail::send('external.debtremind', [
                            'event' => $cite->calendarEvent,
                            'debt' => $total,
                            'patient' => $treatment->medicalHistory->patientProfile
                        ], function ($message) use ($data) {
                            $message->to($data['email'], $data['fullname'])->subject('Consulta2 | Recordatorio de deuda ');
                            $message->from('sistema@consulta2.com', 'Consulta2');
                        });
                        echo "Enviado un email a " . $data['email'] . "\n";
                        break;
                    case 3:
                        echo "Deudor debe 3 consultas. Deshabilitando tratamiento. Volver a habilitar por medio del panel de edición...\n";
                        $treatment->times_per_month = 0;
                        $treatment->save();
                        $total = 0;
                        foreach ($cites as $cite) {
                            $debt = $cite->practice->price->price;
                            $total = (float)$debt + (float)($debt * 0.1);
                        }
                        echo "Debe $" . (string)$total . "\n";
                        $cite = $cites->first();
                        $data = array(
                            'fullname' => $treatment->medicalHistory->patientProfile->getFullName(),
                            'email' => $treatment->medicalHistory->patientProfile->profile->user->email
                        );
                        Mail::send('external.debtremind', [
                            'event' => $cite->calendarEvent,
                            'debt' => $total,
                            'patient' => $treatment->medicalHistory->patientProfile,
                            'disabling' => true
                        ], function ($message) use ($data) {
                            $message->to($data['email'], $data['fullname'])->subject('Consulta2 | Recordatorio de deuda ');
                            $message->from('sistema@consulta2.com', 'Consulta2');
                        });
                        echo "Enviado un email a " . $data['email'] . "\n";
                        break;
                    default:
                        # code...
                        break;
                }
            }
        } else {
            echo "No hay tratamientos para procesar.\n";
        }
    }
}
