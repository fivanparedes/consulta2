<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $user = User::find(auth()->user()->id);
        if ($user->hasRole('Patient')) {
            return view('patients.welcome');
        } elseif ($user->hasRole('Professional')) {
            return redirect('/cite');
        } elseif ($user->hasRole('Institution')){
            return redirect('/manage/professionals');
        } elseif ($user->hasRole('Admin')) {
            return redirect('/manage/professionals');
        }
        
    }
}