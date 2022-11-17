<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $companyLogo =  DB::table('settings')->where('name', 'company-logo')->first(['value']);
        $companyLogo = $companyLogo->value;
        $companyName = DB::table('settings')->where('name', 'company-name')->first(['value']);
        $companyName = $companyName->value;
        } catch (Throwable $th) {
            $companyLogo = "consulta2.png";
            $companyName = "Consulta2";
        }
        
        view()->share(['companyLogo' => $companyLogo, 'companyName' => $companyName]);
        Paginator::defaultView('vendor.pagination.bootstrap-4');
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-4');
    }
}
