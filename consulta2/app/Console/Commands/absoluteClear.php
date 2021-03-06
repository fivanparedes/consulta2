<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class absoluteClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta2:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresca toda la app';

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
        Artisan::call('cache:clear');
        echo "Limpiando cache\n";
        Artisan::call('view:clear');
        echo "Limpiando vistas\n";
        Artisan::call('config:clear');
        echo "Limpiando config\n";
        Artisan::call('route:clear');
        echo "Limpiando rutas\n";
        Artisan::call('cache:clear');
        echo "Limpiando cache\n";
    }
}
