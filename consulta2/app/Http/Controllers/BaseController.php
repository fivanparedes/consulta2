<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    public function __construct()
    {
        $companyLogo =  DB::table('settings')->where('name', 'company-logo')->first(['value']);
        $companyLogo = $companyLogo->value;
        $companyName = DB::table('settings')->where('name', 'company-name')->first(['value']);
        $companyName = $companyName->value;
        View::share(['companyLogo' => $companyLogo, 'companyName' => $companyName]);
    }
}
