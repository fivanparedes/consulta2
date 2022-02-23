<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class sendReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta2:sendsimplereminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía correos con un día de anticipación con respecto al día pactado.';

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
        $reminders = Reminder::whereNull('sent')->get();
        foreach ($reminders as $reminder) {
            $startdate = date_create($reminder->calendarEvent->start);
            $remindate = date_sub($startdate,date_interval_create_from_date_string("1 days"));
            if (date_create('now') >= $remindate && date_create('now') <= $startdate) {
                foreach ($reminder->calendarEvent->patientProfiles as $patient) {
                    try {
                        $data = array(
                            'fullname' => $patient->profile->user->name . ' ' . $patient->profile->user->lastname,
                            'email' => $patient->profile->user->email
                        );
                        Mail::send('external.autoremind', [
                            'event' => $reminder->calendarEvent,
                            'reminder' => $reminder,
                            'patient' => $patient
                        ], function ($message) use ($data) {
                            $message->to($data['email'], $data['fullname'])->subject('Consulta2 | Recordatorio de turno para el día ');
                            $message->from('sistema@consulta2.com', 'Consulta2');
                        });
                        echo "Enviado un email a ". $data['email'] . "\n";

                    } catch (\Throwable $th) {
                        echo "Hubo error al enviar correo. " . $th->getMessage() . "\n";
                    }
                }
                $reminder->update([
                    'sent' => now()
                ]);
                $reminder->save();
                echo "Enviado...\n";
            }
        }
    }
}