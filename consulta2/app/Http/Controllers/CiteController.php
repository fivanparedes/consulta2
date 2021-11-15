<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Cite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $filter2 = $request->query('filter2');
        if (!empty($filter)) {
            if (!empty($filter2)) {
                $query = DB::table('calendar_events')
                    ->join('cites', 'calendar_events.id', '=', 'cites.calendar_event_id')
                    ->join('professional_profiles', 'calendar_events.professional_profile_id' ,'=', 'professional_profiles.id')
                    ->where('calendar_events.start', 'like', '%'.$filter.'%')
                    ->where('calendar_events.title', 'like', '%'.$filter2.'%')
                    ->where('calendar_events.professional_profile_id', Auth::user()->profile->professionalProfile->id)
                    ->get();
            } else {
                $query = DB::table('calendar_events')
                    ->join('cites', 'calendar_events.id', '=', 'cites.calendar_event_id')
                    ->join('professional_profiles', 'calendar_events.professional_profile_id' ,'=', 'professional_profiles.id')
                    ->where('calendar_events.start', 'like', '%'.$filter.'%')
                    ->where('calendar_events.professional_profile_id', Auth::user()->profile->professionalProfile->id)
                    ->get();
            }
        } else if (!empty($filter2)) {
            $query = DB::table('calendar_events')
            ->join('cites', 'calendar_events.id', '=', 'cites.calendar_event_id')
            ->join('professional_profiles', 'calendar_events.professional_profile_id' ,'=', 'professional_profiles.id')
            ->where('calendar_events.title', 'like', '%'.$filter2.'%')
            ->where('calendar_events.professional_profile_id', Auth::user()->profile->professionalProfile->id)
            ->get();
        } else {
            $query = DB::table('calendar_events')
            ->join('cites', 'calendar_events.id', '=', 'cites.calendar_event_id')
            ->join('professional_profiles', 'calendar_events.professional_profile_id' ,'=', 'professional_profiles.id')
            ->where('calendar_events.professional_profile_id', Auth::user()->profile->professionalProfile->id)
            ->get();
        }
        
        //dd($query);
        return view('pages.cites')->with([
            'filter' => $filter,
            'filter2' => $filter2,
            'cites' => $query,
            'professional' => Auth::user()->profile->professionalProfile
        ]);
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

    public function createPDF(Request $request) {
        $filter = $request->query('filter');
        $filter2 = $request->query('filter2');
        if (!empty($filter)) {
            if (!empty($filter2)) {
                $query = DB::table('calendar_events')
                    ->join('cites', 'calendar_events.id', '=', 'cites.calendar_event_id')
                    ->join('professional_profiles', 'calendar_events.professional_profile_id' ,'=', 'professional_profiles.id')
                    ->where('calendar_events.start', 'like', '%'.$filter.'%')
                    ->where('calendar_events.title', 'like', '%'.$filter2.'%')
                    ->where('calendar_events.professional_profile_id', auth()->user()->id)
                    ->get();
            } else {
                $query = DB::table('calendar_events')
                    ->join('cites', 'calendar_events.id', '=', 'cites.calendar_event_id')
                    ->join('professional_profiles', 'calendar_events.professional_profile_id' ,'=', 'professional_profiles.id')
                    ->where('calendar_events.start', 'like', '%'.$filter.'%')
                    ->where('calendar_events.professional_profile_id', auth()->user()->id)
                    ->get();
            }
        } else if (!empty($filter2)) {
            $query = DB::table('calendar_events')
            ->join('cites', 'calendar_events.id', '=', 'cites.calendar_event_id')
            ->join('professional_profiles', 'calendar_events.professional_profile_id' ,'=', 'professional_profiles.id')
            ->where('calendar_events.title', 'like', '%'.$filter2.'%')
            ->where('calendar_events.professional_profile_id', auth()->user()->id)
            ->get();
        } else {
            $query = DB::table('calendar_events')
            ->join('cites', 'calendar_events.id', '=', 'cites.calendar_event_id')
            ->join('professional_profiles', 'calendar_events.professional_profile_id' ,'=', auth()->user()->id)
            ->where('calendar_events.professional_profile_id', auth()->user()->id)
            ->get();
        }
        
        //dd($query);
        $view = view('pages.cites')->with([
            'filter' => $filter,
            'filter2' => $filter2,
            'cites' => $query
        ]);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView($view->render());

        // download PDF file with download method
        return $pdf->download('informe_sesiones.pdf');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cite  $Cite
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $cite = Cite::find($request->id);
        $calendarEvent = CalendarEvent::find($cite->calendar_event_id);
        return view('pages.showcite')->with([
            'cite' => $cite,
            'calendarEvent' => $calendarEvent]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cite  $Cite
     * @return \Illuminate\Http\Response
     */
    public function edit(Cite $cite)
    {
        //$cite = Cite::find($cite->id);
        $calendarEvent = CalendarEvent::find($cite->calendar_event_id);
        return view('pages.showcite')->with([
            'cite' => $cite,
            'calendarEvent' => $calendarEvent]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cite  $Cite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cite $cite)
    {
        $citeobj = Cite::find($request->id);
        
        $citeobj->update([
            'assisted' => $request->assisted,
            'isVirtual' => $request->isVirtual
        ]);
        $cite->update([
            'assisted' => $request->input('assisted'),
            'isVirtual' => $request->input('isVirtual')
        ]);

        $cite->save();
        $citeobj->save();
        return back()->withStatus(__('Datos de sesión actualizados.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cite  $Cite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cite $Cite)
    {
        //
    }
}