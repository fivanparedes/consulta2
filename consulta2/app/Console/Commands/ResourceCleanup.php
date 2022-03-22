<?php

namespace App\Console\Commands;

use App\Models\CalendarEvent;
use App\Models\Cite;
use App\Models\NonWorkableDay;
use Illuminate\Console\Command;

class ResourceCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta2:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Da por cerrados los turnos viejos que aún no se cerraron, elimina los días laborables cuya fecha ya pasó.';

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
        $calendarEvents = CalendarEvent::where('active', true)->where('start', '<=', date('Y-m-d', strtotime(now(). "-2 day")))->get();
        foreach ($calendarEvents as $calendarEvent) {
            if ($calendarEvent->cite != null) {
                if ($calendarEvent->cite->resume == null) {
                    $calendarEvent->cite->assisted = true;
                    $calendarEvent->cite->covered = true;
                    $calendarEvent->cite->paid = true;
                    $calendarEvent->cite->resume = encrypt("Consulta ambulatoria.");
                    $calendarEvent->cite->save();
                }
            }
            
            $calendarEvent->approved = true;
            $calendarEvent->confirmed = true;
            $calendarEvent->gid = null;
            $calendarEvent->save();
        }

        /**
         * Borra dias no laborables. Esperar a la respuesta del profe...
         */
        $nonworkabledays = NonWorkableDay::where('to','<=', date('Y-m-d', strtotime(now(). "-2 day")))->get();
        foreach ($nonworkabledays as $nonworkableday) {
            $nonworkableday->delete();
        }
    }
}
