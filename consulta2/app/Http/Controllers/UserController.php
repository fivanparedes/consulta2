<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CalendarEvent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

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
                    if (!$patients->contains($profile)) {
                        $patients->push($profile);
                    }
                   
                }
                
            }
        }
        return view('professionals.users')->with(['patients' => $patients]);
    }

    public function searchByDni(Request $request) {
        if ($request->ajax()) {
            $user = User::where('dni', $request->dni)->first();
            if (isset($user)) {
                return response()->json([
                    'status' => 'success',
                    'fullname' => $user->name . ' ' . $user->lastname,
                    'dni' => $user->dni,
                    'address' => $user->profile->address,
                    'phone' => $user->profile->phone,
                    'coverage' => $user->profile->patientProfile->lifesheet->coverage->name
                ]);
            } else {
                return response()->json([
                    'status' => 'error'
                ]);
            }
        }
        
    }
}