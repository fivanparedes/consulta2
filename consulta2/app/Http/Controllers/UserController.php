<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Models\CalendarEvent;
use App\Models\PatientProfile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        return view('users.index', ['users' => $model->paginate(15)]);
    }

    public function listAtendees()
    {
        $patients = new Collection;
        $events = CalendarEvent::where('professional_profile_id', Auth::user()->profile->professionalProfile->id)->get();
        foreach ($events as $event) {
            if ($event->patientProfiles->count() > 0) {
                foreach ($event->patientProfiles as $profile) {
                   $patients->push($profile);
                }
                
            }
        }
        return view('professionals.users')->with(['patients' => $patients]);
    }
}