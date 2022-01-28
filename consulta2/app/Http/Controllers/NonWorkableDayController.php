<?php

namespace App\Http\Controllers;

use App\Models\NonWorkableDay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NonWorkableDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if ($user->hasRole('Professional')) {
            $nonworkabledays = NonWorkableDay::where('professional_profile_id', $user->profile->professionalProfile->id);
            if ($request->has('filter1') && $request->filter1 != "") {
                $nonworkabledays = $nonworkabledays->where('from', '<=', date_create($request->filter1))
                    ->orWhere('to', '>=', date_create($request->filter1));
            }

            $nonworkabledays = $nonworkabledays->sortable()->paginate(10);
            return view('non_workable_days.index')->with(['nonworkabledays' => $nonworkabledays]);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NonWorkableDay  $nonWorkableDay
     * @return \Illuminate\Http\Response
     */
    public function show(NonWorkableDay $nonWorkableDay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NonWorkableDay  $nonWorkableDay
     * @return \Illuminate\Http\Response
     */
    public function edit(NonWorkableDay $nonWorkableDay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NonWorkableDay  $nonWorkableDay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NonWorkableDay $nonWorkableDay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NonWorkableDay  $nonWorkableDay
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        NonWorkableDay::destroy($id);
        return back();
    }
}
