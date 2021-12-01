<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laratrust\Checkers\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('Patient')) {
            return redirect('/professionals/list');
        } elseif ($user->hasRole('Professional')) {
            return redirect('/cite');
        } elseif ($user->hasRole('Admin')) {
            return redirect('/admin/professionals');
        }
        
    }
}